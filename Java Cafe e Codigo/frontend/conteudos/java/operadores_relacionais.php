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
                "operadores_matematicos"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "operadores_logicos"
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
    content="Aprenda como comparar valores utilizando os operadores relacionais da linguagem Java."
>

<title>
    Operadores Relacionais em Java | Java, Café e Código
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

        AULA 06

    </span>

    <h1>

        Operadores Relacionais em Java

    </h1>

    <p>

        Aprenda como comparar valores e descobrir
        se uma condição é verdadeira ou falsa.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Fazendo perguntas ao programa

    </h2>

    <p>

        Até agora, aprendemos como armazenar valores
        em variáveis e realizar cálculos.

    </p>

    <p>

        Porém, um programa também precisa analisar
        informações.

    </p>

    <p>

        Podemos querer descobrir:

    </p>

    <ul>

        <li>

            se uma pessoa possui 18 anos ou mais;

        </li>

        <li>

            se duas pontuações são iguais;

        </li>

        <li>

            se existe dinheiro suficiente para
            realizar uma compra;

        </li>

        <li>

            se uma quantidade é maior que outra.

        </li>

    </ul>

    <p>

        Para realizar essas comparações, utilizamos
        os operadores relacionais.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            Um operador relacional compara dois valores
            e produz uma resposta verdadeira ou falsa.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CONCEITO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que são operadores relacionais?

    </h2>

    <p>

        Operadores relacionais são símbolos utilizados
        para comparar valores.

    </p>

    <p>

        Observe:

    </p>

    <pre class="codigo-java"><code>int idade = 18;

System.out.println(idade &gt;= 18);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        O programa comparou a idade com o número 18.

    </p>

    <p>

        Como 18 é maior ou igual a 18, a resposta
        foi verdadeira.

    </p>

    <div class="aula-destaque">

        <strong>

            Toda comparação retorna:

        </strong>

        <p>

            <strong>true</strong>, quando a comparação
            é verdadeira.

        </p>

        <p>

            <strong>false</strong>, quando a comparação
            é falsa.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| TABELA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Principais operadores relacionais

    </h2>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Operador</span>
            <span>Significado</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>==</span>
            <span>Igual a</span>
            <span>10 == 10</span>

        </div>

        <div class="aula-tabela-linha">

            <span>!=</span>
            <span>Diferente de</span>
            <span>10 != 5</span>

        </div>

        <div class="aula-tabela-linha">

            <span>&gt;</span>
            <span>Maior que</span>
            <span>10 &gt; 5</span>

        </div>

        <div class="aula-tabela-linha">

            <span>&lt;</span>
            <span>Menor que</span>
            <span>5 &lt; 10</span>

        </div>

        <div class="aula-tabela-linha">

            <span>&gt;=</span>
            <span>Maior ou igual a</span>
            <span>10 &gt;= 10</span>

        </div>

        <div class="aula-tabela-linha">

            <span>&lt;=</span>
            <span>Menor ou igual a</span>
            <span>5 &lt;= 10</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| IGUALDADE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Igual a: ==

    </h2>

    <p>

        O operador <strong>==</strong> verifica
        se dois valores são iguais.

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;
int numero2 = 10;

System.out.println(numero1 == numero2);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        Como os dois valores são iguais,
        o resultado é verdadeiro.

    </p>

    <p>

        Observe outro exemplo:

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;
int numero2 = 5;

