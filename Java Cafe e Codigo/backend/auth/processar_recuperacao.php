<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| PROCESSAMENTO DA RECUPERAÇÃO DE SENHA
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

$linkRecuperacao =
    rota(
        "recuperar_senha"
    );


$linkCodigo =
    rota(
        "recuperar_codigo"
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
| O formulário envia apenas um endereço de e-mail.
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
        "generico"
    );


    header(
        "Location: "
        .
        $linkRecuperacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| LIMPA FLUXO DE RECUPERAÇÃO ANTERIOR
|--------------------------------------------------------------------------
|
| Uma nova solicitação substitui qualquer tentativa anterior
| armazenada na sessão local.
|
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["usuario_id_recuperacao"],
    $_SESSION["codigo_recuperacao"],
    $_SESSION["codigo_recuperacao_validado"]
);


/*
|--------------------------------------------------------------------------
| RECEBE E-MAIL
|--------------------------------------------------------------------------
*/

$emailRecebido =
    $_POST["email"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| VALIDA TIPO
|--------------------------------------------------------------------------
|
| Evita entradas malformadas como:
|
| email[]=teste@exemplo.com
|
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $emailRecebido
    )
) {

    mensagem(
        "erro",
        "campos_vazios"
    );


    header(
        "Location: "
        .
        $linkRecuperacao,
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
    trim(
        $emailRecebido
    );


/*
|--------------------------------------------------------------------------
| CAMPO OBRIGATÓRIO
|--------------------------------------------------------------------------
*/

if (
    $email ===
    ""
) {

    mensagem(
        "erro",
        "campos_vazios"
    );


    header(
        "Location: "
        .
        $linkRecuperacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDA UTF-8
|--------------------------------------------------------------------------
*/

if (
    !mb_check_encoding(
        $email,
        "UTF-8"
    )
) {

    mensagem(
        "erro",
        "email_invalido"
    );


    header(
        "Location: "
        .
        $linkRecuperacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| NORMALIZA PARA MINÚSCULAS
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
        $linkRecuperacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| CONTROLLER
|--------------------------------------------------------------------------
*/

$auth =
    new AuthController();


/*
|--------------------------------------------------------------------------
| LOCALIZA USUÁRIO
|--------------------------------------------------------------------------
|
| Precisamos conhecer internamente o ID da conta para manter
| o fluxo de recuperação somente na sessão.
|
| O ID não será enviado:
|
| - pela URL;
| - em campo hidden;
| - pelo navegador na próxima etapa.
|
|--------------------------------------------------------------------------
*/

try {

    $usuario =
        $auth->usuarioPorEmail(
            $email
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[RecuperacaoSenha] Erro ao procurar conta: "
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
        $linkRecuperacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| CONTA NÃO ENCONTRADA
|--------------------------------------------------------------------------
|
| Não mostramos ao visitante uma mensagem como:
|
| "Este e-mail não está cadastrado".
|
| Isso evita expor diretamente a existência de contas.
|
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $usuario
    )
    ||
    (int) (
        $usuario["id"]
        ??
        0
    )
    <= 0
) {

    /*
    |--------------------------------------------------------------------------
    | LOG INTERNO
    |--------------------------------------------------------------------------
    |
    | Não registramos o endereço de e-mail.
    |
    */

    error_log(
        "[RecuperacaoSenha] Solicitação para conta inexistente."
    );


    /*
    |--------------------------------------------------------------------------
    | RESPOSTA PÚBLICA GENÉRICA
    |--------------------------------------------------------------------------
    */

    mensagem(
        "sucesso",
        "recuperacao_enviada"
    );


    header(
        "Location: "
        .
        $linkRecuperacao,
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

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkRecuperacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| SOLICITA RECUPERAÇÃO
|--------------------------------------------------------------------------
|
| O AuthController é responsável por:
|
| - gerar código;
| - armazenar token/código;
| - definir expiração;
| - enviar o e-mail.
|
|--------------------------------------------------------------------------
*/

try {

    $resultado =
        $auth->solicitarRecuperacao(
            $email
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[RecuperacaoSenha] Erro inesperado ao solicitar recuperação para usuário "
        .
        $usuarioId
        .
        ": "
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
        $linkRecuperacao,
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
        "[RecuperacaoSenha] Resposta inválida do AuthController para usuário "
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
        $linkRecuperacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| RECUPERAÇÃO COM ERRO
|--------------------------------------------------------------------------
*/

if (
    ($resultado["status"] ?? false)
    !== true
) {

    $erroRecuperacao =
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
        $erroRecuperacao
    ) {

        /*
        |--------------------------------------------------------------------------
        | E-MAIL NÃO ENCONTRADO
        |--------------------------------------------------------------------------
        |
        | Mantemos resposta pública genérica.
        |
        |--------------------------------------------------------------------------
        */

        case "Email não encontrado.":

            mensagem(
                "sucesso",
                "recuperacao_enviada"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | ERRO AO CRIAR CÓDIGO
        |--------------------------------------------------------------------------
        */

        case "Erro ao criar código.":

            error_log(
                "[RecuperacaoSenha] Não foi possível criar token para usuário "
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
        | ERRO AO ENVIAR E-MAIL
        |--------------------------------------------------------------------------
        */

        case "Erro ao enviar email.":

            error_log(
                "[RecuperacaoSenha] Falha no envio do e-mail para usuário "
                .
                $usuarioId
                .
                "."
            );


            mensagem(
                "erro",
                "erro_email"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | RESPOSTA DESCONHECIDA
        |--------------------------------------------------------------------------
        */

        default:

            error_log(
                "[RecuperacaoSenha] Resposta inesperada do AuthController para usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erroRecuperacao
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
        $linkRecuperacao,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| RENOVA ID DA SESSÃO
|--------------------------------------------------------------------------
|
| A partir daqui a sessão passa a carregar um estado sensível
| relacionado à recuperação da conta.
|
|--------------------------------------------------------------------------
*/

if (
    !session_regenerate_id(
        true
    )
) {

    error_log(
        "[RecuperacaoSenha] Não foi possível regenerar a sessão para usuário "
        .
        $usuarioId
        .
        "."
    );

}


/*
|--------------------------------------------------------------------------
| SALVA ESTADO DE RECUPERAÇÃO
|--------------------------------------------------------------------------
|
| Mantemos somente o ID da conta.
|
| O código digitado pelo usuário ainda NÃO foi validado.
|
|--------------------------------------------------------------------------
*/

$_SESSION["usuario_id_recuperacao"] =
    $usuarioId;


/*
|--------------------------------------------------------------------------
| GARANTE ESTADO NÃO VALIDADO
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["codigo_recuperacao"],
    $_SESSION["codigo_recuperacao_validado"]
);


/*
|--------------------------------------------------------------------------
| LOG
|--------------------------------------------------------------------------
|
| Não registramos:
|
| - código;
| - token;
| - e-mail.
|
|--------------------------------------------------------------------------
*/

error_log(
    "[RecuperacaoSenha] Código de recuperação solicitado para usuário "
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
    "recuperacao_enviada"
);


/*
|--------------------------------------------------------------------------
| PRÓXIMA ETAPA
|--------------------------------------------------------------------------
*/

header(
    "Location: "
    .
    $linkCodigo,
    true,
    303
);


exit;