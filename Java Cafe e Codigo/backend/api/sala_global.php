<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . "/../Controller/SalaGlobalController.php";


/*
|--------------------------------------------------------------------------
| HEADERS
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
| RESPONDER JSON
|--------------------------------------------------------------------------
*/

function responderSalaGlobal(
    array $dados,
    int $codigoHttp = 200
): never {

    http_response_code(
        $codigoHttp
    );


    $json =
        json_encode(
            $dados,
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );


    /*
    |--------------------------------------------------------------------------
    | FALLBACK
    |--------------------------------------------------------------------------
    */

    if (
        $json === false
    ) {

        http_response_code(
            500
        );


        echo '{"status":false,"mensagem":"Não foi possível gerar a resposta."}';

        exit;

    }


    echo $json;

    exit;

}


/*
|--------------------------------------------------------------------------
| MÉTODO NÃO PERMITIDO
|--------------------------------------------------------------------------
*/

function metodoNaoPermitidoSalaGlobal(
    array $permitidos
): never {

    header(
        "Allow: " .
        implode(
            ", ",
            $permitidos
        )
    );


    responderSalaGlobal(
        [
            "status" =>
                false,

            "mensagem" =>
                "Método não permitido."
        ],
        405
    );

}


/*
|--------------------------------------------------------------------------
| OBTER TEXTO DE UMA ENTRADA
|--------------------------------------------------------------------------
|
| Evita:
|
| trim(array)
|
| e outros valores inesperados enviados manualmente.
|
|--------------------------------------------------------------------------
*/

function textoEntradaSalaGlobal(
    array $origem,
    string $chave,
    ?string $padrao = ""
): ?string {

    if (
        !array_key_exists(
            $chave,
            $origem
        )
    ) {

        return $padrao;

    }


    $valor =
        $origem[
            $chave
        ];


    if (
        $valor === null
    ) {

        return $padrao;

    }


    if (
        !is_scalar(
            $valor
        )
    ) {

        return $padrao;

    }


    return trim(
        (string) $valor
    );

}


/*
|--------------------------------------------------------------------------
| OBTER INTEIRO
|--------------------------------------------------------------------------
*/

function inteiroEntradaSalaGlobal(
    array $origem,
    string $chave,
    int $padrao = 0
): int {

    if (
        !array_key_exists(
            $chave,
            $origem
        )
    ) {

        return $padrao;

    }


    $valor =
        $origem[
            $chave
        ];


    if (
        is_array(
            $valor
        )
        ||
        is_object(
            $valor
        )
    ) {

        return $padrao;

    }


    $validado =
        filter_var(
            $valor,
            FILTER_VALIDATE_INT
        );


    if (
        $validado === false
    ) {

        return $padrao;

    }


    return (int) $validado;

}


/*
|--------------------------------------------------------------------------
| LOGIN
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

    responderSalaGlobal(
        [
            "status" =>
                false,

            "mensagem" =>
                "Você precisa estar conectado."
        ],
        401
    );

}


/*
|--------------------------------------------------------------------------
| USUÁRIO DA SESSÃO
|--------------------------------------------------------------------------
|
| O ID do usuário nunca é recebido pelo formulário/API.
|
| A identidade utilizada nas ações da Sala Global vem exclusivamente
| da sessão autenticada.
|
|--------------------------------------------------------------------------
*/

$usuarioId =
    (int) (
        $_SESSION["usuario"]["id"]
        ??
        0
    );


if (
    $usuarioId <= 0
) {

    responderSalaGlobal(
        [
            "status" =>
                false,

            "mensagem" =>
                "Sessão inválida."
        ],
        401
    );

}


/*
|--------------------------------------------------------------------------
| MÉTODO HTTP
|--------------------------------------------------------------------------
*/

$metodo =
    strtoupper(
        trim(
            (string) (
                $_SERVER["REQUEST_METHOD"]
                ??
                "GET"
            )
        )
    );


