<?php

/*
|--------------------------------------------------------------------------
| COMO FUNCIONA - PROFESSOR
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Explica:
|
| - o objetivo do perfil Professor;
| - quais recursos gerais estão disponíveis;
| - como funciona a aprovação administrativa;
| - quais recursos dependem dessa aprovação;
| - o papel auxiliar da plataforma.
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
        "[ProfessorComoFunciona] Erro ao carregar perfil do usuário "
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
| SITUAÇÃO
|--------------------------------------------------------------------------
*/

$professorAprovado =
    filter_var(
        $perfilProfessor["aprovado"]
        ??
        false,
        FILTER_VALIDATE_BOOLEAN
    );


$situacaoProfessor =
    $professorAprovado
        ?
        "Aprovado"
        :
        "Em análise";


/*
|--------------------------------------------------------------------------
| LINKS
|--------------------------------------------------------------------------
*/

$linkPainel =
    rota(
        "professor.painel"
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


$linkSalaGlobal =
    rota(
        "sala_global"
    );


$linkManual =
    rota(
        "manual"
    );


$linkPerfil =
    rota(
        "professor.perfil"
    );


$linkSalas =
    rota(
        "professor.salas"
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


$cadernoDisponivel =
    rotaExiste(
        "caderno"
    );


$manualDisponivel =
    rotaExiste(
        "manual"
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
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function professorComoFuncionaEscapar(
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
    content="Entenda como funciona o perfil Professor na plataforma Java, Café e Código."
>

<title>
    Como funciona | Professor | <?= professorComoFuncionaEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= professorComoFuncionaEscapar(
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
    href="<?= professorComoFuncionaEscapar(
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
    href="<?= professorComoFuncionaEscapar(
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
            PERFIL PROFESSOR
        </span>


        <h1>

            Como funciona o perfil

            <strong>
                Professor
            </strong>

        </h1>


        <p>

            O perfil Professor utiliza o Java, Café e Código
            como uma ferramenta auxiliar de apoio ao ensino,
            à consulta e à aprendizagem de programação Java.

        </p>


        <p>

            Os recursos gerais permanecem disponíveis
            independentemente da aprovação administrativa.

            A aprovação é necessária apenas para criar
            e gerenciar Salas de Aula.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= professorComoFuncionaEscapar(
                    $linkPainel
                ) ?>"
                class="botao-principal"
            >

                Ir para o painel

                <span aria-hidden="true">
                    →
                </span>

            </a>


            <a
                href="<?= professorComoFuncionaEscapar(
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
                PerfilProfessor.java
            </strong>


            <small>
                JAVA
            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">Professor</span> {

    <span class="codigo-roxo">public void</span> <span class="codigo-amarelo">utilizarPlataforma</span>() {

        acessarConteudos();
        consultarAulas();
        praticarJava();
        colaborar();

    }

}</code></pre>


        <div class="codigo-status">


            <span>

                ●
                <?= professorComoFuncionaEscapar(
                    $situacaoProfessor
                ) ?>

            </span>


            <span>
                Professor
            </span>


        </div>


    </div>


</section>


<!-- =====================================================
SITUAÇÃO
====================================================== -->

<section class="painel-estatisticas">


    <article>


        <span class="estatistica-icone">
            PR
        </span>


        <div>


            <small>
                PERFIL
            </small>


            <strong>
                Professor
            </strong>


            <p>
                perfil selecionado
            </p>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">
            AD
        </span>


        <div>


            <small>
                SITUAÇÃO
            </small>


            <strong>

                <?= professorComoFuncionaEscapar(
                    $situacaoProfessor
                ) ?>

            </strong>


            <p>

                <?= $professorAprovado
                    ?
                    "salas de aula liberadas"
                    :
                    "aguardando análise administrativa"
                ?>

            </p>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">
            JV
        </span>


        <div>


            <small>
                RECURSOS GERAIS
            </small>


            <strong>
                Liberados
            </strong>


            <p>
                acesso normal à plataforma
            </p>


        </div>


    </article>


</section>


<!-- =====================================================
APROVAÇÃO ADMINISTRATIVA
====================================================== -->

<section class="painel-estudo">


    <article class="painel-ultima-aula">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">
                    SITUAÇÃO DO PERFIL
                </span>


                <h2>

                    <?= $professorAprovado
                        ?
                        "Perfil aprovado"
                        :
                        "Perfil em análise"
                    ?>

                </h2>


            </div>


            <span class="painel-card-numero">
                AD
            </span>


        </div>


        <div class="painel-aula-conteudo">


            <?php if (
                $professorAprovado
            ): ?>


                <small>
                    APROVAÇÃO CONCLUÍDA
                </small>


                <h3>
                    Salas de Aula liberadas
                </h3>


                <p>

                    Seu cadastro foi aprovado pela administração.

                    Você possui acesso aos recursos gerais
                    e também às funções específicas
                    relacionadas às Salas de Aula.

                </p>


            <?php else: ?>


                <small>
                    AGUARDANDO ANÁLISE
                </small>


                <h3>
                    Continue utilizando a plataforma
                </h3>


                <p>

                    Você pode utilizar normalmente
                    Aulas Conversadas, Conteúdos Java,
                    Laboratório, Caderno, Sala Global
                    e os demais recursos.

                </p>


                <p>

                    Somente a criação e o gerenciamento
                    de Salas de Aula permanecem indisponíveis
                    enquanto o cadastro estiver em análise.

                </p>


            <?php endif; ?>


        </div>


        <a
            href="<?= professorComoFuncionaEscapar(
                $linkPainel
            ) ?>"
            class="botao-aula"
        >

            Voltar ao painel

        </a>


    </article>


    <!-- =================================================
    REGRA
    ================================================== -->

    <article class="painel-proximo-passo">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">
                    REGRA DE ACESSO
                </span>


                <h2>
                    O que depende de aprovação?
                </h2>


            </div>


            <span class="painel-proximo-icone">
                ?
            </span>


        </div>


        <div class="painel-proximo-conteudo">


            <small>
                RECURSOS GERAIS
            </small>


            <h3>
                Não dependem de aprovação
            </h3>


            <p>

                O perfil Professor continua sendo um usuário
                completo da plataforma mesmo antes
                da análise administrativa.

            </p>


            <p>

                A aprovação existe somente como controle
                para a criação e administração
                de ambientes destinados às turmas.

            </p>


        </div>


    </article>


</section>


<!-- =====================================================
FLUXO
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">
            SALAS DE AULA
        </span>


        <h2>
            Como funciona a aprovação
        </h2>


        <p>

            A análise administrativa controla somente
            a permissão especial destinada
            ao gerenciamento de turmas.

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
                de ensino e principal área de atuação.

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
                os dados profissionais informados.

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

                Após a aprovação, as funções
                de Sala de Aula são liberadas.

            </p>


        </article>


    </div>


</section>


<!-- =====================================================
RECURSOS
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">
            RECURSOS
        </span>


        <h2>
            O que o Professor pode utilizar?
        </h2>


        <p>

            Conheça os principais recursos disponíveis
            para apoio às atividades de ensino,
            estudo, consulta e prática.

        </p>


    </div>


    <div class="painel-acessos-grid">


        <!-- =================================================
        PAINEL
        ================================================== -->

        <a
            href="<?= professorComoFuncionaEscapar(
                $linkPainel
            ) ?>"
            class="acesso-card acesso-principal"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    PN
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
                    Painel do Professor
                </h3>


                <p>

                    Consulte seu progresso,
                    perfil profissional, situação
                    e recursos disponíveis.

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
        AULAS
        ================================================== -->

        <a
            href="<?= professorComoFuncionaEscapar(
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

                    Explore aulas interativas,
                    consulte conceitos e acompanhe
                    seu próprio progresso.

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
            href="<?= professorComoFuncionaEscapar(
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

                    Consulte materiais teóricos
                    organizados sobre diferentes
                    conceitos da linguagem Java.

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

        <?php if (
            $laboratorioDisponivel
        ): ?>


            <a
                href="<?= professorComoFuncionaEscapar(
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
                        programas Java diretamente
                        pela plataforma.

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

        <?php if (
            $cadernoDisponivel
        ): ?>


            <a
                href="<?= professorComoFuncionaEscapar(
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

                        Registre conceitos,
                        exemplos, observações
                        e informações importantes.

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
        ASSISTENTE
        ================================================== -->

        <article class="acesso-card">


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    AI
                </span>


                <span class="acesso-status acesso-disponivel">
                    Disponível
                </span>


            </div>


            <div>


                <small>
                    ASSISTENTE
                </small>


                <h3>
                    Assistente Java
                </h3>


                <p>

                    Utilize o assistente como apoio
                    para consultar conceitos,
                    interpretar códigos e esclarecer dúvidas.

                </p>


            </div>


        </article>


        <!-- =================================================
        SALA GLOBAL
        ================================================== -->

        <?php if (
            $salaGlobalDisponivel
        ): ?>


            <a
                href="<?= professorComoFuncionaEscapar(
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

                        Compartilhe dúvidas,
                        conhecimentos e experiências
                        com a comunidade da plataforma.

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
        SALAS DE AULA
        ================================================== -->

        <?php if (
            $professorAprovado
            &&
            $salasDisponiveis
        ): ?>


            <a
                href="<?= professorComoFuncionaEscapar(
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

                        Crie salas, compartilhe
                        códigos de acesso
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
                            "A interface de gerenciamento não está disponível."
                            :
                            "A criação e o gerenciamento serão liberados após a aprovação administrativa."
                        ?>

                    </p>


                </div>


            </article>


        <?php endif; ?>


        <!-- =================================================
        MANUAL
        ================================================== -->

        <?php if (
            $manualDisponivel
        ): ?>


            <a
                href="<?= professorComoFuncionaEscapar(
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
                        sobre o funcionamento
                        da plataforma e seus recursos.

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
            href="<?= professorComoFuncionaEscapar(
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
                    pessoais, profissionais
                    e situação administrativa.

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
IMPORTANTE
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">
            IMPORTANTE
        </span>


        <h2>
            Uma ferramenta de apoio
        </h2>


        <p>

            O Java, Café e Código é uma plataforma auxiliar
            voltada ao apoio da aprendizagem
            e da compreensão de programação Java.

        </p>


    </div>


    <div class="painel-orientacao-etapas">


        <article>


            <span>
                01
            </span>


            <strong>
                Apoio
            </strong>


            <p>

                Os recursos funcionam como ferramentas
                complementares de estudo e consulta.

            </p>


        </article>


        <article>


            <span>
                02
            </span>


            <strong>
                Não substitui profissionais
            </strong>


            <p>

                A plataforma não substitui professores,
                instituições de ensino ou acompanhamento formal.

            </p>


        </article>


        <article>


            <span>
                03
            </span>


            <strong>
                Sem certificação
            </strong>


            <p>

                O uso da plataforma não gera
                certificação ou qualificação profissional.

            </p>


        </article>


    </div>


</section>


<!-- =====================================================
AÇÕES FINAIS
====================================================== -->

<section class="painel-apresentacao-acoes">


    <a
        href="<?= professorComoFuncionaEscapar(
            $linkPainel
        ) ?>"
        class="botao-principal"
    >

        ← Voltar ao painel

    </a>


    <a
        href="<?= professorComoFuncionaEscapar(
            $linkPerfil
        ) ?>"
        class="botao-secundario"
    >

        Meu Perfil

    </a>


</section>


</main>


<?php

require_once __DIR__
    .
    "/../../../componentes/footer.php";

?>


<script
    src="<?= professorComoFuncionaEscapar(
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