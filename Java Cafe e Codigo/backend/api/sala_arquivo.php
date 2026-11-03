<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| ARQUIVO PROTEGIDO DA SALA — ESTUDANTE
|
| Permite ao estudante acessar arquivos de conteúdos publicados
| pertencentes às salas em que ele participa.
|
| Fluxo:
|
| sessão
| → perfil Estudante
| → participação na sala
| → conteúdo publicado
| → arquivo anexado ao conteúdo
| → validação física
| → stream
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . "/../config/helpers.php";

require_once __DIR__ . "/../Controller/PerfilController.php";
require_once __DIR__ . "/../Controller/SalaController.php";


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
| LIMITE
|--------------------------------------------------------------------------
*/

const SALA_ARQUIVO_TAMANHO_MAXIMO =
    10 * 1024 * 1024;


/*
|--------------------------------------------------------------------------
| RESPOSTA DE ERRO
|--------------------------------------------------------------------------
*/

function salaArquivoErro(
    string $mensagem,
    int $codigoHttp = 400
): never {

    while (
        ob_get_level() > 0
    ) {

        @ob_end_clean();

    }


    http_response_code(
        $codigoHttp
    );


    header(
        "Content-Type: text/plain; charset=UTF-8"
    );


    echo $mensagem;


    exit;

}


/*
|--------------------------------------------------------------------------
| NORMALIZAR CAMINHO
|--------------------------------------------------------------------------
*/

