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
                "inserindo_tela"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "tipos_dados"
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
    content="Aprenda o que são variáveis e como armazenar informações em programas Java."
>

<title>
    Variáveis em Java | Java, Café e Código
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

        AULA 02

    </span>

    <h1>

        Variáveis em Java

    </h1>

    <p>

        Aprenda como criar espaços na memória
        para guardar e utilizar informações
        dentro dos seus programas.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Informações podem mudar

    </h2>

    <p>

        Na aula anterior, aprendemos como mostrar
        mensagens utilizando
        <strong>System.out.println()</strong>.

    </p>

    <p>

        Até aquele momento, os textos estavam
        escritos diretamente dentro do código.

    </p>

    <pre class="codigo-java"><code>System.out.println("Olá, Robert!");</code></pre>

    <p>

        Mas imagine que o programa precise apresentar
        nomes diferentes.

    </p>

    <p>

        Em determinado momento, o nome pode ser
        Robert. Depois, pode ser Natanael ou qualquer
        outra pessoa.

    </p>

    <p>

        Em vez de escrever o nome diretamente em
        todas as partes do programa, podemos guardar
        essa informação em uma variável.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";</code></pre>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            Uma variável permite guardar uma informação
            para utilizá-la novamente e, quando necessário,
            alterar seu valor.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE É UMA VARIÁVEL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é uma variável?

    </h2>

    <p>

        Imagine que você possui diferentes caixas
        para guardar objetos.

    </p>

    <p>

        Uma caixa pode guardar um livro, outra pode
        guardar uma caneta e outra pode guardar
        um celular.

    </p>

    <p>

        Para saber o que existe em cada caixa,
        podemos colocar uma identificação nelas.

    </p>

    <p>

        Na programação, uma variável funciona
        de maneira parecida.

    </p>

    <p>

        Ela representa um espaço na memória do
        computador utilizado para armazenar
        uma informação.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";</code></pre>

    <p>

        Nesse exemplo, criamos uma variável chamada
        <strong>nome</strong> e guardamos nela
        o texto <strong>"Robert"</strong>.

    </p>

    <div class="aula-destaque">

        <strong>

            Pense assim:

        </strong>

        <p>

            A variável é como uma caixa, seu nome
            funciona como uma etiqueta e o valor
            representa aquilo que está guardado nela.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| POR QUE USAR VARIÁVEIS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Por que utilizamos variáveis?

    </h2>

    <p>

        As variáveis permitem organizar as informações
        utilizadas pelo programa.

    </p>

    <p>

        Podemos, por exemplo, guardar o nome e a idade
        de um estudante.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";
int idade = 17;</code></pre>

    <p>

        Agora o programa possui duas informações
        armazenadas:

    </p>

    <ul>

        <li>

            <strong>nome</strong>
            guarda o texto "Robert";

        </li>

        <li>

            <strong>idade</strong>
            guarda o número 17.

        </li>

    </ul>

    <p>

        Essas informações podem ser utilizadas
        em diferentes partes do programa.

    </p>

    <div class="aula-destaque">

        <strong>

            Vantagem das variáveis:

        </strong>

        <p>

            Quando uma informação mudar, podemos alterar
            o valor armazenado na variável sem precisar
            reescrever todas as partes do programa.

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

        Estrutura de uma variável

    </h2>

    <p>

        Para criar uma variável em Java,
        normalmente utilizamos esta estrutura:

    </p>

    <pre class="codigo-java"><code>tipo nome = valor;</code></pre>

    <p>

        Cada parte possui uma função.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Função</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Tipo</span>
            <span>Define a categoria da informação</span>
            <span>int</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Nome</span>
            <span>Identifica a variável</span>
            <span>idade</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Atribuição</span>
            <span>Coloca um valor na variável</span>
            <span>=</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Valor</span>
            <span>Informação armazenada</span>
            <span>17</span>

        </div>

    </div>

    <p>

        Observe novamente:

    </p>

    <pre class="codigo-java"><code>int idade = 17;</code></pre>

    <p>

        Nessa instrução:

    </p>

    <ul>

        <li><strong>int</strong> é o tipo;</li>

        <li><strong>idade</strong> é o nome da variável;</li>

        <li><strong>=</strong> realiza a atribuição;</li>

        <li><strong>17</strong> é o valor armazenado;</li>

        <li><strong>;</strong> encerra a instrução.</li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| TIPO DA VARIÁVEL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Toda variável possui um tipo

    </h2>

    <p>

        Em Java, precisamos informar que tipo de
        informação será armazenado na variável.

    </p>

    <p>

        Observe estes dois exemplos:

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";
int idade = 17;</code></pre>

    <p>

        A variável <strong>nome</strong> guarda
        um texto, enquanto a variável
        <strong>idade</strong> guarda um número inteiro.

    </p>

    <p>

        Por enquanto, basta compreender que o tipo
        informa ao Java qual categoria de informação
        aquela variável poderá armazenar.

    </p>

    <div class="aula-destaque">

        <strong>

            Na próxima aula:

        </strong>

        <p>

            Vamos estudar detalhadamente os tipos
            de dados disponíveis em Java, como
            int, double, String, char e boolean.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| DECLARAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Declarando uma variável

    </h2>

    <p>

        Declarar uma variável significa informar
        ao Java que ela existe.

    </p>

    <p>

        Podemos declarar uma variável sem colocar
        um valor imediatamente.

    </p>

    <pre class="codigo-java"><code>int idade;</code></pre>

    <p>

        Nesse momento, criamos uma variável chamada
        <strong>idade</strong>, preparada para guardar
        um número inteiro.

    </p>

    <p>

        Depois, podemos colocar um valor nela:

    </p>

    <pre class="codigo-java"><code>idade = 17;</code></pre>

    <p>

        O símbolo <strong>=</strong> realiza uma
        atribuição.

    </p>

    <p>

        Isso significa que o valor localizado
        à direita será armazenado na variável
        localizada à esquerda.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INICIALIZAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Inicializando uma variável

    </h2>

    <p>

        Inicializar uma variável significa colocar
        nela seu primeiro valor.

    </p>

    <p>

        Podemos declarar e inicializar separadamente:

    </p>

    <pre class="codigo-java"><code>int idade;

