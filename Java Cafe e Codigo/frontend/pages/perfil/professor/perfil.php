<?php

/*
|--------------------------------------------------------------------------
| PERFIL DO PROFESSOR
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Exibe:
|
| - informações da conta;
| - informações públicas;
| - dados profissionais;
| - situação da aprovação;
| - recursos disponíveis para o Professor.
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

$nomeUsuario =
    trim(
        (string) (
            $usuario["nome"]
            ??
            "Professor"
        )
    );


$emailUsuario =
    trim(
        (string) (
            $usuario["email"]
            ??
            ""
        )
    );


if (
    $nomeUsuario === ""
) {

    $nomeUsuario =
        "Professor";

}


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
        "[PerfilProfessor] Erro ao carregar perfil do usuário "
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
            "professor.painel"
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
| E-MAIL DA CONTA
|--------------------------------------------------------------------------
*/

if (
    $emailUsuario === ""
) {

    $emailUsuario =
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
| LOCALIZAÇÃO
|--------------------------------------------------------------------------
*/

$cidade =
    trim(
        (string) (
            $perfilUsuario["cidade"]
            ??
            ""
        )
    );


$estado =
    strtoupper(
        trim(
            (string) (
                $perfilUsuario["estado"]
                ??
                ""
            )
        )
    );


/*
|--------------------------------------------------------------------------
| LIMITA UF DEFENSIVAMENTE
|--------------------------------------------------------------------------
*/

if (
    $estado !== ""
    &&
    preg_match(
        "/^[A-Z]{2}$/",
        $estado
    )
    !== 1
) {

    $estado =
        "";

}


$localizacao =
    "Não informada";


if (
    $cidade !== ""
    &&
    $estado !== ""
) {

    $localizacao =
        $cidade
        .
        " - "
        .
        $estado;

}
elseif (
    $cidade !== ""
) {

    $localizacao =
        $cidade;

}
elseif (
    $estado !== ""
) {

    $localizacao =
        $estado;

}


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


if (
    $instituicao === ""
) {

    $instituicao =
        "Não informada";

}


if (
    $disciplina === ""
) {

    $disciplina =
        "Não informada";

}


/*
|--------------------------------------------------------------------------
| PERFIL PÚBLICO
|--------------------------------------------------------------------------
*/

$nickProgramador =
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
| NICK
|--------------------------------------------------------------------------
*/

$nickProgramadorExibicao =
    ltrim(
        $nickProgramador,
        "@"
    );


/*
|--------------------------------------------------------------------------
| INSTAGRAM
|--------------------------------------------------------------------------
*/

$instagramUsuario =
    ltrim(
        $instagram,
        "@"
    );


$linkInstagram =
    "";


/*
|--------------------------------------------------------------------------
| USERNAME DO INSTAGRAM
|--------------------------------------------------------------------------
|
| Instagram aceita letras, números, ponto e underline.
|
|--------------------------------------------------------------------------
*/

if (
    $instagramUsuario !== ""
    &&
    preg_match(
        "/^[A-Za-z0-9._]{1,30}$/",
        $instagramUsuario
    )
    === 1
) {

    $linkInstagram =
        "https://www.instagram.com/"
        .
        rawurlencode(
            $instagramUsuario
        );

}
else {

    $instagramUsuario =
        "";

}


/*
|--------------------------------------------------------------------------
| E-MAIL DE CONTATO
|--------------------------------------------------------------------------
*/

$emailContatoValido =
    (
        $emailContato !== ""
        &&
        filter_var(
            $emailContato,
            FILTER_VALIDATE_EMAIL
        )
        !== false
    );


/*
|--------------------------------------------------------------------------
| FUNÇÃO PARA PRIMEIRO CARACTERE
|--------------------------------------------------------------------------
*/

function perfilProfessorPrimeiroCaractere(
    string $texto
): string {

    $texto =
        trim(
            $texto
        );


    if (
        $texto === ""
    ) {

        return "";

    }


    if (
        function_exists(
            "mb_substr"
        )
    ) {

        return mb_substr(
            $texto,
            0,
            1,
            "UTF-8"
        );

    }


    return substr(
        $texto,
        0,
        1
    );

}


/*
|--------------------------------------------------------------------------
| INICIAIS
|--------------------------------------------------------------------------
*/

$partesNome =
    preg_split(
        "/\s+/u",
        $nomeUsuario,
        -1,
        PREG_SPLIT_NO_EMPTY
    );


if (
    !is_array(
        $partesNome
    )
) {

    $partesNome =
        [];

}


$iniciais =
    "";


if (
    isset(
        $partesNome[0]
    )
) {

    $iniciais .=
        perfilProfessorPrimeiroCaractere(
            $partesNome[0]
        );

}


if (
    count(
        $partesNome
    )
    >
    1
) {

    $iniciais .=
        perfilProfessorPrimeiroCaractere(
            $partesNome[
                count(
                    $partesNome
                )
                -
                1
            ]
        );

}


if (
    function_exists(
        "mb_strtoupper"
    )
) {

    $iniciais =
        mb_strtoupper(
            $iniciais,
            "UTF-8"
        );

}
else {

    $iniciais =
        strtoupper(
            $iniciais
        );

}


/*
|--------------------------------------------------------------------------
| LINKS
|--------------------------------------------------------------------------
*/

$linkPainel =
    rota(
        "professor.painel"
    );


$linkEditar =
    rota(
        "professor.editar"
    );


$linkSalaGlobal =
    rota(
        "sala_global"
    );


$linkSalas =
    rota(
        "professor.salas"
    );


$linkAulas =
    rota(
        "aulas"
    );


$linkConteudos =
    rota(
        "conteudos"
    );


$linkLaboratorio =
    rota(
        "laboratorio"
    );


$linkCaderno =
    rota(
        "caderno"
    );


$linkManual =
    rota(
        "manual"
    );


$linkComoFunciona =
    rota(
        "professor.como_funciona"
    );


$linkLogout =
    rota(
        "logout"
    );


/*
|--------------------------------------------------------------------------
| DISPONIBILIDADE DOS RECURSOS
|--------------------------------------------------------------------------
*/

$editarDisponivel =
    rotaExiste(
        "professor.editar"
    );


$salasDisponiveis =
    rotaExiste(
        "professor.salas"
    );


$comoFuncionaDisponivel =
    rotaExiste(
        "professor.como_funciona"
    );


$laboratorioDisponivel =
    rotaExiste(
        "laboratorio"
    );


$cadernoDisponivel =
    rotaExiste(
        "caderno"
    );


$salaGlobalDisponivel =
    rotaExiste(
        "sala_global"
    );


$manualDisponivel =
    rotaExiste(
        "manual"
    );


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function perfilProfessorEscapar(
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
    content="Perfil do professor na plataforma Java, Café e Código."
>

<title>
    Meu Perfil | <?= perfilProfessorEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= perfilProfessorEscapar(
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
    href="<?= perfilProfessorEscapar(
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
    href="<?= perfilProfessorEscapar(
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


<body class="pagina-perfil pagina-perfil-professor">


<?php

require_once __DIR__
    .
    "/../../../componentes/header.php";


require_once __DIR__
    .
    "/../../../componentes/mensagem.php";

?>


<main class="perfil-pagina">


<!-- =====================================================
CABEÇALHO
====================================================== -->

<section class="perfil-cabecalho">


    <div class="perfil-identidade">


        <div
            class="perfil-avatar"
            aria-hidden="true"
        >

            <?= perfilProfessorEscapar(
                $iniciais !== ""
                    ?
                    $iniciais
                    :
                    "JC"
            ) ?>

        </div>


        <div>


            <span class="perfil-etiqueta">
                PERFIL DO PROFESSOR
            </span>


            <h1>

                <?= perfilProfessorEscapar(
                    $nomeUsuario
                ) ?>

            </h1>


            <div class="perfil-identidade-detalhes">


                <span class="perfil-tipo">
                    Professor
                </span>


                <?php if (
                    $nickProgramadorExibicao !== ""
                ): ?>


                    <span aria-hidden="true">
                        •
                    </span>


                    <span>

                        @<?= perfilProfessorEscapar(
                            $nickProgramadorExibicao
                        ) ?>

                    </span>


                <?php endif; ?>


                <?php if (
                    $localizacao !==
                    "Não informada"
                ): ?>


                    <span aria-hidden="true">
                        •
                    </span>


                    <span>

                        <?= perfilProfessorEscapar(
                            $localizacao
                        ) ?>

                    </span>


                <?php endif; ?>


            </div>


        </div>


    </div>


    <?php if (
        $editarDisponivel
    ): ?>


        <div class="perfil-cabecalho-acoes">


            <a
                href="<?= perfilProfessorEscapar(
                    $linkEditar
                ) ?>"
                class="perfil-botao-principal"
            >

                Editar informações

            </a>


        </div>


    <?php endif; ?>


</section>


<!-- =====================================================
RESUMO
====================================================== -->

<section class="perfil-resumo">


    <article>


        <span class="perfil-resumo-icone">
            ID
        </span>


        <div>


            <small>
                PERFIL
            </small>


            <strong>
                Professor
            </strong>


        </div>


    </article>


    <article>


        <span class="perfil-resumo-icone">
            &lt;/&gt;
        </span>


        <div>


            <small>
                ÁREA
            </small>


            <strong>

                <?= perfilProfessorEscapar(
                    $disciplina
                ) ?>

            </strong>


        </div>


    </article>


    <article>


        <span class="perfil-resumo-icone">

            <?= $professorAprovado
                ?
                "OK"
                :
                "..."
            ?>

        </span>


        <div>


            <small>
                SITUAÇÃO
            </small>


            <strong>

                <?= $professorAprovado
                    ?
                    "Aprovado"
                    :
                    "Em análise"
                ?>

            </strong>


        </div>


    </article>


</section>


<!-- =====================================================
SOBRE
====================================================== -->

<section class="perfil-secao">


    <div class="perfil-secao-cabecalho">


        <span class="perfil-etiqueta">
            SOBRE
        </span>


        <h2>
            Sobre mim
        </h2>


        <p>

            Uma breve apresentação do professor
            dentro da comunidade da plataforma.

        </p>


    </div>


    <article class="perfil-objetivo">


        <?php if (
            $biografia !== ""
        ): ?>


            <p>

                <?= nl2br(
                    perfilProfessorEscapar(
                        $biografia
                    )
                ) ?>

            </p>


        <?php else: ?>


            <p class="perfil-nao-informado">

                Nenhuma biografia adicionada ainda.

            </p>


        <?php endif; ?>


    </article>


</section>


<!-- =====================================================
INFORMAÇÕES DO PERFIL
====================================================== -->

<section class="perfil-secao">


    <div class="perfil-secao-cabecalho">


        <span class="perfil-etiqueta">
            PERFIL
        </span>


        <h2>
            Informações
        </h2>


        <p>

            Informações utilizadas para identificar
            seu perfil dentro da plataforma.

        </p>


    </div>


    <div class="perfil-informacoes-grid">


        <article class="perfil-informacao">


            <small>
                Nome
            </small>


            <strong>

                <?= perfilProfessorEscapar(
                    $nomeUsuario
                ) ?>

            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                Nick de programador
            </small>


            <strong>

                <?= $nickProgramadorExibicao !== ""
                    ?
                    "@"
                    .
                    perfilProfessorEscapar(
                        $nickProgramadorExibicao
                    )
                    :
                    "Não informado"
                ?>

            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                Cidade
            </small>


            <strong>

                <?= $cidade !== ""
                    ?
                    perfilProfessorEscapar(
                        $cidade
                    )
                    :
                    "Não informada"
                ?>

            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                Estado
            </small>


            <strong>

                <?= $estado !== ""
                    ?
                    perfilProfessorEscapar(
                        $estado
                    )
                    :
                    "Não informado"
                ?>

            </strong>


        </article>


    </div>


</section>


<!-- =====================================================
CONTATO
====================================================== -->

<section class="perfil-secao">


    <div class="perfil-secao-cabecalho">


        <span class="perfil-etiqueta">
            CONTATO
        </span>


        <h2>
            Formas de contato
        </h2>


        <p>

            Informações opcionais disponibilizadas
            pelo próprio usuário.

        </p>


    </div>


    <div class="perfil-informacoes-grid">


        <article class="perfil-informacao">


            <small>
                Instagram
            </small>


            <strong>


                <?php if (
                    $linkInstagram !== ""
                ): ?>


                    <a
                        href="<?= perfilProfessorEscapar(
                            $linkInstagram
                        ) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >

                        @<?= perfilProfessorEscapar(
                            $instagramUsuario
                        ) ?>

                    </a>


                <?php else: ?>


                    Não informado


                <?php endif; ?>


            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                E-mail para contato
            </small>


            <strong>


                <?php if (
                    $emailContatoValido
                ): ?>


                    <a
                        href="mailto:<?= perfilProfessorEscapar(
                            $emailContato
                        ) ?>"
                    >

                        <?= perfilProfessorEscapar(
                            $emailContato
                        ) ?>

                    </a>


                <?php else: ?>


                    Não informado


                <?php endif; ?>


            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                E-mail da conta
            </small>


            <strong>

                <?= $emailUsuario !== ""
                    ?
                    perfilProfessorEscapar(
                        $emailUsuario
                    )
                    :
                    "Não informado"
                ?>

            </strong>


        </article>


    </div>


