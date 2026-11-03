<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES DE UPLOAD
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Centraliza configurações gerais utilizadas
| no armazenamento de arquivos enviados pelos usuários.
|
| Os arquivos devem permanecer dentro do backend/storage,
| fora das páginas públicas do frontend.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| TAMANHO MÁXIMO PADRÃO
|--------------------------------------------------------------------------
|
| Limite geral:
|
| 5 MB
|
| Alguns módulos podem possuir regras próprias.
|
| Exemplo:
|
| Sala de Aula atualmente possui validação específica
| para determinados tipos de arquivo e pode utilizar
| um limite diferente deste.
|
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "UPLOAD_MAX_SIZE"
    )
) {

    define(
        "UPLOAD_MAX_SIZE",
        5 * 1024 * 1024
    );

}


/*
|--------------------------------------------------------------------------
| DIRETÓRIO PRINCIPAL DE UPLOAD
|--------------------------------------------------------------------------
|
| Caminho atual:
|
| backend/storage/uploads
|
| UPLOAD_PATH é mantido por compatibilidade
| com código que já utiliza essa constante.
|
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "UPLOAD_PATH"
    )
) {

    define(
        "UPLOAD_PATH",
        UPLOAD_STORAGE_PATH
    );

}


/*
|--------------------------------------------------------------------------
| PUBLICAÇÕES DA SALA GLOBAL
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "UPLOAD_PUBLICACOES_PATH"
    )
) {

    define(
        "UPLOAD_PUBLICACOES_PATH",
        UPLOAD_PATH . "/publicacoes"
    );

}


/*
|--------------------------------------------------------------------------
| SALAS DE AULA
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "UPLOAD_SALAS_PATH"
    )
) {

    define(
        "UPLOAD_SALAS_PATH",
        UPLOAD_PATH . "/salas"
    );

}


/*
|--------------------------------------------------------------------------
| EXTENSÕES PADRÃO
|--------------------------------------------------------------------------
|
| Lista genérica utilizada apenas por módulos
| que adotarem a configuração global.
|
| Módulos com necessidades específicas devem validar
| suas próprias extensões e tipos MIME.
|
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "ALLOWED_EXTENSIONS"
    )
) {

    define(
        "ALLOWED_EXTENSIONS",
        [
            "jpg",
            "jpeg",
            "png",
            "pdf"
        ]
    );

}