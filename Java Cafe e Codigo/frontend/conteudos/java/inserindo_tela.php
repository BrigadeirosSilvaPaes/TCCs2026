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


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "variaveis"
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
    content="Aprenda a utilizar System.out.print() e System.out.println() para mostrar informações no console em Java."
>

<title>
    Inserindo texto na tela | Java, Café e Código
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= $versaoStyle ?>"
>


<!-- =====================================================
CSS DA AULA
====================================================== -->

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
| CABEÇALHO
|--------------------------------------------------------------------------
-->

<section class="aula-cabecalho">

    <span class="aula-numero">

        AULA 01

    </span>

    <h1>

        Inserindo texto na tela

    </h1>

    <p>

        Aprenda como fazer um programa Java
        mostrar mensagens e informações no console.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Começando pelo começo

    </h2>

    <p>

        Quando começamos a aprender programação,
        uma das primeiras coisas que queremos fazer
        é ver algum resultado acontecendo na tela.

    </p>

    <p>

        Afinal, escrever código sem conseguir
        visualizar o que ele faz pode tornar
        o aprendizado mais difícil.

    </p>

    <p>

        Por isso, uma das primeiras instruções
        que aprendemos em Java serve justamente
        para mostrar informações ao usuário.

    </p>

    <p>

        Podemos imaginar que o programa é como
        uma pessoa tentando se comunicar.

    </p>

    <p>

        Se o programa não consegue apresentar
        nenhuma informação, é como se ele soubesse
        pensar, mas não conseguisse falar.

    </p>

    <div class="aula-destaque">

        <strong>

            Pense assim:

        </strong>

        <p>

            O programa executa instruções,
            e o console permite visualizar
            o resultado dessas instruções.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CONSOLE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é o console?

    </h2>

    <p>

        O console é uma área utilizada para exibir
        textos, resultados, avisos e mensagens
        produzidas pelo programa.

    </p>

    <p>

        Quando escrevemos uma instrução de saída,
        o Java envia a informação para o console.

    </p>

    <p>

        Podemos imaginar o seguinte caminho:

    </p>

    <div class="aula-destaque">

        <p>

            O programador escreve o código.

        </p>

        <p>

            ↓

        </p>

        <p>

            O Java executa as instruções.

        </p>

        <p>

            ↓

        </p>

        <p>

            O resultado aparece no console.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PRIMEIRO EXEMPLO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Nosso primeiro exemplo

    </h2>

    <p>

        Vamos começar com algo simples.

    </p>

    <p>

        Imagine que queremos criar um programa
        que mostre uma mensagem na tela.

    </p>

    <p>

        Podemos escrever:

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, mundo!");</code></pre>

    <p>

        Quando essa instrução for executada,
        o resultado será:

    </p>

    <div class="resultado-codigo">

        <code>Olá, mundo!</code>

    </div>

    <p>

        O Java recebeu uma instrução dizendo
        que deveria mostrar o texto escrito
        entre as aspas.

    </p>

    <div class="aula-destaque">

        <p>

            É como se estivéssemos dizendo:

        </p>

        <p>

            <strong>

                Java, escreva esta mensagem
                no console.

            </strong>

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ENTENDENDO PRINTLN
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Entendendo o System.out.println()

    </h2>

    <p>

        No começo, essa instrução pode parecer
        grande e um pouco estranha.

    </p>

    <p>

        Vamos separar cada parte para entender
        melhor.

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, mundo!");</code></pre>

    <h3>

        System

    </h3>

    <p>

        O <strong>System</strong> é uma classe
        do próprio Java.

    </p>

    <p>

        Ela permite acessar alguns recursos
        importantes do sistema.

    </p>

    <p>

        Neste momento, você não precisa memorizar
        todos os detalhes internos dessa classe.

    </p>

    <h3>

        out

    </h3>

    <p>

        O <strong>out</strong> representa
        a saída padrão do programa.

    </p>

    <p>

        É nessa saída que as mensagens aparecem
        quando executamos o código.

    </p>

    <h3>

        println

    </h3>

    <p>

        O <strong>println</strong> mostra
        uma informação e depois pula
        para a próxima linha.

    </p>

    <h3>

        Parênteses

    </h3>

    <p>

        Dentro dos parênteses colocamos aquilo
        que desejamos mostrar.

    </p>

    <pre class="codigo-java"><code>("Olá, mundo!")</code></pre>

    <h3>

        Ponto e vírgula

    </h3>

    <p>

        O ponto e vírgula indica o final
        da instrução.

    </p>

    <pre class="codigo-java"><code>;</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| TEXTOS E ASPAS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Por que o texto fica entre aspas?

    </h2>

    <p>

        Em Java, textos escritos diretamente
        dentro do código precisam ficar
        entre aspas duplas.

    </p>

    <pre class="codigo-java"><code>System.out.println("Estou aprendendo Java!");</code></pre>

    <p>

        O conteúdo que está entre as aspas
        será mostrado no console.

    </p>

    <div class="resultado-codigo">

        <code>Estou aprendendo Java!</code>

    </div>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            As aspas indicam onde o texto começa
            e onde ele termina.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIAS MENSAGENS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Mostrando várias mensagens

    </h2>

    <p>

        Podemos utilizar várias instruções
        <strong>System.out.println()</strong>
        dentro do mesmo programa.

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá!");
System.out.println("Bem-vindo à plataforma.");
System.out.println("Vamos aprender Java.");</code></pre>

    <p>

        O resultado será:

    </p>

    <div class="resultado-codigo"><code>Olá!
