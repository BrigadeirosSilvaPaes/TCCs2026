<?php

require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../config/bootstrap.php";
require_once __DIR__ . "/../services/EmailService.php";


/*
|--------------------------------------------------------------------------
| ACEITA APENAS POST
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php#contato"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| CAMPO ANTISPAM
|--------------------------------------------------------------------------
|
| Este campo fica invisível para pessoas.
| Robôs costumam preenchê-lo automaticamente.
|
*/

$site = trim(
    $_POST["site"] ?? ""
);

if ($site !== "") {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php?contato=sucesso#contato"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| RECEBE OS DADOS
|--------------------------------------------------------------------------
*/

$nome = trim(
    $_POST["nome"] ?? ""
);

$email = trim(
    $_POST["email"] ?? ""
);

$tipo = trim(
    $_POST["tipo"] ?? ""
);

$assunto = trim(
    $_POST["assunto"] ?? ""
);

$mensagem = trim(
    $_POST["mensagem"] ?? ""
);


/*
|--------------------------------------------------------------------------
| TIPOS PERMITIDOS
|--------------------------------------------------------------------------
*/

$tiposPermitidos = [

    "Dúvida",
    "Sugestão",
    "Erro encontrado",
    "Contato",
    "Outro"

];


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO DE CAMPOS OBRIGATÓRIOS
|--------------------------------------------------------------------------
*/

if (
    $nome === "" ||
    $email === "" ||
    $tipo === "" ||
    $assunto === "" ||
    $mensagem === ""
) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php?contato=campos#contato"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO DO NOME
|--------------------------------------------------------------------------
*/

if (
    mb_strlen($nome) < 3 ||
    mb_strlen($nome) > 100
) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php?contato=nome#contato"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO DO E-MAIL
|--------------------------------------------------------------------------
*/

if (
    !filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    ) ||
    mb_strlen($email) > 150
) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php?contato=email#contato"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO DO TIPO
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $tipo,
        $tiposPermitidos,
        true
    )
) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php?contato=tipo#contato"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO DO ASSUNTO
|--------------------------------------------------------------------------
*/

if (
    mb_strlen($assunto) < 3 ||
    mb_strlen($assunto) > 120
) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php?contato=assunto#contato"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VALIDAÇÃO DA MENSAGEM
|--------------------------------------------------------------------------
*/

if (
    mb_strlen($mensagem) < 10 ||
    mb_strlen($mensagem) > 3000
) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php?contato=mensagem#contato"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| CONTROLE SIMPLES DE ENVIO
|--------------------------------------------------------------------------
|
| Evita vários envios seguidos pela mesma sessão.
|
*/

$agora = time();

$ultimoEnvio = $_SESSION["ultimo_contato_enviado"] ?? 0;

if (($agora - $ultimoEnvio) < 60) {

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php?contato=limite#contato"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| ENVIA O E-MAIL
|--------------------------------------------------------------------------
*/

$enviado = EmailService::enviarContato(
    $nome,
    $email,
    $tipo,
    $assunto,
    $mensagem
);


/*
|--------------------------------------------------------------------------
| RESULTADO
|--------------------------------------------------------------------------
*/

if ($enviado) {

    $_SESSION["ultimo_contato_enviado"] = time();

    header(
        "Location: " .
        BASE_URL .
        "/frontend/index.php?contato=sucesso#contato"
    );

    exit;

}


header(
    "Location: " .
    BASE_URL .
    "/frontend/index.php?contato=erro#contato"
);

exit;