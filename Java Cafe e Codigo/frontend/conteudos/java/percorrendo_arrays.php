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
                "arrays"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "arraylist"
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
    content="Aprenda como percorrer arrays em Java utilizando for, for-each e a propriedade length."
>

<title>
    Percorrendo Arrays em Java | Java, Café e Código
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

        AULA 19

    </span>

    <h1>

        Percorrendo Arrays em Java

    </h1>

    <p>

        Aprenda como acessar automaticamente todos
        os elementos de um array utilizando estruturas
        de repetição.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como acessar todos os valores?

    </h2>

    <p>

        Na aula anterior, aprendemos que um array
        permite armazenar vários valores do mesmo tipo.

    </p>

    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria",
    "João"
};</code></pre>

    <p>

        Também aprendemos a acessar cada posição
        utilizando seu índice.

    </p>

    <pre class="codigo-java"><code>System.out.println(nomes[0]);
System.out.println(nomes[1]);
System.out.println(nomes[2]);
System.out.println(nomes[3]);</code></pre>

    <p>

        Isso funciona, mas pode se tornar trabalhoso
        quando o array possui muitos elementos.

    </p>

    <p>

        Imagine um array com cem valores. Não seria
        interessante escrever cem comandos diferentes.

    </p>

    <div class="aula-destaque">

        <strong>

            Solução:

        </strong>

        <p>

            Podemos utilizar estruturas de repetição
            para percorrer automaticamente todas as
            posições de um array.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE SIGNIFICA PERCORRER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que significa percorrer um array?

    </h2>

    <p>

        Percorrer um array significa visitar suas
        posições uma por uma.

    </p>

    <p>

        Em cada repetição, o programa acessa um índice
        diferente.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Repetição</span>
            <span>Índice</span>
            <span>Valor</span>

        </div>

        <div class="aula-tabela-linha">

            <span>1ª</span>
            <span>0</span>
            <span>Ana</span>

        </div>

        <div class="aula-tabela-linha">

            <span>2ª</span>
            <span>1</span>
            <span>Carlos</span>

        </div>

        <div class="aula-tabela-linha">

            <span>3ª</span>
            <span>2</span>
            <span>Maria</span>

        </div>

        <div class="aula-tabela-linha">

            <span>4ª</span>
            <span>3</span>
            <span>João</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| FOR COM ARRAY
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Percorrendo um array com for

    </h2>

    <p>

        Podemos utilizar um contador para representar
        o índice que será acessado.

    </p>

    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria",
    "João"
};

for (int i = 0; i &lt; nomes.length; i++) {

    System.out.println(nomes[i]);

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Ana
Carlos
Maria
João</code></div>

    <p>

        A variável <strong>i</strong> começa com zero,
        que é o primeiro índice do array.

    </p>

    <p>

        Em cada repetição, o valor de i aumenta
        em uma unidade.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ENTENDENDO O FOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Entendendo a estrutura

    </h2>

    <pre class="codigo-java"><code>for (int i = 0; i &lt; nomes.length; i++) {

    System.out.println(nomes[i]);

}</code></pre>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Código</span>
            <span>Função</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Inicialização</span>
            <span>int i = 0</span>
            <span>Começa no primeiro índice</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Condição</span>
            <span>i &lt; nomes.length</span>
            <span>Impede o acesso fora do array</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Atualização</span>
            <span>i++</span>
            <span>Avança para o próximo índice</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Acesso</span>
            <span>nomes[i]</span>
            <span>Obtém o elemento da posição atual</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| LENGTH
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando length como limite

    </h2>

    <p>

        A propriedade <strong>length</strong> informa
        a quantidade total de elementos do array.

    </p>

    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria"
};

System.out.println(nomes.length);</code></pre>

    <div class="resultado-codigo"><code>3</code></div>

    <p>

        Como os índices começam em zero, os índices
        desse array são:

    </p>

    <div class="resultado-codigo"><code>0
