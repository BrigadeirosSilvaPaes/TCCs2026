import { ArrowLeft, ArrowRight, CheckCircle2 } from 'lucide-react';

interface NavegacaoEtapasProps {
  step: number;
  totalSteps: number;
  podeAvancar: boolean;
  enviando: boolean;
  onVoltar: () => void;
  onAvancar: () => void;
  onEnviar: () => void;
}

export default function NavegacaoEtapas({
  step,
  totalSteps,
  podeAvancar,
  enviando,
  onVoltar,
  onAvancar,
  onEnviar,
}: NavegacaoEtapasProps) {
  return (
    <div className="flex gap-4 mt-8">
      {step > 0 && (
        <button
          onClick={onVoltar}
          className="flex items-center gap-2 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300"
        >
          <ArrowLeft className="w-5 h-5" />
          Voltar
        </button>
      )}

      {step < totalSteps - 1 ? (
        <button
          onClick={onAvancar}
          disabled={!podeAvancar}
          className="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-500 hover:to-purple-500 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Continuar
          <ArrowRight className="w-5 h-5" />
        </button>
      ) : (
        <button
          onClick={onEnviar}
          disabled={!podeAvancar || enviando}
          className="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-500 hover:to-emerald-500 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {enviando ? 'Enviando...' : 'Enviar Relato'}
          <CheckCircle2 className="w-5 h-5" />
        </button>
      )}
    </div>
  );
}
