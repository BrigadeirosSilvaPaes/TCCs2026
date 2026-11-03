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

$materialModel = new Material($pdo);
$aulaModel = new Aula($pdo);

$filtro = $_GET["tipo"] ?? "";
$busca = trim($_GET["busca"] ?? "");

if ($busca !== "") {

    $materiais = $materialModel->pesquisarPorProfessor(
        $usuario["id"],
        $busca
    );

} else {

    $materiais = $materialModel->listarPorProfessor(
        $usuario["id"]
    );
}

if ($filtro !== "") {

    $materiais = array_filter(
        $materiais,
        function ($material) use ($filtro) {
            return $material["tipo"] === $filtro;
        }
    );
}

$aulasProfessor = $aulaModel->listarPorProfessor(
    $usuario["id"]
);

$totalMateriais = $materialModel->contarPorProfessor(
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
        Materiais | Tamborim Para Todos
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
                    Materiais
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
                    Conteúdos complementares
                </span>

                <h2>
                    Organize seus materiais
                </h2>

                <p>
                    Envie PDFs, documentos, apresentações, imagens
                    ou adicione links de vídeos e conteúdos externos.
                </p>

            </div>

        </section>


        <!-- MENSAGENS -->

        <?php if ($status === "created"): ?>

            <div class="dashboard-message-area">

                <div class="alert-success">
                    Material adicionado com sucesso.
                </div>

            </div>

        <?php endif; ?>

        <?php if ($status === "updated"): ?>

            <div class="dashboard-message-area">

                <div class="alert-success">
                    Material atualizado com sucesso.
                </div>

            </div>

        <?php endif; ?>

        <?php if ($status === "deleted"): ?>

            <div class="dashboard-message-area">

                <div class="alert-success">
                    Material excluído com sucesso.
                </div>

            </div>

        <?php endif; ?>

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
                    Selecione um arquivo válido.
                </div>

            </div>

        <?php endif; ?>

        <?php if ($status === "size"): ?>

            <div class="dashboard-message-area">

                <div class="alert-error">
                    O arquivo ultrapassa o limite permitido de 20 MB.
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
                    Não foi possível realizar a operação.
                </div>

            </div>

        <?php endif; ?>


        <!-- RESUMO -->

        <section class="dashboard-stats">

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    📁
                </div>

                <div>

                    <span>
                        Total de materiais
                    </span>

                    <strong>
                        <?= $totalMateriais ?>
                    </strong>

                </div>

            </article>

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    📚
                </div>

                <div>

                    <span>
                        Minhas aulas
                    </span>

                    <strong>
                        <?= count($aulasProfessor) ?>
                    </strong>

                </div>

            </article>

        </section>


        <!-- ADICIONAR MATERIAL -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Novo material
                    </span>

                    <h2>
                        Adicionar conteúdo complementar
                    </h2>

                </div>

            </div>


            <div class="lesson-form-card">

                <form
                    action="../../routes/materiais.php"
                    method="POST"
                    enctype="multipart/form-data"
                    class="lesson-form"
                    id="materialForm">

                    <input
                        type="hidden"
                        name="action"
                        value="criar">


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
                                placeholder="Ex.: Apostila de ritmos"
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
                                rows="3"
                                placeholder="Explique brevemente o conteúdo deste material."></textarea>

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

                                <option value="">
                                    Selecione
                                </option>

                                <option value="video">
                                    Vídeo / Videoaula
                                </option>

                                <option value="pdf">
                                    PDF
                                </option>

                                <option value="documento">
                                    Documento
                                </option>

                                <option value="apresentacao">
                                    Apresentação
                                </option>

                                <option value="imagem">
                                    Imagem
                                </option>

                                <option value="arquivo">
                                    Outro arquivo
                                </option>

                                <option value="link">
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
                                        value="<?= (int) $aula["id"] ?>">

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
                                Como deseja adicionar?
                            </label>

                            <div class="material-source-options">

                                <label class="material-source-card">

                                    <input
                                        type="radio"
                                        name="fonte"
                                        value="link"
                                        checked>

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
                                        value="arquivo">

                                    <span class="material-source-icon">
                                        📤
                                    </span>

                                    <span>

                                        <strong>
                                            Enviar arquivo
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
                                placeholder="https://...">

                        </div>


                        <!-- ARQUIVO -->

                        <div
                            class="input-group lesson-field-full"
                            id="materialFileGroup"
                            style="display: none;">

                            <label for="arquivo">
                                Selecionar arquivo
                            </label>

                            <input
                                type="file"
                                id="arquivo"
                                name="arquivo"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.webp,.txt,.zip">

                            <small class="form-help">
                                Tamanho máximo: 20 MB.
                            </small>

                        </div>

                    </div>


                    <div class="lesson-form-actions">

                        <button
                            type="reset"
                            class="btn-secondary"
                            id="resetMaterial">

                            Limpar

                        </button>

                        <button
                            type="submit"
                            class="btn-enviar lesson-submit">

                            Adicionar material

                        </button>

                    </div>

                </form>

            </div>

        </section>


        <!-- BUSCA E FILTROS -->

        <section class="dashboard-section">

            <div class="material-toolbar">

                <form
                    action="materiais.php"
                    method="GET"
                    class="lesson-search">

                    <input
                        type="search"
                        name="busca"
                        value="<?= htmlspecialchars($busca) ?>"
                        placeholder="Pesquisar materiais...">

                    <button
                        type="submit"
                        class="btn-secondary">

                        Buscar

                    </button>

                </form>


                <div class="material-filters">

                    <a
                        href="materiais.php"
                        class="material-filter <?= $filtro === "" ? "active" : "" ?>">

                        Todos

                    </a>

                    <a
                        href="materiais.php?tipo=video"
                        class="material-filter <?= $filtro === "video" ? "active" : "" ?>">

                        Vídeos

                    </a>

                    <a
                        href="materiais.php?tipo=pdf"
                        class="material-filter <?= $filtro === "pdf" ? "active" : "" ?>">

                        PDFs

                    </a>

                    <a
                        href="materiais.php?tipo=documento"
                        class="material-filter <?= $filtro === "documento" ? "active" : "" ?>">

                        Documentos

                    </a>

                    <a
                        href="materiais.php?tipo=apresentacao"
                        class="material-filter <?= $filtro === "apresentacao" ? "active" : "" ?>">

                        Apresentações

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
                        Meus materiais
                    </h2>

                </div>

            </div>


            <?php if (empty($materiais)): ?>

                <div class="empty-state">

                    <div class="empty-state-icon">
                        📁
                    </div>

                    <h3>
                        Nenhum material encontrado
                    </h3>

                    <p>
                        Adicione seu primeiro material complementar.
                    </p>

                </div>

            <?php else: ?>

                <div class="materials-grid">

                    <?php foreach ($materiais as $material): ?>

                        <?php

                        $icone = "📎";

                        switch ($material["tipo"]) {

                            case "video":
                                $icone = "🎥";
                                break;

                            case "pdf":
                                $icone = "📄";
                                break;

                            case "documento":
                                $icone = "📝";
                                break;

                            case "imagem":
                                $icone = "🖼️";
                                break;

                            case "apresentacao":
                                $icone = "📊";
                                break;

                            case "link":
                                $icone = "🔗";
                                break;
                        }

                        $linkMaterial = "";

                        if (!empty($material["url"])) {

                            $linkMaterial =
                                $material["url"];

                        } elseif (
                            !empty($material["caminho"])
                        ) {

                            $linkMaterial =
                                "../"
                                . $material["caminho"];
                        }

                        ?>


                        <article class="material-card">

                            <div class="material-card-top">

                                <div class="material-icon">
                                    <?= $icone ?>
                                </div>

                                <span class="material-type-badge">

                                    <?= htmlspecialchars(
                                        ucfirst(
                                            $material["tipo"]
                                        )
                                    ) ?>

                                </span>

                            </div>


                            <h3>

                                <?= htmlspecialchars(
                                    $material["titulo"]
                                ) ?>

                            </h3>


                            <?php if (
                                !empty($material["descricao"])
                            ): ?>

                                <p>

                                    <?= htmlspecialchars(
                                        $material["descricao"]
                                    ) ?>

                                </p>

                            <?php endif; ?>


                            <div class="material-meta">

                                <?php if (
                                    !empty($material["aula_titulo"])
                                ): ?>

                                    <span>
                                        📚
                                        <?= htmlspecialchars(
                                            $material["aula_titulo"]
                                        ) ?>
                                    </span>

                                <?php else: ?>

                                    <span>
                                        Sem aula vinculada
                                    </span>

                                <?php endif; ?>


                                <?php if (
                                    !empty($material["nome_original"])
                                ): ?>

                                    <span>
                                        📎
                                        <?= htmlspecialchars(
                                            $material["nome_original"]
                                        ) ?>
                                    </span>

                                <?php endif; ?>

                            </div>


                            <div class="material-actions">

                                <?php if (
                                    !empty($linkMaterial)
                                ): ?>

                                    <a
                                        href="<?= htmlspecialchars(
                                            $linkMaterial
                                        ) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="lesson-action-button">

                                        Abrir

                                    </a>

                                <?php endif; ?>


                                <a
                                    href="editar-material.php?id=<?= (int) $material["id"] ?>"
                                    class="lesson-action-button">

                                    Editar

                                </a>


                                <form
                                    action="../../routes/materiais.php"
                                    method="POST"
                                    onsubmit="return confirm('Deseja realmente excluir este material?');">

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="excluir">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $material["id"] ?>">

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

const fileInput = document.getElementById(
    "arquivo"
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
        fileInput.required = true;

    } else {

        urlGroup.style.display = "flex";
        fileGroup.style.display = "none";

        urlInput.required = true;
        fileInput.required = false;
    }
}

fonteInputs.forEach((input) => {

    input.addEventListener(
        "change",
        atualizarFonte
    );

});

document
    .getElementById("resetMaterial")
    .addEventListener(
        "click",
        () => {

            setTimeout(
                atualizarFonte,
                0
            );

        }
    );

atualizarFonte();

</script>

</body>

</html>