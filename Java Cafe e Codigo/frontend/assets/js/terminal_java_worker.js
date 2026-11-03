/*
|--------------------------------------------------------------------------
| TERMINAL JAVA - WORKER
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Executa Java dentro do navegador através do CheerpJ.
|
| Responsável por:
|
| - inicializar a JVM;
| - carregar tools.jar;
| - compilar Main.java;
| - executar Main;
| - capturar System.out;
| - capturar System.err;
| - fornecer System.in;
| - permitir Scanner interativo;
| - informar resultados ao terminal_java_motor.js.
|
| Não utiliza:
|
| - Node.js;
| - WebSocket;
| - terminal real do servidor;
| - JDK instalado no servidor.
|
|--------------------------------------------------------------------------
*/

"use strict";


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

const CHEERPJ_URL =
    "https://cjrtnc.leaningtech.com/4.3/loader.js";


/*
|--------------------------------------------------------------------------
| LIMITES
|--------------------------------------------------------------------------
*/

const LIMITE_CODIGO =
    100000;


const LIMITE_ENTRADA =
    10000;


const LIMITE_FILA_ENTRADA =
    100000;


const LIMITE_SAIDA_BYTES =
    1000000;


const LIMITE_DIAGNOSTICO =
    200000;


/*
|--------------------------------------------------------------------------
| ESTADO
|--------------------------------------------------------------------------
*/

let runtimePronto =
    false;


let inicializando =
    false;


let compilando =
    false;


let programaExecutando =
    false;


let comandoExecutando =
    false;


let codigoCompilado =
    false;


let classPath =
    "";


let resultadoCompilacao =
    null;


/*
|--------------------------------------------------------------------------
| CONTROLE DE SAÍDA
|--------------------------------------------------------------------------
*/

let bytesSaida =
    0;


let avisoLimiteSaidaEnviado =
    false;


/*
|--------------------------------------------------------------------------
| STDIN
|--------------------------------------------------------------------------
*/

const filaEntrada =
    [];


/*
|--------------------------------------------------------------------------
| LEITURAS PENDENTES
|--------------------------------------------------------------------------
*/

const leiturasPendentes =
    [];


/*
|--------------------------------------------------------------------------
| ENCODERS
|--------------------------------------------------------------------------
*/

const encoderUtf8 =
    new TextEncoder();


const decoderUtf8 =
    new TextDecoder(
        "UTF-8"
    );


/*
|--------------------------------------------------------------------------
| FONTE - TERMINAL NATIVE
|--------------------------------------------------------------------------
*/

const FONTE_NATIVE = `
package javacafe.runtime;

public final class TerminalNative {

    private TerminalNative() {
    }


    public static native void writeText(
        int canal,
        String texto
    );


    public static native int readByte();


    public static native int availableBytes();


    public static native void reportCompile(
        int codigo,
        String mensagem
    );

}
`.trim();


/*
|--------------------------------------------------------------------------
| FONTE - OUTPUT STREAM
|--------------------------------------------------------------------------
*/

const FONTE_OUTPUT_STREAM = `
package javacafe.runtime;

import java.io.IOException;
import java.io.OutputStream;
import java.nio.charset.StandardCharsets;

public final class JavaCafeOutputStream
    extends OutputStream {

    private final int canal;


    public JavaCafeOutputStream(
        int canal
    ) {

        this.canal =
            canal;

    }


    @Override
    public void write(
        int valor
    ) throws IOException {

        byte[] dados = {
            (byte) valor
        };


        TerminalNative.writeText(
            canal,
            new String(
                dados,
                StandardCharsets.UTF_8
            )
        );

    }


    @Override
    public void write(
        byte[] dados,
        int inicio,
        int tamanho
    ) throws IOException {

        if (
            dados == null
        ) {

            throw new NullPointerException(
                "dados"
            );

        }


        if (
            inicio < 0
            ||
            tamanho < 0
            ||
            inicio > dados.length - tamanho
        ) {

            throw new IndexOutOfBoundsException();

        }


        if (
            tamanho == 0
        ) {

            return;

        }


        String texto =
            new String(
                dados,
                inicio,
                tamanho,
                StandardCharsets.UTF_8
            );


        TerminalNative.writeText(
            canal,
            texto
        );

    }

}
`.trim();


/*
|--------------------------------------------------------------------------
| FONTE - INPUT STREAM
|--------------------------------------------------------------------------
|
| Scanner pode utilizar:
|
| read()
|
| e:
|
| read(byte[], int, int)
|
| O primeiro byte pode aguardar entrada.
| Os demais consomem apenas o que já está disponível.
|
|--------------------------------------------------------------------------
*/

