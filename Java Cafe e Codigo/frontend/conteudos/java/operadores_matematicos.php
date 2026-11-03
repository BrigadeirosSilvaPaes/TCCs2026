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
                "scanner"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "operadores_relacionais"
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
    content="Aprenda como realizar cálculos utilizando os operadores matemáticos da linguagem Java."
>

<title>
    Operadores Matemáticos em Java | Java, Café e Código
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

        AULA 05

    </span>

    <h1>

        Operadores Matemáticos em Java

    </h1>

    <p>

        Aprenda como realizar cálculos de adição,
        subtração, multiplicação, divisão e resto
        utilizando valores e variáveis.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Fazendo o programa calcular

    </h2>

    <p>

        Nas aulas anteriores, aprendemos como guardar
        informações em variáveis e receber valores
        digitados pelo usuário.

    </p>

    <p>

        Agora podemos utilizar essas informações
        para realizar cálculos.

    </p>

    <p>

        Imagine, por exemplo, um programa que precisa
        calcular o valor total de uma compra.

    </p>

    <pre class="codigo-java"><code>double preco = 25.00;
int quantidade = 3;

double total = preco * quantidade;</code></pre>

    <p>

        O programa utiliza o preço e a quantidade
        para calcular automaticamente o total.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            Os operadores matemáticos são símbolos
            que permitem realizar cálculos utilizando
            números e variáveis numéricas.

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

        O que são operadores matemáticos?

    </h2>

    <p>

        Operadores matemáticos, também chamados
        de operadores aritméticos, são símbolos
        utilizados para realizar operações com números.

    </p>

    <p>

        Podemos, por exemplo, somar dois valores:

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;
int numero2 = 5;

int resultado = numero1 + numero2;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>15</code></div>

    <p>

        O operador <strong>+</strong> recebeu
        os valores 10 e 5 e realizou uma adição.

    </p>

    <div class="aula-destaque">

        <strong>

            Os operadores podem trabalhar com:

        </strong>

        <p>

            Valores escritos diretamente no código
            ou valores armazenados em variáveis.

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

        Principais operadores matemáticos

    </h2>

    <p>

        Nesta aula, vamos trabalhar com cinco
        operadores matemáticos principais.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Operador</span>
            <span>Operação</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>+</span>
            <span>Adição</span>
            <span>10 + 5</span>

        </div>

        <div class="aula-tabela-linha">

            <span>-</span>
            <span>Subtração</span>
            <span>10 - 5</span>

        </div>

        <div class="aula-tabela-linha">

            <span>*</span>
            <span>Multiplicação</span>
            <span>10 * 5</span>

        </div>

        <div class="aula-tabela-linha">

            <span>/</span>
            <span>Divisão</span>
            <span>10 / 5</span>

        </div>

        <div class="aula-tabela-linha">

            <span>%</span>
            <span>Resto da divisão</span>
            <span>10 % 3</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ADIÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Adição

    </h2>

    <p>

        O operador de adição é representado
        pelo símbolo <strong>+</strong>.

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;
int numero2 = 5;

int resultado = numero1 + numero2;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>15</code></div>

    <p>

        Também podemos somar números decimais.

    </p>

    <pre class="codigo-java"><code>double preco = 10.50;
double frete = 5.00;

double total = preco + frete;

System.out.println(total);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>15.5</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| ADIÇÃO E TEXTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O símbolo + também junta textos

    </h2>

    <p>

        Quando o símbolo <strong>+</strong>
        é utilizado com textos, ele não realiza
        uma soma.

    </p>

    <p>

        Nesse caso, ele realiza uma concatenação.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";

System.out.println("Olá, " + nome);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Olá, Robert</code></div>

    <p>

        Também precisamos ter atenção ao misturar
        textos e cálculos.

    </p>

    <pre class="codigo-java"><code>System.out.println("Resultado: " + 10 + 5);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Resultado: 105</code></div>

    <p>

        Depois de encontrar o texto, o Java começa
        a juntar os valores como partes da mensagem.

    </p>

    <p>

        Para realizar a soma primeiro, utilizamos
        parênteses.

    </p>

    <pre class="codigo-java"><code>System.out.println("Resultado: " + (10 + 5));</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Resultado: 15</code></div>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            Com números, o operador + pode realizar
            uma adição. Quando existe um texto
            na expressão, ele também pode realizar
            uma concatenação.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SUBTRAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Subtração

    </h2>

    <p>

        O operador de subtração é representado
        pelo símbolo <strong>-</strong>.

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;
int numero2 = 5;

