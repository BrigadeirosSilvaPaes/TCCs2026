<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private PHPMailer $mail;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $config =
            require __DIR__
                . "/../../config/mail.php";


        $this->mail =
            new PHPMailer(true);


        $this->mail->isSMTP();


        $this->mail->Host =
            $config["host"];


        $this->mail->SMTPAuth =
            true;


        $this->mail->Username =
            $config["username"];


        $this->mail->Password =
            $config["password"];


        $this->mail->SMTPSecure =
            $config["encryption"];


        $this->mail->Port =
            $config["port"];


        $this->mail->CharSet =
            "UTF-8";


        $this->mail->isHTML(true);


        $this->mail->setFrom(
            $config["from_email"],
            $config["from_name"]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | E-MAIL DE RECUPERAÇÃO
    |--------------------------------------------------------------------------
    |
    | Envia um código numérico de seis dígitos.
    |
    | Não existe mais link de redefinição enviado ao usuário.
    |
    */

    public function enviarRecuperacao(
        $email,
        $nome,
        $codigo
    ) {
        try {

            /*
            |--------------------------------------------------------------------------
            | VALIDAÇÕES
            |--------------------------------------------------------------------------
            */

            if (
                !filter_var(
                    $email,
                    FILTER_VALIDATE_EMAIL
                )
            ) {
                return false;
            }


            if (
                !preg_match(
                    "/^[0-9]{6}$/",
                    (string) $codigo
                )
            ) {
                return false;
            }


            /*
            |--------------------------------------------------------------------------
            | CONTEÚDO SEGURO
            |--------------------------------------------------------------------------
            */

            $nomeSeguro =
                htmlspecialchars(
                    (string) $nome,
                    ENT_QUOTES,
                    "UTF-8"
                );


            $codigoSeguro =
                htmlspecialchars(
                    (string) $codigo,
                    ENT_QUOTES,
                    "UTF-8"
                );


            /*
            |--------------------------------------------------------------------------
            | DESTINATÁRIO
            |--------------------------------------------------------------------------
            */

            $this->mail
                ->clearAddresses();


            $this->mail
                ->addAddress(
                    $email,
                    $nome
                );


            /*
            |--------------------------------------------------------------------------
            | ASSUNTO
            |--------------------------------------------------------------------------
            */

            $this->mail->Subject =
                "Código de recuperação - Tamborim Para Todos";


            /*
            |--------------------------------------------------------------------------
            | CORPO HTML
            |--------------------------------------------------------------------------
            */

            $this->mail->Body = "
            <!DOCTYPE html>

            <html lang='pt-BR'>

            <head>

                <meta charset='UTF-8'>

                <meta
                    name='viewport'
                    content='width=device-width, initial-scale=1.0'
                >

            </head>

            <body
                style='
                    margin: 0;
                    padding: 0;
                    background-color: #f8fafc;
                    font-family: Arial, Helvetica, sans-serif;
                    color: #1e293b;
                '
            >

                <table
                    width='100%'
                    cellpadding='0'
                    cellspacing='0'
                    border='0'
                    style='
                        padding: 32px 16px;
                        background-color: #f8fafc;
                    '
                >

                    <tr>

                        <td align='center'>


                            <table
                                width='100%'
                                cellpadding='0'
                                cellspacing='0'
                                border='0'
                                style='
                                    max-width: 520px;
                                    overflow: hidden;
                                    background-color: #ffffff;
                                    border: 1px solid #e2e8f0;
                                    border-radius: 16px;
                                '
                            >


                                <tr>

                                    <td
                                        style='
                                            padding: 30px;
                                            text-align: center;
                                            background-color: #f59e0b;
                                        '
                                    >

                                        <div
                                            style='
                                                margin-bottom: 5px;
                                                font-size: 22px;
                                                font-weight: bold;
                                                color: #451a03;
                                            '
                                        >
                                            Tamborim Para Todos
                                        </div>


                                        <div
                                            style='
                                                font-size: 12px;
                                                font-weight: bold;
                                                letter-spacing: 1px;
                                                color: #78350f;
                                            '
                                        >
                                            RECUPERAÇÃO DE CONTA
                                        </div>

                                    </td>

                                </tr>


                                <tr>

                                    <td
                                        style='
                                            padding: 34px 30px;
                                        '
                                    >

                                        <h1
                                            style='
                                                margin: 0 0 22px;
                                                text-align: center;
                                                font-size: 25px;
                                                color: #0f172a;
                                            '
                                        >
                                            Recuperação de senha
                                        </h1>


                                        <p
                                            style='
                                                margin: 0 0 16px;
                                                font-size: 16px;
                                                line-height: 1.7;
                                            '
                                        >
                                            Olá,
                                            <strong>
                                                {$nomeSeguro}
                                            </strong>.
                                        </p>


                                        <p
                                            style='
                                                margin: 0 0 24px;
                                                font-size: 16px;
                                                line-height: 1.7;
                                            '
                                        >
                                            Recebemos uma solicitação
                                            para redefinir a senha da sua conta.
                                            Utilize o código abaixo para continuar.
                                        </p>


                                        <div
                                            style='
                                                margin: 30px 0;
                                                padding: 25px 18px;
                                                text-align: center;
                                                background-color: #fff7ed;
                                                border: 2px dashed #f59e0b;
                                                border-radius: 14px;
                                            '
                                        >

                                            <div
                                                style='
                                                    margin-bottom: 12px;
                                                    font-size: 12px;
                                                    font-weight: bold;
                                                    letter-spacing: 1px;
                                                    color: #92400e;
                                                '
                                            >
                                                SEU CÓDIGO
                                            </div>


                                            <div
                                                style='
                                                    font-size: 36px;
                                                    font-weight: bold;
                                                    letter-spacing: 9px;
                                                    color: #111827;
                                                '
                                            >
                                                {$codigoSeguro}
                                            </div>

                                        </div>


                                        <div
                                            style='
                                                margin-top: 22px;
                                                padding: 16px 18px;
                                                background-color: #f1f5f9;
                                                border-radius: 10px;
                                                font-size: 14px;
                                                line-height: 1.7;
                                                color: #475569;
                                            '
                                        >

                                            Este código é válido por

                                            <strong>
                                                10 minutos
                                            </strong>

                                            e pode ser utilizado apenas uma vez.

                                        </div>


                                        <p
                                            style='
                                                margin: 26px 0 0;
                                                font-size: 13px;
                                                line-height: 1.7;
                                                color: #64748b;
                                            '
                                        >
                                            Se você não solicitou esta alteração,
                                            pode ignorar esta mensagem.
                                        </p>


                                        <p
                                            style='
                                                margin: 10px 0 0;
                                                font-size: 13px;
                                                line-height: 1.7;
                                                color: #64748b;
                                            '
                                        >
                                            Por segurança, nunca compartilhe
                                            este código com outras pessoas.
                                        </p>

                                    </td>

                                </tr>


                                <tr>

                                    <td
                                        style='
                                            padding: 20px 24px;
                                            text-align: center;
                                            background-color: #f8fafc;
                                            font-size: 12px;
                                            line-height: 1.6;
                                            color: #94a3b8;
                                        '
                                    >

                                        Mensagem automática do
                                        Tamborim Para Todos.

                                        <br>

                                        Não responda este e-mail.

                                    </td>

                                </tr>


                            </table>


                        </td>

                    </tr>

                </table>

            </body>

            </html>
            ";


            /*
            |--------------------------------------------------------------------------
            | TEXTO PURO
            |--------------------------------------------------------------------------
            */

            $this->mail->AltBody =
                "Tamborim Para Todos\n\n"
                . "Recuperação de senha\n\n"
                . "Olá {$nome},\n\n"
                . "Recebemos uma solicitação para redefinir sua senha.\n\n"
                . "Seu código é: {$codigo}\n\n"
                . "Este código é válido por 10 minutos e pode ser usado apenas uma vez.\n\n"
                . "Se você não solicitou esta alteração, ignore esta mensagem.";


            /*
            |--------------------------------------------------------------------------
            | ENVIO
            |--------------------------------------------------------------------------
            */

            $this->mail->send();


            return true;

        }
        catch (Exception $e) {

            $mensagemErro =
                date("Y-m-d H:i:s")
                . " [EmailService] "
                . $e->getMessage()
                . PHP_EOL;

            file_put_contents(
                __DIR__
                . "/../../email_debug.log",
                $mensagemErro,
                FILE_APPEND
            );

            return false;

        }
    }
}