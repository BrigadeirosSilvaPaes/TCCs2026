<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| PROCESSAMENTO DA CONFIRMAÇÃO DE E-MAIL
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

$linkCadastro =
    rota(
        "cadastro"
    );


$linkConfirmacao =
    rota(
        "confirmar_email"
    );


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
| O formulário envia apenas um código de seis dígitos.
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
    8192
) {

    mensagem(
        "erro",
        "codigo_invalido"
    );


    header(
        "Location: "
        .
        $linkConfirmacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO EM CONFIRMAÇÃO
|--------------------------------------------------------------------------
|
| O ID não é aceito pelo formulário.
|
| Ele permanece apenas na sessão criada durante o cadastro.
|
|--------------------------------------------------------------------------
*/

$usuarioId =
    (int) (
        $_SESSION["usuario_id_confirmacao"]
        ??
        0
    );


/*
|--------------------------------------------------------------------------
| FLUXO DE CONFIRMAÇÃO INVÁLIDO
|--------------------------------------------------------------------------
*/

if (
    $usuarioId <=
    0
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkCadastro,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| RECEBE CÓDIGO
|--------------------------------------------------------------------------
*/

$codigoRecebido =
    $_POST["codigo"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| VALIDA TIPO
|--------------------------------------------------------------------------
|
| Evita entradas como:
|
| codigo[]=123456
|
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $codigoRecebido
    )
) {

    mensagem(
        "erro",
        "codigo_invalido"
    );


    header(
        "Location: "
        .
        $linkConfirmacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| NORMALIZA CÓDIGO
|--------------------------------------------------------------------------
*/

$codigo =
    trim(
        $codigoRecebido
    );


/*
|--------------------------------------------------------------------------
| CÓDIGO OBRIGATÓRIO
|--------------------------------------------------------------------------
*/

if (
    $codigo ===
    ""
) {

    mensagem(
        "erro",
        "codigo_invalido"
    );


    header(
        "Location: "
        .
        $linkConfirmacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDA FORMATO
|--------------------------------------------------------------------------
|
| O código precisa possuir:
|
| - exatamente 6 caracteres;
| - apenas números ASCII de 0 a 9.
|
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        "/^[0-9]{6}$/D",
        $codigo
    )
    !== 1
) {

    mensagem(
        "erro",
        "codigo_invalido"
    );


    header(
        "Location: "
        .
        $linkConfirmacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| CONFIRMA E-MAIL
|--------------------------------------------------------------------------
*/

$auth =
    new AuthController();


try {

    $resultado =
        $auth->confirmarEmail(
            $usuarioId,
            $codigo
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[ConfirmacaoEmail] Erro inesperado ao confirmar usuário "
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
        $linkConfirmacao,
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
        "[ConfirmacaoEmail] AuthController retornou resposta inválida para usuário "
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
        $linkConfirmacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| CONFIRMAÇÃO COM ERRO
|--------------------------------------------------------------------------
*/

if (
    ($resultado["status"] ?? false)
    !== true
) {

    $erroConfirmacao =
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
        $erroConfirmacao
    ) {

        /*
        |--------------------------------------------------------------------------
        | CÓDIGO INVÁLIDO
        |--------------------------------------------------------------------------
        */

        case "Código inválido.":

            mensagem(
                "erro",
                "codigo_invalido"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | CÓDIGO EXPIRADO
        |--------------------------------------------------------------------------
        */

        case "Código expirado.":

            mensagem(
                "erro",
                "codigo_expirado"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | FALHA AO ATIVAR USUÁRIO
        |--------------------------------------------------------------------------
        */

        case "Erro ao ativar usuário.":

            error_log(
                "[ConfirmacaoEmail] Não foi possível ativar usuário "
                .
                $usuarioId
                .
                "."
            );


            mensagem(
                "erro",
                "erro_banco"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | RESPOSTA NÃO RECONHECIDA
        |--------------------------------------------------------------------------
        */

        default:

            error_log(
                "[ConfirmacaoEmail] Resposta inesperada para usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erroConfirmacao
            );


            mensagem(
                "erro",
                "generico"
            );

            break;

    }


    header(
        "Location: "
        .
        $linkConfirmacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| CONFIRMAÇÃO CONCLUÍDA
|--------------------------------------------------------------------------
|
| A conta foi confirmada.
|
| Removemos o estado temporário utilizado durante esse fluxo.
|
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["usuario_id_confirmacao"]
);


/*
|--------------------------------------------------------------------------
| REGENERA ID DA SESSÃO
|--------------------------------------------------------------------------
|
| Encerramos um estado sensível da sessão e criamos um novo identificador
| antes de seguir para a tela de login.
|
|--------------------------------------------------------------------------
*/

if (
    !session_regenerate_id(
        true
    )
) {

    error_log(
        "[ConfirmacaoEmail] Não foi possível regenerar a sessão após confirmar usuário "
        .
        $usuarioId
        .
        "."
    );

}


/*
|--------------------------------------------------------------------------
| MENSAGEM DE SUCESSO
|--------------------------------------------------------------------------
*/

mensagem(
    "sucesso",
    "email_confirmado"
);


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