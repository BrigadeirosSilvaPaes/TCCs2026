<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 4,

    "titulo" =>
        "Fazendo o programa calcular",

    "assunto" =>
        "Operadores matemáticos",

    "descricao" =>
        "Observe diferentes situações e descubra como o Java pode realizar cálculos utilizando valores e variáveis.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        5,


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
                "Começando pelo básico...",

            "mensagem" =>
                "Olha esse código. Antes de pensar no Java, tenta resolver a conta normalmente.",

            "codigo" => <<<'JAVA'
int cafe = 5;
int pao = 3;

int total = cafe + pao;

System.out.println(total);
JAVA,

            "pergunta" =>
                "Qual valor vai aparecer no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "8",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "53",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_juntar"
                ],

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_subtracao"
                ],

                [
                    "texto" =>
                        "Não sei",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUSÃO COM 53
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_juntar" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aí tu juntou os números como se fossem texto kkkkk. Mas aqui cafe vale 5 e pao vale 3. O sinal + tá fazendo uma conta de verdade.",

            "natanael" =>
                "Aqui apareceu uma confusão interessante. Juntar 5 e 3 formando 53 seria parecido com juntar dois pedaços de texto. Só que as duas variáveis são int, ou seja, representam números inteiros. Nesse contexto, o sinal + realiza uma soma matemática.",

            "pergunta" =>
                "Se tu compra algo por 5 reais e outra coisa por 3 reais, quanto gastou no total?",

            "opcoes" => [

                [
                    "texto" =>
                        "8 reais",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "53 reais",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_juntar"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUSÃO COM SUBTRAÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_subtracao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "2 seria se fosse 5 - 3. Mas olha o símbolo que aparece no código: é +. Ele tá mandando somar.",

            "natanael" =>
                "O resultado 2 faria sentido se estivéssemos calculando a diferença entre 5 e 3. Porém o operador presente na expressão é +. Então precisamos combinar os dois valores por meio de uma soma.",

            "codigo" => <<<'JAVA'
int total = 5 + 3;
JAVA,

            "pergunta" =>
                "Qual operação o símbolo + representa aqui?",

            "opcoes" => [

                [
                    "texto" =>
                        "Soma",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Subtração",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_subtracao"
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
                "Pensa numa compra: café custou 5 e pão custou 3. Pra descobrir quanto saiu do bolso, tu soma os dois.",

            "natanael" =>
                "Trazendo para uma situação comum: imagina que um produto custa 5 reais e outro custa 3 reais. Para descobrir o valor total da compra, precisamos juntar matematicamente esses valores por meio de uma soma.",

            "pergunta" =>
                "Então quanto vale 5 + 3?",

            "opcoes" => [

                [
                    "texto" =>
                        "8",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_subtracao"
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
                "Agora muda o símbolo...",

            "mensagem" =>
                "Imagina que cada café custa 4 reais e uma pessoa compra 3 cafés.",

            "codigo" => <<<'JAVA'
int preco = 4;
int quantidade = 3;

int total = preco * quantidade;

System.out.println(total);
JAVA,

            "pergunta" =>
                "Qual valor será mostrado?",

            "opcoes" => [

                [
                    "texto" =>
                        "12",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_soma"
                ],

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_subtracao"
                ],

                [
                    "texto" =>
                        "Não sei o que * significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUSÃO COM SOMA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_soma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "7 seria 4 + 3. Só que aqui apareceu *. No Java esse asterisco é usado pra multiplicar.",

            "natanael" =>
                "O valor 7 surgiria se somássemos 4 e 3 apenas uma vez. Porém a situação diz que temos 3 unidades custando 4 reais cada. Em Java, o símbolo * representa multiplicação.",

            "pergunta" =>
                "Se são 3 cafés de 4 reais cada, qual conta representa melhor isso?",

            "opcoes" => [

                [
                    "texto" =>
                        "4 × 3",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "4 + 3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_soma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUSÃO COM SUBTRAÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_subtracao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "1 seria 4 - 3. Mas ninguém tá tirando café aqui kkkkk. Temos 3 cafés e cada um custa 4.",

            "natanael" =>
                "A subtração seria adequada se estivéssemos retirando uma quantidade de outra. Aqui a situação é diferente: o mesmo preço de 4 reais se repete para cada uma das 3 unidades compradas.",

            "pergunta" =>
                "Quanto dá 4 + 4 + 4?",

            "opcoes" => [

                [
                    "texto" =>
                        "12",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_subtracao"
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
                "O * é o sinal de multiplicação no Java. Pensa que 4 reais se repete 3 vezes: 4 + 4 + 4.",

            "natanael" =>
                "Como o teclado não possui normalmente o símbolo × usado na matemática escrita, linguagens de programação costumam usar o asterisco *. Nesse exemplo ele representa 4 repetido três vezes: 4 + 4 + 4.",

            "pergunta" =>
                "Quanto vale 4 * 3?",

            "opcoes" => [

                [
                    "texto" =>
                        "12",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_soma"
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
                "Hora de dividir...",

            "mensagem" =>
                "Quatro amigos decidiram dividir igualmente uma conta de 40 reais.",

            "codigo" => <<<'JAVA'
int conta = 40;
int pessoas = 4;

int valorPorPessoa = conta / pessoas;

System.out.println(valorPorPessoa);
JAVA,

            "pergunta" =>
                "Quanto cada pessoa vai pagar?",

            "opcoes" => [

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "44",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_soma"
                ],

                [
                    "texto" =>
                        "160",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_multiplicacao"
                ],

                [
                    "texto" =>
                        "Não sei o que / significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUSÃO COM SOMA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_soma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "44 seria somar 40 + 4. Mas a conta precisa ser repartida entre quatro pessoas, não aumentada.",

            "natanael" =>
                "Somar 40 e 4 aumentaria o valor da conta. A situação pede justamente o contrário: queremos repartir o total de 40 reais igualmente entre quatro pessoas.",

            "pergunta" =>
                "Se 40 reais forem separados igualmente entre 4 pessoas, quanto fica para cada uma?",

            "opcoes" => [

                [
                    "texto" =>
                        "10 reais",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "44 reais",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_soma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUSÃO COM MULTIPLICAÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_multiplicacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "160 seria 40 * 4. Aí tu ia multiplicar a dívida kkkkk. Aqui a ideia é dividir o total entre os quatro.",

            "natanael" =>
                "Multiplicar 40 por 4 faria o valor crescer para 160. Porém já temos um total de 40 reais e queremos descobrir qual parcela desse total corresponde a cada uma das quatro pessoas.",

            "pergunta" =>
                "A situação pede aumentar o valor ou repartir o valor?",

            "opcoes" => [

                [
                    "texto" =>
                        "Repartir",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Aumentar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_multiplicacao"
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
                "No Java, / significa divisão. É tipo dividir uma pizza igualmente entre várias pessoas. Aqui são 40 reais pra 4 pessoas.",

            "natanael" =>
                "O operador / representa divisão. Imagina quatro pessoas em volta de uma mesa repartindo igualmente um valor total. Precisamos descobrir quantas partes de mesmo tamanho cabem dentro dos 40 reais.",

            "pergunta" =>
                "Quanto vale 40 / 4?",

            "opcoes" => [

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "160",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_multiplicacao"
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
                "E quando sobra alguma coisa?",

            "mensagem" =>
                "Agora temos 17 balas e queremos dividir igualmente entre 5 pessoas.",

            "codigo" => <<<'JAVA'
int balas = 17;
int pessoas = 5;

int sobra = balas % pessoas;

System.out.println(sobra);
JAVA,

            "pergunta" =>
                "O que tu acha que o operador % vai mostrar nesse caso?",

            "opcoes" => [

                [
                    "texto" =>
                        "2, porque é o que sobra da divisão",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "3, porque cada pessoa recebe 3 balas",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_quociente"
                ],

                [
                    "texto" =>
                        "85",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_multiplicacao"
                ],

                [
                    "texto" =>
                        "Não sei o que % significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUSÃO COM RESULTADO DA DIVISÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_quociente" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "3 é quanto cada pessoa consegue receber. Mas o % não quer saber isso. Ele pergunta: depois de dividir, quantas ficaram sobrando?",

            "natanael" =>
                "Tu encontrou corretamente quantas balas cada pessoa consegue receber: três. Porém o operador % não retorna essa quantidade. Ele retorna o restante que não conseguiu ser distribuído igualmente.",

            "pergunta" =>
                "Se cada uma das 5 pessoas recebe 3 balas, quantas foram utilizadas no total?",

            "opcoes" => [

                [
                    "texto" =>
                        "15",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "17",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_quociente"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUSÃO COM MULTIPLICAÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_multiplicacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "85 seria 17 * 5. Mas o símbolo aqui é %, não *. O % serve pra descobrir o resto de uma divisão.",

            "natanael" =>
                "O resultado 85 corresponderia à multiplicação entre 17 e 5. O operador utilizado aqui é %, chamado de módulo ou resto da divisão. Ele responde quanto sobra depois que fazemos uma divisão inteira.",

            "pergunta" =>
                "Quando 17 balas são distribuídas de 5 em 5, quantas sobram?",

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
                        "85",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_multiplicacao"
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
                "Pensa nas balas mesmo. 17 dividido entre 5 pessoas: cada uma pega 3. Isso usa 15 balas. Quantas ficaram na mão?",

            "natanael" =>
                "Vamos fazer a distribuição mentalmente. Temos 17 balas. Se cada uma das cinco pessoas receber três, utilizamos 15 balas. O operador % serve justamente para descobrir a quantidade que permaneceu sem conseguir formar outra divisão completa.",

            "pergunta" =>
                "Depois de usar 15 das 17 balas, quantas sobraram?",

            "opcoes" => [

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_quociente"
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
                "Contas desbloqueadas!",

            "robert" =>
                "Boa po kkkkk. Agora já tem o kit básico: + soma, - subtrai, * multiplica, / divide e % pega o resto da divisão. É literalmente dar as contas pro programa fazer por ti. Na próxima a gente vai começar a comparar valores.",

            "natanael" =>
                "Fechamos os principais operadores matemáticos básicos. O + realiza adição, o - realiza subtração, o * representa multiplicação, o / realiza divisão e o % retorna o resto de uma divisão inteira. As situações de compra, quantidade, divisão de conta e sobra ajudam a relacionar cada operador com um problema real. Na próxima etapa vamos usar valores de outra maneira: comparando informações para descobrir se uma condição é verdadeira ou falsa.",

            "mensagem" =>
                "Tu concluiu a quarta Aula Conversada."

        ]

    ]

];