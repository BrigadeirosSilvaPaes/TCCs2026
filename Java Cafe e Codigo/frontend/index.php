<?php

require_once __DIR__ . "/../backend/config/app.php";
require_once __DIR__ . "/../backend/config/helpers.php";


/*
|--------------------------------------------------------------------------
| GARANTE SESSÃO ANTES DE QUALQUER HTML
|--------------------------------------------------------------------------
|
| O componente de mensagens utiliza a sessão.
| Por isso ela precisa estar ativa antes que qualquer conteúdo
| seja enviado ao navegador.
|
*/

garantirSessao();

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
        content="Java, Café e Código: plataforma interativa de apoio ao aprendizado de programação em Java."
    >

    <title>
        <?= SITE_NAME ?>
    </title>


    <!-- =====================================================
    CSS GLOBAL
    ====================================================== -->

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= filemtime(__DIR__ . "/assets/css/style.css") ?>"
    >


    <!-- =====================================================
    CSS DA LANDING
    ====================================================== -->

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>/frontend/assets/css/index.css?v=<?= filemtime(__DIR__ . "/assets/css/index.css") ?>"
    >


    <!-- =====================================================
    MENSAGENS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css?v=<?= filemtime(__DIR__ . "/assets/css/mensagem.css") ?>"
    >

</head>


<body class="landing-page">


<?php

include __DIR__ . "/componentes/navbar_landing.php";



include __DIR__ . "/componentes/mensagem.php";

?>


<main class="pagina-inicial">


<!-- =====================================================
HERO
====================================================== -->

<section
    class="inicio-hero"
    id="inicio"
>

    <div class="landing-container">

        <div class="inicio-hero-layout">


            <!-- APRESENTAÇÃO -->

            <div class="inicio-hero-conteudo">

                <span class="inicio-etiqueta">
                    PLATAFORMA DE APOIO AO APRENDIZADO
                </span>


                <h1>

                    Aprender Java pode ser mais

                    <span>
                        simples e interativo.
                    </span>

                </h1>


                <p class="inicio-hero-descricao">

                    O Java, Café e Código é uma plataforma educacional
                    desenvolvida para auxiliar pessoas que estão começando
                    a estudar programação em Java.

                </p>


                <p class="inicio-hero-descricao">

                    A proposta é apresentar conceitos de forma organizada,
                    utilizar uma linguagem acessível e incentivar a prática
                    durante o processo de aprendizagem.

                </p>


                <div class="inicio-hero-acoes">

                    <a
                        href="<?= BASE_URL ?>/frontend/pages/cadastro/cadastro.php"
                        class="inicio-botao-principal"
                    >

                        Começar agora

                        <span aria-hidden="true">
                            →
                        </span>

                    </a>


                    <a
                        href="#recursos"
                        class="inicio-botao-secundario"
                    >

                        Conhecer os recursos

                    </a>

                </div>


                <p class="inicio-hero-observacao">

                    <span aria-hidden="true">
                        ✓
                    </span>

                    Ferramenta auxiliar para estudo, revisão e prática de Java.

                </p>

            </div>


            <!-- REPRESENTAÇÃO VISUAL -->

            <div class="inicio-hero-visual">

                <div class="inicio-hero-painel">

                    <div class="inicio-hero-painel-barra">

                        <span></span>
                        <span></span>
                        <span></span>

                    </div>


                    <div class="inicio-hero-painel-conteudo">

                        <div class="inicio-hero-painel-menu">

                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>

                        </div>


                        <div class="inicio-hero-codigo">

                            <small>
                                PRIMEIRO PROGRAMA EM JAVA
                            </small>

<pre><code><span class="codigo-palavra">public class</span> <span class="codigo-classe">PrimeiroPrograma</span> {

    <span class="codigo-palavra">public static void</span> main(
        String[] args
    ) {

        System.out.println(
            <span class="codigo-texto">"Olá, mundo!"</span>
        );

    }

}</code></pre>

                        </div>

                    </div>

                </div>


                <div class="inicio-hero-flutuante">

                    <div>

                        <strong>
                            Aprendizado progressivo
                        </strong>

                        <span>

                            Do primeiro comando aos conceitos
                            mais importantes da linguagem Java.

                        </span>

                    </div>

                </div>

            </div>


        </div>

    </div>

</section>


<!-- =====================================================
FAIXA DE CARACTERÍSTICAS
====================================================== -->

<section class="inicio-faixa">

    <div class="landing-container">

        <ul class="inicio-faixa-lista">

            <li class="inicio-faixa-item">
                Conteúdos organizados
            </li>

            <li class="inicio-faixa-item">
                Foco na linguagem Java
            </li>

            <li class="inicio-faixa-item">
                Linguagem acessível
            </li>

            <li class="inicio-faixa-item">
                Aprendizado progressivo
            </li>

        </ul>

    </div>

</section>


<!-- =====================================================
RECURSOS
====================================================== -->

<section
    class="recursos-secao"
    id="recursos"
