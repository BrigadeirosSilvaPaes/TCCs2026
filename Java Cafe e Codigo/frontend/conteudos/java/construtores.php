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
                "atributos_metodos"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "encapsulamento"
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
    content="Aprenda como criar construtores em Java para inicializar atributos e preparar objetos no momento em que são criados."
>

<title>
    Construtores em Java | Java, Café e Código
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

        AULA 23

    </span>

    <h1>

        Construtores em Java

    </h1>

    <p>

        Aprenda como utilizar construtores para
        preparar e inicializar objetos no momento
        em que eles são criados.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que são construtores?

    </h2>

    <p>

        Nas aulas anteriores, aprendemos que uma classe
        pode possuir <strong>atributos</strong> e
        <strong>métodos</strong>.

    </p>

    <p>

        Os atributos representam as características
        de um objeto, enquanto os métodos representam
        seus comportamentos.

    </p>

    <p>

        Agora vamos conhecer outro elemento importante
        da Programação Orientada a Objetos:
        o <strong>construtor</strong>.

    </p>

    <p>

        Um construtor é uma estrutura especial utilizada
        para preparar um objeto no momento em que ele
        é criado.

    </p>

    <div class="aula-destaque">

        <strong>

            Pense no construtor como a preparação
            inicial do objeto.

        </strong>

        <p>

            Ele pode receber informações e utilizá-las
            para definir os valores iniciais dos atributos.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SEM CONSTRUTOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando um objeto sem construtor personalizado

    </h2>

    <p>

        Considere uma classe chamada
        <strong>Aluno</strong>.

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

        Podemos criar um objeto dessa classe:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();</code></pre>

    <p>

        Depois, precisamos preencher os atributos
        separadamente:

    </p>

    <pre class="codigo-java"><code>aluno1.nome = "Robert";

aluno1.idade = 17;

aluno1.curso = "Informática";</code></pre>

    <p>

        Esse código funciona, mas o objeto é criado
        primeiro e recebe suas informações somente
        depois.

    </p>

    <p>

        Um construtor permite organizar esse processo
        e inicializar os dados durante a criação.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PRIMEIRO CONSTRUTOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando o primeiro construtor

    </h2>

    <p>

        Um construtor possui o mesmo nome da classe.

    </p>

    <p>

        Como nossa classe se chama
        <strong>Aluno</strong>, seu construtor também
        deve se chamar <strong>Aluno</strong>.

    </p>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;

    String curso;


    public Aluno() {

        System.out.println(
            "Aluno criado!"
        );

    }

}</code></pre>

    <p>

        O construtor é esta parte:

    </p>

    <pre class="codigo-java"><code>public Aluno() {

    System.out.println(
        "Aluno criado!"
    );

}</code></pre>

    <p>

        Agora, quando criarmos um objeto:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();</code></pre>

    <p>

        O construtor será executado automaticamente.

    </p>

    <div class="resultado-codigo"><code>Aluno criado!</code></div>

    <div class="aula-destaque">

        <strong>

            O construtor não é chamado separadamente.

        </strong>

        <p>

            Ele é executado quando utilizamos
            <strong>new</strong> para criar o objeto.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CARACTERÍSTICAS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Características de um construtor

    </h2>

    <ul>

        <li>
            possui o mesmo nome da classe;
        </li>

        <li>
            é executado durante a criação do objeto;
        </li>

        <li>
            não possui tipo de retorno;
        </li>

        <li>
            não utiliza nem mesmo
            <strong>void</strong>;
        </li>

        <li>
            pode receber parâmetros;
        </li>

        <li>
            pode inicializar atributos;
        </li>

        <li>
            uma classe pode possuir mais de um
            construtor.
        </li>

    </ul>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Construtor</span>
            <span>Significado</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Visibilidade</span>
            <span>public</span>
            <span>Pode ser utilizado por outras classes</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Nome</span>
            <span>Aluno</span>
            <span>Deve ser igual ao nome da classe</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Parênteses</span>
            <span>()</span>
            <span>Podem receber parâmetros</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Corpo</span>
            <span>{ }</span>
            <span>Contém as instruções de inicialização</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CONSTRUTOR COM PARÂMETROS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Construtor com parâmetros

    </h2>

    <p>

        Um construtor pode receber informações
        por meio de parâmetros.

    </p>

    <p>

        Isso permite criar o objeto já com seus
        principais dados preenchidos.

    </p>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;

    String curso;


    public Aluno(
        String nome,
        int idade,
        String curso
    ) {

        this.nome = nome;

        this.idade = idade;

        this.curso = curso;

    }

}</code></pre>

    <p>

        O construtor recebe três parâmetros:

    </p>

    <ul>

        <li>
            <strong>String nome;</strong>
        </li>

        <li>
            <strong>int idade;</strong>
        </li>

        <li>
            <strong>String curso.</strong>
        </li>

    </ul>

    <p>

        Esses valores serão utilizados para preencher
        os atributos do objeto.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| THIS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Para que serve o this?

    </h2>

    <p>

        No construtor anterior, utilizamos a palavra
        <strong>this</strong>.

    </p>

    <pre class="codigo-java"><code>this.nome = nome;</code></pre>

    <p>

        O <strong>this</strong> representa o objeto
        atual.

    </p>

    <p>

        Nesse comando:

    </p>

    <ul>

        <li>

            <strong>this.nome</strong> representa
            o atributo do objeto;

        </li>

        <li>

            <strong>nome</strong> representa o parâmetro
            recebido pelo construtor.

        </li>

    </ul>

    <div class="aula-destaque">

        <strong>

            Podemos ler o comando assim:

        </strong>

        <p>

            Guarde no atributo
            <strong>nome</strong> do objeto o valor
            recebido pelo parâmetro
            <strong>nome</strong>.

        </p>

    </div>

    <p>

        O mesmo acontece nestes comandos:

    </p>

    <pre class="codigo-java"><code>this.idade = idade;

