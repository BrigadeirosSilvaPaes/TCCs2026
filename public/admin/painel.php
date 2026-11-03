<?php

session_start();

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "admin"
) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION["usuario"];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel Administrador | Tamborim Para Todos</title>

    <link rel="stylesheet" href="../assets/css/style.css">
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
                class="dashboard-nav-item active">

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

        <!-- TOPBAR -->

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Administração
                </span>

                <h1>
                    Olá,
                    <?= htmlspecialchars($usuario["nome"]) ?>
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
                    Visão geral
                </span>

                <h2>
                    Gerencie a plataforma em um só lugar
                </h2>

                <p>
                    Aprove professores, acompanhe usuários
                    e organize os conteúdos disponíveis
                    no Tamborim Para Todos.
                </p>

            </div>

        </section>


        <!-- CARDS DE RESUMO -->

        <section class="dashboard-stats">

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    👨‍🏫
                </div>

                <div>

                    <span>
                        Professores
                    </span>

                    <strong>
                        Gerenciar
                    </strong>

                </div>

            </article>

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    🎓
                </div>

                <div>

                    <span>
                        Alunos
                    </span>

                    <strong>
                        Visualizar
                    </strong>

                </div>

            </article>

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    📚
                </div>

                <div>

                    <span>
                        Conteúdos
                    </span>

                    <strong>
                        Organizar
                    </strong>

                </div>

            </article>

        </section>


        <!-- AÇÕES RÁPIDAS -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Ações rápidas
                    </span>

                    <h2>
                        O que você deseja fazer?
                    </h2>

                </div>

            </div>

            <div class="dashboard-actions-grid">

                <a
                    href="professores.php"
                    class="dashboard-action-card">

                    <div class="dashboard-action-icon">
                        👨‍🏫
                    </div>

                    <h3>
                        Aprovar professores
                    </h3>

                    <p>
                        Veja solicitações pendentes e autorize novos professores.
                    </p>

                    <span class="dashboard-action-link">
                        Gerenciar →
                    </span>

                </a>

                <a
                    href="alunos.php"
                    class="dashboard-action-card">

                    <div class="dashboard-action-icon">
                        🎓
                    </div>

                    <h3>
                        Gerenciar alunos
                    </h3>

                    <p>
                        Consulte os alunos cadastrados na plataforma.
                    </p>

                    <span class="dashboard-action-link">
                        Em breve
                    </span>

                </a>

                <a
                    href="aulas.php"
                    class="dashboard-action-card">

                    <div class="dashboard-action-icon">
                        📚
                    </div>

                    <h3>
                        Gerenciar aulas
                    </h3>

                    <p>
                        Organize conteúdos, módulos e materiais disponíveis.
                    </p>

                    <span class="dashboard-action-link">
                        Em breve
                    </span>

                </a>

            </div>

        </section>


        <!-- STATUS -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Sistema
                    </span>

                    <h2>
                        Status da plataforma
                    </h2>

                </div>

            </div>

            <div class="dashboard-status-card">

                <div class="dashboard-status-dot"></div>

                <div>

                    <strong>
                        Plataforma funcionando normalmente
                    </strong>

                    <p>
                        Autenticação, cadastro e recuperação de senha estão disponíveis.
                    </p>

                </div>

            </div>

        </section>

    </main>

</div>

<script src="../assets/js/theme.js"></script>

</body>

</html>