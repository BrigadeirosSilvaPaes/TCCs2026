<?php

/*
|--------------------------------------------------------------------------
| ASSISTENTE JAVA
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Componente visual do Assistente Java.
|
| Visual:
| frontend/assets/css/assistente_ia.css
|
| Comportamento:
| frontend/assets/js/assistente_ia.js
|
| Modelo:
| frontend/assets/js/assistente_modelo.js
|
| Backend:
| backend/assistente/responder.php
| backend/assistente/historico.php
| backend/assistente/limpar_historico.php
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| SOMENTE USUÁRIOS AUTENTICADOS
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["usuario"]
    )
    ||
    !is_array(
        $_SESSION["usuario"]
    )
) {

    return;

}


/*
|--------------------------------------------------------------------------
| ID DO USUÁRIO
|--------------------------------------------------------------------------
*/

$assistenteUsuarioId =
    (int) (
        $_SESSION["usuario"]["id"]
        ??
        0
    );


if (
    $assistenteUsuarioId <= 0
) {

    return;

}


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

$assistenteEscapar =
    static function (
        mixed $valor
    ): string {

        return htmlspecialchars(
            (string) $valor,
            ENT_QUOTES,
            "UTF-8"
        );

    };


/*
|--------------------------------------------------------------------------
| TOKEN CSRF
|--------------------------------------------------------------------------
|
| O mesmo token será validado posteriormente em:
|
| - responder.php;
| - limpar_historico.php.
|
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["csrf"]
    )
    ||
    !is_array(
        $_SESSION["csrf"]
    )
) {

    $_SESSION["csrf"] =
        [];

}


$assistenteCsrfToken =
    $_SESSION["csrf"]["assistente_java"]
    ??
    "";


/*
|--------------------------------------------------------------------------
| VALIDA TOKEN EXISTENTE
|--------------------------------------------------------------------------
*/

if (
    !is_string(
        $assistenteCsrfToken
    )
    ||
    preg_match(
        "/^[a-f0-9]{64}$/",
        $assistenteCsrfToken
    )
    !== 1
) {

    try {

        $assistenteCsrfToken =
            bin2hex(
                random_bytes(
                    32
                )
            );

    }
    catch (
        Throwable $erro
    ) {

        error_log(
            "[Assistente Java] Não foi possível gerar token CSRF: "
            .
            $erro->getMessage()
        );


        /*
        |--------------------------------------------------------------------------
        | FALHA FECHADA
        |--------------------------------------------------------------------------
        |
        | Se não conseguirmos gerar segurança para os POSTs,
        | o componente não será exibido.
        |
        |--------------------------------------------------------------------------
        */

        return;

    }


    $_SESSION["csrf"]["assistente_java"] =
        $assistenteCsrfToken;

}


/*
|--------------------------------------------------------------------------
| ENDPOINT - RESPONDER / SALVAR
|--------------------------------------------------------------------------
|
| Rota futura:
|
| /assistente/responder
|
|--------------------------------------------------------------------------
*/

if (
    function_exists(
        "rotaExiste"
    )
    &&
    function_exists(
        "rota"
    )
    &&
    rotaExiste(
        "assistente.responder"
    )
) {

    $assistenteEndpoint =
        rota(
            "assistente.responder"
        );

}
else {

    /*
    |--------------------------------------------------------------------------
    | FALLBACK TEMPORÁRIO
    |--------------------------------------------------------------------------
    */

    $assistenteEndpoint =
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/backend/assistente/responder.php";

}


/*
|--------------------------------------------------------------------------
| ENDPOINT - HISTÓRICO
|--------------------------------------------------------------------------
|
| Rota futura:
|
| /assistente/historico
|
|--------------------------------------------------------------------------
*/

if (
    function_exists(
        "rotaExiste"
    )
    &&
    function_exists(
        "rota"
    )
    &&
    rotaExiste(
        "assistente.historico"
    )
) {

    $assistenteHistoricoEndpoint =
        rota(
            "assistente.historico"
        );

}
else {

    $assistenteHistoricoEndpoint =
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/backend/assistente/historico.php";

}


/*
|--------------------------------------------------------------------------
| ENDPOINT - NOVA CONVERSA
|--------------------------------------------------------------------------
|
| Rota futura:
|
| /assistente/limpar
|
|--------------------------------------------------------------------------
*/

if (
    function_exists(
        "rotaExiste"
    )
    &&
    function_exists(
        "rota"
    )
    &&
    rotaExiste(
        "assistente.limpar"
    )
) {

    $assistenteLimparEndpoint =
        rota(
            "assistente.limpar"
        );

}
else {

    $assistenteLimparEndpoint =
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/backend/assistente/limpar_historico.php";

}

?>


<!-- =====================================================
ASSISTENTE JAVA
====================================================== -->

<div
    class="assistente-ia"
    id="assistenteIa"

    data-endpoint="<?= $assistenteEscapar(
        $assistenteEndpoint
    ) ?>"

    data-historico-endpoint="<?= $assistenteEscapar(
        $assistenteHistoricoEndpoint
    ) ?>"

    data-limpar-endpoint="<?= $assistenteEscapar(
        $assistenteLimparEndpoint
    ) ?>"

    data-csrf-token="<?= $assistenteEscapar(
        $assistenteCsrfToken
    ) ?>"
