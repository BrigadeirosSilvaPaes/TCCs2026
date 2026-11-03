<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 6,

    "titulo" =>
        "Quando uma regra muda o caminho",

    "assunto" =>
        "if",

    "descricao" =>
        "Observe diferentes situações e descubra como uma condição pode mudar o que o programa executa.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        7,


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
                "Olha essa regra...",

            "mensagem" =>
                "Na aula anterior tu viu comparações que resultam em true ou false. Agora observa como uma delas aparece nesse código.",

            "codigo" => <<<'JAVA'
int idade = 18;

if (idade >= 18) {
    System.out.println("Entrada permitida");
}
JAVA,

            "pergunta" =>
                "O que tu acha que acontece quando esse programa executa?",

            "opcoes" => [

                [
                    "texto" =>
                        "Mostra Entrada permitida",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Não mostra nada",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_condicao"
                ],

                [
                    "texto" =>
                        "Mostra true no console",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_true"
                ],

                [
                    "texto" =>
                        "Não sei o que o if faz",

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
                "Olha primeiro pra regra: idade >= 18. A idade vale 18, então essa pergunta dá true. Se a regra foi atendida, o código dentro do if pode rodar.",

            "natanael" =>
                "Vamos separar em duas etapas. Primeiro o Java avalia a condição idade >= 18. Como idade vale exatamente 18 e o operador significa maior ou igual, essa comparação resulta em true. Depois disso o if usa esse resultado para decidir se executa ou não o bloco que está entre as chaves.",

            "pergunta" =>
                "Se idade >= 18 resulta em true, a regra foi atendida?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_quase"
                ],

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_condicao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHOU QUE IMPRIME TRUE
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_true" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Na aula passada a gente colocou a comparação dentro do println, aí aparecia true ou false. Aqui não. Agora a comparação tá dentro do if, então ela tá sendo usada como uma regra.",

            "natanael" =>
                "Essa diferença é importante. Antes utilizávamos System.out.println(idade >= 18), então o resultado lógico era enviado diretamente ao console. Agora idade >= 18 aparece dentro do if. Nesse caso, true ou false não são mostrados automaticamente: eles são usados para decidir se o bloco será executado.",

            "codigo" => <<<'JAVA'
if (idade >= 18) {
    System.out.println("Entrada permitida");
}
JAVA,

            "pergunta" =>
                "Então o true está sendo mostrado ou usado para tomar uma decisão?",

            "opcoes" => [

                [
                    "texto" =>
                        "Usado para tomar uma decisão",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_quase"
                ],

                [
                    "texto" =>
                        "Mostrado no console",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_true"
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
                "Pensa num segurança na entrada de um evento. A regra é: 18 anos ou mais entra. O if funciona meio como esse segurança: ele olha a condição e decide se libera o que tá dentro.",

            "natanael" =>
                "Vamos trazer para uma situação do dia a dia. Imagina a entrada de um evento com a regra 'somente pessoas com 18 anos ou mais'. O responsável verifica a idade primeiro. Se a regra for atendida, a pessoa entra. O if trabalha de forma parecida: primeiro verifica uma condição e depois decide se o bloco associado a ela deve ser executado.",

            "pergunta" =>
                "Uma pessoa com exatamente 18 anos atende à regra de 18 anos ou mais?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_quase"
                ],

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_condicao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — QUASE
        |--------------------------------------------------------------------------
        */

        "pergunta_1_quase" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Isso. Guarda essa ideia: o if olha uma condição. Se ela der true, ele executa o que tá dentro das chaves.",

            "natanael" =>
                "Exatamente. Podemos pensar no if como uma porta controlada por uma regra. A condição é verificada primeiro. Quando o resultado é true, o bloco entre as chaves fica liberado para execução.",

            "pergunta" =>
                "Nesse exemplo, qual mensagem será executada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Entrada permitida",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Nenhuma mensagem",

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
                "E se a regra não for atendida?",

            "mensagem" =>
                "Agora a estrutura continua igual, mas o valor da variável mudou.",

            "codigo" => <<<'JAVA'
int idade = 16;

if (idade >= 18) {
    System.out.println("Entrada permitida");
}
JAVA,

            "pergunta" =>
                "O que acontece dessa vez?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não aparece Entrada permitida",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Aparece Entrada permitida normalmente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_true"
                ],

                [
                    "texto" =>
                        "O programa mostra false automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_false"
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
        | PERGUNTA 2 — ACHA QUE EXECUTA MESMO COM FALSE
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_true" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se rodasse sempre, o if não serviria pra muita coisa kkkkk. A ideia dele é justamente bloquear o bloco quando a condição dá false.",

            "natanael" =>
                "Se o bloco fosse executado independentemente da condição, não haveria uma decisão de fato. O papel do if é executar aquele trecho somente quando sua condição resulta em true. Quando o resultado é false, o bloco é ignorado.",

            "pergunta" =>
                "16 é maior ou igual a 18?",

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
                        "pergunta_2_erro_true"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE FALSE É MOSTRADO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_false" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O false acontece por trás, mas ninguém mandou imprimir ele. O Java usa esse false só pra decidir: 'não entra nesse bloco'.",

            "natanael" =>
                "A comparação realmente resulta em false, mas esse valor não foi colocado dentro de um println. Ele serve internamente para controlar o if. Como a condição é falsa, o Java simplesmente pula o conteúdo entre as chaves.",

            "pergunta" =>
                "Então quando a condição é false, o Java faz o quê com o bloco do if?",

            "opcoes" => [

                [
                    "texto" =>
                        "Pula o bloco",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Executa o bloco mesmo assim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_true"
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
                "Volta pro segurança do evento. A pessoa tem 16 e a regra exige 18. Ela não passa pela porta. No if é igual: false, então pula o bloco.",

            "natanael" =>
                "Usando novamente a comparação da entrada do evento: se a regra exige pelo menos 18 anos e a pessoa possui 16, a condição não é atendida. O caminho protegido por aquela regra não é liberado. No código isso significa que o conteúdo do if não será executado.",

            "pergunta" =>
                "Então System.out.println(\"Entrada permitida\"); será executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_true"
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
                "O que está dentro da regra?",

            "mensagem" =>
                "Agora presta atenção nas chaves e na posição de cada comando.",

            "codigo" => <<<'JAVA'
int temperatura = 30;

if (temperatura > 25) {
    System.out.println("Está quente");
}

System.out.println("Programa finalizado");
JAVA,

            "pergunta" =>
                "Quais mensagens aparecem?",

            "opcoes" => [

                [
                    "texto" =>
                        "Está quente e depois Programa finalizado",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Somente Está quente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_fora"
                ],

                [
                    "texto" =>
                        "Somente Programa finalizado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_condicao"
                ],

                [
                    "texto" =>
                        "Não sei quais linhas pertencem ao if",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ESQUECEU O CÓDIGO FORA DO IF
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_fora" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Olha onde termina a chave }. Programa finalizado ficou do lado de fora do if. Então ele não depende daquela condição.",

            "natanael" =>
                "As chaves delimitam qual trecho pertence ao if. O comando que mostra Está quente está dentro delas e depende da condição. Já Programa finalizado aparece depois da chave de fechamento, portanto está fora do bloco e será executado normalmente quando o fluxo chegar até ele.",

            "pergunta" =>
                "A linha Programa finalizado está dentro ou fora das chaves do if?",

            "opcoes" => [

                [
                    "texto" =>
                        "Fora",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Dentro",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_fora"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — IGNOROU A CONDIÇÃO VERDADEIRA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_condicao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Temperatura vale 30 e a pergunta é 30 > 25. Isso dá true. Então Está quente vai aparecer sim.",

            "natanael" =>
                "Antes de analisar a segunda mensagem, precisamos avaliar a condição. temperatura vale 30 e a expressão pergunta se 30 é maior que 25. Como isso é verdadeiro, o bloco do if é executado.",

            "pergunta" =>
                "30 é maior que 25?",

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
                        "pergunta_3_erro_condicao"
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
                "Pensa nas chaves como uma cerca. Está quente tá dentro da cerca do if. Programa finalizado tá fora. O primeiro depende da condição; o segundo não.",

            "natanael" =>
                "Uma analogia útil é imaginar as chaves como uma área cercada. Tudo que está dentro dessa área pertence à condição do if. O que aparece depois da chave de fechamento já voltou para o fluxo normal do programa.",

            "pergunta" =>
                "Como 30 > 25 é true e a última mensagem está fora do if, qual resultado temos?",

            "opcoes" => [

                [
                    "texto" =>
                        "As duas mensagens aparecem",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Nenhuma mensagem aparece",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_condicao"
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
                "Agora não temos uma comparação com números. Mesmo assim o if continua funcionando.",

            "codigo" => <<<'JAVA'
boolean logado = true;

if (logado) {
    System.out.println("Bem-vindo!");
}
JAVA,

            "pergunta" =>
                "Por que o if consegue usar logado diretamente como condição?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque logado já contém um valor booleano",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Porque toda variável pode ser usada diretamente no if",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_tipo"
                ],

                [
                    "texto" =>
                        "Porque true é um texto especial",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_texto"
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
        | PERGUNTA 4 — ACHA QUE QUALQUER VARIÁVEL SERVE
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não é qualquer variável. O if precisa de uma condição que resulte em true ou false. logado já é boolean, então ele já entrega exatamente isso.",

            "natanael" =>
                "O if precisa receber uma expressão booleana, ou seja, algo cujo resultado seja true ou false. Uma variável int, por exemplo, não pode ser colocada diretamente como condição em Java. Já logado foi declarada como boolean, então seu conteúdo já possui exatamente o tipo que o if precisa.",

            "codigo" => <<<'JAVA'
boolean logado = true;
JAVA,

            "pergunta" =>
                "Qual valor está guardado em logado?",

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
                        "1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — TRUE COMO TEXTO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_texto" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se fosse texto seria \"true\" com aspas. Aqui true sem aspas é um valor booleano mesmo.",

            "natanael" =>
                "Existe uma diferença importante entre true e \"true\". Com aspas teríamos uma String contendo os caracteres t, r, u e e. Sem aspas, true é um valor booleano da própria linguagem Java.",

            "pergunta" =>
                "Nesse código, true está entre aspas?",

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
                        "pergunta_4_erro_texto"
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
                "Pensa num interruptor. logado já tá guardando ligado ou desligado: true ou false. O if só olha esse estado e decide se libera o bloco.",

            "natanael" =>
                "Volta à comparação do interruptor que usamos quando estudamos boolean. A variável logado já representa um estado de duas possibilidades. Como seu valor atual é true, o if não precisa fazer outra comparação antes de tomar a decisão.",

            "pergunta" =>
                "Se logado vale true, o bloco do if será executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_tipo"
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
                "Primeira decisão desbloqueada!",

            "robert" =>
                "Boa po kkkkk. Agora o true e false começaram a ter utilidade de verdade. O if olha uma condição: se der true, executa o bloco; se der false, pula. E as chaves mostram exatamente o que depende daquela regra. Só que tem uma pergunta agora: e quando a condição for falsa e eu quiser fazer OUTRA coisa? É isso que vem depois.",

            "natanael" =>
                "Fechamos a primeira estrutura de decisão. O if recebe uma condição booleana; quando essa condição é true, o bloco entre as chaves é executado. Quando é false, esse bloco é ignorado e o programa continua depois dele. Também vimos que uma variável boolean pode ser utilizada diretamente como condição. O próximo passo surge naturalmente: até agora definimos apenas o que acontece quando uma condição é verdadeira. Na próxima aula vamos descobrir como criar um caminho alternativo quando ela for falsa.",

            "mensagem" =>
                "Tu concluiu a sexta Aula Conversada."

        ]

    ]

];