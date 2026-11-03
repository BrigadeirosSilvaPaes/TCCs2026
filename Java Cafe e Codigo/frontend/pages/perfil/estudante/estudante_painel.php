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


/*
|--------------------------------------------------------------------------
| VERIFICA LOGIN
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

    header(
        "Location: " .
        $linkLogin
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| DADOS DO USUÁRIO
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
        (string) (
            $usuario["nome"]
            ?? "Estudante"
        )
    );


if (
    $nomeUsuario === ""
) {

    $nomeUsuario =
        "Estudante";

}


$partesNome =
    preg_split(
        "/\s+/u",
        $nomeUsuario
    ) ?: [];


$primeiroNome =
    $partesNome[0]
    ?? "Estudante";


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
| ESCAPA HTML
|--------------------------------------------------------------------------
*/

function painelEstudanteEscapar(
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
| VERIFICA PERFIL
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
        "Não foi possível carregar seu perfil.";


    header(
        "Location: " .
        $linkEscolherPerfil
    );

    exit;

}


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
        $linkInicio
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

    $_SESSION["erro"] =
        "Complete seu perfil de estudante.";


    header(
        "Location: " .
        rota(
            "estudante.questionario"
        )
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| AULAS CONVERSADAS DISPONÍVEIS
|--------------------------------------------------------------------------
*/

$arquivosAulas =
    glob(
        __DIR__ .
        "/../../../../backend/aulas/dados/aula[0-9][0-9].php"
    ) ?: [];


/*
|--------------------------------------------------------------------------
| ORGANIZA AULAS POR ID
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
        Throwable $erroAula
    ) {

        error_log(
            "[PainelEstudante] Erro ao carregar aula: " .
            $erroAula->getMessage()
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
            ?? 0
        );


    /*
    |--------------------------------------------------------------------------
    | CASO NÃO TENHA ID
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


    $aulasConversadas[
        $aulaIdArquivo
    ] = [

        "id" =>
            $aulaIdArquivo,

        "titulo" =>
            trim(
                (string) (
                    $dadosAula["titulo"]
                    ?? "Aula Conversada"
                )
            ),

        "descricao" =>
            trim(
                (string) (
                    $dadosAula["descricao"]
                    ?? ""
                )
            )

    ];

}


/*
|--------------------------------------------------------------------------
| ORGANIZA PELO NÚMERO
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
| PROGRESSO REAL
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

        $sqlProgressoAulas = "

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

        ";


        $stmtProgressoAulas =
            $pdo->prepare(
                $sqlProgressoAulas
            );


        $stmtProgressoAulas->execute([

            ":usuario_id" =>
                $usuarioId

        ]);


        $registrosProgresso =
            $stmtProgressoAulas->fetchAll(
                PDO::FETCH_ASSOC
            );

    }
    catch (
        Throwable $erroProgresso
    ) {

        error_log(
            "[PainelEstudante] Erro ao buscar progresso: " .
            $erroProgresso->getMessage()
        );

    }

}


/*
|--------------------------------------------------------------------------
| NORMALIZA PROGRESSO POR AULA
|--------------------------------------------------------------------------
|
| Mantemos apenas o registro mais recente de cada aula.
|
|--------------------------------------------------------------------------
*/

$progressoPorAula =
    [];


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
            ?? 0
        );


    if (
        $aulaIdProgresso <= 0
        ||
        !isset(
            $aulasConversadas[
                $aulaIdProgresso
            ]
        )
    ) {

        continue;

    }


    $progressoPorAula[
        $aulaIdProgresso
    ] =
        $registro;

}


/*
|--------------------------------------------------------------------------
| CONTABILIZA PROGRESSO
|--------------------------------------------------------------------------
*/

$idsAulasConcluidas =
    [];


$aulasEmAndamento =
    0;


$aulaEmAndamento =
    null;


