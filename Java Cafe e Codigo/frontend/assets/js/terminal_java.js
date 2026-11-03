/*
|--------------------------------------------------------------------------
| TERMINAL JAVA INTERATIVO
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Arquitetura:
|
| navegador
|     ↓
| terminal_java.js
|     ↓
| terminal_java_motor.js
|     ↓
| Web Worker
|     ↓
| CheerpJ / JVM
|
| O terminal não possui acesso ao terminal real do sistema operacional.
|
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "DOMContentLoaded",
    () => {

        "use strict";


        /*
        |--------------------------------------------------------------------------
        | ELEMENTOS
        |--------------------------------------------------------------------------
        */

        const codigo =
            document.getElementById(
                "terminalJavaCodigo"
            );


        const linhas =
            document.getElementById(
                "terminalJavaLinhas"
            );


        const saida =
            document.getElementById(
                "terminalJavaSaida"
            );


        const formulario =
            document.getElementById(
                "terminalJavaFormulario"
            );


        const comando =
            document.getElementById(
                "terminalJavaComando"
            );


        const botaoLimpar =
            document.getElementById(
                "terminalJavaLimpar"
            );


        const promptFormulario =
            formulario?.querySelector(
                ".terminal-java-prompt"
            );


        const terminal =
            document.querySelector(
                ".terminal-java-terminal"
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDA ESTRUTURA
        |--------------------------------------------------------------------------
        */

        if (
            !codigo
            ||
            !linhas
            ||
            !saida
            ||
            !formulario
            ||
            !comando
            ||
            !botaoLimpar
            ||
            !promptFormulario
        ) {

            console.error(
                "[Terminal Java] Estrutura HTML incompleta."
            );


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDA MOTOR
        |--------------------------------------------------------------------------
        */

        if (
            typeof window.TerminalJavaMotor !==
            "function"
        ) {

            console.error(
                "[Terminal Java] TerminalJavaMotor não foi carregado."
            );


            const erro =
                document.createElement(
                    "div"
                );


            erro.className =
                "terminal-java-sistema";


            erro.textContent =
                "Não foi possível carregar o ambiente Java.";


            saida.appendChild(
                erro
            );


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIGURAÇÕES
        |--------------------------------------------------------------------------
        */

        const PROMPT =
            "C:\\JavaCafe>";


        const CHAVE_AUTOSAVE =
            "java-cafe-e-codigo:terminal:Main.java";


        const TEMPO_AUTOSAVE =
            300;


        const MAX_HISTORICO =
            50;


        const MAX_CARACTERES_CODIGO =
            100000;


        const MAX_CARACTERES_ENTRADA =
            10000;


        const MAX_CARACTERES_COMANDO =
            200;


        /*
        |--------------------------------------------------------------------------
        | PROTEÇÃO DA SAÍDA
        |--------------------------------------------------------------------------
        |
        | Impede que um programa com saída infinita faça o navegador
        | criar elementos indefinidamente.
        |
        */

        const MAX_LINHAS_SAIDA =
            1000;


        const MAX_CARACTERES_LINHA =
            20000;


        /*
        |--------------------------------------------------------------------------
        | ESTADO
        |--------------------------------------------------------------------------
        */

        let ocupado =
            false;


        let programaExecutando =
            false;


        let ambientePronto =
            false;


        let codigoCompilado =
            null;


        let linhaStreamAtual =
            null;


        let classeStreamAtual =
            "";


        let linhaProcessando =
            null;


        let timerAutosave =
            null;


        let primeiraInicializacao =
            true;


        let reiniciandoDepoisInterrupcao =
            false;


        let saidaLimitada =
            false;


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO
        |--------------------------------------------------------------------------
        */

        const historico =
            [];


        let posicaoHistorico =
            0;


        /*
        |--------------------------------------------------------------------------
        | SCROLL
        |--------------------------------------------------------------------------
        */

        function rolarTerminal() {

            saida.scrollTop =
                saida.scrollHeight;

        }


        /*
        |--------------------------------------------------------------------------
        | LIMITE DA SAÍDA
        |--------------------------------------------------------------------------
        */

        function marcarLimiteSaida() {

            if (
                saidaLimitada
            ) {

                return;

            }


            saidaLimitada =
                true;


            linhaStreamAtual =
                null;


            classeStreamAtual =
                "";


            const aviso =
                document.createElement(
                    "div"
                );


            aviso.className =
                "terminal-java-sistema";


            aviso.textContent =
                "A saída foi limitada para proteger o navegador. Limpe o terminal para continuar exibindo novas mensagens.";


            saida.appendChild(
                aviso
            );


            rolarTerminal();

        }


        /*
        |--------------------------------------------------------------------------
        | VERIFICA SE PODE CRIAR NOVA LINHA
        |--------------------------------------------------------------------------
        */

        function podeCriarLinha() {

            if (
                saidaLimitada
            ) {

                return false;

            }


            if (
                saida.children.length >=
                MAX_LINHAS_SAIDA
            ) {

                marcarLimiteSaida();


                return false;

            }


            return true;

        }


        /*
        |--------------------------------------------------------------------------
        | LINHA NORMAL
        |--------------------------------------------------------------------------
        */

        function adicionarLinha(
            texto = "",
            classe = ""
        ) {

            linhaStreamAtual =
                null;


            classeStreamAtual =
                "";


            if (
                !podeCriarLinha()
            ) {

                return null;

            }


            const elemento =
                document.createElement(
                    "div"
                );


            if (
                classe
            ) {

                elemento.className =
                    classe;

            }


            let conteudo =
                String(
                    texto ?? ""
                );


            if (
                conteudo.length >
                MAX_CARACTERES_LINHA
            ) {

                conteudo =
                    conteudo.slice(
                        0,
                        MAX_CARACTERES_LINHA
                    );


                elemento.textContent =
                    conteudo;


                saida.appendChild(
                    elemento
                );


                marcarLimiteSaida();


                return elemento;

            }


            elemento.textContent =
                conteudo;


            saida.appendChild(
                elemento
            );


            rolarTerminal();


            return elemento;

        }


        /*
        |--------------------------------------------------------------------------
        | FINALIZA STREAM
        |--------------------------------------------------------------------------
        */

        function finalizarLinhaStream() {

            linhaStreamAtual =
                null;


            classeStreamAtual =
                "";

        }


        /*
        |--------------------------------------------------------------------------
        | OBTÉM LINHA PARA STREAM
        |--------------------------------------------------------------------------
        */

        function obterLinhaStream(
            classe = ""
        ) {

            /*
            |--------------------------------------------------------------------------
            | MUDA DE STDOUT PARA STDERR
            |--------------------------------------------------------------------------
            |
            | Se a classe visual mudou, usamos uma nova linha para evitar que
            | texto de erro seja misturado visualmente com stdout.
            |
            */

            if (
                linhaStreamAtual
                &&
                classeStreamAtual !== classe
            ) {

                finalizarLinhaStream();

            }


            if (
                linhaStreamAtual
            ) {

                return linhaStreamAtual;

            }


            if (
                !podeCriarLinha()
            ) {

                return null;

            }


            linhaStreamAtual =
                document.createElement(
                    "div"
                );


            classeStreamAtual =
                classe;


            if (
                classe
            ) {

                linhaStreamAtual.className =
                    classe;

            }


            saida.appendChild(
                linhaStreamAtual
            );


            return linhaStreamAtual;

        }


        /*
        |--------------------------------------------------------------------------
        | STREAM
        |--------------------------------------------------------------------------
        |
        | Mantém System.out.print() sem adicionar quebra artificial.
        |
        */

        function escreverStream(
            texto,
            classe = ""
        ) {

            if (
                saidaLimitada
            ) {

                return;

            }


            const conteudo =
                String(
                    texto ?? ""
                )
                    .replaceAll(
                        "\r\n",
                        "\n"
                    )
                    .replaceAll(
                        "\r",
                        "\n"
                    );


            const partes =
                conteudo.split(
                    "\n"
                );


            for (
                let indice = 0;
                indice < partes.length;
                indice++
            ) {

                const parte =
                    partes[
                        indice
                    ];


                const linha =
                    obterLinhaStream(
                        classe
                    );


                if (
                    !linha
                ) {

                    return;

                }


                const tamanhoAtual =
                    linha.textContent.length;


                const disponivel =
                    MAX_CARACTERES_LINHA
                    -
                    tamanhoAtual;


                if (
                    disponivel <= 0
                ) {

                    marcarLimiteSaida();


                    return;

                }


                if (
                    parte.length >
                    disponivel
                ) {

                    linha.appendChild(
                        document.createTextNode(
                            parte.slice(
                                0,
                                disponivel
                            )
                        )
                    );


                    marcarLimiteSaida();


                    return;

                }


                linha.appendChild(
                    document.createTextNode(
                        parte
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | EXISTE QUEBRA DE LINHA DEPOIS DESTA PARTE
                |--------------------------------------------------------------------------
                */

                if (
                    indice <
                    partes.length - 1
                ) {

                    finalizarLinhaStream();

                }

            }


            rolarTerminal();

        }


        /*
        |--------------------------------------------------------------------------
        | MOSTRA COMANDO
        |--------------------------------------------------------------------------
        */

        function mostrarComando(
            texto
        ) {

            finalizarLinhaStream();


            if (
                !podeCriarLinha()
            ) {

                return;

            }


            const elemento =
                document.createElement(
                    "div"
                );


            const prompt =
                document.createElement(
                    "span"
                );


            prompt.className =
                "terminal-java-prompt";


            prompt.textContent =
                PROMPT;


            elemento.appendChild(
                prompt
            );


            elemento.appendChild(
                document.createTextNode(
                    " " +
                    String(
                        texto ?? ""
                    )
                )
            );


            saida.appendChild(
                elemento
            );


            rolarTerminal();

        }


        /*
        |--------------------------------------------------------------------------
        | ENTRADA DO PROGRAMA
        |--------------------------------------------------------------------------
        */

        function mostrarEntradaPrograma(
            valor
        ) {

            const texto =
                String(
                    valor ?? ""
                );


            const linha =
                obterLinhaStream(
                    ""
                );


            if (
                !linha
            ) {

                return;

            }


            const disponivel =
                MAX_CARACTERES_LINHA
                -
                linha.textContent.length;


            if (
                disponivel <= 0
            ) {

                marcarLimiteSaida();


                return;

            }


            linha.appendChild(
                document.createTextNode(
                    texto.slice(
                        0,
                        disponivel
                    )
                )
            );


            if (
                texto.length >
                disponivel
            ) {

                marcarLimiteSaida();

            }


            finalizarLinhaStream();


            rolarTerminal();

        }


        /*
        |--------------------------------------------------------------------------
        | ESPAÇO
        |--------------------------------------------------------------------------
        */

        function adicionarEspaco() {

            adicionarLinha(
                ""
            );

        }


        /*
        |--------------------------------------------------------------------------
        | LIMPAR TERMINAL
        |--------------------------------------------------------------------------
        */

        function limparTerminal() {

            saida.replaceChildren();


            linhaStreamAtual =
                null;


            classeStreamAtual =
                "";


            linhaProcessando =
                null;


            saidaLimitada =
                false;

        }


        /*
        |--------------------------------------------------------------------------
        | PROCESSANDO
        |--------------------------------------------------------------------------
        */

        function mostrarProcessando(
            texto
        ) {

            removerProcessando();


            linhaProcessando =
                adicionarLinha(
                    texto,
                    "terminal-java-sistema"
                );

        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE PROCESSANDO
        |--------------------------------------------------------------------------
        */

        function removerProcessando() {

            if (
                linhaProcessando
                &&
                linhaProcessando.isConnected
            ) {

                linhaProcessando.remove();

            }


            linhaProcessando =
                null;

        }


        /*
        |--------------------------------------------------------------------------
        | PROMPT NORMAL
        |--------------------------------------------------------------------------
        */

        function restaurarPrompt() {

            promptFormulario.textContent =
                PROMPT;


            comando.placeholder =
                "Digite um comando...";

        }


        /*
        |--------------------------------------------------------------------------
        | PROMPT DO PROGRAMA
        |--------------------------------------------------------------------------
        */

        function ativarEntradaPrograma() {

            promptFormulario.textContent =
                "";


            comando.placeholder =
                "Entrada do programa...";

        }


        /*
        |--------------------------------------------------------------------------
        | CONTROLES
        |--------------------------------------------------------------------------
        */

        function atualizarControles() {

            /*
            |--------------------------------------------------------------------------
            | PROGRAMA EXECUTANDO
            |--------------------------------------------------------------------------
            */

            if (
                programaExecutando
            ) {

                comando.disabled =
                    false;


                botaoLimpar.disabled =
                    false;


                ativarEntradaPrograma();


                comando.focus();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | COMANDO / COMPILAÇÃO
            |--------------------------------------------------------------------------
            */

            comando.disabled =
                ocupado;


            botaoLimpar.disabled =
                false;


            restaurarPrompt();


            if (
                !ocupado
            ) {

                comando.focus();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ERRO VISUAL
        |--------------------------------------------------------------------------
        */

        function mostrarErro(
            mensagem,
            detalhe = ""
        ) {

            removerProcessando();


            ocupado =
                false;


            programaExecutando =
                false;


            codigoCompilado =
                null;


            finalizarLinhaStream();


            adicionarLinha(
                mensagem
                ||
                "Erro no Terminal Java.",
                "terminal-java-sistema"
            );


            if (
                detalhe
            ) {

                adicionarLinha(
                    detalhe,
                    "terminal-java-sistema"
                );

            }


            adicionarEspaco();


            atualizarControles();

        }


        /*
        |--------------------------------------------------------------------------
        | MENSAGENS DO MOTOR
        |--------------------------------------------------------------------------
        */

        function processarMensagemMotor(
            mensagem
        ) {

            if (
                !mensagem
                ||
                typeof mensagem !==
                    "object"
            ) {

                return;

            }


            const tipo =
                String(
                    mensagem.tipo ?? ""
                );


            /*
            |--------------------------------------------------------------------------
            | COMPILAÇÃO INICIADA
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "compilacao_iniciada"
            ) {

                ocupado =
                    true;


                codigoCompilado =
                    null;


                mostrarProcessando(
                    "Compilando Main.java..."
                );


                atualizarControles();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | COMPILAÇÃO FINALIZADA
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "compilacao_finalizada"
            ) {

                removerProcessando();


                ocupado =
                    false;


                if (
                    mensagem.sucesso
                ) {

                    codigoCompilado =
                        codigo.value;


                    adicionarLinha(
                        mensagem.mensagem
                        ||
                        "Main.java compilado com sucesso."
                    );

                }
                else {

                    codigoCompilado =
                        null;


                    adicionarLinha(
                        mensagem.mensagem
                        ||
                        "O código possui erro de compilação.",
                        "terminal-java-sistema"
                    );

                }


                adicionarEspaco();


                atualizarControles();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | COMANDO INICIADO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "comando_iniciado"
            ) {

                ocupado =
                    true;


                mostrarProcessando(
                    "Executando..."
                );


                atualizarControles();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | COMANDO FINALIZADO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "comando_finalizado"
            ) {

                removerProcessando();


                ocupado =
                    false;


                finalizarLinhaStream();


                adicionarEspaco();


                atualizarControles();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | JAVA MAIN INICIADO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "execucao_iniciada"
            ) {

                removerProcessando();


                ocupado =
                    false;


                programaExecutando =
                    true;


                atualizarControles();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | JAVA MAIN FINALIZADO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "execucao_finalizada"
            ) {

                removerProcessando();


                programaExecutando =
                    false;


                ocupado =
                    false;


                finalizarLinhaStream();


                adicionarEspaco();


                atualizarControles();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | STDOUT
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "stdout"
            ) {

                removerProcessando();


                escreverStream(
                    mensagem.dados
                    ?? ""
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | STDERR
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "stderr"
            ) {

                removerProcessando();


                escreverStream(
                    mensagem.dados
                    ?? "",
                    "terminal-java-erro"
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | INTERROMPENDO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "interrompendo"
            ) {

                codigoCompilado =
                    null;


                reiniciandoDepoisInterrupcao =
                    true;


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | ERRO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "erro"
            ) {

                mostrarErro(
                    mensagem.mensagem
                    ||
                    "Erro no Terminal Java.",

                    mensagem.detalhe
                    ||
                    ""
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ESTADO DO MOTOR
        |--------------------------------------------------------------------------
        */

        function processarEstadoMotor(
            estado
        ) {

            if (
                !estado
                ||
                typeof estado !==
                    "object"
            ) {

                return;

            }


            const tipo =
                String(
                    estado.tipo ?? ""
                );


            /*
            |--------------------------------------------------------------------------
            | INICIALIZANDO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "inicializando"
            ) {

                ambientePronto =
                    false;


                mostrarProcessando(
                    estado.mensagem
                    ||
                    "Preparando ambiente Java..."
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | PROGRESSO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "progresso"
            ) {

                mostrarProcessando(
                    estado.mensagem
                    ||
                    "Preparando ambiente Java..."
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | PRONTO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "pronto"
            ) {

                ambientePronto =
                    true;


                removerProcessando();


                if (
                    primeiraInicializacao
                ) {

                    primeiraInicializacao =
                        false;


                    console.info(
                        "[Terminal Java] Ambiente Java pronto:",
                        estado.java
                        ?? "CheerpJ"
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | RETORNO DEPOIS DO CTRL+C
                |--------------------------------------------------------------------------
                */

                if (
                    reiniciandoDepoisInterrupcao
                ) {

                    reiniciandoDepoisInterrupcao =
                        false;


                    programaExecutando =
                        false;


                    ocupado =
                        false;


                    codigoCompilado =
                        null;


                    adicionarLinha(
                        "Ambiente Java reiniciado.",
                        "terminal-java-sistema"
                    );


                    adicionarLinha(
                        "Compile Main.java novamente antes de executar.",
                        "terminal-java-sistema"
                    );


                    adicionarEspaco();

                }


                atualizarControles();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | MOTOR
        |--------------------------------------------------------------------------
        */

        const motor =
            new window.TerminalJavaMotor(
                {

                    onMensagem:
                        processarMensagemMotor,


                    onEstado:
                        processarEstadoMotor,


                    onErro:
                        erro => {

                            console.error(
                                "[Terminal Java]",
                                erro
                            );


                            ambientePronto =
                                false;


                            mostrarErro(
                                erro?.message
                                ||
                                "Não foi possível iniciar o ambiente Java."
                            );

                        }

                }
            );


        /*
        |--------------------------------------------------------------------------
        | AJUDA
        |--------------------------------------------------------------------------
        */

        function mostrarAjuda() {

            adicionarLinha(
                "Comandos disponíveis:"
            );


            adicionarEspaco();


            adicionarLinha(
                "javac Main.java    Compila Main.java."
            );


            adicionarLinha(
                "java Main          Executa o programa compilado."
            );


            adicionarLinha(
                "java -version      Exibe a versão do Java."
            );


            adicionarLinha(
                "clear              Limpa o terminal."
            );


            adicionarLinha(
                "cls                Limpa o terminal."
            );


            adicionarLinha(
                "help               Exibe esta ajuda."
            );


            adicionarEspaco();


            adicionarLinha(
                "Durante java Main, o texto digitado é enviado para System.in.",
                "terminal-java-sistema"
            );


            adicionarLinha(
                "Ctrl+C interrompe o programa Java em execução.",
                "terminal-java-sistema"
            );


            adicionarLinha(
                "Ctrl+L limpa a tela do terminal.",
                "terminal-java-sistema"
            );


            adicionarEspaco();

        }


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO
        |--------------------------------------------------------------------------
        */

        function registrarHistorico(
            texto
        ) {

            historico.push(
                texto
            );


            if (
                historico.length >
                MAX_HISTORICO
            ) {

                historico.shift();

            }


            posicaoHistorico =
                historico.length;

        }


        /*
        |--------------------------------------------------------------------------
        | PROCESSAR COMANDO
        |--------------------------------------------------------------------------
        */

        async function processarComando(
            textoOriginal
        ) {

            const texto =
                String(
                    textoOriginal
                    ?? ""
                ).trim();


            if (
                texto ===
                ""
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | LIMITE
            |--------------------------------------------------------------------------
            */

            if (
                texto.length >
                MAX_CARACTERES_COMANDO
            ) {

                adicionarLinha(
                    "O comando informado é muito grande.",
                    "terminal-java-sistema"
                );


                adicionarEspaco();


                return;

            }


            registrarHistorico(
                texto
            );


            mostrarComando(
                texto
            );


            /*
            |--------------------------------------------------------------------------
            | NORMALIZA
            |--------------------------------------------------------------------------
            */

            const normalizado =
                texto
                    .toLowerCase()
                    .replace(
                        /\s+/g,
                        " "
                    );


            /*
            |--------------------------------------------------------------------------
            | CLEAR
            |--------------------------------------------------------------------------
            */

            if (
                normalizado ===
                    "clear"
                ||
                normalizado ===
                    "cls"
            ) {

                limparTerminal();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | HELP
            |--------------------------------------------------------------------------
            */

            if (
                normalizado ===
                "help"
            ) {

                mostrarAjuda();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | JAVAC MAIN.JAVA
            |--------------------------------------------------------------------------
            */

            if (
                normalizado ===
                "javac main.java"
            ) {

                const codigoAtual =
                    codigo.value;


                if (
                    codigoAtual.trim() ===
                    ""
                ) {

                    adicionarLinha(
                        "Main.java está vazio.",
                        "terminal-java-sistema"
                    );


                    adicionarEspaco();


                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | PROTEÇÃO DE TAMANHO
                |--------------------------------------------------------------------------
                */

                if (
                    codigoAtual.length >
                    MAX_CARACTERES_CODIGO
                ) {

                    adicionarLinha(
                        "Main.java ultrapassou o tamanho permitido pelo laboratório.",
                        "terminal-java-sistema"
                    );


                    adicionarLinha(
                        "Reduza o código antes de compilar.",
                        "terminal-java-sistema"
                    );


                    adicionarEspaco();


                    return;

                }


                ocupado =
                    true;


                atualizarControles();


                mostrarProcessando(
                    ambientePronto
                        ?
                        "Compilando..."
                        :
                        "Preparando ambiente Java..."
                );


                try {

                    await motor.compilar(
                        codigoAtual
                    );

                }
                catch (erro) {

                    mostrarErro(
                        erro?.message
                        ||
                        "Não foi possível compilar Main.java."
                    );

                }


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | JAVA MAIN
            |--------------------------------------------------------------------------
            */

            if (
                normalizado ===
                "java main"
            ) {

                if (
                    codigoCompilado ===
                    null
                ) {

                    adicionarLinha(
                        "Main.java ainda não foi compilado.",
                        "terminal-java-sistema"
                    );


                    adicionarLinha(
                        "Compile com javac Main.java antes de executar.",
                        "terminal-java-sistema"
                    );


                    adicionarEspaco();


                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | CÓDIGO ALTERADO
                |--------------------------------------------------------------------------
                */

                if (
                    codigo.value !==
                    codigoCompilado
                ) {

                    adicionarLinha(
                        "Main.java foi alterado após a última compilação.",
                        "terminal-java-sistema"
                    );


                    adicionarLinha(
                        "Compile novamente com javac Main.java.",
                        "terminal-java-sistema"
                    );


                    adicionarEspaco();


                    return;

                }


                ocupado =
                    true;


                atualizarControles();


                mostrarProcessando(
                    "Iniciando programa..."
                );


                try {

                    await motor.executar();

                }
                catch (erro) {

                    mostrarErro(
                        erro?.message
                        ||
                        "Não foi possível executar Main."
                    );

                }


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | JAVA -VERSION
            |--------------------------------------------------------------------------
            */

            if (
                normalizado ===
                "java -version"
            ) {

                ocupado =
                    true;


                atualizarControles();


                mostrarProcessando(
                    ambientePronto
                        ?
                        "Consultando versão..."
                        :
                        "Preparando ambiente Java..."
                );


                try {

                    await motor.obterVersao();

                }
                catch (erro) {

                    mostrarErro(
                        erro?.message
                        ||
                        "Não foi possível consultar a versão do Java."
                    );

                }


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | COMANDO NÃO AUTORIZADO
            |--------------------------------------------------------------------------
            */

            adicionarLinha(
                `'${texto}' não é um comando permitido neste terminal.`
            );


            adicionarLinha(
                "Digite help para visualizar os comandos disponíveis.",
                "terminal-java-sistema"
            );


            adicionarEspaco();

        }


        /*
        |--------------------------------------------------------------------------
        | FORMULÁRIO
        |--------------------------------------------------------------------------
        */

        formulario.addEventListener(
            "submit",
            evento => {

                evento.preventDefault();


                const valor =
                    comando.value;


                /*
                |--------------------------------------------------------------------------
                | STDIN
                |--------------------------------------------------------------------------
                */

                if (
                    programaExecutando
                ) {

                    if (
                        valor.length >
                        MAX_CARACTERES_ENTRADA
                    ) {

                        adicionarLinha(
                            "A entrada informada é muito grande.",
                            "terminal-java-sistema"
                        );


                        adicionarEspaco();


                        comando.select();


                        return;

                    }


                    mostrarEntradaPrograma(
                        valor
                    );


                    motor
                        .enviarEntrada(
                            valor + "\n"
                        )
                        .catch(
                            erro => {

                                mostrarErro(
                                    erro?.message
                                    ||
                                    "Não foi possível enviar a entrada ao programa."
                                );

                            }
                        );


                    comando.value =
                        "";


                    comando.focus();


                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | OPERAÇÃO EM ANDAMENTO
                |--------------------------------------------------------------------------
                */

                if (
                    ocupado
                ) {

                    return;

                }


                if (
                    valor.trim() ===
                    ""
                ) {

                    comando.focus();


                    return;

                }


                comando.value =
                    "";


                void processarComando(
                    valor
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | LIMPAR
        |--------------------------------------------------------------------------
        */

        botaoLimpar.addEventListener(
            "click",
            () => {

                limparTerminal();


                comando.focus();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO ↑ ↓
        |--------------------------------------------------------------------------
        */

        comando.addEventListener(
            "keydown",
            evento => {

                if (
                    programaExecutando
                ) {

                    return;

                }


                if (
                    evento.key ===
                    "ArrowUp"
                ) {

                    evento.preventDefault();


                    if (
                        historico.length ===
                        0
                    ) {

                        return;

                    }


                    posicaoHistorico =
                        Math.max(
                            0,
                            posicaoHistorico - 1
                        );


                    comando.value =
                        historico[
                            posicaoHistorico
                        ];


                    window.setTimeout(
                        () => {

                            comando.setSelectionRange(
                                comando.value.length,
                                comando.value.length
                            );

                        },
                        0
                    );


                    return;

                }


                if (
                    evento.key ===
                    "ArrowDown"
                ) {

                    evento.preventDefault();


                    if (
                        historico.length ===
                        0
                    ) {

                        return;

                    }


                    posicaoHistorico =
                        Math.min(
                            historico.length,
                            posicaoHistorico + 1
                        );


                    if (
                        posicaoHistorico ===
                        historico.length
                    ) {

                        comando.value =
                            "";


                        return;

                    }


                    comando.value =
                        historico[
                            posicaoHistorico
                        ];


                    window.setTimeout(
                        () => {

                            comando.setSelectionRange(
                                comando.value.length,
                                comando.value.length
                            );

                        },
                        0
                    );

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | CTRL + C
        |--------------------------------------------------------------------------
        */

        comando.addEventListener(
            "keydown",
            evento => {

                if (
                    !evento.ctrlKey
                    ||
                    evento.key.toLowerCase() !==
                        "c"
                ) {

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | HÁ TEXTO SELECIONADO
                |--------------------------------------------------------------------------
                |
                | Mantemos o comportamento normal de copiar.
                |
                */

                if (
                    comando.selectionStart !==
                    comando.selectionEnd
                ) {

                    return;

                }


                if (
                    !programaExecutando
                ) {

                    return;

                }


                evento.preventDefault();


                mostrarEntradaPrograma(
                    "^C"
                );


                codigoCompilado =
                    null;


                motor
                    .interromper()
                    .catch(
                        erro => {

                            mostrarErro(
                                erro?.message
                                ||
                                "Não foi possível interromper o programa."
                            );

                        }
                    );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | CTRL + L
        |--------------------------------------------------------------------------
        */

        comando.addEventListener(
            "keydown",
            evento => {

                if (
                    evento.ctrlKey
                    &&
                    evento.key.toLowerCase() ===
                        "l"
                ) {

                    evento.preventDefault();


                    limparTerminal();


                    comando.focus();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | NUMERAÇÃO DAS LINHAS
        |--------------------------------------------------------------------------
        */

        function atualizarNumeracaoLinhas() {

            const quantidade =
                Math.max(
                    1,
                    codigo.value.split(
                        "\n"
                    ).length
                );


            linhas.replaceChildren();


            const fragmento =
                document.createDocumentFragment();


            for (
                let numero = 1;
                numero <= quantidade;
                numero++
            ) {

                const elemento =
                    document.createElement(
                        "span"
                    );


                elemento.textContent =
                    String(
                        numero
                    );


                fragmento.appendChild(
                    elemento
                );

            }


            linhas.appendChild(
                fragmento
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SALVA LOCALMENTE
        |--------------------------------------------------------------------------
        */

        function salvarCodigoLocal() {

            try {

                if (
                    codigo.value.length >
                    MAX_CARACTERES_CODIGO
                ) {

                    return;

                }


                localStorage.setItem(
                    CHAVE_AUTOSAVE,
                    codigo.value
                );

            }
            catch {

                /*
                |--------------------------------------------------------------------------
                | localStorage pode estar indisponível.
                |--------------------------------------------------------------------------
                */

            }

        }


        /*
        |--------------------------------------------------------------------------
        | RESTAURA CÓDIGO
        |--------------------------------------------------------------------------
        */

        function restaurarCodigoLocal() {

            try {

                const salvo =
                    localStorage.getItem(
                        CHAVE_AUTOSAVE
                    );


                if (
                    salvo === null
                    ||
                    salvo === ""
                ) {

                    return;

                }


                if (
                    salvo.length >
                    MAX_CARACTERES_CODIGO
                ) {

                    localStorage.removeItem(
                        CHAVE_AUTOSAVE
                    );


                    return;

                }


                codigo.value =
                    salvo;

            }
            catch {

                /*
                |--------------------------------------------------------------------------
                | localStorage indisponível.
                |--------------------------------------------------------------------------
                */

            }

        }


        /*
        |--------------------------------------------------------------------------
        | EDITOR ALTERADO
        |--------------------------------------------------------------------------
        */

        codigo.addEventListener(
            "input",
            () => {

                atualizarNumeracaoLinhas();


                /*
                |--------------------------------------------------------------------------
                | Mantemos codigoCompilado.
                |
                | Isso permite identificar posteriormente que o arquivo foi
                | alterado depois da compilação.
                |--------------------------------------------------------------------------
                */


                window.clearTimeout(
                    timerAutosave
                );


                timerAutosave =
                    window.setTimeout(
                        salvarCodigoLocal,
                        TEMPO_AUTOSAVE
                    );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | SCROLL DAS LINHAS
        |--------------------------------------------------------------------------
        */

        codigo.addEventListener(
            "scroll",
            () => {

                linhas.scrollTop =
                    codigo.scrollTop;

            }
        );


        /*
        |--------------------------------------------------------------------------
        | TAB
        |--------------------------------------------------------------------------
        */

        codigo.addEventListener(
            "keydown",
            evento => {

                if (
                    evento.key !==
                    "Tab"
                ) {

                    return;

                }


                evento.preventDefault();


                const inicio =
                    codigo.selectionStart;


                const fim =
                    codigo.selectionEnd;


                const espacos =
                    "    ";


                const novoCodigo =
                    codigo.value.substring(
                        0,
                        inicio
                    )
                    +
                    espacos
                    +
                    codigo.value.substring(
                        fim
                    );


                if (
                    novoCodigo.length >
                    MAX_CARACTERES_CODIGO
                ) {

                    return;

                }


                codigo.value =
                    novoCodigo;


                const novaPosicao =
                    inicio
                    +
                    espacos.length;


                codigo.setSelectionRange(
                    novaPosicao,
                    novaPosicao
                );


                atualizarNumeracaoLinhas();


                salvarCodigoLocal();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | CTRL + S
        |--------------------------------------------------------------------------
        */

        codigo.addEventListener(
            "keydown",
            evento => {

                if (
                    evento.ctrlKey
                    &&
                    evento.key.toLowerCase() ===
                        "s"
                ) {

                    evento.preventDefault();


                    salvarCodigoLocal();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | CLIQUE NO TERMINAL
        |--------------------------------------------------------------------------
        */

        if (
            terminal
        ) {

            terminal.addEventListener(
                "click",
                evento => {

                    if (
                        evento.target.closest(
                            "button"
                        )
                    ) {

                        return;

                    }


                    if (
                        !ocupado
                        ||
                        programaExecutando
                    ) {

                        comando.focus();

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FECHAMENTO DA PÁGINA
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            "beforeunload",
            () => {

                salvarCodigoLocal();


                window.clearTimeout(
                    timerAutosave
                );


                try {

                    motor.encerrar();

                }
                catch {

                    /*
                    |--------------------------------------------------------------------------
                    | A página já está sendo encerrada.
                    |--------------------------------------------------------------------------
                    */

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | INICIALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        restaurarCodigoLocal();


        atualizarNumeracaoLinhas();


        restaurarPrompt();


        atualizarControles();


        /*
        |--------------------------------------------------------------------------
        | PREPARA JAVA EM SEGUNDO PLANO
        |--------------------------------------------------------------------------
        |
        | A JVM começa a carregar enquanto o aluno visualiza ou escreve código.
        |
        */

        motor
            .iniciar()
            .catch(
                erro => {

                    /*
                    |--------------------------------------------------------------------------
                    | O callback onErro do motor também trata a falha visualmente.
                    |--------------------------------------------------------------------------
                    */

                    console.error(
                        "[Terminal Java] Inicialização:",
                        erro
                    );

                }
            );


        comando.focus();

    }
);