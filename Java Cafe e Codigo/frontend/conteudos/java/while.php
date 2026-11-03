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
                "for"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "do_while"
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
    content="Aprenda como utilizar a estrutura while para repetir comandos enquanto uma condição for verdadeira."
>

<title>
    Estrutura While em Java | Java, Café e Código
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

        AULA 16

    </span>

    <h1>

        Estrutura while em Java

    </h1>

    <p>

        Aprenda como repetir comandos enquanto
        uma determinada condição continuar verdadeira.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Repetindo enquanto uma condição for verdadeira

    </h2>

    <p>

        Na aula anterior, aprendemos a utilizar for
        para controlar repetições com um contador.

    </p>

    <p>

        Porém, nem sempre sabemos exatamente quantas
        vezes uma ação deverá acontecer.

    </p>

    <p>

        Imagine um programa que solicita uma senha
        até que o usuário digite o valor correto.

    </p>

    <p>

        Não sabemos quantas tentativas serão necessárias.

    </p>

    <p>

        Nesse tipo de situação, podemos utilizar
        a estrutura <strong>while</strong>.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            While significa “enquanto”.

        </p>

        <p>

            Enquanto uma condição for verdadeira,
            o bloco continuará sendo executado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ESTRUTURA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Estrutura básica do while

    </h2>

    <pre class="codigo-java"><code>while (condicao) {

    // Código que será repetido

}</code></pre>

    <p>

        Antes de executar o bloco, o Java verifica
        a condição entre parênteses.

    </p>

    <p>

        Se o resultado for true, o bloco será executado.

    </p>

    <p>

        Depois disso, a condição será verificada novamente.

    </p>

    <p>

        Quando a condição resultar em false,
        a repetição terminará.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PRIMEIRO EXEMPLO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Contando de 1 até 5

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        int numero = 1;

        while (numero &lt;= 5) {

            System.out.println(numero);

            numero++;

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>1
2
3
4
5</code></div>

    <p>

        A variável numero começa com o valor 1.

    </p>

    <p>

        Enquanto numero for menor ou igual a 5,
        o bloco será executado.

    </p>

    <p>

        Dentro do laço, numero++ aumenta o valor
        da variável em uma unidade.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FLUXO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como o while funciona?

    </h2>

    <div class="aula-destaque">

        <p>

            A variável numero começa com 1.

        </p>

        <p>

            ↓

        </p>

        <p>

            A condição numero &lt;= 5 é verificada.

        </p>

        <p>

            ↓

        </p>

        <p>

            Se for verdadeira, o bloco é executado.

        </p>

        <p>

            ↓

        </p>

        <p>

            O valor de numero é alterado.

        </p>

        <p>

            ↓

        </p>

        <p>

            A condição é verificada novamente.

        </p>

    </div>

    <p>

        Quando numero chegar a 6, a condição será falsa
        e o laço terminará.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CONDIÇÃO FALSA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A condição é verificada antes

    </h2>

    <p>

        O while verifica a condição antes
        de executar o bloco.

    </p>

    <pre class="codigo-java"><code>int numero = 10;

while (numero &lt; 5) {

    System.out.println(numero);

}</code></pre>

    <p>

        Como 10 não é menor que 5, a condição
        já começa falsa.

    </p>

    <p>

        Portanto, nenhuma mensagem será exibida.

    </p>

    <div class="resultado-codigo"><code>O bloco não será executado.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| CONTADOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando while como contador

    </h2>

    <pre class="codigo-java"><code>int contador = 1;

while (contador &lt;= 4) {

    System.out.println(
        "Repetição: " + contador
    );

    contador++;

}</code></pre>

    <div class="resultado-codigo"><code>Repetição: 1
Repetição: 2
Repetição: 3
Repetição: 4</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| CONTAGEM REGRESSIVA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Contagem regressiva

    </h2>

    <pre class="codigo-java"><code>int numero = 5;

while (numero &gt;= 1) {

    System.out.println(numero);

    numero--;

}

System.out.println("Começou!");</code></pre>

    <div class="resultado-codigo"><code>5
4
3
2
1
Começou!</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| LOOP INFINITO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Cuidado com o loop infinito

    </h2>

    <p>

        A condição do while precisa conseguir
        se tornar falsa.

    </p>

    <p>

        Este exemplo possui um problema:

    </p>

    <pre class="codigo-java"><code>int numero = 1;

while (numero &lt;= 5) {

    System.out.println(numero);

}</code></pre>

    <p>

        O valor de numero nunca é alterado.

    </p>

    <p>

        Portanto, numero continuará valendo 1
        e a condição continuará verdadeira.

    </p>

    <div class="aula-destaque">

        <strong>

            Loop infinito:

        </strong>

        <p>

            É uma repetição que não possui
            uma condição de encerramento alcançável.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CORREÇÃO LOOP
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Corrigindo a repetição

    </h2>

    <p>

        Para que o laço termine, precisamos modificar
        alguma informação utilizada na condição.

    </p>

    <pre class="codigo-java"><code>int numero = 1;

while (numero &lt;= 5) {

    System.out.println(numero);

    numero++;

}</code></pre>

    <p>

        Agora numero aumenta a cada repetição.

    </p>

    <p>

        Quando chegar a 6, a condição se tornará falsa.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ACUMULADOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Somando valores

    </h2>

    <pre class="codigo-java"><code>int numero = 1;
int soma = 0;

while (numero &lt;= 5) {

    soma += numero;

    numero++;

}

System.out.println(
    "Resultado: " + soma
);</code></pre>

    <p>

        O programa realizará:

    </p>

    <pre class="codigo-java"><code>1 + 2 + 3 + 4 + 5</code></pre>

    <div class="resultado-codigo"><code>Resultado: 15</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| NÚMEROS PARES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Mostrando números pares

    </h2>

    <pre class="codigo-java"><code>int numero = 2;

while (numero &lt;= 10) {

    System.out.println(numero);

    numero += 2;

}</code></pre>

    <div class="resultado-codigo"><code>2
4
6
8
10</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| IF X WHILE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Diferença entre if e while

    </h2>

    <p>

        If verifica uma condição e executa
        seu bloco uma vez.

    </p>

    <pre class="codigo-java"><code>if (numero &lt;= 5) {

    System.out.println(numero);

}</code></pre>

    <p>

        While continua executando enquanto
        a condição for verdadeira.

    </p>

    <pre class="codigo-java"><code>while (numero &lt;= 5) {

    System.out.println(numero);

    numero++;

}</code></pre>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Estrutura</span>
            <span>Comportamento</span>
            <span>Repete?</span>

        </div>

        <div class="aula-tabela-linha">

            <span>if</span>

            <span>
                Executa quando a condição é verdadeira
            </span>

            <span>Não</span>

        </div>

        <div class="aula-tabela-linha">

            <span>while</span>

            <span>
                Executa enquanto a condição é verdadeira
            </span>

            <span>Sim</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| FOR X WHILE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        For ou while?

    </h2>

    <p>

        As duas estruturas podem produzir resultados
        parecidos.

    </p>

    <pre class="codigo-java"><code>for (int i = 1; i &lt;= 5; i++) {

    System.out.println(i);

}</code></pre>

    <pre class="codigo-java"><code>int i = 1;

while (i &lt;= 5) {

    System.out.println(i);

    i++;

}</code></pre>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Estrutura</span>
            <span>Melhor utilização</span>
            <span>Controle</span>

        </div>

        <div class="aula-tabela-linha">

            <span>for</span>

            <span>
                Quantidade de repetições conhecida
            </span>

            <span>Centralizado</span>

        </div>

        <div class="aula-tabela-linha">

            <span>while</span>

            <span>
                Repetição depende de uma condição
            </span>

            <span>Distribuído</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| TENTATIVAS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo com tentativas

    </h2>

    <pre class="codigo-java"><code>int tentativa = 1;

while (tentativa &lt;= 3) {

    System.out.println(
        "Tentativa: " + tentativa
    );

    tentativa++;

}

System.out.println(
    "Limite de tentativas atingido."
);</code></pre>

    <div class="resultado-codigo"><code>Tentativa: 1
Tentativa: 2
Tentativa: 3
Limite de tentativas atingido.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        While com Scanner

    </h2>

    <p>

        Podemos repetir uma solicitação até que
        o usuário informe um valor válido.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        int numero = 0;

        while (numero &lt;= 0) {

            System.out.println(
                "Digite um número positivo:"
            );

            numero = entrada.nextInt();

        }

        System.out.println(
            "Número válido: " + numero
        );

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite um número positivo:
-5
Digite um número positivo:
0
Digite um número positivo:
8
Número válido: 8</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| SENHA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Solicitando uma senha

    </h2>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        String senha = "";

        while (!senha.equals("1234")) {

            System.out.println(
                "Digite a senha:"
            );

            senha = entrada.nextLine();

        }

        System.out.println(
            "Acesso permitido."
        );

        entrada.close();

    }

}</code></pre>

    <div class="resultado-codigo"><code>Digite a senha:
