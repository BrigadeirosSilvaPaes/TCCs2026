<?php

/*
|--------------------------------------------------------------------------
| SISTEMA CENTRAL DE RESPOSTAS
|--------------------------------------------------------------------------
|
| Este arquivo centraliza a criação das respostas utilizadas pelo sistema.
|
| As mensagens são carregadas automaticamente de:
|
| backend/config/mensagem.php
|
| Dessa forma, Controllers e APIs não precisam armazenar textos
| de mensagens diretamente no código.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CARREGAR ARQUIVO DE MENSAGENS
|--------------------------------------------------------------------------
*/

$mensagens = require_once __DIR__ . "/../config/mensagem.php";


/*
|--------------------------------------------------------------------------
| BUSCAR MENSAGEM
|--------------------------------------------------------------------------
|
| Busca uma mensagem dentro das categorias:
|
| - sucesso
| - erro
| - aviso
| - info
|
| Exemplo:
|
| mensagem("sucesso", "perfil_salvo")
|
| Retorno:
|
| [
|     "titulo" => "Perfil atualizado!",
|     "texto" => "Suas informações foram salvas com sucesso."
| ]
|
|--------------------------------------------------------------------------
*/

function mensagem(

    string $tipo,

    string $chave

): array
{

    global $mensagens;


    /*
    |--------------------------------------------------------------------------
    | VERIFICA SE A CATEGORIA EXISTE
    |--------------------------------------------------------------------------
    */

    if (!isset($mensagens[$tipo])) {

        return [

            "titulo" => "Ops!",

            "texto" => "Mensagem não encontrada."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | VERIFICA SE A CHAVE EXISTE
    |--------------------------------------------------------------------------
    */

    if (!isset($mensagens[$tipo][$chave])) {

        return [

            "titulo" => "Ops!",

            "texto" => "Mensagem não encontrada."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | RETORNA A MENSAGEM
    |--------------------------------------------------------------------------
    */

    return $mensagens[$tipo][$chave];

}


/*
|--------------------------------------------------------------------------
| RESPOSTA DE SUCESSO
|--------------------------------------------------------------------------
|
| Exemplo:
|
| return respostaSucesso("perfil_salvo");
|
| Resultado:
|
| [
|     "status" => true,
|     "mensagem" => [
|         "titulo" => "Perfil atualizado!",
|         "texto" => "Suas informações foram salvas com sucesso."
|     ]
| ]
|
|--------------------------------------------------------------------------
*/

function respostaSucesso(

    string $chave,

    array $dados = []

): array
{

    return [

        "status" => true,

        "mensagem" => mensagem(

            "sucesso",

            $chave

        ),

        "dados" => $dados

    ];

}


/*
|--------------------------------------------------------------------------
| RESPOSTA DE ERRO
|--------------------------------------------------------------------------
|
| Exemplo:
|
| return respostaErro("erro_banco");
|
|--------------------------------------------------------------------------
*/

function respostaErro(

    string $chave,

    array $dados = []

): array
{

    return [

        "status" => false,

        "mensagem" => mensagem(

            "erro",

            $chave

        ),

        "dados" => $dados

    ];

}


/*
|--------------------------------------------------------------------------
| RESPOSTA DE AVISO
|--------------------------------------------------------------------------
|
| Exemplo:
|
| return respostaAviso("login_necessario");
|
|--------------------------------------------------------------------------
*/

function respostaAviso(

    string $chave,

    array $dados = []

): array
{

    return [

        "status" => false,

        "mensagem" => mensagem(

            "aviso",

            $chave

        ),

        "dados" => $dados

    ];

}


/*
|--------------------------------------------------------------------------
| RESPOSTA INFORMATIVA
|--------------------------------------------------------------------------
|
| Exemplo:
|
| return respostaInfo("bem_vindo");
|
|--------------------------------------------------------------------------
*/

function respostaInfo(

    string $chave,

    array $dados = []

): array
{

    return [

        "status" => true,

        "mensagem" => mensagem(

            "info",

            $chave

        ),

        "dados" => $dados

    ];

}


/*
|--------------------------------------------------------------------------
| RESPOSTA PERSONALIZADA
|--------------------------------------------------------------------------
|
| Permite criar uma resposta usando diretamente uma mensagem
| personalizada.
|
| Útil para casos específicos que ainda não possuem uma chave
| cadastrada no arquivo mensagem.php.
|
| Exemplo:
|
| return respostaPersonalizada(
|
|     false,
|
|     "Erro!",
|
|     "Não foi possível realizar esta operação."
|
| );
|
|--------------------------------------------------------------------------
*/

function respostaPersonalizada(

    bool $status,

    string $titulo,

    string $texto,

    array $dados = []

): array
{

    return [

        "status" => $status,

        "mensagem" => [

            "titulo" => $titulo,

            "texto" => $texto

        ],

        "dados" => $dados

    ];

}


/*
|--------------------------------------------------------------------------
| RESPOSTA DE SUCESSO SIMPLES
|--------------------------------------------------------------------------
|
| Esta função pode ser utilizada quando você deseja retornar apenas
| o status e a mensagem, sem dados adicionais.
|
| Mantida como função separada para facilitar a leitura do código.
|
|--------------------------------------------------------------------------
*/

function sucesso(

    string $chave

): array
{

    return respostaSucesso(

        $chave

    );

}




function erro(

    string $chave

): array
{

    return respostaErro(

        $chave

    );

}

function aviso(

    string $chave

): array
{

    return respostaAviso(

        $chave

    );

}

function info(

    string $chave

): array
{

    return respostaInfo(

        $chave

    );

}

?>
