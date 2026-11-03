<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../../../backend/config/app.php";
require_once __DIR__ . "/../../../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../../../backend/config/helpers.php";

require_once __DIR__ . "/../../../../../backend/Controller/PerfilController.php";
require_once __DIR__ . "/../../../../../backend/Controller/SalaController.php";


/*
|--------------------------------------------------------------------------
| SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| ROTAS
|--------------------------------------------------------------------------
*/

$linkLogin =
    rota(
        "login"
    );


$linkEscolherPerfil =
    rota(
        "perfil.escolher"
    );


$linkInicio =
    rota(
        "inicio"
    );


$linkQuestionario =
    rota(
        "estudante.questionario"
    );


$linkPainel =
    rota(
        "estudante.painel"
    );


$linkSalas =
    rota(
        "estudante.salas"
    );


/*
|--------------------------------------------------------------------------
| FUNÇÕES AUXILIARES
|--------------------------------------------------------------------------
*/

function salaEstudanteEscapar(
    mixed $valor
): string {

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );

}


/*
|--------------------------------------------------------------------------
| REDIRECIONAR
|--------------------------------------------------------------------------
*/

function salaEstudanteRedirecionar(
    string $url,
    int $codigo = 302
): never {

    header(
        "Location: " . $url,
        true,
        $codigo
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| VERSÃO DE ASSET
|--------------------------------------------------------------------------
*/

function salaEstudanteVersaoAsset(
    string $arquivo
): int {

    if (
        !is_file(
            $arquivo
        )
    ) {

        return 1;

    }


    $versao =
        @filemtime(
            $arquivo
        );


    return $versao !== false
        ?
        (int) $versao
        :
        1;

}


/*
|--------------------------------------------------------------------------
| PRIMEIRO NOME
|--------------------------------------------------------------------------
*/

function salaEstudantePrimeiroNome(
    string $nome
): string {

    $nome =
        trim(
            $nome
        );


    if (
        $nome === ""
    ) {

        return "Estudante";

    }


    $partes =
        preg_split(
            "/\s+/",
            $nome,
            -1,
            PREG_SPLIT_NO_EMPTY
        );


    if (
        !is_array(
            $partes
        )
        ||
        empty(
            $partes
        )
    ) {

        return "Estudante";

    }


    return (string) $partes[0];

}


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

function salaEstudanteCsrfToken(): string {

    if (
        !isset(
            $_SESSION["csrf"]
        )
        ||
        !is_array(
            $_SESSION["csrf"]
        )
    ) {

        $_SESSION["csrf"] =
            [];

    }


    $token =
        $_SESSION["csrf"]["estudante_salas"]
        ??
        "";


    if (
        is_string(
            $token
        )
        &&
        preg_match(
            "/^[a-f0-9]{64}$/",
            $token
        )
        === 1
    ) {

        return $token;

    }


    $token =
        bin2hex(
            random_bytes(
                32
            )
        );


    $_SESSION["csrf"]["estudante_salas"] =
        $token;


    return $token;

}


/*
|--------------------------------------------------------------------------
| VALIDAR CSRF
|--------------------------------------------------------------------------
*/

function salaEstudanteCsrfValido(
    mixed $token
): bool {

    $tokenSessao =
        $_SESSION["csrf"]["estudante_salas"]
        ??
        null;


    if (
        !is_string(
            $tokenSessao
        )
        ||
        !is_string(
            $token
        )
        ||
        $token === ""
    ) {

        return false;

    }


    return hash_equals(
        $tokenSessao,
        $token
    );

}


/*
|--------------------------------------------------------------------------
| RENOVAR CSRF
|--------------------------------------------------------------------------
*/

function salaEstudanteCsrfRenovar(): void {

    if (
        isset(
            $_SESSION["csrf"]
        )
        &&
        is_array(
            $_SESSION["csrf"]
        )
    ) {

        unset(
            $_SESSION["csrf"]["estudante_salas"]
        );

    }

}


/*
|--------------------------------------------------------------------------
| ORIGEM DO POST
|--------------------------------------------------------------------------
|
| Camada adicional ao CSRF.
|
|--------------------------------------------------------------------------
*/

function salaEstudanteOrigemPostValida(): bool {

    $origem =
        strtolower(
            trim(
                (string) (
                    $_SERVER["HTTP_SEC_FETCH_SITE"]
                    ??
                    ""
                )
            )
        );


    if (
        $origem === ""
    ) {

        return true;

    }


    return in_array(
        $origem,
        [
            "same-origin",
            "same-site",
            "none"
        ],
        true
    );

}


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["usuario"]
    )
    ||
    !is_array(
        $_SESSION["usuario"]
    )
) {

    salaEstudanteRedirecionar(
        $linkLogin
    );

}


