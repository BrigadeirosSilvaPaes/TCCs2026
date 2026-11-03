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
| Confirma diretamente no banco:
|
| - usuário autenticado;
| - conta existente;
| - conta ativa;
| - conta não bloqueada;
| - tipo_acesso = admin.
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
| EXIGE IDENTIFICADOR VÁLIDO
|--------------------------------------------------------------------------
|
| Esta página somente pode ser aberta quando o identificador
| enviado através de ?acesso=... corresponde ao identificador
| armazenado na sessão administrativa atual.
|
*/

adminUrlExigir();


/*
|--------------------------------------------------------------------------
| ADMINISTRADOR ATUAL
|--------------------------------------------------------------------------
*/

$usuario =
    $_SESSION["usuario"];


$adminId =
    (int) (
        $usuario["id"]
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| PROFESSORES
|--------------------------------------------------------------------------
*/

$professoresPendentes =
    [];

$professoresAprovados =
    [];


/*
|--------------------------------------------------------------------------
| BUSCA PROFESSORES
|--------------------------------------------------------------------------
|
| Contas administrativas não aparecem nesta área,
| mesmo caso possuam algum perfil antigo de professor.
|
*/

try {

    $stmtProfessores =
        $pdo->prepare("

            SELECT

                u.id AS usuario_id,

                u.nome,

                u.email,

                u.ativo,

                u.bloqueado,

                u.tipo_acesso,

                pu.cidade,

                pu.estado,

                pp.instituicao,

                pp.disciplina,

                pp.aprovado,

                pp.data_aprovacao

            FROM perfil_professor pp

            INNER JOIN usuarios u
                ON u.id = pp.usuario_id

            INNER JOIN perfil_usuario pu
                ON pu.usuario_id = u.id

            WHERE
                pu.tipo_usuario = 'professor'

            AND
                u.tipo_acesso <> :tipo_admin

            ORDER BY

                pp.aprovado ASC,

                u.nome ASC

        ");


    $stmtProfessores->execute([

        ":tipo_admin" =>
            ROLE_ADMIN

    ]);


    $professores =
        $stmtProfessores->fetchAll(
            PDO::FETCH_ASSOC
        );


    foreach (
        $professores as $professor
    ) {

        if (
            (int) (
                $professor["aprovado"]
                ?? 0
            ) === 1
        ) {

            $professoresAprovados[] =
                $professor;

        }
        else {

            $professoresPendentes[] =
                $professor;

        }

    }

}
catch (Throwable $erro) {

    error_log(
        "[AdminProfessores] Erro ao carregar professores: " .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar a lista de professores.";

}


/*
|--------------------------------------------------------------------------
| TOTAIS
|--------------------------------------------------------------------------
*/

$totalPendentes =
    count(
        $professoresPendentes
    );


$totalAprovados =
    count(
        $professoresAprovados
    );


$totalProfessores =
    $totalPendentes
    +
    $totalAprovados;


/*
|--------------------------------------------------------------------------
| LINKS ADMINISTRATIVOS
|--------------------------------------------------------------------------
|
| Todos os links internos carregam automaticamente
| o identificador administrativo atual.
|
*/

$linkPainel =
    adminUrl(
        "inicio.php"
    );


$linkUsuarios =
    adminUrl(
        "usuarios.php"
    );


/*
|--------------------------------------------------------------------------
| TOKEN ADMINISTRATIVO
|--------------------------------------------------------------------------
|
| Será enviado dentro dos formulários POST.
|
| O backend não confiará apenas no usuario_id.
|
*/

$adminUrlToken =
    adminUrlToken();


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function adminProfessorEscapar(
    mixed $valor
): string
{

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );

}


/*
|--------------------------------------------------------------------------
| FORMATA LOCALIZAÇÃO
|--------------------------------------------------------------------------
*/

function adminProfessorLocalizacao(
    array $professor
): string
{

    $cidade =
        trim(
            (string) (
                $professor["cidade"]
                ?? ""
            )
        );


    $estado =
        strtoupper(
            trim(
                (string) (
                    $professor["estado"]
                    ?? ""
                )
            )
        );


    if (
        $cidade !== ""
        &&
        $estado !== ""
    ) {

        return
            $cidade .
            " - " .
            $estado;

    }


    if ($cidade !== "") {

        return $cidade;

    }


    if ($estado !== "") {

        return $estado;

    }


    return "Não informada";

}


/*
|--------------------------------------------------------------------------
| FORMATA DATA
|--------------------------------------------------------------------------
*/

function adminProfessorData(
    mixed $data
): string
{

    if (
        empty($data)
    ) {

        return "Não registrada";

    }


    try {

        $dataObjeto =
            new DateTime(
                (string) $data
            );


        return $dataObjeto->format(
            "d/m/Y H:i"
        );

    }
    catch (Throwable $erro) {

        return (string) $data;

    }

}


/*
|--------------------------------------------------------------------------
| CONFIRMAÇÃO DE E-MAIL
|--------------------------------------------------------------------------
*/

function adminProfessorConfirmacao(
    array $professor
): string
{

    return
        (int) (
            $professor["ativo"]
            ?? 0
        ) === 1
            ? "E-mail confirmado"
            : "Aguardando confirmação";

}


/*
|--------------------------------------------------------------------------
| BLOQUEIO ADMINISTRATIVO
|--------------------------------------------------------------------------
*/

function adminProfessorBloqueio(
    array $professor
): string
{

    return
        (int) (
            $professor["bloqueado"]
            ?? 0
        ) === 1
            ? "Conta bloqueada"
            : "Não bloqueada";

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
    content="Gerenciamento de professores da plataforma Java, Café e Código."
>

<title>
    Professores | Administração | <?= SITE_NAME ?>
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

            Gerenciar

            <strong>
                Professores
            </strong>

        </h1>


        <p>

            Analise os cadastros de professores
            e controle a permissão necessária
            para criação e gerenciamento
            das Salas de Aula.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= adminProfessorEscapar($linkPainel) ?>"
                class="botao-principal"
            >

                ← Painel administrativo

            </a>


            <a
                href="<?= adminProfessorEscapar($linkUsuarios) ?>"
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
                ProfessorAdmin.java
            </strong>


            <small>
                JAVA
            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">ProfessorAdmin</span> {

    <span class="codigo-roxo">public void</span> <span class="codigo-amarelo">analisar</span>() {

        verificarCadastro();
        controlarPermissao();
        liberarSalas();

    }

}</code></pre>


        <div class="codigo-status">


            <span>
                ● Controle administrativo
            </span>


            <span>
                PROFESSORES
            </span>


        </div>


    </div>


</section>


<!-- =====================================================
ESTATÍSTICAS
====================================================== -->

<section class="painel-estatisticas">


    <!-- TOTAL -->

    <article>


        <span class="estatistica-icone">
            PR
        </span>


        <div>


            <small>
                PROFESSORES
            </small>


            <strong>

                <?= adminProfessorEscapar(
                    $totalProfessores
                ) ?>

            </strong>


            <p>
                cadastros encontrados
            </p>


        </div>


    </article>


    <!-- PENDENTES -->

    <article>


        <span class="estatistica-icone">
            AN
        </span>


        <div>


            <small>
                AGUARDANDO ANÁLISE
            </small>


            <strong>

                <?= adminProfessorEscapar(
                    $totalPendentes
                ) ?>

            </strong>


            <p>
                cadastro(s) pendente(s)
            </p>


        </div>


    </article>


    <!-- APROVADOS -->

    <article>


        <span class="estatistica-icone">
            OK
        </span>


        <div>


            <small>
                APROVADOS
            </small>


            <strong>

                <?= adminProfessorEscapar(
                    $totalAprovados
                ) ?>

            </strong>


            <p>
                permissão de salas liberada
            </p>


        </div>


    </article>


</section>


<!-- =====================================================
REGRA DE APROVAÇÃO
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">
            PERMISSÃO
        </span>


        <h2>
            O que a aprovação libera?
        </h2>


        <p>

            A aprovação administrativa não define
            se o usuário pode utilizar o perfil Professor.

            Ela controla somente a autorização
            necessária para criar e gerenciar
            Salas de Aula.

        </p>


    </div>


    <div class="painel-orientacao-etapas">


        <article>


            <span>
                01
            </span>


            <strong>
                Não aprovado
            </strong>


            <p>

                Utiliza normalmente os recursos
                gerais da plataforma.

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

                O administrador verifica instituição
                e área de atuação informadas.

            </p>


        </article>


        <article>


            <span>
                03
            </span>


            <strong>
                Aprovado
            </strong>


            <p>

                Recebe permissão para criar
                e gerenciar Salas de Aula.

            </p>


        </article>


    </div>


</section>


<!-- =====================================================
PROFESSORES PENDENTES
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">
            ANÁLISE
        </span>


        <h2>
            Aguardando aprovação
        </h2>


        <p>

            Professores que ainda não possuem
            autorização administrativa
            para criar e gerenciar salas.

        </p>


    </div>


    <?php if (
        empty(
            $professoresPendentes
        )
    ): ?>


        <div class="painel-acessos-grid">


            <article class="acesso-card">


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        OK
                    </span>


                    <span class="acesso-status acesso-disponivel">
                        Em dia
                    </span>


                </div>


                <div>


                    <small>
                        TUDO CERTO
                    </small>


                    <h3>
                        Nenhuma análise pendente
                    </h3>


                    <p>

                        Não existem professores
                        aguardando aprovação administrativa.

                    </p>


                </div>


            </article>


        </div>


    <?php else: ?>


        <div class="painel-acessos-grid">


            <?php foreach (
                $professoresPendentes
                as $professor
            ): ?>


                <?php

                $professorId =
                    (int) (
                        $professor["usuario_id"]
                        ?? 0
                    );


                $nome =
                    trim(
                        (string) (
                            $professor["nome"]
                            ?? "Professor"
                        )
                    );


                $email =
                    trim(
                        (string) (
                            $professor["email"]
                            ?? ""
                        )
                    );


                $instituicao =
                    trim(
                        (string) (
                            $professor["instituicao"]
                            ?? ""
                        )
                    );


                $disciplina =
                    trim(
                        (string) (
                            $professor["disciplina"]
                            ?? ""
                        )
                    );


                $localizacao =
                    adminProfessorLocalizacao(
                        $professor
                    );


                $confirmacao =
                    adminProfessorConfirmacao(
                        $professor
                    );


                $bloqueio =
                    adminProfessorBloqueio(
                        $professor
                    );


                $estaBloqueado =
                    (int) (
                        $professor["bloqueado"]
                        ?? 0
                    ) === 1;


                $emailConfirmado =
                    (int) (
                        $professor["ativo"]
                        ?? 0
                    ) === 1;

                ?>


                <article class="acesso-card acesso-principal">


                    <div class="acesso-card-topo">


                        <span class="acesso-icone">
                            PR
                        </span>


                        <span class="acesso-status acesso-breve">
                            Em análise
                        </span>


                    </div>


                    <div>


                        <small>
                            PROFESSOR
                        </small>


                        <h3>

                            <?= adminProfessorEscapar(
                                $nome
                            ) ?>

                        </h3>


                        <p>

                            <strong>
                                E-mail:
                            </strong>

                            <?= adminProfessorEscapar(
                                $email !== ""
                                    ? $email
                                    : "Não informado"
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Instituição:
                            </strong>

                            <?= adminProfessorEscapar(
                                $instituicao !== ""
                                    ? $instituicao
                                    : "Não informada"
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Disciplina / área:
                            </strong>

                            <?= adminProfessorEscapar(
                                $disciplina !== ""
                                    ? $disciplina
                                    : "Não informada"
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Localização:
                            </strong>

                            <?= adminProfessorEscapar(
                                $localizacao
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Confirmação:
                            </strong>

                            <?= adminProfessorEscapar(
                                $confirmacao
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Bloqueio:
                            </strong>

                            <?= adminProfessorEscapar(
                                $bloqueio
                            ) ?>

                        </p>


                    </div>


                    <?php if (
                        !$estaBloqueado
                        &&
                        $emailConfirmado
                    ): ?>


                        <form
                            action="<?= BASE_URL ?>/backend/admin/aprovar_professor.php"
                            method="POST"
                        >


                            <input
                                type="hidden"
                                name="usuario_id"
                                value="<?= adminProfessorEscapar($professorId) ?>"
                            >


                            <!-- =============================================
                            IDENTIFICADOR ADMINISTRATIVO
                            ============================================== -->

                            <input
                                type="hidden"
                                name="admin_url_token"
                                value="<?= adminProfessorEscapar($adminUrlToken) ?>"
                            >


                            <button
                                type="submit"
                                class="botao"
                            >

                                Aprovar professor

                            </button>


                        </form>


                    <?php elseif ($estaBloqueado): ?>


                        <p>

                            A conta está bloqueada.
                            Desbloqueie o usuário antes
                            de conceder a permissão.

                        </p>


                    <?php else: ?>


                        <p>

                            O endereço de e-mail ainda
                            não foi confirmado.

                            A aprovação ficará disponível
                            após a confirmação da conta.

                        </p>


                    <?php endif; ?>


                </article>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</section>


<!-- =====================================================
PROFESSORES APROVADOS
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">
            AUTORIZADOS
        </span>


        <h2>
            Professores aprovados
        </h2>


        <p>

            Professores que possuem atualmente
            autorização administrativa
            para criação e gerenciamento
            de Salas de Aula.

        </p>


    </div>


    <?php if (
        empty(
            $professoresAprovados
        )
    ): ?>


        <div class="painel-acessos-grid">


            <article class="acesso-card">


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        PR
                    </span>


                    <span class="acesso-status acesso-breve">
                        Sem registros
                    </span>


                </div>


                <div>


                    <small>
                        APROVAÇÃO
                    </small>


                    <h3>
                        Nenhum professor aprovado
                    </h3>


                    <p>

                        Ainda não existem professores
                        com autorização administrativa
                        para gerenciamento de salas.

                    </p>


                </div>


            </article>


        </div>


    <?php else: ?>


        <div class="painel-acessos-grid">


            <?php foreach (
                $professoresAprovados
                as $professor
            ): ?>


                <?php

                $professorId =
                    (int) (
                        $professor["usuario_id"]
                        ?? 0
                    );


                $nome =
                    trim(
                        (string) (
                            $professor["nome"]
                            ?? "Professor"
                        )
                    );


                $email =
                    trim(
                        (string) (
                            $professor["email"]
                            ?? ""
                        )
                    );


                $instituicao =
                    trim(
                        (string) (
                            $professor["instituicao"]
                            ?? ""
                        )
                    );


                $disciplina =
                    trim(
                        (string) (
                            $professor["disciplina"]
                            ?? ""
                        )
                    );


                $localizacao =
                    adminProfessorLocalizacao(
                        $professor
                    );


                $dataAprovacao =
                    adminProfessorData(
                        $professor["data_aprovacao"]
                        ?? null
                    );


                $confirmacao =
                    adminProfessorConfirmacao(
                        $professor
                    );


                $bloqueio =
                    adminProfessorBloqueio(
                        $professor
                    );

                ?>


                <article class="acesso-card acesso-principal">


                    <div class="acesso-card-topo">


                        <span class="acesso-icone">
                            PR
                        </span>


                        <span class="acesso-status acesso-disponivel">
                            Aprovado
                        </span>


                    </div>


                    <div>


                        <small>
                            PROFESSOR
                        </small>


                        <h3>

                            <?= adminProfessorEscapar(
                                $nome
                            ) ?>

                        </h3>


                        <p>

                            <strong>
                                E-mail:
                            </strong>

                            <?= adminProfessorEscapar(
                                $email !== ""
                                    ? $email
                                    : "Não informado"
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Instituição:
                            </strong>

                            <?= adminProfessorEscapar(
                                $instituicao !== ""
                                    ? $instituicao
                                    : "Não informada"
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Disciplina / área:
                            </strong>

                            <?= adminProfessorEscapar(
                                $disciplina !== ""
                                    ? $disciplina
                                    : "Não informada"
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Localização:
                            </strong>

                            <?= adminProfessorEscapar(
                                $localizacao
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Confirmação:
                            </strong>

                            <?= adminProfessorEscapar(
                                $confirmacao
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Bloqueio:
                            </strong>

                            <?= adminProfessorEscapar(
                                $bloqueio
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Aprovado em:
                            </strong>

                            <?= adminProfessorEscapar(
                                $dataAprovacao
                            ) ?>

                        </p>


                    </div>


                    <form
                        action="<?= BASE_URL ?>/backend/admin/recusar_professor.php"
                        method="POST"
                    >


                        <input
                            type="hidden"
                            name="usuario_id"
                            value="<?= adminProfessorEscapar($professorId) ?>"
                        >


                        <!-- =============================================
                        IDENTIFICADOR ADMINISTRATIVO
                        ============================================== -->

                        <input
                            type="hidden"
                            name="admin_url_token"
                            value="<?= adminProfessorEscapar($adminUrlToken) ?>"
                        >


                        <button
                            type="submit"
                            class="botao-secundario"
                        >

                            Remover aprovação

                        </button>


                    </form>


                </article>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</section>


<!-- =====================================================
AÇÕES FINAIS
====================================================== -->

<section class="painel-apresentacao-acoes">


    <a
        href="<?= adminProfessorEscapar($linkPainel) ?>"
        class="botao-principal"
    >

        ← Voltar ao painel

    </a>


    <a
        href="<?= adminProfessorEscapar($linkUsuarios) ?>"
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