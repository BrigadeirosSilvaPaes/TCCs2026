<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 12,

    "titulo" =>
        "Repetindo com controle",

    "assunto" =>
        "for",

    "descricao" =>
        "Descubra como o Java organiza uma repetição utilizando início, condição e atualização em uma única estrutura.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        13,


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
                "Tudo em uma linha...",

            "mensagem" =>
                "Olha esse código e tenta acompanhar o valor de i durante as repetições.",

            "codigo" => <<<'JAVA'
for (int i = 1; i <= 3; i++) {

    System.out.println(i);

}
JAVA,

            "pergunta" =>
                "O que aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "1, 2 e 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Somente 1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_uma"
                ],

                [
                    "texto" =>
                        "1, 2, 3 e 4",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_quatro"
                ],

                [
                    "texto" =>
                        "Não sei como o for funciona",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE EXECUTA UMA VEZ
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_uma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se parasse no 1, o i++ ia ficar ali de enfeite kkkkk. Depois da primeira repetição, i aumenta e o for testa a condição de novo.",

            "natanael" =>
                "O valor inicial é apenas o começo da repetição. Depois que o bloco executa com i valendo 1, a parte i++ aumenta esse valor. Em seguida, a condição i <= 3 é verificada novamente para decidir se haverá outra repetição.",

            "pergunta" =>
                "Depois da primeira repetição, quanto vale i?",

            "opcoes" => [

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Continua 1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_uma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE MOSTRA 4
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_quatro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O i até chega em 4, mas quando isso acontece o Java testa 4 <= 3. Dá false. Então o bloco não roda com 4.",

            "natanael" =>
                "Depois da repetição em que i vale 3, a atualização i++ faz o valor chegar a 4. Porém, antes de uma nova execução, a condição é verificada. Como 4 <= 3 é false, o bloco não é executado novamente.",

            "pergunta" =>
                "Quando i vale 4, a condição i <= 3 continua verdadeira?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_quatro"
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
                "Pensa numa contagem com começo, limite e passo. Começa em 1, continua enquanto for 3 ou menos e soma 1 depois de cada volta.",

            "natanael" =>
                "O for pode ser entendido como uma repetição organizada em três partes. Primeiro definimos onde começar. Depois indicamos até quando continuar. Por último, definimos como o valor muda após cada repetição.",

            "codigo" => <<<'JAVA'
for (
    int i = 1;
    i <= 3;
    i++
)
JAVA,

            "pergunta" =>
                "Se começa em 1 e aumenta de um em um enquanto for menor ou igual a 3, quais valores aparecem?",

            "opcoes" => [

                [
                    "texto" =>
                        "1, 2 e 3",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Somente 1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_uma"
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
                "Segue o caminho: começa em 1, mostra 1, aumenta. Mostra 2, aumenta. Mostra 3, aumenta. Chegou em 4 e a condição falha.",

            "natanael" =>
                "A execução fica assim: i começa em 1 e passa pela condição. O bloco executa e depois i é incrementado. O mesmo acontece com 2 e 3. Quando chega a 4, a condição deixa de ser verdadeira e a repetição termina.",

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "1 → 2 → 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "1 → 2 → 3 → 4",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_quatro"
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
                "O que significa cada parte?",

            "mensagem" =>
                "Agora vamos separar o cabeçalho do for em três pedaços.",

            "codigo" => <<<'JAVA'
for (int i = 0; i < 5; i++) {

    System.out.println(i);

}
JAVA,

            "pergunta" =>
                "Qual parte define o valor inicial do contador?",

            "opcoes" => [

                [
                    "texto" =>
                        "int i = 0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "i < 5",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_condicao"
                ],

                [
                    "texto" =>
                        "i++",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_incremento"
                ],

                [
                    "texto" =>
                        "System.out.println(i)",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_bloco"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUNDIU COM CONDIÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_condicao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "i < 5 não diz onde começar. Ele diz até quando o for pode continuar.",

            "natanael" =>
                "A expressão i < 5 é uma condição booleana. Ela não cria o contador nem define seu primeiro valor. Sua função é decidir se uma nova repetição pode acontecer.",

            "pergunta" =>
                "Qual parte realmente cria i já com um valor?",

            "opcoes" => [

                [
                    "texto" =>
                        "int i = 0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "i < 5",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_condicao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUNDIU COM INCREMENTO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_incremento" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "i++ é o que acontece depois de cada volta. Ele aumenta o contador. Pra começar, olha a primeira parte.",

            "natanael" =>
                "A expressão i++ representa a atualização. Ela acontece depois de cada repetição e aumenta o valor do contador em uma unidade. O valor inicial é definido antes desse processo começar.",

            "pergunta" =>
                "Qual parte aparece primeiro no for e prepara o contador?",

            "opcoes" => [

                [
                    "texto" =>
                        "int i = 0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "i++",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_incremento"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUNDIU COM O BLOCO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_bloco" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O println é a tarefa que vai repetir. Ele não controla o contador. O controle tá dentro dos parênteses do for.",

            "natanael" =>
                "System.out.println(i) faz parte do bloco repetido. Ele usa o contador, mas não define o início da repetição. As informações de controle ficam dentro dos parênteses do for.",

            "pergunta" =>
                "Dentro dos parênteses, qual trecho cria o contador?",

            "opcoes" => [

                [
                    "texto" =>
                        "int i = 0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "System.out.println(i)",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_bloco"
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
                "O for fica quase assim: começa em 0; continua enquanto i < 5; depois de cada volta faz i++.",

            "natanael" =>
                "Podemos ler as três partes como uma frase: comece criando i com valor 0; continue enquanto i for menor que 5; depois de cada repetição, aumente i em uma unidade.",

            "codigo" => <<<'JAVA'
for (
    int i = 0;  // início
    i < 5;      // condição
    i++         // atualização
)
JAVA,

            "pergunta" =>
                "Qual parte representa o início?",

            "opcoes" => [

                [
                    "texto" =>
                        "int i = 0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "i++",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_incremento"
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
                "Não precisa aumentar sempre de um...",

            "mensagem" =>
                "Agora observa a parte responsável pela atualização.",

            "codigo" => <<<'JAVA'
for (int numero = 0; numero <= 10; numero += 2) {

    System.out.println(numero);

}
JAVA,

            "pergunta" =>
                "Qual sequência será exibida?",

            "opcoes" => [

                [
                    "texto" =>
                        "0, 2, 4, 6, 8 e 10",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "0, 1, 2, 3, 4, 5, 6, 7, 8, 9 e 10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_um"
                ],

                [
                    "texto" =>
                        "2, 4, 6, 8 e 10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_inicio"
                ],

                [
                    "texto" =>
                        "0, 2, 4, 6, 8, 10 e 12",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_doze"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE AUMENTA DE UM
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_um" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aqui não tem numero++. Tem numero += 2. Então ele pula de dois em dois.",

            "natanael" =>
                "A atualização define o passo da repetição. Nesse caso não estamos somando apenas uma unidade. A expressão numero += 2 acrescenta dois ao valor atual depois de cada execução.",

            "pergunta" =>
                "Se numero começa em 0 e somamos 2, qual é o próximo valor?",

            "opcoes" => [

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_um"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ESQUECEU O ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_inicio" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Olha o começo: numero = 0. O bloco executa com esse valor antes de fazer o primeiro += 2.",

            "natanael" =>
                "A inicialização já coloca numero com valor 0. Como 0 <= 10 é verdadeiro, existe uma primeira execução utilizando esse valor. Somente depois ocorre a atualização para 2.",

            "pergunta" =>
                "Qual é o primeiro valor definido para numero?",

            "opcoes" => [

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_inicio"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE MOSTRA 12
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_doze" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Depois do 10 realmente vira 12. Só que antes de imprimir de novo ele testa 12 <= 10. False. Aí acabou.",

            "natanael" =>
                "Após a execução com 10, a atualização acrescenta dois e o contador chega a 12. Porém uma nova execução só poderia ocorrer se 12 <= 10 fosse verdadeiro. Como não é, o laço termina.",

            "pergunta" =>
                "12 é menor ou igual a 10?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_doze"
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
                "Começa no 0 e dá pulos de 2: 0, 2, 4, 6, 8, 10. Depois vira 12 e para.",

            "natanael" =>
                "Podemos visualizar como passos em uma linha numérica. Partimos do zero e avançamos duas unidades por repetição. Enquanto o valor continuar dentro do limite 10, ele será exibido.",

            "pergunta" =>
                "Qual sequência respeita início 0, passo 2 e limite 10?",

            "opcoes" => [

                [
                    "texto" =>
                        "0 → 2 → 4 → 6 → 8 → 10",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "0 → 1 → 2 → 3 → 4",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_um"
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
                "Também dá pra voltar...",

            "mensagem" =>
                "O for não precisa contar somente para frente. Observa a atualização desse exemplo.",

            "codigo" => <<<'JAVA'
for (int i = 3; i >= 1; i--) {

    System.out.println(i);

}

System.out.println("Vai!");
JAVA,

            "pergunta" =>
                "Qual sequência aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "3, 2, 1 e depois Vai!",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "1, 2, 3 e depois Vai!",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_direcao"
                ],

                [
                    "texto" =>
                        "3, 2, 1, 0 e depois Vai!",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_zero"
                ],

                [
                    "texto" =>
                        "3 para sempre",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_infinito"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — DIREÇÃO ERRADA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_direcao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Olha o i--. Dois sinais de menos fazem o contador diminuir uma unidade por volta. Então começa em 3 e vai descendo.",

            "natanael" =>
                "A direção da contagem é determinada pela atualização. A expressão i-- reduz o valor em uma unidade após cada repetição. Como o valor inicial é 3, a sequência segue para 2 e depois 1.",

            "pergunta" =>
                "Depois de i valer 3, quanto ele passa a valer com i--?",

            "opcoes" => [

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "4",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_direcao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE MOSTRA ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_zero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Depois do 1 ele vira 0, mas aí vem a condição: 0 >= 1? Não. Então o zero não chega no println.",

            "natanael" =>
                "Depois da execução com i valendo 1, a atualização reduz o valor para 0. Em seguida a condição i >= 1 é avaliada novamente. Como é falsa, não existe uma execução utilizando zero.",

            "pergunta" =>
                "Quando i vale 0, a condição i >= 1 é verdadeira?",

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
                        "pergunta_4_erro_zero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE É INFINITO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_infinito" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Tem i-- mexendo no valor. Então 3 não fica parado. Vai pra 2, depois 1, depois 0 e aí a condição quebra.",

            "natanael" =>
                "A variável está sendo atualizada a cada repetição. O i-- reduz gradualmente o valor até que ele deixe de satisfazer a condição i >= 1. Portanto existe um ponto claro de encerramento.",

            "pergunta" =>
                "Qual parte está diminuindo o contador?",

            "opcoes" => [

                [
                    "texto" =>
                        "i--",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "i >= 1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_infinito"
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
                "É uma contagem regressiva: começa em 3, diminui pra 2, depois 1. Depois vira 0 e o for termina. Aí segue pro Vai!.",

            "natanael" =>
                "Acompanhe como uma contagem para lançamento. O valor começa em 3, depois passa para 2 e finalmente 1. Quando chega a zero, a condição deixa de ser atendida e o programa continua para a instrução que está fora do for.",

            "pergunta" =>
                "Qual sequência completa faz sentido?",

            "opcoes" => [

                [
                    "texto" =>
                        "3 → 2 → 1 → Vai!",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "3 → 2 → 1 → 0 → Vai!",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_zero"
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
                "For desbloqueado!",

            "robert" =>
                "Boa po kkkkk. O for parece grandão no começo, mas pensa em três perguntas: onde começa? até quando vai? como muda depois de cada volta? Fica tudo organizado na mesma linha. Dá pra contar pra frente, pra trás, de dois em dois e muito mais. Agora já temos uma base boa de repetição pra começar a usar estruturas que guardam vários valores.",

            "natanael" =>
                "Fechamos as principais estruturas de repetição. O for reúne inicialização, condição e atualização em seu cabeçalho. A inicialização prepara o contador, a condição decide se a repetição continua e a atualização modifica o valor depois de cada execução. Também vimos que o contador pode crescer, diminuir ou avançar em passos diferentes. Essa estrutura será especialmente útil quando começarmos a percorrer conjuntos de informações, como arrays.",

            "mensagem" =>
                "Tu concluiu a décima segunda Aula Conversada."

        ]

    ]

];