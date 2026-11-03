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


$linkCaderno =
    rota(
        "caderno"
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
| ESCAPA HTML
|--------------------------------------------------------------------------
*/

function manualEscapar(
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
| VALIDA IDENTIFICADORES
|--------------------------------------------------------------------------
*/

function manualIdValido(
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
| TEXTO DE PESQUISA
|--------------------------------------------------------------------------
*/

function manualTextoPesquisa(
    string $texto
): string
{

    if (
        function_exists(
            "mb_strtolower"
        )
    ) {

        return mb_strtolower(
            $texto,
            "UTF-8"
        );

    }


    return strtolower(
        $texto
    );

}


/*
|--------------------------------------------------------------------------
| CARREGA O CONTEÚDO DO MANUAL
|--------------------------------------------------------------------------
*/

$arquivoTopicos =
    __DIR__ .
    "/dados/topicos.php";


if (
    !is_file(
        $arquivoTopicos
    )
) {

    error_log(
        "[ManualJava] Catálogo de tópicos não encontrado."
    );


    http_response_code(
        500
    );


    exit(
        "Não foi possível carregar o Manual de Java."
    );

}


try {

    $categorias =
        require $arquivoTopicos;

}
catch (Throwable $erro) {

    error_log(
        "[ManualJava] Erro ao carregar catálogo: " .
        $erro->getMessage()
    );


    http_response_code(
        500
    );


    exit(
        "Não foi possível carregar o Manual de Java."
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA CATÁLOGO
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $categorias
    )
) {

    error_log(
        "[ManualJava] O catálogo não retornou um array."
    );


    http_response_code(
        500
    );


    exit(
        "Não foi possível carregar o Manual de Java."
    );

}


/*
|--------------------------------------------------------------------------
| ESTATÍSTICAS
|--------------------------------------------------------------------------
*/

$totalCategorias =
    0;


$totalTopicos =
    0;


foreach (
    $categorias as $categoria
) {

    if (
        !is_array(
            $categoria
        )
    ) {

        continue;

    }


    $categoriaId =
        manualIdValido(
            $categoria["id"]
            ?? ""
        );


    if (
        $categoriaId === null
    ) {

        continue;

    }


    $topicos =
        $categoria["topicos"]
        ?? [];


    if (
        !is_array(
            $topicos
        )
    ) {

        continue;

    }


    $quantidadeValidos =
        0;


    foreach (
        $topicos as $topico
    ) {

        if (
            !is_array(
                $topico
            )
        ) {

            continue;

        }


        $topicoId =
            manualIdValido(
                $topico["id"]
                ?? ""
            );


        if (
            $topicoId === null
        ) {

            continue;

        }


        $quantidadeValidos++;

    }


    $totalCategorias++;

    $totalTopicos +=
        $quantidadeValidos;

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


$arquivoManualCss =
    __DIR__ .
    "/../../assets/css/manual.css";


$arquivoThemeJs =
    __DIR__ .
    "/../../assets/js/theme.js";


$arquivoManualJs =
    __DIR__ .
    "/../../assets/js/manual.js";


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


$versaoManualCss =
    is_file(
        $arquivoManualCss
    )
        ? filemtime(
            $arquivoManualCss
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


$versaoManualJs =
    is_file(
        $arquivoManualJs
    )
        ? filemtime(
            $arquivoManualJs
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
    content="Manual complementar de Java com conteúdos organizados do nível fundamental ao avançado."
>

<title>
    Manual de Java | Java, Café e Código
</title>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= $versaoStyle ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/aula.css?v=<?= $versaoAulaCss ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/manual.css?v=<?= $versaoManualCss ?>"
>

</head>


<body>


<?php

require_once __DIR__ .
    "/../../componentes/header.php";

?>


<main class="aula-container manual-container">


<!--
|--------------------------------------------------------------------------
| CABEÇALHO
|--------------------------------------------------------------------------
-->

<section class="aula-cabecalho">

    <span class="aula-numero">

        MATERIAL COMPLEMENTAR

    </span>


    <h1>

        Manual de Java

    </h1>


    <p>

        Consulte conceitos da linguagem Java,
        do nível fundamental ao avançado,
        com explicações, boas práticas,
        analogias e exemplos de código.

    </p>


    <div
        class="manual-resumo"
        aria-label="Resumo do manual"
    >

        <span>

            <?= manualEscapar(
                $totalCategorias
            ) ?>

            <?= $totalCategorias === 1
                ? "categoria"
                : "categorias" ?>

        </span>


        <span>

            <?= manualEscapar(
                $totalTopicos
            ) ?>

            <?= $totalTopicos === 1
                ? "tópico"
                : "tópicos" ?>

        </span>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| APRESENTAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Como utilizar este manual?

    </h2>


    <p>

        As aulas principais apresentam uma sequência
        de aprendizagem. Este manual funciona como um
        material complementar de consulta e aprofundamento.

    </p>


    <p>

        Utilize a pesquisa e os filtros para localizar
        rapidamente um assunto específico. Depois,
        abra o card para visualizar o conteúdo completo.

    </p>


    <div class="aula-destaque">

        <strong>

            O manual complementa as aulas.

        </strong>

        <p>

            Para quem está começando, recomendamos seguir
            primeiro a ordem das aulas e utilizar esta página
            para revisão, consulta e aprofundamento.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PESQUISA E FILTROS
|--------------------------------------------------------------------------
-->

<section
    class="aula-secao"
    aria-labelledby="manual-ferramentas-titulo"
>

    <h2
        id="manual-ferramentas-titulo"
    >

        Encontre um conteúdo

    </h2>


    <p>

        Pesquise pelo nome do assunto ou filtre os tópicos
        por categoria e nível de dificuldade.

    </p>


    <div
        class="manual-ferramentas"
        aria-label="Ferramentas de pesquisa do manual"
    >


        <div
            class="manual-campo manual-campo-pesquisa"
        >

            <label
                for="manual-pesquisa"
            >

                Pesquisar assunto

            </label>


            <input
                type="search"
                id="manual-pesquisa"
                placeholder="Ex.: coleções, Spring Boot, threads..."
                autocomplete="off"
            >

        </div>


        <div class="manual-campo">

            <label
                for="manual-categoria"
            >

                Categoria

            </label>


            <select
                id="manual-categoria"
            >

                <option value="">

                    Todas as categorias

                </option>


                <?php foreach (
                    $categorias as $categoria
                ): ?>


                    <?php

                    if (
                        !is_array(
                            $categoria
                        )
                    ) {

                        continue;

                    }


                    $categoriaId =
                        manualIdValido(
                            $categoria["id"]
                            ?? ""
                        );


                    if (
                        $categoriaId === null
                    ) {

                        continue;

                    }

                    ?>


                    <option
                        value="<?= manualEscapar(
                            $categoriaId
                        ) ?>"
                    >

                        <?= manualEscapar(
                            $categoria["titulo"]
                            ?? ""
                        ) ?>

                    </option>


                <?php endforeach; ?>


            </select>

        </div>


        <div class="manual-campo">

            <label
                for="manual-nivel"
            >

                Nível

            </label>


            <select
                id="manual-nivel"
            >

                <option value="">

                    Todos os níveis

                </option>


                <option value="Fundamental">

                    Fundamental

                </option>


                <option value="Intermediário">

                    Intermediário

                </option>


                <option value="Avançado">

                    Avançado

                </option>

            </select>

        </div>

    </div>


    <div class="manual-acoes">

        <button
            type="button"
            id="manual-expandir"
            class="aula-botao manual-controle"
        >

            Expandir visíveis

        </button>


        <button
            type="button"
            id="manual-fechar"
            class="aula-botao manual-controle"
        >

            Fechar todos

        </button>


        <button
            type="button"
            id="manual-limpar"
            class="aula-botao manual-controle manual-controle-secundario"
        >

            Limpar filtros

        </button>

    </div>


    <p
        id="manual-contador"
        class="manual-resultado"
        aria-live="polite"
    >

        Exibindo

        <?= manualEscapar(
            $totalTopicos
        ) ?>

        de

        <?= manualEscapar(
            $totalTopicos
        ) ?>

        tópicos.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CATEGORIAS
|--------------------------------------------------------------------------
-->

<div id="manual-lista">


<?php foreach (
    $categorias as $categoria
): ?>


    <?php

    if (
        !is_array(
            $categoria
        )
    ) {

        continue;

    }


    $categoriaId =
        manualIdValido(
            $categoria["id"]
            ?? ""
        );


    if (
        $categoriaId === null
    ) {

        error_log(
            "[ManualJava] Categoria ignorada por possuir ID inválido."
        );


        continue;

    }


    $topicos =
        $categoria["topicos"]
        ?? [];


    if (
        !is_array(
            $topicos
        )
    ) {

        $topicos =
            [];

    }

    ?>


    <section
        class="aula-secao manual-categoria"
        data-categoria="<?= manualEscapar(
            $categoriaId
        ) ?>"
    >


        <div class="manual-categoria-cabecalho">


            <div class="manual-categoria-texto">


                <span class="manual-nivel">

                    <?= manualEscapar(
                        $categoria["nivel"]
                        ?? ""
                    ) ?>

                </span>


                <h2>

                    <?= manualEscapar(
                        $categoria["titulo"]
                        ?? ""
                    ) ?>

                </h2>


                <p>

                    <?= manualEscapar(
                        $categoria["descricao"]
                        ?? ""
                    ) ?>

                </p>


            </div>


        </div>


        <div class="manual-grade">


        <?php foreach (
            $topicos as $topico
        ): ?>


            <?php

            if (
                !is_array(
                    $topico
                )
            ) {

                continue;

            }


            $topicoId =
                manualIdValido(
                    $topico["id"]
                    ?? ""
                );


            if (
                $topicoId === null
            ) {

                error_log(
                    "[ManualJava] Tópico ignorado por possuir ID inválido."
                );


                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | ID ÚNICO DO CONTEÚDO
            |--------------------------------------------------------------------------
            */

            $conteudoId =
                "conteudo-" .
                $categoriaId .
                "-" .
                $topicoId;


            /*
            |--------------------------------------------------------------------------
            | TEXTO DE PESQUISA
            |--------------------------------------------------------------------------
            */

            $textoPesquisa =
                (
                    $categoria["titulo"]
                    ?? ""
                ) .
                " " .
                (
                    $categoria["descricao"]
                    ?? ""
                ) .
                " " .
                (
                    $topico["titulo"]
                    ?? ""
                ) .
                " " .
                (
                    $topico["descricao"]
                    ?? ""
                ) .
                " " .
                (
                    $topico["nivel"]
                    ?? ""
                );


            $textoPesquisa =
                manualTextoPesquisa(
                    $textoPesquisa
                );

            ?>


            <article
                class="manual-topico"
                data-categoria="<?= manualEscapar(
                    $categoriaId
                ) ?>"
                data-nivel="<?= manualEscapar(
                    $topico["nivel"]
                    ?? ""
                ) ?>"
                data-pesquisa="<?= manualEscapar(
                    $textoPesquisa
                ) ?>"
            >


                <button
                    type="button"
                    class="manual-topico-botao"
                    aria-expanded="false"
                    aria-controls="<?= manualEscapar(
                        $conteudoId
                    ) ?>"
                >


                    <span class="manual-topico-texto">


                        <span class="manual-topico-nivel">

                            <?= manualEscapar(
                                $topico["nivel"]
                                ?? ""
                            ) ?>

                        </span>


                        <strong>

                            <?= manualEscapar(
                                $topico["titulo"]
                                ?? ""
                            ) ?>

                        </strong>


                        <small>

                            <?= manualEscapar(
                                $topico["descricao"]
                                ?? ""
                            ) ?>

                        </small>


                    </span>


                    <span
                        class="manual-topico-seta"
                        aria-hidden="true"
                    >

                        +

                    </span>


                </button>


                <div
                    id="<?= manualEscapar(
                        $conteudoId
                    ) ?>"
                    class="manual-topico-conteudo"
                    hidden
                >

                    <?php

                    /*
                    |--------------------------------------------------------------------------
                    | CONTEÚDO DO CATÁLOGO
                    |--------------------------------------------------------------------------
                    |
                    | Este HTML vem exclusivamente do arquivo interno:
                    |
                    | dados/topicos.php
                    |
                    | Não vem de entrada do usuário.
                    |
                    */

                    echo (
                        $topico["conteudo"]
                        ?? ""
                    );

                    ?>

                </div>


            </article>


        <?php endforeach; ?>


        </div>


    </section>


<?php endforeach; ?>


</div>


<!--
|--------------------------------------------------------------------------
| SEM RESULTADOS
|--------------------------------------------------------------------------
-->

<section
    id="manual-vazio"
    class="aula-secao manual-vazio"
    hidden
>

    <h2>

        Nenhum tópico encontrado

    </h2>


    <p>

        Tente utilizar outra palavra ou remover
        algum dos filtros selecionados.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| NAVEGAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-acoes">


    <a
        href="<?= manualEscapar(
            $linkConteudos
        ) ?>"
        class="aula-botao"
    >

        ← Voltar para conteúdos

    </a>


    <a
        href="<?= manualEscapar(
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


<script
    src="<?= BASE_URL ?>/frontend/assets/js/manual.js?v=<?= $versaoManualJs ?>"
></script>


</body>

</html>