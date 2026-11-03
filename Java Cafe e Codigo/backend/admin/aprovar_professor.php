<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/bootstrap.php";


/*
|--------------------------------------------------------------------------
| SEGURANÇA ADMINISTRATIVA
|--------------------------------------------------------------------------
|
| Confirma diretamente no banco que a conta:
|
| - existe;
| - está ativa;
| - não está bloqueada;
| - continua sendo administrador.
|
*/

require_once __DIR__ . "/../middleware/verificar_admin.php";


/*
|--------------------------------------------------------------------------
| SEGURANÇA DA URL ADMINISTRATIVA
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../security/admin_url.php";


/*
|--------------------------------------------------------------------------
| ADMINISTRADOR ATUAL
|--------------------------------------------------------------------------
*/

$adminId =
    (int) (
        $_SESSION["usuario"]["id"]
        ?? 0
    );


if ($adminId <= 0) {

    unset(
        $_SESSION["usuario"]
    );

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/login/login.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| DESTINO
|--------------------------------------------------------------------------
|
| O retorno sempre preserva:
|
| professores.php?acesso=TOKEN
|
*/

$linkProfessores =
    adminUrl(
        "professores.php"
    );


/*
|--------------------------------------------------------------------------
| ACEITA SOMENTE POST
|--------------------------------------------------------------------------
*/

if (
    ($_SERVER["REQUEST_METHOD"] ?? "")
    !== "POST"
) {

    $_SESSION["erro"] =
        "Requisição inválida.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| IDENTIFICADOR ADMINISTRATIVO
|--------------------------------------------------------------------------
*/

$adminUrlTokenRecebido =
    $_POST["admin_url_token"]
    ?? "";


if (
    !is_string(
        $adminUrlTokenRecebido
    )
) {

    $adminUrlTokenRecebido =
        "";

}


$adminUrlTokenRecebido =
    trim(
        $adminUrlTokenRecebido
    );


/*
|--------------------------------------------------------------------------
| VALIDA IDENTIFICADOR ADMINISTRATIVO
|--------------------------------------------------------------------------
|
| O token recebido precisa corresponder ao token
| armazenado na sessão administrativa atual.
|
*/

if (
    !adminUrlValidar(
        $adminUrlTokenRecebido
    )
) {

    error_log(
        "[AdminAprovarProfessor] " .
        "Tentativa com token administrativo inválido. " .
        "Admin ID: " .
        $adminId
    );


    $_SESSION["erro"] =
        "Requisição administrativa inválida.";


    header(
        "Location: " .
        adminUrl(
            "inicio.php"
        )
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| RECEBE PROFESSOR
|--------------------------------------------------------------------------
*/

$usuarioId =
    filter_input(
        INPUT_POST,
        "usuario_id",
        FILTER_VALIDATE_INT
    );


$usuarioId =
    $usuarioId !== false &&
    $usuarioId !== null
        ? (int) $usuarioId
        : 0;


/*
|--------------------------------------------------------------------------
| VALIDA ID
|--------------------------------------------------------------------------
*/

if (
    $usuarioId <= 0
) {

    $_SESSION["erro"] =
        "Professor inválido.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| IMPEDE AÇÃO SOBRE A PRÓPRIA CONTA
|--------------------------------------------------------------------------
*/

if (
    $usuarioId === $adminId
) {

    $_SESSION["erro"] =
        "Você não pode executar esta ação sobre sua própria conta.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| BUSCA E VALIDA PROFESSOR
|--------------------------------------------------------------------------
|
| Não confiamos somente no ID recebido.
|
| Consultamos novamente:
|
| - existência da conta;
| - perfil principal;
| - perfil de professor;
| - confirmação de e-mail;
| - bloqueio;
| - tipo de acesso;
| - situação atual da aprovação.
|
*/

try {

    $stmtProfessor =
        $pdo->prepare("

            SELECT

                u.id AS usuario_id,

                u.nome,

                u.email,

                u.ativo,

                u.bloqueado,

                u.tipo_acesso,

                pu.tipo_usuario,

                pp.aprovado

            FROM usuarios u

            INNER JOIN perfil_usuario pu
                ON pu.usuario_id = u.id

            INNER JOIN perfil_professor pp
                ON pp.usuario_id = u.id

            WHERE
                u.id = :usuario_id

            LIMIT 1

        ");


    $stmtProfessor->execute([

        ":usuario_id" =>
            $usuarioId

    ]);


    $professor =
        $stmtProfessor->fetch(
            PDO::FETCH_ASSOC
        );

}
catch (Throwable $erro) {

    error_log(
        "[AdminAprovarProfessor] " .
        "Erro ao consultar professor " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível verificar o cadastro do professor.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| PROFESSOR NÃO ENCONTRADO
|--------------------------------------------------------------------------
*/

if (!$professor) {

    $_SESSION["erro"] =
        "Perfil de professor não encontrado.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| PROTEGE CONTAS ADMINISTRATIVAS
|--------------------------------------------------------------------------
|
| Mesmo que uma conta ADM possua registros antigos
| em perfil_usuario ou perfil_professor,
| ela não pode ser aprovada por esta área.
|
*/

if (
    ($professor["tipo_acesso"] ?? "")
    === ROLE_ADMIN
) {

    $_SESSION["erro"] =
        "Contas administrativas não podem ser aprovadas como professor por esta área.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| GARANTE TIPO PROFESSOR
|--------------------------------------------------------------------------
*/

if (
    ($professor["tipo_usuario"] ?? "")
    !== "professor"
) {

    $_SESSION["erro"] =
        "O usuário informado não possui perfil de professor.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| EXIGE E-MAIL CONFIRMADO
|--------------------------------------------------------------------------
*/

if (
    (int) (
        $professor["ativo"]
        ?? 0
    ) !== 1
) {

    $_SESSION["erro"] =
        "O professor precisa confirmar o endereço de e-mail antes da aprovação.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| IMPEDE APROVAÇÃO DE CONTA BLOQUEADA
|--------------------------------------------------------------------------
*/

if (
    (int) (
        $professor["bloqueado"]
        ?? 0
    ) === 1
) {

    $_SESSION["erro"] =
        "Uma conta bloqueada não pode ser aprovada como professor.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VERIFICA SE JÁ ESTÁ APROVADO
|--------------------------------------------------------------------------
*/

if (
    (int) (
        $professor["aprovado"]
        ?? 0
    ) === 1
) {

    $_SESSION["sucesso"] =
        "Este professor já está aprovado.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| APROVA PROFESSOR
|--------------------------------------------------------------------------
|
| Não confiamos apenas nas validações feitas acima.
|
| O UPDATE repete as regras diretamente no banco:
|
| - perfil = professor;
| - e-mail confirmado;
| - conta não bloqueada;
| - conta não administrativa;
| - ainda não aprovado.
|
*/

try {

    $stmtAprovar =
        $pdo->prepare("

            UPDATE perfil_professor pp

            INNER JOIN perfil_usuario pu
                ON pu.usuario_id = pp.usuario_id

            INNER JOIN usuarios u
                ON u.id = pp.usuario_id

            SET

                pp.aprovado = 1,

                pp.data_aprovacao = NOW()

            WHERE
                pp.usuario_id = :usuario_id

            AND
                pp.aprovado = 0

            AND
                pu.tipo_usuario = 'professor'

            AND
                u.ativo = 1

            AND
                u.bloqueado = 0

            AND
                u.tipo_acesso <> :tipo_admin

        ");


    $stmtAprovar->execute([

        ":usuario_id" =>
            $usuarioId,

        ":tipo_admin" =>
            ROLE_ADMIN

    ]);


/*
|--------------------------------------------------------------------------
| VERIFICA ALTERAÇÃO
|--------------------------------------------------------------------------
*/

    if (
        $stmtAprovar->rowCount() !== 1
    ) {

        $_SESSION["erro"] =
            "Não foi possível aprovar o professor.";

        header(
            "Location: " .
            $linkProfessores
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | LOG ADMINISTRATIVO
    |--------------------------------------------------------------------------
    |
    | Registra somente:
    |
    | - ID do administrador;
    | - ID do professor;
    | - ação realizada.
    |
    | Nenhum token é colocado no log.
    |
    */

    error_log(
        "[AdminAprovarProfessor] " .
        "Administrador " .
        $adminId .
        " aprovou o professor " .
        $usuarioId .
        "."
    );


    /*
    |--------------------------------------------------------------------------
    | SUCESSO
    |--------------------------------------------------------------------------
    */

    $_SESSION["sucesso"] =
        "Professor aprovado com sucesso!";

}
catch (Throwable $erro) {

    error_log(
        "[AdminAprovarProfessor] " .
        "Erro ao aprovar professor " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível aprovar o professor.";

}


/*
|--------------------------------------------------------------------------
| RETORNA À LISTA
|--------------------------------------------------------------------------
*/

header(
    "Location: " .
    $linkProfessores
);

exit;