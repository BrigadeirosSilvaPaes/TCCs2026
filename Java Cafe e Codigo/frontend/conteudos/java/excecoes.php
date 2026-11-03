<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../backend/config/app.php";
require_once __DIR__ . "/../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../backend/config/helpers.php";


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


$linkConteudos =
    rota(
        "conteudos"
    );


$linkAulaAnterior =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "interfaces"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "try_catch"
        ]
    );


/*
|--------------------------------------------------------------------------
| VERIFICA LOGIN
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


/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

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
| ASSETS
|--------------------------------------------------------------------------
*/

$arquivoStyle =
    __DIR__ .
    "/../../assets/css/style.css";


$arquivoAulaCss =
    __DIR__ .
    "/../../assets/css/aula.css";


$arquivoThemeJs =
    __DIR__ .
    "/../../assets/js/theme.js";


$versaoStyle =
    is_file(
        $arquivoStyle
    )
        ? filemtime(
            $arquivoStyle
        )
        : 1;


$versaoAulaCss =
    is_file(
        $arquivoAulaCss
    )
        ? filemtime(
            $arquivoAulaCss
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


/*
|--------------------------------------------------------------------------
| ESCAPA HTML
|--------------------------------------------------------------------------
*/

function aulaJavaEscapar(
    mixed $valor
): string
{

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );

}

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
    content="Aprenda o que são exceções em Java, por que elas acontecem e como preparar programas para lidar com situações inesperadas."
>

<title>
    Tratamento de Exceções em Java | Java, Café e Código
</title>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= $versaoStyle ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/aula.css?v=<?= $versaoAulaCss ?>"
>

</head>

<body>


<?php

require_once __DIR__ .
    "/../../componentes/header.php";

?>


<main class="aula-container">


<!--
|--------------------------------------------------------------------------
| CABEÇALHO DA AULA
|--------------------------------------------------------------------------
-->

<section class="aula-cabecalho">

    <span class="aula-numero">

        AULA 28

    </span>


    <h1>

        Tratamento de Exceções em Java

    </h1>


    <p>

        Aprenda o que são exceções, por que elas acontecem
        e como preparar seus programas para lidar com
        situações inesperadas durante a execução.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE SÃO EXCEÇÕES?
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que são exceções?

    </h2>


    <p>

        Durante a execução de um programa, podem acontecer
        situações inesperadas que impedem o código de continuar
        funcionando normalmente.

    </p>


    <p>

        Essas situações são chamadas de
        <strong>exceções</strong>.

    </p>


    <p>

        Uma exceção acontece quando o programa encontra um
        problema durante sua execução. Por exemplo, podemos
        tentar dividir um número por zero, acessar uma posição
        inexistente de um array ou converter um texto que não
        representa um número.

    </p>


    <div class="aula-destaque">

        <strong>

            Pense em uma exceção como um imprevisto.

        </strong>

        <p>

            O programa estava seguindo seu caminho normalmente,
            mas encontrou uma situação que não estava preparada
            para executar da maneira esperada.

        </p>

    </div>


    <p>

        Se o programa não souber lidar com esse problema,
        sua execução poderá ser interrompida e uma mensagem
        de erro será exibida no console.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO DE EXCEÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Um exemplo de exceção

    </h2>


    <p>

        Vamos imaginar que queremos dividir dois números.

    </p>


    <pre class="codigo-java"><code>int numero1 = 10;

int numero2 = 0;

int resultado = numero1 / numero2;

