<?php

session_start();

require_once __DIR__ . "/../../config/db.php";

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "admin"
) {
    header("Location: ../login.php");
    exit;
}

$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id) {
    header("Location: aulas.php?status=invalid");
    exit;
}

$sql = "
    SELECT
        a.*,
        u.nome AS autor_nome
    FROM aulas a
    LEFT JOIN usuarios u
        ON u.id = a.professor_id
    WHERE a.id = :id
    LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":id" => $id
]);

$aula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$aula) {
    header("Location: aulas.php?status=invalid");
    exit;
}

$sqlMateriais = "
    SELECT
        id,
        titulo,
        descricao,
        tipo,
        caminho,
        url,
        nome_original
    FROM materiais
    WHERE aula_id = :aula_id
    ORDER BY id DESC
";

$stmtMateriais =
    $pdo->prepare(
        $sqlMateriais
    );

$stmtMateriais->execute([
    ":aula_id" => $id
]);

$materiais =
    $stmtMateriais->fetchAll(
        PDO::FETCH_ASSOC
    );

$usuario =
    $_SESSION["usuario"];

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Visualizar Aula | Tamborim Para Todos
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

<div class="dashboard-layout">

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


    <main class="dashboard-main">

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Administração
                </span>

                <h1>
                    Visualizar aula
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


        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Conteúdo da aula
                </span>

                <h2>
                    <?= htmlspecialchars(
                        $aula["titulo"]
                    ) ?>
                </h2>

                <p>
                    <?= htmlspecialchars(
                        $aula["descricao"]
                    ) ?>
                </p>

            </div>

        </section>


        <section class="dashboard-section">

            <div class="lesson-form-card">

                <div class="input-group">

                    <label>
                        Status
                    </label>

                    <p>
                        <?= $aula["status"] === "publicada"
                            ? "Publicada"
                            : "Rascunho" ?>
                    </p>

                </div>

                <div class="input-group">

                    <label>
                        Autor
                    </label>

                    <p>
                        <?= htmlspecialchars(
                            $aula["autor_nome"]
                            ?? "Não informado"
                        ) ?>
                    </p>

                </div>


                <div class="input-group lesson-field-full">

                    <label>
                        Conteúdo
                    </label>

                    <div>

                        <?php if (
                            !empty($aula["conteudo"])
                        ): ?>

                            <?= nl2br(
                                htmlspecialchars(
                                    $aula["conteudo"]
                                )
                            ) ?>

                        <?php else: ?>

                            <p>
                                Nenhum conteúdo textual cadastrado.
                            </p>

                        <?php endif; ?>

                    </div>

                </div>


                <?php if (
                    !empty($aula["video_url"])
                ): ?>

                    <div class="input-group lesson-field-full">

                        <label>
                            Vídeo
                        </label>

                        <a
                            href="<?= htmlspecialchars(
                                $aula["video_url"]
                            ) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="lesson-action-button">

                            Abrir vídeo

                        </a>

                    </div>

                <?php endif; ?>


                <?php if (
                    !empty($aula["material_url"])
                ): ?>

                    <div class="input-group lesson-field-full">

                        <label>
                            Material complementar
                        </label>

                        <a
                            href="<?= htmlspecialchars(
                                $aula["material_url"]
                            ) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="lesson-action-button">

                            Abrir material

                        </a>

                    </div>

                <?php endif; ?>


                <?php if (!empty($materiais)): ?>

                    <div class="input-group lesson-field-full">

                        <label>
                            Arquivos vinculados
                        </label>

                        <?php foreach (
                            $materiais
                            as $material
                        ): ?>

                            <div class="lesson-meta">

                                <span>

                                    📎

                                    <?= htmlspecialchars(
                                        $material["nome_original"]
                                        ?: $material["titulo"]
                                    ) ?>

                                </span>

                                <?php if (
                                    !empty($material["caminho"])
                                ): ?>

                                    <a
                                        href="../<?= htmlspecialchars(
                                            $material["caminho"]
                                        ) ?>"
                                        target="_blank"
                                        class="lesson-action-button">

                                        Abrir arquivo

                                    </a>

                                <?php elseif (
                                    !empty($material["url"])
                                ): ?>

                                    <a
                                        href="<?= htmlspecialchars(
                                            $material["url"]
                                        ) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="lesson-action-button">

                                        Abrir link

                                    </a>

                                <?php endif; ?>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>


                <div class="lesson-form-actions">

                    <a
                        href="aulas.php"
                        class="btn-secondary">

                        Voltar

                    </a>

                </div>

            </div>

        </section>

    </main>

</div>

</body>

</html>