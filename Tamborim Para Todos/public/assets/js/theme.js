const THEME_KEY = "tamborim-theme";


/* =========================================================
   APLICAR TEMA
========================================================= */

function aplicarTema(tema) {

    if (
        tema !== "light" &&
        tema !== "dark"
    ) {
        tema = "light";
    }

    document.documentElement.setAttribute(
        "data-theme",
        tema
    );

    atualizarBotoesTema();
}


/* =========================================================
   SALVAR TEMA
========================================================= */

function salvarTema(tema) {

    localStorage.setItem(
        THEME_KEY,
        tema
    );

    aplicarTema(tema);
}


/* =========================================================
   CARREGAR TEMA
========================================================= */

function carregarTema() {

    const temaSalvo =
        localStorage.getItem(
            THEME_KEY
        );

    if (temaSalvo) {

        aplicarTema(temaSalvo);

        return;
    }


    const prefereEscuro =
        window.matchMedia(
            "(prefers-color-scheme: dark)"
        ).matches;


    aplicarTema(
        prefereEscuro
            ? "dark"
            : "light"
    );
}


/* =========================================================
   ATUALIZAR BOTÕES
========================================================= */

function atualizarBotoesTema() {

    const temaAtual =
        document.documentElement.getAttribute(
            "data-theme"
        );


    document
        .querySelectorAll(
            "[data-theme-option]"
        )
        .forEach((botao) => {

            const temaBotao =
                botao.dataset.themeOption;


            botao.classList.toggle(
                "active",
                temaBotao === temaAtual
            );

        });
}


/* =========================================================
   INICIALIZAÇÃO
========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    () => {

        carregarTema();


        document
            .querySelectorAll(
                "[data-theme-option]"
            )
            .forEach((botao) => {

                botao.addEventListener(
                    "click",
                    () => {

                        salvarTema(
                            botao.dataset.themeOption
                        );

                    }
                );

            });

    }
);