const FONTE_INPUT_STREAM = `
package javacafe.runtime;

import java.io.IOException;
import java.io.InputStream;

public final class JavaCafeInputStream
    extends InputStream {

    @Override
    public int read()
        throws IOException {

        return TerminalNative.readByte();

    }


    @Override
    public int available()
        throws IOException {

        return TerminalNative.availableBytes();

    }


    @Override
    public int read(
        byte[] buffer,
        int inicio,
        int tamanho
    ) throws IOException {

        if (
            buffer == null
        ) {

            throw new NullPointerException(
                "buffer"
            );

        }


        if (
            inicio < 0
            ||
            tamanho < 0
            ||
            inicio > buffer.length - tamanho
        ) {

            throw new IndexOutOfBoundsException();

        }


        if (
            tamanho == 0
        ) {

            return 0;

        }


        /*
        |--------------------------------------------------------------------------
        | PRIMEIRO BYTE
        |--------------------------------------------------------------------------
        */

        int primeiro =
            read();


        if (
            primeiro < 0
        ) {

            return -1;

        }


        buffer[inicio] =
            (byte) primeiro;


        int quantidade =
            1;


        /*
        |--------------------------------------------------------------------------
        | BYTES JÁ DISPONÍVEIS
        |--------------------------------------------------------------------------
        */

        while (
            quantidade < tamanho
            &&
            available() > 0
        ) {

            int valor =
                read();


            if (
                valor < 0
            ) {

                break;

            }


            buffer[
                inicio + quantidade
            ] =
                (byte) valor;


            quantidade++;

        }


        return quantidade;

    }

}
`.trim();


/*
|--------------------------------------------------------------------------
| FONTE - RUNNER
|--------------------------------------------------------------------------
*/

const FONTE_RUNNER = `
package javacafe.runtime;

import java.io.PrintStream;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

public final class TerminalRunner {

    private TerminalRunner() {
    }


    public static void main(
        String[] args
    ) {

        try {

            /*
            |--------------------------------------------------------------------------
            | STDIN
            |--------------------------------------------------------------------------
            */

            System.setIn(
                new JavaCafeInputStream()
            );


            /*
            |--------------------------------------------------------------------------
            | STDOUT
            |--------------------------------------------------------------------------
            */

            System.setOut(
                new PrintStream(
                    new JavaCafeOutputStream(1),
                    true,
                    "UTF-8"
                )
            );


            /*
            |--------------------------------------------------------------------------
            | STDERR
            |--------------------------------------------------------------------------
            */

            System.setErr(
                new PrintStream(
                    new JavaCafeOutputStream(2),
                    true,
                    "UTF-8"
                )
            );


            /*
            |--------------------------------------------------------------------------
            | CLASSE
            |--------------------------------------------------------------------------
            */

            String nomeClasse =
                "Main";


            if (
                args != null
                &&
                args.length > 0
                &&
                args[0] != null
                &&
                !args[0].trim().isEmpty()
            ) {

                nomeClasse =
                    args[0].trim();

            }


            /*
            |--------------------------------------------------------------------------
            | CARREGA MAIN
            |--------------------------------------------------------------------------
            */

            Class<?> classe =
                Class.forName(
                    nomeClasse
                );


            Method metodoMain =
                classe.getMethod(
                    "main",
                    String[].class
                );


            /*
            |--------------------------------------------------------------------------
            | VALIDA MAIN
            |--------------------------------------------------------------------------
            */

            int modificadores =
                metodoMain.getModifiers();


            if (
                !java.lang.reflect.Modifier.isStatic(
                    modificadores
                )
                ||
                metodoMain.getReturnType()
                    != void.class
            ) {

                throw new NoSuchMethodException(
                    "O método main deve ser public static void."
                );

            }


            String[] argumentos =
                new String[0];


            /*
            |--------------------------------------------------------------------------
            | EXECUTA
            |--------------------------------------------------------------------------
            */

            try {

                metodoMain.invoke(
                    null,
                    (Object) argumentos
                );

            }
            catch (
                InvocationTargetException erro
            ) {

                Throwable causa =
                    erro.getCause();


                if (
                    causa != null
                ) {

                    causa.printStackTrace(
                        System.err
                    );

                }
                else {

                    erro.printStackTrace(
                        System.err
                    );

                }

            }

        }
        catch (
            Throwable erro
        ) {

            erro.printStackTrace(
                System.err
            );

        }

    }

}
`.trim();


/*
|--------------------------------------------------------------------------
| FONTE - COMPILADOR
|--------------------------------------------------------------------------
|
| Os componentes internos permanecem em:
|
| /files/javacafe/runtime/
|
| O código do aluno passa a ser compilado em:
|
| /files/user/
|
| Isso evita que Main.class e outras classes do estudante se misturem
| fisicamente com as classes internas do terminal.
|
|--------------------------------------------------------------------------
*/

