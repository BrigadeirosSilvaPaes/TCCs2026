<?php

/*
|--------------------------------------------------------------------------
| NAVBAR DA LANDING PAGE
|--------------------------------------------------------------------------
|
| Este componente é utilizado somente na página pública.
| Os links levam para seções explicativas da Landing Page.
|
|--------------------------------------------------------------------------
*/

?>

<header class="landing-navbar" id="navbarLanding">

<div class="landing-navbar-container">

    <!--
    ==================================================
    IDENTIDADE DA PLATAFORMA
    ==================================================
    -->

    <a
        href="#inicio"
        class="landing-marca"
        aria-label="Voltar ao início"
    >

        <img
            src="<?= BASE_URL ?>/frontend/assets/img/logo.png"
            alt="Logo Java, Café e Código"
            class="landing-logo"
        >

        <div class="landing-marca-texto">

            <strong>

                Java, Café e Código

            </strong>

            <span>

                Aprenda Java de forma acessível

            </span>

        </div>

    </a>


    <!--
    ==================================================
    BOTÃO MOBILE
    ==================================================
    -->

    <button
        type="button"
        class="landing-menu-botao"
        id="landingMenuBotao"
        aria-label="Abrir menu"
        aria-expanded="false"
        aria-controls="landingMenu"
    >

        <span></span>
        <span></span>
        <span></span>

    </button>


    <!--
    ==================================================
    NAVEGAÇÃO
    ==================================================
    -->

    <nav
        class="landing-menu"
        id="landingMenu"
        aria-label="Navegação principal"
    >

        <ul class="landing-menu-lista">

            <li>

                <a href="#inicio">

                    Início

                </a>

            </li>


            <!--
            ==================================================
            DROPDOWN DE RECURSOS
            ==================================================
            -->

            <li class="landing-dropdown">

                <button
                    type="button"
                    class="landing-dropdown-botao"
                    id="recursosBotao"
                    aria-expanded="false"
                >

                    Recursos

                    <span class="landing-dropdown-seta">

                        ▾

                    </span>

                </button>


                <div
                    class="landing-dropdown-menu"
                    id="recursosMenu"
                >

                    <a href="#conteudos">

                        <span class="landing-dropdown-icone">

                            📚

                        </span>

                        <span>

                            <strong>Conteúdos</strong>

                            <small>

                                Trilhas organizadas de Java

                            </small>

                        </span>

                    </a>


                    <a href="#aulas">

                        <span class="landing-dropdown-icone">

                            🎥

                        </span>

                        <span>

                            <strong>Aulas</strong>

                            <small>

                                Explicações acessíveis e exemplos

                            </small>

                        </span>

                    </a>


                    <a href="#sala-aula">

                        <span class="landing-dropdown-icone">

                            👨‍🏫

                        </span>

                        <span>

                            <strong>Sala de Aula</strong>

                            <small>

                                Organização para estudantes e professores

                            </small>

                        </span>

                    </a>


                    <a href="#ia-auxiliar">

                        <span class="landing-dropdown-icone">

                            🤖

                        </span>

                        <span>

                            <strong>IA Auxiliar</strong>

                            <small>

                                Apoio durante os estudos

                            </small>

                        </span>

                    </a>


                    <a href="#javacode">

                        <span class="landing-dropdown-icone">

                            ☕

                        </span>

                        <span>

                            <strong>JavaCode</strong>

                            <small>

                                Ambiente para praticar programação

                            </small>

                        </span>

                    </a>


                    <a href="#aulas-interativas">

                        <span class="landing-dropdown-icone">

                            🎮

                        </span>

                        <span>

                            <strong>Aulas Interativas</strong>

                            <small>

                                Aprendizado com desafios e experiências

                            </small>

                        </span>

                    </a>

                </div>

            </li>


            <li>

                <a href="#sobre">

                    Sobre

                </a>

            </li>


            <li>

                <a href="#equipe">

                    Equipe

                </a>

            </li>


                        <li>

                <a href="#parceiros">

                    Parceiros

                </a>

            </li>

<li>

                <a href="#contato">

                    Contato

                </a>

            </li>

        </ul>


        <!--
        ==================================================
        AÇÕES
        ==================================================
        -->

        <div class="landing-navbar-acoes">

            <a
                href="<?= BASE_URL ?>/frontend/pages/login/login.php"
                class="landing-botao-entrar"
            >

                Entrar

            </a>

            <a
                href="<?= BASE_URL ?>/frontend/pages/cadastro/cadastro.php"
                class="landing-botao-destaque"
            >

                Começar agora

            </a>

        </div>

    </nav>

</div>

</header>