>


    <!-- =================================================
    BOTÃO FLUTUANTE
    ================================================== -->

    <button
        type="button"
        class="assistente-ia-botao"
        id="assistenteIaBotao"
        aria-label="Abrir Assistente Java"
        aria-controls="assistenteIaJanela"
        aria-expanded="false"
    >


        <span
            class="assistente-ia-botao-icone"
            aria-hidden="true"
        >

            ✦

        </span>


        <span class="assistente-ia-botao-texto">

            Assistente Java

        </span>


    </button>


    <!-- =================================================
    JANELA DO CHAT
    ================================================== -->

    <section
        class="assistente-ia-janela"
        id="assistenteIaJanela"

        role="dialog"

        aria-modal="false"

        aria-hidden="true"

        aria-labelledby="assistenteIaTitulo"

        aria-describedby="assistenteIaDescricao"
    >


        <!-- =============================================
        CABEÇALHO
        ============================================== -->

        <header class="assistente-ia-cabecalho">


            <div class="assistente-ia-identidade">


                <div
                    class="assistente-ia-avatar"
                    aria-hidden="true"
                >

                    ✦

                </div>


                <div class="assistente-ia-identidade-texto">


                    <strong id="assistenteIaTitulo">

                        Assistente Java

                    </strong>


                    <span>

                        Apoio rápido para suas dúvidas

                    </span>


                </div>


            </div>


            <!-- =========================================
            AÇÕES
            ========================================== -->

            <div class="assistente-ia-cabecalho-acoes">


                <button
                    type="button"
                    class="assistente-ia-nova-conversa"
                    id="assistenteIaNovaConversa"
                    aria-label="Iniciar uma nova conversa"
                    title="Nova conversa"
                >

                    Nova conversa

                </button>


                <button
                    type="button"
                    class="assistente-ia-fechar"
                    id="assistenteIaFechar"
                    aria-label="Fechar Assistente Java"
                    title="Fechar"
                >

                    ×

                </button>


            </div>


        </header>


        <!-- =============================================
        INFORMAÇÃO
        ============================================== -->

        <div
            class="assistente-ia-informacao"
            id="assistenteIaDescricao"
        >


            <span
                class="assistente-ia-informacao-icone"
                aria-hidden="true"
            >

                i

            </span>


            <p>

                Sou uma ferramenta auxiliar para dúvidas
                sobre Java, programação e a plataforma.

            </p>


        </div>


        <!-- =============================================
        MENSAGENS
        ============================================== -->

        <div
            class="assistente-ia-mensagens"
            id="assistenteIaMensagens"

            aria-live="polite"

            aria-relevant="additions"

            aria-atomic="false"
        >


            <!-- =========================================
            MENSAGEM INICIAL
            ========================================== -->

            <div
                class="
                    assistente-ia-mensagem
                    assistente-ia-mensagem-assistente
                    assistente-ia-mensagem-inicial
                "
                id="assistenteIaMensagemInicial"
            >


                <div
                    class="assistente-ia-mensagem-avatar"
                    aria-hidden="true"
                >

                    ✦

                </div>


                <div class="assistente-ia-balao">


                    <strong>

                        Assistente Java

                    </strong>


                    <p>

                        Travou em alguma parte de Java?
                        Manda a dúvida que eu tento explicar
                        de um jeito mais simples.

                    </p>


                </div>


            </div>


        </div>


        <!-- =============================================
        INDICADOR DE RESPOSTA
        ============================================== -->

        <div
            class="assistente-ia-digitando"
            id="assistenteIaDigitando"
            role="status"
            aria-live="polite"
            hidden
        >


            <div
                class="assistente-ia-digitando-pontos"
                aria-hidden="true"
            >

                <span></span>
                <span></span>
                <span></span>

            </div>


            <small>

                Pensando...

            </small>


        </div>


        <!-- =============================================
        ERRO
        ============================================== -->

        <div
            class="assistente-ia-erro"
            id="assistenteIaErro"
            role="alert"
            aria-live="assertive"
            hidden
        ></div>


        <!-- =============================================
        FORMULÁRIO
        ============================================== -->

        <form
            class="assistente-ia-formulario"
            id="assistenteIaFormulario"
            autocomplete="off"
        >


            <label
                for="assistenteIaCampo"
                class="assistente-ia-label"
            >

                Digite sua dúvida

            </label>


            <div class="assistente-ia-campo-area">


                <textarea
                    name="mensagem"
                    id="assistenteIaCampo"

                    rows="1"

                    maxlength="4000"

                    placeholder="Ex.: não entendi a diferença entre classe e objeto"

                    aria-describedby="assistenteIaContador"

                    autocomplete="off"

                    autocapitalize="sentences"

                    spellcheck="true"

                    required
                ></textarea>


                <button
                    type="submit"
                    class="assistente-ia-enviar"
                    id="assistenteIaEnviar"
                    aria-label="Enviar mensagem"
                    title="Enviar"
                >

                    <span aria-hidden="true">

                        ➜

                    </span>

                </button>


            </div>


            <!-- =========================================
            RODAPÉ DO FORMULÁRIO
            ========================================== -->

            <div class="assistente-ia-formulario-rodape">


                <small>

                    IA executada diretamente no seu navegador.

                </small>


                <small
                    id="assistenteIaContador"
                    class="assistente-ia-contador"
                    aria-live="polite"
                >

                    0 / 4000

                </small>


            </div>


        </form>


        <!-- =============================================
        RODAPÉ
        ============================================== -->

        <footer class="assistente-ia-rodape">


            <p>

                O Assistente Java é um recurso auxiliar
                e pode apresentar imprecisões.

            </p>


        </footer>


    </section>


</div>