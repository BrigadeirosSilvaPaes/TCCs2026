<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| ATUALIZAÇÃO DO PERFIL PÚBLICO
|
| Endpoint compartilhado por:
|
| - Estudante;
| - Professor;
| - Aprendiz.
|
| Atualiza:
|
| - nome;
| - nick de programador;
| - biografia;
| - Instagram;
| - e-mail de contato.
|
| O e-mail utilizado para login NÃO é alterado aqui.
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

function perfilPublicoRedirecionar(
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

}


/*
|--------------------------------------------------------------------------
| COMPRIMENTO
|--------------------------------------------------------------------------
*/

function perfilPublicoComprimento(
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

function perfilPublicoUtf8Valido(
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

}


/*
|--------------------------------------------------------------------------
| NORMALIZA QUEBRAS DE LINHA
|--------------------------------------------------------------------------
*/

function perfilPublicoNormalizarTexto(
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

}


/*
|--------------------------------------------------------------------------
| CARACTERES DE CONTROLE
|--------------------------------------------------------------------------
*/

function perfilPublicoPossuiControleInvalido(
    string $texto,
    bool $permitirQuebras = false
): bool {

    if (
        $permitirQuebras
    ) {

        /*
        |--------------------------------------------------------------------------
        | Permitimos TAB e quebra de linha.
        |--------------------------------------------------------------------------
        */

        return preg_match(
            "/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/",
            $texto
        ) === 1;

    }


    return preg_match(
        "/[\x00-\x1F\x7F]/",
        $texto
    ) === 1;

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


    perfilPublicoRedirecionar(
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


    perfilPublicoRedirecionar(
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
| Administração é nível de acesso da conta.
|
| Este endpoint existe exclusivamente para perfis pedagógicos.
|
|--------------------------------------------------------------------------
*/

$tipoAcessoSessao =
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
    $tipoAcessoSessao === ACCESS_ADMIN
) {

    mensagem(
        "erro",
        "sem_permissao"
    );


    perfilPublicoRedirecionar(
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
        "[PerfilPublico] Conexão PDO indisponível."
    );


    mensagem(
        "erro",
        "erro_banco"
    );


    perfilPublicoRedirecionar(
        "painel"
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


    perfilPublicoRedirecionar(
        "painel"
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


    perfilPublicoRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| BUSCA PERFIL ATUAL
|--------------------------------------------------------------------------
*/

try {

    $stmtPerfil =
        $pdo->prepare(
            "

            SELECT

                pu.id,
                pu.tipo_usuario,
                pu.nick_programador,
                pu.biografia,
                pu.instagram,
                pu.email_contato,

                u.nome,
                u.tipo_acesso

            FROM perfil_usuario pu

            INNER JOIN usuarios u
                ON u.id = pu.usuario_id

            WHERE
                pu.usuario_id = :usuario_id

            LIMIT 1

            "
        );


    $stmtPerfil->execute(
        [
            ":usuario_id" =>
                $usuarioId
        ]
    );


    $perfilAtual =
        $stmtPerfil->fetch(
            PDO::FETCH_ASSOC
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[PerfilPublico] Erro ao localizar perfil do usuário "
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


    perfilPublicoRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL EXISTE
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $perfilAtual
    )
) {

    mensagem(
        "aviso",
        "perfil_incompleto"
    );


    perfilPublicoRedirecionar(
        "perfil.escolher"
    );

}


/*
|--------------------------------------------------------------------------
| CONFIRMA TIPO DE ACESSO NO BANCO
|--------------------------------------------------------------------------
*/

$tipoAcessoBanco =
    is_string(
        $perfilAtual["tipo_acesso"]
        ??
        null
    )
        ?
        trim(
            $perfilAtual["tipo_acesso"]
        )
        :
        "";


if (
    $tipoAcessoBanco === ACCESS_ADMIN
) {

    mensagem(
        "erro",
        "sem_permissao"
    );


    perfilPublicoRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| TIPO DE PERFIL
|--------------------------------------------------------------------------
*/

$tipoUsuario =
    is_string(
        $perfilAtual["tipo_usuario"]
        ??
        null
    )
        ?
        strtolower(
            trim(
                $perfilAtual["tipo_usuario"]
            )
        )
        :
        "";


/*
|--------------------------------------------------------------------------
| ROTAS POR PERFIL
|--------------------------------------------------------------------------
*/

$rotasPerfil = [

    PROFILE_ESTUDANTE => [

        "perfil" =>
            "estudante.perfil",

        "editar" =>
            "estudante.editar"

    ],

    PROFILE_PROFESSOR => [

        "perfil" =>
            "professor.perfil",

        "editar" =>
            "professor.editar"

    ],

    PROFILE_APRENDIZ => [

        "perfil" =>
            "aprendiz.perfil",

        "editar" =>
            "aprendiz.editar"

    ]

];


/*
|--------------------------------------------------------------------------
| PERFIL SUPORTADO
|--------------------------------------------------------------------------
*/

if (
    !array_key_exists(
        $tipoUsuario,
        $rotasPerfil
    )
) {

    error_log(
        "[PerfilPublico] Tipo de perfil inválido para usuário "
        .
        $usuarioId
        .
        ": "
        .
        $tipoUsuario
    );


    mensagem(
        "erro",
        "sem_permissao"
    );


    perfilPublicoRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| DESTINOS
|--------------------------------------------------------------------------
*/

$rotaPerfil =
    $rotasPerfil[$tipoUsuario]["perfil"];


$rotaEditar =
    $rotasPerfil[$tipoUsuario]["editar"];


/*
|--------------------------------------------------------------------------
| ORIGEM DA SOLICITAÇÃO
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


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


/*
|--------------------------------------------------------------------------
| CSRF POR PERFIL
|--------------------------------------------------------------------------
|
| Cada formulário possui seu próprio namespace.
|
|--------------------------------------------------------------------------
*/

$csrfNamespaces = [

    PROFILE_ESTUDANTE =>
        "estudante_editar",

    PROFILE_PROFESSOR =>
        "professor_editar",

    PROFILE_APRENDIZ =>
        "aprendiz_editar"

];


$csrfNamespace =
    $csrfNamespaces[$tipoUsuario]
    ??
    null;


/*
|--------------------------------------------------------------------------
| NAMESPACE DEVE EXISTIR
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $csrfNamespace
    )
    ||
    $csrfNamespace === ""
) {

    mensagem(
        "erro",
        "sem_permissao"
    );


    perfilPublicoRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| TOKEN CSRF
|--------------------------------------------------------------------------
*/

$csrfSessao =
    $_SESSION["csrf"][$csrfNamespace]
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
        $_SESSION["csrf"][$csrfNamespace]
    );


    mensagem(
        "erro",
        "csrf_expirado"
    );


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


$csrfFormulario =
    trim(
        $csrfFormulario
    );


/*
|--------------------------------------------------------------------------
| VALIDA FORMATO E VALOR
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
        $_SESSION["csrf"][$csrfNamespace]
    );


    mensagem(
        "erro",
        "csrf_expirado"
    );


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


/*
|--------------------------------------------------------------------------
| RECEBE CAMPOS
|--------------------------------------------------------------------------
*/

$nomeRecebido =
    $_POST["nome"]
    ??
    null;


$nickRecebido =
    $_POST["nick_programador"]
    ??
    "";


$biografiaRecebida =
    $_POST["biografia"]
    ??
    "";


$instagramRecebido =
    $_POST["instagram"]
    ??
    "";


$emailContatoRecebido =
    $_POST["email_contato"]
    ??
    "";


/*
|--------------------------------------------------------------------------
| VALIDA TIPOS
|--------------------------------------------------------------------------
|
| Impede entradas como:
|
| nome[]=teste
| instagram[]=teste
|
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $nomeRecebido
    )
    ||
    !is_string(
        $nickRecebido
    )
    ||
    !is_string(
        $biografiaRecebida
    )
    ||
    !is_string(
        $instagramRecebido
    )
    ||
    !is_string(
        $emailContatoRecebido
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


/*
|--------------------------------------------------------------------------
| NORMALIZA CAMPOS
|--------------------------------------------------------------------------
*/

$nome =
    perfilPublicoNormalizarTexto(
        $nomeRecebido
    );


$nick =
    perfilPublicoNormalizarTexto(
        $nickRecebido
    );


$biografia =
    perfilPublicoNormalizarTexto(
        $biografiaRecebida
    );


$instagram =
    perfilPublicoNormalizarTexto(
        $instagramRecebido
    );


$emailContato =
    perfilPublicoNormalizarTexto(
        $emailContatoRecebido
    );


/*
|--------------------------------------------------------------------------
| UTF-8
|--------------------------------------------------------------------------
*/

if (
    !perfilPublicoUtf8Valido(
        $nome
    )
    ||
    !perfilPublicoUtf8Valido(
        $nick
    )
    ||
    !perfilPublicoUtf8Valido(
        $biografia
    )
    ||
    !perfilPublicoUtf8Valido(
        $instagram
    )
    ||
    !perfilPublicoUtf8Valido(
        $emailContato
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


/*
|--------------------------------------------------------------------------
| NOME
|--------------------------------------------------------------------------
*/

$tamanhoNome =
    perfilPublicoComprimento(
        $nome
    );


if (
    $tamanhoNome < 3
    ||
    $tamanhoNome > 100
    ||
    perfilPublicoPossuiControleInvalido(
        $nome
    )
) {

    mensagem(
        "erro",
        "nome_invalido"
    );


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


/*
|--------------------------------------------------------------------------
| NORMALIZA ESPAÇOS DO NOME
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


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


$nome =
    trim(
        $nomeNormalizado
    );


/*
|--------------------------------------------------------------------------
| NICK
|--------------------------------------------------------------------------
*/

if (
    perfilPublicoComprimento(
        $nick
    )
    > 50
) {

    mensagem(
        "erro",
        "generico"
    );


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


if (
    $nick !== ""
    &&
    perfilPublicoPossuiControleInvalido(
        $nick
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


/*
|--------------------------------------------------------------------------
| REMOVE @ DO NICK
|--------------------------------------------------------------------------
*/

if (
    $nick !== ""
) {

    $nick =
        ltrim(
            $nick,
            "@"
        );


    $nick =
        trim(
            $nick
        );


    if (
        $nick === ""
    ) {

        mensagem(
            "erro",
            "generico"
        );


        perfilPublicoRedirecionar(
            $rotaEditar
        );

    }

}


/*
|--------------------------------------------------------------------------
| BIOGRAFIA
|--------------------------------------------------------------------------
*/

if (
    perfilPublicoComprimento(
        $biografia
    )
    > 300
) {

    mensagem(
        "erro",
        "generico"
    );


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


if (
    $biografia !== ""
    &&
    perfilPublicoPossuiControleInvalido(
        $biografia,
        true
    )
) {

    mensagem(
        "erro",
        "generico"
    );


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


/*
|--------------------------------------------------------------------------
| INSTAGRAM
|--------------------------------------------------------------------------
*/

if (
    $instagram !== ""
) {

    $instagram =
        ltrim(
            $instagram,
            "@"
        );


    if (
        preg_match(
            "/^[A-Za-z0-9._]{1,30}$/D",
            $instagram
        )
        !== 1
    ) {

        mensagem(
            "erro",
            "generico"
        );


        perfilPublicoRedirecionar(
            $rotaEditar
        );

    }


    $instagram =
        "@"
        .
        $instagram;

}


/*
|--------------------------------------------------------------------------
| E-MAIL DE CONTATO
|--------------------------------------------------------------------------
*/

if (
    $emailContato !== ""
) {

    if (
        strlen(
            $emailContato
        )
        > 150
        ||
        perfilPublicoPossuiControleInvalido(
            $emailContato
        )
        ||
        filter_var(
            $emailContato,
            FILTER_VALIDATE_EMAIL
        )
        === false
    ) {

        mensagem(
            "erro",
            "email_invalido"
        );


        perfilPublicoRedirecionar(
            $rotaEditar
        );

    }


    if (
        function_exists(
            "mb_strtolower"
        )
    ) {

        $emailContato =
            mb_strtolower(
                $emailContato,
                "UTF-8"
            );

    }
    else {

        $emailContato =
            strtolower(
                $emailContato
            );

    }

}


/*
|--------------------------------------------------------------------------
| CAMPOS OPCIONAIS PARA O BANCO
|--------------------------------------------------------------------------
*/

$nickBanco =
    $nick !== ""
        ?
        $nick
        :
        null;


$biografiaBanco =
    $biografia !== ""
        ?
        $biografia
        :
        null;


$instagramBanco =
    $instagram !== ""
        ?
        $instagram
        :
        null;


$emailContatoBanco =
    $emailContato !== ""
        ?
        $emailContato
        :
        null;


/*
|--------------------------------------------------------------------------
| NORMALIZA DADOS ATUAIS
|--------------------------------------------------------------------------
*/

$nomeAtual =
    is_string(
        $perfilAtual["nome"]
        ??
        null
    )
        ?
        perfilPublicoNormalizarTexto(
            $perfilAtual["nome"]
        )
        :
        "";


$nomeAtualNormalizado =
    preg_replace(
        "/\s+/u",
        " ",
        $nomeAtual
    );


if (
    is_string(
        $nomeAtualNormalizado
    )
) {

    $nomeAtual =
        trim(
            $nomeAtualNormalizado
        );

}


$nickAtual =
    is_string(
        $perfilAtual["nick_programador"]
        ??
        null
    )
        ?
        perfilPublicoNormalizarTexto(
            $perfilAtual["nick_programador"]
        )
        :
        "";


if (
    $nickAtual !== ""
) {

    $nickAtual =
        ltrim(
            $nickAtual,
            "@"
        );

}


$biografiaAtual =
    is_string(
        $perfilAtual["biografia"]
        ??
        null
    )
        ?
        perfilPublicoNormalizarTexto(
            $perfilAtual["biografia"]
        )
        :
        "";


$instagramAtual =
    is_string(
        $perfilAtual["instagram"]
        ??
        null
    )
        ?
        perfilPublicoNormalizarTexto(
            $perfilAtual["instagram"]
        )
        :
        "";


if (
    $instagramAtual !== ""
) {

    $instagramAtual =
        "@"
        .
        ltrim(
            $instagramAtual,
            "@"
        );

}


$emailContatoAtual =
    is_string(
        $perfilAtual["email_contato"]
        ??
        null
    )
        ?
        perfilPublicoNormalizarTexto(
            $perfilAtual["email_contato"]
        )
        :
        "";


if (
    $emailContatoAtual !== ""
) {

    if (
        function_exists(
            "mb_strtolower"
        )
    ) {

        $emailContatoAtual =
            mb_strtolower(
                $emailContatoAtual,
                "UTF-8"
            );

    }
    else {

        $emailContatoAtual =
            strtolower(
                $emailContatoAtual
            );

    }

}


/*
|--------------------------------------------------------------------------
| VERIFICA SE HOUVE ALTERAÇÃO
|--------------------------------------------------------------------------
*/

$houveAlteracao =
    (
        $nome !==
        $nomeAtual
    )
    ||
    (
        $nick !==
        $nickAtual
    )
    ||
    (
        $biografia !==
        $biografiaAtual
    )
    ||
    (
        (
            $instagramBanco
            ??
            ""
        )
        !==
        $instagramAtual
    )
    ||
    (
        (
            $emailContatoBanco
            ??
            ""
        )
        !==
        $emailContatoAtual
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
        $_SESSION["csrf"][$csrfNamespace]
    );


    mensagem(
        "sucesso",
        "perfil_salvo"
    );


    perfilPublicoRedirecionar(
        $rotaPerfil
    );

}


/*
|--------------------------------------------------------------------------
| TRANSAÇÃO
|--------------------------------------------------------------------------
*/

$transacaoIniciada =
    false;


try {

    if (
        $pdo->inTransaction()
    ) {

        throw new RuntimeException(
            "Já existe uma transação ativa antes da atualização do perfil público."
        );

    }


    $pdo->beginTransaction();


    $transacaoIniciada =
        true;


    /*
    |--------------------------------------------------------------------------
    | NOME DA CONTA
    |--------------------------------------------------------------------------
    */

    $stmtUsuario =
        $pdo->prepare(
            "

            UPDATE usuarios

            SET
                nome = :nome

            WHERE
                id = :usuario_id

            LIMIT 1

            "
        );


    $stmtUsuario->bindValue(
        ":nome",
        $nome,
        PDO::PARAM_STR
    );


    $stmtUsuario->bindValue(
        ":usuario_id",
        $usuarioId,
        PDO::PARAM_INT
    );


    if (
        $stmtUsuario->execute()
        !== true
    ) {

        throw new RuntimeException(
            "Falha ao atualizar o nome da conta."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PERFIL PÚBLICO
    |--------------------------------------------------------------------------
    */

    $stmtPerfilPublico =
        $pdo->prepare(
            "

            UPDATE perfil_usuario

            SET

                nick_programador = :nick_programador,
                biografia = :biografia,
                instagram = :instagram,
                email_contato = :email_contato

            WHERE
                usuario_id = :usuario_id

            LIMIT 1

            "
        );


    $stmtPerfilPublico->bindValue(
        ":nick_programador",
        $nickBanco,
        $nickBanco === null
            ?
            PDO::PARAM_NULL
            :
            PDO::PARAM_STR
    );


    $stmtPerfilPublico->bindValue(
        ":biografia",
        $biografiaBanco,
        $biografiaBanco === null
            ?
            PDO::PARAM_NULL
            :
            PDO::PARAM_STR
    );


    $stmtPerfilPublico->bindValue(
        ":instagram",
        $instagramBanco,
        $instagramBanco === null
            ?
            PDO::PARAM_NULL
            :
            PDO::PARAM_STR
    );


    $stmtPerfilPublico->bindValue(
        ":email_contato",
        $emailContatoBanco,
        $emailContatoBanco === null
            ?
            PDO::PARAM_NULL
            :
            PDO::PARAM_STR
    );


    $stmtPerfilPublico->bindValue(
        ":usuario_id",
        $usuarioId,
        PDO::PARAM_INT
    );


    if (
        $stmtPerfilPublico->execute()
        !== true
    ) {

        throw new RuntimeException(
            "Falha ao atualizar o perfil público."
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
                "[PerfilPublico] Falha no rollback do usuário "
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
        "[PerfilPublico] Erro ao atualizar perfil do usuário "
        .
        $usuarioId
        .
        " ("
        .
        $tipoUsuario
        .
        "): "
        .
        $erro->getMessage()
    );


    mensagem(
        "erro",
        "erro_banco"
    );


    perfilPublicoRedirecionar(
        $rotaEditar
    );

}


/*
|--------------------------------------------------------------------------
| ATUALIZA NOME NA SESSÃO
|--------------------------------------------------------------------------
*/

$_SESSION["usuario"]["nome"] =
    $nome;


/*
|--------------------------------------------------------------------------
| INVALIDA TOKEN CSRF UTILIZADO
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION["csrf"][$csrfNamespace]
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
| REDIRECIONA PARA O PERFIL
|--------------------------------------------------------------------------
*/

perfilPublicoRedirecionar(
    $rotaPerfil
);