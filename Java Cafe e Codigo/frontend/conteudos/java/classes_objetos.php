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
                "arraylist"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "atributos_metodos"
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
    content="Aprenda os conceitos de classes e objetos em Java e dê os primeiros passos na Programação Orientada a Objetos."
>

<title>
    Classes e Objetos em Java | Java, Café e Código
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

        AULA 21

    </span>

    <h1>

        Classes e Objetos em Java

    </h1>

    <p>

        Conheça os primeiros conceitos da Programação
        Orientada a Objetos e aprenda como classes
        podem ser utilizadas para criar objetos em Java.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO À POO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Uma nova forma de organizar programas

    </h2>

    <p>

        Até agora, trabalhamos principalmente com
        variáveis, condições, repetições, métodos,
        arrays e listas.

    </p>

    <p>

        Esses recursos continuam sendo utilizados,
        mas agora começaremos uma nova etapa:

    </p>

    <div class="aula-destaque">

        <strong>

            Programação Orientada a Objetos

        </strong>

        <p>

            Também conhecida pela sigla
            <strong>POO</strong>.

        </p>

    </div>

    <p>

        A Programação Orientada a Objetos permite
        organizar um programa utilizando estruturas
        que representam elementos, entidades ou
        conceitos do sistema.

    </p>

    <p>

        Podemos representar, por exemplo:

    </p>

    <ul>

        <li>
            um estudante;
        </li>

        <li>
            um produto;
        </li>

        <li>
            uma conta bancária;
        </li>

        <li>
            um livro;
        </li>

        <li>
            um carro;
        </li>

        <li>
            uma bebida de uma cafeteria.
        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| ANALOGIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Pensando em um exemplo do cotidiano

    </h2>

    <p>

        Imagine uma fábrica de canecas.

    </p>

    <p>

        Antes de produzir milhares de canecas,
        a fábrica possui um modelo que define
        como aquela caneca será criada.

    </p>

    <p>

        Utilizando esse mesmo modelo, podem ser
        produzidas várias canecas diferentes.

    </p>

    <div class="aula-destaque">

        <strong>

            Em Java podemos pensar de forma parecida:

        </strong>

        <p>

            A <strong>classe</strong> funciona como
            o modelo.

        </p>

        <p>

            Os <strong>objetos</strong> são as
            estruturas criadas a partir desse modelo.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CLASSE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é uma classe?

    </h2>

    <p>

        Uma <strong>classe</strong> é uma estrutura
        utilizada como modelo para criar objetos.

    </p>

    <p>

        Ela define como determinado tipo de objeto
        será representado dentro do programa.

    </p>

    <p>

        Podemos criar uma classe chamada
        <strong>Aluno</strong>.

    </p>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno {

}</code></pre>

    <p>

        Mesmo vazia, essa já é uma classe válida.

    </p>

    <p>

        A palavra <strong>class</strong> informa
        ao Java que estamos declarando uma classe.

    </p>

    <p>

        O nome da classe é:

    </p>

    <pre class="codigo-java"><code>Aluno</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| ESTRUTURA DA CLASSE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Estrutura básica de uma classe

    </h2>

    <pre class="codigo-java"><code>public class NomeDaClasse {

}</code></pre>

    <p>

        Vamos observar as principais partes:

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Função</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>public</span>

            <span>
                Define a visibilidade da classe
            </span>

            <span>public</span>

        </div>

        <div class="aula-tabela-linha">

            <span>class</span>

            <span>
                Informa que estamos criando uma classe
            </span>

            <span>class</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Nome</span>

            <span>
                Identifica a classe
            </span>

            <span>Aluno</span>

        </div>

        <div class="aula-tabela-linha">

            <span>{ }</span>

            <span>
                Delimitam o conteúdo da classe
            </span>

            <span>{ }</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| NOMES DE CLASSES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como nomear uma classe?

    </h2>

    <p>

        Em Java, os nomes de classes normalmente
        começam com letra maiúscula.

    </p>

    <p>

        Bons exemplos:

    </p>

    <pre class="codigo-java"><code>Aluno
Produto
ContaBancaria
Livro
Cafe
Pedido</code></pre>

    <p>

        Quando o nome possui mais de uma palavra,
        cada palavra normalmente começa com
        letra maiúscula.

    </p>

    <pre class="codigo-java"><code>ContaBancaria

ProdutoDigital

