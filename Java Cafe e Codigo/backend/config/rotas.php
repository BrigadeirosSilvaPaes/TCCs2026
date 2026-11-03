<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| CATÁLOGO CENTRAL DE ROTAS
|
| Cada rota pode definir:
|
| - uri: URL pública;
| - arquivo: arquivo físico responsável;
| - metodos: métodos HTTP permitidos;
| - parametros: validação dos parâmetros dinâmicos;
| - query: parâmetros que o router repassa para $_GET;
| - arquivo_dinamico: resolução controlada de arquivos dinâmicos.
|
|--------------------------------------------------------------------------
*/

return [


    /*
    |--------------------------------------------------------------------------
    | PÁGINA INICIAL
    |--------------------------------------------------------------------------
    */

    "inicio" => [

        "uri" =>
            "/",

        "arquivo" =>
            "frontend/index.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | AUTENTICAÇÃO — PÁGINAS
    |--------------------------------------------------------------------------
    */

    "login" => [

        "uri" =>
            "/login",

        "arquivo" =>
            "frontend/pages/login/login.php",

        "metodos" => [
            "GET"
        ]

    ],


    "cadastro" => [

        "uri" =>
            "/cadastro",

        "arquivo" =>
            "frontend/pages/cadastro/cadastro.php",

        "metodos" => [
            "GET"
        ]

    ],


    "confirmar_email" => [

        "uri" =>
            "/confirmar-email",

        "arquivo" =>
            "frontend/pages/cadastro/confirmar.php",

        "metodos" => [
            "GET"
        ]

    ],


    "recuperar_senha" => [

        "uri" =>
            "/recuperar-senha",

        "arquivo" =>
            "frontend/pages/recuperacao/esqueceu.php",

        "metodos" => [
            "GET"
        ]

    ],


    "recuperar_codigo" => [

        "uri" =>
            "/recuperar-senha/codigo",

        "arquivo" =>
            "frontend/pages/recuperacao/verificar_codigo.php",

        "metodos" => [
            "GET"
        ]

    ],


    "nova_senha" => [

        "uri" =>
            "/recuperar-senha/nova-senha",

        "arquivo" =>
            "frontend/pages/recuperacao/nova_senha.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | MENSAGENS
    |--------------------------------------------------------------------------
    */

    "mensagem" => [

        "uri" =>
            "/mensagem",

        "arquivo" =>
            "frontend/pages/mensagem/mensagem.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | PERFIL — ÁREA GERAL
    |--------------------------------------------------------------------------
    */

    "painel" => [

        "uri" =>
            "/painel",

        "arquivo" =>
            "frontend/pages/perfil/inicio.php",

        "metodos" => [
            "GET"
        ]

    ],


    "perfil.escolher" => [

        "uri" =>
            "/escolher-perfil",

        "arquivo" =>
            "frontend/pages/perfil/escolher.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | ESTUDANTE
    |--------------------------------------------------------------------------
    */

    "estudante.inicio" => [

        "uri" =>
            "/estudante",

        "arquivo" =>
            "frontend/pages/perfil/estudante/estudante.php",

        "metodos" => [
            "GET"
        ]

    ],


    "estudante.painel" => [

        "uri" =>
            "/estudante/painel",

        "arquivo" =>
            "frontend/pages/perfil/estudante/estudante_painel.php",

        /*
        | POST permanece temporariamente permitido.
        | Confirmaremos sua necessidade ao auditar o painel do estudante.
        */

        "metodos" => [
            "GET",
            "POST"
        ]

    ],


    "estudante.perfil" => [

        "uri" =>
            "/estudante/perfil",

        "arquivo" =>
            "frontend/pages/perfil/estudante/perfil.php",

        "metodos" => [
            "GET"
        ]

    ],


    "estudante.editar" => [

        "uri" =>
            "/estudante/perfil/editar",

        "arquivo" =>
            "frontend/pages/perfil/estudante/editar.php",

        "metodos" => [
            "GET"
        ]

    ],


    "estudante.questionario" => [

        "uri" =>
            "/estudante/completar-perfil",

        "arquivo" =>
            "frontend/pages/perfil/estudante/estudante_questionario.php",

        "metodos" => [
            "GET"
        ]

    ],


    "estudante.como_funciona" => [

        "uri" =>
            "/estudante/como-funciona",

        "arquivo" =>
            "frontend/pages/perfil/estudante/como_funciona.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | SALAS — ESTUDANTE
    |--------------------------------------------------------------------------
    */

    "estudante.salas" => [

        "uri" =>
            "/estudante/salas",

        "arquivo" =>
            "frontend/pages/perfil/estudante/salas/index.php",

        "metodos" => [
            "GET",
            "POST"
        ]

    ],


    "estudante.sala" => [

        "uri" =>
            "/estudante/salas/{id}",

        "arquivo" =>
            "frontend/pages/perfil/estudante/salas/sala.php",

        "metodos" => [
            "GET",
            "POST"
        ],

        "parametros" => [

            "id" =>
                "[1-9][0-9]*"

        ],

        "query" => [

            "id" =>
                "id"

        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | ARQUIVO PROTEGIDO — SALA DO ESTUDANTE
    |--------------------------------------------------------------------------
    |
    | Exemplo:
    |
    | /estudante/salas/12/arquivo/37
    |
    | O arquivo não é entregue diretamente da pasta de uploads.
    |
    |--------------------------------------------------------------------------
    */

    "estudante.sala.arquivo" => [

        "uri" =>
            "/estudante/salas/{sala_id}/arquivo/{arquivo_id}",

        "arquivo" =>
            "backend/api/sala_arquivo.php",

        "metodos" => [
            "GET"
        ],

        "parametros" => [

            "sala_id" =>
                "[1-9][0-9]*",

            "arquivo_id" =>
                "[1-9][0-9]*"

        ],

        "query" => [

            "sala_id" =>
                "sala_id",

            "arquivo_id" =>
                "arquivo_id"

        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | ASSISTENTE JAVA
    |--------------------------------------------------------------------------
    |
    | O modelo de IA executa no navegador.
    |
    | O backend cuida apenas de:
    |
    | - histórico;
    | - armazenamento das interações;
    | - limpeza da conversa.
    |
    |--------------------------------------------------------------------------
    */

    "assistente.historico" => [

        "uri" =>
            "/assistente/historico",

        "arquivo" =>
            "backend/assistente/historico.php",

        "metodos" => [
            "GET"
        ]

    ],


    "assistente.responder" => [

        "uri" =>
            "/assistente/responder",

        "arquivo" =>
            "backend/assistente/responder.php",

        "metodos" => [
            "POST"
        ]

    ],


    "assistente.limpar" => [

        "uri" =>
            "/assistente/limpar",

        "arquivo" =>
            "backend/assistente/limpar_historico.php",

        "metodos" => [
            "POST"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | PROFESSOR
    |--------------------------------------------------------------------------
    */

    "professor.inicio" => [

        "uri" =>
            "/professor",

        "arquivo" =>
            "frontend/pages/perfil/professor/professor.php",

        "metodos" => [
            "GET"
        ]

    ],


    "professor.painel" => [

        "uri" =>
            "/professor/painel",

        "arquivo" =>
            "frontend/pages/perfil/professor/professor_painel.php",

        "metodos" => [
            "GET"
        ]

    ],


    "professor.perfil" => [

        "uri" =>
            "/professor/perfil",

        "arquivo" =>
            "frontend/pages/perfil/professor/perfil.php",

        "metodos" => [
            "GET"
        ]

    ],


    "professor.editar" => [

        "uri" =>
            "/professor/perfil/editar",

        "arquivo" =>
            "frontend/pages/perfil/professor/editar.php",

        "metodos" => [
            "GET"
        ]

    ],


    "professor.questionario" => [

        "uri" =>
            "/professor/completar-perfil",

        "arquivo" =>
            "frontend/pages/perfil/professor/professor_questionario.php",

        "metodos" => [
            "GET"
        ]

    ],


    "professor.como_funciona" => [

        "uri" =>
            "/professor/como-funciona",

        "arquivo" =>
            "frontend/pages/perfil/professor/como_funciona.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | SALAS — PROFESSOR
    |--------------------------------------------------------------------------
    */

    "professor.salas" => [

        "uri" =>
            "/professor/salas",

        "arquivo" =>
            "frontend/pages/perfil/professor/salas/index.php",

        "metodos" => [
            "GET",
            "POST"
        ]

    ],


    "professor.sala" => [

        "uri" =>
            "/professor/salas/{id}",

        "arquivo" =>
            "frontend/pages/perfil/professor/salas/abrir.php",

        "metodos" => [
            "GET",
            "POST"
        ],

        "parametros" => [

            "id" =>
                "[1-9][0-9]*"

        ],

        "query" => [

            "id" =>
                "id"

        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | ARQUIVO PROTEGIDO — SALA DO PROFESSOR
    |--------------------------------------------------------------------------
    |
    | Exemplo:
    |
    | /professor/salas/12/arquivo/37
    |
    | GET:
    | entrega o conteúdo.
    |
    | HEAD:
    | permite consultar cabeçalhos sem transferir o arquivo.
    |
    |--------------------------------------------------------------------------
    */

    "professor.sala.arquivo" => [

        "uri" =>
            "/professor/salas/{sala_id}/arquivo/{arquivo_id}",

        "arquivo" =>
            "backend/api/professor_sala_arquivo.php",

        "metodos" => [
            "GET",
            "HEAD"
        ],

        "parametros" => [

            "sala_id" =>
                "[1-9][0-9]*",

            "arquivo_id" =>
                "[1-9][0-9]*"

        ],

        "query" => [

            "sala_id" =>
                "sala_id",

            "arquivo_id" =>
                "arquivo_id"

        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | APRENDIZ
    |--------------------------------------------------------------------------
    */

    "aprendiz.inicio" => [

        "uri" =>
            "/aprendiz",

        "arquivo" =>
            "frontend/pages/perfil/aprendiz/aprendiz.php",

        "metodos" => [
            "GET"
        ]

    ],


    "aprendiz.painel" => [

        "uri" =>
            "/aprendiz/painel",

        "arquivo" =>
            "frontend/pages/perfil/aprendiz/aprendiz_painel.php",

        "metodos" => [
            "GET"
        ]

    ],


    "aprendiz.perfil" => [

        "uri" =>
            "/aprendiz/perfil",

        "arquivo" =>
            "frontend/pages/perfil/aprendiz/perfil.php",

        "metodos" => [
            "GET"
        ]

    ],


    "aprendiz.editar" => [

        "uri" =>
            "/aprendiz/perfil/editar",

        "arquivo" =>
            "frontend/pages/perfil/aprendiz/editar.php",

        "metodos" => [
            "GET"
        ]

    ],


    "aprendiz.questionario" => [

        "uri" =>
            "/aprendiz/completar-perfil",

        "arquivo" =>
            "frontend/pages/perfil/aprendiz/aprendiz_questionario.php",

        "metodos" => [
            "GET"
        ]

    ],


    "aprendiz.como_funciona" => [

        "uri" =>
            "/aprendiz/como-funciona",

        "arquivo" =>
            "frontend/pages/perfil/aprendiz/como_funciona.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | AULAS CONVERSADAS
    |--------------------------------------------------------------------------
    */

    "aulas" => [

        "uri" =>
            "/aulas",

        "arquivo" =>
            "frontend/aulas/index.php",

        "metodos" => [
            "GET"
        ]

    ],


    "aulas.iniciar" => [

        "uri" =>
            "/aulas/iniciar",

        "arquivo" =>
            "frontend/aulas/aula.php",

        "metodos" => [
            "GET"
        ]

    ],


    "aulas.ver" => [

        "uri" =>
            "/aulas/{id}",

        "arquivo" =>
            "frontend/aulas/aula.php",

        "metodos" => [
            "GET"
        ],

        "parametros" => [

            "id" =>
                "[1-9][0-9]*"

        ],

        "query" => [

            "id" =>
                "id"

        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | CONTEÚDOS
    |--------------------------------------------------------------------------
    */

    "conteudos" => [

        "uri" =>
            "/conteudos",

        "arquivo" =>
            "frontend/conteudos/index.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | CADERNO
    |--------------------------------------------------------------------------
    */

    "caderno" => [

        "uri" =>
            "/caderno",

        "arquivo" =>
            "frontend/conteudos/caderno.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | CONTEÚDOS JAVA DINÂMICOS
    |--------------------------------------------------------------------------
    |
    | O slug aceita somente:
    |
    | - letras minúsculas;
    | - números;
    | - hífen;
    | - underscore.
    |
    | Isso impede a utilização direta de caminhos arbitrários.
    |
    |--------------------------------------------------------------------------
    */

    "java.conteudo" => [

        "uri" =>
            "/java/{slug}",

        "metodos" => [
            "GET"
        ],

        "parametros" => [

            "slug" =>
                "[a-z0-9_-]+"

        ],

        "arquivo_dinamico" => [

            "diretorio" =>
                "frontend/conteudos/java",

            "parametro" =>
                "slug",

            "extensao" =>
                ".php"

        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | MANUAL
    |--------------------------------------------------------------------------
    */

    "manual" => [

        "uri" =>
            "/manual",

        "arquivo" =>
            "frontend/conteudos/manual/index.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | LABORATÓRIO JAVA
    |--------------------------------------------------------------------------
    */

    "laboratorio" => [

        "uri" =>
            "/laboratorio",

        "arquivo" =>
            "frontend/laboratorio/index.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | SALA GLOBAL
    |--------------------------------------------------------------------------
    */

    "sala_global" => [

        "uri" =>
            "/sala-global",

        "arquivo" =>
            "frontend/pages/sala-global/index.php",

        "metodos" => [
            "GET",
            "POST"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | ARQUIVO PROTEGIDO — SALA GLOBAL
    |--------------------------------------------------------------------------
    */

    "sala_global.arquivo" => [

        "uri" =>
            "/sala-global/arquivo/{id}",

        "arquivo" =>
            "backend/api/sala_global_arquivo.php",

        "metodos" => [
            "GET"
        ],

        "parametros" => [

            "id" =>
                "[1-9][0-9]*"

        ],

        "query" => [

            "id" =>
                "id"

        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | AUTENTICAÇÃO — AÇÕES
    |--------------------------------------------------------------------------
    */

    "acao.login" => [

        "uri" =>
            "/entrar",

        "arquivo" =>
            "backend/auth/processar_login.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.cadastro" => [

        "uri" =>
            "/criar-conta",

        "arquivo" =>
            "backend/auth/processar_cadastro.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.confirmar_email" => [

        "uri" =>
            "/confirmar-email",

        "arquivo" =>
            "backend/auth/processar_codigo.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.recuperar_senha" => [

        "uri" =>
            "/recuperar-senha",

        "arquivo" =>
            "backend/auth/processar_recuperacao.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.recuperar_codigo" => [

        "uri" =>
            "/recuperar-senha/codigo",

        "arquivo" =>
            "backend/auth/processar_codigo_redefinicao.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.nova_senha" => [

        "uri" =>
            "/recuperar-senha/nova-senha",

        "arquivo" =>
            "backend/auth/processar_nova_senha.php",

        "metodos" => [
            "POST"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    |
    | Temporariamente permanece GET por compatibilidade com os links atuais.
    |
    | Durante a auditoria do fluxo de autenticação, migraremos para:
    |
    | POST + CSRF
    |
    |--------------------------------------------------------------------------
    */

    "logout" => [

        "uri" =>
            "/sair",

        "arquivo" =>
            "backend/auth/logout.php",

        "metodos" => [
            "GET"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | CONTATO
    |--------------------------------------------------------------------------
    */

    "acao.contato" => [

        "uri" =>
            "/contato/enviar",

        "arquivo" =>
            "backend/contato/processar_contato.php",

        "metodos" => [
            "POST"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | PERFIL — AÇÕES
    |--------------------------------------------------------------------------
    */

    "acao.perfil.salvar" => [

        "uri" =>
            "/perfil/salvar",

        "arquivo" =>
            "backend/perfil/salvar_perfil.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.estudante.salvar" => [

        "uri" =>
            "/estudante/perfil/salvar",

        "arquivo" =>
            "backend/perfil/salvar_estudante.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.professor.salvar" => [

        "uri" =>
            "/professor/perfil/salvar",

        "arquivo" =>
            "backend/perfil/salvar_professor.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.professor.atualizar" => [

        "uri" =>
            "/professor/perfil/atualizar",

        "arquivo" =>
            "backend/perfil/atualizar_professor.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.aprendiz.salvar" => [

        "uri" =>
            "/aprendiz/perfil/salvar",

        "arquivo" =>
            "backend/perfil/salvar_aprendiz.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.aprendiz.atualizar" => [

        "uri" =>
            "/aprendiz/perfil/atualizar",

        "arquivo" =>
            "backend/perfil/atualizar_aprendiz.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.perfil_publico.atualizar" => [

        "uri" =>
            "/perfil/publico/atualizar",

        "arquivo" =>
            "backend/perfil/atualizar_perfil_publico.php",

        "metodos" => [
            "POST"
        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | AULAS — AÇÕES
    |--------------------------------------------------------------------------
    */

    "acao.aula.concluir" => [

        "uri" =>
            "/aulas/concluir",

        "arquivo" =>
            "backend/aulas/concluir_aula.php",

        "metodos" => [
            "POST"
        ]

    ],


    "acao.aula.progresso" => [

        "uri" =>
            "/aulas/progresso",

        "arquivo" =>
            "backend/aulas/salvar_progresso.php",

        "metodos" => [
            "POST"
        ]

    ]

];