int resultado = numero1 - numero2;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>5</code></div>

    <p>

        A subtração pode representar diferentes
        situações.

    </p>

    <pre class="codigo-java"><code>int dinheiro = 100;
int valorGasto = 35;

int restante = dinheiro - valorGasto;

System.out.println(
    "Dinheiro restante: " + restante
);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Dinheiro restante: 65</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| MULTIPLICAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Multiplicação

    </h2>

    <p>

        O operador de multiplicação é representado
        pelo asterisco <strong>*</strong>.

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;
int numero2 = 5;

int resultado = numero1 * numero2;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>50</code></div>

    <p>

        Podemos utilizar a multiplicação para
        calcular o preço de vários produtos.

    </p>

    <pre class="codigo-java"><code>double precoProduto = 10.00;
int quantidade = 3;

double total = precoProduto * quantidade;

System.out.println("Total: " + total);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Total: 30.0</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| DIVISÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Divisão

    </h2>

    <p>

        O operador de divisão é representado
        pelo símbolo <strong>/</strong>.

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;
int numero2 = 2;

int resultado = numero1 / numero2;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>5</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| DIVISÃO INTEIRA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Divisão entre números inteiros

    </h2>

    <p>

        Quando dividimos dois valores do tipo
        <strong>int</strong>, o resultado também
        será inteiro.

    </p>

    <pre class="codigo-java"><code>int resultado = 5 / 2;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>2</code></div>

    <p>

        Matematicamente, o resultado seria 2,5.

    </p>

    <p>

        Porém, como os dois valores são inteiros,
        a parte decimal é descartada.

    </p>

    <div class="aula-destaque">

        <strong>

            Importante:

        </strong>

        <p>

            O Java não arredondou 2,5 para 2.

        </p>

        <p>

            Ele descartou a parte decimal porque
            a divisão foi realizada entre valores
            inteiros.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| DIVISÃO DECIMAL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Obtendo um resultado decimal

    </h2>

    <p>

        Para obter um resultado decimal, pelo menos
        um dos valores da divisão deve ser decimal.

    </p>

    <pre class="codigo-java"><code>double resultado = 5.0 / 2.0;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>2.5</code></div>

    <p>

        Também poderíamos escrever:

    </p>

    <pre class="codigo-java"><code>double resultado = 5 / 2.0;</code></pre>

    <p>

        Como um dos valores é decimal, o resultado
        também poderá possuir casas decimais.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| DIVISÃO POR ZERO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Divisão por zero

    </h2>

    <p>

        Não podemos realizar uma divisão inteira
        por zero.

    </p>

    <pre class="codigo-java"><code>int resultado = 10 / 0;</code></pre>

    <p>

        Essa operação provoca um erro durante
        a execução do programa.

    </p>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            Antes de realizar uma divisão com um valor
            informado pelo usuário, é importante
            verificar se o divisor é diferente de zero.

        </p>

        <p>

            Aprenderemos a realizar essa verificação
            quando estudarmos as estruturas condicionais.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| RESTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Resto da divisão

    </h2>

    <p>

        O operador de resto da divisão é representado
        pelo símbolo <strong>%</strong>.

    </p>

    <pre class="codigo-java"><code>int resultado = 10 % 3;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>1</code></div>

    <p>

        O número 10 pode ser dividido por 3
        três vezes, restando 1.

    </p>

    <pre class="codigo-java"><code>10 / 3 = 3
Resto = 1</code></pre>

    <div class="aula-destaque">

        <strong>

            O símbolo % não calcula porcentagem.

        </strong>

        <p>

            Em Java, o operador % retorna o resto
            de uma divisão.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PAR OU ÍMPAR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Descobrindo se um número é par

    </h2>

    <p>

        O operador de resto é muito utilizado
        para verificar se um número é par ou ímpar.

    </p>

    <p>

        Quando um número é dividido por 2 e o resto
        é zero, ele é par.

    </p>

    <pre class="codigo-java"><code>int numero = 10;

int resto = numero % 2;

