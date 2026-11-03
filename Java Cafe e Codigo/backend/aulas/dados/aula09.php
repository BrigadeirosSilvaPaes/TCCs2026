<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 9,

    "titulo" =>
        "Escolhendo entre opções",

    "assunto" =>
        "switch",

    "descricao" =>
        "Descubra como o Java pode comparar um valor com várias opções específicas e escolher qual caminho executar.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        10,


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
                "Uma escolha entre várias opções...",

            "mensagem" =>
                "Olha o valor da variável e depois observa os diferentes cases.",

            "codigo" => <<<'JAVA'
int opcao = 2;

switch (opcao) {

    case 1:
        System.out.println("Cadastrar");
        break;

    case 2:
        System.out.println("Entrar");
        break;

    case 3:
        System.out.println("Sair");
        break;
}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Entrar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Cadastrar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_case"
                ],

                [
                    "texto" =>
                        "As três mensagens",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_todas"
                ],

                [
                    "texto" =>
                        "Não sei como o switch escolhe",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ESCOLHEU O CASE ERRADO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_case" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Olha pro valor primeiro: opcao vale 2. O switch vai procurar justamente um case 2. É tipo escolher a opção 2 num menu.",

            "natanael" =>
                "O switch começa observando o valor colocado entre seus parênteses. Nesse caso, opcao contém 2. Depois o Java compara esse valor com os cases disponíveis até encontrar um que corresponda exatamente a ele.",

            "codigo" => <<<'JAVA'
int opcao = 2;

switch (opcao) {
    case 1:
        System.out.println("Cadastrar");
        break;

    case 2:
        System.out.println("Entrar");
        break;
}
JAVA,

            "pergunta" =>
                "Qual case possui o mesmo valor guardado em opcao?",

            "opcoes" => [

                [
                    "texto" =>
                        "case 2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "case 1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_case"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE TODOS OS CASES EXECUTAM
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_todas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não é pra rodar o menu inteiro kkkkk. O switch recebe um valor e procura a opção correspondente. Como vale 2, ele entra no case 2.",

            "natanael" =>
                "Os cases representam alternativas diferentes. Podemos imaginar um menu de atendimento: se a pessoa escolhe a opção 2, não queremos executar também as opções 1 e 3. O switch procura a alternativa correspondente ao valor recebido.",

            "pergunta" =>
                "Se o usuário escolheu a opção 2, qual alternativa deve ser usada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Somente a opção 2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Todas as opções",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_todas"
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
                "Pensa num menu: 1 é Cadastrar, 2 é Entrar e 3 é Sair. Se tu digita 2, o sistema procura a opção marcada com 2.",

            "natanael" =>
                "Imagina um menu de terminal com opções numeradas. O usuário escolhe um número e o sistema precisa descobrir qual ação está associada a ele. O switch ajuda justamente nesse processo de associar um valor a uma alternativa específica.",

            "pergunta" =>
                "Se opcao vale 2, qual case combina com ela?",

            "opcoes" => [

                [
                    "texto" =>
                        "case 2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "case 3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_case"
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
                "É isso. O switch olha opcao, vê 2, encontra case 2 e entra nele.",

            "natanael" =>
                "Exatamente. A variável contém 2 e existe um case com esse mesmo valor. Portanto esse é o caminho escolhido pelo switch.",

            "pergunta" =>
                "Qual mensagem está dentro do case 2?",

            "opcoes" => [

                [
                    "texto" =>
                        "Entrar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Cadastrar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_case"
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
                "O case procura o quê?",

            "mensagem" =>
                "Agora o switch está trabalhando com um dia da semana representado por um número.",

            "codigo" => <<<'JAVA'
int dia = 3;

switch (dia) {

    case 1:
        System.out.println("Segunda");
        break;

    case 2:
        System.out.println("Terça");
        break;

    case 3:
        System.out.println("Quarta");
        break;
}
JAVA,

            "pergunta" =>
                "Como o Java decide entrar no case 3?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque o valor de dia é exatamente 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Porque 3 é maior que 1 e 2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_comparacao"
                ],

                [
                    "texto" =>
                        "Porque o último case sempre é escolhido",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_ultimo"
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
        | PERGUNTA 2 — CONFUNDIU COM OPERADORES RELACIONAIS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_comparacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aí tu trouxe a lógica do > e < pra cá. Nesse switch não importa se 3 é maior que 2. Ele procura um case com o mesmo valor.",

            "natanael" =>
                "Essa estrutura está sendo usada de forma diferente das comparações relacionais. Não estamos perguntando se um valor é maior ou menor. O switch procura uma correspondência entre o valor analisado e os valores definidos nos cases.",

            "pergunta" =>
                "Se dia valesse 2, qual case seria escolhido?",

            "opcoes" => [

                [
                    "texto" =>
                        "case 2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "case 3, porque 3 é maior",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_comparacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O ÚLTIMO É ESCOLHIDO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_ultimo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O último não ganha por ser último kkkkk. Ele só roda se o valor combinar com ele.",

            "natanael" =>
                "A posição do case não faz com que ele seja automaticamente escolhido. O critério principal é a correspondência entre o valor do switch e o valor definido naquele case.",

            "pergunta" =>
                "O case 3 foi escolhido porque é o último ou porque dia vale 3?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque dia vale 3",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Porque é o último",

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
                "Pensa em etiquetas. A variável tá com etiqueta 3 e o Java procura o case com etiqueta 3.",

            "natanael" =>
                "Uma boa comparação é pensar em caixas numeradas. O valor analisado é 3, então precisamos encontrar a caixa identificada exatamente com o número 3. O case funciona como essa identificação.",

            "pergunta" =>
                "Então qual mensagem aparece quando dia vale 3?",

            "opcoes" => [

                [
                    "texto" =>
                        "Quarta",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Terça",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_comparacao"
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
                "Tem uma palavra importante aí...",

            "mensagem" =>
                "Agora observa o que acontece quando retiramos um detalhe do primeiro case.",

            "codigo" => <<<'JAVA'
int opcao = 1;

switch (opcao) {

    case 1:
        System.out.println("Perfil");

    case 2:
        System.out.println("Configurações");
        break;

    case 3:
        System.out.println("Sair");
        break;
}
JAVA,

            "pergunta" =>
                "Sem o break no case 1, o que acontece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Aparecem Perfil e Configurações",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Aparece somente Perfil",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_parar"
                ],

                [
                    "texto" =>
                        "Aparecem as três mensagens",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_todas"
                ],

                [
                    "texto" =>
                        "Dá erro de compilação",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_compilacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE PARA SOZINHO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_parar" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Essa é a pegadinha do switch. Ele entrou no case 1, mas não encontrou break pra mandar parar. Então continua descendo.",

            "natanael" =>
                "Encontrar um case correspondente não significa necessariamente que o switch terminará naquele ponto. Sem um break, a execução continua para as instruções do case seguinte até encontrar um break ou chegar ao fim do switch.",

            "pergunta" =>
                "Depois de mostrar Perfil, existe um break imediatamente depois?",

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
                        "pergunta_3_erro_parar"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE TODAS APARECEM
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_todas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Chegou perto da ideia, mas tem um break no case 2. Então ele desce do 1 pro 2 e para ali. O case 3 não roda.",

            "natanael" =>
                "A ausência do break no case 1 faz a execução continuar para o case 2. Porém, ao terminar o case 2, encontramos um break. Nesse momento o switch é encerrado antes de chegar ao case 3.",

            "pergunta" =>
                "Em qual case aparece o primeiro break encontrado durante esse caminho?",

            "opcoes" => [

                [
                    "texto" =>
                        "case 2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "case 3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_todas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE NÃO COMPILA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_compilacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não dá erro só porque faltou break. Java permite isso. Só que o comportamento muda e ele continua pro próximo case.",

            "natanael" =>
                "O break não é obrigatório para que o código compile nesse tipo de switch. A ausência dele provoca outro comportamento: depois de entrar em um case, a execução pode continuar pelos cases seguintes. Esse comportamento é conhecido como fall-through.",

            "pergunta" =>
                "Então a ausência do break causa erro ou faz a execução continuar?",

            "opcoes" => [

                [
                    "texto" =>
                        "Faz continuar",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Sempre causa erro",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_compilacao"
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
                "Pensa no break como uma placa de PARE. Entrou no case 1, não tem placa. Continua pro case 2. Depois dele tem break, aí para.",

            "natanael" =>
                "Imagina os cases como andares de uma escada. A execução entra no primeiro andar correspondente. Se não encontra um break, continua descendo para o próximo. No case 2 finalmente encontra a instrução que encerra o switch.",

            "pergunta" =>
                "Qual sequência aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Perfil → Configurações",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Somente Perfil",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_parar"
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
                "E se nenhuma opção existir?",

            "mensagem" =>
                "Agora o valor recebido não corresponde a nenhum dos cases definidos.",

            "codigo" => <<<'JAVA'
int opcao = 9;

switch (opcao) {

    case 1:
        System.out.println("Cadastrar");
        break;

    case 2:
        System.out.println("Entrar");
        break;

    case 3:
        System.out.println("Sair");
        break;

    default:
        System.out.println("Opção inválida");
}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Opção inválida",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Nada aparece",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_nada"
                ],

                [
                    "texto" =>
                        "Sair",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_ultimo"
                ],

                [
                    "texto" =>
                        "O programa dá erro porque não existe case 9",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_programa"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE NADA ACONTECE
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_nada" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se não tivesse default, aí sim poderia não aparecer nada. Mas tem um default justamente pra pegar valor que não combina com nenhum case.",

            "natanael" =>
                "Sem um case correspondente e sem default, o switch poderia simplesmente terminar sem executar nenhum dos blocos. Porém neste código existe um default, que representa um caminho alternativo para valores que não correspondem aos cases anteriores.",

            "pergunta" =>
                "Qual parte do switch recebe os valores que não encontraram nenhum case?",

            "opcoes" => [

                [
                    "texto" =>
                        "default",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "case 3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_ultimo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE O ÚLTIMO CASE É ESCOLHIDO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_ultimo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "case 3 só serve quando opcao vale 3. Ele não vira opção padrão só porque é o último case.",

            "natanael" =>
                "O case 3 continua representando exclusivamente o valor 3. Ele não recebe automaticamente outros valores. Para tratar situações que não correspondem a nenhum case existe uma parte específica: o default.",

            "pergunta" =>
                "9 é igual a 3?",

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
                        "pergunta_4_erro_ultimo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE DÁ ERRO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_programa" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não precisa existir case pra todo valor possível. Senão imagina criar case pra tudo kkkkk. O default existe justamente pra resolver esses casos.",

            "natanael" =>
                "O switch não exige que exista um case para cada valor que uma variável possa receber. É comum criar apenas as opções válidas e utilizar default como tratamento para qualquer valor que não corresponda a elas.",

            "pergunta" =>
                "Se 9 não combina com nenhum case, qual caminho foi preparado para ele?",

            "opcoes" => [

                [
                    "texto" =>
                        "default",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Nenhum, sempre dá erro",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_programa"
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
                "default é tipo o 'nenhuma das opções acima'. Tentou 1, 2 e 3, não bateu? Cai nele.",

            "natanael" =>
                "Pensa no default como a opção de segurança de um menu. Primeiro o Java procura uma correspondência entre os cases. Se nenhuma delas existir, o fluxo utiliza o bloco default.",

            "pergunta" =>
                "Como opcao vale 9, qual mensagem será exibida?",

            "opcoes" => [

                [
                    "texto" =>
                        "Opção inválida",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Sair",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_ultimo"
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
                "Switch desbloqueado!",

            "robert" =>
                "Boa po kkkkk. O resumo é: switch olha um valor, case representa uma opção específica, break manda parar e default pega o que não combinou com nenhum case. É muito útil pra menu, escolha de opção, estado e coisa desse tipo. Agora dá pra sair das decisões e começar a fazer o programa repetir tarefas.",

            "natanael" =>
                "Fechamos a estrutura switch. Ela analisa um valor e procura um case correspondente. O break normalmente encerra a execução daquele caminho e evita que ela continue pelos cases seguintes. Já o default pode funcionar como uma alternativa para valores que não correspondem a nenhuma opção definida. Essa estrutura é especialmente útil quando trabalhamos com várias possibilidades específicas. O próximo passo será conhecer estruturas que permitem repetir instruções automaticamente.",

            "mensagem" =>
                "Tu concluiu a nona Aula Conversada."

        ]

    ]

];