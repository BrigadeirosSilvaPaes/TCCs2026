<?php

/*
|--------------------------------------------------------------------------
| SERVIÇO DE UPLOAD DA SALA GLOBAL
|--------------------------------------------------------------------------
|
| Responsável por:
|
| - validar erros de upload;
| - limitar tamanho;
| - validar extensão;
| - verificar MIME real;
| - validar imagens;
| - validar PDF;
| - validar arquivos Office antigos;
| - validar DOCX e PPTX internamente;
| - limitar arquivos compactados suspeitos;
| - gerar nome interno aleatório;
| - armazenar fora do frontend;
| - impedir acesso direto quando Apache/LiteSpeed permitir .htaccess;
| - excluir arquivo físico quando necessário.
|
|--------------------------------------------------------------------------
*/

class UploadPublicacaoService
{

    /*
    |--------------------------------------------------------------------------
    | TAMANHO MÁXIMO
    |--------------------------------------------------------------------------
    |
    | 10 MB por arquivo.
    |
    |--------------------------------------------------------------------------
    */

    private const TAMANHO_MAXIMO =
        10 * 1024 * 1024;


    /*
    |--------------------------------------------------------------------------
    | LIMITE DO NOME ORIGINAL
    |--------------------------------------------------------------------------
    */

    private const TAMANHO_MAXIMO_NOME =
        255;


    /*
    |--------------------------------------------------------------------------
    | IMAGENS
    |--------------------------------------------------------------------------
    */

    private const MAXIMO_PIXELS_IMAGEM =
        40_000_000;


    /*
    |--------------------------------------------------------------------------
    | OFFICE OPEN XML
    |--------------------------------------------------------------------------
    */

    private const MAXIMO_ENTRADAS_ZIP =
        2000;


    private const MAXIMO_TAMANHO_DESCOMPACTADO =
        100 * 1024 * 1024;


    private const MAXIMA_RAZAO_COMPACTACAO =
        150;


    /*
    |--------------------------------------------------------------------------
    | DIRETÓRIO DOS ARQUIVOS
    |--------------------------------------------------------------------------
    */

    private string $diretorioUpload;


    /*
    |--------------------------------------------------------------------------
    | EXTENSÕES PERMITIDAS
    |--------------------------------------------------------------------------
    */