System.out.println(numero1 == numero2);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>false</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| ATRIBUIÇÃO E IGUALDADE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        = e == não são a mesma coisa

    </h2>

    <p>

        Um erro muito comum é confundir
        <strong>=</strong> com
        <strong>==</strong>.

    </p>

    <h3>

        Um sinal de igual

    </h3>

    <pre class="codigo-java"><code>int idade = 18;</code></pre>

    <p>

        O operador <strong>=</strong> atribui
        um valor a uma variável.

    </p>

    <h3>

        Dois sinais de igual

    </h3>

    <pre class="codigo-java"><code>idade == 18</code></pre>

    <p>

        O operador <strong>==</strong> compara
        dois valores.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Operador</span>
            <span>Função</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>=</span>
            <span>Atribui um valor</span>
            <span>idade = 18</span>

        </div>

        <div class="aula-tabela-linha">

            <span>==</span>
            <span>Compara valores</span>
            <span>idade == 18</span>

        </div>

    </div>

    <div class="aula-destaque">

        <strong>

            Lembre-se:

        </strong>

        <p>

            Um sinal guarda um valor.

        </p>

        <p>

            Dois sinais comparam valores.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| DIFERENTE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Diferente de: !=

    </h2>

    <p>

        O operador <strong>!=</strong> verifica
        se dois valores são diferentes.

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;
int numero2 = 5;

System.out.println(numero1 != numero2);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        Como 10 é diferente de 5,
        a comparação é verdadeira.

    </p>

    <p>

        Se os valores forem iguais:

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;
int numero2 = 10;

System.out.println(numero1 != numero2);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>false</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| MAIOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Maior que: &gt;

    </h2>

    <p>

        O operador <strong>&gt;</strong> verifica
        se o valor da esquerda é maior
        que o valor da direita.

    </p>

    <pre class="codigo-java"><code>int idade = 20;

System.out.println(idade &gt; 18);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        Como 20 é maior que 18, a resposta
        é verdadeira.

    </p>

    <pre class="codigo-java"><code>int idade = 15;

System.out.println(idade &gt; 18);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>false</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| MENOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Menor que: &lt;

    </h2>

    <p>

        O operador <strong>&lt;</strong> verifica
        se o valor da esquerda é menor
        que o valor da direita.

    </p>

    <pre class="codigo-java"><code>int idade = 15;

System.out.println(idade &lt; 18);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <pre class="codigo-java"><code>int idade = 20;

System.out.println(idade &lt; 18);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>false</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| MAIOR OU IGUAL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Maior ou igual a: &gt;=

    </h2>

    <p>

        O operador <strong>&gt;=</strong> verifica
        se um valor é maior ou igual a outro.

    </p>

    <pre class="codigo-java"><code>int idade = 18;

System.out.println(idade &gt;= 18);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        A comparação é verdadeira porque a idade
        é exatamente igual a 18.

    </p>

    <p>

        Ela também seria verdadeira se a idade
        fosse maior.

    </p>

    <pre class="codigo-java"><code>int idade = 20;

System.out.println(idade &gt;= 18);</code></pre>

    <div class="resultado-codigo"><code>true</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| MENOR OU IGUAL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Menor ou igual a: &lt;=

    </h2>

    <p>

        O operador <strong>&lt;=</strong> verifica
        se um valor é menor ou igual a outro.

    </p>

    <pre class="codigo-java"><code>int quantidade = 5;

System.out.println(quantidade &lt;= 10);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        O valor 5 é menor que 10.

    </p>

    <pre class="codigo-java"><code>int quantidade = 10;

System.out.println(quantidade &lt;= 10);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        Nesse segundo exemplo, os valores são iguais.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| BOOLEAN
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O resultado é um valor booleano

    </h2>

    <p>

        Toda comparação produz um valor do tipo
        <strong>boolean</strong>.

    </p>

    <p>

        Por isso, podemos guardar o resultado
        de uma comparação em uma variável.

    </p>

    <pre class="codigo-java"><code>int idade = 20;

boolean maiorDeIdade = idade &gt;= 18;

System.out.println(maiorDeIdade);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        A comparação foi realizada primeiro.

    </p>

    <pre class="codigo-java"><code>idade &gt;= 18</code></pre>

    <p>

        Depois, o resultado foi armazenado
        na variável <strong>maiorDeIdade</strong>.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| COMPARANDO VARIÁVEIS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Comparando variáveis

    </h2>

    <p>

        Também podemos comparar os valores
        armazenados em duas variáveis.

    </p>

    <pre class="codigo-java"><code>int idadeRobert = 17;
