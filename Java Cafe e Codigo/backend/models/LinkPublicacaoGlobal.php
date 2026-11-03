<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: LINK DE PUBLICAÇÃO GLOBAL
|
| Responsável pelos recursos externos adicionados às publicações
| da Sala Global.
|
| Tabela:
|
| publicacoes_links
|
| Tipos suportados:
|
| - link;
| - video.
|
| Somente URLs HTTP e HTTPS podem ser armazenadas.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class LinkPublicacaoGlobal
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

    private const LIMITE_URL =
        2048;


    private const LIMITE_TITULO =
        150;


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
    | TAMANHO DE TEXTO
    |--------------------------------------------------------------------------
    */

    private function tamanhoTexto(
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
    | VALIDAR TÍTULO RECEBIDO
    |--------------------------------------------------------------------------
    |
    | O título é opcional.
    |
    | Quando informado:
    |
    | - precisa ser UTF-8 válido;
    | - não pode possuir controles;
    | - precisa respeitar o limite.
    |
    |--------------------------------------------------------------------------
    */

    private function tituloEntradaValido(
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


        /*
        |--------------------------------------------------------------------------
        | TAB / ENTER NÃO FAZEM SENTIDO EM TÍTULO
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                "/[\x00-\x1F\x7F]/",
                $titulo
            )
            === 1
        ) {

            return false;

        }


        return true;

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


        $normalizado =
            preg_replace(
                "/\s+/u",
                " ",
                $titulo
            );


        if (
            !is_string(
                $normalizado
            )
        ) {

            return null;

        }


        $normalizado =
            trim(
                $normalizado
            );


        return
            $normalizado !== ""
                ?
                $normalizado
                :
                null;

    }


    /*
    |--------------------------------------------------------------------------
    | TÍTULO NORMALIZADO VÁLIDO
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


        return
            $this->utf8Valido(
                $titulo
            )
            &&
            $this->tamanhoTexto(
                $titulo
            )
            <=
            self::LIMITE_TITULO;

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
    | VALIDAR URL
    |--------------------------------------------------------------------------
    |
    | Este Model NÃO acessa a URL.
    |
    | Portanto não existe SSRF aqui.
    |
    | Apenas validamos o endereço que será armazenado.
    |
    | Permitidos:
    |
    | https://...
    | http://...
    |
    | Bloqueados:
    |
    | javascript:
    | data:
    | file:
    | ftp:
    | outros esquemas.
    |
    |--------------------------------------------------------------------------
    */

    private function urlValida(
        string $url
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | TAMANHO / CONTEÚDO
        |--------------------------------------------------------------------------
        */

        if (
            $url === ""
            ||
            strlen(
                $url
            )
            >
            self::LIMITE_URL
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | CARACTERES DE CONTROLE
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                "/[\x00-\x20\x7F]/",
                $url
            )
            === 1
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | URL
        |--------------------------------------------------------------------------
        */

        if (
            filter_var(
                $url,
                FILTER_VALIDATE_URL
            )
            === false
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | COMPONENTES
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | HOST
        |--------------------------------------------------------------------------
        */

        if (
            $host === ""
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | PROTOCOLO
        |--------------------------------------------------------------------------
        */

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
    | CRIAR LINK
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $publicacaoId,
        string $url,
        ?string $titulo = null,
        string $tipo = "link"
    ): int|false {

        /*
        |--------------------------------------------------------------------------
        | PUBLICAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $publicacaoId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | TÍTULO ORIGINAL
        |--------------------------------------------------------------------------
        |
        | Validamos antes da normalização para não transformar uma entrada
        | inválida silenciosamente em NULL.
        |
        |--------------------------------------------------------------------------
        */

        if (
            !$this->tituloEntradaValido(
                $titulo
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
                    INSERT INTO publicacoes_links
                    (
                        publicacao_id,
                        url,
                        titulo,
                        tipo
                    )

                    VALUES
                    (
                        :publicacao_id,
                        :url,
                        :titulo,
                        :tipo
                    )
                "
            );


        if (
            $stmt->execute(
                [
                    ":publicacao_id" =>
                        $publicacaoId,

                    ":url" =>
                        $url,

                    ":titulo" =>
                        $titulo,

                    ":tipo" =>
                        $tipo
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
    | BUSCAR PELO ID
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
                        publicacao_id,
                        url,
                        titulo,
                        tipo,
                        criado_em

                    FROM publicacoes_links

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
    | LISTAR LINKS DA PUBLICAÇÃO
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
                        id,
                        publicacao_id,
                        url,
                        titulo,
                        tipo,
                        criado_em

                    FROM publicacoes_links

                    WHERE
                        publicacao_id = :publicacao_id

                    ORDER BY
                        criado_em ASC,
                        id ASC
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
    | LISTAR APENAS VÍDEOS
    |--------------------------------------------------------------------------
    */

    public function listarVideos(
        int $publicacaoId
    ): array {

        return $this->listarPorTipo(
            $publicacaoId,
            "video"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR LINKS NORMAIS
    |--------------------------------------------------------------------------
    */

    public function listarLinks(
        int $publicacaoId
    ): array {

        return $this->listarPorTipo(
            $publicacaoId,
            "link"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR LINKS DA PUBLICAÇÃO
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

                    FROM publicacoes_links

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
    | CONTAR VÍDEOS
    |--------------------------------------------------------------------------
    */

    public function contarVideos(
        int $publicacaoId
    ): int {

        return $this->contarPorTipo(
            $publicacaoId,
            "video"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR SE PERTENCE À PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    public function pertenceAPublicacao(
        int $linkId,
        int $publicacaoId
    ): bool {

        if (
            !$this->idValido(
                $linkId
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

                    FROM publicacoes_links

                    WHERE
                        id = :link_id
                        AND publicacao_id = :publicacao_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":link_id" =>
                    $linkId,

                ":publicacao_id" =>
                    $publicacaoId
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR LINK
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
        | TÍTULO ORIGINAL
        |--------------------------------------------------------------------------
        */

        if (
            !$this->tituloEntradaValido(
                $titulo
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
                    UPDATE publicacoes_links

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
        | Salvar exatamente os mesmos valores continua válido.
        |
        | Porém precisamos confirmar que o registro realmente existe.
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
    | EXCLUIR LINK
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
                    DELETE FROM publicacoes_links

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
    | EXCLUIR LINKS DA PUBLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | Como a Sala Global utiliza soft delete, este método NÃO deve ser
    | chamado simplesmente quando uma publicação recebe:
    |
    | status = removido
    |
    | Ele fica reservado para hard delete/limpeza permanente.
    |
    | Zero registros removidos não é erro.
    |
    |--------------------------------------------------------------------------
    */

    public function excluirPorPublicacao(
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
                    DELETE FROM publicacoes_links

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
    | LISTAR POR TIPO
    |--------------------------------------------------------------------------
    */

    private function listarPorTipo(
        int $publicacaoId,
        string $tipo
    ): array {

        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

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
            !$this->idValido(
                $publicacaoId
            )
            ||
            !$this->tipoValido(
                $tipo
            )
        ) {

            return [];

        }


        /*
        |--------------------------------------------------------------------------
        | CONSULTA
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        id,
                        publicacao_id,
                        url,
                        titulo,
                        tipo,
                        criado_em

                    FROM publicacoes_links

                    WHERE
                        publicacao_id = :publicacao_id
                        AND tipo = :tipo

                    ORDER BY
                        criado_em ASC,
                        id ASC
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId,

                ":tipo" =>
                    $tipo
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
    | CONTAR POR TIPO
    |--------------------------------------------------------------------------
    */

    private function contarPorTipo(
        int $publicacaoId,
        string $tipo
    ): int {

        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

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
            !$this->idValido(
                $publicacaoId
            )
            ||
            !$this->tipoValido(
                $tipo
            )
        ) {

            return 0;

        }


        /*
        |--------------------------------------------------------------------------
        | CONSULTA
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        COUNT(*)

                    FROM publicacoes_links

                    WHERE
                        publicacao_id = :publicacao_id
                        AND tipo = :tipo
                "
            );


        $stmt->execute(
            [
                ":publicacao_id" =>
                    $publicacaoId,

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