<?php

session_start();

require_once __DIR__ . "/protecao.php";

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../app/Models/Aula.php";
require_once __DIR__ . "/../../app/Models/Progresso.php";

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "aluno"
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
    header("Location: aulas.php");
    exit;
}

$aulaModel = new Aula($pdo);

$aula = $aulaModel->buscarPorId($id);

/*
|--------------------------------------------------------------------------
| VALIDAÇÃO
|--------------------------------------------------------------------------
*/

if (
    !$aula ||
    $aula["status"] !== "publicada"
) {
    header("Location: aulas.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| PROGRESSO
|--------------------------------------------------------------------------
*/

$progressoModel = new Progresso($pdo);

$aulaConcluida = $progressoModel->aulaConcluida(
    $usuario["id"],
    $aula["id"]
);

$status = $_GET["status"] ?? "";


/*
|--------------------------------------------------------------------------
| VÍDEO
|--------------------------------------------------------------------------
*/

$videoEmbed = null;

if (!empty($aula["video_url"])) {

    $videoUrl = $aula["video_url"];

    if (
        str_contains(
            $videoUrl,
            "youtube.com/watch?v="
        )
    ) {

        $partes = parse_url($videoUrl);

        if (!empty($partes["query"])) {

            parse_str(
                $partes["query"],
                $query
            );

            if (!empty($query["v"])) {

                $videoEmbed =
                    "https://www.youtube.com/embed/"
                    . urlencode($query["v"]);
            }
        }

    } elseif (
        str_contains(
            $videoUrl,
            "youtu.be/"
        )
    ) {

        $partes = parse_url($videoUrl);

        $videoId = trim(
            $partes["path"] ?? "",
            "/"
        );

        if ($videoId !== "") {

            $videoEmbed =
                "https://www.youtube.com/embed/"
                . urlencode($videoId);
        }

    } elseif (
        str_contains(
            $videoUrl,
            "youtube.com/embed/"
        )
    ) {

        $videoEmbed = $videoUrl;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($aula["titulo"]) ?>
        | Tamborim Para Todos
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
                    Área do Aluno
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
                href="aulas.php"
                class="dashboard-nav-item active">

                <span>📚</span>
                Aulas

            </a>

            <a
                href="#"
                class="dashboard-nav-item">

                <span>📈</span>
                Progresso

            </a>

            <a
                href="#"
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

        <!-- TOPO -->

        <div class="dashboard-topbar">

            <div>

                <a
                    href="aulas.php"
                    class="auth-back-link lesson-back-link">

                    ← Voltar para aulas

                </a>

                <span class="dashboard-eyebrow">
                    Aula <?= (int) $aula["ordem"] ?>
                </span>

                <h1>
                    <?= htmlspecialchars($aula["titulo"]) ?>
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
                        Aluno
                    </span>

                </div>

            </div>

        </div>


        <!-- CABEÇALHO -->

        <section class="lesson-view-header">

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

            <div class="lesson-teacher">

                <span>
                    Professor
                </span>

                <strong>
                    <?= htmlspecialchars(
                        $aula["professor_nome"]
                    ) ?>
                </strong>

            </div>

        </section>


        <!-- VÍDEO -->

        <?php if (!empty($aula["video_url"])): ?>

            <section class="dashboard-section">

                <div class="dashboard-section-header">

                    <div>

                        <span class="section-eyebrow">
                            Videoaula
                        </span>

                        <h2>
                            Assista ao conteúdo
                        </h2>

                    </div>

                </div>

                <?php if ($videoEmbed): ?>

                    <div class="lesson-video">

                        <iframe
                            src="<?= htmlspecialchars(
                                $videoEmbed
                            ) ?>"
                            title="<?= htmlspecialchars(
                                $aula["titulo"]
                            ) ?>"
                            allow="
                                accelerometer;
                                autoplay;
                                clipboard-write;
                                encrypted-media;
                                gyroscope;
                                picture-in-picture;
                                web-share
                            "
                            allowfullscreen>
                        </iframe>

                    </div>

                <?php else: ?>

                    <div class="lesson-external-resource">

                        <div class="lesson-resource-icon">
                            🎥
                        </div>

                        <div>

                            <strong>
                                Vídeo da aula
                            </strong>

                            <p>
                                Este vídeo está disponível em
                                um serviço externo.
                            </p>

                        </div>

                        <a
                            href="<?= htmlspecialchars(
                                $aula["video_url"]
                            ) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn-primary">

                            Abrir vídeo

                        </a>

                    </div>

                <?php endif; ?>

            </section>

        <?php endif; ?>


        <!-- CONTEÚDO -->

        <?php if (!empty($aula["conteudo"])): ?>

            <section class="dashboard-section">

                <div class="dashboard-section-header">

                    <div>

                        <span class="section-eyebrow">
                            Material de estudo
                        </span>

                        <h2>
                            Conteúdo da aula
                        </h2>

                    </div>

                </div>

                <article class="lesson-content-card">

                    <?= nl2br(
                        htmlspecialchars(
                            $aula["conteudo"]
                        )
                    ) ?>

                </article>

            </section>

        <?php endif; ?>


        <!-- MATERIAL COMPLEMENTAR -->

        <?php if (!empty($aula["material_url"])): ?>

            <section class="dashboard-section">

                <div class="dashboard-section-header">

                    <div>

                        <span class="section-eyebrow">
                            Material complementar
                        </span>

                        <h2>
                            Continue estudando
                        </h2>

                    </div>

                </div>

                <div class="lesson-external-resource">

                    <div class="lesson-resource-icon">
                        📎
                    </div>

                    <div>

                        <strong>
                            Material da aula
                        </strong>

                        <p>
                            Acesse o material complementar
                            disponibilizado pelo professor.
                        </p>

                    </div>

                    <a
                        href="<?= htmlspecialchars(
                            $aula["material_url"]
                        ) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn-secondary">

                        Abrir material

                    </a>

                </div>

            </section>

        <?php endif; ?>


        <!-- PROGRESSO -->

        <section class="dashboard-section">

            <?php if ($status === "completed"): ?>

                <div class="alert-success">
                    Aula concluída com sucesso!
                </div>

            <?php endif; ?>


            <?php if ($status === "reopened"): ?>

                <div class="alert-success">
                    Aula marcada como não concluída.
                </div>

            <?php endif; ?>


            <div class="lesson-progress-card">

                <div>

                    <span class="section-eyebrow">
                        Seu progresso
                    </span>

                    <?php if ($aulaConcluida): ?>

                        <h2>
                            Aula concluída ✓
                        </h2>

                        <p>
                            Esta aula já faz parte do seu progresso.
                        </p>

                    <?php else: ?>

                        <h2>
                            Concluiu esta aula?
                        </h2>

                        <p>
                            Marque como concluída para atualizar
                            seu progresso na plataforma.
                        </p>

                    <?php endif; ?>

                </div>


                <?php if ($aulaConcluida): ?>

                    <form
                        action="../../routes/progresso.php"
                        method="POST">

                        <input
                            type="hidden"
                            name="action"
                            value="reabrir">

                        <input
                            type="hidden"
                            name="aula_id"
                            value="<?= (int) $aula["id"] ?>">

                        <button
                            type="submit"
                            class="btn-secondary">

                            Marcar como não concluída

                        </button>

                    </form>

                <?php else: ?>

                    <form
                        action="../../routes/progresso.php"
                        method="POST">

                        <input
                            type="hidden"
                            name="action"
                            value="concluir">

                        <input
                            type="hidden"
                            name="aula_id"
                            value="<?= (int) $aula["id"] ?>">

                        <button
                            type="submit"
                            class="btn-primary">

                            ✓ Concluir aula

                        </button>

                    </form>

                <?php endif; ?>

            </div>

        </section>


        <!-- FINAL -->

        <section class="dashboard-section">

            <div class="lesson-finish-card">

                <div class="lesson-finish-icon">
                    🥁
                </div>

                <div>

                    <span class="section-eyebrow">
                        Continue praticando
                    </span>

                    <h2>
                        Final da aula
                    </h2>

                    <p>
                        Revise o conteúdo, pratique os exercícios
                        e avance para outras aulas quando estiver preparado.
                    </p>

                </div>

                <a
                    href="aulas.php"
                    class="btn-primary">

                    Ver outras aulas

                </a>

            </div>

        </section>

    </main>

</div>

<script src="../assets/js/theme.js"></script>

</body>

</html>