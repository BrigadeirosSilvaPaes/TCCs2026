<?php


require_once __DIR__ . "/../../backend/config/helpers.php";


/*
|--------------------------------------------------------------------------
| RECUPERA MENSAGEM DO SISTEMA PRINCIPAL
|--------------------------------------------------------------------------
*/

$mensagem = pegarMensagem();


/*
|--------------------------------------------------------------------------
| COMPATIBILIDADE COM SISTEMA ANTIGO - ERRO
|--------------------------------------------------------------------------
*/

if (
    !$mensagem &&
    isset($_SESSION["erro"]) &&
    $_SESSION["erro"] !== ""
) {

    $mensagem = [

        "tipo" => "erro",

        "titulo" => "Não foi possível continuar",

        "texto" => (string) $_SESSION["erro"]

    ];


    unset(
        $_SESSION["erro"]
    );

}


/*
|--------------------------------------------------------------------------
| COMPATIBILIDADE COM SISTEMA ANTIGO - SUCESSO
|--------------------------------------------------------------------------
*/

if (
    !$mensagem &&
    isset($_SESSION["sucesso"]) &&
    $_SESSION["sucesso"] !== ""
) {

    $mensagem = [

        "tipo" => "sucesso",

        "titulo" => "Operação realizada com sucesso",

        "texto" => (string) $_SESSION["sucesso"]

    ];


    unset(
        $_SESSION["sucesso"]
    );

}


/*
|--------------------------------------------------------------------------
| EXIBE A MENSAGEM
|--------------------------------------------------------------------------
*/

if ($mensagem):


    $tipo = htmlspecialchars(
        $mensagem["tipo"] ?? "info",
        ENT_QUOTES,
        "UTF-8"
    );


    $titulo = htmlspecialchars(
        $mensagem["titulo"] ?? "",
        ENT_QUOTES,
        "UTF-8"
    );


    $texto = htmlspecialchars(
        $mensagem["texto"] ?? "",
        ENT_QUOTES,
        "UTF-8"
    );

?>


<div
    class="mensagem-container"
    role="status"
    aria-live="polite"
>


    <div class="mensagem <?= $tipo ?>">


        <div class="mensagem-conteudo">


            <h3>
                <?= $titulo ?>
            </h3>


            <p>
                <?= $texto ?>
            </p>


        </div>


        <button
            type="button"
            class="fechar-mensagem"
            onclick="this.parentElement.remove();"
            aria-label="Fechar mensagem"
            title="Fechar mensagem"
        >

            ×

        </button>


    </div>


</div>


<?php endif; ?>