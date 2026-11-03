<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| SALVA O PERFIL GERAL DO USUÁRIO
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . "/../config/helpers.php";
require_once __DIR__ . "/../Controller/PerfilController.php";


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


$linkEscolherPerfil =
    rota(
        "perfil.escolher"
    );


/*
|--------------------------------------------------------------------------
| VERIFICA LOGIN
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

    mensagem(
        "aviso",
        "login_necessario"
    );


    header(
        "Location: "
        .
        $linkLogin,
        true,
        303
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
        $usuario["id"]
        ??
        0
    );


if (
    $usuarioId <=
    0
) {

    unset(
        $_SESSION["usuario"]
    );


    mensagem(
        "aviso",
        "login_necessario"
    );


    header(
        "Location: "
        .
        $linkLogin,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| ADMINISTRADOR NÃO POSSUI PERFIL PEDAGÓGICO
|--------------------------------------------------------------------------
|
| O administrador pertence ao nível de acesso da conta.
|
| Ele não deve possuir:
|
| estudante
| professor
| aprendiz
|
| como perfil pedagógico através deste endpoint.
|
|--------------------------------------------------------------------------
*/

$tipoAcesso =
    is_string(
        $usuario["tipo_acesso"]
        ??
        null
    )
        ?
        trim(
            $usuario["tipo_acesso"]
        )
        :
        ACCESS_USUARIO;


