<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| ARQUIVO PROTEGIDO — SALA GLOBAL
|
| Entrega arquivos anexados às publicações da Sala Global.
|
| O arquivo somente poderá ser acessado quando:
|
| - o usuário estiver autenticado;
| - o arquivo existir no banco;
| - a publicação existir;
| - a publicação estiver publicada;
| - o arquivo físico existir;
| - o arquivo estiver no storage autorizado;
| - extensão, tamanho e conteúdo forem válidos.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . "/../config/helpers.php";

require_once __DIR__ . "/../models/PublicacaoGlobal.php";
require_once __DIR__ . "/../models/ArquivoPublicacaoGlobal.php";

require_once __DIR__ . "/../services/UploadPublicacaoService.php";


/*
|--------------------------------------------------------------------------
| HEADERS DE SEGURANÇA
|--------------------------------------------------------------------------
*/

header(
    "X-Content-Type-Options: nosniff"
);


header(
    "X-Frame-Options: SAMEORIGIN"
);


header(
    "Referrer-Policy: same-origin"
);


header(
    "Cross-Origin-Resource-Policy: same-origin"
);


header(
    "Cache-Control: private, no-store, no-cache, must-revalidate, max-age=0"
);


header(
    "Pragma: no-cache"
);


/*
|--------------------------------------------------------------------------
| SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| LIMITES
|--------------------------------------------------------------------------
*/

const SALA_GLOBAL_ARQUIVO_TAMANHO_MAXIMO =
    10 * 1024 * 1024;


/*
|--------------------------------------------------------------------------
| ERRO
|--------------------------------------------------------------------------
*/

function erroArquivoSalaGlobal(
    string $mensagem,
    int $codigo = 400
): never {

    /*
    |--------------------------------------------------------------------------
    | LIMPA BUFFERS
    |--------------------------------------------------------------------------
    */

    while (
        ob_get_level() > 0
    ) {

        @ob_end_clean();

    }


    /*
    |--------------------------------------------------------------------------
    | STATUS HTTP
    |--------------------------------------------------------------------------
    */

    http_response_code(
        $codigo
    );


    /*
    |--------------------------------------------------------------------------
    | CONTEÚDO
    |--------------------------------------------------------------------------
    */

    header(
        "Content-Type: text/plain; charset=UTF-8"
    );


    echo $mensagem;


    exit;

}


/*
|--------------------------------------------------------------------------
| NOME ORIGINAL SEGURO
|--------------------------------------------------------------------------
*/

function nomeSeguroArquivoSalaGlobal(
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
            trim(
                $nome
            )
        );


    /*
    |--------------------------------------------------------------------------
    | REMOVE POSSÍVEL CAMINHO
    |--------------------------------------------------------------------------
    */

    $nome =
        basename(
            $nome
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


    $nome =
        trim(
            $nome
        );


    /*
    |--------------------------------------------------------------------------
    | LIMITA TAMANHO
    |--------------------------------------------------------------------------
    */

    if (
        function_exists(
            "mb_substr"
        )
    ) {

        $nome =
            mb_substr(
                $nome,
                0,
                180,
                "UTF-8"
            );

    }
    else {

        $nome =
            substr(
                $nome,
                0,
                180
            );

    }


    return
        $nome !== ""
            ?
            $nome
            :
            "arquivo";

}


/*
|--------------------------------------------------------------------------
| NOME ASCII
|--------------------------------------------------------------------------
*/

function nomeAsciiArquivoSalaGlobal(
    string $nome
): string {

    $nomeAscii =
        preg_replace(
            "/[^A-Za-z0-9._ -]/",
            "_",
            $nome
        );


    if (
        !is_string(
            $nomeAscii
        )
        ||
        trim(
            $nomeAscii
        )
        === ""
    ) {

        return "arquivo";

    }


    return trim(
        $nomeAscii
    );

}


/*
|--------------------------------------------------------------------------
| MIME CANÔNICO
|--------------------------------------------------------------------------
|
| É o Content-Type que enviaremos ao navegador depois que o
| conteúdo físico for validado.
|
|--------------------------------------------------------------------------
*/

function mimeCanonicoArquivoSalaGlobal(
    string $extensao
): ?string {

    return match (
        strtolower(
            trim(
                $extensao
            )
        )
    ) {

        "pdf" =>
            "application/pdf",

        "doc" =>
            "application/msword",

        "docx" =>
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",

        "ppt" =>
            "application/vnd.ms-powerpoint",

        "pptx" =>
            "application/vnd.openxmlformats-officedocument.presentationml.presentation",

        "txt" =>
            "text/plain",

        "png" =>
            "image/png",

        "jpg",
        "jpeg" =>
            "image/jpeg",

        "webp" =>
            "image/webp",

        default =>
            null

    };

}


