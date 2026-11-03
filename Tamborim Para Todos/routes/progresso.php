<?php

session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../app/Models/Progresso.php";
require_once __DIR__ . "/../app/Models/Aula.php";

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "aluno"
) {
    header("Location: ../public/login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| BLOQUEIO DE CONTA
|--------------------------------------------------------------------------
*/

$usuarioIdProtecao = filter_var(
    $_SESSION["usuario"]["id"] ?? null,
    FILTER_VALIDATE_INT,
    [
        "options" => [
            "min_range" => 1
        ]
    ]
);

if ($usuarioIdProtecao === false || $usuarioIdProtecao === null) {
    session_unset();
    session_destroy();
    header("Location: ../public/login.php?status=blocked");
    exit;
}

$stmtProtecaoAluno = $pdo->prepare(
    "SELECT id, bloqueado
     FROM usuarios
     WHERE id = :id
     AND tipo = 'aluno'
     LIMIT 1"
);

$stmtProtecaoAluno->execute([
    ":id" => $usuarioIdProtecao
]);

$alunoProtecao = $stmtProtecaoAluno->fetch(PDO::FETCH_ASSOC);

if (
    !$alunoProtecao ||
    (int) $alunoProtecao["bloqueado"] === 1
) {
    session_unset();
    session_destroy();
    header("Location: ../public/login.php?status=blocked");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Método não permitido.");
}

$usuarioId = $_SESSION["usuario"]["id"];

$action = $_POST["action"] ?? "";

$aulaId = filter_input(
    INPUT_POST,
    "aula_id",
    FILTER_VALIDATE_INT
);

if (!$aulaId) {
    header(
        "Location: ../public/aluno/aulas.php"
    );

    exit;
}

$aulaModel = new Aula($pdo);

$aula = $aulaModel->buscarPorId($aulaId);

/*
|--------------------------------------------------------------------------
| SEGURANÇA
|--------------------------------------------------------------------------
|
| Só registramos progresso em aula publicada.
|
*/

if (
    !$aula ||
    $aula["status"] !== "publicada"
) {
    header(
        "Location: ../public/aluno/aulas.php"
    );

    exit;
}

$progressoModel = new Progresso($pdo);

switch ($action) {

    case "concluir":

        $progressoModel->concluirAula(
            $usuarioId,
            $aulaId
        );

        header(
            "Location: ../public/aluno/aula.php?id="
            . $aulaId
            . "&status=completed"
        );

        exit;


    case "reabrir":

        $progressoModel->removerConclusao(
            $usuarioId,
            $aulaId
        );

        header(
            "Location: ../public/aluno/aula.php?id="
            . $aulaId
            . "&status=reopened"
        );

        exit;


    default:

        exit("Ação inválida.");
}