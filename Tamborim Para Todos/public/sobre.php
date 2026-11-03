<?php

session_start();

$usuarioLogado = isset($_SESSION["usuario"]);

$painel = "";

if ($usuarioLogado) {

    $tipo = $_SESSION["usuario"]["tipo"] ?? "";

    switch ($tipo) {

        case "admin":
            $painel = "admin/painel.php";
            break;

        case "professor":
            $painel = "professor/painel.php";
            break;

        case "aluno":
            $painel = "aluno/painel.php";
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sobre | Tamborim Para Todos</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<header class="landing-header">

    <div class="logo-container">

        <img
            src="assets/img/logo.png"
            alt="Logo Tamborim Para Todos"
            class="logo-img">

        <div class="logo-text">
            Tamborim Para Todos
        </div>

    </div>

    <nav class="landing-nav">

        <ul class="landing-nav-links">

            <li>
                <a href="inicio.php">
                    Início
                </a>
            </li>

            <li>
                <a href="inicio.php#recursos">
                    Recursos
                </a>
            </li>

            <li>
                <a href="inicio.php#como-funciona">
                    Como funciona
                </a>
            </li>

            <li>
                <a href="sobre.php">
                    Sobre
                </a>
            </li>

        </ul>

        <div class="landing-nav-actions">

            <?php if ($usuarioLogado && !empty($painel)): ?>

                <a
                    href="<?= htmlspecialchars($painel) ?>"
                    class="btn-primary">

                    Meu painel

                </a>

            <?php else: ?>

                <a
                    href="login.php"
                    class="btn-secondary">

                    Entrar

                </a>

                <a
                    href="criar.php"
                    class="btn-primary">

                    Criar conta

                </a>

            <?php endif; ?>

        </div>

    </nav>

</header>


<!-- HERO SOBRE -->

<section class="about-hero">

    <div class="about-content">

        <span class="section-eyebrow">
            Sobre o projeto
        </span>

        <h1>
            Ensino de tamborim
            <span>mais acessível.</span>
        </h1>

        <p>
            O Tamborim Para Todos é uma plataforma digital criada
            como Trabalho de Conclusão de Curso, com o objetivo de
            facilitar o aprendizado do tamborim por meio de conteúdos
            organizados, videoaulas e acompanhamento da evolução do aluno.
        </p>

        <p>
            A proposta é reunir tecnologia e educação musical em um
            ambiente simples, moderno e acessível, permitindo que cada
            estudante avance no seu próprio ritmo.
        </p>

    </div>

</section>


<!-- PROPÓSITO -->

<section class="landing-section alt">

    <div class="section-heading">

        <span class="section-eyebrow">
            Nosso propósito
        </span>

        <h2>
            Democratizar o aprendizado musical
        </h2>

        <p>
            A plataforma busca reduzir barreiras no acesso ao ensino
            de instrumentos de percussão, oferecendo uma experiência
            digital organizada e intuitiva.
        </p>

    </div>

    <div class="features-grid">

        <article class="feature-card">

            <div class="feature-icon">
                🎓
            </div>

            <h3>
                Educação acessível
            </h3>

            <p>
                Conteúdos disponíveis de forma simples para que mais
                pessoas tenham acesso ao aprendizado do tamborim.
            </p>

        </article>

        <article class="feature-card">

            <div class="feature-icon">
                🥁
            </div>

            <h3>
                Foco na prática
            </h3>

            <p>
                O aluno aprende técnicas, ritmos e fundamentos através
                de aulas e exercícios pensados para aplicação prática.
            </p>

        </article>

        <article class="feature-card">

            <div class="feature-icon">
                💻
            </div>

            <h3>
                Tecnologia como apoio
            </h3>

            <p>
                O ambiente digital permite organizar conteúdos, acompanhar
                progresso e facilitar a comunicação entre alunos e professores.
            </p>

        </article>

    </div>

</section>


<!-- COMO A PLATAFORMA FUNCIONA -->

<section class="landing-section">

    <div class="section-heading">

        <span class="section-eyebrow">
            Estrutura
        </span>

        <h2>
            Uma plataforma com diferentes perfis
        </h2>

        <p>
            Cada usuário possui uma área específica de acordo com sua função
            dentro do sistema.
        </p>

    </div>

    <div class="steps-grid">

        <article class="step-card">

            <span class="step-number">
                01
            </span>

            <h3>
                Aluno
            </h3>

            <p>
                Acessa conteúdos, aulas e acompanha sua evolução dentro
                da plataforma.
            </p>

        </article>

        <article class="step-card">

            <span class="step-number">
                02
            </span>

            <h3>
                Professor
            </h3>

            <p>
                Após autorização, pode publicar e organizar conteúdos
                destinados aos alunos.
            </p>

        </article>

        <article class="step-card">

            <span class="step-number">
                03
            </span>

            <h3>
                Administrador
            </h3>

            <p>
                Gerencia professores, usuários e conteúdos, mantendo
                o funcionamento e a organização da plataforma.
            </p>

        </article>

    </div>

</section>


<!-- TCC -->

<section class="landing-section alt">

    <div class="section-heading">

        <span class="section-eyebrow">
            Trabalho de Conclusão de Curso
        </span>

        <h2>
            Um projeto desenvolvido na escola
        </h2>

        <p>
            O Tamborim Para Todos nasceu dentro do ambiente escolar como
            uma proposta de integração entre tecnologia, educação e música.
        </p>

    </div>

    <div class="features-grid">

        <article class="feature-card">

            <div class="feature-icon">
                🏫
            </div>

            <h3>
                Instituição
            </h3>

            <p>
                E.E.E.M. Brigadeiro José da Silva Paes.
            </p>

        </article>

        <article class="feature-card">

            <div class="feature-icon">
                👨‍💻
            </div>

            <h3>
                Desenvolvimento
            </h3>

            <p>
                Plataforma construída com foco em organização,
                autenticação, permissões e experiência do usuário.
            </p>

        </article>

        <article class="feature-card">

            <div class="feature-icon">
                🎵
            </div>

            <h3>
                Música e tecnologia
            </h3>

            <p>
                O projeto utiliza recursos digitais para ampliar
                possibilidades de ensino do tamborim.
            </p>

        </article>

    </div>

</section>


<!-- CTA -->

<section class="cta-section">

    <div class="cta-card">

        <div class="cta-card-content">

            <h2>
                Quer conhecer a plataforma?
            </h2>

            <p>
                Crie uma conta e comece a explorar os conteúdos
                disponíveis no Tamborim Para Todos.
            </p>

            <?php if ($usuarioLogado && !empty($painel)): ?>

                <a
                    href="<?= htmlspecialchars($painel) ?>"
                    class="btn-secondary">

                    Ir para meu painel

                </a>

            <?php else: ?>

                <a
                    href="criar.php"
                    class="btn-secondary">

                    Criar minha conta

                </a>

            <?php endif; ?>

        </div>

    </div>

</section>


<footer>

    <div class="school-info">

        <img
            src="assets/img/logo_escola.png"
            alt="Logo da Escola"
            class="footer-logo">

        <h3>
            E.E.E.M. Brigadeiro José da Silva Paes
        </h3>

        <p>
            Projeto desenvolvido como Trabalho de Conclusão de Curso.
        </p>

        <p>
            Instagram:
            <a
                href="https://www.instagram.com/escolasilvapaes_rg/"
                target="_blank"
                rel="noopener noreferrer">

                @escolasilvapaes_rg

            </a>
        </p>

    </div>

    <hr>

    <div class="credits">

        Criado por
        <span>Bernado Monte Padilha</span>
        e
        <span>Laura Nascimento Martins</span>

    </div>

</footer>


<script src="assets/js/theme.js"></script>

</body>

</html>