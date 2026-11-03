/*
|--------------------------------------------------------------------------
| TAMBORIM PARA TODOS - BANCO PARA HOSPEDAGEM
|--------------------------------------------------------------------------
|
| Crie primeiro um banco vazio no painel da hospedagem.
| Depois selecione esse banco no phpMyAdmin e importe este arquivo.
|
| Este arquivo NÃO executa CREATE DATABASE nem USE.
|
*/


/*
|--------------------------------------------------------------------------
| USUÁRIOS
|--------------------------------------------------------------------------
*/

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100) NOT NULL,

    email VARCHAR(120) NOT NULL UNIQUE,

    senha VARCHAR(255) NOT NULL,

    tipo ENUM(
        'aluno',
        'professor',
        'admin'
    ) NOT NULL DEFAULT 'aluno',

    status ENUM(
        'ativo',
        'pendente'
    ) NOT NULL DEFAULT 'ativo',

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/*
|--------------------------------------------------------------------------
| RECUPERAÇÃO DE SENHA POR CÓDIGO
|--------------------------------------------------------------------------
*/

CREATE TABLE recuperacao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    codigo_hash VARCHAR(255) NOT NULL,

    expiracao DATETIME NOT NULL,

    tentativas TINYINT UNSIGNED NOT NULL DEFAULT 0,

    usado BOOLEAN NOT NULL DEFAULT FALSE,

    usado_em DATETIME NULL,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_recuperacao_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);


/*
|--------------------------------------------------------------------------
| AUTORIZAÇÃO TEMPORÁRIA PARA REDEFINIÇÃO
|--------------------------------------------------------------------------
*/

CREATE TABLE autorizacoes_redefinicao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    token_hash CHAR(64) NOT NULL UNIQUE,

    expiracao DATETIME NOT NULL,

    usado BOOLEAN NOT NULL DEFAULT FALSE,

    usado_em DATETIME NULL,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_autorizacao_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);


/*
|--------------------------------------------------------------------------
| AULAS
|--------------------------------------------------------------------------
*/

CREATE TABLE aulas (
    id INT AUTO_INCREMENT PRIMARY KEY,

    professor_id INT NOT NULL,

    titulo VARCHAR(150) NOT NULL,

    descricao TEXT NOT NULL,

    conteudo LONGTEXT NULL,

    video_url VARCHAR(255) NULL,

    material_url VARCHAR(255) NULL,

    ordem INT NOT NULL DEFAULT 1,

    status ENUM(
        'rascunho',
        'publicada'
    ) NOT NULL DEFAULT 'rascunho',

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    atualizado_em TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_aulas_professor
        FOREIGN KEY (professor_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);


/*
|--------------------------------------------------------------------------
| PROGRESSO DAS AULAS
|--------------------------------------------------------------------------
*/

CREATE TABLE progresso_aulas (
    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    aula_id INT NOT NULL,

    concluida BOOLEAN NOT NULL DEFAULT FALSE,

    concluida_em DATETIME NULL,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    atualizado_em TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY progresso_unico (
        usuario_id,
        aula_id
    ),

    CONSTRAINT fk_progresso_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_progresso_aula
        FOREIGN KEY (aula_id)
        REFERENCES aulas(id)
        ON DELETE CASCADE
);


/*
|--------------------------------------------------------------------------
| MATERIAIS
|--------------------------------------------------------------------------
*/

CREATE TABLE materiais (
    id INT AUTO_INCREMENT PRIMARY KEY,

    professor_id INT NOT NULL,

    aula_id INT NULL,

    titulo VARCHAR(150) NOT NULL,

    descricao TEXT NULL,

    tipo ENUM(
        'video',
        'pdf',
        'documento',
        'imagem',
        'apresentacao',
        'arquivo',
        'link'
    ) NOT NULL,

    caminho VARCHAR(255) NULL,

    url VARCHAR(500) NULL,

    nome_original VARCHAR(255) NULL,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    atualizado_em TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_materiais_professor
        FOREIGN KEY (professor_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_materiais_aula
        FOREIGN KEY (aula_id)
        REFERENCES aulas(id)
        ON DELETE SET NULL
);


/*
|--------------------------------------------------------------------------
| ÍNDICES
|--------------------------------------------------------------------------
*/

CREATE INDEX idx_usuarios_tipo_status
ON usuarios(tipo, status);

CREATE INDEX idx_recuperacao_usuario
ON recuperacao_senha(usuario_id);

CREATE INDEX idx_recuperacao_validade
ON recuperacao_senha(
    usuario_id,
    usado,
    expiracao,
    tentativas
);

CREATE INDEX idx_autorizacao_usuario
ON autorizacoes_redefinicao_senha(usuario_id);

CREATE INDEX idx_autorizacao_validade
ON autorizacoes_redefinicao_senha(
    token_hash,
    usado,
    expiracao
);

CREATE INDEX idx_aulas_professor
ON aulas(professor_id);

CREATE INDEX idx_aulas_status
ON aulas(status);

CREATE INDEX idx_aulas_ordem
ON aulas(ordem);

CREATE INDEX idx_progresso_usuario
ON progresso_aulas(usuario_id);

CREATE INDEX idx_progresso_aula
ON progresso_aulas(aula_id);

CREATE INDEX idx_materiais_professor
ON materiais(professor_id);

CREATE INDEX idx_materiais_aula
ON materiais(aula_id);

CREATE INDEX idx_materiais_tipo
ON materiais(tipo);
