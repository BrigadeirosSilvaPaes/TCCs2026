<?php

require_once __DIR__ . "/../Models/Usuario.php";
require_once __DIR__ . "/../Services/EmailService.php";

class AuthController
{
    private $usuario;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        global $pdo;

        $this->usuario = new Usuario($pdo);
    }


    /*
    |--------------------------------------------------------------------------
    | CADASTRO
    |--------------------------------------------------------------------------
    */

    public function register()
    {
        $nome = trim($_POST["nome"] ?? "");
        $email = trim($_POST["email"] ?? "");

        $senha = $_POST["senha"] ?? "";
        $confirmarSenha = $_POST["confirmarSenha"] ?? "";

        $tipo = $_POST["tipo"] ?? "aluno";


        /*
        |--------------------------------------------------------------------------
        | CAMPOS OBRIGATÓRIOS
        |--------------------------------------------------------------------------
        */

        if (
            $nome === "" ||
            $email === "" ||
            $senha === "" ||
            $confirmarSenha === ""
        ) {
            header(
                "Location: ../public/criar.php?status=empty"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | E-MAIL
        |--------------------------------------------------------------------------
        */

        if (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            header(
                "Location: ../public/criar.php?status=email"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | TIPO DE CONTA
        |--------------------------------------------------------------------------
        |
        | Não permitimos criar administrador pelo cadastro público.
        |
        |--------------------------------------------------------------------------
        */

        $tiposPermitidos = [
            "aluno",
            "professor"
        ];

        if (
            !in_array(
                $tipo,
                $tiposPermitidos,
                true
            )
        ) {
            $tipo = "aluno";
        }


        /*
        |--------------------------------------------------------------------------
        | SENHA
        |--------------------------------------------------------------------------
        */

        if ($senha !== $confirmarSenha) {

            header(
                "Location: ../public/criar.php?status=password"
            );

            exit;
        }


        if (strlen($senha) < 6) {

            header(
                "Location: ../public/criar.php?status=passwordLength"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | E-MAIL JÁ CADASTRADO
        |--------------------------------------------------------------------------
        */

        if (
            $this->usuario->buscarPorEmail(
                $email
            )
        ) {
            header(
                "Location: ../public/criar.php?status=exists"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS DA CONTA
        |--------------------------------------------------------------------------
        |
        | Aluno:
        | ativo automaticamente.
        |
        | Professor:
        | precisa da aprovação do administrador.
        |
        |--------------------------------------------------------------------------
        */

        if ($tipo === "professor") {

            $status = "pendente";

        } else {

            $status = "ativo";
        }


        /*
        |--------------------------------------------------------------------------
        | CRIPTOGRAFIA
        |--------------------------------------------------------------------------
        */

        $senhaHash = password_hash(
            $senha,
            PASSWORD_DEFAULT
        );


        /*
        |--------------------------------------------------------------------------
        | CADASTRAR
        |--------------------------------------------------------------------------
        */

        $resultado =
            $this->usuario->cadastrar(
                $nome,
                $email,
                $senhaHash,
                $tipo,
                $status
            );


        if (!$resultado) {

            header(
                "Location: ../public/criar.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECIONAMENTO
        |--------------------------------------------------------------------------
        */

        if ($tipo === "professor") {

            header(
                "Location: ../public/login.php?status=teacherCreated"
            );

            exit;
        }


        header(
            "Location: ../public/login.php?status=success"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        $perfil = $_POST["perfil"] ?? "";

        $email = trim(
            $_POST["email"] ?? ""
        );

        $senha =
            $_POST["senha"] ?? "";


        /*
        |--------------------------------------------------------------------------
        | CAMPOS
        |--------------------------------------------------------------------------
        */

        if (
            $perfil === "" ||
            $email === "" ||
            $senha === ""
        ) {
            header(
                "Location: ../public/login.php?status=empty"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | PERFIS PERMITIDOS
        |--------------------------------------------------------------------------
        */

        $perfisPermitidos = [
            "aluno",
            "professor",
            "admin"
        ];


        if (
            !in_array(
                $perfil,
                $perfisPermitidos,
                true
            )
        ) {
            header(
                "Location: ../public/login.php?status=profile"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | BUSCAR USUÁRIO
        |--------------------------------------------------------------------------
        */

        $usuario =
            $this->usuario->buscarPorEmail(
                $email
            );


        if (!$usuario) {

            header(
                "Location: ../public/login.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | SENHA
        |--------------------------------------------------------------------------
        */

        if (
            !password_verify(
                $senha,
                $usuario["senha"]
            )
        ) {
            header(
                "Location: ../public/login.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | PERFIL ESCOLHIDO X PERFIL DA CONTA
        |--------------------------------------------------------------------------
        */

        if (
            $usuario["tipo"] !==
            $perfil
        ) {
            header(
                "Location: ../public/login.php?status=profile"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | ALUNO BLOQUEADO
        |--------------------------------------------------------------------------
        */

        if (
            $usuario["tipo"] === "aluno" &&
            (int) ($usuario["bloqueado"] ?? 0) === 1
        ) {
            header(
                "Location: ../public/login.php?status=blocked"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | PROFESSOR PENDENTE
        |--------------------------------------------------------------------------
        */

        if (
            $usuario["tipo"] === "professor" &&
            $usuario["status"] !== "ativo"
        ) {
            header(
                "Location: ../public/login.php?status=pending"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | SESSÃO
        |--------------------------------------------------------------------------
        */

        if (
            session_status() ===
            PHP_SESSION_NONE
        ) {
            session_start();
        }


        /*
        |--------------------------------------------------------------------------
        | REGENERA ID DA SESSÃO
        |--------------------------------------------------------------------------
        */

        session_regenerate_id(true);


        /*
        |--------------------------------------------------------------------------
        | DADOS DA SESSÃO
        |--------------------------------------------------------------------------
        |
        | IMPORTANTE:
        |
        | O status precisa ficar salvo porque páginas como:
        |
        | professor/painel.php
        | professor/materiais.php
        | professor/perfil.php
        |
        | verificam:
        |
        | $_SESSION["usuario"]["status"]
        |
        |--------------------------------------------------------------------------
        */

        $_SESSION["usuario"] = [

            "id" =>
                $usuario["id"],

            "nome" =>
                $usuario["nome"],

            "email" =>
                $usuario["email"],

            "tipo" =>
                $usuario["tipo"],

            "status" =>
                $usuario["status"]
        ];

        $_SESSION["usuario"]["bloqueado"] =
            (int) ($usuario["bloqueado"] ?? 0);


        /*
        |--------------------------------------------------------------------------
        | REDIRECIONAMENTO POR PERFIL
        |--------------------------------------------------------------------------
        */

        switch ($usuario["tipo"]) {

            case "admin":

                header(
                    "Location: ../public/admin/painel.php"
                );

                break;


            case "professor":

                header(
                    "Location: ../public/professor/painel.php"
                );

                break;


            case "aluno":

                header(
                    "Location: ../public/aluno/painel.php"
                );

                break;


            default:

                session_unset();
                session_destroy();

                header(
                    "Location: ../public/login.php?status=error"
                );

                break;
        }

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | ESQUECI A SENHA
    |--------------------------------------------------------------------------
    */

    public function forgotPassword()
    {
        $email =
            strtolower(
                trim(
                    $_POST["email"]
                    ?? ""
                )
            );


        /*
        |--------------------------------------------------------------------------
        | CAMPO VAZIO
        |--------------------------------------------------------------------------
        */

        if ($email === "") {

            header(
                "Location: ../public/esquece.php?status=empty"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | E-MAIL INVÁLIDO
        |--------------------------------------------------------------------------
        */

        if (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            header(
                "Location: ../public/esquece.php?status=email"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | SESSÃO
        |--------------------------------------------------------------------------
        */

        if (
            session_status()
            === PHP_SESSION_NONE
        ) {
            session_start();
        }


        /*
        |--------------------------------------------------------------------------
        | GUARDA O E-MAIL DURANTE O PROCESSO
        |--------------------------------------------------------------------------
        |
        | O e-mail não precisa aparecer na URL.
        |
        */

        $_SESSION[
            "recuperacao_senha_email"
        ] = $email;


        unset(
            $_SESSION[
                "redefinicao_senha_token"
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | BUSCAR USUÁRIO
        |--------------------------------------------------------------------------
        */

        $usuario =
            $this->usuario
                ->buscarPorEmail(
                    $email
                );


        /*
        |--------------------------------------------------------------------------
        | NÃO REVELAR SE O E-MAIL EXISTE
        |--------------------------------------------------------------------------
        |
        | Mesmo quando o e-mail não está cadastrado,
        | levamos o usuário para a mesma etapa.
        |
        */

        if (!$usuario) {

            header(
                "Location: ../public/verificar-codigo.php?status=sent"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | INVALIDAR RECUPERAÇÕES ANTERIORES
        |--------------------------------------------------------------------------
        */

        $this->usuario
            ->removerCodigosRecuperacaoDoUsuario(
                $usuario["id"]
            );


        $this->usuario
            ->removerAutorizacoesRedefinicaoDoUsuario(
                $usuario["id"]
            );


        /*
        |--------------------------------------------------------------------------
        | CÓDIGO DE SEIS DÍGITOS
        |--------------------------------------------------------------------------
        */

        try {

            $codigo =
                (string) random_int(
                    100000,
                    999999
                );

        }
        catch (Throwable $e) {

            header(
                "Location: ../public/esquece.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | HASH DO CÓDIGO
        |--------------------------------------------------------------------------
        |
        | Os seis números não ficam gravados em texto puro no banco.
        |
        */

        $codigoHash =
            password_hash(
                $codigo,
                PASSWORD_DEFAULT
            );


        if ($codigoHash === false) {

            header(
                "Location: ../public/esquece.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDADE: 10 MINUTOS
        |--------------------------------------------------------------------------
        */

        $expiracao =
            date(
                "Y-m-d H:i:s",
                time() + 600
            );


        /*
        |--------------------------------------------------------------------------
        | SALVAR CÓDIGO
        |--------------------------------------------------------------------------
        */

        $salvo =
            $this->usuario
                ->salvarCodigoRecuperacao(
                    $usuario["id"],
                    $codigoHash,
                    $expiracao
                );


        if (!$salvo) {

            header(
                "Location: ../public/esquece.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | ENVIAR E-MAIL
        |--------------------------------------------------------------------------
        */

        try {

            $emailService =
                new EmailService();


            $enviado =
                $emailService
                    ->enviarRecuperacao(
                        $usuario["email"],
                        $usuario["nome"],
                        $codigo
                    );


            if (!$enviado) {

                throw new RuntimeException(
                    "Falha no envio do código."
                );

            }

        }
        catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | REMOVE O CÓDIGO QUE NÃO FOI ENVIADO
            |--------------------------------------------------------------------------
            */

            $this->usuario
                ->removerCodigosRecuperacaoDoUsuario(
                    $usuario["id"]
                );


            header(
                "Location: ../public/esquece.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | IR PARA DIGITAÇÃO DO CÓDIGO
        |--------------------------------------------------------------------------
        */

        header(
            "Location: ../public/verificar-codigo.php?status=sent"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICAR CÓDIGO DE RECUPERAÇÃO
    |--------------------------------------------------------------------------
    */

    public function verifyRecoveryCode()
    {
        if (
            session_status()
            === PHP_SESSION_NONE
        ) {
            session_start();
        }


        $email =
            $_SESSION[
                "recuperacao_senha_email"
            ]
            ?? "";


        $codigo =
            trim(
                $_POST["codigo"]
                ?? ""
            );


        /*
        |--------------------------------------------------------------------------
        | PROCESSO NÃO INICIADO
        |--------------------------------------------------------------------------
        */

        if ($email === "") {

            header(
                "Location: ../public/esquece.php"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | FORMATO
        |--------------------------------------------------------------------------
        */

        if (
            !preg_match(
                "/^[0-9]{6}$/",
                $codigo
            )
        ) {

            header(
                "Location: ../public/verificar-codigo.php?status=invalid"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        $usuario =
            $this->usuario
                ->buscarPorEmail(
                    $email
                );


        if (!$usuario) {

            header(
                "Location: ../public/verificar-codigo.php?status=invalid"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | CÓDIGO ATIVO
        |--------------------------------------------------------------------------
        */

        $dadosCodigo =
            $this->usuario
                ->buscarCodigoRecuperacaoAtivo(
                    $usuario["id"]
                );


        if (!$dadosCodigo) {

            header(
                "Location: ../public/verificar-codigo.php?status=expired"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | COMPARAR CÓDIGO
        |--------------------------------------------------------------------------
        */

        if (
            !password_verify(
                $codigo,
                $dadosCodigo["codigo_hash"]
            )
        ) {

            $this->usuario
                ->incrementarTentativasCodigoRecuperacao(
                    $dadosCodigo["id"]
                );


            header(
                "Location: ../public/verificar-codigo.php?status=invalid"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | AUTORIZAÇÃO INTERNA
        |--------------------------------------------------------------------------
        |
        | Acertar o código NÃO altera a senha diretamente.
        |
        | Criamos uma autorização temporária de 15 minutos.
        |
        */

        try {

            $token =
                bin2hex(
                    random_bytes(32)
                );

        }
        catch (Throwable $e) {

            header(
                "Location: ../public/verificar-codigo.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | HASH DA AUTORIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $tokenHash =
            hash(
                "sha256",
                $token
            );


        $expiracao =
            date(
                "Y-m-d H:i:s",
                time() + 900
            );


        /*
        |--------------------------------------------------------------------------
        | INVALIDAR AUTORIZAÇÕES ANTIGAS
        |--------------------------------------------------------------------------
        */

        $this->usuario
            ->removerAutorizacoesRedefinicaoDoUsuario(
                $usuario["id"]
            );


        /*
        |--------------------------------------------------------------------------
        | SALVAR AUTORIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $salvo =
            $this->usuario
                ->salvarAutorizacaoRedefinicao(
                    $usuario["id"],
                    $tokenHash,
                    $expiracao
                );


        if (!$salvo) {

            header(
                "Location: ../public/verificar-codigo.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | CÓDIGO FOI UTILIZADO
        |--------------------------------------------------------------------------
        */

        $this->usuario
            ->marcarCodigoRecuperacaoComoUsado(
                $dadosCodigo["id"]
            );


        /*
        |--------------------------------------------------------------------------
        | PROTEGER A SESSÃO
        |--------------------------------------------------------------------------
        */

        session_regenerate_id(true);


        $_SESSION[
            "redefinicao_senha_token"
        ] = $token;


        unset(
            $_SESSION[
                "recuperacao_senha_email"
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | NOVA SENHA
        |--------------------------------------------------------------------------
        */

        header(
            "Location: ../public/novasenha.php"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | REDEFINIR SENHA
    |--------------------------------------------------------------------------
    */

    public function resetPassword()
    {
        if (
            session_status()
            === PHP_SESSION_NONE
        ) {
            session_start();
        }


        /*
        |--------------------------------------------------------------------------
        | AUTORIZAÇÃO DA SESSÃO
        |--------------------------------------------------------------------------
        */

        $token =
            $_SESSION[
                "redefinicao_senha_token"
            ]
            ?? "";


        if ($token === "") {

            header(
                "Location: ../public/esquece.php?status=expired"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | SENHAS
        |--------------------------------------------------------------------------
        */

        $senha =
            $_POST["senha"]
            ?? "";


        $confirmarSenha =
            $_POST["confirmarSenha"]
            ?? "";


        /*
        |--------------------------------------------------------------------------
        | CAMPOS
        |--------------------------------------------------------------------------
        */

        if (
            $senha === "" ||
            $confirmarSenha === ""
        ) {

            header(
                "Location: ../public/novasenha.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | SENHAS DIFERENTES
        |--------------------------------------------------------------------------
        */

        if (
            $senha !==
            $confirmarSenha
        ) {

            header(
                "Location: ../public/novasenha.php?status=password"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | TAMANHO
        |--------------------------------------------------------------------------
        */

        if (
            strlen($senha) < 6
        ) {

            header(
                "Location: ../public/novasenha.php?status=passwordLength"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | HASH DA AUTORIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $tokenHash =
            hash(
                "sha256",
                $token
            );


        /*
        |--------------------------------------------------------------------------
        | BUSCAR AUTORIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $autorizacao =
            $this->usuario
                ->buscarAutorizacaoRedefinicao(
                    $tokenHash
                );


        if (!$autorizacao) {

            unset(
                $_SESSION[
                    "redefinicao_senha_token"
                ]
            );


            header(
                "Location: ../public/esquece.php?status=expired"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | HASH DA NOVA SENHA
        |--------------------------------------------------------------------------
        */

        $senhaHash =
            password_hash(
                $senha,
                PASSWORD_DEFAULT
            );


        if ($senhaHash === false) {

            header(
                "Location: ../public/novasenha.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | ALTERAR SENHA
        |--------------------------------------------------------------------------
        */

        $atualizado =
            $this->usuario
                ->atualizarSenha(
                    $autorizacao["usuario_id"],
                    $senhaHash
                );


        if (!$atualizado) {

            header(
                "Location: ../public/novasenha.php?status=error"
            );

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | INVALIDAR AUTORIZAÇÃO
        |--------------------------------------------------------------------------
        */

        $this->usuario
            ->marcarAutorizacaoRedefinicaoComoUsada(
                $autorizacao["id"]
            );


        /*
        |--------------------------------------------------------------------------
        | LIMPAR RECUPERAÇÕES
        |--------------------------------------------------------------------------
        */

        $this->usuario
            ->removerCodigosRecuperacaoDoUsuario(
                $autorizacao["usuario_id"]
            );


        /*
        |--------------------------------------------------------------------------
        | LIMPAR SESSÃO DE RECUPERAÇÃO
        |--------------------------------------------------------------------------
        */

        unset(
            $_SESSION[
                "redefinicao_senha_token"
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        header(
            "Location: ../public/login.php?status=passwordChanged"
        );

        exit;
    }
}