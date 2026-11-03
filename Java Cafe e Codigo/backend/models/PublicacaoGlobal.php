<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: PUBLICAÇÃO GLOBAL
|
| Responsável pelas publicações principais da Sala Global.
|
| Tabela:
|
| publicacoes_globais
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class PublicacaoGlobal
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | LIMITES
    |--------------------------------------------------------------------------
    */

    private const LIMITE_FEED =
        100;


    private const LIMITE_PESQUISA =
        200;


    private const LIMITE_TITULO =
        150;


    /*
    |--------------------------------------------------------------------------
    | CATEGORIAS
    |--------------------------------------------------------------------------
    |
    | "exercicio" permanece temporariamente porque, na Sala Global,
    | representa apenas uma categoria de publicação.
    |
    |--------------------------------------------------------------------------
    */

    private const CATEGORIAS_PERMITIDAS = [

        "dica",
        "explicacao",
        "codigo",
        "exercicio",
        "material",
        "video",
        "documentacao",
        "duvida",
        "outro"

    ];


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
    | NORMALIZAR TEXTO CURTO
    |--------------------------------------------------------------------------
    */

    private function normalizarTextoCurto(
        string $texto
    ): string {

        $texto =
            trim(
                $texto
            );


        if (
            $texto === ""
            ||
            !$this->utf8Valido(
                $texto
            )
        ) {

            return "";

        }


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

            return "";

        }


        return trim(
            $normalizado
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR CONTEÚDO
    |--------------------------------------------------------------------------
    */

    private function normalizarConteudo(
        ?string $conteudo
    ): ?string {

        if (
            $conteudo === null
        ) {

            return null;

        }


        $conteudo =
            str_replace(
                [
                    "\r\n",
                    "\r"
                ],
                "\n",
                $conteudo
            );


        $conteudo =
            trim(
                $conteudo
            );


        return
            $conteudo !== ""
                ?
                $conteudo
                :
                null;

    }


    /*
    |--------------------------------------------------------------------------
    | TÍTULO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function tituloValido(
        string $titulo
    ): bool {

        if (
            $titulo === ""
            ||
            !$this->utf8Valido(
                $titulo
            )
        ) {

            return false;

        }


        if (
            $this->comprimento(
                $titulo
            )
            >
            self::LIMITE_TITULO
        ) {

            return false;

        }


        return preg_match(
            "/[\x00-\x1F\x7F]/",
            $titulo
        ) !== 1;

    }


    /*
    |--------------------------------------------------------------------------
    | CONTEÚDO VÁLIDO
    |--------------------------------------------------------------------------
    |
    | Permitimos:
    |
    | - TAB;
    | - quebra de linha.
    |
    |--------------------------------------------------------------------------
    */

    private function conteudoValido(
        ?string $conteudo
    ): bool {

        if (
            $conteudo === null
        ) {

            return true;

        }


        if (
            !$this->utf8Valido(
                $conteudo
            )
        ) {

            return false;

        }


        return preg_match(
            "/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/",
            $conteudo
        ) !== 1;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR CATEGORIA
    |--------------------------------------------------------------------------
    */

    private function normalizarCategoria(
        string $categoria
    ): string {

        return strtolower(
            trim(
                $categoria
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CATEGORIA VÁLIDA
    |--------------------------------------------------------------------------
    */

    private function categoriaValida(
        string $categoria
    ): bool {

        return in_array(
            $categoria,
            self::CATEGORIAS_PERMITIDAS,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $usuarioId,
        string $titulo,
        ?string $conteudo,
        string $categoria = "outro"
    ): int|false {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $usuarioId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | CONTEÚDO ORIGINAL
        |--------------------------------------------------------------------------
        |
        | Validamos antes da normalização para não transformar entrada
        | inválida silenciosamente em NULL.
        |
        |--------------------------------------------------------------------------
        */

        if (
            !$this->conteudoValido(
                $conteudo
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $titulo =
            $this->normalizarTextoCurto(
                $titulo
            );


        $conteudo =
            $this->normalizarConteudo(
                $conteudo
            );


        $categoria =
            $this->normalizarCategoria(
                $categoria
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->tituloValido(
                $titulo
            )
            ||
            !$this->conteudoValido(
                $conteudo
            )
            ||
            !$this->categoriaValida(
                $categoria
            )
        ) {

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
                    INSERT INTO publicacoes_globais
                    (
                        usuario_id,
                        titulo,
                        conteudo,
                        categoria,
                        status
                    )

                    VALUES
                    (
                        :usuario_id,
                        :titulo,
                        :conteudo,
                        :categoria,
                        :status
                    )
                "
            );


        if (
            $stmt->execute(
                [
                    ":usuario_id" =>
                        $usuarioId,

                    ":titulo" =>
                        $titulo,

                    ":conteudo" =>
                        $conteudo,

                    ":categoria" =>
                        $categoria,

                    ":status" =>
                        self::STATUS_PUBLICADO
                ]
            )
            !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        $id =
            (int) $this->pdo->lastInsertId();


        return
            $id > 0
                ?
                $id
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR PUBLICAÇÃO PELO ID
    |--------------------------------------------------------------------------
    |
    | Mantemos pg.* temporariamente até conhecermos o schema completo
    | e todos os campos utilizados pelo frontend/Admin.
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
                        pg.*,
                        u.nome AS autor_nome,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_ajudas pa

                            WHERE
                                pa.publicacao_id = pg.id

                        ) AS total_ajudas,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_respostas pr

                            WHERE
                                pr.publicacao_id = pg.id
                                AND pr.status = :status_resposta

                        ) AS total_respostas

                    FROM publicacoes_globais pg

                    INNER JOIN usuarios u
                        ON u.id = pg.usuario_id

                    WHERE
                        pg.id = :id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":id" =>
                    $id,

                ":status_resposta" =>
                    self::STATUS_PUBLICADO
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
    | LISTAR PUBLICAÇÕES DO FEED
    |--------------------------------------------------------------------------
    */

    public function listarPublicadas(
        int $limite = 20,
        int $offset = 0
    ): array {

        [
            $limite,
            $offset
        ] =
            $this->normalizarPaginacao(
                $limite,
                $offset
            );


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        pg.*,
                        u.nome AS autor_nome,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_ajudas pa

                            WHERE
                                pa.publicacao_id = pg.id

                        ) AS total_ajudas,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_respostas pr

                            WHERE
                                pr.publicacao_id = pg.id
                                AND pr.status = :status_resposta

                        ) AS total_respostas

                    FROM publicacoes_globais pg

                    INNER JOIN usuarios u
                        ON u.id = pg.usuario_id

                    WHERE
                        pg.status = :status_publicacao

                    ORDER BY
                        pg.criado_em DESC,
                        pg.id DESC

                    LIMIT :limite
                    OFFSET :offset
                "
            );


        $stmt->bindValue(
            ":status_resposta",
            self::STATUS_PUBLICADO,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":status_publicacao",
            self::STATUS_PUBLICADO,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":limite",
            $limite,
            PDO::PARAM_INT
        );


        $stmt->bindValue(
            ":offset",
            $offset,
            PDO::PARAM_INT
        );


        $stmt->execute();


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
    | LISTAR POR CATEGORIA
    |--------------------------------------------------------------------------
    */

    public function listarPorCategoria(
        string $categoria,
        int $limite = 20,
        int $offset = 0
    ): array {

        $categoria =
            $this->normalizarCategoria(
                $categoria
            );


        if (
            !$this->categoriaValida(
                $categoria
            )
        ) {

            return [];

        }


        [
            $limite,
            $offset
        ] =
            $this->normalizarPaginacao(
                $limite,
                $offset
            );


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        pg.*,
                        u.nome AS autor_nome,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_ajudas pa

                            WHERE
                                pa.publicacao_id = pg.id

                        ) AS total_ajudas,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_respostas pr

                            WHERE
                                pr.publicacao_id = pg.id
                                AND pr.status = :status_resposta

                        ) AS total_respostas

                    FROM publicacoes_globais pg

                    INNER JOIN usuarios u
                        ON u.id = pg.usuario_id

                    WHERE
                        pg.status = :status_publicacao
                        AND pg.categoria = :categoria

                    ORDER BY
                        pg.criado_em DESC,
                        pg.id DESC

                    LIMIT :limite
                    OFFSET :offset
                "
            );


        $stmt->bindValue(
            ":status_resposta",
            self::STATUS_PUBLICADO,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":status_publicacao",
            self::STATUS_PUBLICADO,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":categoria",
            $categoria,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":limite",
            $limite,
            PDO::PARAM_INT
        );


        $stmt->bindValue(
            ":offset",
            $offset,
            PDO::PARAM_INT
        );


        $stmt->execute();


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
    | PESQUISAR PUBLICAÇÕES
    |--------------------------------------------------------------------------
    */

    public function pesquisar(
        string $termo,
        int $limite = 20,
        int $offset = 0
    ): array {

        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $termo =
            $this->normalizarTextoCurto(
                $termo
            );


        if (
            $termo === ""
            ||
            !$this->utf8Valido(
                $termo
            )
        ) {

            return [];

        }


        /*
        |--------------------------------------------------------------------------
        | LIMITE
        |--------------------------------------------------------------------------
        */

        $termo =
            $this->cortarTexto(
                $termo,
                self::LIMITE_PESQUISA
            );


        /*
        |--------------------------------------------------------------------------
        | LIKE
        |--------------------------------------------------------------------------
        |
        | "=" será nosso caractere de escape.
        |
        |--------------------------------------------------------------------------
        */

        $termo =
            str_replace(
                [
                    "=",
                    "%",
                    "_"
                ],
                [
                    "==",
                    "=%",
                    "=_"
                ],
                $termo
            );


        $termoBusca =
            "%"
            .
            $termo
            .
            "%";


        /*
        |--------------------------------------------------------------------------
        | PAGINAÇÃO
        |--------------------------------------------------------------------------
        */

        [
            $limite,
            $offset
        ] =
            $this->normalizarPaginacao(
                $limite,
                $offset
            );


        /*
        |--------------------------------------------------------------------------
        | CONSULTA
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        pg.*,
                        u.nome AS autor_nome,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_ajudas pa

                            WHERE
                                pa.publicacao_id = pg.id

                        ) AS total_ajudas,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_respostas pr

                            WHERE
                                pr.publicacao_id = pg.id
                                AND pr.status = :status_resposta

                        ) AS total_respostas

                    FROM publicacoes_globais pg

                    INNER JOIN usuarios u
                        ON u.id = pg.usuario_id

                    WHERE
                        pg.status = :status_publicacao

                        AND
                        (
                            pg.titulo LIKE :titulo ESCAPE '='
                            OR
                            pg.conteudo LIKE :conteudo ESCAPE '='
                        )

                    ORDER BY
                        pg.criado_em DESC,
                        pg.id DESC

                    LIMIT :limite
                    OFFSET :offset
                "
            );


        $stmt->bindValue(
            ":status_resposta",
            self::STATUS_PUBLICADO,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":status_publicacao",
            self::STATUS_PUBLICADO,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":titulo",
            $termoBusca,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":conteudo",
            $termoBusca,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":limite",
            $limite,
            PDO::PARAM_INT
        );


        $stmt->bindValue(
            ":offset",
            $offset,
            PDO::PARAM_INT
        );


        $stmt->execute();


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
    | LISTAR PUBLICAÇÕES DO USUÁRIO
    |--------------------------------------------------------------------------
    |
    | Inclui:
    |
    | - publicadas;
    | - ocultas.
    |
    | Não inclui:
    |
    | - removidas.
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

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_ajudas pa

                            WHERE
                                pa.publicacao_id = pg.id

                        ) AS total_ajudas,

                        (
                            SELECT
                                COUNT(*)

                            FROM publicacoes_respostas pr

                            WHERE
                                pr.publicacao_id = pg.id
                                AND pr.status = :status_resposta

                        ) AS total_respostas

                    FROM publicacoes_globais pg

                    WHERE
                        pg.usuario_id = :usuario_id
                        AND pg.status != :status_removido

                    ORDER BY
                        pg.criado_em DESC,
                        pg.id DESC
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId,

                ":status_resposta" =>
                    self::STATUS_PUBLICADO,

                ":status_removido" =>
                    self::STATUS_REMOVIDO
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
    | PUBLICAÇÃO PERTENCE AO USUÁRIO?
    |--------------------------------------------------------------------------
    */

    public function pertenceAoUsuario(
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
                        AND usuario_id = :usuario_id
                        AND status != :status_removido

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId,

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
    | ATUALIZAR PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $id,
        int $usuarioId,
        string $titulo,
        ?string $conteudo,
        string $categoria
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
        | CONTEÚDO ORIGINAL
        |--------------------------------------------------------------------------
        */

        if (
            !$this->conteudoValido(
                $conteudo
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $titulo =
            $this->normalizarTextoCurto(
                $titulo
            );


        $conteudo =
            $this->normalizarConteudo(
                $conteudo
            );


        $categoria =
            $this->normalizarCategoria(
                $categoria
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->tituloValido(
                $titulo
            )
            ||
            !$this->conteudoValido(
                $conteudo
            )
            ||
            !$this->categoriaValida(
                $categoria
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
                    UPDATE publicacoes_globais

                    SET
                        titulo = :titulo,
                        conteudo = :conteudo,
                        categoria = :categoria

                    WHERE
                        id = :id
                        AND usuario_id = :usuario_id
                        AND status != :status_removido
                "
            );


        if (
            $stmt->execute(
                [
                    ":titulo" =>
                        $titulo,

                    ":conteudo" =>
                        $conteudo,

                    ":categoria" =>
                        $categoria,

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
        | Salvar os mesmos dados continua sendo uma operação válida.
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
    | OCULTAR PUBLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | Transição permitida:
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
                    UPDATE publicacoes_globais

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


        /*
        |--------------------------------------------------------------------------
        | Aqui rowCount() é proposital.
        |
        | A operação só deve retornar true quando realmente ocorreu:
        |
        | publicado → oculto
        |--------------------------------------------------------------------------
        */

        return
            $stmt->rowCount() === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | RESTAURAR PUBLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | Transição permitida:
    |
    | oculto → publicado
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
                    UPDATE publicacoes_globais

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
    | REMOVER PUBLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | SOFT DELETE:
    |
    | publicado/oculto → removido
    |
    | O registro e os anexos físicos permanecem existentes.
    |
    |--------------------------------------------------------------------------
    */

    public function remover(
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
                    UPDATE publicacoes_globais

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


    /*
    |--------------------------------------------------------------------------
    | CONTAR PUBLICAÇÕES PUBLICADAS
    |--------------------------------------------------------------------------
    */

    public function contarPublicadas(): int
    {

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        COUNT(*)

                    FROM publicacoes_globais

                    WHERE
                        status = :status_publicado
                "
            );


        $stmt->execute(
            [
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
    | CONTAR PUBLICAÇÕES DO USUÁRIO
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

                    FROM publicacoes_globais

                    WHERE
                        usuario_id = :usuario_id
                        AND status != :status_removido
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId,

                ":status_removido" =>
                    self::STATUS_REMOVIDO
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
    | NORMALIZAR PAGINAÇÃO
    |--------------------------------------------------------------------------
    */

    private function normalizarPaginacao(
        int $limite,
        int $offset
    ): array {

        $limite =
            max(
                1,
                min(
                    $limite,
                    self::LIMITE_FEED
                )
            );


        $offset =
            max(
                0,
                $offset
            );


        return [
            $limite,
            $offset
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CORTAR TEXTO
    |--------------------------------------------------------------------------
    */

    private function cortarTexto(
        string $texto,
        int $limite
    ): string {

        if (
            $limite <= 0
        ) {

            return "";

        }


        if (
            function_exists(
                "mb_substr"
            )
        ) {

            return mb_substr(
                $texto,
                0,
                $limite,
                "UTF-8"
            );

        }


        return substr(
            $texto,
            0,
            $limite
        );

    }

}