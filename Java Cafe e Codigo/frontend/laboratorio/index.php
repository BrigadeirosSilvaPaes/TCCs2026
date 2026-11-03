<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../backend/config/app.php";
require_once __DIR__ . "/../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../backend/config/helpers.php";


/*
|--------------------------------------------------------------------------
| SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| ROTAS
|--------------------------------------------------------------------------
*/

$linkLogin =
    rota(
        "login"
    );


$linkPainel =
    rota(
        "estudante.painel"
    );


$linkConteudos =
    rota(
        "conteudos"
    );


/*
|--------------------------------------------------------------------------
| VERIFICA USUÁRIO
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["usuario"]
    )
    ||
    !is_array(
        $_SESSION["usuario"]
    )
) {

    header(
        "Location: " .
        $linkLogin
    );

    exit;

}


$usuarioId =
    (int) (
        $_SESSION["usuario"]["id"]
        ?? 0
    );


if (
    $usuarioId <= 0
) {

    unset(
        $_SESSION["usuario"]
    );


    header(
        "Location: " .
        $linkLogin
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

$nomeUsuario =
    trim(
        (string) (
            $_SESSION["usuario"]["nome"]
            ?? "Estudante"
        )
    );


if (
    $nomeUsuario === ""
) {

    $nomeUsuario =
        "Estudante";

}


/*
|--------------------------------------------------------------------------
| ESCAPA HTML
|--------------------------------------------------------------------------
*/

function laboratorioEscapar(
    mixed $valor
): string
{

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );

}


/*
|--------------------------------------------------------------------------
| ASSETS
|--------------------------------------------------------------------------
*/

$arquivoStyle =
    __DIR__ .
    "/../assets/css/style.css";


$arquivoTerminalCss =
    __DIR__ .
    "/../assets/css/terminal_java.css";


$arquivoThemeJs =
    __DIR__ .
    "/../assets/js/theme.js";


$arquivoMotorTerminal =
    __DIR__ .
    "/../assets/js/terminal_java_motor.js";


$arquivoTerminalJs =
    __DIR__ .
    "/../assets/js/terminal_java.js";


$versaoStyle =
    is_file(
        $arquivoStyle
    )
        ? filemtime(
            $arquivoStyle
        )
        : 1;


$versaoTerminalCss =
    is_file(
        $arquivoTerminalCss
    )
        ? filemtime(
            $arquivoTerminalCss
        )
        : 1;


$versaoThemeJs =
    is_file(
        $arquivoThemeJs
    )
        ? filemtime(
            $arquivoThemeJs
        )
        : 1;


$versaoMotorTerminal =
    is_file(
        $arquivoMotorTerminal
    )
        ? filemtime(
            $arquivoMotorTerminal
        )
        : 1;


$versaoTerminalJs =
    is_file(
        $arquivoTerminalJs
    )
        ? filemtime(
            $arquivoTerminalJs
        )
        : 1;

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="description"
    content="Laboratório Java da plataforma Java, Café e Código para escrever, compilar e executar programas Java diretamente no navegador."
>

<title>
    Laboratório Java |
    <?= laboratorioEscapar(
        SITE_NAME
    ) ?>
</title>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= $versaoStyle ?>"
>


<?php if (
    is_file(
        $arquivoTerminalCss
    )
): ?>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/terminal_java.css?v=<?= $versaoTerminalCss ?>"
>

<?php endif; ?>


</head>


<body class="pagina-terminal-java">


<?php

require_once __DIR__ .
    "/../componentes/header.php";

?>


<main class="terminal-java-pagina">


<!--
|--------------------------------------------------------------------------
| CABEÇALHO
|--------------------------------------------------------------------------
-->

<section class="terminal-java-cabecalho">


    <div>


        <span class="terminal-java-etiqueta">

            LABORATÓRIO JAVA

        </span>


        <h1>

            Terminal Java

        </h1>


        <p>

            Escreva, compile e execute pequenos programas
            Java diretamente no navegador em um ambiente
            preparado para aprendizagem e experimentação.

        </p>


    </div>


    <a
        href="<?= laboratorioEscapar(
            $linkPainel
        ) ?>"
        class="terminal-java-voltar"
    >

        ← Voltar ao painel

    </a>


</section>


<!--
|--------------------------------------------------------------------------
| AVISO DO AMBIENTE
|--------------------------------------------------------------------------
-->

<section class="terminal-java-aviso">


    <span
        aria-hidden="true"
    >

        i

    </span>


    <div>

        <p>

            Este laboratório é destinado exclusivamente
            ao aprendizado de Java.

        </p>


        <p>

            Os comandos disponíveis pertencem ao ambiente
            didático da plataforma e não fornecem acesso
            ao sistema operacional do dispositivo.

        </p>

    </div>


