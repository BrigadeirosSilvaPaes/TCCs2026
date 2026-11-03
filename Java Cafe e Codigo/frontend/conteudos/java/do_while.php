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
                "while"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "arrays"
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
    content="Aprenda como utilizar a estrutura do while para executar um bloco antes de verificar a condição."
>

<title>
    Estrutura Do While | Java, Café e Código
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

        AULA 17

    </span>

    <h1>

        Estrutura do while

    </h1>

    <p>

        Aprenda como executar um bloco pelo menos
        uma vez antes de verificar se a repetição
        deverá continuar.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Uma repetição que começa pela execução

    </h2>

    <p>

        Na aula anterior, aprendemos que while verifica
        uma condição antes de executar o bloco.

    </p>

    <p>

        Por isso, quando a condição começa falsa,
        o bloco não é executado nenhuma vez.

    </p>

    <p>

        A estrutura <strong>do while</strong> funciona
        de forma diferente.

    </p>

    <p>

        Primeiro, o bloco é executado.

    </p>

    <p>

        Somente depois o Java verifica se a repetição
        deverá continuar.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            No do while, o bloco sempre será executado
            pelo menos uma vez.

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

        Estrutura básica do do while

    </h2>

    <pre class="codigo-java"><code>do {

    // Código que será executado

} while (condicao);</code></pre>

    <p>

        A estrutura começa com a palavra
        <strong>do</strong>.

    </p>

    <p>

        Depois do bloco, utilizamos while
        com a condição.

    </p>

    <div class="aula-destaque">

        <strong>

            Atenção:

        </strong>

        <p>

            No do while, existe um ponto e vírgula
            depois da condição.

        </p>

        <pre class="codigo-java"><code>} while (condicao);</code></pre>

    </div>

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

        do {

            System.out.println(numero);

            numero++;

        } while (numero &lt;= 5);

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

        Primeiro, o número é exibido.

    </p>

    <p>

        Depois, numero é aumentado.

    </p>

    <p>

        Por último, a condição numero &lt;= 5
        é verificada.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FLUXO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como o do while funciona?

    </h2>

    <div class="aula-destaque">

        <p>

            O programa entra no bloco do.

        </p>

        <p>

            ↓

        </p>

        <p>

            As instruções são executadas.

        </p>

        <p>

            ↓

        </p>

        <p>

            A condição do while é verificada.

        </p>

        <p>

            ↓

        </p>

        <p>

            Se for verdadeira, o programa
            volta para o bloco.

        </p>

        <p>

            ↓

        </p>

        <p>

            Se for falsa, a repetição termina.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXECUTA UMA VEZ
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Executando mesmo com condição falsa

    </h2>

    <pre class="codigo-java"><code>int numero = 10;

do {

    System.out.println(numero);

} while (numero &lt; 5);</code></pre>

    <p>

        O número 10 não é menor que 5.

    </p>

    <p>

        Mesmo assim, o bloco será executado uma vez,
        porque a verificação acontece depois.

    </p>

    <div class="resultado-codigo"><code>10</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| WHILE X DO WHILE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        While e do while

    </h2>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Estrutura</span>
            <span>Verificação</span>
            <span>Executa pelo menos uma vez?</span>

        </div>

        <div class="aula-tabela-linha">

            <span>while</span>
            <span>Antes do bloco</span>
            <span>Não</span>

        </div>

        <div class="aula-tabela-linha">

            <span>do while</span>
            <span>Depois do bloco</span>
            <span>Sim</span>

        </div>

    </div>

    <p>

        Exemplo com while:

    </p>

    <pre class="codigo-java"><code>int numero = 10;

while (numero &lt; 5) {

    System.out.println(numero);

}</code></pre>

    <div class="resultado-codigo"><code>Nenhuma mensagem será exibida.</code></div>

    <p>

        Exemplo com do while:

    </p>

    <pre class="codigo-java"><code>int numero = 10;

do {

    System.out.println(numero);

} while (numero &lt; 5);</code></pre>

    <div class="resultado-codigo"><code>10</code></div>

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

