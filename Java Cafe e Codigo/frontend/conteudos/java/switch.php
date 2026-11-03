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
                "else_if"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "metodos"
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
    content="Aprenda como utilizar a estrutura switch para trabalhar com diferentes opções em Java."
>

<title>
    Switch em Java | Java, Café e Código
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

        AULA 11

    </span>

    <h1>

        Estrutura Switch em Java

    </h1>

    <p>

        Aprenda como comparar uma opção com vários
        valores possíveis e executar um bloco diferente
        para cada escolha.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Trabalhando com opções específicas

    </h2>

    <p>

        Nas aulas anteriores, aprendemos como utilizar
        if, else e else if para criar decisões.

    </p>

    <p>

        Essas estruturas podem analisar comparações,
        intervalos e condições mais complexas.

    </p>

    <pre class="codigo-java"><code>int opcao = 2;

if (opcao == 1) {

    System.out.println("Iniciar");

} else if (opcao == 2) {

    System.out.println("Configurações");

} else if (opcao == 3) {

    System.out.println("Sair");

} else {

    System.out.println("Opção inválida");

}</code></pre>

    <p>

        Esse código funciona, mas existe outra estrutura
        que pode deixar situações como essa mais organizadas.

    </p>

    <p>

        Quando precisamos comparar um valor com várias
        opções específicas, podemos utilizar
        <strong>switch</strong>.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            O switch recebe um valor e procura um caso
            que corresponda exatamente a ele.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE É SWITCH
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é a estrutura switch?

    </h2>

    <p>

        A estrutura <strong>switch</strong> é utilizada
        para escolher entre diferentes opções.

    </p>

    <p>

        Podemos imaginá-la como um menu.

    </p>

    <div class="aula-destaque">

        <strong>

            Exemplo de menu:

        </strong>

        <p>

            Opção 1: iniciar o programa.

        </p>

        <p>

            Opção 2: abrir as configurações.

        </p>

        <p>

            Opção 3: encerrar o programa.

        </p>

    </div>

    <p>

        O programa analisa a opção escolhida e executa
        o código correspondente.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ESTRUTURA BÁSICA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Estrutura básica do switch

    </h2>

    <pre class="codigo-java"><code>switch (valor) {

    case opcao1:

        // Código da primeira opção
        break;

    case opcao2:

        // Código da segunda opção
        break;

    default:

        // Executado quando nenhuma opção corresponde

}</code></pre>

    <p>

        A estrutura possui algumas partes importantes.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Função</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>switch</span>
            <span>Inicia a estrutura</span>
            <span>switch (opcao)</span>

        </div>

        <div class="aula-tabela-linha">

            <span>case</span>
            <span>Representa uma opção possível</span>
            <span>case 1:</span>

        </div>

        <div class="aula-tabela-linha">

            <span>break</span>
            <span>Encerra a execução do switch</span>
            <span>break;</span>

        </div>

        <div class="aula-tabela-linha">

            <span>default</span>
            <span>Trata valores não encontrados</span>
            <span>default:</span>

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

        Primeiro exemplo

    </h2>

    <p>

        Vamos analisar um número armazenado
        em uma variável.

    </p>

    <pre class="codigo-java"><code>int numero = 2;

switch (numero) {

    case 1:

        System.out.println(
            "Você escolheu o número 1."
        );

        break;

    case 2:

        System.out.println(
            "Você escolheu o número 2."
        );

        break;

    case 3:

        System.out.println(
            "Você escolheu o número 3."
        );

        break;

    default:

        System.out.println(
            "Número inválido."
        );

}</code></pre>

    <p>

        A variável possui o valor 2.

    </p>

    <p>

        O Java procura um case que corresponda
        exatamente a esse valor.

    </p>

    <p>

        Quando encontra o case 2, executa
        seu bloco.

    </p>

    <div class="resultado-codigo"><code>Você escolheu o número 2.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| FLUXO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como o switch funciona?

    </h2>

    <div class="aula-destaque">

        <p>O programa encontra o switch.</p>

        <p>↓</p>

        <p>O valor entre os parênteses é analisado.</p>

        <p>↓</p>

        <p>O Java procura um case correspondente.</p>

        <p>↓</p>

        <p>O bloco do case encontrado é executado.</p>

        <p>↓</p>

        <p>O break encerra a estrutura.</p>

        <p>↓</p>

        <p>
            Se nenhum case corresponder,
            o default poderá ser executado.
        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CASE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Entendendo o case

    </h2>

    <p>

        Cada <strong>case</strong> representa
        um valor possível.

    </p>

    <pre class="codigo-java"><code>case 1:

    System.out.println("Primeira opção");
    break;</code></pre>

    <p>

        O código desse case será executado
        quando o valor analisado pelo switch
        for igual a 1.

    </p>

    <p>

        Observe que utilizamos dois-pontos
        depois do valor.

    </p>

    <pre class="codigo-java"><code>case 1:</code></pre>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            No case tradicional, utilizamos
            dois-pontos, e não ponto e vírgula.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| BREAK
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Para que serve o break?

    </h2>

    <p>

        A palavra-chave <strong>break</strong>
        encerra a execução do switch.

    </p>

    <pre class="codigo-java"><code>int numero = 1;

