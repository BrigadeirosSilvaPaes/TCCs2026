<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../../backend/config/app.php";
require_once __DIR__ . "/../../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../../backend/config/helpers.php";

require_once __DIR__ . "/../../../../backend/Controller/PerfilController.php";


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


$linkPainel =
    rota(
        "painel"
    );


$linkPerfilEstudante =
    rota(
        "estudante.perfil"
    );


$linkSalvarEstudante =
    rota(
        "acao.estudante.salvar"
    );


/*
|--------------------------------------------------------------------------
| VERIFICA LOGIN
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["usuario"]
    )
) {

    header(
        "Location: " .
        $linkLogin
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

$usuarioId =
    (int) (
        $_SESSION["usuario"]["id"]
        ?? 0
    );


$nomeUsuario =
    trim(
        (string) (
            $_SESSION["usuario"]["nome"]
            ?? "Estudante"
        )
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


    header(
        "Location: " .
        $linkLogin
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| BUSCA PERFIL
|--------------------------------------------------------------------------
*/

$perfilController =
    new PerfilController();


try {

    $resultadoPerfil =
        $perfilController->buscarPerfil(
            $usuarioId
        );

}
catch (Throwable $erro) {

    error_log(
        "[QuestionarioEstudante] Erro ao buscar perfil do usuário " .
        $usuarioId .
        ": " .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    header(
        "Location: " .
        $linkPainel
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| VERIFICA PERFIL GERAL
|--------------------------------------------------------------------------
*/

if (
    empty(
        $resultadoPerfil["status"]
    )
    ||
    !isset(
        $resultadoPerfil["perfil"]["usuario"]
    )
) {

    $_SESSION["erro"] =
        "Complete primeiro as informações gerais do seu perfil.";


    header(
        "Location: " .
        $linkEscolherPerfil
    );

    exit;

}


$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


$perfilEstudante =
    $resultadoPerfil["perfil"]["estudante"]
    ?? null;


/*
|--------------------------------------------------------------------------
| GARANTE PERFIL DE ESTUDANTE
|--------------------------------------------------------------------------
*/

if (
    (
        $perfilUsuario["tipo_usuario"]
        ?? ""
    )
    !== "estudante"
) {

    $_SESSION["erro"] =
        "Este questionário é exclusivo para estudantes.";


    header(
        "Location: " .
        $linkPainel
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| MODO
|--------------------------------------------------------------------------
|
| Se já existe registro específico de estudante:
|
| editar
|
| Caso contrário:
|
| cadastro
|
*/

$modoEdicao =
    !empty(
        $perfilEstudante
    );


$modo =
    $modoEdicao
        ? "editar"
        : "cadastro";


/*
|--------------------------------------------------------------------------
| INSTITUIÇÃO ATUAL
|--------------------------------------------------------------------------
*/

$instituicaoAtual =
    trim(
        (string) (
            $perfilEstudante["instituicao"]
            ?? ""
        )
    );


/*
|--------------------------------------------------------------------------
| LOCALIZAÇÃO
|--------------------------------------------------------------------------
*/

$estado =
    strtoupper(
        trim(
            (string) (
                $perfilUsuario["estado"]
                ?? ""
            )
        )
    );


$cidade =
    trim(
        (string) (
            $perfilUsuario["cidade"]
            ?? ""
        )
    );


/*
|--------------------------------------------------------------------------
| VERIFICA RIO GRANDE - RS
|--------------------------------------------------------------------------
|
| A opção específica da E.E.E.M. Brigadeiro José da Silva Paes
| fica disponível apenas quando o perfil estiver localizado
| em Rio Grande - RS.
|
*/

$ehRioGrande =
    (
        $estado === "RS"
        &&
        mb_strtolower(
            $cidade,
            "UTF-8"
        ) === "rio grande"
    );


/*
|--------------------------------------------------------------------------
| PRIMEIRO NOME
|--------------------------------------------------------------------------
*/

$partesNome =
    array_values(
        array_filter(
            explode(
                " ",
                $nomeUsuario
            )
        )
    );


$primeiroNome =
    $partesNome[0]
    ?? "Estudante";


/*
|--------------------------------------------------------------------------
| ESCAPA HTML
|--------------------------------------------------------------------------
*/

function questionarioEstudanteEscapar(
    mixed $valor
): string
{

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );

}


/*
|--------------------------------------------------------------------------
| CSS
|--------------------------------------------------------------------------
*/

$caminhoCssQuestionario =
    __DIR__ .
    "/../../../assets/css/questionario_estudante.css";

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
    content="Complete as informações do seu perfil de estudante na plataforma Java, Café e Código."
>

<title>
    Questionário do Estudante | <?= SITE_NAME ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= filemtime(
        __DIR__ .
        "/../../../assets/css/style.css"
    ) ?>"
>


<!-- =====================================================
PERFIL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/perfil.css?v=<?= filemtime(
        __DIR__ .
        "/../../../assets/css/perfil.css"
    ) ?>"
>


<!-- =====================================================
MENSAGENS
====================================================== -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css?v=<?= filemtime(
        __DIR__ .
        "/../../../assets/css/mensagem.css"
    ) ?>"
>


<!-- =====================================================
QUESTIONÁRIO DO ESTUDANTE
====================================================== -->

<?php if (
    is_file(
        $caminhoCssQuestionario
    )
): ?>

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/questionario_estudante.css?v=<?= filemtime(
        $caminhoCssQuestionario
    ) ?>"
>

<?php endif; ?>


</head>


<body class="pagina-perfil pagina-questionario-estudante">


<?php

require_once __DIR__ .
    "/../../../componentes/header.php";


require_once __DIR__ .
    "/../../../componentes/mensagem.php";

?>


<main class="questionario-estudante">


<section class="questionario-card">


    <!-- =====================================================
    CABEÇALHO
    ====================================================== -->

    <span class="questionario-etiqueta">

        <?= $modoEdicao
            ? "EDITAR INFORMAÇÃO"
            : "PERFIL DO ESTUDANTE"
        ?>

    </span>


    <h1>

        <?php if (
            $modoEdicao
        ): ?>

            Atualize sua instituição

        <?php else: ?>

            Só mais uma pergunta,

            <?= questionarioEstudanteEscapar(
                $primeiroNome
            ) ?>!

        <?php endif; ?>

    </h1>


    <p>

        Para finalizar seu perfil de estudante,
        selecione a opção que melhor representa
        sua instituição de ensino.

    </p>


    <!-- =====================================================
    FORMULÁRIO
    ======================================================

    Antes:

    /backend/perfil/salvar_estudante.php

    Agora:

    POST pela rota acao.estudante.salvar
    ====================================================== -->

    <form
        action="<?= questionarioEstudanteEscapar(
            $linkSalvarEstudante
        ) ?>"
        method="POST"
    >


        <!-- =====================================================
        MODO
        ====================================================== -->

        <input
            type="hidden"
            name="modo"
            value="<?= questionarioEstudanteEscapar(
                $modo
            ) ?>"
        >


        <!-- =====================================================
        INSTITUIÇÕES
        ====================================================== -->

        <div class="questionario-opcoes">


            <!-- =================================================
            BRIGADEIRO
            ================================================== -->

            <div class="questionario-opcao">


                <input
                    type="radio"
                    name="instituicao"
                    id="instituicao-brigadeiro"
                    value="brigadeiro"
                    <?= $instituicaoAtual === "brigadeiro"
                        ? "checked"
                        : ""
                    ?>
                    <?= !$ehRioGrande
                        ? "disabled"
                        : ""
                    ?>
                    required
                >


                <label for="instituicao-brigadeiro">

                    <strong>

                        E.E.E.M. Brigadeiro José da Silva Paes

                    </strong>


                    <span>

                        Opção específica para estudantes
                        da instituição em Rio Grande - RS.

                    </span>

                </label>


            </div>


            <!-- =================================================
            ESTADUAL
            ================================================== -->

            <div class="questionario-opcao">


                <input
                    type="radio"
                    name="instituicao"
                    id="instituicao-estadual"
                    value="estadual"
                    <?= $instituicaoAtual === "estadual"
                        ? "checked"
                        : ""
                    ?>
                    required
                >


                <label for="instituicao-estadual">

                    <strong>

                        Escola Estadual

                    </strong>


                    <span>

                        Para estudantes de instituições
                        da rede estadual de ensino.

                    </span>

                </label>


            </div>


            <!-- =================================================
            MUNICIPAL
            ================================================== -->

            <div class="questionario-opcao">


                <input
                    type="radio"
                    name="instituicao"
                    id="instituicao-municipal"
                    value="municipal"
                    <?= $instituicaoAtual === "municipal"
                        ? "checked"
                        : ""
                    ?>
                    required
                >


                <label for="instituicao-municipal">

                    <strong>

                        Escola Municipal

                    </strong>


                    <span>

                        Para estudantes de instituições
                        da rede municipal de ensino.

                    </span>

                </label>


            </div>


            <!-- =================================================
            PRIVADA
            ================================================== -->

            <div class="questionario-opcao">


                <input
                    type="radio"
                    name="instituicao"
                    id="instituicao-privada"
                    value="privada"
                    <?= $instituicaoAtual === "privada"
                        ? "checked"
                        : ""
                    ?>
                    required
                >


                <label for="instituicao-privada">

                    <strong>

                        Instituição Privada

                    </strong>


                    <span>

                        Para estudantes matriculados
                        em instituições privadas.

                    </span>

                </label>


            </div>


            <!-- =================================================
            SUPERIOR
            ================================================== -->

            <div class="questionario-opcao">


                <input
                    type="radio"
                    name="instituicao"
                    id="instituicao-superior"
                    value="superior"
                    <?= $instituicaoAtual === "superior"
                        ? "checked"
                        : ""
                    ?>
                    required
                >


                <label for="instituicao-superior">

                    <strong>

                        Ensino Superior

                    </strong>


                    <span>

                        Para estudantes de faculdade,
                        universidade ou instituição equivalente.

                    </span>

                </label>


            </div>


        </div>


        <!-- =====================================================
        AVISO DA ESCOLA BRIGADEIRO
        ====================================================== -->

        <?php if (
            !$ehRioGrande
        ): ?>


            <div class="questionario-aviso">

                A opção

                <strong>

                    E.E.E.M. Brigadeiro José da Silva Paes

                </strong>

                aparece indisponível porque seu perfil
                não está cadastrado como Rio Grande - RS.

            </div>


        <?php endif; ?>


        <!-- =====================================================
        AÇÕES
        ====================================================== -->

        <div class="questionario-acoes">


            <?php if (
                $modoEdicao
            ): ?>


                <!--
                =====================================================
                Antes:

                /frontend/pages/perfil/estudante/perfil.php

                Agora:

                /estudante/perfil
                =====================================================
                -->

                <a
                    href="<?= questionarioEstudanteEscapar(
                        $linkPerfilEstudante
                    ) ?>"
                    class="perfil-botao-secundario"
                >

                    Cancelar

                </a>


            <?php endif; ?>


            <button type="submit">

                <?= $modoEdicao
                    ? "Salvar alteração"
                    : "Finalizar perfil"
                ?>

            </button>


        </div>


    </form>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../../componentes/footer.php";

?>


<!-- =====================================================
TEMA
====================================================== -->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= filemtime(
        __DIR__ .
        "/../../../assets/js/theme.js"
    ) ?>"
></script>


</body>

</html>