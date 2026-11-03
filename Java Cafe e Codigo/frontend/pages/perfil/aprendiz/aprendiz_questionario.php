<?php

/*
|--------------------------------------------------------------------------
| QUESTIONÁRIO DO APRENDIZ
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Esta página realiza a configuração inicial
| do perfil específico de Aprendiz.
|
| O usuário informa:
|
| - experiência atual com programação;
| - objetivo de aprendizagem, opcional.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../../backend/config/app.php";
require_once __DIR__ . "/../../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../../backend/config/helpers.php";

require_once __DIR__ . "/../../../../backend/Controller/PerfilController.php";


/*
|--------------------------------------------------------------------------
| SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| REDIRECIONAMENTO
|--------------------------------------------------------------------------
*/

function aprendizQuestionarioRedirecionar(
    string $nomeRota,
    int $codigo = 302
): never {

    header(
        "Location: " .
        rota(
            $nomeRota
        ),
        true,
        $codigo
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function aprendizQuestionarioEscapar(
    mixed $valor
): string {

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );

}


/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

$usuario =
    $_SESSION["usuario"]
    ??
    null;


if (
    !is_array(
        $usuario
    )
) {

    unset(
        $_SESSION["usuario"]
    );


    aprendizQuestionarioRedirecionar(
        "login"
    );

}


/*
|--------------------------------------------------------------------------
| ID DO USUÁRIO
|--------------------------------------------------------------------------
*/

$usuarioId =
    filter_var(
        $usuario["id"]
        ??
        null,
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 1
            ]
        ]
    );


if (
    $usuarioId === false
    ||
    $usuarioId === null
) {

    unset(
        $_SESSION["usuario"]
    );


    $_SESSION["erro"] =
        "Não foi possível identificar sua conta.";


    aprendizQuestionarioRedirecionar(
        "login"
    );

}


$usuarioId =
    (int) $usuarioId;


/*
|--------------------------------------------------------------------------
| BUSCA PERFIL
|--------------------------------------------------------------------------
*/

try {

    $perfilController =
        new PerfilController();


    $resultadoPerfil =
        $perfilController->buscarPerfil(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[AprendizQuestionario] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    aprendizQuestionarioRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA PERFIL PRINCIPAL
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $resultadoPerfil
    )
    ||
    empty(
        $resultadoPerfil["status"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]["usuario"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]["usuario"]
    )
) {

    $_SESSION["erro"] =
        "Não foi possível localizar seu perfil.";


    aprendizQuestionarioRedirecionar(
        "perfil.escolher"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIS
|--------------------------------------------------------------------------
*/

$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


$perfilAprendiz =
    $resultadoPerfil["perfil"]["aprendiz"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| GARANTE PERFIL APRENDIZ
|--------------------------------------------------------------------------
*/

$tipoUsuario =
    strtolower(
        trim(
            (string) (
                $perfilUsuario["tipo_usuario"]
                ??
                ""
            )
        )
    );


$tipoAprendiz =
    defined(
        "ROLE_APRENDIZ"
    )
        ?
        strtolower(
            trim(
                (string) ROLE_APRENDIZ
            )
        )
        :
        "aprendiz";


if (
    $tipoUsuario !==
    $tipoAprendiz
) {

    $_SESSION["erro"] =
        "Este formulário é exclusivo para o perfil Aprendiz.";


    aprendizQuestionarioRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| EVITA CADASTRO DUPLICADO
|--------------------------------------------------------------------------
|
| Se o perfil específico de Aprendiz já existe,
| não há motivo para abrir novamente o questionário inicial.
|
| Alterações futuras devem ser realizadas em:
|
| /aprendiz/perfil/editar
|
|--------------------------------------------------------------------------
*/

if (
    is_array(
        $perfilAprendiz
    )
    &&
    !empty(
        $perfilAprendiz
    )
) {

    aprendizQuestionarioRedirecionar(
        "aprendiz.painel"
    );

}


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["csrf"]
    )
    ||
    !is_array(
        $_SESSION["csrf"]
    )
) {

    $_SESSION["csrf"] =
        [];

}


/*
|--------------------------------------------------------------------------
| TOKEN DO QUESTIONÁRIO
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["csrf"]["aprendiz_questionario"]
    )
    ||
    !is_string(
        $_SESSION["csrf"]["aprendiz_questionario"]
    )
    ||
    preg_match(
        "/^[a-f0-9]{64}$/",
        $_SESSION["csrf"]["aprendiz_questionario"]
    )
    !== 1
) {

    $_SESSION["csrf"]["aprendiz_questionario"] =
        bin2hex(
            random_bytes(32)
        );

}


$csrfToken =
    $_SESSION["csrf"]["aprendiz_questionario"];


/*
|--------------------------------------------------------------------------
| AÇÃO
|--------------------------------------------------------------------------
*/

$linkSalvar =
    rota(
        "acao.aprendiz.salvar"
    );


/*
|--------------------------------------------------------------------------
| ASSETS
|--------------------------------------------------------------------------
*/

$caminhoStyle =
    __DIR__
    .
    "/../../../assets/css/style.css";


$caminhoPosLogin =
    __DIR__
    .
    "/../../../assets/css/poslogin.css";


$caminhoPerfil =
    __DIR__
    .
    "/../../../assets/css/perfil.css";


$caminhoEstudante =
    __DIR__
    .
    "/../../../assets/css/estudante.css";


$caminhoMensagem =
    __DIR__
    .
    "/../../../assets/css/mensagem.css";


$caminhoTheme =
    __DIR__
    .
    "/../../../assets/js/theme.js";


/*
|--------------------------------------------------------------------------
| VERSÕES DOS ASSETS
|--------------------------------------------------------------------------
*/

$versaoStyle =
    is_file(
        $caminhoStyle
    )
        ?
        (string) filemtime(
            $caminhoStyle
        )
        :
        "1";


$versaoPosLogin =
    is_file(
        $caminhoPosLogin
    )
        ?
        (string) filemtime(
            $caminhoPosLogin
        )
        :
        "1";


$versaoPerfil =
    is_file(
        $caminhoPerfil
    )
        ?
        (string) filemtime(
            $caminhoPerfil
        )
        :
        "1";


$versaoEstudante =
    is_file(
        $caminhoEstudante
    )
        ?
        (string) filemtime(
            $caminhoEstudante
        )
        :
        "1";


$versaoMensagem =
    is_file(
        $caminhoMensagem
    )
        ?
        (string) filemtime(
            $caminhoMensagem
        )
        :
        "1";


$versaoTheme =
    is_file(
        $caminhoTheme
    )
        ?
        (string) filemtime(
            $caminhoTheme
        )
        :
        "1";

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
    content="Complete o perfil de Aprendiz na plataforma Java, Café e Código."
>

<title>
    Perfil de Aprendiz | <?= aprendizQuestionarioEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= aprendizQuestionarioEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/style.css?v="
        .
        rawurlencode(
            $versaoStyle
        )
    ) ?>"
>


<!-- =====================================================
PÓS-LOGIN
====================================================== -->

<link
    rel="stylesheet"
    href="<?= aprendizQuestionarioEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/poslogin.css?v="
        .
        rawurlencode(
            $versaoPosLogin
        )
    ) ?>"
