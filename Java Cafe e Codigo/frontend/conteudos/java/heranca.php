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
                "encapsulamento"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "polimorfismo"
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
    content="Aprenda como utilizar herança em Java para criar classes filhas, reutilizar atributos e métodos e trabalhar com extends e super."
>

<title>
    Herança em Java | Java, Café e Código
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

        AULA 25

    </span>

    <h1>

        Herança em Java

    </h1>

    <p>

        Aprenda como uma classe pode aproveitar
        atributos e métodos definidos em outra classe,
        permitindo reutilizar código e representar
        relações entre diferentes objetos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é herança?

    </h2>

    <p>

        A <strong>herança</strong> é um dos principais
        conceitos da Programação Orientada a Objetos.

    </p>

    <p>

        Ela permite que uma classe aproveite atributos
        e métodos definidos em outra classe.

    </p>

    <p>

        Imagine que queremos representar diferentes
        tipos de animais dentro de um programa.

    </p>

    <p>

        Um cachorro e um gato possuem características
        em comum, como nome e idade. Eles também podem
        realizar ações semelhantes, como comer e dormir.

    </p>

    <p>

        Em vez de repetir essas informações nas classes
        Cachorro e Gato, podemos criar uma classe mais
        geral chamada <strong>Animal</strong>.

    </p>

    <div class="aula-destaque">

        <strong>

            A herança permite reutilizar código.

        </strong>

        <p>

            Uma classe mais específica pode aproveitar
            características e comportamentos definidos
            em uma classe mais geral.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CLASSE PAI E CLASSE FILHA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Classe pai e classe filha

    </h2>

    <p>

        Em uma relação de herança, normalmente temos
        uma classe mais geral e uma classe mais
        específica.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Conceito</span>
            <span>Outro nome</span>
            <span>Função</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Classe pai</span>

            <span>
                Superclasse
            </span>

            <span>
                Fornece características e comportamentos
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>Classe filha</span>

            <span>
                Subclasse
            </span>

            <span>
                Herda e pode adicionar novos elementos
            </span>

        </div>

    </div>

    <p>

        Podemos representar essa relação desta forma:

    </p>

    <div class="resultado-codigo"><code>Animal
├── Cachorro
└── Gato</code></div>

    <p>

        Animal é a classe pai.

    </p>

    <p>

        Cachorro e Gato são classes filhas.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| RELAÇÃO É UM
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A relação “é um”

    </h2>

    <p>

        Uma forma de verificar se a herança faz sentido
        é utilizar a expressão <strong>“é um”</strong>.

    </p>

    <ul>

        <li>
            um cachorro <strong>é um</strong> animal;
        </li>

        <li>
            um gato <strong>é um</strong> animal;
        </li>

        <li>
            um aluno <strong>é uma</strong> pessoa;
        </li>

        <li>
            um professor <strong>é um</strong> funcionário.
        </li>

    </ul>

    <p>

        Porém, esta relação não faria sentido:

    </p>

    <div class="resultado-codigo"><code>Um carro é um motor.</code></div>

    <p>

        Um carro possui um motor, mas não é um motor.

    </p>

    <div class="aula-destaque">

        <strong>

            Herança representa uma relação lógica.

        </strong>

        <p>

            A classe filha deve realmente representar
            um tipo mais específico da classe pai.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CLASSE PAI
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando a classe pai

    </h2>

    <p>

        Vamos criar uma classe chamada Animal.

    </p>

    <h3>

        Arquivo Animal.java

    </h3>

    <pre class="codigo-java"><code>public class Animal {

    String nome;

    int idade;


    void comer() {

        System.out.println(
            nome + " está comendo."
        );

    }


    void dormir() {

        System.out.println(
            nome + " está dormindo."
        );

    }

}</code></pre>

    <p>

        A classe Animal possui:

    </p>

    <ul>

        <li>
            o atributo nome;
        </li>

        <li>
            o atributo idade;
        </li>

        <li>
            o método comer();
        </li>

        <li>
            o método dormir().
        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| EXTENDS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando extends

    </h2>

    <p>

        Para criar uma relação de herança em Java,
        utilizamos a palavra-chave
        <strong>extends</strong>.

    </p>

    <h3>

        Arquivo Cachorro.java

    </h3>

    <pre class="codigo-java"><code>public class Cachorro extends Animal {

}</code></pre>

    <p>

        Essa declaração informa que Cachorro herda
        da classe Animal.

    </p>

    <p>

        A estrutura geral é:

    </p>

    <pre class="codigo-java"><code>public class ClasseFilha
        extends ClassePai {

}</code></pre>

    <div class="aula-destaque">

        <strong>

            extends significa estender.

        </strong>

        <p>

            A classe filha estende o funcionamento
            da classe pai, aproveitando seus elementos
            e podendo adicionar novos recursos.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| UTILIZANDO ELEMENTOS HERDADOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando atributos e métodos herdados

    </h2>

    <p>

        Depois de criar a herança, um objeto Cachorro
        poderá utilizar os elementos disponíveis
        na classe Animal.

    </p>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Cachorro cachorro =
            new Cachorro();

        cachorro.nome = "Rex";

        cachorro.idade = 4;

        cachorro.comer();

        cachorro.dormir();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Rex está comendo.
