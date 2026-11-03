<?php

session_start();

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../app/Models/Usuario.php";
require_once __DIR__ . "/../../app/Models/Aula.php";
require_once __DIR__ . "/../../app/Models/Material.php";
require_once __DIR__ . "/../../app/Models/Progresso.php";

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "admin"
) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION["usuario"];

$usuarioModel = new Usuario($pdo);
$aulaModel = new Aula($pdo);
$materialModel = new Material($pdo);
$progressoModel = new Progresso($pdo);


/*
|--------------------------------------------------------------------------
| DADOS GERAIS
|--------------------------------------------------------------------------
*/

$totalAlunos = $usuarioModel->contarAlunos();

$totalAulas = $aulaModel->contarTodas();

$totalAulasPublicadas = $aulaModel->contarPublicadas();

$totalRascunhos =
    $totalAulas - $totalAulasPublicadas;


/*
|--------------------------------------------------------------------------
| PROFESSORES
|--------------------------------------------------------------------------
|
| Usamos as funções já existentes no Usuario.php.
|--------------------------------------------------------------------------
*/

$professoresAtivos =
    $usuarioModel->listarProfessoresAtivos();

$professoresPendentes =
    $usuarioModel->listarProfessoresPendentes();

$totalProfessoresAtivos =
    count($professoresAtivos);

$totalProfessoresPendentes =
    count($professoresPendentes);


/*
|--------------------------------------------------------------------------
| MATERIAIS
|--------------------------------------------------------------------------
*/

$materiais =
    $materialModel->listarTodos();

$totalMateriais =
    count($materiais);


/*
|--------------------------------------------------------------------------
| PROGRESSO DOS ALUNOS
|--------------------------------------------------------------------------
*/

$alunos =
    $usuarioModel->listarAlunos();

$somaPercentuais = 0;

$alunosComProgresso = 0;

$alunosConcluidos = 0;

$alunosNaoIniciaram = 0;

foreach ($alunos as $aluno) {

    $percentual =
        $progressoModel->calcularPercentual(
            $aluno["id"]
        );

    $somaPercentuais += $percentual;

    if ($percentual > 0) {
        $alunosComProgresso++;
    }

    if (
        $percentual === 100 &&
        $totalAulasPublicadas > 0
    ) {
        $alunosConcluidos++;
    }

    if ($percentual === 0) {
        $alunosNaoIniciaram++;
    }
}


/*
|--------------------------------------------------------------------------
| PROGRESSO MÉDIO
|--------------------------------------------------------------------------
*/

if ($totalAlunos > 0) {

    $progressoMedio =
        round(
            $somaPercentuais /
            $totalAlunos
        );

} else {

    $progressoMedio = 0;
}


/*
|--------------------------------------------------------------------------
| MATERIAIS POR TIPO
|--------------------------------------------------------------------------
*/

$materiaisPorTipo = [
    "video" => 0,
    "pdf" => 0,
    "documento" => 0,
    "imagem" => 0,
    "apresentacao" => 0,
    "arquivo" => 0,
    "link" => 0
];

