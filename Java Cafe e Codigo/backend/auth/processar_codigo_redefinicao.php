<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| VALIDAÇÃO DO CÓDIGO DE RECUPERAÇÃO DE SENHA
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


$linkNovaSenha =
    rota(
        "nova_senha"
    );


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES DE SEGURANÇA DO FLUXO
|--------------------------------------------------------------------------
|
| Esta limitação funciona na sessão atual.
|
| Posteriormente podemos complementar com rate limiting
| persistente no backend para usuário/IP.
|
|--------------------------------------------------------------------------
*/

$maxTentativas =
    5;


$janelaTentativas =
    600;


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
| O formulário possui apenas um código de seis dígitos.
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
        $linkCodigo,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO EM RECUPERAÇÃO
|--------------------------------------------------------------------------
|
| O ID da conta existe somente na sessão.
|
| Não aceitamos usuario_id vindo:
|
| - da URL;
| - do formulário;
| - de campo hidden.
|
|--------------------------------------------------------------------------
*/

$usuarioId =
    (int) (
        $_SESSION["usuario_id_recuperacao"]
        ??
        0
    );


/*
|--------------------------------------------------------------------------
| FLUXO INVÁLIDO / EXPIRADO
|--------------------------------------------------------------------------
*/

if (
    $usuarioId <=
    0
) {

    unset(
        $_SESSION["usuario_id_recuperacao"],
        $_SESSION["codigo_recuperacao"],
        $_SESSION["codigo_recuperacao_validado"],
        $_SESSION["codigo_recuperacao_validado_em"],
        $_SESSION["recuperacao_codigo_tentativas"],
        $_SESSION["recuperacao_codigo_janela"]
    );


    mensagem(
        "erro",
        "sessao_recuperacao_expirada"
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
| GARANTE QUE UMA VALIDAÇÃO ANTERIOR NÃO SOBREVIVA
|--------------------------------------------------------------------------
|
| Se o usuário voltou para a etapa do código, consideramos que
| qualquer autorização anterior para trocar a senha deve ser descartada.
|
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["codigo_recuperacao"],
    $_SESSION["codigo_recuperacao_validado"],
    $_SESSION["codigo_recuperacao_validado_em"]
);


/*
|--------------------------------------------------------------------------
| CONTROLE DA JANELA DE TENTATIVAS
|--------------------------------------------------------------------------
*/

$agora =
    time();


$inicioJanela =
    (int) (
        $_SESSION["recuperacao_codigo_janela"]
        ??
        0
    );


$tentativas =
    (int) (
        $_SESSION["recuperacao_codigo_tentativas"]
        ??
        0
    );


if (
    $inicioJanela <= 0
    ||
    ($agora - $inicioJanela)
    >
    $janelaTentativas
) {

    $inicioJanela =
        $agora;


    $tentativas =
        0;


    $_SESSION["recuperacao_codigo_janela"] =
        $inicioJanela;


    $_SESSION["recuperacao_codigo_tentativas"] =
        0;

}


/*
|--------------------------------------------------------------------------
| LIMITE DE TENTATIVAS ATINGIDO
|--------------------------------------------------------------------------
*/

if (
    $tentativas >=
    $maxTentativas
) {

    error_log(
        "[RecuperacaoSenha] Limite de tentativas atingido para usuário "
        .
        $usuarioId
        .
        "."
    );


    unset(
        $_SESSION["usuario_id_recuperacao"],
        $_SESSION["codigo_recuperacao"],
        $_SESSION["codigo_recuperacao_validado"],
        $_SESSION["codigo_recuperacao_validado_em"],
        $_SESSION["recuperacao_codigo_tentativas"],
        $_SESSION["recuperacao_codigo_janela"]
    );


    mensagem(
        "erro",
        "sessao_recuperacao_expirada"
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
| Impede entradas malformadas como:
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
        $linkCodigo,
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
        $linkCodigo,
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
| Exatamente seis números ASCII.
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
        $linkCodigo,
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
| VERIFICA CÓDIGO
|--------------------------------------------------------------------------
|
| O Controller confere:
|
| - usuário;
| - token de recuperação;
| - código;
| - validade/expiração.
|
|--------------------------------------------------------------------------
*/

try {

    $codigoValido =
        $auth->verificarCodigoRecuperacao(
            $usuarioId,
            $codigo
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[RecuperacaoSenha] Erro ao verificar código do usuário "
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
        $linkCodigo,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| CÓDIGO INVÁLIDO OU EXPIRADO
|--------------------------------------------------------------------------
|
| Não diferenciamos publicamente:
|
| - código incorreto;
| - código expirado;
| - token inexistente.
|
|--------------------------------------------------------------------------
*/

if (
    $codigoValido !==
    true
) {

    $tentativas++;


    $_SESSION["recuperacao_codigo_tentativas"] =
        $tentativas;


    error_log(
        "[RecuperacaoSenha] Código rejeitado para usuário "
        .
        $usuarioId
        .
        ". Tentativa "
        .
        $tentativas
        .
        " de "
        .
        $maxTentativas
        .
        "."
    );


    /*
    |--------------------------------------------------------------------------
    | LIMITE ATINGIDO NESTA TENTATIVA
    |--------------------------------------------------------------------------
    */

    if (
        $tentativas >=
        $maxTentativas
    ) {

        unset(
            $_SESSION["usuario_id_recuperacao"],
            $_SESSION["codigo_recuperacao"],
            $_SESSION["codigo_recuperacao_validado"],
            $_SESSION["codigo_recuperacao_validado_em"],
            $_SESSION["recuperacao_codigo_tentativas"],
            $_SESSION["recuperacao_codigo_janela"]
        );


        mensagem(
            "erro",
            "sessao_recuperacao_expirada"
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


    mensagem(
        "erro",
        "codigo_invalido"
    );


    header(
        "Location: "
        .
        $linkCodigo,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| REGENERA SESSION ID
|--------------------------------------------------------------------------
|
| Antes:
|
| usuário apenas solicitou recuperação.
|
| Agora:
|
| usuário comprovou acesso ao código enviado.
|
| Essa mudança de privilégio exige um novo session_id.
|
|--------------------------------------------------------------------------
*/

if (
    !session_regenerate_id(
        true
    )
) {

    error_log(
        "[RecuperacaoSenha] Não foi possível regenerar a sessão após validar "
        .
        "o código do usuário "
        .
        $usuarioId
        .
        "."
    );


    /*
    |--------------------------------------------------------------------------
    | FAIL CLOSED
    |--------------------------------------------------------------------------
    */

    unset(
        $_SESSION["usuario_id_recuperacao"],
        $_SESSION["codigo_recuperacao"],
        $_SESSION["codigo_recuperacao_validado"],
        $_SESSION["codigo_recuperacao_validado_em"],
        $_SESSION["recuperacao_codigo_tentativas"],
        $_SESSION["recuperacao_codigo_janela"]
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
| AUTORIZA A REDEFINIÇÃO
|--------------------------------------------------------------------------
|
| O código não é colocado em:
|
| - URL;
| - query string;
| - campo hidden;
| - JavaScript.
|
| Ele permanece exclusivamente na sessão PHP porque a próxima
| etapa ainda precisa dele para consumir o token no banco.
|
|--------------------------------------------------------------------------
*/

$_SESSION["codigo_recuperacao"] =
    $codigo;


$_SESSION["codigo_recuperacao_validado"] =
    true;


/*
|--------------------------------------------------------------------------
| MOMENTO DA VALIDAÇÃO
|--------------------------------------------------------------------------
|
| A próxima etapa poderá verificar há quanto tempo o código
| foi confirmado antes de permitir a troca da senha.
|
|--------------------------------------------------------------------------
*/

$_SESSION["codigo_recuperacao_validado_em"] =
    time();


/*
|--------------------------------------------------------------------------
| LIMPA CONTROLE DE TENTATIVAS
|--------------------------------------------------------------------------
|
| O código já foi validado.
|
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["recuperacao_codigo_tentativas"],
    $_SESSION["recuperacao_codigo_janela"]
);


/*
|--------------------------------------------------------------------------
| LOG SEGURO
|--------------------------------------------------------------------------
|
| Não registramos:
|
| - código;
| - token;
| - e-mail;
| - session_id.
|
|--------------------------------------------------------------------------
*/

error_log(
    "[RecuperacaoSenha] Código validado para usuário "
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
    "codigo_confirmado"
);


/*
|--------------------------------------------------------------------------
| PRÓXIMA ETAPA
|--------------------------------------------------------------------------
*/

header(
    "Location: "
    .
    $linkNovaSenha,
    true,
    303
);


exit;