>

    <div class="landing-container">


        <div class="secao-cabecalho">

            <span class="secao-etiqueta">
                RECURSOS DA PLATAFORMA
            </span>

            <h2>
                Conheça a proposta do Java, Café e Código
            </h2>

            <p>

                Os recursos apresentados abaixo representam a proposta
                educacional da plataforma. Nesta página, eles são exibidos
                apenas para explicar seus conceitos, objetivos e benefícios.

            </p>

        </div>


        <div class="recursos-lista">


            <!-- =====================================================
            CONTEÚDOS
            ====================================================== -->

            <article
                class="recurso-apresentacao"
                id="conteudos"
            >

                <div class="recurso-conteudo">

                    <span class="recurso-numero">
                        01 — CONTEÚDOS
                    </span>

                    <br>

                    <span class="recurso-status status-disponivel">
                        Recurso educacional
                    </span>

                    <h3>
                        Conteúdos organizados em uma sequência progressiva
                    </h3>

                    <p>

                        A área de conteúdos representa a trilha de aprendizagem
                        da plataforma. Os assuntos são organizados em uma sequência
                        que começa pelos fundamentos da programação e avança
                        gradualmente para conceitos mais completos da linguagem Java.

                    </p>

                    <p>

                        Essa organização busca evitar que o estudante tenha contato
                        com assuntos avançados antes de compreender os conhecimentos
                        necessários para continuar seus estudos.

                    </p>

                    <ul class="recurso-beneficios">

                        <li>
                            Organização dos assuntos por módulos e níveis.
                        </li>

                        <li>
                            Progressão desde os fundamentos até orientação a objetos.
                        </li>

                        <li>
                            Possibilidade de revisar conteúdos anteriores.
                        </li>

                        <li>
                            Maior clareza sobre a sequência recomendada de estudos.
                        </li>

                    </ul>

                </div>


                <div class="recurso-visual">

                    <span class="recurso-visual-tag">
                        APRESENTAÇÃO CONCEITUAL
                    </span>

                    <div class="recurso-visual-placeholder">

                        <strong>
                            Trilha de conteúdos
                        </strong>

                        <p>

                            Representação de uma sequência organizada de assuntos,
                            como introdução, variáveis, condições, repetições,
                            métodos, arrays e orientação a objetos.

                        </p>

                    </div>

                </div>

            </article>


            <!-- =====================================================
            AULAS
            ====================================================== -->

            <article
                class="recurso-apresentacao recurso-invertido"
                id="aulas"
            >

                <div class="recurso-visual">

                    <span class="recurso-visual-tag">
                        APRESENTAÇÃO CONCEITUAL
                    </span>

                    <div class="recurso-visual-placeholder">

                        <strong>
                            Aulas explicativas
                        </strong>

                        <p>

                            Representação de aulas com teoria, exemplos,
                            blocos de código, resultados e resumos dos
                            principais conceitos estudados.

                        </p>

                    </div>

                </div>


                <div class="recurso-conteudo">

                    <span class="recurso-numero">
                        02 — AULAS
                    </span>

                    <br>

                    <span class="recurso-status status-disponivel">
                        Recurso educacional
                    </span>

                    <h3>
                        Explicações acessíveis acompanhadas de exemplos
                    </h3>

                    <p>

                        As aulas representam o espaço onde cada conceito de Java
                        é apresentado de forma detalhada, utilizando explicações,
                        exemplos de código e demonstrações de resultados.

                    </p>

                    <p>

                        A proposta é evitar explicações excessivamente técnicas
                        no primeiro contato, ajudando o estudante a compreender
                        o que cada comando faz e por que ele é utilizado.

                    </p>

                    <ul class="recurso-beneficios">

                        <li>
                            Explicações apresentadas em linguagem acessível.
                        </li>

                        <li>
                            Exemplos práticos relacionados ao conteúdo estudado.
                        </li>

                        <li>
                            Blocos de código organizados para facilitar a leitura.
                        </li>

                        <li>
                            Resumos para apoiar a revisão dos conceitos.
                        </li>

                    </ul>

                </div>

            </article>


            <!-- =====================================================
            SALA DE AULA
            ====================================================== -->

            <article
                class="recurso-apresentacao"
                id="sala-aula"
            >

                <div class="recurso-conteudo">

                    <span class="recurso-numero">
                        03 — SALA DE AULA
                    </span>

                    <br>

                    <span class="recurso-status">
                        Conceito em desenvolvimento
                    </span>

                    <h3>
                        Um ambiente de apoio para estudantes e professores
                    </h3>

                    <p>

                        A Sala de Aula representa a ideia de um ambiente que
                        permita aproximar estudantes e professores dentro da
                        plataforma.

                    </p>

                    <p>

                        Nesse conceito, o professor poderia organizar grupos,
                        recomendar conteúdos e acompanhar atividades, enquanto
                        os estudantes teriam acesso a uma experiência direcionada.

                    </p>

                    <p>

                        Esse recurso não busca substituir uma sala de aula real.
                        Sua função seria oferecer uma ferramenta complementar
                        para organização e acompanhamento dos estudos.

                    </p>

                    <ul class="recurso-beneficios">

                        <li>
                            Organização de estudantes em grupos ou turmas.
                        </li>

                        <li>
                            Recomendação de conteúdos pelo professor.
                        </li>

                        <li>
                            Apoio ao acompanhamento da aprendizagem.
                        </li>

                        <li>
                            Integração entre estudo individual e orientação docente.
                        </li>

                    </ul>

                </div>


                <div class="recurso-visual">

                    <span class="recurso-visual-tag">
                        RECURSO PLANEJADO
                    </span>

                    <div class="recurso-visual-placeholder">

                        <strong>
                            Sala de Aula
                        </strong>

                        <p>

                            Representação conceitual de um espaço onde professores
                            poderiam organizar estudantes, conteúdos e atividades
                            educacionais.

                        </p>

                    </div>

                </div>

            </article>


            <!-- =====================================================
            IA AUXILIAR
            ====================================================== -->

            <article
                class="recurso-apresentacao recurso-invertido"
                id="ia-auxiliar"
            >

                <div class="recurso-visual">

                    <span class="recurso-visual-tag">
                        RECURSO PLANEJADO
                    </span>

                    <div class="recurso-visual-placeholder">

                        <strong>
                            IA Auxiliar
                        </strong>

                        <p>

                            Representação de uma ferramenta capaz de explicar
                            conceitos, ajudar na leitura de códigos e orientar
                            o estudante durante seus estudos.

                        </p>

                    </div>

                </div>


                <div class="recurso-conteudo">

                    <span class="recurso-numero">
                        04 — IA AUXILIAR
                    </span>

                    <br>

                    <span class="recurso-status">
                        Conceito em desenvolvimento
                    </span>

                    <h3>
                        Um recurso auxiliar para compreender dúvidas e códigos
                    </h3>

                    <p>

                        A IA Auxiliar representa o conceito de uma ferramenta
                        de apoio integrada à experiência de aprendizagem.

                    </p>

                    <p>

                        Sua proposta seria ajudar o usuário a compreender mensagens
                        de erro, interpretar trechos de código e revisar conceitos
                        apresentados nas aulas.

                    </p>

                    <p>

                        A inteligência artificial não substituiria professores,
                        materiais didáticos ou profissionais da área. Ela funcionaria
                        apenas como um recurso complementar de orientação.

                    </p>

                    <ul class="recurso-beneficios">

                        <li>
                            Explicação de conceitos relacionados à linguagem Java.
                        </li>

                        <li>
                            Apoio na interpretação de mensagens de erro.
                        </li>

                        <li>
                            Orientações sobre a estrutura de códigos.
                        </li>

                        <li>
                            Reforço dos assuntos apresentados durante as aulas.
                        </li>

                    </ul>

                </div>

            </article>


            <!-- =====================================================
            JAVACODE
            ====================================================== -->

            <article
                class="recurso-apresentacao"
                id="javacode"
            >

                <div class="recurso-conteudo">

                    <span class="recurso-numero">
                        05 — JAVACODE
                    </span>

                    <br>

                    <span class="recurso-status">
                        Conceito em desenvolvimento
                    </span>

                    <h3>
                        Um ambiente pensado para escrever e testar códigos
                    </h3>

                    <p>

                        O JavaCode representa a proposta de um espaço destinado
                        à prática da programação em Java.

                    </p>

                    <p>

                        Nesse ambiente, o estudante poderia escrever pequenos
                        códigos, executar exemplos e observar os resultados
                        diretamente durante os estudos.

                    </p>

                    <p>

                        A finalidade desse recurso seria reduzir a distância
                        entre a explicação teórica e a experimentação prática.

                    </p>

                    <ul class="recurso-beneficios">

                        <li>
                            Espaço dedicado à escrita de códigos em Java.
                        </li>

                        <li>
                            Possibilidade de testar exemplos apresentados nas aulas.
                        </li>

                        <li>
                            Visualização dos resultados da execução.
                        </li>

                        <li>
                            Incentivo à experimentação e à aprendizagem prática.
                        </li>

                    </ul>

                </div>


                <div class="recurso-visual">

                    <span class="recurso-visual-tag">
                        RECURSO PLANEJADO
                    </span>

                    <div class="recurso-visual-placeholder">

                        <strong>
                            Ambiente JavaCode
                        </strong>

                        <p>

                            Representação conceitual de um editor onde o usuário
                            poderia escrever, executar e analisar pequenos programas
                            desenvolvidos em Java.

                        </p>

                    </div>

                </div>

            </article>


            <!-- =====================================================
            AULAS INTERATIVAS
            ====================================================== -->

            <article
                class="recurso-apresentacao recurso-invertido"
                id="aulas-interativas"
            >

                <div class="recurso-visual">

                    <span class="recurso-visual-tag">
                        RECURSO PLANEJADO
                    </span>

                    <div class="recurso-visual-placeholder">

                        <strong>
                            Aulas Interativas
                        </strong>

                        <p>

                            Representação de desafios, decisões, atividades
                            e pequenas experiências para tornar o aprendizado
                            mais participativo.

                        </p>

                    </div>

                </div>


                <div class="recurso-conteudo">

                    <span class="recurso-numero">
                        06 — AULAS INTERATIVAS
                    </span>

                    <br>

                    <span class="recurso-status">
                        Conceito em desenvolvimento
                    </span>

                    <h3>
                        Aprendizado por meio de desafios e participação ativa
                    </h3>

                    <p>

                        As Aulas Interativas representam a proposta de utilizar
                        atividades, desafios e experiências visuais para complementar
                        as explicações tradicionais.

                    </p>

                    <p>

                        O estudante não seria apenas um leitor. Ele participaria
                        de pequenas situações, escolheria respostas, analisaria
                        resultados e aprenderia com os próprios erros.

                    </p>

                    <p>

                        Esse conceito busca aproximar o aprendizado da programação
                        de uma experiência mais dinâmica e envolvente.

                    </p>

                    <ul class="recurso-beneficios">

                        <li>
                            Participação ativa durante o aprendizado.
                        </li>

                        <li>
                            Desafios relacionados aos conteúdos estudados.
                        </li>

                        <li>
                            Retornos que ajudam a compreender os erros.
                        </li>

                        <li>
                            Experiência mais dinâmica e motivadora.
                        </li>

                    </ul>

                </div>

            </article>


        </div>

    </div>

