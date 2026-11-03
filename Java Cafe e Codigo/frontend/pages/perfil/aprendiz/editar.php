<?php

/*
|--------------------------------------------------------------------------
| EDITAR PERFIL DO APRENDIZ
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Esta página permite atualizar:
|
| PERFIL DE APRENDIZAGEM
|
| - experiência;
| - objetivo de aprendizagem.
|
| PERFIL PÚBLICO
|
| - nome;
| - nick;
| - Instagram;
| - e-mail de contato;
| - biografia.
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

function editarAprendizRedirecionar(
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

function editarAprendizEscapar(
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


    editarAprendizRedirecionar(
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


    editarAprendizRedirecionar(
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
        "[EditarAprendiz] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    editarAprendizRedirecionar(
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


    editarAprendizRedirecionar(
        "perfil.escolher"
    );

}


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
        "Esta área é exclusiva para o perfil Aprendiz.";


    editarAprendizRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL ESPECÍFICO
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $perfilAprendiz
    )
    ||
    empty(
        $perfilAprendiz
    )
) {

    $_SESSION["erro"] =
        "Complete seu perfil de aprendiz.";


    editarAprendizRedirecionar(
        "aprendiz.questionario"
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
        "Aprendiz";

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
| PERFIL PÚBLICO
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
| PERFIL DE APRENDIZAGEM
|--------------------------------------------------------------------------
*/

$experiencia =
    strtolower(
        trim(
            (string) (
                $perfilAprendiz["experiencia"]
                ??
                "nenhuma"
            )
        )
    );


$experienciasPermitidas = [
    "nenhuma",
    "basica",
    "intermediaria",
    "avancada"
];


if (
    !in_array(
        $experiencia,
        $experienciasPermitidas,
        true
    )
) {

    $experiencia =
        "nenhuma";

}


$objetivo =
    trim(
        (string) (
            $perfilAprendiz["objetivo"]
            ??
            ""
        )
    );


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
|
| Os dois formulários pertencem à mesma página de edição.
|
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
        $_SESSION["csrf"]["aprendiz_editar"]
    )
    ||
    !is_string(
        $_SESSION["csrf"]["aprendiz_editar"]
    )
    ||
    preg_match(
        "/^[a-f0-9]{64}$/",
        $_SESSION["csrf"]["aprendiz_editar"]
    )
    !== 1
) {

    $_SESSION["csrf"]["aprendiz_editar"] =
        bin2hex(
            random_bytes(32)
        );

}


$csrfToken =
    $_SESSION["csrf"]["aprendiz_editar"];


/*
|--------------------------------------------------------------------------
| LINKS
|--------------------------------------------------------------------------
*/

$linkPerfil =
    rota(
        "aprendiz.perfil"
    );


$linkPainel =
    rota(
        "aprendiz.painel"
    );


/*
|--------------------------------------------------------------------------
| AÇÕES
|--------------------------------------------------------------------------
*/

$linkAtualizarAprendiz =
    rota(
        "acao.aprendiz.atualizar"
    );


$linkAtualizarPerfilPublico =
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
    content="Editar perfil do Aprendiz na plataforma Java, Café e Código."
>

<title>
    Editar Perfil | <?= editarAprendizEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= editarAprendizEscapar(
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
    href="<?= editarAprendizEscapar(
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
    href="<?= editarAprendizEscapar(
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
    href="<?= editarAprendizEscapar(
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


<body class="pagina-perfil pagina-editar-perfil pagina-editar-aprendiz">


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
            PERFIL DO APRENDIZ
        </span>


        <h1>
            Editar informações
        </h1>


        <p>

            Atualize suas informações públicas
            e os dados relacionados à sua
            experiência de aprendizagem.

        </p>


    </div>


    <a
        href="<?= editarAprendizEscapar(
            $linkPerfil
        ) ?>"
        class="editar-perfil-voltar"
    >

        ← Voltar ao perfil

    </a>


</section>


<!-- =====================================================
PERFIL DE APRENDIZAGEM
====================================================== -->