Bem-vindo à plataforma.
Vamos aprender Java.</code></div>

    <p>

        Cada mensagem apareceu em uma linha
        diferente.

    </p>

    <p>

        Isso acontece porque o
        <strong>println()</strong>
        pula para a próxima linha depois
        de mostrar a informação.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PRINT E PRINTLN
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Print e println são a mesma coisa?

    </h2>

    <p>

        Não exatamente.

    </p>

    <p>

        As duas instruções servem para mostrar
        informações, mas existe uma diferença
        importante entre elas.

    </p>

    <h3>

        Utilizando println()

    </h3>

    <p>

        O <strong>println()</strong> mostra
        a mensagem e pula para a próxima linha.

    </p>

    <pre class="codigo-java"><code>System.out.println("Java");
System.out.println("Café e Código");</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Java
Café e Código</code></div>

    <h3>

        Utilizando print()

    </h3>

    <p>

        O <strong>print()</strong> mostra
        a mensagem, mas continua na mesma linha.

    </p>

    <pre class="codigo-java"><code>System.out.print("Java ");
System.out.print("Café e Código");</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo">

        <code>Java Café e Código</code>

    </div>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>

                Instrução

            </span>

            <span>

                O que faz

            </span>

            <span>

                Depois da mensagem

            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>

                println()

            </span>

            <span>

                Mostra a informação

            </span>

            <span>

                Pula uma linha

            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>

                print()

            </span>

            <span>

                Mostra a informação

            </span>

            <span>

                Continua na mesma linha

            </span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| QUEBRA DE LINHA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando uma quebra de linha

    </h2>

    <p>

        Também podemos utilizar
        <strong>\n</strong>
        para criar uma quebra de linha
        dentro de uma mensagem.

    </p>

    <pre class="codigo-java"><code>System.out.println("Java\nCafé e Código");</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Java
Café e Código</code></div>

    <p>

        A sequência
        <strong>\n</strong>
        representa uma nova linha.

    </p>

    <div class="aula-destaque">

        <strong>

            Importante:

        </strong>

        <p>

            A barra utilizada é a barra invertida:

        </p>

        <p>

            <strong>\</strong>

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| TABULAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando um espaço de tabulação

    </h2>

    <p>

        Outra sequência que podemos utilizar
        é o <strong>\t</strong>.

    </p>

    <p>

        Ela cria um espaço semelhante
        ao produzido pela tecla Tab do teclado.

    </p>

    <pre class="codigo-java"><code>System.out.println("Nome:\tRobert");</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo">

        <code>Nome:    Robert</code>

    </div>

    <p>

        O tamanho do espaço pode variar
        dependendo do console utilizado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ASPAS DENTRO DO TEXTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Mostrando aspas dentro de uma mensagem

    </h2>

    <p>

        Como os textos já utilizam aspas duplas,
        precisamos utilizar uma barra invertida
        quando queremos mostrar aspas dentro
        da própria mensagem.

    </p>

    <pre class="codigo-java"><code>System.out.println("Ela disse: \"Olá!\"");</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo">

        <code>Ela disse: "Olá!"</code>

    </div>

    <p>

        A sequência
        <strong>\"</strong>
        permite mostrar uma aspa dentro
        de um texto.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PROGRAMA COMPLETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Colocando a instrução em um programa completo

    </h2>

    <p>

        Uma instrução Java normalmente aparece
        dentro da estrutura de uma classe
        e do método principal.

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        System.out.println("Olá, mundo!");

    }

}</code></pre>

    <p>

        Quando esse programa for executado,
        o Java começará pelo método
        <strong>main</strong>.

    </p>

    <p>

        Depois, ele executará a instrução:

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, mundo!");</code></pre>

    <p>

        O resultado será:

    </p>

    <div class="resultado-codigo">

        <code>Olá, mundo!</code>

    </div>

    <div class="aula-destaque">

        <strong>

            Por enquanto:

        </strong>

        <p>

            Não é necessário memorizar toda
            a estrutura da classe e do método main.

        </p>

        <p>

            Concentre-se em reconhecer onde
            colocamos o System.out.println().

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ERROS COMUNS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Alguns erros comuns

    </h2>

    <p>

        No começo, é normal cometer pequenos
        erros de escrita.

    </p>

    <p>

        Vamos observar alguns dos erros
        mais comuns.

    </p>

    <h3>

        Esquecer as aspas

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>System.out.println(Olá, mundo!);</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, mundo!");</code></pre>

    <h3>

        Esquecer o ponto e vírgula

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, mundo!")</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, mundo!");</code></pre>

    <h3>

        Escrever println incorretamente

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>System.out.printLn("Olá, mundo!");</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, mundo!");</code></pre>

    <h3>

        Esquecer um parêntese

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, mundo!";</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, mundo!");</code></pre>

    <div class="aula-destaque">

        <strong>

            Java diferencia letras maiúsculas
            e minúsculas.

        </strong>

        <p>

            Por isso,
            <strong>println</strong>
            e
            <strong>printLn</strong>
            não são considerados a mesma coisa.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PRÁTICA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Pratique

    </h2>

    <p>

        Vamos criar um programa que mostre
        três mensagens no console.

    </p>

    <p>

        O resultado deverá ser:

    </p>

    <div class="resultado-codigo"><code>Bem-vindo!
