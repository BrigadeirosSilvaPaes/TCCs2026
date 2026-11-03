<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| PROCESSAMENTO DA NOVA SENHA
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
require_once __DIR__ . "/../security/admin_session.php";


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


$linkNovaSenha =
    rota(
        "nova_senha"
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
| O formulário possui somente os campos da nova senha.
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
        $linkNovaSenha,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| DADOS DO FLUXO DE RECUPERAÇÃO
|--------------------------------------------------------------------------
|
| Nenhum destes valores vem da URL ou do formulário.
|
| Eles precisam existir na sessão PHP atual.
|
|--------------------------------------------------------------------------
*/

$usuarioId =
    (int) (
        $_SESSION["usuario_id_recuperacao"]
        ??
        0
    );


$codigo =
    $_SESSION["codigo_recuperacao"]
    ??
    null;


$codigoValidado =
    $_SESSION["codigo_recuperacao_validado"]
    ??
    false;


$codigoValidadoEm =
    (int) (
        $_SESSION["codigo_recuperacao_validado_em"]
        ??
        0
    );


/*
|--------------------------------------------------------------------------
| VERIFICA FLUXO DE RECUPERAÇÃO
|--------------------------------------------------------------------------
*/

if (
    $usuarioId <=
    0
    ||
    $codigoValidado !==
    true
    ||
    !is_string(
        $codigo
    )
    ||
    preg_match(
        "/^[0-9]{6}$/D",
        $codigo
    )
    !== 1
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
| TEMPO MÁXIMO DA ETAPA
|--------------------------------------------------------------------------
|
| Depois de validar o código, o usuário possui 15 minutos
| para concluir a redefinição.
|
| O token armazenado no banco possui sua própria expiração.
|
|--------------------------------------------------------------------------
*/

$limiteEtapa =
    15 * 60;


$agora =
    time();


if (
    $codigoValidadoEm <=
    0
    ||
    $codigoValidadoEm >
    ($agora + 60)
    ||
    ($agora - $codigoValidadoEm)
    >
    $limiteEtapa
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
| RECEBE NOVA SENHA
|--------------------------------------------------------------------------
*/

$senhaRecebida =
    $_POST["senha"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| CONFIRMAÇÃO
|--------------------------------------------------------------------------
|
| Mantemos compatibilidade temporária com:
|
| confirmar
| confirmar_senha
|
|--------------------------------------------------------------------------
*/

if (
    array_key_exists(
        "confirmar",
        $_POST
    )
) {

    $confirmarRecebido =
        $_POST["confirmar"];

}
else {

    $confirmarRecebido =
        $_POST["confirmar_senha"]
        ??
        null;

}


/*
|--------------------------------------------------------------------------
| VALIDA TIPOS
|--------------------------------------------------------------------------
|
| Evita entradas malformadas como:
|
| senha[]=teste
|
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $senhaRecebida
    )
    ||
    !is_string(
        $confirmarRecebido
    )
) {

    mensagem(
        "erro",
        "campos_vazios"
    );


    header(
        "Location: "
        .
        $linkNovaSenha,
        true,
        303
    );


    exit;

}


$senha =
    $senhaRecebida;


$confirmar =
    $confirmarRecebido;


/*
|--------------------------------------------------------------------------
| CAMPOS OBRIGATÓRIOS
|--------------------------------------------------------------------------
*/

if (
    $senha ===
    ""
    ||
    $confirmar ===
    ""
) {

    mensagem(
        "erro",
        "campos_vazios"
    );


    header(
        "Location: "
        .
        $linkNovaSenha,
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
        $senha,
        "UTF-8"
    )
    ||
    !mb_check_encoding(
        $confirmar,
        "UTF-8"
    )
) {

    mensagem(
        "erro",
        "senha_invalida"
    );


    header(
        "Location: "
        .
        $linkNovaSenha,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| BYTE NULO
|--------------------------------------------------------------------------
*/

if (
    str_contains(
        $senha,
        "\0"
    )
    ||
    str_contains(
        $confirmar,
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
        $linkNovaSenha,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| TAMANHO MÍNIMO
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
        $linkNovaSenha,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| TAMANHO MÁXIMO
|--------------------------------------------------------------------------
|
| O limite máximo da plataforma é verificado em bytes.
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
        $linkNovaSenha,
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
        $confirmar
    )
) {

    mensagem(
        "erro",
        "senhas_diferentes"
    );


    header(
        "Location: "
        .
        $linkNovaSenha,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| REDEFINE SENHA
|--------------------------------------------------------------------------
|
| O AuthController deverá novamente:
|
| - localizar o token;
| - conferir usuário;
| - conferir código;
| - conferir expiração;
| - gerar o hash da nova senha;
| - atualizar usuarios.senha;
| - consumir o token.
|
| Portanto a autorização armazenada na sessão não é suficiente
| sozinha para alterar a senha.
|
|--------------------------------------------------------------------------
*/

$auth =
    new AuthController();


try {

    $resultado =
        $auth->redefinirSenha(
            $usuarioId,
            $codigo,
            $senha
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[RecuperacaoSenha] Erro inesperado ao redefinir senha do usuário "
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
        $linkNovaSenha,
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
        "[RecuperacaoSenha] AuthController retornou resposta inválida "
        .
        "ao redefinir senha do usuário "
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
        $linkNovaSenha,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| FALHA NA REDEFINIÇÃO
|--------------------------------------------------------------------------
*/

if (
    ($resultado["status"] ?? false)
    !== true
) {

    $erroRedefinicao =
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
        $erroRedefinicao
    ) {

        /*
        |--------------------------------------------------------------------------
        | CÓDIGO INVÁLIDO
        |--------------------------------------------------------------------------
        */

        case "Código inválido.":

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
                "codigo_invalido"
            );


            header(
                "Location: "
                .
                $linkRecuperacao,
                true,
                303
            );


            exit;


        /*
        |--------------------------------------------------------------------------
        | CÓDIGO EXPIRADO
        |--------------------------------------------------------------------------
        */

        case "Código expirado.":

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
                "codigo_expirado"
            );


            header(
                "Location: "
                .
                $linkRecuperacao,
                true,
                303
            );


            exit;


        /*
        |--------------------------------------------------------------------------
        | ERRO AO ATUALIZAR SENHA
        |--------------------------------------------------------------------------
        */

        case "Erro ao atualizar senha.":

            error_log(
                "[RecuperacaoSenha] Não foi possível atualizar a senha do usuário "
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
        | RESPOSTA DESCONHECIDA
        |--------------------------------------------------------------------------
        */

        default:

            error_log(
                "[RecuperacaoSenha] Resposta inesperada ao redefinir senha "
                .
                "do usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erroRedefinicao
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
        $linkNovaSenha,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| SENHA ALTERADA
|--------------------------------------------------------------------------
|
| Neste ponto:
|
| - o token existia;
| - o código estava correto;
| - o token estava válido;
| - a nova senha foi salva.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| REVOKA SESSÕES ADMINISTRATIVAS
|--------------------------------------------------------------------------
|
| Caso seja uma conta administrativa, todas as credenciais
| administrativas persistentes desse usuário são invalidadas.
|
|--------------------------------------------------------------------------
*/

try {

    adminSessaoRevogarTodas(
        $pdo,
        $usuarioId
    );

}
catch (
    Throwable $erro
) {

    /*
    |--------------------------------------------------------------------------
    | A SENHA JÁ FOI ALTERADA
    |--------------------------------------------------------------------------
    |
    | Uma falha nessa limpeza secundária não desfaz a alteração,
    | mas precisa ficar registrada.
    |
    |--------------------------------------------------------------------------
    */

    error_log(
        "[RecuperacaoSenha] Não foi possível revogar sessões administrativas "
        .
        "do usuário "
        .
        $usuarioId
        .
        " após redefinição de senha: "
        .
        $erro->getMessage()
    );

}


/*
|--------------------------------------------------------------------------
| LIMPA CREDENCIAL ADMINISTRATIVA LOCAL
|--------------------------------------------------------------------------
*/

adminSessaoLimparLocal();


/*
|--------------------------------------------------------------------------
| FORÇA NOVO LOGIN
|--------------------------------------------------------------------------
|
| Caso o navegador possua uma sessão autenticada antiga,
| ela não deve permanecer válida depois da redefinição da senha.
|
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["usuario"],
    $_SESSION["seguranca"]
);


/*
|--------------------------------------------------------------------------
| REMOVE TODO O FLUXO DE RECUPERAÇÃO
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["usuario_id_recuperacao"],
    $_SESSION["codigo_recuperacao"],
    $_SESSION["codigo_recuperacao_validado"],
    $_SESSION["codigo_recuperacao_validado_em"],
    $_SESSION["recuperacao_codigo_tentativas"],
    $_SESSION["recuperacao_codigo_janela"],
    $_SESSION["recuperacao"]
);


/*
|--------------------------------------------------------------------------
| REMOVE FLUXO DE CONFIRMAÇÃO ANTIGO
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["usuario_id_confirmacao"]
);


/*
|--------------------------------------------------------------------------
| REGENERA SESSION ID
|--------------------------------------------------------------------------
|
| Encerramos o contexto de recuperação e mantemos somente
| a mensagem que será exibida na tela de login.
|
|--------------------------------------------------------------------------
*/

if (
    session_status()
    === PHP_SESSION_ACTIVE
) {

    if (
        !session_regenerate_id(
            true
        )
    ) {

        error_log(
            "[RecuperacaoSenha] Não foi possível regenerar a sessão após "
            .
            "redefinição da senha do usuário "
            .
            $usuarioId
            .
            "."
        );

    }

}


/*
|--------------------------------------------------------------------------
| LOG SEGURO
|--------------------------------------------------------------------------
|
| Não registramos:
|
| - nova senha;
| - código;
| - token;
| - endereço de e-mail;
| - session_id.
|
|--------------------------------------------------------------------------
*/

error_log(
    "[RecuperacaoSenha] Senha redefinida com sucesso para usuário "
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
    "senha_alterada"
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