System.out.println(resultado);</code></pre>


    <p>

        O problema está na divisão:

    </p>


    <pre class="codigo-java"><code>10 / 0</code></pre>


    <p>

        Em matemática, não podemos dividir um número inteiro
        por zero. Quando o Java tenta realizar essa operação,
        uma exceção é gerada.

    </p>


    <p>

        Nesse caso, o Java apresenta uma
        <strong>ArithmeticException</strong>.

    </p>


    <div class="resultado-codigo"><code>Exception in thread "main" java.lang.ArithmeticException: / by zero</code></div>


    <p>

        Esse erro acontece durante a execução do programa,
        por isso é considerado uma exceção de tempo de execução.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| POR QUE TRATAR EXCEÇÕES?
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Por que precisamos tratar exceções?

    </h2>


    <p>

        Imagine que você esteja desenvolvendo um sistema
        utilizado por milhares de pessoas.

    </p>


    <p>

        Se qualquer situação inesperada fizer o programa
        simplesmente parar, a experiência do usuário será
        muito ruim.

    </p>


    <p>

        Por isso, podemos preparar o programa para reconhecer
        determinadas situações e tomar uma atitude adequada
        quando elas acontecerem.

    </p>


    <p>

        O tratamento de exceções permite que o programa
        <strong>controle situações inesperadas</strong>
        e apresente uma resposta mais adequada ao usuário.

    </p>


    <div class="aula-destaque">

        <strong>

            Exceções não significam necessariamente que o
            programador escreveu todo o código errado.

        </strong>

        <p>

            Muitas vezes, elas acontecem por causa de informações
            fornecidas pelo usuário ou de situações que só podem
            ser conhecidas enquanto o programa está sendo executado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SITUAÇÕES QUE PODEM GERAR EXCEÇÕES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Quando uma exceção pode acontecer?

    </h2>


    <p>

        Existem várias situações que podem provocar exceções
        durante a execução de um programa.

    </p>


    <ul>

        <li>

            Dividir um número por zero;

        </li>


        <li>

            Tentar acessar uma posição que não existe em um array;

        </li>


        <li>

            Tentar converter um texto inválido para número;

        </li>


        <li>

            Tentar utilizar um objeto que possui valor
            <strong>null</strong>;

        </li>


        <li>

            Tentar abrir um arquivo que não existe;

        </li>


        <li>

            Tentar realizar uma operação que não pode ser concluída
            naquele momento.

        </li>

    </ul>


    <p>

        Cada situação pode gerar diferentes tipos de exceções,
        dependendo do problema encontrado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| TIPOS DE EXCEÇÕES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Tipos de exceções

    </h2>


    <p>

        O Java possui diferentes tipos de exceções.
        Cada uma representa uma situação diferente
        que pode acontecer durante a execução do programa.

    </p>


    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>

                Exceção

            </span>

            <span>

                Situação

            </span>

            <span>

                Exemplo

            </span>

        </div>


        <div class="aula-tabela-linha">

            <span>

                ArithmeticException

            </span>

            <span>

                Erros em operações matemáticas

            </span>

            <span>

                Divisão por zero

            </span>

        </div>


        <div class="aula-tabela-linha">

            <span>

                ArrayIndexOutOfBoundsException

            </span>

            <span>

                Acesso a posição inexistente

            </span>

            <span>

                Array[10]

            </span>

        </div>


        <div class="aula-tabela-linha">

            <span>

                NumberFormatException

            </span>

            <span>

                Conversão inválida de texto

            </span>

            <span>

                Integer.parseInt("abc")

            </span>

        </div>


        <div class="aula-tabela-linha">

            <span>

                NullPointerException

            </span>

            <span>

                Utilização de uma referência nula

            </span>

            <span>

                objeto.metodo()

            </span>

        </div>

    </div>


    <p>

        Conhecer os diferentes tipos de exceções ajuda
        o programador a entender o que aconteceu quando
        um problema ocorre.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ARRAY E EXCEÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo com Arrays

    </h2>


    <p>

        Vamos observar outro exemplo.

    </p>


    <pre class="codigo-java"><code>int[] numeros = {10, 20, 30};

