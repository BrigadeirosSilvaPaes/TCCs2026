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
                "metodos"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "retorno"
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
    content="Aprenda como enviar informações para métodos Java utilizando parâmetros e argumentos."
>

<title>
    Parâmetros e Argumentos | Java, Café e Código
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

        AULA 13

    </span>

    <h1>

        Parâmetros e Argumentos

    </h1>

    <p>

        Aprenda como enviar informações para métodos
        e criar tarefas mais flexíveis, organizadas
        e reutilizáveis.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos que recebem informações

    </h2>

    <p>

        Na aula anterior, aprendemos que métodos
        são blocos de código responsáveis por
        realizar tarefas específicas.

    </p>

    <pre class="codigo-java"><code>static void mostrarMensagem() {

    System.out.println(
        "Olá, estudante!"
    );

}</code></pre>

    <p>

        Esse método sempre apresenta a mesma mensagem.

    </p>

    <p>

        Porém, podemos querer utilizar o mesmo método
        para apresentar diferentes pessoas.

    </p>

    <pre class="resultado-codigo"><code>Olá, Robert!
Olá, Maria!
Olá, João!</code></pre>

    <p>

        Em vez de criar um método diferente para cada
        pessoa, podemos criar um único método capaz
        de receber o nome que deverá utilizar.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            Parâmetros permitem enviar informações
            para dentro de um método.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PARÂMETROS E ARGUMENTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que são parâmetros e argumentos?

    </h2>

    <p>

        Parâmetros e argumentos trabalham juntos,
        mas representam partes diferentes.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Conceito</span>
            <span>Significado</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Parâmetro</span>

            <span>
                Variável definida na declaração do método
            </span>

            <span>String nome</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Argumento</span>

            <span>
                Valor enviado durante a chamada
            </span>

            <span>"Robert"</span>

        </div>

    </div>

    <div class="aula-destaque">

        <strong>

            Pense assim:

        </strong>

        <p>

            O parâmetro é o espaço preparado
            para receber uma informação.

        </p>

        <p>

            O argumento é a informação realmente
            enviada para esse espaço.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PRIMEIRO PARÂMETRO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando um método com parâmetro

    </h2>

    <p>

        Os parâmetros são declarados dentro
        dos parênteses do método.

    </p>

    <pre class="codigo-java"><code>static void apresentar(String nome) {

    System.out.println(
        "Olá, " + nome + "!"
    );

}</code></pre>

    <p>

        Dentro dos parênteses temos:

    </p>

    <pre class="codigo-java"><code>String nome</code></pre>

    <p>

        Essa declaração possui duas partes.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Função</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Tipo</span>

            <span>
                Define qual informação será recebida
            </span>

            <span>String</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Nome</span>

            <span>
                Identifica o parâmetro dentro do método
            </span>

            <span>nome</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CHAMADA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Enviando um argumento

    </h2>

    <p>

        Quando chamamos o método, precisamos enviar
        uma informação compatível com o parâmetro.

    </p>

    <pre class="codigo-java"><code>apresentar("Robert");</code></pre>

    <p>

        O texto <strong>"Robert"</strong>
        é o argumento.

    </p>

    <p>

        Esse valor será recebido pelo parâmetro
        chamado <strong>nome</strong>.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void apresentar(String nome) {

        System.out.println(
            "Olá, " + nome + "!"
        );

    }


    public static void main(String[] args) {

        apresentar("Robert");

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Olá, Robert!</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| FLUXO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como a informação é enviada?

    </h2>

    <div class="aula-destaque">

        <p>
            O programa chama apresentar("Robert").
        </p>

        <p>↓</p>

        <p>
            O valor "Robert" é enviado como argumento.
        </p>

        <p>↓</p>

        <p>
            O parâmetro nome recebe esse valor.
        </p>

        <p>↓</p>

        <p>
            O método utiliza nome em suas instruções.
        </p>

        <p>↓</p>

        <p>
            A mensagem é exibida.
        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| REUTILIZAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Chamando o método com valores diferentes

    </h2>

    <p>

        Podemos chamar o mesmo método várias vezes,
        enviando argumentos diferentes.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void apresentar(String nome) {

        System.out.println(
            "Olá, " + nome + "!"
        );

    }


    public static void main(String[] args) {

        apresentar("Robert");
        apresentar("Maria");
        apresentar("João");

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Olá, Robert!
Olá, Maria!
Olá, João!</code></div>

    <div class="aula-destaque">

        <strong>

            Vantagem:

        </strong>

        <p>

            Um único método pode trabalhar
            com diversas informações diferentes.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VARIÁVEL COMO ARGUMENTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Enviando uma variável

    </h2>

    <p>

        O argumento não precisa ser escrito
        diretamente na chamada.

    </p>

    <p>

        Também podemos enviar o conteúdo
        de uma variável.

    </p>

    <pre class="codigo-java"><code>String nomeUsuario = "Robert";

apresentar(nomeUsuario);</code></pre>

    <p>

        O valor armazenado em nomeUsuario será
        enviado para o parâmetro nome.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void apresentar(String nome) {

        System.out.println(
            "Olá, " + nome + "!"
        );

    }


    public static void main(String[] args) {

        String nomeUsuario = "Robert";

        apresentar(nomeUsuario);

    }

}</code></pre>

    <div class="resultado-codigo"><code>Olá, Robert!</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| TIPOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Diferentes tipos de parâmetros

    </h2>

    <p>

        Um parâmetro pode utilizar diferentes
        tipos de dados.

    </p>

    <pre class="codigo-java"><code>static void mostrarIdade(int idade) {

    System.out.println(
        "Idade: " + idade
    );

}</code></pre>

    <pre class="codigo-java"><code>static void mostrarAltura(double altura) {

    System.out.println(
        "Altura: " + altura
    );

}</code></pre>

    <pre class="codigo-java"><code>static void mostrarInicial(char inicial) {

    System.out.println(
        "Inicial: " + inicial
    );

}</code></pre>

    <pre class="codigo-java"><code>static void mostrarSituacao(boolean ativo) {

    System.out.println(
        "Ativo: " + ativo
    );

}</code></pre>

    <p>

        Cada argumento precisa ser compatível
        com o tipo do parâmetro correspondente.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| COMPATIBILIDADE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O tipo do argumento deve ser compatível

    </h2>

    <p>

        Observe este método:

    </p>

    <pre class="codigo-java"><code>static void mostrarIdade(int idade) {

    System.out.println(
        "Idade: " + idade
    );

}</code></pre>

    <p>

        A chamada abaixo está correta:

    </p>

    <pre class="codigo-java"><code>mostrarIdade(17);</code></pre>

    <p>

        Porém, esta chamada está incorreta:

    </p>

    <pre class="codigo-java"><code>mostrarIdade("dezessete");</code></pre>

    <p>

        O parâmetro espera um valor do tipo int,
        mas foi enviado um texto do tipo String.

    </p>

    <div class="aula-destaque">

        <strong>

            Regra:

        </strong>

        <p>

            O tipo do argumento precisa ser aceito
            pelo tipo definido no parâmetro.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIOS PARÂMETROS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando mais de um parâmetro

    </h2>

    <p>

        Um método pode receber várias informações.

    </p>

    <p>

        Os parâmetros são separados por vírgulas.

    </p>

    <pre class="codigo-java"><code>static void apresentarPessoa(
    String nome,
    int idade
) {

    System.out.println(
        "Nome: " + nome
    );

    System.out.println(
        "Idade: " + idade
    );

}</code></pre>

    <p>

        Para chamar o método, enviamos
        dois argumentos.

    </p>

    <pre class="codigo-java"><code>apresentarPessoa("Robert", 17);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert
Idade: 17</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| ORDEM
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A ordem dos argumentos

    </h2>

    <p>

        Os argumentos precisam seguir a mesma ordem
        dos parâmetros.

    </p>

    <pre class="codigo-java"><code>static void apresentarPessoa(
    String nome,
    int idade
) {

}</code></pre>

    <p>

        O primeiro parâmetro espera uma String.

    </p>

    <p>

        O segundo parâmetro espera um int.

    </p>

    <p>

        Portanto, esta chamada está correta:

    </p>

    <pre class="codigo-java"><code>apresentarPessoa("Robert", 17);</code></pre>

    <p>

        Esta chamada está incorreta:

    </p>

    <pre class="codigo-java"><code>apresentarPessoa(17, "Robert");</code></pre>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            Além da quantidade, a ordem e os tipos
            dos argumentos precisam corresponder
            aos parâmetros.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| QUANTIDADE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A quantidade também precisa corresponder

    </h2>

    <p>

        Se o método possui dois parâmetros,
        normalmente precisamos enviar dois argumentos.

    </p>

    <pre class="codigo-java"><code>static void apresentarPessoa(
    String nome,
    int idade
) {

}</code></pre>

    <p>

        Chamada correta:

    </p>

    <pre class="codigo-java"><code>apresentarPessoa("Robert", 17);</code></pre>

    <p>

        Chamada incorreta por falta de argumento:

    </p>

    <pre class="codigo-java"><code>apresentarPessoa("Robert");</code></pre>

    <p>

        Chamada incorreta por excesso de argumentos:

    </p>

    <pre class="codigo-java"><code>apresentarPessoa(
    "Robert",
    17,
    "Informática"
);</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| CÁLCULOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Realizando cálculos com parâmetros

    </h2>

    <p>

        Os valores recebidos podem ser utilizados
        em cálculos.

    </p>

    <pre class="codigo-java"><code>static void somar(
    int numero1,
    int numero2
) {

    int resultado =
        numero1 + numero2;

    System.out.println(
        "Resultado: " + resultado
    );

}</code></pre>

    <p>

        Chamando o método:

    </p>

    <pre class="codigo-java"><code>somar(10, 5);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Resultado: 15</code></div>

    <p>

        Também podemos realizar outras operações.

    </p>

    <pre class="codigo-java"><code>static void multiplicar(
    double valor,
    int quantidade
) {

    double total =
        valor * quantidade;

    System.out.println(
        "Total: " + total
    );

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| CONDIÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando parâmetros em condições

    </h2>

    <p>

        Um parâmetro também pode ser utilizado
        dentro de estruturas de decisão.

    </p>

    <pre class="codigo-java"><code>static void verificarIdade(int idade) {

    if (idade &gt;= 18) {

        System.out.println(
            "Maior de idade."
        );

    } else {

        System.out.println(
            "Menor de idade."
        );

    }

}</code></pre>

    <p>

        Chamadas:

    </p>

    <pre class="codigo-java"><code>verificarIdade(20);
verificarIdade(15);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Maior de idade.
Menor de idade.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| ESCOPO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Onde o parâmetro pode ser utilizado?

    </h2>

    <p>

        Um parâmetro existe dentro do método
        em que foi declarado.

    </p>

    <pre class="codigo-java"><code>static void apresentar(String nome) {

    System.out.println(nome);

}</code></pre>

    <p>

        Dentro do método apresentar, podemos
        utilizar a variável nome.

    </p>

    <p>

        Fora desse método, esse parâmetro
        não está disponível.

    </p>

    <pre class="codigo-java"><code>static void apresentar(String nome) {

    System.out.println(nome);

}


public static void main(String[] args) {

    System.out.println(nome);

}</code></pre>

    <p>

        A tentativa de utilizar nome no main
        está incorreta.

    </p>

    <div class="aula-destaque">

        <strong>

            Esse limite é chamado de escopo.

        </strong>

        <p>

            O parâmetro pertence ao método
            em que foi declarado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Enviando dados recebidos pelo Scanner

    </h2>

    <p>

        Podemos receber informações do usuário
        e enviá-las para um método.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    static void apresentarPessoa(
        String nome,
        int idade
    ) {

        System.out.println();
        System.out.println(
            "Nome: " + nome
        );

        System.out.println(
            "Idade: " + idade
        );

    }


    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        System.out.println(
            "Digite seu nome:"
        );

        String nome =
            entrada.nextLine();

        System.out.println(
            "Digite sua idade:"
        );

        int idade =
            entrada.nextInt();

        apresentarPessoa(
            nome,
            idade
        );

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
Idade: 17</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO COMPLETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: dados do estudante

    </h2>

    <pre class="codigo-java"><code>public class Main {

    static void apresentarEstudante(
        String nome,
        int idade,
        String curso,
        double nota
    ) {

        System.out.println(
            "Nome: " + nome
        );

        System.out.println(
            "Idade: " + idade
        );

        System.out.println(
            "Curso: " + curso
        );

        System.out.println(
            "Nota: " + nota
        );

        if (nota &gt;= 7) {

            System.out.println(
                "Situação: aprovado."
            );

        } else {

            System.out.println(
                "Situação: reprovado."
            );

        }

    }


    public static void main(String[] args) {

        String nome = "Robert";
        int idade = 17;
        String curso = "Informática";
        double nota = 8.5;

        apresentarEstudante(
            nome,
            idade,
            curso,
            nota
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Nome: Robert
Idade: 17
Curso: Informática
Nota: 8.5
Situação: aprovado.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| VANTAGENS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Por que utilizar parâmetros?

    </h2>

    <p>

        Sem parâmetros, poderíamos acabar criando
        métodos muito específicos.

    </p>

    <pre class="codigo-java"><code>mostrarRobert();
mostrarMaria();
mostrarJoao();</code></pre>

    <p>

        Com parâmetros, criamos um único método
        reutilizável.

    </p>

    <pre class="codigo-java"><code>apresentar("Robert");
apresentar("Maria");
apresentar("João");</code></pre>

    <ul>

        <li>
            evitam repetição de código;
        </li>

        <li>
            tornam os métodos flexíveis;
        </li>

        <li>
            permitem trabalhar com dados diferentes;
        </li>

        <li>
            ajudam a separar tarefas;
        </li>

        <li>
            facilitam a manutenção do programa.
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

        Não informar o tipo do parâmetro

    </h3>

    <p>

        Esta declaração está incorreta:

    </p>

    <pre class="codigo-java"><code>static void apresentar(nome) {

}</code></pre>

    <p>

        Em Java, precisamos informar o tipo.

    </p>

    <pre class="codigo-java"><code>static void apresentar(String nome) {

}</code></pre>

    <h3>

        Utilizar ponto e vírgula entre parâmetros

    </h3>

    <p>

        Esta declaração está incorreta:

    </p>

    <pre class="codigo-java"><code>static void apresentar(
    String nome;
    int idade
) {

}</code></pre>

    <p>

        Os parâmetros são separados por vírgula.

    </p>

    <pre class="codigo-java"><code>static void apresentar(
    String nome,
    int idade
) {

}</code></pre>

    <h3>

        Enviar argumentos em ordem incorreta

    </h3>

    <pre class="codigo-java"><code>apresentarPessoa(17, "Robert");</code></pre>

    <p>

        A ordem precisa corresponder à declaração.

    </p>

    <pre class="codigo-java"><code>apresentarPessoa("Robert", 17);</code></pre>

    <h3>

        Enviar uma quantidade incorreta

    </h3>

    <p>

        Se o método espera dois parâmetros,
        envie os dois argumentos necessários.

    </p>

    <h3>

        Enviar um tipo incompatível

    </h3>

    <pre class="codigo-java"><code>mostrarIdade("17");</code></pre>

    <p>

        O texto "17" não é um valor int.

    </p>

    <pre class="codigo-java"><code>mostrarIdade(17);</code></pre>

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

        Crie um método que receba o nome
        de um produto, seu preço e a quantidade.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void mostrarCompra(
        String produto,
        double preco,
        int quantidade
    ) {

        double total =
            preco * quantidade;

        System.out.println(
            "Produto: " + produto
        );

        System.out.println(
            "Preço: R$ " + preco
        );

        System.out.println(
            "Quantidade: " + quantidade
        );

        System.out.println(
            "Total: R$ " + total
        );

    }


    public static void main(String[] args) {

        mostrarCompra(
            "Caderno",
            15.50,
            2
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Produto: Caderno
Preço: R$ 15.5
Quantidade: 2
Total: R$ 31.0</code></div>

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

        Crie um método chamado
        <strong>mostrarResultado</strong>.

    </p>

    <p>

        Ele deverá receber:

    </p>

    <ul>

        <li>
            o nome do estudante;
        </li>

        <li>
            a primeira nota;
        </li>

        <li>
            a segunda nota;
        </li>

        <li>
            a frequência.
        </li>

    </ul>

    <p>

        Dentro do método:

    </p>

    <ul>

        <li>
            calcule a média;
        </li>

        <li>
            mostre os dados;
        </li>

        <li>
            informe se o estudante foi aprovado.
        </li>

    </ul>

    <p>

        Para aprovação, utilize:

    </p>

    <pre class="codigo-java"><code>media &gt;= 7 &amp;&amp; frequencia &gt;= 75</code></pre>

    <div class="resultado-codigo"><code>Aluno: Robert
Média: 8.0
Frequência: 80.0
Situação: aprovado.</code></div>

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

        Nesta aula, aprendemos como enviar
        informações para métodos.

    </p>

    <ul>

        <li>
            parâmetros são variáveis declaradas
            no método;
        </li>

        <li>
            argumentos são valores enviados
            durante a chamada;
        </li>

        <li>
            os parâmetros ficam entre
            os parênteses do método;
        </li>

        <li>
            um método pode receber
            vários parâmetros;
        </li>

        <li>
            parâmetros podem utilizar
            diferentes tipos de dados;
        </li>

        <li>
            a ordem dos argumentos precisa
            corresponder à ordem dos parâmetros;
        </li>

        <li>
            a quantidade de argumentos também
            precisa corresponder;
        </li>

        <li>
            argumentos podem ser valores diretos
            ou variáveis;
        </li>

        <li>
            parâmetros pertencem ao método
            em que foram declarados;
        </li>

        <li>
            parâmetros tornam os métodos
            mais reutilizáveis.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Parâmetros permitem que o mesmo
                método trabalhe com diferentes
                informações.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como um método
        pode devolver um resultado utilizando return.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Métodos se tornam mais flexíveis quando
        podem receber e utilizar diferentes informações."

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

        Próxima aula: Retorno →

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