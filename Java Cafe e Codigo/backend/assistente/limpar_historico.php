<?php

/*
|--------------------------------------------------------------------------
| ASSISTENTE JAVA - LIMPAR HISTÓRICO
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Remove todo o histórico do Assistente Java pertencente
| exclusivamente ao usuário autenticado.
|
| Esta operação:
|
| - altera dados;
| - exige POST;
| - exige JSON;
| - exige sessão válida;
| - exige CSRF válido.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/bootstrap.php";

require_once __DIR__ . "/../models/AssistenteHistorico.php";


/*
|--------------------------------------------------------------------------
| HEADERS
|--------------------------------------------------------------------------
*/

header(
    "Content-Type: application/json; charset=UTF-8"
);

header(
    "Cache-Control: private, no-store, no-cache, must-revalidate, max-age=0"
);

header(
    "Pragma: no-cache"
);

header(
    "X-Content-Type-Options: nosniff"
);

header(
    "Referrer-Policy: same-origin"
);


/*
|--------------------------------------------------------------------------
| LIMITES
|--------------------------------------------------------------------------
*/

const ASSISTENTE_LIMPAR_LIMITE_CORPO =
    4096;


/*
|--------------------------------------------------------------------------
| RESPOSTA JSON
|--------------------------------------------------------------------------
*/

function limparHistoricoResponderJson(
    bool $status,
    string $mensagem,
    array $dados = [],
    int $codigoHttp = 200
): never {

    http_response_code(
        $codigoHttp
    );


    $resposta =
        array_merge(
            [
                "status" =>
                    $status,

                "mensagem" =>
                    $mensagem
            ],
            $dados
        );


    $json =
        json_encode(
            $resposta,
            JSON_UNESCAPED_UNICODE
            |
            JSON_UNESCAPED_SLASHES
            |
            JSON_INVALID_UTF8_SUBSTITUTE
        );


    if (
        $json === false
    ) {

        http_response_code(
            500
        );


        echo '{"status":false,"mensagem":"Não foi possível preparar a resposta."}';


        exit;

    }


    echo $json;


    exit;

}


/*
|--------------------------------------------------------------------------
| MÉTODO
|--------------------------------------------------------------------------
*/

$metodo =
    strtoupper(
        trim(
            (string) (
                $_SERVER["REQUEST_METHOD"]
                ??
                ""
            )
        )
    );


if (
    $metodo !==
    "POST"
) {

    header(
        "Allow: POST"
    );


    limparHistoricoResponderJson(
        false,
        "Método não permitido.",
        [],
        405
    );

}


/*
|--------------------------------------------------------------------------
| SESSÃO
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

    limparHistoricoResponderJson(
        false,
        "Sua sessão expirou. Entre novamente na plataforma.",
        [],
        401
    );

}


/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

$usuarioId =
    filter_var(
        $_SESSION["usuario"]["id"]
        ??
        null,
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 1
            ]
        ]
    );


if (
    $usuarioId === false
    ||
    $usuarioId === null
) {

    limparHistoricoResponderJson(
        false,
        "Não foi possível identificar o usuário.",
        [],
        401
    );

}


$usuarioId =
    (int) $usuarioId;


/*
|--------------------------------------------------------------------------
| ORIGEM DA REQUISIÇÃO
|--------------------------------------------------------------------------
|
| Proteção adicional ao CSRF.
|
| Navegadores modernos normalmente enviam:
|
| Sec-Fetch-Site: same-origin
|
|--------------------------------------------------------------------------
*/

$fetchSite =
    strtolower(
        trim(
            (string) (
                $_SERVER["HTTP_SEC_FETCH_SITE"]
                ??
                ""
            )
        )
    );


if (
    $fetchSite !== ""
    &&
    !in_array(
        $fetchSite,
        [
            "same-origin",
            "same-site",
            "none"
        ],
        true
    )
) {

    limparHistoricoResponderJson(
        false,
        "A origem da solicitação não pôde ser validada.",
        [],
        403
    );

}


/*
|--------------------------------------------------------------------------
| CONTENT-TYPE
|--------------------------------------------------------------------------
*/

$contentType =
    strtolower(
        trim(
            (string) (
                $_SERVER["CONTENT_TYPE"]
                ??
                ""
            )
        )
    );


if (
    $contentType !== ""
) {

    $contentTypeBase =
        trim(
            explode(
                ";",
                $contentType,
                2
            )[0]
        );


    if (
        $contentTypeBase !==
        "application/json"
    ) {

        limparHistoricoResponderJson(
            false,
            "Formato da requisição não permitido.",
            [],
            415
        );

    }

}
else {

    limparHistoricoResponderJson(
        false,
        "Formato da requisição não informado.",
        [],
        415
    );

}


/*
|--------------------------------------------------------------------------
| CONTENT-LENGTH
|--------------------------------------------------------------------------
*/

$contentLength =
    isset(
        $_SERVER["CONTENT_LENGTH"]
    )
        ?
        (int) $_SERVER["CONTENT_LENGTH"]
        :
        0;


if (
    $contentLength >
    ASSISTENTE_LIMPAR_LIMITE_CORPO
) {

    limparHistoricoResponderJson(
        false,
        "A solicitação enviada é muito grande.",
        [],
        413
    );

}


/*
|--------------------------------------------------------------------------
| CORPO JSON
|--------------------------------------------------------------------------
*/

$corpoBruto =
    file_get_contents(
        "php://input"
    );


