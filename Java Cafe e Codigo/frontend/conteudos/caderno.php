<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../backend/config/app.php";
require_once __DIR__ . "/../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../backend/config/helpers.php";


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
                "try_catch"
        ]
    );


/*
|--------------------------------------------------------------------------
| VERIFICA USUÁRIO
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
| IDENTIFICADOR DO CADERNO
|--------------------------------------------------------------------------
|
| O caderno ainda utiliza localStorage.
|
| O ID do usuário é utilizado para separar as anotações
| quando diferentes contas utilizam o mesmo navegador.
|
*/

$chaveCaderno =
    "java_cafe_codigo_caderno_" .
    hash(
        "sha256",
        (string) $usuarioId
    );


/*
|--------------------------------------------------------------------------
| ASSETS
|--------------------------------------------------------------------------
*/

$arquivoStyle =
    __DIR__ .
    "/../assets/css/style.css";


$arquivoAulaCss =
    __DIR__ .
    "/../assets/css/aula.css";


$arquivoThemeJs =
    __DIR__ .
    "/../assets/js/theme.js";


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

function cadernoEscapar(
    mixed $valor
): string
{

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );

}


/*
|--------------------------------------------------------------------------
| CONTEÚDOS DO CADERNO
|--------------------------------------------------------------------------
*/

