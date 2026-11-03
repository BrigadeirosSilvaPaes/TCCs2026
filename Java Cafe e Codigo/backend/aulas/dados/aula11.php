<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 11,

    "titulo" =>
        "Executando antes de verificar",

    "assunto" =>
        "do while",

    "descricao" =>
        "Descubra como criar repetições que executam pelo menos uma vez antes de verificar se devem continuar.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        12,


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
                "Tem algo diferente aqui...",

            "mensagem" =>
                "Repara na posição da condição. Dessa vez ela aparece depois do bloco.",

            "codigo" => <<<'JAVA'
int numero = 5;

do {

    System.out.println(numero);

} while (numero < 3);
JAVA,

            "pergunta" =>
                "O que acontece quando esse código é executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Mostra 5 uma vez",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Não mostra nada porque 5 < 3 é false",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_condicao"
                ],

                [
                    "texto" =>
                        "Mostra 5 infinitamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_infinito"
                ],

                [
                    "texto" =>
                        "Não sei",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHOU QUE NÃO EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_condicao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Essa seria a lógica do while normal. Só que aqui tem do primeiro. O Java entra no bloco, mostra 5 e só DEPOIS pergunta se 5 < 3.",

            "natanael" =>
                "A condição realmente é falsa, mas existe uma diferença importante na ordem. No do while, o Java primeiro executa tudo que está dentro do bloco do e somente depois verifica a condição escrita no while. Por isso existe uma primeira execução mesmo quando a condição já começa falsa.",

            "pergunta" =>
                "Nesse código, o que acontece primeiro?",

            "opcoes" => [

                [
                    "texto" =>
                        "O bloco do é executado",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "A condição é testada antes de tudo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_condicao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHOU QUE É INFINITO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_infinito" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Ele consegue executar uma vez, mas depois encontra 5 < 3. Isso dá false. Aí já encerra, então não fica preso repetindo.",

            "natanael" =>
                "A primeira execução realmente acontece sem uma verificação anterior. Porém, quando ela termina, o Java avalia numero < 3. Como numero vale 5, a condição resulta em false e nenhuma segunda repetição ocorre.",

            "pergunta" =>
                "Depois da primeira execução, 5 < 3 resulta em quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "false",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_infinito"
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
                "Pensa que o do fala: faz primeiro, pergunta depois kkkkk. Ele mostra 5 e só então olha se deve repetir.",

            "natanael" =>
                "Imagina alguém recebendo a ordem: faça uma tentativa e, depois dela, confira se precisa tentar novamente. A primeira tentativa não depende da condição. A condição serve para decidir se haverá uma próxima.",

            "pergunta" =>
                "Então o bloco consegue executar pelo menos quantas vezes?",

            "opcoes" => [

                [
                    "texto" =>
                        "Uma vez",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Nenhuma vez",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_condicao"
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
                "É isso: faz primeiro e testa depois. Mostrou 5, testou 5 < 3, deu false e terminou.",

            "natanael" =>
                "O fluxo pode ser resumido assim: executar o bloco, verificar a condição e somente repetir se ela resultar em true. Nesse exemplo, depois da primeira execução a condição é false.",

            "pergunta" =>
                "Quantas vezes o número 5 aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Uma vez",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Nenhuma vez",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_condicao"
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
                "while ou do while?",

            "mensagem" =>
                "Agora compara as duas estruturas usando exatamente a mesma condição.",

            "codigo" => <<<'JAVA'
int numero = 10;

while (numero < 5) {
    System.out.println("while");
}

do {
    System.out.println("do while");
} while (numero < 5);
JAVA,

            "pergunta" =>
                "Qual mensagem aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Somente do while",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Somente while",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_invertido"
                ],

                [
                    "texto" =>
                        "As duas mensagens",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_duas"
                ],

                [
                    "texto" =>
                        "Nenhuma mensagem",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_nenhuma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — INVERTEU AS ESTRUTURAS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_invertido" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Tá invertido kkkkk. O while testa primeiro. Como 10 < 5 já é false, ele nem entra. O do while é que consegue entrar uma vez antes do teste.",

            "natanael" =>
                "A diferença está justamente no momento da verificação. O while verifica numero < 5 antes de executar seu bloco. Como a condição é falsa, ele não executa. O do while começa executando o bloco e só depois realiza essa mesma verificação.",

            "pergunta" =>
                "Qual das duas estruturas verifica a condição antes da primeira execução?",

            "opcoes" => [

                [
                    "texto" =>
                        "while",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "do while",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_invertido"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE AS DUAS EXECUTAM
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_duas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A condição é falsa pros dois. Só que um deles executa antes de descobrir isso: o do while.",

            "natanael" =>
                "Ambas utilizam a mesma condição falsa. A diferença não está no resultado da comparação, mas no momento em que ela é feita. O while descobre que é falsa antes de executar. O do while só descobre depois de executar uma primeira vez.",

            "pergunta" =>
                "Qual estrutura consegue executar antes de descobrir que a condição é false?",

            "opcoes" => [

                [
                    "texto" =>
                        "do while",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "while",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_duas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE NENHUMA EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_nenhuma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "No while realmente não aparece nada. Mas lembra da regra nova: do while faz primeiro e pergunta depois.",

            "natanael" =>
                "Essa resposta estaria correta se as duas estruturas verificassem a condição antes da execução. Porém o do while possui uma primeira execução garantida, porque sua verificação acontece no final.",

            "pergunta" =>
                "O bloco do do while executa antes ou depois da primeira verificação?",

            "opcoes" => [

                [
                    "texto" =>
                        "Antes",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Depois",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_nenhuma"
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
                "Resumo: while = testa → talvez executa. do while = executa → depois testa.",

            "natanael" =>
                "Podemos organizar assim: no while, primeiro temos a pergunta e depois, se a resposta for true, a ação. No do while, primeiro ocorre a ação e só depois surge a pergunta sobre continuar.",

            "pergunta" =>
                "Nesse exemplo, qual bloco consegue mostrar uma mensagem?",

            "opcoes" => [

                [
                    "texto" =>
                        "O do while",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "O while",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_invertido"
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
                "Tem um detalhe faltando...",

            "mensagem" =>
                "Esse código está quase correto, mas falta um símbolo importante no final da estrutura.",

            "codigo" => <<<'JAVA'
int numero = 1;

do {

    System.out.println(numero);

    numero++;

} while (numero <= 3)
JAVA,

            "pergunta" =>
                "O que está faltando depois da condição?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ponto e vírgula ;",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Outra chave }",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_chave"
                ],

                [
                    "texto" =>
                        "A palavra break",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_break"
                ],

                [
                    "texto" =>
                        "Não falta nada",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_nada"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE FALTA CHAVE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_chave" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A chave do bloco já fechou antes do while. O detalhe tá lá no finalzinho, depois do parêntese.",

            "natanael" =>
                "O bloco iniciado depois de do já possui uma chave de abertura e uma de fechamento. Depois dele temos while (numero <= 3). A estrutura do do while possui ainda um pequeno símbolo de encerramento após esse parêntese.",

            "pergunta" =>
                "Nos exemplos anteriores de do while, qual símbolo aparecia depois do último parêntese?",

            "opcoes" => [

                [
                    "texto" =>
                        ";",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "}",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_chave"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE PRECISA DE BREAK
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_break" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "break não é obrigatório aqui. O próprio while decide quando repetir ou parar. O que falta é fechar a instrução no final.",

            "natanael" =>
                "O break pode ser utilizado em algumas situações de repetição, mas não faz parte obrigatória da sintaxe do do while. A condição já controla quando o laço continuará. O detalhe ausente é o encerramento da instrução while.",

            "pergunta" =>
                "Qual símbolo normalmente encerra uma instrução Java?",

            "opcoes" => [

                [
                    "texto" =>
                        ";",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "break",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_break"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — NÃO VIU O ERRO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_nada" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Olha exatamente depois de while (numero <= 3). No do while tem uma coisinha que não aparece no while comum.",

            "natanael" =>
                "Compare o encerramento dessa estrutura com os exemplos anteriores. No do while, a parte while (condicao) aparece depois do bloco e termina como uma instrução, portanto existe um símbolo após o parêntese final.",

            "pergunta" =>
                "O que deveria existir depois do último )?",

            "opcoes" => [

                [
                    "texto" =>
                        ";",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Nada",

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
                "É o ; mesmo. Fica while (numero <= 3);. No do while ele faz parte da estrutura.",

            "natanael" =>
                "Exatamente. Diferentemente do while comum, a estrutura do while termina com ponto e vírgula depois da condição.",

            "codigo" => <<<'JAVA'
do {

    System.out.println(numero);

    numero++;

} while (numero <= 3);
JAVA,

            "pergunta" =>
                "Qual é a forma correta de terminar esse do while?",

            "opcoes" => [

                [
                    "texto" =>
                        "while (numero <= 3);",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "while (numero <= 3)",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_nada"
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
                "Agora acompanha o valor da variável durante cada repetição.",

            "codigo" => <<<'JAVA'
int tentativas = 1;

do {

    System.out.println(
        "Tentativa " + tentativas
    );

    tentativas++;

} while (tentativas <= 3);

System.out.println("Fim");
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Tentativa 1, Tentativa 2, Tentativa 3 e depois Fim",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Tentativa 1 e depois Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_uma"
                ],

                [
                    "texto" =>
                        "Tentativa 1, 2, 3 e 4 antes de Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_quatro"
                ],

                [
                    "texto" =>
                        "Fica repetindo para sempre",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_infinito"
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
                "Depois da primeira tentativa, tentativas++ faz 1 virar 2. Aí o Java pergunta 2 <= 3. Como dá true, vai de novo.",

            "natanael" =>
                "Depois da primeira execução a variável é incrementada e passa para 2. Somente então a condição é verificada. Como 2 <= 3 é verdadeira, uma nova repetição acontece.",

            "pergunta" =>
                "Quando tentativas vale 2, a condição tentativas <= 3 ainda é verdadeira?",

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
        | PERGUNTA 4 — ACHA QUE MOSTRA 4
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_quatro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Depois de mostrar Tentativa 3, o ++ realmente faz virar 4. Só que aí vem a pergunta: 4 <= 3? False. Então não existe Tentativa 4.",

            "natanael" =>
                "A variável chega ao valor 4, mas isso acontece depois de mostrar Tentativa 3. Em seguida a condição 4 <= 3 é verificada e resulta em false. O bloco não inicia novamente com esse valor.",

            "pergunta" =>
                "Quando tentativas chega em 4, existe uma nova repetição?",

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
                        "pergunta_4_erro_quatro"
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
                "Tem tentativas++ ali justamente mudando o valor. 1 vira 2, 2 vira 3, 3 vira 4. Quando vira 4 a condição quebra.",

            "natanael" =>
                "O laço possui uma alteração que permite que ele termine. A cada repetição, tentativas++ aproxima o valor do ponto em que a condição será falsa. Quando chega a 4, não existe mais repetição.",

            "pergunta" =>
                "Qual instrução evita que o valor fique sempre em 1?",

            "opcoes" => [

                [
                    "texto" =>
                        "tentativas++;",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "System.out.println",

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
                "Segue o caminho: mostra 1, vira 2 e testa. Mostra 2, vira 3 e testa. Mostra 3, vira 4 e testa. 4 <= 3 dá false. Aí vai pro Fim.",

            "natanael" =>
                "A sequência completa é: primeira execução com 1, incremento para 2 e condição verdadeira; segunda execução com 2, incremento para 3 e condição verdadeira; terceira execução com 3, incremento para 4 e condição falsa. Depois disso o fluxo continua fora do laço.",

            "pergunta" =>
                "Qual sequência está correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "1 → 2 → 3 → Fim",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "1 → 2 → 3 → 4 → Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_quatro"
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
                "Do while desbloqueado!",

            "robert" =>
                "Boa po kkkkk. A diferença principal é fácil: while pergunta primeiro e depois executa. do while executa primeiro e pergunta depois. Por isso o do while sempre roda pelo menos uma vez. E lembra do ; depois do while no final. Agora bora conhecer um jeito mais compacto de fazer repetições com contador.",

            "natanael" =>
                "Fechamos a segunda estrutura de repetição. O while verifica sua condição antes da primeira execução e, por isso, pode executar zero vezes. Já o do while executa o bloco primeiro e verifica a condição somente depois, garantindo pelo menos uma execução. Também vimos que a estrutura termina com ponto e vírgula após a condição. Na próxima aula vamos conhecer o for, que reúne em uma única estrutura a inicialização, a condição e a atualização de um contador.",

            "mensagem" =>
                "Tu concluiu a décima primeira Aula Conversada."

        ]

    ]

];