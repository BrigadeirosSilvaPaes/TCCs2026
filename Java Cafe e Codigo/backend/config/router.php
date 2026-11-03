<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| ROUTER CENTRAL DA APLICAÇÃO
|
| Fluxo:
|
| navegador
|     ↓
| .htaccess
|     ↓
| backend/config/router.php
|     ↓
| backend/config/rotas.php
|     ↓
| arquivo responsável
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO PRINCIPAL
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/app.php";


/*
|--------------------------------------------------------------------------
| CATÁLOGO DE ROTAS
|--------------------------------------------------------------------------
*/

$rotas =
    require __DIR__ . "/rotas.php";


if (
    !is_array(
        $rotas
    )
) {

    error_log(
        "[Router] rotas.php não retornou um array válido."
    );


    http_response_code(
        500
    );


    header(
        "Content-Type: text/plain; charset=UTF-8"
    );


    echo
        "Erro interno da aplicação.";


    exit;

}


/*
|--------------------------------------------------------------------------
| RAIZ FÍSICA DO PROJETO
|--------------------------------------------------------------------------
*/

$raizProjeto =
    realpath(
        __DIR__ . "/../../"
    );


if (
    $raizProjeto ===
    false
) {

    error_log(
        "[Router] Não foi possível localizar a raiz do projeto."
    );


    http_response_code(
        500
    );


    header(
        "Content-Type: text/plain; charset=UTF-8"
    );


    echo
        "Erro interno da aplicação.";


    exit;

}


/*
|--------------------------------------------------------------------------
| CABEÇALHO DE SEGURANÇA
|--------------------------------------------------------------------------
*/

header(
    "X-Content-Type-Options: nosniff"
);


/*
|--------------------------------------------------------------------------
| NORMALIZA URI
|--------------------------------------------------------------------------
*/

function routerNormalizarUri(
    string $uri
): string
{

    $uri =
        trim(
            $uri
        );


    if (
        $uri ===
        ""
    ) {

        return "/";

    }


    if (
        !str_starts_with(
            $uri,
            "/"
        )
    ) {

        $uri =
            "/"
            .
            $uri;

    }


    if (
        $uri !==
        "/"
    ) {

        $uri =
            rtrim(
                $uri,
                "/"
            );

    }


    if (
        $uri ===
        ""
    ) {

        return "/";

    }


    return $uri;

}


/*
|--------------------------------------------------------------------------
| OBTÉM CAMINHO DA REQUISIÇÃO
|--------------------------------------------------------------------------
*/

