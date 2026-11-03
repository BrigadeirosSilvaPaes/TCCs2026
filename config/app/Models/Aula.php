<?php

class Aula
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    /*
    |--------------------------------------------------------------------------
    | CRIAR AULA
    |--------------------------------------------------------------------------
    */

    public function criar(
        $professorId,
        $titulo,
        $descricao,
        $conteudo = null,
        $videoUrl = null,
        $materialUrl = null,
        $ordem = 1,
        $status = "rascunho"
    ) {

        $sql = "INSERT INTO aulas (
                    professor_id,
                    titulo,
                    descricao,
                    conteudo,
                    video_url,
                    material_url,
                    ordem,
                    status
                )
                VALUES (
                    :professor_id,
                    :titulo,
                    :descricao,
                    :conteudo,
                    :video_url,
                    :material_url,
                    :ordem,
                    :status
                )";

        $stmt = $this->pdo->prepare($sql);

        $executado = $stmt->execute([

            ":professor_id" => $professorId,
            ":titulo" => $titulo,
            ":descricao" => $descricao,
            ":conteudo" => $conteudo,
            ":video_url" => $videoUrl,
            ":material_url" => $materialUrl,
            ":ordem" => $ordem,
            ":status" => $status
        
        ]);

        if (!$executado) {
            return false;
        }

        return (int) $this->pdo->lastInsertId();
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR AULA PELO ID
    |--------------------------------------------------------------------------
    */

    public function buscarPorId($id)
    {
        $sql = "SELECT aulas.*, usuarios.nome AS professor_nome
                FROM aulas

                INNER JOIN usuarios
                    ON usuarios.id = aulas.professor_id

                WHERE aulas.id = :id

                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR TODAS AS AULAS
    | ADMIN
    |--------------------------------------------------------------------------
    */

    public function listarTodas()
    {
        $sql = "SELECT aulas.*, usuarios.nome AS professor_nome
                FROM aulas

                INNER JOIN usuarios
                    ON usuarios.id = aulas.professor_id

                ORDER BY aulas.ordem ASC,
                         aulas.criado_em DESC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR AULAS DE UM PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function listarPorProfessor($professorId)
    {
        $sql = "SELECT *
                FROM aulas

                WHERE professor_id = :professor_id

                ORDER BY ordem ASC,
                         criado_em DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":professor_id" => $professorId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR AULAS PUBLICADAS
    | ALUNO
    |--------------------------------------------------------------------------
    */

    public function listarPublicadas()
    {
        $sql = "SELECT aulas.*, usuarios.nome AS professor_nome
                FROM aulas

                INNER JOIN usuarios
                    ON usuarios.id = aulas.professor_id

                WHERE aulas.status = 'publicada'

                ORDER BY aulas.ordem ASC,
                         aulas.criado_em ASC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR RASCUNHOS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function listarRascunhos($professorId)
    {
        $sql = "SELECT *
                FROM aulas

                WHERE professor_id = :professor_id
                AND status = 'rascunho'

                ORDER BY atualizado_em DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":professor_id" => $professorId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR AULAS PUBLICADAS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function listarPublicadasPorProfessor($professorId)
    {
        $sql = "SELECT *
                FROM aulas

                WHERE professor_id = :professor_id
                AND status = 'publicada'

                ORDER BY ordem ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":professor_id" => $professorId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR AULA
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        $id,
        $titulo,
        $descricao,
        $conteudo,
        $videoUrl,
        $materialUrl,
        $ordem,
        $status
    ) {

        $sql = "UPDATE aulas

                SET titulo = :titulo,
                    descricao = :descricao,
                    conteudo = :conteudo,
                    video_url = :video_url,
                    material_url = :material_url,
                    ordem = :ordem,
                    status = :status

                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":titulo" => $titulo,
            ":descricao" => $descricao,
            ":conteudo" => $conteudo,
            ":video_url" => $videoUrl,
            ":material_url" => $materialUrl,
            ":ordem" => $ordem,
            ":status" => $status,
            ":id" => $id
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR AULA DO PROFESSOR
    | IMPEDE ALTERAR AULA DE OUTRO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function atualizarDoProfessor(
        $id,
        $professorId,
        $titulo,
        $descricao,
        $conteudo,
        $videoUrl,
        $materialUrl,
        $ordem,
        $status
    ) {

        $sql = "UPDATE aulas

                SET titulo = :titulo,
                    descricao = :descricao,
                    conteudo = :conteudo,
                    video_url = :video_url,
                    material_url = :material_url,
                    ordem = :ordem,
                    status = :status

                WHERE id = :id
                AND professor_id = :professor_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":titulo" => $titulo,
            ":descricao" => $descricao,
            ":conteudo" => $conteudo,
            ":video_url" => $videoUrl,
            ":material_url" => $materialUrl,
            ":ordem" => $ordem,
            ":status" => $status,
            ":id" => $id,
            ":professor_id" => $professorId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLICAR AULA
    |--------------------------------------------------------------------------
    */

    public function publicar($id)
    {
        $sql = "UPDATE aulas

                SET status = 'publicada'

                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLICAR AULA DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function publicarDoProfessor($id, $professorId)
    {
        $sql = "UPDATE aulas

                SET status = 'publicada'

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
    | TRANSFORMAR EM RASCUNHO
    |--------------------------------------------------------------------------
    */

    public function tornarRascunho($id)
    {
        $sql = "UPDATE aulas

                SET status = 'rascunho'

                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | TRANSFORMAR EM RASCUNHO
    | PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function tornarRascunhoDoProfessor($id, $professorId)
    {
        $sql = "UPDATE aulas

                SET status = 'rascunho'

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
    | EXCLUIR AULA
    | ADMIN
    |--------------------------------------------------------------------------
    */

    public function excluir($id)
    {
        $sql = "DELETE FROM aulas
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $id
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR AULA DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function excluirDoProfessor($id, $professorId)
    {
        $sql = "DELETE FROM aulas

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
    | VERIFICAR SE A AULA PERTENCE AO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function pertenceAoProfessor($aulaId, $professorId)
    {
        $sql = "SELECT id
                FROM aulas

                WHERE id = :id
                AND professor_id = :professor_id

                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $aulaId,
            ":professor_id" => $professorId
        ]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR TODAS AS AULAS
    |--------------------------------------------------------------------------
    */

    public function contarTodas()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM aulas";

        $stmt = $this->pdo->query($sql);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado["total"];
    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR AULAS PUBLICADAS
    |--------------------------------------------------------------------------
    */

    public function contarPublicadas()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM aulas
                WHERE status = 'publicada'";

        $stmt = $this->pdo->query($sql);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado["total"];
    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR AULAS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function contarPorProfessor($professorId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM aulas

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
    | CONTAR PUBLICADAS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function contarPublicadasPorProfessor($professorId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM aulas

                WHERE professor_id = :professor_id
                AND status = 'publicada'";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":professor_id" => $professorId
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado["total"];
    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR RASCUNHOS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function contarRascunhosPorProfessor($professorId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM aulas

                WHERE professor_id = :professor_id
                AND status = 'rascunho'";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":professor_id" => $professorId
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado["total"];
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR AULAS
    |--------------------------------------------------------------------------
    */

    public function pesquisar($termo)
    {
        $sql = "SELECT aulas.*, usuarios.nome AS professor_nome

                FROM aulas

                INNER JOIN usuarios
                    ON usuarios.id = aulas.professor_id

                WHERE aulas.titulo LIKE :termo
                OR aulas.descricao LIKE :termo

                ORDER BY aulas.criado_em DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":termo" => "%" . $termo . "%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | PESQUISAR NAS AULAS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function pesquisarPorProfessor($professorId, $termo)
    {
        $sql = "SELECT *
                FROM aulas

                WHERE professor_id = :professor_id

                AND (
                    titulo LIKE :termo
                    OR descricao LIKE :termo
                )

                ORDER BY criado_em DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":professor_id" => $professorId,
            ":termo" => "%" . $termo . "%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | PRÓXIMA ORDEM
    |--------------------------------------------------------------------------
    | Exemplo:
    |
    | existem aulas 1, 2 e 3
    | próxima ordem = 4
    |--------------------------------------------------------------------------
    */

    public function proximaOrdem()
    {
        $sql = "SELECT COALESCE(MAX(ordem), 0) + 1 AS proxima
                FROM aulas";

        $stmt = $this->pdo->query($sql);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado["proxima"];
    }


    /*
    |--------------------------------------------------------------------------
    | ÚLTIMAS AULAS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function listarRecentesPorProfessor($professorId, $limite = 5)
    {
        $limite = (int) $limite;

        if ($limite < 1) {
            $limite = 5;
        }

        $sql = "SELECT *
                FROM aulas

                WHERE professor_id = :professor_id

                ORDER BY atualizado_em DESC

                LIMIT {$limite}";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":professor_id" => $professorId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | ÚLTIMAS AULAS PUBLICADAS
    | HOME / ALUNO
    |--------------------------------------------------------------------------
    */

    public function listarRecentesPublicadas($limite = 6)
    {
        $limite = (int) $limite;

        if ($limite < 1) {
            $limite = 6;
        }

        $sql = "SELECT aulas.*, usuarios.nome AS professor_nome

                FROM aulas

                INNER JOIN usuarios
                    ON usuarios.id = aulas.professor_id

                WHERE aulas.status = 'publicada'

                ORDER BY aulas.criado_em DESC

                LIMIT {$limite}";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}