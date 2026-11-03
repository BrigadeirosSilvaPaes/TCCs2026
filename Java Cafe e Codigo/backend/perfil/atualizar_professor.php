<?php

/*
|--------------------------------------------------------------------------
| ATUALIZAR PERFIL PROFISSIONAL DO PROFESSOR
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Responsável por atualizar:
|
| - instituição de ensino;
| - disciplina ou área de atuação.
|
| Regras:
|
| - somente usuário autenticado;
| - somente perfil Professor;
| - somente POST;
| - CSRF obrigatório;
| - perfil profissional precisa existir;
| - se os dados profissionais forem alterados,
|   a aprovação administrativa é removida;
| - os recursos gerais continuam disponíveis;
| - apenas as Salas de Aula dependem da nova aprovação.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/app.php";
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

function atualizarProfessorRedirecionar(
    string $nomeRota,
    int $codigo = 303
): never  {

    header(
        "Location: " .
        rota(
            $nomeRota
        ),
        true,
        $codigo
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| NORMALIZAR TEXTO
|--------------------------------------------------------------------------
*/

function atualizarProfessorNormalizarTexto(
    mixed $valor
): string {

    $texto =
        (string) $valor;


    $texto =
        str_replace(
            [
                "\r\n",
                "\r",
                "\0"
            ],
            [
                "\n",
                "\n",
                ""
            ],
            $texto
        );


    return trim(
        $texto
    );

}


/*
|--------------------------------------------------------------------------
| COMPRIMENTO
|--------------------------------------------------------------------------
*/

function atualizarProfessorComprimento(
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

}


/*
|--------------------------------------------------------------------------
| UTF-8
|--------------------------------------------------------------------------
*/

function atualizarProfessorUtf8Valido(
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
    )
    === 1;

}


/*
|--------------------------------------------------------------------------
| CARACTERES DE CONTROLE
|--------------------------------------------------------------------------
*/