/*
|--------------------------------------------------------------------------
| MÉTODOS ACEITOS PELA API
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $metodo,
        [
            "GET",
            "POST"
        ],
        true
    )
) {

    metodoNaoPermitidoSalaGlobal(
        [
            "GET",
            "POST"
        ]
    );

}


/*
|--------------------------------------------------------------------------
| AÇÃO
|--------------------------------------------------------------------------
|
| GET:
|
|     ação vem somente de $_GET.
|
| POST:
|
|     ação vem somente de $_POST.
|
| Isso evita ambiguidades como:
|
| POST /api.php?acao=feed
| body: acao=remover
|
|--------------------------------------------------------------------------
*/

if (
    $metodo ===
    "GET"
) {

    $acao =
        textoEntradaSalaGlobal(
            $_GET,
            "acao",
            ""
        );

}
else {

    $acao =
        textoEntradaSalaGlobal(
            $_POST,
            "acao",
            ""
        );

}


$acao =
    strtolower(
        trim(
            (string) $acao
        )
    );


/*
|--------------------------------------------------------------------------
| AÇÃO OBRIGATÓRIA
|--------------------------------------------------------------------------
*/

if (
    $acao === ""
) {

    responderSalaGlobal(
        [
            "status" =>
                false,

            "mensagem" =>
                "Informe uma ação."
        ],
        400
    );

}


/*
|--------------------------------------------------------------------------
| CSRF DA SALA GLOBAL
|--------------------------------------------------------------------------
|
| Utiliza o mesmo espaço de sessão usado pela página:
|
| $_SESSION["csrf"]["sala_global"]
|
| O token pode ser enviado por:
|
| POST:
|
|     csrf_token
|
| ou por JavaScript:
|
|     X-CSRF-Token
|
|--------------------------------------------------------------------------
*/

function tokenCsrfSalaGlobal(): string
{

    if (
        !isset(
            $_SESSION["csrf"]
        )
        ||
        !is_array(
            $_SESSION["csrf"]
        )
    ) {

        $_SESSION["csrf"] =
            [];

    }


    $token =
        $_SESSION["csrf"]["sala_global"]
        ??
        "";


    if (
        !is_string(
            $token
        )
        ||
        strlen(
            $token
        )
        !== 64
        ||
        preg_match(
            "/^[a-f0-9]{64}$/",
            $token
        )
        !== 1
    ) {

        $token =
            bin2hex(
                random_bytes(
                    32
                )
            );


        $_SESSION["csrf"]["sala_global"] =
            $token;

    }


    return $token;

}


/*
|--------------------------------------------------------------------------
| TOKEN RECEBIDO
|--------------------------------------------------------------------------
*/

function tokenCsrfRecebidoSalaGlobal(): string
{

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    $cabecalho =
        $_SERVER[
            "HTTP_X_CSRF_TOKEN"
        ]
        ??
        "";


    if (
        is_string(
            $cabecalho
        )
        &&
        trim(
            $cabecalho
        )
        !== ""
    ) {

        return trim(
            $cabecalho
        );

    }


    /*
    |--------------------------------------------------------------------------
    | POST
    |--------------------------------------------------------------------------
    */

    $post =
        $_POST[
            "csrf_token"
        ]
        ??
        "";


    if (
        is_string(
            $post
        )
    ) {

        return trim(
            $post
        );

    }


    return "";

}


/*
|--------------------------------------------------------------------------
| VALIDAR CSRF
|--------------------------------------------------------------------------
*/

function validarCsrfSalaGlobal(): bool
{

    $esperado =
        tokenCsrfSalaGlobal();


    $recebido =
        tokenCsrfRecebidoSalaGlobal();


    if (
        $recebido === ""
    ) {

        return false;

    }


    return hash_equals(
        $esperado,
        $recebido
    );

}


/*
|--------------------------------------------------------------------------
| SEC-FETCH-SITE
|--------------------------------------------------------------------------
|
| Camada complementar.
|
| O CSRF token continua sendo a proteção principal.
|
|--------------------------------------------------------------------------
*/

