<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: RESPOSTA DE PUBLICAÇÃO GLOBAL
|
| Responsável pelas respostas publicadas dentro das publicações
| da Sala Global.
|
| Estados:
|
| publicado
| oculto
| removido
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class RespostaPublicacaoGlobal
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


    private const STATUS_OCULTO =
        "oculto";


    private const STATUS_REMOVIDO =
        "removido";


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {

        global $pdo;


        if (
            !isset($pdo)
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
    | UTF-8 VÁLIDO
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
    | NORMALIZAR MENSAGEM
    |--------------------------------------------------------------------------
    |
    | Preserva quebras de linha.
    |
    |--------------------------------------------------------------------------
    */

    private function normalizarMensagem(
        string $mensagem
    ): string {

        $mensagem =
            str_replace(
                [
                    "\r\n",
                    "\r"
                ],
                "\n",
                $mensagem
            );


        return trim(
            $mensagem
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MENSAGEM VÁLIDA
    |--------------------------------------------------------------------------
    |
    | Permitimos:
    |
    | - texto;
    | - TAB;
    | - quebra de linha.
    |
    | Demais caracteres de controle são rejeitados.
    |
    |--------------------------------------------------------------------------
    */

    private function mensagemValida(
        string $mensagem
    ): bool {

        if (
            $mensagem === ""
        ) {

            return false;

        }


        if (
            !$this->utf8Valido(
                $mensagem
            )
        ) {

            return false;

        }


        return preg_match(
            "/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/",
            $mensagem
        ) !== 1;

    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR RESPOSTA
    |--------------------------------------------------------------------------
    |
    | Defesa adicional:
    |
    | mesmo que alguém utilize este Model fora do SalaGlobalController,
    | a resposta somente é criada se a publicação estiver PUBLICADA.
    |
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $publicacaoId,
        int $usuarioId,
        string $mensagem
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
        | MENSAGEM
        |--------------------------------------------------------------------------
        */

        $mensagem =
            $this->normalizarMensagem(
                $mensagem
            );


        if (
            !$this->mensagemValida(
                $mensagem
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | INSERT CONDICIONAL
        |--------------------------------------------------------------------------
        |
        | INSERT ... SELECT garante atomicamente:
        |
        | publicação existe
        | +
        | publicação está publicada
        |
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    INSERT INTO publicacoes_respostas
                    (
                        publicacao_id,
                        usuario_id,
                        mensagem,
                        status
                    )

                    SELECT
                        pg.id,
                        :usuario_id,
                        :mensagem,
                        :status_resposta

                    FROM publicacoes_globais pg

                    WHERE
                        pg.id = :publicacao_id
                        AND pg.status = :status_publicacao

                    LIMIT 1
                "
            );


        if (
            $stmt->execute(
                [
                    ":usuario_id" =>
                        $usuarioId,

                    ":mensagem" =>
                        $mensagem,

                    ":status_resposta" =>
                        self::STATUS_PUBLICADO,

                    ":publicacao_id" =>
                        $publicacaoId,

                    ":status_publicacao" =>
                        self::STATUS_PUBLICADO
                ]
            )
            !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | PRECISA TER CRIADO UMA RESPOSTA
        |--------------------------------------------------------------------------
        */

        if (
            $stmt->rowCount() !== 1
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        $respostaId =
            (int) $this->pdo->lastInsertId();


        return
            $respostaId > 0
                ?
                $respostaId
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR RESPOSTA PELO ID
    |--------------------------------------------------------------------------
    |
    | Não retornamos o e-mail do autor.
    |
    | Mantemos pr.* temporariamente até conferirmos o schema completo
    | da tabela publicacoes_respostas.
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
                        pr.*,
                        u.nome AS autor_nome

                    FROM publicacoes_respostas pr

                    INNER JOIN usuarios u
                        ON u.id = pr.usuario_id

                    WHERE
                        pr.id = :id

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
    | LISTAR RESPOSTAS DE UMA PUBLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | Somente respostas PUBLICADAS são exibidas publicamente.
    |
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
                        pr.*,
                        u.nome AS autor_nome

                    FROM publicacoes_respostas pr

                    INNER JOIN usuarios u
                        ON u.id = pr.usuario_id

                    WHERE
                        pr.publicacao_id = :publicacao_id
                        AND pr.status = :status_publicado

                    ORDER BY
                        pr.criado_em ASC,
                        pr.id ASC
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId,

                ":status_publicado" =>
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
    | LISTAR RESPOSTAS DE UM USUÁRIO
    |--------------------------------------------------------------------------
    |
    | Mostra somente respostas:
    |
    | - publicadas;
    | - pertencentes a publicações ainda publicadas.
    |
    | Respostas ocultadas pela moderação NÃO aparecem.
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
                        pr.*,
                        pg.titulo AS publicacao_titulo

                    FROM publicacoes_respostas pr

                    INNER JOIN publicacoes_globais pg
                        ON pg.id = pr.publicacao_id

                    WHERE
                        pr.usuario_id = :usuario_id
                        AND pr.status = :status_resposta
                        AND pg.status = :status_publicacao

                    ORDER BY
                        pr.criado_em DESC,
                        pr.id DESC
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId,

                ":status_resposta" =>
                    self::STATUS_PUBLICADO,

                ":status_publicacao" =>
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
    | CONTAR RESPOSTAS DE UMA PUBLICAÇÃO
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

                    FROM publicacoes_respostas

                    WHERE
                        publicacao_id = :publicacao_id
                        AND status = :status_publicado
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId,

                ":status_publicado" =>
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
    | CONTAR RESPOSTAS DE UM USUÁRIO
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

                    FROM publicacoes_respostas pr

                    INNER JOIN publicacoes_globais pg
                        ON pg.id = pr.publicacao_id

                    WHERE
                        pr.usuario_id = :usuario_id
                        AND pr.status = :status_resposta
                        AND pg.status = :status_publicacao
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId,

                ":status_resposta" =>
                    self::STATUS_PUBLICADO,

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
    | RESPOSTA PERTENCE AO USUÁRIO?
    |--------------------------------------------------------------------------
    |
    | Uma resposta removida não é mais considerada editável/removível.
    |
    |--------------------------------------------------------------------------
    */

    public function pertenceAoUsuario(
        int $respostaId,
        int $usuarioId
    ): bool {

        if (
            !$this->idValido(
                $respostaId
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

                    FROM publicacoes_respostas

                    WHERE
                        id = :resposta_id
                        AND usuario_id = :usuario_id
                        AND status != :status_removido

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":resposta_id" =>
                    $respostaId,

                ":usuario_id" =>
                    $usuarioId,

                ":status_removido" =>
                    self::STATUS_REMOVIDO
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | RESPOSTA PERTENCE À PUBLICAÇÃO?
    |--------------------------------------------------------------------------
    */

    public function pertenceAPublicacao(
        int $respostaId,
        int $publicacaoId
    ): bool {

        if (
            !$this->idValido(
                $respostaId
            )
            ||
            !$this->idValido(
                $publicacaoId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM publicacoes_respostas

                    WHERE
                        id = :resposta_id
                        AND publicacao_id = :publicacao_id
                        AND status != :status_removido

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":resposta_id" =>
                    $respostaId,

                ":publicacao_id" =>
                    $publicacaoId,

                ":status_removido" =>
                    self::STATUS_REMOVIDO
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR RESPOSTA
    |--------------------------------------------------------------------------
    |
    | O usuário só pode editar a própria resposta.
    |
    | Respostas removidas não podem ser editadas.
    |
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $id,
        int $usuarioId,
        string $mensagem
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | IDS
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $id
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
        | MENSAGEM
        |--------------------------------------------------------------------------
        */

        $mensagem =
            $this->normalizarMensagem(
                $mensagem
            );


        if (
            !$this->mensagemValida(
                $mensagem
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE publicacoes_respostas

                    SET
                        mensagem = :mensagem

                    WHERE
                        id = :id
                        AND usuario_id = :usuario_id
                        AND status != :status_removido
                "
            );


        if (
            $stmt->execute(
                [
                    ":mensagem" =>
                        $mensagem,

                    ":id" =>
                        $id,

                    ":usuario_id" =>
                        $usuarioId,

                    ":status_removido" =>
                        self::STATUS_REMOVIDO
                ]
            )
            !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NÃO USAMOS rowCount()
        |--------------------------------------------------------------------------
        |
        | Salvar exatamente a mesma mensagem é válido.
        |
        | Precisamos apenas confirmar que a resposta existe,
        | pertence ao usuário e não foi removida.
        |
        |--------------------------------------------------------------------------
        */

        return $this->pertenceAoUsuario(
            $id,
            $usuarioId
        );

    }


    /*
    |--------------------------------------------------------------------------
    | OCULTAR RESPOSTA
    |--------------------------------------------------------------------------
    |
    | Transição:
    |
    | publicado → oculto
    |
    |--------------------------------------------------------------------------
    */

    public function ocultar(
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
                    UPDATE publicacoes_respostas

                    SET
                        status = :status_oculto

                    WHERE
                        id = :id
                        AND status = :status_publicado
                "
            );


        if (
            $stmt->execute(
                [
                    ":status_oculto" =>
                        self::STATUS_OCULTO,

                    ":status_publicado" =>
                        self::STATUS_PUBLICADO,

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
    | RESTAURAR RESPOSTA
    |--------------------------------------------------------------------------
    |
    | Transição:
    |
    | oculto → publicado
    |
    | Uma resposta removida não é restaurada.
    |
    |--------------------------------------------------------------------------
    */

    public function restaurar(
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
                    UPDATE publicacoes_respostas

                    SET
                        status = :status_publicado

                    WHERE
                        id = :id
                        AND status = :status_oculto
                "
            );


        if (
            $stmt->execute(
                [
                    ":status_publicado" =>
                        self::STATUS_PUBLICADO,

                    ":status_oculto" =>
                        self::STATUS_OCULTO,

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
    | REMOVER PRÓPRIA RESPOSTA
    |--------------------------------------------------------------------------
    |
    | Soft delete:
    |
    | status = removido
    |
    |--------------------------------------------------------------------------
    */

    public function remover(
        int $id,
        int $usuarioId
    ): bool {

        if (
            !$this->idValido(
                $id
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
                    UPDATE publicacoes_respostas

                    SET
                        status = :status_removido

                    WHERE
                        id = :id
                        AND usuario_id = :usuario_id
                        AND status != :status_removido
                "
            );


        if (
            $stmt->execute(
                [
                    ":status_removido" =>
                        self::STATUS_REMOVIDO,

                    ":id" =>
                        $id,

                    ":usuario_id" =>
                        $usuarioId
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
    | REMOVER POR MODERAÇÃO
    |--------------------------------------------------------------------------
    |
    | Não verifica autoria.
    |
    | Este método só deve ser chamado por fluxo administrativo/moderação
    | devidamente protegido.
    |
    |--------------------------------------------------------------------------
    */

    public function removerPorModeracao(
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
                    UPDATE publicacoes_respostas

                    SET
                        status = :status_removido

                    WHERE
                        id = :id
                        AND status != :status_removido
                "
            );


        if (
            $stmt->execute(
                [
                    ":status_removido" =>
                        self::STATUS_REMOVIDO,

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

}