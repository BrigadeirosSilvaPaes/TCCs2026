export default function Rodape() {
  const anoAtual = new Date().getFullYear();

  return (
    <div className="mt-8 text-center">
      <p className="text-gray-500 dark:text-gray-500 text-xs">
        © {anoAtual} Matheus Dias Studinski e Isaque da Silva Barbachã. Todos os direitos reservados.
      </p>
    </div>
  );
}
