const params =
    new URLSearchParams(
        window.location.search
    );

const mensagem =
    document.getElementById(
        "mensagem"
    );


if (mensagem) {

    const status =
        params.get("status");


    switch (status) {


        /*
        |--------------------------------------------------------------------------
        | CONTA DE ALUNO CRIADA
        |--------------------------------------------------------------------------
        */

        case "success":

            mensagem.innerHTML = `
                <div class="alert-success">
                    Conta criada com sucesso!
                    Faça seu login.
                </div>
            `;

            break;


        /*
        |--------------------------------------------------------------------------
        | PROFESSOR CADASTRADO
        |--------------------------------------------------------------------------
        */

        case "teacherCreated":

            mensagem.innerHTML = `
                <div class="alert-success">
                    Cadastro de professor realizado com sucesso!
                    Aguarde a aprovação do administrador
                    para acessar a plataforma.
                </div>
            `;

            break;


        /*
        |--------------------------------------------------------------------------
        | PROFESSOR AINDA NÃO APROVADO
        |--------------------------------------------------------------------------
        */

        case "pending":

            mensagem.innerHTML = `
                <div class="alert-error">
                    Sua conta de professor ainda está
                    aguardando aprovação do administrador.
                </div>
            `;

            break;


        /*
        |--------------------------------------------------------------------------
        | ALUNO BLOQUEADO
        |--------------------------------------------------------------------------
        */

        case "blocked":

            mensagem.innerHTML = `
                <div class="alert-error">
                    Sua conta está bloqueada. Entre em contato com o administrador.
                </div>
            `;

            break;


        /*
        |--------------------------------------------------------------------------
        | LOGIN INCORRETO
        |--------------------------------------------------------------------------
        */

        case "error":

            mensagem.innerHTML = `
                <div class="alert-error">
                    E-mail ou senha inválidos.
                </div>
            `;

            break;


        /*
        |--------------------------------------------------------------------------
        | CAMPOS VAZIOS
        |--------------------------------------------------------------------------
        */

        case "empty":

            mensagem.innerHTML = `
                <div class="alert-error">
                    Preencha todos os campos.
                </div>
            `;

            break;


        /*
        |--------------------------------------------------------------------------
        | PERFIL INCORRETO
        |--------------------------------------------------------------------------
        */

        case "profile":

            mensagem.innerHTML = `
                <div class="alert-error">
                    Esta conta não possui permissão
                    para acessar o perfil selecionado.
                </div>
            `;

            break;


        /*
        |--------------------------------------------------------------------------
        | SENHA ALTERADA
        |--------------------------------------------------------------------------
        */

        case "passwordChanged":

            mensagem.innerHTML = `
                <div class="alert-success">
                    Senha alterada com sucesso!
                    Faça seu login.
                </div>
            `;

            break;
    }


    /*
    |--------------------------------------------------------------------------
    | LIMPA A URL
    |--------------------------------------------------------------------------
    |
    | Exemplo:
    |
    | login.php?status=pending
    |
    | vira:
    |
    | login.php
    |
    |--------------------------------------------------------------------------
    */

    if (status) {

        window.history.replaceState(
            {},
            document.title,
            "login.php"
        );
    }
}