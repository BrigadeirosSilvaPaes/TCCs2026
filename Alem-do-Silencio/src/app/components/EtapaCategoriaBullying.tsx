import { MessageSquareWarning, Hand, Brain, UserX, Smartphone, HelpCircle } from 'lucide-react';
import { CategoriaBullying } from '../types';

interface EtapaCategoriaBullyingProps {
  valor: CategoriaBullying;
  outroTexto: string;
  onEscolher: (categoria: CategoriaBullying) => void;
  onOutroTextoChange: (texto: string) => void;
}

const opcoes: { valor: Exclude<CategoriaBullying, null>; titulo: string; descricao: string; icone: typeof Hand }[] = [
  { valor: 'verbal', titulo: 'Verbal', descricao: 'Apelidos, xingamentos, ameaças', icone: MessageSquareWarning },
  { valor: 'fisico', titulo: 'Físico', descricao: 'Empurrões, socos, danos a objetos', icone: Hand },
  { valor: 'psicologico', titulo: 'Psicológico', descricao: 'Humilhação, intimidação, chantagem', icone: Brain },
  { valor: 'social', titulo: 'Exclusão social', descricao: 'Isolar, ignorar, excluir de grupos', icone: UserX },
  { valor: 'cyberbullying', titulo: 'Cyberbullying', descricao: 'Ataques pela internet e redes sociais', icone: Smartphone },
  { valor: 'outro', titulo: 'Não sei / Outro', descricao: 'Descreva com suas palavras', icone: HelpCircle },
];

export default function EtapaCategoriaBullying({
  valor,
  outroTexto,
  onEscolher,
  onOutroTextoChange,
}: EtapaCategoriaBullyingProps) {
  return (
    <div className="space-y-6">
      <div className="mb-6">
        <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">Que tipo de bullying foi?</h2>
        <p className="text-gray-500 dark:text-gray-400 text-sm">
          Escolha a opção que mais se encaixa na situação. Se não souber, tudo bem, pode descrever com suas
          próprias palavras.
        </p>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
        {opcoes.map(({ valor: valorOpcao, titulo, descricao, icone: Icone }) => {
          const selecionado = valor === valorOpcao;
          return (
            <button
              key={valorOpcao}
              onClick={() => onEscolher(valorOpcao)}
              className={`flex items-start gap-3 p-4 rounded-xl border-2 text-left transition-all duration-200 ${
                selecionado
                  ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30'
                  : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-700/30 hover:border-indigo-300 dark:hover:border-indigo-600'
              }`}
            >
              <Icone
                className={`w-6 h-6 flex-shrink-0 mt-0.5 ${
                  selecionado ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-500'
                }`}
              />
              <div>
                <p className="font-bold text-gray-900 dark:text-white">{titulo}</p>
                <p className="text-xs text-gray-500 dark:text-gray-400">{descricao}</p>
              </div>
            </button>
          );
        })}
      </div>

      {valor === 'outro' && (
        <div className="animate-in fade-in duration-200">
          <label className="block text-sm text-gray-500 dark:text-gray-400 mb-2">
            Descreva rapidamente o que aconteceu
          </label>
          <input
            type="text"
            value={outroTexto}
            onChange={(e) => onOutroTextoChange(e.target.value)}
            placeholder="Ex: espalharam mentiras sobre mim para o grupo"
            autoFocus
            className="w-full px-6 py-4 bg-gray-100 dark:bg-gray-700/50 border-2 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition placeholder:text-gray-400 dark:placeholder:text-gray-500"
          />
        </div>
      )}
    </div>
  );
}
