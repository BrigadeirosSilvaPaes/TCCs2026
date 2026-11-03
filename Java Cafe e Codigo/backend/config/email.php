<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES DE E-MAIL
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Configura o servidor SMTP utilizado pelo EmailService.
|
| Prioridade das configurações:
|
| 1. Variáveis de ambiente
| 2. secrets.php
| 3. Valores padrão não sensíveis
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CREDENCIAIS PRIVADAS
|--------------------------------------------------------------------------
*/

$secrets = [];

$secretsFile =
    __DIR__
    . "/secrets.php";


if (
    is_file(
        $secretsFile
    )
) {

    $secretsLoaded =
        require $secretsFile;


    if (
        is_array(
            $secretsLoaded
        )
    ) {

        $secrets =
            $secretsLoaded;

    }

}


$emailSecrets =
    isset(
        $secrets["email"]
    )
    &&
    is_array(
        $secrets["email"]
    )
        ?
        $secrets["email"]
        :
        [];


/*
|--------------------------------------------------------------------------
| HOST SMTP
|--------------------------------------------------------------------------
*/

$emailHostEnv =
    trim(
        (string) getenv(
            "EMAIL_HOST"
        )
    );


$emailHost =
    $emailHostEnv !== ""
        ?
        $emailHostEnv
        :
        trim(
            (string) (
                $emailSecrets["host"]
                ??
                "smtp.gmail.com"
            )
        );


/*
|--------------------------------------------------------------------------
| PORTA SMTP
|--------------------------------------------------------------------------
*/

$emailPortEnv =
    filter_var(
        getenv(
            "EMAIL_PORT"
        ),
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 1,
                "max_range" => 65535
            ]
        ]
    );


if (
    $emailPortEnv !== false
) {

    $emailPort =
        (int) $emailPortEnv;

} else {

    $emailPort =
        isset(
            $emailSecrets["port"]
        )
            ?
            (int) $emailSecrets["port"]
            :
            587;

}


/*
|--------------------------------------------------------------------------
| SEGURANÇA SMTP
|--------------------------------------------------------------------------
|
| Gmail:
|
| Porta 587 -> tls
| Porta 465 -> ssl
|
|--------------------------------------------------------------------------
*/

$emailSecurityEnv =
    strtolower(
        trim(
            (string) getenv(
                "EMAIL_SECURITY"
            )
        )
    );


$emailSecuritySecret =
    strtolower(
        trim(
            (string) (
                $emailSecrets["security"]
                ??
                ""
            )
        )
    );


if (
    in_array(
        $emailSecurityEnv,
        [
            "tls",
            "ssl"
        ],
        true
    )
) {

    $emailSecurity =
        $emailSecurityEnv;

} elseif (
    in_array(
        $emailSecuritySecret,
        [
            "tls",
            "ssl"
        ],
        true
    )
) {

    $emailSecurity =
        $emailSecuritySecret;

} else {

    $emailSecurity =
        "tls";

}


/*
|--------------------------------------------------------------------------
| CHARSET
|--------------------------------------------------------------------------
*/

$emailCharsetEnv =
    trim(
        (string) getenv(
            "EMAIL_CHARSET"
        )
    );


$emailCharset =
    $emailCharsetEnv !== ""
        ?
        $emailCharsetEnv
        :
        trim(
            (string) (
                $emailSecrets["charset"]
                ??
                "UTF-8"
            )
        );


/*
|--------------------------------------------------------------------------
| IDIOMA
|--------------------------------------------------------------------------
*/

$emailLanguageEnv =
    trim(
        (string) getenv(
            "EMAIL_LANGUAGE"
        )
    );


$emailLanguage =
    $emailLanguageEnv !== ""
        ?
        $emailLanguageEnv
        :
        trim(
            (string) (
                $emailSecrets["language"]
                ??
                "pt_br"
            )
        );


/*
|--------------------------------------------------------------------------
| USUÁRIO SMTP
|--------------------------------------------------------------------------
*/

$emailUsernameEnv =
    trim(
        (string) getenv(
            "EMAIL_USERNAME"
        )
    );


$emailUsername =
    $emailUsernameEnv !== ""
        ?
        $emailUsernameEnv
        :
        trim(
            (string) (
                $emailSecrets["username"]
                ??
                "javacafeecodigo@gmail.com"
            )
        );


/*
|--------------------------------------------------------------------------
| SENHA SMTP
|--------------------------------------------------------------------------
|
| Nunca existe senha padrão.
|
| Prioridade:
|
| 1. EMAIL_PASSWORD
| 2. secrets.php
|
|--------------------------------------------------------------------------
*/

$emailPasswordEnv =
    (string) getenv(
        "EMAIL_PASSWORD"
    );


$emailPassword =
    $emailPasswordEnv !== ""
        ?
        $emailPasswordEnv
        :
        (string) (
            $emailSecrets["password"]
            ??
            ""
        );


