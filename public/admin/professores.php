<?php

session_start();

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../app/Models/Usuario.php";

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "admin"
) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION["usuario"];

$usuarioModel = new Usuario($pdo);

$professoresPendentes =
    $usuarioModel->listarProfessoresPendentes();

$professoresAtivos =
    $usuarioModel->listarProfessoresAtivos();

$status = $_GET["status"] ?? "";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Professores | Tamborim Para Todos
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
                class="dashboard-nav-item active">

                <span>👨‍🏫</span>
                Professores

            </a>

            <a
                href="alunos.php"
                class="dashboard-nav-item">

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

        </nav>

        <div class="dashboard-sidebar-bottom">

            

            <a
                href="../inicio.php"
                class="dashboard-nav-item">

                <span>←</span>
                Voltar ao site

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
                    Professores
                </h1>

            </div>

            <div class="dashboard-user">

                <div class="dashboard-avatar">

                    <?= strtoupper(
                        substr(
                            htmlspecialchars($usuario["nome"]),
                            0,
                            1
                        )
                    ) ?>

                </div>

                <div>

                    <strong>
                        <?= htmlspecialchars($usuario["nome"]) ?>
                    </strong>

                    <span>
                        Administrador
                    </span>

                </div>

            </div>

        </div>


        <!-- INTRO -->

        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Permissões
                </span>

                <h2>
                    Gerencie o acesso dos professores
                </h2>

                <p>
                    Analise solicitações pendentes e autorize professores
                    que poderão publicar conteúdos dentro da plataforma.
                </p>

            </div>

        </section>


        <!-- MENSAGENS -->

        <?php if ($status === "approved"): ?>

            <div class="dashboard-message-area">

                <div class="alert-success">
                    Professor aprovado com sucesso.
                </div>

            </div>

        <?php endif; ?>

        <?php if ($status === "error"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Não foi possível aprovar o professor.
                </div>

            </div>

        <?php endif; ?>

        <?php if ($status === "invalid"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Solicitação de professor inválida.
                </div>

            </div>

        <?php endif; ?>


        <!-- RESUMO -->

        <section class="dashboard-stats">

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    ⏳
                </div>

                <div>

                    <span>
                        Aguardando aprovação
                    </span>

                    <strong>
                        <?= count($professoresPendentes) ?>
                    </strong>

                </div>

            </article>

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    ✅
                </div>

                <div>

                    <span>
                        Professores ativos
                    </span>

                    <strong>
                        <?= count($professoresAtivos) ?>
                    </strong>

                </div>

            </article>

        </section>


        <!-- PENDENTES -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Solicitações
                    </span>

                    <h2>
                        Aguardando aprovação
                    </h2>

                </div>

                <span class="teacher-count-badge">
                    <?= count($professoresPendentes) ?>
                    pendente<?= count($professoresPendentes) === 1 ? "" : "s" ?>
                </span>

            </div>

            <?php if (empty($professoresPendentes)): ?>

                <div class="empty-state">

                    <div class="empty-state-icon">
                        ✓
                    </div>

                    <h3>
                        Nenhuma solicitação pendente
                    </h3>

                    <p>
                        Todos os professores cadastrados já foram analisados.
                    </p>

                </div>

            <?php else: ?>

                <div class="teacher-list">

                    <?php foreach (
                        $professoresPendentes
                        as $professor
                    ): ?>

                        <article class="teacher-row">

                            <div class="teacher-avatar">

                                <?= strtoupper(
                                    substr(
                                        htmlspecialchars(
                                            $professor["nome"]
                                        ),
                                        0,
                                        1
                                    )
                                ) ?>

                            </div>

                            <div class="teacher-main-info">

                                <strong>
                                    <?= htmlspecialchars(
                                        $professor["nome"]
                                    ) ?>
                                </strong>

                                <span>
                                    <?= htmlspecialchars(
                                        $professor["email"]
                                    ) ?>
                                </span>

                            </div>

                            <div class="teacher-meta">

                                <span class="status-badge pending">
                                    Pendente
                                </span>

                                <small>
                                    Cadastro:
                                    <?= htmlspecialchars(
                                        $professor["criado_em"]
                                    ) ?>
                                </small>

                            </div>

                            <form
                                action="../../routes/admin.php"
                                method="POST"
                                class="teacher-action-form">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="aprovarProfessor">

                                <input
                                    type="hidden"
                                    name="usuario_id"
                                    value="<?= (int) $professor["id"] ?>">

                                <button
                                    type="submit"
                                    class="btn-enviar teacher-approve-button">

                                    Aprovar

                                </button>

                            </form>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>


        <!-- ATIVOS -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Autorizados
                    </span>

                    <h2>
                        Professores ativos
                    </h2>

                </div>

                <span class="teacher-count-badge">
                    <?= count($professoresAtivos) ?>
                    ativo<?= count($professoresAtivos) === 1 ? "" : "s" ?>
                </span>

            </div>

            <?php if (empty($professoresAtivos)): ?>

                <div class="empty-state">

                    <div class="empty-state-icon">
                        👨‍🏫
                    </div>

                    <h3>
                        Nenhum professor ativo
                    </h3>

                    <p>
                        Quando um professor for aprovado,
                        ele aparecerá nesta lista.
                    </p>

                </div>

            <?php else: ?>

                <div class="teacher-list">

                    <?php foreach (
                        $professoresAtivos
                        as $professor
                    ): ?>

                        <article class="teacher-row">

                            <div class="teacher-avatar">

                                <?= strtoupper(
                                    substr(
                                        htmlspecialchars(
                                            $professor["nome"]
                                        ),
                                        0,
                                        1
                                    )
                                ) ?>

                            </div>

                            <div class="teacher-main-info">

                                <strong>
                                    <?= htmlspecialchars(
                                        $professor["nome"]
                                    ) ?>
                                </strong>

                                <span>
                                    <?= htmlspecialchars(
                                        $professor["email"]
                                    ) ?>
                                </span>

                            </div>

                            <div class="teacher-meta">

                                <span class="status-badge active">
                                    Ativo
                                </span>

                                <small>
                                    Professor autorizado
                                </small>

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