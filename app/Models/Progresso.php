<?php

class Progresso
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function concluirAula($usuarioId, $aulaId)
    {
        $sql = "INSERT INTO progresso_aulas
                (
                    usuario_id,
                    aula_id,
                    concluida,
                    concluida_em
                )
                VALUES
                (
                    :usuario_id,
                    :aula_id,
                    TRUE,
                    NOW()
                )

                ON DUPLICATE KEY UPDATE
                    concluida = TRUE,
                    concluida_em = NOW()";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":usuario_id" => $usuarioId,
            ":aula_id" => $aulaId
        ]);
    }

    public function removerConclusao($usuarioId, $aulaId)
    {
        $sql = "UPDATE progresso_aulas

                SET concluida = FALSE,
                    concluida_em = NULL

                WHERE usuario_id = :usuario_id
                AND aula_id = :aula_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":usuario_id" => $usuarioId,
            ":aula_id" => $aulaId
        ]);
    }

    public function aulaConcluida($usuarioId, $aulaId)
    {
        $sql = "SELECT concluida
                FROM progresso_aulas

                WHERE usuario_id = :usuario_id
                AND aula_id = :aula_id

                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":usuario_id" => $usuarioId,
            ":aula_id" => $aulaId
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) {
            return false;
        }

        return (bool) $resultado["concluida"];
    }

    public function contarConcluidas($usuarioId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM progresso_aulas pa

                INNER JOIN aulas a
                    ON a.id = pa.aula_id

                WHERE pa.usuario_id = :usuario_id
                AND pa.concluida = TRUE
                AND a.status = 'publicada'";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":usuario_id" => $usuarioId
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado["total"];
    }

    public function calcularPercentual($usuarioId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM aulas
                WHERE status = 'publicada'";

        $stmt = $this->pdo->query($sql);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalAulas = (int) $resultado["total"];

        if ($totalAulas === 0) {
            return 0;
        }

        $concluidas = $this->contarConcluidas($usuarioId);

        return round(
            ($concluidas / $totalAulas) * 100
        );
    }

    public function listarProgressoDoAluno($usuarioId)
    {
        $sql = "SELECT
                    a.id,
                    a.titulo,
                    a.descricao,
                    a.ordem,
                    a.status,

                    COALESCE(
                        pa.concluida,
                        FALSE
                    ) AS concluida,

                    pa.concluida_em

                FROM aulas a

                LEFT JOIN progresso_aulas pa
                    ON pa.aula_id = a.id
                    AND pa.usuario_id = :usuario_id

                WHERE a.status = 'publicada'

                ORDER BY a.ordem ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":usuario_id" => $usuarioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}