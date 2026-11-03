<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . "/../config/helpers.php";


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO DA SESSÃO ADMINISTRATIVA
|--------------------------------------------------------------------------
|
| O token administrativo:
|
| - possui 32 bytes aleatórios;
| - fica em texto original SOMENTE na sessão PHP;
| - no banco fica apenas SHA-256(token);
| - também é vinculado ao session_id() atual;
| - possui prazo de validade.
|
*/

const ADMIN_SESSION_TOKEN_KEY =
    "admin_token";


const ADMIN_SESSION_USER_KEY =
    "admin_usuario_id";


const ADMIN_SESSION_VALIDITY =
    28800;


/*
|--------------------------------------------------------------------------
| GARANTE ESTRUTURA DE SEGURANÇA
|--------------------------------------------------------------------------
*/

function adminSessaoGarantirEstrutura(): void
{

    garantirSessao();


    if (
        !isset($_SESSION["seguranca"]) ||
        !is_array($_SESSION["seguranca"])
    ) {

        $_SESSION["seguranca"] = [];

    }

}


/*
|--------------------------------------------------------------------------
| HASH SHA-256
|--------------------------------------------------------------------------
*/

function adminSessaoHash(
    string $valor
): string
{

    return hash(
        "sha256",
        $valor
    );

}


/*
|--------------------------------------------------------------------------
| HASH DO SESSION ID
|--------------------------------------------------------------------------
|
| Nunca armazenamos session_id() puro na tabela admin_sessoes.
|
*/

function adminSessaoHashSessionId(): string
{

    $sessionId =
        session_id();


    if ($sessionId === "") {

        throw new RuntimeException(
            "Sessão PHP não inicializada."
        );

    }


    return adminSessaoHash(
        $sessionId
    );

}


/*
|--------------------------------------------------------------------------
| TOKEN ADMINISTRATIVO ATUAL
|--------------------------------------------------------------------------
*/