$secoesCaderno = [

    [
        "titulo" =>
            "Fundamentos de Java",

        "introducao" =>
            "Registre aqui suas conclusões sobre os primeiros conceitos aprendidos durante sua jornada.",

        "topicos" => [

            [
                "id" =>
                    "aula-01",

                "numero" =>
                    "01",

                "titulo" =>
                    "Inserindo texto na tela",

                "pergunta" =>
                    "O que você aprendeu sobre <strong>System.out.println()</strong>? Explique com suas próprias palavras para que ele serve e como pode ser utilizado.",

                "placeholder" =>
                    "Escreva sua conclusão sobre System.out.println()..."
            ],

            [
                "id" =>
                    "aula-02",

                "numero" =>
                    "02",

                "titulo" =>
                    "Variáveis",

                "pergunta" =>
                    "Explique com suas próprias palavras o que é uma variável e por que ela é importante em um programa.",

                "placeholder" =>
                    "Escreva o que você entendeu sobre variáveis..."
            ],

            [
                "id" =>
                    "aula-03",

                "numero" =>
                    "03",

                "titulo" =>
                    "Tipos de dados",

                "pergunta" =>
                    "Qual é a diferença entre <strong>int, double, String, char</strong> e <strong>boolean</strong>? Quando você utilizaria cada um deles?",

                "placeholder" =>
                    "Escreva o que você aprendeu sobre tipos de dados..."
            ],

            [
                "id" =>
                    "aula-04",

                "numero" =>
                    "04",

                "titulo" =>
                    "Entrada de dados com Scanner",

                "pergunta" =>
                    "Como funciona a entrada de dados utilizando o <strong>Scanner</strong>? Explique como um programa pode receber informações digitadas pelo usuário.",

                "placeholder" =>
                    "Explique como funciona a entrada de dados..."
            ],

            [
                "id" =>
                    "aula-05",

                "numero" =>
                    "05",

                "titulo" =>
                    "Operadores matemáticos",

                "pergunta" =>
                    "O que você aprendeu sobre os operadores matemáticos utilizados em Java? Cite exemplos e explique para que eles servem.",

                "placeholder" =>
                    "Registre seu aprendizado sobre operadores matemáticos..."
            ]

        ]

    ],

    [
        "titulo" =>
            "Lógica e decisões",

        "introducao" =>
            "Registre o que você aprendeu sobre comparações, condições e estruturas que permitem que o programa tome decisões.",

        "topicos" => [

            [
                "id" =>
                    "aula-06",

                "numero" =>
                    "06",

                "titulo" =>
                    "Operadores relacionais",

                "pergunta" =>
                    "Como podemos comparar valores utilizando operadores relacionais? Explique o significado de operadores como <strong>maior que, menor que, maior ou igual e igual a</strong>.",

                "placeholder" =>
                    "Escreva sua conclusão sobre operadores relacionais..."
            ],

            [
                "id" =>
                    "aula-07",

                "numero" =>
                    "07",

                "titulo" =>
                    "Operadores lógicos",

                "pergunta" =>
                    "Como podemos combinar condições utilizando os operadores <strong>&amp;&amp;</strong>, <strong>||</strong> e <strong>!</strong>?",

                "placeholder" =>
                    "Escreva o que você entendeu sobre operadores lógicos..."
            ],

            [
                "id" =>
                    "aula-08",

                "numero" =>
                    "08",

                "titulo" =>
                    "Estrutura if",

                "pergunta" =>
                    "O que você entendeu sobre a estrutura condicional <strong>if</strong>? Em quais situações ela pode ser utilizada?",

                "placeholder" =>
                    "Explique como funciona a estrutura if..."
            ],

            [
                "id" =>
                    "aula-09",

                "numero" =>
                    "09",

                "titulo" =>
                    "If e Else",

                "pergunta" =>
                    "Qual é a diferença entre utilizar apenas <strong>if</strong> e utilizar <strong>if e else</strong>? Explique como o programa pode seguir caminhos diferentes.",

                "placeholder" =>
                    "Escreva sua resposta sobre if e else..."
            ],

            [
                "id" =>
                    "aula-10",

                "numero" =>
                    "10",

                "titulo" =>
                    "Else If",

                "pergunta" =>
                    "Quando podemos utilizar várias condições com <strong>else if</strong>? Explique como essa estrutura analisa diferentes possibilidades.",

                "placeholder" =>
                    "Escreva sua conclusão sobre else if..."
            ],

            [
                "id" =>
                    "aula-11",

                "numero" =>
                    "11",

                "titulo" =>
                    "Switch",

                "pergunta" =>
                    "Em quais situações a estrutura <strong>switch</strong> pode ser útil? Explique como funcionam <strong>case</strong>, <strong>break</strong> e <strong>default</strong>.",

                "placeholder" =>
                    "Registre o que você aprendeu sobre switch..."
            ]

        ]

    ],

    [
        "titulo" =>
            "Organizando o código com métodos",

        "introducao" =>
            "Registre suas conclusões sobre como os métodos ajudam a organizar, dividir e reutilizar partes do código.",

        "topicos" => [

            [
                "id" =>
                    "aula-12",

                "numero" =>
                    "12",

                "titulo" =>
                    "Métodos em Java",

                "pergunta" =>
                    "Por que utilizamos métodos para organizar o código? Explique como eles podem evitar repetição e dividir o programa em tarefas menores.",

                "placeholder" =>
                    "Escreva sua conclusão sobre métodos..."
            ],

            [
                "id" =>
                    "aula-13",

                "numero" =>
                    "13",

                "titulo" =>
                    "Parâmetros e argumentos",

                "pergunta" =>
                    "Qual é a função dos parâmetros de um método? Explique também a diferença entre parâmetro e argumento.",

                "placeholder" =>
                    "Explique a diferença entre parâmetros e argumentos..."
            ],

            [
                "id" =>
                    "aula-14",

                "numero" =>
                    "14",

                "titulo" =>
                    "Retorno de métodos",

                "pergunta" =>
                    "O que significa um método retornar um valor? Explique como o resultado pode ser utilizado pelo restante do programa.",

                "placeholder" =>
                    "Escreva o que você entendeu sobre retorno..."
            ]

        ]

    ],

    [
        "titulo" =>
            "Estruturas de repetição",

        "introducao" =>
            "Registre o que você aprendeu sobre as estruturas utilizadas para repetir comandos automaticamente.",

        "topicos" => [

            [
                "id" =>
                    "aula-15",

                "numero" =>
                    "15",

                "titulo" =>
                    "For",

                "pergunta" =>
                    "Quando podemos utilizar uma repetição <strong>for</strong>? Explique por que ela é útil quando sabemos quantas vezes uma ação será repetida.",

                "placeholder" =>
                    "Escreva sua conclusão sobre for..."
            ],

            [
                "id" =>
                    "aula-16",

                "numero" =>
                    "16",

                "titulo" =>
                    "While",

                "pergunta" =>
                    "Quando podemos utilizar uma repetição <strong>while</strong>? Explique como a condição controla a continuidade da repetição.",

                "placeholder" =>
                    "Explique como funciona o while..."
            ],

            [
                "id" =>
                    "aula-17",

                "numero" =>
                    "17",

                "titulo" =>
                    "Do While",

                "pergunta" =>
                    "Qual é a diferença entre <strong>while</strong> e <strong>do while</strong>? Explique por que o do while executa o bloco pelo menos uma vez.",

                "placeholder" =>
                    "Registre o que você aprendeu sobre do while..."
            ]

        ]

    ],

    [
        "titulo" =>
            "Arrays e coleções",

        "introducao" =>
            "Registre suas conclusões sobre as estruturas utilizadas para armazenar e organizar vários valores.",

        "topicos" => [

            [
                "id" =>
                    "aula-18",

                "numero" =>
                    "18",

                "titulo" =>
                    "Arrays",

                "pergunta" =>
                    "O que são arrays e para que podemos utilizá-los? Explique como eles armazenam vários valores do mesmo tipo.",

                "placeholder" =>
                    "Escreva sua conclusão sobre arrays..."
            ],

            [
                "id" =>
                    "aula-19",

                "numero" =>
                    "19",

                "titulo" =>
                    "Percorrendo Arrays",

                "pergunta" =>
                    "Como podemos acessar todos os valores armazenados em um array? Explique como for e for-each podem ajudar nessa tarefa.",

                "placeholder" =>
                    "Explique como percorrer um array..."
            ],

            [
                "id" =>
                    "aula-20",

                "numero" =>
                    "20",

                "titulo" =>
                    "ArrayList",

                "pergunta" =>
                    "O que você entendeu sobre <strong>ArrayList</strong>? Explique sua diferença em relação aos arrays e como trabalhar com listas dinâmicas.",

                "placeholder" =>
                    "Escreva o que você aprendeu sobre ArrayList..."
            ]

        ]

    ],

    [
        "titulo" =>
            "Programação Orientada a Objetos",

        "introducao" =>
            "Registre como você está entendendo os principais conceitos da Programação Orientada a Objetos.",

        "topicos" => [

            [
                "id" =>
                    "aula-21",

                "numero" =>
                    "21",

                "titulo" =>
                    "Classes e objetos",

                "pergunta" =>
                    "O que você entendeu sobre classes e objetos? Explique qual é a relação entre um modelo e os objetos criados a partir dele.",

                "placeholder" =>
                    "Escreva sua conclusão sobre classes e objetos..."
            ],

            [
                "id" =>
                    "aula-22",

                "numero" =>
                    "22",

                "titulo" =>
                    "Atributos e métodos",

                "pergunta" =>
                    "Qual é a função dos atributos e métodos dentro de uma classe? Explique a diferença entre estado e comportamento.",

                "placeholder" =>
                    "Explique a função dos atributos e métodos..."
            ],

            [
                "id" =>
                    "aula-23",

                "numero" =>
                    "23",

                "titulo" =>
                    "Construtores",

                "pergunta" =>
                    "Para que servem os construtores? Explique o que acontece com os atributos quando um objeto é criado por um construtor.",

                "placeholder" =>
                    "Escreva o que você aprendeu sobre construtores..."
            ],

            [
                "id" =>
                    "aula-24",

                "numero" =>
                    "24",

                "titulo" =>
                    "Encapsulamento",

                "pergunta" =>
                    "O que você entendeu sobre encapsulamento? Explique por que podemos utilizar atributos privados, getters, setters e métodos específicos.",

                "placeholder" =>
                    "Registre sua conclusão sobre encapsulamento..."
            ],

            [
                "id" =>
                    "aula-25",

                "numero" =>
                    "25",

                "titulo" =>
                    "Herança",

                "pergunta" =>
                    "Como uma classe pode aproveitar atributos e métodos de outra classe? Explique a relação entre classe pai e classe filha.",

                "placeholder" =>
                    "Escreva sua resposta sobre herança..."
            ],

            [
                "id" =>
                    "aula-26",

                "numero" =>
                    "26",

                "titulo" =>
                    "Polimorfismo",

                "pergunta" =>
                    "O que você entendeu sobre polimorfismo? Explique como a mesma chamada pode produzir comportamentos diferentes.",

                "placeholder" =>
                    "Explique o polimorfismo com suas próprias palavras..."
            ],

            [
                "id" =>
                    "aula-27",

                "numero" =>
                    "27",

                "titulo" =>
                    "Interfaces",

                "pergunta" =>
                    "Qual é a finalidade de uma interface? Explique a ideia de definir um contrato que diferentes classes podem implementar.",

                "placeholder" =>
                    "Registre o que você aprendeu sobre interfaces..."
            ]

        ]

    ],

    [
        "titulo" =>
            "Tratamento de exceções",

        "introducao" =>
            "Registre suas conclusões sobre como os programas podem responder a situações inesperadas durante a execução.",

        "topicos" => [

            [
                "id" =>
                    "aula-28",

                "numero" =>
                    "28",

                "titulo" =>
                    "Exceções",

                "pergunta" =>
                    "O que são exceções? Explique a diferença entre uma exceção em tempo de execução e um erro de compilação.",

                "placeholder" =>
                    "Escreva sua conclusão sobre exceções..."
            ],

            [
                "id" =>
                    "aula-29",

                "numero" =>
                    "29",

                "titulo" =>
                    "Try e Catch",

                "pergunta" =>
                    "Como <strong>try</strong>, <strong>catch</strong> e <strong>finally</strong> ajudam a controlar falhas durante a execução?",

                "placeholder" =>
                    "Explique como funciona o tratamento de exceções..."
            ]

        ]

    ]

];


