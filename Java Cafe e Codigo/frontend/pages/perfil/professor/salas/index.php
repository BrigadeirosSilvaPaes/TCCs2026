<?php

/*
|--------------------------------------------------------------------------
| SALAS DE AULA - PROFESSOR
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Nesta área o Professor aprovado pode:
|
| - criar Salas de Aula;
| - visualizar suas salas;
| - consultar códigos de entrada;
| - acompanhar participantes;
| - acessar a área de publicação de conteúdos.
|
|--------------------------------------------------------------------------
*/


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
| REDIRECIONAMENTO
|--------------------------------------------------------------------------
*/

function salaProfessorRedirecionar(
    string $nomeRota,
    array $parametros = [],
    array $query = [],
    int $codigo = 303
): never {

    header(
        "Location: " .
        rota(
            $nomeRota,
            $parametros,
            $query
        ),
        true,
        $codigo
    );


    exit;

}


/*
|--------------------------------------------------------------------------
| ESCAPAR HTML
|--------------------------------------------------------------------------
*/

function salaProfessorEscapar(
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
| FORMATAR DATA
|--------------------------------------------------------------------------
*/

function salaProfessorFormatarData(
    mixed $data
): string {

    $data =
        trim(
            (string) $data
        );


    if (
        $data === ""
    ) {

        return "Data não informada";

    }


    try {

        $objetoData =
            new DateTime(
                $data
            );


        return $objetoData->format(
            "d/m/Y"
        );

    }
    catch (
        Throwable
    ) {

        return $data;

    }

}


/*
|--------------------------------------------------------------------------
| USUÁRIO
|--------------------------------------------------------------------------
*/

$usuario =
    $_SESSION["usuario"]
    ??
    null;


if (
    !is_array(
        $usuario
    )
) {

    unset(
        $_SESSION["usuario"]
    );


    salaProfessorRedirecionar(
        "login"
    );

}


/*
|--------------------------------------------------------------------------
| ID DO USUÁRIO
|--------------------------------------------------------------------------
*/

$usuarioId =
    filter_var(
        $usuario["id"]
        ??
        null,
        FILTER_VALIDATE_INT,
        [
            "options" => [
                "min_range" => 1
            ]
        ]
    );


if (
    $usuarioId === false
    ||
    $usuarioId === null
) {

    unset(
        $_SESSION["usuario"]
    );


    salaProfessorRedirecionar(
        "login"
    );

}


$usuarioId =
    (int) $usuarioId;


/*
|--------------------------------------------------------------------------
| NOME
|--------------------------------------------------------------------------
*/

$nomeUsuario =
    trim(
        (string) (
            $usuario["nome"]
            ??
            "Professor"
        )
    );


if (
    $nomeUsuario === ""
) {

    $nomeUsuario =
        "Professor";

}


/*
|--------------------------------------------------------------------------
| PRIMEIRO NOME
|--------------------------------------------------------------------------
*/

$partesNome =
    preg_split(
        "/\s+/u",
        $nomeUsuario,
        -1,
        PREG_SPLIT_NO_EMPTY
    );


$primeiroNome =
    is_array(
        $partesNome
    )
    &&
    isset(
        $partesNome[0]
    )
        ?
        $partesNome[0]
        :
        "Professor";


/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

try {

    $perfilController =
        new PerfilController();


    $resultadoPerfil =
        $perfilController->buscarPerfil(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalasProfessor] Erro ao carregar perfil do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar seu perfil.";


    salaProfessorRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| VALIDA PERFIL
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $resultadoPerfil
    )
    ||
    empty(
        $resultadoPerfil["status"]
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


    salaProfessorRedirecionar(
        "painel"
    );

}


$perfilUsuario =
    $resultadoPerfil["perfil"]["usuario"];


$perfilProfessor =
    $resultadoPerfil["perfil"]["professor"]
    ??
    null;


/*
|--------------------------------------------------------------------------
| TIPO DO PERFIL
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


$tipoProfessor =
    defined(
        "ROLE_PROFESSOR"
    )
        ?
        strtolower(
            trim(
                (string) ROLE_PROFESSOR
            )
        )
        :
        "professor";


if (
    $tipoUsuario !==
    $tipoProfessor
) {

    $_SESSION["erro"] =
        "Esta área é exclusiva para professores.";


    salaProfessorRedirecionar(
        "painel"
    );

}


/*
|--------------------------------------------------------------------------
| PERFIL PROFISSIONAL
|--------------------------------------------------------------------------
*/

if (
    !is_array(
        $perfilProfessor
    )
    ||
    empty(
        $perfilProfessor
    )
) {

    $_SESSION["erro"] =
        "Complete seu perfil de professor.";


    salaProfessorRedirecionar(
        "professor.questionario"
    );

}


/*
|--------------------------------------------------------------------------
| APROVAÇÃO
|--------------------------------------------------------------------------
*/

$professorAprovado =
    filter_var(
        $perfilProfessor["aprovado"]
        ??
        false,
        FILTER_VALIDATE_BOOLEAN
    );


if (
    !$professorAprovado
) {

    $_SESSION["erro"] =
        "Seu perfil ainda está em análise. As Salas de Aula serão liberadas após a aprovação administrativa.";


    salaProfessorRedirecionar(
        "professor.painel"
    );

}


/*
|--------------------------------------------------------------------------
| DADOS PROFISSIONAIS
|--------------------------------------------------------------------------
*/

$instituicao =
    trim(
        (string) (
            $perfilProfessor["instituicao"]
            ??
            ""
        )
    );


$disciplina =
    trim(
        (string) (
            $perfilProfessor["disciplina"]
            ??
            ""
        )
    );


if (
    $instituicao === ""
) {

    $instituicao =
        "Instituição não informada";

}


if (
    $disciplina === ""
) {

    $disciplina =
        "Área não informada";

}


/*
|--------------------------------------------------------------------------
| CONTROLLER DE SALAS
|--------------------------------------------------------------------------
*/

try {

    $salaController =
        new SalaController();

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalasProfessor] Erro ao inicializar SalaController: "
        .
        $erro->getMessage()
    );


    $_SESSION["erro"] =
        "Não foi possível carregar as Salas de Aula.";


    salaProfessorRedirecionar(
        "professor.painel"
    );

}