</section>


<!-- =====================================================
INFORMAÇÕES PROFISSIONAIS
====================================================== -->

<section class="perfil-secao">


    <div class="perfil-secao-cabecalho">


        <span class="perfil-etiqueta">
            PROFESSOR
        </span>


        <h2>
            Informações profissionais
        </h2>


        <p>

            Informações relacionadas ao cadastro
            profissional utilizado na plataforma.

        </p>


    </div>


    <div class="perfil-informacoes-grid">


        <article class="perfil-informacao">


            <small>
                Instituição de ensino
            </small>


            <strong>

                <?= perfilProfessorEscapar(
                    $instituicao
                ) ?>

            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                Disciplina ou área
            </small>


            <strong>

                <?= perfilProfessorEscapar(
                    $disciplina
                ) ?>

            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                Situação do cadastro
            </small>


            <strong>

                <?= $professorAprovado
                    ?
                    "Aprovado pela administração"
                    :
                    "Aguardando análise"
                ?>

            </strong>


        </article>


    </div>


</section>


<!-- =====================================================
PERMISSÃO DAS SALAS
====================================================== -->

<section class="perfil-secao">


    <div class="perfil-secao-cabecalho">


        <span class="perfil-etiqueta">
            PERMISSÃO
        </span>


        <h2>
            Salas de Aula
        </h2>


        <p>

            A aprovação administrativa está relacionada
            exclusivamente à criação e ao gerenciamento
            de Salas de Aula.

        </p>


    </div>


    <article class="perfil-objetivo">


        <?php if (
            $professorAprovado
        ): ?>


            <p>

                Seu perfil foi aprovado pela administração.

                Você possui autorização para criar e
                gerenciar Salas de Aula, além de utilizar
                normalmente os demais recursos da plataforma.

            </p>


        <?php else: ?>


            <p>

                Seu cadastro ainda está aguardando análise.

                Enquanto isso, você pode utilizar normalmente
                Aulas Conversadas, Conteúdos de Java,
                Laboratório Java, Caderno, Assistente Java,
                Sala Global e os demais recursos gerais.

                Apenas a criação e o gerenciamento de
                Salas de Aula permanecem bloqueados.

            </p>


        <?php endif; ?>


    </article>


