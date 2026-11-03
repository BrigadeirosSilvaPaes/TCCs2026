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
                "heranca"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "interfaces"
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
    content="Aprenda polimorfismo em Java, sobrescrita de métodos, referências da classe pai, ligação dinâmica e uso da anotação Override."
>

<title>
    Polimorfismo em Java | Java, Café e Código
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

        AULA 26

    </span>

    <h1>

        Polimorfismo em Java

    </h1>

    <p>

        Aprenda como diferentes objetos podem responder
        de formas diferentes à mesma operação, tornando
        os programas mais flexíveis e fáceis de expandir.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é polimorfismo?

    </h2>

    <p>

        O <strong>polimorfismo</strong> é um dos principais
        conceitos da Programação Orientada a Objetos.

    </p>

    <p>

        A palavra pode ser entendida como
        <strong>“muitas formas”</strong>.

    </p>

    <p>

        Na programação, isso significa que uma mesma
        operação pode apresentar comportamentos diferentes,
        dependendo do objeto que estiver executando essa
        operação.

    </p>

    <p>

        Imagine diferentes tipos de animais.

    </p>

    <p>

        Todos podem emitir um som, mas cada animal
        produz um som diferente.

    </p>

    <ul>

        <li>
            um cachorro late;
        </li>

        <li>
            um gato mia;
        </li>

        <li>
            um pássaro canta.
        </li>

    </ul>

    <div class="aula-destaque">

        <strong>

            Forma simples de entender:

        </strong>

        <p>

            O mesmo método pode produzir resultados
            diferentes conforme o objeto utilizado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO DO COTIDIANO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Um exemplo do cotidiano

    </h2>

    <p>

        Podemos representar o comportamento dos animais
        utilizando um método chamado
        <strong>emitirSom()</strong>.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Objeto</span>
            <span>Método</span>
            <span>Resultado</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Cachorro</span>
            <span>emitirSom()</span>
            <span>Au au!</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Gato</span>
            <span>emitirSom()</span>
            <span>Miau!</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Pássaro</span>
            <span>emitirSom()</span>
            <span>Piu piu!</span>

        </div>

    </div>

    <p>

        O nome do método permanece igual, mas cada
        classe fornece sua própria implementação.

    </p>

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

        O polimorfismo costuma trabalhar em conjunto
        com a herança.

    </p>

    <p>

        Primeiro, criaremos uma classe mais geral
        chamada Animal.

    </p>

    <h3>

        Arquivo Animal.java

    </h3>

    <pre class="codigo-java"><code>public class Animal {

    private String nome;


    public Animal(String nome) {

        this.nome = nome;

    }


    public String getNome() {

        return nome;

    }


    public void emitirSom() {

        System.out.println(
            "O animal emitiu um som."
        );

    }

}</code></pre>

    <p>

        A classe possui:

    </p>

    <ul>

        <li>
            um atributo privado chamado nome;
        </li>

        <li>
            um construtor;
        </li>

        <li>
            o método getNome();
        </li>

        <li>
            o método emitirSom().
        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| CLASSE CACHORRO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Sobrescrevendo o método em Cachorro

    </h2>

    <p>

        Agora criaremos uma classe Cachorro que herda
        de Animal.

    </p>

    <h3>

        Arquivo Cachorro.java

    </h3>

    <pre class="codigo-java"><code>public class Cachorro extends Animal {

    public Cachorro(String nome) {

        super(nome);

    }


    @Override
    public void emitirSom() {

        System.out.println(
            getNome() + ": Au au!"
        );

    }

}</code></pre>

    <p>

        A classe Cachorro herdou o método emitirSom(),
        mas forneceu uma nova implementação.

    </p>

    <p>

        Esse processo é chamado de
        <strong>sobrescrita de método</strong>.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CLASSE GATO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Sobrescrevendo o método em Gato

    </h2>

    <h3>

        Arquivo Gato.java

    </h3>

    <pre class="codigo-java"><code>public class Gato extends Animal {

    public Gato(String nome) {

        super(nome);

    }


    @Override
    public void emitirSom() {

        System.out.println(
            getNome() + ": Miau!"
        );

    }

}</code></pre>

    <p>

        A classe Gato também sobrescreveu emitirSom(),
        mas criou um comportamento diferente.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CLASSE PÁSSARO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Adicionando outra forma

    </h2>

    <p>

        Também podemos criar uma classe Passaro.

    </p>

    <h3>

        Arquivo Passaro.java

    </h3>

    <pre class="codigo-java"><code>public class Passaro extends Animal {

    public Passaro(String nome) {

        super(nome);

    }


    @Override
    public void emitirSom() {

        System.out.println(
            getNome() + ": Piu piu!"
        );

    }

}</code></pre>

    <p>

        Agora temos três classes que respondem
        de maneiras diferentes ao mesmo método.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| OVERRIDE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que significa @Override?

    </h2>

    <p>

        A anotação <strong>@Override</strong> indica
        que um método herdado está sendo sobrescrito.

    </p>

    <pre class="codigo-java"><code>@Override
