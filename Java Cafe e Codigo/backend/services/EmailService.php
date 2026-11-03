<?php

require_once __DIR__ . "/../config/bootstrap.php";
require_once VENDOR_PATH . "/autoload.php";

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


class EmailService
{

    /*
    |--------------------------------------------------------------------------
    | CONFIGURA PHPMailer
    |--------------------------------------------------------------------------
    */

    private static function configurar(): PHPMailer
    {

        $mail = new PHPMailer(true);


        /*
        |--------------------------------------------------------------------------
        | DEBUG SMTP
        |--------------------------------------------------------------------------
        |
        | Ative somente durante testes de envio.
        |
        */

        // $mail->SMTPDebug = 2;
        // $mail->Debugoutput = "html";


        /*
        |--------------------------------------------------------------------------
        | CONFIGURAÇÃO SMTP
        |--------------------------------------------------------------------------
        */

        $mail->isSMTP();

        $mail->Host = EMAIL_HOST;

        $mail->SMTPAuth = true;

        $mail->AuthType = "LOGIN";

        $mail->Username = EMAIL_USERNAME;

        $mail->Password = EMAIL_PASSWORD;

        $mail->SMTPSecure = EMAIL_SECURITY;

        $mail->Port = EMAIL_PORT;


        /*
        |--------------------------------------------------------------------------
        | CONFIGURAÇÃO DE CARACTERES
        |--------------------------------------------------------------------------
        */

        $mail->CharSet = EMAIL_CHARSET;

        $mail->setLanguage(
            EMAIL_LANGUAGE
        );


        /*
        |--------------------------------------------------------------------------
        | REMETENTE
        |--------------------------------------------------------------------------
        */

        $mail->setFrom(
            EMAIL_FROM,
            EMAIL_FROM_NAME
        );

        $mail->Sender = EMAIL_FROM;


        /*
        |--------------------------------------------------------------------------
        | HOSTNAME
        |--------------------------------------------------------------------------
        */

        $mail->Hostname =
            $_SERVER["SERVER_NAME"]
            ?? "localhost";


        return $mail;

    }


    /*
    |--------------------------------------------------------------------------
    | ENVIA E-MAIL
    |--------------------------------------------------------------------------
    |
    | $imagensIncorporadas permite adicionar imagens diretamente
    | dentro do próprio e-mail através de CID.
    |
    | Exemplo:
    |
    | [
    |     [
    |         "caminho" => ".../mascote.png",
    |         "cid" => "mascote-jcc",
    |         "nome" => "mascote.png"
    |     ]
    | ]
    |
    */

