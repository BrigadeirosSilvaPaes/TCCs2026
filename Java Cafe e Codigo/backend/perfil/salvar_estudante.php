<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| SALVA / ATUALIZA O PERFIL DE ESTUDANTE
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
require_once __DIR__ . "/../models/PerfilUsuario.php";


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


$linkPainelGeral =
    rota(
        "painel"
    );


$linkQuestionario =
    rota(
        "estudante.questionario"
    );


$linkPerfilEstudante =
    rota(
        "estudante.perfil"
    );


$linkPainelEstudante =
    rota(
        "estudante.painel"
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
    $usuarioId <= 0
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
| ADMINISTRADOR NÃO UTILIZA PERFIL DE ESTUDANTE
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
    $tipoAcesso === ACCESS_ADMIN
) {

    mensagem(
        "erro",
        "sem_permissao"
    );


    header(
        "Location: "
        .
        $linkPainelGeral,
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
    $metodo !== "POST"
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
| O CSRF formal será ligado quando auditarmos os formulários
| específicos do estudante.
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
    $fetchSite === "cross-site"
) {

    http_response_code(
        403
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| LIMITA TAMANHO DO POST
|--------------------------------------------------------------------------
*/

$contentLength =
    (int) (
        $_SERVER["CONTENT_LENGTH"]
        ??
        0
    );


if (
    $contentLength > 32768
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkQuestionario,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| MODO
|--------------------------------------------------------------------------
|
| cadastro:
| preenchimento inicial.
|
| editar:
| atualização dos dados específicos.
|
| O modo altera somente o destino após o sucesso.
|
|--------------------------------------------------------------------------
*/

$modoRecebido =
    $_POST["modo"]
    ??
    "cadastro";


if (
    !is_string(
        $modoRecebido
    )
) {

    $modoRecebido =
        "cadastro";

}


$modo =
    strtolower(
        trim(
            $modoRecebido
        )
    );


if (
    !in_array(
        $modo,
        [
            "cadastro",
            "editar"
        ],
        true
    )
) {

    $modo =
        "cadastro";

}


$modoEdicao =
    $modo === "editar";


/*
|--------------------------------------------------------------------------
| BUSCA PERFIL GERAL
|--------------------------------------------------------------------------
*/

try {

    $perfilUsuarioModel =
        new PerfilUsuario();


    $perfil =
        $perfilUsuarioModel->buscarPorUsuario(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalvarEstudante] Erro ao buscar perfil do usuário "
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
        $linkPainelGeral,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| PERFIL GERAL NÃO EXISTE
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $perfil
    )
) {

    mensagem(
        "aviso",
        "perfil_incompleto"
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
| GARANTE PERFIL ESTUDANTE
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


if (
    $tipoPerfil !== PROFILE_ESTUDANTE
) {

    mensagem(
        "erro",
        "sem_permissao"
    );


    header(
        "Location: "
        .
        $linkPainelGeral,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| DADOS ATUAIS DO PERFIL
|--------------------------------------------------------------------------
|
| O questionário específico normalmente envia apenas instituição.
|
| Estado, cidade e objetivo pertencem ao perfil geral.
|
|--------------------------------------------------------------------------
*/

$estadoAtual =
    is_string(
        $perfil["estado"]
        ??
        null
    )
        ?
        trim(
            $perfil["estado"]
        )
        :
        "";


$cidadeAtual =
    is_string(
        $perfil["cidade"]
        ??
        null
    )
        ?
        trim(
            $perfil["cidade"]
        )
        :
        "";


$objetivoAtual =
    is_string(
        $perfil["objetivo"]
        ??
        null
    )
        ?
        trim(
            $perfil["objetivo"]
        )
        :
        "";


/*
|--------------------------------------------------------------------------
| RECEBE INSTITUIÇÃO
|--------------------------------------------------------------------------
*/

$instituicaoRecebida =
    $_POST["instituicao"]
    ??
    null;


if (
    !is_string(
        $instituicaoRecebida
    )
) {

    mensagem(
        "erro",
        "campos_vazios"
    );


    header(
        "Location: "
        .
        $linkQuestionario,
        true,
        303
    );


    exit;

}


$instituicao =
    strtolower(
        trim(
            $instituicaoRecebida
        )
    );


/*
|--------------------------------------------------------------------------
| CAMPOS GERAIS — COMPATIBILIDADE
|--------------------------------------------------------------------------
|
| Mantemos suporte caso uma versão antiga do formulário envie
| estado, cidade ou objetivo.
|
|--------------------------------------------------------------------------
*/

$estadoRecebido =
    $_POST["estado"]
    ??
    $estadoAtual;


$cidadeRecebida =
    $_POST["cidade"]
    ??
    $cidadeAtual;


$objetivoRecebido =
    $_POST["objetivo"]
    ??
    $objetivoAtual;


if (
    !is_string(
        $estadoRecebido
    )
    ||
    !is_string(
        $cidadeRecebida
    )
    ||
    !is_string(
        $objetivoRecebido
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkQuestionario,
        true,
        303
    );


    exit;

}


$estado =
    strtoupper(
        trim(
            $estadoRecebido
        )
    );


$cidade =
    trim(
        $cidadeRecebida
    );


$objetivo =
    trim(
        $objetivoRecebido
    );


/*
|--------------------------------------------------------------------------
| UTF-8
|--------------------------------------------------------------------------
*/

if (
    !mb_check_encoding(
        $instituicao,
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
        $linkQuestionario,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| INSTITUIÇÕES PERMITIDAS
|--------------------------------------------------------------------------
*/

$instituicoesPermitidas = [

    "brigadeiro",
    "estadual",
    "municipal",
    "privada",
    "superior"

];


/*
|--------------------------------------------------------------------------
| VALIDA INSTITUIÇÃO
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $instituicao,
        $instituicoesPermitidas,
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
        $linkQuestionario,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| ESTADOS PERMITIDOS
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
        "generico"
    );


    header(
        "Location: "
        .
        $linkQuestionario,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| CIDADE
|--------------------------------------------------------------------------
*/

if (
    $estado === "RS"
) {

    if (
        $cidade === ""
    ) {

        mensagem(
            "erro",
            "campos_vazios"
        );


        header(
            "Location: "
            .
            $linkQuestionario,
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
        > 100
    ) {

        mensagem(
            "erro",
            "generico"
        );


        header(
            "Location: "
            .
            $linkQuestionario,
            true,
            303
        );


        exit;

    }


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
            $linkQuestionario,
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
*/

if (
    mb_strlen(
        $objetivo,
        "UTF-8"
    )
    > 500
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkQuestionario,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| CARACTERES DE CONTROLE
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
        $linkQuestionario,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| OBJETIVO OPCIONAL
|--------------------------------------------------------------------------
*/

$objetivoBanco =
    $objetivo !== ""
        ? $objetivo
        : null;


/*
|--------------------------------------------------------------------------
| VERIFICA RIO GRANDE - RS
|--------------------------------------------------------------------------
*/

$ehRioGrande =
    (
        $estado === "RS"
        &&
        is_string(
            $cidade
        )
        &&
        mb_strtolower(
            trim(
                $cidade
            ),
            "UTF-8"
        )
        === "rio grande"
    );


/*
|--------------------------------------------------------------------------
| ESCOLA BRIGADEIRO
|--------------------------------------------------------------------------
|
| A opção Brigadeiro somente pode ser utilizada por estudantes
| de Rio Grande - RS.
|
|--------------------------------------------------------------------------
*/

if (
    $instituicao === "brigadeiro"
    &&
    !$ehRioGrande
) {

    mensagem(
        "erro",
        "generico"
    );


    header(
        "Location: "
        .
        $linkQuestionario,
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

$perfilController =
    new PerfilController();


/*
|--------------------------------------------------------------------------
| TRANSAÇÃO
|--------------------------------------------------------------------------
|
| O perfil de estudante envolve três alterações relacionadas:
|
| 1. perfil geral;
| 2. dados específicos do estudante;
| 3. perfil marcado como completo.
|
| Não queremos deixar o cadastro pela metade.
|
|--------------------------------------------------------------------------
*/

$transacaoIniciada =
    false;


try {

    if (
        !$pdo->inTransaction()
    ) {

        $pdo->beginTransaction();


        $transacaoIniciada =
            true;

    }


    /*
    |--------------------------------------------------------------------------
    | PERFIL GERAL
    |--------------------------------------------------------------------------
    */

    $resultadoPerfil =
        $perfilController->salvarPerfilUsuario(
            $usuarioId,
            PROFILE_ESTUDANTE,
            $estado,
            $cidade,
            $objetivoBanco
        );


    if (
        !is_array(
            $resultadoPerfil
        )
        ||
        ($resultadoPerfil["status"] ?? false)
        !== true
    ) {

        $mensagemController =
            is_array(
                $resultadoPerfil
            )
            &&
            is_string(
                $resultadoPerfil["mensagem"]
                ??
                null
            )
                ?
                $resultadoPerfil["mensagem"]
                :
                "Falha ao salvar perfil geral.";


        throw new RuntimeException(
            $mensagemController
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PERFIL ESPECÍFICO
    |--------------------------------------------------------------------------
    */

    $resultadoEstudante =
        $perfilController->salvarEstudante(
            $usuarioId,
            $instituicao
        );


    if (
        !is_array(
            $resultadoEstudante
        )
        ||
        ($resultadoEstudante["status"] ?? false)
        !== true
    ) {

        $mensagemController =
            is_array(
                $resultadoEstudante
            )
            &&
            is_string(
                $resultadoEstudante["mensagem"]
                ??
                null
            )
                ?
                $resultadoEstudante["mensagem"]
                :
                "Falha ao salvar perfil específico.";


        throw new RuntimeException(
            $mensagemController
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PERFIL COMPLETO
    |--------------------------------------------------------------------------
    */

    $resultadoCompleto =
        $perfilController->marcarPerfilCompleto(
            $usuarioId
        );


    if (
        !is_array(
            $resultadoCompleto
        )
        ||
        ($resultadoCompleto["status"] ?? false)
        !== true
    ) {

        $mensagemController =
            is_array(
                $resultadoCompleto
            )
            &&
            is_string(
                $resultadoCompleto["mensagem"]
                ??
                null
            )
                ?
                $resultadoCompleto["mensagem"]
                :
                "Falha ao finalizar perfil.";


        throw new RuntimeException(
            $mensagemController
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COMMIT
    |--------------------------------------------------------------------------
    */

    if (
        $transacaoIniciada
        &&
        $pdo->inTransaction()
    ) {

        $pdo->commit();

    }

}
catch (
    Throwable $erro
) {

    if (
        $transacaoIniciada
        &&
        $pdo->inTransaction()
    ) {

        try {

            $pdo->rollBack();

        }
        catch (
            Throwable $erroRollback
        ) {

            error_log(
                "[SalvarEstudante] Falha no rollback do usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erroRollback->getMessage()
            );

        }

    }


    error_log(
        "[SalvarEstudante] Não foi possível salvar perfil do usuário "
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
        $linkQuestionario,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| SUCESSO
|--------------------------------------------------------------------------
*/

mensagem(
    "sucesso",
    "perfil_completo"
);


/*
|--------------------------------------------------------------------------
| EDIÇÃO
|--------------------------------------------------------------------------
*/

if (
    $modoEdicao
) {

    header(
        "Location: "
        .
        $linkPerfilEstudante,
        true,
        303
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| PRIMEIRO CADASTRO
|--------------------------------------------------------------------------
*/

header(
    "Location: "
    .
    $linkPainelEstudante,
    true,
    303
);


exit;