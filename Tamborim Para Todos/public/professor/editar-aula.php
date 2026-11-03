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

$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id) {
    header("Location: minhas-aulas.php?status=invalid");
    exit;
}

$aulaModel = new Aula($pdo);

$aula = $aulaModel->buscarPorId($id);

if (
    !$aula ||
    (int) $aula["professor_id"] !== (int) $usuario["id"]
) {
    header("Location: minhas-aulas.php?status=invalid");
    exit;
}

$status = $_GET["status"] ?? "";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Editar Aula | Tamborim Para Todos</title>

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
                    Área do Professor
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

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Professor
                </span>

                <h1>
                    Editar aula
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
                        Professor
                    </span>

                </div>

            </div>

        </div>


        <!-- INTRO -->

        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Conteúdo existente
                </span>

                <h2>
                    <?= htmlspecialchars($aula["titulo"]) ?>
                </h2>

                <p>
                    Atualize as informações da aula e escolha
                    se ela deve permanecer publicada ou em rascunho.
                </p>

            </div>

        </section>


        <!-- MENSAGENS -->

        <?php if ($status === "empty"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Preencha pelo menos o título e a descrição.
                </div>

            </div>

        <?php endif; ?>

        <?php if ($status === "error"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Não foi possível atualizar a aula.
                </div>

            </div>

        <?php endif; ?>


        <!-- FORMULÁRIO -->

        <section class="dashboard-section">

            <div class="lesson-form-card">

                <form
                    action="../../routes/aulas.php"
                    method="POST"
                    class="lesson-form">

                    <input
                        type="hidden"
                        name="action"
                        value="atualizar">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int) $aula["id"] ?>">

                    <div class="lesson-form-grid">

                        <div class="input-group lesson-field-full">

                            <label for="titulo">
                                Título da aula
                            </label>

                            <input
                                type="text"
                                id="titulo"
                                name="titulo"
                                maxlength="150"
                                value="<?= htmlspecialchars($aula["titulo"]) ?>"
                                required>

                        </div>


                        <div class="input-group lesson-field-full">

                            <label for="descricao">
                                Descrição
                            </label>

                            <textarea
                                id="descricao"
                                name="descricao"
                                rows="4"
                                required><?= htmlspecialchars($aula["descricao"]) ?></textarea>

                        </div>


                        <div class="input-group lesson-field-full">

                            <label for="conteudo">
                                Conteúdo da aula
                            </label>

                            <textarea
                                id="conteudo"
                                name="conteudo"
                                rows="10"><?= htmlspecialchars($aula["conteudo"] ?? "") ?></textarea>

                        </div>


                        <div class="input-group">

                            <label for="video_url">
                                Link do vídeo
                            </label>

                            <input
                                type="url"
                                id="video_url"
                                name="video_url"
                                value="<?= htmlspecialchars($aula["video_url"] ?? "") ?>"
                                placeholder="https://...">

                        </div>


                        <div class="input-group">

                            <label for="material_url">
                                Link de material complementar
                            </label>

                            <input
                                type="url"
                                id="material_url"
                                name="material_url"
                                value="<?= htmlspecialchars($aula["material_url"] ?? "") ?>"
                                placeholder="https://...">

                        </div>


                        <div class="input-group">

                            <label for="ordem">
                                Ordem da aula
                            </label>

                            <input
                                type="number"
                                id="ordem"
                                name="ordem"
                                min="1"
                                value="<?= (int) $aula["ordem"] ?>"
                                required>

                        </div>


                        <div class="input-group">

                            <label for="status">
                                Publicação
                            </label>

                            <select
                                id="status"
                                name="status">

                                <option
                                    value="rascunho"
                                    <?= $aula["status"] === "rascunho" ? "selected" : "" ?>>

                                    Rascunho

                                </option>

                                <option
                                    value="publicada"
                                    <?= $aula["status"] === "publicada" ? "selected" : "" ?>>

                                    Publicada

                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="lesson-form-actions">

                        <a
                            href="minhas-aulas.php"
                            class="btn-secondary">

                            Cancelar

                        </a>

                        <button
                            type="submit"
                            class="btn-enviar lesson-submit">

                            Salvar alterações

                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

</div>

<script src="../assets/js/theme.js"></script>

</body>

</html>