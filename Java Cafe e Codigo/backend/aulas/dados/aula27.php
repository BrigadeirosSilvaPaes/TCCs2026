<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 27,

    "titulo" =>
        "Cada parte no seu lugar",

    "assunto" =>
        "Organizando o projeto em métodos",

    "descricao" =>
        "Descubra como dividir um programa em métodos menores para deixar o código mais organizado, reutilizável e fácil de entender.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        28,


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
                "Tirando o menu de dentro do main...",

            "mensagem" =>
                "O código responsável por mostrar o menu foi colocado em um método separado.",

            "codigo" => <<<'JAVA'
public class Main {

    static void mostrarMenu() {

        System.out.println(
            "1 - Adicionar tarefa"
        );

        System.out.println(
            "2 - Listar tarefas"
        );

        System.out.println(
            "0 - Sair"
        );

    }


    public static void main(String[] args) {

        System.out.println(
            "Antes do menu"
        );

        mostrarMenu();

        System.out.println(
            "Depois do menu"
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual é a ordem das mensagens apresentadas?",

            "opcoes" => [

                [
                    "texto" =>
                        "Antes do menu, opções do menu e depois Depois do menu",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Somente Antes do menu e Depois do menu",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nao_executa"
                ],

                [
                    "texto" =>
                        "As opções aparecem antes de Antes do menu",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ordem"
                ],

                [
                    "texto" =>
                        "O programa termina dentro de mostrarMenu",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_termina"
                ],

                [
                    "texto" =>
                        "Não sei como a chamada do método funciona",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O MÉTODO NÃO EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_nao_executa" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método não executa sozinho só porque foi criado. Mas dentro do main aparece mostrarMenu();. Essa linha chama o método e executa todos os println dele.",

            "natanael" =>
                "A declaração do método apenas define o comportamento. A execução acontece quando o main encontra a chamada mostrarMenu(). Nesse momento, o fluxo entra no método e executa suas instruções.",

            "pergunta" =>
                "Qual linha chama o método responsável por apresentar as opções?",

            "opcoes" => [

                [
                    "texto" =>
                        "mostrarMenu();",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "static void mostrarMenu()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nao_executa"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU A ORDEM
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_ordem" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O Java começa pelo main e segue de cima pra baixo. Primeiro mostra Antes do menu. Só depois encontra mostrarMenu().",

            "natanael" =>
                "A existência do método acima do main não significa que ele será executado primeiro. A execução começa pelo main e respeita a ordem das instruções presentes dentro dele.",

            "pergunta" =>
                "Qual é a primeira instrução executada dentro do main?",

            "opcoes" => [

                [
                    "texto" =>
                        "O println de Antes do menu",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "O primeiro println de mostrarMenu",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ordem"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O PROGRAMA TERMINA NO MÉTODO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_termina" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Quando mostrarMenu termina, o programa volta exatamente pra linha seguinte da chamada. Aí executa Depois do menu.",

            "natanael" =>
                "A chamada funciona como um desvio temporário. O fluxo entra em mostrarMenu, executa suas instruções e retorna ao ponto onde o método foi chamado, continuando na próxima linha do main.",

            "pergunta" =>
                "Depois que mostrarMenu termina, para onde o fluxo retorna?",

            "opcoes" => [

                [
                    "texto" =>
                        "Para a próxima linha do main",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "O programa é encerrado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_termina"
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
                "Pensa numa lista de tarefas. O main fala: primeiro faz isso, depois vai lá mostrar o menu e depois volta pra continuar. O método é uma tarefa separada.",

            "natanael" =>
                "Imagine o main como alguém coordenando atividades. Quando chega à chamada mostrarMenu, ele entrega temporariamente essa responsabilidade ao método. Quando o método termina, o controle retorna ao main.",

            "pergunta" =>
                "O que acontece quando o main encontra mostrarMenu();?",

            "opcoes" => [

                [
                    "texto" =>
                        "As instruções de mostrarMenu são executadas",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "A chamada é ignorada",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nao_executa"
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
                "O fluxo fica assim: começa no main, mostra Antes, chama mostrarMenu, mostra as três opções, volta pro main e mostra Depois.",

            "natanael" =>
                "A chamada não altera a ordem geral. Ela apenas executa um bloco organizado em outro local. Depois que esse bloco termina, o fluxo principal continua normalmente.",

            "pergunta" =>
                "Qual sequência está correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Antes → menu → Depois",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Menu → Antes → programa encerrado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ordem"
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
                "Enviando a lista para outro método...",

            "mensagem" =>
                "O método adicionarTarefa recebe um ArrayList e acrescenta um elemento nele.",

            "codigo" => <<<'JAVA'
import java.util.ArrayList;

public class Main {

    static void adicionarTarefa(
        ArrayList<String> tarefas
    ) {

        tarefas.add(
            "Estudar Java"
        );

    }


    public static void main(String[] args) {

        ArrayList<String> tarefas =
            new ArrayList<>();

        adicionarTarefa(tarefas);

        System.out.println(
            tarefas.size()
        );

        System.out.println(
            tarefas.get(0)
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "1 e depois Estudar Java",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "0 e depois acontece erro ao acessar get(0)",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_copia"
                ],

                [
                    "texto" =>
                        "1 e depois tarefas",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_texto"
                ],

                [
                    "texto" =>
                        "2 e depois Estudar Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_dois"
                ],

                [
                    "texto" =>
                        "Não sei o que o parâmetro tarefas recebe",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE A LISTA FOI COPIADA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_copia" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método não cria outro ArrayList vazio. Ele recebe acesso ao mesmo objeto criado no main. Então o add modifica a lista que depois é consultada.",

            "natanael" =>
                "O Java envia ao parâmetro uma cópia da referência para o mesmo objeto ArrayList. Portanto o parâmetro e a variável do main apontam para a mesma lista. Alterar esse objeto dentro do método permanece visível depois da chamada.",

            "pergunta" =>
                "O método adiciona a tarefa em uma lista diferente ou no mesmo objeto recebido?",

            "opcoes" => [

                [
                    "texto" =>
                        "No mesmo objeto recebido",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Em uma lista completamente separada",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_copia"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE MOSTRA O NOME DA VARIÁVEL
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_texto" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "get(0) devolve o elemento guardado na primeira posição. O texto colocado pelo add foi Estudar Java.",

            "natanael" =>
                "O método get não devolve o nome da variável. Ele consulta o conteúdo armazenado no índice informado. Na posição zero está a String Estudar Java.",

            "pergunta" =>
                "Qual texto foi enviado para tarefas.add(...)?",

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
                        "tarefas",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_texto"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE FORAM ADICIONADOS DOIS ELEMENTOS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_dois" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Só existe uma chamada de add. Chamar o método não entra na lista como outro item kkkkk. Ele apenas executa a adição.",

            "natanael" =>
                "A chamada adicionarTarefa executa uma única instrução add. Nenhum outro elemento é acrescentado. Por isso o tamanho final é um.",

            "pergunta" =>
                "Quantas chamadas de tarefas.add aparecem dentro do método?",

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
        | PERGUNTA 2 — NÃO SEI
        |--------------------------------------------------------------------------
        */

        "pergunta_2_nao_sei" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Quando chama adicionarTarefa(tarefas), a lista criada no main entra no parâmetro tarefas do método. Aí o método coloca Estudar Java nela.",

            "natanael" =>
                "O parâmetro funciona como uma variável que recebe o valor enviado na chamada. Nesse caso, ele permite que adicionarTarefa trabalhe com o ArrayList criado no main.",

            "pergunta" =>
                "Qual objeto foi enviado na chamada adicionarTarefa(tarefas)?",

            "opcoes" => [

                [
                    "texto" =>
                        "O ArrayList criado no main",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Uma String vazia",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_nao_sei"
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
                "A lista começa vazia. O método recebe acesso a ela e adiciona Estudar Java. Depois size dá 1 e get(0) pega esse texto.",

            "natanael" =>
                "O objeto é criado antes da chamada, modificado dentro do método e consultado depois. Como todos esses acessos envolvem o mesmo ArrayList, a alteração permanece.",

            "pergunta" =>
                "Qual é o conteúdo final da lista?",

            "opcoes" => [

                [
                    "texto" =>
                        "[Estudar Java]",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "[]",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_copia"
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
                "Um método também pode devolver um resultado...",

            "mensagem" =>
                "O método receberOpcao tenta converter uma entrada e devolve um valor para quem realizou a chamada.",

            "codigo" => <<<'JAVA'
public class Main {

    static int receberOpcao(
        String entrada
    ) {

        try {

            return Integer.parseInt(
                entrada
            );

        } catch (NumberFormatException erro) {

            return -1;

        }

    }


    public static void main(String[] args) {

        int opcao =
            receberOpcao("2");

        System.out.println(opcao);

        System.out.println(
            "Programa continuou"
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "2 e depois Programa continuou",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "-1 e depois Programa continuou",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_catch"
                ],

                [
                    "texto" =>
                        "\"2\" e depois Programa continuou",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_string"
                ],

                [
                    "texto" =>
                        "O return encerra todo o programa antes do segundo println",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_encerra"
                ],

                [
                    "texto" =>
                        "Não sei para onde o return envia o valor",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O CATCH EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_catch" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O -1 só volta quando a conversão dá erro. Como \"2\" é um texto numérico válido, parseInt funciona e o catch é pulado.",

            "natanael" =>
                "A entrada contém um formato válido para inteiro. Portanto o primeiro return é alcançado e devolve 2. O bloco catch não é utilizado.",

            "pergunta" =>
                "Integer.parseInt(\"2\") consegue produzir um inteiro?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim, produz 2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Não, sempre lança exceção",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_catch"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE DEVOLVE STRING
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_string" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A entrada começa como String, mas parseInt transforma ela em int. Além disso, o método foi declarado como static int, então ele devolve um inteiro.",

            "natanael" =>
                "O tipo de retorno foi declarado como int. A conversão transforma o conteúdo textual em valor numérico antes de o return enviá-lo para a variável opcao.",

            "pergunta" =>
                "Qual é o tipo declarado antes do nome receberOpcao?",

            "opcoes" => [

                [
                    "texto" =>
                        "int",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_string"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE RETURN ENCERRA O PROGRAMA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_encerra" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O return encerra só o método receberOpcao. Ele devolve o valor pro main, e o main continua na próxima linha.",

            "natanael" =>
                "O return finaliza a execução do método em que está localizado. Depois disso, o valor devolvido é utilizado pela chamada, e o fluxo do main continua normalmente.",

            "pergunta" =>
                "Qual método é encerrado quando return 2 é executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "receberOpcao",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Todo o programa Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_encerra"
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
                "Pensa no método como alguém fazendo uma conta e entregando o resultado. receberOpcao recebe \"2\", transforma em 2 e entrega esse número pro main.",

            "natanael" =>
                "A chamada aguarda um resultado. O método processa a entrada e utiliza return para enviar o valor produzido de volta. Esse valor é então armazenado em opcao.",

            "pergunta" =>
                "Qual variável recebe o resultado devolvido pelo método?",

            "opcoes" => [

                [
                    "texto" =>
                        "opcao",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "entrada",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_nao_sei"
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
                "\"2\" vira o inteiro 2. O return entrega esse valor pro main. A variável opcao recebe 2 e depois o programa segue normalmente.",

            "natanael" =>
                "A conversão é concluída, o método devolve o inteiro e sua execução termina. O main recebe esse resultado e continua nas instruções posteriores.",

            "pergunta" =>
                "Qual valor fica guardado em opcao?",

            "opcoes" => [

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "-1",

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
                "Juntando os métodos no projeto...",

            "mensagem" =>
                "Agora observa o projeto organizado. O main controla o fluxo, mas cada ação foi colocada em seu próprio método.",

            "codigo" => <<<'JAVA'
import java.util.ArrayList;
import java.util.Scanner;

public class Main {

    static void mostrarMenu() {

        System.out.println(
            "1 - Adicionar tarefa"
        );

        System.out.println(
            "2 - Listar tarefas"
        );

        System.out.println(
            "0 - Sair"
        );

    }


    static int receberOpcao(
        Scanner leitor
    ) {

        try {

            return Integer.parseInt(
                leitor.nextLine()
            );

        } catch (NumberFormatException erro) {

            return -1;

        }

    }


    static void adicionarTarefa(
        ArrayList<String> tarefas,
        Scanner leitor
    ) {

        System.out.print(
            "Digite a tarefa: "
        );

        String tarefa =
            leitor.nextLine();

        tarefas.add(tarefa);

        System.out.println(
            "Tarefa adicionada"
        );

    }


    static void listarTarefas(
        ArrayList<String> tarefas
    ) {

        if (tarefas.isEmpty()) {

            System.out.println(
                "Nenhuma tarefa cadastrada"
            );

            return;

        }

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

    }


    public static void main(String[] args) {

        Scanner leitor =
            new Scanner(System.in);

        ArrayList<String> tarefas =
            new ArrayList<>();

        int opcao = -1;


        while (opcao != 0) {

            mostrarMenu();

            opcao =
                receberOpcao(leitor);

            switch (opcao) {

                case 1:

                    adicionarTarefa(
                        tarefas,
                        leitor
                    );

                    break;

                case 2:

                    listarTarefas(
                        tarefas
                    );

                    break;

                case 0:

                    System.out.println(
                        "Programa encerrado"
                    );

                    break;

                default:

                    System.out.println(
                        "Opção inválida"
                    );

            }

        }

        leitor.close();

    }

}
JAVA,

            "contexto" =>
                "O usuário digita, nesta ordem: 1, Estudar Java, 2 e 0.",

            "pergunta" =>
                "Desconsiderando as linhas repetidas do menu, quais mensagens principais aparecem?",

            "opcoes" => [

                [
                    "texto" =>
                        "Tarefa adicionada, 1 - Estudar Java e depois Programa encerrado",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Tarefa adicionada, Nenhuma tarefa cadastrada e depois Programa encerrado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_lista"
                ],

                [
                    "texto" =>
                        "Tarefa adicionada e o programa encerra sem listar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_while"
                ],

                [
                    "texto" =>
                        "Opção inválida aparece quando o usuário digita 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_zero"
                ],

                [
                    "texto" =>
                        "O código não funciona porque os métodos estão fora do main",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_metodos"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE A LISTA CONTINUA VAZIA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_lista" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "adicionarTarefa recebe a mesma lista criada no main e coloca Estudar Java nela. Quando listarTarefas recebe essa lista depois, ela já tem um item.",

            "natanael" =>
                "O ArrayList é criado uma única vez no main e enviado aos dois métodos. A primeira ação modifica esse objeto. Na segunda ação, listarTarefas recebe o mesmo ArrayList já preenchido.",

            "pergunta" =>
                "Depois de adicionarTarefa, tarefas.isEmpty() retorna true ou false?",

            "opcoes" => [

                [
                    "texto" =>
                        "false",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_lista"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE O WHILE NÃO CONTINUA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_while" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Depois da opção 1, o case termina no break, mas o while continua porque opcao vale 1 e 1 é diferente de 0. Aí o menu aparece de novo.",

            "natanael" =>
                "O break encerra apenas o switch, não o while. Como a condição do laço ainda é verdadeira, uma nova repetição começa e permite que o usuário escolha a opção 2.",

            "pergunta" =>
                "O break dentro do case encerra o switch ou todo o while?",

            "opcoes" => [

                [
                    "texto" =>
                        "Somente o switch",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Todo o while",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_while"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE ZERO É INVÁLIDO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_zero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Zero tem um case próprio. Quando opcao vale 0, entra no case 0, mostra Programa encerrado e depois o while termina.",

            "natanael" =>
                "A opção zero não segue para default porque existe uma correspondência exata em case 0. Depois dessa execução, a condição opcao != 0 se torna falsa.",

            "pergunta" =>
                "Existe um case 0 dentro do switch?",

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
                        "pergunta_4_erro_zero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE MÉTODOS PRECISAM FICAR DENTRO DO MAIN
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_metodos" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Método não fica dentro de outro método. Eles ficam dentro da classe, cada um separado. O main consegue chamar esses métodos porque eles estão disponíveis na classe Main.",

            "natanael" =>
                "Em Java, os métodos são declarados no corpo da classe e não dentro do método main. O main é apenas um dos métodos da classe e pode chamar os demais.",

            "pergunta" =>
                "mostrarMenu e adicionarTarefa estão dentro da classe Main?",

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
                        "Não, estão fora da classe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_metodos"
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
                "Segue as escolhas: 1 chama adicionarTarefa e guarda Estudar Java. O while volta. 2 chama listarTarefas e mostra o item. Depois 0 entra no case de saída e encerra.",

            "natanael" =>
                "Cada opção direciona o fluxo para um método específico. A lista permanece disponível entre as repetições porque foi criada antes do while. O valor zero encerra o processo somente depois das ações anteriores.",

            "pergunta" =>
                "Qual sequência principal está correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Adicionar → listar Estudar Java → encerrar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Adicionar → perder a lista → opção inválida",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_lista"
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
                "Projeto organizado em métodos!",

            "robert" =>
                "Boa po kkkkk. Agora o main não precisa fazer tudo sozinho. Ele controla o fluxo e chama métodos menores. mostrarMenu mostra, receberOpcao devolve a escolha, adicionarTarefa modifica a lista e listarTarefas percorre os itens. Cada método fica com uma responsabilidade. Assim o código fica muito mais fácil de entender, testar e alterar sem virar uma bagunça.",

            "natanael" =>
                "Concluímos a organização do projeto em métodos. O main passou a representar apenas o fluxo principal, enquanto responsabilidades específicas foram distribuídas em partes menores. Também vimos métodos sem retorno, métodos que recebem parâmetros e métodos que devolvem resultados com return. Essa separação reduz repetições, melhora a leitura e facilita futuras alterações. Na próxima aula vamos aprofundar o funcionamento de parâmetros e valores de retorno, entendendo como as informações entram e saem de um método.",

            "mensagem" =>
                "Tu concluiu a vigésima sétima Aula Conversada."

        ]

    ]

];