const FONTE_COMPILER = `
package javacafe.runtime;

import java.io.File;
import java.io.PrintWriter;
import java.io.StringWriter;

public final class TerminalCompiler {

    private static final File DIRETORIO_USUARIO =
        new File(
            "/files/user"
        );


    private TerminalCompiler() {
    }


    /*
    |--------------------------------------------------------------------------
    | GARANTE DIRETÓRIO
    |--------------------------------------------------------------------------
    */

    private static void garantirDiretorioUsuario()
        throws Exception {

        if (
            DIRETORIO_USUARIO.exists()
        ) {

            if (
                !DIRETORIO_USUARIO.isDirectory()
            ) {

                throw new Exception(
                    "O diretório de compilação do usuário é inválido."
                );

            }


            return;

        }


        if (
            !DIRETORIO_USUARIO.mkdirs()
            &&
            !DIRETORIO_USUARIO.isDirectory()
        ) {

            throw new Exception(
                "Não foi possível criar o diretório de compilação."
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE CONTEÚDO RECURSIVAMENTE
    |--------------------------------------------------------------------------
    */

    private static void limparDiretorio(
        File diretorio
    ) {

        File[] arquivos =
            diretorio.listFiles();


        if (
            arquivos == null
        ) {

            return;

        }


        for (
            File arquivo : arquivos
        ) {

            if (
                arquivo.isDirectory()
            ) {

                limparDiretorio(
                    arquivo
                );

            }


            try {

                arquivo.delete();

            }
            catch (
                Throwable ignorado
            ) {

                /*
                |--------------------------------------------------------------------------
                | Ignora falha individual.
                |--------------------------------------------------------------------------
                */

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LIMPA CLASSES LEGADAS DA RAIZ
    |--------------------------------------------------------------------------
    |
    | Versões anteriores do laboratório compilavam diretamente em:
    |
    | /files/
    |
    | Removemos .class deixados na raiz para que eles não sejam utilizados
    | acidentalmente no lugar da nova compilação.
    |
    |--------------------------------------------------------------------------
    */

    private static void limparClassesLegadas() {

        File diretorio =
            new File(
                "/files"
            );


        File[] arquivos =
            diretorio.listFiles();


        if (
            arquivos == null
        ) {

            return;

        }


        for (
            File arquivo : arquivos
        ) {

            if (
                arquivo.isFile()
                &&
                arquivo.getName()
                    .endsWith(
                        ".class"
                    )
            ) {

                try {

                    arquivo.delete();

                }
                catch (
                    Throwable ignorado
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Ignora.
                    |--------------------------------------------------------------------------
                    */

                }

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | MAIN DO COMPILADOR
    |--------------------------------------------------------------------------
    */

    public static void main(
        String[] args
    ) {

        if (
            args == null
            ||
            args.length == 0
        ) {

            TerminalNative.reportCompile(
                1,
                "Main.java não foi informado."
            );


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | BUFFER DO JAVAC
        |--------------------------------------------------------------------------
        */

        StringWriter buffer =
            new StringWriter();


        PrintWriter escritor =
            new PrintWriter(
                buffer
            );


        int codigo =
            1;


        try {

            /*
            |--------------------------------------------------------------------------
            | PREPARA DIRETÓRIO
            |--------------------------------------------------------------------------
            */

            garantirDiretorioUsuario();


            limparDiretorio(
                DIRETORIO_USUARIO
            );


            limparClassesLegadas();


            /*
            |--------------------------------------------------------------------------
            | FONTE
            |--------------------------------------------------------------------------
            */

            String arquivoFonte =
                args[0];


            /*
            |--------------------------------------------------------------------------
            | JAVAC
            |--------------------------------------------------------------------------
            */

            String[] argumentosJavac = {

                "-encoding",
                "UTF-8",

                "-classpath",
                "/files/:/files/user/",

                "-d",
                "/files/user/",

                arquivoFonte

            };


            codigo =
                com.sun.tools.javac.Main.compile(
                    argumentosJavac,
                    escritor
                );

        }
        catch (
            Throwable erro
        ) {

            erro.printStackTrace(
                escritor
            );


            codigo =
                1;

        }


        escritor.flush();


        /*
        |--------------------------------------------------------------------------
        | RESULTADO
        |--------------------------------------------------------------------------
        */

        TerminalNative.reportCompile(
            codigo,
            buffer.toString()
        );

    }

}
`.trim();


/*
|--------------------------------------------------------------------------
| FONTE - JAVA VERSION
|--------------------------------------------------------------------------
*/

const FONTE_VERSION = `
package javacafe.runtime;

public final class TerminalVersion {

    private TerminalVersion() {
    }


    public static void main(
        String[] args
    ) {

        String versao =
            System.getProperty(
                "java.version",
                "desconhecida"
            );


        String vm =
            System.getProperty(
                "java.vm.name",
                "Java Virtual Machine"
            );


        TerminalNative.writeText(
            1,
            "java version \\"" +
            versao +
            "\\"\\n" +
            vm +
            "\\n" +
            "CheerpJ 4.3 - Java executando no navegador\\n"
        );

    }

}
`.trim();


/*
|--------------------------------------------------------------------------
| ENVIA AO MOTOR
|--------------------------------------------------------------------------
*/

function enviar(
    dados
) {

    self.postMessage(
        dados
    );

}


/*
|--------------------------------------------------------------------------
| ERRO PARA TEXTO
|--------------------------------------------------------------------------
*/

