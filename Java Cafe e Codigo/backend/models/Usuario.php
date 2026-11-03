<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| MODEL: USUÁRIO
|
| Responsável exclusivamente pela persistência da tabela "usuarios".
|
| Campos principais:
|
| - id;
| - nome;
| - email;
| - senha;
| - ativo;
| - bloqueado;
| - tipo_acesso;
| - criado_em;
| - ultimo_login.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";


class Usuario
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
    | NORMALIZAR E-MAIL
    |--------------------------------------------------------------------------
    */

    private function normalizarEmail(
        string $email
    ): string {

        $email =
            trim(
                $email
            );


        if (
            function_exists(
                "mb_strtolower"
            )
        ) {

            return mb_strtolower(
                $email,
                "UTF-8"
            );

        }


        return strtolower(
            $email
        );

    }


    /*
    |--------------------------------------------------------------------------
    | TIPO DE ACESSO VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function tipoAcessoValido(
        string $tipo
    ): bool {

        return in_array(
            $tipo,
            [
                ACCESS_USUARIO,
                ACCESS_PROFESSOR,
                ACCESS_ADMIN
            ],
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | E-MAIL DUPLICADO?
    |--------------------------------------------------------------------------
    |
    | O Controller normalmente verifica o e-mail antes do INSERT.
    |
    | Porém essa verificação sozinha não protege contra concorrência:
    |
    | requisição A verifica → livre
    | requisição B verifica → livre
    | requisição A insere
    | requisição B tenta inserir
    |
    | Por isso a proteção definitiva deve ser UNIQUE(email) no banco.
    |
    | Aqui identificamos especificamente a violação de chave duplicada.
    |
    |--------------------------------------------------------------------------
    */

    private function emailDuplicado(
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
    | CRIAR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function criar(
        string $nome,
        string $email,
        string $senhaHash,
        string $tipo = ACCESS_USUARIO
    ): int|false {

        /*
        |--------------------------------------------------------------------------
        | NORMALIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $nome =
            trim(
                $nome
            );


        $email =
            $this->normalizarEmail(
                $email
            );


        $senhaHash =
            trim(
                $senhaHash
            );


        $tipo =
            strtolower(
                trim(
                    $tipo
                )
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO BÁSICA
        |--------------------------------------------------------------------------
        |
        | As regras completas continuam no Controller.
        |
        | O Model mantém apenas uma última barreira contra dados
        | estruturalmente inválidos.
        |
        |--------------------------------------------------------------------------
        */

        if (
            $nome === ""
            ||
            $email === ""
            ||
            $senhaHash === ""
            ||
            !$this->tipoAcessoValido(
                $tipo
            )
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        |
        | Não fazemos SELECT antes do INSERT aqui.
        |
        | AuthController já faz a checagem amigável.
        |
        | A garantia contra duplicação concorrente pertence ao
        | UNIQUE(email) do banco.
        |
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    INSERT INTO usuarios
                    (
                        nome,
                        email,
                        senha,
                        tipo_acesso
                    )

                    VALUES
                    (
                        :nome,
                        :email,
                        :senha,
                        :tipo
                    )
                "
            );


        try {

            $resultado =
                $stmt->execute(
                    [
                        ":nome" =>
                            $nome,

                        ":email" =>
                            $email,

                        ":senha" =>
                            $senhaHash,

                        ":tipo" =>
                            $tipo
                    ]
                );

        }
        catch (
            PDOException $erro
        ) {

            /*
            |--------------------------------------------------------------------------
            | DUPLICAÇÃO DE E-MAIL
            |--------------------------------------------------------------------------
            */

            if (
                $this->emailDuplicado(
                    $erro
                )
            ) {

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | OUTROS ERROS DEVEM SUBIR
            |--------------------------------------------------------------------------
            */

            throw $erro;

        }


        if (
            $resultado !== true
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | ID GERADO
        |--------------------------------------------------------------------------
        */

        $id =
            (int) $this->pdo->lastInsertId();


        return
            $id > 0
                ?
                $id
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR POR ID
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


        /*
        |--------------------------------------------------------------------------
        | COLUNAS EXPLÍCITAS
        |--------------------------------------------------------------------------
        |
        | Mantemos "senha" por compatibilidade interna.
        |
        | A exposição desse campo deve ser controlada pela camada
        | que utiliza o Model.
        |
        |--------------------------------------------------------------------------
        */

        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        id,
                        nome,
                        email,
                        senha,
                        ativo,
                        bloqueado,
                        tipo_acesso,
                        criado_em,
                        ultimo_login

                    FROM usuarios

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


        $usuario =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $usuario
            )
                ?
                $usuario
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR POR E-MAIL
    |--------------------------------------------------------------------------
    |
    | Este método é utilizado principalmente pela autenticação.
    |
    | Por isso precisa retornar:
    |
    | - senha;
    | - ativo;
    | - bloqueado;
    | - tipo_acesso.
    |
    |--------------------------------------------------------------------------
    */

    public function buscarPorEmail(
        string $email
    ): array|false {

        $email =
            $this->normalizarEmail(
                $email
            );


        if (
            $email === ""
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        id,
                        nome,
                        email,
                        senha,
                        ativo,
                        bloqueado,
                        tipo_acesso,
                        criado_em,
                        ultimo_login

                    FROM usuarios

                    WHERE
                        email = :email

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":email" =>
                    $email
            ]
        );


        $usuario =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $usuario
            )
                ?
                $usuario
                :
                false;

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR SE E-MAIL EXISTE
    |--------------------------------------------------------------------------
    |
    | Não precisamos carregar:
    |
    | - senha;
    | - nome;
    | - permissões;
    | - demais dados.
    |
    |--------------------------------------------------------------------------
    */

    public function emailExiste(
        string $email
    ): bool {

        $email =
            $this->normalizarEmail(
                $email
            );


        if (
            $email === ""
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    SELECT
                        1

                    FROM usuarios

                    WHERE
                        email = :email

                    LIMIT 1
                "
            );


        $stmt->execute(
            [
                ":email" =>
                    $email
            ]
        );


        return
            $stmt->fetchColumn()
            !== false;

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR ÚLTIMO LOGIN
    |--------------------------------------------------------------------------
    */

    public function atualizarUltimoLogin(
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
                    UPDATE usuarios

                    SET
                        ultimo_login = NOW()

                    WHERE
                        id = :id
                "
            );


        return $stmt->execute(
            [
                ":id" =>
                    $id
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR / ATIVAR E-MAIL
    |--------------------------------------------------------------------------
    |
    | ATENÇÃO:
    |
    | ativo = confirmação do endereço de e-mail.
    |
    | ativo = 0
    | → e-mail ainda não confirmado.
    |
    | ativo = 1
    | → e-mail confirmado.
    |
    | Este método NÃO controla bloqueio administrativo.
    |
    |--------------------------------------------------------------------------
    */

    public function ativar(
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
                    UPDATE usuarios

                    SET
                        ativo = 1

                    WHERE
                        id = :id
                "
            );


        return $stmt->execute(
            [
                ":id" =>
                    $id
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR SENHA
    |--------------------------------------------------------------------------
    |
    | O Model recebe apenas um hash já criado.
    |
    | Ele nunca recebe senha em texto puro.
    |
    |--------------------------------------------------------------------------
    */

    public function atualizarSenha(
        int $id,
        string $senhaHash
    ): bool {

        if (
            !$this->idValido(
                $id
            )
        ) {

            return false;

        }


        $senhaHash =
            trim(
                $senhaHash
            );


        if (
            $senhaHash === ""
        ) {

            return false;

        }


        $stmt =
            $this->pdo->prepare(
                "
                    UPDATE usuarios

                    SET
                        senha = :senha

                    WHERE
                        id = :id
                "
            );


        return $stmt->execute(
            [
                ":senha" =>
                    $senhaHash,

                ":id" =>
                    $id
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR USUÁRIOS
    |--------------------------------------------------------------------------
    |
    | Mantido temporariamente porque ainda vamos auditar o módulo Admin.
    |
    | Este método nunca retorna o hash de senha.
    |
    |--------------------------------------------------------------------------
    */

    public function listar(): array {

        $stmt =
            $this->pdo->query(
                "
                    SELECT
                        id,
                        nome,
                        email,
                        ativo,
                        bloqueado,
                        tipo_acesso,
                        criado_em,
                        ultimo_login

                    FROM usuarios

                    ORDER BY
                        nome ASC,
                        id ASC
                "
            );


        $usuarios =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        return
            is_array(
                $usuarios
            )
                ?
                $usuarios
                :
                [];

    }


    /*
    |--------------------------------------------------------------------------
    | ALTERAR STATUS DE CONFIRMAÇÃO
    |--------------------------------------------------------------------------
    |
    | 🟡 MÉTODO LEGADO
    |
    | Não confundir com bloqueio administrativo.
    |
    | ativo:
    |
    | 0 = e-mail não confirmado
    | 1 = e-mail confirmado
    |
    | Mantemos por enquanto até terminar a auditoria do módulo Admin.
    |
    |--------------------------------------------------------------------------
    */

    public function alterarStatus(
        int $id,
        bool $status
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
                    UPDATE usuarios

                    SET
                        ativo = :ativo

                    WHERE
                        id = :id
                "
            );


        return $stmt->execute(
            [
                ":ativo" =>
                    $status
                        ?
                        1
                        :
                        0,

                ":id" =>
                    $id
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ALTERAR BLOQUEIO ADMINISTRATIVO
    |--------------------------------------------------------------------------
    |
    | bloqueado = 0
    | → conta liberada.
    |
    | bloqueado = 1
    | → conta bloqueada pelo administrador.
    |
    | Este método NÃO altera "ativo".
    |
    |--------------------------------------------------------------------------
    */

    public function alterarBloqueio(
        int $id,
        bool $bloqueado
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
                    UPDATE usuarios

                    SET
                        bloqueado = :bloqueado

                    WHERE
                        id = :id
                "
            );


        return $stmt->execute(
            [
                ":bloqueado" =>
                    $bloqueado
                        ?
                        1
                        :
                        0,

                ":id" =>
                    $id
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR USUÁRIO
    |--------------------------------------------------------------------------
    |
    | 🟡 REVISAR NO BLOCO ADMIN
    |
    | Trata-se de DELETE físico.
    |
    | Antes de mantermos qualquer tela que utilize este método em produção,
    | precisaremos conferir:
    |
    | - foreign keys;
    | - ON DELETE CASCADE;
    | - publicações;
    | - progresso;
    | - salas;
    | - arquivos;
    | - histórico do Assistente;
    | - exigências de retenção.
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
                    DELETE FROM usuarios

                    WHERE
                        id = :id
                "
            );


        return $stmt->execute(
            [
                ":id" =>
                    $id
            ]
        );

    }

}