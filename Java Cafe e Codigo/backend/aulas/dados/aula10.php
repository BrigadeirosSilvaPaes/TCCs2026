<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 10,

    "titulo" =>
        "Repetindo enquanto for necessário",

    "assunto" =>
        "while",

    "descricao" =>
        "Descubra como o Java pode repetir instruções enquanto uma condição continuar sendo verdadeira.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        11,


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
                "Uma tarefa que se repete...",

            "mensagem" =>
                "Olha o valor inicial do contador e depois acompanha a condição do while.",

            "codigo" => <<<'JAVA'
int contador = 1;

while (contador <= 3) {

    System.out.println(contador);

    contador++;
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
                        "pergunta_1_erro_uma_vez"
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
                        "Não sei como o while funciona",

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

        "pergunta_1_erro_uma_vez" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se fosse só uma vez nem precisava do while kkkkk. Depois de mostrar 1, contador++ faz ele virar 2. Aí o programa volta e testa a condição de novo.",

            "natanael" =>
                "O detalhe principal é que o fluxo retorna para a condição depois de executar o bloco. Na primeira passagem contador vale 1. Depois contador++ aumenta esse valor para 2. Como 2 ainda satisfaz contador <= 3, o bloco pode ser executado novamente.",

            "pergunta" =>
                "Depois da primeira repetição, quanto vale contador?",

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
                        "pergunta_1_erro_uma_vez"
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
                "O contador até chega em 4, mas tem uma diferença: quando ele vira 4, o Java testa 4 <= 3. Dá false. Então ele sai antes de imprimir o 4.",

            "natanael" =>
                "O valor 4 realmente aparece internamente no contador após a terceira repetição. Porém, antes de executar novamente o conteúdo do while, a condição é verificada. Como 4 <= 3 é false, o bloco não é executado outra vez.",

            "pergunta" =>
                "Quando contador vale 4, a condição contador <= 3 é verdadeira?",

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
                "Pensa numa catraca: enquanto o contador for 3 ou menos, ela deixa passar mais uma repetição. Mostra o número, aumenta 1 e volta pra testar de novo.",

            "natanael" =>
                "Imagina uma tarefa com a regra 'continue enquanto o número for menor ou igual a 3'. O programa verifica essa regra, executa a tarefa, altera o contador e volta para conferir novamente se ainda pode continuar.",

            "pergunta" =>
                "Se começa em 1 e aumenta de um em um, quais valores conseguem passar pela regra <= 3?",

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
                        "pergunta_1_erro_uma_vez"
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
                "Segue o ciclo: testa → mostra → aumenta → volta. 1 passa, 2 passa, 3 passa. Quando chega em 4, para.",

            "natanael" =>
                "Podemos acompanhar passo a passo: 1 <= 3 é true, então imprime 1. Depois vira 2. A condição continua verdadeira e imprime 2. Em seguida vira 3 e imprime 3. Finalmente passa para 4, mas a condição se torna falsa e o laço termina.",

            "pergunta" =>
                "Então qual sequência aparece?",

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
                "E se a regra já começar falsa?",

            "mensagem" =>
                "Agora repara no valor inicial antes mesmo de o while começar.",

            "codigo" => <<<'JAVA'
int cafe = 0;

while (cafe > 0) {

    System.out.println("Servindo café");

    cafe--;
}

System.out.println("Acabou o café");
JAVA,

            "pergunta" =>
                "O que aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Somente Acabou o café",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Servindo café e depois Acabou o café",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_executa"
                ],

                [
                    "texto" =>
                        "Servindo café infinitamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_infinito"
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
        | PERGUNTA 2 — ACHA QUE O BLOCO EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_executa" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O while confere a condição ANTES de entrar. cafe já começa em 0. A pergunta é 0 > 0? Não. Então nem entra no bloco.",

            "natanael" =>
                "Uma característica importante do while é que sua condição é verificada antes da primeira execução do bloco. Como cafe começa valendo 0, a expressão cafe > 0 já resulta em false e o conteúdo do laço é ignorado.",

            "pergunta" =>
                "0 é maior que 0?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_executa"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE É INFINITO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_infinito" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Nem dá tempo de começar kkkkk. Pra ficar infinito, primeiro a condição precisaria deixar entrar. Aqui 0 > 0 já é false.",

            "natanael" =>
                "Um laço infinito exige que a condição permaneça verdadeira durante as repetições. Neste caso, porém, a condição não chega sequer a ser verdadeira uma vez. O bloco nunca inicia.",

            "pergunta" =>
                "O programa entra no while pelo menos uma vez?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_infinito"
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
                "Pensa numa cafeteira com a regra: enquanto tiver café, serve. Se ela já começa vazia, não serve nem uma xícara. Só continua pro código de baixo.",

            "natanael" =>
                "Imagine alguém recebendo a instrução 'enquanto houver café na garrafa, continue servindo'. Se a garrafa já estiver vazia antes da primeira tentativa, a ação de servir não acontece nenhuma vez. O fluxo segue para a instrução seguinte.",

            "pergunta" =>
                "Qual linha está fora do while e será executada normalmente?",

            "opcoes" => [

                [
                    "texto" =>
                        "Acabou o café",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Servindo café",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_executa"
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
                "Tem alguma coisa perigosa aqui...",

            "mensagem" =>
                "Esse código parece parecido com o primeiro, mas existe uma diferença importante.",

            "codigo" => <<<'JAVA'
int contador = 1;

while (contador <= 3) {

    System.out.println(contador);

}
JAVA,

            "pergunta" =>
                "Qual é o problema desse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "O contador nunca muda e o while pode repetir para sempre",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "O while executa apenas três vezes automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_tres"
                ],

                [
                    "texto" =>
                        "O contador aumenta sozinho",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_sozinho"
                ],

                [
                    "texto" =>
                        "Não tem problema nenhum",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_nada"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE PARA EM TRÊS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_tres" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O while não conta três repetições sozinho. Ele só olha a condição. Se contador continuar valendo 1, 1 <= 3 vai continuar true pra sempre.",

            "natanael" =>
                "O número 3 faz parte da condição, mas não representa automaticamente a quantidade de repetições. O while continuará enquanto contador <= 3 for verdadeiro. Como contador não sofre alteração, seu valor permanece 1 e a condição continua verdadeira.",

            "pergunta" =>
                "Depois da primeira repetição, quanto vale contador nesse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "1",

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
                        "pergunta_3_erro_sozinho"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE AUMENTA SOZINHO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_sozinho" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Variável não se atualiza por telepatia kkkkk. Se tu quer que contador aumente, precisa mandar ele aumentar, tipo contador++.",

            "natanael" =>
                "O valor de uma variável só muda quando alguma instrução modifica seu conteúdo. O while não altera contador automaticamente. Seria necessário incluir uma operação como contador++ para que o valor avance.",

            "pergunta" =>
                "Qual instrução poderia aumentar contador em uma unidade?",

            "opcoes" => [

                [
                    "texto" =>
                        "contador++;",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "while++;",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_sozinho"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — NÃO VIU PROBLEMA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_nada" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Faz uma simulação. contador começa em 1. Mostra 1. Volta pro while. Continua 1. Mostra 1 de novo. E de novo. E de novo... aí complica kkkkk.",

            "natanael" =>
                "Vamos executar mentalmente. contador inicia em 1. A condição 1 <= 3 é verdadeira e o número 1 é exibido. Como nenhuma instrução altera contador, voltamos à condição ainda com o valor 1. A mesma situação se repete indefinidamente.",

            "pergunta" =>
                "A condição algum dia consegue se tornar false sozinha?",

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
                        "pergunta_3_erro_nada"
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
                "Pra esse loop conseguir terminar, alguma coisa precisa fazer contador sair de 1, depois 2, 3 e finalmente 4. Aí 4 <= 3 vira false.",

            "natanael" =>
                "Para que esse laço termine, precisamos alterar alguma informação envolvida na condição. Acrescentando contador++, o valor progride até chegar a 4. Nesse momento a condição contador <= 3 deixa de ser verdadeira.",

            "codigo" => <<<'JAVA'
int contador = 1;

while (contador <= 3) {

    System.out.println(contador);

    contador++;
}
JAVA,

            "pergunta" =>
                "Qual parte evita que esse exemplo fique repetindo para sempre?",

            "opcoes" => [

                [
                    "texto" =>
                        "contador++;",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "System.out.println(contador);",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_sozinho"
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
                "Agora vamos acompanhar uma situação em que uma quantidade vai diminuindo até acabar.",

            "codigo" => <<<'JAVA'
int tentativas = 3;

while (tentativas > 0) {

    System.out.println(
        "Tentativas restantes: " + tentativas
    );

    tentativas--;
}

System.out.println("Fim");
JAVA,

            "pergunta" =>
                "Qual sequência representa melhor o funcionamento desse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "Mostra 3, depois 2, depois 1 e então Fim",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Mostra 3, 2, 1 e 0 antes de Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_zero"
                ],

                [
                    "texto" =>
                        "Mostra somente 3 e depois Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_uma"
                ],

                [
                    "texto" =>
                        "Fica repetindo 3 para sempre",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_infinito"
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
                "O zero aparece no valor da variável, mas não chega no println de dentro. Quando tentativas vira 0, a condição pergunta 0 > 0. Dá false e sai.",

            "natanael" =>
                "Depois de exibir 1, tentativas-- reduz o valor para 0. Porém antes de iniciar outra repetição o Java verifica tentativas > 0. Como 0 > 0 é false, o bloco não executa novamente e o número 0 não é exibido dentro do while.",

            "pergunta" =>
                "Quando tentativas vale 0, o bloco do while ainda executa?",

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
        | PERGUNTA 4 — ACHA QUE EXECUTA UMA VEZ
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_uma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Depois do 3, tentativas-- vira 2. E 2 ainda é maior que 0. Então o while continua.",

            "natanael" =>
                "Após a primeira execução, tentativas passa de 3 para 2. Como a condição 2 > 0 permanece verdadeira, existe uma nova repetição. O processo continua até o valor chegar a zero.",

            "pergunta" =>
                "Quando tentativas vale 2, a condição tentativas > 0 é verdadeira?",

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
                        "pergunta_4_erro_uma"
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
                "Dessa vez tem uma mudança sim: tentativas--. Ela vai fazendo 3 virar 2, depois 1 e depois 0. Quando chega em 0, acaba.",

            "natanael" =>
                "Esse exemplo possui justamente a alteração que faltava no laço infinito anterior. A instrução tentativas-- reduz o valor a cada repetição, aproximando-o de uma situação em que a condição será falsa.",

            "pergunta" =>
                "Qual instrução está fazendo a condição se aproximar de false?",

            "opcoes" => [

                [
                    "texto" =>
                        "tentativas--;",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "while (tentativas > 0)",

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
                "É uma contagem regressiva: começa em 3, mostra, diminui pra 2; mostra, diminui pra 1; mostra, diminui pra 0. Aí 0 > 0 dá false e sai.",

            "natanael" =>
                "Acompanhe como uma contagem regressiva. O laço executa com 3, depois com 2 e finalmente com 1. Após essa última passagem a variável chega a 0. Como a condição exige um valor maior que zero, o laço termina e o programa segue para Fim.",

            "pergunta" =>
                "Qual sequência será exibida antes de Fim?",

            "opcoes" => [

                [
                    "texto" =>
                        "3 → 2 → 1",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "3 → 2 → 1 → 0",

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
                "Repetições desbloqueadas!",

            "robert" =>
                "Boa po kkkkk. while é basicamente: enquanto a condição for true, continua. Quando virar false, sai. E fica esperto com loop infinito: se a condição depende de uma variável e tu nunca muda ela, o programa pode ficar preso repetindo. Na próxima tem uma diferença pequena mas importante: um laço que executa primeiro e pergunta depois.",

            "natanael" =>
                "Fechamos a primeira estrutura de repetição. O while verifica sua condição antes de cada execução e repete o bloco enquanto ela permanecer true. Por isso ele pode executar várias vezes ou até mesmo nenhuma vez. Também vimos que é fundamental modificar corretamente os valores envolvidos na condição para que o laço tenha uma possibilidade de terminar. Na próxima aula vamos conhecer uma estrutura semelhante, mas com uma diferença importante: nela o bloco é executado antes da primeira verificação.",

            "mensagem" =>
                "Tu concluiu a décima Aula Conversada."

        ]

    ]

];