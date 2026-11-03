<?php

/*
|--------------------------------------------------------------------------
| PAINEL DO APRENDIZ
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Painel principal do perfil Aprendiz.
|
| Recursos apresentados:
|
| - Aulas Conversadas;
| - Conteúdos Java;
| - Laboratório Java;
| - Caderno Java;
| - Sala Global;
| - Manual;
| - Perfil;
| - orientações sobre a plataforma.
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

function aprendizPainelRedirecionar(
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

function aprendizPainelEscapar(
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


    aprendizPainelRedirecionar(
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


    aprendizPainelRedirecionar(
        "login"
    );

}


$usuarioId =
    (int) $usuarioId;


/*
|--------------------------------------------------------------------------
| NOME
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


/*
|--------------------------------------------------------------------------
| PRIMEIRO NOME
|--------------------------------------------------------------------------
*/

$partesNome =
    preg_split(
        "/\s+/u",
        $nomeUsuario,
        -1,
        PREG_SPLIT_NO_EMPTY
    );


$primeiroNome =
    is_array(
        $partesNome
    )
    &&
    isset(
        $partesNome[0]
    )
        ?
        $partesNome[0]
        :
        "Aprendiz";


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
        "[PainelAprendiz] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    aprendizPainelRedirecionar(
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


    aprendizPainelRedirecionar(
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


    aprendizPainelRedirecionar(
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


    aprendizPainelRedirecionar(
        "aprendiz.questionario"
    );

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


if (
    $objetivo === ""
) {

    $objetivo =
        "Nenhum objetivo específico foi informado.";

}


/*
|--------------------------------------------------------------------------
| AULAS CONVERSADAS DISPONÍVEIS
|--------------------------------------------------------------------------
*/

$arquivosAulas =
    glob(
        __DIR__
        .
        "/../../../../backend/aulas/dados/aula[0-9][0-9].php"
    )
    ?:
    [];


/*
|--------------------------------------------------------------------------
| ORGANIZA AULAS
|--------------------------------------------------------------------------
*/

$aulasConversadas =
    [];


foreach (
    $arquivosAulas as $arquivoAula
) {

    try {

        $dadosAula =
            require $arquivoAula;

    }
    catch (
        Throwable $erro
    ) {

        error_log(
            "[PainelAprendiz] Erro ao carregar aula "
            .
            basename(
                $arquivoAula
            )
            .
            ": "
            .
            $erro->getMessage()
        );


        continue;

    }


    if (
        !is_array(
            $dadosAula
        )
    ) {

        continue;

    }


    $aulaIdArquivo =
        (int) (
            $dadosAula["id"]
            ??
            0
        );


    /*
    |--------------------------------------------------------------------------
    | ID PELO ARQUIVO
    |--------------------------------------------------------------------------
    */

    if (
        $aulaIdArquivo <= 0
    ) {

        $nomeArquivo =
            basename(
                $arquivoAula
            );


        if (
            preg_match(
                "/^aula([0-9]{2})\.php$/i",
                $nomeArquivo,
                $resultadoNumero
            )
            === 1
        ) {

            $aulaIdArquivo =
                (int) $resultadoNumero[1];

        }

    }


    if (
        $aulaIdArquivo <= 0
    ) {

        continue;

    }


    $tituloAula =
        trim(
            (string) (
                $dadosAula["titulo"]
                ??
                "Aula Conversada"
            )
        );


    if (
        $tituloAula === ""
    ) {

        $tituloAula =
            "Aula Conversada";

    }


    $aulasConversadas[
        $aulaIdArquivo
    ] = [

        "id" =>
            $aulaIdArquivo,

        "titulo" =>
            $tituloAula,

        "descricao" =>
            trim(
                (string) (
                    $dadosAula["descricao"]
                    ??
                    ""
                )
            )

    ];

}


/*
|--------------------------------------------------------------------------
| ORDENA AULAS
|--------------------------------------------------------------------------
*/

ksort(
    $aulasConversadas,
    SORT_NUMERIC
);


$totalAulasConversadas =
    count(
        $aulasConversadas
    );


/*
|--------------------------------------------------------------------------
| PROGRESSO
|--------------------------------------------------------------------------
*/

$registrosProgresso =
    [];


if (
    isset(
        $pdo
    )
    &&
    $pdo instanceof PDO
) {

    try {

        $stmtProgresso =
            $pdo->prepare(
                "

                SELECT

                    id,
                    aula_id,
                    etapa_atual,
                    concluida

                FROM aula_progresso_conversado

                WHERE
                    usuario_id = :usuario_id

                ORDER BY
                    id ASC

                "
            );


        $stmtProgresso->execute(
            [
                ":usuario_id" =>
                    $usuarioId
            ]
        );


        $registrosProgresso =
            $stmtProgresso->fetchAll(
                PDO::FETCH_ASSOC
            );

    }
    catch (
        Throwable $erro
    ) {

        error_log(
            "[PainelAprendiz] Erro ao buscar progresso do usuário "
            .
            $usuarioId
            .
            ": "
            .
            $erro->getMessage()
        );

    }

}


/*
|--------------------------------------------------------------------------
| CONTABILIZA PROGRESSO
|--------------------------------------------------------------------------
*/

$idsAulasConcluidas =
    [];


$idsAulasEmAndamento =
    [];


$aulaEmAndamento =
    null;


/*
|--------------------------------------------------------------------------
| REGISTROS
|--------------------------------------------------------------------------
*/

foreach (
    $registrosProgresso as $registro
) {

    if (
        !is_array(
            $registro
        )
    ) {

        continue;

    }


    $aulaIdProgresso =
        (int) (
            $registro["aula_id"]
            ??
            0
        );


    if (
        !isset(
            $aulasConversadas[
                $aulaIdProgresso
            ]
        )
    ) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | CONCLUÍDA
    |--------------------------------------------------------------------------
    */

    if (
        (int) (
            $registro["concluida"]
            ??
            0
        )
        === 1
    ) {

        $idsAulasConcluidas[
            $aulaIdProgresso
        ] =
            true;


        unset(
            $idsAulasEmAndamento[
                $aulaIdProgresso
            ]
        );


        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | EM ANDAMENTO
    |--------------------------------------------------------------------------
    */

    if (
        !isset(
            $idsAulasConcluidas[
                $aulaIdProgresso
            ]
        )
    ) {

        $idsAulasEmAndamento[
            $aulaIdProgresso
        ] =
            true;


        /*
        |--------------------------------------------------------------------------
        | PRIMEIRA AULA EM ANDAMENTO
        |--------------------------------------------------------------------------
        */

        if (
            $aulaEmAndamento ===
            null
        ) {

            $aulaEmAndamento =
                $aulasConversadas[
                    $aulaIdProgresso
                ];

        }

    }

}


/*
|--------------------------------------------------------------------------
| TOTAIS
|--------------------------------------------------------------------------
*/

$totalAulasConcluidas =
    count(
        $idsAulasConcluidas
    );


$aulasEmAndamento =
    count(
        $idsAulasEmAndamento
    );


/*
|--------------------------------------------------------------------------
| PERCENTUAL
|--------------------------------------------------------------------------
*/

$percentualProgresso =
    $totalAulasConversadas > 0
        ?
        (int) round(
            (
                $totalAulasConcluidas
                /
                $totalAulasConversadas
            )
            *
            100
        )
        :
        0;


/*
|--------------------------------------------------------------------------
| LIMITA PERCENTUAL
|--------------------------------------------------------------------------
*/

$percentualProgresso =
    max(
        0,
        min(
            100,
            $percentualProgresso
        )
    );


/*
|--------------------------------------------------------------------------
| TODAS CONCLUÍDAS
|--------------------------------------------------------------------------
*/

$todasAulasConcluidas =
    (
        $totalAulasConversadas > 0
        &&
        $totalAulasConcluidas
        >=
        $totalAulasConversadas
    );


/*
|--------------------------------------------------------------------------
| AULA DESTINO
|--------------------------------------------------------------------------
*/

$aulaDestino =
    null;


/*
|--------------------------------------------------------------------------
| PRIMEIRO: EM ANDAMENTO
|--------------------------------------------------------------------------
*/

if (
    $aulaEmAndamento !==
    null
) {

    $aulaDestino =
        $aulaEmAndamento;

}


/*
|--------------------------------------------------------------------------
| SEGUNDO: PRIMEIRA NÃO CONCLUÍDA
|--------------------------------------------------------------------------
*/

if (
    $aulaDestino ===
    null
    &&
    !$todasAulasConcluidas
) {

    foreach (
        $aulasConversadas as $aulaDisponivel
    ) {

        $idDisponivel =
            (int) (
                $aulaDisponivel["id"]
                ??
                0
            );


        if (
            $idDisponivel <= 0
        ) {

            continue;

        }


        if (
            isset(
                $idsAulasConcluidas[
                    $idDisponivel
                ]
            )
        ) {

            continue;

        }


        $aulaDestino =
            $aulaDisponivel;


        break;

    }

}


/*
|--------------------------------------------------------------------------
| REVISÃO
|--------------------------------------------------------------------------
*/

if (
    $aulaDestino ===
    null
    &&
    $totalAulasConversadas > 0
) {

    $primeiraAula =
        reset(
            $aulasConversadas
        );


    if (
        is_array(
            $primeiraAula
        )
    ) {

        $aulaDestino =
            $primeiraAula;

    }

}


/*
|--------------------------------------------------------------------------
| LINKS
|--------------------------------------------------------------------------
*/

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


$linkManual =
    rota(
        "manual"
    );


$linkCaderno =
    rota(
        "caderno"
    );


$linkSalaGlobal =
    rota(
        "sala_global"
    );


$linkComoFunciona =
    rota(
        "aprendiz.como_funciona"
    );


$linkPerfil =
    rota(
        "aprendiz.perfil"
    );


/*
|--------------------------------------------------------------------------
| CONTINUAR AULA
|--------------------------------------------------------------------------
*/

if (
    is_array(
        $aulaDestino
    )
    &&
    isset(
        $aulaDestino["id"]
    )
    &&
    (int) $aulaDestino["id"] > 0
) {

    $linkContinuarAula =
        rota(
            "aulas.ver",
            [
                "id" =>
                    (int) $aulaDestino["id"]
            ]
        );

}
else {

    $linkContinuarAula =
        $linkAulas;

}


/*
|--------------------------------------------------------------------------
| PRÓXIMO PASSO
|--------------------------------------------------------------------------
*/

if (
    $todasAulasConcluidas
) {

    $rotuloProximoPasso =
        "REVISÃO";


    $tituloProximoPasso =
        "Todas as aulas concluídas";


    $descricaoProximoPasso =
        "Você concluiu todas as Aulas Conversadas disponíveis. "
        .
        "Pode retornar às aulas para revisar os conceitos.";


    $textoBotaoProximoPasso =
        "Revisar aulas";

}
elseif (
    $aulaEmAndamento !==
    null
) {

    $rotuloProximoPasso =
        "EM ANDAMENTO";


    $tituloProximoPasso =
        (string) (
            $aulaDestino["titulo"]
            ??
            "Aula Conversada"
        );


    $descricaoProximoPasso =
        "Você já iniciou esta Aula Conversada. "
        .
        "Continue de onde parou.";


    $textoBotaoProximoPasso =
        "Continuar aula";

}
else {

    $rotuloProximoPasso =
        "PRÓXIMA AULA";


    $tituloProximoPasso =
        (string) (
            $aulaDestino["titulo"]
            ??
            "Aulas Conversadas"
        );


    $descricaoProximoPasso =
        "Continue sua jornada iniciando a próxima "
        .
        "Aula Conversada disponível.";


    $textoBotaoProximoPasso =
        "Começar aula";

}


/*
|--------------------------------------------------------------------------
| CONTEÚDOS JAVA
|--------------------------------------------------------------------------
*/

$arquivosConteudos =
    glob(
        __DIR__
        .
        "/../../../conteudos/java/*.php"
    )
    ?:
    [];


$totalConteudos =
    count(
        $arquivosConteudos
    );


/*
|--------------------------------------------------------------------------
| DISPONIBILIDADE DAS ROTAS
|--------------------------------------------------------------------------
*/

$laboratorioDisponivel =
    rotaExiste(
        "laboratorio"
    );


$manualDisponivel =
    rotaExiste(
        "manual"
    );


$salaGlobalDisponivel =
    rotaExiste(
        "sala_global"
    );


$comoFuncionaDisponivel =
    rotaExiste(
        "aprendiz.como_funciona"
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


$caminhoMensagem =
    __DIR__
    .
    "/../../../assets/css/mensagem.css";


$caminhoPainel =
    __DIR__
    .
    "/../../../assets/css/estudante.css";


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


$versaoPainel =
    is_file(
        $caminhoPainel
    )
        ?
        (string) filemtime(
            $caminhoPainel
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
    content="Painel do Aprendiz da plataforma Java, Café e Código."
>

<title>
    Painel do Aprendiz | <?= aprendizPainelEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= aprendizPainelEscapar(
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
MENSAGENS
====================================================== -->

<link
    rel="stylesheet"
    href="<?= aprendizPainelEscapar(
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
PAINEL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= aprendizPainelEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/estudante.css?v="
        .
        rawurlencode(
            $versaoPainel
        )
    ) ?>"
>

</head>


<body class="pagina-estudante pagina-aprendiz">


<?php

require_once __DIR__
    .
    "/../../../componentes/header.php";


require_once __DIR__
    .
    "/../../../componentes/mensagem.php";

?>


<main class="painel-estudante">


<!-- =====================================================
APRESENTAÇÃO
====================================================== -->

<section class="painel-apresentacao">


    <div class="painel-apresentacao-conteudo">


        <span class="painel-etiqueta">
            ÁREA DO APRENDIZ
        </span>


        <h1>

            Olá,

            <strong>
                <?= aprendizPainelEscapar(
                    $primeiroNome
                ) ?>!
            </strong>

        </h1>


        <p>

            Desenvolva seus conhecimentos em Java no seu
            próprio ritmo utilizando conteúdos, Aulas Conversadas
            e ferramentas de prática e apoio disponíveis
            na plataforma.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= aprendizPainelEscapar(
                    $linkAulas
                ) ?>"
                class="botao-principal"
            >

                Aulas Conversadas

                <span aria-hidden="true">
                    →
                </span>

            </a>


            <?php if (
                $laboratorioDisponivel
            ): ?>

                <a
                    href="<?= aprendizPainelEscapar(
                        $linkLaboratorio
                    ) ?>"
                    class="botao-secundario"
                >

                    Laboratório Java

                </a>

            <?php endif; ?>


            <?php if (
                $comoFuncionaDisponivel
            ): ?>

                <a
                    href="<?= aprendizPainelEscapar(
                        $linkComoFunciona
                    ) ?>"
                    class="botao-secundario"
                >

                    Como funciona

                </a>

            <?php endif; ?>


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
                Aprendiz.java
            </strong>


            <small>
                JAVA
            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">Aprendiz</span> {

    <span class="codigo-roxo">public void</span> <span class="codigo-amarelo">aprender</span>() {

        estudar();
        praticar();
        experimentar();
        compartilhar();
        evoluir();

    }

}</code></pre>


        <div class="codigo-status">


            <span>
                ● Plataforma disponível
            </span>


            <span>
                Aprendiz
            </span>


        </div>


    </div>


</section>


<!-- =====================================================
ESTATÍSTICAS
====================================================== -->

<section class="painel-estatisticas">


    <article>


        <span class="estatistica-icone">
            IA
        </span>


        <div>


            <small>
                AULAS CONVERSADAS
            </small>


            <strong>
                <?= aprendizPainelEscapar(
                    $totalAulasConversadas
                ) ?>
            </strong>


            <p>
                aulas disponíveis
            </p>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">
            &lt;/&gt;
        </span>


        <div>


            <small>
                CONTEÚDOS JAVA
            </small>


            <strong>
                <?= aprendizPainelEscapar(
                    $totalConteudos
                ) ?>
            </strong>


            <p>
                assuntos disponíveis
            </p>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">
            &gt;_
        </span>


        <div>


            <small>
                LABORATÓRIO JAVA
            </small>


            <?php if (
                $laboratorioDisponivel
            ): ?>


                <strong>
                    Disponível
                </strong>


                <p>
                    prática de código Java
                </p>


            <?php else: ?>


                <strong>
                    Indisponível
                </strong>


                <p>
                    recurso não encontrado
                </p>


            <?php endif; ?>


        </div>


    </article>


</section>


<!-- =====================================================
PROGRESSO
====================================================== -->

<section class="painel-estudo">


    <article class="painel-ultima-aula">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">
                    MEU PROGRESSO
                </span>


                <h2>
                    Aulas Conversadas
                </h2>


            </div>


            <span class="painel-card-numero">

                <?= aprendizPainelEscapar(
                    $percentualProgresso
                ) ?>%

            </span>


        </div>


        <div class="painel-aula-conteudo">


            <small>
                PROGRESSO GERAL
            </small>


            <h3>

                <?= aprendizPainelEscapar(
                    $totalAulasConcluidas
                ) ?>

                de

                <?= aprendizPainelEscapar(
                    $totalAulasConversadas
                ) ?>

                aulas concluídas

            </h3>


            <p>


                <?php if (
                    $todasAulasConcluidas
                ): ?>


                    Você concluiu todas as Aulas Conversadas
                    disponíveis na plataforma.


                <?php elseif (
                    $aulasEmAndamento > 0
                ): ?>


                    Você possui

                    <?= aprendizPainelEscapar(
                        $aulasEmAndamento
                    ) ?>

                    <?= $aulasEmAndamento === 1
                        ?
                        "aula em andamento"
                        :
                        "aulas em andamento"
                    ?>.


                <?php elseif (
                    $totalAulasConcluidas > 0
                ): ?>


                    Continue avançando pelas Aulas Conversadas
                    para ampliar seu progresso.


                <?php else: ?>


                    Você ainda não concluiu nenhuma Aula Conversada.
                    Comece pela primeira aula disponível.


                <?php endif; ?>


            </p>


            <div class="painel-aula-progresso">


                <div>


                    <span>
                        Concluído
                    </span>


                    <strong>
                        <?= aprendizPainelEscapar(
                            $percentualProgresso
                        ) ?>%
                    </strong>


                </div>


                <progress
                    class="painel-barra-progresso"
                    value="<?= aprendizPainelEscapar(
                        $percentualProgresso
                    ) ?>"
                    max="100"
                    aria-label="Progresso das Aulas Conversadas"
                >

                    <?= aprendizPainelEscapar(
                        $percentualProgresso
                    ) ?>%

                </progress>


            </div>


            <a
                href="<?= aprendizPainelEscapar(
                    $linkAulas
                ) ?>"
                class="botao-aula"
            >

                Ver todas as aulas

            </a>


        </div>


    </article>


    <!-- =================================================
    PRÓXIMO PASSO
    ================================================== -->

    <article class="painel-proximo-passo">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">
                    CONTINUE APRENDENDO
                </span>


                <h2>
                    Próximo passo
                </h2>


            </div>


            <span
                class="painel-proximo-icone"
                aria-hidden="true"
            >

                <?= $todasAulasConcluidas
                    ?
                    "✓"
                    :
                    "→"
                ?>

            </span>


        </div>


        <div class="painel-proximo-conteudo">


            <small>
                <?= aprendizPainelEscapar(
                    $rotuloProximoPasso
                ) ?>
            </small>


            <h3>
                <?= aprendizPainelEscapar(
                    $tituloProximoPasso
                ) ?>
            </h3>


            <p>
                <?= aprendizPainelEscapar(
                    $descricaoProximoPasso
                ) ?>
            </p>


            <a
                href="<?= aprendizPainelEscapar(
                    $linkContinuarAula
                ) ?>"
                class="botao-secundario"
            >

                <?= aprendizPainelEscapar(
                    $textoBotaoProximoPasso
                ) ?>

                <span aria-hidden="true">
                    →
                </span>

            </a>


        </div>


    </article>