int idadeAna = 18;

boolean resultado = idadeRobert &lt; idadeAna;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        A idade de Robert é menor que a idade
        de Ana.

    </p>

    <p>

        Outro exemplo:

    </p>

    <pre class="codigo-java"><code>double dinheiro = 100.00;
double preco = 50.00;

boolean podeComprar = dinheiro &gt;= preco;

System.out.println(podeComprar);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| COMPARANDO CÁLCULOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Comparando o resultado de cálculos

    </h2>

    <p>

        Uma expressão matemática pode ser calculada
        antes de uma comparação.

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;
int numero2 = 5;

boolean resultado = numero1 + numero2 &gt; 10;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        Primeiro, o Java calcula:

    </p>

    <pre class="codigo-java"><code>10 + 5 = 15</code></pre>

    <p>

        Depois, realiza a comparação:

    </p>

    <pre class="codigo-java"><code>15 &gt; 10</code></pre>

    <p>

        Como 15 é maior que 10, o resultado
        é verdadeiro.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| STRING
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Comparando textos com equals()

    </h2>

    <p>

        Para comparar o conteúdo de textos
        do tipo String, normalmente utilizamos
        o método <strong>equals()</strong>.

    </p>

    <pre class="codigo-java"><code>String linguagem = "Java";

boolean resultado = linguagem.equals("Java");

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        O método equals() verifica se os textos
        possuem o mesmo conteúdo.

    </p>

    <div class="aula-destaque">

        <strong>

            Muito importante:

        </strong>

        <p>

            Não utilize == como regra geral
            para comparar o conteúdo de Strings.

        </p>

        <p>

            Para verificar se dois textos possuem
            o mesmo conteúdo, utilize equals().

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| STRING MAIÚSCULA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        equals() diferencia maiúsculas e minúsculas

    </h2>

    <p>

        O método equals() diferencia letras
        maiúsculas e minúsculas.

    </p>

    <pre class="codigo-java"><code>String linguagem = "Java";

System.out.println(
    linguagem.equals("java")
);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>false</code></div>

    <p>

        Os textos possuem letras diferentes:

    </p>

    <pre class="codigo-java"><code>"Java"
"java"</code></pre>

    <p>

        Quando queremos ignorar essa diferença,
        podemos utilizar
        <strong>equalsIgnoreCase()</strong>.

    </p>

    <pre class="codigo-java"><code>String linguagem = "Java";

System.out.println(
    linguagem.equalsIgnoreCase("java")
);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO COM SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Comparando um valor digitado

    </h2>

    <p>

        Podemos receber uma idade e realizar
        uma comparação.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada = new Scanner(System.in);

        System.out.println("Digite sua idade:");
        int idade = entrada.nextInt();

        boolean maiorDeIdade = idade &gt;= 18;

        System.out.println(
            "É maior de idade? " + maiorDeIdade
        );

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite sua idade:
17
É maior de idade? false</code></div>

    <p>

        Neste momento, o programa apenas mostra
        o resultado da comparação.

    </p>

    <p>

        Nas próximas aulas, utilizaremos esse resultado
        para controlar decisões com if e else.

    </p>

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

        Confundir = com ==

    </h3>

    <p>

        O operador = atribui um valor.

    </p>

    <pre class="codigo-java"><code>int idade = 18;</code></pre>

    <p>

        O operador == compara valores.

    </p>

    <pre class="codigo-java"><code>idade == 18</code></pre>

    <h3>

        Inverter a comparação

    </h3>

    <pre class="codigo-java"><code>int idade = 17;

