<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 33,

    "titulo" =>
        "Cálculos e números aleatórios",

    "assunto" =>
        "Math e Random",

    "descricao" =>
        "Aprenda a comparar valores, calcular potências e raízes, arredondar números e gerar resultados aleatórios em Java.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        34,


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
                "Encontrando o maior e o menor valor...",

            "mensagem" =>
                "A classe Math possui métodos que comparam dois números e devolvem o maior ou o menor deles.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        int idade1 = 18;

        int idade2 = 25;

        int maior =
            Math.max(
                idade1,
                idade2
            );

        int menor =
            Math.min(
                idade1,
                idade2
            );

        System.out.println(
            maior
        );

        System.out.println(
            menor
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "25 e depois 18",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "18 e depois 25",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_inverteu"
                ],

                [
                    "texto" =>
                        "25 duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_max"
                ],

                [
                    "texto" =>
                        "18 duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_min"
                ],

                [
                    "texto" =>
                        "Não sei o que max e min fazem",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — INVERTEU OS MÉTODOS
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_inverteu" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Tu inverteu os dois, po. max vem de máximo, então devolve o maior. min vem de mínimo, então devolve o menor.",

            "natanael" =>
                "Os nomes indicam o comportamento. Math.max seleciona o valor máximo entre os argumentos, enquanto Math.min seleciona o valor mínimo.",

            "pergunta" =>
                "Entre 18 e 25, qual é o valor máximo?",

            "opcoes" => [

                [
                    "texto" =>
                        "25",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "18",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_inverteu"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE OS DOIS DEVOLVEM O MAIOR
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_max" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Só Math.max devolve o maior. Depois o código usa Math.min, que procura justamente o menor valor.",

            "natanael" =>
                "Os métodos possuem responsabilidades opostas. O primeiro identifica o máximo, enquanto o segundo identifica o mínimo entre os mesmos valores.",

            "pergunta" =>
                "Qual método devolve o menor número?",

            "opcoes" => [

                [
                    "texto" =>
                        "Math.min()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Math.max()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_max"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE OS DOIS DEVOLVEM O MENOR
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_min" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Math.min devolve 18, mas antes dele existe Math.max. Esse primeiro método encontra 25.",

            "natanael" =>
                "A variável menor recebe 18, porém a variável maior foi preenchida anteriormente com o resultado de Math.max, que é 25.",

            "pergunta" =>
                "Qual método preenche a variável maior?",

            "opcoes" => [

                [
                    "texto" =>
                        "Math.max()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Math.min()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_min"
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
                "Pensa numa comparação de altura. max escolhe quem é maior e min escolhe quem é menor. Aqui estamos comparando 18 com 25.",

            "natanael" =>
                "Imagine dois valores colocados lado a lado. Math.max devolve aquele que está acima na comparação, enquanto Math.min devolve o valor inferior.",

            "pergunta" =>
                "Entre 18 e 25, qual número é o menor?",

            "opcoes" => [

                [
                    "texto" =>
                        "18",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "25",

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
                "Math.max compara 18 e 25 e devolve 25. Math.min compara os mesmos números e devolve 18.",

            "natanael" =>
                "A primeira comparação seleciona o maior argumento. A segunda seleciona o menor. Os resultados são apresentados na mesma ordem das variáveis.",

            "pergunta" =>
                "Qual sequência completa será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "25 → 18",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "18 → 25",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_inverteu"
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
                "Calculando potências e raízes...",

            "mensagem" =>
                "Math.pow calcula uma potência, enquanto Math.sqrt calcula uma raiz quadrada.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        double potencia =
            Math.pow(
                3,
                2
            );

        double raiz =
            Math.sqrt(
                81
            );

        System.out.println(
            potencia
        );

        System.out.println(
            raiz
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "9.0 e depois 9.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "6.0 e depois 9.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_pow"
                ],

                [
                    "texto" =>
                        "9.0 e depois 40.5",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_raiz"
                ],

                [
                    "texto" =>
                        "3.0 e depois 81.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_retorno"
                ],

                [
                    "texto" =>
                        "Não sei como potência e raiz funcionam",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUNDIU POTÊNCIA COM MULTIPLICAÇÃO DOS ARGUMENTOS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_pow" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Math.pow(3, 2) não faz 3 vezes 2. Ele faz 3 elevado a 2, ou seja, 3 vezes 3.",

            "natanael" =>
                "O primeiro argumento representa a base e o segundo representa o expoente. Elevar três ao quadrado significa multiplicar a base por ela mesma duas vezes.",

            "codigo" => <<<'JAVA'
Math.pow(3, 2)

3² = 3 * 3
JAVA,

            "pergunta" =>
                "Quanto resulta 3 multiplicado por 3?",

            "opcoes" => [

                [
                    "texto" =>
                        "9",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "6",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_pow"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUNDIU RAIZ COM DIVISÃO POR DOIS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_raiz" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Raiz quadrada não é pegar metade do número. A pergunta é: qual número multiplicado por ele mesmo dá 81? A resposta é 9.",

            "natanael" =>
                "A raiz quadrada procura um valor cujo quadrado resulte no número informado. Como nove multiplicado por nove produz 81, Math.sqrt devolve 9.0.",

            "pergunta" =>
                "Qual número multiplicado por ele mesmo resulta em 81?",

            "opcoes" => [

                [
                    "texto" =>
                        "9",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "40.5",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_raiz"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE DEVOLVE OS ARGUMENTOS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_retorno" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Os métodos não devolvem os números do jeito que entraram. Eles realizam o cálculo e devolvem o resultado.",

            "natanael" =>
                "Os argumentos funcionam como informações de entrada. Math.pow e Math.sqrt processam esses valores e retornam novos resultados numéricos.",

            "pergunta" =>
                "Math.sqrt(81) devolve o próprio 81 ou sua raiz quadrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sua raiz quadrada",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "O próprio 81",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_retorno"
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
                "pow faz potência: 3 elevado a 2 dá 9. sqrt faz o caminho da raiz: a raiz de 81 também dá 9.",

            "natanael" =>
                "Na potência, utilizamos uma base e um expoente. Na raiz quadrada, procuramos o número que, multiplicado por ele mesmo, gera o valor informado.",

            "pergunta" =>
                "Quanto vale 3²?",

            "opcoes" => [

                [
                    "texto" =>
                        "9",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "6",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_pow"
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
                "Math.pow(3, 2) faz 3 vezes 3. Math.sqrt(81) procura qual número vezes ele mesmo dá 81. Os dois chegam em 9.",

            "natanael" =>
                "A potência resulta em nove e a raiz quadrada também resulta em nove. Como os métodos devolvem double, a saída apresenta 9.0.",

            "pergunta" =>
                "Qual sequência completa será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "9.0 → 9.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "6.0 → 40.5",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_pow"
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
                "Trabalhando com valores negativos e decimais...",

            "mensagem" =>
                "Math.abs devolve o valor absoluto de um número, enquanto Math.round realiza um arredondamento.",

            "codigo" => <<<'JAVA'
public class Main {

    public static void main(String[] args) {

        int saldo = -40;

        int valorPositivo =
            Math.abs(saldo);

        double nota = 7.6;

        long notaArredondada =
            Math.round(nota);

        System.out.println(
            valorPositivo
        );

        System.out.println(
            notaArredondada
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "40 e depois 8",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "-40 e depois 8",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_abs"
                ],

                [
                    "texto" =>
                        "40 e depois 7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_round"
                ],

                [
                    "texto" =>
                        "40 e depois 7.6",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_original"
                ],

                [
                    "texto" =>
                        "Não sei como abs e round funcionam",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE ABS MANTÉM O SINAL NEGATIVO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_abs" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Math.abs pega a distância até o zero. Distância não fica negativa, então o resultado de -40 vira 40.",

            "natanael" =>
                "O valor absoluto representa a magnitude do número sem considerar seu sinal. Por isso Math.abs aplicado a -40 devolve o inteiro positivo 40.",

            "pergunta" =>
                "Qual é o valor absoluto de -40?",

            "opcoes" => [

                [
                    "texto" =>
                        "40",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "-40",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_abs"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE ROUND SEMPRE ARREDONDA PARA BAIXO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_round" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Math.round olha a parte decimal. Como 7.6 está mais perto de 8 do que de 7, ele arredonda pra cima.",

            "natanael" =>
                "O arredondamento considera a proximidade com o inteiro seguinte. Como a parte decimal é 0.6, o valor é arredondado para oito.",

            "pergunta" =>
                "7.6 está mais próximo de 7 ou de 8?",

            "opcoes" => [

                [
                    "texto" =>
                        "De 8",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "De 7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_round"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE ROUND DEVOLVE O MESMO DECIMAL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_original" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Math.round não mantém o decimal igual. Ele devolve um número inteiro arredondado. Aqui a variável até foi declarada como long.",

            "natanael" =>
                "O método processa o valor decimal e devolve um número inteiro do tipo long quando recebe um double. Por isso 7.6 não permanece inalterado.",

            "pergunta" =>
                "Qual é o tipo da variável notaArredondada?",

            "opcoes" => [

                [
                    "texto" =>
                        "long",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "double",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_original"
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
                "abs tira o sinal negativo do valor. round olha qual inteiro está mais perto. Então -40 vira 40 e 7.6 vira 8.",

            "natanael" =>
                "O primeiro método trabalha com a magnitude do número. O segundo transforma um decimal no inteiro mais próximo, seguindo a regra de arredondamento.",

            "pergunta" =>
                "Qual inteiro está mais próximo de 7.6?",

            "opcoes" => [

                [
                    "texto" =>
                        "8",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_round"
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
                "Math.abs(-40) devolve 40. Math.round(7.6) arredonda pro inteiro mais próximo, que é 8.",

            "natanael" =>
                "A primeira operação remove a influência do sinal negativo. A segunda avalia a parte decimal e aproxima o valor para oito.",

            "pergunta" =>
                "Qual sequência completa será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "40 → 8",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "-40 → 7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_abs"
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
                "Simulando o lançamento de um dado...",

            "mensagem" =>
                "A classe Random pode gerar números diferentes durante cada execução do programa.",

            "codigo" => <<<'JAVA'
import java.util.Random;

public class Main {

    public static void main(String[] args) {

        Random sorteio =
            new Random();

        for (
            int i = 0;
            i < 3;
            i++
        ) {

            int dado =
                sorteio.nextInt(6) + 1;

            System.out.println(
                dado
            );

        }

    }

}
JAVA,

            "pergunta" =>
                "Qual afirmação é garantida sobre os números apresentados?",

            "opcoes" => [

                [
                    "texto" =>
                        "Todos estarão entre 1 e 6",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Todos estarão entre 0 e 5",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_soma"
                ],

                [
                    "texto" =>
                        "O número 7 também pode aparecer",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_limite"
                ],

                [
                    "texto" =>
                        "Os três resultados serão sempre iguais",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_iguais"
                ],

                [
                    "texto" =>
                        "O programa mostra somente um número",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_quantidade"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ESQUECEU DO +1
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_soma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "nextInt(6) sozinho gera de 0 até 5. Só que depois o código soma 1. Então tudo sobe uma posição e vira de 1 até 6.",

            "natanael" =>
                "O limite informado não é incluído. Inicialmente os resultados possíveis são zero, um, dois, três, quatro e cinco. A adição de uma unidade transforma o intervalo em um até seis.",

            "codigo" => <<<'JAVA'
nextInt(6)
→ 0, 1, 2, 3, 4 ou 5

nextInt(6) + 1
→ 1, 2, 3, 4, 5 ou 6
JAVA,

            "pergunta" =>
                "Qual é o menor resultado depois da soma de 1?",

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
                        "pergunta_4_erro_soma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE O LIMITE É INCLUÍDO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_limite" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O 6 do nextInt é limite e não entra no sorteio. O maior valor antes da soma é 5. Depois de somar 1, o maior vira 6, não 7.",

            "natanael" =>
                "Random.nextInt(6) utiliza zero como início e seis como limite exclusivo. Assim, o maior valor possível nessa etapa é cinco.",

            "pergunta" =>
                "Qual é o maior valor que nextInt(6) pode devolver antes do +1?",

            "opcoes" => [

                [
                    "texto" =>
                        "5",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "6",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_limite"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE OS RESULTADOS PRECISAM SER IGUAIS
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_iguais" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Cada volta chama nextInt de novo. Pode repetir número, mas também pode gerar valores diferentes. Não existe garantia de serem iguais.",

            "natanael" =>
                "Uma nova geração acontece em cada repetição. Resultados repetidos são possíveis, porém não obrigatórios. O comportamento aleatório permite diferentes combinações.",

            "pergunta" =>
                "O mesmo número pode se repetir em um sorteio aleatório?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim, mas não é obrigatório",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Não, nunca pode repetir",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_iguais"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE O FOR EXECUTA UMA VEZ
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_quantidade" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O for começa em 0 e continua enquanto i for menor que 3. Então passa por 0, 1 e 2: três sorteios.",

            "natanael" =>
                "O contador assume três valores válidos antes de alcançar o limite. Em cada passagem, um número é gerado e apresentado.",

            "pergunta" =>
                "Quantas vezes o corpo do for é executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Três vezes",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Uma vez",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_quantidade"
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
                "nextInt(6) gera de 0 até 5. O +1 transforma isso em 1 até 6. O for faz esse sorteio três vezes.",

            "natanael" =>
                "Cada repetição produz um valor dentro do intervalo definido. Os números exatos não podem ser previstos, mas todos respeitarão os limites de um dado comum.",

            "pergunta" =>
                "Qual intervalo representa todos os resultados possíveis?",

            "opcoes" => [

                [
                    "texto" =>
                        "De 1 até 6",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "De 0 até 7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_limite"
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
                "Recursos matemáticos desbloqueados!",

            "robert" =>
                "Boa po kkkkk. Agora tu consegue usar Math.max e Math.min pra comparar números, Math.pow pra potência, Math.sqrt pra raiz, Math.abs pra valor absoluto e Math.round pra arredondar. Também aprendeu que Random gera resultados diferentes e que nextInt usa um limite que fica de fora. Pra simular um dado, nextInt(6) + 1 gera valores de 1 até 6.",

            "natanael" =>
                "Concluímos alguns dos principais recursos matemáticos do Java. Utilizamos Math.max e Math.min para comparar valores, Math.pow e Math.sqrt para realizar cálculos, Math.abs para obter valores absolutos e Math.round para arredondar números. Também conhecemos a classe Random e entendemos que o limite de nextInt é exclusivo. Com ajustes no resultado, podemos criar intervalos adequados para dados, sorteios, jogos e outras situações. Na próxima aula vamos aprender a representar e manipular datas e horários com as classes modernas do Java.",

            "mensagem" =>
                "Tu concluiu a trigésima terceira Aula Conversada."

        ]

    ]

];