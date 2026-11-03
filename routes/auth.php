<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../app/Controller/AuthController.php";

$auth = new AuthController();


/*
|--------------------------------------------------------------------------
| SOMENTE POST
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"]
    !== "POST"
) {
    http_response_code(405);

    exit(
        "Método não permitido."
    );
}


/*
|--------------------------------------------------------------------------
| AÇÃO
|--------------------------------------------------------------------------
*/

$action =
    $_POST["action"]
    ?? "";


/*
|--------------------------------------------------------------------------
| ROTAS DE AUTENTICAÇÃO
|--------------------------------------------------------------------------
*/

switch ($action) {

    case "register":

        $auth->register();

        break;


    case "login":

        $auth->login();

        break;


    case "forgotPassword":

        $auth->forgotPassword();

        break;


    case "verifyRecoveryCode":

        $auth->verifyRecoveryCode();

        break;


    case "resetPassword":

        $auth->resetPassword();

        break;


    default:

        http_response_code(400);

        exit(
            "Ação inválida."
        );
}