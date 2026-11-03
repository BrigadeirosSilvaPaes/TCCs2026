<?php

/*
|--------------------------------------------------------------------------
| EDITAR PERFIL DO ESTUDANTE
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Esta página permite atualizar as informações públicas
| utilizadas para apresentar o perfil do Estudante.
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

function editarEstudanteRedirecionar(
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

function editarPerfilEscapar(
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


    editarEstudanteRedirecionar(
        "login"
    );

}


/*
|--------------------------------------------------------------------------
| ID
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


    editarEstudanteRedirecionar(
        "login"
    );

}


$usuarioId =
    (int) $usuarioId;


/*
|--------------------------------------------------------------------------
| DADOS DA SESSÃO
|--------------------------------------------------------------------------
*/

$nome =
    trim(
        (string) (
            $usuario["nome"]
            ??
            ""
        )
    );


$emailConta =
    trim(
        (string) (
            $usuario["email"]
            ??
            ""
        )
    );


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
        "[EditarPerfilEstudante] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    editarEstudanteRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA PERFIL
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
        "Não foi possível carregar seu perfil.";


    editarEstudanteRedirecionar(
        "painel"
    );

}


$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


/*
|--------------------------------------------------------------------------
| GARANTE PERFIL ESTUDANTE
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


$tipoEstudante =
    defined(
        "ROLE_ESTUDANTE"
    )
        ?
        strtolower(
            trim(
                (string) ROLE_ESTUDANTE
            )
        )
        :
        "estudante";


if (
    $tipoUsuario !==
    $tipoEstudante
) {

    $_SESSION["erro"] =
        "Esta área é exclusiva para o perfil Estudante.";


    editarEstudanteRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| NOME MAIS ATUAL
|--------------------------------------------------------------------------
*/

$nomePerfil =
    trim(
        (string) (
            $perfilUsuario["nome"]
            ??
            ""
        )
    );


if (
    $nomePerfil !== ""
) {

    $nome =
        $nomePerfil;

}


if (
    $nome === ""
) {

    $nome =
        "Estudante";

}


/*
|--------------------------------------------------------------------------
| E-MAIL DA CONTA
|--------------------------------------------------------------------------
*/

if (
    $emailConta === ""
) {

    $emailConta =
        trim(
            (string) (
                $perfilUsuario["email"]
                ??
                ""
            )
        );

}


/*
|--------------------------------------------------------------------------
| DADOS EDITÁVEIS
|--------------------------------------------------------------------------
*/

$nick =
    trim(
        (string) (
            $perfilUsuario["nick_programador"]
            ??
            ""
        )
    );


$nick =
    ltrim(
        $nick,
        "@"
    );


$biografia =
    trim(
        (string) (
            $perfilUsuario["biografia"]
            ??
            ""
        )
    );


$instagram =
    trim(
        (string) (
            $perfilUsuario["instagram"]
            ??
            ""
        )
    );


$instagram =
    ltrim(
        $instagram,
        "@"
    );


$emailContato =
    trim(
        (string) (
            $perfilUsuario["email_contato"]
            ??
            ""
        )
    );


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


if (
    !isset(
        $_SESSION["csrf"]["estudante_editar"]
    )
    ||
    !is_string(
        $_SESSION["csrf"]["estudante_editar"]
    )
    ||
    preg_match(
        "/^[a-f0-9]{64}$/",
        $_SESSION["csrf"]["estudante_editar"]
    )
    !== 1
) {

    $_SESSION["csrf"]["estudante_editar"] =
        bin2hex(
            random_bytes(32)
        );

}


$csrfToken =
    $_SESSION["csrf"]["estudante_editar"];


/*
|--------------------------------------------------------------------------
| ROTAS
|--------------------------------------------------------------------------
*/

$linkPerfil =
    rota(
        "estudante.perfil"
    );


