<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 24,

    "titulo" =>
        "Tratando problemas durante a execução",

    "assunto" =>
        "try e catch",

    "descricao" =>
        "Descubra como capturar exceções e definir um caminho alternativo para evitar que o programa seja interrompido inesperadamente.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        25,


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
                "Agora existe um caminho para o erro...",

            "mensagem" =>
                "A divisão por zero ainda é inválida, mas agora ela está dentro de um bloco try acompanhado por catch.",

            "codigo" => <<<'JAVA'
System.out.println("Início");

try {

    int divisor = 0;

    int resultado = 10 / divisor;

    System.out.println(resultado);

} catch (ArithmeticException erro) {

    System.out.println(
        "Não foi possível dividir"
    );

}

System.out.println("Fim");
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Início, Não foi possível dividir e depois Fim",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Início e depois o programa é encerrado sem mostrar Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_interrompe"
                ],

                [
                    "texto" =>
                        "Início, 0 e depois Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_zero"
                ],

                [
                    "texto" =>
                        "O catch é ignorado e nenhuma mensagem aparece",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_catch"
                ],

                [
                    "texto" =>
                        "Não sei como try e catch funcionam",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O PROGRAMA CONTINUA INTERROMPIDO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_interrompe" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Na aula anterior parava porque ninguém tratava a exceção. Agora o catch captura a ArithmeticException, mostra uma mensagem e deixa o programa seguir até Fim.",

            "natanael" =>
                "Sem tratamento, a ArithmeticException interromperia o fluxo. Neste exemplo, porém, existe um catch compatível com esse tipo de problema. Depois que o catch é executado, o programa continua nas instruções posteriores à estrutura.",

            "pergunta" =>
                "Existe um catch preparado para ArithmeticException?",

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
                        "pergunta_1_erro_interrompe"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O RESULTADO É ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_zero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Divisão inteira por zero continua não devolvendo resultado. O try não transforma a conta em válida kkkkk. Ele só permite tratar a exceção quando ela acontece.",

            "natanael" =>
                "A presença do try não modifica o funcionamento da divisão. A operação continua inválida e lança ArithmeticException. A diferença é que agora existe um bloco preparado para responder a essa situação.",

            "pergunta" =>
                "O try corrige a divisão ou apenas permite tratar o problema?",

            "opcoes" => [

                [
                    "texto" =>
                        "Permite tratar o problema",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Corrige a divisão e devolve zero",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_zero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O CATCH É IGNORADO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_catch" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O catch só seria ignorado se nenhuma ArithmeticException acontecesse. Como existe uma divisão inteira por zero, ele entra em ação.",

            "natanael" =>
                "O tipo informado no catch corresponde exatamente à exceção lançada pela divisão inteira por zero. Portanto o bloco é selecionado e sua mensagem é exibida.",

            "pergunta" =>
                "Qual exceção a divisão inteira por zero produz?",

            "opcoes" => [

                [
                    "texto" =>
                        "ArithmeticException",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "NumberFormatException",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_catch"
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
                "Pensa no try como: tenta fazer isso. Se der exatamente esse problema, o catch fala: beleza, eu sei o que fazer. Depois o programa continua.",

            "natanael" =>
                "Imagine que o try representa uma tentativa que pode falhar. O catch funciona como um plano alternativo preparado para determinado tipo de problema. Quando a exceção acontece, o programa desvia para esse plano.",

            "pergunta" =>
                "Quando a divisão lança ArithmeticException, qual bloco é executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "O catch",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "O restante do try",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_catch"
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
                "O caminho fica: mostra Início, tenta dividir, dá ArithmeticException, entra no catch e depois continua no Fim.",

            "natanael" =>
                "A primeira mensagem é executada normalmente. A divisão lança uma exceção, fazendo o fluxo sair do try e entrar no catch. Depois do tratamento, a execução continua fora da estrutura.",

            "pergunta" =>
                "Qual é a sequência correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Início → Não foi possível dividir → Fim",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Início → resultado 0 → Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_zero"
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
                "E quando nenhuma exceção acontece?",

            "mensagem" =>
                "Dessa vez o texto possui um formato numérico válido.",

            "codigo" => <<<'JAVA'
String entrada = "25";

try {

    int idade =
        Integer.parseInt(entrada);

    System.out.println(
        "Idade: " + idade
    );

} catch (NumberFormatException erro) {

    System.out.println(
        "Idade inválida"
    );

}

System.out.println(
    "Cadastro concluído"
);
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Idade: 25 e depois Cadastro concluído",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Idade inválida e depois Cadastro concluído",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_catch"
                ],

                [
                    "texto" =>
                        "Aparecem Idade: 25 e Idade inválida",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_duas"
                ],

                [
                    "texto" =>
                        "Somente Cadastro concluído",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_try"
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
        | PERGUNTA 2 — ACHA QUE O CATCH SEMPRE EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_catch" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O catch não executa só porque está escrito ali. Ele espera uma NumberFormatException. Como \"25\" pode virar int normalmente, não tem motivo pra entrar nele.",

            "natanael" =>
                "O bloco catch é um caminho alternativo utilizado somente quando uma exceção compatível é lançada pelo try. A conversão de \"25\" é válida, portanto o fluxo permanece no caminho normal.",

            "pergunta" =>
                "O texto \"25\" representa um número inteiro válido?",

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
                        "pergunta_2_erro_catch"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE TRY E CATCH EXECUTAM JUNTOS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_duas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O catch não é uma segunda etapa obrigatória. Se o try termina de boa, o catch é pulado. Não aparece mensagem de sucesso e de erro ao mesmo tempo.",

            "natanael" =>
                "O catch representa um desvio para situações excepcionais. Quando todas as instruções do try são concluídas sem falha, o bloco catch correspondente não é executado.",

            "pergunta" =>
                "Se nenhuma exceção acontece, o catch é executado?",

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
                        "Sim, sempre",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_duas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O TRY NÃO EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_try" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O código dentro do try é justamente o primeiro a ser tentado. Como a conversão funciona, ele mostra Idade: 25 normalmente.",

            "natanael" =>
                "A execução entra no try e tenta converter a entrada. O resultado é o inteiro 25, que depois é utilizado pelo println. Somente depois a execução continua para Cadastro concluído.",

            "pergunta" =>
                "Qual valor Integer.parseInt(\"25\") produz?",

            "opcoes" => [

                [
                    "texto" =>
                        "O inteiro 25",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Nenhum valor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_try"
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
                "Tenta converter 25, funciona e mostra a idade. Como não teve erro, pula o catch. Depois mostra Cadastro concluído.",

            "natanael" =>
                "A conversão é concluída com sucesso. O println do try apresenta a idade, o catch é ignorado e a instrução posterior à estrutura é executada normalmente.",

            "pergunta" =>
                "Qual sequência completa será exibida?",

            "opcoes" => [

                [
                    "texto" =>
                        "Idade: 25 → Cadastro concluído",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Idade inválida → Cadastro concluído",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_catch"
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
                "O tipo do catch precisa combinar...",

            "mensagem" =>
                "Agora o try lança uma exceção, mas o catch foi preparado para um problema diferente.",

            "codigo" => <<<'JAVA'
String texto = "Java";

try {

    int numero =
        Integer.parseInt(texto);

    System.out.println(numero);

} catch (ArithmeticException erro) {

    System.out.println(
        "Erro de divisão"
    );

}

System.out.println("Fim");
JAVA,

            "pergunta" =>
                "O que acontece durante a execução?",

            "opcoes" => [

                [
                    "texto" =>
                        "NumberFormatException não é capturada e o programa não chega a mostrar Fim",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "O catch mostra Erro de divisão e depois Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_catch"
                ],

                [
                    "texto" =>
                        "numero recebe 0 e o programa mostra Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_zero"
                ],

                [
                    "texto" =>
                        "O programa ignora a conversão e mostra somente Fim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_ignora"
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
        | PERGUNTA 3 — ACHA QUE QUALQUER CATCH SERVE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_catch" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não basta ter qualquer catch. O problema é NumberFormatException, mas o catch só está esperando ArithmeticException. É tipo chamar o mecânico pra resolver senha errada kkkkk.",

            "natanael" =>
                "O catch precisa ser compatível com a exceção lançada. A conversão produz NumberFormatException, enquanto o bloco disponível captura apenas ArithmeticException. Portanto esse catch não atende ao problema ocorrido.",

            "pergunta" =>
                "Qual exceção Integer.parseInt(\"Java\") lança?",

            "opcoes" => [

                [
                    "texto" =>
                        "NumberFormatException",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "ArithmeticException",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_catch"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE RETORNA ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_zero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "parseInt não usa zero como prêmio de consolação kkkkk. Se o texto não tem formato numérico, ele lança NumberFormatException.",

            "natanael" =>
                "A conversão precisa produzir um inteiro válido para concluir a atribuição. Como \"Java\" não representa um número, a operação termina com uma exceção em vez de devolver zero.",

            "pergunta" =>
                "O texto \"Java\" possui um formato numérico inteiro?",

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
                        "Sim, representa zero",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_zero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE A LINHA É IGNORADA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_ignora" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O Java não pula uma linha problemática sozinho. Ele tenta converter, a exceção acontece e, como não existe catch compatível, o fluxo é interrompido.",

            "natanael" =>
                "A instrução é executada e falha durante a conversão. Como o tratamento disponível não corresponde ao tipo lançado, a exceção permanece não tratada e impede a continuação normal.",

            "pergunta" =>
                "Existe um catch para NumberFormatException nesse código?",

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
                        "pergunta_3_erro_ignora"
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
                "A conversão lança NumberFormatException. O catch espera ArithmeticException. Não combinou, então a exceção escapa e o programa para antes do Fim.",

            "natanael" =>
                "A estrutura possui tratamento, mas para um tipo diferente de problema. Como nenhuma parte captura NumberFormatException, ela continua interrompendo o fluxo da execução.",

            "codigo" => <<<'JAVA'
// Exceção lançada:
NumberFormatException

// Exceção capturada:
ArithmeticException
JAVA,

            "pergunta" =>
                "Por que o catch não é executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque ele espera um tipo diferente de exceção",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Porque nenhum problema aconteceu",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_catch"
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
                "Uma parte que sempre executa...",

            "mensagem" =>
                "Agora apareceu o bloco finally depois do catch.",

            "codigo" => <<<'JAVA'
try {

    int numero =
        Integer.parseInt("café");

    System.out.println(numero);

} catch (NumberFormatException erro) {

    System.out.println(
        "Valor inválido"
    );

} finally {

    System.out.println(
        "Tentativa encerrada"
    );

}

System.out.println(
    "Programa continuou"
);
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Valor inválido, Tentativa encerrada e depois Programa continuou",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Somente Valor inválido",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_finally"
                ],

                [
                    "texto" =>
                        "Tentativa encerrada e depois Programa continuou, sem executar o catch",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_catch"
                ],

                [
                    "texto" =>
                        "O programa é interrompido antes de executar finally",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_interrompe"
                ],

                [
                    "texto" =>
                        "Não sei o que finally faz",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ESQUECEU O FINALLY
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_finally" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Depois do catch ainda existe finally. Ele é tipo aquela etapa de fechar a porta depois de sair: acontece no final, mesmo quando teve exceção.",

            "natanael" =>
                "O catch trata o formato inválido, mas a estrutura ainda possui um bloco finally. Esse bloco é executado depois do try ou do catch, sendo utilizado normalmente para ações que precisam acontecer ao final.",

            "pergunta" =>
                "Qual mensagem está dentro do finally?",

            "opcoes" => [

                [
                    "texto" =>
                        "Tentativa encerrada",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Valor inválido",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_finally"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ESQUECEU O CATCH
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_catch" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O catch combina certinho com o problema. parseInt de café lança NumberFormatException, então Valor inválido aparece antes do finally.",

            "natanael" =>
                "O conteúdo \"café\" não pode ser convertido para int, produzindo NumberFormatException. Como existe um catch compatível, ele é executado antes do bloco finally.",

            "pergunta" =>
                "Qual exceção o catch está preparado para receber?",

            "opcoes" => [

                [
                    "texto" =>
                        "NumberFormatException",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "ArithmeticException",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_catch"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE O PROGRAMA É INTERROMPIDO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_interrompe" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Dessa vez a exceção foi capturada. Então executa o catch, passa pelo finally e depois continua no println que tá fora da estrutura.",

            "natanael" =>
                "A NumberFormatException não permanece sem tratamento. O catch responde ao problema, o finally é executado e, depois que a estrutura termina, o programa segue normalmente.",

            "pergunta" =>
                "Existe um catch compatível com a exceção lançada?",

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
                        "pergunta_4_erro_interrompe"
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
                "Segue o fluxo: tenta converter café, dá erro, entra no catch e mostra Valor inválido. Depois o finally mostra Tentativa encerrada. Aí continua fora da estrutura.",

            "natanael" =>
                "A conversão lança NumberFormatException. O catch compatível trata o problema. Em seguida, o finally é executado. Como a exceção foi tratada, a instrução posterior também acontece normalmente.",

            "pergunta" =>
                "Qual sequência completa está correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Valor inválido → Tentativa encerrada → Programa continuou",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Valor inválido → programa interrompido",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_interrompe"
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
                "Tratamento de exceções desbloqueado!",

            "robert" =>
                "Boa po kkkkk. O try guarda o código que pode dar problema. Se a exceção acontecer, o catch certo entra em ação. Se não acontecer nada, o catch é pulado. E o finally serve pra algo que precisa acontecer no final de qualquer jeito. O mais importante é o tipo combinar: ArithmeticException não captura NumberFormatException. Agora dá pra usar isso em situações mais reais, como receber e validar dados digitados pelo usuário.",

            "natanael" =>
                "Fechamos o tratamento básico de exceções. O bloco try contém as instruções que podem lançar uma exceção. O catch define uma resposta para um tipo compatível de problema. Quando não ocorre exceção, o catch é ignorado; quando ocorre uma exceção não compatível, ela continua sem tratamento. Também conhecemos finally, que normalmente é executado ao final da estrutura, independentemente de o caminho ter passado pelo try ou pelo catch. Na próxima aula vamos aplicar esses conhecimentos em uma interação com dados informados pelo usuário.",

            "mensagem" =>
                "Tu concluiu a vigésima quarta Aula Conversada."

        ]

    ]

];