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
                "operadores_relacionais"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "if"
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
    content="Aprenda como combinar condições utilizando os operadores lógicos AND, OR e NOT em Java."
>

<title>
    Operadores Lógicos em Java | Java, Café e Código
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

        AULA 07

    </span>

    <h1>

        Operadores Lógicos em Java

    </h1>

    <p>

        Aprenda como combinar e inverter condições
        utilizando os operadores lógicos AND, OR e NOT.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Combinando perguntas

    </h2>

    <p>

        Na aula anterior, aprendemos como comparar
        valores utilizando os operadores relacionais.

    </p>

    <pre class="codigo-java"><code>int idade = 20;

boolean maiorDeIdade = idade &gt;= 18;</code></pre>

    <p>

        Essa comparação responde apenas uma pergunta:

    </p>

    <div class="aula-destaque">

        <p>

            A idade é maior ou igual a 18?

        </p>

    </div>

    <p>

        Porém, alguns programas precisam verificar
        mais de uma condição.

    </p>

    <p>

        Um sistema pode precisar descobrir se a pessoa
        é maior de idade e também possui ingresso.

    </p>

    <p>

        Para combinar essas condições, utilizamos
        os operadores lógicos.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            Os operadores lógicos permitem combinar
            ou inverter condições que produzem
            resultados verdadeiros ou falsos.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VALORES BOOLEANOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Relembrando o tipo boolean

    </h2>

    <p>

        Os operadores lógicos trabalham com valores
        do tipo <strong>boolean</strong>.

    </p>

    <p>

        Uma variável boolean pode guardar apenas:

    </p>

    <ul>

        <li>

            <strong>true</strong>, que representa verdadeiro;

        </li>

        <li>

            <strong>false</strong>, que representa falso.

        </li>

    </ul>

    <pre class="codigo-java"><code>boolean possuiIngresso = true;
boolean estaAutorizado = false;</code></pre>

    <p>

        Valores booleanos também podem surgir
        como resultado de comparações.

    </p>

    <pre class="codigo-java"><code>int idade = 20;

boolean maiorDeIdade = idade &gt;= 18;

System.out.println(maiorDeIdade);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| OPERADORES PRINCIPAIS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Principais operadores lógicos

    </h2>

    <p>

        Nesta aula, estudaremos três operadores.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Operador</span>
            <span>Significado</span>
            <span>Função</span>

        </div>

        <div class="aula-tabela-linha">

            <span>&amp;&amp;</span>
            <span>AND — E</span>
            <span>Exige todas as condições verdadeiras</span>

        </div>

        <div class="aula-tabela-linha">

            <span>||</span>
            <span>OR — OU</span>
            <span>Exige pelo menos uma condição verdadeira</span>

        </div>

        <div class="aula-tabela-linha">

            <span>!</span>
            <span>NOT — NÃO</span>
            <span>Inverte o valor de uma condição</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| AND
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Operador AND: &amp;&amp;

    </h2>

    <p>

        O operador <strong>&amp;&amp;</strong>
        representa a palavra
        <strong>E</strong>.

    </p>

    <p>

        Ele é utilizado quando todas as condições
        precisam ser verdadeiras.

    </p>

    <p>

        Imagine a seguinte regra:

    </p>

    <div class="aula-destaque">

        <strong>

            Para entrar no evento:

        </strong>

        <p>

            A pessoa precisa ser maior de idade
            <strong>e</strong> possuir ingresso.

        </p>

    </div>

    <p>

        Podemos representar essa regra assim:

    </p>

    <pre class="codigo-java"><code>int idade = 20;
boolean possuiIngresso = true;

boolean maiorDeIdade = idade &gt;= 18;

boolean entradaPermitida =
    maiorDeIdade &amp;&amp; possuiIngresso;

