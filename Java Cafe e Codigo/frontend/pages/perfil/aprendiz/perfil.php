<?php

/*
|--------------------------------------------------------------------------
| PERFIL DO APRENDIZ
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Página responsável por apresentar:
|
| - dados públicos;
| - localização;
| - experiência;
| - objetivo de aprendizagem;
| - formas opcionais de contato;
| - acessos do perfil Aprendiz.
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

function perfilAprendizRedirecionar(
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

function perfilAprendizEscapar(
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
| PRIMEIRA LETRA
|--------------------------------------------------------------------------
*/

function perfilAprendizPrimeiraLetra(
    string $texto
): string {

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
| MAIÚSCULAS
|--------------------------------------------------------------------------
*/

function perfilAprendizMaiusculo(
    string $texto
): string {

    if (
        function_exists(
            "mb_strtoupper"
        )
    ) {

        return mb_strtoupper(
            $texto,
            "UTF-8"
        );

    }


    return strtoupper(
        $texto
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


    perfilAprendizRedirecionar(
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


    perfilAprendizRedirecionar(
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

$nomeUsuario =
    trim(
        (string) (
            $usuario["nome"]
            ??
            "Aprendiz"
        )
    );


if (
    $nomeUsuario === ""
) {

    $nomeUsuario =
        "Aprendiz";

}


$emailUsuario =
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
        "[PerfilAprendiz] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    perfilAprendizRedirecionar(
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


    perfilAprendizRedirecionar(
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
| TIPO DO PERFIL
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


    perfilAprendizRedirecionar(
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


    perfilAprendizRedirecionar(
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| NOME MAIS ATUAL
|--------------------------------------------------------------------------
|
| Se o model principal fornecer nome, utilizamos esse valor.
|
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

    $nomeUsuario =
        $nomePerfil;

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
    trim(
        (string) (
            $perfilUsuario["estado"]
            ??
            ""
        )
    );


$estado =
    perfilAprendizMaiusculo(
        $estado
    );


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
| EXPERIÊNCIA
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


$nomesExperiencia = [

    "nenhuma" =>
        "Nunca estudou programação",

    "basica" =>
        "Conhece conceitos básicos de programação",

    "intermediaria" =>
        "Já desenvolveu pequenos programas",

    "avancada" =>
        "Possui experiência avançada"

];


$experienciaFormatada =
    $nomesExperiencia[
        $experiencia
    ]
    ??
    ucfirst(
        $experiencia
    );


/*
|--------------------------------------------------------------------------
| OBJETIVO
|--------------------------------------------------------------------------
*/

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
| NORMALIZA NICK
|--------------------------------------------------------------------------
*/

$nickProgramador =
    ltrim(
        $nickProgramador,
        "@"
    );


/*
|--------------------------------------------------------------------------
| INSTAGRAM
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


    /*
    |--------------------------------------------------------------------------
    | SOMENTE USUÁRIO VÁLIDO
    |--------------------------------------------------------------------------
    */

    if (
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

}


/*
|--------------------------------------------------------------------------
| E-MAIL DE CONTATO
|--------------------------------------------------------------------------
*/

if (
    $emailContato !== ""
    &&
    filter_var(
        $emailContato,
        FILTER_VALIDATE_EMAIL
    )
    === false
) {

    $emailContato =
        "";

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


$iniciais =
    "";


if (
    is_array(
        $partesNome
    )
    &&
    isset(
        $partesNome[0]
    )
) {

    $iniciais .=
        perfilAprendizPrimeiraLetra(
            $partesNome[0]
        );


    if (
        count(
            $partesNome
        )
        > 1
    ) {

        $ultimoNome =
            $partesNome[
                count(
                    $partesNome
                )
                -
                1
            ];


        $iniciais .=
            perfilAprendizPrimeiraLetra(
                $ultimoNome
            );

    }

}


$iniciais =
    perfilAprendizMaiusculo(
        $iniciais
    );


if (
    $iniciais === ""
) {

    $iniciais =
        "JC";

}


/*
|--------------------------------------------------------------------------
| LINKS
|--------------------------------------------------------------------------
*/

$linkPainel =
    rota(
        "aprendiz.painel"
    );


$linkEditar =
    rota(
        "aprendiz.editar"
    );


$linkAulas =
    rota(
        "aulas"
    );


$linkSalaGlobal =
    rota(
        "sala_global"
    );


$linkLaboratorio =
    rota(
        "laboratorio"
    );


$linkComoFunciona =
    rota(
        "aprendiz.como_funciona"
    );


$linkManual =
    rota(
        "manual"
    );


$linkLogout =
    rota(
        "logout"
    );


/*
|--------------------------------------------------------------------------
| DISPONIBILIDADE
|--------------------------------------------------------------------------
*/

$laboratorioDisponivel =
    rotaExiste(
        "laboratorio"
    );


$salaGlobalDisponivel =
    rotaExiste(
        "sala_global"
    );


$comoFuncionaDisponivel =
    rotaExiste(
        "aprendiz.como_funciona"
    );


$manualDisponivel =
    rotaExiste(
        "manual"
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
    content="Perfil do Aprendiz na plataforma Java, Café e Código."
>

<title>
    Meu Perfil | <?= perfilAprendizEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= perfilAprendizEscapar(
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
    href="<?= perfilAprendizEscapar(
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
    href="<?= perfilAprendizEscapar(
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


<body class="pagina-perfil pagina-perfil-aprendiz">


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


        <div class="perfil-avatar">

            <?= perfilAprendizEscapar(
                $iniciais
            ) ?>

        </div>


        <div>


            <span class="perfil-etiqueta">
                PERFIL DO APRENDIZ
            </span>


            <h1>
                <?= perfilAprendizEscapar(
                    $nomeUsuario
                ) ?>
            </h1>


            <div class="perfil-identidade-detalhes">


                <span class="perfil-tipo">
                    Aprendiz
                </span>


                <?php if (
                    $nickProgramador !== ""
                ): ?>


                    <span aria-hidden="true">
                        •
                    </span>


                    <span>

                        @<?= perfilAprendizEscapar(
                            $nickProgramador
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
                        <?= perfilAprendizEscapar(
                            $localizacao
                        ) ?>
                    </span>


                <?php endif; ?>


            </div>


        </div>


    </div>


    <div class="perfil-cabecalho-acoes">


        <a
            href="<?= perfilAprendizEscapar(
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
                Aprendiz
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
                    "@"
                    .
                    perfilAprendizEscapar(
                        $nickProgramador
                    )
                    :
                    "Não informado"
                ?>

            </strong>


        </div>


    </article>


    <article>


        <span class="perfil-resumo-icone">
            &lt;/&gt;
        </span>


        <div>


            <small>
                EXPERIÊNCIA
            </small>


            <strong>
                <?= perfilAprendizEscapar(
                    $experienciaFormatada
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

            Uma breve apresentação do Aprendiz
            dentro da comunidade da plataforma.

        </p>


    </div>


    <article class="perfil-objetivo">


        <?php if (
            $biografia !== ""
        ): ?>


            <p>

                <?= nl2br(
                    perfilAprendizEscapar(
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
INFORMAÇÕES
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
                <?= perfilAprendizEscapar(
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
                    "@"
                    .
                    perfilAprendizEscapar(
                        $nickProgramador
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
                    perfilAprendizEscapar(
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
                    perfilAprendizEscapar(
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
                    $instagramUsuario !== ""
                    &&
                    $linkInstagram !== ""
                ): ?>


                    <a
                        href="<?= perfilAprendizEscapar(
                            $linkInstagram
                        ) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                    >

                        @<?= perfilAprendizEscapar(
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
                        href="mailto:<?= perfilAprendizEscapar(
                            $emailContato
                        ) ?>"
                    >

                        <?= perfilAprendizEscapar(
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
                    perfilAprendizEscapar(
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
PERFIL DE APRENDIZAGEM
====================================================== -->

<section class="perfil-secao">


    <div class="perfil-secao-cabecalho">


        <span class="perfil-etiqueta">
            APRENDIZ
        </span>


        <h2>
            Perfil de aprendizagem
        </h2>


        <p>

            Informações relacionadas à sua experiência
            atual com programação e à forma como utiliza
            os recursos da plataforma.

        </p>


    </div>


    <div class="perfil-informacoes-grid">


        <article class="perfil-informacao">


            <small>
                Experiência atual
            </small>


            <strong>
                <?= perfilAprendizEscapar(
                    $experienciaFormatada
                ) ?>
            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                Forma de utilização
            </small>


            <strong>
                Aprendizagem independente
            </strong>


        </article>


        <article class="perfil-informacao">


            <small>
                Comunidade
            </small>


            <strong>
                Sala Global
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
            Objetivo de aprendizagem
        </h2>


        <p>

            Objetivo informado durante a configuração
            ou edição do perfil Aprendiz.

        </p>


    </div>


    <article class="perfil-objetivo">


        <?php if (
            $objetivo !== ""
        ): ?>


            <p>

                <?= nl2br(
                    perfilAprendizEscapar(
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
            utilizando os recursos disponíveis
            para o perfil Aprendiz.

        </p>


    </div>


    <div class="perfil-acoes-grid">


        <!-- EDITAR -->

        <a
            href="<?= perfilAprendizEscapar(
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

                    Atualize seu perfil público,
                    experiência e objetivo de aprendizagem.

                </p>


            </div>


            <b aria-hidden="true">
                →
            </b>


        </a>


        <!-- PAINEL -->

        <a
            href="<?= perfilAprendizEscapar(
                $linkPainel
            ) ?>"
            class="perfil-acao-card"
        >


            <span>
                PN
            </span>


            <div>


                <strong>
                    Painel do Aprendiz
                </strong>


                <p>

                    Retorne ao seu painel
                    e continue sua aprendizagem.

                </p>


            </div>


            <b aria-hidden="true">
                →
            </b>


        </a>


        <!-- AULAS -->

        <a
            href="<?= perfilAprendizEscapar(
                $linkAulas
            ) ?>"
            class="perfil-acao-card"
        >


            <span>
                IA
            </span>


            <div>


                <strong>
                    Aulas Conversadas
                </strong>


                <p>

                    Continue estudando os conceitos
                    disponíveis nas aulas interativas.

                </p>


            </div>


            <b aria-hidden="true">
                →
            </b>


        </a>


        <!-- LABORATÓRIO -->

        <?php if (
            $laboratorioDisponivel
        ): ?>


            <a
                href="<?= perfilAprendizEscapar(
                    $linkLaboratorio
                ) ?>"
                class="perfil-acao-card"
            >


                <span>
                    &gt;_
                </span>


                <div>


                    <strong>
                        Laboratório Java
                    </strong>


                    <p>

                        Escreva, compile e execute
                        seus códigos Java diretamente
                        na plataforma.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- SALA GLOBAL -->

        <?php if (
            $salaGlobalDisponivel
        ): ?>


            <a
                href="<?= perfilAprendizEscapar(
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

                        Compartilhe conhecimentos,
                        dúvidas e experiências
                        com a comunidade.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- COMO FUNCIONA -->

        <?php if (
            $comoFuncionaDisponivel
        ): ?>


            <a
                href="<?= perfilAprendizEscapar(
                    $linkComoFunciona
                ) ?>"
                class="perfil-acao-card"
            >


                <span>
                    i
                </span>


                <div>


                    <strong>
                        Como funciona
                    </strong>


                    <p>

                        Conheça os recursos disponíveis
                        para o perfil Aprendiz.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- MANUAL -->

        <?php if (
            $manualDisponivel
        ): ?>


            <a
                href="<?= perfilAprendizEscapar(
                    $linkManual
                ) ?>"
                class="perfil-acao-card"
            >


                <span>
                    ?
                </span>


                <div>


                    <strong>
                        Manual
                    </strong>


                    <p>

                        Consulte as orientações
                        disponíveis no manual
                        da plataforma.

                    </p>


                </div>


                <b aria-hidden="true">
                    →
                </b>


            </a>


        <?php endif; ?>


        <!-- SAIR -->

        <a
            href="<?= perfilAprendizEscapar(
                $linkLogout
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
                    Encerre sua sessão atual na plataforma.
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
    src="<?= perfilAprendizEscapar(
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