/*
|--------------------------------------------------------------------------
| LINKS
|--------------------------------------------------------------------------
*/

$linkSalas =
    rota(
        "professor.salas"
    );


$linkCriarSala =
    $linkSalas
    .
    "#criar-sala";


$linkPainel =
    rota(
        "professor.painel"
    );


$linkPerfil =
    rota(
        "professor.perfil"
    );


$linkAulas =
    rota(
        "aulas"
    );


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

if (
    !isset(
        $_SESSION["csrf_salas_professor"]
    )
    ||
    !is_string(
        $_SESSION["csrf_salas_professor"]
    )
    ||
    preg_match(
        "/^[a-f0-9]{64}$/",
        $_SESSION["csrf_salas_professor"]
    )
    !== 1
) {

    $_SESSION["csrf_salas_professor"] =
        bin2hex(
            random_bytes(32)
        );

}


$csrfToken =
    $_SESSION["csrf_salas_professor"];


/*
|--------------------------------------------------------------------------
| MÉTODO
|--------------------------------------------------------------------------
*/

$metodo =
    strtoupper(
        trim(
            (string) (
                $_SERVER["REQUEST_METHOD"]
                ??
                "GET"
            )
        )
    );


/*
|--------------------------------------------------------------------------
| POST
|--------------------------------------------------------------------------
*/

