<?php

if (
    session_status()
    === PHP_SESSION_NONE
) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| AUTORIZAÇÃO OBRIGATÓRIA
|--------------------------------------------------------------------------
|
| Esta página só pode ser acessada após a validação
| do código de recuperação de seis dígitos.
|
*/

if (
    empty(
        $_SESSION[
            "redefinicao_senha_token"
        ]
    )
) {

    header(
        "Location: esquece.php?status=expired"
    );

    exit;
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Nova Senha | Tamborim Para Todos
    </title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>


<body class="auth-page">

    <main class="auth-layout">


        <!-- LADO ESQUERDO -->

        <section class="auth-showcase">

            <div class="auth-showcase-top">

                <a
                    href="inicio.php"
                    class="auth-brand"
                >

                    <img
                        src="assets/img/logo.png"
                        alt="Logo Tamborim Para Todos"
                        class="logo-img"
                    >

                    <span>
                        Tamborim Para Todos
                    </span>

                </a>

            </div>


            <div class="auth-showcase-content">

                <span class="hero-badge">
                    🔐 Última etapa
                </span>


                <h1>

                    Crie sua
                    <span>
                        nova senha.
                    </span>

                </h1>


                <p>

                    Seu código foi confirmado com sucesso.

                    Agora escolha uma nova senha
                    para recuperar o acesso à sua conta.

                </p>


                <div class="auth-feature-list">


                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            🔑
                        </span>

                        <div>

                            <strong>
                                Nova senha
                            </strong>

                            <p>
                                Escolha uma senha que você não utilize em outros serviços.
                            </p>

                        </div>

                    </div>


                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            🛡️
                        </span>

                        <div>

                            <strong>
                                Processo protegido
                            </strong>

                            <p>
                                A alteração só é liberada após a confirmação do código.
                            </p>

                        </div>

                    </div>


                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            ✅
                        </span>

                        <div>

                            <strong>
                                Acesso recuperado
                            </strong>

                            <p>
                                Após salvar, você poderá entrar normalmente com a nova senha.
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

                    <a
                        href="inicio.php"
                        class="auth-brand"
                    >

                        <img
                            src="assets/img/logo.png"
                            alt="Logo Tamborim Para Todos"
                            class="logo-img"
                        >

                        <span>
                            Tamborim Para Todos
                        </span>

                    </a>

                </div>


                <div class="auth-form-header">

                    <span class="section-eyebrow">
                        Nova senha
                    </span>

                    <h2>
                        Redefina sua senha
                    </h2>

                    <p>
                        Digite sua nova senha duas vezes para confirmar.
                    </p>

                </div>


                <div id="mensagem">

                    <?php

                    $status =
                        $_GET["status"]
                        ?? "";


                    if ($status === "error") {

                        echo '
                            <div class="alert-error">
                                Não foi possível alterar sua senha.
                                Tente novamente.
                            </div>
                        ';

                    }


                    if ($status === "password") {

                        echo '
                            <div class="alert-error">
                                As senhas digitadas não são iguais.
                            </div>
                        ';

                    }


                    if ($status === "passwordLength") {

                        echo '
                            <div class="alert-error">
                                A senha precisa ter pelo menos
                                6 caracteres.
                            </div>
                        ';

                    }

                    ?>

                </div>


                <form
                    action="../routes/auth.php"
                    method="POST"
                    class="auth-form"
                >

                    <input
                        type="hidden"
                        name="action"
                        value="resetPassword"
                    >


                    <div class="input-group">

                        <label for="senha">
                            Nova senha
                        </label>

                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="Digite sua nova senha"
                            minlength="6"
                            autocomplete="new-password"
                            required
                        >

                    </div>


                    <div class="input-group">

                        <label for="confirmarSenha">
                            Confirme a nova senha
                        </label>

                        <input
                            type="password"
                            id="confirmarSenha"
                            name="confirmarSenha"
                            placeholder="Digite novamente"
                            minlength="6"
                            autocomplete="new-password"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="btn-enviar"
                    >

                        Salvar nova senha

                    </button>

                </form>


                <div class="auth-divider">

                    <span>
                        segurança
                    </span>

                </div>


                <p
                    style="
                        text-align: center;
                        font-size: 13px;
                        color: #64748b;
                        line-height: 1.6;
                    "
                >

                    Após alterar a senha,
                    esta autorização será invalidada
                    automaticamente.

                </p>


            </div>

        </section>

    </main>


    <script src="assets/js/theme.js"></script>

</body>

</html>