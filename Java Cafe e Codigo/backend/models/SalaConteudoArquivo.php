<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: ARQUIVO DE CONTEÚDO DA SALA
|
| Responsável pelos metadados dos arquivos anexados aos conteúdos
| das Salas de Aula.
|
| Tabela:
|
| sala_conteudos_arquivos
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class SalaConteudoArquivo
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | EXTENSÕES PERMITIDAS
    |--------------------------------------------------------------------------
    |
    | Precisa permanecer alinhado com:
    |
    | - SalaController;
    | - fluxo de upload das Salas de Aula;
    | - endpoints protegidos de download.
    |
    |--------------------------------------------------------------------------
    */

    private const EXTENSOES_PERMITIDAS = [

        "pdf",

        "doc",
        "docx",

        "ppt",
        "pptx",

        "txt",

        "png",
        "jpg",
        "jpeg",
        "webp"

    ];


    /*
    |--------------------------------------------------------------------------
    | TAMANHO MÁXIMO
    |--------------------------------------------------------------------------
    |
    | 10 MB.
    |
    |--------------------------------------------------------------------------
    */

    private const TAMANHO_MAXIMO =
        10 * 1024 * 1024;


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
    | NORMALIZAR CAMINHO
    |--------------------------------------------------------------------------
    */

    private function normalizarCaminho(
        string $caminho
    ): string {

        return str_replace(
            "\\",
            "/",
            trim(
                $caminho
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NOME ORIGINAL VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function nomeOriginalValido(
        string $nome
    ): bool {

        if (
            $nome === ""
            ||
            !$this->utf8Valido(
                $nome
            )
        ) {

            return false;

        }


        if (
            str_contains(
                $nome,
                "\0"
            )
        ) {

            return false;

        }


        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | NOME INTERNO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function nomeArquivoValido(
        string $nomeArquivo,
        string $extensao
    ): bool {

        if (
            $nomeArquivo === ""
            ||
            str_contains(
                $nomeArquivo,
                "\0"
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NOME INTERNO NÃO PODE CONTER CAMINHO
        |--------------------------------------------------------------------------
        */

        if (
            basename(
                str_replace(
                    "\\",
                    "/",
                    $nomeArquivo
                )
            )
            !==
            $nomeArquivo
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | EXTENSÃO PRECISA CONFERIR
        |--------------------------------------------------------------------------
        */

        $extensaoNome =
            strtolower(
                trim(
                    pathinfo(
                        $nomeArquivo,
                        PATHINFO_EXTENSION
                    )
                )
            );


        return
            $extensaoNome !== ""
            &&
            $extensaoNome === $extensao;

    }


    /*
    |--------------------------------------------------------------------------
    | EXTENSÃO VÁLIDA
    |--------------------------------------------------------------------------
    */

    private function extensaoValida(
        string $extensao
    ): bool {

        return in_array(
            $extensao,
            self::EXTENSOES_PERMITIDAS,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MIME VÁLIDO COMO METADADO
    |--------------------------------------------------------------------------
    |
    | A validação física do MIME continua sendo feita durante
    | upload/download.
    |
    | Aqui apenas impedimos metadados estruturalmente inválidos.
    |
    |--------------------------------------------------------------------------
    */

    private function mimeValido(
        string $mimeType
    ): bool {

        if (
            $mimeType === ""
            ||
            strlen(
                $mimeType
            )
            > 150
        ) {

            return false;

        }


        return preg_match(
            "/^[A-Za-z0-9!#$&^_.+\-]+\/[A-Za-z0-9!#$&^_.+\-]+$/D",
            $mimeType
        ) === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | CAMINHO VÁLIDO
    |--------------------------------------------------------------------------
    |
    | O banco armazena caminho relativo ao projeto.
    |
    | Não permitimos:
    |
    | - caminho absoluto Unix;
    | - caminho absoluto Windows;
    | - ../;
    | - byte nulo.
    |
    |--------------------------------------------------------------------------
    */

    private function caminhoValido(
        string $caminho
    ): bool {

        if (
            $caminho === ""
        ) {

            return false;

        }


        if (
            str_contains(
                $caminho,
                "\0"
            )
        ) {

            return false;

        }


        if (
            str_starts_with(
                $caminho,
                "/"
            )
        ) {

            return false;

        }


        if (
            preg_match(
                "/^[A-Za-z]:\//",
                $caminho
            )
            === 1
        ) {

            return false;

        }


        if (
            preg_match(
                "#(^|/)\.\.(/|$)#",
                $caminho
            )
            === 1
        ) {

            return false;

        }


        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $conteudoId,
        string $nomeOriginal,
        string $nomeArquivo,
        string $extensao,
        string $mimeType,
        int $tamanho,
        string $caminho
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

        $nomeOriginal =
            trim(
                $nomeOriginal
            );


        $nomeArquivo =
            trim(
                $nomeArquivo
            );


        $extensao =
            strtolower(
                trim(
                    $extensao
                )
            );


        $mimeType =
            strtolower(
                trim(
                    $mimeType
                )
            );


        $caminho =
            $this->normalizarCaminho(
                $caminho
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->nomeOriginalValido(
                $nomeOriginal
            )
            ||
            !$this->extensaoValida(
                $extensao
            )
            ||
            !$this->nomeArquivoValido(
                $nomeArquivo,
                $extensao
            )
            ||
            !$this->mimeValido(
                $mimeType
            )
            ||
            $tamanho <= 0
            ||
            $tamanho > self::TAMANHO_MAXIMO
            ||
            !$this->caminhoValido(
                $caminho
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
                    INSERT INTO sala_conteudos_arquivos
                    (
                        conteudo_id,
                        nome_original,
                        nome_arquivo,
                        extensao,
                        mime_type,
                        tamanho,
                        caminho
                    )

                    VALUES
                    (
                        :conteudo_id,
                        :nome_original,
                        :nome_arquivo,
                        :extensao,
                        :mime_type,
                        :tamanho,
                        :caminho
                    )
                "
            );


        $resultado =
            $stmt->execute(
                [
                    ":conteudo_id" =>
                        $conteudoId,

                    ":nome_original" =>
                        $nomeOriginal,

                    ":nome_arquivo" =>
                        $nomeArquivo,

                    ":extensao" =>
                        $extensao,

                    ":mime_type" =>
                        $mimeType,

                    ":tamanho" =>
                        $tamanho,

                    ":caminho" =>
                        $caminho
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

        $arquivoId =
            (int) $this->pdo->lastInsertId();


        return
            $arquivoId > 0
                ?
                $arquivoId
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
                        nome_original,
                        nome_arquivo,
                        extensao,
                        mime_type,
                        tamanho,
                        caminho,
                        criado_em

                    FROM sala_conteudos_arquivos

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


        $arquivo =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $arquivo
            )
                ?
                $arquivo
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR ARQUIVO COM CONTEXTO DA SALA
    |--------------------------------------------------------------------------
    |
    | Esta consulta permite validar diretamente:
    |
    | arquivo
    | → conteúdo
    | → sala
    | → professor
    |
    | sem carregar todos os conteúdos e anexos da sala.
    |
    | Poderemos utilizar este método nos endpoints:
    |
    | - sala_arquivo.php;
    | - professor_sala_arquivo.php.
    |
    |--------------------------------------------------------------------------
    */

    public function buscarComContexto(
        int $arquivoId
    ): array|false {

        if (
            !$this->idValido(
                $arquivoId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        a.id,
                        a.conteudo_id,
                        a.nome_original,
                        a.nome_arquivo,
                        a.extensao,
                        a.mime_type,
                        a.tamanho,
                        a.caminho,
                        a.criado_em,

                        c.sala_id,
                        c.usuario_id AS conteudo_usuario_id,
                        c.status AS conteudo_status,

                        s.professor_id

                    FROM sala_conteudos_arquivos a

                    INNER JOIN sala_conteudos c
                        ON c.id = a.conteudo_id

                    INNER JOIN salas s
                        ON s.id = c.sala_id

                    WHERE
                        a.id = :arquivo_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":arquivo_id" =>
                    $arquivoId
            ]
        );


        $arquivo =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $arquivo
            )
                ?
                $arquivo
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


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        id,
                        conteudo_id,
                        nome_original,
                        nome_arquivo,
                        extensao,
                        mime_type,
                        tamanho,
                        caminho,
                        criado_em

                    FROM sala_conteudos_arquivos

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


        $arquivos =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $arquivos
            )
                ?
                $arquivos
                :
                [];

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR SE PERTENCE AO CONTEÚDO
    |--------------------------------------------------------------------------
    */

    public function pertenceAoConteudo(
        int $arquivoId,
        int $conteudoId
    ): bool {

        if (
            !$this->idValido(
                $arquivoId
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

                    FROM sala_conteudos_arquivos

                    WHERE
                        id = :arquivo_id
                        AND conteudo_id = :conteudo_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":arquivo_id" =>
                    $arquivoId,

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
    | EXCLUIR
    |--------------------------------------------------------------------------
    |
    | Remove somente o METADADO do banco.
    |
    | A camada superior precisa remover também o arquivo físico.
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
                    DELETE FROM sala_conteudos_arquivos

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
    | Esta operação representa:
    |
    | "garantir que não existam mais metadados de arquivos
    |  vinculados ao conteúdo".
    |
    | Por isso zero linhas não é considerado erro.
    |
    | ATENÇÃO:
    |
    | Isso NÃO remove os arquivos físicos.
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
                    DELETE FROM sala_conteudos_arquivos

                    WHERE
                        conteudo_id = :conteudo_id
                "
            );


        return $stmt->execute(
            [
                ":conteudo_id" =>
                    $conteudoId
            ]
        ) === true;

    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR
    |--------------------------------------------------------------------------
    */

    public function contarPorConteudo(
        int $conteudoId
    ): int {

        if (
            !$this->idValido(
                $conteudoId
            )
        ) {

            return 0;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        COUNT(*)

                    FROM sala_conteudos_arquivos

                    WHERE
                        conteudo_id = :conteudo_id
                "
            );


        $stmt->execute(
            [
                ":conteudo_id" =>
                    $conteudoId
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