System.out.println(numeros[5]);</code></pre>


    <p>

        Nosso array possui apenas três posições:

    </p>


    <ul>

        <li>

            Posição 0 → 10

        </li>


        <li>

            Posição 1 → 20

        </li>


        <li>

            Posição 2 → 30

        </li>

    </ul>


    <p>

        Porém, estamos tentando acessar a posição 5,
        que não existe.

    </p>


    <pre class="codigo-java"><code>numeros[5]</code></pre>


    <p>

        Nesse caso, o Java gera uma exceção chamada
        <strong>ArrayIndexOutOfBoundsException</strong>.

    </p>


    <div class="aula-destaque">

        <strong>

            O problema acontece durante a execução.

        </strong>

        <p>

            O código pode estar escrito corretamente do ponto
            de vista da sintaxe, mas ainda assim uma situação
            inesperada pode acontecer quando o programa estiver
            sendo executado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXCEÇÕES E USUÁRIO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exceções causadas pela entrada do usuário

    </h2>


    <p>

        Um dos casos mais comuns acontece quando o programa
        recebe informações digitadas pelo usuário.

    </p>


    <p>

        Imagine um programa que espera receber um número inteiro.

    </p>


    <pre class="codigo-java"><code>Scanner scanner = new Scanner(System.in);

System.out.print("Digite sua idade: ");

int idade = scanner.nextInt();

System.out.println("Idade: " + idade);</code></pre>


    <p>

        Se o usuário digitar:

    </p>


    <pre class="codigo-java"><code>17</code></pre>


    <p>

        O programa funcionará normalmente.

    </p>


    <p>

        Porém, se o usuário digitar:

    </p>


    <pre class="codigo-java"><code>abc</code></pre>


    <p>

        O Java não conseguirá interpretar esse texto como
        um número inteiro e poderá gerar uma exceção.

    </p>


    <p>

        Esse é um exemplo de situação em que o programa precisa
        estar preparado para receber informações inesperadas.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| HIERARQUIA DE EXCEÇÕES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A hierarquia de exceções

    </h2>


    <p>

        No Java, as exceções fazem parte de uma estrutura
        organizada de classes.

    </p>


    <p>

        De forma simplificada, podemos imaginar que existe
        uma classe principal chamada <strong>Throwable</strong>,
        que possui diferentes tipos de problemas derivados dela.

    </p>


    <p>

        Entre os principais grupos estão
        <strong>Error</strong> e <strong>Exception</strong>.

    </p>


    <p>

        Os objetos do tipo <strong>Error</strong> normalmente
        representam problemas graves relacionados ao ambiente
        de execução da aplicação.

    </p>


    <p>

        Já as classes derivadas de <strong>Exception</strong>
        representam situações que geralmente podem ser tratadas
        pelo programa.

    </p>


    <div class="aula-destaque">

        <strong>

            O importante neste momento é entender a ideia.

        </strong>

        <p>

            O Java organiza os diferentes problemas em uma
            hierarquia de classes. Nas próximas aulas,
            utilizaremos mecanismos específicos para lidar
            com essas exceções.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| TRATAMENTO DE EXCEÇÕES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como tratar uma exceção?

    </h2>


    <p>

        Para tratar exceções, o Java possui estruturas
        específicas que permitem controlar o que acontece
        quando um problema é encontrado.

    </p>


    <p>

        Uma das estruturas mais importantes utiliza
        <strong>try</strong> e <strong>catch</strong>.

    </p>


    <p>

        O bloco <strong>try</strong> é utilizado para colocar
        o código que pode gerar uma exceção.

    </p>


    <p>

        O bloco <strong>catch</strong> é utilizado para definir
        o que deve acontecer caso uma exceção seja encontrada.

    </p>


    <p>

        Um exemplo básico seria:

    </p>


    <pre class="codigo-java"><code>try {

    // Código que pode gerar uma exceção

} catch (Exception e) {

    // Código executado quando ocorre uma exceção

}</code></pre>


    <p>

        Na próxima aula, vamos estudar detalhadamente
        como essa estrutura funciona.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| IMPORTÂNCIA DO TRATAMENTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que acontece quando tratamos uma exceção?

    </h2>


    <p>

        Sem tratamento, uma exceção pode interromper a execução
        do programa.

    </p>


    <p>

        Com o tratamento adequado, podemos informar ao usuário
        que ocorreu um problema e permitir que o programa
        continue funcionando de maneira controlada.

    </p>


    <p>

        Por exemplo, em um sistema de cadastro, se o usuário
        digitar uma informação inválida, podemos apresentar
        uma mensagem explicando o problema em vez de simplesmente
        encerrar o programa.

    </p>


    <div class="aula-destaque">

        <strong>

            Um bom programa não considera apenas o caminho perfeito.

        </strong>

        <p>

            Ele também está preparado para situações inesperadas,
            entradas inválidas e possíveis falhas durante sua execução.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO CONCEITUAL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Pensando como um programador

    </h2>


    <p>

        Imagine um programa que pede ao usuário para informar
        sua idade.

    </p>


    <p>

        O programador pode pensar no caminho normal:

    </p>


    <ul>

        <li>

            O usuário digita uma idade válida;

        </li>


        <li>

            O programa recebe a informação;

        </li>


        <li>

            A idade é armazenada;

        </li>


        <li>

            O programa continua sua execução.

        </li>

    </ul>


    <p>

        Porém, também precisamos pensar em situações diferentes:

    </p>


    <ul>

        <li>

            O usuário digita letras;

        </li>


        <li>

            O usuário digita um valor inválido;

        </li>


        <li>

            O usuário não informa o valor esperado;

        </li>


        <li>

            Ocorre algum problema durante a execução.

        </li>

    </ul>


    <p>

        O tratamento de exceções existe justamente para
        ajudar o programa a lidar com esses caminhos inesperados.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| RESUMO
