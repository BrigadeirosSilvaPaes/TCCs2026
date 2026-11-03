<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 26,

    "titulo" =>
        "Construindo um programa completo",

    "assunto" =>
        "Projeto no terminal",

    "descricao" =>
        "Reúna Scanner, ArrayList, repetições, decisões e tratamento de exceções para construir um gerenciador de tarefas.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        27,


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
                "Mantendo o menu funcionando...",

            "mensagem" =>
                "Nosso programa precisa continuar mostrando o menu até o usuário escolher a opção de sair.",

            "codigo" => <<<'JAVA'
import java.util.Scanner;

Scanner leitor =
    new Scanner(System.in);

int opcao = -1;

while (opcao != 0) {

    System.out.println(
        "1 - Adicionar tarefa"
    );

    System.out.println(
        "2 - Listar tarefas"
    );

    System.out.println(
        "0 - Sair"
    );

    opcao = Integer.parseInt(
        leitor.nextLine()
    );

}

System.out.println(
    "Programa encerrado"
);

leitor.close();
JAVA,

            "pergunta" =>
                "Qual valor o usuário precisa digitar para encerrar o while?",

            "opcoes" => [

                [
                    "texto" =>
                        "0",

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
                        "pergunta_1_erro_um"
                ],

                [
                    "texto" =>
                        "-1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_inicial"
                ],

                [
                    "texto" =>
                        "Qualquer valor encerra o programa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_qualquer"
                ],

                [
                    "texto" =>
                        "Não sei interpretar a condição",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ESCOLHEU 1
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_um" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se opcao vale 1, a condição fica 1 != 0. Isso dá true, então o menu continua aparecendo.",

            "natanael" =>
                "A repetição continua enquanto opcao for diferente de zero. Quando o usuário digita 1, a comparação 1 != 0 resulta em true. Portanto o programa volta ao início do while.",

            "pergunta" =>
                "A expressão 1 != 0 é verdadeira ou falsa?",

            "opcoes" => [

                [
                    "texto" =>
                        "Verdadeira",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Falsa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_um"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU O VALOR INICIAL
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_inicial" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O -1 é só o valor inicial usado pra garantir que o while consiga começar. Como -1 é diferente de 0, o primeiro menu aparece.",

            "natanael" =>
                "A variável começa em -1 para que a condição seja verdadeira na primeira verificação. Esse valor não representa a opção de encerramento. O laço termina quando opcao deixa de ser diferente de zero.",

            "pergunta" =>
                "Quando opcao vale -1, a condição opcao != 0 permite entrar no while?",

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
                        "pergunta_1_erro_inicial"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE QUALQUER VALOR ENCERRA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_qualquer" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A condição não diz pra parar com qualquer valor. Ela diz: continua enquanto a opção for diferente de zero.",

            "natanael" =>
                "O operador != significa diferente. Portanto valores como 1, 2 ou 5 mantêm a condição verdadeira. Apenas o valor zero torna a expressão falsa.",

            "pergunta" =>
                "Qual valor faz opcao != 0 resultar em false?",

            "opcoes" => [

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_qualquer"
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
                "Lê quase em português: enquanto opção for diferente de zero, continua. Quando virar zero, a condição quebra.",

            "natanael" =>
                "Imagine um atendente que continua mostrando as opções enquanto o cliente não disser que deseja sair. Nesse programa, o número zero representa essa escolha de encerramento.",

            "pergunta" =>
                "Quando opcao recebe 0, opcao != 0 fica true ou false?",

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
                        "pergunta_1_erro_qualquer"
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
                "É isso. Opções 1 e 2 mantêm o menu funcionando. Quando entra 0, a condição fica falsa e o programa segue pro println de encerramento.",

            "natanael" =>
                "O menu permanece ativo para qualquer valor diferente de zero. Ao receber zero, a próxima verificação falha, o laço termina e a execução continua depois das chaves do while.",

            "pergunta" =>
                "Qual opção encerra o menu?",

            "opcoes" => [

                [
                    "texto" =>
                        "0 - Sair",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "1 - Adicionar tarefa",

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
                "Adicionando uma tarefa...",

            "mensagem" =>
                "Agora vamos usar uma das opções do switch para receber uma tarefa e colocá-la na lista.",

            "codigo" => <<<'JAVA'
import java.util.ArrayList;
import java.util.Scanner;

Scanner leitor =
    new Scanner(System.in);

ArrayList<String> tarefas =
    new ArrayList<>();

int opcao = 1;

switch (opcao) {

    case 1:

        System.out.print(
            "Digite a tarefa: "
        );

        String tarefa =
            leitor.nextLine();

        tarefas.add(tarefa);

        System.out.println(
            "Tarefa adicionada"
        );

        break;

    default:

        System.out.println(
            "Opção inválida"
        );

}

System.out.println(
    tarefas.size()
);

leitor.close();
JAVA,

            "contexto" =>
                "O usuário digita: Estudar Java",

            "pergunta" =>
                "Qual é o tamanho da lista depois dessa operação?",

            "opcoes" => [

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_zero"
                ],

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_dois"
                ],

                [
                    "texto" =>
                        "A lista guarda somente o número da opção",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_opcao"
                ],

                [
                    "texto" =>
                        "Não sei como a tarefa entra na lista",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE CONTINUA ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_zero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A lista começa vazia, mas depois tarefas.add(tarefa) coloca Estudar Java dentro dela. Então deixa de ter tamanho zero.",

            "natanael" =>
                "O ArrayList é criado inicialmente sem elementos. Entretanto a opção 1 é executada, nextLine recebe a tarefa e add acrescenta esse texto à lista.",

            "pergunta" =>
                "Qual método acrescenta o texto ao ArrayList?",

            "opcoes" => [

                [
                    "texto" =>
                        "add()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "size()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_zero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE FORAM ADICIONADOS DOIS ITENS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_dois" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Só teve um add. A opção 1 não vira item da lista. Ela só escolhe qual parte do switch será executada.",

            "natanael" =>
                "A variável opcao controla o fluxo do menu, mas não é adicionada ao ArrayList. Apenas o conteúdo recebido em tarefa participa da chamada tarefas.add(tarefa).",

            "pergunta" =>
                "Quantas chamadas de tarefas.add aparecem?",

            "opcoes" => [

                [
                    "texto" =>
                        "Uma",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Duas",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_dois"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUNDIU OPÇÃO COM TAREFA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_opcao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A opção só manda entrar no case 1. O valor adicionado é a variável tarefa, que recebeu Estudar Java pelo nextLine.",

            "natanael" =>
                "O número 1 é utilizado pelo switch para selecionar uma ação. Dentro dessa ação, o texto digitado é armazenado em tarefa e depois acrescentado à lista.",

            "pergunta" =>
                "Qual variável aparece dentro de tarefas.add(...)?",

            "opcoes" => [

                [
                    "texto" =>
                        "tarefa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "opcao",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_opcao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — NÃO SEI
        |--------------------------------------------------------------------------
        */

        "pergunta_2_nao_sei" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "O usuário digita Estudar Java. nextLine entrega esse texto pra variável tarefa. Depois add coloca a tarefa dentro da lista.",

            "natanael" =>
                "O fluxo ocorre em três etapas: receber a linha digitada, armazená-la em uma variável e acrescentar essa variável ao ArrayList. Ao final, existe um elemento armazenado.",

            "pergunta" =>
                "Qual texto fica guardado na primeira posição?",

            "opcoes" => [

                [
                    "texto" =>
                        "Estudar Java",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Opção 1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_opcao"
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
                "A lista fica assim: [Estudar Java]. Um item dentro, então size() devolve 1.",

            "natanael" =>
                "Depois da chamada add, a lista possui apenas o texto Estudar Java. Como existe um elemento armazenado, o método size devolve 1.",

            "pergunta" =>
                "Quanto tarefas.size() mostra?",

            "opcoes" => [

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_zero"
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
                "Mostrando todas as tarefas...",

            "mensagem" =>
                "A opção de listagem utiliza um for para percorrer todos os elementos do ArrayList.",

            "codigo" => <<<'JAVA'
ArrayList<String> tarefas =
    new ArrayList<>();

tarefas.add("Estudar Java");
tarefas.add("Treinar");
tarefas.add("Organizar o quarto");

for (
    int i = 0;
    i < tarefas.size();
    i++
) {

    System.out.println(
        (i + 1) +
        " - " +
        tarefas.get(i)
    );

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "1 - Estudar Java, 2 - Treinar e 3 - Organizar o quarto",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "0 - Estudar Java, 1 - Treinar e 2 - Organizar o quarto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_numeracao"
                ],

                [
                    "texto" =>
                        "Aparece somente Estudar Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_um"
                ],

                [
                    "texto" =>
                        "O código tenta acessar tarefas[3]",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_limite"
                ],

                [
                    "texto" =>
                        "Não sei como o for percorre a lista",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU ÍNDICE COM NUMERAÇÃO VISUAL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_numeracao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Por dentro, os índices são 0, 1 e 2. Mas na hora de mostrar pro usuário o código usa i + 1. Então aparece 1, 2 e 3.",

            "natanael" =>
                "O índice continua começando em zero para acessar corretamente o ArrayList. Entretanto a numeração apresentada utiliza i + 1 para ficar mais natural para o usuário.",

            "codigo" => <<<'JAVA'
i = 0
i + 1 = 1
tarefas.get(0) = "Estudar Java"
JAVA,

            "pergunta" =>
                "Quando i vale 0, qual número é mostrado por i + 1?",

            "opcoes" => [

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_numeracao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O FOR EXECUTA UMA VEZ
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_um" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Depois da primeira tarefa, i++ faz o índice virar 1. Como 1 ainda é menor que size 3, o for continua.",

            "natanael" =>
                "O contador é atualizado depois de cada execução. Ele assume os valores 0, 1 e 2, acessando uma tarefa diferente em cada passagem.",

            "pergunta" =>
                "Quantos elementos existem em tarefas?",

            "opcoes" => [

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_um"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE ULTRAPASSA O LIMITE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_limite" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O tamanho é 3, mas a condição usa i < 3. Então passa por 0, 1 e 2. Quando i vira 3, para antes de acessar outra posição.",

            "natanael" =>
                "A condição utiliza menor que, e não menor ou igual. Quando o contador chega a 3, a expressão 3 < 3 é falsa. Assim nenhum acesso ao índice 3 acontece.",

            "pergunta" =>
                "Qual é o último índice válido da lista com três elementos?",

            "opcoes" => [

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_limite"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — NÃO SEI
        |--------------------------------------------------------------------------
        */

        "pergunta_3_nao_sei" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "O i funciona como endereço da tarefa. Primeiro pega get(0), depois get(1) e por último get(2). O i + 1 serve só pra mostrar uma contagem mais bonita.",

            "natanael" =>
                "O contador possui dois usos. Dentro de get, ele representa o índice real da lista. Na parte visual, i + 1 transforma os índices 0, 1 e 2 nos números 1, 2 e 3.",

            "pergunta" =>
                "Quando i vale 1, qual tarefa é acessada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Treinar",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Estudar Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_numeracao"
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
                "O for percorre 0, 1 e 2. O get pega cada tarefa, mas o número mostrado é i + 1. Por isso a lista aparece numerada de 1 até 3.",

            "natanael" =>
                "Cada repetição consulta uma posição válida. A tarefa é recuperada com get(i), enquanto a numeração visível é calculada com i + 1.",

            "pergunta" =>
                "Qual listagem completa será exibida?",

            "opcoes" => [

                [
                    "texto" =>
                        "1 - Estudar Java → 2 - Treinar → 3 - Organizar o quarto",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "0 - Estudar Java → 1 - Treinar → 2 - Organizar o quarto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_numeracao"
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
                "Removendo uma tarefa escolhida...",

            "mensagem" =>
                "A lista aparece para o usuário começando em 1, mas os índices internos continuam começando em zero.",

            "codigo" => <<<'JAVA'
ArrayList<String> tarefas =
    new ArrayList<>();

tarefas.add("Estudar Java");
tarefas.add("Treinar");
tarefas.add("Organizar o quarto");

String entrada = "2";

try {

    int numero =
        Integer.parseInt(entrada);

    int indice =
        numero - 1;

    if (
        indice >= 0 &&
        indice < tarefas.size()
    ) {

        String removida =
            tarefas.remove(indice);

        System.out.println(
            "Removida: " + removida
        );

    } else {

        System.out.println(
            "Tarefa inexistente"
        );

    }

} catch (NumberFormatException erro) {

    System.out.println(
        "Digite um número válido"
    );

}

System.out.println(tarefas);
JAVA,

            "pergunta" =>
                "Qual é o resultado final dessa operação?",

            "opcoes" => [

                [
                    "texto" =>
                        "Remove Treinar e a lista fica [Estudar Java, Organizar o quarto]",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Remove Organizar o quarto porque o número digitado foi 2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_indice"
                ],

                [
                    "texto" =>
                        "Mostra Tarefa inexistente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_validacao"
                ],

                [
                    "texto" =>
                        "Mostra Digite um número válido",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_conversao"
                ],

                [
                    "texto" =>
                        "A lista continua com as três tarefas",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_remove"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUNDIU NÚMERO VISUAL COM ÍNDICE
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_indice" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O usuário escolheu a tarefa número 2, mas o código faz numero - 1. Então o índice usado é 1, que pertence a Treinar.",

            "natanael" =>
                "A numeração apresentada ao usuário começa em um. Para converter essa escolha em índice, o programa subtrai uma unidade. Assim, a escolha 2 corresponde ao índice 1.",

            "codigo" => <<<'JAVA'
numero = 2
indice = numero - 1
indice = 1
JAVA,

            "pergunta" =>
                "Qual tarefa está no índice 1?",

            "opcoes" => [

                [
                    "texto" =>
                        "Treinar",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Organizar o quarto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_indice"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE A VALIDAÇÃO FALHA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_validacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O índice calculado é 1. Ele é maior ou igual a zero e também menor que o tamanho 3. Então é uma posição válida.",

            "natanael" =>
                "A validação confirma as duas extremidades. O índice não pode ser negativo e precisa ser menor que a quantidade de tarefas. O valor 1 satisfaz ambas as regras.",

            "pergunta" =>
                "A expressão 1 >= 0 && 1 < 3 é verdadeira?",

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
                        "pergunta_4_erro_validacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE A CONVERSÃO FALHA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_conversao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A entrada é a String \"2\", que tem formato numérico válido. parseInt consegue transformar ela no inteiro 2 sem cair no catch.",

            "natanael" =>
                "O tratamento seria utilizado se a entrada fosse algo como \"dois\" ou \"Java\". Como o conteúdo é formado pelo dígito 2, a conversão acontece normalmente.",

            "pergunta" =>
                "Integer.parseInt(\"2\") consegue gerar qual valor?",

            "opcoes" => [

                [
                    "texto" =>
                        "O inteiro 2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Nenhum valor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_conversao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE NÃO REMOVE
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_remove" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O índice passou na validação e tarefas.remove(indice) foi executado. Isso tira o item da lista e ainda devolve o texto removido.",

            "natanael" =>
                "Como a posição é válida, o método remove é chamado com o índice 1. O elemento Treinar é retirado, e os itens posteriores são reorganizados.",

            "pergunta" =>
                "Qual método altera a lista removendo o elemento?",

            "opcoes" => [

                [
                    "texto" =>
                        "remove()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "get()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_remove"
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
                "Segue o fluxo: \"2\" vira o número 2. O código tira 1 e chega no índice 1. Esse índice é válido e contém Treinar. Depois da remoção sobram a primeira e a terceira tarefas.",

            "natanael" =>
                "A entrada é convertida com sucesso. A escolha visual 2 é transformada no índice interno 1. Como a posição existe, Treinar é removida e Organizar o quarto passa a ocupar o índice 1.",

            "pergunta" =>
                "Qual lista permanece depois da remoção?",

            "opcoes" => [

                [
                    "texto" =>
                        "[Estudar Java, Organizar o quarto]",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "[Estudar Java, Treinar]",

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
                "Primeiro projeto no terminal concluído!",

            "robert" =>
                "Boa po kkkkk. Agora tu juntou várias peças de verdade: o while mantém o menu aberto, switch escolhe a ação, Scanner recebe os dados, ArrayList guarda as tarefas, for mostra tudo e try com catch protege a conversão. Também viu a diferença entre o número mostrado pro usuário e o índice real da lista. Na próxima aula dá pra organizar esse programa em métodos pra não deixar tudo amontoado no main.",

            "natanael" =>
                "Concluímos um pequeno projeto utilizando conhecimentos construídos ao longo das aulas. A repetição mantém o sistema em funcionamento, a estrutura switch organiza as opções, Scanner recebe as entradas e ArrayList mantém as tarefas. O for percorre os elementos, enquanto validações e tratamento de exceções evitam operações inválidas. Também convertemos a numeração apresentada ao usuário em índices internos da lista. Na próxima aula vamos reorganizar esse projeto em métodos menores, deixando o código mais legível, reutilizável e fácil de manter.",

            "mensagem" =>
                "Tu concluiu a vigésima sexta Aula Conversada."

        ]

    ]

];