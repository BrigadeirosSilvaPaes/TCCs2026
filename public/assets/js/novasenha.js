const params = new URLSearchParams(window.location.search);
const mensagem = document.getElementById("mensagem");

if (mensagem) {

    const status = params.get("status");

    switch (status) {

        case "error":
            mensagem.innerHTML = `
                <div class="alert-error">
                    Não foi possível alterar sua senha. Tente novamente.
                </div>
            `;
            break;

        case "password":
            mensagem.innerHTML = `
                <div class="alert-error">
                    As senhas digitadas não são iguais.
                </div>
            `;
            break;

        case "passwordLength":
            mensagem.innerHTML = `
                <div class="alert-error">
                    A senha precisa ter pelo menos 6 caracteres.
                </div>
            `;
            break;

        case "expired":
            mensagem.innerHTML = `
                <div class="alert-error">
                    Sua autorização de recuperação expirou. Solicite um novo código.
                </div>
            `;
            break;
    }

    if (status) {
        history.replaceState({}, "", "novasenha.php");
    }
}
