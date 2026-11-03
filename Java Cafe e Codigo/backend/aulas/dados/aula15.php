<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 15,

    "titulo" =>
        "Uma lista que pode crescer",

    "assunto" =>
        "ArrayList",

    "descricao" =>
        "Descubra como criar uma lista flexível, adicionar, consultar, alterar e remover elementos durante a execução do programa.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        16,


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
                "Uma lista vazia que pode crescer...",

            "mensagem" =>
                "Olha como a lista começa vazia e recebe novos elementos durante a execução.",

            "codigo" => <<<'JAVA'
import java.util.ArrayList;

ArrayList<String> cafes = new ArrayList<>();

cafes.add("Expresso");
cafes.add("Latte");
cafes.add("Mocha");

System.out.println(cafes.size());
JAVA,

            "pergunta" =>
                "Qual número aparece no console?",

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
                        "0, porque a lista começou vazia",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_vazia"
                ],

                [
                    "texto" =>
                        "1, porque existe apenas uma variável",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_variavel"
                ],

                [
                    "texto" =>
                        "Não sei o que add e size fazem",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE CONTINUA VAZIA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_vazia" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Ela começa vazia, mas não fica vazia kkkkk. Cada add coloca um item novo. Foram três adds: Expresso, Latte e Mocha.",

            "natanael" =>
                "A lista realmente é criada sem elementos, mas depois recebe três chamadas do método add. Cada chamada acrescenta um novo texto ao final da lista. Portanto o tamanho muda durante a execução.",

            "pergunta" =>
                "Quantos elementos foram adicionados com add?",

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
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_vazia"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU VARIÁVEL COM QUANTIDADE
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_variavel" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Tem uma variável chamada cafes, mas ela é uma lista. É tipo uma mochila só com três coisas dentro. O size conta os itens, não os nomes de variável.",

            "natanael" =>
                "Existe apenas uma variável chamada cafes, porém ela referencia uma estrutura capaz de organizar vários elementos. O método size não conta variáveis; ele informa quantos elementos estão armazenados dentro da lista.",

            "pergunta" =>
                "O método size conta a variável ou os elementos guardados nela?",

            "opcoes" => [

                [
                    "texto" =>
                        "Os elementos guardados",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Somente a variável",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_variavel"
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
                "Pensa numa lista de compras no celular. Ela começa vazia. Tu toca em adicionar três vezes e coloca três produtos. add adiciona; size diz quantos itens tem.",

            "natanael" =>
                "Imagina uma lista de compras digital. No início ela não possui itens. Cada vez que utilizamos add, acrescentamos um novo elemento. Depois, size funciona como uma contagem de quantos itens estão presentes naquele momento.",

            "pergunta" =>
                "Depois de adicionar três cafés, quanto vale cafes.size()?",

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
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_vazia"
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
                "Isso. A lista cresce conforme tu usa add. Três itens adicionados fazem size devolver 3.",

            "natanael" =>
                "Exatamente. A lista foi criada vazia, recebeu três elementos e passou a possuir tamanho 3. Essa capacidade de crescer é uma diferença importante em relação aos arrays de tamanho fixo.",

            "pergunta" =>
                "Qual é o tamanho final da lista?",

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
                        "pergunta_1_erro_variavel"
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
                "Buscando um item da lista...",

            "mensagem" =>
                "Assim como nos arrays, os elementos possuem posições que começam em zero.",

            "codigo" => <<<'JAVA'
ArrayList<String> alunos = new ArrayList<>();

alunos.add("Ana");
alunos.add("Bruno");
alunos.add("Carlos");

System.out.println(alunos.get(1));
JAVA,

            "pergunta" =>
                "Qual nome aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Bruno",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Ana",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_indice"
                ],

                [
                    "texto" =>
                        "Carlos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_ultimo"
                ],

                [
                    "texto" =>
                        "Não sei o que get faz",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ESQUECEU QUE COMEÇA EM ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_indice" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Mesma regra do array: começa no zero. Ana tá no 0. O get(1) pega o segundo item, que é Bruno.",

            "natanael" =>
                "A numeração segue o mesmo padrão visto nos arrays. O primeiro elemento ocupa o índice 0, o segundo ocupa o índice 1 e o terceiro ocupa o índice 2. Portanto get(1) acessa Bruno.",

            "codigo" => <<<'JAVA'
alunos.get(0) // Ana
alunos.get(1) // Bruno
alunos.get(2) // Carlos
JAVA,

            "pergunta" =>
                "Qual índice representa o segundo elemento?",

            "opcoes" => [

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_indice"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ESCOLHEU O ÚLTIMO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_ultimo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Carlos é o terceiro item e fica no índice 2. O código pediu get(1), então para um antes.",

            "natanael" =>
                "Carlos ocupa a terceira posição visível da lista, mas seu índice é 2. Como o método utilizado recebe o índice 1, o elemento selecionado é o que aparece na posição anterior: Bruno.",

            "pergunta" =>
                "Qual elemento está no índice 1?",

            "opcoes" => [

                [
                    "texto" =>
                        "Bruno",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Carlos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_ultimo"
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
                "get significa pegar. alunos.get(1) pede: pega o item do índice 1. Contando do zero, é Bruno.",

            "natanael" =>
                "O método get consulta um elemento sem removê-lo. O número informado entre parênteses representa o índice desejado. Como a lista começa no índice zero, o índice 1 corresponde ao segundo nome.",

            "pergunta" =>
                "Então qual nome get(1) devolve?",

            "opcoes" => [

                [
                    "texto" =>
                        "Bruno",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Ana",

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
                "Trocando um item existente...",

            "mensagem" =>
                "Agora uma posição da lista será alterada com o método set.",

            "codigo" => <<<'JAVA'
ArrayList<String> tarefas = new ArrayList<>();

tarefas.add("Estudar Java");
tarefas.add("Treinar");
tarefas.add("Descansar");

tarefas.set(1, "Fazer exercícios");

System.out.println(tarefas.get(1));
JAVA,

            "pergunta" =>
                "Qual texto aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Fazer exercícios",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Treinar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_antigo"
                ],

                [
                    "texto" =>
                        "A lista passa a ter quatro elementos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_adicionar"
                ],

                [
                    "texto" =>
                        "Estudar Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_indice"
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
                "Treinar tava ali antes, mas set troca o item daquela posição. É tipo editar uma linha da lista, não só olhar.",

            "natanael" =>
                "Inicialmente o índice 1 contém Treinar. Porém set(1, \"Fazer exercícios\") substitui o elemento existente nessa posição pelo novo texto. O valor anterior deixa de ocupar esse índice.",

            "pergunta" =>
                "Depois do set, qual valor ficou no índice 1?",

            "opcoes" => [

                [
                    "texto" =>
                        "Fazer exercícios",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Treinar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_antigo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU SET COM ADD
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_adicionar" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Quem adiciona item novo é add. O set recebe um índice e troca o que já tá ali. O tamanho continua 3.",

            "natanael" =>
                "O método add acrescenta um novo elemento. Já set trabalha com uma posição existente e substitui seu conteúdo. Como nenhuma nova posição foi criada, a quantidade de elementos permanece a mesma.",

            "pergunta" =>
                "set(1, ...) adiciona uma nova posição ou altera a posição 1?",

            "opcoes" => [

                [
                    "texto" =>
                        "Altera a posição 1",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Adiciona uma quarta posição",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_adicionar"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ERRO NO ÍNDICE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_indice" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Estudar Java tá no índice 0. O set pediu o índice 1, que é o segundo item da lista.",

            "natanael" =>
                "Aplicando novamente a contagem iniciada em zero: Estudar Java ocupa o índice 0, Treinar ocupa inicialmente o índice 1 e Descansar ocupa o índice 2. Portanto o segundo elemento é o que será alterado.",

            "codigo" => <<<'JAVA'
tarefas.get(0) // Estudar Java
tarefas.get(1) // Fazer exercícios
tarefas.get(2) // Descansar
JAVA,

            "pergunta" =>
                "Qual posição foi modificada?",

            "opcoes" => [

                [
                    "texto" =>
                        "A segunda posição, índice 1",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "A primeira posição, índice 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_indice"
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
                "A lista fica assim: Estudar Java, Fazer exercícios, Descansar. O set editou o item do meio.",

            "natanael" =>
                "Depois da alteração, a lista continua com três posições. Somente o conteúdo do índice 1 foi substituído, passando de Treinar para Fazer exercícios.",

            "pergunta" =>
                "O que tarefas.get(1) devolve agora?",

            "opcoes" => [

                [
                    "texto" =>
                        "Fazer exercícios",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Treinar",

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
                "Removendo um elemento...",

            "mensagem" =>
                "Agora observa como a lista muda quando um elemento é removido.",

            "codigo" => <<<'JAVA'
ArrayList<String> nomes = new ArrayList<>();

nomes.add("Ana");
nomes.add("Bruno");
nomes.add("Carlos");

nomes.remove(1);

System.out.println(nomes.get(1));
System.out.println(nomes.size());
JAVA,

            "pergunta" =>
                "Qual resultado aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Carlos e depois 2",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Bruno e depois 3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_nao_removeu"
                ],

                [
                    "texto" =>
                        "Carlos e depois 3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_tamanho"
                ],

                [
                    "texto" =>
                        "Ana e depois 2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_posicao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE REMOVE NÃO FUNCIONOU
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_nao_removeu" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "remove(1) tira o item do índice 1, que era Bruno. Depois disso Carlos anda uma posição pra esquerda e vira o novo índice 1.",

            "natanael" =>
                "O método remove exclui o elemento da posição indicada. Como o índice 1 continha Bruno, esse nome é removido. Os elementos posteriores são reorganizados, fazendo Carlos passar do índice 2 para o índice 1.",

            "pergunta" =>
                "Qual nome foi removido pelo índice 1?",

            "opcoes" => [

                [
                    "texto" =>
                        "Bruno",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Carlos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_nao_removeu"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ERRO NO TAMANHO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_tamanho" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Carlos realmente vira o índice 1, mas a lista não continua com três itens. Um foi removido, então size cai de 3 pra 2.",

            "natanael" =>
                "A reorganização dos índices não cria outro elemento. A lista possuía três nomes e perdeu um deles. Portanto permanecem apenas Ana e Carlos, totalizando tamanho 2.",

            "pergunta" =>
                "Depois de remover um item de uma lista com três, quantos restam?",

            "opcoes" => [

                [
                    "texto" =>
                        "2",

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
                        "pergunta_4_erro_tamanho"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ERRO NA NOVA POSIÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_posicao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Ana continua no índice 0. Quando Bruno sai, Carlos ocupa o espaço dele. Então get(1) pega Carlos.",

            "natanael" =>
                "A remoção acontece no meio da lista. Ana permanece na primeira posição, índice 0. Carlos, que estava depois de Bruno, desloca-se para preencher o espaço vazio e passa ao índice 1.",

            "codigo" => <<<'JAVA'
// Depois de remove(1)
nomes.get(0) // Ana
nomes.get(1) // Carlos
JAVA,

            "pergunta" =>
                "Quem ocupa o índice 1 depois da remoção?",

            "opcoes" => [

                [
                    "texto" =>
                        "Carlos",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Ana",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_posicao"
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
                "Pensa numa fila. Ana, Bruno, Carlos. Bruno sai. Carlos anda pra frente. Fica Ana no 0, Carlos no 1 e tamanho 2.",

            "natanael" =>
                "Podemos visualizar como uma fila organizada. Ao remover a pessoa do meio, quem estava depois ocupa a posição deixada. A lista final contém Ana e Carlos, com índices 0 e 1, e size igual a 2.",

            "pergunta" =>
                "Qual saída completa aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Carlos → 2",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Bruno → 3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_nao_removeu"
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
                "ArrayList desbloqueado!",

            "robert" =>
                "Boa po kkkkk. ArrayList é tipo uma lista que tu pode editar durante o programa: add adiciona, get consulta, set troca, remove tira e size conta. Diferente do array, ela consegue crescer e diminuir conforme precisa. E lembra: os índices continuam começando no zero. Agora já dá pra entrar numa parte muito importante do Java: criar nossos próprios objetos.",

            "natanael" =>
                "Fechamos a introdução ao ArrayList. Ele representa uma lista flexível que pode crescer ou diminuir durante a execução. O método add adiciona elementos, get consulta uma posição, set substitui um valor existente, remove exclui um elemento e size informa a quantidade atual. Os índices continuam começando em zero, assim como nos arrays. Também é importante lembrar que ArrayList pertence ao pacote java.util e utiliza tipos de referência, como String e Integer. Na próxima aula começaremos a estudar classes e objetos, uma das bases da programação orientada a objetos em Java.",

            "mensagem" =>
                "Tu concluiu a décima quinta Aula Conversada."

        ]

    ]

];