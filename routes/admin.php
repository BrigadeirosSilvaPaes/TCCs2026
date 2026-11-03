<?php

session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../app/Models/Usuario.php";

/*
|--------------------------------------------------------------------------
| PROTEÇÃO DA ROTA
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "admin"
) {
    header("Location: ../public/login.php");
    exit;
}

$usuarioModel = new Usuario($pdo);

/*
|--------------------------------------------------------------------------
| SOMENTE POST
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Método não permitido.");
}

$action = $_POST["action"] ?? "";

/*
|--------------------------------------------------------------------------
| AÇÕES ADMINISTRATIVAS
|--------------------------------------------------------------------------
*/

switch ($action) {

    case "aprovarProfessor":

        $usuarioId = filter_input(
            INPUT_POST,
            "usuario_id",
            FILTER_VALIDATE_INT
        );

        if (!$usuarioId) {
            header(
                "Location: ../public/admin/professores.php?status=invalid"
            );
            exit;
        }

        $aprovado = $usuarioModel->aprovarProfessor(
            $usuarioId
        );

        if ($aprovado) {

            header(
                "Location: ../public/admin/professores.php?status=approved"
            );

            exit;
        }

        header(
            "Location: ../public/admin/professores.php?status=error"
        );

        exit;

    default:

        exit("Ação inválida.");
}