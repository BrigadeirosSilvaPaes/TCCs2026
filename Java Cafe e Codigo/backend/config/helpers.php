<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| HELPERS GERAIS DA APLICAÇÃO
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| GARANTE SESSÃO ATIVA
|--------------------------------------------------------------------------
|
| Toda inicialização passa por session.php para preservar:
|
| - HttpOnly;
| - SameSite;
| - Secure em HTTPS;
| - modo estrito;
| - uso exclusivo de cookies.
|
|--------------------------------------------------------------------------
*/

function garantirSessao(): void
{

    if (
        session_status()
        === PHP_SESSION_ACTIVE
    ) {

        return;

    }


    require_once __DIR__ . "/session.php";


    if (
        session_status()
        !== PHP_SESSION_ACTIVE
    ) {

        throw new RuntimeException(
            "Não foi possível iniciar a sessão da aplicação."
        );

    }

}


/*
|--------------------------------------------------------------------------
| CATÁLOGO DE MENSAGENS
|--------------------------------------------------------------------------
*/

function catalogoMensagens(): array
{

    static $catalogo = null;


    if (
        is_array(
            $catalogo
        )
    ) {

        return $catalogo;

    }


    $arquivo =
        __DIR__
        .
        "/mensagens.php";


    if (
        !is_file(
            $arquivo
        )
    ) {

        error_log(
            "[Helpers] Arquivo mensagens.php não encontrado."
        );


        $catalogo = [];


        return $catalogo;

    }


    $resultado =
        require $arquivo;


    if (
        !is_array(
            $resultado
        )
    ) {

        error_log(
            "[Helpers] mensagens.php não retornou um array válido."
        );


        $catalogo = [];


        return $catalogo;

    }


    $catalogo =
        $resultado;


    return $catalogo;

}


/*
|--------------------------------------------------------------------------
| CRIA MENSAGEM
|--------------------------------------------------------------------------
|
| Exemplo:
|
| mensagem(
|     "erro",
|     "login_invalido"
| );
|
|--------------------------------------------------------------------------
*/