</section>


<!--
|--------------------------------------------------------------------------
| COMPATIBILIDADE
|--------------------------------------------------------------------------
-->

<section class="terminal-java-aviso">


    <span
        aria-hidden="true"
    >

        !

    </span>


    <div>

        <p>

            O laboratório utiliza um ambiente compatível
            com Java 8.

        </p>


        <p>

            Recursos introduzidos em versões posteriores,
            como módulos do Java 9 ou APIs exclusivas do
            Java 11+, podem aparecer no Manual como material
            de consulta, mas não necessariamente funcionarão
            neste terminal.

        </p>

    </div>


</section>


<!--
|--------------------------------------------------------------------------
| ÁREA PRINCIPAL
|--------------------------------------------------------------------------
-->

<section class="terminal-java-workspace">


    <!--
    |--------------------------------------------------------------------------
    | EDITOR
    |--------------------------------------------------------------------------
    -->

    <article class="terminal-java-editor">


        <header class="terminal-java-editor-topo">


            <div
                class="terminal-java-controles"
                aria-hidden="true"
            >

                <span></span>
                <span></span>
                <span></span>

            </div>


            <strong>

                Main.java

            </strong>


            <small>

                JAVA

            </small>


        </header>


        <div class="terminal-java-editor-conteudo">


            <div
                class="terminal-java-linhas"
                id="terminalJavaLinhas"
                aria-hidden="true"
            >

                <span>1</span>
                <span>2</span>
                <span>3</span>
                <span>4</span>
                <span>5</span>
                <span>6</span>
                <span>7</span>
                <span>8</span>
                <span>9</span>

            </div>


            <label
                for="terminalJavaCodigo"
                class="terminal-java-label"
            >

                Código Java

            </label>


            <textarea
                id="terminalJavaCodigo"
                class="terminal-java-codigo"
                spellcheck="false"
                autocomplete="off"
                autocapitalize="off"
                aria-label="Editor de código Java"
            >public class Main {

    public static void main(String[] args) {

        System.out.println("Olá, Java!");

    }

}</textarea>


        </div>


        <footer class="terminal-java-editor-rodape">


            <span>

                Main.java

            </span>


            <span>

                UTF-8

            </span>


            <span>

                Java

            </span>


        </footer>


    </article>


    <!--
    |--------------------------------------------------------------------------
    | TERMINAL
    |--------------------------------------------------------------------------
    -->

    <article class="terminal-java-terminal">


        <header class="terminal-java-terminal-topo">


            <div>


                <span
                    aria-hidden="true"
                >

                    &gt;_

                </span>


                <strong>

                    TERMINAL

                </strong>


            </div>


            <button
                type="button"
                id="terminalJavaLimpar"
                class="terminal-java-limpar"
            >

                Limpar

            </button>


        </header>


        <!--
        |--------------------------------------------------------------------------
        | SAÍDA
        |--------------------------------------------------------------------------
        -->

        <div
            id="terminalJavaSaida"
            class="terminal-java-saida"
            aria-live="polite"
            aria-atomic="false"
        >


            <div class="terminal-java-sistema">

                Java, Café e Código Terminal

            </div>


            <div class="terminal-java-sistema">

                Ambiente Java executado diretamente no navegador.

            </div>


            <div class="terminal-java-sistema">

                Olá,

                <strong>

                    <?= laboratorioEscapar(
                        $nomeUsuario
                    ) ?>

                </strong>.

            </div>


            <div class="terminal-java-sistema">

                Digite

                <strong>

                    help

                </strong>

                para visualizar os comandos disponíveis.

            </div>


        </div>


        <!--
        |--------------------------------------------------------------------------
        | COMANDO
        |--------------------------------------------------------------------------
        -->

        <form
            id="terminalJavaFormulario"
            class="terminal-java-formulario"
            autocomplete="off"
        >


            <span
                class="terminal-java-prompt"
                aria-hidden="true"
            >

                C:\JavaCafe&gt;

            </span>


            <label
                for="terminalJavaComando"
                class="terminal-java-label"
            >

                Comando

            </label>


            <input
                type="text"
                id="terminalJavaComando"
                class="terminal-java-comando"
                placeholder="Digite um comando..."
                autocomplete="off"
                autocapitalize="off"
                spellcheck="false"
                aria-label="Digite um comando do terminal Java"
            >


        </form>


    </article>


</section>


<!--
|--------------------------------------------------------------------------
| COMANDOS DISPONÍVEIS
|--------------------------------------------------------------------------
-->