if (
    !is_string(
        $corpoBruto
    )
) {

    limparHistoricoResponderJson(
        false,
        "Não foi possível ler a solicitação.",
        [],
        400
    );

}


if (
    strlen(
        $corpoBruto
    )
    >
    ASSISTENTE_LIMPAR_LIMITE_CORPO
) {

    limparHistoricoResponderJson(
        false,
        "A solicitação enviada é muito grande.",
        [],
        413
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
            $corpoBruto,
            true,
            16,
            JSON_THROW_ON_ERROR
        );

}
catch (
    JsonException $erro
) {

    limparHistoricoResponderJson(
        false,
        "Os dados enviados são inválidos.",
        [],
        400
    );

}


if (
    !is_array(
        $dados
    )
) {

    limparHistoricoResponderJson(
        false,
        "Os dados enviados são inválidos.",
        [],
        400
    );

}


/*
|--------------------------------------------------------------------------
| CSRF DA SESSÃO
|--------------------------------------------------------------------------
*/

$csrfSessao =
    $_SESSION["csrf"]["assistente_java"]
    ??
    null;


if (
    !is_string(
        $csrfSessao
    )
    ||
    preg_match(
        "/^[a-f0-9]{64}$/",
        $csrfSessao
    )
    !== 1
) {

    limparHistoricoResponderJson(
        false,
        "Sua sessão de segurança expirou. Atualize a página.",
        [],
        403
    );

}


/*
|--------------------------------------------------------------------------
| CSRF DO HEADER
|--------------------------------------------------------------------------
*/

$csrfHeader =
    trim(
        (string) (
            $_SERVER["HTTP_X_CSRF_TOKEN"]
            ??
            ""
        )
    );


/*
|--------------------------------------------------------------------------
| CSRF DO JSON
|--------------------------------------------------------------------------
*/

$csrfCorpo =
    trim(
        (string) (
            $dados["csrf_token"]
            ??
            ""
        )
    );


/*
|--------------------------------------------------------------------------
| TOKEN OBRIGATÓRIO
|--------------------------------------------------------------------------
|
| O frontend envia o token:
|
| 1. no header X-CSRF-Token;
| 2. no JSON.
|
| Validamos os dois para detectar alterações inesperadas na requisição.
|
|--------------------------------------------------------------------------
*/

if (
    $csrfHeader === ""
    ||
    $csrfCorpo === ""
) {

    limparHistoricoResponderJson(
        false,
        "Não foi possível validar a solicitação.",
        [],
        403
    );

}


/*
|--------------------------------------------------------------------------
| HEADER E CORPO PRECISAM SER IGUAIS
|--------------------------------------------------------------------------
*/

if (
    !hash_equals(
        $csrfHeader,
        $csrfCorpo
    )
) {

    limparHistoricoResponderJson(
        false,
        "Não foi possível validar a solicitação.",
        [],
        403
    );

}


/*
|--------------------------------------------------------------------------
| COMPARA COM A SESSÃO
|--------------------------------------------------------------------------
*/

if (
    !hash_equals(
        $csrfSessao,
        $csrfHeader
    )
) {

    limparHistoricoResponderJson(
        false,
        "Sua sessão de segurança expirou. Atualize a página.",
        [],
        403
    );

}


/*
|--------------------------------------------------------------------------
| CONEXÃO
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
        "[Assistente Java] PDO indisponível ao limpar histórico."
    );


    limparHistoricoResponderJson(
        false,
        "Não foi possível limpar a conversa.",
        [],
        500
    );

}


/*
|--------------------------------------------------------------------------
| LIMPAR HISTÓRICO
|--------------------------------------------------------------------------
*/

try {

    $historicoModel =
        new AssistenteHistorico(
            $pdo
        );


    /*
    |--------------------------------------------------------------------------
    | QUANTIDADE ANTES
    |--------------------------------------------------------------------------
    */

    $totalAnterior =
        $historicoModel->contarMensagens(
            $usuarioId
        );


    $totalAnterior =
        max(
            0,
            (int) $totalAnterior
        );


    /*
    |--------------------------------------------------------------------------
    | REMOVE SOMENTE O HISTÓRICO DESTE USUÁRIO
    |--------------------------------------------------------------------------
    */

    $resultado =
        $historicoModel->limparHistorico(
            $usuarioId
        );


    if (
        !$resultado
    ) {

        throw new RuntimeException(
            "O model não confirmou a remoção do histórico."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LIBERA LOCK DA SESSÃO
    |--------------------------------------------------------------------------
    |
    | O token CSRF permanece válido durante a sessão.
    |
    | Não o rotacionamos aqui porque a interface continuará utilizando
    | o Assistente depois da limpeza sem precisar recarregar a página.
    |
    |--------------------------------------------------------------------------
    */

    if (
        session_status() ===
        PHP_SESSION_ACTIVE
    ) {

        session_write_close();

    }


    /*
    |--------------------------------------------------------------------------
    | SUCESSO
    |--------------------------------------------------------------------------
    */

    limparHistoricoResponderJson(
        true,
        "Conversa limpa com sucesso.",
        [
            "mensagens_removidas" =>
                $totalAnterior
        ]
    );

}
catch (
    Throwable $erro
) {

    error_log(
        "[Assistente Java] Erro ao limpar histórico do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    limparHistoricoResponderJson(
        false,
        "Não foi possível limpar a conversa.",
        [],
        500
    );

}