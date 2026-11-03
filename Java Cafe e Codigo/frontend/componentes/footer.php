<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../backend/config/app.php";


/*
|--------------------------------------------------------------------------
| ARQUIVOS DO ASSISTENTE JAVA
|--------------------------------------------------------------------------
*/

$caminhoAssistente =
    __DIR__ .
    "/assistente_ia.php";

$caminhoCssAssistente =
    __DIR__ .
    "/../assets/css/assistente_ia.css";

$caminhoJsAssistente =
    __DIR__ .
    "/../assets/js/assistente_ia.js";


/*
|--------------------------------------------------------------------------
| VOLTAR AO PAINEL
|--------------------------------------------------------------------------
*/

$caminhoVoltarPainel =
    __DIR__ .
    "/voltar_painel.php";

$caminhoCssVoltarPainel =
    __DIR__ .
    "/../assets/css/voltar_painel.css";


/*
|--------------------------------------------------------------------------
| PÁGINA ATUAL
|--------------------------------------------------------------------------
|
| SCRIPT_NAME retorna, por exemplo:
|
| /java-cafe-e-codigo/frontend/laboratorio/index.php
|
|--------------------------------------------------------------------------
*/

$caminhoPaginaAtual =
    str_replace(
        "\\",
        "/",
        $_SERVER["SCRIPT_NAME"]
        ?? ""
    );


$nomePaginaAtual =
    basename(
        $caminhoPaginaAtual
    );


/*
|--------------------------------------------------------------------------
| USUÁRIO AUTENTICADO
|--------------------------------------------------------------------------
*/

$usuarioAutenticado =
    (
        isset(
            $_SESSION["usuario"]
        )
        &&
        is_array(
            $_SESSION["usuario"]
        )
    );


/*
|--------------------------------------------------------------------------
| PÁGINA INICIAL
|--------------------------------------------------------------------------
|
| Não mostra:
|
| frontend/index.php
|
|--------------------------------------------------------------------------
*/

$estaNaPaginaInicial =
    str_ends_with(
        $caminhoPaginaAtual,
        "/frontend/index.php"
    );


/*
|--------------------------------------------------------------------------
| PAINÉIS
|--------------------------------------------------------------------------
|
| Não precisamos mostrar "Voltar ao painel"
| quando o usuário já está no próprio painel.
|
| Exemplos:
|
| estudante_painel.php
| aprendiz_painel.php
| professor_painel.php
|
|--------------------------------------------------------------------------
*/

$estaEmPainel =
    (
        str_ends_with(
            $nomePaginaAtual,
            "_painel.php"
        )
        ||
        str_contains(
            $caminhoPaginaAtual,
            "/pages/admin/inicio.php"
        )
    );


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

$estaEmLogin =
    str_contains(
        $caminhoPaginaAtual,
        "/pages/login/"
    );


/*
|--------------------------------------------------------------------------
| CADASTRO
|--------------------------------------------------------------------------
*/

$estaEmCadastro =
    str_contains(
        $caminhoPaginaAtual,
        "/pages/cadastro/"
    );


/*
|--------------------------------------------------------------------------
| RECUPERAÇÃO DE SENHA
|--------------------------------------------------------------------------
*/

$estaEmRecuperacao =
    str_contains(
        $caminhoPaginaAtual,
        "/pages/recuperacao/"
    );


/*
|--------------------------------------------------------------------------
| ESCOLHA DE PERFIL
|--------------------------------------------------------------------------
*/

$estaEmEscolhaPerfil =
    str_ends_with(
        $caminhoPaginaAtual,
        "/pages/perfil/escolher.php"
    );


/*
|--------------------------------------------------------------------------
| QUESTIONÁRIOS
|--------------------------------------------------------------------------
|
| Funciona para todos:
|
| estudante_questionario.php
| aprendiz_questionario.php
| professor_questionario.php
|
|--------------------------------------------------------------------------
*/

$estaEmQuestionario =
    str_ends_with(
        $nomePaginaAtual,
        "_questionario.php"
    );


/*
|--------------------------------------------------------------------------
| PÁGINAS DE AUTENTICAÇÃO
|--------------------------------------------------------------------------
*/

$estaEmPaginaAutenticacao =
    (
        $estaEmLogin
        ||
        $estaEmCadastro
        ||
        $estaEmRecuperacao
    );


/*
|--------------------------------------------------------------------------
| DECIDE SE MOSTRA "VOLTAR AO PAINEL"
|--------------------------------------------------------------------------
|
| MOSTRA:
|
| - Aulas Conversadas
| - Conteúdos
| - Laboratório Java
| - Manual
| - Como funciona
| - Caderno Java
| - Sala Global
| - Sala de Aula
| - Perfil
| - Editar perfil
| - demais páginas internas
|
| NÃO MOSTRA:
|
| - Página inicial
| - Painéis
| - Login
| - Cadastro
| - Recuperação
| - Escolha do perfil
| - Questionários
| - Usuário não autenticado
|
|--------------------------------------------------------------------------
*/

$mostrarVoltarPainel =
    (
        $usuarioAutenticado
        &&
        !$estaNaPaginaInicial
        &&
        !$estaEmPainel
        &&
        !$estaEmPaginaAutenticacao
        &&
        !$estaEmEscolhaPerfil
        &&
        !$estaEmQuestionario
    );

?>


<!-- =====================================================
VOLTAR AO PAINEL - CSS
====================================================== -->

<?php if (
    $mostrarVoltarPainel
    &&
    is_file(
        $caminhoCssVoltarPainel
    )
): ?>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/voltar_painel.css?v=<?= filemtime($caminhoCssVoltarPainel) ?>"
>

<?php endif; ?>


<!-- =====================================================
VOLTAR AO PAINEL - COMPONENTE
====================================================== -->

<?php

if (
    $mostrarVoltarPainel
    &&
    is_file(
        $caminhoVoltarPainel
    )
) {

    require
        $caminhoVoltarPainel;

}

?>


<!-- =====================================================
RODAPÉ
====================================================== -->

<footer class="rodape">

    <p>
        Desenvolvido por Robert de Castro Silveira
        e Natanael de Farias Lucena - 2026
    </p>
</footer>


<!-- =====================================================
ASSISTENTE JAVA - CSS
====================================================== -->

<?php if (
    is_file(
        $caminhoCssAssistente
    )
): ?>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/assistente_ia.css?v=<?= filemtime($caminhoCssAssistente) ?>"
>

<?php endif; ?>


<!-- =====================================================
ASSISTENTE JAVA - COMPONENTE
====================================================== -->

<?php

if (
    is_file(
        $caminhoAssistente
    )
) {

    require_once
        $caminhoAssistente;

}

?>


<!-- =====================================================
ASSISTENTE JAVA - JAVASCRIPT
====================================================== -->

<?php if (
    is_file(
        $caminhoJsAssistente
    )
): ?>

<script
    src="<?= BASE_URL ?>/frontend/assets/js/assistente_ia.js?v=<?= filemtime($caminhoJsAssistente) ?>"
></script>

<?php endif; ?>