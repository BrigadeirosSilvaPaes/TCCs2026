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

    <title>Login | Tamborim Para Todos</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<header class="top-bar">

    <div class="logo-container">

        <img
            src="assets/img/logo.png"
            class="logo-img"
            alt="Logo Tamborim Para Todos">

        <div class="logo-text">
            Tamborim Para Todos
        </div>

    </div>

    <a href="inicio.php" class="btn-voltar">
        Voltar
    </a>

</header>

<main class="container">

    <div class="login-box">

        <h2>
            Acessar <span>Plataforma</span>
        </h2>

        <p>
            Escolha seu perfil e faça login.
        </p>

        <div id="mensagem"></div>

        <form action="../routes/auth.php" method="POST">

            <input
                type="hidden"
                name="action"
                value="login">

            <div class="input-group">

                <label for="perfil">
                    Entrar como
                </label>

                <select
                    id="perfil"
                    name="perfil"
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

                    <option value="admin">
                        Administrador
                    </option>

                </select>

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
                    placeholder="Digite sua senha"
                    required>

            </div>

            <button
                class="btn-login"
                type="submit">

                Entrar

            </button>

        </form>

        <div class="links">

            <p>
                <a href="esquece.php">
                    Esqueci Minha Senha
                </a>
            </p>

            <p>
                <a href="criar.php">
                    Criar Conta
                </a>
            </p>

        </div>

    </div>

</main>

<footer>

    Criado por
    <span>Bernado Monte Padilha</span>
    e
    <span>Laura Nascimento Martins</span>

</footer>

<script src="assets/js/login.js"></script>

</body>

</html>