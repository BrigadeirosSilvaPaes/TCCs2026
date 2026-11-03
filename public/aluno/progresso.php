<?php

session_start();

require_once __DIR__ . "/protecao.php";

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../app/Models/Progresso.php";

/*
|--------------------------------------------------------------------------
| PROTEÇÃO DA PÁGINA
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "aluno"
) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION["usuario"];


/*
|--------------------------------------------------------------------------
| MODEL
|--------------------------------------------------------------------------
*/

$progressoModel = new Progresso($pdo);


/*
|--------------------------------------------------------------------------
| DADOS DO PROGRESSO
|--------------------------------------------------------------------------
*/

$aulas = $progressoModel->listarProgressoDoAluno(
    $usuario["id"]
);

$totalAulas = count($aulas);

$totalConcluidas = $progressoModel->contarConcluidas(
    $usuario["id"]
);

$totalPendentes = $totalAulas - $totalConcluidas;

$percentualProgresso = $progressoModel->calcularPercentual(
    $usuario["id"]
);


/*
|--------------------------------------------------------------------------
| SEPARAÇÃO DAS AULAS
|--------------------------------------------------------------------------
*/

$aulasConcluidas = [];

$aulasPendentes = [];

foreach ($aulas as $aula) {

    if ($aula["concluida"]) {

        $aulasConcluidas[] = $aula;

    } else {

        $aulasPendentes[] = $aula;

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
        Progresso | Tamborim Para Todos
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
                class="dashboard-nav-item">

                <span>📚</span>
                Aulas

            </a>

            <a
                href="progresso.php"
                class="dashboard-nav-item active">

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


    <!-- =====================================================
         CONTEÚDO
    ====================================================== -->

    <main class="dashboard-main">


        <!-- TOPBAR -->

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Aluno
                </span>

                <h1>
                    Meu progresso
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


        <!-- =================================================
             APRESENTAÇÃO
        ================================================== -->

        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Evolução
                </span>

                <h2>
                    Acompanhe seu aprendizado
                </h2>

                <p>
                    Veja quantas aulas você já concluiu,
                    quais ainda faltam e continue avançando
                    no seu aprendizado de tamborim.
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
                        Concluídas
                    </span>

                    <strong>
                        <?= $totalConcluidas ?>
                    </strong>

                </div>

            </article>


            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    ⏳
                </div>

                <div>

                    <span>
                        Pendentes
                    </span>

                    <strong>
                        <?= $totalPendentes ?>
                    </strong>

                </div>

            </article>

        </section>


        <!-- =================================================
             PROGRESSO GERAL
        ================================================== -->

        <section class="student-progress-overview progress-page-overview">

            <div class="student-progress-header">

                <div>

                    <span class="section-eyebrow">
                        Progresso geral
                    </span>

                    <h2>
                        <?= $percentualProgresso ?>% concluído
                    </h2>

                </div>

                <span class="student-progress-count">

                    <?= $totalConcluidas ?>
                    /
                    <?= $totalAulas ?>
                    aulas

                </span>

            </div>


            <div
                class="student-progress-bar"
                role="progressbar"
                aria-valuenow="<?= $percentualProgresso ?>"
                aria-valuemin="0"
                aria-valuemax="100">

                <div
                    class="student-progress-fill"
                    style="width: <?= $percentualProgresso ?>%;">
                </div>

            </div>


            <?php if ($totalAulas === 0): ?>

                <p>
                    Ainda não existem aulas publicadas.
                </p>

            <?php elseif ($percentualProgresso === 100): ?>

                <p>
                    🎉 Parabéns! Você concluiu todas as aulas disponíveis.
                </p>

            <?php elseif ($totalConcluidas === 0): ?>

                <p>
                    Você ainda não concluiu nenhuma aula.
                </p>

            <?php else: ?>

                <p>
                    Continue estudando para aumentar seu progresso.
                </p>

            <?php endif; ?>

        </section>


        <!-- =================================================
             CONCLUÍDAS
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Finalizadas
                    </span>

                    <h2>
                        Aulas concluídas
                    </h2>

                </div>

                <span class="teacher-count-badge">
                    <?= $totalConcluidas ?>
                    concluída<?= $totalConcluidas === 1 ? "" : "s" ?>
                </span>

            </div>


            <?php if (empty($aulasConcluidas)): ?>

                <div class="empty-state">

                    <div class="empty-state-icon">
                        ✅
                    </div>

                    <h3>
                        Nenhuma aula concluída
                    </h3>

                    <p>
                        Conclua uma aula para começar a registrar sua evolução.
                    </p>

                </div>

            <?php else: ?>

                <div class="progress-lessons-list">

                    <?php foreach ($aulasConcluidas as $aula): ?>

                        <a
                            href="aula.php?id=<?= (int) $aula["id"] ?>"
                            class="progress-lesson-row completed">

                            <div class="progress-lesson-status">
                                ✓
                            </div>

                            <div class="progress-lesson-info">

                                <span>
                                    Aula <?= (int) $aula["ordem"] ?>
                                </span>

                                <strong>
                                    <?= htmlspecialchars(
                                        $aula["titulo"]
                                    ) ?>
                                </strong>

                                <p>
                                    <?= htmlspecialchars(
                                        $aula["descricao"]
                                    ) ?>
                                </p>

                            </div>

                            <div class="progress-lesson-action">

                                <span class="status-badge active">
                                    Concluída
                                </span>

                                <strong>
                                    Revisar →
                                </strong>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>


        <!-- =================================================
             PENDENTES
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Próximos passos
                    </span>

                    <h2>
                        Aulas pendentes
                    </h2>

                </div>

                <span class="teacher-count-badge">
                    <?= $totalPendentes ?>
                    pendente<?= $totalPendentes === 1 ? "" : "s" ?>
                </span>

            </div>


            <?php if (empty($aulasPendentes)): ?>

                <div class="empty-state">

                    <div class="empty-state-icon">
                        🎉
                    </div>

                    <h3>
                        Nenhuma aula pendente
                    </h3>

                    <p>
                        Você concluiu todas as aulas disponíveis.
                    </p>

                </div>

            <?php else: ?>

                <div class="progress-lessons-list">

                    <?php foreach ($aulasPendentes as $aula): ?>

                        <a
                            href="aula.php?id=<?= (int) $aula["id"] ?>"
                            class="progress-lesson-row">

                            <div class="progress-lesson-status">
                                <?= (int) $aula["ordem"] ?>
                            </div>

                            <div class="progress-lesson-info">

                                <span>
                                    Aula <?= (int) $aula["ordem"] ?>
                                </span>

                                <strong>
                                    <?= htmlspecialchars(
                                        $aula["titulo"]
                                    ) ?>
                                </strong>

                                <p>
                                    <?= htmlspecialchars(
                                        $aula["descricao"]
                                    ) ?>
                                </p>

                            </div>

                            <div class="progress-lesson-action">

                                <span class="status-badge pending">
                                    Pendente
                                </span>

                                <strong>
                                    Continuar →
                                </strong>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>

    </main>

</div>

<script src="../assets/js/theme.js"></script>

</body>

</html>