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
                "tipos_dados"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "operadores_matematicos"
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
    content="Aprenda como receber informações do usuário utilizando a classe Scanner em Java."
>

<title>
    Entrada de Dados com Scanner | Java, Café e Código
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= $versaoStyle ?>"
>


<!-- =====================================================
CSS DA AULA
====================================================== -->

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

        AULA 04

    </span>

    <h1>

        Entrada de Dados com Scanner

    </h1>

    <p>

        Aprenda como fazer um programa receber
        informações digitadas pelo usuário
        durante sua execução.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Tornando o programa interativo

    </h2>

    <p>

        Até agora, nossos programas conseguiram
        mostrar informações no console.

    </p>

    <p>

        Também aprendemos como guardar valores
        utilizando variáveis.

    </p>

    <pre class="codigo-java"><code>String nome = "Robert";

System.out.println("Olá, " + nome);</code></pre>

    <p>

        Porém, o nome ainda está escrito diretamente
        dentro do código.

    </p>

    <p>

        Para utilizar outro nome, seria necessário
        alterar o programa e executá-lo novamente.

    </p>

    <p>

        Uma solução melhor é permitir que o próprio
        usuário digite seu nome.

    </p>

    <div class="aula-destaque">

        <strong>

            Entrada de dados:

        </strong>

        <p>

            Entrada de dados é o processo de receber
            informações fornecidas pelo usuário
            durante a execução do programa.

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

        O que é Scanner?

    </h2>

    <p>

        <strong>Scanner</strong> é uma classe
        disponível no Java.

    </p>

    <p>

        Ela pode ser utilizada para ler informações
        digitadas pelo teclado.

    </p>

    <p>

        Com Scanner, podemos receber:

    </p>

    <ul>

        <li>nomes;</li>

        <li>cidades;</li>

        <li>números inteiros;</li>

        <li>números decimais;</li>

        <li>outras informações fornecidas pelo usuário.</li>

    </ul>

    <div class="aula-destaque">

        <strong>

            Pense assim:

        </strong>

        <p>

            Scanner funciona como um leitor que
            acompanha aquilo que o usuário digita
            no teclado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| IMPORTAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Importando a classe Scanner

    </h2>

    <p>

        Antes de utilizar Scanner, precisamos
        importar essa classe.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;</code></pre>

    <p>

        Essa linha normalmente é colocada no início
        do arquivo Java, antes da criação da classe.

    </p>

    <p>

        A instrução informa que utilizaremos a classe
        Scanner que está disponível no pacote
        <strong>java.util</strong>.

    </p>

    <div class="aula-destaque">

        <strong>

            Sobre o import:

        </strong>

        <p>

            O import permite utilizar em nosso programa
            uma classe localizada em outro pacote
            da linguagem Java.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| CRIAÇÃO DO SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando o Scanner

    </h2>

    <p>

        Depois da importação, precisamos criar
        um objeto Scanner.

    </p>

    <pre class="codigo-java"><code>Scanner entrada = new Scanner(System.in);</code></pre>

    <p>

        Vamos observar cada parte.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Função</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Scanner</span>
            <span>Tipo do objeto criado</span>
            <span>Scanner</span>

        </div>

        <div class="aula-tabela-linha">

            <span>entrada</span>
            <span>Nome escolhido para o Scanner</span>
            <span>entrada</span>

        </div>

        <div class="aula-tabela-linha">

            <span>new Scanner</span>
            <span>Cria um novo objeto Scanner</span>
            <span>new Scanner(...)</span>

        </div>

        <div class="aula-tabela-linha">

            <span>System.in</span>
            <span>Representa a entrada pelo teclado</span>
            <span>System.in</span>

        </div>

    </div>

    <p>

        O nome <strong>entrada</strong> foi escolhido
        para identificar o Scanner.

    </p>

    <p>

        Também poderíamos utilizar outro nome claro,
        como:

    </p>

    <pre class="codigo-java"><code>Scanner teclado = new Scanner(System.in);</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| PRIMEIRO PROGRAMA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Nosso primeiro programa com Scanner

    </h2>

    <p>

        Vamos criar um programa que solicita
        o nome do usuário.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada = new Scanner(System.in);

        System.out.println("Digite seu nome:");

        String nome = entrada.nextLine();

        System.out.println("Olá, " + nome + "!");

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite seu nome:
Robert
Olá, Robert!</code></div>

    <p>

        O programa primeiro mostra uma pergunta.

    </p>

    <p>

        Depois, espera o usuário digitar uma resposta.

    </p>

    <p>

        O valor digitado é armazenado na variável
        <strong>nome</strong>.

    </p>

    <pre class="codigo-java"><code>String nome = entrada.nextLine();</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| FLUXO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como a entrada de dados funciona?

    </h2>

    <p>

        Podemos representar o funcionamento
        do programa desta forma:

    </p>

    <div class="aula-destaque">

        <p>O programa mostra uma pergunta.</p>

        <p>↓</p>

        <p>O usuário digita uma informação.</p>

        <p>↓</p>

        <p>Scanner captura a informação.</p>

        <p>↓</p>

        <p>O valor é armazenado em uma variável.</p>

        <p>↓</p>

        <p>O programa utiliza o valor recebido.</p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| NEXTLINE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Lendo textos com nextLine()

    </h2>

    <p>

        O método <strong>nextLine()</strong>
        é utilizado para ler uma linha completa
        de texto.

    </p>

    <pre class="codigo-java"><code>String nome = entrada.nextLine();</code></pre>

    <p>

        Se o usuário digitar:

    </p>

    <div class="resultado-codigo"><code>Robert de Castro</code></div>

    <p>

        Todo o conteúdo digitado será armazenado
        na variável.

    </p>

    <p>

        Também podemos receber uma cidade:

    </p>

    <pre class="codigo-java"><code>System.out.println("Digite sua cidade:");

