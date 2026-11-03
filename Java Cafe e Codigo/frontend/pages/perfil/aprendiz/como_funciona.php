<?php

/*
|--------------------------------------------------------------------------
| COMO FUNCIONA - APRENDIZ
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Esta página explica:
|
| - o funcionamento do perfil Aprendiz;
| - os recursos disponíveis;
| - a proposta de aprendizagem independente;
| - o caráter auxiliar da plataforma.
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

function aprendizComoFuncionaRedirecionar(
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

function aprendizComoFuncionaEscapar(
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


    aprendizComoFuncionaRedirecionar(
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


    aprendizComoFuncionaRedirecionar(
        "login"
    );

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
        "[AprendizComoFunciona] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    aprendizComoFuncionaRedirecionar(
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


    aprendizComoFuncionaRedirecionar(
        "perfil.escolher"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIS
|--------------------------------------------------------------------------
*/

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


    aprendizComoFuncionaRedirecionar(
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


    aprendizComoFuncionaRedirecionar(
        "aprendiz.questionario"
    );

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
        "aprendiz.perfil"
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
| ASSETS
|--------------------------------------------------------------------------
*/

$caminhoStyle =
    __DIR__
    .
    "/../../../assets/css/style.css";


$caminhoPainel =
    __DIR__
    .
    "/../../../assets/css/estudante.css";


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
    content="Conheça os recursos disponíveis para o perfil Aprendiz na plataforma Java, Café e Código."
>

<title>
    Como funciona | Aprendiz | <?= aprendizComoFuncionaEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= aprendizComoFuncionaEscapar(
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
    href="<?= aprendizComoFuncionaEscapar(
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
    href="<?= aprendizComoFuncionaEscapar(
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
            Como funciona a plataforma?
        </h1>


        <p>

            Conheça os recursos disponíveis para o perfil
            Aprendiz e entenda como cada ferramenta pode
            auxiliar durante seus estudos e práticas
            relacionadas à linguagem Java.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= aprendizComoFuncionaEscapar(
                    $linkPainel
                ) ?>"
                class="botao-principal"
            >

                Voltar ao painel

                <span aria-hidden="true">
                    →
                </span>

            </a>


            <?php if (
                $manualDisponivel
            ): ?>


                <a
                    href="<?= aprendizComoFuncionaEscapar(
                        $linkManual
                    ) ?>"
                    class="botao-secundario"
                >

                    Abrir manual

                </a>


            <?php endif; ?>


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
        perguntar();
        compartilhar();
        evoluir();

    }

}</code></pre>


        <div class="codigo-status">


            <span>
                ● Recursos disponíveis
            </span>


            <span>
                Aprendiz
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
            Recursos disponíveis para aprendizes
        </h2>


        <p>

            O perfil Aprendiz possui ferramentas voltadas
            ao estudo independente de Java.

            Você pode consultar conteúdos, participar
            das Aulas Conversadas, praticar programação,
            registrar anotações e interagir com a comunidade.

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

                    Consulte conteúdos e utilize
                    as Aulas Conversadas para compreender
                    os conceitos de Java.

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

                    Escreva e execute seus próprios
                    códigos no Laboratório Java.

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

                    Utilize a Sala Global para trocar
                    conhecimentos com a comunidade.

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

                Entenda a função de cada recurso
                disponível para o perfil Aprendiz.

            </p>


        </div>


    </div>


    <div class="painel-acessos-grid">


        <!-- =================================================
        PAINEL
        ================================================== -->

        <a
            href="<?= aprendizComoFuncionaEscapar(
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
                    Painel do Aprendiz
                </h3>


                <p>

                    É a página principal da sua área.

                    Nela você acompanha o progresso
                    das Aulas Conversadas, encontra
                    seu próximo passo e acessa
                    os principais recursos da plataforma.

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
        AULAS CONVERSADAS
        ================================================== -->

        <a
            href="<?= aprendizComoFuncionaEscapar(
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

                    Aulas interativas destinadas
                    à compreensão dos conceitos da linguagem Java.

                    Você pode avançar pelas etapas
                    utilizando diferentes estilos
                    de explicação dos Tutores IA.

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
        CONTEÚDOS JAVA
        ================================================== -->

        <a
            href="<?= aprendizComoFuncionaEscapar(
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

                    Consulte explicações, conceitos
                    e exemplos organizados sobre
                    diferentes assuntos da linguagem Java.

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
        LABORATÓRIO
        ================================================== -->

        <?php if (
            $laboratorioDisponivel
        ): ?>


            <a
                href="<?= aprendizComoFuncionaEscapar(
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

                        Ambiente destinado à prática
                        de programação.

                        Escreva, compile e execute
                        programas Java diretamente
                        pela plataforma.

                    </p>


                    <p>

                        Também é possível testar programas
                        que utilizam entrada de dados
                        durante a execução.

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
        CADERNO
        ================================================== -->

        <?php if (
            $cadernoDisponivel
        ): ?>


            <a
                href="<?= aprendizComoFuncionaEscapar(
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

                        Espaço pessoal para registrar
                        dúvidas, observações, conclusões
                        e informações importantes
                        encontradas durante os estudos.

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
        ASSISTENTE JAVA
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
                    AUXÍLIO
                </small>


                <h3>
                    Assistente Java
                </h3>


                <p>

                    O Assistente Java funciona
                    como uma ferramenta complementar
                    para ajudar na compreensão
                    de dúvidas relacionadas à linguagem Java.

                </p>


                <p>

                    Ele pode ser utilizado durante
                    a navegação pela plataforma
                    como recurso adicional de apoio.

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
                href="<?= aprendizComoFuncionaEscapar(
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

                        Espaço colaborativo destinado
                        à troca de conhecimentos,
                        dúvidas, códigos, materiais
                        e experiências entre os usuários
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
        MANUAL
        ================================================== -->

        <?php if (
            $manualDisponivel
        ): ?>


            <a
                href="<?= aprendizComoFuncionaEscapar(
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

                        Consulte informações
                        e orientações sobre o funcionamento
                        do Java, Café e Código.

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
            href="<?= aprendizComoFuncionaEscapar(
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

                    Consulte suas informações públicas,
                    seu nível de experiência
                    e seu objetivo de aprendizagem.

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
DIFERENÇA DO PERFIL
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">
            PERFIL APRENDIZ
        </span>


        <h2>
            Aprendizagem independente
        </h2>


        <p>

            O perfil Aprendiz é destinado a quem deseja
            utilizar os recursos da plataforma
            de maneira independente.

        </p>


        <p>

            Diferentemente do perfil Estudante,
            o Aprendiz não utiliza as Salas de Aula
            privadas criadas por professores.

            Para interação com outros usuários,
            permanece disponível a Sala Global.

        </p>


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
            e da compreensão da linguagem Java
            e de seus conceitos.

        </p>


        <p>

            A plataforma não substitui professores,
            instituições de ensino ou profissionais da área
            e seu uso não gera certificação
            ou qualificação profissional.

        </p>


    </div>


</section>


<!-- =====================================================
AÇÕES FINAIS
====================================================== -->

<section class="painel-apresentacao-acoes">


    <a
        href="<?= aprendizComoFuncionaEscapar(
            $linkPainel
        ) ?>"
        class="botao-principal"
    >

        ← Voltar ao painel

    </a>


    <a
        href="<?= aprendizComoFuncionaEscapar(
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
    src="<?= aprendizComoFuncionaEscapar(
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