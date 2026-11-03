<?php

/*
|--------------------------------------------------------------------------
| ASSISTENTE JAVA - SALVAR CONVERSA
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| A inteligência artificial executa diretamente no navegador.
|
| Este endpoint NÃO:
|
| - executa modelo de IA;
| - chama Ollama;
| - chama API externa;
| - confia que a resposta recebida foi realmente gerada pelo modelo.
|
| Este endpoint:
|
| - exige usuário autenticado;
| - exige POST;
| - exige JSON;
| - exige CSRF;
| - valida pergunta e resposta;
| - salva ambas na mesma transação;
| - retorna confirmação em JSON.
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

const ASSISTENTE_LIMITE_MENSAGEM =
    4000;


/*
|--------------------------------------------------------------------------
| A interface atualmente limita a resposta em cerca de 2200 caracteres.
|
| Mantemos uma margem no servidor sem aceitar textos gigantes.
|--------------------------------------------------------------------------
*/

const ASSISTENTE_LIMITE_RESPOSTA =
    5000;


/*
|--------------------------------------------------------------------------
| JSON contém:
|
| - csrf_token;
| - mensagem;
| - resposta_assistente.
|
|--------------------------------------------------------------------------
*/

const ASSISTENTE_LIMITE_CORPO =
    16384;


/*
|--------------------------------------------------------------------------
| RESPOSTA JSON
|--------------------------------------------------------------------------
*/

