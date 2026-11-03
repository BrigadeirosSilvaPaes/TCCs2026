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
| CAMINHOS DOS ARQUIVOS CSS
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


/*
|--------------------------------------------------------------------------
| ROTAS PÚBLICAS
|--------------------------------------------------------------------------
|
| Nenhuma página PHP física precisa aparecer para o usuário.
|
*/

$linkInicio =
    rota(
        "inicio"
    );


$linkCadastro =
    rota(
        "cadastro"
    );


$linkRecuperarSenha =
    rota(
        "recuperar_senha"
    );


$linkProcessarLogin =
    rota(
        "acao.login"
    );

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
    content="Entre na plataforma Java, Café e Código para acessar conteúdos, atividades e acompanhar seu progresso no aprendizado de Java."
>

<title>
    Login | <?= SITE_NAME ?>
</title>


<!-- CSS GLOBAL -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= filemtime(
        $styleCssPath
    ) ?>"
>


<!-- CSS DO LOGIN -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/login.css?v=<?= filemtime(
        $loginCssPath
    ) ?>"
>


<!-- CSS DAS MENSAGENS -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css?v=<?= filemtime(
        $mensagemCssPath
    ) ?>"
>

</head>


<body class="pagina-login">


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
| MENSAGENS DO SISTEMA
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

