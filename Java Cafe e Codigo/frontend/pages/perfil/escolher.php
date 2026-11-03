<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../backend/config/app.php";
require_once __DIR__ . "/../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../backend/config/helpers.php";

require_once __DIR__ . "/componentes/localidades.php";


/*
|--------------------------------------------------------------------------
| SESSÃO
|--------------------------------------------------------------------------
*/

garantirSessao();


/*
|--------------------------------------------------------------------------
| VERIFICA LOGIN
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["usuario"])
    ||
    !is_array($_SESSION["usuario"])
) {

    header(
        "Location: " .
        rota("login")
    );

    exit;

}


$usuario = $_SESSION["usuario"];


/*
|--------------------------------------------------------------------------
| NOME DO USUÁRIO
|--------------------------------------------------------------------------
*/

$nomeUsuario = "Estudante";

if (is_array($usuario)) {

    $nomeUsuario =
        $usuario["nome"] ??
        $usuario["name"] ??
        "Estudante";

} elseif (is_object($usuario)) {

    $nomeUsuario =
        $usuario->nome ??
        $usuario->name ??
        "Estudante";

} elseif (is_string($usuario) && trim($usuario) !== "") {

    $nomeUsuario = $usuario;

}


$nomeUsuario = trim($nomeUsuario);

$primeiroNome = explode(" ", $nomeUsuario)[0] ?? "Estudante";

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
    content="Escolha como deseja utilizar a plataforma Java, Café e Código e personalize sua experiência de aprendizagem."
>

<title>
    Escolha seu perfil | <?= SITE_NAME ?>
</title>

<!-- CSS PRINCIPAL -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css"
>

<!-- CSS DO PERFIL -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/perfil.css"
>

<!-- CSS DAS MENSAGENS -->

<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css"
>

</head>

<body class="pagina-escolher-perfil">

<!-- HEADER -->

<?php

include __DIR__ . "/../../componentes/header.php";

?>

<!-- MENSAGEM DO SISTEMA -->

<?php

include __DIR__ . "/../../componentes/mensagem.php";

?>

<!-- CONTEÚDO PRINCIPAL -->