PedidoCliente</code></pre>

    <div class="aula-destaque">

        <strong>

            Esse padrão é conhecido como PascalCase.

        </strong>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ARQUIVO DA CLASSE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O arquivo deve possuir o nome da classe

    </h2>

    <p>

        Quando criamos uma classe pública em Java,
        o arquivo deve possuir o mesmo nome da classe.

    </p>

    <p>

        Se temos:

    </p>

    <pre class="codigo-java"><code>public class Aluno {

}</code></pre>

    <p>

        o arquivo deverá se chamar:

    </p>

    <div class="resultado-codigo"><code>Aluno.java</code></div>

    <p>

        Para:

    </p>

    <pre class="codigo-java"><code>public class Produto {

}</code></pre>

    <p>

        utilizamos:

    </p>

    <div class="resultado-codigo"><code>Produto.java</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| OBJETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é um objeto?

    </h2>

    <p>

        Um <strong>objeto</strong> é uma instância
        criada a partir de uma classe.

    </p>

    <p>

        A classe define o modelo.

    </p>

    <p>

        O objeto é criado utilizando esse modelo.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Conceito</span>
            <span>Significado</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Classe</span>

            <span>
                Modelo utilizado para criar objetos
            </span>

            <span>Aluno</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Objeto</span>

            <span>
                Instância criada a partir da classe
            </span>

            <span>aluno1</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CRIANDO OBJETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando nosso primeiro objeto

    </h2>

    <p>

        Primeiro criamos a classe.

    </p>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno {

}</code></pre>

    <p>

        Depois podemos criar um objeto dessa classe.

    </p>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Aluno aluno1 =
            new Aluno();

    }

}</code></pre>

    <p>

        Nesse momento, foi criado um objeto chamado
        <strong>aluno1</strong> a partir da classe
        <strong>Aluno</strong>.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ENTENDENDO A CRIAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Entendendo a criação do objeto

    </h2>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();</code></pre>

    <p>

        Podemos dividir essa instrução em partes.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Significado</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Tipo</span>

            <span>
                Informa de qual classe será o objeto
            </span>

            <span>Aluno</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Variável</span>

            <span>
                Guarda a referência para o objeto
            </span>

            <span>aluno1</span>

        </div>

        <div class="aula-tabela-linha">

            <span>new</span>

            <span>
                Solicita a criação de um novo objeto
            </span>

            <span>new</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Aluno()</span>

            <span>
                Realiza a criação do objeto da classe
            </span>

            <span>Aluno()</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| NEW
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Para que serve new?

    </h2>

    <p>

        A palavra <strong>new</strong> é utilizada
        para criar um novo objeto.

    </p>

    <pre class="codigo-java"><code>new Aluno();</code></pre>

    <p>

        Podemos imaginar que estamos dizendo:

    </p>

    <div class="aula-destaque">

        <p>

            “Java, crie um novo objeto utilizando
            a classe Aluno como modelo.”

        </p>

    </div>

    <p>

        Depois guardamos uma referência para esse
        objeto em uma variável:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 =
    new Aluno();</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| INSTÂNCIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que significa instância?

    </h2>

    <p>

        Quando um objeto é criado a partir de uma
        classe, dizemos que ele é uma
        <strong>instância</strong> daquela classe.

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 =
    new Aluno();</code></pre>

    <p>

        Podemos dizer:

    </p>

    <div class="aula-destaque">

        <p>

            aluno1 é um objeto.

        </p>

        <p>

            aluno1 é uma instância da classe Aluno.

        </p>

    </div>

    <p>

        As duas formas estão relacionadas ao mesmo
        objeto criado no programa.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIOS OBJETOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando vários objetos

    </h2>

    <p>

        Uma mesma classe pode ser utilizada para
        criar vários objetos.

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 =
    new Aluno();

Aluno aluno2 =
    new Aluno();

Aluno aluno3 =
    new Aluno();</code></pre>

    <p>

        Nesse exemplo, criamos três objetos diferentes.

    </p>

    <div class="resultado-codigo"><code>Classe: Aluno

