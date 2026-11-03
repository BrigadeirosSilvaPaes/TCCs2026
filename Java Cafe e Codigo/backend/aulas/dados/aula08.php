<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 8,

    "titulo" =>
        "Mais de dois caminhos",

    "assunto" =>
        "else if",

    "descricao" =>
        "Descubra como o Java pode analisar várias condições em sequência quando existem mais de dois caminhos possíveis.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        9,


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
                "E quando existem três possibilidades?",

            "mensagem" =>
                "Na aula anterior vimos dois caminhos com if e else. Agora apareceu uma condição entre eles.",

            "codigo" => <<<'JAVA'
double nota = 8.0;

if (nota >= 9.0) {

    System.out.println("Excelente");

} else if (nota >= 7.0) {

    System.out.println("Aprovado");

} else {

    System.out.println("Reprovado");

}
JAVA,

            "pergunta" =>
                "Qual mensagem vai aparecer?",

            "opcoes" => [

                [
                    "texto" =>
                        "Excelente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
                ],

                [
                    "texto" =>
                        "Aprovado",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Reprovado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
                ],

                [
                    "texto" =>
                        "Não sei como o else if funciona",

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
                "Vai na ordem kkkkk. Primeiro ele pergunta se 8 é maior ou igual a 9. Não é. Aí ele tenta a próxima condição: 8 é maior ou igual a 7? Essa é verdadeira.",

            "natanael" =>
                "O Java verifica as condições de cima para baixo. A primeira pergunta é se 8.0 é maior ou igual a 9.0, o que resulta em false. Em seguida ele avalia o else if. Como 8.0 é maior ou igual a 7.0, essa condição é verdadeira e seu bloco é executado.",

            "pergunta" =>
                "8 é maior ou igual a 7?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Não",

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
                "Pensa no else if como outra pergunta. Primeiro: nota é 9 ou mais? Se não, pergunta de novo: é 7 ou mais? Só se as duas falharem que chega no else.",

            "natanael" =>
                "Podemos imaginar uma fila de perguntas. O if faz a primeira pergunta. Se ela for falsa, o else if permite fazer uma segunda pergunta. O else fica reservado para quando nenhuma das condições anteriores for atendida.",

            "pergunta" =>
                "Qual condição a nota 8 consegue atender?",

            "opcoes" => [

                [
                    "texto" =>
                        "nota >= 7.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "nota >= 9.0",

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
                "A ordem das condições importa?",

            "mensagem" =>
                "Agora observa duas condições que poderiam parecer verdadeiras para o mesmo valor.",

            "codigo" => <<<'JAVA'
int idade = 20;

if (idade >= 18) {

    System.out.println("Adulto");

} else if (idade >= 16) {

    System.out.println("Pode votar");

} else {

    System.out.println("Menor");

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Adulto",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Pode votar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
                ],

                [
                    "texto" =>
                        "Adulto e Pode votar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
                ],

                [
                    "texto" =>
                        "Menor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
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
                "O Java para na primeira condição verdadeira. Como 20 já passa no idade >= 18, ele executa Adulto e nem precisa testar o else if.",

            "natanael" =>
                "Embora 20 também seja maior ou igual a 16, a estrutura não executa todos os blocos verdadeiros. As condições são verificadas em ordem. Assim que uma delas resulta em true, seu bloco é executado e os demais caminhos daquela estrutura são ignorados.",

            "pergunta" =>
                "Qual é a primeira condição verdadeira encontrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "idade >= 18",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "idade >= 16",

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
                "Podemos ter vários else if?",

            "mensagem" =>
                "Dessa vez existem várias faixas de temperatura.",

            "codigo" => <<<'JAVA'
int temperatura = 18;

if (temperatura >= 30) {

    System.out.println("Muito quente");

} else if (temperatura >= 20) {

    System.out.println("Agradável");

} else if (temperatura >= 10) {

    System.out.println("Frio");

} else {

    System.out.println("Muito frio");

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece para temperatura igual a 18?",

            "opcoes" => [

                [
                    "texto" =>
                        "Muito quente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
                ],

                [
                    "texto" =>
                        "Agradável",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
                ],

                [
                    "texto" =>
                        "Frio",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Muito frio",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
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
                "Vai eliminando. 18 não chega em 30. Também não chega em 20. Mas chega em 10. Então para nessa condição e mostra Frio.",

            "natanael" =>
                "Seguimos novamente de cima para baixo. A temperatura não atende à condição de 30 ou mais, nem à de 20 ou mais. Quando chegamos em temperatura >= 10, a expressão se torna verdadeira. Nesse ponto o Java executa esse bloco e encerra a decisão.",

            "pergunta" =>
                "18 é maior ou igual a 10?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Não",

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
                "Agora nenhuma das condições anteriores será verdadeira.",

            "codigo" => <<<'JAVA'
int pontos = 35;

if (pontos >= 100) {

    System.out.println("Ouro");

} else if (pontos >= 70) {

    System.out.println("Prata");

} else if (pontos >= 40) {

    System.out.println("Bronze");

} else {

    System.out.println("Sem medalha");

}
JAVA,

            "pergunta" =>
                "Qual será o resultado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ouro",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
                ],

                [
                    "texto" =>
                        "Prata",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
                ],

                [
                    "texto" =>
                        "Bronze",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
                ],

                [
                    "texto" =>
                        "Sem medalha",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
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
                "35 não chega em 100, nem 70 e nem 40. Então nenhuma das perguntas deu true. Quando isso acontece sobra o else.",

            "natanael" =>
                "As três condições são avaliadas e todas resultam em false. Como nenhuma alternativa anterior foi escolhida, o fluxo chega ao else, que representa justamente todos os casos restantes.",

            "pergunta" =>
                "Se nenhum if ou else if for verdadeiro, qual bloco executa?",

            "opcoes" => [

                [
                    "texto" =>
                        "O else",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "O primeiro if",

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
                "Vários caminhos desbloqueados!",

            "robert" =>
                "Boa po kkkkk. Agora não estamos presos a só duas opções. O if testa a primeira condição, cada else if pode testar outra e o else fica com o que sobrou. O Java vai de cima pra baixo e para quando encontra a primeira condição verdadeira. Só que às vezes a gente quer comparar um único valor com várias opções específicas. Aí existe outra estrutura bem útil.",

            "natanael" =>
                "Fechamos o uso básico de else if. Essa estrutura permite organizar decisões com mais de duas possibilidades. O Java analisa as condições na ordem em que foram escritas e executa o primeiro bloco cuja condição seja verdadeira. Depois disso, os demais caminhos daquela decisão são ignorados. Se nenhuma condição for atendida, o else pode representar o caso restante. Na próxima aula vamos conhecer outra forma de escolher entre várias opções específicas.",

            "mensagem" =>
                "Tu concluiu a oitava Aula Conversada."

        ]

    ]

];