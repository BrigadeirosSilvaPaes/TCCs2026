<?php

require_once __DIR__ . "/../Models/Material.php";
require_once __DIR__ . "/../Models/Aula.php";

class MaterialController
{
    private $material;
    private $aula;

    private $tamanhoMaximo = 20 * 1024 * 1024;

    private $extensoesPermitidas = [
        "pdf",
        "doc",
        "docx",
        "ppt",
        "pptx",
        "jpg",
        "jpeg",
        "png",
        "webp",
        "txt",
        "zip"
    ];

    private $mimesPermitidos = [
        "application/pdf",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "application/vnd.ms-powerpoint",
        "application/vnd.openxmlformats-officedocument.presentationml.presentation",
        "image/jpeg",
        "image/png",
        "image/webp",
        "text/plain",
        "application/zip",
        "application/x-zip-compressed"
    ];

    public function __construct()
    {
        global $pdo;

        $this->material = new Material($pdo);
        $this->aula = new Aula($pdo);
    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR MATERIAL
    |--------------------------------------------------------------------------
    */

    public function criar()
    {
        $this->iniciarSessao();

        if (!$this->professorAutorizado()) {
            header("Location: ../public/login.php");
            exit;
        }

        $professorId = $_SESSION["usuario"]["id"];

        $titulo = trim($_POST["titulo"] ?? "");
        $descricao = trim($_POST["descricao"] ?? "");
        $tipo = trim($_POST["tipo"] ?? "");
        $fonte = trim($_POST["fonte"] ?? "");
        $url = trim($_POST["url"] ?? "");

        $aulaId = filter_input(
            INPUT_POST,
            "aula_id",
            FILTER_VALIDATE_INT
        );

        if (!$aulaId) {
            $aulaId = null;
        }

        if (
            empty($titulo) ||
            empty($tipo) ||
            empty($fonte)
        ) {
            $this->redirecionar(
                "materiais.php?status=empty"
            );
        }

        $tiposPermitidos = [
            "video",
            "pdf",
            "documento",
            "imagem",
            "apresentacao",
            "arquivo",
            "link"
        ];

        if (!in_array(
            $tipo,
            $tiposPermitidos,
            true
        )) {
            $this->redirecionar(
                "materiais.php?status=invalid"
            );
        }

        if (
            $aulaId !== null &&
            !$this->aula->pertenceAoProfessor(
                $aulaId,
                $professorId
            )
        ) {
            $this->redirecionar(
                "materiais.php?status=invalid"
            );
        }

        $caminho = null;
        $nomeOriginal = null;


        /*
        |--------------------------------------------------------------------------
        | LINK EXTERNO
        |--------------------------------------------------------------------------
        */

        if ($fonte === "link") {

            if (
                empty($url) ||
                !filter_var(
                    $url,
                    FILTER_VALIDATE_URL
                )
            ) {
                $this->redirecionar(
                    "materiais.php?status=url"
                );
            }

        /*
        |--------------------------------------------------------------------------
        | UPLOAD
        |--------------------------------------------------------------------------
        */

        } elseif ($fonte === "arquivo") {

            if (
                !isset($_FILES["arquivo"]) ||
                $_FILES["arquivo"]["error"] !== UPLOAD_ERR_OK
            ) {
                $this->redirecionar(
                    "materiais.php?status=file"
                );
            }

            $upload = $this->processarUpload(
                $_FILES["arquivo"]
            );

            if (!$upload["sucesso"]) {

                $this->redirecionar(
                    "materiais.php?status="
                    . urlencode(
                        $upload["erro"]
                    )
                );
            }

            $caminho = $upload["caminho"];
            $nomeOriginal = $upload["nome_original"];

            $url = null;

        } else {

            $this->redirecionar(
                "materiais.php?status=invalid"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SALVAR NO BANCO
        |--------------------------------------------------------------------------
        */

        $criado = $this->material->criar(
            $professorId,
            $aulaId,
            $titulo,
            $descricao ?: null,
            $tipo,
            $caminho,
            $url ?: null,
            $nomeOriginal
        );

        if (!$criado) {

            if ($caminho) {
                $this->excluirArquivoFisico(
                    $caminho
                );
            }

            $this->redirecionar(
                "materiais.php?status=error"
            );
        }

        $this->redirecionar(
            "materiais.php?status=created"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR MATERIAL
    |--------------------------------------------------------------------------
    */

    public function atualizar()
    {
        $this->iniciarSessao();

        if (!$this->professorAutorizado()) {
            header("Location: ../public/login.php");
            exit;
        }

        $professorId = $_SESSION["usuario"]["id"];

        $id = filter_input(
            INPUT_POST,
            "id",
            FILTER_VALIDATE_INT
        );

        if (!$id) {
            $this->redirecionar(
                "materiais.php?status=invalid"
            );
        }

        $materialAtual =
            $this->material->buscarPorId($id);

        if (
            !$materialAtual ||
            (int) $materialAtual["professor_id"] !==
            (int) $professorId
        ) {
            $this->redirecionar(
                "materiais.php?status=invalid"
            );
        }

        $titulo = trim($_POST["titulo"] ?? "");
        $descricao = trim($_POST["descricao"] ?? "");
        $tipo = trim($_POST["tipo"] ?? "");
        $fonte = trim($_POST["fonte"] ?? "");
        $url = trim($_POST["url"] ?? "");

        $aulaId = filter_input(
            INPUT_POST,
            "aula_id",
            FILTER_VALIDATE_INT
        );

        if (!$aulaId) {
            $aulaId = null;
        }

        if (empty($titulo)) {
            $this->redirecionar(
                "editar-material.php?id={$id}&status=empty"
            );
        }

        if (
            $aulaId !== null &&
            !$this->aula->pertenceAoProfessor(
                $aulaId,
                $professorId
            )
        ) {
            $this->redirecionar(
                "editar-material.php?id={$id}&status=invalid"
            );
        }

        $caminho =
            $materialAtual["caminho"];

        $nomeOriginal =
            $materialAtual["nome_original"];


        /*
        |--------------------------------------------------------------------------
        | ALTERAR PARA LINK
        |--------------------------------------------------------------------------
        */

        if ($fonte === "link") {

            if (
                empty($url) ||
                !filter_var(
                    $url,
                    FILTER_VALIDATE_URL
                )
            ) {
                $this->redirecionar(
                    "editar-material.php?id={$id}&status=url"
                );
            }

            if (!empty($caminho)) {

                $this->excluirArquivoFisico(
                    $caminho
                );

                $caminho = null;
                $nomeOriginal = null;
            }

        /*
        |--------------------------------------------------------------------------
        | ARQUIVO
        |--------------------------------------------------------------------------
        */

        } elseif ($fonte === "arquivo") {

            $url = null;

            if (
                isset($_FILES["arquivo"]) &&
                $_FILES["arquivo"]["error"] !== UPLOAD_ERR_NO_FILE
            ) {

                if (
                    $_FILES["arquivo"]["error"] !== UPLOAD_ERR_OK
                ) {
                    $this->redirecionar(
                        "editar-material.php?id={$id}&status=file"
                    );
                }

                $upload = $this->processarUpload(
                    $_FILES["arquivo"]
                );

                if (!$upload["sucesso"]) {
                    $this->redirecionar(
                        "editar-material.php?id={$id}&status="
                        . urlencode(
                            $upload["erro"]
                        )
                    );
                }

                $novoCaminho =
                    $upload["caminho"];

                $novoNome =
                    $upload["nome_original"];

                if (!empty($caminho)) {
                    $this->excluirArquivoFisico(
                        $caminho
                    );
                }

                $caminho = $novoCaminho;
                $nomeOriginal = $novoNome;
            }

        } else {

            $this->redirecionar(
                "editar-material.php?id={$id}&status=invalid"
            );
        }


        $atualizado =
            $this->material->atualizar(
                $id,
                $professorId,
                $aulaId,
                $titulo,
                $descricao ?: null,
                $tipo,
                $caminho,
                $url ?: null,
                $nomeOriginal
            );

        if (!$atualizado) {
            $this->redirecionar(
                "editar-material.php?id={$id}&status=error"
            );
        }

        $this->redirecionar(
            "materiais.php?status=updated"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR MATERIAL
    |--------------------------------------------------------------------------
    */

    public function excluir()
    {
        $this->iniciarSessao();

        if (!$this->professorAutorizado()) {
            header("Location: ../public/login.php");
            exit;
        }

        $professorId =
            $_SESSION["usuario"]["id"];

        $id = filter_input(
            INPUT_POST,
            "id",
            FILTER_VALIDATE_INT
        );

        if (!$id) {
            $this->redirecionar(
                "materiais.php?status=invalid"
            );
        }

        $material =
            $this->material->buscarPorId($id);

        if (
            !$material ||
            (int) $material["professor_id"] !==
            (int) $professorId
        ) {
            $this->redirecionar(
                "materiais.php?status=invalid"
            );
        }

        $excluido =
            $this->material->excluirDoProfessor(
                $id,
                $professorId
            );

        if (!$excluido) {
            $this->redirecionar(
                "materiais.php?status=error"
            );
        }

        if (!empty($material["caminho"])) {
            $this->excluirArquivoFisico(
                $material["caminho"]
            );
        }

        $this->redirecionar(
            "materiais.php?status=deleted"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROCESSAR UPLOAD
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | CRIAR ARQUIVO VINCULADO A UMA AULA
    |--------------------------------------------------------------------------
    |
    | Utilizado quando o professor anexa um arquivo diretamente
    | durante a criação de uma aula.
    |
    */
    public function criarArquivoVinculadoAAula(
        $professorId,
        $aulaId,
        $arquivo
    ) {
        $professorId = (int) $professorId;
        $aulaId = (int) $aulaId;

        if (
            $professorId <= 0 ||
            $aulaId <= 0 ||
            !is_array($arquivo)
        ) {
            return [
                "sucesso" => false,
                "erro" => "invalid"
            ];
        }

        $erroUpload =
            (int) (
                $arquivo["error"]
                ?? UPLOAD_ERR_NO_FILE
            );

        if ($erroUpload !== UPLOAD_ERR_OK) {
            return [
                "sucesso" => false,
                "erro" => "file"
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | UTILIZA O MESMO PROCESSAMENTO DE UPLOAD DOS MATERIAIS
        |--------------------------------------------------------------------------
        */

        $upload =
            $this->processarUpload(
                $arquivo
            );

        if (!$upload["sucesso"]) {
            return $upload;
        }

        $caminho =
            $upload["caminho"];

        $nomeOriginal =
            $upload["nome_original"];

        /*
        |--------------------------------------------------------------------------
        | IDENTIFICAR TIPO DO MATERIAL
        |--------------------------------------------------------------------------
        */

        $extensao =
            strtolower(
                pathinfo(
                    $nomeOriginal,
                    PATHINFO_EXTENSION
                )
            );

        $tipos = [
            "pdf" => "pdf",

            "doc" => "documento",
            "docx" => "documento",

            "ppt" => "apresentacao",
            "pptx" => "apresentacao",

            "jpg" => "imagem",
            "jpeg" => "imagem",
            "png" => "imagem",
            "webp" => "imagem",

            "txt" => "arquivo",
            "zip" => "arquivo"
        ];

        $tipo =
            $tipos[$extensao]
            ?? "arquivo";

        $tituloMaterial =
            trim($nomeOriginal) !== ""
                ? $nomeOriginal
                : "Material complementar";

        /*
        |--------------------------------------------------------------------------
        | SALVAR MATERIAL VINCULADO À AULA
        |--------------------------------------------------------------------------
        */

        $criado =
            $this->material->criar(
                $professorId,
                $aulaId,
                $tituloMaterial,
                null,
                $tipo,
                $caminho,
                null,
                $nomeOriginal
            );

        if (!$criado) {

            if (!empty($caminho)) {
                $this->excluirArquivoFisico(
                    $caminho
                );
            }

            return [
                "sucesso" => false,
                "erro" => "database"
            ];
        }

        return [
            "sucesso" => true,
            "tipo" => $tipo,
            "caminho" => $caminho,
            "nome_original" => $nomeOriginal
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | SUBSTITUIR ARQUIVO VINCULADO À AULA
    |--------------------------------------------------------------------------
    |
    | Se existir um arquivo vinculado à aula:
    |   - envia o novo
    |   - atualiza o registro
    |   - remove fisicamente o arquivo antigo
    |
    | Se não existir:
    |   - cria um novo material vinculado à aula
    |
    */

    public function substituirArquivoVinculadoAAula(
        $professorId,
        $aulaId,
        $arquivo
    ) {
        $professorId = (int) $professorId;
        $aulaId = (int) $aulaId;

        if (
            $professorId <= 0 ||
            $aulaId <= 0 ||
            !is_array($arquivo)
        ) {
            return [
                "sucesso" => false,
                "erro" => "invalid"
            ];
        }

        $erroUpload =
            (int) (
                $arquivo["error"]
                ?? UPLOAD_ERR_NO_FILE
            );

        if ($erroUpload !== UPLOAD_ERR_OK) {
            return [
                "sucesso" => false,
                "erro" => "file"
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PROCESSAR NOVO ARQUIVO
        |--------------------------------------------------------------------------
        */

        $upload =
            $this->processarUpload(
                $arquivo
            );

        if (!$upload["sucesso"]) {
            return $upload;
        }

        $novoCaminho =
            $upload["caminho"];

        $novoNome =
            $upload["nome_original"];

        /*
        |--------------------------------------------------------------------------
        | IDENTIFICAR TIPO
        |--------------------------------------------------------------------------
        */

        $extensao =
            strtolower(
                pathinfo(
                    $novoNome,
                    PATHINFO_EXTENSION
                )
            );

        $tipos = [
            "pdf" => "pdf",
            "doc" => "documento",
            "docx" => "documento",
            "ppt" => "apresentacao",
            "pptx" => "apresentacao",
            "jpg" => "imagem",
            "jpeg" => "imagem",
            "png" => "imagem",
            "webp" => "imagem",
            "txt" => "arquivo",
            "zip" => "arquivo"
        ];

        $tipo =
            $tipos[$extensao]
            ?? "arquivo";

        /*
        |--------------------------------------------------------------------------
        | PROCURAR ARQUIVO ATUAL DA AULA
        |--------------------------------------------------------------------------
        */

        $materiais =
            $this->material->listarPorAula(
                $aulaId
            );

        $materialAtual = null;

        foreach ($materiais as $material) {

            if (!empty($material["caminho"])) {

                $materialAtual =
                    $material;

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | NÃO EXISTE ARQUIVO ATUAL
        |--------------------------------------------------------------------------
        */

        if (!$materialAtual) {

            $criado =
                $this->material->criar(
                    $professorId,
                    $aulaId,
                    $novoNome,
                    null,
                    $tipo,
                    $novoCaminho,
                    null,
                    $novoNome
                );

            if (!$criado) {

                $this->excluirArquivoFisico(
                    $novoCaminho
                );

                return [
                    "sucesso" => false,
                    "erro" => "database"
                ];
            }

            return [
                "sucesso" => true,
                "acao" => "criado",
                "caminho" => $novoCaminho,
                "nome_original" => $novoNome
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | SUBSTITUIR ARQUIVO EXISTENTE
        |--------------------------------------------------------------------------
        */

        $caminhoAntigo =
            $materialAtual["caminho"]
            ?? null;

        $atualizado =
            $this->material->atualizar(
                (int) $materialAtual["id"],
                (int) $materialAtual["professor_id"],
                $aulaId,
                $novoNome,
                $materialAtual["descricao"] ?? null,
                $tipo,
                $novoCaminho,
                null,
                $novoNome
            );

        if (!$atualizado) {

            $this->excluirArquivoFisico(
                $novoCaminho
            );

            return [
                "sucesso" => false,
                "erro" => "database"
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | APAGAR ARQUIVO ANTIGO SOMENTE APÓS BANCO ATUALIZADO
        |--------------------------------------------------------------------------
        */

        if (
            !empty($caminhoAntigo) &&
            $caminhoAntigo !== $novoCaminho
        ) {
            $this->excluirArquivoFisico(
                $caminhoAntigo
            );
        }

        return [
            "sucesso" => true,
            "acao" => "substituido",
            "caminho" => $novoCaminho,
            "nome_original" => $novoNome
        ];
    }

    private function processarUpload($arquivo)
    {
        if (
            $arquivo["size"] <= 0 ||
            $arquivo["size"] >
            $this->tamanhoMaximo
        ) {
            return [
                "sucesso" => false,
                "erro" => "size"
            ];
        }

        $nomeOriginal =
            basename($arquivo["name"]);

        $extensao = strtolower(
            pathinfo(
                $nomeOriginal,
                PATHINFO_EXTENSION
            )
        );

        if (
            !in_array(
                $extensao,
                $this->extensoesPermitidas,
                true
            )
        ) {
            return [
                "sucesso" => false,
                "erro" => "extension"
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | MIME REAL
        |--------------------------------------------------------------------------
        */

        $finfo = new finfo(
            FILEINFO_MIME_TYPE
        );

        $mime = $finfo->file(
            $arquivo["tmp_name"]
        );

        if (
            !in_array(
                $mime,
                $this->mimesPermitidos,
                true
            )
        ) {
            return [
                "sucesso" => false,
                "erro" => "type"
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | PASTA
        |--------------------------------------------------------------------------
        */

        $pasta =
            __DIR__
            . "/../../public/uploads/materiais/";

        if (!is_dir($pasta)) {

            if (
                !mkdir(
                    $pasta,
                    0755,
                    true
                )
            ) {
                return [
                    "sucesso" => false,
                    "erro" => "folder"
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | NOME SEGURO
        |--------------------------------------------------------------------------
        */

        $nomeSeguro =
            bin2hex(
                random_bytes(16)
            )
            . "."
            . $extensao;

        $destino =
            $pasta
            . $nomeSeguro;


        if (
            !move_uploaded_file(
                $arquivo["tmp_name"],
                $destino
            )
        ) {
            return [
                "sucesso" => false,
                "erro" => "upload"
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | CAMINHO SALVO NO BANCO
        |--------------------------------------------------------------------------
        */

        $caminhoBanco =
            "uploads/materiais/"
            . $nomeSeguro;

        return [
            "sucesso" => true,
            "caminho" => $caminhoBanco,
            "nome_original" => $nomeOriginal
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR ARQUIVO FÍSICO
    |--------------------------------------------------------------------------
    */

    private function excluirArquivoFisico(
        $caminho
    ) {
        if (empty($caminho)) {
            return;
        }

        $arquivo =
            __DIR__
            . "/../../public/"
            . ltrim(
                $caminho,
                "/"
            );

        if (is_file($arquivo)) {
            unlink($arquivo);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PROFESSOR AUTORIZADO
    |--------------------------------------------------------------------------
    */

    private function professorAutorizado()
    {
        return (
            isset($_SESSION["usuario"]) &&
            $_SESSION["usuario"]["tipo"] ===
            "professor" &&
            $_SESSION["usuario"]["status"] ===
            "ativo"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SESSÃO
    |--------------------------------------------------------------------------
    */

    private function iniciarSessao()
    {
        if (
            session_status() ===
            PHP_SESSION_NONE
        ) {
            session_start();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REDIRECIONAR
    |--------------------------------------------------------------------------
    */

    private function redirecionar(
        $destino
    ) {
        header(
            "Location: ../public/professor/"
            . $destino
        );

        exit;
    }
}