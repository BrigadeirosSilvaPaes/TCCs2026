<?php

/*
|--------------------------------------------------------------------------
| ÁREA DO PROFESSOR - ENTRADA
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Esta página funciona como porta de entrada do perfil Professor.
|
| Ela não possui interface própria.
|
| Fluxo:
|
| - valida a sessão;
| - identifica o usuário;
| - confirma que o perfil selecionado é Professor;
| - verifica se o cadastro profissional existe;
| - encaminha para o questionário ou para o painel.
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
| GARANTE SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| USUÁRIO DA SESSÃO
|--------------------------------------------------------------------------
*/

$usuario =
    $_SESSION["usuario"]
    ?? null;


if (
    !is_array(
        $usuario
    )
) {

    unset(
        $_SESSION["usuario"]
    );


    header(
        "Location: " .
        (
            rotaExiste("login")
                ?
                rota("login")
                :
                rtrim(BASE_URL, "/") .
                "/frontend/pages/login/login.php"
        )
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| ID DO USUÁRIO
|--------------------------------------------------------------------------
*/

$usuarioId =
    filter_var(
        $usuario["id"]
        ?? null,
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


    header(
        "Location: " .
        (
            rotaExiste("login")
                ?
                rota("login")
                :
                rtrim(BASE_URL, "/") .
                "/frontend/pages/login/login.php"
        )
    );


    exit;

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
        "[Professor] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    header(
        "Location: " .
        (
            rotaExiste("painel")
                ?
                rota("painel")
                :
                rtrim(BASE_URL, "/") .
                "/frontend/pages/perfil/inicio.php"
        )
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDA RETORNO
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
        $resultadoPerfil["perfil"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]
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
        "Não foi possível carregar seu perfil.";


    header(
        "Location: " .
        (
            rotaExiste("perfil.escolher")
                ?
                rota("perfil.escolher")
                :
                rtrim(BASE_URL, "/") .
                "/frontend/pages/perfil/escolher.php"
        )
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| PERFIL GERAL
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


$roleProfessor =
    defined(
        "ROLE_PROFESSOR"
    )
        ?
        strtolower(
            trim(
                (string) ROLE_PROFESSOR
            )
        )
        :
        "professor";


/*
|--------------------------------------------------------------------------
| GARANTE PERFIL PROFESSOR
|--------------------------------------------------------------------------
*/

if (
    $tipoUsuario !==
    $roleProfessor
) {

    $_SESSION["erro"] =
        "Esta área é exclusiva para o perfil Professor.";


    header(
        "Location: " .
        (
            rotaExiste("painel")
                ?
                rota("painel")
                :
                rtrim(BASE_URL, "/") .
                "/frontend/pages/perfil/inicio.php"
        )
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| PERFIL PROFISSIONAL
|--------------------------------------------------------------------------
*/

$perfilProfessor =
    $resultadoPerfil["perfil"]["professor"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| AINDA NÃO COMPLETOU O CADASTRO PROFISSIONAL
|--------------------------------------------------------------------------
|
| O formulário de instituição e área de atuação pertence exclusivamente
| ao questionário do Professor.
|
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $perfilProfessor
    )
    ||
    empty(
        $perfilProfessor
    )
) {

    header(
        "Location: " .
        (
            rotaExiste("professor.questionario")
                ?
                rota("professor.questionario")
                :
                rtrim(BASE_URL, "/") .
                "/frontend/pages/perfil/professor/professor_questionario.php"
        )
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| PERFIL JÁ CRIADO
|--------------------------------------------------------------------------
|
| Estar aguardando aprovação NÃO impede o acesso ao painel.
|
| O painel mostrará a situação do cadastro.
|
| Somente recursos específicos, como gerenciamento de Salas de Aula,
| exigirão aprovação.
|
|--------------------------------------------------------------------------
*/

header(
    "Location: " .
    (
        rotaExiste("professor.painel")
            ?
            rota("professor.painel")
            :
            rtrim(BASE_URL, "/") .
            "/frontend/pages/perfil/professor/professor_painel.php"
    ),
    true,
    302
);


exit;