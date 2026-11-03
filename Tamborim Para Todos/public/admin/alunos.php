<?php

session_start();

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../app/Models/Usuario.php";
require_once __DIR__ . "/../../app/Models/Progresso.php";
require_once __DIR__ . "/../../app/Models/Aula.php";

/*
|--------------------------------------------------------------------------
| PROTEÇÃO
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "admin"
) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION["usuario"];

$usuarioModel = new Usuario($pdo);
$progressoModel = new Progresso($pdo);
$aulaModel = new Aula($pdo);


/*
|--------------------------------------------------------------------------
| CSRF E AÇÃO DE BLOQUEIO / DESBLOQUEIO
|--------------------------------------------------------------------------
*/

if (empty($_SESSION["csrf_bloqueio_aluno"])) {
    $_SESSION["csrf_bloqueio_aluno"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $csrf = $_POST["csrf_token"] ?? "";

    if (
        !hash_equals(
            $_SESSION["csrf_bloqueio_aluno"],
            $csrf
        )
    ) {
        header("Location: alunos.php?status=csrf");
        exit;
    }

    $alunoId = filter_var(
        $_POST["aluno_id"] ?? null,
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 1
            ]
        ]
    );

    if ($alunoId === false || $alunoId === null) {
        header("Location: alunos.php?status=invalid");
        exit;
    }

    $stmtAluno = $pdo->prepare(
        "SELECT id, bloqueado
         FROM usuarios
         WHERE id = :id
         AND tipo = 'aluno'
         LIMIT 1"
    );

    $stmtAluno->execute([
        ":id" => $alunoId
    ]);

    $alunoEncontrado = $stmtAluno->fetch(PDO::FETCH_ASSOC);

    if (!$alunoEncontrado) {
        header("Location: alunos.php?status=invalid");
        exit;
    }

    $novoBloqueio =
        (int) $alunoEncontrado["bloqueado"] === 1
        ? 0
        : 1;

    $stmtBloqueio = $pdo->prepare(
        "UPDATE usuarios
         SET bloqueado = :bloqueado
         WHERE id = :id
         AND tipo = 'aluno'"
    );

    $stmtBloqueio->execute([
        ":bloqueado" => $novoBloqueio,
        ":id" => $alunoId
    ]);

    header(
        "Location: alunos.php?status="
        . ($novoBloqueio === 1 ? "blocked" : "unblocked")
    );
    exit;
}


/*
|--------------------------------------------------------------------------
| MENSAGEM DA AÇÃO
|--------------------------------------------------------------------------
*/

$mensagemAcao = $_GET["status"] ?? "";


/*
|--------------------------------------------------------------------------
| FIM DA MENSAGEM DA AÇÃO
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| BUSCA
|--------------------------------------------------------------------------
*/

$busca = trim($_GET["busca"] ?? "");


/*
|--------------------------------------------------------------------------
| ALUNOS
|--------------------------------------------------------------------------
|
| Aqui vamos usar métodos que adicionaremos ao Usuario.php
| no próximo passo.
|
*/

if ($busca !== "") {

    $alunos = $usuarioModel->pesquisarAlunos(
        $busca
    );

} else {

    $alunos = $usuarioModel->listarAlunos();
}


/*
|--------------------------------------------------------------------------
| ESTATÍSTICAS
|--------------------------------------------------------------------------
*/

$totalAlunos = $usuarioModel->contarAlunos();

$totalAulas = $aulaModel->contarPublicadas();


/*
|--------------------------------------------------------------------------
| ADICIONA PROGRESSO EM CADA ALUNO
|--------------------------------------------------------------------------
*/

foreach ($alunos as &$aluno) {

    $aluno["aulas_concluidas"] =
        $progressoModel->contarConcluidas(
            $aluno["id"]
        );

    $aluno["percentual"] =
        $progressoModel->calcularPercentual(
            $aluno["id"]
        );
}

unset($aluno);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Alunos | Tamborim Para Todos
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

