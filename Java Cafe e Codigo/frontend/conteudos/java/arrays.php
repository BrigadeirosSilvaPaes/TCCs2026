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
                "do_while"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "percorrendo_arrays"
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
    content="Aprenda como criar e utilizar arrays para armazenar vários valores relacionados em Java."
>

<title>
    Arrays em Java | Java, Café e Código
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
| CABEÇALHO DA AULA
|--------------------------------------------------------------------------
-->

<section class="aula-cabecalho">

    <span class="aula-numero">

        AULA 18

    </span>


    <h1>

        Arrays em Java

    </h1>


    <p>

        Aprenda como armazenar vários valores relacionados
        dentro de uma única estrutura e organizar melhor
        as informações dos seus programas.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE SÃO ARRAYS?
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que são Arrays?

    </h2>


    <p>

        Durante as aulas anteriores, aprendemos a utilizar
        variáveis para armazenar informações.

    </p>


    <p>

        Por exemplo, podemos criar uma variável para guardar
        o nome de uma pessoa:

    </p>


    <pre class="codigo-java"><code>String nome = "Robert";</code></pre>


    <p>

        Também podemos armazenar uma idade:

    </p>


    <pre class="codigo-java"><code>int idade = 17;</code></pre>


    <p>

        Porém, imagine que agora precisamos armazenar
        o nome de <strong>100 pessoas</strong>.

    </p>


    <p>

        Criar uma variável diferente para cada pessoa
        seria trabalhoso e deixaria o código muito grande:

    </p>


    <pre class="codigo-java"><code>String nome1 = "Ana";
String nome2 = "Carlos";
String nome3 = "Maria";
String nome4 = "João";
String nome5 = "Pedro";</code></pre>


    <p>

        É justamente nesse tipo de situação que podemos
        utilizar um <strong>array</strong>.

    </p>


    <div class="aula-destaque">

        <strong>

            O que é um Array?

        </strong>

        <p>

            Um array é uma estrutura que permite armazenar
            vários valores do mesmo tipo dentro de uma única
            variável.

        </p>

    </div>


    <p>

        Dessa forma, podemos guardar vários nomes,
        números ou outros valores relacionados utilizando
        uma única estrutura.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CRIANDO UM ARRAY
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como criar um Array?

    </h2>


    <p>

        Para criar um array em Java, precisamos informar
        o tipo de dado que será armazenado e utilizar
        os colchetes <strong>[]</strong>.

    </p>


    <p>

        Veja um exemplo:

    </p>


    <pre class="codigo-java"><code>String[] nomes;</code></pre>


    <p>

        Nesse exemplo, estamos declarando uma variável
        chamada <strong>nomes</strong> que será capaz de
        armazenar vários valores do tipo
        <strong>String</strong>.

    </p>


    <p>

        Também podemos criar o array já definindo
        a quantidade de posições:

    </p>


    <pre class="codigo-java"><code>String[] nomes = new String[5];</code></pre>


    <p>

        Nesse caso, criamos um array chamado
        <strong>nomes</strong> com espaço para
        <strong>5 valores</strong>.

    </p>


    <div class="aula-destaque">

        <strong>

            Importante

        </strong>

        <p>

            O tamanho de um array é definido no momento
            em que ele é criado e não pode ser alterado
            posteriormente.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| INICIALIZANDO UM ARRAY
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Inicializando um Array

    </h2>


    <p>

        Também podemos criar um array já preenchido
        com vários valores.

    </p>


    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria",
    "João",
    "Pedro"
};</code></pre>


    <p>

        Agora temos um array chamado
        <strong>nomes</strong> que armazena cinco nomes.

    </p>


    <p>

        Também podemos escrever os valores em uma
        única linha:

    </p>


    <pre class="codigo-java"><code>String[] nomes = {"Ana", "Carlos", "Maria", "João", "Pedro"};</code></pre>


    <p>

        As duas formas funcionam da mesma maneira.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| POSIÇÕES DO ARRAY
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        As posições de um Array

    </h2>


    <p>

        Cada valor armazenado dentro de um array ocupa
        uma posição.

    </p>


    <p>

        Em Java, a primeira posição de um array começa
        sempre pelo número <strong>0</strong>.

    </p>


    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria",
    "João",
    "Pedro"
};</code></pre>


    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>

                Índice

            </span>

            <span>

                Valor

            </span>

            <span>

                Posição

            </span>

        </div>


        <div class="aula-tabela-linha">

            <span>0</span>
            <span>Ana</span>
            <span>1ª posição</span>

        </div>


        <div class="aula-tabela-linha">

            <span>1</span>
            <span>Carlos</span>
            <span>2ª posição</span>

        </div>


        <div class="aula-tabela-linha">

            <span>2</span>
            <span>Maria</span>
            <span>3ª posição</span>

        </div>


        <div class="aula-tabela-linha">

            <span>3</span>
            <span>João</span>
            <span>4ª posição</span>

        </div>


        <div class="aula-tabela-linha">

            <span>4</span>
            <span>Pedro</span>
            <span>5ª posição</span>

        </div>

    </div>


    <div class="aula-destaque">

        <strong>

            Atenção ao índice

        </strong>

        <p>

            Embora o array possua cinco valores, seus índices
            vão de <strong>0 até 4</strong>.

            Isso acontece porque a contagem das posições
            começa pelo zero.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ACESSANDO VALORES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Acessando valores de um Array

    </h2>


    <p>

        Para acessar um valor específico dentro de um array,
        utilizamos o índice entre colchetes.

    </p>


    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria"
};