/*
|--------------------------------------------------------------------------
| REFLEXÕES FINAIS
|--------------------------------------------------------------------------
*/

$reflexoes = [

    [
        "id" =>
            "reflexao-favorito",

        "titulo" =>
            "O conteúdo de que mais gostei",

        "pergunta" =>
            "Qual foi o conceito que você mais gostou de aprender durante esta jornada? Explique o motivo.",

        "placeholder" =>
            "Escreva sobre o conteúdo que você mais gostou..."
    ],

    [
        "id" =>
            "reflexao-dificuldade",

        "titulo" =>
            "O conteúdo mais difícil",

        "pergunta" =>
            "Qual assunto apresentou mais dificuldade? Explique o que tornou esse conteúdo mais complicado.",

        "placeholder" =>
            "Conte qual conteúdo foi mais difícil..."
    ],

    [
        "id" =>
            "reflexao-revisao",

        "titulo" =>
            "O que ainda preciso revisar",

        "pergunta" =>
            "Existe algum conteúdo que você precisa estudar novamente ou praticar com mais atenção?",

        "placeholder" =>
            "Escreva o que você ainda precisa revisar..."
    ],

    [
        "id" =>
            "reflexao-pratica",

        "titulo" =>
            "Projeto que gostaria de criar",

        "pergunta" =>
            "Que programa ou sistema você gostaria de desenvolver utilizando os conhecimentos aprendidos?",

        "placeholder" =>
            "Descreva uma ideia de projeto..."
    ],

    [
        "id" =>
            "reflexao-conclusao",

        "titulo" =>
            "Minha conclusão sobre Java",

        "pergunta" =>
            "Depois de conhecer os principais fundamentos da linguagem, escreva uma conclusão sobre sua experiência aprendendo Java.",

        "placeholder" =>
            "Escreva sua conclusão final sobre a jornada..."
    ]

];

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
    content="Caderno interativo para registrar conclusões, dúvidas e aprendizados obtidos durante as aulas de Java."
