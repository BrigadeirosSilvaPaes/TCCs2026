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
                "construtores"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "heranca"
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
    content="Aprenda como utilizar encapsulamento, atributos privados, getters, setters e validações para proteger os dados dos objetos em Java."
>

<title>
    Encapsulamento em Java | Java, Café e Código
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

        AULA 24

    </span>

    <h1>

        Encapsulamento em Java

    </h1>

    <p>

        Aprenda como proteger os dados de uma classe
        e controlar a maneira como essas informações
        podem ser consultadas e modificadas.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é encapsulamento?

    </h2>

    <p>

        O <strong>encapsulamento</strong> é um dos
        principais conceitos da Programação Orientada
        a Objetos.

    </p>

    <p>

        Ele consiste em proteger os dados internos
        de uma classe e definir caminhos controlados
        para acessar ou modificar essas informações.

    </p>

    <p>

        Imagine uma conta bancária. O saldo é uma
        informação importante e não deveria ser
        alterado livremente por qualquer parte
        do programa.

    </p>

    <p>

        Em vez de permitir que outro código altere
        o saldo diretamente, podemos criar métodos
        responsáveis por realizar depósitos, saques
        e consultas de forma segura.

    </p>

    <div class="aula-destaque">

        <strong>

            Pense no encapsulamento como uma proteção.

        </strong>

        <p>

            Os dados ficam protegidos dentro da classe,
            enquanto o acesso acontece por meio de
            métodos que podem aplicar regras e validações.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PROBLEMA SEM ENCAPSULAMENTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O problema dos atributos públicos

    </h2>

    <p>

        Considere uma classe responsável por representar
        uma conta bancária.

    </p>

    <h3>

        Arquivo Conta.java

    </h3>

    <pre class="codigo-java"><code>public class Conta {

    public double saldo;

}</code></pre>

    <p>

        Como o atributo saldo é público, qualquer parte
        do programa pode alterar seu valor diretamente.

    </p>

    <pre class="codigo-java"><code>Conta conta = new Conta();

conta.saldo = 500;

conta.saldo = -10000;</code></pre>

    <p>

        O programa permitiu que o saldo recebesse
        um valor negativo sem realizar nenhuma
        verificação.

    </p>

    <p>

        Isso deixa os dados da classe expostos e
        pode causar comportamentos incorretos.

    </p>

    <div class="aula-destaque">

        <strong>

            Problema:

        </strong>

        <p>

            Quando um atributo pode ser alterado
            diretamente, a classe perde o controle
            sobre seus próprios dados.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SOLUÇÃO COM ENCAPSULAMENTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Protegendo os atributos

    </h2>

    <p>

        Para proteger um atributo, podemos utilizar
        o modificador de acesso
        <strong>private</strong>.

    </p>

    <pre class="codigo-java"><code>public class Conta {

    private double saldo;

}</code></pre>

    <p>

        Agora o atributo saldo só pode ser acessado
        diretamente dentro da própria classe Conta.

    </p>

    <p>

        Este código, escrito em outra classe,
        não será permitido:

    </p>

    <pre class="codigo-java"><code>Conta conta = new Conta();

conta.saldo = 500;</code></pre>

    <p>

        Para trabalhar com o saldo, precisaremos
        utilizar métodos públicos definidos pela
        própria classe.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MODIFICADORES DE ACESSO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Modificadores de acesso

    </h2>

    <p>

        Os modificadores de acesso determinam
        onde uma classe, atributo, construtor
        ou método poderá ser utilizado.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Modificador</span>
            <span>Acesso principal</span>
            <span>Uso comum</span>

        </div>

        <div class="aula-tabela-linha">

            <span>public</span>

            <span>
                Pode ser acessado de outras classes
            </span>

            <span>
                Métodos de uso externo
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>private</span>

            <span>
                Apenas dentro da própria classe
            </span>

            <span>
                Proteção de atributos
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>protected</span>

            <span>
                Mesmo pacote e classes herdeiras
            </span>

            <span>
                Herança
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>Sem modificador</span>

            <span>
                Classes do mesmo pacote
            </span>

            <span>
                Acesso de pacote
            </span>

        </div>

    </div>

    <div class="aula-destaque">

        <strong>

            Convenção muito utilizada:

        </strong>

        <p>

            Atributos normalmente são declarados
            como private, enquanto os métodos que
            precisam ser utilizados por outras classes
            podem ser declarados como public.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PRIVATE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando private

    </h2>

    <p>

        Quando um atributo é declarado como
        <strong>private</strong>, ele fica protegido
        contra acessos diretos realizados por outras
        classes.

    </p>

    <h3>

        Arquivo Pessoa.java

    </h3>

    <pre class="codigo-java"><code>public class Pessoa {

    private String nome;

    private int idade;

}</code></pre>

    <p>

        Os atributos nome e idade pertencem à classe,
        mas não podem ser acessados diretamente
        pelo arquivo Main.java.

    </p>

    <pre class="codigo-java"><code>Pessoa pessoa = new Pessoa();

