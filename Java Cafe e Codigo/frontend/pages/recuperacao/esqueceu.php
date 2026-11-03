<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../backend/config/app.php";
require_once __DIR__ . "/../../../backend/config/helpers.php";


/*
|--------------------------------------------------------------------------
| ROTAS
|--------------------------------------------------------------------------
*/

$linkLogin =
    rota(
        "login"
    );


$linkProcessarRecuperacao =
    rota(
        "acao.recuperar_senha"
    );


/*
|--------------------------------------------------------------------------
| CAMINHOS CSS
|--------------------------------------------------------------------------
*/

$styleCssPath =
    __DIR__ .
    "/../../assets/css/style.css";


$loginCssPath =
    __DIR__ .
    "/../../assets/css/login.css";


$mensagemCssPath =
    __DIR__ .
    "/../../assets/css/mensagem.css";

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="description"
    content="Recupere o acesso à sua conta na plataforma Java, Café e Código."
>

<title>
    Recuperar senha | <?= SITE_NAME ?>
</title>


<!--
|--------------------------------------------------------------------------
| CSS PRINCIPAL
|--------------------------------------------------------------------------
-->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= filemtime(
        $styleCssPath
    ) ?>"
>


<!--
|--------------------------------------------------------------------------
| CSS DE AUTENTICAÇÃO
|--------------------------------------------------------------------------
-->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/login.css?v=<?= filemtime(
        $loginCssPath
    ) ?>"
>


<!--
|--------------------------------------------------------------------------
| CSS DAS MENSAGENS
|--------------------------------------------------------------------------
-->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css?v=<?= filemtime(
        $mensagemCssPath
    ) ?>"
>

</head>


<body class="pagina-login pagina-recuperacao">


<!--
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
-->

<?php

include __DIR__ . "/../../componentes/header.php";

?>


<!--
|--------------------------------------------------------------------------
| MENSAGEM DO SISTEMA
|--------------------------------------------------------------------------
-->

<?php

include __DIR__ . "/../../componentes/mensagem.php";

?>


<!--
|--------------------------------------------------------------------------
| CONTEÚDO PRINCIPAL
|--------------------------------------------------------------------------
-->