public void emitirSom() {

    System.out.println("Au au!");

}</code></pre>

    <p>

        O Java verifica se realmente existe na classe
        pai um método compatível com essa declaração.

    </p>

    <p>

        Isso ajuda a identificar erros de digitação
        ou declarações incompatíveis.

    </p>

    <div class="aula-destaque">

        <strong>

            A anotação é recomendada.

        </strong>

        <p>

            Mesmo quando a sobrescrita funcionaria sem
            a anotação, utilizar @Override deixa a intenção
            do código mais clara e ajuda o compilador
            a encontrar erros.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| REGRAS DA SOBRESCRITA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Regras da sobrescrita

    </h2>

    <p>

        Para sobrescrever corretamente um método,
        a classe filha precisa respeitar sua assinatura.

    </p>

    <p>

        Considere o método da classe Animal:

    </p>

    <pre class="codigo-java"><code>public void emitirSom() {

}</code></pre>

    <p>

        A classe filha deve manter:

    </p>

    <ul>

        <li>
            o mesmo nome;
        </li>

        <li>
            a mesma quantidade de parâmetros;
        </li>

        <li>
            os mesmos tipos de parâmetros;
        </li>

        <li>
            um tipo de retorno compatível;
        </li>

        <li>
            um nível de acesso que não seja mais restritivo.
        </li>

    </ul>

    <p>

        Esta sobrescrita está correta:

    </p>

    <pre class="codigo-java"><code>@Override