this.curso = curso;</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| CRIANDO COM CONSTRUTOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando um objeto com o construtor

    </h2>

    <p>

        Depois de criar um construtor com parâmetros,
        precisamos enviar os argumentos durante a
        criação do objeto.

    </p>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Aluno aluno1 = new Aluno(
            "Robert",
            17,
            "Informática"
        );

    }

}</code></pre>

    <p>

        Os argumentos serão enviados na mesma ordem
        dos parâmetros.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Argumento</span>
            <span>Parâmetro</span>
            <span>Atributo preenchido</span>

        </div>

        <div class="aula-tabela-linha">

            <span>"Robert"</span>
            <span>String nome</span>
            <span>this.nome</span>

        </div>

        <div class="aula-tabela-linha">

            <span>17</span>
            <span>int idade</span>
            <span>this.idade</span>

        </div>

        <div class="aula-tabela-linha">

            <span>"Informática"</span>
            <span>String curso</span>
            <span>this.curso</span>

        </div>

    </div>

    <p>

        O objeto já será criado com seus atributos
        preenchidos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ORDEM DOS ARGUMENTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A ordem dos argumentos é importante

    </h2>

    <p>

        Os argumentos precisam corresponder à ordem
        e aos tipos definidos no construtor.

    </p>

    <pre class="codigo-java"><code>public Aluno(
    String nome,
    int idade,
    String curso
) {

    this.nome = nome;

    this.idade = idade;

    this.curso = curso;

}</code></pre>

    <p>

        Esta criação está correta:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno(
    "Robert",
    17,
    "Informática"
);</code></pre>

    <p>

        Esta criação está incorreta:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno(
    17,
    "Robert",
    "Informática"
);</code></pre>

    <p>

        O primeiro parâmetro espera uma String,
        mas recebeu um número inteiro.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO COMPLETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo completo com Aluno

    </h2>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;

    String curso;


    public Aluno(
        String nome,
        int idade,
        String curso
    ) {

        this.nome = nome;

        this.idade = idade;

        this.curso = curso;

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

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Aluno aluno1 = new Aluno(
            "Robert",
            17,
            "Informática"
        );

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
| EXEMPLO COM PRODUTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo com a classe Produto

    </h2>

    <h3>

        Arquivo Produto.java

    </h3>

    <pre class="codigo-java"><code>public class Produto {

    String nome;

    double preco;

    int quantidade;


    public Produto(
        String nome,
        double preco,
        int quantidade
    ) {

        this.nome = nome;

        this.preco = preco;

        this.quantidade = quantidade;

    }


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

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Produto produto1 = new Produto(
            "Café",
            18.50,
            3
        );

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

        Podemos utilizar o mesmo construtor para
        criar vários objetos com valores diferentes.

    </p>

    <pre class="codigo-java"><code>Produto produto1 = new Produto(
    "Café",
    18.50,
    3
);

Produto produto2 = new Produto(
    "Chocolate",
    10.00,
    2
);

Produto produto3 = new Produto(
    "Bolo",
    8.50,
    5
);</code></pre>

    <p>

        Cada objeto possuirá seus próprios atributos.

    </p>

    <pre class="codigo-java"><code>produto1.exibirInformacoes();

produto2.exibirInformacoes();

produto3.exibirInformacoes();</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Produto: Café
Preço: R$ 18.5
Quantidade: 3
Total: R$ 55.5

Produto: Chocolate
Preço: R$ 10.0
Quantidade: 2
Total: R$ 20.0

Produto: Bolo
Preço: R$ 8.5
Quantidade: 5
Total: R$ 42.5</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| CONSTRUTOR PADRÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O construtor padrão

    </h2>

    <p>

        Quando uma classe não possui nenhum construtor
        declarado pelo programador, o Java fornece
        automaticamente um construtor sem parâmetros.

    </p>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;

}</code></pre>

    <p>

        Mesmo sem escrever um construtor, podemos criar:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();</code></pre>

    <p>

        Isso funciona porque o Java fornece um
        construtor padrão equivalente a:

    </p>

    <pre class="codigo-java"><code>public Aluno() {

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Atenção!

        </strong>

        <p>

            Quando declaramos manualmente qualquer
            construtor, o Java deixa de fornecer
            automaticamente o construtor sem parâmetros.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ERRO SEM CONSTRUTOR VAZIO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que acontece depois de criar um construtor?

    </h2>

    <p>

        Considere esta classe:

    </p>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;


    public Aluno(
        String nome,
        int idade
    ) {

        this.nome = nome;

        this.idade = idade;

    }

}</code></pre>

    <p>

        Agora esta criação funcionará:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno(
    "Robert",
    17
);</code></pre>

    <p>

        Porém, esta criação não funcionará:

    </p>

    <pre class="codigo-java"><code>Aluno aluno2 = new Aluno();</code></pre>

    <p>

        A classe possui apenas um construtor que exige
        nome e idade.

    </p>

    <p>

        Para permitir as duas formas, precisamos
        declarar dois construtores.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| SOBRECARGA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Sobrecarga de construtores

    </h2>

    <p>

        Uma classe pode possuir mais de um construtor,
        desde que eles tenham listas de parâmetros
        diferentes.

    </p>

    <p>

        Esse recurso é chamado de
        <strong>sobrecarga de construtores</strong>.

    </p>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;


    public Aluno() {

        nome = "Não informado";

        idade = 0;

    }


    public Aluno(String nome) {

        this.nome = nome;

        idade = 0;

    }


    public Aluno(
        String nome,
        int idade
    ) {

        this.nome = nome;

        this.idade = idade;

    }

}</code></pre>

    <p>

        Agora temos três possibilidades:

    </p>

    <pre class="codigo-java"><code>Aluno aluno1 = new Aluno();