System.out.println(nomes[0]);</code></pre>


    <p>

        O resultado será:

    </p>


    <pre class="resultado-codigo"><code>Ana</code></pre>


    <p>

        Isso acontece porque o índice
        <strong>0</strong> representa o primeiro elemento
        do array.

    </p>


    <p>

        Podemos acessar outros valores utilizando seus
        respectivos índices.

    </p>


    <pre class="codigo-java"><code>System.out.println(nomes[1]);
System.out.println(nomes[2]);</code></pre>


    <p>

        O resultado será:

    </p>


    <pre class="resultado-codigo"><code>Carlos
Maria</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| ALTERANDO VALORES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Alterando valores de um Array

    </h2>


    <p>

        Assim como podemos acessar um valor utilizando
        seu índice, também podemos alterar o conteúdo
        de uma determinada posição.

    </p>


    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria"
};

nomes[1] = "Pedro";

System.out.println(nomes[1]);</code></pre>


    <p>

        O resultado será:

    </p>


    <pre class="resultado-codigo"><code>Pedro</code></pre>


    <p>

        Inicialmente, a posição <strong>1</strong> armazenava
        o valor <strong>"Carlos"</strong>.

    </p>


    <p>

        Depois da atribuição:

    </p>


    <pre class="codigo-java"><code>nomes[1] = "Pedro";</code></pre>


    <p>

        O valor armazenado nessa posição passou a ser
        <strong>"Pedro"</strong>.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ARRAY DE NÚMEROS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Arrays com números

    </h2>


    <p>

        Arrays também podem armazenar números.

    </p>


    <p>

        Por exemplo, podemos criar um array de inteiros:

    </p>


    <pre class="codigo-java"><code>int[] numeros = {
    10,
    20,
    30,
    40,
    50
};</code></pre>


    <p>

        Podemos acessar um valor específico:

    </p>


    <pre class="codigo-java"><code>System.out.println(numeros[2]);</code></pre>


    <p>

        O resultado será:

    </p>


    <pre class="resultado-codigo"><code>30</code></pre>


    <p>

        Isso acontece porque o número
        <strong>30</strong> está armazenado no índice
        <strong>2</strong>.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| TAMANHO DO ARRAY
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Descobrindo o tamanho de um Array

    </h2>


    <p>

        Podemos descobrir quantos elementos existem
        dentro de um array utilizando a propriedade
        <strong>length</strong>.

    </p>


    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria",
    "João"
};

