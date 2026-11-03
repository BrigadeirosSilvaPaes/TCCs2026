<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/helpers.php";


/*
|--------------------------------------------------------------------------
| NOME DA CHAVE NA SESSÃO
|--------------------------------------------------------------------------
|
| O token fica isolado dentro de:
|
| $_SESSION["seguranca"]["csrf_token"]
|
*/

const CSRF_SESSION_KEY =
    "csrf_token";


/*
|--------------------------------------------------------------------------
| GARANTE ESTRUTURA DE SEGURANÇA
|--------------------------------------------------------------------------
*/

function csrfGarantirSessao(): void
{

    garantirSessao();


    if (
        !isset($_SESSION["seguranca"]) ||
        !is_array($_SESSION["seguranca"])
    ) {

        $_SESSION["seguranca"] = [];

    }

}


/*
|--------------------------------------------------------------------------
| GERA / RECUPERA TOKEN CSRF
|--------------------------------------------------------------------------
|
| O token é criado com 32 bytes aleatórios criptograficamente seguros.
|
| bin2hex transforma os 32 bytes em uma string hexadecimal
| de 64 caracteres.
|
| Enquanto a sessão existir, o mesmo token pode ser reutilizado
| pelos formulários daquela sessão.
|
*/

function csrfToken(): string
{

    csrfGarantirSessao();


    $token =
        $_SESSION["seguranca"][CSRF_SESSION_KEY]
        ?? null;


    /*
    |--------------------------------------------------------------------------
    | TOKEN EXISTENTE VÁLIDO
    |--------------------------------------------------------------------------
    */

    if (
        is_string($token) &&
        preg_match(
            "/^[a-f0-9]{64}$/",
            $token
        ) === 1
    ) {

        return $token;

    }


    /*
    |--------------------------------------------------------------------------
    | GERA NOVO TOKEN
    |--------------------------------------------------------------------------
    */

    $token =
        bin2hex(
            random_bytes(32)
        );


    $_SESSION["seguranca"][CSRF_SESSION_KEY] =
        $token;


    return $token;

}


/*
|--------------------------------------------------------------------------
| GERA CAMPO HIDDEN PARA FORMULÁRIOS
|--------------------------------------------------------------------------
|
| Uso:
|
| <?= csrfCampo() ?>
|
| Resultado:
|
| <input
|     type="hidden"
|     name="csrf_token"
|     value="..."
| >
|
*/

function csrfCampo(): string
{

    $token =
        htmlspecialchars(
            csrfToken(),
            ENT_QUOTES,
            "UTF-8"
        );


    return
        '<input type="hidden" name="csrf_token" value="' .
        $token .
        '">';

}


/*
|--------------------------------------------------------------------------
| RECUPERA TOKEN ENVIADO
|--------------------------------------------------------------------------
*/

function csrfTokenRecebido(): string
{

    $token =
        $_POST["csrf_token"]
        ?? "";


    if (!is_string($token)) {

        return "";

    }


    return trim($token);

}


/*
|--------------------------------------------------------------------------
| VALIDA TOKEN
|--------------------------------------------------------------------------
|
| hash_equals realiza comparação segura e reduz risco
| de ataques baseados em tempo de comparação.
|
*/

function csrfValidar(
    ?string $tokenRecebido = null
): bool
{

    csrfGarantirSessao();


    $tokenSessao =
        $_SESSION["seguranca"][CSRF_SESSION_KEY]
        ?? null;


    /*
    |--------------------------------------------------------------------------
    | NÃO EXISTE TOKEN NA SESSÃO
    |--------------------------------------------------------------------------
    */

    if (
        !is_string($tokenSessao) ||
        $tokenSessao === ""
    ) {

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | TOKEN RECEBIDO
    |--------------------------------------------------------------------------
    */

    if ($tokenRecebido === null) {

        $tokenRecebido =
            csrfTokenRecebido();

    }


    if (
        !is_string($tokenRecebido) ||
        $tokenRecebido === ""
    ) {

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | FORMATO ESPERADO
    |--------------------------------------------------------------------------
    */

    if (
        preg_match(
            "/^[a-f0-9]{64}$/",
            $tokenRecebido
        ) !== 1
    ) {

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | COMPARAÇÃO SEGURA
    |--------------------------------------------------------------------------
    */

    return hash_equals(
        $tokenSessao,
        $tokenRecebido
    );

}


/*
|--------------------------------------------------------------------------
| REGENERA TOKEN
|--------------------------------------------------------------------------
|
| Pode ser utilizado futuramente após operações sensíveis,
| troca de senha, mudança de privilégios ou novo login.
|
*/

function csrfRegenerar(): string
{

    csrfGarantirSessao();


    $novoToken =
        bin2hex(
            random_bytes(32)
        );


    $_SESSION["seguranca"][CSRF_SESSION_KEY] =
        $novoToken;


    return $novoToken;

}


/*
|--------------------------------------------------------------------------
| REMOVE TOKEN
|--------------------------------------------------------------------------
|
| Útil durante logout ou encerramento de sessão.
|
*/

function csrfLimpar(): void
{

    csrfGarantirSessao();


    unset(
        $_SESSION["seguranca"][CSRF_SESSION_KEY]
    );

}