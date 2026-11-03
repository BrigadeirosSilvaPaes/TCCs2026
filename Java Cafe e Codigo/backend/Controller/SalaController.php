<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| SALA CONTROLLER
|
| Responsável pelas Salas de Aula:
|
| - criação e gerenciamento de salas;
| - entrada e saída de estudantes;
| - participantes;
| - publicações;
| - arquivos;
| - links;
| - vídeos.
|
| A Sala de Aula NÃO possui:
|
| - sistema de mensagens;
| - sistema próprio de exercícios.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| MODELS
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../models/Sala.php";
require_once __DIR__ . "/../models/SalaAlunos.php";
require_once __DIR__ . "/../models/SalaConteudo.php";
require_once __DIR__ . "/../models/SalaConteudoArquivo.php";
require_once __DIR__ . "/../models/SalaConteudoLink.php";


class SalaController
{

    /*
    |--------------------------------------------------------------------------
    | MODELS
    |--------------------------------------------------------------------------
    */

    private Sala $sala;

    private SalaAluno $salaAluno;

    private SalaConteudo $salaConteudo;

    private SalaConteudoArquivo $salaConteudoArquivo;

    private SalaConteudoLink $salaConteudoLink;


    /*
    |--------------------------------------------------------------------------
    | CATEGORIAS DE CONTEÚDO
    |--------------------------------------------------------------------------
    |
    | "exercicio" foi removido.
    |
    | A Sala de Aula publica materiais e conteúdos.
    | Ela não implementa um módulo próprio de exercícios.
    |
    |--------------------------------------------------------------------------
    */

    private const CATEGORIAS_CONTEUDO = [

        "aviso",
        "explicacao",
        "codigo",
        "material",
        "video",
        "link",
        "outro"

    ];


    /*
    |--------------------------------------------------------------------------
    | STATUS DE CONTEÚDO
    |--------------------------------------------------------------------------
    */

    private const STATUS_CONTEUDO = [

        "publicado",
        "rascunho",
        "oculto"

    ];


    /*
    |--------------------------------------------------------------------------
    | TIPOS DE RECURSO EXTERNO
    |--------------------------------------------------------------------------
    */

    private const TIPOS_RECURSO = [

        "link",
        "video"

    ];


    /*
    |--------------------------------------------------------------------------
    | EXTENSÕES DE ARQUIVO PERMITIDAS
    |--------------------------------------------------------------------------
    |
    | Mantém compatibilidade com o fluxo atual de upload das salas.
    |
    |--------------------------------------------------------------------------
    */

