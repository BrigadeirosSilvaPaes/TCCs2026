<?php

session_start();

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../app/Models/Material.php";
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
    header("Location: materiais.php?status=invalid");
    exit;
}

$materialModel = new Material($pdo);
$aulaModel = new Aula($pdo);

$material = $materialModel->buscarPorId($id);

if (
    !$material ||
    (int) $material["professor_id"] !== (int) $usuario["id"]
) {
    header("Location: materiais.php?status=invalid");
    exit;
}

$aulasProfessor = $aulaModel->listarPorProfessor(
    $usuario["id"]
);

$status = $_GET["status"] ?? "";

$fonteAtual = !empty($material["caminho"])
    ? "arquivo"
    : "link";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Editar Material | Tamborim Para Todos
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
                class="dashboard-nav-item">

                <span>＋</span>
                Criar aula

            </a>

            <a
                href="materiais.php"
                class="dashboard-nav-item active">

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

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Professor
                </span>

                <h1>
                    Editar material
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
                    Biblioteca
                </span>

                <h2>
                    <?= htmlspecialchars($material["titulo"]) ?>
                </h2>

                <p>
                    Atualize as informações, o arquivo,
                    o link ou a aula vinculada a este material.
                </p>

            </div>

        </section>


        <!-- MENSAGENS -->

        <?php if ($status === "empty"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Preencha os campos obrigatórios.
                </div>

            </div>

        <?php endif; ?>

        <?php if ($status === "url"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Informe um link válido.
                </div>

            </div>

        <?php endif; ?>

        <?php if ($status === "file"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Não foi possível processar o arquivo.
                </div>

            </div>

        <?php endif; ?>

        <?php if ($status === "size"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    O arquivo ultrapassa o limite de 20 MB.
                </div>

            </div>

        <?php endif; ?>

        <?php if (
            $status === "extension" ||
            $status === "type"
        ): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Esse tipo de arquivo não é permitido.
                </div>

            </div>

        <?php endif; ?>

        <?php if (
            $status === "invalid" ||
            $status === "error" ||
            $status === "upload" ||
            $status === "folder"
        ): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    Não foi possível atualizar o material.
                </div>

            </div>

        <?php endif; ?>


        <!-- FORMULÁRIO -->

        <section class="dashboard-section">

            <div class="lesson-form-card">

                <form
                    action="../../routes/materiais.php"
                    method="POST"
                    enctype="multipart/form-data"
                    class="lesson-form">

                    <input
                        type="hidden"
                        name="action"
                        value="atualizar">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int) $material["id"] ?>">


                    <div class="lesson-form-grid">


                        <!-- TÍTULO -->

                        <div class="input-group lesson-field-full">

                            <label for="titulo">
                                Título
                            </label>

                            <input
                                type="text"
                                id="titulo"
                                name="titulo"
                                maxlength="150"
                                value="<?= htmlspecialchars(
                                    $material["titulo"]
                                ) ?>"
                                required>

                        </div>


                        <!-- DESCRIÇÃO -->

                        <div class="input-group lesson-field-full">

                            <label for="descricao">
                                Descrição
                            </label>

                            <textarea
                                id="descricao"
                                name="descricao"
                                rows="4"><?= htmlspecialchars(
                                    $material["descricao"] ?? ""
                                ) ?></textarea>

                        </div>


                        <!-- TIPO -->

                        <div class="input-group">

                            <label for="tipo">
                                Tipo de material
                            </label>

                            <select
                                id="tipo"
                                name="tipo"
                                required>

                                <option
                                    value="video"
                                    <?= $material["tipo"] === "video"
                                        ? "selected"
                                        : "" ?>>

                                    Vídeo / Videoaula

                                </option>

                                <option
                                    value="pdf"
                                    <?= $material["tipo"] === "pdf"
                                        ? "selected"
                                        : "" ?>>

                                    PDF

                                </option>

                                <option
                                    value="documento"
                                    <?= $material["tipo"] === "documento"
                                        ? "selected"
                                        : "" ?>>

                                    Documento

                                </option>

                                <option
                                    value="apresentacao"
                                    <?= $material["tipo"] === "apresentacao"
                                        ? "selected"
                                        : "" ?>>

                                    Apresentação

                                </option>

                                <option
                                    value="imagem"
                                    <?= $material["tipo"] === "imagem"
                                        ? "selected"
                                        : "" ?>>

                                    Imagem

                                </option>

                                <option
                                    value="arquivo"
                                    <?= $material["tipo"] === "arquivo"
                                        ? "selected"
                                        : "" ?>>

                                    Outro arquivo

                                </option>

                                <option
                                    value="link"
                                    <?= $material["tipo"] === "link"
                                        ? "selected"
                                        : "" ?>>

                                    Link externo

                                </option>

                            </select>

                        </div>


                        <!-- AULA -->

                        <div class="input-group">

                            <label for="aula_id">
                                Vincular a uma aula
                            </label>

                            <select
                                id="aula_id"
                                name="aula_id">

                                <option value="">
                                    Nenhuma aula
                                </option>

                                <?php foreach (
                                    $aulasProfessor
                                    as $aula
                                ): ?>

                                    <option
                                        value="<?= (int) $aula["id"] ?>"
                                        <?= (int) ($material["aula_id"] ?? 0)
                                            === (int) $aula["id"]
                                            ? "selected"
                                            : "" ?>>

                                        Aula
                                        <?= (int) $aula["ordem"] ?>
                                        —
                                        <?= htmlspecialchars(
                                            $aula["titulo"]
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <!-- FONTE -->

                        <div class="input-group lesson-field-full">

                            <label>
                                Fonte do material
                            </label>

                            <div class="material-source-options">

                                <label class="material-source-card">

                                    <input
                                        type="radio"
                                        name="fonte"
                                        value="link"
                                        <?= $fonteAtual === "link"
                                            ? "checked"
                                            : "" ?>>

                                    <span class="material-source-icon">
                                        🔗
                                    </span>

                                    <span>

                                        <strong>
                                            Link externo
                                        </strong>

                                        <small>
                                            YouTube, Drive, site etc.
                                        </small>

                                    </span>

                                </label>


                                <label class="material-source-card">

                                    <input
                                        type="radio"
                                        name="fonte"
                                        value="arquivo"
                                        <?= $fonteAtual === "arquivo"
                                            ? "checked"
                                            : "" ?>>

                                    <span class="material-source-icon">
                                        📤
                                    </span>

                                    <span>

                                        <strong>
                                            Arquivo
                                        </strong>

                                        <small>
                                            PDF, Word, PowerPoint...
                                        </small>

                                    </span>

                                </label>

                            </div>

                        </div>


                        <!-- URL -->

                        <div
                            class="input-group lesson-field-full"
                            id="materialUrlGroup">

                            <label for="url">
                                Link do material
                            </label>

                            <input
                                type="url"
                                id="url"
                                name="url"
                                value="<?= htmlspecialchars(
                                    $material["url"] ?? ""
                                ) ?>"
                                placeholder="https://...">

                        </div>


                        <!-- ARQUIVO -->

                        <div
                            class="input-group lesson-field-full"
                            id="materialFileGroup">

                            <label for="arquivo">
                                Substituir arquivo
                            </label>

                            <?php if (
                                !empty($material["nome_original"])
                            ): ?>

                                <div class="current-file">

                                    <span>
                                        📎 Arquivo atual
                                    </span>

                                    <strong>
                                        <?= htmlspecialchars(
                                            $material["nome_original"]
                                        ) ?>
                                    </strong>

                                </div>

                            <?php endif; ?>

                            <input
                                type="file"
                                id="arquivo"
                                name="arquivo"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.webp,.txt,.zip">

                            <small class="form-help">
                                Deixe vazio para manter o arquivo atual.
                                Tamanho máximo: 20 MB.
                            </small>

                        </div>

                    </div>


                    <!-- AÇÕES -->

                    <div class="lesson-form-actions">

                        <a
                            href="materiais.php"
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


        <!-- MATERIAL ATUAL -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Visualização
                    </span>

                    <h2>
                        Material atual
                    </h2>

                </div>

            </div>

            <div class="lesson-external-resource">

                <div class="lesson-resource-icon">

                    <?php

                    switch ($material["tipo"]) {

                        case "video":
                            echo "🎥";
                            break;

                        case "pdf":
                            echo "📄";
                            break;

                        case "documento":
                            echo "📝";
                            break;

                        case "imagem":
                            echo "🖼️";
                            break;

                        case "apresentacao":
                            echo "📊";
                            break;

                        case "link":
                            echo "🔗";
                            break;

                        default:
                            echo "📎";
                    }

                    ?>

                </div>

                <div>

                    <strong>
                        <?= htmlspecialchars(
                            $material["titulo"]
                        ) ?>
                    </strong>

                    <p>

                        <?php if (
                            !empty($material["nome_original"])
                        ): ?>

                            <?= htmlspecialchars(
                                $material["nome_original"]
                            ) ?>

                        <?php elseif (
                            !empty($material["url"])
                        ): ?>

                            Material por link externo

                        <?php else: ?>

                            Material sem arquivo disponível

                        <?php endif; ?>

                    </p>

                </div>


                <?php if (!empty($material["url"])): ?>

                    <a
                        href="<?= htmlspecialchars(
                            $material["url"]
                        ) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn-secondary">

                        Abrir material

                    </a>

                <?php elseif (
                    !empty($material["caminho"])
                ): ?>

                    <a
                        href="<?= htmlspecialchars(
                            "../" . $material["caminho"]
                        ) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn-secondary">

                        Abrir material

                    </a>

                <?php endif; ?>

            </div>

        </section>

    </main>

</div>


<script src="../assets/js/theme.js"></script>

<script>

const fonteInputs = document.querySelectorAll(
    'input[name="fonte"]'
);

const urlGroup = document.getElementById(
    "materialUrlGroup"
);

const fileGroup = document.getElementById(
    "materialFileGroup"
);

const urlInput = document.getElementById(
    "url"
);

function atualizarFonte() {

    const selecionado =
        document.querySelector(
            'input[name="fonte"]:checked'
        );

    if (!selecionado) {
        return;
    }

    if (selecionado.value === "arquivo") {

        urlGroup.style.display = "none";
        fileGroup.style.display = "flex";

        urlInput.required = false;

    } else {

        urlGroup.style.display = "flex";
        fileGroup.style.display = "none";

        urlInput.required = true;
    }
}


fonteInputs.forEach((input) => {

    input.addEventListener(
        "change",
        atualizarFonte
    );

});


atualizarFonte();

</script>

</body>

</html>