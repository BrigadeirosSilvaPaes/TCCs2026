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


$linkEscolherPerfil =
    rota(
        "perfil.escolher"
    );


$linkInicio =
    rota(
        "inicio"
    );


$linkPainel =
    rota(
        "estudante.painel"
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


$linkSalaAula =
    rota(
        "estudante.salas"
    );


$linkManual =
    rota(
        "manual"
    );


$linkPerfil =
    rota(
        "estudante.perfil"
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


try {

    $resultadoPerfil =
        $perfilController->buscarPerfil(
            $usuarioId
        );

}
catch (Throwable $erro) {

    error_log(
        "[ComoFuncionaEstudante] Erro ao carregar perfil do usuário " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    header(
        "Location: " .
        $linkInicio
    );

    exit;

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
) {

    $_SESSION["erro"] =
        "Complete primeiro seu perfil.";


    header(
        "Location: " .
        $linkEscolherPerfil
    );

    exit;

}


$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


/*
|--------------------------------------------------------------------------
| GARANTE PERFIL ESTUDANTE
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
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function comoFuncionaEstudanteEscapar(
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
    content="Conheça os recursos disponíveis para estudantes na plataforma Java, Café e Código."
>

<title>
    Como funciona | <?= SITE_NAME ?>
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
CSS DO ESTUDANTE
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/estudante.css?v=<?= filemtime(
        __DIR__ .
        "/../../../assets/css/estudante.css"
    ) ?>"
>


</head>


<body class="pagina-estudante">


<?php

require_once __DIR__ .
    "/../../../componentes/header.php";

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
            Como funciona a plataforma?
        </h1>


        <p>

            Conheça os recursos disponíveis para estudantes
            no Java, Café e Código e entenda como cada espaço
            pode auxiliar durante seus estudos sobre Java.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= comoFuncionaEstudanteEscapar(
                    $linkPainel
                ) ?>"
                class="botao-principal"
            >

                Voltar ao painel

                <span aria-hidden="true">
                    →
                </span>

            </a>


            <a
                href="<?= comoFuncionaEstudanteEscapar(
                    $linkManual
                ) ?>"
                class="botao-secundario"
            >

                Abrir manual

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
                Estudante.java
            </strong>


            <small>
                JAVA
            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">Estudante</span> {

    <span class="codigo-roxo">public void</span> <span class="codigo-amarelo">aprender</span>() {

        estudar();
        praticar();
        perguntar();
        compartilhar();

    }

}</code></pre>


        <div class="codigo-status">

            <span>
                ● Recursos disponíveis
            </span>

            <span>
                Estudante
            </span>

        </div>


    </div>


</section>


<!-- =====================================================
INTRODUÇÃO
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">
            CONHEÇA A PLATAFORMA
        </span>


        <h2>
            Recursos disponíveis para estudantes
        </h2>


        <p>

            A plataforma reúne diferentes ferramentas de apoio
            à aprendizagem. Cada uma possui uma função específica:
            estudar conceitos, conversar sobre conteúdos, praticar
            programação, registrar anotações ou interagir com
            outros participantes.

        </p>


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
                    Consulte conteúdos e participe das Aulas Conversadas.
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
                    Utilize o Laboratório Java para escrever e executar códigos.
                </p>

            </div>

        </article>


        <article>

            <span>
                03
            </span>

            <div>

                <strong>
                    Compartilhe
                </strong>

                <p>
                    Utilize os espaços colaborativos disponíveis na plataforma.
                </p>

            </div>

        </article>


    </div>


</section>


<!-- =====================================================
FUNCIONALIDADES
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <div>

            <span class="painel-etiqueta">
                FUNCIONALIDADES
            </span>


            <h2>
                O que você pode utilizar
            </h2>


            <p>

                Veja abaixo a função de cada recurso disponível
                no perfil Estudante.

            </p>

        </div>


    </div>


    <div class="painel-acessos-grid">


        <!-- =====================================================
        PAINEL DO ESTUDANTE
        ====================================================== -->

        <a
            href="<?= comoFuncionaEstudanteEscapar(
                $linkPainel
            ) ?>"
            class="acesso-card acesso-principal"
        >


            <div class="acesso-card-topo">

                <span class="acesso-icone">
                    01
                </span>

                <span class="acesso-status acesso-disponivel">
                    Disponível
                </span>

            </div>


            <div>

                <small>
                    VISÃO GERAL
                </small>


                <h3>
                    Painel do Estudante
                </h3>


                <p>

                    É a página principal da área do estudante.
                    Nela você acompanha seu progresso nas Aulas
                    Conversadas e encontra atalhos para os recursos
                    disponíveis na plataforma.

                </p>

            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =====================================================
        AULAS CONVERSADAS
        ====================================================== -->

        <a
            href="<?= comoFuncionaEstudanteEscapar(
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

                    São aulas interativas voltadas à compreensão
                    dos conceitos de Java. Durante a aula você
                    pode escolher um estilo de tutor e avançar
                    pelas etapas da explicação.

                </p>

            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =====================================================
        CONTEÚDOS JAVA
        ====================================================== -->

        <a
            href="<?= comoFuncionaEstudanteEscapar(
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

                    Área destinada à consulta de explicações,
                    conceitos e exemplos sobre diferentes
                    assuntos relacionados à linguagem Java.

                </p>

            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =====================================================
        LABORATÓRIO JAVA
        ====================================================== -->

        <a
            href="<?= comoFuncionaEstudanteEscapar(
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

                    Ambiente destinado à prática de programação.
                    Você pode escrever o código no arquivo Main.java,
                    compilá-lo e executá-lo diretamente na plataforma.

                </p>


                <p>

                    O terminal também permite programas interativos
                    que utilizam entrada de dados, como códigos
                    desenvolvidos com Scanner.

                </p>

            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =====================================================
        CADERNO JAVA
        ====================================================== -->

        <a
            href="<?= comoFuncionaEstudanteEscapar(
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

                    Espaço pessoal para registrar dúvidas,
                    observações, conclusões e informações
                    importantes encontradas durante seus estudos.

                </p>

            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =====================================================
        ASSISTENTE JAVA
        ====================================================== -->

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
                    AUXÍLIO
                </small>


                <h3>
                    Assistente Java
                </h3>


                <p>

                    O Assistente Java pode ser utilizado para
                    auxiliar na compreensão de dúvidas relacionadas
                    à linguagem Java durante a navegação pela
                    plataforma.

                </p>


                <p>

                    Ele funciona como um recurso complementar
                    de apoio e não substitui professores ou
                    outros meios formais de aprendizagem.

                </p>

            </div>


        </article>


        <!-- =====================================================
        SALA GLOBAL
        ====================================================== -->

        <a
            href="<?= comoFuncionaEstudanteEscapar(
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

                    Espaço colaborativo da plataforma destinado
                    à troca de conhecimentos, dúvidas, códigos,
                    materiais e experiências entre participantes.

                </p>

            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =====================================================
        SALA DE AULA
        ====================================================== -->

        <a
            href="<?= comoFuncionaEstudanteEscapar(
                $linkSalaAula
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
                    TURMAS
                </small>


                <h3>
                    Sala de Aula
                </h3>


                <p>

                    Ambiente destinado à integração entre
                    estudante e professor. Para participar,
                    o estudante utiliza o código de uma sala
                    disponibilizado pelo professor.

                </p>

            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =====================================================
        MANUAL
        ====================================================== -->

        <a
            href="<?= comoFuncionaEstudanteEscapar(
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

                    Área de consulta que reúne informações
                    e orientações já disponibilizadas no
                    manual da plataforma.

                </p>

            </div>


            <strong
                class="acesso-seta"
                aria-hidden="true"
            >
                →
            </strong>


        </a>


        <!-- =====================================================
        MEU PERFIL
        ====================================================== -->

        <a
            href="<?= comoFuncionaEstudanteEscapar(
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

                    Espaço onde você pode consultar as
                    informações associadas ao seu perfil
                    dentro da plataforma.

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


<!-- =====================================================
AVISO
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
            voltada ao apoio na compreensão da linguagem Java
            e de seus conceitos. Seus recursos não constituem
            um curso, não oferecem certificação e não substituem
            professores, instituições de ensino ou profissionais
            da área.

        </p>


    </div>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../../componentes/footer.php";

?>


<!-- =====================================================
TEMA
====================================================== -->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= filemtime(
        __DIR__ .
        "/../../../assets/js/theme.js"
    ) ?>"
></script>


</body>

</html>