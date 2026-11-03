<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . "/../Controller/PerfilController.php";


/*
|--------------------------------------------------------------------------
| VERIFICA LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["usuario"])) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/login/login.php"
    );

    exit;
}


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

$tipoAcesso =
    trim(
        (string) (
            $usuario["tipo_acesso"]
            ?? ""
        )
    );


/*
|--------------------------------------------------------------------------
| VALIDA USUÁRIO
|--------------------------------------------------------------------------
*/

if ($usuarioId <= 0) {

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
| ADMINISTRADOR
|--------------------------------------------------------------------------
|
| O administrador não precisa possuir perfil de estudante,
| aprendiz ou professor.
|
| Por isso a verificação administrativa acontece antes
| da busca do perfil comum.
|
|--------------------------------------------------------------------------
*/

if (
    $tipoAcesso === ROLE_ADMIN
) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/admin/inicio.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| BUSCA PERFIL
|--------------------------------------------------------------------------
*/

$perfilController =
    new PerfilController();


$resultadoPerfil =
    $perfilController->buscarPerfil(
        $usuarioId
    );


/*
|--------------------------------------------------------------------------
| PERFIL NÃO CONFIGURADO
|--------------------------------------------------------------------------
*/

if (
    empty(
        $resultadoPerfil["status"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]["usuario"]
    )
) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/perfil/escolher.php"
    );

    exit;
}


$perfil =
    $resultadoPerfil["perfil"];

$perfilUsuario =
    $perfil["usuario"];


/*
|--------------------------------------------------------------------------
| TIPO DE PERFIL
|--------------------------------------------------------------------------
*/

$tipoUsuario =
    trim(
        (string) (
            $perfilUsuario["tipo_usuario"]
            ?? ""
        )
    );


/*
|--------------------------------------------------------------------------
| PERFIL COMPLETO
|--------------------------------------------------------------------------
*/

$perfilCompleto =
    (int) (
        $perfilUsuario["perfil_completo"]
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| ESTUDANTE
|--------------------------------------------------------------------------
*/

if (
    $tipoUsuario === "estudante"
) {

    /*
    |--------------------------------------------------------------------------
    | QUESTIONÁRIO AINDA NÃO CONCLUÍDO
    |--------------------------------------------------------------------------
    */

    if (
        !$perfilCompleto
        ||
        !isset(
            $perfil["estudante"]
        )
    ) {

        header(
            "Location: " .
            BASE_URL .
            "/frontend/pages/perfil/estudante/estudante_questionario.php"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | PAINEL
    |--------------------------------------------------------------------------
    */

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/perfil/estudante/estudante_painel.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| APRENDIZ
|--------------------------------------------------------------------------
*/

if (
    $tipoUsuario === "aprendiz"
) {

    /*
    |--------------------------------------------------------------------------
    | QUESTIONÁRIO AINDA NÃO CONCLUÍDO
    |--------------------------------------------------------------------------
    */

    if (
        !$perfilCompleto
        ||
        !isset(
            $perfil["aprendiz"]
        )
    ) {

        header(
            "Location: " .
            BASE_URL .
            "/frontend/pages/perfil/aprendiz/aprendiz_questionario.php"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | PAINEL
    |--------------------------------------------------------------------------
    */

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/perfil/aprendiz/aprendiz_painel.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| PROFESSOR
|--------------------------------------------------------------------------
*/

if (
    $tipoUsuario === "professor"
) {

    /*
    |--------------------------------------------------------------------------
    | QUESTIONÁRIO AINDA NÃO CONCLUÍDO
    |--------------------------------------------------------------------------
    */

    if (
        !$perfilCompleto
        ||
        !isset(
            $perfil["professor"]
        )
    ) {

        header(
            "Location: " .
            BASE_URL .
            "/frontend/pages/perfil/professor/professor_questionario.php"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | PAINEL
    |--------------------------------------------------------------------------
    |
    | O professor pode entrar normalmente mesmo sem aprovação.
    |
    | A aprovação administrativa controla apenas
    | as funções relacionadas às Salas de Aula.
    |
    |--------------------------------------------------------------------------
    */

    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/perfil/professor/professor_painel.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| PERFIL DESCONHECIDO
|--------------------------------------------------------------------------
*/

$_SESSION["erro"] =
    "Não foi possível identificar seu perfil.";


header(
    "Location: " .
    BASE_URL .
    "/frontend/pages/perfil/escolher.php"
);

exit;