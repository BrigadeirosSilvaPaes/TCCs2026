<?php

/*
|--------------------------------------------------------------------------
| PAINEL DO PROFESSOR
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Área principal do perfil Professor.
|
| O professor pode utilizar normalmente:
|
| - Aulas Conversadas;
| - Conteúdos Java;
| - Laboratório;
| - Caderno;
| - Sala Global;
| - Manual;
| - Assistente Java.
|
| A aprovação administrativa é necessária somente para recursos
| específicos do Professor, como criação e gerenciamento de Salas de Aula.
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
| NOME
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


if (
    $nomeUsuario === ""
) {

    $nomeUsuario =
        "Professor";

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
        "Professor";


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
        "[PainelProfessor] Erro ao carregar perfil do usuário "
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
            "painel"
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
        "Instituição não informada";

}


if (
    $disciplina === ""
) {

    $disciplina =
        "Área não informada";

}


/*
|--------------------------------------------------------------------------
| AULAS CONVERSADAS
|--------------------------------------------------------------------------
*/

$arquivosAulas =
    glob(
        __DIR__
        .
        "/../../../../backend/aulas/dados/aula[0-9][0-9].php"
    );


if (
    !is_array(
        $arquivosAulas
    )
) {

    $arquivosAulas =
        [];

}


$totalAulas =
    count(
        $arquivosAulas
    );


/*
|--------------------------------------------------------------------------
| PROGRESSO
|--------------------------------------------------------------------------
*/

$aulasConcluidas =
    0;


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
                    COUNT(*) AS total

                FROM aula_progresso_conversado

                WHERE
                    usuario_id = :usuario_id

                    AND concluida = 1

                "
            );


        $stmtProgresso->execute(
            [
                ":usuario_id" =>
                    $usuarioId
            ]
        );


        $resultadoProgresso =
            $stmtProgresso->fetch(
                PDO::FETCH_ASSOC
            );


        if (
            is_array(
                $resultadoProgresso
            )
        ) {

            $aulasConcluidas =
                max(
                    0,
                    (int) (
                        $resultadoProgresso["total"]
                        ??
                        0
                    )
                );

        }

    }
    catch (
        Throwable $erro
    ) {

        error_log(
            "[PainelProfessor] Erro ao buscar progresso do usuário "
            .
            $usuarioId
            .
            ": "
            .
            $erro->getMessage()
        );

    }

}
else {

    error_log(
        "[PainelProfessor] PDO indisponível ao consultar progresso."
    );

}


/*
|--------------------------------------------------------------------------
| LIMITA PROGRESSO
|--------------------------------------------------------------------------
*/

if (
    $totalAulas > 0
) {

    $aulasConcluidas =
        min(
            $aulasConcluidas,
            $totalAulas
        );

}
else {

    $aulasConcluidas =
        0;

}


/*
|--------------------------------------------------------------------------
| PORCENTAGEM
|--------------------------------------------------------------------------
*/

$porcentagemProgresso =
    $totalAulas > 0
        ?
        (int) round(
            (
                $aulasConcluidas
                /
                $totalAulas
            )
            *
            100
        )
        :
        0;


$porcentagemProgresso =
    max(
        0,
        min(
            100,
            $porcentagemProgresso
        )
    );


/*
|--------------------------------------------------------------------------
| TODAS CONCLUÍDAS
|--------------------------------------------------------------------------
*/

$todasAulasConcluidas =
    (
        $totalAulas > 0
        &&
        $aulasConcluidas >=
            $totalAulas
    );


/*
|--------------------------------------------------------------------------
| PRÓXIMA AULA
|--------------------------------------------------------------------------
*/

$proximaAula =
    max(
        1,
        $aulasConcluidas + 1
    );


if (
    $totalAulas > 0
) {

    $proximaAula =
        min(
            $proximaAula,
            $totalAulas
        );

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
    );


if (
    !is_array(
        $arquivosConteudos
    )
) {

    $arquivosConteudos =
        [];

}


