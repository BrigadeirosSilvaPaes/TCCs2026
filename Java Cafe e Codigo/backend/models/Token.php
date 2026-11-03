<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: TOKEN
|
| Responsável pelos códigos temporários utilizados em:
|
| - confirmação de e-mail;
| - recuperação de senha.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class Token
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | TIPOS PERMITIDOS
    |--------------------------------------------------------------------------
    */

    private const TIPOS_PERMITIDOS = [

        "confirmacao",
        "recuperacao"

    ];


    /*
    |--------------------------------------------------------------------------
    | EXPIRAÇÃO PADRÃO
    |--------------------------------------------------------------------------
    |
    | Mantemos os 15 minutos utilizados atualmente pela plataforma.
    |
    |--------------------------------------------------------------------------
    */

    private const EXPIRACAO_PADRAO_MINUTOS =
        15;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {

        global $pdo;


        if (
            !isset(
                $pdo
            )
            ||
            !$pdo instanceof PDO
        ) {

            throw new RuntimeException(
                "A conexão com o banco de dados não está disponível."
            );

        }


        $this->pdo =
            $pdo;

    }


    /*
    |--------------------------------------------------------------------------
    | USUÁRIO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function usuarioValido(
        int $usuarioId
    ): bool {

        return
            $usuarioId > 0;

    }


    /*
    |--------------------------------------------------------------------------
    | TOKEN ID VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function tokenIdValido(
        int $id
    ): bool {

        return
            $id > 0;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR TIPO
    |--------------------------------------------------------------------------
    */

    private function normalizarTipo(
        string $tipo
    ): string {

        return strtolower(
            trim(
                $tipo
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | TIPO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function tipoValido(
        string $tipo
    ): bool {

        return in_array(
            $tipo,
            self::TIPOS_PERMITIDOS,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CÓDIGO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function codigoValido(
        string $codigo
    ): bool {

        return preg_match(
            "/^[0-9]{6}$/D",
            $codigo
        ) === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | INICIAR ESCOPO DE TRANSAÇÃO
    |--------------------------------------------------------------------------
    |
    | Caso já exista uma transação externa, utilizamos SAVEPOINT.
    |
    | Diferente dos uploads físicos, aqui todas as operações acontecem
    | exclusivamente dentro do banco.
    |
    |--------------------------------------------------------------------------
    */

    private function iniciarEscopoTransacao(): array
    {

        /*
        |--------------------------------------------------------------------------
        | TRANSAÇÃO PRÓPRIA
        |--------------------------------------------------------------------------
        */

        if (
            !$this->pdo->inTransaction()
        ) {

            if (
                !$this->pdo->beginTransaction()
            ) {

                throw new RuntimeException(
                    "Não foi possível iniciar a transação de token."
                );

            }


            return [

                "tipo" =>
                    "transacao",

                "nome" =>
                    null

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | TRANSAÇÃO EXTERNA → SAVEPOINT
        |--------------------------------------------------------------------------
        */

        $nome =
            "token_"
            .
            bin2hex(
                random_bytes(
                    6
                )
            );


        $this->pdo->exec(
            "SAVEPOINT {$nome}"
        );


        return [

            "tipo" =>
                "savepoint",

            "nome" =>
                $nome

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR ESCOPO
    |--------------------------------------------------------------------------
    */

    private function confirmarEscopoTransacao(
        array $escopo
    ): void {

        $tipo =
            $escopo["tipo"]
            ??
            null;


        /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */

        if (
            $tipo === "transacao"
        ) {

            if (
                !$this->pdo->inTransaction()
            ) {

                throw new RuntimeException(
                    "A transação de token foi encerrada inesperadamente."
                );

            }


            if (
                !$this->pdo->commit()
            ) {

                throw new RuntimeException(
                    "Não foi possível confirmar a transação de token."
                );

            }


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | RELEASE SAVEPOINT
        |--------------------------------------------------------------------------
        */

        if (
            $tipo === "savepoint"
        ) {

            $nome =
                $escopo["nome"]
                ??
                null;


            if (
                !is_string(
                    $nome
                )
                ||
                $nome === ""
                ||
                !$this->pdo->inTransaction()
            ) {

                throw new RuntimeException(
                    "Savepoint de token inválido."
                );

            }


            $this->pdo->exec(
                "RELEASE SAVEPOINT {$nome}"
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | REVERTER ESCOPO
    |--------------------------------------------------------------------------
    */

    private function reverterEscopoTransacao(
        array $escopo
    ): void {

        $tipo =
            $escopo["tipo"]
            ??
            null;


        /*
        |--------------------------------------------------------------------------
        | ROLLBACK COMPLETO
        |--------------------------------------------------------------------------
        */

        if (
            $tipo === "transacao"
        ) {

            if (
                $this->pdo->inTransaction()
            ) {

                $this->pdo->rollBack();

            }


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ROLLBACK PARA SAVEPOINT
        |--------------------------------------------------------------------------
        */

        if (
            $tipo === "savepoint"
        ) {

            $nome =
                $escopo["nome"]
                ??
                null;


            if (
                !is_string(
                    $nome
                )
                ||
                $nome === ""
                ||
                !$this->pdo->inTransaction()
            ) {

                return;

            }


            $this->pdo->exec(
                "ROLLBACK TO SAVEPOINT {$nome}"
            );


            $this->pdo->exec(
                "RELEASE SAVEPOINT {$nome}"
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | INVALIDAR TOKENS ANTERIORES
    |--------------------------------------------------------------------------
    |
    | Somente um código de cada tipo deve permanecer utilizável
    | por usuário.
    |
    | Exemplo:
    |
    | recuperação antiga → utilizada = 1
    | recuperação nova   → utilizada = 0
    |
    |--------------------------------------------------------------------------
    */

    private function invalidarTokensAnteriores(
        int $usuarioId,
        string $tipo
    ): bool {

        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE tokens

                    SET
                        utilizado = 1

                    WHERE
                        usuario_id = :usuario
                        AND tipo = :tipo
                        AND utilizado = 0
                "
            );


        return $stmt->execute(
            [
                ":usuario" =>
                    $usuarioId,

                ":tipo" =>
                    $tipo
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR TOKEN
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $usuarioId,
        string $tipo,
        string $codigo
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | NORMALIZA
        |--------------------------------------------------------------------------
        */

        $tipo =
            $this->normalizarTipo(
                $tipo
            );


        $codigo =
            trim(
                $codigo
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDA
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioValido(
                $usuarioId
            )
            ||
            !$this->tipoValido(
                $tipo
            )
            ||
            !$this->codigoValido(
                $codigo
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | EXPIRAÇÃO
        |--------------------------------------------------------------------------
        */

        $expiraEm =
            $this->gerarDataExpiracao(
                self::EXPIRACAO_PADRAO_MINUTOS
            );


        /*
        |--------------------------------------------------------------------------
        | TRANSAÇÃO
        |--------------------------------------------------------------------------
        |
        | A invalidação do código anterior e a criação do novo
        | precisam acontecer juntas.
        |
        |--------------------------------------------------------------------------
        */

        $escopo =
            null;


        try {

            $escopo =
                $this->iniciarEscopoTransacao();


            /*
            |--------------------------------------------------------------------------
            | INVALIDA CÓDIGOS ANTIGOS
            |--------------------------------------------------------------------------
            */

            if (
                !$this->invalidarTokensAnteriores(
                    $usuarioId,
                    $tipo
                )
            ) {

                throw new RuntimeException(
                    "Não foi possível invalidar tokens anteriores."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | NOVO TOKEN
            |--------------------------------------------------------------------------
            */

            $stmt =
                $this->pdo->prepare(
                    "
                        INSERT INTO tokens
                        (
                            usuario_id,
                            tipo,
                            codigo,
                            expira_em
                        )

                        VALUES
                        (
                            :usuario,
                            :tipo,
                            :codigo,
                            :expira
                        )
                    "
                );


            $resultado =
                $stmt->execute(
                    [
                        ":usuario" =>
                            $usuarioId,

                        ":tipo" =>
                            $tipo,

                        ":codigo" =>
                            $codigo,

                        ":expira" =>
                            $expiraEm
                    ]
                );


            if (
                $resultado !== true
            ) {

                throw new RuntimeException(
                    "Não foi possível criar o token."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | CONFIRMA
            |--------------------------------------------------------------------------
            */

            $this->confirmarEscopoTransacao(
                $escopo
            );


            return true;

        }
        catch (
            Throwable $erro
        ) {

            if (
                is_array(
                    $escopo
                )
            ) {

                try {

                    $this->reverterEscopoTransacao(
                        $escopo
                    );

                }
                catch (
                    Throwable $erroRollback
                ) {

                    error_log(
                        "[Token] Falha ao reverter criação de token: "
                        .
                        $erroRollback->getMessage()
                    );

                }

            }


            throw $erro;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR TOKEN PELO CÓDIGO
    |--------------------------------------------------------------------------
    |
    | Não filtramos expiração aqui.
    |
    | O AuthController precisa distinguir:
    |
    | código inexistente
    | de
    | código encontrado, porém expirado.
    |
    |--------------------------------------------------------------------------
    */

    public function buscarCodigo(
        int $usuarioId,
        string $tipo,
        string $codigo
    ): array|false {

        $tipo =
            $this->normalizarTipo(
                $tipo
            );


        $codigo =
            trim(
                $codigo
            );


        if (
            !$this->usuarioValido(
                $usuarioId
            )
            ||
            !$this->tipoValido(
                $tipo
            )
            ||
            !$this->codigoValido(
                $codigo
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        id,
                        usuario_id,
                        tipo,
                        codigo,
                        expira_em,
                        utilizado,
                        criado_em

                    FROM tokens

                    WHERE
                        usuario_id = :usuario
                        AND tipo = :tipo
                        AND codigo = :codigo
                        AND utilizado = 0

                    ORDER BY
                        criado_em DESC,
                        id DESC

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":usuario" =>
                    $usuarioId,

                ":tipo" =>
                    $tipo,

                ":codigo" =>
                    $codigo
            ]
        );


        $token =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $token
            )
                ?
                $token
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR ÚLTIMO TOKEN DO USUÁRIO
    |--------------------------------------------------------------------------
    |
    | 🟡 POSSÍVEL LEGADO
    |
    | Mantido por compatibilidade até encerrarmos a auditoria.
    |
    |--------------------------------------------------------------------------
    */

    public function buscarUsuario(
        int $usuarioId,
        string $tipo
    ): array|false {

        $tipo =
            $this->normalizarTipo(
                $tipo
            );


        if (
            !$this->usuarioValido(
                $usuarioId
            )
            ||
            !$this->tipoValido(
                $tipo
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        id,
                        usuario_id,
                        tipo,
                        codigo,
                        expira_em,
                        utilizado,
                        criado_em

                    FROM tokens

                    WHERE
                        usuario_id = :usuario
                        AND tipo = :tipo
                        AND utilizado = 0

                    ORDER BY
                        criado_em DESC,
                        id DESC

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":usuario" =>
                    $usuarioId,

                ":tipo" =>
                    $tipo
            ]
        );


        $token =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $token
            )
                ?
                $token
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | UTILIZAR TOKEN
    |--------------------------------------------------------------------------
    |
    | Esta operação precisa ser ATÔMICA.
    |
    | Não basta:
    |
    | SELECT utilizado = 0
    | ↓
    | UPDATE depois
    |
    | porque duas requisições poderiam ler o mesmo token antes
    | de uma delas marcá-lo como utilizado.
    |
    | Fazemos:
    |
    | UPDATE ... WHERE utilizado = 0 AND ainda não expirou
    |
    | e exigimos exatamente 1 linha alterada.
    |
    |--------------------------------------------------------------------------
    */

    public function utilizar(
        int $id
    ): bool {

        if (
            !$this->tokenIdValido(
                $id
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE tokens

                    SET
                        utilizado = 1

                    WHERE
                        id = :id
                        AND utilizado = 0
                        AND expira_em > NOW()
                "
            );


        $stmt->execute(
            [
                ":id" =>
                    $id
            ]
        );


        return
            $stmt->rowCount() === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR TOKENS EXPIRADOS
    |--------------------------------------------------------------------------
    */

    public function excluirExpirados(): bool
    {

        $stmt =
            $this->pdo->prepare(
                "
                    DELETE FROM tokens

                    WHERE
                        expira_em <= NOW()
                "
            );


        return $stmt->execute();

    }


    /*
    |--------------------------------------------------------------------------
    | CÓDIGO EXPIRADO?
    |--------------------------------------------------------------------------
    |
    | 🟡 POSSÍVEL LEGADO
    |
    | ATENÇÃO:
    |
    | Este método consulta somente pelo código.
    |
    | Como códigos possuem apenas 6 dígitos, ele NÃO deve ser utilizado
    | como autorização de segurança.
    |
    | O fluxo atual correto utiliza:
    |
    | buscarCodigo(usuarioId, tipo, codigo)
    |
    |--------------------------------------------------------------------------
    */

    public function codigoExpirado(
        string $codigo
    ): bool {

        $codigo =
            trim(
                $codigo
            );


        if (
            !$this->codigoValido(
                $codigo
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM tokens

                    WHERE
                        codigo = :codigo
                        AND expira_em <= NOW()

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":codigo" =>
                    $codigo
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | GERAR CÓDIGO
    |--------------------------------------------------------------------------
    |
    | random_int() utiliza fonte criptograficamente segura.
    |
    |--------------------------------------------------------------------------
    */

    public function gerarCodigo(): string
    {

        return str_pad(
            (string) random_int(
                0,
                999999
            ),
            6,
            "0",
            STR_PAD_LEFT
        );

    }


    /*
    |--------------------------------------------------------------------------
    | GERAR DATA DE EXPIRAÇÃO
    |--------------------------------------------------------------------------
    */

    public function gerarDataExpiracao(
        int $minutos = self::EXPIRACAO_PADRAO_MINUTOS
    ): string {

        /*
        |--------------------------------------------------------------------------
        | LIMITE DEFENSIVO
        |--------------------------------------------------------------------------
        |
        | Permitimos entre:
        |
        | 1 minuto
        | e
        | 24 horas.
        |
        |--------------------------------------------------------------------------
        */

        if (
            $minutos < 1
            ||
            $minutos > 1440
        ) {

            throw new InvalidArgumentException(
                "Tempo de expiração inválido."
            );

        }


        $agora =
            new DateTimeImmutable(
                "now"
            );


        $expira =
            $agora->modify(
                "+"
                .
                $minutos
                .
                " minutes"
            );


        if (
            !$expira instanceof DateTimeImmutable
        ) {

            throw new RuntimeException(
                "Não foi possível calcular a expiração do token."
            );

        }


        return $expira->format(
            "Y-m-d H:i:s"
        );

    }

}