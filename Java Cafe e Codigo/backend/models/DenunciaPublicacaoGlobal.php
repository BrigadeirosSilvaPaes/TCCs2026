<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: DENÚNCIA DE PUBLICAÇÃO GLOBAL
|
| Responsável pelas denúncias enviadas pelos usuários contra
| publicações da Sala Global.
|
| Fluxo:
|
| pendente
|    ↓
| analisada
|
| ou
|
| pendente
|    ↓
| ignorada
|
| As permissões administrativas para analisar/ignorar denúncias
| pertencem ao Controller/API de moderação.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class DenunciaPublicacaoGlobal
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

    private const STATUS_PENDENTE =
        "pendente";


    private const STATUS_ANALISADA =
        "analisada";


    private const STATUS_IGNORADA =
        "ignorada";


    /*
    |--------------------------------------------------------------------------
    | STATUS PERMITIDOS
    |--------------------------------------------------------------------------
    */

    private const STATUS_PERMITIDOS = [

        self::STATUS_PENDENTE,
        self::STATUS_ANALISADA,
        self::STATUS_IGNORADA

    ];


    /*
    |--------------------------------------------------------------------------
    | PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    private const STATUS_PUBLICACAO_PUBLICADA =
        "publicado";


    /*
    |--------------------------------------------------------------------------
    | LIMITES
    |--------------------------------------------------------------------------
    |
    | Alinhado ao SalaGlobalController atual.
    |
    |--------------------------------------------------------------------------
    */

    private const MOTIVO_MAX =
        255;


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
    | COMPRIMENTO
    |--------------------------------------------------------------------------
    */

    private function comprimento(
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

    private function utf8Valido(
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
    | NORMALIZAR MOTIVO
    |--------------------------------------------------------------------------
    */

    private function normalizarMotivo(
        string $motivo
    ): string {

        $motivo =
            trim(
                $motivo
            );


        if (
            $motivo === ""
            ||
            !$this->utf8Valido(
                $motivo
            )
        ) {

            return "";

        }


        $normalizado =
            preg_replace(
                "/\s+/u",
                " ",
                $motivo
            );


        if (
            !is_string(
                $normalizado
            )
        ) {

            return "";

        }


        return trim(
            $normalizado
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MOTIVO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function motivoValido(
        string $motivo
    ): bool {

        if (
            $motivo === ""
            ||
            !$this->utf8Valido(
                $motivo
            )
        ) {

            return false;

        }


        if (
            $this->comprimento(
                $motivo
            )
            >
            self::MOTIVO_MAX
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | Motivo é texto curto.
        |
        | Não permitimos TAB, Enter ou outros caracteres de controle.
        |--------------------------------------------------------------------------
        */

        return preg_match(
            "/[\x00-\x1F\x7F]/",
            $motivo
        ) !== 1;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR DESCRIÇÃO
    |--------------------------------------------------------------------------
    |
    | A descrição é opcional e pode possuir várias linhas.
    |
    |--------------------------------------------------------------------------
    */

    private function normalizarDescricao(
        ?string $descricao
    ): ?string {

        if (
            $descricao === null
        ) {

            return null;

        }


        $descricao =
            str_replace(
                [
                    "\r\n",
                    "\r"
                ],
                "\n",
                $descricao
            );


        $descricao =
            trim(
                $descricao
            );


        return
            $descricao !== ""
                ?
                $descricao
                :
                null;

    }


    /*
    |--------------------------------------------------------------------------
    | DESCRIÇÃO VÁLIDA
    |--------------------------------------------------------------------------
    |
    | Permitimos:
    |
    | - TAB;
    | - quebra de linha.
    |
    | O limite máximo será alinhado depois que conferirmos o tipo/tamanho
    | real da coluna "descricao" no banco.
    |
    |--------------------------------------------------------------------------
    */

    private function descricaoValida(
        ?string $descricao
    ): bool {

        if (
            $descricao === null
        ) {

            return true;

        }


        if (
            !$this->utf8Valido(
                $descricao
            )
        ) {

            return false;

        }


        return preg_match(
            "/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/",
            $descricao
        ) !== 1;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR STATUS
    |--------------------------------------------------------------------------
    */

    private function normalizarStatus(
        string $status
    ): string {

        return strtolower(
            trim(
                $status
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | STATUS VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function statusValido(
        string $status
    ): bool {

        return in_array(
            $status,
            self::STATUS_PERMITIDOS,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DENÚNCIA PENDENTE — LEITURA BLOQUEANTE
    |--------------------------------------------------------------------------
    |
    | Utilizado dentro de criar().
    |
    | Como a publicação já estará bloqueada com FOR UPDATE,
    | esta leitura ocorre no estado atual do banco e participa
    | da proteção contra concorrência.
    |
    |--------------------------------------------------------------------------
    */

    private function existePendenteBloqueado(
        int $publicacaoId,
        int $usuarioId
    ): bool {

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        id

                    FROM publicacoes_denuncias

                    WHERE
                        publicacao_id = :publicacao_id
                        AND usuario_id = :usuario_id
                        AND status = :status

                    LIMIT 1

                    FOR UPDATE
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId,

                ":usuario_id" =>
                    $usuarioId,

                ":status" =>
                    self::STATUS_PENDENTE
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR DENÚNCIA
    |--------------------------------------------------------------------------
    |
    | Regras aplicadas também no Model:
    |
    | - publicação precisa existir;
    | - precisa estar publicada;
    | - usuário não pode denunciar a própria publicação;
    | - somente uma denúncia pendente por usuário/publicação.
    |
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $publicacaoId,
        int $usuarioId,
        string $motivo,
        ?string $descricao = null
    ): int|false {

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
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $motivo =
            $this->normalizarMotivo(
                $motivo
            );


        $descricao =
            $this->normalizarDescricao(
                $descricao
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->motivoValido(
                $motivo
            )
            ||
            !$this->descricaoValida(
                $descricao
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | TRANSAÇÃO LOCAL?
        |--------------------------------------------------------------------------
        |
        | Se o Controller já possuir uma transação aberta, usamos a mesma.
        |
        | Caso contrário, o próprio Model abre uma transação curta.
        |
        |--------------------------------------------------------------------------
        */

        $transacaoLocal =
            !$this->pdo->inTransaction();


        try {

            if (
                $transacaoLocal
            ) {

                if (
                    !$this->pdo->beginTransaction()
                ) {

                    throw new RuntimeException(
                        "Não foi possível iniciar a transação da denúncia."
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | BLOQUEIA A PUBLICAÇÃO
            |--------------------------------------------------------------------------
            |
            | Essa trava serializa denúncias concorrentes da mesma publicação.
            |
            | Exemplo:
            |
            | requisição A trava publicação 10
            | requisição B espera
            | A insere denúncia e COMMIT
            | B continua e enxerga a denúncia pendente já criada
            |
            |--------------------------------------------------------------------------
            */

            $stmtPublicacao =
                $this->pdo->prepare(
                    "
                        SELECT
                            usuario_id,
                            status

                        FROM publicacoes_globais

                        WHERE
                            id = :publicacao_id

                        LIMIT 1

                        FOR UPDATE
                    "
                );


            $stmtPublicacao->execute(
                [
                    ":publicacao_id" =>
                        $publicacaoId
                ]
            );


            $publicacao =
                $stmtPublicacao->fetch(
                    PDO::FETCH_ASSOC
                );


            /*
            |--------------------------------------------------------------------------
            | PUBLICAÇÃO NÃO EXISTE
            |--------------------------------------------------------------------------
            */

            if (
                !is_array(
                    $publicacao
                )
            ) {

                if (
                    $transacaoLocal
                    &&
                    $this->pdo->inTransaction()
                ) {

                    $this->pdo->rollBack();

                }


                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | PRECISA ESTAR PUBLICADA
            |--------------------------------------------------------------------------
            */

            $statusPublicacao =
                strtolower(
                    trim(
                        (string) (
                            $publicacao["status"]
                            ??
                            ""
                        )
                    )
                );


            if (
                $statusPublicacao
                !==
                self::STATUS_PUBLICACAO_PUBLICADA
            ) {

                if (
                    $transacaoLocal
                    &&
                    $this->pdo->inTransaction()
                ) {

                    $this->pdo->rollBack();

                }


                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | NÃO DENUNCIAR A PRÓPRIA PUBLICAÇÃO
            |--------------------------------------------------------------------------
            */

            $autorId =
                (int) (
                    $publicacao["usuario_id"]
                    ??
                    0
                );


            if (
                $autorId <= 0
                ||
                $autorId ===
                $usuarioId
            ) {

                if (
                    $transacaoLocal
                    &&
                    $this->pdo->inTransaction()
                ) {

                    $this->pdo->rollBack();

                }


                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | DENÚNCIA PENDENTE
            |--------------------------------------------------------------------------
            */

            if (
                $this->existePendenteBloqueado(
                    $publicacaoId,
                    $usuarioId
                )
            ) {

                if (
                    $transacaoLocal
                    &&
                    $this->pdo->inTransaction()
                ) {

                    $this->pdo->rollBack();

                }


                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

            $stmt =
                $this->pdo->prepare(
                    "
                        INSERT INTO publicacoes_denuncias
                        (
                            publicacao_id,
                            usuario_id,
                            motivo,
                            descricao,
                            status
                        )

                        VALUES
                        (
                            :publicacao_id,
                            :usuario_id,
                            :motivo,
                            :descricao,
                            :status
                        )
                    "
                );


            if (
                $stmt->execute(
                    [
                        ":publicacao_id" =>
                            $publicacaoId,

                        ":usuario_id" =>
                            $usuarioId,

                        ":motivo" =>
                            $motivo,

                        ":descricao" =>
                            $descricao,

                        ":status" =>
                            self::STATUS_PENDENTE
                    ]
                )
                !== true
            ) {

                throw new RuntimeException(
                    "Não foi possível registrar a denúncia."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | ID
            |--------------------------------------------------------------------------
            */

            $denunciaId =
                (int) $this->pdo->lastInsertId();


            if (
                $denunciaId <= 0
            ) {

                throw new RuntimeException(
                    "A denúncia não recebeu um identificador válido."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | COMMIT LOCAL
            |--------------------------------------------------------------------------
            */

            if (
                $transacaoLocal
            ) {

                if (
                    !$this->pdo->commit()
                ) {

                    throw new RuntimeException(
                        "Não foi possível confirmar a denúncia."
                    );

                }

            }


            return
                $denunciaId;

        }
        catch (
            Throwable $erro
        ) {

            /*
            |--------------------------------------------------------------------------
            | SOMENTE REVERTE TRANSAÇÃO ABERTA PELO PRÓPRIO MODEL
            |--------------------------------------------------------------------------
            */

            if (
                $transacaoLocal
                &&
                $this->pdo->inTransaction()
            ) {

                $this->pdo->rollBack();

            }


            throw $erro;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | EXISTE DENÚNCIA PENDENTE?
    |--------------------------------------------------------------------------
    */

    public function existePendente(
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

                    FROM publicacoes_denuncias

                    WHERE
                        publicacao_id = :publicacao_id
                        AND usuario_id = :usuario_id
                        AND status = :status

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId,

                ":usuario_id" =>
                    $usuarioId,

                ":status" =>
                    self::STATUS_PENDENTE
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR DENÚNCIA POR ID
    |--------------------------------------------------------------------------
    |
    | Voltado principalmente para moderação.
    |
    | Mantemos pd.* temporariamente até conhecermos o schema completo
    | de publicacoes_denuncias.
    |
    |--------------------------------------------------------------------------
    */

    public function buscarPorId(
        int $id
    ): array|false {

        if (
            !$this->idValido(
                $id
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        pd.*,

                        pg.titulo
                            AS publicacao_titulo,

                        pg.usuario_id
                            AS publicacao_autor_id,

                        pg.status
                            AS publicacao_status,

                        u.nome
                            AS denunciante_nome

                    FROM publicacoes_denuncias pd

                    INNER JOIN publicacoes_globais pg
                        ON pg.id = pd.publicacao_id

                    INNER JOIN usuarios u
                        ON u.id = pd.usuario_id

                    WHERE
                        pd.id = :id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":id" =>
                    $id
            ]
        );


        $resultado =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $resultado
            )
                ?
                $resultado
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR DENÚNCIAS PENDENTES
    |--------------------------------------------------------------------------
    |
    | Uso previsto:
    |
    | painel administrativo de moderação.
    |
    |--------------------------------------------------------------------------
    */

    public function listarPendentes(): array
    {

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        pd.*,

                        pg.titulo
                            AS publicacao_titulo,

                        pg.usuario_id
                            AS publicacao_autor_id,

                        pg.status
                            AS publicacao_status,

                        u.nome
                            AS denunciante_nome

                    FROM publicacoes_denuncias pd

                    INNER JOIN publicacoes_globais pg
                        ON pg.id = pd.publicacao_id

                    INNER JOIN usuarios u
                        ON u.id = pd.usuario_id

                    WHERE
                        pd.status = :status

                    ORDER BY
                        pd.criado_em ASC,
                        pd.id ASC
                "
            );


        $stmt->execute(
            [
                ":status" =>
                    self::STATUS_PENDENTE
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
    | LISTAR DENÚNCIAS DE UMA PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    public function listarPorPublicacao(
        int $publicacaoId
    ): array {

        if (
            !$this->idValido(
                $publicacaoId
            )
        ) {

            return [];

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        pd.*,
                        u.nome AS denunciante_nome

                    FROM publicacoes_denuncias pd

                    INNER JOIN usuarios u
                        ON u.id = pd.usuario_id

                    WHERE
                        pd.publicacao_id = :publicacao_id

                    ORDER BY
                        pd.criado_em DESC,
                        pd.id DESC
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId
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
    | LISTAR DENÚNCIAS DO USUÁRIO
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
                        pd.*,

                        pg.titulo
                            AS publicacao_titulo,

                        pg.status
                            AS publicacao_status

                    FROM publicacoes_denuncias pd

                    INNER JOIN publicacoes_globais pg
                        ON pg.id = pd.publicacao_id

                    WHERE
                        pd.usuario_id = :usuario_id

                    ORDER BY
                        pd.criado_em DESC,
                        pd.id DESC
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId
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
    | CONTAR DENÚNCIAS PENDENTES
    |--------------------------------------------------------------------------
    */

    public function contarPendentes(): int
    {

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        COUNT(*)

                    FROM publicacoes_denuncias

                    WHERE
                        status = :status
                "
            );


        $stmt->execute(
            [
                ":status" =>
                    self::STATUS_PENDENTE
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
    | CONTAR DENÚNCIAS DA PUBLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | $status = null
    |
    | → conta todas.
    |
    | $status informado
    |
    | → conta somente o status solicitado.
    |
    |--------------------------------------------------------------------------
    */

    public function contarPorPublicacao(
        int $publicacaoId,
        ?string $status = null
    ): int {

        if (
            !$this->idValido(
                $publicacaoId
            )
        ) {

            return 0;

        }


        /*
        |--------------------------------------------------------------------------
        | TODAS
        |--------------------------------------------------------------------------
        */

        if (
            $status === null
        ) {

            $stmt =
                $this->pdo->prepare(
                    "
                        SELECT
                            COUNT(*)

                        FROM publicacoes_denuncias

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
        | STATUS
        |--------------------------------------------------------------------------
        */

        $status =
            $this->normalizarStatus(
                $status
            );


        if (
            !$this->statusValido(
                $status
            )
        ) {

            return 0;

        }


        /*
        |--------------------------------------------------------------------------
        | POR STATUS
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        COUNT(*)

                    FROM publicacoes_denuncias

                    WHERE
                        publicacao_id = :publicacao_id
                        AND status = :status
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId,

                ":status" =>
                    $status
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
    | MARCAR COMO ANALISADA
    |--------------------------------------------------------------------------
    |
    | Transição:
    |
    | pendente → analisada
    |
    |--------------------------------------------------------------------------
    */

    public function marcarComoAnalisada(
        int $id
    ): bool {

        if (
            !$this->idValido(
                $id
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE publicacoes_denuncias

                    SET
                        status = :status_analisada

                    WHERE
                        id = :id
                        AND status = :status_pendente
                "
            );


        if (
            $stmt->execute(
                [
                    ":status_analisada" =>
                        self::STATUS_ANALISADA,

                    ":status_pendente" =>
                        self::STATUS_PENDENTE,

                    ":id" =>
                        $id
                ]
            )
            !== true
        ) {

            return false;

        }


        return
            $stmt->rowCount() === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | IGNORAR DENÚNCIA
    |--------------------------------------------------------------------------
    |
    | Transição:
    |
    | pendente → ignorada
    |
    |--------------------------------------------------------------------------
    */

    public function ignorar(
        int $id
    ): bool {

        if (
            !$this->idValido(
                $id
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE publicacoes_denuncias

                    SET
                        status = :status_ignorada

                    WHERE
                        id = :id
                        AND status = :status_pendente
                "
            );


        if (
            $stmt->execute(
                [
                    ":status_ignorada" =>
                        self::STATUS_IGNORADA,

                    ":status_pendente" =>
                        self::STATUS_PENDENTE,

                    ":id" =>
                        $id
                ]
            )
            !== true
        ) {

            return false;

        }


        return
            $stmt->rowCount() === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | MARCAR TODAS COMO ANALISADAS
    |--------------------------------------------------------------------------
    |
    | Utilizado quando a moderação toma uma ação sobre a publicação e
    | todas as denúncias pendentes podem ser consideradas tratadas.
    |
    | É uma operação idempotente:
    |
    | se não existir denúncia pendente, o estado final já está correto.
    |
    |--------------------------------------------------------------------------
    */

    public function analisarTodasDaPublicacao(
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
                    UPDATE publicacoes_denuncias

                    SET
                        status = :status_analisada

                    WHERE
                        publicacao_id = :publicacao_id
                        AND status = :status_pendente
                "
            );


        return
            $stmt->execute(
                [
                    ":status_analisada" =>
                        self::STATUS_ANALISADA,

                    ":status_pendente" =>
                        self::STATUS_PENDENTE,

                    ":publicacao_id" =>
                        $publicacaoId
                ]
            )
            === true;

    }

}