</section>


<!-- =====================================================
COMO FUNCIONA
====================================================== -->

<section
    class="como-funciona-secao"
    id="como-funciona"
>

    <div class="landing-container">

        <div class="secao-cabecalho secao-cabecalho-centralizado">

            <span class="secao-etiqueta">
                JORNADA DO USUÁRIO
            </span>

            <h2>
                Como funciona a experiência na plataforma?
            </h2>

            <p>

                A jornada foi planejada para conduzir o usuário desde
                o primeiro acesso até o acompanhamento de sua evolução
                nos estudos.

            </p>

        </div>


        <div class="como-funciona-grid">


            <article class="como-funciona-etapa">

                <span class="como-funciona-numero">
                    01
                </span>

                <h3>
                    Criar uma conta
                </h3>

                <p>

                    O usuário realiza seu cadastro para acessar os recursos
                    disponíveis e manter suas informações salvas.

                </p>

            </article>


            <article class="como-funciona-etapa">

                <span class="como-funciona-numero">
                    02
                </span>

                <h3>
                    Escolher um perfil
                </h3>

                <p>

                    A plataforma identifica se o usuário é estudante,
                    professor ou uma pessoa interessada em aprender Java.

                </p>

            </article>


            <article class="como-funciona-etapa">

                <span class="como-funciona-numero">
                    03
                </span>

                <h3>
                    Estudar e praticar
                </h3>

                <p>

                    O usuário acessa conteúdos organizados, aulas explicativas
                    e atividades relacionadas aos assuntos estudados.

                </p>

            </article>


            <article class="como-funciona-etapa">

                <span class="como-funciona-numero">
                    04
                </span>

                <h3>
                    Acompanhar a evolução
                </h3>

                <p>

                    O progresso permite observar os conteúdos acessados
                    e identificar os próximos passos da aprendizagem.

                </p>

            </article>


        </div>

    </div>