Rex está dormindo.</code></div>

    <p>

        Os atributos nome e idade foram definidos
        na classe Animal.

    </p>

    <p>

        Os métodos comer() e dormir() também foram
        definidos na classe Animal.

    </p>

    <p>

        Mesmo assim, o objeto Cachorro pode utilizá-los
        por causa da herança.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ELEMENTOS PRÓPRIOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Adicionando elementos próprios

    </h2>

    <p>

        A classe filha pode aproveitar os elementos
        da classe pai e também possuir seus próprios
        atributos e métodos.

    </p>

    <h3>

        Arquivo Cachorro.java

    </h3>

    <pre class="codigo-java"><code>public class Cachorro extends Animal {

    String raca;


    void latir() {

        System.out.println(
            nome + " está latindo."
        );

    }

}</code></pre>

    <p>

        A classe Cachorro possui o atributo raca
        e o método latir().

    </p>

    <p>

        Ao mesmo tempo, continua aproveitando nome,
        idade, comer() e dormir() da classe Animal.

    </p>

    <pre class="codigo-java"><code>Cachorro cachorro =
    new Cachorro();

cachorro.nome = "Rex";

cachorro.idade = 4;

cachorro.raca = "Labrador";

cachorro.comer();

cachorro.latir();</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Rex está comendo.
Rex está latindo.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| OUTRA CLASSE FILHA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando outra classe filha

    </h2>

    <p>

        Também podemos criar uma classe Gato
        herdando de Animal.

    </p>

    <h3>

        Arquivo Gato.java

    </h3>

    <pre class="codigo-java"><code>public class Gato extends Animal {

    String cor;


    void miar() {

        System.out.println(
            nome + " está miando."
        );

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Cachorro cachorro =
            new Cachorro();

        cachorro.nome = "Rex";

        cachorro.raca = "Labrador";

        cachorro.latir();


        Gato gato =
            new Gato();

        gato.nome = "Mingau";

        gato.cor = "Branco";

        gato.miar();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Rex está latindo.
Mingau está miando.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO PESSOA E ALUNO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo com Pessoa e Aluno

    </h2>

    <p>

        Todo aluno é uma pessoa. Portanto, podemos
        representar essa relação utilizando herança.

    </p>

    <h3>

        Arquivo Pessoa.java

    </h3>

    <pre class="codigo-java"><code>public class Pessoa {

    String nome;

    int idade;


    void apresentar() {

        System.out.println(
            "Nome: " + nome
        );

        System.out.println(
            "Idade: " + idade
        );

    }

}</code></pre>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno extends Pessoa {

    String curso;


    void estudar() {

        System.out.println(
            nome + " está estudando."
        );

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Aluno aluno = new Aluno();

        aluno.nome = "Robert";

        aluno.idade = 17;

        aluno.curso = "Informática";

        aluno.apresentar();

        System.out.println(
            "Curso: " + aluno.curso
        );

        aluno.estudar();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert
Idade: 17
Curso: Informática
Robert está estudando.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| REUTILIZAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Reutilizando código

    </h2>

    <p>

        Uma das principais vantagens da herança
        é evitar a repetição de código.

    </p>

    <p>

        Considere diferentes tipos de pessoas
        dentro de uma instituição:

    </p>

    <h3>

        Arquivo Pessoa.java

    </h3>

    <pre class="codigo-java"><code>public class Pessoa {

    String nome;

    int idade;

}</code></pre>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno extends Pessoa {

    String curso;

}</code></pre>

    <h3>

        Arquivo Professor.java

    </h3>

    <pre class="codigo-java"><code>public class Professor extends Pessoa {

    String disciplina;

}</code></pre>

    <h3>

        Arquivo Diretor.java

    </h3>

    <pre class="codigo-java"><code>public class Diretor extends Pessoa {

    String setor;

}</code></pre>

    <p>

        As três classes aproveitam nome e idade,
        que foram definidos apenas uma vez em Pessoa.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ACESSO PRIVATE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Atributos privados são acessados diretamente?

    </h2>

    <p>

        Um atributo declarado como
        <strong>private</strong> não pode ser acessado
        diretamente pela classe filha.

    </p>

    <h3>

        Arquivo Pessoa.java

    </h3>

    <pre class="codigo-java"><code>public class Pessoa {

    private String nome;

}</code></pre>

    <p>

        Este acesso dentro de Aluno não será permitido:

    </p>

    <pre class="codigo-java"><code>public class Aluno extends Pessoa {

    void mostrarNome() {

        System.out.println(nome);

    }

}</code></pre>

    <p>

        O atributo nome é privado e só pode ser
        acessado diretamente dentro da própria
        classe Pessoa.

    </p>

    <p>

        Podemos criar um getter público:

    </p>

    <pre class="codigo-java"><code>public class Pessoa {

    private String nome;


    public String getNome() {

        return nome;

    }


    public void setNome(String nome) {

        this.nome = nome;

    }

}</code></pre>

    <p>

        Agora a classe filha pode utilizar getNome().

    </p>

    <pre class="codigo-java"><code>public class Aluno extends Pessoa {

    void mostrarNome() {

        System.out.println(
            getNome()
        );

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| PROTECTED
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O modificador protected

    </h2>

    <p>

        O modificador <strong>protected</strong>
        permite que um elemento seja acessado:

    </p>

    <ul>

        <li>
            dentro da própria classe;
        </li>

        <li>
            por classes do mesmo pacote;
        </li>

        <li>
            por subclasses, seguindo as regras
            de acesso da linguagem.
        </li>

    </ul>

    <pre class="codigo-java"><code>public class Pessoa {

    protected String nome;

}</code></pre>

    <p>

        Uma classe filha poderá utilizar esse atributo:

    </p>

    <pre class="codigo-java"><code>public class Aluno extends Pessoa {

    void estudar() {

        System.out.println(
            nome + " está estudando."
        );

    }

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Qual usar?

        </strong>

        <p>

            Em muitos projetos, os atributos continuam
            private e são acessados por métodos protegidos
            ou públicos.

        </p>

        <p>

            Isso mantém um controle maior sobre os dados.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CONSTRUTOR DA CLASSE PAI
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Herança e construtores

    </h2>

    <p>

        Os construtores não são herdados como métodos
        comuns.

    </p>

    <p>

        Porém, o construtor da classe pai participa
        da criação do objeto da classe filha.

    </p>

    <h3>

        Arquivo Pessoa.java

    </h3>

    <pre class="codigo-java"><code>public class Pessoa {

    private String nome;

    private int idade;


    public Pessoa(
        String nome,
        int idade
    ) {

        this.nome = nome;

        this.idade = idade;

    }


    public String getNome() {

        return nome;

    }


    public int getIdade() {

        return idade;

    }

}</code></pre>

    <p>

        Como Pessoa possui um construtor com parâmetros,
        a classe filha precisa chamar esse construtor.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| SUPER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando super()

    </h2>

    <p>

        A palavra-chave <strong>super</strong>
        representa a parte da classe pai.

    </p>

    <p>

        Podemos utilizar <strong>super()</strong>
        para chamar o construtor da classe pai.

    </p>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno extends Pessoa {

    private String curso;


    public Aluno(
        String nome,
        int idade,
        String curso
    ) {

        super(nome, idade);

        this.curso = curso;

    }


    public String getCurso() {

        return curso;

    }

}</code></pre>

    <p>

        O comando:

    </p>

    <pre class="codigo-java"><code>super(nome, idade);</code></pre>

    <p>

        chama o construtor de Pessoa e envia os valores
        de nome e idade.

    </p>

    <p>

        Depois, a classe Aluno inicializa seu próprio
        atributo curso.

    </p>

    <div class="aula-destaque">

        <strong>

            Regra importante:

        </strong>

        <p>

            Quando super() é utilizado dentro de um
            construtor, ele precisa ser a primeira
            instrução.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO COM CONSTRUTOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando o objeto com herança

    </h2>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Aluno aluno = new Aluno(
            "Robert",
            17,
            "Informática"
        );

        System.out.println(
            "Nome: " + aluno.getNome()
        );

        System.out.println(
            "Idade: " + aluno.getIdade()
        );

        System.out.println(
            "Curso: " + aluno.getCurso()
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert
Idade: 17
Curso: Informática</code></div>

    <p>

        O objeto Aluno reúne informações definidas
        na classe Pessoa e na própria classe Aluno.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| SUPER EM MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando super em métodos

    </h2>

    <p>

        Também podemos utilizar super para chamar
        um método da classe pai.

    </p>

    <h3>

        Arquivo Pessoa.java

    </h3>

    <pre class="codigo-java"><code>public class Pessoa {

    void apresentar() {

        System.out.println(
            "Sou uma pessoa."
        );

    }

}</code></pre>

    <h3>

        Arquivo Aluno.java

    </h3>

    <pre class="codigo-java"><code>public class Aluno extends Pessoa {

    void apresentarAluno() {

        super.apresentar();

        System.out.println(
            "Também sou um aluno."
        );

    }

}</code></pre>

    <p>

        Ao executar:

    </p>

    <pre class="codigo-java"><code>Aluno aluno = new Aluno();

aluno.apresentarAluno();</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Sou uma pessoa.
Também sou um aluno.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| HERANÇA EM VÁRIOS NÍVEIS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Herança em vários níveis

    </h2>

    <p>

        Uma classe filha também pode servir como
        classe pai para outra classe.

    </p>

    <pre class="codigo-java"><code>public class Pessoa {

    String nome;

}</code></pre>

    <pre class="codigo-java"><code>public class Funcionario
        extends Pessoa {

    double salario;

}</code></pre>

    <pre class="codigo-java"><code>public class Professor
        extends Funcionario {

    String disciplina;

}</code></pre>

    <p>

        A relação fica assim:

    </p>

    <div class="resultado-codigo"><code>Pessoa
   ↓
Funcionario
   ↓
Professor</code></div>

    <p>

        Professor aproveita elementos definidos
        em Funcionario e também elementos herdados
        por Funcionario de Pessoa.

    </p>

    <div class="aula-destaque">

        <strong>

            Utilize com cuidado.

        </strong>

        <p>

            Hierarquias muito grandes podem deixar
            o código difícil de compreender e manter.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| HERANÇA ÚNICA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Uma classe pode herdar de várias classes?

    </h2>

    <p>

        Em Java, uma classe pode estender diretamente
        apenas uma classe.

    </p>

    <p>

        Este código não é permitido:

    </p>

    <pre class="codigo-java"><code>public class Aluno
        extends Pessoa, Usuario {

}</code></pre>

    <p>

        Uma classe Java possui apenas uma superclasse
        direta.

    </p>

    <p>

        Porém, uma classe pode implementar várias
        interfaces. Esse conteúdo será estudado
        em outra aula.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| HERANÇA E OBJECT
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A classe Object

    </h2>

    <p>

        Em Java, toda classe herda direta ou indiretamente
        da classe <strong>Object</strong>.

    </p>

    <p>

        Quando criamos:

    </p>

    <pre class="codigo-java"><code>public class Pessoa {

}</code></pre>

    <p>

        Podemos entender que ela também faz parte
        desta hierarquia:

    </p>

    <div class="resultado-codigo"><code>Object
   ↓
Pessoa</code></div>

    <p>

        Por isso, objetos Java possuem métodos
        que vêm da classe Object, como toString().

    </p>

    <p>

        Esses recursos serão utilizados com mais
        profundidade ao estudar polimorfismo
        e sobrescrita de métodos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO FUNCIONÁRIO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: funcionários

    </h2>

    <h3>

        Arquivo Funcionario.java

    </h3>

    <pre class="codigo-java"><code>public class Funcionario {

    private String nome;

    private double salario;


    public Funcionario(
        String nome,
        double salario
    ) {

        this.nome = nome;

        this.salario = salario;

    }


    public String getNome() {

        return nome;

    }


    public double getSalario() {

        return salario;

    }


    public void trabalhar() {

        System.out.println(
            nome + " está trabalhando."
        );

    }

}</code></pre>

    <h3>

        Arquivo Professor.java

    </h3>

    <pre class="codigo-java"><code>public class Professor
        extends Funcionario {

    private String disciplina;


    public Professor(
        String nome,
        double salario,
        String disciplina
    ) {

        super(nome, salario);

        this.disciplina = disciplina;

    }


    public String getDisciplina() {

        return disciplina;

    }


    public void ensinar() {

        System.out.println(
            getNome()
            + " está ensinando "
            + disciplina
            + "."
        );

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| UTILIZANDO PROFESSOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando o objeto Professor

    </h2>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Professor professor =
            new Professor(
                "Henrique",
                5000.00,
                "Java"
            );

        professor.trabalhar();

        professor.ensinar();

        System.out.println(
            "Salário: R$ "
            + professor.getSalario()
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Henrique está trabalhando.
Henrique está ensinando Java.
Salário: R$ 5000.0</code></div>

    <p>

        O método trabalhar() foi herdado
        de Funcionario.

    </p>

    <p>

        O método ensinar() pertence diretamente
        à classe Professor.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| BENEFÍCIOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Vantagens da herança

    </h2>

    <ul>

        <li>

            <strong>Reutilização:</strong>
            evita repetir atributos e métodos;

        </li>

        <li>

            <strong>Organização:</strong>
            permite criar hierarquias entre classes;

        </li>

        <li>

            <strong>Especialização:</strong>
            classes filhas podem adicionar recursos
            próprios;

        </li>

        <li>

            <strong>Manutenção:</strong>
            comportamentos comuns podem ser alterados
            na classe pai;

        </li>

        <li>

            <strong>Polimorfismo:</strong>
            permite tratar objetos diferentes por meio
            de um tipo mais geral.

        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| QUANDO NÃO USAR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Quando não utilizar herança?

    </h2>

    <p>

        A herança não deve ser utilizada apenas
        para evitar algumas linhas repetidas.

    </p>

    <p>

        É importante existir uma relação lógica
        entre as classes.

    </p>

    <p>

        Considere:

    </p>

    <div class="resultado-codigo"><code>Carro possui Motor.</code></div>

    <p>

        Como um carro não é um motor, a herança
        não representa corretamente essa relação.

    </p>

    <p>

        Nesse caso, podemos criar um atributo
        do tipo Motor dentro da classe Carro.

    </p>

    <pre class="codigo-java"><code>public class Carro {

    private Motor motor;

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Antes de utilizar herança, pergunte:

        </strong>

        <p>

            A classe filha realmente é um tipo
            da classe pai?

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

        Esquecer a palavra extends

    </h3>

    <p>

        Sem extends, não existe relação de herança.

    </p>

    <pre class="codigo-java"><code>public class Cachorro {

}</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>public class Cachorro
        extends Animal {

}</code></pre>

    <h3>

        Tentar acessar um atributo private

    </h3>

    <p>

        A classe filha não acessa diretamente
        atributos privados da classe pai.

    </p>

    <p>

        Utilize getters, setters ou métodos
        protegidos definidos pela classe pai.

    </p>

    <h3>

        Esquecer de chamar o construtor da classe pai

    </h3>

    <p>

        Quando a classe pai possui apenas um construtor
        com parâmetros, a classe filha precisa chamar
        esse construtor com super().

    </p>

    <pre class="codigo-java"><code>public Aluno(
    String nome,
    int idade,
    String curso
) {

    super(nome, idade);

    this.curso = curso;

}</code></pre>

    <h3>

        Colocar código antes de super()

    </h3>

    <p>

        Esta ordem está incorreta:

    </p>

    <pre class="codigo-java"><code>public Aluno(String nome) {

    System.out.println("Criando aluno");

    super(nome);

}</code></pre>

    <p>

        super() precisa ser a primeira instrução.

    </p>

    <h3>

        Tentar estender duas classes

    </h3>

    <pre class="codigo-java"><code>public class Aluno
        extends Pessoa, Usuario {

}</code></pre>

    <p>

        Java permite apenas uma superclasse direta.

    </p>

    <h3>

        Utilizar herança sem relação lógica

    </h3>

    <p>

        A classe filha deve representar um tipo
        mais específico da classe pai.

    </p>

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

        Vamos criar uma classe Veiculo e uma classe
        Carro.

    </p>

    <h3>

        Arquivo Veiculo.java

    </h3>

    <pre class="codigo-java"><code>public class Veiculo {

    private String marca;

    private int ano;


    public Veiculo(
        String marca,
        int ano
    ) {

        this.marca = marca;

        this.ano = ano;

    }


    public String getMarca() {

        return marca;

    }


    public int getAno() {

        return ano;

    }


    public void ligar() {

        System.out.println(
            "O veículo foi ligado."
        );

    }

}</code></pre>

    <h3>

        Arquivo Carro.java

    </h3>

    <pre class="codigo-java"><code>public class Carro extends Veiculo {

    private int quantidadePortas;


    public Carro(
        String marca,
        int ano,
        int quantidadePortas
    ) {

        super(marca, ano);

        this.quantidadePortas =
            quantidadePortas;

    }


    public int getQuantidadePortas() {

        return quantidadePortas;

    }


    public void abrirPortaMalas() {

        System.out.println(
            "O porta-malas foi aberto."
        );

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Carro carro = new Carro(
            "Toyota",
            2025,
            4
        );

        System.out.println(
            "Marca: " + carro.getMarca()
        );

        System.out.println(
            "Ano: " + carro.getAno()
        );

        System.out.println(
            "Portas: "
            + carro.getQuantidadePortas()
        );

        carro.ligar();

        carro.abrirPortaMalas();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Marca: Toyota
Ano: 2025
Portas: 4
O veículo foi ligado.
O porta-malas foi aberto.</code></div>

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
        <strong>Produto</strong> com os atributos:

    </p>

    <ul>

        <li>
            nome;
        </li>

        <li>
            preço;
        </li>

        <li>
            código.
        </li>

    </ul>

    <p>

        Depois, crie uma classe
        <strong>Livro</strong> que herde de Produto
        e possua:

    </p>

    <ul>

        <li>
            autor;
        </li>

        <li>
            quantidade de páginas.
        </li>

    </ul>

    <p>

        Crie também uma classe
        <strong>Alimento</strong> que herde de Produto
        e possua:

    </p>

    <ul>

        <li>
            data de validade;
        </li>

        <li>
            peso.
        </li>

    </ul>

    <p>

        Utilize construtores, encapsulamento,
        super() e métodos para exibir as informações.

    </p>

    <div class="resultado-codigo"><code>=== LIVRO ===
Nome: Java para Iniciantes
Preço: R$ 59.9
Código: 101
Autor: Ana Silva
Páginas: 300

=== ALIMENTO ===
Nome: Café
Preço: R$ 18.5
Código: 202
Validade: 20/12/2026
Peso: 500g</code></div>

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
        herança em Java.

    </p>

    <ul>

        <li>
            herança permite reutilizar atributos
            e métodos;
        </li>

        <li>
            a classe pai também é chamada
            de superclasse;
        </li>

        <li>
            a classe filha também é chamada
            de subclasse;
        </li>

        <li>
            extends cria uma relação de herança;
        </li>

        <li>
            a classe filha pode adicionar atributos
            e métodos próprios;
        </li>

        <li>
            herança normalmente representa
            uma relação “é um”;
        </li>

        <li>
            atributos private não são acessados
            diretamente pela classe filha;
        </li>

        <li>
            protected permite um acesso mais amplo
            em relações de herança;
        </li>

        <li>
            super() chama o construtor da classe pai;
        </li>

        <li>
            super também pode acessar métodos
            da classe pai;
        </li>

        <li>
            construtores não são herdados como
            métodos comuns;
        </li>

        <li>
            uma classe pode estender diretamente
            apenas uma classe;
        </li>

        <li>
            toda classe Java herda direta ou
            indiretamente de Object;
        </li>

        <li>
            herança deve ser utilizada somente
            quando existe uma relação lógica.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                A classe filha aproveita elementos
                da classe pai e adiciona aquilo que
                possui de específico.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos sobre
        polimorfismo e como diferentes objetos
        podem responder de maneiras diferentes
        para uma mesma operação.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Herança permite aproveitar o que já existe
        para construir classes mais específicas."

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

        Próxima aula: Polimorfismo →

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