import { useEffect, useState } from 'react';

const CHAVE_ARMAZENAMENTO = 'tamanho-fonte';
const TAMANHO_MINIMO = 14;
const TAMANHO_MAXIMO = 22;
const TAMANHO_PADRAO = 16;
const PASSO = 2;

function obterTamanhoInicial(): number {
  const salvo = localStorage.getItem(CHAVE_ARMAZENAMENTO);
  const numero = salvo ? parseInt(salvo, 10) : TAMANHO_PADRAO;
  if (isNaN(numero)) return TAMANHO_PADRAO;
  return Math.min(TAMANHO_MAXIMO, Math.max(TAMANHO_MINIMO, numero));
}

export function useTamanhoFonte() {
  const [tamanho, setTamanho] = useState<number>(obterTamanhoInicial);

  useEffect(() => {
    document.documentElement.style.setProperty('--font-size', `${tamanho}px`);
    localStorage.setItem(CHAVE_ARMAZENAMENTO, String(tamanho));
  }, [tamanho]);

  const aumentar = () => {
    setTamanho((atual) => Math.min(TAMANHO_MAXIMO, atual + PASSO));
  };

  const diminuir = () => {
    setTamanho((atual) => Math.max(TAMANHO_MINIMO, atual - PASSO));
  };

  const redefinir = () => {
    setTamanho(TAMANHO_PADRAO);
  };

  return {
    tamanho,
    aumentar,
    diminuir,
    redefinir,
    noMinimo: tamanho <= TAMANHO_MINIMO,
    noMaximo: tamanho >= TAMANHO_MAXIMO,
  };
}