    private static function enviar(
        string $destinatario,
        string $assunto,
        string $mensagem,
        ?string $emailResposta = null,
        ?string $nomeResposta = null,
        array $imagensIncorporadas = [],
        ?string $mensagemTexto = null
    ): bool {

        try {

            $mail =
                self::configurar();


            /*
            |--------------------------------------------------------------------------
            | DESTINATÁRIO
            |--------------------------------------------------------------------------
            */

            $mail->addAddress(
                $destinatario
            );


            /*
            |--------------------------------------------------------------------------
            | ENDEREÇO PARA RESPOSTA
            |--------------------------------------------------------------------------
            */

            if (
                $emailResposta !== null &&
                filter_var(
                    $emailResposta,
                    FILTER_VALIDATE_EMAIL
                )
            ) {

                $mail->addReplyTo(
                    $emailResposta,
                    $nomeResposta ?? ""
                );

            }


            /*
            |--------------------------------------------------------------------------
            | IMAGENS INCORPORADAS
            |--------------------------------------------------------------------------
            */

            foreach (
                $imagensIncorporadas
                as $imagem
            ) {

                $caminho =
                    $imagem["caminho"]
                    ?? "";

                $cid =
                    $imagem["cid"]
                    ?? "";

                $nome =
                    $imagem["nome"]
                    ?? basename($caminho);


                if (
                    $caminho !== "" &&
                    $cid !== "" &&
                    is_file($caminho)
                ) {

                    $mail->addEmbeddedImage(
                        $caminho,
                        $cid,
                        $nome
                    );

                }

            }


            /*
            |--------------------------------------------------------------------------
            | FORMATO
            |--------------------------------------------------------------------------
            */

            $mail->isHTML(true);

            $mail->Subject =
                $assunto;

            $mail->Body =
                $mensagem;


            /*
            |--------------------------------------------------------------------------
            | VERSÃO EM TEXTO
            |--------------------------------------------------------------------------
            */

            if (
                $mensagemTexto !== null
            ) {

                $mail->AltBody =
                    $mensagemTexto;

            } else {

                $mail->AltBody =
                    html_entity_decode(
                        strip_tags(
                            str_replace(
                                [
                                    "<br>",
                                    "<br/>",
                                    "<br />",
                                    "</p>",
                                    "</div>",
                                    "</tr>"
                                ],
                                PHP_EOL,
                                $mensagem
                            )
                        ),
                        ENT_QUOTES,
                        "UTF-8"
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | ENVIA
            |--------------------------------------------------------------------------
            */

            return $mail->send();


        } catch (Exception $e) {

            if (
                defined("ENVIRONMENT") &&
                ENVIRONMENT === "development"
            ) {

                error_log(
                    "Erro ao enviar e-mail: " .
                    $e->getMessage()
                );

            }

            return false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | TEMPLATE DE CÓDIGO
    |--------------------------------------------------------------------------
    */

    private static function criarEmailCodigo(
        string $titulo,
        string $descricao,
        string $codigo,
        string $textoAviso,
        string $cidMascote
    ): string {

        $tituloSeguro =
            htmlspecialchars(
                $titulo,
                ENT_QUOTES,
                "UTF-8"
            );

        $descricaoSegura =
            htmlspecialchars(
                $descricao,
                ENT_QUOTES,
                "UTF-8"
            );

        $codigoSeguro =
            htmlspecialchars(
                $codigo,
                ENT_QUOTES,
                "UTF-8"
            );

        $avisoSeguro =
            htmlspecialchars(
                $textoAviso,
                ENT_QUOTES,
                "UTF-8"
            );


        return "
        <!DOCTYPE html>

        <html lang='pt-BR'>

        <head>

            <meta charset='UTF-8'>

            <meta
                name='viewport'
                content='width=device-width, initial-scale=1.0'
            >

            <title>
                {$tituloSeguro}
            </title>

        </head>

        <body
            style='
                margin: 0;
                padding: 0;
                background-color: #f1f5f9;
                font-family: Arial, Helvetica, sans-serif;
                color: #1e293b;
            '
        >

            <table
                role='presentation'
                width='100%'
                cellpadding='0'
                cellspacing='0'
                border='0'
                style='
                    width: 100%;
                    background-color: #f1f5f9;
                '
            >

                <tr>

                    <td
                        align='center'
                        style='
                            padding: 36px 16px;
                        '
                    >

                        <table
                            role='presentation'
                            width='100%'
                            cellpadding='0'
                            cellspacing='0'
                            border='0'
                            style='
                                width: 100%;
                                max-width: 620px;
                                overflow: hidden;
                                background-color: #ffffff;
                                border: 1px solid #e2e8f0;
                                border-radius: 18px;
                            '
                        >


                            <!-- =================================================
                            CABEÇALHO
                            ================================================== -->

                            <tr>

                                <td
                                    align='center'
                                    style='
                                        padding: 34px 30px 30px;
                                        background-color: #172033;
                                    '
                                >

                                    <div
                                        style='
                                            margin-bottom: 8px;
                                            color: #38bdf8;
                                            font-size: 12px;
                                            font-weight: bold;
                                            letter-spacing: 1.5px;
                                        '
                                    >

                                        JAVA, CAFÉ E CÓDIGO

                                    </div>

                                    <h1
                                        style='
                                            margin: 0;
                                            color: #ffffff;
                                            font-size: 26px;
                                            line-height: 1.3;
                                        '
                                    >

                                        Plataforma de apoio ao aprendizado

                                    </h1>

                                    <p
                                        style='
                                            margin: 10px 0 0;
                                            color: #cbd5e1;
                                            font-size: 14px;
                                            line-height: 1.5;
                                        '
                                    >

                                        Aprenda Java de forma acessível,
                                        organizada e interativa.

                                    </p>

                                </td>

                            </tr>


                            <!-- =================================================
                            MASCOTE
                            ================================================== -->

                            <tr>

                                <td
                                    align='center'
                                    style='
                                        padding: 32px 30px 6px;
                                    '
                                >

                                    <img
                                        src='cid:{$cidMascote}'
                                        alt='Mascote Java Café e Código'
                                        width='125'
                                        style='
                                            width: 125px;
                                            max-width: 125px;
                                            height: auto;
                                            display: block;
                                            margin: 0 auto;
                                            border: 0;
                                        '
                                    >

                                </td>

                            </tr>


                            <!-- =================================================
                            CONTEÚDO
                            ================================================== -->

                            <tr>

                                <td
                                    style='
                                        padding: 18px 38px 36px;
                                    '
                                >

                                    <h2
                                        style='
                                            margin: 0 0 16px;
                                            color: #0f172a;
                                            font-size: 27px;
                                            line-height: 1.3;
                                            text-align: center;
                                        '
                                    >

                                        {$tituloSeguro}

                                    </h2>


                                    <p
                                        style='
                                            margin: 0 auto 26px;
                                            max-width: 480px;
                                            color: #475569;
                                            font-size: 16px;
                                            line-height: 1.7;
                                            text-align: center;
                                        '
                                    >

                                        {$descricaoSegura}

                                    </p>


                                    <!-- CÓDIGO -->

                                    <table
                                        role='presentation'
                                        width='100%'
                                        cellpadding='0'
                                        cellspacing='0'
                                        border='0'
                                    >

                                        <tr>

                                            <td align='center'>

                                                <div
                                                    style='
                                                        display: inline-block;
                                                        padding: 18px 28px;
                                                        background-color: #eff6ff;
                                                        border: 2px dashed #38bdf8;
                                                        border-radius: 14px;
                                                        color: #0f3b73;
                                                        font-family: Arial, Helvetica, sans-serif;
                                                        font-size: 36px;
                                                        font-weight: bold;
                                                        letter-spacing: 8px;
                                                        text-align: center;
                                                    '
                                                >

                                                    {$codigoSeguro}

                                                </div>

                                            </td>

                                        </tr>

                                    </table>


                                    <!-- AVISO -->

                                    <div
                                        style='
                                            margin-top: 30px;
                                            padding: 18px 20px;
                                            background-color: #f8fafc;
                                            border-left: 4px solid #38bdf8;
                                            border-radius: 8px;
                                        '
                                    >

                                        <div
                                            style='
                                                margin-bottom: 6px;
                                                color: #0f172a;
                                                font-size: 14px;
                                                font-weight: bold;
                                            '
                                        >

                                            Importante

                                        </div>

                                        <p
                                            style='
                                                margin: 0;
                                                color: #64748b;
                                                font-size: 14px;
                                                line-height: 1.6;
                                            '
                                        >

                                            {$avisoSeguro}

                                        </p>

                                    </div>


                                    <!-- SEGURANÇA -->

                                    <p
                                        style='
                                            margin: 26px 0 0;
                                            color: #64748b;
                                            font-size: 13px;
                                            line-height: 1.6;
                                            text-align: center;
                                        '
                                    >

                                        Nunca compartilhe este código
                                        com outras pessoas.

                                    </p>

                                </td>

                            </tr>


                            <!-- =================================================
                            RODAPÉ
                            ================================================== -->

                            <tr>

                                <td
                                    align='center'
                                    style='
                                        padding: 24px 30px;
                                        background-color: #f8fafc;
                                        border-top: 1px solid #e2e8f0;
                                    '
                                >

                                    <strong
                                        style='
                                            display: block;
                                            margin-bottom: 7px;
                                            color: #334155;
                                            font-size: 14px;
                                        '
                                    >

                                        Java, Café e Código

                                    </strong>

                                    <p
                                        style='
                                            margin: 0;
                                            color: #94a3b8;
                                            font-size: 12px;
                                            line-height: 1.6;
                                        '
                                    >

                                        Plataforma web de apoio ao
                                        aprendizado de programação em Java.

                                    </p>

                                    <p
                                        style='
                                            margin: 8px 0 0;
                                            color: #94a3b8;
                                            font-size: 12px;
                                        '
                                    >

                                        © 2026 Java, Café e Código

                                    </p>

                                </td>

                            </tr>


                        </table>

                    </td>

                </tr>

            </table>

        </body>

        </html>
        ";

    }


    /*
    |--------------------------------------------------------------------------
    | E-MAIL DE TESTE
    |--------------------------------------------------------------------------
    */

    public static function enviarTeste(
        string $destinatario
    ): bool {

        $mensagem = "
        <!DOCTYPE html>

        <html lang='pt-BR'>

        <head>
            <meta charset='UTF-8'>
        </head>

        <body
            style='
                margin: 0;
                padding: 30px;
                background-color: #f1f5f9;
                font-family: Arial, Helvetica, sans-serif;
            '
        >

            <div
                style='
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 35px;
                    background-color: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 16px;
                    text-align: center;
                '
            >

                <h1
                    style='
                        margin-top: 0;
                        color: #0f172a;
                    '
                >

                    Java, Café e Código

                </h1>

                <p
                    style='
                        color: #475569;
                        line-height: 1.7;
                    '
                >

                    A integração com o servidor SMTP
                    está funcionando corretamente.

                </p>

            </div>

        </body>

        </html>
        ";


        return self::enviar(

            $destinatario,

            "Teste SMTP - Java Café e Código",

            $mensagem,

            null,

            null,

            [],

            "Java, Café e Código\n\nA integração com o servidor SMTP está funcionando corretamente."

        );

    }


    /*
    |--------------------------------------------------------------------------
    | E-MAIL DE CONFIRMAÇÃO DE CADASTRO
    |--------------------------------------------------------------------------
    */

    public static function enviarCodigo(
        string $destinatario,
        string $codigo
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | MASCOTE
        |--------------------------------------------------------------------------
        */

        $mascote =
            __DIR__ .
            "/../../frontend/assets/img/mascote/jcc-acerto.png";


        /*
        |--------------------------------------------------------------------------
        | CORPO
        |--------------------------------------------------------------------------
        */

        $corpo =
            self::criarEmailCodigo(

                "Confirmação de cadastro",

                "Seu cadastro está quase pronto. Utilize o código abaixo para confirmar seu endereço de e-mail e concluir a criação da sua conta.",

                $codigo,

                "Este código possui validade limitada. Caso ele expire, será necessário solicitar um novo código de confirmação.",

                "mascote-confirmacao"

            );


        /*
        |--------------------------------------------------------------------------
        | TEXTO ALTERNATIVO
        |--------------------------------------------------------------------------
        */

        $texto =

            "Java, Café e Código\n\n" .

            "Confirmação de cadastro\n\n" .

            "Seu cadastro está quase pronto.\n" .

            "Utilize o código abaixo para confirmar seu endereço de e-mail:\n\n" .

            $codigo . "\n\n" .

            "Este código possui validade limitada.\n" .

            "Nunca compartilhe este código com outras pessoas.\n\n" .

            "© 2026 Java, Café e Código";


        return self::enviar(

            $destinatario,

            "Confirme seu cadastro - Java Café e Código",

            $corpo,

            null,

            null,

            [
                [
                    "caminho" =>
                        $mascote,

                    "cid" =>
                        "mascote-confirmacao",

                    "nome" =>
                        "jcc-acerto.png"
                ]
            ],

            $texto

        );

    }


    /*
    |--------------------------------------------------------------------------
    | E-MAIL DE RECUPERAÇÃO DE SENHA
    |--------------------------------------------------------------------------
    */

    public static function enviarRecuperacaoSenha(
        string $destinatario,
        string $codigo
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | MASCOTE
        |--------------------------------------------------------------------------
        */

        $mascote =
            __DIR__ .
            "/../../frontend/assets/img/mascote/jcc-pergunta.png";


        /*
        |--------------------------------------------------------------------------
        | CORPO
        |--------------------------------------------------------------------------
        */

        $corpo =
            self::criarEmailCodigo(

                "Recuperação de senha",

                "Recebemos uma solicitação para redefinir a senha da sua conta. Utilize o código abaixo para continuar com a recuperação.",

                $codigo,

                "Se você não solicitou a recuperação de senha, ignore este e-mail. Nenhuma alteração será realizada sem a confirmação do código.",

                "mascote-recuperacao"

            );


        /*
        |--------------------------------------------------------------------------
        | TEXTO ALTERNATIVO
        |--------------------------------------------------------------------------
        */

        $texto =

            "Java, Café e Código\n\n" .

            "Recuperação de senha\n\n" .

            "Recebemos uma solicitação para redefinir sua senha.\n\n" .

            "Código de recuperação:\n\n" .

            $codigo . "\n\n" .

            "Se você não solicitou esta recuperação, ignore este e-mail.\n" .

            "Nunca compartilhe este código com outras pessoas.\n\n" .

            "© 2026 Java, Café e Código";


        return self::enviar(

            $destinatario,

            "Recuperação de senha - Java Café e Código",

            $corpo,

            null,

            null,

            [
                [
                    "caminho" =>
                        $mascote,

                    "cid" =>
                        "mascote-recuperacao",

                    "nome" =>
                        "jcc-pergunta.png"
                ]
            ],

            $texto

        );

    }


    /*
    |--------------------------------------------------------------------------
    | E-MAIL DE AVISO
    |--------------------------------------------------------------------------
    */

    public static function enviarAviso(
        string $destinatario,
        string $titulo,
        string $mensagem
    ): bool {

        $tituloSeguro =
            htmlspecialchars(
                $titulo,
                ENT_QUOTES,
                "UTF-8"
            );

        $mensagemSegura =
            nl2br(
                htmlspecialchars(
                    $mensagem,
                    ENT_QUOTES,
                    "UTF-8"
                )
            );


        $corpo = "
        <!DOCTYPE html>

        <html lang='pt-BR'>

        <head>

            <meta charset='UTF-8'>

        </head>

        <body
            style='
                margin: 0;
                padding: 30px;
                background-color: #f1f5f9;
                font-family: Arial, Helvetica, sans-serif;
                color: #1e293b;
            '
        >

            <div
                style='
                    max-width: 620px;
                    margin: 0 auto;
                    overflow: hidden;
                    background-color: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 16px;
                '
            >

                <div
                    style='
                        padding: 26px 30px;
                        background-color: #172033;
                        color: #ffffff;
                    '
                >

                    <div
                        style='
                            margin-bottom: 7px;
                            color: #38bdf8;
                            font-size: 12px;
                            font-weight: bold;
                            letter-spacing: 1px;
                        '
                    >

                        JAVA, CAFÉ E CÓDIGO

                    </div>

                    <h1
                        style='
                            margin: 0;
                            font-size: 24px;
                        '
                    >

                        {$tituloSeguro}

                    </h1>

                </div>

                <div
                    style='
                        padding: 30px;
                        color: #475569;
                        font-size: 15px;
                        line-height: 1.7;
                    '
                >

                    {$mensagemSegura}

                </div>

            </div>

        </body>

        </html>
        ";


        return self::enviar(

            $destinatario,

            $titulo,

            $corpo,

            null,

            null,

            [],

            "Java, Café e Código\n\n{$titulo}\n\n{$mensagem}"

        );

    }


    /*
    |--------------------------------------------------------------------------
    | E-MAIL DO FORMULÁRIO DE CONTATO
    |--------------------------------------------------------------------------
    */

    public static function enviarContato(
        string $nome,
        string $email,
        string $tipo,
        string $assunto,
        string $mensagem
    ): bool {


        /*
        |--------------------------------------------------------------------------
        | ESCAPA OS DADOS
        |--------------------------------------------------------------------------
        */

        $nomeSeguro =
            htmlspecialchars(
                $nome,
                ENT_QUOTES,
                "UTF-8"
            );

        $emailSeguro =
            htmlspecialchars(
                $email,
                ENT_QUOTES,
                "UTF-8"
            );

        $tipoSeguro =
            htmlspecialchars(
                $tipo,
                ENT_QUOTES,
                "UTF-8"
            );

        $assuntoSeguro =
            htmlspecialchars(
                $assunto,
                ENT_QUOTES,
                "UTF-8"
            );

        $mensagemSegura =
            nl2br(
                htmlspecialchars(
                    $mensagem,
                    ENT_QUOTES,
                    "UTF-8"
                )
            );


        /*
        |--------------------------------------------------------------------------
        | DESTINATÁRIO OFICIAL
        |--------------------------------------------------------------------------
        */

        $destinatario =
            EMAIL_CONTACT;


        /*
        |--------------------------------------------------------------------------
        | ASSUNTO
        |--------------------------------------------------------------------------
        */

        $tituloEmail =
            "[Java, Café e Código] " .
            $tipo .
            " - " .
            $assunto;


        /*
        |--------------------------------------------------------------------------
        | CORPO
        |--------------------------------------------------------------------------
        */

        $corpoEmail = "
        <!DOCTYPE html>

        <html lang='pt-BR'>

        <head>

            <meta charset='UTF-8'>

        </head>

        <body
            style='
                margin: 0;
                padding: 30px;
                background-color: #f1f5f9;
                font-family: Arial, Helvetica, sans-serif;
                color: #1e293b;
            '
        >

            <div
                style='
                    max-width: 680px;
                    margin: 0 auto;
                    overflow: hidden;
                    background-color: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 16px;
                '
            >

                <div
                    style='
                        padding: 28px 30px;
                        background-color: #172033;
                        color: #ffffff;
                    '
                >

                    <div
                        style='
                            margin-bottom: 8px;
                            color: #38bdf8;
                            font-size: 12px;
                            font-weight: bold;
                            letter-spacing: 1px;
                        '
                    >

                        NOVA MENSAGEM DE CONTATO

                    </div>

                    <h1
                        style='
                            margin: 0;
                            font-size: 25px;
                            line-height: 1.3;
                        '
                    >

                        Java, Café e Código

                    </h1>

                </div>


                <div style='padding: 30px;'>

                    <p
                        style='
                            margin-top: 0;
                            color: #475569;
                            line-height: 1.7;
                        '
                    >

                        Uma nova mensagem foi enviada
                        pelo formulário de contato da plataforma.

                    </p>


                    <table
                        cellpadding='0'
                        cellspacing='0'
                        style='
                            width: 100%;
                            margin: 25px 0;
                            border-collapse: collapse;
                        '
                    >

                        <tr>

                            <td
                                style='
                                    width: 130px;
                                    padding: 12px;
                                    border-bottom: 1px solid #e2e8f0;
                                    color: #64748b;
                                    font-size: 13px;
                                    font-weight: bold;
                                '
                            >
                                Nome
                            </td>

                            <td
                                style='
                                    padding: 12px;
                                    border-bottom: 1px solid #e2e8f0;
                                '
                            >
                                {$nomeSeguro}
                            </td>

                        </tr>


                        <tr>

                            <td
                                style='
                                    padding: 12px;
                                    border-bottom: 1px solid #e2e8f0;
                                    color: #64748b;
                                    font-size: 13px;
                                    font-weight: bold;
                                '
                            >
                                E-mail
                            </td>

                            <td
                                style='
                                    padding: 12px;
                                    border-bottom: 1px solid #e2e8f0;
                                '
                            >
                                {$emailSeguro}
                            </td>

                        </tr>


                        <tr>

                            <td
                                style='
                                    padding: 12px;
                                    border-bottom: 1px solid #e2e8f0;
                                    color: #64748b;
                                    font-size: 13px;
                                    font-weight: bold;
                                '
                            >
                                Tipo
                            </td>

                            <td
                                style='
                                    padding: 12px;
                                    border-bottom: 1px solid #e2e8f0;
                                '
                            >
                                {$tipoSeguro}
                            </td>

                        </tr>


                        <tr>

                            <td
                                style='
                                    padding: 12px;
                                    border-bottom: 1px solid #e2e8f0;
                                    color: #64748b;
                                    font-size: 13px;
                                    font-weight: bold;
                                '
                            >
                                Assunto
                            </td>

                            <td
                                style='
                                    padding: 12px;
                                    border-bottom: 1px solid #e2e8f0;
                                '
                            >
                                {$assuntoSeguro}
                            </td>

                        </tr>

                    </table>


                    <div
                        style='
                            margin-top: 25px;
                            padding: 20px;
                            background-color: #f8fafc;
                            border-left: 4px solid #38bdf8;
                            border-radius: 8px;
                        '
                    >

                        <div
                            style='
                                margin-bottom: 10px;
                                color: #64748b;
                                font-size: 12px;
                                font-weight: bold;
                                letter-spacing: 0.8px;
                            '
                        >

                            MENSAGEM

                        </div>

                        <div
                            style='
                                color: #334155;
                                font-size: 15px;
                                line-height: 1.8;
                            '
                        >

                            {$mensagemSegura}

                        </div>

                    </div>


                    <p
                        style='
                            margin: 25px 0 0;
                            color: #64748b;
                            font-size: 13px;
                            line-height: 1.6;
                        '
                    >

                        Para responder ao visitante,
                        utilize o botão
                        <strong>Responder</strong>
                        do seu serviço de e-mail.

                    </p>

                </div>

            </div>

        </body>

        </html>
        ";


        /*
        |--------------------------------------------------------------------------
        | VERSÃO TEXTO
        |--------------------------------------------------------------------------
        */

        $textoEmail =

            "Java, Café e Código\n\n" .

            "Nova mensagem de contato\n\n" .

            "Nome: {$nome}\n" .

            "E-mail: {$email}\n" .

            "Tipo: {$tipo}\n" .

            "Assunto: {$assunto}\n\n" .

            "Mensagem:\n{$mensagem}";


        /*
        |--------------------------------------------------------------------------
        | ENVIA
        |--------------------------------------------------------------------------
        */

        return self::enviar(

            $destinatario,

            $tituloEmail,

            $corpoEmail,

            $email,

            $nome,

            [],

            $textoEmail

        );

    }

}