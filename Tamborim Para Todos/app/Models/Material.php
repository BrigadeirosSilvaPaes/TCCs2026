<?php

class Material
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /*
    |--------------------------------------------------------------------------
    | CRIAR MATERIAL
    |--------------------------------------------------------------------------
    */

    public function criar(
        $professorId,
        $aulaId,
        $titulo,
        $descricao,
        $tipo,
        $caminho = null,
        $url = null,
        $nomeOriginal = null
    ) {
        $sql = "INSERT INTO materiais (
                    professor_id,
                    aula_id,
                    titulo,
                    descricao,
                    tipo,
                    caminho,
                    url,
                    nome_original
                )
                VALUES (
                    :professor_id,
                    :aula_id,
                    :titulo,
                    :descricao,
                    :tipo,
                    :caminho,
                    :url,
                    :nome_original
                )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":professor_id" => $professorId,
            ":aula_id" => $aulaId,
            ":titulo" => $titulo,
            ":descricao" => $descricao,
            ":tipo" => $tipo,
            ":caminho" => $caminho,
            ":url" => $url,
            ":nome_original" => $nomeOriginal
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR POR ID
    |--------------------------------------------------------------------------
    */

    public function buscarPorId($id)
    {
        $sql = "SELECT
                    materiais.*,
                    usuarios.nome AS professor_nome,
                    aulas.titulo AS aula_titulo

                FROM materiais

                INNER JOIN usuarios
                    ON usuarios.id = materiais.professor_id

                LEFT JOIN aulas
                    ON aulas.id = materiais.aula_id

                WHERE materiais.id = :id

                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR MATERIAIS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function listarPorProfessor($professorId)
    {
        $sql = "SELECT
                    materiais.*,
                    aulas.titulo AS aula_titulo

                FROM materiais

                LEFT JOIN aulas
                    ON aulas.id = materiais.aula_id

                WHERE materiais.professor_id = :professor_id

                ORDER BY materiais.criado_em DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":professor_id" => $professorId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR MATERIAIS DE UMA AULA
    |--------------------------------------------------------------------------
    */

    public function listarPorAula($aulaId)
    {
        $sql = "SELECT
                    materiais.*,
                    usuarios.nome AS professor_nome

                FROM materiais

                INNER JOIN usuarios
                    ON usuarios.id = materiais.professor_id

                WHERE materiais.aula_id = :aula_id

                ORDER BY materiais.criado_em ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":aula_id" => $aulaId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR TODOS
    | ADMIN
    |--------------------------------------------------------------------------
    */

    public function listarTodos()
    {
        $sql = "SELECT
                    materiais.*,
                    usuarios.nome AS professor_nome,
                    aulas.titulo AS aula_titulo

                FROM materiais

                INNER JOIN usuarios
                    ON usuarios.id = materiais.professor_id

                LEFT JOIN aulas
                    ON aulas.id = materiais.aula_id

                ORDER BY materiais.criado_em DESC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR MATERIAL
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        $id,
        $professorId,
        $aulaId,
        $titulo,
        $descricao,
        $tipo,
        $caminho,
        $url,
        $nomeOriginal
    ) {
        $sql = "UPDATE materiais

                SET aula_id = :aula_id,
                    titulo = :titulo,
                    descricao = :descricao,
                    tipo = :tipo,
                    caminho = :caminho,
                    url = :url,
                    nome_original = :nome_original

                WHERE id = :id
                AND professor_id = :professor_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":aula_id" => $aulaId,
            ":titulo" => $titulo,
            ":descricao" => $descricao,
            ":tipo" => $tipo,
            ":caminho" => $caminho,
            ":url" => $url,
            ":nome_original" => $nomeOriginal,
            ":id" => $id,
            ":professor_id" => $professorId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR MATERIAL DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function excluirDoProfessor(
        $id,
        $professorId
    ) {
        $sql = "DELETE FROM materiais

                WHERE id = :id
                AND professor_id = :professor_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id,
            ":professor_id" => $professorId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR
    | ADMIN
    |--------------------------------------------------------------------------
    */

    public function excluir($id)
    {
        $sql = "DELETE FROM materiais
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR PROPRIEDADE
    |--------------------------------------------------------------------------
    */

    public function pertenceAoProfessor(
        $materialId,
        $professorId
    ) {
        $sql = "SELECT id
                FROM materiais

                WHERE id = :id
                AND professor_id = :professor_id

                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $materialId,
            ":professor_id" => $professorId
        ]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR MATERIAIS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function contarPorProfessor($professorId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM materiais

                WHERE professor_id = :professor_id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":professor_id" => $professorId
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado["total"];
    }


    /*
    |--------------------------------------------------------------------------
    | PESQUISAR
    |--------------------------------------------------------------------------
    */

    public function pesquisarPorProfessor(
        $professorId,
        $termo
    ) {
        $sql = "SELECT
                    materiais.*,
                    aulas.titulo AS aula_titulo

                FROM materiais

                LEFT JOIN aulas
                    ON aulas.id = materiais.aula_id

                WHERE materiais.professor_id = :professor_id

                AND (
                    materiais.titulo LIKE :termo
                    OR materiais.descricao LIKE :termo
                    OR materiais.nome_original LIKE :termo
                )

                ORDER BY materiais.criado_em DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":professor_id" => $professorId,
            ":termo" => "%" . $termo . "%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}