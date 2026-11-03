<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| ARQUIVO PROTEGIDO DA SALA — PROFESSOR
|
| Permite ao professor proprietário da sala acessar arquivos
| vinculados aos conteúdos publicados na própria sala.
|
| Aceita:
|
| GET
| HEAD
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
| ERRO
|--------------------------------------------------------------------------
*/

function professorSalaArquivoErro(
    string $mensagem,
    int $codigoHttp = 400
): never {

    /*
    |--------------------------------------------------------------------------
    | REMOVE BUFFER
    |--------------------------------------------------------------------------
    */

    while (
        ob_get_level() > 0
    ) {

        @ob_end_clean();

    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    http_response_code(
        $codigoHttp
    );


    /*
    |--------------------------------------------------------------------------
    | TIPO
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
| NORMALIZA CAMINHO
|--------------------------------------------------------------------------
*/

function professorSalaArquivoNormalizarCaminho(
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

function professorSalaArquivoDentroDiretorio(
    string $arquivo,
    string $diretorio
): bool {

    $arquivo =
        professorSalaArquivoNormalizarCaminho(
            $arquivo
        );


    $diretorio =
        rtrim(
            professorSalaArquivoNormalizarCaminho(
                $diretorio
            ),
            "/"
        );


    /*
    |--------------------------------------------------------------------------
    | WINDOWS NÃO DIFERENCIA MAIÚSCULAS/MINÚSCULAS
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
| NOME SEGURO PARA DOWNLOAD
|--------------------------------------------------------------------------
*/

function professorSalaArquivoNomeSeguro(
    string $nome
): string {

    $nome =
        professorSalaArquivoNormalizarCaminho(
            $nome
        );


    /*
    |--------------------------------------------------------------------------
    | REMOVE CAMINHO EVENTUAL
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

function professorSalaArquivoNomeAscii(
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
| Este será o Content-Type enviado pelo servidor.
|
|--------------------------------------------------------------------------
*/

function professorSalaArquivoMimeCanonico(
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
| MIME REAL COMPATÍVEL COM EXTENSÃO?
|--------------------------------------------------------------------------
*/

function professorSalaArquivoMimeCompativel(
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

        /*
        |--------------------------------------------------------------------------
        | FORMATOS OLE ANTIGOS
        |--------------------------------------------------------------------------
        */

        "doc" => [

            "application/msword",
            "application/vnd.ms-office",
            "application/x-ole-storage",
            "application/x-cfb",
            "application/octet-stream"

        ],

        "ppt" => [

            "application/vnd.ms-powerpoint",
            "application/vnd.ms-office",
            "application/x-ole-storage",
            "application/x-cfb",
            "application/octet-stream"

        ],

        /*
        |--------------------------------------------------------------------------
        | FORMATOS OFFICE OPEN XML
        |--------------------------------------------------------------------------
        |
        | DOCX/PPTX são containers ZIP, por isso alguns servidores
        | identificam o MIME apenas como ZIP.
        |
        |--------------------------------------------------------------------------
        */

        "docx" => [

            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/zip",
            "application/x-zip",
            "application/x-zip-compressed",
            "application/octet-stream"

        ],

        "pptx" => [

            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "application/zip",
            "application/x-zip",
            "application/x-zip-compressed",
            "application/octet-stream"

        ],

        /*
        |--------------------------------------------------------------------------
        | TEXTO
        |--------------------------------------------------------------------------
        */

        "txt" => [

            "text/plain",
            "application/octet-stream"

        ],

        /*
        |--------------------------------------------------------------------------
        | IMAGENS
        |--------------------------------------------------------------------------
        */

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
    !in_array(
        $metodo,
        [
            "GET",
            "HEAD"
        ],
        true
    )
) {

    header(
        "Allow: GET, HEAD"
    );


    professorSalaArquivoErro(
        "Método não permitido.",
        405
    );

}


/*
|--------------------------------------------------------------------------
| USUÁRIO DA SESSÃO
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

    professorSalaArquivoErro(
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

    professorSalaArquivoErro(
        "Sessão inválida.",
        401
    );

}


$usuarioId =
    (int) $usuarioId;


/*
|--------------------------------------------------------------------------
| ID DA SALA
|--------------------------------------------------------------------------
|
| O Router injeta os parâmetros dinâmicos diretamente em $_GET.
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

    professorSalaArquivoErro(
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

    professorSalaArquivoErro(
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
        "[ProfessorSalaArquivo] Erro ao iniciar dependências: "
        .
        $erro->getMessage()
    );


    professorSalaArquivoErro(
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
        "[ProfessorSalaArquivo] Erro ao consultar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    professorSalaArquivoErro(
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

    professorSalaArquivoErro(
        "Você não possui acesso a este arquivo.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| GARANTE PERFIL PROFESSOR
|--------------------------------------------------------------------------
*/

$tipoUsuarioRecebido =
    $resultadoPerfil["perfil"]["usuario"]["tipo_usuario"]
    ??
    null;


if (
    !is_string(
        $tipoUsuarioRecebido
    )
) {

    professorSalaArquivoErro(
        "Você não possui acesso a este arquivo.",
        403
    );

}


$tipoUsuario =
    strtolower(
        trim(
            $tipoUsuarioRecebido
        )
    );


if (
    $tipoUsuario !== PROFILE_PROFESSOR
) {

    professorSalaArquivoErro(
        "Você não possui acesso a este arquivo.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL ESPECÍFICO
|--------------------------------------------------------------------------
*/

$perfilProfessor =
    $resultadoPerfil["perfil"]["professor"]
    ??
    null;


if (
    !is_array(
        $perfilProfessor
    )
) {

    professorSalaArquivoErro(
        "Você não possui acesso a este arquivo.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| PROFESSOR APROVADO
|--------------------------------------------------------------------------
*/

$aprovado =
    $perfilProfessor["aprovado"]
    ??
    0;


$professorAprovado =
    (
        $aprovado === true
        ||
        $aprovado === 1
        ||
        $aprovado === "1"
    );


if (
    !$professorAprovado
) {

    professorSalaArquivoErro(
        "Seu perfil não possui permissão para acessar documentos de salas.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| BUSCA SALA
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
        "[ProfessorSalaArquivo] Erro ao consultar sala "
        .
        $salaId
        .
        ": "
        .
        $erro->getMessage()
    );


    professorSalaArquivoErro(
        "Não foi possível validar a sala.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| SALA EXISTE
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $resultadoSala
    )
    ||
    ($resultadoSala["status"] ?? false)
    !== true
    ||
    !isset(
        $resultadoSala["sala"]
    )
    ||
    !is_array(
        $resultadoSala["sala"]
    )
) {

    professorSalaArquivoErro(
        "Arquivo não encontrado.",
        404
    );

}


$sala =
    $resultadoSala["sala"];


/*
|--------------------------------------------------------------------------
| PROFESSOR É DONO DA SALA
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

    /*
    |--------------------------------------------------------------------------
    | 404 evita confirmar para terceiros que a sala/arquivo existe.
    |--------------------------------------------------------------------------
    */

    professorSalaArquivoErro(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| CONTEÚDOS DO PROFESSOR
|--------------------------------------------------------------------------
|
| O professor proprietário pode acessar conteúdo:
|
| - publicado;
| - rascunho;
| - oculto.
|
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
        "[ProfessorSalaArquivo] Erro ao listar conteúdos. Professor "
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


    professorSalaArquivoErro(
        "Não foi possível validar o acesso ao arquivo.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| AUTORIZAÇÃO
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

    professorSalaArquivoErro(
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
| LOCALIZA ARQUIVO
|--------------------------------------------------------------------------
|
| O arquivo precisa:
|
| - pertencer a um conteúdo;
| - o conteúdo precisa pertencer à sala;
| - a sala precisa pertencer ao professor autenticado.
|
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
    | CONFIRMA SALA
    |--------------------------------------------------------------------------
    */

    $conteudoSalaId =
        (int) (
            $conteudo["sala_id"]
            ??
            0
        );


    if (
        $conteudoSalaId !==
        $salaId
    ) {

        continue;

    }


    /*
    |--------------------------------------------------------------------------
    | ARQUIVOS DO CONTEÚDO
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
| ARQUIVO NÃO ENCONTRADO
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $arquivo
    )
) {

    professorSalaArquivoErro(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| METADADOS DO BANCO
|--------------------------------------------------------------------------
*/

$caminhoRecebido =
    $arquivo["caminho"]
    ??
    null;


$nomeArquivoRecebido =
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
| VALIDA TIPOS DOS METADADOS
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $caminhoRecebido
    )
    ||
    !is_string(
        $nomeArquivoRecebido
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
        "[ProfessorSalaArquivo] Metadados inválidos no banco. Arquivo "
        .
        $arquivoId
    );


    professorSalaArquivoErro(
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
    professorSalaArquivoNormalizarCaminho(
        $caminhoRecebido
    );


$nomeArquivoInterno =
    trim(
        $nomeArquivoRecebido
    );


$nomeOriginal =
    professorSalaArquivoNomeSeguro(
        $nomeOriginalRecebido
    );


$extensao =
    strtolower(
        trim(
            $extensaoRecebida
        )
    );


$tamanhoBanco =
    filter_var(
        $arquivo["tamanho"]
        ??
        0,
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 0
            ]
        ]
    );


if (
    $tamanhoBanco === false
    ||
    $tamanhoBanco === null
) {

    $tamanhoBanco =
        0;

}


$tamanhoBanco =
    (int) $tamanhoBanco;


/*
|--------------------------------------------------------------------------
| EXTENSÃO / MIME CANÔNICO
|--------------------------------------------------------------------------
*/

$mimeCanonico =
    professorSalaArquivoMimeCanonico(
        $extensao
    );


if (
    $mimeCanonico === null
) {

    professorSalaArquivoErro(
        "Tipo de arquivo não permitido.",
        415
    );

}


/*
|--------------------------------------------------------------------------
| CAMINHO
|--------------------------------------------------------------------------
*/

if (
    $caminhoBanco === ""
) {

    professorSalaArquivoErro(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| BLOQUEIA PATH TRAVERSAL / CAMINHO ABSOLUTO
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
        "[ProfessorSalaArquivo] Caminho suspeito bloqueado. Arquivo "
        .
        $arquivoId
    );


    professorSalaArquivoErro(
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

    professorSalaArquivoErro(
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
| BLOQUEIA LINK SIMBÓLICO NO ARQUIVO FINAL
|--------------------------------------------------------------------------
*/

if (
    is_link(
        $caminhoCandidato
    )
) {

    error_log(
        "[ProfessorSalaArquivo] Link simbólico bloqueado. Arquivo "
        .
        $arquivoId
    );


    professorSalaArquivoErro(
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

    professorSalaArquivoErro(
        "Arquivo não encontrado.",
        404
    );

}


/*
|--------------------------------------------------------------------------
| DIRETÓRIOS PERMITIDOS
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
| O diretório legado permanece apenas até verificarmos/migrarmos
| possíveis arquivos antigos.
|
|--------------------------------------------------------------------------
*/

$diretoriosPermitidos =
    [];


/*
|--------------------------------------------------------------------------
| NOVO STORAGE
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
| FALLBACK DO NOVO STORAGE
|--------------------------------------------------------------------------
*/

$storageFallback =
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


$storageFallbackReal =
    realpath(
        $storageFallback
    );


if (
    $storageFallbackReal !== false
    &&
    is_dir(
        $storageFallbackReal
    )
    &&
    !in_array(
        $storageFallbackReal,
        $diretoriosPermitidos,
        true
    )
) {

    $diretoriosPermitidos[] =
        $storageFallbackReal;

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
| NENHUM DIRETÓRIO DISPONÍVEL
|--------------------------------------------------------------------------
*/

if (
    empty(
        $diretoriosPermitidos
    )
) {

    error_log(
        "[ProfessorSalaArquivo] Nenhum diretório autorizado encontrado."
    );


    professorSalaArquivoErro(
        "Arquivo indisponível.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| CONFIRMA LOCALIZAÇÃO FÍSICA
|--------------------------------------------------------------------------
*/

$caminhoPermitido =
    false;


foreach (
    $diretoriosPermitidos as
    $diretorioPermitido
) {

    if (
        professorSalaArquivoDentroDiretorio(
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
        "[ProfessorSalaArquivo] Arquivo fora das pastas permitidas. ID "
        .
        $arquivoId
    );


    professorSalaArquivoErro(
        "Arquivo indisponível.",
        403
    );

}


/*
|--------------------------------------------------------------------------
| CONFIRMA ID DA SALA NO CAMINHO
|--------------------------------------------------------------------------
*/

$marcadorSala =
    "/salas/"
    .
    $salaId
    .
    "/";


$caminhoBancoComparacao =
    "/"
    .
    trim(
        $caminhoBanco,
        "/"
    )
    .
    "/";


if (
    !str_contains(
        $caminhoBancoComparacao,
        $marcadorSala
    )
) {

    error_log(
        "[ProfessorSalaArquivo] Caminho não corresponde à sala. Arquivo "
        .
        $arquivoId
        .
        ", sala "
        .
        $salaId
    );


    professorSalaArquivoErro(
        "Arquivo indisponível.",
        409
    );

}


/*
|--------------------------------------------------------------------------
| CONFERE NOME INTERNO
|--------------------------------------------------------------------------
*/

if (
    $nomeArquivoInterno === ""
) {

    professorSalaArquivoErro(
        "Arquivo indisponível.",
        409
    );

}


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
            $nomeArquivoInterno
        )
        === 0;

}
else {

    $nomeConfere =
        hash_equals(
            $nomeFisico,
            $nomeArquivoInterno
        );

}


if (
    !$nomeConfere
) {

    error_log(
        "[ProfessorSalaArquivo] Nome do arquivo divergente. ID "
        .
        $arquivoId
    );


    professorSalaArquivoErro(
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

    professorSalaArquivoErro(
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
|
| O módulo de Salas aceita até 10 MB.
|
|--------------------------------------------------------------------------
*/

$limiteArquivo =
    10
    *
    1024
    *
    1024;


if (
    $tamanhoReal >
    $limiteArquivo
) {

    professorSalaArquivoErro(
        "Arquivo maior que o limite permitido.",
        413
    );

}


/*
|--------------------------------------------------------------------------
| CONFERE TAMANHO COM O BANCO
|--------------------------------------------------------------------------
*/

if (
    $tamanhoBanco > 0
    &&
    $tamanhoBanco !==
    $tamanhoReal
) {

    error_log(
        "[ProfessorSalaArquivo] Tamanho divergente. Arquivo "
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


    professorSalaArquivoErro(
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
        "[ProfessorSalaArquivo] Extensão fileinfo não disponível."
    );


    professorSalaArquivoErro(
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
        "[ProfessorSalaArquivo] Erro ao detectar MIME do arquivo "
        .
        $arquivoId
        .
        ": "
        .
        $erro->getMessage()
    );


    professorSalaArquivoErro(
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

    professorSalaArquivoErro(
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
    !professorSalaArquivoMimeCompativel(
        $extensao,
        $mimeReal
    )
) {

    error_log(
        "[ProfessorSalaArquivo] MIME incompatível. Arquivo "
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


    professorSalaArquivoErro(
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

        professorSalaArquivoErro(
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

        professorSalaArquivoErro(
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

        professorSalaArquivoErro(
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

        professorSalaArquivoErro(
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
| /professor/salas/{sala}/arquivo/{arquivo}
|
| Download:
|
| /professor/salas/{sala}/arquivo/{arquivo}?download=1
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
| NOME PARA CONTENT-DISPOSITION
|--------------------------------------------------------------------------
*/

$nomeAscii =
    professorSalaArquivoNomeAscii(
        $nomeOriginal
    );


/*
|--------------------------------------------------------------------------
| ABRE ARQUIVO
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

    professorSalaArquivoErro(
        "Não foi possível abrir o arquivo.",
        500
    );

}


/*
|--------------------------------------------------------------------------
| LIBERA LOCK DA SESSÃO
|--------------------------------------------------------------------------
|
| O download pode demorar.
|
| Não precisamos manter a sessão PHP bloqueada durante o stream.
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
| REMOVE BUFFER ANTES DO STREAM
|--------------------------------------------------------------------------
*/

while (
    ob_get_level() > 0
) {

    @ob_end_clean();

}


/*
|--------------------------------------------------------------------------
| HEAD NÃO POSSUI CORPO
|--------------------------------------------------------------------------
*/

if (
    $metodo === "HEAD"
) {

    fclose(
        $handle
    );


    exit;

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
            "[ProfessorSalaArquivo] Falha durante leitura do arquivo "
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