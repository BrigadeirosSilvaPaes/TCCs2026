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
                "classes_objetos"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "construtores"
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
    content="Aprenda como criar atributos e métodos em classes Java para representar características e comportamentos dos objetos."
>

<title>
    Atributos e Métodos em Java | Java, Café e Código
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

        AULA 22

    </span>

    <h1>

        Atributos e Métodos em Java

    </h1>

    <p>

        Aprenda como definir as características
        e os comportamentos que pertencem aos objetos
        criados a partir de uma classe.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Características e comportamentos

    </h2>

    <p>

        Na aula anterior, aprendemos que uma
        <strong>classe</strong> funciona como um modelo
        utilizado para criar objetos.

    </p>

    <p>

        Agora vamos aprofundar esse conhecimento
        estudando dois elementos fundamentais da
        Programação Orientada a Objetos:

    </p>

    <ul>

        <li>

            <strong>atributos;</strong>

        </li>

        <li>

            <strong>métodos.</strong>

        </li>

    </ul>

    <p>

        Os atributos representam as informações
        e características de um objeto.

    </p>

    <p>

        Os métodos representam as ações e comportamentos
        que um objeto pode realizar.

    </p>

    <div class="aula-destaque">

        <strong>

            Forma simples de lembrar:

        </strong>

        <p>

            <strong>Atributos:</strong>
            aquilo que o objeto possui.

        </p>

        <p>

            <strong>Métodos:</strong>
            aquilo que o objeto pode fazer.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO DO MUNDO REAL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo com um estudante

    </h2>

    <p>

        Imagine que queremos representar um estudante
        dentro de um programa.

    </p>

    <p>

        Esse estudante pode possuir características como:

    </p>

    <ul>

        <li>

            nome;

        </li>

        <li>

            idade;

        </li>

        <li>

            curso;

        </li>

        <li>

            nota.

        </li>

    </ul>

    <p>

        Ele também pode realizar ações como:

    </p>

    <ul>

        <li>

            estudar;

        </li>

        <li>

            apresentar seus dados;

        </li>

        <li>

            verificar sua situação;

        </li>

        <li>

            alterar seu curso.

        </li>

    </ul>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>

                Elemento

            </span>

            <span>

                Representação

            </span>

            <span>

                Exemplos

            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>

                Características

            </span>

            <span>

                Atributos

            </span>

            <span>

                nome, idade e curso

            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>

                Comportamentos

            </span>

            <span>

                Métodos

            </span>

            <span>

                estudar() e apresentar()

            </span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ATRIBUTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que são atributos?

    </h2>

    <p>

        Um <strong>atributo</strong> é uma variável
        declarada dentro de uma classe e fora dos métodos.

    </p>

    <p>

        O atributo representa uma informação que pertence
        aos objetos criados a partir daquela classe.

    </p>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;

    String curso;

}</code></pre>

    <p>

        A classe Aluno possui três atributos.

    </p>

    <ul>

        <li>

            <strong>nome:</strong>
            armazena o nome do aluno;

        </li>

        <li>

            <strong>idade:</strong>
            armazena a idade do aluno;

        </li>

        <li>

            <strong>curso:</strong>
            armazena o curso do aluno.

        </li>

    </ul>

    <p>

        Todo objeto criado a partir dessa classe
        poderá possuir valores próprios para esses
        atributos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| UTILIZANDO ATRIBUTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando os atributos em um objeto

    </h2>

    <p>

        Primeiro, criamos um objeto da classe Aluno.

    </p>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Aluno aluno1 = new Aluno();

        aluno1.nome = "Robert";
        aluno1.idade = 17;
        aluno1.curso = "Informática";

    }

}</code></pre>

    <p>

        O operador <strong>.</strong> permite acessar
        algo que pertence ao objeto.

    </p>

    <pre class="codigo-java"><code>aluno1.nome

aluno1.idade

aluno1.curso</code></pre>

    <p>

        Podemos exibir essas informações:

    </p>

    <pre class="codigo-java"><code>System.out.println(
    "Nome: " + aluno1.nome
);

System.out.println(
    "Idade: " + aluno1.idade
);

System.out.println(
    "Curso: " + aluno1.curso
);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert
Idade: 17
Curso: Informática</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| OBJETOS INDEPENDENTES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Cada objeto possui seus próprios valores

    </h2>

    <p>

        Podemos criar vários objetos utilizando
        a mesma classe como modelo.

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();

