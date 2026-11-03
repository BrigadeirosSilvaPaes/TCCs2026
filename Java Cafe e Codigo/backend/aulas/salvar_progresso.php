<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/bootstrap.php";


/*
|--------------------------------------------------------------------------
| RESPOSTA JSON
|--------------------------------------------------------------------------
*/

header(
    "Content-Type: application/json; charset=UTF-8"
);

header(
    "Cache-Control: no-store, no-cache, must-revalidate, max-age=0"
);

header(
    "Pragma: no-cache"
);

header(
    "X-Content-Type-Options: nosniff"
);


/*
|--------------------------------------------------------------------------
| RESPONDER
|--------------------------------------------------------------------------
*/

function responderProgressoJson(
    array $dados,
    int $status = 200
): never
{

    http_response_code(
        $status
    );


    echo json_encode(
        $dados,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| SOMENTE POST
|--------------------------------------------------------------------------
*/

if (
    (
        $_SERVER["REQUEST_METHOD"]
        ?? ""
    )
    !== "POST"
) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Método não permitido."
        ],
        405
    );

}


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

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Sua sessão expirou. Faça login novamente."
        ],
        401
    );

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


    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Não foi possível identificar o usuário."
        ],
        401
    );

}


/*
|--------------------------------------------------------------------------
| BANCO
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $pdo
    )
    ||
    !(
        $pdo instanceof PDO
    )
) {

    error_log(
        "[AulaProgresso] Conexão PDO indisponível."
    );


    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Serviço temporariamente indisponível."
        ],
        500
    );

}


/*
|--------------------------------------------------------------------------
| CONTENT-TYPE
|--------------------------------------------------------------------------
|
| Esperamos exclusivamente JSON.
|
| Exemplo:
|
| {
|     "aula_id": 3,
|     "etapa_atual": 2
| }
|
*/

$contentType =
    strtolower(
        trim(
            (string) (
                $_SERVER["CONTENT_TYPE"]
                ?? ""
            )
        )
    );


if (
    $contentType === ""
    ||
    !str_starts_with(
        $contentType,
        "application/json"
    )
) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Tipo de conteúdo inválido."
        ],
        415
    );

}


/*
|--------------------------------------------------------------------------
| PROTEÇÃO BÁSICA CONTRA REQUISIÇÃO CROSS-SITE
|--------------------------------------------------------------------------
|
| Quando o navegador fornece Sec-Fetch-Site,
| recusamos chamadas originadas de outro site.
|
| Isso é uma camada adicional.
|
| Posteriormente ainda podemos integrar o CSRF central.
|
*/

$secFetchSite =
    strtolower(
        trim(
            (string) (
                $_SERVER["HTTP_SEC_FETCH_SITE"]
                ?? ""
            )
        )
    );


if (
    $secFetchSite !== ""
    &&
    !in_array(
        $secFetchSite,
        [
            "same-origin",
            "same-site",
            "none"
        ],
        true
    )
) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Requisição não autorizada."
        ],
        403
    );

}


/*
|--------------------------------------------------------------------------
| TAMANHO MÁXIMO
|--------------------------------------------------------------------------
*/

$contentLength =
    (int) (
        $_SERVER["CONTENT_LENGTH"]
        ?? 0
    );


if (
    $contentLength > 2048
) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Dados enviados são muito grandes."
        ],
        413
    );

}


/*
|--------------------------------------------------------------------------
| RECEBE JSON
|--------------------------------------------------------------------------
*/

$conteudo =
    file_get_contents(
        "php://input"
    );


if (
    $conteudo === false
    ||
    trim(
        $conteudo
    ) === ""
) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Dados inválidos."
        ],
        400
    );

}


/*
|--------------------------------------------------------------------------
| DECODIFICA JSON
|--------------------------------------------------------------------------
*/

try {

    $dados =
        json_decode(
            $conteudo,
            true,
            16,
            JSON_THROW_ON_ERROR
        );

}
catch (JsonException $erro) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Dados inválidos."
        ],
        400
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA ESTRUTURA
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $dados
    )
) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Dados inválidos."
        ],
        400
    );

}


/*
|--------------------------------------------------------------------------
| AULA
|--------------------------------------------------------------------------
*/

$aulaId =
    filter_var(
        $dados["aula_id"]
        ?? null,
        FILTER_VALIDATE_INT
    );


if (
    $aulaId === false
    ||
    $aulaId === null
    ||
    $aulaId < 1
    ||
    $aulaId > 99
) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Aula inválida."
        ],
        400
    );

}


/*
|--------------------------------------------------------------------------
| ETAPA
|--------------------------------------------------------------------------
|
| As Aulas Conversadas atuais possuem quatro etapas principais:
|
| 1 -> pergunta 1
| 2 -> pergunta 2
| 3 -> pergunta 3
| 4 -> pergunta 4
|
| O estado "concluída" NÃO é definido aqui.
|
*/

$etapaAtual =
    filter_var(
        $dados["etapa_atual"]
        ?? null,
        FILTER_VALIDATE_INT
    );


