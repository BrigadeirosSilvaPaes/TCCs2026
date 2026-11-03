-- ============================================================
-- TAMBORIM PARA TODOS
-- BANCO COMPLETO / ATUALIZAÇÃO SEGURA PARA PHPMYADMIN
-- 05/09/2026
--
-- COMO USAR:
-- 1. No phpMyAdmin, selecione o banco do Tamborim.
-- 2. Clique em "Importar".
-- 3. Selecione este arquivo.
-- 4. Execute.
--
-- IMPORTANTE:
-- - NÃO cria nem seleciona um banco por nome.
-- - Pode ser usado no banco local "tamborim" ou no banco da hospedagem.
-- - Preserva usuarios, aulas, materiais e progresso já existentes.
-- - Recria somente as tabelas de recuperação de senha,
--   apagando apenas códigos/tokens temporários antigos.
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;


-- ============================================================
-- USUÁRIOS
-- ============================================================

CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,
    `email` VARCHAR(120) NOT NULL,
    `senha` VARCHAR(255) NOT NULL,
    `tipo` ENUM('aluno','professor','admin') NOT NULL DEFAULT 'aluno',
    `status` ENUM('ativo','pendente') NOT NULL DEFAULT 'ativo',
    `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_usuarios_email` (`email`),
    KEY `idx_usuarios_tipo_status` (`tipo`, `status`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- ============================================================
-- AULAS
-- ============================================================

CREATE TABLE IF NOT EXISTS `aulas` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `professor_id` INT NOT NULL,
    `titulo` VARCHAR(150) NOT NULL,
    `descricao` TEXT NOT NULL,
    `conteudo` LONGTEXT NULL,
    `video_url` VARCHAR(255) NULL,
    `material_url` VARCHAR(255) NULL,
    `ordem` INT NOT NULL DEFAULT 1,
    `status` ENUM('rascunho','publicada') NOT NULL DEFAULT 'rascunho',
    `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `atualizado_em` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_aulas_professor` (`professor_id`),
    KEY `idx_aulas_status` (`status`),
    KEY `idx_aulas_ordem` (`ordem`),

    CONSTRAINT `fk_aulas_professor`
        FOREIGN KEY (`professor_id`)
        REFERENCES `usuarios` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- ============================================================
-- PROGRESSO DAS AULAS
-- ============================================================

CREATE TABLE IF NOT EXISTS `progresso_aulas` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `usuario_id` INT NOT NULL,
    `aula_id` INT NOT NULL,
    `concluida` TINYINT(1) NOT NULL DEFAULT 0,
    `concluida_em` DATETIME NULL,
    `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `atualizado_em` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `progresso_unico` (`usuario_id`, `aula_id`),
    KEY `idx_progresso_usuario` (`usuario_id`),
    KEY `idx_progresso_aula` (`aula_id`),

    CONSTRAINT `fk_progresso_usuario`
        FOREIGN KEY (`usuario_id`)
        REFERENCES `usuarios` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT `fk_progresso_aula`
        FOREIGN KEY (`aula_id`)
        REFERENCES `aulas` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- ============================================================
-- MATERIAIS
-- ============================================================

CREATE TABLE IF NOT EXISTS `materiais` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `professor_id` INT NOT NULL,
    `aula_id` INT NULL,
    `titulo` VARCHAR(150) NOT NULL,
    `descricao` TEXT NULL,
    `tipo` ENUM(
        'video',
        'pdf',
        'documento',
        'imagem',
        'apresentacao',
        'arquivo',
        'link'
    ) NOT NULL,
    `caminho` VARCHAR(255) NULL,
    `url` VARCHAR(500) NULL,
    `nome_original` VARCHAR(255) NULL,
    `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `atualizado_em` TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_materiais_professor` (`professor_id`),
    KEY `idx_materiais_aula` (`aula_id`),
    KEY `idx_materiais_tipo` (`tipo`),

    CONSTRAINT `fk_materiais_professor`
        FOREIGN KEY (`professor_id`)
        REFERENCES `usuarios` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT `fk_materiais_aula`
        FOREIGN KEY (`aula_id`)
        REFERENCES `aulas` (`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


-- ============================================================
-- RECUPERAÇÃO DE SENHA
--
-- Estas duas tabelas são recriadas para garantir que o banco
-- antigo fique compatível com o fluxo atual de código de 6 dígitos.
-- Usuários, aulas, materiais e progresso NÃO são apagados.
-- ============================================================

DROP TABLE IF EXISTS `autorizacoes_redefinicao_senha`;
DROP TABLE IF EXISTS `recuperacao_senha`;


CREATE TABLE `recuperacao_senha` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `usuario_id` INT NOT NULL,
    `codigo_hash` VARCHAR(255) NOT NULL,
    `expiracao` DATETIME NOT NULL,
    `tentativas` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `usado` TINYINT(1) NOT NULL DEFAULT 0,
    `usado_em` DATETIME NULL,
    `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_recuperacao_usuario` (`usuario_id`),
    KEY `idx_recuperacao_validade`
        (`usuario_id`, `usado`, `expiracao`, `tentativas`),

    CONSTRAINT `fk_recuperacao_usuario`
        FOREIGN KEY (`usuario_id`)
        REFERENCES `usuarios` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


CREATE TABLE `autorizacoes_redefinicao_senha` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `usuario_id` INT NOT NULL,
    `token_hash` CHAR(64) NOT NULL,
    `expiracao` DATETIME NOT NULL,
    `usado` TINYINT(1) NOT NULL DEFAULT 0,
    `usado_em` DATETIME NULL,
    `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_autorizacao_token_hash` (`token_hash`),
    KEY `idx_autorizacao_usuario` (`usuario_id`),
    KEY `idx_autorizacao_validade`
        (`token_hash`, `usado`, `expiracao`),

    CONSTRAINT `fk_autorizacao_usuario`
        FOREIGN KEY (`usuario_id`)
        REFERENCES `usuarios` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;


SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- FIM
--
-- Estrutura esperada:
--   usuarios
--   aulas
--   progresso_aulas
--   materiais
--   recuperacao_senha
--   autorizacoes_redefinicao_senha
-- ============================================================
