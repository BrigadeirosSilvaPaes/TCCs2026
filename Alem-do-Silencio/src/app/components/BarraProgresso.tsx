import { LucideIcon } from 'lucide-react';

interface BarraProgressoProps {
  step: number;
  totalSteps: number;
  icones: LucideIcon[];
}

export default function BarraProgresso({ step, totalSteps, icones }: BarraProgressoProps) {
  const porcentagem = ((step + 1) / totalSteps) * 100;

  return (
    <div className="mb-8">
      <div className="flex items-center justify-center gap-2 mb-4">
        {icones.map((Icone, index) => {
          const concluido = index < step;
          const atual = index === step;
          return (
            <div
              key={index}
              className={`flex items-center justify-center w-8 h-8 rounded-full border-2 transition-all duration-300 ${
                atual
                  ? 'border-indigo-500 bg-indigo-500 text-white scale-110'
                  : concluido
                    ? 'border-indigo-400 bg-indigo-400/20 text-indigo-500 dark:text-indigo-400'
                    : 'border-gray-300 dark:border-gray-600 text-gray-400 dark:text-gray-500'
              }`}
            >
              <Icone className="w-4 h-4" />
            </div>
          );
        })}
      </div>

      <div className="flex items-center justify-between mb-2">
        <span className="text-sm text-gray-500 dark:text-gray-400">
          Etapa {step + 1} de {totalSteps}
        </span>
        <span className="text-sm text-gray-500 dark:text-gray-400">{Math.round(porcentagem)}%</span>
      </div>
      <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
        <div
          className="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full transition-all duration-500"
          style={{ width: `${porcentagem}%` }}
        />
      </div>
    </div>
  );
}