function mensagem(
    string $tipo,
    string $codigo
): void
{

    garantirSessao();


    /*
    |--------------------------------------------------------------------------
    | NORMALIZA
    |--------------------------------------------------------------------------
    */

    $tipo =
        strtolower(
            trim(
                $tipo
            )
        );


    $codigo =
        trim(
            $codigo
        );


    /*
    |--------------------------------------------------------------------------
    | TIPOS PERMITIDOS
    |--------------------------------------------------------------------------
    */

    $tiposPermitidos = [
        "sucesso",
        "erro",
        "aviso",
        "info"
    ];


    if (
        !in_array(
            $tipo,
            $tiposPermitidos,
            true
        )
    ) {

        $tipo =
            "info";

    }


    /*
    |--------------------------------------------------------------------------
    | CATÁLOGO
    |--------------------------------------------------------------------------
    */

    $catalogo =
        catalogoMensagens();


    /*
    |--------------------------------------------------------------------------
    | LOCALIZA ENTRADA
    |--------------------------------------------------------------------------
    |
    | Formato principal esperado:
    |
    | [
    |     "erro" => [
    |         "login_invalido" => [
    |             "titulo" => "...",
    |             "texto"  => "..."
    |         ]
    |     ]
    | ]
    |
    |--------------------------------------------------------------------------
    */

    $entrada =
        $catalogo[$tipo][$codigo]
        ??
        null;


    /*
    |--------------------------------------------------------------------------
    | COMPATIBILIDADE COM CATÁLOGO SIMPLES
    |--------------------------------------------------------------------------
    |
    | [
    |     "login_invalido" => "..."
    | ]
    |
    | ou:
    |
    | [
    |     "login_invalido" => [
    |         "titulo" => "...",
    |         "texto"  => "..."
    |     ]
    | ]
    |
    |--------------------------------------------------------------------------
    */

    if (
        $entrada ===
        null
        &&
        array_key_exists(
            $codigo,
            $catalogo
        )
    ) {

        $entrada =
            $catalogo[$codigo];

    }


    /*
    |--------------------------------------------------------------------------
    | VALORES PADRÃO
    |--------------------------------------------------------------------------
    */

    $titulo =
        null;


    $texto =
        null;


    /*
    |--------------------------------------------------------------------------
    | FORMATO ESTRUTURADO
    |--------------------------------------------------------------------------
    */

    if (
        is_array(
            $entrada
        )
    ) {

        $tituloEntrada =
            $entrada["titulo"]
            ??
            null;


        $textoEntrada =
            $entrada["texto"]
            ??
            $entrada["mensagem"]
            ??
            null;


        if (
            is_string(
                $tituloEntrada
            )
            &&
            trim(
                $tituloEntrada
            )
            !== ""
        ) {

            $titulo =
                trim(
                    $tituloEntrada
                );

        }


        if (
            is_string(
                $textoEntrada
            )
            &&
            trim(
                $textoEntrada
            )
            !== ""
        ) {

            $texto =
                trim(
                    $textoEntrada
                );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | FORMATO SIMPLES
    |--------------------------------------------------------------------------
    */

    elseif (
        is_string(
            $entrada
        )
        &&
        trim(
            $entrada
        )
        !== ""
    ) {

        $texto =
            trim(
                $entrada
            );

    }


    /*
    |--------------------------------------------------------------------------
    | FALLBACK
    |--------------------------------------------------------------------------
    */

    if (
        $texto ===
        null
    ) {

        error_log(
            "[Mensagens] Código não encontrado ou inválido: "
            .
            $tipo
            .
            "."
            .
            $codigo
        );


        $titulo =
            "Atenção";


        $texto =
            "Não foi possível concluir a operação.";

    }


    /*
    |--------------------------------------------------------------------------
    | TÍTULO PADRÃO
    |--------------------------------------------------------------------------
    */

    if (
        $titulo ===
        null
    ) {

        $titulo =
            match (
                $tipo
            ) {

                "sucesso" =>
                    "Tudo certo!",

                "erro" =>
                    "Não foi possível concluir",

                "aviso" =>
                    "Atenção",

                default =>
                    "Informação"

            };

    }


    /*
    |--------------------------------------------------------------------------
    | SALVA NA SESSÃO
    |--------------------------------------------------------------------------
    |
    | Mantemos:
    |
    | - titulo;
    | - texto;
    | - mensagem.
    |
    | "mensagem" continua existindo por compatibilidade
    | com componentes antigos.
    |
    |--------------------------------------------------------------------------
    */

    $_SESSION["mensagem"] = [

        "tipo" =>
            $tipo,

        "codigo" =>
            $codigo,

        "titulo" =>
            $titulo,

        "texto" =>
            $texto,

        "mensagem" =>
            $texto

    ];

}


/*
|--------------------------------------------------------------------------
| VERIFICA SE EXISTE MENSAGEM
|--------------------------------------------------------------------------
*/

function existeMensagem(): bool
{

    garantirSessao();


    return
        isset(
            $_SESSION["mensagem"]
        )
        &&
        is_array(
            $_SESSION["mensagem"]
        );

}


/*
|--------------------------------------------------------------------------
| PEGA MENSAGEM
|--------------------------------------------------------------------------
|
| A mensagem é removida imediatamente após ser recuperada.
|
|--------------------------------------------------------------------------
*/

function pegarMensagem(): ?array
{

    garantirSessao();


    if (
        !existeMensagem()
    ) {

        return null;

    }


    $mensagem =
        $_SESSION["mensagem"];


    unset(
        $_SESSION["mensagem"]
    );


    return
        is_array(
            $mensagem
        )
            ?
            $mensagem
            :
            null;

}


/*
|--------------------------------------------------------------------------
| LIMPA MENSAGEM
|--------------------------------------------------------------------------
*/

function limparMensagem(): void
{

    garantirSessao();


    unset(
        $_SESSION["mensagem"]
    );

}


/*
|--------------------------------------------------------------------------
| CATÁLOGO CENTRAL DE ROTAS
|--------------------------------------------------------------------------
*/

function catalogoRotas(): array
{

    static $rotas = null;


    if (
        is_array(
            $rotas
        )
    ) {

        return $rotas;

    }


    $arquivoRotas =
        __DIR__
        .
        "/rotas.php";


    if (
        !is_file(
            $arquivoRotas
        )
    ) {

        throw new RuntimeException(
            "O catálogo de rotas não foi encontrado."
        );

    }


    $resultado =
        require $arquivoRotas;


    if (
        !is_array(
            $resultado
        )
    ) {

        throw new RuntimeException(
            "O catálogo de rotas possui formato inválido."
        );

    }


    $rotas =
        $resultado;


    return $rotas;

}


/*
|--------------------------------------------------------------------------
| BASE URL
|--------------------------------------------------------------------------
*/

function urlBaseAplicacao(): string
{

    if (
        !defined(
            "BASE_URL"
        )
    ) {

        throw new RuntimeException(
            "BASE_URL não está definida."
        );

    }


    return rtrim(
        (string) BASE_URL,
        "/"
    );

}


/*
|--------------------------------------------------------------------------
| CONVERTE PARÂMETRO DE ROTA PARA TEXTO
|--------------------------------------------------------------------------
*/

function valorParametroRota(
    mixed $valor,
    string $nomeParametro,
    string $nomeRota
): string
{

    if (
        is_string(
            $valor
        )
        ||
        is_int(
            $valor
        )
        ||
        is_float(
            $valor
        )
    ) {

        return (string) $valor;

    }


    if (
        $valor instanceof Stringable
    ) {

        return (string) $valor;

    }


    throw new InvalidArgumentException(
        "Valor inválido para o parâmetro "
        .
        $nomeParametro
        .
        " da rota "
        .
        $nomeRota
        .
        "."
    );

}


/*
|--------------------------------------------------------------------------
| GERA URL DE UMA ROTA
|--------------------------------------------------------------------------
*/

function rota(
    string $nome,
    array $parametros = [],
    array $query = []
): string
{

    $nome =
        trim(
            $nome
        );


    if (
        $nome === ""
    ) {

        throw new InvalidArgumentException(
            "O nome da rota não pode ser vazio."
        );

    }


    $rotas =
        catalogoRotas();


    /*
    |--------------------------------------------------------------------------
    | ROTA EXISTE
    |--------------------------------------------------------------------------
    */

    if (
        !array_key_exists(
            $nome,
            $rotas
        )
    ) {

        throw new InvalidArgumentException(
            "Rota não encontrada: "
            .
            $nome
        );

    }


    $configuracao =
        $rotas[$nome];


    if (
        !is_array(
            $configuracao
        )
    ) {

        throw new RuntimeException(
            "Configuração inválida da rota: "
            .
            $nome
        );

    }


    /*
    |--------------------------------------------------------------------------
    | URI
    |--------------------------------------------------------------------------
    */

    $uri =
        $configuracao["uri"]
        ??
        null;


    if (
        !is_string(
            $uri
        )
        ||
        trim(
            $uri
        )
        === ""
    ) {

        throw new RuntimeException(
            "URI inválida da rota: "
            .
            $nome
        );

    }


    $uri =
        trim(
            $uri
        );


    /*
    |--------------------------------------------------------------------------
    | PARÂMETROS DA URI
    |--------------------------------------------------------------------------
    */

    preg_match_all(
        "/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/",
        $uri,
        $matches
    );


    $nomesParametros =
        $matches[1]
        ??
        [];


    $regrasParametros =
        $configuracao["parametros"]
        ??
        [];


    if (
        !is_array(
            $regrasParametros
        )
    ) {

        $regrasParametros =
            [];

    }


    foreach (
        $nomesParametros as
        $nomeParametro
    ) {

        /*
        |--------------------------------------------------------------------------
        | PARÂMETRO OBRIGATÓRIO
        |--------------------------------------------------------------------------
        */

        if (
            !array_key_exists(
                $nomeParametro,
                $parametros
            )
        ) {

            throw new InvalidArgumentException(
                "Parâmetro obrigatório ausente na rota "
                .
                $nome
                .
                ": "
                .
                $nomeParametro
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VALOR
        |--------------------------------------------------------------------------
        */

        $valor =
            valorParametroRota(
                $parametros[$nomeParametro],
                $nomeParametro,
                $nome
            );


        /*
        |--------------------------------------------------------------------------
        | REGEX
        |--------------------------------------------------------------------------
        */

        $regex =
            $regrasParametros[$nomeParametro]
            ??
            null;


        if (
            is_string(
                $regex
            )
            &&
            $regex !== ""
        ) {

            $regexCompleta =
                "#^(?:"
                .
                str_replace(
                    "#",
                    "\\#",
                    $regex
                )
                .
                ")$#D";


            if (
                preg_match(
                    $regexCompleta,
                    $valor
                )
                !== 1
            ) {

                throw new InvalidArgumentException(
                    "Valor inválido para o parâmetro "
                    .
                    $nomeParametro
                    .
                    " da rota "
                    .
                    $nome
                    .
                    "."
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | SUBSTITUI
        |--------------------------------------------------------------------------
        */

        $uri =
            str_replace(
                "{"
                .
                $nomeParametro
                .
                "}",
                rawurlencode(
                    $valor
                ),
                $uri
            );

    }


    /*
    |--------------------------------------------------------------------------
    | PLACEHOLDER RESTANTE
    |--------------------------------------------------------------------------
    */

    if (
        preg_match(
            "/\{[^}]+\}/",
            $uri
        )
        === 1
    ) {

        throw new RuntimeException(
            "Não foi possível gerar completamente a rota "
            .
            $nome
            .
            "."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZA URI
    |--------------------------------------------------------------------------
    */

    if (
        $uri !==
        "/"
    ) {

        $uri =
            "/"
            .
            ltrim(
                $uri,
                "/"
            );


        $uri =
            rtrim(
                $uri,
                "/"
            );

    }


    /*
    |--------------------------------------------------------------------------
    | URL
    |--------------------------------------------------------------------------
    */

    $url =
        urlBaseAplicacao();


    if (
        $uri ===
        "/"
    ) {

        $url .=
            "/";

    }
    else {

        $url .=
            $uri;

    }


    /*
    |--------------------------------------------------------------------------
    | QUERY STRING
    |--------------------------------------------------------------------------
    */

    if (
        !empty(
            $query
        )
    ) {

        $query =
            array_filter(
                $query,
                static function (
                    mixed $valor
                ): bool {

                    return
                        $valor !==
                        null;

                }
            );


        if (
            !empty(
                $query
            )
        ) {

            $queryString =
                http_build_query(
                    $query,
                    "",
                    "&",
                    PHP_QUERY_RFC3986
                );


            if (
                $queryString !==
                ""
            ) {

                $url .=
                    "?"
                    .
                    $queryString;

            }

        }

    }


    return $url;

}


/*
|--------------------------------------------------------------------------
| VERIFICA SE UMA ROTA EXISTE
|--------------------------------------------------------------------------
*/

function rotaExiste(
    string $nome
): bool
{

    $nome =
        trim(
            $nome
        );


    if (
        $nome === ""
    ) {

        return false;

    }


    $rotas =
        catalogoRotas();


    return
        array_key_exists(
            $nome,
            $rotas
        );

}


/*
|--------------------------------------------------------------------------
| NOME DA ROTA ATUAL
|--------------------------------------------------------------------------
*/

function rotaAtual(): ?string
{

    $rota =
        $GLOBALS["ROTA_ATUAL"]
        ??
        null;


    if (
        !is_string(
            $rota
        )
    ) {

        return null;

    }


    $rota =
        trim(
            $rota
        );


    return
        $rota !== ""
            ?
            $rota
            :
            null;

}


/*
|--------------------------------------------------------------------------
| PARÂMETROS DA ROTA ATUAL
|--------------------------------------------------------------------------
*/

function parametrosRota(): array
{

    $parametros =
        $GLOBALS["ROTA_PARAMETROS"]
        ??
        [];


    return
        is_array(
            $parametros
        )
            ?
            $parametros
            :
            [];

}


/*
|--------------------------------------------------------------------------
| PARÂMETRO INDIVIDUAL DA ROTA
|--------------------------------------------------------------------------
*/

function parametroRota(
    string $nome,
    mixed $padrao = null
): mixed
{

    $parametros =
        parametrosRota();


    return
        array_key_exists(
            $nome,
            $parametros
        )
            ?
            $parametros[$nome]
            :
            $padrao;

}