function routerObterCaminho(): string
{

    $requestUri =
        $_SERVER["REQUEST_URI"]
        ??
        "/";


    if (
        !is_string(
            $requestUri
        )
        ||
        $requestUri ===
        ""
    ) {

        $requestUri =
            "/";

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE QUERY STRING
    |--------------------------------------------------------------------------
    */

    $caminho =
        parse_url(
            $requestUri,
            PHP_URL_PATH
        );


    if (
        !is_string(
            $caminho
        )
        ||
        $caminho ===
        ""
    ) {

        $caminho =
            "/";

    }


    /*
    |--------------------------------------------------------------------------
    | CAMINHO BASE
    |--------------------------------------------------------------------------
    */

    $basePath =
        parse_url(
            (string) BASE_URL,
            PHP_URL_PATH
        );


    if (
        !is_string(
            $basePath
        )
    ) {

        $basePath =
            "";

    }


    $basePath =
        rtrim(
            $basePath,
            "/"
        );


    /*
    |--------------------------------------------------------------------------
    | REMOVE SUBDIRETÓRIO DA APLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | Exemplo local:
    |
    | REQUEST_URI:
    | /java-cafe-e-codigo/login
    |
    | BASE_URL:
    | /java-cafe-e-codigo
    |
    | Resultado:
    | /login
    |
    |--------------------------------------------------------------------------
    */

    if (
        $basePath !==
        ""
    ) {

        if (
            $caminho ===
            $basePath
        ) {

            $caminho =
                "/";

        }
        elseif (
            str_starts_with(
                $caminho,
                $basePath . "/"
            )
        ) {

            $caminho =
                substr(
                    $caminho,
                    strlen(
                        $basePath
                    )
                );

        }

    }


    return
        routerNormalizarUri(
            $caminho
        );

}


/*
|--------------------------------------------------------------------------
| COMPILA UMA ROTA
|--------------------------------------------------------------------------
|
| Exemplo:
|
| /aulas/{id}
|
| transforma-se em uma expressão regular utilizando
| as regras definidas em rotas.php.
|
|--------------------------------------------------------------------------
*/

function routerCompilarRota(
    string $uri,
    array $configuracao
): string
{

    $uri =
        routerNormalizarUri(
            $uri
        );


    /*
    |--------------------------------------------------------------------------
    | RAIZ
    |--------------------------------------------------------------------------
    */

    if (
        $uri ===
        "/"
    ) {

        return
            "#^/$#D";

    }


    /*
    |--------------------------------------------------------------------------
    | REGRAS DOS PARÂMETROS
    |--------------------------------------------------------------------------
    */

    $parametros =
        $configuracao["parametros"]
        ??
        [];


    if (
        !is_array(
            $parametros
        )
    ) {

        $parametros =
            [];

    }


    /*
    |--------------------------------------------------------------------------
    | LOCALIZA PLACEHOLDERS
    |--------------------------------------------------------------------------
    */

    preg_match_all(
        "/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/",
        $uri,
        $matches,
        PREG_OFFSET_CAPTURE
    );


    $padrao =
        "";


    $offset =
        0;


    $quantidade =
        isset(
            $matches[0]
        )
        &&
        is_array(
            $matches[0]
        )
            ?
            count(
                $matches[0]
            )
            :
            0;


    for (
        $i = 0;
        $i < $quantidade;
        $i++
    ) {

        $placeholder =
            $matches[0][$i][0];


        $posicao =
            $matches[0][$i][1];


        $nomeParametro =
            $matches[1][$i][0];


        /*
        |--------------------------------------------------------------------------
        | PARTE ESTÁTICA
        |--------------------------------------------------------------------------
        */

        $parteEstatica =
            substr(
                $uri,
                $offset,
                $posicao - $offset
            );


        $padrao .=
            preg_quote(
                $parteEstatica,
                "#"
            );


        /*
        |--------------------------------------------------------------------------
        | REGEX DO PARÂMETRO
        |--------------------------------------------------------------------------
        */

        $regexParametro =
            $parametros[$nomeParametro]
            ??
            "[^/]+";


        if (
            !is_string(
                $regexParametro
            )
            ||
            $regexParametro ===
            ""
        ) {

            $regexParametro =
                "[^/]+";

        }


        /*
        |--------------------------------------------------------------------------
        | PROTEGE DELIMITADOR
        |--------------------------------------------------------------------------
        */

        $regexParametro =
            str_replace(
                "#",
                "\\#",
                $regexParametro
            );


        /*
        |--------------------------------------------------------------------------
        | GRUPO NOMEADO
        |--------------------------------------------------------------------------
        */

        $padrao .=
            "(?P<"
            .
            $nomeParametro
            .
            ">"
            .
            $regexParametro
            .
            ")";


        $offset =
            $posicao
            +
            strlen(
                $placeholder
            );

    }


    /*
    |--------------------------------------------------------------------------
    | RESTANTE DA URI
    |--------------------------------------------------------------------------
    */

    $restante =
        substr(
            $uri,
            $offset
        );


    $padrao .=
        preg_quote(
            $restante,
            "#"
        );


    return
        "#^"
        .
        $padrao
        .
        "/?$#D";

}


/*
|--------------------------------------------------------------------------
| NORMALIZA CAMINHO PARA COMPARAÇÃO
|--------------------------------------------------------------------------
*/

function routerNormalizarCaminhoFisico(
    string $caminho
): string
{

    $caminho =
        str_replace(
            [
                "/",
                "\\"
            ],
            DIRECTORY_SEPARATOR,
            $caminho
        );


    $caminho =
        rtrim(
            $caminho,
            DIRECTORY_SEPARATOR
        );


    /*
    |--------------------------------------------------------------------------
    | WINDOWS NÃO DIFERENCIA MAIÚSCULAS/MINÚSCULAS EM CAMINHOS
    |--------------------------------------------------------------------------
    */

    if (
        PHP_OS_FAMILY ===
        "Windows"
    ) {

        $caminho =
            strtolower(
                $caminho
            );

    }


    return $caminho;

}


/*
|--------------------------------------------------------------------------
| VERIFICA SE CAMINHO ESTÁ DENTRO DE UMA RAIZ
|--------------------------------------------------------------------------
*/

function routerCaminhoPermitido(
    string $arquivo,
    string $raiz
): bool
{

    $arquivoReal =
        realpath(
            $arquivo
        );


    $raizReal =
        realpath(
            $raiz
        );


    if (
        $arquivoReal ===
        false
        ||
        $raizReal ===
        false
    ) {

        return false;

    }


    $arquivoReal =
        routerNormalizarCaminhoFisico(
            $arquivoReal
        );


    $raizReal =
        routerNormalizarCaminhoFisico(
            $raizReal
        );


    if (
        $arquivoReal ===
        $raizReal
    ) {

        return true;

    }


    $prefixo =
        $raizReal
        .
        DIRECTORY_SEPARATOR;


    return
        str_starts_with(
            $arquivoReal,
            $prefixo
        );

}


/*
|--------------------------------------------------------------------------
| NORMALIZA MÉTODOS HTTP
|--------------------------------------------------------------------------
*/

function routerNormalizarMetodos(
    mixed $metodos
): array
{

    if (
        !is_array(
            $metodos
        )
    ) {

        return [
            "GET"
        ];

    }


    $resultado =
        [];


    foreach (
        $metodos as
        $metodo
    ) {

        if (
            !is_string(
                $metodo
            )
        ) {

            continue;

        }


        $metodo =
            strtoupper(
                trim(
                    $metodo
                )
            );


        if (
            $metodo ===
            ""
        ) {

            continue;

        }


        if (
            !in_array(
                $metodo,
                $resultado,
                true
            )
        ) {

            $resultado[] =
                $metodo;

        }

    }


    if (
        empty(
            $resultado
        )
    ) {

        return [
            "GET"
        ];

    }


    return $resultado;

}


/*
|--------------------------------------------------------------------------
| VERIFICA MÉTODO HTTP
|--------------------------------------------------------------------------
|
| HEAD é permitido automaticamente quando GET estiver disponível.
|
|--------------------------------------------------------------------------
*/

function routerMetodoPermitido(
    string $metodoAtual,
    array $metodosPermitidos
): bool
{

    if (
        in_array(
            $metodoAtual,
            $metodosPermitidos,
            true
        )
    ) {

        return true;

    }


    if (
        $metodoAtual ===
        "HEAD"
        &&
        in_array(
            "GET",
            $metodosPermitidos,
            true
        )
    ) {

        return true;

    }


    return false;

}


/*
|--------------------------------------------------------------------------
| HTML DE ERRO
|--------------------------------------------------------------------------
*/

function routerResponderErroHtml(
    int $status,
    string $titulo,
    string $mensagem
): never
{

    http_response_code(
        $status
    );


    header(
        "Content-Type: text/html; charset=UTF-8"
    );


    /*
    |--------------------------------------------------------------------------
    | HEAD NÃO ENVIA CORPO
    |--------------------------------------------------------------------------
    */

    $metodo =
        strtoupper(
            $_SERVER["REQUEST_METHOD"]
            ??
            "GET"
        );


    if (
        $metodo ===
        "HEAD"
    ) {

        exit;

    }


    echo
        "<!DOCTYPE html>"
        .
        "<html lang=\"pt-BR\">"
        .
        "<head>"
        .
        "<meta charset=\"UTF-8\">"
        .
        "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">"
        .
        "<title>"
        .
        htmlspecialchars(
            $titulo,
            ENT_QUOTES | ENT_SUBSTITUTE,
            "UTF-8"
        )
        .
        "</title>"
        .
        "</head>"
        .
        "<body>"
        .
        "<h1>"
        .
        htmlspecialchars(
            $titulo,
            ENT_QUOTES | ENT_SUBSTITUTE,
            "UTF-8"
        )
        .
        "</h1>"
        .
        "<p>"
        .
        htmlspecialchars(
            $mensagem,
            ENT_QUOTES | ENT_SUBSTITUTE,
            "UTF-8"
        )
        .
        "</p>"
        .
        "</body>"
        .
        "</html>";


    exit;

}


/*
|--------------------------------------------------------------------------
| ERRO 404
|--------------------------------------------------------------------------
*/

function routerErro404(): never
{

    routerResponderErroHtml(
        404,
        "Página não encontrada",
        "O endereço solicitado não existe."
    );

}


/*
|--------------------------------------------------------------------------
| ERRO 405
|--------------------------------------------------------------------------
*/

function routerErro405(
    array $metodosPermitidos
): never
{

    $metodosPermitidos =
        routerNormalizarMetodos(
            $metodosPermitidos
        );


    /*
    |--------------------------------------------------------------------------
    | HEAD É IMPLÍCITO QUANDO GET EXISTE
    |--------------------------------------------------------------------------
    */

    if (
        in_array(
            "GET",
            $metodosPermitidos,
            true
        )
        &&
        !in_array(
            "HEAD",
            $metodosPermitidos,
            true
        )
    ) {

        $metodosPermitidos[] =
            "HEAD";

    }


    if (
        !empty(
            $metodosPermitidos
        )
    ) {

        header(
            "Allow: "
            .
            implode(
                ", ",
                $metodosPermitidos
            )
        );

    }


    routerResponderErroHtml(
        405,
        "Requisição não permitida",
        "O método utilizado não é permitido para este endereço."
    );

}


/*
|--------------------------------------------------------------------------
| ERRO INTERNO
|--------------------------------------------------------------------------
*/

function routerErroInterno(
    string $mensagemLog
): never
{

    error_log(
        "[Router] "
        .
        $mensagemLog
    );


    http_response_code(
        500
    );


    header(
        "Content-Type: text/plain; charset=UTF-8"
    );


    $metodo =
        strtoupper(
            $_SERVER["REQUEST_METHOD"]
            ??
            "GET"
        );


    if (
        $metodo !==
        "HEAD"
    ) {

        echo
            "Erro interno da aplicação.";

    }


    exit;

}


/*
|--------------------------------------------------------------------------
| REQUISIÇÃO ATUAL
|--------------------------------------------------------------------------
*/

$caminhoAtual =
    routerObterCaminho();


$metodoAtual =
    strtoupper(
        $_SERVER["REQUEST_METHOD"]
        ??
        "GET"
    );


/*
|--------------------------------------------------------------------------
| MÉTODOS ENCONTRADOS PARA O CAMINHO
|--------------------------------------------------------------------------
|
| Permite diferenciar:
|
| 404 → caminho inexistente
|
| 405 → caminho existe, mas método não é permitido
|
|--------------------------------------------------------------------------
*/

$metodosDoCaminho =
    [];


/*
|--------------------------------------------------------------------------
| PROCURA ROTA
|--------------------------------------------------------------------------
*/

foreach (
    $rotas as
    $nomeRota =>
    $configuracao
) {

    /*
    |--------------------------------------------------------------------------
    | CONFIGURAÇÃO
    |--------------------------------------------------------------------------
    */

    if (
        !is_string(
            $nomeRota
        )
        ||
        trim(
            $nomeRota
        )
        === ""
        ||
        !is_array(
            $configuracao
        )
    ) {

        continue;

    }


    $uriRota =
        $configuracao["uri"]
        ??
        null;


    if (
        !is_string(
            $uriRota
        )
        ||
        trim(
            $uriRota
        )
        === ""
    ) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | COMPILA ROTA
    |--------------------------------------------------------------------------
    */

    $regex =
        routerCompilarRota(
            $uriRota,
            $configuracao
        );


    /*
    |--------------------------------------------------------------------------
    | TESTA CAMINHO
    |--------------------------------------------------------------------------
    */

    $corresponde =
        preg_match(
            $regex,
            $caminhoAtual,
            $matches
        );


    if (
        $corresponde !==
        1
    ) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PERMITIDOS
    |--------------------------------------------------------------------------
    */

    $metodosPermitidos =
        routerNormalizarMetodos(
            $configuracao["metodos"]
            ??
            [
                "GET"
            ]
        );


    $metodosDoCaminho =
        array_values(
            array_unique(
                array_merge(
                    $metodosDoCaminho,
                    $metodosPermitidos
                )
            )
        );


    /*
    |--------------------------------------------------------------------------
    | MÉTODO NÃO CORRESPONDE
    |--------------------------------------------------------------------------
    |
    | Continuamos procurando porque podem existir duas rotas
    | com a mesma URI e métodos diferentes.
    |
    | Exemplo:
    |
    | GET  /recuperar-senha
    | POST /recuperar-senha
    |
    |--------------------------------------------------------------------------
    */

    if (
        !routerMetodoPermitido(
            $metodoAtual,
            $metodosPermitidos
        )
    ) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | EXTRAI PARÂMETROS DINÂMICOS
    |--------------------------------------------------------------------------
    */

    $parametrosRota =
        [];


    foreach (
        $matches as
        $chave =>
        $valor
    ) {

        if (
            is_string(
                $chave
            )
        ) {

            $parametrosRota[$chave] =
                $valor;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA ROTA ATUAL
    |--------------------------------------------------------------------------
    */

    $GLOBALS["ROTA_ATUAL"] =
        $nomeRota;


    $GLOBALS["ROTA_PARAMETROS"] =
        $parametrosRota;


    /*
    |--------------------------------------------------------------------------
    | CONVERTE PARÂMETROS EM $_GET
    |--------------------------------------------------------------------------
    */

    $queryMap =
        $configuracao["query"]
        ??
        [];


    if (
        !is_array(
            $queryMap
        )
    ) {

        $queryMap =
            [];

    }


    foreach (
        $queryMap as
        $parametroRota =>
        $nomeGet
    ) {

        if (
            !is_string(
                $parametroRota
            )
            ||
            !array_key_exists(
                $parametroRota,
                $parametrosRota
            )
        ) {

            continue;

        }


        if (
            !is_string(
                $nomeGet
            )
            ||
            trim(
                $nomeGet
            )
            === ""
        ) {

            continue;

        }


        $_GET[$nomeGet] =
            $parametrosRota[$parametroRota];

    }


    /*
    |--------------------------------------------------------------------------
    | ARQUIVO ESTÁTICO
    |--------------------------------------------------------------------------
    */

    if (
        array_key_exists(
            "arquivo",
            $configuracao
        )
    ) {

        $arquivoRelativo =
            $configuracao["arquivo"];


        if (
            !is_string(
                $arquivoRelativo
            )
            ||
            trim(
                $arquivoRelativo
            )
            === ""
        ) {

            routerErroInterno(
                "Arquivo inválido configurado na rota "
                .
                $nomeRota
                .
                "."
            );

        }


        $arquivo =
            $raizProjeto
            .
            DIRECTORY_SEPARATOR
            .
            str_replace(
                [
                    "/",
                    "\\"
                ],
                DIRECTORY_SEPARATOR,
                $arquivoRelativo
            );


        /*
        |--------------------------------------------------------------------------
        | EXISTÊNCIA
        |--------------------------------------------------------------------------
        */

        if (
            !is_file(
                $arquivo
            )
        ) {

            routerErroInterno(
                "Arquivo da rota "
                .
                $nomeRota
                .
                " não foi encontrado: "
                .
                $arquivoRelativo
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CAMINHO PERMITIDO
        |--------------------------------------------------------------------------
        */

        if (
            !routerCaminhoPermitido(
                $arquivo,
                $raizProjeto
            )
        ) {

            routerErroInterno(
                "A rota "
                .
                $nomeRota
                .
                " tentou acessar um arquivo fora da raiz permitida."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | EXECUTA
        |--------------------------------------------------------------------------
        */

        require $arquivo;


        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | ARQUIVO DINÂMICO
    |--------------------------------------------------------------------------
    |
    | Exemplo:
    |
    | /java/arrays
    |
    | →
    |
    | frontend/conteudos/java/arrays.php
    |
    |--------------------------------------------------------------------------
    */

    if (
        isset(
            $configuracao["arquivo_dinamico"]
        )
        &&
        is_array(
            $configuracao["arquivo_dinamico"]
        )
    ) {

        $arquivoDinamico =
            $configuracao["arquivo_dinamico"];


        $diretorioRelativo =
            $arquivoDinamico["diretorio"]
            ??
            "";


        $nomeParametro =
            $arquivoDinamico["parametro"]
            ??
            "";


        $extensao =
            $arquivoDinamico["extensao"]
            ??
            "";


        /*
        |--------------------------------------------------------------------------
        | CONFIGURAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !is_string(
                $diretorioRelativo
            )
            ||
            trim(
                $diretorioRelativo
            )
            === ""
            ||
            !is_string(
                $nomeParametro
            )
            ||
            trim(
                $nomeParametro
            )
            === ""
            ||
            !is_string(
                $extensao
            )
            ||
            $extensao ===
            ""
        ) {

            routerErroInterno(
                "Configuração dinâmica inválida na rota "
                .
                $nomeRota
                .
                "."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VALOR DO PARÂMETRO
        |--------------------------------------------------------------------------
        */

        $valorParametro =
            $parametrosRota[$nomeParametro]
            ??
            "";


        if (
            !is_string(
                $valorParametro
            )
            ||
            $valorParametro ===
            ""
        ) {

            routerErro404();

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO DEFENSIVA
        |--------------------------------------------------------------------------
        |
        | Mesmo que rotas.php já possua regex, somente aceitamos:
        |
        | a-z
        | 0-9
        | _
        | -
        |
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                "/^[a-z0-9_-]+$/",
                $valorParametro
            )
            !== 1
        ) {

            routerErro404();

        }


        /*
        |--------------------------------------------------------------------------
        | DIRETÓRIO
        |--------------------------------------------------------------------------
        */

        $diretorio =
            $raizProjeto
            .
            DIRECTORY_SEPARATOR
            .
            str_replace(
                [
                    "/",
                    "\\"
                ],
                DIRECTORY_SEPARATOR,
                $diretorioRelativo
            );


        $diretorioReal =
            realpath(
                $diretorio
            );


        if (
            $diretorioReal ===
            false
            ||
            !is_dir(
                $diretorioReal
            )
        ) {

            routerErroInterno(
                "Diretório dinâmico da rota "
                .
                $nomeRota
                .
                " não existe."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DIRETÓRIO DENTRO DO PROJETO
        |--------------------------------------------------------------------------
        */

        if (
            !routerCaminhoPermitido(
                $diretorioReal,
                $raizProjeto
            )
        ) {

            routerErroInterno(
                "Diretório dinâmico fora da raiz permitida na rota "
                .
                $nomeRota
                .
                "."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MONTA ARQUIVO
        |--------------------------------------------------------------------------
        */

        $arquivo =
            $diretorioReal
            .
            DIRECTORY_SEPARATOR
            .
            $valorParametro
            .
            $extensao;


        $arquivoReal =
            realpath(
                $arquivo
            );


        if (
            $arquivoReal ===
            false
            ||
            !is_file(
                $arquivoReal
            )
        ) {

            routerErro404();

        }


        /*
        |--------------------------------------------------------------------------
        | ARQUIVO DENTRO DO DIRETÓRIO DINÂMICO
        |--------------------------------------------------------------------------
        */

        if (
            !routerCaminhoPermitido(
                $arquivoReal,
                $diretorioReal
            )
        ) {

            routerErro404();

        }


        /*
        |--------------------------------------------------------------------------
        | EXECUTA
        |--------------------------------------------------------------------------
        */

        require $arquivoReal;


        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | ROTA SEM DESTINO
    |--------------------------------------------------------------------------
    */

    routerErroInterno(
        "A rota "
        .
        $nomeRota
        .
        " não possui destino configurado."
    );

}


/*
|--------------------------------------------------------------------------
| CAMINHO EXISTE, MAS MÉTODO NÃO É PERMITIDO
|--------------------------------------------------------------------------
*/

if (
    !empty(
        $metodosDoCaminho
    )
) {

    routerErro405(
        $metodosDoCaminho
    );

}


/*
|--------------------------------------------------------------------------
| ROTA NÃO ENCONTRADA
|--------------------------------------------------------------------------
*/

routerErro404();