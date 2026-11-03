<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 5,

    "titulo" =>
        "Comparando informações",

    "assunto" =>
        "Operadores relacionais",

    "descricao" =>
        "Observe diferentes situações e descubra como o Java compara valores e responde se uma condição é verdadeira ou falsa.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        6,


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
                "Começando com uma regra...",

            "mensagem" =>
                "Imagina que um sistema precisa verificar se uma pessoa já atingiu determinada idade.",

            "codigo" => <<<'JAVA'
int idade = 18;

System.out.println(idade >= 18);
JAVA,

            "pergunta" =>
                "O que tu acha que aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "false, porque 18 não é maior que 18",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_igualdade"
                ],

                [
                    "texto" =>
                        "18",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_resultado"
                ],

                [
                    "texto" =>
                        "Não sei o que >= significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUSÃO COM IGUALDADE
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_igualdade" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A pegadinha tá no = junto com o >. Não é só 'maior que'. >= significa 'maior OU igual'. Então 18 também entra na regra.",

            "natanael" =>
                "Aqui o detalhe está nos dois símbolos trabalhando juntos. Se tivéssemos somente >, a pergunta seria se 18 é maior que 18, e isso seria falso. Porém usamos >=, que significa 'maior ou igual'. Portanto o próprio valor 18 também atende à condição.",

            "pergunta" =>
                "Pensa numa placa dizendo: entrada permitida para pessoas com 18 anos ou mais. Alguém com exatamente 18 pode entrar?",

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
                        "pergunta_1_erro_igualdade"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUSÃO COM O RESULTADO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_resultado" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "18 é o valor da variável, mas agora o Java tá fazendo uma pergunta sobre esse valor. É tipo perguntar '18 é maior ou igual a 18?'. A resposta não é outro número.",

            "natanael" =>
                "A variável realmente contém 18, mas a expressão não está pedindo para mostrar diretamente esse valor. Ela está fazendo uma comparação: 'idade é maior ou igual a 18?'. Comparações desse tipo produzem uma resposta lógica, como uma pergunta de sim ou não.",

            "pergunta" =>
                "Se a comparação estiver correta, qual resposta lógica o Java utiliza?",

            "opcoes" => [

                [
                    "texto" =>
                        "true",

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
                        "pergunta_1_erro_resultado"
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
                "Pensa assim: >= é tipo uma regra escrita '18 ou mais'. Se a pessoa tem 18, passou na comparação. E o Java responde essas comparações com true ou false.",

            "natanael" =>
                "Uma comparação simples é uma regra de idade mínima. Se um evento permite pessoas com 18 anos ou mais, alguém que possui exatamente 18 atende à regra. O operador >= representa justamente essa ideia: maior ou igual. Como a condição foi atendida, o Java responde com um valor lógico.",

            "pergunta" =>
                "Então idade >= 18, quando idade vale 18, resulta em quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "false",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_igualdade"
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
                "Agora olha o placar...",

            "mensagem" =>
                "Dois valores estão sendo comparados.",

            "codigo" => <<<'JAVA'
int timeA = 3;
int timeB = 1;

System.out.println(timeA > timeB);
JAVA,

            "pergunta" =>
                "Qual resultado aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "true, porque 3 é maior que 1",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "false, porque > significa menor que",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_direcao"
                ],

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_resultado"
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
        | PERGUNTA 2 — CONFUSÃO COM > E <
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_direcao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Esses dois símbolos confundem mesmo no começo kkkkk. Aqui > quer dizer 'maior que'. Então a pergunta é: 3 é maior que 1?",

            "natanael" =>
                "Vamos separar os símbolos. O operador > representa 'maior que', enquanto < representa 'menor que'. Nesse código estamos perguntando se o valor de timeA, que é 3, é maior que o valor de timeB, que é 1.",

            "codigo" => <<<'JAVA'
3 > 1
JAVA,

            "pergunta" =>
                "Pensando num placar, três gols é uma quantidade maior que um gol?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_direcao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUSÃO COM O VALOR EXIBIDO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_resultado" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "3 é o valor do timeA, só que o println tá recebendo a comparação inteira: timeA > timeB. Primeiro o Java resolve essa pergunta e só depois mostra o resultado.",

            "natanael" =>
                "O valor 3 pertence à variável timeA. Porém aquilo que foi entregue ao println é uma expressão completa: timeA > timeB. O Java primeiro avalia se essa comparação é verdadeira ou falsa e depois apresenta esse resultado.",

            "pergunta" =>
                "Se 3 realmente é maior que 1, a comparação resulta em quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_resultado"
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
                "Pensa no símbolo como uma boquinha aberta pro número maior. Em 3 > 1, ela abre pro 3. Então a comparação é verdadeira.",

            "natanael" =>
                "Uma forma visual de lembrar é observar que a abertura maior do símbolo fica voltada para o valor maior. Em 3 > 1, o lado aberto está voltado para 3. A expressão está afirmando que 3 é maior que 1.",

            "pergunta" =>
                "Então qual valor lógico aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "false",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_direcao"
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
                "Igual e diferente...",

            "mensagem" =>
                "Agora temos duas comparações usando o mesmo valor.",

            "codigo" => <<<'JAVA'
int pontos = 10;

System.out.println(pontos == 10);
System.out.println(pontos != 10);
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "true e depois false",

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
                        "pergunta_3_erro_invertido"
                ],

                [
                    "texto" =>
                        "10 e depois 10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_resultado"
                ],

                [
                    "texto" =>
                        "Não sei o que == e != significam",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — OPERADORES INVERTIDOS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_invertido" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aqui é quase leitura mesmo: == pergunta 'é igual?'. != pergunta 'é diferente?'. pontos vale 10. Então ele é igual a 10 e não é diferente de 10.",

            "natanael" =>
                "Vamos traduzir os dois operadores para frases. pontos == 10 pergunta se pontos é igual a 10. Como pontos vale 10, a resposta é verdadeira. Já pontos != 10 pergunta se pontos é diferente de 10. Como os valores são iguais, essa segunda comparação é falsa.",

            "pergunta" =>
                "Se pontos vale exatamente 10, a frase 'pontos é diferente de 10' é verdadeira?",

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
                        "pergunta_3_erro_invertido"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUSÃO COM O RESULTADO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_resultado" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "De novo, o 10 é só o valor usado na comparação. É tipo uma pergunta de prova com verdadeiro ou falso: '10 é igual a 10?' e depois '10 é diferente de 10?'.",

            "natanael" =>
                "Os números servem como valores que serão comparados, mas o resultado da comparação é lógico. Podemos transformar as linhas em duas perguntas: '10 é igual a 10?' e '10 é diferente de 10?'. Cada pergunta precisa ser respondida com verdadeiro ou falso.",

            "pergunta" =>
                "A primeira pergunta, 10 == 10, é verdadeira?",

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
                        "pergunta_3_erro_invertido"
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
                "Decora pela leitura por enquanto: == é 'é igual a?' e != é 'é diferente de?'. Com pontos valendo 10 fica fácil testar as duas.",

            "natanael" =>
                "Pensa como duas perguntas de conferência. O operador == verifica igualdade. O operador != verifica diferença. Como pontos contém exatamente 10, a primeira comparação confirma uma igualdade e a segunda tenta afirmar uma diferença que não existe.",

            "pergunta" =>
                "Então qual sequência faz sentido?",

            "opcoes" => [

                [
                    "texto" =>
                        "true e depois false",

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
                        "pergunta_3_erro_invertido"
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
                "Última comparação...",

            "mensagem" =>
                "Imagina um sistema que precisa avisar quando a temperatura está em 10 graus ou abaixo disso.",

            "codigo" => <<<'JAVA'
int temperatura = 10;

System.out.println(temperatura <= 10);
JAVA,

            "pergunta" =>
                "Qual resultado aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "false, porque 10 não é menor que 10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_igualdade"
                ],

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_resultado"
                ],

                [
                    "texto" =>
                        "Não sei o que <= significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ESQUECEU O IGUAL
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_igualdade" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Mesma pegadinha da primeira kkkkk. <= não significa só menor. Significa menor OU igual. Como a temperatura tá exatamente em 10, ela entra na condição.",

            "natanael" =>
                "Esse caso é parecido com a primeira comparação da aula. O operador <= combina duas possibilidades: um valor pode ser menor ou pode ser igual ao limite. Como temperatura vale exatamente 10, a parte 'igual' da comparação torna a condição verdadeira.",

            "pergunta" =>
                "Se o aviso vale para temperaturas de 10 graus ou menos, uma temperatura exatamente de 10 deve ativar o aviso?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_igualdade"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUSÃO COM RESULTADO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_resultado" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "10 é a temperatura. Mas o programa tá perguntando se ela é menor ou igual a 10. O resultado dessa pergunta é true ou false.",

            "natanael" =>
                "O número 10 é o valor utilizado na comparação. Entretanto, o println recebe a expressão temperatura <= 10. Primeiro o Java avalia essa condição e gera uma resposta lógica; depois essa resposta é apresentada.",

            "pergunta" =>
                "Se 10 atende à regra '10 ou menos', qual resposta lógica temos?",

            "opcoes" => [

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_resultado"
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
                "<= é 'menor ou igual'. Pensa numa promoção que vale pra produtos de 10 reais ou menos. Um produto que custa exatamente 10 entra, né?",

            "natanael" =>
                "Pensa em um limite de preço: 'produtos de 10 reais ou menos'. Isso inclui produtos de 8, 9 e também exatamente 10 reais. O operador <= representa essa mesma lógica, combinando menor que e igual a.",

            "pergunta" =>
                "Então temperatura <= 10, com temperatura valendo 10, resulta em quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "false",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_igualdade"
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
                "Comparações desbloqueadas!",

            "robert" =>
                "Boa po kkkkk. Agora tu já consegue fazer o Java responder perguntas sobre valores: > maior, < menor, >= maior ou igual, <= menor ou igual, == igual e != diferente. E lembra: comparação responde true ou false. Isso vai ser muito importante na próxima aula.",

            "natanael" =>
                "Fechamos os principais operadores relacionais. O Java pode verificar se um valor é maior (>), menor (<), maior ou igual (>=), menor ou igual (<=), igual (==) ou diferente (!=) de outro. Essas expressões produzem valores booleanos, ou seja, true ou false. Esse conhecimento será fundamental para o próximo passo, porque vamos começar a utilizar essas comparações para fazer o programa tomar decisões.",

            "mensagem" =>
                "Tu concluiu a quinta Aula Conversada."

        ]

    ]

];