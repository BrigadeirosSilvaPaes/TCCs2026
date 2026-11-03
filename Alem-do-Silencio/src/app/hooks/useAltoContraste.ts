import { useEffect, useState } from 'react';

const CHAVE_ARMAZENAMENTO = 'alto-contraste';

function obterEstadoInicial(): boolean {
  return localStorage.getItem(CHAVE_ARMAZENAMENTO) === 'ativado';
}

export function useAltoContraste() {
  const [ativo, setAtivo] = useState<boolean>(obterEstadoInicial);

  useEffect(() => {
    const raiz = document.documentElement;
    if (ativo) {
      raiz.classList.add('alto-contraste');
    } else {
      raiz.classList.remove('alto-contraste');
    }
    localStorage.setItem(CHAVE_ARMAZENAMENTO, ativo ? 'ativado' : 'desativado');
  }, [ativo]);

  const alternarContraste = () => {
    setAtivo((atual) => !atual);
  };

  return { ativo, alternarContraste };
}