1
2</code></div>

    <p>

        Por isso, utilizamos:

    </p>

    <pre class="codigo-java"><code>i &lt; nomes.length</code></pre>

    <p>

        Quando i chegar ao mesmo valor de length,
        a repetição será encerrada.

    </p>

    <div class="aula-destaque">

        <strong>

            Regra importante:

        </strong>

        <p>

            O último índice de um array sempre será
            <strong>length - 1</strong>.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MOSTRANDO ÍNDICE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Mostrando o índice e o valor

    </h2>

    <p>

        Como o for trabalha diretamente com o índice,
        podemos mostrar a posição junto com seu valor.

    </p>

    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria"
};

for (int i = 0; i &lt; nomes.length; i++) {

    System.out.println(
        "Índice " + i + ": " + nomes[i]
    );

}</code></pre>

    <div class="resultado-codigo"><code>Índice 0: Ana
Índice 1: Carlos
Índice 2: Maria</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| ALTERANDO ELEMENTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Alterando elementos durante a repetição

    </h2>

    <p>

        O índice também pode ser utilizado para
        modificar os elementos.

    </p>

    <pre class="codigo-java"><code>int[] numeros = {
    1,
    2,
    3,
    4
};

for (int i = 0; i &lt; numeros.length; i++) {

    numeros[i] = numeros[i] * 2;

}</code></pre>

    <p>

        Depois da repetição, os valores serão:

    </p>

    <div class="resultado-codigo"><code>2
4
6
8</code></div>

    <p>

        Cada elemento foi multiplicado por dois.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FOR-EACH
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Percorrendo com for-each

    </h2>

    <p>

        Java possui outra forma de percorrer arrays:
        o <strong>for-each</strong>.

    </p>

    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria"
};

for (String nome : nomes) {

    System.out.println(nome);

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Ana
Carlos
Maria</code></div>

    <p>

        Em cada repetição, a variável
        <strong>nome</strong> recebe um elemento
        do array.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ESTRUTURA FOR-EACH
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Estrutura do for-each

    </h2>

    <pre class="codigo-java"><code>for (Tipo elemento : array) {

    // Utilização do elemento

}</code></pre>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Parte</span>
            <span>Exemplo</span>
            <span>Função</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Tipo</span>
            <span>String</span>
            <span>Tipo dos valores armazenados</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Elemento</span>
            <span>nome</span>
            <span>Recebe o valor atual</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Array</span>
            <span>nomes</span>
            <span>Estrutura que será percorrida</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| FOR X FOR-EACH
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        For tradicional ou for-each?

    </h2>

    <p>

        As duas formas percorrem os elementos,
        mas possuem diferenças.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Característica</span>
            <span>for</span>
            <span>for-each</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Acesso ao índice</span>
            <span>Sim</span>
            <span>Não diretamente</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Alteração pela posição</span>
            <span>Sim</span>
            <span>Não diretamente</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Simplicidade</span>
            <span>Mais controle</span>
            <span>Mais simples</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Melhor uso</span>
            <span>Quando precisamos do índice</span>
            <span>Quando precisamos apenas dos valores</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SOMANDO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Somando os valores de um array

    </h2>

    <p>

        Podemos utilizar uma variável acumuladora
        para somar todos os elementos.

    </p>

    <pre class="codigo-java"><code>int[] numeros = {
    10,
    20,
    30,
    40
};

int soma = 0;

for (int numero : numeros) {

    soma += numero;

}

System.out.println(
    "Soma: " + soma
);</code></pre>

    <div class="resultado-codigo"><code>Soma: 100</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| MÉDIA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Calculando uma média

    </h2>

    <pre class="codigo-java"><code>double[] notas = {
    8.0,
    7.5,
    9.0,
    6.5
};

double soma = 0;

for (double nota : notas) {

    soma += nota;

}

double media =
    soma / notas.length;

System.out.println(
    "Média: " + media
);</code></pre>

    <div class="resultado-codigo"><code>Média: 7.75</code></div>

    <p>

        Primeiro somamos todas as notas.

    </p>

    <p>

        Depois dividimos o resultado pela quantidade
        de elementos do array.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MAIOR VALOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Encontrando o maior valor

    </h2>

    <pre class="codigo-java"><code>int[] numeros = {
    15,
    8,
    40,
    22,
    11
};

int maior = numeros[0];

for (int numero : numeros) {

    if (numero &gt; maior) {

        maior = numero;

    }

}

System.out.println(
    "Maior valor: " + maior
);</code></pre>

    <div class="resultado-codigo"><code>Maior valor: 40</code></div>

    <p>

        Começamos considerando o primeiro elemento
        como o maior.

    </p>

    <p>

        Depois comparamos cada valor com a variável
        <strong>maior</strong>.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MENOR VALOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Encontrando o menor valor

    </h2>

    <pre class="codigo-java"><code>int[] numeros = {
    15,
    8,
    40,
    22,
    11
};

int menor = numeros[0];

for (int numero : numeros) {

    if (numero &lt; menor) {

        menor = numero;

    }

}

System.out.println(
    "Menor valor: " + menor
);</code></pre>

    <div class="resultado-codigo"><code>Menor valor: 8</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| PROCURANDO ELEMENTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Procurando um elemento

    </h2>

    <p>

        Podemos percorrer o array e comparar cada valor.

    </p>

    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria",
    "João"
};

