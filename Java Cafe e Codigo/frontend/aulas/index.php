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
| ROTAS
|--------------------------------------------------------------------------
*/

$linkLogin =
    rota(
        "login"
    );


$linkPainel =
    rota(
        "estudante.painel"
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

$usuario =
    $_SESSION["usuario"];


$usuarioId =
    (int) (
        $usuario["id"]
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| VALIDA USUÁRIO
|--------------------------------------------------------------------------
*/

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
| LOCALIZA AS AULAS CONVERSADAS
|--------------------------------------------------------------------------
*/

$arquivosAulas =
    glob(
        __DIR__ .
        "/../../backend/aulas/dados/aula[0-9][0-9].php"
    ) ?: [];


/*
|--------------------------------------------------------------------------
| MONTA LISTA DE AULAS
|--------------------------------------------------------------------------
*/

$aulas = [];


foreach (
    $arquivosAulas as $arquivoAula
) {

    /*
    |--------------------------------------------------------------------------
    | CARREGA DADOS DA AULA
    |--------------------------------------------------------------------------
    */

    try {

        $dadosAula =
            require $arquivoAula;

    }
    catch (Throwable $erro) {

        error_log(
            "[AulasConversadas] Erro ao carregar arquivo " .
            basename($arquivoAula) .
            ": " .
            $erro->getMessage()
        );


        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDA ESTRUTURA
    |--------------------------------------------------------------------------
    */

    if (
        !is_array(
            $dadosAula
        )
    ) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | ID PELOS DADOS DA AULA
    |--------------------------------------------------------------------------
    */

    $aulaId =
        (int) (
            $dadosAula["id"]
            ?? 0
        );


    /*
    |--------------------------------------------------------------------------
    | FALLBACK PELO NOME DO ARQUIVO
    |--------------------------------------------------------------------------
    |
    | Exemplo:
    |
    | aula01.php
    |
    | vira:
    |
    | 1
    |
    */

    if (
        $aulaId <= 0
    ) {

        $nomeArquivo =
            basename(
                $arquivoAula
            );


        if (
            preg_match(
                "/^aula([0-9]{2})\.php$/i",
                $nomeArquivo,
                $resultado
            )
        ) {

            $aulaId =
                (int) $resultado[1];

        }

    }


    /*
    |--------------------------------------------------------------------------
    | IGNORA ID INVÁLIDO
    |--------------------------------------------------------------------------
    */

    if (
        $aulaId <= 0
    ) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | DADOS DA AULA
    |--------------------------------------------------------------------------
    */

    $aulas[
        $aulaId
    ] = [

        "id" =>
            $aulaId,

        "titulo" =>
            trim(
                (string) (
                    $dadosAula["titulo"]
                    ?? "Aula Conversada"
                )
            ),

        "descricao" =>
            trim(
                (string) (
                    $dadosAula["descricao"]
                    ?? ""
                )
            )

    ];

}


/*
|--------------------------------------------------------------------------
| ORDENA PELO NÚMERO
|--------------------------------------------------------------------------
*/

ksort(
    $aulas,
    SORT_NUMERIC
);


/*
|--------------------------------------------------------------------------
| TOTAL DE AULAS
|--------------------------------------------------------------------------
*/

$totalAulas =
    count(
        $aulas
    );


/*
|--------------------------------------------------------------------------
| BUSCA PROGRESSO DO USUÁRIO
|--------------------------------------------------------------------------
*/

$progressoPorAula = [];


if (
    isset($pdo)
    &&
    $pdo instanceof PDO
) {

    try {

        $sql = "

            SELECT

                aula_id,
                etapa_atual,
                concluida

            FROM aula_progresso_conversado

            WHERE
                usuario_id = :usuario_id

        ";


        $stmt =
            $pdo->prepare(
                $sql
            );


        $stmt->execute([

            ":usuario_id" =>
                $usuarioId

        ]);


        $registros =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        /*
        |--------------------------------------------------------------------------
        | ORGANIZA PROGRESSO POR ID DA AULA
        |--------------------------------------------------------------------------
        */

        foreach (
            $registros as $registro
        ) {

            $id =
                (int) (
                    $registro["aula_id"]
                    ?? 0
                );


            if (
                $id <= 0
            ) {

                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | IGNORA PROGRESSO DE AULA QUE NÃO EXISTE MAIS
            |--------------------------------------------------------------------------
            */

            if (
                !isset(
                    $aulas[$id]
                )
            ) {

                continue;

            }


            $progressoPorAula[
                $id
            ] = [

                "etapa_atual" =>
                    max(
                        1,
                        (int) (
                            $registro["etapa_atual"]
                            ?? 1
                        )
                    ),

                "concluida" =>
                    (int) (
                        $registro["concluida"]
                        ?? 0
                    ) === 1

            ];

        }

    }
    catch (Throwable $erro) {

        error_log(
            "[AulasConversadas] Erro ao buscar progresso do usuário " .
            $usuarioId .
            ": " .
            $erro->getMessage()
        );

    }

}


/*
|--------------------------------------------------------------------------
| ESTATÍSTICAS
|--------------------------------------------------------------------------
*/

$totalConcluidas =
    0;


$totalAndamento =
    0;


foreach (
    $aulas as $aula
) {

    $id =
        (int) $aula["id"];


    /*
    |--------------------------------------------------------------------------
    | NÃO INICIADA
    |--------------------------------------------------------------------------
    */

    if (
        !isset(
            $progressoPorAula[$id]
        )
    ) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | CONCLUÍDA
    |--------------------------------------------------------------------------
    */

    if (
        $progressoPorAula[$id]["concluida"]
    ) {

        $totalConcluidas++;

    }
    else {

        /*
        |--------------------------------------------------------------------------
        | EM ANDAMENTO
        |--------------------------------------------------------------------------
        */

        $totalAndamento++;

    }

}


/*
|--------------------------------------------------------------------------
| PERCENTUAL GERAL
|--------------------------------------------------------------------------
*/

$percentualGeral =
    $totalAulas > 0
        ?
        (int) round(
            (
                $totalConcluidas
                /
                $totalAulas
            )
            * 100
        )
        :
        0;


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function aulasConversadasEscapar(
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
    content="Lista de Aulas Conversadas da plataforma Java, Café e Código."
>

<title>
    Aulas Conversadas | <?= SITE_NAME ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= filemtime(
        __DIR__ .
        "/../assets/css/style.css"
    ) ?>"
>


<!-- =====================================================
PADRÃO VISUAL DAS AULAS
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/aula.css?v=<?= filemtime(
        __DIR__ .
        "/../assets/css/aula.css"
    ) ?>"
>

</head>


<body>


<?php

require_once __DIR__ .
    "/../componentes/header.php";

?>


<main class="aula-container">


<!-- =====================================================
CABEÇALHO
====================================================== -->

<section class="aula-cabecalho">


    <span class="aula-numero">

        APRENDIZAGEM INTERATIVA

    </span>


    <h1>

        Aulas Conversadas

    </h1>


    <p>

        Escolha uma aula para estudar conceitos de Java
        através de uma experiência interativa com os tutores
        disponíveis na plataforma.

    </p>


</section>


<!-- =====================================================
PROGRESSO GERAL
====================================================== -->

<section class="aula-secao">


    <h2>

        Seu progresso

    </h2>


    <p>

        Você concluiu

        <strong>
            <?= $totalConcluidas ?>
        </strong>

        de

        <strong>
            <?= $totalAulas ?>
        </strong>

        Aulas Conversadas.

    </p>


    <?php if (
        $totalAndamento > 0
    ): ?>


        <p>

            Você também possui

            <strong>
                <?= $totalAndamento ?>
            </strong>

            <?= $totalAndamento === 1
                ?
                "aula em andamento."
                :
                "aulas em andamento."
            ?>

        </p>


    <?php endif; ?>


    <div
        class="resultado-codigo"
        aria-label="Progresso geral das Aulas Conversadas"
    >

        <?= $percentualGeral ?>% concluído

    </div>


</section>


<!-- =====================================================
LISTA DAS AULAS
====================================================== -->

<?php if (
    $totalAulas > 0
): ?>


    <?php foreach (
        $aulas as $aula
    ): ?>


        <?php

        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        $id =
            (int) $aula["id"];


        /*
        |--------------------------------------------------------------------------
        | NÚMERO FORMATADO
        |--------------------------------------------------------------------------
        */

        $numero =
            str_pad(
                (string) $id,
                2,
                "0",
                STR_PAD_LEFT
            );


        /*
        |--------------------------------------------------------------------------
        | PROGRESSO
        |--------------------------------------------------------------------------
        */

        $progresso =
            $progressoPorAula[$id]
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | STATUS E AÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            $progresso
            &&
            $progresso["concluida"]
        ) {

            $status =
                "Concluída";


            $acao =
                "Revisar aula";

        }
        elseif (
            $progresso
        ) {

            $status =
                "Em andamento";


            $acao =
                "Continuar aula";

        }
        else {

            $status =
                "Não iniciada";


            $acao =
                "Iniciar aula";

        }


        /*
        |--------------------------------------------------------------------------
        | URL LIMPA DA AULA
        |--------------------------------------------------------------------------
        |
        | Antes:
        |
        | /frontend/aulas/aula.php?id=7
        |
        | Agora:
        |
        | /aulas/7
        |
        */

        $linkAula =
            rota(
                "aulas.ver",
                [
                    "id" =>
                        $id
                ]
            );

        ?>


        <section class="aula-secao">


            <span class="aula-numero">

                AULA CONVERSADA

                <?= aulasConversadasEscapar(
                    $numero
                ) ?>

            </span>


            <h2>

                <?= aulasConversadasEscapar(
                    $aula["titulo"]
                ) ?>

            </h2>


            <?php if (
                $aula["descricao"]
                !== ""
            ): ?>


                <p>

                    <?= aulasConversadasEscapar(
                        $aula["descricao"]
                    ) ?>

                </p>


            <?php endif; ?>


            <!-- =================================================
            STATUS
            ================================================== -->

            <div class="aula-destaque">


                <strong>

                    <?= aulasConversadasEscapar(
                        $status
                    ) ?>

                </strong>


                <?php if (
                    $progresso
                    &&
                    !$progresso["concluida"]
                ): ?>


                    <p>

                        Você parou na etapa

                        <strong>

                            <?= (int) $progresso["etapa_atual"] ?>

                        </strong>

                        desta aula.

                    </p>


                <?php elseif (
                    $progresso
                    &&
                    $progresso["concluida"]
                ): ?>


                    <p>

                        Esta Aula Conversada já foi concluída
                        por você.

                    </p>


                <?php else: ?>


                    <p>

                        Você ainda não iniciou esta Aula Conversada.

                    </p>


                <?php endif; ?>


            </div>


            <!-- =================================================
            ABRIR AULA
            ================================================== -->

            <div class="aula-acoes">


                <a
                    href="<?= aulasConversadasEscapar(
                        $linkAula
                    ) ?>"
                    class="aula-botao"
                >

                    <?= aulasConversadasEscapar(
                        $acao
                    ) ?>

                    →

                </a>


            </div>


        </section>


    <?php endforeach; ?>


<?php else: ?>


    <!-- =====================================================
    SEM AULAS
    ====================================================== -->

    <section class="aula-secao">


        <h2>

            Nenhuma aula disponível

        </h2>


        <p>

            Ainda não existem Aulas Conversadas disponíveis
            nesta versão da plataforma.

        </p>


    </section>


<?php endif; ?>


<!-- =====================================================
NAVEGAÇÃO
====================================================== -->

<section class="aula-acoes">


    <a
        href="<?= aulasConversadasEscapar(
            $linkPainel
        ) ?>"
        class="aula-botao"
    >

        ← Voltar para o painel

    </a>


</section>


</main>


<?php

require_once __DIR__ .
    "/../componentes/footer.php";

?>


<!-- =====================================================
TEMA
====================================================== -->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= filemtime(
        __DIR__ .
        "/../assets/js/theme.js"
    ) ?>"
></script>


</body>

</html>