function erroParaTexto(
    erro
) {

    if (
        erro instanceof Error
    ) {

        return (
            erro.message
            ||
            erro.name
            ||
            "Erro desconhecido."
        );

    }


    return String(
        erro
        ??
        "Erro desconhecido."
    );

}


/*
|--------------------------------------------------------------------------
| LIMITA TEXTO
|--------------------------------------------------------------------------
*/

function limitarTexto(
    texto,
    limite
) {

    const valor =
        String(
            texto
            ?? ""
        );


    if (
        valor.length <=
        limite
    ) {

        return valor;

    }


    return (
        valor.slice(
            0,
            limite
        )
        +
        "\n...[conteúdo reduzido pelo laboratório]"
    );

}


/*
|--------------------------------------------------------------------------
| RESETA CONTADOR DE SAÍDA
|--------------------------------------------------------------------------
*/

function resetarSaida() {

    bytesSaida =
        0;


    avisoLimiteSaidaEnviado =
        false;

}


/*
|--------------------------------------------------------------------------
| AVISA LIMITE DE SAÍDA
|--------------------------------------------------------------------------
*/

function avisarLimiteSaida() {

    if (
        avisoLimiteSaidaEnviado
    ) {

        return;

    }


    avisoLimiteSaidaEnviado =
        true;


    enviar(
        {

            tipo:
                "stderr",

            dados:
                "\nA saída do programa foi limitada para proteger o navegador.\n"

        }
    );

}


/*
|--------------------------------------------------------------------------
| ENVIA STDOUT / STDERR COM LIMITE
|--------------------------------------------------------------------------
*/

function enviarSaidaControlada(
    canal,
    texto
) {

    if (
        avisoLimiteSaidaEnviado
    ) {

        return;

    }


    const valor =
        String(
            texto
            ?? ""
        );


    if (
        valor ===
        ""
    ) {

        return;

    }


    const bytes =
        encoderUtf8.encode(
            valor
        );


    const restante =
        LIMITE_SAIDA_BYTES
        -
        bytesSaida;


    if (
        restante <= 0
    ) {

        avisarLimiteSaida();


        return;

    }


    /*
    |--------------------------------------------------------------------------
    | CABE INTEIRO
    |--------------------------------------------------------------------------
    */

    if (
        bytes.byteLength <=
        restante
    ) {

        bytesSaida +=
            bytes.byteLength;


        enviar(
            {

                tipo:
                    Number(
                        canal
                    ) === 2
                        ?
                        "stderr"
                        :
                        "stdout",

                dados:
                    valor

            }
        );


        return;

    }


    /*
    |--------------------------------------------------------------------------
    | ENVIA SOMENTE O RESTANTE
    |--------------------------------------------------------------------------
    */

    const parcial =
        decoderUtf8.decode(
            bytes.slice(
                0,
                restante
            )
        );


    if (
        parcial !==
        ""
    ) {

        bytesSaida =
            LIMITE_SAIDA_BYTES;


        enviar(
            {

                tipo:
                    Number(
                        canal
                    ) === 2
                        ?
                        "stderr"
                        :
                        "stdout",

                dados:
                    parcial

            }
        );

    }


    avisarLimiteSaida();

}


/*
|--------------------------------------------------------------------------
| CAMINHO DO TOOLS.JAR
|--------------------------------------------------------------------------
*/
function resolverToolsJar() {

    return "/str/tools.jar";

}


/*
|--------------------------------------------------------------------------
| CARREGA TOOLS.JAR EM PARTES
|--------------------------------------------------------------------------
|
| O InfinityFree não permite o tools.jar original por causa do tamanho.
| Por isso ele é armazenado em partes menores e reconstruído no navegador.
|
*/

async function carregarToolsJar() {

    const partes = [

        "../java/tools_parts/tools.part01.bin",
        "../java/tools_parts/tools.part02.bin",
        "../java/tools_parts/tools.part03.bin"

    ];


    const arquivos = [];

    let tamanhoTotal = 0;


    for (const parte of partes) {

        const urlParte =
            new URL(
                parte,
                self.location.href
            );


        console.info(
            "[Terminal Java] Carregando:",
            urlParte.href
        );


        const resposta =
            await fetch(
                urlParte.href,
                {
                    cache: "force-cache"
                }
            );


        if (!resposta.ok) {

            throw new Error(
                "Não foi possível carregar " +
                parte +
                ". HTTP " +
                resposta.status
            );

        }


        const dados =
            new Uint8Array(
                await resposta.arrayBuffer()
            );


        arquivos.push(
            dados
        );


        tamanhoTotal +=
            dados.byteLength;

    }


    /*
    |--------------------------------------------------------------------------
    | RECONSTRÓI O JAR
    |--------------------------------------------------------------------------
    */

    const toolsJar =
        new Uint8Array(
            tamanhoTotal
        );


    let posicao = 0;


    for (const dados of arquivos) {

        toolsJar.set(
            dados,
            posicao
        );


        posicao +=
            dados.byteLength;

    }


    /*
    |--------------------------------------------------------------------------
    | ADICIONA AO SISTEMA DE ARQUIVOS DO CHEERPJ
    |--------------------------------------------------------------------------
    */

    if (
        typeof self.cheerpOSAddStringFile !==
        "function"
    ) {

        throw new Error(
            "cheerpOSAddStringFile não está disponível."
        );

    }


    self.cheerpOSAddStringFile(
        "/str/tools.jar",
        toolsJar
    );


    console.info(
        "[Terminal Java] tools.jar reconstruído:",
        tamanhoTotal,
        "bytes"
    );


    return "/str/tools.jar";

}