function adminSessaoTokenAtual(): ?string
{

    adminSessaoGarantirEstrutura();


    $token =
        $_SESSION["seguranca"][ADMIN_SESSION_TOKEN_KEY]
        ?? null;


    if (
        !is_string($token) ||
        preg_match(
            "/^[a-f0-9]{64}$/",
            $token
        ) !== 1
    ) {

        return null;

    }


    return $token;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO ADMINISTRATIVO DA SESSÃO
|--------------------------------------------------------------------------
*/

function adminSessaoUsuarioAtual(): int
{

    adminSessaoGarantirEstrutura();


    return
        (int) (
            $_SESSION["seguranca"][ADMIN_SESSION_USER_KEY]
            ?? 0
        );

}


/*
|--------------------------------------------------------------------------
| REMOVE TOKEN APENAS DA SESSÃO PHP
|--------------------------------------------------------------------------
*/

function adminSessaoLimparLocal(): void
{

    adminSessaoGarantirEstrutura();


    unset(
        $_SESSION["seguranca"][ADMIN_SESSION_TOKEN_KEY],
        $_SESSION["seguranca"][ADMIN_SESSION_USER_KEY]
    );

}


/*
|--------------------------------------------------------------------------
| CRIA SESSÃO ADMINISTRATIVA
|--------------------------------------------------------------------------
|
| Esta função deverá ser chamada após um login administrativo válido.
|
| Fluxo:
|
| 1. recebe usuario_id;
| 2. confirma novamente se a conta ainda é admin;
| 3. gera token aleatório;
| 4. calcula hash do token;
| 5. calcula hash do session_id();
| 6. registra a sessão no banco;
| 7. salva o token original somente na sessão PHP.
|
*/

function adminSessaoCriar(
    PDO $pdo,
    int $usuarioId,
    int $validadeSegundos = ADMIN_SESSION_VALIDITY
): bool
{

    adminSessaoGarantirEstrutura();


    /*
    |--------------------------------------------------------------------------
    | VALIDA ID
    |--------------------------------------------------------------------------
    */

    if ($usuarioId <= 0) {

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDADE
    |--------------------------------------------------------------------------
    */

    if (
        $validadeSegundos < 300
    ) {

        $validadeSegundos =
            ADMIN_SESSION_VALIDITY;

    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRMA ADMINISTRADOR
    |--------------------------------------------------------------------------
    |
    | Não criamos um admin_token apenas porque a sessão PHP diz "admin".
    |
    | O banco precisa confirmar novamente.
    |
    */

    try {

        $stmtUsuario =
            $pdo->prepare("

                SELECT

                    id,
                    ativo,
                    bloqueado,
                    tipo_acesso

                FROM usuarios

                WHERE
                    id = :usuario_id

                LIMIT 1

            ");


        $stmtUsuario->execute([

            ":usuario_id" =>
                $usuarioId

        ]);


        $usuario =
            $stmtUsuario->fetch(
                PDO::FETCH_ASSOC
            );

    }
    catch (Throwable $erro) {

        error_log(
            "[AdminSession] Erro ao validar administrador " .
            $usuarioId .
            ": " .
            $erro->getMessage()
        );


        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | CONTA INVÁLIDA
    |--------------------------------------------------------------------------
    */

    if (!$usuario) {

        return false;

    }


    if (
        (int) (
            $usuario["ativo"]
            ?? 0
        ) !== 1
    ) {

        return false;

    }


    if (
        (int) (
            $usuario["bloqueado"]
            ?? 0
        ) === 1
    ) {

        return false;

    }


    if (
        ($usuario["tipo_acesso"] ?? "")
        !== ROLE_ADMIN
    ) {

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | GERA TOKEN
    |--------------------------------------------------------------------------
    */

    try {

        $token =
            bin2hex(
                random_bytes(32)
            );


        $tokenHash =
            adminSessaoHash(
                $token
            );


        $sessionHash =
            adminSessaoHashSessionId();


        $expiraEm =
            date(
                "Y-m-d H:i:s",
                time() + $validadeSegundos
            );

    }
    catch (Throwable $erro) {

        error_log(
            "[AdminSession] Erro ao gerar token administrativo: " .
            $erro->getMessage()
        );


        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRA NO BANCO
    |--------------------------------------------------------------------------
    */

    try {

        $pdo->beginTransaction();


        /*
        |--------------------------------------------------------------------------
        | REVOKA TOKEN ANTIGO DESTA MESMA SESSÃO PHP
        |--------------------------------------------------------------------------
        |
        | Evita acumular várias credenciais administrativas
        | para o mesmo session_id().
        |
        */

        $stmtRevogar =
            $pdo->prepare("

                UPDATE admin_sessoes

                SET
                    revogado_em = NOW()

                WHERE
                    usuario_id = :usuario_id

                AND
                    session_hash = :session_hash

                AND
                    revogado_em IS NULL

            ");


        $stmtRevogar->execute([

            ":usuario_id" =>
                $usuarioId,

            ":session_hash" =>
                $sessionHash

        ]);


        /*
        |--------------------------------------------------------------------------
        | NOVA SESSÃO ADMINISTRATIVA
        |--------------------------------------------------------------------------
        */

        $stmtCriar =
            $pdo->prepare("

                INSERT INTO admin_sessoes
                (

                    usuario_id,

                    token_hash,

                    session_hash,

                    criado_em,

                    ultimo_uso,

                    expira_em,

                    revogado_em

                )

                VALUES
                (

                    :usuario_id,

                    :token_hash,

                    :session_hash,

                    NOW(),

                    NOW(),

                    :expira_em,

                    NULL

                )

            ");


        $stmtCriar->execute([

            ":usuario_id" =>
                $usuarioId,

            ":token_hash" =>
                $tokenHash,

            ":session_hash" =>
                $sessionHash,

            ":expira_em" =>
                $expiraEm

        ]);


        $pdo->commit();

    }
    catch (Throwable $erro) {

        if (
            $pdo->inTransaction()
        ) {

            $pdo->rollBack();

        }


        error_log(
            "[AdminSession] Erro ao criar sessão administrativa para usuário " .
            $usuarioId .
            ": " .
            $erro->getMessage()
        );


        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | SALVA TOKEN ORIGINAL NA SESSÃO PHP
    |--------------------------------------------------------------------------
    |
    | O banco conhece somente o hash.
    |
    */

    $_SESSION["seguranca"][ADMIN_SESSION_TOKEN_KEY] =
        $token;


    $_SESSION["seguranca"][ADMIN_SESSION_USER_KEY] =
        $usuarioId;


    return true;

}


/*
|--------------------------------------------------------------------------
| VALIDA SESSÃO ADMINISTRATIVA
|--------------------------------------------------------------------------
|
| Esta função será chamada dentro de verificar_admin.php.
|
| Ela exige simultaneamente:
|
| - token presente na sessão PHP;
| - usuario_id correto;
| - hash do token existente no banco;
| - mesmo session_id();
| - sessão não revogada;
| - sessão não expirada;
| - usuário ainda ativo;
| - usuário não bloqueado;
| - usuário ainda admin.
|
*/

function adminSessaoValidar(
    PDO $pdo,
    int $usuarioId
): bool
{

    adminSessaoGarantirEstrutura();


    /*
    |--------------------------------------------------------------------------
    | ID INVÁLIDO
    |--------------------------------------------------------------------------
    */

    if ($usuarioId <= 0) {

        adminSessaoLimparLocal();

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | CONFERE USUÁRIO VINCULADO AO TOKEN
    |--------------------------------------------------------------------------
    */

    $usuarioSessao =
        adminSessaoUsuarioAtual();


    if (
        $usuarioSessao <= 0 ||
        $usuarioSessao !== $usuarioId
    ) {

        adminSessaoLimparLocal();

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | TOKEN LOCAL
    |--------------------------------------------------------------------------
    */

    $token =
        adminSessaoTokenAtual();


    if ($token === null) {

        adminSessaoLimparLocal();

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | HASHES
    |--------------------------------------------------------------------------
    */

    try {

        $tokenHash =
            adminSessaoHash(
                $token
            );


        $sessionHash =
            adminSessaoHashSessionId();

    }
    catch (Throwable $erro) {

        error_log(
            "[AdminSession] Falha ao preparar validação da sessão administrativa: " .
            $erro->getMessage()
        );


        adminSessaoLimparLocal();

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | CONSULTA NO BANCO
    |--------------------------------------------------------------------------
    */

    try {

        $stmtSessao =
            $pdo->prepare("

                SELECT

                    ads.id,

                    ads.usuario_id,

                    ads.token_hash,

                    ads.session_hash,

                    ads.criado_em,

                    ads.ultimo_uso,

                    ads.expira_em,

                    ads.revogado_em,

                    u.ativo,

                    u.bloqueado,

                    u.tipo_acesso

                FROM admin_sessoes ads

                INNER JOIN usuarios u
                    ON u.id = ads.usuario_id

                WHERE
                    ads.usuario_id = :usuario_id

                AND
                    ads.token_hash = :token_hash

                AND
                    ads.session_hash = :session_hash

                AND
                    ads.revogado_em IS NULL

                AND
                    ads.expira_em > NOW()

                AND
                    u.ativo = 1

                AND
                    u.bloqueado = 0

                AND
                    u.tipo_acesso = :tipo_admin

                LIMIT 1

            ");


        $stmtSessao->execute([

            ":usuario_id" =>
                $usuarioId,

            ":token_hash" =>
                $tokenHash,

            ":session_hash" =>
                $sessionHash,

            ":tipo_admin" =>
                ROLE_ADMIN

        ]);


        $sessaoAdmin =
            $stmtSessao->fetch(
                PDO::FETCH_ASSOC
            );

    }
    catch (Throwable $erro) {

        error_log(
            "[AdminSession] Erro ao validar sessão administrativa do usuário " .
            $usuarioId .
            ": " .
            $erro->getMessage()
        );


        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | TOKEN INVÁLIDO / REVOGADO / EXPIRADO
    |--------------------------------------------------------------------------
    */

    if (!$sessaoAdmin) {

        adminSessaoLimparLocal();

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZA ÚLTIMO USO
    |--------------------------------------------------------------------------
    |
    | Não usamos este campo para estender automaticamente expira_em.
    |
    | Ou seja:
    |
    | criado às 10h
    | validade = 8h
    |
    | expira às 18h independentemente da atividade.
    |
    */

    try {

        $stmtUso =
            $pdo->prepare("

                UPDATE admin_sessoes

                SET
                    ultimo_uso = NOW()

                WHERE
                    id = :sessao_id

                AND
                    revogado_em IS NULL

            ");


        $stmtUso->execute([

            ":sessao_id" =>
                (int) $sessaoAdmin["id"]

        ]);

    }
    catch (Throwable $erro) {

        /*
        |--------------------------------------------------------------------------
        | NÃO LIBERAMOS INFORMAÇÃO AO USUÁRIO
        |--------------------------------------------------------------------------
        |
        | A autenticação já foi validada.
        | Uma falha somente ao registrar ultimo_uso não precisa
        | derrubar a sessão administrativa.
        |
        */

        error_log(
            "[AdminSession] Não foi possível atualizar ultimo_uso da sessão " .
            (int) $sessaoAdmin["id"] .
            ": " .
            $erro->getMessage()
        );

    }


    return true;

}


/*
|--------------------------------------------------------------------------
| REVOKA A SESSÃO ADMINISTRATIVA ATUAL
|--------------------------------------------------------------------------
|
| Ideal para logout.
|
*/

function adminSessaoRevogar(
    PDO $pdo,
    int $usuarioId
): bool
{

    adminSessaoGarantirEstrutura();


    $token =
        adminSessaoTokenAtual();


    if (
        $usuarioId <= 0 ||
        $token === null
    ) {

        adminSessaoLimparLocal();

        return false;

    }


    try {

        $tokenHash =
            adminSessaoHash(
                $token
            );


        $sessionHash =
            adminSessaoHashSessionId();


        $stmtRevogar =
            $pdo->prepare("

                UPDATE admin_sessoes

                SET
                    revogado_em = NOW()

                WHERE
                    usuario_id = :usuario_id

                AND
                    token_hash = :token_hash

                AND
                    session_hash = :session_hash

                AND
                    revogado_em IS NULL

            ");


        $stmtRevogar->execute([

            ":usuario_id" =>
                $usuarioId,

            ":token_hash" =>
                $tokenHash,

            ":session_hash" =>
                $sessionHash

        ]);


        adminSessaoLimparLocal();


        return true;

    }
    catch (Throwable $erro) {

        error_log(
            "[AdminSession] Erro ao revogar sessão administrativa do usuário " .
            $usuarioId .
            ": " .
            $erro->getMessage()
        );


        /*
        |--------------------------------------------------------------------------
        | REMOVE LOCALMENTE MESMO ASSIM
        |--------------------------------------------------------------------------
        |
        | Se o banco falhar durante logout, removemos a credencial
        | da sessão PHP atual.
        |
        */

        adminSessaoLimparLocal();


        return false;

    }

}


/*
|--------------------------------------------------------------------------
| REVOKA TODAS AS SESSÕES DE UM ADMINISTRADOR
|--------------------------------------------------------------------------
|
| Pode ser usado futuramente para:
|
| - "Sair de todos os dispositivos";
| - remoção de privilégios;
| - suspeita de comprometimento;
| - redefinição administrativa.
|
*/

function adminSessaoRevogarTodas(
    PDO $pdo,
    int $usuarioId
): bool
{

    if ($usuarioId <= 0) {

        return false;

    }


    try {

        $stmtRevogar =
            $pdo->prepare("

                UPDATE admin_sessoes

                SET
                    revogado_em = NOW()

                WHERE
                    usuario_id = :usuario_id

                AND
                    revogado_em IS NULL

            ");


        $stmtRevogar->execute([

            ":usuario_id" =>
                $usuarioId

        ]);


        /*
        |--------------------------------------------------------------------------
        | SE FOR A CONTA DA SESSÃO ATUAL
        |--------------------------------------------------------------------------
        */

        if (
            adminSessaoUsuarioAtual()
            === $usuarioId
        ) {

            adminSessaoLimparLocal();

        }


        return true;

    }
    catch (Throwable $erro) {

        error_log(
            "[AdminSession] Erro ao revogar todas as sessões do usuário " .
            $usuarioId .
            ": " .
            $erro->getMessage()
        );


        return false;

    }

}


/*
|--------------------------------------------------------------------------
| LIMPA REGISTROS ANTIGOS
|--------------------------------------------------------------------------
|
| Para não deixar admin_sessoes crescer indefinidamente.
|
| Mantemos registros recentes para auditoria e removemos apenas:
|
| - sessões expiradas há mais de 7 dias;
| - sessões revogadas há mais de 7 dias.
|
*/

function adminSessaoLimparExpiradas(
    PDO $pdo
): int
{

    try {

        $stmtLimpar =
            $pdo->prepare("

                DELETE FROM admin_sessoes

                WHERE

                    expira_em <
                    DATE_SUB(
                        NOW(),
                        INTERVAL 7 DAY
                    )

                OR

                    (
                        revogado_em IS NOT NULL

                        AND

                        revogado_em <
                        DATE_SUB(
                            NOW(),
                            INTERVAL 7 DAY
                        )
                    )

            ");


        $stmtLimpar->execute();


        return
            $stmtLimpar->rowCount();

    }
    catch (Throwable $erro) {

        error_log(
            "[AdminSession] Erro ao limpar sessões administrativas antigas: " .
            $erro->getMessage()
        );


        return 0;

    }

}