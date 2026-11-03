<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 23,

    "titulo" =>
        "Quando algo dá errado durante a execução",

    "assunto" =>
        "Exceções",

    "descricao" =>
        "Descubra como o Java representa problemas que acontecem enquanto o programa está sendo executado.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        24,


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
                "O programa começou normalmente...",

            "mensagem" =>
                "Esse código consegue começar a execução, mas existe um problema no meio do caminho.",

            "codigo" => <<<'JAVA'
System.out.println("Início");

int divisor = 0;

int resultado = 10 / divisor;

System.out.println(resultado);

System.out.println("Fim");
JAVA,

            "pergunta" =>
                "O que tu acha que acontece quando o Java tenta fazer 10 / 0?",

            "opcoes" => [

                [
                    "texto" =>
                        "O programa encontra um problema durante a execução",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "resultado recebe 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_zero"
                ],

                [
                    "texto" =>
                        "O Java ignora a divisão e continua normalmente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ignora"
                ],

                [
                    "texto" =>
                        "Não sei o que acontece",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE RETORNA ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_zero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não rola esse zero salvador kkkkk. Em uma divisão inteira, tentar dividir por zero gera um problema durante a execução.",

            "natanael" =>
                "A operação não consegue produzir um resultado inteiro válido. Em vez de armazenar zero na variável resultado, o Java identifica uma situação excepcional durante a execução.",

            "pergunta" =>
                "Então resultado consegue receber normalmente um número?",

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
                        "Sim, recebe 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_zero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE IGNORA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_ignora" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O Java não olha pra divisão por zero e fala 'deixa quieto' kkkkk. Ele tenta executar a linha e o problema acontece ali.",

            "natanael" =>
                "A instrução não é ignorada automaticamente. Quando o fluxo chega à divisão, o Java tenta executá-la. Como a operação não é válida nesse contexto, uma exceção é produzida.",

            "pergunta" =>
                "A linha problemática chega a ser executada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim, e nela acontece o problema",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Não, o Java pula a linha sozinho",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ignora"
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
                "Pensa que o programa tá andando linha por linha. Ele mostra Início, chega em 10 / 0 e bate numa situação que não consegue continuar normalmente.",

            "natanael" =>
                "Imagine o fluxo como uma sequência de etapas. As primeiras instruções são executadas normalmente. Quando a divisão por zero é alcançada, surge um problema durante a execução e o fluxo normal é interrompido.",

            "pergunta" =>
                "O problema acontece antes ou durante a execução do programa?",

            "opcoes" => [

                [
                    "texto" =>
                        "Durante a execução",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Depois que o programa já terminou",

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
                "Esse tipo de problema que aparece enquanto o programa tá rodando pode ser representado por uma exceção.",

            "natanael" =>
                "Quando determinadas situações anormais acontecem durante a execução, o Java pode representá-las através de exceções. Elas indicam que o fluxo encontrou um problema que precisa ser tratado ou propagado.",

            "pergunta" =>
                "Como chamamos esse tipo de situação no Java?",

            "opcoes" => [

                [
                    "texto" =>
                        "Exceção",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Uma repetição",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ignora"
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
                "O problema também possui um tipo...",

            "mensagem" =>
                "Quando a divisão inteira por zero acontece, o Java identifica essa situação com um nome específico.",

            "codigo" => <<<'JAVA'
int divisor = 0;

int resultado =
    10 / divisor;
JAVA,

            "pergunta" =>
                "Qual exceção está relacionada a essa divisão inteira por zero?",

            "opcoes" => [

                [
                    "texto" =>
                        "ArithmeticException",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "NumberFormatException",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_tipo"
                ],

                [
                    "texto" =>
                        "ArrayList",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_tipo"
                ],

                [
                    "texto" =>
                        "Não sabia que existiam tipos diferentes",

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

        "pergunta_2_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O nome dá uma pista: Arithmetic lembra operação matemática. Divisão inteira por zero entra nesse tipo de problema.",

            "natanael" =>
                "As exceções possuem tipos diferentes de acordo com a situação. Uma divisão inteira por zero está relacionada a uma operação aritmética inválida, sendo representada por ArithmeticException.",

            "pergunta" =>
                "Qual nome lembra um problema aritmético?",

            "opcoes" => [

                [
                    "texto" =>
                        "ArithmeticException",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "NumberFormatException",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_tipo"
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
                "Sim. Não existe uma exceção única pra tudo. O Java consegue diferenciar situações. Nesse caso é ArithmeticException.",

            "natanael" =>
                "Os nomes ajudam a identificar a origem do problema. ArithmeticException está ligada a determinadas operações aritméticas inválidas durante a execução.",

            "pergunta" =>
                "Então 10 / 0 com inteiros pode produzir qual tipo?",

            "opcoes" => [

                [
                    "texto" =>
                        "ArithmeticException",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_tipo"
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
                "Outro problema, outro tipo...",

            "mensagem" =>
                "Agora não existe divisão. O programa está tentando transformar um texto em número inteiro.",

            "codigo" => <<<'JAVA'
String texto =
    "Java";

int numero =
    Integer.parseInt(texto);

System.out.println(numero);
JAVA,

            "pergunta" =>
                "O texto \"Java\" consegue ser convertido normalmente para int?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não, ocorre NumberFormatException",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Sim, o valor vira 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_zero"
                ],

                [
                    "texto" =>
                        "Sim, porque toda String pode virar int",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_string"
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
        | PERGUNTA 3 — ACHA QUE VIRA ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_zero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "De novo o zero tentando salvar tudo kkkkk. parseInt precisa receber um texto que realmente represente um inteiro.",

            "natanael" =>
                "Integer.parseInt espera encontrar uma representação textual de um número inteiro. Como \"Java\" não possui esse formato, a operação não consegue produzir normalmente um valor numérico.",

            "pergunta" =>
                "\"Java\" representa um número inteiro?",

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
                        "Sim, representa 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_zero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE TODA STRING CONVERTE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_string" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "String pode ter qualquer texto. \"25\" dá pra converter pra inteiro. \"Java\" não tem formato de número.",

            "natanael" =>
                "O fato de uma informação ser String não significa que ela represente um valor numérico. Uma String como \"25\" possui formato compatível com um inteiro, enquanto \"Java\" não possui.",

            "pergunta" =>
                "Qual dessas Strings parece representar um número?",

            "opcoes" => [

                [
                    "texto" =>
                        "\"25\"",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "\"Java\"",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_string"
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
                "Quando parseInt tenta converter um texto que não parece um número, aparece outro tipo de exceção: NumberFormatException.",

            "natanael" =>
                "Nesse caso o problema está no formato da informação utilizada na conversão. Por isso a exceção correspondente é chamada NumberFormatException.",

            "pergunta" =>
                "Qual exceção combina com um formato numérico inválido?",

            "opcoes" => [

                [
                    "texto" =>
                        "NumberFormatException",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "ArithmeticException",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_tipo"
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
                "E o restante do programa?",

            "mensagem" =>
                "Agora acompanha o fluxo quando uma exceção acontece e ainda não existe nenhum tratamento.",

            "codigo" => <<<'JAVA'
System.out.println("Antes");

String texto =
    "café";

int numero =
    Integer.parseInt(texto);

System.out.println(
    "Depois"
);
JAVA,

            "pergunta" =>
                "A mensagem \"Depois\" será executada normalmente?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não, a exceção interrompe o fluxo normal",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Sim, porque o Java ignora a exceção",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_ignora"
                ],

                [
                    "texto" =>
                        "Sim, porque numero recebe 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_zero"
                ],

                [
                    "texto" =>
                        "As duas mensagens aparecem ao mesmo tempo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_fluxo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — IGNORA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_ignora" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Sem nenhum tratamento, o Java não simplesmente esquece a exceção. O fluxo normal para naquele problema.",

            "natanael" =>
                "Como ainda não existe nenhuma estrutura preparada para tratar NumberFormatException, ela permanece sem tratamento e interrompe a continuação normal daquele fluxo.",

            "pergunta" =>
                "Existe algum tratamento nesse código?",

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
                        "pergunta_4_erro_ignora"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_zero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "parseInt não consegue transformar café em inteiro e não coloca zero no lugar. A exceção acontece antes dessa atribuição terminar.",

            "natanael" =>
                "A conversão não consegue produzir um inteiro válido. Portanto numero não recebe simplesmente um valor padrão. A NumberFormatException acontece durante a tentativa.",

            "pergunta" =>
                "A conversão de \"café\" para int é válida?",

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
        | PERGUNTA 4 — FLUXO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_fluxo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O programa vai na ordem. Mostra Antes, tenta converter café, dá exceção e não chega normalmente no println Depois.",

            "natanael" =>
                "O fluxo ocorre sequencialmente. A primeira mensagem é exibida. Depois ocorre a tentativa de conversão. Como a exceção não possui tratamento nesse exemplo, a instrução seguinte não é alcançada normalmente.",

            "pergunta" =>
                "Em qual linha o fluxo encontra o problema?",

            "opcoes" => [

                [
                    "texto" =>
                        "Integer.parseInt(texto)",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "System.out.println(\"Depois\")",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_fluxo"
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
                "Sem tratamento fica assim: Antes → tenta converter → NumberFormatException → o fluxo normal é interrompido.",

            "natanael" =>
                "Já conseguimos identificar o problema e seu tipo. O que ainda falta é definir como o programa deve responder quando essa situação acontece, evitando que a execução simplesmente seja interrompida.",

            "pergunta" =>
                "Então precisamos de uma forma de quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "Tratar a exceção",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Ignorar todos os problemas",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_ignora"
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
                "Exceções identificadas!",

            "robert" =>
                "Boa po kkkkk. Agora tu já sabe reconhecer a ideia: às vezes o programa começa normal e encontra um problema durante a execução. O Java representa essas situações com exceções, e existem tipos diferentes, tipo ArithmeticException e NumberFormatException. Sem tratamento, a execução pode parar naquele ponto. Então falta descobrir como falar pro programa: se isso der problema, faz outra coisa em vez de simplesmente quebrar. É exatamente o próximo passo.",

            "natanael" =>
                "Fechamos a introdução às exceções. Elas representam situações problemáticas que podem surgir durante a execução do programa. Também vimos que diferentes situações possuem tipos específicos, como ArithmeticException para determinados problemas aritméticos e NumberFormatException para conversões numéricas inválidas. Quando uma exceção não é tratada, ela pode interromper o fluxo normal da execução. Na próxima aula vamos aprender como utilizar try e catch para definir uma resposta quando esses problemas acontecerem.",

            "mensagem" =>
                "Tu concluiu a vigésima terceira Aula Conversada."

        ]

    ]

];