Aluno aluno2 = new Aluno(
    "Robert"
);

Aluno aluno3 = new Aluno(
    "Ana",
    18
);</code></pre>

    <p>

        O Java escolhe o construtor de acordo
        com os argumentos enviados.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| THIS PARA CONSTRUTOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Chamando outro construtor com this()

    </h2>

    <p>

        Também podemos utilizar
        <strong>this()</strong> para chamar outro
        construtor da mesma classe.

    </p>

    <pre class="codigo-java"><code>public class Aluno {

    String nome;

    int idade;


    public Aluno() {

        this(
            "Não informado",
            0
        );

    }


    public Aluno(String nome) {

        this(
            nome,
            0
        );

    }


    public Aluno(
        String nome,
        int idade
    ) {

        this.nome = nome;

        this.idade = idade;

    }

}</code></pre>

    <p>

        O construtor sem parâmetros chama o construtor
        que recebe nome e idade.

    </p>

    <p>

        O construtor que recebe somente o nome também
        chama o construtor mais completo.

    </p>

    <div class="aula-destaque">

        <strong>

            Regra importante:

        </strong>

        <p>

            Quando utilizado dentro de um construtor,
            o comando <strong>this()</strong> precisa
            ser a primeira instrução.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CONSTRUTOR X MÉTODO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Construtor ou método?

    </h2>

    <p>

        Construtores e métodos possuem algumas
        semelhanças, mas têm funções diferentes.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Característica</span>
            <span>Construtor</span>
            <span>Método</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Nome</span>
            <span>Mesmo nome da classe</span>
            <span>Qualquer nome válido</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Tipo de retorno</span>
            <span>Não possui</span>
            <span>Pode possuir</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Execução</span>
            <span>Durante a criação do objeto</span>
            <span>Quando é chamado</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Objetivo</span>
            <span>Inicializar o objeto</span>
            <span>Executar uma tarefa</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Chamada</span>
            <span>new Aluno()</span>
            <span>aluno.estudar()</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VALIDAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Validando valores no construtor

    </h2>

    <p>

        Um construtor também pode verificar os valores
        recebidos antes de preencher os atributos.

    </p>

    <pre class="codigo-java"><code>public class Produto {

    String nome;

    double preco;


    public Produto(
        String nome,
        double preco
    ) {

        this.nome = nome;

        if (preco &gt;= 0) {

            this.preco = preco;

        } else {

            this.preco = 0;

        }

    }

}</code></pre>

    <p>

        Caso seja enviado um preço negativo,
        o atributo receberá zero.

    </p>

    <pre class="codigo-java"><code>Produto produto = new Produto(
    "Café",
    -10.00
);

