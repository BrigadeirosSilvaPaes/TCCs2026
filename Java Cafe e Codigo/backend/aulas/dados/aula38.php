<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 38,

    "titulo" =>
        "Criando modelos para outras classes",

    "assunto" =>
        "Classes abstratas",

    "descricao" =>
        "Aprenda a utilizar classes abstratas como modelos, declarar métodos obrigatórios e compartilhar atributos e comportamentos entre classes filhas.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        39,


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
                "Criando um objeto a partir de uma classe filha...",

            "mensagem" =>
                "Animal funciona como um modelo geral. Cachorro herda esse modelo e implementa seu próprio comportamento.",

            "codigo" => <<<'JAVA'
abstract class Animal {

    abstract void emitirSom();

}

class Cachorro extends Animal {

    @Override
    void emitirSom() {

        System.out.println(
            "Au au"
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Cachorro cachorro =
            new Cachorro();

        cachorro.emitirSom();

    }

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Au au",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
                ],

                [
                    "texto" =>
                        "Nenhuma mensagem, porque Animal é abstrata",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_abstrata"
                ],

                [
                    "texto" =>
                        "O código não compila porque Cachorro herda uma classe abstrata",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_heranca"
                ],

                [
                    "texto" =>
                        "Não sei qual método é executado",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU O TIPO COM A SAÍDA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Animal é o nome da classe-base. O println está dentro do método emitirSom de Cachorro e mostra Au au.",

            "natanael" =>
                "A classe Animal define apenas a obrigação de possuir o método emitirSom. A implementação concreta está em Cachorro e apresenta a mensagem Au au.",

            "pergunta" =>
                "Em qual classe existe o println com a mensagem Au au?",

            "opcoes" => [

                [
                    "texto" =>
                        "Na classe Cachorro",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Na classe Animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE NADA EXECUTA POR ANIMAL SER ABSTRATA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_abstrata" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Animal não pode ser criada diretamente, mas Cachorro pode. O objeto criado é um Cachorro concreto, então o método funciona normalmente.",

            "natanael" =>
                "A restrição vale para a criação direta da classe Animal. Cachorro não foi declarada como abstrata e implementou o método obrigatório, portanto pode ser instanciada.",

            "codigo" => <<<'JAVA'
Cachorro cachorro =
    new Cachorro();
JAVA,

            "pergunta" =>
                "Qual classe está sendo criada com new?",

            "opcoes" => [

                [
                    "texto" =>
                        "Cachorro",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_abstrata"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE NÃO PODE HERDAR CLASSE ABSTRATA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_heranca" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Classe abstrata existe justamente pra ser usada como base. Cachorro pode herdar Animal e completar o comportamento que faltava.",

            "natanael" =>
                "Uma das principais finalidades de uma classe abstrata é fornecer uma estrutura comum para subclasses. A herança é permitida e esperada.",

            "pergunta" =>
                "Cachorro implementou o método abstrato emitirSom?",

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
                        "pergunta_1_erro_heranca"
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
                "Pensa em Animal como uma regra geral: todo animal deve emitir algum som. Cachorro decide qual som vai emitir e escreve Au au.",

            "natanael" =>
                "Animal define o que precisa existir, mas não determina o som específico. A subclasse Cachorro fornece essa implementação de acordo com seu próprio comportamento.",

            "pergunta" =>
                "Qual classe decidiu que o som seria Au au?",

            "opcoes" => [

                [
                    "texto" =>
                        "Cachorro",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
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
                "Animal exige o método emitirSom. Cachorro implementa esse método, o objeto é criado normalmente e a chamada mostra Au au.",

            "natanael" =>
                "A classe abstrata define o contrato básico. A subclasse concreta completa esse contrato e sua implementação é executada pelo objeto criado.",

            "pergunta" =>
                "Qual é a saída final?",

            "opcoes" => [

                [
                    "texto" =>
                        "Au au",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Nenhuma saída",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_abstrata"
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
                "Uma classe filha sem completar a obrigação...",

            "mensagem" =>
                "A classe Veiculo declarou um método abstrato, mas Bicicleta não implementou esse método.",

            "codigo" => <<<'JAVA'
abstract class Veiculo {

    abstract void mover();

}

class Bicicleta extends Veiculo {

}

public class Main {

    public static void main(String[] args) {

        Bicicleta bicicleta =
            new Bicicleta();

    }

}
JAVA,

            "pergunta" =>
                "O que acontece com esse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não compila porque Bicicleta precisa implementar mover ou também ser abstrata",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Compila normalmente, porque mover nunca foi chamado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_chamada"
                ],

                [
                    "texto" =>
                        "Compila e cria um método mover vazio automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_automatico"
                ],

                [
                    "texto" =>
                        "Compila, mas mover mostra null",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_null"
                ],

                [
                    "texto" =>
                        "Não sei qual regra Bicicleta precisa seguir",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE NÃO PRECISA IMPLEMENTAR POR NÃO CHAMAR
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_chamada" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Mesmo sem chamar mover no main, Bicicleta é uma classe concreta. Pra ser concreta, precisa completar todos os métodos abstratos herdados.",

            "natanael" =>
                "A obrigação é verificada durante a compilação da classe, não somente quando o método é chamado. Uma subclasse concreta precisa implementar todos os métodos abstratos herdados.",

            "pergunta" =>
                "Bicicleta foi declarada com a palavra abstract?",

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
                        "pergunta_2_erro_chamada"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O JAVA CRIA IMPLEMENTAÇÃO AUTOMÁTICA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_automatico" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O Java não inventa o comportamento da bicicleta. A classe filha precisa escrever o que mover significa pra ela.",

            "natanael" =>
                "O método abstrato possui apenas sua declaração. O compilador não cria um corpo automaticamente, porque não sabe qual comportamento específico deveria ser utilizado.",

            "pergunta" =>
                "Quem precisa escrever o corpo de mover para Bicicleta?",

            "opcoes" => [

                [
                    "texto" =>
                        "A própria classe Bicicleta",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "O Java cria sozinho",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_automatico"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE MOSTRA NULL
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_null" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não chega a executar e nem mostrar null. O erro aparece na compilação, porque a classe concreta ficou incompleta.",

            "natanael" =>
                "Nenhum objeto é criado e nenhuma saída acontece. A ausência da implementação obrigatória impede a compilação da classe Bicicleta.",

            "pergunta" =>
                "O problema acontece antes ou depois da execução?",

            "opcoes" => [

                [
                    "texto" =>
                        "Antes da execução, durante a compilação",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Depois da execução começar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_null"
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
                "Veiculo criou uma regra: toda classe concreta precisa dizer como se move. Bicicleta herdou essa regra, mas não respondeu como se move.",

            "natanael" =>
                "A classe abstrata estabelece uma obrigação para suas subclasses. Como Bicicleta pretende ser concreta e instanciável, precisa fornecer a implementação ausente.",

            "pergunta" =>
                "Qual método abstrato Bicicleta herdou?",

            "opcoes" => [

                [
                    "texto" =>
                        "mover()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "main()",

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
                "Bicicleta não é abstrata, então precisa implementar mover. Outra opção seria declarar abstract class Bicicleta, mas aí também não daria pra criar ela diretamente.",

            "natanael" =>
                "Uma subclasse pode continuar abstrata e deixar a obrigação para outra classe. Porém, para ser concreta e permitir new Bicicleta(), precisa completar o método.",

            "pergunta" =>
                "Qual correção deixaria Bicicleta concreta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Implementar o método mover()",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Ignorar o método abstrato",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_chamada"
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
                "Compartilhando atributos e métodos prontos...",

            "mensagem" =>
                "Uma classe abstrata também pode possuir construtor, atributos e métodos com implementação completa.",

            "codigo" => <<<'JAVA'
abstract class Funcionario {

    protected String nome;


    Funcionario(String nome) {

        this.nome = nome;

    }


    void apresentar() {

        System.out.println(
            "Funcionário: " + nome
        );

    }


    abstract double calcularBonus();

}

class Desenvolvedor extends Funcionario {

    private double salario;


    Desenvolvedor(
        String nome,
        double salario
    ) {

        super(nome);

        this.salario = salario;

    }


    @Override
    double calcularBonus() {

        return salario * 0.10;

    }

}

public class Main {

    public static void main(String[] args) {

        Desenvolvedor desenvolvedor =
            new Desenvolvedor(
                "Ana",
                3000.0
            );

        desenvolvedor.apresentar();

        System.out.println(
            desenvolvedor.calcularBonus()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Funcionário: Ana e depois 300.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Funcionário: Ana e depois 3000.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_bonus"
                ],

                [
                    "texto" =>
                        "Funcionário: null e depois 300.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_super"
                ],

                [
                    "texto" =>
                        "Somente 300.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_metodo"
                ],

                [
                    "texto" =>
                        "O código não compila porque classe abstrata não pode ter construtor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_construtor"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU SALÁRIO COM BÔNUS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_bonus" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método não devolve o salário inteiro. Ele calcula 10% de 3000. Dez por cento dá 300.",

            "natanael" =>
                "A expressão multiplica o salário por 0.10. Esse valor representa dez por cento, portanto o retorno é 300.0.",

            "codigo" => <<<'JAVA'
3000.0 * 0.10
JAVA,

            "pergunta" =>
                "Quanto é 10% de 3000?",

            "opcoes" => [

                [
                    "texto" =>
                        "300",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "3000",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_bonus"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O NOME FICA NULL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_super" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O construtor de Desenvolvedor chama super(nome). Isso envia Ana pro construtor de Funcionario, que guarda o nome.",

            "natanael" =>
                "A chamada super(nome) executa o construtor da classe-base. O parâmetro recebido é atribuído ao atributo protegido nome.",

            "codigo" => <<<'JAVA'
super(nome);
JAVA,

            "pergunta" =>
                "Qual valor é enviado ao construtor de Funcionario?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "null",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_super"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ESQUECEU O MÉTODO CONCRETO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_metodo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Antes do bônus, o código chama apresentar(). Esse método já veio pronto da classe Funcionario e mostra o nome.",

            "natanael" =>
                "Nem todos os métodos de uma classe abstrata precisam ser abstratos. apresentar possui corpo completo e é herdado normalmente pela subclasse.",

            "pergunta" =>
                "O método apresentar possui implementação dentro de Funcionario?",

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
                        "pergunta_3_erro_metodo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE ABSTRATA NÃO PODE TER CONSTRUTOR
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_construtor" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Classe abstrata não pode ser criada diretamente, mas pode ter construtor. Esse construtor é usado quando uma classe filha é criada.",

            "natanael" =>
                "O construtor da classe abstrata participa da inicialização da parte herdada do objeto. Ele é executado por meio da chamada super realizada pela subclasse.",

            "pergunta" =>
                "Qual palavra chama o construtor da classe-base?",

            "opcoes" => [

                [
                    "texto" =>
                        "super",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "new Funcionario",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_construtor"
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
                "super guarda Ana no atributo herdado. apresentar mostra esse nome. Depois calcularBonus faz 3000 vezes 0.10 e devolve 300.",

            "natanael" =>
                "A construção inicializa tanto a parte herdada quanto o atributo específico. O método concreto apresenta o nome e o método sobrescrito calcula o bônus.",

            "pergunta" =>
                "Qual sequência completa será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Funcionário: Ana → 300.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Funcionário: null → 3000.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_super"
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
                "Utilizando polimorfismo com classes abstratas...",

            "mensagem" =>
                "Uma variável do tipo da classe abstrata pode apontar para objetos concretos de classes diferentes.",

            "codigo" => <<<'JAVA'
import java.util.ArrayList;

abstract class Pagamento {

    protected double valor;


    Pagamento(double valor) {

        this.valor = valor;

    }


    abstract void processar();

}

class PagamentoPix extends Pagamento {

    PagamentoPix(double valor) {

        super(valor);

    }


    @Override
    void processar() {

        System.out.println(
            "PIX de R$ " + valor
        );

    }

}

class PagamentoCartao extends Pagamento {

    PagamentoCartao(double valor) {

        super(valor);

    }


    @Override
    void processar() {

        System.out.println(
            "Cartão de R$ " + valor
        );

    }

}

public class Main {

    public static void main(String[] args) {

        ArrayList<Pagamento> pagamentos =
            new ArrayList<>();

        pagamentos.add(
            new PagamentoPix(50.0)
        );

        pagamentos.add(
            new PagamentoCartao(80.0)
        );

        for (
            Pagamento pagamento :
            pagamentos
        ) {

            pagamento.processar();

        }

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "PIX de R$ 50.0 e depois Cartão de R$ 80.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Pagamento de R$ 50.0 e depois Pagamento de R$ 80.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_base"
                ],

                [
                    "texto" =>
                        "PIX de R$ 50.0 duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_pix"
                ],

                [
                    "texto" =>
                        "O código não compila porque ArrayList não pode usar uma classe abstrata como tipo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_lista"
                ],

                [
                    "texto" =>
                        "O código tenta criar diretamente dois objetos Pagamento",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_instancia"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE EXECUTA UM MÉTODO DA CLASSE-BASE
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_base" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Pagamento não possui uma implementação pronta de processar. Cada objeto usa a versão da sua classe real: Pix ou Cartão.",

            "natanael" =>
                "A variável do laço possui tipo Pagamento, mas o comportamento executado depende do objeto armazenado. Esse é o polimorfismo.",

            "pergunta" =>
                "O primeiro objeto da lista pertence a qual classe?",

            "opcoes" => [

                [
                    "texto" =>
                        "PagamentoPix",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Pagamento diretamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_base"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE AS DUAS CHAMADAS USAM PIX
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_pix" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A lista tem dois objetos diferentes. O primeiro é Pix e o segundo é Cartão. Cada um executa sua própria versão de processar.",

            "natanael" =>
                "O laço percorre duas instâncias concretas diferentes. A chamada é resolvida de acordo com o tipo real de cada objeto.",

            "pergunta" =>
                "Qual classe foi usada no segundo new?",

            "opcoes" => [

                [
                    "texto" =>
                        "PagamentoCartao",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "PagamentoPix",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_pix"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE LISTA NÃO PODE USAR TIPO ABSTRATO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_lista" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A lista pode usar Pagamento como tipo. O que ela não pode guardar é um objeto criado diretamente com new Pagamento(). Ela guarda subclasses concretas.",

            "natanael" =>
                "Tipos abstratos podem ser utilizados em variáveis, parâmetros e coleções. A restrição existe apenas na instanciação direta da classe abstrata.",

            "pergunta" =>
                "Os objetos adicionados são concretos?",

            "opcoes" => [

                [
                    "texto" =>
                        "Sim, são PagamentoPix e PagamentoCartao",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Não, são objetos Pagamento diretos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_lista"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE PAGAMENTO FOI INSTANCIADO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_instancia" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não existe new Pagamento(). O código cria new PagamentoPix e new PagamentoCartao. Pagamento é só o tipo geral da lista e da variável.",

            "natanael" =>
                "A classe abstrata aparece como referência comum. As expressões new utilizam exclusivamente as subclasses concretas.",

            "pergunta" =>
                "Existe alguma expressão new Pagamento() no código?",

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
                        "pergunta_4_erro_instancia"
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
                "A lista usa o tipo geral Pagamento, mas guarda um Pix e um Cartão. No laço, cada objeto executa sua própria implementação de processar.",

            "natanael" =>
                "A abstração permite tratar objetos diferentes de maneira uniforme. Entretanto o método executado continua sendo aquele implementado pela classe concreta de cada instância.",

            "pergunta" =>
                "Qual sequência completa será apresentada?",

            "opcoes" => [

                [
                    "texto" =>
                        "PIX de R$ 50.0 → Cartão de R$ 80.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "PIX de R$ 50.0 → PIX de R$ 80.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_pix"
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
                "Classes abstratas desbloqueadas!",

            "robert" =>
                "Boa po kkkkk. Classe abstrata serve como uma base que não pode ser criada diretamente. Ela pode ter atributos, construtor, métodos prontos e métodos abstratos. Os métodos abstratos criam obrigações pras classes filhas. Se a filha for concreta, precisa implementar tudo que ficou faltando. Depois ainda dá pra usar o tipo abstrato em variáveis e listas, aproveitando o polimorfismo.",

            "natanael" =>
                "Concluímos o funcionamento das classes abstratas. Elas representam modelos gerais que não podem ser instanciados diretamente, mas podem fornecer atributos, construtores e métodos concretos para suas subclasses. Também podem declarar métodos abstratos, que precisam ser implementados pelas classes concretas. Por meio do polimorfismo, referências do tipo abstrato podem apontar para diferentes objetos concretos e executar seus comportamentos específicos. Na próxima aula vamos aprender a organizar classes em pacotes e compreender os principais modificadores de acesso.",

            "mensagem" =>
                "Tu concluiu a trigésima oitava Aula Conversada."

        ]

    ]

];