<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| SALVAR PERFIL DO APRENDIZ
|
| Endpoint exclusivo para a configuração inicial
| do perfil específico de Aprendiz.
|
| Responsável por:
|
| - validar autenticação;
| - aceitar somente POST;
| - validar origem;
| - validar CSRF;
| - validar experiência;
| - validar objetivo;
| - confirmar perfil geral Aprendiz;
| - criar o perfil específico;
| - recuperar cadastro parcial antigo, quando necessário;
| - marcar o perfil como completo;
| - executar as alterações em transação.
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
| COMPRIMENTO DE TEXTO
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
| NORMALIZA TEXTO COM QUEBRAS DE LINHA
|--------------------------------------------------------------------------
*/

$normalizarTextoLongo =
    static function (
        string $texto
    ): string {

        $texto =
            str_replace(
                [
                    "\r\n",
                    "\r"
                ],
                "\n",
                $texto
            );


        return trim(
            $texto
        );

    };


/*
|--------------------------------------------------------------------------
| CARACTERES DE CONTROLE
|--------------------------------------------------------------------------
|
| Para o objetivo:
|
| permitimos:
|
| - TAB;
| - quebra de linha.
|
|--------------------------------------------------------------------------
*/

$possuiControleInvalido =
    static function (
        string $texto
    ): bool {

        return preg_match(
            "/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/",
            $texto
        ) === 1;

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
| Administrador é nível de acesso da conta.
|
| Este endpoint é exclusivo dos perfis pedagógicos.
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
        "[SalvarAprendiz] Conexão PDO indisponível."
    );


    mensagem(
        "erro",
        "erro_banco"
    );


    $redirecionar(
        "aprendiz.questionario"
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
    $contentLength > 8192
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "aprendiz.questionario"
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
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| ORIGEM DA SOLICITAÇÃO
|--------------------------------------------------------------------------
|
| Defesa adicional ao CSRF.
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
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

$csrfSessao =
    $_SESSION["csrf"]["aprendiz_questionario"]
    ??
    null;


$csrfFormulario =
    $_POST["csrf_token"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| VALIDA TIPOS DOS TOKENS
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
        $_SESSION["csrf"]["aprendiz_questionario"]
    );


    mensagem(
        "erro",
        "csrf_expirado"
    );


    $redirecionar(
        "aprendiz.questionario"
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
        $_SESSION["csrf"]["aprendiz_questionario"]
    );


    mensagem(
        "erro",
        "csrf_expirado"
    );


    $redirecionar(
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| RECEBE DADOS
|--------------------------------------------------------------------------
*/

$experienciaRecebida =
    $_POST["experiencia"]
    ??
    null;


$objetivoRecebido =
    $_POST["objetivo"]
    ??
    "";


/*
|--------------------------------------------------------------------------
| VALIDA TIPOS
|--------------------------------------------------------------------------
|
| Impede entradas malformadas como:
|
| experiencia[]=basica
|
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $experienciaRecebida
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


    $redirecionar(
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| UTF-8
|--------------------------------------------------------------------------
*/

if (
    !$utf8Valido(
        $experienciaRecebida
    )
    ||
    !$utf8Valido(
        $objetivoRecebido
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| NORMALIZA EXPERIÊNCIA
|--------------------------------------------------------------------------
*/

$experiencia =
    strtolower(
        trim(
            $experienciaRecebida
        )
    );


/*
|--------------------------------------------------------------------------
| NORMALIZA OBJETIVO
|--------------------------------------------------------------------------
*/

$objetivo =
    $normalizarTextoLongo(
        $objetivoRecebido
    );


/*
|--------------------------------------------------------------------------
| EXPERIÊNCIAS PERMITIDAS
|--------------------------------------------------------------------------
*/

$experienciasPermitidas = [

    "nenhuma",
    "basica",
    "intermediaria",
    "avancada"

];


/*
|--------------------------------------------------------------------------
| VALIDA EXPERIÊNCIA
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $experiencia,
        $experienciasPermitidas,
        true
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| OBJETIVO
|--------------------------------------------------------------------------
*/

if (
    $comprimento(
        $objetivo
    )
    > 500
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| CARACTERES DE CONTROLE NO OBJETIVO
|--------------------------------------------------------------------------
*/

if (
    $objetivo !== ""
    &&
    $possuiControleInvalido(
        $objetivo
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    $redirecionar(
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| OBJETIVO OPCIONAL
|--------------------------------------------------------------------------
*/

$objetivoBanco =
    $objetivo !== ""
        ?
        $objetivo
        :
        null;


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
        "[SalvarAprendiz] Erro ao carregar perfil do usuário "
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
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA PERFIL PRINCIPAL
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


/*
|--------------------------------------------------------------------------
| PERFIL PRINCIPAL
|--------------------------------------------------------------------------
*/

$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


/*
|--------------------------------------------------------------------------
| GARANTE PERFIL APRENDIZ
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
    $tipoUsuario !== PROFILE_APRENDIZ
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
| PERFIL ESPECÍFICO ATUAL
|--------------------------------------------------------------------------
*/

$perfilAprendizAtual =
    $resultadoPerfil["perfil"]["aprendiz"]
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
| Se o perfil específico existe e o perfil geral já está completo,
| o questionário inicial não deve funcionar como edição.
|
|--------------------------------------------------------------------------
*/

if (
    is_array(
        $perfilAprendizAtual
    )
    &&
    !empty(
        $perfilAprendizAtual
    )
    &&
    $perfilCompleto === 1
) {

    unset(
        $_SESSION["csrf"]["aprendiz_questionario"]
    );


    mensagem(
        "sucesso",
        "perfil_completo"
    );


    $redirecionar(
        "aprendiz.painel"
    );

}


/*
|--------------------------------------------------------------------------
| TRANSAÇÃO
|--------------------------------------------------------------------------
|
| Precisamos concluir:
|
| 1. perfil_aprendiz;
| 2. perfil_usuario como completo.
|
| Qualquer falha:
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
            "Já existe uma transação ativa antes da criação do perfil Aprendiz."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | INICIA TRANSAÇÃO
    |--------------------------------------------------------------------------
    */

    $pdo->beginTransaction();


    $transacaoIniciada =
        true;


    /*
    |--------------------------------------------------------------------------
    | PERFIL ESPECÍFICO
    |--------------------------------------------------------------------------
    |
    | Existe um caso de recuperação:
    |
    | versões antigas poderiam ter criado perfil_aprendiz
    | e falhado antes de marcar perfil_usuario como completo.
    |
    | Nesse cenário atualizamos a linha existente e concluímos o fluxo.
    |
    |--------------------------------------------------------------------------
    */

    if (
        is_array(
            $perfilAprendizAtual
        )
        &&
        !empty(
            $perfilAprendizAtual
        )
    ) {

        $resultadoSalvar =
            $perfilController->atualizarAprendiz(
                $usuarioId,
                $experiencia,
                $objetivoBanco
            );

    }
    else {

        $resultadoSalvar =
            $perfilController->salvarAprendiz(
                $usuarioId,
                $experiencia,
                $objetivoBanco
            );

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICA PERFIL ESPECÍFICO
    |--------------------------------------------------------------------------
    */

    if (
        !is_array(
            $resultadoSalvar
        )
        ||
        ($resultadoSalvar["status"] ?? false)
        !== true
    ) {

        $mensagemController =
            is_array(
                $resultadoSalvar
            )
            &&
            is_string(
                $resultadoSalvar["mensagem"]
                ??
                null
            )
                ?
                $resultadoSalvar["mensagem"]
                :
                "Falha ao salvar perfil Aprendiz.";


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
                "Falha ao finalizar perfil Aprendiz.";


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
                "[SalvarAprendiz] Falha no rollback do usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erroRollback->getMessage()
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LOG
    |--------------------------------------------------------------------------
    */

    error_log(
        "[SalvarAprendiz] Erro ao configurar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    /*
    |--------------------------------------------------------------------------
    | ERRO
    |--------------------------------------------------------------------------
    */

    mensagem(
        "erro",
        "erro_banco"
    );


    $redirecionar(
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| INVALIDA TOKEN CSRF UTILIZADO
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["csrf"]["aprendiz_questionario"]
);


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
| PAINEL
|--------------------------------------------------------------------------
*/

$redirecionar(
    "aprendiz.painel"
);