</section>


<!-- =====================================================
BENEFÍCIOS
====================================================== -->

<section class="beneficios-secao">

    <div class="landing-container">

        <div class="secao-cabecalho secao-cabecalho-centralizado">

            <span class="secao-etiqueta">
                PROPOSTA EDUCACIONAL
            </span>

            <h2>
                Por que utilizar uma plataforma de apoio?
            </h2>

            <p>

                O projeto busca reunir organização, acessibilidade
                e prática em uma mesma experiência de aprendizagem.

            </p>

        </div>


        <div class="beneficios-grid">


            <article class="beneficio-card">

                <h3>
                    Conteúdo organizado
                </h3>

                <p>

                    Os assuntos são apresentados em uma sequência planejada,
                    ajudando o estudante a identificar o que estudar primeiro
                    e quais conhecimentos vêm depois.

                </p>

            </article>


            <article class="beneficio-card">

                <h3>
                    Linguagem acessível
                </h3>

                <p>

                    As explicações procuram reduzir o excesso de termos
                    técnicos durante o primeiro contato com a programação.

                </p>

            </article>


            <article class="beneficio-card">

                <h3>
                    Aprendizado prático
                </h3>

                <p>

                    A plataforma incentiva a escrita de códigos,
                    a realização de testes e a análise de resultados.

                </p>

            </article>


            <article class="beneficio-card">

                <h3>
                    Revisão de conceitos
                </h3>

                <p>

                    O usuário pode retornar aos conteúdos anteriores
                    sempre que considerar necessário para reforçar
                    sua aprendizagem.

                </p>

            </article>


            <article class="beneficio-card">

                <h3>
                    Ritmo próprio
                </h3>

                <p>

                    Cada pessoa pode avançar conforme sua disponibilidade,
                    sem a necessidade de seguir o mesmo ritmo de outros usuários.

                </p>

            </article>


            <article class="beneficio-card">

                <h3>
                    Apoio ao ensino
                </h3>

                <p>

                    O projeto pode ser utilizado como ferramenta complementar
                    por professores, estudantes e pessoas que estudam
                    programação por conta própria.

                </p>

            </article>


        </div>

    </div>

</section>


<!-- =====================================================
SOBRE
====================================================== -->

<section
    class="sobre-secao"
    id="sobre"
