import { useState } from 'react';
import { Shield, User, GraduationCap, Calendar, Tag, AlertTriangle, FileText, CheckSquare } from 'lucide-react';
import { TipoDenuncia, CategoriaBullying, NivelUrgencia } from './types';
import { gerarProtocolo } from './utils/protocolo';
import { enviarRelato } from './services/enviarRelato';
import AvisoConfidencialidade from './components/AvisoConfidencialidade';
import CabecalhoRelato from './components/CabecalhoRelato';
import DefinicaoBullying from './components/DefinicaoBullying';
import BarraProgresso from './components/BarraProgresso';
import TelaConfirmacao from './components/TelaConfirmacao';
import EtapaTipoDenuncia from './components/EtapaTipoDenuncia';
import EtapaCategoriaBullying from './components/EtapaCategoriaBullying';
import EtapaUrgencia from './components/EtapaUrgencia';
import EtapaCampoTexto from './components/EtapaCampoTexto';
import EtapaCampoTextarea from './components/EtapaCampoTextarea';
import NavegacaoEtapas from './components/NavegacaoEtapas';
import SobreNos from './components/SobreNos';
import Rodape from './components/Rodape';
import BarraAcessibilidade from './components/BarraAcessibilidade';

const ICONES_IDENTIFICADA = [Shield, User, GraduationCap, Calendar, Tag, AlertTriangle, FileText, CheckSquare];
const ICONES_ANONIMA = [Shield, Tag, AlertTriangle, FileText, CheckSquare];