Estou aprendendo Java.
Esta é minha primeira aula.</code></div>

    <p>

        Podemos escrever:

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        System.out.println("Bem-vindo!");
        System.out.println("Estou aprendendo Java.");
        System.out.println("Esta é minha primeira aula.");

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| DESAFIO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Desafio da aula

    </h2>

    <p>

        Crie um programa que apresente
        o seguinte resultado:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert
Projeto: Java, Café e Código
Status: aprendendo Java!</code></div>

    <div class="aula-destaque">

        <strong>

            Regras do desafio:

        </strong>

        <p>

            Utilize pelo menos uma vez
            o <strong>print()</strong>.

        </p>

        <p>

            Utilize pelo menos duas vezes
            o <strong>println()</strong>.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CURIOSIDADE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Curiosidade

    </h2>

    <p>

        Além de
        <strong>print()</strong>
        e
        <strong>println()</strong>,
        o Java também possui uma instrução
        chamada <strong>printf()</strong>.

    </p>

    <p>

        Ela é utilizada para apresentar
        informações com uma formatação
        mais detalhada.

    </p>

    <p>

        Não vamos utilizá-la nesta aula,
        mas você poderá encontrá-la
        em programas Java mais avançados.

    </p>

    <div class="aula-destaque">

        <p>

            Por enquanto, concentre-se
            em dominar print() e println().

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| RESUMO
|--------------------------------------------------------------------------
-->

<section class="aula-resumo">

    <h2>

        Resumindo a aula

    </h2>

    <p>

        Nesta aula, aprendemos como fazer
        um programa Java mostrar informações
        no console.

    </p>

    <p>

        Vimos que:

    </p>

    <ul>

        <li>

            <strong>System.out.println()</strong>
            mostra uma informação e pula
            para a próxima linha;

        </li>

        <li>

            <strong>System.out.print()</strong>
            mostra uma informação e continua
            na mesma linha;

        </li>

        <li>

            textos escritos diretamente no código
            precisam ficar entre aspas duplas;

        </li>

        <li>

            muitas instruções Java terminam
            com ponto e vírgula;

        </li>

        <li>

            <strong>\n</strong>
            cria uma quebra de linha;

        </li>

        <li>

            <strong>\t</strong>
            cria um espaço de tabulação;

        </li>

        <li>

            <strong>\"</strong>
            permite mostrar aspas dentro
            de uma mensagem.

        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A ideia principal é simples:

        </p>

        <p>

            <strong>

                Utilizamos System.out.print()
                e System.out.println() para mostrar
                informações no console.

            </strong>

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Todo grande programa começa
        com uma primeira instrução."

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

        ← Voltar para os conteúdos

    </a>


    <a
        href="<?= aulaJavaEscapar(
            $linkProximaAula
        ) ?>"
        class="aula-botao"
    >

        Próxima aula: Variáveis →

    </a>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../componentes/footer.php";

?>


<!-- =====================================================
TEMA
====================================================== -->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


</body>

</html>