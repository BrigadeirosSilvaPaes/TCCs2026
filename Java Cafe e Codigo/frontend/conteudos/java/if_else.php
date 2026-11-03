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
                "if"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "else_if"
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
    content="Aprenda como utilizar if e else para criar dois caminhos diferentes em programas Java."
>

<title>
    If e Else em Java | Java, Café e Código
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

        AULA 09

    </span>

    <h1>

        If e Else em Java

    </h1>

    <p>

        Aprenda como criar dois caminhos diferentes
        e fazer o programa escolher qual deles deverá
        ser executado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando uma alternativa

    </h2>

    <p>

        Na aula anterior, aprendemos que a estrutura
        <strong>if</strong> executa um bloco de código
        somente quando uma condição é verdadeira.

    </p>

    <pre class="codigo-java"><code>int idade = 20;

if (idade &gt;= 18) {

    System.out.println(
        "Você é maior de idade."
    );

}</code></pre>

    <p>

        Porém, quando a condição é falsa, o bloco
        do if simplesmente é ignorado.

    </p>

    <p>

        Em muitos programas, precisamos executar
        uma ação diferente quando a condição não
        for atendida.

    </p>

    <p>

        Para criar essa segunda possibilidade,
        utilizamos a palavra
        <strong>else</strong>.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            O if representa o caminho executado
            quando a condição é verdadeira.

        </p>

        <p>

            O else representa o caminho executado
            quando a condição é falsa.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE É ELSE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que significa else?

    </h2>

    <p>

        A palavra <strong>else</strong> pode ser
        entendida como:

    </p>

    <div class="aula-destaque">

        <p>

            Caso contrário.

        </p>

    </div>

    <p>

        Podemos pensar em uma regra do cotidiano:

    </p>

    <div class="aula-destaque">

        <strong>

            Se estiver chovendo:

        </strong>

        <p>

            Levar um guarda-chuva.

        </p>

        <strong>

            Caso contrário:

        </strong>

        <p>

            Não levar o guarda-chuva.

        </p>

    </div>

    <p>

        Em Java, utilizamos if e else para representar
        esses dois possíveis caminhos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ESTRUTURA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Estrutura básica do if e else

    </h2>

    <pre class="codigo-java"><code>if (condicao) {

    // Executado quando a condição for verdadeira

} else {

    // Executado quando a condição for falsa

}</code></pre>

    <p>

        A estrutura possui duas partes principais.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Quando é executada</span>
            <span>Quantidade</span>

        </div>

        <div class="aula-tabela-linha">

            <span>if</span>
            <span>Quando a condição é verdadeira</span>
            <span>Um bloco</span>

        </div>

        <div class="aula-tabela-linha">

            <span>else</span>
            <span>Quando a condição é falsa</span>
            <span>Um bloco</span>

        </div>

    </div>

    <div class="aula-destaque">

        <strong>

            Importante:

        </strong>

        <p>

            Em uma estrutura if e else, apenas
            um dos dois blocos será executado.

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

        Primeiro exemplo

    </h2>

    <p>

        Vamos verificar se uma pessoa é maior
        ou menor de idade.

    </p>

    <pre class="codigo-java"><code>int idade = 17;

if (idade &gt;= 18) {

    System.out.println(
        "Você é maior de idade."
    );

} else {

    System.out.println(
        "Você é menor de idade."
    );

}</code></pre>

    <p>

        A condição analisada será:

    </p>

    <pre class="codigo-java"><code>idade &gt;= 18</code></pre>

    <p>

        Como a idade é 17, a condição produz
        o resultado false.

    </p>

    <p>

        Por isso, o bloco do if será ignorado
        e o bloco do else será executado.

    </p>

    <div class="resultado-codigo"><code>Você é menor de idade.</code></div>

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

    <p>

        Agora vamos utilizar o valor 20.

    </p>

    <pre class="codigo-java"><code>int idade = 20;

