<?php


/*
|--------------------------------------------------------------------------
| VALIDAÇÕES DO SISTEMA
|--------------------------------------------------------------------------
|
| Funções auxiliares para validar
| dados recebidos pelo backend.
|
*/



/**
 * Verifica se um campo está vazio
 */

function campoVazio(

    mixed $valor

): bool {


    return (

        !isset($valor)

        ||

        trim((string)$valor) === ""

    );


}





/**
 * Valida formato de email
 */

function emailValido(

    string $email

): bool {


    return filter_var(

        $email,

        FILTER_VALIDATE_EMAIL

    ) !== false;


}





/**
 * Valida senha mínima
 */

function senhaValida(

    string $senha

): bool {


    return strlen($senha) >= 8;


}





/**
 * Limpa dados recebidos
 */

function limparTexto(

    string $texto

): string {


    return htmlspecialchars(

        trim($texto),

        ENT_QUOTES,

        "UTF-8"

    );


}





/**
 * Valida tamanho máximo de texto
 */

function tamanhoValido(

    string $texto,

    int $maximo

): bool {


    return mb_strlen($texto) <= $maximo;


}





/**
 * Verifica se valor está dentro de opções permitidas
 */

function opcaoValida(

    string $valor,

    array $opcoes

): bool {


    return in_array(

        $valor,

        $opcoes,

        true

    );


}