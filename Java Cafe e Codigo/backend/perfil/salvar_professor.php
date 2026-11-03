<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| SALVAR PERFIL DO PROFESSOR
|
| Responsável pelo preenchimento inicial dos dados profissionais.
|
| Este endpoint:
|
| - exige autenticação;
| - aceita somente POST;
| - exige perfil geral Professor;
| - exige CSRF;
| - valida instituição e disciplina;
| - impede sobrescrever perfil já completo;
| - cria o perfil profissional;
| - marca o perfil geral como completo;
| - executa as alterações em transação.
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
| SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| REDIRECIONAMENTO
|--------------------------------------------------------------------------
*/

$redirecionar =
    static function (
        string $nomeRota,
        int $codigo = 303
    ): never {

        header(
            "Location: "
            .
            rota(
                $nomeRota
            ),
            true,
            $codigo
        );


        exit;

    };


/*
|--------------------------------------------------------------------------
| COMPRIMENTO
|--------------------------------------------------------------------------
*/

$comprimento =
    static function (
        string $texto
    ): int {

        if (
            function_exists(
                "mb_strlen"
            )
        ) {

            return mb_strlen(
                $texto,
                "UTF-8"
            );

        }


        return strlen(
            $texto
        );

    };


/*
|--------------------------------------------------------------------------
| UTF-8
|--------------------------------------------------------------------------
*/

$utf8Valido =
    static function (
        string $texto
    ): bool {

        if (
            function_exists(
                "mb_check_encoding"
            )
        ) {

            return mb_check_encoding(
                $texto,
                "UTF-8"
            );

        }


        return preg_match(
            "//u",
            $texto
        ) === 1;

    };


/*
|--------------------------------------------------------------------------
| NORMALIZA TEXTO
|--------------------------------------------------------------------------
*/

$normalizar =
    static function (
        string $texto
    ): ?string {

        $texto =
            trim(
                $texto
            );


        $normalizado =
            preg_replace(
                "/\s+/u",
                " ",
                $texto
            );


        if (
            !is_string(
                $normalizado
            )
        ) {

            return null;

        }


        return trim(
            $normalizado
        );

    };


/*
|--------------------------------------------------------------------------
| MÉTODO HTTP
|--------------------------------------------------------------------------
*/

$metodo =
    strtoupper(
        trim(
            (string) (
                $_SERVER["REQUEST_METHOD"]
                ??
                ""
            )
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
| USUÁRIO DA SESSÃO
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

    unset(
        $_SESSION["usuario"]
    );


    mensagem(
        "aviso",
        "login_necessario"
    );


    $redirecionar(
        "login"
    );

}


/*
|--------------------------------------------------------------------------
| ID DO USUÁRIO
|--------------------------------------------------------------------------
*/

$usuarioId =
    filter_var(
        $usuario["id"]
        ??
        null,
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 1
            ]
        ]
    );


if (
    $usuarioId === false
    ||
    $usuarioId === null
) {

    unset(
        $_SESSION["usuario"]
    );


    mensagem(
        "aviso",
        "login_necessario"
    );


    $redirecionar(
        "login"
    );

}


$usuarioId =
    (int) $usuarioId;


/*
|--------------------------------------------------------------------------
| ADMINISTRADOR
|--------------------------------------------------------------------------
|
| Admin é nível de acesso da conta e não deve criar
| um perfil pedagógico de Professor por este endpoint.
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
    $tipoAcesso === ACCESS_ADMIN
) {

    mensagem(
        "erro",
        "sem_permissao"
    );


    $redirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| PDO
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $pdo
    )
    ||
    !$pdo instanceof PDO
) {

    error_log(
        "[SalvarProfessor] Conexão PDO indisponível."
    );


    mensagem(
        "erro",
        "erro_banco"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| TAMANHO DA REQUISIÇÃO
|--------------------------------------------------------------------------
*/

$contentLength =
    filter_var(
        $_SERVER["CONTENT_LENGTH"]
        ??
        null,
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 0
            ]
        ]
    );


