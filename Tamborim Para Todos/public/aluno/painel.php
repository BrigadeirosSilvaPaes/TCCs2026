<?php

session_start();

require_once __DIR__ . "/protecao.php";

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../app/Models/Aula.php";
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
| MODELS
|--------------------------------------------------------------------------
*/

$aulaModel = new Aula($pdo);

$progressoModel = new Progresso($pdo);


/*
|--------------------------------------------------------------------------
| AULAS
|--------------------------------------------------------------------------
*/

$aulasRecentes = $aulaModel->listarRecentesPublicadas(3);

$totalPublicadas = $aulaModel->contarPublicadas();


/*
|--------------------------------------------------------------------------
| PROGRESSO DO ALUNO
|--------------------------------------------------------------------------
*/

$totalConcluidas = $progressoModel->contarConcluidas(
    $usuario["id"]
);

$percentualProgresso = $progressoModel->calcularPercentual(
    $usuario["id"]
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
        Painel do Aluno | Tamborim Para Todos
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


        <!-- MENU -->

        <nav class="dashboard-nav">

            <a
                href="painel.php"
                class="dashboard-nav-item active">

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
                href="#progresso"
                class="dashboard-nav-item">

                <span>📈</span>

                Progresso

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

        </div>

    </aside>



    <!-- =====================================================
         CONTEÚDO PRINCIPAL
    ====================================================== -->

    <main class="dashboard-main">


        <!-- =================================================
             TOPBAR
        ================================================== -->

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Aluno
                </span>

                <h1>

                    Olá,
                    <?= htmlspecialchars($usuario["nome"]) ?>

                </h1>

            </div>


            <!-- USUÁRIO -->

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
             BOAS-VINDAS
        ================================================== -->

        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Aprendizado
                </span>

                <h2>
                    Continue evoluindo no tamborim
                </h2>

                <p>

                    Explore as aulas publicadas pelos professores
                    e avance no seu aprendizado no seu próprio ritmo.

                </p>

            </div>

        </section>



        <!-- =================================================
             ESTATÍSTICAS
        ================================================== -->

        <section class="dashboard-stats">


            <!-- AULAS DISPONÍVEIS -->

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">

                    📚

                </div>

                <div>

                    <span>
                        Aulas disponíveis
                    </span>

                    <strong>

                        <?= $totalPublicadas ?>

                    </strong>

                </div>

            </article>



            <!-- PROGRESSO -->

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">

                    📈

                </div>

                <div>

                    <span>
                        Progresso
                    </span>

                    <strong>

                        <?= $percentualProgresso ?>%

                    </strong>

                    <small class="dashboard-stat-detail">

                        <?= $totalConcluidas ?>

                        de

                        <?= $totalPublicadas ?>

                        aulas concluídas

                    </small>

                </div>

            </article>



            <!-- PRÁTICA -->

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">

                    🥁

                </div>

                <div>

                    <span>
                        Prática
                    </span>

                    <strong>
                        Continue treinando
                    </strong>

                </div>

            </article>

        </section>



        <!-- =================================================
             PROGRESSO VISUAL
        ================================================== -->

        <section
            class="student-progress-overview"
            id="progresso">

            <div class="student-progress-header">

                <div>

                    <span class="section-eyebrow">

                        Seu progresso

                    </span>

                    <h2>

                        <?= $percentualProgresso ?>% concluído

                    </h2>

                </div>


                <span class="student-progress-count">

                    <?= $totalConcluidas ?>

                    /

                    <?= $totalPublicadas ?>

                    aulas

                </span>

            </div>


            <!-- BARRA -->

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


            <!-- MENSAGEM -->

            <?php if ($totalPublicadas === 0): ?>

                <p>

                    Ainda não existem aulas publicadas.

                </p>


            <?php elseif ($percentualProgresso === 100): ?>

                <p>

                    🎉 Parabéns! Você concluiu todas
                    as aulas disponíveis.

                </p>


            <?php elseif ($totalConcluidas === 0): ?>

                <p>

                    Comece sua primeira aula para
                    registrar seu progresso.

                </p>


            <?php else: ?>

                <p>

                    Continue avançando. Cada aula
                    concluída aumenta seu progresso.

                </p>

            <?php endif; ?>

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
                    href="aulas.php"
                    class="btn-secondary">

                    Ver todas

                </a>

            </div>



            <!-- NENHUMA AULA -->

            <?php if (empty($aulasRecentes)): ?>

                <div class="empty-state">

                    <div class="empty-state-icon">

                        📚

                    </div>

                    <h3>

                        Nenhuma aula publicada ainda

                    </h3>

                    <p>

                        Quando os professores publicarem conteúdos,
                        eles aparecerão aqui.

                    </p>

                </div>



            <!-- EXISTEM AULAS -->

            <?php else: ?>

                <div class="student-lessons-grid">


                    <?php foreach ($aulasRecentes as $aula): ?>

                        <a
                            href="aula.php?id=<?= (int) $aula["id"] ?>"
                            class="student-lesson-card">


                            <!-- TOPO DO CARD -->

                            <div class="student-lesson-top">

                                <span class="student-lesson-number">

                                    Aula
                                    <?= (int) $aula["ordem"] ?>

                                </span>

                                <span class="status-badge active">

                                    Disponível

                                </span>

                            </div>



                            <!-- TÍTULO -->

                            <h3>

                                <?= htmlspecialchars(
                                    $aula["titulo"]
                                ) ?>

                            </h3>



                            <!-- DESCRIÇÃO -->

                            <p>

                                <?= htmlspecialchars(
                                    $aula["descricao"]
                                ) ?>

                            </p>



                            <!-- RODAPÉ -->

                            <div class="student-lesson-footer">

                                <span>

                                    👨‍🏫

                                    <?= htmlspecialchars(
                                        $aula["professor_nome"]
                                    ) ?>

                                </span>

                                <strong>

                                    Acessar →

                                </strong>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>

    </main>

</div>


<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script src="../assets/js/theme.js"></script>

</body>

</html>