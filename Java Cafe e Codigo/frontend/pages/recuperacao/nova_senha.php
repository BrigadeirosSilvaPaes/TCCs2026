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


$linkLogin =
    rota(
        "login"
    );


$linkProcessarNovaSenha =
    rota(
        "acao.nova_senha"
    );


/*
|--------------------------------------------------------------------------
| DADOS DO FLUXO DE RECUPERAÇÃO
|--------------------------------------------------------------------------
*/

$usuarioIdRecuperacao =
    (int) (
        $_SESSION["usuario_id_recuperacao"]
        ?? 0
    );


$codigoRecuperacao =
    $_SESSION["codigo_recuperacao"]
    ?? "";


$codigoValidado =
    $_SESSION["codigo_recuperacao_validado"]
    ?? false;


/*
|--------------------------------------------------------------------------
| VALIDA USUÁRIO DA RECUPERAÇÃO
|--------------------------------------------------------------------------
*/

if (
    $usuarioIdRecuperacao <= 0
) {

    unset(
        $_SESSION["usuario_id_recuperacao"],
        $_SESSION["codigo_recuperacao"],
        $_SESSION["codigo_recuperacao_validado"],
        $_SESSION["codigo_recuperacao_validado_em"]
    );


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
| VALIDA AUTORIZAÇÃO DA ETAPA
|--------------------------------------------------------------------------
|
| Não basta acessar:
|
| /recuperar-senha/nova-senha
|
| diretamente.
|
| O código precisa ter sido validado anteriormente.
|
*/

if (
    $codigoValidado !== true
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
| VALIDA CÓDIGO MANTIDO NA SESSÃO
|--------------------------------------------------------------------------
|
| O código não aparece:
|
| - na URL;
| - no HTML;
| - em input hidden;
| - no JavaScript.
|
| Ele permanece somente na sessão PHP.
|
*/

if (
    !is_string(
        $codigoRecuperacao
    )
    ||
    preg_match(
        "/^[0-9]{6}$/",
        $codigoRecuperacao
    ) !== 1
) {

    unset(
        $_SESSION["codigo_recuperacao"],
        $_SESSION["codigo_recuperacao_validado"],
        $_SESSION["codigo_recuperacao_validado_em"]
    );


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
    content="Crie uma nova senha para sua conta na plataforma Java, Café e Código."
>

<title>
    Nova senha | <?= SITE_NAME ?>
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

                        ÚLTIMA ETAPA

                    </span>


                    <h1>

                        Crie uma nova senha para proteger sua
                        <strong>
                            conta e seus dados
                        </strong>

                    </h1>


                    <p class="login-apresentacao-texto">

                        Escolha uma senha segura e diferente das utilizadas
                        anteriormente. Após salvar, você poderá entrar
                        novamente na plataforma.

                    </p>


                    <div class="login-recursos recuperacao-recursos">


                        <!-- DICA 01 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                01

                            </span>


                            <div>


                                <strong>

                                    Utilize seis ou mais caracteres

                                </strong>


                                <p>

                                    Senhas maiores geralmente oferecem
                                    mais segurança para sua conta.

                                </p>


                            </div>


                        </div>


                        <!-- DICA 02 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                02

                            </span>


                            <div>


                                <strong>

                                    Evite informações óbvias

                                </strong>


                                <p>

                                    Não utilize apenas seu nome, data de
                                    nascimento ou sequências simples.

                                </p>


                            </div>


                        </div>


                        <!-- DICA 03 -->

                        <div class="login-recurso">


                            <span class="login-recurso-numero">

                                03

                            </span>


                            <div>


                                <strong>

                                    Guarde sua senha

                                </strong>


                                <p>

                                    Não compartilhe sua senha com colegas
                                    ou outras pessoas.

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

                        novaSenha =

                        <span class="codigo-texto">

                            "********"

                        </span>

                        ;


                    </span>


                    <span class="codigo-linha">


                        <span class="codigo-palavra">

                            if

                        </span>

                        (senhaSegura) {


                    </span>


                    <span class="codigo-linha codigo-recuo">

                        atualizarSenha(novaSenha);

                    </span>


                    <span class="codigo-linha codigo-recuo">

                        System.out.println(

                        <span class="codigo-texto">

                            "Senha atualizada!"

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

                        •

                    </div>


                </div>


                <div class="login-formulario-cabecalho">


                    <span class="login-formulario-etiqueta">

                        TERCEIRA ETAPA

                    </span>


                    <h2>

                        Crie sua nova senha

                    </h2>


                    <p>

                        Digite e confirme a senha que será utilizada no login.

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


                    <!-- E-MAIL -->

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


                    <!-- CÓDIGO -->

                    <div class="recuperacao-etapa recuperacao-etapa-concluida">


                        <span>

                            ✓

                        </span>


                        <small>

                            Código

                        </small>


                    </div>


                    <div
                        class="recuperacao-etapa-linha recuperacao-linha-concluida"
                    ></div>


                    <!-- NOVA SENHA -->

                    <div class="recuperacao-etapa recuperacao-etapa-ativa">


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
                | FORMULÁRIO
                |--------------------------------------------------------------------------
                |
                | Antes:
                |
                | POST /backend/auth/processar_nova_senha.php
                |
                | Agora:
                |
                | POST /recuperar-senha/nova-senha
                |
                -->

                <form
                    class="login-formulario recuperacao-formulario"
                    action="<?= htmlspecialchars(
                        $linkProcessarNovaSenha,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    method="POST"
                    autocomplete="on"
                >


                    <!--
                    |--------------------------------------------------------------------------
                    | NOVA SENHA
                    |--------------------------------------------------------------------------
                    -->

                    <div class="login-campo">


                        <label for="senha">

                            Nova senha

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
                                placeholder="Digite sua nova senha"
                                autocomplete="new-password"
                                minlength="6"
                                maxlength="72"
                                required
                            >


                            <button
                                type="button"
                                class="ver-senha"
                                onclick="mostrarSenha('senha')"
                                aria-label="Mostrar ou ocultar nova senha"
                                title="Mostrar ou ocultar senha"
                            >


                                <span aria-hidden="true">

                                    👁

                                </span>


                            </button>


                        </div>


                        <p class="recuperacao-campo-ajuda">

                            Utilize entre 6 e 72 caracteres.

                        </p>


                    </div>


                    <!--
                    |--------------------------------------------------------------------------
                    | CONFIRMAR SENHA
                    |--------------------------------------------------------------------------
                    -->

                    <div class="login-campo">


                        <label for="confirmar">

                            Confirmar nova senha

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
                                id="confirmar"
                                name="confirmar"
                                placeholder="Digite novamente sua senha"
                                autocomplete="new-password"
                                minlength="6"
                                maxlength="72"
                                required
                            >


                            <button
                                type="button"
                                class="ver-senha"
                                onclick="mostrarSenha('confirmar')"
                                aria-label="Mostrar ou ocultar confirmação da senha"
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
                    | AVISO DE SEGURANÇA
                    |--------------------------------------------------------------------------
                    -->

                    <div class="recuperacao-aviso recuperacao-aviso-formulario">


                        <span
                            class="recuperacao-aviso-icone"
                            aria-hidden="true"
                        >

                            i

                        </span>


                        <p>

                            Utilize uma senha difícil de descobrir e evite
                            reutilizar a mesma senha utilizada em outros
                            serviços.

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

                            Salvar nova senha

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

            Depois de salvar sua nova senha, utilize-a para entrar
            novamente na plataforma.

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
| JAVASCRIPT PARA MOSTRAR SENHA
|--------------------------------------------------------------------------
-->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/senha.js"
></script>


</body>

</html>