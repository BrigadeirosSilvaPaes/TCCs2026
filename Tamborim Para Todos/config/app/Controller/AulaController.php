<?php

require_once __DIR__ . "/../Models/Aula.php";
require_once __DIR__ . "/MaterialController.php";

class AulaController
{
    private $aula;

    public function __construct()
    {
        global $pdo;

        $this->aula = new Aula($pdo);
    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR AULA
    |--------------------------------------------------------------------------
    */

    public function criar()
    {
        $this->iniciarSessao();

        if (!$this->podeGerenciarAulas()) {
            $this->enviarParaLogin();
        }

        $usuario = $_SESSION["usuario"];

        $titulo = trim($_POST["titulo"] ?? "");
        $descricao = trim($_POST["descricao"] ?? "");
        $conteudo = trim($_POST["conteudo"] ?? "");
        $videoUrl = trim($_POST["video_url"] ?? "");
        $materialUrl = trim($_POST["material_url"] ?? "");
        $status = $_POST["status"] ?? "rascunho";


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            $titulo === "" ||
            $descricao === ""
        ) {
            $this->redirecionar(
                "criar-aula.php?status=empty"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $status,
                ["rascunho", "publicada"],
                true
            )
        ) {
            $status = "rascunho";
        }


        /*
        |--------------------------------------------------------------------------
        | ORDEM
        |--------------------------------------------------------------------------
        */

        $ordem = $this->aula->proximaOrdem();


        /*
        |--------------------------------------------------------------------------
        | CRIA A AULA
        |--------------------------------------------------------------------------
        */

        $resultado = $this->aula->criar(
            $usuario["id"],
            $titulo,
            $descricao,
            $conteudo !== "" ? $conteudo : null,
            $videoUrl !== "" ? $videoUrl : null,
            $materialUrl !== "" ? $materialUrl : null,
            $ordem,
            $status
        );


        if (!$resultado) {
            $this->redirecionar(
                "criar-aula.php?status=error"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ARQUIVO COMPLEMENTAR DA AULA
        |--------------------------------------------------------------------------
        */

        $aulaId =
            (int) $resultado;

        $arquivoEnviado =
            isset($_FILES["arquivo_aula"]) &&
            is_array($_FILES["arquivo_aula"]) &&
            (
                (int) (
                    $_FILES["arquivo_aula"]["error"]
                    ?? UPLOAD_ERR_NO_FILE
                )
                !== UPLOAD_ERR_NO_FILE
            );

        if ($arquivoEnviado) {

            $materialController =
                new MaterialController();

            $resultadoArquivo =
                $materialController
                    ->criarArquivoVinculadoAAula(
                        $usuario["id"],
                        $aulaId,
                        $_FILES["arquivo_aula"]
                    );

            if (
                !(
                    $resultadoArquivo["sucesso"]
                    ?? false
                )
            ) {

                $erroArquivo =
                    $resultadoArquivo["erro"]
                    ?? "file";

                $this->redirecionar(
                    "minhas-aulas.php?status=created_material_"
                    . urlencode($erroArquivo)
                );
            }
        }

        $this->redirecionar(
            "minhas-aulas.php?status=created"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR AULA
    |--------------------------------------------------------------------------
    */

    public function atualizar()
    {
        $this->iniciarSessao();

        if (!$this->podeGerenciarAulas()) {
            $this->enviarParaLogin();
        }

        $usuario = $_SESSION["usuario"];

        $id = filter_input(
            INPUT_POST,
            "id",
            FILTER_VALIDATE_INT
        );


        if (!$id) {
            $this->redirecionar(
                "minhas-aulas.php?status=invalid"
            );
        }


        $titulo = trim($_POST["titulo"] ?? "");
        $descricao = trim($_POST["descricao"] ?? "");
        $conteudo = trim($_POST["conteudo"] ?? "");
        $videoUrl = trim($_POST["video_url"] ?? "");
        $materialUrl = trim($_POST["material_url"] ?? "");

        $ordem = filter_input(
            INPUT_POST,
            "ordem",
            FILTER_VALIDATE_INT
        );

        $status = $_POST["status"] ?? "rascunho";


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            $titulo === "" ||
            $descricao === ""
        ) {
            $this->redirecionar(
                "editar-aula.php?id={$id}&status=empty"
            );
        }


        if (!$ordem || $ordem < 1) {
            $ordem = 1;
        }


        if (
            !in_array(
                $status,
                ["rascunho", "publicada"],
                true
            )
        ) {
            $status = "rascunho";
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN PODE EDITAR QUALQUER AULA
        |--------------------------------------------------------------------------
        */

        if ($usuario["tipo"] === "admin") {

            $resultado = $this->aula->atualizar(
                $id,
                $titulo,
                $descricao,
                $conteudo !== "" ? $conteudo : null,
                $videoUrl !== "" ? $videoUrl : null,
                $materialUrl !== "" ? $materialUrl : null,
                $ordem,
                $status
            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | PROFESSOR SÓ EDITA A PRÓPRIA AULA
            |--------------------------------------------------------------------------
            */

            $resultado =
                $this->aula->atualizarDoProfessor(
                    $id,
                    $usuario["id"],
                    $titulo,
                    $descricao,
                    $conteudo !== "" ? $conteudo : null,
                    $videoUrl !== "" ? $videoUrl : null,
                    $materialUrl !== "" ? $materialUrl : null,
                    $ordem,
                    $status
                );
        }


        if (!$resultado) {
            $this->redirecionar(
                "editar-aula.php?id={$id}&status=error"
            );
        }


                /*
        |--------------------------------------------------------------------------
        | SUBSTITUIR ARQUIVO COMPLEMENTAR
        |--------------------------------------------------------------------------
        */

        if (
            isset($_FILES["arquivo_aula"]) &&
            is_array($_FILES["arquivo_aula"]) &&
            (
                (int) (
                    $_FILES["arquivo_aula"]["error"]
                    ?? UPLOAD_ERR_NO_FILE
                )
                !== UPLOAD_ERR_NO_FILE
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | RESPONSÁVEL ORIGINAL DA AULA
            |--------------------------------------------------------------------------
            */

            $aulaAtual =
                $this->aula->buscarPorId(
                    $id
                );

            $responsavelArquivoId =
                (int) (
                    $aulaAtual["professor_id"]
                    ?? $usuario["id"]
                );

            $materialController =
                new MaterialController();

            $resultadoArquivo =
                $materialController
                    ->substituirArquivoVinculadoAAula(
                        $responsavelArquivoId,
                        $id,
                        $_FILES["arquivo_aula"]
                    );

            if (!$resultadoArquivo["sucesso"]) {

                $erroArquivo =
                    urlencode(
                        $resultadoArquivo["erro"]
                        ?? "file"
                    );

                $this->redirecionar(
                    "editar-aula.php?id={$id}&status=arquivo_{$erroArquivo}"
                );
            }
        }

$this->redirecionar(
            "minhas-aulas.php?status=updated"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLICAR AULA
    |--------------------------------------------------------------------------
    */

    public function publicar()
    {
        $this->iniciarSessao();

        if (!$this->podeGerenciarAulas()) {
            $this->enviarParaLogin();
        }

        $usuario = $_SESSION["usuario"];

        $id = filter_input(
            INPUT_POST,
            "id",
            FILTER_VALIDATE_INT
        );


        if (!$id) {
            $this->redirecionar(
                "minhas-aulas.php?status=invalid"
            );
        }


        if ($usuario["tipo"] === "admin") {

            $resultado =
                $this->aula->publicar($id);

        } else {

            $resultado =
                $this->aula->publicarDoProfessor(
                    $id,
                    $usuario["id"]
                );
        }


        if (!$resultado) {
            $this->redirecionar(
                "minhas-aulas.php?status=error"
            );
        }


        $this->redirecionar(
            "minhas-aulas.php?status=published"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TORNAR RASCUNHO
    |--------------------------------------------------------------------------
    */

    public function tornarRascunho()
    {
        $this->iniciarSessao();

        if (!$this->podeGerenciarAulas()) {
            $this->enviarParaLogin();
        }

        $usuario = $_SESSION["usuario"];

        $id = filter_input(
            INPUT_POST,
            "id",
            FILTER_VALIDATE_INT
        );


        if (!$id) {
            $this->redirecionar(
                "minhas-aulas.php?status=invalid"
            );
        }


        if ($usuario["tipo"] === "admin") {

            $resultado =
                $this->aula->tornarRascunho($id);

        } else {

            $resultado =
                $this->aula->tornarRascunhoDoProfessor(
                    $id,
                    $usuario["id"]
                );
        }


        if (!$resultado) {
            $this->redirecionar(
                "minhas-aulas.php?status=error"
            );
        }


        $this->redirecionar(
            "minhas-aulas.php?status=draft"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR AULA
    |--------------------------------------------------------------------------
    */

    public function excluir()
    {
        $this->iniciarSessao();

        if (!$this->podeGerenciarAulas()) {
            $this->enviarParaLogin();
        }

        $usuario = $_SESSION["usuario"];

        $id = filter_input(
            INPUT_POST,
            "id",
            FILTER_VALIDATE_INT
        );


        if (!$id) {
            $this->redirecionar(
                "minhas-aulas.php?status=invalid"
            );
        }


        if ($usuario["tipo"] === "admin") {

            $resultado =
                $this->aula->excluir($id);

        } else {

            $resultado =
                $this->aula->excluirDoProfessor(
                    $id,
                    $usuario["id"]
                );
        }


        if (!$resultado) {
            $this->redirecionar(
                "minhas-aulas.php?status=error"
            );
        }


        $this->redirecionar(
            "minhas-aulas.php?status=deleted"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR AULA PARA EDIÇÃO
    |--------------------------------------------------------------------------
    |
    | Admin:
    | pode acessar qualquer aula.
    |
    | Professor:
    | somente as próprias aulas.
    |
    |--------------------------------------------------------------------------
    */

    public function buscarParaEdicao($id)
    {
        $this->iniciarSessao();

        if (!$this->podeGerenciarAulas()) {
            return false;
        }


        $usuario = $_SESSION["usuario"];

        $aula = $this->aula->buscarPorId($id);


        if (!$aula) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($usuario["tipo"] === "admin") {
            return $aula;
        }


        /*
        |--------------------------------------------------------------------------
        | PROFESSOR
        |--------------------------------------------------------------------------
        */

        if (
            $usuario["tipo"] === "professor" &&
            (int) $aula["professor_id"] ===
            (int) $usuario["id"]
        ) {
            return $aula;
        }


        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR AULAS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function listarDoProfessor($professorId)
    {
        return $this->aula->listarPorProfessor(
            $professorId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ESTATÍSTICAS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function estatisticasProfessor($professorId)
    {
        return [

            "total" =>
                $this->aula->contarPorProfessor(
                    $professorId
                ),

            "publicadas" =>
                $this->aula->contarPublicadasPorProfessor(
                    $professorId
                ),

            "rascunhos" =>
                $this->aula->contarRascunhosPorProfessor(
                    $professorId
                )
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR AULAS PUBLICADAS
    |--------------------------------------------------------------------------
    */

    public function listarPublicadas()
    {
        return $this->aula->listarPublicadas();
    }


    /*
    |--------------------------------------------------------------------------
    | INICIAR SESSÃO
    |--------------------------------------------------------------------------
    */

    private function iniciarSessao()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR PERMISSÃO
    |--------------------------------------------------------------------------
    */

    private function podeGerenciarAulas()
    {
        if (!isset($_SESSION["usuario"])) {
            return false;
        }

        return in_array(
            $_SESSION["usuario"]["tipo"],
            [
                "professor",
                "admin"
            ],
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ENVIAR PARA LOGIN
    |--------------------------------------------------------------------------
    */

    private function enviarParaLogin()
    {
        header(
            "Location: ../public/login.php"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | REDIRECIONAMENTO
    |--------------------------------------------------------------------------
    |
    | PROFESSOR
    |
    | minhas-aulas.php
    | criar-aula.php
    | editar-aula.php
    |
    | ADMIN
    |
    | operações terminam em:
    |
    | public/admin/aulas.php
    |
    |--------------------------------------------------------------------------
    */

    private function redirecionar($destino)
    {
        $tipo =
            $_SESSION["usuario"]["tipo"]
            ?? "";


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($tipo === "admin") {

            /*
            | Extrai somente a query string.
            |
            | Exemplo:
            |
            | minhas-aulas.php?status=published
            |
            | resultado:
            |
            | status=published
            */

            $query = "";

            $posicao =
                strpos(
                    $destino,
                    "?"
                );


            if ($posicao !== false) {

                $query =
                    substr(
                        $destino,
                        $posicao + 1
                    );
            }


            $url =
                "../public/admin/aulas.php";


            if ($query !== "") {

                $url .=
                    "?" .
                    $query;
            }


            header(
                "Location: " . $url
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | PROFESSOR
        |--------------------------------------------------------------------------
        */

        header(
            "Location: ../public/professor/" .
            $destino
        );

        exit;
    }
}