Objeto 1: aluno1
Objeto 2: aluno2
Objeto 3: aluno3</code></div>

    <p>

        Todos foram criados utilizando a classe
        Aluno como modelo.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ANALOGIA DOS OBJETOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Uma classe pode criar muitos objetos

    </h2>

    <p>

        Pense novamente na ideia de um molde.

    </p>

    <p>

        Um único molde pode ser utilizado várias vezes.

    </p>

    <p>

        Da mesma maneira, uma classe pode ser utilizada
        para criar diversos objetos.

    </p>

    <div class="aula-destaque">

        <strong>

            Classe:

        </strong>

        <p>

            Define o modelo.

        </p>

        <strong>

            Objetos:

        </strong>

        <p>

            São as instâncias criadas utilizando
            aquele modelo.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO PRODUTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo com Produto

    </h2>

    <p>

        Podemos criar uma classe para representar
        produtos dentro de um sistema.

    </p>

    <h3>

        Arquivo Produto.java

    </h3>

    <pre class="codigo-java"><code>public class Produto {

}</code></pre>

    <p>

        Depois podemos criar vários produtos.

    </p>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Produto produto1 =
            new Produto();

        Produto produto2 =
            new Produto();

        Produto produto3 =
            new Produto();

    }

}</code></pre>

    <p>

        Temos uma classe chamada Produto e três
        objetos criados a partir dela.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO CAFETERIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo no Java, Café e Código

    </h2>

    <p>

        Imagine que estamos desenvolvendo um sistema
        para uma cafeteria.

    </p>

    <p>

        Poderíamos criar uma classe chamada
        <strong>Cafe</strong>.

    </p>

    <h3>

        Arquivo Cafe.java

    </h3>

    <pre class="codigo-java"><code>public class Cafe {

}</code></pre>

    <p>

        A partir dela, podemos criar vários objetos.

    </p>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Cafe cafe1 =
            new Cafe();

        Cafe cafe2 =
            new Cafe();

        Cafe cafe3 =
            new Cafe();

    }

}</code></pre>

    <p>

        Esses três objetos pertencem ao mesmo tipo:

    </p>

    <div class="resultado-codigo"><code>Cafe</code></div>

    <p>

        Na próxima aula, aprenderemos como adicionar
        características e comportamentos a esses objetos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CLASSE X OBJETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Classe e objeto não são a mesma coisa

    </h2>

    <p>

        Essa diferença é muito importante na
        Programação Orientada a Objetos.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Classe</span>
            <span>Objeto</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Modelo</span>
            <span>Instância do modelo</span>
            <span>Aluno → aluno1</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Define um tipo</span>
            <span>Pertence a esse tipo</span>
            <span>Produto → produto1</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Pode criar vários objetos</span>
            <span>Representa uma criação específica</span>
            <span>Cafe → cafe1</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MAIN E OUTRAS CLASSES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Main e nossas próprias classes

    </h2>

    <p>

        Até agora, utilizamos principalmente uma
        classe chamada Main.

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

    }

}</code></pre>

    <p>

        O método main continua sendo o ponto inicial
        da aplicação.

    </p>

    <p>

        Porém, agora podemos criar outras classes
        para representar diferentes elementos.

    </p>

    <div class="resultado-codigo"><code>Main.java
Aluno.java
Produto.java
Cafe.java</code></div>

    <p>

        O Main pode então criar objetos utilizando
        essas classes.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ORGANIZAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Organização em arquivos

    </h2>

    <p>

        Imagine que nosso projeto possui:

    </p>

    <div class="resultado-codigo"><code>Aluno.java