/*
|--------------------------------------------------------------------------
| DADOS DO USUÁRIO
|--------------------------------------------------------------------------
*/

$usuario =
    $_SESSION["usuario"];


$usuarioId =
    (int) (
        $usuario["id"]
        ??
        0
    );


$nomeUsuario =
    trim(
        (string) (
            $usuario["nome"]
            ??
            "Estudante"
        )
    );


$primeiroNome =
    salaEstudantePrimeiroNome(
        $nomeUsuario
    );


/*
|--------------------------------------------------------------------------
| VALIDA USUÁRIO
|--------------------------------------------------------------------------
*/

if (
    $usuarioId <= 0
) {

    unset(
        $_SESSION["usuario"]
    );


    salaEstudanteRedirecionar(
        $linkLogin
    );

}


/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

try {

    $perfilController =
        new PerfilController();


    $salaController =
        new SalaController();

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalasEstudante] Erro ao iniciar controllers: "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar a Sala de Aula.";


    salaEstudanteRedirecionar(
        $linkPainel
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

try {

    $resultadoPerfil =
        $perfilController->buscarPerfil(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalasEstudante] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    salaEstudanteRedirecionar(
        $linkEscolherPerfil
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA RESULTADO DO PERFIL
|--------------------------------------------------------------------------
*/

if (
    empty(
        $resultadoPerfil["status"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]["usuario"]
    )
    ||
    !is_array(
        $resultadoPerfil["perfil"]["usuario"]
    )
) {

    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    salaEstudanteRedirecionar(
        $linkEscolherPerfil
    );

}


$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


$perfilEstudante =
    isset(
        $resultadoPerfil["perfil"]["estudante"]
    )
    &&
    is_array(
        $resultadoPerfil["perfil"]["estudante"]
    )
        ?
        $resultadoPerfil["perfil"]["estudante"]
        :
        null;


/*
|--------------------------------------------------------------------------
| GARANTE QUE É ESTUDANTE
|--------------------------------------------------------------------------
*/

$tipoUsuario =
    strtolower(
        trim(
            (string) (
                $perfilUsuario["tipo_usuario"]
                ??
                ""
            )
        )
    );


if (
    $tipoUsuario !==
    "estudante"
) {

    $_SESSION["erro"] =
        "Esta área é exclusiva para estudantes.";


    salaEstudanteRedirecionar(
        $linkInicio
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL DE ESTUDANTE
|--------------------------------------------------------------------------
*/

if (
    $perfilEstudante === null
) {

    $_SESSION["erro"] =
        "Complete seu perfil de estudante.";


    salaEstudanteRedirecionar(
        $linkQuestionario
    );

}


/*
|--------------------------------------------------------------------------
| TOKEN CSRF
|--------------------------------------------------------------------------
*/

$csrfToken =
    salaEstudanteCsrfToken();


/*
|--------------------------------------------------------------------------
| POST — ENTRAR EM SALA
|--------------------------------------------------------------------------
*/

if (
    strtoupper(
        (string) (
            $_SERVER["REQUEST_METHOD"]
            ??
            "GET"
        )
    )
    ===
    "POST"
) {

    /*
    |--------------------------------------------------------------------------
    | ORIGEM
    |--------------------------------------------------------------------------
    */

    if (
        !salaEstudanteOrigemPostValida()
    ) {

        $_SESSION["erro"] =
            "A solicitação não pôde ser validada.";


        salaEstudanteCsrfRenovar();


        salaEstudanteRedirecionar(
            $linkSalas,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    if (
        !salaEstudanteCsrfValido(
            $_POST["csrf_token"]
            ??
            null
        )
    ) {

        $_SESSION["erro"] =
            "Sua sessão de segurança expirou. Atualize a página e tente novamente.";


        salaEstudanteCsrfRenovar();


        salaEstudanteRedirecionar(
            $linkSalas,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AÇÃO
    |--------------------------------------------------------------------------
    */

    $acao =
        strtolower(
            trim(
                (string) (
                    $_POST["acao"]
                    ??
                    ""
                )
            )
        );


    if (
        $acao !==
        "entrar_sala"
    ) {

        $_SESSION["erro"] =
            "Ação inválida.";


        salaEstudanteCsrfRenovar();


        salaEstudanteRedirecionar(
            $linkSalas,
            303
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CÓDIGO
    |--------------------------------------------------------------------------
    */

    $codigo =
        strtoupper(
            trim(
                (string) (
                    $_POST["codigo"]
                    ??
                    ""
                )
            )
        );


    /*
    |--------------------------------------------------------------------------
    | VALIDAÇÃO
    |--------------------------------------------------------------------------
    */

    if (
        $codigo === ""
    ) {

        $_SESSION["erro"] =
            "Informe o código da sala.";

    }
    elseif (
        strlen(
            $codigo
        )
        >
        50
    ) {

        $_SESSION["erro"] =
            "O código informado é inválido.";

    }
    elseif (
        preg_match(
            "/[\x00-\x1F\x7F]/",
            $codigo
        )
        === 1
    ) {

        $_SESSION["erro"] =
            "O código informado é inválido.";

    }
    else {

        /*
        |--------------------------------------------------------------------------
        | ENTRAR
        |--------------------------------------------------------------------------
        */

        try {

            $resultadoEntrada =
                $salaController->entrarPorCodigo(

                    $codigo,

                    $usuarioId

                );


            if (
                !empty(
                    $resultadoEntrada["status"]
                )
            ) {

                $_SESSION["sucesso"] =
                    $resultadoEntrada["mensagem"]
                    ??
                    "Você entrou na sala.";

            }
            else {

                $_SESSION["erro"] =
                    $resultadoEntrada["mensagem"]
                    ??
                    "Não foi possível entrar na sala.";

            }

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalasEstudante] Erro ao entrar na sala. Usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );


            $_SESSION["erro"] =
                "Não foi possível entrar na sala.";

        }

    }


    /*
    |--------------------------------------------------------------------------
    | POST / REDIRECT / GET
    |--------------------------------------------------------------------------
    */

    salaEstudanteCsrfRenovar();


    salaEstudanteRedirecionar(
        $linkSalas,
        303
    );

}


/*
|--------------------------------------------------------------------------
| LISTAR SALAS
|--------------------------------------------------------------------------
*/

try {

    $resultadoSalas =
        $salaController->listarSalasAluno(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalasEstudante] Erro ao listar salas do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $resultadoSalas = [

        "status" =>
            false,

        "salas" =>
            []

    ];


    $_SESSION["erro"] =
        "Não foi possível carregar suas salas.";

}


/*
|--------------------------------------------------------------------------
| NORMALIZAR SALAS
|--------------------------------------------------------------------------
*/

$salas =
    !empty(
        $resultadoSalas["status"]
    )
    &&
    isset(
        $resultadoSalas["salas"]
    )
    &&
    is_array(
        $resultadoSalas["salas"]
    )
        ?
        $resultadoSalas["salas"]
        :
        [];


/*
|--------------------------------------------------------------------------
| REMOVE ENTRADAS INVÁLIDAS
|--------------------------------------------------------------------------
*/

$salas =
    array_values(
        array_filter(

            $salas,

            static function (
                mixed $sala
            ): bool {

                return (
                    is_array(
                        $sala
                    )
                    &&
                    (int) (
                        $sala["id"]
                        ??
                        0
                    )
                    >
                    0
                );

            }

        )
    );


$totalSalas =
    count(
        $salas
    );


/*
|--------------------------------------------------------------------------
| ASSETS
|--------------------------------------------------------------------------
*/

$arquivoStyle =
    __DIR__ .
    "/../../../../assets/css/style.css";


$arquivoMensagemCss =
    __DIR__ .
    "/../../../../assets/css/mensagem.css";


$arquivoEstudanteCss =
    __DIR__ .
    "/../../../../assets/css/estudante.css";


$arquivoThemeJs =
    __DIR__ .
    "/../../../../assets/js/theme.js";


$versaoStyle =
    salaEstudanteVersaoAsset(
        $arquivoStyle
    );


$versaoMensagemCss =
    salaEstudanteVersaoAsset(
        $arquivoMensagemCss
    );


$versaoEstudanteCss =
    salaEstudanteVersaoAsset(
        $arquivoEstudanteCss
    );


$versaoThemeJs =
    salaEstudanteVersaoAsset(
        $arquivoThemeJs
    );

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="description"
    content="Salas de aula do estudante na plataforma Java, Café e Código."
>

<title>
    Sala de Aula |
    <?= salaEstudanteEscapar(
        SITE_NAME
    ) ?>
</title>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= $versaoStyle ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css?v=<?= $versaoMensagemCss ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/estudante.css?v=<?= $versaoEstudanteCss ?>"
>

</head>


<body class="pagina-estudante pagina-salas-estudante">


<?php

require_once __DIR__ .
    "/../../../../componentes/header.php";


require_once __DIR__ .
    "/../../../../componentes/mensagem.php";

?>


<main class="painel-estudante">


<!-- =====================================================
APRESENTAÇÃO
====================================================== -->

<section class="painel-apresentacao">


    <div class="painel-apresentacao-conteudo">


        <span class="painel-etiqueta">

            SALA DE AULA

        </span>


        <h1>

            Suas salas,

            <strong>

                <?= salaEstudanteEscapar(
                    $primeiroNome
                ) ?>

            </strong>

        </h1>


        <p>

            Utilize o código fornecido pelo professor para
            participar de uma sala. Depois de entrar, ela
            ficará vinculada à sua conta de estudante.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= salaEstudanteEscapar(
                    $linkPainel
                ) ?>"
                class="botao-secundario"
            >

                ← Voltar ao painel

            </a>


        </div>


    </div>


    <!-- =====================================================
    RESUMO
    ====================================================== -->

    <div class="painel-apresentacao-codigo">


        <div class="codigo-topo">


            <div class="codigo-controles">

                <span></span>
                <span></span>
                <span></span>

            </div>


            <strong>

                SalaDeAula.java

            </strong>


            <small>

                JAVA

            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">SalaDeAula</span> {

    <span class="codigo-roxo">public static void</span> <span class="codigo-amarelo">main</span>(
        String[] args
    ) {

        Estudante aluno =
            <span class="codigo-roxo">new</span> Estudante();

        aluno.<span class="codigo-amarelo">participar</span>();

    }

}</code></pre>


        <div class="codigo-status">


            <span>

                ● <?= $totalSalas ?>

                sala<?= $totalSalas === 1
                    ? ""
                    : "s"
                ?>

            </span>


            <span>

                Estudante

            </span>


        </div>


    </div>


</section>


<!-- =====================================================
ENTRAR EM UMA SALA
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">

            ENTRAR EM UMA SALA

        </span>


        <h2>

            Recebeu um código do professor?

        </h2>


        <p>

            Digite o código exatamente como foi informado.
            A plataforma localizará a sala e vinculará sua
            conta de estudante a ela.

        </p>


    </div>


    <form
        method="POST"
        action="<?= salaEstudanteEscapar(
            $linkSalas
        ) ?>"
        class="sala-entrada-formulario"
    >


        <!-- CSRF -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= salaEstudanteEscapar(
                $csrfToken
            ) ?>"
        >


        <!-- AÇÃO -->

        <input
            type="hidden"
            name="acao"
            value="entrar_sala"
        >


        <label for="codigoSala">

            Código da sala

        </label>


        <div class="sala-codigo-linha">


            <input
                type="text"
                id="codigoSala"
                name="codigo"
                placeholder="Ex.: JCC-ABC123"
                maxlength="50"
                autocomplete="off"
                autocapitalize="characters"
                spellcheck="false"
                required
            >


            <button
                type="submit"
                class="botao-principal"
            >

                Entrar na sala

                <span aria-hidden="true">

                    →

                </span>

            </button>


        </div>


        <small>

            O código é fornecido pelo professor responsável
            pela sala.

        </small>


    </form>


</section>


<!-- =====================================================
MINHAS SALAS
====================================================== -->

<section class="painel-acessos">


    <div class="painel-secao-cabecalho">


        <div>


            <span class="painel-etiqueta">

                MINHAS SALAS

            </span>


            <h2>

                Salas em que você participa

            </h2>


            <p>


                <?php if (
                    $totalSalas === 0
                ): ?>


                    Você ainda não participa de nenhuma sala.


                <?php else: ?>


                    Você participa de

                    <?= $totalSalas ?>

                    sala<?= $totalSalas === 1
                        ? "."
                        : "s."
                    ?>


                <?php endif; ?>


            </p>


        </div>


    </div>


    <?php if (
        $totalSalas > 0
    ): ?>


        <div class="painel-acessos-grid">


            <?php foreach (
                $salas as $sala
            ): ?>


                <?php

                /*
                |--------------------------------------------------------------------------
                | DADOS
                |--------------------------------------------------------------------------
                */

                $salaId =
                    (int) (
                        $sala["id"]
                        ??
                        0
                    );


                $nomeSala =
                    trim(
                        (string) (
                            $sala["nome"]
                            ??
                            "Sala de Aula"
                        )
                    );


                if (
                    $nomeSala === ""
                ) {

                    $nomeSala =
                        "Sala de Aula";

                }


                $codigoSala =
                    trim(
                        (string) (
                            $sala["codigo"]
                            ??
                            ""
                        )
                    );


                $linguagemSala =
                    trim(
                        (string) (
                            $sala["linguagem"]
                            ??
                            ""
                        )
                    );


                $descricaoSala =
                    trim(
                        (string) (
                            $sala["descricao"]
                            ??
                            ""
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | ROTA LIMPA
                |--------------------------------------------------------------------------
                */

                $linkSalaIndividual =
                    rota(
                        "estudante.sala",
                        [
                            "id" =>
                                $salaId
                        ]
                    );

                ?>


                <a
                    href="<?= salaEstudanteEscapar(
                        $linkSalaIndividual
                    ) ?>"
                    class="acesso-card"
                >


                    <div class="acesso-card-topo">


                        <span
                            class="acesso-icone"
                            aria-hidden="true"
                        >

                            SL

                        </span>


                        <span class="acesso-status acesso-disponivel">

                            Participando

                        </span>


                    </div>


                    <div>


                        <small>

                            <?= salaEstudanteEscapar(
                                $linguagemSala !== ""
                                    ?
                                    strtoupper(
                                        $linguagemSala
                                    )
                                    :
                                    "SALA DE AULA"
                            ) ?>

                        </small>


                        <h3>

                            <?= salaEstudanteEscapar(
                                $nomeSala
                            ) ?>

                        </h3>


                        <p>

                            <?= salaEstudanteEscapar(
                                $descricaoSala !== ""
                                    ?
                                    $descricaoSala
                                    :
                                    "Sala vinculada à sua conta de estudante."
                            ) ?>

                        </p>


                        <?php if (
                            $codigoSala !== ""
                        ): ?>


                            <p>

                                Código:

                                <strong>

                                    <?= salaEstudanteEscapar(
                                        $codigoSala
                                    ) ?>

                                </strong>

                            </p>


                        <?php endif; ?>


                    </div>


                    <strong
                        class="acesso-seta"
                        aria-hidden="true"
                    >

                        →

                    </strong>


                </a>


            <?php endforeach; ?>


        </div>


    <?php else: ?>


        <!-- =====================================================
        ESTADO VAZIO
        ====================================================== -->

        <div class="painel-orientacao">


            <div class="painel-orientacao-texto">


                <span class="painel-etiqueta">

                    NENHUMA SALA

                </span>


                <h2>

                    Você ainda não entrou em uma sala

                </h2>


                <p>

                    Quando um professor criar uma sala e fornecer
                    o código de acesso, utilize o formulário acima
                    para participar.

                </p>


            </div>


            <div class="painel-orientacao-etapas">


                <article>


                    <span>

                        01

                    </span>


                    <div>


                        <strong>

                            Receba o código

                        </strong>


                        <p>

                            O professor cria a sala e compartilha o código.

                        </p>


                    </div>


                </article>


                <article>


                    <span>

                        02

                    </span>


                    <div>


                        <strong>

                            Digite o código

                        </strong>


                        <p>

                            Informe o código no campo disponível nesta página.

                        </p>


                    </div>


                </article>


                <article>


                    <span>

                        03

                    </span>


                    <div>


                        <strong>

                            Participe da turma

                        </strong>


                        <p>

                            A sala ficará vinculada à sua conta.

                        </p>


                    </div>


                </article>


            </div>


        </div>


    <?php endif; ?>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../../../componentes/footer.php";

?>


<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


</body>

</html>