switch (numero) {

    case 1:

        System.out.println("Um");
        break;

    case 2:

        System.out.println("Dois");
        break;

    case 3:

        System.out.println("Três");
        break;

}</code></pre>

    <p>

        Como o valor é 1, o primeiro caso
        será executado.

    </p>

    <div class="resultado-codigo"><code>Um</code></div>

    <p>

        Depois disso, o break encerra a estrutura.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| SEM BREAK
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que acontece sem o break?

    </h2>

    <p>

        No switch tradicional, quando não utilizamos
        break, o programa pode continuar executando
        os casos seguintes.

    </p>

    <pre class="codigo-java"><code>int numero = 1;

switch (numero) {

    case 1:

        System.out.println("Um");

    case 2:

        System.out.println("Dois");

    case 3:

        System.out.println("Três");

}</code></pre>

    <p>

        O programa encontra o case 1 e começa
        sua execução.

    </p>

    <p>

        Como não existe break, ele continua
        pelos casos seguintes.

    </p>

    <div class="resultado-codigo"><code>Um
Dois
Três</code></div>

    <div class="aula-destaque">

        <strong>

            Esse comportamento é chamado de fall-through.

        </strong>

        <p>

            Quando não queremos executar os próximos
            casos, precisamos utilizar break.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CASOS AGRUPADOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Agrupando casos

    </h2>

    <p>

        Em algumas situações, vários valores
        devem produzir o mesmo resultado.

    </p>

    <p>

        Podemos agrupar cases deixando o break
        apenas no último deles.

    </p>

    <pre class="codigo-java"><code>int dia = 6;

switch (dia) {

    case 1:
    case 2:
    case 3:
    case 4:
    case 5:

        System.out.println(
            "Dia útil."
        );

        break;

    case 6:
    case 7:

        System.out.println(
            "Fim de semana."
        );

        break;

    default:

        System.out.println(
            "Dia inválido."
        );

}</code></pre>

    <p>

        Como o valor é 6, o programa executará:

    </p>

    <div class="resultado-codigo"><code>Fim de semana.</code></div>

    <div class="aula-destaque">

        <strong>

            Nesse caso:

        </strong>

        <p>

            A ausência de break entre os cases
            foi utilizada de forma intencional
            para agrupá-los.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Para que serve o default?

    </h2>

    <p>

        O bloco <strong>default</strong> é executado
        quando nenhum case corresponde ao valor.

    </p>

    <pre class="codigo-java"><code>int numero = 10;

switch (numero) {

    case 1:

        System.out.println("Número 1");
        break;

    case 2:

        System.out.println("Número 2");
        break;

    case 3:

        System.out.println("Número 3");
        break;

    default:

        System.out.println(
            "Número não encontrado."
        );

}</code></pre>

    <p>

        Como não existe case 10, o programa
        executará o default.

    </p>

    <div class="resultado-codigo"><code>Número não encontrado.</code></div>

    <div class="aula-destaque">

        <strong>

            O default é opcional.

        </strong>

        <p>

            Porém, ele é útil para tratar valores
            inesperados ou opções inválidas.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SWITCH COM CHAR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Switch com char

    </h2>

    <p>

        Também podemos utilizar switch
        com valores do tipo char.

    </p>

    <pre class="codigo-java"><code>char categoria = 'B';

switch (categoria) {

    case 'A':

        System.out.println(
            "Categoria excelente."
        );

        break;

    case 'B':

        System.out.println(
            "Categoria boa."
        );

        break;

    case 'C':

        System.out.println(
            "Categoria regular."
        );

        break;

    default:

        System.out.println(
            "Categoria inválida."
        );

}</code></pre>

    <p>

        Como a categoria possui o caractere 'B',
        o segundo case será executado.

    </p>

    <div class="resultado-codigo"><code>Categoria boa.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| SWITCH COM STRING
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Switch com String

    </h2>

    <p>

        O switch também pode analisar textos
        do tipo String.

    </p>

    <pre class="codigo-java"><code>String fruta = "banana";

