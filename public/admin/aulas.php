<?php

session_start();

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../app/Models/Aula.php";

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "admin"
) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION["usuario"];

$aulaModel = new Aula($pdo);

$busca = trim($_GET["busca"] ?? "");
$filtro = $_GET["status_aula"] ?? "";

if ($busca !== "") {

    $aulas = $aulaModel->pesquisar($busca);

} else {

    $aulas = $aulaModel->listarTodas();
}


/*
|--------------------------------------------------------------------------
| FILTRO POR STATUS
|--------------------------------------------------------------------------
*/

if (
    $filtro === "publicada" ||
    $filtro === "rascunho"
) {

    $aulas = array_filter(
        $aulas,
        function ($aula) use ($filtro) {
            return $aula["status"] === $filtro;
        }
    );
}


/*
|--------------------------------------------------------------------------
| ESTATÍSTICAS
|--------------------------------------------------------------------------
*/

$totalAulas = $aulaModel->contarTodas();

$totalPublicadas = $aulaModel->contarPublicadas();

$totalRascunhos =
    $totalAulas - $totalPublicadas;

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
        Gerenciar Aulas | Tamborim Para Todos
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
                class="dashboard-nav-item">

                <span>🎓</span>
                Alunos

            </a>

            <a
                href="aulas.php"
                class="dashboard-nav-item active">

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
                    Aulas
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
                    Conteúdos
                </span>

                <h2>
                    Gerencie todas as aulas
                </h2>

                <p>
                    Visualize os conteúdos publicados pelos professores,
                    acompanhe rascunhos e gerencie o que está disponível
                    para os alunos.
                </p>

            </div>

        </section>


        <!-- MENSAGENS -->

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
                    Aula removida da publicação.
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


        <!-- ESTATÍSTICAS -->

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


        <!-- BUSCA / FILTROS -->

        <section class="dashboard-section">

            <div class="admin-lessons-toolbar">

                <form
                    action="aulas.php"
                    method="GET"
                    class="lesson-search">

                    <input
                        type="search"
                        name="busca"
                        value="<?= htmlspecialchars($busca) ?>"
                        placeholder="Pesquisar aulas...">

                    <button
                        type="submit"
                        class="btn-secondary">

                        Buscar

                    </button>

                </form>


                <div class="material-filters">

                    <a
                        href="aulas.php"
                        class="material-filter <?= $filtro === "" ? "active" : "" ?>">

                        Todas

                    </a>

                    <a
                        href="aulas.php?status_aula=publicada"
                        class="material-filter <?= $filtro === "publicada" ? "active" : "" ?>">

                        Publicadas

                    </a>

                    <a
                        href="aulas.php?status_aula=rascunho"
                        class="material-filter <?= $filtro === "rascunho" ? "active" : "" ?>">

                        Rascunhos

                    </a>

                </div>

            </div>

        </section>


        <!-- LISTAGEM -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Biblioteca
                    </span>

                    <h2>
                        Todas as aulas
                    </h2>

                </div>

                <a
                    href="criar-aula.php"
                    class="btn-enviar">
                    + Criar aula
                </a>

                <span class="teacher-count-badge">

                    <?= count($aulas) ?>
                    aula<?= count($aulas) === 1 ? "" : "s" ?>

                </span>

            </div>


            <?php if (empty($aulas)): ?>

                <div class="empty-state">

                    <div class="empty-state-icon">
                        📚
                    </div>

                    <h3>
                        Nenhuma aula encontrada
                    </h3>

                    <p>
                        Ainda não existem conteúdos correspondentes
                        aos filtros utilizados.
                    </p>

                </div>

            <?php else: ?>


                <div class="admin-lessons-list">


                    <?php foreach ($aulas as $aula): ?>


                        <article class="admin-lesson-row">


                            <!-- ORDEM -->

                            <div class="lesson-order">

                                <?= (int) $aula["ordem"] ?>

                            </div>


                            <!-- INFORMAÇÕES -->

                            <div class="admin-lesson-info">

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


                                <div class="admin-lesson-meta">

                                    <span>

                                        👨‍🏫

                                        <?= htmlspecialchars(
                                            $aula["professor_nome"]
                                        ) ?>

                                    </span>


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

                                        Atualizada:

                                        <?= htmlspecialchars(
                                            $aula["atualizado_em"]
                                        ) ?>

                                    </span>

                                </div>

                            </div>


                            <!-- AÇÕES -->

                            <div class="admin-lesson-actions">


                                                                <a
                                    href="visualizar-aula.php?id=<?= (int) $aula["id"] ?>"
                                    class="lesson-action-button">

                                    Ver conteúdo

                                </a>

                                <a
                                    href="editar-aula.php?id=<?= (int) $aula["id"] ?>"
                                    class="lesson-action-button">

                                    Editar

                                </a>

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