</section>


<!-- =====================================================
PERFIL DE APRENDIZAGEM
====================================================== -->

<section class="painel-estudo">


    <article class="painel-ultima-aula">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">
                    MEU PERFIL
                </span>


                <h2>
                    Experiência atual
                </h2>


            </div>


            <span
                class="painel-proximo-icone"
                aria-hidden="true"
            >
                &lt;/&gt;
            </span>


        </div>


        <div class="painel-aula-conteudo">


            <small>
                EXPERIÊNCIA COM PROGRAMAÇÃO
            </small>


            <h3>
                <?= aprendizPainelEscapar(
                    $experienciaFormatada
                ) ?>
            </h3>


            <p>

                Essa informação foi definida durante
                a configuração inicial do seu perfil
                como Aprendiz.

            </p>


        </div>


    </article>


    <article class="painel-proximo-passo">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">
                    OBJETIVO
                </span>


                <h2>
                    O que quero aprender
                </h2>


            </div>


            <span
                class="painel-proximo-icone"
                aria-hidden="true"
            >
                →
            </span>


        </div>


        <div class="painel-proximo-conteudo">


            <p>

                <?= nl2br(
                    aprendizPainelEscapar(
                        $objetivo
                    )
                ) ?>

            </p>


        </div>


    </article>


