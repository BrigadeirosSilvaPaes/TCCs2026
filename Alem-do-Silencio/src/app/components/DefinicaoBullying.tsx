import { BookOpen } from 'lucide-react';

export default function DefinicaoBullying() {
  return (
    <div className="mb-6 bg-indigo-100 dark:bg-indigo-900/30 border-2 border-indigo-300 dark:border-indigo-600/50 rounded-2xl p-4 backdrop-blur-sm">
      <div className="flex items-start gap-3">
        <BookOpen className="w-5 h-5 text-indigo-600 dark:text-indigo-300 flex-shrink-0 mt-0.5" />
        <div className="text-indigo-900 dark:text-indigo-100 text-sm leading-relaxed">
          <p className="font-bold mb-1">O que é bullying?</p>
          <p>
            Bullying é todo ato de violência física ou psicológica, intencional e repetitivo,
            praticado por um indivíduo ou grupo contra uma ou mais pessoas, com o objetivo de
            intimidar ou agredir, sem motivação evidente, causando dor e angústia à vítima, em
            uma relação de desequilíbrio de poder. Isso inclui apelidos ofensivos, agressões
            físicas, exclusão social, ameaças, humilhações e ataques pelas redes sociais
            (cyberbullying). Leia esta definição com atenção antes de registrar seu relato, para
            garantir que a situação descrita se enquadra como bullying.
          </p>
        </div>
      </div>
    </div>
  );
}
