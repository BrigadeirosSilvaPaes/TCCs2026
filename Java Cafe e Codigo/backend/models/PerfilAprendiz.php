<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: PERFIL APRENDIZ
|
| Responsável pelos dados específicos armazenados em:
|
| perfil_aprendiz
|
| Campos utilizados atualmente:
|
| - usuario_id;
| - experiencia;
| - objetivo.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class PerfilAprendiz
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | EXPERIÊNCIAS PERMITIDAS
    |--------------------------------------------------------------------------
    */

    private const EXPERIENCIAS_PERMITIDAS = [

        "nenhuma",
        "basica",
        "intermediaria",
        "avancada"

    ];


    /*
    |--------------------------------------------------------------------------
    | LIMITE DO OBJETIVO
    |--------------------------------------------------------------------------
    */

    private const OBJETIVO_MAX =
        500;


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
    | UTF-8 VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function utf8Valido(
        string $texto
    ): bool {

        if (
            function_exists(
                "mb_check_encoding"
            )
        ) {

            return mb_check_encoding(
                $texto,
                "UTF-8"
            );

        }


        return preg_match(
            "//u",
            $texto
        ) === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR EXPERIÊNCIA
    |--------------------------------------------------------------------------
    */

    private function normalizarExperiencia(
        string $experiencia
    ): string {

        return strtolower(
            trim(
                $experiencia
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | EXPERIÊNCIA VÁLIDA
    |--------------------------------------------------------------------------
    */

    private function experienciaValida(
        string $experiencia
    ): bool {

        return in_array(
            $experiencia,
            self::EXPERIENCIAS_PERMITIDAS,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR OBJETIVO
    |--------------------------------------------------------------------------
    |
    | Preservamos quebras de linha porque o objetivo pode ser
    | um pequeno texto escrito pelo usuário.
    |
    |--------------------------------------------------------------------------
    */

    private function normalizarObjetivo(
        ?string $objetivo
    ): ?string {

        if (
            $objetivo === null
        ) {

            return null;

        }


        $objetivo =
            str_replace(
                [
                    "\r\n",
                    "\r"
                ],
                "\n",
                $objetivo
            );


        $objetivo =
            trim(
                $objetivo
            );


        return
            $objetivo !== ""
                ?
                $objetivo
                :
                null;

    }


    /*
    |--------------------------------------------------------------------------
    | OBJETIVO VÁLIDO
    |--------------------------------------------------------------------------
    |
    | Permitimos:
    |
    | - texto normal;
    | - TAB;
    | - quebra de linha.
    |
    | Bloqueamos os demais caracteres de controle.
    |
    |--------------------------------------------------------------------------
    */

    private function objetivoValido(
        ?string $objetivo
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | OPCIONAL
        |--------------------------------------------------------------------------
        */

        if (
            $objetivo === null
        ) {

            return true;

        }


        /*
        |--------------------------------------------------------------------------
        | UTF-8
        |--------------------------------------------------------------------------
        */

        if (
            !$this->utf8Valido(
                $objetivo
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | TAMANHO
        |--------------------------------------------------------------------------
        */

        if (
            $this->comprimento(
                $objetivo
            )
            >
            self::OBJETIVO_MAX
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | CARACTERES DE CONTROLE
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                "/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/",
                $objetivo
            )
            === 1
        ) {

            return false;

        }


        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | PERFIL DUPLICADO?
    |--------------------------------------------------------------------------
    |
    | Esta proteção depende da existência da constraint:
    |
    | UNIQUE(usuario_id)
    |
    | em perfil_aprendiz.
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
        string $experiencia = "nenhuma",
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
        | EXPERIÊNCIA
        |--------------------------------------------------------------------------
        */

        $experiencia =
            $this->normalizarExperiencia(
                $experiencia
            );


        if (
            !$this->experienciaValida(
                $experiencia
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | OBJETIVO
        |--------------------------------------------------------------------------
        */

        $objetivo =
            $this->normalizarObjetivo(
                $objetivo
            );


        if (
            !$this->objetivoValido(
                $objetivo
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
                    INSERT INTO perfil_aprendiz
                    (
                        usuario_id,
                        experiencia,
                        objetivo
                    )

                    VALUES
                    (
                        :usuario_id,
                        :experiencia,
                        :objetivo
                    )
                "
            );


        $stmt->bindValue(
            ":usuario_id",
            $usuarioId,
            PDO::PARAM_INT
        );


        $stmt->bindValue(
            ":experiencia",
            $experiencia,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":objetivo",
            $objetivo,
            $objetivo === null
                ?
                PDO::PARAM_NULL
                :
                PDO::PARAM_STR
        );


        /*
        |--------------------------------------------------------------------------
        | EXECUTA
        |--------------------------------------------------------------------------
        */

        try {

            return
                $stmt->execute() === true;

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
                        usuario_id,
                        experiencia,
                        objetivo

                    FROM perfil_aprendiz

                    WHERE
                        usuario_id = :usuario_id

                    LIMIT 1
                "
            );


        $stmt->bindValue(
            ":usuario_id",
            $usuarioId,
            PDO::PARAM_INT
        );


        $stmt->execute();


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
        string $experiencia,
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
        | EXPERIÊNCIA
        |--------------------------------------------------------------------------
        */

        $experiencia =
            $this->normalizarExperiencia(
                $experiencia
            );


        if (
            !$this->experienciaValida(
                $experiencia
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | OBJETIVO
        |--------------------------------------------------------------------------
        */

        $objetivo =
            $this->normalizarObjetivo(
                $objetivo
            );


        if (
            !$this->objetivoValido(
                $objetivo
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
                    UPDATE perfil_aprendiz

                    SET
                        experiencia = :experiencia,
                        objetivo = :objetivo

                    WHERE
                        usuario_id = :usuario_id

                    LIMIT 1
                "
            );


        $stmt->bindValue(
            ":experiencia",
            $experiencia,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":objetivo",
            $objetivo,
            $objetivo === null
                ?
                PDO::PARAM_NULL
                :
                PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":usuario_id",
            $usuarioId,
            PDO::PARAM_INT
        );


        if (
            $stmt->execute()
            !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NÃO USAMOS rowCount()
        |--------------------------------------------------------------------------
        |
        | Se os dados forem exatamente iguais aos já salvos,
        | MySQL pode informar zero linhas modificadas.
        |
        |--------------------------------------------------------------------------
        */

        return $this->existePerfil(
            $usuarioId
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ALTERAR SOMENTE EXPERIÊNCIA
    |--------------------------------------------------------------------------
    |
    | Este método é utilizado atualmente pelo PerfilController.
    |
    |--------------------------------------------------------------------------
    */

    public function alterarExperiencia(
        int $usuarioId,
        string $experiencia
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
        | EXPERIÊNCIA
        |--------------------------------------------------------------------------
        */

        $experiencia =
            $this->normalizarExperiencia(
                $experiencia
            );


        if (
            !$this->experienciaValida(
                $experiencia
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
                    UPDATE perfil_aprendiz

                    SET
                        experiencia = :experiencia

                    WHERE
                        usuario_id = :usuario_id

                    LIMIT 1
                "
            );


        $stmt->bindValue(
            ":experiencia",
            $experiencia,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":usuario_id",
            $usuarioId,
            PDO::PARAM_INT
        );


        if (
            $stmt->execute()
            !== true
        ) {

            return false;

        }


        return $this->existePerfil(
            $usuarioId
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR EXISTÊNCIA
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

                    FROM perfil_aprendiz

                    WHERE
                        usuario_id = :usuario_id

                    LIMIT 1
                "
            );


        $stmt->bindValue(
            ":usuario_id",
            $usuarioId,
            PDO::PARAM_INT
        );


        $stmt->execute();


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR PERFIL
    |--------------------------------------------------------------------------
    |
    | 🟡 REVISAR
    |
    | Não utilizar isoladamente sem coordenar também perfil_usuario.
    |
    | Caso contrário poderíamos gerar:
    |
    | perfil_usuario.tipo_usuario = aprendiz
    |
    | mas:
    |
    | perfil_aprendiz inexistente.
    |
    |--------------------------------------------------------------------------
    */

    public function excluir(
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
        | PRECISA EXISTIR
        |--------------------------------------------------------------------------
        */

        if (
            !$this->existePerfil(
                $usuarioId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    DELETE FROM perfil_aprendiz

                    WHERE
                        usuario_id = :usuario_id

                    LIMIT 1
                "
            );


        $stmt->bindValue(
            ":usuario_id",
            $usuarioId,
            PDO::PARAM_INT
        );


        if (
            $stmt->execute()
            !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | DELETE DEVE ALTERAR EXATAMENTE UMA LINHA
        |--------------------------------------------------------------------------
        */

        return
            $stmt->rowCount() === 1;

    }

}