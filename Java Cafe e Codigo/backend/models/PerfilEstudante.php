<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: PERFIL ESTUDANTE
|
| Responsável pelos dados específicos do perfil Estudante.
|
| Atualmente armazena:
|
| - usuário;
| - instituição/tipo de instituição.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class PerfilEstudante
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | INSTITUIÇÕES PERMITIDAS
    |--------------------------------------------------------------------------
    |
    | Estes valores precisam permanecer alinhados com o fluxo
    | de cadastro/edição do estudante.
    |
    |--------------------------------------------------------------------------
    */

    private const INSTITUICOES_PERMITIDAS = [

        "brigadeiro",
        "estadual",
        "municipal",
        "privada",
        "superior"

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
    | NORMALIZAR INSTITUIÇÃO
    |--------------------------------------------------------------------------
    */

    private function normalizarInstituicao(
        string $instituicao
    ): string {

        return strtolower(
            trim(
                $instituicao
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | INSTITUIÇÃO VÁLIDA
    |--------------------------------------------------------------------------
    */

    private function instituicaoValida(
        string $instituicao
    ): bool {

        return in_array(
            $instituicao,
            self::INSTITUICOES_PERMITIDAS,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PERFIL DUPLICADO?
    |--------------------------------------------------------------------------
    |
    | A proteção definitiva depende também de:
    |
    | UNIQUE(usuario_id)
    |
    | na tabela perfil_estudante.
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
        string $instituicao
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
        | INSTITUIÇÃO
        |--------------------------------------------------------------------------
        */

        $instituicao =
            $this->normalizarInstituicao(
                $instituicao
            );


        if (
            !$this->instituicaoValida(
                $instituicao
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
                    INSERT INTO perfil_estudante
                    (
                        usuario_id,
                        instituicao
                    )

                    VALUES
                    (
                        :usuario,
                        :instituicao
                    )
                "
            );


        try {

            return $stmt->execute(
                [
                    ":usuario" =>
                        $usuarioId,

                    ":instituicao" =>
                        $instituicao
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
                        instituicao

                    FROM perfil_estudante

                    WHERE
                        usuario_id = :usuario

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":usuario" =>
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
    | ATUALIZAR PERFIL
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $usuarioId,
        string $instituicao
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
        | INSTITUIÇÃO
        |--------------------------------------------------------------------------
        */

        $instituicao =
            $this->normalizarInstituicao(
                $instituicao
            );


        if (
            !$this->instituicaoValida(
                $instituicao
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
                    UPDATE perfil_estudante

                    SET
                        instituicao = :instituicao

                    WHERE
                        usuario_id = :usuario
                "
            );


        $resultado =
            $stmt->execute(
                [
                    ":instituicao" =>
                        $instituicao,

                    ":usuario" =>
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
        | Se o estudante salvar exatamente a mesma instituição,
        | o MySQL pode informar 0 linhas modificadas.
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

                    FROM perfil_estudante

                    WHERE
                        usuario_id = :usuario

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":usuario" =>
                    $usuarioId
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }

}