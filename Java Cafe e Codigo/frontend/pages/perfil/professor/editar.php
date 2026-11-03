<?php

/*
|--------------------------------------------------------------------------
| EDITAR PERFIL DO PROFESSOR
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Permite ao Professor atualizar:
|
| 1. informações profissionais;
| 2. informações públicas do perfil.
|
| O e-mail utilizado para autenticação não é alterado aqui.
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
| DADOS DA CONTA
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
        "[EditarProfessor] Erro ao carregar perfil do usuário "
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
            "professor.perfil"
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
        "Esta área é exclusiva para o perfil Professor.";


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
| PERFIL PROFISSIONAL
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $perfilProfessor
    )
    ||
    empty(
        $perfilProfessor
    )
) {

    $_SESSION["erro"] =
        "Complete seu perfil de professor.";


    header(
        "Location: " .
        rota(
            "professor.questionario"
        )
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| DADOS ATUALIZADOS DO PERFIL
|--------------------------------------------------------------------------
|
| Preferimos os dados retornados pelo banco quando disponíveis.
|
|--------------------------------------------------------------------------
*/

if (
    isset(
        $perfilUsuario["nome"]
    )
) {

    $nomeBanco =
        trim(
            (string) $perfilUsuario["nome"]
        );


    if (
        $nomeBanco !== ""
    ) {

        $nome =
            $nomeBanco;

    }

}


if (
    $emailConta === ""
    &&
    isset(
        $perfilUsuario["email"]
    )
) {

    $emailConta =
        trim(
            (string) $perfilUsuario["email"]
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
| DADOS PROFISSIONAIS
|--------------------------------------------------------------------------
*/

$instituicao =
    trim(
        (string) (
            $perfilProfessor["instituicao"]
            ??
            ""
        )
    );


$disciplina =
    trim(
        (string) (
            $perfilProfessor["disciplina"]
            ??
            ""
        )
    );


$professorAprovado =
    filter_var(
        $perfilProfessor["aprovado"]
        ??
        false,
        FILTER_VALIDATE_BOOLEAN
    );


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
|
| Os dois formulários alteram dados.
|
| Geramos um token específico para esta página e armazenamos
| exclusivamente na sessão do usuário.
|
| Os endpoints deverão validar esse token antes de alterar o banco.
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


$csrfToken =
    $_SESSION["csrf"]["professor_editar"]
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
            "[EditarProfessor] Erro ao gerar CSRF para o usuário "
            .
            $usuarioId
            .
            ": "
            .
            $erro->getMessage()
        );


        $_SESSION["erro"] =
            "Não foi possível preparar a edição do perfil.";


        header(
            "Location: " .
            rota(
                "professor.perfil"
            )
        );


        exit;

    }


    $_SESSION["csrf"]["professor_editar"] =
        $csrfToken;

}


/*
|--------------------------------------------------------------------------
| LINKS
|--------------------------------------------------------------------------
*/

$linkPerfil =
    rota(
        "professor.perfil"
    );


$linkPainel =
    rota(
        "professor.painel"
    );


/*
|--------------------------------------------------------------------------
| AÇÃO - ATUALIZAR PROFESSOR
|--------------------------------------------------------------------------
|
| A rota ainda pode não existir no catálogo atual.
|
| Quando criarmos:
|
| acao.professor.atualizar
|
| esta página passará automaticamente a utilizá-la.
|
|--------------------------------------------------------------------------
*/

if (
    rotaExiste(
        "acao.professor.atualizar"
    )
) {

    $acaoAtualizarProfessor =
        rota(
            "acao.professor.atualizar"
        );

}
else {

    $acaoAtualizarProfessor =
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/backend/perfil/atualizar_professor.php";

}


/*
|--------------------------------------------------------------------------
| AÇÃO - PERFIL PÚBLICO
|--------------------------------------------------------------------------
*/