$linkSalvarPerfil =
    rota(
        "acao.perfil_publico.atualizar"
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


$caminhoPerfil =
    __DIR__
    .
    "/../../../assets/css/perfil.css";


$caminhoMensagem =
    __DIR__
    .
    "/../../../assets/css/mensagem.css";


$caminhoEditar =
    __DIR__
    .
    "/../../../assets/css/editar_perfil.css";


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


$cssEditarExiste =
    is_file(
        $caminhoEditar
    );


$versaoEditar =
    $cssEditarExiste
        ?
        (string) filemtime(
            $caminhoEditar
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
    content="Editar perfil de Estudante na plataforma Java, Café e Código."
>

<title>
    Editar Perfil | <?= editarPerfilEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= editarPerfilEscapar(
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
    href="<?= editarPerfilEscapar(
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
    href="<?= editarPerfilEscapar(
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


<!-- =====================================================
EDITAR PERFIL
====================================================== -->

<?php if (
    $cssEditarExiste
): ?>

<link
    rel="stylesheet"
    href="<?= editarPerfilEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/editar_perfil.css?v="
        .
        rawurlencode(
            $versaoEditar
        )
    ) ?>"
>

<?php endif; ?>

</head>


<body class="pagina-perfil pagina-editar-perfil pagina-editar-estudante">


<?php

require_once __DIR__
    .
    "/../../../componentes/header.php";


require_once __DIR__
    .
    "/../../../componentes/mensagem.php";

?>


<main class="editar-perfil-container">


<!-- =====================================================
CABEÇALHO
====================================================== -->

<section class="editar-perfil-cabecalho">


    <div>


        <span class="editar-perfil-etiqueta">
            PERFIL DO ESTUDANTE
        </span>


        <h1>
            Editar informações
        </h1>


        <p>

            Personalize as informações utilizadas
            para apresentar seu perfil dentro da plataforma.

        </p>


    </div>


    <a
        href="<?= editarPerfilEscapar(
            $linkPerfil
        ) ?>"
        class="editar-perfil-voltar"
    >

        ← Voltar ao perfil

    </a>


</section>


<!-- =====================================================
FORMULÁRIO
====================================================== -->

<section class="editar-perfil-card">


    <div class="editar-perfil-card-cabecalho">


        <span class="editar-perfil-etiqueta">
            INFORMAÇÕES
        </span>


        <h2>
            Perfil público
        </h2>


        <p>

            Essas informações poderão identificar você
            nos espaços colaborativos da plataforma.

        </p>


    </div>


    <form
        action="<?= editarPerfilEscapar(
            $linkSalvarPerfil
        ) ?>"
        method="POST"
        class="editar-perfil-formulario"
    >


        <!-- =================================================
        CSRF
        ================================================== -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= editarPerfilEscapar(
                $csrfToken
            ) ?>"
        >


        <!-- =====================================================
        E-MAIL DA CONTA
        ====================================================== -->

        <div class="editar-perfil-conta">


            <div>


                <small>
                    E-MAIL DA CONTA
                </small>


                <strong>

                    <?= $emailConta !== ""
                        ?
                        editarPerfilEscapar(
                            $emailConta
                        )
                        :
                        "Não informado"
                    ?>

                </strong>


            </div>


            <span>
                Usado para entrar na plataforma
            </span>


        </div>


        <!-- =====================================================
        CAMPOS
        ====================================================== -->

        <div class="editar-perfil-grid">


            <!-- NOME -->

            <div class="editar-perfil-campo">


                <label for="nome">
                    Nome
                </label>


                <input
                    type="text"
                    name="nome"
                    id="nome"
                    value="<?= editarPerfilEscapar(
                        $nome
                    ) ?>"
                    minlength="3"
                    maxlength="100"
                    autocomplete="name"
                    required
                >


                <small>
                    Nome exibido no seu perfil.
                </small>


            </div>


            <!-- NICK -->

            <div class="editar-perfil-campo">


                <label for="nick_programador">
                    Nick de programador
                </label>


                <input
                    type="text"
                    name="nick_programador"
                    id="nick_programador"
                    value="<?= editarPerfilEscapar(
                        $nick
                    ) ?>"
                    placeholder="Ex.: javadev"
                    maxlength="50"
                    autocomplete="off"
                >


                <small>

                    Nome curto utilizado para identificar
                    você na comunidade.

                </small>


            </div>


            <!-- INSTAGRAM -->

            <div class="editar-perfil-campo">


                <label for="instagram">
                    Instagram
                </label>


                <input
                    type="text"
                    name="instagram"
                    id="instagram"
                    value="<?= editarPerfilEscapar(
                        $instagram
                    ) ?>"
                    placeholder="@seuusuario"
                    maxlength="30"
                    autocomplete="off"
                >


                <small>
                    Opcional. Informe apenas o nome de usuário.
                </small>


            </div>


            <!-- E-MAIL PARA CONTATO -->

            <div class="editar-perfil-campo">


                <label for="email_contato">
                    E-mail para contato
                </label>


                <input
                    type="email"
                    name="email_contato"
                    id="email_contato"
                    value="<?= editarPerfilEscapar(
                        $emailContato
                    ) ?>"
                    placeholder="contato@exemplo.com"
                    maxlength="150"
                    autocomplete="email"
                >


                <small>

                    Pode ser diferente do e-mail
                    utilizado para entrar na conta.

                </small>


            </div>


            <!-- BIOGRAFIA -->

            <div class="editar-perfil-campo editar-perfil-campo-largo">


                <label for="biografia">
                    Biografia
                </label>


                <textarea
                    name="biografia"
                    id="biografia"
                    maxlength="300"
                    placeholder="Conte um pouco sobre você, seus estudos, interesses em programação ou tecnologias que gosta..."
                ><?= editarPerfilEscapar(
                    $biografia
                ) ?></textarea>


                <div class="editar-perfil-campo-rodape">


                    <small>
                        Uma breve apresentação sobre você.
                    </small>


                    <small>
                        Máximo de 300 caracteres.
                    </small>


                </div>


            </div>


        </div>


        <!-- =====================================================
        AVISO
        ====================================================== -->

        <div class="editar-perfil-aviso">


            <strong>
                Sobre o e-mail da conta
            </strong>


            <p>

                O e-mail utilizado para login não é alterado
                nesta página. O campo de contato é opcional
                e serve apenas para apresentação no perfil.

            </p>


        </div>


        <!-- =====================================================
        AÇÕES
        ====================================================== -->

        <div class="editar-perfil-acoes">


            <a
                href="<?= editarPerfilEscapar(
                    $linkPerfil
                ) ?>"
                class="editar-perfil-cancelar"
            >

                Cancelar

            </a>


            <button
                type="submit"
                class="editar-perfil-salvar"
            >

                Salvar alterações

            </button>


        </div>


    </form>


</section>



<?php

require_once __DIR__
    .
    "/../../../componentes/configuracao_tema.php";

?>
</main>


<?php

require_once __DIR__
    .
    "/../../../componentes/footer.php";

?>


<script
    src="<?= editarPerfilEscapar(
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