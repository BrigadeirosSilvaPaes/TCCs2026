<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| CONTROLLER DE AUTENTICAÇÃO
|
| Responsável por:
|
| - cadastro;
| - confirmação de e-mail;
| - login;
| - recuperação de senha;
| - validação do código de recuperação;
| - redefinição de senha.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . "/../config/helpers.php";

require_once __DIR__ . "/../models/Usuario.php";
require_once __DIR__ . "/../models/Token.php";

require_once __DIR__ . "/../services/EmailService.php";


/*
|--------------------------------------------------------------------------
| CONTROLLER
|--------------------------------------------------------------------------
*/

class AuthController
{

    /*
    |--------------------------------------------------------------------------
    | BANCO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | MODELS
    |--------------------------------------------------------------------------
    */

    private Usuario $usuario;

    private Token $token;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {

        global $pdo;


        /*
        |--------------------------------------------------------------------------
        | PDO
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | MODELS
        |--------------------------------------------------------------------------
        */

        $this->usuario =
            new Usuario();


        $this->token =
            new Token();

    }


    /*
    |--------------------------------------------------------------------------
    | ALGORITMO DE SENHA
    |--------------------------------------------------------------------------
    */

    private function algoritmoSenha(): string|int
    {

        if (
            defined(
                "PASSWORD_ALGORITHM"
            )
        ) {

            return PASSWORD_ALGORITHM;

        }


        return PASSWORD_DEFAULT;

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICA EXPIRAÇÃO DO TOKEN
    |--------------------------------------------------------------------------
    */

    private function tokenExpirado(
        array $token
    ): bool
    {

        $expiraEm =
            $token["expira_em"]
            ??
            null;


        if (
            !is_string(
                $expiraEm
            )
            ||
            trim(
                $expiraEm
            )
            === ""
        ) {

            return true;

        }


        $timestamp =
            strtotime(
                $expiraEm
            );


        if (
            $timestamp ===
            false
        ) {

            error_log(
                "[AuthController] Token possui data de expiração inválida."
            );


            return true;

        }


        return
            $timestamp <=
            time();

    }


    /*
    |--------------------------------------------------------------------------
    | ID DO TOKEN
    |--------------------------------------------------------------------------
    */

    private function tokenId(
        array $token
    ): int
    {

        $id =
            filter_var(
                $token["id"]
                ??
                null,
                FILTER_VALIDATE_INT,
                [
                    "options" => [
                        "min_range" => 1
                    ]
                ]
            );


        if (
            $id === false
            ||
            $id === null
        ) {

            return 0;

        }


        return (int) $id;

    }


    /*
    |--------------------------------------------------------------------------
    | INICIA TRANSAÇÃO
    |--------------------------------------------------------------------------
    |
    | Retorna true somente quando este Controller foi responsável
    | por iniciar a transação.
    |
    |--------------------------------------------------------------------------
    */

    private function iniciarTransacao(): bool
    {

        if (
            $this->pdo->inTransaction()
        ) {

            return false;

        }


        if (
            !$this->pdo->beginTransaction()
        ) {

            throw new RuntimeException(
                "Não foi possível iniciar a transação."
            );

        }


        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | COMMIT
    |--------------------------------------------------------------------------
    */

    private function confirmarTransacao(
        bool $iniciadaAqui
    ): void
    {

        if (
            !$iniciadaAqui
        ) {

            return;

        }


        if (
            !$this->pdo->inTransaction()
        ) {

            throw new RuntimeException(
                "A transação foi encerrada inesperadamente antes do commit."
            );

        }


        if (
            !$this->pdo->commit()
        ) {

            throw new RuntimeException(
                "Não foi possível confirmar a transação."
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | ROLLBACK
    |--------------------------------------------------------------------------
    */

    private function desfazerTransacao(
        bool $iniciadaAqui
    ): void
    {

        if (
            !$iniciadaAqui
            ||
            !$this->pdo->inTransaction()
        ) {

            return;

        }


        try {

            $this->pdo->rollBack();

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[AuthController] Falha ao executar rollback: "
                .
                $erro->getMessage()
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CADASTRAR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function cadastrar(
        string $nome,
        string $email,
        string $senha,
        string $tipo = ACCESS_USUARIO
    ): array
    {

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
            trim(
                $email
            );


        if (
            function_exists(
                "mb_strtolower"
            )
        ) {

            $email =
                mb_strtolower(
                    $email,
                    "UTF-8"
                );

        }
        else {

            $email =
                strtolower(
                    $email
                );

        }


        $tipo =
            strtolower(
                trim(
                    $tipo
                )
            );


        /*
        |--------------------------------------------------------------------------
        | TIPO DE ACESSO
        |--------------------------------------------------------------------------
        |
        | O formulário público continua enviando apenas ACCESS_USUARIO.
        |
        | Esta whitelist impede que um valor arbitrário chegue ao banco.
        |
        |--------------------------------------------------------------------------
        */

        $tiposPermitidos = [
            ACCESS_USUARIO,
            ACCESS_PROFESSOR,
            ACCESS_ADMIN
        ];


        if (
            !in_array(
                $tipo,
                $tiposPermitidos,
                true
            )
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Erro ao criar usuário."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | E-MAIL JÁ EXISTE
        |--------------------------------------------------------------------------
        */

        if (
            $this->usuario->buscarPorEmail(
                $email
            )
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Email já cadastrado."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | HASH
        |--------------------------------------------------------------------------
        */

        $senhaHash =
            password_hash(
                $senha,
                $this->algoritmoSenha()
            );


        if (
            !is_string(
                $senhaHash
            )
            ||
            $senhaHash === ""
        ) {

            error_log(
                "[AuthController] password_hash() falhou durante cadastro."
            );


            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Erro ao criar usuário."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | TRANSAÇÃO
        |--------------------------------------------------------------------------
        |
        | Criamos usuário e token como uma única operação lógica.
        |
        | Neste projeto também fazemos o envio do e-mail antes do commit.
        |
        | Motivo:
        |
        | se o SMTP falhar, o cadastro é desfeito e o visitante pode
        | tentar criar a conta novamente, em vez de ficar preso com
        | um e-mail cadastrado mas sem receber o código.
        |
        |--------------------------------------------------------------------------
        */

        $transacaoIniciada =
            false;


        try {

            $transacaoIniciada =
                $this->iniciarTransacao();


            /*
            |--------------------------------------------------------------------------
            | CRIA USUÁRIO
            |--------------------------------------------------------------------------
            */

            $usuarioId =
                $this->usuario->criar(
                    $nome,
                    $email,
                    $senhaHash,
                    $tipo
                );


            $usuarioId =
                (int) $usuarioId;


            if (
                $usuarioId <=
                0
            ) {

                $this->desfazerTransacao(
                    $transacaoIniciada
                );


                return [
                    "status" =>
                        false,

                    "mensagem" =>
                        "Erro ao criar usuário."
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | GERA CÓDIGO
            |--------------------------------------------------------------------------
            */

            $codigo =
                $this->token->gerarCodigo();


            if (
                !is_string(
                    $codigo
                )
                ||
                preg_match(
                    "/^[0-9]{6}$/D",
                    $codigo
                )
                !== 1
            ) {

                throw new RuntimeException(
                    "Código de confirmação gerado em formato inválido."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | SALVA TOKEN
            |--------------------------------------------------------------------------
            */

            if (
                !$this->token->criar(
                    $usuarioId,
                    "confirmacao",
                    $codigo
                )
            ) {

                $this->desfazerTransacao(
                    $transacaoIniciada
                );


                return [
                    "status" =>
                        false,

                    "mensagem" =>
                        "Erro ao gerar confirmação."
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | ENVIA E-MAIL
            |--------------------------------------------------------------------------
            */

            if (
                !EmailService::enviarCodigo(
                    $email,
                    $codigo
                )
            ) {

                $this->desfazerTransacao(
                    $transacaoIniciada
                );


                return [
                    "status" =>
                        false,

                    "mensagem" =>
                        "Usuário criado, mas erro ao enviar código."
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            $this->confirmarTransacao(
                $transacaoIniciada
            );


            return [
                "status" =>
                    true,

                "usuario_id" =>
                    $usuarioId,

                "mensagem" =>
                    "Cadastro realizado. Confirme seu email."
            ];

        }
        catch (
            Throwable $erro
        ) {

            $this->desfazerTransacao(
                $transacaoIniciada
            );


            error_log(
                "[AuthController][Cadastro] "
                .
                $erro->getMessage()
            );


            throw $erro;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR E-MAIL
    |--------------------------------------------------------------------------
    */

    public function confirmarEmail(
        int $usuarioId,
        string $codigo
    ): array
    {

        if (
            $usuarioId <=
            0
            ||
            preg_match(
                "/^[0-9]{6}$/D",
                $codigo
            )
            !== 1
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Código inválido."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | LOCALIZA TOKEN
        |--------------------------------------------------------------------------
        */

        $token =
            $this->token->buscarCodigo(
                $usuarioId,
                "confirmacao",
                $codigo
            );


        if (
            !is_array(
                $token
            )
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Código inválido."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | EXPIRAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            $this->tokenExpirado(
                $token
            )
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Código expirado."
            ];

        }


        $tokenId =
            $this->tokenId(
                $token
            );


        if (
            $tokenId <=
            0
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Código inválido."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | TRANSAÇÃO
        |--------------------------------------------------------------------------
        |
        | Ativar a conta e consumir o token precisam acontecer juntos.
        |
        |--------------------------------------------------------------------------
        */

        $transacaoIniciada =
            false;


        try {

            $transacaoIniciada =
                $this->iniciarTransacao();


            /*
            |--------------------------------------------------------------------------
            | ATIVA CONTA
            |--------------------------------------------------------------------------
            */

            if (
                !$this->usuario->ativar(
                    $usuarioId
                )
            ) {

                $this->desfazerTransacao(
                    $transacaoIniciada
                );


                return [
                    "status" =>
                        false,

                    "mensagem" =>
                        "Erro ao ativar usuário."
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | CONSOME TOKEN
            |--------------------------------------------------------------------------
            */

            if (
                !$this->token->utilizar(
                    $tokenId
                )
            ) {

                $this->desfazerTransacao(
                    $transacaoIniciada
                );


                error_log(
                    "[AuthController][Confirmacao] Não foi possível consumir token do usuário "
                    .
                    $usuarioId
                    .
                    "."
                );


                return [
                    "status" =>
                        false,

                    "mensagem" =>
                        "Erro ao ativar usuário."
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            $this->confirmarTransacao(
                $transacaoIniciada
            );


            return [
                "status" =>
                    true,

                "mensagem" =>
                    "Email confirmado com sucesso."
            ];

        }
        catch (
            Throwable $erro
        ) {

            $this->desfazerTransacao(
                $transacaoIniciada
            );


            error_log(
                "[AuthController][Confirmacao] Usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );


            throw $erro;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR USUÁRIO POR E-MAIL
    |--------------------------------------------------------------------------
    */

    public function usuarioPorEmail(
        string $email
    ): array|false
    {

        $email =
            trim(
                $email
            );


        if (
            function_exists(
                "mb_strtolower"
            )
        ) {

            $email =
                mb_strtolower(
                    $email,
                    "UTF-8"
                );

        }
        else {

            $email =
                strtolower(
                    $email
                );

        }


        $usuario =
            $this->usuario->buscarPorEmail(
                $email
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
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(
        string $email,
        string $senha
    ): array
    {

        /*
        |--------------------------------------------------------------------------
        | NORMALIZA E-MAIL
        |--------------------------------------------------------------------------
        */

        $email =
            trim(
                $email
            );


        if (
            function_exists(
                "mb_strtolower"
            )
        ) {

            $email =
                mb_strtolower(
                    $email,
                    "UTF-8"
                );

        }
        else {

            $email =
                strtolower(
                    $email
                );

        }


        /*
        |--------------------------------------------------------------------------
        | BUSCA USUÁRIO
        |--------------------------------------------------------------------------
        */

        $usuario =
            $this->usuario->buscarPorEmail(
                $email
            );


        if (
            !is_array(
                $usuario
            )
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Email ou senha inválidos."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | HASH ARMAZENADO
        |--------------------------------------------------------------------------
        */

        $senhaHash =
            $usuario["senha"]
            ??
            null;


        if (
            !is_string(
                $senhaHash
            )
            ||
            $senhaHash === ""
        ) {

            error_log(
                "[AuthController][Login] Usuário possui hash de senha inválido."
            );


            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Email ou senha inválidos."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | VERIFICA SENHA
        |--------------------------------------------------------------------------
        */

        if (
            !password_verify(
                $senha,
                $senhaHash
            )
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Email ou senha inválidos."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRMAÇÃO DO E-MAIL
        |--------------------------------------------------------------------------
        */

        if (
            (int) (
                $usuario["ativo"]
                ??
                0
            )
            !== 1
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Confirme seu email antes de entrar."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | BLOQUEIO ADMINISTRATIVO
        |--------------------------------------------------------------------------
        */

        if (
            (int) (
                $usuario["bloqueado"]
                ??
                0
            )
            === 1
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Conta bloqueada administrativamente."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | ID
        |--------------------------------------------------------------------------
        */

        $usuarioId =
            (int) (
                $usuario["id"]
                ??
                0
            );


        if (
            $usuarioId <=
            0
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Email ou senha inválidos."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | REHASH AUTOMÁTICO
        |--------------------------------------------------------------------------
        |
        | Se PASSWORD_ALGORITHM mudar futuramente, a senha pode ser
        | atualizada para o algoritmo mais novo durante um login válido.
        |
        | Uma falha nesta manutenção não impede o login.
        |
        |--------------------------------------------------------------------------
        */

        try {

            if (
                password_needs_rehash(
                    $senhaHash,
                    $this->algoritmoSenha()
                )
            ) {

                $novoHash =
                    password_hash(
                        $senha,
                        $this->algoritmoSenha()
                    );


                if (
                    is_string(
                        $novoHash
                    )
                    &&
                    $novoHash !== ""
                ) {

                    if (
                        !$this->usuario->atualizarSenha(
                            $usuarioId,
                            $novoHash
                        )
                    ) {

                        error_log(
                            "[AuthController][Login] Não foi possível atualizar o hash do usuário "
                            .
                            $usuarioId
                            .
                            "."
                        );

                    }

                }

            }

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[AuthController][Login] Falha no rehash do usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ÚLTIMO LOGIN
        |--------------------------------------------------------------------------
        |
        | Este registro é secundário.
        |
        | Se falhar, não bloqueamos um usuário que já apresentou
        | credenciais válidas.
        |
        |--------------------------------------------------------------------------
        */

        try {

            $atualizado =
                $this->usuario->atualizarUltimoLogin(
                    $usuarioId
                );


            if (
                $atualizado ===
                false
            ) {

                error_log(
                    "[AuthController][Login] Não foi possível registrar último login do usuário "
                    .
                    $usuarioId
                    .
                    "."
                );

            }

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[AuthController][Login] Falha ao registrar último login do usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );

        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE HASH DO RESULTADO
        |--------------------------------------------------------------------------
        */

        unset(
            $usuario["senha"]
        );


        /*
        |--------------------------------------------------------------------------
        | SUCESSO
        |--------------------------------------------------------------------------
        */

        return [
            "status" =>
                true,

            "mensagem" =>
                "Login realizado com sucesso.",

            "usuario" =>
                $usuario
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | SOLICITAR RECUPERAÇÃO DE SENHA
    |--------------------------------------------------------------------------
    */

    public function solicitarRecuperacao(
        string $email
    ): array
    {

        /*
        |--------------------------------------------------------------------------
        | NORMALIZA E-MAIL
        |--------------------------------------------------------------------------
        */

        $email =
            trim(
                $email
            );


        if (
            function_exists(
                "mb_strtolower"
            )
        ) {

            $email =
                mb_strtolower(
                    $email,
                    "UTF-8"
                );

        }
        else {

            $email =
                strtolower(
                    $email
                );

        }


        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        $usuario =
            $this->usuario->buscarPorEmail(
                $email
            );


        if (
            !is_array(
                $usuario
            )
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Email não encontrado."
            ];

        }


        $usuarioId =
            (int) (
                $usuario["id"]
                ??
                0
            );


        if (
            $usuarioId <=
            0
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Email não encontrado."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | CÓDIGO
        |--------------------------------------------------------------------------
        */

        $codigo =
            $this->token->gerarCodigo();


        if (
            !is_string(
                $codigo
            )
            ||
            preg_match(
                "/^[0-9]{6}$/D",
                $codigo
            )
            !== 1
        ) {

            error_log(
                "[AuthController][Recuperacao] Código gerado em formato inválido."
            );


            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Erro ao criar código."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | TRANSAÇÃO
        |--------------------------------------------------------------------------
        |
        | Se o token for criado, mas o e-mail não sair,
        | o token desta tentativa não deve permanecer válido.
        |
        |--------------------------------------------------------------------------
        */

        $transacaoIniciada =
            false;


        try {

            $transacaoIniciada =
                $this->iniciarTransacao();


            /*
            |--------------------------------------------------------------------------
            | TOKEN
            |--------------------------------------------------------------------------
            */

            if (
                !$this->token->criar(
                    $usuarioId,
                    "recuperacao",
                    $codigo
                )
            ) {

                $this->desfazerTransacao(
                    $transacaoIniciada
                );


                return [
                    "status" =>
                        false,

                    "mensagem" =>
                        "Erro ao criar código."
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | E-MAIL
            |--------------------------------------------------------------------------
            */

            if (
                !EmailService::enviarRecuperacaoSenha(
                    $email,
                    $codigo
                )
            ) {

                $this->desfazerTransacao(
                    $transacaoIniciada
                );


                return [
                    "status" =>
                        false,

                    "mensagem" =>
                        "Erro ao enviar email."
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            $this->confirmarTransacao(
                $transacaoIniciada
            );


            return [
                "status" =>
                    true,

                "mensagem" =>
                    "Código enviado."
            ];

        }
        catch (
            Throwable $erro
        ) {

            $this->desfazerTransacao(
                $transacaoIniciada
            );


            error_log(
                "[AuthController][Recuperacao] Usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );


            throw $erro;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR CÓDIGO DE RECUPERAÇÃO
    |--------------------------------------------------------------------------
    */

    public function verificarCodigoRecuperacao(
        int $usuarioId,
        string $codigo
    ): bool
    {

        if (
            $usuarioId <=
            0
            ||
            preg_match(
                "/^[0-9]{6}$/D",
                $codigo
            )
            !== 1
        ) {

            return false;

        }


        $token =
            $this->token->buscarCodigo(
                $usuarioId,
                "recuperacao",
                $codigo
            );


        if (
            !is_array(
                $token
            )
        ) {

            return false;

        }


        if (
            $this->tokenExpirado(
                $token
            )
        ) {

            return false;

        }


        return
            $this->tokenId(
                $token
            )
            >
            0;

    }


    /*
    |--------------------------------------------------------------------------
    | REDEFINIR SENHA
    |--------------------------------------------------------------------------
    */

    public function redefinirSenha(
        int $usuarioId,
        string $codigo,
        string $novaSenha
    ): array
    {

        /*
        |--------------------------------------------------------------------------
        | ENTRADA
        |--------------------------------------------------------------------------
        */

        if (
            $usuarioId <=
            0
            ||
            preg_match(
                "/^[0-9]{6}$/D",
                $codigo
            )
            !== 1
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Código inválido."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | TOKEN
        |--------------------------------------------------------------------------
        */

        $token =
            $this->token->buscarCodigo(
                $usuarioId,
                "recuperacao",
                $codigo
            );


        if (
            !is_array(
                $token
            )
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Código inválido."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | EXPIRAÇÃO
        |--------------------------------------------------------------------------
        */

        if (
            $this->tokenExpirado(
                $token
            )
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Código expirado."
            ];

        }


        $tokenId =
            $this->tokenId(
                $token
            );


        if (
            $tokenId <=
            0
        ) {

            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Código inválido."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | HASH
        |--------------------------------------------------------------------------
        */

        $senhaHash =
            password_hash(
                $novaSenha,
                $this->algoritmoSenha()
            );


        if (
            !is_string(
                $senhaHash
            )
            ||
            $senhaHash === ""
        ) {

            error_log(
                "[AuthController][Redefinicao] password_hash() falhou para usuário "
                .
                $usuarioId
                .
                "."
            );


            return [
                "status" =>
                    false,

                "mensagem" =>
                    "Erro ao atualizar senha."
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | TRANSAÇÃO
        |--------------------------------------------------------------------------
        |
        | Nova senha e consumo do token precisam ser confirmados juntos.
        |
        |--------------------------------------------------------------------------
        */

        $transacaoIniciada =
            false;


        try {

            $transacaoIniciada =
                $this->iniciarTransacao();


            /*
            |--------------------------------------------------------------------------
            | ATUALIZA SENHA
            |--------------------------------------------------------------------------
            */

            if (
                !$this->usuario->atualizarSenha(
                    $usuarioId,
                    $senhaHash
                )
            ) {

                $this->desfazerTransacao(
                    $transacaoIniciada
                );


                return [
                    "status" =>
                        false,

                    "mensagem" =>
                        "Erro ao atualizar senha."
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | CONSOME TOKEN
            |--------------------------------------------------------------------------
            */

            if (
                !$this->token->utilizar(
                    $tokenId
                )
            ) {

                $this->desfazerTransacao(
                    $transacaoIniciada
                );


                error_log(
                    "[AuthController][Redefinicao] Senha não foi confirmada porque o token do usuário "
                    .
                    $usuarioId
                    .
                    " não pôde ser consumido."
                );


                return [
                    "status" =>
                        false,

                    "mensagem" =>
                        "Erro ao atualizar senha."
                ];

            }


            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            $this->confirmarTransacao(
                $transacaoIniciada
            );


            return [
                "status" =>
                    true,

                "mensagem" =>
                    "Senha alterada com sucesso."
            ];

        }
        catch (
            Throwable $erro
        ) {

            $this->desfazerTransacao(
                $transacaoIniciada
            );


            error_log(
                "[AuthController][Redefinicao] Usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );


            throw $erro;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | PODE REDEFINIR SENHA?
    |--------------------------------------------------------------------------
    |
    | Mantemos este método por compatibilidade com páginas que possam
    | consultá-lo.
    |
    | Ele não inicia mais session_start() diretamente.
    |
    |--------------------------------------------------------------------------
    */

    public function podeRedefinirSenha(): bool
    {

        garantirSessao();


        $usuarioId =
            (int) (
                $_SESSION["usuario_id_recuperacao"]
                ??
                0
            );


        $codigo =
            $_SESSION["codigo_recuperacao"]
            ??
            null;


        $validado =
            $_SESSION["codigo_recuperacao_validado"]
            ??
            false;


        $validadoEm =
            (int) (
                $_SESSION["codigo_recuperacao_validado_em"]
                ??
                0
            );


        if (
            $usuarioId <=
            0
            ||
            $validado !==
            true
            ||
            !is_string(
                $codigo
            )
            ||
            preg_match(
                "/^[0-9]{6}$/D",
                $codigo
            )
            !== 1
            ||
            $validadoEm <=
            0
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | MESMO LIMITE DA ETAPA DE NOVA SENHA
        |--------------------------------------------------------------------------
        */

        $agora =
            time();


        if (
            $validadoEm >
            ($agora + 60)
            ||
            ($agora - $validadoEm)
            >
            (15 * 60)
        ) {

            return false;

        }


        return true;

    }

}