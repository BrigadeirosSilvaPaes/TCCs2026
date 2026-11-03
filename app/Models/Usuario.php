<?php

class Usuario
{
    private $pdo;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    /*
    |--------------------------------------------------------------------------
    | CADASTRO
    |--------------------------------------------------------------------------
    |
    | $tipo pode ser:
    |
    | aluno
    | professor
    | admin
    |
    | $status pode ser:
    |
    | ativo
    | pendente
    |
    |--------------------------------------------------------------------------
    */

    public function cadastrar(
        $nome,
        $email,
        $senha,
        $tipo = "aluno",
        $status = "ativo"
    ) {
        $sql = "INSERT INTO usuarios (
                    nome,
                    email,
                    senha,
                    tipo,
                    status
                )
                VALUES (
                    :nome,
                    :email,
                    :senha,
                    :tipo,
                    :status
                )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":nome" => $nome,
            ":email" => $email,
            ":senha" => $senha,
            ":tipo" => $tipo,
            ":status" => $status
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR USUÁRIO POR E-MAIL
    |--------------------------------------------------------------------------
    */

    public function buscarPorEmail($email)
    {
        $sql = "SELECT *
                FROM usuarios
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":email" => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR USUÁRIO POR ID
    |--------------------------------------------------------------------------
    */

    public function buscarPorId($id)
    {
        $sql = "SELECT *
                FROM usuarios
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":id" => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR TODOS OS USUÁRIOS
    |--------------------------------------------------------------------------
    |
    | Útil futuramente para o administrador.
    |
    |--------------------------------------------------------------------------
    */

    public function listarTodos()
    {
        $sql = "SELECT
                    id,
                    nome,
                    email,
                    tipo,
                    status,
                    criado_em

                FROM usuarios

                ORDER BY criado_em DESC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | PROFESSORES
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | LISTAR PROFESSORES PENDENTES
    |--------------------------------------------------------------------------
    */

    public function listarProfessoresPendentes()
    {
        $sql = "SELECT
                    id,
                    nome,
                    email,
                    tipo,
                    status,
                    criado_em

                FROM usuarios

                WHERE tipo = 'professor'
                AND status = 'pendente'

                ORDER BY criado_em ASC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR PROFESSORES ATIVOS
    |--------------------------------------------------------------------------
    */

    public function listarProfessoresAtivos()
    {
        $sql = "SELECT
                    id,
                    nome,
                    email,
                    tipo,
                    status,
                    criado_em

                FROM usuarios

                WHERE tipo = 'professor'
                AND status = 'ativo'

                ORDER BY nome ASC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | APROVAR PROFESSOR
    |--------------------------------------------------------------------------
    |
    | O usuário precisa:
    |
    | tipo = professor
    | status = pendente
    |
    | Depois da aprovação:
    |
    | status = ativo
    |
    |--------------------------------------------------------------------------
    */

    public function aprovarProfessor($usuarioId)
    {
        $sql = "UPDATE usuarios

                SET status = 'ativo'

                WHERE id = :id
                AND tipo = 'professor'
                AND status = 'pendente'";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $usuarioId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DESATIVAR / VOLTAR PROFESSOR PARA PENDENTE
    |--------------------------------------------------------------------------
    |
    | Já deixamos pronto caso o administrador precise
    | retirar a autorização posteriormente.
    |
    |--------------------------------------------------------------------------
    */

    public function tornarProfessorPendente($usuarioId)
    {
        $sql = "UPDATE usuarios

                SET status = 'pendente'

                WHERE id = :id
                AND tipo = 'professor'";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $usuarioId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR PROFESSORES ATIVOS
    |--------------------------------------------------------------------------
    */

    public function contarProfessoresAtivos()
    {
        $sql = "SELECT COUNT(*) AS total

                FROM usuarios

                WHERE tipo = 'professor'
                AND status = 'ativo'";

        $stmt = $this->pdo->query($sql);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado["total"];
    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR PROFESSORES PENDENTES
    |--------------------------------------------------------------------------
    */

    public function contarProfessoresPendentes()
    {
        $sql = "SELECT COUNT(*) AS total

                FROM usuarios

                WHERE tipo = 'professor'
                AND status = 'pendente'";

        $stmt = $this->pdo->query($sql);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado["total"];
    }


    /*
    |--------------------------------------------------------------------------
    | ALUNOS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | LISTAR TODOS OS ALUNOS
    |--------------------------------------------------------------------------
    */

    public function listarAlunos()
    {
        $sql = "SELECT
                    id,
                    nome,
                    email,
                    tipo,
                    status,
                    criado_em

                FROM usuarios

                WHERE tipo = 'aluno'

                ORDER BY nome ASC";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | PESQUISAR ALUNOS
    |--------------------------------------------------------------------------
    |
    | Pesquisa por:
    |
    | nome
    | email
    |
    |--------------------------------------------------------------------------
    */

    public function pesquisarAlunos($termo)
    {
        $sql = "SELECT
                    id,
                    nome,
                    email,
                    tipo,
                    status,
                    criado_em

                FROM usuarios

                WHERE tipo = 'aluno'

                AND (
                    nome LIKE :termo
                    OR email LIKE :termo
                )

                ORDER BY nome ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":termo" => "%" . $termo . "%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR ALUNOS
    |--------------------------------------------------------------------------
    */

    public function contarAlunos()
    {
        $sql = "SELECT COUNT(*) AS total

                FROM usuarios

                WHERE tipo = 'aluno'";

        $stmt = $this->pdo->query($sql);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado["total"];
    }


    /*
    |--------------------------------------------------------------------------
    | RECUPERAÇÃO DE SENHA
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | SALVAR CÓDIGO DE RECUPERAÇÃO
    |--------------------------------------------------------------------------
    */

    public function salvarCodigoRecuperacao(
        $usuarioId,
        $codigoHash,
        $expiracao
    ) {
        $sql = "INSERT INTO recuperacao_senha (
                    usuario_id,
                    codigo_hash,
                    expiracao
                )
                VALUES (
                    :usuario_id,
                    :codigo_hash,
                    :expiracao
                )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":usuario_id" => $usuarioId,
            ":codigo_hash" => $codigoHash,
            ":expiracao" => $expiracao
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR CÓDIGO DE RECUPERAÇÃO ATIVO
    |--------------------------------------------------------------------------
    */

    public function buscarCodigoRecuperacaoAtivo(
        $usuarioId
    ) {
        $sql = "SELECT *
                FROM recuperacao_senha

                WHERE usuario_id = :usuario_id
                AND usado = FALSE
                AND expiracao > NOW()
                AND tentativas < 5

                ORDER BY id DESC

                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":usuario_id" => $usuarioId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | INCREMENTAR TENTATIVA
    |--------------------------------------------------------------------------
    |
    | Ao atingir cinco tentativas erradas, o código é automaticamente
    | inutilizado.
    |
    */

    public function incrementarTentativasCodigoRecuperacao(
        $codigoId
    ) {
        $sql = "UPDATE recuperacao_senha

                SET
                    tentativas = tentativas + 1,

                    usado =
                        CASE
                            WHEN tentativas + 1 >= 5
                            THEN TRUE
                            ELSE usado
                        END,

                    usado_em =
                        CASE
                            WHEN tentativas + 1 >= 5
                            THEN NOW()
                            ELSE usado_em
                        END

                WHERE id = :id
                AND usado = FALSE";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $codigoId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MARCAR CÓDIGO COMO UTILIZADO
    |--------------------------------------------------------------------------
    */

    public function marcarCodigoRecuperacaoComoUsado(
        $codigoId
    ) {
        $sql = "UPDATE recuperacao_senha

                SET
                    usado = TRUE,
                    usado_em = NOW()

                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $codigoId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER CÓDIGOS ANTERIORES
    |--------------------------------------------------------------------------
    */

    public function removerCodigosRecuperacaoDoUsuario(
        $usuarioId
    ) {
        $sql = "DELETE FROM recuperacao_senha

                WHERE usuario_id = :usuario_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":usuario_id" => $usuarioId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | SALVAR AUTORIZAÇÃO PARA REDEFINIR SENHA
    |--------------------------------------------------------------------------
    */

    public function salvarAutorizacaoRedefinicao(
        $usuarioId,
        $tokenHash,
        $expiracao
    ) {
        $sql = "INSERT INTO autorizacoes_redefinicao_senha (
                    usuario_id,
                    token_hash,
                    expiracao
                )
                VALUES (
                    :usuario_id,
                    :token_hash,
                    :expiracao
                )";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":usuario_id" => $usuarioId,
            ":token_hash" => $tokenHash,
            ":expiracao" => $expiracao
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR AUTORIZAÇÃO VÁLIDA
    |--------------------------------------------------------------------------
    */

    public function buscarAutorizacaoRedefinicao(
        $tokenHash
    ) {
        $sql = "SELECT *
                FROM autorizacoes_redefinicao_senha

                WHERE token_hash = :token_hash
                AND usado = FALSE
                AND expiracao > NOW()

                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ":token_hash" => $tokenHash
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /*
    |--------------------------------------------------------------------------
    | MARCAR AUTORIZAÇÃO COMO UTILIZADA
    |--------------------------------------------------------------------------
    */

    public function marcarAutorizacaoRedefinicaoComoUsada(
        $autorizacaoId
    ) {
        $sql = "UPDATE autorizacoes_redefinicao_senha

                SET
                    usado = TRUE,
                    usado_em = NOW()

                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $autorizacaoId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER AUTORIZAÇÕES ANTERIORES
    |--------------------------------------------------------------------------
    */

    public function removerAutorizacoesRedefinicaoDoUsuario(
        $usuarioId
    ) {
        $sql = "DELETE FROM autorizacoes_redefinicao_senha

                WHERE usuario_id = :usuario_id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":usuario_id" => $usuarioId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR SENHA
    |--------------------------------------------------------------------------
    */

    public function atualizarSenha(
        $usuarioId,
        $senha
    ) {
        $sql = "UPDATE usuarios

                SET senha = :senha

                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":senha" => $senha,
            ":id" => $usuarioId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ALIAS PARA ALTERAR SENHA
    |--------------------------------------------------------------------------
    */

    public function alterarSenha(
        $usuarioId,
        $senha
    ) {
        return $this->atualizarSenha(
            $usuarioId,
            $senha
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMINISTRAÇÃO DE USUÁRIOS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | ALTERAR STATUS
    |--------------------------------------------------------------------------
    */

    public function alterarStatus(
        $usuarioId,
        $status
    ) {
        $statusPermitidos = [
            "ativo",
            "pendente"
        ];

        if (
            !in_array(
                $status,
                $statusPermitidos,
                true
            )
        ) {
            return false;
        }

        $sql = "UPDATE usuarios

                SET status = :status

                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":status" => $status,
            ":id" => $usuarioId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ALTERAR TIPO
    |--------------------------------------------------------------------------
    */

    public function alterarTipo(
        $usuarioId,
        $tipo
    ) {
        $tiposPermitidos = [
            "aluno",
            "professor",
            "admin"
        ];

        if (
            !in_array(
                $tipo,
                $tiposPermitidos,
                true
            )
        ) {
            return false;
        }

        $sql = "UPDATE usuarios

                SET tipo = :tipo

                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":tipo" => $tipo,
            ":id" => $usuarioId
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function excluir($usuarioId)
    {
        $sql = "DELETE FROM usuarios
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":id" => $usuarioId
        ]);
    }
}