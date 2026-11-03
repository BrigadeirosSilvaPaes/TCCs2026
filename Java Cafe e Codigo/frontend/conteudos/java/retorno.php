<?php

require_once __DIR__ . "/../../../backend/config/app.php";
require_once __DIR__ . "/../../../backend/config/bootstrap.php";


/*
|--------------------------------------------------------------------------
| VERIFICA LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["usuario"])) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/login/login.php"
    );

    exit;

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
    content="Aprenda como criar métodos que devolvem resultados utilizando return em Java."
>

<title>
    Retorno de Métodos | Java, Café e Código
</title>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css"
>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/aula.css?v=2"
>

</head>

<body>

<?php

require_once __DIR__ . "/../../componentes/header.php";

?>

<main class="aula-container">


<!--
|--------------------------------------------------------------------------
| CABEÇALHO
|--------------------------------------------------------------------------
-->

<section class="aula-cabecalho">

    <span class="aula-numero">

        AULA 14

    </span>

    <h1>

        Retorno de Métodos

    </h1>

    <p>

        Aprenda como fazer um método processar
        informações e devolver um resultado para
        outras partes do programa.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos que devolvem resultados

    </h2>

    <p>

        Na aula anterior, aprendemos como enviar
        informações para métodos utilizando parâmetros.

    </p>

    <pre class="codigo-java"><code>static void somar(
    int numero1,
    int numero2
) {

    int resultado =
        numero1 + numero2;

    System.out.println(resultado);

}</code></pre>

    <p>

        Esse método calcula a soma e mostra
        o resultado diretamente na tela.

    </p>

    <p>

        Porém, podemos querer utilizar o resultado
        em outra parte do programa.

    </p>

    <p>

        Podemos armazená-lo, compará-lo ou utilizá-lo
        em outro cálculo.

    </p>

    <p>

        Para isso, o método precisa devolver
        o resultado.

    </p>

    <div class="aula-destaque">

        <strong>

            Ideia principal:

        </strong>

        <p>

            Um método com retorno entrega um valor
            para o local em que foi chamado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VOID
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Relembrando o void

    </h2>

    <p>

        A palavra <strong>void</strong> indica
        que o método não devolve um valor.

    </p>

    <pre class="codigo-java"><code>static void mostrarMensagem() {

    System.out.println(
        "Olá, estudante!"
    );

}</code></pre>

    <p>

        Esse método realiza uma ação,
        mas não entrega um resultado.

    </p>

    <pre class="codigo-java"><code>mostrarMensagem();</code></pre>

    <div class="resultado-codigo"><code>Olá, estudante!</code></div>

    <p>

        Não podemos armazenar essa chamada
        como um valor.

    </p>

    <pre class="codigo-java"><code>String mensagem =
    mostrarMensagem();</code></pre>

    <p>

        Essa tentativa está incorreta porque
        o método é void.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODO COM RETORNO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando um método com retorno

    </h2>

    <p>

        Para devolver um valor, precisamos definir
        o tipo que será retornado.

    </p>

    <pre class="codigo-java"><code>static int somar(
    int numero1,
    int numero2
) {

    return numero1 + numero2;

}</code></pre>

    <p>

        O método foi declarado com o tipo
        <strong>int</strong>.

    </p>

    <p>

        Isso significa que ele deverá devolver
        um número inteiro.

    </p>

    <p>

        A palavra <strong>return</strong> envia
        o resultado para o local da chamada.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PARTES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Partes de um método com retorno

    </h2>

    <pre class="codigo-java"><code>static int somar(
    int numero1,
    int numero2
) {

    return numero1 + numero2;

}</code></pre>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>

                Parte

            </span>

            <span>

                Função

            </span>

            <span>

                Exemplo

            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>

                Tipo de retorno

            </span>

            <span>

                Define qual valor será devolvido

            </span>

            <span>

                int

            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>

                Nome

            </span>

            <span>

                Identifica o método

            </span>

            <span>

                somar

            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>

                Parâmetros

            </span>

            <span>

                Recebem informações

            </span>

            <span>

                int numero1, int numero2

            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>

                return

            </span>

            <span>

                Devolve o resultado

            </span>

            <span>

                return numero1 + numero2;

            </span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ARMAZENANDO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Armazenando o retorno

    </h2>

    <p>

        O valor devolvido pode ser armazenado
        em uma variável.

    </p>

    <pre class="codigo-java"><code>int resultado = somar(10, 5);</code></pre>

    <p>

        O método recebe 10 e 5.

    </p>

    <p>

        Depois realiza:

    </p>

    <pre class="codigo-java"><code>10 + 5</code></pre>

    <p>

        O return devolve 15.

    </p>

    <p>

        Portanto, essa chamada funciona como:

    </p>

    <pre class="codigo-java"><code>int resultado = 15;</code></pre>

    <p>

        Podemos exibir a variável:

    </p>

    <pre class="codigo-java"><code>System.out.println(resultado);</code></pre>

    <div class="resultado-codigo"><code>15</code></div>

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

    <pre class="codigo-java"><code>public class Main {

    static int somar(
        int numero1,
        int numero2
    ) {

        return numero1 + numero2;

    }


    public static void main(String[] args) {

        int resultado =
            somar(20, 30);

        System.out.println(
            "Resultado: " + resultado
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Resultado: 50</code></div>

    <div class="aula-destaque">

        <strong>

            Observe:

        </strong>

        <p>

            O método calcula.

        </p>

        <p>

            O main decide como utilizar
            o resultado devolvido.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VOID X RETORNO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Void ou método com retorno?

    </h2>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>

                Método

            </span>

            <span>

                Comportamento

            </span>

            <span>

                Exemplo

            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>

                void

            </span>

            <span>

                Realiza uma ação sem devolver valor

            </span>

            <span>

                mostrarMensagem()

            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>

                Com retorno

            </span>

            <span>

                Devolve um valor para quem chamou

            </span>

            <span>

                calcularMedia()

            </span>

        </div>

    </div>

    <p>

        Um método void pode mostrar diretamente
        uma informação.

    </p>

    <pre class="codigo-java"><code>static void mostrarSoma(
    int numero1,
    int numero2
) {

    System.out.println(
        numero1 + numero2
    );

}</code></pre>

    <p>

        Um método com retorno devolve o resultado.

    </p>

    <pre class="codigo-java"><code>static int somar(
    int numero1,
    int numero2
) {

    return numero1 + numero2;

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Vantagem do retorno:

        </strong>

        <p>

            O resultado pode ser armazenado,
            comparado, exibido ou utilizado
            em outros cálculos.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| RETORNO DIRETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando o retorno diretamente

    </h2>

    <p>

        Nem sempre precisamos criar uma variável.

    </p>

    <p>

        Podemos utilizar o método diretamente
        dentro de outra instrução.

    </p>

    <pre class="codigo-java"><code>static int multiplicar(
    int numero1,
    int numero2
) {

    return numero1 * numero2;

}</code></pre>

    <p>

        Chamada direta:

    </p>

    <pre class="codigo-java"><code>System.out.println(
    multiplicar(5, 4)
);</code></pre>

    <div class="resultado-codigo"><code>20</code></div>

    <p>

        Também podemos misturar com um texto.

    </p>

    <pre class="codigo-java"><code>System.out.println(
    "Resultado: " + multiplicar(5, 4)
);</code></pre>

    <div class="resultado-codigo"><code>Resultado: 20</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| RETORNO EM EXPRESSÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando o retorno em cálculos

    </h2>

    <p>

        O valor retornado pode participar
        de outras expressões.

    </p>

    <pre class="codigo-java"><code>static int dobrar(int numero) {

    return numero * 2;

}</code></pre>

    <p>

        Podemos fazer:

    </p>

    <pre class="codigo-java"><code>int resultado =
    dobrar(5) + 10;</code></pre>

    <p>

        Primeiro, dobrar(5) retorna 10.

    </p>

    <p>

        Depois, o programa realiza:

    </p>

    <pre class="codigo-java"><code>10 + 10</code></pre>

    <div class="resultado-codigo"><code>20</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| STRING
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Retornando uma String

    </h2>

    <p>

        Para retornar um texto, declaramos
        o método com o tipo String.

    </p>

    <pre class="codigo-java"><code>static String criarMensagem(
    String nome
) {

    return "Olá, " + nome + "!";

}</code></pre>

    <p>

        Utilizando o retorno:

    </p>

    <pre class="codigo-java"><code>String mensagem =
    criarMensagem("Robert");

System.out.println(mensagem);</code></pre>

    <div class="resultado-codigo"><code>Olá, Robert!</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| DOUBLE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Retornando um double

    </h2>

    <p>

        Podemos criar um método que calcula
        a média de duas notas.

    </p>

    <pre class="codigo-java"><code>static double calcularMedia(
    double nota1,
    double nota2
) {

    return (nota1 + nota2) / 2;

}</code></pre>

    <p>

        Chamando o método:

    </p>

    <pre class="codigo-java"><code>double media =
    calcularMedia(8.0, 10.0);

System.out.println(
    "Média: " + media
);</code></pre>

    <div class="resultado-codigo"><code>Média: 9.0</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| BOOLEAN
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Retornando boolean

    </h2>

    <p>

        Um método boolean devolve
        true ou false.

    </p>

    <pre class="codigo-java"><code>static boolean maiorDeIdade(
    int idade
) {

    return idade &gt;= 18;

}</code></pre>

    <p>

        Utilizando o método:

    </p>

    <pre class="codigo-java"><code>boolean resultado =
    maiorDeIdade(20);

System.out.println(resultado);</code></pre>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        Também podemos utilizar o retorno
        diretamente em um if.

    </p>

    <pre class="codigo-java"><code>if (maiorDeIdade(20)) {

    System.out.println(
        "Entrada permitida."
    );

}</code></pre>

    <div class="resultado-codigo"><code>Entrada permitida.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| RETURN COM IF
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Return com if e else

    </h2>

    <p>

        Um método pode possuir caminhos
        diferentes de retorno.

    </p>

    <pre class="codigo-java"><code>static String verificarSituacao(
    double nota
) {

    if (nota &gt;= 7) {

        return "Aprovado";

    } else {

        return "Reprovado";

    }

}</code></pre>

    <p>

        Chamando:

    </p>

    <pre class="codigo-java"><code>String situacao =
    verificarSituacao(8.5);

System.out.println(situacao);</code></pre>

    <div class="resultado-codigo"><code>Aprovado</code></div>

    <div class="aula-destaque">

        <strong>

            Importante:

        </strong>

        <p>

            Todos os caminhos possíveis precisam
            devolver um valor compatível com
            o tipo do método.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| RETORNO SIMPLIFICADO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Simplificando um retorno booleano

    </h2>

    <p>

        Observe este método:

    </p>

    <pre class="codigo-java"><code>static boolean maiorDeIdade(
    int idade
) {

    if (idade &gt;= 18) {

        return true;

    } else {

        return false;

    }

}</code></pre>

    <p>

        A comparação idade &gt;= 18 já produz
        um valor booleano.

    </p>

    <p>

        Podemos escrever de forma mais simples:

    </p>

    <pre class="codigo-java"><code>static boolean maiorDeIdade(
    int idade
) {

    return idade &gt;= 18;

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Boa prática:

        </strong>

        <p>

            Quando uma expressão já produz
            true ou false, podemos retorná-la
            diretamente.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| RETURN ENCERRA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Return encerra o método

    </h2>

    <p>

        Quando o Java executa um return,
        o método termina naquele ponto.

    </p>

    <pre class="codigo-java"><code>static String verificarNumero(
    int numero
) {

    if (numero &lt; 0) {

        return "Número negativo";

    }

    return "Número não negativo";

}</code></pre>

    <p>

        Se o número for negativo, o primeiro
        return será executado.

    </p>

    <p>

        O restante do método não será executado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CÓDIGO INALCANÇÁVEL
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Código depois do return

    </h2>

    <p>

        Uma instrução colocada depois de um return
        no mesmo caminho não poderá ser executada.

    </p>

    <p>

        Este exemplo está incorreto:

    </p>

    <pre class="codigo-java"><code>static int dobrar(int numero) {

    return numero * 2;

    System.out.println(
        "Cálculo realizado."
    );

}</code></pre>

    <p>

        O método termina no return.

    </p>

    <p>

        Portanto, o System.out.println()
        nunca seria alcançado.

    </p>

    <div class="aula-destaque">

        <strong>

            Regra:

        </strong>

        <p>

            Coloque as instruções necessárias
            antes do return.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| TODOS OS CAMINHOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Todos os caminhos precisam retornar

    </h2>

    <p>

        Um método com tipo de retorno precisa
        devolver um valor em todos os caminhos possíveis.

    </p>

    <p>

        Este exemplo possui um problema:

    </p>

    <pre class="codigo-java"><code>static String verificarNota(
    double nota
) {

    if (nota &gt;= 7) {

        return "Aprovado";

    }

}</code></pre>

    <p>

        Quando a nota for menor que 7,
        nenhum valor será retornado.

    </p>

    <p>

        Podemos corrigir com else:

    </p>

    <pre class="codigo-java"><code>static String verificarNota(
    double nota
) {

    if (nota &gt;= 7) {

        return "Aprovado";

    } else {

        return "Reprovado";

    }

}</code></pre>

    <p>

        Também podemos escrever:

    </p>

    <pre class="codigo-java"><code>static String verificarNota(
    double nota
) {

    if (nota &gt;= 7) {

        return "Aprovado";

    }

    return "Reprovado";

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| COMPATIBILIDADE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O valor deve ser compatível

    </h2>

    <p>

        O tipo do valor retornado precisa combinar
        com o tipo declarado no método.

    </p>

    <p>

        Método correto:

    </p>

    <pre class="codigo-java"><code>static int dobrar(int numero) {

    return numero * 2;

}</code></pre>

    <p>

        O método é int e devolve um inteiro.

    </p>

    <p>

        Este método está incorreto:

    </p>

    <pre class="codigo-java"><code>static int criarMensagem() {

    return "Olá";

}</code></pre>

    <p>

        O método foi declarado como int,
        mas está tentando devolver uma String.

    </p>

    <p>

        O correto seria:

    </p>

    <pre class="codigo-java"><code>static String criarMensagem() {

    return "Olá";

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| COMBINANDO MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando o retorno de um método em outro

    </h2>

    <p>

        Métodos podem trabalhar em conjunto.

    </p>

    <pre class="codigo-java"><code>static double calcularMedia(
    double nota1,
    double nota2
) {

    return (nota1 + nota2) / 2;

}


static String verificarSituacao(
    double media
) {

    if (media &gt;= 7) {

        return "Aprovado";

    }

    return "Reprovado";

}</code></pre>

    <p>

        Primeiro calculamos a média.

    </p>

    <pre class="codigo-java"><code>double media =
    calcularMedia(8.0, 7.0);</code></pre>

    <p>

        Depois enviamos esse resultado
        para outro método.

    </p>

    <pre class="codigo-java"><code>String situacao =
    verificarSituacao(media);</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO PRÁTICO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: resultado do estudante

    </h2>

    <pre class="codigo-java"><code>public class Main {

    static double calcularMedia(
        double nota1,
        double nota2
    ) {

        return (nota1 + nota2) / 2;

    }


    static boolean possuiFrequencia(
        double frequencia
    ) {

        return frequencia &gt;= 75;

    }


    static String verificarSituacao(
        double media,
        double frequencia
    ) {

        boolean notaSuficiente =
            media &gt;= 7;

        boolean frequenciaSuficiente =
            possuiFrequencia(frequencia);

        if (
            notaSuficiente
            &amp;&amp; frequenciaSuficiente
        ) {

            return "Aprovado";

        }

        return "Reprovado";

    }


    public static void main(String[] args) {

        String nome = "Robert";

        double media =
            calcularMedia(8.0, 7.0);

        double frequencia = 80.0;

        String situacao =
            verificarSituacao(
                media,
                frequencia
            );

        System.out.println(
            "Aluno: " + nome
        );

        System.out.println(
            "Média: " + media
        );

        System.out.println(
            "Frequência: " + frequencia
        );

        System.out.println(
            "Situação: " + situacao
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Aluno: Robert
Média: 7.5
Frequência: 80.0
Situação: Aprovado</code></div>

    <p>

        Cada método possui uma responsabilidade:

    </p>

    <ul>

        <li>

            calcularMedia calcula e devolve a média;

        </li>

        <li>

            possuiFrequencia verifica a frequência;

        </li>

        <li>

            verificarSituacao devolve a situação final;

        </li>

        <li>

            o main organiza os resultados.

        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| VOID COM RETURN
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Return sem valor em métodos void

    </h2>

    <p>

        Um método void não devolve um resultado,
        mas pode utilizar return sem valor para
        encerrar sua execução.

    </p>

    <pre class="codigo-java"><code>static void mostrarIdade(
    int idade
) {

    if (idade &lt; 0) {

        System.out.println(
            "Idade inválida."
        );

        return;

    }

    System.out.println(
        "Idade: " + idade
    );

}</code></pre>

    <p>

        Quando a idade for negativa, o método
        exibirá a mensagem e será encerrado.

    </p>

    <div class="aula-destaque">

        <strong>

            Diferença:

        </strong>

        <p>

            Em um método void, podemos utilizar
            return; sem devolver valor.

        </p>

        <p>

            Em métodos int, String, double ou boolean,
            o return precisa devolver um valor compatível.

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

        Utilizar void e tentar devolver um valor

    </h3>

    <p>

        Este método está incorreto:

    </p>

    <pre class="codigo-java"><code>static void somar(
    int numero1,
    int numero2
) {

    return numero1 + numero2;

}</code></pre>

    <p>

        Para devolver um inteiro, utilize int.

    </p>

    <pre class="codigo-java"><code>static int somar(
    int numero1,
    int numero2
) {

    return numero1 + numero2;

}</code></pre>

    <h3>

        Declarar um tipo e retornar outro

    </h3>

    <pre class="codigo-java"><code>static int mensagem() {

    return "Olá";

}</code></pre>

    <p>

        O tipo declarado e o valor retornado
        precisam ser compatíveis.

    </p>

    <h3>

        Esquecer o return

    </h3>

    <pre class="codigo-java"><code>static int somar(
    int numero1,
    int numero2
) {

    int resultado =
        numero1 + numero2;

}</code></pre>

    <p>

        O resultado foi calculado, mas não devolvido.

    </p>

    <pre class="codigo-java"><code>return resultado;</code></pre>

    <h3>

        Não retornar em todos os caminhos

    </h3>

    <p>

        Verifique se todas as possibilidades
        devolvem um valor.

    </p>

    <h3>

        Colocar instruções inalcançáveis

    </h3>

    <p>

        Instruções no mesmo caminho depois do return
        não serão executadas.

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

        Vamos criar métodos para calcular
        o valor de uma compra.

    </p>

    <pre class="codigo-java"><code>public class Main {

    static double calcularSubtotal(
        double preco,
        int quantidade
    ) {

        return preco * quantidade;

    }


    static double aplicarDesconto(
        double subtotal,
        double percentual
    ) {

        double desconto =
            subtotal * percentual / 100;

        return subtotal - desconto;

    }


    public static void main(String[] args) {

        double subtotal =
            calcularSubtotal(
                50.0,
                3
            );

        double total =
            aplicarDesconto(
                subtotal,
                10
            );

        System.out.println(
            "Subtotal: R$ " + subtotal
        );

        System.out.println(
            "Total: R$ " + total
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Subtotal: R$ 150.0
Total: R$ 135.0</code></div>

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

        Crie um programa com os métodos:

    </p>

    <ul>

        <li>

            calcularMedia, que recebe três notas
            e devolve a média;

        </li>

        <li>

            possuiFrequencia, que recebe uma frequência
            e devolve true ou false;

        </li>

        <li>

            verificarSituacao, que recebe a média
            e a frequência e devolve uma String.

        </li>

    </ul>

    <p>

        As possíveis situações serão:

    </p>

    <ul>

        <li>

            aprovado;

        </li>

        <li>

            reprovado por nota;

        </li>

        <li>

            reprovado por frequência.

        </li>

    </ul>

    <div class="resultado-codigo"><code>Média: 8.0
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

        Nesta aula, aprendemos como métodos
        podem devolver valores.

    </p>

    <ul>

        <li>

            void indica que o método não
            devolve um valor;

        </li>

        <li>

            métodos com retorno possuem
            um tipo declarado;

        </li>

        <li>

            return devolve um valor;

        </li>

        <li>

            o valor devolvido deve ser compatível
            com o tipo do método;

        </li>

        <li>

            o retorno pode ser armazenado
            em uma variável;

        </li>

        <li>

            o retorno pode ser utilizado
            diretamente em expressões;

        </li>

        <li>

            métodos podem retornar int,
            double, String, boolean e outros tipos;

        </li>

        <li>

            return encerra a execução
            do método naquele ponto;

        </li>

        <li>

            todos os caminhos de um método
            precisam devolver um valor;

        </li>

        <li>

            métodos podem utilizar retornos
            de outros métodos;

        </li>

        <li>

            métodos void podem utilizar return;
            sem devolver valor.

        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Um método com retorno processa
                informações e entrega um resultado
                para o restante do programa.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, iniciaremos as estruturas
        de repetição estudando o laço for.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Um método se torna ainda mais útil quando
        pode receber dados, processá-los e devolver
        um resultado."

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| NAVEGAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-acoes">

    <a
        href="<?= BASE_URL ?>/frontend/conteudos/index.php"
        class="aula-botao"
    >

        ← Voltar para conteúdos

    </a>

    <a
        href="<?= BASE_URL ?>/frontend/conteudos/java/parametros.php"
        class="aula-botao"
    >

        ← Aula anterior

    </a>

    <a
        href="<?= BASE_URL ?>/frontend/conteudos/java/for.php"
        class="aula-botao"
    >

        Próxima aula: Estrutura for →

    </a>

</section>

</main>

<?php

require_once __DIR__ . "/../../componentes/footer.php";

?>

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js"
></script>

</body>

</html>