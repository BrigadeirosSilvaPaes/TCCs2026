<?php

/*
|--------------------------------------------------------------------------
| FUSO HORÁRIO
|--------------------------------------------------------------------------
*/

date_default_timezone_set("America/Sao_Paulo");


/*
|--------------------------------------------------------------------------
| BANCO DE DADOS
|--------------------------------------------------------------------------
*/

$host = "sql101.infinityfree.com";
$port = 3306;

$dbname = "if0_42866862_tamborim";
$user = "if0_42866862";
$password = "YZuDMc2ob13m";


try {

    $pdo = new PDO(
        "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | ALINHA O MYSQL AO HORÁRIO DE BRASÍLIA
    |--------------------------------------------------------------------------
    */

    $pdo->exec(
        "SET time_zone = '-03:00'"
    );

} catch (PDOException $e) {

    error_log(
        "[Tamborim][Banco] "
        . $e->getMessage()
    );

    http_response_code(500);

    exit(
        "Não foi possível conectar ao banco de dados."
    );
}