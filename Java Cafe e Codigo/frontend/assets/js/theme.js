document.addEventListener("DOMContentLoaded", () => {

    /*
    |--------------------------------------------------------------------------
    | TEMA SALVO
    |--------------------------------------------------------------------------
    |
    | O tema deve ser aplicado em todas as páginas,
    | mesmo quando o botão de alteração não estiver presente.
    |
    */

    const temaSalvo =
        localStorage.getItem("tema") || "dark";


    /*
    |--------------------------------------------------------------------------
    | APLICA O TEMA
    |--------------------------------------------------------------------------
    */

    document.body.classList.toggle(
        "light-mode",
        temaSalvo === "light"
    );


    /*
    |--------------------------------------------------------------------------
    | CONTROLE DE TEMA
    |--------------------------------------------------------------------------
    |
    | O controle existe somente na área de configurações/aparência.
    |
    */

    const controleTema =
        document.getElementById("tema");


    if (!controleTema) {
        return;
    }


    controleTema.checked =
        temaSalvo === "light";


    controleTema.addEventListener(
        "change",
        function () {

            const novoTema =
                this.checked
                    ? "light"
                    : "dark";


            document.body.classList.toggle(
                "light-mode",
                novoTema === "light"
            );


            localStorage.setItem(
                "tema",
                novoTema
            );

        }
    );

});