if (
    $contentLength !== false
    &&
    $contentLength !== null
    &&
    $contentLength > 16384
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| CONTENT-TYPE
|--------------------------------------------------------------------------
*/

$contentType =
    strtolower(
        trim(
            (string) (
                $_SERVER["CONTENT_TYPE"]
                ??
                ""
            )
        )
    );


if (
    $contentType !== ""
    &&
    !str_starts_with(
        $contentType,
        "application/x-www-form-urlencoded"
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| ORIGEM DA SOLICITAÇÃO
|--------------------------------------------------------------------------
|
| Camada adicional ao CSRF.
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
    $fetchSite !== ""
    &&
    !in_array(
        $fetchSite,
        [
            "same-origin",
            "same-site",
            "none"
        ],
        true
    )
) {

    mensagem(
        "erro",
        "csrf_expirado"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

$csrfSessao =
    $_SESSION["csrf"]["professor_questionario"]
    ??
    null;


$csrfFormulario =
    $_POST["csrf_token"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| VALIDA TIPO DOS TOKENS
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $csrfSessao
    )
    ||
    !is_string(
        $csrfFormulario
    )
) {

    unset(
        $_SESSION["csrf"]["professor_questionario"]
    );


    mensagem(
        "erro",
        "csrf_expirado"
    );


    $redirecionar(
        "professor.questionario"
    );

}


$csrfFormulario =
    trim(
        $csrfFormulario
    );


/*
|--------------------------------------------------------------------------
| VALIDA TOKEN
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        "/^[a-f0-9]{64}$/D",
        $csrfSessao
    )
    !== 1
    ||
    preg_match(
        "/^[a-f0-9]{64}$/D",
        $csrfFormulario
    )
    !== 1
    ||
    !hash_equals(
        $csrfSessao,
        $csrfFormulario
    )
) {

    unset(
        $_SESSION["csrf"]["professor_questionario"]
    );


    mensagem(
        "erro",
        "csrf_expirado"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| RECEBE DADOS
|--------------------------------------------------------------------------
*/

$instituicaoRecebida =
    $_POST["instituicao"]
    ??
    null;


$disciplinaRecebida =
    $_POST["disciplina"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| VALIDA TIPOS
|--------------------------------------------------------------------------
|
| Impede entradas como:
|
| instituicao[]=teste
|
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $instituicaoRecebida
    )
    ||
    !is_string(
        $disciplinaRecebida
    )
) {

    mensagem(
        "erro",
        "campos_vazios"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| UTF-8
|--------------------------------------------------------------------------
*/

if (
    !$utf8Valido(
        $instituicaoRecebida
    )
    ||
    !$utf8Valido(
        $disciplinaRecebida
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| NORMALIZA
|--------------------------------------------------------------------------
*/

$instituicao =
    $normalizar(
        $instituicaoRecebida
    );


$disciplina =
    $normalizar(
        $disciplinaRecebida
    );


if (
    $instituicao === null
    ||
    $disciplina === null
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| CAMPOS OBRIGATÓRIOS
|--------------------------------------------------------------------------
*/

if (
    $instituicao === ""
    ||
    $disciplina === ""
) {

    mensagem(
        "erro",
        "campos_vazios"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| INSTITUIÇÃO
|--------------------------------------------------------------------------
*/

$tamanhoInstituicao =
    $comprimento(
        $instituicao
    );


if (
    $tamanhoInstituicao < 2
    ||
    $tamanhoInstituicao > 150
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| DISCIPLINA / ÁREA
|--------------------------------------------------------------------------
*/

$tamanhoDisciplina =
    $comprimento(
        $disciplina
    );


if (
    $tamanhoDisciplina < 2
    ||
    $tamanhoDisciplina > 100
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| CARACTERES DE CONTROLE
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        "/[\x00-\x1F\x7F]/",
        $instituicao
    )
    === 1
    ||
    preg_match(
        "/[\x00-\x1F\x7F]/",
        $disciplina
    )
    === 1
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| CONTROLLER
|--------------------------------------------------------------------------
*/

try {

    $perfilController =
        new PerfilController();


    $resultadoPerfil =
        $perfilController->buscarPerfil(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalvarProfessor] Erro ao carregar perfil do usuário "
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


    $redirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL PRINCIPAL
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $resultadoPerfil
    )
    ||
    ($resultadoPerfil["status"] ?? false)
    !== true
    ||
    !isset(
        $resultadoPerfil["perfil"]["usuario"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]["usuario"]
    )
) {

    mensagem(
        "aviso",
        "perfil_incompleto"
    );


    $redirecionar(
        "perfil.escolher"
    );

}


$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


/*
|--------------------------------------------------------------------------
| GARANTE TIPO PROFESSOR
|--------------------------------------------------------------------------
*/

$tipoUsuario =
    is_string(
        $perfilUsuario["tipo_usuario"]
        ??
        null
    )
        ?
        strtolower(
            trim(
                $perfilUsuario["tipo_usuario"]
            )
        )
        :
        "";


if (
    $tipoUsuario !== PROFILE_PROFESSOR
) {

    mensagem(
        "erro",
        "sem_permissao"
    );


    $redirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL PROFISSIONAL EXISTENTE
|--------------------------------------------------------------------------
*/

$perfilProfessorExistente =
    $resultadoPerfil["perfil"]["professor"]
    ??
    null;


$perfilCompleto =
    (int) (
        $perfilUsuario["perfil_completo"]
        ??
        0
    );


/*
|--------------------------------------------------------------------------
| PERFIL JÁ CONFIGURADO
|--------------------------------------------------------------------------
|
| Se o cadastro profissional existe e o perfil já está completo,
| esta rota não pode ser utilizada para editar os dados.
|
| Alterações devem passar pelo endpoint específico de atualização.
|
|--------------------------------------------------------------------------
*/

if (
    is_array(
        $perfilProfessorExistente
    )
    &&
    !empty(
        $perfilProfessorExistente
    )
    &&
    $perfilCompleto === 1
) {

    unset(
        $_SESSION["csrf"]["professor_questionario"]
    );


    mensagem(
        "sucesso",
        "perfil_completo"
    );


    $redirecionar(
        "professor.painel"
    );

}


/*
|--------------------------------------------------------------------------
| TRANSAÇÃO
|--------------------------------------------------------------------------
|
| As operações precisam ser atômicas:
|
| 1. cria/recupera perfil profissional;
| 2. marca o perfil geral como completo.
|
| Se qualquer etapa falhar:
|
| ROLLBACK.
|
|--------------------------------------------------------------------------
*/

$transacaoIniciada =
    false;


try {

    /*
    |--------------------------------------------------------------------------
    | NÃO HERDA TRANSAÇÃO INESPERADA
    |--------------------------------------------------------------------------
    */

    if (
        $pdo->inTransaction()
    ) {

        throw new RuntimeException(
            "Já existe uma transação ativa antes da criação do perfil de professor."
        );

    }


    $pdo->beginTransaction();


    $transacaoIniciada =
        true;


    /*
    |--------------------------------------------------------------------------
    | PERFIL PROFISSIONAL
    |--------------------------------------------------------------------------
    |
    | Existe um caso de recuperação:
    |
    | versões antigas poderiam criar perfil_professor e falhar antes
    | de marcar perfil_usuario como completo.
    |
    | Nessa situação atualizamos os dados enviados e concluímos o fluxo.
    |
    |--------------------------------------------------------------------------
    */

    if (
        is_array(
            $perfilProfessorExistente
        )
        &&
        !empty(
            $perfilProfessorExistente
        )
    ) {

        $resultadoProfessor =
            $perfilController->atualizarProfessor(
                $usuarioId,
                $instituicao,
                $disciplina
            );

    }
    else {

        $resultadoProfessor =
            $perfilController->salvarProfessor(
                $usuarioId,
                $instituicao,
                $disciplina
            );

    }


    if (
        !is_array(
            $resultadoProfessor
        )
        ||
        ($resultadoProfessor["status"] ?? false)
        !== true
    ) {

        $mensagemController =
            is_array(
                $resultadoProfessor
            )
            &&
            is_string(
                $resultadoProfessor["mensagem"]
                ??
                null
            )
                ?
                $resultadoProfessor["mensagem"]
                :
                "Falha ao salvar perfil profissional.";


        throw new RuntimeException(
            $mensagemController
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MARCA PERFIL COMO COMPLETO
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

    $pdo->commit();


    $transacaoIniciada =
        false;

}
catch (
    Throwable $erro
) {

    /*
    |--------------------------------------------------------------------------
    | ROLLBACK
    |--------------------------------------------------------------------------
    */

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
                "[SalvarProfessor] Falha no rollback do usuário "
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
        "[SalvarProfessor] Não foi possível concluir o perfil do usuário "
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


    $redirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| INVALIDA CSRF UTILIZADO
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["csrf"]["professor_questionario"]
);


/*
|--------------------------------------------------------------------------
| SUCESSO
|--------------------------------------------------------------------------
|
| O perfil está completo, porém os recursos exclusivos do Professor
| continuam sujeitos ao fluxo de aprovação administrativa.
|
|--------------------------------------------------------------------------
*/

mensagem(
    "sucesso",
    "perfil_completo"
);


/*
|--------------------------------------------------------------------------
| PAINEL
|--------------------------------------------------------------------------
*/

$redirecionar(
    "professor.painel"
);