$totalConteudos =
    count(
        $arquivosConteudos
    );


/*
|--------------------------------------------------------------------------
| LINKS
|--------------------------------------------------------------------------
|
| Todos os links de navegação utilizam agora o catálogo central.
|
|--------------------------------------------------------------------------
*/

$linkAulas =
    rota(
        "aulas"
    );


$linkProximaAula =
    (
        $totalAulas <= 0
        ||
        $todasAulasConcluidas
    )
        ?
        $linkAulas
        :
        rota(
            "aulas.ver",
            [
                "id" =>
                    $proximaAula
            ]
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


$linkSalas =
    rota(
        "professor.salas"
    );


$linkComoFunciona =
    rota(
        "professor.como_funciona"
    );


$linkPerfil =
    rota(
        "professor.perfil"
    );


$linkLogout =
    rota(
        "logout"
    );


/*
|--------------------------------------------------------------------------
| DISPONIBILIDADE
|--------------------------------------------------------------------------
|
| A existência das rotas é nossa referência para os recursos públicos.
|
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


$comoFuncionaDisponivel =
    rotaExiste(
        "professor.como_funciona"
    );


$salaGlobalDisponivel =
    rotaExiste(
        "sala_global"
    );


$salasDisponiveis =
    rotaExiste(
        "professor.salas"
    );


/*
|--------------------------------------------------------------------------
| SITUAÇÃO
|--------------------------------------------------------------------------
*/

$situacaoProfessor =
    $professorAprovado
        ?
        "Aprovado"
        :
        "Em análise";


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function professorPainelEscapar(
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
    content="Painel do professor da plataforma Java, Café e Código."
>

<title>
    Painel do Professor | <?= professorPainelEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= professorPainelEscapar(
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
    href="<?= professorPainelEscapar(
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
    href="<?= professorPainelEscapar(
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


<body class="pagina-estudante pagina-professor">


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
            ÁREA DO PROFESSOR
        </span>


        <h1>

            Olá,

            <strong>

                <?= professorPainelEscapar(
                    $primeiroNome
                ) ?>!

            </strong>

        </h1>


        <p>

            Utilize os recursos do Java, Café e Código
            como apoio às suas atividades de ensino,
            consulta, prática e aprendizagem da linguagem Java.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= professorPainelEscapar(
                    $linkAulas
                ) ?>"
                class="botao-principal"
            >

                Aulas Conversadas

                <span aria-hidden="true">
                    →
                </span>

            </a>


            <a
                href="<?= professorPainelEscapar(
                    $linkPerfil
                ) ?>"
                class="botao-secundario"
            >

                Meu perfil

            </a>


        </div>


    </div>


    <!-- =================================================
    REPRESENTAÇÃO VISUAL
    ================================================== -->

    <div class="painel-apresentacao-codigo">


        <div class="codigo-topo">


            <div class="codigo-controles">

                <span></span>
                <span></span>
                <span></span>

            </div>


            <strong>
                ProfessorJava.java
            </strong>


            <small>
                JAVA
            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">ProfessorJava</span> {

    <span class="codigo-roxo">public static void</span> <span class="codigo-amarelo">main</span>(
        String[] args
    ) {

        Professor professor =
            <span class="codigo-roxo">new</span> Professor();

        professor.<span class="codigo-amarelo">aprender</span>();
        professor.<span class="codigo-amarelo">compartilhar</span>();
        professor.<span class="codigo-amarelo">ensinar</span>();

    }

}</code></pre>


        <div class="codigo-status">


            <span>

                ● Perfil
                <?= professorPainelEscapar(
                    strtolower(
                        $situacaoProfessor
                    )
                ) ?>

            </span>


            <span>
                Professor
            </span>


        </div>


    </div>


</section>


<!-- =====================================================
RESUMO
====================================================== -->

<section class="painel-estatisticas">


    <!-- SITUAÇÃO -->

    <article>


        <span class="estatistica-icone">
            PR
        </span>


        <div>


            <small>
                SITUAÇÃO DO PERFIL
            </small>


            <strong>

                <?= professorPainelEscapar(
                    $situacaoProfessor
                ) ?>

            </strong>


            <p>

                <?= $professorAprovado
                    ?
                    "permissão de salas liberada"
                    :
                    "aguardando análise administrativa"
                ?>

            </p>


        </div>


    </article>


    <!-- AULAS -->

    <article>


        <span class="estatistica-icone">
            IA
        </span>


        <div>


            <small>
                AULAS CONVERSADAS
            </small>


            <strong>

                <?= professorPainelEscapar(
                    $aulasConcluidas
                ) ?>

                <span>

                    /

                    <?= professorPainelEscapar(
                        $totalAulas
                    ) ?>

                </span>

            </strong>


            <p>
                aulas concluídas
            </p>


        </div>


    </article>


    <!-- CONTEÚDOS -->

    <article>


        <span class="estatistica-icone">
            &lt;/&gt;
        </span>


        <div>


            <small>
                CONTEÚDOS JAVA
            </small>


            <strong>

                <?= professorPainelEscapar(
                    $totalConteudos
                ) ?>

            </strong>


            <p>
                assuntos disponíveis
            </p>


        </div>


    </article>


</section>


<!-- =====================================================
PERFIL E PROGRESSO
====================================================== -->

<section class="painel-estudo">


    <!-- =================================================
    PERFIL PROFISSIONAL
    ================================================== -->

    <article class="painel-ultima-aula">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">
                    PERFIL PROFISSIONAL
                </span>


                <h2>
                    Seus dados
                </h2>


            </div>


            <span class="painel-card-numero">
                PR
            </span>


        </div>


        <div class="painel-aula-conteudo">


            <small>
                INSTITUIÇÃO DE ENSINO
            </small>


            <h3>

                <?= professorPainelEscapar(
                    $instituicao
                ) ?>

            </h3>


            <p>

                Área ou disciplina informada:

                <strong>

                    <?= professorPainelEscapar(
                        $disciplina
                    ) ?>

                </strong>

            </p>


            <p>

                <?php if ($professorAprovado): ?>

                    Seu cadastro está aprovado pela administração
                    e as funções de Sala de Aula estão liberadas.

                <?php else: ?>

                    Seu cadastro está em análise. Os recursos gerais
                    continuam disponíveis normalmente; apenas a criação
                    e o gerenciamento de Salas de Aula aguardam aprovação.

                <?php endif; ?>

            </p>


        </div>


        <a
            href="<?= professorPainelEscapar(
                $linkPerfil
            ) ?>"
            class="botao-aula"
        >

            Ver meu perfil

        </a>


    </article>


    <!-- =================================================
    PROGRESSO
    ================================================== -->

    <article class="painel-proximo-passo">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">
                    AULAS CONVERSADAS
                </span>


                <h2>
                    Seu progresso
                </h2>


            </div>


            <span class="painel-proximo-icone">

                <?= professorPainelEscapar(
                    $porcentagemProgresso
                ) ?>%

            </span>


        </div>


        <div class="painel-proximo-conteudo">


            <small>

                <?= $todasAulasConcluidas
                    ?
                    "REVISÃO"
                    :
                    "PRÓXIMO PASSO"
                ?>

            </small>


            <h3>

                <?= $todasAulasConcluidas
                    ?
                    "Todas as aulas concluídas"
                    :
                    "Continuar aprendendo"
                ?>

            </h3>


            <p>

                Professores também podem utilizar
                as Aulas Conversadas para consultar,
                revisar e explorar conceitos de Java.

            </p>


        </div>


        <div class="painel-aula-progresso">


            <div>


                <span>

                    <?= professorPainelEscapar(
                        $aulasConcluidas
                    ) ?>

                    de

                    <?= professorPainelEscapar(
                        $totalAulas
                    ) ?>

                    aulas

                </span>


                <strong>

                    <?= professorPainelEscapar(
                        $porcentagemProgresso
                    ) ?>%

                </strong>


            </div>


            <!--
            =================================================
            PROGRESSO SEM CSS INLINE

            Antes:
            style="width: 50%;"

            Agora o valor é semântico e fica no próprio
            elemento HTML <progress>.
            =================================================
            -->

            <div class="painel-barra">

                <progress
                    class="painel-barra-progresso"
                    value="<?= professorPainelEscapar(
                        $porcentagemProgresso
                    ) ?>"
                    max="100"
                    aria-label="Progresso nas Aulas Conversadas"
                >

                    <?= professorPainelEscapar(
                        $porcentagemProgresso
                    ) ?>%

                </progress>

            </div>


        </div>


        <?php if ($totalAulas > 0): ?>


            <a
                href="<?= professorPainelEscapar(
                    $linkProximaAula
                ) ?>"
                class="botao-secundario"
            >

                <?= $todasAulasConcluidas
                    ?
                    "Revisar aulas"
                    :
                    "Continuar aula"
                ?>

            </a>


        <?php endif; ?>


    </article>


