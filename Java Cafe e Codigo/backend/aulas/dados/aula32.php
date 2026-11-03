<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 32,

    "titulo" =>
        "Montando textos sem complicação",

    "assunto" =>
        "StringBuilder",

    "descricao" =>
        "Aprenda a construir e modificar textos em várias etapas utilizando StringBuilder, append, insert, replace e toString.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        33,


    /*
    |--------------------------------------------------------------------------
    | PRIMEIRA INTERAÇÃO
    |--------------------------------------------------------------------------
    */

    "inicio" =>
        "pergunta_1",


    /*
    |--------------------------------------------------------------------------
    | INTERAÇÕES
    |--------------------------------------------------------------------------
    */

    "interacoes" => [

        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1
        |--------------------------------------------------------------------------
        */

        "pergunta_1" => [

            "tipo" =>
                "pergunta",

            "titulo" =>
                "Construindo um texto por partes...",

            "mensagem" =>
                "O método append acrescenta um novo conteúdo ao final do StringBuilder.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        StringBuilder texto =
            new StringBuilder();

        texto.append("Java");

        texto.append(" ");

        texto.append("Café");

        System.out.println(
            texto.toString()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual texto aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java Café",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Café",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_substitui"
                ],

                [
                    "texto" =>
                        "JavaCafé",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_espaco"
                ],

                [
                    "texto" =>
                        "Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ultimo"
                ],

                [
                    "texto" =>
                        "Não sei como append funciona",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE APPEND SUBSTITUI
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_substitui" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "append não apaga o que já estava ali, po. Ele pega o conteúdo novo e coloca no final. Primeiro fica Java, depois entra o espaço e depois Café.",

            "natanael" =>
                "O método append preserva o conteúdo existente e acrescenta o novo valor ao final. Cada chamada aumenta o texto que está sendo construído.",

            "pergunta" =>
                "Depois de texto.append(\"Java\"), qual conteúdo está guardado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Nenhum conteúdo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_substitui"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ESQUECEU O ESPAÇO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_espaco" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Tem um append só pro espaço entre as palavras. Ele parece pequeno, mas também entra no texto kkkkk.",

            "natanael" =>
                "A segunda chamada adiciona uma String contendo um espaço. Esse caractere permanece entre Java e Café quando o conteúdo final é apresentado.",

            "codigo" => <<<'JAVA'
texto.append("Java");
texto.append(" ");
texto.append("Café");
JAVA,

            "pergunta" =>
                "O segundo append adiciona qual conteúdo?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um espaço",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Nenhum caractere",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_espaco"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONSIDEROU APENAS O PRIMEIRO APPEND
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_ultimo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Depois de colocar Java, o código ainda chama append mais duas vezes. O StringBuilder continua sendo o mesmo e recebe os próximos conteúdos.",

            "natanael" =>
                "A primeira chamada representa apenas o começo da construção. As instruções seguintes continuam modificando o mesmo objeto StringBuilder.",

            "pergunta" =>
                "Quantas chamadas de append aparecem no código?",

            "opcoes" => [

                [
                    "texto" =>
                        "Três",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Uma",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ultimo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — NÃO SEI
        |--------------------------------------------------------------------------
        */

        "pergunta_1_nao_sei" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Pensa numa frase sendo escrita aos poucos. Tu escreve Java, depois coloca um espaço e depois escreve Café. append serve pra continuar escrevendo no final.",

            "natanael" =>
                "Imagine um quadro em que cada nova informação é colocada depois da anterior. O StringBuilder mantém tudo que já foi escrito e recebe novas partes com append.",

            "pergunta" =>
                "append adiciona o conteúdo no começo ou no final?",

            "opcoes" => [

                [
                    "texto" =>
                        "No final",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Sempre no começo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — DICA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_dica" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "O mesmo objeto recebe três partes: Java, um espaço e Café. No final, toString entrega o texto completo.",

            "natanael" =>
                "As chamadas são executadas em ordem e acumulam seus conteúdos. O método toString produz uma String com o estado atual do construtor de texto.",

            "pergunta" =>
                "Qual é o conteúdo final?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java Café",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "JavaCafé",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_espaco"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2
        |--------------------------------------------------------------------------
        */

        "pergunta_2" => [

            "tipo" =>
                "pergunta",

            "titulo" =>
                "Encadeando várias chamadas...",

            "mensagem" =>
                "append devolve o próprio StringBuilder, permitindo chamar outro append logo em seguida.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        StringBuilder mensagem =
            new StringBuilder("Olá");

        mensagem
            .append(", ")
            .append("Ana")
            .append("!");

        System.out.println(
            mensagem
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Olá, Ana!",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Olá",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_encadeamento"
                ],

                [
                    "texto" =>
                        ", Ana!",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_inicial"
                ],

                [
                    "texto" =>
                        "OláAna!",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_separacao"
                ],

                [
                    "texto" =>
                        "O código não funciona porque existem vários append",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_compilacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O ENCADEAMENTO NÃO EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_encadeamento" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Cada append dessa corrente executa normalmente. O primeiro coloca vírgula e espaço, o segundo coloca Ana e o terceiro coloca a exclamação.",

            "natanael" =>
                "O resultado de cada chamada continua sendo o próprio StringBuilder. Isso permite utilizar imediatamente o próximo método sobre o mesmo objeto.",

            "pergunta" =>
                "Quantas chamadas de append aparecem no encadeamento?",

            "opcoes" => [

                [
                    "texto" =>
                        "Três",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Uma",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_encadeamento"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ESQUECEU O CONTEÚDO INICIAL
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_inicial" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O StringBuilder já nasce com Olá. Os append não apagam isso. Eles continuam escrevendo depois desse conteúdo inicial.",

            "natanael" =>
                "O construtor recebeu a String Olá. Portanto o objeto já possui esse conteúdo antes das chamadas de append.",

            "codigo" => <<<'JAVA'
new StringBuilder("Olá")
JAVA,

            "pergunta" =>
                "Qual texto existe antes do primeiro append?",

            "opcoes" => [

                [
                    "texto" =>
                        "Olá",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Nenhum texto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_inicial"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ESQUECEU A VÍRGULA E O ESPAÇO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_separacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O primeiro append coloca exatamente vírgula e espaço. Então Ana não fica grudada em Olá.",

            "natanael" =>
                "A primeira parte acrescentada é a String \", \". Ela contém dois caracteres importantes para separar corretamente a saudação do nome.",

            "pergunta" =>
                "Qual conteúdo é acrescentado antes de Ana?",

            "opcoes" => [

                [
                    "texto" =>
                        "Uma vírgula e um espaço",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Nenhum separador",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_separacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O ENCADEAMENTO NÃO COMPILA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_compilacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Funciona sim. append devolve o mesmo StringBuilder. Por isso dá pra terminar uma chamada e já começar outra com ponto.",

            "natanael" =>
                "O tipo retornado por append permite continuar utilizando os métodos de StringBuilder. Esse padrão recebe o nome de encadeamento de métodos.",

            "pergunta" =>
                "O que append devolve para permitir outra chamada?",

            "opcoes" => [

                [
                    "texto" =>
                        "O próprio StringBuilder",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Nenhum objeto utilizável",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_compilacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — DICA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_dica" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Começa com Olá. Depois entra vírgula e espaço, depois Ana e por último a exclamação.",

            "natanael" =>
                "O conteúdo inicial é preservado e cada chamada encadeada acrescenta uma nova parte na mesma ordem em que aparece no código.",

            "pergunta" =>
                "Qual mensagem completa é formada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Olá, Ana!",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "OláAna!",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_separacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3
        |--------------------------------------------------------------------------
        */

        "pergunta_3" => [

            "tipo" =>
                "pergunta",

            "titulo" =>
                "Substituindo uma parte do texto...",

            "mensagem" =>
                "StringBuilder também permite alterar diretamente um intervalo de caracteres.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        StringBuilder frase =
            new StringBuilder(
                "Java é legal"
            );

        frase.replace(
            7,
            12,
            "poderoso"
        );

        System.out.println(
            frase
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual texto aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java é poderoso",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Java é legal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_nao_altera"
                ],

                [
                    "texto" =>
                        "poderoso",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_frase"
                ],

                [
                    "texto" =>
                        "Java é poderosol",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_limite"
                ],

                [
                    "texto" =>
                        "Não sei quais caracteres são substituídos",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O STRINGBUILDER NÃO É ALTERADO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_nao_altera" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Diferente da String, o StringBuilder pode mudar o próprio conteúdo. replace altera o objeto frase diretamente.",

            "natanael" =>
                "StringBuilder é uma estrutura mutável. O método replace modifica o intervalo indicado no mesmo objeto, sem exigir uma nova variável para armazenar o resultado.",

            "pergunta" =>
                "StringBuilder é usado para textos que podem ser modificados?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Não, nunca pode mudar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_nao_altera"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE DEVOLVE SOMENTE A PALAVRA NOVA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_frase" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "replace não apaga a frase inteira. Ele troca só o intervalo escolhido. Java é continua ali, e legal vira poderoso.",

            "natanael" =>
                "Os caracteres que estão fora do intervalo permanecem no objeto. Apenas o trecho legal é retirado e substituído pela nova palavra.",

            "pergunta" =>
                "Qual parte da frase permanece antes da palavra substituída?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java é ",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Nenhuma parte",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_frase"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU O LIMITE FINAL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_limite" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O final 12 fica de fora, mas legal ocupa os índices 7 até 11. Então a palavra inteira é retirada, inclusive o último l.",

            "natanael" =>
                "O índice inicial é inclusivo e o índice final é exclusivo. O intervalo de 7 até 12 seleciona os índices 7, 8, 9, 10 e 11, que formam toda a palavra legal.",

            "codigo" => <<<'JAVA'
Índice 7  = l
Índice 8  = e
Índice 9  = g
Índice 10 = a
Índice 11 = l
Índice 12 = limite final
JAVA,

            "pergunta" =>
                "O caractere do índice 11 é substituído?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_limite"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — NÃO SEI
        |--------------------------------------------------------------------------
        */

        "pergunta_3_nao_sei" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Pensa no replace como selecionar uma parte num editor de texto. O código seleciona legal e digita poderoso por cima.",

            "natanael" =>
                "O primeiro índice marca onde a substituição começa. O segundo marca onde ela termina, sem incluir essa posição. O texto fornecido ocupa o lugar do intervalo removido.",

            "pergunta" =>
                "Qual palavra será retirada da frase?",

            "opcoes" => [

                [
                    "texto" =>
                        "legal",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_frase"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — DICA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_dica" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "O intervalo pega a palavra legal inteira. StringBuilder remove esse trecho e coloca poderoso no lugar.",

            "natanael" =>
                "A parte inicial Java é permanece preservada. O intervalo correspondente a legal é substituído, formando a nova frase.",

            "pergunta" =>
                "Qual é o conteúdo final do StringBuilder?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java é poderoso",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Java é legal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_nao_altera"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4
        |--------------------------------------------------------------------------
        */

        "pergunta_4" => [

            "tipo" =>
                "pergunta",

            "titulo" =>
                "Construindo uma lista dentro de uma repetição...",

            "mensagem" =>
                "StringBuilder é especialmente útil quando um texto precisa receber várias partes durante uma repetição.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        StringBuilder lista =
            new StringBuilder();

        for (
            int numero = 1;
            numero <= 3;
            numero++
        ) {

            lista.append(numero);

            if (numero < 3) {

                lista.append(" - ");

            }

        }

        String resultado =
            lista.toString();

        System.out.println(
            resultado
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual texto aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "1 - 2 - 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "1 - 2 - 3 - ",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_final"
                ],

                [
                    "texto" =>
                        "123",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_separador"
                ],

                [
                    "texto" =>
                        "0 - 1 - 2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_inicio"
                ],

                [
                    "texto" =>
                        "O resultado continua sendo um StringBuilder",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE SOBRA UM SEPARADOR NO FINAL
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_final" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Quando numero vale 3, a condição numero < 3 fica falsa. Então o último separador não é adicionado.",

            "natanael" =>
                "O separador é acrescentado somente quando ainda existe outro número depois do atual. Na última repetição, a condição impede a criação de um traço sobrando.",

            "pergunta" =>
                "Quando numero vale 3, a expressão 3 < 3 é verdadeira?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_final"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ESQUECEU O SEPARADOR
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_separador" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Nas duas primeiras voltas, numero é menor que 3. Então o if coloca espaço, traço e espaço depois dos números 1 e 2.",

            "natanael" =>
                "Depois de adicionar o primeiro e o segundo número, a condição é verdadeira. Por isso o separador também passa a fazer parte do conteúdo.",

            "pergunta" =>
                "Depois do número 1, o código adiciona qual texto?",

            "opcoes" => [

                [
                    "texto" =>
                        "\" - \"",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Nenhum separador",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_separador"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE COMEÇA EM ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_inicio" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O contador já começa em 1. O zero nem entra nessa repetição.",

            "natanael" =>
                "A inicialização do for é int numero = 1. Portanto o primeiro valor acrescentado ao StringBuilder é um.",

            "pergunta" =>
                "Qual é o valor inicial da variável numero?",

            "opcoes" => [

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_inicio"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUNDIU STRINGBUILDER COM STRING
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A variável lista é StringBuilder. Só que depois lista.toString() cria uma String e guarda ela em resultado.",

            "natanael" =>
                "O método toString produz uma representação do conteúdo no tipo String. Por isso a atribuição para String resultado é válida.",

            "codigo" => <<<'JAVA'
String resultado =
    lista.toString();
JAVA,

            "pergunta" =>
                "Qual é o tipo da variável resultado?",

            "opcoes" => [

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "StringBuilder",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — DICA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_dica" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Primeiro entra 1 e o separador. Depois entra 2 e outro separador. Por último entra 3, mas sem traço depois. Aí toString entrega tudo como texto.",

            "natanael" =>
                "A repetição produz três números. O separador aparece somente entre eles, evitando caracteres extras no final. Depois o conteúdo é convertido para String.",

            "pergunta" =>
                "Qual é o resultado completo?",

            "opcoes" => [

                [
                    "texto" =>
                        "1 - 2 - 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "1 - 2 - 3 - ",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_final"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | FINAL
        |--------------------------------------------------------------------------
        */

        "final" => [

            "tipo" =>
                "final",

            "resultado" =>
                "acerto",

            "titulo" =>
                "StringBuilder desbloqueado!",

            "robert" =>
                "Boa po kkkkk. StringBuilder serve pra montar e modificar texto sem ficar criando uma nova String em cada pequena alteração. append coloca no final, dá pra encadear várias chamadas, replace troca um intervalo e toString entrega o resultado como String. Ele é muito útil em loops, relatórios, listas e textos que crescem aos poucos.",

            "natanael" =>
                "Concluímos o funcionamento básico de StringBuilder. Diferentemente de String, ele permite modificar o mesmo objeto durante a construção do texto. Utilizamos append para acrescentar partes, encadeamos métodos, substituímos intervalos com replace e convertemos o resultado com toString. Essa estrutura é especialmente adequada quando realizamos várias concatenações ou alterações sucessivas. Na próxima aula vamos conhecer recursos matemáticos do Java e aprender a gerar valores aleatórios.",

            "mensagem" =>
                "Tu concluiu a trigésima segunda Aula Conversada."

        ]

    ]

];