/*
|--------------------------------------------------------------------------
| MIME REAL COMPATÍVEL?
|--------------------------------------------------------------------------
*/

function mimeCompativelArquivoSalaGlobal(
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


    $permitidos = [

        "pdf" => [

            "application/pdf"

        ],

        "doc" => [

            "application/msword",
            "application/vnd.ms-office",
            "application/x-ole-storage",
            "application/x-cfb",
            "application/octet-stream"

        ],

        "docx" => [

            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/zip",
            "application/x-zip",
            "application/x-zip-compressed",
            "application/octet-stream"

        ],

        "ppt" => [

            "application/vnd.ms-powerpoint",
            "application/vnd.ms-office",
            "application/x-ole-storage",
            "application/x-cfb",
            "application/octet-stream"

        ],

        "pptx" => [

            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "application/zip",
            "application/x-zip",
            "application/x-zip-compressed",
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


    if (
        !isset(
            $permitidos[$extensao]
        )
    ) {

        return false;

    }


    return in_array(
        $mime,
        $permitidos[$extensao],
        true
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA OFFICE ANTIGO
|--------------------------------------------------------------------------
|
| DOC/PPT utilizam Compound File Binary Format.
|
|--------------------------------------------------------------------------
*/

function arquivoOleValidoSalaGlobal(
    string $caminho
): bool {

    $handle =
        @fopen(
            $caminho,
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


    return
        $assinatura ===
        "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1";

}


/*
|--------------------------------------------------------------------------
| VALIDA OFFICE OPEN XML
|--------------------------------------------------------------------------
|
| DOCX/PPTX são ZIPs.
|
| Não basta o MIME indicar ZIP:
| confirmamos também a estrutura interna esperada.
|
|--------------------------------------------------------------------------
*/

function arquivoOfficeXmlValidoSalaGlobal(
    string $caminho,
    string $tipo
): bool {

    if (
        !class_exists(
            "ZipArchive"
        )
    ) {

        return false;

    }


    $zip =
        new ZipArchive();


    $resultado =
        $zip->open(
            $caminho
        );


    if (
        $resultado !== true
    ) {

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | ESTRUTURA BÁSICA
    |--------------------------------------------------------------------------
    */

    $possuiContentTypes =
        $zip->locateName(
            "[Content_Types].xml"
        )
        !== false;


    if (
        !$possuiContentTypes
    ) {

        $zip->close();


        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | DOCX
    |--------------------------------------------------------------------------
    */

    if (
        $tipo === "docx"
    ) {

        $valido =
            $zip->locateName(
                "word/document.xml"
            )
            !== false;


        $zip->close();


        return $valido;

    }


    /*
    |--------------------------------------------------------------------------
    | PPTX
    |--------------------------------------------------------------------------
    */

    if (
        $tipo === "pptx"
    ) {

        $valido =
            $zip->locateName(
                "ppt/presentation.xml"
            )
            !== false;


        $zip->close();


        return $valido;

    }


    $zip->close();


    return false;

}


/*
|--------------------------------------------------------------------------
| ARQUIVO ESTÁ DENTRO DO DIRETÓRIO?
|--------------------------------------------------------------------------
*/

function arquivoDentroDiretorioSalaGlobal(
    string $arquivo,
    string $diretorio
): bool {

    $arquivo =
        str_replace(
            "\\",
            "/",
            $arquivo
        );


    $diretorio =
        rtrim(
            str_replace(
                "\\",
                "/",
                $diretorio
            ),
            "/"
        );


    /*
    |--------------------------------------------------------------------------
    | WINDOWS
    |--------------------------------------------------------------------------
    */

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


    return
        $arquivo === $diretorio
        ||
        str_starts_with(
            $arquivo,
            $diretorio . "/"
        );

}


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

$usuario =
    $_SESSION["usuario"]
    ??
    null;


if (
    !is_array(
        $usuario
    )
) {

    erroArquivoSalaGlobal(
        "Você precisa estar conectado.",
        401
    );

}


/*
|--------------------------------------------------------------------------
| ID DO USUÁRIO
|--------------------------------------------------------------------------
*/

$usuarioId =
    filter_var(
        $usuario["id"]
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
    $usuarioId === false
    ||
    $usuarioId === null
) {

    erroArquivoSalaGlobal(
        "Sessão inválida.",
        401
    );

}


$usuarioId =
    (int) $usuarioId;


/*
|--------------------------------------------------------------------------
| MÉTODO HTTP
|--------------------------------------------------------------------------
*/

$metodo =
    strtoupper(
        trim(
            (string) (
                $_SERVER["REQUEST_METHOD"]
                ??
                ""
            )
        )
    );


if (
    $metodo !== "GET"
) {

    header(
        "Allow: GET"
    );


    erroArquivoSalaGlobal(
        "Método não permitido.",
        405
    );

}


/*
|--------------------------------------------------------------------------
| ID DO ARQUIVO
|--------------------------------------------------------------------------
|
| A rota:
|
| /sala-global/arquivo/15
|
| injeta:
|
| $_GET["id"] = 15
|
|--------------------------------------------------------------------------
*/

$arquivoId =
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
    $arquivoId === false
    ||
    $arquivoId === null
) {

    erroArquivoSalaGlobal(
        "Arquivo inválido.",
        400
    );

}


$arquivoId =
    (int) $arquivoId;


/*
|--------------------------------------------------------------------------
| DEPENDÊNCIAS
|--------------------------------------------------------------------------
*/

try {

    $modelArquivo =
        new ArquivoPublicacaoGlobal();


    $modelPublicacao =
        new PublicacaoGlobal();


    $upload =
        new UploadPublicacaoService();

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaGlobalArquivo] Falha ao iniciar dependências: "
        .
        $erro->getMessage()
    );


    erroArquivoSalaGlobal(
        "Não foi possível abrir o arquivo.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| BUSCAR ARQUIVO
|--------------------------------------------------------------------------
*/

try {

    $arquivo =
        $modelArquivo->buscarPorId(
            $arquivoId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaGlobalArquivo] Erro ao buscar arquivo "
        .
        $arquivoId
        .
        ": "
        .
        $erro->getMessage()
    );


    erroArquivoSalaGlobal(
        "Não foi possível abrir o arquivo.",
        500
    );

}


if (
    !is_array(
        $arquivo
    )
) {

    erroArquivoSalaGlobal(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| PUBLICAÇÃO DO ARQUIVO
|--------------------------------------------------------------------------
*/

$publicacaoId =
    filter_var(
        $arquivo["publicacao_id"]
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
    $publicacaoId === false
    ||
    $publicacaoId === null
) {

    erroArquivoSalaGlobal(
        "Arquivo inválido.",
        404
    );

}


$publicacaoId =
    (int) $publicacaoId;


/*
|--------------------------------------------------------------------------
| BUSCAR PUBLICAÇÃO
|--------------------------------------------------------------------------
*/

try {

    $publicacao =
        $modelPublicacao->buscarPorId(
            $publicacaoId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaGlobalArquivo] Erro ao buscar publicação "
        .
        $publicacaoId
        .
        ": "
        .
        $erro->getMessage()
    );


    erroArquivoSalaGlobal(
        "Não foi possível abrir o arquivo.",
        500
    );

}


if (
    !is_array(
        $publicacao
    )
) {

    erroArquivoSalaGlobal(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| PUBLICAÇÃO PRECISA ESTAR PUBLICADA
|--------------------------------------------------------------------------
*/

$statusRecebido =
    $publicacao["status"]
    ??
    null;


if (
    !is_string(
        $statusRecebido
    )
    ||
    strtolower(
        trim(
            $statusRecebido
        )
    )
    !== "publicado"
) {

    /*
    |--------------------------------------------------------------------------
    | 404 não revela se existe anexo de uma publicação removida/oculta.
    |--------------------------------------------------------------------------
    */

    erroArquivoSalaGlobal(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| METADADOS
|--------------------------------------------------------------------------
*/

$nomeArquivoRecebido =
    $arquivo["nome_arquivo"]
    ??
    null;


$extensaoRecebida =
    $arquivo["extensao"]
    ??
    null;


$nomeOriginalRecebido =
    $arquivo["nome_original"]
    ??
    "arquivo";


if (
    !is_string(
        $nomeArquivoRecebido
    )
    ||
    !is_string(
        $extensaoRecebida
    )
    ||
    !is_string(
        $nomeOriginalRecebido
    )
) {

    error_log(
        "[SalaGlobalArquivo] Metadados inválidos no arquivo "
        .
        $arquivoId
    );


    erroArquivoSalaGlobal(
        "Arquivo indisponível.",
        409
    );

}


/*
|--------------------------------------------------------------------------
| NORMALIZA METADADOS
|--------------------------------------------------------------------------
*/

$nomeArquivo =
    trim(
        $nomeArquivoRecebido
    );


$extensao =
    strtolower(
        trim(
            $extensaoRecebida
        )
    );


$nomeOriginal =
    nomeSeguroArquivoSalaGlobal(
        $nomeOriginalRecebido
    );


/*
|--------------------------------------------------------------------------
| EXTENSÃO DO NOME INTERNO
|--------------------------------------------------------------------------
*/

$extensaoNomeInterno =
    strtolower(
        trim(
            pathinfo(
                $nomeArquivo,
                PATHINFO_EXTENSION
            )
        )
    );


if (
    $extensao === ""
    ||
    $extensaoNomeInterno === ""
    ||
    $extensao !==
    $extensaoNomeInterno
) {

    error_log(
        "[SalaGlobalArquivo] Extensão divergente no arquivo "
        .
        $arquivoId
    );


    erroArquivoSalaGlobal(
        "Arquivo indisponível.",
        409
    );

}


/*
|--------------------------------------------------------------------------
| MIME CANÔNICO
|--------------------------------------------------------------------------
*/

$mimeCanonico =
    mimeCanonicoArquivoSalaGlobal(
        $extensao
    );


if (
    $mimeCanonico === null
) {

    erroArquivoSalaGlobal(
        "Tipo de arquivo não permitido.",
        415
    );

}


/*
|--------------------------------------------------------------------------
| CAMINHO ABSOLUTO
|--------------------------------------------------------------------------
|
| caminhoAbsoluto() também valida o padrão do nome interno:
|
| 48 hexadecimais + extensão permitida.
|
|--------------------------------------------------------------------------
*/

$caminhoCandidato =
    $upload->caminhoAbsoluto(
        $nomeArquivo
    );


if (
    $caminhoCandidato === ""
) {

    erroArquivoSalaGlobal(
        "Arquivo inválido.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| BLOQUEIA LINK SIMBÓLICO
|--------------------------------------------------------------------------
*/

if (
    is_link(
        $caminhoCandidato
    )
) {

    error_log(
        "[SalaGlobalArquivo] Link simbólico bloqueado. Arquivo "
        .
        $arquivoId
    );


    erroArquivoSalaGlobal(
        "Arquivo indisponível.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| CAMINHO REAL
|--------------------------------------------------------------------------
*/

$caminhoReal =
    realpath(
        $caminhoCandidato
    );


if (
    $caminhoReal === false
    ||
    !is_file(
        $caminhoReal
    )
    ||
    !is_readable(
        $caminhoReal
    )
) {

    erroArquivoSalaGlobal(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| DIRETÓRIO AUTORIZADO
|--------------------------------------------------------------------------
*/

$diretorioEsperado =
    realpath(
        dirname(
            $caminhoCandidato
        )
    );


if (
    $diretorioEsperado === false
    ||
    !is_dir(
        $diretorioEsperado
    )
) {

    erroArquivoSalaGlobal(
        "Arquivo indisponível.",
        500
    );

}


if (
    !arquivoDentroDiretorioSalaGlobal(
        $caminhoReal,
        $diretorioEsperado
    )
) {

    error_log(
        "[SalaGlobalArquivo] Arquivo fora do diretório autorizado. ID "
        .
        $arquivoId
    );


    erroArquivoSalaGlobal(
        "Arquivo indisponível.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| CONFERE NOME FÍSICO
|--------------------------------------------------------------------------
*/

$nomeFisico =
    basename(
        $caminhoReal
    );


if (
    DIRECTORY_SEPARATOR === "\\"
) {

    $nomeConfere =
        strcasecmp(
            $nomeFisico,
            $nomeArquivo
        )
        === 0;

}
else {

    $nomeConfere =
        hash_equals(
            $nomeFisico,
            $nomeArquivo
        );

}


if (
    !$nomeConfere
) {

    error_log(
        "[SalaGlobalArquivo] Nome físico divergente. Arquivo "
        .
        $arquivoId
    );


    erroArquivoSalaGlobal(
        "Arquivo indisponível.",
        409
    );

}


/*
|--------------------------------------------------------------------------
| TAMANHO REAL
|--------------------------------------------------------------------------
*/

$tamanhoReal =
    @filesize(
        $caminhoReal
    );


if (
    $tamanhoReal === false
    ||
    $tamanhoReal <= 0
) {

    erroArquivoSalaGlobal(
        "Arquivo inválido.",
        404
    );

}


$tamanhoReal =
    (int) $tamanhoReal;


/*
|--------------------------------------------------------------------------
| LIMITE
|--------------------------------------------------------------------------
*/

if (
    $tamanhoReal >
    SALA_GLOBAL_ARQUIVO_TAMANHO_MAXIMO
) {

    erroArquivoSalaGlobal(
        "Arquivo maior que o limite permitido.",
        413
    );

}


/*
|--------------------------------------------------------------------------
| TAMANHO SALVO NO BANCO
|--------------------------------------------------------------------------
*/

$tamanhoBanco =
    filter_var(
        $arquivo["tamanho"]
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
    $tamanhoBanco === false
    ||
    $tamanhoBanco === null
) {

    error_log(
        "[SalaGlobalArquivo] Tamanho inválido no banco. Arquivo "
        .
        $arquivoId
    );


    erroArquivoSalaGlobal(
        "Arquivo indisponível.",
        409
    );

}


$tamanhoBanco =
    (int) $tamanhoBanco;


/*
|--------------------------------------------------------------------------
| BANCO X DISCO
|--------------------------------------------------------------------------
*/

if (
    $tamanhoBanco !==
    $tamanhoReal
) {

    error_log(
        "[SalaGlobalArquivo] Tamanho divergente. Arquivo "
        .
        $arquivoId
        .
        ". Banco="
        .
        $tamanhoBanco
        .
        "; Disco="
        .
        $tamanhoReal
    );


    erroArquivoSalaGlobal(
        "O arquivo não pôde ser validado.",
        409
    );

}


/*
|--------------------------------------------------------------------------
| FILEINFO É OBRIGATÓRIO
|--------------------------------------------------------------------------
|
| Não confiamos no MIME salvo no banco como fallback.
|
|--------------------------------------------------------------------------
*/

if (
    !class_exists(
        "finfo"
    )
) {

    error_log(
        "[SalaGlobalArquivo] Extensão fileinfo não disponível."
    );


    erroArquivoSalaGlobal(
        "Não foi possível validar o tipo do arquivo.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| MIME REAL
|--------------------------------------------------------------------------
*/

try {

    $finfo =
        new finfo(
            FILEINFO_MIME_TYPE
        );


    $mimeReal =
        $finfo->file(
            $caminhoReal
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaGlobalArquivo] Falha ao identificar MIME do arquivo "
        .
        $arquivoId
        .
        ": "
        .
        $erro->getMessage()
    );


    erroArquivoSalaGlobal(
        "Não foi possível validar o arquivo.",
        500
    );

}


if (
    !is_string(
        $mimeReal
    )
    ||
    trim(
        $mimeReal
    )
    === ""
) {

    erroArquivoSalaGlobal(
        "Não foi possível identificar o tipo do arquivo.",
        415
    );

}


$mimeReal =
    strtolower(
        trim(
            $mimeReal
        )
    );


/*
|--------------------------------------------------------------------------
| MIME X EXTENSÃO
|--------------------------------------------------------------------------
*/

if (
    !mimeCompativelArquivoSalaGlobal(
        $extensao,
        $mimeReal
    )
) {

    error_log(
        "[SalaGlobalArquivo] MIME incompatível. Arquivo "
        .
        $arquivoId
        .
        ", extensão "
        .
        $extensao
        .
        ", MIME "
        .
        $mimeReal
    );


    erroArquivoSalaGlobal(
        "O tipo do arquivo não pôde ser validado.",
        415
    );

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO EXTRA — IMAGEM
|--------------------------------------------------------------------------
*/

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
) {

    $imagem =
        @getimagesize(
            $caminhoReal
        );


    if (
        !is_array(
            $imagem
        )
        ||
        empty(
            $imagem[0]
        )
        ||
        empty(
            $imagem[1]
        )
    ) {

        erroArquivoSalaGlobal(
            "A imagem não pôde ser validada.",
            415
        );

    }

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO EXTRA — PDF
|--------------------------------------------------------------------------
*/

if (
    $extensao === "pdf"
) {

    $handlePdf =
        @fopen(
            $caminhoReal,
            "rb"
        );


    if (
        $handlePdf === false
    ) {

        erroArquivoSalaGlobal(
            "O PDF não pôde ser aberto.",
            500
        );

    }


    $assinaturaPdf =
        fread(
            $handlePdf,
            5
        );


    fclose(
        $handlePdf
    );


    if (
        $assinaturaPdf !== "%PDF-"
    ) {

        erroArquivoSalaGlobal(
            "O PDF não pôde ser validado.",
            415
        );

    }

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO EXTRA — DOC / PPT
|--------------------------------------------------------------------------
*/

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
    !arquivoOleValidoSalaGlobal(
        $caminhoReal
    )
) {

    erroArquivoSalaGlobal(
        "O arquivo Office não pôde ser validado.",
        415
    );

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO EXTRA — DOCX / PPTX
|--------------------------------------------------------------------------
*/

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
    !arquivoOfficeXmlValidoSalaGlobal(
        $caminhoReal,
        $extensao
    )
) {

    erroArquivoSalaGlobal(
        "O arquivo Office não pôde ser validado.",
        415
    );

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO EXTRA — TXT
|--------------------------------------------------------------------------
*/

if (
    $extensao === "txt"
) {

    $amostra =
        @file_get_contents(
            $caminhoReal,
            false,
            null,
            0,
            min(
                8192,
                $tamanhoReal
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

        erroArquivoSalaGlobal(
            "O arquivo TXT não pôde ser validado.",
            415
        );

    }

}


/*
|--------------------------------------------------------------------------
| DOWNLOAD?
|--------------------------------------------------------------------------
|
| Visualização:
|
| /sala-global/arquivo/{id}
|
| Download:
|
| /sala-global/arquivo/{id}?download=1
|
|--------------------------------------------------------------------------
*/

$download =
    isset(
        $_GET["download"]
    )
    &&
    is_scalar(
        $_GET["download"]
    )
    &&
    (string) $_GET["download"]
    === "1";


$disposicao =
    $download
        ?
        "attachment"
        :
        "inline";


/*
|--------------------------------------------------------------------------
| NOMES PARA CONTENT-DISPOSITION
|--------------------------------------------------------------------------
*/

$nomeAscii =
    nomeAsciiArquivoSalaGlobal(
        $nomeOriginal
    );


/*
|--------------------------------------------------------------------------
| ABRE O ARQUIVO ANTES DOS HEADERS FINAIS
|--------------------------------------------------------------------------
*/

$handle =
    @fopen(
        $caminhoReal,
        "rb"
    );


if (
    $handle === false
) {

    erroArquivoSalaGlobal(
        "Não foi possível abrir o arquivo.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| LIBERA O LOCK DA SESSÃO
|--------------------------------------------------------------------------
|
| Não precisamos manter a sessão PHP bloqueada enquanto o navegador
| recebe os bytes.
|
|--------------------------------------------------------------------------
*/

if (
    session_status()
    === PHP_SESSION_ACTIVE
) {

    session_write_close();

}


/*
|--------------------------------------------------------------------------
| HEADERS DO ARQUIVO
|--------------------------------------------------------------------------
*/

header(
    "Content-Type: "
    .
    $mimeCanonico
);


header(
    "Content-Length: "
    .
    $tamanhoReal
);


header(
    "Content-Disposition: "
    .
    $disposicao
    .
    "; filename=\""
    .
    addcslashes(
        $nomeAscii,
        "\\\""
    )
    .
    "\"; filename*=UTF-8''"
    .
    rawurlencode(
        $nomeOriginal
    )
);


header(
    "Accept-Ranges: none"
);


/*
|--------------------------------------------------------------------------
| CSP COMPLEMENTAR
|--------------------------------------------------------------------------
*/

header(
    "Content-Security-Policy: default-src 'none'; sandbox"
);


/*
|--------------------------------------------------------------------------
| LIMPA BUFFERS
|--------------------------------------------------------------------------
*/

while (
    ob_get_level() > 0
) {

    @ob_end_clean();

}


/*
|--------------------------------------------------------------------------
| STREAM
|--------------------------------------------------------------------------
*/

$bloco =
    8192;


while (
    !feof(
        $handle
    )
) {

    $dados =
        fread(
            $handle,
            $bloco
        );


    if (
        $dados === false
    ) {

        error_log(
            "[SalaGlobalArquivo] Falha durante leitura do arquivo "
            .
            $arquivoId
        );


        break;

    }


    if (
        $dados === ""
    ) {

        continue;

    }


    echo $dados;


    if (
        connection_status()
        !== CONNECTION_NORMAL
    ) {

        break;

    }

}


fclose(
    $handle
);


exit;