<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| PROCESSAMENTO DO CADASTRO
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/helpers.php";
require_once __DIR__ . "/../config/security.php";
require_once __DIR__ . "/../config/permissions.php";
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
| Navegadores modernos informam a origem da navegação através
| do cabeçalho Sec-Fetch-Site.
|
| Se o cabeçalho não existir, não bloqueamos a requisição para
| manter compatibilidade.
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
|
| Um cadastro possui apenas poucos campos de texto.
|
| Não existe motivo para aceitar um corpo de requisição muito grande.
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
        $linkCadastro,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| RECEBE DADOS
|--------------------------------------------------------------------------
|
| Não convertemos arrays ou objetos enviados maliciosamente para string.
|
|--------------------------------------------------------------------------
*/

$nomeRecebido =
    $_POST["nome"]
    ??
    null;


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
| CONFIRMAÇÃO DE SENHA
|--------------------------------------------------------------------------
|
| Mantemos temporariamente compatibilidade com:
|
| confirmarSenha
| confirmar_senha
|
|--------------------------------------------------------------------------
*/

if (
    array_key_exists(
        "confirmarSenha",
        $_POST
    )
) {

    $confirmarSenhaRecebida =
        $_POST["confirmarSenha"];

}
else {

    $confirmarSenhaRecebida =
        $_POST["confirmar_senha"]
        ??
        null;

}


/*
|--------------------------------------------------------------------------
| TIPOS DOS CAMPOS
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $nomeRecebido
    )
    ||
    !is_string(
        $emailRecebido
    )
    ||
    !is_string(
        $senhaRecebida
    )
    ||
    !is_string(
        $confirmarSenhaRecebida
    )
) {

    mensagem(
        "erro",
        "campos_vazios"
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
| NORMALIZA DADOS
|--------------------------------------------------------------------------
*/

$nome =
    trim(
        $nomeRecebido
    );


$email =
    trim(
        $emailRecebido
    );


$senha =
    $senhaRecebida;


$confirmarSenha =
    $confirmarSenhaRecebida;


/*
|--------------------------------------------------------------------------
| VALIDA UTF-8
|--------------------------------------------------------------------------
*/

