<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| ATUALIZAR PERFIL DO APRENDIZ
|
| Endpoint exclusivo para editar os dados específicos
| do perfil Aprendiz:
|
| - experiência com programação;
| - objetivo de aprendizagem.
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
| NORMALIZA TEXTO LONGO
|--------------------------------------------------------------------------
*/

$normalizarTexto =
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
| Permitimos:
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
| Admin é nível de acesso da conta.
|
| Este endpoint é exclusivo de perfis pedagógicos.
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
        "[AtualizarAprendiz] Conexão PDO indisponível."
    );


    mensagem(
        "erro",
        "erro_banco"
    );


    $redirecionar(
        "aprendiz.editar"
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
        "aprendiz.editar"
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
        "aprendiz.editar"
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
        "aprendiz.editar"
    );

}


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

$csrfSessao =
    $_SESSION["csrf"]["aprendiz_editar"]
    ??
    null;


$csrfFormulario =
    $_POST["csrf_token"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| VALIDA TIPOS
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
        $_SESSION["csrf"]["aprendiz_editar"]
    );


    mensagem(
        "erro",
        "csrf_expirado"
    );


    $redirecionar(
        "aprendiz.editar"
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
        $_SESSION["csrf"]["aprendiz_editar"]
    );


    mensagem(
        "erro",
        "csrf_expirado"
    );


    $redirecionar(
        "aprendiz.editar"
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
| VALIDA TIPOS DOS CAMPOS
|--------------------------------------------------------------------------
|
| Impede entradas como:
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
        "aprendiz.editar"
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
        "aprendiz.editar"
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
    $normalizarTexto(
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
        "aprendiz.editar"
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
        "aprendiz.editar"
    );

}


/*
|--------------------------------------------------------------------------
| CARACTERES DE CONTROLE
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
        "aprendiz.editar"
    );

}


/*
|--------------------------------------------------------------------------
| OBJETIVO PARA O BANCO
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
        "[AtualizarAprendiz] Erro ao carregar perfil do usuário "
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
        "aprendiz.editar"
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
| PERFIL ESPECÍFICO
|--------------------------------------------------------------------------
*/

$perfilAprendiz =
    $resultadoPerfil["perfil"]["aprendiz"]
    ??
    null;


if (
    !is_array(
        $perfilAprendiz
    )
    ||
    empty(
        $perfilAprendiz
    )
) {

    mensagem(
        "aviso",
        "perfil_incompleto"
    );


    $redirecionar(
        "aprendiz.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| DADOS ATUAIS
|--------------------------------------------------------------------------
*/

$experienciaAtual =
    is_string(
        $perfilAprendiz["experiencia"]
        ??
        null
    )
        ?
        strtolower(
            trim(
                $perfilAprendiz["experiencia"]
            )
        )
        :
        "";


$objetivoAtualRecebido =
    is_string(
        $perfilAprendiz["objetivo"]
        ??
        null
    )
        ?
        $perfilAprendiz["objetivo"]
        :
        "";


$objetivoAtual =
    $normalizarTexto(
        $objetivoAtualRecebido
    );


/*
|--------------------------------------------------------------------------
| VERIFICA ALTERAÇÕES
|--------------------------------------------------------------------------
*/

$houveAlteracao =
    (
        $experiencia !==
        $experienciaAtual
    )
    ||
    (
        $objetivo !==
        $objetivoAtual
    );


/*
|--------------------------------------------------------------------------
| NENHUMA ALTERAÇÃO
|--------------------------------------------------------------------------
*/

if (
    !$houveAlteracao
) {

    unset(
        $_SESSION["csrf"]["aprendiz_editar"]
    );


    mensagem(
        "sucesso",
        "perfil_salvo"
    );


    $redirecionar(
        "aprendiz.perfil"
    );

}


/*
|--------------------------------------------------------------------------
| ATUALIZA PERFIL
|--------------------------------------------------------------------------
|
| Utilizamos exclusivamente atualizarAprendiz().
|
| Este endpoint não cria perfil específico.
|
|--------------------------------------------------------------------------
*/

try {

    $resultadoAtualizacao =
        $perfilController->atualizarAprendiz(
            $usuarioId,
            $experiencia,
            $objetivoBanco
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[AtualizarAprendiz] Erro ao atualizar perfil do usuário "
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
        "aprendiz.editar"
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA RESULTADO
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $resultadoAtualizacao
    )
    ||
    ($resultadoAtualizacao["status"] ?? false)
    !== true
) {

    $mensagemController =
        is_array(
            $resultadoAtualizacao
        )
        &&
        is_string(
            $resultadoAtualizacao["mensagem"]
            ??
            null
        )
            ?
            $resultadoAtualizacao["mensagem"]
            :
            "Resposta inválida ao atualizar perfil Aprendiz.";


    error_log(
        "[AtualizarAprendiz] Falha ao atualizar usuário "
        .
        $usuarioId
        .
        ": "
        .
        $mensagemController
    );


    mensagem(
        "erro",
        "erro_banco"
    );


    $redirecionar(
        "aprendiz.editar"
    );

}


/*
|--------------------------------------------------------------------------
| INVALIDA TOKEN UTILIZADO
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["csrf"]["aprendiz_editar"]
);


/*
|--------------------------------------------------------------------------
| SUCESSO
|--------------------------------------------------------------------------
*/

mensagem(
    "sucesso",
    "perfil_salvo"
);


/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

$redirecionar(
    "aprendiz.perfil"
);