</section>


<!-- =====================================================
ACESSOS
====================================================== -->

<section class="perfil-secao">


    <div class="perfil-secao-cabecalho">


        <span class="perfil-etiqueta">
            ACESSOS
        </span>


        <h2>
            Minha conta
        </h2>


        <p>

            Gerencie seu perfil ou continue
            utilizando os recursos disponíveis
            para o Professor.

        </p>


    </div>


    <div class="perfil-acoes-grid">


        <!-- =================================================
        EDITAR
        ================================================== -->

        <?php if (
            $editarDisponivel
        ): ?>


            <a
                href="<?= perfilProfessorEscapar(
                    $linkEditar
                ) ?>"
                class="perfil-acao-card"
            >


                <span aria-hidden="true">
                    ✎
                </span>


                <div>


                    <strong>
                        Editar informações
                    </strong>


                    <p>

                        Atualize seus dados públicos
                        e informações profissionais.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- =================================================
        PAINEL
        ================================================== -->

        <a
            href="<?= perfilProfessorEscapar(
                $linkPainel
            ) ?>"
            class="perfil-acao-card"
        >


            <span aria-hidden="true">
                PN
            </span>


            <div>


                <strong>
                    Painel do Professor
                </strong>


                <p>

                    Retorne aos recursos disponíveis
                    para seu perfil.

                </p>


            </div>


            <b aria-hidden="true">
                →
            </b>


        </a>


        <!-- =================================================
        SALAS
        ================================================== -->

        <?php if (
            $professorAprovado
            &&
            $salasDisponiveis
        ): ?>


            <a
                href="<?= perfilProfessorEscapar(
                    $linkSalas
                ) ?>"
                class="perfil-acao-card"
            >


                <span aria-hidden="true">
                    SA
                </span>


                <div>


                    <strong>
                        Salas de Aula
                    </strong>


                    <p>

                        Crie e gerencie suas salas
                        e organize a participação
                        dos estudantes.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- =================================================
        AULAS
        ================================================== -->

        <a
            href="<?= perfilProfessorEscapar(
                $linkAulas
            ) ?>"
            class="perfil-acao-card"
        >


            <span aria-hidden="true">
                IA
            </span>


            <div>


                <strong>
                    Aulas Conversadas
                </strong>


                <p>

                    Consulte as aulas interativas
                    disponíveis sobre Java.

                </p>


            </div>


            <b aria-hidden="true">
                →
            </b>


        </a>


        <!-- =================================================
        CONTEÚDOS
        ================================================== -->

        <a
            href="<?= perfilProfessorEscapar(
                $linkConteudos
            ) ?>"
            class="perfil-acao-card"
        >


            <span aria-hidden="true">
                JV
            </span>


            <div>


                <strong>
                    Conteúdos de Java
                </strong>


                <p>

                    Consulte os materiais teóricos
                    disponíveis na plataforma.

                </p>


            </div>


            <b aria-hidden="true">
                →
            </b>


        </a>


        <!-- =================================================
        LABORATÓRIO
        ================================================== -->

        <?php if (
            $laboratorioDisponivel
        ): ?>


            <a
                href="<?= perfilProfessorEscapar(
                    $linkLaboratorio
                ) ?>"
                class="perfil-acao-card"
            >


                <span aria-hidden="true">
                    &gt;_
                </span>


                <div>


                    <strong>
                        Laboratório Java
                    </strong>


                    <p>

                        Escreva, compile e execute
                        programas em Java.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- =================================================
        CADERNO
        ================================================== -->

        <?php if (
            $cadernoDisponivel
        ): ?>


            <a
                href="<?= perfilProfessorEscapar(
                    $linkCaderno
                ) ?>"
                class="perfil-acao-card"
            >


                <span aria-hidden="true">
                    CD
                </span>


                <div>


                    <strong>
                        Caderno Java
                    </strong>


                    <p>

                        Organize suas anotações
                        e informações importantes.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- =================================================
        SALA GLOBAL
        ================================================== -->

        <?php if (
            $salaGlobalDisponivel
        ): ?>


            <a
                href="<?= perfilProfessorEscapar(
                    $linkSalaGlobal
                ) ?>"
                class="perfil-acao-card"
            >


                <span aria-hidden="true">
                    SG
                </span>


                <div>


                    <strong>
                        Sala Global
                    </strong>


                    <p>

                        Participe da comunidade
                        e compartilhe conhecimentos.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- =================================================
        COMO FUNCIONA
        ================================================== -->

        <?php if (
            $comoFuncionaDisponivel
        ): ?>


            <a
                href="<?= perfilProfessorEscapar(
                    $linkComoFunciona
                ) ?>"
                class="perfil-acao-card"
            >


                <span aria-hidden="true">
                    i
                </span>


                <div>


                    <strong>
                        Como funciona
                    </strong>


                    <p>

                        Conheça os recursos e permissões
                        disponíveis para o Professor.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- =================================================
        MANUAL
        ================================================== -->

        <?php if (
            $manualDisponivel
        ): ?>


            <a
                href="<?= perfilProfessorEscapar(
                    $linkManual
                ) ?>"
                class="perfil-acao-card"
            >


                <span aria-hidden="true">
                    ?
                </span>


                <div>


                    <strong>
                        Manual
                    </strong>


                    <p>

                        Consulte orientações gerais
                        sobre a plataforma.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- =================================================
        SAIR
        ================================================== -->

        <a
            href="<?= perfilProfessorEscapar(
                $linkLogout
            ) ?>"
            class="perfil-acao-card perfil-acao-sair"
        >


            <span aria-hidden="true">
                ↪
            </span>


            <div>


                <strong>
                    Sair da conta
                </strong>


                <p>

                    Encerre sua sessão
                    atual na plataforma.

                </p>


            </div>


            <b aria-hidden="true">
                →
            </b>


        </a>


    </div>


</section>


</main>


<?php

require_once __DIR__
    .
    "/../../../componentes/footer.php";

?>


<script
    src="<?= perfilProfessorEscapar(
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