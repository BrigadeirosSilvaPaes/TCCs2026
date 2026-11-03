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
| GARANTE SESSÃO
|--------------------------------------------------------------------------
*/

if (
    session_status()
    !== PHP_SESSION_ACTIVE
) {

    session_start();

}


/*
|--------------------------------------------------------------------------
| VERIFICA SE EXISTE USUÁRIO NA SESSÃO
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["usuario"]
    )
) {

    $_SESSION["erro"] =
        "Você precisa estar logado para acessar esta página.";

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/login/login.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| ID DO USUÁRIO
|--------------------------------------------------------------------------
*/

$usuarioId =
    (int) (
        $_SESSION["usuario"]["id"]
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| SESSÃO INVÁLIDA
|--------------------------------------------------------------------------
*/

if ($usuarioId <= 0) {

    unset(
        $_SESSION["usuario"]
    );

    $_SESSION["erro"] =
        "Sua sessão é inválida. Faça login novamente.";

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/login/login.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| CONSULTA ESTADO ATUAL DA CONTA
|--------------------------------------------------------------------------
|
| A sessão não é considerada a única fonte de verdade.
|
| A cada acesso protegido, o sistema consulta o banco
| para verificar se a conta:
|
| - ainda existe;
| - confirmou o e-mail;
| - não foi bloqueada;
| - mantém o mesmo tipo de acesso.
|
|--------------------------------------------------------------------------
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


    $usuarioBanco =
        $stmtUsuario->fetch(
            PDO::FETCH_ASSOC
        );

}
catch (Throwable $erro) {

    error_log(
        "[MiddlewareLogin] Erro ao verificar usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível verificar sua sessão.";

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/login/login.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| USUÁRIO NÃO EXISTE MAIS
|--------------------------------------------------------------------------
*/

if (!$usuarioBanco) {

    unset(
        $_SESSION["usuario"]
    );

    $_SESSION["erro"] =
        "Sua conta não foi encontrada.";

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/login/login.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| VERIFICA CONFIRMAÇÃO DO E-MAIL
|--------------------------------------------------------------------------
|
| ativo = 0
| e-mail ainda não confirmado
|
| ativo = 1
| e-mail confirmado
|
|--------------------------------------------------------------------------
*/

if (
    (int) (
        $usuarioBanco["ativo"]
        ?? 0
    ) !== 1
) {

    unset(
        $_SESSION["usuario"]
    );


    $_SESSION["erro"] =
        "Confirme seu email antes de acessar a plataforma.";


    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/login/login.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| VERIFICA BLOQUEIO ADMINISTRATIVO
|--------------------------------------------------------------------------
|
| bloqueado = 0
| acesso liberado
|
| bloqueado = 1
| acesso suspenso pelo administrador
|
|--------------------------------------------------------------------------
*/

if (
    (int) (
        $usuarioBanco["bloqueado"]
        ?? 0
    ) === 1
) {

    /*
    |--------------------------------------------------------------------------
    | REMOVE DADOS DE AUTENTICAÇÃO
    |--------------------------------------------------------------------------
    */

    unset(
        $_SESSION["usuario"],
        $_SESSION["id"],
        $_SESSION["nome"],
        $_SESSION["email"],
        $_SESSION["tipo_acesso"]
    );


    $_SESSION["erro"] =
        "Sua conta está bloqueada administrativamente.";


    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/login/login.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| ATUALIZA DADOS DA SESSÃO
|--------------------------------------------------------------------------
|
| Caso nome, e-mail ou tipo de acesso sejam alterados
| no banco, a sessão passa a refletir os dados atuais.
|
|--------------------------------------------------------------------------
*/

$_SESSION["usuario"] = [

    "id" =>
        (int) $usuarioBanco["id"],

    "nome" =>
        $usuarioBanco["nome"],

    "email" =>
        $usuarioBanco["email"],

    "tipo_acesso" =>
        $usuarioBanco["tipo_acesso"]

];


/*
|--------------------------------------------------------------------------
| COMPATIBILIDADE COM CÓDIGO ANTIGO
|--------------------------------------------------------------------------
|
| Algumas partes antigas da plataforma ainda podem
| utilizar $_SESSION["id"] e $_SESSION["nome"].
|
| Mantemos esses valores enquanto o projeto
| é totalmente padronizado.
|
|--------------------------------------------------------------------------
*/

$_SESSION["id"] =
    (int) $usuarioBanco["id"];

$_SESSION["nome"] =
    $usuarioBanco["nome"];

$_SESSION["email"] =
    $usuarioBanco["email"];

$_SESSION["tipo_acesso"] =
    $usuarioBanco["tipo_acesso"];