boolean resultado = idade &gt; 18;</code></pre>

    <p>

        Esse código pergunta se 17 é maior
        que 18, e não o contrário.

    </p>

    <p>

        Observe sempre qual valor está
        de cada lado do operador.

    </p>

    <h3>

        Escrever os operadores separados

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>idade &gt; = 18</code></pre>

    <p>

        Os símbolos devem ficar juntos:

    </p>

    <pre class="codigo-java"><code>idade &gt;= 18</code></pre>

    <h3>

        Utilizar =&gt; ou =&lt;

    </h3>

    <p>

        Estes operadores não existem em Java:

    </p>

    <pre class="codigo-java"><code>idade =&gt; 18
idade =&lt; 18</code></pre>

    <p>

        O sinal de maior ou menor aparece primeiro:

    </p>

    <pre class="codigo-java"><code>idade &gt;= 18
idade &lt;= 18</code></pre>

    <h3>

        Comparar Strings com ==

    </h3>

    <p>

        Para comparar o conteúdo de textos,
        utilize equals().

    </p>

    <pre class="codigo-java"><code>String linguagem = "Java";

boolean resultado =
    linguagem.equals("Java");</code></pre>

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

        Vamos comparar a pontuação de dois jogadores.

    </p>

    <pre class="codigo-java"><code>int pontosJogador1 = 150;
int pontosJogador2 = 120;

boolean jogador1Venceu =
    pontosJogador1 &gt; pontosJogador2;

boolean pontuacoesIguais =
    pontosJogador1 == pontosJogador2;

System.out.println(
    "Jogador 1 venceu? " + jogador1Venceu
);

System.out.println(
    "Pontuações iguais? " + pontuacoesIguais
);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Jogador 1 venceu? true
Pontuações iguais? false</code></div>

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

        Crie um programa que receba:

    </p>

    <ul>

        <li>

            o preço de um produto;

        </li>

        <li>

            o dinheiro disponível pelo usuário.

        </li>

    </ul>

    <p>

        Depois, realize comparações para descobrir:

    </p>

    <ul>

        <li>

            se o usuário possui dinheiro suficiente;

        </li>

        <li>

            se o dinheiro é exatamente igual ao preço;

        </li>

        <li>

            se o preço é maior que o dinheiro disponível.

        </li>

    </ul>

    <p>

        O resultado pode ficar semelhante a:

    </p>

    <div class="resultado-codigo"><code>Digite o preço:
50
Digite o dinheiro disponível:
100

Possui dinheiro suficiente? true
O valor é exato? false
O produto é mais caro? false</code></div>

    <div class="aula-destaque">

        <strong>

            Comparações sugeridas:

        </strong>

        <p>

            dinheiro >= preço

        </p>

        <p>

            dinheiro == preço

        </p>

        <p>

            preço > dinheiro

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

        Nesta aula, aprendemos como comparar
        informações em Java.

    </p>

    <ul>

        <li>

            <strong>==</strong>
            verifica se dois valores são iguais;

        </li>

        <li>

            <strong>!=</strong>
            verifica se são diferentes;

        </li>

        <li>

            <strong>&gt;</strong>
            verifica se um valor é maior;

        </li>

        <li>

            <strong>&lt;</strong>
            verifica se um valor é menor;

        </li>

        <li>

            <strong>&gt;=</strong>
            verifica se é maior ou igual;

        </li>

        <li>

            <strong>&lt;=</strong>
            verifica se é menor ou igual;

        </li>

        <li>

            toda comparação produz true ou false;

        </li>

        <li>

            o resultado pode ser armazenado
            em uma variável boolean;

        </li>

        <li>

            o conteúdo de Strings deve ser comparado
            utilizando equals().

        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Operadores relacionais permitem
                fazer perguntas sobre os valores
                utilizados pelo programa.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como combinar
        várias comparações utilizando os operadores
        lógicos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Programar é ensinar o computador
        a fazer perguntas e analisar respostas."

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

        Próxima aula: Operadores lógicos →

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