    private array $extensoesPermitidas = [

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
    */

    private array $mimesPermitidos = [

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

        $this->diretorioUpload =
            dirname(
                __DIR__
            )
            .
            "/storage/uploads/publicacoes";


        $this->garantirDiretorio();

        $this->protegerDiretorio();

    }


    /*
    |--------------------------------------------------------------------------
    | PROCESSAR UPLOAD
    |--------------------------------------------------------------------------
    */

    public function enviar(
        array $arquivo
    ): array {

        /*
        |--------------------------------------------------------------------------
        | ESTRUTURA
        |--------------------------------------------------------------------------
        */

        if (
            !array_key_exists(
                "name",
                $arquivo
            )
            ||
            !array_key_exists(
                "tmp_name",
                $arquivo
            )
            ||
            !array_key_exists(
                "error",
                $arquivo
            )
            ||
            !array_key_exists(
                "size",
                $arquivo
            )
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "Arquivo inválido."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | ERRO DO PHP
        |--------------------------------------------------------------------------
        */

        $erro =
            (int) $arquivo["error"];


        if (
            $erro !==
            UPLOAD_ERR_OK
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    $this->mensagemErroUpload(
                        $erro
                    )

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | TAMANHO INFORMADO
        |--------------------------------------------------------------------------
        */

        $tamanho =
            (int) $arquivo["size"];


        if (
            $tamanho <= 0
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "O arquivo está vazio."

            ];

        }


        if (
            $tamanho >
            self::TAMANHO_MAXIMO
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "O arquivo excede o limite de 10 MB."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | ARQUIVO TEMPORÁRIO
        |--------------------------------------------------------------------------
        */

        $arquivoTemporario =
            $arquivo["tmp_name"];


        if (
            !is_string(
                $arquivoTemporario
            )
            ||
            $arquivoTemporario === ""
            ||
            !is_uploaded_file(
                $arquivoTemporario
            )
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "O arquivo enviado não é válido."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | TAMANHO REAL
        |--------------------------------------------------------------------------
        */

        $tamanhoReal =
            @filesize(
                $arquivoTemporario
            );


        if (
            $tamanhoReal === false
            ||
            $tamanhoReal <= 0
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "Não foi possível validar o tamanho do arquivo."

            ];

        }


        if (
            $tamanhoReal >
            self::TAMANHO_MAXIMO
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "O arquivo excede o limite de 10 MB."

            ];

        }


        $tamanho =
            (int) $tamanhoReal;


        /*
        |--------------------------------------------------------------------------
        | NOME ORIGINAL
        |--------------------------------------------------------------------------
        */

        $nomeOriginal =
            $this->normalizarNomeOriginal(
                (string) $arquivo["name"]
            );


        if (
            $nomeOriginal === ""
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "O arquivo precisa possuir um nome."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | EXTENSÃO
        |--------------------------------------------------------------------------
        */

        $extensao =
            strtolower(
                trim(
                    pathinfo(
                        $nomeOriginal,
                        PATHINFO_EXTENSION
                    )
                )
            );


        if (
            $extensao === ""
            ||
            !in_array(
                $extensao,
                $this->extensoesPermitidas,
                true
            )
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "Tipo de arquivo não permitido."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | MIME REAL
        |--------------------------------------------------------------------------
        */

        $mimeType =
            $this->obterMimeReal(
                $arquivoTemporario
            );


        if (
            $mimeType === null
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "Não foi possível identificar o tipo do arquivo."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | EXTENSÃO X MIME
        |--------------------------------------------------------------------------
        */

        if (
            !$this->mimeValido(
                $extensao,
                $mimeType
            )
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "O conteúdo do arquivo não corresponde ao formato informado."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO ESPECÍFICA
        |--------------------------------------------------------------------------
        */

        $resultadoValidacao =
            $this->validarFormato(

                $arquivoTemporario,

                $extensao,

                $mimeType

            );


        if (
            $resultadoValidacao !==
            true
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    is_string(
                        $resultadoValidacao
                    )
                        ?
                        $resultadoValidacao
                        :
                        "O arquivo enviado é inválido."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | NOME INTERNO
        |--------------------------------------------------------------------------
        */

        $nomeArquivo =
            $this->gerarNomeArquivo(
                $extensao
            );


        if (
            $nomeArquivo === null
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "Não foi possível preparar o arquivo."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | CAMINHO FINAL
        |--------------------------------------------------------------------------
        */

        $caminhoCompleto =
            $this->diretorioUpload
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

                $arquivoTemporario,

                $caminhoCompleto

            )
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "Não foi possível salvar o arquivo."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRMA ARQUIVO FINAL
        |--------------------------------------------------------------------------
        */

        if (
            !is_file(
                $caminhoCompleto
            )
        ) {

            return [

                "status" =>
                    false,

                "mensagem" =>
                    "Não foi possível confirmar o arquivo enviado."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | TAMANHO FINAL
        |--------------------------------------------------------------------------
        */

        $tamanhoFinal =
            @filesize(
                $caminhoCompleto
            );


        if (
            $tamanhoFinal === false
            ||
            (int) $tamanhoFinal !==
            $tamanho
        ) {

            @unlink(
                $caminhoCompleto
            );


            return [

                "status" =>
                    false,

                "mensagem" =>
                    "O arquivo não pôde ser validado após o envio."

            ];

        }


        /*
        |--------------------------------------------------------------------------
        | PERMISSÃO
        |--------------------------------------------------------------------------
        */

        @chmod(
            $caminhoCompleto,
            0640
        );


        /*
        |--------------------------------------------------------------------------
        | SUCESSO
        |--------------------------------------------------------------------------
        */

        return [

            "status" =>
                true,

            "mensagem" =>
                "Arquivo enviado com sucesso.",

            "arquivo" => [

                "nome_original" =>
                    $nomeOriginal,

                "nome_arquivo" =>
                    $nomeArquivo,

                "extensao" =>
                    $extensao,

                "mime_type" =>
                    $mimeType,

                "tamanho" =>
                    $tamanho,

                "caminho" =>
                    "storage/uploads/publicacoes/"
                    .
                    $nomeArquivo

            ]

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR ARQUIVO FÍSICO
    |--------------------------------------------------------------------------
    */

    public function excluir(
        string $nomeArquivo
    ): bool {

        $nomeArquivo =
            trim(
                $nomeArquivo
            );


        if (
            !$this->nomeInternoValido(
                $nomeArquivo
            )
        ) {

            return false;

        }


        $caminho =
            $this->diretorioUpload
            .
            DIRECTORY_SEPARATOR
            .
            $nomeArquivo;


        if (
            !is_file(
                $caminho
            )
        ) {

            return true;

        }


        if (
            is_link(
                $caminho
            )
        ) {

            return false;

        }


        return @unlink(
            $caminho
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CAMINHO ABSOLUTO
    |--------------------------------------------------------------------------
    */

    public function caminhoAbsoluto(
        string $nomeArquivo
    ): string {

        $nomeArquivo =
            trim(
                $nomeArquivo
            );


        if (
            !$this->nomeInternoValido(
                $nomeArquivo
            )
        ) {

            return "";

        }


        return
            $this->diretorioUpload
            .
            DIRECTORY_SEPARATOR
            .
            $nomeArquivo;

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR FORMATO
    |--------------------------------------------------------------------------
    */

    private function validarFormato(
        string $arquivo,
        string $extensao,
        string $mimeType
    ): bool|string {

        /*
        |--------------------------------------------------------------------------
        | IMAGEM
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

            if (
                !$this->imagemValida(

                    $arquivo,

                    $extensao,

                    $mimeType

                )
            ) {

                return
                    "A imagem enviada é inválida.";

            }


            return true;

        }


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        if (
            $extensao ===
            "pdf"
        ) {

            if (
                !$this->pdfValido(
                    $arquivo
                )
            ) {

                return
                    "O arquivo PDF é inválido.";

            }


            return true;

        }


        /*
        |--------------------------------------------------------------------------
        | DOC / PPT
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
        ) {

            if (
                !$this->arquivoOleValido(
                    $arquivo
                )
            ) {

                return
                    "O arquivo Office enviado é inválido.";

            }


            return true;

        }


        /*
        |--------------------------------------------------------------------------
        | DOCX
        |--------------------------------------------------------------------------
        */

        if (
            $extensao ===
            "docx"
        ) {

            if (
                !$this->arquivoOfficeValido(
                    $arquivo,
                    "docx"
                )
            ) {

                return
                    "O arquivo DOCX é inválido.";

            }


            return true;

        }


        /*
        |--------------------------------------------------------------------------
        | PPTX
        |--------------------------------------------------------------------------
        */

        if (
            $extensao ===
            "pptx"
        ) {

            if (
                !$this->arquivoOfficeValido(
                    $arquivo,
                    "pptx"
                )
            ) {

                return
                    "O arquivo PPTX é inválido.";

            }


            return true;

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

            if (
                !$this->textoValido(
                    $arquivo
                )
            ) {

                return
                    "O arquivo de texto é inválido.";

            }


            return true;

        }


        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | GERAR NOME INTERNO
    |--------------------------------------------------------------------------
    */

    private function gerarNomeArquivo(
        string $extensao
    ): ?string {

        if (
            !in_array(
                $extensao,
                $this->extensoesPermitidas,
                true
            )
        ) {

            return null;

        }


        for (
            $tentativa = 0;
            $tentativa < 5;
            $tentativa++
        ) {

            try {

                $nome =
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

                error_log(
                    "[UploadPublicacaoService] Não foi possível gerar nome de arquivo: "
                    .
                    $erro->getMessage()
                );


                return null;

            }


            $caminho =
                $this->diretorioUpload
                .
                DIRECTORY_SEPARATOR
                .
                $nome;


            if (
                !file_exists(
                    $caminho
                )
            ) {

                return $nome;

            }

        }


        return null;

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR NOME INTERNO
    |--------------------------------------------------------------------------
    */

    private function nomeInternoValido(
        string $nomeArquivo
    ): bool {

        if (
            $nomeArquivo === ""
        ) {

            return false;

        }


        if (
            basename(
                $nomeArquivo
            )
            !==
            $nomeArquivo
        ) {

            return false;

        }


        if (
            preg_match(
                "/^([a-f0-9]{48})\.([a-z0-9]+)$/",
                $nomeArquivo,
                $resultado
            )
            !== 1
        ) {

            return false;

        }


        $extensao =
            strtolower(
                (string) (
                    $resultado[2]
                    ??
                    ""
                )
            );


        return in_array(
            $extensao,
            $this->extensoesPermitidas,
            true
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
        | REMOVE POSSÍVEL CAMINHO
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
        |
        | Não utilizamos /u porque o nome recebido do sistema operacional
        | pode não chegar ao PHP em UTF-8 válido.
        |
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


        if (
            $nome === ""
        ) {

            return "";

        }


        /*
        |--------------------------------------------------------------------------
        | LIMITA TAMANHO
        |--------------------------------------------------------------------------
        */

        if (
            $this->tamanhoTexto(
                $nome
            )
            >
            self::TAMANHO_MAXIMO_NOME
        ) {

            $extensao =
                strtolower(
                    trim(
                        pathinfo(
                            $nome,
                            PATHINFO_EXTENSION
                        )
                    )
                );


            $nomeBase =
                pathinfo(
                    $nome,
                    PATHINFO_FILENAME
                );


            $espacoExtensao =
                $extensao !== ""
                    ?
                    (
                        $this->tamanhoTexto(
                            $extensao
                        )
                        +
                        1
                    )
                    :
                    0;


            $limiteBase =
                max(
                    1,
                    self::TAMANHO_MAXIMO_NOME
                    -
                    $espacoExtensao
                );


            $nomeBase =
                $this->cortarTexto(
                    $nomeBase,
                    $limiteBase
                );


            $nome =
                $nomeBase
                .
                (
                    $extensao !== ""
                        ?
                        "."
                        .
                        $extensao
                        :
                        ""
                );

        }


        return trim(
            $nome
        );

    }


    /*
    |--------------------------------------------------------------------------
    | GARANTIR DIRETÓRIO
    |--------------------------------------------------------------------------
    */

    private function garantirDiretorio(): void
    {

        /*
        |--------------------------------------------------------------------------
        | NÃO ACEITA SYMLINK
        |--------------------------------------------------------------------------
        */

        if (
            is_link(
                $this->diretorioUpload
            )
        ) {

            throw new RuntimeException(
                "O diretório de uploads não pode ser um link simbólico."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CRIA DIRETÓRIO
        |--------------------------------------------------------------------------
        */

        if (
            !is_dir(
                $this->diretorioUpload
            )
        ) {

            if (
                !mkdir(
                    $this->diretorioUpload,
                    0750,
                    true
                )
                &&
                !is_dir(
                    $this->diretorioUpload
                )
            ) {

                throw new RuntimeException(
                    "Não foi possível criar o diretório de uploads."
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRMA DIRETÓRIO
        |--------------------------------------------------------------------------
        */

        if (
            !is_dir(
                $this->diretorioUpload
            )
        ) {

            throw new RuntimeException(
                "O diretório de uploads é inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ESCRITA
        |--------------------------------------------------------------------------
        */

        if (
            !is_writable(
                $this->diretorioUpload
            )
        ) {

            throw new RuntimeException(
                "O diretório de uploads não possui permissão de escrita."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CAMINHO REAL
        |--------------------------------------------------------------------------
        */

        $real =
            realpath(
                $this->diretorioUpload
            );


        if (
            $real === false
        ) {

            throw new RuntimeException(
                "Não foi possível validar o diretório de uploads."
            );

        }


        $this->diretorioUpload =
            $real;

    }


    /*
    |--------------------------------------------------------------------------
    | PROTEGER DIRETÓRIO
    |--------------------------------------------------------------------------
    */

    private function protegerDiretorio(): void
    {

        $arquivoHtaccess =
            $this->diretorioUpload
            .
            DIRECTORY_SEPARATOR
            .
            ".htaccess";


        if (
            is_file(
                $arquivoHtaccess
            )
        ) {

            return;

        }


        $conteudo = <<<'HTACCESS'
Options -Indexes

<IfModule mod_authz_core.c>
    Require all denied
</IfModule>

<IfModule !mod_authz_core.c>
    Order allow,deny
    Deny from all
</IfModule>
HTACCESS;


        $resultado =
            @file_put_contents(

                $arquivoHtaccess,

                $conteudo,

                LOCK_EX

            );


        if (
            $resultado === false
        ) {

            error_log(
                "[UploadPublicacaoService] Não foi possível criar proteção adicional no diretório de uploads."
            );


            return;

        }


        @chmod(
            $arquivoHtaccess,
            0640
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MIME REAL
    |--------------------------------------------------------------------------
    */

    private function obterMimeReal(
        string $arquivo
    ): ?string {

        if (
            !class_exists(
                "finfo"
            )
        ) {

            return null;

        }


        try {

            $finfo =
                new finfo(
                    FILEINFO_MIME_TYPE
                );


            $mime =
                $finfo->file(
                    $arquivo
                );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[UploadPublicacaoService] Falha ao analisar MIME: "
                .
                $erro->getMessage()
            );


            return null;

        }


        if (
            !is_string(
                $mime
            )
            ||
            trim(
                $mime
            )
            === ""
        ) {

            return null;

        }


        return strtolower(
            trim(
                $mime
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR MIME
    |--------------------------------------------------------------------------
    */

    private function mimeValido(
        string $extensao,
        string $mimeType
    ): bool {

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


        if (
            !isset(
                $this->mimesPermitidos[
                    $extensao
                ]
            )
        ) {

            return false;

        }


        return in_array(
            $mimeType,
            $this->mimesPermitidos[
                $extensao
            ],
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR IMAGEM
    |--------------------------------------------------------------------------
    */

    private function imagemValida(
        string $arquivo,
        string $extensao,
        string $mimeEsperado
    ): bool {

        $informacoes =
            @getimagesize(
                $arquivo
            );


        if (
            !is_array(
                $informacoes
            )
        ) {

            return false;

        }


        $largura =
            (int) (
                $informacoes[0]
                ??
                0
            );


        $altura =
            (int) (
                $informacoes[1]
                ??
                0
            );


        if (
            $largura <= 0
            ||
            $altura <= 0
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | LIMITE DE PIXELS
        |--------------------------------------------------------------------------
        */

        if (
            $largura >
            intdiv(
                self::MAXIMO_PIXELS_IMAGEM,
                max(
                    1,
                    $altura
                )
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | MIME DA IMAGEM
        |--------------------------------------------------------------------------
        */

        $mimeImagem =
            strtolower(
                trim(
                    (string) (
                        $informacoes["mime"]
                        ??
                        ""
                    )
                )
            );


        if (
            $mimeImagem === ""
        ) {

            return false;

        }


        if (
            $mimeImagem !==
            strtolower(
                $mimeEsperado
            )
        ) {

            return false;

        }


        $mimesExtensao =
            $this->mimesPermitidos[
                $extensao
            ]
            ??
            [];


        return in_array(
            $mimeImagem,
            $mimesExtensao,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR PDF
    |--------------------------------------------------------------------------
    */

    private function pdfValido(
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


        /*
        |--------------------------------------------------------------------------
        | INÍCIO
        |--------------------------------------------------------------------------
        */

        $inicio =
            fread(
                $handle,
                1024
            );


        if (
            !is_string(
                $inicio
            )
            ||
            strpos(
                $inicio,
                "%PDF-"
            )
            === false
        ) {

            fclose(
                $handle
            );


            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | TAMANHO
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | FINAL
        |--------------------------------------------------------------------------
        */

        $bytesFinais =
            min(
                4096,
                (int) $tamanho
            );


        if (
            fseek(
                $handle,
                -$bytesFinais,
                SEEK_END
            )
            !== 0
        ) {

            fclose(
                $handle
            );


            return false;

        }


        $final =
            fread(
                $handle,
                $bytesFinais
            );


        fclose(
            $handle
        );


        if (
            !is_string(
                $final
            )
        ) {

            return false;

        }


        return strpos(
            $final,
            "%%EOF"
        )
        !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | OFFICE BINÁRIO ANTIGO
    |--------------------------------------------------------------------------
    */

    private function arquivoOleValido(
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


        if (
            !is_string(
                $assinatura
            )
            ||
            strlen(
                $assinatura
            )
            !== 8
        ) {

            return false;

        }


        return hash_equals(

            "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1",

            $assinatura

        );

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR TXT
    |--------------------------------------------------------------------------
    */

    private function textoValido(
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


        while (
            !feof(
                $handle
            )
        ) {

            $bloco =
                fread(
                    $handle,
                    8192
                );


            if (
                $bloco === false
            ) {

                fclose(
                    $handle
                );


                return false;

            }


            if (
                strpos(
                    $bloco,
                    "\0"
                )
                !== false
            ) {

                fclose(
                    $handle
                );


                return false;

            }

        }


        fclose(
            $handle
        );


        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR DOCX / PPTX
    |--------------------------------------------------------------------------
    */

    private function arquivoOfficeValido(
        string $arquivo,
        string $tipo
    ): bool {

        if (
            !in_array(
                $tipo,
                [
                    "docx",
                    "pptx"
                ],
                true
            )
        ) {

            return false;

        }


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
                $arquivo
            );


        if (
            $resultado !==
            true
        ) {

            return false;

        }


        try {

            /*
            |--------------------------------------------------------------------------
            | QUANTIDADE
            |--------------------------------------------------------------------------
            */

            if (
                $zip->numFiles <= 0
                ||
                $zip->numFiles >
                self::MAXIMO_ENTRADAS_ZIP
            ) {

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | CONTENT TYPES
            |--------------------------------------------------------------------------
            */

            if (
                $zip->locateName(
                    "[Content_Types].xml"
                )
                === false
            ) {

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | ESTRUTURA DO TIPO
            |--------------------------------------------------------------------------
            */

            if (
                $tipo ===
                "docx"
            ) {

                if (
                    $zip->locateName(
                        "word/document.xml"
                    )
                    === false
                ) {

                    return false;

                }

            }
            elseif (
                $tipo ===
                "pptx"
            ) {

                if (
                    $zip->locateName(
                        "ppt/presentation.xml"
                    )
                    === false
                ) {

                    return false;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | ANALISA CONTEÚDO
            |--------------------------------------------------------------------------
            */

            $totalDescompactado =
                0;


            for (
                $indice = 0;
                $indice < $zip->numFiles;
                $indice++
            ) {

                $estatistica =
                    $zip->statIndex(
                        $indice
                    );


                if (
                    !is_array(
                        $estatistica
                    )
                ) {

                    return false;

                }


                /*
                |--------------------------------------------------------------------------
                | NOME DA ENTRADA
                |--------------------------------------------------------------------------
                */

                $nomeEntrada =
                    str_replace(
                        "\\",
                        "/",
                        (string) (
                            $estatistica["name"]
                            ??
                            ""
                        )
                    );


                if (
                    $nomeEntrada === ""
                ) {

                    return false;

                }


                /*
                |--------------------------------------------------------------------------
                | PATH TRAVERSAL
                |--------------------------------------------------------------------------
                */

                if (
                    str_starts_with(
                        $nomeEntrada,
                        "/"
                    )
                    ||
                    preg_match(
                        "#(^|/)\.\.(/|$)#",
                        $nomeEntrada
                    )
                    === 1
                ) {

                    return false;

                }


                /*
                |--------------------------------------------------------------------------
                | NOME MINÚSCULO
                |--------------------------------------------------------------------------
                */

                $nomeMinusculo =
                    strtolower(
                        $nomeEntrada
                    );


                /*
                |--------------------------------------------------------------------------
                | VBA
                |--------------------------------------------------------------------------
                */

                if (
                    str_ends_with(
                        $nomeMinusculo,
                        "vbaproject.bin"
                    )
                ) {

                    return false;

                }


                /*
                |--------------------------------------------------------------------------
                | OBJETOS EMBUTIDOS
                |--------------------------------------------------------------------------
                */

                if (
                    str_contains(
                        $nomeMinusculo,
                        "/embeddings/"
                    )
                ) {

                    return false;

                }


                /*
                |--------------------------------------------------------------------------
                | TAMANHO DESCOMPACTADO
                |--------------------------------------------------------------------------
                */

                $tamanhoOriginal =
                    max(
                        0,
                        (int) (
                            $estatistica["size"]
                            ??
                            0
                        )
                    );


                $tamanhoComprimido =
                    max(
                        0,
                        (int) (
                            $estatistica["comp_size"]
                            ??
                            0
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | PROTEGE SOMA
                |--------------------------------------------------------------------------
                */

                if (
                    $tamanhoOriginal >
                    self::MAXIMO_TAMANHO_DESCOMPACTADO
                ) {

                    return false;

                }


                if (
                    $totalDescompactado >
                    self::MAXIMO_TAMANHO_DESCOMPACTADO
                    -
                    $tamanhoOriginal
                ) {

                    return false;

                }


                $totalDescompactado +=
                    $tamanhoOriginal;


                /*
                |--------------------------------------------------------------------------
                | RAZÃO DE COMPRESSÃO
                |--------------------------------------------------------------------------
                */

                if (
                    $tamanhoOriginal > 0
                    &&
                    $tamanhoComprimido > 0
                ) {

                    $razao =
                        $tamanhoOriginal
                        /
                        $tamanhoComprimido;


                    if (
                        $razao >
                        self::MAXIMA_RAZAO_COMPACTACAO
                    ) {

                        return false;

                    }

                }

            }


            return true;

        }
        finally {

            $zip->close();

        }

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
            &&
            mb_check_encoding(
                $texto,
                "UTF-8"
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
            &&
            function_exists(
                "mb_check_encoding"
            )
            &&
            mb_check_encoding(
                $texto,
                "UTF-8"
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


    /*
    |--------------------------------------------------------------------------
    | MENSAGENS DOS ERROS DO PHP
    |--------------------------------------------------------------------------
    */

    private function mensagemErroUpload(
        int $erro
    ): string {

        return match (
            $erro
        ) {

            UPLOAD_ERR_INI_SIZE,
            UPLOAD_ERR_FORM_SIZE =>
                "O arquivo excede o tamanho permitido.",

            UPLOAD_ERR_PARTIAL =>
                "O arquivo foi enviado apenas parcialmente.",

            UPLOAD_ERR_NO_FILE =>
                "Nenhum arquivo foi selecionado.",

            UPLOAD_ERR_NO_TMP_DIR =>
                "O servidor não possui diretório temporário disponível.",

            UPLOAD_ERR_CANT_WRITE =>
                "O servidor não conseguiu salvar o arquivo.",

            UPLOAD_ERR_EXTENSION =>
                "O upload foi bloqueado pelo servidor.",

            default =>
                "Ocorreu um erro durante o envio do arquivo."

        };

    }

}