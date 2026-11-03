<?php

session_start();

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../app/Models/Aula.php";

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "professor" ||
    $_SESSION["usuario"]["status"] !== "ativo"
) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION["usuario"];

$aulaModel = new Aula($pdo);

$totalAulas = $aulaModel->contarPorProfessor(
    $usuario["id"]
);

$totalPublicadas = $aulaModel->contarPublicadasPorProfessor(
    $usuario["id"]
);

$totalRascunhos = $aulaModel->contarRascunhosPorProfessor(
    $usuario["id"]
);

$aulasRecentes = $aulaModel->listarRecentesPorProfessor(
    $usuario["id"],
    3
);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Painel Professor | Tamborim Para Todos
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

<div class="dashboard-layout">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

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
                    Área do Professor
                </span>

            </div>

        </div>


        <!-- MENU -->

        <nav class="dashboard-nav">

            <a
                href="painel.php"
                class="dashboard-nav-item active">

                <span>⌂</span>
                Dashboard

            </a>

            <a
                href="minhas-aulas.php"
                class="dashboard-nav-item">

                <span>📚</span>
                Minhas aulas

            </a>

            <a
                href="criar-aula.php"
                class="dashboard-nav-item">

                <span>＋</span>
                Criar aula

            </a>

            <a
                href="materiais.php"
                class="dashboard-nav-item">

                <span>📁</span>
                Materiais

            </a>

            <a
                href="perfil.php"
                class="dashboard-nav-item">

                <span>👤</span>
                Perfil

            </a>

        </nav>


        <!-- PARTE INFERIOR -->

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


    <!-- =====================================================
         CONTEÚDO PRINCIPAL
    ====================================================== -->

    <main class="dashboard-main">


        <!-- =================================================
             TOPO
        ================================================== -->

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Professor
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
                        Professor
                    </span>

                </div>

            </div>

        </div>


        <!-- =================================================
             DESTAQUE
        ================================================== -->

        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Área de ensino
                </span>

                <h2>
                    Organize suas aulas e compartilhe conhecimento
                </h2>

                <p>
                    Crie conteúdos, gerencie suas aulas e acompanhe
                    tudo que você já publicou dentro da plataforma.
                </p>

            </div>

        </section>


        <!-- =================================================
             ESTATÍSTICAS
        ================================================== -->

        <section class="dashboard-stats">

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    📚
                </div>

                <div>

                    <span>
                        Total de aulas
                    </span>

                    <strong>
                        <?= $totalAulas ?>
                    </strong>

                </div>

            </article>


            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    ✅
                </div>

                <div>

                    <span>
                        Publicadas
                    </span>

                    <strong>
                        <?= $totalPublicadas ?>
                    </strong>

                </div>

            </article>


            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    📝
                </div>

                <div>

                    <span>
                        Rascunhos
                    </span>

                    <strong>
                        <?= $totalRascunhos ?>
                    </strong>

                </div>

            </article>

        </section>


        <!-- =================================================
             AÇÕES RÁPIDAS
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Ações rápidas
                    </span>

                    <h2>
                        Gerencie seus conteúdos
                    </h2>

                </div>

            </div>


            <div class="dashboard-actions-grid">


                <!-- CRIAR AULA -->

                <a
                    href="criar-aula.php"
                    class="dashboard-action-card">

                    <div class="dashboard-action-icon">
                        ＋
                    </div>

                    <h3>
                        Criar nova aula
                    </h3>

                    <p>
                        Adicione título, descrição, vídeo,
                        conteúdo e materiais para uma nova aula.
                    </p>

                    <span class="dashboard-action-link">
                        Criar aula →
                    </span>

                </a>


                <!-- MINHAS AULAS -->

                <a
                    href="minhas-aulas.php"
                    class="dashboard-action-card">

                    <div class="dashboard-action-icon">
                        📚
                    </div>

                    <h3>
                        Minhas aulas
                    </h3>

                    <p>
                        Visualize, edite, publique ou exclua
                        os conteúdos que você já criou.
                    </p>

                    <span class="dashboard-action-link">
                        Ver aulas →
                    </span>

                </a>


                <!-- MATERIAIS -->

                <a
                    href="materiais.php"
                    class="dashboard-action-card">

                    <div class="dashboard-action-icon">
                        📁
                    </div>

                    <h3>
                        Materiais
                    </h3>

                    <p>
                        Organize materiais complementares
                        utilizados nas suas aulas.
                    </p>

                    <span class="dashboard-action-link">
                        Ver materiais →
                    </span>

                </a>


                <!-- PERFIL -->

                <a
                    href="perfil.php"
                    class="dashboard-action-card">

                    <div class="dashboard-action-icon">
                        👤
                    </div>

                    <h3>
                        Meu perfil
                    </h3>

                    <p>
                        Consulte seus dados e personalize
                        a aparência da plataforma.
                    </p>

                    <span class="dashboard-action-link">
                        Abrir perfil →
                    </span>

                </a>

            </div>

        </section>


        <!-- =================================================
             AULAS RECENTES
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Conteúdo
                    </span>

                    <h2>
                        Aulas recentes
                    </h2>

                </div>

                <a
                    href="minhas-aulas.php"
                    class="btn-secondary">

                    Ver todas

                </a>

            </div>


            <?php if (empty($aulasRecentes)): ?>

                <div class="empty-state">

                    <div class="empty-state-icon">
                        📚
                    </div>

                    <h3>
                        Você ainda não publicou nenhuma aula
                    </h3>

                    <p>
                        Crie sua primeira aula para começar
                        a disponibilizar conteúdos aos alunos.
                    </p>

                    <a
                        href="criar-aula.php"
                        class="btn-primary lesson-empty-button">

                        Criar primeira aula

                    </a>

                </div>


            <?php else: ?>

                <div class="lesson-list">

                    <?php foreach ($aulasRecentes as $aula): ?>

                        <article class="lesson-row">

                            <div class="lesson-order">

                                <?= (int) $aula["ordem"] ?>

                            </div>


                            <div class="lesson-info">

                                <div class="lesson-title-line">

                                    <h3>
                                        <?= htmlspecialchars(
                                            $aula["titulo"]
                                        ) ?>
                                    </h3>


                                    <?php if (
                                        $aula["status"] === "publicada"
                                    ): ?>

                                        <span class="status-badge active">

                                            Publicada

                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge pending">

                                            Rascunho

                                        </span>

                                    <?php endif; ?>

                                </div>


                                <p>
                                    <?= htmlspecialchars(
                                        $aula["descricao"]
                                    ) ?>
                                </p>


                                <div class="lesson-meta">

                                    <?php if (
                                        !empty($aula["video_url"])
                                    ): ?>

                                        <span>
                                            🎥 Vídeo
                                        </span>

                                    <?php endif; ?>


                                    <?php if (
                                        !empty($aula["material_url"])
                                    ): ?>

                                        <span>
                                            📎 Material
                                        </span>

                                    <?php endif; ?>


                                    <span>

                                        Atualizada em

                                        <?= htmlspecialchars(
                                            $aula["atualizado_em"]
                                        ) ?>

                                    </span>

                                </div>

                            </div>


                            <div class="lesson-actions">

                                <a
                                    href="editar-aula.php?id=<?= (int) $aula["id"] ?>"
                                    class="lesson-action-button">

                                    Editar

                                </a>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>


        <!-- =================================================
             STATUS
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Conta
                    </span>

                    <h2>
                        Status do professor
                    </h2>

                </div>

            </div>


            <div class="dashboard-status-card">

                <div class="dashboard-status-dot"></div>

                <div>

                    <strong>
                        Conta autorizada
                    </strong>

                    <p>
                        Seu perfil de professor está ativo
                        e possui permissão para publicar aulas.
                    </p>

                </div>

            </div>

        </section>

    </main>

</div>

<script src="../assets/js/theme.js"></script>

</body>

</html>