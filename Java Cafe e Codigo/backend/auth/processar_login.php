<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| PROCESSAMENTO DO LOGIN
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

require_once __DIR__ . "/../Controller/AuthController.php";
require_once __DIR__ . "/../models/PerfilUsuario.php";

require_once __DIR__ . "/../security/admin_session.php";
require_once __DIR__ . "/../security/admin_url.php";


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
|
| O login possui apenas e-mail e senha.
|
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
    16384
) {

    mensagem(
        "erro",
        "generico"
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
| RECEBE DADOS
|--------------------------------------------------------------------------
*/

$emailRecebido =
    $_POST["email"]
    ??
    null;


$senhaRecebida =
    $_POST["senha"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| VALIDA TIPOS
|--------------------------------------------------------------------------
|
| Evita conversões inesperadas como:
|
| email[]=...
|
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $emailRecebido
    )
    ||
    !is_string(
        $senhaRecebida
    )
) {

    mensagem(
        "erro",
        "campos_vazios"
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
| NORMALIZA
|--------------------------------------------------------------------------
*/

$email =
    trim(
        $emailRecebido
    );


$senha =
    $senhaRecebida;


/*
|--------------------------------------------------------------------------
| CAMPOS OBRIGATÓRIOS
|--------------------------------------------------------------------------
*/

if (
    $email ===
    ""
    ||
    $senha ===
    ""
) {

    mensagem(
        "erro",
        "campos_vazios"
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
| UTF-8
|--------------------------------------------------------------------------
*/

if (
    !mb_check_encoding(
        $email,
        "UTF-8"
    )
    ||
    !mb_check_encoding(
        $senha,
        "UTF-8"
    )
) {

    mensagem(
        "erro",
        "login_invalido"
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
| NORMALIZA E-MAIL
|--------------------------------------------------------------------------
*/

$email =
    mb_strtolower(
        $email,
        "UTF-8"
    );


/*
|--------------------------------------------------------------------------
| VALIDA E-MAIL
|--------------------------------------------------------------------------
*/

if (
    mb_strlen(
        $email,
        "UTF-8"
    )
    >
    150
    ||
    filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )
    === false
) {

    mensagem(
        "erro",
        "email_invalido"
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
| VALIDA SENHA RECEBIDA
|--------------------------------------------------------------------------
|
| Não aplicamos aqui o mínimo de 8 caracteres.
|
| Motivo:
|
| contas antigas da plataforma podem ter sido criadas quando
| o mínimo ainda era 6 caracteres.
|
| O login deve validar a credencial existente, não redefinir
| a política utilizada no momento da criação da conta.
|
|--------------------------------------------------------------------------
*/

if (
    str_contains(
        $senha,
        "\0"
    )
) {

    mensagem(
        "erro",
        "login_invalido"
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


if (
    strlen(
        $senha
    )
    >
    MAX_PASSWORD_LENGTH
) {

    mensagem(
        "erro",
        "login_invalido"
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
| AUTENTICA
|--------------------------------------------------------------------------
*/

$auth =
    new AuthController();


try {

    $resultado =
        $auth->login(
            $email,
            $senha
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[Login] Erro inesperado durante autenticação: "
        .
        $erro->getMessage()
    );


    mensagem(
        "erro",
        "generico"
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
| VALIDA RESPOSTA DO CONTROLLER
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $resultado
    )
) {

    error_log(
        "[Login] AuthController retornou resposta inválida."
    );


    mensagem(
        "erro",
        "generico"
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
| LOGIN COM ERRO
|--------------------------------------------------------------------------
*/

if (
    ($resultado["status"] ?? false)
    !== true
) {

    $erroLogin =
        is_string(
            $resultado["mensagem"]
            ??
            null
        )
            ?
            $resultado["mensagem"]
            :
            "";


    switch (
        $erroLogin
    ) {

        /*
        |--------------------------------------------------------------------------
        | E-MAIL NÃO CONFIRMADO
        |--------------------------------------------------------------------------
        */

        case "Confirme seu email antes de entrar.":

            mensagem(
                "erro",
                "email_nao_confirmado"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | CONTA BLOQUEADA
        |--------------------------------------------------------------------------
        */

        case "Conta bloqueada administrativamente.":

            mensagem(
                "erro",
                "conta_bloqueada"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | CREDENCIAIS INVÁLIDAS
        |--------------------------------------------------------------------------
        */

        case "Email ou senha inválidos.":

            mensagem(
                "erro",
                "login_invalido"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | RESPOSTA DESCONHECIDA
        |--------------------------------------------------------------------------
        */

        default:

            if (
                $erroLogin !==
                ""
            ) {

                error_log(
                    "[Login] Resposta não reconhecida do AuthController: "
                    .
                    $erroLogin
                );

            }


            mensagem(
                "erro",
                "login_invalido"
            );

            break;

    }


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
| DADOS DO USUÁRIO
|--------------------------------------------------------------------------
*/

$usuario =
    $resultado["usuario"]
    ??
    null;


if (
    !is_array(
        $usuario
    )
) {

    error_log(
        "[Login] Autenticação retornou sucesso sem dados válidos do usuário."
    );


    mensagem(
        "erro",
        "generico"
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

    error_log(
        "[Login] Autenticação retornou ID de usuário inválido."
    );


    mensagem(
        "erro",
        "generico"
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
| DADOS DA CONTA
|--------------------------------------------------------------------------
*/

$nomeUsuario =
    is_string(
        $usuario["nome"]
        ??
        null
    )
        ?
        trim(
            $usuario["nome"]
        )
        :
        "";


$emailUsuario =
    is_string(
        $usuario["email"]
        ??
        null
    )
        ?
        trim(
            $usuario["email"]
        )
        :
        $email;


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


/*
|--------------------------------------------------------------------------
| VALIDA TIPO DE ACESSO
|--------------------------------------------------------------------------
*/

$tiposAcessoPermitidos = [
    ACCESS_USUARIO,
    ACCESS_PROFESSOR,
    ACCESS_ADMIN
];


if (
    !in_array(
        $tipoAcesso,
        $tiposAcessoPermitidos,
        true
    )
) {

    error_log(
        "[Login] Usuário "
        .
        $usuarioId
        .
        " possui tipo_acesso inválido."
    );


    mensagem(
        "erro",
        "sem_permissao"
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
| LIMPA ESTADOS DE AUTENTICAÇÕES ANTERIORES
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["usuario"],
    $_SESSION["seguranca"],
    $_SESSION["usuario_id_confirmacao"],
    $_SESSION["recuperacao"]
);


/*
|--------------------------------------------------------------------------
| REGENERA ID DA SESSÃO
|--------------------------------------------------------------------------
|
| Proteção contra Session Fixation.
|
|--------------------------------------------------------------------------
*/

if (
    !session_regenerate_id(
        true
    )
) {

    error_log(
        "[Login] Não foi possível regenerar o ID da sessão do usuário "
        .
        $usuarioId
        .
        "."
    );


    unset(
        $_SESSION["usuario"],
        $_SESSION["seguranca"]
    );


    mensagem(
        "erro",
        "generico"
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
| SALVA USUÁRIO NA SESSÃO
|--------------------------------------------------------------------------
*/

$_SESSION["usuario"] = [

    "id" =>
        $usuarioId,

    "nome" =>
        $nomeUsuario,

    "email" =>
        $emailUsuario,

    "tipo_acesso" =>
        $tipoAcesso

];


/*
|--------------------------------------------------------------------------
| ADMINISTRADOR
|--------------------------------------------------------------------------
*/

if (
    $tipoAcesso ===
    ACCESS_ADMIN
) {

    /*
    |--------------------------------------------------------------------------
    | CRIA SEGUNDA CREDENCIAL ADMINISTRATIVA
    |--------------------------------------------------------------------------
    */

    try {

        $sessaoAdministrativaCriada =
            adminSessaoCriar(
                $pdo,
                $usuarioId
            );

    }
    catch (
        Throwable $erro
    ) {

        error_log(
            "[LoginAdmin] Erro ao criar sessão administrativa para usuário "
            .
            $usuarioId
            .
            ": "
            .
            $erro->getMessage()
        );


        $sessaoAdministrativaCriada =
            false;

    }


    /*
    |--------------------------------------------------------------------------
    | FAIL CLOSED
    |--------------------------------------------------------------------------
    */

    if (
        !$sessaoAdministrativaCriada
    ) {

        error_log(
            "[LoginAdmin] Não foi possível criar sessão administrativa para usuário "
            .
            $usuarioId
            .
            "."
        );


        adminSessaoLimparLocal();


        unset(
            $_SESSION["usuario"]
        );


        mensagem(
            "erro",
            "generico"
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
    | REGENERA TOKEN DA URL ADMINISTRATIVA
    |--------------------------------------------------------------------------
    */

    adminUrlRegenerar();


    /*
    |--------------------------------------------------------------------------
    | REGISTRO
    |--------------------------------------------------------------------------
    */

    error_log(
        "[LoginAdmin] Sessão administrativa criada para usuário "
        .
        $usuarioId
        .
        "."
    );


    /*
    |--------------------------------------------------------------------------
    | MENSAGEM
    |--------------------------------------------------------------------------
    */

    mensagem(
        "sucesso",
        "login"
    );


    /*
    |--------------------------------------------------------------------------
    | REDIRECIONA
    |--------------------------------------------------------------------------
    */

    header(
        "Location: "
        .
        adminUrl(
            "inicio.php"
        ),
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| CONTA NÃO ADMINISTRATIVA
|--------------------------------------------------------------------------
|
| Nenhuma credencial administrativa pode sobreviver.
|
|--------------------------------------------------------------------------
*/

adminSessaoLimparLocal();


/*
|--------------------------------------------------------------------------
| BUSCA PERFIL GERAL
|--------------------------------------------------------------------------
*/

try {

    $perfilModel =
        new PerfilUsuario();


    $perfil =
        $perfilModel->buscarPorUsuario(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[Login] Falha ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    unset(
        $_SESSION["usuario"]
    );


    mensagem(
        "erro",
        "erro_banco"
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
| PERFIL AINDA NÃO ESCOLHIDO
|--------------------------------------------------------------------------
*/

if (
    !$perfil
) {

    mensagem(
        "aviso",
        "perfil_incompleto"
    );


    header(
        "Location: "
        .
        rota(
            "perfil.escolher"
        ),
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDA PERFIL RETORNADO
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $perfil
    )
) {

    error_log(
        "[Login] Perfil do usuário "
        .
        $usuarioId
        .
        " possui formato inválido."
    );


    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        rota(
            "perfil.escolher"
        ),
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| DADOS DO PERFIL
|--------------------------------------------------------------------------
*/

$tipoPerfil =
    is_string(
        $perfil["tipo_usuario"]
        ??
        null
    )
        ?
        trim(
            $perfil["tipo_usuario"]
        )
        :
        "";


$perfilCompleto =
    (int) (
        $perfil["perfil_completo"]
        ??
        0
    );


/*
|--------------------------------------------------------------------------
| TIPOS DE PERFIL PERMITIDOS
|--------------------------------------------------------------------------
*/

$tiposPerfilPermitidos = [
    PROFILE_ESTUDANTE,
    PROFILE_PROFESSOR,
    PROFILE_APRENDIZ
];


if (
    !in_array(
        $tipoPerfil,
        $tiposPerfilPermitidos,
        true
    )
) {

    error_log(
        "[Login] Usuário "
        .
        $usuarioId
        .
        " possui tipo_usuario inválido: "
        .
        $tipoPerfil
    );


    mensagem(
        "aviso",
        "perfil_incompleto"
    );


    header(
        "Location: "
        .
        rota(
            "perfil.escolher"
        ),
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| PERFIL INCOMPLETO
|--------------------------------------------------------------------------
*/

if (
    $perfilCompleto !==
    1
) {

    mensagem(
        "aviso",
        "perfil_incompleto"
    );


    switch (
        $tipoPerfil
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

}


/*
|--------------------------------------------------------------------------
| LOGIN REALIZADO
|--------------------------------------------------------------------------
*/

mensagem(
    "sucesso",
    "login"
);


/*
|--------------------------------------------------------------------------
| REDIRECIONA PARA O PAINEL CORRETO
|--------------------------------------------------------------------------
*/

switch (
    $tipoPerfil
) {

    case PROFILE_ESTUDANTE:

        header(
            "Location: "
            .
            rota(
                "estudante.painel"
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
                "professor.painel"
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
                "aprendiz.painel"
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
    "aviso",
    "perfil_incompleto"
);


header(
    "Location: "
    .
    rota(
        "perfil.escolher"
    ),
    true,
    303
);


exit;