pessoa.nome = "Robert";</code></pre>

    <p>

        Esse código causará um erro de compilação
        porque nome possui acesso privado.

    </p>

    <p>

        Para permitir uma consulta ou alteração
        controlada, podemos criar métodos públicos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| GETTERS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos getters

    </h2>

    <p>

        Um <strong>getter</strong> é um método utilizado
        para consultar o valor de um atributo privado.

    </p>

    <h3>

        Arquivo Pessoa.java

    </h3>

    <pre class="codigo-java"><code>public class Pessoa {

    private String nome;


    public String getNome() {

        return nome;

    }

}</code></pre>

    <p>

        O método getNome() possui retorno do tipo
        String porque devolve o valor armazenado
        no atributo nome.

    </p>

    <p>

        Podemos utilizar o getter desta forma:

    </p>

    <pre class="codigo-java"><code>Pessoa pessoa = new Pessoa();

System.out.println(
    pessoa.getNome()
);</code></pre>

    <div class="aula-destaque">

        <strong>

            Getter:

        </strong>

        <p>

            É um método utilizado para ler ou consultar
            o valor de um atributo.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SETTERS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos setters

    </h2>

    <p>

        Um <strong>setter</strong> é um método utilizado
        para alterar o valor de um atributo privado.

    </p>

    <pre class="codigo-java"><code>public class Pessoa {

    private String nome;


    public void setNome(String nome) {

        this.nome = nome;

    }

}</code></pre>

    <p>

        O método recebe um novo nome por meio
        do parâmetro.

    </p>

    <p>

        Podemos utilizá-lo desta forma:

    </p>

    <pre class="codigo-java"><code>Pessoa pessoa = new Pessoa();

