<?php

session_start();

require_once __DIR__ . "/protecao.php";

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../app/Models/Aula.php";
require_once __DIR__ . "/../../app/Models/Progresso.php";

/*
|--------------------------------------------------------------------------
| PROTEÇÃO DA PÁGINA
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["usuario"]) ||
    $_SESSION["usuario"]["tipo"] !== "aluno"
) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION["usuario"];


/*
|--------------------------------------------------------------------------
| MODELS
|--------------------------------------------------------------------------
*/

$aulaModel = new Aula($pdo);
$progressoModel = new Progresso($pdo);


/*
|--------------------------------------------------------------------------
| AULAS E PROGRESSO
|--------------------------------------------------------------------------
*/

$aulas = $progressoModel->listarProgressoDoAluno(
    $usuario["id"]
);

$totalAulas = count($aulas);

$totalConcluidas = $progressoModel->contarConcluidas(
    $usuario["id"]
);

$percentualProgresso = $progressoModel->calcularPercentual(
    $usuario["id"]
);

/*
|--------------------------------------------------------------------------
| BUSCA E AUTOCOMPLETE DE AULAS
|--------------------------------------------------------------------------
*/

$busca = trim($_GET["busca"] ?? "");

$stmtTitulosAulas = $pdo->prepare(
    "SELECT DISTINCT titulo
     FROM aulas
     WHERE status = :status
     AND titulo IS NOT NULL
     AND titulo <> ''
     ORDER BY titulo ASC"
);

$stmtTitulosAulas->execute([
    ":status" => "publicada"
]);

$titulosAulas = $stmtTitulosAulas->fetchAll(PDO::FETCH_COLUMN);

if ($busca !== "") {

    $stmtBuscaAulas = $pdo->prepare(
        "SELECT
            a.id,
            a.titulo,
            a.descricao,
            a.ordem,
            a.status,

            COALESCE(
                pa.concluida,
                FALSE
            ) AS concluida,

            pa.concluida_em

        FROM aulas a

        LEFT JOIN progresso_aulas pa
            ON pa.aula_id = a.id
            AND pa.usuario_id = :usuario_id

        WHERE a.status = 'publicada'
        AND a.titulo LIKE :busca

        ORDER BY a.ordem ASC"
    );

    $stmtBuscaAulas->execute([
        ":usuario_id" => $usuario["id"],
        ":busca" => "%" . $busca . "%"
    ]);

    $aulas = $stmtBuscaAulas->fetchAll(PDO::FETCH_ASSOC);
}

$titulosAulasJson = json_encode(
    $titulosAulas,
    JSON_HEX_TAG |
    JSON_HEX_AMP |
    JSON_HEX_APOS |
    JSON_HEX_QUOT
);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Aulas | Tamborim Para Todos
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

