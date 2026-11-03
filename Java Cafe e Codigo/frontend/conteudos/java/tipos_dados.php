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
                "variaveis"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "scanner"
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
    content="Aprenda os principais tipos de dados utilizados na linguagem Java."
>

<title>
    Tipos de Dados em Java | Java, Café e Código
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

        AULA 03

    </span>

    <h1>

        Tipos de Dados em Java

    </h1>

    <p>

        Aprenda como o Java organiza diferentes
        informações e conheça os principais tipos
        utilizados durante a programação.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Nem toda informação é igual

    </h2>

    <p>

        Na aula anterior, aprendemos que variáveis
        são utilizadas para armazenar informações
        dentro de um programa.

    </p>

    <p>

        Porém, nem todas as informações possuem
        as mesmas características.

    </p>

    <p>

        Uma idade é um número inteiro. Uma altura
        pode possuir casas decimais. Um nome é
        formado por texto.

    </p>

    <pre class="codigo-java"><code>int idade = 17;
double altura = 1.68;
String nome = "Robert";</code></pre>

    <p>

        Para diferenciar essas informações,
        o Java utiliza os
        <strong>tipos de dados</strong>.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            O tipo de dado informa ao Java qual
            categoria de informação uma variável
            poderá armazenar.

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

        O que são tipos de dados?

    </h2>

    <p>

        Podemos imaginar uma variável como uma caixa
        utilizada para guardar uma informação.

    </p>

    <p>

        O tipo de dado funciona como uma etiqueta
        que informa o que pode ser colocado dentro
        dessa caixa.

    </p>

    <p>

        Observe o exemplo:

    </p>

    <pre class="codigo-java"><code>int idade = 17;</code></pre>

    <p>

        Nesse código, o tipo
        <strong>int</strong>
        informa que a variável
        <strong>idade</strong>
        armazenará um número inteiro.

    </p>

    <p>

        Já neste exemplo:

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";</code></pre>

    <p>

        O tipo <strong>String</strong> informa
        que a variável armazenará um texto.

    </p>

    <div class="aula-destaque">

        <strong>

            Pense assim:

        </strong>

        <p>

            Cada variável possui um nome, um tipo
            e um valor armazenado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| IMPORTÂNCIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Por que os tipos são importantes?

    </h2>

    <p>

        Os tipos ajudam o Java a entender como
        cada informação deverá ser armazenada
        e utilizada.

    </p>

    <p>

        Por exemplo, números podem ser utilizados
        em cálculos.

    </p>

    <pre class="codigo-java"><code>int idade = 17;
int novaIdade = idade + 1;</code></pre>

    <p>

        Já textos podem ser utilizados para guardar
        nomes, mensagens e outras informações escritas.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";
String cidade = "Rio Grande";</code></pre>

    <p>

        Quando escolhemos o tipo correto, o programa
        consegue trabalhar com a informação de maneira
        adequada.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PRIMITIVOS E STRING
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Tipos primitivos e String

    </h2>

    <p>

        Java possui tipos chamados
        <strong>primitivos</strong>.

    </p>

    <p>

        Eles representam valores básicos da linguagem,
        como números, caracteres e valores lógicos.

    </p>

    <p>

        Alguns exemplos são:

    </p>

    <pre class="codigo-java"><code>int idade = 17;
double altura = 1.68;
char inicial = 'R';
boolean estudante = true;</code></pre>

    <p>

        O tipo <strong>String</strong> não é um tipo
        primitivo. Ele é uma classe utilizada para
        trabalhar com textos.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";</code></pre>

    <div class="aula-destaque">

        <strong>

            Para esta aula:

        </strong>

        <p>

            O mais importante é aprender qual tipo
            utilizar para cada informação.

        </p>

        <p>

            A diferença entre tipos primitivos e
            classes será compreendida com mais detalhes
            durante o estudo de orientação a objetos.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PRINCIPAIS TIPOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Principais tipos utilizados no início

    </h2>

    <p>

        Java possui vários tipos de dados.

    </p>

    <p>

        Nesta aula, vamos estudar cinco tipos muito
        utilizados por quem está começando.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Tipo</span>
            <span>Utilização</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>int</span>
            <span>Números inteiros</span>
            <span>17</span>

        </div>

        <div class="aula-tabela-linha">

            <span>double</span>
            <span>Números decimais</span>
            <span>1.68</span>

        </div>

        <div class="aula-tabela-linha">

            <span>String</span>
            <span>Textos</span>
            <span>"Java"</span>

        </div>

        <div class="aula-tabela-linha">

            <span>char</span>
            <span>Um único caractere</span>
            <span>'J'</span>

        </div>

        <div class="aula-tabela-linha">

            <span>boolean</span>
            <span>Verdadeiro ou falso</span>
            <span>true</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| INT
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        int — números inteiros

    </h2>

    <p>

        O tipo <strong>int</strong> é utilizado
        para armazenar números inteiros.

    </p>

    <p>

        Números inteiros não possuem casas decimais.

    </p>

    <pre class="codigo-java"><code>int idade = 17;
