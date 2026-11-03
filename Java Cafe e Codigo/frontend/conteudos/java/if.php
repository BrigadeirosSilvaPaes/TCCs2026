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
                "operadores_logicos"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "if_else"
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
    content="Aprenda como utilizar a estrutura if para criar decisões em programas Java."
>

<title>
    Estrutura If em Java | Java, Café e Código
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

        AULA 08

    </span>

    <h1>

        Estrutura if em Java

    </h1>

    <p>

        Aprenda como executar um bloco de código
        somente quando uma determinada condição
        for verdadeira.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Fazendo o programa tomar decisões

    </h2>

    <p>

        Até agora, nossos programas executaram
        instruções seguindo uma sequência.

    </p>

    <p>

        Eles mostraram mensagens, receberam dados,
        realizaram cálculos e compararam valores.

    </p>

    <p>

        Agora vamos aprender como fazer o programa
        escolher se uma instrução deve ou não
        ser executada.

    </p>

    <p>

        Imagine a seguinte regra:

    </p>

    <div class="aula-destaque">

        <strong>

            Se a pessoa tiver 18 anos ou mais:

        </strong>

        <p>

            Mostrar a mensagem "Entrada permitida".

        </p>

    </div>

    <p>

        Em Java, podemos representar essa decisão
        utilizando a estrutura <strong>if</strong>.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE É IF
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é a estrutura if?

    </h2>

    <p>

        A palavra <strong>if</strong>
        significa <strong>se</strong> em inglês.

    </p>

    <p>

        Ela verifica uma condição.

    </p>

    <p>

        Quando essa condição for verdadeira,
        o programa executará um bloco de código.

    </p>

    <pre class="codigo-java"><code>if (condicao) {

    // Código executado quando
    // a condição for verdadeira

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            Se a condição resultar em true,
            o bloco do if será executado.

        </p>

        <p>

            Se resultar em false,
            o bloco será ignorado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ESTRUTURA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Partes da estrutura if

    </h2>

    <pre class="codigo-java"><code>if (idade &gt;= 18) {

    System.out.println("Maior de idade.");

}</code></pre>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Função</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>if</span>
            <span>Inicia uma decisão</span>
            <span>if</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Parênteses</span>
            <span>Guardam a condição analisada</span>
            <span>(idade &gt;= 18)</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Condição</span>
            <span>Produz true ou false</span>
            <span>idade &gt;= 18</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Chaves</span>
            <span>Delimitam o bloco executado</span>
            <span>{ }</span>

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

        Primeiro exemplo com if

    </h2>

    <p>

        Vamos verificar se uma pessoa é maior
        de idade.

    </p>

    <pre class="codigo-java"><code>int idade = 18;

if (idade &gt;= 18) {

    System.out.println(
        "Você é maior de idade."
    );

}</code></pre>

    <p>

        A condição analisada é:

    </p>

    <pre class="codigo-java"><code>idade &gt;= 18</code></pre>

    <p>

        Como a idade é 18, a condição produz true.

    </p>

    <p>

        Por isso, o bloco é executado.

    </p>

    <div class="resultado-codigo"><code>Você é maior de idade.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| FLUXO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como o if funciona?

    </h2>

    <p>

        Podemos representar o funcionamento
        do if desta forma:

    </p>

    <div class="aula-destaque">

        <p>O programa encontra o if.</p>

        <p>↓</p>

        <p>A condição é analisada.</p>

        <p>↓</p>

        <p>
            Se o resultado for true,
            o bloco é executado.
        </p>

        <p>↓</p>

        <p>
            Se o resultado for false,
            o bloco é ignorado.
        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CONDIÇÃO VERDADEIRA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Quando a condição é verdadeira

    </h2>

    <pre class="codigo-java"><code>int pontos = 100;

if (pontos &gt;= 50) {

    System.out.println(
        "Pontuação suficiente."
    );

}</code></pre>

    <p>

        A comparação é:

    </p>

    <pre class="codigo-java"><code>100 &gt;= 50</code></pre>

    <p>

        O resultado é true.

    </p>

    <div class="resultado-codigo"><code>Pontuação suficiente.</code></div>

    <p>

        Depois de executar o bloco, o programa
        continua normalmente pelas próximas instruções.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CONDIÇÃO FALSA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Quando a condição é falsa

    </h2>

    <pre class="codigo-java"><code>int pontos = 30;

if (pontos &gt;= 50) {

    System.out.println(
        "Pontuação suficiente."
    );

}</code></pre>

    <p>

        A comparação é:

    </p>

    <pre class="codigo-java"><code>30 &gt;= 50</code></pre>

    <p>

        O resultado é false.

    </p>

    <p>

        Por isso, o bloco do if será ignorado.

    </p>

    <div class="resultado-codigo"><code>Nenhuma mensagem será exibida.</code></div>

    <div class="aula-destaque">

        <strong>

            Nesta aula:

        </strong>

        <p>

            Estamos aprendendo apenas o if.

        </p>

        <p>

            Na próxima aula, utilizaremos else
            para criar uma alternativa quando
            a condição for falsa.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| BOOLEAN
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A condição precisa produzir boolean

    </h2>

    <p>

        A expressão dentro dos parênteses precisa
        produzir um valor true ou false.

    </p>

    <pre class="codigo-java"><code>int idade = 20;

if (idade &gt;= 18) {

    System.out.println(
        "Condição verdadeira."
    );

}</code></pre>

    <p>

        A expressão idade &gt;= 18 produz
        um valor booleano.

    </p>

    <p>

        Também podemos utilizar diretamente
        uma variável do tipo boolean.

    </p>

    <pre class="codigo-java"><code>boolean estaLogado = true;

if (estaLogado) {

    System.out.println(
        "Usuário conectado."
    );

}</code></pre>

    <div class="resultado-codigo"><code>Usuário conectado.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| NÃO É NÚMERO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Java não aceita números como condição

    </h2>

    <p>

        Em Java, não podemos colocar apenas
        um número dentro do if.

    </p>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>int idade = 18;

if (idade) {

    System.out.println("Executado.");

}</code></pre>

    <p>

        A variável idade é do tipo int,
        e não boolean.

    </p>

    <p>

        Precisamos criar uma comparação:

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18) {

    System.out.println("Executado.");

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| RELACIONAIS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        if com operadores relacionais

    </h2>

    <p>

        Os operadores relacionais são muito
        utilizados dentro das condições.

    </p>

    <pre class="codigo-java"><code>int numero = 10;

if (numero == 10) {

    System.out.println(
        "O número é igual a 10."
    );

}</code></pre>

    <div class="resultado-codigo"><code>O número é igual a 10.</code></div>

    <p>

        Também podemos verificar diferenças:

    </p>

    <pre class="codigo-java"><code>int numero = 10;

if (numero != 5) {

    System.out.println(
        "O número é diferente de 5."
    );

}</code></pre>

    <div class="resultado-codigo"><code>O número é diferente de 5.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| LÓGICOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        if com operadores lógicos

    </h2>

    <p>

        Podemos utilizar os operadores lógicos
        para combinar condições.

    </p>

    <p>

        Imagine que uma pessoa precise possuir
        idade suficiente e autorização.

    </p>

    <pre class="codigo-java"><code>int idade = 20;
boolean autorizado = true;

if (idade &gt;= 18 &amp;&amp; autorizado) {

    System.out.println(
        "Acesso permitido."
    );

}</code></pre>

    <p>

        As duas condições são verdadeiras.

    </p>

    <div class="resultado-codigo"><code>Acesso permitido.</code></div>

    <p>

        Também podemos utilizar o operador OR.

    </p>

    <pre class="codigo-java"><code>boolean professor = false;
boolean administrador = true;

if (professor || administrador) {

    System.out.println(
        "Área liberada."
    );

}</code></pre>

    <div class="resultado-codigo"><code>Área liberada.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| NOT
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        if com operador NOT

    </h2>

    <p>

        O operador
        <strong>!</strong>
        inverte um valor booleano.

    </p>

    <pre class="codigo-java"><code>boolean estaLogado = false;

if (!estaLogado) {

    System.out.println(
        "Faça login para continuar."
    );

}</code></pre>

    <p>

        A variável estaLogado possui o valor false.

    </p>

    <p>

        Ao utilizar !estaLogado, o resultado
        da condição se torna true.

    </p>

    <div class="resultado-codigo"><code>Faça login para continuar.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| STRING
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        if com textos

    </h2>

    <p>

        Para comparar o conteúdo de Strings,
        utilizamos o método
        <strong>equals()</strong>.

    </p>

    <pre class="codigo-java"><code>String linguagem = "Java";

if (linguagem.equals("Java")) {

    System.out.println(
        "Linguagem encontrada."
    );

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Linguagem encontrada.</code></div>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            Não utilize == como regra geral
            para comparar o conteúdo de Strings.

        </p>

        <p>

            Para comparar textos, utilize equals().

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| IGNORANDO MAIÚSCULAS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Ignorando letras maiúsculas e minúsculas

    </h2>

    <p>

        O método equals() diferencia letras
        maiúsculas e minúsculas.

    </p>

    <pre class="codigo-java"><code>String resposta = "sim";

if (resposta.equals("SIM")) {

    System.out.println("Confirmado.");

}</code></pre>

    <p>

        Nesse exemplo, a condição será falsa.

    </p>

    <p>

        Para ignorar essa diferença, podemos utilizar
        <strong>equalsIgnoreCase()</strong>.

    </p>

    <pre class="codigo-java"><code>String resposta = "sim";

if (resposta.equalsIgnoreCase("SIM")) {

    System.out.println("Confirmado.");

}</code></pre>

    <div class="resultado-codigo"><code>Confirmado.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIAS INSTRUÇÕES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Executando várias instruções

    </h2>

    <p>

        O bloco de um if pode possuir várias
        instruções.

    </p>

    <pre class="codigo-java"><code>double nota = 9.0;

if (nota &gt;= 7) {

    System.out.println(
        "Nota suficiente."
    );

    System.out.println(
        "O estudante foi aprovado."
    );

    System.out.println(
        "Continue estudando!"
    );

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Nota suficiente.
O estudante foi aprovado.
Continue estudando!</code></div>

    <p>

        Todas as instruções que estiverem entre
        as chaves pertencem ao bloco do if.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CHAVES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Sempre utilize chaves

    </h2>

    <p>

        Java permite escrever um if sem chaves
        quando existe apenas uma instrução.

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18)
    System.out.println("Maior de idade.");</code></pre>

    <p>

        Porém, essa escrita pode causar confusão
        quando novas instruções forem adicionadas.

    </p>

    <p>

        Prefira sempre:

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18) {

    System.out.println(
        "Maior de idade."
    );

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Boa prática:

        </strong>

        <p>

            Mesmo que exista apenas uma instrução,
            mantenha as chaves para deixar o bloco
            mais claro e seguro.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        if com Scanner

    </h2>

    <p>

        Podemos receber um valor do usuário
        e utilizá-lo em uma condição.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        System.out.println(
            "Digite sua idade:"
        );

        int idade = entrada.nextInt();

        if (idade &gt;= 18) {

            System.out.println(
                "Você é maior de idade."
            );

        }

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite sua idade:
20
Você é maior de idade.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO PRÁTICO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: aprovação

    </h2>

    <p>

        Vamos verificar se um estudante atingiu
        a nota mínima e a frequência necessária.

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        String nome = "Robert";
        double nota = 8.5;
        double frequencia = 80.0;

        System.out.println(
            "Aluno: " + nome
        );

        if (
            nota &gt;= 7
            &amp;&amp; frequencia &gt;= 75
        ) {

            System.out.println(
                "Situação: aprovado."
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Aluno: Robert
Situação: aprovado.</code></div>

    <p>

        A mensagem de aprovação somente aparece
        porque as duas condições são verdadeiras.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| IF ANINHADO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Um if dentro de outro

    </h2>

    <p>

        Também podemos colocar um if dentro
        de outro if.

    </p>

    <p>

        Isso é chamado de
        <strong>if aninhado</strong>.

    </p>

    <pre class="codigo-java"><code>int idade = 20;
boolean possuiDocumento = true;

if (idade &gt;= 18) {

    if (possuiDocumento) {

        System.out.println(
            "Entrada permitida."
        );

    }

}</code></pre>

    <p>

        O segundo if somente será analisado
        se o primeiro for verdadeiro.

    </p>

    <div class="resultado-codigo"><code>Entrada permitida.</code></div>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            Muitos níveis de if aninhado podem
            deixar o código difícil de compreender.

        </p>

        <p>

            Quando possível, utilize operadores
            lógicos para organizar condições relacionadas.

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

        Esquecer os parênteses

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>if idade &gt;= 18 {

    System.out.println(
        "Maior de idade."
    );

}</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18) {

    System.out.println(
        "Maior de idade."
    );

}</code></pre>

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

    <pre class="codigo-java"><code>if (idade == 18) {

    System.out.println(
        "A idade é 18."
    );

}</code></pre>

    <h3>

        Colocar ponto e vírgula depois do if

    </h3>

    <p>

        Este código possui um problema:

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18); {

    System.out.println(
        "Maior de idade."
    );

}</code></pre>

    <p>

        O ponto e vírgula encerrou o if antes
        da abertura do bloco.

    </p>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18) {

    System.out.println(
        "Maior de idade."
    );

}</code></pre>

    <h3>

        Utilizar um número como condição

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>if (idade) {

    System.out.println("Executado.");

}</code></pre>

    <p>

        Crie uma comparação que produza
        true ou false.

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18) {

    System.out.println("Executado.");

}</code></pre>

    <h3>

        Comparar Strings com ==

    </h3>

    <p>

        Para comparar o conteúdo de uma String,
        utilize equals().

    </p>

    <pre class="codigo-java"><code>if (nome.equals("Robert")) {

    System.out.println(
        "Nome encontrado."
    );

}</code></pre>

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

        Vamos verificar se um número é positivo.

    </p>

    <pre class="codigo-java"><code>int numero = 10;

