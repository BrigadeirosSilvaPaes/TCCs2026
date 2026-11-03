document.addEventListener(
    "DOMContentLoaded",
    function () {

        /*
        |--------------------------------------------------------------------------
        | DADOS DA AULA
        |--------------------------------------------------------------------------
        */

        const aula =
            window.AULA_CONVERSADA;


        const baseUrl =
            typeof window.BASE_URL === "string"
                ? window.BASE_URL.replace(/\/+$/, "")
                : "";


        /*
        |--------------------------------------------------------------------------
        | VALIDA DADOS
        |--------------------------------------------------------------------------
        */

        if (
            !aula
            ||
            typeof aula !== "object"
            ||
            !aula.interacoes
            ||
            typeof aula.interacoes !== "object"
        ) {

            console.error(
                "Os dados da Aula Conversada não foram carregados."
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ID DA AULA
        |--------------------------------------------------------------------------
        */

        const aulaId =
            Number.parseInt(
                aula.id,
                10
            );


        if (
            !Number.isInteger(
                aulaId
            )
            ||
            aulaId <= 0
        ) {

            console.error(
                "Não foi possível identificar a aula atual."
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ROTAS
        |--------------------------------------------------------------------------
        */

        const rotaListaAulas =
            typeof aula.lista_aulas_url === "string"
            &&
            aula.lista_aulas_url.trim() !== ""
                ? aula.lista_aulas_url
                : baseUrl + "/aulas";


        const rotaProximaAula =
            typeof aula.proxima_aula_url === "string"
            &&
            aula.proxima_aula_url.trim() !== ""
                ? aula.proxima_aula_url
                : null;


        const rotaConcluirAula =
            typeof aula.concluir_url === "string"
            &&
            aula.concluir_url.trim() !== ""
                ? aula.concluir_url
                : null;


        const rotaSalvarProgresso =
            typeof aula.progresso_url === "string"
            &&
            aula.progresso_url.trim() !== ""
                ? aula.progresso_url
                : null;


        /*
        |--------------------------------------------------------------------------
        | VALIDA ROTAS
        |--------------------------------------------------------------------------
        */

        if (
            !rotaConcluirAula
        ) {

            console.error(
                "A rota para concluir a aula não foi configurada."
            );

        }


        if (
            !rotaSalvarProgresso
        ) {

            console.error(
                "A rota para salvar o progresso não foi configurada."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PROGRESSO RECEBIDO DO SERVIDOR
        |--------------------------------------------------------------------------
        */

        const progressoServidor =
            aula.progresso
            &&
            typeof aula.progresso === "object"
                ? aula.progresso
                : {};


        const aulaJaIniciada =
            progressoServidor.iniciada === true;


        const aulaJaConcluida =
            progressoServidor.concluida === true;


        let etapaSalva =
            Number.parseInt(
                progressoServidor.etapa_atual,
                10
            );


        /*
        |--------------------------------------------------------------------------
        | NORMALIZA ETAPA
        |--------------------------------------------------------------------------
        */

        if (
            !Number.isInteger(
                etapaSalva
            )
            ||
            etapaSalva < 1
            ||
            etapaSalva > 4
        ) {

            etapaSalva =
                1;

        }


        /*
        |--------------------------------------------------------------------------
        | INTERAÇÃO DE CADA ETAPA
        |--------------------------------------------------------------------------
        */

        const interacoesPorEtapa = {

            1:
                "pergunta_1",

            2:
                "pergunta_2",

            3:
                "pergunta_3",

            4:
                "pergunta_4"

        };


        /*
        |--------------------------------------------------------------------------
        | DESCOBRE PONTO DE INÍCIO
        |--------------------------------------------------------------------------
        */

        let interacaoInicial =
            aula.inicio;


        if (
            aulaJaIniciada
            &&
            !aulaJaConcluida
        ) {

            const interacaoSalva =
                interacoesPorEtapa[
                    etapaSalva
                ];


            if (
                interacaoSalva
                &&
                aula.interacoes[
                    interacaoSalva
                ]
            ) {

                interacaoInicial =
                    interacaoSalva;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ESTADO DA AULA
        |--------------------------------------------------------------------------
        */

        let tutorEscolhido =
            null;


        let interacaoAtual =
            interacaoInicial;


        /*
        |--------------------------------------------------------------------------
        | ETAPAS CONCLUÍDAS
        |--------------------------------------------------------------------------
        |
        | etapa 1 = 0
        | etapa 2 = 1
        | etapa 3 = 2
        | etapa 4 = 3
        |
        */

        let etapasConcluidas =
            (
                aulaJaIniciada
                &&
                !aulaJaConcluida
            )
                ? Math.max(
                    0,
                    etapaSalva - 1
                )
                : 0;


        /*
        |--------------------------------------------------------------------------
        | CONCLUSÃO
        |--------------------------------------------------------------------------
        */

        let conclusaoRegistrada =
            aulaJaConcluida;


        let salvandoConclusao =
            false;


        /*
        |--------------------------------------------------------------------------
        | CONTROLE DO SALVAMENTO
        |--------------------------------------------------------------------------
        */

        let ultimaEtapaConfirmada =
            (
                aulaJaIniciada
                &&
                !aulaJaConcluida
            )
                ? etapaSalva
                : 0;


        let ultimaEtapaSolicitada =
            ultimaEtapaConfirmada;


        /*
        |--------------------------------------------------------------------------
        | ELEMENTOS
        |--------------------------------------------------------------------------
        */

        const telaTutor =
            document.getElementById(
                "tela-tutor"
            );


        const telaAula =
            document.getElementById(
                "tela-aula"
            );


        const mascote =
            document.getElementById(
                "mascote-jcc"
            );


        const titulo =
            document.getElementById(
                "interacao-titulo"
            );


        const mensagem =
            document.getElementById(
                "interacao-mensagem"
            );


        const codigoArea =
            document.getElementById(
                "codigo-area"
            );


        const codigoConteudo =
            document.getElementById(
                "codigo-conteudo"
            );


        const perguntaExtra =
            document.getElementById(
                "pergunta-extra"
            );


        const opcoes =
            document.getElementById(
                "opcoes-resposta"
            );


        const etiqueta =
            document.getElementById(
                "tipo-interacao"
            );


        const botaoContinuar =
            document.getElementById(
                "botao-continuar"
            );


        const barra =
            document.getElementById(
                "barra-progresso"
            );


        const textoProgresso =
            document.getElementById(
                "texto-progresso"
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDA ELEMENTOS
        |--------------------------------------------------------------------------
        */

        const elementosObrigatorios = [

            telaTutor,
            telaAula,
            mascote,
            titulo,
            mensagem,
            codigoArea,
            codigoConteudo,
            perguntaExtra,
            opcoes,
            etiqueta,
            botaoContinuar,
            barra,
            textoProgresso

        ];


        const algumElementoFaltando =
            elementosObrigatorios.some(
                function (
                    elemento
                ) {

                    return elemento === null;

                }
            );


        if (
            algumElementoFaltando
        ) {

            console.error(
                "Algum elemento necessário da Aula Conversada não foi encontrado."
            );

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | CLASSES DA BARRA
        |--------------------------------------------------------------------------
        */

        const classesProgresso = [

            "progresso-0",
            "progresso-25",
            "progresso-50",
            "progresso-75",
            "progresso-100"

        ];


        /*
        |--------------------------------------------------------------------------
        | MASCOTES
        |--------------------------------------------------------------------------
        */

        const mascotes = {

            pergunta:
                baseUrl +
                "/frontend/assets/img/mascote/jcc-pergunta.png",

            acerto:
                baseUrl +
                "/frontend/assets/img/mascote/jcc-acerto.png",

            quase:
                baseUrl +
                "/frontend/assets/img/mascote/jcc-quase.png",

            erro:
                baseUrl +
                "/frontend/assets/img/mascote/jcc-erro.png"

        };


        /*
        |--------------------------------------------------------------------------
        | PROGRESSO VISUAL INICIAL
        |--------------------------------------------------------------------------
        */

        atualizarProgresso();


        /*
        |--------------------------------------------------------------------------
        | ESCOLHA DO TUTOR
        |--------------------------------------------------------------------------
        */

        const botoesTutor =
            document.querySelectorAll(
                ".tutor-card"
            );


        botoesTutor.forEach(
            function (
                botao
            ) {

                botao.addEventListener(
                    "click",
                    function () {

                        tutorEscolhido =
                            botao.dataset.tutor;


                        /*
                        |--------------------------------------------------------------------------
                        | VALIDA TUTOR
                        |--------------------------------------------------------------------------
                        */

                        if (
                            tutorEscolhido !== "robert"
                            &&
                            tutorEscolhido !== "natanael"
                        ) {

                            console.error(
                                "Tutor inválido:",
                                tutorEscolhido
                            );

                            return;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | PRIMEIRO ACESSO
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !aulaJaIniciada
                        ) {

                            salvarProgresso(
                                1
                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | TROCA DE TELA
                        |--------------------------------------------------------------------------
                        */

                        telaTutor.hidden =
                            true;


                        telaAula.hidden =
                            false;


                        /*
                        |--------------------------------------------------------------------------
                        | COMEÇA / CONTINUA
                        |--------------------------------------------------------------------------
                        */

                        mostrarInteracao(
                            interacaoInicial
                        );

                    }
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | SALVA PROGRESSO
        |--------------------------------------------------------------------------
        */

        async function salvarProgresso(
            etapa
        ) {

            /*
            |--------------------------------------------------------------------------
            | AULA EM REVISÃO
            |--------------------------------------------------------------------------
            */

            if (
                aulaJaConcluida
            ) {

                return true;

            }


            /*
            |--------------------------------------------------------------------------
            | ROTA
            |--------------------------------------------------------------------------
            */

            if (
                !rotaSalvarProgresso
            ) {

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | NORMALIZA
            |--------------------------------------------------------------------------
            */

            const etapaNormalizada =
                Number.parseInt(
                    etapa,
                    10
                );


            if (
                !Number.isInteger(
                    etapaNormalizada
                )
                ||
                etapaNormalizada < 1
                ||
                etapaNormalizada > 4
            ) {

                console.error(
                    "Etapa inválida para salvamento:",
                    etapa
                );

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | JÁ CONFIRMADA
            |--------------------------------------------------------------------------
            */

            if (
                etapaNormalizada
                <=
                ultimaEtapaConfirmada
            ) {

                return true;

            }


            /*
            |--------------------------------------------------------------------------
            | JÁ SOLICITADA
            |--------------------------------------------------------------------------
            */

            if (
                etapaNormalizada
                ===
                ultimaEtapaSolicitada
            ) {

                return true;

            }


            ultimaEtapaSolicitada =
                etapaNormalizada;


            try {

                /*
                |--------------------------------------------------------------------------
                | POST
                |--------------------------------------------------------------------------
                */

                const resposta =
                    await fetch(
                        rotaSalvarProgresso,
                        {

                            method:
                                "POST",

                            credentials:
                                "same-origin",

                            headers: {

                                "Content-Type":
                                    "application/json",

                                "Accept":
                                    "application/json"

                            },

                            body:
                                JSON.stringify(
                                    {

                                        aula_id:
                                            aulaId,

                                        etapa_atual:
                                            etapaNormalizada

                                    }
                                )

                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | JSON
                |--------------------------------------------------------------------------
                */

                let dados =
                    null;


                try {

                    dados =
                        await resposta.json();

                }
                catch (
                    erroJson
                ) {

                    console.error(
                        "A resposta do salvamento de progresso não é JSON válido.",
                        erroJson
                    );


                    if (
                        ultimaEtapaSolicitada
                        ===
                        etapaNormalizada
                    ) {

                        ultimaEtapaSolicitada =
                            ultimaEtapaConfirmada;

                    }


                    return false;

                }


                /*
                |--------------------------------------------------------------------------
                | VALIDA RESPOSTA
                |--------------------------------------------------------------------------
                */

                if (
                    !resposta.ok
                    ||
                    !dados
                    ||
                    dados.sucesso !== true
                ) {

                    console.error(
                        "Não foi possível salvar o progresso da aula.",
                        dados
                    );


                    if (
                        ultimaEtapaSolicitada
                        ===
                        etapaNormalizada
                    ) {

                        ultimaEtapaSolicitada =
                            ultimaEtapaConfirmada;

                    }


                    return false;

                }


                /*
                |--------------------------------------------------------------------------
                | ETAPA CONFIRMADA PELO SERVIDOR
                |--------------------------------------------------------------------------
                */

                const etapaServidor =
                    Number.parseInt(
                        dados.etapa_atual,
                        10
                    );


                if (
                    Number.isInteger(
                        etapaServidor
                    )
                    &&
                    etapaServidor >= 1
                    &&
                    etapaServidor <= 4
                ) {

                    ultimaEtapaConfirmada =
                        Math.max(
                            ultimaEtapaConfirmada,
                            etapaServidor
                        );

                }
                else {

                    ultimaEtapaConfirmada =
                        Math.max(
                            ultimaEtapaConfirmada,
                            etapaNormalizada
                        );

                }


                ultimaEtapaSolicitada =
                    Math.max(
                        ultimaEtapaSolicitada,
                        ultimaEtapaConfirmada
                    );


                return true;

            }
            catch (
                erro
            ) {

                console.error(
                    "Erro de comunicação ao salvar progresso da aula:",
                    erro
                );


                if (
                    ultimaEtapaSolicitada
                    ===
                    etapaNormalizada
                ) {

                    ultimaEtapaSolicitada =
                        ultimaEtapaConfirmada;

                }


                return false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | MOSTRA INTERAÇÃO
        |--------------------------------------------------------------------------
        */

        function mostrarInteracao(
            id
        ) {

            if (
                typeof id !== "string"
                ||
                id.trim() === ""
            ) {

                console.error(
                    "Identificador de interação inválido:",
                    id
                );

                return;

            }


            const interacao =
                aula.interacoes[
                    id
                ];


            if (
                !interacao
                ||
                typeof interacao !== "object"
            ) {

                console.error(
                    "Interação não encontrada:",
                    id
                );

                return;

            }


            interacaoAtual =
                id;


            limparTela();


            /*
            |--------------------------------------------------------------------------
            | MASCOTE
            |--------------------------------------------------------------------------
            */

            let estadoMascote =
                "pergunta";


            if (
                typeof interacao.resultado === "string"
                &&
                interacao.resultado !== ""
            ) {

                estadoMascote =
                    interacao.resultado;

            }


            trocarMascote(
                estadoMascote
            );


            /*
            |--------------------------------------------------------------------------
            | ETIQUETA
            |--------------------------------------------------------------------------
            */

            if (
                interacao.tipo === "feedback"
            ) {

                etiqueta.hidden =
                    false;


                etiqueta.textContent =
                    tutorEscolhido === "robert"
                        ? "Dica do Robert"
                        : "Dica do Natanael";

            }
            else if (
                interacao.tipo === "final"
            ) {

                etiqueta.hidden =
                    false;


                etiqueta.textContent =
                    "Aula concluída";

            }
            else {

                etiqueta.hidden =
                    true;


                etiqueta.textContent =
                    "";

            }


            /*
            |--------------------------------------------------------------------------
            | TÍTULO
            |--------------------------------------------------------------------------
            */

            titulo.textContent =
                typeof interacao.titulo === "string"
                    ? interacao.titulo
                    : "";


            /*
            |--------------------------------------------------------------------------
            | MENSAGEM
            |--------------------------------------------------------------------------
            */

            let textoMensagem =
                typeof interacao.mensagem === "string"
                    ? interacao.mensagem
                    : "";


            if (
                interacao.tipo === "feedback"
                ||
                interacao.tipo === "final"
            ) {

                if (
                    tutorEscolhido
                    &&
                    typeof interacao[
                        tutorEscolhido
                    ] === "string"
                ) {

                    textoMensagem =
                        interacao[
                            tutorEscolhido
                        ];

                }

            }


            mensagem.textContent =
                textoMensagem;


            /*
            |--------------------------------------------------------------------------
            | CÓDIGO
            |--------------------------------------------------------------------------
            */

            if (
                typeof interacao.codigo === "string"
                &&
                interacao.codigo !== ""
            ) {

                codigoArea.hidden =
                    false;


                codigoConteudo.textContent =
                    interacao.codigo;

            }


            /*
            |--------------------------------------------------------------------------
            | PERGUNTA EXTRA
            |--------------------------------------------------------------------------
            */

            if (
                typeof interacao.pergunta === "string"
                &&
                interacao.pergunta !== ""
            ) {

                perguntaExtra.hidden =
                    false;


                perguntaExtra.textContent =
                    interacao.pergunta;

            }


            /*
            |--------------------------------------------------------------------------
            | FINAL
            |--------------------------------------------------------------------------
            */

            if (
                interacao.tipo === "final"
            ) {

                finalizarAula();

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | OPÇÕES
            |--------------------------------------------------------------------------
            */

            const listaOpcoes =
                Array.isArray(
                    interacao.opcoes
                )
                    ? interacao.opcoes
                    : [];


            criarOpcoes(
                listaOpcoes
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FINALIZA AULA
        |--------------------------------------------------------------------------
        */

        async function finalizarAula() {

            trocarMascote(
                "acerto"
            );


            atualizarProgresso(
                100
            );


            /*
            |--------------------------------------------------------------------------
            | JÁ CONCLUÍDA = REVISÃO
            |--------------------------------------------------------------------------
            */

            if (
                aulaJaConcluida
            ) {

                prepararNavegacaoFinal();

                return;

            }


            botaoContinuar.hidden =
                false;


            botaoContinuar.disabled =
                true;


            botaoContinuar.textContent =
                "Registrando conclusão...";


            const registrado =
                await registrarConclusao();


            /*
            |--------------------------------------------------------------------------
            | FALHA
            |--------------------------------------------------------------------------
            */

            if (
                !registrado
            ) {

                botaoContinuar.disabled =
                    false;


                botaoContinuar.textContent =
                    "Tentar registrar novamente";


                botaoContinuar.onclick =
                    function () {

                        finalizarAula();

                    };


                return;

            }


            prepararNavegacaoFinal();

        }


        /*
        |--------------------------------------------------------------------------
        | NAVEGAÇÃO FINAL
        |--------------------------------------------------------------------------
        */

        function prepararNavegacaoFinal() {

            botaoContinuar.hidden =
                false;


            botaoContinuar.disabled =
                false;


            /*
            |--------------------------------------------------------------------------
            | PRÓXIMA AULA
            |--------------------------------------------------------------------------
            */

            if (
                rotaProximaAula
            ) {

                botaoContinuar.textContent =
                    "Ir para a próxima aula";


                botaoContinuar.onclick =
                    function () {

                        window.location.assign(
                            rotaProximaAula
                        );

                    };


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | VOLTA À LISTA
            |--------------------------------------------------------------------------
            */

            botaoContinuar.textContent =
                "Voltar às aulas";


            botaoContinuar.onclick =
                function () {

                    window.location.assign(
                        rotaListaAulas
                    );

                };

        }


        /*
        |--------------------------------------------------------------------------
        | REGISTRA CONCLUSÃO
        |--------------------------------------------------------------------------
        */

        async function registrarConclusao() {

            if (
                !rotaConcluirAula
            ) {

                return false;

            }


            if (
                conclusaoRegistrada
            ) {

                return true;

            }


            if (
                salvandoConclusao
            ) {

                return false;

            }


            salvandoConclusao =
                true;


            try {

                const resposta =
                    await fetch(
                        rotaConcluirAula,
                        {

                            method:
                                "POST",

                            credentials:
                                "same-origin",

                            headers: {

                                "Content-Type":
                                    "application/json",

                                "Accept":
                                    "application/json"

                            },

                            body:
                                JSON.stringify(
                                    {

                                        aula_id:
                                            aulaId

                                    }
                                )

                        }
                    );


                let dados =
                    null;


                try {

                    dados =
                        await resposta.json();

                }
                catch (
                    erroJson
                ) {

                    console.error(
                        "A resposta do servidor não é JSON válido.",
                        erroJson
                    );


                    return false;

                }


                if (
                    !resposta.ok
                    ||
                    !dados
                    ||
                    dados.sucesso !== true
                ) {

                    console.error(
                        "Não foi possível registrar a conclusão da aula.",
                        dados
                    );


                    return false;

                }


                conclusaoRegistrada =
                    true;


                ultimaEtapaConfirmada =
                    4;


                ultimaEtapaSolicitada =
                    4;


                return true;

            }
            catch (
                erro
            ) {

                console.error(
                    "Erro de comunicação ao registrar a conclusão da aula:",
                    erro
                );


                return false;

            }
            finally {

                salvandoConclusao =
                    false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CRIA OPÇÕES
        |--------------------------------------------------------------------------
        */

        function criarOpcoes(
            lista
        ) {

            if (
                !Array.isArray(
                    lista
                )
            ) {

                return;

            }


            lista.forEach(
                function (
                    opcao,
                    indice
                ) {

                    const botao =
                        document.createElement(
                            "button"
                        );


                    botao.type =
                        "button";


                    botao.className =
                        "opcao-resposta";


                    /*
                    |--------------------------------------------------------------------------
                    | LETRA
                    |--------------------------------------------------------------------------
                    */

                    const letra =
                        document.createElement(
                            "span"
                        );


                    letra.className =
                        "opcao-letra";


                    letra.textContent =
                        String.fromCharCode(
                            65 + indice
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | TEXTO
                    |--------------------------------------------------------------------------
                    */

                    const texto =
                        document.createElement(
                            "span"
                        );


                    texto.textContent =
                        (
                            opcao
                            &&
                            typeof opcao.texto === "string"
                        )
                            ? opcao.texto
                            : "";


                    botao.appendChild(
                        letra
                    );


                    botao.appendChild(
                        texto
                    );


                    botao.addEventListener(
                        "click",
                        function () {

                            responder(
                                opcao,
                                botao
                            );

                        }
                    );


                    opcoes.appendChild(
                        botao
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PROCESSA RESPOSTA
        |--------------------------------------------------------------------------
        */

        function responder(
            opcao,
            botaoSelecionado
        ) {

            if (
                !opcao
                ||
                typeof opcao !== "object"
                ||
                typeof opcao.destino !== "string"
                ||
                opcao.destino === ""
            ) {

                console.error(
                    "Opção inválida.",
                    opcao
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | DESTINO EXISTE?
            |--------------------------------------------------------------------------
            */

            if (
                !aula.interacoes[
                    opcao.destino
                ]
            ) {

                console.error(
                    "Destino inexistente:",
                    opcao.destino
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | BLOQUEIA OPÇÕES
            |--------------------------------------------------------------------------
            */

            const botoes =
                opcoes.querySelectorAll(
                    ".opcao-resposta"
                );


            botoes.forEach(
                function (
                    botao
                ) {

                    botao.disabled =
                        true;

                }
            );


            /*
            |--------------------------------------------------------------------------
            | RESULTADO
            |--------------------------------------------------------------------------
            */

            let resultado =
                typeof opcao.resultado === "string"
                    ? opcao.resultado
                    : "erro";


            if (
                ![
                    "acerto",
                    "quase",
                    "erro"
                ].includes(
                    resultado
                )
            ) {

                resultado =
                    "erro";

            }


            /*
            |--------------------------------------------------------------------------
            | MARCA ESCOLHA
            |--------------------------------------------------------------------------
            */

            botaoSelecionado.classList.add(
                "selecionada"
            );


            botaoSelecionado.classList.add(
                "resultado-" +
                resultado
            );


            trocarMascote(
                resultado
            );


            /*
            |--------------------------------------------------------------------------
            | AVANÇO PRINCIPAL
            |--------------------------------------------------------------------------
            */

            const avancouEtapa =
                resultado === "acerto"
                &&
                ehAvancoPrincipal(
                    interacaoAtual,
                    opcao.destino
                );


            if (
                avancouEtapa
            ) {

                etapasConcluidas =
                    Math.min(
                        4,
                        etapasConcluidas + 1
                    );


                atualizarProgresso();


                const etapaBanco =
                    Math.min(
                        4,
                        etapasConcluidas + 1
                    );


                salvarProgresso(
                    etapaBanco
                );

            }


            /*
            |--------------------------------------------------------------------------
            | PRÓXIMA INTERAÇÃO
            |--------------------------------------------------------------------------
            */

            window.setTimeout(
                function () {

                    mostrarInteracao(
                        opcao.destino
                    );

                },
                700
            );

        }


        /*
        |--------------------------------------------------------------------------
        | AVANÇOS PRINCIPAIS
        |--------------------------------------------------------------------------
        */

        function ehAvancoPrincipal(
            origem,
            destino
        ) {

            const avancosPrincipais = [

                [
                    "pergunta_1",
                    "pergunta_2"
                ],

                [
                    "pergunta_1_quase",
                    "pergunta_2"
                ],

                [
                    "pergunta_1_erro",
                    "pergunta_2"
                ],

                [
                    "pergunta_1_nao_sei",
                    "pergunta_2"
                ],


                [
                    "pergunta_2",
                    "pergunta_3"
                ],

                [
                    "pergunta_2_erro",
                    "pergunta_3"
                ],

                [
                    "pergunta_2_dica",
                    "pergunta_3"
                ],


                [
                    "pergunta_3",
                    "pergunta_4"
                ],

                [
                    "pergunta_3_erro",
                    "pergunta_4"
                ],

                [
                    "pergunta_3_dica",
                    "pergunta_4"
                ],


                [
                    "pergunta_4",
                    "final"
                ],

                [
                    "pergunta_4_erro",
                    "final"
                ],

                [
                    "pergunta_4_dica",
                    "final"
                ]

            ];


            return avancosPrincipais.some(
                function (
                    avanco
                ) {

                    return (
                        origem === avanco[0]
                        &&
                        destino === avanco[1]
                    );

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TROCA MASCOTE
        |--------------------------------------------------------------------------
        */

        function trocarMascote(
            estado
        ) {

            if (
                !Object.prototype.hasOwnProperty.call(
                    mascotes,
                    estado
                )
            ) {

                estado =
                    "pergunta";

            }


            mascote.classList.add(
                "mascote-trocando"
            );


            window.setTimeout(
                function () {

                    mascote.src =
                        mascotes[
                            estado
                        ];


                    mascote.alt =
                        obterAltMascote(
                            estado
                        );


                    window.setTimeout(
                        function () {

                            mascote.classList.remove(
                                "mascote-trocando"
                            );

                        },
                        100
                    );

                },
                120
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ALT DO MASCOTE
        |--------------------------------------------------------------------------
        */

        function obterAltMascote(
            estado
        ) {

            const textos = {

                pergunta:
                    "Mascote J.C.C. fazendo uma pergunta",

                acerto:
                    "Mascote J.C.C. comemorando uma resposta correta",

                quase:
                    "Mascote J.C.C. ajudando o estudante a pensar",

                erro:
                    "Mascote J.C.C. reagindo a uma resposta incorreta"

            };


            return textos[
                estado
            ]
            ??
            "Mascote J.C.C.";

        }


        /*
        |--------------------------------------------------------------------------
        | PROGRESSO VISUAL
        |--------------------------------------------------------------------------
        */

        function atualizarProgresso(
            forcar
        ) {

            const totalPrincipal =
                4;


            let percentual =
                0;


            /*
            |--------------------------------------------------------------------------
            | VALOR FORÇADO
            |--------------------------------------------------------------------------
            */

            if (
                typeof forcar === "number"
                &&
                Number.isFinite(
                    forcar
                )
            ) {

                percentual =
                    forcar;

            }
            else {

                percentual =
                    (
                        etapasConcluidas
                        /
                        totalPrincipal
                    )
                    *
                    100;

            }


            /*
            |--------------------------------------------------------------------------
            | LIMITA 0–100
            |--------------------------------------------------------------------------
            */

            percentual =
                Math.max(
                    0,
                    Math.min(
                        100,
                        percentual
                    )
                );


            /*
            |--------------------------------------------------------------------------
            | NORMALIZA PARA OS 5 ESTADOS VISUAIS
            |--------------------------------------------------------------------------
            |
            | Como a aula possui quatro etapas:
            |
            | 0%
            | 25%
            | 50%
            | 75%
            | 100%
            |
            */

            const niveisPermitidos = [

                0,
                25,
                50,
                75,
                100

            ];


            let nivelVisual =
                niveisPermitidos.reduce(
                    function (
                        maisProximo,
                        nivel
                    ) {

                        const distanciaAtual =
                            Math.abs(
                                nivel - percentual
                            );


                        const distanciaAnterior =
                            Math.abs(
                                maisProximo - percentual
                            );


                        return distanciaAtual
                            <
                            distanciaAnterior
                                ? nivel
                                : maisProximo;

                    },
                    0
                );


            /*
            |--------------------------------------------------------------------------
            | REMOVE ESTADO ANTERIOR
            |--------------------------------------------------------------------------
            */

            barra.classList.remove(
                ...classesProgresso
            );


            /*
            |--------------------------------------------------------------------------
            | ADICIONA NOVO ESTADO
            |--------------------------------------------------------------------------
            */

            barra.classList.add(
                "progresso-" +
                nivelVisual
            );


            /*
            |--------------------------------------------------------------------------
            | TEXTO
            |--------------------------------------------------------------------------
            */

            textoProgresso.textContent =
                nivelVisual +
                "%";

        }


        /*
        |--------------------------------------------------------------------------
        | LIMPA TELA
        |--------------------------------------------------------------------------
        */

        function limparTela() {

            etiqueta.hidden =
                true;


            etiqueta.textContent =
                "";


            titulo.textContent =
                "";


            mensagem.textContent =
                "";


            codigoConteudo.textContent =
                "";


            codigoArea.hidden =
                true;


            perguntaExtra.hidden =
                true;


            perguntaExtra.textContent =
                "";


            opcoes.replaceChildren();


            botaoContinuar.hidden =
                true;


            botaoContinuar.disabled =
                false;


            botaoContinuar.onclick =
                null;

        }

    }
);