<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES DE SEGURANÇA
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Centraliza regras gerais relacionadas à segurança
| da aplicação.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| ALGORITMO DE SENHA
|--------------------------------------------------------------------------
|
| PASSWORD_DEFAULT permite que o PHP utilize
| o algoritmo recomendado pela versão instalada.
|
| Atualmente normalmente utiliza bcrypt,
| podendo evoluir futuramente sem alterar
| a lógica da aplicação.
|
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "PASSWORD_ALGORITHM"
    )
) {

    define(
        "PASSWORD_ALGORITHM",
        PASSWORD_DEFAULT
    );

}


/*
|--------------------------------------------------------------------------
| TAMANHO MÍNIMO DA SENHA
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "MIN_PASSWORD_LENGTH"
    )
) {

    define(
        "MIN_PASSWORD_LENGTH",
        6
    );

}


/*
|--------------------------------------------------------------------------
| TAMANHO MÁXIMO DA SENHA
|--------------------------------------------------------------------------
|
| Evita entradas excessivamente grandes
| e mantém uma regra única em:
|
| - cadastro;
| - alteração;
| - recuperação de senha.
|
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "MAX_PASSWORD_LENGTH"
    )
) {

    define(
        "MAX_PASSWORD_LENGTH",
        72
    );

}