function atualizarProfessorPossuiControleInvalido(
    string $texto
): bool {

    return preg_match(
        "/[\x01-\x08\x0B\x0C\x0E-\x1F\x7F]/",
        $texto
    )
    === 1;

}


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
    $metodo !==
    "POST"
) {

    header(
        "Allow: POST"
    );


    atualizarProfessorRedirecionar(
        "professor.perfil"
    );

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


    $_SESSION["erro"] =
        "Sua sessão expirou. Entre novamente na plataforma.";


    atualizarProfessorRedirecionar(
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


    $_SESSION["erro"] =
        "Não foi possível identificar sua conta.";


    atualizarProfessorRedirecionar(
        "login"
    );

}


$usuarioId =
    (int) $usuarioId;


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

    $_SESSION["erro"] =
        "Os dados enviados ultrapassaram o tamanho permitido.";


    atualizarProfessorRedirecionar(
        "professor.editar"
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

    $_SESSION["erro"] =
        "Formato de envio inválido.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


/*
|--------------------------------------------------------------------------
| ORIGEM DA SOLICITAÇÃO
|--------------------------------------------------------------------------
|
| Defesa adicional.
|
| O CSRF continua sendo a proteção principal.
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

    $_SESSION["erro"] =
        "Não foi possível validar a origem da solicitação.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
|
| Token criado em:
|
| frontend/pages/perfil/professor/editar.php
|
|--------------------------------------------------------------------------
*/

$csrfSessao =
    $_SESSION["csrf"]["professor_editar"]
    ??
    null;


$csrfFormulario =
    trim(
        (string) (
            $_POST["csrf_token"]
            ??
            ""
        )
    );


if (
    !is_string(
        $csrfSessao
    )
    ||
    preg_match(
        "/^[a-f0-9]{64}$/",
        $csrfSessao
    )
    !== 1
    ||
    preg_match(
        "/^[a-f0-9]{64}$/",
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
        $_SESSION["csrf"]["professor_editar"]
    );


    $_SESSION["erro"] =
        "Sua sessão de segurança expirou. Atualize a página e tente novamente.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


/*
|--------------------------------------------------------------------------
| DADOS
|--------------------------------------------------------------------------
*/

$instituicao =
    atualizarProfessorNormalizarTexto(
        $_POST["instituicao"]
        ??
        ""
    );


$disciplina =
    atualizarProfessorNormalizarTexto(
        $_POST["disciplina"]
        ??
        ""
    );


/*
|--------------------------------------------------------------------------
| INSTITUIÇÃO
|--------------------------------------------------------------------------
*/

if (
    $instituicao === ""
) {

    $_SESSION["erro"] =
        "Informe sua instituição de ensino.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


$tamanhoInstituicao =
    atualizarProfessorComprimento(
        $instituicao
    );


if (
    $tamanhoInstituicao < 2
) {

    $_SESSION["erro"] =
        "Informe uma instituição de ensino válida.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


if (
    $tamanhoInstituicao > 150
) {

    $_SESSION["erro"] =
        "O nome da instituição deve possuir no máximo 150 caracteres.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


if (
    !atualizarProfessorUtf8Valido(
        $instituicao
    )
    ||
    atualizarProfessorPossuiControleInvalido(
        $instituicao
    )
) {

    $_SESSION["erro"] =
        "A instituição contém caracteres inválidos.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


/*
|--------------------------------------------------------------------------
| DISCIPLINA
|--------------------------------------------------------------------------
*/

if (
    $disciplina === ""
) {

    $_SESSION["erro"] =
        "Informe sua disciplina ou área de atuação.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


$tamanhoDisciplina =
    atualizarProfessorComprimento(
        $disciplina
    );


if (
    $tamanhoDisciplina < 2
) {

    $_SESSION["erro"] =
        "Informe uma disciplina ou área de atuação válida.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


if (
    $tamanhoDisciplina > 100
) {

    $_SESSION["erro"] =
        "A disciplina ou área de atuação deve possuir no máximo 100 caracteres.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


if (
    !atualizarProfessorUtf8Valido(
        $disciplina
    )
    ||
    atualizarProfessorPossuiControleInvalido(
        $disciplina
    )
) {

    $_SESSION["erro"] =
        "A disciplina ou área de atuação contém caracteres inválidos.";


    atualizarProfessorRedirecionar(
        "professor.editar"
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
        "[AtualizarProfessor] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    atualizarProfessorRedirecionar(
        "professor.perfil"
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
    empty(
        $resultadoPerfil["status"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]["usuario"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]["usuario"]
    )
) {

    $_SESSION["erro"] =
        "Perfil do usuário não encontrado.";


    atualizarProfessorRedirecionar(
        "perfil.escolher"
    );

}


/*
|--------------------------------------------------------------------------
| GARANTE PERFIL PROFESSOR
|--------------------------------------------------------------------------
*/

$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


$tipoUsuario =
    strtolower(
        trim(
            (string) (
                $perfilUsuario["tipo_usuario"]
                ??
                ""
            )
        )
    );


$roleProfessor =
    defined(
        "ROLE_PROFESSOR"
    )
        ?
        strtolower(
            trim(
                (string) ROLE_PROFESSOR
            )
        )
        :
        "professor";


if (
    $tipoUsuario !==
    $roleProfessor
) {

    $_SESSION["erro"] =
        "Esta área é exclusiva para o perfil Professor.";


    atualizarProfessorRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL PROFISSIONAL
|--------------------------------------------------------------------------
*/

$perfilProfessorAtual =
    $resultadoPerfil["perfil"]["professor"]
    ??
    null;


if (
    !is_array(
        $perfilProfessorAtual
    )
    ||
    empty(
        $perfilProfessorAtual
    )
) {

    $_SESSION["erro"] =
        "Não foi possível localizar seu perfil de professor.";


    atualizarProfessorRedirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| DADOS ATUAIS
|--------------------------------------------------------------------------
*/

$instituicaoAtual =
    atualizarProfessorNormalizarTexto(
        $perfilProfessorAtual["instituicao"]
        ??
        ""
    );


$disciplinaAtual =
    atualizarProfessorNormalizarTexto(
        $perfilProfessorAtual["disciplina"]
        ??
        ""
    );


$professorEstavaAprovado =
    filter_var(
        $perfilProfessorAtual["aprovado"]
        ??
        false,
        FILTER_VALIDATE_BOOLEAN
    );


/*
|--------------------------------------------------------------------------
| VERIFICA ALTERAÇÕES
|--------------------------------------------------------------------------
*/

$instituicaoAlterada =
    $instituicao !==
    $instituicaoAtual;


$disciplinaAlterada =
    $disciplina !==
    $disciplinaAtual;


$dadosProfissionaisAlterados =
    $instituicaoAlterada
    ||
    $disciplinaAlterada;


/*
|--------------------------------------------------------------------------
| NENHUMA ALTERAÇÃO
|--------------------------------------------------------------------------
*/

if (
    !$dadosProfissionaisAlterados
) {

    unset(
        $_SESSION["csrf"]["professor_editar"]
    );


    $_SESSION["sucesso"] =
        "Nenhuma alteração profissional foi necessária.";


    atualizarProfessorRedirecionar(
        "professor.perfil"
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
        "[AtualizarProfessor] Conexão PDO indisponível."
    );


    $_SESSION["erro"] =
        "Não foi possível atualizar suas informações profissionais.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


/*
|--------------------------------------------------------------------------
| TRANSAÇÃO
|--------------------------------------------------------------------------
|
| Alteração dos dados e remoção da aprovação devem fazer parte
| da mesma operação.
|
|--------------------------------------------------------------------------
*/

$transacaoIniciada =
    false;


try {

    /*
    |--------------------------------------------------------------------------
    | EVITA TRANSAÇÃO INESPERADA
    |--------------------------------------------------------------------------
    */

    if (
        $pdo->inTransaction()
    ) {

        throw new RuntimeException(
            "Já existe uma transação ativa antes da atualização do professor."
        );

    }


    $pdo->beginTransaction();


    $transacaoIniciada =
        true;


    /*
    |--------------------------------------------------------------------------
    | ATUALIZA DADOS PROFISSIONAIS
    |--------------------------------------------------------------------------
    */

    $resultadoAtualizacao =
        $perfilController->atualizarProfessor(

            $usuarioId,

            $instituicao,

            $disciplina

        );


    if (
        !is_array(
            $resultadoAtualizacao
        )
        ||
        empty(
            $resultadoAtualizacao["status"]
        )
    ) {

        throw new RuntimeException(
            is_array(
                $resultadoAtualizacao
            )
            &&
            isset(
                $resultadoAtualizacao["mensagem"]
            )
                ?
                (string) $resultadoAtualizacao["mensagem"]
                :
                "Não foi possível atualizar o perfil de professor."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE APROVAÇÃO
    |--------------------------------------------------------------------------
    |
    | Sempre que os dados profissionais mudarem, o estado final
    | deve ser "não aprovado".
    |
    | Mesmo que o cadastro já estivesse em análise, manteremos
    | explicitamente a aprovação removida.
    |
    |--------------------------------------------------------------------------
    */

    $resultadoAprovacao =
        $perfilController->removerAprovacaoProfessor(
            $usuarioId
        );


    if (
        !is_array(
            $resultadoAprovacao
        )
        ||
        empty(
            $resultadoAprovacao["status"]
        )
    ) {

        throw new RuntimeException(
            is_array(
                $resultadoAprovacao
            )
            &&
            isset(
                $resultadoAprovacao["mensagem"]
            )
                ?
                (string) $resultadoAprovacao["mensagem"]
                :
                "Não foi possível atualizar a situação da aprovação."
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
                "[AtualizarProfessor] Erro no rollback do usuário "
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
        "[AtualizarProfessor] Usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    /*
    |--------------------------------------------------------------------------
    | MENSAGEM
    |--------------------------------------------------------------------------
    */

    $_SESSION["erro"] =
        "Não foi possível atualizar suas informações profissionais.";


    atualizarProfessorRedirecionar(
        "professor.editar"
    );

}


/*
|--------------------------------------------------------------------------
| INVALIDA CSRF UTILIZADO
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["csrf"]["professor_editar"]
);


/*
|--------------------------------------------------------------------------
| SUCESSO
|--------------------------------------------------------------------------
*/

if (
    $professorEstavaAprovado
) {

    $_SESSION["sucesso"] =
        "Informações profissionais atualizadas. Como seus dados foram alterados, seu perfil voltou para análise administrativa.";

}
else {

    $_SESSION["sucesso"] =
        "Informações profissionais atualizadas com sucesso.";

}


/*
|--------------------------------------------------------------------------
| RETORNA AO PERFIL
|--------------------------------------------------------------------------
*/

atualizarProfessorRedirecionar(
    "professor.perfil"
);