aluno1.nome = "Robert";
aluno1.idade = 17;
aluno1.curso = "Informática";


Aluno aluno2 = new Aluno();

aluno2.nome = "Natanael";
aluno2.idade = 18;
aluno2.curso = "Informática";</code></pre>

    <p>

        Os objetos aluno1 e aluno2 pertencem à mesma
        classe, mas armazenam valores diferentes.

    </p>

    <pre class="codigo-java"><code>System.out.println(aluno1.nome);

System.out.println(aluno2.nome);</code></pre>

    <div class="resultado-codigo"><code>Robert
Natanael</code></div>

    <div class="aula-destaque">

        <strong>

            Objetos independentes:

        </strong>

        <p>

            Alterar um atributo de aluno1 não altera
            automaticamente os atributos de aluno2.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que são métodos?

    </h2>

    <p>

        Um <strong>método</strong> é um bloco de código
        criado para executar uma tarefa.

    </p>

    <p>

        Dentro de uma classe, os métodos representam
        os comportamentos que os objetos podem realizar.

    </p>

    <p>

        Podemos adicionar um método chamado
        <strong>estudar()</strong> na classe Aluno.

    </p>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;

    String curso;


    void estudar() {

        System.out.println(
            "O aluno está estudando."
        );

    }

}</code></pre>

    <p>

        A palavra <strong>void</strong> indica que
        o método executa uma ação, mas não devolve
        nenhum valor.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CHAMANDO MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Chamando um método do objeto

    </h2>

    <p>

        Para executar um método pertencente a um objeto,
        utilizamos a seguinte estrutura:

    </p>

    <pre class="codigo-java"><code>nomeDoObjeto.nomeDoMetodo();</code></pre>

    <p>

        No nosso exemplo:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();

aluno1.estudar();</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>O aluno está estudando.</code></div>

    <p>

        Estamos solicitando que o objeto
        <strong>aluno1</strong> execute o método
        <strong>estudar()</strong>.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODOS DE INSTÂNCIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos pertencentes aos objetos

    </h2>

    <p>

        Nas aulas anteriores, criamos métodos utilizando
        a palavra <strong>static</strong>.

    </p>

    <pre class="codigo-java"><code>static void mostrarMensagem() {

    System.out.println("Olá!");

}</code></pre>

    <p>

        Agora estamos criando métodos que pertencem
        aos objetos.

    </p>

    <pre class="codigo-java"><code>void estudar() {

    System.out.println(
        "O aluno está estudando."
    );

}</code></pre>

    <p>

        Esse tipo de método é chamado de
        <strong>método de instância</strong>.

    </p>

    <p>

        Para executá-lo, precisamos criar um objeto.

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();

aluno1.estudar();</code></pre>

    <div class="aula-destaque">

        <strong>

            Neste momento:

        </strong>

        <p>

            Métodos sem static pertencem aos objetos
            criados a partir da classe.

        </p>

        <p>

            O método main() continua utilizando static
            porque é o ponto inicial da aplicação.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODOS UTILIZANDO ATRIBUTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Um método pode utilizar os atributos

    </h2>

    <p>

        Os métodos de um objeto podem acessar
        os atributos pertencentes àquele objeto.

    </p>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;

    String curso;


    void estudar() {

        System.out.println(
            nome + " está estudando."
        );

    }

}</code></pre>

    <p>

        Agora podemos criar dois objetos:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();

aluno1.nome = "Robert";


Aluno aluno2 = new Aluno();

aluno2.nome = "Natanael";


aluno1.estudar();

aluno2.estudar();</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Robert está estudando.
Natanael está estudando.</code></div>

    <p>

        O mesmo método utiliza o nome pertencente
        a cada objeto.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| EXIBINDO DADOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando um método para exibir os dados

    </h2>

    <p>

        Podemos colocar dentro da classe a responsabilidade
        de exibir as informações do aluno.

    </p>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;

    String curso;


    void exibirInformacoes() {

        System.out.println(
            "Nome: " + nome
        );

        System.out.println(
            "Idade: " + idade
        );

        System.out.println(
            "Curso: " + curso
        );

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Aluno aluno1 = new Aluno();

        aluno1.nome = "Robert";
        aluno1.idade = 17;
        aluno1.curso = "Informática";

        aluno1.exibirInformacoes();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert
Idade: 17
Curso: Informática</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIOS MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Uma classe pode possuir vários métodos

    </h2>

    <p>

        Podemos criar diferentes comportamentos
        dentro da mesma classe.

    </p>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;

    String curso;


    void estudar() {

        System.out.println(
            nome + " está estudando."
        );

    }


    void apresentarTrabalho() {

        System.out.println(
            nome
            + " está apresentando um trabalho."
        );

    }


    void exibirInformacoes() {

        System.out.println(
            "Nome: " + nome
        );

        System.out.println(
            "Idade: " + idade
        );

        System.out.println(
            "Curso: " + curso
        );

    }

}</code></pre>

    <p>

        Podemos executar cada método utilizando
        um objeto.

    </p>

    <pre class="codigo-java"><code>aluno1.estudar();

