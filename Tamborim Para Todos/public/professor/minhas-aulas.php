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

$busca = trim($_GET["busca"] ?? "");

if ($busca !== "") {

    $aulas = $aulaModel->pesquisarPorProfessor(
        $usuario["id"],
        $busca
    );

} else {

    $aulas = $aulaModel->listarPorProfessor(
        $usuario["id"]
    );
}

$totalAulas = $aulaModel->contarPorProfessor(
    $usuario["id"]
);

$totalPublicadas =
    $aulaModel->contarPublicadasPorProfessor(
        $usuario["id"]
    );

$totalRascunhos =
    $aulaModel->contarRascunhosPorProfessor(
        $usuario["id"]
    );

$status = $_GET["status"] ?? "";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Minhas Aulas | Tamborim Para Todos
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
                class="dashboard-nav-item">

                <span>⌂</span>
                Dashboard

            </a>

            <a
                href="minhas-aulas.php"
                class="dashboard-nav-item active">

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
         CONTEÚDO
    ====================================================== -->

    <main class="dashboard-main">


        <!-- TOPO -->

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Professor
                </span>

                <h1>
                    Minhas aulas
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
             INTRO
        ================================================== -->

        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Conteúdos
                </span>

                <h2>
                    Organize tudo que você ensina
                </h2>

                <p>
                    Visualize suas aulas, edite conteúdos,
                    publique rascunhos ou retire aulas temporariamente
                    da área dos alunos.
                </p>

            </div>

        </section>


        <!-- =================================================
             MENSAGENS
        ================================================== -->

        <?php if ($status === "created"): ?>

            <div class="dashboard-message-area">

                <div class="alert-success">
                    Aula criada com sucesso.
                </div>

            </div>

        <?php endif; ?>


        <?php if ($status === "updated"): ?>

            <div class="dashboard-message-area">

                <div class="alert-success">
                    Aula atualizada com sucesso.
                </div>

            </div>

        <?php endif; ?>


        <?php if ($status === "published"): ?>

            <div class="dashboard-message-area">

                <div class="alert-success">
                    Aula publicada com sucesso.
                </div>

            </div>

        <?php endif; ?>


        <?php if ($status === "draft"): ?>

            <div class="dashboard-message-area">

                <div class="alert-success">
                    Aula alterada para rascunho.
                </div>

            </div>

        <?php endif; ?>


        <?php if ($status === "deleted"): ?>

            <div class="dashboard-message-area">

                <div class="alert-success">
                    Aula excluída com sucesso.
                </div>

            </div>

        <?php endif; ?>


        <?php if (
            $status === "error" ||
            $status === "invalid"
        ): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Não foi possível realizar a operação.
                </div>

            </div>

        <?php endif; ?>


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
             BARRA DE AÇÕES
        ================================================== -->

        <section class="dashboard-section">

            <div class="lesson-toolbar">

                <form
                    action="minhas-aulas.php"
                    method="GET"
                    class="lesson-search">

                    <input
                        type="search"
                        name="busca"
                        value="<?= htmlspecialchars($busca) ?>"
                        placeholder="Pesquisar minhas aulas...">

                    <button
                        type="submit"
                        class="btn-secondary">

                        Buscar

                    </button>

                </form>


                <a
                    href="criar-aula.php"
                    class="btn-primary">

                    + Nova aula

                </a>

            </div>

        </section>


        <!-- =================================================
             LISTA DE AULAS
        ================================================== -->

        <section class="dashboard-section lesson-list-section">


            <?php if (empty($aulas)): ?>


                <!-- SEM AULAS -->

                <div class="empty-state">

                    <div class="empty-state-icon">
                        📚
                    </div>

                    <h3>
                        Nenhuma aula encontrada
                    </h3>

                    <p>

                        <?php if ($busca !== ""): ?>

                            Não encontramos aulas correspondentes
                            à sua pesquisa.

                        <?php else: ?>

                            Crie sua primeira aula para começar
                            a publicar conteúdos.

                        <?php endif; ?>

                    </p>


                    <?php if ($busca === ""): ?>

                        <a
                            href="criar-aula.php"
                            class="btn-primary lesson-empty-button">

                            Criar primeira aula

                        </a>

                    <?php endif; ?>

                </div>


            <?php else: ?>


                <!-- EXISTEM AULAS -->

                <div class="lesson-list">


                    <?php foreach ($aulas as $aula): ?>


                        <article class="lesson-row">


                            <!-- ORDEM -->

                            <div class="lesson-order">

                                <?= (int) $aula["ordem"] ?>

                            </div>


                            <!-- INFORMAÇÕES -->

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


                            <!-- AÇÕES -->

                            <div class="lesson-actions">


                                <!-- EDITAR -->

                                <a
                                    href="editar-aula.php?id=<?= (int) $aula["id"] ?>"
                                    class="lesson-action-button">

                                    Editar

                                </a>


                                <!-- PUBLICAR / DESPUBLICAR -->

                                <?php if (
                                    $aula["status"] === "rascunho"
                                ): ?>

                                    <form
                                        action="../../routes/aulas.php"
                                        method="POST">

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="publicar">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $aula["id"] ?>">

                                        <button
                                            type="submit"
                                            class="lesson-action-button publish">

                                            Publicar

                                        </button>

                                    </form>

                                <?php else: ?>

                                    <form
                                        action="../../routes/aulas.php"
                                        method="POST">

                                        <input
                                            type="hidden"
                                            name="action"
                                            value="rascunho">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $aula["id"] ?>">

                                        <button
                                            type="submit"
                                            class="lesson-action-button">

                                            Despublicar

                                        </button>

                                    </form>

                                <?php endif; ?>


                                <!-- EXCLUIR -->

                                <form
                                    action="../../routes/aulas.php"
                                    method="POST"
                                    onsubmit="return confirm('Deseja realmente excluir esta aula?');">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="excluir">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $aula["id"] ?>">

                                    <button
                                        type="submit"
                                        class="lesson-action-button danger">

                                        Excluir

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