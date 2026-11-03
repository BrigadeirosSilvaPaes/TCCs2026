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


$linkPainelEstudante =
    rota(
        "estudante.painel"
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
| ESCAPA HTML
|--------------------------------------------------------------------------
*/

function conteudoEscapar(
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
| PREPARA ÍCONE
|--------------------------------------------------------------------------
|
| Alguns ícones do catálogo podem estar armazenados assim:
|
| &lt;/&gt;
|
| Primeiro convertemos a entidade para o caractere real e depois
| escapamos novamente para exibição segura como texto.
|
*/

function conteudoEscaparIcone(
    mixed $valor
): string
{

    $texto =
        html_entity_decode(
            (string) $valor,
            ENT_QUOTES | ENT_HTML5,
            "UTF-8"
        );


    return conteudoEscapar(
        $texto
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA ID DO MÓDULO
|--------------------------------------------------------------------------
*/

function conteudoModuloId(
    mixed $valor
): ?string
{

    $id =
        strtolower(
            trim(
                (string) $valor
            )
        );


    if (
        $id === ""
        ||
        preg_match(
            "/^[a-z0-9_-]+$/",
            $id
        ) !== 1
    ) {

        return null;

    }


    return $id;

}


/*
|--------------------------------------------------------------------------
| GERA URL LIMPA DA AULA
|--------------------------------------------------------------------------
|
| O catálogo guarda:
|
| "arquivo" => "variaveis.php"
|
| O navegador recebe:
|
| /java/variaveis
|
*/

function conteudoUrlAula(
    array $aula
): ?string
{

    $arquivo =
        trim(
            (string) (
                $aula["arquivo"]
                ?? ""
            )
        );


    if (
        $arquivo === ""
    ) {

        return null;

    }


    /*
    |--------------------------------------------------------------------------
    | EXTRAI SOMENTE O SLUG
    |--------------------------------------------------------------------------
    */

    $slug =
        pathinfo(
            basename(
                $arquivo
            ),
            PATHINFO_FILENAME
        );


    $slug =
        strtolower(
            trim(
                (string) $slug
            )
        );


    /*
    |--------------------------------------------------------------------------
    | MESMA REGRA DO ROUTER
    |--------------------------------------------------------------------------
    */

    if (
        preg_match(
            "/^[a-z0-9_-]+$/",
            $slug
        ) !== 1
    ) {

        return null;

    }


    return rota(
        "java.conteudo",
        [
            "slug" =>
                $slug
        ]
    );

}


/*
|--------------------------------------------------------------------------
| CARREGA A ORGANIZAÇÃO DAS AULAS
|--------------------------------------------------------------------------
*/

$arquivoAulas =
    __DIR__ .
    "/../../backend/config/aulas.php";


if (
    !is_file(
        $arquivoAulas
    )
) {

    error_log(
        "[ConteudosJava] Arquivo do catálogo de aulas não encontrado."
    );


    http_response_code(
        500
    );


    exit(
        "Não foi possível carregar a trilha de conteúdos."
    );

}


try {

    $modulos =
        require $arquivoAulas;

}
catch (Throwable $erro) {

    error_log(
        "[ConteudosJava] Erro ao carregar catálogo: " .
        $erro->getMessage()
    );


    http_response_code(
        500
    );


    exit(
        "Não foi possível carregar a trilha de conteúdos."
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA CATÁLOGO
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $modulos
    )
) {

    error_log(
        "[ConteudosJava] O catálogo de aulas não retornou um array."
    );


    http_response_code(
        500
    );


    exit(
        "Não foi possível carregar a trilha de conteúdos."
    );

}


/*
|--------------------------------------------------------------------------
| ESTATÍSTICAS DA TRILHA
|--------------------------------------------------------------------------
|
| Contamos apenas módulos e aulas que realmente podem ser exibidos.
|
*/

$totalModulos =
    0;


$totalAulas =
    0;


foreach (
    $modulos as $modulo
) {

    if (
        !is_array(
            $modulo
        )
    ) {

        continue;

    }


    $moduloId =
        conteudoModuloId(
            $modulo["id"]
            ?? ""
        );


    if (
        $moduloId === null
    ) {

        continue;

    }


    $totalModulos++;


    $aulasModulo =
        $modulo["aulas"]
        ?? [];


    if (
        !is_array(
            $aulasModulo
        )
    ) {

        continue;

    }


    foreach (
        $aulasModulo as $aula
    ) {

        if (
            !is_array(
                $aula
            )
        ) {

            continue;

        }


        if (
            conteudoUrlAula(
                $aula
            ) === null
        ) {

            continue;

        }


        $totalAulas++;

    }

}


/*
|--------------------------------------------------------------------------
| VERSÕES DOS ASSETS
|--------------------------------------------------------------------------
*/

$arquivoStyle =
    __DIR__ .
    "/../assets/css/style.css";


$arquivoConteudoCss =
    __DIR__ .
    "/../assets/css/conteudo.css";


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


$versaoConteudoCss =
    is_file(
        $arquivoConteudoCss
    )
        ? filemtime(
            $arquivoConteudoCss
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
    content="Trilha completa de conteúdos de Java da plataforma Java, Café e Código."
>

<title>
    Conteúdos de Java | Java, Café e Código
</title>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= $versaoStyle ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/conteudo.css?v=<?= $versaoConteudoCss ?>"
>

</head>


<body class="pagina-conteudos">


<?php

require_once __DIR__ .
    "/../componentes/header.php";

?>


<main class="conteudos-pagina">


<!--
|--------------------------------------------------------------------------
| APRESENTAÇÃO
|--------------------------------------------------------------------------
-->

<section class="conteudos-hero">


    <div class="conteudos-hero-texto">


        <span class="conteudos-etiqueta">

            TRILHA DE APRENDIZAGEM

        </span>


        <h1>

            Aprenda Java

            <strong>

                passo a passo.

            </strong>

        </h1>


        <p>

            Escolha uma aula, estude os conceitos no seu ritmo
            e avance gradualmente até conseguir construir seus
            próprios programas em Java.

        </p>


        <div class="conteudos-resumo">


            <article>

                <strong>

                    <?= conteudoEscapar(
                        $totalAulas
                    ) ?>

                </strong>

                <span>

                    aulas

                </span>

            </article>


            <article>

                <strong>

                    <?= conteudoEscapar(
                        $totalModulos
                    ) ?>

                </strong>

                <span>

                    etapas

                </span>

            </article>


            <article>

                <strong>

                    Java

                </strong>

                <span>

                    do básico à POO

                </span>

            </article>


        </div>


    </div>


    <div class="conteudos-hero-codigo">


        <div class="conteudos-codigo-topo">

            <span></span>
            <span></span>
            <span></span>

            <strong>

                TrilhaJava.java

            </strong>

        </div>


        <pre><code><span class="codigo-palavra">public class</span> <span class="codigo-classe">TrilhaJava</span> {

    <span class="codigo-palavra">public static void</span> <span class="codigo-metodo">main</span>(
        String[] args
    ) {

        Aluno aluno = <span class="codigo-palavra">new</span> Aluno();

        aluno.<span class="codigo-metodo">aprender</span>();
        aluno.<span class="codigo-metodo">praticar</span>();

    }
}</code></pre>


    </div>


</section>


<!--
|--------------------------------------------------------------------------
| FERRAMENTAS
|--------------------------------------------------------------------------
-->

<section class="conteudos-ferramentas">


    <div class="conteudos-busca">


        <span
            class="conteudos-busca-icone"
            aria-hidden="true"
        >

            ⌕

        </span>


        <input
            type="search"
            id="pesquisaAula"
            placeholder="Pesquisar por aula, assunto ou conceito..."
            autocomplete="off"
            aria-label="Pesquisar uma aula de Java"
        >


        <button
            type="button"
            id="limparPesquisa"
            class="conteudos-limpar-pesquisa"
            aria-label="Limpar pesquisa"
            title="Limpar pesquisa"
            hidden
        >

            ×

        </button>


    </div>


    <a
        href="<?= conteudoEscapar(
            $linkPainelEstudante
        ) ?>"
        class="conteudos-voltar-topo"
    >

        ← Voltar ao painel

    </a>


</section>


<!--
|--------------------------------------------------------------------------
| RESULTADO DA PESQUISA
|--------------------------------------------------------------------------
-->

<div
    class="conteudos-resultado-pesquisa"
    id="resultadoPesquisa"
    aria-live="polite"
    hidden
>

    <span
        id="textoResultadoPesquisa"
    ></span>

</div>


<!--
|--------------------------------------------------------------------------
| NAVEGAÇÃO DOS MÓDULOS
|--------------------------------------------------------------------------
-->

<nav
    class="conteudos-navegacao"
    aria-label="Navegação pelas etapas da trilha"
>


    <?php foreach (
        $modulos as $modulo
    ): ?>


        <?php

        if (
            !is_array(
                $modulo
            )
        ) {

            continue;

        }


        $moduloId =
            conteudoModuloId(
                $modulo["id"]
                ?? ""
            );


        if (
            $moduloId === null
        ) {

            continue;

        }

        ?>


        <a
            href="#<?= conteudoEscapar(
                $moduloId
            ) ?>"
        >

            <span>

                <?= conteudoEscapar(
                    $modulo["numero"]
                    ?? ""
                ) ?>

            </span>


            <?= conteudoEscapar(
                $modulo["titulo"]
                ?? ""
            ) ?>


        </a>


    <?php endforeach; ?>


</nav>


<!--
|--------------------------------------------------------------------------
| MÓDULOS E AULAS
|--------------------------------------------------------------------------
-->

<div id="listaConteudos">


    <?php foreach (
        $modulos as $modulo
    ): ?>


        <?php

        if (
            !is_array(
                $modulo
            )
        ) {

            continue;

        }


        $moduloId =
            conteudoModuloId(
                $modulo["id"]
                ?? ""
            );


        if (
            $moduloId === null
        ) {

            error_log(
                "[ConteudosJava] Módulo ignorado por possuir ID inválido."
            );


            continue;

        }


        $aulasModulo =
            $modulo["aulas"]
            ?? [];


        if (
            !is_array(
                $aulasModulo
            )
        ) {

            $aulasModulo =
                [];

        }


        $totalAulasModulo =
            0;


        foreach (
            $aulasModulo as $aulaContagem
        ) {

            if (
                !is_array(
                    $aulaContagem
                )
            ) {

                continue;

            }


            if (
                conteudoUrlAula(
                    $aulaContagem
                ) !== null
            ) {

                $totalAulasModulo++;

            }

        }

        ?>


        <section
            class="conteudos-modulo"
            id="<?= conteudoEscapar(
                $moduloId
            ) ?>"
        >


            <header class="conteudos-modulo-cabecalho">


                <div class="conteudos-modulo-identidade">


                    <span
                        class="conteudos-modulo-icone"
                        aria-hidden="true"
                    >

                        <?= conteudoEscaparIcone(
                            $modulo["icone"]
                            ?? ""
                        ) ?>

                    </span>


                    <div>


                        <span class="conteudos-modulo-etapa">

                            ETAPA

                            <?= conteudoEscapar(
                                $modulo["numero"]
                                ?? ""
                            ) ?>

                        </span>


                        <h2>

                            <?= conteudoEscapar(
                                $modulo["titulo"]
                                ?? ""
                            ) ?>

                        </h2>


                        <p>

                            <?= conteudoEscapar(
                                $modulo["resumo"]
                                ?? ""
                            ) ?>

                        </p>


                    </div>


                </div>


                <span class="conteudos-modulo-total">

                    <?= conteudoEscapar(
                        $totalAulasModulo
                    ) ?>


                    <?= $totalAulasModulo === 1
                        ? "aula"
                        : "aulas" ?>

                </span>


            </header>


            <div class="conteudos-grid">


                <?php foreach (
                    $aulasModulo as $aula
                ): ?>


                    <?php

                    if (
                        !is_array(
                            $aula
                        )
                    ) {

                        continue;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | URL LIMPA
                    |--------------------------------------------------------------------------
                    */

                    $urlAula =
                        conteudoUrlAula(
                            $aula
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | IGNORA ENTRADA INVÁLIDA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $urlAula === null
                    ) {

                        error_log(
                            "[ConteudosJava] Aula com arquivo inválido: " .
                            (
                                $aula["arquivo"]
                                ?? "não informado"
                            )
                        );


                        continue;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | NÚMERO DA AULA
                    |--------------------------------------------------------------------------
                    */

                    $numeroAula =
                        (int) (
                            $aula["numero"]
                            ?? 0
                        );


                    $numeroFormatado =
                        str_pad(
                            (string) $numeroAula,
                            2,
                            "0",
                            STR_PAD_LEFT
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | TEXTO DA PESQUISA
                    |--------------------------------------------------------------------------
                    */

                    $textoPesquisa =
                        $numeroAula .
                        " aula " .
                        $numeroFormatado .
                        " " .
                        (
                            $aula["titulo"]
                            ?? ""
                        ) .
                        " " .
                        (
                            $aula["descricao"]
                            ?? ""
                        ) .
                        " " .
                        (
                            $aula["conceito"]
                            ?? ""
                        ) .
                        " " .
                        (
                            $modulo["titulo"]
                            ?? ""
                        ) .
                        " " .
                        (
                            $modulo["resumo"]
                            ?? ""
                        );

                    ?>


                    <a
                        href="<?= conteudoEscapar(
                            $urlAula
                        ) ?>"
                        class="conteudo-card"
                        data-pesquisa="<?= conteudoEscapar(
                            $textoPesquisa
                        ) ?>"
                    >


                        <div class="conteudo-card-topo">


                            <span class="conteudo-numero">

                                AULA

                                <?= conteudoEscapar(
                                    $numeroFormatado
                                ) ?>

                            </span>


                            <span
                                class="conteudo-card-seta"
                                aria-hidden="true"
                            >

                                →

                            </span>


                        </div>


                        <div class="conteudo-card-corpo">


                            <span class="conteudo-conceito">

                                <?= conteudoEscapar(
                                    $aula["conceito"]
                                    ?? ""
                                ) ?>

                            </span>


                            <h3>

                                <?= conteudoEscapar(
                                    $aula["titulo"]
                                    ?? ""
                                ) ?>

                            </h3>


                            <p>

                                <?= conteudoEscapar(
                                    $aula["descricao"]
                                    ?? ""
                                ) ?>

                            </p>


                        </div>


                        <span class="conteudo-card-acao">

                            Acessar aula

                        </span>


                    </a>


                <?php endforeach; ?>


            </div>


        </section>


    <?php endforeach; ?>


</div>


<!--
|--------------------------------------------------------------------------
| NENHUM RESULTADO
|--------------------------------------------------------------------------
-->

<section
    class="conteudos-sem-resultado"
    id="semResultado"
    hidden
>


    <span aria-hidden="true">

        { }

    </span>


    <h2>

        Nenhuma aula encontrada

    </h2>


    <p>

        Tente pesquisar utilizando outro termo.

    </p>


</section>


<!--
|--------------------------------------------------------------------------
| CADERNO JAVA
|--------------------------------------------------------------------------
-->

<section
    class="conteudos-caderno"
    id="caderno"
>


    <div>


        <span class="conteudos-etiqueta">

            MEU CADERNO JAVA

        </span>


        <h2>

            Transforme estudo em aprendizado

        </h2>


        <p>

            Registre suas conclusões, dúvidas e exemplos.
            Explicar um conceito com suas próprias palavras
            ajuda a perceber o que realmente foi compreendido.

        </p>


    </div>


    <a
        href="<?= conteudoEscapar(
            $linkCaderno
        ) ?>"
        class="conteudos-caderno-botao"
    >

        Abrir meu caderno

        <span aria-hidden="true">

            →

        </span>

    </a>


</section>


<!--
|--------------------------------------------------------------------------
| VOLTAR
|--------------------------------------------------------------------------
-->

<section class="conteudos-acoes">


    <a
        href="<?= conteudoEscapar(
            $linkPainelEstudante
        ) ?>"
        class="conteudos-voltar"
    >

        ← Voltar para o painel

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

        const campoPesquisa =
            document.getElementById(
                "pesquisaAula"
            );


        const botaoLimpar =
            document.getElementById(
                "limparPesquisa"
            );


        const cards =
            Array.from(
                document.querySelectorAll(
                    ".conteudo-card"
                )
            );


        const modulos =
            Array.from(
                document.querySelectorAll(
                    ".conteudos-modulo"
                )
            );


        const semResultado =
            document.getElementById(
                "semResultado"
            );


        const resultadoPesquisa =
            document.getElementById(
                "resultadoPesquisa"
            );


        const textoResultadoPesquisa =
            document.getElementById(
                "textoResultadoPesquisa"
            );


        const navegacaoModulos =
            document.querySelector(
                ".conteudos-navegacao"
            );


        /*
        |--------------------------------------------------------------------------
        | PROTEÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !campoPesquisa
            ||
            cards.length === 0
        ) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZA TEXTO
        |--------------------------------------------------------------------------
        */

        function normalizarTexto(
            texto
        ) {

            return String(
                texto
                ?? ""
            )
                .normalize(
                    "NFD"
                )
                .replace(
                    /[\u0300-\u036f]/g,
                    ""
                )
                .toLocaleLowerCase(
                    "pt-BR"
                )
                .trim();

        }


        /*
        |--------------------------------------------------------------------------
        | ATUALIZA A PESQUISA
        |--------------------------------------------------------------------------
        */

        function atualizarPesquisa() {

            const termoOriginal =
                campoPesquisa.value.trim();


            const termo =
                normalizarTexto(
                    termoOriginal
                );


            let quantidadeEncontrada =
                0;


            /*
            |--------------------------------------------------------------------------
            | FILTRA CARDS
            |--------------------------------------------------------------------------
            */

            cards.forEach(
                function (
                    card
                ) {

                    const conteudoCard =
                        normalizarTexto(
                            card.dataset.pesquisa
                        );


                    const encontrou =
                        termo === ""
                        ||
                        conteudoCard.includes(
                            termo
                        );


                    card.hidden =
                        !encontrou;


                    if (
                        encontrou
                    ) {

                        quantidadeEncontrada++;

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | ESCONDE MÓDULOS SEM RESULTADO
            |--------------------------------------------------------------------------
            */

            modulos.forEach(
                function (
                    modulo
                ) {

                    const cardsDoModulo =
                        Array.from(
                            modulo.querySelectorAll(
                                ".conteudo-card"
                            )
                        );


                    const possuiCardVisivel =
                        cardsDoModulo.some(
                            function (
                                card
                            ) {

                                return !card.hidden;

                            }
                        );


                    modulo.hidden =
                        termo !== ""
                        &&
                        !possuiCardVisivel;

                }
            );


            /*
            |--------------------------------------------------------------------------
            | BOTÃO LIMPAR
            |--------------------------------------------------------------------------
            */

            if (
                botaoLimpar
            ) {

                botaoLimpar.hidden =
                    termoOriginal === "";

            }


            /*
            |--------------------------------------------------------------------------
            | NAVEGAÇÃO DOS MÓDULOS
            |--------------------------------------------------------------------------
            */

            if (
                navegacaoModulos
            ) {

                navegacaoModulos.hidden =
                    termoOriginal !== "";

            }


            /*
            |--------------------------------------------------------------------------
            | RESULTADO
            |--------------------------------------------------------------------------
            */

            if (
                resultadoPesquisa
                &&
                textoResultadoPesquisa
            ) {

                if (
                    termoOriginal === ""
                ) {

                    resultadoPesquisa.hidden =
                        true;


                    textoResultadoPesquisa.textContent =
                        "";

                }
                else {

                    resultadoPesquisa.hidden =
                        false;


                    if (
                        quantidadeEncontrada === 1
                    ) {

                        textoResultadoPesquisa.textContent =
                            '1 aula encontrada para "' +
                            termoOriginal +
                            '"';

                    }
                    else {

                        textoResultadoPesquisa.textContent =
                            quantidadeEncontrada +
                            ' aulas encontradas para "' +
                            termoOriginal +
                            '"';

                    }

                }

            }


            /*
            |--------------------------------------------------------------------------
            | SEM RESULTADO
            |--------------------------------------------------------------------------
            */

            if (
                semResultado
            ) {

                semResultado.hidden =
                    termoOriginal === ""
                    ||
                    quantidadeEncontrada > 0;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | PESQUISA
        |--------------------------------------------------------------------------
        */

        campoPesquisa.addEventListener(
            "input",
            atualizarPesquisa
        );


        /*
        |--------------------------------------------------------------------------
        | LIMPAR PESQUISA
        |--------------------------------------------------------------------------
        */

        if (
            botaoLimpar
        ) {

            botaoLimpar.addEventListener(
                "click",
                function () {

                    campoPesquisa.value =
                        "";


                    atualizarPesquisa();


                    campoPesquisa.focus();

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ESC
        |--------------------------------------------------------------------------
        */

        campoPesquisa.addEventListener(
            "keydown",
            function (
                evento
            ) {

                if (
                    evento.key === "Escape"
                    &&
                    campoPesquisa.value !== ""
                ) {

                    campoPesquisa.value =
                        "";


                    atualizarPesquisa();


                    campoPesquisa.focus();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | ESTADO INICIAL
        |--------------------------------------------------------------------------
        */

        atualizarPesquisa();

    }
);

</script>


</body>

</html>