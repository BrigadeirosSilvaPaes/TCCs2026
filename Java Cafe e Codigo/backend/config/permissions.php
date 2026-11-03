<?php

/*
|--------------------------------------------------------------------------
| PERMISSÕES E TIPOS DE PERFIL
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| A aplicação possui dois conceitos diferentes:
|
| 1. TIPO DE ACESSO DA CONTA
|
|    usuarios.tipo_acesso
|
|    - usuario
|    - professor
|    - admin
|
| 2. TIPO DE PERFIL
|
|    perfil_usuario.tipo_usuario
|
|    - estudante
|    - professor
|    - aprendiz
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| TIPOS DE ACESSO
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "ACCESS_USUARIO"
    )
) {

    define(
        "ACCESS_USUARIO",
        "usuario"
    );

}


if (
    !defined(
        "ACCESS_PROFESSOR"
    )
) {

    define(
        "ACCESS_PROFESSOR",
        "professor"
    );

}


if (
    !defined(
        "ACCESS_ADMIN"
    )
) {

    define(
        "ACCESS_ADMIN",
        "admin"
    );

}


/*
|--------------------------------------------------------------------------
| TIPOS DE PERFIL
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "PROFILE_ESTUDANTE"
    )
) {

    define(
        "PROFILE_ESTUDANTE",
        "estudante"
    );

}


if (
    !defined(
        "PROFILE_PROFESSOR"
    )
) {

    define(
        "PROFILE_PROFESSOR",
        "professor"
    );

}


if (
    !defined(
        "PROFILE_APRENDIZ"
    )
) {

    define(
        "PROFILE_APRENDIZ",
        "aprendiz"
    );

}


/*
|--------------------------------------------------------------------------
| ALIASES DE COMPATIBILIDADE
|--------------------------------------------------------------------------
|
| Mantemos os nomes ROLE_* porque diversos arquivos atuais
| já utilizam essas constantes.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| ACESSO: USUÁRIO
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "ROLE_USUARIO"
    )
) {

    define(
        "ROLE_USUARIO",
        ACCESS_USUARIO
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL / ACESSO: PROFESSOR
|--------------------------------------------------------------------------
|
| Professor utiliza o mesmo valor textual nos dois contextos:
|
| "professor"
|
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "ROLE_PROFESSOR"
    )
) {

    define(
        "ROLE_PROFESSOR",
        PROFILE_PROFESSOR
    );

}


/*
|--------------------------------------------------------------------------
| ACESSO: ADMIN
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "ROLE_ADMIN"
    )
) {

    define(
        "ROLE_ADMIN",
        ACCESS_ADMIN
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL: ESTUDANTE
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "ROLE_ESTUDANTE"
    )
) {

    define(
        "ROLE_ESTUDANTE",
        PROFILE_ESTUDANTE
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL: APRENDIZ
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "ROLE_APRENDIZ"
    )
) {

    define(
        "ROLE_APRENDIZ",
        PROFILE_APRENDIZ
    );

}