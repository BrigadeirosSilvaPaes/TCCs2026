<?php

/*
|--------------------------------------------------------------------------
| CATÁLOGO DE MENSAGENS
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Estrutura padrão:
|
| [
|     "tipo" => [
|         "codigo" => [
|             "titulo" => "...",
|             "texto"  => "..."
|         ]
|     ]
| ]
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| REGRAS DE SENHA
|--------------------------------------------------------------------------
|
| Utilizamos as constantes de security.php quando disponíveis.
|
| Os fallbacks preservam o funcionamento caso este catálogo
| seja carregado isoladamente.
|
|--------------------------------------------------------------------------
*/

$senhaMinima =
    defined(
        "MIN_PASSWORD_LENGTH"
    )
        ?
        (int) MIN_PASSWORD_LENGTH
        :
        8;


$senhaMaxima =
    defined(
        "MAX_PASSWORD_LENGTH"
    )
        ?
        (int) MAX_PASSWORD_LENGTH
        :
        72;


/*
|--------------------------------------------------------------------------
| CATÁLOGO
|--------------------------------------------------------------------------
*/

return [


    /*
    |--------------------------------------------------------------------------
    | SUCESSO
    |--------------------------------------------------------------------------
    */

    "sucesso" => [


        "cadastro" => [

            "titulo" =>
                "Conta criada com sucesso!",

            "texto" =>
                "Enviamos um código de confirmação para o seu e-mail. "
                .
                "Digite esse código para ativar sua conta."

        ],


        "email_confirmado" => [

            "titulo" =>
                "E-mail confirmado!",

            "texto" =>
                "Sua conta foi ativada com sucesso. "
                .
                "Agora você já pode entrar na plataforma."

        ],


        "login" => [

            "titulo" =>
                "Login realizado!",

            "texto" =>
                "Bem-vindo ao Java, Café e Código."

        ],


        "logout" => [

            "titulo" =>
                "Sessão encerrada!",

            "texto" =>
                "Você saiu da sua conta com segurança."

        ],


        "senha_alterada" => [

            "titulo" =>
                "Senha alterada com sucesso!",

            "texto" =>
                "Sua nova senha já está ativa. "
                .
                "Agora você pode entrar normalmente na plataforma."

        ],


        "recuperacao_enviada" => [

            "titulo" =>
                "Código de recuperação enviado!",

            "texto" =>
                "Enviamos um código para o e-mail informado. "
                .
                "Confira sua caixa de entrada e também a pasta de spam."

        ],


        "codigo_confirmado" => [

            "titulo" =>
                "Código confirmado!",

            "texto" =>
                "Agora crie uma nova senha para recuperar o acesso à sua conta."

        ],


        "perfil_salvo" => [

            "titulo" =>
                "Perfil atualizado!",

            "texto" =>
                "Suas informações foram salvas com sucesso."

        ],


        "perfil_completo" => [

            "titulo" =>
                "Perfil completo!",

            "texto" =>
                "Agora você pode aproveitar os recursos disponíveis na plataforma."

        ],


        "progresso_salvo" => [

            "titulo" =>
                "Progresso salvo!",

            "texto" =>
                "Seu progresso de aprendizagem foi atualizado."

        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | ERRO
    |--------------------------------------------------------------------------
    */

    "erro" => [


        "campos_vazios" => [

            "titulo" =>
                "Preencha os campos obrigatórios",

            "texto" =>
                "Existem informações que ainda não foram preenchidas. "
                .
                "Confira os campos e tente novamente."

        ],


        "nome_invalido" => [

            "titulo" =>
                "Nome inválido",

            "texto" =>
                "Informe um nome válido para continuar."

        ],


        "email_existente" => [

            "titulo" =>
                "Este e-mail já está cadastrado",

            "texto" =>
                "Já existe uma conta utilizando este endereço. "
                .
                "Entre na sua conta ou utilize a opção \"Esqueci minha senha\"."

        ],


        "email_invalido" => [

            "titulo" =>
                "E-mail inválido",

            "texto" =>
                "Informe um endereço de e-mail válido, como nome@exemplo.com."

        ],


        "senha_invalida" => [

            "titulo" =>
                "Senha inválida",

            "texto" =>
                "Utilize uma senha entre "
                .
                $senhaMinima
                .
                " e "
                .
                $senhaMaxima
                .
                " caracteres. Letras, números e símbolos são permitidos."

        ],


        "senha_curta" => [

            "titulo" =>
                "Senha muito curta",

            "texto" =>
                "Sua senha precisa possuir pelo menos "
                .
                $senhaMinima
                .
                " caracteres. Você pode utilizar letras, números e símbolos."

        ],


        "senha_longa" => [

            "titulo" =>
                "Senha muito longa",

            "texto" =>
                "Sua senha pode possuir no máximo "
                .
                $senhaMaxima
                .
                " caracteres."

        ],


        "senhas_diferentes" => [

            "titulo" =>
                "As senhas não coincidem",

            "texto" =>
                "Digite exatamente a mesma senha nos dois campos antes de continuar."

        ],


        "login_invalido" => [

            "titulo" =>
                "Não foi possível entrar",

            "texto" =>
                "Confira o e-mail e a senha informados. "
                .
                "Se não lembrar da senha, utilize a opção \"Esqueci minha senha\"."

        ],


        "email_nao_confirmado" => [

            "titulo" =>
                "E-mail ainda não confirmado",

            "texto" =>
                "Sua conta foi criada, mas ainda precisa ser ativada. "
                .
                "Informe o código de confirmação enviado para seu e-mail."

        ],


        "conta_bloqueada" => [

            "titulo" =>
                "Conta bloqueada",

            "texto" =>
                "Esta conta está bloqueada administrativamente. "
                .
                "Entre em contato com a equipe do Java, Café e Código para obter mais informações."

        ],


        "usuario_nao_encontrado" => [

            "titulo" =>
                "Conta não encontrada",

            "texto" =>
                "Não encontramos uma conta vinculada às informações fornecidas. "
                .
                "Confira os dados e tente novamente."

        ],


        "codigo_invalido" => [

            "titulo" =>
                "Código incorreto",

            "texto" =>
                "O código informado não corresponde ao código enviado. "
                .
                "Confira seu e-mail e tente novamente."

        ],


        "codigo_expirado" => [

            "titulo" =>
                "Código expirado",

            "texto" =>
                "O código de segurança não é mais válido. "
                .
                "Solicite um novo código para continuar."

        ],


        "sessao_recuperacao_expirada" => [

            "titulo" =>
                "Recuperação expirada",

            "texto" =>
                "Sua sessão de recuperação não é mais válida. "
                .
                "Solicite um novo código para continuar."

        ],


        "sessao_expirada" => [

            "titulo" =>
                "Sessão expirada",

            "texto" =>
                "Sua sessão não está mais ativa. "
                .
                "Entre novamente na plataforma para continuar."

        ],


        "csrf_expirado" => [

            "titulo" =>
                "Solicitação expirada",

            "texto" =>
                "A validação de segurança desta página expirou. "
                .
                "Atualize a página e tente novamente."

        ],


        "erro_email" => [

            "titulo" =>
                "Não foi possível enviar o e-mail",

            "texto" =>
                "O sistema não conseguiu enviar a mensagem neste momento. "
                .
                "Tente novamente em alguns instantes."

        ],


        "erro_banco" => [

            "titulo" =>
                "Não foi possível concluir a operação",

            "texto" =>
                "Ocorreu um problema ao salvar as informações. "
                .
                "Tente novamente em alguns instantes."

        ],


        "sem_permissao" => [

            "titulo" =>
                "Acesso negado",

            "texto" =>
                "Sua conta não possui permissão para acessar este recurso."

        ],


        "generico" => [

            "titulo" =>
                "Algo deu errado",

            "texto" =>
                "Não foi possível concluir esta operação. "
                .
                "Tente novamente."

        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | AVISOS
    |--------------------------------------------------------------------------
    */

    "aviso" => [


        "confirmar_email" => [

            "titulo" =>
                "Confirme seu e-mail",

            "texto" =>
                "Digite o código de verificação enviado para seu e-mail "
                .
                "para concluir a criação da conta."

        ],


        "perfil_incompleto" => [

            "titulo" =>
                "Complete seu perfil",

            "texto" =>
                "Antes de continuar, precisamos de algumas informações "
                .
                "para configurar sua experiência na plataforma."

        ],


        "login_necessario" => [

            "titulo" =>
                "Login necessário",

            "texto" =>
                "Entre na sua conta para acessar este recurso."

        ],


        "manutencao" => [

            "titulo" =>
                "Sistema em manutenção",

            "texto" =>
                "Algumas funcionalidades podem estar temporariamente indisponíveis."

        ]

    ],


    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES
    |--------------------------------------------------------------------------
    */

    "info" => [


        "bem_vindo" => [

            "titulo" =>
                "Bem-vindo!",

            "texto" =>
                "Explore os conteúdos e recursos disponíveis no Java, Café e Código."

        ],


        "novo_conteudo" => [

            "titulo" =>
                "Novo conteúdo disponível!",

            "texto" =>
                "Confira as novidades disponíveis na plataforma."

        ],


        "regras_senha" => [

            "titulo" =>
                "Como criar sua senha",

            "texto" =>
                "Utilize entre "
                .
                $senhaMinima
                .
                " e "
                .
                $senhaMaxima
                .
                " caracteres. Você pode combinar letras, números e símbolos."

        ],


        /*
        |--------------------------------------------------------------------------
        | ASSISTENTE JAVA
        |--------------------------------------------------------------------------
        |
        | A primeira interação pode demorar mais porque o modelo
        | utilizado pelo Assistente Java é inicializado no navegador.
        |
        |--------------------------------------------------------------------------
        */

        "assistente_inicializando" => [

            "titulo" =>
                "Preparando o Assistente Java",

            "texto" =>
                "A primeira resposta pode levar um pouco mais de tempo, "
                .
                "pois o modelo de inteligência artificial está sendo inicializado no navegador. "
                .
                "As próximas respostas normalmente são mais rápidas."

        ]

    ]

];