if (numero &gt; 0) {

    System.out.println(
        "O número é positivo."
    );

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>O número é positivo.</code></div>

    <div class="aula-destaque">

        <strong>

            Experimente:

        </strong>

        <p>

            Troque o valor por zero ou por um número
            negativo e observe que a mensagem
            não será exibida.

        </p>

    </div>

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
            o nome do usuário;
        </li>

        <li>
            sua idade;
        </li>

        <li>
            uma resposta indicando se possui ingresso.
        </li>

    </ul>

    <p>

        Mostre a mensagem
        <strong>"Entrada permitida"</strong>
        somente quando:

    </p>

    <ul>

        <li>
            a idade for maior ou igual a 18;
        </li>

        <li>
            e o usuário possuir ingresso.
        </li>

    </ul>

    <p>

        Um possível resultado seria:

    </p>

    <div class="resultado-codigo"><code>Digite seu nome:
Robert
Digite sua idade:
18
Possui ingresso?
true

Olá, Robert.
Entrada permitida.</code></div>

    <div class="aula-destaque">

        <strong>

            Condição sugerida:

        </strong>

        <p>

            idade &gt;= 18
            <strong>&amp;&amp;</strong>
            possuiIngresso

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
        a estrutura if para criar decisões.

    </p>

    <ul>

        <li>
            if significa se;
        </li>

        <li>
            a condição precisa produzir
            true ou false;
        </li>

        <li>
            o bloco é executado quando
            a condição é verdadeira;
        </li>

        <li>
            o bloco é ignorado quando
            a condição é falsa;
        </li>

        <li>
            operadores relacionais podem
            ser utilizados no if;
        </li>

        <li>
            operadores lógicos permitem
            combinar condições;
        </li>

        <li>
            variáveis boolean podem ser
            utilizadas diretamente;
        </li>

        <li>
            Strings devem ser comparadas
            com equals();
        </li>

        <li>
            as chaves delimitam as instruções
            que pertencem ao if;
        </li>

        <li>
            podemos colocar um if dentro de outro.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                A estrutura if executa um bloco
                somente quando sua condição
                for verdadeira.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como criar
        uma alternativa para quando a condição
        for falsa utilizando if e else.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Um programa começa a tomar decisões
        quando aprende a analisar condições."

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

        Próxima aula: If e Else →

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