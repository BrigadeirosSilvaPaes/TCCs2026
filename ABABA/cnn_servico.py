"""Servico da CNN (ressonancia) pro backend do ABABA

oq esse codigo faz basicamente
- carrega o modelo treinado no Treinar_modelo.py (modelo_otimizado2.keras)
- prepara as imagens do mesmo jeito que o treino preparou (128x128, tons de cinza)
- devolve a probabilidade media de cada classe e a P(demencia)
- combina a probabilidade da CNN com a do Random Forest

Variaveis de ambiente opcionais
- CNN_MODELO   caminho do .keras      (padrao: modelo_otimizado2.keras)
- CNN_CLASSES  caminho do .json       (padrao: classes_cnn.json)
- PESO_CNN     peso da CNN na fusao   (padrao: 0.5 -> RF e CNN com o mesmo peso)
"""

import json
import os
import shutil
import tempfile
import threading
from pathlib import Path

import numpy as np

_AQUI = Path(__file__).resolve().parent
CAMINHO_MODELO = os.environ.get('CNN_MODELO', 'modelo_otimizado2.keras')
CAMINHO_CLASSES = os.environ.get('CNN_CLASSES', 'classes_cnn.json')
TAMANHO_IMG = (128, 128)

CLASSES_PADRAO = ['MildDemented', 'ModerateDemented', 'NonDemented', 'VeryMildDemented']

ROTULOS_PT = {
    'nondemented': 'Sem demência',
    'verymilddemented': 'Demência muito leve',
    'milddemented': 'Demência leve',
    'moderatedemented': 'Demência moderada',
}

PESO_CNN = float(os.environ.get('PESO_CNN', '0.5'))
if not 0.0 <= PESO_CNN <= 1.0:
    raise ValueError('PESO_CNN precisa estar entre 0 e 1.')

_modelo = None
_classes = list(CLASSES_PADRAO)
_idx_saudavel = None
_tf = None
_lock = threading.Lock()


def _resolver(caminho):
    caminho = Path(caminho)
    return caminho if caminho.is_absolute() else _AQUI / caminho

ASSINATURA_HDF5 = b'\x89HDF\r\n\x1a\n'


def _preparar_arquivo_modelo(caminho):
    """
    Se o .keras for HDF5, abre uma copia temporaria com extensao .h5 pra
    qualquer versao do Keras conseguir carregar sem converter nem mexer no original
    """
    with open(caminho, 'rb') as f:
        assinatura = f.read(8)
    if assinatura == ASSINATURA_HDF5 and caminho.suffix.lower() not in ('.h5', '.hdf5'):
        pasta = tempfile.mkdtemp(prefix='cnn_')
        destino = Path(pasta) / 'modelo.h5'
        shutil.copyfile(caminho, destino)
        return destino, pasta
    return caminho, None


def _construir_arquitetura(tf):
    """Reconstroi em codigo a mesma rede do Treinar_modelo.py
    Plano B caso o load_model nao conseguir ler a configuracao salva a gente monta a rede aqui e carrega so os pesos do arquivo
    """
    L = tf.keras.layers
    blocos = []
    for filtros in (32, 64, 128):
        blocos += [
            L.Conv2D(filtros, (3, 3), padding='same'),
            L.BatchNormalization(),
            L.Activation('relu'),
            L.MaxPooling2D((2, 2)),
        ]
    return tf.keras.Sequential([
        L.Input(shape=(*TAMANHO_IMG, 1)),
        L.RandomFlip('horizontal'),
        L.RandomRotation(0.05),
        L.RandomZoom(0.05),
        L.Rescaling(1. / 255),
        *blocos,
        L.GlobalAveragePooling2D(),
        L.Dense(128, activation='relu'),
        L.Dropout(0.3),
        L.Dense(4, activation='softmax'),
    ])


def disponivel():
    """True se a CNN foi carregada com sucesso"""
    return _modelo is not None


def _ler_classes():
    caminho = _resolver(CAMINHO_CLASSES)
    if caminho.exists():
        with open(caminho, 'r', encoding='utf-8') as f:
            classes = json.load(f)
        if not isinstance(classes, list) or not all(isinstance(c, str) for c in classes):
            raise ValueError(f'{CAMINHO_CLASSES} precisa ser uma lista de nomes de classes.')
        return classes
    print(f'⚠ {CAMINHO_CLASSES} nao encontrado: usando a ordem padrao {CLASSES_PADRAO}')
    return list(CLASSES_PADRAO)


