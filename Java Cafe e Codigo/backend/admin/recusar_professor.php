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
| Confirma diretamente no banco que a conta atualmente:
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
| O retorno sempre utiliza a URL administrativa
| com o identificador atual da sessão.
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
|
| Recebe o token enviado pelo formulário de professores.php.
|
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
| O valor recebido precisa corresponder exatamente
| ao identificador armazenado na sessão atual do administrador.
|
*/

if (
    !adminUrlValidar(
        $adminUrlTokenRecebido
    )
) {

    error_log(
        "[AdminRemoverAprovacaoProfessor] " .
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
|
| FILTER_VALIDATE_INT rejeita valores como:
|
| abc
| 1.5
| <script>
|
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
| BUSCA PROFESSOR
|--------------------------------------------------------------------------
|
| Não confiamos apenas no usuario_id recebido pelo formulário.
|
| Consultamos novamente:
|
| - tipo do usuário;
| - tipo de acesso;
| - existência do perfil de professor;
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
        "[AdminRemoverAprovacaoProfessor] " .
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
| Mesmo que uma conta administrativa possua registros antigos
| em perfil_usuario ou perfil_professor, este endpoint
| não poderá alterar suas permissões.
|
*/

if (
    ($professor["tipo_acesso"] ?? "")
    === ROLE_ADMIN
) {

    $_SESSION["erro"] =
        "Contas administrativas não podem ser alteradas por esta área.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| GARANTE QUE REALMENTE É PROFESSOR
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
| VERIFICA APROVAÇÃO ATUAL
|--------------------------------------------------------------------------
*/

if (
    (int) (
        $professor["aprovado"]
        ?? 0
    ) !== 1
) {

    $_SESSION["erro"] =
        "Este professor não possui uma aprovação ativa.";

    header(
        "Location: " .
        $linkProfessores
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| REMOVE APROVAÇÃO
|--------------------------------------------------------------------------
|
| A validação também é repetida no próprio UPDATE.
|
| O banco exige:
|
| - aprovação atualmente ativa;
| - perfil realmente professor;
| - conta não administrativa.
|
*/

try {

    $stmtRemover =
        $pdo->prepare("

            UPDATE perfil_professor pp

            INNER JOIN perfil_usuario pu
                ON pu.usuario_id = pp.usuario_id

            INNER JOIN usuarios u
                ON u.id = pp.usuario_id

            SET
                pp.aprovado = 0

            WHERE
                pp.usuario_id = :usuario_id

            AND
                pp.aprovado = 1

            AND
                pu.tipo_usuario = 'professor'

            AND
                u.tipo_acesso <> :tipo_admin

        ");


    $stmtRemover->execute([

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
        $stmtRemover->rowCount() !== 1
    ) {

        $_SESSION["erro"] =
            "Não foi possível remover a aprovação do professor.";

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
    | Registra:
    |
    | - administrador responsável;
    | - professor afetado;
    | - ação executada.
    |
    | Tokens e dados sensíveis não são registrados.
    |
    */

    error_log(
        "[AdminRemoverAprovacaoProfessor] " .
        "Administrador " .
        $adminId .
        " removeu a aprovação do professor " .
        $usuarioId .
        "."
    );


    /*
    |--------------------------------------------------------------------------
    | SUCESSO
    |--------------------------------------------------------------------------
    */

    $_SESSION["sucesso"] =
        "A aprovação do professor foi removida com sucesso.";

}
catch (Throwable $erro) {

    error_log(
        "[AdminRemoverAprovacaoProfessor] " .
        "Erro ao remover aprovação do professor " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível remover a aprovação do professor.";

}


/*
|--------------------------------------------------------------------------
| RETORNA À LISTA
|--------------------------------------------------------------------------
|
| O retorno fica:
|
| professores.php?acesso=TOKEN
|
*/

header(
    "Location: " .
    $linkProfessores
);

exit;