<main class="login-principal">


    <!-- ELEMENTOS DECORATIVOS -->

    <div
        class="login-luz login-luz-superior"
        aria-hidden="true"
    ></div>


    <div
        class="login-luz login-luz-inferior"
        aria-hidden="true"
    ></div>


    <div class="login-container">


        <section class="login-card">


            <!--
            |--------------------------------------------------------------------------
            | APRESENTAÇÃO
            |--------------------------------------------------------------------------
            -->

            <div class="login-apresentacao">


                <div class="login-apresentacao-conteudo">


                    <span class="login-etiqueta">

                        ÁREA DO ESTUDANTE

                    </span>


                    <h1>

                        Continue sua jornada no
                        <strong>
                            Java, Café e Código
                        </strong>

                    </h1>


                    <p class="login-apresentacao-texto">

                        Acesse sua conta para estudar os conteúdos,
                        realizar atividades práticas e acompanhar sua
                        evolução durante a trilha de aprendizagem.

                    </p>


                    <div class="login-recursos">


                        <!-- RECURSO 01 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                01

                            </span>


                            <div>


                                <strong>

                                    Conteúdos organizados

                                </strong>


                                <p>

                                    Aprenda Java seguindo uma sequência
                                    construída especialmente para iniciantes.

                                </p>


                            </div>


                        </div>


                        <!-- RECURSO 02 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                02

                            </span>


                            <div>


                                <strong>

                                    Aprendizado prático

                                </strong>


                                <p>

                                    Reforce os conceitos por meio de exemplos,
                                    exercícios e atividades interativas.

                                </p>


                            </div>


                        </div>


                        <!-- RECURSO 03 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                03

                            </span>


                            <div>


                                <strong>

                                    Progresso individual

                                </strong>


                                <p>

                                    Acompanhe sua evolução e retome seus
                                    estudos de onde parou.

                                </p>


                            </div>


                        </div>


                    </div>


                </div>


                <!-- CÓDIGO DECORATIVO -->

                <div
                    class="login-codigo-decorativo"
                    aria-hidden="true"
                >


                    <span class="codigo-linha">


                        <span class="codigo-palavra">

                            public

                        </span>


                        <span class="codigo-palavra">

                            class

                        </span>


                        <span class="codigo-classe">

                            Aprendizado

                        </span>

                        {


                    </span>


                    <span class="codigo-linha codigo-recuo">


                        <span class="codigo-palavra">

                            public static void

                        </span>


                        <span class="codigo-metodo">

                            main

                        </span>

                        () {


                    </span>


                    <span class="codigo-linha codigo-recuo-duplo">

                        System.out.println(

                        <span class="codigo-texto">

                            "Olá, Java!"

                        </span>

                        );

                    </span>


                    <span class="codigo-linha codigo-recuo">

                        }

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

            <div class="login-formulario-area">


                <div class="login-formulario-cabecalho">


                    <span class="login-formulario-etiqueta">

                        ACESSO À PLATAFORMA

                    </span>


                    <h2>

                        Entre na sua conta

                    </h2>


                    <p>

                        Informe seu e-mail e sua senha para continuar.

                    </p>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | FORMULÁRIO DE LOGIN
                |--------------------------------------------------------------------------
                |
                | Antes:
                |
                | /backend/auth/processar_login.php
                |
                | Agora:
                |
                | /entrar
                |
                -->

                <form
                    class="login-formulario"
                    action="<?= htmlspecialchars(
                        $linkProcessarLogin,
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

                            E-mail

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
                    | SENHA
                    |--------------------------------------------------------------------------
                    -->

                    <div class="login-campo">


                        <div class="login-label-linha">


                            <label for="senha">

                                Senha

                            </label>


                            <!--
                            |------------------------------------------------------------------
                            | RECUPERAÇÃO
                            |------------------------------------------------------------------
                            |
                            | Antes:
                            |
                            | /frontend/pages/recuperacao/esqueceu.php
                            |
                            | Agora:
                            |
                            | /recuperar-senha
                            |
                            -->

                            <a
                                href="<?= htmlspecialchars(
                                    $linkRecuperarSenha,
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>"
                                class="login-esqueci-link"
                            >

                                Esqueci minha senha

                            </a>


                        </div>


                        <div class="login-input-container senha-box">


                            <span
                                class="login-input-icone login-senha-icone"
                                aria-hidden="true"
                            >

                                •

                            </span>


                            <input
                                type="password"
                                id="senha"
                                name="senha"
                                placeholder="Digite sua senha"
                                autocomplete="current-password"
                                required
                            >


                            <button
                                type="button"
                                class="ver-senha"
                                onclick="mostrarSenha('senha')"
                                aria-label="Mostrar ou ocultar senha"
                                title="Mostrar ou ocultar senha"
                            >

                                <span aria-hidden="true">

                                    👁

                                </span>

                            </button>


                        </div>


                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | BOTÃO
                    |--------------------------------------------------------------------------
                    -->

                    <button
                        type="submit"
                        class="login-botao"
                    >


                        <span>

                            Entrar na plataforma

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
                | CRIAR CONTA
                |--------------------------------------------------------------------------
                -->

                <div class="login-divisor">

                    <span>

                        ou

                    </span>

                </div>


                <div class="login-cadastro">


                    <p>

                        Ainda não possui uma conta?

                    </p>


                    <!--
                    |------------------------------------------------------------------
                    | Antes:
                    |
                    | /frontend/pages/cadastro/cadastro.php
                    |
                    | Agora:
                    |
                    | /cadastro
                    |------------------------------------------------------------------
                    -->

                    <a
                        href="<?= htmlspecialchars(
                            $linkCadastro,
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>"
                        class="login-botao-secundario"
                    >

                        Criar Conta

                    </a>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | VOLTAR
                |--------------------------------------------------------------------------
                |
                | Antes:
                |
                | /index.php
                |
                | Agora:
                |
                | /
                |
                -->

                <a
                    href="<?= htmlspecialchars(
                        $linkInicio,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    class="login-voltar"
                >


                    <span aria-hidden="true">

                        ←

                    </span>


                    Voltar para a página inicial


                </a>


            </div>


        </section>


        <p class="login-aviso">

            O Java, Café e Código é uma plataforma educacional de apoio ao
            aprendizado e não substitui o acompanhamento de professores.

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


<!--
|--------------------------------------------------------------------------
| JAVASCRIPT DA SENHA
|--------------------------------------------------------------------------
-->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/senha.js"
></script>


</body>

</html>