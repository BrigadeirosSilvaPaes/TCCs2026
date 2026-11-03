<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 2,

    "titulo" =>
        "Fazendo o programa lembrar",

    "assunto" =>
        "Variáveis",

    "descricao" =>
        "Observe os exemplos e descubra como um programa consegue manter uma informação para utilizar depois.",


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
                "Olha isso aqui...",

            "mensagem" =>
                "Tem algo diferente nesse código. O que tu acha que está acontecendo?",

            "codigo" => <<<'JAVA'
int idade = 17;
JAVA,

            "opcoes" => [

                [
                    "texto" =>
                        "O programa guarda o valor 17 com o nome idade",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "O programa mostra 17 no console",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
                ],

                [
                    "texto" =>
                        "O programa soma idade com 17",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
                ],

                [
                    "texto" =>
                        "Não sei ainda",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ERRO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Olha que aqui nem apareceu println kkkkk. Então ele não tá mostrando nada ainda. Imagina uma gaveta com a etiqueta idade. Se eu colocar o número 17 dentro dela, o que aconteceu?",

            "natanael" =>
                "Vamos comparar com algo do dia a dia. Imagina várias gavetas e cada uma possui uma etiqueta dizendo o que existe ali. Criamos uma gaveta chamada idade e colocamos o número 17 dentro dela. O programa consegue lembrar daquele valor porque agora existe um lugar identificado onde ele foi armazenado.",

            "pergunta" =>
                "Nessa comparação, o nome idade seria o quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "O nome da gaveta onde o valor foi guardado",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Uma mensagem que aparece na tela",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_nao_sei"
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
                "Tranquilo. Pensa numa caixa. Na frente eu escrevo idade e dentro coloco 17. Depois, quando eu quiser saber a idade, procuro aquela caixa. No programa a ideia é bem parecida.",

            "natanael" =>
                "Vamos tirar o Java da história por um instante. Imagina uma caixa com uma etiqueta escrita idade. Dentro dela existe um papel com o número 17. A etiqueta ajuda a encontrar a informação mais tarde. Uma variável segue uma ideia parecida: temos um nome associado a um valor.",

            "codigo" => <<<'JAVA'
int idade = 17;
JAVA,

            "pergunta" =>
                "Pensando nessa caixa, qual valor está associado ao nome idade?",

            "opcoes" => [

                [
                    "texto" =>
                        "17",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "int",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
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
                "E se o valor mudar?",

            "mensagem" =>
                "Agora acompanha essas três linhas.",

            "codigo" => <<<'JAVA'
int pontos = 10;
pontos = 25;

System.out.println(pontos);
JAVA,

            "pergunta" =>
                "Qual número tu acha que vai aparecer?",

            "opcoes" => [

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
                ],

                [
                    "texto" =>
                        "25",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "10 e depois 25",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
                ],

                [
                    "texto" =>
                        "Não sei",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ERRO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Lembra da caixa? Primeiro tinha 10 dentro dela. Depois a gente tirou o 10 e colocou 25. Quando abrir a caixa no final, qual número vai estar lá?",

            "natanael" =>
                "Volta para a comparação da caixa. Primeiro criamos uma caixa chamada pontos e colocamos 10 dentro dela. Depois usamos novamente a mesma caixa, mas substituímos o conteúdo por 25. O nome continua sendo pontos, porém o valor armazenado agora é outro.",

            "pergunta" =>
                "Depois da troca, qual valor ficou guardado em pontos?",

            "opcoes" => [

                [
                    "texto" =>
                        "25",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_dica"
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
                "É tipo atualizar teu nome num quadro. O que estava escrito antes é substituído pelo novo valor. Aqui pontos começou com 10, mas depois recebeu 25.",

            "natanael" =>
                "Imagina um placar de jogo. Em um momento ele mostra 10 pontos. Depois o placar é atualizado para 25. Quando alguém olha novamente para o placar, não espera enxergar o valor antigo, mas o valor atual. A variável pontos segue essa mesma lógica nesse exemplo.",

            "pergunta" =>
                "Então o println vai encontrar qual valor em pontos?",

            "opcoes" => [

                [
                    "texto" =>
                        "25",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
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
                "Agora junta as duas ideias...",

            "mensagem" =>
                "Na aula anterior usamos valores diretamente no println. Agora olha esse exemplo.",

            "codigo" => <<<'JAVA'
int cafe = 2;

System.out.println(cafe);
JAVA,

            "pergunta" =>
                "O que tu acha que aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "A palavra cafe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
                ],

                [
                    "texto" =>
                        "O número 2",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Nada, porque cafe não está entre aspas",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
                ],

                [
                    "texto" =>
                        "Não sei",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ERRO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aqui tem uma diferença importante. Se fosse \"cafe\" com aspas, aí seria o texto cafe. Sem aspas, o Java vai procurar a caixa chamada cafe e pegar o que tem dentro dela.",

            "natanael" =>
                "Lembra da nossa caixa com etiqueta? Quando escrevemos cafe sem aspas, estamos usando o nome daquela caixa. O Java então procura o valor associado a esse nome. Se escrevêssemos \"cafe\" entre aspas, aí estaríamos falando literalmente da palavra cafe.",

            "codigo" => <<<'JAVA'
int cafe = 2;

System.out.println(cafe);
JAVA,

            "pergunta" =>
                "Qual valor está guardado na variável cafe?",

            "opcoes" => [

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "A palavra cafe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_dica"
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
                "Pensa assim: escrever cafe é tipo falar 'abre a caixa cafe'. O programa olha dentro dela e encontra o valor 2.",

            "natanael" =>
                "Imagina que tu pede para alguém: 'me traz o conteúdo da caixa cafe'. A pessoa não vai responder com a palavra cafe; ela vai abrir a caixa e entregar aquilo que está armazenado nela. É isso que acontece quando usamos o nome de uma variável.",

            "opcoes" => [

                [
                    "texto" =>
                        "Então aparece 2",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Então aparece cafe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
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
                "Último desafio...",

            "mensagem" =>
                "Agora a variável vai usar o próprio valor para mudar.",

            "codigo" => <<<'JAVA'
int idade = 17;

idade = idade + 1;

System.out.println(idade);
JAVA,

            "pergunta" =>
                "Qual valor vai aparecer no final?",

            "opcoes" => [

                [
                    "texto" =>
                        "17",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
                ],

                [
                    "texto" =>
                        "18",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "171",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
                ],

                [
                    "texto" =>
                        "Não sei",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ERRO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Vai linha por linha. A caixa idade começa com 17. Depois o programa pega esse 17, soma 1 e coloca o resultado de volta na mesma caixa.",

            "natanael" =>
                "Vamos simular como se estivéssemos atualizando uma informação numa ficha. Primeiro idade vale 17. Depois lemos esse valor, fazemos 17 + 1 e o resultado passa a ocupar o lugar do valor anterior. A variável continua com o mesmo nome, mas seu conteúdo foi atualizado.",

            "pergunta" =>
                "Quanto dá 17 + 1?",

            "opcoes" => [

                [
                    "texto" =>
                        "18",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "17",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_dica"
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
                "Imagina aniversário kkkkk. Hoje tua idade tá na caixa como 17. Passou um ano: pega 17, soma 1 e atualiza a caixa. Qual idade fica?",

            "natanael" =>
                "Pensa em uma ficha que registra a idade de uma pessoa. Neste momento está escrito 17. Quando passa um ano, pegamos a idade atual, acrescentamos 1 e atualizamos a mesma informação. No código estamos fazendo exatamente esse processo.",

            "opcoes" => [

                [
                    "texto" =>
                        "18",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "17",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
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
                "Desafio concluído!",

            "robert" =>
                "Boa po kkkkk. Guarda essa ideia: variável é tipo uma caixa com nome. Tu coloca um valor nela, pode trocar esse valor depois e pode pedir pro programa usar o que tá guardado ali. O int apareceu hoje, mas a gente vai entender melhor esses tipos na próxima.",

            "natanael" =>
                "Fechamos mais uma etapa. A comparação principal é pensar em uma variável como uma caixa identificada: existe um nome que permite encontrar aquela informação e um valor armazenado dentro dela. Esse conteúdo pode ser consultado, usado em outros comandos e até substituído por um novo valor. Também apareceu a palavra int, que indica um tipo de informação. Esse assunto merece uma aula própria e será o próximo passo.",

            "mensagem" =>
                "Tu concluiu a segunda Aula Conversada."

        ]

    ]

];