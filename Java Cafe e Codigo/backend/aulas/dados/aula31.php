<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 31,

    "titulo" =>
        "Trabalhando melhor com textos",

    "assunto" =>
        "Métodos de String",

    "descricao" =>
        "Aprenda a contar caracteres, transformar textos, realizar buscas e criar novas versões de uma String.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        32,


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
                "Descobrindo o tamanho de um texto...",

            "mensagem" =>
                "O método length informa quantos caracteres existem dentro de uma String.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        String texto =
            "Java Cafe";

        int tamanho =
            texto.length();

        System.out.println(
            tamanho
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual número aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "9",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "8",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_espaco"
                ],

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_indice"
                ],

                [
                    "texto" =>
                        "O console mostra Java Cafe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_texto"
                ],

                [
                    "texto" =>
                        "Não sei o que length faz",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
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
                "Tu contou só as letras, po. O espaço entre Java e Cafe também é um caractere. São 4 letras, 1 espaço e mais 4 letras.",

            "natanael" =>
                "O método length considera todos os caracteres presentes na String. Além das oito letras, existe um espaço entre as duas palavras, totalizando nove caracteres.",

            "codigo" => <<<'JAVA'
Java = 4 caracteres
espaço = 1 caractere
Cafe = 4 caracteres

Total = 9
JAVA,

            "pergunta" =>
                "O espaço também participa da contagem?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_espaco"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU TAMANHO COM ÚLTIMO ÍNDICE
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_indice" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "length devolve a quantidade de caracteres, não o próximo índice. O texto possui 9 caracteres, mesmo que os índices comecem no zero.",

            "natanael" =>
                "O tamanho representa a quantidade total de caracteres. Os índices começariam em zero e terminariam em oito, mas length continuaria devolvendo nove.",

            "pergunta" =>
                "Se existem índices de 0 até 8, quantos caracteres existem ao todo?",

            "opcoes" => [

                [
                    "texto" =>
                        "9",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_indice"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE MOSTRA O TEXTO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_texto" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O println não recebe texto diretamente. Ele recebe a variável tamanho, que guardou o resultado de texto.length().",

            "natanael" =>
                "A chamada length produz um valor inteiro. Esse valor é armazenado em tamanho e depois enviado ao println. Portanto o conteúdo original não é apresentado.",

            "pergunta" =>
                "Qual é o tipo da variável tamanho?",

            "opcoes" => [

                [
                    "texto" =>
                        "int",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_texto"
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
                "Pensa no length como alguém contando tudo que foi digitado: letras, números, espaços e símbolos. Em Java Cafe, ele conta nove posições.",

            "natanael" =>
                "O método percorre a representação do texto e devolve sua quantidade de caracteres. O resultado é um número inteiro que pode ser armazenado ou usado em cálculos.",

            "pergunta" =>
                "O método length devolve o próprio texto ou sua quantidade de caracteres?",

            "opcoes" => [

                [
                    "texto" =>
                        "A quantidade de caracteres",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "O próprio texto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_texto"
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
                "Conta junto: Java tem 4, o espaço vale 1 e Cafe tem 4. Quatro mais um mais quatro dá nove.",

            "natanael" =>
                "A contagem considera todos os elementos presentes no texto. Somando as letras das duas palavras e o espaço central, chegamos ao tamanho total.",

            "pergunta" =>
                "Quanto texto.length() devolve?",

            "opcoes" => [

                [
                    "texto" =>
                        "9",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "8",

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
                "Transformando letras maiúsculas e minúsculas...",

            "mensagem" =>
                "O método toUpperCase cria uma versão do texto com letras maiúsculas.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        String linguagem =
            "java";

        String maiusculo =
            linguagem.toUpperCase();

        System.out.println(
            linguagem
        );

        System.out.println(
            maiusculo
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "java e depois JAVA",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "JAVA duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_original"
                ],

                [
                    "texto" =>
                        "java duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_retorno"
                ],

                [
                    "texto" =>
                        "toUpperCase",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_metodo"
                ],

                [
                    "texto" =>
                        "Não sei se a String original é alterada",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE ALTERA A STRING ORIGINAL
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_original" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "String não muda por dentro desse jeito. toUpperCase devolve uma nova versão. A variável linguagem continua com java, enquanto maiusculo recebe JAVA.",

            "natanael" =>
                "Strings são imutáveis. O método não modifica o objeto textual original. Ele produz outra String, que foi armazenada na variável maiusculo.",

            "pergunta" =>
                "Qual variável recebeu o resultado de toUpperCase?",

            "opcoes" => [

                [
                    "texto" =>
                        "maiusculo",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "linguagem foi modificada automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_original"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O RETORNO FOI IGNORADO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_retorno" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O resultado não foi perdido. linguagem.toUpperCase() devolve JAVA, e esse valor entra na variável maiusculo.",

            "natanael" =>
                "A chamada do método retorna uma nova String. Como existe uma atribuição, essa nova versão é guardada e pode ser apresentada posteriormente.",

            "codigo" => <<<'JAVA'
String maiusculo =
    linguagem.toUpperCase();
JAVA,

            "pergunta" =>
                "Qual valor fica guardado em maiusculo?",

            "opcoes" => [

                [
                    "texto" =>
                        "JAVA",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_retorno"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE MOSTRA O NOME DO MÉTODO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_metodo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "toUpperCase é só o nome da ação. O println recebe as variáveis linguagem e maiusculo, não o nome do método.",

            "natanael" =>
                "O método é executado durante a atribuição. O resultado produzido por ele é uma String. O identificador toUpperCase não é apresentado literalmente.",

            "pergunta" =>
                "O que toUpperCase devolve nesse exemplo?",

            "opcoes" => [

                [
                    "texto" =>
                        "A String JAVA",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "O texto toUpperCase",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_metodo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — NÃO SEI
        |--------------------------------------------------------------------------
        */

        "pergunta_2_nao_sei" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Pensa numa fotocópia. O texto original continua java. O método cria uma cópia transformada em JAVA e entrega ela pra outra variável.",

            "natanael" =>
                "A operação não reescreve o valor armazenado em linguagem. Ela produz um novo resultado. Como esse resultado foi atribuído a maiusculo, agora existem duas versões disponíveis.",

            "pergunta" =>
                "Depois da operação, linguagem ainda vale o quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "java",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "JAVA",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_original"
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
                "A original fica java. A nova versão criada pelo método fica JAVA. Cada println mostra uma dessas variáveis.",

            "natanael" =>
                "A primeira saída consulta o valor original. A segunda consulta a nova String retornada pelo método e armazenada separadamente.",

            "pergunta" =>
                "Qual é a sequência correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "java → JAVA",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "JAVA → JAVA",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_original"
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
                "Procurando informações dentro do texto...",

            "mensagem" =>
                "Os métodos contains e startsWith respondem com valores booleanos.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        String linguagem =
            "Java";

        boolean possui =
            linguagem.contains("av");

        boolean comeca =
            linguagem.startsWith("Ja");

        System.out.println(
            possui
        );

        System.out.println(
            comeca
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "true e depois true",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "false e depois true",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_contains"
                ],

                [
                    "texto" =>
                        "true e depois false",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_inicio"
                ],

                [
                    "texto" =>
                        "av e depois Ja",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_texto"
                ],

                [
                    "texto" =>
                        "Não sei o que esses métodos devolvem",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — NÃO ENCONTROU AV
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_contains" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Olha bem pra Java: as letras a e v aparecem juntas no meio. Então contains(\"av\") encontra esse trecho.",

            "natanael" =>
                "O método contains procura uma sequência exata de caracteres em qualquer parte da String. A sequência av existe entre a primeira e a terceira letra de Java.",

            "codigo" => <<<'JAVA'
Java
 av
JAVA,

            "pergunta" =>
                "A sequência av aparece dentro de Java?",

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
                        "pergunta_3_erro_contains"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE NÃO COMEÇA COM JA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_inicio" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Java começa exatamente com J maiúsculo e a minúsculo. Então startsWith(\"Ja\") devolve true.",

            "natanael" =>
                "startsWith analisa somente o início da String. Os dois primeiros caracteres de Java são exatamente Ja, respeitando inclusive letras maiúsculas e minúsculas.",

            "pergunta" =>
                "Quais são os dois primeiros caracteres de Java?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ja",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "av",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_inicio"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE DEVOLVE TEXTO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_texto" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Esses métodos fazem perguntas pro texto. A resposta é sim ou não, ou seja, true ou false. Eles não devolvem o trecho pesquisado.",

            "natanael" =>
                "contains e startsWith realizam verificações. Seus tipos de retorno são boolean, indicando apenas se a condição foi satisfeita.",

            "pergunta" =>
                "Qual tipo foi usado nas variáveis possui e comeca?",

            "opcoes" => [

                [
                    "texto" =>
                        "boolean",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_texto"
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
                "contains pergunta: esse pedaço aparece em algum lugar? startsWith pergunta: o texto começa desse jeito? As respostas são true ou false.",

            "natanael" =>
                "O primeiro método realiza uma busca em toda a String. O segundo compara apenas os caracteres iniciais. Ambos devolvem resultados booleanos.",

            "pergunta" =>
                "contains(\"av\") procura o trecho em qualquer parte ou somente no início?",

            "opcoes" => [

                [
                    "texto" =>
                        "Em qualquer parte",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Somente no início",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_contains"
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
                "Java contém av no meio e também começa com Ja. As duas perguntas recebem resposta verdadeira.",

            "natanael" =>
                "A primeira verificação encontra a sequência solicitada. A segunda confirma a correspondência no início do texto. Portanto os dois valores armazenados são true.",

            "pergunta" =>
                "Qual sequência completa será apresentada?",

            "opcoes" => [

                [
                    "texto" =>
                        "true → true",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "false → false",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_contains"
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
                "Substituindo e recortando partes do texto...",

            "mensagem" =>
                "replace cria uma versão com substituições, enquanto substring seleciona uma parte da String.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        String frase =
            "Java é legal";

        String novaFrase =
            frase.replace(
                "legal",
                "poderoso"
            );

        String primeiraPalavra =
            novaFrase.substring(
                0,
                4
            );

        System.out.println(
            novaFrase
        );

        System.out.println(
            primeiraPalavra
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java é poderoso e depois Java",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Java é legal e depois Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_replace"
                ],

                [
                    "texto" =>
                        "Java é poderoso e depois Java ",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_limite"
                ],

                [
                    "texto" =>
                        "poderoso e depois Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_frase"
                ],

                [
                    "texto" =>
                        "Não sei como replace e substring funcionam",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE REPLACE NÃO FUNCIONA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_replace" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "replace encontra legal e coloca poderoso no lugar. A versão nova fica guardada em novaFrase.",

            "natanael" =>
                "O método procura todas as ocorrências exatas do primeiro texto e cria uma nova String substituindo-as pelo segundo. O resultado foi armazenado separadamente.",

            "pergunta" =>
                "Qual palavra substitui legal?",

            "opcoes" => [

                [
                    "texto" =>
                        "poderoso",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Nenhuma palavra",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_replace"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — INCLUIU O LIMITE FINAL
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_limite" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "No substring, o primeiro número entra e o último fica de fora. substring(0, 4) pega os índices 0, 1, 2 e 3. Não pega o espaço do índice 4.",

            "natanael" =>
                "O índice inicial é inclusivo, enquanto o índice final é exclusivo. Portanto são selecionados quatro caracteres, começando em zero e terminando antes da posição quatro.",

            "codigo" => <<<'JAVA'
Índice 0 = J
Índice 1 = a
Índice 2 = v
Índice 3 = a
Índice 4 = espaço
JAVA,

            "pergunta" =>
                "O caractere do índice 4 entra em substring(0, 4)?",

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
                        "pergunta_4_erro_limite"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE MOSTRA SOMENTE A PALAVRA SUBSTITUÍDA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_frase" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "replace não devolve só a palavra nova. Ele devolve a frase inteira com a troca feita: Java é poderoso.",

            "natanael" =>
                "A substituição preserva todos os demais caracteres da String. Apenas o trecho correspondente é trocado, gerando uma frase completa.",

            "pergunta" =>
                "Qual é o valor completo de novaFrase?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java é poderoso",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "poderoso",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_frase"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — NÃO SEI
        |--------------------------------------------------------------------------
        */

        "pergunta_4_nao_sei" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Pensa numa edição de texto. replace troca uma palavra. Depois substring funciona como uma tesoura que recorta só os caracteres escolhidos.",

            "natanael" =>
                "A primeira operação cria uma frase modificada. Em seguida, a segunda operação seleciona o trecho que começa no índice zero e termina antes do índice quatro.",

            "pergunta" =>
                "Qual é a primeira palavra de Java é poderoso?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "poderoso",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_frase"
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
                "Primeiro legal vira poderoso. Depois substring pega os quatro primeiros caracteres da frase nova: J, a, v e a.",

            "natanael" =>
                "O resultado da substituição é Java é poderoso. Sobre essa nova String, substring seleciona os índices de zero até três, formando Java.",

            "pergunta" =>
                "Qual sequência completa está correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java é poderoso → Java",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Java é legal → Java ",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_replace"
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
                "Métodos de String desbloqueados!",

            "robert" =>
                "Boa po kkkkk. Agora tu consegue trabalhar melhor com texto. length conta os caracteres, toUpperCase e toLowerCase criam versões com letras diferentes, contains procura um trecho, startsWith verifica o começo, replace troca partes e substring recorta uma região. Só lembra que String é imutável: esses métodos normalmente devolvem uma nova String em vez de alterar a original.",

            "natanael" =>
                "Concluímos os principais métodos iniciais da classe String. Aprendemos a consultar o tamanho de um texto, criar versões em letras maiúsculas ou minúsculas, verificar a presença de sequências, analisar o início de uma String, substituir trechos e selecionar intervalos por índices. Também conhecemos a imutabilidade das Strings: as operações produzem novos valores, que precisam ser armazenados quando desejamos utilizá-los posteriormente. Na próxima aula vamos conhecer StringBuilder, uma estrutura apropriada para construir e modificar textos em várias etapas.",

            "mensagem" =>
                "Tu concluiu a trigésima primeira Aula Conversada."

        ]

    ]

];