do {

    System.out.println(numero);

    numero--;

} while (numero &gt;= 1);

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

        Cuidado com repetições infinitas

    </h2>

    <p>

        Assim como no while, a condição precisa
        conseguir se tornar falsa.

    </p>

    <pre class="codigo-java"><code>int numero = 1;

do {

    System.out.println(numero);

} while (numero &lt;= 5);</code></pre>

    <p>

        O valor de numero nunca é alterado.

    </p>

    <p>

        Portanto, a condição continuará verdadeira
        e a repetição não terminará normalmente.

    </p>

    <p>

        O correto seria:

    </p>

    <pre class="codigo-java"><code>int numero = 1;

do {

    System.out.println(numero);

    numero++;

} while (numero &lt;= 5);</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| MENU
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando um menu

    </h2>

    <p>

        Do while é muito útil para menus,
        pois o menu precisa ser exibido
        pelo menos uma vez.

    </p>

    <pre class="codigo-java"><code>int opcao = 0;

do {

    System.out.println("=== MENU ===");
    System.out.println("1 - Iniciar");
    System.out.println("2 - Ajuda");
    System.out.println("3 - Sair");

    opcao = 3;

} while (opcao != 3);

System.out.println(
    "Programa encerrado."
);</code></pre>

    <p>

        O menu será exibido antes de a condição
        ser verificada.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MENU COM SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Menu interativo com Scanner

    </h2>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        int opcao;

        do {

            System.out.println();
            System.out.println("=== MENU ===");
            System.out.println("1 - Ver perfil");
            System.out.println("2 - Abrir conteúdos");
            System.out.println("3 - Sair");

            System.out.println();
            System.out.println(
                "Escolha uma opção:"
            );

            opcao = entrada.nextInt();

            switch (opcao) {

                case 1:

                    System.out.println(
                        "Abrindo perfil..."
                    );

                    break;

                case 2:

                    System.out.println(
                        "Abrindo conteúdos..."
                    );

                    break;

                case 3:

                    System.out.println(
                        "Encerrando..."
                    );

                    break;

                default:

                    System.out.println(
                        "Opção inválida."
                    );

            }

        } while (opcao != 3);

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>=== MENU ===
1 - Ver perfil
2 - Abrir conteúdos
3 - Sair

Escolha uma opção:
2
Abrindo conteúdos...

=== MENU ===
1 - Ver perfil
2 - Abrir conteúdos
3 - Sair

Escolha uma opção:
3
Encerrando...</code></div>

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

        String senha;

        do {

            System.out.println(
                "Digite a senha:"
            );

            senha = entrada.nextLine();

        } while (!senha.equals("1234"));

        System.out.println(
            "Senha correta!"
        );

        entrada.close();

    }

}</code></pre>

    <p>

        A senha será solicitada pelo menos uma vez.

    </p>

    <p>

        Enquanto o valor for diferente de "1234",
        o programa continuará solicitando.

    </p>

    <div class="resultado-codigo"><code>Digite a senha:
1111
Digite a senha:
abcd
Digite a senha:
1234
Senha correta!</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| VALIDAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Validando uma nota

    </h2>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        double nota;

        do {

            System.out.println(
                "Digite uma nota entre 0 e 10:"
            );

            nota = entrada.nextDouble();

            if (nota &lt; 0 || nota &gt; 10) {

                System.out.println(
                    "Nota inválida."
                );

            }

        } while (nota &lt; 0 || nota &gt; 10);

        System.out.println(
            "Nota válida: " + nota
        );

        entrada.close();

    }

}</code></pre>

    <div class="resultado-codigo"><code>Digite uma nota entre 0 e 10:
15
Nota inválida.