aluno1.apresentarTrabalho();

aluno1.exibirInformacoes();</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODOS COM PARÂMETROS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos com parâmetros

    </h2>

    <p>

        Um método pertencente a uma classe também
        pode receber parâmetros.

    </p>

    <p>

        Podemos criar um método que altera o curso
        do aluno.

    </p>

    <pre class="codigo-java"><code>void alterarCurso(String novoCurso) {

    curso = novoCurso;

}</code></pre>

    <p>

        Agora podemos utilizar o método:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();

aluno1.nome = "Robert";

aluno1.curso = "Informática";

aluno1.alterarCurso(
    "Desenvolvimento de Sistemas"
);

System.out.println(aluno1.curso);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Desenvolvimento de Sistemas</code></div>

    <p>

        O argumento enviado para o método é recebido
        pelo parâmetro <strong>novoCurso</strong>.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODOS COM RETORNO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos que retornam valores

    </h2>

    <p>

        Também podemos criar métodos que utilizam
        os atributos e devolvem um resultado.

    </p>

    <p>

        Por exemplo, podemos verificar se o aluno
        é maior de idade.

    </p>

    <pre class="codigo-java"><code>boolean maiorDeIdade() {

    return idade &gt;= 18;

}</code></pre>

    <p>

        Utilizando o método:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();

aluno1.idade = 17;

boolean resultado =
    aluno1.maiorDeIdade();

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>false</code></div>

    <p>

        Como a idade é 17, o método retorna false.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PRODUTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo com a classe Produto

    </h2>

    <p>

        Vamos criar uma classe que representa
        um produto.

    </p>

    <h3>

        Arquivo Produto.java

    </h3>

    <pre class="codigo-java"><code>public class Produto {

    String nome;

    double preco;

    int quantidade;


    double calcularTotal() {

        return preco * quantidade;

    }


    void exibirInformacoes() {

        System.out.println(
            "Produto: " + nome
        );

        System.out.println(
            "Preço: R$ " + preco
        );

        System.out.println(
            "Quantidade: " + quantidade
        );

        System.out.println(
            "Total: R$ " + calcularTotal()
        );

    }

}</code></pre>

    <p>

        A classe possui três atributos:

    </p>

    <ul>

        <li>

            nome;

        </li>

        <li>

            preço;

        </li>

        <li>

            quantidade.

        </li>

    </ul>

    <p>

        Ela também possui dois métodos:

    </p>

    <ul>

        <li>

            <strong>calcularTotal():</strong>
            calcula e retorna o valor total;

        </li>

        <li>

            <strong>exibirInformacoes():</strong>
            apresenta os dados do produto.

        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| UTILIZANDO PRODUTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando o objeto Produto

    </h2>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Produto produto1 =
            new Produto();

        produto1.nome = "Café";
        produto1.preco = 18.50;
        produto1.quantidade = 3;

        produto1.exibirInformacoes();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Produto: Café
