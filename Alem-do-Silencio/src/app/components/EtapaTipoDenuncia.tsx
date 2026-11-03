import { Shield, User, AlertCircle } from 'lucide-react';
import { TipoDenuncia } from '../types';

interface EtapaTipoDenunciaProps {
  onEscolher: (tipo: TipoDenuncia) => void;
}

export default function EtapaTipoDenuncia({ onEscolher }: EtapaTipoDenunciaProps) {
  return (
    <div className="space-y-6">
      <div className="text-center mb-8">
        <Shield className="w-16 h-16 text-indigo-500 dark:text-indigo-400 mx-auto mb-4" />
        <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">Como deseja fazer o relato?</h2>
        <p className="text-gray-500 dark:text-gray-400">Escolha o tipo de denúncia</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <button
          onClick={() => onEscolher('identificada')}
          className="group relative bg-gradient-to-br from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white p-8 rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-xl"
        >
          <User className="w-12 h-12 mb-4 mx-auto" />
          <h3 className="text-xl font-bold mb-2">Identificada</h3>
          <p className="text-indigo-100 text-sm">Informar seus dados pessoais</p>
        </button>

        <button
          onClick={() => onEscolher('anonima')}
          className="group relative bg-gradient-to-br from-purple-600 to-purple-700 hover:from-purple-500 hover:to-purple-600 text-white p-8 rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-xl"
        >
          <AlertCircle className="w-12 h-12 mb-4 mx-auto" />
          <h3 className="text-xl font-bold mb-2">Anônima</h3>
          <p className="text-purple-100 text-sm">Manter identidade em sigilo</p>
        </button>
      </div>
    </div>
  );
}