System.out.println(resto);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>0</code></div>

    <p>

        Como o resto foi zero, sabemos que 10
        é divisível por 2.

    </p>

    <p>

        Mais adiante, utilizaremos essa comparação
        junto com <strong>if</strong> e
        <strong>else</strong> para informar
        automaticamente se um número é par ou ímpar.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PRECEDÊNCIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Ordem das operações

    </h2>

    <p>

        Quando uma expressão possui vários operadores,
        o Java segue uma ordem de execução.

    </p>

    <p>

        De forma simplificada, a ordem é:

    </p>

    <ul>

        <li>
            primeiro, operações entre parênteses;
        </li>

        <li>
            depois, multiplicação, divisão e resto;
        </li>

        <li>
            por último, adição e subtração.
        </li>

    </ul>

    <p>

        Observe:

    </p>

    <pre class="codigo-java"><code>int resultado = 10 + 5 * 2;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>20</code></div>

    <p>

        Primeiro, o Java calcula:

    </p>

    <pre class="codigo-java"><code>5 * 2 = 10</code></pre>

    <p>

        Depois:

    </p>

    <pre class="codigo-java"><code>10 + 10 = 20</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| PARÊNTESES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Alterando a ordem com parênteses

    </h2>

    <p>

        Podemos utilizar parênteses para indicar
        qual cálculo deverá ser realizado primeiro.

    </p>

    <pre class="codigo-java"><code>int resultado = (10 + 5) * 2;

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>30</code></div>

    <p>

        Primeiro, o Java calcula o conteúdo
        dos parênteses:

    </p>

    <pre class="codigo-java"><code>10 + 5 = 15</code></pre>

    <p>

        Depois, realiza a multiplicação:

    </p>

    <pre class="codigo-java"><code>15 * 2 = 30</code></pre>

    <div class="aula-destaque">

        <strong>

            Boa prática:

        </strong>

        <p>

            Mesmo quando os parênteses não forem
            obrigatórios, eles podem deixar a intenção
            do cálculo mais clara.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ATRIBUIÇÃO COMPOSTA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Operadores de atribuição composta

    </h2>

    <p>

        Podemos utilizar o valor atual de uma variável
        para realizar uma operação e guardar
        o novo resultado nela.

    </p>

    <pre class="codigo-java"><code>int pontos = 10;

pontos = pontos + 5;

System.out.println(pontos);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>15</code></div>

    <p>

        Existe uma forma abreviada de escrever
        a mesma operação.

    </p>

    <pre class="codigo-java"><code>int pontos = 10;

pontos += 5;

System.out.println(pontos);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>15</code></div>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Forma abreviada</span>
            <span>Forma completa</span>
            <span>Operação</span>

        </div>

        <div class="aula-tabela-linha">

            <span>valor += 5</span>
            <span>valor = valor + 5</span>
            <span>Adição</span>

        </div>

        <div class="aula-tabela-linha">

            <span>valor -= 5</span>
            <span>valor = valor - 5</span>
            <span>Subtração</span>

        </div>

        <div class="aula-tabela-linha">

            <span>valor *= 5</span>
            <span>valor = valor * 5</span>
            <span>Multiplicação</span>

        </div>

        <div class="aula-tabela-linha">

            <span>valor /= 5</span>
            <span>valor = valor / 5</span>
            <span>Divisão</span>

        </div>

        <div class="aula-tabela-linha">

            <span>valor %= 5</span>
            <span>valor = valor % 5</span>
            <span>Resto</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| INCREMENTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Incremento e decremento

    </h2>

    <p>

        Quando queremos aumentar uma variável
        em uma unidade, podemos utilizar
        o operador <strong>++</strong>.

    </p>

    <pre class="codigo-java"><code>int pontos = 10;

pontos++;

System.out.println(pontos);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>11</code></div>

    <p>

        Quando queremos diminuir uma unidade,
        utilizamos <strong>--</strong>.

    </p>

    <pre class="codigo-java"><code>int vidas = 3;

vidas--;

System.out.println(vidas);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>2</code></div>

    <div class="aula-destaque">

        <strong>

            Esses operadores serão muito utilizados:

        </strong>

        <p>

            Incremento e decremento aparecem
            frequentemente em estruturas de repetição,
            contadores, pontuações e controles
            de quantidade.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO COM SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Calculadora com Scanner

    </h2>

    <p>

        Podemos combinar o Scanner com os operadores
        matemáticos.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada = new Scanner(System.in);

        System.out.println("Digite o primeiro número:");
        double numero1 = entrada.nextDouble();

        System.out.println("Digite o segundo número:");
        double numero2 = entrada.nextDouble();

        double soma = numero1 + numero2;
        double subtracao = numero1 - numero2;
        double multiplicacao = numero1 * numero2;
        double divisao = numero1 / numero2;

        System.out.println();
        System.out.println("Soma: " + soma);
        System.out.println("Subtração: " + subtracao);
        System.out.println("Multiplicação: " + multiplicacao);
        System.out.println("Divisão: " + divisao);

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite o primeiro número:
10
Digite o segundo número:
2