System.out.println(entradaPermitida);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        O resultado é verdadeiro porque as duas
        condições são verdadeiras.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| TABELA AND
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como funciona o operador &amp;&amp;?

    </h2>

    <p>

        O resultado será verdadeiro apenas quando
        todas as condições forem verdadeiras.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Condição 1</span>
            <span>Condição 2</span>
            <span>Resultado</span>

        </div>

        <div class="aula-tabela-linha">

            <span>true</span>
            <span>true</span>
            <span>true</span>

        </div>

        <div class="aula-tabela-linha">

            <span>true</span>
            <span>false</span>
            <span>false</span>

        </div>

        <div class="aula-tabela-linha">

            <span>false</span>
            <span>true</span>
            <span>false</span>

        </div>

        <div class="aula-tabela-linha">

            <span>false</span>
            <span>false</span>
            <span>false</span>

        </div>

    </div>

    <div class="aula-destaque">

        <strong>

            Regra do AND:

        </strong>

        <p>

            Se uma das condições for falsa,
            o resultado completo será falso.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO AND
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Outro exemplo com &amp;&amp;

    </h2>

    <p>

        Imagine que um estudante precisa possuir
        frequência suficiente e nota maior ou igual a 7.

    </p>

    <pre class="codigo-java"><code>double nota = 8.5;
double frequencia = 80.0;

boolean notaSuficiente = nota &gt;= 7;
boolean frequenciaSuficiente = frequencia &gt;= 75;

boolean aprovado =
    notaSuficiente &amp;&amp; frequenciaSuficiente;

