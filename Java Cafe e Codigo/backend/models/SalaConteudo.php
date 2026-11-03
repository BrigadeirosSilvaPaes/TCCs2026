<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: CONTEÚDO DA SALA
|
| Responsável pelos conteúdos publicados dentro das Salas de Aula.
|
| Tabela:
|
| sala_conteudos
|
| O módulo trabalha com:
|
| - avisos;
| - explicações;
| - códigos;
| - materiais;
| - vídeos;
| - links;
| - outros conteúdos.
|
| Não existe módulo próprio de exercícios dentro da Sala de Aula.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class SalaConteudo
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | CATEGORIAS
    |--------------------------------------------------------------------------
    |
    | Precisa permanecer alinhado com SalaController.
    |
    |--------------------------------------------------------------------------
    */

    private const CATEGORIAS_PERMITIDAS = [

        "aviso",
        "explicacao",
        "codigo",
        "material",
        "video",
        "link",
        "outro"

    ];


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    private const STATUS_PERMITIDOS = [

        "publicado",
        "rascunho",
        "oculto"

    ];


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
    |
    | Preserva quebras de linha.
    |
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


        if (
            !$this->utf8Valido(
                $conteudo
            )
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
        ) {

            return false;

        }


        if (
            !$this->utf8Valido(
                $titulo
            )
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
    | CRIAR CONTEÚDO
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $salaId,
        int $usuarioId,
        string $titulo,
        ?string $conteudo,
        string $categoria = "material",
        string $status = "publicado"
    ): int|false {

        /*
        |--------------------------------------------------------------------------
        | IDS
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $salaId
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


        $status =
            $this->normalizarStatus(
                $status
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
            ||
            !$this->statusValido(
                $status
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
                    INSERT INTO sala_conteudos
                    (
                        sala_id,
                        usuario_id,
                        titulo,
                        conteudo,
                        categoria,
                        status
                    )

                    VALUES
                    (
                        :sala_id,
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
                    ":sala_id" =>
                        $salaId,

                    ":usuario_id" =>
                        $usuarioId,

                    ":titulo" =>
                        $titulo,

                    ":conteudo" =>
                        $conteudo,

                    ":categoria" =>
                        $categoria,

                    ":status" =>
                        $status
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

        $conteudoId =
            (int) $this->pdo->lastInsertId();


        return
            $conteudoId > 0
                ?
                $conteudoId
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR POR ID
    |--------------------------------------------------------------------------
    |
    | Mantemos sc.* temporariamente até terminarmos a auditoria do
    | schema da tabela sala_conteudos.
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
                        sc.*,
                        u.nome AS autor_nome,
                        u.email AS autor_email

                    FROM sala_conteudos sc

                    INNER JOIN usuarios u
                        ON u.id = sc.usuario_id

                    WHERE
                        sc.id = :id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":id" =>
                    $id
            ]
        );


        $conteudo =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $conteudo
            )
                ?
                $conteudo
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR CONTEÚDOS DA SALA
    |--------------------------------------------------------------------------
    |
    | $incluirOcultos = true:
    |
    | professor recebe:
    |
    | - publicado;
    | - rascunho;
    | - oculto.
    |
    | false:
    |
    | estudante recebe somente "publicado".
    |
    |--------------------------------------------------------------------------
    */

    public function listarPorSala(
        int $salaId,
        bool $incluirOcultos = false
    ): array {

        if (
            !$this->idValido(
                $salaId
            )
        ) {

            return [];

        }


        /*
        |--------------------------------------------------------------------------
        | PROFESSOR
        |--------------------------------------------------------------------------
        */

        if (
            $incluirOcultos
        ) {

            $sql =
                "
                    SELECT
                        sc.*,
                        u.nome AS autor_nome

                    FROM sala_conteudos sc

                    INNER JOIN usuarios u
                        ON u.id = sc.usuario_id

                    WHERE
                        sc.sala_id = :sala_id

                    ORDER BY
                        sc.criado_em DESC,
                        sc.id DESC
                ";

        }


        /*
        |--------------------------------------------------------------------------
        | ESTUDANTE
        |--------------------------------------------------------------------------
        */

        else {

            $sql =
                "
                    SELECT
                        sc.*,
                        u.nome AS autor_nome

                    FROM sala_conteudos sc

                    INNER JOIN usuarios u
                        ON u.id = sc.usuario_id

                    WHERE
                        sc.sala_id = :sala_id
                        AND sc.status = 'publicado'

                    ORDER BY
                        sc.criado_em DESC,
                        sc.id DESC
                ";

        }


        $stmt =
            $this->pdo->prepare(
                $sql
            );


        $stmt->execute(
            [
                ":sala_id" =>
                    $salaId
            ]
        );


        $conteudos =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $conteudos
            )
                ?
                $conteudos
                :
                [];

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR CONTEÚDOS DO PROFESSOR
    |--------------------------------------------------------------------------
    |
    | Lista conteúdos criados pelo usuário informado.
    |
    | 🟡 Vamos confirmar posteriormente se este método ainda possui
    | um consumidor atual fora do SalaController.
    |
    |--------------------------------------------------------------------------
    */

    public function listarPorProfessor(
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
                        sc.*,
                        s.nome AS sala_nome,
                        s.codigo AS sala_codigo

                    FROM sala_conteudos sc

                    INNER JOIN salas s
                        ON s.id = sc.sala_id

                    WHERE
                        sc.usuario_id = :usuario_id

                    ORDER BY
                        sc.criado_em DESC,
                        sc.id DESC
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId
            ]
        );


        $conteudos =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $conteudos
            )
                ?
                $conteudos
                :
                [];

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR CONTEÚDO
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $id,
        string $titulo,
        ?string $conteudo,
        string $categoria,
        string $status
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $id
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


        $status =
            $this->normalizarStatus(
                $status
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
            ||
            !$this->statusValido(
                $status
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
                    UPDATE sala_conteudos

                    SET
                        titulo = :titulo,
                        conteudo = :conteudo,
                        categoria = :categoria,
                        status = :status

                    WHERE
                        id = :id
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

                    ":status" =>
                        $status,

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
        | NÃO USAMOS rowCount()
        |--------------------------------------------------------------------------
        |
        | Se todos os valores forem iguais aos atuais, o UPDATE pode
        | modificar zero linhas mesmo com um conteúdo válido.
        |
        |--------------------------------------------------------------------------
        */

        return
            $this->existe(
                $id
            );

    }


    /*
    |--------------------------------------------------------------------------
    | ALTERAR STATUS
    |--------------------------------------------------------------------------
    */

    public function alterarStatus(
        int $id,
        string $status
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $id
            )
        ) {

            return false;

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
                    UPDATE sala_conteudos

                    SET
                        status = :status

                    WHERE
                        id = :id
                "
            );


        if (
            $stmt->execute(
                [
                    ":status" =>
                        $status,

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
        | CONFIRMA ESTADO FINAL
        |--------------------------------------------------------------------------
        */

        $conteudo =
            $this->buscarPorId(
                $id
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return false;

        }


        $statusAtual =
            $conteudo["status"]
            ??
            null;


        if (
            !is_string(
                $statusAtual
            )
        ) {

            return false;

        }


        return
            $this->normalizarStatus(
                $statusAtual
            )
            ===
            $status;

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR
    |--------------------------------------------------------------------------
    |
    | O Model remove somente o registro no banco.
    |
    | A camada superior precisa coordenar a remoção dos arquivos
    | físicos vinculados ao conteúdo.
    |
    |--------------------------------------------------------------------------
    */

    public function excluir(
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
                    DELETE FROM sala_conteudos

                    WHERE
                        id = :id
                "
            );


        if (
            $stmt->execute(
                [
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
    | CONTEÚDO EXISTE?
    |--------------------------------------------------------------------------
    */

    public function existe(
        int $conteudoId
    ): bool {

        if (
            !$this->idValido(
                $conteudoId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM sala_conteudos

                    WHERE
                        id = :conteudo_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":conteudo_id" =>
                    $conteudoId
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | CONTEÚDO PERTENCE À SALA?
    |--------------------------------------------------------------------------
    */

    public function pertenceASala(
        int $conteudoId,
        int $salaId
    ): bool {

        if (
            !$this->idValido(
                $conteudoId
            )
            ||
            !$this->idValido(
                $salaId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM sala_conteudos

                    WHERE
                        id = :conteudo_id
                        AND sala_id = :sala_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":conteudo_id" =>
                    $conteudoId,

                ":sala_id" =>
                    $salaId
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | CONTEÚDO FOI CRIADO PELO PROFESSOR?
    |--------------------------------------------------------------------------
    |
    | O SalaController atual autoriza alterações principalmente pela
    | propriedade da SALA.
    |
    | Mantemos este método por compatibilidade até terminar a busca
    | cruzada de referências.
    |
    |--------------------------------------------------------------------------
    */

    public function pertenceAoProfessor(
        int $conteudoId,
        int $usuarioId
    ): bool {

        if (
            !$this->idValido(
                $conteudoId
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

                    FROM sala_conteudos

                    WHERE
                        id = :conteudo_id
                        AND usuario_id = :usuario_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":conteudo_id" =>
                    $conteudoId,

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
    | CONTAR CONTEÚDOS PUBLICADOS DA SALA
    |--------------------------------------------------------------------------
    */

    public function contarPorSala(
        int $salaId
    ): int {

        if (
            !$this->idValido(
                $salaId
            )
        ) {

            return 0;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        COUNT(*)

                    FROM sala_conteudos

                    WHERE
                        sala_id = :sala_id
                        AND status = 'publicado'
                "
            );


        $stmt->execute(
            [
                ":sala_id" =>
                    $salaId
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

}