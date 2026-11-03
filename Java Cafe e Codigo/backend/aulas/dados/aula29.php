<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 29,

    "titulo" =>
        "Objetos trabalhando em equipe",

    "assunto" =>
        "Métodos e objetos trabalhando juntos",

    "descricao" =>
        "Entenda como métodos modificam objetos, recebem objetos como parâmetros e conectam diferentes classes dentro de um programa.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        30,


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
                "O método alterando o próprio objeto...",

            "mensagem" =>
                "A conta possui um saldo protegido. O método depositar modifica esse valor sempre que é chamado.",

            "codigo" => <<<'JAVA'
class Conta {

    private double saldo;


    Conta(double saldoInicial) {

        this.saldo = saldoInicial;

    }


    void depositar(double valor) {

        saldo = saldo + valor;

    }


    double getSaldo() {

        return saldo;

    }

}

public class Main {

    public static void main(String[] args) {

        Conta conta =
            new Conta(100.0);

        conta.depositar(50.0);

        conta.depositar(25.0);

        System.out.println(
            conta.getSaldo()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual valor aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "175.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "150.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_um_deposito"
                ],

                [
                    "texto" =>
                        "100.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nao_altera"
                ],

                [
                    "texto" =>
                        "75.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_substitui"
                ],

                [
                    "texto" =>
                        "Não sei como o saldo muda",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONSIDEROU APENAS UM DEPÓSITO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_um_deposito" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "150 seria depois do primeiro depósito. Só que depositar é chamado outra vez com 25. A segunda chamada também muda o saldo.",

            "natanael" =>
                "O saldo começa em 100. A primeira chamada acrescenta 50, chegando a 150. Depois existe uma segunda chamada, que utiliza o valor atualizado e acrescenta mais 25.",

            "pergunta" =>
                "Quantas vezes conta.depositar(...) é chamado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Duas vezes",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Uma vez",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_um_deposito"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O MÉTODO NÃO ALTERA O OBJETO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_nao_altera" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Dentro de depositar tem uma atribuição. O método pega o saldo atual, soma o valor recebido e guarda o resultado de volta no objeto.",

            "natanael" =>
                "O método não está apenas consultando o atributo. A instrução saldo = saldo + valor produz um novo valor e o armazena novamente no estado da conta.",

            "codigo" => <<<'JAVA'
saldo = saldo + valor;
JAVA,

            "pergunta" =>
                "Essa linha apenas consulta ou também modifica saldo?",

            "opcoes" => [

                [
                    "texto" =>
                        "Também modifica",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Apenas consulta",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nao_altera"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O VALOR É SUBSTITUÍDO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_substitui" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método não faz saldo = valor. Ele faz saldo = saldo + valor. Então os depósitos são somados ao que já existia.",

            "natanael" =>
                "O valor recebido não substitui diretamente o saldo. A expressão do lado direito utiliza o saldo atual e acrescenta o novo depósito antes de realizar a atribuição.",

            "pergunta" =>
                "Se o saldo vale 100 e o depósito é 50, qual será o novo saldo?",

            "opcoes" => [

                [
                    "texto" =>
                        "150",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "50",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_substitui"
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
                "Pensa numa carteira. Ela começa com 100. Tu coloca mais 50 e fica com 150. Depois coloca mais 25 e chega em 175.",

            "natanael" =>
                "Imagine que o objeto Conta mantém seu próprio saldo. Cada chamada de depositar utiliza o valor já guardado, acrescenta uma quantia e atualiza o estado da mesma conta.",

            "pergunta" =>
                "Depois do primeiro depósito, quanto vale o saldo?",

            "opcoes" => [

                [
                    "texto" =>
                        "150.0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "100.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nao_altera"
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
                "O objeto começa com 100. O primeiro método soma 50 e o segundo soma 25. getSaldo devolve o valor que ficou guardado.",

            "natanael" =>
                "Acompanhe o estado da conta: 100 inicialmente, 150 depois da primeira chamada e 175 depois da segunda. O getter devolve esse estado final.",

            "pergunta" =>
                "Qual é o saldo final?",

            "opcoes" => [

                [
                    "texto" =>
                        "175.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "150.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_um_deposito"
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
                "Enviando um objeto para um método...",

            "mensagem" =>
                "O método premiar recebe um jogador e utiliza os métodos daquele objeto.",

            "codigo" => <<<'JAVA'
class Jogador {

    private String nome;

    private int pontos;


    Jogador(
        String nome,
        int pontos
    ) {

        this.nome = nome;

        this.pontos = pontos;

    }


    void adicionarPontos(
        int quantidade
    ) {

        pontos = pontos + quantidade;

    }


    String getNome() {

        return nome;

    }


    int getPontos() {

        return pontos;

    }

}

public class Main {

    static void premiar(
        Jogador jogador
    ) {

        jogador.adicionarPontos(10);

        System.out.println(
            jogador.getNome() +
            " recebeu um prêmio"
        );

    }


    public static void main(String[] args) {

        Jogador jogador =
            new Jogador(
                "Ana",
                20
            );

        premiar(jogador);

        System.out.println(
            jogador.getPontos()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana recebeu um prêmio e depois 30",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Ana recebeu um prêmio e depois 20",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_copia"
                ],

                [
                    "texto" =>
                        "Ana recebeu um prêmio e depois 10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_substitui"
                ],

                [
                    "texto" =>
                        "O método cria automaticamente outro jogador",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_novo"
                ],

                [
                    "texto" =>
                        "Não sei qual objeto o método modifica",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O MÉTODO ALTERA UMA CÓPIA SEPARADA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_copia" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O parâmetro jogador recebe uma referência pro mesmo objeto criado no main. Quando o método adiciona pontos, o jogador original também fica com 30.",

            "natanael" =>
                "O parâmetro recebe uma cópia da referência para o mesmo objeto. Isso significa que as duas variáveis apontam para a mesma instância. Modificar o estado por uma delas fica visível pela outra.",

            "pergunta" =>
                "O jogador usado dentro de premiar representa o mesmo objeto criado no main?",

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
                        "Não, é um objeto separado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_copia"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE OS PONTOS VIRAM 10
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_substitui" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "adicionarPontos não troca os pontos por 10. Ele soma 10 ao valor atual, que já era 20.",

            "natanael" =>
                "O método recebe a quantidade 10, mas utiliza pontos = pontos + quantidade. Assim o estado anterior participa do cálculo: 20 mais 10.",

            "pergunta" =>
                "Quanto resulta 20 + 10?",

            "opcoes" => [

                [
                    "texto" =>
                        "30",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_substitui"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE OUTRO OBJETO É CRIADO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_novo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Objeto novo só nasce quando aparece new. Dentro de premiar não tem new Jogador(). O método trabalha com o jogador que recebeu.",

            "natanael" =>
                "A criação de uma nova instância exigiria uma expressão new Jogador(...). Como essa expressão não existe dentro de premiar, o método apenas utiliza o objeto enviado como argumento.",

            "pergunta" =>
                "Existe new Jogador() dentro do método premiar?",

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
                        "pergunta_2_erro_novo"
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
                "Pensa que tu entrega o jogador Ana pro método. O método pega exatamente essa jogadora, soma 10 pontos nela e devolve o controle pro main.",

            "natanael" =>
                "O objeto é criado uma única vez. A chamada premiar(jogador) permite que o método acesse essa mesma instância temporariamente e utilize seus comportamentos.",

            "pergunta" =>
                "Quantos pontos Ana possui antes do prêmio?",

            "opcoes" => [

                [
                    "texto" =>
                        "20",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "0",

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
                "O mesmo objeto entra no método com 20 pontos. adicionarPontos soma 10. Quando volta pro main, ele continua com os 30 pontos guardados.",

            "natanael" =>
                "A instância mantém seu estado entre as chamadas. O método recebe acesso ao objeto, modifica seus pontos e encerra. Depois o getter consulta o valor atualizado.",

            "pergunta" =>
                "Qual valor getPontos devolve no final?",

            "opcoes" => [

                [
                    "texto" =>
                        "30",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "20",

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
                "Um objeto guardando outro objeto...",

            "mensagem" =>
                "Agora Pedido possui um atributo do tipo Cliente. Assim, um objeto pode utilizar informações e métodos de outro.",

            "codigo" => <<<'JAVA'
class Cliente {

    private String nome;


    Cliente(String nome) {

        this.nome = nome;

    }


    String getNome() {

        return nome;

    }

}

class Pedido {

    private Cliente cliente;

    private double total;


    Pedido(
        Cliente cliente,
        double total
    ) {

        this.cliente = cliente;

        this.total = total;

    }


    void exibirResumo() {

        System.out.println(
            cliente.getNome() +
            " - R$ " +
            total
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Cliente cliente =
            new Cliente("Carlos");

        Pedido pedido =
            new Pedido(
                cliente,
                45.0
            );

        pedido.exibirResumo();

    }

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Carlos - R$ 45.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "cliente - R$ 45.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_literal"
                ],

                [
                    "texto" =>
                        "null - R$ 45.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_null"
                ],

                [
                    "texto" =>
                        "O código não funciona porque um atributo não pode ser um objeto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_objeto"
                ],

                [
                    "texto" =>
                        "O Pedido cria um novo cliente chamado Pedido",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_novo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE MOSTRA O NOME DA VARIÁVEL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_literal" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "cliente é o nome do atributo. O método chama cliente.getNome(), então pega o nome guardado dentro do objeto Cliente: Carlos.",

            "natanael" =>
                "O atributo cliente referencia uma instância da classe Cliente. Ao chamar getNome, o programa consulta o valor interno dessa instância, e não o nome utilizado na variável.",

            "pergunta" =>
                "Qual valor foi enviado na criação de Cliente?",

            "opcoes" => [

                [
                    "texto" =>
                        "Carlos",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "cliente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_literal"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O ATRIBUTO CONTINUA NULL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_null" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O Pedido recebe o cliente no construtor. A linha this.cliente = cliente guarda a referência dentro do objeto Pedido.",

            "natanael" =>
                "O atributo poderia começar com null, mas o construtor é executado durante a criação do Pedido. Nele, o objeto Cliente recebido é atribuído ao atributo.",

            "codigo" => <<<'JAVA'
this.cliente = cliente;
JAVA,

            "pergunta" =>
                "Essa linha guarda o Cliente dentro do Pedido?",

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
                        "pergunta_3_erro_null"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE ATRIBUTO NÃO PODE SER OBJETO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_objeto" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Pode sim. Atributo não precisa ser só int, double ou String. Ele também pode guardar a referência de outro objeto, tipo Cliente.",

            "natanael" =>
                "Classes também podem ser utilizadas como tipos. Por isso o atributo private Cliente cliente pode referenciar uma instância da classe Cliente.",

            "pergunta" =>
                "Qual é o tipo do atributo cliente dentro de Pedido?",

            "opcoes" => [

                [
                    "texto" =>
                        "Cliente",

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
                        "pergunta_3_erro_objeto"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE PEDIDO CRIA OUTRO CLIENTE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_novo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O Cliente Carlos já foi criado antes. O construtor de Pedido só recebe esse objeto e guarda ele. Não existe new Cliente dentro de Pedido.",

            "natanael" =>
                "A instância Cliente é criada no main e enviada ao construtor de Pedido. O Pedido não substitui nem cria outro cliente; apenas mantém uma referência para o objeto recebido.",

            "pergunta" =>
                "Onde o Cliente Carlos foi criado?",

            "opcoes" => [

                [
                    "texto" =>
                        "No main, com new Cliente(\"Carlos\")",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Dentro de exibirResumo",

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
                "O Pedido guarda o Cliente Carlos. Quando precisa montar o resumo, chama getNome nesse objeto e junta com o total 45.0.",

            "natanael" =>
                "Existe uma colaboração entre os objetos. Pedido conhece o Cliente recebido e solicita a ele o nome necessário para completar sua própria mensagem.",

            "pergunta" =>
                "Qual resumo será apresentado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Carlos - R$ 45.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "null - R$ 45.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_null"
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
                "Uma classe organizando vários objetos...",

            "mensagem" =>
                "A classe ListaTarefas guarda objetos Tarefa e solicita que cada um execute seus próprios métodos.",

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

class ListaTarefas {

    private ArrayList<Tarefa> tarefas =
        new ArrayList<>();


    void adicionar(
        Tarefa tarefa
    ) {

        tarefas.add(tarefa);

    }


    void concluirPrimeira() {

        if (!tarefas.isEmpty()) {

            tarefas.get(0).concluir();

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

        ListaTarefas lista =
            new ListaTarefas();

        lista.adicionar(
            new Tarefa("Estudar Java")
        );

        lista.adicionar(
            new Tarefa("Treinar")
        );

        lista.concluirPrimeira();

        lista.listar();

    }

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "[x] Estudar Java e depois [ ] Treinar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "[x] Estudar Java e depois [x] Treinar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_duas"
                ],

                [
                    "texto" =>
                        "[ ] Estudar Java e depois [ ] Treinar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_nenhuma"
                ],

                [
                    "texto" =>
                        "Aparece somente [x] Estudar Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_uma"
                ],

                [
                    "texto" =>
                        "A lista guarda textos, não objetos Tarefa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE AS DUAS TAREFAS SÃO CONCLUÍDAS
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_duas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método se chama concluirPrimeira e usa get(0). Então só manda a primeira tarefa executar concluir(). A segunda continua false.",

            "natanael" =>
                "O método acessa exclusivamente o índice zero. Apenas esse objeto recebe a chamada concluir. Nenhuma instrução modifica a tarefa armazenada no índice um.",

            "pergunta" =>
                "Qual posição recebe a chamada concluir()?",

            "opcoes" => [

                [
                    "texto" =>
                        "A posição 0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Todas as posições",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_duas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE NENHUMA TAREFA É CONCLUÍDA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_nenhuma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A lista não está vazia. Então entra no if, pega tarefas.get(0) e chama concluir na primeira tarefa.",

            "natanael" =>
                "Duas tarefas foram adicionadas antes da chamada. Portanto isEmpty retorna false, a negação transforma o resultado em true e o método concluir é executado.",

            "pergunta" =>
                "Depois das duas adições, tarefas.isEmpty() retorna o quê?",

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
                        "pergunta_4_erro_nenhuma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE SÓ UMA TAREFA É LISTADA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_uma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O for percorre todos os itens da lista. Tem duas tarefas, então getResumo é chamado duas vezes.",

            "natanael" =>
                "O método listar utiliza tarefas.size() como limite. Como existem dois elementos, o contador assume os índices zero e um, apresentando ambos.",

            "pergunta" =>
                "Quantos objetos Tarefa foram adicionados?",

            "opcoes" => [

                [
                    "texto" =>
                        "Dois",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Um",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_uma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUNDIU O TIPO DA LISTA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O tipo é ArrayList<Tarefa>. Então cada posição guarda um objeto Tarefa. Depois a lista consegue chamar concluir e getResumo nesses objetos.",

            "natanael" =>
                "O tipo genérico informado entre os sinais menor e maior define quais elementos podem ser armazenados. Nesse caso, a estrutura foi preparada especificamente para referências do tipo Tarefa.",

            "codigo" => <<<'JAVA'
ArrayList<Tarefa> tarefas
JAVA,

            "pergunta" =>
                "Qual tipo de objeto essa lista recebe?",

            "opcoes" => [

                [
                    "texto" =>
                        "Tarefa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Somente String",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_tipo"
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
                "A lista guarda duas tarefas. concluirPrimeira pega só a primeira e muda ela pra true. Depois listar pede o resumo de cada objeto.",

            "natanael" =>
                "Os objetos colaboram em etapas. ListaTarefas organiza as instâncias, seleciona a primeira para alteração e depois solicita individualmente a representação de cada tarefa.",

            "pergunta" =>
                "Qual sequência completa será apresentada?",

            "opcoes" => [

                [
                    "texto" =>
                        "[x] Estudar Java → [ ] Treinar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "[x] Estudar Java → [x] Treinar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_duas"
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
                "Objetos trabalhando juntos!",

            "robert" =>
                "Boa po kkkkk. Agora os objetos não ficam isolados. Um método pode mudar o estado do próprio objeto, receber outro objeto como parâmetro e chamar os métodos dele. Uma classe também pode guardar outro objeto em um atributo ou organizar vários objetos dentro de um ArrayList. É assim que programas maiores dividem o trabalho sem jogar tudo dentro do main.",

            "natanael" =>
                "Concluímos a colaboração entre métodos e objetos. Vimos métodos modificando o estado de uma instância, objetos sendo enviados como argumentos e referências permitindo que o mesmo estado seja acessado em diferentes partes do programa. Também observamos composição, quando um objeto mantém outro como atributo, e uma classe responsável por organizar uma coleção de objetos. Essa distribuição de responsabilidades é fundamental para projetos orientados a objetos mais organizados. Na próxima aula vamos aplicar esses conceitos em uma versão orientada a objetos do gerenciador de tarefas.",

            "mensagem" =>
                "Tu concluiu a vigésima nona Aula Conversada."

        ]

    ]

];