export default function App() {
  const [step, setStep] = useState(0);
  const [tipoDenuncia, setTipoDenuncia] = useState<TipoDenuncia>(null);
  const [nomeCompleto, setNomeCompleto] = useState('');
  const [turma, setTurma] = useState('');
  const [idade, setIdade] = useState('');
  const [categoriaBullying, setCategoriaBullying] = useState<CategoriaBullying>(null);
  const [categoriaOutroTexto, setCategoriaOutroTexto] = useState('');
  const [nivelUrgencia, setNivelUrgencia] = useState<NivelUrgencia>(null);
  const [descricao, setDescricao] = useState('');
  const [medidas, setMedidas] = useState('');
  const [enviando, setEnviando] = useState(false);
  const [enviado, setEnviado] = useState(false);
  const [erro, setErro] = useState('');
  const [protocolo, setProtocolo] = useState('');
  const [direcao, setDirecao] = useState<'frente' | 'voltar'>('frente');

  const totalSteps = tipoDenuncia === 'identificada' ? 8 : 5;
  const icones = tipoDenuncia === 'identificada' ? ICONES_IDENTIFICADA : ICONES_ANONIMA;

  const handleNext = () => {
    if (step < totalSteps - 1) {
      setDirecao('frente');
      setStep(step + 1);
    }
  };

  const handleBack = () => {
    if (step > 0) {
      setDirecao('voltar');
      setStep(step - 1);
    }
  };

  const handleTipoDenuncia = (tipo: TipoDenuncia) => {
    setDirecao('frente');
    setTipoDenuncia(tipo);
    setStep(1);
  };

  const handleSubmit = async () => {
    setEnviando(true);
    setErro('');

    const numeroProtocolo = gerarProtocolo();
    setProtocolo(numeroProtocolo);

    try {
      const ok = await enviarRelato({
        protocolo: numeroProtocolo,
        tipoDenuncia,
        nomeCompleto,
        turma,
        idade,
        categoriaBullying,
        categoriaOutroTexto,
        nivelUrgencia,
        descricao,
        medidas,
      });

      if (ok) {
        setEnviado(true);
      } else {
        setErro('Erro ao enviar. Tente novamente.');
      }
    } catch (error) {
      setErro('Erro de conexão. Verifique sua internet.');
    } finally {
      setEnviando(false);
    }
  };

  const handleNovoRelato = () => {
    setEnviado(false);
    setStep(0);
    setTipoDenuncia(null);
    setNomeCompleto('');
    setTurma('');
    setIdade('');
    setCategoriaBullying(null);
    setCategoriaOutroTexto('');
    setNivelUrgencia(null);
    setDescricao('');
    setMedidas('');
    setProtocolo('');
  };

  const canProceed = () => {
    if (step === 0) return tipoDenuncia !== null;
    if (tipoDenuncia === 'identificada') {
      if (step === 1) return nomeCompleto.trim() !== '';
      if (step === 2) return turma.trim() !== '';
      if (step === 3) return idade.trim() !== '';
      if (step === 4) return categoriaBullying !== null && (categoriaBullying !== 'outro' || categoriaOutroTexto.trim() !== '');
      if (step === 5) return nivelUrgencia !== null;
      if (step === 6) return descricao.trim() !== '';
      if (step === 7) return medidas.trim() !== '';
    } else {
      if (step === 1) return categoriaBullying !== null && (categoriaBullying !== 'outro' || categoriaOutroTexto.trim() !== '');
      if (step === 2) return nivelUrgencia !== null;
      if (step === 3) return descricao.trim() !== '';
      if (step === 4) return medidas.trim() !== '';
    }
    return true;
  };

  if (enviado) {
    return <TelaConfirmacao protocolo={protocolo} onNovoRelato={handleNovoRelato} />;
  }

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gradient-to-br dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex items-center justify-center p-6">
      <div className="max-w-2xl w-full">
        <BarraAcessibilidade />
        <CabecalhoRelato />
        <DefinicaoBullying />
        <AvisoConfidencialidade />

        <div className="bg-white dark:bg-gray-800/50 backdrop-blur-xl rounded-3xl p-8 md:p-12 border border-gray-200 dark:border-gray-700/50 shadow-2xl">
          <BarraProgresso step={step} totalSteps={totalSteps} icones={icones} />

          <div className="min-h-[300px] flex flex-col justify-between">
            <div
              key={step}
              className={`animate-in fade-in duration-300 ${
                direcao === 'frente' ? 'slide-in-from-right-4' : 'slide-in-from-left-4'
              }`}
            >
              {step === 0 && <EtapaTipoDenuncia onEscolher={handleTipoDenuncia} />}

              {tipoDenuncia === 'identificada' && (
                <>
                  {step === 1 && (
                    <EtapaCampoTexto
                      titulo="Qual é o seu nome completo?"
                      subtitulo="Esta informação ficará em sigilo"
                      valor={nomeCompleto}
                      onChange={setNomeCompleto}
                      placeholder="Digite seu nome completo"
                    />
                  )}
                  {step === 2 && (
                    <EtapaCampoTexto
                      titulo="Qual é a sua turma?"
                      subtitulo="Informe sua série e turma"
                      valor={turma}
                      onChange={setTurma}
                      placeholder="Ex: 8º Ano A"
                    />
                  )}
                  {step === 3 && (
                    <EtapaCampoTexto
                      titulo="Qual é a sua idade?"
                      subtitulo="Informe sua idade atual"
                      valor={idade}
                      onChange={setIdade}
                      placeholder="Digite sua idade"
                      tipo="number"
                      min="1"
                      max="100"
                    />
                  )}
                  {step === 4 && (
                    <EtapaCategoriaBullying valor={categoriaBullying} outroTexto={categoriaOutroTexto} onEscolher={setCategoriaBullying} onOutroTextoChange={setCategoriaOutroTexto} />
                  )}
                  {step === 5 && <EtapaUrgencia valor={nivelUrgencia} onEscolher={setNivelUrgencia} />}
                  {step === 6 && (
                    <EtapaCampoTextarea
                      titulo="O que aconteceu?"
                      subtitulo="Descreva o ocorrido com detalhes"
                      valor={descricao}
                      onChange={setDescricao}
                      placeholder="Conte o que aconteceu..."
                    />
                  )}
                  {step === 7 && (
                    <EtapaCampoTextarea
                      titulo="O que você espera da escola?"
                      subtitulo="Quais medidas você gostaria que fossem tomadas?"
                      valor={medidas}
                      onChange={setMedidas}
                      placeholder="Descreva as ações que você espera..."
                    />
                  )}
                </>
              )}

              {tipoDenuncia === 'anonima' && (
                <>
                  {step === 1 && (
                    <EtapaCategoriaBullying valor={categoriaBullying} outroTexto={categoriaOutroTexto} onEscolher={setCategoriaBullying} onOutroTextoChange={setCategoriaOutroTexto} />
                  )}
                  {step === 2 && <EtapaUrgencia valor={nivelUrgencia} onEscolher={setNivelUrgencia} />}
                  {step === 3 && (
                    <EtapaCampoTextarea
                      titulo="O que aconteceu?"
                      subtitulo="Descreva o ocorrido com detalhes"
                      valor={descricao}
                      onChange={setDescricao}
                      placeholder="Conte o que aconteceu..."
                    />
                  )}
                  {step === 4 && (
                    <EtapaCampoTextarea
                      titulo="O que você espera da escola?"
                      subtitulo="Quais medidas você gostaria que fossem tomadas?"
                      valor={medidas}
                      onChange={setMedidas}
                      placeholder="Descreva as ações que você espera..."
                    />
                  )}
                </>
              )}
            </div>
          </div>

          {erro && (
            <div className="mt-6 bg-red-100 dark:bg-red-900/50 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-xl">
              {erro}
            </div>
          )}

          <NavegacaoEtapas
            step={step}
            totalSteps={totalSteps}
            podeAvancar={canProceed()}
            enviando={enviando}
            onVoltar={handleBack}
            onAvancar={handleNext}
            onEnviar={handleSubmit}
          />
        </div>

        <SobreNos />
        <Rodape />
      </div>
    </div>
  );
}