Preço: R$ 18.5
Quantidade: 3
Total: R$ 55.5</code></div>

    <p>

        O método calcularTotal() utiliza os atributos
        preço e quantidade pertencentes ao próprio objeto.

    </p>

    <p>

        Depois, exibirInformacoes() chama calcularTotal()
        para mostrar o resultado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODOS CHAMANDO MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Um método pode chamar outro método

    </h2>

    <p>

        Observe novamente este trecho:

    </p>

    <pre class="codigo-java"><code>void exibirInformacoes() {

    System.out.println(
        "Produto: " + nome
    );

    System.out.println(
        "Total: R$ " + calcularTotal()
    );

}</code></pre>

    <p>

        O método exibirInformacoes() chama
        calcularTotal().

    </p>

    <p>

        Isso evita repetir a operação de multiplicação
        em diferentes partes da classe.

    </p>

    <div class="aula-destaque">

        <strong>

            Organização:

        </strong>

        <p>

            Cada método pode ficar responsável
            por uma tarefa específica.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ATRIBUTOS E VARIÁVEIS LOCAIS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Atributos e variáveis locais

    </h2>

    <p>

        Um atributo é declarado dentro da classe,
        mas fora dos métodos.

    </p>

    <pre class="codigo-java"><code>public class Produto {

    double preco;

    int quantidade;

}</code></pre>

    <p>

        Uma variável local é declarada dentro
        de um método.

    </p>

    <pre class="codigo-java"><code>double calcularTotal() {

    double total =
        preco * quantidade;

    return total;

}</code></pre>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Elemento</span>
            <span>Local</span>
            <span>Utilização</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Atributo</span>

            <span>
                Dentro da classe
            </span>

            <span>
                Pertence ao objeto
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>Variável local</span>

            <span>
                Dentro de um método
            </span>

            <span>
                Existe durante a execução do método
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>Parâmetro</span>

            <span>
                Nos parênteses do método
            </span>

            <span>
                Recebe um argumento
            </span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO DA CAFETERIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: cafeteria

    </h2>

    <p>

        Vamos criar uma classe para representar
        uma bebida da cafeteria.

    </p>

    <h3>

        Arquivo Cafe.java

    </h3>

    <pre class="codigo-java"><code>public class Cafe {

    String nome;

    String tamanho;

    double preco;

    boolean disponivel;


    void exibirInformacoes() {

        System.out.println(
            "Café: " + nome
        );

        System.out.println(
            "Tamanho: " + tamanho
        );

        System.out.println(
            "Preço: R$ " + preco
        );

        System.out.println(
            "Disponível: " + disponivel
        );

    }


    void alterarPreco(double novoPreco) {

        if (novoPreco &gt; 0) {

            preco = novoPreco;

        }

    }


    boolean podeSerVendido() {

        return disponivel
            &amp;&amp; preco &gt; 0;

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Cafe cafe1 = new Cafe();

        cafe1.nome = "Café com leite";
        cafe1.tamanho = "Médio";
        cafe1.preco = 8.50;
        cafe1.disponivel = true;

        cafe1.exibirInformacoes();

        System.out.println();

        System.out.println(
            "Pode ser vendido: "
            + cafe1.podeSerVendido()
        );

        cafe1.alterarPreco(9.00);

        System.out.println();

        System.out.println(
            "Novo preço: R$ "
            + cafe1.preco
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Café: Café com leite
Tamanho: Médio
Preço: R$ 8.5
Disponível: true

Pode ser vendido: true

Novo preço: R$ 9.0</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| ORGANIZAÇÃO DOS ARQUIVOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Organização dos arquivos

    </h2>

    <p>

        Cada classe pública deve ficar em seu
        próprio arquivo.

    </p>

    <div class="resultado-codigo"><code>Aluno.java
Produto.java
Cafe.java
Main.java</code></div>

    <p>

        O arquivo deve possuir o mesmo nome
        da classe pública.

    </p>

    <pre class="codigo-java"><code>public class Produto {

}</code></pre>

    <p>

        Essa classe deve ficar no arquivo:

    </p>

    <div class="resultado-codigo"><code>Produto.java</code></div>

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

        Esquecer os parênteses ao chamar o método

    </h3>

    <p>

        Esta chamada está incorreta:

    </p>

    <pre class="codigo-java"><code>aluno1.estudar;</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>aluno1.estudar();</code></pre>

    <h3>

        Confundir atributo com método

    </h3>

    <p>

        Atributos não utilizam parênteses:

    </p>

    <pre class="codigo-java"><code>aluno1.nome</code></pre>

    <p>

        Métodos utilizam parênteses:

    </p>

    <pre class="codigo-java"><code>aluno1.estudar()</code></pre>

    <h3>

        Chamar um método sem criar o objeto

    </h3>

    <pre class="codigo-java"><code>Aluno aluno1;

aluno1.estudar();</code></pre>

    <p>

        A variável foi declarada, mas nenhum objeto
        foi criado.

    </p>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();

aluno1.estudar();</code></pre>

    <h3>

        Utilizar o tipo de retorno errado

    </h3>

    <p>

        Este método está incorreto porque foi definido
        como int, mas tenta retornar um texto:

    </p>

    <pre class="codigo-java"><code>int mostrarNome() {

    return nome;

}</code></pre>

    <p>

        O correto seria:

    </p>

    <pre class="codigo-java"><code>String mostrarNome() {

    return nome;

}</code></pre>

    <h3>

        Nome do arquivo diferente da classe pública

    </h3>

    <pre class="codigo-java"><code>public class Aluno {

}</code></pre>

    <p>

        Essa classe deve ficar no arquivo:

    </p>

    <div class="resultado-codigo"><code>Aluno.java</code></div>

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
        uma conta bancária simples.

    </p>

    <h3>

        Arquivo Conta.java

    </h3>

    <pre class="codigo-java"><code>public class Conta {

    String titular;

    double saldo;


    void depositar(double valor) {

        if (valor &gt; 0) {

            saldo += valor;

        }

    }


    boolean sacar(double valor) {

        if (
            valor &gt; 0
            &amp;&amp; valor &lt;= saldo
        ) {

            saldo -= valor;

            return true;

        }

        return false;

    }


    void exibirSaldo() {

        System.out.println(
            "Titular: " + titular
        );

        System.out.println(
            "Saldo: R$ " + saldo
        );

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Conta conta = new Conta();

        conta.titular = "Robert";
        conta.saldo = 100.00;

        conta.depositar(50.00);

        boolean saqueRealizado =
            conta.sacar(30.00);

        conta.exibirSaldo();

        System.out.println(
            "Saque realizado: "
            + saqueRealizado
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Titular: Robert
Saldo: R$ 120.0
Saque realizado: true</code></div>

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

        Crie uma classe chamada
        <strong>Livro</strong>.

    </p>

    <p>

        A classe deverá possuir os atributos:

    </p>

    <ul>

        <li>

            título;

        </li>

        <li>

            autor;

        </li>

        <li>

            quantidade de páginas;

        </li>

        <li>

            preço;

        </li>

        <li>

            disponibilidade.

        </li>

    </ul>

    <p>

        Crie também os métodos:

    </p>

    <ul>

        <li>

            <strong>exibirInformacoes():</strong>
            mostra os dados do livro;

        </li>

        <li>

            <strong>alterarPreco():</strong>
            recebe e altera o preço;

        </li>

        <li>

            <strong>emprestar():</strong>
            altera a disponibilidade para false;

        </li>

        <li>

            <strong>devolver():</strong>
            altera a disponibilidade para true;

        </li>

        <li>

            <strong>estaDisponivel():</strong>
            retorna a disponibilidade atual.

        </li>

    </ul>

    <p>

        Depois, crie dois objetos diferentes
        no arquivo Main.java.

    </p>

    <div class="resultado-codigo"><code>=== LIVRO 1 ===
Título: Introdução ao Java
Autor: Ana Silva
Páginas: 250
Preço: R$ 59.9
Disponível: true

Livro emprestado.

Disponível agora: false</code></div>

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

        Nesta aula, aprendemos como atributos e métodos
        representam as características e os comportamentos
        dos objetos.

    </p>

    <ul>

        <li>

            atributos são variáveis declaradas
            dentro de uma classe;

        </li>

        <li>

            atributos representam informações
            pertencentes aos objetos;

        </li>

        <li>

            cada objeto pode possuir valores diferentes;

        </li>

        <li>

            métodos representam ações e comportamentos;

        </li>

        <li>

            métodos de instância pertencem aos objetos;

        </li>

        <li>

            utilizamos o operador ponto para acessar
            atributos e métodos;

        </li>

        <li>

            métodos podem acessar os atributos
            do próprio objeto;

        </li>

        <li>

            métodos podem receber parâmetros;

        </li>

        <li>

            métodos podem retornar valores;

        </li>

        <li>

            um método pode chamar outro método
            da mesma classe;

        </li>

        <li>

            atributos são diferentes de variáveis locais;

        </li>

        <li>

            cada método deve possuir uma responsabilidade
            bem definida.

        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Os atributos representam o estado
                do objeto, enquanto os métodos representam
                seus comportamentos.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como utilizar
        construtores para preparar os atributos de um
        objeto no momento em que ele é criado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Os atributos dizem como o objeto está.
        Os métodos dizem o que ele pode fazer."

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

        Próxima aula: Construtores →

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