<?php

session_start();

if (isset($_SESSION["usuario"])) {

    $tipo = $_SESSION["usuario"]["tipo"] ?? "";

    switch ($tipo) {

        case "admin":
            header("Location: admin/painel.php");
            exit;

        case "professor":
            header("Location: professor/painel.php");
            exit;

        case "aluno":
            header("Location: aluno/painel.php");
            exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Criar Conta | Tamborim Para Todos</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="auth-page">

    <main class="auth-layout">

        <!-- LADO ESQUERDO -->

        <section class="auth-showcase">

            <div class="auth-showcase-top">

                <a href="inicio.php" class="auth-brand">

                    <img
                        src="assets/img/logo.png"
                        alt="Logo Tamborim Para Todos"
                        class="logo-img">

                    <span>
                        Tamborim Para Todos
                    </span>

                </a>

                

            </div>

            <div class="auth-showcase-content">

                <span class="hero-badge">
                    🥁 Comece sua jornada musical
                </span>

                <h1>
                    Faça parte do
                    <span>Tamborim Para Todos.</span>
                </h1>

                <p>
                    Crie sua conta para acessar conteúdos,
                    acompanhar seu aprendizado ou contribuir
                    com novas aulas dentro da plataforma.
                </p>

                <div class="auth-feature-list">

                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            🎓
                        </span>

                        <div>

                            <strong>
                                Conta de aluno
                            </strong>

                            <p>
                                Acesso imediato às aulas e conteúdos disponíveis.
                            </p>

                        </div>

                    </div>

                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            👨‍🏫
                        </span>

                        <div>

                            <strong>
                                Conta de professor
                            </strong>

                            <p>
                                Professores podem publicar conteúdos após aprovação do administrador.
                            </p>

                        </div>

                    </div>

                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            🔐
                        </span>

                        <div>

                            <strong>
                                Acesso seguro
                            </strong>

                            <p>
                                Cada tipo de usuário possui permissões específicas dentro da plataforma.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            <div class="auth-showcase-footer">

                <p>
                    E.E.E.M. Brigadeiro José da Silva Paes
                </p>

            </div>

        </section>


        <!-- LADO DIREITO -->

        <section class="auth-form-side">

            <div class="auth-form-wrapper">

                <div class="auth-mobile-header">

                    <a href="inicio.php" class="auth-brand">

                        <img
                            src="assets/img/logo.png"
                            alt="Logo Tamborim Para Todos"
                            class="logo-img">

                        <span>
                            Tamborim Para Todos
                        </span>

                    </a>

                </div>

                <a
                    href="inicio.php"
                    class="auth-back-link">

                    ← Voltar para o início

                </a>

                <div class="auth-form-header">

                    <span class="section-eyebrow">
                        Nova conta
                    </span>

                    <h2>
                        Crie sua conta
                    </h2>

                    <p>
                        Escolha seu perfil e preencha seus dados.
                    </p>

                </div>

                <form
                    action="../routes/auth.php"
                    method="POST"
                    class="auth-form">

                    <input
                        type="hidden"
                        name="action"
                        value="register">

                    <div class="input-group">

                        <label for="tipo">
                            Criar conta como
                        </label>

                        <select
                            id="tipo"
                            name="tipo"
                            required>

                            <option value="">
                                Selecione seu perfil
                            </option>

                            <option value="aluno">
                                Aluno
                            </option>

                            <option value="professor">
                                Professor
                            </option>

                        </select>

                    </div>

                    <div
                        id="avisoProfessor"
                        class="alert-warning"
                        style="display: none;">

                        Contas de professor precisam ser aprovadas pelo administrador antes do primeiro acesso.

                    </div>

                    <div class="input-group">

                        <label for="nome">
                            Nome completo
                        </label>

                        <input
                            type="text"
                            id="nome"
                            name="nome"
                            placeholder="Digite seu nome completo"
                            autocomplete="name"
                            required>

                    </div>

                    <div class="input-group">

                        <label for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Digite seu email"
                            autocomplete="email"
                            required>

                    </div>

                    <div class="input-group">

                        <label for="senha">
                            Senha
                        </label>

                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="Crie uma senha"
                            minlength="6"
                            autocomplete="new-password"
                            required>

                    </div>

                    <div class="input-group">

                        <label for="confirmarSenha">
                            Confirmar senha
                        </label>

                        <input
                            type="password"
                            id="confirmarSenha"
                            name="confirmarSenha"
                            placeholder="Digite novamente sua senha"
                            minlength="6"
                            autocomplete="new-password"
                            required>

                    </div>

                    <button
                        type="submit"
                        class="btn-enviar">

                        Criar conta

                    </button>

                </form>

                <div class="auth-divider">

                    <span>
                        já possui uma conta?
                    </span>

                </div>

                <a
                    href="login.php"
                    class="btn-secondary auth-create-account">

                    Fazer login

                </a>

            </div>

        </section>

    </main>

    <script src="assets/js/theme.js"></script>

    <script>
        const tipo = document.getElementById("tipo");
        const avisoProfessor = document.getElementById("avisoProfessor");

        tipo.addEventListener("change", () => {

            if (tipo.value === "professor") {
                avisoProfessor.style.display = "block";
            } else {
                avisoProfessor.style.display = "none";
            }

        });
    </script>

</body>

</html>