/*
|--------------------------------------------------------------------------
| REMETENTE
|--------------------------------------------------------------------------
*/

$emailFromEnv =
    trim(
        (string) getenv(
            "EMAIL_FROM"
        )
    );


$emailFrom =
    $emailFromEnv !== ""
        ?
        $emailFromEnv
        :
        trim(
            (string) (
                $emailSecrets["from"]
                ??
                $emailUsername
            )
        );


/*
|--------------------------------------------------------------------------
| NOME DO REMETENTE
|--------------------------------------------------------------------------
*/

$emailFromNameEnv =
    trim(
        (string) getenv(
            "EMAIL_FROM_NAME"
        )
    );


$emailFromName =
    $emailFromNameEnv !== ""
        ?
        $emailFromNameEnv
        :
        trim(
            (string) (
                $emailSecrets["from_name"]
                ??
                "Java, Café e Código"
            )
        );


/*
|--------------------------------------------------------------------------
| E-MAIL DE CONTATO
|--------------------------------------------------------------------------
*/

$emailContactEnv =
    trim(
        (string) getenv(
            "EMAIL_CONTACT"
        )
    );


$emailContact =
    $emailContactEnv !== ""
        ?
        $emailContactEnv
        :
        trim(
            (string) (
                $emailSecrets["contact"]
                ??
                $emailUsername
            )
        );


/*
|--------------------------------------------------------------------------
| VALIDAÇÕES
|--------------------------------------------------------------------------
*/

if (
    $emailHost === ""
) {

    throw new RuntimeException(
        "O servidor SMTP não foi configurado."
    );

}


if (
    $emailPort < 1
    ||
    $emailPort > 65535
) {

    throw new RuntimeException(
        "A porta SMTP configurada é inválida."
    );

}


if (
    !in_array(
        $emailSecurity,
        [
            "tls",
            "ssl"
        ],
        true
    )
) {

    throw new RuntimeException(
        "O protocolo de segurança SMTP é inválido."
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA PRODUÇÃO
|--------------------------------------------------------------------------
|
| Em produção, usuário e senha SMTP são obrigatórios.
|
|--------------------------------------------------------------------------
*/

$isProduction =
    defined(
        "ENVIRONMENT"
    )
    &&
    ENVIRONMENT ===
    "production";


if (
    $isProduction
    &&
    (
        $emailUsername === ""
        ||
        $emailPassword === ""
    )
) {

    throw new RuntimeException(
        "As credenciais SMTP não foram configuradas para o ambiente de produção."
    );

}


/*
|--------------------------------------------------------------------------
| CONSTANTES
|--------------------------------------------------------------------------
*/

if (
    !defined(
        "EMAIL_HOST"
    )
) {

    define(
        "EMAIL_HOST",
        $emailHost
    );

}


if (
    !defined(
        "EMAIL_PORT"
    )
) {

    define(
        "EMAIL_PORT",
        $emailPort
    );

}


if (
    !defined(
        "EMAIL_SECURITY"
    )
) {

    define(
        "EMAIL_SECURITY",
        $emailSecurity
    );

}


if (
    !defined(
        "EMAIL_CHARSET"
    )
) {

    define(
        "EMAIL_CHARSET",
        $emailCharset
    );

}


if (
    !defined(
        "EMAIL_LANGUAGE"
    )
) {

    define(
        "EMAIL_LANGUAGE",
        $emailLanguage
    );

}


if (
    !defined(
        "EMAIL_USERNAME"
    )
) {

    define(
        "EMAIL_USERNAME",
        $emailUsername
    );

}


if (
    !defined(
        "EMAIL_PASSWORD"
    )
) {

    define(
        "EMAIL_PASSWORD",
        $emailPassword
    );

}


if (
    !defined(
        "EMAIL_FROM"
    )
) {

    define(
        "EMAIL_FROM",
        $emailFrom
    );

}


if (
    !defined(
        "EMAIL_FROM_NAME"
    )
) {

    define(
        "EMAIL_FROM_NAME",
        $emailFromName
    );

}


if (
    !defined(
        "EMAIL_CONTACT"
    )
) {

    define(
        "EMAIL_CONTACT",
        $emailContact
    );

}


/*
|--------------------------------------------------------------------------
| LIMPEZA
|--------------------------------------------------------------------------
*/

unset(
    $secrets,
    $secretsFile,
    $secretsLoaded,
    $emailSecrets,
    $emailHostEnv,
    $emailHost,
    $emailPortEnv,
    $emailPort,
    $emailSecurityEnv,
    $emailSecuritySecret,
    $emailSecurity,
    $emailCharsetEnv,
    $emailCharset,
    $emailLanguageEnv,
    $emailLanguage,
    $emailUsernameEnv,
    $emailUsername,
    $emailPasswordEnv,
    $emailPassword,
    $emailFromEnv,
    $emailFrom,
    $emailFromNameEnv,
    $emailFromName,
    $emailContactEnv,
    $emailContact,
    $isProduction
);