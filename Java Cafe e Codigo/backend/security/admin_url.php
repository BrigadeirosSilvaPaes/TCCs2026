<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/helpers.php";


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
|
| O identificador da URL administrativa:
|
| - pertence à sessão atual;
| - muda a cada novo login administrativo;
| - não substitui o admin_token;
| - não substitui a sessão PHP;
| - não substitui verificar_admin.php.
|
*/

const ADMIN_URL_SESSION_KEY =
    "admin_url_token";


const ADMIN_URL_PARAM =
    "acesso";


/*
|--------------------------------------------------------------------------
| ROTAS ADMINISTRATIVAS PÚBLICAS
|--------------------------------------------------------------------------
|
| Não permitimos qualquer arquivo arbitrário.
|
| Apenas páginas explicitamente cadastradas aqui
| podem ser transformadas em uma rota administrativa.
|
*/

const ADMIN_URL_ROUTES = [

    "inicio.php" =>
        "",

    "usuarios.php" =>
        "usuarios",

    "professores.php" =>
        "professores"

];


/*
|--------------------------------------------------------------------------
| GARANTE ESTRUTURA DE SESSÃO
|--------------------------------------------------------------------------
*/

function adminUrlGarantirSessao(): void
{

    garantirSessao();


    if (
        !isset($_SESSION["seguranca"]) ||
        !is_array($_SESSION["seguranca"])
    ) {

        $_SESSION["seguranca"] = [];

    }

}


/*
|--------------------------------------------------------------------------
| GERA TOKEN
|--------------------------------------------------------------------------
|
| 24 bytes aleatórios:
|
| random_bytes(24)
|
| Depois:
|
| bin2hex()
|
| Resultado:
|
| 48 caracteres hexadecimais.
|
*/

function adminUrlGerarToken(): string
{

    return bin2hex(
        random_bytes(24)
    );

}


/*
|--------------------------------------------------------------------------
| TOKEN ATUAL
|--------------------------------------------------------------------------
*/

function adminUrlToken(): string
{

    adminUrlGarantirSessao();


    $token =
        $_SESSION["seguranca"][ADMIN_URL_SESSION_KEY]
        ?? null;


    /*
    |--------------------------------------------------------------------------
    | TOKEN EXISTENTE VÁLIDO
    |--------------------------------------------------------------------------
    */

    if (
        is_string($token) &&
        preg_match(
            "/^[a-f0-9]{48}$/",
            $token
        ) === 1
    ) {

        return $token;

    }


    /*
    |--------------------------------------------------------------------------
    | CRIA TOKEN
    |--------------------------------------------------------------------------
    */

    $token =
        adminUrlGerarToken();


    $_SESSION["seguranca"][ADMIN_URL_SESSION_KEY] =
        $token;


    return $token;

}


/*
|--------------------------------------------------------------------------
| REGENERA TOKEN
|--------------------------------------------------------------------------
|
| Utilizado principalmente depois do login administrativo.
|
*/

function adminUrlRegenerar(): string
{

    adminUrlGarantirSessao();


    $novoToken =
        adminUrlGerarToken();


    $_SESSION["seguranca"][ADMIN_URL_SESSION_KEY] =
        $novoToken;


    return $novoToken;

}


/*
|--------------------------------------------------------------------------
| TOKEN RECEBIDO
|--------------------------------------------------------------------------
|
| O Apache recebe, por exemplo:
|
| /admin/TOKEN/usuarios
|
| e internamente reescreve para:
|
| /frontend/pages/admin/usuarios.php?acesso=TOKEN
|
| Portanto o PHP continua recebendo:
|
| $_GET["acesso"]
|
*/

