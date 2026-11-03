<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO DE SESSÃO
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Responsável por:
|
| - configurar cookies de sessão;
| - impedir uso de IDs enviados pela URL;
| - habilitar modo estrito;
| - iniciar a sessão da aplicação.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| SESSÃO JÁ INICIADA
|--------------------------------------------------------------------------
*/

if (
    session_status() ===
    PHP_SESSION_ACTIVE
) {

    return;

}


/*
|--------------------------------------------------------------------------
| APENAS COOKIES
|--------------------------------------------------------------------------
|
| Impede que o PHP aceite IDs de sessão através da URL.
|
|--------------------------------------------------------------------------
*/

ini_set(
    "session.use_only_cookies",
    "1"
);


ini_set(
    "session.use_trans_sid",
    "0"
);


/*
|--------------------------------------------------------------------------
| MODO ESTRITO
|--------------------------------------------------------------------------
|
| O PHP rejeita IDs de sessão que não tenham sido
| previamente criados pelo servidor.
|
|--------------------------------------------------------------------------
*/

ini_set(
    "session.use_strict_mode",
    "1"
);


/*
|--------------------------------------------------------------------------
| HTTP ONLY
|--------------------------------------------------------------------------
|
| JavaScript do navegador não deve acessar o cookie
| responsável pela sessão PHP.
|
|--------------------------------------------------------------------------
*/

ini_set(
    "session.cookie_httponly",
    "1"
);


/*
|--------------------------------------------------------------------------
| DETECTA HTTPS
|--------------------------------------------------------------------------
*/

$httpsAtivo =
    (
        isset(
            $_SERVER["HTTPS"]
        )
        &&
        strtolower(
            (string) $_SERVER["HTTPS"]
        )
        !== "off"
        &&
        (string) $_SERVER["HTTPS"]
        !== ""
    );


/*
|--------------------------------------------------------------------------
| PROXY / HOSPEDAGEM
|--------------------------------------------------------------------------
|
| Algumas hospedagens utilizam proxy reverso e informam
| HTTPS através de X-Forwarded-Proto.
|
|--------------------------------------------------------------------------
*/

$forwardedProto =
    strtolower(
        trim(
            (string) (
                $_SERVER["HTTP_X_FORWARDED_PROTO"]
                ??
                ""
            )
        )
    );


if (
    $forwardedProto ===
    "https"
) {

    $httpsAtivo =
        true;

}


/*
|--------------------------------------------------------------------------
| PARÂMETROS DO COOKIE
|--------------------------------------------------------------------------
|
| secure:
|
| - true em HTTPS;
| - false no localhost HTTP.
|
| samesite=Lax:
|
| - protege contra vários cenários de CSRF;
| - mantém compatibilidade normal com navegação e login.
|
|--------------------------------------------------------------------------
*/

session_set_cookie_params(
    [
        "lifetime" => 0,

        "path" => "/",

        "domain" => "",

        "secure" => $httpsAtivo,

        "httponly" => true,

        "samesite" => "Lax"
    ]
);


/*
|--------------------------------------------------------------------------
| NOME DA SESSÃO
|--------------------------------------------------------------------------
|
| Evita utilizar o nome genérico PHPSESSID.
|
|--------------------------------------------------------------------------
*/

session_name(
    "JCC_SESSION"
);


/*
|--------------------------------------------------------------------------
| INICIA SESSÃO
|--------------------------------------------------------------------------
*/

if (
    !session_start()
) {

    throw new RuntimeException(
        "Não foi possível iniciar a sessão da aplicação."
    );

}


/*
|--------------------------------------------------------------------------
| LIMPEZA
|--------------------------------------------------------------------------
*/

unset(
    $httpsAtivo,
    $forwardedProto
);