<section class="terminal-java-comandos">


    <div class="terminal-java-secao-cabecalho">


        <span class="terminal-java-etiqueta">

            COMANDOS

        </span>


        <h2>

            Comandos disponíveis

        </h2>


        <p>

            A interface simula comandos comuns de
            compilação e execução Java sem liberar
            acesso ao terminal real do computador.

        </p>


    </div>


    <div class="terminal-java-comandos-grid">


        <article>


            <code>

                javac Main.java

            </code>


            <div>

                <strong>

                    Compilar

                </strong>


                <p>

                    Compila o código escrito no editor
                    e prepara a classe para execução.

                </p>

            </div>


        </article>


        <article>


            <code>

                java Main

            </code>


            <div>

                <strong>

                    Executar

                </strong>


                <p>

                    Executa o programa Java depois
                    de uma compilação bem-sucedida.

                </p>

            </div>


        </article>


        <article>


            <code>

                java -version

            </code>


            <div>

                <strong>

                    Versão

                </strong>


                <p>

                    Exibe informações sobre a versão
                    Java utilizada pelo laboratório.

                </p>

            </div>


        </article>


        <article>


            <code>

                help

            </code>


            <div>

                <strong>

                    Ajuda

                </strong>


                <p>

                    Lista os comandos reconhecidos
                    pelo ambiente didático.

                </p>

            </div>


        </article>


        <article>


            <code>

                clear

            </code>


            <div>

                <strong>

                    Limpar

                </strong>


                <p>

                    Limpa as mensagens exibidas
                    na área do terminal.

                </p>

            </div>


        </article>


    </div>


</section>


<!--
|--------------------------------------------------------------------------
| ORIENTAÇÃO
|--------------------------------------------------------------------------
-->

<section class="terminal-java-comandos">


    <div class="terminal-java-secao-cabecalho">


        <span class="terminal-java-etiqueta">

            COMO PRATICAR

        </span>


        <h2>

            Use o laboratório junto das aulas

        </h2>


        <p>

            Depois de aprender um conceito, altere o código,
            teste valores diferentes e observe como o programa
            responde.

        </p>


    </div>


    <div class="terminal-java-comandos-grid">


        <article>


            <code>

                1

            </code>


            <div>

                <strong>

                    Escreva

                </strong>


                <p>

                    Digite ou altere o código no editor.

                </p>

            </div>


        </article>


        <article>


            <code>

                2

            </code>


            <div>

                <strong>

                    Compile

                </strong>


                <p>

                    Utilize javac Main.java para verificar
                    se o código pode ser compilado.

                </p>

            </div>


        </article>


        <article>


            <code>

                3

            </code>


            <div>

                <strong>

                    Execute

                </strong>


                <p>

                    Utilize java Main e observe
                    a saída do programa.

                </p>

            </div>


        </article>


    </div>


</section>


<!--
|--------------------------------------------------------------------------
| NAVEGAÇÃO
|--------------------------------------------------------------------------
-->

<section class="terminal-java-comandos">


    <div class="terminal-java-secao-cabecalho">


        <span class="terminal-java-etiqueta">

            CONTINUE ESTUDANDO

        </span>


        <h2>

            Volte para a trilha quando quiser

        </h2>


        <p>

            Utilize as aulas para aprender novos conceitos
            e retorne ao laboratório para colocá-los
            em prática.

        </p>


    </div>


    <div class="aula-acoes">


        <a
            href="<?= laboratorioEscapar(
                $linkConteudos
            ) ?>"
            class="aula-botao"
        >

            ← Ir para conteúdos

        </a>


        <a
            href="<?= laboratorioEscapar(
                $linkPainel
            ) ?>"
            class="aula-botao"
        >

            Voltar ao painel

        </a>


    </div>


</section>


</main>


<?php

require_once __DIR__ .
    "/../componentes/footer.php";

?>


<!--
|--------------------------------------------------------------------------
| TEMA
|--------------------------------------------------------------------------
-->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


<!--
|--------------------------------------------------------------------------
| MOTOR JAVA
|--------------------------------------------------------------------------
-->

<?php if (
    is_file(
        $arquivoMotorTerminal
    )
): ?>

<script
    src="<?= BASE_URL ?>/frontend/assets/js/terminal_java_motor.js?v=<?= $versaoMotorTerminal ?>"
></script>

<?php endif; ?>


<!--
|--------------------------------------------------------------------------
| INTERFACE DO TERMINAL
|--------------------------------------------------------------------------
-->

<?php if (
    is_file(
        $arquivoTerminalJs
    )
): ?>

<script
    src="<?= BASE_URL ?>/frontend/assets/js/terminal_java.js?v=<?= $versaoTerminalJs ?>"
></script>

<?php endif; ?>


</body>

</html>