/*
|--------------------------------------------------------------------------
| ADICIONA ARQUIVO EM /str
|--------------------------------------------------------------------------
*/

function adicionarArquivo(
    caminho,
    conteudo
) {

    if (
        typeof self.cheerpOSAddStringFile ===
        "function"
    ) {

        self.cheerpOSAddStringFile(
            caminho,
            conteudo
        );


        return;

    }


    throw new Error(
        "CheerpJ não disponibilizou cheerpOSAddStringFile()."
    );

}


/*
|--------------------------------------------------------------------------
| RETIRA BYTE DO STDIN
|--------------------------------------------------------------------------
*/

function retirarByteEntrada() {

    if (
        filaEntrada.length ===
        0
    ) {

        return null;

    }


    return filaEntrada.shift();

}


/*
|--------------------------------------------------------------------------
| RESOLVE LEITURAS PENDENTES
|--------------------------------------------------------------------------
*/

function resolverLeiturasPendentes() {

    while (
        filaEntrada.length > 0
        &&
        leiturasPendentes.length > 0
    ) {

        const resolver =
            leiturasPendentes.shift();


        const byte =
            filaEntrada.shift();


        resolver(
            byte
        );

    }

}


/*
|--------------------------------------------------------------------------
| ADICIONA STDIN
|--------------------------------------------------------------------------
*/

