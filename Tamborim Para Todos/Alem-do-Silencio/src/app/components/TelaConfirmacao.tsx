import { CheckCircle2 } from 'lucide-react';
import BarraAcessibilidade from './BarraAcessibilidade';

interface TelaConfirmacaoProps {
  protocolo: string;
  onNovoRelato: () => void;
}

export default function TelaConfirmacao({ protocolo, onNovoRelato }: TelaConfirmacaoProps) {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gradient-to-br dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex items-center justify-center p-6">
      <div className="max-w-md w-full">
        <BarraAcessibilidade />
        <div className="bg-white dark:bg-gray-800/50 backdrop-blur-xl rounded-3xl p-8 md:p-12 border border-gray-200 dark:border-gray-700/50 shadow-2xl text-center">
          <div className="mb-6">
            <div className="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
              <CheckCircle2 className="w-10 h-10 text-green-500 dark:text-green-400" />
            </div>
            <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-3">Relato Enviado!</h2>
            <p className="text-gray-500 dark:text-gray-400 mb-6">
              A equipe escolar receberá sua mensagem e tomará as devidas providências.
            </p>

            <div className="bg-gradient-to-r from-indigo-600/20 to-purple-600/20 border-2 border-indigo-500/50 rounded-2xl p-6 mb-6">
              <p className="text-sm text-gray-600 dark:text-gray-300 mb-2">Número do Protocolo</p>
              <p className="text-2xl font-mono font-bold text-gray-900 dark:text-white mb-3">{protocolo}</p>
              <button
                onClick={() => {
                  navigator.clipboard.writeText(protocolo);
                  alert('Protocolo copiado!');
                }}
                className="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition"
              >
                Clique para copiar
              </button>
            </div>

            <div className="bg-yellow-100 dark:bg-yellow-900/20 border border-yellow-400 dark:border-yellow-600/50 rounded-xl p-4 mb-6">
              <p className="text-yellow-800 dark:text-yellow-300 text-sm font-semibold mb-2">⚠️ Importante</p>
              <p className="text-yellow-700 dark:text-yellow-200/80 text-xs">
                Guarde este número de protocolo com você. Ele poderá ser usado para acompanhar o
                andamento da sua solicitação.
              </p>
            </div>

            <button onClick={onNovoRelato} className="text-gray-500 dark:text-gray-400 text-sm hover:text-gray-900 dark:hover:text-white transition">
              Fazer outro relato
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
