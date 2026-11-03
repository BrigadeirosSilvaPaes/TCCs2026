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
                "switch"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "parametros"
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
    content="Aprenda como criar e utilizar métodos para organizar programas Java."
>

<title>
    Métodos em Java | Java, Café e Código
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

        AULA 12

    </span>

    <h1>

        Métodos em Java

    </h1>

    <p>

        Aprenda como dividir um programa em tarefas
        menores, organizar o código e reutilizar
        instruções.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Organizando programas maiores

    </h2>

    <p>

        Até agora, colocamos grande parte das instruções
        dentro do método main.

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        System.out.println("Bem-vindo!");

        System.out.println("Nome: Robert");
        System.out.println("Curso: Informática");

        System.out.println("Programa encerrado.");

    }

}</code></pre>

    <p>

        Em um programa pequeno, essa organização
        pode funcionar.

    </p>

    <p>

        Porém, conforme o programa cresce, o método
        main pode ficar muito grande e difícil
        de compreender.

    </p>

    <p>

        Para organizar melhor as tarefas,
        podemos criar outros métodos.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            Um método reúne instruções responsáveis
            por realizar uma tarefa específica.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE É MÉTODO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é um método?

    </h2>

    <p>

        Um método é um bloco de código criado
        para realizar uma determinada tarefa.

    </p>

    <p>

        Podemos criar métodos para:

    </p>

    <ul>

        <li>
            mostrar uma mensagem;
        </li>

        <li>
            exibir um menu;
        </li>

        <li>
            apresentar dados de um usuário;
        </li>

        <li>
            realizar um cálculo;
        </li>

        <li>
            salvar uma informação;
        </li>

        <li>
            executar outras tarefas do programa.
        </li>

    </ul>

    <div class="aula-destaque">

        <strong>

            Pense em um método como uma tarefa:

        </strong>

        <p>

            Em vez de repetir várias instruções,
            colocamos essas instruções dentro
            de um método e pedimos que ele execute
            a tarefa quando necessário.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PRIMEIRO MÉTODO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando o primeiro método

    </h2>

    <p>

        Vamos criar um método chamado
        <strong>mostrarMensagem</strong>.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void mostrarMensagem() {

        System.out.println(
            "Olá! Seja bem-vindo ao Java."
        );

    }

}</code></pre>

    <p>

        Esse método possui uma instrução
        System.out.println().

    </p>

    <p>

        Porém, apenas criar o método não faz
        com que ele seja executado.

    </p>

    <p>

        Para executar sua tarefa, precisamos
        chamá-lo.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| DECLARAR E CHAMAR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criar e chamar são ações diferentes

    </h2>

    <p>

        Quando escrevemos o método, estamos
        definindo qual tarefa ele realizará.

    </p>

    <pre class="codigo-java"><code>static void mostrarMensagem() {

    System.out.println(
        "Olá! Seja bem-vindo ao Java."
    );

}</code></pre>

    <p>

        Quando escrevemos o nome seguido
        de parênteses, estamos chamando o método.

    </p>

    <pre class="codigo-java"><code>mostrarMensagem();</code></pre>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Ação</span>
            <span>Significado</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Declaração</span>
            <span>Define o que o método fará</span>
            <span>static void mostrarMensagem()</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Chamada</span>
            <span>Solicita a execução do método</span>
            <span>mostrarMensagem();</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CHAMANDO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Chamando um método

    </h2>

    <p>

        Vamos chamar o método dentro do main.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void mostrarMensagem() {

        System.out.println(
            "Olá! Seja bem-vindo ao Java."
        );

    }


    public static void main(String[] args) {

        mostrarMensagem();

    }

}</code></pre>

    <p>

        O programa começa sua execução pelo main.

    </p>

    <p>

        Quando encontra a chamada:

    </p>

    <pre class="codigo-java"><code>mostrarMensagem();</code></pre>

    <p>

        o Java executa as instruções existentes
        dentro daquele método.

    </p>

    <div class="resultado-codigo"><code>Olá! Seja bem-vindo ao Java.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| ESTRUTURA DO MÉTODO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Partes de um método

    </h2>

    <pre class="codigo-java"><code>static void mostrarMensagem() {

    System.out.println("Olá!");

}</code></pre>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Função</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>static</span>
            <span>Permite a chamada no contexto atual</span>
            <span>static</span>

        </div>

        <div class="aula-tabela-linha">

            <span>void</span>
            <span>Indica que não existe valor retornado</span>
            <span>void</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Nome</span>
            <span>Identifica o método</span>
            <span>mostrarMensagem</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Parênteses</span>
            <span>Receberão parâmetros posteriormente</span>
            <span>()</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Chaves</span>
            <span>Delimitam as instruções do método</span>
            <span>{ }</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VOID
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que significa void?

    </h2>

    <p>

        A palavra <strong>void</strong> indica
        que o método não devolve um valor.

    </p>

    <pre class="codigo-java"><code>static void mostrarMensagem() {

    System.out.println("Olá!");

}</code></pre>

    <p>

        Esse método executa uma tarefa:
        mostrar uma mensagem.

    </p>

    <p>

        Porém, ele não entrega um resultado
        para o local onde foi chamado.

    </p>

    <div class="aula-destaque">

        <strong>

            Nesta aula:

        </strong>

        <p>

            Trabalharemos com métodos void,
            responsáveis apenas por executar tarefas.

        </p>

        <p>

            Em uma aula futura, estudaremos métodos
            que retornam valores.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| STATIC
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Por que utilizamos static?

    </h2>

    <p>

        O método main possui a palavra
        <strong>static</strong>.

    </p>

    <pre class="codigo-java"><code>public static void main(String[] args) {

}</code></pre>

    <p>

        Nos exemplos desta etapa, também utilizamos
        static nos métodos criados.

    </p>

    <pre class="codigo-java"><code>static void mostrarMensagem() {

    System.out.println("Olá!");

}</code></pre>

    <p>

        Isso permite chamar o método diretamente
        dentro do main.

    </p>

    <pre class="codigo-java"><code>public static void main(String[] args) {

    mostrarMensagem();

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Por enquanto:

        </strong>

        <p>

            Utilize static nos métodos simples
            criados nesta etapa.

        </p>

        <p>

            O significado completo será aprofundado
            quando estudarmos classes e objetos.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MAIN
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O método main

    </h2>

    <p>

        O método main é o ponto inicial
        da execução do programa.

    </p>

    <pre class="codigo-java"><code>public static void main(String[] args) {

}</code></pre>

    <p>

        Quando executamos a aplicação,
        o Java começa pelas instruções
        existentes dentro dele.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void iniciarPrograma() {

        System.out.println(
            "Programa iniciado."
        );

    }


    public static void main(String[] args) {

        iniciarPrograma();

    }

}</code></pre>

    <p>

        O Java começa pelo main e encontra
        a chamada iniciarPrograma().

    </p>

    <p>

        Depois, executa as instruções daquele método.

    </p>

    <div class="resultado-codigo"><code>Programa iniciado.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| FLUXO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Fluxo de uma chamada

    </h2>

    <div class="aula-destaque">

        <p>O programa começa no main.</p>

        <p>↓</p>

        <p>Encontra a chamada de um método.</p>

        <p>↓</p>

        <p>Vai até o método chamado.</p>

        <p>↓</p>

        <p>Executa as instruções do método.</p>

        <p>↓</p>

        <p>
            Retorna para o ponto seguinte
            à chamada.
        </p>

    </div>

    <pre class="codigo-java"><code>static void mensagem() {

    System.out.println("Dentro do método");

}