>


<!-- =====================================================
PERFIL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= aprendizQuestionarioEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/perfil.css?v="
        .
        rawurlencode(
            $versaoPerfil
        )
    ) ?>"
>


<!-- =====================================================
ESTUDANTE / APRENDIZ
====================================================== -->

<link
    rel="stylesheet"
    href="<?= aprendizQuestionarioEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/estudante.css?v="
        .
        rawurlencode(
            $versaoEstudante
        )
    ) ?>"
>


<!-- =====================================================
MENSAGENS
====================================================== -->

<link
    rel="stylesheet"
    href="<?= aprendizQuestionarioEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/mensagem.css?v="
        .
        rawurlencode(
            $versaoMensagem
        )
    ) ?>"
>

</head>


<body class="pagina-aprendiz pagina-questionario-aprendiz">


<?php

require_once __DIR__
    .
    "/../../../componentes/header.php";


require_once __DIR__
    .
    "/../../../componentes/mensagem.php";

?>


<main class="estudante-container">


<!-- =====================================================
CABEÇALHO
====================================================== -->

<section class="estudante-boas-vindas">


    <div>


        <span class="subtitulo">
            PERFIL DE APRENDIZ
        </span>


        <h1>
            Conte um pouco sobre sua experiência
        </h1>


        <p>

            Essas informações ajudam a identificar
            seu nível atual de experiência e registrar
            o objetivo que deseja alcançar durante
            sua jornada de aprendizagem.

        </p>


    </div>


</section>


<!-- =====================================================
FORMULÁRIO
====================================================== -->

<section class="estudante-recursos">


    <h2>
        Complete seu perfil
    </h2>


    <p class="descricao-secao">

        Informe sua experiência com programação.
        O objetivo de aprendizagem é opcional.

    </p>


    <form
        method="POST"
        action="<?= aprendizQuestionarioEscapar(
            $linkSalvar
        ) ?>"
        class="perfil-formulario"
    >


        <!-- =================================================
        CSRF
        ================================================== -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= aprendizQuestionarioEscapar(
                $csrfToken
            ) ?>"
        >


        <!-- =================================================
        EXPERIÊNCIA
        ================================================== -->

        <div class="campo-perfil">


            <label for="experiencia">
                Qual é sua experiência com programação?
            </label>


            <select
                name="experiencia"
                id="experiencia"
                required
            >


                <option value="">
                    Selecione uma opção
                </option>


                <option value="nenhuma">
                    Nunca estudei programação
                </option>


                <option value="basica">
                    Conheço alguns conceitos básicos
                </option>


                <option value="intermediaria">
                    Já desenvolvi pequenos programas
                </option>


                <option value="avancada">
                    Já tenho bastante experiência
                </option>


            </select>


        </div>


        <!-- =================================================
        OBJETIVO
        ================================================== -->

        <div class="campo-perfil">


            <label for="objetivo">

                O que você deseja aprender ou desenvolver?

                <span>
                    (opcional)
                </span>

            </label>


            <textarea
                name="objetivo"
                id="objetivo"
                rows="6"
                maxlength="500"
                placeholder="Opcional: conte quais são seus objetivos com a programação."
            ></textarea>


            <small>
                Este campo é opcional. Limite de 500 caracteres.
            </small>


        </div>


        <!-- =================================================
        BOTÃO
        ================================================== -->

        <button
            type="submit"
            class="botao"
        >

            Finalizar meu perfil

        </button>


    </form>


</section>


</main>


<?php

require_once __DIR__
    .
    "/../../../componentes/footer.php";

?>


<script
    src="<?= aprendizQuestionarioEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/js/theme.js?v="
        .
        rawurlencode(
            $versaoTheme
        )
    ) ?>"
></script>


</body>

</html>