switch (fruta) {

    case "maçã":

        System.out.println(
            "Você escolheu uma maçã."
        );

        break;

    case "banana":

        System.out.println(
            "Você escolheu uma banana."
        );

        break;

    case "laranja":

        System.out.println(
            "Você escolheu uma laranja."
        );

        break;

    default:

        System.out.println(
            "Fruta não encontrada."
        );

}</code></pre>

    <p>

        Como o texto armazenado é "banana",
        o segundo caso será executado.

    </p>

    <div class="resultado-codigo"><code>Você escolheu uma banana.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| MAIÚSCULAS E MINÚSCULAS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Maiúsculas e minúsculas fazem diferença

    </h2>

    <p>

        Ao utilizar String no switch, o texto
        precisa corresponder ao valor do case.

    </p>

    <pre class="codigo-java"><code>String comando = "INICIAR";

switch (comando) {

    case "iniciar":

        System.out.println(
            "Programa iniciado."
        );

        break;

    default:

        System.out.println(
            "Comando inválido."
        );

}</code></pre>

    <p>

        Os textos "INICIAR" e "iniciar"
        possuem letras diferentes.

    </p>

    <div class="resultado-codigo"><code>Comando inválido.</code></div>

    <p>

        Podemos transformar o texto antes
        de utilizá-lo no switch.

    </p>

    <pre class="codigo-java"><code>String comando = "INICIAR";

switch (comando.toLowerCase()) {

    case "iniciar":

        System.out.println(
            "Programa iniciado."
        );

        break;

    default:

        System.out.println(
            "Comando inválido."
        );

}</code></pre>

    <div class="resultado-codigo"><code>Programa iniciado.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| MENU
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando um menu

    </h2>

    <p>

        Uma utilização comum do switch
        é a criação de menus.

    </p>

    <pre class="codigo-java"><code>int opcao = 2;

System.out.println("=== MENU ===");
System.out.println("1 - Iniciar");
System.out.println("2 - Configurações");
System.out.println("3 - Sair");

switch (opcao) {

    case 1:

        System.out.println(
            "Programa iniciado."
        );

        break;

    case 2:

        System.out.println(
            "Abrindo configurações."
        );

        break;

    case 3:

        System.out.println(
            "Encerrando programa."
        );

        break;

    default:

        System.out.println(
            "Opção inválida."
        );

}</code></pre>

    <p>

        Como a opção é 2, o resultado será:

    </p>

    <div class="resultado-codigo"><code>=== MENU ===
1 - Iniciar
2 - Configurações
3 - Sair
Abrindo configurações.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Menu com Scanner

    </h2>

    <p>

        Podemos receber a escolha do usuário
        utilizando Scanner.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        System.out.println("=== MENU ===");
        System.out.println("1 - Iniciar");
        System.out.println("2 - Ajuda");
        System.out.println("3 - Sair");

        System.out.println();
        System.out.println(
            "Escolha uma opção:"
        );

        int opcao = entrada.nextInt();

        switch (opcao) {

            case 1:

                System.out.println(
                    "Iniciando programa..."
                );

                break;

            case 2:

                System.out.println(
                    "Abrindo ajuda..."
                );

                break;

            case 3:

                System.out.println(
                    "Encerrando programa..."
                );

                break;

            default:

                System.out.println(
                    "Opção inválida."
                );

        }

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>=== MENU ===
1 - Iniciar
2 - Ajuda
3 - Sair