public static void main(String[] args) {

    System.out.println("Antes");

    mensagem();

    System.out.println("Depois");

}</code></pre>

    <div class="resultado-codigo"><code>Antes
Dentro do método
Depois</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIAS CHAMADAS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Chamando um método várias vezes

    </h2>

    <p>

        Uma grande vantagem dos métodos
        é a reutilização.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void mostrarMensagem() {

        System.out.println(
            "Estudando Java!"
        );

    }


    public static void main(String[] args) {

        mostrarMensagem();
        mostrarMensagem();
        mostrarMensagem();

    }

}</code></pre>

    <p>

        Como o método foi chamado três vezes,
        sua tarefa será executada três vezes.

    </p>

    <div class="resultado-codigo"><code>Estudando Java!
Estudando Java!
Estudando Java!</code></div>

    <div class="aula-destaque">

        <strong>

            Vantagem:

        </strong>

        <p>

            Caso a mensagem precise mudar,
            alteramos apenas o conteúdo do método.

        </p>

        <p>

            Todas as chamadas utilizarão
            a nova versão.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIOS MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando vários métodos

    </h2>

    <p>

        Uma classe pode possuir vários métodos.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void mostrarBoasVindas() {

        System.out.println(
            "Bem-vindo ao programa!"
        );

    }


    static void mostrarInformacoes() {

        System.out.println(
            "Curso: Java"
        );

        System.out.println(
            "Nível: iniciante"
        );

    }


    static void encerrarPrograma() {

        System.out.println(
            "Programa encerrado."
        );

    }


    public static void main(String[] args) {

        mostrarBoasVindas();

        mostrarInformacoes();

        encerrarPrograma();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Bem-vindo ao programa!
Curso: Java
Nível: iniciante
Programa encerrado.</code></div>

    <p>

        Cada método possui uma responsabilidade
        diferente.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ORGANIZAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O main fica mais organizado

    </h2>

    <p>

        Quando utilizamos métodos, o main pode
        funcionar como um coordenador das tarefas.

    </p>

    <pre class="codigo-java"><code>public static void main(String[] args) {

    mostrarCabecalho();

    exibirMenu();

    mostrarMensagemFinal();

}</code></pre>

    <p>

        Apenas lendo as chamadas, conseguimos
        compreender o fluxo geral do programa.

    </p>

    <p>

        Os detalhes de cada tarefa ficam organizados
        dentro de seus respectivos métodos.

    </p>

    <div class="aula-destaque">

        <strong>

            Código organizado:

        </strong>

        <p>

            O main indica quais tarefas serão executadas.

        </p>

        <p>

            Cada método contém os detalhes
            de uma dessas tarefas.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ORDEM DOS MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A ordem das declarações

    </h2>

    <p>

        Um método pode ser declarado antes
        ou depois do main dentro da classe.

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        mostrarMensagem();

    }


    static void mostrarMensagem() {

        System.out.println(
            "Olá!"
        );

    }

}</code></pre>

    <p>

        O programa continua funcionando porque
        o Java analisa a estrutura da classe.

    </p>

    <p>

        Porém, é importante manter um padrão
        de organização no projeto.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| LOCAL DO MÉTODO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Onde um método deve ser criado?

    </h2>

    <p>

        Um método deve ficar dentro da classe,
        mas fora de outro método.

    </p>

    <p>

        Esta organização está correta:

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void mensagem() {

        System.out.println("Olá!");

    }


    public static void main(String[] args) {

        mensagem();

    }

}</code></pre>

    <p>

        Não podemos declarar um método dentro
        do método main.

    </p>

    <p>

        Este exemplo está incorreto:

    </p>

    <pre class="codigo-java"><code>public static void main(String[] args) {

    static void mensagem() {

        System.out.println("Olá!");

    }

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Regra:

        </strong>

        <p>

            Os métodos ficam dentro da classe
            e separados uns dos outros.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| NOMES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Escolhendo bons nomes

    </h2>

    <p>

        O nome do método deve indicar claramente
        qual tarefa ele realiza.

    </p>

    <p>

        Bons exemplos:

    </p>

    <pre class="codigo-java"><code>mostrarMensagem()
exibirMenu()
calcularMedia()
salvarUsuario()
validarSenha()
encerrarPrograma()</code></pre>

    <p>

        Nomes pouco explicativos:

    </p>

    <pre class="codigo-java"><code>fazer()
coisa()
metodo1()
teste()
executarAlgo()</code></pre>

    <div class="aula-destaque">

        <strong>

            Boa prática:

        </strong>

        <p>

            Normalmente, o nome começa com um verbo,
            pois o método representa uma ação.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CAMEL CASE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos e camelCase

    </h2>

    <p>

        Os nomes de métodos normalmente seguem
        o padrão camelCase.

    </p>

    <pre class="codigo-java"><code>mostrarMensagem()
calcularMedia()
exibirInformacoes()
iniciarPrograma()
salvarDados()</code></pre>

    <p>

        A primeira palavra começa com letra minúscula.

    </p>

    <p>

        Cada nova palavra começa com letra maiúscula,
        sem espaços entre elas.

    </p>

    <div class="aula-destaque">

        <strong>

            Atenção às letras:

        </strong>

        <p>

            Java diferencia letras maiúsculas
            de minúsculas.

        </p>

        <p>

            mostrarMensagem() e MostrarMensagem()
            representam nomes diferentes.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| RESPONSABILIDADE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Um método deve possuir uma tarefa clara

    </h2>

    <p>

        Uma boa prática é criar métodos
        com responsabilidades bem definidas.

    </p>

    <pre class="codigo-java"><code>static void mostrarDadosAluno() {

    System.out.println(
        "Nome: Robert"
    );

    System.out.println(
        "Idade: 17"
    );

    System.out.println(
        "Curso: Informática"
    );

}</code></pre>

    <p>

        O método mostrarDadosAluno possui
        uma função clara: exibir os dados
        de um estudante.

    </p>

    <p>

        Evite colocar muitas tarefas sem relação
        dentro do mesmo método.

    </p>

    <div class="aula-destaque">

        <strong>

            Pense assim:

        </strong>

        <p>

            Um método deve fazer uma tarefa
            e fazê-la de maneira clara.

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

        Exemplo prático completo

    </h2>

    <pre class="codigo-java"><code>public class Main {

    static void mostrarCabecalho() {

        System.out.println(
            "================================"
        );

        System.out.println(
            "      JAVA, CAFÉ E CÓDIGO"
        );

        System.out.println(
            "================================"
        );

    }


    static void mostrarCurso() {

        System.out.println(
            "Curso: Programação em Java"
        );

        System.out.println(
            "Nível: iniciante"
        );

    }


    static void mostrarMensagemFinal() {

        System.out.println(
            "Bons estudos!"
        );

    }


    public static void main(String[] args) {

        mostrarCabecalho();

        mostrarCurso();

        mostrarMensagemFinal();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>================================
      JAVA, CAFÉ E CÓDIGO
================================
Curso: Programação em Java
Nível: iniciante
Bons estudos!</code></div>

    <p>

        O main ficou pequeno e fácil
        de compreender.

    </p>

    <p>

        Cada parte do programa foi separada
        em um método específico.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| VANTAGENS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Por que utilizar métodos?

    </h2>

    <ul>

        <li>

            <strong>Organização:</strong>
            dividem programas em partes menores;

        </li>

        <li>

            <strong>Reutilização:</strong>
            permitem executar a mesma tarefa várias vezes;

        </li>

        <li>

            <strong>Leitura:</strong>
            deixam o código mais fácil de compreender;

        </li>

        <li>

            <strong>Manutenção:</strong>
            facilitam alterações em tarefas específicas;

        </li>

        <li>

            <strong>Responsabilidade:</strong>
            ajudam a separar diferentes funções;

        </li>

        <li>

            <strong>Testes:</strong>
            permitem verificar partes menores do programa.

        </li>

    </ul>

    <p>

        Conforme os programas crescem,
        essas vantagens se tornam ainda
        mais importantes.

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

        Criar o método, mas não chamá-lo

    </h3>

    <pre class="codigo-java"><code>static void mensagem() {

    System.out.println("Olá!");

}</code></pre>

    <p>

        Apenas declarar o método não executa
        suas instruções.

    </p>

    <p>

        Precisamos chamá-lo:

    </p>

    <pre class="codigo-java"><code>mensagem();</code></pre>

    <h3>

        Esquecer os parênteses na chamada

    </h3>

    <p>

        Esta chamada está incorreta:

    </p>

    <pre class="codigo-java"><code>mensagem;</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>mensagem();</code></pre>

    <h3>

        Esquecer o ponto e vírgula na chamada

    </h3>

    <p>

        Esta chamada está incorreta:

    </p>

    <pre class="codigo-java"><code>mensagem()</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>mensagem();</code></pre>

    <h3>

        Colocar ponto e vírgula na declaração

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>static void mensagem(); {

    System.out.println("Olá!");

}</code></pre>

    <p>

        Não coloque ponto e vírgula depois
        dos parênteses da declaração.

    </p>

    <pre class="codigo-java"><code>static void mensagem() {

    System.out.println("Olá!");

}</code></pre>

    <h3>

        Criar um método dentro do main

    </h3>

    <p>

        Métodos devem ser declarados separadamente,
        dentro da classe.

    </p>

    <h3>

        Escrever o nome diferente

    </h3>

    <pre class="codigo-java"><code>static void mostrarMensagem() {

    System.out.println("Olá!");

}


public static void main(String[] args) {

    mostrarmensagem();

}</code></pre>

    <p>

        mostrarMensagem e mostrarmensagem
        não possuem a mesma escrita.

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

        Vamos criar três métodos simples.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static void mostrarTitulo() {

        System.out.println(
            "=== MEU PROGRAMA ==="
        );

    }


    static void mostrarObjetivo() {

        System.out.println(
            "Objetivo: aprender métodos."
        );

    }


    static void mostrarEncerramento() {

        System.out.println(
            "Fim da execução."
        );

    }


    public static void main(String[] args) {

        mostrarTitulo();

        mostrarObjetivo();

        mostrarEncerramento();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>=== MEU PROGRAMA ===
Objetivo: aprender métodos.
Fim da execução.</code></div>

    <div class="aula-destaque">

        <strong>

            Experimente:

        </strong>

        <p>

            Crie um quarto método chamado
            mostrarAutor() e utilize-o no main.

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

        Crie um programa organizado em métodos
        para representar um pequeno perfil.

    </p>

    <p>

        O programa deverá possuir os métodos:

    </p>

    <ul>

        <li>
            mostrarCabecalho();
        </li>

        <li>
            mostrarDadosPessoais();
        </li>

        <li>
            mostrarCurso();
        </li>

        <li>
            mostrarMensagemFinal().
        </li>

    </ul>

    <p>

        O main deverá apenas chamar esses métodos.

    </p>

    <div class="resultado-codigo"><code>========================
       MEU PERFIL
========================
Nome: Robert
Idade: 17
Curso: Informática
Mensagem: continue estudando Java!</code></div>

    <div class="aula-destaque">

        <strong>

            Regra do desafio:

        </strong>

        <p>

            Não coloque os comandos de saída
            diretamente no main.

        </p>

        <p>

            O main deverá coordenar as chamadas
            dos métodos.

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

        Nesta aula, aprendemos como criar
        métodos simples em Java.

    </p>

    <ul>

        <li>

            métodos são blocos responsáveis
            por tarefas específicas;

        </li>

        <li>

            declarar um método define
            o que ele realizará;

        </li>

        <li>

            chamar um método solicita
            sua execução;

        </li>

        <li>

            um método pode ser chamado
            várias vezes;

        </li>

        <li>

            void indica que o método
            não retorna um valor;

        </li>

        <li>

            nesta etapa, utilizamos static
            para chamar os métodos no main;

        </li>

        <li>

            main é o ponto inicial
            da execução do programa;

        </li>

        <li>

            métodos ficam dentro da classe
            e fora de outros métodos;

        </li>

        <li>

            nomes devem ser claros
            e seguir o padrão camelCase;

        </li>

        <li>

            métodos tornam o código mais organizado,
            reutilizável e fácil de manter.

        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Métodos dividem o programa
                em tarefas menores e organizadas.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como enviar
        informações para um método utilizando
        parâmetros e argumentos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Programas grandes se tornam mais simples
        quando são divididos em pequenas tarefas."

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

        Próxima aula: Parâmetros →

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