if (idade &gt;= 18) {

    System.out.println(
        "Você é maior de idade."
    );

} else {

    System.out.println(
        "Você é menor de idade."
    );

}</code></pre>

    <p>

        A condição:

    </p>

    <pre class="codigo-java"><code>20 &gt;= 18</code></pre>

    <p>

        produz true.

    </p>

    <p>

        O bloco do if será executado e o else
        será ignorado.

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

        Como funciona o fluxo?

    </h2>

    <div class="aula-destaque">

        <p>O programa encontra o if.</p>

        <p>↓</p>

        <p>A condição é analisada.</p>

        <p>↓</p>

        <p>
            Se for true, o bloco do if é executado.
        </p>

        <p>↓</p>

        <p>
            Se for false, o bloco do else é executado.
        </p>

        <p>↓</p>

        <p>
            O programa continua pelas próximas instruções.
        </p>

    </div>

    <p>

        Os dois blocos nunca serão executados
        ao mesmo tempo dentro da mesma decisão.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| IF SOZINHO OU IF ELSE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        If sozinho ou if e else?

    </h2>

    <p>

        Utilize apenas if quando nenhuma ação
        for necessária caso a condição seja falsa.

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18) {

    System.out.println(
        "Conteúdo liberado."
    );

}</code></pre>

    <p>

        Nesse exemplo, nenhuma mensagem será apresentada
        quando a idade for menor que 18.

    </p>

    <p>

        Utilize if e else quando o programa precisar
        escolher entre duas possibilidades.

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18) {

    System.out.println(
        "Conteúdo liberado."
    );

} else {

    System.out.println(
        "Conteúdo bloqueado."
    );

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Diferença:

        </strong>

        <p>

            If sozinho cria um caminho opcional.

        </p>

        <p>

            If e else criam dois caminhos possíveis.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| NOTA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo com nota

    </h2>

    <p>

        Vamos verificar se um estudante foi aprovado.

    </p>

    <pre class="codigo-java"><code>double nota = 7.5;

if (nota &gt;= 6) {

    System.out.println(
        "Aluno aprovado."
    );

} else {

    System.out.println(
        "Aluno reprovado."
    );

}</code></pre>

    <p>

        A nota 7.5 é maior ou igual a 6.

    </p>

    <p>

        Portanto, o resultado será:

    </p>

    <div class="resultado-codigo"><code>Aluno aprovado.</code></div>

    <p>

        Agora observe uma nota menor:

    </p>

    <pre class="codigo-java"><code>double nota = 4.5;

if (nota &gt;= 6) {

    System.out.println(
        "Aluno aprovado."
    );

} else {

    System.out.println(
        "Aluno reprovado."
    );

}</code></pre>

    <div class="resultado-codigo"><code>Aluno reprovado.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| POSITIVO OU NÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Número positivo ou não positivo

    </h2>

    <pre class="codigo-java"><code>int numero = -5;

if (numero &gt; 0) {

    System.out.println(
        "O número é positivo."
    );

} else {

    System.out.println(
        "O número não é positivo."
    );

}</code></pre>

    <p>

        Como -5 não é maior que zero,
        o bloco else será executado.

    </p>

    <div class="resultado-codigo"><code>O número não é positivo.</code></div>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            O else desse exemplo reúne dois casos:
            número negativo e número igual a zero.

        </p>

        <p>

            Na próxima aula, utilizaremos else if
            para separar mais possibilidades.

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

        If e else com boolean

    </h2>

    <p>

        Uma variável boolean pode ser utilizada
        diretamente como condição.

    </p>

    <pre class="codigo-java"><code>boolean estaLogado = true;

if (estaLogado) {

    System.out.println(
        "Usuário conectado."
    );

} else {

    System.out.println(
        "Usuário não conectado."
    );

}</code></pre>

    <p>

        Como estaLogado possui o valor true,
        o bloco do if será executado.

    </p>

    <div class="resultado-codigo"><code>Usuário conectado.</code></div>

    <p>

        Se o valor fosse false, o programa mostraria:

    </p>

    <div class="resultado-codigo"><code>Usuário não conectado.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| NOT
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando o operador NOT

    </h2>

    <p>

        Também podemos inverter uma variável
        booleana com o operador
        <strong>!</strong>.

    </p>

    <pre class="codigo-java"><code>boolean contaBloqueada = false;

if (!contaBloqueada) {

    System.out.println(
        "Conta disponível."
    );

} else {

    System.out.println(
        "Conta bloqueada."
    );

}</code></pre>

    <p>

        Como contaBloqueada é false,
        !contaBloqueada produz true.

    </p>

    <div class="resultado-codigo"><code>Conta disponível.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| OPERADORES LÓGICOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        If e else com operadores lógicos

    </h2>

    <p>

        Podemos combinar várias condições
        dentro do if.

    </p>

    <pre class="codigo-java"><code>int idade = 20;
boolean possuiIngresso = true;

if (idade &gt;= 18 &amp;&amp; possuiIngresso) {

    System.out.println(
        "Entrada permitida."
    );

} else {

    System.out.println(
        "Entrada negada."
    );

}</code></pre>

    <p>

        Para a entrada ser permitida, as duas condições
        precisam ser verdadeiras.

    </p>

    <div class="resultado-codigo"><code>Entrada permitida.</code></div>

    <p>

        Se a pessoa não possuir ingresso,
        o bloco else será executado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| STRING
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        If e else com textos

    </h2>

    <p>

        Para comparar o conteúdo de Strings,
        utilizamos o método
        <strong>equals()</strong>.

    </p>

    <pre class="codigo-java"><code>String senha = "1234";

if (senha.equals("1234")) {

    System.out.println(
        "Senha correta."
    );

} else {

    System.out.println(
        "Senha incorreta."
    );

}</code></pre>

    <p>

        Como o conteúdo da variável é igual
        ao texto informado, o resultado será:

    </p>

    <div class="resultado-codigo"><code>Senha correta.</code></div>

    <div class="aula-destaque">

        <strong>

            Lembre-se:

        </strong>

        <p>

            Utilize equals() para comparar
            o conteúdo de Strings.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EQUALS IGNORE CASE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Ignorando maiúsculas e minúsculas

    </h2>

    <p>

        Podemos utilizar
        <strong>equalsIgnoreCase()</strong>
        quando não queremos diferenciar letras
        maiúsculas e minúsculas.

    </p>

    <pre class="codigo-java"><code>String resposta = "sim";

if (resposta.equalsIgnoreCase("SIM")) {

    System.out.println(
        "Resposta confirmada."
    );

} else {

    System.out.println(
        "Resposta não confirmada."
    );

}</code></pre>

    <p>

        Os textos "sim" e "SIM" serão considerados
        equivalentes nesse método.

    </p>

    <div class="resultado-codigo"><code>Resposta confirmada.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        If e else com Scanner

    </h2>

    <p>

        Podemos receber um valor do usuário
        e utilizar esse valor em uma decisão.

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

        } else {

            System.out.println(
                "Você é menor de idade."
            );

        }

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite sua idade:
17
Você é menor de idade.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO COMPLETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: acesso ao sistema

    </h2>

    <p>

        Vamos criar um programa que recebe
        um nome e uma idade.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        System.out.println(
            "Digite seu nome:"
        );

        String nome = entrada.nextLine();

        System.out.println(
            "Digite sua idade:"
        );

        int idade = entrada.nextInt();

        System.out.println();
        System.out.println(
            "Olá, " + nome + "!"
        );

        if (idade &gt;= 18) {

            System.out.println(
                "Acesso permitido."
            );

        } else {

            System.out.println(
                "Acesso negado."
            );

        }

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite seu nome:
Robert
Digite sua idade:
17