foreach ($materiais as $material) {

    $tipo =
        $material["tipo"];

    if (
        isset(
            $materiaisPorTipo[$tipo]
        )
    ) {
        $materiaisPorTipo[$tipo]++;
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
        Relatórios | Tamborim Para Todos
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
                class="dashboard-nav-item">

                <span>📚</span>
                Aulas

            </a>

            <a
                href="relatorios.php"
                class="dashboard-nav-item active">

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


    <!-- =====================================================
         CONTEÚDO
    ====================================================== -->

    <main class="dashboard-main">


        <!-- TOPO -->

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Administração
                </span>

                <h1>
                    Relatórios
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


        <!-- APRESENTAÇÃO -->

        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Visão geral
                </span>

                <h2>
                    Acompanhe os dados da plataforma
                </h2>

                <p>
                    Consulte indicadores de usuários,
                    aulas, materiais e evolução dos alunos
                    dentro do Tamborim Para Todos.
                </p>

            </div>

        </section>


        <!-- =================================================
             INDICADORES PRINCIPAIS
        ================================================== -->

        <section class="dashboard-stats">

            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    🎓
                </div>

                <div>

                    <span>
                        Alunos
                    </span>

                    <strong>
                        <?= $totalAlunos ?>
                    </strong>

                </div>

            </article>


            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    👨‍🏫
                </div>

                <div>

                    <span>
                        Professores ativos
                    </span>

                    <strong>
                        <?= $totalProfessoresAtivos ?>
                    </strong>

                </div>

            </article>


            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    📚
                </div>

                <div>

                    <span>
                        Aulas
                    </span>

                    <strong>
                        <?= $totalAulas ?>
                    </strong>

                </div>

            </article>


            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">
                    📁
                </div>

                <div>

                    <span>
                        Materiais
                    </span>

                    <strong>
                        <?= $totalMateriais ?>
                    </strong>

                </div>

            </article>

        </section>


        <!-- =================================================
             PROGRESSO MÉDIO
        ================================================== -->

        <section class="dashboard-section">

            <div class="report-highlight">

                <div>

                    <span class="section-eyebrow">
                        Aprendizado
                    </span>

                    <h2>
                        Progresso médio dos alunos
                    </h2>

                    <p>
                        Média calculada considerando
                        todas as aulas atualmente publicadas.
                    </p>

                </div>


                <div class="report-percentage">

                    <?= $progressoMedio ?>%

                </div>

            </div>


            <div class="student-progress-bar">

                <div
                    class="student-progress-fill"
                    style="width: <?= $progressoMedio ?>%;">
                </div>

            </div>

        </section>


        <!-- =================================================
             ALUNOS
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Alunos
                    </span>

                    <h2>
                        Situação do aprendizado
                    </h2>

                </div>

            </div>


            <div class="report-grid">

                <article class="report-card">

                    <div class="report-card-icon">
                        ▶
                    </div>

                    <span>
                        Iniciaram os estudos
                    </span>

                    <strong>
                        <?= $alunosComProgresso ?>
                    </strong>

                    <small>
                        alunos com pelo menos
                        uma aula concluída
                    </small>

                </article>


                <article class="report-card">

                    <div class="report-card-icon">
                        🎉
                    </div>

                    <span>
                        Concluíram tudo
                    </span>

                    <strong>
                        <?= $alunosConcluidos ?>
                    </strong>

                    <small>
                        alunos com 100% de progresso
                    </small>

                </article>


                <article class="report-card">

                    <div class="report-card-icon">
                        ⏳
                    </div>

                    <span>
                        Não iniciaram
                    </span>

                    <strong>
                        <?= $alunosNaoIniciaram ?>
                    </strong>

                    <small>
                        alunos ainda com 0%
                    </small>

                </article>

            </div>

        </section>


        <!-- =================================================
             AULAS
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Conteúdos
                    </span>

                    <h2>
                        Situação das aulas
                    </h2>

                </div>

            </div>


            <div class="report-grid">

                <article class="report-card">

                    <div class="report-card-icon">
                        📚
                    </div>

                    <span>
                        Total
                    </span>

                    <strong>
                        <?= $totalAulas ?>
                    </strong>

                    <small>
                        aulas cadastradas
                    </small>

                </article>


                <article class="report-card">

                    <div class="report-card-icon">
                        ✅
                    </div>

                    <span>
                        Publicadas
                    </span>

                    <strong>
                        <?= $totalAulasPublicadas ?>
                    </strong>

                    <small>
                        disponíveis aos alunos
                    </small>

                </article>


                <article class="report-card">

                    <div class="report-card-icon">
                        📝
                    </div>

                    <span>
                        Rascunhos
                    </span>

                    <strong>
                        <?= $totalRascunhos ?>
                    </strong>

                    <small>
                        ainda não publicados
                    </small>

                </article>

            </div>

        </section>


        <!-- =================================================
             PROFESSORES
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Professores
                    </span>

                    <h2>
                        Situação das contas
                    </h2>

                </div>

            </div>


            <div class="report-grid">

                <article class="report-card">

                    <div class="report-card-icon">
                        ✅
                    </div>

                    <span>
                        Ativos
                    </span>

                    <strong>
                        <?= $totalProfessoresAtivos ?>
                    </strong>

                    <small>
                        autorizados a publicar
                    </small>

                </article>


                <article class="report-card">

                    <div class="report-card-icon">
                        ⏳
                    </div>

                    <span>
                        Pendentes
                    </span>

                    <strong>
                        <?= $totalProfessoresPendentes ?>
                    </strong>

                    <small>
                        aguardando aprovação
                    </small>

                </article>

            </div>

        </section>


        <!-- =================================================
             MATERIAIS
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Biblioteca
                    </span>

                    <h2>
                        Materiais por tipo
                    </h2>

                </div>

            </div>


            <div class="report-grid report-material-grid">


                <article class="report-card">

                    <div class="report-card-icon">
                        🎥
                    </div>

                    <span>
                        Vídeos
                    </span>

                    <strong>
                        <?= $materiaisPorTipo["video"] ?>
                    </strong>

                </article>


                <article class="report-card">

                    <div class="report-card-icon">
                        📄
                    </div>

                    <span>
                        PDFs
                    </span>

                    <strong>
                        <?= $materiaisPorTipo["pdf"] ?>
                    </strong>

                </article>


                <article class="report-card">

                    <div class="report-card-icon">
                        📝
                    </div>

                    <span>
                        Documentos
                    </span>

                    <strong>
                        <?= $materiaisPorTipo["documento"] ?>
                    </strong>

                </article>


                <article class="report-card">

                    <div class="report-card-icon">
                        📊
                    </div>

                    <span>
                        Apresentações
                    </span>

                    <strong>
                        <?= $materiaisPorTipo["apresentacao"] ?>
                    </strong>

                </article>


                <article class="report-card">

                    <div class="report-card-icon">
                        🖼️
                    </div>

                    <span>
                        Imagens
                    </span>

                    <strong>
                        <?= $materiaisPorTipo["imagem"] ?>
                    </strong>

                </article>


                <article class="report-card">

                    <div class="report-card-icon">
                        🔗
                    </div>

                    <span>
                        Links
                    </span>

                    <strong>
                        <?= $materiaisPorTipo["link"] ?>
                    </strong>

                </article>

            </div>

        </section>

    </main>

</div>


<script src="../assets/js/theme.js"></script>

</body>

</html>