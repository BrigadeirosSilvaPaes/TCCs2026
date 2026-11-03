import { useEffect, useState } from 'react';

type Tema = 'claro' | 'escuro';

const CHAVE_ARMAZENAMENTO = 'tema-preferido';

function obterTemaInicial(): Tema {
  const salvo = localStorage.getItem(CHAVE_ARMAZENAMENTO);
  if (salvo === 'claro' || salvo === 'escuro') return salvo;

  const prefereClaro = window.matchMedia('(prefers-color-scheme: light)').matches;
  return prefereClaro ? 'claro' : 'escuro';
}

export function useTema() {
  const [tema, setTema] = useState<Tema>(obterTemaInicial);

  useEffect(() => {
    const raiz = document.documentElement;
    if (tema === 'escuro') {
      raiz.classList.add('dark');
    } else {
      raiz.classList.remove('dark');
    }
    localStorage.setItem(CHAVE_ARMAZENAMENTO, tema);
  }, [tema]);

  const alternarTema = () => {
    setTema((atual) => (atual === 'escuro' ? 'claro' : 'escuro'));
  };

  return { tema, alternarTema };
}