    private const EXTENSOES_ARQUIVO = [

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
    | TAMANHO MÁXIMO DE ARQUIVO
    |--------------------------------------------------------------------------
    |
    | 10 MB.
    |
    |--------------------------------------------------------------------------
    */

    private const TAMANHO_MAXIMO_ARQUIVO =
        10 * 1024 * 1024;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {

        $this->sala =
            new Sala();


        $this->salaAluno =
            new SalaAluno();


        $this->salaConteudo =
            new SalaConteudo();


        $this->salaConteudoArquivo =
            new SalaConteudoArquivo();


        $this->salaConteudoLink =
            new SalaConteudoLink();

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
    | PROFESSOR É DONO DA SALA
    |--------------------------------------------------------------------------
    */

    private function professorEhDono(
        array $sala,
        int $professorId
    ): bool {

        if (
            $professorId <= 0
        ) {

            return false;

        }


        return
            (int) (
                $sala["professor_id"]
                ??
                0
            )
            ===
            $professorId;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR SALA DO CONTEÚDO
    |--------------------------------------------------------------------------
    */

    private function buscarSalaDoConteudo(
        array $conteudo
    ): array|false {

        $salaId =
            (int) (
                $conteudo["sala_id"]
                ??
                0
            );


        if (
            $salaId <= 0
        ) {

            return false;

        }


        $sala =
            $this->sala->buscarPorId(
                $salaId
            );


        return
            is_array(
                $sala
            )
                ?
                $sala
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | MONTAR ANEXOS DO CONTEÚDO
    |--------------------------------------------------------------------------
    */

    private function montarAnexosConteudo(
        int $conteudoId
    ): array {

        if (
            $conteudoId <= 0
        ) {

            return [

                "arquivos" => [],
                "links" => [],
                "videos" => [],

                "total_arquivos" => 0,
                "total_links" => 0,
                "total_videos" => 0

            ];

        }


        $arquivos =
            $this->salaConteudoArquivo
                ->listarPorConteudo(
                    $conteudoId
                );


        $links =
            $this->salaConteudoLink
                ->listarLinks(
                    $conteudoId
                );


        $videos =
            $this->salaConteudoLink
                ->listarVideos(
                    $conteudoId
                );


        if (
            !is_array(
                $arquivos
            )
        ) {

            $arquivos =
                [];

        }


        if (
            !is_array(
                $links
            )
        ) {

            $links =
                [];

        }


        if (
            !is_array(
                $videos
            )
        ) {

            $videos =
                [];

        }


        return [

            "arquivos" =>
                $arquivos,

            "links" =>
                $links,

            "videos" =>
                $videos,

            "total_arquivos" =>
                count(
                    $arquivos
                ),

            "total_links" =>
                count(
                    $links
                ),

            "total_videos" =>
                count(
                    $videos
                )

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | ADICIONAR ANEXOS À LISTA DE CONTEÚDOS
    |--------------------------------------------------------------------------
    */

    private function adicionarAnexosAConteudos(
        array $conteudos
    ): array {

        foreach (
            $conteudos as
            $indice => $conteudo
        ) {

            if (
                !is_array(
                    $conteudo
                )
            ) {

                continue;

            }


            $conteudoId =
                (int) (
                    $conteudo["id"]
                    ??
                    0
                );


            $anexos =
                $this->montarAnexosConteudo(
                    $conteudoId
                );


            $conteudos[$indice]["arquivos"] =
                $anexos["arquivos"];


            $conteudos[$indice]["links"] =
                $anexos["links"];


            $conteudos[$indice]["videos"] =
                $anexos["videos"];


            $conteudos[$indice]["total_arquivos"] =
                $anexos["total_arquivos"];


            $conteudos[$indice]["total_links"] =
                $anexos["total_links"];


            $conteudos[$indice]["total_videos"] =
                $anexos["total_videos"];

        }


        return $conteudos;

    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR SALA
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $professorId,
        string $nome,
        string $codigo,
        ?string $linguagem,
        ?string $descricao
    ): array {

        if (
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Professor inválido."
            );

        }


        $nome =
            $this->normalizarTextoCurto(
                $nome
            );


        $codigo =
            strtoupper(
                trim(
                    $codigo
                )
            );


        $linguagem =
            $linguagem !== null
                ?
                $this->normalizarTextoCurto(
                    $linguagem
                )
                :
                null;


        $descricao =
            $this->normalizarTextoLongo(
                $descricao
            );


        if (
            $nome === ""
        ) {

            return $this->resposta(
                false,
                "Informe o nome da sala."
            );

        }


        if (
            $codigo === ""
        ) {

            return $this->resposta(
                false,
                "Informe o código da sala."
            );

        }


        if (
            preg_match(
                "/[\x00-\x1F\x7F]/",
                $codigo
            )
            === 1
        ) {

            return $this->resposta(
                false,
                "Código de sala inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CÓDIGO JÁ EXISTE
        |--------------------------------------------------------------------------
        |
        | O banco também deverá possuir UNIQUE nesse campo.
        |
        |--------------------------------------------------------------------------
        */

        if (
            $this->sala->existeCodigo(
                $codigo
            )
        ) {

            return $this->resposta(
                false,
                "Este código de sala já está sendo utilizado."
            );

        }


        $resultado =
            $this->sala->criar(
                $professorId,
                $nome,
                $codigo,
                $linguagem,
                $descricao
            );


        if (
            !$resultado
        ) {

            return $this->resposta(
                false,
                "Não foi possível criar a sala."
            );

        }


        return $this->resposta(
            true,
            "Sala criada com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR SALA
    |--------------------------------------------------------------------------
    */

    public function buscar(
        int $id
    ): array {

        if (
            $id <= 0
        ) {

            return $this->resposta(
                false,
                "Sala inválida."
            );

        }


        $sala =
            $this->sala->buscarPorId(
                $id
            );


        if (
            !is_array(
                $sala
            )
        ) {

            return $this->resposta(
                false,
                "Sala não encontrada."
            );

        }


        return $this->resposta(
            true,
            "Sala encontrada.",
            [
                "sala" =>
                    $sala
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR SALA PELO CÓDIGO
    |--------------------------------------------------------------------------
    */

    public function buscarPorCodigo(
        string $codigo
    ): array {

        $codigo =
            strtoupper(
                trim(
                    $codigo
                )
            );


        if (
            $codigo === ""
        ) {

            return $this->resposta(
                false,
                "Informe o código da sala."
            );

        }


        $sala =
            $this->sala->buscarPorCodigo(
                $codigo
            );


        if (
            !is_array(
                $sala
            )
        ) {

            return $this->resposta(
                false,
                "Nenhuma sala foi encontrada com esse código."
            );

        }


        return $this->resposta(
            true,
            "Sala encontrada.",
            [
                "sala" =>
                    $sala
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR SALAS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function listarSalasProfessor(
        int $professorId
    ): array {

        if (
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Professor inválido.",
                [
                    "salas" =>
                        []
                ]
            );

        }


        $salas =
            $this->sala->listarPorProfessor(
                $professorId
            );


        if (
            !is_array(
                $salas
            )
        ) {

            $salas =
                [];

        }


        return $this->resposta(
            true,
            "Salas carregadas.",
            [
                "salas" =>
                    $salas
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR SALA
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $salaId,
        int $professorId,
        string $nome,
        ?string $linguagem,
        ?string $descricao
    ): array {

        if (
            $salaId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        $nome =
            $this->normalizarTextoCurto(
                $nome
            );


        $linguagem =
            $linguagem !== null
                ?
                $this->normalizarTextoCurto(
                    $linguagem
                )
                :
                null;


        $descricao =
            $this->normalizarTextoLongo(
                $descricao
            );


        if (
            $nome === ""
        ) {

            return $this->resposta(
                false,
                "Informe o nome da sala."
            );

        }


        $sala =
            $this->sala->buscarPorId(
                $salaId
            );


        if (
            !is_array(
                $sala
            )
        ) {

            return $this->resposta(
                false,
                "Sala não encontrada."
            );

        }


        if (
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para editar esta sala."
            );

        }


        $resultado =
            $this->sala->atualizar(
                $salaId,
                $nome,
                $linguagem,
                $descricao
            );


        if (
            !$resultado
        ) {

            return $this->resposta(
                false,
                "Não foi possível atualizar a sala."
            );

        }


        return $this->resposta(
            true,
            "Sala atualizada com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR SALA
    |--------------------------------------------------------------------------
    */

    public function excluir(
        int $salaId,
        int $professorId
    ): array {

        if (
            $salaId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        $sala =
            $this->sala->buscarPorId(
                $salaId
            );


        if (
            !is_array(
                $sala
            )
        ) {

            return $this->resposta(
                false,
                "Sala não encontrada."
            );

        }


        if (
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para excluir esta sala."
            );

        }


        if (
            !$this->sala->excluir(
                $salaId
            )
        ) {

            return $this->resposta(
                false,
                "Não foi possível excluir a sala."
            );

        }


        return $this->resposta(
            true,
            "Sala excluída com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ENTRAR NA SALA
    |--------------------------------------------------------------------------
    */

    public function entrar(
        int $salaId,
        int $alunoId
    ): array {

        if (
            $salaId <= 0
            ||
            $alunoId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados da sala ou do estudante são inválidos."
            );

        }


        $sala =
            $this->sala->buscarPorId(
                $salaId
            );


        if (
            !is_array(
                $sala
            )
        ) {

            return $this->resposta(
                false,
                "Sala não encontrada."
            );

        }


        if (
            $this->professorEhDono(
                $sala,
                $alunoId
            )
        ) {

            return $this->resposta(
                false,
                "O professor responsável já possui acesso à sala.",
                [
                    "sala" =>
                        $sala
                ]
            );

        }


        if (
            $this->salaAluno->existe(
                $salaId,
                $alunoId
            )
        ) {

            return $this->resposta(
                false,
                "Você já participa desta sala.",
                [
                    "sala" =>
                        $sala
                ]
            );

        }


        if (
            !$this->salaAluno->entrar(
                $salaId,
                $alunoId
            )
        ) {

            return $this->resposta(
                false,
                "Não foi possível entrar na sala."
            );

        }


        return $this->resposta(
            true,
            "Você entrou na sala com sucesso.",
            [
                "sala" =>
                    $sala
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ENTRAR PELO CÓDIGO
    |--------------------------------------------------------------------------
    */

    public function entrarPorCodigo(
        string $codigo,
        int $alunoId
    ): array {

        if (
            $alunoId <= 0
        ) {

            return $this->resposta(
                false,
                "Estudante inválido."
            );

        }


        $codigo =
            strtoupper(
                trim(
                    $codigo
                )
            );


        if (
            $codigo === ""
        ) {

            return $this->resposta(
                false,
                "Informe o código da sala."
            );

        }


        $sala =
            $this->sala->buscarPorCodigo(
                $codigo
            );


        if (
            !is_array(
                $sala
            )
        ) {

            return $this->resposta(
                false,
                "Código de sala não encontrado."
            );

        }


        $salaId =
            (int) (
                $sala["id"]
                ??
                0
            );


        if (
            $salaId <= 0
        ) {

            return $this->resposta(
                false,
                "Sala inválida."
            );

        }


        return $this->entrar(
            $salaId,
            $alunoId
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR SALAS DO ALUNO
    |--------------------------------------------------------------------------
    */

    public function listarSalasAluno(
        int $alunoId
    ): array {

        if (
            $alunoId <= 0
        ) {

            return $this->resposta(
                false,
                "Estudante inválido.",
                [
                    "salas" =>
                        []
                ]
            );

        }


        $salas =
            $this->salaAluno->listarSalasAluno(
                $alunoId
            );


        if (
            !is_array(
                $salas
            )
        ) {

            $salas =
                [];

        }


        return $this->resposta(
            true,
            "Salas carregadas.",
            [
                "salas" =>
                    $salas
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SAIR DA SALA
    |--------------------------------------------------------------------------
    */

    public function sair(
        int $salaId,
        int $alunoId
    ): array {

        if (
            $salaId <= 0
            ||
            $alunoId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        if (
            !$this->salaAluno->existe(
                $salaId,
                $alunoId
            )
        ) {

            return $this->resposta(
                false,
                "Você não participa desta sala."
            );

        }


        if (
            !$this->salaAluno->sair(
                $salaId,
                $alunoId
            )
        ) {

            return $this->resposta(
                false,
                "Não foi possível sair da sala."
            );

        }


        return $this->resposta(
            true,
            "Você saiu da sala."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR ALUNOS
    |--------------------------------------------------------------------------
    */

    public function listarAlunos(
        int $salaId,
        int $professorId
    ): array {

        if (
            $salaId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos.",
                [
                    "alunos" =>
                        []
                ]
            );

        }


        $sala =
            $this->sala->buscarPorId(
                $salaId
            );


        if (
            !is_array(
                $sala
            )
        ) {

            return $this->resposta(
                false,
                "Sala não encontrada.",
                [
                    "alunos" =>
                        []
                ]
            );

        }


        if (
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para visualizar os alunos desta sala.",
                [
                    "alunos" =>
                        []
                ]
            );

        }


        $alunos =
            $this->salaAluno->listarAlunos(
                $salaId
            );


        if (
            !is_array(
                $alunos
            )
        ) {

            $alunos =
                [];

        }


        return $this->resposta(
            true,
            "Participantes carregados.",
            [
                "alunos" =>
                    $alunos
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR CONTEÚDO
    |--------------------------------------------------------------------------
    */

    public function criarConteudo(
        int $salaId,
        int $professorId,
        string $titulo,
        ?string $conteudo,
        string $categoria = "material",
        string $status = "publicado"
    ): array {

        if (
            $salaId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


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


        $status =
            strtolower(
                trim(
                    $status
                )
            );


        if (
            $titulo === ""
        ) {

            return $this->resposta(
                false,
                "Informe o título do conteúdo."
            );

        }


        if (
            !in_array(
                $categoria,
                self::CATEGORIAS_CONTEUDO,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Categoria inválida."
            );

        }


        if (
            !in_array(
                $status,
                self::STATUS_CONTEUDO,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Status inválido."
            );

        }


        $sala =
            $this->sala->buscarPorId(
                $salaId
            );


        if (
            !is_array(
                $sala
            )
        ) {

            return $this->resposta(
                false,
                "Sala não encontrada."
            );

        }


        if (
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para publicar nesta sala."
            );

        }


        $conteudoId =
            $this->salaConteudo->criar(
                $salaId,
                $professorId,
                $titulo,
                $conteudo,
                $categoria,
                $status
            );


        $conteudoId =
            (int) $conteudoId;


        if (
            $conteudoId <= 0
        ) {

            return $this->resposta(
                false,
                "Não foi possível criar o conteúdo."
            );

        }


        return $this->resposta(
            true,
            $status === "rascunho"
                ?
                "Rascunho salvo com sucesso."
                :
                "Conteúdo publicado com sucesso.",
            [
                "conteudo_id" =>
                    $conteudoId
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR CONTEÚDOS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function listarConteudosProfessor(
        int $salaId,
        int $professorId
    ): array {

        if (
            $salaId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos.",
                [
                    "conteudos" =>
                        []
                ]
            );

        }


        $sala =
            $this->sala->buscarPorId(
                $salaId
            );


        if (
            !is_array(
                $sala
            )
        ) {

            return $this->resposta(
                false,
                "Sala não encontrada.",
                [
                    "conteudos" =>
                        []
                ]
            );

        }


        if (
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para visualizar os conteúdos desta sala.",
                [
                    "conteudos" =>
                        []
                ]
            );

        }


        $conteudos =
            $this->salaConteudo->listarPorSala(
                $salaId,
                true
            );


        if (
            !is_array(
                $conteudos
            )
        ) {

            $conteudos =
                [];

        }


        $conteudos =
            $this->adicionarAnexosAConteudos(
                $conteudos
            );


        return $this->resposta(
            true,
            "Conteúdos carregados.",
            [
                "conteudos" =>
                    $conteudos
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR CONTEÚDOS DO ALUNO
    |--------------------------------------------------------------------------
    */

    public function listarConteudosAluno(
        int $salaId,
        int $alunoId
    ): array {

        if (
            $salaId <= 0
            ||
            $alunoId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos.",
                [
                    "conteudos" =>
                        []
                ]
            );

        }


        $sala =
            $this->sala->buscarPorId(
                $salaId
            );


        if (
            !is_array(
                $sala
            )
        ) {

            return $this->resposta(
                false,
                "Sala não encontrada.",
                [
                    "conteudos" =>
                        []
                ]
            );

        }


        if (
            !$this->salaAluno->existe(
                $salaId,
                $alunoId
            )
        ) {

            return $this->resposta(
                false,
                "Você não participa desta sala.",
                [
                    "conteudos" =>
                        []
                ]
            );

        }


        $conteudos =
            $this->salaConteudo->listarPorSala(
                $salaId,
                false
            );


        if (
            !is_array(
                $conteudos
            )
        ) {

            $conteudos =
                [];

        }


        $conteudos =
            $this->adicionarAnexosAConteudos(
                $conteudos
            );


        return $this->resposta(
            true,
            "Conteúdos carregados.",
            [
                "conteudos" =>
                    $conteudos
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR CONTEÚDO DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function buscarConteudoProfessor(
        int $conteudoId,
        int $professorId
    ): array {

        if (
            $conteudoId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Conteúdo inválido."
            );

        }


        $conteudo =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        $sala =
            $this->buscarSalaDoConteudo(
                $conteudo
            );


        if (
            !is_array(
                $sala
            )
            ||
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para acessar este conteúdo."
            );

        }


        $anexos =
            $this->montarAnexosConteudo(
                $conteudoId
            );


        $conteudo["arquivos"] =
            $anexos["arquivos"];


        $conteudo["links"] =
            $anexos["links"];


        $conteudo["videos"] =
            $anexos["videos"];


        $conteudo["total_arquivos"] =
            $anexos["total_arquivos"];


        $conteudo["total_links"] =
            $anexos["total_links"];


        $conteudo["total_videos"] =
            $anexos["total_videos"];


        return $this->resposta(
            true,
            "Conteúdo encontrado.",
            [
                "conteudo" =>
                    $conteudo
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR CONTEÚDO DO ALUNO
    |--------------------------------------------------------------------------
    */

    public function buscarConteudoAluno(
        int $conteudoId,
        int $alunoId
    ): array {

        if (
            $conteudoId <= 0
            ||
            $alunoId <= 0
        ) {

            return $this->resposta(
                false,
                "Conteúdo inválido."
            );

        }


        $conteudo =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        if (
            (
                $conteudo["status"]
                ??
                ""
            )
            !== "publicado"
        ) {

            return $this->resposta(
                false,
                "Este conteúdo não está disponível."
            );

        }


        $salaId =
            (int) (
                $conteudo["sala_id"]
                ??
                0
            );


        if (
            $salaId <= 0
            ||
            !$this->salaAluno->existe(
                $salaId,
                $alunoId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui acesso a este conteúdo."
            );

        }


        $anexos =
            $this->montarAnexosConteudo(
                $conteudoId
            );


        $conteudo["arquivos"] =
            $anexos["arquivos"];


        $conteudo["links"] =
            $anexos["links"];


        $conteudo["videos"] =
            $anexos["videos"];


        $conteudo["total_arquivos"] =
            $anexos["total_arquivos"];


        $conteudo["total_links"] =
            $anexos["total_links"];


        $conteudo["total_videos"] =
            $anexos["total_videos"];


        return $this->resposta(
            true,
            "Conteúdo encontrado.",
            [
                "conteudo" =>
                    $conteudo
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR CONTEÚDO
    |--------------------------------------------------------------------------
    */

    public function atualizarConteudo(
        int $conteudoId,
        int $professorId,
        string $titulo,
        ?string $conteudo,
        string $categoria,
        string $status
    ): array {

        if (
            $conteudoId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


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


        $status =
            strtolower(
                trim(
                    $status
                )
            );


        if (
            $titulo === ""
        ) {

            return $this->resposta(
                false,
                "Informe o título do conteúdo."
            );

        }


        if (
            !in_array(
                $categoria,
                self::CATEGORIAS_CONTEUDO,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Categoria inválida."
            );

        }


        if (
            !in_array(
                $status,
                self::STATUS_CONTEUDO,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Status inválido."
            );

        }


        $conteudoAtual =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudoAtual
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        $sala =
            $this->buscarSalaDoConteudo(
                $conteudoAtual
            );


        if (
            !is_array(
                $sala
            )
            ||
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para editar este conteúdo."
            );

        }


        if (
            !$this->salaConteudo->atualizar(
                $conteudoId,
                $titulo,
                $conteudo,
                $categoria,
                $status
            )
        ) {

            return $this->resposta(
                false,
                "Não foi possível atualizar o conteúdo."
            );

        }


        return $this->resposta(
            true,
            "Conteúdo atualizado com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ALTERAR STATUS DO CONTEÚDO
    |--------------------------------------------------------------------------
    */

    public function alterarStatusConteudo(
        int $conteudoId,
        int $professorId,
        string $status
    ): array {

        if (
            $conteudoId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        $status =
            strtolower(
                trim(
                    $status
                )
            );


        if (
            !in_array(
                $status,
                self::STATUS_CONTEUDO,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Status inválido."
            );

        }


        $conteudo =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        $sala =
            $this->buscarSalaDoConteudo(
                $conteudo
            );


        if (
            !is_array(
                $sala
            )
            ||
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para alterar este conteúdo."
            );

        }


        if (
            !$this->salaConteudo->alterarStatus(
                $conteudoId,
                $status
            )
        ) {

            return $this->resposta(
                false,
                "Não foi possível alterar o conteúdo."
            );

        }


        return $this->resposta(
            true,
            "Status atualizado com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR CONTEÚDO
    |--------------------------------------------------------------------------
    */

    public function excluirConteudo(
        int $conteudoId,
        int $professorId
    ): array {

        if (
            $conteudoId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        $conteudo =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        $sala =
            $this->buscarSalaDoConteudo(
                $conteudo
            );


        if (
            !is_array(
                $sala
            )
            ||
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para excluir este conteúdo."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | GUARDA METADADOS DOS ARQUIVOS
        |--------------------------------------------------------------------------
        |
        | Após o DELETE, registros relacionados podem desaparecer
        | por ON DELETE CASCADE.
        |
        |--------------------------------------------------------------------------
        */

        $arquivos =
            $this->salaConteudoArquivo
                ->listarPorConteudo(
                    $conteudoId
                );


        if (
            !is_array(
                $arquivos
            )
        ) {

            $arquivos =
                [];

        }


        if (
            !$this->salaConteudo->excluir(
                $conteudoId
            )
        ) {

            return $this->resposta(
                false,
                "Não foi possível excluir o conteúdo."
            );

        }


        return $this->resposta(
            true,
            "Conteúdo excluído com sucesso.",
            [
                "arquivos_removidos" =>
                    $arquivos
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR CONTEÚDOS
    |--------------------------------------------------------------------------
    */

    public function contarConteudos(
        int $salaId
    ): int {

        if (
            $salaId <= 0
        ) {

            return 0;

        }


        $total =
            $this->salaConteudo->contarPorSala(
                $salaId
            );


        return max(
            0,
            (int) $total
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ADICIONAR ARQUIVO
    |--------------------------------------------------------------------------
    |
    | O upload físico continua sendo responsabilidade da camada
    | que recebe o arquivo.
    |
    | Aqui registramos apenas metadados já validados.
    |
    |--------------------------------------------------------------------------
    */

    public function adicionarArquivo(
        int $conteudoId,
        int $professorId,
        string $nomeOriginal,
        string $nomeArquivo,
        string $extensao,
        string $mimeType,
        int $tamanho,
        string $caminho
    ): array {

        if (
            $conteudoId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


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
            trim(
                $caminho
            );


        if (
            $nomeOriginal === ""
            ||
            $nomeArquivo === ""
            ||
            $extensao === ""
            ||
            $mimeType === ""
            ||
            $caminho === ""
        ) {

            return $this->resposta(
                false,
                "Dados do arquivo incompletos."
            );

        }


        if (
            str_contains(
                $nomeOriginal,
                "\0"
            )
            ||
            str_contains(
                $nomeArquivo,
                "\0"
            )
            ||
            str_contains(
                $caminho,
                "\0"
            )
        ) {

            return $this->resposta(
                false,
                "Arquivo inválido."
            );

        }


        if (
            !in_array(
                $extensao,
                self::EXTENSOES_ARQUIVO,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Tipo de arquivo não permitido."
            );

        }


        if (
            $tamanho <= 0
            ||
            $tamanho >
            self::TAMANHO_MAXIMO_ARQUIVO
        ) {

            return $this->resposta(
                false,
                "Arquivo inválido."
            );

        }


        $conteudo =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        $sala =
            $this->buscarSalaDoConteudo(
                $conteudo
            );


        if (
            !is_array(
                $sala
            )
            ||
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para adicionar arquivos neste conteúdo."
            );

        }


        $arquivoId =
            $this->salaConteudoArquivo->criar(
                $conteudoId,
                $nomeOriginal,
                $nomeArquivo,
                $extensao,
                $mimeType,
                $tamanho,
                $caminho
            );


        $arquivoId =
            (int) $arquivoId;


        if (
            $arquivoId <= 0
        ) {

            return $this->resposta(
                false,
                "Não foi possível registrar o arquivo."
            );

        }


        return $this->resposta(
            true,
            "Arquivo adicionado com sucesso.",
            [
                "arquivo_id" =>
                    $arquivoId
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER ARQUIVO
    |--------------------------------------------------------------------------
    */

    public function removerArquivo(
        int $arquivoId,
        int $professorId
    ): array {

        if (
            $arquivoId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        $arquivo =
            $this->salaConteudoArquivo
                ->buscarPorId(
                    $arquivoId
                );


        if (
            !is_array(
                $arquivo
            )
        ) {

            return $this->resposta(
                false,
                "Arquivo não encontrado."
            );

        }


        $conteudoId =
            (int) (
                $arquivo["conteudo_id"]
                ??
                0
            );


        if (
            $conteudoId <= 0
        ) {

            return $this->resposta(
                false,
                "Conteúdo inválido."
            );

        }


        $conteudo =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        $sala =
            $this->buscarSalaDoConteudo(
                $conteudo
            );


        if (
            !is_array(
                $sala
            )
            ||
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para remover este arquivo."
            );

        }


        if (
            !$this->salaConteudoArquivo->excluir(
                $arquivoId
            )
        ) {

            return $this->resposta(
                false,
                "Não foi possível remover o arquivo."
            );

        }


        return $this->resposta(
            true,
            "Arquivo removido com sucesso.",
            [
                "arquivo" =>
                    $arquivo
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ADICIONAR LINK
    |--------------------------------------------------------------------------
    */

    public function adicionarLink(
        int $conteudoId,
        int $professorId,
        string $url,
        ?string $titulo = null
    ): array {

        return $this->adicionarLinkOuVideo(
            $conteudoId,
            $professorId,
            $url,
            $titulo,
            "link"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ADICIONAR VÍDEO
    |--------------------------------------------------------------------------
    */

    public function adicionarVideo(
        int $conteudoId,
        int $professorId,
        string $url,
        ?string $titulo = null
    ): array {

        return $this->adicionarLinkOuVideo(
            $conteudoId,
            $professorId,
            $url,
            $titulo,
            "video"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ADICIONAR LINK OU VÍDEO
    |--------------------------------------------------------------------------
    */

    private function adicionarLinkOuVideo(
        int $conteudoId,
        int $professorId,
        string $url,
        ?string $titulo,
        string $tipo
    ): array {

        if (
            $conteudoId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        if (
            !in_array(
                $tipo,
                self::TIPOS_RECURSO,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Tipo de recurso inválido."
            );

        }


        $url =
            trim(
                $url
            );


        $titulo =
            $titulo !== null
                ?
                $this->normalizarTextoCurto(
                    $titulo
                )
                :
                null;


        if (
            $url === ""
            ||
            filter_var(
                $url,
                FILTER_VALIDATE_URL
            )
            === false
        ) {

            return $this->resposta(
                false,
                $tipo === "video"
                    ?
                    "Informe uma URL de vídeo válida."
                    :
                    "Informe uma URL válida."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SOMENTE HTTP / HTTPS
        |--------------------------------------------------------------------------
        */

        $esquema =
            strtolower(
                (string) parse_url(
                    $url,
                    PHP_URL_SCHEME
                )
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

            return $this->resposta(
                false,
                "A URL deve utilizar HTTP ou HTTPS."
            );

        }


        $conteudo =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        $sala =
            $this->buscarSalaDoConteudo(
                $conteudo
            );


        if (
            !is_array(
                $sala
            )
            ||
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para adicionar recursos neste conteúdo."
            );

        }


        $recursoId =
            $this->salaConteudoLink->criar(
                $conteudoId,
                $url,
                $titulo !== ""
                    ?
                    $titulo
                    :
                    null,
                $tipo
            );


        $recursoId =
            (int) $recursoId;


        if (
            $recursoId <= 0
        ) {

            return $this->resposta(
                false,
                $tipo === "video"
                    ?
                    "Não foi possível adicionar o vídeo."
                    :
                    "Não foi possível adicionar o link."
            );

        }


        return $this->resposta(
            true,
            $tipo === "video"
                ?
                "Vídeo adicionado com sucesso."
                :
                "Link adicionado com sucesso.",
            [
                "recurso_id" =>
                    $recursoId
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER LINK
    |--------------------------------------------------------------------------
    */

    public function removerLink(
        int $linkId,
        int $professorId
    ): array {

        return $this->removerLinkOuVideo(
            $linkId,
            $professorId,
            "link"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER VÍDEO
    |--------------------------------------------------------------------------
    */

    public function removerVideo(
        int $videoId,
        int $professorId
    ): array {

        return $this->removerLinkOuVideo(
            $videoId,
            $professorId,
            "video"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER LINK OU VÍDEO
    |--------------------------------------------------------------------------
    */

    private function removerLinkOuVideo(
        int $recursoId,
        int $professorId,
        string $tipoEsperado
    ): array {

        if (
            $recursoId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        if (
            !in_array(
                $tipoEsperado,
                self::TIPOS_RECURSO,
                true
            )
        ) {

            return $this->resposta(
                false,
                "Tipo de recurso inválido."
            );

        }


        $recurso =
            $this->salaConteudoLink->buscarPorId(
                $recursoId
            );


        if (
            !is_array(
                $recurso
            )
        ) {

            return $this->resposta(
                false,
                "Recurso não encontrado."
            );

        }


        $tipoAtual =
            strtolower(
                trim(
                    (string) (
                        $recurso["tipo"]
                        ??
                        ""
                    )
                )
            );


        if (
            $tipoAtual !==
            $tipoEsperado
        ) {

            return $this->resposta(
                false,
                "Tipo de recurso inválido."
            );

        }


        $conteudoId =
            (int) (
                $recurso["conteudo_id"]
                ??
                0
            );


        if (
            $conteudoId <= 0
        ) {

            return $this->resposta(
                false,
                "Conteúdo inválido."
            );

        }


        $conteudo =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        $sala =
            $this->buscarSalaDoConteudo(
                $conteudo
            );


        if (
            !is_array(
                $sala
            )
            ||
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para remover este recurso."
            );

        }


        if (
            !$this->salaConteudoLink->excluir(
                $recursoId
            )
        ) {

            return $this->resposta(
                false,
                "Não foi possível remover o recurso."
            );

        }


        return $this->resposta(
            true,
            $tipoEsperado === "video"
                ?
                "Vídeo removido com sucesso."
                :
                "Link removido com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR ANEXOS PARA PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function listarAnexosProfessor(
        int $conteudoId,
        int $professorId
    ): array {

        if (
            $conteudoId <= 0
            ||
            $professorId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        $conteudo =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        $sala =
            $this->buscarSalaDoConteudo(
                $conteudo
            );


        if (
            !is_array(
                $sala
            )
            ||
            !$this->professorEhDono(
                $sala,
                $professorId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui permissão para visualizar estes anexos."
            );

        }


        return $this->resposta(
            true,
            "Anexos carregados.",
            [
                "anexos" =>
                    $this->montarAnexosConteudo(
                        $conteudoId
                    )
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR ANEXOS PARA ALUNO
    |--------------------------------------------------------------------------
    */

    public function listarAnexosAluno(
        int $conteudoId,
        int $alunoId
    ): array {

        if (
            $conteudoId <= 0
            ||
            $alunoId <= 0
        ) {

            return $this->resposta(
                false,
                "Dados inválidos."
            );

        }


        $conteudo =
            $this->salaConteudo->buscarPorId(
                $conteudoId
            );


        if (
            !is_array(
                $conteudo
            )
        ) {

            return $this->resposta(
                false,
                "Conteúdo não encontrado."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ESTUDANTE SÓ ACESSA CONTEÚDO PUBLICADO
        |--------------------------------------------------------------------------
        */

        if (
            (
                $conteudo["status"]
                ??
                ""
            )
            !== "publicado"
        ) {

            return $this->resposta(
                false,
                "Este conteúdo não está disponível."
            );

        }


        $salaId =
            (int) (
                $conteudo["sala_id"]
                ??
                0
            );


        if (
            $salaId <= 0
            ||
            !$this->salaAluno->existe(
                $salaId,
                $alunoId
            )
        ) {

            return $this->resposta(
                false,
                "Você não possui acesso aos anexos deste conteúdo."
            );

        }


        return $this->resposta(
            true,
            "Anexos carregados.",
            [
                "anexos" =>
                    $this->montarAnexosConteudo(
                        $conteudoId
                    )
            ]
        );

    }

}