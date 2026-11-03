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
| GARANTE SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| ROTAS
|--------------------------------------------------------------------------
*/

$linkCadastro =
    rota(
        "cadastro"
    );


$linkProcessarCodigo =
    rota(
        "acao.confirmar_email"
    );


/*
|--------------------------------------------------------------------------
| VALIDA FLUXO DE CONFIRMAÇÃO
|--------------------------------------------------------------------------
|
| A página só pode ser acessada enquanto existir
| um usuário aguardando confirmação de e-mail.
|
*/

$usuarioIdConfirmacao =
    (int) (
        $_SESSION["usuario_id_confirmacao"]
        ?? 0
    );


if (
    $usuarioIdConfirmacao <= 0
) {

    header(
        "Location: " .
        $linkCadastro
    );

    exit;

}


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
    content="Confirme seu endereço de e-mail para concluir o cadastro na plataforma Java, Café e Código."
>

<title>
    Confirmar e-mail | <?= SITE_NAME ?>
</title>


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

</head>


<body class="pagina-login pagina-confirmacao">


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
|
| Agora usamos somente o componente central de mensagens.
|
| Não precisamos mais ler diretamente:
|
| $_SESSION["erro"]
| $_SESSION["sucesso"]
|
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

<main class="login-principal confirmacao-principal">


    <!-- ELEMENTOS DECORATIVOS -->

    <div
        class="login-luz login-luz-superior"
        aria-hidden="true"
    ></div>


    <div
        class="login-luz login-luz-inferior"
        aria-hidden="true"
    ></div>


    <div class="login-container confirmacao-container">


        <section class="login-card confirmacao-card">


            <!--
            |--------------------------------------------------------------------------
            | ÁREA DE APRESENTAÇÃO
            |--------------------------------------------------------------------------
            -->

            <div class="login-apresentacao confirmacao-apresentacao">


                <div class="login-apresentacao-conteudo">


                    <span class="login-etiqueta">

                        VALIDAÇÃO DE CADASTRO

                    </span>


                    <h1>

                        Falta apenas confirmar seu
                        <strong>
                            endereço de e-mail
                        </strong>

                    </h1>


                    <p class="login-apresentacao-texto">

                        Enviamos um código de confirmação para o e-mail
                        informado durante o cadastro. Essa etapa ajuda a
                        proteger sua conta e confirmar que o endereço
                        pertence a você.

                    </p>


                    <div class="login-recursos confirmacao-recursos">


                        <!-- PASSO 01 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                01

                            </span>


                            <div>


                                <strong>

                                    Verifique sua caixa de entrada

                                </strong>


                                <p>

                                    Procure pelo e-mail enviado pela plataforma
                                    Java, Café e Código.

                                </p>


                            </div>


                        </div>


                        <!-- PASSO 02 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                02

                            </span>


                            <div>


                                <strong>

                                    Confira a pasta de spam

                                </strong>


                                <p>

                                    Caso não encontre a mensagem, verifique
                                    também as pastas de spam ou lixo eletrônico.

                                </p>


                            </div>


                        </div>


                        <!-- PASSO 03 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                03

                            </span>


                            <div>


                                <strong>

                                    Digite os seis números

                                </strong>


                                <p>

                                    Insira o código recebido exatamente como
                                    aparece na mensagem.

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
                    class="login-codigo-decorativo confirmacao-codigo-decorativo"
                    aria-hidden="true"
                >


                    <span class="codigo-linha">


                        <span class="codigo-palavra">

                            boolean

                        </span>


                        <span class="codigo-metodo">

                            emailConfirmado

                        </span>

                        =

                        <span class="codigo-palavra">

                            false

                        </span>

                        ;


                    </span>


                    <span class="codigo-linha">


                        <span class="codigo-palavra">

                            if

                        </span>

                        (codigoCorreto) {


                    </span>


                    <span class="codigo-linha codigo-recuo">

                        emailConfirmado =

                        <span class="codigo-palavra">

                            true

                        </span>

                        ;

                    </span>


                    <span class="codigo-linha codigo-recuo">

                        System.out.println(

                        <span class="codigo-texto">

                            "Cadastro confirmado!"

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
            | ÁREA DO FORMULÁRIO
            |--------------------------------------------------------------------------
            -->

            <div class="login-formulario-area confirmacao-formulario-area">


                <div class="confirmacao-icone-area">


                    <div
                        class="confirmacao-icone"
                        aria-hidden="true"
                    >

                        @

                    </div>


                </div>


                <div class="login-formulario-cabecalho confirmacao-cabecalho">


                    <span class="login-formulario-etiqueta">

                        CONFIRMAÇÃO DE E-MAIL

                    </span>


                    <h2>

                        Digite o código recebido

                    </h2>


                    <p>

                        Informe o código de seis dígitos enviado para seu e-mail.

                    </p>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | FORMULÁRIO
                |--------------------------------------------------------------------------
                |
                | Antes:
                |
                | POST /backend/auth/processar_codigo.php
                |
                | Agora:
                |
                | POST /confirmar-email
                |
                | O router diferencia GET e POST automaticamente.
                |
                -->

                <form
                    class="login-formulario confirmacao-formulario"
                    method="POST"
                    action="<?= htmlspecialchars(
                        $linkProcessarCodigo,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    autocomplete="off"
                >


                    <!--
                    |--------------------------------------------------------------------------
                    | CÓDIGO
                    |--------------------------------------------------------------------------
                    -->

                    <div class="login-campo confirmacao-campo">


                        <label for="codigo">

                            Código de confirmação

                        </label>


                        <div class="confirmacao-input-container">


                            <input
                                type="text"
                                id="codigo"
                                name="codigo"
                                maxlength="6"
                                minlength="6"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                placeholder="000000"
                                autocomplete="one-time-code"
                                aria-describedby="codigo-ajuda"
                                required
                            >


                        </div>


                        <p
                            id="codigo-ajuda"
                            class="confirmacao-campo-ajuda"
                        >

                            O código deve conter exatamente seis números.

                        </p>


                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | CONFIRMAR
                    |--------------------------------------------------------------------------
                    -->

                    <button
                        type="submit"
                        class="login-botao"
                    >


                        <span>

                            Confirmar meu e-mail

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

                <div class="confirmacao-aviso">


                    <span
                        class="confirmacao-aviso-icone"
                        aria-hidden="true"
                    >

                        i

                    </span>


                    <p>

                        Não compartilhe esse código com outras pessoas.
                        Ele é utilizado somente para concluir seu cadastro.

                    </p>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | VOLTAR
                |--------------------------------------------------------------------------
                |
                | Antes:
                |
                | /frontend/pages/cadastro/cadastro.php
                |
                | Agora:
                |
                | /cadastro
                |
                -->

                <a
                    href="<?= htmlspecialchars(
                        $linkCadastro,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    class="login-voltar"
                >


                    <span aria-hidden="true">

                        ←

                    </span>


                    Voltar para o cadastro


                </a>


            </div>


        </section>


        <p class="login-aviso">

            Após a confirmação, sua conta estará pronta para acessar os
            conteúdos e recursos da plataforma.

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