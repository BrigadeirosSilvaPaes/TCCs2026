<?php

session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../app/Models/Usuario.php";


/*
|--------------------------------------------------------------------------
| ESTADO DE LOGIN
|--------------------------------------------------------------------------
*/

$usuarioLogado = false;

$painel = "";


/*
|--------------------------------------------------------------------------
| VERIFICAR SESSÃO
|--------------------------------------------------------------------------
|
| Não basta existir $_SESSION["usuario"].
|
| Também precisamos conferir se o usuário ainda existe no banco.
|
|--------------------------------------------------------------------------
*/

if (isset($_SESSION["usuario"])) {

    $usuarioModel = new Usuario($pdo);

    $usuarioId =
        $_SESSION["usuario"]["id"]
        ?? null;


    /*
    |--------------------------------------------------------------------------
    | ID INVÁLIDO
    |--------------------------------------------------------------------------
    */

    if (!$usuarioId) {

        session_unset();
        session_destroy();

    } else {

        /*
        |--------------------------------------------------------------------------
        | BUSCAR USUÁRIO REAL NO BANCO
        |--------------------------------------------------------------------------
        */

        $usuarioBanco =
            $usuarioModel->buscarPorId(
                $usuarioId
            );


        /*
        |--------------------------------------------------------------------------
        | USUÁRIO FOI EXCLUÍDO
        |--------------------------------------------------------------------------
        */

        if (!$usuarioBanco) {

            session_unset();
            session_destroy();

        } else {

            /*
            |--------------------------------------------------------------------------
            | ATUALIZAR A SESSÃO
            |--------------------------------------------------------------------------
            |
            | Isso também resolve alterações feitas pelo admin.
            |
            | Exemplo:
            |
            | professor ativo → pendente
            |
            | A sessão passa a refletir o banco.
            |
            |--------------------------------------------------------------------------
            */

            $_SESSION["usuario"] = [

                "id" =>
                    $usuarioBanco["id"],

                "nome" =>
                    $usuarioBanco["nome"],

                "email" =>
                    $usuarioBanco["email"],

                "tipo" =>
                    $usuarioBanco["tipo"],

                "status" =>
                    $usuarioBanco["status"]
            ];


            /*
            |--------------------------------------------------------------------------
            | PROFESSOR NÃO AUTORIZADO
            |--------------------------------------------------------------------------
            */

            if (
                $usuarioBanco["tipo"] === "professor" &&
                $usuarioBanco["status"] !== "ativo"
            ) {

                session_unset();
                session_destroy();

            } else {

                $usuarioLogado = true;


                /*
                |--------------------------------------------------------------------------
                | DEFINIR PAINEL
                |--------------------------------------------------------------------------
                */

                switch (
                    $usuarioBanco["tipo"]
                ) {

                    case "admin":

                        $painel =
                            "admin/painel.php";

                        break;


                    case "professor":

                        $painel =
                            "professor/painel.php";

                        break;


                    case "aluno":

                        $painel =
                            "aluno/painel.php";

                        break;


                    default:

                        /*
                        |--------------------------------------------------------------------------
                        | TIPO INVÁLIDO
                        |--------------------------------------------------------------------------
                        */

                        $usuarioLogado = false;

                        session_unset();
                        session_destroy();

                        break;
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tamborim Para Todos</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css">

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
                <a href="#inicio">
                    Início
                </a>
            </li>

            <li>
                <a href="#recursos">
                    Recursos
                </a>
            </li>

            <li>
                <a href="#como-funciona">
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


<!-- HERO -->

<section
    class="landing-hero"
    id="inicio">

    <div class="landing-hero-content">

        <span class="hero-badge">
             Plataforma de ensino musical
        </span>

        <h1>
            Aprenda tamborim
            <span>no seu ritmo.</span>
        </h1>

        <p>
            Uma plataforma criada para tornar o aprendizado do tamborim
            mais simples, acessível e interativo, reunindo videoaulas,
            conteúdos organizados e acompanhamento da evolução do aluno.
        </p>

        <div class="hero-actions">

            <?php if ($usuarioLogado && !empty($painel)): ?>

                <a
                    href="<?= htmlspecialchars($painel) ?>"
                    class="btn-primary">

                    Continuar aprendendo

                </a>

            <?php else: ?>

                <a
                    href="criar.php"
                    class="btn-primary">

                    Começar agora

                </a>

                <a
                    href="#como-funciona"
                    class="btn-secondary">

                    Como funciona

                </a>

            <?php endif; ?>

        </div>

    </div>


    <!-- PREVIEW VISUAL -->

    <div class="landing-hero-visual">

        <div class="platform-preview">

            <div class="platform-preview-window">

                <div class="platform-preview-topbar">

                    <span class="preview-dot"></span>
                    <span class="preview-dot"></span>
                    <span class="preview-dot"></span>

                </div>

                <div class="platform-preview-content">

                    <div class="preview-title"></div>

                    <div class="preview-line"></div>

                    <div class="preview-line"></div>

                    <div class="preview-line short"></div>

                    <div class="preview-grid">

                        <div class="preview-card">

                            <strong>
                                Aula 01
                            </strong>

                            <p>
                                Primeiros ritmos
                            </p>

                        </div>

                        <div class="preview-card">

                            <strong>
                                Aula 02
                            </strong>

                            <p>
                                Técnicas básicas
                            </p>

                        </div>

                        <div class="preview-card">

                            <strong>
                                Progresso
                            </strong>

                            <p>
                                Acompanhe sua evolução
                            </p>

                        </div>

                        <div class="preview-card">

                            <strong>
                                Exercícios
                            </strong>

                            <p>
                                Pratique o conteúdo
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- NÚMEROS / DESTAQUES -->

<section class="stats-section">

    <div class="stats-grid">

        <div class="stat-item">

            <strong>
                Videoaulas
            </strong>

            <span>
                Conteúdos organizados por professores
            </span>

        </div>

        <div class="stat-item">

            <strong>
                Evolução
            </strong>

            <span>
                Acompanhe seu desenvolvimento
            </span>

        </div>

        <div class="stat-item">

            <strong>
                Acesso simples
            </strong>

            <span>
                Aprenda onde e quando quiser
            </span>

        </div>

    </div>

</section>


<!-- RECURSOS -->

<section
    class="landing-section alt"
    id="recursos">

    <div class="section-heading">

        <span class="section-eyebrow">
            Recursos
        </span>

        <h2>
            Tudo em um único lugar
        </h2>

        <p>
            A plataforma foi pensada para reunir aprendizado,
            prática e acompanhamento em uma experiência simples
            e organizada.
        </p>

    </div>

    <div class="features-grid">

        <article class="feature-card">

            <div class="feature-icon">
                🎥
            </div>

            <h3>
                Videoaulas
            </h3>

            <p>
                Assista a conteúdos preparados por professores
                e avance conforme seu próprio ritmo.
            </p>

        </article>

        <article class="feature-card">

            <div class="feature-icon">
                🥁
            </div>

            <h3>
                Prática musical
            </h3>

            <p>
                Encontre exercícios e orientações para desenvolver
                ritmo, coordenação e técnica.
            </p>

        </article>

        <article class="feature-card">

            <div class="feature-icon">
                📈
            </div>

            <h3>
                Progresso
            </h3>

            <p>
                Acompanhe as aulas concluídas e visualize sua
                evolução dentro da plataforma.
            </p>

        </article>

    </div>

</section>


<!-- COMO FUNCIONA -->

<section
    class="landing-section"
    id="como-funciona">

    <div class="section-heading">

        <span class="section-eyebrow">
            Como funciona
        </span>

        <h2>
            Começar é simples
        </h2>

        <p>
            Em poucos passos você já pode acessar os conteúdos
            e começar seu aprendizado.
        </p>

    </div>

    <div class="steps-grid">

        <article class="step-card">

            <span class="step-number">
                01
            </span>

            <h3>
                Crie sua conta
            </h3>

            <p>
                Cadastre-se como aluno ou professor e acesse
                sua área dentro da plataforma.
            </p>

        </article>

        <article class="step-card">

            <span class="step-number">
                02
            </span>

            <h3>
                Escolha suas aulas
            </h3>

            <p>
                Navegue pelos conteúdos disponíveis e encontre
                aulas adequadas ao seu aprendizado.
            </p>

        </article>

        <article class="step-card">

            <span class="step-number">
                03
            </span>

            <h3>
                Continue evoluindo
            </h3>

            <p>
                Pratique, conclua conteúdos e acompanhe seu
                progresso dentro da plataforma.
            </p>

        </article>

    </div>

</section>


<!-- CTA -->

<section class="cta-section">

    <div class="cta-card">

        <div class="cta-card-content">

            <h2>
                Pronto para começar?
            </h2>

            <p>
                Crie sua conta gratuitamente e comece a explorar
                os conteúdos do Tamborim Para Todos.
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


<!-- FOOTER -->

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