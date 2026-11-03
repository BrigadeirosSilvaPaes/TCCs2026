<?php

session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../app/Controller/AulaController.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: ../public/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Método não permitido.");
}

$controller = new AulaController();

$action = $_POST["action"] ?? "";

switch ($action) {

    case "criar":
        $controller->criar();
        break;

    case "atualizar":
        $controller->atualizar();
        break;

    case "publicar":
        $controller->publicar();
        break;

    case "rascunho":
        $controller->tornarRascunho();
        break;

    case "excluir":
        $controller->excluir();
        break;

    default:
        exit("Ação inválida.");
}