String procurado = "Maria";

boolean encontrado = false;

for (String nome : nomes) {

    if (nome.equals(procurado)) {

        encontrado = true;

        break;

    }

}

System.out.println(
    "Encontrado: " + encontrado
);</code></pre>

    <div class="resultado-codigo"><code>Encontrado: true</code></div>

    <p>

        O comando <strong>break</strong> encerra
        a repetição depois que o elemento é encontrado.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PERCORRENDO AO CONTRÁRIO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Percorrendo o array ao contrário

    </h2>

    <p>

        Para começar pelo último elemento,
        utilizamos o último índice.

    </p>

    <pre class="codigo-java"><code>String[] nomes = {
    "Ana",
    "Carlos",
    "Maria",
    "João"
};

for (
    int i = nomes.length - 1;
    i &gt;= 0;
    i--
) {

    System.out.println(nomes[i]);

}</code></pre>

    <div class="resultado-codigo"><code>João
Maria
Carlos
Ana</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| SCANNER
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Preenchendo um array com Scanner

    </h2>

    <p>

        Podemos utilizar Scanner para preencher
        cada posição.

    </p>

    <pre class="codigo-java"><code>import java.util.Scanner;

public class Main {

    public static void main(String[] args) {

        Scanner entrada =
            new Scanner(System.in);

        String[] nomes =
            new String[3];

        for (
            int i = 0;
            i &lt; nomes.length;
            i++
        ) {

            System.out.println(
                "Digite o nome da posição "
                + i
                + ":"
            );

            nomes[i] =
                entrada.nextLine();

        }

        System.out.println();
        System.out.println(
            "Nomes cadastrados:"
        );

        for (String nome : nomes) {

            System.out.println(nome);

        }

        entrada.close();

    }

}</code></pre>

    <p>

        Uma possível execução seria:

    </p>

    <div class="resultado-codigo"><code>Digite o nome da posição 0:
Robert
Digite o nome da posição 1:
Ana
Digite o nome da posição 2:
Carlos

Nomes cadastrados:
Robert
Ana
Carlos</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO PRÁTICO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: notas do estudante

    </h2>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        double[] notas = {
            8.5,
            7.0,
            9.0,
            6.5
        };

        double soma = 0;

        System.out.println(
            "Notas cadastradas:"
        );

        for (
            int i = 0;
            i &lt; notas.length;
            i++
        ) {

            System.out.println(
                "Nota "
                + (i + 1)
                + ": "
                + notas[i]
            );

            soma += notas[i];

        }

        double media =
            soma / notas.length;

        System.out.println();
        System.out.println(
            "Média: " + media
        );

        if (media &gt;= 7) {

            System.out.println(
                "Situação: aprovado."
            );

        } else {

            System.out.println(
                "Situação: reprovado."
            );

        }

    }

}</code></pre>

    <div class="resultado-codigo"><code>Notas cadastradas:
Nota 1: 8.5
Nota 2: 7.0
Nota 3: 9.0
Nota 4: 6.5

Média: 7.75
Situação: aprovado.</code></div>

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

        Utilizar &lt;= com length

    </h3>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>for (int i = 0; i &lt;= nomes.length; i++) {

    System.out.println(nomes[i]);

}</code></pre>

    <p>

        Quando i for igual a nomes.length,
        o programa tentará acessar uma posição
        que não existe.

    </p>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>for (int i = 0; i &lt; nomes.length; i++) {

    System.out.println(nomes[i]);

}</code></pre>

    <h3>

        Começar pelo índice 1

    </h3>

    <p>

        Quando começamos em 1, o primeiro elemento
        do array não será processado.

    </p>

    <pre class="codigo-java"><code>for (int i = 1; i &lt; nomes.length; i++) {

}</code></pre>

    <p>

        Normalmente, começamos em zero.

    </p>

    <h3>

        Utilizar o índice fora da repetição

    </h3>

    <p>

        Quando a variável i é declarada dentro do for,
        ela pertence ao escopo da estrutura.

    </p>

    <h3>

        Confundir length com size()

    </h3>

    <p>

        Arrays utilizam:

    </p>

    <pre class="codigo-java"><code>nomes.length</code></pre>

    <p>

        ArrayList utiliza:

    </p>

    <pre class="codigo-java"><code>nomes.size()</code></pre>

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

        Conte quantos números pares existem no array.

    </p>

    <pre class="codigo-java"><code>int[] numeros = {
    2,
    7,
    10,
    13,
    20,
    25
};

int quantidadePares = 0;

for (int numero : numeros) {

    if (numero % 2 == 0) {

        quantidadePares++;

    }

}

System.out.println(
    "Quantidade de pares: "
    + quantidadePares
);</code></pre>

    <div class="resultado-codigo"><code>Quantidade de pares: 3</code></div>

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

        Crie um array com cinco notas.

    </p>

    <p>

        Depois:

    </p>

    <ul>

        <li>
            mostre todas as notas;
        </li>

        <li>
            calcule a média;
        </li>

        <li>
            encontre a maior nota;
        </li>

        <li>
            encontre a menor nota;
        </li>

        <li>
            conte quantas notas são maiores ou iguais a 7.
        </li>

    </ul>

    <div class="resultado-codigo"><code>Média: 7.8
Maior nota: 10.0
Menor nota: 5.5
Notas acima da média mínima: 4</code></div>

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

        Nesta aula, aprendemos como percorrer arrays
        utilizando estruturas de repetição.

    </p>

    <ul>

        <li>
            percorrer significa acessar os elementos
            um por um;
        </li>

        <li>
            podemos percorrer arrays utilizando for;
        </li>

        <li>
            o contador representa o índice atual;
        </li>

        <li>
            normalmente começamos pelo índice zero;
        </li>

        <li>
            utilizamos i &lt; array.length como condição;
        </li>

        <li>
            podemos utilizar for-each quando não
            precisamos trabalhar com índices;
        </li>

        <li>
            arrays utilizam a propriedade length;
        </li>

        <li>
            podemos somar e calcular médias;
        </li>

        <li>
            podemos encontrar valores maiores e menores;
        </li>

        <li>
            podemos procurar elementos;
        </li>

        <li>
            também podemos preencher arrays com Scanner.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                Estruturas de repetição permitem
                trabalhar automaticamente com todos
                os elementos de um array.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos sobre ArrayList,
        uma estrutura de lista cujo tamanho pode
        aumentar ou diminuir.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Guardar informações é importante, mas saber
        percorrê-las e utilizá-las é o que realmente
        torna os dados úteis."

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

        Próxima aula: ArrayList →

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