<section class="editar-perfil-card">


    <div class="editar-perfil-card-cabecalho">


        <span class="editar-perfil-etiqueta">
            APRENDIZAGEM
        </span>


        <h2>
            Perfil de aprendizagem
        </h2>


        <p>

            Atualize seu nível atual de experiência
            com programação e o objetivo que deseja
            alcançar utilizando a plataforma.

        </p>


    </div>


    <form
        action="<?= editarAprendizEscapar(
            $linkAtualizarAprendiz
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
            value="<?= editarAprendizEscapar(
                $csrfToken
            ) ?>"
        >


        <div class="editar-perfil-grid">


            <!-- =================================================
            EXPERIÊNCIA
            ================================================== -->

            <div class="editar-perfil-campo">


                <label for="experiencia">
                    Experiência com programação
                </label>


                <select
                    name="experiencia"
                    id="experiencia"
                    required
                >


                    <option
                        value="nenhuma"
                        <?= $experiencia === "nenhuma"
                            ?
                            "selected"
                            :
                            ""
                        ?>
                    >

                        Nunca estudei programação

                    </option>


                    <option
                        value="basica"
                        <?= $experiencia === "basica"
                            ?
                            "selected"
                            :
                            ""
                        ?>
                    >

                        Conheço alguns conceitos básicos

                    </option>


                    <option
                        value="intermediaria"
                        <?= $experiencia === "intermediaria"
                            ?
                            "selected"
                            :
                            ""
                        ?>
                    >

                        Já desenvolvi pequenos programas

                    </option>


                    <option
                        value="avancada"
                        <?= $experiencia === "avancada"
                            ?
                            "selected"
                            :
                            ""
                        ?>
                    >

                        Já tenho bastante experiência

                    </option>


                </select>


                <small>

                    Selecione a opção que melhor
                    representa seu conhecimento atual.

                </small>


            </div>


            <!-- =================================================
            OBJETIVO
            ================================================== -->

            <div class="editar-perfil-campo editar-perfil-campo-largo">


                <label for="objetivo">
                    Objetivo de aprendizagem
                </label>


                <textarea
                    name="objetivo"
                    id="objetivo"
                    maxlength="500"
                    placeholder="Ex.: Quero compreender Java, desenvolver projetos e melhorar minha lógica de programação."
                ><?= editarAprendizEscapar(
                    $objetivo
                ) ?></textarea>


                <div class="editar-perfil-campo-rodape">


                    <small>
                        Campo opcional.
                    </small>


                    <small>
                        Máximo de 500 caracteres.
                    </small>


                </div>


            </div>


        </div>


        <!-- =====================================================
        AVISO
        ====================================================== -->

        <div class="editar-perfil-aviso">


            <strong>
                Sobre essas informações
            </strong>


            <p>

                Seu nível de experiência pode ser atualizado
                conforme você desenvolve novos conhecimentos.
                O objetivo também pode ser alterado sempre
                que sua jornada de aprendizagem mudar.

            </p>


        </div>


        <!-- =====================================================
        AÇÕES
        ====================================================== -->

        <div class="editar-perfil-acoes">


            <a
                href="<?= editarAprendizEscapar(
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

                Salvar aprendizagem

            </button>


        </div>


    </form>


</section>


<!-- =====================================================
PERFIL PÚBLICO
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

            Essas informações poderão identificar
            você nos espaços colaborativos da plataforma,
            principalmente na Sala Global.

        </p>


    </div>


    <form
        action="<?= editarAprendizEscapar(
            $linkAtualizarPerfilPublico
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
            value="<?= editarAprendizEscapar(
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
                        editarAprendizEscapar(
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


            <!-- =================================================
            NOME
            ================================================== -->

            <div class="editar-perfil-campo">


                <label for="nome">
                    Nome
                </label>


                <input
                    type="text"
                    name="nome"
                    id="nome"
                    value="<?= editarAprendizEscapar(
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


            <!-- =================================================
            NICK
            ================================================== -->

            <div class="editar-perfil-campo">


                <label for="nick_programador">
                    Nick de programador
                </label>


                <input
                    type="text"
                    name="nick_programador"
                    id="nick_programador"
                    value="<?= editarAprendizEscapar(
                        $nick
                    ) ?>"
                    placeholder="Ex.: javadev"
                    maxlength="50"
                    autocomplete="off"
                >


                <small>

                    Nome curto utilizado para
                    identificar você na comunidade.

                </small>


            </div>


            <!-- =================================================
            INSTAGRAM
            ================================================== -->

            <div class="editar-perfil-campo">


                <label for="instagram">
                    Instagram
                </label>


                <input
                    type="text"
                    name="instagram"
                    id="instagram"
                    value="<?= editarAprendizEscapar(
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


            <!-- =================================================
            E-MAIL PARA CONTATO
            ================================================== -->

            <div class="editar-perfil-campo">


                <label for="email_contato">
                    E-mail para contato
                </label>


                <input
                    type="email"
                    name="email_contato"
                    id="email_contato"
                    value="<?= editarAprendizEscapar(
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


            <!-- =================================================
            BIOGRAFIA
            ================================================== -->

            <div class="editar-perfil-campo editar-perfil-campo-largo">


                <label for="biografia">
                    Biografia
                </label>


                <textarea
                    name="biografia"
                    id="biografia"
                    maxlength="300"
                    placeholder="Conte um pouco sobre você, seus interesses em programação e o que está aprendendo..."
                ><?= editarAprendizEscapar(
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
                href="<?= editarAprendizEscapar(
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

                Salvar informações

            </button>


        </div>


    </form>


</section>


<!-- =====================================================
NAVEGAÇÃO
====================================================== -->

<section class="editar-perfil-card">


    <div class="editar-perfil-card-cabecalho">


        <span class="editar-perfil-etiqueta">
            NAVEGAÇÃO
        </span>


        <h2>
            Continuar aprendendo
        </h2>


        <p>

            Retorne ao painel do Aprendiz para acessar
            suas aulas, conteúdos e ferramentas.

        </p>


    </div>


    <div class="editar-perfil-acoes">


        <a
            href="<?= editarAprendizEscapar(
                $linkPainel
            ) ?>"
            class="editar-perfil-cancelar"
        >

            Ir para o painel

        </a>


    </div>


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
    src="<?= editarAprendizEscapar(
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