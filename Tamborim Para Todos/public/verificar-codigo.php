<?php

if (
    session_status()
    === PHP_SESSION_NONE
) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| RECUPERAÇÃO PRECISA TER SIDO INICIADA
|--------------------------------------------------------------------------
*/

if (
    empty(
        $_SESSION[
            "recuperacao_senha_email"
        ]
    )
) {
    header(
        "Location: esquece.php"
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
        Verificar Código | Tamborim Para Todos
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
                    🔐 Verificação de segurança
                </span>


                <h1>

                    Confirme sua
                    <span>
                        identidade.
                    </span>

                </h1>


                <p>

                    Enviamos um código de seis dígitos
                    para o e-mail informado.

                    Digite o código para continuar
                    a recuperação da sua conta.

                </p>


                <div class="auth-feature-list">


                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            🔢
                        </span>

                        <div>

                            <strong>
                                Código de 6 dígitos
                            </strong>

                            <p>
                                Digite exatamente o código recebido por e-mail.
                            </p>

                        </div>

                    </div>


                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            ⏱️
                        </span>

                        <div>

                            <strong>
                                Validade de 10 minutos
                            </strong>

                            <p>
                                Após esse período será necessário solicitar um novo código.
                            </p>

                        </div>

                    </div>


                    <div class="auth-feature-item">

                        <span class="auth-feature-icon">
                            🛡️
                        </span>

                        <div>

                            <strong>
                                Proteção contra tentativas
                            </strong>

                            <p>
                                O código é bloqueado após cinco tentativas incorretas.
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


                <a
                    href="esquece.php"
                    class="auth-back-link"
                >

                    ← Solicitar outro código

                </a>


                <div class="auth-form-header">

                    <span class="section-eyebrow">
                        Código de segurança
                    </span>

                    <h2>
                        Verifique seu e-mail
                    </h2>

                    <p>

                        Digite abaixo o código
                        de seis dígitos enviado
                        para seu e-mail.

                    </p>

                </div>


                <div id="mensagem">

                    <?php

                    $status =
                        $_GET["status"]
                        ?? "";


                    if ($status === "sent") {

                        echo '
                            <div class="alert-success">
                                Se o e-mail estiver cadastrado,
                                um código de recuperação foi enviado.
                            </div>
                        ';

                    }


                    if ($status === "invalid") {

                        echo '
                            <div class="alert-error">
                                Código inválido.
                                Verifique os números digitados
                                e tente novamente.
                            </div>
                        ';

                    }


                    if ($status === "expired") {

                        echo '
                            <div class="alert-error">
                                O código expirou ou atingiu
                                o limite de tentativas.
                                Solicite um novo código.
                            </div>
                        ';

                    }


                    if ($status === "error") {

                        echo '
                            <div class="alert-error">
                                Não foi possível validar o código.
                                Tente novamente.
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
                        value="verifyRecoveryCode"
                    >


                    <div class="input-group">

                        <label for="codigo">
                            Código de recuperação
                        </label>


                        <input
                            type="text"
                            id="codigo"
                            name="codigo"
                            placeholder="000000"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            maxlength="6"
                            minlength="6"
                            pattern="[0-9]{6}"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="btn-enviar"
                    >

                        Verificar código

                    </button>

                </form>


                <div class="auth-divider">

                    <span>
                        não recebeu?
                    </span>

                </div>


                <a
                    href="esquece.php"
                    class="btn-secondary auth-create-account"
                >

                    Solicitar novo código

                </a>


            </div>

        </section>

    </main>


    <script src="assets/js/theme.js"></script>


    <script>

        const campoCodigo =
            document.getElementById(
                "codigo"
            );


        if (campoCodigo) {

            campoCodigo.addEventListener(
                "input",
                function () {

                    this.value =
                        this.value
                            .replace(
                                /\D/g,
                                ""
                            )
                            .slice(
                                0,
                                6
                            );

                }
            );

        }

    </script>


</body>

</html>