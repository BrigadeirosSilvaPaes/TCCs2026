import { useState } from 'react';
import { ChevronDown, Users } from 'lucide-react';

export default function SobreNos() {
  const [aberto, setAberto] = useState(false);

  return (
    <div className="mt-6">
      <button
        onClick={() => setAberto(!aberto)}
        className="w-full flex items-center justify-center gap-2 px-6 py-3 bg-white dark:bg-gray-800/50 backdrop-blur-xl border border-gray-200 dark:border-gray-700/50 text-gray-900 dark:text-white rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-all duration-300"
      >
        <Users className="w-5 h-5" />
        Sobre nós
        <ChevronDown
          className={`w-5 h-5 transition-transform duration-300 ${aberto ? 'rotate-180' : ''}`}
        />
      </button>

      {aberto && (
        <div className="mt-3 bg-white dark:bg-gray-800/50 backdrop-blur-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700/50 text-gray-600 dark:text-gray-300 text-sm leading-relaxed animate-in fade-in duration-300">
          <p>
            Este site foi criado por <span className="font-bold text-gray-900 dark:text-white">Matheus Dias Studinski</span> e{' '}
            <span className="font-bold text-gray-900 dark:text-white">Isaque da Silva Barbachã</span> como Trabalho de
            Conclusão de Curso, com o objetivo de oferecer um canal seguro e confidencial para que
            vítimas e testemunhas de bullying escolar possam fazer suas denúncias, sejam elas
            identificadas ou anônimas.
          </p>
        </div>
      )}
    </div>
  );
}
