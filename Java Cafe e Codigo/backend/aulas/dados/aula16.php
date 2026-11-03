<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 16,

    "titulo" =>
        "Criando nossos próprios objetos",

    "assunto" =>
        "Classes e objetos",

    "descricao" =>
        "Descubra como o Java utiliza classes como modelos para criar objetos com informações e comportamentos próprios.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        17,


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
                "Um novo tipo de estrutura...",

            "mensagem" =>
                "Olha essa classe e pensa no que ela parece descrever.",

            "codigo" => <<<'JAVA'
class Aluno {

    String nome;

    int nivel;

    void estudar() {
        System.out.println("Estudando Java");
    }

}
JAVA,

            "pergunta" =>
                "O que a classe Aluno representa nesse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um modelo usado para criar objetos do tipo Aluno",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Um aluno específico chamado Aluno",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_objeto"
                ],

                [
                    "texto" =>
                        "Uma tabela do banco de dados",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_banco"
                ],

                [
                    "texto" =>
                        "Não sei o que uma classe representa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU CLASSE COM OBJETO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_objeto" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A classe ainda não é um aluno específico. Pensa nela como uma ficha em branco dizendo que todo aluno pode ter nome, nível e estudar. Os alunos de verdade vão ser criados depois.",

            "natanael" =>
                "Vamos separar modelo e elemento real. A classe Aluno descreve quais informações e comportamentos um aluno poderá possuir. Porém ela ainda não representa uma pessoa específica. Para isso, precisamos criar um objeto baseado nessa classe.",

            "pergunta" =>
                "Uma planta de casa é a casa pronta ou o modelo usado para construí-la?",

            "opcoes" => [

                [
                    "texto" =>
                        "O modelo usado para construir",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "A casa pronta",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_objeto"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU COM BANCO DE DADOS
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_banco" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Banco de dados é outra coisa kkkkk. Ele pode salvar informações, mas aqui estamos ensinando o Java a reconhecer um novo tipo chamado Aluno.",

            "natanael" =>
                "Uma tabela de banco de dados é utilizada para armazenar registros de forma persistente. Nesse exemplo não existe conexão nem comando de banco. A palavra class está criando uma definição dentro do próprio programa Java.",

            "pergunta" =>
                "O código está salvando alunos ou descrevendo como um Aluno deve ser?",

            "opcoes" => [

                [
                    "texto" =>
                        "Descrevendo como um Aluno deve ser",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Salvando alunos no banco",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_banco"
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
                "Pensa num molde de caneca. O molde define o formato, mas ainda não é uma caneca pronta. A classe é esse molde; os objetos são as canecas criadas com ele.",

            "natanael" =>
                "Imagina uma fábrica que possui um projeto para produzir várias canecas. O projeto descreve características comuns, mas cada caneca fabricada será um objeto separado. Em Java, a classe funciona como esse projeto utilizado na criação dos objetos.",

            "pergunta" =>
                "A classe funciona mais como um modelo ou como um objeto já criado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Como um modelo",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Como um objeto já criado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_objeto"
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
                "É isso. A classe é o modelo. Ela diz que um Aluno pode ter nome, nível e o comportamento estudar.",

            "natanael" =>
                "Exatamente. A classe reúne uma descrição do tipo Aluno. Ela define informações que os objetos poderão possuir e ações que poderão realizar.",

            "pergunta" =>
                "Qual opção resume melhor essa ideia?",

            "opcoes" => [

                [
                    "texto" =>
                        "Classe é um modelo para criar objetos",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Classe é sempre um objeto específico",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_objeto"
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
                "Usando o modelo...",

            "mensagem" =>
                "Agora a classe Aluno será utilizada dentro do programa.",

            "codigo" => <<<'JAVA'
class Aluno {

    String nome;

}

public class Main {

    public static void main(String[] args) {

        Aluno aluno = new Aluno();

        aluno.nome = "Ana";

        System.out.println(aluno.nome);

    }

}
JAVA,

            "pergunta" =>
                "O que a expressão new Aluno() está fazendo?",

            "opcoes" => [

                [
                    "texto" =>
                        "Criando um objeto baseado na classe Aluno",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Criando outra classe chamada aluno",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_classe"
                ],

                [
                    "texto" =>
                        "Mostrando automaticamente Ana no console",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_console"
                ],

                [
                    "texto" =>
                        "Não sei o que new significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE CRIA OUTRA CLASSE
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_classe" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A classe continua sendo Aluno. A variável aluno só guarda o acesso ao objeto criado com new Aluno().",

            "natanael" =>
                "Não estamos criando uma nova definição de classe. Aluno é o tipo, aluno é o nome da variável e new Aluno() cria uma instância daquele tipo. Essa instância é o objeto que será utilizado pelo programa.",

            "codigo" => <<<'JAVA'
Aluno aluno = new Aluno();
JAVA,

            "pergunta" =>
                "Qual parte representa o tipo usado como modelo?",

            "opcoes" => [

                [
                    "texto" =>
                        "Aluno",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "aluno",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_classe"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE MOSTRA NO CONSOLE
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_console" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Quem mostra no console é o println. O new só cria o objeto. Depois o código coloca Ana no nome e manda imprimir.",

            "natanael" =>
                "A criação do objeto não apresenta nenhuma mensagem automaticamente. O new prepara uma nova instância de Aluno. A saída acontece somente porque existe posteriormente um System.out.println.",

            "pergunta" =>
                "Qual comando realmente envia aluno.nome para o console?",

            "opcoes" => [

                [
                    "texto" =>
                        "System.out.println(aluno.nome);",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "new Aluno();",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_console"
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
                "Lembra do molde? new é tipo mandar fabricar uma unidade usando aquele molde. new Aluno() cria um aluno no programa.",

            "natanael" =>
                "Voltando à comparação da fábrica: a classe é o projeto disponível. A palavra new representa o momento em que utilizamos esse projeto para produzir uma nova unidade. Essa unidade criada é chamada de objeto ou instância.",

            "pergunta" =>
                "Depois de new Aluno(), temos apenas o modelo ou um objeto criado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um objeto criado",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Somente o modelo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_classe"
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
                "Fica assim: Aluno é o tipo, aluno é a variável e new Aluno() cria o objeto.",

            "natanael" =>
                "Podemos dividir a declaração em partes: Aluno informa o tipo; aluno identifica a variável; new Aluno() cria uma nova instância da classe.",

            "codigo" => <<<'JAVA'
Aluno aluno = new Aluno();
JAVA,

            "pergunta" =>
                "Qual ação é realizada por new Aluno()?",

            "opcoes" => [

                [
                    "texto" =>
                        "Cria um objeto do tipo Aluno",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Cria uma segunda classe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_classe"
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
                "Mesmo modelo, objetos diferentes...",

            "mensagem" =>
                "Agora a mesma classe será utilizada para criar dois objetos.",

            "codigo" => <<<'JAVA'
class Aluno {

    String nome;

}

public class Main {

    public static void main(String[] args) {

        Aluno aluno1 = new Aluno();

        aluno1.nome = "Ana";


        Aluno aluno2 = new Aluno();

        aluno2.nome = "Carlos";


        System.out.println(aluno1.nome);

        System.out.println(aluno2.nome);

    }

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana e depois Carlos",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Carlos e depois Carlos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_mesmo"
                ],

                [
                    "texto" =>
                        "Ana e depois Ana",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_primeiro"
                ],

                [
                    "texto" =>
                        "O Java não permite dois objetos da mesma classe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_dois"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE É O MESMO OBJETO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_mesmo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Tem dois new Aluno(). Isso cria dois objetos separados. Mudar o nome do aluno2 não troca o nome do aluno1.",

            "natanael" =>
                "Cada execução de new Aluno() cria uma nova instância. aluno1 e aluno2 apontam para objetos diferentes. Por isso cada objeto pode manter seu próprio valor no atributo nome.",

            "pergunta" =>
                "Quantas vezes new Aluno() aparece no código?",

            "opcoes" => [

                [
                    "texto" =>
                        "Duas vezes",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Uma vez",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_mesmo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE OS DOIS POSSUEM ANA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_primeiro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Ana foi colocada no aluno1. No aluno2 o código colocou Carlos. Cada um tem seu próprio nome.",

            "natanael" =>
                "O valor Ana foi atribuído ao atributo nome do primeiro objeto. Depois criamos outro objeto e atribuímos Carlos ao atributo nome dele. Uma atribuição não substitui automaticamente a informação do outro objeto.",

            "pergunta" =>
                "Qual valor foi colocado em aluno2.nome?",

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
                        "Ana",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_primeiro"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE NÃO PODE CRIAR DOIS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_dois" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Pode criar vários kkkkk. Uma classe serve justamente pra isso. É um molde que pode gerar muitos objetos.",

            "natanael" =>
                "Uma das principais vantagens de uma classe é permitir a criação de várias instâncias com a mesma estrutura. Assim como um projeto pode ser usado para fabricar várias unidades, uma classe pode originar muitos objetos.",

            "pergunta" =>
                "Um único molde pode ser usado para fabricar apenas uma unidade ou várias?",

            "opcoes" => [

                [
                    "texto" =>
                        "Várias unidades",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Somente uma unidade",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_dois"
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
                "São dois alunos feitos com o mesmo molde. aluno1 guarda Ana. aluno2 guarda Carlos. Um não apaga o outro.",

            "natanael" =>
                "Os dois objetos possuem a mesma estrutura porque vieram da classe Aluno, mas mantêm estados independentes. O atributo nome do primeiro contém Ana e o atributo nome do segundo contém Carlos.",

            "pergunta" =>
                "Qual sequência será apresentada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana → Carlos",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Carlos → Carlos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_mesmo"
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
                "Objetos também podem realizar ações...",

            "mensagem" =>
                "Além de guardar informações, uma classe pode definir comportamentos para seus objetos.",

            "codigo" => <<<'JAVA'
class Cafeteira {

    void prepararCafe() {

        System.out.println(
            "Preparando café"
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Cafeteira maquina = new Cafeteira();

        maquina.prepararCafe();

    }

}
JAVA,

            "pergunta" =>
                "O que acontece quando maquina.prepararCafe() é executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "O método é executado e mostra Preparando café",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Uma nova cafeteira é criada",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_new"
                ],

                [
                    "texto" =>
                        "Nada acontece porque o método já foi escrito na classe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_chamada"
                ],

                [
                    "texto" =>
                        "prepararCafe vira um atributo de texto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_atributo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUNDIU MÉTODO COM NEW
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_new" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A cafeteira já foi criada na linha com new Cafeteira(). prepararCafe() só manda esse objeto realizar uma ação.",

            "natanael" =>
                "A criação do objeto acontece em new Cafeteira(). Depois disso, maquina.prepararCafe() utiliza o objeto já existente para executar um comportamento definido em sua classe.",

            "pergunta" =>
                "Qual trecho realmente cria o objeto Cafeteira?",

            "opcoes" => [

                [
                    "texto" =>
                        "new Cafeteira()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "maquina.prepararCafe()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_new"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE DEFINIR JÁ EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_chamada" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Escrever o método na classe só ensina a ação. Pra ação acontecer, precisa chamar ela no objeto com maquina.prepararCafe().",

            "natanael" =>
                "A definição do método descreve o que deve acontecer quando aquela ação for solicitada. Porém o código interno não é executado apenas por estar escrito na classe. A chamada do método é necessária para iniciar o comportamento.",

            "pergunta" =>
                "Qual linha está solicitando a execução do método?",

            "opcoes" => [

                [
                    "texto" =>
                        "maquina.prepararCafe();",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "void prepararCafe()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_chamada"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUNDIU MÉTODO COM ATRIBUTO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_atributo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Atributo guarda informação. Método representa uma ação. prepararCafe tem parênteses e um bloco de código, então é um comportamento.",

            "natanael" =>
                "Um atributo representa um dado mantido pelo objeto, como nome ou nível. Já prepararCafe possui parênteses e um bloco de instruções, indicando que é um método responsável por executar uma ação.",

            "pergunta" =>
                "Preparar café representa uma informação ou uma ação?",

            "opcoes" => [

                [
                    "texto" =>
                        "Uma ação",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Uma informação armazenada",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_atributo"
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
                "Pensa no objeto como uma cafeteira real. new monta a máquina. prepararCafe() aperta o botão pra ela trabalhar.",

            "natanael" =>
                "Podemos separar criação e comportamento. Primeiro construímos a cafeteira com new. Depois utilizamos a referência maquina para solicitar a ação prepararCafe. O código definido dentro do método é então executado.",

            "pergunta" =>
                "Qual mensagem o método apresenta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Preparando café",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Nenhuma mensagem",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_chamada"
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
                "Classes e objetos desbloqueados!",

            "robert" =>
                "Boa po kkkkk. Guarda o resumão: classe é o molde, objeto é a unidade criada com new, atributos guardam informações e métodos representam ações. Dá pra criar vários objetos da mesma classe, e cada um pode ter seus próprios valores. Agora vamos aprofundar justamente os atributos e métodos.",

            "natanael" =>
                "Fechamos a introdução à programação orientada a objetos. Uma classe define um modelo com características e comportamentos. Um objeto é uma instância criada a partir dessa classe, normalmente utilizando new. Os atributos mantêm o estado de cada objeto, enquanto os métodos representam ações que ele pode executar. Objetos diferentes da mesma classe podem possuir valores independentes. Na próxima aula vamos analisar com mais detalhes como atributos e métodos trabalham juntos dentro de uma classe.",

            "mensagem" =>
                "Tu concluiu a décima sexta Aula Conversada."

        ]

    ]

];