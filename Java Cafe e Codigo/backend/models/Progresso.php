<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: PROGRESSO
|
| Responsável pela persistência do progresso dos usuários.
|
| Cada registro representa:
|
| usuário
| +
| módulo
| +
| nível
| +
| XP
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class Progresso
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

    private const MODULO_MAX =
        50;


    /*
    |--------------------------------------------------------------------------
    | INT DO MYSQL
    |--------------------------------------------------------------------------
    |
    | A estrutura atual utiliza INT para nível e XP.
    |
    |--------------------------------------------------------------------------
    */

    private const VALOR_MAXIMO =
        2147483647;


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
    | UTF-8
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
    | NORMALIZAR MÓDULO
    |--------------------------------------------------------------------------
    */

    private function normalizarModulo(
        string $modulo
    ): string {

        return strtolower(
            trim(
                $modulo
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MÓDULO VÁLIDO
    |--------------------------------------------------------------------------
    |
    | Ainda não usamos whitelist.
    |
    | Precisamos primeiro cruzar os fluxos atuais de:
    |
    | - Aulas Conversadas;
    | - progresso;
    | - módulos existentes.
    |
    |--------------------------------------------------------------------------
    */

    private function moduloValido(
        string $modulo
    ): bool {

        if (
            $modulo === ""
        ) {

            return false;

        }


        if (
            !$this->utf8Valido(
                $modulo
            )
        ) {

            return false;

        }


        if (
            $this->comprimento(
                $modulo
            )
            >
            self::MODULO_MAX
        ) {

            return false;

        }


        if (
            preg_match(
                "/[\x00-\x1F\x7F]/",
                $modulo
            )
            === 1
        ) {

            return false;

        }


        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | NÍVEL VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function nivelValido(
        int $nivel
    ): bool {

        return
            $nivel >= 0
            &&
            $nivel <=
            self::VALOR_MAXIMO;

    }


    /*
    |--------------------------------------------------------------------------
    | XP TOTAL VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function xpValido(
        int $xp
    ): bool {

        return
            $xp >= 0
            &&
            $xp <=
            self::VALOR_MAXIMO;

    }


    /*
    |--------------------------------------------------------------------------
    | XP PARA ADIÇÃO
    |--------------------------------------------------------------------------
    */

    private function xpAdicionalValido(
        int $xp
    ): bool {

        return
            $xp > 0
            &&
            $xp <=
            self::VALOR_MAXIMO;

    }


    /*
    |--------------------------------------------------------------------------
    | PROGRESSO DUPLICADO?
    |--------------------------------------------------------------------------
    |
    | A tabela deve possuir:
    |
    | UNIQUE(usuario_id, modulo)
    |
    |--------------------------------------------------------------------------
    */

    private function progressoDuplicado(
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
    | BUSCAR PROGRESSO
    |--------------------------------------------------------------------------
    */

    public function buscar(
        int $usuarioId,
        string $modulo
    ): array|false {

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
        | MÓDULO
        |--------------------------------------------------------------------------
        */

        $modulo =
            $this->normalizarModulo(
                $modulo
            );


        if (
            !$this->moduloValido(
                $modulo
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | CONSULTA
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        id,
                        usuario_id,
                        modulo,
                        nivel,
                        xp,
                        atualizado_em

                    FROM progresso

                    WHERE
                        usuario_id = :usuario
                        AND modulo = :modulo

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":usuario" =>
                    $usuarioId,

                ":modulo" =>
                    $modulo
            ]
        );


        $progresso =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $progresso
            )
                ?
                $progresso
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR PROGRESSO DO USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function listar(
        int $usuarioId
    ): array {

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return [];

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        id,
                        usuario_id,
                        modulo,
                        nivel,
                        xp,
                        atualizado_em

                    FROM progresso

                    WHERE
                        usuario_id = :usuario

                    ORDER BY
                        atualizado_em DESC,
                        id DESC
                "
            );


        $stmt->execute(
            [
                ":usuario" =>
                    $usuarioId
            ]
        );


        $dados =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $dados
            )
                ?
                $dados
                :
                [];

    }


    /*
    |--------------------------------------------------------------------------
    | SALVAR NOVO PROGRESSO
    |--------------------------------------------------------------------------
    |
    | Este método faz somente INSERT.
    |
    | Não transforma silenciosamente uma criação em atualização.
    |
    |--------------------------------------------------------------------------
    */

    public function salvar(
        int $usuarioId,
        string $modulo,
        int $nivel,
        int $xp
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
        | MÓDULO
        |--------------------------------------------------------------------------
        */

        $modulo =
            $this->normalizarModulo(
                $modulo
            );


        if (
            !$this->moduloValido(
                $modulo
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | VALORES
        |--------------------------------------------------------------------------
        */

        if (
            !$this->nivelValido(
                $nivel
            )
            ||
            !$this->xpValido(
                $xp
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
                    INSERT INTO progresso
                    (
                        usuario_id,
                        modulo,
                        nivel,
                        xp,
                        atualizado_em
                    )

                    VALUES
                    (
                        :usuario,
                        :modulo,
                        :nivel,
                        :xp,
                        NOW()
                    )
                "
            );


        try {

            return $stmt->execute(
                [
                    ":usuario" =>
                        $usuarioId,

                    ":modulo" =>
                        $modulo,

                    ":nivel" =>
                        $nivel,

                    ":xp" =>
                        $xp
                ]
            ) === true;

        }
        catch (
            PDOException $erro
        ) {

            /*
            |--------------------------------------------------------------------------
            | PROGRESSO JÁ EXISTE
            |--------------------------------------------------------------------------
            */

            if (
                $this->progressoDuplicado(
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
    | ATUALIZAR PROGRESSO
    |--------------------------------------------------------------------------
    |
    | Substitui:
    |
    | - nível;
    | - XP total.
    |
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $usuarioId,
        string $modulo,
        int $nivel,
        int $xp
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
        | MÓDULO
        |--------------------------------------------------------------------------
        */

        $modulo =
            $this->normalizarModulo(
                $modulo
            );


        if (
            !$this->moduloValido(
                $modulo
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | VALORES
        |--------------------------------------------------------------------------
        */

        if (
            !$this->nivelValido(
                $nivel
            )
            ||
            !$this->xpValido(
                $xp
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
                    UPDATE progresso

                    SET
                        nivel = :nivel,
                        xp = :xp,
                        atualizado_em = NOW()

                    WHERE
                        usuario_id = :usuario
                        AND modulo = :modulo
                "
            );


        $resultado =
            $stmt->execute(
                [
                    ":nivel" =>
                        $nivel,

                    ":xp" =>
                        $xp,

                    ":usuario" =>
                        $usuarioId,

                    ":modulo" =>
                        $modulo
                ]
            );


        if (
            $resultado !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NÃO DEPENDEMOS DE rowCount()
        |--------------------------------------------------------------------------
        |
        | Se nível e XP forem iguais, o registro continua válido.
        |
        |--------------------------------------------------------------------------
        */

        return $this->existe(
            $usuarioId,
            $modulo
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ADICIONAR XP
    |--------------------------------------------------------------------------
    |
    | Esta operação é atômica no banco:
    |
    | xp = xp + valor
    |
    | Evitamos:
    |
    | SELECT xp
    | → PHP soma
    | → UPDATE
    |
    | que seria mais vulnerável a concorrência.
    |
    |--------------------------------------------------------------------------
    */

    public function adicionarXP(
        int $usuarioId,
        string $modulo,
        int $xp
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
        | MÓDULO
        |--------------------------------------------------------------------------
        */

        $modulo =
            $this->normalizarModulo(
                $modulo
            );


        if (
            !$this->moduloValido(
                $modulo
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | XP
        |--------------------------------------------------------------------------
        */

        if (
            !$this->xpAdicionalValido(
                $xp
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE ATÔMICO
        |--------------------------------------------------------------------------
        |
        | Também evitamos overflow do INT.
        |
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE progresso

                    SET
                        xp = xp + :xp,
                        atualizado_em = NOW()

                    WHERE
                        usuario_id = :usuario
                        AND modulo = :modulo
                        AND xp <= :limite
                "
            );


        /*
        |--------------------------------------------------------------------------
        | LIMITE PARA A SOMA
        |--------------------------------------------------------------------------
        */

        $limiteAtual =
            self::VALOR_MAXIMO
            -
            $xp;


        $stmt->bindValue(
            ":xp",
            $xp,
            PDO::PARAM_INT
        );


        $stmt->bindValue(
            ":usuario",
            $usuarioId,
            PDO::PARAM_INT
        );


        $stmt->bindValue(
            ":modulo",
            $modulo,
            PDO::PARAM_STR
        );


        $stmt->bindValue(
            ":limite",
            $limiteAtual,
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
        | XP > 0
        |--------------------------------------------------------------------------
        |
        | Se um registro válido foi encontrado, obrigatoriamente ocorre
        | alteração do XP.
        |
        |--------------------------------------------------------------------------
        */

        return
            $stmt->rowCount() === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR EXISTÊNCIA
    |--------------------------------------------------------------------------
    */

    public function existe(
        int $usuarioId,
        string $modulo
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
        | MÓDULO
        |--------------------------------------------------------------------------
        */

        $modulo =
            $this->normalizarModulo(
                $modulo
            );


        if (
            !$this->moduloValido(
                $modulo
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | CONSULTA
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM progresso

                    WHERE
                        usuario_id = :usuario
                        AND modulo = :modulo

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":usuario" =>
                    $usuarioId,

                ":modulo" =>
                    $modulo
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }

}