if (
    $metodo ===
    "POST"
) {

    /*
    |--------------------------------------------------------------------------
    | ORIGEM
    |--------------------------------------------------------------------------
    */

    $fetchSite =
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
        $fetchSite !== ""
        &&
        !in_array(
            $fetchSite,
            [
                "same-origin",
                "same-site",
                "none"
            ],
            true
        )
    ) {

        $_SESSION["erro"] =
            "Não foi possível validar a origem da solicitação.";


        salaProfessorRedirecionar(
            "professor.salas"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CSRF RECEBIDO
    |--------------------------------------------------------------------------
    */

    $csrfRecebido =
        trim(
            (string) (
                $_POST["csrf_token"]
                ??
                ""
            )
        );


    if (
        preg_match(
            "/^[a-f0-9]{64}$/",
            $csrfRecebido
        )
        !== 1
        ||
        !hash_equals(
            $csrfToken,
            $csrfRecebido
        )
    ) {

        unset(
            $_SESSION["csrf_salas_professor"]
        );


        $_SESSION["erro"] =
            "Não foi possível validar a solicitação. Atualize a página e tente novamente.";


        salaProfessorRedirecionar(
            "professor.salas"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AÇÃO
    |--------------------------------------------------------------------------
    */

    $acao =
        trim(
            (string) (
                $_POST["acao"]
                ??
                ""
            )
        );


    /*
    |--------------------------------------------------------------------------
    | CRIAR SALA
    |--------------------------------------------------------------------------
    */

    if (
        $acao ===
        "criar"
    ) {

        $nomeSala =
            trim(
                (string) (
                    $_POST["nome"]
                    ??
                    ""
                )
            );


        $linguagem =
            trim(
                (string) (
                    $_POST["linguagem"]
                    ??
                    ""
                )
            );


        $descricao =
            trim(
                (string) (
                    $_POST["descricao"]
                    ??
                    ""
                )
            );


        /*
        |--------------------------------------------------------------------------
        | NOME
        |--------------------------------------------------------------------------
        */

        if (
            $nomeSala === ""
        ) {

            $_SESSION["erro"] =
                "Informe o nome da sala.";


            salaProfessorRedirecionar(
                "professor.salas"
            );

        }


        $tamanhoNome =
            function_exists(
                "mb_strlen"
            )
                ?
                mb_strlen(
                    $nomeSala,
                    "UTF-8"
                )
                :
                strlen(
                    $nomeSala
                );


        if (
            $tamanhoNome > 120
        ) {

            $_SESSION["erro"] =
                "O nome da sala deve possuir no máximo 120 caracteres.";


            salaProfessorRedirecionar(
                "professor.salas"
            );

        }


        /*
        |--------------------------------------------------------------------------
        | LINGUAGEM
        |--------------------------------------------------------------------------
        */

        $tamanhoLinguagem =
            function_exists(
                "mb_strlen"
            )
                ?
                mb_strlen(
                    $linguagem,
                    "UTF-8"
                )
                :
                strlen(
                    $linguagem
                );


        if (
            $tamanhoLinguagem > 80
        ) {

            $_SESSION["erro"] =
                "A linguagem ou conteúdo deve possuir no máximo 80 caracteres.";


            salaProfessorRedirecionar(
                "professor.salas"
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DESCRIÇÃO
        |--------------------------------------------------------------------------
        */

        $tamanhoDescricao =
            function_exists(
                "mb_strlen"
            )
                ?
                mb_strlen(
                    $descricao,
                    "UTF-8"
                )
                :
                strlen(
                    $descricao
                );


        if (
            $tamanhoDescricao > 1000
        ) {

            $_SESSION["erro"] =
                "A descrição deve possuir no máximo 1000 caracteres.";


            salaProfessorRedirecionar(
                "professor.salas"
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CÓDIGO ÚNICO
        |--------------------------------------------------------------------------
        */

        $codigoSala =
            "JCC-"
            .
            strtoupper(
                bin2hex(
                    random_bytes(4)
                )
            );


        /*
        |--------------------------------------------------------------------------
        | CRIA
        |--------------------------------------------------------------------------
        */

        try {

            $resultadoCriacao =
                $salaController->criar(

                    $usuarioId,

                    $nomeSala,

                    $codigoSala,

                    $linguagem !== ""
                        ?
                        $linguagem
                        :
                        null,

                    $descricao !== ""
                        ?
                        $descricao
                        :
                        null

                );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[SalasProfessor] Erro ao criar sala para o usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );


            $_SESSION["erro"] =
                "Não foi possível criar a sala.";


            salaProfessorRedirecionar(
                "professor.salas"
            );

        }


        if (
            !is_array(
                $resultadoCriacao
            )
            ||
            empty(
                $resultadoCriacao["status"]
            )
        ) {

            $_SESSION["erro"] =
                is_array(
                    $resultadoCriacao
                )
                    ?
                    (
                        $resultadoCriacao["mensagem"]
                        ??
                        "Não foi possível criar a sala."
                    )
                    :
                    "Não foi possível criar a sala.";


            salaProfessorRedirecionar(
                "professor.salas"
            );

        }


        /*
        |--------------------------------------------------------------------------
        | RENOVA CSRF
        |--------------------------------------------------------------------------
        */

        $_SESSION["csrf_salas_professor"] =
            bin2hex(
                random_bytes(32)
            );


        /*
        |--------------------------------------------------------------------------
        | SUCESSO
        |--------------------------------------------------------------------------
        */

        $_SESSION["sucesso"] =
            "Sala criada com sucesso. Código: "
            .
            $codigoSala;


        salaProfessorRedirecionar(
            "professor.salas"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AÇÃO DESCONHECIDA
    |--------------------------------------------------------------------------
    */

    $_SESSION["erro"] =
        "Ação inválida.";


    salaProfessorRedirecionar(
        "professor.salas"
    );

}


/*
|--------------------------------------------------------------------------
| MÉTODO NÃO SUPORTADO
|--------------------------------------------------------------------------
*/

if (
    $metodo !==
    "GET"
) {

    header(
        "Allow: GET, POST"
    );


    $_SESSION["erro"] =
        "Método não permitido.";


    salaProfessorRedirecionar(
        "professor.salas"
    );

}


/*
|--------------------------------------------------------------------------
| LISTAR SALAS
|--------------------------------------------------------------------------
*/

try {

    $resultadoSalas =
        $salaController->listarSalasProfessor(
            $usuarioId
        );

}
catch (
    Throwable $erro
) {

    error_log(
        "[SalasProfessor] Erro ao listar salas do usuário "
        .
        $usuarioId
        .
        ": "
        .
        $erro->getMessage()
    );


    $resultadoSalas = [
        "status" => false
    ];


    $_SESSION["erro"] =
        "Não foi possível carregar suas salas.";

}


/*
|--------------------------------------------------------------------------
| SALAS
|--------------------------------------------------------------------------
*/

$salas =
    [];


if (
    is_array(
        $resultadoSalas
    )
    &&
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
) {

    $salas =
        $resultadoSalas["salas"];

}


/*
|--------------------------------------------------------------------------
| ESTATÍSTICAS
|--------------------------------------------------------------------------
*/

$totalSalas =
    count(
        $salas
    );


$totalAlunos =
    0;


$salasPreparadas =
    [];


/*
|--------------------------------------------------------------------------
| PREPARA SALAS
|--------------------------------------------------------------------------
*/

foreach (
    $salas as $sala
) {

    if (
        !is_array(
            $sala
        )
    ) {

        continue;

    }


    $salaId =
        filter_var(
            $sala["id"]
            ??
            null,
            FILTER_VALIDATE_INT,
            [
                "options" => [
                    "min_range" => 1
                ]
            ]
        );


    if (
        $salaId === false
        ||
        $salaId === null
    ) {

        continue;

    }


    $salaId =
        (int) $salaId;


    $quantidadeAlunos =
        0;


    try {

        $resultadoAlunos =
            $salaController->listarAlunos(
                $salaId,
                $usuarioId
            );


        if (
            is_array(
                $resultadoAlunos
            )
            &&
            !empty(
                $resultadoAlunos["status"]
            )
            &&
            isset(
                $resultadoAlunos["alunos"]
            )
            &&
            is_array(
                $resultadoAlunos["alunos"]
            )
        ) {

            $quantidadeAlunos =
                count(
                    $resultadoAlunos["alunos"]
                );

        }

    }
    catch (
        Throwable $erro
    ) {

        error_log(
            "[SalasProfessor] Erro ao contar alunos da sala "
            .
            $salaId
            .
            ": "
            .
            $erro->getMessage()
        );

    }


    $totalAlunos +=
        $quantidadeAlunos;


    $sala["_total_alunos"] =
        $quantidadeAlunos;


    $salasPreparadas[] =
        $sala;

}


/*
|--------------------------------------------------------------------------
| CORRIGE TOTAL DE SALAS VÁLIDAS
|--------------------------------------------------------------------------
*/

$totalSalas =
    count(
        $salasPreparadas
    );


/*
|--------------------------------------------------------------------------
| ASSETS
|--------------------------------------------------------------------------
*/

$caminhoStyle =
    __DIR__
    .
    "/../../../../assets/css/style.css";


$caminhoMensagem =
    __DIR__
    .
    "/../../../../assets/css/mensagem.css";


$caminhoEstudante =
    __DIR__
    .
    "/../../../../assets/css/estudante.css";


$caminhoSalas =
    __DIR__
    .
    "/../../../../assets/css/salas_professor.css";


$caminhoTheme =
    __DIR__
    .
    "/../../../../assets/js/theme.js";


$versaoStyle =
    is_file(
        $caminhoStyle
    )
        ?
        (string) filemtime(
            $caminhoStyle
        )
        :
        "1";


$versaoMensagem =
    is_file(
        $caminhoMensagem
    )
        ?
        (string) filemtime(
            $caminhoMensagem
        )
        :
        "1";


$versaoEstudante =
    is_file(
        $caminhoEstudante
    )
        ?
        (string) filemtime(
            $caminhoEstudante
        )
        :
        "1";


$cssSalasExiste =
    is_file(
        $caminhoSalas
    );


$versaoSalas =
    $cssSalasExiste
        ?
        (string) filemtime(
            $caminhoSalas
        )
        :
        "1";


$versaoTheme =
    is_file(
        $caminhoTheme
    )
        ?
        (string) filemtime(
            $caminhoTheme
        )
        :
        "1";

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
    content="Gerenciamento das Salas de Aula do Professor."
>

<title>
    Salas de Aula | <?= salaProfessorEscapar(SITE_NAME) ?>
</title>


<!-- =====================================================
CSS GLOBAL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= salaProfessorEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/style.css?v="
        .
        rawurlencode(
            $versaoStyle
        )
    ) ?>"
>


<!-- =====================================================
MENSAGENS DO SISTEMA
====================================================== -->

<link
    rel="stylesheet"
    href="<?= salaProfessorEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/mensagem.css?v="
        .
        rawurlencode(
            $versaoMensagem
        )
    ) ?>"
>


<!-- =====================================================
PAINEL
====================================================== -->

<link
    rel="stylesheet"
    href="<?= salaProfessorEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/estudante.css?v="
        .
        rawurlencode(
            $versaoEstudante
        )
    ) ?>"
>


<!-- =====================================================
SALAS DO PROFESSOR
====================================================== -->

<?php if (
    $cssSalasExiste
): ?>

<link
    rel="stylesheet"
    href="<?= salaProfessorEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/css/salas_professor.css?v="
        .
        rawurlencode(
            $versaoSalas
        )
    ) ?>"
>

<?php endif; ?>

</head>


<body class="pagina-estudante pagina-professor pagina-salas-professor">


<?php

require_once __DIR__
    .
    "/../../../../componentes/header.php";


require_once __DIR__
    .
    "/../../../../componentes/mensagem.php";

?>


<main class="painel-estudante">


<!-- =====================================================
APRESENTAÇÃO
====================================================== -->

<section class="painel-apresentacao">


    <div class="painel-apresentacao-conteudo">


        <span class="painel-etiqueta">
            SALAS DE AULA
        </span>


        <h1>

            Suas turmas,

            <strong>
                <?= salaProfessorEscapar(
                    $primeiroNome
                ) ?>.
            </strong>

        </h1>


        <p>

            Crie espaços para organizar estudantes,
            compartilhar conteúdos e acompanhar
            suas turmas dentro do Java, Café e Código.

        </p>


        <div class="painel-apresentacao-acoes">


            <a
                href="<?= salaProfessorEscapar(
                    $linkCriarSala
                ) ?>"
                class="botao-principal"
            >

                Criar nova sala

                <span aria-hidden="true">
                    +
                </span>

            </a>


            <a
                href="<?= salaProfessorEscapar(
                    $linkPainel
                ) ?>"
                class="botao-secundario"
            >

                Voltar ao painel

            </a>


        </div>


    </div>


    <!-- =================================================
    REPRESENTAÇÃO VISUAL
    ================================================== -->

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
                PROFESSOR
            </small>


        </div>


<pre><code><span class="codigo-roxo">public class</span> <span class="codigo-azul">SalaDeAula</span> {

    <span class="codigo-roxo">public void</span> <span class="codigo-amarelo">organizarTurma</span>() {

        turma.<span class="codigo-amarelo">adicionarAlunos</span>();
        turma.<span class="codigo-amarelo">publicarConteudos</span>();
        turma.<span class="codigo-amarelo">acompanhar</span>();

    }

}</code></pre>


        <div class="codigo-status">


            <span>
                ● Professor aprovado
            </span>


            <span>
                Salas liberadas
            </span>


        </div>


    </div>


</section>


<!-- =====================================================
ESTATÍSTICAS
====================================================== -->

<section class="painel-estatisticas">


    <article>


        <span class="estatistica-icone">
            SA
        </span>


        <div>


            <small>
                MINHAS SALAS
            </small>


            <strong>

                <?= salaProfessorEscapar(
                    $totalSalas
                ) ?>

            </strong>


            <p>

                <?= $totalSalas === 1
                    ?
                    "sala criada"
                    :
                    "salas criadas"
                ?>

            </p>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">
            AL
        </span>


        <div>


            <small>
                PARTICIPANTES
            </small>


            <strong>

                <?= salaProfessorEscapar(
                    $totalAlunos
                ) ?>

            </strong>


            <p>

                <?= $totalAlunos === 1
                    ?
                    "estudante vinculado"
                    :
                    "estudantes vinculados"
                ?>

            </p>


        </div>


    </article>


    <article>


        <span class="estatistica-icone">
            PR
        </span>


        <div>


            <small>
                SUA ÁREA
            </small>


            <strong class="estatistica-texto">

                <?= salaProfessorEscapar(
                    $disciplina
                ) ?>

            </strong>


            <p>

                <?= salaProfessorEscapar(
                    $instituicao
                ) ?>

            </p>


        </div>


    </article>


</section>


<!-- =====================================================
CRIAR SALA
====================================================== -->

<section
    class="painel-estudo"
    id="criar-sala"
>


    <article class="painel-ultima-aula sala-professor-criar">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">
                    NOVA SALA
                </span>


                <h2>
                    Criar Sala de Aula
                </h2>


            </div>


            <span class="painel-card-numero">
                +
            </span>


        </div>


        <div class="painel-aula-conteudo">


            <p>

                Informe os dados principais da turma.
                O código de entrada dos estudantes será
                criado automaticamente pelo sistema.

            </p>


            <form
                method="POST"
                action="<?= salaProfessorEscapar(
                    $linkSalas
                ) ?>"
                class="sala-professor-formulario"
            >


                <input
                    type="hidden"
                    name="acao"
                    value="criar"
                >


                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= salaProfessorEscapar(
                        $csrfToken
                    ) ?>"
                >


                <!-- NOME -->

                <div class="sala-professor-campo">


                    <label for="nome">

                        Nome da sala

                        <span>
                            *
                        </span>

                    </label>


                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        maxlength="120"
                        placeholder="Ex.: Java — Turma 3A"
                        required
                    >


                    <small>
                        Escolha um nome fácil de identificar.
                    </small>


                </div>


                <!-- CONTEÚDO -->

                <div class="sala-professor-campo">


                    <label for="linguagem">
                        Linguagem ou conteúdo
                    </label>


                    <input
                        type="text"
                        id="linguagem"
                        name="linguagem"
                        maxlength="80"
                        value="Java"
                        placeholder="Ex.: Java"
                    >


                    <small>

                        Identifique o principal conteúdo
                        trabalhado com a turma.

                    </small>


                </div>


                <!-- DESCRIÇÃO -->

                <div class="sala-professor-campo sala-professor-campo-completo">


                    <label for="descricao">
                        Descrição
                    </label>


                    <textarea
                        id="descricao"
                        name="descricao"
                        rows="5"
                        maxlength="1000"
                        placeholder="Ex.: Sala destinada à turma 3A..."
                    ></textarea>


                    <small>
                        Explique brevemente a finalidade da sala.
                    </small>


                </div>


                <div class="sala-professor-formulario-acoes">


                    <button
                        type="submit"
                        class="botao-principal"
                    >

                        Criar sala

                        <span aria-hidden="true">
                            →
                        </span>

                    </button>


                </div>


            </form>


        </div>


    </article>


    <!-- =================================================
    ENTRADA DOS ALUNOS
    ================================================== -->

    <article class="painel-proximo-passo">


        <div class="painel-card-cabecalho">


            <div>


                <span class="painel-etiqueta">
                    COMO FUNCIONA
                </span>


                <h2>
                    Entrada dos alunos
                </h2>


            </div>


            <span class="painel-proximo-icone">
                #
            </span>


        </div>


        <div class="painel-proximo-conteudo">


            <small>
                CÓDIGO DA SALA
            </small>


            <h3>
                Compartilhe o código
            </h3>


            <p>

                Cada nova sala recebe um código único,
                como:

                <strong>
                    JCC-8F2A91BC
                </strong>

            </p>


            <p>

                O estudante acessa a área de Salas de Aula,
                informa esse código e passa a fazer parte
                da turma.

            </p>


        </div>


        <a
            href="<?= salaProfessorEscapar(
                $linkAulas
            ) ?>"
            class="botao-secundario"
        >

            Ver Aulas Conversadas

        </a>


    </article>


