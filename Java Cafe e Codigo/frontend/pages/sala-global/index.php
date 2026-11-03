<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../backend/config/app.php";
require_once __DIR__ . "/../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../backend/config/helpers.php";

require_once __DIR__ . "/../../../backend/Controller/SalaGlobalController.php";
require_once __DIR__ . "/../../../backend/Controller/PerfilController.php";


/*
|--------------------------------------------------------------------------
| SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| ROTAS
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


$linkSalaGlobal =
    rota(
        "sala_global"
    );


/*
|--------------------------------------------------------------------------
| FUNÇÕES AUXILIARES
|--------------------------------------------------------------------------
*/

function salaGlobalEscapar(
    mixed $valor
): string
{

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

function salaGlobalRedirecionar(
    string $url,
    int $codigo = 302
): never
{

    header(
        "Location: " . $url,
        true,
        $codigo
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| DATA
|--------------------------------------------------------------------------
*/

function salaGlobalData(
    ?string $data
): string
{

    if (
        $data === null
        ||
        trim(
            $data
        ) === ""
    ) {

        return "";

    }


    $timestamp =
        strtotime(
            $data
        );


    if (
        $timestamp === false
    ) {

        return trim(
            $data
        );

    }


    return date(
        "d/m/Y H:i",
        $timestamp
    );

}


/*
|--------------------------------------------------------------------------
| TAMANHO DE ARQUIVO
|--------------------------------------------------------------------------
*/

function salaGlobalTamanhoArquivo(
    int $bytes
): string
{

    if (
        $bytes <= 0
    ) {

        return "0 B";

    }


    if (
        $bytes < 1024
    ) {

        return (
            $bytes .
            " B"
        );

    }


    if (
        $bytes <
        1024 * 1024
    ) {

        return number_format(
            $bytes / 1024,
            1,
            ",",
            "."
        )
        .
        " KB";

    }


    return number_format(
        $bytes / 1024 / 1024,
        1,
        ",",
        "."
    )
    .
    " MB";

}


/*
|--------------------------------------------------------------------------
| PRIMEIRA LETRA DO AVATAR
|--------------------------------------------------------------------------
*/

function salaGlobalInicial(
    string $nome
): string
{

    $nome =
        trim(
            $nome
        );


    if (
        $nome === ""
    ) {

        return "?";

    }


    if (
        function_exists(
            "mb_substr"
        )
    ) {

        $letra =
            mb_substr(
                $nome,
                0,
                1,
                "UTF-8"
            );


        if (
            function_exists(
                "mb_strtoupper"
            )
        ) {

            return mb_strtoupper(
                $letra,
                "UTF-8"
            );

        }


        return strtoupper(
            $letra
        );

    }


    return strtoupper(
        substr(
            $nome,
            0,
            1
        )
    );

}


/*
|--------------------------------------------------------------------------
| URL EXTERNA SEGURA
|--------------------------------------------------------------------------
*/

function salaGlobalUrlExterna(
    mixed $valor
): ?string
{

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
        ) === false
    ) {

        return null;

    }


    $esquema =
        strtolower(
            (string) (
                parse_url(
                    $url,
                    PHP_URL_SCHEME
                )
                ??
                ""
            )
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
| TIPO DE VISUALIZAÇÃO DE ARQUIVO
|--------------------------------------------------------------------------
*/

function salaGlobalTipoVisualizacao(
    string $extensao
): string
{

    $extensao =
        strtolower(
            trim(
                $extensao
            )
        );


    if (
        in_array(
            $extensao,
            [
                "png",
                "jpg",
                "jpeg",
                "webp"
            ],
            true
        )
    ) {

        return "imagem";

    }


    if (
        $extensao ===
        "pdf"
    ) {

        return "pdf";

    }


    if (
        $extensao ===
        "txt"
    ) {

        return "texto";

    }


    return "arquivo";

}


/*
|--------------------------------------------------------------------------
| ÍCONE DO ARQUIVO
|--------------------------------------------------------------------------
*/

function salaGlobalIconeArquivo(
    string $extensao
): string
{

    $extensao =
        strtolower(
            trim(
                $extensao
            )
        );


    if (
        in_array(
            $extensao,
            [
                "png",
                "jpg",
                "jpeg",
                "webp"
            ],
            true
        )
    ) {

        return "🖼️";

    }


    if (
        $extensao ===
        "pdf"
    ) {

        return "📕";

    }


    if (
        in_array(
            $extensao,
            [
                "doc",
                "docx"
            ],
            true
        )
    ) {

        return "📘";

    }


    if (
        in_array(
            $extensao,
            [
                "ppt",
                "pptx"
            ],
            true
        )
    ) {

        return "📙";

    }


    if (
        $extensao ===
        "txt"
    ) {

        return "📄";

    }


    return "📎";

}


/*
|--------------------------------------------------------------------------
| CSRF - TOKEN
|--------------------------------------------------------------------------
*/

function salaGlobalCsrfToken(): string
{

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


    $tokenAtual =
        $_SESSION["csrf"]["sala_global"]
        ?? null;


    if (
        is_string(
            $tokenAtual
        )
        &&
        preg_match(
            "/^[a-f0-9]{64}$/",
            $tokenAtual
        ) === 1
    ) {

        return $tokenAtual;

    }


    $novoToken =
        bin2hex(
            random_bytes(
                32
            )
        );


    $_SESSION["csrf"]["sala_global"] =
        $novoToken;


    return $novoToken;

}


/*
|--------------------------------------------------------------------------
| CSRF - VALIDAÇÃO
|--------------------------------------------------------------------------
*/

function salaGlobalCsrfValido(
    mixed $tokenRecebido
): bool
{

    $tokenSessao =
        $_SESSION["csrf"]["sala_global"]
        ?? null;


    if (
        !is_string(
            $tokenSessao
        )
        ||
        !is_string(
            $tokenRecebido
        )
        ||
        $tokenRecebido === ""
    ) {

        return false;

    }


    return hash_equals(
        $tokenSessao,
        $tokenRecebido
    );

}


/*
|--------------------------------------------------------------------------
| CSRF - RENOVA
|--------------------------------------------------------------------------
*/

function salaGlobalCsrfRenovar(): void
{

    if (
        isset(
            $_SESSION["csrf"]
        )
        &&
        is_array(
            $_SESSION["csrf"]
        )
    ) {

        unset(
            $_SESSION["csrf"]["sala_global"]
        );

    }

}


/*
|--------------------------------------------------------------------------
| VERIFICA ORIGEM DO POST
|--------------------------------------------------------------------------
*/

function salaGlobalOrigemPostValida(): bool
{

    $origem =
        strtolower(
            trim(
                (string) (
                    $_SERVER["HTTP_SEC_FETCH_SITE"]
                    ?? ""
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

    salaGlobalRedirecionar(
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
        ?? 0
    );


if (
    $usuarioId <= 0
) {

    unset(
        $_SESSION["usuario"]
    );


    salaGlobalRedirecionar(
        $linkLogin
    );

}


/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

$perfilController =
    new PerfilController();


$salaGlobalController =
    new SalaGlobalController();


/*
|--------------------------------------------------------------------------
| PERFIL DO USUÁRIO
|--------------------------------------------------------------------------
*/

try {

    $resultadoPerfil =
        $perfilController->buscarPerfil(
            $usuarioId
        );

}
catch (
    Throwable $erroPerfil
) {

    error_log(
        "[SalaGlobal] Erro ao carregar perfil: " .
        $erroPerfil->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    salaGlobalRedirecionar(
        $linkEscolherPerfil
    );

}


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
    ||
    !is_array(
        $resultadoPerfil["perfil"]["usuario"]
    )
) {

    $_SESSION["erro"] =
        "Complete seu perfil antes de acessar a Sala Global.";


    salaGlobalRedirecionar(
        $linkEscolherPerfil
    );

}


$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


$tipoUsuario =
    strtolower(
        trim(
            (string) (
                $perfilUsuario["tipo_usuario"]
                ?? ""
            )
        )
    );


/*
|--------------------------------------------------------------------------
| TIPOS ACEITOS
|--------------------------------------------------------------------------
*/

$tiposPermitidos = [

    "estudante",
    "aprendiz",
    "professor"

];


if (
    !in_array(
        $tipoUsuario,
        $tiposPermitidos,
        true
    )
) {

    $_SESSION["erro"] =
        "Seu perfil não possui acesso à Sala Global.";


    salaGlobalRedirecionar(
        $linkInicio
    );

}


/*
|--------------------------------------------------------------------------
| PAINEL CORRESPONDENTE AO PERFIL
|--------------------------------------------------------------------------
*/

$rotasPaineis = [

    "estudante" =>
        "estudante.painel",

    "aprendiz" =>
        "aprendiz.painel",

    "professor" =>
        "professor.painel"

];


$linkPainel =
    rota(
        $rotasPaineis[
            $tipoUsuario
        ]
    );


/*
|--------------------------------------------------------------------------
| CATEGORIAS
|--------------------------------------------------------------------------
*/

$categorias = [

    "todos" =>
        "Todos",

    "dica" =>
        "Dicas",

    "explicacao" =>
        "Explicações",

    "codigo" =>
        "Código",

    "exercicio" =>
        "Exercícios",

    "material" =>
        "Materiais",

    "video" =>
        "Vídeos",

    "documentacao" =>
        "Documentação",

    "duvida" =>
        "Dúvidas",

    "outro" =>
        "Outros"

];


/*
|--------------------------------------------------------------------------
| NOMES DOS PERFIS
|--------------------------------------------------------------------------
*/

$nomesPerfis = [

    "estudante" =>
        "Estudante",

    "aprendiz" =>
        "Aprendiz",

    "professor" =>
        "Professor"

];


/*
|--------------------------------------------------------------------------
| TOKEN CSRF
|--------------------------------------------------------------------------
*/

$csrfToken =
    salaGlobalCsrfToken();


/*
|--------------------------------------------------------------------------
| POST
|--------------------------------------------------------------------------
*/

if (
    (
        $_SERVER["REQUEST_METHOD"]
        ?? "GET"
    )
    === "POST"
) {

    /*
    |--------------------------------------------------------------------------
    | ORIGEM
    |--------------------------------------------------------------------------
    */

    if (
        !salaGlobalOrigemPostValida()
    ) {

        $_SESSION["erro"] =
            "A solicitação não pôde ser validada.";


        salaGlobalCsrfRenovar();


        salaGlobalRedirecionar(
            $linkSalaGlobal,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    if (
        !salaGlobalCsrfValido(
            $_POST["csrf_token"]
            ?? null
        )
    ) {

        $_SESSION["erro"] =
            "Sua sessão de segurança expirou. Tente novamente.";


        salaGlobalCsrfRenovar();


        salaGlobalRedirecionar(
            $linkSalaGlobal,
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
                    ?? ""
                )
            )
        );


    /*
    |--------------------------------------------------------------------------
    | CRIAR PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    if (
        $acao ===
        "criar_publicacao"
    ) {

        $titulo =
            trim(
                (string) (
                    $_POST["titulo"]
                    ?? ""
                )
            );


        $conteudo =
            isset(
                $_POST["conteudo"]
            )
                ?
                trim(
                    (string) $_POST["conteudo"]
                )
                :
                null;


        $categoria =
            strtolower(
                trim(
                    (string) (
                        $_POST["categoria"]
                        ?? "outro"
                    )
                )
            );


        if (
            $categoria === "todos"
            ||
            !array_key_exists(
                $categoria,
                $categorias
            )
        ) {

            $categoria =
                "outro";

        }


        /*
        |--------------------------------------------------------------------------
        | ARQUIVOS
        |--------------------------------------------------------------------------
        */

        $arquivos =
            isset(
                $_FILES["arquivos"]
            )
            &&
            is_array(
                $_FILES["arquivos"]
            )
                ?
                $_FILES["arquivos"]
                :
                [];


        /*
        |--------------------------------------------------------------------------
        | LINK / VÍDEO
        |--------------------------------------------------------------------------
        */

        $links =
            [];


        $linkUrlOriginal =
            trim(
                (string) (
                    $_POST["link_url"]
                    ?? ""
                )
            );


        if (
            $linkUrlOriginal !== ""
        ) {

            $linkUrl =
                salaGlobalUrlExterna(
                    $linkUrlOriginal
                );


            if (
                $linkUrl === null
            ) {

                $_SESSION["erro"] =
                    "O link informado não é válido. Utilize um endereço HTTP ou HTTPS.";


                salaGlobalCsrfRenovar();


                salaGlobalRedirecionar(
                    $linkSalaGlobal,
                    303
                );

            }


            $linkTipo =
                strtolower(
                    trim(
                        (string) (
                            $_POST["link_tipo"]
                            ?? "link"
                        )
                    )
                );


            if (
                !in_array(
                    $linkTipo,
                    [
                        "link",
                        "video"
                    ],
                    true
                )
            ) {

                $linkTipo =
                    "link";

            }


            $links[] = [

                "url" =>
                    $linkUrl,

                "titulo" =>
                    trim(
                        (string) (
                            $_POST["link_titulo"]
                            ?? ""
                        )
                    ),

                "tipo" =>
                    $linkTipo

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | CRIA
        |--------------------------------------------------------------------------
        */

        try {

            $resultado =
                $salaGlobalController->criarPublicacao(

                    $usuarioId,

                    $titulo,

                    $conteudo,

                    $categoria,

                    $arquivos,

                    $links

                );

        }
        catch (
            Throwable $erroPublicacao
        ) {

            error_log(
                "[SalaGlobal] Erro ao criar publicação: " .
                $erroPublicacao->getMessage()
            );


            $resultado = [

                "status" =>
                    false,

                "mensagem" =>
                    "Não foi possível criar a publicação."

            ];

        }


        if (
            !empty(
                $resultado["status"]
            )
        ) {

            $_SESSION["sucesso"] =
                $resultado["mensagem"]
                ??
                "Publicação criada com sucesso.";

        }
        else {

            $_SESSION["erro"] =
                $resultado["mensagem"]
                ??
                "Não foi possível criar a publicação.";

        }


        salaGlobalCsrfRenovar();


        salaGlobalRedirecionar(
            $linkSalaGlobal,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ISSO ME AJUDOU
    |--------------------------------------------------------------------------
    */

    if (
        $acao ===
        "ajuda"
    ) {

        $publicacaoId =
            filter_var(
                $_POST["publicacao_id"]
                ?? null,
                FILTER_VALIDATE_INT,
                [
                    "options" => [
                        "min_range" => 1
                    ]
                ]
            );


        if (
            $publicacaoId === false
        ) {

            $_SESSION["erro"] =
                "A publicação informada é inválida.";


            salaGlobalCsrfRenovar();


            salaGlobalRedirecionar(
                $linkSalaGlobal,
                303
            );

        }


        try {

            $resultado =
                $salaGlobalController->alternarAjuda(

                    (int) $publicacaoId,

                    $usuarioId

                );

        }
        catch (
            Throwable $erroAjuda
        ) {

            error_log(
                "[SalaGlobal] Erro ao registrar ajuda: " .
                $erroAjuda->getMessage()
            );


            $resultado = [

                "status" =>
                    false,

                "mensagem" =>
                    "Não foi possível registrar sua avaliação."

            ];

        }


        if (
            empty(
                $resultado["status"]
            )
        ) {

            $_SESSION["erro"] =
                $resultado["mensagem"]
                ??
                "Não foi possível registrar.";

        }


        salaGlobalCsrfRenovar();


        salaGlobalRedirecionar(
            $linkSalaGlobal,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AÇÃO DESCONHECIDA
    |--------------------------------------------------------------------------
    */

    $_SESSION["erro"] =
        "A ação solicitada não é válida.";


    salaGlobalCsrfRenovar();


    salaGlobalRedirecionar(
        $linkSalaGlobal,
        303
    );

}


/*
|--------------------------------------------------------------------------
| FILTROS
|--------------------------------------------------------------------------
*/

$categoriaAtual =
    strtolower(
        trim(
            (string) (
                $_GET["categoria"]
                ?? "todos"
            )
        )
    );


if (
    !array_key_exists(
        $categoriaAtual,
        $categorias
    )
) {

    $categoriaAtual =
        "todos";

}


$pesquisa =
    trim(
        (string) (
            $_GET["pesquisa"]
            ?? ""
        )
    );


if (
    function_exists(
        "mb_substr"
    )
) {

    $pesquisa =
        mb_substr(
            $pesquisa,
            0,
            150,
            "UTF-8"
        );

}
else {

    $pesquisa =
        substr(
            $pesquisa,
            0,
            150
        );

}


/*
|--------------------------------------------------------------------------
| FEED
|--------------------------------------------------------------------------
*/

try {

    $resultadoFeed =
        $salaGlobalController->listarFeed(

            $categoriaAtual,

            $pesquisa,

            30,

            0,

            $usuarioId

        );

}
catch (
    Throwable $erroFeed
) {

    error_log(
        "[SalaGlobal] Erro ao carregar feed: " .
        $erroFeed->getMessage()
    );


    $resultadoFeed = [

        "status" =>
            false,

        "publicacoes" =>
            []

    ];

}


/*
|--------------------------------------------------------------------------
| PUBLICAÇÕES
|--------------------------------------------------------------------------
*/

$publicacoes =
    !empty(
        $resultadoFeed["status"]
    )
    &&
    isset(
        $resultadoFeed["publicacoes"]
    )
    &&
    is_array(
        $resultadoFeed["publicacoes"]
    )
        ?
        $resultadoFeed["publicacoes"]
        :
        [];


/*
|--------------------------------------------------------------------------
| PERFIL DOS AUTORES
|--------------------------------------------------------------------------
*/

$tiposAutores =
    [];


foreach (
    $publicacoes as $indice => $publicacao
) {

    if (
        !is_array(
            $publicacao
        )
    ) {

        unset(
            $publicacoes[
                $indice
            ]
        );


        continue;

    }


    $autorId =
        (int) (
            $publicacao["usuario_id"]
            ?? 0
        );


    if (
        $autorId > 0
        &&
        !array_key_exists(
            $autorId,
            $tiposAutores
        )
    ) {

        try {

            $perfilAutor =
                $perfilController->buscarPerfil(
                    $autorId
                );


            $tipoAutor =
                strtolower(
                    trim(
                        (string) (
                            $perfilAutor["perfil"]["usuario"]["tipo_usuario"]
                            ?? ""
                        )
                    )
                );


            if (
                !in_array(
                    $tipoAutor,
                    $tiposPermitidos,
                    true
                )
            ) {

                $tipoAutor =
                    "";

            }


            $tiposAutores[
                $autorId
            ] =
                $tipoAutor;

        }
        catch (
            Throwable $erroAutor
        ) {

            error_log(
                "[SalaGlobal] Erro ao carregar perfil do autor " .
                $autorId .
                ": " .
                $erroAutor->getMessage()
            );


            $tiposAutores[
                $autorId
            ] =
                "";

        }

    }


    $publicacoes[
        $indice
    ]["autor_tipo"] =
        $tiposAutores[
            $autorId
        ]
        ??
        "";

}


$publicacoes =
    array_values(
        $publicacoes
    );


$totalPublicacoes =
    count(
        $publicacoes
    );


/*
|--------------------------------------------------------------------------
| TÍTULO DO FEED
|--------------------------------------------------------------------------
*/

if (
    $pesquisa !== ""
) {

    $tituloFeed =
        "Resultados da pesquisa";

}
elseif (
    $categoriaAtual !==
    "todos"
) {

    $tituloFeed =
        $categorias[
            $categoriaAtual
        ]
        ??
        "Publicações";

}
else {

    $tituloFeed =
        "Publicações recentes";

}


/*
|--------------------------------------------------------------------------
| ASSETS
|--------------------------------------------------------------------------
*/

$arquivoStyle =
    __DIR__ .
    "/../../assets/css/style.css";


$arquivoMensagemCss =
    __DIR__ .
    "/../../assets/css/mensagem.css";


$arquivoSalaGlobalCss =
    __DIR__ .
    "/../../assets/css/sala_global.css";


$arquivoThemeJs =
    __DIR__ .
    "/../../assets/js/theme.js";


$versaoStyle =
    is_file(
        $arquivoStyle
    )
        ?
        filemtime(
            $arquivoStyle
        )
        :
        1;


$versaoMensagemCss =
    is_file(
        $arquivoMensagemCss
    )
        ?
        filemtime(
            $arquivoMensagemCss
        )
        :
        1;


$versaoSalaGlobalCss =
    is_file(
        $arquivoSalaGlobalCss
    )
        ?
        filemtime(
            $arquivoSalaGlobalCss
        )
        :
        1;


$versaoThemeJs =
    is_file(
        $arquivoThemeJs
    )
        ?
        filemtime(
            $arquivoThemeJs
        )
        :
        1;

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
    content="Sala Global colaborativa da plataforma Java, Café e Código."
>

<title>
    Sala Global |
    <?= salaGlobalEscapar(
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


<?php if (
    is_file(
        $arquivoSalaGlobalCss
    )
): ?>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/sala_global.css?v=<?= $versaoSalaGlobalCss ?>"
>

<?php endif; ?>


</head>


<body class="pagina-sala-global">


<?php

require_once __DIR__ .
    "/../../componentes/header.php";


require_once __DIR__ .
    "/../../componentes/mensagem.php";

?>


<main class="sala-global">


<!-- =====================================================
CABEÇALHO
====================================================== -->

<section class="sala-global-hero">


    <div>


        <span class="sala-global-etiqueta">

            COMUNIDADE

        </span>


        <h1>

            Sala Global

        </h1>


        <p>

            Um espaço colaborativo para compartilhar conhecimentos,
            materiais, dúvidas, códigos, vídeos e experiências que
            possam ajudar outras pessoas a aprender programação.

        </p>


        <div class="sala-global-perfil">

            Você está participando como

            <strong>

                <?= salaGlobalEscapar(
                    $nomesPerfis[
                        $tipoUsuario
                    ]
                    ??
                    ucfirst(
                        $tipoUsuario
                    )
                ) ?>

            </strong>

        </div>


    </div>


    <a
        href="<?= salaGlobalEscapar(
            $linkPainel
        ) ?>"
        class="sala-global-voltar"
    >

        ← Voltar ao painel

    </a>


</section>


<!-- =====================================================
AVISO COMUNITÁRIO
====================================================== -->

<section class="sala-global-aviso">


    <strong>

        Conhecimento compartilhado pela comunidade

    </strong>


    <p>

        Estudantes, aprendizes e professores podem contribuir.
        Os materiais publicados representam contribuições dos
        próprios usuários e podem conter erros. Sempre que possível,
        compare informações técnicas com fontes confiáveis e
        documentação oficial.

    </p>


</section>


<!-- =====================================================
CRIAR PUBLICAÇÃO
====================================================== -->

<section class="sala-global-criar">


<details>


    <summary>

        <span aria-hidden="true">

            ＋

        </span>

        Compartilhar conhecimento

    </summary>


    <form
        method="POST"
        action="<?= salaGlobalEscapar(
            $linkSalaGlobal
        ) ?>"
        enctype="multipart/form-data"
        class="sala-global-formulario"
    >


        <input
            type="hidden"
            name="csrf_token"
            value="<?= salaGlobalEscapar(
                $csrfToken
            ) ?>"
        >


        <input
            type="hidden"
            name="acao"
            value="criar_publicacao"
        >


        <div class="sala-global-campo">


            <label for="titulo">

                Título

            </label>


            <input
                type="text"
                id="titulo"
                name="titulo"
                maxlength="150"
                placeholder="Ex.: Uma forma simples de entender ArrayList"
                required
            >


        </div>


        <div class="sala-global-campo">


            <label for="categoria">

                Categoria

            </label>


            <select
                id="categoria"
                name="categoria"
                required
            >


                <?php foreach (
                    $categorias
                    as $valor => $nome
                ): ?>


                    <?php if (
                        $valor !==
                        "todos"
                    ): ?>


                        <option
                            value="<?= salaGlobalEscapar(
                                $valor
                            ) ?>"
                        >

                            <?= salaGlobalEscapar(
                                $nome
                            ) ?>

                        </option>


                    <?php endif; ?>


                <?php endforeach; ?>


            </select>


        </div>


        <div class="sala-global-campo">


            <label for="conteudo">

                Conteúdo

            </label>


            <textarea
                id="conteudo"
                name="conteudo"
                rows="7"
                placeholder="Compartilhe uma explicação, dica, dúvida, exemplo ou experiência..."
            ></textarea>


        </div>


        <div class="sala-global-campo">


            <label for="arquivos">

                Anexar materiais

            </label>


            <input
                type="file"
                id="arquivos"
                name="arquivos[]"
                multiple
                accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.png,.jpg,.jpeg,.webp"
            >


            <small>

                PDF, Word, PowerPoint, TXT e imagens.
                Máximo de 10 MB por arquivo.

            </small>


        </div>


        <fieldset class="sala-global-link">


            <legend>

                Link ou vídeo opcional

            </legend>


            <div class="sala-global-campo">


                <label for="link_tipo">

                    Tipo

                </label>


                <select
                    id="link_tipo"
                    name="link_tipo"
                >

                    <option value="link">

                        Link

                    </option>


                    <option value="video">

                        Vídeo

                    </option>

                </select>


            </div>


            <div class="sala-global-campo">


                <label for="link_titulo">

                    Nome do material

                </label>


                <input
                    type="text"
                    id="link_titulo"
                    name="link_titulo"
                    maxlength="150"
                    placeholder="Ex.: Documentação do ArrayList"
                >


            </div>


            <div class="sala-global-campo">


                <label for="link_url">

                    Endereço

                </label>


                <input
                    type="url"
                    id="link_url"
                    name="link_url"
                    maxlength="2048"
                    placeholder="https://..."
                >


                <small>

                    Para vídeos, prefira links do YouTube
                    ou outras plataformas de vídeo.

                </small>


            </div>


        </fieldset>


        <button
            type="submit"
            class="sala-global-publicar"
        >

            Publicar na Sala Global

        </button>


    </form>


</details>


</section>


<!-- =====================================================
BUSCA E FILTROS
====================================================== -->

<section class="sala-global-filtros">


    <form
        method="GET"
        action="<?= salaGlobalEscapar(
            $linkSalaGlobal
        ) ?>"
        class="sala-global-pesquisa"
    >


        <input
            type="search"
            name="pesquisa"
            maxlength="150"
            value="<?= salaGlobalEscapar(
                $pesquisa
            ) ?>"
            placeholder="Buscar explicações, códigos, materiais..."
        >


        <select
            name="categoria"
        >


            <?php foreach (
                $categorias
                as $valor => $nome
            ): ?>


                <option
                    value="<?= salaGlobalEscapar(
                        $valor
                    ) ?>"
                    <?= $categoriaAtual === $valor
                        ? "selected"
                        : ""
                    ?>
                >

                    <?= salaGlobalEscapar(
                        $nome
                    ) ?>

                </option>


            <?php endforeach; ?>


        </select>


        <button type="submit">

            Buscar

        </button>


        <?php if (
            $pesquisa !== ""
            ||
            $categoriaAtual !==
            "todos"
        ): ?>


            <a
                href="<?= salaGlobalEscapar(
                    $linkSalaGlobal
                ) ?>"
            >

                Limpar

            </a>


        <?php endif; ?>


    </form>


</section>


<!-- =====================================================
FEED
====================================================== -->

<section class="sala-global-feed">


    <div class="sala-global-feed-topo">


        <div>


            <span class="sala-global-etiqueta">

                PUBLICAÇÕES

            </span>


            <h2>

                <?= salaGlobalEscapar(
                    $tituloFeed
                ) ?>

            </h2>


        </div>


        <span class="sala-global-contagem">

            <?= $totalPublicacoes ?>

            publicaç<?= $totalPublicacoes === 1
                ? "ão"
                : "ões"
            ?>

        </span>


    </div>


    <?php if (
        $totalPublicacoes > 0
    ): ?>


        <div class="sala-global-lista">


        <?php foreach (
            $publicacoes
            as $publicacao
        ): ?>


            <?php

            $publicacaoId =
                (int) (
                    $publicacao["id"]
                    ?? 0
                );


            $autorId =
                (int) (
                    $publicacao["usuario_id"]
                    ?? 0
                );


            $autorNome =
                trim(
                    (string) (
                        $publicacao["autor_nome"]
                        ?? "Usuário"
                    )
                );


            if (
                $autorNome === ""
            ) {

                $autorNome =
                    "Usuário";

            }


            $autorTipo =
                strtolower(
                    trim(
                        (string) (
                            $publicacao["autor_tipo"]
                            ?? ""
                        )
                    )
                );


            $categoriaPublicacao =
                strtolower(
                    trim(
                        (string) (
                            $publicacao["categoria"]
                            ?? "outro"
                        )
                    )
                );


            $tituloPublicacao =
                trim(
                    (string) (
                        $publicacao["titulo"]
                        ?? "Publicação"
                    )
                );


            if (
                $tituloPublicacao === ""
            ) {

                $tituloPublicacao =
                    "Publicação";

            }


            $conteudoPublicacao =
                trim(
                    (string) (
                        $publicacao["conteudo"]
                        ?? ""
                    )
                );


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


            $totalAjudas =
                max(
                    0,
                    (int) (
                        $publicacao["total_ajudas"]
                        ?? 0
                    )
                );


            $totalRespostas =
                max(
                    0,
                    (int) (
                        $publicacao["total_respostas"]
                        ?? 0
                    )
                );


            $usuarioMarcouAjuda =
                !empty(
                    $publicacao["usuario_marcou_ajuda"]
                );


            $ehAutor =
                (
                    $autorId > 0
                    &&
                    $autorId ===
                    $usuarioId
                );

            ?>


            <article class="sala-global-card">


                <!-- AUTOR -->

                <header class="sala-global-card-autor">


                    <div
                        class="sala-global-avatar"
                        aria-hidden="true"
                    >

                        <?= salaGlobalEscapar(
                            salaGlobalInicial(
                                $autorNome
                            )
                        ) ?>

                    </div>


                    <div>


                        <strong>

                            <?= salaGlobalEscapar(
                                $autorNome
                            ) ?>

                        </strong>


                        <div class="sala-global-autor-info">


                            <span>

                                <?= salaGlobalEscapar(
                                    $nomesPerfis[
                                        $autorTipo
                                    ]
                                    ??
                                    "Comunidade"
                                ) ?>

                            </span>


                            <span aria-hidden="true">

                                ·

                            </span>


                            <time>

                                <?= salaGlobalEscapar(
                                    salaGlobalData(
                                        $publicacao["criado_em"]
                                        ?? null
                                    )
                                ) ?>

                            </time>


                            <?php if (
                                $ehAutor
                            ): ?>


                                <span>

                                    · Sua publicação

                                </span>


                            <?php endif; ?>


                        </div>


                    </div>


                    <span class="sala-global-categoria">

                        <?= salaGlobalEscapar(
                            $categorias[
                                $categoriaPublicacao
                            ]
                            ??
                            "Outros"
                        ) ?>

                    </span>


                </header>


                <!-- CONTEÚDO -->

                <div class="sala-global-card-conteudo">


                    <h3>

                        <?= salaGlobalEscapar(
                            $tituloPublicacao
                        ) ?>

                    </h3>


                    <?php if (
                        $conteudoPublicacao !== ""
                    ): ?>


                        <p>

                            <?= nl2br(
                                salaGlobalEscapar(
                                    $conteudoPublicacao
                                )
                            ) ?>

                        </p>


                    <?php endif; ?>


                </div>


                <!-- =====================================================
                ARQUIVOS
                ====================================================== -->

                <?php if (
                    !empty(
                        $arquivos
                    )
                ): ?>


                    <div class="sala-global-anexos">


                        <strong>

                            📎 Materiais anexados

                        </strong>


                        <?php foreach (
                            $arquivos
                            as $arquivo
                        ): ?>


                            <?php

                            if (
                                !is_array(
                                    $arquivo
                                )
                            ) {

                                continue;

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | ID
                            |--------------------------------------------------------------------------
                            */

                            $arquivoId =
                                (int) (
                                    $arquivo["id"]
                                    ?? 0
                                );


                            if (
                                $arquivoId <= 0
                            ) {

                                continue;

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | NOME
                            |--------------------------------------------------------------------------
                            */

                            $nomeArquivo =
                                trim(
                                    (string) (
                                        $arquivo["nome_original"]
                                        ??
                                        "Arquivo"
                                    )
                                );


                            if (
                                $nomeArquivo === ""
                            ) {

                                $nomeArquivo =
                                    "Arquivo";

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | EXTENSÃO
                            |--------------------------------------------------------------------------
                            */

                            $extensaoArquivo =
                                strtolower(
                                    trim(
                                        (string) (
                                            $arquivo["extensao"]
                                            ??
                                            ""
                                        )
                                    )
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | TAMANHO
                            |--------------------------------------------------------------------------
                            */

                            $tamanhoArquivo =
                                salaGlobalTamanhoArquivo(
                                    (int) (
                                        $arquivo["tamanho"]
                                        ??
                                        0
                                    )
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | TIPO DE VISUALIZAÇÃO
                            |--------------------------------------------------------------------------
                            */

                            $tipoVisualizacao =
                                salaGlobalTipoVisualizacao(
                                    $extensaoArquivo
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | ÍCONE
                            |--------------------------------------------------------------------------
                            */

                            $iconeArquivo =
                                salaGlobalIconeArquivo(
                                    $extensaoArquivo
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | URL PROTEGIDA
                            |--------------------------------------------------------------------------
                            */

                            $urlArquivo =
                                rota(
                                    "sala_global.arquivo",
                                    [
                                        "id" =>
                                            $arquivoId
                                    ]
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | DOWNLOAD PROTEGIDO
                            |--------------------------------------------------------------------------
                            */

                            $urlDownload =
                                rota(
                                    "sala_global.arquivo",
                                    [
                                        "id" =>
                                            $arquivoId
                                    ],
                                    [
                                        "download" =>
                                            1
                                    ]
                                );

                            ?>


                            <a
                                href="<?= salaGlobalEscapar(
                                    $urlArquivo
                                ) ?>"
                                class="sala-global-arquivo sala-global-arquivo-abrir"
                                data-sg-arquivo
                                data-sg-url="<?= salaGlobalEscapar(
                                    $urlArquivo
                                ) ?>"
                                data-sg-download="<?= salaGlobalEscapar(
                                    $urlDownload
                                ) ?>"
                                data-sg-nome="<?= salaGlobalEscapar(
                                    $nomeArquivo
                                ) ?>"
                                data-sg-extensao="<?= salaGlobalEscapar(
                                    strtoupper(
                                        $extensaoArquivo
                                    )
                                ) ?>"
                                data-sg-tamanho="<?= salaGlobalEscapar(
                                    $tamanhoArquivo
                                ) ?>"
                                data-sg-tipo="<?= salaGlobalEscapar(
                                    $tipoVisualizacao
                                ) ?>"
                            >


                                <span aria-hidden="true">

                                    <?= salaGlobalEscapar(
                                        $iconeArquivo
                                    ) ?>

                                </span>


                                <div>


                                    <strong>

                                        <?= salaGlobalEscapar(
                                            $nomeArquivo
                                        ) ?>

                                    </strong>


                                    <small>

                                        <?= salaGlobalEscapar(
                                            strtoupper(
                                                $extensaoArquivo
                                            )
                                        ) ?>

                                        ·

                                        <?= salaGlobalEscapar(
                                            $tamanhoArquivo
                                        ) ?>

                                        · Visualizar

                                    </small>


                                </div>


                                <span
                                    class="sala-global-arquivo-seta"
                                    aria-hidden="true"
                                >

                                    ›

                                </span>


                            </a>


                        <?php endforeach; ?>


                    </div>


                <?php endif; ?>


                <!-- LINKS -->

                <?php if (
                    !empty(
                        $links
                    )
                ): ?>


                    <div class="sala-global-links">


                        <?php foreach (
                            $links
                            as $link
                        ): ?>


                            <?php

                            if (
                                !is_array(
                                    $link
                                )
                            ) {

                                continue;

                            }


                            $tipoLink =
                                strtolower(
                                    trim(
                                        (string) (
                                            $link["tipo"]
                                            ?? "link"
                                        )
                                    )
                                );


                            if (
                                !in_array(
                                    $tipoLink,
                                    [
                                        "link",
                                        "video"
                                    ],
                                    true
                                )
                            ) {

                                $tipoLink =
                                    "link";

                            }


                            $urlLink =
                                salaGlobalUrlExterna(
                                    $link["url"]
                                    ?? ""
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
                                        ?? ""
                                    )
                                );

                            ?>


                            <a
                                href="<?= salaGlobalEscapar(
                                    $urlLink
                                ) ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="sala-global-link-card"
                            >


                                <span aria-hidden="true">

                                    <?= $tipoLink === "video"
                                        ? "🎥"
                                        : "🔗"
                                    ?>

                                </span>


                                <div>


                                    <strong>

                                        <?= salaGlobalEscapar(
                                            $tituloLink !== ""
                                                ?
                                                $tituloLink
                                                :
                                                (
                                                    $tipoLink === "video"
                                                        ?
                                                        "Assistir vídeo"
                                                        :
                                                        "Acessar material"
                                                )
                                        ) ?>

                                    </strong>


                                    <small>

                                        <?= $tipoLink === "video"
                                            ? "Vídeo externo"
                                            : "Link externo"
                                        ?>

                                    </small>


                                </div>


                            </a>


                        <?php endforeach; ?>


                    </div>


                <?php endif; ?>


                <!-- AÇÕES -->

                <footer class="sala-global-card-acoes">


                    <form
                        method="POST"
                        action="<?= salaGlobalEscapar(
                            $linkSalaGlobal
                        ) ?>"
                    >


                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= salaGlobalEscapar(
                                $csrfToken
                            ) ?>"
                        >


                        <input
                            type="hidden"
                            name="acao"
                            value="ajuda"
                        >


                        <input
                            type="hidden"
                            name="publicacao_id"
                            value="<?= $publicacaoId ?>"
                        >


                        <button
                            type="submit"
                            class="<?= $usuarioMarcouAjuda
                                ? "sala-global-ajuda ativo"
                                : "sala-global-ajuda"
                            ?>"
                        >

                            <span aria-hidden="true">

                                👍

                            </span>

                            <?= $usuarioMarcouAjuda
                                ? "Ajudou"
                                : "Isso me ajudou"
                            ?>


                            <?php if (
                                $totalAjudas > 0
                            ): ?>

                                <span>

                                    <?= $totalAjudas ?>

                                </span>

                            <?php endif; ?>


                        </button>


                    </form>


                    <span class="sala-global-respostas">

                        <span aria-hidden="true">

                            💬

                        </span>

                        <?= $totalRespostas ?>

                        resposta<?= $totalRespostas === 1
                            ? ""
                            : "s"
                        ?>

                    </span>


                </footer>


            </article>


        <?php endforeach; ?>


        </div>


    <?php else: ?>


        <div class="sala-global-vazio">


            <h3>

                Nenhuma publicação encontrada

            </h3>


            <p>

                Seja uma das primeiras pessoas a compartilhar
                conhecimento com a comunidade.

            </p>


        </div>


    <?php endif; ?>


</section>


</main>


<!-- =====================================================
VISUALIZADOR DE ARQUIVOS
====================================================== -->

<dialog
    class="sala-global-visualizador"
    id="salaGlobalVisualizador"
    aria-labelledby="salaGlobalVisualizadorTitulo"
>


    <div class="sala-global-visualizador-caixa">


        <!-- CABEÇALHO -->

        <header class="sala-global-visualizador-topo">


            <div>


                <span class="sala-global-etiqueta">

                    MATERIAL DA COMUNIDADE

                </span>


                <h2 id="salaGlobalVisualizadorTitulo">

                    Visualizar arquivo

                </h2>


            </div>


            <button
                type="button"
                class="sala-global-visualizador-fechar"
                id="salaGlobalVisualizadorFechar"
                aria-label="Fechar visualizador"
            >

                ×

            </button>


        </header>


        <!-- CONTEÚDO -->

        <div class="sala-global-visualizador-conteudo">


            <!-- CARREGANDO -->

            <div
                class="sala-global-visualizador-carregando"
                id="salaGlobalVisualizadorCarregando"
            >

                <span aria-hidden="true">

                    ⏳

                </span>


                <p>

                    Carregando material...

                </p>


            </div>


            <!-- IMAGEM -->

            <img
                id="salaGlobalVisualizadorImagem"
                class="sala-global-visualizador-imagem"
                src=""
                alt=""
                hidden
            >


            <!-- PDF / TXT -->

            <iframe
                id="salaGlobalVisualizadorFrame"
                class="sala-global-visualizador-frame"
                src=""
                title="Visualização do material"
                loading="lazy"
                hidden
            ></iframe>


            <!-- ARQUIVO SEM VISUALIZAÇÃO NATIVA -->

            <div
                class="sala-global-visualizador-indisponivel"
                id="salaGlobalVisualizadorIndisponivel"
                hidden
            >


                <span aria-hidden="true">

                    📄

                </span>


                <h3>

                    Pré-visualização indisponível

                </h3>


                <p>

                    Este formato ainda não possui visualização
                    direta pelo navegador. Você pode baixar o
                    material sem sair da Sala Global.

                </p>


            </div>


        </div>


        <!-- RODAPÉ -->

        <footer class="sala-global-visualizador-rodape">


            <div>


                <strong id="salaGlobalVisualizadorNome">

                    Arquivo

                </strong>


                <small id="salaGlobalVisualizadorMeta">

                    Material da Sala Global

                </small>


            </div>


            <a
                href="#"
                class="sala-global-visualizador-download"
                id="salaGlobalVisualizadorDownload"
                download
            >

                ↓ Baixar arquivo

            </a>


        </footer>


    </div>


</dialog>


<?php

require_once __DIR__ .
    "/../../componentes/footer.php";

?>


<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


<!-- =====================================================
VISUALIZADOR DA SALA GLOBAL
====================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    () => {

        /*
        |--------------------------------------------------------------------------
        | ELEMENTOS
        |--------------------------------------------------------------------------
        */

        const dialog =
            document.getElementById(
                "salaGlobalVisualizador"
            );


        if (
            !(dialog instanceof HTMLDialogElement)
        ) {

            return;

        }


        const titulo =
            document.getElementById(
                "salaGlobalVisualizadorTitulo"
            );


        const nome =
            document.getElementById(
                "salaGlobalVisualizadorNome"
            );


        const meta =
            document.getElementById(
                "salaGlobalVisualizadorMeta"
            );


        const fechar =
            document.getElementById(
                "salaGlobalVisualizadorFechar"
            );


        const download =
            document.getElementById(
                "salaGlobalVisualizadorDownload"
            );


        const carregando =
            document.getElementById(
                "salaGlobalVisualizadorCarregando"
            );


        const imagem =
            document.getElementById(
                "salaGlobalVisualizadorImagem"
            );


        const frame =
            document.getElementById(
                "salaGlobalVisualizadorFrame"
            );


        const indisponivel =
            document.getElementById(
                "salaGlobalVisualizadorIndisponivel"
            );


        /*
        |--------------------------------------------------------------------------
        | LIMPAR VISUALIZADOR
        |--------------------------------------------------------------------------
        */

        function limparVisualizador()
        {

            /*
            |--------------------------------------------------------------------------
            | IMAGEM
            |--------------------------------------------------------------------------
            */

            if (
                imagem instanceof HTMLImageElement
            ) {

                imagem.hidden =
                    true;


                imagem.removeAttribute(
                    "src"
                );


                imagem.alt =
                    "";

            }


            /*
            |--------------------------------------------------------------------------
            | FRAME
            |--------------------------------------------------------------------------
            */

            if (
                frame instanceof HTMLIFrameElement
            ) {

                frame.hidden =
                    true;


                frame.removeAttribute(
                    "src"
                );

            }


            /*
            |--------------------------------------------------------------------------
            | INDISPONÍVEL
            |--------------------------------------------------------------------------
            */

            if (
                indisponivel instanceof HTMLElement
            ) {

                indisponivel.hidden =
                    true;

            }


            /*
            |--------------------------------------------------------------------------
            | CARREGANDO
            |--------------------------------------------------------------------------
            */

            if (
                carregando instanceof HTMLElement
            ) {

                carregando.hidden =
                    true;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | FECHAR
        |--------------------------------------------------------------------------
        */

        function fecharVisualizador()
        {

            if (
                dialog.open
            ) {

                dialog.close();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ABRIR
        |--------------------------------------------------------------------------
        */

        function abrirVisualizador(
            link
        ) {

            const url =
                link.dataset.sgUrl
                ??
                "";


            const urlDownload =
                link.dataset.sgDownload
                ??
                url;


            const nomeArquivo =
                link.dataset.sgNome
                ??
                "Arquivo";


            const extensao =
                link.dataset.sgExtensao
                ??
                "";


            const tamanho =
                link.dataset.sgTamanho
                ??
                "";


            const tipo =
                link.dataset.sgTipo
                ??
                "arquivo";


            if (
                url === ""
            ) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | LIMPA ESTADO ANTERIOR
            |--------------------------------------------------------------------------
            */

            limparVisualizador();


            /*
            |--------------------------------------------------------------------------
            | DADOS
            |--------------------------------------------------------------------------
            */

            if (
                titulo instanceof HTMLElement
            ) {

                titulo.textContent =
                    nomeArquivo;

            }


            if (
                nome instanceof HTMLElement
            ) {

                nome.textContent =
                    nomeArquivo;

            }


            if (
                meta instanceof HTMLElement
            ) {

                const dados =
                    [];


                if (
                    extensao !== ""
                ) {

                    dados.push(
                        extensao
                    );

                }


                if (
                    tamanho !== ""
                ) {

                    dados.push(
                        tamanho
                    );

                }


                meta.textContent =
                    dados.length > 0
                        ?
                        dados.join(
                            " • "
                        )
                        :
                        "Material da Sala Global";

            }


            if (
                download instanceof HTMLAnchorElement
            ) {

                download.href =
                    urlDownload;

            }


            /*
            |--------------------------------------------------------------------------
            | ABRE MODAL
            |--------------------------------------------------------------------------
            */

            if (
                !dialog.open
            ) {

                dialog.showModal();

            }


            /*
            |--------------------------------------------------------------------------
            | IMAGEM
            |--------------------------------------------------------------------------
            */

            if (
                tipo ===
                "imagem"
                &&
                imagem instanceof HTMLImageElement
            ) {

                if (
                    carregando instanceof HTMLElement
                ) {

                    carregando.hidden =
                        false;

                }


                imagem.onload =
                    () => {

                        if (
                            carregando instanceof HTMLElement
                        ) {

                            carregando.hidden =
                                true;

                        }


                        imagem.hidden =
                            false;

                    };


                imagem.onerror =
                    () => {

                        if (
                            carregando instanceof HTMLElement
                        ) {

                            carregando.hidden =
                                true;

                        }


                        if (
                            indisponivel instanceof HTMLElement
                        ) {

                            indisponivel.hidden =
                                false;

                        }

                    };


                imagem.alt =
                    nomeArquivo;


                imagem.src =
                    url;


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | PDF / TEXTO
            |--------------------------------------------------------------------------
            */

            if (
                (
                    tipo ===
                    "pdf"
                    ||
                    tipo ===
                    "texto"
                )
                &&
                frame instanceof HTMLIFrameElement
            ) {

                if (
                    carregando instanceof HTMLElement
                ) {

                    carregando.hidden =
                        false;

                }


                frame.onload =
                    () => {

                        if (
                            carregando instanceof HTMLElement
                        ) {

                            carregando.hidden =
                                true;

                        }


                        frame.hidden =
                            false;

                    };


                frame.src =
                    url;


                return;

            }


            /*
            |--------------------------------------------------------------------------
            | WORD / POWERPOINT
            |--------------------------------------------------------------------------
            */

            if (
                indisponivel instanceof HTMLElement
            ) {

                indisponivel.hidden =
                    false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CLIQUES NOS ARQUIVOS
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll(
            "[data-sg-arquivo]"
        )
        .forEach(
            elemento => {

                elemento.addEventListener(
                    "click",
                    evento => {

                        /*
                        |--------------------------------------------------------------------------
                        | Sem JavaScript o href continua funcionando como fallback.
                        |--------------------------------------------------------------------------
                        */

                        evento.preventDefault();


                        abrirVisualizador(
                            elemento
                        );

                    }
                );

            }
        );


        /*
        |--------------------------------------------------------------------------
        | BOTÃO FECHAR
        |--------------------------------------------------------------------------
        */

        if (
            fechar instanceof HTMLButtonElement
        ) {

            fechar.addEventListener(
                "click",
                fecharVisualizador
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CLIQUE NO FUNDO
        |--------------------------------------------------------------------------
        */

        dialog.addEventListener(
            "click",
            evento => {

                if (
                    evento.target ===
                    dialog
                ) {

                    fecharVisualizador();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | LIMPEZA DEPOIS DE FECHAR
        |--------------------------------------------------------------------------
        */

        dialog.addEventListener(
            "close",
            () => {

                limparVisualizador();


                if (
                    titulo instanceof HTMLElement
                ) {

                    titulo.textContent =
                        "Visualizar arquivo";

                }


                if (
                    nome instanceof HTMLElement
                ) {

                    nome.textContent =
                        "Arquivo";

                }


                if (
                    meta instanceof HTMLElement
                ) {

                    meta.textContent =
                        "Material da Sala Global";

                }


                if (
                    download instanceof HTMLAnchorElement
                ) {

                    download.href =
                        "#";

                }

            }
        );

    }
);

</script>


</body>

</html>