function origemNavegadorPermitidaSalaGlobal(): bool
{

    $origem =
        strtolower(
            trim(
                (string) (
                    $_SERVER[
                        "HTTP_SEC_FETCH_SITE"
                    ]
                    ??
                    ""
                )
            )
        );


    /*
    |--------------------------------------------------------------------------
    | Navegadores antigos e algumas ferramentas não enviam o header.
    |--------------------------------------------------------------------------
    */

    if (
        $origem === ""
    ) {

        return true;

    }


    return in_array(
        $origem,
        [
            "same-origin",
            "same-site",
            "none"
        ],
        true
    );

}


/*
|--------------------------------------------------------------------------
| AÇÕES QUE ALTERAM ESTADO
|--------------------------------------------------------------------------
*/

$acoesPost = [

    "criar",
    "atualizar",
    "remover",
    "responder",
    "remover_resposta",
    "ajuda",
    "denunciar"

];


/*
|--------------------------------------------------------------------------
| AÇÕES SOMENTE DE LEITURA
|--------------------------------------------------------------------------
*/

$acoesGet = [

    "feed",
    "buscar",
    "minhas_publicacoes",
    "listar_respostas"

];


/*
|--------------------------------------------------------------------------
| CONFERE MÉTODO DA AÇÃO
|--------------------------------------------------------------------------
*/