</section>


<!-- =====================================================
MINHAS SALAS
====================================================== -->

<section class="painel-acessos salas-professor-listagem">


    <div class="painel-secao-cabecalho">


        <span class="painel-etiqueta">
            MINHAS SALAS
        </span>


        <h2>
            Salas criadas
        </h2>


        <p>

            Consulte suas turmas e os códigos utilizados
            pelos estudantes para entrar em cada sala.

        </p>


    </div>


    <?php if (
        empty(
            $salasPreparadas
        )
    ): ?>


        <div class="sala-professor-vazio">


            <span class="sala-professor-vazio-icone">
                SA
            </span>


            <h3>
                Nenhuma sala criada
            </h3>


            <p>

                Você ainda não possui nenhuma Sala de Aula.
                Crie a primeira turma para começar
                a organizar seus estudantes.

            </p>


            <a
                href="<?= salaProfessorEscapar(
                    $linkCriarSala
                ) ?>"
                class="botao-principal"
            >

                Criar minha primeira sala

            </a>


        </div>


    <?php else: ?>


        <div class="painel-acessos-grid">


            <?php foreach (
                $salasPreparadas as $sala
            ): ?>


                <?php

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


                $quantidadeAlunos =
                    (int) (
                        $sala["_total_alunos"]
                        ??
                        0
                    );


                $dataCriacao =
                    salaProfessorFormatarData(
                        $sala["criado_em"]
                        ??
                        ""
                    );


                /*
                |--------------------------------------------------------------------------
                | URL LIMPA
                |--------------------------------------------------------------------------
                |
                | Antes:
                |
                | /frontend/pages/perfil/professor/salas/abrir.php?id=3
                |
                | Agora:
                |
                | /professor/salas/3
                |
                |--------------------------------------------------------------------------
                */

                $linkAbrirSala =
                    rota(
                        "professor.sala",
                        [
                            "id" =>
                                $salaId
                        ]
                    );

                ?>


                <article class="acesso-card sala-professor-card">


                    <div class="acesso-card-topo">


                        <span class="acesso-icone">
                            SA
                        </span>


                        <span class="acesso-status acesso-disponivel">
                            Ativa
                        </span>


                    </div>


                    <div>


                        <small>

                            <?= salaProfessorEscapar(
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

                            <?= salaProfessorEscapar(
                                $nomeSala
                            ) ?>

                        </h3>


                        <?php if (
                            $descricaoSala !== ""
                        ): ?>


                            <p>

                                <?= salaProfessorEscapar(
                                    $descricaoSala
                                ) ?>

                            </p>


                        <?php else: ?>


                            <p>
                                Sala criada para organização da turma.
                            </p>


                        <?php endif; ?>


                    </div>


                    <div class="sala-professor-card-informacoes">


                        <div>


                            <small>
                                CÓDIGO
                            </small>


                            <strong class="sala-professor-codigo">

                                <?= salaProfessorEscapar(
                                    $codigoSala
                                ) ?>

                            </strong>


                        </div>


                        <div>


                            <small>
                                ALUNOS
                            </small>


                            <strong>

                                <?= salaProfessorEscapar(
                                    $quantidadeAlunos
                                ) ?>

                            </strong>


                        </div>


                    </div>


                    <div class="sala-professor-card-acoes">


                        <a
                            href="<?= salaProfessorEscapar(
                                $linkAbrirSala
                            ) ?>"
                            class="botao-principal"
                        >

                            Abrir sala

                            <span aria-hidden="true">
                                →
                            </span>

                        </a>


                    </div>


                    <div class="sala-professor-card-rodape">


                        <span>

                            Criada em

                            <strong>

                                <?= salaProfessorEscapar(
                                    $dataCriacao
                                ) ?>

                            </strong>

                        </span>


                        <span>

                            Sala #

                            <?= salaProfessorEscapar(
                                $salaId
                            ) ?>

                        </span>


                    </div>


                </article>


            <?php endforeach; ?>


        </div>


    <?php endif; ?>