1111
Digite a senha:
4321
Digite a senha:
1234
Acesso permitido.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO PRÁTICO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: controle de estoque

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        int estoque = 5;

        while (estoque &gt; 0) {

            System.out.println(
                "Produto vendido."
            );

            estoque--;

            System.out.println(
                "Estoque restante: " + estoque
            );

        }

        System.out.println(
            "Estoque esgotado."
        );

    }

}</code></pre>

    <div class="resultado-codigo"><code>Produto vendido.
Estoque restante: 4
Produto vendido.
Estoque restante: 3
Produto vendido.
Estoque restante: 2
Produto vendido.
Estoque restante: 1
Produto vendido.
Estoque restante: 0
Estoque esgotado.</code></div>

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

        Esquecer de atualizar a condição

    </h3>

    <p>

        Verifique se alguma informação utilizada
        na condição será modificada.

    </p>

    <h3>

        Utilizar uma condição impossível

    </h3>

    <pre class="codigo-java"><code>int numero = 10;

while (numero &lt; 5) {

    System.out.println(numero);

}</code></pre>

    <p>

        O bloco não será executado porque
        a condição começa falsa.

    </p>

    <h3>

        Colocar ponto e vírgula depois do while

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>while (numero &lt;= 5); {

    System.out.println(numero);

    numero++;

}</code></pre>

    <p>

        Não coloque ponto e vírgula depois
        da condição do while.

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

        Vamos calcular o fatorial de 5.

    </p>

    <pre class="codigo-java"><code>int numero = 5;
