<?php

/*
|--------------------------------------------------------------------------
| CONEXÃO COM O BANCO DE DADOS
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Responsável por criar e disponibilizar a conexão PDO
| utilizada por toda a aplicação.
|
| A variável:
|
| $pdo
|
| fica disponível após o carregamento do bootstrap.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| EXTENSÃO PDO
|--------------------------------------------------------------------------
*/

if (
    !class_exists(
        "PDO"
    )
) {

    throw new RuntimeException(
        "A extensão PDO não está disponível no servidor."
    );

}


/*
|--------------------------------------------------------------------------
| DRIVER MYSQL
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        "mysql",
        PDO::getAvailableDrivers(),
        true
    )
) {

    throw new RuntimeException(
        "O driver PDO MySQL não está disponível no servidor."
    );

}


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES OBRIGATÓRIAS
|--------------------------------------------------------------------------
*/

$constantesBanco = [

    "DB_HOST",

    "DB_PORT",

    "DB_NAME",

    "DB_USER",

    "DB_PASS",

    "DB_CHARSET"

];


foreach (
    $constantesBanco
    as
    $constante
) {

    if (
        !defined(
            $constante
        )
    ) {

        throw new RuntimeException(
            "Configuração obrigatória do banco não encontrada: "
            .
            $constante
        );

    }

}


/*
|--------------------------------------------------------------------------
| VALIDA PORTA
|--------------------------------------------------------------------------
*/

$dbPort =
    (int) DB_PORT;


if (
    $dbPort < 1
    ||
    $dbPort > 65535
) {

    throw new RuntimeException(
        "A porta configurada para o banco de dados é inválida."
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA CHARSET
|--------------------------------------------------------------------------
|
| Evita inserir qualquer valor inesperado diretamente no DSN.
|
|--------------------------------------------------------------------------
*/

$dbCharset =
    trim(
        (string) DB_CHARSET
    );


if (
    !preg_match(
        '/^[A-Za-z0-9_]+$/',
        $dbCharset
    )
) {

    throw new RuntimeException(
        "O charset configurado para o banco de dados é inválido."
    );

}


/*
|--------------------------------------------------------------------------
| DSN
|--------------------------------------------------------------------------
|
| Desenvolvimento:
|
| mysql:host=localhost;port=3306;dbname=...;charset=utf8mb4
|
| Produção:
|
| mysql:host=sql301.infinityfree.com;port=3306;dbname=...;charset=utf8mb4
|
|--------------------------------------------------------------------------
*/

$dsn =

    "mysql:host="
    .
    DB_HOST

    .
    ";port="
    .
    $dbPort

    .
    ";dbname="
    .
    DB_NAME

    .
    ";charset="
    .
    $dbCharset;


/*
|--------------------------------------------------------------------------
| OPÇÕES PDO
|--------------------------------------------------------------------------
*/

$opcoesPdo = [

    /*
    |--------------------------------------------------------------------------
    | ERROS COMO EXCEÇÕES
    |--------------------------------------------------------------------------
    */

    PDO::ATTR_ERRMODE =>
        PDO::ERRMODE_EXCEPTION,


    /*
    |--------------------------------------------------------------------------
    | RESULTADOS ASSOCIATIVOS
    |--------------------------------------------------------------------------
    */

    PDO::ATTR_DEFAULT_FETCH_MODE =>
        PDO::FETCH_ASSOC,


    /*
    |--------------------------------------------------------------------------
    | PREPARED STATEMENTS NATIVOS
    |--------------------------------------------------------------------------
    */

    PDO::ATTR_EMULATE_PREPARES =>
        false,


    /*
    |--------------------------------------------------------------------------
    | PRESERVA TIPOS QUANDO POSSÍVEL
    |--------------------------------------------------------------------------
    */

    PDO::ATTR_STRINGIFY_FETCHES =>
        false

];


/*
|--------------------------------------------------------------------------
| CONEXÃO
|--------------------------------------------------------------------------
*/

try {

    $pdo =
        new PDO(

            $dsn,

            DB_USER,

            DB_PASS,

            $opcoesPdo

        );

}
catch (
    PDOException $erro
) {

    /*
    |--------------------------------------------------------------------------
    | REGISTRA ERRO REAL
    |--------------------------------------------------------------------------
    |
    | O erro técnico fica registrado no log do servidor.
    |
    | Nunca exibimos senha ou demais credenciais ao visitante.
    |
    |--------------------------------------------------------------------------
    */

    error_log(
        "[BancoDeDados] Falha na conexão PDO: "
        .
        $erro->getMessage()
    );


    /*
    |--------------------------------------------------------------------------
    | STATUS HTTP
    |--------------------------------------------------------------------------
    */

    if (
        !headers_sent()
    ) {

        http_response_code(
            500
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DESENVOLVIMENTO
    |--------------------------------------------------------------------------
    |
    | No computador local podemos exibir detalhes
    | para facilitar a correção.
    |
    |--------------------------------------------------------------------------
    */

    if (
        defined(
            "ENVIRONMENT"
        )
        &&
        ENVIRONMENT ===
        "development"
    ) {

        die(
            "Erro na conexão com o banco de dados: "
            .
            htmlspecialchars(
                $erro->getMessage(),
                ENT_QUOTES,
                "UTF-8"
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PRODUÇÃO
    |--------------------------------------------------------------------------
    |
    | O visitante recebe apenas uma mensagem genérica.
    |
    |--------------------------------------------------------------------------
    */

    die(
        "Não foi possível conectar ao banco de dados."
    );

}


/*
|--------------------------------------------------------------------------
| LIMPEZA
|--------------------------------------------------------------------------
*/

unset(
    $dsn,
    $opcoesPdo,
    $constantesBanco,
    $constante,
    $dbPort,
    $dbCharset
);