<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| ENCERRAMENTO DA SESSÃO
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
require_once __DIR__ . "/../security/admin_session.php";


/*
|--------------------------------------------------------------------------
| GARANTE SESSÃO ATIVA
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| ROTA DE DESTINO
|--------------------------------------------------------------------------
*/

$linkLogin =
    rota(
        "login"
    );


/*
|--------------------------------------------------------------------------
| MÉTODO HTTP
|--------------------------------------------------------------------------
|
| TEMPORARIAMENTE:
|
| GET /sair
|
| permanece permitido porque o frontend atual ainda utiliza links
| para realizar o logout.
|
| Durante a auditoria do frontend, este fluxo deverá migrar para:
|
| POST + CSRF
|
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
    "GET"
) {

    header(
        "Allow: GET"
    );


    http_response_code(
        405
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO ATUAL
|--------------------------------------------------------------------------
|
| O ID precisa ser obtido antes da sessão ser apagada.
|
|--------------------------------------------------------------------------
*/

$usuarioId =
    (int) (
        $_SESSION["usuario"]["id"]
        ??
        0
    );


/*
|--------------------------------------------------------------------------
| REVOGA SESSÃO ADMINISTRATIVA
|--------------------------------------------------------------------------
|
| Caso exista uma segunda credencial administrativa ativa,
| tentamos revogá-la no banco antes de encerrar a sessão local.
|
| Uma eventual indisponibilidade do banco NÃO pode impedir
| o navegador de realizar o logout local.
|
|--------------------------------------------------------------------------
*/

if (
    $usuarioId >
    0
) {

    try {

        $adminToken =
            adminSessaoTokenAtual();


        if (
            $adminToken !==
            null
        ) {

            $revogado =
                adminSessaoRevogar(
                    $pdo,
                    $usuarioId
                );


            if (
                $revogado
            ) {

                error_log(
                    "[LogoutAdmin] Sessão administrativa revogada para usuário "
                    .
                    $usuarioId
                    .
                    "."
                );

            }
            else {

                error_log(
                    "[LogoutAdmin] Não foi possível confirmar a revogação "
                    .
                    "da sessão administrativa no banco para usuário "
                    .
                    $usuarioId
                    .
                    ". A sessão local será encerrada."
                );

            }

        }

    }
    catch (
        Throwable $erro
    ) {

        /*
        |--------------------------------------------------------------------------
        | FAIL LOCAL
        |--------------------------------------------------------------------------
        |
        | Mesmo se o banco estiver temporariamente indisponível,
        | os dados e o cookie da sessão deste navegador serão removidos.
        |
        |--------------------------------------------------------------------------
        */

        error_log(
            "[LogoutAdmin] Erro ao revogar sessão administrativa do usuário "
            .
            $usuarioId
            .
            ": "
            .
            $erro->getMessage()
        );

    }

}


/*
|--------------------------------------------------------------------------
| LIMPA ESTADO ADMINISTRATIVO LOCAL
|--------------------------------------------------------------------------
|
| Remove os dados específicos da segunda autenticação administrativa
| antes da limpeza geral da sessão.
|
|--------------------------------------------------------------------------
*/

try {

    adminSessaoLimparLocal();

}
catch (
    Throwable $erro
) {

    error_log(
        "[Logout] Falha ao executar limpeza administrativa local para usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );

}


/*
|--------------------------------------------------------------------------
| REMOVE TODOS OS DADOS DA SESSÃO
|--------------------------------------------------------------------------
|
| Isso elimina, entre outros:
|
| - usuário autenticado;
| - mensagens temporárias;
| - dados administrativos;
| - dados de confirmação;
| - dados de recuperação;
| - tokens CSRF;
| - estado do perfil;
| - qualquer outro dado salvo na sessão.
|
|--------------------------------------------------------------------------
*/

$_SESSION =
    [];


/*
|--------------------------------------------------------------------------
| REMOVE COOKIE DA SESSÃO
|--------------------------------------------------------------------------
|
| Utilizamos exatamente os mesmos parâmetros do cookie atual.
|
|--------------------------------------------------------------------------
*/

if (
    (bool) ini_get(
        "session.use_cookies"
    )
) {

    $parametros =
        session_get_cookie_params();


    if (
        !headers_sent()
    ) {

        setcookie(
            session_name(),
            "",
            [
                "expires" =>
                    time() - 42000,

                "path" =>
                    is_string(
                        $parametros["path"]
                        ??
                        null
                    )
                        ?
                        $parametros["path"]
                        :
                        "/",

                "domain" =>
                    is_string(
                        $parametros["domain"]
                        ??
                        null
                    )
                        ?
                        $parametros["domain"]
                        :
                        "",

                "secure" =>
                    (bool) (
                        $parametros["secure"]
                        ??
                        false
                    ),

                "httponly" =>
                    (bool) (
                        $parametros["httponly"]
                        ??
                        true
                    ),

                "samesite" =>
                    is_string(
                        $parametros["samesite"]
                        ??
                        null
                    )
                        ?
                        $parametros["samesite"]
                        :
                        "Lax"
            ]
        );

    }
    else {

        error_log(
            "[Logout] Não foi possível remover o cookie da sessão porque os cabeçalhos já foram enviados."
        );

    }

}


/*
|--------------------------------------------------------------------------
| DESTRÓI A SESSÃO PHP
|--------------------------------------------------------------------------
*/

if (
    session_status()
    === PHP_SESSION_ACTIVE
) {

    if (
        !session_destroy()
    ) {

        error_log(
            "[Logout] session_destroy() falhou para usuário "
            .
            $usuarioId
            .
            "."
        );

    }

}


/*
|--------------------------------------------------------------------------
| EVITA CACHE DO RESULTADO DO LOGOUT
|--------------------------------------------------------------------------
*/

if (
    !headers_sent()
) {

    header(
        "Cache-Control: no-store, no-cache, must-revalidate, max-age=0"
    );


    header(
        "Pragma: no-cache"
    );

}


/*
|--------------------------------------------------------------------------
| REDIRECIONA PARA LOGIN
|--------------------------------------------------------------------------
*/

header(
    "Location: "
    .
    $linkLogin,
    true,
    303
);


exit;