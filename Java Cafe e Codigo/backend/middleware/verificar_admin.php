<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . "/../config/helpers.php";


/*
|--------------------------------------------------------------------------
| SEGUNDA CREDENCIAL ADMINISTRATIVA
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../security/admin_session.php";


/*
|--------------------------------------------------------------------------
| GARANTE SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| CABEÇALHOS DE SEGURANÇA
|--------------------------------------------------------------------------
|
| Páginas administrativas não devem permanecer armazenadas
| no cache do navegador.
|
*/

header(
    "Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private"
);

header(
    "Pragma: no-cache"
);

header(
    "Expires: 0"
);


/*
|--------------------------------------------------------------------------
| VERIFICA SESSÃO DE USUÁRIO
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["usuario"]) ||
    !is_array($_SESSION["usuario"])
) {

    mensagem(
        "erro",
        "login_necessario"
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
| ID DO USUÁRIO
|--------------------------------------------------------------------------
|
| Neste momento confiamos apenas no ID para localizar
| novamente a conta diretamente no banco.
|
*/

$usuarioId =
    (int) (
        $_SESSION["usuario"]["id"]
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| ID INVÁLIDO
|--------------------------------------------------------------------------
*/

if (
    $usuarioId <= 0
) {

    unset(
        $_SESSION["usuario"],
        $_SESSION["seguranca"]
    );


    mensagem(
        "erro",
        "login_necessario"
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
| CONFIRMA CONTA DIRETAMENTE NO BANCO
|--------------------------------------------------------------------------
|
| Não confiamos em:
|
| $_SESSION["usuario"]["ativo"]
| $_SESSION["usuario"]["bloqueado"]
| $_SESSION["usuario"]["tipo_acesso"]
|
| para conceder acesso administrativo.
|
*/

try {

    $stmtAdmin =
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


    $stmtAdmin->execute([

        ":usuario_id" =>
            $usuarioId

    ]);


    $usuarioBanco =
        $stmtAdmin->fetch(
            PDO::FETCH_ASSOC
        );

}
catch (Throwable $erro) {

    /*
    |--------------------------------------------------------------------------
    | FAIL CLOSED
    |--------------------------------------------------------------------------
    |
    | Se o banco não puder confirmar a autorização,
    | o acesso administrativo não é liberado.
    |
    */

    error_log(
        "[SegurancaAdmin] Falha ao consultar administrador " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    mensagem(
        "erro",
        "erro_banco"
    );


    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO NÃO EXISTE
|--------------------------------------------------------------------------
*/

if (!$usuarioBanco) {

    unset(
        $_SESSION["usuario"],
        $_SESSION["seguranca"]
    );


    mensagem(
        "erro",
        "login_necessario"
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
| CONTA NÃO CONFIRMADA / INATIVA
|--------------------------------------------------------------------------
*/

if (
    (int) (
        $usuarioBanco["ativo"]
        ?? 0
    ) !== 1
) {

    /*
    |--------------------------------------------------------------------------
    | REMOVE CREDENCIAIS LOCAIS
    |--------------------------------------------------------------------------
    */

    adminSessaoLimparLocal();


    unset(
        $_SESSION["usuario"]
    );


    mensagem(
        "erro",
        "email_nao_confirmado"
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
| CONTA BLOQUEADA
|--------------------------------------------------------------------------
*/

if (
    (int) (
        $usuarioBanco["bloqueado"]
        ?? 0
    ) === 1
) {

    adminSessaoLimparLocal();


    unset(
        $_SESSION["usuario"]
    );


    mensagem(
        "erro",
        "conta_bloqueada"
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
| CONFERE PRIVILÉGIO ADMINISTRATIVO
|--------------------------------------------------------------------------
*/

if (
    ($usuarioBanco["tipo_acesso"] ?? "")
    !== ROLE_ADMIN
) {

    /*
    |--------------------------------------------------------------------------
    | REMOVE POSSÍVEL TOKEN ADMINISTRATIVO LOCAL
    |--------------------------------------------------------------------------
    */

    adminSessaoLimparLocal();


    /*
    |--------------------------------------------------------------------------
    | SINCRONIZA TIPO DE ACESSO
    |--------------------------------------------------------------------------
    */

    $_SESSION["usuario"]["tipo_acesso"] =
        (string) (
            $usuarioBanco["tipo_acesso"]
            ?? "usuario"
        );


    mensagem(
        "erro",
        "sem_permissao"
    );


    header(
        "Location: " .
        BASE_URL .
        "/frontend/pages/perfil/inicio.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| SEGUNDA CREDENCIAL ADMINISTRATIVA
|--------------------------------------------------------------------------
|
| A partir daqui já sabemos que:
|
| - o usuário existe;
| - está ativo;
| - não está bloqueado;
| - continua sendo admin.
|
| Agora exigimos também a sessão administrativa registrada
| na tabela admin_sessoes.
|
*/

$sessaoAdminValida =
    adminSessaoValidar(
        $pdo,
        $usuarioId
    );


/*
|--------------------------------------------------------------------------
| SESSÃO ADMINISTRATIVA INVÁLIDA
|--------------------------------------------------------------------------
|
| Pode ocorrer quando:
|
| - não existe admin_token;
| - token foi alterado;
| - token não existe no banco;
| - session_id não corresponde;
| - sessão expirou;
| - sessão foi revogada;
| - token pertence a outro usuário;
| - registro administrativo foi removido.
|
*/

if (
    !$sessaoAdminValida
) {

    error_log(
        "[SegurancaAdmin] Sessão administrativa inválida para usuário " .
        $usuarioId .
        "."
    );


    /*
    |--------------------------------------------------------------------------
    | ENCERRA AUTENTICAÇÃO LOCAL
    |--------------------------------------------------------------------------
    |
    | Não mantemos uma conta administrativa autenticada
    | parcialmente.
    |
    */

    adminSessaoLimparLocal();


    unset(
        $_SESSION["usuario"]
    );


    /*
    |--------------------------------------------------------------------------
    | TROCA O ID DA SESSÃO
    |--------------------------------------------------------------------------
    |
    | Depois de invalidar uma credencial administrativa,
    | também mudamos o identificador PHP da sessão.
    |
    */

    if (
        session_status() === PHP_SESSION_ACTIVE
    ) {

        session_regenerate_id(
            true
        );

    }


    mensagem(
        "erro",
        "login_necessario"
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
| SINCRONIZA DADOS DA SESSÃO
|--------------------------------------------------------------------------
|
| Como tudo acabou de ser confirmado diretamente no banco,
| atualizamos os dados locais.
|
*/

$_SESSION["usuario"]["id"] =
    (int) $usuarioBanco["id"];


$_SESSION["usuario"]["nome"] =
    (string) (
        $usuarioBanco["nome"]
        ?? ""
    );


$_SESSION["usuario"]["email"] =
    (string) (
        $usuarioBanco["email"]
        ?? ""
    );


$_SESSION["usuario"]["ativo"] =
    (int) (
        $usuarioBanco["ativo"]
        ?? 0
    );


$_SESSION["usuario"]["bloqueado"] =
    (int) (
        $usuarioBanco["bloqueado"]
        ?? 0
    );


$_SESSION["usuario"]["tipo_acesso"] =
    (string) (
        $usuarioBanco["tipo_acesso"]
        ?? ""
    );


/*
|--------------------------------------------------------------------------
| ADMINISTRADOR AUTORIZADO
|--------------------------------------------------------------------------
|
| Para chegar até aqui foi necessário passar por:
|
| ✓ sessão PHP válida
| ✓ usuário existente
| ✓ conta ativa
| ✓ conta não bloqueada
| ✓ tipo_acesso = admin
| ✓ admin_token presente
| ✓ hash do token existente no banco
| ✓ token pertencente ao mesmo usuário
| ✓ vínculo com o mesmo session_id()
| ✓ sessão administrativa não revogada
| ✓ sessão administrativa não expirada
|
| Somente agora a página ou endpoint administrativo continua.
|
*/