<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../backend/config/app.php";
require_once __DIR__ . "/../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../backend/models/PerfilUsuario.php";


/*
|--------------------------------------------------------------------------
| VERIFICA LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION["usuario"])) {

    header(
        "Location: " . BASE_URL .
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
    (int) ($_SESSION["usuario"]["id"] ?? 0);


/*
|--------------------------------------------------------------------------
| BUSCA PERFIL
|--------------------------------------------------------------------------
*/

$perfilModel =
    new PerfilUsuario();

$perfil =
    $perfilModel->buscarPorUsuario(
        $usuarioId
    );


/*
|--------------------------------------------------------------------------
| PERFIL NÃO CRIADO
|--------------------------------------------------------------------------
*/

if (!$perfil) {

    header(
        "Location: " . BASE_URL .
        "/frontend/pages/perfil/escolher.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| DADOS DO PERFIL
|--------------------------------------------------------------------------
*/

$tipoPerfil =
    $perfil["tipo_usuario"] ?? "";

$perfilCompleto =
    (int) ($perfil["perfil_completo"] ?? 0);


/*
|--------------------------------------------------------------------------
| PERFIL INCOMPLETO
|--------------------------------------------------------------------------
*/

if ($perfilCompleto !== 1) {

    switch ($tipoPerfil) {

        case "estudante":

            header(
                "Location: " . BASE_URL .
                "/frontend/pages/perfil/estudante/estudante_questionario.php"
            );

            exit;


        case "professor":

            header(
                "Location: " . BASE_URL .
                "/frontend/pages/perfil/professor/professor_questionario.php"
            );

            exit;


        case "aprendiz":

            header(
                "Location: " . BASE_URL .
                "/frontend/pages/perfil/aprendiz/aprendiz_questionario.php"
            );

            exit;


        default:

            header(
                "Location: " . BASE_URL .
                "/frontend/pages/perfil/escolher.php"
            );

            exit;

    }

}


/*
|--------------------------------------------------------------------------
| PERFIL COMPLETO
|--------------------------------------------------------------------------
*/

switch ($tipoPerfil) {

    case "estudante":

        header(
            "Location: " . BASE_URL .
            "/frontend/pages/perfil/estudante/estudante_painel.php"
        );

        exit;


    case "professor":

        header(
            "Location: " . BASE_URL .
            "/frontend/pages/perfil/professor/professor_painel.php"
        );

        exit;


    case "aprendiz":

        header(
            "Location: " . BASE_URL .
            "/frontend/pages/perfil/aprendiz/aprendiz_painel.php"
        );

        exit;


    default:

        header(
            "Location: " . BASE_URL .
            "/frontend/pages/perfil/escolher.php"
        );

        exit;

}