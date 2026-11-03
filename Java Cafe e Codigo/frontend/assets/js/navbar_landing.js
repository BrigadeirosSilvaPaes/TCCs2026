document.addEventListener("DOMContentLoaded", function() {

    const menuBotao =
        document.getElementById("landingMenuBotao");

    const menu =
        document.getElementById("landingMenu");

    const dropdown =
        document.querySelector(".landing-dropdown");

    const recursosBotao =
        document.getElementById("recursosBotao");

    const links =
        document.querySelectorAll(
            '.landing-menu a[href^="#"]'
        );


    /*
    |--------------------------------------------------------------------------
    | MENU MOBILE
    |--------------------------------------------------------------------------
    */

    if (menuBotao && menu) {

        menuBotao.addEventListener("click", function() {

            const menuAberto =
                menu.classList.toggle("ativo");

            menuBotao.classList.toggle(
                "ativo",
                menuAberto
            );

            menuBotao.setAttribute(
                "aria-expanded",
                menuAberto ? "true" : "false"
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | DROPDOWN DE RECURSOS
    |--------------------------------------------------------------------------
    */

    if (recursosBotao && dropdown) {

        recursosBotao.addEventListener(
            "click",
            function(evento) {

                evento.stopPropagation();

                const aberto =
                    dropdown.classList.toggle("ativo");

                recursosBotao.setAttribute(
                    "aria-expanded",
                    aberto ? "true" : "false"
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FECHA AO CLICAR FORA
    |--------------------------------------------------------------------------
    */

    document.addEventListener("click", function(evento) {

        if (
            dropdown &&
            !dropdown.contains(evento.target)
        ) {

            dropdown.classList.remove("ativo");

            if (recursosBotao) {

                recursosBotao.setAttribute(
                    "aria-expanded",
                    "false"
                );

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | FECHA O MENU APÓS ESCOLHER UMA SEÇÃO
    |--------------------------------------------------------------------------
    */

    links.forEach(function(link) {

        link.addEventListener("click", function() {

            if (menu) {

                menu.classList.remove("ativo");

            }

            if (menuBotao) {

                menuBotao.classList.remove("ativo");

                menuBotao.setAttribute(
                    "aria-expanded",
                    "false"
                );

            }

            if (dropdown) {

                dropdown.classList.remove("ativo");

            }

            if (recursosBotao) {

                recursosBotao.setAttribute(
                    "aria-expanded",
                    "false"
                );

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | FECHA COM ESC
    |--------------------------------------------------------------------------
    */

    document.addEventListener("keydown", function(evento) {

        if (evento.key !== "Escape") {

            return;

        }

        if (menu) {

            menu.classList.remove("ativo");

        }

        if (menuBotao) {

            menuBotao.classList.remove("ativo");

            menuBotao.setAttribute(
                "aria-expanded",
                "false"
            );

        }

        if (dropdown) {

            dropdown.classList.remove("ativo");

        }

        if (recursosBotao) {

            recursosBotao.setAttribute(
                "aria-expanded",
                "false"
            );

        }

    });

});