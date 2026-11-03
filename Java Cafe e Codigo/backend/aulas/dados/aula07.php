<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 7,

    "titulo" =>
        "Dois caminhos possíveis",

    "assunto" =>
        "if e else",

    "descricao" =>
        "Descubra como o Java escolhe entre dois caminhos diferentes quando uma condição é verdadeira ou falsa.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        8,


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
                "Agora existem dois caminhos...",

            "mensagem" =>
                "Na aula anterior, quando a condição do if era falsa, o bloco simplesmente era ignorado. Agora apareceu uma nova parte.",

            "codigo" => <<<'JAVA'
int idade = 16;

if (idade >= 18) {
    System.out.println("Entrada permitida");
} else {
    System.out.println("Entrada não permitida");
}
JAVA,

            "pergunta" =>
                "Qual mensagem vai aparecer nesse exemplo?",

            "opcoes" => [

                [
                    "texto" =>
                        "Entrada não permitida",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Entrada permitida",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_condicao"
                ],

                [
                    "texto" =>
                        "As duas mensagens aparecem",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_duas"
                ],

                [
                    "texto" =>
                        "Não sei o que else faz",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ERRO NA CONDIÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_condicao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Olha primeiro pra condição. idade vale 16 e a regra pede 18 ou mais. Então o if dá false. Se o primeiro caminho não serve, o programa vai pro else.",

            "natanael" =>
                "Vamos avaliar a condição antes de olhar as mensagens. idade vale 16 e a expressão pergunta se 16 é maior ou igual a 18. Como isso é falso, o bloco do if não será executado. Nesse caso, o Java segue para o caminho alternativo representado pelo else.",

            "pergunta" =>
                "16 atende à regra de 18 anos ou mais?",

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
                        "pergunta_1_erro_condicao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE OS DOIS EXECUTAM
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_duas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não rodam os dois kkkkk. É tipo uma bifurcação: ou vai pra esquerda ou pra direita. Se o if executa, o else não. Se o if não executa, aí entra o else.",

            "natanael" =>
                "O if e o else representam caminhos alternativos. Podemos imaginar uma bifurcação em uma estrada: ao escolher um caminho, o outro deixa de ser seguido naquela decisão. Quando a condição é verdadeira, executamos o bloco do if. Quando é falsa, executamos o bloco do else.",

            "pergunta" =>
                "Se a condição deu false, qual caminho deve ser executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "O else",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Os dois",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_duas"
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
                "Pensa numa porta com duas placas: se tu tem 18 ou mais, entra. Senão, recebe outra resposta. O else é basicamente esse 'senão'.",

            "natanael" =>
                "Vamos traduzir literalmente a ideia. O if pode ser entendido como 'se'. Já o else funciona como 'senão'. Então podemos ler assim: se a idade for maior ou igual a 18, mostra Entrada permitida; senão, mostra Entrada não permitida.",

            "pergunta" =>
                "Como idade vale 16, qual caminho parece fazer sentido?",

            "opcoes" => [

                [
                    "texto" =>
                        "O caminho do else",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "O caminho do if",

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
                "Fica simples assim: if = se. else = senão. Se idade >= 18 deu false, sobra o senão.",

            "natanael" =>
                "A estrutura representa uma decisão completa com duas possibilidades. Primeiro o Java tenta o caminho do if. Como a condição é falsa, esse caminho é descartado e o bloco do else passa a ser executado.",

            "pergunta" =>
                "Então qual mensagem aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Entrada não permitida",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Entrada permitida",

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
                "E quando o if é verdadeiro?",

            "mensagem" =>
                "Agora a estrutura é a mesma, mas a nota mudou.",

            "codigo" => <<<'JAVA'
double nota = 8.0;

if (nota >= 7.0) {
    System.out.println("Aprovado");
} else {
    System.out.println("Reprovado");
}
JAVA,

            "pergunta" =>
                "Qual mensagem será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Aprovado",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Reprovado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_condicao"
                ],

                [
                    "texto" =>
                        "Aprovado e Reprovado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_duas"
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
        | PERGUNTA 2 — ERRO NA CONDIÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_condicao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Nota 8 e regra 7 ou mais. 8 passa fácil kkkkk. Então a condição dá true e o bloco do if roda.",

            "natanael" =>
                "A condição pergunta se a nota é maior ou igual a 7. Como a variável contém 8.0, essa expressão resulta em true. Quando isso acontece, o Java executa o primeiro caminho e ignora o else.",

            "pergunta" =>
                "8 é maior ou igual a 7?",

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
                        "pergunta_2_erro_condicao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE OS DOIS EXECUTAM
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_duas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "De novo: é um caminho ou o outro. Se o if já venceu porque deu true, o else nem entra na jogada.",

            "natanael" =>
                "O else não é uma segunda instrução independente. Ele está ligado ao if. Quando a condição do if é verdadeira, seu bloco é executado e o else é automaticamente ignorado.",

            "pergunta" =>
                "Se o if foi executado, o else também deve executar?",

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
                        "pergunta_2_erro_duas"
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
                "Lê quase em português: se nota >= 7, Aprovado. Senão, Reprovado. A nota é 8, então já dá pra escolher o caminho.",

            "natanael" =>
                "Podemos ler o código como uma regra escolar: se a nota for maior ou igual a 7, o aluno está aprovado; caso contrário, está reprovado. Como 8 satisfaz a primeira condição, o caminho do if será utilizado.",

            "pergunta" =>
                "Qual mensagem fica no caminho verdadeiro?",

            "opcoes" => [

                [
                    "texto" =>
                        "Aprovado",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Reprovado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_condicao"
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
                "O else precisa de condição?",

            "mensagem" =>
                "Repara que existe uma condição depois do if, mas não aparece nenhuma depois do else.",

            "codigo" => <<<'JAVA'
int saldo = 50;

if (saldo >= 100) {
    System.out.println("Compra liberada");
} else {
    System.out.println("Saldo insuficiente");
}
JAVA,

            "pergunta" =>
                "Por que o else não precisa de uma nova condição?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque ele representa automaticamente o caso em que o if é falso",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Porque o else sempre executa junto com o if",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_sempre"
                ],

                [
                    "texto" =>
                        "Porque o Java ignora condições dentro de um else",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_logica"
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
        | PERGUNTA 3 — ACHA QUE ELSE SEMPRE EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_sempre" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se ele executasse sempre, ia dar mensagem contraditória kkkkk. Imagina aparecer Compra liberada e Saldo insuficiente ao mesmo tempo.",

            "natanael" =>
                "O else existe justamente como alternativa. Se ele executasse sempre, poderíamos ter dois resultados incompatíveis ao mesmo tempo. Nesse exemplo, seria possível informar que a compra foi liberada e que o saldo é insuficiente, o que não faria sentido.",

            "pergunta" =>
                "O else deve executar quando a condição do if é true ou false?",

            "opcoes" => [

                [
                    "texto" =>
                        "False",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "True",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_sempre"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUSÃO NA LÓGICA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_logica" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não é que o Java ignora condição. É que o else já significa 'se não aconteceu o if'. Então ele não precisa repetir outra pergunta.",

            "natanael" =>
                "O motivo não é uma limitação do Java. O else já possui um significado lógico próprio: ele representa todos os casos em que a condição associada ao if foi falsa. Por isso não precisamos escrever uma segunda condição para ele.",

            "pergunta" =>
                "Se saldo >= 100 deu false, qual caminho sobra?",

            "opcoes" => [

                [
                    "texto" =>
                        "O else",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "O if",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_logica"
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
                "else é literalmente o 'senão'. Se a pergunta do if não passou, ele pega o resto dos casos.",

            "natanael" =>
                "Pensa numa pergunta com somente duas possibilidades. Se a condição é verdadeira, usamos o primeiro caminho. Caso ela não seja verdadeira, tudo que restou pertence ao caminho alternativo. O else representa exatamente esse segundo caso.",

            "pergunta" =>
                "Então o else acontece quando o if resulta em quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "false",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_sempre"
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
                "Agora observa que existe código depois de toda a decisão.",

            "codigo" => <<<'JAVA'
boolean temIngresso = false;

if (temIngresso) {
    System.out.println("Pode entrar");
} else {
    System.out.println("Precisa comprar ingresso");
}

System.out.println("Verificação concluída");
JAVA,

            "pergunta" =>
                "Quais mensagens aparecem e em qual ordem?",

            "opcoes" => [

                [
                    "texto" =>
                        "Precisa comprar ingresso e depois Verificação concluída",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Pode entrar e depois Verificação concluída",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_boolean"
                ],

                [
                    "texto" =>
                        "Somente Precisa comprar ingresso",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_fora"
                ],

                [
                    "texto" =>
                        "As três mensagens aparecem",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_todas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ERRO NO BOOLEAN
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_boolean" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "temIngresso tá false. Então o if não libera Pode entrar. Cai direto no else.",

            "natanael" =>
                "A variável temIngresso já contém um valor booleano. Como seu conteúdo é false, a condição do if não é atendida. Portanto o primeiro bloco é ignorado e o bloco do else será executado.",

            "pergunta" =>
                "Se temIngresso vale false, qual bloco executa?",

            "opcoes" => [

                [
                    "texto" =>
                        "O else",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "O if",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_boolean"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ESQUECEU CÓDIGO DEPOIS DA DECISÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_fora" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Verificação concluída ficou depois das chaves do if e do else. Então, independente de qual caminho foi escolhido, o programa continua e chega nela.",

            "natanael" =>
                "A linha Verificação concluída está fora tanto do bloco do if quanto do bloco do else. Depois que um dos dois caminhos termina, o fluxo do programa continua normalmente e alcança essa instrução.",

            "pergunta" =>
                "A linha Verificação concluída pertence ao if ou ao else?",

            "opcoes" => [

                [
                    "texto" =>
                        "A nenhum dos dois",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Ao else",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_fora"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE TODAS EXECUTAM
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_todas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "As três não kkkkk. if e else são exclusivos. Um deles roda. Depois o código que tá fora continua normalmente.",

            "natanael" =>
                "Precisamos separar a decisão do restante do programa. Entre if e else apenas um bloco será escolhido. Depois que esse caminho termina, as instruções que estão fora da estrutura continuam sendo executadas normalmente.",

            "pergunta" =>
                "Quantos dos dois blocos, if ou else, executam em uma mesma decisão?",

            "opcoes" => [

                [
                    "texto" =>
                        "Apenas um",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Os dois",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_todas"
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
                "Segue a ordem: temIngresso é false, então pula o if e entra no else. Depois termina a decisão e continua no println de fora.",

            "natanael" =>
                "Vamos acompanhar o fluxo passo a passo. Primeiro temIngresso é avaliado como false. Por isso o bloco do if é ignorado. Em seguida o bloco do else mostra Precisa comprar ingresso. Depois a estrutura termina e o programa continua para Verificação concluída.",

            "pergunta" =>
                "Qual sequência representa esse caminho?",

            "opcoes" => [

                [
                    "texto" =>
                        "Precisa comprar ingresso → Verificação concluída",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Pode entrar → Precisa comprar ingresso",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_todas"
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
                "Dois caminhos desbloqueados!",

            "robert" =>
                "Boa po kkkkk. Agora ficou completo: if é 'se' e else é 'senão'. Se a condição der true, roda o if. Se der false, roda o else. Nunca os dois na mesma decisão. Só que agora pensa: e se tiver mais de duas opções? Tipo nota alta, média ou baixa. Aí entra o próximo passo.",

            "natanael" =>
                "Fechamos a estrutura if e else. O if representa o caminho escolhido quando uma condição é verdadeira. O else representa automaticamente o caminho alternativo quando essa condição é falsa. Apenas um desses blocos é executado em cada decisão, e depois o programa continua normalmente. Agora surge uma nova situação: nem sempre existem apenas duas possibilidades. Na próxima aula vamos aprender como lidar com vários caminhos diferentes.",

            "mensagem" =>
                "Tu concluiu a sétima Aula Conversada."

        ]

    ]

];