<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 14,

    "titulo" =>
        "Percorrendo os valores",

    "assunto" =>
        "Percorrendo Arrays",

    "descricao" =>
        "Descubra como utilizar estruturas de repetição para acessar automaticamente cada posição de um array.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        15,


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
                "Um por um na mão?",

            "mensagem" =>
                "Na aula anterior vimos que cada elemento de um array possui um índice. Agora observa como podemos acessar todos sem escrever vários println.",

            "codigo" => <<<'JAVA'
String[] linguagens = {
    "Java",
    "Python",
    "C#"
};

for (int i = 0; i < linguagens.length; i++) {

    System.out.println(
        linguagens[i]
    );

}
JAVA,

            "pergunta" =>
                "Quais valores aparecem no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Java, Python e C#",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Somente Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
                ],

                [
                    "texto" =>
                        "Somente C#",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro"
                ],

                [
                    "texto" =>
                        "Não sei como i funciona dentro do array",

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
                "Lembra do for da aula passada kkkkk. O i começa em 0, depois vira 1 e depois 2. Então linguagens[i] vai acessando uma posição diferente a cada volta.",

            "natanael" =>
                "O contador i muda durante a repetição. Na primeira volta ele vale 0, depois 1 e finalmente 2. Como utilizamos esse contador dentro dos colchetes, cada repetição acessa uma posição diferente do array.",

            "codigo" => <<<'JAVA'
linguagens[0] // Java
linguagens[1] // Python
linguagens[2] // C#
JAVA,

            "pergunta" =>
                "Quantas posições diferentes serão acessadas?",

            "opcoes" => [

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "1",

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
                "Pensa que i é o número da gaveta atual. Primeiro abre a gaveta 0, depois a 1 e depois a 2.",

            "natanael" =>
                "Voltando à comparação do array com várias gavetas, podemos imaginar i como o número da gaveta que desejamos abrir. Como ele muda a cada repetição, conseguimos visitar todos os espaços automaticamente.",

            "pergunta" =>
                "Quando i vale 1, qual posição é acessada?",

            "opcoes" => [

                [
                    "texto" =>
                        "linguagens[1]",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "linguagens[0]",

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
                "Por que começa no zero?",

            "mensagem" =>
                "Agora observa com atenção o valor inicial do contador.",

            "codigo" => <<<'JAVA'
int[] numeros = {
    10,
    20,
    30,
    40
};

for (int i = 0; i < numeros.length; i++) {

    System.out.println(
        numeros[i]
    );

}
JAVA,

            "pergunta" =>
                "Por que o contador i começa em 0?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque o primeiro índice de um array é 0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Porque o primeiro valor do array é sempre zero",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro"
                ],

                [
                    "texto" =>
                        "Porque todo for precisa obrigatoriamente começar em zero",

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
                "Não é regra do for. É por causa do array. A primeira posição é [0], então começamos ali pra não pular nenhum valor.",

            "natanael" =>
                "O for pode iniciar em diferentes valores dependendo do problema. Nesse caso começamos em zero porque queremos acessar o primeiro elemento do array, e os índices dessa estrutura começam justamente em 0.",

            "codigo" => <<<'JAVA'
numeros[0] // 10
numeros[1] // 20
numeros[2] // 30
numeros[3] // 40
JAVA,

            "pergunta" =>
                "Qual índice contém o primeiro número?",

            "opcoes" => [

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "1",

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
                "Na aula de arrays vimos que a primeira posição não é 1. É 0. O for só está seguindo essa numeração.",

            "natanael" =>
                "O contador está sendo utilizado diretamente como índice. Por isso ele precisa começar no mesmo número utilizado pela primeira posição do array.",

            "pergunta" =>
                "Qual é o primeiro índice de um array em Java?",

            "opcoes" => [

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "1",

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
                "E por que usamos length?",

            "mensagem" =>
                "Agora presta atenção na condição que mantém a repetição.",

            "codigo" => <<<'JAVA'
String[] alunos = {
    "Ana",
    "Carlos",
    "Maria"
};

for (int i = 0; i < alunos.length; i++) {

    System.out.println(
        alunos[i]
    );

}
JAVA,

            "pergunta" =>
                "Qual vantagem existe em usar alunos.length na condição?",

            "opcoes" => [

                [
                    "texto" =>
                        "O for utiliza automaticamente a quantidade de elementos do array",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "O array passa a ter tamanho infinito",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro"
                ],

                [
                    "texto" =>
                        "O Java adiciona novos alunos automaticamente",

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
                "length não cria nada novo. Ele só responde quantos elementos existem. Se são três alunos, alunos.length vale 3.",

            "natanael" =>
                "A propriedade length não modifica o array. Ela informa sua quantidade atual de posições. Assim podemos utilizar esse número como limite da repetição sem precisar escrever manualmente um valor fixo.",

            "pergunta" =>
                "Nesse exemplo, quanto vale alunos.length?",

            "opcoes" => [

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "2",

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
                "Imagina trocar esse array por outro com dez nomes. Se usamos length, o próprio programa descobre o limite.",

            "natanael" =>
                "Utilizar length torna a repetição dependente do tamanho real do array. Se a quantidade de elementos mudar no código, não precisamos alterar manualmente o limite do for.",

            "pergunta" =>
                "length representa qual informação?",

            "opcoes" => [

                [
                    "texto" =>
                        "A quantidade de elementos",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "O valor do último elemento",

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
                "Também podemos usar cada valor...",

            "mensagem" =>
                "Percorrer um array não serve apenas para mostrar seus elementos. Podemos utilizar cada valor em operações.",

            "codigo" => <<<'JAVA'
int[] notas = {
    7,
    8,
    10
};

int soma = 0;

for (int i = 0; i < notas.length; i++) {

    soma =
        soma + notas[i];

}

System.out.println(soma);
JAVA,

            "pergunta" =>
                "Qual valor aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "25",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
                ],

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro"
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
                "Vai somando um de cada vez. Começa com 0. Depois soma 7, depois 8 e depois 10. Dá 25.",

            "natanael" =>
                "A variável soma mantém o resultado acumulado. Na primeira repetição recebe 7. Na segunda passa para 15. Na terceira recebe mais 10 e termina com 25.",

            "codigo" => <<<'JAVA'
0 + 7 = 7
7 + 8 = 15
15 + 10 = 25
JAVA,

            "pergunta" =>
                "Quanto vale soma depois das três repetições?",

            "opcoes" => [

                [
                    "texto" =>
                        "25",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "10",

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
                "Arrays percorridos!",

            "robert" =>
                "Boa po kkkkk. Agora juntamos as duas ideias: o for controla o índice e o array fornece o valor daquela posição. Assim dá pra passar por tudo sem escrever um comando pra cada elemento. Só que array ainda tem tamanho fixo. E se a gente quiser adicionar ou remover elementos enquanto o programa tá rodando? É aí que entra a próxima aula.",

            "natanael" =>
                "Fechamos a ideia de percorrer arrays. O contador do for pode ser utilizado como índice, permitindo acessar cada elemento automaticamente. Como os índices começam em zero, iniciamos o contador nesse valor, e length fornece um limite baseado na quantidade real de posições existentes. Durante esse percurso podemos consultar e utilizar os valores em diferentes operações. Na próxima aula vamos conhecer uma estrutura mais flexível para listas que podem crescer ou diminuir durante a execução.",

            "mensagem" =>
                "Tu concluiu a décima quarta Aula Conversada."

        ]

    ]

];