System.out.println(produto.preco);</code></pre>

    <div class="resultado-codigo"><code>0.0</code></div>

    <p>

        Mais adiante, com encapsulamento, aprenderemos
        formas melhores de proteger os dados dos objetos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CAFETERIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: cafeteria

    </h2>

    <h3>

        Arquivo Cafe.java

    </h3>

    <pre class="codigo-java"><code>public class Cafe {

    String nome;

    String tamanho;

    double preco;

    boolean disponivel;


    public Cafe(
        String nome,
        String tamanho,
        double preco
    ) {

        this.nome = nome;

        this.tamanho = tamanho;

        this.preco = preco;

        this.disponivel = true;

    }


    double calcularPrecoFinal(
        int quantidade
    ) {

        return preco * quantidade;

    }


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

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Cafe cafe1 = new Cafe(
            "Café Expresso",
            "Grande",
            8.50
        );

        cafe1.exibirInformacoes();

        double total =
            cafe1.calcularPrecoFinal(3);

        System.out.println();

        System.out.println(
            "Total de 3 cafés: R$ "
            + total
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Café: Café Expresso
Tamanho: Grande
Preço: R$ 8.5
Disponível: true

Total de 3 cafés: R$ 25.5</code></div>

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

        Cada classe pública deve ficar em um arquivo
        com o mesmo nome.

    </p>

    <div class="resultado-codigo"><code>Aluno.java
Produto.java
Cafe.java
Main.java</code></div>

    <p>

        Por exemplo:

    </p>

    <pre class="codigo-java"><code>public class Cafe {

}</code></pre>

    <p>

        deve ficar no arquivo:

    </p>

    <div class="resultado-codigo"><code>Cafe.java</code></div>

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

        Colocar um tipo de retorno no construtor

    </h3>

    <p>

        Este código não cria um construtor:

    </p>

    <pre class="codigo-java"><code>public void Aluno() {

}</code></pre>

    <p>

        Como possui void, ele é um método chamado Aluno.

    </p>

    <p>

        O construtor correto é:

    </p>

    <pre class="codigo-java"><code>public Aluno() {

}</code></pre>

    <h3>

        Utilizar um nome diferente da classe

    </h3>

    <p>

        Na classe Aluno, isto não é um construtor:

    </p>

    <pre class="codigo-java"><code>public Estudante() {

}</code></pre>

    <p>

        O nome correto precisa ser Aluno:

    </p>

    <pre class="codigo-java"><code>public Aluno() {

}</code></pre>

    <h3>

        Enviar uma quantidade incorreta de argumentos

    </h3>

    <p>

        Para este construtor:

    </p>

    <pre class="codigo-java"><code>public Aluno(
    String nome,
    int idade
) {

}</code></pre>

    <p>

        precisamos enviar dois argumentos:

    </p>

    <pre class="codigo-java"><code>Aluno aluno = new Aluno(
    "Robert",
    17
);</code></pre>

    <h3>

        Enviar tipos incompatíveis

    </h3>

    <p>

        Esta criação está incorreta:

    </p>

    <pre class="codigo-java"><code>Aluno aluno = new Aluno(
    17,
    "Robert"
);</code></pre>

    <p>

        A ordem e os tipos precisam corresponder
        aos parâmetros.

    </p>

    <h3>

        Tentar utilizar o construtor vazio inexistente

    </h3>

    <p>

        Depois de declarar somente um construtor
        com parâmetros, esta criação não funcionará:

    </p>

    <pre class="codigo-java"><code>Aluno aluno = new Aluno();</code></pre>

    <p>

        Será necessário declarar também um construtor
        sem parâmetros.

    </p>

    <h3>

        Esquecer o this

    </h3>

    <p>

        Este código não altera o atributo como esperado:

    </p>

    <pre class="codigo-java"><code>public Aluno(String nome) {

    nome = nome;

}</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>public Aluno(String nome) {

    this.nome = nome;

}</code></pre>

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

        Vamos criar uma classe chamada
        <strong>Livro</strong>.

    </p>

    <h3>

        Arquivo Livro.java

    </h3>

    <pre class="codigo-java"><code>public class Livro {

    String titulo;

    String autor;

    int paginas;

    double preco;


    public Livro(
        String titulo,
        String autor,
        int paginas,
        double preco
    ) {

        this.titulo = titulo;

        this.autor = autor;

        this.paginas = paginas;

        this.preco = preco;

    }


    void exibirInformacoes() {

        System.out.println(
            "Título: " + titulo
        );

        System.out.println(
            "Autor: " + autor
        );

        System.out.println(
            "Páginas: " + paginas
        );

        System.out.println(
            "Preço: R$ " + preco
        );

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Livro livro = new Livro(
            "Introdução ao Java",
            "Ana Silva",
            250,
            59.90
        );

        livro.exibirInformacoes();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Título: Introdução ao Java
Autor: Ana Silva
Páginas: 250
Preço: R$ 59.9</code></div>

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
        <strong>Conta</strong>.

    </p>

    <p>

        Ela deverá possuir os atributos:

    </p>

    <ul>

        <li>
            titular;
        </li>

        <li>
            número da conta;
        </li>

        <li>
            saldo;
        </li>

        <li>
            ativa.
        </li>

    </ul>

    <p>

        Crie dois construtores:

    </p>

    <ul>

        <li>

            um construtor que receba apenas o titular
            e o número da conta;

        </li>

        <li>

            outro construtor que também receba
            o saldo inicial.

        </li>

    </ul>

    <p>

        O atributo ativa deve começar com
        <strong>true</strong>.

    </p>

    <p>

        Crie também um método para exibir os dados.

    </p>

    <div class="resultado-codigo"><code>Titular: Robert
Conta: 12345
Saldo: R$ 500.0
Ativa: true</code></div>

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
        construtores para preparar objetos em Java.

    </p>

    <ul>

        <li>
            um construtor prepara um objeto durante
            sua criação;
        </li>

        <li>
            o construtor possui o mesmo nome da classe;
        </li>

        <li>
            construtores não possuem tipo de retorno;
        </li>

        <li>
            o construtor é executado quando utilizamos
            new;
        </li>

        <li>
            construtores podem receber parâmetros;
        </li>

        <li>
            os argumentos devem respeitar a ordem
            e os tipos dos parâmetros;
        </li>

        <li>
            this representa o objeto atual;
        </li>

        <li>
            this permite diferenciar atributos
            e parâmetros com o mesmo nome;
        </li>

        <li>
            o Java fornece um construtor padrão quando
            nenhum construtor é declarado;
        </li>

        <li>
            depois que declaramos um construtor,
            o construtor vazio não é criado
            automaticamente;
        </li>

        <li>
            uma classe pode possuir mais de um
            construtor;
        </li>

        <li>
            esse recurso é chamado de sobrecarga;
        </li>

        <li>
            this() permite chamar outro construtor
            da mesma classe;
        </li>

        <li>
            construtores também podem validar
            os valores recebidos.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                O construtor permite que um objeto
                seja criado já preparado para ser
                utilizado pelo programa.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos sobre
        encapsulamento e como proteger os atributos
        dos objetos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Um construtor prepara o objeto para começar
        sua vida dentro do programa."

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

        Próxima aula: Encapsulamento →

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