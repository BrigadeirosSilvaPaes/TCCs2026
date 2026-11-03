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
                "percorrendo_arrays"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "classes_objetos"
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
    content="Aprenda como utilizar ArrayList em Java para criar listas dinâmicas e adicionar, acessar, alterar e remover elementos."
>

<title>
    ArrayList em Java | Java, Café e Código
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

        AULA 20

    </span>

    <h1>

        ArrayList em Java

    </h1>

    <p>

        Aprenda a utilizar o ArrayList para armazenar
        e organizar vários valores em uma lista que
        pode aumentar ou diminuir durante a execução
        do programa.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| O QUE É ARRAYLIST?
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é um ArrayList?

    </h2>

    <p>

        Até agora, aprendemos que um
        <strong>array</strong> permite armazenar
        vários valores dentro de uma única estrutura.

    </p>

    <p>

        Porém, os arrays possuem uma característica
        importante: seu tamanho é definido no momento
        em que são criados e não pode ser alterado
        posteriormente.

    </p>

    <p>

        Imagine que você criou um array capaz de
        armazenar cinco nomes. Caso seja necessário
        guardar um sexto nome, não será possível
        simplesmente aumentar o tamanho daquele array.

    </p>

    <div class="aula-destaque">

        <strong>

            É aqui que entra o ArrayList!

        </strong>

        <p>

            O <strong>ArrayList</strong> é uma estrutura
            utilizada para armazenar vários elementos
            em uma lista de tamanho dinâmico.

        </p>

        <p>

            Isso significa que podemos adicionar ou
            remover elementos conforme as necessidades
            do programa.

        </p>

    </div>

    <p>

        Podemos imaginar o ArrayList como uma lista
        que cresce quando adicionamos informações
        e diminui quando removemos elementos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ARRAY X ARRAYLIST
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Array ou ArrayList?

    </h2>

    <p>

        As duas estruturas podem armazenar vários
        valores, mas possuem diferenças importantes.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Característica</span>
            <span>Array</span>
            <span>ArrayList</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Tamanho</span>
            <span>Fixo</span>
            <span>Dinâmico</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Adicionar elementos</span>
            <span>Por posições existentes</span>
            <span>Com add()</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Remover elementos</span>
            <span>Não possui método direto</span>
            <span>Com remove()</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Consultar tamanho</span>
            <span>length</span>
            <span>size()</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Utilização</span>
            <span>Quantidade fixa de valores</span>
            <span>Quantidade variável de valores</span>

        </div>

    </div>

    <p>

        O ArrayList é útil quando não sabemos
        antecipadamente quantos elementos o programa
        precisará armazenar.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| IMPORTANDO ARRAYLIST
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Importando o ArrayList

    </h2>

    <p>

        Para utilizar um ArrayList em Java, precisamos
        importar a classe <strong>ArrayList</strong>.

    </p>

    <pre class="codigo-java"><code>import java.util.ArrayList;</code></pre>

    <p>

        Essa linha informa ao Java que queremos
        utilizar a classe ArrayList, que está
        disponível no pacote <strong>java.util</strong>.

    </p>

    <p>

        A importação deve aparecer antes da declaração
        da classe principal.

    </p>

    <pre class="codigo-java"><code>import java.util.ArrayList;

public class Main {

