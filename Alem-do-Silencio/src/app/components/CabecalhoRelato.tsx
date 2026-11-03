import logoEscola from '../../assets/logo.png';

export default function CabecalhoRelato() {
  return (
    <div className="mb-8 text-center">
      <img
        src={logoEscola}
        alt="Logo da escola"
        className="mx-auto mb-4 w-24 h-24 sm:w-28 sm:h-28 object-contain"
      />
      <h1 className="text-4xl font-bold text-gray-900 dark:text-white mb-2">Relato de Bullying Escolar</h1>
      <p className="text-gray-500 dark:text-gray-400">Suas informações serão tratadas com confidencialidade</p>
    </div>
  );
}
