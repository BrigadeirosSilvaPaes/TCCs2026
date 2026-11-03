<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: PERFIL PROFESSOR
|
| Responsável pelos dados específicos do perfil Professor:
|
| - instituição;
| - disciplina;
| - aprovação administrativa;
| - data da aprovação.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class PerfilProfessor
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | LIMITES
    |--------------------------------------------------------------------------
    */

    private const INSTITUICAO_MIN =
        2;


    private const INSTITUICAO_MAX =
        150;


    private const DISCIPLINA_MIN =
        2;


    private const DISCIPLINA_MAX =
        100;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    |
    | Utilizamos a mesma conexão PDO do bootstrap.
    |
    | Isso permite que este Model participe das transações abertas
    | pelos endpoints da aplicação.
    |
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
    | USUÁRIO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function usuarioValido(
        int $usuarioId
    ): bool {

        return
            $usuarioId > 0;

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
    | NORMALIZAR TEXTO
    |--------------------------------------------------------------------------
    */

    private function normalizarTexto(
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
    | TEXTO POSSUI CARACTERE DE CONTROLE?
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
    | INSTITUIÇÃO VÁLIDA
    |--------------------------------------------------------------------------
    */

    private function instituicaoValida(
        string $instituicao
    ): bool {

        $tamanho =
            $this->comprimento(
                $instituicao
            );


        return
            $instituicao !== ""
            &&
            $tamanho >= self::INSTITUICAO_MIN
            &&
            $tamanho <= self::INSTITUICAO_MAX
            &&
            !$this->possuiControleInvalido(
                $instituicao
            );

    }


    /*
    |--------------------------------------------------------------------------
    | DISCIPLINA VÁLIDA
    |--------------------------------------------------------------------------
    */

    private function disciplinaValida(
        string $disciplina
    ): bool {

        $tamanho =
            $this->comprimento(
                $disciplina
            );


        return
            $disciplina !== ""
            &&
            $tamanho >= self::DISCIPLINA_MIN
            &&
            $tamanho <= self::DISCIPLINA_MAX
            &&
            !$this->possuiControleInvalido(
                $disciplina
            );

    }


    /*
    |--------------------------------------------------------------------------
    | DUPLICAÇÃO
    |--------------------------------------------------------------------------
    |
    | A proteção definitiva precisa existir no banco:
    |
    | UNIQUE(usuario_id)
    |
    |--------------------------------------------------------------------------
    */

    private function perfilDuplicado(
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
    | CRIAR PERFIL
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $usuarioId,
        string $instituicao,
        string $disciplina
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $instituicao =
            $this->normalizarTexto(
                $instituicao
            );


        $disciplina =
            $this->normalizarTexto(
                $disciplina
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->instituicaoValida(
                $instituicao
            )
            ||
            !$this->disciplinaValida(
                $disciplina
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        |
        | O professor começa sempre:
        |
        | aprovado = 0
        | data_aprovacao = NULL
        |
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    INSERT INTO perfil_professor
                    (
                        usuario_id,
                        instituicao,
                        disciplina,
                        aprovado,
                        data_aprovacao
                    )

                    VALUES
                    (
                        :usuario_id,
                        :instituicao,
                        :disciplina,
                        0,
                        NULL
                    )
                "
            );


        try {

            return $stmt->execute(
                [
                    ":usuario_id" =>
                        $usuarioId,

                    ":instituicao" =>
                        $instituicao,

                    ":disciplina" =>
                        $disciplina
                ]
            );

        }
        catch (
            PDOException $erro
        ) {

            /*
            |--------------------------------------------------------------------------
            | PERFIL DUPLICADO
            |--------------------------------------------------------------------------
            */

            if (
                $this->perfilDuplicado(
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
    | BUSCAR POR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function buscarPorUsuario(
        int $usuarioId
    ): array|false {

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        id,
                        usuario_id,
                        instituicao,
                        disciplina,
                        aprovado,
                        data_aprovacao

                    FROM perfil_professor

                    WHERE
                        usuario_id = :usuario_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId
            ]
        );


        $perfil =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $perfil
            )
                ?
                $perfil
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR PERFIL PROFISSIONAL
    |--------------------------------------------------------------------------
    |
    | Este método altera somente:
    |
    | - instituição;
    | - disciplina.
    |
    | Ele NÃO altera automaticamente a aprovação.
    |
    | O endpoint atualizar_professor.php coordena:
    |
    | atualizar()
    | +
    | removerAprovacao()
    |
    | dentro da mesma transação.
    |
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $usuarioId,
        string $instituicao,
        string $disciplina
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $instituicao =
            $this->normalizarTexto(
                $instituicao
            );


        $disciplina =
            $this->normalizarTexto(
                $disciplina
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->instituicaoValida(
                $instituicao
            )
            ||
            !$this->disciplinaValida(
                $disciplina
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE perfil_professor

                    SET
                        instituicao = :instituicao,
                        disciplina = :disciplina

                    WHERE
                        usuario_id = :usuario_id
                "
            );


        $resultado =
            $stmt->execute(
                [
                    ":instituicao" =>
                        $instituicao,

                    ":disciplina" =>
                        $disciplina,

                    ":usuario_id" =>
                        $usuarioId
                ]
            );


        if (
            $resultado !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NÃO USAMOS rowCount()
        |--------------------------------------------------------------------------
        |
        | Salvar os mesmos dados pode produzir 0 linhas modificadas.
        |
        | Isso não significa erro.
        |
        |--------------------------------------------------------------------------
        */

        return $this->existePerfil(
            $usuarioId
        );

    }


    /*
    |--------------------------------------------------------------------------
    | APROVAR PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function aprovar(
        int $usuarioId
    ): bool {

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE perfil_professor

                    SET
                        aprovado = 1,
                        data_aprovacao = NOW()

                    WHERE
                        usuario_id = :usuario_id
                "
            );


        $resultado =
            $stmt->execute(
                [
                    ":usuario_id" =>
                        $usuarioId
                ]
            );


        if (
            $resultado !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRMA O ESTADO FINAL
        |--------------------------------------------------------------------------
        */

        return $this->estaAprovado(
            $usuarioId
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER APROVAÇÃO
    |--------------------------------------------------------------------------
    |
    | Utilizado quando os dados profissionais analisados
    | administrativamente são alterados.
    |
    |--------------------------------------------------------------------------
    */

    public function removerAprovacao(
        int $usuarioId
    ): bool {

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE perfil_professor

                    SET
                        aprovado = 0,
                        data_aprovacao = NULL

                    WHERE
                        usuario_id = :usuario_id
                "
            );


        $resultado =
            $stmt->execute(
                [
                    ":usuario_id" =>
                        $usuarioId
                ]
            );


        if (
            $resultado !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRMA QUE O PERFIL EXISTE E NÃO ESTÁ APROVADO
        |--------------------------------------------------------------------------
        */

        $perfil =
            $this->buscarPorUsuario(
                $usuarioId
            );


        if (
            !is_array(
                $perfil
            )
        ) {

            return false;

        }


        $aprovado =
            $perfil["aprovado"]
            ??
            null;


        $dataAprovacao =
            $perfil["data_aprovacao"]
            ??
            null;


        return
            (int) $aprovado === 0
            &&
            $dataAprovacao === null;

    }


    /*
    |--------------------------------------------------------------------------
    | PROFESSOR ESTÁ APROVADO?
    |--------------------------------------------------------------------------
    */

    public function estaAprovado(
        int $usuarioId
    ): bool {

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        aprovado

                    FROM perfil_professor

                    WHERE
                        usuario_id = :usuario_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId
            ]
        );


        $aprovado =
            $stmt->fetchColumn();


        if (
            $aprovado === false
        ) {

            return false;

        }


        return
            (int) $aprovado === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | EXISTE PERFIL?
    |--------------------------------------------------------------------------
    */

    public function existePerfil(
        int $usuarioId
    ): bool {

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM perfil_professor

                    WHERE
                        usuario_id = :usuario_id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":usuario_id" =>
                    $usuarioId
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }

}