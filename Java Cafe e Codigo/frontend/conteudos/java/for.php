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
                "retorno"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "while"
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
    content="Aprenda como utilizar a estrutura de repetição for para repetir comandos em Java."
>

<title>
    Estrutura de Repetição For | Java, Café e Código
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
| CABEÇALHO
|--------------------------------------------------------------------------
-->

<section class="aula-cabecalho">

    <span class="aula-numero">

        AULA 15

    </span>

    <h1>

        Estrutura de repetição for

    </h1>

    <p>

        Aprenda como repetir instruções automaticamente
        e controlar a quantidade de repetições em um
        programa Java.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Por que precisamos repetir comandos?

    </h2>

    <p>

        Durante a programação, muitas vezes precisamos
        executar a mesma instrução várias vezes.

    </p>

    <p>

        Imagine que desejamos mostrar a mensagem
        “Olá, Java!” cinco vezes.

    </p>

    <p>

        Poderíamos repetir manualmente o comando:

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, Java!");
System.out.println("Olá, Java!");
System.out.println("Olá, Java!");
System.out.println("Olá, Java!");
System.out.println("Olá, Java!");</code></pre>

    <p>

        Esse código funciona, mas possui muita repetição.

    </p>

    <p>

        Se precisássemos mostrar a mensagem cem vezes,
        teríamos um código ainda maior e mais difícil
        de manter.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            Estruturas de repetição permitem executar
            um bloco de código várias vezes sem precisar
            escrever as mesmas instruções repetidamente.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE É FOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é o for?

    </h2>

    <p>

        O <strong>for</strong> é uma estrutura de repetição
        muito utilizada quando sabemos ou conseguimos
        definir quantas vezes uma ação deverá acontecer.

    </p>

    <p>

        Por exemplo, podemos utilizar for para:

    </p>

    <ul>

        <li>
            contar de 1 até 10;
        </li>

        <li>
            repetir uma mensagem cinco vezes;
        </li>

        <li>
            criar uma tabuada;
        </li>

        <li>
            percorrer valores de uma sequência;
        </li>

        <li>
            realizar cálculos repetidamente.
        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| ESTRUTURA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Estrutura básica do for

    </h2>

    <pre class="codigo-java"><code>for (inicializacao; condicao; atualizacao) {

    // Código que será repetido

}</code></pre>

    <p>

        A estrutura possui três partes principais,
        separadas por ponto e vírgula.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Função</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Inicialização</span>

            <span>
                Define o valor inicial do contador
            </span>

            <span>int i = 1</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Condição</span>

            <span>
                Define enquanto o laço continuará
            </span>

            <span>i &lt;= 5</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Atualização</span>

            <span>
                Modifica o contador após cada repetição
            </span>

            <span>i++</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PRIMEIRO EXEMPLO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Contando de 1 até 5

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        for (int i = 1; i &lt;= 5; i++) {

            System.out.println(i);

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>1
2
3
4
5</code></div>

    <p>

        A variável <strong>i</strong> começa com o valor 1.

    </p>

    <p>

        Enquanto i for menor ou igual a 5,
        o bloco será executado.

    </p>

    <p>

        Depois de cada execução, i será aumentado em 1.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FLUXO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como o for funciona?

    </h2>

    <div class="aula-destaque">

        <p>
            A variável i é criada com o valor 1.
        </p>

        <p>↓</p>

        <p>
            A condição i &lt;= 5 é verificada.
        </p>

        <p>↓</p>

        <p>
            Se a condição for verdadeira,
            o bloco é executado.
        </p>

        <p>↓</p>

        <p>
            O comando i++ aumenta o contador.
        </p>

        <p>↓</p>

        <p>
            A condição é verificada novamente.
        </p>

    </div>

    <p>

        Quando i chegar ao valor 6, a condição será falsa
        e a repetição terminará.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CONTADOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A variável de controle

    </h2>

    <p>

        A variável utilizada para controlar a repetição
        costuma ser chamada de contador.

    </p>

    <pre class="codigo-java"><code>for (int contador = 1; contador &lt;= 3; contador++) {

    System.out.println(
        "Repetição: " + contador
    );

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Repetição: 1
Repetição: 2
Repetição: 3</code></div>

    <p>

        É muito comum utilizar nomes curtos como
        <strong>i</strong>, <strong>j</strong> e
        <strong>k</strong> como contadores.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INCREMENTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O operador de incremento

    </h2>

    <p>

        O operador <strong>++</strong> aumenta o valor
        de uma variável em uma unidade.

    </p>

    <pre class="codigo-java"><code>int numero = 1;

numero++;

System.out.println(numero);</code></pre>

    <div class="resultado-codigo"><code>2</code></div>

    <p>

        Estas duas instruções possuem um resultado
        semelhante:

    </p>

    <pre class="codigo-java"><code>numero++;

numero = numero + 1;</code></pre>

    <p>

        O operador ++ é muito utilizado em estruturas
        de repetição.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CONTAGEM REGRESSIVA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Contagem regressiva

    </h2>

    <p>

        Também podemos diminuir o contador utilizando
        o operador <strong>--</strong>.

    </p>

    <pre class="codigo-java"><code>for (int i = 5; i &gt;= 1; i--) {

    System.out.println(i);

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>5
4
3
2
1</code></div>

    <p>

        Nesse exemplo:

    </p>

    <ul>

        <li>
            começamos em 5;
        </li>

        <li>
            continuamos enquanto i for maior ou igual a 1;
        </li>

        <li>
            diminuímos i em uma unidade.
        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| PASSOS DIFERENTES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Aumentando de dois em dois

    </h2>

    <p>

        A atualização não precisa acontecer apenas
        de uma unidade por vez.

    </p>

    <pre class="codigo-java"><code>for (int i = 0; i &lt;= 10; i += 2) {

    System.out.println(i);

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>0
2
4
6
8
10</code></div>

    <p>

        O comando <strong>i += 2</strong> adiciona
        duas unidades ao contador após cada repetição.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| NÚMEROS ÍMPARES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Contando números ímpares

    </h2>

    <p>

        Podemos começar pelo número 1 e continuar
        aumentando de dois em dois.

    </p>

    <pre class="codigo-java"><code>for (int i = 1; i &lt;= 10; i += 2) {

    System.out.println(i);

}</code></pre>

    <div class="resultado-codigo"><code>1
3
5
7
9</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| REPETINDO TEXTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Repetindo uma mensagem

    </h2>

    <p>

        O for pode repetir qualquer bloco de código,
        não apenas números.

    </p>

    <pre class="codigo-java"><code>for (int i = 1; i &lt;= 5; i++) {

    System.out.println(
        "Olá, Java!"
    );

}</code></pre>

    <div class="resultado-codigo"><code>Olá, Java!
Olá, Java!
Olá, Java!
Olá, Java!
Olá, Java!</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| TEXTO COM CONTADOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando o contador na mensagem

    </h2>

    <pre class="codigo-java"><code>for (int i = 1; i &lt;= 4; i++) {

    System.out.println(
        "Executando pela " + i + "ª vez."
    );

}</code></pre>

    <div class="resultado-codigo"><code>Executando pela 1ª vez.
Executando pela 2ª vez.
Executando pela 3ª vez.
Executando pela 4ª vez.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| VARIÁVEIS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando outras variáveis

    </h2>

    <pre class="codigo-java"><code>String nome = "Robert";

for (int i = 1; i &lt;= 3; i++) {

    System.out.println(
        "Olá, " + nome + "!"
    );

}</code></pre>

    <div class="resultado-codigo"><code>Olá, Robert!
Olá, Robert!
Olá, Robert!</code></div>

    <p>

        O for controla a quantidade de repetições,
        enquanto a variável nome guarda a informação
        exibida na mensagem.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ACUMULADOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Somando valores com for

    </h2>

    <p>

        Podemos utilizar uma variável para acumular
        resultados durante as repetições.

    </p>

    <pre class="codigo-java"><code>int soma = 0;

for (int i = 1; i &lt;= 5; i++) {

    soma = soma + i;

}

System.out.println(
    "Resultado: " + soma
);</code></pre>

    <p>

        O programa realizará:

    </p>

    <pre class="codigo-java"><code>1 + 2 + 3 + 4 + 5</code></pre>

    <div class="resultado-codigo"><code>Resultado: 15</code></div>

    <div class="aula-destaque">

        <strong>

            Acumulador:

        </strong>

        <p>

            A variável soma começa com zero e recebe
            um novo valor a cada repetição.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| TABUADA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: tabuada

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        int numero = 5;

        for (int i = 1; i &lt;= 10; i++) {

            int resultado =
                numero * i;

            System.out.println(
                numero
                + " x "
                + i
                + " = "
                + resultado
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>5 x 1 = 5
5 x 2 = 10
5 x 3 = 15
5 x 4 = 20
5 x 5 = 25
5 x 6 = 30
5 x 7 = 35
5 x 8 = 40
5 x 9 = 45
5 x 10 = 50</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| FOR COM MÉTODO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando for dentro de um método

    </h2>

    <pre class="codigo-java"><code>public class Main {

    static void mostrarTabuada(int numero) {

        for (int i = 1; i &lt;= 10; i++) {

            System.out.println(
                numero
                + " x "
                + i
                + " = "
                + (numero * i)
            );

        }

    }


    public static void main(String[] args) {

        mostrarTabuada(7);

    }

}</code></pre>

    <p>

        O método pode receber qualquer número
        e gerar sua tabuada.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| LIMITES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Diferença entre &lt; e &lt;=

    </h2>

    <p>

        Observe este for:

    </p>

    <pre class="codigo-java"><code>for (int i = 1; i &lt;= 5; i++) {

    System.out.println(i);

}</code></pre>

    <p>

        Como utilizamos menor ou igual, o número 5
        será incluído.

    </p>

    <div class="resultado-codigo"><code>1
2
3
4
5</code></div>

    <p>

        Agora observe:

    </p>

    <pre class="codigo-java"><code>for (int i = 1; i &lt; 5; i++) {

    System.out.println(i);

}</code></pre>

    <p>

        O número 5 não será incluído.

    </p>

    <div class="resultado-codigo"><code>1
2
3
4</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| LOOP INFINITO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Cuidado com repetições infinitas

    </h2>

    <p>

        A condição precisa conseguir se tornar falsa.

    </p>

    <p>

        Este exemplo possui um problema:

    </p>

    <pre class="codigo-java"><code>for (int i = 1; i &lt;= 5; i--) {

    System.out.println(i);

}</code></pre>

    <p>

        O contador começa em 1 e é diminuído.

    </p>

    <p>

        Portanto, ele continuará sendo menor ou igual
        a 5 e a repetição não terminará normalmente.

    </p>

    <div class="aula-destaque">

        <strong>

            Verifique sempre:

        </strong>

        <p>

            Se a atualização aproxima o contador
            do momento em que a condição será falsa.

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

        Erros comuns

    </h2>

    <h3>

        Esquecer os pontos e vírgulas

    </h3>

    <p>

        As três partes do for devem ser separadas
        por ponto e vírgula.

    </p>

    <pre class="codigo-java"><code>for (int i = 1; i &lt;= 5; i++) {

}</code></pre>

    <h3>

        Colocar ponto e vírgula depois do for

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>for (int i = 1; i &lt;= 5; i++); {

    System.out.println(i);

}</code></pre>

    <p>

        Não coloque ponto e vírgula depois
        dos parênteses do for.

    </p>

    <h3>

        Utilizar uma atualização incompatível

    </h3>

    <p>

        Se a contagem precisa crescer,
        utilize uma atualização que aumente o contador.

    </p>

    <h3>

        Utilizar o limite errado

    </h3>

    <p>

        Verifique se deseja incluir ou não
        o último valor da sequência.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PRÁTICA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Prática guiada

    </h2>

    <p>

        Vamos mostrar os quadrados dos números
        de 1 até 5.

    </p>

    <pre class="codigo-java"><code>for (int i = 1; i &lt;= 5; i++) {

    int quadrado = i * i;

    System.out.println(
        i + "² = " + quadrado
    );

}</code></pre>

    <div class="resultado-codigo"><code>1² = 1
2² = 4
3² = 9
4² = 16
5² = 25</code></div>

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

        Crie um programa que mostre os números
        de 1 até 20.

    </p>

    <p>

        Para cada número, informe se ele é par
        ou ímpar.

    </p>

    <div class="resultado-codigo"><code>1 é ímpar
2 é par
3 é ímpar
4 é par
...</code></div>

    <div class="aula-destaque">

        <strong>

            Condição sugerida:

        </strong>

        <p>

            Utilize <strong>i % 2 == 0</strong>
            para verificar se o número é par.

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

        Nesta aula, aprendemos como utilizar
        a estrutura de repetição for.

    </p>

    <ul>

        <li>
            for repete um bloco de código;
        </li>

        <li>
            é útil quando conseguimos controlar
            a quantidade de repetições;
        </li>

        <li>
            possui inicialização, condição
            e atualização;
        </li>

        <li>
            ++ aumenta uma variável em uma unidade;
        </li>

        <li>
            -- diminui uma variável em uma unidade;
        </li>

        <li>
            += permite aumentar em outros intervalos;
        </li>

        <li>
            o contador pode ser utilizado
            dentro do bloco;
        </li>

        <li>
            podemos repetir textos e cálculos;
        </li>

        <li>
            acumuladores guardam resultados
            entre as repetições;
        </li>

        <li>
            a condição precisa conseguir
            se tornar falsa.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                O for permite controlar uma repetição
                utilizando um contador.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como repetir
        comandos enquanto uma condição for verdadeira
        utilizando while.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Automatizar uma repetição é transformar
        várias instruções em uma única lógica."

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

        Próxima aula: While →

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