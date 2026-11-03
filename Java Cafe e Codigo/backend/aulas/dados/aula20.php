<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 20,

    "titulo" =>
        "Aproveitando características de outra classe",

    "assunto" =>
        "Herança",

    "descricao" =>
        "Descubra como uma classe pode herdar características e comportamentos de outra utilizando extends.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        21,


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
                "Uma classe aproveitando outra...",

            "mensagem" =>
                "Observa que Cachorro não possui o método emitirSom escrito dentro dela.",

            "codigo" => <<<'JAVA'
class Animal {

    void emitirSom() {

        System.out.println(
            "Animal emitindo som"
        );

    }

}

class Cachorro extends Animal {

}

public class Main {

    public static void main(String[] args) {

        Cachorro cachorro = new Cachorro();

        cachorro.emitirSom();

    }

}
JAVA,

            "pergunta" =>
                "O que acontece quando cachorro.emitirSom() é executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Aparece Animal emitindo som, porque Cachorro herdou o método",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "O código não compila porque Cachorro não possui métodos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_metodo"
                ],

                [
                    "texto" =>
                        "Nada acontece porque extends não executa métodos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_extends"
                ],

                [
                    "texto" =>
                        "Não sei o que extends significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O MÉTODO NÃO EXISTE
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_metodo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método não tá escrito dentro de Cachorro, mas Cachorro usa extends Animal. Isso faz ela herdar comportamentos disponíveis na classe Animal.",

            "natanael" =>
                "A classe Cachorro não declara emitirSom diretamente. Porém ela estende Animal. Por causa dessa relação de herança, objetos do tipo Cachorro também podem utilizar os métodos herdados da classe principal.",

            "pergunta" =>
                "Qual classe possui originalmente o método emitirSom()?",

            "opcoes" => [

                [
                    "texto" =>
                        "Animal",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Main",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_metodo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU EXTENDS
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_extends" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "extends não chama o método sozinho, mas faz Cachorro receber os comportamentos de Animal. Depois cachorro.emitirSom() chama o método herdado.",

            "natanael" =>
                "A palavra extends não executa uma ação automaticamente. Ela cria uma relação entre as classes. Depois dessa relação, o objeto cachorro consegue chamar emitirSom porque esse comportamento foi herdado de Animal.",

            "pergunta" =>
                "Qual linha realmente solicita a execução do método?",

            "opcoes" => [

                [
                    "texto" =>
                        "cachorro.emitirSom();",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "class Cachorro extends Animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_extends"
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
                "Pensa que Animal já tem uma habilidade pronta. Quando Cachorro usa extends Animal, ele aproveita essa habilidade sem precisar copiar o código.",

            "natanael" =>
                "Imagine uma categoria geral chamada Animal que já define comportamentos comuns. Cachorro é um tipo mais específico de Animal. A herança permite que Cachorro aproveite aquilo que já foi definido na classe mais geral.",

            "pergunta" =>
                "Cachorro precisa copiar o método emitirSom para conseguir utilizá-lo?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não, ele pode herdar",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Sim, precisa copiar tudo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_metodo"
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
                "É isso. extends cria a herança. Cachorro aproveita o método de Animal e consegue chamar emitirSom().",

            "natanael" =>
                "Exatamente. A classe Animal disponibiliza o comportamento, e Cachorro o recebe por meio da herança. O objeto criado a partir da classe filha pode utilizar esse método normalmente.",

            "pergunta" =>
                "Qual mensagem será apresentada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Animal emitindo som",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Nenhuma mensagem",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_metodo"
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
                "Atributos também podem ser herdados...",

            "mensagem" =>
                "Agora a classe Aluno aproveita uma característica e um comportamento definidos em Pessoa.",

            "codigo" => <<<'JAVA'
class Pessoa {

    String nome;

    void apresentar() {

        System.out.println(
            "Meu nome é " + nome
        );

    }

}

class Aluno extends Pessoa {

    int nivel;

}

public class Main {

    public static void main(String[] args) {

        Aluno aluno = new Aluno();

        aluno.nome = "Ana";
        aluno.nivel = 3;

        aluno.apresentar();

        System.out.println(
            "Nível: " + aluno.nivel
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Meu nome é Ana e depois Nível: 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Aparece somente Nível: 3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_metodo"
                ],

                [
                    "texto" =>
                        "O código não compila porque nome pertence a Pessoa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_atributo"
                ],

                [
                    "texto" =>
                        "Meu nome é null e depois Nível: 3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_valor"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ESQUECEU O MÉTODO HERDADO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_metodo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aluno herdou apresentar() de Pessoa. Então aluno.apresentar() funciona e mostra o nome antes do println do nível.",

            "natanael" =>
                "O método apresentar foi declarado em Pessoa, mas está disponível para Aluno devido à herança. Portanto a primeira chamada apresenta o nome, e depois o segundo println mostra o nível.",

            "pergunta" =>
                "Qual método é chamado antes de mostrar o nível?",

            "opcoes" => [

                [
                    "texto" =>
                        "apresentar()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "nenhum método",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_metodo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O ATRIBUTO NÃO É HERDADO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_atributo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Como nome não é private nesse exemplo, Aluno consegue utilizar o atributo herdado. Por isso aluno.nome = \"Ana\" funciona.",

            "natanael" =>
                "A classe Aluno recebe a estrutura acessível definida em Pessoa. Como o atributo nome não foi declarado como private, ele pode ser utilizado pelo objeto da classe filha nesse exemplo.",

            "pergunta" =>
                "Qual classe declarou originalmente o atributo nome?",

            "opcoes" => [

                [
                    "texto" =>
                        "Pessoa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Main",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_atributo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O NOME CONTINUA NULL
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_valor" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O nome não fica null porque o código coloca Ana em aluno.nome antes de chamar apresentar().",

            "natanael" =>
                "O atributo poderia começar com valor null, mas ele é alterado antes da chamada do método. A instrução aluno.nome = \"Ana\" prepara o valor utilizado por apresentar().",

            "pergunta" =>
                "Qual valor foi colocado em aluno.nome?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "null",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_valor"
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
                "Aluno tem o que veio de Pessoa e também o que ele próprio criou. Herdou nome e apresentar(), e adicionou nivel.",

            "natanael" =>
                "A classe filha combina elementos herdados com elementos próprios. Nesse caso, Aluno utiliza nome e apresentar de Pessoa, enquanto acrescenta o atributo nivel à sua estrutura.",

            "pergunta" =>
                "Qual sequência completa será exibida?",

            "opcoes" => [

                [
                    "texto" =>
                        "Meu nome é Ana → Nível: 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Nível: 3 somente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_metodo"
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
                "A classe filha pode acrescentar coisas...",

            "mensagem" =>
                "A classe Moto herdou um comportamento de Veiculo e também ganhou um método próprio.",

            "codigo" => <<<'JAVA'
class Veiculo {

    void ligar() {

        System.out.println(
            "Veículo ligado"
        );

    }

}

class Moto extends Veiculo {

    void acelerar() {

        System.out.println(
            "Moto acelerando"
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Moto moto = new Moto();

        moto.ligar();

        moto.acelerar();

    }

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Veículo ligado e depois Moto acelerando",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Somente Veículo ligado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_proprio"
                ],

                [
                    "texto" =>
                        "Somente Moto acelerando",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_herdado"
                ],

                [
                    "texto" =>
                        "A classe filha só pode usar métodos herdados",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_limite"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ESQUECEU O MÉTODO PRÓPRIO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_proprio" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Além de herdar ligar(), Moto criou acelerar(). Então o objeto consegue fazer as duas ações.",

            "natanael" =>
                "A herança não impede a classe filha de possuir seus próprios comportamentos. Moto recebe ligar de Veiculo e declara adicionalmente o método acelerar.",

            "pergunta" =>
                "Qual método foi criado diretamente dentro de Moto?",

            "opcoes" => [

                [
                    "texto" =>
                        "acelerar()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "ligar()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_proprio"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ESQUECEU O MÉTODO HERDADO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_herdado" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Moto não escreveu ligar(), mas herdou esse método de Veiculo. Por isso moto.ligar() funciona antes de acelerar().",

            "natanael" =>
                "O método ligar pertence originalmente a Veiculo. Como Moto estende essa classe, o comportamento fica disponível para seus objetos e pode ser chamado normalmente.",

            "pergunta" =>
                "De qual classe veio o método ligar()?",

            "opcoes" => [

                [
                    "texto" =>
                        "Veiculo",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Main",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_herdado"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE A FILHA NÃO PODE CRIAR MÉTODOS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_limite" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Pode sim kkkkk. A classe filha não fica presa ao que herdou. Ela aproveita o que já existe e ainda pode adicionar coisa nova.",

            "natanael" =>
                "A herança oferece uma base reutilizável, mas a classe filha pode especializar essa base. Ela pode acrescentar novos atributos e métodos relacionados à sua função específica.",

            "pergunta" =>
                "Moto aproveitou Veiculo e também adicionou qual comportamento?",

            "opcoes" => [

                [
                    "texto" =>
                        "acelerar()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Nenhum comportamento",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_limite"
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
                "A classe filha junta os dois lados: usa o que herdou e usa o que criou. Moto liga e também acelera.",

            "natanael" =>
                "A instância de Moto possui acesso ao comportamento herdado ligar e ao comportamento específico acelerar. As chamadas são executadas na ordem em que aparecem no main.",

            "pergunta" =>
                "Qual sequência será exibida?",

            "opcoes" => [

                [
                    "texto" =>
                        "Veículo ligado → Moto acelerando",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Moto acelerando somente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_herdado"
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
                "A classe filha também pode mudar um comportamento...",

            "mensagem" =>
                "Agora Cachorro recebeu emitirSom de Animal, mas decidiu criar sua própria versão do método.",

            "codigo" => <<<'JAVA'
class Animal {

    void emitirSom() {

        System.out.println(
            "Som de animal"
        );

    }

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

        Cachorro cachorro = new Cachorro();

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
                        "final"
                ],

                [
                    "texto" =>
                        "Som de animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_pai"
                ],

                [
                    "texto" =>
                        "As duas mensagens aparecem",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_duas"
                ],

                [
                    "texto" =>
                        "O código não compila porque o método já existe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_redefinir"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE USA A VERSÃO DA CLASSE PRINCIPAL
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_pai" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Cachorro herdou o método, mas depois criou uma versão própria com o mesmo nome. Quando o objeto é Cachorro, essa versão mostra Au au.",

            "natanael" =>
                "A classe Animal fornece uma implementação geral. Porém Cachorro redefine o método emitirSom. Ao chamar o método em um objeto Cachorro, a implementação específica da classe filha é utilizada.",

            "pergunta" =>
                "Qual classe possui a versão que mostra Au au?",

            "opcoes" => [

                [
                    "texto" =>
                        "Cachorro",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_pai"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE AS DUAS VERSÕES EXECUTAM
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_duas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não executa as duas automaticamente. A versão de Cachorro substitui o comportamento herdado quando o método é chamado nesse objeto.",

            "natanael" =>
                "A redefinição cria uma implementação específica para a classe filha. A chamada cachorro.emitirSom() seleciona essa versão. A implementação da classe principal não é executada automaticamente junto com ela.",

            "pergunta" =>
                "Quantos println existem dentro da versão de Cachorro?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Dois",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_duas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE NÃO PODE REDEFINIR
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_redefinir" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Pode redefinir sim. O @Override até ajuda a indicar que Cachorro tá criando outra versão de um método herdado.",

            "natanael" =>
                "Uma classe filha pode fornecer sua própria implementação para determinados métodos herdados. A anotação @Override sinaliza que estamos redefinindo um método existente na classe principal.",

            "pergunta" =>
                "O método de Cachorro possui o mesmo nome do método de Animal?",

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
                        "pergunta_4_erro_redefinir"
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
                "Animal tem um som geral. Cachorro herda a ideia de emitir som, mas redefine como esse som funciona pra ele: Au au.",

            "natanael" =>
                "A herança fornece o comportamento geral, enquanto a classe filha pode especializá-lo. Como o objeto é um Cachorro, sua implementação específica de emitirSom é executada.",

            "pergunta" =>
                "Qual mensagem a versão de Cachorro apresenta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Au au",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Som de animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_pai"
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
                "Herança desbloqueada!",

            "robert" =>
                "Boa po kkkkk. Herança serve pra reaproveitar código sem ficar copiando tudo. A classe filha usa extends, herda atributos e métodos acessíveis, pode adicionar coisas próprias e até redefinir um comportamento herdado com @Override. Agora vem uma parte muito interessante: usar o mesmo tipo geral pra trabalhar com objetos que se comportam de formas diferentes.",

            "natanael" =>
                "Fechamos o conceito básico de herança. Uma classe filha utiliza extends para aproveitar características e comportamentos de uma classe principal. Ela pode utilizar membros herdados, acrescentar novos atributos e métodos e também redefinir determinados comportamentos com @Override. Isso permite criar estruturas mais organizadas e especializadas sem duplicar código. Na próxima aula vamos estudar polimorfismo e entender como objetos relacionados por herança podem responder de maneiras diferentes à mesma chamada de método.",

            "mensagem" =>
                "Tu concluiu a vigésima Aula Conversada."

        ]

    ]

];