if (
    $etapaAtual === false
    ||
    $etapaAtual === null
    ||
    $etapaAtual < 1
    ||
    $etapaAtual > 4
) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Etapa inválida."
        ],
        400
    );

}


/*
|--------------------------------------------------------------------------
| CONFIRMA QUE A AULA EXISTE
|--------------------------------------------------------------------------
*/

$arquivoAula =
    __DIR__
    .
    "/dados/aula"
    .
    str_pad(
        (string) $aulaId,
        2,
        "0",
        STR_PAD_LEFT
    )
    .
    ".php";


if (
    !is_file(
        $arquivoAula
    )
) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "A aula informada não existe."
        ],
        404
    );

}


/*
|--------------------------------------------------------------------------
| CONFIRMA ESTRUTURA DA AULA
|--------------------------------------------------------------------------
*/

try {

    $dadosAula =
        require $arquivoAula;

}
catch (Throwable $erro) {

    error_log(
        "[AulaProgresso] Erro ao carregar aula " .
        $aulaId .
        ": " .
        $erro->getMessage()
    );


    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Não foi possível validar a aula."
        ],
        500
    );

}


if (
    !is_array(
        $dadosAula
    )
    ||
    !isset(
        $dadosAula["interacoes"]
    )
    ||
    !isset(
        $dadosAula["inicio"]
    )
) {

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "A aula informada é inválida."
        ],
        400
    );

}


/*
|--------------------------------------------------------------------------
| CONFIRMA ID INTERNO
|--------------------------------------------------------------------------
*/

$idInternoAula =
    (int) (
        $dadosAula["id"]
        ?? $aulaId
    );


if (
    $idInternoAula !== $aulaId
) {

    error_log(
        "[AulaProgresso] ID divergente. Solicitado: " .
        $aulaId .
        ", arquivo: " .
        $idInternoAula .
        "."
    );


    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "A aula informada é inválida."
        ],
        400
    );

}


/*
|--------------------------------------------------------------------------
| SALVA PROGRESSO
|--------------------------------------------------------------------------
|
| Regras importantes:
|
| 1. Se ainda não existir progresso:
|    cria o registro.
|
| 2. Se já existir:
|    nunca diminui a etapa.
|
| 3. Se a aula já estiver concluída:
|    continua concluída.
|
| O navegador jamais pode utilizar este endpoint
| para alterar concluida para 1.
|
*/

try {

    $sql = "

        INSERT INTO aula_progresso_conversado
        (
            usuario_id,
            aula_id,
            etapa_atual,
            concluida
        )

        VALUES
        (
            :usuario_id,
            :aula_id,
            :etapa_atual,
            0
        )

        ON DUPLICATE KEY UPDATE

            etapa_atual =
                CASE

                    WHEN concluida = 1
                    THEN etapa_atual

                    ELSE GREATEST(
                        etapa_atual,
                        :etapa_atual_update
                    )

                END,

            atualizado_em =
                CURRENT_TIMESTAMP

    ";


    $stmt =
        $pdo->prepare(
            $sql
        );


    $stmt->execute([

        ":usuario_id" =>
            $usuarioId,

        ":aula_id" =>
            $aulaId,

        ":etapa_atual" =>
            $etapaAtual,

        ":etapa_atual_update" =>
            $etapaAtual

    ]);


    /*
    |--------------------------------------------------------------------------
    | BUSCA ESTADO REAL SALVO
    |--------------------------------------------------------------------------
    |
    | Não confiamos apenas no valor enviado pelo navegador.
    |
    */

    $stmtEstado =
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


    $stmtEstado->execute([

        ":usuario_id" =>
            $usuarioId,

        ":aula_id" =>
            $aulaId

    ]);


    $estadoSalvo =
        $stmtEstado->fetch(
            PDO::FETCH_ASSOC
        );


    if (
        !$estadoSalvo
    ) {

        throw new RuntimeException(
            "Registro de progresso não encontrado após salvamento."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZA RESPOSTA
    |--------------------------------------------------------------------------
    */

    $etapaSalva =
        max(
            1,
            (int) (
                $estadoSalvo["etapa_atual"]
                ?? 1
            )
        );


    $concluida =
        (int) (
            $estadoSalvo["concluida"]
            ?? 0
        ) === 1;


    /*
    |--------------------------------------------------------------------------
    | SUCESSO
    |--------------------------------------------------------------------------
    */

    responderProgressoJson(
        [
            "sucesso" =>
                true,

            "aula_id" =>
                $aulaId,

            "etapa_atual" =>
                $etapaSalva,

            "concluida" =>
                $concluida
        ]
    );

}
catch (Throwable $erro) {

    /*
    |--------------------------------------------------------------------------
    | LOG
    |--------------------------------------------------------------------------
    */

    error_log(
        "[AulaProgresso] Erro ao salvar aula " .
        $aulaId .
        " do usuário " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    /*
    |--------------------------------------------------------------------------
    | ERRO SEGURO
    |--------------------------------------------------------------------------
    */

    responderProgressoJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Não foi possível salvar o progresso da aula."
        ],
        500
    );

}