<main class="perfil-onboarding">

    <!-- ELEMENTOS DECORATIVOS -->

    <div
        class="perfil-luz perfil-luz-superior"
        aria-hidden="true"
    ></div>

    <div
        class="perfil-luz perfil-luz-inferior"
        aria-hidden="true"
    ></div>

    <div class="perfil-onboarding-container">

        <!-- CABEÇALHO -->

        <section class="perfil-onboarding-cabecalho">

            <div class="perfil-onboarding-cabecalho-texto">

                <span class="perfil-etiqueta">

                    CONFIGURAÇÃO INICIAL

                </span>

                <h1>

                    Olá,
                    <strong>
                        <?= htmlspecialchars(
                            $primeiroNome,
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>!
                    </strong>

                    Vamos preparar sua experiência.

                </h1>

                <p>

                    Antes de acessar os conteúdos, conte um pouco sobre
                    como você pretende utilizar a plataforma. Essas
                    informações ajudam a organizar sua jornada de maneira
                    mais adequada ao seu perfil.

                </p>

            </div>

            <!-- ETAPA -->

            <div class="perfil-etapa-atual">

                <span class="perfil-etapa-numero">
                    1
                </span>

                <div>

                    <strong>
                        Personalização
                    </strong>

                    <small>
                        Primeira configuração da conta
                    </small>

                </div>

            </div>

        </section>

        <!-- FORMULÁRIO -->

        <form
            class="perfil-formulario"
            action="<?= BASE_URL ?>/backend/perfil/salvar_perfil.php"
            method="POST"
        >

            <!-- ESCOLHA DO PERFIL -->

            <section class="perfil-secao">

                <div class="perfil-secao-cabecalho">

                    <span class="perfil-secao-numero">
                        01
                    </span>

                    <div>

                        <h2>
                            Como você utilizará a plataforma?
                        </h2>

                        <p>
                            Escolha a opção que melhor representa seu objetivo.
                        </p>

                    </div>

                </div>

                <div class="opcoes-perfil">

                    <!-- ESTUDANTE -->

                    <label class="opcao-perfil">

                        <input
                            type="radio"
                            name="tipo_usuario"
                            value="estudante"
                            required
                        >

                        <span class="opcao-perfil-marcador"></span>

                        <span
                            class="opcao-perfil-icone"
                            aria-hidden="true"
                        >

                            01

                        </span>

                        <span class="opcao-perfil-conteudo">

                            <strong>
                                Sou estudante
                            </strong>

                            <p>
                                Estou aprendendo programação na escola,
                                curso técnico, faculdade ou outro ambiente
                                educacional.
                            </p>

                            <small>
                                Aprendizagem orientada
                            </small>

                        </span>

                    </label>

                    <!-- PROFESSOR -->

                    <label class="opcao-perfil">

                        <input
                            type="radio"
                            name="tipo_usuario"
                            value="professor"
                            required
                        >

                        <span class="opcao-perfil-marcador"></span>

                        <span
                            class="opcao-perfil-icone"
                            aria-hidden="true"
                        >

                            02

                        </span>

                        <span class="opcao-perfil-conteudo">

                            <strong>
                                Sou professor
                            </strong>

                            <p>
                                Quero utilizar os conteúdos como apoio em
                                aulas, explicações ou atividades com estudantes.
                            </p>

                            <small>
                                Apoio ao ensino
                            </small>

                        </span>

                    </label>

                    <!-- APRENDIZ -->

                    <label class="opcao-perfil">

                        <input
                            type="radio"
                            name="tipo_usuario"
                            value="aprendiz"
                            required
                        >

                        <span class="opcao-perfil-marcador"></span>

                        <span
                            class="opcao-perfil-icone"
                            aria-hidden="true"
                        >

                            03

                        </span>

                        <span class="opcao-perfil-conteudo">

                            <strong>
                                Aprendo por conta própria
                            </strong>

                            <p>
                                Quero estudar Java de maneira independente,
                                seguindo meu próprio ritmo de aprendizagem.
                            </p>

                            <small>
                                Aprendizagem autônoma
                            </small>

                        </span>

                    </label>

                </div>

            </section>

            <!-- LOCALIZAÇÃO -->

            <section class="perfil-secao">

                <div class="perfil-secao-cabecalho">

                    <span class="perfil-secao-numero">
                        02
                    </span>

                    <div>

                        <h2>
                            De onde você está acessando?
                        </h2>

                        <p>
                            Informe sua localização para conhecermos melhor
                            o alcance da plataforma.
                        </p>

                    </div>

                </div>

                <div class="perfil-campos-grid">

                    <!-- ESTADO -->

                    <div class="campo-perfil">

                        <label for="estado">

                            Estado

                        </label>

                        <div class="perfil-select-container">

                            <select
                                name="estado"
                                id="estado"
                                required
                            >

                                <option value="">

                                    Selecione seu estado

                                </option>

                                <?php foreach ($estados as $sigla => $nome): ?>

                                    <option
                                        value="<?= htmlspecialchars(
                                            $sigla,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $nome,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                            <span
                                class="perfil-select-seta"
                                aria-hidden="true"
                            >

                                ↓

                            </span>

                        </div>

                    </div>

                    <!-- CIDADE -->

                    <div
                        class="campo-perfil campo-cidade"
                        id="campo-cidade"
                        hidden
                    >

                        <label for="cidade">

                            Cidade

                        </label>

                        <div class="perfil-select-container">

                            <select
                                name="cidade"
                                id="cidade"
                            >

                                <option value="">

                                    Selecione sua cidade

                                </option>

                                <?php foreach ($cidades_rs as $cidade): ?>

                                    <option
                                        value="<?= htmlspecialchars(
                                            $cidade,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $cidade,
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                            <span
                                class="perfil-select-seta"
                                aria-hidden="true"
                            >

                                ↓

                            </span>

                        </div>

                    </div>

                </div>

                <div class="perfil-informacao">

                    <span
                        class="perfil-informacao-icone"
                        aria-hidden="true"
                    >

                        i

                    </span>

                    <p>

                        A cidade será solicitada somente quando o estado
                        selecionado for o Rio Grande do Sul.

                    </p>

                </div>

            </section>

            <!-- OBJETIVO -->

            <section class="perfil-secao">

                <div class="perfil-secao-cabecalho">

                    <span class="perfil-secao-numero">
                        03
                    </span>

                    <div>

                        <h2>
                            Qual é seu principal objetivo?
                        </h2>

                        <p>
                            Essa resposta é opcional e poderá ser alterada
                            posteriormente em seu perfil.
                        </p>

                    </div>

                </div>

                <div class="campo-perfil campo-objetivo">

                    <label for="objetivo">

                        Conte um pouco sobre sua meta

                    </label>

                    <div class="perfil-textarea-container">

                        <textarea
                            name="objetivo"
                            id="objetivo"
                            maxlength="500"
                            placeholder="Exemplo: quero aprender os fundamentos de Java, melhorar minha lógica de programação e desenvolver meu primeiro projeto..."
                            aria-describedby="objetivo-ajuda"
                        ></textarea>

                        <span
                            class="perfil-contador"
                            id="contador-objetivo"
                        >

                            0/500

                        </span>

                    </div>

                    <p
                        id="objetivo-ajuda"
                        class="perfil-campo-ajuda"
                    >

                        Não é necessário utilizar termos técnicos. Escreva
                        com suas próprias palavras.

                    </p>

                </div>

            </section>

            <!-- AÇÕES -->

            <section class="perfil-acoes">

                <div class="perfil-acoes-texto">

                    <strong>
                        Tudo pronto para começar
                    </strong>

                    <p>
                        Você poderá atualizar essas informações
                        posteriormente nas configurações do perfil.
                    </p>

                </div>

                <button
                    type="submit"
                    class="perfil-botao-continuar"
                >

                    <span>
                        Salvar e continuar
                    </span>

                    <span
                        class="perfil-botao-seta"
                        aria-hidden="true"
                    >

                        →

                    </span>

                </button>

            </section>

        </form>

        <p class="perfil-aviso-privacidade">

            As informações desta página são utilizadas apenas para
            personalizar a experiência e compreender o público da plataforma.

        </p>

    </div>

</main>

<!-- FOOTER -->

<?php

include __DIR__ . "/../../componentes/footer.php";

?>

<!-- JAVASCRIPT DO TEMA -->

<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js"
></script>

<!-- JAVASCRIPT DA PÁGINA -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const estado =
            document.getElementById("estado");

        const campoCidade =
            document.getElementById("campo-cidade");

        const cidade =
            document.getElementById("cidade");

        const objetivo =
            document.getElementById("objetivo");

        const contadorObjetivo =
            document.getElementById("contador-objetivo");


        /*
        |--------------------------------------------------------------------------
        | EXIBIR CIDADE DO RIO GRANDE DO SUL
        |--------------------------------------------------------------------------
        */

        function atualizarCampoCidade() {

            if (estado.value === "RS") {

                campoCidade.hidden = false;

                cidade.required = true;

            } else {

                campoCidade.hidden = true;

                cidade.required = false;

                cidade.value = "";

            }

        }


        estado.addEventListener(
            "change",
            atualizarCampoCidade
        );


        atualizarCampoCidade();


        /*
        |--------------------------------------------------------------------------
        | CONTADOR DO OBJETIVO
        |--------------------------------------------------------------------------
        */

        function atualizarContadorObjetivo() {

            const quantidade =
                objetivo.value.length;

            contadorObjetivo.textContent =
                quantidade + "/500";

        }


        objetivo.addEventListener(
            "input",
            atualizarContadorObjetivo
        );


        atualizarContadorObjetivo();

    }
);

</script>

</body>

</html>