public void emitirSom() {

    System.out.println("Miau!");

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| REFERÊNCIA DA CLASSE PAI
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Referência da classe pai

    </h2>

    <p>

        Uma referência do tipo da classe pai pode
        armazenar objetos de suas classes filhas.

    </p>

    <pre class="codigo-java"><code>Animal animal1 =
    new Cachorro("Rex");

Animal animal2 =
    new Gato("Mingau");

Animal animal3 =
    new Passaro("Pipoca");</code></pre>

    <p>

        As três variáveis foram declaradas como Animal.

    </p>

    <p>

        Porém, cada uma guarda um objeto diferente.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Referência</span>
            <span>Tipo declarado</span>
            <span>Objeto criado</span>

        </div>

        <div class="aula-tabela-linha">

            <span>animal1</span>
            <span>Animal</span>
            <span>Cachorro</span>

        </div>

        <div class="aula-tabela-linha">

            <span>animal2</span>
            <span>Animal</span>
            <span>Gato</span>

        </div>

        <div class="aula-tabela-linha">

            <span>animal3</span>
            <span>Animal</span>
            <span>Passaro</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXECUÇÃO POLIMÓRFICA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Executando o mesmo método

    </h2>

    <p>

        Agora podemos chamar emitirSom() utilizando
        as três referências.

    </p>

    <pre class="codigo-java"><code>animal1.emitirSom();

animal2.emitirSom();

animal3.emitirSom();</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Rex: Au au!
Mingau: Miau!
Pipoca: Piu piu!</code></div>

    <p>

        Embora as variáveis tenham o tipo Animal,
        o Java executa a implementação correspondente
        ao objeto verdadeiro.

    </p>

    <div class="aula-destaque">

        <strong>

            Essa é a ideia central do polimorfismo:

        </strong>

        <p>

            A mesma chamada pode executar comportamentos
            diferentes conforme o objeto armazenado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| LIGAÇÃO DINÂMICA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como o Java escolhe o método?

    </h2>

    <p>

        Quando um método sobrescrito é chamado,
        o Java verifica qual é o objeto real armazenado
        na referência.

    </p>

    <pre class="codigo-java"><code>Animal animal =
    new Cachorro("Rex");

animal.emitirSom();</code></pre>

    <p>

        A referência é do tipo Animal, mas o objeto
        foi criado como Cachorro.

    </p>

    <p>

        Portanto, o Java executa a versão definida
        na classe Cachorro.

    </p>

    <p>

        Esse comportamento é conhecido como:

    </p>

    <div class="resultado-codigo"><code>ligação dinâmica
ou
despacho dinâmico de métodos</code></div>

    <p>

        A decisão acontece durante a execução
        do programa.

    </p>

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

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Animal animal1 =
            new Cachorro("Rex");

        Animal animal2 =
            new Gato("Mingau");

        Animal animal3 =
            new Passaro("Pipoca");


        animal1.emitirSom();

        animal2.emitirSom();

        animal3.emitirSom();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Rex: Au au!
Mingau: Miau!
Pipoca: Piu piu!</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| LIMITAÇÃO DA REFERÊNCIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que a referência consegue acessar?

    </h2>

    <p>

        Quando uma variável é declarada como Animal,
        ela pode acessar os métodos disponíveis
        no tipo Animal.

    </p>

    <p>

        Considere um método próprio de Cachorro:

    </p>

    <pre class="codigo-java"><code>public class Cachorro extends Animal {

    public Cachorro(String nome) {

        super(nome);

    }


    @Override
    public void emitirSom() {

        System.out.println("Au au!");

    }


    public void buscarBola() {

        System.out.println(
            "O cachorro buscou a bola."
        );

    }

}</code></pre>

    <p>

        Esta chamada funciona:

    </p>

    <pre class="codigo-java"><code>Cachorro cachorro =
    new Cachorro("Rex");

cachorro.buscarBola();</code></pre>

    <p>

        Porém, esta chamada não funciona diretamente:

    </p>

    <pre class="codigo-java"><code>Animal animal =
    new Cachorro("Rex");

animal.buscarBola();</code></pre>

    <p>

        A referência Animal não conhece o método
        buscarBola(), pois ele não foi declarado
        na classe Animal.

    </p>

    <div class="aula-destaque">

        <strong>

            Importante:

        </strong>

        <p>

            O tipo da referência determina quais
            membros podem ser acessados pelo código.

        </p>

        <p>

            O objeto real determina qual versão
            sobrescrita será executada.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ARRAY POLIMÓRFICO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Array com diferentes objetos

    </h2>

    <p>

        Podemos criar um array do tipo Animal
        e armazenar diferentes classes filhas.

    </p>

    <pre class="codigo-java"><code>Animal[] animais = {

    new Cachorro("Rex"),

    new Gato("Mingau"),

    new Passaro("Pipoca")

};</code></pre>

    <p>

        Depois, podemos percorrer o array:

    </p>

    <pre class="codigo-java"><code>for (Animal animal : animais) {

    animal.emitirSom();

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Rex: Au au!
Mingau: Miau!
Pipoca: Piu piu!</code></div>

    <p>

        O laço utiliza uma única estrutura, mas cada
        objeto executa sua própria implementação.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODO POLIMÓRFICO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Recebendo diferentes objetos em um método

    </h2>

    <p>

        Também podemos criar um método que recebe
        um Animal como parâmetro.

    </p>

    <pre class="codigo-java"><code>public static void apresentarSom(
    Animal animal
) {

    animal.emitirSom();

}</code></pre>

    <p>

        Como Cachorro, Gato e Passaro são tipos
        de Animal, podemos enviar qualquer um deles.

    </p>

    <pre class="codigo-java"><code>apresentarSom(
    new Cachorro("Rex")
);

apresentarSom(
    new Gato("Mingau")
);

apresentarSom(
    new Passaro("Pipoca")
);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Rex: Au au!
Mingau: Miau!
Pipoca: Piu piu!</code></div>

    <p>

        O método apresentarSom() não precisa saber
        antecipadamente qual classe específica receberá.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| NOVAS CLASSES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Adicionando novas classes

    </h2>

    <p>

        Imagine que posteriormente precisamos adicionar
        uma classe Vaca.

    </p>

    <h3>

        Arquivo Vaca.java

    </h3>

    <pre class="codigo-java"><code>public class Vaca extends Animal {

    public Vaca(String nome) {

        super(nome);

    }


    @Override
    public void emitirSom() {

        System.out.println(
            getNome() + ": Muuu!"
        );

    }

}</code></pre>

    <p>

        Podemos utilizá-la sem alterar o método
        apresentarSom():

    </p>

    <pre class="codigo-java"><code>apresentarSom(
    new Vaca("Mimosa")
);</code></pre>

    <div class="resultado-codigo"><code>Mimosa: Muuu!</code></div>

    <div class="aula-destaque">

        <strong>

            Extensibilidade:

        </strong>

        <p>

            Novas classes podem ser adicionadas
            mantendo a estrutura principal do programa.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SUPER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando super na sobrescrita

    </h2>

    <p>

        Uma classe filha também pode aproveitar
        o comportamento da classe pai e adicionar
        novas instruções.

    </p>

    <h3>

        Arquivo Animal.java

    </h3>

    <pre class="codigo-java"><code>public class Animal {

    public void apresentar() {

        System.out.println(
            "Este objeto é um animal."
        );

    }

}</code></pre>

    <h3>

        Arquivo Cachorro.java

    </h3>

    <pre class="codigo-java"><code>public class Cachorro extends Animal {

    @Override
    public void apresentar() {

        super.apresentar();

        System.out.println(
            "Mais especificamente, um cachorro."
        );

    }

}</code></pre>

    <p>

        Ao executar:

    </p>

    <pre class="codigo-java"><code>Cachorro cachorro =
    new Cachorro();

cachorro.apresentar();</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Este objeto é um animal.
Mais especificamente, um cachorro.</code></div>

    <p>

        O comando super.apresentar() executa
        a implementação da classe pai.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| SOBRESCRITA X SOBRECARGA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Sobrescrita ou sobrecarga?

    </h2>

    <p>

        Os nomes são parecidos, mas representam
        conceitos diferentes.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Conceito</span>
            <span>Como funciona?</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Sobrescrita</span>

            <span>
                Classe filha redefine método herdado
            </span>

            <span>
                emitirSom()
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>Sobrecarga</span>

            <span>
                Métodos possuem parâmetros diferentes
            </span>

            <span>
                calcular(int) e calcular(double)
            </span>

        </div>

    </div>

    <h3>

        Exemplo de sobrecarga

    </h3>

    <pre class="codigo-java"><code>public int somar(
    int numero1,
    int numero2
) {

    return numero1 + numero2;

}


public double somar(
    double numero1,
    double numero2
) {

    return numero1 + numero2;

}</code></pre>

    <p>

        Os dois métodos se chamam somar(), mas recebem
        parâmetros de tipos diferentes.

    </p>

    <div class="aula-destaque">

        <strong>

            Resumindo:

        </strong>

        <p>

            Sobrescrita altera um comportamento herdado.

        </p>

        <p>

            Sobrecarga cria diferentes formas de chamar
            um método.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO FUNCIONÁRIOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: funcionários

    </h2>

    <p>

        Vamos criar diferentes tipos de funcionários.

    </p>

    <h3>

        Arquivo Funcionario.java

    </h3>

    <pre class="codigo-java"><code>public class Funcionario {

    private String nome;


    public Funcionario(String nome) {

        this.nome = nome;

    }


    public String getNome() {

        return nome;

    }


    public void trabalhar() {

        System.out.println(
            nome + " está trabalhando."
        );

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| PROFESSOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Classe Professor

    </h2>

    <h3>

        Arquivo Professor.java

    </h3>

    <pre class="codigo-java"><code>public class Professor
        extends Funcionario {

    public Professor(String nome) {

        super(nome);

    }


    @Override
    public void trabalhar() {

        System.out.println(
            getNome()
            + " está ensinando uma aula."
        );

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| PROGRAMADOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Classe Programador

    </h2>

    <h3>

        Arquivo Programador.java

    </h3>

    <pre class="codigo-java"><code>public class Programador
        extends Funcionario {

    public Programador(String nome) {

        super(nome);

    }


    @Override
    public void trabalhar() {

        System.out.println(
            getNome()
            + " está desenvolvendo um sistema."
        );

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| DIRETOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Classe Diretor

    </h2>

    <h3>

        Arquivo Diretor.java

    </h3>

    <pre class="codigo-java"><code>public class Diretor
        extends Funcionario {

    public Diretor(String nome) {

        super(nome);

    }


    @Override
    public void trabalhar() {

        System.out.println(
            getNome()
            + " está organizando a instituição."
        );

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| UTILIZANDO FUNCIONÁRIOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando os funcionários

    </h2>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Funcionario[] funcionarios = {

            new Professor("Henrique"),

            new Programador("Robert"),

            new Diretor("Mariana")

        };


        for (
            Funcionario funcionario
            : funcionarios
        ) {

            funcionario.trabalhar();

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Henrique está ensinando uma aula.
Robert está desenvolvendo um sistema.
Mariana está organizando a instituição.</code></div>

    <p>

        O laço utiliza apenas o tipo Funcionario.

    </p>

    <p>

        Mesmo assim, cada objeto executa seu
        comportamento específico.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| BENEFÍCIOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Vantagens do polimorfismo

    </h2>

    <ul>

        <li>

            <strong>Flexibilidade:</strong>
            permite trabalhar com diferentes objetos
            através de um tipo comum;

        </li>

        <li>

            <strong>Organização:</strong>
            cada classe mantém seu próprio comportamento;

        </li>

        <li>

            <strong>Reutilização:</strong>
            reduz estruturas repetidas;

        </li>

        <li>

            <strong>Extensibilidade:</strong>
            novas classes podem ser adicionadas;

        </li>

        <li>

            <strong>Manutenção:</strong>
            mudanças ficam concentradas nas classes
            responsáveis;

        </li>

        <li>

            <strong>Desacoplamento:</strong>
            o código principal não precisa conhecer
            todos os detalhes de cada classe filha.

        </li>

    </ul>

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

        Alterar o nome do método

    </h3>

    <p>

        A classe pai possui:

    </p>

    <pre class="codigo-java"><code>public void emitirSom() {

}</code></pre>

    <p>

        Este método da classe filha não sobrescreve
        o método original:

    </p>

    <pre class="codigo-java"><code>public void emitirSons() {

}</code></pre>

    <p>

        Os nomes são diferentes.

    </p>


    <h3>

        Alterar os parâmetros

    </h3>

    <p>

        Este método também não representa a mesma
        assinatura:

    </p>

    <pre class="codigo-java"><code>public void emitirSom(
    String mensagem
) {

}</code></pre>

    <p>

        Ele recebe um parâmetro que não existe
        no método da classe pai.

    </p>


    <h3>

        Utilizar acesso mais restritivo

    </h3>

    <p>

        Se o método da classe pai é public,
        a versão sobrescrita não pode ser private.

    </p>

    <pre class="codigo-java"><code>@Override
private void emitirSom() {

}</code></pre>

    <p>

        Esse código causará erro.

    </p>


    <h3>

        Chamar um método exclusivo pela referência pai

    </h3>

    <pre class="codigo-java"><code>Animal animal =
    new Cachorro("Rex");

animal.buscarBola();</code></pre>

    <p>

        A referência Animal não possui acesso direto
        ao método buscarBola().

    </p>


    <h3>

        Confundir sobrescrita com sobrecarga

    </h3>

    <p>

        Sobrescrita acontece entre classe pai
        e classe filha.

    </p>

    <p>

        Sobrecarga acontece quando existem diferentes
        listas de parâmetros.

    </p>


    <h3>

        Esquecer a herança

    </h3>

    <p>

        Para que Cachorro seja tratado como Animal,
        a relação precisa existir.

    </p>

    <pre class="codigo-java"><code>public class Cachorro
        extends Animal {

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

        Vamos criar diferentes formas de pagamento.

    </p>

    <h3>

        Arquivo Pagamento.java

    </h3>

    <pre class="codigo-java"><code>public class Pagamento {

    private double valor;


    public Pagamento(double valor) {

        this.valor = valor;

    }


    public double getValor() {

        return valor;

    }


    public void processar() {

        System.out.println(
            "Processando pagamento."
        );

    }

}</code></pre>

    <h3>

        Arquivo PagamentoPix.java

    </h3>

    <pre class="codigo-java"><code>public class PagamentoPix
        extends Pagamento {

    public PagamentoPix(double valor) {

        super(valor);

    }


    @Override
    public void processar() {

        System.out.println(
            "Pagamento de R$ "
            + getValor()
            + " realizado por Pix."
        );

    }

}</code></pre>

    <h3>

        Arquivo PagamentoCartao.java

    </h3>

    <pre class="codigo-java"><code>public class PagamentoCartao
        extends Pagamento {

    public PagamentoCartao(
        double valor
    ) {

        super(valor);

    }


    @Override
    public void processar() {

        System.out.println(
            "Pagamento de R$ "
            + getValor()
            + " realizado no cartão."
        );

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Pagamento[] pagamentos = {

            new PagamentoPix(50.00),

            new PagamentoCartao(120.00)

        };


        for (
            Pagamento pagamento
            : pagamentos
        ) {

            pagamento.processar();

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Pagamento de R$ 50.0 realizado por Pix.
Pagamento de R$ 120.0 realizado no cartão.</code></div>

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
        <strong>Notificacao</strong> com os atributos:

    </p>

    <ul>

        <li>
            destinatário;
        </li>

        <li>
            mensagem.
        </li>

    </ul>

    <p>

        Crie o método:

    </p>

    <pre class="codigo-java"><code>public void enviar() {

}</code></pre>

    <p>

        Depois, crie três classes filhas:

    </p>

    <ul>

        <li>
            NotificacaoEmail;
        </li>

        <li>
            NotificacaoSMS;
        </li>

        <li>
            NotificacaoAplicativo.
        </li>

    </ul>

    <p>

        Cada classe deverá sobrescrever enviar()
        e apresentar uma mensagem diferente.

    </p>

    <p>

        Por fim, coloque os objetos em um array
        do tipo Notificacao e percorra esse array.

    </p>

    <div class="resultado-codigo"><code>E-mail enviado para aluno@email.com.
SMS enviado para 53999999999.
Notificação enviada pelo aplicativo.</code></div>

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
        polimorfismo em Java.

    </p>

    <ul>

        <li>
            polimorfismo significa muitas formas;
        </li>

        <li>
            uma mesma operação pode possuir
            comportamentos diferentes;
        </li>

        <li>
            o polimorfismo costuma trabalhar
            em conjunto com herança;
        </li>

        <li>
            classes filhas podem sobrescrever
            métodos da classe pai;
        </li>

        <li>
            @Override indica uma sobrescrita;
        </li>

        <li>
            a assinatura do método deve ser respeitada;
        </li>

        <li>
            uma referência da classe pai pode guardar
            objetos das classes filhas;
        </li>

        <li>
            o objeto real determina qual versão
            sobrescrita será executada;
        </li>

        <li>
            esse comportamento é chamado de ligação
            ou despacho dinâmico;
        </li>

        <li>
            o tipo da referência determina quais
            membros podem ser acessados;
        </li>

        <li>
            arrays podem armazenar objetos de diferentes
            classes filhas;
        </li>

        <li>
            métodos podem receber a classe pai
            e trabalhar com diferentes subclasses;
        </li>

        <li>
            sobrescrita é diferente de sobrecarga;
        </li>

        <li>
            o polimorfismo torna o sistema mais flexível,
            organizado e fácil de expandir.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                A mesma chamada pode executar diferentes
                comportamentos de acordo com o objeto
                que estiver sendo utilizado.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos sobre interfaces
        e como definir contratos que diferentes classes
        deverão seguir.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "O polimorfismo permite usar uma estrutura comum
        sem retirar de cada objeto sua forma particular
        de agir."

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

        Próxima aula: Interfaces →

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