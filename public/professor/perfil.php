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

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Meu Perfil | Tamborim Para Todos
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
                    Área do Professor
                </span>

            </div>

        </div>


        <!-- MENU -->

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
                class="dashboard-nav-item">

                <span>📁</span>
                Materiais

            </a>

            <a
                href="perfil.php"
                class="dashboard-nav-item active">

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


        <!-- =================================================
             TOPO
        ================================================== -->

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Professor
                </span>

                <h1>
                    Meu perfil
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
                        Professor
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
                    Conta e preferências
                </span>

                <h2>
                    Gerencie seu perfil
                </h2>

                <p>
                    Consulte seus dados de acesso e escolha
                    como deseja visualizar a plataforma.
                </p>

            </div>

        </section>


        <!-- =================================================
             PERFIL
        ================================================== -->

        <section class="dashboard-section">

            <div class="profile-grid">


                <!-- CARD PRINCIPAL -->

                <article class="profile-card">

                    <div class="profile-avatar-large">

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

                    <h2>
                        <?= htmlspecialchars(
                            $usuario["nome"]
                        ) ?>
                    </h2>

                    <span class="status-badge active">
                        Professor
                    </span>

                    <p class="profile-card-description">
                        Conta autorizada para criar
                        e publicar conteúdos na plataforma.
                    </p>

                </article>


                <!-- DADOS -->

                <article class="profile-details-card">

                    <div class="profile-card-heading">

                        <span class="section-eyebrow">
                            Dados da conta
                        </span>

                        <h2>
                            Informações pessoais
                        </h2>

                    </div>


                    <div class="profile-detail">

                        <span>
                            Nome
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $usuario["nome"]
                            ) ?>
                        </strong>

                    </div>


                    <div class="profile-detail">

                        <span>
                            E-mail
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $usuario["email"]
                            ) ?>
                        </strong>

                    </div>


                    <div class="profile-detail">

                        <span>
                            Tipo de conta
                        </span>

                        <strong>
                            Professor
                        </strong>

                    </div>


                    <div class="profile-detail">

                        <span>
                            Status
                        </span>

                        <strong class="profile-status-active">
                            Ativo
                        </strong>

                    </div>

                </article>

            </div>

        </section>


        <!-- =================================================
             APARÊNCIA
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Preferências
                    </span>

                    <h2>
                        Aparência
                    </h2>

                    <p>
                        Escolha o tema que deseja usar na plataforma.
                        A preferência ficará salva neste navegador.
                    </p>

                </div>

            </div>


            <div class="appearance-settings-card">

                <div class="appearance-settings-info">

                    <div class="appearance-settings-icon">
                        ◐
                    </div>

                    <div>

                        <strong>
                            Tema da plataforma
                        </strong>

                        <p>
                            Você pode alternar entre uma interface
                            clara ou escura.
                        </p>

                    </div>

                </div>


                <div class="appearance-options">


                    <!-- TEMA CLARO -->

                    <button
                        type="button"
                        class="appearance-option"
                        data-theme-option="light">

                        <span class="appearance-icon">
                            ☀
                        </span>

                        <span class="appearance-option-info">

                            <strong>
                                Claro
                            </strong>

                            <small>
                                Interface clara
                            </small>

                        </span>

                        <span class="appearance-check">
                            ✓
                        </span>

                    </button>


                    <!-- TEMA ESCURO -->

                    <button
                        type="button"
                        class="appearance-option"
                        data-theme-option="dark">

                        <span class="appearance-icon">
                            🌙
                        </span>

                        <span class="appearance-option-info">

                            <strong>
                                Escuro
                            </strong>

                            <small>
                                Interface escura
                            </small>

                        </span>

                        <span class="appearance-check">
                            ✓
                        </span>

                    </button>

                </div>

            </div>

        </section>


        <!-- =================================================
             SEGURANÇA / SENHA
        ================================================== -->

        <section class="dashboard-section">

            <div class="settings-card">

                <div>

                    <span class="section-eyebrow">
                        Segurança
                    </span>

                    <h2>
                        Senha da conta
                    </h2>

                    <p>
                        Caso esqueça sua senha, utilize a recuperação
                        disponível na tela de login.
                    </p>

                </div>

                <a
                    href="../esquece.php"
                    class="btn-secondary">

                    Recuperar senha

                </a>

            </div>

        </section>


        <!-- =================================================
             SESSÃO
        ================================================== -->

        <section class="dashboard-section">

            <div class="settings-card danger-zone">

                <div>

                    <span class="section-eyebrow">
                        Sessão
                    </span>

                    <h2>
                        Sair da plataforma
                    </h2>

                    <p>
                        Encerre sua sessão atual neste dispositivo.
                    </p>

                </div>

                <a
                    href="../../routes/logout.php"
                    class="btn-danger">

                    Sair da conta

                </a>

            </div>

        </section>

    </main>

</div>


<script src="../assets/js/theme.js"></script>

</body>

</html>