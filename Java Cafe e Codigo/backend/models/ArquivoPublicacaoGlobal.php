<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: ARQUIVO DE PUBLICAÇÃO GLOBAL
|
| Responsável pelos METADADOS dos arquivos anexados às publicações
| da Sala Global.
|
| Tabela:
|
| publicacoes_arquivos
|
| O arquivo físico é administrado por:
|
| UploadPublicacaoService
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class ArquivoPublicacaoGlobal
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | TAMANHO MÁXIMO
    |--------------------------------------------------------------------------
    |
    | Precisa permanecer alinhado com UploadPublicacaoService.
    |
    | 10 MB por arquivo.
    |
    |--------------------------------------------------------------------------
    */

    private const TAMANHO_MAXIMO =
        10 * 1024 * 1024;


    /*
    |--------------------------------------------------------------------------
    | NOME ORIGINAL
    |--------------------------------------------------------------------------
    */

    private const NOME_ORIGINAL_MAX =
        255;


    /*
    |--------------------------------------------------------------------------
    | MIME
    |--------------------------------------------------------------------------
    */

    private const MIME_MAX =
        150;


    /*
    |--------------------------------------------------------------------------
    | EXTENSÕES PERMITIDAS
    |--------------------------------------------------------------------------
    |
    | Precisa permanecer alinhado com UploadPublicacaoService.
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
    | MIME TYPES PERMITIDOS
    |--------------------------------------------------------------------------
    |
    | Estes valores representam os metadados que o serviço de upload
    | pode gerar depois de validar fisicamente o arquivo.
    |
    |--------------------------------------------------------------------------
    */

    private const MIMES_PERMITIDOS = [

        "pdf" => [

            "application/pdf"

        ],

        "doc" => [

            "application/msword"

        ],

        "docx" => [

            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/zip",
            "application/octet-stream"

        ],

        "ppt" => [

            "application/vnd.ms-powerpoint"

        ],

        "pptx" => [

            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "application/zip",
            "application/octet-stream"

        ],

        "txt" => [

            "text/plain"

        ],

        "png" => [

            "image/png"

        ],

        "jpg" => [

            "image/jpeg"

        ],

        "jpeg" => [

            "image/jpeg"

        ],

        "webp" => [

            "image/webp"

        ]

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
    | NORMALIZAR NOME ORIGINAL
    |--------------------------------------------------------------------------
    */

    private function normalizarNomeOriginal(
        string $nome
    ): string {

        /*
        |--------------------------------------------------------------------------
        | NORMALIZA BARRAS
        |--------------------------------------------------------------------------
        */

        $nome =
            str_replace(
                "\\",
                "/",
                $nome
            );


        /*
        |--------------------------------------------------------------------------
        | REMOVE POSSÍVEL DIRETÓRIO
        |--------------------------------------------------------------------------
        */

        $nome =
            basename(
                trim(
                    $nome
                )
            );


        /*
        |--------------------------------------------------------------------------
        | REMOVE CARACTERES DE CONTROLE
        |--------------------------------------------------------------------------
        */

        $nomeLimpo =
            preg_replace(
                "/[\x00-\x1F\x7F]/",
                "",
                $nome
            );


        if (
            is_string(
                $nomeLimpo
            )
        ) {

            $nome =
                $nomeLimpo;

        }


        return trim(
            $nome
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NOME ORIGINAL VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function nomeOriginalValido(
        string $nomeOriginal,
        string $extensao
    ): bool {

        if (
            $nomeOriginal === ""
        ) {

            return false;

        }


        if (
            $this->comprimento(
                $nomeOriginal
            )
            >
            self::NOME_ORIGINAL_MAX
        ) {

            return false;

        }


        if (
            str_contains(
                $nomeOriginal,
                "\0"
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | EXTENSÃO DO NOME ORIGINAL
        |--------------------------------------------------------------------------
        */

        $extensaoOriginal =
            strtolower(
                trim(
                    pathinfo(
                        $nomeOriginal,
                        PATHINFO_EXTENSION
                    )
                )
            );


        return
            $extensaoOriginal !== ""
            &&
            $extensaoOriginal ===
            $extensao;

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
    | NOME INTERNO VÁLIDO
    |--------------------------------------------------------------------------
    |
    | UploadPublicacaoService gera:
    |
    | 48 caracteres hexadecimais
    | +
    | .
    | +
    | extensão
    |
    | Exemplo:
    |
    | a41f...9c.pdf
    |
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
        | NÃO PODE CONTER DIRETÓRIO
        |--------------------------------------------------------------------------
        */

        if (
            str_contains(
                $nomeArquivo,
                "/"
            )
            ||
            str_contains(
                $nomeArquivo,
                "\\"
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | PADRÃO
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                "/^([a-f0-9]{48})\.([a-z0-9]+)$/D",
                $nomeArquivo,
                $resultado
            )
            !== 1
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | EXTENSÃO
        |--------------------------------------------------------------------------
        */

        $extensaoNome =
            strtolower(
                trim(
                    (string) (
                        $resultado[2]
                        ??
                        ""
                    )
                )
            );


        return
            $extensaoNome ===
            $extensao
            &&
            $this->extensaoValida(
                $extensaoNome
            );

    }


    /*
    |--------------------------------------------------------------------------
    | MIME VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function mimeValido(
        string $extensao,
        string $mimeType
    ): bool {

        if (
            $mimeType === ""
            ||
            strlen(
                $mimeType
            )
            >
            self::MIME_MAX
        ) {

            return false;

        }


        if (
            !isset(
                self::MIMES_PERMITIDOS[
                    $extensao
                ]
            )
        ) {

            return false;

        }


        return in_array(
            $mimeType,
            self::MIMES_PERMITIDOS[
                $extensao
            ],
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | TAMANHO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function tamanhoValido(
        int $tamanho
    ): bool {

        return
            $tamanho > 0
            &&
            $tamanho <=
            self::TAMANHO_MAXIMO;

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
    | CAMINHO VÁLIDO
    |--------------------------------------------------------------------------
    |
    | Para novos arquivos da Sala Global existe somente um padrão:
    |
    | storage/uploads/publicacoes/{nome_arquivo}
    |
    | O Model não aceita caminhos absolutos nem outros diretórios.
    |
    |--------------------------------------------------------------------------
    */

    private function caminhoValido(
        string $caminho,
        string $nomeArquivo
    ): bool {

        if (
            $caminho === ""
            ||
            str_contains(
                $caminho,
                "\0"
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | CAMINHO ABSOLUTO UNIX
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $caminho,
                "/"
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | CAMINHO ABSOLUTO WINDOWS
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                "/^[A-Za-z]:\//",
                $caminho
            )
            === 1
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | PATH TRAVERSAL
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                "#(^|/)\.\.(/|$)#",
                $caminho
            )
            === 1
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | CAMINHO ESPERADO
        |--------------------------------------------------------------------------
        */

        $caminhoEsperado =
            "storage/uploads/publicacoes/"
            .
            $nomeArquivo;


        return hash_equals(
            $caminhoEsperado,
            $caminho
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR ARQUIVO
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $publicacaoId,
        string $nomeOriginal,
        string $nomeArquivo,
        string $extensao,
        string $mimeType,
        int $tamanho,
        string $caminho
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
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $nomeOriginal =
            $this->normalizarNomeOriginal(
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
            !$this->extensaoValida(
                $extensao
            )
            ||
            !$this->nomeOriginalValido(
                $nomeOriginal,
                $extensao
            )
            ||
            !$this->nomeArquivoValido(
                $nomeArquivo,
                $extensao
            )
            ||
            !$this->mimeValido(
                $extensao,
                $mimeType
            )
            ||
            !$this->tamanhoValido(
                $tamanho
            )
            ||
            !$this->caminhoValido(
                $caminho,
                $nomeArquivo
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
                    INSERT INTO publicacoes_arquivos
                    (
                        publicacao_id,
                        nome_original,
                        nome_arquivo,
                        extensao,
                        mime_type,
                        tamanho,
                        caminho
                    )

                    VALUES
                    (
                        :publicacao_id,
                        :nome_original,
                        :nome_arquivo,
                        :extensao,
                        :mime_type,
                        :tamanho,
                        :caminho
                    )
                "
            );


        if (
            $stmt->execute(
                [
                    ":publicacao_id" =>
                        $publicacaoId,

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
    | BUSCAR ARQUIVO PELO ID
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
                        nome_original,
                        nome_arquivo,
                        extensao,
                        mime_type,
                        tamanho,
                        caminho,
                        criado_em

                    FROM publicacoes_arquivos

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
    | LISTAR ARQUIVOS DE UMA PUBLICAÇÃO
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
                        nome_original,
                        nome_arquivo,
                        extensao,
                        mime_type,
                        tamanho,
                        caminho,
                        criado_em

                    FROM publicacoes_arquivos

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
    | CONTAR ARQUIVOS DE UMA PUBLICAÇÃO
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

                    FROM publicacoes_arquivos

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
    | ARQUIVO PERTENCE À PUBLICAÇÃO?
    |--------------------------------------------------------------------------
    */

    public function pertenceAPublicacao(
        int $arquivoId,
        int $publicacaoId
    ): bool {

        if (
            !$this->idValido(
                $arquivoId
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

                    FROM publicacoes_arquivos

                    WHERE
                        id = :arquivo_id
                        AND publicacao_id = :publicacao_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":arquivo_id" =>
                    $arquivoId,

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
    | EXCLUIR REGISTRO DO ARQUIVO
    |--------------------------------------------------------------------------
    |
    | HARD DELETE somente dos METADADOS.
    |
    | Não remove o arquivo físico.
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
                    DELETE FROM publicacoes_arquivos

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


        /*
        |--------------------------------------------------------------------------
        | EXCLUSÃO ESPECÍFICA PRECISA REMOVER UMA LINHA
        |--------------------------------------------------------------------------
        */

        return
            $stmt->rowCount() === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR ARQUIVOS DE UMA PUBLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | HARD DELETE dos METADADOS.
    |
    | Não utilizar para o soft delete normal da publicação.
    |
    | Zero linhas removidas não é erro, porque a publicação pode
    | legitimamente não possuir anexos.
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
                    DELETE FROM publicacoes_arquivos

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
    | TAMANHO TOTAL DOS ARQUIVOS
    |--------------------------------------------------------------------------
    */

    public function tamanhoTotalPublicacao(
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
                        COALESCE(
                            SUM(tamanho),
                            0
                        )

                    FROM publicacoes_arquivos

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
    | NOME INTERNO JÁ EXISTE?
    |--------------------------------------------------------------------------
    */

    public function existeNomeArquivo(
        string $nomeArquivo
    ): bool {

        $nomeArquivo =
            trim(
                $nomeArquivo
            );


        /*
        |--------------------------------------------------------------------------
        | O nome precisa ter o padrão usado pelo serviço.
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                "/^([a-f0-9]{48})\.([a-z0-9]+)$/D",
                $nomeArquivo,
                $resultado
            )
            !== 1
        ) {

            return false;

        }


        $extensao =
            strtolower(
                trim(
                    (string) (
                        $resultado[2]
                        ??
                        ""
                    )
                )
            );


        if (
            !$this->nomeArquivoValido(
                $nomeArquivo,
                $extensao
            )
        ) {

            return false;

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
                        1

                    FROM publicacoes_arquivos

                    WHERE
                        nome_arquivo = :nome_arquivo

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":nome_arquivo" =>
                    $nomeArquivo
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }

}