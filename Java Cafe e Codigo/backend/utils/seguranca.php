<?php


/*
|--------------------------------------------------------------------------
| FUNÇÕES DE SEGURANÇA
|--------------------------------------------------------------------------
|
| Funções auxiliares utilizadas
| para proteção do sistema.
|
*/



/**
 * Cria senha criptografada
 */

function gerarSenhaSegura(

    string $senha

): string {


    return password_hash(

        $senha,

        PASSWORD_DEFAULT

    );


}





/**
 * Verifica senha informada
 * contra senha salva no banco
 */

function verificarSenha(

    string $senha,

    string $hash

): bool {


    return password_verify(

        $senha,

        $hash

    );


}





/**
 * Verifica se usuário está autenticado
 */

function usuarioLogado(): bool
{


    return isset(

        $_SESSION["usuario"]

    );


}





/**
 * Retorna ID do usuário logado
 */

function usuarioId(): ?int
{


    return isset($_SESSION["usuario_id"])

        ?

        (int) $_SESSION["usuario_id"]

        :

        null;


}





/**
 * Bloqueia acesso sem login
 */

function exigirLogin(): void
{


    if(!usuarioLogado()){


        $_SESSION["erro"] =

        "Você precisa estar logado para acessar esta página.";


        header(

            "Location: /java-cafe-e-codigo/frontend/pages/login/login.php"

        );


        exit;


    }


}





/**
 * Gera código aleatório
 * usado em confirmação e recuperação
 */

function gerarCodigo(

    int $tamanho = 6

): string {


    return str_pad(

        random_int(

            0,

            (10 ** $tamanho) - 1

        ),

        $tamanho,

        "0",

        STR_PAD_LEFT

    );


}





/**
 * Remove espaços e caracteres
 * perigosos de entrada
 */

function tratarEntrada(

    string $valor

): string {


    return htmlspecialchars(

        trim($valor),

        ENT_QUOTES,

        "UTF-8"

    );


}