|--------------------------------------------------------------------------
-->

<section class="aula-resumo">

    <h2>

        O que aprendemos?

    </h2>


    <p>

        Nesta aula, você aprendeu que exceções são situações
        inesperadas que podem acontecer durante a execução
        de um programa Java.

    </p>


    <ul>

        <li>

            Exceções acontecem durante a execução do programa;

        </li>


        <li>

            Uma exceção pode interromper a execução caso não
            seja tratada;

        </li>


        <li>

            Divisão por zero pode gerar
            <strong>ArithmeticException</strong>;

        </li>


        <li>

            Acessar uma posição inexistente de um array pode gerar
            <strong>ArrayIndexOutOfBoundsException</strong>;

        </li>


        <li>

            Converter um texto inválido para número pode gerar
            <strong>NumberFormatException</strong>;

        </li>


        <li>

            Utilizar uma referência nula pode gerar
            <strong>NullPointerException</strong>;

        </li>


        <li>

            A entrada de dados do usuário pode provocar
            situações inesperadas;

        </li>


        <li>

            O Java possui mecanismos para tratar exceções;

        </li>


        <li>

            A estrutura <strong>try/catch</strong> é uma das
            principais formas de tratamento de exceções.

        </li>

    </ul>


    <p>

        Agora você já entende o que são exceções e por que
        é importante preparar seus programas para situações
        inesperadas.

    </p>


    <p>

        Na próxima aula, vamos aprender na prática como utilizar
        <strong>try e catch</strong> para capturar exceções,
        apresentar mensagens ao usuário e manter o programa
        funcionando de maneira controlada.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Um bom programador não pensa apenas no que deve acontecer,
        mas também se prepara para o que pode dar errado."

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| NAVEGAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-acoes">


    <a
        href="<?= aulaJavaEscapar(
            $linkConteudos
        ) ?>"
        class="aula-botao"
    >

        ← Voltar para conteúdos

    </a>


    <a
        href="<?= aulaJavaEscapar(
            $linkAulaAnterior
        ) ?>"
        class="aula-botao"
    >

        ← Aula anterior

    </a>


    <a
        href="<?= aulaJavaEscapar(
            $linkProximaAula
        ) ?>"
        class="aula-botao"
    >

        Próxima aula: Try e Catch →

    </a>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../componentes/footer.php";

?>


<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


</body>

</html>