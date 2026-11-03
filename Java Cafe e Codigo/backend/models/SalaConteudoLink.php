<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: LINKS E VÍDEOS DO CONTEÚDO DA SALA
|
| Responsável pelos recursos externos vinculados aos conteúdos
| das Salas de Aula.
|
| Tabela:
|
| sala_conteudos_links
|
| Tipos permitidos:
|
| - link;
| - video.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class SalaConteudoLink
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | TIPOS
    |--------------------------------------------------------------------------
    */

    private const TIPOS_PERMITIDOS = [

        "link",
        "video"

    ];


    /*
    |--------------------------------------------------------------------------
    | LIMITES
    |--------------------------------------------------------------------------
    */

    private const TITULO_MAX =
        150;


    private const URL_MAX =
        2048;


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
    | NORMALIZAR TÍTULO
    |--------------------------------------------------------------------------
    */

    private function normalizarTitulo(
        ?string $titulo
    ): ?string {

        if (
            $titulo === null
        ) {

            return null;

        }


        $titulo =
            trim(
                $titulo
            );


        if (
            $titulo === ""
        ) {

            return null;

        }


        if (
            !$this->utf8Valido(
                $titulo
            )
        ) {

            return null;

        }


        $tituloNormalizado =
            preg_replace(
                "/\s+/u",
                " ",
                $titulo
            );


        if (
            !is_string(
                $tituloNormalizado
            )
        ) {

            return null;

        }


        $tituloNormalizado =
            trim(
                $tituloNormalizado
            );


        return
            $tituloNormalizado !== ""
                ?
                $tituloNormalizado
                :
                null;

    }


    /*
    |--------------------------------------------------------------------------
    | TÍTULO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function tituloValido(
        ?string $titulo
    ): bool {

        if (
            $titulo === null
        ) {

            return true;

        }


        if (
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
            self::TITULO_MAX
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
    | NORMALIZAR URL
    |--------------------------------------------------------------------------
    */

    private function normalizarUrl(
        string $url
    ): string {

        return trim(
            $url
        );

    }


    /*
    |--------------------------------------------------------------------------
    | URL VÁLIDA
    |--------------------------------------------------------------------------
    |
    | Como esse recurso é apenas armazenado e apresentado ao usuário,
    | aceitamos URLs HTTP e HTTPS.
    |
    | Não aceitamos:
    |
    | - javascript:;
    | - data:;
    | - file:;
    | - ftp:;
    | - outros esquemas.
    |
    |--------------------------------------------------------------------------
    */

    private function urlValida(
        string $url
    ): bool {

        if (
            $url === ""
            ||
            strlen(
                $url
            )
            >
            self::URL_MAX
        ) {

            return false;

        }


        if (
            str_contains(
                $url,
                "\0"
            )
        ) {

            return false;

        }


        if (
            filter_var(
                $url,
                FILTER_VALIDATE_URL
            )
            === false
        ) {

            return false;

        }


        $partes =
            parse_url(
                $url
            );


        if (
            !is_array(
                $partes
            )
        ) {

            return false;

        }


        $esquema =
            strtolower(
                trim(
                    (string) (
                        $partes["scheme"]
                        ??
                        ""
                    )
                )
            );


        $host =
            trim(
                (string) (
                    $partes["host"]
                    ??
                    ""
                )
            );


        if (
            $host === ""
        ) {

            return false;

        }


        return in_array(
            $esquema,
            [
                "http",
                "https"
            ],
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $conteudoId,
        string $url,
        ?string $titulo,
        string $tipo = "link"
    ): int|false {

        /*
        |--------------------------------------------------------------------------
        | CONTEÚDO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $conteudoId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $url =
            $this->normalizarUrl(
                $url
            );


        $titulo =
            $this->normalizarTitulo(
                $titulo
            );


        $tipo =
            $this->normalizarTipo(
                $tipo
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->urlValida(
                $url
            )
            ||
            !$this->tituloValido(
                $titulo
            )
            ||
            !$this->tipoValido(
                $tipo
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
                    INSERT INTO sala_conteudos_links
                    (
                        conteudo_id,
                        url,
                        titulo,
                        tipo
                    )

                    VALUES
                    (
                        :conteudo_id,
                        :url,
                        :titulo,
                        :tipo
                    )
                "
            );


        $resultado =
            $stmt->execute(
                [
                    ":conteudo_id" =>
                        $conteudoId,

                    ":url" =>
                        $url,

                    ":titulo" =>
                        $titulo,

                    ":tipo" =>
                        $tipo
                ]
            );


        if (
            $resultado !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        $linkId =
            (int) $this->pdo->lastInsertId();


        return
            $linkId > 0
                ?
                $linkId
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR POR ID
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
                        id,
                        conteudo_id,
                        url,
                        titulo,
                        tipo,
                        criado_em

                    FROM sala_conteudos_links

                    WHERE
                        id = :id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":id" =>
                    $id
            ]
        );


        $recurso =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $recurso
            )
                ?
                $recurso
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR POR CONTEÚDO
    |--------------------------------------------------------------------------
    */

    public function listarPorConteudo(
        int $conteudoId
    ): array {

        if (
            !$this->idValido(
                $conteudoId
            )
        ) {

            return [];

        }


        return $this->listar(
            $conteudoId,
            null
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR SOMENTE LINKS
    |--------------------------------------------------------------------------
    */

    public function listarLinks(
        int $conteudoId
    ): array {

        if (
            !$this->idValido(
                $conteudoId
            )
        ) {

            return [];

        }


        return $this->listar(
            $conteudoId,
            "link"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR SOMENTE VÍDEOS
    |--------------------------------------------------------------------------
    */

    public function listarVideos(
        int $conteudoId
    ): array {

        if (
            !$this->idValido(
                $conteudoId
            )
        ) {

            return [];

        }


        return $this->listar(
            $conteudoId,
            "video"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAGEM INTERNA
    |--------------------------------------------------------------------------
    */

    private function listar(
        int $conteudoId,
        ?string $tipo
    ): array {

        /*
        |--------------------------------------------------------------------------
        | CONTEÚDO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $conteudoId
            )
        ) {

            return [];

        }


        /*
        |--------------------------------------------------------------------------
        | TODOS
        |--------------------------------------------------------------------------
        */

        if (
            $tipo === null
        ) {

            $stmt =
                $this->pdo->prepare(
                    "
                        SELECT
                            id,
                            conteudo_id,
                            url,
                            titulo,
                            tipo,
                            criado_em

                        FROM sala_conteudos_links

                        WHERE
                            conteudo_id = :conteudo_id

                        ORDER BY
                            criado_em ASC,
                            id ASC
                    "
                );


            $stmt->execute(
                [
                    ":conteudo_id" =>
                        $conteudoId
                ]
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTRADO POR TIPO
        |--------------------------------------------------------------------------
        */

        else {

            $tipo =
                $this->normalizarTipo(
                    $tipo
                );


            if (
                !$this->tipoValido(
                    $tipo
                )
            ) {

                return [];

            }


            $stmt =
                $this->pdo->prepare(
                    "
                        SELECT
                            id,
                            conteudo_id,
                            url,
                            titulo,
                            tipo,
                            criado_em

                        FROM sala_conteudos_links

                        WHERE
                            conteudo_id = :conteudo_id
                            AND tipo = :tipo

                        ORDER BY
                            criado_em ASC,
                            id ASC
                    "
                );


            $stmt->execute(
                [
                    ":conteudo_id" =>
                        $conteudoId,

                    ":tipo" =>
                        $tipo
                ]
            );

        }


        /*
        |--------------------------------------------------------------------------
        | RESULTADO
        |--------------------------------------------------------------------------
        */

        $recursos =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $recursos
            )
                ?
                $recursos
                :
                [];

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR SE PERTENCE AO CONTEÚDO
    |--------------------------------------------------------------------------
    */

    public function pertenceAoConteudo(
        int $linkId,
        int $conteudoId
    ): bool {

        if (
            !$this->idValido(
                $linkId
            )
            ||
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

                    FROM sala_conteudos_links

                    WHERE
                        id = :link_id
                        AND conteudo_id = :conteudo_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":link_id" =>
                    $linkId,

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
    | ATUALIZAR
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $id,
        string $url,
        ?string $titulo,
        string $tipo
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

        $url =
            $this->normalizarUrl(
                $url
            );


        $titulo =
            $this->normalizarTitulo(
                $titulo
            );


        $tipo =
            $this->normalizarTipo(
                $tipo
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->urlValida(
                $url
            )
            ||
            !$this->tituloValido(
                $titulo
            )
            ||
            !$this->tipoValido(
                $tipo
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
                    UPDATE sala_conteudos_links

                    SET
                        url = :url,
                        titulo = :titulo,
                        tipo = :tipo

                    WHERE
                        id = :id
                "
            );


        if (
            $stmt->execute(
                [
                    ":url" =>
                        $url,

                    ":titulo" =>
                        $titulo,

                    ":tipo" =>
                        $tipo,

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
        | Se os dados forem iguais aos atuais, zero linhas modificadas
        | não significa falha.
        |
        |--------------------------------------------------------------------------
        */

        return
            $this->buscarPorId(
                $id
            )
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR
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
                    DELETE FROM sala_conteudos_links

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
    | EXCLUIR TODOS DO CONTEÚDO
    |--------------------------------------------------------------------------
    |
    | Zero registros removidos não é considerado erro.
    |
    | O objetivo desta operação é:
    |
    | "não deixar nenhum link/vídeo associado ao conteúdo".
    |
    |--------------------------------------------------------------------------
    */

    public function excluirPorConteudo(
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
                    DELETE FROM sala_conteudos_links

                    WHERE
                        conteudo_id = :conteudo_id
                "
            );


        return
            $stmt->execute(
                [
                    ":conteudo_id" =>
                        $conteudoId
                ]
            )
            === true;

    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR LINKS
    |--------------------------------------------------------------------------
    */

    public function contarLinks(
        int $conteudoId
    ): int {

        return $this->contarPorTipo(
            $conteudoId,
            "link"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR VÍDEOS
    |--------------------------------------------------------------------------
    */

    public function contarVideos(
        int $conteudoId
    ): int {

        return $this->contarPorTipo(
            $conteudoId,
            "video"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR POR TIPO
    |--------------------------------------------------------------------------
    */

    private function contarPorTipo(
        int $conteudoId,
        string $tipo
    ): int {

        if (
            !$this->idValido(
                $conteudoId
            )
        ) {

            return 0;

        }


        $tipo =
            $this->normalizarTipo(
                $tipo
            );


        if (
            !$this->tipoValido(
                $tipo
            )
        ) {

            return 0;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        COUNT(*)

                    FROM sala_conteudos_links

                    WHERE
                        conteudo_id = :conteudo_id
                        AND tipo = :tipo
                "
            );


        $stmt->execute(
            [
                ":conteudo_id" =>
                    $conteudoId,

                ":tipo" =>
                    $tipo
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