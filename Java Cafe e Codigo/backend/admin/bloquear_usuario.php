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
| Confirma diretamente no banco que:
|
| - existe usuário autenticado;
| - conta está ativa;
| - conta não está bloqueada;
| - tipo_acesso continua sendo admin.
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
| DESTINO ADMINISTRATIVO
|--------------------------------------------------------------------------
|
| Nunca montamos manualmente:
|
| /admin/usuarios.php
|
| O retorno sempre recebe o identificador
| administrativo atual da sessão.
|
*/

$linkUsuarios =
    adminUrl(
        "usuarios.php"
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
        $linkUsuarios
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| IDENTIFICADOR ADMINISTRATIVO RECEBIDO
|--------------------------------------------------------------------------
|
| Este token veio do formulário existente em usuarios.php.
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

    $adminUrlTokenRecebido = "";

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
| Comparamos o token recebido pelo POST com o token
| armazenado na sessão do administrador.
|
| Não confiamos simplesmente no valor enviado pelo navegador.
|
*/

if (
    !adminUrlValidar(
        $adminUrlTokenRecebido
    )
) {

    error_log(
        "[AdminUsuario] Tentativa com token administrativo inválido. Admin ID: " .
        $adminId
    );


    $_SESSION["erro"] =
        "Requisição administrativa inválida.";


    header(
        "Location: " .
        adminUrl("inicio.php")
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| RECEBE USUÁRIO
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
| RECEBE AÇÃO
|--------------------------------------------------------------------------
*/

$acao =
    $_POST["acao"]
    ?? "";


if (
    !is_string(
        $acao
    )
) {

    $acao = "";

}


$acao =
    strtolower(
        trim(
            $acao
        )
    );


/*
|--------------------------------------------------------------------------
| VALIDA USUÁRIO
|--------------------------------------------------------------------------
*/

if (
    $usuarioId <= 0
) {

    $_SESSION["erro"] =
        "Usuário inválido.";


    header(
        "Location: " .
        $linkUsuarios
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDA AÇÃO
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $acao,
        [
            "bloquear",
            "desbloquear"
        ],
        true
    )
) {

    $_SESSION["erro"] =
        "Ação administrativa inválida.";


    header(
        "Location: " .
        $linkUsuarios
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| IMPEDE ALTERAÇÃO DA PRÓPRIA CONTA
|--------------------------------------------------------------------------
|
| Mesmo que o administrador altere manualmente
| o usuario_id no navegador, o backend recusa.
|
*/

if (
    $usuarioId === $adminId
) {

    $_SESSION["erro"] =
        "Você não pode alterar o status da sua própria conta.";


    header(
        "Location: " .
        $linkUsuarios
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| BUSCA USUÁRIO ALVO
|--------------------------------------------------------------------------
|
| Nunca confiamos apenas nas informações enviadas
| pelo formulário.
|
| A situação atual da conta é consultada novamente
| diretamente no banco.
|
*/

try {

    $stmtUsuario =
        $pdo->prepare("

            SELECT

                id,

                nome,

                email,

                ativo,

                bloqueado,

                tipo_acesso

            FROM usuarios

            WHERE
                id = :usuario_id

            LIMIT 1

        ");


    $stmtUsuario->execute([

        ":usuario_id" =>
            $usuarioId

    ]);


    $usuarioAlvo =
        $stmtUsuario->fetch(
            PDO::FETCH_ASSOC
        );

}
catch (Throwable $erro) {

    error_log(
        "[AdminUsuario] Erro ao buscar usuário " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível localizar o usuário.";


    header(
        "Location: " .
        $linkUsuarios
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO NÃO ENCONTRADO
|--------------------------------------------------------------------------
*/

if (!$usuarioAlvo) {

    $_SESSION["erro"] =
        "Usuário não encontrado.";


    header(
        "Location: " .
        $linkUsuarios
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| PROTEGE CONTAS ADMINISTRATIVAS
|--------------------------------------------------------------------------
|
| Nenhum administrador pode bloquear outro administrador
| através desta área.
|
*/

if (
    ($usuarioAlvo["tipo_acesso"] ?? "")
    === ROLE_ADMIN
) {

    $_SESSION["erro"] =
        "Contas administrativas não podem ser bloqueadas por esta área.";


    header(
        "Location: " .
        $linkUsuarios
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| STATUS ATUAL
|--------------------------------------------------------------------------
*/

$bloqueadoAtual =
    (int) (
        $usuarioAlvo["bloqueado"]
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| DEFINE NOVO STATUS
|--------------------------------------------------------------------------
*/

$novoBloqueio =
    $acao === "bloquear"
        ? 1
        : 0;


/*
|--------------------------------------------------------------------------
| EVITA ALTERAÇÃO DESNECESSÁRIA
|--------------------------------------------------------------------------
*/

if (
    $bloqueadoAtual === $novoBloqueio
) {

    $_SESSION["sucesso"] =
        $novoBloqueio === 1
            ? "Este usuário já está bloqueado."
            : "Este usuário já está desbloqueado.";


    header(
        "Location: " .
        $linkUsuarios
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| ALTERA BLOQUEIO
|--------------------------------------------------------------------------
|
| A regra de proteção contra contas administrativas
| também é aplicada dentro do próprio UPDATE.
|
*/

try {

    $stmtAtualizar =
        $pdo->prepare("

            UPDATE usuarios

            SET
                bloqueado = :bloqueado

            WHERE
                id = :usuario_id

            AND
                tipo_acesso <> :tipo_admin

        ");


    $stmtAtualizar->execute([

        ":bloqueado" =>
            $novoBloqueio,

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
        $stmtAtualizar->rowCount() !== 1
    ) {

        $_SESSION["erro"] =
            "Não foi possível alterar o bloqueio do usuário.";


        header(
            "Location: " .
            $linkUsuarios
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | LOG ADMINISTRATIVO
    |--------------------------------------------------------------------------
    |
    | Registramos:
    |
    | - administrador responsável;
    | - usuário afetado;
    | - ação executada.
    |
    | Não são registrados senha, token ou outros
    | dados sensíveis.
    |
    */

    error_log(
        "[AdminUsuario] Admin " .
        $adminId .
        " " .
        (
            $novoBloqueio === 1
                ? "bloqueou"
                : "desbloqueou"
        ) .
        " o usuário " .
        $usuarioId .
        "."
    );


    /*
    |--------------------------------------------------------------------------
    | SUCESSO
    |--------------------------------------------------------------------------
    */

    $_SESSION["sucesso"] =
        $novoBloqueio === 1
            ? "Usuário bloqueado com sucesso!"
            : "Usuário desbloqueado com sucesso!";

}
catch (Throwable $erro) {

    error_log(
        "[AdminUsuario] Erro ao alterar bloqueio do usuário " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível alterar o bloqueio do usuário.";

}


/*
|--------------------------------------------------------------------------
| RETORNA AO GERENCIAMENTO
|--------------------------------------------------------------------------
|
| O retorno preserva automaticamente:
|
| usuarios.php?acesso=TOKEN
|
*/

header(
    "Location: " .
    $linkUsuarios
);

exit;