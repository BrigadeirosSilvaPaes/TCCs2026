<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: SALA
|
| Responsável pela persistência das Salas de Aula.
|
| Operações:
|
| - criar sala;
| - buscar por ID;
| - buscar por código;
| - listar salas do professor;
| - atualizar;
| - excluir;
| - verificar código existente.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class Sala
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
    | NORMALIZAR TEXTO CURTO
    |--------------------------------------------------------------------------
    */

    private function normalizarTextoCurto(
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
    | NORMALIZAR TEXTO OPCIONAL
    |--------------------------------------------------------------------------
    */

    private function normalizarTextoOpcional(
        ?string $texto
    ): ?string {

        if (
            $texto === null
        ) {

            return null;

        }


        $texto =
            $this->normalizarTextoCurto(
                $texto
            );


        return
            $texto !== ""
                ?
                $texto
                :
                null;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR DESCRIÇÃO
    |--------------------------------------------------------------------------
    */

    private function normalizarDescricao(
        ?string $descricao
    ): ?string {

        if (
            $descricao === null
        ) {

            return null;

        }


        $descricao =
            str_replace(
                [
                    "\r\n",
                    "\r"
                ],
                "\n",
                $descricao
            );


        $descricao =
            trim(
                $descricao
            );


        return
            $descricao !== ""
                ?
                $descricao
                :
                null;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR CÓDIGO
    |--------------------------------------------------------------------------
    */

    private function normalizarCodigo(
        string $codigo
    ): string {

        return strtoupper(
            trim(
                $codigo
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | TEXTO CURTO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function textoCurtoValido(
        string $texto
    ): bool {

        if (
            $texto === ""
        ) {

            return false;

        }


        if (
            !$this->utf8Valido(
                $texto
            )
        ) {

            return false;

        }


        return preg_match(
            "/[\x00-\x1F\x7F]/",
            $texto
        ) !== 1;

    }


    /*
    |--------------------------------------------------------------------------
    | TEXTO LONGO VÁLIDO
    |--------------------------------------------------------------------------
    |
    | Permite:
    |
    | - TAB;
    | - quebra de linha.
    |
    |--------------------------------------------------------------------------
    */

    private function textoLongoValido(
        ?string $texto
    ): bool {

        if (
            $texto === null
        ) {

            return true;

        }


        if (
            !$this->utf8Valido(
                $texto
            )
        ) {

            return false;

        }


        return preg_match(
            "/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/",
            $texto
        ) !== 1;

    }


    /*
    |--------------------------------------------------------------------------
    | CÓDIGO DUPLICADO?
    |--------------------------------------------------------------------------
    |
    | A proteção definitiva precisa existir no banco:
    |
    | UNIQUE(codigo)
    |
    |--------------------------------------------------------------------------
    */

    private function codigoDuplicado(
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
    | CRIAR SALA
    |--------------------------------------------------------------------------
    */

    public function criar(
        int $professorId,
        string $nome,
        string $codigo,
        ?string $linguagem = null,
        ?string $descricao = null
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | PROFESSOR
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $professorId
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $nome =
            $this->normalizarTextoCurto(
                $nome
            );


        $codigo =
            $this->normalizarCodigo(
                $codigo
            );


        $linguagem =
            $this->normalizarTextoOpcional(
                $linguagem
            );


        $descricao =
            $this->normalizarDescricao(
                $descricao
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÕES
        |--------------------------------------------------------------------------
        */

        if (
            !$this->textoCurtoValido(
                $nome
            )
            ||
            !$this->textoCurtoValido(
                $codigo
            )
        ) {

            return false;

        }


        if (
            $linguagem !== null
            &&
            !$this->textoCurtoValido(
                $linguagem
            )
        ) {

            return false;

        }


        if (
            !$this->textoLongoValido(
                $descricao
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        |
        | SalaController já faz uma verificação amigável de código.
        |
        | Mesmo assim, a garantia definitiva contra duas requisições
        | simultâneas precisa ser UNIQUE(codigo) no banco.
        |
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    INSERT INTO salas
                    (
                        professor_id,
                        nome,
                        codigo,
                        linguagem,
                        descricao
                    )

                    VALUES
                    (
                        :professor,
                        :nome,
                        :codigo,
                        :linguagem,
                        :descricao
                    )
                "
            );


        try {

            return $stmt->execute(
                [
                    ":professor" =>
                        $professorId,

                    ":nome" =>
                        $nome,

                    ":codigo" =>
                        $codigo,

                    ":linguagem" =>
                        $linguagem,

                    ":descricao" =>
                        $descricao
                ]
            ) === true;

        }
        catch (
            PDOException $erro
        ) {

            /*
            |--------------------------------------------------------------------------
            | CÓDIGO JÁ EXISTE
            |--------------------------------------------------------------------------
            */

            if (
                $this->codigoDuplicado(
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
    | BUSCAR POR ID
    |--------------------------------------------------------------------------
    |
    | Mantemos SELECT * temporariamente.
    |
    | As páginas atuais recebem o array da sala e ainda vamos
    | conferir o schema completo antes de fechar explicitamente
    | todas as colunas disponíveis.
    |
    |--------------------------------------------------------------------------
    */

    public function buscarPorId(
        int $id
    ): array|false {

        if (
            !$this->idValido(
                $id
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        *

                    FROM salas

                    WHERE
                        id = :id

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":id" =>
                    $id
            ]
        );


        $sala =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $sala
            )
                ?
                $sala
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR POR CÓDIGO
    |--------------------------------------------------------------------------
    */

    public function buscarPorCodigo(
        string $codigo
    ): array|false {

        $codigo =
            $this->normalizarCodigo(
                $codigo
            );


        if (
            !$this->textoCurtoValido(
                $codigo
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        *

                    FROM salas

                    WHERE
                        codigo = :codigo

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":codigo" =>
                    $codigo
            ]
        );


        $sala =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $sala
            )
                ?
                $sala
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR SALAS DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function listarPorProfessor(
        int $professorId
    ): array {

        if (
            !$this->idValido(
                $professorId
            )
        ) {

            return [];

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        *

                    FROM salas

                    WHERE
                        professor_id = :professor

                    ORDER BY
                        criado_em DESC,
                        id DESC
                "
            );


        $stmt->execute(
            [
                ":professor" =>
                    $professorId
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
    | ATUALIZAR SALA
    |--------------------------------------------------------------------------
    |
    | O professor proprietário é validado pelo SalaController.
    |
    | O Model atualiza somente os dados da sala.
    |
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $id,
        string $nome,
        ?string $linguagem,
        ?string $descricao
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        if (
            !$this->idValido(
                $id
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $nome =
            $this->normalizarTextoCurto(
                $nome
            );


        $linguagem =
            $this->normalizarTextoOpcional(
                $linguagem
            );


        $descricao =
            $this->normalizarDescricao(
                $descricao
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÕES
        |--------------------------------------------------------------------------
        */

        if (
            !$this->textoCurtoValido(
                $nome
            )
        ) {

            return false;

        }


        if (
            $linguagem !== null
            &&
            !$this->textoCurtoValido(
                $linguagem
            )
        ) {

            return false;

        }


        if (
            !$this->textoLongoValido(
                $descricao
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
                    UPDATE salas

                    SET
                        nome = :nome,
                        linguagem = :linguagem,
                        descricao = :descricao

                    WHERE
                        id = :id
                "
            );


        $resultado =
            $stmt->execute(
                [
                    ":nome" =>
                        $nome,

                    ":linguagem" =>
                        $linguagem,

                    ":descricao" =>
                        $descricao,

                    ":id" =>
                        $id
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
        | Se os dados forem iguais aos atuais, MySQL pode informar
        | zero linhas modificadas.
        |
        |--------------------------------------------------------------------------
        */

        return
            $this->buscarPorId(
                $id
            )
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR SALA
    |--------------------------------------------------------------------------
    |
    | ATENÇÃO:
    |
    | Este método exclui somente o registro da sala no banco.
    |
    | A limpeza dos arquivos físicos vinculados aos conteúdos precisa
    | ser coordenada em uma camada superior.
    |
    |--------------------------------------------------------------------------
    */

    public function excluir(
        int $id
    ): bool {

        if (
            !$this->idValido(
                $id
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    DELETE FROM salas

                    WHERE
                        id = :id
                "
            );


        if (
            $stmt->execute(
                [
                    ":id" =>
                        $id
                ]
            )
            !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | DELETE PRECISA REMOVER EXATAMENTE UMA SALA
        |--------------------------------------------------------------------------
        */

        return
            $stmt->rowCount() === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | CÓDIGO JÁ EXISTE?
    |--------------------------------------------------------------------------
    */

    public function existeCodigo(
        string $codigo
    ): bool {

        $codigo =
            $this->normalizarCodigo(
                $codigo
            );


        if (
            !$this->textoCurtoValido(
                $codigo
            )
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM salas

                    WHERE
                        codigo = :codigo

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":codigo" =>
                    $codigo
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }

}