int quantidade = 10;
int temperatura = -5;
int pontos = 0;</code></pre>

    <p>

        O tipo int pode armazenar:

    </p>

    <ul>

        <li>números positivos;</li>

        <li>números negativos;</li>

        <li>o número zero.</li>

    </ul>

    <div class="aula-destaque">

        <strong>

            Utilize int quando:

        </strong>

        <p>

            A informação representar uma quantidade
            inteira, como idade, número de produtos,
            pontuação ou quantidade de alunos.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| DOUBLE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        double — números decimais

    </h2>

    <p>

        O tipo <strong>double</strong> é utilizado
        para armazenar números com casas decimais.

    </p>

    <pre class="codigo-java"><code>double altura = 1.68;
double preco = 19.90;
double nota = 9.5;</code></pre>

    <p>

        Ele pode ser utilizado para representar
        valores como:

    </p>

    <ul>

        <li>altura;</li>

        <li>peso;</li>

        <li>preço;</li>

        <li>nota;</li>

        <li>temperatura com casas decimais.</li>

    </ul>

    <p>

        Nos códigos Java, normalmente utilizamos
        o ponto para separar a parte inteira
        da parte decimal.

    </p>

    <pre class="codigo-java"><code>double altura = 1.68;</code></pre>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            Dentro do código, escrevemos
            <strong>1.68</strong>
            em vez de
            <strong>1,68</strong>.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| STRING
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        String — textos

    </h2>

    <p>

        O tipo <strong>String</strong> é utilizado
        para armazenar textos.

    </p>

    <p>

        Podemos guardar nomes, cidades, mensagens,
        endereços e frases completas.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";
String cidade = "Rio Grande";
String curso = "Informática";
String mensagem = "Estou aprendendo Java!";</code></pre>

    <p>

        Textos do tipo String devem ser escritos
        entre aspas duplas.

    </p>

    <pre class="codigo-java"><code>String linguagem = "Java";</code></pre>

    <div class="aula-destaque">

        <strong>

            Observe a escrita:

        </strong>

        <p>

            O nome <strong>String</strong> começa
            com letra maiúscula.

        </p>

        <p>

            Isso acontece porque String é uma classe
            da linguagem Java.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CHAR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        char — um único caractere

    </h2>

    <p>

        O tipo <strong>char</strong> é utilizado
        para armazenar apenas um caractere.

    </p>

    <pre class="codigo-java"><code>char inicial = 'R';
char letra = 'A';
char simbolo = '@';
char numero = '5';</code></pre>

    <p>

        Um caractere pode ser uma letra, um número
        ou um símbolo.

    </p>

    <p>

        O valor do tipo char deve ficar entre
        aspas simples.

    </p>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            <strong>'R'</strong>
            é um valor do tipo char.

        </p>

        <p>

            <strong>"R"</strong>
            é um texto do tipo String.

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

        boolean — verdadeiro ou falso

    </h2>

    <p>

        O tipo <strong>boolean</strong> representa
        apenas duas possibilidades.

    </p>

    <ul>

        <li>

            <strong>true</strong>
            representa verdadeiro;

        </li>

        <li>

            <strong>false</strong>
            representa falso.

        </li>

    </ul>

    <pre class="codigo-java"><code>boolean estudante = true;
boolean usuarioLogado = false;
boolean maiorDeIdade = false;</code></pre>

    <p>

        Os valores true e false são escritos
        sem aspas.

    </p>

    <p>

        Esse tipo será muito utilizado quando
        começarmos a trabalhar com decisões
        utilizando <strong>if</strong> e
        <strong>else</strong>.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| STRING E CHAR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        String e char: qual é a diferença?

    </h2>

    <p>

        Essa é uma dúvida comum para quem está
        começando a aprender Java.

    </p>

    <p>

        String pode guardar um conjunto de caracteres.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";</code></pre>

    <p>

        Char guarda apenas um caractere.

    </p>

    <pre class="codigo-java"><code>char inicial = 'R';</code></pre>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Tipo</span>
            <span>Característica</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>String</span>
            <span>Pode possuir vários caracteres</span>
            <span>"Robert"</span>

        </div>

        <div class="aula-tabela-linha">

            <span>char</span>
            <span>Possui apenas um caractere</span>
            <span>'R'</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| NÚMERO E TEXTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Um número também pode ser um texto

    </h2>

    <p>

        Observe estes dois valores:

    </p>

    <pre class="codigo-java"><code>int idade = 17;