if (
    $tipoAcesso ===
    ACCESS_ADMIN
) {

    mensagem(
        "erro",
        "sem_permissao"
    );


    header(
        "Location: "
        .
        rota(
            "painel"
        ),
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| ACEITA SOMENTE POST
|--------------------------------------------------------------------------
*/

$metodo =
    strtoupper(
        (string) (
            $_SERVER["REQUEST_METHOD"]
            ??
            ""
        )
    );


if (
    $metodo !==
    "POST"
) {

    header(
        "Allow: POST"
    );


    http_response_code(
        405
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| PROTEÇÃO CONTRA REQUISIÇÃO CROSS-SITE
|--------------------------------------------------------------------------
|
| O CSRF formal será adicionado quando o formulário escolher.php
| for auditado junto com este endpoint.
|
|--------------------------------------------------------------------------
*/

$fetchSite =
    strtolower(
        trim(
            (string) (
                $_SERVER["HTTP_SEC_FETCH_SITE"]
                ??
                ""
            )
        )
    );


if (
    $fetchSite ===
    "cross-site"
) {

    http_response_code(
        403
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| LIMITA TAMANHO DA REQUISIÇÃO
|--------------------------------------------------------------------------
*/

$contentLength =
    (int) (
        $_SERVER["CONTENT_LENGTH"]
        ??
        0
    );


if (
    $contentLength >
    32768
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkEscolherPerfil,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| RECEBE DADOS
|--------------------------------------------------------------------------
*/

$tipoUsuarioRecebido =
    $_POST["tipo_usuario"]
    ??
    null;


$estadoRecebido =
    $_POST["estado"]
    ??
    null;


$cidadeRecebida =
    $_POST["cidade"]
    ??
    null;


$objetivoRecebido =
    $_POST["objetivo"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| VALIDA TIPOS
|--------------------------------------------------------------------------
|
| Impede entradas malformadas como:
|
| tipo_usuario[]=estudante
|
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $tipoUsuarioRecebido
    )
    ||
    !is_string(
        $estadoRecebido
    )
    ||
    (
        $cidadeRecebida !==
        null
        &&
        !is_string(
            $cidadeRecebida
        )
    )
    ||
    (
        $objetivoRecebido !==
        null
        &&
        !is_string(
            $objetivoRecebido
        )
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkEscolherPerfil,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| NORMALIZA
|--------------------------------------------------------------------------
*/

$tipoUsuario =
    strtolower(
        trim(
            $tipoUsuarioRecebido
        )
    );


$estado =
    strtoupper(
        trim(
            $estadoRecebido
        )
    );


$cidade =
    trim(
        $cidadeRecebida
        ??
        ""
    );


$objetivo =
    trim(
        $objetivoRecebido
        ??
        ""
    );


/*
|--------------------------------------------------------------------------
| VALIDA UTF-8
|--------------------------------------------------------------------------
*/

if (
    !mb_check_encoding(
        $tipoUsuario,
        "UTF-8"
    )
    ||
    !mb_check_encoding(
        $estado,
        "UTF-8"
    )
    ||
    !mb_check_encoding(
        $cidade,
        "UTF-8"
    )
    ||
    !mb_check_encoding(
        $objetivo,
        "UTF-8"
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkEscolherPerfil,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| TIPOS DE PERFIL PERMITIDOS
|--------------------------------------------------------------------------
*/

$tiposPermitidos = [
    PROFILE_ESTUDANTE,
    PROFILE_PROFESSOR,
    PROFILE_APRENDIZ
];


/*
|--------------------------------------------------------------------------
| VALIDA TIPO DE PERFIL
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $tipoUsuario,
        $tiposPermitidos,
        true
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkEscolherPerfil,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| ESTADOS BRASILEIROS PERMITIDOS
|--------------------------------------------------------------------------
*/

$estadosPermitidos = [

    "AC",
    "AL",
    "AP",
    "AM",
    "BA",
    "CE",
    "DF",
    "ES",
    "GO",
    "MA",
    "MT",
    "MS",
    "MG",
    "PA",
    "PB",
    "PR",
    "PE",
    "PI",
    "RJ",
    "RN",
    "RS",
    "RO",
    "RR",
    "SC",
    "SP",
    "SE",
    "TO"

];


/*
|--------------------------------------------------------------------------
| VALIDA ESTADO
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $estado,
        $estadosPermitidos,
        true
    )
) {

    mensagem(
        "erro",
        "campos_vazios"
    );


    header(
        "Location: "
        .
        $linkEscolherPerfil,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| CIDADE
|--------------------------------------------------------------------------
|
| O fluxo atual da plataforma solicita cidade detalhada apenas
| quando o estado selecionado é Rio Grande do Sul.
|
| Preservamos esse comportamento.
|
|--------------------------------------------------------------------------
*/

if (
    $estado ===
    "RS"
) {

    if (
        $cidade ===
        ""
    ) {

        mensagem(
            "erro",
            "campos_vazios"
        );


        header(
            "Location: "
            .
            $linkEscolherPerfil,
            true,
            303
        );


        exit;

    }


    if (
        mb_strlen(
            $cidade,
            "UTF-8"
        )
        >
        100
    ) {

        mensagem(
            "erro",
            "generico"
        );


        header(
            "Location: "
            .
            $linkEscolherPerfil,
            true,
            303
        );


        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZA ESPAÇOS
    |--------------------------------------------------------------------------
    */

    $cidadeNormalizada =
        preg_replace(
            "/\s+/u",
            " ",
            $cidade
        );


    if (
        !is_string(
            $cidadeNormalizada
        )
    ) {

        mensagem(
            "erro",
            "generico"
        );


        header(
            "Location: "
            .
            $linkEscolherPerfil,
            true,
            303
        );


        exit;

    }


    $cidade =
        trim(
            $cidadeNormalizada
        );

}
else {

    $cidade =
        null;

}


/*
|--------------------------------------------------------------------------
| OBJETIVO
|--------------------------------------------------------------------------
|
| Campo opcional.
|
|--------------------------------------------------------------------------
*/

if (
    mb_strlen(
        $objetivo,
        "UTF-8"
    )
    >
    500
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkEscolherPerfil,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| REJEITA CARACTERES DE CONTROLE
|--------------------------------------------------------------------------
*/

if (
    (
        is_string(
            $cidade
        )
        &&
        preg_match(
            "/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u",
            $cidade
        )
        === 1
    )
    ||
    preg_match(
        "/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u",
        $objetivo
    )
    === 1
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkEscolherPerfil,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| VALOR OPCIONAL DO OBJETIVO
|--------------------------------------------------------------------------
*/

$objetivoBanco =
    $objetivo !==
    ""
        ?
        $objetivo
        :
        null;


/*
|--------------------------------------------------------------------------
| CONTROLLER
|--------------------------------------------------------------------------
*/

try {

    $perfilController =
        new PerfilController();


    $resultado =
        $perfilController->salvarPerfilUsuario(
            $usuarioId,
            $tipoUsuario,
            $estado,
            $cidade,
            $objetivoBanco
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[Perfil] Erro ao salvar perfil geral do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    mensagem(
        "erro",
        "erro_banco"
    );


    header(
        "Location: "
        .
        $linkEscolherPerfil,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDA RESPOSTA
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $resultado
    )
) {

    error_log(
        "[Perfil] PerfilController retornou resposta inválida para usuário "
        .
        $usuarioId
        .
        "."
    );


    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkEscolherPerfil,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| FALHA AO SALVAR
|--------------------------------------------------------------------------
*/

if (
    ($resultado["status"] ?? false)
    !== true
) {

    $mensagemController =
        is_string(
            $resultado["mensagem"]
            ??
            null
        )
            ?
            $resultado["mensagem"]
            :
            "";


    if (
        $mensagemController !==
        ""
    ) {

        error_log(
            "[Perfil] Não foi possível salvar perfil geral do usuário "
            .
            $usuarioId
            .
            ": "
            .
            $mensagemController
        );

    }


    mensagem(
        "erro",
        "erro_banco"
    );


    header(
        "Location: "
        .
        $linkEscolherPerfil,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| REDIRECIONA PARA QUESTIONÁRIO ESPECÍFICO
|--------------------------------------------------------------------------
|
| O perfil específico somente será criado quando o usuário
| preencher seu respectivo questionário.
|
|--------------------------------------------------------------------------
*/

switch (
    $tipoUsuario
) {

    case PROFILE_ESTUDANTE:

        header(
            "Location: "
            .
            rota(
                "estudante.questionario"
            ),
            true,
            303
        );


        exit;


    case PROFILE_PROFESSOR:

        header(
            "Location: "
            .
            rota(
                "professor.questionario"
            ),
            true,
            303
        );


        exit;


    case PROFILE_APRENDIZ:

        header(
            "Location: "
            .
            rota(
                "aprendiz.questionario"
            ),
            true,
            303
        );


        exit;

}


/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/

mensagem(
    "erro",
    "generico"
);


header(
    "Location: "
    .
    $linkEscolherPerfil,
    true,
    303
);


exit;