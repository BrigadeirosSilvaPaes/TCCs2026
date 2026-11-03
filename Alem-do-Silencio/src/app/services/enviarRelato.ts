import { TipoDenuncia, CategoriaBullying, NivelUrgencia } from '../types';

interface DadosRelato {
  protocolo: string;
  tipoDenuncia: TipoDenuncia;
  nomeCompleto: string;
  turma: string;
  idade: string;
  categoriaBullying: CategoriaBullying;
  categoriaOutroTexto: string;
  nivelUrgencia: NivelUrgencia;
  descricao: string;
  medidas: string;
}

const ROTULOS_CATEGORIA: Record<Exclude<CategoriaBullying, null>, string> = {
  verbal: 'Verbal',
  fisico: 'Físico',
  psicologico: 'Psicológico',
  social: 'Exclusão social',
  cyberbullying: 'Cyberbullying',
  outro: 'Outro / Não soube especificar',
};

const ROTULOS_URGENCIA: Record<Exclude<NivelUrgencia, null>, string> = {
  baixa: 'Baixa',
  media: 'Média',
  alta: 'Alta',
};

export async function enviarRelato(dados: DadosRelato): Promise<boolean> {
  const formData = new FormData();
  formData.append('Protocolo', dados.protocolo);
  formData.append('_captcha', 'false');
  formData.append('Tipo_de_Denuncia', dados.tipoDenuncia === 'identificada' ? 'Identificada' : 'Anônima');
  formData.append('Nome_Completo', dados.nomeCompleto || 'Anônimo');
  formData.append('Turma', dados.turma || 'Não informado');
  formData.append('Idade', dados.idade || 'Não informado');
  formData.append(
    'Categoria_do_Bullying',
    dados.categoriaBullying ? ROTULOS_CATEGORIA[dados.categoriaBullying] : 'Não informado'
  );
  if (dados.categoriaBullying === 'outro' && dados.categoriaOutroTexto) {
    formData.append('Categoria_Descrita_Pelo_Usuario', dados.categoriaOutroTexto);
  }
  formData.append(
    'Nivel_de_Urgencia',
    dados.nivelUrgencia ? ROTULOS_URGENCIA[dados.nivelUrgencia] : 'Não informado'
  );
  formData.append('Descricao_do_Ocorrido', dados.descricao);
  formData.append('Medidas_Esperadas', dados.medidas);
  formData.append('Data_e_Hora', new Date().toLocaleString('pt-BR'));
  formData.append('_subject', `[Protocolo: ${dados.protocolo}] Novo Relato de Ocorrência Escolar`);
  formData.append('_template', 'table');

  const response = await fetch('https://formsubmit.co/matheusstudinski@gmail.com', {
    method: 'POST',
    body: formData,
    headers: {
      Accept: 'application/json',
    },
  });

  return response.ok;
}
