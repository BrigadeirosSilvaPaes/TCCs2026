<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: AJUDA EM PUBLICAÇÃO GLOBAL
|
| Representa a ação:
|
| "Isso me ajudou"
|
| Não funciona como uma curtida de rede social.
|
| A finalidade é registrar que uma publicação publicada foi útil
| para outro usuário da plataforma.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class AjudaPublicacaoGlobal
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    private const STATUS_PUBLICADO =
        "publicado";


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
    | ID VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function idValido(
        int $id
    ): bool {

        return
            $id > 0;

    }


    /*
    |--------------------------------------------------------------------------
    | PUBLICAÇÃO PODE RECEBER AJUDA?
    |--------------------------------------------------------------------------
    |
    | Para aceitar "Isso me ajudou":
    |
    | - a publicação precisa existir;
    | - precisa estar publicada;
    | - não pode pertencer ao próprio usuário.
    |
    |--------------------------------------------------------------------------
    */

    private function publicacaoPodeReceberAjuda(
        int $publicacaoId,
        int $usuarioId
    ): bool {

        if (
            !$this->idValido(
                $publicacaoId
            )
            ||
            !$this->idValido(
                $usuarioId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM publicacoes_globais

                    WHERE
                        id = :publicacao_id
                        AND status = :status_publicado
                        AND usuario_id != :usuario_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId,

                ":status_publicado" =>
                    self::STATUS_PUBLICADO,

                ":usuario_id" =>
                    $usuarioId
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR SE O USUÁRIO JÁ MARCOU
    |--------------------------------------------------------------------------
    */

    public function existe(
        int $publicacaoId,
        int $usuarioId
    ): bool {

        if (
            !$this->idValido(
                $publicacaoId
            )
            ||
            !$this->idValido(
                $usuarioId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM publicacoes_ajudas

                    WHERE
                        publicacao_id = :publicacao_id
                        AND usuario_id = :usuario_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId,

                ":usuario_id" =>
                    $usuarioId
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | MARCAR COMO ÚTIL
    |--------------------------------------------------------------------------
    |
    | A operação confirma o estado da publicação no próprio INSERT.
    |
    | Isso evita:
    |
    | Controller verifica "publicado"
    | ↓
    | outra requisição remove a publicação
    | ↓
    | ajuda é inserida mesmo assim
    |
    | O banco também precisa possuir:
    |
    | UNIQUE(publicacao_id, usuario_id)
    |
    |--------------------------------------------------------------------------
    */

    public function adicionar(
        int $publicacaoId,
        int $usuarioId
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | IDS
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $publicacaoId
            )
            ||
            !$this->idValido(
                $usuarioId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | INSERT CONDICIONAL
        |--------------------------------------------------------------------------
        |
        | Só existe SELECT para o INSERT quando:
        |
        | - a publicação está publicada;
        | - o usuário não é o próprio autor.
        |
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    INSERT INTO publicacoes_ajudas
                    (
                        publicacao_id,
                        usuario_id
                    )

                    SELECT
                        pg.id,
                        :usuario_id

                    FROM publicacoes_globais pg

                    WHERE
                        pg.id = :publicacao_id
                        AND pg.status = :status_publicado
                        AND pg.usuario_id != :usuario_autor

                    LIMIT 1
                "
            );


        try {

            if (
                $stmt->execute(
                    [
                        ":usuario_id" =>
                            $usuarioId,

                        ":publicacao_id" =>
                            $publicacaoId,

                        ":status_publicado" =>
                            self::STATUS_PUBLICADO,

                        ":usuario_autor" =>
                            $usuarioId
                    ]
                )
                !== true
            ) {

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | NENHUM INSERT
            |--------------------------------------------------------------------------
            |
            | Pode significar:
            |
            | - publicação inexistente;
            | - publicação oculta/removida;
            | - própria publicação.
            |
            |--------------------------------------------------------------------------
            */

            if (
                $stmt->rowCount() !== 1
            ) {

                return false;

            }


            return true;

        }
        catch (
            PDOException $erro
        ) {

            /*
            |--------------------------------------------------------------------------
            | DUPLICAÇÃO POR CONCORRÊNCIA
            |--------------------------------------------------------------------------
            |
            | Duas requisições podem tentar adicionar a mesma ajuda.
            |
            | Se outra já conseguiu inserir, o estado final desejado
            | já está correto.
            |
            |--------------------------------------------------------------------------
            */

            if (
                $this->erroDuplicacao(
                    $erro
                )
                &&
                $this->publicacaoPodeReceberAjuda(
                    $publicacaoId,
                    $usuarioId
                )
                &&
                $this->existe(
                    $publicacaoId,
                    $usuarioId
                )
            ) {

                return true;

            }


            throw $erro;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER MARCAÇÃO
    |--------------------------------------------------------------------------
    |
    | Operação idempotente.
    |
    | Se a marcação já não existir, o estado final desejado continua sendo:
    |
    | marcado = false.
    |
    |--------------------------------------------------------------------------
    */

    public function remover(
        int $publicacaoId,
        int $usuarioId
    ): bool {

        if (
            !$this->idValido(
                $publicacaoId
            )
            ||
            !$this->idValido(
                $usuarioId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    DELETE FROM publicacoes_ajudas

                    WHERE
                        publicacao_id = :publicacao_id
                        AND usuario_id = :usuario_id
                "
            );


        return
            $stmt->execute(
                [
                    ":publicacao_id" =>
                        $publicacaoId,

                    ":usuario_id" =>
                        $usuarioId
                ]
            )
            === true;

    }


    /*
    |--------------------------------------------------------------------------
    | ALTERNAR MARCAÇÃO
    |--------------------------------------------------------------------------
    |
    | Não marcou:
    |
    | → adiciona.
    |
    | Já marcou:
    |
    | → remove.
    |
    |--------------------------------------------------------------------------
    */

    public function alternar(
        int $publicacaoId,
        int $usuarioId
    ): array {

        /*
        |--------------------------------------------------------------------------
        | IDS
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $publicacaoId
            )
            ||
            !$this->idValido(
                $usuarioId
            )
        ) {

            return [

                "status" =>
                    false,

                "marcado" =>
                    false,

                "total" =>
                    0,

                "mensagem" =>
                    "Dados inválidos."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | PUBLICAÇÃO
        |--------------------------------------------------------------------------
        |
        | O Controller já valida isso, mas o Model também precisa proteger
        | o próprio contrato caso seja reutilizado diretamente.
        |
        |--------------------------------------------------------------------------
        */

        if (
            !$this->publicacaoPodeReceberAjuda(
                $publicacaoId,
                $usuarioId
            )
        ) {

            return [

                "status" =>
                    false,

                "marcado" =>
                    false,

                "total" =>
                    $this->contarPorPublicacao(
                        $publicacaoId
                    ),

                "mensagem" =>
                    "Esta publicação não pode receber esta marcação."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | JÁ MARCOU
        |--------------------------------------------------------------------------
        */

        if (
            $this->existe(
                $publicacaoId,
                $usuarioId
            )
        ) {

            $resultado =
                $this->remover(
                    $publicacaoId,
                    $usuarioId
                );


            /*
            |--------------------------------------------------------------------------
            | CONFIRMA ESTADO REAL
            |--------------------------------------------------------------------------
            */

            $marcado =
                $this->existe(
                    $publicacaoId,
                    $usuarioId
                );


            return [

                "status" =>
                    $resultado
                    &&
                    !$marcado,

                "marcado" =>
                    $marcado,

                "total" =>
                    $this->contarPorPublicacao(
                        $publicacaoId
                    )

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | AINDA NÃO MARCOU
        |--------------------------------------------------------------------------
        */

        $resultado =
            $this->adicionar(
                $publicacaoId,
                $usuarioId
            );


        /*
        |--------------------------------------------------------------------------
        | CONFIRMA ESTADO REAL
        |--------------------------------------------------------------------------
        */

        $marcado =
            $this->existe(
                $publicacaoId,
                $usuarioId
            );


        return [

            "status" =>
                $resultado
                &&
                $marcado,

            "marcado" =>
                $marcado,

            "total" =>
                $this->contarPorPublicacao(
                    $publicacaoId
                )

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR MARCAÇÕES DA PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    public function contarPorPublicacao(
        int $publicacaoId
    ): int {

        if (
            !$this->idValido(
                $publicacaoId
            )
        ) {

            return 0;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        COUNT(*)

                    FROM publicacoes_ajudas

                    WHERE
                        publicacao_id = :publicacao_id
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId
            ]
        );


        $total =
            $stmt->fetchColumn();


        if (
            $total === false
        ) {

            return 0;

        }


        return max(
            0,
            (int) $total
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR PUBLICAÇÕES QUE AJUDARAM O USUÁRIO
    |--------------------------------------------------------------------------
    |
    | Somente publicações atualmente visíveis no feed.
    |
    |--------------------------------------------------------------------------
    */

    public function contarPorUsuario(
        int $usuarioId
    ): int {

        if (
            !$this->idValido(
                $usuarioId
            )
        ) {

            return 0;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        COUNT(*)

                    FROM publicacoes_ajudas pa

                    INNER JOIN publicacoes_globais pg
                        ON pg.id = pa.publicacao_id

                    WHERE
                        pa.usuario_id = :usuario_id
                        AND pg.status = :status_publicacao
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId,

                ":status_publicacao" =>
                    self::STATUS_PUBLICADO
            ]
        );


        $total =
            $stmt->fetchColumn();


        if (
            $total === false
        ) {

            return 0;

        }


        return max(
            0,
            (int) $total
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR PUBLICAÇÕES QUE AJUDARAM O USUÁRIO
    |--------------------------------------------------------------------------
    |
    | Pode futuramente alimentar:
    |
    | "Materiais que me ajudaram"
    |
    | Somente publicações atualmente publicadas são retornadas.
    |
    | Mantemos pg.* temporariamente até a auditoria completa do schema
    | de publicacoes_globais.
    |
    |--------------------------------------------------------------------------
    */

    public function listarPorUsuario(
        int $usuarioId
    ): array {

        if (
            !$this->idValido(
                $usuarioId
            )
        ) {

            return [];

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        pg.*,
                        u.nome AS autor_nome,
                        pa.criado_em AS marcado_em,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_ajudas pa2

                            WHERE
                                pa2.publicacao_id = pg.id

                        ) AS total_ajudas,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_respostas pr

                            WHERE
                                pr.publicacao_id = pg.id
                                AND pr.status = :status_resposta

                        ) AS total_respostas

                    FROM publicacoes_ajudas pa

                    INNER JOIN publicacoes_globais pg
                        ON pg.id = pa.publicacao_id

                    INNER JOIN usuarios u
                        ON u.id = pg.usuario_id

                    WHERE
                        pa.usuario_id = :usuario_id
                        AND pg.status = :status_publicacao

                    ORDER BY
                        pa.criado_em DESC,
                        pa.id DESC
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId,

                ":status_publicacao" =>
                    self::STATUS_PUBLICADO,

                ":status_resposta" =>
                    self::STATUS_PUBLICADO
            ]
        );


        $resultado =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $resultado
            )
                ?
                $resultado
                :
                [];

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER TODAS AS MARCAÇÕES DA PUBLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | HARD DELETE.
    |
    | Não utilizar apenas porque a publicação recebeu:
    |
    | status = removido
    |
    | Como a Sala Global utiliza soft delete, as marcações também
    | permanecem armazenadas nesse fluxo.
    |
    |--------------------------------------------------------------------------
    */

    public function removerPorPublicacao(
        int $publicacaoId
    ): bool {

        if (
            !$this->idValido(
                $publicacaoId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    DELETE FROM publicacoes_ajudas

                    WHERE
                        publicacao_id = :publicacao_id
                "
            );


        return
            $stmt->execute(
                [
                    ":publicacao_id" =>
                        $publicacaoId
                ]
            )
            === true;

    }


    /*
    |--------------------------------------------------------------------------
    | ERRO DE DUPLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | Projeto utiliza MySQL/MariaDB.
    |
    | SQLSTATE:
    |
    | 23000
    |
    | Código MySQL:
    |
    | 1062 = Duplicate entry.
    |
    |--------------------------------------------------------------------------
    */

    private function erroDuplicacao(
        PDOException $erro
    ): bool {

        $sqlState =
            (string) (
                $erro->errorInfo[0]
                ??
                $erro->getCode()
            );


        $codigoDriver =
            (int) (
                $erro->errorInfo[1]
                ??
                0
            );


        return
            $sqlState === "23000"
            &&
            $codigoDriver === 1062;

    }

}