System.out.println(aprovado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        Como a nota e a frequência atendem
        aos requisitos, o resultado é verdadeiro.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| OR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Operador OR: ||

    </h2>

    <p>

        O operador <strong>||</strong>
        representa a palavra
        <strong>OU</strong>.

    </p>

    <p>

        Ele é utilizado quando pelo menos uma
        das condições precisa ser verdadeira.

    </p>

    <p>

        Imagine que um sistema permita acesso
        para professores ou administradores.

    </p>

    <pre class="codigo-java"><code>boolean professor = true;
boolean administrador = false;

boolean acessoPermitido =
    professor || administrador;

System.out.println(acessoPermitido);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        Mesmo que administrador seja falso,
        professor é verdadeiro.

    </p>

    <p>

        Como pelo menos uma condição foi atendida,
        o resultado é verdadeiro.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| TABELA OR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como funciona o operador ||?

    </h2>

    <p>

        O resultado será verdadeiro quando pelo menos
        uma das condições for verdadeira.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Condição 1</span>
            <span>Condição 2</span>
            <span>Resultado</span>

        </div>

        <div class="aula-tabela-linha">

            <span>true</span>
            <span>true</span>
            <span>true</span>

        </div>

        <div class="aula-tabela-linha">

            <span>true</span>
            <span>false</span>
            <span>true</span>

        </div>

        <div class="aula-tabela-linha">

            <span>false</span>
            <span>true</span>
            <span>true</span>

        </div>

        <div class="aula-tabela-linha">

            <span>false</span>
            <span>false</span>
            <span>false</span>

        </div>

    </div>

    <div class="aula-destaque">

        <strong>

            Regra do OR:

        </strong>

        <p>

            O resultado será falso apenas quando
            todas as condições forem falsas.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO OR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Outro exemplo com ||

    </h2>

    <p>

        Imagine que uma pessoa possa entrar gratuitamente
        se for criança ou idosa.

    </p>

    <pre class="codigo-java"><code>int idade = 70;

boolean crianca = idade &lt;= 12;
boolean idoso = idade &gt;= 60;

boolean entradaGratuita =
    crianca || idoso;

System.out.println(entradaGratuita);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        A pessoa não é criança, mas é idosa.

    </p>

    <p>

        Como uma das condições é verdadeira,
        o resultado também é verdadeiro.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| NOT
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Operador NOT: !

    </h2>

    <p>

        O operador <strong>!</strong>
        representa a palavra
        <strong>NÃO</strong>.

    </p>

    <p>

        Ele inverte um valor booleano.

    </p>

    <pre class="codigo-java"><code>boolean estaLogado = false;

boolean naoEstaLogado = !estaLogado;

System.out.println(naoEstaLogado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        A variável estaLogado possuía o valor false.

    </p>

    <p>

        Ao utilizar o operador
        <strong>!</strong>, o valor foi invertido.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Valor original</span>
            <span>Expressão</span>
            <span>Resultado</span>

        </div>

        <div class="aula-tabela-linha">

            <span>true</span>
            <span>!true</span>
            <span>false</span>

        </div>

        <div class="aula-tabela-linha">

            <span>false</span>
            <span>!false</span>
            <span>true</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| NOT EM COMPARAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Invertendo uma comparação

    </h2>

    <p>

        O operador NOT também pode inverter
        o resultado de uma comparação.

    </p>

    <pre class="codigo-java"><code>int idade = 17;

boolean maiorDeIdade = idade &gt;= 18;

System.out.println(maiorDeIdade);
System.out.println(!maiorDeIdade);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>false
true</code></div>

    <p>

        A pessoa não é maior de idade.

    </p>

    <p>

        Por isso, maiorDeIdade é false e
        !maiorDeIdade é true.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| COMBINANDO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Combinando operadores lógicos

    </h2>

    <p>

        Podemos combinar os operadores
        <strong>&amp;&amp;</strong>,
        <strong>||</strong> e
        <strong>!</strong>.

    </p>

    <p>

        Imagine que uma pessoa possa entrar em um evento
        quando for maior de idade e possuir ingresso,
        ou quando possuir autorização especial.

    </p>

    <pre class="codigo-java"><code>int idade = 20;

boolean possuiIngresso = true;
boolean possuiAutorizacaoEspecial = false;

boolean entradaPermitida =
    (idade &gt;= 18 &amp;&amp; possuiIngresso)
    || possuiAutorizacaoEspecial;

System.out.println(entradaPermitida);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        A primeira parte da expressão é verdadeira,
        porque a pessoa é maior de idade e possui ingresso.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PARÊNTESES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Organizando condições com parênteses

    </h2>

    <p>

        Os parênteses ajudam a organizar expressões
        com várias condições.

    </p>

    <pre class="codigo-java"><code>boolean resultado =
    (condicao1 &amp;&amp; condicao2)
    || condicao3;</code></pre>

    <p>

        Nesse exemplo, as condições 1 e 2 são
        avaliadas como um grupo.

    </p>

    <p>

        Depois, o resultado desse grupo é combinado
        com a condição 3.

    </p>

    <div class="aula-destaque">

        <strong>

            Boa prática:

        </strong>

        <p>

            Utilize parênteses para tornar expressões
            lógicas mais fáceis de compreender,
            mesmo quando eles não forem obrigatórios.

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

        Ordem dos operadores lógicos

    </h2>

    <p>

        Quando uma expressão possui vários operadores,
        o Java segue uma ordem de avaliação.

    </p>

    <p>

        De forma simplificada:

    </p>

    <ul>

        <li>

            primeiro, expressões entre parênteses;

        </li>

        <li>

            depois, o operador
            <strong>!</strong>;

        </li>

        <li>

            depois, o operador
            <strong>&amp;&amp;</strong>;

        </li>

        <li>

            por último, o operador
            <strong>||</strong>.

        </li>

    </ul>

    <pre class="codigo-java"><code>boolean resultado =
    !condicao1 &amp;&amp; condicao2 || condicao3;</code></pre>

    <p>

        Porém, expressões desse tipo podem ser difíceis
        de ler.

    </p>

    <p>

        Uma escrita mais clara seria:

    </p>

    <pre class="codigo-java"><code>boolean resultado =
    ((!condicao1) &amp;&amp; condicao2)
    || condicao3;</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO PRÁTICO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: acesso ao sistema

    </h2>

    <p>

        Vamos criar uma expressão que verifica
        se um usuário pode acessar uma área do sistema.

    </p>

    <p>

        Para acessar, ele precisa estar logado
        e possuir o perfil de professor ou administrador.

    </p>

    <pre class="codigo-java"><code>boolean estaLogado = true;
boolean professor = true;
boolean administrador = false;

boolean podeAcessar =
    estaLogado
    &amp;&amp; (professor || administrador);

System.out.println(podeAcessar);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        O usuário está logado e possui um dos
        perfis permitidos.

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

        Escrever AND e OR como palavras

    </h3>

    <p>

        Este código está incorreto em Java:

    </p>

    <pre class="codigo-java"><code>boolean resultado =
    condicao1 AND condicao2;</code></pre>

    <p>

        O correto é utilizar:

    </p>

    <pre class="codigo-java"><code>boolean resultado =
    condicao1 &amp;&amp; condicao2;</code></pre>

    <h3>

        Utilizar apenas um símbolo

    </h3>

    <p>

        Nesta aula, utilizamos os operadores:

    </p>

    <pre class="codigo-java"><code>&amp;&amp;
||</code></pre>

    <p>

        Eles possuem dois símbolos.

    </p>

    <h3>

        Colocar uma expressão que não produz boolean

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>boolean resultado = 10 &amp;&amp; 20;</code></pre>

    <p>

        Os operadores lógicos precisam trabalhar
        com valores ou expressões booleanas.

    </p>

    <pre class="codigo-java"><code>boolean resultado =
    10 &gt; 5 &amp;&amp; 20 &gt; 10;</code></pre>

    <h3>

        Esquecer o significado do NOT

    </h3>

    <pre class="codigo-java"><code>boolean ativo = true;

System.out.println(!ativo);</code></pre>

    <p>

        O resultado será false, porque
        o operador ! inverte o valor.

    </p>

    <h3>

        Criar expressões difíceis de entender

    </h3>

    <p>

        Evite condições muito longas sem organização.

    </p>

    <p>

        Utilize variáveis booleanas e parênteses
        para melhorar a leitura.

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

        Vamos verificar se um estudante pode participar
        de uma atividade.

    </p>

    <p>

        Ele precisa estar matriculado e possuir
        autorização do responsável.

    </p>

    <pre class="codigo-java"><code>boolean estaMatriculado = true;
boolean possuiAutorizacao = true;

boolean podeParticipar =
    estaMatriculado &amp;&amp; possuiAutorizacao;

System.out.println(
    "Pode participar? " + podeParticipar
);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Pode participar? true</code></div>

    <div class="aula-destaque">

        <strong>

            Experimente:

        </strong>

        <p>

            Altere uma das variáveis para false
            e observe como o resultado muda.

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

        Crie um programa que represente o acesso
        a um jogo.

    </p>

    <p>

        O jogador poderá acessar quando:

    </p>

    <ul>

        <li>

            possuir uma conta ativa;

        </li>

        <li>

            e tiver comprado o jogo ou possuir assinatura.

        </li>

    </ul>

    <p>

        Utilize variáveis semelhantes a:

    </p>

    <pre class="codigo-java"><code>boolean contaAtiva = true;
boolean comprouJogo = false;
boolean possuiAssinatura = true;</code></pre>

    <p>

        Depois, crie uma expressão que produza:

    </p>

    <div class="resultado-codigo"><code>Acesso ao jogo: true</code></div>

    <div class="aula-destaque">

        <strong>

            Estrutura da regra:

        </strong>

        <p>

            conta ativa
            <strong>E</strong>
            jogo comprado ou assinatura ativa.

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

        Nesta aula, aprendemos como combinar
        e inverter condições booleanas.

    </p>

    <ul>

        <li>

            <strong>&amp;&amp;</strong>
            representa o operador AND ou E;

        </li>

        <li>

            o AND exige que todas as condições
            sejam verdadeiras;

        </li>

        <li>

            <strong>||</strong>
            representa o operador OR ou OU;

        </li>

        <li>

            o OR exige pelo menos uma condição
            verdadeira;

        </li>

        <li>

            <strong>!</strong>
            representa o operador NOT ou NÃO;

        </li>

        <li>

            o NOT inverte um valor booleano;

        </li>

        <li>

            operadores lógicos trabalham com
            valores true e false;

        </li>

        <li>

            parênteses ajudam a organizar
            expressões mais complexas.

        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Operadores lógicos permitem combinar
                perguntas simples para construir
                condições mais completas.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aplicaremos essas condições
        dentro da estrutura if, permitindo que o programa
        execute comandos somente quando uma condição
        for verdadeira.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Condições simples podem ser combinadas
        para representar regras muito mais completas."

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

        Próxima aula: Estrutura if →

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