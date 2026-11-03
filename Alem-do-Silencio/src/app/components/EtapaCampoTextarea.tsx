interface EtapaCampoTextareaProps {
  titulo: string;
  subtitulo: string;
  valor: string;
  onChange: (valor: string) => void;
  placeholder: string;
}

export default function EtapaCampoTextarea({
  titulo,
  subtitulo,
  valor,
  onChange,
  placeholder,
}: EtapaCampoTextareaProps) {
  return (
    <div className="space-y-6">
      <div className="mb-6">
        <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-2">{titulo}</h2>
        <p className="text-gray-500 dark:text-gray-400 text-sm">{subtitulo}</p>
      </div>
      <textarea
        value={valor}
        onChange={(e) => onChange(e.target.value)}
        rows={6}
        className="w-full px-6 py-4 bg-gray-100 dark:bg-gray-700/50 border-2 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-lg rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition resize-none placeholder:text-gray-400 dark:placeholder:text-gray-500"
        placeholder={placeholder}
        autoFocus
      />
    </div>
  );
}