String idadeTexto = "17";</code></pre>

    <p>

        Apesar de visualmente parecerem iguais,
        eles possuem tipos diferentes.

    </p>

    <p>

        O primeiro valor é um número inteiro e pode
        ser utilizado diretamente em cálculos.

    </p>

    <pre class="codigo-java"><code>int proximaIdade = idade + 1;</code></pre>

    <p>

        O segundo valor é um texto formado pelos
        caracteres 1 e 7.

    </p>

    <div class="aula-destaque">

        <strong>

            Lembre-se:

        </strong>

        <p>

            As aspas mudam o significado da informação.

        </p>

        <p>

            <strong>17</strong> é um número.

        </p>

        <p>

            <strong>"17"</strong> é um texto.

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

        Utilizando vários tipos de dados

    </h2>

    <p>

        Agora vamos criar um programa que utiliza
        diferentes tipos de dados.

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        String nome = "Robert";
        int idade = 17;
        double altura = 1.68;
        char inicial = 'R';
        boolean estudante = true;

        System.out.println("Nome: " + nome);
        System.out.println("Idade: " + idade);
        System.out.println("Altura: " + altura);
        System.out.println("Inicial: " + inicial);
        System.out.println("É estudante? " + estudante);

    }

}</code></pre>

    <p>

        O resultado será:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert
Idade: 17
Altura: 1.68
Inicial: R
É estudante? true</code></div>

    <p>

        Cada variável possui um tipo adequado
        para a informação armazenada.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ESCOLHENDO O TIPO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como escolher o tipo correto?

    </h2>

    <p>

        Antes de criar uma variável, pense sobre
        qual informação será armazenada.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Informação</span>
            <span>Tipo indicado</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Idade</span>
            <span>int</span>
            <span>17</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Preço</span>
            <span>double</span>
            <span>19.90</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Nome</span>
            <span>String</span>
            <span>"Robert"</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Inicial</span>
            <span>char</span>
            <span>'R'</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Está logado?</span>
            <span>boolean</span>
            <span>true</span>

        </div>

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

        Alguns erros acontecem quando o valor
        utilizado não combina com o tipo da variável.

    </p>

    <h3>

        Colocar texto em uma variável int

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>int idade = "17";</code></pre>

    <p>

        O tipo int espera um número inteiro,
        e não um texto.

    </p>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>int idade = 17;</code></pre>

    <h3>

        Utilizar vírgula em um número decimal

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>double altura = 1,68;</code></pre>

    <p>

        No código Java, utilizamos ponto:

    </p>

    <pre class="codigo-java"><code>double altura = 1.68;</code></pre>

    <h3>

        Utilizar aspas duplas em um char

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>char inicial = "R";</code></pre>

    <p>

        Char utiliza aspas simples:

    </p>

    <pre class="codigo-java"><code>char inicial = 'R';</code></pre>

    <h3>

        Colocar vários caracteres em um char

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>char nome = 'Robert';</code></pre>

    <p>

        Para guardar uma palavra, utilizamos String:

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";</code></pre>

    <h3>

        Utilizar aspas em um boolean

    </h3>

    <p>

        Este código cria um texto, e não um valor
        booleano:

    </p>

    <pre class="codigo-java"><code>boolean ativo = "true";</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>boolean ativo = true;</code></pre>

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

        Vamos representar algumas informações
        sobre um produto.

    </p>

    <pre class="codigo-java"><code>String nomeProduto = "Teclado";
double precoProduto = 89.90;
int quantidadeEstoque = 12;
char categoria = 'A';
boolean disponivel = true;</code></pre>

    <p>

        Depois, podemos exibir os valores:

    </p>

    <pre class="codigo-java"><code>System.out.println("Produto: " + nomeProduto);
System.out.println("Preço: " + precoProduto);
System.out.println("Quantidade: " + quantidadeEstoque);
System.out.println("Categoria: " + categoria);
System.out.println("Disponível: " + disponivel);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Produto: Teclado
Preço: 89.9
Quantidade: 12
Categoria: A
Disponível: true</code></div>

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

        Crie um programa para representar
        informações sobre um filme.

    </p>

    <p>

        O programa deverá possuir variáveis para:

    </p>

    <ul>

        <li>nome do filme;</li>

        <li>ano de lançamento;</li>

        <li>duração em horas;</li>

        <li>classificação representada por uma letra;</li>

        <li>informação indicando se o filme está disponível.</li>

    </ul>

    <p>

        Escolha o tipo correto para cada variável
        e mostre um resultado semelhante a este:

    </p>

    <div class="resultado-codigo"><code>Filme: Interestelar
Ano: 2014
Duração: 2.49
Classificação: A
Disponível: true</code></div>

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

        Nesta aula, aprendemos que os tipos de dados
        informam ao Java qual categoria de informação
        uma variável poderá armazenar.

    </p>

    <ul>

        <li>

            <strong>int</strong>
            armazena números inteiros;

        </li>

        <li>

            <strong>double</strong>
            armazena números decimais;

        </li>

        <li>

            <strong>String</strong>
            armazena textos;

        </li>

        <li>

            <strong>char</strong>
            armazena um único caractere;

        </li>

        <li>

            <strong>boolean</strong>
            armazena true ou false;

        </li>

        <li>

            String utiliza aspas duplas;

        </li>

        <li>

            char utiliza aspas simples;

        </li>

        <li>

            valores booleanos não utilizam aspas.

        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Cada informação deve ser armazenada
                utilizando um tipo de dado adequado.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como receber
        informações digitadas pelo usuário utilizando
        a classe Scanner.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Escolher o tipo correto é ensinar ao programa
        como cada informação deverá ser entendida."

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

        Próxima aula: Scanner →

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