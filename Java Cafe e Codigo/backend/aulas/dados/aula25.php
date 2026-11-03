<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 25,

    "titulo" =>
        "Validando o que o usuário digita",

    "assunto" =>
        "Scanner, conversão e tratamento de erros",

    "descricao" =>
        "Descubra como receber informações do usuário, converter valores e tratar entradas inválidas sem interromper o programa.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        26,


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
                "Agora o valor vem do usuário...",

            "mensagem" =>
                "Até agora muitos valores já estavam escritos no código. Nesse exemplo, o usuário digita uma informação e o programa tenta transformá-la em número.",

            "codigo" => <<<'JAVA'
Scanner entrada =
    new Scanner(System.in);

String texto =
    entrada.nextLine();

int idade =
    Integer.parseInt(texto);

System.out.println(
    "Idade: " + idade
);
JAVA,

            "pergunta" =>
                "Se o usuário digitar 18, o que será mostrado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Idade: 18",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Idade: texto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
                ],

                [
                    "texto" =>
                        "Sempre ocorre um erro",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
                ],

                [
                    "texto" =>
                        "Não sei o que parseInt faz",

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
                "O nextLine pega o que a pessoa digitou como texto. Depois o parseInt tenta transformar esse texto em int. Se ela digitou 18, funciona de boa kkkkk.",

            "natanael" =>
                "O Scanner recebe a informação digitada. Como utilizamos nextLine, inicialmente ela é armazenada como String. Em seguida, Integer.parseInt tenta converter o conteúdo para um número inteiro. Como \"18\" representa um inteiro válido, a conversão é realizada normalmente.",

            "pergunta" =>
                "O texto \"18\" pode ser convertido para int?",

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
                "Pensa que o usuário entrega uma palavra pro programa. Se essa palavra for \"18\", o parseInt olha e fala: beleza, isso pode virar o número 18.",

            "natanael" =>
                "Podemos pensar em parseInt como uma tentativa de interpretação. O programa possui o texto \"18\" e tenta compreender esse conteúdo como um número inteiro. Como o formato é válido, ele consegue produzir o valor numérico 18.",

            "pergunta" =>
                "Depois da conversão, quanto vale idade?",

            "opcoes" => [

                [
                    "texto" =>
                        "18",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "0",

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
                "Mas o usuário pode digitar qualquer coisa...",

            "mensagem" =>
                "Agora a entrada não representa um número inteiro válido.",

            "codigo" => <<<'JAVA'
String texto =
    "dezessete";

try {

    int idade =
        Integer.parseInt(texto);

    System.out.println(idade);

} catch (NumberFormatException erro) {

    System.out.println(
        "Digite uma idade válida"
    );

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Digite uma idade válida",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "17",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
                ],

                [
                    "texto" =>
                        "O catch nunca executa",

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
                "parseInt não traduz português kkkkk. \"dezessete\" não tem formato de número inteiro. A conversão falha e lança NumberFormatException.",

            "natanael" =>
                "Integer.parseInt consegue interpretar representações numéricas como \"17\", mas não converte palavras escritas por extenso. Como \"dezessete\" não possui o formato esperado, uma NumberFormatException é lançada.",

            "pergunta" =>
                "Existe um catch preparado para NumberFormatException?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
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
                "O try tenta converter. Como não consegue, pula pro catch compatível e mostra uma mensagem pro usuário em vez de deixar o programa quebrar.",

            "natanael" =>
                "A conversão é executada dentro do bloco try. Quando NumberFormatException ocorre, o fluxo é direcionado ao catch correspondente. Assim conseguimos apresentar uma resposta compreensível ao usuário.",

            "pergunta" =>
                "Qual bloco trata a entrada inválida?",

            "opcoes" => [

                [
                    "texto" =>
                        "catch",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "O restante do try",

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
                "E se quisermos pedir novamente?",

            "mensagem" =>
                "Agora juntamos repetição e tratamento de erros para continuar perguntando até receber um número válido.",

            "codigo" => <<<'JAVA'
Scanner entrada =
    new Scanner(System.in);

boolean valido = false;

while (!valido) {

    System.out.print(
        "Digite sua idade: "
    );

    String texto =
        entrada.nextLine();

    try {

        int idade =
            Integer.parseInt(texto);

        System.out.println(
            "Idade registrada: "
            + idade
        );

        valido = true;

    } catch (
        NumberFormatException erro
    ) {

        System.out.println(
            "Valor inválido"
        );

    }

}
JAVA,

            "pergunta" =>
                "Quando o laço deixa de repetir?",

            "opcoes" => [

                [
                    "texto" =>
                        "Quando uma idade válida é convertida e valido recebe true",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Sempre depois da primeira tentativa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
                ],

                [
                    "texto" =>
                        "Somente quando acontece uma exceção",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
                ],

                [
                    "texto" =>
                        "Nunca termina",

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
                "Enquanto valido for false, o while continua. Só quando a conversão funciona o código chega em valido = true. Aí !valido vira false e o laço termina.",

            "natanael" =>
                "A condição do while é !valido. Inicialmente valido é false, portanto !false resulta em true e a repetição começa. Quando a entrada é convertida corretamente, valido recebe true. Na próxima verificação, !true resulta em false e o laço termina.",

            "pergunta" =>
                "Qual instrução indica que conseguimos uma entrada válida?",

            "opcoes" => [

                [
                    "texto" =>
                        "valido = true",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "valido = false",

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
                "Tem uma saída sim kkkkk. Quando a pessoa digita um número válido, o parseInt funciona e logo depois valido vira true.",

            "natanael" =>
                "O encerramento depende do sucesso da conversão. Enquanto ocorrer uma entrada inválida, o catch permite continuar. Quando a conversão finalmente funciona, a variável de controle é alterada e encerra a repetição.",

            "pergunta" =>
                "Depois de valido = true, !valido resulta em quê?",

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
                "Agora vamos acompanhar duas tentativas feitas pelo usuário.",

            "codigo" => <<<'JAVA'
// Primeira tentativa:
"Java"

// Segunda tentativa:
"20"

try {

    int idade =
        Integer.parseInt(texto);

    System.out.println(
        "Idade registrada"
    );

} catch (
    NumberFormatException erro
) {

    System.out.println(
        "Tente novamente"
    );

}
JAVA,

            "pergunta" =>
                "Se o programa repetir a pergunta após o erro, qual sequência representa essas duas tentativas?",

            "opcoes" => [

                [
                    "texto" =>
                        "Tente novamente → Idade registrada",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Idade registrada → Tente novamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
                ],

                [
                    "texto" =>
                        "Idade registrada nas duas tentativas",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
                ],

                [
                    "texto" =>
                        "O programa precisa encerrar na primeira tentativa",

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
                "Na primeira tentativa aparece Java. Não dá pra converter pra int, então cai no catch. Na segunda vem 20, aí a conversão funciona.",

            "natanael" =>
                "Precisamos analisar cada entrada separadamente. \"Java\" produz NumberFormatException e executa o tratamento. Já \"20\" possui formato numérico válido, permitindo que o try seja concluído normalmente.",

            "pergunta" =>
                "Qual das duas entradas consegue ser convertida para int?",

            "opcoes" => [

                [
                    "texto" =>
                        "\"20\"",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "\"Java\"",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
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
                "Esse é justamente o objetivo da validação: erro não precisa significar fim do programa. A gente avisa e deixa a pessoa tentar de novo.",

            "natanael" =>
                "Ao combinar tratamento de exceções com uma repetição, podemos responder a uma entrada incorreta e solicitar uma nova tentativa. Assim a aplicação continua funcionando até receber um valor adequado.",

            "pergunta" =>
                "Depois de uma entrada inválida, o programa pode solicitar outro valor?",

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
                        "Não, precisa encerrar",

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
                "Entrada de dados protegida!",

            "robert" =>
                "Boa po kkkkk. Agora o programa não precisa confiar cegamente no que a pessoa digita. A gente recebe o valor, tenta converter dentro do try e, se vier algo inválido, o catch consegue responder sem deixar tudo quebrar. Com uma repetição ainda dá pra pedir de novo até receber uma entrada válida. Agora já temos várias peças separadas. Na próxima vamos juntar Scanner, ArrayList, repetição, decisões e tratamento de erro num programa completo.",

            "natanael" =>
                "Fechamos a validação básica de dados informados pelo usuário. O Scanner permite receber a entrada, a conversão transforma textos válidos em valores numéricos e o tratamento de exceções impede que uma entrada inadequada interrompa inesperadamente o programa. Ao combinar esse processo com estruturas de repetição, podemos permitir novas tentativas até obter uma informação válida. Na próxima aula vamos reunir diferentes conceitos já estudados para construir um programa mais completo no terminal.",

            "mensagem" =>
                "Tu concluiu a vigésima quinta Aula Conversada."

        ]

    ]

];