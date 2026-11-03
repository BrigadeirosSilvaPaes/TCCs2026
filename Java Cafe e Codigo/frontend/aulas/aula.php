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


$linkAulas =
    rota(
        "aulas"
    );


$linkConcluirAula =
    rota(
        "acao.aula.concluir"
    );


$linkSalvarProgresso =
    rota(
        "acao.aula.progresso"
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

$usuarioId =
    (int) (
        $_SESSION["usuario"]["id"]
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
| LOCALIZA TODAS AS AULAS DISPONÍVEIS
|--------------------------------------------------------------------------
|
| Precisamos dessa lista antes mesmo de saber qual aula abrir.
|
| Isso permite que:
|
| /aulas/iniciar
|
| escolha automaticamente a melhor aula para o estudante.
|
*/

$arquivosAulas =
    glob(
        __DIR__ .
        "/../../backend/aulas/dados/aula[0-9][0-9].php"
    ) ?: [];


$idsDisponiveis =
    [];


/*
|--------------------------------------------------------------------------
| EXTRAI IDS
|--------------------------------------------------------------------------
*/

foreach (
    $arquivosAulas as $arquivo
) {

    $nome =
        basename(
            $arquivo
        );


    if (
        preg_match(
            "/^aula([0-9]{2})\.php$/i",
            $nome,
            $resultado
        ) !== 1
    ) {

        continue;

    }


    $idEncontrado =
        (int) $resultado[1];


    if (
        $idEncontrado <= 0
        ||
        $idEncontrado > 99
    ) {

        continue;

    }


    $idsDisponiveis[] =
        $idEncontrado;

}


/*
|--------------------------------------------------------------------------
| REMOVE DUPLICADOS
|--------------------------------------------------------------------------
*/

$idsDisponiveis =
    array_values(
        array_unique(
            $idsDisponiveis
        )
    );


/*
|--------------------------------------------------------------------------
| ORDENA
|--------------------------------------------------------------------------
*/

sort(
    $idsDisponiveis,
    SORT_NUMERIC
);


/*
|--------------------------------------------------------------------------
| NENHUMA AULA DISPONÍVEL
|--------------------------------------------------------------------------
*/

if (
    empty(
        $idsDisponiveis
    )
) {

    header(
        "Location: " .
        $linkAulas
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| IDENTIFICA A AULA SOLICITADA
|--------------------------------------------------------------------------
|
| Existem dois cenários:
|
| 1.
|
| /aulas/7
|
| O router fornece:
|
| $_GET["id"] = 7
|
|
| 2.
|
| /aulas/iniciar
|
| Não existe ID.
|
| Nesse caso escolheremos automaticamente a aula ideal.
|
*/

$aulaId =
    (int) (
        $_GET["id"]
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| /AULAS/INICIAR — ESCOLHA AUTOMÁTICA
|--------------------------------------------------------------------------
*/

if (
    $aulaId <= 0
) {

    /*
    |--------------------------------------------------------------------------
    | PROGRESSO DO USUÁRIO
    |--------------------------------------------------------------------------
    */

    $progressoUsuario =
        [];


    if (
        isset(
            $pdo
        )
        &&
        $pdo instanceof PDO
    ) {

        try {

            $stmtProgressoGeral =
                $pdo->prepare("

                    SELECT

                        aula_id,
                        etapa_atual,
                        concluida

                    FROM aula_progresso_conversado

                    WHERE
                        usuario_id = :usuario_id

                ");


            $stmtProgressoGeral->execute([

                ":usuario_id" =>
                    $usuarioId

            ]);


            $registrosProgresso =
                $stmtProgressoGeral->fetchAll(
                    PDO::FETCH_ASSOC
                );


            /*
            |--------------------------------------------------------------------------
            | ORGANIZA POR AULA
            |--------------------------------------------------------------------------
            */

            foreach (
                $registrosProgresso as $registro
            ) {

                $idProgresso =
                    (int) (
                        $registro["aula_id"]
                        ?? 0
                    );


                if (
                    $idProgresso <= 0
                ) {

                    continue;

                }


                /*
                |--------------------------------------------------------------------------
                | IGNORA AULAS QUE NÃO EXISTEM MAIS
                |--------------------------------------------------------------------------
                */

                if (
                    !in_array(
                        $idProgresso,
                        $idsDisponiveis,
                        true
                    )
                ) {

                    continue;

                }


                $progressoUsuario[
                    $idProgresso
                ] = [

                    "etapa_atual" =>
                        max(
                            1,
                            min(
                                4,
                                (int) (
                                    $registro["etapa_atual"]
                                    ?? 1
                                )
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

            /*
            |--------------------------------------------------------------------------
            | LOG
            |--------------------------------------------------------------------------
            |
            | Se a consulta falhar, ainda conseguimos mandar
            | o estudante para a primeira aula disponível.
            |
            */

            error_log(
                "[AulasIniciar] Erro ao buscar progresso do usuário " .
                $usuarioId .
                ": " .
                $erro->getMessage()
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | PRIORIDADE 1 — AULA EM ANDAMENTO
    |--------------------------------------------------------------------------
    |
    | Procuramos a primeira aula que:
    |
    | - possui registro;
    | - ainda não foi concluída.
    |
    */

    $aulaDestino =
        null;


    foreach (
        $idsDisponiveis as $idDisponivel
    ) {

        if (
            isset(
                $progressoUsuario[
                    $idDisponivel
                ]
            )
            &&
            !$progressoUsuario[
                $idDisponivel
            ]["concluida"]
        ) {

            $aulaDestino =
                $idDisponivel;

            break;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | PRIORIDADE 2 — PRIMEIRA AULA NÃO CONCLUÍDA
    |--------------------------------------------------------------------------
    |
    | Se não há nenhuma em andamento, procuramos a primeira
    | aula que ainda não foi concluída.
    |
    | Uma aula sem registro significa:
    |
    | "não iniciada".
    |
    */

    if (
        $aulaDestino === null
    ) {

        foreach (
            $idsDisponiveis as $idDisponivel
        ) {

            /*
            |--------------------------------------------------------------------------
            | SEM REGISTRO
            |--------------------------------------------------------------------------
            */

            if (
                !isset(
                    $progressoUsuario[
                        $idDisponivel
                    ]
                )
            ) {

                $aulaDestino =
                    $idDisponivel;

                break;

            }


            /*
            |--------------------------------------------------------------------------
            | REGISTRO EXISTE MAS NÃO ESTÁ CONCLUÍDA
            |--------------------------------------------------------------------------
            */

            if (
                !$progressoUsuario[
                    $idDisponivel
                ]["concluida"]
            ) {

                $aulaDestino =
                    $idDisponivel;

                break;

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | TODAS AS AULAS CONCLUÍDAS
    |--------------------------------------------------------------------------
    */

    if (
        $aulaDestino === null
    ) {

        header(
            "Location: " .
            $linkAulas
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | REDIRECIONA PARA URL LIMPA
    |--------------------------------------------------------------------------
    |
    | Exemplo:
    |
    | /aulas/iniciar
    |
    | vira:
    |
    | /aulas/4
    |
    */

    header(
        "Location: " .
        rota(
            "aulas.ver",
            [
                "id" =>
                    $aulaDestino
            ]
        )
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| AULA ESPECÍFICA
|--------------------------------------------------------------------------
|
| Daqui para baixo estamos em:
|
| /aulas/{id}
|
*/


/*
|--------------------------------------------------------------------------
| VALIDA ID
|--------------------------------------------------------------------------
*/

if (
    $aulaId > 99
    ||
    !in_array(
        $aulaId,
        $idsDisponiveis,
        true
    )
) {

    header(
        "Location: " .
        $linkAulas
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| NOME DO ARQUIVO
|--------------------------------------------------------------------------
*/

$nomeArquivo =
    "aula" .
    str_pad(
        (string) $aulaId,
        2,
        "0",
        STR_PAD_LEFT
    )
    .
    ".php";


/*
|--------------------------------------------------------------------------
| CAMINHO INTERNO
|--------------------------------------------------------------------------
*/

$caminhoAula =
    __DIR__ .
    "/../../backend/aulas/dados/" .
    $nomeArquivo;


/*
|--------------------------------------------------------------------------
| VERIFICA EXISTÊNCIA
|--------------------------------------------------------------------------
*/

if (
    !is_file(
        $caminhoAula
    )
) {

    header(
        "Location: " .
        $linkAulas
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| CARREGA AULA
|--------------------------------------------------------------------------
*/

try {

    $aula =
        require $caminhoAula;

}
catch (Throwable $erro) {

    error_log(
        "[AulaConversada] Erro ao carregar aula " .
        $aulaId .
        ": " .
        $erro->getMessage()
    );


    header(
        "Location: " .
        $linkAulas
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDA ESTRUTURA
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $aula
    )
    ||
    !isset(
        $aula["interacoes"]
    )
    ||
    !is_array(
        $aula["interacoes"]
    )
    ||
    !isset(
        $aula["inicio"]
    )
) {

    error_log(
        "[AulaConversada] Estrutura inválida na aula " .
        $aulaId .
        "."
    );


    header(
        "Location: " .
        $linkAulas
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDA ID INTERNO
|--------------------------------------------------------------------------
*/

$idInternoAula =
    (int) (
        $aula["id"]
        ?? $aulaId
    );


if (
    $idInternoAula !== $aulaId
) {

    error_log(
        "[AulaConversada] ID divergente. URL: " .
        $aulaId .
        ", arquivo: " .
        $idInternoAula .
        "."
    );


    header(
        "Location: " .
        $linkAulas
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| ID OFICIAL
|--------------------------------------------------------------------------
*/

$aula["id"] =
    $aulaId;


/*
|--------------------------------------------------------------------------
| PROGRESSO DA AULA ATUAL
|--------------------------------------------------------------------------
*/

$progressoAula = [

    "iniciada" =>
        false,

    "etapa_atual" =>
        1,

    "concluida" =>
        false

];


/*
|--------------------------------------------------------------------------
| BUSCA PROGRESSO
|--------------------------------------------------------------------------
*/

if (
    isset(
        $pdo
    )
    &&
    $pdo instanceof PDO
) {

    try {

        $stmtProgresso =
            $pdo->prepare("

                SELECT

                    etapa_atual,
                    concluida

                FROM aula_progresso_conversado

                WHERE
                    usuario_id = :usuario_id

                    AND

                    aula_id = :aula_id

                LIMIT 1

            ");


        $stmtProgresso->execute([

            ":usuario_id" =>
                $usuarioId,

            ":aula_id" =>
                $aulaId

        ]);


        $registroProgresso =
            $stmtProgresso->fetch(
                PDO::FETCH_ASSOC
            );


        if (
            $registroProgresso
        ) {

            $etapaAtual =
                max(
                    1,
                    min(
                        4,
                        (int) (
                            $registroProgresso["etapa_atual"]
                            ?? 1
                        )
                    )
                );


            $progressoAula = [

                "iniciada" =>
                    true,

                "etapa_atual" =>
                    $etapaAtual,

                "concluida" =>
                    (int) (
                        $registroProgresso["concluida"]
                        ?? 0
                    ) === 1

            ];

        }

    }
    catch (Throwable $erro) {

        error_log(
            "[AulaConversada] Erro ao buscar progresso da aula " .
            $aulaId .
            " para usuário " .
            $usuarioId .
            ": " .
            $erro->getMessage()
        );

    }

}


/*
|--------------------------------------------------------------------------
| ENTREGA PROGRESSO AO JAVASCRIPT
|--------------------------------------------------------------------------
*/

$aula["progresso"] =
    $progressoAula;


/*
|--------------------------------------------------------------------------
| PROCURA PRÓXIMA AULA
|--------------------------------------------------------------------------
*/

$proximaAulaId =
    null;


foreach (
    $idsDisponiveis as $idDisponivel
) {

    if (
        $idDisponivel > $aulaId
    ) {

        $proximaAulaId =
            $idDisponivel;

        break;

    }

}


/*
|--------------------------------------------------------------------------
| PRÓXIMA AULA — ID
|--------------------------------------------------------------------------
*/

$aula["proxima_aula"] =
    $proximaAulaId;


/*
|--------------------------------------------------------------------------
| PRÓXIMA AULA — URL
|--------------------------------------------------------------------------
*/

$aula["proxima_aula_url"] =
    $proximaAulaId !== null
        ?
        rota(
            "aulas.ver",
            [
                "id" =>
                    $proximaAulaId
            ]
        )
        :
        null;


/*
|--------------------------------------------------------------------------
| URL DA AULA ATUAL
|--------------------------------------------------------------------------
*/

$aula["url"] =
    rota(
        "aulas.ver",
        [
            "id" =>
                $aulaId
        ]
    );


/*
|--------------------------------------------------------------------------
| URL DA LISTA
|--------------------------------------------------------------------------
*/

$aula["lista_aulas_url"] =
    $linkAulas;


/*
|--------------------------------------------------------------------------
| URL PARA SALVAR PROGRESSO
|--------------------------------------------------------------------------
*/

$aula["progresso_url"] =
    $linkSalvarProgresso;


/*
|--------------------------------------------------------------------------
| URL PARA CONCLUIR AULA
|--------------------------------------------------------------------------
*/

$aula["concluir_url"] =
    $linkConcluirAula;


/*
|--------------------------------------------------------------------------
| NÚMERO VISUAL
|--------------------------------------------------------------------------
*/

$numeroAula =
    str_pad(
        (string) $aulaId,
        2,
        "0",
        STR_PAD_LEFT
    );


/*
|--------------------------------------------------------------------------
| ESCAPA HTML
|--------------------------------------------------------------------------
*/

function aulaConversadaEscapar(
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
    content="Aula Conversada interativa da plataforma Java, Café e Código."
>

<title>

    <?= aulaConversadaEscapar(
        $aula["titulo"]
        ?? "Aula Conversada"
    ) ?>

    | <?= SITE_NAME ?>

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
PADRÃO DAS AULAS
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/aula.css?v=<?= filemtime(
        __DIR__ .
        "/../assets/css/aula.css"
    ) ?>"
>


<!-- =====================================================
AULA CONVERSADA
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/aula_conversada.css?v=<?= filemtime(
        __DIR__ .
        "/../assets/css/aula_conversada.css"
    ) ?>"
>

</head>


<body>


<?php

require_once __DIR__ .
    "/../componentes/header.php";

?>


<main class="aula-conversada">


<!-- =====================================================
PROGRESSO
====================================================== -->

<section class="aula-topo">


    <a
        class="aula-sair"
        href="<?= aulaConversadaEscapar(
            $linkAulas
        ) ?>"
        aria-label="Sair da aula"
        title="Sair da aula"
    >

        ×

    </a>


    <div class="aula-progresso">


        <div
            id="barra-progresso"
            class="aula-progresso-preenchimento"
        ></div>


    </div>


    <span
        id="texto-progresso"
        class="aula-progresso-texto"
    >

        0%

    </span>


</section>


<!-- =====================================================
CABEÇALHO
====================================================== -->

<section class="aula-conversada-cabecalho">


    <span class="aula-conversada-numero">

        Aula Conversada
        <?= aulaConversadaEscapar(
            $numeroAula
        ) ?>

    </span>


    <h1>

        <?= aulaConversadaEscapar(
            $aula["titulo"]
            ?? "Aula Conversada"
        ) ?>

    </h1>


    <?php if (
        !empty(
            $aula["descricao"]
        )
    ): ?>


        <p>

            <?= aulaConversadaEscapar(
                $aula["descricao"]
            ) ?>

        </p>


    <?php endif; ?>


</section>


<!-- =====================================================
ESCOLHA DO TUTOR
====================================================== -->

<section
    id="tela-tutor"
    class="tela-tutor"
>


    <div class="tela-tutor-conteudo">


        <span class="aula-etiqueta">

            Antes de começar

        </span>


        <h2>

            Como tu prefere aprender?

        </h2>


        <p>

            Escolhe o estilo de explicação que vai te acompanhar
            durante esta aula.

        </p>


        <div class="tutores-grid">


            <button
                type="button"
                class="tutor-card"
                data-tutor="robert"
            >

                <strong>
                    Robert
                </strong>

                <span>
                    Direto e prático
                </span>

                <p>

                    Explicações mais rápidas, exemplos simples
                    e comparações diretas com situações do dia a dia.

                </p>

            </button>


            <button
                type="button"
                class="tutor-card"
                data-tutor="natanael"
            >

                <strong>
                    Natanael
                </strong>

                <span>
                    Detalhado e passo a passo
                </span>

                <p>

                    Desenvolve mais o raciocínio, explica o motivo
                    e conecta cada conceito com exemplos do cotidiano.

                </p>

            </button>


        </div>


    </div>


</section>


<!-- =====================================================
TELA DA AULA
====================================================== -->

<section
    id="tela-aula"
    class="tela-aula"
    hidden
>


    <div class="aula-layout">


        <div class="mascote-area">


            <img
                id="mascote-jcc"
                class="mascote-jcc"
                src="<?= BASE_URL ?>/frontend/assets/img/mascote/jcc-pergunta.png"
                alt="Mascote J.C.C."
            >


        </div>


        <div class="conversa-area">


            <span
                id="tipo-interacao"
                class="interacao-etiqueta"
                hidden
            ></span>


            <h2
                id="interacao-titulo"
            ></h2>


            <p
                id="interacao-mensagem"
                class="interacao-mensagem"
            ></p>


            <div
                id="codigo-area"
                hidden
            >

                <pre class="codigo-java"><code id="codigo-conteudo"></code></pre>

            </div>


            <p
                id="pergunta-extra"
                class="pergunta-extra"
                hidden
            ></p>


            <div
                id="opcoes-resposta"
                class="opcoes-resposta"
            ></div>


            <button
                id="botao-continuar"
                class="botao-continuar"
                type="button"
                hidden
            >

                Continuar

            </button>


        </div>


    </div>


</section>


</main>


<?php

require_once __DIR__ .
    "/../componentes/footer.php";

?>


<!-- =====================================================
DADOS PHP → JAVASCRIPT
====================================================== -->

<script>

window.BASE_URL =
    <?= json_encode(
        BASE_URL,
        JSON_UNESCAPED_SLASHES |
        JSON_HEX_TAG |
        JSON_HEX_AMP |
        JSON_HEX_APOS |
        JSON_HEX_QUOT
    ) ?>;


window.AULA_CONVERSADA =
    <?= json_encode(
        $aula,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_HEX_TAG |
        JSON_HEX_AMP |
        JSON_HEX_APOS |
        JSON_HEX_QUOT
    ) ?>;

</script>


<!-- =====================================================
TEMA
====================================================== -->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= filemtime(
        __DIR__ .
        "/../assets/js/theme.js"
    ) ?>"
></script>


<!-- =====================================================
AULA CONVERSADA
====================================================== -->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/aula_conversada.js?v=<?= filemtime(
        __DIR__ .
        "/../assets/js/aula_conversada.js"
    ) ?>"
></script>


</body>

</html>