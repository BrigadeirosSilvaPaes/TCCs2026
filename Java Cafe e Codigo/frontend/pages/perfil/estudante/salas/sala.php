<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../../../backend/config/app.php";
require_once __DIR__ . "/../../../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../../../backend/config/helpers.php";

require_once __DIR__ . "/../../../../../backend/Controller/PerfilController.php";
require_once __DIR__ . "/../../../../../backend/Controller/SalaController.php";


/*
|--------------------------------------------------------------------------
| SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| ROTAS GERAIS
|--------------------------------------------------------------------------
*/

$linkLogin =
    rota(
        "login"
    );


$linkEscolherPerfil =
    rota(
        "perfil.escolher"
    );


$linkInicio =
    rota(
        "inicio"
    );


$linkQuestionario =
    rota(
        "estudante.questionario"
    );


$linkSalas =
    rota(
        "estudante.salas"
    );


$linkPainel =
    rota(
        "estudante.painel"
    );


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function salaAlunoEscapar(
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
| REDIRECIONAMENTO
|--------------------------------------------------------------------------
*/

function salaAlunoRedirecionar(
    string $url,
    int $codigo = 302
): never {

    header(
        "Location: " . $url,
        true,
        $codigo
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| VERSÃO DE ASSET
|--------------------------------------------------------------------------
*/

function salaAlunoVersaoAsset(
    string $arquivo
): int {

    if (
        !is_file(
            $arquivo
        )
    ) {

        return 1;

    }


    $versao =
        @filemtime(
            $arquivo
        );


    return $versao !== false
        ?
        (int) $versao
        :
        1;

}


/*
|--------------------------------------------------------------------------
| PRIMEIRO NOME
|--------------------------------------------------------------------------
*/

function salaAlunoPrimeiroNome(
    string $nome
): string {

    $nome =
        trim(
            $nome
        );


    if (
        $nome === ""
    ) {

        return "Estudante";

    }


    $partes =
        preg_split(
            "/\s+/",
            $nome,
            -1,
            PREG_SPLIT_NO_EMPTY
        );


    if (
        !is_array(
            $partes
        )
        ||
        empty(
            $partes
        )
    ) {

        return "Estudante";

    }


    return (string) $partes[0];

}


/*
|--------------------------------------------------------------------------
| FORMATAR DATA
|--------------------------------------------------------------------------
*/

function salaAlunoFormatarData(
    mixed $data
): string {

    $data =
        trim(
            (string) $data
        );


    if (
        $data === ""
    ) {

        return "Data não informada";

    }


    try {

        $objetoData =
            new DateTime(
                $data
            );


        return $objetoData->format(
            "d/m/Y \à\s H:i"
        );

    }
    catch (
        Throwable $erro
    ) {

        return $data;

    }

}


/*
|--------------------------------------------------------------------------
| CATEGORIA
|--------------------------------------------------------------------------
*/

function salaAlunoCategoria(
    mixed $categoria
): string {

    $categoria =
        strtolower(
            trim(
                (string) $categoria
            )
        );


    return match (
        $categoria
    ) {

        "aviso" =>
            "Aviso",

        "explicacao" =>
            "Explicação",

        "codigo" =>
            "Código",

        "exercicio" =>
            "Exercício",

        "material" =>
            "Material",

        "video" =>
            "Vídeo",

        "link" =>
            "Link",

        default =>
            "Outro"

    };

}


/*
|--------------------------------------------------------------------------
| TAMANHO DO ARQUIVO
|--------------------------------------------------------------------------
*/

function salaAlunoFormatarTamanho(
    int $bytes
): string {

    if (
        $bytes <= 0
    ) {

        return "0 B";

    }


    if (
        $bytes < 1024
    ) {

        return $bytes . " B";

    }


    if (
        $bytes >= 1073741824
    ) {

        return
            number_format(
                $bytes / 1073741824,
                2,
                ",",
                "."
            )
            .
            " GB";

    }


    if (
        $bytes >= 1048576
    ) {

        return
            number_format(
                $bytes / 1048576,
                2,
                ",",
                "."
            )
            .
            " MB";

    }


    return
        number_format(
            $bytes / 1024,
            1,
            ",",
            "."
        )
        .
        " KB";

}


/*
|--------------------------------------------------------------------------
| URL EXTERNA SEGURA
|--------------------------------------------------------------------------
|
| Somente:
|
| http://
| https://
|
| Não permitimos:
|
| javascript:
| data:
| file:
| etc.
|
|--------------------------------------------------------------------------
*/

function salaAlunoUrlExterna(
    mixed $valor
): ?string {

    $url =
        trim(
            (string) $valor
        );


    if (
        $url === ""
        ||
        filter_var(
            $url,
            FILTER_VALIDATE_URL
        )
        === false
    ) {

        return null;

    }


    $partes =
        parse_url(
            $url
        );


    if (
        !is_array(
            $partes
        )
        ||
        empty(
            $partes["scheme"]
        )
        ||
        empty(
            $partes["host"]
        )
    ) {

        return null;

    }


    $esquema =
        strtolower(
            (string) $partes["scheme"]
        );


    if (
        !in_array(
            $esquema,
            [
                "http",
                "https"
            ],
            true
        )
    ) {

        return null;

    }


    return $url;

}


/*
|--------------------------------------------------------------------------
| ORIGEM DO POST
|--------------------------------------------------------------------------
*/

function salaAlunoOrigemPostValida(): bool {

    $origem =
        strtolower(
            trim(
                (string) (
                    $_SERVER["HTTP_SEC_FETCH_SITE"]
                    ??
                    ""
                )
            )
        );


    if (
        $origem === ""
    ) {

        return true;

    }


    return in_array(
        $origem,
        [
            "same-origin",
            "same-site",
            "none"
        ],
        true
    );

}


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["usuario"]
    )
    ||
    !is_array(
        $_SESSION["usuario"]
    )
) {

    salaAlunoRedirecionar(
        $linkLogin
    );

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
        ??
        0
    );


$nomeUsuario =
    trim(
        (string) (
            $usuario["nome"]
            ??
            "Estudante"
        )
    );


if (
    $usuarioId <= 0
) {

    unset(
        $_SESSION["usuario"]
    );


    salaAlunoRedirecionar(
        $linkLogin
    );

}


$primeiroNome =
    salaAlunoPrimeiroNome(
        $nomeUsuario
    );


/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

try {

    $perfilController =
        new PerfilController();


    $salaController =
        new SalaController();

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaEstudante] Erro ao iniciar controllers: "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar a Sala de Aula.";


    salaAlunoRedirecionar(
        $linkSalas
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

try {

    $resultadoPerfil =
        $perfilController->buscarPerfil(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaEstudante] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    salaAlunoRedirecionar(
        $linkEscolherPerfil
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL NÃO ENCONTRADO
|--------------------------------------------------------------------------
*/

if (
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


    salaAlunoRedirecionar(
        $linkEscolherPerfil
    );

}


$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


$perfilEstudante =
    isset(
        $resultadoPerfil["perfil"]["estudante"]
    )
    &&
    is_array(
        $resultadoPerfil["perfil"]["estudante"]
    )
        ?
        $resultadoPerfil["perfil"]["estudante"]
        :
        null;


/*
|--------------------------------------------------------------------------
| GARANTE ESTUDANTE
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


if (
    $tipoUsuario !==
    "estudante"
) {

    $_SESSION["erro"] =
        "Esta área é exclusiva para estudantes.";


    salaAlunoRedirecionar(
        $linkInicio
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL DE ESTUDANTE
|--------------------------------------------------------------------------
*/

if (
    $perfilEstudante === null
) {

    $_SESSION["erro"] =
        "Complete seu perfil de estudante.";


    salaAlunoRedirecionar(
        $linkQuestionario
    );

}


/*
|--------------------------------------------------------------------------
| ID DA SALA
|--------------------------------------------------------------------------
|
| O router converte:
|
| /estudante/salas/12
|
| para:
|
| $_GET["id"] = 12
|
|--------------------------------------------------------------------------
*/

$salaId =
    filter_var(
        $_GET["id"]
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
    $salaId === false
    ||
    $salaId === null
) {

    $_SESSION["erro"] =
        "Sala inválida.";


    salaAlunoRedirecionar(
        $linkSalas
    );

}


$salaId =
    (int) $salaId;


/*
|--------------------------------------------------------------------------
| URL DA SALA
|--------------------------------------------------------------------------
*/

$linkSala =
    rota(
        "estudante.sala",
        [
            "id" =>
                $salaId
        ]
    );


/*
|--------------------------------------------------------------------------
| VERIFICA PARTICIPAÇÃO
|--------------------------------------------------------------------------
*/

try {

    $resultadoSalasAluno =
        $salaController->listarSalasAluno(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaEstudante] Erro ao verificar participação do usuário "
        .
        $usuarioId
        .
        " na sala "
        .
        $salaId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível verificar sua participação na sala.";


    salaAlunoRedirecionar(
        $linkSalas
    );

}


/*
|--------------------------------------------------------------------------
| NORMALIZA SALAS
|--------------------------------------------------------------------------
*/

$salasAluno =
    !empty(
        $resultadoSalasAluno["status"]
    )
    &&
    isset(
        $resultadoSalasAluno["salas"]
    )
    &&
    is_array(
        $resultadoSalasAluno["salas"]
    )
        ?
        $resultadoSalasAluno["salas"]
        :
        [];


/*
|--------------------------------------------------------------------------
| CONFIRMA PARTICIPAÇÃO
|--------------------------------------------------------------------------
*/

$participaDaSala =
    false;


foreach (
    $salasAluno as $salaAluno
) {

    if (
        !is_array(
            $salaAluno
        )
    ) {

        continue;

    }


    if (
        (int) (
            $salaAluno["id"]
            ??
            0
        )
        ===
        $salaId
    ) {

        $participaDaSala =
            true;


        break;

    }

}


if (
    !$participaDaSala
) {

    $_SESSION["erro"] =
        "Você não participa desta sala.";


    salaAlunoRedirecionar(
        $linkSalas
    );

}


/*
|--------------------------------------------------------------------------
| BUSCA SALA
|--------------------------------------------------------------------------
*/

try {

    $resultadoSala =
        $salaController->buscar(
            $salaId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaEstudante] Erro ao buscar sala "
        .
        $salaId
        .
        " para usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar a sala.";


    salaAlunoRedirecionar(
        $linkSalas
    );

}


/*
|--------------------------------------------------------------------------
| SALA NÃO ENCONTRADA
|--------------------------------------------------------------------------
*/

if (
    empty(
        $resultadoSala["status"]
    )
    ||
    !isset(
        $resultadoSala["sala"]
    )
    ||
    !is_array(
        $resultadoSala["sala"]
    )
) {

    $_SESSION["erro"] =
        "Sala não encontrada.";


    salaAlunoRedirecionar(
        $linkSalas
    );

}


$sala =
    $resultadoSala["sala"];


/*
|--------------------------------------------------------------------------
| DADOS DA SALA
|--------------------------------------------------------------------------
*/

$nomeSala =
    trim(
        (string) (
            $sala["nome"]
            ??
            "Sala de Aula"
        )
    );


if (
    $nomeSala === ""
) {

    $nomeSala =
        "Sala de Aula";

}


$codigoSala =
    trim(
        (string) (
            $sala["codigo"]
            ??
            ""
        )
    );


$linguagemSala =
    trim(
        (string) (
            $sala["linguagem"]
            ??
            ""
        )
    );


$descricaoSala =
    trim(
        (string) (
            $sala["descricao"]
            ??
            ""
        )
    );


/*
|--------------------------------------------------------------------------
| CSRF DA SALA
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
        $_SESSION["csrf"]["estudante_salas"]
    )
    ||
    !is_array(
        $_SESSION["csrf"]["estudante_salas"]
    )
) {

    $_SESSION["csrf"]["estudante_salas"] =
        [];

}


$csrfToken =
    $_SESSION["csrf"]["estudante_salas"][$salaId]
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

    $csrfToken =
        bin2hex(
            random_bytes(
                32
            )
        );


    $_SESSION["csrf"]["estudante_salas"][$salaId] =
        $csrfToken;

}


/*
|--------------------------------------------------------------------------
| POST — SAIR DA SALA
|--------------------------------------------------------------------------
*/

if (
    strtoupper(
        (string) (
            $_SERVER["REQUEST_METHOD"]
            ??
            "GET"
        )
    )
    ===
    "POST"
) {

    /*
    |--------------------------------------------------------------------------
    | ORIGEM
    |--------------------------------------------------------------------------
    */

    if (
        !salaAlunoOrigemPostValida()
    ) {

        $_SESSION["erro"] =
            "A solicitação não pôde ser validada.";


        unset(
            $_SESSION["csrf"]["estudante_salas"][$salaId]
        );


        salaAlunoRedirecionar(
            $linkSala,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AÇÃO
    |--------------------------------------------------------------------------
    */

    $acao =
        strtolower(
            trim(
                (string) (
                    $_POST["acao"]
                    ??
                    ""
                )
            )
        );


    /*
    |--------------------------------------------------------------------------
    | TOKEN RECEBIDO
    |--------------------------------------------------------------------------
    */

    $csrfRecebido =
        $_POST["csrf_token"]
        ??
        null;


    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    if (
        !is_string(
            $csrfRecebido
        )
        ||
        $csrfRecebido === ""
        ||
        !hash_equals(
            $csrfToken,
            $csrfRecebido
        )
    ) {

        error_log(
            "[SalaEstudante] CSRF inválido. Usuário "
            .
            $usuarioId
            .
            ", sala "
            .
            $salaId
            .
            "."
        );


        $_SESSION["erro"] =
            "Sua sessão de segurança expirou. Atualize a página e tente novamente.";


        unset(
            $_SESSION["csrf"]["estudante_salas"][$salaId]
        );


        salaAlunoRedirecionar(
            $linkSala,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AÇÃO PERMITIDA
    |--------------------------------------------------------------------------
    */

    if (
        $acao !==
        "sair_sala"
    ) {

        $_SESSION["erro"] =
            "Ação inválida.";


        unset(
            $_SESSION["csrf"]["estudante_salas"][$salaId]
        );


        salaAlunoRedirecionar(
            $linkSala,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SAIR
    |--------------------------------------------------------------------------
    */

    try {

        $resultadoSaida =
            $salaController->sair(

                $salaId,

                $usuarioId

            );

    }
    catch (
        Throwable $erro
    ) {

        error_log(
            "[SalaEstudante] Erro ao sair da sala "
            .
            $salaId
            .
            ". Usuário "
            .
            $usuarioId
            .
            ": "
            .
            $erro->getMessage()
        );


        $_SESSION["erro"] =
            "Não foi possível sair da sala.";


        unset(
            $_SESSION["csrf"]["estudante_salas"][$salaId]
        );


        salaAlunoRedirecionar(
            $linkSala,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LIMPA TOKEN
    |--------------------------------------------------------------------------
    */

    unset(
        $_SESSION["csrf"]["estudante_salas"][$salaId]
    );


    /*
    |--------------------------------------------------------------------------
    | RESULTADO
    |--------------------------------------------------------------------------
    */

    if (
        empty(
            $resultadoSaida["status"]
        )
    ) {

        $_SESSION["erro"] =
            $resultadoSaida["mensagem"]
            ??
            "Não foi possível sair da sala.";


        salaAlunoRedirecionar(
            $linkSala,
            303
        );

    }


    $_SESSION["sucesso"] =
        $resultadoSaida["mensagem"]
        ??
        "Você saiu da sala.";


    salaAlunoRedirecionar(
        $linkSalas,
        303
    );

}


/*
|--------------------------------------------------------------------------
| CONTEÚDOS DA SALA
|--------------------------------------------------------------------------
|
| Só chegamos aqui após:
|
| - login;
| - perfil estudante;
| - sala válida;
| - vínculo real do estudante com a sala.
|
|--------------------------------------------------------------------------
*/

try {

    $resultadoConteudos =
        $salaController->listarConteudosAluno(

            $salaId,

            $usuarioId

        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaEstudante] Erro ao carregar conteúdos da sala "
        .
        $salaId
        .
        " para usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar os conteúdos da sala.";


    salaAlunoRedirecionar(
        $linkSalas
    );

}


/*
|--------------------------------------------------------------------------
| ERRO NOS CONTEÚDOS
|--------------------------------------------------------------------------
*/

if (
    empty(
        $resultadoConteudos["status"]
    )
) {

    $_SESSION["erro"] =
        $resultadoConteudos["mensagem"]
        ??
        "Não foi possível carregar os conteúdos da sala.";


    salaAlunoRedirecionar(
        $linkSalas
    );

}


/*
|--------------------------------------------------------------------------
| NORMALIZA CONTEÚDOS
|--------------------------------------------------------------------------
*/

$conteudos =
    isset(
        $resultadoConteudos["conteudos"]
    )
    &&
    is_array(
        $resultadoConteudos["conteudos"]
    )
        ?
        $resultadoConteudos["conteudos"]
        :
        [];


$conteudos =
    array_values(
        array_filter(
            $conteudos,
            "is_array"
        )
    );


$totalConteudos =
    count(
        $conteudos
    );


/*
|--------------------------------------------------------------------------
| CONTADORES
|--------------------------------------------------------------------------
*/

$totalArquivos =
    0;


$totalLinks =
    0;


$totalVideos =
    0;


foreach (
    $conteudos as $publicacao
) {

    $arquivosPublicacao =
        isset(
            $publicacao["arquivos"]
        )
        &&
        is_array(
            $publicacao["arquivos"]
        )
            ?
            $publicacao["arquivos"]
            :
            [];


    $linksPublicacao =
        isset(
            $publicacao["links"]
        )
        &&
        is_array(
            $publicacao["links"]
        )
            ?
            $publicacao["links"]
            :
            [];


    $videosPublicacao =
        isset(
            $publicacao["videos"]
        )
        &&
        is_array(
            $publicacao["videos"]
        )
            ?
            $publicacao["videos"]
            :
            [];


    $totalArquivos +=
        count(
            $arquivosPublicacao
        );


    $totalLinks +=
        count(
            $linksPublicacao
        );


    $totalVideos +=
        count(
            $videosPublicacao
        );

}


/*
|--------------------------------------------------------------------------
| ASSETS
|--------------------------------------------------------------------------
*/

$arquivoStyle =
    __DIR__ .
    "/../../../../assets/css/style.css";


$arquivoMensagemCss =
    __DIR__ .
    "/../../../../assets/css/mensagem.css";


$arquivoEstudanteCss =
    __DIR__ .
    "/../../../../assets/css/estudante.css";


$arquivoThemeJs =
    __DIR__ .
    "/../../../../assets/js/theme.js";


$versaoStyle =
    salaAlunoVersaoAsset(
        $arquivoStyle
    );


$versaoMensagemCss =
    salaAlunoVersaoAsset(
        $arquivoMensagemCss
    );


$versaoEstudanteCss =
    salaAlunoVersaoAsset(
        $arquivoEstudanteCss
    );


$versaoThemeJs =
    salaAlunoVersaoAsset(
        $arquivoThemeJs
    );

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
    content="Conteúdos da Sala de Aula."
>

<title>
    <?= salaAlunoEscapar(
        $nomeSala
    ) ?>
    |
    <?= salaAlunoEscapar(
        SITE_NAME
    ) ?>
</title>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= $versaoStyle ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css?v=<?= $versaoMensagemCss ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/estudante.css?v=<?= $versaoEstudanteCss ?>"
>


</head>


<body class="pagina-estudante pagina-salas-estudante">


<?php

require_once __DIR__ .
    "/../../../../componentes/header.php";


require_once __DIR__ .
    "/../../../../componentes/mensagem.php";

?>


<main class="painel-estudante">


<!-- =====================================================
APRESENTAÇÃO
====================================================== -->

<section class="painel-apresentacao">


    <div class="painel-apresentacao-conteudo">


        <span class="painel-etiqueta">

            SALA DE AULA

        </span>


        <h1>

            <?= salaAlunoEscapar(
                $nomeSala
            ) ?>

        </h1>


        <p>

            <?= salaAlunoEscapar(
                $descricaoSala !== ""
                    ?
                    $descricaoSala
                    :
                    "Acompanhe os conteúdos disponibilizados pelo professor."
            ) ?>

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="#conteudos-sala"
                class="botao-principal"
            >

                Ver conteúdos

                <span aria-hidden="true">

                    ↓

                </span>

            </a>


            <a
                href="<?= salaAlunoEscapar(
                    $linkSalas
                ) ?>"
                class="botao-secundario"
            >

                ← Minhas salas

            </a>


        </div>


    </div>


    <!-- =================================================
    CÓDIGO VISUAL
    ================================================== -->

    <div class="painel-apresentacao-codigo">


        <div class="codigo-topo">


            <div class="codigo-controles">

                <span></span>
                <span></span>
                <span></span>

            </div>


            <strong>

                MinhaTurma.java

            </strong>


            <small>

                ESTUDANTE

            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">MinhaTurma</span> {

    String codigo =
        <span class="codigo-amarelo">"<?= salaAlunoEscapar(
            $codigoSala
        ) ?>"</span>;

    int conteudos =
        <span class="codigo-amarelo"><?= salaAlunoEscapar(
            $totalConteudos
        ) ?></span>;

    <span class="codigo-roxo">void</span> <span class="codigo-amarelo">estudar</span>() {
        aluno.<span class="codigo-amarelo">aprender</span>();
    }

}</code></pre>


        <div class="codigo-status">


            <span>

                ● Participando

            </span>


            <span>

                <?= salaAlunoEscapar(
                    $primeiroNome
                ) ?>

            </span>


        </div>


    </div>


</section>


<!-- =====================================================
RESUMO
====================================================== -->

<section class="painel-estatisticas">


    <article>


        <span class="estatistica-icone">

            CT

        </span>


        <div>


            <small>

                CONTEÚDOS

            </small>


            <strong>

                <?= $totalConteudos ?>

            </strong>


            <p>

                disponíveis para você

            </p>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">

            DOC

        </span>


        <div>


            <small>

                DOCUMENTOS

            </small>


            <strong>

                <?= $totalArquivos ?>

            </strong>


            <p>

                arquivos anexados

            </p>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">

            VD

        </span>


        <div>


            <small>

                VÍDEOS

            </small>


            <strong>

                <?= $totalVideos ?>

            </strong>


            <p>

                materiais em vídeo

            </p>


        </div>


    </article>


</section>


<!-- =====================================================
INFORMAÇÕES DA SALA
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">

            SUA TURMA

        </span>


        <h2>

            Informações da sala

        </h2>


    </div>


    <div class="painel-acessos-grid">


        <article class="acesso-card">


            <div class="acesso-card-topo">


                <span class="acesso-icone">

                    #

                </span>


                <span class="acesso-status acesso-disponivel">

                    Participando

                </span>


            </div>


            <div>


                <small>

                    CÓDIGO

                </small>


                <h3>

                    <?= salaAlunoEscapar(
                        $codigoSala
                    ) ?>

                </h3>


                <p>

                    Código utilizado para entrar nesta turma.

                </p>


            </div>


        </article>


        <article class="acesso-card">


            <div class="acesso-card-topo">


                <span class="acesso-icone">

                    JV

                </span>


                <span class="acesso-status acesso-disponivel">

                    Conteúdo

                </span>


            </div>


            <div>


                <small>

                    ÁREA

                </small>


                <h3>

                    <?= salaAlunoEscapar(
                        $linguagemSala !== ""
                            ?
                            $linguagemSala
                            :
                            "Sala de Aula"
                    ) ?>

                </h3>


                <p>

                    Área principal definida pelo professor.

                </p>


            </div>


        </article>


        <article class="acesso-card">


            <div class="acesso-card-topo">


                <span class="acesso-icone">

                    LK

                </span>


                <span class="acesso-status acesso-disponivel">

                    <?= $totalLinks ?>

                </span>


            </div>


            <div>


                <small>

                    LINKS

                </small>


                <h3>

                    Materiais externos

                </h3>


                <p>

                    Sites e documentações compartilhados.

                </p>


            </div>


        </article>


    </div>


</section>


<!-- =====================================================
CONTEÚDOS
====================================================== -->

<section
    class="painel-acessos"
    id="conteudos-sala"
>


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">

            CONTEÚDOS DA SALA

        </span>


        <h2>

            Materiais publicados

        </h2>


        <p>

            Aqui aparecem somente os conteúdos que
            o professor disponibilizou para os estudantes.

        </p>


    </div>


    <?php if (
        empty(
            $conteudos
        )
    ): ?>


        <div class="painel-orientacao">


            <div class="painel-orientacao-texto">


                <span class="painel-etiqueta">

                    NENHUM CONTEÚDO

                </span>


                <h2>

                    O professor ainda não publicou materiais

                </h2>


                <p>

                    Você já está participando corretamente
                    desta sala. Quando o professor publicar
                    um conteúdo, ele aparecerá aqui.

                </p>


            </div>


        </div>


    <?php else: ?>


        <div class="painel-acessos-grid">


            <?php foreach (
                $conteudos as $publicacao
            ): ?>


                <?php

                /*
                |--------------------------------------------------------------------------
                | PUBLICAÇÃO
                |--------------------------------------------------------------------------
                */

                $tituloPublicacao =
                    trim(
                        (string) (
                            $publicacao["titulo"]
                            ??
                            "Conteúdo"
                        )
                    );


                if (
                    $tituloPublicacao === ""
                ) {

                    $tituloPublicacao =
                        "Conteúdo";

                }


                $textoPublicacao =
                    trim(
                        (string) (
                            $publicacao["conteudo"]
                            ??
                            ""
                        )
                    );


                $categoria =
                    strtolower(
                        trim(
                            (string) (
                                $publicacao["categoria"]
                                ??
                                "outro"
                            )
                        )
                    );


                $autor =
                    trim(
                        (string) (
                            $publicacao["autor_nome"]
                            ??
                            "Professor"
                        )
                    );


                if (
                    $autor === ""
                ) {

                    $autor =
                        "Professor";

                }


                $data =
                    salaAlunoFormatarData(
                        $publicacao["criado_em"]
                        ??
                        ""
                    );


                /*
                |--------------------------------------------------------------------------
                | MATERIAIS
                |--------------------------------------------------------------------------
                */

                $arquivos =
                    isset(
                        $publicacao["arquivos"]
                    )
                    &&
                    is_array(
                        $publicacao["arquivos"]
                    )
                        ?
                        $publicacao["arquivos"]
                        :
                        [];


                $links =
                    isset(
                        $publicacao["links"]
                    )
                    &&
                    is_array(
                        $publicacao["links"]
                    )
                        ?
                        $publicacao["links"]
                        :
                        [];


                $videos =
                    isset(
                        $publicacao["videos"]
                    )
                    &&
                    is_array(
                        $publicacao["videos"]
                    )
                        ?
                        $publicacao["videos"]
                        :
                        [];

                ?>


                <article class="acesso-card">


                    <!-- CABEÇALHO -->

                    <div class="acesso-card-topo">


                        <span class="acesso-icone">

                            CT

                        </span>


                        <span class="acesso-status acesso-disponivel">

                            <?= salaAlunoEscapar(
                                salaAlunoCategoria(
                                    $categoria
                                )
                            ) ?>

                        </span>


                    </div>


                    <!-- TEXTO -->

                    <div>


                        <small>

                            PUBLICAÇÃO

                        </small>


                        <h3>

                            <?= salaAlunoEscapar(
                                $tituloPublicacao
                            ) ?>

                        </h3>


                        <?php if (
                            $textoPublicacao !== ""
                        ): ?>


                            <p>

                                <?= nl2br(
                                    salaAlunoEscapar(
                                        $textoPublicacao
                                    )
                                ) ?>

                            </p>


                        <?php endif; ?>


                        <p>


                            <small>

                                Publicado por

                                <strong>

                                    <?= salaAlunoEscapar(
                                        $autor
                                    ) ?>

                                </strong>

                                •

                                <?= salaAlunoEscapar(
                                    $data
                                ) ?>

                            </small>


                        </p>


                    </div>


                    <!-- =================================================
                    DOCUMENTOS
                    ================================================== -->

                    <?php if (
                        !empty(
                            $arquivos
                        )
                    ): ?>


                        <div>


                            <small>

                                📄 DOCUMENTOS

                            </small>


                            <?php foreach (
                                $arquivos as $arquivo
                            ): ?>


                                <?php

                                if (
                                    !is_array(
                                        $arquivo
                                    )
                                ) {

                                    continue;

                                }


                                $arquivoId =
                                    (int) (
                                        $arquivo["id"]
                                        ??
                                        0
                                    );


                                $nomeArquivo =
                                    trim(
                                        (string) (
                                            $arquivo["nome_original"]
                                            ??
                                            "Documento"
                                        )
                                    );


                                if (
                                    $nomeArquivo === ""
                                ) {

                                    $nomeArquivo =
                                        "Documento";

                                }


                                $tamanhoArquivo =
                                    max(
                                        0,
                                        (int) (
                                            $arquivo["tamanho"]
                                            ??
                                            0
                                        )
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | URL PROTEGIDA
                                |--------------------------------------------------------------------------
                                |
                                | Não utilizamos mais:
                                |
                                | $arquivo["caminho"]
                                |
                                | Quando a rota protegida for adicionada,
                                | este arquivo passa automaticamente a gerar
                                | o endereço seguro.
                                |
                                |--------------------------------------------------------------------------
                                */

                                $linkDocumento =
                                    null;


                                if (
                                    $arquivoId > 0
                                    &&
                                    rotaExiste(
                                        "estudante.sala.arquivo"
                                    )
                                ) {

                                    $linkDocumento =
                                        rota(
                                            "estudante.sala.arquivo",
                                            [
                                                "sala_id" =>
                                                    $salaId,

                                                "arquivo_id" =>
                                                    $arquivoId
                                            ]
                                        );

                                }

                                ?>


                                <p>


                                    <strong>

                                        <?= salaAlunoEscapar(
                                            $nomeArquivo
                                        ) ?>

                                    </strong>


                                    <br>


                                    <small>

                                        <?= salaAlunoEscapar(
                                            salaAlunoFormatarTamanho(
                                                $tamanhoArquivo
                                            )
                                        ) ?>

                                    </small>


                                </p>


                                <?php if (
                                    $linkDocumento !== null
                                ): ?>


                                    <a
                                        href="<?= salaAlunoEscapar(
                                            $linkDocumento
                                        ) ?>"
                                        class="botao-secundario"
                                    >

                                        Abrir documento

                                        <span aria-hidden="true">

                                            →

                                        </span>

                                    </a>


                                <?php else: ?>


                                    <small>

                                        🔒 Arquivo protegido

                                    </small>


                                <?php endif; ?>


                            <?php endforeach; ?>


                        </div>


                    <?php endif; ?>


                    <!-- =================================================
                    LINKS
                    ================================================== -->

                    <?php if (
                        !empty(
                            $links
                        )
                    ): ?>


                        <div>


                            <small>

                                🔗 LINKS

                            </small>


                            <?php foreach (
                                $links as $link
                            ): ?>


                                <?php

                                if (
                                    !is_array(
                                        $link
                                    )
                                ) {

                                    continue;

                                }


                                $urlLink =
                                    salaAlunoUrlExterna(
                                        $link["url"]
                                        ??
                                        ""
                                    );


                                if (
                                    $urlLink === null
                                ) {

                                    continue;

                                }


                                $tituloLink =
                                    trim(
                                        (string) (
                                            $link["titulo"]
                                            ??
                                            ""
                                        )
                                    );


                                if (
                                    $tituloLink === ""
                                ) {

                                    $tituloLink =
                                        "Abrir material";

                                }

                                ?>


                                <p>


                                    <a
                                        href="<?= salaAlunoEscapar(
                                            $urlLink
                                        ) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="botao-secundario"
                                    >

                                        🔗

                                        <?= salaAlunoEscapar(
                                            $tituloLink
                                        ) ?>

                                        <span aria-hidden="true">

                                            ↗

                                        </span>

                                    </a>


                                </p>


                            <?php endforeach; ?>


                        </div>


                    <?php endif; ?>


                    <!-- =================================================
                    VÍDEOS
                    ================================================== -->

                    <?php if (
                        !empty(
                            $videos
                        )
                    ): ?>


                        <div>


                            <small>

                                🎬 VÍDEOS

                            </small>


                            <?php foreach (
                                $videos as $video
                            ): ?>


                                <?php

                                if (
                                    !is_array(
                                        $video
                                    )
                                ) {

                                    continue;

                                }


                                $urlVideo =
                                    salaAlunoUrlExterna(
                                        $video["url"]
                                        ??
                                        ""
                                    );


                                if (
                                    $urlVideo === null
                                ) {

                                    continue;

                                }


                                $tituloVideo =
                                    trim(
                                        (string) (
                                            $video["titulo"]
                                            ??
                                            ""
                                        )
                                    );


                                if (
                                    $tituloVideo === ""
                                ) {

                                    $tituloVideo =
                                        "Assistir vídeo";

                                }

                                ?>


                                <p>


                                    <a
                                        href="<?= salaAlunoEscapar(
                                            $urlVideo
                                        ) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="botao-principal"
                                    >

                                        ▶

                                        <?= salaAlunoEscapar(
                                            $tituloVideo
                                        ) ?>

                                    </a>


                                </p>


                            <?php endforeach; ?>


                        </div>


                    <?php endif; ?>


                </article>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</section>


<!-- =====================================================
ORIENTAÇÃO
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">

            COMO FUNCIONA

        </span>


        <h2>

            Os materiais são atualizados pelo professor

        </h2>


        <p>

            Novas explicações, exercícios, arquivos,
            links e vídeos publicados nesta sala ficarão
            disponíveis automaticamente nesta página.

        </p>


    </div>


    <div class="painel-orientacao-etapas">


        <article>


            <span>

                01

            </span>


            <div>


                <strong>

                    Professor publica

                </strong>


                <p>

                    O professor adiciona o material à turma.

                </p>


            </div>


        </article>


        <article>


            <span>

                02

            </span>


            <div>


                <strong>

                    Material aparece

                </strong>


                <p>

                    A publicação fica disponível nesta página.

                </p>


            </div>


        </article>


        <article>


            <span>

                03

            </span>


            <div>


                <strong>

                    Você acessa

                </strong>


                <p>

                    Consulte os materiais disponibilizados pela turma.

                </p>


            </div>


        </article>


    </div>


</section>


<!-- =====================================================
SAIR DA SALA
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">

            PARTICIPAÇÃO

        </span>


        <h2>

            Gerenciar participação

        </h2>


        <p>

            Ao sair da sala, ela deixará de aparecer
            na sua lista. Para participar novamente,
            será necessário informar o código outra vez.

        </p>


    </div>


    <form
        method="POST"
        action="<?= salaAlunoEscapar(
            $linkSala
        ) ?>"
    >


        <input
            type="hidden"
            name="acao"
            value="sair_sala"
        >


        <input
            type="hidden"
            name="csrf_token"
            value="<?= salaAlunoEscapar(
                $csrfToken
            ) ?>"
        >


        <button
            type="submit"
            class="botao-secundario"
        >

            Sair desta sala

        </button>


    </form>


</section>


<!-- =====================================================
AÇÕES
====================================================== -->

<section class="painel-apresentacao-acoes">


    <a
        href="<?= salaAlunoEscapar(
            $linkSalas
        ) ?>"
        class="botao-secundario"
    >

        ← Minhas salas

    </a>


    <a
        href="<?= salaAlunoEscapar(
            $linkPainel
        ) ?>"
        class="botao-secundario"
    >

        Painel do estudante

    </a>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../../../componentes/footer.php";

?>


<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


</body>

</html>