idade = 17;</code></pre>

    <p>

        Também podemos realizar as duas ações
        na mesma instrução:

    </p>

    <pre class="codigo-java"><code>int idade = 17;</code></pre>

    <p>

        Essa segunda forma é muito utilizada
        em programas Java.

    </p>

    <div class="aula-destaque">

        <strong>

            Declaração e inicialização:

        </strong>

        <p>

            Em <strong>int idade;</strong>,
            apenas declaramos a variável.

        </p>

        <p>

            Em <strong>int idade = 17;</strong>,
            declaramos a variável e colocamos
            nela seu primeiro valor.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXIBINDO VARIÁVEL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exibindo o valor de uma variável

    </h2>

    <p>

        Podemos utilizar o
        <strong>System.out.println()</strong>
        para mostrar o valor armazenado em uma variável.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";

System.out.println(nome);</code></pre>

    <p>

        O resultado será:

    </p>

    <div class="resultado-codigo"><code>Robert</code></div>

    <p>

        Dentro do
        <strong>System.out.println()</strong>,
        escrevemos o nome da variável sem aspas.

    </p>

    <p>

        Assim, o Java acessa o conteúdo que está
        armazenado nela.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| TEXTO OU VARIÁVEL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Texto e variável não são a mesma coisa

    </h2>

    <p>

        Observe estas duas instruções:

    </p>

    <pre class="codigo-java"><code>System.out.println("nome");
System.out.println(nome);</code></pre>

    <p>

        Apesar de parecerem semelhantes, elas possuem
        comportamentos diferentes.

    </p>

    <h3>

        Com aspas

    </h3>

    <pre class="codigo-java"><code>System.out.println("nome");</code></pre>

    <p>

        O Java mostra exatamente o texto escrito
        entre as aspas.

    </p>

    <div class="resultado-codigo"><code>nome</code></div>

    <h3>

        Sem aspas

    </h3>

    <pre class="codigo-java"><code>System.out.println(nome);</code></pre>

    <p>

        O Java procura uma variável chamada
        <strong>nome</strong> e mostra o valor
        armazenado nela.

    </p>

    <div class="resultado-codigo"><code>Robert</code></div>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            <strong>"nome"</strong> é um texto.

        </p>

        <p>

            <strong>nome</strong>, sem aspas,
            representa uma variável.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ALTERANDO VALOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Alterando o valor de uma variável

    </h2>

    <p>

        Como o próprio nome indica, o valor de uma
        variável pode variar durante o programa.

    </p>

    <p>

        Primeiro, podemos guardar o número 17:

    </p>

    <pre class="codigo-java"><code>int idade = 17;</code></pre>

    <p>

        Depois, podemos colocar outro valor
        na mesma variável:

    </p>

    <pre class="codigo-java"><code>idade = 18;</code></pre>

    <p>

        O valor anterior foi substituído pelo
        novo valor.

    </p>

    <pre class="codigo-java"><code>int idade = 17;

System.out.println(idade);

idade = 18;