>

    <div class="landing-container">

        <div class="sobre-layout">


            <div class="sobre-conteudo">

                <span class="secao-etiqueta">
                    SOBRE O PROJETO
                </span>

                <h2>
                    Uma plataforma criada a partir das dificuldades de aprender programação
                </h2>

                <p>

                    O Java, Café e Código surgiu a partir das dificuldades
                    observadas durante o primeiro contato com linguagens
                    de programação.

                </p>

                <p>

                    Conceitos abstratos, sintaxes desconhecidas e explicações
                    excessivamente técnicas podem dificultar a compreensão
                    de quem está começando.

                </p>

                <p>

                    Por esse motivo, o projeto propõe uma plataforma web
                    interativa que organiza conteúdos de Java e apresenta
                    explicações de forma gradual e acessível.

                </p>


                <div class="sobre-destaque">

                    <strong>
                        IMPORTANTE
                    </strong>

                    <p>

                        O Java, Café e Código não se identifica como um curso,
                        não oferece certificação e não substitui professores,
                        instituições de ensino ou profissionais da área.

                        Sua finalidade é funcionar como uma ferramenta auxiliar
                        para estudo, revisão e prática.

                    </p>

                </div>

            </div>


            <div class="sobre-visual">

                <div class="sobre-informacoes">


                    <div class="sobre-informacao">

                        <span>
                            NOME DO PROJETO
                        </span>

                        <strong>
                            Java, Café e Código
                        </strong>

                    </div>


                    <div class="sobre-informacao">

                        <span>
                            TIPO DE PROJETO
                        </span>

                        <strong>
                            Plataforma web interativa de apoio ao aprendizado
                        </strong>

                    </div>


                    <div class="sobre-informacao">

                        <span>
                            ÁREA PRINCIPAL
                        </span>

                        <strong>
                            Ensino de programação em Java
                        </strong>

                    </div>


                    <div class="sobre-informacao">

                        <span>
                            PÚBLICO PRINCIPAL
                        </span>

                        <strong>

                            Pessoas iniciantes em programação,
                            estudantes e professores

                        </strong>

                    </div>


                    <div class="sobre-informacao">

                        <span>
                            TECNOLOGIAS
                        </span>

                        <strong>
                            PHP, MySQL, HTML, CSS e JavaScript
                        </strong>

                    </div>


                    <div class="sobre-informacao">

                        <span>
                            DESENVOLVIMENTO
                        </span>

                        <strong>
                            Trabalho de Conclusão de Curso — 2026
                        </strong>

                    </div>


                </div>

            </div>


        </div>

    </div>

</section>


<!-- =====================================================
EQUIPE
====================================================== -->

<section
    class="equipe-secao"
    id="equipe"
>

    <div class="landing-container">


        <div class="secao-cabecalho secao-cabecalho-centralizado">

            <span class="secao-etiqueta">
                EQUIPE DO PROJETO
            </span>

            <h2>
                Conheça quem participou do desenvolvimento
            </h2>

            <p>

                O Java, Café e Código foi desenvolvido como Trabalho de
                Conclusão do Curso Técnico em Informática Integrado ao
                Ensino Médio, contando com a participação dos autores,
                orientador e coorientadores.

            </p>

        </div>


        <!-- =====================================================
        AUTORES E DESENVOLVEDORES
        ====================================================== -->

        <div class="equipe-subtitulo">

            <span>
                DESENVOLVIMENTO
            </span>

            <h3>
                Autores e desenvolvedores
            </h3>

            <p>

                Responsáveis pela elaboração da proposta, desenvolvimento
                técnico, organização dos conteúdos e construção da plataforma.

            </p>

        </div>


        <div class="equipe-grid equipe-grid-autores">


            <!-- =================================================
            ROBERT
            ================================================== -->

            <article class="equipe-card equipe-card-autor">

                <div class="equipe-foto-container">

                    <img
                        src="<?= BASE_URL ?>/frontend/assets/img/robert.png"
                        alt="Foto de Robert de Castro Silveira"
                        class="equipe-foto"
                    >

                    <span class="equipe-foto-identificacao">
                        DESENVOLVEDOR
                    </span>

                </div>


                <div class="equipe-card-conteudo">

                    <h3>
                        Robert de Castro Silveira
                    </h3>

                    <span class="equipe-funcao">
                        AUTOR E DESENVOLVEDOR
                    </span>

                    <p>

                        Coautor e desenvolvedor da plataforma, atuando no
                        desenvolvimento das funcionalidades, implementação do
                        backend e frontend, modelagem do banco de dados,
                        integração entre páginas, organização dos conteúdos e
                        aperfeiçoamento da experiência do usuário durante todas
                        as etapas do projeto.

                    </p>


                    <div class="equipe-areas">

                        <span>PHP</span>
                        <span>Java</span>
                        <span>MySQL</span>
                        <span>Frontend</span>
                        <span>Backend</span>
                        <span>Conteúdos</span>

                    </div>

                </div>

            </article>


            <!-- =================================================
            NATANAEL
            ================================================== -->

            <article class="equipe-card equipe-card-autor">

                <div class="equipe-foto-container">

                    <img
                        src="<?= BASE_URL ?>/frontend/assets/img/natanael.png"
                        alt="Foto de Natanael de Farias Lucena"
                        class="equipe-foto"
                    >

                    <span class="equipe-foto-identificacao">
                        DESENVOLVEDOR
                    </span>

                </div>


                <div class="equipe-card-conteudo">

                    <h3>
                        Natanael de Farias Lucena
                    </h3>

                    <span class="equipe-funcao">
                        AUTOR E DESENVOLVEDOR
                    </span>

                    <p>

                        Coautor e desenvolvedor da plataforma, participando da
                        implementação das funcionalidades, desenvolvimento do
                        backend e frontend, organização dos conteúdos
                        educacionais, elaboração de atividades e evolução da
                        estrutura da plataforma ao longo do desenvolvimento do
                        projeto.

                    </p>


                    <div class="equipe-areas">

                        <span>PHP</span>
                        <span>Java</span>
                        <span>MySQL</span>
                        <span>Backend</span>
                        <span>Frontend</span>
                        <span>Conteúdos</span>

                    </div>

                </div>

            </article>


        </div>


        <!-- =====================================================
        ORIENTAÇÃO ACADÊMICA
        ====================================================== -->

        <div class="equipe-subtitulo equipe-subtitulo-orientacao">

            <span>
                ORIENTAÇÃO ACADÊMICA
            </span>

            <h3>
                Professores orientadores do projeto
            </h3>

            <p>

                O desenvolvimento do <strong>Java, Café e Código</strong>
                contou com o acompanhamento de professores que contribuíram
                com orientações técnicas, metodológicas, pedagógicas e
                acadêmicas, auxiliando no planejamento, desenvolvimento e
                aperfeiçoamento da plataforma durante todas as etapas do
                Trabalho de Conclusão de Curso.

            </p>

        </div>


        <div class="equipe-grid equipe-grid-orientacao">


            <!-- =================================================
            HENRIQUE
            ================================================== -->

            <article class="equipe-card equipe-card-orientacao">

                <div class="equipe-foto-container">

                    <img
                        src="<?= BASE_URL ?>/frontend/assets/img/professor.png"
                        alt="Foto do Professor José Henrique Lopes da Silva"
                        class="equipe-foto"
                        loading="lazy"
                    >

                    <span class="equipe-foto-identificacao">
                        ORIENTADOR
                    </span>

                </div>


                <div class="equipe-card-conteudo">

                    <span class="equipe-funcao">
                        ORIENTADOR DO PROJETO
                    </span>

                    <h3>
                        Professor José Henrique Lopes da Silva
                    </h3>

                    <p>

                        Responsável pelo acompanhamento geral do Trabalho de
                        Conclusão de Curso, orientando o desenvolvimento da
                        plataforma, a organização estrutural do projeto,
                        a análise das etapas executadas e o direcionamento
                        acadêmico necessário para a evolução do trabalho.

                    </p>

                </div>

            </article>


            <!-- =================================================
            LEANDRO
            ================================================== -->

            <article class="equipe-card equipe-card-orientacao">

                
                <div class="equipe-foto-container">

                    <img
                        src="<?= BASE_URL ?>/frontend/assets/img/leandro.png"
                        alt="Foto do Professor Leandro Dutra Junior"
                        class="equipe-foto"
                        loading="lazy"
                    >

                    <span class="equipe-foto-identificacao">
                        COORIENTADOR
                    </span>

                </div>


                <div class="equipe-card-conteudo">

                    <span class="equipe-funcao">
                        COORIENTADOR — LÓGICA DE PROGRAMAÇÃO
                    </span>

                    <h3>
                        Professor Leandro Dutra Junior
                    </h3>

                    <p>

                        Durante todo o Curso Técnico em Informática,
                        contribuiu para a construção da base lógica necessária
                        ao desenvolvimento de software, auxiliando na compreensão
                        de algoritmos, resolução de problemas e conceitos
                        fundamentais que serviram de alicerce para o
                        desenvolvimento da plataforma.

                    </p>

                </div>

            </article>


           <!-- =================================================
