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
| ROTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

$linkInicio =
    rota(
        "inicio"
    );


$linkLogin =
    rota(
        "login"
    );


$linkProcessarCadastro =
    rota(
        "acao.cadastro"
    );


/*
|--------------------------------------------------------------------------
| CAMINHOS CSS
|--------------------------------------------------------------------------
*/

$mensagemCssPath =
    __DIR__ .
    "/../../assets/css/mensagem.css";


$styleCssPath =
    __DIR__ .
    "/../../assets/css/style.css";


$loginCssPath =
    __DIR__ .
    "/../../assets/css/login.css";

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
    content="Crie sua conta no Java, Café e Código e comece sua jornada de aprendizagem em programação Java."
>

<title>
    Cadastro | <?= SITE_NAME ?>
</title>


<!-- CSS DAS MENSAGENS -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css?v=<?= filemtime(
        $mensagemCssPath
    ) ?>"
>


<!-- CSS PRINCIPAL -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= filemtime(
        $styleCssPath
    ) ?>"
>


<!-- CSS DE AUTENTICAÇÃO -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/login.css?v=<?= filemtime(
        $loginCssPath
    ) ?>"
>

</head>


<body class="pagina-login pagina-cadastro">


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

<main class="login-principal cadastro-principal">


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


        <section class="login-card cadastro-card">


            <!--
            |--------------------------------------------------------------------------
            | APRESENTAÇÃO
            |--------------------------------------------------------------------------
            -->

            <div class="login-apresentacao cadastro-apresentacao">


                <div class="login-apresentacao-conteudo">


                    <span class="login-etiqueta">

                        COMECE SUA JORNADA

                    </span>


                    <h1>

                        Dê o primeiro passo para aprender
                        <strong>
                            programação em Java
                        </strong>

                    </h1>


                    <p class="login-apresentacao-texto">

                        Crie sua conta gratuitamente e tenha acesso a uma
                        trilha de aprendizagem organizada, com conteúdos,
                        exemplos e atividades voltadas para quem está
                        começando na programação.

                    </p>


                    <div class="login-recursos cadastro-recursos">


                        <!-- RECURSO 01 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                01

                            </span>


                            <div>


                                <strong>

                                    Aprenda no seu ritmo

                                </strong>


                                <p>

                                    Estude os conteúdos de acordo com sua
                                    disponibilidade e avance gradualmente.

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

                                    Conteúdo acessível

                                </strong>


                                <p>

                                    Entenda conceitos de Java por meio de
                                    explicações simples e exemplos práticos.

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

                                    Evolução acompanhada

                                </strong>


                                <p>

                                    Registre seu progresso e continue os
                                    estudos a partir da última etapa concluída.

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

                            public

                        </span>


                        <span class="codigo-palavra">

                            class

                        </span>


                        <span class="codigo-classe">

                            NovoAluno

                        </span>

                        {


                    </span>


                    <span class="codigo-linha codigo-recuo">


                        <span class="codigo-palavra">

                            public static void

                        </span>


                        <span class="codigo-metodo">

                            começar

                        </span>

                        () {


                    </span>


                    <span class="codigo-linha codigo-recuo-duplo">

                        System.out.println(

                        <span class="codigo-texto">

                            "Minha jornada começou!"

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

            <div class="login-formulario-area cadastro-formulario-area">


                <div class="login-formulario-cabecalho">


                    <span class="login-formulario-etiqueta">

                        CRIAÇÃO DE CONTA

                    </span>


                    <h2>

                        Crie sua conta

                    </h2>


                    <p>

                        Preencha seus dados para acessar a plataforma.

                    </p>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | FORMULÁRIO DE CADASTRO
                |--------------------------------------------------------------------------
                |
                | Antes:
                |
                | /backend/auth/processar_cadastro.php
                |
                | Agora:
                |
                | /criar-conta
                |
                -->

                <form
                    class="login-formulario cadastro-formulario"
                    action="<?= htmlspecialchars(
                        $linkProcessarCadastro,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    method="POST"
                    autocomplete="on"
                >


                    <!--
                    |--------------------------------------------------------------------------
                    | NOME
                    |--------------------------------------------------------------------------
                    -->

                    <div class="login-campo">


                        <label for="nome">

                            Nome completo

                        </label>


                        <div class="login-input-container">


                            <span
                                class="login-input-icone"
                                aria-hidden="true"
                            >

                                N

                            </span>


                            <input
                                type="text"
                                id="nome"
                                name="nome"
                                placeholder="Digite seu nome completo"
                                autocomplete="name"
                                minlength="3"
                                maxlength="100"
                                required
                            >


                        </div>


                    </div>


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


                        <label for="senha">

                            Senha

                        </label>


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
                                placeholder="Crie uma senha"
                                autocomplete="new-password"
                                minlength="6"
                                maxlength="72"
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


                        <p class="cadastro-campo-ajuda">

                            Utilize entre 6 e 72 caracteres.

                        </p>


                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | CONFIRMAR SENHA
                    |--------------------------------------------------------------------------
                    -->

                    <div class="login-campo">


                        <label for="confirmarSenha">

                            Confirmar senha

                        </label>


                        <div class="login-input-container senha-box">


                            <span
                                class="login-input-icone login-senha-icone"
                                aria-hidden="true"
                            >

                                •

                            </span>


                            <input
                                type="password"
                                id="confirmarSenha"
                                name="confirmarSenha"
                                placeholder="Digite novamente sua senha"
                                autocomplete="new-password"
                                minlength="6"
                                maxlength="72"
                                required
                            >


                            <button
                                type="button"
                                class="ver-senha"
                                onclick="mostrarSenha('confirmarSenha')"
                                aria-label="Mostrar ou ocultar a confirmação da senha"
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
                    | AVISO
                    |--------------------------------------------------------------------------
                    -->

                    <div class="cadastro-termos">


                        <span
                            class="cadastro-termos-icone"
                            aria-hidden="true"
                        >

                            i

                        </span>


                        <p>

                            Ao criar sua conta, você confirma que os dados
                            informados são válidos e serão utilizados somente
                            para o funcionamento da plataforma.

                        </p>


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

                            Criar minha conta

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
                | DIVISOR
                |--------------------------------------------------------------------------
                -->

                <div class="login-divisor">


                    <span>

                        ou

                    </span>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | LOGIN
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

                <div class="login-cadastro">


                    <p>

                        Já possui uma conta?

                    </p>


                    <a
                        href="<?= htmlspecialchars(
                            $linkLogin,
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>"
                        class="login-botao-secundario"
                    >

                        Entrar na plataforma

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

            O Java, Café e Código é uma plataforma auxiliar de aprendizagem.
            A criação da conta é gratuita e não gera certificação.

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