>

<title>
    Meu Caderno Java | Java, Café e Código
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
    "/../componentes/header.php";

?>


<main class="aula-container">


<!--
|--------------------------------------------------------------------------
| CABEÇALHO
|--------------------------------------------------------------------------
-->

<section class="aula-cabecalho">

    <span class="aula-numero">

        MEU CADERNO

    </span>

    <h1>

        Meu Caderno Java

    </h1>

    <p>

        Um espaço pessoal para registrar conclusões,
        dúvidas, exemplos e aprendizados construídos
        durante sua jornada pela linguagem Java.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como utilizar o caderno?

    </h2>

    <p>

        Após estudar uma aula, procure explicar o conteúdo
        utilizando suas próprias palavras.

    </p>

    <p>

        Você também pode registrar exemplos, dificuldades,
        dúvidas e observações que ajudem em uma futura revisão.

    </p>

    <div class="aula-destaque">

        <strong>

            Não utilize o caderno apenas para copiar.

        </strong>

        <p>

            Quando você consegue explicar um conceito
            utilizando suas próprias palavras, demonstra
            que começou a compreender realmente aquele assunto.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PROGRESSO E SALVAMENTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Progresso do caderno

    </h2>

    <div class="aula-destaque">

        <strong
            id="caderno-progresso"
        >

            0 de 34 registros preenchidos

        </strong>

        <p
            id="caderno-status"
            aria-live="polite"
        >

            Suas respostas são salvas automaticamente
            neste navegador.

        </p>

        <p>

            O salvamento atual fica armazenado neste
            dispositivo e navegador. A sincronização entre
            dispositivos exigirá um salvamento no banco de dados.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| SEÇÕES DO CADERNO