function adminUrlTokenRecebido(): string
{

    $token =
        $_GET[ADMIN_URL_PARAM]
        ?? "";


    if (
        !is_string($token)
    ) {

        return "";

    }


    return trim(
        $token
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA TOKEN
|--------------------------------------------------------------------------
*/

function adminUrlValidar(
    ?string $tokenRecebido = null
): bool
{

    adminUrlGarantirSessao();


    /*
    |--------------------------------------------------------------------------
    | TOKEN DA SESSÃO
    |--------------------------------------------------------------------------
    */

    $tokenSessao =
        $_SESSION["seguranca"][ADMIN_URL_SESSION_KEY]
        ?? null;


    if (
        !is_string($tokenSessao) ||
        preg_match(
            "/^[a-f0-9]{48}$/",
            $tokenSessao
        ) !== 1
    ) {

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | TOKEN RECEBIDO
    |--------------------------------------------------------------------------
    */

    if (
        $tokenRecebido === null
    ) {

        $tokenRecebido =
            adminUrlTokenRecebido();

    }


    if (
        !is_string($tokenRecebido) ||
        preg_match(
            "/^[a-f0-9]{48}$/",
            $tokenRecebido
        ) !== 1
    ) {

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | COMPARAÇÃO SEGURA
    |--------------------------------------------------------------------------
    */

    return hash_equals(
        $tokenSessao,
        $tokenRecebido
    );

}


/*
|--------------------------------------------------------------------------
| MONTA URL ADMINISTRATIVA
|--------------------------------------------------------------------------
|
| Agora NÃO geramos mais:
|
| /frontend/pages/admin/inicio.php?acesso=TOKEN
|
| Geramos:
|
| /admin/TOKEN
|
|
| E:
|
| adminUrl("usuarios.php")
|
| gera:
|
| /admin/TOKEN/usuarios
|
*/

function adminUrl(
    string $pagina
): string
{

    /*
    |--------------------------------------------------------------------------
    | NORMALIZA
    |--------------------------------------------------------------------------
    */

    $pagina =
        basename(
            trim(
                $pagina
            )
        );


    /*
    |--------------------------------------------------------------------------
    | PÁGINA PERMITIDA
    |--------------------------------------------------------------------------
    |
    | Só aceitamos páginas cadastradas em ADMIN_URL_ROUTES.
    |
    */

    if (
        !array_key_exists(
            $pagina,
            ADMIN_URL_ROUTES
        )
    ) {

        throw new InvalidArgumentException(
            "Página administrativa inválida."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | TOKEN
    |--------------------------------------------------------------------------
    */

    $token =
        adminUrlToken();


    /*
    |--------------------------------------------------------------------------
    | ROTA
    |--------------------------------------------------------------------------
    */

    $rota =
        ADMIN_URL_ROUTES[$pagina];


    /*
    |--------------------------------------------------------------------------
    | BASE ADMINISTRATIVA
    |--------------------------------------------------------------------------
    */

    $url =
        rtrim(
            BASE_URL,
            "/"
        ) .
        "/admin/" .
        rawurlencode(
            $token
        );


    /*
    |--------------------------------------------------------------------------
    | PÁGINA INICIAL
    |--------------------------------------------------------------------------
    |
    | inicio.php:
    |
    | /admin/TOKEN
    |
    */

    if (
        $rota === ""
    ) {

        return $url;

    }


    /*
    |--------------------------------------------------------------------------
    | SUBROTA
    |--------------------------------------------------------------------------
    |
    | usuarios.php:
    |
    | /admin/TOKEN/usuarios
    |
    */

    return
        $url .
        "/" .
        rawurlencode(
            $rota
        );

}


/*
|--------------------------------------------------------------------------
| EXIGE TOKEN VÁLIDO
|--------------------------------------------------------------------------
*/

function adminUrlExigir(): void
{

    /*
    |--------------------------------------------------------------------------
    | REFERRER
    |--------------------------------------------------------------------------
    |
    | Como existe um identificador na URL administrativa,
    | não permitimos que o navegador envie a URL completa
    | como Referer para outros sites.
    |
    */

    header(
        "Referrer-Policy: no-referrer"
    );


    /*
    |--------------------------------------------------------------------------
    | CACHE
    |--------------------------------------------------------------------------
    */

    header(
        "Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private"
    );


    header(
        "Pragma: no-cache"
    );


    header(
        "Expires: 0"
    );


    /*
    |--------------------------------------------------------------------------
    | VALIDA TOKEN
    |--------------------------------------------------------------------------
    */

    if (
        adminUrlValidar()
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | TOKEN INVÁLIDO
    |--------------------------------------------------------------------------
    |
    | Não informamos ao visitante:
    |
    | - se o token existia;
    | - se expirou;
    | - qual token era esperado;
    | - qualquer informação sobre a sessão administrativa.
    |
    */

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php"
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| LIMPA TOKEN
|--------------------------------------------------------------------------
*/

function adminUrlLimpar(): void
{

    adminUrlGarantirSessao();


    unset(
        $_SESSION["seguranca"][ADMIN_URL_SESSION_KEY]
    );

}