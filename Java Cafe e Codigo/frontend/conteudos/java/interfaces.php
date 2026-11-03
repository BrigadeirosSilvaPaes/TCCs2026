<?php

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../backend/config/app.php";
require_once __DIR__ . "/../../../backend/config/bootstrap.php";
require_once __DIR__ . "/../../../backend/config/helpers.php";


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


$linkConteudos =
    rota(
        "conteudos"
    );


$linkAulaAnterior =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "polimorfismo"
        ]
    );


$linkProximaAula =
    rota(
        "java.conteudo",
        [
            "slug" =>
                "excecoes"
        ]
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
    ||
    !is_array(
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
| ASSETS
|--------------------------------------------------------------------------
*/

$arquivoStyle =
    __DIR__ .
    "/../../assets/css/style.css";


$arquivoAulaCss =
    __DIR__ .
    "/../../assets/css/aula.css";


$arquivoThemeJs =
    __DIR__ .
    "/../../assets/js/theme.js";


$versaoStyle =
    is_file(
        $arquivoStyle
    )
        ? filemtime(
            $arquivoStyle
        )
        : 1;


$versaoAulaCss =
    is_file(
        $arquivoAulaCss
    )
        ? filemtime(
            $arquivoAulaCss
        )
        : 1;


$versaoThemeJs =
    is_file(
        $arquivoThemeJs
    )
        ? filemtime(
            $arquivoThemeJs
        )
        : 1;


/*
|--------------------------------------------------------------------------
| ESCAPA HTML
|--------------------------------------------------------------------------
*/

function aulaJavaEscapar(
    mixed $valor
): string
{

    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );

}

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
    content="Aprenda como criar e implementar interfaces em Java para definir contratos, aplicar polimorfismo e organizar comportamentos."
>

<title>
    Interfaces em Java | Java, Café e Código
</title>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/style.css?v=<?= $versaoStyle ?>"
>


<link
    rel="stylesheet"
    href="<?= BASE_URL ?>/frontend/assets/css/aula.css?v=<?= $versaoAulaCss ?>"
>

</head>

<body>


<?php

require_once __DIR__ .
    "/../../componentes/header.php";

?>


<main class="aula-container">


<!--
|--------------------------------------------------------------------------
| CABEÇALHO
|--------------------------------------------------------------------------
-->

<section class="aula-cabecalho">

    <span class="aula-numero">

        AULA 27

    </span>

    <h1>

        Interfaces em Java

    </h1>

    <p>

        Aprenda como criar contratos que definem
        comportamentos que diferentes classes deverão
        implementar dentro de um programa Java.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTRODUÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que é uma interface?

    </h2>

    <p>

        Uma <strong>interface</strong> é uma estrutura
        utilizada para definir comportamentos que uma
        classe deverá oferecer.

    </p>

    <p>

        Podemos imaginar uma interface como um
        <strong>contrato</strong>.

    </p>

    <p>

        Quando uma classe decide implementar uma
        interface, ela assume a responsabilidade
        de cumprir esse contrato.

    </p>

    <p>

        A interface normalmente define
        <strong>quais métodos devem existir</strong>,
        enquanto cada classe decide como esses métodos
        funcionarão.

    </p>

    <div class="aula-destaque">

        <strong>

            Forma simples de entender:

        </strong>

        <p>

            A interface define o que deve ser feito.

        </p>

        <p>

            A classe define como aquilo será feito.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO DO COTIDIANO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Um exemplo do cotidiano

    </h2>

    <p>

        Imagine diferentes dispositivos eletrônicos,
        como uma televisão, um computador e uma cafeteira.

    </p>

    <p>

        Todos podem possuir a capacidade de serem ligados,
        mas cada dispositivo realiza essa ação de uma forma
        diferente.

    </p>

    <p>

        Podemos representar essa capacidade por meio
        de uma interface chamada
        <strong>Ligavel</strong>.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Classe</span>
            <span>Contrato</span>
            <span>Comportamento</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Televisao</span>
            <span>ligar()</span>
            <span>Liga a tela</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Computador</span>
            <span>ligar()</span>
            <span>Inicia o sistema</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Cafeteira</span>
            <span>ligar()</span>
            <span>Inicia o preparo</span>

        </div>

    </div>

    <p>

        Todas as classes oferecem o método ligar(),
        mas cada uma possui sua própria implementação.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CRIANDO UMA INTERFACE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Criando uma interface

    </h2>

    <p>

        Para declarar uma interface em Java, utilizamos
        a palavra-chave <strong>interface</strong>.

    </p>

    <h3>

        Arquivo Ligavel.java

    </h3>

    <pre class="codigo-java"><code>public interface Ligavel {

    void ligar();

}</code></pre>

    <p>

        A interface Ligavel define que deve existir
        um método chamado ligar().

    </p>

    <p>

        Esse método não possui corpo porque a interface
        está apenas definindo o contrato.

    </p>

    <div class="aula-destaque">

        <strong>

            Dentro de uma interface:

        </strong>

        <p>

            Um método declarado sem corpo é
            implicitamente público e abstrato.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODOS DA INTERFACE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos públicos e abstratos

    </h2>

    <p>

        Este método:

    </p>

    <pre class="codigo-java"><code>void ligar();</code></pre>

    <p>

        pode ser entendido como:

    </p>

    <pre class="codigo-java"><code>public abstract void ligar();</code></pre>

    <p>

        As palavras public e abstract podem ser
        omitidas porque o Java já considera essas
        características automaticamente.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Palavra</span>
            <span>Significado</span>
            <span>Aplicação</span>

        </div>

        <div class="aula-tabela-linha">

            <span>public</span>

            <span>
                O método pode ser acessado externamente
            </span>

            <span>
                Contrato público
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>abstract</span>

            <span>
                O método ainda não possui implementação
            </span>

            <span>
                A classe implementadora cria o corpo
            </span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| IMPLEMENTS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Implementando uma interface

    </h2>

    <p>

        Para indicar que uma classe seguirá uma
        interface, utilizamos a palavra-chave
        <strong>implements</strong>.

    </p>

    <h3>

        Arquivo Televisao.java

    </h3>

    <pre class="codigo-java"><code>public class Televisao
        implements Ligavel {

    @Override
    public void ligar() {

        System.out.println(
            "A televisão foi ligada."
        );

    }

}</code></pre>

    <p>

        A classe Televisao implementa a interface Ligavel.

    </p>

    <p>

        Por isso, ela precisa fornecer uma implementação
        pública para o método ligar().

    </p>

    <div class="aula-destaque">

        <strong>

            A palavra implements significa:

        </strong>

        <p>

            Esta classe assume o contrato definido
            pela interface.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| PUBLIC NA IMPLEMENTAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Por que o método precisa ser public?

    </h2>

    <p>

        Os métodos abstratos de uma interface são
        considerados públicos.

    </p>

    <p>

        Por isso, a implementação também precisa
        utilizar public.

    </p>

    <p>

        Esta implementação está correta:

    </p>

    <pre class="codigo-java"><code>@Override
public void ligar() {

    System.out.println(
        "O dispositivo foi ligado."
    );

}</code></pre>

    <p>

        Esta implementação está incorreta:

    </p>

    <pre class="codigo-java"><code>@Override
void ligar() {

    System.out.println(
        "O dispositivo foi ligado."
    );

}</code></pre>

    <p>

        Sem public, o método possuiria um nível
        de acesso mais restrito do que o definido
        pela interface.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| OVERRIDE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        A anotação @Override

    </h2>

    <p>

        A anotação <strong>@Override</strong> informa
        que estamos implementando ou sobrescrevendo
        um método definido anteriormente.

    </p>

    <pre class="codigo-java"><code>@Override
public void ligar() {

    System.out.println(
        "A televisão foi ligada."
    );

}</code></pre>

    <p>

        A anotação ajuda o compilador a verificar
        se o método corresponde corretamente ao
        contrato da interface.

    </p>

    <p>

        Também deixa a intenção do código mais clara.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| DIFERENTES IMPLEMENTAÇÕES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Diferentes classes, diferentes implementações

    </h2>

    <p>

        Várias classes podem implementar a mesma
        interface.

    </p>

    <h3>

        Arquivo Computador.java

    </h3>

    <pre class="codigo-java"><code>public class Computador
        implements Ligavel {

    @Override
    public void ligar() {

        System.out.println(
            "O computador está iniciando."
        );

    }

}</code></pre>

    <h3>

        Arquivo Cafeteira.java

    </h3>

    <pre class="codigo-java"><code>public class Cafeteira
        implements Ligavel {

    @Override
    public void ligar() {

        System.out.println(
            "A cafeteira iniciou o preparo."
        );

    }

}</code></pre>

    <p>

        As duas classes cumprem o mesmo contrato,
        mas executam ações diferentes.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| EXEMPLO COMPLETO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo completo

    </h2>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Televisao televisao =
            new Televisao();

        Computador computador =
            new Computador();

        Cafeteira cafeteira =
            new Cafeteira();


        televisao.ligar();

        computador.ligar();

        cafeteira.ligar();

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>A televisão foi ligada.
O computador está iniciando.
A cafeteira iniciou o preparo.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| INTERFACE E POLIMORFISMO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Interfaces e polimorfismo

    </h2>

    <p>

        Uma interface também pode ser utilizada
        como tipo de uma variável.

    </p>

    <pre class="codigo-java"><code>Ligavel dispositivo1 =
    new Televisao();

Ligavel dispositivo2 =
    new Computador();

Ligavel dispositivo3 =
    new Cafeteira();</code></pre>

    <p>

        As variáveis possuem o tipo Ligavel,
        mas armazenam objetos de classes diferentes.

    </p>

    <p>

        Podemos chamar o mesmo método:

    </p>

    <pre class="codigo-java"><code>dispositivo1.ligar();

dispositivo2.ligar();

dispositivo3.ligar();</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>A televisão foi ligada.
O computador está iniciando.
A cafeteira iniciou o preparo.</code></div>

    <div class="aula-destaque">

        <strong>

            Interface e polimorfismo:

        </strong>

        <p>

            O código utiliza um contrato comum,
            enquanto cada objeto mantém sua própria
            forma de executar o comportamento.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| INTERFACE NÃO É INSTANCIADA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Uma interface pode criar objetos?

    </h2>

    <p>

        Uma interface não pode ser instanciada
        diretamente.

    </p>

    <p>

        Esta criação não é permitida:

    </p>

    <pre class="codigo-java"><code>Ligavel dispositivo =
    new Ligavel();</code></pre>

    <p>

        A interface define um contrato, mas não
        representa uma implementação completa.

    </p>

    <p>

        Precisamos criar um objeto de uma classe
        que implemente a interface.

    </p>

    <pre class="codigo-java"><code>Ligavel dispositivo =
    new Televisao();</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| ARRAY DE INTERFACES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Array com objetos diferentes

    </h2>

    <p>

        Podemos criar um array do tipo da interface
        e armazenar objetos de diferentes classes.

    </p>

    <pre class="codigo-java"><code>Ligavel[] dispositivos = {

    new Televisao(),

    new Computador(),

    new Cafeteira()

};</code></pre>

    <p>

        Depois, podemos percorrer o array com for-each.

    </p>

    <pre class="codigo-java"><code>for (
    Ligavel dispositivo
    : dispositivos
) {

    dispositivo.ligar();

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>A televisão foi ligada.
O computador está iniciando.
A cafeteira iniciou o preparo.</code></div>

    <p>

        O laço utiliza apenas o contrato Ligavel.

    </p>

    <p>

        Cada objeto executa sua própria implementação.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODO RECEBENDO INTERFACE
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Recebendo uma interface como parâmetro

    </h2>

    <p>

        Um método também pode receber uma interface
        como parâmetro.

    </p>

    <pre class="codigo-java"><code>public static void iniciar(
    Ligavel dispositivo
) {

    dispositivo.ligar();

}</code></pre>

    <p>

        Podemos enviar qualquer objeto que implemente
        Ligavel.

    </p>

    <pre class="codigo-java"><code>iniciar(
    new Televisao()
);

iniciar(
    new Computador()
);

iniciar(
    new Cafeteira()
);</code></pre>

    <p>

        O método iniciar() não precisa conhecer
        os detalhes de cada classe.

    </p>

    <p>

        Ele precisa apenas saber que recebeu algo
        capaz de executar ligar().

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| VÁRIOS MÉTODOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Uma interface pode definir vários métodos

    </h2>

    <p>

        Uma interface pode exigir mais de um
        comportamento.

    </p>

    <h3>

        Arquivo Dispositivo.java

    </h3>

    <pre class="codigo-java"><code>public interface Dispositivo {

    void ligar();

    void desligar();

    void reiniciar();

}</code></pre>

    <p>

        Uma classe concreta deverá implementar
        todos esses métodos.

    </p>

    <h3>

        Arquivo Computador.java

    </h3>

    <pre class="codigo-java"><code>public class Computador
        implements Dispositivo {

    @Override
    public void ligar() {

        System.out.println(
            "Computador ligado."
        );

    }


    @Override
    public void desligar() {

        System.out.println(
            "Computador desligado."
        );

    }


    @Override
    public void reiniciar() {

        System.out.println(
            "Computador reiniciado."
        );

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| NÃO IMPLEMENTAR MÉTODO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        O que acontece se faltar um método?

    </h2>

    <p>

        Uma classe concreta precisa implementar
        todos os métodos abstratos exigidos pelas
        interfaces.

    </p>

    <p>

        Considere esta interface:

    </p>

    <pre class="codigo-java"><code>public interface Dispositivo {

    void ligar();

    void desligar();

}</code></pre>

    <p>

        Esta classe está incompleta:

    </p>

    <pre class="codigo-java"><code>public class Computador
        implements Dispositivo {

    @Override
    public void ligar() {

        System.out.println(
            "Computador ligado."
        );

    }

}</code></pre>

    <p>

        O método desligar() não foi implementado.

    </p>

    <p>

        Portanto, a classe causará erro de compilação,
        a menos que seja declarada como abstrata.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MÚLTIPLAS INTERFACES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Implementando várias interfaces

    </h2>

    <p>

        Uma classe Java pode implementar mais
        de uma interface.

    </p>

    <p>

        As interfaces são separadas por vírgulas.

    </p>

    <h3>

        Arquivo EmitidorSom.java

    </h3>

    <pre class="codigo-java"><code>public interface EmitidorSom {

    void emitirSom();

}</code></pre>

    <h3>

        Arquivo Movimentavel.java

    </h3>

    <pre class="codigo-java"><code>public interface Movimentavel {

    void mover();

}</code></pre>

    <h3>

        Arquivo Cachorro.java

    </h3>

    <pre class="codigo-java"><code>public class Cachorro
        implements EmitidorSom,
                   Movimentavel {

    @Override
    public void emitirSom() {

        System.out.println(
            "Au au!"
        );

    }


    @Override
    public void mover() {

        System.out.println(
            "O cachorro está correndo."
        );

    }

}</code></pre>

    <p>

        A classe Cachorro precisa cumprir os
        contratos das duas interfaces.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| CLASSE E INTERFACES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Herança e interfaces juntas

    </h2>

    <p>

        Uma classe pode estender uma classe e,
        ao mesmo tempo, implementar interfaces.

    </p>

    <pre class="codigo-java"><code>public class Animal {

    private String nome;

}</code></pre>

    <pre class="codigo-java"><code>public interface Movimentavel {

    void mover();

}</code></pre>

    <pre class="codigo-java"><code>public interface EmitidorSom {

    void emitirSom();

}</code></pre>

    <pre class="codigo-java"><code>public class Cachorro
        extends Animal
        implements Movimentavel,
                   EmitidorSom {

    @Override
    public void mover() {

        System.out.println(
            "O cachorro está correndo."
        );

    }


    @Override
    public void emitirSom() {

        System.out.println(
            "Au au!"
        );

    }

}</code></pre>

    <div class="aula-destaque">

        <strong>

            Ordem da declaração:

        </strong>

        <p>

            Primeiro utilizamos extends para a classe pai.

        </p>

        <p>

            Depois utilizamos implements para as interfaces.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| INTERFACE X HERANÇA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Herança ou interface?

    </h2>

    <p>

        Herança entre classes e implementação de
        interfaces possuem objetivos relacionados,
        mas não são iguais.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Característica</span>
            <span>Classe pai</span>
            <span>Interface</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Palavra utilizada</span>
            <span>extends</span>
            <span>implements</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Relação comum</span>
            <span>“É um”</span>
            <span>“É capaz de”</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Quantidade</span>
            <span>Uma classe pai direta</span>
            <span>Várias interfaces</span>

        </div>

        <div class="aula-tabela-linha">

            <span>Estado do objeto</span>

            <span>
                Pode possuir atributos de instância
            </span>

            <span>
                Não possui atributos de instância
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>Objetivo</span>
            <span>Reutilizar e especializar</span>
            <span>Definir capacidades e contratos</span>

        </div>

    </div>

    <p>

        Um cachorro <strong>é um</strong> animal.

    </p>

    <p>

        Um cachorro <strong>é capaz de</strong>
        se movimentar e emitir sons.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| INTERFACE X CLASSE ABSTRATA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Interface ou classe abstrata?

    </h2>

    <p>

        Uma classe abstrata pode armazenar atributos
        de instância e fornecer comportamentos comuns.

    </p>

    <p>

        Uma interface é especialmente útil para
        representar capacidades que podem aparecer
        em classes diferentes.

    </p>

    <div class="aula-tabela">

        <div class="aula-tabela-linha aula-tabela-cabecalho">

            <span>Situação</span>
            <span>Estrutura indicada</span>
            <span>Exemplo</span>

        </div>

        <div class="aula-tabela-linha">

            <span>
                Compartilhar estado e comportamento
            </span>

            <span>
                Classe pai ou abstrata
            </span>

            <span>
                Animal
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>
                Definir uma capacidade
            </span>

            <span>
                Interface
            </span>

            <span>
                Movimentavel
            </span>

        </div>

        <div class="aula-tabela-linha">

            <span>
                Exigir um contrato comum
            </span>

            <span>
                Interface
            </span>

            <span>
                Pagamento
            </span>

        </div>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODOS DEFAULT
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos default

    </h2>

    <p>

        Uma interface também pode fornecer uma
        implementação pronta utilizando a palavra
        <strong>default</strong>.

    </p>

    <h3>

        Arquivo Notificavel.java

    </h3>

    <pre class="codigo-java"><code>public interface Notificavel {

    void enviarNotificacao();


    default void registrarEnvio() {

        System.out.println(
            "Envio registrado no sistema."
        );

    }

}</code></pre>

    <p>

        A classe precisa implementar enviarNotificacao(),
        mas pode utilizar registrarEnvio() diretamente.

    </p>

    <pre class="codigo-java"><code>public class Email
        implements Notificavel {

    @Override
    public void enviarNotificacao() {

        System.out.println(
            "E-mail enviado."
        );

    }

}</code></pre>

    <p>

        Agora podemos executar:

    </p>

    <pre class="codigo-java"><code>Email email = new Email();

email.enviarNotificacao();

email.registrarEnvio();</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>E-mail enviado.
Envio registrado no sistema.</code></div>

    <p>

        A classe também pode sobrescrever o método
        default quando precisar de outro comportamento.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| MÉTODOS ESTÁTICOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Métodos estáticos em interfaces

    </h2>

    <p>

        Uma interface pode possuir métodos estáticos.

    </p>

    <pre class="codigo-java"><code>public interface Calculadora {

    static double calcularDesconto(
        double valor,
        double percentual
    ) {

        return valor
            - valor * percentual / 100;

    }

}</code></pre>

    <p>

        O método é chamado pelo nome da interface.

    </p>

    <pre class="codigo-java"><code>double resultado =
    Calculadora.calcularDesconto(
        100,
        10
    );

System.out.println(resultado);</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>90.0</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| CONSTANTES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Constantes em interfaces

    </h2>

    <p>

        Os campos declarados em uma interface são
        considerados automaticamente:

    </p>

    <div class="resultado-codigo"><code>public static final</code></div>

    <p>

        Isso significa que representam constantes.

    </p>

    <pre class="codigo-java"><code>public interface Configuracao {

    int LIMITE_TENTATIVAS = 3;

}</code></pre>

    <p>

        Podemos acessar o valor desta forma:

    </p>

    <pre class="codigo-java"><code>System.out.println(
    Configuracao.LIMITE_TENTATIVAS
);</code></pre>

    <div class="aula-destaque">

        <strong>

            Convenção:

        </strong>

        <p>

            Nomes de constantes normalmente são escritos
            com letras maiúsculas e palavras separadas
            por sublinhado.

        </p>

    </div>

</section>


<!--
|--------------------------------------------------------------------------
| HERANÇA ENTRE INTERFACES
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Uma interface pode herdar de outra

    </h2>

    <p>

        Interfaces utilizam <strong>extends</strong>
        para herdar contratos de outras interfaces.

    </p>

    <pre class="codigo-java"><code>public interface Ligavel {

    void ligar();

}</code></pre>

    <pre class="codigo-java"><code>public interface Desligavel {

    void desligar();

}</code></pre>

    <pre class="codigo-java"><code>public interface Dispositivo
        extends Ligavel,
                Desligavel {

    void reiniciar();

}</code></pre>

    <p>

        Uma interface pode estender mais de uma
        interface.

    </p>

    <p>

        Uma classe que implementar Dispositivo
        deverá fornecer:

    </p>

    <ul>

        <li>
            ligar();
        </li>

        <li>
            desligar();
        </li>

        <li>
            reiniciar().
        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| PAGAMENTO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Exemplo prático: sistema de pagamentos

    </h2>

    <p>

        Vamos criar um contrato para diferentes
        formas de pagamento.

    </p>

    <h3>

        Arquivo Pagamento.java

    </h3>

    <pre class="codigo-java"><code>public interface Pagamento {

    boolean processar(double valor);

    String getTipo();

}</code></pre>

    <p>

        Toda forma de pagamento precisará informar
        seu tipo e processar um valor.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PAGAMENTO PIX
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Pagamento por Pix

    </h2>

    <h3>

        Arquivo PagamentoPix.java

    </h3>

    <pre class="codigo-java"><code>public class PagamentoPix
        implements Pagamento {

    @Override
    public boolean processar(
        double valor
    ) {

        if (valor &gt; 0) {

            System.out.println(
                "Pix de R$ "
                + valor
                + " realizado."
            );

            return true;

        }

        return false;

    }


    @Override
    public String getTipo() {

        return "Pix";

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| PAGAMENTO CARTÃO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Pagamento com cartão

    </h2>

    <h3>

        Arquivo PagamentoCartao.java

    </h3>

    <pre class="codigo-java"><code>public class PagamentoCartao
        implements Pagamento {

    @Override
    public boolean processar(
        double valor
    ) {

        if (valor &gt; 0) {

            System.out.println(
                "Pagamento de R$ "
                + valor
                + " realizado no cartão."
            );

            return true;

        }

        return false;

    }


    @Override
    public String getTipo() {

        return "Cartão";

    }

}</code></pre>

</section>


<!--
|--------------------------------------------------------------------------
| UTILIZANDO PAGAMENTOS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Utilizando diferentes pagamentos

    </h2>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Pagamento[] pagamentos = {

            new PagamentoPix(),

            new PagamentoCartao()

        };


        for (
            Pagamento pagamento
            : pagamentos
        ) {

            System.out.println(
                "Forma: "
                + pagamento.getTipo()
            );

            boolean realizado =
                pagamento.processar(100.00);

            System.out.println(
                "Realizado: "
                + realizado
            );

            System.out.println();

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>Forma: Pix
Pix de R$ 100.0 realizado.
Realizado: true

Forma: Cartão
Pagamento de R$ 100.0 realizado no cartão.
Realizado: true</code></div>

    <p>

        O programa trabalha com o contrato Pagamento.

    </p>

    <p>

        Cada classe decide como processará o valor.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| VANTAGENS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Vantagens das interfaces

    </h2>

    <ul>

        <li>

            <strong>Organização:</strong>
            definem contratos claros;

        </li>

        <li>

            <strong>Flexibilidade:</strong>
            diferentes classes podem cumprir
            o mesmo contrato;

        </li>

        <li>

            <strong>Polimorfismo:</strong>
            objetos diferentes podem ser tratados
            por uma referência comum;

        </li>

        <li>

            <strong>Extensibilidade:</strong>
            novas implementações podem ser adicionadas;

        </li>

        <li>

            <strong>Manutenção:</strong>
            cada classe mantém sua própria implementação;

        </li>

        <li>

            <strong>Desacoplamento:</strong>
            o código pode depender de um contrato,
            em vez de depender de uma classe específica;

        </li>

        <li>

            <strong>Múltiplas capacidades:</strong>
            uma classe pode implementar várias interfaces.

        </li>

    </ul>

</section>


<!--
|--------------------------------------------------------------------------
| ERROS COMUNS
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Erros comuns

    </h2>


    <h3>

        Utilizar extends em vez de implements

    </h3>

    <p>

        Uma classe implementa uma interface.

    </p>

    <p>

        Este código está incorreto:

    </p>

    <pre class="codigo-java"><code>public class Televisao
        extends Ligavel {

}</code></pre>

    <p>

        O correto é:

    </p>

    <pre class="codigo-java"><code>public class Televisao
        implements Ligavel {

}</code></pre>


    <h3>

        Esquecer de implementar um método

    </h3>

    <p>

        Uma classe concreta precisa implementar
        todos os métodos abstratos exigidos.

    </p>


    <h3>

        Esquecer o public

    </h3>

    <p>

        A implementação de um método da interface
        precisa ser pública.

    </p>

    <pre class="codigo-java"><code>@Override
public void ligar() {

}</code></pre>


    <h3>

        Alterar a assinatura do método

    </h3>

    <p>

        Se a interface define:

    </p>

    <pre class="codigo-java"><code>void pagar(double valor);</code></pre>

    <p>

        A classe não pode implementar apenas:

    </p>

    <pre class="codigo-java"><code>public void pagar() {

}</code></pre>

    <p>

        Os parâmetros não correspondem ao contrato.

    </p>


    <h3>

        Tentar criar um objeto da interface

    </h3>

    <pre class="codigo-java"><code>Pagamento pagamento =
    new Pagamento();</code></pre>

    <p>

        Interfaces não podem ser instanciadas
        diretamente.

    </p>


    <h3>

        Confundir interface com classe pai

    </h3>

    <p>

        Uma interface define principalmente capacidades
        e contratos.

    </p>

    <p>

        Uma classe pai pode armazenar estado e fornecer
        uma implementação compartilhada.

    </p>


    <h3>

        Criar interfaces sem propósito claro

    </h3>

    <p>

        Uma interface deve representar um contrato
        útil para uma ou mais classes.

    </p>

    <p>

        Criar interfaces desnecessárias pode tornar
        o sistema mais difícil de compreender.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| PRÁTICA GUIADA
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Prática guiada

    </h2>

    <p>

        Vamos criar diferentes tipos de notificações.

    </p>

    <h3>

        Arquivo Notificavel.java

    </h3>

    <pre class="codigo-java"><code>public interface Notificavel {

    void enviar(
        String destinatario,
        String mensagem
    );

}</code></pre>

    <h3>

        Arquivo NotificacaoEmail.java

    </h3>

    <pre class="codigo-java"><code>public class NotificacaoEmail
        implements Notificavel {

    @Override
    public void enviar(
        String destinatario,
        String mensagem
    ) {

        System.out.println(
            "E-mail enviado para "
            + destinatario
            + ": "
            + mensagem
        );

    }

}</code></pre>

    <h3>

        Arquivo NotificacaoSMS.java

    </h3>

    <pre class="codigo-java"><code>public class NotificacaoSMS
        implements Notificavel {

    @Override
    public void enviar(
        String destinatario,
        String mensagem
    ) {

        System.out.println(
            "SMS enviado para "
            + destinatario
            + ": "
            + mensagem
        );

    }

}</code></pre>

    <h3>

        Arquivo Main.java

    </h3>

    <pre class="codigo-java"><code>public class Main {

    public static void main(String[] args) {

        Notificavel[] notificacoes = {

            new NotificacaoEmail(),

            new NotificacaoSMS()

        };


        for (
            Notificavel notificacao
            : notificacoes
        ) {

            notificacao.enviar(
                "Robert",
                "Sua atividade foi corrigida."
            );

        }

    }

}</code></pre>

    <p>

        Resultado:

    </p>

    <div class="resultado-codigo"><code>E-mail enviado para Robert: Sua atividade foi corrigida.
SMS enviado para Robert: Sua atividade foi corrigida.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| DESAFIO
|--------------------------------------------------------------------------
-->

<section class="aula-secao">

    <h2>

        Desafio da aula

    </h2>

    <p>

        Crie uma interface chamada
        <strong>Reprodutivel</strong> com os métodos:

    </p>

    <ul>

        <li>
            reproduzir();
        </li>

        <li>
            pausar();
        </li>

        <li>
            parar().
        </li>

    </ul>

    <p>

        Depois, crie as classes:

    </p>

    <ul>

        <li>
            Musica;
        </li>

        <li>
            Podcast;
        </li>

        <li>
            Video.
        </li>

    </ul>

    <p>

        Todas deverão implementar Reprodutivel
        e apresentar comportamentos próprios.

    </p>

    <p>

        Por fim, coloque os objetos em um array
        do tipo Reprodutivel e percorra esse array.

    </p>

    <div class="resultado-codigo"><code>Reproduzindo música.
Música pausada.
Música encerrada.

Reproduzindo podcast.
Podcast pausado.
Podcast encerrado.

Reproduzindo vídeo.
Vídeo pausado.
Vídeo encerrado.</code></div>

</section>


<!--
|--------------------------------------------------------------------------
| RESUMO
|--------------------------------------------------------------------------
-->

<section class="aula-resumo">

    <h2>

        Resumindo a aula

    </h2>

    <p>

        Nesta aula, aprendemos como utilizar
        interfaces em Java.

    </p>

    <ul>

        <li>
            uma interface funciona como um contrato;
        </li>

        <li>
            interface é a palavra utilizada para
            declarar uma interface;
        </li>

        <li>
            implements conecta uma classe a uma
            interface;
        </li>

        <li>
            métodos abstratos de interfaces são
            públicos;
        </li>

        <li>
            a implementação precisa utilizar public;
        </li>

        <li>
            @Override ajuda a verificar a implementação;
        </li>

        <li>
            uma classe concreta deve cumprir todos
            os métodos abstratos do contrato;
        </li>

        <li>
            interfaces não podem ser instanciadas
            diretamente;
        </li>

        <li>
            uma interface pode ser utilizada como
            tipo de uma referência;
        </li>

        <li>
            interfaces permitem aplicar polimorfismo;
        </li>

        <li>
            arrays podem armazenar diferentes objetos
            que seguem a mesma interface;
        </li>

        <li>
            métodos podem receber interfaces como
            parâmetros;
        </li>

        <li>
            uma classe pode implementar várias
            interfaces;
        </li>

        <li>
            uma classe pode estender uma classe e
            implementar interfaces;
        </li>

        <li>
            interfaces podem possuir métodos default
            e estáticos;
        </li>

        <li>
            campos de interfaces representam constantes;
        </li>

        <li>
            uma interface pode estender outras interfaces;
        </li>

        <li>
            interfaces ajudam a reduzir a dependência
            entre partes do sistema.
        </li>

    </ul>

    <div class="aula-destaque">

        <p>

            A principal ideia desta aula é:

        </p>

        <p>

            <strong>

                A interface define um contrato comum,
                enquanto cada classe fornece sua própria
                maneira de cumprir esse contrato.

            </strong>

        </p>

    </div>

    <p>

        Na próxima aula, aprenderemos como o Java
        representa e trata situações inesperadas
        durante a execução dos programas.

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| FRASE FINAL
|--------------------------------------------------------------------------
-->

<section class="aula-frase">

    <p>

        "Uma interface define o compromisso.
        Cada classe define sua forma de cumpri-lo."

    </p>

</section>


<!--
|--------------------------------------------------------------------------
| NAVEGAÇÃO
|--------------------------------------------------------------------------
-->

<section class="aula-acoes">


    <a
        href="<?= aulaJavaEscapar(
            $linkConteudos
        ) ?>"
        class="aula-botao"
    >

        ← Voltar para conteúdos

    </a>


    <a
        href="<?= aulaJavaEscapar(
            $linkAulaAnterior
        ) ?>"
        class="aula-botao"
    >

        ← Aula anterior

    </a>


    <a
        href="<?= aulaJavaEscapar(
            $linkProximaAula
        ) ?>"
        class="aula-botao"
    >

        Próxima aula: Exceções →

    </a>


</section>


</main>


<?php

require_once __DIR__ .
    "/../../componentes/footer.php";

?>


<script
    src="<?= BASE_URL ?>/frontend/assets/js/theme.js?v=<?= $versaoThemeJs ?>"
></script>


</body>

</html>