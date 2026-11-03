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
| CABEÇALHOS DA API
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
| RESPOSTA JSON
|--------------------------------------------------------------------------
*/

function responderJson(
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
|
| Rota pública:
|
| POST /aulas/concluir
|
*/

if (
    (
        $_SERVER["REQUEST_METHOD"]
        ?? ""
    )
    !== "POST"
) {

    responderJson(
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

    responderJson(
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


    responderJson(
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
| VERIFICA BANCO
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
        "[AulaConclusao] Conexão PDO indisponível."
    );


    responderJson(
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
| O JavaScript envia:
|
| Content-Type: application/json
|
| Isso evita aceitar submissões comuns de formulários HTML
| neste endpoint.
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

    responderJson(
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
| TAMANHO MÁXIMO DA REQUISIÇÃO
|--------------------------------------------------------------------------
|
| Este endpoint precisa receber apenas algo parecido com:
|
| {"aula_id":7}
|
| Portanto não existe motivo para aceitar payloads grandes.
|
*/

$contentLength =
    (int) (
        $_SERVER["CONTENT_LENGTH"]
        ?? 0
    );


if (
    $contentLength > 2048
) {

    responderJson(
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

    responderJson(
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

    responderJson(
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

    responderJson(
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


/*
|--------------------------------------------------------------------------
| VALIDA AULA
|--------------------------------------------------------------------------
*/

if (
    $aulaId === false
    ||
    $aulaId === null
    ||
    $aulaId < 1
) {

    responderJson(
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
| LIMITE DO ID
|--------------------------------------------------------------------------
|
| Atualmente os arquivos utilizam:
|
| aula01.php
| aula02.php
| ...
|
| O padrão físico trabalha com dois dígitos.
|
*/

if (
    $aulaId > 99
) {

    responderJson(
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
| CONFIRMA QUE A AULA EXISTE
|--------------------------------------------------------------------------
|
| Este é um caminho interno do servidor.
|
| Ele não é enviado ao navegador.
|
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

    responderJson(
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
|
| Além de o arquivo existir, confirmamos que ele representa
| realmente uma Aula Conversada válida.
|
*/

try {

    $dadosAula =
        require $arquivoAula;

}
catch (Throwable $erro) {

    error_log(
        "[AulaConclusao] Erro ao carregar aula " .
        $aulaId .
        ": " .
        $erro->getMessage()
    );


    responderJson(
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

    error_log(
        "[AulaConclusao] Estrutura inválida para aula " .
        $aulaId .
        "."
    );


    responderJson(
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
| ID INTERNO DA AULA
|--------------------------------------------------------------------------
|
| Caso o arquivo declare um ID, ele precisa corresponder
| à aula solicitada.
|
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
        "[AulaConclusao] ID divergente. URL/entrada: " .
        $aulaId .
        ", arquivo: " .
        $idInternoAula .
        "."
    );


    responderJson(
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
| SALVA CONCLUSÃO
|--------------------------------------------------------------------------
*/

try {

    /*
    |--------------------------------------------------------------------------
    | INSERT OU UPDATE
    |--------------------------------------------------------------------------
    |
    | CENÁRIO 1
    |
    | usuário nunca possuiu registro:
    |
    | cria:
    |
    | usuario_id
    | aula_id
    | etapa_atual = 1
    | concluida = 1
    |
    |
    | CENÁRIO 2
    |
    | registro já existe:
    |
    | mantém o progresso existente
    | e altera concluida para 1.
    |
    */

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
            1,
            1
        )

        ON DUPLICATE KEY UPDATE

            concluida = 1,
            atualizado_em = CURRENT_TIMESTAMP

    ";


    $stmt =
        $pdo->prepare(
            $sql
        );


    $stmt->execute([

        ":usuario_id" =>
            $usuarioId,

        ":aula_id" =>
            $aulaId

    ]);


    /*
    |--------------------------------------------------------------------------
    | LOG
    |--------------------------------------------------------------------------
    |
    | Não registramos dados sensíveis.
    |
    */

    error_log(
        "[AulaConclusao] Aula " .
        $aulaId .
        " concluída pelo usuário " .
        $usuarioId .
        "."
    );


    /*
    |--------------------------------------------------------------------------
    | SUCESSO
    |--------------------------------------------------------------------------
    */

    responderJson(
        [
            "sucesso" =>
                true,

            "aula_id" =>
                $aulaId,

            "concluida" =>
                true,

            "mensagem" =>
                "Aula concluída com sucesso."
        ]
    );

}
catch (Throwable $erro) {

    /*
    |--------------------------------------------------------------------------
    | LOG TÉCNICO
    |--------------------------------------------------------------------------
    */

    error_log(
        "[AulaConclusao] Erro ao registrar conclusão da aula " .
        $aulaId .
        " para usuário " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    /*
    |--------------------------------------------------------------------------
    | RESPOSTA SEGURA
    |--------------------------------------------------------------------------
    */

    responderJson(
        [
            "sucesso" =>
                false,

            "erro" =>
                "Não foi possível registrar a conclusão da aula."
        ],
        500
    );

}