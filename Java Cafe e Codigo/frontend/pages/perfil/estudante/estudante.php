<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../../backend/config/app.php";
require_once __DIR__ . "/../../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../../backend/config/helpers.php";

require_once __DIR__ . "/../../../../backend/models/PerfilEstudante.php";


/*
|--------------------------------------------------------------------------
| GARANTE SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| ROTAS
|--------------------------------------------------------------------------
*/

$linkLogin =
    rota(
        "login"
    );


$linkQuestionario =
    rota(
        "estudante.questionario"
    );


$linkPainel =
    rota(
        "estudante.painel"
    );


/*
|--------------------------------------------------------------------------
| VERIFICA LOGIN
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["usuario"]
    )
) {

    header(
        "Location: " .
        $linkLogin
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO LOGADO
|--------------------------------------------------------------------------
*/

$usuarioId =
    (int) (
        $_SESSION["usuario"]["id"]
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| VALIDA USUÁRIO
|--------------------------------------------------------------------------
*/

if (
    $usuarioId <= 0
) {

    /*
    |--------------------------------------------------------------------------
    | REMOVE SESSÃO INVÁLIDA
    |--------------------------------------------------------------------------
    */

    unset(
        $_SESSION["usuario"]
    );


    header(
        "Location: " .
        $linkLogin
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| PERFIL DE ESTUDANTE
|--------------------------------------------------------------------------
*/

try {

    $perfilEstudante =
        new PerfilEstudante();


    $possuiPerfil =
        $perfilEstudante->existePerfil(
            $usuarioId
        );

}
catch (Throwable $erro) {

    /*
    |--------------------------------------------------------------------------
    | LOG
    |--------------------------------------------------------------------------
    */

    error_log(
        "[EntradaEstudante] Erro ao verificar perfil do usuário " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    /*
    |--------------------------------------------------------------------------
    | ERRO
    |--------------------------------------------------------------------------
    */

    $_SESSION["erro"] =
        "Não foi possível verificar seu perfil de estudante.";


    /*
    |--------------------------------------------------------------------------
    | VOLTA AO LOGIN
    |--------------------------------------------------------------------------
    |
    | Evitamos continuar o fluxo sem saber se o perfil existe.
    |
    */

    header(
        "Location: " .
        $linkLogin
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| QUESTIONÁRIO NÃO RESPONDIDO
|--------------------------------------------------------------------------
|
| Se ainda não existir um registro específico em PerfilEstudante:
|
| /estudante
|
|         ↓
|
| /estudante/completar-perfil
|
*/

if (
    !$possuiPerfil
) {

    header(
        "Location: " .
        $linkQuestionario
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| PERFIL DE ESTUDANTE JÁ EXISTE
|--------------------------------------------------------------------------
|
| Se o questionário já foi respondido:
|
| /estudante
|
|         ↓
|
| /estudante/painel
|
*/

header(
    "Location: " .
    $linkPainel
);

exit;