LEONARDO
================================================== -->

<article class="equipe-card equipe-card-orientacao">

    <div class="equipe-foto-container">

        <img
            src="<?= BASE_URL ?>/frontend/assets/img/leo.png"
            alt="Foto do Professor Leonardo Santana Benevides"
            class="equipe-foto"
            loading="lazy"
        >

        <span class="equipe-foto-identificacao">
            COORIENTADOR
        </span>

    </div>


    <div class="equipe-card-conteudo">

        <span class="equipe-funcao">
            COORIENTADOR — PROGRAMAÇÃO E BANCO DE DADOS
        </span>

        <h3>
            Professor Leonardo Santana Benevides
        </h3>

        <p>

            Compartilhou os conhecimentos que fundamentaram
            a construção técnica da plataforma, ministrando
            conteúdos relacionados à programação, banco de dados
            e desenvolvimento de sistemas, competências essenciais
            para a implementação do Java, Café e Código.

        </p>

    </div>

</article>

            <!-- =================================================
            SIMONI
            ================================================== -->

            <article class="equipe-card equipe-card-orientacao">

                
                <div class="equipe-foto-container">

                    <img
                        src="<?= BASE_URL ?>/frontend/assets/img/simoni.png"
                        alt="Foto da Professora Simoni Machado Gomes"
                        class="equipe-foto"
                        loading="lazy"
                    >

                    <span class="equipe-foto-identificacao">
                        COORIENTADORA
                    </span>

                </div>


                <div class="equipe-card-conteudo">

                    <span class="equipe-funcao">
                        COORIENTADORA — METODOLOGIA E DESENVOLVIMENTO TEÓRICO
                    </span>

                    <h3>
                        Professora Simoni Machado Gomes
                    </h3>

                    <p>

                        Orientou a elaboração da fundamentação teórica
                        e da produção textual do Trabalho de Conclusão
                        de Curso, contribuindo para a organização
                        metodológica, estrutura científica, escrita acadêmica
                        e construção dos capítulos que compõem a documentação
                        do projeto.

                    </p>

                </div>

            </article>


        </div>

    </div>

</section>


<!-- =====================================================
CONTATO
====================================================== -->

<section
    class="contato-secao"
    id="contato"