</section>


<!-- =====================================================
FLUXO
====================================================== -->

<section class="painel-orientacao">


    <div class="painel-orientacao-texto">


        <span class="painel-etiqueta">
            FLUXO DA TURMA
        </span>


        <h2>
            Como a Sala de Aula funciona
        </h2>


        <p>

            Depois de criar uma sala, compartilhe o código
            com seus estudantes. Cada aluno entra utilizando
            sua própria conta e passa a fazer parte daquela turma.

        </p>


    </div>


    <div class="painel-orientacao-etapas">


        <article>


            <span>
                01
            </span>


            <strong>
                Crie
            </strong>


            <p>

                Defina o nome, conteúdo
                e descrição da turma.

            </p>


        </article>


        <article>


            <span>
                02
            </span>


            <strong>
                Compartilhe
            </strong>


            <p>

                Envie o código único
                da sala aos estudantes.

            </p>


        </article>


        <article>


            <span>
                03
            </span>


            <strong>
                Gerencie
            </strong>


            <p>

                Acompanhe os participantes
                e os conteúdos publicados
                dentro da sala.

            </p>


        </article>


    </div>


</section>


<!-- =====================================================
AÇÕES FINAIS
====================================================== -->

<section class="painel-apresentacao-acoes">


    <a
        href="<?= salaProfessorEscapar(
            $linkPainel
        ) ?>"
        class="botao-secundario"
    >

        Voltar ao Painel

    </a>


    <a
        href="<?= salaProfessorEscapar(
            $linkPerfil
        ) ?>"
        class="botao-secundario"
    >

        Meu Perfil

    </a>


</section>


</main>


<?php

require_once __DIR__
    .
    "/../../../../componentes/footer.php";

?>


<script
    src="<?= salaProfessorEscapar(
        rtrim(
            BASE_URL,
            "/"
        )
        .
        "/frontend/assets/js/theme.js?v="
        .
        rawurlencode(
            $versaoTheme
        )
    ) ?>"
></script>


</body>

</html>