function salaArquivoNormalizarCaminho(
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
| CAMINHO ESTÁ DENTRO DO DIRETÓRIO?
|--------------------------------------------------------------------------
*/

function salaArquivoDentroDoDiretorio(
    string $arquivo,
    string $diretorio
): bool {

    $arquivo =
        salaArquivoNormalizarCaminho(
            $arquivo
        );


    $diretorio =
        rtrim(
            salaArquivoNormalizarCaminho(
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
| NOME SEGURO
|--------------------------------------------------------------------------
*/

function salaArquivoNomeSeguro(
    string $nome
): string {

    $nome =
        salaArquivoNormalizarCaminho(
            $nome
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


    $nome =
        trim(
            $nome
        );


    /*
    |--------------------------------------------------------------------------
    | LIMITA O NOME ENVIADO NO HEADER
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

function salaArquivoNomeAscii(
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
*/

function salaArquivoMimeCanonico(
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
| MIME REAL COMPATÍVEL
|--------------------------------------------------------------------------
*/

function salaArquivoMimeCompativel(
    string $extensao,
    string $mimeReal
): bool {

    $extensao =
        strtolower(
            trim(
                $extensao
            )
        );


    $mimeReal =
        strtolower(
            trim(
                $mimeReal
            )
        );


    $permitidos = [

        "pdf" => [

            "application/pdf"

        ],

        "doc" => [

            "application/msword",
            "application/x-ole-storage",
            "application/x-cfb",
            "application/vnd.ms-office",
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
            "application/x-ole-storage",
            "application/x-cfb",
            "application/vnd.ms-office",
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
        $mimeReal,
        $permitidos[$extensao],
        true
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

    salaArquivoErro(
        "Você precisa estar conectado.",
        401
    );

}


/*
|--------------------------------------------------------------------------
| USUÁRIO
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

    salaArquivoErro(
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


    salaArquivoErro(
        "Método não permitido.",
        405
    );

}


/*
|--------------------------------------------------------------------------
| ID DA SALA
|--------------------------------------------------------------------------
|
| O Router injeta:
|
| $_GET["sala_id"]
|
|--------------------------------------------------------------------------
*/

$salaId =
    filter_var(
        $_GET["sala_id"]
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

    salaArquivoErro(
        "Sala inválida.",
        400
    );

}


$salaId =
    (int) $salaId;


/*
|--------------------------------------------------------------------------
| ID DO ARQUIVO
|--------------------------------------------------------------------------
*/

$arquivoId =
    filter_var(
        $_GET["arquivo_id"]
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

    salaArquivoErro(
        "Arquivo inválido.",
        400
    );

}


$arquivoId =
    (int) $arquivoId;


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
        "[SalaArquivo] Erro ao iniciar dependências: "
        .
        $erro->getMessage()
    );


    salaArquivoErro(
        "Não foi possível abrir o arquivo.",
        500
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
        "[SalaArquivo] Erro ao consultar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    salaArquivoErro(
        "Não foi possível validar seu acesso.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| RESULTADO DO PERFIL
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $resultadoPerfil
    )
    ||
    ($resultadoPerfil["status"] ?? false)
    !== true
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

    salaArquivoErro(
        "Você não possui acesso a este arquivo.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL ESTUDANTE
|--------------------------------------------------------------------------
*/

$tipoRecebido =
    $resultadoPerfil["perfil"]["usuario"]["tipo_usuario"]
    ??
    null;


if (
    !is_string(
        $tipoRecebido
    )
) {

    salaArquivoErro(
        "Você não possui acesso a este arquivo.",
        403
    );

}


$tipoUsuario =
    strtolower(
        trim(
            $tipoRecebido
        )
    );


if (
    $tipoUsuario !== PROFILE_ESTUDANTE
) {

    salaArquivoErro(
        "Você não possui acesso a este arquivo.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL ESPECÍFICO DO ESTUDANTE
|--------------------------------------------------------------------------
*/

$perfilEstudante =
    $resultadoPerfil["perfil"]["estudante"]
    ??
    null;


if (
    !is_array(
        $perfilEstudante
    )
) {

    salaArquivoErro(
        "Você não possui acesso a este arquivo.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| CONTEÚDOS AUTORIZADOS
|--------------------------------------------------------------------------
|
| listarConteudosAluno() já exige:
|
| - sala existente;
| - participação do estudante;
| - somente conteúdo disponível para estudante.
|
|--------------------------------------------------------------------------
*/

try {

    $resultadoConteudos =
        $salaController->listarConteudosAluno(
            $salaId,
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaArquivo] Erro ao validar conteúdos. Usuário "
        .
        $usuarioId
        .
        ", sala "
        .
        $salaId
        .
        ": "
        .
        $erro->getMessage()
    );


    salaArquivoErro(
        "Não foi possível validar o acesso ao arquivo.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| ACESSO À SALA
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $resultadoConteudos
    )
    ||
    ($resultadoConteudos["status"] ?? false)
    !== true
) {

    /*
    |--------------------------------------------------------------------------
    | Retornamos 404 para não revelar sala/arquivo para terceiros.
    |--------------------------------------------------------------------------
    */

    salaArquivoErro(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| CONTEÚDOS
|--------------------------------------------------------------------------
*/

$conteudos =
    $resultadoConteudos["conteudos"]
    ??
    [];


if (
    !is_array(
        $conteudos
    )
) {

    $conteudos =
        [];

}


/*
|--------------------------------------------------------------------------
| LOCALIZA ARQUIVO DENTRO DOS CONTEÚDOS AUTORIZADOS
|--------------------------------------------------------------------------
*/

$arquivo =
    null;


foreach (
    $conteudos as
    $conteudo
) {

    if (
        !is_array(
            $conteudo
        )
    ) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | SALA
    |--------------------------------------------------------------------------
    */

    $conteudoSalaId =
        (int) (
            $conteudo["sala_id"]
            ??
            0
        );


    if (
        $conteudoSalaId !== $salaId
    ) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    $statusRecebido =
        $conteudo["status"]
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

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | ARQUIVOS
    |--------------------------------------------------------------------------
    */

    $arquivosConteudo =
        $conteudo["arquivos"]
        ??
        [];


    if (
        !is_array(
            $arquivosConteudo
        )
    ) {

        continue;

    }


    foreach (
        $arquivosConteudo as
        $arquivoConteudo
    ) {

        if (
            !is_array(
                $arquivoConteudo
            )
        ) {

            continue;

        }


        if (
            (int) (
                $arquivoConteudo["id"]
                ??
                0
            )
            !== $arquivoId
        ) {

            continue;

        }


        $arquivo =
            $arquivoConteudo;


        break 2;

    }

}


/*
|--------------------------------------------------------------------------
| NÃO ENCONTRADO
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $arquivo
    )
) {

    salaArquivoErro(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| METADADOS
|--------------------------------------------------------------------------
*/

$caminhoRecebido =
    $arquivo["caminho"]
    ??
    null;


$nomeInternoRecebido =
    $arquivo["nome_arquivo"]
    ??
    null;


$nomeOriginalRecebido =
    $arquivo["nome_original"]
    ??
    "arquivo";


$extensaoRecebida =
    $arquivo["extensao"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| TIPOS DOS METADADOS
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $caminhoRecebido
    )
    ||
    !is_string(
        $nomeInternoRecebido
    )
    ||
    !is_string(
        $nomeOriginalRecebido
    )
    ||
    !is_string(
        $extensaoRecebida
    )
) {

    error_log(
        "[SalaArquivo] Metadados inválidos. Arquivo "
        .
        $arquivoId
    );


    salaArquivoErro(
        "Arquivo indisponível.",
        409
    );

}


/*
|--------------------------------------------------------------------------
| NORMALIZA METADADOS
|--------------------------------------------------------------------------
*/

$caminhoBanco =
    salaArquivoNormalizarCaminho(
        $caminhoRecebido
    );


$nomeInterno =
    trim(
        $nomeInternoRecebido
    );


$nomeOriginal =
    salaArquivoNomeSeguro(
        $nomeOriginalRecebido
    );


$extensao =
    strtolower(
        trim(
            $extensaoRecebida
        )
    );


/*
|--------------------------------------------------------------------------
| CAMPOS OBRIGATÓRIOS
|--------------------------------------------------------------------------
*/

if (
    $caminhoBanco === ""
    ||
    $nomeInterno === ""
    ||
    $extensao === ""
) {

    salaArquivoErro(
        "Arquivo indisponível.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| EXTENSÃO DO NOME INTERNO
|--------------------------------------------------------------------------
*/

$extensaoNome =
    strtolower(
        trim(
            pathinfo(
                $nomeInterno,
                PATHINFO_EXTENSION
            )
        )
    );


if (
    $extensaoNome === ""
    ||
    $extensaoNome !==
    $extensao
) {

    error_log(
        "[SalaArquivo] Extensão divergente. Arquivo "
        .
        $arquivoId
    );


    salaArquivoErro(
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
    salaArquivoMimeCanonico(
        $extensao
    );


if (
    $mimeCanonico === null
) {

    salaArquivoErro(
        "Tipo de arquivo não permitido.",
        415
    );

}


/*
|--------------------------------------------------------------------------
| BLOQUEIA PATH TRAVERSAL
|--------------------------------------------------------------------------
*/

if (
    str_contains(
        $caminhoBanco,
        "\0"
    )
    ||
    str_starts_with(
        $caminhoBanco,
        "/"
    )
    ||
    preg_match(
        "/^[A-Za-z]:\//",
        $caminhoBanco
    )
    === 1
    ||
    preg_match(
        "#(^|/)\.\.(/|$)#",
        $caminhoBanco
    )
    === 1
) {

    error_log(
        "[SalaArquivo] Caminho suspeito bloqueado. Arquivo "
        .
        $arquivoId
    );


    salaArquivoErro(
        "Arquivo indisponível.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| RAIZ DO PROJETO
|--------------------------------------------------------------------------
*/

$raizProjeto =
    defined(
        "PROJECT_PATH"
    )
        ?
        realpath(
            PROJECT_PATH
        )
        :
        realpath(
            dirname(
                __DIR__,
                2
            )
        );


if (
    $raizProjeto === false
) {

    salaArquivoErro(
        "Não foi possível localizar o arquivo.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| CAMINHO CANDIDATO
|--------------------------------------------------------------------------
*/

$caminhoRelativoSistema =
    str_replace(
        "/",
        DIRECTORY_SEPARATOR,
        ltrim(
            $caminhoBanco,
            "/"
        )
    );


$caminhoCandidato =
    $raizProjeto
    .
    DIRECTORY_SEPARATOR
    .
    $caminhoRelativoSistema;


/*
|--------------------------------------------------------------------------
| BLOQUEIA LINK SIMBÓLICO ANTES DO REALPATH
|--------------------------------------------------------------------------
*/

if (
    is_link(
        $caminhoCandidato
    )
) {

    error_log(
        "[SalaArquivo] Link simbólico bloqueado. Arquivo "
        .
        $arquivoId
    );


    salaArquivoErro(
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

    salaArquivoErro(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| DIRETÓRIOS AUTORIZADOS
|--------------------------------------------------------------------------
|
| ATUAL:
|
| backend/storage/uploads/salas
|
| LEGADO TEMPORÁRIO:
|
| frontend/uploads/salas
|
|--------------------------------------------------------------------------
*/

$diretoriosPermitidos =
    [];


/*
|--------------------------------------------------------------------------
| STORAGE NOVO
|--------------------------------------------------------------------------
*/

if (
    defined(
        "UPLOAD_STORAGE_PATH"
    )
) {

    $storageSalas =
        UPLOAD_STORAGE_PATH
        .
        DIRECTORY_SEPARATOR
        .
        "salas";


    $storageSalasReal =
        realpath(
            $storageSalas
        );


    if (
        $storageSalasReal !== false
        &&
        is_dir(
            $storageSalasReal
        )
    ) {

        $diretoriosPermitidos[] =
            $storageSalasReal;

    }

}


/*
|--------------------------------------------------------------------------
| FALLBACK NOVO
|--------------------------------------------------------------------------
*/

$storageNovo =
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
    "salas";


$storageNovoReal =
    realpath(
        $storageNovo
    );


if (
    $storageNovoReal !== false
    &&
    is_dir(
        $storageNovoReal
    )
    &&
    !in_array(
        $storageNovoReal,
        $diretoriosPermitidos,
        true
    )
) {

    $diretoriosPermitidos[] =
        $storageNovoReal;

}


/*
|--------------------------------------------------------------------------
| STORAGE LEGADO
|--------------------------------------------------------------------------
*/

$storageLegado =
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
    "salas";


$storageLegadoReal =
    realpath(
        $storageLegado
    );


if (
    $storageLegadoReal !== false
    &&
    is_dir(
        $storageLegadoReal
    )
) {

    $diretoriosPermitidos[] =
        $storageLegadoReal;

}


/*
|--------------------------------------------------------------------------
| NENHUM STORAGE
|--------------------------------------------------------------------------
*/

if (
    empty(
        $diretoriosPermitidos
    )
) {

    error_log(
        "[SalaArquivo] Nenhum diretório autorizado encontrado."
    );


    salaArquivoErro(
        "Arquivo indisponível.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| ARQUIVO PRECISA ESTAR EM STORAGE DE SALAS
|--------------------------------------------------------------------------
*/

$caminhoPermitido =
    false;


foreach (
    $diretoriosPermitidos as
    $diretorioPermitido
) {

    if (
        salaArquivoDentroDoDiretorio(
            $caminhoReal,
            $diretorioPermitido
        )
    ) {

        $caminhoPermitido =
            true;


        break;

    }

}


if (
    !$caminhoPermitido
) {

    error_log(
        "[SalaArquivo] Arquivo fora das pastas de salas. ID "
        .
        $arquivoId
    );


    salaArquivoErro(
        "Arquivo indisponível.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| CAMINHO PRECISA CORRESPONDER À SALA
|--------------------------------------------------------------------------
|
| Estrutura esperada:
|
| .../uploads/salas/{salaId}/...
|
|--------------------------------------------------------------------------
*/

$caminhoRealComparacao =
    "/"
    .
    trim(
        salaArquivoNormalizarCaminho(
            $caminhoReal
        ),
        "/"
    )
    .
    "/";


$marcadorSala =
    "/salas/"
    .
    $salaId
    .
    "/";


if (
    !str_contains(
        $caminhoRealComparacao,
        $marcadorSala
    )
) {

    error_log(
        "[SalaArquivo] Caminho não corresponde à sala. Arquivo "
        .
        $arquivoId
        .
        ", sala "
        .
        $salaId
    );


    salaArquivoErro(
        "Arquivo indisponível.",
        409
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

    $nomesIguais =
        strcasecmp(
            $nomeFisico,
            $nomeInterno
        )
        === 0;

}
else {

    $nomesIguais =
        hash_equals(
            $nomeFisico,
            $nomeInterno
        );

}


if (
    !$nomesIguais
) {

    error_log(
        "[SalaArquivo] Nome físico divergente. Arquivo "
        .
        $arquivoId
    );


    salaArquivoErro(
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

    salaArquivoErro(
        "Arquivo inválido.",
        404
    );

}


$tamanhoReal =
    (int) $tamanhoReal;


/*
|--------------------------------------------------------------------------
| LIMITE DE SEGURANÇA
|--------------------------------------------------------------------------
*/

if (
    $tamanhoReal >
    SALA_ARQUIVO_TAMANHO_MAXIMO
) {

    salaArquivoErro(
        "Arquivo maior que o limite permitido.",
        413
    );

}


/*
|--------------------------------------------------------------------------
| TAMANHO DO BANCO
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
        "[SalaArquivo] Tamanho inválido no banco. Arquivo "
        .
        $arquivoId
    );


    salaArquivoErro(
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
        "[SalaArquivo] Tamanho divergente. Arquivo "
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


    salaArquivoErro(
        "O arquivo não pôde ser validado.",
        409
    );

}


/*
|--------------------------------------------------------------------------
| FILEINFO
|--------------------------------------------------------------------------
*/

if (
    !class_exists(
        "finfo"
    )
) {

    error_log(
        "[SalaArquivo] Extensão Fileinfo não disponível."
    );


    salaArquivoErro(
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


    $mimeDetectado =
        $finfo->file(
            $caminhoReal
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalaArquivo] Erro ao detectar MIME do arquivo "
        .
        $arquivoId
        .
        ": "
        .
        $erro->getMessage()
    );


    salaArquivoErro(
        "Não foi possível validar o arquivo.",
        500
    );

}


if (
    !is_string(
        $mimeDetectado
    )
    ||
    trim(
        $mimeDetectado
    )
    === ""
) {

    salaArquivoErro(
        "Não foi possível identificar o tipo do arquivo.",
        415
    );

}


$mimeDetectado =
    strtolower(
        trim(
            $mimeDetectado
        )
    );


/*
|--------------------------------------------------------------------------
| MIME X EXTENSÃO
|--------------------------------------------------------------------------
*/

if (
    !salaArquivoMimeCompativel(
        $extensao,
        $mimeDetectado
    )
) {

    error_log(
        "[SalaArquivo] MIME incompatível. Arquivo "
        .
        $arquivoId
        .
        ", extensão "
        .
        $extensao
        .
        ", MIME "
        .
        $mimeDetectado
    );


    salaArquivoErro(
        "O tipo do arquivo não pôde ser validado.",
        415
    );

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO EXTRA DE IMAGEM
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

        salaArquivoErro(
            "A imagem não pôde ser validada.",
            415
        );

    }

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO EXTRA DE PDF
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

        salaArquivoErro(
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

        salaArquivoErro(
            "O PDF não pôde ser validado.",
            415
        );

    }

}


/*
|--------------------------------------------------------------------------
| TXT NÃO PODE SER BINÁRIO
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

        salaArquivoErro(
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
| /estudante/salas/{sala}/arquivo/{arquivo}
|
| Download:
|
| /estudante/salas/{sala}/arquivo/{arquivo}?download=1
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
| NOME ASCII
|--------------------------------------------------------------------------
*/

$nomeAscii =
    salaArquivoNomeAscii(
        $nomeOriginal
    );


/*
|--------------------------------------------------------------------------
| ABRE ARQUIVO ANTES DOS HEADERS FINAIS
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

    salaArquivoErro(
        "Não foi possível abrir o arquivo.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| LIBERA LOCK DA SESSÃO
|--------------------------------------------------------------------------
*/

if (
    session_status() === PHP_SESSION_ACTIVE
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
| LIMPA QUALQUER SAÍDA ANTERIOR
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

$tamanhoBloco =
    8192;


while (
    !feof(
        $handle
    )
) {

    $dados =
        fread(
            $handle,
            $tamanhoBloco
        );


    if (
        $dados === false
    ) {

        error_log(
            "[SalaArquivo] Falha durante leitura do arquivo "
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