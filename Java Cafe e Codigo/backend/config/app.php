<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES GERAIS DA APLICAÇÃO
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Configura informações globais, ambiente,
| caminhos web e tratamento de erros.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| FUSO HORÁRIO
|--------------------------------------------------------------------------
*/

date_default_timezone_set(
    "America/Sao_Paulo"
);


/*
|--------------------------------------------------------------------------
| INFORMAÇÕES DO SITE
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "SITE_NAME"
    )
) {

    define(
        "SITE_NAME",
        "JAVA, CAFÉ E CÓDIGO"
    );

}


if (
    !defined(
        "SITE_VERSION"
    )
) {

    define(
        "SITE_VERSION",
        "1.0.0"
    );

}


/*
|--------------------------------------------------------------------------
| IDENTIFICA O AMBIENTE
|--------------------------------------------------------------------------
|
| Prioridade:
|
| 1. APP_ENV
| 2. Detecção automática de localhost
| 3. Produção
|
|--------------------------------------------------------------------------
*/

$ambienteConfigurado =
    strtolower(
        trim(
            (string) getenv(
                "APP_ENV"
            )
        )
    );


if (
    in_array(
        $ambienteConfigurado,
        [
            "development",
            "production"
        ],
        true
    )
) {

    $ambiente =
        $ambienteConfigurado;

} else {

    /*
    |--------------------------------------------------------------------------
    | HOST ATUAL
    |--------------------------------------------------------------------------
    */

    $hostAtual =
        strtolower(
            trim(
                (string) (
                    $_SERVER["HTTP_HOST"]
                    ??
                    $_SERVER["SERVER_NAME"]
                    ??
                    ""
                )
            )
        );


    /*
    |--------------------------------------------------------------------------
    | REMOVE PORTA
    |--------------------------------------------------------------------------
    |
    | localhost:80
    | localhost:8080
    |
    |--------------------------------------------------------------------------
    */

    $hostSemPorta =
        preg_replace(
            "/:\d+$/",
            "",
            $hostAtual
        )
        ??
        $hostAtual;


    /*
    |--------------------------------------------------------------------------
    | HOSTS LOCAIS
    |--------------------------------------------------------------------------
    */

    $hostsLocais = [
        "",
        "localhost",
        "127.0.0.1",
        "::1"
    ];


    $ambiente =
        in_array(
            $hostSemPorta,
            $hostsLocais,
            true
        )
            ?
            "development"
            :
            "production";

}


/*
|--------------------------------------------------------------------------
| CONSTANTE DO AMBIENTE
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "ENVIRONMENT"
    )
) {

    define(
        "ENVIRONMENT",
        $ambiente
    );

}


/*
|--------------------------------------------------------------------------
| URL BASE
|--------------------------------------------------------------------------
|
| BASE_URL representa somente o caminho onde
| a aplicação está instalada.
|
| Local:
|
| /java-cafe-e-codigo
|
| Produção na raiz:
|
| ""
|
|--------------------------------------------------------------------------
*/

$baseUrlConfigurada =
    trim(
        (string) getenv(
            "APP_BASE_URL"
        )
    );


if (
    $baseUrlConfigurada !== ""
) {

    $baseUrl =
        rtrim(
            $baseUrlConfigurada,
            "/"
        );

} else {

    $baseUrl =
        ENVIRONMENT ===
        "development"
            ?
            "/java-cafe-e-codigo"
            :
            "";

}


/*
|--------------------------------------------------------------------------
| CONSTANTE BASE_URL
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "BASE_URL"
    )
) {

    define(
        "BASE_URL",
        $baseUrl
    );

}


/*
|--------------------------------------------------------------------------
| URL COMPLETA DA APLICAÇÃO
|--------------------------------------------------------------------------
|
| APP_URL é utilizada principalmente para:
|
| - confirmação de cadastro;
| - recuperação de senha;
| - links enviados por e-mail;
| - URLs absolutas.
|
| Exemplos:
|
| http://localhost/java-cafe-e-codigo
|
| https://javacafeecodigo.infinityfree.me
|
|--------------------------------------------------------------------------
*/

$appUrlConfigurada =
    trim(
        (string) getenv(
            "APP_URL"
        )
    );


if (
    $appUrlConfigurada !== ""
) {

    $appUrl =
        rtrim(
            $appUrlConfigurada,
            "/"
        );

} else {

    /*
    |--------------------------------------------------------------------------
    | DESENVOLVIMENTO
    |--------------------------------------------------------------------------
    */

    if (
        ENVIRONMENT ===
        "development"
    ) {

        $appUrl =
            "http://localhost"
            .
            BASE_URL;

    }


    /*
    |--------------------------------------------------------------------------
    | PRODUÇÃO
    |--------------------------------------------------------------------------
    */

    else {

        /*
        |--------------------------------------------------------------------------
        | HTTPS
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
                    !==
                    "off"
            )

            ||

            (
                isset(
                    $_SERVER["HTTP_X_FORWARDED_PROTO"]
                )
                &&
                strtolower(
                    (string) $_SERVER["HTTP_X_FORWARDED_PROTO"]
                )
                    ===
                    "https"
            );


        $protocolo =
            $httpsAtivo
                ?
                "https"
                :
                "http";


        /*
        |--------------------------------------------------------------------------
        | HOST
        |--------------------------------------------------------------------------
        */

        $hostAplicacao =
            trim(
                (string) (
                    $_SERVER["HTTP_HOST"]
                    ??
                    $_SERVER["SERVER_NAME"]
                    ??
                    ""
                )
            );


        if (
            $hostAplicacao === ""
        ) {

            throw new RuntimeException(
                "Não foi possível identificar o domínio da aplicação."
            );

        }


        $appUrl =
            $protocolo
            .
            "://"
            .
            $hostAplicacao
            .
            BASE_URL;

    }

}


/*
|--------------------------------------------------------------------------
| CONSTANTE APP_URL
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "APP_URL"
    )
) {

    define(
        "APP_URL",
        $appUrl
    );

}


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO DE ERROS
|--------------------------------------------------------------------------
*/

error_reporting(
    E_ALL
);


/*
|--------------------------------------------------------------------------
| DESENVOLVIMENTO
|--------------------------------------------------------------------------
*/

if (
    ENVIRONMENT ===
    "development"
) {

    ini_set(
        "display_errors",
        "1"
    );


    ini_set(
        "display_startup_errors",
        "1"
    );


    ini_set(
        "log_errors",
        "1"
    );

}


/*
|--------------------------------------------------------------------------
| PRODUÇÃO
|--------------------------------------------------------------------------
*/

else {

    /*
    |--------------------------------------------------------------------------
    | NÃO MOSTRA ERROS AO VISITANTE
    |--------------------------------------------------------------------------
    */

    ini_set(
        "display_errors",
        "0"
    );


    ini_set(
        "display_startup_errors",
        "0"
    );


    /*
    |--------------------------------------------------------------------------
    | REGISTRA ERROS NO SERVIDOR
    |--------------------------------------------------------------------------
    */

    ini_set(
        "log_errors",
        "1"
    );

}


/*
|--------------------------------------------------------------------------
| LIMPEZA
|--------------------------------------------------------------------------
*/

unset(
    $ambienteConfigurado,
    $ambiente,
    $hostAtual,
    $hostSemPorta,
    $hostsLocais,
    $baseUrlConfigurada,
    $baseUrl,
    $appUrlConfigurada,
    $appUrl,
    $httpsAtivo,
    $protocolo,
    $hostAplicacao
);