    public static void main(String[] args) {

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| CRIANDO ARRAYLIST
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando um ArrayList

    </h2>

    <p>

        A estrutura básica para criar um ArrayList é:

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;Tipo&gt; nomeDaLista = new ArrayList&lt;&gt;();</code></pre>

    <p>

        Observe cada parte dessa estrutura:

    </p>

    <ul>

        <li>

            <strong>ArrayList:</strong>
            representa o tipo da lista;

        </li>

        <li>

            <strong>&lt;Tipo&gt;:</strong>
            define o tipo de elemento armazenado;

        </li>

        <li>

            <strong>nomeDaLista:</strong>
            é o nome da variável;

        </li>

        <li>

            <strong>new ArrayList&lt;&gt;():</strong>
            cria uma nova lista vazia.

        </li>

    </ul>

    <p>

        Para criar uma lista de textos:

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes = new ArrayList&lt;&gt;();</code></pre>

    <p>

        Para criar uma lista de números inteiros:

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;Integer&gt; numeros = new ArrayList&lt;&gt;();</code></pre>

    <p>

        Para criar uma lista de números decimais:

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;Double&gt; precos = new ArrayList&lt;&gt;();</code></pre>

    <div class="aula-destaque">

        <strong>

            Atenção aos tipos!

        </strong>

        <p>

            Dentro dos sinais
            <strong>&lt; &gt;</strong>, utilizamos
            classes como <strong>Integer</strong>,
            <strong>Double</strong>,
            <strong>Character</strong> e
            <strong>Boolean</strong>.

        </p>

        <p>

            Não utilizamos diretamente os tipos
            primitivos <strong>int</strong>,
            <strong>double</strong>,
            <strong>char</strong> e
            <strong>boolean</strong>.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ADICIONANDO ELEMENTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Adicionando elementos com add()

    </h2>

    <p>

        Para adicionar um elemento ao ArrayList,
        utilizamos o método <strong>add()</strong>.

    </p>

    <pre class="codigo-java"><code>import java.util.ArrayList;

public class Main {

    public static void main(String[] args) {

        ArrayList&lt;String&gt; nomes =
            new ArrayList&lt;&gt;();

        nomes.add("Robert");
        nomes.add("Ana");
        nomes.add("Carlos");

        System.out.println(nomes);

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>[Robert, Ana, Carlos]</code></div>

    <p>

        Cada chamada ao método
        <strong>add()</strong> adiciona um novo
        elemento ao final da lista.

    </p>

    <p>

        Os elementos ocupam os seguintes índices:

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Índice</span>
            <span>Elemento</span>
            <span>Posição</span>

        </div>

        <div class="aula-tabela-linha">

            <span>0</span>
            <span>Robert</span>
            <span>Primeira</span>

        </div>

        <div class="aula-tabela-linha">

            <span>1</span>
            <span>Ana</span>
            <span>Segunda</span>

        </div>

        <div class="aula-tabela-linha">

            <span>2</span>
            <span>Carlos</span>
            <span>Terceira</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ADICIONANDO EM UMA POSIÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Adicionando em uma posição específica

    </h2>

    <p>

        Também podemos informar o índice em que
        o novo elemento será adicionado.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.add("Robert");
nomes.add("Carlos");

nomes.add(1, "Ana");

System.out.println(nomes);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>[Robert, Ana, Carlos]</code></div>

    <p>

        O valor <strong>"Ana"</strong> foi inserido
        no índice 1.

    </p>

    <p>

        O elemento que estava naquela posição
        foi deslocado para o próximo índice.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| ACESSANDO ELEMENTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Acessando elementos com get()

    </h2>

    <p>

        Para acessar um elemento específico,
        utilizamos o método <strong>get()</strong>.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.add("Robert");
nomes.add("Ana");
nomes.add("Carlos");

System.out.println(nomes.get(0));
System.out.println(nomes.get(1));
System.out.println(nomes.get(2));</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Robert
Ana
Carlos</code></div>

    <p>

        O número informado dentro dos parênteses
        representa o índice do elemento.

    </p>

    <div class="aula-destaque">

        <strong>

            Lembre-se da posição zero!

        </strong>

        <p>

            Assim como acontece com arrays,
            o primeiro elemento de um ArrayList
            possui o índice <strong>0</strong>.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ALTERANDO ELEMENTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Alterando elementos com set()

    </h2>

    <p>

        Podemos alterar um elemento existente
        utilizando o método <strong>set()</strong>.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.add("Robert");
nomes.add("Ana");
nomes.add("Carlos");

nomes.set(1, "Maria");

System.out.println(nomes);</code></pre>

    <p>

        Antes da alteração:

    </p>

    <div class="resultado-codigo"><code>[Robert, Ana, Carlos]</code></div>

    <p>

        Depois da alteração:

    </p>

    <div class="resultado-codigo"><code>[Robert, Maria, Carlos]</code></div>

    <p>

        No comando:

    </p>

    <pre class="codigo-java"><code>nomes.set(1, "Maria");</code></pre>

    <ul>

        <li>

            <strong>1</strong> representa o índice
            que será alterado;

        </li>

        <li>

            <strong>"Maria"</strong> representa
            o novo valor.

        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| REMOVENDO ELEMENTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Removendo elementos com remove()

    </h2>

    <p>

        Para remover um elemento, utilizamos
        o método <strong>remove()</strong>.

    </p>

    <p>

        Podemos remover um elemento pelo índice:

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.add("Robert");
nomes.add("Ana");
nomes.add("Carlos");

nomes.remove(1);

System.out.println(nomes);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>[Robert, Carlos]</code></div>

    <p>

        O elemento do índice 1 foi removido.

    </p>

    <p>

        Também podemos remover um elemento
        informando seu valor:

    </p>

    <pre class="codigo-java"><code>nomes.remove("Robert");</code></pre>

    <p>

        Nesse caso, o Java procura pelo elemento
        <strong>"Robert"</strong> e remove sua
        primeira ocorrência.

    </p>

    <div class="aula-destaque">

        <strong>

            Atenção com listas de Integer

        </strong>

        <p>

            Em uma lista de números inteiros,
            <strong>remove(1)</strong> normalmente
            representa a remoção do elemento que
            está no índice 1.

        </p>

        <p>

            Para remover o valor 1, podemos utilizar:

        </p>

        <pre class="codigo-java"><code>numeros.remove(Integer.valueOf(1));</code></pre>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| TAMANHO DO ARRAYLIST
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Descobrindo o tamanho com size()

    </h2>

    <p>

        Para descobrir quantos elementos existem
        atualmente no ArrayList, utilizamos
        o método <strong>size()</strong>.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.add("Robert");
nomes.add("Ana");
nomes.add("Carlos");

System.out.println(nomes.size());</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>3</code></div>

    <p>

        O método size() informa a quantidade
        atual de elementos armazenados na lista.

    </p>

    <div class="aula-destaque">

        <strong>

            Não confunda:

        </strong>

        <p>

            Arrays utilizam:

        </p>

        <pre class="codigo-java"><code>nomes.length</code></pre>

        <p>

            ArrayLists utilizam:

        </p>

        <pre class="codigo-java"><code>nomes.size()</code></pre>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| VERIFICANDO SE ESTÁ VAZIO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Verificando se a lista está vazia

    </h2>

    <p>

        O método <strong>isEmpty()</strong> verifica
        se o ArrayList não possui elementos.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

System.out.println(nomes.isEmpty());</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true</code></div>

    <p>

        Depois de adicionar um elemento:

    </p>

    <pre class="codigo-java"><code>nomes.add("Robert");

System.out.println(nomes.isEmpty());</code></pre>

    <div class="resultado-codigo"><code>false</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| VERIFICANDO ELEMENTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Verificando se um elemento existe

    </h2>

    <p>

        Podemos verificar se determinado valor
        está presente no ArrayList utilizando
        o método <strong>contains()</strong>.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.add("Robert");
nomes.add("Ana");
nomes.add("Carlos");

System.out.println(
    nomes.contains("Robert")
);

System.out.println(
    nomes.contains("João")
);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>true
false</code></div>

    <p>

        O método contains() retorna:

    </p>

    <ul>

        <li>

            <strong>true</strong> quando o elemento
            está presente;

        </li>

        <li>

            <strong>false</strong> quando o elemento
            não está presente.

        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| DESCOBRINDO O ÍNDICE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Descobrindo o índice com indexOf()

    </h2>

    <p>

        O método <strong>indexOf()</strong> informa
        o índice de um elemento.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.add("Robert");
nomes.add("Ana");
nomes.add("Carlos");

System.out.println(
    nomes.indexOf("Ana")
);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>1</code></div>

    <p>

        Quando o elemento não existe na lista,
        indexOf() retorna <strong>-1</strong>.

    </p>

    <pre class="codigo-java"><code>System.out.println(
    nomes.indexOf("João")
);</code></pre>

    <div class="resultado-codigo"><code>-1</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| PERCORRENDO COM FOR
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Percorrendo um ArrayList com for

    </h2>

    <p>

        Podemos utilizar um laço
        <strong>for</strong> para percorrer
        todos os elementos da lista.

    </p>

    <pre class="codigo-java"><code>import java.util.ArrayList;

public class Main {

    public static void main(String[] args) {

        ArrayList&lt;String&gt; nomes =
            new ArrayList&lt;&gt;();

        nomes.add("Robert");
        nomes.add("Ana");
        nomes.add("Carlos");

        for (
            int i = 0;
            i &lt; nomes.size();
            i++
        ) {

            System.out.println(
                nomes.get(i)
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Robert
Ana
Carlos</code></div>

    <p>

        Utilizamos:

    </p>

    <ul>

        <li>

            <strong>size()</strong> para descobrir
            o tamanho da lista;

        </li>

        <li>

            <strong>get(i)</strong> para acessar
            o elemento do índice atual.

        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| PERCORRENDO COM FOR-EACH
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Percorrendo com for-each

    </h2>

    <p>

        Outra maneira de percorrer um ArrayList
        é utilizando o <strong>for-each</strong>.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.add("Robert");
nomes.add("Ana");
nomes.add("Carlos");

for (String nome : nomes) {

    System.out.println(nome);

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Robert
Ana
Carlos</code></div>

    <p>

        Em cada repetição, a variável
        <strong>nome</strong> recebe um elemento
        da lista.

    </p>

    <div class="aula-destaque">

        <strong>

            Quando utilizar?

        </strong>

        <p>

            Utilize o for tradicional quando
            precisar trabalhar com os índices.

        </p>

        <p>

            Utilize o for-each quando precisar
            apenas acessar os elementos.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| ARRAYLIST COM NÚMEROS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        ArrayList com números

    </h2>

    <p>

        O ArrayList também pode armazenar números.

    </p>

    <p>

        Para números inteiros, utilizamos
        <strong>Integer</strong>.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;Integer&gt; numeros =
    new ArrayList&lt;&gt;();

numeros.add(10);
numeros.add(20);
numeros.add(30);

for (Integer numero : numeros) {

    System.out.println(numero);

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>10
20
30</code></div>

    <p>

        Também podemos realizar cálculos com os valores.

    </p>

    <pre class="codigo-java"><code>int soma = 0;

for (Integer numero : numeros) {

    soma += numero;

}

System.out.println(
    "Soma: " + soma
);</code></pre>

    <div class="resultado-codigo"><code>Soma: 60</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| LIMPANDO A LISTA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Removendo todos os elementos

    </h2>

    <p>

        O método <strong>clear()</strong> remove
        todos os elementos do ArrayList.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.add("Robert");
nomes.add("Ana");
nomes.add("Carlos");

nomes.clear();

System.out.println(nomes);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>[]</code></div>

    <p>

        Depois de utilizar clear(), a lista
        continua existindo, mas fica vazia.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| TABELA DE MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Principais métodos do ArrayList

    </h2>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Método</span>
            <span>Função</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>add()</span>
            <span>Adiciona um elemento</span>
            <span>nomes.add("Ana")</span>

        </div>

        <div class="aula-tabela-linha">

            <span>get()</span>
            <span>Acessa um elemento</span>
            <span>nomes.get(0)</span>

        </div>

        <div class="aula-tabela-linha">

            <span>set()</span>
            <span>Altera um elemento</span>
            <span>nomes.set(0, "Maria")</span>

        </div>

        <div class="aula-tabela-linha">

            <span>remove()</span>
            <span>Remove um elemento</span>
            <span>nomes.remove(0)</span>

        </div>

        <div class="aula-tabela-linha">

            <span>size()</span>
            <span>Retorna a quantidade de elementos</span>
            <span>nomes.size()</span>

        </div>

        <div class="aula-tabela-linha">

            <span>contains()</span>
            <span>Verifica se um elemento existe</span>
            <span>nomes.contains("Ana")</span>

        </div>

        <div class="aula-tabela-linha">

            <span>indexOf()</span>
            <span>Retorna o índice de um elemento</span>
            <span>nomes.indexOf("Ana")</span>

        </div>

        <div class="aula-tabela-linha">

            <span>isEmpty()</span>
            <span>Verifica se a lista está vazia</span>
            <span>nomes.isEmpty()</span>

        </div>

        <div class="aula-tabela-linha">

            <span>clear()</span>
            <span>Remove todos os elementos</span>
            <span>nomes.clear()</span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO PRÁTICO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: linguagens de programação

    </h2>

    <pre class="codigo-java"><code>import java.util.ArrayList;

public class Main {

    public static void main(String[] args) {

        ArrayList&lt;String&gt; linguagens =
            new ArrayList&lt;&gt;();

        linguagens.add("Java");
        linguagens.add("Python");
        linguagens.add("JavaScript");

        System.out.println(
            "Linguagens cadastradas:"
        );

        for (String linguagem : linguagens) {

            System.out.println(
                "- " + linguagem
            );

        }

        System.out.println();

        System.out.println(
            "Quantidade de linguagens: "
            + linguagens.size()
        );

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Linguagens cadastradas:
- Java
- Python
- JavaScript

Quantidade de linguagens: 3</code></div>

    <p>

        Nesse programa:

    </p>

    <ul>

        <li>
            criamos um ArrayList de Strings;
        </li>

        <li>
            adicionamos elementos com add();
        </li>

        <li>
            percorremos a lista com for-each;
        </li>

        <li>
            consultamos a quantidade com size().
        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO DE CADASTRO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: lista de estudantes

    </h2>

    <pre class="codigo-java"><code>import java.util.ArrayList;

public class Main {

    public static void main(String[] args) {

        ArrayList&lt;String&gt; estudantes =
            new ArrayList&lt;&gt;();

        estudantes.add("Robert");
        estudantes.add("Ana");
        estudantes.add("Carlos");
        estudantes.add("Mariana");

        System.out.println(
            "Total de estudantes: "
            + estudantes.size()
        );

        System.out.println();

        for (
            int i = 0;
            i &lt; estudantes.size();
            i++
        ) {

            System.out.println(
                (i + 1)
                + " - "
                + estudantes.get(i)
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Total de estudantes: 4

1 - Robert
2 - Ana
3 - Carlos
4 - Mariana</code></div>

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

        Esquecer a importação

    </h3>

    <p>

        Para utilizar ArrayList, precisamos importar:

    </p>

    <pre class="codigo-java"><code>import java.util.ArrayList;</code></pre>

    <h3>

        Utilizar tipos primitivos nos sinais &lt; &gt;

    </h3>

    <p>

        Esta declaração está incorreta:

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;int&gt; numeros =
    new ArrayList&lt;&gt;();</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;Integer&gt; numeros =
    new ArrayList&lt;&gt;();</code></pre>

    <h3>

        Acessar um índice inexistente

    </h3>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.add("Ana");
nomes.add("Carlos");

System.out.println(nomes.get(2));</code></pre>

    <p>

        A lista possui apenas os índices 0 e 1.

    </p>

    <p>

        Tentar acessar o índice 2 causará um erro
        durante a execução.

    </p>

    <h3>

        Confundir size() com length

    </h3>

    <p>

        ArrayList utiliza:

    </p>

    <pre class="codigo-java"><code>nomes.size()</code></pre>

    <p>

        Array utiliza:

    </p>

    <pre class="codigo-java"><code>nomes.length</code></pre>

    <h3>

        Alterar um índice que não existe

    </h3>

    <p>

        O método set() altera um elemento existente.
        Ele não cria automaticamente uma nova posição.

    </p>

    <pre class="codigo-java"><code>ArrayList&lt;String&gt; nomes =
    new ArrayList&lt;&gt;();

nomes.set(0, "Ana");</code></pre>

    <p>

        Esse código causará erro porque a lista
        ainda está vazia.

    </p>

    <p>

        Para adicionar o primeiro elemento,
        utilize add():

    </p>

    <pre class="codigo-java"><code>nomes.add("Ana");</code></pre>

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

        Vamos criar uma lista de notas, calcular
        a soma e a média.

    </p>

    <pre class="codigo-java"><code>import java.util.ArrayList;

public class Main {

    public static void main(String[] args) {

        ArrayList&lt;Double&gt; notas =
            new ArrayList&lt;&gt;();

        notas.add(8.5);
        notas.add(7.0);
        notas.add(9.5);
        notas.add(6.0);

        double soma = 0;

        for (Double nota : notas) {

            soma += nota;

        }

        double media =
            soma / notas.size();

        System.out.println(
            "Média: " + media
        );

    }

}</code></pre>

    <div class="resultado-codigo"><code>Média: 7.75</code></div>

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

        Crie um programa com um ArrayList de produtos.

    </p>

    <p>

        O programa deverá:

    </p>

    <ul>

        <li>
            adicionar pelo menos cinco produtos;
        </li>

        <li>
            mostrar todos os produtos;
        </li>

        <li>
            alterar um produto utilizando set();
        </li>

        <li>
            remover um produto;
        </li>

        <li>
            verificar se determinado produto existe;
        </li>

        <li>
            mostrar a quantidade final de produtos.
        </li>

    </ul>

    <div class="resultado-codigo"><code>Produtos cadastrados:
- Café
- Leite
- Açúcar
- Pão

Produto "Café" encontrado.
Quantidade de produtos: 4</code></div>

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
        ArrayList para trabalhar com listas
        dinâmicas em Java.

    </p>

    <ul>

        <li>
            ArrayList armazena vários elementos;
        </li>

        <li>
            seu tamanho pode aumentar ou diminuir;
        </li>

        <li>
            precisamos importar java.util.ArrayList;
        </li>

        <li>
            utilizamos classes como Integer e Double
            nos tipos genéricos;
        </li>

        <li>
            add() adiciona elementos;
        </li>

        <li>
            get() acessa elementos;
        </li>

        <li>
            set() altera elementos existentes;
        </li>

        <li>
            remove() remove elementos;
        </li>

        <li>
            size() informa a quantidade de elementos;
        </li>

        <li>
            contains() verifica se um elemento existe;
        </li>

        <li>
            indexOf() informa o índice de um elemento;
        </li>

        <li>
            isEmpty() verifica se a lista está vazia;
        </li>

        <li>
            clear() remove todos os elementos;
        </li>

        <li>
            podemos percorrer a lista com for
            ou for-each.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                ArrayList permite armazenar uma
                quantidade dinâmica de elementos
                e oferece métodos para manipular
                essa lista.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, iniciaremos o estudo
        da Programação Orientada a Objetos com
        classes e objetos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Organizar os dados é um passo essencial
        para transformar informações em soluções."

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

        Próxima aula: Classes e Objetos →

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