System.out.println(idade);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>17
18</code></div>

    <p>

        Não escrevemos novamente o tipo
        <strong>int</strong> porque a variável
        já havia sido declarada.

    </p>

    <div class="aula-destaque">

        <strong>

            O que aconteceu?

        </strong>

        <p>

            A variável continuou sendo do mesmo tipo,
            mas passou a armazenar um valor diferente.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MOSTRANDO TEXTO E VARIÁVEL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Mostrando textos e variáveis juntos

    </h2>

    <p>

        Podemos mostrar um texto e o valor de uma
        variável na mesma instrução.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";

System.out.println("Nome: " + nome);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert</code></div>

    <p>

        O operador <strong>+</strong> juntou o texto
        <strong>"Nome: "</strong> com o valor
        armazenado na variável <strong>nome</strong>.

    </p>

    <p>

        Essa união de informações é chamada
        de <strong>concatenação</strong>.

    </p>

    <div class="aula-destaque">

        <strong>

            Neste momento:

        </strong>

        <p>

            Basta compreender que o operador
            <strong>+</strong> pode juntar um texto
            com o conteúdo de uma variável.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO COMPLETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo completo

    </h2>

    <p>

        Vamos criar um pequeno programa que guarda
        e apresenta informações sobre um estudante.

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        String nome = "Robert";
        int idade = 17;
        String curso = "Informática";

        System.out.println("Nome: " + nome);
        System.out.println("Idade: " + idade);
        System.out.println("Curso: " + curso);

    }

}</code></pre>

    <p>

        Nesse programa, criamos três variáveis:

    </p>

    <ul>

        <li>
            <strong>nome</strong>
            guarda o nome do estudante;
        </li>

        <li>
            <strong>idade</strong>
            guarda a idade do estudante;
        </li>

        <li>
            <strong>curso</strong>
            guarda o nome do curso.
        </li>

    </ul>

    <p>

        O resultado será:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert
Idade: 17
Curso: Informática</code></div>

    <p>

        Perceba que as informações foram armazenadas
        primeiro e utilizadas depois.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| NOMES DE VARIÁVEIS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Escolhendo bons nomes

    </h2>

    <p>

        Toda variável precisa possuir um nome.

    </p>

    <p>

        Esse nome deve ajudar a identificar qual
        informação está sendo armazenada.

    </p>

    <p>

        Observe alguns nomes claros:

    </p>

    <pre class="codigo-java"><code>String nomeAluno;
int idadeAluno;
double precoProduto;</code></pre>

    <p>

        Apenas lendo os nomes, conseguimos compreender
        o objetivo de cada variável.

    </p>

    <p>

        Agora observe nomes pouco explicativos:

    </p>

    <pre class="codigo-java"><code>String x;
int a;
double valor1;</code></pre>

    <p>

        Esses nomes podem funcionar, mas deixam
        o código mais difícil de compreender.

    </p>

    <div class="aula-destaque">

        <strong>

            Boa prática:

        </strong>

        <p>

            O nome da variável deve explicar
            claramente qual informação ela guarda.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| REGRAS DOS NOMES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Regras para nomes de variáveis

    </h2>

    <p>

        O Java possui algumas regras para definir
        nomes de variáveis.

    </p>

    <h3>

        Não pode começar com número

    </h3>

    <p>

        Este nome está incorreto:

    </p>

    <pre class="codigo-java"><code>int 1idade = 17;</code></pre>

    <p>

        Uma forma correta seria:

    </p>

    <pre class="codigo-java"><code>int idade1 = 17;</code></pre>

    <h3>

        Não pode possuir espaços

    </h3>

    <p>

        Este nome está incorreto:

    </p>

    <pre class="codigo-java"><code>String nome aluno = "Robert";</code></pre>

    <p>

        Uma forma correta seria:

    </p>

    <pre class="codigo-java"><code>String nomeAluno = "Robert";</code></pre>

    <h3>

        Não pode utilizar palavras reservadas

    </h3>

    <p>

        Algumas palavras já possuem uma função
        especial dentro da linguagem Java.

    </p>

    <p>

        Por isso, não podemos utilizá-las como
        nomes de variáveis.

    </p>

    <pre class="codigo-java"><code>int class = 10;</code></pre>

    <p>

        Nesse exemplo, <strong>class</strong>
        é uma palavra reservada da linguagem.

    </p>

    <h3>

        Letras maiúsculas fazem diferença

    </h3>

    <p>

        Java diferencia letras maiúsculas
        de letras minúsculas.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";
String Nome = "Natanael";</code></pre>

    <p>

        Para o Java, <strong>nome</strong>
        e <strong>Nome</strong> são variáveis
        diferentes.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CAMEL CASE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O padrão camelCase

    </h2>

    <p>

        Em Java, é comum utilizar o padrão
        <strong>camelCase</strong> para criar
        nomes de variáveis.

    </p>

    <p>

        Nesse padrão, a primeira palavra começa
        com letra minúscula.

    </p>

    <p>

        Quando o nome possui mais palavras,
        cada nova palavra começa com letra maiúscula.

    </p>

    <pre class="codigo-java"><code>nomeAluno
