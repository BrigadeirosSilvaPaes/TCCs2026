import { Sun, Moon, Minus, Plus, RotateCcw, Contrast } from 'lucide-react';
import { useTema } from '../hooks/useTema';
import { useTamanhoFonte } from '../hooks/useTamanhoFonte';
import { useAltoContraste } from '../hooks/useAltoContraste';

export default function BarraAcessibilidade() {
  const { tema, alternarTema } = useTema();
  const { aumentar, diminuir, redefinir, noMinimo, noMaximo } = useTamanhoFonte();
  const { ativo: contrasteAtivo, alternarContraste } = useAltoContraste();

  return (
    <div className="mb-6 flex flex-wrap items-center justify-center gap-2">
      <div className="flex items-center gap-1 bg-white dark:bg-gray-800/50 backdrop-blur-xl border border-gray-200 dark:border-gray-700/50 rounded-xl p-1">
        <button
          onClick={diminuir}
          disabled={noMinimo}
          aria-label="Diminuir tamanho da fonte"
          title="Diminuir fonte"
          className="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition disabled:opacity-40 disabled:cursor-not-allowed"
        >
          <Minus className="w-4 h-4" />
        </button>
        <button
          onClick={redefinir}
          aria-label="Redefinir tamanho da fonte"
          title="Redefinir fonte"
          className="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition"
        >
          <RotateCcw className="w-4 h-4" />
        </button>
        <button
          onClick={aumentar}
          disabled={noMaximo}
          aria-label="Aumentar tamanho da fonte"
          title="Aumentar fonte"
          className="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition disabled:opacity-40 disabled:cursor-not-allowed"
        >
          <Plus className="w-4 h-4" />
        </button>
      </div>

      <button
        onClick={alternarContraste}
        aria-pressed={contrasteAtivo}
        aria-label={contrasteAtivo ? 'Desativar alto contraste' : 'Ativar alto contraste'}
        title={contrasteAtivo ? 'Desativar alto contraste' : 'Alto contraste'}
        className={`flex items-center gap-2 backdrop-blur-xl border rounded-xl px-4 py-2 transition ${
          contrasteAtivo
            ? 'bg-indigo-600 border-indigo-600 text-white hover:bg-indigo-500'
            : 'bg-white dark:bg-gray-800/50 border-gray-200 dark:border-gray-700/50 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700'
        }`}
      >
        <Contrast className="w-4 h-4" />
        <span className="text-sm">Alto contraste</span>
      </button>

      <button
        onClick={alternarTema}
        aria-label={tema === 'escuro' ? 'Mudar para modo claro' : 'Mudar para modo escuro'}
        title={tema === 'escuro' ? 'Modo claro' : 'Modo escuro'}
        className="flex items-center gap-2 bg-white dark:bg-gray-800/50 backdrop-blur-xl border border-gray-200 dark:border-gray-700/50 rounded-xl px-4 py-2 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition"
      >
        {tema === 'escuro' ? <Sun className="w-4 h-4" /> : <Moon className="w-4 h-4" />}
        <span className="text-sm">{tema === 'escuro' ? 'Modo claro' : 'Modo escuro'}</span>
      </button>
    </div>
  );
}
