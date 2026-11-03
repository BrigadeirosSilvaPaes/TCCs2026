/*
|--------------------------------------------------------------------------
| TERMINAL JAVA - MOTOR
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Camada de comunicação entre:
|
| terminal_java.js
|        ↓
| terminal_java_motor.js
|        ↓
| terminal_java_worker.js
|        ↓
| CheerpJ / JVM
|
| O código Java não executa na thread principal da página.
|
|--------------------------------------------------------------------------
*/

(() => {

    "use strict";


    /*
    |--------------------------------------------------------------------------
    | SCRIPT ATUAL
    |--------------------------------------------------------------------------
    */

    const SCRIPT_ELEMENT =
        document.currentScript;


    const SCRIPT_URL =
        SCRIPT_ELEMENT?.src
        ?? "";


    /*
    |--------------------------------------------------------------------------
    | CONFIGURAÇÕES
    |--------------------------------------------------------------------------
    */

    const TEMPO_INICIALIZACAO =
        120000;


    const MAX_CARACTERES_CODIGO =
        100000;


    const MAX_CARACTERES_ENTRADA =
        10000;


    /*
    |--------------------------------------------------------------------------
    | NORMALIZA ERRO
    |--------------------------------------------------------------------------
    */

    function normalizarErro(
        erro,
        mensagemPadrao =
            "O Terminal Java encontrou um erro."
    ) {

        if (
            erro instanceof Error
        ) {

            return erro;

        }


        return new Error(
            String(
                erro
                ?? mensagemPadrao
            )
            ||
            mensagemPadrao
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ERRO DE CANCELAMENTO
    |--------------------------------------------------------------------------
    */

    function criarErroCancelamento(
        mensagem
    ) {

        const erro =
            new Error(
                mensagem
            );


        erro.name =
            "AbortError";


        return erro;

    }


    /*
    |--------------------------------------------------------------------------
    | URL DO WORKER
    |--------------------------------------------------------------------------
    */

    function obterWorkerUrl() {

        if (
            !SCRIPT_URL
        ) {

            throw new Error(
                "Não foi possível localizar o motor do Terminal Java."
            );

        }


        const scriptUrl =
            new URL(
                SCRIPT_URL,
                window.location.href
            );


        const workerUrl =
            new URL(
                "terminal_java_worker.js",
                scriptUrl
            );


        /*
        |--------------------------------------------------------------------------
        | VERSIONAMENTO
        |--------------------------------------------------------------------------
        |
        | O terminal_java_motor.js já é carregado pelo PHP utilizando
        | ?v=filemtime(...).
        |
        | Reaproveitamos essa versão também no Worker para reduzir o risco de
        | um navegador manter uma versão antiga do terminal_java_worker.js.
        |
        */

        const versao =
            scriptUrl.searchParams.get(
                "v"
            );


        if (
            versao
        ) {

            workerUrl.searchParams.set(
                "v",
                versao
            );

        }


        return workerUrl.href;

    }


    /*
    |--------------------------------------------------------------------------
    | MOTOR
    |--------------------------------------------------------------------------
    */

    class TerminalJavaMotor {


        /*
        |--------------------------------------------------------------------------
        | CONSTRUTOR
        |--------------------------------------------------------------------------
        */

        constructor(
            opcoes = {}
        ) {

            /*
            |--------------------------------------------------------------------------
            | WORKER
            |--------------------------------------------------------------------------
            */

            this.worker =
                null;


            this.geracaoWorker =
                0;


            /*
            |--------------------------------------------------------------------------
            | ESTADO
            |--------------------------------------------------------------------------
            */

            this.iniciado =
                false;


            this.inicializando =
                false;


            this.encerrado =
                false;


            this.pronto =
                false;


            this.programaExecutando =
                false;


            this.compilando =
                false;


            this.codigoCompilado =
                false;


            /*
            |--------------------------------------------------------------------------
            | INICIALIZAÇÃO
            |--------------------------------------------------------------------------
            */

            this.timerInicializacao =
                null;


            this.promessaInicializacao =
                null;


            this.resolverInicializacao =
                null;


            this.rejeitarInicializacao =
                null;


            /*
            |--------------------------------------------------------------------------
            | CALLBACKS
            |--------------------------------------------------------------------------
            */

            this.onMensagem =
                typeof opcoes.onMensagem ===
                    "function"
                    ?
                    opcoes.onMensagem
                    :
                    () => {};


            this.onEstado =
                typeof opcoes.onEstado ===
                    "function"
                    ?
                    opcoes.onEstado
                    :
                    () => {};


            this.onErro =
                typeof opcoes.onErro ===
                    "function"
                    ?
                    opcoes.onErro
                    :
                    () => {};

        }


        /*
        |--------------------------------------------------------------------------
        | INICIAR
        |--------------------------------------------------------------------------
        */

        async iniciar() {

            if (
                this.encerrado
            ) {

                throw new Error(
                    "O Terminal Java já foi encerrado."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | JÁ ESTÁ PRONTO
            |--------------------------------------------------------------------------
            */

            if (
                this.worker
                &&
                this.pronto
            ) {

                return true;

            }


            /*
            |--------------------------------------------------------------------------
            | INICIALIZAÇÃO EM ANDAMENTO
            |--------------------------------------------------------------------------
            */

            if (
                this.promessaInicializacao
            ) {

                return this.promessaInicializacao;

            }


            /*
            |--------------------------------------------------------------------------
            | CRIA PROMESSA
            |--------------------------------------------------------------------------
            */

            const promessa =
                new Promise(
                    (
                        resolver,
                        rejeitar
                    ) => {

                        this.resolverInicializacao =
                            resolver;


                        this.rejeitarInicializacao =
                            rejeitar;

                    }
                );


            this.promessaInicializacao =
                promessa;


            /*
            |--------------------------------------------------------------------------
            | ESTADO
            |--------------------------------------------------------------------------
            */

            this.inicializando =
                true;


            this.iniciado =
                false;


            this.pronto =
                false;


            this.emitirEstado(
                {

                    tipo:
                        "inicializando",

                    mensagem:
                        "Preparando ambiente Java..."

                }
            );


            /*
            |--------------------------------------------------------------------------
            | CRIA WORKER
            |--------------------------------------------------------------------------
            */

            try {

                this.criarWorker();

            }
            catch (erroOriginal) {

                const erro =
                    normalizarErro(
                        erroOriginal,
                        "Não foi possível criar o ambiente Java."
                    );


                this.falharInicializacao(
                    erro,
                    true
                );


                return promessa;

            }


            /*
            |--------------------------------------------------------------------------
            | TIMEOUT
            |--------------------------------------------------------------------------
            */

            this.timerInicializacao =
                window.setTimeout(
                    () => {

                        if (
                            this.pronto
                        ) {

                            return;

                        }


                        const erro =
                            new Error(
                                "O ambiente Java demorou demais para iniciar."
                            );


                        this.falharInicializacao(
                            erro,
                            true
                        );

                    },
                    TEMPO_INICIALIZACAO
                );


            return promessa;

        }


        /*
        |--------------------------------------------------------------------------
        | CRIAR WORKER
        |--------------------------------------------------------------------------
        */

        criarWorker() {

            /*
            |--------------------------------------------------------------------------
            | REMOVE WORKER ANTIGO
            |--------------------------------------------------------------------------
            */

            this.destruirWorkerAtual();


            /*
            |--------------------------------------------------------------------------
            | URL
            |--------------------------------------------------------------------------
            */

            const workerUrl =
                obterWorkerUrl();


            /*
            |--------------------------------------------------------------------------
            | GERAÇÃO
            |--------------------------------------------------------------------------
            |
            | Cada Worker recebe uma geração.
            |
            | Caso alguma mensagem antiga permaneça na fila após um restart,
            | ela será ignorada.
            |
            */

            const geracao =
                ++this.geracaoWorker;


            /*
            |--------------------------------------------------------------------------
            | CRIA WORKER
            |--------------------------------------------------------------------------
            */

            const worker =
                new Worker(
                    workerUrl,
                    {

                        name:
                            "JavaCafeTerminal",

                        type:
                            "classic"

                    }
                );


            this.worker =
                worker;


            /*
            |--------------------------------------------------------------------------
            | MENSAGENS
            |--------------------------------------------------------------------------
            */

            worker.addEventListener(
                "message",
                evento => {

                    if (
                        !this.workerAtual(
                            worker,
                            geracao
                        )
                    ) {

                        return;

                    }


                    this.processarMensagemWorker(
                        evento.data
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | ERRO FATAL
            |--------------------------------------------------------------------------
            */

            worker.addEventListener(
                "error",
                evento => {

                    if (
                        !this.workerAtual(
                            worker,
                            geracao
                        )
                    ) {

                        return;

                    }


                    const erro =
                        new Error(
                            evento.message
                            ||
                            "O ambiente Java encontrou um erro inesperado."
                        );


                    console.error(
                        "[Terminal Java] Worker:",
                        evento
                    );


                    this.tratarFalhaWorker(
                        erro
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | ERRO DE MENSAGEM
            |--------------------------------------------------------------------------
            */

            worker.addEventListener(
                "messageerror",
                evento => {

                    if (
                        !this.workerAtual(
                            worker,
                            geracao
                        )
                    ) {

                        return;

                    }


                    console.error(
                        "[Terminal Java] Mensagem inválida do Worker:",
                        evento
                    );


                    this.tratarFalhaWorker(
                        new Error(
                            "O ambiente Java retornou uma mensagem inválida."
                        )
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | INICIALIZA
            |--------------------------------------------------------------------------
            */

            try {

                worker.postMessage(
                    {

                        tipo:
                            "inicializar"

                    }
                );

            }
            catch (erro) {

                this.destruirWorkerAtual();


                throw normalizarErro(
                    erro,
                    "Não foi possível inicializar o ambiente Java."
                );

            }


            this.iniciado =
                true;

        }


        /*
        |--------------------------------------------------------------------------
        | WORKER ATUAL
        |--------------------------------------------------------------------------
        */

        workerAtual(
            worker,
            geracao
        ) {

            return (
                this.worker ===
                    worker
                &&
                this.geracaoWorker ===
                    geracao
                &&
                !this.encerrado
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DESTRUIR WORKER
        |--------------------------------------------------------------------------
        */

        destruirWorkerAtual() {

            const worker =
                this.worker;


            this.worker =
                null;


            /*
            |--------------------------------------------------------------------------
            | INVALIDA EVENTOS PENDENTES
            |--------------------------------------------------------------------------
            */

            this.geracaoWorker++;


            if (
                !worker
            ) {

                return;

            }


            try {

                worker.terminate();

            }
            catch {

                /*
                |--------------------------------------------------------------------------
                | Worker já pode ter sido finalizado.
                |--------------------------------------------------------------------------
                */

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ENVIAR PARA WORKER
        |--------------------------------------------------------------------------
        */

        enviarParaWorker(
            mensagem
        ) {

            if (
                this.encerrado
            ) {

                throw new Error(
                    "O Terminal Java foi encerrado."
                );

            }


            if (
                !this.worker
            ) {

                throw new Error(
                    "O ambiente Java não está disponível."
                );

            }


            try {

                this.worker.postMessage(
                    mensagem
                );

            }
            catch (erro) {

                throw normalizarErro(
                    erro,
                    "Não foi possível enviar dados ao ambiente Java."
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | PROCESSAR MENSAGEM DO WORKER
        |--------------------------------------------------------------------------
        */

        processarMensagemWorker(
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
                    mensagem.tipo
                    ?? ""
                )
                    .trim()
                    .toLowerCase();


            if (
                tipo ===
                ""
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | RUNTIME PRONTO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "runtime_pronto"
            ) {

                if (
                    this.encerrado
                ) {

                    return;

                }


                this.pronto =
                    true;


                this.iniciado =
                    true;


                this.inicializando =
                    false;


                this.limparTimerInicializacao();


                this.emitirEstado(
                    {

                        tipo:
                            "pronto",

                        mensagem:
                            mensagem.mensagem
                            ||
                            "Ambiente Java pronto.",

                        java:
                            mensagem.java
                            ?? null

                    }
                );


                if (
                    this.resolverInicializacao
                ) {

                    this.resolverInicializacao(
                        true
                    );

                }


                this.limparPromessaInicializacao();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | PROGRESSO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "runtime_progresso"
            ) {

                if (
                    !this.inicializando
                ) {

                    return;

                }


                this.emitirEstado(
                    {

                        tipo:
                            "progresso",

                        mensagem:
                            mensagem.mensagem
                            ||
                            "Preparando ambiente Java...",

                        progresso:
                            mensagem.progresso
                            ?? null

                    }
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | COMPILAÇÃO INICIADA
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "compilacao_iniciada"
            ) {

                this.compilando =
                    true;


                this.codigoCompilado =
                    false;

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

                this.compilando =
                    false;


                this.codigoCompilado =
                    Boolean(
                        mensagem.sucesso
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | EXECUÇÃO INICIADA
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "execucao_iniciada"
            ) {

                this.programaExecutando =
                    true;

            }


            /*
            |--------------------------------------------------------------------------
            | EXECUÇÃO FINALIZADA
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "execucao_finalizada"
            ) {

                this.programaExecutando =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | ERRO DO RUNTIME
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "runtime_erro"
            ) {

                const erro =
                    new Error(
                        mensagem.mensagem
                        ||
                        "O ambiente Java encontrou um erro fatal."
                    );


                /*
                |--------------------------------------------------------------------------
                | AINDA ESTAVA INICIALIZANDO
                |--------------------------------------------------------------------------
                */

                if (
                    this.inicializando
                    ||
                    !this.pronto
                ) {

                    this.falharInicializacao(
                        erro,
                        true
                    );


                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | RUNTIME JÁ ESTAVA ATIVO
                |--------------------------------------------------------------------------
                */

                this.invalidarAmbiente(
                    erro,
                    true
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | ERRO DE OPERAÇÃO
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "erro"
            ) {

                this.compilando =
                    false;


                this.programaExecutando =
                    false;


                this.codigoCompilado =
                    false;

            }


            /*
            |--------------------------------------------------------------------------
            | ENCAMINHA À INTERFACE
            |--------------------------------------------------------------------------
            */

            this.emitirMensagem(
                mensagem
            );

        }


        /*
        |--------------------------------------------------------------------------
        | COMPILAR
        |--------------------------------------------------------------------------
        */

        async compilar(
            codigo
        ) {

            await this.garantirPronto();


            if (
                this.programaExecutando
            ) {

                throw new Error(
                    "Existe um programa Java em execução."
                );

            }


            if (
                this.compilando
            ) {

                throw new Error(
                    "Uma compilação já está em andamento."
                );

            }


            const fonte =
                String(
                    codigo
                    ?? ""
                );


            if (
                fonte.trim() ===
                ""
            ) {

                throw new Error(
                    "Main.java está vazio."
                );

            }


            if (
                fonte.length >
                MAX_CARACTERES_CODIGO
            ) {

                throw new Error(
                    "Main.java ultrapassou o tamanho permitido pelo laboratório."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | ESTADO ANTES DO POSTMESSAGE
            |--------------------------------------------------------------------------
            |
            | Evita que duas chamadas de compilação sejam iniciadas antes que
            | o Worker tenha tempo de responder "compilacao_iniciada".
            |
            */

            this.compilando =
                true;


            this.codigoCompilado =
                false;


            try {

                this.enviarParaWorker(
                    {

                        tipo:
                            "compilar",

                        codigo:
                            fonte

                    }
                );

            }
            catch (erro) {

                this.compilando =
                    false;


                throw erro;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | EXECUTAR
        |--------------------------------------------------------------------------
        */

        async executar() {

            await this.garantirPronto();


            if (
                this.compilando
            ) {

                throw new Error(
                    "Aguarde a compilação terminar."
                );

            }


            if (
                this.programaExecutando
            ) {

                throw new Error(
                    "Já existe um programa Java em execução."
                );

            }


            if (
                !this.codigoCompilado
            ) {

                throw new Error(
                    "Compile Main.java antes de executar."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | BLOQUEIA EXECUÇÃO DUPLA
            |--------------------------------------------------------------------------
            */

            this.programaExecutando =
                true;


            try {

                this.enviarParaWorker(
                    {

                        tipo:
                            "executar",

                        classe:
                            "Main"

                    }
                );

            }
            catch (erro) {

                this.programaExecutando =
                    false;


                throw erro;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | STDIN
        |--------------------------------------------------------------------------
        */

        async enviarEntrada(
            dados
        ) {

            await this.garantirPronto();


            if (
                !this.programaExecutando
            ) {

                throw new Error(
                    "Nenhum programa está aguardando entrada."
                );

            }


            const entrada =
                String(
                    dados
                    ?? ""
                );


            if (
                entrada.length >
                MAX_CARACTERES_ENTRADA
            ) {

                throw new Error(
                    "A entrada enviada ao programa é muito grande."
                );

            }


            this.enviarParaWorker(
                {

                    tipo:
                        "stdin",

                    dados:
                        entrada

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | JAVA -VERSION
        |--------------------------------------------------------------------------
        */

        async obterVersao() {

            await this.garantirPronto();


            if (
                this.compilando
            ) {

                throw new Error(
                    "Aguarde a compilação terminar."
                );

            }


            if (
                this.programaExecutando
            ) {

                throw new Error(
                    "Aguarde o programa Java terminar."
                );

            }


            this.enviarParaWorker(
                {

                    tipo:
                        "java_version"

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | INTERROMPER
        |--------------------------------------------------------------------------
        |
        | Como a JVM está dentro de um Web Worker, um programa como:
        |
        | while (true) {
        | }
        |
        | pode ser interrompido destruindo completamente o Worker.
        |
        |--------------------------------------------------------------------------
        */

        async interromper() {

            if (
                this.encerrado
                ||
                !this.worker
            ) {

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | NÃO HÁ PROGRAMA
            |--------------------------------------------------------------------------
            */

            if (
                !this.programaExecutando
            ) {

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | AVISA INTERFACE
            |--------------------------------------------------------------------------
            */

            this.emitirMensagem(
                {

                    tipo:
                        "interrompendo",

                    motivo:
                        "usuario"

                }
            );


            /*
            |--------------------------------------------------------------------------
            | DESTRÓI JVM
            |--------------------------------------------------------------------------
            */

            this.destruirWorkerAtual();


            this.pronto =
                false;


            this.iniciado =
                false;


            this.inicializando =
                false;


            this.compilando =
                false;


            this.programaExecutando =
                false;


            this.codigoCompilado =
                false;


            this.limparTimerInicializacao();


            /*
            |--------------------------------------------------------------------------
            | FINALIZA VISUALMENTE
            |--------------------------------------------------------------------------
            */

            this.emitirMensagem(
                {

                    tipo:
                        "execucao_finalizada",

                    codigo:
                        null,

                    sinal:
                        "SIGINT",

                    interrompido:
                        true

                }
            );


            /*
            |--------------------------------------------------------------------------
            | REINICIA O AMBIENTE
            |--------------------------------------------------------------------------
            |
            | Os arquivos compilados pertenciam à JVM destruída.
            |
            | Portanto Main.java precisará ser compilado novamente.
            |
            */

            await this.reiniciar();


            return true;

        }


        /*
        |--------------------------------------------------------------------------
        | REINICIAR
        |--------------------------------------------------------------------------
        */

        async reiniciar() {

            if (
                this.encerrado
            ) {

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | CANCELA INICIALIZAÇÃO ANTERIOR
            |--------------------------------------------------------------------------
            |
            | Isso evita deixar Promises penduradas para sempre.
            |
            */

            if (
                this.promessaInicializacao
            ) {

                this.rejeitarPromessaInicializacao(
                    criarErroCancelamento(
                        "A inicialização anterior do ambiente Java foi reiniciada."
                    )
                );

            }


            this.limparTimerInicializacao();


            this.destruirWorkerAtual();


            this.pronto =
                false;


            this.iniciado =
                false;


            this.inicializando =
                false;


            this.compilando =
                false;


            this.programaExecutando =
                false;


            this.codigoCompilado =
                false;


            return this.iniciar();

        }


        /*
        |--------------------------------------------------------------------------
        | GARANTIR PRONTO
        |--------------------------------------------------------------------------
        */

        async garantirPronto() {

            if (
                this.encerrado
            ) {

                throw new Error(
                    "O Terminal Java foi encerrado."
                );

            }


            if (
                this.pronto
                &&
                this.worker
            ) {

                return true;

            }


            await this.iniciar();


            if (
                !this.pronto
                ||
                !this.worker
            ) {

                throw new Error(
                    "O ambiente Java ainda não está disponível."
                );

            }


            return true;

        }


        /*
        |--------------------------------------------------------------------------
        | FALHA DO WORKER
        |--------------------------------------------------------------------------
        */

        tratarFalhaWorker(
            erroOriginal
        ) {

            const erro =
                normalizarErro(
                    erroOriginal,
                    "O ambiente Java encontrou um erro inesperado."
                );


            /*
            |--------------------------------------------------------------------------
            | DURANTE INICIALIZAÇÃO
            |--------------------------------------------------------------------------
            */

            if (
                this.inicializando
                ||
                !this.pronto
            ) {

                this.falharInicializacao(
                    erro,
                    true
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | DEPOIS DE INICIALIZADO
            |--------------------------------------------------------------------------
            */

            this.invalidarAmbiente(
                erro,
                true
            );

        }


        /*
        |--------------------------------------------------------------------------
        | INVALIDAR AMBIENTE
        |--------------------------------------------------------------------------
        */

        invalidarAmbiente(
            erroOriginal,
            notificar = true
        ) {

            const erro =
                normalizarErro(
                    erroOriginal,
                    "O ambiente Java foi interrompido."
                );


            this.limparTimerInicializacao();


            this.destruirWorkerAtual();


            this.pronto =
                false;


            this.iniciado =
                false;


            this.inicializando =
                false;


            this.compilando =
                false;


            this.programaExecutando =
                false;


            this.codigoCompilado =
                false;


            if (
                this.promessaInicializacao
            ) {

                this.rejeitarPromessaInicializacao(
                    erro
                );

            }


            if (
                notificar
            ) {

                this.emitirErro(
                    erro
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | FALHAR INICIALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        falharInicializacao(
            erroOriginal,
            notificar = true
        ) {

            const erro =
                normalizarErro(
                    erroOriginal,
                    "Não foi possível iniciar o ambiente Java."
                );


            this.limparTimerInicializacao();


            this.destruirWorkerAtual();


            this.pronto =
                false;


            this.iniciado =
                false;


            this.inicializando =
                false;


            this.compilando =
                false;


            this.programaExecutando =
                false;


            this.codigoCompilado =
                false;


            if (
                notificar
            ) {

                this.emitirErro(
                    erro
                );

            }


            this.rejeitarPromessaInicializacao(
                erro
            );

        }


        /*
        |--------------------------------------------------------------------------
        | LIMPA TIMER
        |--------------------------------------------------------------------------
        */

        limparTimerInicializacao() {

            if (
                this.timerInicializacao !==
                null
            ) {

                window.clearTimeout(
                    this.timerInicializacao
                );

            }


            this.timerInicializacao =
                null;

        }


        /*
        |--------------------------------------------------------------------------
        | REJEITA PROMESSA
        |--------------------------------------------------------------------------
        */

        rejeitarPromessaInicializacao(
            erro
        ) {

            const rejeitar =
                this.rejeitarInicializacao;


            this.limparPromessaInicializacao();


            if (
                rejeitar
            ) {

                rejeitar(
                    erro
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | LIMPAR PROMESSA
        |--------------------------------------------------------------------------
        */

        limparPromessaInicializacao() {

            this.promessaInicializacao =
                null;


            this.resolverInicializacao =
                null;


            this.rejeitarInicializacao =
                null;

        }


        /*
        |--------------------------------------------------------------------------
        | EMITIR MENSAGEM
        |--------------------------------------------------------------------------
        */

        emitirMensagem(
            mensagem
        ) {

            try {

                this.onMensagem(
                    mensagem
                );

            }
            catch (erro) {

                console.error(
                    "[Terminal Java] Erro no callback de mensagem:",
                    erro
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | EMITIR ESTADO
        |--------------------------------------------------------------------------
        */

        emitirEstado(
            estado
        ) {

            try {

                this.onEstado(
                    estado
                );

            }
            catch (erro) {

                console.error(
                    "[Terminal Java] Erro no callback de estado:",
                    erro
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | EMITIR ERRO
        |--------------------------------------------------------------------------
        */

        emitirErro(
            erroOriginal
        ) {

            const erro =
                normalizarErro(
                    erroOriginal
                );


            try {

                this.onErro(
                    erro
                );

            }
            catch (erroCallback) {

                console.error(
                    "[Terminal Java] Erro no callback de erro:",
                    erroCallback
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        obterEstado() {

            return {

                iniciado:
                    this.iniciado,

                inicializando:
                    this.inicializando,

                pronto:
                    this.pronto,

                compilando:
                    this.compilando,

                programaExecutando:
                    this.programaExecutando,

                codigoCompilado:
                    this.codigoCompilado,

                encerrado:
                    this.encerrado

            };

        }


        /*
        |--------------------------------------------------------------------------
        | ENCERRAR
        |--------------------------------------------------------------------------
        */

        encerrar() {

            if (
                this.encerrado
            ) {

                return;

            }


            this.encerrado =
                true;


            this.limparTimerInicializacao();


            /*
            |--------------------------------------------------------------------------
            | NÃO DEIXA PROMISE PENDURADA
            |--------------------------------------------------------------------------
            */

            if (
                this.promessaInicializacao
            ) {

                this.rejeitarPromessaInicializacao(
                    criarErroCancelamento(
                        "O Terminal Java foi encerrado."
                    )
                );

            }


            /*
            |--------------------------------------------------------------------------
            | FINALIZA WORKER
            |--------------------------------------------------------------------------
            */

            if (
                this.worker
            ) {

                try {

                    this.worker.postMessage(
                        {

                            tipo:
                                "encerrar"

                        }
                    );

                }
                catch {

                    /*
                    |--------------------------------------------------------------------------
                    | Worker pode já ter sido interrompido.
                    |--------------------------------------------------------------------------
                    */

                }

            }


            this.destruirWorkerAtual();


            /*
            |--------------------------------------------------------------------------
            | ESTADO FINAL
            |--------------------------------------------------------------------------
            */

            this.pronto =
                false;


            this.iniciado =
                false;


            this.inicializando =
                false;


            this.compilando =
                false;


            this.programaExecutando =
                false;


            this.codigoCompilado =
                false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | DISPONIBILIZA PARA terminal_java.js
    |--------------------------------------------------------------------------
    */

    window.TerminalJavaMotor =
        TerminalJavaMotor;

})();