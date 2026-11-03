import { Shield } from 'lucide-react';

export default function AvisoConfidencialidade() {
  return (
    <div className="mb-6 bg-red-100 dark:bg-red-900/30 border-2 border-red-300 dark:border-red-600/50 rounded-2xl p-4 backdrop-blur-sm">
      <div className="flex items-start gap-3">
        <Shield className="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" />
        <p className="text-red-800 dark:text-red-200 text-sm font-medium leading-relaxed">
          <span className="font-bold">IMPORTANTE:</span> Todas as informações fornecidas neste
          relato são estritamente confidenciais e terão acesso exclusivo pela Secretaria Escolar,
          conforme as normas de privacidade e proteção de dados da instituição.
        </p>
      </div>
    </div>
  );
}