if (
    !mb_check_encoding(
        $nome,
        "UTF-8"
    )
    ||
    !mb_check_encoding(
        $email,
        "UTF-8"
    )
    ||
    !mb_check_encoding(
        $senha,
        "UTF-8"
    )
    ||
    !mb_check_encoding(
        $confirmarSenha,
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
        $linkCadastro,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| NORMALIZA NOME
|--------------------------------------------------------------------------
|
| "João     Silva"
|
| vira:
|
| "João Silva"
|
|--------------------------------------------------------------------------
*/

$nomeNormalizado =
    preg_replace(
        "/\s+/u",
        " ",
        $nome
    );


if (
    !is_string(
        $nomeNormalizado
    )
) {

    mensagem(
        "erro",
        "nome_invalido"
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


$nome =
    trim(
        $nomeNormalizado
    );


/*
|--------------------------------------------------------------------------
| CAMPOS OBRIGATÓRIOS
|--------------------------------------------------------------------------
*/

if (
    $nome ===
    ""
    ||
    $email ===
    ""
    ||
    $senha ===
    ""
    ||
    $confirmarSenha ===
    ""
) {

    mensagem(
        "erro",
        "campos_vazios"
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
| VALIDA NOME
|--------------------------------------------------------------------------
*/

$tamanhoNome =
    mb_strlen(
        $nome,
        "UTF-8"
    );


if (
    $tamanhoNome <
    3
    ||
    $tamanhoNome >
    100
) {

    mensagem(
        "erro",
        "nome_invalido"
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
| REJEITA CARACTERES DE CONTROLE NO NOME
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        "/[\x00-\x1F\x7F]/u",
        $nome
    )
    === 1
) {

    mensagem(
        "erro",
        "nome_invalido"
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
        $linkCadastro,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| REJEITA BYTE NULO NA SENHA
|--------------------------------------------------------------------------
|
| Além de ser um valor inválido para este contexto, o byte nulo
| pode provocar problemas em determinados algoritmos de hash.
|
|--------------------------------------------------------------------------
*/

if (
    str_contains(
        $senha,
        "\0"
    )
    ||
    str_contains(
        $confirmarSenha,
        "\0"
    )
) {

    mensagem(
        "erro",
        "senha_invalida"
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
| VALIDA TAMANHO MÍNIMO DA SENHA
|--------------------------------------------------------------------------
*/

if (
    mb_strlen(
        $senha,
        "UTF-8"
    )
    <
    MIN_PASSWORD_LENGTH
) {

    mensagem(
        "erro",
        "senha_curta"
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
| VALIDA TAMANHO MÁXIMO DA SENHA
|--------------------------------------------------------------------------
|
| O limite máximo é verificado em bytes.
|
| Isso também mantém compatibilidade segura com hashes baseados
| em bcrypt, cujo limite relevante é de 72 bytes.
|
|--------------------------------------------------------------------------
*/

if (
    strlen(
        $senha
    )
    >
    MAX_PASSWORD_LENGTH
) {

    mensagem(
        "erro",
        "senha_longa"
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
| CONFIRMAÇÃO DA SENHA
|--------------------------------------------------------------------------
*/

if (
    !hash_equals(
        $senha,
        $confirmarSenha
    )
) {

    mensagem(
        "erro",
        "senhas_diferentes"
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
| CADASTRA USUÁRIO
|--------------------------------------------------------------------------
|
| O tipo de acesso é definido exclusivamente pelo backend.
|
| O cadastro público jamais recebe "admin" do formulário.
|
|--------------------------------------------------------------------------
*/

$auth =
    new AuthController();


try {

    $resultado =
        $auth->cadastrar(
            $nome,
            $email,
            $senha,
            ACCESS_USUARIO
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[Cadastro] Erro inesperado durante cadastro: "
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
        $linkCadastro,
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
        "[Cadastro] AuthController retornou um resultado inválido."
    );


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
| CADASTRO COM ERRO
|--------------------------------------------------------------------------
*/

if (
    ($resultado["status"] ?? false)
    !== true
) {

    $erroCadastro =
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
        $erroCadastro
    ) {

        /*
        |--------------------------------------------------------------------------
        | E-MAIL JÁ CADASTRADO
        |--------------------------------------------------------------------------
        */

        case "Email já cadastrado.":

            mensagem(
                "erro",
                "email_existente"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | FALHA NA CRIAÇÃO DO USUÁRIO
        |--------------------------------------------------------------------------
        */

        case "Erro ao criar usuário.":

            mensagem(
                "erro",
                "erro_banco"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | FALHA AO GERAR CONFIRMAÇÃO
        |--------------------------------------------------------------------------
        */

        case "Erro ao gerar confirmação.":

            mensagem(
                "erro",
                "erro_banco"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | FALHA NO ENVIO DO E-MAIL
        |--------------------------------------------------------------------------
        */

        case "Usuário criado, mas erro ao enviar código.":

            mensagem(
                "erro",
                "erro_email"
            );

            break;


        /*
        |--------------------------------------------------------------------------
        | ERRO DESCONHECIDO
        |--------------------------------------------------------------------------
        */

        default:

            error_log(
                "[Cadastro] Resposta inesperada do AuthController: "
                .
                $erroCadastro
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
        $linkCadastro,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| ID DO NOVO USUÁRIO
|--------------------------------------------------------------------------
*/

$usuarioId =
    (int) (
        $resultado["usuario_id"]
        ??
        0
    );


if (
    $usuarioId <=
    0
) {

    error_log(
        "[Cadastro] Cadastro retornou sucesso sem usuario_id válido."
    );


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
| RENOVA O ID DA SESSÃO
|--------------------------------------------------------------------------
|
| O usuário ainda não está autenticado, porém a sessão passa a carregar
| um estado sensível relacionado à confirmação da conta.
|
|--------------------------------------------------------------------------
*/

if (
    !session_regenerate_id(
        true
    )
) {

    error_log(
        "[Cadastro] Não foi possível regenerar o ID da sessão."
    );

}


/*
|--------------------------------------------------------------------------
| SALVA USUÁRIO PARA CONFIRMAÇÃO
|--------------------------------------------------------------------------
*/

$_SESSION["usuario_id_confirmacao"] =
    $usuarioId;


/*
|--------------------------------------------------------------------------
| MENSAGEM
|--------------------------------------------------------------------------
*/

mensagem(
    "aviso",
    "confirmar_email"
);


/*
|--------------------------------------------------------------------------
| REDIRECIONA
|--------------------------------------------------------------------------
*/

header(
    "Location: "
    .
    $linkConfirmacao,
    true,
    303
);


exit;