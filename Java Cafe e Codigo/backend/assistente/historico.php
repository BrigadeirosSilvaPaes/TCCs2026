<?php

/*
|--------------------------------------------------------------------------
| ASSISTENTE JAVA - HISTÓRICO
|--------------------------------------------------------------------------
|
| Retorna o histórico da conversa do usuário autenticado.
|
| Este endpoint:
|
| - não executa inteligência artificial;
| - não recebe contexto da página;
| - não altera dados;
| - apenas consulta mensagens já salvas.
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
|
| O frontend também possui limites próprios.
|
| Mesmo assim, o backend não deve devolver uma quantidade ilimitada
| de mensagens caso o histórico do usuário cresça muito.
|
|--------------------------------------------------------------------------
*/

const ASSISTENTE_HISTORICO_LIMITE =
    50;


const ASSISTENTE_HISTORICO_LIMITE_MENSAGEM =
    4000;


/*
|--------------------------------------------------------------------------
| RESPOSTA JSON
|--------------------------------------------------------------------------
*/

function historicoResponderJson(
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


    /*
    |--------------------------------------------------------------------------
    | FALHA EXTREMAMENTE IMPROVÁVEL NO JSON
    |--------------------------------------------------------------------------
    */

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
    "GET"
) {

    header(
        "Allow: GET"
    );


    historicoResponderJson(
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

    historicoResponderJson(
        false,
        "Sua sessão expirou.",
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

    historicoResponderJson(
        false,
        "Sua sessão é inválida.",
        [],
        401
    );

}


$usuarioId =
    (int) $usuarioId;


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
        "[Assistente Java] PDO indisponível ao buscar histórico."
    );


    historicoResponderJson(
        false,
        "Não foi possível carregar a conversa.",
        [],
        500
    );

}


/*
|--------------------------------------------------------------------------
| BUSCAR HISTÓRICO
|--------------------------------------------------------------------------
*/

try {

    $historicoModel =
        new AssistenteHistorico(
            $pdo
        );


    $historico =
        $historicoModel->buscarHistorico(
            $usuarioId
        );


    /*
    |--------------------------------------------------------------------------
    | GARANTE ARRAY
    |--------------------------------------------------------------------------
    */

    if (
        !is_array(
            $historico
        )
    ) {

        throw new RuntimeException(
            "O model retornou um histórico inválido."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ÚLTIMAS MENSAGENS
    |--------------------------------------------------------------------------
    |
    | Mantemos a ordem recebida do model e selecionamos somente
    | as mensagens mais recentes.
    |
    |--------------------------------------------------------------------------
    */

    if (
        count(
            $historico
        )
        >
        ASSISTENTE_HISTORICO_LIMITE
    ) {

        $historico =
            array_slice(
                $historico,
                -ASSISTENTE_HISTORICO_LIMITE
            );

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR PARA O FRONTEND
    |--------------------------------------------------------------------------
    */

    $mensagens =
        [];


    foreach (
        $historico as $item
    ) {

        if (
            !is_array(
                $item
            )
        ) {

            continue;

        }


        /*
        |--------------------------------------------------------------------------
        | PAPEL
        |--------------------------------------------------------------------------
        */

        $papel =
            strtolower(
                trim(
                    (string) (
                        $item["papel"]
                        ??
                        ""
                    )
                )
            );


        $role =
            match (
                $papel
            ) {

                "usuario" =>
                    "user",

                "assistente" =>
                    "assistant",

                default =>
                    null

            };


        if (
            $role === null
        ) {

            continue;

        }


        /*
        |--------------------------------------------------------------------------
        | MENSAGEM
        |--------------------------------------------------------------------------
        */

        $mensagem =
            trim(
                str_replace(
                    "\0",
                    "",
                    (string) (
                        $item["mensagem"]
                        ??
                        ""
                    )
                )
            );


        if (
            $mensagem === ""
        ) {

            continue;

        }


        /*
        |--------------------------------------------------------------------------
        | LIMITE DEFENSIVO
        |--------------------------------------------------------------------------
        */

        if (
            strlen(
                $mensagem
            )
            >
            ASSISTENTE_HISTORICO_LIMITE_MENSAGEM
        ) {

            $mensagem =
                substr(
                    $mensagem,
                    0,
                    ASSISTENTE_HISTORICO_LIMITE_MENSAGEM
                );

        }


        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        $mensagemId =
            (int) (
                $item["id"]
                ??
                0
            );


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $criadoEm =
            isset(
                $item["criado_em"]
            )
                ?
                trim(
                    (string) $item["criado_em"]
                )
                :
                null;


        if (
            $criadoEm ===
            ""
        ) {

            $criadoEm =
                null;

        }


        /*
        |--------------------------------------------------------------------------
        | RESULTADO
        |--------------------------------------------------------------------------
        */

        $mensagens[] = [

            "id" =>
                $mensagemId > 0
                    ?
                    $mensagemId
                    :
                    null,

            "role" =>
                $role,

            "content" =>
                $mensagem,

            "criado_em" =>
                $criadoEm

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | LIBERA LOCK DA SESSÃO
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

    historicoResponderJson(
        true,
        "Histórico carregado.",
        [
            "historico" =>
                $mensagens,

            "total" =>
                count(
                    $mensagens
                )
        ]
    );

}
catch (
    Throwable $erro
) {

    error_log(
        "[Assistente Java] Erro ao carregar histórico do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    historicoResponderJson(
        false,
        "Não foi possível carregar a conversa.",
        [],
        500
    );

}