<div class="dashboard-layout">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="dashboard-sidebar">

        <div class="dashboard-brand">

            <img
                src="../assets/img/logo.png"
                alt="Logo Tamborim Para Todos"
                class="logo-img">

            <div>

                <strong>
                    Tamborim Para Todos
                </strong>

                <span>
                    Área do Aluno
                </span>

            </div>

        </div>


        <!-- MENU -->

        <nav class="dashboard-nav">

            <a
                href="painel.php"
                class="dashboard-nav-item">

                <span>⌂</span>

                Dashboard

            </a>


            <a
                href="aulas.php"
                class="dashboard-nav-item active">

                <span>📚</span>

                Aulas

            </a>


            <a
                href="progresso.php"
                class="dashboard-nav-item">

                <span>📈</span>

                Progresso

            </a>


            <a
                href="#"
                class="dashboard-nav-item">

                <span>👤</span>

                Perfil

            </a>

        </nav>


        <!-- PARTE INFERIOR -->

        <div class="dashboard-sidebar-bottom">



            <a
                href="../inicio.php"
                class="dashboard-nav-item">

                <span>←</span>

                Voltar ao site

            </a>

        </div>

    </aside>



    <!-- =====================================================
         CONTEÚDO PRINCIPAL
    ====================================================== -->

    <main class="dashboard-main">


        <!-- =================================================
             TOPO
        ================================================== -->

        <div class="dashboard-topbar">

            <div>

                <span class="dashboard-eyebrow">
                    Aluno
                </span>

                <h1>
                    Aulas
                </h1>

            </div>


            <div class="dashboard-user">

                <div class="dashboard-avatar">

                    <?= strtoupper(
                        substr(
                            htmlspecialchars(
                                $usuario["nome"]
                            ),
                            0,
                            1
                        )
                    ) ?>

                </div>

                <div>

                    <strong>

                        <?= htmlspecialchars(
                            $usuario["nome"]
                        ) ?>

                    </strong>

                    <span>
                        Aluno
                    </span>

                </div>

            </div>

        </div>



        <!-- =================================================
             APRESENTAÇÃO
        ================================================== -->

        <section class="dashboard-welcome">

            <div>

                <span class="section-eyebrow">
                    Aprendizado
                </span>

                <h2>
                    Explore as aulas disponíveis
                </h2>

                <p>

                    Acesse os conteúdos publicados pelos professores,
                    acompanhe o que já concluiu e continue avançando
                    no seu aprendizado.

                </p>

            </div>

        </section>



        <!-- =================================================
             RESUMO
        ================================================== -->

        <section class="dashboard-stats">


            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">

                    📚

                </div>

                <div>

                    <span>
                        Aulas disponíveis
                    </span>

                    <strong>
                        <?= $totalAulas ?>
                    </strong>

                </div>

            </article>


            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">

                    ✅

                </div>

                <div>

                    <span>
                        Concluídas
                    </span>

                    <strong>
                        <?= $totalConcluidas ?>
                    </strong>

                </div>

            </article>


            <article class="dashboard-stat-card">

                <div class="dashboard-stat-icon">

                    📈

                </div>

                <div>

                    <span>
                        Progresso
                    </span>

                    <strong>
                        <?= $percentualProgresso ?>%
                    </strong>

                </div>

            </article>

        </section>



        <!-- =================================================
             PROGRESSO
        ================================================== -->

        <section class="student-progress-overview">

            <div class="student-progress-header">

                <div>

                    <span class="section-eyebrow">

                        Seu progresso

                    </span>

                    <h2>

                        <?= $totalConcluidas ?>
                        de
                        <?= $totalAulas ?>
                        aulas concluídas

                    </h2>

                </div>

                <span class="student-progress-count">

                    <?= $percentualProgresso ?>%

                </span>

            </div>


            <div
                class="student-progress-bar"
                role="progressbar"
                aria-valuenow="<?= $percentualProgresso ?>"
                aria-valuemin="0"
                aria-valuemax="100">

                <div
                    class="student-progress-fill"
                    style="width: <?= $percentualProgresso ?>%;">
                </div>

            </div>

        </section>



        <!-- =================================================
             AULAS
        ================================================== -->

        <section class="dashboard-section">

            <div class="dashboard-section-header">

                <div>

                    <span class="section-eyebrow">
                        Conteúdos
                    </span>

                    <h2>
                        Todas as aulas
                    </h2>

                </div>

            </div>


            <form
                action="aulas.php"
                method="GET"
                class="lesson-search-form"
                id="lesson-search-form"
                autocomplete="off">

                <div class="lesson-search-field">

                    <label for="busca">
                        Buscar aula
                    </label>

                    <div class="lesson-search-input-wrapper">

                        <input
                            type="text"
                            id="busca"
                            name="busca"
                            value="<?= htmlspecialchars($busca) ?>"
                            placeholder="Digite o título da aula..."
                            aria-label="Buscar aula pelo título"
                            aria-autocomplete="list"
                            aria-controls="lesson-search-suggestions">

                        <div
                            id="lesson-search-suggestions"
                            class="lesson-search-suggestions"
                            role="listbox"
                            hidden>
                        </div>

                    </div>

                </div>

                <button
                    type="submit"
                    class="btn-enviar lesson-search-button">

                    Pesquisar

                </button>

            </form>


            <?php if ($busca !== "" && empty($aulas)): ?>

                <div class="empty-state lesson-search-empty">

                    <div class="empty-state-icon">

                        🔎

                    </div>

                    <h3>
                        Nenhuma aula encontrada para '<?= htmlspecialchars($busca) ?>'
                    </h3>

                    <p>
                        Tente pesquisar por outro título.
                    </p>

                </div>

            <?php endif; ?>


            <?php if (!($busca !== "" && empty($aulas))): ?>

            <?php if (empty($aulas)): ?>


                <!-- NENHUMA AULA -->

                <div class="empty-state">

                    <div class="empty-state-icon">

                        📚

                    </div>

                    <h3>
                        Nenhuma aula disponível
                    </h3>

                    <p>
                        Aguarde a publicação de novos conteúdos.
                    </p>

                </div>


            <?php else: ?>


                <!-- LISTA DE AULAS -->

                <div class="student-lessons-grid">


                    <?php foreach ($aulas as $aula): ?>


                        <a
                            href="aula.php?id=<?= (int) $aula["id"] ?>"
                            class="student-lesson-card <?= $aula["concluida"] ? "completed" : "" ?>">


                            <!-- TOPO -->

                            <div class="student-lesson-top">

                                <span class="student-lesson-number">

                                    Aula
                                    <?= (int) $aula["ordem"] ?>

                                </span>


                                <?php if ($aula["concluida"]): ?>

                                    <span class="status-badge active">

                                        ✓ Concluída

                                    </span>

                                <?php else: ?>

                                    <span class="status-badge pending">

                                        Pendente

                                    </span>

                                <?php endif; ?>

                            </div>


                            <!-- TÍTULO -->

                            <h3>

                                <?= htmlspecialchars(
                                    $aula["titulo"]
                                ) ?>

                            </h3>


                            <!-- DESCRIÇÃO -->

                            <p>

                                <?= htmlspecialchars(
                                    $aula["descricao"]
                                ) ?>

                            </p>


                            <!-- RODAPÉ -->

                            <div class="student-lesson-footer">


                                <?php if ($aula["concluida"]): ?>

                                    <span>

                                        ✅ Aula concluída

                                    </span>

                                    <strong>

                                        Revisar →

                                    </strong>


                                <?php else: ?>

                                    <span>

                                        🥁 Continue aprendendo

                                    </span>

                                    <strong>

                                        Abrir aula →

                                    </strong>

                                <?php endif; ?>

                            </div>

                        </a>


                    <?php endforeach; ?>


                </div>


            <?php endif; ?>

            <?php endif; ?>

        </section>

    </main>