String cidade = entrada.nextLine();

System.out.println("Você mora em " + cidade);</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite sua cidade:
Rio Grande
Você mora em Rio Grande</code></div>

    <div class="aula-destaque">

        <strong>

            Utilize nextLine() para:

        </strong>

        <p>

            Nomes completos, cidades, endereços,
            mensagens e outros textos que possam
            possuir espaços.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| NEXT
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        next() e nextLine() são diferentes

    </h2>

    <p>

        Scanner também possui um método chamado
        <strong>next()</strong>.

    </p>

    <p>

        O método next() lê apenas até encontrar
        um espaço.

    </p>

    <pre class="codigo-java"><code>String nome = entrada.next();</code></pre>

    <p>

        Se o usuário digitar:

    </p>

    <div class="resultado-codigo"><code>Robert de Castro</code></div>

    <p>

        A variável poderá armazenar apenas:

    </p>

    <div class="resultado-codigo"><code>Robert</code></div>

    <p>

        Já nextLine() consegue ler a linha completa.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Método</span>
            <span>Leitura</span>
            <span>Exemplo recebido</span>

        </div>

        <div class="aula-tabela-linha">

            <span>next()</span>
            <span>Lê até encontrar um espaço</span>
            <span>Robert</span>

        </div>

        <div class="aula-tabela-linha">

            <span>nextLine()</span>
            <span>Lê a linha completa</span>
            <span>Robert de Castro</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| NEXTINT
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Lendo números inteiros com nextInt()

    </h2>

    <p>

        Para receber um número inteiro,
        utilizamos o método
        <strong>nextInt()</strong>.

    </p>

    <pre class="codigo-java"><code>System.out.println("Digite sua idade:");

int idade = entrada.nextInt();</code></pre>

    <p>

        O tipo da variável é int porque nextInt()
        realiza a leitura de um número inteiro.

    </p>

    <p>

        Programa completo:

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada = new Scanner(System.in);

        System.out.println("Digite sua idade:");

        int idade = entrada.nextInt();

        System.out.println(
            "Você tem " + idade + " anos."
        );

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite sua idade:
17
Você tem 17 anos.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| NEXTDOUBLE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Lendo números decimais com nextDouble()

    </h2>

    <p>

        Para receber um número decimal,
        podemos utilizar
        <strong>nextDouble()</strong>.

    </p>

    <pre class="codigo-java"><code>System.out.println("Digite sua altura:");

double altura = entrada.nextDouble();</code></pre>

    <p>

        Depois, podemos mostrar o valor recebido.

    </p>

    <pre class="codigo-java"><code>System.out.println("Altura: " + altura);</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite sua altura:
1.68
Altura: 1.68</code></div>

    <div class="aula-destaque">

        <strong>

            Separador decimal:

        </strong>

        <p>

            O separador aceito durante a digitação
            pode depender da configuração regional
            do ambiente onde o programa está sendo
            executado.

        </p>

        <p>

            Nos exemplos desta aula, utilizamos
            o ponto.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Principais métodos do Scanner

    </h2>

    <p>

        O método utilizado deve combinar com
        o tipo de informação que queremos receber.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Método</span>
            <span>Informação recebida</span>
            <span>Tipo da variável</span>

        </div>

        <div class="aula-tabela-linha">

            <span>nextLine()</span>
            <span>Linha completa de texto</span>
            <span>String</span>

        </div>

        <div class="aula-tabela-linha">

            <span>next()</span>
            <span>Texto até o primeiro espaço</span>
            <span>String</span>

        </div>

        <div class="aula-tabela-linha">

            <span>nextInt()</span>
            <span>Número inteiro</span>
            <span>int</span>

        </div>

        <div class="aula-tabela-linha">

            <span>nextDouble()</span>
            <span>Número decimal</span>
            <span>double</span>

        </div>

        <div class="aula-tabela-linha">

            <span>nextBoolean()</span>
            <span>true ou false</span>
            <span>boolean</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIAS ENTRADAS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Recebendo várias informações

    </h2>

    <p>

        Podemos utilizar o mesmo Scanner para
        receber diferentes informações.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada = new Scanner(System.in);

        System.out.println("Digite seu nome:");
        String nome = entrada.nextLine();

        System.out.println("Digite sua idade:");
        int idade = entrada.nextInt();

        System.out.println("Digite sua altura:");
        double altura = entrada.nextDouble();

        System.out.println();
        System.out.println("=== DADOS DO USUÁRIO ===");
        System.out.println("Nome: " + nome);
        System.out.println("Idade: " + idade);
        System.out.println("Altura: " + altura);

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
Digite sua altura:
1.68

=== DADOS DO USUÁRIO ===
Nome: Robert
Idade: 17
Altura: 1.68</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| BUFFER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Cuidado ao misturar nextInt() e nextLine()

    </h2>

    <p>

        Existe uma situação que costuma causar
        dúvidas para quem está começando.

    </p>

    <p>

        Observe o código:

    </p>

    <pre class="codigo-java"><code>System.out.println("Digite sua idade:");

int idade = entrada.nextInt();

System.out.println("Digite seu nome:");

String nome = entrada.nextLine();</code></pre>

    <p>

        Depois de o usuário digitar a idade e
        pressionar Enter, o nextInt() lê o número,
        mas pode deixar a quebra de linha pendente
        na entrada.

    </p>

    <p>

        Quando nextLine() for executado, ele poderá
        consumir essa quebra de linha sem esperar
        pela digitação do nome.

    </p>

    <div class="aula-destaque">

        <strong>

            O que fica pendente?

        </strong>

        <p>

            O usuário digita o número e pressiona
            Enter. O número é lido, mas a quebra
            de linha produzida pelo Enter pode
            permanecer aguardando leitura.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SOLUÇÃO DO BUFFER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Limpando a quebra de linha pendente

    </h2>

    <p>

        Uma forma comum de resolver o problema
        é executar um nextLine() adicional.

    </p>

    <pre class="codigo-java"><code>System.out.println("Digite sua idade:");

int idade = entrada.nextInt();

entrada.nextLine();

System.out.println("Digite seu nome:");

String nome = entrada.nextLine();</code></pre>

    <p>

        O primeiro nextLine() consome a quebra
        de linha pendente.

    </p>

    <pre class="codigo-java"><code>entrada.nextLine();</code></pre>

    <p>

        O segundo nextLine() realiza a leitura
        do nome digitado pelo usuário.

    </p>

    <pre class="codigo-java"><code>String nome = entrada.nextLine();</code></pre>

    <div class="aula-destaque">

        <strong>

            Regra prática:

        </strong>

        <p>

            Quando utilizar um método numérico
            e depois precisar ler uma linha de texto,
            lembre-se de consumir a quebra de linha
            antes da leitura do texto.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO CORRETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo com número e texto

    </h2>

    <p>

        Vamos criar um programa que primeiro recebe
        a idade e depois o nome do usuário.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada = new Scanner(System.in);

        System.out.println("Digite sua idade:");
        int idade = entrada.nextInt();

        entrada.nextLine();

        System.out.println("Digite seu nome:");
        String nome = entrada.nextLine();

        System.out.println();
        System.out.println("Nome: " + nome);
        System.out.println("Idade: " + idade);

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite sua idade:
17
Digite seu nome:
Robert de Castro

Nome: Robert de Castro
Idade: 17</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| FECHANDO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Fechando o Scanner

    </h2>

    <p>

        Quando terminamos de utilizar o Scanner,
        podemos fechá-lo com o método
        <strong>close()</strong>.

    </p>

    <pre class="codigo-java"><code>entrada.close();</code></pre>

    <p>

        Essa instrução normalmente é colocada
        depois da última leitura.

    </p>

    <pre class="codigo-java"><code>Scanner entrada = new Scanner(System.in);

System.out.println("Digite seu nome:");

String nome = entrada.nextLine();

System.out.println("Olá, " + nome + "!");

