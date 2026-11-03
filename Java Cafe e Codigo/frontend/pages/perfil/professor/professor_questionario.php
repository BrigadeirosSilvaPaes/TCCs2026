<?php

/*
|--------------------------------------------------------------------------
| COMPLETAR PERFIL DO PROFESSOR
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Esta página é utilizada somente na primeira configuração
| do perfil profissional do Professor.
|
| O usuário informa:
|
| - instituição de ensino;
| - disciplina ou área de atuação.
|
| Após o envio, o cadastro profissional ficará aguardando
| análise administrativa para liberação das Salas de Aula.
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
| USUÁRIO
|--------------------------------------------------------------------------
*/

$usuario =
    $_SESSION["usuario"]
    ?? null;


if (
    !is_array(
        $usuario
    )
) {

    unset(
        $_SESSION["usuario"]
    );


    header(
        "Location: " .
        rota(
            "login"
        )
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| ID
|--------------------------------------------------------------------------
*/

$usuarioId =
    filter_var(
        $usuario["id"]
        ?? null,
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


    header(
        "Location: " .
        rota(
            "login"
        )
    );


    exit;

}


$usuarioId =
    (int) $usuarioId;


/*
|--------------------------------------------------------------------------
| PERFIL
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
        "[QuestionarioProfessor] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    header(
        "Location: " .
        rota(
            "painel"
        )
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDA RETORNO
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
        $resultadoPerfil["perfil"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]
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


    header(
        "Location: " .
        rota(
            "perfil.escolher"
        )
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| PERFIS
|--------------------------------------------------------------------------
*/

$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


$perfilProfessor =
    $resultadoPerfil["perfil"]["professor"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| GARANTE PERFIL PROFESSOR
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


$roleProfessor =
    defined(
        "ROLE_PROFESSOR"
    )
        ?
        strtolower(
            trim(
                (string) ROLE_PROFESSOR
            )
        )
        :
        "professor";


if (
    $tipoUsuario !==
    $roleProfessor
) {

    $_SESSION["erro"] =
        "Este formulário é exclusivo para o perfil Professor.";


    header(
        "Location: " .
        rota(
            "painel"
        )
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| PERFIL PROFISSIONAL JÁ EXISTE
|--------------------------------------------------------------------------
|
| O questionário serve apenas para a criação inicial.
|
| Alterações posteriores são feitas em:
|
| /professor/perfil/editar
|
|--------------------------------------------------------------------------
*/

if (
    is_array(
        $perfilProfessor
    )
    &&
    !empty(
        $perfilProfessor
    )
) {

    header(
        "Location: " .
        rota(
            "professor.painel"
        )
    );


    exit;

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


$csrfToken =
    $_SESSION["csrf"]["professor_questionario"]
    ??
    "";


if (
    !is_string(
        $csrfToken
    )
    ||
    preg_match(
        "/^[a-f0-9]{64}$/",
        $csrfToken
    )
    !== 1
) {

    try {

        $csrfToken =
            bin2hex(
                random_bytes(
                    32
                )
            );

    }
    catch (
        Throwable $erro
    ) {

        error_log(
            "[QuestionarioProfessor] Erro ao gerar CSRF para o usuário "
            .
            $usuarioId
            .
            ": "
            .
            $erro->getMessage()
        );


        $_SESSION["erro"] =
            "Não foi possível preparar o formulário.";


        header(
            "Location: " .
            rota(
                "painel"
            )
        );


        exit;

    }


    $_SESSION["csrf"]["professor_questionario"] =
        $csrfToken;

}


/*
|--------------------------------------------------------------------------
| AÇÃO
|--------------------------------------------------------------------------
*/

$acaoSalvarProfessor =
    rota(
        "acao.professor.salvar"
    );


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function questionarioProfessorEscapar(
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
| ASSETS
|--------------------------------------------------------------------------
*/

$caminhoStyle =
    __DIR__
    .
    "/../../../assets/css/style.css";


$caminhoPerfil =
    __DIR__
    .
    "/../../../assets/css/perfil.css";


$caminhoMensagem =
    __DIR__
    .
    "/../../../assets/css/mensagem.css";


$caminhoTheme =
    __DIR__
    .
    "/../../../assets/js/theme.js";


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
    content="Complete seu perfil de professor na plataforma Java, Café e Código."
>

<title>
    Completar Perfil do Professor | <?= questionarioProfessorEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= questionarioProfessorEscapar(
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
PERFIL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= questionarioProfessorEscapar(
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
MENSAGENS
====================================================== -->

<link
    rel="stylesheet"
    href="<?= questionarioProfessorEscapar(
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


<body class="pagina-perfil pagina-questionario-professor">


<?php

require_once __DIR__
    .
    "/../../../componentes/header.php";


require_once __DIR__
    .
    "/../../../componentes/mensagem.php";

?>


<main class="perfil-container">


<section class="perfil-card">


    <!-- =================================================
    CABEÇALHO
    ================================================== -->

    <span class="perfil-etiqueta">
        PERFIL DO PROFESSOR
    </span>


    <h1>
        Complete seu perfil de professor
    </h1>


    <p class="perfil-descricao">

        Informe sua instituição de ensino
        e sua principal disciplina ou área de atuação.

    </p>


    <!-- =================================================
    INFORMAÇÃO
    ================================================== -->

    <div class="perfil-objetivo">


        <p>

            Esses dados serão utilizados para identificar
            seu perfil profissional dentro da plataforma.

            Após o envio, seu cadastro ficará aguardando
            análise administrativa.

        </p>


        <p>

            Enquanto a análise estiver em andamento,
            você poderá utilizar normalmente os recursos
            gerais da plataforma.

            A aprovação será necessária apenas para
            criar e gerenciar Salas de Aula.

        </p>


    </div>


    <!-- =================================================
    FORMULÁRIO
    ================================================== -->

    <form
        method="POST"
        action="<?= questionarioProfessorEscapar(
            $acaoSalvarProfessor
        ) ?>"
        accept-charset="UTF-8"
    >


        <input
            type="hidden"
            name="csrf_token"
            value="<?= questionarioProfessorEscapar(
                $csrfToken
            ) ?>"
        >


        <!-- =================================================
        INSTITUIÇÃO
        ================================================== -->

        <div class="campo-perfil">


            <label for="instituicao">

                Instituição de ensino

            </label>


            <input
                type="text"
                name="instituicao"
                id="instituicao"
                minlength="2"
                maxlength="150"
                placeholder="Ex.: Escola Brigadeiro José da Silva Paes"
                autocomplete="organization"
                required
            >


            <small>

                Informe a instituição relacionada
                à sua atuação profissional.

            </small>


        </div>


        <!-- =================================================
        DISCIPLINA
        ================================================== -->

        <div class="campo-perfil">


            <label for="disciplina">

                Disciplina ou área de atuação

            </label>


            <input
                type="text"
                name="disciplina"
                id="disciplina"
                minlength="2"
                maxlength="100"
                placeholder="Ex.: Informática, Java ou Programação"
                autocomplete="off"
                required
            >


            <small>

                Informe sua principal disciplina,
                área ou campo de atuação.

            </small>


        </div>


        <!-- =================================================
        ENVIO
        ================================================== -->

        <button
            type="submit"
            class="botao"
        >

            Enviar cadastro profissional

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
    src="<?= questionarioProfessorEscapar(
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