Produto.java
Main.java</code></div>

    <p>

        No arquivo Aluno.java:

    </p>

    <pre class="codigo-java"><code>public class Aluno {

}</code></pre>

    <p>

        No arquivo Produto.java:

    </p>

    <pre class="codigo-java"><code>public class Produto {

}</code></pre>

    <p>

        E no Main.java:

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Aluno aluno =
            new Aluno();

        Produto produto =
            new Produto();

    }

}</code></pre>

    <p>

        Dessa forma, cada classe possui sua própria
        responsabilidade dentro do projeto.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| REFERÊNCIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A variável guarda uma referência

    </h2>

    <p>

        Observe:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 =
    new Aluno();</code></pre>

    <p>

        A variável aluno1 é utilizada para acessar
        o objeto criado.

    </p>

    <p>

        De forma introdutória, podemos imaginar
        aluno1 como uma forma de localizar e utilizar
        aquele objeto durante a execução do programa.

    </p>

    <div class="aula-destaque">

        <strong>

            Por enquanto:

        </strong>

        <p>

            Pense na variável como a referência
            que utilizaremos para trabalhar com
            determinado objeto.

        </p>

        <p>

            O funcionamento da memória será
            aprofundado em estudos mais avançados.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| DECLARAR SEM CRIAR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Declarar uma variável não cria o objeto

    </h2>

    <p>

        Observe esta instrução:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1;</code></pre>

    <p>

        Aqui apenas declaramos uma variável
        do tipo Aluno.

    </p>

    <p>

        Ainda não criamos um objeto.

    </p>

    <p>

        A criação acontece com:

    </p>

    <pre class="codigo-java"><code>aluno1 =
    new Aluno();</code></pre>

    <p>

        Também podemos fazer tudo na mesma linha:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 =
    new Aluno();</code></pre>

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

        Nome do arquivo diferente da classe pública

    </h3>

    <p>

        Se temos:

    </p>

    <pre class="codigo-java"><code>public class Aluno {

}</code></pre>

    <p>

        o arquivo deverá se chamar:

    </p>

    <div class="resultado-codigo"><code>Aluno.java</code></div>


    <h3>

        Esquecer a palavra new

    </h3>

    <p>

        Esta criação está incorreta:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 =
    Aluno();</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 =
    new Aluno();</code></pre>


    <h3>

        Confundir classe com objeto

    </h3>

    <p>

        Nesta linha:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 =
    new Aluno();</code></pre>

    <p>

        <strong>Aluno</strong> é a classe.

    </p>

    <p>

        <strong>aluno1</strong> é a variável utilizada
        para referenciar o objeto criado.

    </p>


    <h3>

        Criar um objeto de uma classe inexistente

    </h3>

    <pre class="codigo-java"><code>Cliente cliente =
    new Cliente();</code></pre>

    <p>

        Para esse exemplo funcionar, uma classe
        Cliente precisa existir no projeto.

    </p>


    <h3>

        Esquecer os parênteses

    </h3>

    <p>

        Esta criação está incorreta:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 =
    new Aluno;</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 =
    new Aluno();</code></pre>

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

        Vamos criar uma classe para representar
        um livro.

    </p>

    <h3>

        Arquivo Livro.java

    </h3>

    <pre class="codigo-java"><code>public class Livro {

}</code></pre>

    <p>

        Agora criaremos três objetos.

    </p>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Livro livro1 =
            new Livro();

        Livro livro2 =
            new Livro();

        Livro livro3 =
            new Livro();

    }

}</code></pre>

    <p>

        Resultado conceitual:

    </p>

    <div class="resultado-codigo"><code>Classe: Livro

Objetos criados:
livro1
livro2
livro3</code></div>

    <p>

        Os três objetos foram criados utilizando
        a mesma classe Livro.

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

        Crie três classes:

    </p>

    <ul>

        <li>
            Carro;
        </li>

        <li>
            Pessoa;
        </li>

        <li>
            Computador.
        </li>

    </ul>

    <p>

        Cada classe deverá ficar em seu próprio arquivo.

    </p>

    <p>

        Depois, no Main.java, crie dois objetos
        de cada classe.

    </p>

    <p>

        A estrutura pode seguir esta ideia:

    </p>

    <pre class="codigo-java"><code>Carro carro1 =
    new Carro();

Carro carro2 =
    new Carro();


Pessoa pessoa1 =
    new Pessoa();

Pessoa pessoa2 =
    new Pessoa();


Computador computador1 =
    new Computador();

Computador computador2 =
    new Computador();</code></pre>

    <div class="aula-destaque">

        <strong>

            Neste desafio:

        </strong>

        <p>

            Não precisamos adicionar características
            ou comportamentos ainda.

        </p>

        <p>

            O objetivo é compreender a relação
            entre classe e objeto.

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

        Nesta aula, iniciamos o estudo da
        Programação Orientada a Objetos.

    </p>

    <ul>

        <li>

            POO significa Programação Orientada
            a Objetos;

        </li>

        <li>

            uma classe funciona como um modelo;

        </li>

        <li>

            utilizamos class para declarar uma classe;

        </li>

        <li>

            classes normalmente utilizam PascalCase;

        </li>

        <li>

            uma classe pública deve possuir
            um arquivo com o mesmo nome;

        </li>

        <li>

            objetos são criados a partir de classes;

        </li>

        <li>

            um objeto também pode ser chamado
            de instância de uma classe;

        </li>

        <li>

            utilizamos new para criar um novo objeto;

        </li>

        <li>

            uma classe pode ser utilizada para
            criar vários objetos;

        </li>

        <li>

            declarar uma variável de um tipo
            não significa criar o objeto;

        </li>

        <li>

            o método main continua sendo
            o ponto inicial da aplicação;

        </li>

        <li>

            podemos criar nossas próprias classes
            para organizar diferentes elementos
            do programa.

        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                A classe define o modelo,
                enquanto o objeto é uma instância
                criada a partir desse modelo.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como adicionar
        características e comportamentos aos objetos
        utilizando atributos e métodos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Uma classe define o modelo.
        Um objeto transforma esse modelo
        em uma instância que pode ser utilizada
        pelo programa."

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

        Próxima aula: Atributos e Métodos →

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