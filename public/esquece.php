<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recuperar Senha | Tamborim Para Todos</title>

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
                    🔐 Recuperação de acesso
                </span>

                <h1>
                    Recupere sua conta
                    <span>com segurança.</span>
                </h1>

                <p>
                    Informe o e-mail utilizado no cadastro.
                    Se a conta estiver registrada, você receberá
                    um código de 6 dígitos para definir uma nova senha.
                </p>

                <div class="auth-feature-list">

                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            ✉️
                        </span>

                        <div>

                            <strong>
                                Código por e-mail
                            </strong>

                            <p>
                                O sistema envia um código de 6 dígitos para o e-mail cadastrado.
                            </p>

                        </div>

                    </div>

                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            ⏱️
                        </span>

                        <div>

                            <strong>
                                Código temporário
                            </strong>

                            <p>
                                O código de recuperação é válido por 10 minutos.
                            </p>

                        </div>

                    </div>

                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            🛡️
                        </span>

                        <div>

                            <strong>
                                Uso único
                            </strong>

                            <p>
                                Após a validação, o código não poderá ser utilizado novamente.
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
                    href="login.php"
                    class="auth-back-link">

                    ← Voltar para o login

                </a>

                <div class="auth-form-header">

                    <span class="section-eyebrow">
                        Esqueceu sua senha?
                    </span>

                    <h2>
                        Recuperar acesso
                    </h2>

                    <p>
                        Digite seu e-mail para receber um código de recuperação.
                    </p>

                </div>

                <div id="mensagem"></div>

                <form
                    action="../routes/auth.php"
                    method="POST"
                    class="auth-form">

                    <input
                        type="hidden"
                        name="action"
                        value="forgotPassword">

                    <div class="input-group">

                        <label for="email">
                            E-mail
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Digite seu e-mail"
                            autocomplete="email"
                            required>

                    </div>

                    <button
                        type="submit"
                        class="btn-enviar">

                        Enviar código de recuperação

                    </button>

                </form>

                <div class="auth-divider">

                    <span>
                        lembrou sua senha?
                    </span>

                </div>

                <a
                    href="login.php"
                    class="btn-secondary auth-create-account">

                    Voltar para o login

                </a>

            </div>

        </section>

    </main>

    <script src="assets/js/theme.js"></script>
    <script src="assets/js/esquece.js"></script>

</body>

</html>