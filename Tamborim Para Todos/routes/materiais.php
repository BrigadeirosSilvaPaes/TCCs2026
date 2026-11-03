<?php

session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../app/Controller/MaterialController.php";


/*
|--------------------------------------------------------------------------
| PROTEÇÃO
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !==
    "professor" ||
    $_SESSION["usuario"]["status"] !==
    "ativo"
) {
    header(
        "Location: ../public/login.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| SOMENTE POST
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"]
    !== "POST"
) {
    exit(
        "Método não permitido."
    );
}


$controller =
    new MaterialController();


$action =
    $_POST["action"] ?? "";


/*
|--------------------------------------------------------------------------
| AÇÕES
|--------------------------------------------------------------------------
*/

switch ($action) {

    case "criar":

        $controller->criar();

        break;


    case "atualizar":

        $controller->atualizar();

        break;


    case "excluir":

        $controller->excluir();

        break;


    default:

        exit(
            "Ação inválida."
        );
}