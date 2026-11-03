/*
|--------------------------------------------------------------------------
| ASSISTENTE JAVA
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Responsável por:
|
| - abrir e fechar o mini-chat;
| - carregar o histórico salvo;
| - carregar a IA somente quando necessário;
| - gerar respostas localmente no navegador;
| - salvar pergunta e resposta no backend;
| - iniciar uma nova conversa;
| - apresentar progresso do modelo;
| - limitar entradas;
| - utilizar contexto da página;
| - renderizar respostas com segurança.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| URL DO SCRIPT
|--------------------------------------------------------------------------
|
| Capturada imediatamente porque document.currentScript pode deixar
| de estar disponível depois do carregamento da página.
|
|--------------------------------------------------------------------------
*/

const assistenteIaScriptUrl =
    document.currentScript?.src
    ?? "";


/*
|--------------------------------------------------------------------------
| INICIALIZAÇÃO
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "DOMContentLoaded",
    () => {

        /*
        |--------------------------------------------------------------------------
        | COMPONENTE
        |--------------------------------------------------------------------------
        */

        const assistente =
            document.getElementById(
                "assistenteIa"
            );


        if (
            !assistente
        ) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ELEMENTOS
        |--------------------------------------------------------------------------
        */

        const botaoAbrir =
            document.getElementById(
                "assistenteIaBotao"
            );


        const janela =
            document.getElementById(
                "assistenteIaJanela"
            );


        const botaoFechar =
            document.getElementById(
                "assistenteIaFechar"
            );


        const botaoNovaConversa =
            document.getElementById(
                "assistenteIaNovaConversa"
            );


        const formulario =
            document.getElementById(
                "assistenteIaFormulario"
            );


        const campo =
            document.getElementById(
                "assistenteIaCampo"
            );


        const botaoEnviar =
            document.getElementById(
                "assistenteIaEnviar"
            );


        const mensagens =
            document.getElementById(
                "assistenteIaMensagens"
            );


        const mensagemInicial =
            document.getElementById(
                "assistenteIaMensagemInicial"
            );


        const digitando =
            document.getElementById(
                "assistenteIaDigitando"
            );


        const erro =
            document.getElementById(
                "assistenteIaErro"
            );


        const contador =
            document.getElementById(
                "assistenteIaContador"
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDA ESTRUTURA
        |--------------------------------------------------------------------------
        */

        if (
            !botaoAbrir
            ||
            !janela
            ||
            !botaoFechar
            ||
            !botaoNovaConversa
            ||
            !formulario
            ||
            !campo
            ||
            !botaoEnviar
            ||
            !mensagens
            ||
            !mensagemInicial
            ||
            !digitando
            ||
            !erro
            ||
            !contador
        ) {

            console.error(
                "[Assistente Java] Estrutura do componente incompleta."
            );


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIGURAÇÕES
        |--------------------------------------------------------------------------
        */

        const LIMITE_MENSAGEM =
            4000;


        const LIMITE_HISTORICO_LOCAL =
            20;


        const LIMITE_HISTORICO_VISUAL =
            50;


        const LIMITE_ITEM_HISTORICO =
            4000;


        const LIMITE_CONTEXTO_PAGINA =
            800;


        const TIMEOUT_REQUISICAO =
            15000;


        /*
        |--------------------------------------------------------------------------
        | TOKEN CSRF
        |--------------------------------------------------------------------------
        |
        | Será gerado pelo componente PHP.
        |
        |--------------------------------------------------------------------------
        */

        const csrfToken =
            String(
                assistente.dataset.csrfToken
                ?? ""
            ).trim();


        if (
            csrfToken === ""
        ) {

            console.error(
                "[Assistente Java] Token CSRF não configurado."
            );


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ENDPOINT SEGURO
        |--------------------------------------------------------------------------
        |
        | Só aceitamos endpoints da própria aplicação.
        |
        |--------------------------------------------------------------------------
        */

        function normalizarEndpoint(
            valor,
            nome
        ) {

            const texto =
                String(
                    valor ?? ""
                ).trim();


            if (
                texto === ""
            ) {

                throw new Error(
                    `Endpoint ${nome} não configurado.`
                );

            }


            let url;


            try {

                url =
                    new URL(
                        texto,
                        window.location.href
                    );

            }
            catch {

                throw new Error(
                    `Endpoint ${nome} inválido.`
                );

            }


            if (
                url.origin !==
                window.location.origin
            ) {

                throw new Error(
                    `Endpoint ${nome} não pertence à aplicação.`
                );

            }


            if (
                url.protocol !== "http:"
                &&
                url.protocol !== "https:"
            ) {

                throw new Error(
                    `Endpoint ${nome} utiliza protocolo inválido.`
                );

            }


            return url.href;

        }


        /*
        |--------------------------------------------------------------------------
        | ENDPOINTS
        |--------------------------------------------------------------------------
        */

        let endpoint;
        let historicoEndpoint;
        let limparEndpoint;


        try {

            endpoint =
                normalizarEndpoint(
                    assistente.dataset.endpoint,
                    "de salvamento"
                );


            historicoEndpoint =
                normalizarEndpoint(
                    assistente.dataset.historicoEndpoint,
                    "de histórico"
                );


            limparEndpoint =
                normalizarEndpoint(
                    assistente.dataset.limparEndpoint,
                    "de limpeza"
                );

        }
        catch (
            erroConfiguracao
        ) {

            console.error(
                "[Assistente Java] Configuração inválida:",
                erroConfiguracao
            );


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ESTADO
        |--------------------------------------------------------------------------
        */

        let enviando =
            false;


        let historicoCarregado =
            false;


        let historicoCarregando =
            null;


        let limpandoHistorico =
            false;


        let historicoModelo =
            [];


        /*
        |--------------------------------------------------------------------------
        | MODELO
        |--------------------------------------------------------------------------
        */

        let moduloModelo =
            null;


        let carregandoModuloModelo =
            null;


        let preparandoModelo =
            null;


        let modeloPronto =
            false;


        /*
        |--------------------------------------------------------------------------
        | CONTADOR
        |--------------------------------------------------------------------------
        */

        let contadorEmModoStatus =
            false;


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAR TEXTO
        |--------------------------------------------------------------------------
        */

        function normalizarTexto(
            valor
        ) {

            return String(
                valor ?? ""
            )
                .replace(
                    /\u0000/g,
                    ""
                )
                .replace(
                    /\r\n?/g,
                    "\n"
                )
                .trim();

        }


        /*
        |--------------------------------------------------------------------------
        | FETCH JSON COM TIMEOUT
        |--------------------------------------------------------------------------
        */

        async function requisicaoJson(
            url,
            opcoes = {},
            timeout = TIMEOUT_REQUISICAO
        ) {

            const controller =
                new AbortController();


            const temporizador =
                window.setTimeout(
                    () => {

                        controller.abort();

                    },
                    timeout
                );


            try {

                const resposta =
                    await fetch(
                        url,
                        {
                            ...opcoes,

                            signal:
                                controller.signal,

                            credentials:
                                "same-origin",

                            cache:
                                "no-store"
                        }
                    );


                let dados;


                try {

                    dados =
                        await resposta.json();

                }
                catch {

                    throw new Error(
                        "O servidor retornou uma resposta inválida."
                    );

                }


                if (
                    !resposta.ok
                ) {

                    throw new Error(
                        dados?.mensagem
                        ||
                        `Erro HTTP ${resposta.status}.`
                    );

                }


                if (
                    !dados
                    ||
                    typeof dados !==
                        "object"
                ) {

                    throw new Error(
                        "O servidor retornou dados inválidos."
                    );

                }


                return dados;

            }
            catch (
                erroRequisicao
            ) {

                if (
                    erroRequisicao?.name ===
                    "AbortError"
                ) {

                    throw new Error(
                        "A comunicação com o servidor demorou mais que o esperado."
                    );

                }


                throw erroRequisicao;

            }
            finally {

                window.clearTimeout(
                    temporizador
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRMAÇÃO DA NOVA CONVERSA
        |--------------------------------------------------------------------------
        */

        const confirmacao =
            document.createElement(
                "div"
            );


        confirmacao.className =
            "assistente-ia-confirmacao";


        confirmacao.id =
            "assistenteIaConfirmacao";


        confirmacao.hidden =
            true;


        confirmacao.setAttribute(
            "role",
            "alertdialog"
        );


        confirmacao.setAttribute(
            "aria-modal",
            "true"
        );


        confirmacao.setAttribute(
            "aria-labelledby",
            "assistenteIaConfirmacaoTitulo"
        );


        confirmacao.setAttribute(
            "aria-describedby",
            "assistenteIaConfirmacaoDescricao"
        );


        /*
        |--------------------------------------------------------------------------
        | CONTEÚDO
        |--------------------------------------------------------------------------
        */

        const confirmacaoConteudo =
            document.createElement(
                "div"
            );


        confirmacaoConteudo.className =
            "assistente-ia-confirmacao-conteudo";


        /*
        |--------------------------------------------------------------------------
        | ÍCONE
        |--------------------------------------------------------------------------
        */

        const confirmacaoIcone =
            document.createElement(
                "span"
            );


        confirmacaoIcone.className =
            "assistente-ia-confirmacao-icone";


        confirmacaoIcone.textContent =
            "?";


        confirmacaoIcone.setAttribute(
            "aria-hidden",
            "true"
        );


        /*
        |--------------------------------------------------------------------------
        | TEXTO
        |--------------------------------------------------------------------------
        */

        const confirmacaoTexto =
            document.createElement(
                "div"
            );


        confirmacaoTexto.className =
            "assistente-ia-confirmacao-texto";


        const confirmacaoTitulo =
            document.createElement(
                "strong"
            );


        confirmacaoTitulo.id =
            "assistenteIaConfirmacaoTitulo";


        confirmacaoTitulo.textContent =
            "Iniciar nova conversa?";


        const confirmacaoDescricao =
            document.createElement(
                "p"
            );


        confirmacaoDescricao.id =
            "assistenteIaConfirmacaoDescricao";


        confirmacaoDescricao.textContent =
            "O histórico atual desta conversa será apagado. Essa ação não poderá ser desfeita.";


        confirmacaoTexto.appendChild(
            confirmacaoTitulo
        );


        confirmacaoTexto.appendChild(
            confirmacaoDescricao
        );


        /*
        |--------------------------------------------------------------------------
        | AÇÕES
        |--------------------------------------------------------------------------
        */

        const confirmacaoAcoes =
            document.createElement(
                "div"
            );


        confirmacaoAcoes.className =
            "assistente-ia-confirmacao-acoes";


        const botaoCancelarLimpeza =
            document.createElement(
                "button"
            );


        botaoCancelarLimpeza.type =
            "button";


        botaoCancelarLimpeza.className =
            "assistente-ia-confirmacao-cancelar";


        botaoCancelarLimpeza.textContent =
            "Cancelar";


        const botaoConfirmarLimpeza =
            document.createElement(
                "button"
            );


        botaoConfirmarLimpeza.type =
            "button";


        botaoConfirmarLimpeza.className =
            "assistente-ia-confirmacao-confirmar";


        botaoConfirmarLimpeza.textContent =
            "Nova conversa";


        confirmacaoAcoes.appendChild(
            botaoCancelarLimpeza
        );


        confirmacaoAcoes.appendChild(
            botaoConfirmarLimpeza
        );


        confirmacaoConteudo.appendChild(
            confirmacaoIcone
        );


        confirmacaoConteudo.appendChild(
            confirmacaoTexto
        );


        confirmacao.appendChild(
            confirmacaoConteudo
        );


        confirmacao.appendChild(
            confirmacaoAcoes
        );


        janela.insertBefore(
            confirmacao,
            formulario
        );


        /*
        |--------------------------------------------------------------------------
        | ROLAR
        |--------------------------------------------------------------------------
        */

        function rolarParaFinal() {

            mensagens.scrollTop =
                mensagens.scrollHeight;

        }


        /*
        |--------------------------------------------------------------------------
        | ABRIR CHAT
        |--------------------------------------------------------------------------
        */

        function abrirChat() {

            janela.setAttribute(
                "aria-hidden",
                "false"
            );


            botaoAbrir.setAttribute(
                "aria-expanded",
                "true"
            );


            /*
            |--------------------------------------------------------------------------
            | HISTÓRICO É CARREGADO SOMENTE QUANDO O CHAT FOR UTILIZADO
            |--------------------------------------------------------------------------
            */

            void carregarHistoricoBanco();


            window.setTimeout(
                () => {

                    campo.focus();


                    rolarParaFinal();

                },
                100
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FECHAR CHAT
        |--------------------------------------------------------------------------
        */

        function fecharChat() {

            fecharConfirmacao();


            janela.setAttribute(
                "aria-hidden",
                "true"
            );


            botaoAbrir.setAttribute(
                "aria-expanded",
                "false"
            );


            botaoAbrir.focus();

        }


        /*
        |--------------------------------------------------------------------------
        | ALTERNAR
        |--------------------------------------------------------------------------
        */

        function alternarChat() {

            const aberto =
                janela.getAttribute(
                    "aria-hidden"
                )
                ===
                "false";


            if (
                aberto
            ) {

                fecharChat();

            }
            else {

                abrirChat();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRMAÇÃO
        |--------------------------------------------------------------------------
        */

        function abrirConfirmacao() {

            if (
                enviando
                ||
                limpandoHistorico
            ) {

                return;

            }


            esconderErro();


            confirmacao.hidden =
                false;


            campo.disabled =
                true;


            botaoEnviar.disabled =
                true;


            botaoConfirmarLimpeza.focus();

        }


        function fecharConfirmacao() {

            confirmacao.hidden =
                true;


            if (
                !enviando
                &&
                !limpandoHistorico
            ) {

                campo.disabled =
                    false;


                botaoEnviar.disabled =
                    false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CONTADOR
        |--------------------------------------------------------------------------
        */

        function atualizarContador() {

            if (
                contadorEmModoStatus
            ) {

                return;

            }


            const quantidade =
                campo.value.length;


            contador.textContent =
                `${quantidade} / ${LIMITE_MENSAGEM}`;


            contador.classList.toggle(
                "assistente-ia-contador-limite",
                quantidade >=
                    LIMITE_MENSAGEM
            );

        }


        /*
        |--------------------------------------------------------------------------
        | STATUS DA IA
        |--------------------------------------------------------------------------
        */

        function mostrarStatusModelo(
            texto
        ) {

            contadorEmModoStatus =
                true;


            contador.classList.remove(
                "assistente-ia-contador-limite"
            );


            contador.textContent =
                texto;

        }


        function restaurarContador() {

            contadorEmModoStatus =
                false;


            atualizarContador();

        }


        /*
        |--------------------------------------------------------------------------
        | TEXTAREA
        |--------------------------------------------------------------------------
        */

        function ajustarTextarea() {

            campo.style.height =
                "auto";


            campo.style.height =
                Math.min(
                    campo.scrollHeight,
                    120
                )
                +
                "px";

        }


        /*
        |--------------------------------------------------------------------------
        | ERRO
        |--------------------------------------------------------------------------
        */

        function mostrarErro(
            mensagem
        ) {

            erro.textContent =
                normalizarTexto(
                    mensagem
                )
                ||
                "Ocorreu um erro.";


            erro.hidden =
                false;

        }


        function esconderErro() {

            erro.textContent =
                "";


            erro.hidden =
                true;

        }


        /*
        |--------------------------------------------------------------------------
        | PROCESSAMENTO
        |--------------------------------------------------------------------------
        */

        function definirCarregando(
            estado
        ) {

            enviando =
                Boolean(
                    estado
                );


            botaoEnviar.disabled =
                enviando;


            campo.disabled =
                enviando;


            botaoNovaConversa.disabled =
                enviando;


            digitando.hidden =
                !enviando;


            janela.setAttribute(
                "aria-busy",
                enviando
                    ?
                    "true"
                    :
                    "false"
            );


            if (
                enviando
            ) {

                rolarParaFinal();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ESCAPAR HTML
        |--------------------------------------------------------------------------
        */

        function escaparHtml(
            texto
        ) {

            return String(
                texto ?? ""
            )
                .replaceAll(
                    "&",
                    "&amp;"
                )
                .replaceAll(
                    "<",
                    "&lt;"
                )
                .replaceAll(
                    ">",
                    "&gt;"
                )
                .replaceAll(
                    '"',
                    "&quot;"
                )
                .replaceAll(
                    "'",
                    "&#039;"
                );

        }


        /*
        |--------------------------------------------------------------------------
        | TEXTO NORMAL
        |--------------------------------------------------------------------------
        */

        function formatarTextoNormal(
            texto
        ) {

            let seguro =
                escaparHtml(
                    texto
                );


            /*
            |--------------------------------------------------------------------------
            | CÓDIGO INLINE
            |--------------------------------------------------------------------------
            */

            seguro =
                seguro.replace(
                    /`([^`\n]+)`/g,
                    "<code>$1</code>"
                );


            /*
            |--------------------------------------------------------------------------
            | NEGRITO SIMPLES
            |--------------------------------------------------------------------------
            */

            seguro =
                seguro.replace(
                    /\*\*([^*\n]+)\*\*/g,
                    "<strong>$1</strong>"
                );


            seguro =
                seguro.replace(
                    /\n/g,
                    "<br>"
                );


            return seguro;

        }


        /*
        |--------------------------------------------------------------------------
        | RESPOSTA FORMATADA
        |--------------------------------------------------------------------------
        |
        | Todo conteúdo é escapado antes de entrar no innerHTML.
        |
        |--------------------------------------------------------------------------
        */

        function formatarResposta(
            texto
        ) {

            const partes =
                normalizarTexto(
                    texto
                ).split(
                    /```/
                );


            let html =
                "";


            partes.forEach(
                (
                    parte,
                    indice
                ) => {

                    /*
                    |--------------------------------------------------------------------------
                    | TEXTO
                    |--------------------------------------------------------------------------
                    */

                    if (
                        indice % 2 ===
                        0
                    ) {

                        html +=
                            formatarTextoNormal(
                                parte
                            );


                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | CÓDIGO
                    |--------------------------------------------------------------------------
                    */

                    let codigo =
                        parte.replace(
                            /^(java|php|javascript|js|html|css|sql)\s*\n?/i,
                            ""
                        );


                    codigo =
                        codigo.trim();


                    html +=
                        "<pre><code>"
                        +
                        escaparHtml(
                            codigo
                        )
                        +
                        "</code></pre>";

                }
            );


            return html;

        }


        /*
        |--------------------------------------------------------------------------
        | MENSAGEM DO USUÁRIO
        |--------------------------------------------------------------------------
        */

        function adicionarMensagemUsuario(
            texto,
            rolar = true
        ) {

            texto =
                normalizarTexto(
                    texto
                );


            if (
                texto === ""
            ) {

                return;

            }


            const elemento =
                document.createElement(
                    "div"
                );


            elemento.className =
                "assistente-ia-mensagem assistente-ia-mensagem-usuario";


            const balao =
                document.createElement(
                    "div"
                );


            balao.className =
                "assistente-ia-balao";


            const paragrafo =
                document.createElement(
                    "p"
                );


            paragrafo.textContent =
                texto;


            balao.appendChild(
                paragrafo
            );


            elemento.appendChild(
                balao
            );


            mensagens.appendChild(
                elemento
            );


            if (
                rolar
            ) {

                rolarParaFinal();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | MENSAGEM DA IA
        |--------------------------------------------------------------------------
        */

        function adicionarMensagemAssistente(
            texto,
            rolar = true
        ) {

            texto =
                normalizarTexto(
                    texto
                );


            if (
                texto === ""
            ) {

                return;

            }


            const elemento =
                document.createElement(
                    "div"
                );


            elemento.className =
                "assistente-ia-mensagem assistente-ia-mensagem-assistente";


            const avatar =
                document.createElement(
                    "div"
                );


            avatar.className =
                "assistente-ia-mensagem-avatar";


            avatar.setAttribute(
                "aria-hidden",
                "true"
            );


            avatar.textContent =
                "✦";


            const balao =
                document.createElement(
                    "div"
                );


            balao.className =
                "assistente-ia-balao";


            const nome =
                document.createElement(
                    "strong"
                );


            nome.textContent =
                "Assistente Java";


            const resposta =
                document.createElement(
                    "div"
                );


            resposta.className =
                "assistente-ia-resposta";


            /*
            |--------------------------------------------------------------------------
            | FORMATADOR ESCAPA O TEXTO ANTES DE PRODUZIR HTML
            |--------------------------------------------------------------------------
            */

            resposta.innerHTML =
                formatarResposta(
                    texto
                );


            balao.appendChild(
                nome
            );


            balao.appendChild(
                resposta
            );


            elemento.appendChild(
                avatar
            );


            elemento.appendChild(
                balao
            );


            mensagens.appendChild(
                elemento
            );


            if (
                rolar
            ) {

                rolarParaFinal();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | LIMPAR CHAT VISUAL
        |--------------------------------------------------------------------------
        */

        function limparMensagensVisuais() {

            mensagemInicial.hidden =
                false;


            mensagens.replaceChildren(
                mensagemInicial
            );


            rolarParaFinal();

        }


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO LOCAL DO MODELO
        |--------------------------------------------------------------------------
        */

        function adicionarHistoricoModelo(
            role,
            content
        ) {

            role =
                String(
                    role ?? ""
                ).trim();


            content =
                normalizarTexto(
                    content
                );


            if (
                (
                    role !== "user"
                    &&
                    role !== "assistant"
                )
                ||
                content === ""
            ) {

                return;

            }


            content =
                content.slice(
                    0,
                    LIMITE_ITEM_HISTORICO
                );


            historicoModelo.push(
                {
                    role,
                    content
                }
            );


            if (
                historicoModelo.length >
                LIMITE_HISTORICO_LOCAL
            ) {

                historicoModelo =
                    historicoModelo.slice(
                        -LIMITE_HISTORICO_LOCAL
                    );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO DO BANCO
        |--------------------------------------------------------------------------
        */

        async function carregarHistoricoBanco() {

            if (
                historicoCarregado
            ) {

                return historicoModelo;

            }


            if (
                historicoCarregando
            ) {

                await historicoCarregando;


                return historicoModelo;

            }


            historicoCarregando =
                (
                    async () => {

                        try {

                            const dados =
                                await requisicaoJson(
                                    historicoEndpoint,
                                    {

                                        method:
                                            "GET",

                                        headers: {

                                            "Accept":
                                                "application/json"

                                        }

                                    }
                                );


                            if (
                                !dados.status
                            ) {

                                throw new Error(
                                    dados.mensagem
                                    ||
                                    "Não foi possível carregar o histórico."
                                );

                            }


                            const historico =
                                Array.isArray(
                                    dados.historico
                                )
                                    ?
                                    dados.historico.slice(
                                        -LIMITE_HISTORICO_VISUAL
                                    )
                                    :
                                    [];


                            historicoModelo =
                                [];


                            /*
                            |--------------------------------------------------------------------------
                            | EVITA DUPLICAR O MESMO HISTÓRICO CASO A FUNÇÃO MUDE
                            | DE ESTADO NO FUTURO.
                            |--------------------------------------------------------------------------
                            */

                            mensagens
                                .querySelectorAll(
                                    ".assistente-ia-mensagem"
                                )
                                .forEach(
                                    elemento => {

                                        elemento.remove();

                                    }
                                );


                            if (
                                historico.length >
                                0
                            ) {

                                mensagemInicial.hidden =
                                    true;

                            }


                            historico.forEach(
                                item => {

                                    if (
                                        !item
                                        ||
                                        typeof item !==
                                            "object"
                                    ) {

                                        return;

                                    }


                                    const role =
                                        String(
                                            item.role ?? ""
                                        ).trim();


                                    const content =
                                        normalizarTexto(
                                            item.content
                                        ).slice(
                                            0,
                                            LIMITE_ITEM_HISTORICO
                                        );


                                    if (
                                        content === ""
                                    ) {

                                        return;

                                    }


                                    if (
                                        role ===
                                        "user"
                                    ) {

                                        adicionarMensagemUsuario(
                                            content,
                                            false
                                        );


                                        adicionarHistoricoModelo(
                                            "user",
                                            content
                                        );


                                        return;

                                    }


                                    if (
                                        role ===
                                        "assistant"
                                    ) {

                                        adicionarMensagemAssistente(
                                            content,
                                            false
                                        );


                                        adicionarHistoricoModelo(
                                            "assistant",
                                            content
                                        );

                                    }

                                }
                            );


                            historicoCarregado =
                                true;


                            rolarParaFinal();

                        }
                        catch (
                            erroHistorico
                        ) {

                            console.error(
                                "[Assistente Java] Erro ao carregar histórico:",
                                erroHistorico
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | O HISTÓRICO É OPCIONAL PARA A IA FUNCIONAR.
                            |--------------------------------------------------------------------------
                            */

                            historicoModelo =
                                [];


                            historicoCarregado =
                                true;

                        }

                    }
                )();


            try {

                await historicoCarregando;

            }
            finally {

                historicoCarregando =
                    null;

            }


            return historicoModelo;

        }


        /*
        |--------------------------------------------------------------------------
        | PÁGINA ATUAL
        |--------------------------------------------------------------------------
        */

        function obterPaginaAtual() {

            const personalizada =
                normalizarTexto(
                    document.body.dataset
                        .assistentePagina
                    ?? ""
                );


            if (
                personalizada !==
                ""
            ) {

                return personalizada.slice(
                    0,
                    120
                );

            }


            return normalizarTexto(
                document.title
            ).slice(
                0,
                120
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CONTEXTO DA PÁGINA
        |--------------------------------------------------------------------------
        |
        | O contexto é utilizado SOMENTE pelo modelo local.
        |
        | Não precisamos enviar isso para o backend.
        |
        |--------------------------------------------------------------------------
        */

        function obterContextoPagina() {

            const contextoBody =
                normalizarTexto(
                    document.body.dataset
                        .assistenteContexto
                    ?? ""
                );


            if (
                contextoBody !==
                ""
            ) {

                return contextoBody.slice(
                    0,
                    LIMITE_CONTEXTO_PAGINA
                );

            }


            const meta =
                document.querySelector(
                    'meta[name="assistente-contexto"]'
                );


            if (
                meta
                &&
                meta.content
            ) {

                return normalizarTexto(
                    meta.content
                ).slice(
                    0,
                    LIMITE_CONTEXTO_PAGINA
                );

            }


            return "";

        }


        /*
        |--------------------------------------------------------------------------
        | URL DO MÓDULO DO MODELO
        |--------------------------------------------------------------------------
        */

        function obterUrlModuloModelo() {

            if (
                !assistenteIaScriptUrl
            ) {

                throw new Error(
                    "Não foi possível localizar o módulo da inteligência artificial."
                );

            }


            const scriptAtual =
                new URL(
                    assistenteIaScriptUrl,
                    window.location.href
                );


            const urlModelo =
                new URL(
                    "assistente_modelo.js",
                    scriptAtual
                );


            /*
            |--------------------------------------------------------------------------
            | HERDA A VERSÃO DO SCRIPT PRINCIPAL
            |--------------------------------------------------------------------------
            |
            | Se assistente_ia.js estiver como:
            |
            | ?v=123
            |
            | o assistente_modelo.js também usará ?v=123.
            |
            |--------------------------------------------------------------------------
            */

            const versao =
                scriptAtual.searchParams.get(
                    "v"
                );


            if (
                versao
            ) {

                urlModelo.searchParams.set(
                    "v",
                    versao
                );

            }


            if (
                urlModelo.origin !==
                window.location.origin
            ) {

                throw new Error(
                    "O módulo da inteligência artificial possui origem inválida."
                );

            }


            return urlModelo.href;

        }


        /*
        |--------------------------------------------------------------------------
        | CARREGAR MÓDULO
        |--------------------------------------------------------------------------
        */

        async function carregarModuloModelo() {

            if (
                moduloModelo
            ) {

                return moduloModelo;

            }


            if (
                carregandoModuloModelo
            ) {

                return carregandoModuloModelo;

            }


            carregandoModuloModelo =
                (
                    async () => {

                        try {

                            const url =
                                obterUrlModuloModelo();


                            const modulo =
                                await import(
                                    url
                                );


                            if (
                                typeof modulo.carregarModelo !==
                                    "function"
                                ||
                                typeof modulo.gerarResposta !==
                                    "function"
                            ) {

                                throw new Error(
                                    "O módulo da inteligência artificial está incompleto."
                                );

                            }


                            moduloModelo =
                                modulo;


                            return moduloModelo;

                        }
                        catch (
                            erroModulo
                        ) {

                            console.error(
                                "[Assistente Java] Erro ao carregar módulo:",
                                erroModulo
                            );


                            throw new Error(
                                "Não foi possível iniciar a inteligência artificial."
                            );

                        }
                        finally {

                            carregandoModuloModelo =
                                null;

                        }

                    }
                )();


            return carregandoModuloModelo;

        }


        /*
        |--------------------------------------------------------------------------
        | PROGRESSO DO MODELO
        |--------------------------------------------------------------------------
        */

        function atualizarProgressoModelo(
            progresso
        ) {

            if (
                !progresso
                ||
                typeof progresso !==
                    "object"
            ) {

                mostrarStatusModelo(
                    "Preparando IA..."
                );


                return;

            }


            const valor =
                Number(
                    progresso.progress
                );


            if (
                Number.isFinite(
                    valor
                )
            ) {

                let percentual =
                    valor;


                if (
                    percentual <=
                    1
                ) {

                    percentual *=
                        100;

                }


                percentual =
                    Math.max(
                        0,
                        Math.min(
                            100,
                            percentual
                        )
                    );


                mostrarStatusModelo(
                    "Preparando IA: "
                    +
                    Math.round(
                        percentual
                    )
                    +
                    "%"
                );


                return;

            }


            const status =
                String(
                    progresso.status
                    ?? ""
                ).toLowerCase();


            if (
                status ===
                "ready"
            ) {

                mostrarStatusModelo(
                    "IA pronta"
                );


                return;

            }


            mostrarStatusModelo(
                "Preparando IA..."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | GARANTIR MODELO PRONTO
        |--------------------------------------------------------------------------
        */

        async function garantirModeloPronto() {

            if (
                modeloPronto
                &&
                moduloModelo
            ) {

                return moduloModelo;

            }


            if (
                preparandoModelo
            ) {

                await preparandoModelo;


                return moduloModelo;

            }


            preparandoModelo =
                (
                    async () => {

                        mostrarStatusModelo(
                            "Preparando IA..."
                        );


                        const modulo =
                            await carregarModuloModelo();


                        const resultado =
                            await modulo.carregarModelo(
                                atualizarProgressoModelo
                            );


                        if (
                            !resultado
                            ||
                            !resultado.pronto
                        ) {

                            throw new Error(
                                "A inteligência artificial não ficou disponível."
                            );

                        }


                        modeloPronto =
                            true;


                        console.info(
                            "[Assistente Java] Modelo carregado:",
                            resultado
                        );


                        mostrarStatusModelo(
                            resultado.dispositivo ===
                            "wasm"
                                ?
                                "IA pronta • CPU"
                                :
                                "IA pronta"
                        );


                        await new Promise(
                            resolver => {

                                window.setTimeout(
                                    resolver,
                                    400
                                );

                            }
                        );

                    }
                )();


            try {

                await preparandoModelo;

            }
            finally {

                preparandoModelo =
                    null;


                restaurarContador();

            }


            return moduloModelo;

        }


        /*
        |--------------------------------------------------------------------------
        | GERAR RESPOSTA LOCAL
        |--------------------------------------------------------------------------
        */

        async function gerarRespostaLocal(
            mensagem
        ) {

            const modulo =
                await garantirModeloPronto();


            if (
                !modulo
                ||
                typeof modulo.gerarResposta !==
                    "function"
            ) {

                throw new Error(
                    "O Assistente Java ainda não está disponível."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | CÓPIA DO HISTÓRICO
            |--------------------------------------------------------------------------
            |
            | A pergunta atual ainda não está incluída.
            |
            |--------------------------------------------------------------------------
            */

            const historico =
                historicoModelo.map(
                    item => ({

                        role:
                            item.role,

                        content:
                            item.content

                    })
                );


            const resposta =
                await modulo.gerarResposta(
                    {

                        mensagem,

                        historico,

                        paginaAtual:
                            obterPaginaAtual(),

                        contextoPagina:
                            obterContextoPagina()

                    }
                );


            const texto =
                normalizarTexto(
                    resposta
                );


            if (
                texto === ""
            ) {

                throw new Error(
                    "O Assistente Java não retornou nenhuma mensagem."
                );

            }


            return texto;

        }


        /*
        |--------------------------------------------------------------------------
        | SALVAR CONVERSA
        |--------------------------------------------------------------------------
        |
        | O backend recebe somente:
        |
        | - pergunta;
        | - resposta.
        |
        | O contexto da página permanece no navegador.
        |
        |--------------------------------------------------------------------------
        */

        async function salvarConversaBanco(
            mensagem,
            respostaAssistente
        ) {

            const dados =
                await requisicaoJson(
                    endpoint,
                    {

                        method:
                            "POST",

                        headers: {

                            "Content-Type":
                                "application/json",

                            "Accept":
                                "application/json",

                            "X-CSRF-Token":
                                csrfToken

                        },

                        body:
                            JSON.stringify(
                                {

                                    csrf_token:
                                        csrfToken,

                                    mensagem,

                                    resposta_assistente:
                                        respostaAssistente

                                }
                            )

                    }
                );


            if (
                !dados.status
            ) {

                throw new Error(
                    dados.mensagem
                    ||
                    "Não foi possível salvar a conversa."
                );

            }


            return dados;

        }


        /*
        |--------------------------------------------------------------------------
        | NOVA CONVERSA
        |--------------------------------------------------------------------------
        */

        async function executarNovaConversa() {

            if (
                limpandoHistorico
                ||
                enviando
            ) {

                return;

            }


            limpandoHistorico =
                true;


            botaoConfirmarLimpeza.disabled =
                true;


            botaoCancelarLimpeza.disabled =
                true;


            botaoNovaConversa.disabled =
                true;


            const textoOriginal =
                botaoConfirmarLimpeza.textContent;


            botaoConfirmarLimpeza.textContent =
                "Limpando...";


            esconderErro();


            try {

                const dados =
                    await requisicaoJson(
                        limparEndpoint,
                        {

                            method:
                                "POST",

                            headers: {

                                "Content-Type":
                                    "application/json",

                                "Accept":
                                    "application/json",

                                "X-CSRF-Token":
                                    csrfToken

                            },

                            body:
                                JSON.stringify(
                                    {

                                        csrf_token:
                                            csrfToken

                                    }
                                )

                        }
                    );


                if (
                    !dados.status
                ) {

                    throw new Error(
                        dados.mensagem
                        ||
                        "Não foi possível iniciar uma nova conversa."
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | HISTÓRICO LOCAL
                |--------------------------------------------------------------------------
                */

                historicoModelo =
                    [];


                historicoCarregado =
                    true;


                /*
                |--------------------------------------------------------------------------
                | INTERFACE
                |--------------------------------------------------------------------------
                */

                limparMensagensVisuais();


                campo.value =
                    "";


                atualizarContador();


                ajustarTextarea();


                confirmacao.hidden =
                    true;


                campo.focus();

            }
            catch (
                erroLimpeza
            ) {

                console.error(
                    "[Assistente Java] Erro ao limpar conversa:",
                    erroLimpeza
                );


                mostrarErro(
                    erroLimpeza.message
                    ||
                    "Não foi possível iniciar uma nova conversa."
                );

            }
            finally {

                limpandoHistorico =
                    false;


                botaoConfirmarLimpeza.disabled =
                    false;


                botaoCancelarLimpeza.disabled =
                    false;


                botaoNovaConversa.disabled =
                    false;


                botaoConfirmarLimpeza.textContent =
                    textoOriginal;


                campo.disabled =
                    false;


                botaoEnviar.disabled =
                    false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ENVIAR MENSAGEM
        |--------------------------------------------------------------------------
        */

        async function enviarMensagem(
            mensagem
        ) {

            /*
            |--------------------------------------------------------------------------
            | GARANTE HISTÓRICO
            |--------------------------------------------------------------------------
            */

            await carregarHistoricoBanco();


            /*
            |--------------------------------------------------------------------------
            | EXIBE PERGUNTA
            |--------------------------------------------------------------------------
            */

            adicionarMensagemUsuario(
                mensagem
            );


            esconderErro();


            definirCarregando(
                true
            );


            try {

                /*
                |--------------------------------------------------------------------------
                | GERA LOCALMENTE
                |--------------------------------------------------------------------------
                */

                const textoResposta =
                    await gerarRespostaLocal(
                        mensagem
                    );


                /*
                |--------------------------------------------------------------------------
                | EXIBE RESPOSTA
                |--------------------------------------------------------------------------
                */

                adicionarMensagemAssistente(
                    textoResposta
                );


                /*
                |--------------------------------------------------------------------------
                | CONTEXTO LOCAL
                |--------------------------------------------------------------------------
                */

                adicionarHistoricoModelo(
                    "user",
                    mensagem
                );


                adicionarHistoricoModelo(
                    "assistant",
                    textoResposta
                );


                /*
                |--------------------------------------------------------------------------
                | PERSISTÊNCIA
                |--------------------------------------------------------------------------
                |
                | Falha na persistência NÃO apaga a resposta local.
                |
                |--------------------------------------------------------------------------
                */

                try {

                    await salvarConversaBanco(
                        mensagem,
                        textoResposta
                    );

                }
                catch (
                    erroSalvamento
                ) {

                    console.error(
                        "[Assistente Java] Resposta gerada, mas não salva:",
                        erroSalvamento
                    );


                    mostrarErro(
                        erroSalvamento.message
                        ||
                        "A resposta foi gerada, mas não foi possível salvar a conversa."
                    );

                }

            }
            catch (
                erroGeracao
            ) {

                console.error(
                    "[Assistente Java] Erro durante a geração:",
                    erroGeracao
                );


                mostrarErro(
                    erroGeracao.message
                    ||
                    "Não foi possível gerar uma resposta."
                );

            }
            finally {

                restaurarContador();


                definirCarregando(
                    false
                );


                campo.focus();


                rolarParaFinal();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | FORMULÁRIO
        |--------------------------------------------------------------------------
        */

        formulario.addEventListener(
            "submit",
            async evento => {

                evento.preventDefault();


                if (
                    enviando
                    ||
                    limpandoHistorico
                ) {

                    return;

                }


                const mensagem =
                    normalizarTexto(
                        campo.value
                    );


                if (
                    mensagem === ""
                ) {

                    mostrarErro(
                        "Digite uma dúvida antes de enviar."
                    );


                    campo.focus();


                    return;

                }


                if (
                    mensagem.length >
                    LIMITE_MENSAGEM
                ) {

                    mostrarErro(
                        "Sua mensagem está muito longa."
                    );


                    campo.focus();


                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | LIMPA CAMPO
                |--------------------------------------------------------------------------
                */

                campo.value =
                    "";


                atualizarContador();


                ajustarTextarea();


                /*
                |--------------------------------------------------------------------------
                | ENVIA
                |--------------------------------------------------------------------------
                */

                await enviarMensagem(
                    mensagem
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | ENTER ENVIA
        |--------------------------------------------------------------------------
        */

        campo.addEventListener(
            "keydown",
            evento => {

                if (
                    evento.key ===
                        "Enter"
                    &&
                    !evento.shiftKey
                ) {

                    evento.preventDefault();


                    if (
                        !enviando
                        &&
                        !limpandoHistorico
                        &&
                        normalizarTexto(
                            campo.value
                        )
                        !==
                        ""
                    ) {

                        formulario.requestSubmit();

                    }

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | INPUT
        |--------------------------------------------------------------------------
        */

        campo.addEventListener(
            "input",
            () => {

                /*
                |--------------------------------------------------------------------------
                | DEFESA ALÉM DO maxlength DO HTML
                |--------------------------------------------------------------------------
                */

                if (
                    campo.value.length >
                    LIMITE_MENSAGEM
                ) {

                    campo.value =
                        campo.value.slice(
                            0,
                            LIMITE_MENSAGEM
                        );

                }


                atualizarContador();


                ajustarTextarea();


                esconderErro();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | ABRIR
        |--------------------------------------------------------------------------
        */

        botaoAbrir.addEventListener(
            "click",
            alternarChat
        );


        /*
        |--------------------------------------------------------------------------
        | FECHAR
        |--------------------------------------------------------------------------
        */

        botaoFechar.addEventListener(
            "click",
            fecharChat
        );


        /*
        |--------------------------------------------------------------------------
        | NOVA CONVERSA
        |--------------------------------------------------------------------------
        */

        botaoNovaConversa.addEventListener(
            "click",
            abrirConfirmacao
        );


        /*
        |--------------------------------------------------------------------------
        | CANCELAR
        |--------------------------------------------------------------------------
        */

        botaoCancelarLimpeza.addEventListener(
            "click",
            () => {

                fecharConfirmacao();


                campo.focus();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | CONFIRMAR LIMPEZA
        |--------------------------------------------------------------------------
        */

        botaoConfirmarLimpeza.addEventListener(
            "click",
            executarNovaConversa
        );


        /*
        |--------------------------------------------------------------------------
        | ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            "keydown",
            evento => {

                if (
                    evento.key !==
                    "Escape"
                ) {

                    return;

                }


                if (
                    !confirmacao.hidden
                ) {

                    fecharConfirmacao();


                    campo.focus();


                    return;

                }


                if (
                    janela.getAttribute(
                        "aria-hidden"
                    )
                    ===
                    "false"
                ) {

                    fecharChat();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | ESTADO INICIAL
        |--------------------------------------------------------------------------
        */

        atualizarContador();


        ajustarTextarea();


        /*
        |--------------------------------------------------------------------------
        | CARREGAMENTO
        |--------------------------------------------------------------------------
        |
        | NÃO fazemos aqui:
        |
        | carregarHistoricoBanco();
        | garantirModeloPronto();
        |
        | Resultado:
        |
        | entrar em uma página normal da plataforma não causa download
        | do histórico nem dos arquivos do modelo.
        |
        | O histórico será carregado ao abrir o chat.
        |
        | O modelo será carregado somente ao enviar a primeira pergunta.
        |
        |--------------------------------------------------------------------------
        */

    }
);