Olá, Robert!
Acesso negado.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIAS INSTRUÇÕES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Várias instruções em cada caminho

    </h2>

    <p>

        Os blocos if e else podem possuir
        várias instruções.

    </p>

    <pre class="codigo-java"><code>double nota = 8.0;

if (nota &gt;= 6) {

    System.out.println(
        "Aluno aprovado."
    );

    System.out.println(
        "Parabéns pelo resultado!"
    );

} else {

    System.out.println(
        "Aluno reprovado."
    );

    System.out.println(
        "Continue estudando."
    );

}</code></pre>

    <p>

        Como a nota é suficiente, as duas instruções
        do bloco if serão executadas.

    </p>

    <div class="resultado-codigo"><code>Aluno aprovado.
Parabéns pelo resultado!</code></div>

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

        Colocar uma condição no else

    </h3>

    <p>

        O else sozinho não recebe condição.

    </p>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>else (idade &lt; 18) {

    System.out.println(
        "Menor de idade."
    );

}</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>else {

    System.out.println(
        "Menor de idade."
    );

}</code></pre>

    <h3>

        Utilizar else sem um if

    </h3>

    <p>

        Um else precisa estar relacionado
        a um if anterior.

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18) {

    System.out.println(
        "Maior de idade."
    );

} else {

    System.out.println(
        "Menor de idade."
    );

}</code></pre>

    <h3>

        Colocar ponto e vírgula depois do if

    </h3>

    <p>

        Este código possui um erro:

    </p>

    <pre class="codigo-java"><code>if (idade &gt;= 18); {

    System.out.println(
        "Maior de idade."
    );

} else {

    System.out.println(
        "Menor de idade."
    );

}</code></pre>

    <p>

        Não coloque ponto e vírgula após a condição.

    </p>

    <h3>

        Confundir = com ==

    </h3>

    <p>

        O operador = atribui um valor.

    </p>

    <pre class="codigo-java"><code>int idade = 18;</code></pre>

    <p>

        O operador == realiza uma comparação.

    </p>

    <pre class="codigo-java"><code>if (idade == 18) {

    System.out.println(
        "A idade é 18."
    );

}</code></pre>

    <h3>

        Comparar Strings com ==

    </h3>

    <p>

        Para comparar o conteúdo de textos,
        utilize equals().

    </p>

    <pre class="codigo-java"><code>if (senha.equals("1234")) {

    System.out.println(
        "Senha correta."
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

        Vamos verificar se um número é par ou ímpar.

    </p>

    <pre class="codigo-java"><code>int numero = 10;

if (numero % 2 == 0) {

    System.out.println(
        "O número é par."
    );

} else {

    System.out.println(
        "O número é ímpar."
    );

}</code></pre>

    <p>

        O resto da divisão de 10 por 2 é zero.

    </p>

    <div class="resultado-codigo"><code>O número é par.</code></div>

    <div class="aula-destaque">

        <strong>

            Experimente:

        </strong>

        <p>

            Troque o valor de numero por 7
            e execute novamente.

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
            o nome de um estudante;
        </li>

        <li>
            sua nota;
        </li>

        <li>
            sua frequência.
        </li>

    </ul>

    <p>

        O estudante será aprovado quando:

    </p>

    <ul>

        <li>
            a nota for maior ou igual a 7;
        </li>

        <li>
            e a frequência for maior ou igual a 75.
        </li>

    </ul>

    <p>

        Caso contrário, deverá ser reprovado.

    </p>

    <div class="resultado-codigo"><code>Digite o nome:
Robert
Digite a nota:
8.5
Digite a frequência:
80

Aluno: Robert
Situação: aprovado.</code></div>

    <div class="aula-destaque">

        <strong>

            Condição sugerida:

        </strong>

        <p>

            nota &gt;= 7
            <strong>&amp;&amp;</strong>
            frequencia &gt;= 75

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

        Nesta aula, aprendemos como criar
        dois caminhos dentro de um programa.

    </p>

    <ul>

        <li>
            o if verifica uma condição;
        </li>

        <li>
            o bloco do if é executado
            quando a condição é verdadeira;
        </li>

        <li>
            else significa caso contrário;
        </li>

        <li>
            o bloco do else é executado
            quando a condição é falsa;
        </li>

        <li>
            apenas um dos dois blocos
            será executado;
        </li>

        <li>
            operadores relacionais podem
            ser utilizados nas condições;
        </li>

        <li>
            operadores lógicos permitem
            combinar regras;
        </li>

        <li>
            variáveis boolean podem ser
            utilizadas diretamente;
        </li>

        <li>
            Strings devem ser comparadas
            utilizando equals().
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                If e else permitem que o programa
                escolha entre dois caminhos
                diferentes.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como trabalhar
        com três ou mais possibilidades utilizando
        else if.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Cada condição cria uma escolha,
        e cada escolha define um caminho."

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

        Próxima aula: Else If →

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