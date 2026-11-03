<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: PERFIL DO USUÁRIO
|
| Responsável pela tabela:
|
| perfil_usuario
|
| Este é o perfil principal que define:
|
| - tipo de perfil;
| - estado;
| - cidade;
| - objetivo;
| - conclusão da configuração inicial;
| - demais informações públicas existentes na tabela.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class PerfilUsuario
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | TIPOS DE PERFIL
    |--------------------------------------------------------------------------
    */

    private const TIPOS_PERMITIDOS = [

        PROFILE_ESTUDANTE,
        PROFILE_PROFESSOR,
        PROFILE_APRENDIZ

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
    | NORMALIZAR TIPO
    |--------------------------------------------------------------------------
    */

    private function normalizarTipo(
        string $tipoUsuario
    ): string {

        return strtolower(
            trim(
                $tipoUsuario
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | TIPO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function tipoValido(
        string $tipoUsuario
    ): bool {

        return in_array(
            $tipoUsuario,
            self::TIPOS_PERMITIDOS,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR VALOR OPCIONAL
    |--------------------------------------------------------------------------
    */

    private function normalizarOpcional(
        ?string $valor
    ): ?string {

        if (
            $valor === null
        ) {

            return null;

        }


        $valor =
            trim(
                $valor
            );


        return
            $valor !== ""
                ?
                $valor
                :
                null;

    }


    /*
    |--------------------------------------------------------------------------
    | PERFIL EXISTE COM DETERMINADO TIPO?
    |--------------------------------------------------------------------------
    */

    private function existePerfilComTipo(
        int $usuarioId,
        string $tipoUsuario
    ): bool {

        if (
            !$this->usuarioValido(
                $usuarioId
            )
            ||
            !$this->tipoValido(
                $tipoUsuario
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM perfil_usuario

                    WHERE
                        usuario_id = :usuario
                        AND tipo_usuario = :tipo

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":usuario" =>
                    $usuarioId,

                ":tipo" =>
                    $tipoUsuario
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRO DUPLICADO?
    |--------------------------------------------------------------------------
    |
    | A proteção definitiva contra dois perfis principais para
    | o mesmo usuário precisa existir também no banco:
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
        string $tipoUsuario,
        ?string $estado = null,
        ?string $cidade = null,
        ?string $objetivo = null
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
        | TIPO
        |--------------------------------------------------------------------------
        */

        $tipoUsuario =
            $this->normalizarTipo(
                $tipoUsuario
            );


        if (
            !$this->tipoValido(
                $tipoUsuario
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZA CAMPOS
        |--------------------------------------------------------------------------
        */

        $estado =
            $this->normalizarOpcional(
                $estado
            );


        if (
            $estado !== null
        ) {

            $estado =
                strtoupper(
                    $estado
                );

        }


        $cidade =
            $this->normalizarOpcional(
                $cidade
            );


        $objetivo =
            $this->normalizarOpcional(
                $objetivo
            );


        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    INSERT INTO perfil_usuario
                    (
                        usuario_id,
                        tipo_usuario,
                        estado,
                        cidade,
                        objetivo
                    )

                    VALUES
                    (
                        :usuario,
                        :tipo,
                        :estado,
                        :cidade,
                        :objetivo
                    )
                "
            );


        try {

            return $stmt->execute(
                [
                    ":usuario" =>
                        $usuarioId,

                    ":tipo" =>
                        $tipoUsuario,

                    ":estado" =>
                        $estado,

                    ":cidade" =>
                        $cidade,

                    ":objetivo" =>
                        $objetivo
                ]
            );

        }
        catch (
            PDOException $erro
        ) {

            /*
            |--------------------------------------------------------------------------
            | PERFIL PRINCIPAL DUPLICADO
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
    | BUSCAR PERFIL POR USUÁRIO
    |--------------------------------------------------------------------------
    |
    | Mantemos SELECT * intencionalmente.
    |
    | perfil_usuario também contém informações utilizadas pelo
    | perfil público e queremos preservar todas as colunas atualmente
    | disponíveis enquanto auditamos o restante do banco/frontend.
    |
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
                        *

                    FROM perfil_usuario

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
    |
    | IMPORTANTE:
    |
    | O tipo do perfil NÃO é atualizado.
    |
    | O parâmetro $tipoUsuario continua no método por compatibilidade
    | com o PerfilController atual, mas passa a funcionar como uma
    | confirmação do tipo já existente.
    |
    | Portanto:
    |
    | estudante → professor
    |
    | não pode acontecer por este método.
    |
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $usuarioId,
        string $tipoUsuario,
        ?string $estado,
        ?string $cidade,
        ?string $objetivo
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
        | TIPO
        |--------------------------------------------------------------------------
        */

        $tipoUsuario =
            $this->normalizarTipo(
                $tipoUsuario
            );


        if (
            !$this->tipoValido(
                $tipoUsuario
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZA
        |--------------------------------------------------------------------------
        */

        $estado =
            $this->normalizarOpcional(
                $estado
            );


        if (
            $estado !== null
        ) {

            $estado =
                strtoupper(
                    $estado
                );

        }


        $cidade =
            $this->normalizarOpcional(
                $cidade
            );


        $objetivo =
            $this->normalizarOpcional(
                $objetivo
            );


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        |
        | Repare que tipo_usuario NÃO aparece no SET.
        |
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE perfil_usuario

                    SET
                        estado = :estado,
                        cidade = :cidade,
                        objetivo = :objetivo

                    WHERE
                        usuario_id = :usuario
                        AND tipo_usuario = :tipo
                "
            );


        $resultado =
            $stmt->execute(
                [
                    ":estado" =>
                        $estado,

                    ":cidade" =>
                        $cidade,

                    ":objetivo" =>
                        $objetivo,

                    ":usuario" =>
                        $usuarioId,

                    ":tipo" =>
                        $tipoUsuario
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
        | Se os valores enviados forem iguais aos valores existentes,
        | MySQL pode informar 0 linhas modificadas mesmo com o perfil válido.
        |
        | Por isso confirmamos a existência do perfil com o tipo esperado.
        |
        |--------------------------------------------------------------------------
        */

        return $this->existePerfilComTipo(
            $usuarioId,
            $tipoUsuario
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MARCAR PERFIL COMO COMPLETO
    |--------------------------------------------------------------------------
    */

    public function marcarCompleto(
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
                    UPDATE perfil_usuario

                    SET
                        perfil_completo = 1

                    WHERE
                        usuario_id = :usuario
                "
            );


        $resultado =
            $stmt->execute(
                [
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
        | CONFIRMA ESTADO FINAL
        |--------------------------------------------------------------------------
        */

        return $this->perfilCompleto(
            $usuarioId
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR SE PERFIL EXISTE
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

                    FROM perfil_usuario

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


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR SE PERFIL ESTÁ COMPLETO
    |--------------------------------------------------------------------------
    */

    public function perfilCompleto(
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
                        perfil_completo

                    FROM perfil_usuario

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


        $perfilCompleto =
            $stmt->fetchColumn();


        if (
            $perfilCompleto === false
        ) {

            return false;

        }


        return
            (int) $perfilCompleto === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR TIPO DE USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function tipoUsuario(
        int $usuarioId
    ): ?string {

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return null;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        tipo_usuario

                    FROM perfil_usuario

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


        $tipoUsuario =
            $stmt->fetchColumn();


        if (
            !is_string(
                $tipoUsuario
            )
        ) {

            return null;

        }


        $tipoUsuario =
            $this->normalizarTipo(
                $tipoUsuario
            );


        return
            $this->tipoValido(
                $tipoUsuario
            )
                ?
                $tipoUsuario
                :
                null;

    }

}