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
                "if_else"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "switch"
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
    content="Aprenda como utilizar else if para trabalhar com várias condições em Java."
>

<title>
    Else If em Java | Java, Café e Código
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

        AULA 10

    </span>

    <h1>

        Else If em Java

    </h1>

    <p>

        Aprenda como analisar várias condições
        e fazer o programa escolher entre três
        ou mais possibilidades.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Mais de dois caminhos

    </h2>

    <p>

        Na aula anterior, aprendemos que if e else
        permitem criar dois caminhos.

    </p>

    <pre class="codigo-java"><code>if (nota &gt;= 6) {

    System.out.println(
        "Aluno aprovado."
    );

} else {

    System.out.println(
        "Aluno reprovado."
    );

}</code></pre>

    <p>

        Porém, alguns problemas possuem mais
        de duas possibilidades.

    </p>

    <p>

        Podemos querer classificar uma nota como:

    </p>

    <ul>

        <li>
            excelente;
        </li>

        <li>
            boa;
        </li>

        <li>
            suficiente;
        </li>

        <li>
            insuficiente.
        </li>

    </ul>

    <p>

        Para verificar várias condições em sequência,
        utilizamos <strong>else if</strong>.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            Else if adiciona novas condições
            entre o if inicial e o else final.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE É ELSE IF
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que significa else if?

    </h2>

    <p>

        A expressão <strong>else if</strong>
        pode ser entendida como:

    </p>

    <div class="aula-destaque">

        <p>

            Caso contrário, se...

        </p>

    </div>

    <p>

        Primeiro, o programa verifica a condição
        do if.

    </p>

    <p>

        Se ela for falsa, o programa pode verificar
        uma nova condição utilizando else if.

    </p>

    <p>

        Se nenhuma condição for verdadeira,
        o bloco else poderá ser executado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ESTRUTURA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Estrutura básica

    </h2>

    <pre class="codigo-java"><code>if (condicao1) {

    // Executado se condição 1 for verdadeira

} else if (condicao2) {

    // Executado se condição 2 for verdadeira

} else {

    // Executado se nenhuma condição for verdadeira

}</code></pre>

    <p>

        Uma estrutura pode possuir vários
        blocos else if.

    </p>

    <pre class="codigo-java"><code>if (condicao1) {

    // Primeiro caminho

} else if (condicao2) {

    // Segundo caminho

} else if (condicao3) {

    // Terceiro caminho

} else {

    // Caminho padrão

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Importante:

        </strong>

        <p>

            Mesmo que várias condições sejam verdadeiras,
            somente o primeiro bloco verdadeiro será
            executado.

        </p>

    </div>

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

        <p>

            O programa verifica o if.

        </p>

        <p>

            ↓

        </p>

        <p>

            Se for verdadeiro, executa o bloco
            e encerra a estrutura.

        </p>

        <p>

            ↓

        </p>

        <p>

            Se for falso, verifica o primeiro else if.

        </p>

        <p>

            ↓

        </p>

        <p>

            Continua até encontrar uma condição verdadeira.

        </p>

        <p>

            ↓

        </p>

        <p>

            Se nenhuma condição for verdadeira,
            executa o else.

        </p>

    </div>

    <p>

        As condições são analisadas de cima para baixo.

    </p>

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

        Vamos classificar uma pessoa de acordo
        com sua idade.

    </p>

    <pre class="codigo-java"><code>int idade = 15;

if (idade &gt;= 18) {

    System.out.println(
        "Você é adulto."
    );

} else if (idade &gt;= 13) {

    System.out.println(
        "Você é adolescente."
    );

} else {

    System.out.println(
        "Você é criança."
    );

}</code></pre>

    <p>

        A primeira condição verifica:

    </p>

    <pre class="codigo-java"><code>15 &gt;= 18</code></pre>

    <p>

        O resultado é false.

    </p>

    <p>

        Depois, o programa verifica:

    </p>

    <pre class="codigo-java"><code>15 &gt;= 13</code></pre>

    <p>

        O resultado é true.

    </p>

    <div class="resultado-codigo"><code>Você é adolescente.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| PRIMEIRA VERDADEIRA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A primeira condição verdadeira vence

    </h2>

    <p>

        Quando o programa encontra uma condição
        verdadeira, executa seu bloco e ignora
        todas as condições seguintes.

    </p>

    <pre class="codigo-java"><code>int numero = 15;

if (numero &gt; 0) {

    System.out.println(
        "O número é positivo."
    );

} else if (numero &gt; 10) {

    System.out.println(
        "O número é maior que 10."
    );

}</code></pre>

    <p>

        As duas condições são verdadeiras.

    </p>

    <p>

        Porém, somente a primeira será executada.

    </p>

    <div class="resultado-codigo"><code>O número é positivo.</code></div>

    <div class="aula-destaque">

        <strong>

            Por que isso acontece?

        </strong>

        <p>

            A estrutura para de verificar as condições
            assim que encontra a primeira condição
            verdadeira.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ORDEM
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A ordem das condições é importante

    </h2>

    <p>

        Observe este código:

    </p>

    <pre class="codigo-java"><code>double nota = 9.5;

if (nota &gt;= 6) {

    System.out.println(
        "Aluno aprovado."
    );

} else if (nota &gt;= 9) {

    System.out.println(
        "Desempenho excelente."
    );

}</code></pre>

    <p>

        A nota 9.5 atende às duas condições.

    </p>

    <p>

        Porém, nota &gt;= 6 aparece primeiro.

    </p>

    <div class="resultado-codigo"><code>Aluno aprovado.</code></div>

    <p>

        A mensagem de desempenho excelente
        nunca será alcançada para notas maiores
        ou iguais a 9.

    </p>

    <p>

        Para corrigir, colocamos a condição
        mais específica primeiro.

    </p>

    <pre class="codigo-java"><code>double nota = 9.5;

if (nota &gt;= 9) {

    System.out.println(
        "Desempenho excelente."
    );

} else if (nota &gt;= 6) {

    System.out.println(
        "Aluno aprovado."
    );

}</code></pre>

    <div class="resultado-codigo"><code>Desempenho excelente.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| ESPECÍFICA PRIMEIRO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Condições específicas antes das gerais

    </h2>

    <p>

        Quando as condições trabalham com limites,
        normalmente começamos pelo valor mais específico.

    </p>

    <pre class="codigo-java"><code>if (nota &gt;= 9) {

    System.out.println("Excelente.");

} else if (nota &gt;= 7) {

    System.out.println("Bom.");

} else if (nota &gt;= 6) {

    System.out.println("Aprovado.");

} else {

    System.out.println("Reprovado.");

}</code></pre>

    <p>

        A verificação começa pelas notas mais altas
        e segue para as notas mais baixas.

    </p>

    <div class="aula-destaque">

        <strong>

            Regra prática:

        </strong>

        <p>

            Quando uma condição estiver incluída
            dentro de outra, coloque a mais específica
            primeiro.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CLASSIFICAÇÃO DE NOTAS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Classificando notas

    </h2>

    <pre class="codigo-java"><code>double nota = 8.5;

if (nota &gt;= 9) {

    System.out.println(
        "Desempenho: excelente."
    );

} else if (nota &gt;= 7) {

    System.out.println(
        "Desempenho: bom."
    );

} else if (nota &gt;= 6) {

    System.out.println(
        "Situação: aprovado."
    );

} else {

    System.out.println(
        "Situação: reprovado."
    );

}</code></pre>

    <p>

        A nota 8.5 não atende à primeira condição.

    </p>

    <p>

        Porém, atende à condição nota &gt;= 7.

    </p>

    <div class="resultado-codigo"><code>Desempenho: bom.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| TEMPERATURA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Classificando uma temperatura

    </h2>

    <pre class="codigo-java"><code>double temperatura = 28;

if (temperatura &gt;= 35) {

    System.out.println(
        "Muito quente."
    );

} else if (temperatura &gt;= 25) {

    System.out.println(
        "Quente."
    );

} else if (temperatura &gt;= 15) {

    System.out.println(
        "Agradável."
    );

} else if (temperatura &gt;= 5) {

    System.out.println(
        "Frio."
    );

} else {

    System.out.println(
        "Muito frio."
    );

}</code></pre>

    <p>

        Como a temperatura é 28, a segunda
        condição será atendida.

    </p>

    <div class="resultado-codigo"><code>Quente.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| FAIXAS SEM SOBREPOSIÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Condições anteriores já eliminam valores

    </h2>

    <p>

        Observe esta estrutura:

    </p>

    <pre class="codigo-java"><code>if (idade &lt; 12) {

    System.out.println("Criança.");

} else if (idade &lt; 18) {

    System.out.println("Adolescente.");

} else if (idade &lt; 60) {

    System.out.println("Adulto.");

} else {

    System.out.println("Idoso.");

}</code></pre>

    <p>

        Na segunda condição, não precisamos escrever:

    </p>

    <pre class="codigo-java"><code>idade &gt;= 12 &amp;&amp; idade &lt; 18</code></pre>

    <p>

        Isso acontece porque, se a idade chegou
        ao segundo bloco, já sabemos que ela
        não é menor que 12.

    </p>

    <div class="aula-destaque">

        <strong>

            A sequência também carrega informações:

        </strong>

        <p>

            Cada condição anterior falsa elimina
            uma parte das possibilidades.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIOS ELSE IF
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Quantos else if podemos utilizar?

    </h2>

    <p>

        Podemos utilizar vários blocos else if
        em uma mesma estrutura.

    </p>

    <pre class="codigo-java"><code>if (opcao == 1) {

    System.out.println("Iniciar.");

} else if (opcao == 2) {

    System.out.println("Configurações.");

} else if (opcao == 3) {

    System.out.println("Ajuda.");

} else if (opcao == 4) {

    System.out.println("Sair.");

} else {

    System.out.println("Opção inválida.");

}</code></pre>

    <p>

        Porém, uma estrutura muito longa pode
        ficar difícil de manter.

    </p>

    <p>

        Na próxima aula, conheceremos o switch,
        que pode organizar melhor alguns casos
        baseados em valores específicos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ELSE OPCIONAL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O else é obrigatório?

    </h2>

    <p>

        Não. O bloco else é opcional.

    </p>

    <pre class="codigo-java"><code>int idade = 20;

if (idade &lt; 12) {

    System.out.println("Criança.");

} else if (idade &lt; 18) {

    System.out.println("Adolescente.");

} else if (idade &lt; 60) {

    System.out.println("Adulto.");

}</code></pre>

    <p>

        Se nenhuma condição for verdadeira,
        nenhum dos blocos será executado.

    </p>

    <div class="aula-destaque">

        <strong>

            Quando utilizar else?

        </strong>

        <p>

            Utilize else quando existir uma ação padrão
            para todos os casos que não atenderam
            às condições anteriores.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MENU
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo com opções de menu

    </h2>

    <pre class="codigo-java"><code>int opcao = 2;

if (opcao == 1) {

    System.out.println(
        "Você escolheu iniciar."
    );

} else if (opcao == 2) {

    System.out.println(
        "Você escolheu o perfil."
    );

} else if (opcao == 3) {

    System.out.println(
        "Você escolheu sair."
    );

} else {

    System.out.println(
        "Opção inválida."
    );

}</code></pre>

    <p>

        Como o valor da opção é 2,
        o segundo bloco será executado.

    </p>

    <div class="resultado-codigo"><code>Você escolheu o perfil.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| STRING
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Else if com textos

    </h2>

    <p>

        Também podemos criar várias comparações
        com Strings.

    </p>

    <pre class="codigo-java"><code>String comando = "perfil";

if (comando.equalsIgnoreCase("inicio")) {

    System.out.println(
        "Abrindo o início."
    );

} else if (
    comando.equalsIgnoreCase("perfil")
) {

    System.out.println(
        "Abrindo o perfil."
    );

} else if (
    comando.equalsIgnoreCase("sair")
) {

    System.out.println(
        "Encerrando o programa."
    );

} else {

    System.out.println(
        "Comando inválido."
    );

}</code></pre>

    <div class="resultado-codigo"><code>Abrindo o perfil.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Else if com Scanner

    </h2>

    <p>

        Podemos receber um valor do usuário
        e classificá-lo utilizando várias condições.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        System.out.println(
            "Digite sua nota:"
        );

        double nota = entrada.nextDouble();

        if (nota &gt;= 9) {

            System.out.println(
                "Excelente."
            );

        } else if (nota &gt;= 7) {

            System.out.println(
                "Bom desempenho."
            );

        } else if (nota &gt;= 6) {

            System.out.println(
                "Aprovado."
            );

        } else {

            System.out.println(
                "Reprovado."
            );

        }

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite sua nota:
7.5
Bom desempenho.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| VALIDAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Validando valores antes de classificá-los

    </h2>

    <p>

        Em um programa de notas, podemos verificar
        primeiro se a nota é válida.

    </p>

    <pre class="codigo-java"><code>double nota = 11;

if (nota &lt; 0 || nota &gt; 10) {

    System.out.println(
        "Nota inválida."
    );

} else if (nota &gt;= 9) {

    System.out.println(
        "Excelente."
    );

} else if (nota &gt;= 6) {

    System.out.println(
        "Aprovado."
    );

} else {

    System.out.println(
        "Reprovado."
    );

}</code></pre>

    <p>

        Como a nota é maior que 10,
        a primeira condição será verdadeira.

    </p>

    <div class="resultado-codigo"><code>Nota inválida.</code></div>

    <div class="aula-destaque">

        <strong>

            Boa prática:

        </strong>

        <p>

            Antes de utilizar uma informação,
            verifique se ela está dentro
            dos limites esperados.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| COMPARAÇÃO DAS ESTRUTURAS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        If, else if e else

    </h2>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Estrutura</span>
            <span>Função</span>
            <span>Quantidade</span>

        </div>

        <div class="aula-tabela-linha">

            <span>if</span>
            <span>Inicia a primeira condição</span>
            <span>Um no início da estrutura</span>

        </div>

        <div class="aula-tabela-linha">

            <span>else if</span>
            <span>Adiciona uma nova condição</span>
            <span>Pode existir vários</span>

        </div>

        <div class="aula-tabela-linha">

            <span>else</span>
            <span>Trata os casos restantes</span>
            <span>No máximo um</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO COMPLETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: classificação por idade

    </h2>

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
            "Nome: " + nome
        );

        if (idade &lt; 0) {

            System.out.println(
                "Idade inválida."
            );

        } else if (idade &lt; 12) {

            System.out.println(
                "Classificação: criança."
            );

        } else if (idade &lt; 18) {

            System.out.println(
                "Classificação: adolescente."
            );

        } else if (idade &lt; 60) {

            System.out.println(
                "Classificação: adulto."
            );

        } else {

            System.out.println(
                "Classificação: idoso."
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

Nome: Robert
Classificação: adolescente.</code></div>

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

        Colocar uma condição geral primeiro

    </h3>

    <pre class="codigo-java"><code>if (nota &gt;= 6) {

    System.out.println("Aprovado.");

} else if (nota &gt;= 9) {

    System.out.println("Excelente.");

}</code></pre>

    <p>

        Notas maiores ou iguais a 9 também são
        maiores ou iguais a 6.

    </p>

    <p>

        Por isso, o segundo bloco nunca será utilizado
        para essas notas.

    </p>

    <h3>

        Escrever elseif junto

    </h3>

    <p>

        Em Java, escrevemos duas palavras:

    </p>

    <pre class="codigo-java"><code>else if</code></pre>

    <p>

        Esta escrita está incorreta:

    </p>

    <pre class="codigo-java"><code>elseif</code></pre>

    <h3>

        Colocar else antes dos else if

    </h3>

    <p>

        O else deve aparecer no final.

    </p>

    <pre class="codigo-java"><code>if (condicao1) {

    // Primeiro bloco

} else if (condicao2) {

    // Segundo bloco

} else {

    // Último bloco

}</code></pre>

    <h3>

        Colocar condição no else

    </h3>

    <p>

        Else não possui condição.

    </p>

    <p>

        Para adicionar outra condição,
        utilize else if.

    </p>

    <pre class="codigo-java"><code>} else if (idade &lt; 18) {

    System.out.println(
        "Menor de idade."
    );

}</code></pre>

    <h3>

        Esquecer que apenas um bloco será executado

    </h3>

    <p>

        Depois que uma condição verdadeira
        for encontrada, as condições restantes
        não serão analisadas.

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

        Vamos classificar uma pontuação de jogo.

    </p>

    <pre class="codigo-java"><code>int pontos = 750;

if (pontos &gt;= 1000) {

    System.out.println(
        "Classificação: ouro."
    );

} else if (pontos &gt;= 700) {

    System.out.println(
        "Classificação: prata."
    );

} else if (pontos &gt;= 400) {

    System.out.println(
        "Classificação: bronze."
    );

} else {

    System.out.println(
        "Sem classificação."
    );

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Classificação: prata.</code></div>

    <div class="aula-destaque">

        <strong>

            Experimente:

        </strong>

        <p>

            Altere a pontuação para 1200, 500
            e 200 e observe cada resultado.

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

        Crie um programa que receba a velocidade
        de um veículo.

    </p>

    <p>

        Utilize as seguintes classificações:

    </p>

    <ul>

        <li>
            valor negativo: velocidade inválida;
        </li>

        <li>
            até 40 km/h: velocidade baixa;
        </li>

        <li>
            até 80 km/h: velocidade moderada;
        </li>

        <li>
            até 120 km/h: velocidade alta;
        </li>

        <li>
            acima de 120 km/h: velocidade muito alta.
        </li>

    </ul>

    <p>

        Um possível resultado seria:

    </p>

    <div class="resultado-codigo"><code>Digite a velocidade:
95

Classificação: velocidade alta.</code></div>

    <div class="aula-destaque">

        <strong>

            Atenção à ordem:

        </strong>

        <p>

            Como estamos utilizando limites máximos,
            comece pelos menores valores e avance
            para os maiores.

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
        com várias possibilidades.

    </p>

    <ul>

        <li>
            else if adiciona uma nova condição;
        </li>

        <li>
            podemos utilizar vários blocos else if;
        </li>

        <li>
            as condições são analisadas
            de cima para baixo;
        </li>

        <li>
            apenas o primeiro bloco verdadeiro
            será executado;
        </li>

        <li>
            o else final é opcional;
        </li>

        <li>
            else trata os casos que não foram
            atendidos anteriormente;
        </li>

        <li>
            a ordem das condições pode alterar
            o resultado;
        </li>

        <li>
            condições específicas geralmente devem
            aparecer antes das condições gerais;
        </li>

        <li>
            podemos validar dados antes
            de classificá-los.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Else if permite analisar várias
                possibilidades, mas somente o primeiro
                caminho verdadeiro será executado.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, conheceremos a estrutura
        switch, que oferece outra forma de trabalhar
        com opções específicas.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Quanto mais possibilidades um programa
        consegue analisar, mais flexível ele se torna."

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

        Próxima aula: Switch →

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