if (
    in_array(
        $acao,
        $acoesPost,
        true
    )
) {

    if (
        $metodo !==
        "POST"
    ) {

        metodoNaoPermitidoSalaGlobal(
            [
                "POST"
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ORIGEM
    |--------------------------------------------------------------------------
    */

    if (
        !origemNavegadorPermitidaSalaGlobal()
    ) {

        responderSalaGlobal(
            [
                "status" =>
                    false,

                "mensagem" =>
                    "Solicitação não autorizada."
            ],
            403
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    if (
        !validarCsrfSalaGlobal()
    ) {

        responderSalaGlobal(
            [
                "status" =>
                    false,

                "mensagem" =>
                    "A sessão de segurança expirou. Atualize a página e tente novamente."
            ],
            403
        );

    }

}
elseif (
    in_array(
        $acao,
        $acoesGet,
        true
    )
) {

    if (
        $metodo !==
        "GET"
    ) {

        metodoNaoPermitidoSalaGlobal(
            [
                "GET"
            ]
        );

    }

}
else {

    responderSalaGlobal(
        [
            "status" =>
                false,

            "mensagem" =>
                "Ação inválida."
        ],
        400
    );

}


/*
|--------------------------------------------------------------------------
| NORMALIZAR LINKS
|--------------------------------------------------------------------------
|
| Aceita:
|
| links[0][url]
| links[0][titulo]
| links[0][tipo]
|
| ou:
|
| links = JSON
|
|--------------------------------------------------------------------------
*/

function obterLinksSalaGlobal(): array
{

    $links =
        $_POST["links"]
        ??
        [];


    /*
    |--------------------------------------------------------------------------
    | ARRAY
    |--------------------------------------------------------------------------
    */

    if (
        is_array(
            $links
        )
    ) {

        return $links;

    }


    /*
    |--------------------------------------------------------------------------
    | JSON
    |--------------------------------------------------------------------------
    */

    if (
        is_string(
            $links
        )
    ) {

        $links =
            trim(
                $links
            );


        if (
            $links === ""
        ) {

            return [];

        }


        $decodificado =
            json_decode(
                $links,
                true,
                64
            );


        if (
            is_array(
                $decodificado
            )
        ) {

            return $decodificado;

        }

    }


    return [];

}


/*
|--------------------------------------------------------------------------
| ARQUIVOS
|--------------------------------------------------------------------------
*/

function obterArquivosSalaGlobal(): array
{

    if (
        isset(
            $_FILES["arquivos"]
        )
        &&
        is_array(
            $_FILES["arquivos"]
        )
    ) {

        return $_FILES[
            "arquivos"
        ];

    }


    if (
        isset(
            $_FILES["arquivo"]
        )
        &&
        is_array(
            $_FILES["arquivo"]
        )
    ) {

        return $_FILES[
            "arquivo"
        ];

    }


    return [];

}


/*
|--------------------------------------------------------------------------
| CONTROLLER
|--------------------------------------------------------------------------
*/

try {

    $controller =
        new SalaGlobalController();

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaGlobalAPI] Falha ao iniciar Controller: "
        .
        $erro->getMessage()
    );


    responderSalaGlobal(
        [
            "status" =>
                false,

            "mensagem" =>
                "Não foi possível iniciar a Sala Global."
        ],
        500
    );

}


/*
|--------------------------------------------------------------------------
| EXECUTAR AÇÃO
|--------------------------------------------------------------------------
*/

try {

    switch (
        $acao
    ) {


        /*
        |--------------------------------------------------------------------------
        | CRIAR PUBLICAÇÃO
        |--------------------------------------------------------------------------
        */

        case "criar":

            $titulo =
                textoEntradaSalaGlobal(
                    $_POST,
                    "titulo",
                    ""
                );


            $conteudo =
                array_key_exists(
                    "conteudo",
                    $_POST
                )
                    ?
                    textoEntradaSalaGlobal(
                        $_POST,
                        "conteudo",
                        null
                    )
                    :
                    null;


            $categoria =
                textoEntradaSalaGlobal(
                    $_POST,
                    "categoria",
                    "outro"
                );


            $arquivos =
                obterArquivosSalaGlobal();


            $links =
                obterLinksSalaGlobal();


            $resultado =
                $controller->criarPublicacao(

                    $usuarioId,

                    (string) $titulo,

                    $conteudo,

                    (string) $categoria,

                    $arquivos,

                    $links

                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    201
                    :
                    400
            );


        /*
        |--------------------------------------------------------------------------
        | FEED
        |--------------------------------------------------------------------------
        */

        case "feed":

            $categoria =
                array_key_exists(
                    "categoria",
                    $_GET
                )
                    ?
                    textoEntradaSalaGlobal(
                        $_GET,
                        "categoria",
                        null
                    )
                    :
                    null;


            $pesquisa =
                array_key_exists(
                    "pesquisa",
                    $_GET
                )
                    ?
                    textoEntradaSalaGlobal(
                        $_GET,
                        "pesquisa",
                        null
                    )
                    :
                    null;


            $limite =
                inteiroEntradaSalaGlobal(
                    $_GET,
                    "limite",
                    20
                );


            $offset =
                inteiroEntradaSalaGlobal(
                    $_GET,
                    "offset",
                    0
                );


            $resultado =
                $controller->listarFeed(

                    $categoria,

                    $pesquisa,

                    $limite,

                    $offset,

                    $usuarioId

                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    200
                    :
                    400
            );


        /*
        |--------------------------------------------------------------------------
        | BUSCAR PUBLICAÇÃO
        |--------------------------------------------------------------------------
        */

        case "buscar":

            $publicacaoId =
                inteiroEntradaSalaGlobal(
                    $_GET,
                    "id",
                    0
                );


            $resultado =
                $controller->buscarPublicacao(

                    $publicacaoId,

                    $usuarioId

                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    200
                    :
                    404
            );


        /*
        |--------------------------------------------------------------------------
        | MINHAS PUBLICAÇÕES
        |--------------------------------------------------------------------------
        */

        case "minhas_publicacoes":

            $resultado =
                $controller->listarDoUsuario(
                    $usuarioId
                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    200
                    :
                    400
            );


        /*
        |--------------------------------------------------------------------------
        | ATUALIZAR PUBLICAÇÃO
        |--------------------------------------------------------------------------
        */

        case "atualizar":

            $publicacaoId =
                inteiroEntradaSalaGlobal(
                    $_POST,
                    "publicacao_id",
                    0
                );


            $titulo =
                textoEntradaSalaGlobal(
                    $_POST,
                    "titulo",
                    ""
                );


            $conteudo =
                array_key_exists(
                    "conteudo",
                    $_POST
                )
                    ?
                    textoEntradaSalaGlobal(
                        $_POST,
                        "conteudo",
                        null
                    )
                    :
                    null;


            $categoria =
                textoEntradaSalaGlobal(
                    $_POST,
                    "categoria",
                    "outro"
                );


            $resultado =
                $controller->atualizarPublicacao(

                    $publicacaoId,

                    $usuarioId,

                    (string) $titulo,

                    $conteudo,

                    (string) $categoria

                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    200
                    :
                    400
            );


        /*
        |--------------------------------------------------------------------------
        | REMOVER PUBLICAÇÃO
        |--------------------------------------------------------------------------
        */

        case "remover":

            $publicacaoId =
                inteiroEntradaSalaGlobal(
                    $_POST,
                    "publicacao_id",
                    0
                );


            $resultado =
                $controller->removerPublicacao(

                    $publicacaoId,

                    $usuarioId

                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    200
                    :
                    400
            );


        /*
        |--------------------------------------------------------------------------
        | RESPONDER PUBLICAÇÃO
        |--------------------------------------------------------------------------
        */

        case "responder":

            $publicacaoId =
                inteiroEntradaSalaGlobal(
                    $_POST,
                    "publicacao_id",
                    0
                );


            $mensagem =
                textoEntradaSalaGlobal(
                    $_POST,
                    "mensagem",
                    ""
                );


            $resultado =
                $controller->responder(

                    $publicacaoId,

                    $usuarioId,

                    (string) $mensagem

                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    201
                    :
                    400
            );


        /*
        |--------------------------------------------------------------------------
        | LISTAR RESPOSTAS
        |--------------------------------------------------------------------------
        */

        case "listar_respostas":

            $publicacaoId =
                inteiroEntradaSalaGlobal(
                    $_GET,
                    "publicacao_id",
                    0
                );


            $resultado =
                $controller->listarRespostas(
                    $publicacaoId
                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    200
                    :
                    404
            );


        /*
        |--------------------------------------------------------------------------
        | REMOVER PRÓPRIA RESPOSTA
        |--------------------------------------------------------------------------
        */

        case "remover_resposta":

            $respostaId =
                inteiroEntradaSalaGlobal(
                    $_POST,
                    "resposta_id",
                    0
                );


            $resultado =
                $controller->removerResposta(

                    $respostaId,

                    $usuarioId

                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    200
                    :
                    400
            );


        /*
        |--------------------------------------------------------------------------
        | ISSO ME AJUDOU
        |--------------------------------------------------------------------------
        */

        case "ajuda":

            $publicacaoId =
                inteiroEntradaSalaGlobal(
                    $_POST,
                    "publicacao_id",
                    0
                );


            $resultado =
                $controller->alternarAjuda(

                    $publicacaoId,

                    $usuarioId

                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    200
                    :
                    400
            );


        /*
        |--------------------------------------------------------------------------
        | DENUNCIAR
        |--------------------------------------------------------------------------
        */

        case "denunciar":

            $publicacaoId =
                inteiroEntradaSalaGlobal(
                    $_POST,
                    "publicacao_id",
                    0
                );


            $motivo =
                textoEntradaSalaGlobal(
                    $_POST,
                    "motivo",
                    ""
                );


            $descricao =
                array_key_exists(
                    "descricao",
                    $_POST
                )
                    ?
                    textoEntradaSalaGlobal(
                        $_POST,
                        "descricao",
                        null
                    )
                    :
                    null;


            $resultado =
                $controller->denunciar(

                    $publicacaoId,

                    $usuarioId,

                    (string) $motivo,

                    $descricao

                );


            responderSalaGlobal(
                $resultado,
                !empty(
                    $resultado["status"]
                )
                    ?
                    201
                    :
                    400
            );


        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        default:

            responderSalaGlobal(
                [
                    "status" =>
                        false,

                    "mensagem" =>
                        "Ação inválida."
                ],
                400
            );

    }

}
catch (
    Throwable $erro
) {

    /*
    |--------------------------------------------------------------------------
    | ERRO SOMENTE NO LOG
    |--------------------------------------------------------------------------
    |
    | Não devolvemos:
    |
    | $erro->getMessage()
    |
    | ao navegador.
    |
    |--------------------------------------------------------------------------
    */

    error_log(
        "[SalaGlobalAPI] Erro na ação '{$acao}' para usuário {$usuarioId}: "
        .
        $erro->getMessage()
    );


    responderSalaGlobal(
        [
            "status" =>
                false,

            "mensagem" =>
                "Ocorreu um erro ao processar a solicitação."
        ],
        500
    );

}