foreach (
    $progressoPorAula as $aulaIdProgresso => $registro
) {

    if (
        (int) (
            $registro["concluida"]
            ?? 0
        ) === 1
    ) {

        $idsAulasConcluidas[
            $aulaIdProgresso
        ] = true;


        continue;

    }


    $aulasEmAndamento++;


    $aulaEmAndamento =
        $aulasConversadas[
            $aulaIdProgresso
        ];

}


/*
|--------------------------------------------------------------------------
| AULAS CONCLUÍDAS
|--------------------------------------------------------------------------
*/

$totalAulasConcluidas =
    count(
        $idsAulasConcluidas
    );


/*
|--------------------------------------------------------------------------
| NÃO INICIADAS
|--------------------------------------------------------------------------
*/

$totalAulasNaoIniciadas =
    max(
        0,
        $totalAulasConversadas
        -
        $totalAulasConcluidas
        -
        $aulasEmAndamento
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
    $aulaEmAndamento
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
    !$aulaDestino
    &&
    !$todasAulasConcluidas
) {

    foreach (
        $aulasConversadas as $aulaDisponivel
    ) {

        $idDisponivel =
            (int) (
                $aulaDisponivel["id"]
                ?? 0
            );


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
    !$aulaDestino
    &&
    $totalAulasConversadas > 0
) {

    $aulaDestino =
        reset(
            $aulasConversadas
        );

}


/*
|--------------------------------------------------------------------------
| LINK PARA CONTINUAR
|--------------------------------------------------------------------------
*/

$linkContinuarAula =
    $aulaDestino
        ?
        rota(
            "aulas.ver",
            [
                "id" =>
                    (int) (
                        $aulaDestino["id"]
                        ?? 0
                    )
            ]
        )
        :
        rota(
            "aulas"
        );


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
        "Você concluiu todas as Aulas Conversadas disponíveis. " .
        "Pode retornar às aulas quando quiser para revisar os conceitos.";


    $textoBotaoProximoPasso =
        "Revisar aulas";

}
elseif (
    $aulaEmAndamento
) {

    $rotuloProximoPasso =
        "EM ANDAMENTO";


    $tituloProximoPasso =
        $aulaDestino["titulo"]
        ?? "Aula Conversada";


    $descricaoProximoPasso =
        "Você já iniciou esta Aula Conversada. " .
        "Continue de onde parou para avançar no conteúdo.";


    $textoBotaoProximoPasso =
        "Continuar aula";

}
else {

    $rotuloProximoPasso =
        "PRÓXIMA AULA";


    $tituloProximoPasso =
        $aulaDestino["titulo"]
        ?? "Aulas Conversadas";


    $descricaoProximoPasso =
        "Continue sua sequência de aprendizagem iniciando " .
        "a próxima Aula Conversada disponível.";


    $textoBotaoProximoPasso =
        "Começar aula";

}


/*
|--------------------------------------------------------------------------
| QUANTIDADE DE CONTEÚDOS JAVA
|--------------------------------------------------------------------------
*/

$arquivosConteudos =
    glob(
        __DIR__ .
        "/../../../conteudos/java/*.php"
    ) ?: [];


$totalConteudos =
    count(
        $arquivosConteudos
    );


/*
|--------------------------------------------------------------------------
| DISPONIBILIDADE DOS RECURSOS
|--------------------------------------------------------------------------
*/

$caminhoSalaEstudante =
    __DIR__ .
    "/salas/index.php";


$salaDisponivel =
    is_file(
        $caminhoSalaEstudante
    );


$caminhoSalaGlobal =
    __DIR__ .
    "/../../sala-global/index.php";


$salaGlobalDisponivel =
    is_file(
        $caminhoSalaGlobal
    );


$caminhoLaboratorio =
    __DIR__ .
    "/../../../laboratorio/index.php";


$laboratorioDisponivel =
    is_file(
        $caminhoLaboratorio
    );


/*
|--------------------------------------------------------------------------
| LINKS PÚBLICOS
|--------------------------------------------------------------------------
*/

$linkAulasConversadas =
    rota(
        "aulas"
    );


$linkListaAulas =
    rota(
        "aulas"
    );


$linkConteudos =
    rota(
        "conteudos"
    );