idadeAluno
precoProduto
quantidadeEstoque</code></pre>

    <p>

        Observe como não utilizamos espaços,
        mas ainda conseguimos identificar
        cada palavra.

    </p>

    <div class="aula-destaque">

        <strong>

            Dica de programação:

        </strong>

        <p>

            Utilize nomes claros e siga o padrão
            camelCase para manter seus códigos
            mais organizados e fáceis de ler.

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

    <p>

        Durante o aprendizado, é normal cometer
        alguns erros ao trabalhar com variáveis.

    </p>

    <h3>

        Utilizar uma variável que não existe

    </h3>

    <p>

        Este código apresenta um erro:

    </p>

    <pre class="codigo-java"><code>System.out.println(nome);</code></pre>

    <p>

        Antes de utilizar a variável, precisamos
        declará-la.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";

System.out.println(nome);</code></pre>

    <h3>

        Utilizar uma variável sem valor

    </h3>

    <p>

        Uma variável local precisa possuir um valor
        antes de ser utilizada.

    </p>

    <pre class="codigo-java"><code>int idade;

System.out.println(idade);</code></pre>

    <p>

        O correto seria atribuir um valor antes
        de tentar exibi-la.

    </p>

    <pre class="codigo-java"><code>int idade;

idade = 17;

System.out.println(idade);</code></pre>

    <h3>

        Declarar a mesma variável duas vezes

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>int idade = 17;
int idade = 18;</code></pre>

    <p>

        Como a variável já existe, devemos apenas
        alterar seu valor.

    </p>

    <pre class="codigo-java"><code>int idade = 17;

idade = 18;</code></pre>

    <h3>

        Colocar o nome da variável entre aspas

    </h3>

    <pre class="codigo-java"><code>String nome = "Robert";

System.out.println("nome");</code></pre>

    <p>

        Esse código mostra a palavra
        <strong>nome</strong>, e não o valor
        da variável.

    </p>

    <p>

        Para acessar a variável, retiramos as aspas:

    </p>

    <pre class="codigo-java"><code>System.out.println(nome);</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| PRÁTICA GUIADA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Prática guiada

    </h2>

    <p>

        Vamos criar um programa que guarda
        o nome de uma cidade e depois mostra
        essa informação no console.

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        String cidade = "Rio Grande";

        System.out.println("Cidade: " + cidade);

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Cidade: Rio Grande</code></div>

    <p>

        Agora altere o valor da variável
        <strong>cidade</strong> e execute
        o programa novamente.

    </p>

    <div class="aula-destaque">

        <strong>

            Experimente:

        </strong>

        <p>

            Troque "Rio Grande" pelo nome
            da sua cidade e observe a mudança
            no resultado.

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

        Crie um programa que possua três variáveis:

    </p>

    <ul>

        <li>
            uma variável chamada
            <strong>nomeJogo</strong>;
        </li>

        <li>
            uma variável chamada
            <strong>anoLancamento</strong>;
        </li>

        <li>
            uma variável chamada
            <strong>plataforma</strong>.
        </li>

    </ul>

    <p>

        Depois, utilize essas variáveis para
        produzir um resultado semelhante a este:

    </p>

    <div class="resultado-codigo"><code>Jogo: Minecraft
Ano de lançamento: 2011
Plataforma: Computador</code></div>

    <div class="aula-destaque">

        <strong>

            Regras:

        </strong>

        <p>

            Utilize nomes no padrão camelCase.

        </p>

        <p>

            Mostre os valores utilizando
            System.out.println().

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

        Nesta aula, você aprendeu que uma variável
        representa um espaço utilizado para armazenar
        uma informação.

    </p>

    <ul>

        <li>
            variáveis guardam informações;
        </li>

        <li>
            toda variável possui um tipo e um nome;
        </li>

        <li>
            declarar significa criar uma variável;
        </li>

        <li>
            inicializar significa colocar nela
            seu primeiro valor;
        </li>

        <li>
            o operador <strong>=</strong>
            realiza uma atribuição;
        </li>

        <li>
            o valor de uma variável pode ser alterado;
        </li>

        <li>
            variáveis são utilizadas sem aspas;
        </li>

        <li>
            nomes claros facilitam a leitura do código;
        </li>

        <li>
            o padrão camelCase é muito utilizado
            em Java.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Variáveis permitem guardar, utilizar
                e alterar informações durante
                a execução de um programa.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, vamos conhecer os diferentes
        tipos de dados que podem ser armazenados
        em uma variável.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Organizar informações é um dos primeiros
        passos para construir programas melhores."

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

        Próxima aula: Tipos de dados →

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