|--------------------------------------------------------------------------
-->

<?php foreach ($secoesCaderno as $secao): ?>

<section class="aula-secao">

    <h2>

        <?= cadernoEscapar(
            $secao["titulo"]
        ) ?>

    </h2>

    <p>

        <?= cadernoEscapar(
            $secao["introducao"]
        ) ?>

    </p>


    <?php foreach ($secao["topicos"] as $topico): ?>

        <div class="caderno-topico">

            <h3>

                <?= cadernoEscapar(
                    $topico["numero"]
                ) ?>.

                <?= cadernoEscapar(
                    $topico["titulo"]
                ) ?>

            </h3>


            <p class="caderno-pergunta">

                <?= $topico["pergunta"] ?>

            </p>


            <label
                for="<?= cadernoEscapar(
                    $topico["id"]
                ) ?>"
            >

                Sua anotação:

            </label>


            <textarea
                id="<?= cadernoEscapar(
                    $topico["id"]
                ) ?>"
                name="respostas[<?= cadernoEscapar(
                    $topico["id"]
                ) ?>]"
                class="caderno-textarea"
                data-caderno-id="<?= cadernoEscapar(
                    $topico["id"]
                ) ?>"
                rows="7"
                spellcheck="true"
                placeholder="<?= cadernoEscapar(
                    $topico["placeholder"]
                ) ?>"
            ></textarea>

        </div>

    <?php endforeach; ?>

</section>

<?php endforeach; ?>


<!--
|--------------------------------------------------------------------------
| REFLEXÃO FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-resumo">

    <h2>

        Minha jornada pelo Java

    </h2>

    <p>

        Depois de estudar todos os conteúdos, pare por
        alguns minutos e reflita sobre sua evolução.

    </p>


    <?php foreach ($reflexoes as $reflexao): ?>

        <div class="caderno-topico">

            <h3>

                <?= cadernoEscapar(
                    $reflexao["titulo"]
                ) ?>

            </h3>


            <p class="caderno-pergunta">

                <?= cadernoEscapar(
                    $reflexao["pergunta"]
                ) ?>

            </p>


            <label
                for="<?= cadernoEscapar(
                    $reflexao["id"]
                ) ?>"
            >

                Sua reflexão:

            </label>


            <textarea
                id="<?= cadernoEscapar(
                    $reflexao["id"]
                ) ?>"
                name="respostas[<?= cadernoEscapar(
                    $reflexao["id"]
                ) ?>]"
                class="caderno-textarea"
                data-caderno-id="<?= cadernoEscapar(
                    $reflexao["id"]
                ) ?>"
                rows="7"
                spellcheck="true"
                placeholder="<?= cadernoEscapar(
                    $reflexao["placeholder"]
                ) ?>"
            ></textarea>

        </div>

    <?php endforeach; ?>


    <div class="aula-destaque">

        <strong>

            Continue praticando!

        </strong>

        <p>

            Aprender programação não acontece apenas
            com a leitura de conteúdos.

        </p>

        <p>

            Continue criando pequenos programas,
            testando ideias, encontrando erros e
            tentando novamente.

        </p>

        <p>

            A prática transforma conhecimento em experiência.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| AÇÕES DO CADERNO
|--------------------------------------------------------------------------
-->

