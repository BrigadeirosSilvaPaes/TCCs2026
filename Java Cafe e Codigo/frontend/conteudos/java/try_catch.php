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
                "excecoes"
        ]
    );


$linkCaderno =
    rota(
        "caderno"
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
    content="Aprenda como utilizar try, catch e finally em Java para capturar exceções, tratar falhas e manter o programa funcionando de forma controlada."
>

<title>
    Try e Catch em Java | Java, Café e Código
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

        AULA 29

    </span>

    <h1>

        Try e Catch em Java

    </h1>

    <p>

        Aprenda como capturar exceções, apresentar
        mensagens adequadas e evitar que situações
        inesperadas interrompam o programa sem controle.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| REVISÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Relembrando as exceções

    </h2>

    <p>

        Na aula anterior, aprendemos que uma exceção
        representa uma situação inesperada que acontece
        durante a execução de um programa.

    </p>

    <p>

        Um exemplo comum é a divisão inteira por zero:

    </p>

    <pre class="codigo-java"><code>int numero1 = 10;

int numero2 = 0;

int resultado =
    numero1 / numero2;

System.out.println(resultado);</code></pre>

    <p>

        Ao tentar realizar essa operação, o Java gera:

    </p>

    <pre class="resultado-codigo"><code>ArithmeticException: / by zero</code></pre>

    <p>

        Como a exceção não foi tratada, o fluxo normal
        do programa é interrompido.

    </p>

    <p>

        Para controlar esse tipo de situação, podemos
        utilizar os blocos:

    </p>

    <ul>

        <li>

            <strong>try;</strong>

        </li>

        <li>

            <strong>catch;</strong>

        </li>

        <li>

            <strong>finally.</strong>

        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| TRY
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é o bloco try?

    </h2>

    <p>

        A palavra <strong>try</strong> pode ser entendida
        como “tente”.

    </p>

    <p>

        Dentro desse bloco, colocamos as instruções
        que podem gerar uma exceção.

    </p>

    <pre class="codigo-java"><code>try {

    // Código que pode gerar uma exceção

}</code></pre>

    <p>

        O Java tentará executar normalmente as
        instruções presentes no bloco.

    </p>

    <div class="aula-destaque">

        <strong>

            O try não impede a exceção.

        </strong>

        <p>

            Ele apenas marca uma região que será
            monitorada para que uma possível exceção
            possa ser capturada.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CATCH
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é o bloco catch?

    </h2>

    <p>

        O bloco <strong>catch</strong> é responsável
        por capturar e tratar uma exceção compatível.

    </p>

    <pre class="codigo-java"><code>try {

    // Código que pode gerar uma exceção

} catch (TipoDaExcecao e) {

    // Tratamento da exceção

}</code></pre>

    <p>

        Caso uma exceção aconteça dentro do try,
        o Java procura um catch capaz de capturar
        aquele tipo de problema.

    </p>

    <p>

        O catch possui duas partes principais:

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>
                Parte
            </span>

            <span>
                Exemplo
            </span>

            <span>
                Função
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>
                Tipo da exceção
            </span>

            <span>
                ArithmeticException
            </span>

            <span>
                Define o problema capturado
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>
                Variável
            </span>

            <span>
                e
            </span>

            <span>
                Representa o objeto da exceção
            </span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PRIMEIRO EXEMPLO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Primeiro exemplo com try e catch

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        try {

            int numero1 = 10;

            int numero2 = 0;

            int resultado =
                numero1 / numero2;

            System.out.println(
                resultado
            );

        } catch (
            ArithmeticException e
        ) {

            System.out.println(
                "Não é possível dividir por zero."
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <pre class="resultado-codigo"><code>Não é possível dividir por zero.</code></pre>

    <p>

        A divisão gerou uma ArithmeticException.

    </p>

    <p>

        O catch capturou essa exceção e apresentou
        uma mensagem mais compreensível.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PROGRAMA CONTINUA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O programa pode continuar

    </h2>

    <p>

        Depois que uma exceção é tratada, o programa
        pode continuar executando as instruções que
        aparecem após o try e catch.

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        try {

            int resultado = 10 / 0;

            System.out.println(resultado);

        } catch (
            ArithmeticException e
        ) {

            System.out.println(
                "Divisão inválida."
            );

        }

        System.out.println(
            "O programa continuou."
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <pre class="resultado-codigo"><code>Divisão inválida.
O programa continuou.</code></pre>

    <div class="aula-destaque">

        <strong>

            Uma exceção tratada não precisa encerrar
            toda a aplicação.

        </strong>

        <p>

            O programa pode responder ao problema
            e seguir funcionando de maneira controlada.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| FLUXO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como funciona o fluxo?

    </h2>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>
                Situação
            </span>

            <span>
                Ação do Java
            </span>

            <span>
                Resultado
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>
                Nenhuma exceção
            </span>

            <span>
                Executa todo o try
            </span>

            <span>
                Ignora o catch
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>
                Exceção compatível
            </span>

            <span>
                Interrompe o try
            </span>

            <span>
                Executa o catch
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>
                Exceção incompatível
            </span>

            <span>
                Não encontra tratamento
            </span>

            <span>
                Pode encerrar o programa
            </span>

        </div>

    </div>

    <p>

        Quando uma exceção acontece, as instruções
        restantes dentro do try deixam de ser executadas.

    </p>

    <pre class="codigo-java"><code>try {

    System.out.println(
        "Início do try"
    );

    int resultado = 10 / 0;

    System.out.println(
        "Fim do try"
    );

} catch (
    ArithmeticException e
) {

    System.out.println(
        "Exceção capturada"
    );

}</code></pre>

    <p>

        Resultado:

    </p>

    <pre class="resultado-codigo"><code>Início do try
Exceção capturada</code></pre>

    <p>

        A mensagem “Fim do try” não foi exibida.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| OBJETO DA EXCEÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A variável da exceção

    </h2>

    <p>

        Nesta declaração:

    </p>

    <pre class="codigo-java"><code>catch (ArithmeticException e)</code></pre>

    <p>

        a variável <strong>e</strong> representa
        o objeto da exceção capturada.

    </p>

    <p>

        Podemos utilizar seus métodos para consultar
        informações sobre o problema.

    </p>

    <h3>

        Exibindo o objeto

    </h3>

    <pre class="codigo-java"><code>catch (
    ArithmeticException e
) {

    System.out.println(e);

}</code></pre>

    <p>

        Resultado semelhante a:

    </p>

    <pre class="resultado-codigo"><code>java.lang.ArithmeticException: / by zero</code></pre>

    <h3>

        Utilizando getMessage()

    </h3>

    <pre class="codigo-java"><code>catch (
    ArithmeticException e
) {

    System.out.println(
        e.getMessage()
    );

}</code></pre>

    <p>

        Resultado:

    </p>

    <pre class="resultado-codigo"><code>/ by zero</code></pre>

    <h3>

        Utilizando printStackTrace()

    </h3>

    <pre class="codigo-java"><code>catch (
    ArithmeticException e
) {

    e.printStackTrace();

}</code></pre>

    <p>

        Esse método apresenta informações técnicas
        sobre o caminho percorrido até a exceção.

    </p>

    <div class="aula-destaque">

        <strong>

            Durante o desenvolvimento:

        </strong>

        <p>

            O stack trace pode ajudar a localizar
            a origem de um problema.

        </p>

        <p>

            Para o usuário, normalmente apresentamos
            uma mensagem mais simples e compreensível.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXCEÇÃO ESPECÍFICA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Capturando exceções específicas

    </h2>

    <p>

        É possível capturar a classe geral Exception:

    </p>

    <pre class="codigo-java"><code>try {

    int resultado = 10 / 0;

} catch (Exception e) {

    System.out.println(
        "Ocorreu um problema."
    );

}</code></pre>

    <p>

        Porém, quando sabemos qual exceção pode acontecer,
        devemos preferir um tipo mais específico.

    </p>

    <pre class="codigo-java"><code>try {

    int resultado = 10 / 0;

} catch (
    ArithmeticException e
) {

    System.out.println(
        "O divisor não pode ser zero."
    );

}</code></pre>

    <p>

        A captura específica torna o código mais claro
        e permite fornecer respostas diferentes para
        cada problema.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MÚLTIPLOS CATCH
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando vários blocos catch

    </h2>

    <p>

        Um mesmo bloco try pode gerar diferentes
        tipos de exceção.

    </p>

    <p>

        Podemos criar mais de um catch:

    </p>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        String primeiroTexto = "100";

        String segundoTexto = "0";

        try {

            int numero1 =
                Integer.parseInt(
                    primeiroTexto
                );

            int numero2 =
                Integer.parseInt(
                    segundoTexto
                );

            int resultado =
                numero1 / numero2;

            System.out.println(
                "Resultado: "
                + resultado
            );

        } catch (
            NumberFormatException e
        ) {

            System.out.println(
                "Um dos valores não é inteiro."
            );

        } catch (
            ArithmeticException e
        ) {

            System.out.println(
                "O divisor não pode ser zero."
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <pre class="resultado-codigo"><code>O divisor não pode ser zero.</code></pre>

    <p>

        O programa está preparado para:

    </p>

    <ul>

        <li>
            uma conversão numérica inválida;
        </li>

        <li>
            uma divisão inteira por zero.
        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| ORDEM DOS CATCHES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A ordem dos blocos catch

    </h2>

    <p>

        Quando utilizamos vários catches relacionados,
        os tipos mais específicos devem aparecer
        antes dos mais gerais.

    </p>

    <p>

        Esta ordem está correta:

    </p>

    <pre class="codigo-java"><code>try {

    // Código

} catch (
    ArithmeticException e
) {

    // Tratamento específico

} catch (Exception e) {

    // Tratamento geral

}</code></pre>

    <p>

        Esta ordem está incorreta:

    </p>

    <pre class="codigo-java"><code>try {

    // Código

} catch (Exception e) {

    // Tratamento geral

} catch (
    ArithmeticException e
) {

    // Nunca será alcançado

}</code></pre>

    <p>

        Exception já conseguiria capturar
        ArithmeticException.

    </p>

    <p>

        Por isso, o segundo catch nunca seria alcançado
        e o código não seria compilado.

    </p>

    <div class="aula-destaque">

        <strong>

            Regra:

        </strong>

        <p>

            Coloque primeiro as exceções específicas
            e deixe as mais gerais por último.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MULTI-CATCH
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Capturando diferentes exceções juntas

    </h2>

    <p>

        Quando diferentes exceções receberão exatamente
        o mesmo tratamento, podemos utilizar
        <strong>multi-catch</strong>.

    </p>

    <pre class="codigo-java"><code>try {

    String texto = "abc";

    int numero =
        Integer.parseInt(texto);

    int resultado =
        100 / numero;

    System.out.println(resultado);

} catch (
    NumberFormatException
    | ArithmeticException e
) {

    System.out.println(
        "Não foi possível realizar a operação."
    );

}</code></pre>

    <p>

        O símbolo <strong>|</strong> separa os tipos
        de exceção capturados pelo mesmo bloco.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FINALLY
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O bloco finally

    </h2>

    <p>

        Além de try e catch, podemos utilizar
        o bloco <strong>finally</strong>.

    </p>

    <p>

        Ele é executado ao final do processo,
        tendo acontecido ou não uma exceção
        no fluxo normal do programa.

    </p>

    <pre class="codigo-java"><code>try {

    // Código que pode gerar uma exceção

} catch (Exception e) {

    // Tratamento da exceção

} finally {

    // Código executado ao final

}</code></pre>

    <p>

        O finally é utilizado principalmente para
        executar tarefas de encerramento ou limpeza.

    </p>

    <ul>

        <li>
            fechar arquivos;
        </li>

        <li>
            encerrar conexões;
        </li>

        <li>
            fechar leitores de dados;
        </li>

        <li>
            liberar recursos utilizados.
        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| FINALLY SEM EXCEÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Finally sem exceção

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        try {

            int resultado = 10 / 2;

            System.out.println(
                "Resultado: "
                + resultado
            );

        } catch (
            ArithmeticException e
        ) {

            System.out.println(
                "Divisão inválida."
            );

        } finally {

            System.out.println(
                "Operação finalizada."
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <pre class="resultado-codigo"><code>Resultado: 5
Operação finalizada.</code></pre>

    <p>

        Nenhuma exceção aconteceu, mas o finally
        foi executado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FINALLY COM EXCEÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Finally com exceção

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        try {

            int resultado = 10 / 0;

            System.out.println(resultado);

        } catch (
            ArithmeticException e
        ) {

            System.out.println(
                "Divisão inválida."
            );

        } finally {

            System.out.println(
                "Operação finalizada."
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <pre class="resultado-codigo"><code>Divisão inválida.
Operação finalizada.</code></pre>

    <p>

        A exceção foi capturada e o finally também
        foi executado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| TRY COM FINALLY
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Try pode existir sem catch?

    </h2>

    <p>

        Um bloco try precisa ser acompanhado
        por pelo menos um catch ou por um finally.

    </p>

    <p>

        Esta estrutura é válida:

    </p>

    <pre class="codigo-java"><code>try {

    System.out.println(
        "Executando operação."
    );

} finally {

    System.out.println(
        "Finalizando operação."
    );

}</code></pre>

    <p>

        Porém, normalmente utilizamos catch quando
        precisamos tratar uma exceção.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CONVERSÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Tratando uma conversão inválida

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        String texto = "dezessete";

        try {

            int idade =
                Integer.parseInt(texto);

            System.out.println(
                "Idade: " + idade
            );

        } catch (
            NumberFormatException e
        ) {

            System.out.println(
                "O texto não representa um inteiro."
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <pre class="resultado-codigo"><code>O texto não representa um inteiro.</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| ARRAY
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Tratando um índice inválido

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        String[] nomes = {
            "Ana",
            "Carlos",
            "Maria"
        };

        try {

            System.out.println(
                nomes[5]
            );

        } catch (
            ArrayIndexOutOfBoundsException e
        ) {

            System.out.println(
                "Essa posição não existe."
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <pre class="resultado-codigo"><code>Essa posição não existe.</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| NULL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Tratando uma referência null

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        String nome = null;

        try {

            System.out.println(
                nome.length()
            );

        } catch (
            NullPointerException e
        ) {

            System.out.println(
                "O nome não foi informado."
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <pre class="resultado-codigo"><code>O nome não foi informado.</code></pre>

    <div class="aula-destaque">

        <strong>

            Validação também é importante.

        </strong>

        <p>

            Em situações previsíveis, podemos verificar
            o valor antes de utilizá-lo.

        </p>

    </div>

    <pre class="codigo-java"><code>if (nome != null) {

    System.out.println(
        nome.length()
    );

} else {

    System.out.println(
        "O nome não foi informado."
    );

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Try e catch com Scanner

    </h2>

    <p>

        O Scanner pode gerar uma
        <strong>InputMismatchException</strong>
        quando recebe um tipo diferente do esperado.

    </p>

    <pre class="codigo-java"><code>import java.util.InputMismatchException;
import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner scanner =
            new Scanner(System.in);

        try {

            System.out.print(
                "Digite sua idade: "
            );

            int idade =
                scanner.nextInt();

            System.out.println(
                "Sua idade é: "
                + idade
            );

        } catch (
            InputMismatchException e
        ) {

            System.out.println(
                "Digite apenas números inteiros."
            );

        } finally {

            scanner.close();

        }

    }

}</code></pre>

    <p>

        Com uma entrada válida:

    </p>

    <pre class="resultado-codigo"><code>Digite sua idade: 17
Sua idade é: 17</code></pre>

    <p>

        Com uma entrada inválida:

    </p>

    <pre class="resultado-codigo"><code>Digite sua idade: abc
Digite apenas números inteiros.</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| USANDO NEXTLINE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Lendo como texto e convertendo

    </h2>

    <p>

        Outra opção é ler a entrada com nextLine()
        e depois tentar convertê-la.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner scanner =
            new Scanner(System.in);

        try {

            System.out.print(
                "Digite sua idade: "
            );

            String entrada =
                scanner.nextLine();

            int idade =
                Integer.parseInt(entrada);

            System.out.println(
                "Sua idade é: "
                + idade
            );

        } catch (
            NumberFormatException e
        ) {

            System.out.println(
                "Informe uma idade válida."
            );

        } finally {

            scanner.close();

        }

    }

}</code></pre>

    <p>

        Essa abordagem permite trabalhar diretamente
        com NumberFormatException.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO DIVISÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: calculadora de divisão

    </h2>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner scanner =
            new Scanner(System.in);

        try {

            System.out.print(
                "Digite o primeiro número: "
            );

            String primeiraEntrada =
                scanner.nextLine();

            System.out.print(
                "Digite o segundo número: "
            );

            String segundaEntrada =
                scanner.nextLine();

            int numero1 =
                Integer.parseInt(
                    primeiraEntrada
                );

            int numero2 =
                Integer.parseInt(
                    segundaEntrada
                );

            int resultado =
                numero1 / numero2;

            System.out.println(
                "Resultado: "
                + resultado
            );

        } catch (
            NumberFormatException e
        ) {

            System.out.println(
                "Digite apenas números inteiros."
            );

        } catch (
            ArithmeticException e
        ) {

            System.out.println(
                "Não é possível dividir por zero."
            );

        } finally {

            scanner.close();

            System.out.println(
                "Programa finalizado."
            );

        }

    }

}</code></pre>

    <p>

        Com valores válidos:

    </p>

    <pre class="resultado-codigo"><code>Digite o primeiro número: 20
Digite o segundo número: 4
Resultado: 5
Programa finalizado.</code></pre>

    <p>

        Com divisor igual a zero:

    </p>

    <pre class="resultado-codigo"><code>Digite o primeiro número: 20
Digite o segundo número: 0
Não é possível dividir por zero.
Programa finalizado.</code></pre>

    <p>

        Com uma entrada inválida:

    </p>

    <pre class="resultado-codigo"><code>Digite o primeiro número: abc
Digite o segundo número: 5
Digite apenas números inteiros.
Programa finalizado.</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| TRY COM MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exceções dentro de métodos

    </h2>

    <p>

        Uma exceção também pode acontecer dentro
        de um método chamado pelo bloco try.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static int dividir(
        int numero1,
        int numero2
    ) {

        return numero1 / numero2;

    }


    public static void main(String[] args) {

        try {

            int resultado =
                dividir(10, 0);

            System.out.println(resultado);

        } catch (
            ArithmeticException e
        ) {

            System.out.println(
                "Divisão inválida."
            );

        }

    }

}</code></pre>

    <p>

        A exceção surgiu dentro de dividir(),
        mas foi capturada pelo catch no método main().

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PROPAGAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Propagação de exceções

    </h2>

    <p>

        Quando um método não trata uma exceção,
        ela pode ser enviada para o método que
        realizou a chamada.

    </p>

    <pre class="codigo-java"><code>static int calcular() {

    return 10 / 0;

}


public static void main(String[] args) {

    try {

        int resultado =
            calcular();

        System.out.println(resultado);

    } catch (
        ArithmeticException e
    ) {

        System.out.println(
            "A operação falhou."
        );

    }

}</code></pre>

    <p>

        Esse movimento da exceção entre as chamadas
        é conhecido como <strong>propagação</strong>.

    </p>

    <div class="aula-destaque">

        <strong>

            A exceção procura um tratamento.

        </strong>

        <p>

            Caso nenhum método a capture, ela pode
            chegar ao final da aplicação e interromper
            a execução.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| TRY-WITH-RESOURCES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Try-with-resources

    </h2>

    <p>

        Para recursos que precisam ser fechados,
        Java também possui a estrutura
        <strong>try-with-resources</strong>.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        try (
            Scanner scanner =
                new Scanner(System.in)
        ) {

            System.out.print(
                "Digite seu nome: "
            );

            String nome =
                scanner.nextLine();

            System.out.println(
                "Olá, " + nome
            );

        }

    }

}</code></pre>

    <p>

        O Scanner é fechado automaticamente
        ao final do bloco.

    </p>

    <div class="aula-destaque">

        <strong>

            Esse recurso é útil para:

        </strong>

        <p>

            arquivos, conexões, leitores e outros
            objetos que precisam ser encerrados.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PREVENÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Tratar ou prevenir?

    </h2>

    <p>

        Try e catch são importantes, mas algumas
        situações podem ser evitadas por validações.

    </p>

    <h3>

        Evitando divisão por zero

    </h3>

    <pre class="codigo-java"><code>int numero1 = 10;

int numero2 = 0;

if (numero2 != 0) {

    int resultado =
        numero1 / numero2;

    System.out.println(resultado);

} else {

    System.out.println(
        "O divisor não pode ser zero."
    );

}</code></pre>

    <h3>

        Verificando o índice

    </h3>

    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria"
};

int indice = 5;

if (
    indice &gt;= 0
    &amp;&amp; indice &lt; nomes.length
) {

    System.out.println(
        nomes[indice]
    );

} else {

    System.out.println(
        "Índice inválido."
    );

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Boa prática:

        </strong>

        <p>

            Valide situações previsíveis e utilize
            exceções para controlar falhas que realmente
            interrompem o fluxo esperado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| NÃO IGNORAR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Não ignore as exceções

    </h2>

    <p>

        Este código captura uma exceção, mas não
        realiza nenhuma ação:

    </p>

    <pre class="codigo-java"><code>try {

    int resultado = 10 / 0;

} catch (
    ArithmeticException e
) {

}</code></pre>

    <p>

        Um catch vazio pode esconder o problema
        e dificultar sua localização.

    </p>

    <p>

        Uma resposta melhor seria:

    </p>

    <pre class="codigo-java"><code>try {

    int resultado = 10 / 0;

} catch (
    ArithmeticException e
) {

    System.out.println(
        "Não foi possível realizar a divisão."
    );

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| MENSAGEM TÉCNICA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Mensagem para usuário e mensagem técnica

    </h2>

    <p>

        Uma mensagem para o usuário deve explicar
        de forma clara o que ele precisa fazer.

    </p>

    <pre class="resultado-codigo"><code>Digite apenas números inteiros.</code></pre>

    <p>

        Já uma mensagem técnica pode registrar
        detalhes importantes para o programador.

    </p>

    <pre class="codigo-java"><code>catch (
    NumberFormatException e
) {

    System.out.println(
        "Digite apenas números inteiros."
    );

    System.out.println(
        "Detalhe técnico: "
        + e.getMessage()
    );

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Em sistemas reais:

        </strong>

        <p>

            Informações técnicas normalmente são
            registradas internamente, em vez de serem
            mostradas diretamente ao usuário.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| BOAS PRÁTICAS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Boas práticas

    </h2>

    <ul>

        <li>

            coloque dentro do try apenas as instruções
            que realmente podem falhar;

        </li>

        <li>

            prefira capturar exceções específicas;

        </li>

        <li>

            deixe capturas gerais por último;

        </li>

        <li>

            apresente mensagens compreensíveis;

        </li>

        <li>

            não deixe o catch vazio;

        </li>

        <li>

            não utilize exceções para esconder
            problemas do código;

        </li>

        <li>

            valide situações previsíveis;

        </li>

        <li>

            utilize finally para tarefas de encerramento;

        </li>

        <li>

            utilize try-with-resources quando o recurso
            puder ser fechado automaticamente;

        </li>

        <li>

            registre informações técnicas quando
            necessário;

        </li>

        <li>

            não exponha detalhes internos desnecessários
            ao usuário.

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

        Utilizar try sem catch ou finally

    </h3>

    <p>

        Esta estrutura está incompleta:

    </p>

    <pre class="codigo-java"><code>try {

    int resultado = 10 / 0;

}</code></pre>

    <p>

        O try precisa ser acompanhado por catch,
        finally ou ambos.

    </p>

    <h3>

        Capturar a exceção errada

    </h3>

    <pre class="codigo-java"><code>try {

    int resultado = 10 / 0;

} catch (
    NumberFormatException e
) {

    System.out.println(
        "Conversão inválida."
    );

}</code></pre>

    <p>

        A divisão gera ArithmeticException,
        não NumberFormatException.

    </p>

    <h3>

        Colocar Exception antes de uma classe específica

    </h3>

    <pre class="codigo-java"><code>catch (Exception e) {

} catch (
    ArithmeticException e
) {

}</code></pre>

    <p>

        O segundo catch nunca seria alcançado.

    </p>

    <h3>

        Colocar todo o programa dentro de um try

    </h3>

    <p>

        Um bloco muito grande dificulta descobrir
        qual instrução realmente causou o problema.

    </p>

    <h3>

        Mostrar somente “erro”

    </h3>

    <pre class="resultado-codigo"><code>Erro.</code></pre>

    <p>

        Uma mensagem mais útil seria:

    </p>

    <pre class="resultado-codigo"><code>O divisor não pode ser zero.</code></pre>

    <h3>

        Utilizar catch vazio

    </h3>

    <p>

        Ignorar silenciosamente uma exceção pode
        esconder falhas importantes.

    </p>

    <h3>

        Acreditar que try e catch corrigem erros
        de compilação

    </h3>

    <p>

        Try e catch tratam exceções que acontecem
        durante a execução.

    </p>

    <p>

        Eles não corrigem erros de sintaxe ou
        tipos incompatíveis.

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

        Vamos criar um programa que consulta um nome
        armazenado em um array.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        String[] nomes = {
            "Ana",
            "Carlos",
            "Maria",
            "Robert"
        };

        Scanner scanner =
            new Scanner(System.in);

        try {

            System.out.print(
                "Digite um índice: "
            );

            String entrada =
                scanner.nextLine();

            int indice =
                Integer.parseInt(entrada);

            String nome =
                nomes[indice];

            System.out.println(
                "Nome encontrado: "
                + nome
            );

        } catch (
            NumberFormatException e
        ) {

            System.out.println(
                "Digite apenas números inteiros."
            );

        } catch (
            ArrayIndexOutOfBoundsException e
        ) {

            System.out.println(
                "Esse índice não existe."
            );

        } finally {

            scanner.close();

            System.out.println(
                "Consulta finalizada."
            );

        }

    }

}</code></pre>

    <p>

        Com índice válido:

    </p>

    <pre class="resultado-codigo"><code>Digite um índice: 2
Nome encontrado: Maria
Consulta finalizada.</code></pre>

    <p>

        Com índice inválido:

    </p>

    <pre class="resultado-codigo"><code>Digite um índice: 10
Esse índice não existe.
Consulta finalizada.</code></pre>

    <p>

        Com texto inválido:

    </p>

    <pre class="resultado-codigo"><code>Digite um índice: abc
Digite apenas números inteiros.
Consulta finalizada.</code></pre>

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

        Crie uma calculadora que peça ao usuário:

    </p>

    <ul>

        <li>
            o primeiro número;
        </li>

        <li>
            o segundo número;
        </li>

        <li>
            a operação desejada;
        </li>

        <li>
            soma, subtração, multiplicação ou divisão.
        </li>

    </ul>

    <p>

        O programa deverá tratar:

    </p>

    <ul>

        <li>
            NumberFormatException;
        </li>

        <li>
            ArithmeticException;
        </li>

        <li>
            operação inválida;
        </li>

        <li>
            divisão por zero;
        </li>

        <li>
            encerramento do Scanner.
        </li>

    </ul>

    <p>

        Exemplo válido:

    </p>

    <pre class="resultado-codigo"><code>Primeiro número: 20
Segundo número: 4
Operação: /
Resultado: 5
Calculadora encerrada.</code></pre>

    <p>

        Exemplo com divisão inválida:

    </p>

    <pre class="resultado-codigo"><code>Primeiro número: 20
Segundo número: 0
Operação: /
Não é possível dividir por zero.
Calculadora encerrada.</code></pre>

    <p>

        Exemplo com texto inválido:

    </p>

    <pre class="resultado-codigo"><code>Primeiro número: abc
Informe apenas números inteiros.
Calculadora encerrada.</code></pre>

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
        try, catch e finally para tratar exceções.

    </p>

    <ul>

        <li>
            try contém o código que pode gerar
            uma exceção;
        </li>

        <li>
            catch captura uma exceção compatível;
        </li>

        <li>
            a exceção interrompe o restante do try;
        </li>

        <li>
            depois do tratamento, o programa pode
            continuar;
        </li>

        <li>
            a variável do catch representa o objeto
            da exceção;
        </li>

        <li>
            getMessage() retorna uma mensagem sobre
            o problema;
        </li>

        <li>
            printStackTrace() apresenta informações
            técnicas;
        </li>

        <li>
            capturas específicas deixam o tratamento
            mais claro;
        </li>

        <li>
            podemos utilizar vários blocos catch;
        </li>

        <li>
            catches específicos devem aparecer
            antes dos gerais;
        </li>

        <li>
            multi-catch permite tratar diferentes
            exceções no mesmo bloco;
        </li>

        <li>
            finally executa tarefas de encerramento;
        </li>

        <li>
            try-with-resources fecha recursos
            automaticamente;
        </li>

        <li>
            exceções podem se propagar entre métodos;
        </li>

        <li>
            situações previsíveis também podem
            ser evitadas por validações;
        </li>

        <li>
            blocos catch não devem permanecer vazios;
        </li>

        <li>
            mensagens para usuários devem ser claras;
        </li>

        <li>
            detalhes técnicos podem ser utilizados
            para investigação e registro.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Tratar uma exceção significa reconhecer
                uma falha e definir como o programa
                deverá responder a ela.

            </strong>

        </p>

    </div>

    <p>

        Com esta aula, concluímos a parte principal
        de fundamentos da linguagem Java e dos conceitos
        introdutórios de Programação Orientada a Objetos.

    </p>

    <p>

        Agora você poderá revisar suas anotações no
        caderno e aplicar os conteúdos em exercícios
        e projetos práticos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Programar não é apenas preparar o caminho
        correto, mas também decidir o que fazer
        quando algo não acontece como esperado."

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
            $linkCaderno
        ) ?>"
        class="aula-botao"
    >

        Ir para o Caderno →

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