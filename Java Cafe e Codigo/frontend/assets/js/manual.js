document.addEventListener("DOMContentLoaded", function () {

    "use strict";


    /*
    |--------------------------------------------------------------------------
    | ELEMENTOS
    |--------------------------------------------------------------------------
    */

    const pesquisa =
        document.getElementById("manual-pesquisa");

    const filtroCategoria =
        document.getElementById("manual-categoria");

    const filtroNivel =
        document.getElementById("manual-nivel");

    const botaoExpandir =
        document.getElementById("manual-expandir");

    const botaoFechar =
        document.getElementById("manual-fechar");

    const botaoLimpar =
        document.getElementById("manual-limpar");

    const contador =
        document.getElementById("manual-contador");

    const mensagemVazia =
        document.getElementById("manual-vazio");

    const categorias = Array.from(
        document.querySelectorAll(
            ".manual-categoria"
        )
    );

    const topicos = Array.from(
        document.querySelectorAll(
            ".manual-topico"
        )
    );

    const totalTopicos =
        topicos.length;


    /*
    |--------------------------------------------------------------------------
    | NORMALIZA TEXTO
    |--------------------------------------------------------------------------
    */

    function normalizarTexto(texto) {

        return texto
            .toLocaleLowerCase("pt-BR")
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .trim();

    }


    /*
    |--------------------------------------------------------------------------
    | ABRE OU FECHA UM TÓPICO
    |--------------------------------------------------------------------------
    */

    function definirEstadoTopico(
        topico,
        aberto
    ) {

        const botao =
            topico.querySelector(
                ".manual-topico-botao"
            );

        const conteudo =
            topico.querySelector(
                ".manual-topico-conteudo"
            );

        const seta =
            topico.querySelector(
                ".manual-topico-seta"
            );

        botao.setAttribute(
            "aria-expanded",
            aberto ? "true" : "false"
        );

        conteudo.hidden =
            !aberto;

        topico.classList.toggle(
            "esta-aberto",
            aberto
        );

        seta.textContent =
            aberto ? "−" : "+";

    }


    /*
    |--------------------------------------------------------------------------
    | EVENTOS DOS CARDS
    |--------------------------------------------------------------------------
    */

    topicos.forEach(function (topico) {

        const botao =
            topico.querySelector(
                ".manual-topico-botao"
            );

        botao.addEventListener(
            "click",
            function () {

                const aberto =
                    botao.getAttribute(
                        "aria-expanded"
                    ) === "true";

                definirEstadoTopico(
                    topico,
                    !aberto
                );

            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | FILTROS
    |--------------------------------------------------------------------------
    */

    function aplicarFiltros() {

        const termo =
            normalizarTexto(
                pesquisa.value
            );

        const categoriaSelecionada =
            filtroCategoria.value;

        const nivelSelecionado =
            filtroNivel.value;

        let quantidadeVisivel = 0;

        topicos.forEach(function (topico) {

            const texto =
                normalizarTexto(
                    topico.dataset.pesquisa || ""
                );

            const correspondePesquisa =
                termo === ""
                || texto.includes(termo);

            const correspondeCategoria =
                categoriaSelecionada === ""
                || topico.dataset.categoria
                    === categoriaSelecionada;

            const correspondeNivel =
                nivelSelecionado === ""
                || topico.dataset.nivel
                    === nivelSelecionado;

            const visivel =
                correspondePesquisa
                && correspondeCategoria
                && correspondeNivel;

            topico.hidden =
                !visivel;

            if (visivel) {

                quantidadeVisivel++;

            } else {

                definirEstadoTopico(
                    topico,
                    false
                );

            }

        });

        categorias.forEach(function (categoria) {

            const possuiTopicoVisivel =
                Array.from(
                    categoria.querySelectorAll(
                        ".manual-topico"
                    )
                ).some(function (topico) {

                    return !topico.hidden;

                });

            categoria.hidden =
                !possuiTopicoVisivel;

        });

        contador.textContent =
            "Exibindo "
            + quantidadeVisivel
            + " de "
            + totalTopicos
            + " tópicos.";

        mensagemVazia.hidden =
            quantidadeVisivel !== 0;

    }


    /*
    |--------------------------------------------------------------------------
    | CONTROLES
    |--------------------------------------------------------------------------
    */

    botaoExpandir.addEventListener(
        "click",
        function () {

            topicos.forEach(function (topico) {

                if (!topico.hidden) {

                    definirEstadoTopico(
                        topico,
                        true
                    );

                }

            });

        }
    );

    botaoFechar.addEventListener(
        "click",
        function () {

            topicos.forEach(function (topico) {

                definirEstadoTopico(
                    topico,
                    false
                );

            });

        }
    );

    botaoLimpar.addEventListener(
        "click",
        function () {

            pesquisa.value = "";
            filtroCategoria.value = "";
            filtroNivel.value = "";

            aplicarFiltros();

            pesquisa.focus();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PESQUISA E FILTROS
    |--------------------------------------------------------------------------
    */

    pesquisa.addEventListener(
        "input",
        aplicarFiltros
    );

    filtroCategoria.addEventListener(
        "change",
        aplicarFiltros
    );

    filtroNivel.addEventListener(
        "change",
        aplicarFiltros
    );


    /*
    |--------------------------------------------------------------------------
    | INICIALIZAÇÃO
    |--------------------------------------------------------------------------
    */

    aplicarFiltros();

});