</section>


<!-- =====================================================
COMO FUNCIONA A APROVAÇÃO
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">
            SALAS DE AULA
        </span>


        <h2>
            Permissão do professor
        </h2>


        <p>

            A aprovação administrativa não interfere
            no acesso aos conteúdos gerais da plataforma.

            Ela é necessária apenas para permitir
            a criação e o gerenciamento de Salas de Aula.

        </p>


    </div>


    <div class="painel-orientacao-etapas">


        <article>


            <span>
                01
            </span>


            <strong>
                Cadastro
            </strong>


            <p>

                O professor informa sua instituição
                e área de atuação.

            </p>


        </article>


        <article>


            <span>
                02
            </span>


            <strong>
                Análise
            </strong>


            <p>

                A administração verifica
                o cadastro profissional.

            </p>


        </article>


        <article>


            <span>
                03
            </span>


            <strong>
                Liberação
            </strong>


            <p>

                Após aprovado, o professor pode
                criar e gerenciar Salas de Aula.

            </p>


        </article>


    </div>


</section>


<!-- =====================================================
ACESSOS
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">
            RECURSOS
        </span>


        <h2>
            Recursos do Professor
        </h2>


        <p>

            Acesse ferramentas de aprendizagem,
            consulta, prática e colaboração disponíveis
            na plataforma.

        </p>


    </div>


    <div class="painel-acessos-grid">


        <!-- =================================================
        SALAS DE AULA
        ================================================== -->

        <?php if (
            $professorAprovado
            &&
            $salasDisponiveis
        ): ?>


            <a
                href="<?= professorPainelEscapar(
                    $linkSalas
                ) ?>"
                class="acesso-card acesso-principal"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        SA
                    </span>


                    <span class="acesso-status acesso-disponivel">
                        Liberado
                    </span>


                </div>


                <div>


                    <small>
                        PROFESSOR
                    </small>


                    <h3>
                        Salas de Aula
                    </h3>


                    <p>

                        Crie salas, compartilhe códigos
                        e organize suas turmas.

                    </p>


                </div>


                <span
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </span>


            </a>


        <?php else: ?>


            <article class="acesso-card acesso-indisponivel">


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        SA
                    </span>


                    <span class="acesso-status acesso-breve">

                        <?= $professorAprovado
                            ?
                            "Indisponível"
                            :
                            "Em análise"
                        ?>

                    </span>


                </div>


                <div>


                    <small>
                        PROFESSOR
                    </small>


                    <h3>
                        Salas de Aula
                    </h3>


                    <p>

                        <?= $professorAprovado
                            ?
                            "A interface de salas não está disponível."
                            :
                            "Será liberado após a aprovação administrativa."
                        ?>

                    </p>


                </div>


            </article>


        <?php endif; ?>


        <!-- =================================================
        AULAS CONVERSADAS
        ================================================== -->

        <a
            href="<?= professorPainelEscapar(
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
                    APRENDIZAGEM
                </small>


                <h3>
                    Aulas Conversadas
                </h3>


                <p>

                    Explore conceitos de Java
                    utilizando a experiência interativa.

                </p>


            </div>


            <span
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </span>


        </a>


        <!-- =================================================
        CONTEÚDOS
        ================================================== -->

        <a
            href="<?= professorPainelEscapar(
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
                    Conteúdos Java
                </h3>


                <p>

                    Consulte materiais organizados
                    sobre conceitos da linguagem.

                </p>


            </div>


            <span
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </span>


        </a>


        <!-- =================================================
        LABORATÓRIO
        ================================================== -->

        <?php if ($laboratorioDisponivel): ?>


            <a
                href="<?= professorPainelEscapar(
                    $linkLaboratorio
                ) ?>"
                class="acesso-card"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        JV
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

                        Escreva, compile e execute
                        códigos Java pela plataforma.

                    </p>


                </div>


                <span
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </span>


            </a>


        <?php endif; ?>


        <!-- =================================================
        CADERNO
        ================================================== -->

        <a
            href="<?= professorPainelEscapar(
                $linkCaderno
            ) ?>"
            class="acesso-card"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    CD
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
                    Caderno Java
                </h3>


                <p>

                    Organize anotações,
                    observações e estudos.

                </p>


            </div>


            <span
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </span>


        </a>


        <!-- =================================================
        SALA GLOBAL
        ================================================== -->

        <?php if ($salaGlobalDisponivel): ?>


            <a
                href="<?= professorPainelEscapar(
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

                        Participe do espaço colaborativo
                        com outros usuários.

                    </p>


                </div>


                <span
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </span>


            </a>


        <?php endif; ?>


        <!-- =================================================
        MANUAL
        ================================================== -->

        <?php if ($manualDisponivel): ?>


            <a
                href="<?= professorPainelEscapar(
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
                        ORIENTAÇÃO
                    </small>


                    <h3>
                        Manual
                    </h3>


                    <p>

                        Consulte orientações
                        para utilizar a plataforma.

                    </p>


                </div>


                <span
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </span>


            </a>


        <?php endif; ?>


        <!-- =================================================
        COMO FUNCIONA
        ================================================== -->

        <?php if ($comoFuncionaDisponivel): ?>


            <a
                href="<?= professorPainelEscapar(
                    $linkComoFunciona
                ) ?>"
                class="acesso-card"
            >


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        CF
                    </span>


                    <span class="acesso-status acesso-disponivel">
                        Disponível
                    </span>


                </div>


                <div>


                    <small>
                        PROFESSOR
                    </small>


                    <h3>
                        Como funciona
                    </h3>


                    <p>

                        Conheça as funções
                        e permissões do perfil Professor.

                    </p>


                </div>


                <span
                    class="acesso-seta"
                    aria-hidden="true"
                >
                    →
                </span>


            </a>


        <?php endif; ?>


        <!-- =================================================
        PERFIL
        ================================================== -->

        <a
            href="<?= professorPainelEscapar(
                $linkPerfil
            ) ?>"
            class="acesso-card"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    EU
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

                    Consulte suas informações
                    pessoais e profissionais.

                </p>


            </div>


            <span
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </span>


        </a>


    </div>


</section>


<!-- =====================================================
AÇÕES
====================================================== -->

<section class="painel-apresentacao-acoes">


    <a
        href="<?= professorPainelEscapar(
            $linkPerfil
        ) ?>"
        class="botao-secundario"
    >

        Meu Perfil

    </a>


    <a
        href="<?= professorPainelEscapar(
            $linkLogout
        ) ?>"
        class="botao-secundario"
    >

        Sair da conta

    </a>


</section>


</main>


<?php

require_once __DIR__
    .
    "/../../../componentes/footer.php";

?>


<script
    src="<?= professorPainelEscapar(
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