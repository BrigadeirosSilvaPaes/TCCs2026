<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../backend/config/bootstrap.php";


/*
|--------------------------------------------------------------------------
| SEGURANÇA ADMINISTRATIVA
|--------------------------------------------------------------------------
|
| Confirma diretamente no banco que:
|
| - existe usuário autenticado;
| - conta está ativa;
| - conta não está bloqueada;
| - tipo_acesso continua sendo admin.
|
*/

require_once __DIR__ . "/../../../backend/middleware/verificar_admin.php";


/*
|--------------------------------------------------------------------------
| SEGURANÇA DA URL ADMINISTRATIVA
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../backend/security/admin_url.php";


/*
|--------------------------------------------------------------------------
| GARANTE URL ADMINISTRATIVA COM IDENTIFICADOR
|--------------------------------------------------------------------------
|
| O login atual ainda pode redirecionar para:
|
| /frontend/pages/admin/inicio.php
|
| sem o parâmetro ?acesso=...
|
| Nesse primeiro acesso, como o usuário já foi validado
| como administrador pelo middleware, geramos o identificador
| da sessão e redirecionamos para a URL administrativa completa.
|
*/

$tokenUrlRecebido =
    $_GET[ADMIN_URL_PARAM]
    ?? null;


if (
    !is_string($tokenUrlRecebido) ||
    trim($tokenUrlRecebido) === ""
) {

    header(
        "Location: " .
        adminUrl("inicio.php")
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| EXIGE IDENTIFICADOR VÁLIDO
|--------------------------------------------------------------------------
|
| A partir deste ponto não basta existir ?acesso=...
|
| O valor precisa ser exatamente o token armazenado
| na sessão administrativa atual.
|
*/

adminUrlExigir();


/*
|--------------------------------------------------------------------------
| USUÁRIO
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
            ?? "Administrador"
        )
    );


/*
|--------------------------------------------------------------------------
| PRIMEIRO NOME
|--------------------------------------------------------------------------
*/

$partesNome =
    array_values(
        array_filter(
            explode(
                " ",
                $nomeUsuario
            )
        )
    );


$primeiroNome =
    $partesNome[0]
    ?? "Administrador";


/*
|--------------------------------------------------------------------------
| ESTATÍSTICAS
|--------------------------------------------------------------------------
*/

$totalUsuarios =
    0;

$emailsConfirmados =
    0;

$usuariosBloqueados =
    0;

$professoresPendentes =
    0;

$professoresAprovados =
    0;


try {

    /*
    |--------------------------------------------------------------------------
    | TOTAL DE USUÁRIOS
    |--------------------------------------------------------------------------
    */

    $stmtUsuarios =
        $pdo->query("

            SELECT
                COUNT(*) AS total

            FROM usuarios

        ");


    $resultadoUsuarios =
        $stmtUsuarios->fetch(
            PDO::FETCH_ASSOC
        );


    $totalUsuarios =
        (int) (
            $resultadoUsuarios["total"]
            ?? 0
        );


    /*
    |--------------------------------------------------------------------------
    | E-MAILS CONFIRMADOS
    |--------------------------------------------------------------------------
    */

    $stmtConfirmados =
        $pdo->query("

            SELECT
                COUNT(*) AS total

            FROM usuarios

            WHERE
                ativo = 1

        ");


    $resultadoConfirmados =
        $stmtConfirmados->fetch(
            PDO::FETCH_ASSOC
        );


    $emailsConfirmados =
        (int) (
            $resultadoConfirmados["total"]
            ?? 0
        );


    /*
    |--------------------------------------------------------------------------
    | USUÁRIOS BLOQUEADOS
    |--------------------------------------------------------------------------
    */

    $stmtBloqueados =
        $pdo->query("

            SELECT
                COUNT(*) AS total

            FROM usuarios

            WHERE
                bloqueado = 1

        ");


    $resultadoBloqueados =
        $stmtBloqueados->fetch(
            PDO::FETCH_ASSOC
        );


    $usuariosBloqueados =
        (int) (
            $resultadoBloqueados["total"]
            ?? 0
        );


    /*
    |--------------------------------------------------------------------------
    | PROFESSORES PENDENTES
    |--------------------------------------------------------------------------
    */

    $stmtPendentes =
        $pdo->query("

            SELECT
                COUNT(*) AS total

            FROM perfil_professor pp

            INNER JOIN usuarios u
                ON u.id = pp.usuario_id

            INNER JOIN perfil_usuario pu
                ON pu.usuario_id = u.id

            WHERE
                pp.aprovado = 0

            AND
                pu.tipo_usuario = 'professor'

            AND
                u.tipo_acesso <> :tipo_admin

        ");


    /*
    |--------------------------------------------------------------------------
    | IMPORTANTE
    |--------------------------------------------------------------------------
    |
    | Como PDO::query() não aceita parâmetros nomeados,
    | fazemos esta consulta novamente com prepare().
    |
    */

    $stmtPendentes =
        $pdo->prepare("

            SELECT
                COUNT(*) AS total

            FROM perfil_professor pp

            INNER JOIN usuarios u
                ON u.id = pp.usuario_id

            INNER JOIN perfil_usuario pu
                ON pu.usuario_id = u.id

            WHERE
                pp.aprovado = 0

            AND
                pu.tipo_usuario = 'professor'

            AND
                u.tipo_acesso <> :tipo_admin

        ");


    $stmtPendentes->execute([

        ":tipo_admin" =>
            ROLE_ADMIN

    ]);


    $resultadoPendentes =
        $stmtPendentes->fetch(
            PDO::FETCH_ASSOC
        );


    $professoresPendentes =
        (int) (
            $resultadoPendentes["total"]
            ?? 0
        );


    /*
    |--------------------------------------------------------------------------
    | PROFESSORES APROVADOS
    |--------------------------------------------------------------------------
    */

    $stmtAprovados =
        $pdo->prepare("

            SELECT
                COUNT(*) AS total

            FROM perfil_professor pp

            INNER JOIN usuarios u
                ON u.id = pp.usuario_id

            INNER JOIN perfil_usuario pu
                ON pu.usuario_id = u.id

            WHERE
                pp.aprovado = 1

            AND
                pu.tipo_usuario = 'professor'

            AND
                u.tipo_acesso <> :tipo_admin

        ");


    $stmtAprovados->execute([

        ":tipo_admin" =>
            ROLE_ADMIN

    ]);


    $resultadoAprovados =
        $stmtAprovados->fetch(
            PDO::FETCH_ASSOC
        );


    $professoresAprovados =
        (int) (
            $resultadoAprovados["total"]
            ?? 0
        );

}
catch (Throwable $erro) {

    error_log(
        "[AdminPainel] Erro ao carregar estatísticas: " .
        $erro->getMessage()
    );

}


/*
|--------------------------------------------------------------------------
| E-MAILS NÃO CONFIRMADOS
|--------------------------------------------------------------------------
*/

$emailsNaoConfirmados =
    max(
        0,
        $totalUsuarios - $emailsConfirmados
    );


/*
|--------------------------------------------------------------------------
| LINKS ADMINISTRATIVOS
|--------------------------------------------------------------------------
|
| Agora os links internos carregam automaticamente
| o identificador da sessão administrativa.
|
*/

$linkPainel =
    adminUrl(
        "inicio.php"
    );


$linkProfessores =
    adminUrl(
        "professores.php"
    );


$linkUsuarios =
    adminUrl(
        "usuarios.php"
    );


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function adminPainelEscapar(
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
    content="Painel administrativo da plataforma Java, Café e Código."
>

<title>
    Administração | <?= SITE_NAME ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= filemtime(__DIR__ . "/../../assets/css/style.css") ?>"
>


<!-- =====================================================
MENSAGENS
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css?v=<?= filemtime(__DIR__ . "/../../assets/css/mensagem.css") ?>"
>


<!-- =====================================================
PAINEL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/estudante.css?v=<?= filemtime(__DIR__ . "/../../assets/css/estudante.css") ?>"
>


<!-- =====================================================
CONFIGURAÇÃO DE TEMA
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/configuracao_tema.css?v=<?= filemtime(__DIR__ . "/../../assets/css/configuracao_tema.css") ?>"
>

<!-- =====================================================
DASHBOARD ADMINISTRATIVO
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/admin_dashboard.css?v=<?= filemtime(__DIR__ . "/../../assets/css/admin_dashboard.css") ?>"
>
</head>


<body class="pagina-estudante pagina-admin">


<?php

require_once __DIR__ .
    "/../../componentes/header.php";

require_once __DIR__ .
    "/../../componentes/mensagem.php";

?>


<main class="painel-estudante">


<!-- =====================================================
APRESENTAÇÃO
====================================================== -->

<section class="painel-apresentacao">


    <div class="painel-apresentacao-conteudo">


        <span class="painel-etiqueta">
            ADMINISTRAÇÃO
        </span>


        <h1>

            Olá,

            <strong>

                <?= adminPainelEscapar(
                    $primeiroNome
                ) ?>!

            </strong>

        </h1>


        <p>

            Acompanhe as contas cadastradas,
            analise professores e controle
            permissões administrativas da plataforma.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= adminPainelEscapar($linkProfessores) ?>"
                class="botao-principal"
            >

                Gerenciar professores

                <span aria-hidden="true">
                    →
                </span>

            </a>


            <a
                href="<?= adminPainelEscapar($linkUsuarios) ?>"
                class="botao-secundario"
            >

                Gerenciar usuários

            </a>


        </div>


    </div>


    <!-- =====================================================
    REPRESENTAÇÃO VISUAL
    ====================================================== -->

    <div class="painel-apresentacao-codigo">


        <div class="codigo-topo">


            <div class="codigo-controles">

                <span></span>
                <span></span>
                <span></span>

            </div>


            <strong>
                Administracao.java
            </strong>


            <small>
                JAVA
            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">Administracao</span> {

    <span class="codigo-roxo">public void</span> <span class="codigo-amarelo">gerenciar</span>() {

        analisarProfessores();
        gerenciarUsuarios();
        controlarPermissoes();

    }

}</code></pre>


        <div class="codigo-status">


            <span>
                ● Acesso administrativo
            </span>


            <span>
                ADMIN
            </span>


        </div>


    </div>


</section>


<!-- =====================================================
VISÃO GERAL ADMINISTRATIVA
====================================================== -->

<section class="admin-dashboard-resumo">

    <div class="painel-secao-cabecalho admin-dashboard-cabecalho">

        <span class="painel-etiqueta">
            VISÃO GERAL
        </span>

        <h2>
            Resumo administrativo
        </h2>

        <p>
            Acompanhe os principais números
            e pendências da plataforma.
        </p>

    </div>


    <div class="admin-dashboard-grid">

        <article class="admin-dashboard-card">

            <span class="admin-dashboard-icone">
                US
            </span>

            <div>

                <small>
                    USUÁRIOS
                </small>

                <strong>
                    <?= adminPainelEscapar(
                        $totalUsuarios
                    ) ?>
                </strong>

                <p>
                    contas cadastradas
                </p>

            </div>

        </article>


        <article class="admin-dashboard-card">

            <span class="admin-dashboard-icone">
                OK
            </span>

            <div>

                <small>
                    E-MAILS CONFIRMADOS
                </small>

                <strong>
                    <?= adminPainelEscapar(
                        $emailsConfirmados
                    ) ?>
                </strong>

                <p>
                    de
                    <?= adminPainelEscapar(
                        $totalUsuarios
                    ) ?>
                    contas
                </p>

            </div>

        </article>


        <article class="admin-dashboard-card">

            <span class="admin-dashboard-icone">
                PR
            </span>

            <div>

                <small>
                    PROFESSORES APROVADOS
                </small>

                <strong>
                    <?= adminPainelEscapar(
                        $professoresAprovados
                    ) ?>
                </strong>

                <p>
                    com permissão liberada
                </p>

            </div>

        </article>


        <article class="admin-dashboard-card">

            <span class="admin-dashboard-icone">
                BL
            </span>

            <div>

                <small>
                    CONTAS BLOQUEADAS
                </small>

                <strong>
                    <?= adminPainelEscapar(
                        $usuariosBloqueados
                    ) ?>
                </strong>

                <p>
                    bloqueio administrativo
                </p>

            </div>

        </article>

    </div>

</section>



<!-- =====================================================
PENDÊNCIAS ADMINISTRATIVAS
====================================================== -->

<section class="admin-dashboard-pendencias">

    <div class="painel-secao-cabecalho">

        <span class="painel-etiqueta">
            ATENÇÃO NECESSÁRIA
        </span>

        <h2>
            Pendências
        </h2>

        <p>
            Situações que podem exigir
            uma ação administrativa.
        </p>

    </div>


    <div class="admin-dashboard-pendencias-grid">


        <article
            class="admin-dashboard-pendencia
            <?= $professoresPendentes > 0
                ? "admin-dashboard-pendencia-alerta"
                : "admin-dashboard-pendencia-ok" ?>"
        >

            <div class="admin-dashboard-pendencia-numero">

                <?= adminPainelEscapar(
                    $professoresPendentes
                ) ?>

            </div>


            <div class="admin-dashboard-pendencia-conteudo">

                <small>
                    PROFESSORES
                </small>

                <h3>
                    Aguardando análise
                </h3>

                <p>

                    <?php if ($professoresPendentes > 0): ?>

                        Existem cadastros de professores
                        aguardando aprovação administrativa.

                    <?php else: ?>

                        Nenhum professor aguardando análise.

                    <?php endif; ?>

                </p>


                <a
                    href="<?= adminPainelEscapar(
                        $linkProfessores
                    ) ?>"
                    class="botao-principal"
                >
                    Revisar professores
                </a>

            </div>

        </article>


        <article
            class="admin-dashboard-pendencia
            <?= $emailsNaoConfirmados > 0
                ? "admin-dashboard-pendencia-alerta"
                : "admin-dashboard-pendencia-ok" ?>"
        >

            <div class="admin-dashboard-pendencia-numero">

                <?= adminPainelEscapar(
                    $emailsNaoConfirmados
                ) ?>

            </div>


            <div class="admin-dashboard-pendencia-conteudo">

                <small>
                    CONTAS
                </small>

                <h3>
                    E-mails não confirmados
                </h3>

                <p>

                    <?php if ($emailsNaoConfirmados > 0): ?>

                        Existem usuários que ainda
                        não confirmaram o endereço de e-mail.

                    <?php else: ?>

                        Todos os e-mails cadastrados
                        estão confirmados.

                    <?php endif; ?>

                </p>


                <a
                    href="<?= adminPainelEscapar(
                        $linkUsuarios
                    ) ?>"
                    class="botao-secundario"
                >
                    Gerenciar usuários
                </a>

            </div>

        </article>

    </div>

</section>



<!-- =====================================================
AÇÕES RÁPIDAS
====================================================== -->

<section class="admin-dashboard-acoes">

    <div class="painel-secao-cabecalho">

        <span class="painel-etiqueta">
            ACESSO RÁPIDO
        </span>

        <h2>
            Ações rápidas
        </h2>

        <p>
            Acesse diretamente as áreas
            administrativas mais utilizadas.
        </p>

    </div>


    <div class="admin-dashboard-acoes-grid">


        <a
            href="<?= adminPainelEscapar(
                $linkProfessores
            ) ?>"
            class="admin-dashboard-acao"
        >

            <span>
                PR
            </span>

            <div>

                <strong>
                    Gerenciar professores
                </strong>

                <small>
                    Aprovação e permissões
                </small>

            </div>

            <b aria-hidden="true">
                →
            </b>

        </a>


        <a
            href="<?= adminPainelEscapar(
                $linkUsuarios
            ) ?>"
            class="admin-dashboard-acao"
        >

            <span>
                US
            </span>

            <div>

                <strong>
                    Gerenciar usuários
                </strong>

                <small>
                    Contas e bloqueios
                </small>

            </div>

            <b aria-hidden="true">
                →
            </b>

        </a>

    </div>

</section>


<!-- =====================================================
FERRAMENTAS ADMINISTRATIVAS
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">
            ADMINISTRAÇÃO
        </span>


        <h2>
            Ferramentas administrativas
        </h2>


        <p>

            Utilize os recursos disponíveis
            para acompanhar e controlar
            áreas específicas da plataforma.

        </p>


    </div>


    <div class="painel-acessos-grid">


        <!-- PROFESSORES -->

        <a
            href="<?= adminPainelEscapar($linkProfessores) ?>"
            class="acesso-card acesso-principal"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    PR
                </span>


                <?php if (
                    $professoresPendentes > 0
                ): ?>


                    <span class="acesso-status acesso-breve">

                        <?= adminPainelEscapar(
                            $professoresPendentes
                        ) ?>

                        pendente(s)

                    </span>


                <?php else: ?>


                    <span class="acesso-status acesso-disponivel">
                        Em dia
                    </span>


                <?php endif; ?>


            </div>


            <div>


                <small>
                    PROFESSORES
                </small>


                <h3>
                    Gerenciar professores
                </h3>


                <p>

                    Analise cadastros e controle
                    as permissões relacionadas
                    às Salas de Aula.

                </p>


            </div>


            <span class="acesso-seta">
                →
            </span>


        </a>


        <!-- USUÁRIOS -->

        <a
            href="<?= adminPainelEscapar($linkUsuarios) ?>"
            class="acesso-card acesso-principal"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    US
                </span>


                <span class="acesso-status acesso-disponivel">

                    <?= adminPainelEscapar(
                        $totalUsuarios
                    ) ?>

                    conta(s)

                </span>


            </div>


            <div>


                <small>
                    CONTAS
                </small>


                <h3>
                    Gerenciar usuários
                </h3>


                <p>

                    Consulte contas,
                    perfis, confirmação de e-mail
                    e bloqueios administrativos.

                </p>


            </div>


            <span class="acesso-seta">
                →
            </span>


        </a>


        <!-- PERMISSÕES -->

        <a
            href="<?= adminPainelEscapar($linkProfessores) ?>"
            class="acesso-card"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    SA
                </span>


                <span class="acesso-status acesso-disponivel">

                    <?= adminPainelEscapar(
                        $professoresAprovados
                    ) ?>

                    liberado(s)

                </span>


            </div>


            <div>


                <small>
                    PERMISSÕES
                </small>


                <h3>
                    Salas de Aula
                </h3>


                <p>

                    Controle quais professores
                    possuem autorização para criar
                    e gerenciar turmas.

                </p>


            </div>


            <span class="acesso-seta">
                →
            </span>


        </a>


        <!-- CONFIRMAÇÃO -->

        <a
            href="<?= adminPainelEscapar($linkUsuarios) ?>"
            class="acesso-card"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    @
                </span>


                <span class="acesso-status acesso-disponivel">

                    <?= adminPainelEscapar(
                        $emailsConfirmados
                    ) ?>

                    confirmado(s)

                </span>


            </div>


            <div>


                <small>
                    E-MAIL
                </small>


                <h3>
                    Confirmação de contas
                </h3>


                <p>

                    Consulte quais usuários
                    já concluíram a verificação
                    do endereço de e-mail.

                </p>


            </div>


            <span class="acesso-seta">
                →
            </span>


        </a>


        <!-- BLOQUEIOS -->

        <a
            href="<?= adminPainelEscapar($linkUsuarios) ?>"
            class="acesso-card"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    BL
                </span>


                <span
                    class="acesso-status <?= $usuariosBloqueados > 0 ? "acesso-breve" : "acesso-disponivel" ?>"
                >

                    <?= adminPainelEscapar(
                        $usuariosBloqueados
                    ) ?>

                    bloqueado(s)

                </span>


            </div>


            <div>


                <small>
                    SEGURANÇA
                </small>


                <h3>
                    Bloqueio de contas
                </h3>


                <p>

                    Suspenda ou libere
                    temporariamente o acesso
                    de contas da plataforma.

                </p>


            </div>


            <span class="acesso-seta">
                →
            </span>


        </a>


        <!-- SEGURANÇA -->

        <article class="acesso-card">


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    AD
                </span>


                <span class="acesso-status acesso-disponivel">
                    Protegido
                </span>


            </div>


            <div>


                <small>
                    SISTEMA
                </small>


                <h3>
                    Área administrativa
                </h3>


                <p>

                    A autorização administrativa
                    é validada diretamente no banco
                    e a navegação utiliza
                    um identificador exclusivo da sessão.

                </p>


            </div>


        </article>


    </div>


</section>


<!-- =====================================================
CONTROLE DE ACESSO
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">
            CONTROLE DE ACESSO
        </span>


        <h2>
            Estados diferentes, funções diferentes
        </h2>


        <p>

            A confirmação do e-mail,
            o bloqueio administrativo
            e o nível de acesso são controles
            independentes dentro da plataforma.

        </p>


    </div>


    <div class="painel-orientacao-etapas">


        <article>


            <span>
                01
            </span>


            <strong>
                ativo
            </strong>


            <p>

                Registra se o usuário concluiu
                a confirmação do endereço de e-mail.

            </p>


        </article>


        <article>


            <span>
                02
            </span>


            <strong>
                bloqueado
            </strong>


            <p>

                Registra se a conta foi suspensa
                administrativamente.

            </p>


        </article>


        <article>


            <span>
                03
            </span>


            <strong>
                tipo_acesso
            </strong>


            <p>

                Define se a conta possui
                acesso comum ou administrativo.

            </p>


        </article>


    </div>


</section>


<!-- =====================================================
AÇÕES FINAIS
====================================================== -->


<?php

require_once __DIR__ .
    "/../../componentes/configuracao_tema.php";

?>

<section class="painel-apresentacao-acoes">


    <a
        href="<?= adminPainelEscapar($linkProfessores) ?>"
        class="botao-principal"
    >
        Gerenciar professores
    </a>


    <a
        href="<?= adminPainelEscapar($linkUsuarios) ?>"
        class="botao-secundario"
    >
        Gerenciar usuários
    </a>


    <a
        href="<?= BASE_URL ?>/backend/auth/logout.php"
        class="botao-secundario"
    >
        Sair da conta
    </a>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../componentes/footer.php";

?>


<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= filemtime(__DIR__ . "/../../assets/js/theme.js") ?>"
></script>


</body>

</html>