if (
    rotaExiste(
        "acao.perfil_publico.atualizar"
    )
) {

    $acaoAtualizarPerfilPublico =
        rota(
            "acao.perfil_publico.atualizar"
        );

}
else {

    $acaoAtualizarPerfilPublico =
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/backend/perfil/atualizar_perfil_publico.php";

}


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function editarProfessorEscapar(
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


$caminhoCssEditar =
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


$versaoEditar =
    is_file(
        $caminhoCssEditar
    )
        ?
        (string) filemtime(
            $caminhoCssEditar
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
    content="Editar perfil de professor na plataforma Java, Café e Código."
>

<title>
    Editar Perfil | <?= editarProfessorEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= editarProfessorEscapar(
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
    href="<?= editarProfessorEscapar(
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
    href="<?= editarProfessorEscapar(
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
    is_file(
        $caminhoCssEditar
    )
): ?>

<link
    rel="stylesheet"
    href="<?= editarProfessorEscapar(
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


<body class="pagina-perfil pagina-editar-perfil pagina-editar-professor">


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
            PERFIL DO PROFESSOR
        </span>


        <h1>
            Editar informações
        </h1>


        <p>

            Atualize suas informações públicas
            e os dados relacionados à sua atuação
            profissional.

        </p>


    </div>


    <a
        href="<?= editarProfessorEscapar(
            $linkPerfil
        ) ?>"
        class="editar-perfil-voltar"
    >

        ← Voltar ao perfil

    </a>


</section>


<!-- =====================================================
INFORMAÇÕES PROFISSIONAIS
====================================================== -->

<section class="editar-perfil-card">


    <div class="editar-perfil-card-cabecalho">


        <span class="editar-perfil-etiqueta">
            PROFESSOR
        </span>


        <h2>
            Informações profissionais
        </h2>


        <p>

            Atualize sua instituição de ensino
            e sua principal disciplina ou área
            de atuação.

        </p>


    </div>


    <form
        action="<?= editarProfessorEscapar(
            $acaoAtualizarProfessor
        ) ?>"
        method="POST"
        accept-charset="UTF-8"
        class="editar-perfil-formulario"
    >


        <input
            type="hidden"
            name="csrf_token"
            value="<?= editarProfessorEscapar(
                $csrfToken
            ) ?>"
        >


        <!-- =================================================
        SITUAÇÃO ATUAL
        ================================================== -->

        <div class="editar-perfil-conta">


            <div>


                <small>
                    SITUAÇÃO DO CADASTRO
                </small>


                <strong>

                    <?= $professorAprovado
                        ?
                        "Aprovado pela administração"
                        :
                        "Aguardando análise da administração"
                    ?>

                </strong>


            </div>


            <span>

                <?= $professorAprovado
                    ?
                    "Salas de Aula liberadas"
                    :
                    "Salas de Aula aguardando liberação"
                ?>

            </span>


        </div>


        <!-- =================================================
        CAMPOS PROFISSIONAIS
        ================================================== -->

        <div class="editar-perfil-grid">


            <div class="editar-perfil-campo">


                <label for="instituicao">
                    Instituição de ensino
                </label>


                <input
                    type="text"
                    name="instituicao"
                    id="instituicao"
                    value="<?= editarProfessorEscapar(
                        $instituicao
                    ) ?>"
                    placeholder="Ex.: Escola Brigadeiro José da Silva Paes"
                    minlength="2"
                    maxlength="150"
                    autocomplete="organization"
                    required
                >


                <small>

                    Instituição de ensino
                    relacionada à sua atuação.

                </small>


            </div>


            <div class="editar-perfil-campo">


                <label for="disciplina">
                    Disciplina ou área de atuação
                </label>


                <input
                    type="text"
                    name="disciplina"
                    id="disciplina"
                    value="<?= editarProfessorEscapar(
                        $disciplina
                    ) ?>"
                    placeholder="Ex.: Informática, Java ou Programação"
                    minlength="2"
                    maxlength="100"
                    autocomplete="off"
                    required
                >


                <small>

                    Informe sua principal disciplina,
                    área ou campo de atuação.

                </small>


            </div>


        </div>


        <!-- =================================================
        AVISO DE APROVAÇÃO
        ================================================== -->

        <div class="editar-perfil-aviso">


            <strong>
                Atenção às alterações profissionais
            </strong>


            <?php if (
                $professorAprovado
            ): ?>


                <p>

                    Seu cadastro está atualmente aprovado.

                    Caso a instituição ou a área de atuação
                    seja alterada, a aprovação administrativa
                    atual será removida e seu cadastro voltará
                    para análise.

                    Durante a nova análise, somente a criação
                    e o gerenciamento de Salas de Aula
                    ficarão temporariamente bloqueados.

                </p>


            <?php else: ?>


                <p>

                    Seu cadastro ainda está aguardando
                    análise da administração.

                    Você pode atualizar estas informações
                    normalmente enquanto aguarda a aprovação.

                    Os demais recursos da plataforma
                    continuam disponíveis.

                </p>


            <?php endif; ?>


        </div>


        <!-- =================================================
        AÇÕES
        ================================================== -->

        <div class="editar-perfil-acoes">


            <a
                href="<?= editarProfessorEscapar(
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

                Salvar dados profissionais

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
            você nos espaços colaborativos
            da plataforma, como a Sala Global.

        </p>


    </div>


    <form
        action="<?= editarProfessorEscapar(
            $acaoAtualizarPerfilPublico
        ) ?>"
        method="POST"
        accept-charset="UTF-8"
        class="editar-perfil-formulario"
    >


        <input
            type="hidden"
            name="csrf_token"
            value="<?= editarProfessorEscapar(
                $csrfToken
            ) ?>"
        >


        <!-- =================================================
        E-MAIL DA CONTA
        ================================================== -->

        <div class="editar-perfil-conta">


            <div>


                <small>
                    E-MAIL DA CONTA
                </small>


                <strong>

                    <?= $emailConta !== ""
                        ?
                        editarProfessorEscapar(
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


        <!-- =================================================
        CAMPOS
        ================================================== -->

        <div class="editar-perfil-grid">


            <div class="editar-perfil-campo">


                <label for="nome">
                    Nome
                </label>


                <input
                    type="text"
                    name="nome"
                    id="nome"
                    value="<?= editarProfessorEscapar(
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


            <div class="editar-perfil-campo">


                <label for="nick_programador">
                    Nick de programador
                </label>


                <input
                    type="text"
                    name="nick_programador"
                    id="nick_programador"
                    value="<?= editarProfessorEscapar(
                        $nick
                    ) ?>"
                    placeholder="Ex.: professorjava"
                    maxlength="50"
                    autocomplete="off"
                >


                <small>

                    Nome curto utilizado para
                    identificar você na comunidade.

                </small>


            </div>


            <div class="editar-perfil-campo">


                <label for="instagram">
                    Instagram
                </label>


                <input
                    type="text"
                    name="instagram"
                    id="instagram"
                    value="<?= editarProfessorEscapar(
                        $instagram
                    ) ?>"
                    placeholder="@seuusuario"
                    maxlength="30"
                    autocomplete="off"
                >


                <small>
                    Opcional. Informe somente o usuário.
                </small>


            </div>


            <div class="editar-perfil-campo">


                <label for="email_contato">
                    E-mail para contato
                </label>


                <input
                    type="email"
                    name="email_contato"
                    id="email_contato"
                    value="<?= editarProfessorEscapar(
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


            <div
                class="editar-perfil-campo editar-perfil-campo-largo"
            >


                <label for="biografia">
                    Biografia
                </label>


                <textarea
                    name="biografia"
                    id="biografia"
                    maxlength="300"
                    placeholder="Conte um pouco sobre sua atuação, experiência com programação, ensino ou tecnologias..."
                ><?= editarProfessorEscapar(
                    $biografia
                ) ?></textarea>


                <div class="editar-perfil-campo-rodape">


                    <small>

                        Uma breve apresentação
                        sobre você.

                    </small>


                    <small>
                        Máximo de 300 caracteres.
                    </small>


                </div>


            </div>


        </div>


        <!-- =================================================
        AVISO E-MAIL
        ================================================== -->

        <div class="editar-perfil-aviso">


            <strong>
                Sobre o e-mail da conta
            </strong>


            <p>

                O e-mail utilizado para login
                não é alterado nesta página.

                O e-mail de contato é opcional
                e serve apenas para apresentação
                no perfil público.

            </p>


        </div>


        <!-- =================================================
        AÇÕES
        ================================================== -->

        <div class="editar-perfil-acoes">


            <a
                href="<?= editarProfessorEscapar(
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
            Continuar na plataforma
        </h2>


        <p>

            Retorne ao painel do Professor
            para acessar os recursos disponíveis.

        </p>


    </div>


    <div class="editar-perfil-acoes">


        <a
            href="<?= editarProfessorEscapar(
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
    src="<?= editarProfessorEscapar(
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