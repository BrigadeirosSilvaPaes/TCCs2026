<?php

/*
|--------------------------------------------------------------------------
| ENTRADA DO PERFIL APRENDIZ
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Esta página funciona como a porta de entrada do perfil Aprendiz.
|
| Ela identifica automaticamente se o usuário:
|
| - ainda precisa completar o perfil;
| - ou já possui o perfil Aprendiz configurado.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../../backend/config/app.php";
require_once __DIR__ . "/../../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../../backend/config/helpers.php";

require_once __DIR__ . "/../../../../backend/Controller/PerfilController.php";


/*
|--------------------------------------------------------------------------
| SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| REDIRECIONAMENTO
|--------------------------------------------------------------------------
*/

function aprendizEntradaRedirecionar(
    string $nomeRota,
    int $codigo = 302
): never {

    header(
        "Location: " .
        rota(
            $nomeRota
        ),
        true,
        $codigo
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

$usuario =
    $_SESSION["usuario"]
    ??
    null;


if (
    !is_array(
        $usuario
    )
) {

    unset(
        $_SESSION["usuario"]
    );


    aprendizEntradaRedirecionar(
        "login"
    );

}


/*
|--------------------------------------------------------------------------
| ID DO USUÁRIO
|--------------------------------------------------------------------------
*/

$usuarioId =
    filter_var(
        $usuario["id"]
        ??
        null,
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 1
            ]
        ]
    );


if (
    $usuarioId === false
    ||
    $usuarioId === null
) {

    unset(
        $_SESSION["usuario"]
    );


    aprendizEntradaRedirecionar(
        "login"
    );

}


$usuarioId =
    (int) $usuarioId;


/*
|--------------------------------------------------------------------------
| CARREGA PERFIL
|--------------------------------------------------------------------------
*/

try {

    $perfilController =
        new PerfilController();


    $resultadoPerfil =
        $perfilController->buscarPerfil(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[AprendizEntrada] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    aprendizEntradaRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA RESULTADO
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $resultadoPerfil
    )
    ||
    empty(
        $resultadoPerfil["status"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]["usuario"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]["usuario"]
    )
) {

    $_SESSION["erro"] =
        "Complete a configuração do seu perfil.";


    aprendizEntradaRedirecionar(
        "perfil.escolher"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL PRINCIPAL
|--------------------------------------------------------------------------
*/

$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


/*
|--------------------------------------------------------------------------
| TIPO DE PERFIL
|--------------------------------------------------------------------------
*/

$tipoUsuario =
    strtolower(
        trim(
            (string) (
                $perfilUsuario["tipo_usuario"]
                ??
                ""
            )
        )
    );


$tipoAprendiz =
    defined(
        "ROLE_APRENDIZ"
    )
        ?
        strtolower(
            trim(
                (string) ROLE_APRENDIZ
            )
        )
        :
        "aprendiz";


/*
|--------------------------------------------------------------------------
| GARANTE PERFIL APRENDIZ
|--------------------------------------------------------------------------
*/

if (
    $tipoUsuario !==
    $tipoAprendiz
) {

    $_SESSION["erro"] =
        "Esta área é exclusiva para o perfil Aprendiz.";


    aprendizEntradaRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL ESPECÍFICO
|--------------------------------------------------------------------------
*/

$perfilAprendiz =
    $resultadoPerfil["perfil"]["aprendiz"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| AINDA NÃO COMPLETOU O PERFIL
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $perfilAprendiz
    )
    ||
    empty(
        $perfilAprendiz
    )
) {

    aprendizEntradaRedirecionar(
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL JÁ CONFIGURADO
|--------------------------------------------------------------------------
*/

aprendizEntradaRedirecionar(
    "aprendiz.painel"
);