</div>


<style>
    .lesson-search-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        margin: 24px 0 30px;
        width: 100%;
    }

    .lesson-search-field {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .lesson-search-field label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 600;
    }

    .lesson-search-input-wrapper {
        position: relative;
    }

    .lesson-search-input-wrapper input {
        width: 100%;
        min-height: 48px;
        padding: 12px 16px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: var(--surface);
        color: var(--text-primary);
        box-shadow: var(--shadow-xs);
        outline: none;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
        box-sizing: border-box;
    }

    .lesson-search-input-wrapper input::placeholder {
        color: var(--text-muted);
    }

    .lesson-search-input-wrapper input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }

    .lesson-search-button {
        min-height: 48px;
        width: auto;
        flex: 0 0 auto;
        white-space: nowrap;
        cursor: pointer;
    }

    .lesson-search-suggestions {
        position: absolute;
        z-index: 50;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        max-height: 320px;
        overflow-y: auto;
        padding: 6px;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        background: var(--surface);
        box-shadow: var(--shadow-md);
    }

    .lesson-search-suggestions[hidden] {
        display: none;
    }

    .lesson-search-suggestion {
        display: block;
        width: 100%;
        padding: 11px 12px;
        border: 0;
        border-radius: var(--radius-sm);
        background: transparent;
        color: var(--text-primary);
        text-align: left;
        font: inherit;
        cursor: pointer;
    }

    .lesson-search-suggestion:hover,
    .lesson-search-suggestion:focus {
        background: var(--surface-hover);
        color: var(--primary);
        outline: none;
    }

    @media (max-width: 640px) {
        .lesson-search-form {
            flex-direction: column;
            align-items: stretch;
        }

        .lesson-search-button {
            width: 100%;
        }
    }
</style>

<script>
    (function () {
        const form = document.getElementById("lesson-search-form");
        const input = document.getElementById("busca");
        const suggestions = document.getElementById("lesson-search-suggestions");
        const titles = <?= $titulosAulasJson ?: "[]" ?>;
        const maxSuggestions = 10;

        if (!form || !input || !suggestions) {
            return;
        }

        function normalizeText(text) {
            return String(text)
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .toLowerCase();
        }

        function closeSuggestions() {
            suggestions.hidden = true;
            suggestions.innerHTML = "";
        }

        function renderSuggestions() {
            const term = normalizeText(input.value.trim());

            const filteredTitles = titles
                .filter(function (title) {
                    return normalizeText(title).includes(term);
                })
                .slice(0, maxSuggestions);

            suggestions.innerHTML = "";

            if (!filteredTitles.length) {
                closeSuggestions();
                return;
            }

            filteredTitles.forEach(function (title) {
                const button = document.createElement("button");

                button.type = "button";
                button.className = "lesson-search-suggestion";
                button.setAttribute("role", "option");
                button.textContent = title;

                button.addEventListener("click", function () {
                    input.value = title;
                    closeSuggestions();
                    form.submit();
                });

                suggestions.appendChild(button);
            });

            suggestions.hidden = false;
        }

        input.addEventListener("focus", renderSuggestions);
        input.addEventListener("input", renderSuggestions);

        input.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                closeSuggestions();
                return;
            }

            if (event.key === "Enter") {
                closeSuggestions();
            }
        });

        document.addEventListener("click", function (event) {
            if (!form.contains(event.target)) {
                closeSuggestions();
            }
        });
    })();
</script>

<script src="../assets/js/theme.js"></script>

</body>

</html>