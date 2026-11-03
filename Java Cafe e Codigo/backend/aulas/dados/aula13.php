<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 13,

    "titulo" =>
        "Guardando vários valores",

    "assunto" =>
        "Arrays",

    "descricao" =>
        "Descubra como o Java pode organizar vários valores do mesmo tipo dentro de uma única estrutura.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        14,


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
                "Uma variável diferente...",

            "mensagem" =>
                "Até agora nossas variáveis normalmente guardavam um único valor. Olha o que mudou nesse exemplo.",

            "codigo" => <<<'JAVA'
String[] linguagens = {
    "Java",
    "Python",
    "C#"
};
JAVA,

            "pergunta" =>
                "O que parece estar acontecendo nessa variável linguagens?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ela está guardando vários textos",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Ela guarda somente Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_um"
                ],

                [
                    "texto" =>
                        "Ela cria três variáveis diferentes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tres"
                ],

                [
                    "texto" =>
                        "Não sei o que [] significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE GUARDA APENAS UM VALOR
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_um" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se guardasse só Java, Python e C# iam estar ali fazendo hora extra kkkkk. Repara que os três valores estão dentro da mesma estrutura.",

            "natanael" =>
                "Vamos observar a declaração inteira. Existe apenas um nome de variável: linguagens. Porém, dentro das chaves aparecem três valores diferentes. Isso indica que essa única estrutura está organizando mais de uma informação.",

            "codigo" => <<<'JAVA'
String[] linguagens = {
    "Java",
    "Python",
    "C#"
};
JAVA,

            "pergunta" =>
                "Quantos textos estão organizados dentro de linguagens?",

            "opcoes" => [

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_um"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE SÃO TRÊS VARIÁVEIS
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_tres" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Tem três valores, mas só um nome de variável: linguagens. É tipo uma estante só com vários livros dentro.",

            "natanael" =>
                "Não estamos criando uma variável chamada Java, outra chamada Python e outra chamada C#. Existe apenas linguagens. Podemos imaginar essa variável como um armário que possui vários espaços internos para organizar valores.",

            "pergunta" =>
                "Qual é o único nome de variável declarado nesse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "linguagens",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tres"
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
                "Pensa num armário com várias gavetas. A variável comum seria uma gaveta só. O array é tipo o armário inteiro, com vários espaços pra guardar coisas do mesmo tipo.",

            "natanael" =>
                "Vamos usar uma comparação. Imagina um armário com várias gavetas numeradas. Em vez de criar um armário diferente para cada objeto, podemos utilizar uma única estrutura com vários espaços internos. Um array trabalha de forma parecida: ele organiza vários valores do mesmo tipo dentro de uma única variável.",

            "pergunta" =>
                "Então linguagens parece guardar um valor só ou vários valores?",

            "opcoes" => [

                [
                    "texto" =>
                        "Vários valores",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Somente um valor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_um"
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
                "Isso. O [] ajuda a indicar que estamos trabalhando com um array. Nesse caso, um array de String com três textos dentro.",

            "natanael" =>
                "Exatamente. A presença de [] indica que linguagens é um array. Como utilizamos String[], essa estrutura foi preparada para armazenar vários valores do tipo String.",

            "pergunta" =>
                "Qual ideia melhor descreve um array?",

            "opcoes" => [

                [
                    "texto" =>
                        "Uma estrutura que organiza vários valores do mesmo tipo",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Uma variável que sempre guarda apenas um valor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_um"
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
                "Cada valor tem um endereço...",

            "mensagem" =>
                "Agora vamos tentar pegar somente um dos valores guardados no array.",

            "codigo" => <<<'JAVA'
String[] cafes = {
    "Expresso",
    "Latte",
    "Mocha"
};

System.out.println(cafes[0]);
JAVA,

            "pergunta" =>
                "Qual palavra aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Expresso",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Latte",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_indice"
                ],

                [
                    "texto" =>
                        "Mocha",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_posicao"
                ],

                [
                    "texto" =>
                        "Não sei por que aparece [0]",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE 0 É A SEGUNDA POSIÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_indice" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Essa pega muita gente no começo kkkkk. Array em Java começa contando do zero. Então [0] é o primeiro espaço, não o segundo.",

            "natanael" =>
                "Aqui aparece uma característica muito importante dos arrays: seus índices começam em zero. Portanto, o primeiro elemento utiliza índice 0, o segundo utiliza índice 1 e o terceiro utiliza índice 2.",

            "codigo" => <<<'JAVA'
cafes[0] // Expresso
cafes[1] // Latte
cafes[2] // Mocha
JAVA,

            "pergunta" =>
                "Qual índice representa o primeiro elemento?",

            "opcoes" => [

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_indice"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ESCOLHEU A ÚLTIMA POSIÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_posicao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Mocha tá lá no final. Se contar do zero fica: Expresso é 0, Latte é 1 e Mocha é 2.",

            "natanael" =>
                "Vamos numerar os espaços internamente. Expresso ocupa o índice 0, Latte ocupa o índice 1 e Mocha ocupa o índice 2. O número entre colchetes indica exatamente qual desses espaços queremos acessar.",

            "pergunta" =>
                "Qual valor está no índice 0?",

            "opcoes" => [

                [
                    "texto" =>
                        "Expresso",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Mocha",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_posicao"
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
                "Pensa nas gavetas numeradas: a primeira gaveta é 0, a segunda é 1 e a terceira é 2. cafes[0] abre a primeira.",

            "natanael" =>
                "Voltando ao armário: imagine que cada gaveta possui um número. Porém a numeração começa em zero. Quando escrevemos cafes[0], estamos pedindo ao Java o conteúdo da primeira posição do array.",

            "pergunta" =>
                "Então cafes[0] contém qual valor?",

            "opcoes" => [

                [
                    "texto" =>
                        "Expresso",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Latte",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_indice"
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
                "Também dá pra trocar o conteúdo...",

            "mensagem" =>
                "Um array não serve apenas para consultar valores. Podemos acessar uma posição e colocar outro valor nela.",

            "codigo" => <<<'JAVA'
int[] notas = {
    7,
    8,
    6
};

notas[1] = 10;

System.out.println(notas[1]);
JAVA,

            "pergunta" =>
                "Qual valor aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "8",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_antigo"
                ],

                [
                    "texto" =>
                        "7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_indice"
                ],

                [
                    "texto" =>
                        "O array passa a ter quatro valores",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_novo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O VALOR ANTIGO CONTINUA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_antigo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "8 tava ali antes, mas notas[1] = 10 troca o conteúdo daquela posição. É tipo abrir a gaveta 1, tirar o 8 e colocar 10.",

            "natanael" =>
                "Inicialmente o índice 1 realmente contém 8. Porém a instrução notas[1] = 10 acessa exatamente essa posição e substitui seu conteúdo. O valor anterior deixa de ocupar aquele espaço.",

            "pergunta" =>
                "Depois da atribuição, qual valor ficou guardado em notas[1]?",

            "opcoes" => [

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "8",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_antigo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU O ÍNDICE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_indice" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Lembra que começa no zero. notas[0] é 7. notas[1] é o segundo espaço, que antes tinha 8.",

            "natanael" =>
                "Precisamos aplicar novamente a numeração iniciando em zero. O valor 7 ocupa notas[0]. O valor 8 ocupa originalmente notas[1]. Portanto é a segunda posição que será modificada pela atribuição.",

            "codigo" => <<<'JAVA'
notas[0] // 7
notas[1] // 8
notas[2] // 6
JAVA,

            "pergunta" =>
                "Qual posição recebe o novo valor 10?",

            "opcoes" => [

                [
                    "texto" =>
                        "A posição de índice 1",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "A posição de índice 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_indice"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE CRIA NOVA POSIÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_novo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não tá adicionando uma gaveta nova. Tá escolhendo uma que já existe: [1]. Só troca o que tem dentro dela.",

            "natanael" =>
                "A instrução não aumenta o tamanho do array. Estamos acessando uma posição existente pelo índice 1 e substituindo seu conteúdo. Arrays possuem um tamanho definido quando são criados.",

            "pergunta" =>
                "A operação notas[1] = 10 cria outra posição ou altera uma que já existe?",

            "opcoes" => [

                [
                    "texto" =>
                        "Altera uma posição existente",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Cria uma nova posição",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_novo"
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
                "O array fica tipo [7, 10, 6]. A segunda posição mudou de 8 pra 10.",

            "natanael" =>
                "Depois da atribuição podemos visualizar o array assim: índice 0 contém 7, índice 1 contém 10 e índice 2 contém 6. Apenas o elemento selecionado foi substituído.",

            "codigo" => <<<'JAVA'
int[] notas = {
    7,
    10,
    6
};
JAVA,

            "pergunta" =>
                "Então o que notas[1] mostra agora?",

            "opcoes" => [

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "8",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_antigo"
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
                "Quantos espaços existem?",

            "mensagem" =>
                "Além de acessar valores, o Java consegue informar quantos elementos existem no array.",

            "codigo" => <<<'JAVA'
String[] alunos = {
    "Ana",
    "Carlos",
    "João",
    "Maria"
};

System.out.println(alunos.length);
JAVA,

            "pergunta" =>
                "Qual número aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "4",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_indice"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_zero"
                ],

                [
                    "texto" =>
                        "Não sei o que length significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUNDIU ÚLTIMO ÍNDICE COM TAMANHO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_indice" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "3 é o último índice, mas não é a quantidade. Como começa no zero, os índices são 0, 1, 2 e 3. Isso dá quatro elementos.",

            "natanael" =>
                "Aqui precisamos separar duas ideias. O último índice realmente é 3, porque a contagem começa em zero. Porém length informa a quantidade total de elementos. Os índices 0, 1, 2 e 3 representam quatro posições.",

            "codigo" => <<<'JAVA'
alunos[0] // Ana
alunos[1] // Carlos
alunos[2] // João
alunos[3] // Maria
JAVA,

            "pergunta" =>
                "Quantas posições aparecem nessa lista?",

            "opcoes" => [

                [
                    "texto" =>
                        "4",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_indice"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE LENGTH COMEÇA EM ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_zero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Índice começa em zero. Quantidade não kkkkk. Se tem quatro alunos, length vai dizer 4.",

            "natanael" =>
                "A numeração dos índices começa em zero, mas length não representa um índice. Ele representa quantos elementos existem realmente dentro do array.",

            "pergunta" =>
                "Existem quantos nomes dentro de alunos?",

            "opcoes" => [

                [
                    "texto" =>
                        "4",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_zero"
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
                "length é tipo perguntar: quantas gavetas esse armário tem? Ana, Carlos, João e Maria. Quatro valores.",

            "natanael" =>
                "Pensa em length como uma contagem dos espaços disponíveis no array. Não queremos saber qual é o último índice, mas sim quantos elementos estão armazenados na estrutura.",

            "pergunta" =>
                "Então alunos.length vale quanto?",

            "opcoes" => [

                [
                    "texto" =>
                        "4",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_indice"
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
                "Arrays desbloqueados!",

            "robert" =>
                "Boa po kkkkk. Agora pensa no array como um armário com várias gavetas do mesmo tipo. [] mostra que é array, cada valor fica numa posição, o primeiro índice é 0, dá pra consultar e trocar valores usando [indice], e length mostra quantos elementos existem. Agora que sabemos guardar vários valores e já aprendemos o for, podemos juntar as duas ideias para percorrer um array automaticamente.",

            "natanael" =>
                "Fechamos a introdução aos arrays. Eles permitem organizar vários valores do mesmo tipo dentro de uma única estrutura. Cada elemento possui um índice, e essa numeração começa em zero. Podemos acessar ou alterar elementos utilizando seus índices, e a propriedade length informa a quantidade total de posições existentes. Na próxima aula vamos unir os arrays à estrutura for para percorrer automaticamente todos os seus elementos.",

            "mensagem" =>
                "Tu concluiu a décima terceira Aula Conversada."

        ]

    ]

];