$linkCaderno =
    rota(
        "caderno"
    );


$linkLaboratorio =
    rota(
        "laboratorio"
    );


$linkPerfil =
    rota(
        "estudante.perfil"
    );


$linkSala =
    rota(
        "estudante.salas"
    );


$linkSalaGlobal =
    rota(
        "sala_global"
    );


/*
|--------------------------------------------------------------------------
| ASSETS
|--------------------------------------------------------------------------
*/

$arquivoStyle =
    __DIR__ .
    "/../../../assets/css/style.css";


$arquivoMensagemCss =
    __DIR__ .
    "/../../../assets/css/mensagem.css";


$arquivoEstudanteCss =
    __DIR__ .
    "/../../../assets/css/estudante.css";


$arquivoThemeJs =
    __DIR__ .
    "/../../../assets/js/theme.js";


$versaoStyle =
    is_file(
        $arquivoStyle
    )
        ? filemtime(
            $arquivoStyle
        )
        : 1;


$versaoMensagemCss =
    is_file(
        $arquivoMensagemCss
    )
        ? filemtime(
            $arquivoMensagemCss
        )
        : 1;


$versaoEstudanteCss =
    is_file(
        $arquivoEstudanteCss
    )
        ? filemtime(
            $arquivoEstudanteCss
        )
        : 1;


$versaoThemeJs =
    is_file(
        $arquivoThemeJs
    )
        ? filemtime(
            $arquivoThemeJs
        )
        : 1;

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
    content="Painel do estudante da plataforma Java, Café e Código."
>

<title>
    Painel do Estudante |
    <?= painelEstudanteEscapar(
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


<body class="pagina-estudante">


<?php

require_once __DIR__ .
    "/../../../componentes/header.php";


require_once __DIR__ .
    "/../../../componentes/mensagem.php";

?>


<main class="painel-estudante">


<!-- =====================================================
APRESENTAÇÃO
====================================================== -->

<section class="painel-apresentacao">


    <div class="painel-apresentacao-conteudo">


        <span class="painel-etiqueta">
            ÁREA DO ESTUDANTE
        </span>


        <h1>

            Olá,

            <strong>

                <?= painelEstudanteEscapar(
                    $primeiroNome
                ) ?>!

            </strong>

        </h1>


        <p>

            Continue sua jornada de aprendizagem em Java
            utilizando conteúdos, Aulas Conversadas,
            Laboratório Java e espaços colaborativos
            disponíveis na plataforma.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= painelEstudanteEscapar(
                    $linkAulasConversadas
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
                    href="<?= painelEstudanteEscapar(
                        $linkLaboratorio
                    ) ?>"
                    class="botao-secundario"
                >

                    Laboratório Java

                </a>


            <?php elseif (
                $salaGlobalDisponivel
            ): ?>


                <a
                    href="<?= painelEstudanteEscapar(
                        $linkSalaGlobal
                    ) ?>"
                    class="botao-secundario"
                >

                    Sala Global

                </a>


            <?php else: ?>


                <a
                    href="<?= painelEstudanteEscapar(
                        $linkConteudos
                    ) ?>"
                    class="botao-secundario"
                >

                    Conteúdos de Java

                </a>


            <?php endif; ?>


        </div>


    </div>


    <!-- =====================================================
    REPRESENTAÇÃO DE CÓDIGO
    ====================================================== -->

    <div class="painel-apresentacao-codigo">


        <div class="codigo-topo">


            <div class="codigo-controles">

                <span></span>
                <span></span>
                <span></span>

            </div>


            <strong>
                JavaCafe.java
            </strong>


            <small>
                JAVA
            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">JavaCafe</span> {

    <span class="codigo-roxo">public static void</span> <span class="codigo-amarelo">main</span>(
        String[] args
    ) {

        Aluno aluno = <span class="codigo-roxo">new</span> Aluno();

        aluno.<span class="codigo-amarelo">estudar</span>();
        aluno.<span class="codigo-amarelo">praticar</span>();
        aluno.<span class="codigo-amarelo">evoluir</span>();

    }

}</code></pre>


        <div class="codigo-status">

            <span>
                ● Plataforma disponível
            </span>

            <span>
                Java
            </span>

        </div>


    </div>


