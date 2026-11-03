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
| EXIGE IDENTIFICADOR VÁLIDO NA URL
|--------------------------------------------------------------------------
|
| Diferente do inicio.php, esta página não gera automaticamente
| um novo endereço.
|
| Ela só pode ser acessada através de um link administrativo
| que já contenha o identificador correto da sessão.
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
| LISTAS
|--------------------------------------------------------------------------
*/

$usuariosNaoBloqueados =
    [];

$usuariosBloqueados =
    [];


/*
|--------------------------------------------------------------------------
| BUSCA USUÁRIOS
|--------------------------------------------------------------------------
|
| ativo:
|
| 0 = e-mail ainda não confirmado
| 1 = e-mail confirmado
|
| bloqueado:
|
| 0 = sem bloqueio administrativo
| 1 = bloqueado pelo administrador
|
*/

try {

    $stmtUsuarios =
        $pdo->query("

            SELECT

                u.id,

                u.nome,

                u.email,

                u.ativo,

                u.bloqueado,

                u.tipo_acesso,

                pu.tipo_usuario,

                pu.cidade,

                pu.estado

            FROM usuarios u

            LEFT JOIN perfil_usuario pu
                ON pu.usuario_id = u.id

            ORDER BY

                u.bloqueado ASC,

                u.nome ASC

        ");


    $usuarios =
        $stmtUsuarios->fetchAll(
            PDO::FETCH_ASSOC
        );


    foreach (
        $usuarios as $usuarioLista
    ) {

        if (
            (int) (
                $usuarioLista["bloqueado"]
                ?? 0
            ) === 1
        ) {

            $usuariosBloqueados[] =
                $usuarioLista;

        }
        else {

            $usuariosNaoBloqueados[] =
                $usuarioLista;

        }

    }

}
catch (Throwable $erro) {

    error_log(
        "[AdminUsuarios] Erro ao carregar usuários: " .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar os usuários.";

}


/*
|--------------------------------------------------------------------------
| TOTAIS
|--------------------------------------------------------------------------
*/

$totalNaoBloqueados =
    count(
        $usuariosNaoBloqueados
    );


$totalBloqueados =
    count(
        $usuariosBloqueados
    );


$totalUsuarios =
    $totalNaoBloqueados
    +
    $totalBloqueados;


/*
|--------------------------------------------------------------------------
| CONFIRMAÇÃO
|--------------------------------------------------------------------------
*/

$totalConfirmados =
    0;

$totalNaoConfirmados =
    0;


/*
|--------------------------------------------------------------------------
| PERFIS
|--------------------------------------------------------------------------
*/

$totalEstudantes =
    0;

$totalAprendizes =
    0;

$totalProfessores =
    0;

$totalAdministradores =
    0;


/*
|--------------------------------------------------------------------------
| CONTAGEM
|--------------------------------------------------------------------------
*/

foreach (
    array_merge(
        $usuariosNaoBloqueados,
        $usuariosBloqueados
    )
    as $usuarioContagem
) {

    /*
    |--------------------------------------------------------------------------
    | CONFIRMAÇÃO
    |--------------------------------------------------------------------------
    */

    if (
        (int) (
            $usuarioContagem["ativo"]
            ?? 0
        ) === 1
    ) {

        $totalConfirmados++;

    }
    else {

        $totalNaoConfirmados++;

    }


    /*
    |--------------------------------------------------------------------------
    | ADMINISTRADORES
    |--------------------------------------------------------------------------
    */

    if (
        ($usuarioContagem["tipo_acesso"] ?? "")
        === ROLE_ADMIN
    ) {

        $totalAdministradores++;

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */

    switch (
        $usuarioContagem["tipo_usuario"]
        ?? ""
    ) {

        case "estudante":

            $totalEstudantes++;

            break;


        case "aprendiz":

            $totalAprendizes++;

            break;


        case "professor":

            $totalProfessores++;

            break;

    }

}


/*
|--------------------------------------------------------------------------
| LINKS ADMINISTRATIVOS
|--------------------------------------------------------------------------
|
| Todos os links internos passam pelo adminUrl().
|
| Portanto:
|
| inicio.php
| usuarios.php
| professores.php
|
| compartilham o identificador da sessão administrativa atual.
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


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function adminUsuarioEscapar(
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
| NOME DO PERFIL
|--------------------------------------------------------------------------
*/

function adminUsuarioPerfil(
    array $usuario
): string
{

    if (
        ($usuario["tipo_acesso"] ?? "")
        === ROLE_ADMIN
    ) {

        return "Administrador";

    }


    return match (
        $usuario["tipo_usuario"]
        ?? ""
    ) {

        "estudante" =>
            "Estudante",

        "aprendiz" =>
            "Aprendiz",

        "professor" =>
            "Professor",

        default =>
            "Perfil não configurado"

    };

}


/*
|--------------------------------------------------------------------------
| LOCALIZAÇÃO
|--------------------------------------------------------------------------
*/

function adminUsuarioLocalizacao(
    array $usuario
): string
{

    $cidade =
        trim(
            (string) (
                $usuario["cidade"]
                ?? ""
            )
        );


    $estado =
        strtoupper(
            trim(
                (string) (
                    $usuario["estado"]
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
| CONFIRMAÇÃO
|--------------------------------------------------------------------------
*/

function adminUsuarioConfirmacao(
    array $usuario
): string
{

    return
        (int) (
            $usuario["ativo"]
            ?? 0
        ) === 1
            ? "E-mail confirmado"
            : "Aguardando confirmação";

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
    content="Gerenciamento de usuários da plataforma Java, Café e Código."
>

<title>
    Usuários | Administração | <?= SITE_NAME ?>
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
                usuários
            </strong>

        </h1>


        <p>

            Consulte as contas cadastradas,
            acompanhe a confirmação dos endereços de e-mail
            e controle bloqueios administrativos.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= adminUsuarioEscapar($linkPainel) ?>"
                class="botao-principal"
            >

                ← Painel administrativo

            </a>


            <a
                href="<?= adminUsuarioEscapar($linkProfessores) ?>"
                class="botao-secundario"
            >

                Gerenciar professores

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
                UsuariosAdmin.java
            </strong>


            <small>
                JAVA
            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">UsuariosAdmin</span> {

    <span class="codigo-roxo">public void</span> <span class="codigo-amarelo">gerenciar</span>() {

        consultarContas();
        verificarEmail();
        controlarBloqueios();

    }

}</code></pre>


        <div class="codigo-status">


            <span>
                ● Controle de contas
            </span>


            <span>
                ADMIN
            </span>


        </div>


    </div>


</section>


<!-- =====================================================
ESTATÍSTICAS
====================================================== -->

<section class="painel-estatisticas">


    <!-- USUÁRIOS -->

    <article>


        <span class="estatistica-icone">
            US
        </span>


        <div>


            <small>
                USUÁRIOS
            </small>


            <strong>

                <?= adminUsuarioEscapar(
                    $totalUsuarios
                ) ?>

            </strong>


            <p>
                contas cadastradas
            </p>


        </div>


    </article>


    <!-- CONFIRMADOS -->

    <article>


        <span class="estatistica-icone">
            @
        </span>


        <div>


            <small>
                E-MAIL CONFIRMADO
            </small>


            <strong>

                <?= adminUsuarioEscapar(
                    $totalConfirmados
                ) ?>

                <span>

                    /

                    <?= adminUsuarioEscapar(
                        $totalUsuarios
                    ) ?>

                </span>

            </strong>


            <p>

                <?= adminUsuarioEscapar(
                    $totalNaoConfirmados
                ) ?>

                aguardando confirmação

            </p>


        </div>


    </article>


    <!-- BLOQUEADOS -->

    <article>


        <span class="estatistica-icone">
            BL
        </span>


        <div>


            <small>
                BLOQUEADOS
            </small>


            <strong>

                <?= adminUsuarioEscapar(
                    $totalBloqueados
                ) ?>

            </strong>


            <p>
                bloqueio administrativo
            </p>


        </div>


    </article>


</section>


<!-- =====================================================
PERFIS CADASTRADOS
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">
            PERFIS
        </span>


        <h2>
            Perfis cadastrados
        </h2>


        <p>

            Visão geral dos tipos de usuários
            existentes atualmente na plataforma.

        </p>


    </div>


    <div class="painel-acessos-grid">


        <!-- ESTUDANTE -->

        <article class="acesso-card">


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    ES
                </span>


                <span class="acesso-status acesso-disponivel">

                    <?= adminUsuarioEscapar(
                        $totalEstudantes
                    ) ?>

                </span>


            </div>


            <div>


                <small>
                    ESTUDANTE
                </small>


                <h3>

                    <?= adminUsuarioEscapar(
                        $totalEstudantes
                    ) ?>

                    estudante(s)

                </h3>


                <p>

                    Usuários que escolheram
                    o perfil Estudante.

                </p>


            </div>


        </article>


        <!-- APRENDIZ -->

        <article class="acesso-card">


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    AP
                </span>


                <span class="acesso-status acesso-disponivel">

                    <?= adminUsuarioEscapar(
                        $totalAprendizes
                    ) ?>

                </span>


            </div>


            <div>


                <small>
                    APRENDIZ
                </small>


                <h3>

                    <?= adminUsuarioEscapar(
                        $totalAprendizes
                    ) ?>

                    aprendiz(es)

                </h3>


                <p>

                    Usuários que utilizam
                    a plataforma de forma independente.

                </p>


            </div>


        </article>


        <!-- PROFESSOR -->

        <a
            href="<?= adminUsuarioEscapar($linkProfessores) ?>"
            class="acesso-card acesso-principal"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    PR
                </span>


                <span class="acesso-status acesso-disponivel">

                    <?= adminUsuarioEscapar(
                        $totalProfessores
                    ) ?>

                </span>


            </div>


            <div>


                <small>
                    PROFESSOR
                </small>


                <h3>

                    <?= adminUsuarioEscapar(
                        $totalProfessores
                    ) ?>

                    professor(es)

                </h3>


                <p>

                    Consulte também
                    as permissões administrativas
                    relacionadas às Salas de Aula.

                </p>


            </div>


            <span class="acesso-seta">
                →
            </span>


        </a>


        <!-- ADMIN -->

        <article class="acesso-card acesso-principal">


            <div class="acesso-card-topo">


                <span class="acesso-icone">
                    AD
                </span>


                <span class="acesso-status acesso-disponivel">

                    <?= adminUsuarioEscapar(
                        $totalAdministradores
                    ) ?>

                </span>


            </div>


            <div>


                <small>
                    ADMIN
                </small>


                <h3>

                    <?= adminUsuarioEscapar(
                        $totalAdministradores
                    ) ?>

                    administrador(es)

                </h3>


                <p>

                    Contas que possuem
                    acesso às funções administrativas
                    da plataforma.

                </p>


            </div>


        </article>


    </div>


</section>


<!-- =====================================================
USUÁRIOS NÃO BLOQUEADOS
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">
            CONTAS
        </span>


        <h2>
            Usuários não bloqueados
        </h2>


        <p>

            Contas que não possuem bloqueio administrativo.
            A confirmação do e-mail aparece separadamente.

        </p>


    </div>


    <?php if (
        empty(
            $usuariosNaoBloqueados
        )
    ): ?>


        <div class="painel-acessos-grid">


            <article class="acesso-card">


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        US
                    </span>


                    <span class="acesso-status acesso-breve">
                        Sem registros
                    </span>


                </div>


                <div>


                    <small>
                        CONTAS
                    </small>


                    <h3>
                        Nenhum usuário disponível
                    </h3>


                    <p>

                        Não existem contas sem
                        bloqueio administrativo.

                    </p>


                </div>


            </article>


        </div>


    <?php else: ?>


        <div class="painel-acessos-grid">


            <?php foreach (
                $usuariosNaoBloqueados
                as $usuarioLista
            ): ?>


                <?php

                $id =
                    (int) (
                        $usuarioLista["id"]
                        ?? 0
                    );


                $nome =
                    trim(
                        (string) (
                            $usuarioLista["nome"]
                            ?? "Usuário"
                        )
                    );


                $email =
                    trim(
                        (string) (
                            $usuarioLista["email"]
                            ?? ""
                        )
                    );


                $perfil =
                    adminUsuarioPerfil(
                        $usuarioLista
                    );


                $localizacao =
                    adminUsuarioLocalizacao(
                        $usuarioLista
                    );


                $confirmacao =
                    adminUsuarioConfirmacao(
                        $usuarioLista
                    );


                $propriaConta =
                    $id === $adminId;


                $ehAdministrador =
                    ($usuarioLista["tipo_acesso"] ?? "")
                    === ROLE_ADMIN;


                $emailConfirmado =
                    (int) (
                        $usuarioLista["ativo"]
                        ?? 0
                    ) === 1;

                ?>


                <article class="acesso-card <?= $ehAdministrador ? "acesso-principal" : "" ?>">


                    <div class="acesso-card-topo">


                        <span class="acesso-icone">

                            <?= $ehAdministrador
                                ? "AD"
                                : "US"
                            ?>

                        </span>


                        <span
                            class="acesso-status <?= $emailConfirmado ? "acesso-disponivel" : "acesso-breve" ?>"
                        >

                            <?= $emailConfirmado
                                ? "Confirmado"
                                : "Não confirmado"
                            ?>

                        </span>


                    </div>


                    <div>


                        <small>

                            <?= adminUsuarioEscapar(
                                $perfil
                            ) ?>

                        </small>


                        <h3>

                            <?= adminUsuarioEscapar(
                                $nome
                            ) ?>

                        </h3>


                        <?php if ($propriaConta): ?>


                            <p>

                                <strong>
                                    Esta é sua conta administrativa.
                                </strong>

                            </p>


                        <?php endif; ?>


                        <p>

                            <strong>
                                E-mail:
                            </strong>

                            <?= adminUsuarioEscapar(
                                $email !== ""
                                    ? $email
                                    : "Não informado"
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Perfil:
                            </strong>

                            <?= adminUsuarioEscapar(
                                $perfil
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Localização:
                            </strong>

                            <?= adminUsuarioEscapar(
                                $localizacao
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Confirmação:
                            </strong>

                            <?= adminUsuarioEscapar(
                                $confirmacao
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Bloqueio:
                            </strong>

                            Não bloqueado

                        </p>


                    </div>


                    <?php if (!$ehAdministrador): ?>


                        <form
                            action="<?= BASE_URL ?>/backend/admin/bloquear_usuario.php"
                            method="POST"
                        >


                            <input
                                type="hidden"
                                name="usuario_id"
                                value="<?= adminUsuarioEscapar($id) ?>"
                            >


                            <input
                                type="hidden"
                                name="acao"
                                value="bloquear"
                            >


                            <!--
                            Identificador necessário para que o backend
                            consiga retornar à mesma sessão administrativa.
                            -->

                            <input
                                type="hidden"
                                name="admin_url_token"
                                value="<?= adminUsuarioEscapar(adminUrlToken()) ?>"
                            >


                            <button
                                type="submit"
                                class="botao-secundario"
                            >

                                Bloquear conta

                            </button>


                        </form>


                    <?php else: ?>


                        <p>

                            Contas administrativas
                            não podem ser bloqueadas
                            por esta área.

                        </p>


                    <?php endif; ?>


                </article>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</section>


<!-- =====================================================
USUÁRIOS BLOQUEADOS
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">
            SEGURANÇA
        </span>


        <h2>
            Usuários bloqueados
        </h2>


        <p>

            Contas cujo acesso foi suspenso
            administrativamente.

            O bloqueio não altera
            a confirmação do endereço de e-mail.

        </p>


    </div>


    <?php if (
        empty(
            $usuariosBloqueados
        )
    ): ?>


        <div class="painel-acessos-grid">


            <article class="acesso-card">


                <div class="acesso-card-topo">


                    <span class="acesso-icone">
                        OK
                    </span>


                    <span class="acesso-status acesso-disponivel">
                        Tudo certo
                    </span>


                </div>


                <div>


                    <small>
                        BLOQUEIOS
                    </small>


                    <h3>
                        Nenhuma conta bloqueada
                    </h3>


                    <p>

                        Não existem usuários
                        bloqueados neste momento.

                    </p>


                </div>


            </article>


        </div>


    <?php else: ?>


        <div class="painel-acessos-grid">


            <?php foreach (
                $usuariosBloqueados
                as $usuarioLista
            ): ?>


                <?php

                $id =
                    (int) (
                        $usuarioLista["id"]
                        ?? 0
                    );


                $nome =
                    trim(
                        (string) (
                            $usuarioLista["nome"]
                            ?? "Usuário"
                        )
                    );


                $email =
                    trim(
                        (string) (
                            $usuarioLista["email"]
                            ?? ""
                        )
                    );


                $perfil =
                    adminUsuarioPerfil(
                        $usuarioLista
                    );


                $localizacao =
                    adminUsuarioLocalizacao(
                        $usuarioLista
                    );


                $confirmacao =
                    adminUsuarioConfirmacao(
                        $usuarioLista
                    );

                ?>


                <article class="acesso-card acesso-indisponivel">


                    <div class="acesso-card-topo">


                        <span class="acesso-icone">
                            BL
                        </span>


                        <span class="acesso-status acesso-breve">
                            Bloqueado
                        </span>


                    </div>


                    <div>


                        <small>

                            <?= adminUsuarioEscapar(
                                $perfil
                            ) ?>

                        </small>


                        <h3>

                            <?= adminUsuarioEscapar(
                                $nome
                            ) ?>

                        </h3>


                        <p>

                            <strong>
                                E-mail:
                            </strong>

                            <?= adminUsuarioEscapar(
                                $email !== ""
                                    ? $email
                                    : "Não informado"
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Perfil:
                            </strong>

                            <?= adminUsuarioEscapar(
                                $perfil
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Localização:
                            </strong>

                            <?= adminUsuarioEscapar(
                                $localizacao
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Confirmação:
                            </strong>

                            <?= adminUsuarioEscapar(
                                $confirmacao
                            ) ?>

                        </p>


                        <p>

                            <strong>
                                Bloqueio:
                            </strong>

                            Bloqueado administrativamente

                        </p>


                    </div>


                    <form
                        action="<?= BASE_URL ?>/backend/admin/bloquear_usuario.php"
                        method="POST"
                    >


                        <input
                            type="hidden"
                            name="usuario_id"
                            value="<?= adminUsuarioEscapar($id) ?>"
                        >


                        <input
                            type="hidden"
                            name="acao"
                            value="desbloquear"
                        >


                        <input
                            type="hidden"
                            name="admin_url_token"
                            value="<?= adminUsuarioEscapar(adminUrlToken()) ?>"
                        >


                        <button
                            type="submit"
                            class="botao-principal"
                        >

                            Desbloquear conta

                        </button>


                    </form>


                </article>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</section>


<!-- =====================================================
EXPLICAÇÃO DOS STATUS
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">
            STATUS DA CONTA
        </span>


        <h2>
            Controles independentes
        </h2>


        <p>

            A confirmação do endereço de e-mail
            e o bloqueio administrativo possuem
            funções diferentes dentro da plataforma.

        </p>


    </div>


    <div class="painel-orientacao-etapas">


        <article>


            <span>
                01
            </span>


            <strong>
                E-mail confirmado
            </strong>


            <p>

                O usuário concluiu a validação
                utilizando o código enviado
                após o cadastro.

            </p>


        </article>


        <article>


            <span>
                02
            </span>


            <strong>
                Aguardando confirmação
            </strong>


            <p>

                O usuário ainda não concluiu
                a verificação do endereço
                de e-mail.

            </p>


        </article>


        <article>


            <span>
                03
            </span>


            <strong>
                Bloqueio administrativo
            </strong>


            <p>

                Suspende temporariamente
                o acesso sem alterar
                a confirmação do e-mail.

            </p>


        </article>


    </div>


</section>


<!-- =====================================================
AÇÕES FINAIS
====================================================== -->

<section class="painel-apresentacao-acoes">


    <a
        href="<?= adminUsuarioEscapar($linkPainel) ?>"
        class="botao-principal"
    >

        ← Voltar ao painel

    </a>


    <a
        href="<?= adminUsuarioEscapar($linkProfessores) ?>"
        class="botao-secundario"
    >

        Gerenciar professores

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