def _achar_indice_saudavel(classes):
    """Descobre qual saida do softmax e a classe 'sem demencia'"""
    for i, nome in enumerate(classes):
        n = nome.strip().lower()
        if n.startswith('non') or n.startswith('sem') or 'normal' in n or 'saud' in n:
            return i
    raise ValueError(
        f'Nao consegui identificar a classe saudavel em {classes}. '
        'Renomeie a classe ou ajuste _achar_indice_saudavel().'
    )


def rotulo(nome_classe):
    return ROTULOS_PT.get(nome_classe.strip().lower(), nome_classe)


def carregar():
    """Carrega a CNN. Chamada uma vez quando a API sobe"""
    global _modelo, _classes, _idx_saudavel, _tf

    os.environ.setdefault('TF_CPP_MIN_LOG_LEVEL', '2')
    import tensorflow as tf  # import aqui dentro: sem TF a API continua funcionando so com o RF

    caminho = _resolver(CAMINHO_MODELO)
    if not caminho.exists():
        raise FileNotFoundError(f'Modelo da CNN nao encontrado: {caminho}')
    arquivo, pasta_tmp = _preparar_arquivo_modelo(caminho)
    try:
        try:
            modelo = tf.keras.models.load_model(str(arquivo), compile=False)
        except Exception as erro_carga:
            print(f'⚠ load_model falhou ({type(erro_carga).__name__}: {erro_carga})')
            print('  Reconstruindo a arquitetura em codigo e carregando so os pesos...')
            modelo = _construir_arquitetura(tf)
            modelo.load_weights(str(arquivo))
    finally:
        if pasta_tmp:
            shutil.rmtree(pasta_tmp, ignore_errors=True)  # os pesos ja estao na memoria

    entrada = tuple(modelo.input_shape[1:3])
    if entrada != TAMANHO_IMG:
        raise ValueError(f'A CNN espera imagens {entrada}, mas TAMANHO_IMG={TAMANHO_IMG}.')

    classes = _ler_classes()
    n_saidas = int(modelo.output_shape[-1])
    if len(classes) != n_saidas:
        raise ValueError(f'A CNN tem {n_saidas} saidas, mas ha {len(classes)} nomes de classe: {classes}')

    idx = _achar_indice_saudavel(classes)

    # aquecimento: a primeira predicao e lenta, melhor fazer agora do que na 1a requisicao
    modelo.predict(np.zeros((1, *TAMANHO_IMG, 1), dtype='float32'), verbose=0)

    _tf, _classes, _idx_saudavel, _modelo = tf, classes, idx, modelo
    print(f'✓ CNN carregada: {caminho.name} | classes: {classes} | saudavel = "{classes[idx]}"')


def preparar_imagem(dados_bytes):

    img = _tf.io.decode_image(dados_bytes, channels=1, expand_animations=False)
    img = _tf.image.resize(img, TAMANHO_IMG)
    return img.numpy().astype('float32')


def _prever_lote(lote):
    with _lock:
        return _modelo.predict(lote, batch_size=32, verbose=0)


def analisar_imagens(conteudos):

    if not disponivel():
        raise RuntimeError('CNN nao carregada.')

    arrays = []
    falhas = 0
    for dados in conteudos:
        try:
            arrays.append(preparar_imagem(dados))
        except Exception:
            falhas += 1  # imagem corrompida nao derruba o exame inteiro

    if not arrays:
        raise ValueError('Nenhuma das imagens pôde ser lida pela CNN.')

    probs = np.asarray(_prever_lote(np.stack(arrays)), dtype='float64')
    media = probs.mean(axis=0)

    ordem = np.argsort(media)[::-1]
    topo = int(ordem[0])
    return {
        'n_imagens': len(arrays),
        'n_falhas': falhas,
        'classe': _classes[topo],
        'rotulo': rotulo(_classes[topo]),
        'confianca': round(float(media[topo]) * 100, 2),
        'prob_demencia': round(float(1.0 - media[_idx_saudavel]), 6),
        'por_classe': [
            {'classe': _classes[i], 'rotulo': rotulo(_classes[i]), 'prob': round(float(media[i]) * 100, 2)}
            for i in ordem
        ],
    }

def combinar(prob_rf, prob_cnn, peso_cnn=None):
    """Media ponderada das duas P(demencia). Retorna um valor entre 0 e 1"""
    w = PESO_CNN if peso_cnn is None else peso_cnn
    return (1.0 - w) * prob_rf + w * prob_cnn