</section>


<!-- =====================================================
COMO UTILIZAR
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">
            SUA JORNADA
        </span>


        <h2>
            Como utilizar a plataforma
        </h2>


        <p>

            Combine conteúdos teóricos com as Aulas Conversadas,
            pratique códigos no Laboratório Java, registre suas
            conclusões no Caderno Java e utilize a Sala Global
            para trocar conhecimentos com a comunidade.

        </p>


        <?php if (
            $comoFuncionaDisponivel
        ): ?>


            <a
                href="<?= aprendizPainelEscapar(
                    $linkComoFunciona
                ) ?>"
                class="botao-secundario"
            >

                Entender todos os recursos

                <span aria-hidden="true">
                    →
                </span>

            </a>


        <?php endif; ?>


    </div>


    <div class="painel-orientacao-etapas">


        <article>


            <span>
                01
            </span>


            <div>


                <strong>
                    Estude
                </strong>


                <p>

                    Consulte os conteúdos disponíveis
                    ou participe de uma Aula Conversada.

                </p>


            </div>


        </article>


        <article>


            <span>
                02
            </span>


            <div>


                <strong>
                    Pratique
                </strong>


                <p>

                    Utilize o Laboratório Java para
                    escrever, compilar e executar códigos.

                </p>


            </div>


        </article>


        <article>


            <span>
                03
            </span>


            <div>


                <strong>
                    Registre e compartilhe
                </strong>


                <p>

                    Utilize o Caderno Java e compartilhe
                    conhecimentos através da Sala Global.

                </p>


            </div>


        </article>


    </div>


