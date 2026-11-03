SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS autorizacoes_redefinicao_senha;
DROP TABLE IF EXISTS recuperacao_senha;

CREATE TABLE recuperacao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    codigo_hash VARCHAR(255) NOT NULL,

    expiracao DATETIME NOT NULL,

    tentativas TINYINT UNSIGNED NOT NULL DEFAULT 0,

    usado BOOLEAN NOT NULL DEFAULT FALSE,

    usado_em DATETIME NULL,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_recuperacao_usuario (
        usuario_id
    ),

    INDEX idx_recuperacao_validade (
        usuario_id,
        usado,
        expiracao
    ),

    CONSTRAINT fk_recuperacao_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);

CREATE TABLE autorizacoes_redefinicao_senha (
    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    token_hash CHAR(64) NOT NULL UNIQUE,

    expiracao DATETIME NOT NULL,

    usado BOOLEAN NOT NULL DEFAULT FALSE,

    usado_em DATETIME NULL,

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_autorizacao_usuario (
        usuario_id,
        usado,
        expiracao
    ),

    CONSTRAINT fk_autorizacao_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);

SET FOREIGN_KEY_CHECKS = 1;