function assistenteResponderJson(
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
| NORMALIZAR TEXTO
|--------------------------------------------------------------------------
*/

function assistenteNormalizarTexto(
    mixed $valor
): string {

    $texto =
        (string) $valor;


    /*
    |--------------------------------------------------------------------------
    | QUEBRA DE LINHA
    |--------------------------------------------------------------------------
    */

    $texto =
        str_replace(
            [
                "\r\n",
                "\r"
            ],
            "\n",
            $texto
        );


    /*
    |--------------------------------------------------------------------------
    | BYTE NULO
    |--------------------------------------------------------------------------
    */

    $texto =
        str_replace(
            "\0",
            "",
            $texto
        );


    return trim(
        $texto
    );

}


/*
|--------------------------------------------------------------------------
| UTF-8
|--------------------------------------------------------------------------
*/

function assistenteTextoUtf8Valido(
    string $texto
): bool {

    if (
        function_exists(
            "mb_check_encoding"
        )
    ) {

        return mb_check_encoding(
            $texto,
            "UTF-8"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FALLBACK SEM MBSTRING
    |--------------------------------------------------------------------------
    */

    return preg_match(
        "//u",
        $texto
    )
    === 1;

}


/*
|--------------------------------------------------------------------------
| TAMANHO EM CARACTERES
|--------------------------------------------------------------------------
*/

function assistenteComprimento(
    string $texto
): int {

    if (
        function_exists(
            "mb_strlen"
        )
    ) {

        return mb_strlen(
            $texto,
            "UTF-8"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Sem mbstring usamos strlen.
    |
    | Isso conta bytes e portanto é mais restritivo com acentos,
    | mas continua seguro.
    |--------------------------------------------------------------------------
    */

    return strlen(
        $texto
    );

}


/*
|--------------------------------------------------------------------------
| CONTROLES INVÁLIDOS
|--------------------------------------------------------------------------
|
| Permitimos:
|
| - quebra de linha;
| - tabulação.
|
| Bloqueamos outros caracteres de controle.
|
|--------------------------------------------------------------------------
*/

function assistentePossuiControleInvalido(
    string $texto
): bool {

    return preg_match(
        "/[\x01-\x08\x0B\x0C\x0E-\x1F\x7F]/",
        $texto
    )
    === 1;

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


    assistenteResponderJson(
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

    assistenteResponderJson(
        false,
        "Sua sessão expirou. Entre novamente na plataforma.",
        [
            "sessao_expirada" =>
                true
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

    assistenteResponderJson(
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
| Camada adicional ao CSRF.
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

    assistenteResponderJson(
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
    $contentType === ""
) {

    assistenteResponderJson(
        false,
        "Formato da requisição não informado.",
        [],
        415
    );

}


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

    assistenteResponderJson(
        false,
        "Formato da requisição não permitido.",
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
    ASSISTENTE_LIMITE_CORPO
) {

    assistenteResponderJson(
        false,
        "A solicitação enviada é muito grande.",
        [],
        413
    );

}


/*
|--------------------------------------------------------------------------
| CORPO
|--------------------------------------------------------------------------
*/

$conteudoRecebido =
    file_get_contents(
        "php://input"
    );


if (
    !is_string(
        $conteudoRecebido
    )
    ||
    trim(
        $conteudoRecebido
    )
    === ""
) {

    assistenteResponderJson(
        false,
        "Nenhuma informação foi enviada.",
        [],
        400
    );

}


if (
    strlen(
        $conteudoRecebido
    )
    >
    ASSISTENTE_LIMITE_CORPO
) {

    assistenteResponderJson(
        false,
        "A solicitação enviada é muito grande.",
        [],
        413
    );

}


/*
|--------------------------------------------------------------------------
| JSON
|--------------------------------------------------------------------------
*/

try {

    $dados =
        json_decode(
            $conteudoRecebido,
            true,
            16,
            JSON_THROW_ON_ERROR
        );

}
catch (
    JsonException $erro
) {

    assistenteResponderJson(
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

    assistenteResponderJson(
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

    assistenteResponderJson(
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


if (
    $csrfHeader === ""
    ||
    $csrfCorpo === ""
) {

    assistenteResponderJson(
        false,
        "Não foi possível validar a solicitação.",
        [],
        403
    );

}


/*
|--------------------------------------------------------------------------
| HEADER == JSON
|--------------------------------------------------------------------------
*/

if (
    !hash_equals(
        $csrfHeader,
        $csrfCorpo
    )
) {

    assistenteResponderJson(
        false,
        "Não foi possível validar a solicitação.",
        [],
        403
    );

}


/*
|--------------------------------------------------------------------------
| TOKEN == SESSÃO
|--------------------------------------------------------------------------
*/

if (
    !hash_equals(
        $csrfSessao,
        $csrfHeader
    )
) {

    assistenteResponderJson(
        false,
        "Sua sessão de segurança expirou. Atualize a página.",
        [],
        403
    );

}


/*
|--------------------------------------------------------------------------
| PERGUNTA
|--------------------------------------------------------------------------
*/

$mensagem =
    assistenteNormalizarTexto(
        $dados["mensagem"]
        ??
        ""
    );


if (
    $mensagem === ""
) {

    assistenteResponderJson(
        false,
        "Digite uma dúvida antes de enviar.",
        [],
        400
    );

}


/*
|--------------------------------------------------------------------------
| UTF-8 DA PERGUNTA
|--------------------------------------------------------------------------
*/

if (
    !assistenteTextoUtf8Valido(
        $mensagem
    )
) {

    assistenteResponderJson(
        false,
        "Sua mensagem contém caracteres inválidos.",
        [],
        400
    );

}


/*
|--------------------------------------------------------------------------
| CONTROLES
|--------------------------------------------------------------------------
*/

if (
    assistentePossuiControleInvalido(
        $mensagem
    )
) {

    assistenteResponderJson(
        false,
        "Sua mensagem contém caracteres não permitidos.",
        [],
        400
    );

}


/*
|--------------------------------------------------------------------------
| TAMANHO
|--------------------------------------------------------------------------
*/

if (
    assistenteComprimento(
        $mensagem
    )
    >
    ASSISTENTE_LIMITE_MENSAGEM
) {

    assistenteResponderJson(
        false,
        "Sua mensagem está muito longa. Tente resumir um pouco a dúvida.",
        [],
        400
    );

}


/*
|--------------------------------------------------------------------------
| RESPOSTA DO ASSISTENTE
|--------------------------------------------------------------------------
|
| IMPORTANTE:
|
| Esse texto vem do navegador.
|
| O servidor NÃO considera essa informação confiável e NÃO assume
| que ela tenha sido realmente produzida pelo modelo Qwen.
|
|--------------------------------------------------------------------------
*/

$respostaAssistente =
    assistenteNormalizarTexto(
        $dados["resposta_assistente"]
        ??
        ""
    );


if (
    $respostaAssistente === ""
) {

    assistenteResponderJson(
        false,
        "O Assistente Java não retornou uma resposta válida.",
        [],
        400
    );

}


/*
|--------------------------------------------------------------------------
| UTF-8 DA RESPOSTA
|--------------------------------------------------------------------------
*/

if (
    !assistenteTextoUtf8Valido(
        $respostaAssistente
    )
) {

    assistenteResponderJson(
        false,
        "A resposta contém caracteres inválidos.",
        [],
        400
    );

}


/*
|--------------------------------------------------------------------------
| CONTROLES DA RESPOSTA
|--------------------------------------------------------------------------
*/

if (
    assistentePossuiControleInvalido(
        $respostaAssistente
    )
) {

    assistenteResponderJson(
        false,
        "A resposta contém caracteres não permitidos.",
        [],
        400
    );

}


/*
|--------------------------------------------------------------------------
| TAMANHO DA RESPOSTA
|--------------------------------------------------------------------------
*/

if (
    assistenteComprimento(
        $respostaAssistente
    )
    >
    ASSISTENTE_LIMITE_RESPOSTA
) {

    assistenteResponderJson(
        false,
        "A resposta do Assistente Java ultrapassou o limite permitido.",
        [],
        400
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
        "[Assistente Java] PDO indisponível ao salvar conversa."
    );


    assistenteResponderJson(
        false,
        "Não foi possível acessar o histórico do Assistente Java.",
        [],
        500
    );

}


/*
|--------------------------------------------------------------------------
| MODEL
|--------------------------------------------------------------------------
*/

try {

    $historicoModel =
        new AssistenteHistorico(
            $pdo
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[Assistente Java] Erro ao iniciar histórico do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    assistenteResponderJson(
        false,
        "Não foi possível preparar o histórico da conversa.",
        [],
        500
    );

}


/*
|--------------------------------------------------------------------------
| SALVAR PERGUNTA + RESPOSTA
|--------------------------------------------------------------------------
|
| As duas mensagens são tratadas como uma única operação lógica.
|
|--------------------------------------------------------------------------
*/

try {

    /*
    |--------------------------------------------------------------------------
    | NÃO ACEITA TRANSAÇÃO EXTERNA INESPERADA
    |--------------------------------------------------------------------------
    |
    | Este endpoint deveria controlar sua própria transação.
    |
    |--------------------------------------------------------------------------
    */

    if (
        $pdo->inTransaction()
    ) {

        throw new RuntimeException(
            "Já existe uma transação ativa antes do salvamento."
        );

    }


    if (
        !$pdo->beginTransaction()
    ) {

        throw new RuntimeException(
            "Não foi possível iniciar a transação."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PERGUNTA
    |--------------------------------------------------------------------------
    */

    $salvouUsuario =
        $historicoModel->salvarUsuario(

            $usuarioId,

            $mensagem

        );


    if (
        !$salvouUsuario
    ) {

        throw new RuntimeException(
            "Não foi possível salvar a pergunta."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | RESPOSTA
    |--------------------------------------------------------------------------
    */

    $salvouAssistente =
        $historicoModel->salvarAssistente(

            $usuarioId,

            $respostaAssistente

        );


    if (
        !$salvouAssistente
    ) {

        throw new RuntimeException(
            "Não foi possível salvar a resposta."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COMMIT
    |--------------------------------------------------------------------------
    */

    if (
        !$pdo->commit()
    ) {

        throw new RuntimeException(
            "Não foi possível confirmar a conversa."
        );

    }

}
catch (
    Throwable $erroBanco
) {

    if (
        $pdo->inTransaction()
    ) {

        try {

            $pdo->rollBack();

        }
        catch (
            Throwable $erroRollback
        ) {

            error_log(
                "[Assistente Java] Falha também no rollback: "
                .
                $erroRollback->getMessage()
            );

        }

    }


    error_log(
        "[Assistente Java] Erro ao salvar conversa do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erroBanco->getMessage()
    );


    assistenteResponderJson(
        false,
        "A resposta foi gerada, mas não foi possível salvar a conversa.",
        [],
        500
    );

}


/*
|--------------------------------------------------------------------------
| TOTAL
|--------------------------------------------------------------------------
|
| Não é parte crítica da operação.
|
|--------------------------------------------------------------------------
*/

try {

    $totalMensagens =
        $historicoModel->contarMensagens(
            $usuarioId
        );


    $totalMensagens =
        max(
            0,
            (int) $totalMensagens
        );

}
catch (
    Throwable $erro
) {

    $totalMensagens =
        null;


    error_log(
        "[Assistente Java] Não foi possível contar mensagens do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );

}


/*
|--------------------------------------------------------------------------
| LIBERA A SESSÃO
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

assistenteResponderJson(
    true,
    "Conversa salva com sucesso.",
    [

        "gerado_localmente" =>
            true,

        "historico_salvo" =>
            true,

        "total_mensagens" =>
            $totalMensagens

    ],
    200
);