Escolha uma opção:
2
Abrindo ajuda...</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| SWITCH OU IF
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Switch ou if e else?

    </h2>

    <p>

        As duas estruturas permitem criar decisões,
        mas são mais adequadas para situações diferentes.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Estrutura</span>
            <span>Melhor utilização</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>if / else</span>
            <span>Comparações, intervalos e condições lógicas</span>
            <span>idade &gt;= 18</span>

        </div>

        <div class="aula-tabela-linha">

            <span>switch</span>
            <span>Valores e opções específicas</span>
            <span>opção 1, 2 ou 3</span>

        </div>

    </div>

    <p>

        Para verificar uma faixa de idade,
        if e else costuma ser mais adequado.

    </p>

    <pre class="codigo-java"><code>if (idade &lt; 12) {

    System.out.println("Criança");

} else if (idade &lt; 18) {

    System.out.println("Adolescente");

} else {

    System.out.println("Adulto");

}</code></pre>

    <p>

        Para verificar opções exatas de um menu,
        switch pode deixar o código mais claro.

    </p>

    <div class="aula-destaque">

        <strong>

            Resumindo:

        </strong>

        <p>

            Utilize switch quando estiver comparando
            uma expressão com opções específicas.

        </p>

        <p>

            Utilize if e else quando precisar analisar
            intervalos ou condições mais complexas.

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

        Esquecer os dois-pontos

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>case 1

    System.out.println("Opção 1");
    break;</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>case 1:

    System.out.println("Opção 1");
    break;</code></pre>

    <h3>

        Esquecer o break sem intenção

    </h3>

    <p>

        Sem break, o programa pode continuar
        pelos casos seguintes.

    </p>

    <pre class="codigo-java"><code>case 1:

    System.out.println("Opção 1");
    break;</code></pre>

    <h3>

        Repetir o mesmo case

    </h3>

    <p>

        Dois cases não devem possuir o mesmo valor.

    </p>

    <pre class="codigo-java"><code>case 1:

    System.out.println("Primeira ação");
    break;

case 1:

    System.out.println("Segunda ação");
    break;</code></pre>

    <p>

        Cada valor deve representar um caso único.

    </p>

    <h3>

        Utilizar uma comparação dentro do case

    </h3>

    <p>

        No switch tradicional, o case representa
        um valor específico.

    </p>

    <p>

        Esta ideia não deve ser escrita assim:

    </p>

    <pre class="codigo-java"><code>case idade &gt;= 18:</code></pre>

    <p>

        Para analisar comparações ou intervalos,
        utilize if e else.

    </p>

    <h3>

        Esquecer o default

    </h3>

    <p>

        O default não é obrigatório, mas pode ajudar
        a tratar opções inválidas.

    </p>

    <pre class="codigo-java"><code>default:

    System.out.println(
        "Opção inválida."
    );</code></pre>

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

        Vamos transformar um número em um dia da semana.

    </p>

    <pre class="codigo-java"><code>int dia = 3;

switch (dia) {

    case 1:

        System.out.println("Domingo");
        break;

    case 2:

        System.out.println("Segunda-feira");
        break;

    case 3:

        System.out.println("Terça-feira");
        break;

    case 4:

        System.out.println("Quarta-feira");
        break;

    case 5:

        System.out.println("Quinta-feira");
        break;

    case 6:

        System.out.println("Sexta-feira");
        break;

    case 7:

        System.out.println("Sábado");
        break;

    default:

        System.out.println(
            "Dia inválido."
        );

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Terça-feira</code></div>

    <div class="aula-destaque">

        <strong>

            Experimente:

        </strong>

        <p>

            Altere o valor para 1, 5, 7 e 10
            e observe cada resultado.

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

        Crie um programa que apresente o seguinte menu:

    </p>

    <ul>

        <li>1 — Ver perfil;</li>

        <li>2 — Abrir conteúdos;</li>

        <li>3 — Abrir caderno;</li>

        <li>4 — Sair.</li>

    </ul>

    <p>

        Receba uma opção utilizando Scanner
        e execute uma mensagem diferente
        para cada escolha.

    </p>

    <div class="resultado-codigo"><code>=== JAVA, CAFÉ E CÓDIGO ===
1 - Ver perfil
2 - Abrir conteúdos
3 - Abrir caderno
4 - Sair

Escolha uma opção:
2

Abrindo os conteúdos de Java...</code></div>

    <div class="aula-destaque">

        <strong>

            Não se esqueça:

        </strong>

        <p>

            Adicione um default para tratar
            opções que não existem no menu.

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

        Nesta aula, aprendemos como trabalhar
        com opções específicas utilizando switch.

    </p>

    <ul>

        <li>
            switch analisa um valor;
        </li>

        <li>
            case representa uma opção possível;
        </li>

        <li>
            break encerra a execução da estrutura;
        </li>

        <li>
            sem break, os próximos casos podem
            continuar sendo executados;
        </li>

        <li>
            vários cases podem compartilhar
            o mesmo bloco;
        </li>

        <li>
            default trata valores não encontrados;
        </li>

        <li>
            switch pode ser utilizado com números,
            caracteres e textos;
        </li>

        <li>
            switch é útil para menus e opções específicas;
        </li>

        <li>
            if e else são mais adequados para comparações,
            intervalos e condições complexas.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Switch permite selecionar um caminho
                de acordo com um valor específico.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como dividir
        o programa em tarefas menores utilizando métodos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Escolher a estrutura certa torna o código
        mais claro, organizado e fácil de entender."

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

        Próxima aula: Métodos →

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