<div class="dashboard-layout">

    <!-- SIDEBAR -->

    <aside class="dashboard-sidebar">

        <div class="dashboard-brand">

            <img
                src="../assets/img/logo.png"
                alt="Logo Tamborim Para Todos"
                class="logo-img">

            <div>

                <strong>
                    Tamborim Para Todos
                </strong>

                <span>
                    Administração
                </span>

            </div>

        </div>


        <nav class="dashboard-nav">

            <a
                href="painel.php"
                class="dashboard-nav-item">

                <span>⌂</span>
                Dashboard

            </a>

            <a
                href="professores.php"
                class="dashboard-nav-item">

                <span>👨‍🏫</span>
                Professores

            </a>

            <a
                href="alunos.php"
                class="dashboard-nav-item active">

                <span>🎓</span>
                Alunos

            </a>

            <a
                href="aulas.php"
                class="dashboard-nav-item">

                <span>📚</span>
                Aulas

            </a>

            <a
                href="relatorios.php"
                class="dashboard-nav-item">

                <span>📊</span>
                Relatórios

            </a>

            <a
                href="perfil.php"
                class="dashboard-nav-item">

                <span>👤</span>
                Perfil

            </a>

        </nav>


        <div class="dashboard-sidebar-bottom">

           

            <a
                href="../inicio.php"
                class="dashboard-nav-item">

                <span>←</span>
                Voltar ao site

            </a>

            <a
                href="../../routes/logout.php"
                class="dashboard-nav-item logout">

                <span>↪</span>
                Sair

            </a>

        </div>

    </aside>


    <!-- CONTEÚDO -->

    <main class="dashboard-main">


        <!-- TOPO -->

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Administração
                </span>

                <h1>
                    Alunos
                </h1>

            </div>


            <div class="dashboard-user">

                <div class="dashboard-avatar">

                    <?= strtoupper(
                        substr(
                            htmlspecialchars(
                                $usuario["nome"]
                            ),
                            0,
                            1
                        )
                    ) ?>

                </div>

                <div>

                    <strong>
                        <?= htmlspecialchars(
                            $usuario["nome"]
                        ) ?>
                    </strong>

                    <span>
                        Administrador
                    </span>

                </div>

            </div>

        </div>


        <!-- APRESENTAÇÃO -->

        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Usuários
                </span>

                <h2>
                    Acompanhe os alunos cadastrados
                </h2>

                <p>
                    Consulte os alunos da plataforma
                    e acompanhe o progresso de cada um
                    nas aulas publicadas.
                </p>

            </div>

        </section>


        <!-- ESTATÍSTICAS -->

        <section class="dashboard-stats">

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    🎓
                </div>

                <div>

                    <span>
                        Alunos cadastrados
                    </span>

                    <strong>
                        <?= $totalAlunos ?>
                    </strong>

                </div>

            </article>


            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    📚
                </div>

                <div>

                    <span>
                        Aulas disponíveis
                    </span>

                    <strong>
                        <?= $totalAulas ?>
                    </strong>

                </div>

            </article>

        </section>


        <!-- MENSAGEM DA AÇÃO -->

        <?php if ($mensagemAcao === "blocked"): ?>

            <div class="alert-success">
                Aluno bloqueado com sucesso.
            </div>

        <?php elseif ($mensagemAcao === "unblocked"): ?>

            <div class="alert-success">
                Aluno desbloqueado com sucesso.
            </div>

        <?php elseif ($mensagemAcao === "csrf"): ?>

            <div class="alert-error">
                A solicitação de segurança expirou. Tente novamente.
            </div>

        <?php elseif ($mensagemAcao === "invalid"): ?>

            <div class="alert-error">
                Aluno inválido ou não encontrado.
            </div>

        <?php endif; ?>


        <!-- BUSCA -->

        <section class="dashboard-section">

            <div class="admin-students-toolbar">

                <form
                    action="alunos.php"
                    method="GET"
                    class="lesson-search">

                    <input
                        type="search"
                        name="busca"
                        value="<?= htmlspecialchars($busca) ?>"
                        placeholder="Pesquisar aluno por nome ou e-mail...">

                    <button
                        type="submit"
                        class="btn-secondary">

                        Buscar

                    </button>

                </form>

                <?php if ($busca !== ""): ?>

                    <a
                        href="alunos.php"
                        class="btn-secondary">

                        Limpar busca

                    </a>

                <?php endif; ?>

            </div>

        </section>


        <!-- LISTA -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Cadastros
                    </span>

                    <h2>
                        Lista de alunos
                    </h2>

                </div>

                <span class="teacher-count-badge">

                    <?= count($alunos) ?>

                    aluno<?= count($alunos) === 1 ? "" : "s" ?>

                </span>

            </div>


            <?php if (empty($alunos)): ?>

                <div class="empty-state">

                    <div class="empty-state-icon">
                        🎓
                    </div>

                    <h3>
                        Nenhum aluno encontrado
                    </h3>

                    <p>
                        Não existem alunos correspondentes
                        à pesquisa realizada.
                    </p>

                </div>

            <?php else: ?>

                <div class="students-list">


                    <?php foreach ($alunos as $aluno): ?>


                        <article class="student-admin-row">


                            <!-- AVATAR -->

                            <div class="student-admin-avatar">

                                <?= strtoupper(
                                    substr(
                                        htmlspecialchars(
                                            $aluno["nome"]
                                        ),
                                        0,
                                        1
                                    )
                                ) ?>

                            </div>


                            <!-- DADOS -->

                            <div class="student-admin-info">

                                <strong>

                                    <?= htmlspecialchars(
                                        $aluno["nome"]
                                    ) ?>

                                </strong>

                                <span>

                                    <?= htmlspecialchars(
                                        $aluno["email"]
                                    ) ?>

                                </span>

                                <small>

                                    Cadastro:

                                    <?= htmlspecialchars(
                                        $aluno["criado_em"]
                                    ) ?>

                                </small>

                            </div>


                            <!-- PROGRESSO -->

                            <div class="student-admin-progress">

                                <div class="student-admin-progress-top">

                                    <span>
                                        Progresso
                                    </span>

                                    <strong>

                                        <?= $aluno["percentual"] ?>%

                                    </strong>

                                </div>


                                <div class="student-progress-bar">

                                    <div
                                        class="student-progress-fill"
                                        style="width: <?= $aluno["percentual"] ?>%;">
                                    </div>

                                </div>


                                <small>

                                    <?= $aluno["aulas_concluidas"] ?>

                                    de

                                    <?= $totalAulas ?>

                                    aulas concluídas

                                </small>

                            </div>


                            <!-- STATUS -->

                            <div class="student-admin-status">

                                <?php if (
                                    $aluno["percentual"] === 100 &&
                                    $totalAulas > 0
                                ): ?>

                                    <span class="status-badge active">
                                        Concluído
                                    </span>

                                <?php elseif (
                                    $aluno["aulas_concluidas"] > 0
                                ): ?>

                                    <span class="status-badge pending">
                                        Em andamento
                                    </span>

                                <?php else: ?>

                                    <span class="status-badge pending">
                                        Não iniciado
                                    </span>

                                <?php endif; ?>

                            </div>

                        


                            <!-- STATUS DA CONTA -->

                            <div class="student-admin-status student-admin-account-status">

                                <span class="student-admin-actions-label">Status da conta</span>

                                <?php if ((int) $aluno["bloqueado"] === 1): ?>

                                    <span class="status-badge blocked">
                                        Bloqueado
                                    </span>

                                <?php else: ?>

                                    <span class="status-badge active">
                                        Ativo
                                    </span>

                                <?php endif; ?>

                            </div>


                            <!-- AÇÕES -->

                            <div class="student-admin-actions">

                                <span class="student-admin-actions-label">Ações</span>

                                <form
                                    action="alunos.php"
                                    method="POST">

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars(
                                            $_SESSION["csrf_bloqueio_aluno"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>">

                                    <input
                                        type="hidden"
                                        name="aluno_id"
                                        value="<?= (int) $aluno["id"] ?>">

                                    <button
                                        type="submit"
                                        class="btn-secondary">

                                        <?= (int) $aluno["bloqueado"] === 1
                                            ? "Desbloquear"
                                            : "Bloquear" ?>

                                    </button>

                                </form>

                            </div>
                        </article>


                    <?php endforeach; ?>


                </div>

            <?php endif; ?>

        </section>

    </main>

</div>


<script src="../assets/js/theme.js"></script>

</body>

</html>