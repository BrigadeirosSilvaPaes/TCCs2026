<?php

session_start();

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "professor" ||
    $_SESSION["usuario"]["status"] !== "ativo"
) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION["usuario"];

$status = $_GET["status"] ?? "";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Criar Aula | Tamborim Para Todos</title>

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
                class="dashboard-nav-item">

                <span>📚</span>
                Minhas aulas

            </a>

            <a
                href="criar-aula.php"
                class="dashboard-nav-item active">

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
                    Criar nova aula
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
                    Novo conteúdo
                </span>

                <h2>
                    Monte sua aula
                </h2>

                <p>
                    Preencha as informações abaixo para criar
                    uma nova aula dentro da plataforma.
                </p>

            </div>

        </section>


        <!-- MENSAGENS -->

        <?php if ($status === "empty"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Preencha pelo menos o título e a descrição da aula.
                </div>

            </div>

        <?php endif; ?>

        <?php if ($status === "error"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Não foi possível criar a aula.
                </div>

            </div>

        <?php endif; ?>


        <!-- FORMULÁRIO -->

        <section class="dashboard-section">

            <div class="lesson-form-card">

                <form
                    action="../../routes/aulas.php"
                    method="POST"
                    enctype="multipart/form-data"
                    class="lesson-form">

                    <input
                        type="hidden"
                        name="action"
                        value="criar">

                    <div class="lesson-form-grid">

                        <div class="input-group lesson-field-full">

                            <label for="titulo">
                                Título da aula
                            </label>

                            <input
                                type="text"
                                id="titulo"
                                name="titulo"
                                placeholder="Ex.: Introdução ao Tamborim"
                                maxlength="150"
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
                                placeholder="Explique de forma resumida o que será ensinado nesta aula."
                                required></textarea>

                        </div>


                        <div class="input-group lesson-field-full">

                            <label for="conteudo">
                                Conteúdo da aula
                            </label>

                            <textarea
                                id="conteudo"
                                name="conteudo"
                                rows="10"
                                placeholder="Digite aqui o conteúdo principal da aula, orientações, explicações, exercícios ou observações."></textarea>

                        </div>


                        <div class="input-group">

                            <label for="video_url">
                                Link do vídeo
                            </label>

                            <input
                                type="url"
                                id="video_url"
                                name="video_url"
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
                                placeholder="https://...">

                        </div>



                        <!-- ARQUIVO COMPLEMENTAR -->
                        <div class="input-group lesson-field-full">

                            <label for="arquivo_aula">
                                Arquivo complementar
                            </label>

                            <input
                                type="file"
                                id="arquivo_aula"
                                name="arquivo_aula"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.webp,.txt,.zip">

                            <small class="form-help">
                                Opcional. PDF, Word, PowerPoint,
                                imagem, TXT ou ZIP. Máximo: 20 MB.
                            </small>

                        </div>

                        <div class="input-group">

                            <label for="status">
                                Publicação
                            </label>

                            <select
                                id="status"
                                name="status">

                                <option value="rascunho">
                                    Salvar como rascunho
                                </option>

                                <option value="publicada">
                                    Publicar agora
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

                            Criar aula

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