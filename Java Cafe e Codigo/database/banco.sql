-- ========================================
-- JAVA, CAFÉ E CÓDIGO
-- BANCO DE DADOS COMPLETO
-- =======================================

CREATE DATABASE java_cafe_codigo
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;


USE java_cafe_codigo;



-- ========================================
-- USUÁRIOS
-- ========================================

CREATE TABLE usuarios (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL UNIQUE,

    senha VARCHAR(255) NOT NULL,

    ativo BOOLEAN NOT NULL DEFAULT FALSE,

    tipo_acesso ENUM(
        'usuario',
        'professor',
        'admin'
    ) NOT NULL DEFAULT 'usuario',

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    ultimo_login DATETIME NULL

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ========================================
-- TOKENS
-- CONFIRMAÇÃO E RECUPERAÇÃO DE SENHA
-- ========================================

CREATE TABLE tokens (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    tipo ENUM(
        'recuperacao',
        'confirmacao'
    ) NOT NULL,

    codigo VARCHAR(100) NOT NULL,

    expira_em DATETIME NOT NULL,

    utilizado BOOLEAN NOT NULL DEFAULT FALSE,

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_tokens_usuario (
        usuario_id
    ),

    INDEX idx_tokens_codigo (
        codigo
    ),

    CONSTRAINT fk_tokens_usuario

        FOREIGN KEY (
            usuario_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ========================================
-- PERFIL GERAL DO USUÁRIO
-- ========================================
--
-- Guarda apenas informações gerais:
--
-- - Tipo de perfil
-- - Estado
-- - Cidade
-- - Objetivo geral
-- - Situação de conclusão do perfil
--
-- O nível de programação do aprendiz fica
-- somente em perfil_aprendiz.experiencia.
--
-- ========================================

CREATE TABLE perfil_usuario (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    tipo_usuario ENUM(
        'estudante',
        'professor',
        'aprendiz'
    ) NOT NULL,

    estado VARCHAR(100) NOT NULL,

    cidade VARCHAR(100) NULL,

    objetivo VARCHAR(500) NULL,

    perfil_completo BOOLEAN NOT NULL
        DEFAULT FALSE,

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    atualizado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_perfil_usuario (
        usuario_id
    ),

    CONSTRAINT fk_perfil_usuario

        FOREIGN KEY (
            usuario_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ========================================
-- PERFIL DE ESTUDANTE
-- ========================================

CREATE TABLE perfil_estudante (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    instituicao ENUM(
        'brigadeiro',
        'estadual',
        'municipal',
        'privada',
        'superior'
    ) NOT NULL,

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    atualizado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_perfil_estudante (
        usuario_id
    ),

    CONSTRAINT fk_perfil_estudante_usuario

        FOREIGN KEY (
            usuario_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ========================================
-- PERFIL DE PROFESSOR
-- ========================================

CREATE TABLE perfil_professor (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    instituicao VARCHAR(150) NOT NULL,

    disciplina VARCHAR(100) NOT NULL,

    aprovado BOOLEAN NOT NULL
        DEFAULT FALSE,

    data_aprovacao DATETIME NULL,

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    atualizado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_perfil_professor (
        usuario_id
    ),

    CONSTRAINT fk_perfil_professor_usuario

        FOREIGN KEY (
            usuario_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ========================================
-- PERFIL DE APRENDIZ
-- ========================================
--
-- A experiência informada no questionário
-- será armazenada somente nesta tabela.
--
-- ========================================

CREATE TABLE perfil_aprendiz (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    experiencia ENUM(
        'nenhuma',
        'basica',
        'intermediaria',
        'avancada'
    ) NOT NULL DEFAULT 'nenhuma',

    objetivo TEXT NULL,

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    atualizado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_perfil_aprendiz (
        usuario_id
    ),

    CONSTRAINT fk_perfil_aprendiz_usuario

        FOREIGN KEY (
            usuario_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ========================================
-- CADERNO DE ANOTAÇÕES
-- ========================================

CREATE TABLE caderno_anotacoes (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    topico VARCHAR(100) NOT NULL,

    anotacao TEXT NOT NULL,

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    atualizado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_caderno_usuario_topico (
        usuario_id,
        topico
    ),

    CONSTRAINT fk_caderno_usuario

        FOREIGN KEY (
            usuario_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ========================================
-- PROGRESSO DO USUÁRIO
-- ========================================

CREATE TABLE progresso (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    modulo VARCHAR(100) NOT NULL,

    nivel INT NOT NULL DEFAULT 0,

    xp INT NOT NULL DEFAULT 0,

    atualizado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_progresso_usuario_modulo (
        usuario_id,
        modulo
    ),

    CONSTRAINT fk_progresso_usuario

        FOREIGN KEY (
            usuario_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ========================================
-- SALAS VIRTUAIS
-- ========================================

CREATE TABLE salas (

    id INT AUTO_INCREMENT PRIMARY KEY,

    professor_id INT NOT NULL,

    nome VARCHAR(100) NOT NULL,

    codigo VARCHAR(20) NOT NULL UNIQUE,

    linguagem VARCHAR(50) NULL,

    descricao TEXT NULL,

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    atualizado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_salas_professor (
        professor_id
    ),

    CONSTRAINT fk_salas_professor

        FOREIGN KEY (
            professor_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;



-- ========================================
-- ALUNOS NAS SALAS
-- ========================================

CREATE TABLE sala_alunos (

    id INT AUTO_INCREMENT PRIMARY KEY,

    sala_id INT NOT NULL,

    aluno_id INT NOT NULL,

    entrou_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uk_sala_aluno (
        sala_id,
        aluno_id
    ),

    INDEX idx_sala_alunos_aluno (
        aluno_id
    ),

    CONSTRAINT fk_sala_alunos_sala

        FOREIGN KEY (
            sala_id
        )

        REFERENCES salas (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE,

    CONSTRAINT fk_sala_alunos_usuario

        FOREIGN KEY (
            aluno_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


CREATE TABLE aula_progresso_conversado (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    aula_id INT NOT NULL,

    etapa_atual INT NOT NULL DEFAULT 1,

    concluida TINYINT(1)
        NOT NULL DEFAULT 0,

    atualizado_em TIMESTAMP
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE (
        usuario_id,
        aula_id
    ),

    FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE

);

-- ========================================
-- MENSAGENS DAS SALAS
-- ========================================

CREATE TABLE mensagens_sala (

    id INT AUTO_INCREMENT PRIMARY KEY,

    sala_id INT NOT NULL,

    usuario_id INT NOT NULL,

    mensagem TEXT NOT NULL,

    enviado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_mensagens_sala (
        sala_id
    ),

    INDEX idx_mensagens_usuario (
        usuario_id
    ),

    CONSTRAINT fk_mensagens_sala

        FOREIGN KEY (
            sala_id
        )

        REFERENCES salas (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE,

    CONSTRAINT fk_mensagens_usuario

        FOREIGN KEY (
            usuario_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
/* ==========================================================
   SALA GLOBAL
   Java, Café e Código
========================================================== */


/* ==========================================================
   PUBLICAÇÕES
========================================================== */

CREATE TABLE publicacoes_globais (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    titulo VARCHAR(150) NOT NULL,

    conteudo TEXT NULL,

    categoria ENUM(
        'dica',
        'explicacao',
        'codigo',
        'exercicio',
        'material',
        'video',
        'documentacao',
        'duvida',
        'outro'
    ) NOT NULL DEFAULT 'outro',

    status ENUM(
        'publicado',
        'oculto',
        'removido'
    ) NOT NULL DEFAULT 'publicado',

    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    atualizado_em DATETIME NULL DEFAULT NULL
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_publicacoes_usuario (usuario_id),

    INDEX idx_publicacoes_categoria (categoria),

    INDEX idx_publicacoes_status (status),

    INDEX idx_publicacoes_data (criado_em)

);


/* ==========================================================
   ARQUIVOS DAS PUBLICAÇÕES
========================================================== */

CREATE TABLE publicacoes_arquivos (

    id INT AUTO_INCREMENT PRIMARY KEY,

    publicacao_id INT NOT NULL,

    nome_original VARCHAR(255) NOT NULL,

    nome_arquivo VARCHAR(255) NOT NULL,

    extensao VARCHAR(20) NOT NULL,

    mime_type VARCHAR(120) NOT NULL,

    tamanho BIGINT NOT NULL,

    caminho VARCHAR(500) NOT NULL,

    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_arquivo_publicacao (publicacao_id),

    CONSTRAINT fk_arquivo_publicacao

        FOREIGN KEY (publicacao_id)

        REFERENCES publicacoes_globais(id)

        ON DELETE CASCADE

);


/* ==========================================================
   LINKS E VÍDEOS
========================================================== */

CREATE TABLE publicacoes_links (

    id INT AUTO_INCREMENT PRIMARY KEY,

    publicacao_id INT NOT NULL,

    url VARCHAR(1000) NOT NULL,

    titulo VARCHAR(200) NULL,

    tipo ENUM(
        'link',
        'video'
    ) NOT NULL DEFAULT 'link',

    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_link_publicacao (publicacao_id),

    CONSTRAINT fk_link_publicacao

        FOREIGN KEY (publicacao_id)

        REFERENCES publicacoes_globais(id)

        ON DELETE CASCADE

);


/* ==========================================================
   RESPOSTAS / DISCUSSÕES
========================================================== */

CREATE TABLE publicacoes_respostas (

    id INT AUTO_INCREMENT PRIMARY KEY,

    publicacao_id INT NOT NULL,

    usuario_id INT NOT NULL,

    mensagem TEXT NOT NULL,

    status ENUM(
        'publicado',
        'oculto',
        'removido'
    ) NOT NULL DEFAULT 'publicado',

    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    atualizado_em DATETIME NULL DEFAULT NULL
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_resposta_publicacao (publicacao_id),

    INDEX idx_resposta_usuario (usuario_id),

    CONSTRAINT fk_resposta_publicacao

        FOREIGN KEY (publicacao_id)

        REFERENCES publicacoes_globais(id)

        ON DELETE CASCADE

);


/* ==========================================================
   "ISSO ME AJUDOU"
========================================================== */

CREATE TABLE publicacoes_ajudas (

    id INT AUTO_INCREMENT PRIMARY KEY,

    publicacao_id INT NOT NULL,

    usuario_id INT NOT NULL,

    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unico_usuario_publicacao (
        publicacao_id,
        usuario_id
    ),

    INDEX idx_ajuda_publicacao (publicacao_id),

    CONSTRAINT fk_ajuda_publicacao

        FOREIGN KEY (publicacao_id)

        REFERENCES publicacoes_globais(id)

        ON DELETE CASCADE

);


/* ==========================================================
   DENÚNCIAS
========================================================== */

CREATE TABLE publicacoes_denuncias (

    id INT AUTO_INCREMENT PRIMARY KEY,

    publicacao_id INT NOT NULL,

    usuario_id INT NOT NULL,

    motivo VARCHAR(255) NOT NULL,

    descricao TEXT NULL,

    status ENUM(
        'pendente',
        'analisada',
        'ignorada'
    ) NOT NULL DEFAULT 'pendente',

    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_denuncia_publicacao (publicacao_id),

    INDEX idx_denuncia_status (status),

    CONSTRAINT fk_denuncia_publicacao

        FOREIGN KEY (publicacao_id)

        REFERENCES publicacoes_globais(id)

        ON DELETE CASCADE

);
ALTER TABLE perfil_usuario

ADD COLUMN nick_programador VARCHAR(50) NULL
AFTER objetivo,

ADD COLUMN biografia VARCHAR(300) NULL
AFTER nick_programador,

ADD COLUMN instagram VARCHAR(100) NULL
AFTER biografia,

ADD COLUMN email_contato VARCHAR(150) NULL
AFTER instagram;
CREATE TABLE assistente_historico (

    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    papel ENUM(
        'usuario',
        'assistente'
    ) NOT NULL,

    mensagem TEXT NOT NULL,

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_assistente_usuario (
        usuario_id
    ),

    INDEX idx_assistente_data (
        criado_em
    ),

    CONSTRAINT fk_assistente_historico_usuario

        FOREIGN KEY (
            usuario_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
ALTER TABLE usuarios
ADD COLUMN bloqueado BOOLEAN NOT NULL DEFAULT FALSE
AFTER ativo;
/* ==========================================================
   CONTEÚDOS DAS SALAS
   Java, Café e Código
========================================================== */

CREATE TABLE sala_conteudos (

    id INT AUTO_INCREMENT PRIMARY KEY,

    sala_id INT NOT NULL,

    usuario_id INT NOT NULL,

    titulo VARCHAR(150) NOT NULL,

    conteudo TEXT NULL,

    categoria ENUM(
        'aviso',
        'explicacao',
        'codigo',
        'exercicio',
        'material',
        'video',
        'link',
        'outro'
    ) NOT NULL DEFAULT 'material',

    status ENUM(
        'publicado',
        'rascunho',
        'oculto'
    ) NOT NULL DEFAULT 'publicado',

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    atualizado_em DATETIME NULL
        DEFAULT NULL
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_sala_conteudos_sala (
        sala_id
    ),

    INDEX idx_sala_conteudos_usuario (
        usuario_id
    ),

    INDEX idx_sala_conteudos_categoria (
        categoria
    ),

    INDEX idx_sala_conteudos_status (
        status
    ),

    INDEX idx_sala_conteudos_data (
        criado_em
    ),

    CONSTRAINT fk_sala_conteudos_sala

        FOREIGN KEY (
            sala_id
        )

        REFERENCES salas (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE,

    CONSTRAINT fk_sala_conteudos_usuario

        FOREIGN KEY (
            usuario_id
        )

        REFERENCES usuarios (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


/* ==========================================================
   ARQUIVOS DOS CONTEÚDOS DAS SALAS
========================================================== */

CREATE TABLE sala_conteudos_arquivos (

    id INT AUTO_INCREMENT PRIMARY KEY,

    conteudo_id INT NOT NULL,

    nome_original VARCHAR(255) NOT NULL,

    nome_arquivo VARCHAR(255) NOT NULL,

    extensao VARCHAR(20) NOT NULL,

    mime_type VARCHAR(120) NOT NULL,

    tamanho BIGINT NOT NULL,

    caminho VARCHAR(500) NOT NULL,

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_sala_conteudo_arquivo (
        conteudo_id
    ),

    CONSTRAINT fk_sala_conteudo_arquivo

        FOREIGN KEY (
            conteudo_id
        )

        REFERENCES sala_conteudos (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


/* ==========================================================
   LINKS E VÍDEOS DOS CONTEÚDOS DAS SALAS
========================================================== */

CREATE TABLE sala_conteudos_links (

    id INT AUTO_INCREMENT PRIMARY KEY,

    conteudo_id INT NOT NULL,

    url VARCHAR(1000) NOT NULL,

    titulo VARCHAR(200) NULL,

    tipo ENUM(
        'link',
        'video'
    ) NOT NULL DEFAULT 'link',

    criado_em DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_sala_conteudo_link (
        conteudo_id
    ),

    CONSTRAINT fk_sala_conteudo_link

        FOREIGN KEY (
            conteudo_id
        )

        REFERENCES sala_conteudos (
            id
        )

        ON DELETE CASCADE

        ON UPDATE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
CREATE TABLE admin_sessoes (

    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT NOT NULL,

    token_hash CHAR(64) NOT NULL,

    session_hash CHAR(64) NOT NULL,

    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    ultimo_uso DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    expira_em DATETIME NOT NULL,

    revogado_em DATETIME NULL,

    UNIQUE KEY uk_admin_token (token_hash),

    INDEX idx_admin_usuario (usuario_id),

    INDEX idx_admin_expiracao (expira_em),

    INDEX idx_admin_revogado (revogado_em),

    CONSTRAINT fk_admin_sessoes_usuario

        FOREIGN KEY (usuario_id)

        REFERENCES usuarios(id)

        ON DELETE CASCADE

);