<section class="caderno-acoes">

    <button
        type="button"
        id="exportar-caderno"
        class="caderno-botao"
    >

        Exportar anotações

    </button>


    <button
        type="button"
        id="limpar-caderno"
        class="caderno-botao"
    >

        Limpar caderno

    </button>

</section>


<!--
|--------------------------------------------------------------------------
| NAVEGAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-acoes">


    <a
        href="<?= cadernoEscapar(
            $linkAulaAnterior
        ) ?>"
        class="aula-botao"
    >

        ← Aula anterior

    </a>


    <a
        href="<?= cadernoEscapar(
            $linkConteudos
        ) ?>"
        class="aula-botao"
    >

        Voltar para conteúdos

    </a>


</section>


</main>


<?php

require_once __DIR__ .
    "/../componentes/footer.php";

?>


<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        "use strict";


        /*
        |--------------------------------------------------------------------------
        | ELEMENTOS
        |--------------------------------------------------------------------------
        */

        const campos =
            Array.from(
                document.querySelectorAll(
                    ".caderno-textarea[data-caderno-id]"
                )
            );


        const progressoElemento =
            document.getElementById(
                "caderno-progresso"
            );


        const statusElemento =
            document.getElementById(
                "caderno-status"
            );


        const botaoExportar =
            document.getElementById(
                "exportar-caderno"
            );


        const botaoLimpar =
            document.getElementById(
                "limpar-caderno"
            );


        const chaveArmazenamento =
            <?= json_encode(
                $chaveCaderno,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS
                | JSON_HEX_QUOT
            ) ?>;


        let temporizadorSalvamento =
            null;


        /*
        |--------------------------------------------------------------------------
        | CARREGA OS DADOS
        |--------------------------------------------------------------------------
        */

        function carregarDados() {

            try {

                const dadosSalvos =
                    localStorage.getItem(
                        chaveArmazenamento
                    );


                if (!dadosSalvos) {

                    atualizarProgresso();

                    return;

                }


                const dados =
                    JSON.parse(
                        dadosSalvos
                    );


                if (
                    typeof dados !== "object"
                    ||
                    dados === null
                    ||
                    Array.isArray(dados)
                ) {

                    throw new Error(
                        "Formato inválido do caderno."
                    );

                }


                campos.forEach(
                    function (campo) {

                        const identificador =
                            campo.dataset.cadernoId;


                        if (
                            Object.prototype.hasOwnProperty.call(
                                dados,
                                identificador
                            )
                        ) {

                            campo.value =
                                String(
                                    dados[identificador]
                                    ?? ""
                                );

                        }

                    }
                );


                atualizarProgresso();


                if (statusElemento) {

                    statusElemento.textContent =
                        "Anotações recuperadas deste navegador.";

                }

            } catch (erro) {

                console.error(
                    "Não foi possível carregar o caderno:",
                    erro
                );


                if (statusElemento) {

                    statusElemento.textContent =
                        "Não foi possível recuperar as anotações salvas.";

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | COLETA AS RESPOSTAS
        |--------------------------------------------------------------------------
        */

        function coletarDados() {

            const dados = {};


            campos.forEach(
                function (campo) {

                    dados[
                        campo.dataset.cadernoId
                    ] =
                        campo.value;

                }
            );


            return dados;

        }


        /*
        |--------------------------------------------------------------------------
        | SALVA AS RESPOSTAS
        |--------------------------------------------------------------------------
        */

        function salvarDados() {

            try {

                const dados =
                    coletarDados();


                localStorage.setItem(
                    chaveArmazenamento,
                    JSON.stringify(
                        dados
                    )
                );


                const horario =
                    new Date()
                        .toLocaleTimeString(
                            "pt-BR",
                            {
                                hour:
                                    "2-digit",

                                minute:
                                    "2-digit"
                            }
                        );


                if (statusElemento) {

                    statusElemento.textContent =
                        "Caderno salvo neste navegador às "
                        + horario
                        + ".";

                }

            } catch (erro) {

                console.error(
                    "Não foi possível salvar o caderno:",
                    erro
                );


                if (statusElemento) {

                    statusElemento.textContent =
                        "Não foi possível salvar as anotações.";

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | SALVAMENTO COM PEQUENO ATRASO
        |--------------------------------------------------------------------------
        */

        function agendarSalvamento() {

            if (statusElemento) {

                statusElemento.textContent =
                    "Salvando anotações...";

            }


            window.clearTimeout(
                temporizadorSalvamento
            );


            temporizadorSalvamento =
                window.setTimeout(
                    salvarDados,
                    350
                );

        }


        /*
        |--------------------------------------------------------------------------
        | PROGRESSO
        |--------------------------------------------------------------------------
        */

        function atualizarProgresso() {

            const preenchidos =
                campos.filter(
                    function (campo) {

                        return (
                            campo.value.trim()
                            !== ""
                        );

                    }
                ).length;


            if (progressoElemento) {

                progressoElemento.textContent =
                    preenchidos
                    + " de "
                    + campos.length
                    + " registros preenchidos";

            }

        }


        /*
        |--------------------------------------------------------------------------
        | EXPORTAÇÃO
        |--------------------------------------------------------------------------
        */

        function exportarCaderno() {

            const linhas = [

                "JAVA, CAFÉ E CÓDIGO",

                "MEU CADERNO JAVA",

                "",

                "Exportado em: "
                    + new Date()
                        .toLocaleString(
                            "pt-BR"
                        ),

                "",

                "========================================",

                ""

            ];


            campos.forEach(
                function (campo) {

                    const topico =
                        campo.closest(
                            ".caderno-topico"
                        );


                    const titulo =
                        topico
                            ?.querySelector(
                                "h3"
                            )
                            ?.textContent
                            ?.trim()
                        ||
                        campo.dataset.cadernoId;


                    linhas.push(
                        titulo
                    );


                    linhas.push(
                        "-".repeat(
                            Math.min(
                                titulo.length,
                                80
                            )
                        )
                    );


                    if (
                        campo.value.trim()
                        !== ""
                    ) {

                        linhas.push(
                            campo.value.trim()
                        );

                    } else {

                        linhas.push(
                            "[Nenhuma anotação registrada]"
                        );

                    }


                    linhas.push(
                        ""
                    );

                }
            );


            const arquivo =
                new Blob(
                    [
                        linhas.join(
                            "\n"
                        )
                    ],
                    {
                        type:
                            "text/plain;charset=utf-8"
                    }
                );


            const enderecoArquivo =
                URL.createObjectURL(
                    arquivo
                );


            const link =
                document.createElement(
                    "a"
                );


            const data =
                new Date()
                    .toISOString()
                    .slice(
                        0,
                        10
                    );


            link.href =
                enderecoArquivo;


            link.download =
                "meu-caderno-java-"
                + data
                + ".txt";


            document.body.appendChild(
                link
            );


            link.click();

            link.remove();


            window.setTimeout(
                function () {

                    URL.revokeObjectURL(
                        enderecoArquivo
                    );

                },
                0
            );


            if (statusElemento) {

                statusElemento.textContent =
                    "As anotações foram exportadas.";

            }

        }


        /*
        |--------------------------------------------------------------------------
        | LIMPEZA
        |--------------------------------------------------------------------------
        */

        function limparCaderno() {

            const confirmar =
                window.confirm(
                    "Deseja realmente apagar todas as anotações deste caderno?"
                );


            if (!confirmar) {

                return;

            }


            campos.forEach(
                function (campo) {

                    campo.value =
                        "";

                }
            );


            try {

                localStorage.removeItem(
                    chaveArmazenamento
                );

            } catch (erro) {

                console.error(
                    "Não foi possível limpar o armazenamento:",
                    erro
                );

            }


            atualizarProgresso();


            if (statusElemento) {

                statusElemento.textContent =
                    "Todas as anotações foram apagadas.";

            }

        }


        /*
        |--------------------------------------------------------------------------
        | EVENTOS
        |--------------------------------------------------------------------------
        */

        campos.forEach(
            function (campo) {

                campo.addEventListener(
                    "input",
                    function () {

                        atualizarProgresso();

                        agendarSalvamento();

                    }
                );

            }
        );


        botaoExportar
            ?.addEventListener(
                "click",
                exportarCaderno
            );


        botaoLimpar
            ?.addEventListener(
                "click",
                limparCaderno
            );


        /*
        |--------------------------------------------------------------------------
        | INICIALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        carregarDados();

    }
);

</script>


</body>

</html>