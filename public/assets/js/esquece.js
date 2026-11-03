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
        params.get(
            "status"
        );


    switch (status) {

        case "empty":

            mensagem.innerHTML = `
                <div class="alert-error">
                    Informe seu e-mail.
                </div>
            `;

            break;


        case "email":

            mensagem.innerHTML = `
                <div class="alert-error">
                    Informe um endereço de e-mail válido.
                </div>
            `;

            break;


        case "error":

            mensagem.innerHTML = `
                <div class="alert-error">
                    Não foi possível iniciar a recuperação.
                    Tente novamente em alguns instantes.
                </div>
            `;

            break;


        case "expired":

            mensagem.innerHTML = `
                <div class="alert-error">
                    Sua autorização de recuperação expirou.
                    Solicite um novo código.
                </div>
            `;

            break;


        case "success":

            mensagem.innerHTML = `
                <div class="alert-success">
                    Se o e-mail estiver cadastrado,
                    um código de recuperação será enviado.
                </div>
            `;

            break;
    }


    if (status) {

        window.history.replaceState(
            {},
            document.title,
            "esquece.php"
        );

    }

}