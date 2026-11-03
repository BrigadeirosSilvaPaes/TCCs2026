<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../../../backend/config/app.php";
require_once __DIR__ . "/../../../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../../../backend/config/helpers.php";

require_once __DIR__ . "/../../../../../backend/Controller/PerfilController.php";
require_once __DIR__ . "/../../../../../backend/Controller/SalaController.php";


/*
|--------------------------------------------------------------------------
| SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| ROTAS
|--------------------------------------------------------------------------
*/

$linkLogin =
    rota(
        "login"
    );


$linkInicio =
    rota(
        "inicio"
    );


$linkEscolherPerfil =
    rota(
        "perfil.escolher"
    );


$linkSalas =
    rota(
        "professor.salas"
    );


$linkPainel =
    rota(
        "professor.painel"
    );


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function salaAbrirEscapar(
    mixed $valor
): string {

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );

}


/*
|--------------------------------------------------------------------------
| REDIRECIONAR
|--------------------------------------------------------------------------
*/

function salaAbrirRedirecionar(
    string $url,
    int $codigo = 302
): never {

    header(
        "Location: " . $url,
        true,
        $codigo
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| VERSÃO DE ASSET
|--------------------------------------------------------------------------
*/

function salaAbrirVersaoAsset(
    string $arquivo
): int {

    if (
        !is_file(
            $arquivo
        )
    ) {

        return 1;

    }


    $versao =
        @filemtime(
            $arquivo
        );


    return $versao !== false
        ?
        (int) $versao
        :
        1;

}


/*
|--------------------------------------------------------------------------
| FORMATAR DATA
|--------------------------------------------------------------------------
*/

function salaAbrirFormatarData(
    mixed $data
): string {

    $data =
        trim(
            (string) $data
        );


    if (
        $data === ""
    ) {

        return "Data não informada";

    }


    try {

        $objetoData =
            new DateTime(
                $data
            );


        return $objetoData->format(
            "d/m/Y \à\s H:i"
        );

    }
    catch (
        Throwable $erro
    ) {

        return $data;

    }

}


/*
|--------------------------------------------------------------------------
| CATEGORIA
|--------------------------------------------------------------------------
*/

function salaAbrirCategoriaTexto(
    mixed $categoria
): string {

    $categoria =
        strtolower(
            trim(
                (string) $categoria
            )
        );


    return match (
        $categoria
    ) {

        "aviso" =>
            "Aviso",

        "explicacao" =>
            "Explicação",

        "codigo" =>
            "Código",

        "exercicio" =>
            "Exercício",

        "material" =>
            "Material",

        "video" =>
            "Vídeo",

        "link" =>
            "Link",

        default =>
            "Outro"

    };

}


/*
|--------------------------------------------------------------------------
| STATUS
|--------------------------------------------------------------------------
*/

function salaAbrirStatusTexto(
    mixed $status
): string {

    $status =
        strtolower(
            trim(
                (string) $status
            )
        );


    return match (
        $status
    ) {

        "publicado" =>
            "Publicado",

        "rascunho" =>
            "Rascunho",

        "oculto" =>
            "Oculto",

        default =>
            "Desconhecido"

    };

}


/*
|--------------------------------------------------------------------------
| TAMANHO
|--------------------------------------------------------------------------
*/

function salaAbrirFormatarTamanho(
    int $bytes
): string {

    if (
        $bytes <= 0
    ) {

        return "0 B";

    }


    if (
        $bytes < 1024
    ) {

        return $bytes . " B";

    }


    if (
        $bytes >= 1073741824
    ) {

        return
            number_format(
                $bytes / 1073741824,
                2,
                ",",
                "."
            )
            .
            " GB";

    }


    if (
        $bytes >= 1048576
    ) {

        return
            number_format(
                $bytes / 1048576,
                2,
                ",",
                "."
            )
            .
            " MB";

    }


    return
        number_format(
            $bytes / 1024,
            1,
            ",",
            "."
        )
        .
        " KB";

}


/*
|--------------------------------------------------------------------------
| URL EXTERNA SEGURA
|--------------------------------------------------------------------------
*/

function salaAbrirUrlExterna(
    mixed $valor
): ?string {

    $url =
        trim(
            (string) $valor
        );


    if (
        $url === ""
        ||
        filter_var(
            $url,
            FILTER_VALIDATE_URL
        )
        === false
    ) {

        return null;

    }


    $partes =
        parse_url(
            $url
        );


    if (
        !is_array(
            $partes
        )
        ||
        empty(
            $partes["scheme"]
        )
        ||
        empty(
            $partes["host"]
        )
    ) {

        return null;

    }


    $esquema =
        strtolower(
            (string) $partes["scheme"]
        );


    if (
        !in_array(
            $esquema,
            [
                "http",
                "https"
            ],
            true
        )
    ) {

        return null;

    }


    return $url;

}


/*
|--------------------------------------------------------------------------
| ORIGEM DO POST
|--------------------------------------------------------------------------
*/

function salaAbrirOrigemPostValida(): bool {

    $origem =
        strtolower(
            trim(
                (string) (
                    $_SERVER["HTTP_SEC_FETCH_SITE"]
                    ??
                    ""
                )
            )
        );


    if (
        $origem === ""
    ) {

        return true;

    }


    return in_array(
        $origem,
        [
            "same-origin",
            "same-site",
            "none"
        ],
        true
    );

}


/*
|--------------------------------------------------------------------------
| NORMALIZAR CAMINHO
|--------------------------------------------------------------------------
*/

function salaAbrirNormalizarCaminho(
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
| CAMINHO DENTRO DA PASTA
|--------------------------------------------------------------------------
*/

function salaAbrirCaminhoPermitido(
    string $arquivo,
    string $diretorio
): bool {

    $arquivo =
        salaAbrirNormalizarCaminho(
            $arquivo
        );


    $diretorio =
        rtrim(
            salaAbrirNormalizarCaminho(
                $diretorio
            ),
            "/"
        );


    if (
        DIRECTORY_SEPARATOR === "\\"
    ) {

        $arquivo =
            strtolower(
                $arquivo
            );


        $diretorio =
            strtolower(
                $diretorio
            );

    }


    return (
        $arquivo ===
        $diretorio
        ||
        str_starts_with(
            $arquivo,
            $diretorio . "/"
        )
    );

}


/*
|--------------------------------------------------------------------------
| EXCLUIR ARQUIVO FÍSICO
|--------------------------------------------------------------------------
|
| Compatibilidade:
|
| - arquivos antigos:
|   frontend/uploads/salas/...
|
| - arquivos novos:
|   backend/storage/uploads/salas/...
|
|--------------------------------------------------------------------------
*/

function salaAbrirExcluirArquivoFisico(
    string $caminho,
    string $raizProjeto
): void {

    $caminho =
        salaAbrirNormalizarCaminho(
            $caminho
        );


    if (
        $caminho === ""
        ||
        str_contains(
            $caminho,
            "\0"
        )
        ||
        str_starts_with(
            $caminho,
            "/"
        )
        ||
        preg_match(
            "/^[A-Za-z]:\//",
            $caminho
        )
        === 1
        ||
        preg_match(
            "#(^|/)\.\.(/|$)#",
            $caminho
        )
        === 1
    ) {

        return;

    }


    $arquivoCandidato =
        $raizProjeto
        .
        DIRECTORY_SEPARATOR
        .
        str_replace(
            "/",
            DIRECTORY_SEPARATOR,
            ltrim(
                $caminho,
                "/"
            )
        );


    $arquivoReal =
        realpath(
            $arquivoCandidato
        );


    if (
        $arquivoReal === false
        ||
        !is_file(
            $arquivoReal
        )
        ||
        is_link(
            $arquivoReal
        )
    ) {

        return;

    }


    $bases = [

        $raizProjeto
        .
        DIRECTORY_SEPARATOR
        .
        "frontend"
        .
        DIRECTORY_SEPARATOR
        .
        "uploads"
        .
        DIRECTORY_SEPARATOR
        .
        "salas",

        $raizProjeto
        .
        DIRECTORY_SEPARATOR
        .
        "backend"
        .
        DIRECTORY_SEPARATOR
        .
        "storage"
        .
        DIRECTORY_SEPARATOR
        .
        "uploads"
        .
        DIRECTORY_SEPARATOR
        .
        "salas"

    ];


    foreach (
        $bases as $base
    ) {

        $baseReal =
            realpath(
                $base
            );


        if (
            $baseReal !== false
            &&
            salaAbrirCaminhoPermitido(
                $arquivoReal,
                $baseReal
            )
        ) {

            @unlink(
                $arquivoReal
            );


            return;

        }

    }

}


/*
|--------------------------------------------------------------------------
| NOME ORIGINAL SEGURO
|--------------------------------------------------------------------------
*/

function salaAbrirNomeOriginal(
    mixed $nome
): string {

    $nome =
        salaAbrirNormalizarCaminho(
            (string) $nome
        );


    $nome =
        basename(
            $nome
        );


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
| MIME PERMITIDO POR EXTENSÃO
|--------------------------------------------------------------------------
*/

function salaAbrirMimePermitido(
    string $extensao,
    string $mime
): bool {

    $extensao =
        strtolower(
            trim(
                $extensao
            )
        );


    $mime =
        strtolower(
            trim(
                $mime
            )
        );


    $mimes = [

        "pdf" => [
            "application/pdf"
        ],

        "doc" => [
            "application/msword",
            "application/vnd.ms-office",
            "application/x-ole-storage",
            "application/octet-stream"
        ],

        "docx" => [
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/zip",
            "application/x-zip",
            "application/octet-stream"
        ],

        "ppt" => [
            "application/vnd.ms-powerpoint",
            "application/vnd.ms-office",
            "application/x-ole-storage",
            "application/octet-stream"
        ],

        "pptx" => [
            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "application/zip",
            "application/x-zip",
            "application/octet-stream"
        ],

        "txt" => [
            "text/plain",
            "application/octet-stream"
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


    return (
        isset(
            $mimes[
                $extensao
            ]
        )
        &&
        in_array(
            $mime,
            $mimes[
                $extensao
            ],
            true
        )
    );

}


/*
|--------------------------------------------------------------------------
| ASSINATURA OLE
|--------------------------------------------------------------------------
*/

function salaAbrirAssinaturaOleValida(
    string $arquivo
): bool {

    $handle =
        @fopen(
            $arquivo,
            "rb"
        );


    if (
        $handle === false
    ) {

        return false;

    }


    $assinatura =
        fread(
            $handle,
            8
        );


    fclose(
        $handle
    );


    return (
        is_string(
            $assinatura
        )
        &&
        $assinatura ===
        "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1"
    );

}


/*
|--------------------------------------------------------------------------
| VALIDAR PDF
|--------------------------------------------------------------------------
*/

function salaAbrirPdfValido(
    string $arquivo
): bool {

    $handle =
        @fopen(
            $arquivo,
            "rb"
        );


    if (
        $handle === false
    ) {

        return false;

    }


    $inicio =
        fread(
            $handle,
            5
        );


    if (
        $inicio !==
        "%PDF-"
    ) {

        fclose(
            $handle
        );


        return false;

    }


    $tamanho =
        @filesize(
            $arquivo
        );


    if (
        $tamanho === false
        ||
        $tamanho <= 0
    ) {

        fclose(
            $handle
        );


        return false;

    }


    $ler =
        min(
            4096,
            (int) $tamanho
        );


    fseek(
        $handle,
        -$ler,
        SEEK_END
    );


    $fim =
        fread(
            $handle,
            $ler
        );


    fclose(
        $handle
    );


    return (
        is_string(
            $fim
        )
        &&
        str_contains(
            $fim,
            "%%EOF"
        )
    );

}


/*
|--------------------------------------------------------------------------
| VALIDAR OFFICE OPEN XML
|--------------------------------------------------------------------------
|
| DOCX e PPTX são arquivos ZIP estruturados.
|
|--------------------------------------------------------------------------
*/

function salaAbrirOfficeZipValido(
    string $arquivo,
    string $extensao
): bool {

    if (
        !class_exists(
            "ZipArchive"
        )
    ) {

        /*
        |--------------------------------------------------------------------------
        | Ainda validamos a assinatura ZIP quando ZipArchive não existir.
        |--------------------------------------------------------------------------
        */

        $handle =
            @fopen(
                $arquivo,
                "rb"
            );


        if (
            $handle === false
        ) {

            return false;

        }


        $assinatura =
            fread(
                $handle,
                4
            );


        fclose(
            $handle
        );


        return (
            $assinatura ===
            "PK\x03\x04"
        );

    }


    $zip =
        new ZipArchive();


    $resultado =
        $zip->open(
            $arquivo
        );


    if (
        $resultado !==
        true
    ) {

        return false;

    }


    $arquivoPrincipal =
        $extensao ===
        "docx"
            ?
            "word/document.xml"
            :
            "ppt/presentation.xml";


    $possuiPrincipal =
        $zip->locateName(
            $arquivoPrincipal
        )
        !==
        false;


    /*
    |--------------------------------------------------------------------------
    | BLOQUEIA MACROS
    |--------------------------------------------------------------------------
    */

    $possuiMacro =
        false;


    for (
        $indice = 0;
        $indice < $zip->numFiles;
        $indice++
    ) {

        $nome =
            strtolower(
                (string) $zip->getNameIndex(
                    $indice
                )
            );


        if (
            str_ends_with(
                $nome,
                "vbaproject.bin"
            )
        ) {

            $possuiMacro =
                true;


            break;

        }

    }


    $zip->close();


    return (
        $possuiPrincipal
        &&
        !$possuiMacro
    );

}


/*
|--------------------------------------------------------------------------
| VALIDAR IMAGEM
|--------------------------------------------------------------------------
*/

function salaAbrirImagemValida(
    string $arquivo,
    string $extensao
): bool {

    $dados =
        @getimagesize(
            $arquivo
        );


    if (
        !is_array(
            $dados
        )
        ||
        empty(
            $dados[0]
        )
        ||
        empty(
            $dados[1]
        )
    ) {

        return false;

    }


    $largura =
        (int) $dados[0];


    $altura =
        (int) $dados[1];


    if (
        $largura <= 0
        ||
        $altura <= 0
        ||
        $largura > 12000
        ||
        $altura > 12000
        ||
        (
            $largura *
            $altura
        )
        >
        40000000
    ) {

        return false;

    }


    $mime =
        strtolower(
            trim(
                (string) (
                    $dados["mime"]
                    ??
                    ""
                )
            )
        );


    return match (
        $extensao
    ) {

        "png" =>
            $mime === "image/png",

        "jpg",
        "jpeg" =>
            $mime === "image/jpeg",

        "webp" =>
            $mime === "image/webp",

        default =>
            false

    };

}


/*
|--------------------------------------------------------------------------
| PROCESSAR DOCUMENTO
|--------------------------------------------------------------------------
*/

function salaAbrirProcessarDocumento(

    array $arquivo,

    int $salaId,

    int $conteudoId,

    int $professorId,

    SalaController $salaController,

    string $raizProjeto

): array {

    /*
    |--------------------------------------------------------------------------
    | SEM ARQUIVO
    |--------------------------------------------------------------------------
    */

    $erroUpload =
        (int) (
            $arquivo["error"]
            ??
            UPLOAD_ERR_NO_FILE
        );


    if (
        $erroUpload ===
        UPLOAD_ERR_NO_FILE
    ) {

        return [
            "status" => true,
            "ignorado" => true
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | ERRO
    |--------------------------------------------------------------------------
    */

    if (
        $erroUpload !==
        UPLOAD_ERR_OK
    ) {

        return [
            "status" => false,
            "mensagem" => "O documento não pôde ser enviado."
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | TEMPORÁRIO
    |--------------------------------------------------------------------------
    */

    $temporario =
        (string) (
            $arquivo["tmp_name"]
            ??
            ""
        );


    if (
        $temporario === ""
        ||
        !is_uploaded_file(
            $temporario
        )
    ) {

        return [
            "status" => false,
            "mensagem" => "O documento enviado é inválido."
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | TAMANHO REAL
    |--------------------------------------------------------------------------
    */

    $tamanho =
        @filesize(
            $temporario
        );


    $limite =
        10 *
        1024 *
        1024;


    if (
        $tamanho === false
        ||
        $tamanho <= 0
        ||
        $tamanho > $limite
    ) {

        return [
            "status" => false,
            "mensagem" => "O documento deve possuir no máximo 10 MB."
        ];

    }


    $tamanho =
        (int) $tamanho;


    /*
    |--------------------------------------------------------------------------
    | NOME
    |--------------------------------------------------------------------------
    */

    $nomeOriginal =
        salaAbrirNomeOriginal(
            $arquivo["name"]
            ??
            ""
        );


    if (
        $nomeOriginal === ""
    ) {

        return [
            "status" => false,
            "mensagem" => "O arquivo precisa possuir um nome."
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | EXTENSÃO
    |--------------------------------------------------------------------------
    */

    $extensao =
        strtolower(
            pathinfo(
                $nomeOriginal,
                PATHINFO_EXTENSION
            )
        );


    $extensoesPermitidas = [

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


    if (
        !in_array(
            $extensao,
            $extensoesPermitidas,
            true
        )
    ) {

        return [
            "status" => false,
            "mensagem" => "Formato de documento não permitido."
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | MIME REAL
    |--------------------------------------------------------------------------
    */

    if (
        !class_exists(
            "finfo"
        )
    ) {

        return [
            "status" => false,
            "mensagem" => "Não foi possível validar o tipo do documento."
        ];

    }


    try {

        $finfo =
            new finfo(
                FILEINFO_MIME_TYPE
            );


        $mimeType =
            $finfo->file(
                $temporario
            );

    }
    catch (
        Throwable $erro
    ) {

        return [
            "status" => false,
            "mensagem" => "Não foi possível validar o documento."
        ];

    }


    if (
        !is_string(
            $mimeType
        )
        ||
        trim(
            $mimeType
        )
        === ""
    ) {

        return [
            "status" => false,
            "mensagem" => "Não foi possível identificar o tipo do documento."
        ];

    }


    $mimeType =
        strtolower(
            trim(
                $mimeType
            )
        );


    if (
        !salaAbrirMimePermitido(
            $extensao,
            $mimeType
        )
    ) {

        return [
            "status" => false,
            "mensagem" => "O conteúdo do arquivo não corresponde ao formato informado."
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAÇÕES ESPECÍFICAS
    |--------------------------------------------------------------------------
    */

    if (
        $extensao === "pdf"
        &&
        !salaAbrirPdfValido(
            $temporario
        )
    ) {

        return [
            "status" => false,
            "mensagem" => "O arquivo PDF informado é inválido."
        ];

    }


    if (
        in_array(
            $extensao,
            [
                "doc",
                "ppt"
            ],
            true
        )
        &&
        !salaAbrirAssinaturaOleValida(
            $temporario
        )
    ) {

        return [
            "status" => false,
            "mensagem" => "O documento antigo do Office não pôde ser validado."
        ];

    }


    if (
        in_array(
            $extensao,
            [
                "docx",
                "pptx"
            ],
            true
        )
        &&
        !salaAbrirOfficeZipValido(
            $temporario,
            $extensao
        )
    ) {

        return [
            "status" => false,
            "mensagem" => "O documento do Office não pôde ser validado."
        ];

    }


    if (
        in_array(
            $extensao,
            [
                "png",
                "jpg",
                "jpeg",
                "webp"
            ],
            true
        )
        &&
        !salaAbrirImagemValida(
            $temporario,
            $extensao
        )
    ) {

        return [
            "status" => false,
            "mensagem" => "A imagem enviada não pôde ser validada."
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | TXT
    |--------------------------------------------------------------------------
    */

    if (
        $extensao ===
        "txt"
    ) {

        $amostra =
            @file_get_contents(
                $temporario,
                false,
                null,
                0,
                min(
                    8192,
                    $tamanho
                )
            );


        if (
            !is_string(
                $amostra
            )
            ||
            str_contains(
                $amostra,
                "\0"
            )
        ) {

            return [
                "status" => false,
                "mensagem" => "O arquivo TXT enviado é inválido."
            ];

        }

    }


    /*
    |--------------------------------------------------------------------------
    | ARMAZENAMENTO PROTEGIDO
    |--------------------------------------------------------------------------
    |
    | NOVO:
    |
    | backend/storage/uploads/salas/{sala}/conteudos/{conteudo}/
    |
    |--------------------------------------------------------------------------
    */

    $diretorioRelativo =
        "backend/storage/uploads/salas/"
        .
        $salaId
        .
        "/conteudos/"
        .
        $conteudoId;


    $diretorioFisico =
        $raizProjeto
        .
        DIRECTORY_SEPARATOR
        .
        str_replace(
            "/",
            DIRECTORY_SEPARATOR,
            $diretorioRelativo
        );


    if (
        !is_dir(
            $diretorioFisico
        )
    ) {

        if (
            !mkdir(
                $diretorioFisico,
                0750,
                true
            )
            &&
            !is_dir(
                $diretorioFisico
            )
        ) {

            return [
                "status" => false,
                "mensagem" => "Não foi possível preparar a pasta do documento."
            ];

        }

    }


    if (
        !is_writable(
            $diretorioFisico
        )
    ) {

        return [
            "status" => false,
            "mensagem" => "A pasta de documentos não possui permissão de escrita."
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | PROTEÇÃO APACHE ADICIONAL
    |--------------------------------------------------------------------------
    */

    $pastaProtegida =
        $raizProjeto
        .
        DIRECTORY_SEPARATOR
        .
        "backend"
        .
        DIRECTORY_SEPARATOR
        .
        "storage"
        .
        DIRECTORY_SEPARATOR
        .
        "uploads";


    if (
        is_dir(
            $pastaProtegida
        )
    ) {

        $htaccess =
            $pastaProtegida
            .
            DIRECTORY_SEPARATOR
            .
            ".htaccess";


        if (
            !is_file(
                $htaccess
            )
        ) {

            @file_put_contents(
                $htaccess,
                "Require all denied\nDeny from all\n",
                LOCK_EX
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | NOME INTERNO
    |--------------------------------------------------------------------------
    */

    try {

        $nomeArquivo =
            bin2hex(
                random_bytes(
                    24
                )
            )
            .
            "."
            .
            $extensao;

    }
    catch (
        Throwable $erro
    ) {

        return [
            "status" => false,
            "mensagem" => "Não foi possível preparar o documento."
        ];

    }


    $destinoFisico =
        $diretorioFisico
        .
        DIRECTORY_SEPARATOR
        .
        $nomeArquivo;


    /*
    |--------------------------------------------------------------------------
    | MOVE
    |--------------------------------------------------------------------------
    */

    if (
        !move_uploaded_file(
            $temporario,
            $destinoFisico
        )
    ) {

        return [
            "status" => false,
            "mensagem" => "Não foi possível salvar o documento."
        ];

    }


    @chmod(
        $destinoFisico,
        0640
    );


    /*
    |--------------------------------------------------------------------------
    | CONFIRMA ARQUIVO
    |--------------------------------------------------------------------------
    */

    $tamanhoSalvo =
        @filesize(
            $destinoFisico
        );


    if (
        $tamanhoSalvo === false
        ||
        (int) $tamanhoSalvo !==
        $tamanho
    ) {

        @unlink(
            $destinoFisico
        );


        return [
            "status" => false,
            "mensagem" => "O documento não pôde ser validado após o envio."
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CAMINHO DO BANCO
    |--------------------------------------------------------------------------
    */

    $caminhoBanco =
        $diretorioRelativo
        .
        "/"
        .
        $nomeArquivo;


    /*
    |--------------------------------------------------------------------------
    | REGISTRA
    |--------------------------------------------------------------------------
    */

    try {

        $resultado =
            $salaController->adicionarArquivo(

                $conteudoId,

                $professorId,

                $nomeOriginal,

                $nomeArquivo,

                $extensao,

                $mimeType,

                $tamanho,

                $caminhoBanco

            );

    }
    catch (
        Throwable $erro
    ) {

        @unlink(
            $destinoFisico
        );


        error_log(
            "[SalaProfessor] Erro ao registrar documento: "
            .
            $erro->getMessage()
        );


        return [
            "status" => false,
            "mensagem" => "Não foi possível registrar o documento."
        ];

    }


    if (
        empty(
            $resultado["status"]
        )
    ) {

        @unlink(
            $destinoFisico
        );


        return [
            "status" => false,
            "mensagem" =>
                $resultado["mensagem"]
                ??
                "Não foi possível registrar o documento."
        ];

    }


    return [

        "status" =>
            true,

        "ignorado" =>
            false,

        "arquivo_id" =>
            $resultado["arquivo_id"]
            ??
            null

    ];

}


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["usuario"]
    )
    ||
    !is_array(
        $_SESSION["usuario"]
    )
) {

    salaAbrirRedirecionar(
        $linkLogin
    );

}


/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

$usuario =
    $_SESSION["usuario"];


$usuarioId =
    (int) (
        $usuario["id"]
        ??
        0
    );


$nomeUsuario =
    trim(
        (string) (
            $usuario["nome"]
            ??
            "Professor"
        )
    );


if (
    $usuarioId <= 0
) {

    unset(
        $_SESSION["usuario"]
    );


    salaAbrirRedirecionar(
        $linkLogin
    );

}


/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

try {

    $perfilController =
        new PerfilController();


    $salaController =
        new SalaController();

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaProfessor] Erro ao iniciar controllers: "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar a Sala de Aula.";


    salaAbrirRedirecionar(
        $linkPainel
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

try {

    $resultadoPerfil =
        $perfilController->buscarPerfil(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaProfessor] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    salaAbrirRedirecionar(
        $linkEscolherPerfil
    );

}


if (
    empty(
        $resultadoPerfil["status"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]["usuario"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]["usuario"]
    )
) {

    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    salaAbrirRedirecionar(
        $linkEscolherPerfil
    );

}


$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


$perfilProfessor =
    isset(
        $resultadoPerfil["perfil"]["professor"]
    )
    &&
    is_array(
        $resultadoPerfil["perfil"]["professor"]
    )
        ?
        $resultadoPerfil["perfil"]["professor"]
        :
        null;


/*
|--------------------------------------------------------------------------
| GARANTE PROFESSOR
|--------------------------------------------------------------------------
*/

$tipoUsuario =
    strtolower(
        trim(
            (string) (
                $perfilUsuario["tipo_usuario"]
                ??
                ""
            )
        )
    );


if (
    $tipoUsuario !==
    ROLE_PROFESSOR
) {

    $_SESSION["erro"] =
        "Esta área é exclusiva para professores.";


    salaAbrirRedirecionar(
        $linkInicio
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL PROFESSOR
|--------------------------------------------------------------------------
*/

if (
    $perfilProfessor ===
    null
) {

    $_SESSION["erro"] =
        "Perfil de professor não encontrado.";


    salaAbrirRedirecionar(
        $linkPainel
    );

}


/*
|--------------------------------------------------------------------------
| APROVAÇÃO
|--------------------------------------------------------------------------
*/

$professorAprovado =
    !empty(
        $perfilProfessor["aprovado"]
    );


if (
    !$professorAprovado
) {

    $_SESSION["erro"] =
        "Seu perfil ainda não possui permissão para gerenciar salas.";


    salaAbrirRedirecionar(
        $linkPainel
    );

}


/*
|--------------------------------------------------------------------------
| ID DA SALA
|--------------------------------------------------------------------------
*/

$salaId =
    filter_var(
        $_GET["id"]
        ??
        null,
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 1
            ]
        ]
    );


if (
    $salaId === false
    ||
    $salaId === null
) {

    $_SESSION["erro"] =
        "Sala inválida.";


    salaAbrirRedirecionar(
        $linkSalas
    );

}


$salaId =
    (int) $salaId;


/*
|--------------------------------------------------------------------------
| ROTA DA SALA ATUAL
|--------------------------------------------------------------------------
|
| Enquanto a rota dinâmica professor.sala não existir,
| mantemos fallback físico somente para não quebrar a página.
|
|--------------------------------------------------------------------------
*/

if (
    rotaExiste(
        "professor.sala"
    )
) {

    $linkAbrirSala =
        rota(
            "professor.sala",
            [
                "id" =>
                    $salaId
            ]
        );

}
else {

    $linkAbrirSala =
        BASE_URL
        .
        "/frontend/pages/perfil/professor/salas/abrir.php?id="
        .
        $salaId;

}


/*
|--------------------------------------------------------------------------
| BUSCAR SALA
|--------------------------------------------------------------------------
*/

try {

    $resultadoSala =
        $salaController->buscar(
            $salaId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaProfessor] Erro ao buscar sala "
        .
        $salaId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar a sala.";


    salaAbrirRedirecionar(
        $linkSalas
    );

}


if (
    empty(
        $resultadoSala["status"]
    )
    ||
    !isset(
        $resultadoSala["sala"]
    )
    ||
    !is_array(
        $resultadoSala["sala"]
    )
) {

    $_SESSION["erro"] =
        "Sala não encontrada.";


    salaAbrirRedirecionar(
        $linkSalas
    );

}


$sala =
    $resultadoSala["sala"];


/*
|--------------------------------------------------------------------------
| GARANTE PROPRIETÁRIO
|--------------------------------------------------------------------------
*/

$professorSalaId =
    (int) (
        $sala["professor_id"]
        ??
        0
    );


if (
    $professorSalaId !==
    $usuarioId
) {

    $_SESSION["erro"] =
        "Você não possui permissão para acessar esta sala.";


    salaAbrirRedirecionar(
        $linkSalas
    );

}


/*
|--------------------------------------------------------------------------
| DADOS DA SALA
|--------------------------------------------------------------------------
*/

$nomeSala =
    trim(
        (string) (
            $sala["nome"]
            ??
            "Sala de Aula"
        )
    );


if (
    $nomeSala === ""
) {

    $nomeSala =
        "Sala de Aula";

}


$codigoSala =
    trim(
        (string) (
            $sala["codigo"]
            ??
            ""
        )
    );


$linguagemSala =
    trim(
        (string) (
            $sala["linguagem"]
            ??
            ""
        )
    );


$descricaoSala =
    trim(
        (string) (
            $sala["descricao"]
            ??
            ""
        )
    );


/*
|--------------------------------------------------------------------------
| RAIZ DO PROJETO
|--------------------------------------------------------------------------
*/

$raizProjeto =
    realpath(
        dirname(
            __DIR__,
            5
        )
    );


if (
    $raizProjeto === false
) {

    $_SESSION["erro"] =
        "Não foi possível preparar o armazenamento da Sala de Aula.";


    salaAbrirRedirecionar(
        $linkSalas
    );

}


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["csrf"]
    )
    ||
    !is_array(
        $_SESSION["csrf"]
    )
) {

    $_SESSION["csrf"] =
        [];

}


if (
    !isset(
        $_SESSION["csrf"]["professor_salas"]
    )
    ||
    !is_array(
        $_SESSION["csrf"]["professor_salas"]
    )
) {

    $_SESSION["csrf"]["professor_salas"] =
        [];

}


$csrfToken =
    $_SESSION["csrf"]["professor_salas"][$salaId]
    ??
    "";


if (
    !is_string(
        $csrfToken
    )
    ||
    preg_match(
        "/^[a-f0-9]{64}$/",
        $csrfToken
    )
    !== 1
) {

    $csrfToken =
        bin2hex(
            random_bytes(
                32
            )
        );


    $_SESSION["csrf"]["professor_salas"][$salaId] =
        $csrfToken;

}


/*
|--------------------------------------------------------------------------
| PROCESSAR POST
|--------------------------------------------------------------------------
*/

if (
    strtoupper(
        (string) (
            $_SERVER["REQUEST_METHOD"]
            ??
            "GET"
        )
    )
    ===
    "POST"
) {

    /*
    |--------------------------------------------------------------------------
    | ORIGEM
    |--------------------------------------------------------------------------
    */

    if (
        !salaAbrirOrigemPostValida()
    ) {

        $_SESSION["erro"] =
            "A solicitação não pôde ser validada.";


        unset(
            $_SESSION["csrf"]["professor_salas"][$salaId]
        );


        salaAbrirRedirecionar(
            $linkAbrirSala,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    $csrfRecebido =
        $_POST["csrf_token"]
        ??
        null;


    if (
        !is_string(
            $csrfRecebido
        )
        ||
        $csrfRecebido === ""
        ||
        !hash_equals(
            $csrfToken,
            $csrfRecebido
        )
    ) {

        $_SESSION["erro"] =
            "Sua sessão de segurança expirou. Atualize a página e tente novamente.";


        unset(
            $_SESSION["csrf"]["professor_salas"][$salaId]
        );


        salaAbrirRedirecionar(
            $linkAbrirSala,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AÇÃO
    |--------------------------------------------------------------------------
    */

    $acao =
        strtolower(
            trim(
                (string) (
                    $_POST["acao"]
                    ??
                    ""
                )
            )
        );


    /*
    |--------------------------------------------------------------------------
    | CRIAR CONTEÚDO
    |--------------------------------------------------------------------------
    */

    if (
        $acao ===
        "criar_conteudo"
    ) {

        $titulo =
            trim(
                (string) (
                    $_POST["titulo"]
                    ??
                    ""
                )
            );


        $categoria =
            strtolower(
                trim(
                    (string) (
                        $_POST["categoria"]
                        ??
                        "material"
                    )
                )
            );


        $status =
            strtolower(
                trim(
                    (string) (
                        $_POST["status"]
                        ??
                        "publicado"
                    )
                )
            );


        $textoConteudo =
            trim(
                (string) (
                    $_POST["conteudo"]
                    ??
                    ""
                )
            );


        /*
        |--------------------------------------------------------------------------
        | CAMPOS BÁSICOS
        |--------------------------------------------------------------------------
        */

        if (
            $titulo === ""
        ) {

            $_SESSION["erro"] =
                "Informe um título para a publicação.";


            unset(
                $_SESSION["csrf"]["professor_salas"][$salaId]
            );


            salaAbrirRedirecionar(
                $linkAbrirSala
                .
                "#publicar-conteudo",
                303
            );

        }


        $categoriasPermitidas = [

            "aviso",
            "explicacao",
            "codigo",
            "exercicio",
            "material",
            "video",
            "link",
            "outro"

        ];


        if (
            !in_array(
                $categoria,
                $categoriasPermitidas,
                true
            )
        ) {

            $categoria =
                "material";

        }


        if (
            !in_array(
                $status,
                [
                    "publicado",
                    "rascunho",
                    "oculto"
                ],
                true
            )
        ) {

            $status =
                "rascunho";

        }


        /*
        |--------------------------------------------------------------------------
        | LINK
        |--------------------------------------------------------------------------
        */

        $linkTitulo =
            trim(
                (string) (
                    $_POST["link_titulo"]
                    ??
                    ""
                )
            );


        $linkUrlBruta =
            trim(
                (string) (
                    $_POST["link_url"]
                    ??
                    ""
                )
            );


        $linkUrl =
            $linkUrlBruta !== ""
                ?
                salaAbrirUrlExterna(
                    $linkUrlBruta
                )
                :
                null;


        if (
            $linkUrlBruta !== ""
            &&
            $linkUrl === null
        ) {

            $_SESSION["erro"] =
                "O link informado não é válido. Utilize HTTP ou HTTPS.";


            unset(
                $_SESSION["csrf"]["professor_salas"][$salaId]
            );


            salaAbrirRedirecionar(
                $linkAbrirSala
                .
                "#publicar-conteudo",
                303
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VÍDEO
        |--------------------------------------------------------------------------
        */

        $videoTitulo =
            trim(
                (string) (
                    $_POST["video_titulo"]
                    ??
                    ""
                )
            );


        $videoUrlBruta =
            trim(
                (string) (
                    $_POST["video_url"]
                    ??
                    ""
                )
            );


        $videoUrl =
            $videoUrlBruta !== ""
                ?
                salaAbrirUrlExterna(
                    $videoUrlBruta
                )
                :
                null;


        if (
            $videoUrlBruta !== ""
            &&
            $videoUrl === null
        ) {

            $_SESSION["erro"] =
                "A URL do vídeo não é válida. Utilize HTTP ou HTTPS.";


            unset(
                $_SESSION["csrf"]["professor_salas"][$salaId]
            );


            salaAbrirRedirecionar(
                $linkAbrirSala
                .
                "#publicar-conteudo",
                303
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CRIA PUBLICAÇÃO
        |--------------------------------------------------------------------------
        */

        try {

            $resultadoCriacao =
                $salaController->criarConteudo(

                    $salaId,

                    $usuarioId,

                    $titulo,

                    $textoConteudo !== ""
                        ?
                        $textoConteudo
                        :
                        null,

                    $categoria,

                    $status

                );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaProfessor] Erro ao criar conteúdo: "
                .
                $erro->getMessage()
            );


            $resultadoCriacao = [
                "status" => false,
                "mensagem" => "Não foi possível salvar o conteúdo."
            ];

        }


        if (
            empty(
                $resultadoCriacao["status"]
            )
        ) {

            $_SESSION["erro"] =
                $resultadoCriacao["mensagem"]
                ??
                "Não foi possível salvar o conteúdo.";


            unset(
                $_SESSION["csrf"]["professor_salas"][$salaId]
            );


            salaAbrirRedirecionar(
                $linkAbrirSala
                .
                "#publicar-conteudo",
                303
            );

        }


        $conteudoId =
            (int) (
                $resultadoCriacao["conteudo_id"]
                ??
                0
            );


        if (
            $conteudoId <= 0
        ) {

            $_SESSION["erro"] =
                "O conteúdo foi criado, mas não foi possível identificar sua publicação.";


            unset(
                $_SESSION["csrf"]["professor_salas"][$salaId]
            );


            salaAbrirRedirecionar(
                $linkAbrirSala
                .
                "#conteudos-sala",
                303
            );

        }


        /*
        |--------------------------------------------------------------------------
        | AVISOS
        |--------------------------------------------------------------------------
        */

        $avisos =
            [];


        /*
        |--------------------------------------------------------------------------
        | DOCUMENTO
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $_FILES["documento"]
            )
            &&
            is_array(
                $_FILES["documento"]
            )
        ) {

            $resultadoDocumento =
                salaAbrirProcessarDocumento(

                    $_FILES["documento"],

                    $salaId,

                    $conteudoId,

                    $usuarioId,

                    $salaController,

                    $raizProjeto

                );


            if (
                empty(
                    $resultadoDocumento["status"]
                )
            ) {

                $avisos[] =
                    $resultadoDocumento["mensagem"]
                    ??
                    "O documento não pôde ser adicionado.";

            }

        }


        /*
        |--------------------------------------------------------------------------
        | LINK
        |--------------------------------------------------------------------------
        */

        if (
            $linkUrl !==
            null
        ) {

            try {

                $resultadoLink =
                    $salaController->adicionarLink(

                        $conteudoId,

                        $usuarioId,

                        $linkUrl,

                        $linkTitulo !== ""
                            ?
                            $linkTitulo
                            :
                            null

                    );

            }
            catch (
                Throwable $erro
            ) {

                error_log(
                    "[SalaProfessor] Erro ao adicionar link: "
                    .
                    $erro->getMessage()
                );


                $resultadoLink = [
                    "status" => false
                ];

            }


            if (
                empty(
                    $resultadoLink["status"]
                )
            ) {

                $avisos[] =
                    $resultadoLink["mensagem"]
                    ??
                    "O link não pôde ser adicionado.";

            }

        }


        /*
        |--------------------------------------------------------------------------
        | VÍDEO
        |--------------------------------------------------------------------------
        */

        if (
            $videoUrl !==
            null
        ) {

            try {

                $resultadoVideo =
                    $salaController->adicionarVideo(

                        $conteudoId,

                        $usuarioId,

                        $videoUrl,

                        $videoTitulo !== ""
                            ?
                            $videoTitulo
                            :
                            null

                    );

            }
            catch (
                Throwable $erro
            ) {

                error_log(
                    "[SalaProfessor] Erro ao adicionar vídeo: "
                    .
                    $erro->getMessage()
                );


                $resultadoVideo = [
                    "status" => false
                ];

            }


            if (
                empty(
                    $resultadoVideo["status"]
                )
            ) {

                $avisos[] =
                    $resultadoVideo["mensagem"]
                    ??
                    "O vídeo não pôde ser adicionado.";

            }

        }


        /*
        |--------------------------------------------------------------------------
        | RESULTADO
        |--------------------------------------------------------------------------
        */

        $_SESSION["sucesso"] =
            $resultadoCriacao["mensagem"]
            ??
            "Conteúdo salvo com sucesso.";


        if (
            !empty(
                $avisos
            )
        ) {

            $_SESSION["erro"] =
                implode(
                    " ",
                    $avisos
                );

        }


        unset(
            $_SESSION["csrf"]["professor_salas"][$salaId]
        );


        salaAbrirRedirecionar(
            $linkAbrirSala
            .
            "#conteudos-sala",
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ALTERAR STATUS
    |--------------------------------------------------------------------------
    */

    if (
        $acao ===
        "alterar_status_conteudo"
    ) {

        $conteudoId =
            filter_var(
                $_POST["conteudo_id"]
                ??
                null,
                FILTER_VALIDATE_INT,
                [
                    "options" => [
                        "min_range" => 1
                    ]
                ]
            );


        $novoStatus =
            strtolower(
                trim(
                    (string) (
                        $_POST["novo_status"]
                        ??
                        ""
                    )
                )
            );


        if (
            $conteudoId === false
            ||
            $conteudoId === null
            ||
            !in_array(
                $novoStatus,
                [
                    "publicado",
                    "rascunho",
                    "oculto"
                ],
                true
            )
        ) {

            $_SESSION["erro"] =
                "Os dados do conteúdo são inválidos.";

        }
        else {

            try {

                $resultadoStatus =
                    $salaController->alterarStatusConteudo(

                        (int) $conteudoId,

                        $usuarioId,

                        $novoStatus

                    );

            }
            catch (
                Throwable $erro
            ) {

                error_log(
                    "[SalaProfessor] Erro ao alterar status: "
                    .
                    $erro->getMessage()
                );


                $resultadoStatus = [
                    "status" => false,
                    "mensagem" => "Não foi possível alterar o conteúdo."
                ];

            }


            if (
                empty(
                    $resultadoStatus["status"]
                )
            ) {

                $_SESSION["erro"] =
                    $resultadoStatus["mensagem"]
                    ??
                    "Não foi possível alterar o conteúdo.";

            }
            else {

                $_SESSION["sucesso"] =
                    $resultadoStatus["mensagem"]
                    ??
                    "Conteúdo atualizado.";

            }

        }


        unset(
            $_SESSION["csrf"]["professor_salas"][$salaId]
        );


        salaAbrirRedirecionar(
            $linkAbrirSala
            .
            "#conteudos-sala",
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER DOCUMENTO
    |--------------------------------------------------------------------------
    */

    if (
        $acao ===
        "remover_arquivo"
    ) {

        $arquivoId =
            filter_var(
                $_POST["arquivo_id"]
                ??
                null,
                FILTER_VALIDATE_INT,
                [
                    "options" => [
                        "min_range" => 1
                    ]
                ]
            );


        if (
            $arquivoId === false
            ||
            $arquivoId === null
        ) {

            $_SESSION["erro"] =
                "Documento inválido.";

        }
        else {

            try {

                $resultadoArquivo =
                    $salaController->removerArquivo(

                        (int) $arquivoId,

                        $usuarioId

                    );

            }
            catch (
                Throwable $erro
            ) {

                error_log(
                    "[SalaProfessor] Erro ao remover arquivo: "
                    .
                    $erro->getMessage()
                );


                $resultadoArquivo = [
                    "status" => false,
                    "mensagem" => "Não foi possível remover o documento."
                ];

            }


            if (
                empty(
                    $resultadoArquivo["status"]
                )
            ) {

                $_SESSION["erro"] =
                    $resultadoArquivo["mensagem"]
                    ??
                    "Não foi possível remover o documento.";

            }
            else {

                $arquivoRemovido =
                    isset(
                        $resultadoArquivo["arquivo"]
                    )
                    &&
                    is_array(
                        $resultadoArquivo["arquivo"]
                    )
                        ?
                        $resultadoArquivo["arquivo"]
                        :
                        [];


                $caminhoRemovido =
                    trim(
                        (string) (
                            $arquivoRemovido["caminho"]
                            ??
                            ""
                        )
                    );


                if (
                    $caminhoRemovido !==
                    ""
                ) {

                    salaAbrirExcluirArquivoFisico(

                        $caminhoRemovido,

                        $raizProjeto

                    );

                }


                $_SESSION["sucesso"] =
                    "Documento removido com sucesso.";

            }

        }


        unset(
            $_SESSION["csrf"]["professor_salas"][$salaId]
        );


        salaAbrirRedirecionar(
            $linkAbrirSala
            .
            "#conteudos-sala",
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER LINK
    |--------------------------------------------------------------------------
    */

    if (
        $acao ===
        "remover_link"
    ) {

        $linkId =
            filter_var(
                $_POST["link_id"]
                ??
                null,
                FILTER_VALIDATE_INT,
                [
                    "options" => [
                        "min_range" => 1
                    ]
                ]
            );


        if (
            $linkId === false
            ||
            $linkId === null
        ) {

            $_SESSION["erro"] =
                "Link inválido.";

        }
        else {

            try {

                $resultadoLink =
                    $salaController->removerLink(

                        (int) $linkId,

                        $usuarioId

                    );

            }
            catch (
                Throwable $erro
            ) {

                error_log(
                    "[SalaProfessor] Erro ao remover link: "
                    .
                    $erro->getMessage()
                );


                $resultadoLink = [
                    "status" => false
                ];

            }


            if (
                empty(
                    $resultadoLink["status"]
                )
            ) {

                $_SESSION["erro"] =
                    $resultadoLink["mensagem"]
                    ??
                    "Não foi possível remover o link.";

            }
            else {

                $_SESSION["sucesso"] =
                    "Link removido com sucesso.";

            }

        }


        unset(
            $_SESSION["csrf"]["professor_salas"][$salaId]
        );


        salaAbrirRedirecionar(
            $linkAbrirSala
            .
            "#conteudos-sala",
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER VÍDEO
    |--------------------------------------------------------------------------
    */

    if (
        $acao ===
        "remover_video"
    ) {

        $videoId =
            filter_var(
                $_POST["video_id"]
                ??
                null,
                FILTER_VALIDATE_INT,
                [
                    "options" => [
                        "min_range" => 1
                    ]
                ]
            );


        if (
            $videoId === false
            ||
            $videoId === null
        ) {

            $_SESSION["erro"] =
                "Vídeo inválido.";

        }
        else {

            try {

                $resultadoVideo =
                    $salaController->removerVideo(

                        (int) $videoId,

                        $usuarioId

                    );

            }
            catch (
                Throwable $erro
            ) {

                error_log(
                    "[SalaProfessor] Erro ao remover vídeo: "
                    .
                    $erro->getMessage()
                );


                $resultadoVideo = [
                    "status" => false
                ];

            }


            if (
                empty(
                    $resultadoVideo["status"]
                )
            ) {

                $_SESSION["erro"] =
                    $resultadoVideo["mensagem"]
                    ??
                    "Não foi possível remover o vídeo.";

            }
            else {

                $_SESSION["sucesso"] =
                    "Vídeo removido com sucesso.";

            }

        }


        unset(
            $_SESSION["csrf"]["professor_salas"][$salaId]
        );


        salaAbrirRedirecionar(
            $linkAbrirSala
            .
            "#conteudos-sala",
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR CONTEÚDO
    |--------------------------------------------------------------------------
    */

    if (
        $acao ===
        "excluir_conteudo"
    ) {

        $conteudoId =
            filter_var(
                $_POST["conteudo_id"]
                ??
                null,
                FILTER_VALIDATE_INT,
                [
                    "options" => [
                        "min_range" => 1
                    ]
                ]
            );


        if (
            $conteudoId === false
            ||
            $conteudoId === null
        ) {

            $_SESSION["erro"] =
                "Conteúdo inválido.";

        }
        else {

            try {

                $resultadoExclusao =
                    $salaController->excluirConteudo(

                        (int) $conteudoId,

                        $usuarioId

                    );

            }
            catch (
                Throwable $erro
            ) {

                error_log(
                    "[SalaProfessor] Erro ao excluir conteúdo: "
                    .
                    $erro->getMessage()
                );


                $resultadoExclusao = [
                    "status" => false,
                    "mensagem" => "Não foi possível excluir o conteúdo."
                ];

            }


            if (
                empty(
                    $resultadoExclusao["status"]
                )
            ) {

                $_SESSION["erro"] =
                    $resultadoExclusao["mensagem"]
                    ??
                    "Não foi possível excluir o conteúdo.";

            }
            else {

                $arquivosRemovidos =
                    isset(
                        $resultadoExclusao["arquivos_removidos"]
                    )
                    &&
                    is_array(
                        $resultadoExclusao["arquivos_removidos"]
                    )
                        ?
                        $resultadoExclusao["arquivos_removidos"]
                        :
                        [];


                foreach (
                    $arquivosRemovidos as $arquivo
                ) {

                    if (
                        !is_array(
                            $arquivo
                        )
                    ) {

                        continue;

                    }


                    $caminho =
                        trim(
                            (string) (
                                $arquivo["caminho"]
                                ??
                                ""
                            )
                        );


                    if (
                        $caminho !==
                        ""
                    ) {

                        salaAbrirExcluirArquivoFisico(

                            $caminho,

                            $raizProjeto

                        );

                    }

                }


                $_SESSION["sucesso"] =
                    $resultadoExclusao["mensagem"]
                    ??
                    "Conteúdo excluído.";

            }

        }


        unset(
            $_SESSION["csrf"]["professor_salas"][$salaId]
        );


        salaAbrirRedirecionar(
            $linkAbrirSala
            .
            "#conteudos-sala",
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AÇÃO INVÁLIDA
    |--------------------------------------------------------------------------
    */

    $_SESSION["erro"] =
        "Ação inválida.";


    unset(
        $_SESSION["csrf"]["professor_salas"][$salaId]
    );


    salaAbrirRedirecionar(
        $linkAbrirSala,
        303
    );

}


/*
|--------------------------------------------------------------------------
| ALUNOS
|--------------------------------------------------------------------------
*/

try {

    $resultadoAlunos =
        $salaController->listarAlunos(

            $salaId,

            $usuarioId

        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaProfessor] Erro ao listar alunos: "
        .
        $erro->getMessage()
    );


    $resultadoAlunos = [
        "status" => false,
        "alunos" => []
    ];

}


$alunos =
    !empty(
        $resultadoAlunos["status"]
    )
    &&
    isset(
        $resultadoAlunos["alunos"]
    )
    &&
    is_array(
        $resultadoAlunos["alunos"]
    )
        ?
        $resultadoAlunos["alunos"]
        :
        [];


$totalAlunos =
    count(
        $alunos
    );


/*
|--------------------------------------------------------------------------
| CONTEÚDOS
|--------------------------------------------------------------------------
*/

try {

    $resultadoConteudos =
        $salaController->listarConteudosProfessor(

            $salaId,

            $usuarioId

        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaProfessor] Erro ao listar conteúdos: "
        .
        $erro->getMessage()
    );


    $resultadoConteudos = [
        "status" => false,
        "conteudos" => []
    ];

}


$conteudos =
    !empty(
        $resultadoConteudos["status"]
    )
    &&
    isset(
        $resultadoConteudos["conteudos"]
    )
    &&
    is_array(
        $resultadoConteudos["conteudos"]
    )
        ?
        $resultadoConteudos["conteudos"]
        :
        [];


$conteudos =
    array_values(
        array_filter(
            $conteudos,
            "is_array"
        )
    );


$totalConteudos =
    count(
        $conteudos
    );


$totalPublicados =
    0;


$totalRascunhos =
    0;


$totalOcultos =
    0;


foreach (
    $conteudos as $itemConteudo
) {

    $statusConteudo =
        strtolower(
            trim(
                (string) (
                    $itemConteudo["status"]
                    ??
                    ""
                )
            )
        );


    if (
        $statusConteudo ===
        "publicado"
    ) {

        $totalPublicados++;

    }
    elseif (
        $statusConteudo ===
        "rascunho"
    ) {

        $totalRascunhos++;

    }
    elseif (
        $statusConteudo ===
        "oculto"
    ) {

        $totalOcultos++;

    }

}


/*
|--------------------------------------------------------------------------
| ASSETS
|--------------------------------------------------------------------------
*/

$arquivoStyle =
    __DIR__ .
    "/../../../../assets/css/style.css";


$arquivoMensagemCss =
    __DIR__ .
    "/../../../../assets/css/mensagem.css";


$arquivoEstudanteCss =
    __DIR__ .
    "/../../../../assets/css/estudante.css";


$arquivoSalasCss =
    __DIR__ .
    "/../../../../assets/css/salas_professor.css";


$arquivoThemeJs =
    __DIR__ .
    "/../../../../assets/js/theme.js";


$versaoStyle =
    salaAbrirVersaoAsset(
        $arquivoStyle
    );


$versaoMensagemCss =
    salaAbrirVersaoAsset(
        $arquivoMensagemCss
    );


$versaoEstudanteCss =
    salaAbrirVersaoAsset(
        $arquivoEstudanteCss
    );


$versaoSalasCss =
    salaAbrirVersaoAsset(
        $arquivoSalasCss
    );


$versaoThemeJs =
    salaAbrirVersaoAsset(
        $arquivoThemeJs
    );

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="description"
    content="Sala de Aula do professor."
>

<title>
    <?= salaAbrirEscapar(
        $nomeSala
    ) ?>
    |
    <?= salaAbrirEscapar(
        SITE_NAME
    ) ?>
</title>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= $versaoStyle ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css?v=<?= $versaoMensagemCss ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/estudante.css?v=<?= $versaoEstudanteCss ?>"
>


<?php if (
    is_file(
        $arquivoSalasCss
    )
): ?>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/salas_professor.css?v=<?= $versaoSalasCss ?>"
>

<?php endif; ?>


</head>


<body class="pagina-estudante pagina-professor pagina-salas-professor">


<?php

require_once __DIR__ .
    "/../../../../componentes/header.php";


require_once __DIR__ .
    "/../../../../componentes/mensagem.php";

?>


<main class="painel-estudante">


<!-- =====================================================
APRESENTAÇÃO
====================================================== -->

<section class="painel-apresentacao">


    <div class="painel-apresentacao-conteudo">


        <span class="painel-etiqueta">

            SALA DE AULA

        </span>


        <h1>

            <?= salaAbrirEscapar(
                $nomeSala
            ) ?>

        </h1>


        <p>

            <?= salaAbrirEscapar(
                $descricaoSala !== ""
                    ?
                    $descricaoSala
                    :
                    "Gerencie sua turma e acompanhe os estudantes desta sala."
            ) ?>

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="#publicar-conteudo"
                class="botao-principal"
            >

                Publicar conteúdo

                <span aria-hidden="true">

                    +

                </span>

            </a>


            <a
                href="<?= salaAbrirEscapar(
                    $linkSalas
                ) ?>"
                class="botao-secundario"
            >

                ← Minhas salas

            </a>


        </div>


    </div>


    <div class="painel-apresentacao-codigo">


        <div class="codigo-topo">


            <div class="codigo-controles">

                <span></span>
                <span></span>
                <span></span>

            </div>


            <strong>

                TurmaJava.java

            </strong>


            <small>

                SALA

            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">TurmaJava</span> {

    String codigo =
        <span class="codigo-amarelo">"<?= salaAbrirEscapar(
            $codigoSala
        ) ?>"</span>;

    int alunos =
        <span class="codigo-amarelo"><?= $totalAlunos ?></span>;

    int conteudos =
        <span class="codigo-amarelo"><?= $totalPublicados ?></span>;

}</code></pre>


        <div class="codigo-status">


            <span>

                ● Sala ativa

            </span>


            <span>

                <?= salaAbrirEscapar(
                    $linguagemSala !== ""
                        ?
                        $linguagemSala
                        :
                        "Sala de Aula"
                ) ?>

            </span>


        </div>


    </div>


</section>


<!-- =====================================================
RESUMO
====================================================== -->

<section class="painel-estatisticas">


    <article>


        <span class="estatistica-icone">

            #

        </span>


        <div>


            <small>

                CÓDIGO DA SALA

            </small>


            <strong>

                <?= salaAbrirEscapar(
                    $codigoSala
                ) ?>

            </strong>


            <p>

                compartilhe com seus alunos

            </p>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">

            AL

        </span>


        <div>


            <small>

                ALUNOS

            </small>


            <strong>

                <?= $totalAlunos ?>

            </strong>


            <p>

                <?= $totalAlunos === 1
                    ?
                    "participante"
                    :
                    "participantes"
                ?>

            </p>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">

            CT

        </span>


        <div>


            <small>

                CONTEÚDOS

            </small>


            <strong>

                <?= $totalPublicados ?>

            </strong>


            <p>

                publicações disponíveis

            </p>


        </div>


    </article>


</section>


<!-- =====================================================
GERENCIAMENTO
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">

            GERENCIAMENTO

        </span>


        <h2>

            Gerenciar Sala

        </h2>


        <p>

            Organize os participantes e os conteúdos
            disponibilizados para a turma.

        </p>


    </div>


    <div class="painel-acessos-grid">


        <a
            href="#alunos-sala"
            class="acesso-card"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">

                    AL

                </span>


                <span class="acesso-status acesso-disponivel">

                    <?= $totalAlunos ?>

                </span>


            </div>


            <div>


                <small>

                    PARTICIPANTES

                </small>


                <h3>

                    Alunos da sala

                </h3>


                <p>

                    Consulte os estudantes vinculados à turma.

                </p>


            </div>


            <span
                class="acesso-seta"
                aria-hidden="true"
            >

                →

            </span>


        </a>


        <article class="acesso-card">


            <div class="acesso-card-topo">


                <span class="acesso-icone">

                    MS

                </span>


                <span class="acesso-status acesso-breve">

                    Em breve

                </span>


            </div>


            <div>


                <small>

                    COMUNICAÇÃO

                </small>


                <h3>

                    Mensagens

                </h3>


                <p>

                    Recurso de comunicação da turma em desenvolvimento.

                </p>


            </div>


        </article>


        <a
            href="#conteudos-sala"
            class="acesso-card acesso-principal"
        >


            <div class="acesso-card-topo">


                <span class="acesso-icone">

                    CT

                </span>


                <span class="acesso-status acesso-disponivel">

                    <?= $totalConteudos ?>

                </span>


            </div>


            <div>


                <small>

                    MATERIAIS

                </small>


                <h3>

                    Conteúdos

                </h3>


                <p>

                    Textos, documentos, vídeos,
                    links e atividades da turma.

                </p>


            </div>


            <span
                class="acesso-seta"
                aria-hidden="true"
            >

                →

            </span>


        </a>


    </div>


</section>


<!-- =====================================================
PUBLICAR
====================================================== -->

<section
    class="painel-estudo"
    id="publicar-conteudo"
>


    <article class="painel-ultima-aula sala-professor-criar">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">

                    NOVA PUBLICAÇÃO

                </span>


                <h2>

                    Publicar conteúdo

                </h2>


            </div>


            <span class="painel-card-numero">

                +

            </span>


        </div>


        <div class="painel-aula-conteudo">


            <p>

                Você pode utilizar somente texto
                ou complementar a publicação com
                documento, link e vídeo.

            </p>


            <form
                method="POST"
                action="<?= salaAbrirEscapar(
                    $linkAbrirSala
                ) ?>"
                enctype="multipart/form-data"
                class="sala-professor-formulario"
            >


                <input
                    type="hidden"
                    name="acao"
                    value="criar_conteudo"
                >


                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= salaAbrirEscapar(
                        $csrfToken
                    ) ?>"
                >


                <div class="sala-professor-campo sala-professor-campo-completo">


                    <label for="titulo">

                        Título

                        <span>

                            *

                        </span>

                    </label>


                    <input
                        type="text"
                        id="titulo"
                        name="titulo"
                        maxlength="150"
                        placeholder="Ex.: Introdução ao Scanner"
                        required
                    >


                </div>


                <div class="sala-professor-campo">


                    <label for="categoria">

                        Categoria

                    </label>


                    <select
                        id="categoria"
                        name="categoria"
                        required
                    >

                        <option value="material">
                            Material
                        </option>

                        <option value="explicacao">
                            Explicação
                        </option>

                        <option value="codigo">
                            Código
                        </option>

                        <option value="exercicio">
                            Exercício
                        </option>

                        <option value="aviso">
                            Aviso
                        </option>

                        <option value="video">
                            Vídeo
                        </option>

                        <option value="link">
                            Link
                        </option>

                        <option value="outro">
                            Outro
                        </option>

                    </select>


                    <small>

                        A categoria apenas organiza a publicação.

                    </small>


                </div>


                <div class="sala-professor-campo">


                    <label for="status">

                        Situação

                    </label>


                    <select
                        id="status"
                        name="status"
                    >

                        <option value="publicado">

                            Publicar agora

                        </option>

                        <option value="rascunho">

                            Salvar como rascunho

                        </option>

                        <option value="oculto">

                            Manter oculto

                        </option>

                    </select>


                </div>


                <div class="sala-professor-campo sala-professor-campo-completo">


                    <label for="conteudo">

                        Texto / descrição

                    </label>


                    <textarea
                        id="conteudo"
                        name="conteudo"
                        rows="9"
                        placeholder="Escreva a explicação, instrução ou atividade..."
                    ></textarea>


                </div>


                <div class="sala-professor-campo sala-professor-campo-completo">


                    <label for="documento">

                        📄 Documento / arquivo

                    </label>


                    <input
                        type="file"
                        id="documento"
                        name="documento"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.png,.jpg,.jpeg,.webp"
                    >


                    <small>

                        Permitidos: PDF, Word, PowerPoint,
                        TXT e imagens. Máximo de 10 MB.

                    </small>


                </div>


                <div class="sala-professor-campo">


                    <label for="link_titulo">

                        🔗 Título do link

                    </label>


                    <input
                        type="text"
                        id="link_titulo"
                        name="link_titulo"
                        maxlength="200"
                        placeholder="Ex.: Documentação oficial"
                    >


                </div>


                <div class="sala-professor-campo">


                    <label for="link_url">

                        🔗 URL do link

                    </label>


                    <input
                        type="url"
                        id="link_url"
                        name="link_url"
                        maxlength="1000"
                        placeholder="https://..."
                    >


                </div>


                <div class="sala-professor-campo">


                    <label for="video_titulo">

                        🎬 Título do vídeo

                    </label>


                    <input
                        type="text"
                        id="video_titulo"
                        name="video_titulo"
                        maxlength="200"
                        placeholder="Ex.: Aula sobre Scanner"
                    >


                </div>


                <div class="sala-professor-campo">


                    <label for="video_url">

                        🎬 URL do vídeo

                    </label>


                    <input
                        type="url"
                        id="video_url"
                        name="video_url"
                        maxlength="1000"
                        placeholder="https://youtube.com/..."
                    >


                    <small>

                        Utilize uma URL HTTP ou HTTPS.

                    </small>


                </div>


                <div class="sala-professor-formulario-acoes">


                    <button
                        type="submit"
                        class="botao-principal"
                    >

                        Publicar conteúdo

                        <span aria-hidden="true">

                            →

                        </span>

                    </button>


                </div>


            </form>


        </div>


    </article>


    <article class="painel-proximo-passo">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">

                    PUBLICAÇÕES

                </span>


                <h2>

                    Situação

                </h2>


            </div>


            <span class="painel-proximo-icone">

                <?= $totalConteudos ?>

            </span>


        </div>


        <div class="painel-proximo-conteudo">


            <small>

                CONTEÚDOS DA SALA

            </small>


            <h3>

                <?= $totalPublicados ?>
                publicados

            </h3>


            <p>

                Rascunhos:

                <strong>

                    <?= $totalRascunhos ?>

                </strong>

            </p>


            <p>

                Ocultos:

                <strong>

                    <?= $totalOcultos ?>

                </strong>

            </p>


        </div>


        <a
            href="#conteudos-sala"
            class="botao-secundario"
        >

            Ver publicações

        </a>


    </article>


</section>


<!-- =====================================================
CONTEÚDOS
====================================================== -->

<section
    class="painel-acessos"
    id="conteudos-sala"
>


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">

            CONTEÚDOS DA SALA

        </span>


        <h2>

            Publicações

        </h2>


        <p>

            Textos, documentos, links e vídeos
            disponibilizados para esta turma.

        </p>


    </div>


    <?php if (
        empty(
            $conteudos
        )
    ): ?>


        <div class="sala-professor-vazio">


            <span class="sala-professor-vazio-icone">

                CT

            </span>


            <h3>

                Nenhum conteúdo

            </h3>


            <p>

                Crie a primeira publicação desta Sala de Aula.

            </p>


            <a
                href="#publicar-conteudo"
                class="botao-principal"
            >

                Criar conteúdo

            </a>


        </div>


    <?php else: ?>


        <div class="painel-acessos-grid">


            <?php foreach (
                $conteudos as $publicacao
            ): ?>


                <?php

                $conteudoId =
                    (int) (
                        $publicacao["id"]
                        ??
                        0
                    );


                $tituloPublicacao =
                    trim(
                        (string) (
                            $publicacao["titulo"]
                            ??
                            "Conteúdo"
                        )
                    );


                $textoPublicacao =
                    trim(
                        (string) (
                            $publicacao["conteudo"]
                            ??
                            ""
                        )
                    );


                $categoriaPublicacao =
                    strtolower(
                        trim(
                            (string) (
                                $publicacao["categoria"]
                                ??
                                "outro"
                            )
                        )
                    );


                $statusPublicacao =
                    strtolower(
                        trim(
                            (string) (
                                $publicacao["status"]
                                ??
                                "rascunho"
                            )
                        )
                    );


                $autorPublicacao =
                    trim(
                        (string) (
                            $publicacao["autor_nome"]
                            ??
                            $nomeUsuario
                        )
                    );


                $dataPublicacao =
                    salaAbrirFormatarData(
                        $publicacao["criado_em"]
                        ??
                        ""
                    );


                $arquivos =
                    isset(
                        $publicacao["arquivos"]
                    )
                    &&
                    is_array(
                        $publicacao["arquivos"]
                    )
                        ?
                        $publicacao["arquivos"]
                        :
                        [];


                $links =
                    isset(
                        $publicacao["links"]
                    )
                    &&
                    is_array(
                        $publicacao["links"]
                    )
                        ?
                        $publicacao["links"]
                        :
                        [];


                $videos =
                    isset(
                        $publicacao["videos"]
                    )
                    &&
                    is_array(
                        $publicacao["videos"]
                    )
                        ?
                        $publicacao["videos"]
                        :
                        [];


                $statusClasse =
                    $statusPublicacao ===
                    "publicado"
                        ?
                        "acesso-disponivel"
                        :
                        "acesso-breve";

                ?>


                <article class="acesso-card sala-conteudo-card">


                    <div class="acesso-card-topo">


                        <span class="acesso-icone">

                            CT

                        </span>


                        <span
                            class="acesso-status <?= salaAbrirEscapar(
                                $statusClasse
                            ) ?>"
                        >

                            <?= salaAbrirEscapar(
                                salaAbrirStatusTexto(
                                    $statusPublicacao
                                )
                            ) ?>

                        </span>


                    </div>


                    <div>


                        <small>

                            <?= salaAbrirEscapar(
                                strtoupper(
                                    salaAbrirCategoriaTexto(
                                        $categoriaPublicacao
                                    )
                                )
                            ) ?>

                        </small>


                        <h3>

                            <?= salaAbrirEscapar(
                                $tituloPublicacao
                            ) ?>

                        </h3>


                        <?php if (
                            $textoPublicacao !== ""
                        ): ?>


                            <p>

                                <?= nl2br(
                                    salaAbrirEscapar(
                                        $textoPublicacao
                                    )
                                ) ?>

                            </p>


                        <?php endif; ?>


                        <p>


                            <small>

                                Por

                                <strong>

                                    <?= salaAbrirEscapar(
                                        $autorPublicacao
                                    ) ?>

                                </strong>

                                •

                                <?= salaAbrirEscapar(
                                    $dataPublicacao
                                ) ?>

                            </small>


                        </p>


                    </div>


                    <!-- DOCUMENTOS -->

                    <?php if (
                        !empty(
                            $arquivos
                        )
                    ): ?>


                        <div class="sala-conteudo-recursos">


                            <strong>

                                📄 Documentos

                            </strong>


                            <?php foreach (
                                $arquivos as $arquivo
                            ): ?>


                                <?php

                                if (
                                    !is_array(
                                        $arquivo
                                    )
                                ) {

                                    continue;

                                }


                                $arquivoId =
                                    (int) (
                                        $arquivo["id"]
                                        ??
                                        0
                                    );


                                $arquivoNome =
                                    trim(
                                        (string) (
                                            $arquivo["nome_original"]
                                            ??
                                            "Documento"
                                        )
                                    );


                                $arquivoTamanho =
                                    max(
                                        0,
                                        (int) (
                                            $arquivo["tamanho"]
                                            ??
                                            0
                                        )
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | NUNCA USA CAMINHO FÍSICO NO HREF
                                |--------------------------------------------------------------------------
                                */

                                $linkArquivoProfessor =
                                    null;


                                if (
                                    $arquivoId > 0
                                    &&
                                    rotaExiste(
                                        "professor.sala.arquivo"
                                    )
                                ) {

                                    $linkArquivoProfessor =
                                        rota(
                                            "professor.sala.arquivo",
                                            [
                                                "sala_id" =>
                                                    $salaId,

                                                "arquivo_id" =>
                                                    $arquivoId
                                            ]
                                        );

                                }

                                ?>


                                <div class="sala-conteudo-recurso">


                                    <div>


                                        <strong>

                                            <?= salaAbrirEscapar(
                                                $arquivoNome
                                            ) ?>

                                        </strong>


                                        <small>

                                            <?= salaAbrirEscapar(
                                                salaAbrirFormatarTamanho(
                                                    $arquivoTamanho
                                                )
                                            ) ?>

                                        </small>


                                    </div>


                                    <?php if (
                                        $linkArquivoProfessor !== null
                                    ): ?>


                                        <a
                                            href="<?= salaAbrirEscapar(
                                                $linkArquivoProfessor
                                            ) ?>"
                                            class="botao-secundario"
                                        >

                                            Abrir documento

                                        </a>


                                    <?php else: ?>


                                        <span class="acesso-status acesso-breve">

                                            🔒 Arquivo protegido

                                        </span>


                                    <?php endif; ?>


                                    <form
                                        method="POST"
                                        action="<?= salaAbrirEscapar(
                                            $linkAbrirSala
                                        ) ?>"
                                    >


                                        <input
                                            type="hidden"
                                            name="acao"
                                            value="remover_arquivo"
                                        >


                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= salaAbrirEscapar(
                                                $csrfToken
                                            ) ?>"
                                        >


                                        <input
                                            type="hidden"
                                            name="arquivo_id"
                                            value="<?= $arquivoId ?>"
                                        >


                                        <button
                                            type="submit"
                                            class="botao-secundario"
                                        >

                                            Remover

                                        </button>


                                    </form>


                                </div>


                            <?php endforeach; ?>


                        </div>


                    <?php endif; ?>


                    <!-- LINKS -->

                    <?php if (
                        !empty(
                            $links
                        )
                    ): ?>


                        <div class="sala-conteudo-recursos">


                            <strong>

                                🔗 Links

                            </strong>


                            <?php foreach (
                                $links as $link
                            ): ?>


                                <?php

                                if (
                                    !is_array(
                                        $link
                                    )
                                ) {

                                    continue;

                                }


                                $linkId =
                                    (int) (
                                        $link["id"]
                                        ??
                                        0
                                    );


                                $url =
                                    salaAbrirUrlExterna(
                                        $link["url"]
                                        ??
                                        ""
                                    );


                                $tituloLink =
                                    trim(
                                        (string) (
                                            $link["titulo"]
                                            ??
                                            ""
                                        )
                                    );


                                if (
                                    $tituloLink === ""
                                ) {

                                    $tituloLink =
                                        "Abrir link";

                                }

                                ?>


                                <div class="sala-conteudo-recurso">


                                    <?php if (
                                        $url !== null
                                    ): ?>


                                        <a
                                            href="<?= salaAbrirEscapar(
                                                $url
                                            ) ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="botao-secundario"
                                        >

                                            🔗
                                            <?= salaAbrirEscapar(
                                                $tituloLink
                                            ) ?>

                                        </a>


                                    <?php else: ?>


                                        <span class="acesso-status acesso-breve">

                                            Link indisponível

                                        </span>


                                    <?php endif; ?>


                                    <form
                                        method="POST"
                                        action="<?= salaAbrirEscapar(
                                            $linkAbrirSala
                                        ) ?>"
                                    >


                                        <input
                                            type="hidden"
                                            name="acao"
                                            value="remover_link"
                                        >


                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= salaAbrirEscapar(
                                                $csrfToken
                                            ) ?>"
                                        >


                                        <input
                                            type="hidden"
                                            name="link_id"
                                            value="<?= $linkId ?>"
                                        >


                                        <button
                                            type="submit"
                                            class="botao-secundario"
                                        >

                                            Remover

                                        </button>


                                    </form>


                                </div>


                            <?php endforeach; ?>


                        </div>


                    <?php endif; ?>


                    <!-- VÍDEOS -->

                    <?php if (
                        !empty(
                            $videos
                        )
                    ): ?>


                        <div class="sala-conteudo-recursos">


                            <strong>

                                🎬 Vídeos

                            </strong>


                            <?php foreach (
                                $videos as $video
                            ): ?>


                                <?php

                                if (
                                    !is_array(
                                        $video
                                    )
                                ) {

                                    continue;

                                }


                                $videoId =
                                    (int) (
                                        $video["id"]
                                        ??
                                        0
                                    );


                                $urlVideo =
                                    salaAbrirUrlExterna(
                                        $video["url"]
                                        ??
                                        ""
                                    );


                                $tituloVideo =
                                    trim(
                                        (string) (
                                            $video["titulo"]
                                            ??
                                            ""
                                        )
                                    );


                                if (
                                    $tituloVideo === ""
                                ) {

                                    $tituloVideo =
                                        "Assistir vídeo";

                                }

                                ?>


                                <div class="sala-conteudo-recurso">


                                    <?php if (
                                        $urlVideo !== null
                                    ): ?>


                                        <a
                                            href="<?= salaAbrirEscapar(
                                                $urlVideo
                                            ) ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="botao-principal"
                                        >

                                            ▶
                                            <?= salaAbrirEscapar(
                                                $tituloVideo
                                            ) ?>

                                        </a>


                                    <?php else: ?>


                                        <span class="acesso-status acesso-breve">

                                            Vídeo indisponível

                                        </span>


                                    <?php endif; ?>


                                    <form
                                        method="POST"
                                        action="<?= salaAbrirEscapar(
                                            $linkAbrirSala
                                        ) ?>"
                                    >


                                        <input
                                            type="hidden"
                                            name="acao"
                                            value="remover_video"
                                        >


                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= salaAbrirEscapar(
                                                $csrfToken
                                            ) ?>"
                                        >


                                        <input
                                            type="hidden"
                                            name="video_id"
                                            value="<?= $videoId ?>"
                                        >


                                        <button
                                            type="submit"
                                            class="botao-secundario"
                                        >

                                            Remover

                                        </button>


                                    </form>


                                </div>


                            <?php endforeach; ?>


                        </div>


                    <?php endif; ?>


                    <!-- STATUS -->

                    <div class="sala-professor-formulario-acoes">


                        <?php if (
                            $statusPublicacao ===
                            "publicado"
                        ): ?>


                            <form
                                method="POST"
                                action="<?= salaAbrirEscapar(
                                    $linkAbrirSala
                                ) ?>"
                            >


                                <input
                                    type="hidden"
                                    name="acao"
                                    value="alterar_status_conteudo"
                                >


                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= salaAbrirEscapar(
                                        $csrfToken
                                    ) ?>"
                                >


                                <input
                                    type="hidden"
                                    name="conteudo_id"
                                    value="<?= $conteudoId ?>"
                                >


                                <input
                                    type="hidden"
                                    name="novo_status"
                                    value="oculto"
                                >


                                <button
                                    type="submit"
                                    class="botao-secundario"
                                >

                                    Ocultar

                                </button>


                            </form>


                        <?php else: ?>


                            <form
                                method="POST"
                                action="<?= salaAbrirEscapar(
                                    $linkAbrirSala
                                ) ?>"
                            >


                                <input
                                    type="hidden"
                                    name="acao"
                                    value="alterar_status_conteudo"
                                >


                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= salaAbrirEscapar(
                                        $csrfToken
                                    ) ?>"
                                >


                                <input
                                    type="hidden"
                                    name="conteudo_id"
                                    value="<?= $conteudoId ?>"
                                >


                                <input
                                    type="hidden"
                                    name="novo_status"
                                    value="publicado"
                                >


                                <button
                                    type="submit"
                                    class="botao-principal"
                                >

                                    Publicar

                                </button>


                            </form>


                        <?php endif; ?>


                        <form
                            method="POST"
                            action="<?= salaAbrirEscapar(
                                $linkAbrirSala
                            ) ?>"
                        >


                            <input
                                type="hidden"
                                name="acao"
                                value="excluir_conteudo"
                            >


                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= salaAbrirEscapar(
                                    $csrfToken
                                ) ?>"
                            >


                            <input
                                type="hidden"
                                name="conteudo_id"
                                value="<?= $conteudoId ?>"
                            >


                            <button
                                type="submit"
                                class="botao-secundario"
                            >

                                Excluir publicação

                            </button>


                        </form>


                    </div>


                </article>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</section>


<!-- =====================================================
ALUNOS
====================================================== -->

<section
    class="painel-acessos"
    id="alunos-sala"
>


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">

            PARTICIPANTES

        </span>


        <h2>

            Alunos da turma

        </h2>


        <p>

            Estudantes que utilizaram o código

            <strong>

                <?= salaAbrirEscapar(
                    $codigoSala
                ) ?>

            </strong>

            para entrar nesta sala.

        </p>


    </div>


    <?php if (
        empty(
            $alunos
        )
    ): ?>


        <div class="sala-professor-vazio">


            <span class="sala-professor-vazio-icone">

                AL

            </span>


            <h3>

                Nenhum aluno ainda

            </h3>


            <p>

                Compartilhe o código

                <strong>

                    <?= salaAbrirEscapar(
                        $codigoSala
                    ) ?>

                </strong>

                com seus estudantes.

            </p>


        </div>


    <?php else: ?>


        <div class="painel-acessos-grid">


            <?php foreach (
                $alunos as $aluno
            ): ?>


                <?php

                if (
                    !is_array(
                        $aluno
                    )
                ) {

                    continue;

                }


                $nomeAluno =
                    trim(
                        (string) (
                            $aluno["nome"]
                            ??
                            $aluno["nome_usuario"]
                            ??
                            "Estudante"
                        )
                    );


                $emailAluno =
                    trim(
                        (string) (
                            $aluno["email"]
                            ??
                            ""
                        )
                    );

                ?>


                <article class="acesso-card">


                    <div class="acesso-card-topo">


                        <span class="acesso-icone">

                            AL

                        </span>


                        <span class="acesso-status acesso-disponivel">

                            Participante

                        </span>


                    </div>


                    <div>


                        <small>

                            ESTUDANTE

                        </small>


                        <h3>

                            <?= salaAbrirEscapar(
                                $nomeAluno
                            ) ?>

                        </h3>


                        <?php if (
                            $emailAluno !== ""
                        ): ?>


                            <p>

                                <?= salaAbrirEscapar(
                                    $emailAluno
                                ) ?>

                            </p>


                        <?php else: ?>


                            <p>

                                Participante da Sala de Aula.

                            </p>


                        <?php endif; ?>


                    </div>


                </article>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</section>


<!-- =====================================================
AÇÕES FINAIS
====================================================== -->

<section class="painel-apresentacao-acoes">


    <a
        href="<?= salaAbrirEscapar(
            $linkSalas
        ) ?>"
        class="botao-secundario"
    >

        ← Voltar às salas

    </a>


    <a
        href="<?= salaAbrirEscapar(
            $linkPainel
        ) ?>"
        class="botao-secundario"
    >

        Painel do Professor

    </a>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../../../componentes/footer.php";

?>


<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


</body>

</html>