System.out.println(nomes.length);</code></pre>


    <p>

        O resultado será:

    </p>


    <pre class="resultado-codigo"><code>4</code></pre>


    <p>

        O <strong>length</strong> informa a quantidade total
        de elementos existentes no array.

    </p>


    <div class="aula-destaque">

        <strong>

            Atenção

        </strong>

        <p>

            O tamanho do array é 4, mas os índices vão de
            <strong>0 até 3</strong>.

            Portanto, o último índice sempre será o tamanho
            do array menos 1.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| DIFERENTES TIPOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Arrays de diferentes tipos

    </h2>


    <p>

        Um array pode armazenar diferentes tipos de dados,
        desde que todos os elementos sejam do mesmo tipo.

    </p>


    <p>

        Podemos criar um array de inteiros:

    </p>


    <pre class="codigo-java"><code>int[] idades = {17, 18, 20, 25};</code></pre>


    <p>

        Um array de números decimais:

    </p>


    <pre class="codigo-java"><code>double[] precos = {10.50, 20.75, 5.99};</code></pre>


    <p>

        Um array de textos:

    </p>


    <pre class="codigo-java"><code>String[] cidades = {
    "Rio Grande",
    "Pelotas",
    "Porto Alegre"
};</code></pre>


    <p>

        Também podemos criar arrays de valores
        <strong>booleanos</strong>:

    </p>


    <pre class="codigo-java"><code>boolean[] respostas = {
    true,
    false,
    true
};</code></pre>


    <div class="aula-destaque">

        <strong>

            Regra importante

        </strong>

        <p>

            Um array possui um tipo definido.

            Por isso, um array declarado como
            <strong>int[]</strong> deve armazenar números
            inteiros, enquanto um array
            <strong>String[]</strong> deve armazenar textos.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO PRÁTICO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático

    </h2>


    <p>

        Vamos criar um pequeno programa que armazena
        as notas de um estudante.

    </p>


    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        double[] notas = {
            8.5,
            7.0,
            9.0,
            10.0
        };

        System.out.println("Primeira nota: " + notas[0]);
        System.out.println("Segunda nota: " + notas[1]);
        System.out.println("Terceira nota: " + notas[2]);
        System.out.println("Quarta nota: " + notas[3]);

    }

}</code></pre>


    <p>

        O resultado será:

    </p>


    <pre class="resultado-codigo"><code>Primeira nota: 8.5
Segunda nota: 7.0
Terceira nota: 9.0
Quarta nota: 10.0</code></pre>


    <p>

        Nesse exemplo, todas as notas foram armazenadas
        dentro de um único array chamado
        <strong>notas</strong>.

    </p>


    <p>

        Cada nota pode ser acessada individualmente
        utilizando seu respectivo índice.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ERRO DE ÍNDICE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Cuidado com o índice

    </h2>


    <p>

        Um erro muito comum ao trabalhar com arrays
        acontece quando tentamos acessar uma posição
        que não existe.

    </p>


    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria"
};

System.out.println(nomes[3]);</code></pre>


    <p>

        Esse código causará um erro porque o array possui
        apenas três elementos.

    </p>


    <p>

        Os índices disponíveis são:

    </p>


    <pre class="resultado-codigo"><code>0 → Ana
1 → Carlos
2 → Maria</code></pre>


    <p>

        Portanto, o índice <strong>3</strong> não existe.

    </p>


    <div class="aula-destaque">

        <strong>

            Lembre-se

        </strong>

        <p>

            Se um array possui <strong>5 elementos</strong>,
            seus índices vão de <strong>0 até 4</strong>.

            Sempre tenha atenção ao acessar posições
            de um array.

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

        O que aprendemos?

    </h2>


    <p>

        Nesta aula, aprendemos como utilizar arrays
        para armazenar vários valores relacionados
        dentro de uma única estrutura.

    </p>


    <ul>

        <li>
            Arrays armazenam vários valores;
        </li>


        <li>

            Os valores armazenados precisam ser
            compatíveis com o tipo definido;

        </li>


        <li>

            A primeira posição de um array possui
            o índice <strong>0</strong>;

        </li>


        <li>

            Podemos acessar valores utilizando
            seus índices;

        </li>


        <li>

            Podemos alterar valores de posições
            específicas;

        </li>


        <li>

            A propriedade <strong>length</strong>
            informa o tamanho do array;

        </li>


        <li>

            O tamanho de um array é definido
            quando ele é criado;

        </li>


        <li>

            É importante nunca acessar um índice
            que não existe.

        </li>

    </ul>


    <p>

        Agora você já sabe como criar arrays,
        armazenar informações e acessar valores
        utilizando seus índices.

    </p>


    <p>

        Na próxima aula, vamos aprender como
        <strong>percorrer arrays</strong> automaticamente,
        utilizando estruturas de repetição para acessar
        cada elemento armazenado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Organizar vários dados em uma estrutura é
        um dos primeiros passos para criar programas
        maiores e mais inteligentes."

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

        Próxima aula: Percorrendo Arrays →

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