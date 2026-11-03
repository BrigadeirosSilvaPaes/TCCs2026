import { NivelUrgencia } from '../types';

interface EtapaUrgenciaProps {
  valor: NivelUrgencia;
  onEscolher: (urgencia: NivelUrgencia) => void;
}

const opcoes: { valor: Exclude<NivelUrgencia, null>; titulo: string; descricao: string; corBase: string }[] = [
  {
    valor: 'baixa',
    titulo: 'Baixa',
    descricao: 'Aconteceu, mas não é recorrente',
    corBase: 'green',
  },
  {
    valor: 'media',
    titulo: 'Média',
    descricao: 'Já aconteceu mais de uma vez',
    corBase: 'yellow',
  },
  {
    valor: 'alta',
    titulo: 'Alta',
    descricao: 'Está acontecendo agora ou é grave',
    corBase: 'red',
  },
];

const estilos: Record<string, { selecionado: string; naoSelecionado: string; ponto: string }> = {
  green: {
    selecionado: 'border-green-500 bg-green-50 dark:bg-green-900/30',
    naoSelecionado:
      'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/30 hover:border-green-300 dark:hover:border-green-600',
    ponto: 'bg-green-500',
  },
  yellow: {
    selecionado: 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/30',
    naoSelecionado:
      'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/30 hover:border-yellow-300 dark:hover:border-yellow-600',
    ponto: 'bg-yellow-500',
  },
  red: {
    selecionado: 'border-red-500 bg-red-50 dark:bg-red-900/30',
    naoSelecionado:
      'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/30 hover:border-red-300 dark:hover:border-red-600',
    ponto: 'bg-red-500',
  },
};

export default function EtapaUrgencia({ valor, onEscolher }: EtapaUrgenciaProps) {
  return (
    <div className="space-y-6">
      <div className="mb-6">
        <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">Qual a urgência da situação?</h2>
        <p className="text-gray-500 dark:text-gray-400 text-sm">
          Isso ajuda a escola a priorizar o atendimento
        </p>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
        {opcoes.map(({ valor: valorOpcao, titulo, descricao, corBase }) => {
          const selecionado = valor === valorOpcao;
          const estilo = estilos[corBase];
          return (
            <button
              key={valorOpcao}
              onClick={() => onEscolher(valorOpcao)}
              className={`flex flex-col items-center text-center gap-2 p-4 rounded-xl border-2 transition-all duration-200 ${
                selecionado ? estilo.selecionado : estilo.naoSelecionado
              }`}
            >
              <span className={`w-3 h-3 rounded-full ${estilo.ponto}`} />
              <p className="font-bold text-gray-900 dark:text-white">{titulo}</p>
              <p className="text-xs text-gray-500 dark:text-gray-400">{descricao}</p>
            </button>
          );
        })}
      </div>
    </div>
  );
}