function adicionarEntrada(
    texto
) {

    const valor =
        String(
            texto
            ?? ""
        );


    const bytes =
        encoderUtf8.encode(
            valor
        );


    /*
    |--------------------------------------------------------------------------
    | LIMITE POR ENVIO
    |--------------------------------------------------------------------------
    */

    if (
        bytes.byteLength >
        LIMITE_ENTRADA
    ) {

        throw new Error(
            "A entrada enviada ao programa é muito grande."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LIMITE TOTAL DA FILA
    |--------------------------------------------------------------------------
    */

    if (
        filaEntrada.length
        +
        bytes.byteLength
        >
        LIMITE_FILA_ENTRADA
    ) {

        throw new Error(
            "A fila de entrada do programa atingiu o limite permitido."
        );

    }


    for (
        const byte
        of bytes
    ) {

        filaEntrada.push(
            byte
        );

    }


    resolverLeiturasPendentes();

}


/*
|--------------------------------------------------------------------------
| LIMPA STDIN
|--------------------------------------------------------------------------
*/

function limparEntrada() {

    filaEntrada.length =
        0;

}


/*
|--------------------------------------------------------------------------
| NATIVE - STDOUT / STDERR
|--------------------------------------------------------------------------
*/

function Java_javacafe_runtime_TerminalNative_writeText(
    lib,
    canal,
    texto
) {

    enviarSaidaControlada(
        canal,
        texto
    );

}


/*
|--------------------------------------------------------------------------
| NATIVE - READ BYTE
|--------------------------------------------------------------------------
*/

function Java_javacafe_runtime_TerminalNative_readByte(
    lib
) {

    const existente =
        retirarByteEntrada();


    if (
        existente !== null
    ) {

        return existente;

    }


    return new Promise(
        resolver => {

            leiturasPendentes.push(
                resolver
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| NATIVE - AVAILABLE
|--------------------------------------------------------------------------
*/

function Java_javacafe_runtime_TerminalNative_availableBytes(
    lib
) {

    return filaEntrada.length;

}


/*
|--------------------------------------------------------------------------
| NATIVE - RESULTADO DO JAVAC
|--------------------------------------------------------------------------
*/

function Java_javacafe_runtime_TerminalNative_reportCompile(
    lib,
    codigo,
    mensagem
) {

    resultadoCompilacao =
        {

            codigo:
                Number(
                    codigo
                ),

            mensagem:
                limitarTexto(
                    mensagem,
                    LIMITE_DIAGNOSTICO
                )

        };

}


/*
|--------------------------------------------------------------------------
| CARREGA CHEERPJ
|--------------------------------------------------------------------------
*/

function carregarCheerpJ() {

    enviar(
        {

            tipo:
                "runtime_progresso",

            mensagem:
                "Carregando Java no navegador..."

        }
    );


    importScripts(
        CHEERPJ_URL
    );


    if (
        typeof self.cheerpjInit !==
        "function"
    ) {

        throw new Error(
            "CheerpJ não foi carregado corretamente."
        );

    }

}


/*
|--------------------------------------------------------------------------
| ESCREVE FONTES INTERNAS
|--------------------------------------------------------------------------
*/

function escreverFontesRuntime() {

    adicionarArquivo(
        "/str/TerminalNative.java",
        FONTE_NATIVE
    );


    adicionarArquivo(
        "/str/JavaCafeOutputStream.java",
        FONTE_OUTPUT_STREAM
    );


    adicionarArquivo(
        "/str/JavaCafeInputStream.java",
        FONTE_INPUT_STREAM
    );


    adicionarArquivo(
        "/str/TerminalRunner.java",
        FONTE_RUNNER
    );


    adicionarArquivo(
        "/str/TerminalCompiler.java",
        FONTE_COMPILER
    );


    adicionarArquivo(
        "/str/TerminalVersion.java",
        FONTE_VERSION
    );

}


/*
|--------------------------------------------------------------------------
| COMPILA COMPONENTES INTERNOS
|--------------------------------------------------------------------------
*/

async function compilarRuntimeAuxiliar() {

    enviar(
        {

            tipo:
                "runtime_progresso",

            mensagem:
                "Preparando componentes do terminal..."

        }
    );


    const fontes = [

        "/str/TerminalNative.java",

        "/str/JavaCafeOutputStream.java",

        "/str/JavaCafeInputStream.java",

        "/str/TerminalRunner.java",

        "/str/TerminalCompiler.java",

        "/str/TerminalVersion.java"

    ];


    const codigo =
        await self.cheerpjRunMain(
            "com.sun.tools.javac.Main",
            classPath,

            "-encoding",
            "UTF-8",

            "-classpath",
            classPath,

            "-d",
            "/files/",

            ...fontes
        );


    if (
        Number(
            codigo
        ) !== 0
    ) {

        throw new Error(
            "Não foi possível preparar os componentes internos do Terminal Java."
        );

    }

}


/*
|--------------------------------------------------------------------------
| INICIALIZA JAVA
|--------------------------------------------------------------------------
*/

async function inicializar() {

    if (
        runtimePronto
    ) {

        enviar(
            {

                tipo:
                    "runtime_pronto",

                mensagem:
                    "Ambiente Java pronto.",

                java:
                    "Java 8 / CheerpJ 4.3"

            }
        );


        return;

    }


    if (
        inicializando
    ) {

        return;

    }


    inicializando =
        true;


    try {

        /*
        |--------------------------------------------------------------------------
        | CHEERPJ
        |--------------------------------------------------------------------------
        */

        carregarCheerpJ();


        /*
        |--------------------------------------------------------------------------
        | TOOLS.JAR
        |--------------------------------------------------------------------------
        */

        const caminhoToolsJar =
            resolverToolsJar();


        /*
        |--------------------------------------------------------------------------
        | CLASSPATH
        |--------------------------------------------------------------------------
        |
        | /files/
        |     componentes internos
        |
        | /files/user/
        |     classes compiladas do estudante
        |
        |--------------------------------------------------------------------------
        */

        classPath =
            caminhoToolsJar
            +
            ":/files/"
            +
            ":/files/user/";


        console.info(
            "[Terminal Java] tools.jar:",
            caminhoToolsJar
        );


        /*
        |--------------------------------------------------------------------------
        | JVM
        |--------------------------------------------------------------------------
        */

        enviar(
            {

                tipo:
                    "runtime_progresso",

                mensagem:
                    "Inicializando Java..."

            }
        );


        await self.cheerpjInit(
            {

                version:
                    8,

                status:
                    "none",


                /*
                |--------------------------------------------------------------------------
                | NATIVES
                |--------------------------------------------------------------------------
                */

                natives: {

                    Java_javacafe_runtime_TerminalNative_writeText,

                    Java_javacafe_runtime_TerminalNative_readByte,

                    Java_javacafe_runtime_TerminalNative_availableBytes,

                    Java_javacafe_runtime_TerminalNative_reportCompile

                },


                /*
                |--------------------------------------------------------------------------
                | BLOQUEIA PROCESSOS EXTERNOS
                |--------------------------------------------------------------------------
                */

                execCallback(
                    comando,
                    argumentos
                ) {

                    enviar(
                        {

                            tipo:
                                "stderr",

                            dados:
                                "Execução de processos externos não é permitida neste laboratório.\n"

                        }
                    );


                    console.warn(
                        "[Terminal Java] Processo externo bloqueado:",
                        comando,
                        argumentos
                    );

                }

            }
        );

        /*
        |--------------------------------------------------------------------------
        | TOOLS.JAR
        |--------------------------------------------------------------------------
        */

        enviar(
            {

                tipo:
                    "runtime_progresso",

                mensagem:
                    "Carregando compilador Java..."

            }
        );


        await carregarToolsJar();
        /*
        |--------------------------------------------------------------------------
        | FONTES INTERNAS
        |--------------------------------------------------------------------------
        */

        escreverFontesRuntime();


        /*
        |--------------------------------------------------------------------------
        | COMPILA RUNTIME INTERNO
        |--------------------------------------------------------------------------
        */

        await compilarRuntimeAuxiliar();


        /*
        |--------------------------------------------------------------------------
        | PRONTO
        |--------------------------------------------------------------------------
        */

        runtimePronto =
            true;


        inicializando =
            false;


        enviar(
            {

                tipo:
                    "runtime_pronto",

                mensagem:
                    "Ambiente Java pronto.",

                java:
                    "Java 8 / CheerpJ 4.3"

            }
        );

    }
    catch (erro) {

        inicializando =
            false;


        runtimePronto =
            false;


        codigoCompilado =
            false;


        console.error(
            "[Terminal Java] Inicialização:",
            erro
        );


        enviar(
            {

                tipo:
                    "runtime_erro",

                mensagem:
                    limitarTexto(
                        erroParaTexto(
                            erro
                        ),
                        20000
                    )

            }
        );

    }

}


/*
|--------------------------------------------------------------------------
| COMPILA MAIN.JAVA
|--------------------------------------------------------------------------
*/

async function compilar(
    codigoFonte
) {

    if (
        !runtimePronto
    ) {

        throw new Error(
            "O ambiente Java ainda não está pronto."
        );

    }


    if (
        compilando
    ) {

        throw new Error(
            "Uma compilação já está em andamento."
        );

    }


    if (
        programaExecutando
    ) {

        throw new Error(
            "Existe um programa Java em execução."
        );

    }


    if (
        comandoExecutando
    ) {

        throw new Error(
            "Existe outra operação Java em andamento."
        );

    }


    const fonte =
        String(
            codigoFonte
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


    /*
    |--------------------------------------------------------------------------
    | TAMANHO
    |--------------------------------------------------------------------------
    */

    const tamanho =
        encoderUtf8
            .encode(
                fonte
            )
            .byteLength;


    if (
        tamanho >
        LIMITE_CODIGO
    ) {

        throw new Error(
            "Main.java ultrapassou o tamanho permitido."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ESTADO
    |--------------------------------------------------------------------------
    */

    compilando =
        true;


    codigoCompilado =
        false;


    resultadoCompilacao =
        null;


    /*
    |--------------------------------------------------------------------------
    | AVISA INTERFACE
    |--------------------------------------------------------------------------
    */

    enviar(
        {

            tipo:
                "compilacao_iniciada"

        }
    );


    try {

        /*
        |--------------------------------------------------------------------------
        | MAIN.JAVA
        |--------------------------------------------------------------------------
        */

        adicionarArquivo(
            "/str/Main.java",
            fonte
        );


        /*
        |--------------------------------------------------------------------------
        | COMPILADOR INTERNO
        |--------------------------------------------------------------------------
        */

        await self.cheerpjRunMain(
            "javacafe.runtime.TerminalCompiler",
            classPath,
            "/str/Main.java"
        );


        /*
        |--------------------------------------------------------------------------
        | RESULTADO
        |--------------------------------------------------------------------------
        */

        if (
            !resultadoCompilacao
        ) {

            throw new Error(
                "O compilador não retornou um resultado."
            );

        }


        const diagnostico =
            String(
                resultadoCompilacao.mensagem
                ?? ""
            );


        /*
        |--------------------------------------------------------------------------
        | DIAGNÓSTICO
        |--------------------------------------------------------------------------
        */

        if (
            diagnostico !==
            ""
        ) {

            enviar(
                {

                    tipo:
                        resultadoCompilacao.codigo === 0
                            ?
                            "stdout"
                            :
                            "stderr",

                    dados:
                        diagnostico

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ESTADO
        |--------------------------------------------------------------------------
        */

        codigoCompilado =
            resultadoCompilacao.codigo ===
            0;


        /*
        |--------------------------------------------------------------------------
        | FINALIZA
        |--------------------------------------------------------------------------
        */

        enviar(
            {

                tipo:
                    "compilacao_finalizada",

                sucesso:
                    codigoCompilado,

                codigo:
                    resultadoCompilacao.codigo,

                mensagem:
                    codigoCompilado
                        ?
                        "Main.java compilado com sucesso."
                        :
                        "O código possui erro de compilação."

            }
        );

    }
    catch (erro) {

        codigoCompilado =
            false;


        enviar(
            {

                tipo:
                    "erro",

                mensagem:
                    "Não foi possível compilar Main.java.",

                detalhe:
                    limitarTexto(
                        erroParaTexto(
                            erro
                        ),
                        20000
                    )

            }
        );

    }
    finally {

        compilando =
            false;

    }

}


/*
|--------------------------------------------------------------------------
| EXECUTA MAIN
|--------------------------------------------------------------------------
*/

async function executar() {

    if (
        !runtimePronto
    ) {

        throw new Error(
            "O ambiente Java ainda não está pronto."
        );

    }


    if (
        compilando
    ) {

        throw new Error(
            "Aguarde a compilação terminar."
        );

    }


    if (
        comandoExecutando
    ) {

        throw new Error(
            "Existe outra operação Java em andamento."
        );

    }


    if (
        programaExecutando
    ) {

        throw new Error(
            "Já existe um programa Java em execução."
        );

    }


    if (
        !codigoCompilado
    ) {

        throw new Error(
            "Compile Main.java antes de executar."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LIMPA STDIN E SAÍDA
    |--------------------------------------------------------------------------
    */

    limparEntrada();


    resetarSaida();


    /*
    |--------------------------------------------------------------------------
    | ESTADO
    |--------------------------------------------------------------------------
    */

    programaExecutando =
        true;


    enviar(
        {

            tipo:
                "execucao_iniciada",

            comando:
                "java Main"

        }
    );


    let codigoSaida =
        0;


    try {

        const retorno =
            await self.cheerpjRunMain(
                "javacafe.runtime.TerminalRunner",
                classPath,
                "Main"
            );


        if (
            Number.isFinite(
                Number(
                    retorno
                )
            )
        ) {

            codigoSaida =
                Number(
                    retorno
                );

        }

    }
    catch (erro) {

        codigoSaida =
            1;


        enviarSaidaControlada(
            2,
            limitarTexto(
                erroParaTexto(
                    erro
                ),
                20000
            )
            +
            "\n"
        );

    }
    finally {

        programaExecutando =
            false;


        limparEntrada();


        enviar(
            {

                tipo:
                    "execucao_finalizada",

                codigo:
                    codigoSaida,

                sinal:
                    null,

                interrompido:
                    false

            }
        );

    }

}


/*
|--------------------------------------------------------------------------
| JAVA -VERSION
|--------------------------------------------------------------------------
*/

async function executarJavaVersion() {

    if (
        !runtimePronto
    ) {

        throw new Error(
            "O ambiente Java ainda não está pronto."
        );

    }


    if (
        compilando
    ) {

        throw new Error(
            "Aguarde a compilação terminar."
        );

    }


    if (
        programaExecutando
    ) {

        throw new Error(
            "Aguarde o programa Java terminar."
        );

    }


    if (
        comandoExecutando
    ) {

        throw new Error(
            "Existe outra operação Java em andamento."
        );

    }


    comandoExecutando =
        true;


    resetarSaida();


    enviar(
        {

            tipo:
                "comando_iniciado",

            comando:
                "java -version"

        }
    );


    try {

        await self.cheerpjRunMain(
            "javacafe.runtime.TerminalVersion",
            classPath
        );


        enviar(
            {

                tipo:
                    "comando_finalizado",

                comando:
                    "java -version",

                codigo:
                    0

            }
        );

    }
    catch (erro) {

        enviar(
            {

                tipo:
                    "erro",

                mensagem:
                    "Não foi possível consultar a versão do Java.",

                detalhe:
                    limitarTexto(
                        erroParaTexto(
                            erro
                        ),
                        20000
                    )

            }
        );

    }
    finally {

        comandoExecutando =
            false;

    }

}


/*
|--------------------------------------------------------------------------
| RECEBE STDIN
|--------------------------------------------------------------------------
*/

function receberEntrada(
    dados
) {

    if (
        !programaExecutando
    ) {

        enviar(
            {

                tipo:
                    "erro",

                mensagem:
                    "Nenhum programa está aguardando entrada."

            }
        );


        return;

    }


    adicionarEntrada(
        dados
    );

}


/*
|--------------------------------------------------------------------------
| ENCERRAR
|--------------------------------------------------------------------------
*/

function encerrarWorker() {

    limparEntrada();


    runtimePronto =
        false;


    inicializando =
        false;


    compilando =
        false;


    programaExecutando =
        false;


    comandoExecutando =
        false;


    codigoCompilado =
        false;


    resultadoCompilacao =
        null;


    self.close();

}


/*
|--------------------------------------------------------------------------
| MENSAGENS DO MOTOR
|--------------------------------------------------------------------------
*/

self.addEventListener(
    "message",
    async evento => {

        const mensagem =
            evento.data;


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


        try {

            /*
            |--------------------------------------------------------------------------
            | INICIALIZAR
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "inicializar"
            ) {

                await inicializar();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | COMPILAR
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "compilar"
            ) {

                await compilar(
                    mensagem.codigo
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | EXECUTAR
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "executar"
            ) {

                await executar();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | STDIN
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "stdin"
            ) {

                receberEntrada(
                    mensagem.dados
                );


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | JAVA VERSION
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "java_version"
            ) {

                await executarJavaVersion();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | ENCERRAR
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "encerrar"
            ) {

                encerrarWorker();


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | DESCONHECIDO
            |--------------------------------------------------------------------------
            */

            enviar(
                {

                    tipo:
                        "erro",

                    mensagem:
                        "Operação não reconhecida pelo ambiente Java."

                }
            );

        }
        catch (erro) {

            console.error(
                "[Terminal Java] Worker:",
                erro
            );


            enviar(
                {

                    tipo:
                        "erro",

                    mensagem:
                        "O ambiente Java encontrou um erro.",

                    detalhe:
                        limitarTexto(
                            erroParaTexto(
                                erro
                            ),
                            20000
                        )

                }
            );

        }

    }
);