<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO DO BANCO DE DADOS
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Prioridade das credenciais:
|
| 1. Variáveis de ambiente
| 2. secrets.php
| 3. Valores locais do XAMPP (somente desenvolvimento)
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CARREGA CREDENCIAIS PRIVADAS
|--------------------------------------------------------------------------
*/

$secrets = [];

$secretsFile =
    __DIR__
    . "/secrets.php";


if (
    is_file(
        $secretsFile
    )
) {

    $secretsLoaded =
        require $secretsFile;


    if (
        is_array(
            $secretsLoaded
        )
    ) {

        $secrets =
            $secretsLoaded;

    }

}


$databaseSecrets =
    isset(
        $secrets["database"]
    )
    &&
    is_array(
        $secrets["database"]
    )
        ?
        $secrets["database"]
        :
        [];


/*
|--------------------------------------------------------------------------
| VARIÁVEIS DE AMBIENTE
|--------------------------------------------------------------------------
*/

$dbHostEnv =
    trim(
        (string) getenv(
            "DB_HOST"
        )
    );


$dbPortEnv =
    filter_var(
        getenv(
            "DB_PORT"
        ),
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 1,
                "max_range" => 65535
            ]
        ]
    );


$dbNameEnv =
    trim(
        (string) getenv(
            "DB_NAME"
        )
    );


$dbUserEnv =
    trim(
        (string) getenv(
            "DB_USER"
        )
    );


$dbPassEnv =
    (string) getenv(
        "DB_PASS"
    );


$dbCharsetEnv =
    trim(
        (string) getenv(
            "DB_CHARSET"
        )
    );


/*
|--------------------------------------------------------------------------
| DESENVOLVIMENTO
|--------------------------------------------------------------------------
|
| XAMPP local.
|
| Variáveis de ambiente continuam tendo prioridade.
|
|--------------------------------------------------------------------------
*/

if (
    ENVIRONMENT ===
    "development"
) {

    $dbHost =
        $dbHostEnv !== ""
            ?
            $dbHostEnv
            :
            "localhost";


    $dbPort =
        $dbPortEnv !== false
            ?
            (int) $dbPortEnv
            :
            3306;


    $dbName =
        $dbNameEnv !== ""
            ?
            $dbNameEnv
            :
            "tcc";


    $dbUser =
        $dbUserEnv !== ""
            ?
            $dbUserEnv
            :
            "root";


    $dbPass =
        $dbPassEnv !== ""
            ?
            $dbPassEnv
            :
            "";


    $dbCharset =
        $dbCharsetEnv !== ""
            ?
            $dbCharsetEnv
            :
            "utf8mb4";

}


/*
|--------------------------------------------------------------------------
| PRODUÇÃO
|--------------------------------------------------------------------------
|
| Em produção:
|
| 1. procura variável de ambiente;
| 2. se não existir, utiliza secrets.php.
|
|--------------------------------------------------------------------------
*/

else {

    /*
    |--------------------------------------------------------------------------
    | HOST
    |--------------------------------------------------------------------------
    */

    $dbHost =
        $dbHostEnv !== ""
            ?
            $dbHostEnv
            :
            trim(
                (string) (
                    $databaseSecrets["host"]
                    ??
                    ""
                )
            );


    /*
    |--------------------------------------------------------------------------
    | PORTA
    |--------------------------------------------------------------------------
    */

    if (
        $dbPortEnv !== false
    ) {

        $dbPort =
            (int) $dbPortEnv;

    } else {

        $dbPort =
            isset(
                $databaseSecrets["port"]
            )
                ?
                (int) $databaseSecrets["port"]
                :
                3306;

    }


    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    $dbName =
        $dbNameEnv !== ""
            ?
            $dbNameEnv
            :
            trim(
                (string) (
                    $databaseSecrets["database"]
                    ??
                    ""
                )
            );


    /*
    |--------------------------------------------------------------------------
    | USUÁRIO
    |--------------------------------------------------------------------------
    */

    $dbUser =
        $dbUserEnv !== ""
            ?
            $dbUserEnv
            :
            trim(
                (string) (
                    $databaseSecrets["username"]
                    ??
                    ""
                )
            );


    /*
    |--------------------------------------------------------------------------
    | SENHA
    |--------------------------------------------------------------------------
    */

    $dbPass =
        $dbPassEnv !== ""
            ?
            $dbPassEnv
            :
            (string) (
                $databaseSecrets["password"]
                ??
                ""
            );


    /*
    |--------------------------------------------------------------------------
    | CHARSET
    |--------------------------------------------------------------------------
    */

    $dbCharset =
        $dbCharsetEnv !== ""
            ?
            $dbCharsetEnv
            :
            trim(
                (string) (
                    $databaseSecrets["charset"]
                    ??
                    "utf8mb4"
                )
            );


    /*
    |--------------------------------------------------------------------------
    | CONFIGURAÇÕES OBRIGATÓRIAS
    |--------------------------------------------------------------------------
    */

    if (
        $dbHost === ""
        ||
        $dbName === ""
        ||
        $dbUser === ""
        ||
        $dbPass === ""
    ) {

        throw new RuntimeException(
            "As configurações do banco de dados não foram definidas para o ambiente de produção."
        );

    }

}


/*
|--------------------------------------------------------------------------
| CONSTANTES
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "DB_HOST"
    )
) {

    define(
        "DB_HOST",
        $dbHost
    );

}


if (
    !defined(
        "DB_PORT"
    )
) {

    define(
        "DB_PORT",
        $dbPort
    );

}


if (
    !defined(
        "DB_NAME"
    )
) {

    define(
        "DB_NAME",
        $dbName
    );

}


if (
    !defined(
        "DB_USER"
    )
) {

    define(
        "DB_USER",
        $dbUser
    );

}


if (
    !defined(
        "DB_PASS"
    )
) {

    define(
        "DB_PASS",
        $dbPass
    );

}


if (
    !defined(
        "DB_CHARSET"
    )
) {

    define(
        "DB_CHARSET",
        $dbCharset
    );

}


/*
|--------------------------------------------------------------------------
| LIMPEZA
|--------------------------------------------------------------------------
*/

unset(
    $secrets,
    $secretsFile,
    $secretsLoaded,
    $databaseSecrets,
    $dbHostEnv,
    $dbPortEnv,
    $dbNameEnv,
    $dbUserEnv,
    $dbPassEnv,
    $dbCharsetEnv,
    $dbHost,
    $dbPort,
    $dbName,
    $dbUser,
    $dbPass,
    $dbCharset
);