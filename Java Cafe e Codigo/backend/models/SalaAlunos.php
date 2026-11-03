<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: ALUNOS DA SALA
|
| Responsável pela relação entre:
|
| - salas;
| - estudantes participantes.
|
| Tabela:
|
| sala_alunos
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class SalaAluno
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


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
                "A conexão com o banco de dados não está disponível."
            );

        }


        $this->pdo =
            $pdo;

    }


    /*
    |--------------------------------------------------------------------------
    | ID VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function idValido(
        int $id
    ): bool {

        return
            $id > 0;

    }


    /*
    |--------------------------------------------------------------------------
    | VÍNCULO DUPLICADO?
    |--------------------------------------------------------------------------
    |
    | A proteção definitiva contra o mesmo estudante entrar
    | duas vezes na mesma sala precisa existir no banco:
    |
    | UNIQUE(sala_id, aluno_id)
    |
    |--------------------------------------------------------------------------
    */

    private function vinculoDuplicado(
        PDOException $erro
    ): bool {

        $sqlState =
            (string) (
                $erro->errorInfo[0]
                ??
                $erro->getCode()
            );


        $codigoDriver =
            (int) (
                $erro->errorInfo[1]
                ??
                0
            );


        return
            $sqlState === "23000"
            &&
            $codigoDriver === 1062;

    }


    /*
    |--------------------------------------------------------------------------
    | ENTRAR NA SALA
    |--------------------------------------------------------------------------
    */

    public function entrar(
        int $salaId,
        int $alunoId
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | IDS
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $salaId
            )
            ||
            !$this->idValido(
                $alunoId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | JÁ PARTICIPA?
        |--------------------------------------------------------------------------
        |
        | Esta consulta permite uma resposta rápida no fluxo normal.
        |
        | Porém ela NÃO substitui UNIQUE(sala_id, aluno_id), porque
        | duas requisições simultâneas poderiam chegar aqui juntas.
        |
        |--------------------------------------------------------------------------
        */

        if (
            $this->existe(
                $salaId,
                $alunoId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    INSERT INTO sala_alunos
                    (
                        sala_id,
                        aluno_id
                    )

                    VALUES
                    (
                        :sala,
                        :aluno
                    )
                "
            );


        try {

            return $stmt->execute(
                [
                    ":sala" =>
                        $salaId,

                    ":aluno" =>
                        $alunoId
                ]
            ) === true;

        }
        catch (
            PDOException $erro
        ) {

            /*
            |--------------------------------------------------------------------------
            | JÁ ENTROU POR OUTRA REQUISIÇÃO
            |--------------------------------------------------------------------------
            */

            if (
                $this->vinculoDuplicado(
                    $erro
                )
            ) {

                return false;

            }


            throw $erro;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR PARTICIPAÇÃO
    |--------------------------------------------------------------------------
    */

    public function existe(
        int $salaId,
        int $alunoId
    ): bool {

        if (
            !$this->idValido(
                $salaId
            )
            ||
            !$this->idValido(
                $alunoId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM sala_alunos

                    WHERE
                        sala_id = :sala
                        AND aluno_id = :aluno

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":sala" =>
                    $salaId,

                ":aluno" =>
                    $alunoId
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR ALUNOS DA SALA
    |--------------------------------------------------------------------------
    |
    | O SalaController é responsável por garantir que somente
    | o professor proprietário consulte esta lista.
    |
    |--------------------------------------------------------------------------
    */

    public function listarAlunos(
        int $salaId
    ): array {

        if (
            !$this->idValido(
                $salaId
            )
        ) {

            return [];

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        u.id,
                        u.nome,
                        u.email,
                        sa.entrou_em

                    FROM sala_alunos sa

                    INNER JOIN usuarios u
                        ON u.id = sa.aluno_id

                    WHERE
                        sa.sala_id = :sala

                    ORDER BY
                        sa.entrou_em ASC,
                        sa.id ASC
                "
            );


        $stmt->execute(
            [
                ":sala" =>
                    $salaId
            ]
        );


        $alunos =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $alunos
            )
                ?
                $alunos
                :
                [];

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR SALAS DO ALUNO
    |--------------------------------------------------------------------------
    |
    | Mantemos s.* temporariamente.
    |
    | Ainda vamos conferir o schema completo de "salas" e quais
    | campos as telas atuais realmente utilizam.
    |
    |--------------------------------------------------------------------------
    */

    public function listarSalasAluno(
        int $alunoId
    ): array {

        if (
            !$this->idValido(
                $alunoId
            )
        ) {

            return [];

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        s.*

                    FROM sala_alunos sa

                    INNER JOIN salas s
                        ON s.id = sa.sala_id

                    WHERE
                        sa.aluno_id = :aluno

                    ORDER BY
                        sa.entrou_em DESC,
                        sa.id DESC
                "
            );


        $stmt->execute(
            [
                ":aluno" =>
                    $alunoId
            ]
        );


        $salas =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $salas
            )
                ?
                $salas
                :
                [];

    }


    /*
    |--------------------------------------------------------------------------
    | SAIR DA SALA
    |--------------------------------------------------------------------------
    */

    public function sair(
        int $salaId,
        int $alunoId
    ): bool {

        if (
            !$this->idValido(
                $salaId
            )
            ||
            !$this->idValido(
                $alunoId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        |
        | Não usamos LIMIT 1.
        |
        | Com UNIQUE(sala_id, aluno_id), existirá somente uma linha.
        |
        | Se houver duplicação legada antes de adicionarmos a constraint,
        | este DELETE também limpa todos os vínculos duplicados.
        |
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    DELETE FROM sala_alunos

                    WHERE
                        sala_id = :sala
                        AND aluno_id = :aluno
                "
            );


        if (
            $stmt->execute(
                [
                    ":sala" =>
                        $salaId,

                    ":aluno" =>
                        $alunoId
                ]
            )
            !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | PRECISA TER REMOVIDO PELO MENOS UM VÍNCULO
        |--------------------------------------------------------------------------
        */

        return
            $stmt->rowCount() >= 1;

    }

}