>

    <div class="landing-container">

        <div class="contato-layout">


            <div class="contato-informacoes">

                <span class="secao-etiqueta">
                    CONTATO
                </span>

                <h2>
                    Entre em contato com a equipe do projeto
                </h2>


                <div class="contato-lista">


                    <div class="contato-item">

                        <div>

                            <span>
                                INSTITUIÇÃO
                            </span>

                            <strong>
                                E.E.E.M. Brigadeiro José da Silva Paes
                            </strong>

                        </div>

                    </div>


                    <div class="contato-item">

                        <div>

                            <span>
                                CURSO
                            </span>

                            <strong>
                                Técnico em Informática Integrado ao Ensino Médio
                            </strong>

                        </div>

                    </div>


                    <div class="contato-item">

                        <div>

                            <span>
                                LOCALIZAÇÃO
                            </span>

                            <strong>
                                Rio Grande — Rio Grande do Sul
                            </strong>

                        </div>

                    </div>


                    <div class="contato-item">

                        <div>

                            <span>
                                ANO DO PROJETO
                            </span>

                            <strong>
                                2026
                            </strong>

                        </div>

                    </div>


                </div>

            </div>


            <!-- FORMULÁRIO -->

            <div class="contato-formulario">

                <?php

                $resultadoContato =
                    $_GET["contato"] ?? "";

                $mensagensContato = [

                    "sucesso" => [
                        "classe" => "contato-retorno-sucesso",
                        "titulo" => "Mensagem enviada com sucesso!",
                        "texto" => "A equipe do Java, Café e Código recebeu sua mensagem."
                    ],

                    "campos" => [
                        "classe" => "contato-retorno-erro",
                        "titulo" => "Preencha todos os campos.",
                        "texto" => "Todos os dados do formulário são obrigatórios."
                    ],

                    "nome" => [
                        "classe" => "contato-retorno-erro",
                        "titulo" => "Nome inválido.",
                        "texto" => "Informe um nome entre 3 e 100 caracteres."
                    ],

                    "email" => [
                        "classe" => "contato-retorno-erro",
                        "titulo" => "E-mail inválido.",
                        "texto" => "Informe um endereço de e-mail válido."
                    ],

                    "tipo" => [
                        "classe" => "contato-retorno-erro",
                        "titulo" => "Tipo de contato inválido.",
                        "texto" => "Selecione uma das opções disponíveis."
                    ],

                    "assunto" => [
                        "classe" => "contato-retorno-erro",
                        "titulo" => "Assunto inválido.",
                        "texto" => "O assunto deve possuir entre 3 e 120 caracteres."
                    ],

                    "mensagem" => [
                        "classe" => "contato-retorno-erro",
                        "titulo" => "Mensagem inválida.",
                        "texto" => "A mensagem deve possuir entre 10 e 3.000 caracteres."
                    ],

                    "limite" => [
                        "classe" => "contato-retorno-aviso",
                        "titulo" => "Aguarde antes de enviar novamente.",
                        "texto" => "Para evitar envios repetidos, aguarde um minuto."
                    ],

                    "erro" => [
                        "classe" => "contato-retorno-erro",
                        "titulo" => "Não foi possível enviar.",
                        "texto" => "Verifique sua conexão ou tente novamente mais tarde."
                    ]

                ];


                if (
                    isset(
                        $mensagensContato[$resultadoContato]
                    )
                ):

                    $retorno =
                        $mensagensContato[$resultadoContato];

                ?>

                    <div
                        class="contato-retorno <?= htmlspecialchars(
                            $retorno["classe"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>"
                        role="alert"
                    >

                        <strong>

                            <?= htmlspecialchars(
                                $retorno["titulo"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>

                        </strong>

                        <p>

                            <?= htmlspecialchars(
                                $retorno["texto"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>

                        </p>

                    </div>

                <?php endif; ?>


                <form
                    action="<?= BASE_URL ?>/backend/contato/processar_contato.php"
                    method="POST"
                    class="contato-formulario-real"
                >


                    <!-- ANTISPAM -->

                    <div
                        class="contato-campo-antispam"
                        aria-hidden="true"
                    >

                        <label for="contatoSite">
                            Não preencha este campo
                        </label>

                        <input
                            type="text"
                            id="contatoSite"
                            name="site"
                            tabindex="-1"
                            autocomplete="off"
                        >

                    </div>


                    <div class="contato-formulario-grid">


                        <div class="contato-campo">

                            <label for="contatoNome">
                                Nome
                            </label>

                            <input
                                type="text"
                                id="contatoNome"
                                name="nome"
                                placeholder="Digite seu nome"
                                minlength="3"
                                maxlength="100"
                                autocomplete="name"
                                required
                            >

                        </div>


                        <div class="contato-campo">

                            <label for="contatoEmail">
                                E-mail
                            </label>

                            <input
                                type="email"
                                id="contatoEmail"
                                name="email"
                                placeholder="Digite seu e-mail"
                                maxlength="150"
                                autocomplete="email"
                                required
                            >

                        </div>


                        <div class="contato-campo">

                            <label for="contatoTipo">
                                Tipo de mensagem
                            </label>

                            <select
                                id="contatoTipo"
                                name="tipo"
                                required
                            >

                                <option value="">
                                    Selecione uma opção
                                </option>

                                <option value="Dúvida">
                                    Dúvida
                                </option>

                                <option value="Sugestão">
                                    Sugestão
                                </option>

                                <option value="Erro encontrado">
                                    Erro encontrado
                                </option>

                                <option value="Contato">
                                    Contato
                                </option>

                                <option value="Outro">
                                    Outro
                                </option>

                            </select>

                        </div>


                        <div class="contato-campo">

                            <label for="contatoAssunto">
                                Assunto
                            </label>

                            <input
                                type="text"
                                id="contatoAssunto"
                                name="assunto"
                                placeholder="Informe o assunto"
                                minlength="3"
                                maxlength="120"
                                required
                            >

                        </div>


                        <div class="contato-campo contato-campo-largo">

                            <label for="contatoMensagem">
                                Mensagem
                            </label>

                            <textarea
                                id="contatoMensagem"
                                name="mensagem"
                                placeholder="Escreva sua dúvida, sugestão ou mensagem"
                                minlength="10"
                                maxlength="3000"
                                required
                            ></textarea>

                            <small class="contato-limite">
                                Máximo de 3.000 caracteres.
                            </small>

                        </div>


                    </div>


                    <button
                        type="submit"
                        class="contato-botao"
                    >

                        Enviar mensagem

                        <span aria-hidden="true">
                            →
                        </span>

                    </button>


                    <p class="contato-aviso">

                        A mensagem será encaminhada para a equipe
                        do Java, Café e Código.

                    </p>


                </form>

            </div>


        </div>

    </div>

</section>


<!-- =====================================================
PARCEIROS
====================================================== -->

<section
    class="parceiros-secao"
    id="parceiros"
>

    <div class="parceiros-container">


        <div class="parceiros-cabecalho">

            <span class="parceiros-etiqueta">
                PARCERIAS
            </span>

            <h2>
                Parcerias e apoio institucional
            </h2>

            <p>
                O Java, Café e Código conta com o apoio
                de instituições que contribuem para
                o desenvolvimento e a presença online
                da plataforma.
            </p>

        </div>


        <div class="parceiros-grid">


            <!-- =====================================================
            ESCOLA SILVA PAES
            ====================================================== -->

            <article class="parceiro-card">

                <div class="parceiro-logo-area">

                    <img
                        src="<?= BASE_URL ?>/frontend/assets/img/logo-escola.png"
                        alt="Logo da Escola Brigadeiro José da Silva Paes"
                        class="parceiro-logo parceiro-logo-escola"
                        loading="lazy"
                    >

                </div>


                <div class="parceiro-conteudo">

                    <span class="parceiro-tipo">
                        INSTITUIÇÃO PARCEIRA
                    </span>

                    <h3>
                        Escola Brigadeiro José da Silva Paes
                    </h3>

                    <p>
                        Instituição de ensino responsável pelo
                        desenvolvimento acadêmico do projeto
                        Java, Café e Código.
                    </p>


                    <div class="parceiro-links">

                        <a
                            href="https://www.escolasilvapaes.com.br/#/"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="parceiro-link parceiro-link-principal"
                        >
                            Site oficial
                        </a>

                        <a
                            href="https://www.instagram.com/escolasilvapaes_rg?stkn=MXRyNjhvbTliYnUx"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="parceiro-link parceiro-link-secundario"
                        >
                            Instagram
                        </a>

                    </div>

                </div>

            </article>


            <!-- =====================================================
            WDHOUSE
            ====================================================== -->

            <article class="parceiro-card">

                <div class="parceiro-logo-area">

                    <img
                        src="<?= BASE_URL ?>/frontend/assets/img/wdhouse_logo.png"
                        alt="Logo da WDHouse"
                        class="parceiro-logo parceiro-logo-wdhouse"
                        loading="lazy"
                    >

                </div>


                <div class="parceiro-conteudo">

                    <span class="parceiro-tipo">
                        PARCEIRA DE HOSPEDAGEM
                    </span>

                    <h3>
                        WDHouse
                    </h3>

                    <p>
                        Parceira de hospedagem e apoio tecnológico,
                        contribuindo para a disponibilidade online
                        da plataforma Java, Café e Código.
                    </p>


                    <div class="parceiro-links">

                        <a
                            href="https://www.wdhouse.com.br/?srsltid=AU7gw4XWZk-lLYRASBKd5eoXUZImTjcY2X4WsBOVmW93Ld36QyEfglGv"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="parceiro-link parceiro-link-principal"
                        >
                            Site oficial
                        </a>

                        <a
                            href="https://www.instagram.com/souwdhouse?stkn=MXhrcmk0c2g5MDl6OA%3D%3D"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="parceiro-link parceiro-link-secundario"
                        >
                            Instagram
                        </a>

                    </div>

                </div>

            </article>


        </div>

    </div>

</section>

<!-- =====================================================
CHAMADA FINAL
====================================================== -->

<section class="inicio-chamada-final">

    <div class="inicio-chamada-final-layout">


        <div class="inicio-chamada-final-conteudo">

            <span>
                COMECE SUA JORNADA
            </span>

            <h2>
                Dê o primeiro passo no aprendizado de Java.
            </h2>

            <p>

                Crie sua conta, escolha o perfil que representa sua
                relação com a programação e conheça os conteúdos
                disponíveis na plataforma.

            </p>

        </div>


        <a
            href="<?= BASE_URL ?>/frontend/pages/cadastro/cadastro.php"
            class="inicio-botao-principal"
        >

            Criar minha conta

            <span aria-hidden="true">
                →
            </span>

        </a>


    </div>

</section>


</main>


<?php

include __DIR__ . "/componentes/footer.php";

?>


<!-- =====================================================
TEMA
====================================================== -->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= filemtime(__DIR__ . "/assets/js/theme.js") ?>"
></script>


<!-- =====================================================
NAVBAR
====================================================== -->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/navbar_landing.js?v=<?= filemtime(__DIR__ . "/assets/js/navbar_landing.js") ?>"
></script>


</body>

</html>