</section>


<!-- =====================================================
ACESSOS RÁPIDOS
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <div>


            <span class="painel-etiqueta">
                ACESSOS RÁPIDOS
            </span>


            <h2>
                Recursos da plataforma
            </h2>


            <p>
                Escolha o espaço que deseja acessar.
            </p>


        </div>


    </div>


    <div class="painel-acessos-grid">


        <!-- =================================================
        AULAS CONVERSADAS
        ================================================== -->

        <a
            href="<?= aprendizPainelEscapar(
                $linkAulas
            ) ?>"
            class="acesso-card acesso-principal"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    IA
                </span>


                <span class="acesso-status acesso-disponivel">
                    Disponível
                </span>


            </div>


            <div>


                <small>
                    APRENDIZAGEM INTERATIVA
                </small>


                <h3>
                    Aulas Conversadas
                </h3>


                <p>

                    Aprenda conceitos de Java através
                    de uma experiência interativa com
                    diferentes estilos de explicação.

                </p>


            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =================================================
        CONTEÚDOS
        ================================================== -->

        <a
            href="<?= aprendizPainelEscapar(
                $linkConteudos
            ) ?>"
            class="acesso-card"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    &lt;/&gt;
                </span>


                <span class="acesso-status acesso-disponivel">
                    Disponível
                </span>


            </div>


            <div>


                <small>
                    CONSULTA
                </small>


                <h3>
                    Conteúdos de Java
                </h3>


                <p>

                    Consulte conceitos, explicações
                    e exemplos sobre os principais
                    assuntos da linguagem Java.

                </p>


            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =================================================
        LABORATÓRIO JAVA
        ================================================== -->

        <?php if (
            $laboratorioDisponivel
        ): ?>


            <a
                href="<?= aprendizPainelEscapar(
                    $linkLaboratorio
                ) ?>"
                class="acesso-card acesso-principal"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        &gt;_
                    </span>


                    <span class="acesso-status acesso-disponivel">
                        Disponível
                    </span>


                </div>


                <div>


                    <small>
                        PRÁTICA DE PROGRAMAÇÃO
                    </small>


                    <h3>
                        Laboratório Java
                    </h3>


                    <p>

                        Escreva, compile e execute
                        códigos Java diretamente
                        na plataforma.

                    </p>


                </div>


                <strong
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </strong>


            </a>


        <?php endif; ?>


        <!-- =================================================
        COMO FUNCIONA
        ================================================== -->

        <?php if (
            $comoFuncionaDisponivel
        ): ?>


            <a
                href="<?= aprendizPainelEscapar(
                    $linkComoFunciona
                ) ?>"
                class="acesso-card"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        i
                    </span>


                    <span class="acesso-status acesso-disponivel">
                        Disponível
                    </span>


                </div>


                <div>


                    <small>
                        ORIENTAÇÃO
                    </small>


                    <h3>
                        Como funciona
                    </h3>


                    <p>

                        Conheça os recursos disponíveis
                        para o perfil Aprendiz e entenda
                        como utilizar cada ferramenta.

                    </p>


                </div>


                <strong
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </strong>


            </a>


        <?php endif; ?>


        <!-- =================================================
        MANUAL
        ================================================== -->

        <?php if (
            $manualDisponivel
        ): ?>


            <a
                href="<?= aprendizPainelEscapar(
                    $linkManual
                ) ?>"
                class="acesso-card"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        ?
                    </span>


                    <span class="acesso-status acesso-disponivel">
                        Disponível
                    </span>


                </div>


                <div>


                    <small>
                        CONSULTA
                    </small>


                    <h3>
                        Manual
                    </h3>


                    <p>

                        Consulte orientações e informações
                        disponíveis no manual da plataforma.

                    </p>


                </div>


                <strong
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </strong>


            </a>


        <?php endif; ?>


        <!-- =================================================
        CADERNO JAVA
        ================================================== -->

        <a
            href="<?= aprendizPainelEscapar(
                $linkCaderno
            ) ?>"
            class="acesso-card"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    { }
                </span>


                <span class="acesso-status acesso-disponivel">
                    Disponível
                </span>


            </div>


            <div>


                <small>
                    ANOTAÇÕES
                </small>


                <h3>
                    Meu Caderno Java
                </h3>


                <p>

                    Registre dúvidas, observações
                    e conclusões durante sua aprendizagem.

                </p>


            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =================================================
        SALA GLOBAL
        ================================================== -->

        <?php if (
            $salaGlobalDisponivel
        ): ?>


            <a
                href="<?= aprendizPainelEscapar(
                    $linkSalaGlobal
                ) ?>"
                class="acesso-card"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        SG
                    </span>


                    <span class="acesso-status acesso-disponivel">
                        Disponível
                    </span>


                </div>


                <div>


                    <small>
                        COMUNIDADE
                    </small>


                    <h3>
                        Sala Global
                    </h3>


                    <p>

                        Compartilhe conhecimentos,
                        dúvidas, códigos, materiais e
                        experiências com a comunidade
                        da plataforma.

                    </p>


                </div>


                <strong
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </strong>


            </a>


        <?php endif; ?>


        <!-- =================================================
        PERFIL
        ================================================== -->

        <a
            href="<?= aprendizPainelEscapar(
                $linkPerfil
            ) ?>"
            class="acesso-card"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    ID
                </span>


                <span class="acesso-status acesso-disponivel">
                    Disponível
                </span>


            </div>


            <div>


                <small>
                    CONTA
                </small>


                <h3>
                    Meu Perfil
                </h3>


                <p>

                    Consulte suas informações,
                    experiência e objetivo cadastrados
                    na plataforma.

                </p>


            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


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
    src="<?= aprendizPainelEscapar(
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