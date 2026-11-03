<?php

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
| ROTAS
|--------------------------------------------------------------------------
*/

$linkLogin =
    rota(
        "login"
    );

$linkInicioPerfil =
    rota(
        "painel"
    );

$linkEscolherPerfil =
    rota(
        "perfil.escolher"
    );

$linkPainel =
    rota(
        "estudante.painel"
    );

$linkEditar =
    rota(
        "estudante.editar"
    );

$linkSalaGlobal =
    rota(
        "sala_global"
    );

$linkQuestionario =
    rota(
        "estudante.questionario"
    );

$linkSair =
    rota(
        "logout"
    );


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["usuario"]
    )
) {

    header(
        "Location: " .
        $linkLogin
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

$usuario =
    $_SESSION["usuario"];


$usuarioId =
    (int) (
        $usuario["id"]
        ?? 0
    );


$nomeUsuario =
    trim(
        $usuario["nome"]
        ?? "Estudante"
    );


$emailUsuario =
    trim(
        $usuario["email"]
        ?? ""
    );


/*
|--------------------------------------------------------------------------
| VALIDA USUÁRIO
|--------------------------------------------------------------------------
*/

if (
    $usuarioId <= 0
) {

    unset(
        $_SESSION["usuario"]
    );


    header(
        "Location: " .
        $linkLogin
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| BUSCA PERFIL
|--------------------------------------------------------------------------
*/

$perfilController =
    new PerfilController();


$resultadoPerfil =
    $perfilController->buscarPerfil(
        $usuarioId
    );


/*
|--------------------------------------------------------------------------
| VALIDA PERFIL
|--------------------------------------------------------------------------
*/

if (
    empty(
        $resultadoPerfil["status"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]["usuario"]
    )
) {

    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    header(
        "Location: " .
        $linkEscolherPerfil
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| DADOS DO PERFIL
|--------------------------------------------------------------------------
*/

$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


$perfilEstudante =
    $resultadoPerfil["perfil"]["estudante"]
    ?? null;


/*
|--------------------------------------------------------------------------
| GARANTE QUE É ESTUDANTE
|--------------------------------------------------------------------------
*/

if (
    (
        $perfilUsuario["tipo_usuario"]
        ?? ""
    )
    !== "estudante"
) {

    $_SESSION["erro"] =
        "Esta área é exclusiva para estudantes.";


    header(
        "Location: " .
        $linkInicioPerfil
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| PERFIL ESPECÍFICO
|--------------------------------------------------------------------------
*/

if (
    !$perfilEstudante
) {

    header(
        "Location: " .
        $linkQuestionario
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
    &&
    isset(
        $perfilUsuario["email"]
    )
) {

    $emailUsuario =
        trim(
            $perfilUsuario["email"]
        );

}


/*
|--------------------------------------------------------------------------
| LOCALIZAÇÃO
|--------------------------------------------------------------------------
*/

$cidade =
    trim(
        $perfilUsuario["cidade"]
        ?? ""
    );


$estado =
    strtoupper(
        trim(
            $perfilUsuario["estado"]
            ?? ""
        )
    );


$localizacao =
    "Não informada";


if (
    $cidade !== ""
    &&
    $estado !== ""
) {

    $localizacao =
        $cidade .
        " - " .
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
| INSTITUIÇÃO
|--------------------------------------------------------------------------
*/

$instituicao =
    trim(
        $perfilEstudante["instituicao"]
        ?? ""
    );


$nomesInstituicoes = [

    "brigadeiro" =>
        "E.E.E.M. Brigadeiro José da Silva Paes",

    "estadual" =>
        "Escola Estadual",

    "municipal" =>
        "Escola Municipal",

    "privada" =>
        "Instituição Privada",

    "superior" =>
        "Ensino Superior"

];


$nomeInstituicao =
    $nomesInstituicoes[
        $instituicao
    ]
    ?? (
        $instituicao !== ""
            ? $instituicao
            : "Não informada"
    );


/*
|--------------------------------------------------------------------------
| OBJETIVO
|--------------------------------------------------------------------------
*/

$objetivo =
    trim(
        $perfilUsuario["objetivo"]
        ?? ""
    );


/*
|--------------------------------------------------------------------------
| PERFIL PÚBLICO
|--------------------------------------------------------------------------
*/

$nickProgramador =
    trim(
        $perfilUsuario["nick_programador"]
        ?? ""
    );


$biografia =
    trim(
        $perfilUsuario["biografia"]
        ?? ""
    );


$instagram =
    trim(
        $perfilUsuario["instagram"]
        ?? ""
    );


$emailContato =
    trim(
        $perfilUsuario["email_contato"]
        ?? ""
    );


/*
|--------------------------------------------------------------------------
| LINK DO INSTAGRAM
|--------------------------------------------------------------------------
*/

$linkInstagram =
    "";


$instagramUsuario =
    "";


if (
    $instagram !== ""
) {

    $instagramUsuario =
        ltrim(
            $instagram,
            "@"
        );


    $linkInstagram =
        "https://www.instagram.com/" .
        rawurlencode(
            $instagramUsuario
        );

}


/*
|--------------------------------------------------------------------------
| INICIAIS
|--------------------------------------------------------------------------
*/

$partesNome =
    array_values(
        array_filter(
            explode(
                " ",
                $nomeUsuario
            )
        )
    );


$iniciais =
    "";


if (
    isset(
        $partesNome[0]
    )
) {

    $iniciais .=
        mb_substr(
            $partesNome[0],
            0,
            1
        );

}


if (
    count(
        $partesNome
    ) > 1
) {

    $iniciais .=
        mb_substr(
            $partesNome[
                count(
                    $partesNome
                ) - 1
            ],
            0,
            1
        );

}


$iniciais =
    strtoupper(
        $iniciais
    );


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function perfilEstudanteEscapar(
    mixed $valor
): string
{

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );

}

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
    content="Perfil do estudante na plataforma Java, Café e Código."
>

<title>
    Meu Perfil | <?= SITE_NAME ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= filemtime(
        __DIR__ .
        "/../../../assets/css/style.css"
    ) ?>"
>


<!-- =====================================================
PERFIL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/perfil.css?v=<?= filemtime(
        __DIR__ .
        "/../../../assets/css/perfil.css"
    ) ?>"
>


<!-- =====================================================
MENSAGENS
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css?v=<?= filemtime(
        __DIR__ .
        "/../../../assets/css/mensagem.css"
    ) ?>"
>

</head>


<body class="pagina-perfil pagina-perfil-estudante">


<?php

require_once __DIR__ .
    "/../../../componentes/header.php";


require_once __DIR__ .
    "/../../../componentes/mensagem.php";

?>


<main class="perfil-pagina">


<!-- =====================================================
CABEÇALHO
====================================================== -->

<section class="perfil-cabecalho">


    <div class="perfil-identidade">


        <div class="perfil-avatar">

            <?= perfilEstudanteEscapar(
                $iniciais !== ""
                    ? $iniciais
                    : "JC"
            ) ?>

        </div>


        <div>


            <span class="perfil-etiqueta">
                PERFIL DO ESTUDANTE
            </span>


            <h1>

                <?= perfilEstudanteEscapar(
                    $nomeUsuario
                ) ?>

            </h1>


            <div class="perfil-identidade-detalhes">


                <span class="perfil-tipo">
                    Estudante
                </span>


                <?php if (
                    $nickProgramador !== ""
                ): ?>


                    <span>
                        •
                    </span>


                    <span>

                        @<?= perfilEstudanteEscapar(
                            ltrim(
                                $nickProgramador,
                                "@"
                            )
                        ) ?>

                    </span>


                <?php endif; ?>


                <?php if (
                    $localizacao !==
                    "Não informada"
                ): ?>


                    <span>
                        •
                    </span>


                    <span>

                        <?= perfilEstudanteEscapar(
                            $localizacao
                        ) ?>

                    </span>


                <?php endif; ?>


            </div>


        </div>


    </div>


    <div class="perfil-cabecalho-acoes">


        <a
            href="<?= perfilEstudanteEscapar(
                $linkPainel
            ) ?>"
            class="perfil-botao-secundario"
        >
            ← Voltar ao painel
        </a>


        <a
            href="<?= perfilEstudanteEscapar(
                $linkEditar
            ) ?>"
            class="perfil-botao-principal"
        >
            Editar informações
        </a>


    </div>


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
                Estudante
            </strong>

        </div>


    </article>


    <article>


        <span class="perfil-resumo-icone">
            @
        </span>


        <div>

            <small>
                NICK
            </small>

            <strong>

                <?= $nickProgramador !== ""
                    ?
                    "@" .
                    perfilEstudanteEscapar(
                        ltrim(
                            $nickProgramador,
                            "@"
                        )
                    )
                    :
                    "Não informado"
                ?>

            </strong>

        </div>


    </article>


    <article>


        <span class="perfil-resumo-icone">
            BR
        </span>


        <div>

            <small>
                LOCALIZAÇÃO
            </small>

            <strong>

                <?= perfilEstudanteEscapar(
                    $localizacao
                ) ?>

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

            Uma breve apresentação do estudante
            dentro da comunidade da plataforma.

        </p>


    </div>


    <article class="perfil-objetivo">


        <?php if (
            $biografia !== ""
        ): ?>


            <p>

                <?= nl2br(
                    perfilEstudanteEscapar(
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
            seu perfil na plataforma.

        </p>


    </div>


    <div class="perfil-informacoes-grid">


        <article class="perfil-informacao">


            <small>
                Nome
            </small>


            <strong>

                <?= perfilEstudanteEscapar(
                    $nomeUsuario
                ) ?>

            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                Nick de programador
            </small>


            <strong>

                <?= $nickProgramador !== ""
                    ?
                    "@" .
                    perfilEstudanteEscapar(
                        ltrim(
                            $nickProgramador,
                            "@"
                        )
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
                    perfilEstudanteEscapar(
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
                    perfilEstudanteEscapar(
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
                    $instagram !== ""
                ): ?>


                    <a
                        href="<?= perfilEstudanteEscapar(
                            $linkInstagram
                        ) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >

                        @<?= perfilEstudanteEscapar(
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
                    $emailContato !== ""
                ): ?>


                    <a
                        href="mailto:<?= perfilEstudanteEscapar(
                            $emailContato
                        ) ?>"
                    >

                        <?= perfilEstudanteEscapar(
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
                    perfilEstudanteEscapar(
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
INFORMAÇÕES ACADÊMICAS
====================================================== -->

<section class="perfil-secao">


    <div class="perfil-secao-cabecalho">


        <span class="perfil-etiqueta">
            ESTUDANTE
        </span>


        <h2>
            Informações acadêmicas
        </h2>


        <p>

            Informações relacionadas ao perfil
            educacional do estudante.

        </p>


    </div>


    <div class="perfil-informacoes-grid">


        <article class="perfil-informacao">


            <small>
                Instituição
            </small>


            <strong>

                <?= perfilEstudanteEscapar(
                    $nomeInstituicao
                ) ?>

            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                Alterar instituição
            </small>


            <strong>

                <a
                    href="<?= perfilEstudanteEscapar(
                        $linkQuestionario
                    ) ?>"
                >
                    Atualizar informação
                </a>

            </strong>


        </article>


    </div>


</section>


<!-- =====================================================
OBJETIVO
====================================================== -->

<section class="perfil-secao">


    <div class="perfil-secao-cabecalho">


        <span class="perfil-etiqueta">
            MINHA JORNADA
        </span>


        <h2>
            Objetivo na plataforma
        </h2>


        <p>

            Objetivo informado durante a configuração
            inicial do perfil.

        </p>


    </div>


    <article class="perfil-objetivo">


        <?php if (
            $objetivo !== ""
        ): ?>


            <p>

                <?= nl2br(
                    perfilEstudanteEscapar(
                        $objetivo
                    )
                ) ?>

            </p>


        <?php else: ?>


            <p class="perfil-nao-informado">

                Nenhum objetivo foi informado.

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
            utilizando os recursos da plataforma.

        </p>


    </div>


    <div class="perfil-acoes-grid">


        <!-- EDITAR -->

        <a
            href="<?= perfilEstudanteEscapar(
                $linkEditar
            ) ?>"
            class="perfil-acao-card"
        >


            <span>
                ✎
            </span>


            <div>

                <strong>
                    Editar informações
                </strong>

                <p>

                    Atualize seu nome, nick,
                    biografia e contatos.

                </p>

            </div>


            <b>
                →
            </b>


        </a>


        <!-- PAINEL -->

        <a
            href="<?= perfilEstudanteEscapar(
                $linkPainel
            ) ?>"
            class="perfil-acao-card"
        >


            <span>
                PN
            </span>


            <div>

                <strong>
                    Painel do estudante
                </strong>

                <p>

                    Retorne aos recursos
                    disponíveis para seus estudos.

                </p>

            </div>


            <b>
                →
            </b>


        </a>


        <!-- SALA GLOBAL -->

        <a
            href="<?= perfilEstudanteEscapar(
                $linkSalaGlobal
            ) ?>"
            class="perfil-acao-card"
        >


            <span>
                SG
            </span>


            <div>

                <strong>
                    Sala Global
                </strong>

                <p>

                    Compartilhe conhecimentos
                    com a comunidade.

                </p>

            </div>


            <b>
                →
            </b>


        </a>


        <!-- SAIR -->

        <a
            href="<?= perfilEstudanteEscapar(
                $linkSair
            ) ?>"
            class="perfil-acao-card perfil-acao-sair"
        >


            <span>
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


            <b>
                →
            </b>


        </a>


    </div>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../../componentes/footer.php";

?>


<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= filemtime(
        __DIR__ .
        "/../../../assets/js/theme.js"
    ) ?>"
></script>


</body>

</html>