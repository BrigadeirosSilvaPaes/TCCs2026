<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 1,

    "titulo" =>
        "Seu primeiro comando em Java",

    "assunto" =>
        "System.out.println()",

    "descricao" =>
        "Observe o código, pense nas possibilidades e descubra aos poucos o que esse comando faz.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        2,


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
                "Pra começar...",

            "mensagem" =>
                "Olha esse código com calma. O que tu acha que acontece quando ele é executado?",

            "codigo" => <<<'JAVA'
System.out.println("Olá");
JAVA,

            "opcoes" => [

                [
                    "texto" =>
                        "Mostra alguma coisa na tela",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_quase"
                ],

                [
                    "texto" =>
                        "Mostra uma informação no console",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Cria uma variável",

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
        | PERGUNTA 1 — RESPOSTA PARCIAL
        |--------------------------------------------------------------------------
        */

        "pergunta_1_quase" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Boa, tá bem perto. Pensa num painel mostrando uma senha: ele não guarda a mensagem pra ti, ele mostra ela. No Java acontece algo parecido. Só falta descobrir onde esse recado aparece.",

            "natanael" =>
                "Tu já percebeu a parte principal: alguma informação está sendo apresentada. Pensa em um painel eletrônico de senhas. O sistema trabalha por trás, mas em algum momento precisa mostrar uma informação para quem está olhando. No nosso programa acontece uma ideia parecida. Agora precisamos descobrir qual é o espaço usado pelo Java para mostrar esse resultado.",

            "pergunta" =>
                "Onde essa informação aparece quando o programa está sendo executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "No console",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Dentro de uma variável",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
                ],

                [
                    "texto" =>
                        "No banco de dados",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — RESPOSTA INCORRETA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aí misturou duas funções kkkkk. Variável é mais tipo uma gaveta: tu guarda alguma coisa nela pra usar depois. O println não tá guardando Café numa gaveta. Ele tá tentando fazer outra coisa com essa palavra.",

            "natanael" =>
                "Vamos separar as duas ideias. Imagina uma variável como uma gaveta com uma etiqueta: tu coloca um valor dentro dela porque pretende guardar aquilo para usar depois. Nesse código não estamos criando nenhuma gaveta nem dando nome para um espaço de armazenamento. A palavra Café está sendo entregue diretamente para outro comando.",

            "codigo" => <<<'JAVA'
System.out.println("Café");
JAVA,

            "pergunta" =>
                "Se a palavra Café não está sendo guardada, o que parece mais provável que aconteça com ela?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ela aparece quando o programa executa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_quase"
                ],

                [
                    "texto" =>
                        "Ela vira uma variável",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
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
                "Suave kkkkk. Imagina que o programa quer te mandar um recado. É igual alguém escrever Café num painel pra tu conseguir ler. Agora olha o código de novo.",

            "natanael" =>
                "Vamos trazer isso para uma situação mais comum. Imagina um sistema de atendimento que precisa mostrar uma mensagem para a pessoa que está usando ele. Internamente o computador faz várias coisas, mas em algum momento precisa colocar aquela informação em um lugar onde possamos enxergá-la. Agora aplica essa ideia ao exemplo abaixo.",

            "codigo" => <<<'JAVA'
System.out.println("Café");
JAVA,

            "pergunta" =>
                "Quando essa linha executar, o que parece mais lógico acontecer com a palavra Café?",

            "opcoes" => [

                [
                    "texto" =>
                        "A palavra Café aparece",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_quase"
                ],

                [
                    "texto" =>
                        "A palavra Café fica guardada",

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
                "Agora muda uma coisinha...",

            "mensagem" =>
                "Até agora vimos uma palavra entre aspas. Mas olha o que acontece quando colocamos um número.",

            "codigo" => <<<'JAVA'
System.out.println(20);
JAVA,

            "pergunta" =>
                "O que tu acha que esse código faz?",

            "opcoes" => [

                [
                    "texto" =>
                        "Mostra o número 20",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Dá erro porque não é texto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
                ],

                [
                    "texto" =>
                        "Cria uma variável com valor 20",

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
                "Olha por outro lado: se o println fosse tipo um alto-falante do programa, ele conseguiria 'falar' só palavras? Aqui entregamos o número 20 pra ele. O que ele poderia fazer com esse valor?",

            "natanael" =>
                "Pensa em um painel que consegue apresentar diferentes informações. Em um momento ele pode mostrar um nome; em outro, pode mostrar o número de uma senha. A informação mudou de texto para número, mas a função do painel continua sendo apresentar aquilo que recebeu. Agora traz essa comparação para o println.",

            "pergunta" =>
                "Qual informação foi entregue ao comando nesse exemplo?",

            "opcoes" => [

                [
                    "texto" =>
                        "20",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Nada",

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
                "Pensa no println como a voz do programa. Se eu entrego 20 pra essa voz, ela tem qual informação pra mostrar?",

            "natanael" =>
                "Imagina que o println funciona como um mensageiro. O trabalho dele é receber uma informação e apresentá-la. Antes ele recebeu a palavra Olá. Agora recebeu diretamente um número. O mensageiro mudou ou só mudou aquilo que foi entregue para ele?",

            "pergunta" =>
                "Nesse exemplo, qual valor foi passado para o println?",

            "opcoes" => [

                [
                    "texto" =>
                        "20",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "O texto println",

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
                "E se tiver dois?",

            "mensagem" =>
                "Agora temos dois comandos sendo executados um depois do outro.",

            "codigo" => <<<'JAVA'
System.out.println("Java");
System.out.println("Café");
JAVA,

            "pergunta" =>
                "Como tu acha que esse resultado vai aparecer?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java Café na mesma linha",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
                ],

                [
                    "texto" =>
                        "Java em uma linha e Café na linha seguinte",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Não aparece nada",

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
                "Tem uma pegadinha pequena aí. Pensa numa conversa no WhatsApp: tu escreve Java, envia, depois manda Café em outra mensagem. O println faz algo parecido porque termina a saída e pula pra linha seguinte.",

            "natanael" =>
                "Vamos imaginar duas mensagens sendo escritas uma depois da outra. A primeira contém Java. Depois que ela termina, é como se alguém pressionasse Enter antes de escrever Café. Esse comportamento de avançar para a próxima linha faz parte do println.",

            "pergunta" =>
                "Se depois de Java acontece uma quebra de linha, onde Café vai aparecer?",

            "opcoes" => [

                [
                    "texto" =>
                        "Na linha de baixo",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Na mesma linha",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
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
                "Imagina escrevendo num bloco de notas: tu escreve Java e aperta Enter. Depois escreve Café. Como ficaria?",

            "natanael" =>
                "Vamos transformar o código em uma ação do dia a dia. Imagina que tu está digitando em um editor de texto. Primeiro escreve Java e, assim que termina, pressiona Enter. Depois escreve Café. O println possui justamente esse comportamento de seguir para uma nova linha depois da saída.",

            "pergunta" =>
                "Então como essas duas palavras ficam organizadas?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java e Café ficam em linhas diferentes",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Java e Café ficam na mesma linha",

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
                "Tem alguma coisa faltando...",

            "mensagem" =>
                "Esse código está quase igual aos anteriores, mas existe um pequeno detalhe faltando.",

            "codigo" => <<<'JAVA'
System.out.println("Estou aprendendo Java")
JAVA,

            "pergunta" =>
                "Consegue encontrar o que desapareceu?",

            "opcoes" => [

                [
                    "texto" =>
                        "Falta o ponto e vírgula",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Faltam as aspas",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
                ],

                [
                    "texto" =>
                        "Não tem nada faltando",

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
                "Pensa numa frase. Quando ela termina, normalmente tem um ponto pra mostrar que acabou. Muitas instruções simples em Java também precisam de um sinal no final. Compara com os códigos anteriores.",

            "natanael" =>
                "Uma comparação legal é pensar na pontuação de uma frase. Quando escrevemos uma frase, usamos um sinal para indicar que aquela ideia terminou. Em muitas instruções simples do Java também existe um símbolo que marca o encerramento da instrução. Nos exemplos anteriores ele aparecia logo depois do último parêntese.",

            "pergunta" =>
                "Qual símbolo estava no final dos outros exemplos?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ponto e vírgula ;",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Outro parêntese",

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
                "É tipo o ponto final da instrução. Olha depois do último ). Nos outros códigos sempre tinha um símbolo ali.",

            "natanael" =>
                "Foca somente no final da instrução. Assim como usamos pontuação para indicar que uma frase terminou, muitas instruções Java usam um símbolo específico para indicar o seu encerramento. Repara no que aparecia depois do último parêntese nos exemplos anteriores.",

            "pergunta" =>
                "Qual símbolo completa essa instrução?",

            "opcoes" => [

                [
                    "texto" =>
                        ";",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        ",",

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
                "Primeiro desafio concluído!",

            "robert" =>
                "Boa po kkkkk. Agora pensa assim: o println é tipo a voz do programa. Tu entrega uma informação pra ele e ela aparece no console. Se usar outro println, é como apertar Enter e continuar na linha de baixo. E o ; funciona tipo um ponto final de muitas instruções. Bora pra próxima.",

            "natanael" =>
                "Fechamos o primeiro desafio. Dá para guardar algumas comparações: o println funciona como uma forma do programa enviar um recado para quem está acompanhando sua execução; a informação colocada nos parênteses é aquilo que queremos apresentar; depois de cada println ocorre uma quebra de linha, parecido com pressionar Enter; e o ponto e vírgula marca o encerramento de muitas instruções simples. Agora vamos usar essa base para avançar para o próximo conceito.",

            "mensagem" =>
                "Tu concluiu a primeira Aula Conversada."

        ]

    ]

];