pessoa.setNome("Robert");</code></pre>

    <p>

        O atributo continua privado, mas sua alteração
        ocorre por meio de um método público.

    </p>

    <div class="aula-destaque">

        <strong>

            Setter:

        </strong>

        <p>

            É um método utilizado para receber e
            modificar o valor de um atributo.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| GETTER E SETTER COMPLETOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Getters e setters juntos

    </h2>

    <h3>

        Arquivo Pessoa.java

    </h3>

    <pre class="codigo-java"><code>public class Pessoa {

    private String nome;

    private int idade;


    public String getNome() {

        return nome;

    }


    public void setNome(String nome) {

        this.nome = nome;

    }


    public int getIdade() {

        return idade;

    }


    public void setIdade(int idade) {

        this.idade = idade;

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Pessoa pessoa = new Pessoa();

        pessoa.setNome("Robert");

        pessoa.setIdade(17);

        System.out.println(
            "Nome: " + pessoa.getNome()
        );

        System.out.println(
            "Idade: " + pessoa.getIdade()
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert
Idade: 17</code></div>

    <p>

        Os atributos continuam privados.

    </p>

    <p>

        O acesso acontece por meio dos métodos
        públicos getNome(), setNome(), getIdade()
        e setIdade().

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PADRÃO DE NOMES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Padrão de nomes dos getters e setters

    </h2>

    <p>

        Por convenção, os métodos seguem um padrão
        de nomes.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Atributo</span>
            <span>Getter</span>
            <span>Setter</span>

        </div>

        <div class="aula-tabela-linha">

            <span>nome</span>
            <span>getNome()</span>
            <span>setNome()</span>

        </div>

        <div class="aula-tabela-linha">

            <span>idade</span>
            <span>getIdade()</span>
            <span>setIdade()</span>

        </div>

        <div class="aula-tabela-linha">

            <span>preco</span>
            <span>getPreco()</span>
            <span>setPreco()</span>

        </div>

        <div class="aula-tabela-linha">

            <span>disponivel</span>
            <span>isDisponivel()</span>
            <span>setDisponivel()</span>

        </div>

    </div>

    <p>

        Para atributos booleanos, é comum utilizar
        o prefixo <strong>is</strong> no getter.

    </p>

    <pre class="codigo-java"><code>private boolean disponivel;


public boolean isDisponivel() {

    return disponivel;

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| THIS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Entendendo o this nos setters

    </h2>

    <p>

        Em um setter, é comum que o parâmetro tenha
        o mesmo nome do atributo.

    </p>

    <pre class="codigo-java"><code>private String nome;


public void setNome(String nome) {

    this.nome = nome;

}</code></pre>

    <p>

        Nesse exemplo:

    </p>

    <ul>

        <li>

            <strong>this.nome</strong> representa
            o atributo pertencente ao objeto atual;

        </li>

        <li>

            <strong>nome</strong> representa o parâmetro
            recebido pelo método.

        </li>

    </ul>

    <div class="aula-destaque">

        <strong>

            Podemos ler assim:

        </strong>

        <p>

            Guarde no atributo nome do objeto o valor
            recebido pelo parâmetro nome.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VALIDAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Validando valores nos setters

    </h2>

    <p>

        Uma das vantagens do encapsulamento é poder
        validar os valores antes de armazená-los.

    </p>

    <p>

        Por exemplo, podemos impedir que uma idade
        negativa seja registrada.

    </p>

    <pre class="codigo-java"><code>public class Pessoa {

    private int idade;


    public int getIdade() {

        return idade;

    }


    public void setIdade(int idade) {

        if (idade &gt;= 0) {

            this.idade = idade;

        }

    }

}</code></pre>

    <p>

        Agora o valor só será armazenado quando
        for maior ou igual a zero.

    </p>

    <pre class="codigo-java"><code>Pessoa pessoa = new Pessoa();

pessoa.setIdade(-10);

System.out.println(
    pessoa.getIdade()
);</code></pre>

    <p>

        Como o valor é inválido, a idade não será
        alterada.

    </p>

    <div class="resultado-codigo"><code>0</code></div>

    <p>

        O valor continua zero porque esse é o valor
        padrão de um atributo int.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MENSAGEM DE VALIDAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Informando quando o valor é inválido

    </h2>

    <p>

        Também podemos mostrar uma mensagem quando
        o valor recebido não for aceito.

    </p>

    <pre class="codigo-java"><code>public void setIdade(int idade) {

    if (idade &gt;= 0) {

        this.idade = idade;

    } else {

        System.out.println(
            "A idade não pode ser negativa."
        );

    }

}</code></pre>

    <p>

        Ao executar:

    </p>

    <pre class="codigo-java"><code>pessoa.setIdade(-5);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>A idade não pode ser negativa.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| CONSTRUTOR E ENCAPSULAMENTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Encapsulamento com construtor

    </h2>

    <p>

        Podemos combinar o encapsulamento com os
        construtores estudados na aula anterior.

    </p>

    <h3>

        Arquivo Produto.java

    </h3>

    <pre class="codigo-java"><code>public class Produto {

    private String nome;

    private double preco;


    public Produto(
        String nome,
        double preco
    ) {

        this.nome = nome;

        setPreco(preco);

    }


    public String getNome() {

        return nome;

    }


    public void setNome(String nome) {

        this.nome = nome;

    }


    public double getPreco() {

        return preco;

    }


    public void setPreco(double preco) {

        if (preco &gt;= 0) {

            this.preco = preco;

        }

    }

}</code></pre>

    <p>

        O construtor utiliza o método setPreco()
        para aproveitar a validação já existente.

    </p>

    <div class="aula-destaque">

        <strong>

            Evitando regras duplicadas:

        </strong>

        <p>

            Quando uma validação já existe em um método,
            o construtor pode utilizar esse método em vez
            de repetir a mesma lógica.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SOMENTE LEITURA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Atributo somente para leitura

    </h2>

    <p>

        Nem todo atributo precisa possuir um setter.

    </p>

    <p>

        Podemos permitir que um valor seja consultado,
        mas não alterado diretamente depois da criação.

    </p>

    <pre class="codigo-java"><code>public class Usuario {

    private String email;


    public Usuario(String email) {

        this.email = email;

    }


    public String getEmail() {

        return email;

    }

}</code></pre>

    <p>

        A classe possui getEmail(), mas não possui
        setEmail().

    </p>

    <p>

        Portanto, o e-mail pode ser consultado,
        mas não pode ser modificado por meio
        de um setter.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| GETTER SEM SETTER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Nem todo atributo precisa de getter e setter

    </h2>

    <p>

        Encapsulamento não significa criar getters
        e setters automaticamente para todos os atributos.

    </p>

    <p>

        Devemos pensar no que realmente pode ser
        consultado ou alterado por outras classes.

    </p>

    <p>

        Em uma conta bancária, por exemplo, permitir
        um método setSaldo() poderia ser perigoso.

    </p>

    <pre class="codigo-java"><code>public void setSaldo(double saldo) {

    this.saldo = saldo;

}</code></pre>

    <p>

        Esse método permitiria definir qualquer saldo,
        sem representar uma operação bancária real.

    </p>

    <p>

        Uma solução melhor é criar métodos específicos:

    </p>

    <pre class="codigo-java"><code>public void depositar(double valor) {

    // Validação e depósito

}


public boolean sacar(double valor) {

    // Validação e saque

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Métodos devem representar operações válidas.

        </strong>

        <p>

            Em vez de liberar qualquer alteração,
            a classe pode oferecer apenas as ações
            permitidas pelas regras do sistema.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CONTA BANCÁRIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: conta bancária

    </h2>

    <p>

        Vamos criar uma conta com saldo protegido.

    </p>

    <h3>

        Arquivo Conta.java

    </h3>

    <pre class="codigo-java"><code>public class Conta {

    private String titular;

    private double saldo;


    public Conta(
        String titular,
        double saldoInicial
    ) {

        this.titular = titular;

        if (saldoInicial &gt;= 0) {

            this.saldo = saldoInicial;

        }

    }


    public String getTitular() {

        return titular;

    }


    public double getSaldo() {

        return saldo;

    }


    public void depositar(double valor) {

        if (valor &gt; 0) {

            saldo += valor;

            System.out.println(
                "Depósito realizado."
            );

        } else {

            System.out.println(
                "Valor de depósito inválido."
            );

        }

    }


    public boolean sacar(double valor) {

        if (
            valor &gt; 0
            &amp;&amp; valor &lt;= saldo
        ) {

            saldo -= valor;

            return true;

        }

        return false;

    }

}</code></pre>

    <p>

        Observe que não existe um método setSaldo().

    </p>

    <p>

        O saldo só pode ser alterado pelos métodos
        depositar() e sacar().

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| UTILIZANDO A CONTA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando a conta encapsulada

    </h2>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Conta conta = new Conta(
            "Robert",
            500.00
        );

        conta.depositar(200.00);

        boolean saqueRealizado =
            conta.sacar(150.00);

        System.out.println(
            "Titular: "
            + conta.getTitular()
        );

        System.out.println(
            "Saldo: R$ "
            + conta.getSaldo()
        );

        System.out.println(
            "Saque realizado: "
            + saqueRealizado
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Depósito realizado.
Titular: Robert
Saldo: R$ 550.0
Saque realizado: true</code></div>

    <p>

        O saldo inicial era 500.

    </p>

    <p>

        Depois do depósito de 200, passou para 700.

    </p>

    <p>

        Após o saque de 150, o saldo final ficou em 550.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| SAQUE INVÁLIDO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Impedindo um saque inválido

    </h2>

    <p>

        O método sacar() também impede que o usuário
        retire um valor maior do que o saldo disponível.

    </p>

    <pre class="codigo-java"><code>Conta conta = new Conta(
    "Robert",
    100.00
);

boolean resultado =
    conta.sacar(500.00);

System.out.println(resultado);

System.out.println(
    conta.getSaldo()
);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>false
100.0</code></div>

    <p>

        O saque não foi realizado e o saldo
        permaneceu inalterado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CLASSE CAFE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: produto da cafeteria

    </h2>

    <h3>

        Arquivo Cafe.java

    </h3>

    <pre class="codigo-java"><code>public class Cafe {

    private String nome;

    private String tamanho;

    private double preco;

    private boolean disponivel;


    public Cafe(
        String nome,
        String tamanho,
        double preco
    ) {

        this.nome = nome;

        this.tamanho = tamanho;

        setPreco(preco);

        this.disponivel = true;

    }


    public String getNome() {

        return nome;

    }


    public String getTamanho() {

        return tamanho;

    }


    public double getPreco() {

        return preco;

    }


    public boolean isDisponivel() {

        return disponivel;

    }


    public void setPreco(double preco) {

        if (preco &gt; 0) {

            this.preco = preco;

        }

    }


    public void vender() {

        if (disponivel) {

            disponivel = false;

        }

    }


    public void disponibilizar() {

        disponivel = true;

    }


    public void exibirInformacoes() {

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

    <p>

        Os atributos estão protegidos e as alterações
        acontecem por meio de métodos definidos pela
        própria classe.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| UTILIZANDO O CAFE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando a classe Cafe

    </h2>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Cafe cafe = new Cafe(
            "Café com leite",
            "Médio",
            8.50
        );

        cafe.exibirInformacoes();

        cafe.setPreco(9.00);

        cafe.vender();

        System.out.println();

        System.out.println(
            "Novo preço: R$ "
            + cafe.getPreco()
        );

        System.out.println(
            "Disponível: "
            + cafe.isDisponivel()
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

Novo preço: R$ 9.0
Disponível: false</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| BENEFÍCIOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Vantagens do encapsulamento

    </h2>

    <ul>

        <li>

            <strong>Proteção:</strong>
            evita alterações diretas nos atributos;

        </li>

        <li>

            <strong>Validação:</strong>
            permite verificar valores antes de armazená-los;

        </li>

        <li>

            <strong>Controle:</strong>
            define quais operações são permitidas;

        </li>

        <li>

            <strong>Organização:</strong>
            mantém os dados e suas regras na mesma classe;

        </li>

        <li>

            <strong>Manutenção:</strong>
            facilita mudanças futuras no funcionamento
            interno da classe;

        </li>

        <li>

            <strong>Segurança:</strong>
            reduz o risco de dados inválidos;

        </li>

        <li>

            <strong>Clareza:</strong>
            métodos como depositar() e sacar() deixam
            a intenção do código mais evidente.

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

        Acessar um atributo privado diretamente

    </h3>

    <pre class="codigo-java"><code>pessoa.nome = "Robert";</code></pre>

    <p>

        Quando nome é private, devemos utilizar:

    </p>

    <pre class="codigo-java"><code>pessoa.setNome("Robert");</code></pre>


    <h3>

        Esquecer o return no getter

    </h3>

    <p>

        Este getter está incorreto:

    </p>

    <pre class="codigo-java"><code>public String getNome() {

}</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>public String getNome() {

    return nome;

}</code></pre>


    <h3>

        Utilizar o tipo incorreto no getter

    </h3>

    <p>

        Este getter está incorreto:

    </p>

    <pre class="codigo-java"><code>public int getNome() {

    return nome;

}</code></pre>

    <p>

        Como nome é String, o tipo do método
        também deve ser String.

    </p>

    <pre class="codigo-java"><code>public String getNome() {

    return nome;

}</code></pre>


    <h3>

        Esquecer o this no setter

    </h3>

    <p>

        Este código não altera o atributo corretamente:

    </p>

    <pre class="codigo-java"><code>public void setNome(String nome) {

    nome = nome;

}</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>public void setNome(String nome) {

    this.nome = nome;

}</code></pre>


    <h3>

        Criar setter para dados que não deveriam
        ser alterados livremente

    </h3>

    <p>

        Um setSaldo() permitiria definir o saldo
        sem representar uma operação válida.

    </p>

    <p>

        Para uma conta, é melhor utilizar métodos
        como depositar() e sacar().

    </p>


    <h3>

        Criar validação apenas no Main

    </h3>

    <p>

        As regras que protegem um atributo devem
        ficar dentro da classe responsável pelo dado.

    </p>

    <p>

        Assim, a proteção continuará funcionando
        em qualquer parte do programa.

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

        Vamos criar uma classe para representar
        um produto com preço protegido.

    </p>

    <h3>

        Arquivo Produto.java

    </h3>

    <pre class="codigo-java"><code>public class Produto {

    private String nome;

    private double preco;

    private int quantidade;


    public Produto(
        String nome,
        double preco,
        int quantidade
    ) {

        this.nome = nome;

        setPreco(preco);

        setQuantidade(quantidade);

    }


    public String getNome() {

        return nome;

    }


    public double getPreco() {

        return preco;

    }


    public int getQuantidade() {

        return quantidade;

    }


    public void setPreco(double preco) {

        if (preco &gt;= 0) {

            this.preco = preco;

        }

    }


    public void setQuantidade(int quantidade) {

        if (quantidade &gt;= 0) {

            this.quantidade = quantidade;

        }

    }


    public double calcularTotal() {

        return preco * quantidade;

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Produto produto = new Produto(
            "Café",
            18.50,
            3
        );

        System.out.println(
            "Produto: "
            + produto.getNome()
        );

        System.out.println(
            "Preço: R$ "
            + produto.getPreco()
        );

        System.out.println(
            "Quantidade: "
            + produto.getQuantidade()
        );

        System.out.println(
            "Total: R$ "
            + produto.calcularTotal()
        );

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
| DESAFIO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Desafio da aula

    </h2>

    <p>

        Crie uma classe chamada
        <strong>Aluno</strong> com os atributos privados:

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
            nota;
        </li>

        <li>
            matriculado.
        </li>

    </ul>

    <p>

        Adicione:

    </p>

    <ul>

        <li>
            getters para consultar os dados;
        </li>

        <li>
            setter para o nome;
        </li>

        <li>
            setter para a idade, impedindo valores negativos;
        </li>

        <li>
            setter para a nota, aceitando apenas valores
            entre 0 e 10;
        </li>

        <li>
            método matricular();
        </li>

        <li>
            método cancelarMatricula();
        </li>

        <li>
            método verificarSituacao(), retornando
            "Aprovado" ou "Reprovado".
        </li>

    </ul>

    <div class="resultado-codigo"><code>Nome: Robert
Idade: 17
Curso: Informática
Nota: 8.5
Matriculado: true
Situação: Aprovado</code></div>

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
        encapsulamento para proteger os dados
        de uma classe.

    </p>

    <ul>

        <li>
            encapsulamento protege os dados internos
            dos objetos;
        </li>

        <li>
            private restringe o acesso à própria classe;
        </li>

        <li>
            public permite disponibilizar métodos
            para outras classes;
        </li>

        <li>
            getters consultam valores;
        </li>

        <li>
            setters alteram valores;
        </li>

        <li>
            this representa o objeto atual;
        </li>

        <li>
            setters podem validar dados;
        </li>

        <li>
            nem todo atributo precisa possuir
            getter e setter;
        </li>

        <li>
            alguns dados devem ser alterados por
            métodos específicos;
        </li>

        <li>
            uma conta deve utilizar depositar()
            e sacar() em vez de setSaldo();
        </li>

        <li>
            construtores podem utilizar setters
            para aproveitar validações;
        </li>

        <li>
            encapsulamento deixa o código mais seguro,
            organizado e fácil de manter.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                O objeto deve controlar seus próprios
                dados e permitir somente operações válidas.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos sobre herança
        e como uma classe pode aproveitar atributos
        e métodos de outra classe.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Encapsular não é apenas esconder dados.
        É definir maneiras seguras de trabalhar com eles."

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

        Próxima aula: Herança →

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