int fatorial = 1;

while (numero &gt;= 1) {

    fatorial *= numero;

    numero--;

}

System.out.println(
    "Fatorial: " + fatorial
);</code></pre>

    <div class="resultado-codigo"><code>Fatorial: 120</code></div>

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

        Crie um programa que solicite uma nota
        entre 0 e 10.

    </p>

    <p>

        Enquanto o valor estiver fora desse intervalo,
        solicite uma nova nota.

    </p>

    <div class="resultado-codigo"><code>Digite uma nota:
15
Nota inválida.

Digite uma nota:
-2
Nota inválida.

Digite uma nota:
8.5
Nota válida: 8.5</code></div>

    <div class="aula-destaque">

        <strong>

            Condição sugerida:

        </strong>

        <p>

            nota &lt; 0 <strong>||</strong> nota &gt; 10

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

        Nesta aula, aprendemos como utilizar while.

    </p>

    <ul>

        <li>
            while significa enquanto;
        </li>

        <li>
            a condição é verificada antes do bloco;
        </li>

        <li>
            o bloco é repetido enquanto
            a condição for verdadeira;
        </li>

        <li>
            se a condição começar falsa,
            o bloco não será executado;
        </li>

        <li>
            alguma informação precisa permitir
            que a condição se torne falsa;
        </li>

        <li>
            loops infinitos acontecem quando
            a repetição nunca termina;
        </li>

        <li>
            while pode ser utilizado com contadores;
        </li>

        <li>
            pode realizar cálculos repetitivos;
        </li>

        <li>
            é útil para validações e tentativas;
        </li>

        <li>
            pode ser combinado com Scanner.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                While repete um bloco enquanto
                uma condição permanecer verdadeira.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, conheceremos do while,
        que executa o bloco antes de verificar
        a condição.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Uma repetição precisa saber quando continuar
        e, principalmente, quando parar."

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

        Próxima aula: Do While →

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