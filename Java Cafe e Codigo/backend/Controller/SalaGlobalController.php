<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| SALA GLOBAL CONTROLLER
|
| Responsável por:
|
| - publicações da comunidade;
| - arquivos;
| - links e vídeos;
| - respostas;
| - marcação "isso me ajudou";
| - denúncias.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


/*
|--------------------------------------------------------------------------
| MODELS
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../models/PublicacaoGlobal.php";
require_once __DIR__ . "/../models/ArquivoPublicacaoGlobal.php";
require_once __DIR__ . "/../models/LinkPublicacaoGlobal.php";
require_once __DIR__ . "/../models/RespostaPublicacaoGlobal.php";
require_once __DIR__ . "/../models/AjudaPublicacaoGlobal.php";
require_once __DIR__ . "/../models/DenunciaPublicacaoGlobal.php";


/*
|--------------------------------------------------------------------------
| SERVIÇOS
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../services/UploadPublicacaoService.php";


class SalaGlobalController
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | DEPENDÊNCIAS
    |--------------------------------------------------------------------------
    */

    private PublicacaoGlobal $publicacao;

    private ArquivoPublicacaoGlobal $arquivo;

    private LinkPublicacaoGlobal $link;

    private RespostaPublicacaoGlobal $resposta;

    private AjudaPublicacaoGlobal $ajuda;

    private DenunciaPublicacaoGlobal $denuncia;

    private UploadPublicacaoService $upload;


    /*
    |--------------------------------------------------------------------------
    | LIMITES
    |--------------------------------------------------------------------------
    */

    private const LIMITE_TITULO =
        150;


    private const LIMITE_TITULO_LINK =
        150;


    private const LIMITE_URL =
        2048;


    private const LIMITE_MOTIVO_DENUNCIA =
        255;


    private const LIMITE_FEED =
        100;


    private const LIMITE_PESQUISA =
        200;


    /*
    |--------------------------------------------------------------------------
    | CATEGORIAS
    |--------------------------------------------------------------------------
    |
    | "exercicio" permanece temporariamente como categoria de publicação
    | da comunidade.
    |
    | Isso não representa um módulo de exercícios da Sala de Aula.
    |
    | Vamos confirmar possíveis registros existentes antes de remover
    | esta categoria definitivamente.
    |
    |--------------------------------------------------------------------------
    */

    private const CATEGORIAS_PERMITIDAS = [

        "dica",
        "explicacao",
        "codigo",
        "exercicio",
        "material",
        "video",
        "documentacao",
        "duvida",
        "outro"

    ];


    /*
    |--------------------------------------------------------------------------
    | TIPOS DE LINK
    |--------------------------------------------------------------------------
    */

    private const TIPOS_LINK_PERMITIDOS = [

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
                "A conexão com o banco de dados não foi carregada."
            );

        }


        $this->pdo =
            $pdo;


        $this->publicacao =
            new PublicacaoGlobal();


        $this->arquivo =
            new ArquivoPublicacaoGlobal();


        $this->link =
            new LinkPublicacaoGlobal();


        $this->resposta =
            new RespostaPublicacaoGlobal();


        $this->ajuda =
            new AjudaPublicacaoGlobal();


        $this->denuncia =
            new DenunciaPublicacaoGlobal();


        $this->upload =
            new UploadPublicacaoService();

    }


    /*
    |--------------------------------------------------------------------------
    | RESPOSTA PADRÃO
    |--------------------------------------------------------------------------
    */

    private function resposta(
        bool $status,
        string $mensagem,
        array $dados = []
    ): array {

        $resultado = [

            "status" =>
                $status,

            "mensagem" =>
                $mensagem

        ];


        foreach (
            $dados as
            $chave => $valor
        ) {

            if (
                $chave === "status"
                ||
                $chave === "mensagem"
            ) {

                continue;

            }


            $resultado[$chave] =
                $valor;

        }


        return $resultado;

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
    | NORMALIZA TEXTO CURTO
    |--------------------------------------------------------------------------
    */

    private function normalizarTextoCurto(
        string $texto
    ): string {

        $texto =
            trim(
                $texto
            );


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
    | NORMALIZA TEXTO LONGO
    |--------------------------------------------------------------------------
    */

    private function normalizarTextoLongo(
        ?string $texto
    ): ?string {

        if (
            $texto === null
        ) {

            return null;

        }


        $texto =
            str_replace(
                [
                    "\r\n",
                    "\r"
                ],
                "\n",
                $texto
            );


        $texto =
            trim(
                $texto
            );


        return
            $texto !== ""
                ?
                $texto
                :
                null;

    }


    /*
    |--------------------------------------------------------------------------
    | CONTROLE INVÁLIDO EM TEXTO CURTO
    |--------------------------------------------------------------------------
    */

    private function possuiControleInvalido(
        string $texto
    ): bool {

        return preg_match(
            "/[\x00-\x1F\x7F]/",
            $texto
        ) === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | CONTROLE INVÁLIDO EM TEXTO LONGO
    |--------------------------------------------------------------------------
    |
    | Permite TAB e quebra de linha.
    |
    |--------------------------------------------------------------------------
    */

    private function possuiControleInvalidoTextoLongo(
        string $texto
    ): bool {

        return preg_match(
            "/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/",
            $texto
        ) === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    public function criarPublicacao(
        int $usuarioId,
        string $titulo,
        ?string $conteudo,
        string $categoria = "outro",
        array $arquivos = [],
        array $links = []
    ): array {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        if (
            $usuarioId <= 0
        ) {

            return $this->resposta(
                false,
                "Usuário inválido."
            );

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
            $this->normalizarTextoLongo(
                $conteudo
            );


        $categoria =
            strtolower(
                trim(
                    $categoria
                )
            );


        /*
        |--------------------------------------------------------------------------
        | TÍTULO
        |--------------------------------------------------------------------------
        */

        if (
            $titulo === ""
        ) {

            return $this->resposta(
                false,
                "Informe um título para a publicação."
            );

        }


        if (
            !$this->utf8Valido(
                $titulo
            )
            ||
            $this->possuiControleInvalido(
                $titulo
            )
            ||
            $this->tamanhoTexto(
                $titulo
            )
            > self::LIMITE_TITULO
        ) {

            return $this->resposta(
                false,
                "Informe um título válido de até 150 caracteres."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CONTEÚDO
        |--------------------------------------------------------------------------
        */

        if (
            $conteudo !== null
            &&
            (
                !$this->utf8Valido(
                    $conteudo
                )
                ||
                $this->possuiControleInvalidoTextoLongo(
                    $conteudo
                )
            )
        ) {

            return $this->resposta(
                false,
                "O conteúdo informado possui caracteres inválidos."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORIA
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $categoria,
                self::CATEGORIAS_PERMITIDAS,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Categoria inválida."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZA ARQUIVOS
        |--------------------------------------------------------------------------
        */

        $arquivosNormalizados =
            $this->normalizarArquivos(
                $arquivos
            );


        /*
        |--------------------------------------------------------------------------
        | NORMALIZA LINKS
        |--------------------------------------------------------------------------
        */

        $linksNormalizados =
            $this->normalizarLinks(
                $links
            );


        /*
        |--------------------------------------------------------------------------
        | PUBLICAÇÃO VAZIA
        |--------------------------------------------------------------------------
        */

        if (
            $conteudo === null
            &&
            empty(
                $arquivosNormalizados
            )
            &&
            empty(
                $linksNormalizados
            )
        ) {

            return $this->resposta(
                false,
                "Adicione um texto, arquivo, vídeo ou link à publicação."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | VALIDA LINKS
        |--------------------------------------------------------------------------
        */

        foreach (
            $linksNormalizados as
            $linkRecebido
        ) {

            $url =
                $linkRecebido["url"]
                ??
                null;


            if (
                !is_string(
                    $url
                )
                ||
                !$this->urlValida(
                    $url
                )
            ) {

                return $this->resposta(
                    false,
                    "Um dos links informados é inválido."
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ARQUIVOS FÍSICOS CRIADOS
        |--------------------------------------------------------------------------
        */

        $arquivosSalvos =
            [];


        /*
        |--------------------------------------------------------------------------
        | TRANSAÇÃO
        |--------------------------------------------------------------------------
        |
        | Não utilizamos SAVEPOINT aqui.
        |
        | Esta operação possui efeito fora do banco:
        |
        | upload físico.
        |
        | Se aceitássemos uma transação externa:
        |
        | Controller libera SAVEPOINT
        | → arquivo permanece no disco
        | → transação externa executa ROLLBACK
        | → arquivo órfão.
        |
        | Portanto esta operação deve controlar sua própria transação.
        |
        |--------------------------------------------------------------------------
        */

        if (
            $this->pdo->inTransaction()
        ) {

            error_log(
                "[SalaGlobalController] Tentativa de criar publicação dentro de transação externa."
            );


            return $this->resposta(
                false,
                "Não foi possível criar a publicação."
            );

        }


        $transacaoIniciada =
            false;


        try {

            if (
                !$this->pdo->beginTransaction()
            ) {

                throw new RuntimeException(
                    "Não foi possível iniciar a transação."
                );

            }


            $transacaoIniciada =
                true;


            /*
            |--------------------------------------------------------------------------
            | PUBLICAÇÃO
            |--------------------------------------------------------------------------
            */

            $publicacaoId =
                $this->publicacao->criar(
                    $usuarioId,
                    $titulo,
                    $conteudo,
                    $categoria
                );


            $publicacaoId =
                (int) $publicacaoId;


            if (
                $publicacaoId <= 0
            ) {

                throw new DomainException(
                    "Não foi possível criar a publicação."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | ARQUIVOS
            |--------------------------------------------------------------------------
            */

            foreach (
                $arquivosNormalizados as
                $arquivoRecebido
            ) {

                $resultadoUpload =
                    $this->upload->enviar(
                        $arquivoRecebido
                    );


                if (
                    !is_array(
                        $resultadoUpload
                    )
                    ||
                    ($resultadoUpload["status"] ?? false)
                    !== true
                ) {

                    $mensagemUpload =
                        is_array(
                            $resultadoUpload
                        )
                        &&
                        is_string(
                            $resultadoUpload["mensagem"]
                            ??
                            null
                        )
                            ?
                            $resultadoUpload["mensagem"]
                            :
                            "Não foi possível enviar um dos arquivos.";


                    throw new DomainException(
                        $mensagemUpload
                    );

                }


                $dadosArquivo =
                    $resultadoUpload["arquivo"]
                    ??
                    null;


                if (
                    !is_array(
                        $dadosArquivo
                    )
                ) {

                    throw new RuntimeException(
                        "O serviço de upload retornou dados inválidos."
                    );

                }


                $nomeArquivo =
                    is_string(
                        $dadosArquivo["nome_arquivo"]
                        ??
                        null
                    )
                        ?
                        trim(
                            $dadosArquivo["nome_arquivo"]
                        )
                        :
                        "";


                if (
                    $nomeArquivo === ""
                ) {

                    throw new RuntimeException(
                        "O arquivo enviado não recebeu um nome válido."
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | GUARDA PARA POSSÍVEL ROLLBACK FÍSICO
                |--------------------------------------------------------------------------
                */

                $arquivosSalvos[] =
                    $nomeArquivo;


                /*
                |--------------------------------------------------------------------------
                | METADADOS
                |--------------------------------------------------------------------------
                */

                $arquivoId =
                    $this->arquivo->criar(
                        $publicacaoId,
                        (string) (
                            $dadosArquivo["nome_original"]
                            ??
                            ""
                        ),
                        $nomeArquivo,
                        (string) (
                            $dadosArquivo["extensao"]
                            ??
                            ""
                        ),
                        (string) (
                            $dadosArquivo["mime_type"]
                            ??
                            ""
                        ),
                        (int) (
                            $dadosArquivo["tamanho"]
                            ??
                            0
                        ),
                        (string) (
                            $dadosArquivo["caminho"]
                            ??
                            ""
                        )
                    );


                if (
                    (int) $arquivoId <= 0
                ) {

                    throw new DomainException(
                        "Não foi possível registrar um dos arquivos."
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | LINKS / VÍDEOS
            |--------------------------------------------------------------------------
            */

            foreach (
                $linksNormalizados as
                $linkRecebido
            ) {

                $url =
                    $linkRecebido["url"];


                $tituloLink =
                    $linkRecebido["titulo"]
                    ??
                    null;


                $tipo =
                    $linkRecebido["tipo"]
                    ??
                    "link";


                $linkId =
                    $this->link->criar(
                        $publicacaoId,
                        $url,
                        $tituloLink,
                        $tipo
                    );


                if (
                    (int) $linkId <= 0
                ) {

                    throw new DomainException(
                        "Não foi possível registrar um dos links."
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            if (
                !$this->pdo->commit()
            ) {

                throw new RuntimeException(
                    "Não foi possível confirmar a publicação."
                );

            }


            $transacaoIniciada =
                false;


            return $this->resposta(
                true,
                "Publicação criada com sucesso.",
                [
                    "publicacao_id" =>
                        $publicacaoId
                ]
            );

        }
        catch (
            Throwable $erro
        ) {

            /*
            |--------------------------------------------------------------------------
            | ROLLBACK DO BANCO
            |--------------------------------------------------------------------------
            */

            if (
                $transacaoIniciada
                &&
                $this->pdo->inTransaction()
            ) {

                try {

                    $this->pdo->rollBack();

                }
                catch (
                    Throwable $erroRollback
                ) {

                    error_log(
                        "[SalaGlobalController] Erro no rollback: "
                        .
                        $erroRollback->getMessage()
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | ROLLBACK DOS ARQUIVOS
            |--------------------------------------------------------------------------
            */

            foreach (
                $arquivosSalvos as
                $nomeArquivo
            ) {

                try {

                    $this->upload->excluir(
                        $nomeArquivo
                    );

                }
                catch (
                    Throwable $erroArquivo
                ) {

                    error_log(
                        "[SalaGlobalController] Falha ao remover arquivo "
                        .
                        $nomeArquivo
                        .
                        ": "
                        .
                        $erroArquivo->getMessage()
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | LOG
            |--------------------------------------------------------------------------
            */

            error_log(
                "[SalaGlobalController] Erro ao criar publicação do usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                $erro instanceof DomainException
                    ?
                    $erro->getMessage()
                    :
                    "Não foi possível criar a publicação."
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    public function buscarPublicacao(
        int $publicacaoId,
        int $usuarioId = 0
    ): array {

        if (
            $publicacaoId <= 0
        ) {

            return $this->resposta(
                false,
                "Publicação inválida."
            );

        }


        try {

            $publicacao =
                $this->buscarPublicacaoPublicada(
                    $publicacaoId
                );


            if (
                !is_array(
                    $publicacao
                )
            ) {

                return $this->resposta(
                    false,
                    "Publicação não encontrada."
                );

            }


            $publicacao =
                $this->completarPublicacao(
                    $publicacao,
                    $usuarioId,
                    true
                );


            return $this->resposta(
                true,
                "Publicação encontrada.",
                [
                    "publicacao" =>
                        $publicacao
                ]
            );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaGlobalController] Erro ao buscar publicação "
                .
                $publicacaoId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Não foi possível carregar a publicação."
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR FEED
    |--------------------------------------------------------------------------
    */

    public function listarFeed(
        ?string $categoria = null,
        ?string $pesquisa = null,
        int $limite = 20,
        int $offset = 0,
        int $usuarioId = 0
    ): array {

        $limite =
            max(
                1,
                min(
                    $limite,
                    self::LIMITE_FEED
                )
            );


        $offset =
            max(
                0,
                $offset
            );


        /*
        |--------------------------------------------------------------------------
        | PESQUISA
        |--------------------------------------------------------------------------
        */

        $pesquisa =
            $pesquisa !== null
                ?
                $this->normalizarTextoCurto(
                    $pesquisa
                )
                :
                "";


        if (
            $this->tamanhoTexto(
                $pesquisa
            )
            > self::LIMITE_PESQUISA
        ) {

            return $this->resposta(
                false,
                "A pesquisa informada é muito longa.",
                [
                    "publicacoes" =>
                        [],

                    "total" =>
                        0
                ]
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORIA
        |--------------------------------------------------------------------------
        */

        $categoriaNormalizada =
            strtolower(
                trim(
                    $categoria
                    ??
                    ""
                )
            );


        if (
            $categoriaNormalizada !== ""
            &&
            $categoriaNormalizada !== "todos"
            &&
            !in_array(
                $categoriaNormalizada,
                self::CATEGORIAS_PERMITIDAS,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Categoria inválida.",
                [
                    "publicacoes" =>
                        [],

                    "total" =>
                        0
                ]
            );

        }


        try {

            /*
            |--------------------------------------------------------------------------
            | PESQUISA TEM PRIORIDADE
            |--------------------------------------------------------------------------
            */

            if (
                $pesquisa !== ""
            ) {

                $publicacoes =
                    $this->publicacao->pesquisar(
                        $pesquisa,
                        $limite,
                        $offset
                    );

            }
            elseif (
                $categoriaNormalizada !== ""
                &&
                $categoriaNormalizada !== "todos"
            ) {

                $publicacoes =
                    $this->publicacao->listarPorCategoria(
                        $categoriaNormalizada,
                        $limite,
                        $offset
                    );

            }
            else {

                $publicacoes =
                    $this->publicacao->listarPublicadas(
                        $limite,
                        $offset
                    );

            }


            if (
                !is_array(
                    $publicacoes
                )
            ) {

                $publicacoes =
                    [];

            }


            /*
            |--------------------------------------------------------------------------
            | COMPLEMENTOS
            |--------------------------------------------------------------------------
            */

            foreach (
                $publicacoes as
                $indice => $item
            ) {

                if (
                    !is_array(
                        $item
                    )
                ) {

                    unset(
                        $publicacoes[$indice]
                    );


                    continue;

                }


                $publicacoes[$indice] =
                    $this->completarPublicacao(
                        $item,
                        $usuarioId,
                        false
                    );

            }


            $publicacoes =
                array_values(
                    $publicacoes
                );


            return $this->resposta(
                true,
                "Feed carregado.",
                [
                    "publicacoes" =>
                        $publicacoes,

                    "total" =>
                        count(
                            $publicacoes
                        )
                ]
            );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaGlobalController] Erro ao listar feed: "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Não foi possível carregar as publicações.",
                [
                    "publicacoes" =>
                        [],

                    "total" =>
                        0
                ]
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | PUBLICAÇÕES DO USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function listarDoUsuario(
        int $usuarioId
    ): array {

        if (
            $usuarioId <= 0
        ) {

            return $this->resposta(
                false,
                "Usuário inválido.",
                [
                    "publicacoes" =>
                        []
                ]
            );

        }


        try {

            $publicacoes =
                $this->publicacao->listarPorUsuario(
                    $usuarioId
                );


            if (
                !is_array(
                    $publicacoes
                )
            ) {

                $publicacoes =
                    [];

            }


            return $this->resposta(
                true,
                "Publicações carregadas.",
                [
                    "publicacoes" =>
                        $publicacoes
                ]
            );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaGlobalController] Erro ao listar publicações do usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Não foi possível carregar as publicações.",
                [
                    "publicacoes" =>
                        []
                ]
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    public function atualizarPublicacao(
        int $publicacaoId,
        int $usuarioId,
        string $titulo,
        ?string $conteudo,
        string $categoria
    ): array {

        if (
            $publicacaoId <= 0
            ||
            $usuarioId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZA
        |--------------------------------------------------------------------------
        */

        $titulo =
            $this->normalizarTextoCurto(
                $titulo
            );


        $conteudo =
            $this->normalizarTextoLongo(
                $conteudo
            );


        $categoria =
            strtolower(
                trim(
                    $categoria
                )
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDA
        |--------------------------------------------------------------------------
        */

        if (
            $titulo === ""
            ||
            !$this->utf8Valido(
                $titulo
            )
            ||
            $this->possuiControleInvalido(
                $titulo
            )
            ||
            $this->tamanhoTexto(
                $titulo
            )
            > self::LIMITE_TITULO
        ) {

            return $this->resposta(
                false,
                "Informe um título válido de até 150 caracteres."
            );

        }


        if (
            $conteudo !== null
            &&
            (
                !$this->utf8Valido(
                    $conteudo
                )
                ||
                $this->possuiControleInvalidoTextoLongo(
                    $conteudo
                )
            )
        ) {

            return $this->resposta(
                false,
                "O conteúdo informado possui caracteres inválidos."
            );

        }


        if (
            !in_array(
                $categoria,
                self::CATEGORIAS_PERMITIDAS,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Categoria inválida."
            );

        }


        try {

            /*
            |--------------------------------------------------------------------------
            | AUTORIA
            |--------------------------------------------------------------------------
            */

            if (
                !$this->publicacao->pertenceAoUsuario(
                    $publicacaoId,
                    $usuarioId
                )
            ) {

                return $this->resposta(
                    false,
                    "Você não pode editar esta publicação."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | PUBLICAÇÃO NÃO PODE FICAR VAZIA
            |--------------------------------------------------------------------------
            */

            if (
                $conteudo === null
            ) {

                $arquivos =
                    $this->arquivo->listarPorPublicacao(
                        $publicacaoId
                    );


                $links =
                    $this->link->listarPorPublicacao(
                        $publicacaoId
                    );


                if (
                    empty(
                        $arquivos
                    )
                    &&
                    empty(
                        $links
                    )
                ) {

                    return $this->resposta(
                        false,
                        "A publicação precisa possuir texto, arquivo, vídeo ou link."
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | ATUALIZA
            |--------------------------------------------------------------------------
            */

            $resultado =
                $this->publicacao->atualizar(
                    $publicacaoId,
                    $usuarioId,
                    $titulo,
                    $conteudo,
                    $categoria
                );


            return $this->resposta(
                (bool) $resultado,
                $resultado
                    ?
                    "Publicação atualizada com sucesso."
                    :
                    "Não foi possível atualizar a publicação."
            );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaGlobalController] Erro ao atualizar publicação "
                .
                $publicacaoId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Não foi possível atualizar a publicação."
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER PUBLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | SOFT DELETE.
    |
    | Os anexos permanecem armazenados porque a publicação
    | continua existindo no banco.
    |
    |--------------------------------------------------------------------------
    */

    public function removerPublicacao(
        int $publicacaoId,
        int $usuarioId
    ): array {

        if (
            $publicacaoId <= 0
            ||
            $usuarioId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        try {

            if (
                !$this->publicacao->pertenceAoUsuario(
                    $publicacaoId,
                    $usuarioId
                )
            ) {

                return $this->resposta(
                    false,
                    "Você não pode remover esta publicação."
                );

            }


            if (
                !$this->publicacao->remover(
                    $publicacaoId
                )
            ) {

                return $this->resposta(
                    false,
                    "Não foi possível remover a publicação."
                );

            }


            return $this->resposta(
                true,
                "Publicação removida."
            );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaGlobalController] Erro ao remover publicação "
                .
                $publicacaoId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Não foi possível remover a publicação."
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | RESPONDER
    |--------------------------------------------------------------------------
    */

    public function responder(
        int $publicacaoId,
        int $usuarioId,
        string $mensagem
    ): array {

        if (
            $publicacaoId <= 0
            ||
            $usuarioId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        $mensagem =
            $this->normalizarTextoLongo(
                $mensagem
            );


        if (
            $mensagem === null
        ) {

            return $this->resposta(
                false,
                "Escreva uma resposta."
            );

        }


        if (
            !$this->utf8Valido(
                $mensagem
            )
            ||
            $this->possuiControleInvalidoTextoLongo(
                $mensagem
            )
        ) {

            return $this->resposta(
                false,
                "A resposta possui caracteres inválidos."
            );

        }


        try {

            if (
                !$this->buscarPublicacaoPublicada(
                    $publicacaoId
                )
            ) {

                return $this->resposta(
                    false,
                    "Publicação não encontrada."
                );

            }


            $respostaId =
                $this->resposta->criar(
                    $publicacaoId,
                    $usuarioId,
                    $mensagem
                );


            $respostaId =
                (int) $respostaId;


            if (
                $respostaId <= 0
            ) {

                return $this->resposta(
                    false,
                    "Não foi possível publicar sua resposta."
                );

            }


            return $this->resposta(
                true,
                "Resposta publicada.",
                [
                    "resposta_id" =>
                        $respostaId
                ]
            );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaGlobalController] Erro ao responder publicação "
                .
                $publicacaoId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Não foi possível publicar sua resposta."
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR RESPOSTAS
    |--------------------------------------------------------------------------
    */

    public function listarRespostas(
        int $publicacaoId
    ): array {

        if (
            $publicacaoId <= 0
        ) {

            return $this->resposta(
                false,
                "Publicação inválida.",
                [
                    "respostas" =>
                        []
                ]
            );

        }


        try {

            if (
                !$this->buscarPublicacaoPublicada(
                    $publicacaoId
                )
            ) {

                return $this->resposta(
                    false,
                    "Publicação não encontrada.",
                    [
                        "respostas" =>
                            []
                    ]
                );

            }


            $respostas =
                $this->resposta->listarPorPublicacao(
                    $publicacaoId
                );


            if (
                !is_array(
                    $respostas
                )
            ) {

                $respostas =
                    [];

            }


            return $this->resposta(
                true,
                "Respostas carregadas.",
                [
                    "respostas" =>
                        $respostas
                ]
            );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaGlobalController] Erro ao listar respostas da publicação "
                .
                $publicacaoId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Não foi possível carregar as respostas.",
                [
                    "respostas" =>
                        []
                ]
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER PRÓPRIA RESPOSTA
    |--------------------------------------------------------------------------
    */

    public function removerResposta(
        int $respostaId,
        int $usuarioId
    ): array {

        if (
            $respostaId <= 0
            ||
            $usuarioId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        try {

            if (
                !$this->resposta->pertenceAoUsuario(
                    $respostaId,
                    $usuarioId
                )
            ) {

                return $this->resposta(
                    false,
                    "Você não pode remover esta resposta."
                );

            }


            $resultado =
                $this->resposta->remover(
                    $respostaId,
                    $usuarioId
                );


            return $this->resposta(
                (bool) $resultado,
                $resultado
                    ?
                    "Resposta removida."
                    :
                    "Não foi possível remover a resposta."
            );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaGlobalController] Erro ao remover resposta "
                .
                $respostaId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Não foi possível remover a resposta."
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | ISSO ME AJUDOU
    |--------------------------------------------------------------------------
    */

    public function alternarAjuda(
        int $publicacaoId,
        int $usuarioId
    ): array {

        if (
            $publicacaoId <= 0
            ||
            $usuarioId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos.",
                [
                    "marcado" =>
                        false,

                    "total" =>
                        0
                ]
            );

        }


        try {

            if (
                !$this->buscarPublicacaoPublicada(
                    $publicacaoId
                )
            ) {

                return $this->resposta(
                    false,
                    "Publicação não encontrada.",
                    [
                        "marcado" =>
                            false,

                        "total" =>
                            0
                    ]
                );

            }


            $resultado =
                $this->ajuda->alternar(
                    $publicacaoId,
                    $usuarioId
                );


            if (
                !is_array(
                    $resultado
                )
                ||
                ($resultado["status"] ?? false)
                !== true
            ) {

                return $this->resposta(
                    false,
                    "Não foi possível registrar sua avaliação.",
                    [
                        "marcado" =>
                            false,

                        "total" =>
                            0
                    ]
                );

            }


            $marcado =
                !empty(
                    $resultado["marcado"]
                );


            $total =
                max(
                    0,
                    (int) (
                        $resultado["total"]
                        ??
                        0
                    )
                );


            return $this->resposta(
                true,
                $marcado
                    ?
                    "Você marcou que este conteúdo ajudou."
                    :
                    "Marcação removida.",
                [
                    "marcado" =>
                        $marcado,

                    "total" =>
                        $total
                ]
            );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaGlobalController] Erro ao registrar ajuda na publicação "
                .
                $publicacaoId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Não foi possível registrar sua avaliação.",
                [
                    "marcado" =>
                        false,

                    "total" =>
                        0
                ]
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | DENUNCIAR
    |--------------------------------------------------------------------------
    */

    public function denunciar(
        int $publicacaoId,
        int $usuarioId,
        string $motivo,
        ?string $descricao = null
    ): array {

        if (
            $publicacaoId <= 0
            ||
            $usuarioId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        $motivo =
            $this->normalizarTextoCurto(
                $motivo
            );


        $descricao =
            $this->normalizarTextoLongo(
                $descricao
            );


        if (
            $motivo === ""
        ) {

            return $this->resposta(
                false,
                "Informe o motivo da denúncia."
            );

        }


        if (
            !$this->utf8Valido(
                $motivo
            )
            ||
            $this->possuiControleInvalido(
                $motivo
            )
            ||
            $this->tamanhoTexto(
                $motivo
            )
            > self::LIMITE_MOTIVO_DENUNCIA
        ) {

            return $this->resposta(
                false,
                "O motivo informado é inválido ou muito longo."
            );

        }


        if (
            $descricao !== null
            &&
            (
                !$this->utf8Valido(
                    $descricao
                )
                ||
                $this->possuiControleInvalidoTextoLongo(
                    $descricao
                )
            )
        ) {

            return $this->resposta(
                false,
                "A descrição da denúncia possui caracteres inválidos."
            );

        }


        try {

            $publicacao =
                $this->buscarPublicacaoPublicada(
                    $publicacaoId
                );


            if (
                !is_array(
                    $publicacao
                )
            ) {

                return $this->resposta(
                    false,
                    "Publicação não encontrada."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | NÃO DENUNCIA A PRÓPRIA PUBLICAÇÃO
            |--------------------------------------------------------------------------
            */

            if (
                (int) (
                    $publicacao["usuario_id"]
                    ??
                    0
                )
                === $usuarioId
            ) {

                return $this->resposta(
                    false,
                    "Você não pode denunciar sua própria publicação."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | EVITA DUPLICAÇÃO PENDENTE
            |--------------------------------------------------------------------------
            */

            if (
                $this->denuncia->existePendente(
                    $publicacaoId,
                    $usuarioId
                )
            ) {

                return $this->resposta(
                    false,
                    "Você já possui uma denúncia pendente para esta publicação."
                );

            }


            $denunciaId =
                $this->denuncia->criar(
                    $publicacaoId,
                    $usuarioId,
                    $motivo,
                    $descricao
                );


            $denunciaId =
                (int) $denunciaId;


            if (
                $denunciaId <= 0
            ) {

                return $this->resposta(
                    false,
                    "Não foi possível enviar a denúncia."
                );

            }


            return $this->resposta(
                true,
                "Denúncia enviada para análise.",
                [
                    "denuncia_id" =>
                        $denunciaId
                ]
            );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalaGlobalController] Erro ao denunciar publicação "
                .
                $publicacaoId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Não foi possível enviar a denúncia."
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | COMPLETAR PUBLICAÇÃO
    |--------------------------------------------------------------------------
    */

    private function completarPublicacao(
        array $publicacao,
        int $usuarioId,
        bool $incluirRespostas
    ): array {

        $publicacaoId =
            (int) (
                $publicacao["id"]
                ??
                0
            );


        if (
            $publicacaoId <= 0
        ) {

            $publicacao["arquivos"] =
                [];


            $publicacao["links"] =
                [];


            if (
                $incluirRespostas
            ) {

                $publicacao["respostas"] =
                    [];

            }


            $publicacao["usuario_marcou_ajuda"] =
                false;


            return $publicacao;

        }


        /*
        |--------------------------------------------------------------------------
        | ARQUIVOS
        |--------------------------------------------------------------------------
        */

        $arquivos =
            $this->arquivo->listarPorPublicacao(
                $publicacaoId
            );


        $publicacao["arquivos"] =
            is_array(
                $arquivos
            )
                ?
                $arquivos
                :
                [];


        /*
        |--------------------------------------------------------------------------
        | LINKS
        |--------------------------------------------------------------------------
        */

        $links =
            $this->link->listarPorPublicacao(
                $publicacaoId
            );


        $publicacao["links"] =
            is_array(
                $links
            )
                ?
                $links
                :
                [];


        /*
        |--------------------------------------------------------------------------
        | RESPOSTAS
        |--------------------------------------------------------------------------
        */

        if (
            $incluirRespostas
        ) {

            $respostas =
                $this->resposta->listarPorPublicacao(
                    $publicacaoId
                );


            $publicacao["respostas"] =
                is_array(
                    $respostas
                )
                    ?
                    $respostas
                    :
                    [];

        }


        /*
        |--------------------------------------------------------------------------
        | AJUDA
        |--------------------------------------------------------------------------
        */

        $publicacao["usuario_marcou_ajuda"] =
            $usuarioId > 0
                ?
                (bool) $this->ajuda->existe(
                    $publicacaoId,
                    $usuarioId
                )
                :
                false;


        return $publicacao;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR PUBLICAÇÃO PUBLICADA
    |--------------------------------------------------------------------------
    */

    private function buscarPublicacaoPublicada(
        int $publicacaoId
    ): array|false {

        if (
            $publicacaoId <= 0
        ) {

            return false;

        }


        $publicacao =
            $this->publicacao->buscarPorId(
                $publicacaoId
            );


        if (
            !is_array(
                $publicacao
            )
        ) {

            return false;

        }


        $status =
            $publicacao["status"]
            ??
            null;


        if (
            !is_string(
                $status
            )
            ||
            strtolower(
                trim(
                    $status
                )
            )
            !== "publicado"
        ) {

            return false;

        }


        return $publicacao;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR ARQUIVOS
    |--------------------------------------------------------------------------
    */

    private function normalizarArquivos(
        array $arquivos
    ): array {

        if (
            empty(
                $arquivos
            )
        ) {

            return [];

        }


        /*
        |--------------------------------------------------------------------------
        | ARQUIVO ÚNICO
        |--------------------------------------------------------------------------
        */

        if (
            array_key_exists(
                "name",
                $arquivos
            )
            &&
            !is_array(
                $arquivos["name"]
            )
        ) {

            $nome =
                $arquivos["name"]
                ??
                null;


            $tipo =
                $arquivos["type"]
                ??
                null;


            $temporario =
                $arquivos["tmp_name"]
                ??
                null;


            if (
                !is_string(
                    $nome
                )
                ||
                !is_string(
                    $tipo
                )
                ||
                !is_string(
                    $temporario
                )
            ) {

                return [];

            }


            $erro =
                filter_var(
                    $arquivos["error"]
                    ??
                    UPLOAD_ERR_NO_FILE,
                    FILTER_VALIDATE_INT
                );


            if (
                $erro === false
                ||
                $erro === UPLOAD_ERR_NO_FILE
            ) {

                return [];

            }


            $tamanho =
                filter_var(
                    $arquivos["size"]
                    ??
                    0,
                    FILTER_VALIDATE_INT
                );


            if (
                $tamanho === false
            ) {

                $tamanho =
                    0;

            }


            return [[

                "name" =>
                    $nome,

                "type" =>
                    $tipo,

                "tmp_name" =>
                    $temporario,

                "error" =>
                    $erro,

                "size" =>
                    max(
                        0,
                        $tamanho
                    )

            ]];

        }


        /*
        |--------------------------------------------------------------------------
        | VÁRIOS ARQUIVOS
        |--------------------------------------------------------------------------
        */

        if (
            !isset(
                $arquivos["name"]
            )
            ||
            !is_array(
                $arquivos["name"]
            )
        ) {

            return [];

        }


        $normalizados =
            [];


        foreach (
            $arquivos["name"] as
            $indice => $nome
        ) {

            if (
                !is_string(
                    $nome
                )
            ) {

                continue;

            }


            $tipo =
                $arquivos["type"][$indice]
                ??
                null;


            $temporario =
                $arquivos["tmp_name"][$indice]
                ??
                null;


            if (
                !is_string(
                    $tipo
                )
                ||
                !is_string(
                    $temporario
                )
            ) {

                continue;

            }


            $erro =
                filter_var(
                    $arquivos["error"][$indice]
                    ??
                    UPLOAD_ERR_NO_FILE,
                    FILTER_VALIDATE_INT
                );


            if (
                $erro === false
                ||
                $erro === UPLOAD_ERR_NO_FILE
            ) {

                continue;

            }


            $tamanho =
                filter_var(
                    $arquivos["size"][$indice]
                    ??
                    0,
                    FILTER_VALIDATE_INT
                );


            if (
                $tamanho === false
            ) {

                $tamanho =
                    0;

            }


            $normalizados[] = [

                "name" =>
                    $nome,

                "type" =>
                    $tipo,

                "tmp_name" =>
                    $temporario,

                "error" =>
                    $erro,

                "size" =>
                    max(
                        0,
                        $tamanho
                    )

            ];

        }


        return $normalizados;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR LINKS
    |--------------------------------------------------------------------------
    */

    private function normalizarLinks(
        array $links
    ): array {

        $normalizados =
            [];


        foreach (
            $links as
            $link
        ) {

            if (
                !is_array(
                    $link
                )
            ) {

                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | URL
            |--------------------------------------------------------------------------
            */

            $urlRecebida =
                $link["url"]
                ??
                null;


            if (
                !is_string(
                    $urlRecebida
                )
            ) {

                continue;

            }


            $url =
                trim(
                    $urlRecebida
                );


            if (
                $url === ""
                ||
                strlen(
                    $url
                )
                > self::LIMITE_URL
            ) {

                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | TÍTULO
            |--------------------------------------------------------------------------
            */

            $tituloRecebido =
                $link["titulo"]
                ??
                "";


            if (
                !is_string(
                    $tituloRecebido
                )
            ) {

                $tituloRecebido =
                    "";

            }


            $titulo =
                $this->normalizarTextoCurto(
                    $tituloRecebido
                );


            if (
                $titulo !== ""
                &&
                (
                    !$this->utf8Valido(
                        $titulo
                    )
                    ||
                    $this->possuiControleInvalido(
                        $titulo
                    )
                    ||
                    $this->tamanhoTexto(
                        $titulo
                    )
                    > self::LIMITE_TITULO_LINK
                )
            ) {

                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | TIPO
            |--------------------------------------------------------------------------
            */

            $tipoRecebido =
                $link["tipo"]
                ??
                "link";


            if (
                !is_string(
                    $tipoRecebido
                )
            ) {

                $tipoRecebido =
                    "link";

            }


            $tipo =
                strtolower(
                    trim(
                        $tipoRecebido
                    )
                );


            if (
                !in_array(
                    $tipo,
                    self::TIPOS_LINK_PERMITIDOS,
                    true
                )
            ) {

                continue;

            }


            $normalizados[] = [

                "url" =>
                    $url,

                "titulo" =>
                    $titulo !== ""
                        ?
                        $titulo
                        :
                        null,

                "tipo" =>
                    $tipo

            ];

        }


        return $normalizados;

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAR URL
    |--------------------------------------------------------------------------
    */

    private function urlValida(
        string $url
    ): bool {

        $url =
            trim(
                $url
            );


        if (
            $url === ""
            ||
            strlen(
                $url
            )
            > self::LIMITE_URL
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
            ||
            empty(
                $partes["scheme"]
            )
            ||
            empty(
                $partes["host"]
            )
        ) {

            return false;

        }


        $esquema =
            strtolower(
                (string) $partes["scheme"]
            );


        return in_array(
            $esquema,
            [
                "http",
                "https"
            ],
            true
        );

    }

}