Soma: 12.0
Subtração: 8.0
Multiplicação: 20.0
Divisão: 5.0</code></div>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            Neste exemplo, o segundo número não deve
            ser zero, pois ele será utilizado
            como divisor.

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

        Utilizar x para multiplicar

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>int resultado = 10 x 5;</code></pre>

    <p>

        Em Java, utilizamos o asterisco:

    </p>

    <pre class="codigo-java"><code>int resultado = 10 * 5;</code></pre>

    <h3>

        Esperar um resultado decimal usando apenas int

    </h3>

    <pre class="codigo-java"><code>int resultado = 5 / 2;</code></pre>

    <p>

        O resultado será 2.

    </p>

    <p>

        Para obter 2.5, utilize valores decimais:

    </p>

    <pre class="codigo-java"><code>double resultado = 5.0 / 2.0;</code></pre>

    <h3>

        Dividir por zero

    </h3>

    <pre class="codigo-java"><code>int resultado = 10 / 0;</code></pre>

    <p>

        Uma divisão inteira por zero provoca
        um erro de execução.

    </p>

    <h3>

        Confundir resto com porcentagem

    </h3>

    <pre class="codigo-java"><code>int resto = 10 % 3;</code></pre>

    <p>

        O símbolo % retorna o resto da divisão.
        Ele não calcula automaticamente uma porcentagem.

    </p>

    <h3>

        Ignorar a ordem das operações

    </h3>

    <pre class="codigo-java"><code>int resultado = 10 + 5 * 2;</code></pre>

    <p>

        A multiplicação será realizada antes da soma.

    </p>

    <p>

        Utilize parênteses quando precisar controlar
        a ordem.

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

        Vamos calcular a média de três notas.

    </p>

    <pre class="codigo-java"><code>double nota1 = 8.0;
double nota2 = 7.5;
double nota3 = 9.0;

double soma = nota1 + nota2 + nota3;

double media = soma / 3;

System.out.println("Média: " + media);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Média: 8.166666666666666</code></div>

    <p>

        O Java apresenta várias casas decimais
        porque o resultado da divisão não é exato.

    </p>

    <p>

        Mais adiante, aprenderemos como formatar
        a apresentação desses números.

    </p>

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

        Crie um programa para calcular o valor
        de uma compra.

    </p>

    <p>

        O programa deverá receber:

    </p>

    <ul>

        <li>preço do produto;</li>

        <li>quantidade comprada;</li>

        <li>valor pago pelo cliente.</li>

    </ul>

    <p>

        Depois, calcule o total da compra
        e o troco do cliente.

    </p>

    <div class="resultado-codigo"><code>Digite o preço do produto:
25
Digite a quantidade:
3
Digite o valor pago:
100

Total da compra: 75.0
Troco: 25.0</code></div>

    <div class="aula-destaque">

        <strong>

            Cálculos necessários:

        </strong>

        <p>

            Total da compra = preço × quantidade.

        </p>

        <p>

            Troco = valor pago − total da compra.

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

        Nesta aula, aprendemos como realizar
        cálculos em Java.

    </p>

    <ul>

        <li>
            <strong>+</strong>
            realiza adição;
        </li>

        <li>
            <strong>-</strong>
            realiza subtração;
        </li>

        <li>
            <strong>*</strong>
            realiza multiplicação;
        </li>

        <li>
            <strong>/</strong>
            realiza divisão;
        </li>

        <li>
            <strong>%</strong>
            retorna o resto da divisão;
        </li>

        <li>
            divisões entre valores int produzem
            resultados inteiros;
        </li>

        <li>
            parênteses podem alterar a ordem
            dos cálculos;
        </li>

        <li>
            operadores como += e -= atualizam
            o valor de uma variável;
        </li>

        <li>
            <strong>++</strong>
            aumenta uma unidade;
        </li>

        <li>
            <strong>--</strong>
            diminui uma unidade.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Os operadores matemáticos permitem
                transformar valores armazenados
                em novos resultados.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, vamos aprender como
        comparar valores utilizando os operadores
        relacionais.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Programar também é transformar problemas
        em cálculos que o computador consegue resolver."

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

        Próxima aula: Operadores relacionais →

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