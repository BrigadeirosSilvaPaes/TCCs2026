<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 30,

    "titulo" =>
        "Transformando o projeto em objetos",

    "assunto" =>
        "Projeto orientado a objetos",

    "descricao" =>
        "Aplique classes, objetos, encapsulamento, ArrayList, métodos e Scanner em um gerenciador de tarefas orientado a objetos.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        31,


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
                "Cada tarefa controlando seu próprio estado...",

            "mensagem" =>
                "A classe Tarefa guarda a descrição e informa se aquela atividade já foi concluída.",

            "codigo" => <<<'JAVA'
class Tarefa {

    private String descricao;

    private boolean concluida;


    Tarefa(String descricao) {

        this.descricao = descricao;

        this.concluida = false;

    }


    void concluir() {

        concluida = true;

    }


    String getResumo() {

        if (concluida) {

            return "[x] " + descricao;

        }

        return "[ ] " + descricao;

    }

}

public class Main {

    public static void main(String[] args) {

        Tarefa tarefa =
            new Tarefa("Estudar Java");

        System.out.println(
            tarefa.getResumo()
        );

        tarefa.concluir();

        System.out.println(
            tarefa.getResumo()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "[ ] Estudar Java e depois [x] Estudar Java",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "[x] Estudar Java duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_inicial"
                ],

                [
                    "texto" =>
                        "[ ] Estudar Java duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_concluir"
                ],

                [
                    "texto" =>
                        "true e depois false",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_boolean"
                ],

                [
                    "texto" =>
                        "Não sei como o estado da tarefa muda",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE COMEÇA CONCLUÍDA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_inicial" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Ela não começa concluída. No construtor aparece this.concluida = false. Então o primeiro resumo usa o quadradinho vazio.",

            "natanael" =>
                "Durante a criação do objeto, o construtor define explicitamente o atributo concluida como false. Por isso, antes da chamada concluir, a tarefa ainda aparece como pendente.",

            "pergunta" =>
                "Qual valor o construtor coloca inicialmente em concluida?",

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
                        "pergunta_1_erro_inicial"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE CONCLUIR NÃO ALTERA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_concluir" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Depois do primeiro resumo, tarefa.concluir() é chamado. Esse método muda concluida pra true. Então o segundo resumo precisa mudar também.",

            "natanael" =>
                "A chamada concluir modifica o estado interno do objeto. A partir desse momento, getResumo encontra concluida com valor true e utiliza a representação de tarefa finalizada.",

            "pergunta" =>
                "O que o método concluir coloca no atributo concluida?",

            "opcoes" => [

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "false",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_concluir"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE MOSTRA O BOOLEAN
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_boolean" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O boolean não é mostrado direto. getResumo usa ele pra escolher entre [ ] e [x], juntando isso com a descrição.",

            "natanael" =>
                "O atributo concluida funciona como uma condição interna. O método não devolve o próprio boolean; ele devolve uma String diferente conforme o estado da tarefa.",

            "pergunta" =>
                "Qual é o tipo devolvido por getResumo?",

            "opcoes" => [

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "boolean",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_boolean"
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
                "Pensa numa tarefa escrita numa agenda. Primeiro ela está sem marcação. Quando tu conclui, faz um xis nela. O objeto guarda essa mudança.",

            "natanael" =>
                "A mesma instância permanece durante todo o programa. Inicialmente ela mantém o estado pendente. Depois o método concluir altera esse estado, e os próximos métodos passam a enxergar o novo valor.",

            "pergunta" =>
                "Depois de tarefa.concluir(), a tarefa está pendente ou concluída?",

            "opcoes" => [

                [
                    "texto" =>
                        "Concluída",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Pendente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_concluir"
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
                "Primeiro concluida vale false e aparece [ ]. Depois concluir muda pra true e aparece [x].",

            "natanael" =>
                "O primeiro resumo representa o estado inicial. Após a modificação realizada pelo método concluir, o segundo resumo representa o estado atualizado.",

            "pergunta" =>
                "Qual sequência completa está correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "[ ] Estudar Java → [x] Estudar Java",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "[x] Estudar Java → [ ] Estudar Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_inicial"
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
                "Uma classe responsável pela lista...",

            "mensagem" =>
                "Agora o GerenciadorTarefas cria e organiza vários objetos do tipo Tarefa.",

            "codigo" => <<<'JAVA'
import java.util.ArrayList;

class Tarefa {

    private String descricao;


    Tarefa(String descricao) {

        this.descricao = descricao;

    }


    String getDescricao() {

        return descricao;

    }

}

class GerenciadorTarefas {

    private ArrayList<Tarefa> tarefas =
        new ArrayList<>();


    void adicionar(
        String descricao
    ) {

        Tarefa tarefa =
            new Tarefa(descricao);

        tarefas.add(tarefa);

    }


    int quantidade() {

        return tarefas.size();

    }

}

public class Main {

    public static void main(String[] args) {

        GerenciadorTarefas gerenciador =
            new GerenciadorTarefas();

        gerenciador.adicionar(
            "Estudar Java"
        );

        gerenciador.adicionar(
            "Treinar"
        );

        System.out.println(
            gerenciador.quantidade()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual número aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "2",

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
                        "pergunta_2_erro_vazia"
                ],

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_uma"
                ],

                [
                    "texto" =>
                        "4",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_quatro"
                ],

                [
                    "texto" =>
                        "O ArrayList guarda somente textos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE A LISTA CONTINUA VAZIA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_vazia" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A lista começa vazia, mas adicionar é chamado duas vezes. Em cada chamada ele cria uma Tarefa e coloca ela no ArrayList.",

            "natanael" =>
                "A inicialização produz uma coleção vazia. Entretanto cada execução de adicionar cria uma nova instância e realiza uma chamada tarefas.add. Ao final existem dois elementos.",

            "pergunta" =>
                "Quantas chamadas de gerenciador.adicionar aparecem?",

            "opcoes" => [

                [
                    "texto" =>
                        "Duas",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Nenhuma",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_vazia"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONSIDEROU APENAS UMA TAREFA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_uma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Depois de Estudar Java ainda tem outra chamada adicionando Treinar. Cada chamada coloca um objeto diferente na lista.",

            "natanael" =>
                "A primeira chamada produz a tarefa Estudar Java. A segunda produz outra instância com a descrição Treinar. O segundo objeto não substitui o primeiro.",

            "pergunta" =>
                "Qual tarefa é adicionada na segunda chamada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Treinar",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Nenhuma",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_uma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE CADA TAREFA CONTA DUAS VEZES
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_quatro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A descrição não vira um item separado. Cada add coloca um objeto Tarefa inteiro dentro da lista. Duas tarefas significam tamanho 2.",

            "natanael" =>
                "O ArrayList contabiliza seus elementos, e cada elemento é uma referência do tipo Tarefa. Os atributos internos desses objetos não são contados como posições adicionais.",

            "pergunta" =>
                "Quantos objetos Tarefa foram criados?",

            "opcoes" => [

                [
                    "texto" =>
                        "Dois",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Quatro",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_quatro"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUNDIU O TIPO DA LISTA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A lista foi declarada como ArrayList<Tarefa>. O texto entra no construtor, vira parte de um objeto Tarefa e aí esse objeto é adicionado.",

            "natanael" =>
                "A descrição recebida é uma String, mas ela é utilizada para construir uma instância. O elemento armazenado pela coleção é o objeto Tarefa resultante.",

            "codigo" => <<<'JAVA'
ArrayList<Tarefa> tarefas
JAVA,

            "pergunta" =>
                "Qual tipo de elemento essa lista guarda?",

            "opcoes" => [

                [
                    "texto" =>
                        "Tarefa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_tipo"
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
                "Cada chamada cria uma Tarefa nova e faz um add. Foram duas chamadas, então size devolve 2.",

            "natanael" =>
                "O gerenciador mantém uma coleção única. Depois de receber as duas descrições, essa coleção contém duas instâncias independentes.",

            "pergunta" =>
                "Qual é a quantidade final de tarefas?",

            "opcoes" => [

                [
                    "texto" =>
                        "2",

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
                        "pergunta_2_erro_uma"
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
                "Convertendo o número escolhido em índice...",

            "mensagem" =>
                "O usuário enxerga as tarefas começando pelo número 1, mas o ArrayList continua usando índices a partir de zero.",

            "codigo" => <<<'JAVA'
import java.util.ArrayList;

class Tarefa {

    private String descricao;

    private boolean concluida;


    Tarefa(String descricao) {

        this.descricao = descricao;

        this.concluida = false;

    }


    void concluir() {

        concluida = true;

    }


    String getResumo() {

        if (concluida) {

            return "[x] " + descricao;

        }

        return "[ ] " + descricao;

    }

}

class GerenciadorTarefas {

    private ArrayList<Tarefa> tarefas =
        new ArrayList<>();


    void adicionar(
        String descricao
    ) {

        tarefas.add(
            new Tarefa(descricao)
        );

    }


    void concluir(
        int numero
    ) {

        int indice =
            numero - 1;

        if (
            indice >= 0 &&
            indice < tarefas.size()
        ) {

            tarefas
                .get(indice)
                .concluir();

        }

    }


    void listar() {

        for (
            int i = 0;
            i < tarefas.size();
            i++
        ) {

            System.out.println(
                tarefas.get(i).getResumo()
            );

        }

    }

}

public class Main {

    public static void main(String[] args) {

        GerenciadorTarefas gerenciador =
            new GerenciadorTarefas();

        gerenciador.adicionar(
            "Estudar Java"
        );

        gerenciador.adicionar(
            "Treinar"
        );

        gerenciador.concluir(2);

        gerenciador.listar();

    }

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "[ ] Estudar Java e depois [x] Treinar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "[x] Estudar Java e depois [ ] Treinar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_indice"
                ],

                [
                    "texto" =>
                        "[x] Estudar Java e depois [x] Treinar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_duas"
                ],

                [
                    "texto" =>
                        "As duas tarefas continuam pendentes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_nenhuma"
                ],

                [
                    "texto" =>
                        "O número 2 é inválido porque a lista começa no índice 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_validacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU NÚMERO COM ÍNDICE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_indice" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O usuário escolheu a tarefa número 2. O método faz 2 - 1 e chega no índice 1. Esse índice pertence a Treinar.",

            "natanael" =>
                "A numeração externa começa em um. Para transformá-la em índice interno, o programa subtrai uma unidade. Assim, a escolha dois aponta para a segunda tarefa armazenada.",

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
                        "pergunta_3_dica"
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
        | PERGUNTA 3 — ACHA QUE CONCLUI TODAS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_duas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O código não percorre a lista concluindo tudo. Ele usa get(indice), pega só uma tarefa e chama concluir nela.",

            "natanael" =>
                "A operação seleciona apenas o elemento localizado no índice calculado. Não existe repetição nem chamada sobre os demais objetos.",

            "pergunta" =>
                "Quantos objetos recebem diretamente a chamada concluir()?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um objeto",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Todos os objetos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_duas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE NENHUMA É CONCLUÍDA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_nenhuma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O índice calculado é 1. Ele é válido numa lista com tamanho 2, então o código entra no if e conclui a tarefa.",

            "natanael" =>
                "A lista possui índices zero e um. Como o índice calculado é um, as duas condições são verdadeiras e a chamada concluir é executada.",

            "pergunta" =>
                "A expressão 1 >= 0 && 1 < 2 é verdadeira?",

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
                        "pergunta_3_erro_nenhuma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O NÚMERO É INVÁLIDO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_validacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O usuário pode escolher 1 ou 2. O método faz a conversão pro índice internamente. Escolher 2 vira índice 1, que existe.",

            "natanael" =>
                "A escolha informada não é usada diretamente como índice. Primeiro ela é convertida por numero - 1. O resultado fica dentro dos limites válidos.",

            "pergunta" =>
                "Depois da conversão, qual índice será utilizado?",

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
                        "2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_validacao"
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
                "A escolha 2 vira índice 1. O índice 1 guarda Treinar, então só essa tarefa muda pra concluída.",

            "natanael" =>
                "A primeira tarefa permanece com false. A segunda recebe a chamada concluir e passa a manter true. O método listar consulta o resumo das duas.",

            "pergunta" =>
                "Qual sequência completa será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "[ ] Estudar Java → [x] Treinar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "[x] Estudar Java → [ ] Treinar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_indice"
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
                "O projeto completo orientado a objetos...",

            "mensagem" =>
                "Agora o menu utiliza o GerenciadorTarefas, enquanto cada objeto Tarefa controla suas próprias informações.",

            "codigo" => <<<'JAVA'
import java.util.ArrayList;
import java.util.Scanner;

class Tarefa {

    private String descricao;

    private boolean concluida;


    Tarefa(String descricao) {

        this.descricao = descricao;

        this.concluida = false;

    }


    void concluir() {

        concluida = true;

    }


    String getResumo() {

        if (concluida) {

            return "[x] " + descricao;

        }

        return "[ ] " + descricao;

    }

}

class GerenciadorTarefas {

    private ArrayList<Tarefa> tarefas =
        new ArrayList<>();


    void adicionar(
        String descricao
    ) {

        tarefas.add(
            new Tarefa(descricao)
        );

        System.out.println(
            "Tarefa adicionada"
        );

    }


    void listar() {

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
                tarefas.get(i).getResumo()
            );

        }

    }


    void concluir(
        int numero
    ) {

        int indice =
            numero - 1;

        if (
            indice < 0 ||
            indice >= tarefas.size()
        ) {

            System.out.println(
                "Tarefa inexistente"
            );

            return;

        }

        tarefas
            .get(indice)
            .concluir();

        System.out.println(
            "Tarefa concluída"
        );

    }

}

public class Main {

    static void mostrarMenu() {

        System.out.println(
            "1 - Adicionar"
        );

        System.out.println(
            "2 - Listar"
        );

        System.out.println(
            "3 - Concluir"
        );

        System.out.println(
            "0 - Sair"
        );

    }


    static int receberNumero(
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


    public static void main(String[] args) {

        Scanner leitor =
            new Scanner(System.in);

        GerenciadorTarefas gerenciador =
            new GerenciadorTarefas();

        int opcao = -1;


        while (opcao != 0) {

            mostrarMenu();

            opcao =
                receberNumero(leitor);

            switch (opcao) {

                case 1:

                    System.out.print(
                        "Descrição: "
                    );

                    String descricao =
                        leitor.nextLine();

                    gerenciador.adicionar(
                        descricao
                    );

                    break;

                case 2:

                    gerenciador.listar();

                    break;

                case 3:

                    System.out.print(
                        "Número da tarefa: "
                    );

                    int numero =
                        receberNumero(leitor);

                    gerenciador.concluir(
                        numero
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
                "O usuário digita: 1, Estudar Java, 1, Treinar, 3, 1, 2 e 0.",

            "pergunta" =>
                "Desconsiderando os menus e as perguntas de entrada, quais mensagens principais aparecem?",

            "opcoes" => [

                [
                    "texto" =>
                        "Tarefa adicionada duas vezes, Tarefa concluída, 1 - [x] Estudar Java, 2 - [ ] Treinar e Programa encerrado",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Tarefa adicionada duas vezes, Tarefa inexistente e Programa encerrado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_numero"
                ],

                [
                    "texto" =>
                        "As duas tarefas aparecem concluídas",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_duas"
                ],

                [
                    "texto" =>
                        "A lista aparece vazia porque cada opção cria outro gerenciador",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_gerenciador"
                ],

                [
                    "texto" =>
                        "O código não funciona porque Tarefa e GerenciadorTarefas estão fora do main",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_classes"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE A ESCOLHA É INVÁLIDA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_numero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Quando escolhe concluir, o usuário digita 1. O método transforma esse número no índice 0. Como a lista tem duas tarefas, esse índice é válido.",

            "natanael" =>
                "A primeira tarefa é apresentada ao usuário com o número um. Internamente, a conversão produz o índice zero, que está dentro dos limites da coleção.",

            "pergunta" =>
                "Qual índice corresponde à tarefa número 1?",

            "opcoes" => [

                [
                    "texto" =>
                        "0",

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
                        "pergunta_4_erro_numero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE AS DUAS SÃO CONCLUÍDAS
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_duas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A opção de concluir recebe o número 1. Então só pega tarefas.get(0). Treinar fica sem receber a chamada concluir().",

            "natanael" =>
                "O método seleciona apenas uma instância de acordo com o número recebido. A segunda tarefa permanece com seu estado inicial false.",

            "pergunta" =>
                "Qual tarefa recebe a chamada concluir()?",

            "opcoes" => [

                [
                    "texto" =>
                        "Estudar Java",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Treinar e Estudar Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_duas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE O GERENCIADOR É RECRIADO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_gerenciador" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O gerenciador é criado uma vez, antes do while. Então todas as opções trabalham com o mesmo objeto e com a mesma lista.",

            "natanael" =>
                "A instância GerenciadorTarefas é criada antes da repetição. Ela permanece viva durante todas as voltas do menu, mantendo os objetos adicionados anteriormente.",

            "pergunta" =>
                "A criação new GerenciadorTarefas() está dentro ou fora do while?",

            "opcoes" => [

                [
                    "texto" =>
                        "Fora do while",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Dentro do while",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_gerenciador"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE CLASSES PRECISAM ESTAR NO MAIN
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_classes" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "As classes precisam ficar fora dos métodos mesmo. O main só cria objetos dessas classes e chama os métodos disponíveis.",

            "natanael" =>
                "Tarefa, GerenciadorTarefas e Main são classes separadas no mesmo arquivo de exemplo. Apenas Main é pública. O método main utiliza as outras classes normalmente.",

            "pergunta" =>
                "O main precisa conter o código interno de todas as classes?",

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
                        "pergunta_4_erro_classes"
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
                "O mesmo gerenciador recebe duas tarefas. Depois a escolha 1 conclui Estudar Java. Na listagem, a primeira aparece com x e Treinar continua pendente. Por último, a opção 0 encerra.",

            "natanael" =>
                "A instância mantém a coleção durante todo o menu. As duas adições permanecem armazenadas, a primeira tarefa recebe a alteração e a listagem consulta o estado atual de cada objeto antes do encerramento.",

            "pergunta" =>
                "Qual sequência final está correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Adicionar duas tarefas → concluir a primeira → listar uma concluída e uma pendente → encerrar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Adicionar duas tarefas → perder a lista → encerrar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_gerenciador"
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
                "Projeto orientado a objetos concluído!",

            "robert" =>
                "Boa po kkkkk. Agora o projeto ficou dividido de verdade. A Tarefa cuida da própria descrição e do próprio estado. O GerenciadorTarefas organiza a lista e decide como adicionar, concluir e mostrar os objetos. O Main fica responsável só pelo menu e pelas entradas do usuário. Isso evita jogar tudo num arquivo bagunçado e mostra como classes diferentes podem trabalhar juntas.",

            "natanael" =>
                "Concluímos o projeto orientado a objetos. A classe Tarefa representa cada atividade individual e protege seu estado. GerenciadorTarefas mantém a coleção e concentra as regras relacionadas à lista. Main controla somente a interação com o usuário e direciona as escolhas para os objetos responsáveis. Durante o projeto utilizamos construtores, encapsulamento, métodos, parâmetros, retornos, ArrayList, repetições, decisões, Scanner e tratamento de entradas. Essa organização cria uma base mais clara, reutilizável e preparada para futuras funcionalidades.",

            "mensagem" =>
                "Tu concluiu a trigésima Aula Conversada e finalizou esta sequência de fundamentos de Java."

        ]

    ]

];