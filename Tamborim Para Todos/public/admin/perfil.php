<?php

session_start();

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "admin"
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
        Perfil Administrador | Tamborim Para Todos
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
                class="dashboard-nav-item">

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
                class="dashboard-nav-item active">

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
                    Administrador
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
                        Administrador
                    </span>

                </div>

            </div>

        </div>


        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Conta e preferências
                </span>

                <h2>
                    Gerencie sua conta administrativa
                </h2>

                <p>
                    Consulte seus dados e personalize
                    a aparência da plataforma.
                </p>

            </div>

        </section>


        <section class="dashboard-section">

            <div class="profile-grid">

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
                        Administrador
                    </span>

                    <p class="profile-card-description">
                        Conta administrativa com acesso
                        ao gerenciamento geral da plataforma.
                    </p>

                </article>


                <article class="profile-details-card">

                    <div class="profile-card-heading">

                        <span class="section-eyebrow">
                            Dados da conta
                        </span>

                        <h2>
                            Informações administrativas
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
                            Administrador
                        </strong>

                    </div>


                    <div class="profile-detail">

                        <span>
                            Permissões
                        </span>

                        <strong class="profile-status-active">
                            Acesso total
                        </strong>

                    </div>

                </article>

            </div>

        </section>


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
                            Selecione entre uma interface
                            clara ou escura.
                        </p>

                    </div>

                </div>


                <div class="appearance-options">

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
                        Caso esqueça sua senha,
                        utilize a recuperação disponível no login.
                    </p>

                </div>

                <a
                    href="../esquece.php"
                    class="btn-secondary">

                    Recuperar senha

                </a>

            </div>

        </section>


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
                        Encerre sua sessão administrativa
                        neste dispositivo.
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