</section>


<!-- =====================================================
RECURSOS DISPONÍVEIS
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
                <?= $totalAulasConversadas ?>
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
                <?= $totalConteudos ?>
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
                LABORATÓRIO
            </small>


            <?php if (
                $laboratorioDisponivel
            ): ?>

                <strong>
                    Disponível
                </strong>

                <p>
                    Java no navegador
                </p>

            <?php else: ?>

                <strong>
                    Em breve
                </strong>

                <p>
                    recurso em implementação
                </p>

            <?php endif; ?>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">
            SG
        </span>


        <div>

            <small>
                SALA GLOBAL
            </small>


            <?php if (
                $salaGlobalDisponivel
            ): ?>

                <strong>
                    Disponível
                </strong>

                <p>
                    comunidade colaborativa
                </p>

            <?php else: ?>

                <strong>
                    Em breve
                </strong>

                <p>
                    recurso em implementação
                </p>

            <?php endif; ?>


        </div>


    </article>


</section>


<!-- =====================================================
MEU PROGRESSO
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

                <?= $percentualProgresso ?>%

            </span>


        </div>


        <div class="painel-aula-conteudo">


            <small>
                PROGRESSO GERAL
            </small>


            <h3>

                <?= $totalAulasConcluidas ?>

                de

                <?= $totalAulasConversadas ?>

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

                    <?= $aulasEmAndamento ?>

                    <?= $aulasEmAndamento === 1
                        ? "aula em andamento"
                        : "aulas em andamento"
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
                        <?= $percentualProgresso ?>%
                    </strong>


                </div>


                <progress
                    class="painel-barra-progresso"
                    value="<?= $percentualProgresso ?>"
                    max="100"
                    aria-label="Progresso das Aulas Conversadas"
                >

                    <?= $percentualProgresso ?>%

                </progress>


            </div>


            <a
                href="<?= painelEstudanteEscapar(
                    $linkListaAulas
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
                    CONTINUE ESTUDANDO
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
                    ? "✓"
                    : "→"
                ?>

            </span>


        </div>


        <div class="painel-proximo-conteudo">


            <small>

                <?= painelEstudanteEscapar(
                    $rotuloProximoPasso
                ) ?>

            </small>


            <h3>

                <?= painelEstudanteEscapar(
                    $tituloProximoPasso
                ) ?>

            </h3>


            <p>

                <?= painelEstudanteEscapar(
                    $descricaoProximoPasso
                ) ?>

            </p>


            <a
                href="<?= painelEstudanteEscapar(
                    $linkContinuarAula
                ) ?>"
                class="botao-secundario"
            >

                <?= painelEstudanteEscapar(
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
ORIENTAÇÃO
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
            conclusões no Caderno Java, participe das salas
            disponibilizadas pelos professores e utilize a
            Sala Global para trocar conhecimentos com a comunidade.

        </p>


    </div>


    <div class="painel-orientacao-etapas">


        <article>


            <span>
                01
            </span>


            <div>

                <strong>
                    Escolha como estudar
                </strong>

                <p>

                    Consulte um conteúdo ou inicie
                    uma Aula Conversada.

                </p>

            </div>


        </article>


        <article>


            <span>
                02
            </span>


            <div>

                <strong>
                    Pratique o conceito
                </strong>

                <p>

                    Escreva, compile e execute códigos
                    utilizando o Laboratório Java.

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
                    conhecimentos na Sala Global.

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


        <!-- AULAS CONVERSADAS -->

        <a
            href="<?= painelEstudanteEscapar(
                $linkAulasConversadas
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

                    Aprenda Java através de uma experiência
                    interativa e escolha o estilo de tutor
                    que prefere durante cada aula.

                </p>


            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- CONTEÚDOS -->

        <a
            href="<?= painelEstudanteEscapar(
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
                    APRENDIZAGEM
                </small>


                <h3>
                    Conteúdos de Java
                </h3>


                <p>

                    Consulte explicações e exemplos organizados
                    pelos principais assuntos da linguagem Java.

                </p>


            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- CADERNO -->

        <a
            href="<?= painelEstudanteEscapar(
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

                    Registre dúvidas, conclusões e observações
                    durante seus estudos.

                </p>


            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- LABORATÓRIO JAVA -->

        <?php if (
            $laboratorioDisponivel
        ): ?>


            <a
                href="<?= painelEstudanteEscapar(
                    $linkLaboratorio
                ) ?>"
                class="acesso-card"
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
                        PRÁTICA
                    </small>


                    <h3>
                        Laboratório Java
                    </h3>


                    <p>

                        Escreva, compile e execute programas Java
                        diretamente no navegador para colocar
                        os conceitos estudados em prática.

                    </p>


                </div>


                <strong
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </strong>


            </a>


        <?php else: ?>


            <article
                class="acesso-card acesso-indisponivel"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        &gt;_
                    </span>


                    <span class="acesso-status acesso-breve">
                        Em implementação
                    </span>


                </div>


                <div>


                    <small>
                        PRÁTICA
                    </small>


                    <h3>
                        Laboratório Java
                    </h3>


                    <p>

                        Ambiente destinado à escrita,
                        compilação e execução de programas Java.

                    </p>


                </div>


            </article>


        <?php endif; ?>


        <!-- SALA GLOBAL -->

        <?php if (
            $salaGlobalDisponivel
        ): ?>


            <a
                href="<?= painelEstudanteEscapar(
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

                        Compartilhe conhecimentos, materiais,
                        dúvidas, códigos, vídeos e experiências
                        com estudantes, aprendizes e professores.

                    </p>


                </div>


                <strong
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </strong>


            </a>


        <?php else: ?>


            <article
                class="acesso-card acesso-indisponivel"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        SG
                    </span>


                    <span class="acesso-status acesso-breve">
                        Em implementação
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

                        Espaço colaborativo para troca
                        de conhecimentos entre a comunidade.

                    </p>


                </div>


            </article>


        <?php endif; ?>


        <!-- SALA DE AULA -->

        <?php if (
            $salaDisponivel
        ): ?>


            <a
                href="<?= painelEstudanteEscapar(
                    $linkSala
                ) ?>"
                class="acesso-card"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        SL
                    </span>


                    <span class="acesso-status acesso-disponivel">
                        Disponível
                    </span>


                </div>


                <div>


                    <small>
                        TURMA
                    </small>


                    <h3>
                        Sala de Aula
                    </h3>


                    <p>

                        Entre em uma sala utilizando o código
                        fornecido pelo professor e acompanhe
                        as atividades e orientações da turma.

                    </p>


                </div>


                <strong
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </strong>


            </a>


        <?php else: ?>


            <article
                class="acesso-card acesso-indisponivel"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        SL
                    </span>


                    <span class="acesso-status acesso-breve">
                        Em implementação
                    </span>


                </div>


                <div>


                    <small>
                        TURMA
                    </small>


                    <h3>
                        Sala de Aula
                    </h3>


                    <p>

                        Ambiente privado destinado à integração
                        entre estudantes e professores.

                    </p>


                </div>


            </article>


        <?php endif; ?>


        <!-- PERFIL -->

        <a
            href="<?= painelEstudanteEscapar(
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

                    Consulte seus dados e informações
                    cadastradas na plataforma.

                </p>


            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- EXERCÍCIOS -->

        <article
            class="acesso-card acesso-indisponivel"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    ?
                </span>


                <span class="acesso-status acesso-breve">
                    Em desenvolvimento
                </span>


            </div>


            <div>


                <small>
                    PRÁTICA
                </small>


                <h3>
                    Exercícios
                </h3>


                <p>

                    Espaço destinado à prática e revisão
                    dos conceitos estudados.

                </p>


            </div>


        </article>


    </div>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../../componentes/footer.php";

?>


<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


</body>

</html>