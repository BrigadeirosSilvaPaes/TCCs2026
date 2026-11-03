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

$linkRecuperacao =
    rota(
        "recuperar_senha"
    );


$linkProcessarCodigo =
    rota(
        "acao.recuperar_codigo"
    );


$linkNovaSenha =
    rota(
        "nova_senha"
    );


/*
|--------------------------------------------------------------------------
| VERIFICA FLUXO DE RECUPERAÇÃO
|--------------------------------------------------------------------------
|
| O usuario_id da recuperação deve existir somente na sessão.
|
| Não utilizamos:
|
| ?usuario_id=...
|
| nem campos hidden com o ID da conta.
|
*/

$usuarioIdRecuperacao =
    (int) (
        $_SESSION["usuario_id_recuperacao"]
        ?? 0
    );


if (
    $usuarioIdRecuperacao <= 0
) {

    mensagem(
        "erro",
        "sessao_recuperacao_expirada"
    );


    header(
        "Location: " .
        $linkRecuperacao
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| CÓDIGO JÁ VALIDADO
|--------------------------------------------------------------------------
|
| Se o usuário já concluiu esta etapa, não há motivo
| para voltar ao formulário do código.
|
*/

if (
    isset(
        $_SESSION["codigo_recuperacao_validado"]
    )
    &&
    $_SESSION["codigo_recuperacao_validado"] === true
) {

    header(
        "Location: " .
        $linkNovaSenha
    );

    exit;

}


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
    content="Informe o código de segurança enviado por e-mail para continuar a recuperação da sua conta."
>

<title>
    Verificar código | <?= SITE_NAME ?>
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
| MENSAGENS
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

                        VERIFICAÇÃO DE SEGURANÇA

                    </span>


                    <h1>

                        Confira seu e-mail e informe o
                        <strong>
                            código de recuperação
                        </strong>

                    </h1>


                    <p class="login-apresentacao-texto">

                        Enviamos um código de segurança para o endereço
                        informado. Digite os seis números para confirmar
                        que você possui acesso à conta.

                    </p>


                    <div class="login-recursos recuperacao-recursos">


                        <!-- ETAPA 01 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                01

                            </span>


                            <div>


                                <strong>

                                    Abra seu e-mail

                                </strong>


                                <p>

                                    Procure pela mensagem enviada pelo
                                    Java, Café e Código.

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

                                    Localize o código

                                </strong>


                                <p>

                                    O código contém seis números e possui
                                    tempo limitado de validade.

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

                                    Continue com segurança

                                </strong>


                                <p>

                                    Depois da validação, você poderá criar
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

                            int

                        </span>

                        codigo = 123456;


                    </span>


                    <span class="codigo-linha">


                        <span class="codigo-palavra">

                            if

                        </span>

                        (codigoValido) {


                    </span>


                    <span class="codigo-linha codigo-recuo">


                        liberarNovaSenha();


                    </span>


                    <span class="codigo-linha codigo-recuo">


                        System.out.println(

                        <span class="codigo-texto">

                            "Código confirmado!"

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

                        #

                    </div>


                </div>


                <div class="login-formulario-cabecalho">


                    <span class="login-formulario-etiqueta">

                        SEGUNDA ETAPA

                    </span>


                    <h2>

                        Digite o código recebido

                    </h2>


                    <p>

                        Informe os seis números enviados para seu e-mail.

                    </p>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | ETAPAS
                |--------------------------------------------------------------------------
                -->

                <div
                    class="recuperacao-etapas"
                    aria-label="Etapas da recuperação de senha"
                >


                    <!-- E-MAIL CONCLUÍDO -->

                    <div class="recuperacao-etapa recuperacao-etapa-concluida">


                        <span>

                            ✓

                        </span>


                        <small>

                            E-mail

                        </small>


                    </div>


                    <div
                        class="recuperacao-etapa-linha recuperacao-linha-concluida"
                    ></div>


                    <!-- CÓDIGO ATUAL -->

                    <div class="recuperacao-etapa recuperacao-etapa-ativa">


                        <span>

                            2

                        </span>


                        <small>

                            Código

                        </small>


                    </div>


                    <div class="recuperacao-etapa-linha"></div>


                    <!-- NOVA SENHA -->

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
                | FORMULÁRIO DE VERIFICAÇÃO
                |--------------------------------------------------------------------------
                |
                | Antes:
                |
                | /backend/auth/processar_codigo_redefinicao.php
                |
                | Agora:
                |
                | POST /recuperar-senha/codigo
                |
                -->

                <form
                    class="login-formulario recuperacao-formulario"
                    action="<?= htmlspecialchars(
                        $linkProcessarCodigo,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    method="POST"
                    autocomplete="off"
                >


                    <!--
                    |--------------------------------------------------------------------------
                    | CÓDIGO
                    |--------------------------------------------------------------------------
                    -->

                    <div class="login-campo">


                        <label for="codigo">

                            Código de recuperação

                        </label>


                        <div class="confirmacao-input-container">


                            <input
                                type="text"
                                id="codigo"
                                name="codigo"
                                placeholder="000000"
                                minlength="6"
                                maxlength="6"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                autocomplete="one-time-code"
                                aria-describedby="codigo-ajuda"
                                required
                            >


                        </div>


                        <p
                            id="codigo-ajuda"
                            class="confirmacao-campo-ajuda"
                        >

                            Digite exatamente os seis números recebidos.

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

                            Verificar código

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
                | SEGURANÇA
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

                        Não compartilhe o código recebido.
                        A plataforma nunca solicitará esse código
                        fora do processo de recuperação de senha.

                    </p>


                </div>


                <!--
                |--------------------------------------------------------------------------
                | SOLICITAR NOVAMENTE
                |--------------------------------------------------------------------------
                |
                | Voltamos para a primeira etapa.
                |
                | Uma nova solicitação poderá gerar outro código.
                |
                -->

                <a
                    href="<?= htmlspecialchars(
                        $linkRecuperacao,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    class="login-voltar"
                >


                    <span aria-hidden="true">

                        ←

                    </span>


                    Solicitar um novo código


                </a>


            </div>


        </section>


        <p class="login-aviso">

            Por segurança, códigos de recuperação possuem validade limitada
            e não devem ser compartilhados.

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