Digite uma nota entre 0 e 10:
8.5
Nota válida: 8.5</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| CONTINUAR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Perguntando se o usuário deseja continuar

    </h2>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        String resposta;

        do {

            System.out.println(
                "Executando uma tarefa..."
            );

            System.out.println(
                "Deseja continuar? S/N"
            );

            resposta =
                entrada.nextLine();

        } while (
            resposta.equalsIgnoreCase("S")
        );

        System.out.println(
            "Programa encerrado."
        );

        entrada.close();

    }

}</code></pre>

    <p>

        Enquanto o usuário responder S,
        a tarefa será executada novamente.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| QUANDO USAR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Quando utilizar do while?

    </h2>

    <p>

        Do while é indicado quando o bloco
        precisa acontecer antes da primeira
        verificação.

    </p>

    <ul>

        <li>
            exibir menus;
        </li>

        <li>
            solicitar uma senha;
        </li>

        <li>
            validar dados informados;
        </li>

        <li>
            perguntar se o usuário deseja continuar;
        </li>

        <li>
            executar uma tarefa inicial antes
            de verificar a condição.
        </li>

    </ul>

    <div class="aula-destaque">

        <strong>

            Regra prática:

        </strong>

        <p>

            Use while quando a condição precisar
            ser verificada antes da primeira execução.

        </p>

        <p>

            Use do while quando o bloco precisar
            ser executado pelo menos uma vez.

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

        Esquecer o ponto e vírgula final

    </h3>

    <p>

        Esta escrita está incorreta:

    </p>

    <pre class="codigo-java"><code>} while (condicao)</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>} while (condicao);</code></pre>

    <h3>

        Não alterar a condição

    </h3>

    <p>

        Verifique se alguma informação será modificada
        dentro da repetição.

    </p>

    <h3>

        Utilizar do while sem necessidade

    </h3>

    <p>

        Quando o bloco não pode ser executado
        antes da primeira validação, while pode
        ser mais adequado.

    </p>

    <h3>

        Esquecer que a primeira execução é garantida

    </h3>

    <p>

        Mesmo com uma condição falsa desde o início,
        o bloco do do será executado uma vez.

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

        Vamos solicitar um número maior que zero.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        int numero;

        do {

            System.out.println(
                "Digite um número positivo:"
            );

            numero = entrada.nextInt();

        } while (numero &lt;= 0);

        System.out.println(
            "Valor aceito: " + numero
        );

        entrada.close();

    }

}</code></pre>

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

        Crie uma calculadora com o seguinte menu:

    </p>

    <ul>

        <li>
            1 — Somar;
        </li>

        <li>
            2 — Subtrair;
        </li>

        <li>
            3 — Multiplicar;
        </li>

        <li>
            4 — Dividir;
        </li>

        <li>
            5 — Sair.
        </li>

    </ul>

    <p>

        Utilize:

    </p>

    <ul>

        <li>
            Scanner para receber os valores;
        </li>

        <li>
            do while para repetir o menu;
        </li>

        <li>
            switch para executar cada operação;
        </li>

        <li>
            métodos para organizar os cálculos.
        </li>

    </ul>

    <div class="resultado-codigo"><code>=== CALCULADORA ===
1 - Somar
2 - Subtrair
3 - Multiplicar
4 - Dividir
5 - Sair

Escolha uma opção:
1

Primeiro número:
10

Segundo número:
5

Resultado: 15</code></div>

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
        a estrutura do while.

    </p>

    <ul>

        <li>
            do while é uma estrutura de repetição;
        </li>

        <li>
            o bloco é executado antes
            da condição ser verificada;
        </li>

        <li>
            o bloco sempre será executado
            pelo menos uma vez;
        </li>

        <li>
            a condição aparece depois do bloco;
        </li>

        <li>
            existe um ponto e vírgula depois
            da condição;
        </li>

        <li>
            a repetição continua enquanto
            a condição for verdadeira;
        </li>

        <li>
            a condição precisa conseguir
            se tornar falsa;
        </li>

        <li>
            do while é útil para menus;
        </li>

        <li>
            pode ser utilizado para validações;
        </li>

        <li>
            pode ser combinado com Scanner
            e switch.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Do while executa primeiro
                e verifica a condição depois.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, iniciaremos o estudo
        de arrays para armazenar vários valores
        dentro de uma mesma estrutura.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Algumas tarefas precisam acontecer antes
        mesmo de sabermos se serão repetidas."

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

        Próxima aula: Arrays →

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