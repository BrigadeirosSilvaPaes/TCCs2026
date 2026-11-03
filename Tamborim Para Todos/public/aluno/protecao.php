<?php

/*
|--------------------------------------------------------------------------
| PROTEÇÃO DA ÁREA DO ALUNO
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "aluno"
) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . "/../../config/db.php";

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
    header("Location: ../login.php?status=blocked");
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
    header("Location: ../login.php?status=blocked");
    exit;
}

$_SESSION["usuario"]["bloqueado"] = 0;