<main class="login-principal recuperacao-principal">


    <!-- ELEMENTOS DECORATIVOS -->

    <div
        class="login-luz login-luz-superior"
        aria-hidden="true"
    ></div>


    <div
        class="login-luz login-luz-inferior"
        aria-hidden="true"
    ></div>


    <div class="login-container recuperacao-container">


        <section class="login-card recuperacao-card">


            <!--
            |--------------------------------------------------------------------------
            | APRESENTAÇÃO
            |--------------------------------------------------------------------------
            -->

            <div class="login-apresentacao recuperacao-apresentacao">


                <div class="login-apresentacao-conteudo">


                    <span class="login-etiqueta">

                        RECUPERAÇÃO DE ACESSO

                    </span>


                    <h1>

                        Vamos ajudar você a recuperar sua
                        <strong>
                            conta na plataforma
                        </strong>

                    </h1>


                    <p class="login-apresentacao-texto">

                        Informe o e-mail utilizado no cadastro. Enviaremos
                        um código de segurança para confirmar sua identidade
                        e permitir a criação de uma nova senha.

                    </p>


                    <div class="login-recursos recuperacao-recursos">


                        <!-- ETAPA 01 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                01

                            </span>


                            <div>


                                <strong>

                                    Informe seu e-mail

                                </strong>


                                <p>

                                    Utilize o mesmo endereço cadastrado
                                    anteriormente na plataforma.

                                </p>


                            </div>


                        </div>


                        <!-- ETAPA 02 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                02

                            </span>


                            <div>


                                <strong>

                                    Receba o código

                                </strong>


                                <p>

                                    Um código de segurança será enviado para
                                    sua caixa de entrada.

                                </p>


                            </div>


                        </div>


                        <!-- ETAPA 03 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                03

                            </span>


                            <div>


                                <strong>

                                    Crie uma nova senha

                                </strong>


                                <p>

                                    Após validar o código, você poderá definir
                                    uma nova senha para sua conta.

                                </p>


                            </div>


                        </div>


                    </div>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | CÓDIGO DECORATIVO
                |--------------------------------------------------------------------------
                -->

                <div
                    class="login-codigo-decorativo"
                    aria-hidden="true"
                >


                    <span class="codigo-linha">


                        <span class="codigo-palavra">

                            String

                        </span>

                        usuario =

                        <span class="codigo-texto">

                            "estudante"

                        </span>

                        ;


                    </span>


                    <span class="codigo-linha">


                        <span class="codigo-palavra">

                            if

                        </span>

                        (usuarioEsqueceuSenha) {


                    </span>


                    <span class="codigo-linha codigo-recuo">

                        enviarCodigoRecuperacao();

                    </span>


                    <span class="codigo-linha codigo-recuo">

                        System.out.println(

                        <span class="codigo-texto">

                            "Código enviado!"

                        </span>

                        );

                    </span>


                    <span class="codigo-linha">

                        }

                    </span>


                </div>


            </div>


            <!--
            |--------------------------------------------------------------------------
            | FORMULÁRIO
            |--------------------------------------------------------------------------
            -->

            <div class="login-formulario-area recuperacao-formulario-area">


                <div class="recuperacao-icone-area">


                    <div
                        class="recuperacao-icone"
                        aria-hidden="true"
                    >

                        @

                    </div>


                </div>


                <div class="login-formulario-cabecalho">


                    <span class="login-formulario-etiqueta">

                        PRIMEIRA ETAPA

                    </span>


                    <h2>

                        Recupere sua senha

                    </h2>


                    <p>

                        Digite o e-mail associado à sua conta.

                    </p>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | INDICADOR DAS ETAPAS
                |--------------------------------------------------------------------------
                -->

                <div
                    class="recuperacao-etapas"
                    aria-label="Etapas da recuperação de senha"
                >


                    <div class="recuperacao-etapa recuperacao-etapa-ativa">


                        <span>

                            1

                        </span>


                        <small>

                            E-mail

                        </small>


                    </div>


                    <div class="recuperacao-etapa-linha"></div>


                    <div class="recuperacao-etapa">


                        <span>

                            2

                        </span>


                        <small>

                            Código

                        </small>


                    </div>


                    <div class="recuperacao-etapa-linha"></div>


                    <div class="recuperacao-etapa">


                        <span>

                            3

                        </span>


                        <small>

                            Nova senha

                        </small>


                    </div>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | FORMULÁRIO DE RECUPERAÇÃO
                |--------------------------------------------------------------------------
                |
                | Antes:
                |
                | /backend/auth/processar_recuperacao.php
                |
                | Agora:
                |
                | POST /recuperar-senha
                |
                | O router diferencia:
                |
                | GET  /recuperar-senha
                |      → abre esta página
                |
                | POST /recuperar-senha
                |      → processa o pedido
                |
                -->

                <form
                    class="login-formulario recuperacao-formulario"
                    action="<?= htmlspecialchars(
                        $linkProcessarRecuperacao,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    method="POST"
                    autocomplete="on"
                >


                    <!--
                    |--------------------------------------------------------------------------
                    | E-MAIL
                    |--------------------------------------------------------------------------
                    -->

                    <div class="login-campo">


                        <label for="email">

                            E-mail cadastrado

                        </label>


                        <div class="login-input-container">


                            <span
                                class="login-input-icone"
                                aria-hidden="true"
                            >

                                @

                            </span>


                            <input
                                type="email"
                                id="email"
                                name="email"
                                placeholder="seuemail@exemplo.com"
                                autocomplete="email"
                                maxlength="150"
                                required
                            >


                        </div>


                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | ENVIAR
                    |--------------------------------------------------------------------------
                    -->

                    <button
                        type="submit"
                        class="login-botao"
                    >


                        <span>

                            Enviar código de recuperação

                        </span>


                        <span
                            class="login-botao-seta"
                            aria-hidden="true"
                        >

                            →

                        </span>


                    </button>


                </form>


                <!--
                |--------------------------------------------------------------------------
                | AVISO
                |--------------------------------------------------------------------------
                -->

                <div class="recuperacao-aviso">


                    <span
                        class="recuperacao-aviso-icone"
                        aria-hidden="true"
                    >

                        i

                    </span>


                    <p>

                        Confira também a pasta de spam ou lixo eletrônico
                        caso não encontre a mensagem na caixa de entrada.

                    </p>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | VOLTAR
                |--------------------------------------------------------------------------
                |
                | Antes:
                |
                | /frontend/pages/login/login.php
                |
                | Agora:
                |
                | /login
                |
                -->

                <a
                    href="<?= htmlspecialchars(
                        $linkLogin,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    class="login-voltar"
                >


                    <span aria-hidden="true">

                        ←

                    </span>


                    Voltar para o login


                </a>


            </div>


        </section>


        <p class="login-aviso">

            O código de recuperação é pessoal e não deve ser compartilhado
            com outras pessoas.

        </p>


    </div>


</main>


<!--
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
-->

<?php

include __DIR__ . "/../../componentes/footer.php";

?>


<!--
|--------------------------------------------------------------------------
| JAVASCRIPT DO TEMA
|--------------------------------------------------------------------------
-->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js"
></script>


</body>

</html>