entrada.close();</code></pre>

    <div class="aula-destaque">

        <strong>

            Importante:

        </strong>

        <p>

            Fechar o Scanner também fecha a entrada
            associada a ele.

        </p>

        <p>

            Por isso, coloque close() somente quando
            o programa não precisar mais receber
            informações pelo teclado.

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

        Esquecer de importar Scanner

    </h3>

    <p>

        Este código não funcionará corretamente
        sem a importação da classe:

    </p>

    <pre class="codigo-java"><code>Scanner entrada = new Scanner(System.in);</code></pre>

    <p>

        Adicione no início do arquivo:

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;</code></pre>

    <h3>

        Digitar texto quando o programa espera um número

    </h3>

    <p>

        Se o programa utilizar nextInt():

    </p>

    <pre class="codigo-java"><code>int idade = entrada.nextInt();</code></pre>

    <p>

        O usuário deverá fornecer um número inteiro.

    </p>

    <p>

        Digitar uma palavra nesse momento provocará
        um erro de entrada.

    </p>

    <h3>

        Utilizar um tipo incompatível

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>String idade = entrada.nextInt();</code></pre>

    <p>

        nextInt() produz um valor inteiro,
        portanto a variável deve ser do tipo int.

    </p>

    <pre class="codigo-java"><code>int idade = entrada.nextInt();</code></pre>

    <h3>

        Esquecer o problema da quebra de linha

    </h3>

    <p>

        Ao utilizar nextInt() antes de nextLine(),
        lembre-se da leitura adicional:

    </p>

    <pre class="codigo-java"><code>int idade = entrada.nextInt();

entrada.nextLine();

String nome = entrada.nextLine();</code></pre>

    <h3>

        Fechar o Scanner cedo demais

    </h3>

    <p>

        Depois de executar:

    </p>

    <pre class="codigo-java"><code>entrada.close();</code></pre>

    <p>

        O programa não deverá tentar realizar
        novas leituras utilizando o mesmo Scanner.

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

        Vamos criar um programa que solicita
        o nome de uma linguagem de programação.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada = new Scanner(System.in);

        System.out.println(
            "Digite uma linguagem de programação:"
        );

        String linguagem = entrada.nextLine();

        System.out.println(
            "Você escolheu: " + linguagem
        );

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite uma linguagem de programação:
Java
Você escolheu: Java</code></div>

    <div class="aula-destaque">

        <strong>

            Experimente:

        </strong>

        <p>

            Execute o programa novamente e digite
            uma linguagem diferente.

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

        Crie um programa de cadastro de um jogo.

    </p>

    <p>

        O programa deverá solicitar:

    </p>

    <ul>

        <li>nome do jogo;</li>

        <li>plataforma;</li>

        <li>ano de lançamento;</li>

        <li>preço.</li>

    </ul>

    <p>

        Depois, apresente os dados no console.

    </p>

    <div class="resultado-codigo"><code>Digite o nome do jogo:
Minecraft
Digite a plataforma:
Computador
Digite o ano de lançamento:
2011
Digite o preço:
99.90

=== JOGO CADASTRADO ===
Nome: Minecraft
Plataforma: Computador
Ano: 2011
Preço: 99.9</code></div>

    <div class="aula-destaque">

        <strong>

            Métodos necessários:

        </strong>

        <p>

            Utilize nextLine() para os textos,
            nextInt() para o ano e nextDouble()
            para o preço.

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

        Nesta aula, aprendemos como receber
        informações digitadas pelo usuário.

    </p>

    <ul>

        <li>

            Scanner permite ler informações
            fornecidas pelo teclado;

        </li>

        <li>

            utilizamos
            <strong>import java.util.Scanner;</strong>
            para importar a classe;

        </li>

        <li>

            criamos o Scanner utilizando
            <strong>new Scanner(System.in)</strong>;

        </li>

        <li>

            nextLine() lê uma linha de texto;

        </li>

        <li>

            next() lê até encontrar um espaço;

        </li>

        <li>

            nextInt() lê números inteiros;

        </li>

        <li>

            nextDouble() lê números decimais;

        </li>

        <li>

            podemos armazenar os dados recebidos
            em variáveis;

        </li>

        <li>

            devemos ter cuidado ao misturar
            métodos numéricos com nextLine();

        </li>

        <li>

            close() fecha o Scanner quando
            não precisamos mais utilizá-lo.

        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Scanner permite que o programa
                receba informações e interaja
                diretamente com o usuário.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como realizar
        cálculos utilizando os operadores matemáticos
        do Java.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Um programa se torna mais interessante
        quando começa a ouvir e responder ao usuário."

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

        Próxima aula: Operadores →

    </a>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../componentes/footer.php";

?>


<!-- =====================================================
TEMA
====================================================== -->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


</body>

</html>