<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 36,

    "titulo" =>
        "Informações compartilhadas pela classe",

    "assunto" =>
        "static",

    "descricao" =>
        "Entenda como atributos e métodos static pertencem à classe e podem ser compartilhados entre diferentes objetos.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        37,


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
                "Contando todos os objetos criados...",

            "mensagem" =>
                "O atributo totalAlunos foi declarado como static e é atualizado sempre que um novo objeto Aluno é criado.",

            "codigo" => <<<'JAVA'
class Aluno {

    String nome;

    static int totalAlunos = 0;


    Aluno(String nome) {

        this.nome = nome;

        totalAlunos++;

    }

}

public class Main {

    public static void main(String[] args) {

        Aluno aluno1 =
            new Aluno("Ana");

        Aluno aluno2 =
            new Aluno("Carlos");

        Aluno aluno3 =
            new Aluno("Lia");

        System.out.println(
            Aluno.totalAlunos
        );

    }

}
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
                        "1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_individual"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_construtor"
                ],

                [
                    "texto" =>
                        "Ana, Carlos e Lia",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nomes"
                ],

                [
                    "texto" =>
                        "Não sei o que static muda",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE CADA OBJETO POSSUI UM CONTADOR SEPARADO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_individual" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se totalAlunos fosse comum, cada objeto teria sua própria cópia. Mas ele é static, então existe um contador compartilhado pela classe toda.",

            "natanael" =>
                "A palavra static faz o atributo pertencer à classe Aluno. As três construções utilizam o mesmo contador, em vez de cada objeto manter um total separado.",

            "pergunta" =>
                "Os três objetos modificam o mesmo atributo totalAlunos?",

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
                        "Não, cada um possui um contador isolado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_individual"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O CONSTRUTOR NÃO ALTERA O CONTADOR
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_construtor" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O contador começa em zero, mas o construtor executa totalAlunos++ toda vez que aparece new Aluno(...).",

            "natanael" =>
                "A criação de cada objeto executa o construtor. Dentro dele, o operador ++ acrescenta uma unidade ao valor compartilhado.",

            "codigo" => <<<'JAVA'
totalAlunos++;
JAVA,

            "pergunta" =>
                "Quantas vezes o construtor de Aluno é executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Três vezes",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Nenhuma vez",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_construtor"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU O CONTADOR COM OS NOMES
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_nomes" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Os nomes estão nos atributos nome de cada objeto. O println mostra totalAlunos, que é um número inteiro.",

            "natanael" =>
                "Cada objeto armazena seu próprio nome, mas a saída consulta o atributo static totalAlunos. Esse atributo representa apenas a quantidade de instâncias criadas.",

            "pergunta" =>
                "Qual é o tipo declarado para totalAlunos?",

            "opcoes" => [

                [
                    "texto" =>
                        "int",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nomes"
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
                "Pensa num contador na entrada de uma escola. Cada aluno que entra aumenta o mesmo número. Não existe um contador diferente pra cada pessoa.",

            "natanael" =>
                "Imagine um registro geral da turma. Cada objeto representa um aluno diferente, mas todos contribuem para uma única informação compartilhada: a quantidade total de alunos.",

            "pergunta" =>
                "Depois da criação de Ana, quanto vale totalAlunos?",

            "opcoes" => [

                [
                    "texto" =>
                        "1",

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
                        "pergunta_1_erro_construtor"
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
                "Começa em zero. Ana aumenta pra 1, Carlos pra 2 e Lia pra 3. Como o atributo é static, todo mundo usa o mesmo contador.",

            "natanael" =>
                "As três chamadas ao construtor atualizam o único atributo pertencente à classe. Depois da última criação, o valor compartilhado é três.",

            "pergunta" =>
                "Qual é o total final de alunos?",

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
                        "pergunta_1_erro_individual"
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
                "Chamando um método pela própria classe...",

            "mensagem" =>
                "O método dobro foi declarado como static. Por isso ele pode ser chamado sem criar um objeto Calculadora.",

            "codigo" => <<<'JAVA'
class Calculadora {

    static int dobro(
        int numero
    ) {

        return numero * 2;

    }

}

public class Main {

    public static void main(String[] args) {

        int resultado =
            Calculadora.dobro(7);

        System.out.println(
            resultado
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual número aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "14",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_calculo"
                ],

                [
                    "texto" =>
                        "2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_argumento"
                ],

                [
                    "texto" =>
                        "O código não funciona porque faltou new Calculadora()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_objeto"
                ],

                [
                    "texto" =>
                        "Não sei como o método é chamado",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ESQUECEU O CÁLCULO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_calculo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O 7 entra no parâmetro numero. Depois o método calcula numero * 2 antes de devolver o resultado.",

            "natanael" =>
                "O argumento sete é recebido pelo parâmetro. A expressão de retorno multiplica esse valor por dois, produzindo um novo inteiro.",

            "pergunta" =>
                "Quanto resulta 7 multiplicado por 2?",

            "opcoes" => [

                [
                    "texto" =>
                        "14",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_calculo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUNDIU O ARGUMENTO COM O MULTIPLICADOR
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_argumento" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O 2 aparece dentro do cálculo, mas o método não devolve só ele. Ele multiplica o número recebido por 2.",

            "natanael" =>
                "O valor dois funciona como multiplicador. O retorno corresponde ao resultado completo da expressão numero * 2.",

            "pergunta" =>
                "Qual valor foi enviado para o parâmetro numero?",

            "opcoes" => [

                [
                    "texto" =>
                        "7",

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
                        "pergunta_2_erro_argumento"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE PRECISA CRIAR OBJETO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_objeto" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Como dobro é static, ele pertence à classe Calculadora. Dá pra chamar direto com Calculadora.dobro(7), sem criar objeto.",

            "natanael" =>
                "Métodos static podem ser acessados utilizando o nome da classe. A chamada não depende do estado de uma instância específica.",

            "codigo" => <<<'JAVA'
Calculadora.dobro(7);
JAVA,

            "pergunta" =>
                "Qual nome aparece antes do ponto na chamada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Calculadora, o nome da classe",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Um objeto criado com new",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_objeto"
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
                "Pensa numa calculadora de uso geral. Tu não precisa criar uma calculadora com nome e estado. Só chama a função pela classe e entrega o número.",

            "natanael" =>
                "O método não depende de atributos pertencentes a um objeto. Ele recebe todas as informações necessárias pelo parâmetro e devolve o cálculo.",

            "pergunta" =>
                "O método dobro recebe qual número?",

            "opcoes" => [

                [
                    "texto" =>
                        "7",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Nenhum número",

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
                "Calculadora.dobro(7) chama o método static pela classe. O método faz 7 vezes 2 e devolve 14.",

            "natanael" =>
                "A chamada utiliza a classe porque o método é static. O argumento é processado e o resultado retornado fica armazenado na variável resultado.",

            "pergunta" =>
                "Qual valor fica guardado em resultado?",

            "opcoes" => [

                [
                    "texto" =>
                        "14",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_calculo"
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
                "Um método static tentando acessar um objeto...",

            "mensagem" =>
                "O atributo nome pertence a cada objeto Pessoa, mas o método mostrarNome pertence à classe.",

            "codigo" => <<<'JAVA'
class Pessoa {

    String nome;


    Pessoa(String nome) {

        this.nome = nome;

    }


    static void mostrarNome() {

        System.out.println(
            nome
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Pessoa pessoa =
            new Pessoa("Ana");

        Pessoa.mostrarNome();

    }

}
JAVA,

            "pergunta" =>
                "O que acontece com esse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não compila porque o método static tenta acessar diretamente um atributo de instância",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Mostra Ana normalmente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_ana"
                ],

                [
                    "texto" =>
                        "Mostra null",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_null"
                ],

                [
                    "texto" =>
                        "Cria automaticamente uma nova Pessoa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_novo"
                ],

                [
                    "texto" =>
                        "Não sei por que o acesso é diferente",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE MOSTRA ANA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_ana" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método static não sabe qual Pessoa tu quer usar. Poderiam existir Ana, Carlos e Lia. Sem receber um objeto, ele não consegue escolher um nome.",

            "natanael" =>
                "O atributo nome pertence individualmente a cada instância. Como o método pertence à classe, não existe uma referência de objeto específica disponível para acessar esse campo.",

            "pergunta" =>
                "O atributo nome pertence à classe inteira ou a cada objeto Pessoa?",

            "opcoes" => [

                [
                    "texto" =>
                        "A cada objeto Pessoa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "À classe inteira",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_ana"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE MOSTRA NULL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_null" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O código nem chega a executar. O compilador percebe que nome é de objeto e está sendo usado diretamente num contexto static.",

            "natanael" =>
                "Não existe uma execução que produza null. A referência inválida é identificada durante a compilação, impedindo que o programa seja executado.",

            "pergunta" =>
                "O problema aparece durante a compilação ou somente durante a execução?",

            "opcoes" => [

                [
                    "texto" =>
                        "Durante a compilação",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Somente durante a execução",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_null"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE OUTRO OBJETO É CRIADO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_novo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Objeto novo só aparece quando o código usa new Pessoa(...). O método mostrarNome não possui nenhum new.",

            "natanael" =>
                "Um método static não cria automaticamente uma instância para acessar atributos comuns. Seria necessário receber ou construir explicitamente um objeto.",

            "pergunta" =>
                "Existe alguma expressão new Pessoa dentro de mostrarNome?",

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
                        "pergunta_3_erro_novo"
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
                "Pensa que nome está no crachá de cada pessoa. O método static fica numa placa geral da classe. A placa não sabe qual crachá deve ler sem receber uma pessoa.",

            "natanael" =>
                "A classe pode possuir várias instâncias, cada uma com um nome diferente. Um método static não está ligado automaticamente a nenhuma dessas instâncias.",

            "pergunta" =>
                "Para mostrar um nome específico, o método precisa conhecer o quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um objeto Pessoa específico",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Somente o nome da classe",

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
                "mostrarNome é da classe. nome é de cada objeto. Pra funcionar, o método teria que receber uma Pessoa ou deixar de ser static.",

            "natanael" =>
                "O contexto static não possui acesso direto ao estado de uma instância. Uma correção possível seria enviar o objeto como parâmetro.",

            "codigo" => <<<'JAVA'
static void mostrarNome(
    Pessoa pessoa
) {

    System.out.println(
        pessoa.nome
    );

}
JAVA,

            "pergunta" =>
                "Por que essa versão consegue acessar nome?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque recebeu um objeto Pessoa específico",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Porque todo atributo virou static automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_ana"
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
                "Separando informações individuais e compartilhadas...",

            "mensagem" =>
                "Cada conta possui seu próprio saldo, mas o total de contas pertence à classe inteira.",

            "codigo" => <<<'JAVA'
class Conta {

    private double saldo;

    private static int totalContas = 0;


    Conta(double saldoInicial) {

        saldo = saldoInicial;

        totalContas++;

    }


    double getSaldo() {

        return saldo;

    }


    static int getTotalContas() {

        return totalContas;

    }

}

public class Main {

    public static void main(String[] args) {

        Conta conta1 =
            new Conta(100.0);

        Conta conta2 =
            new Conta(250.0);

        System.out.println(
            conta1.getSaldo()
        );

        System.out.println(
            conta2.getSaldo()
        );

        System.out.println(
            Conta.getTotalContas()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "100.0, 250.0 e depois 2",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "250.0, 250.0 e depois 2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_saldo_compartilhado"
                ],

                [
                    "texto" =>
                        "100.0, 250.0 e depois 1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_total"
                ],

                [
                    "texto" =>
                        "100.0, 100.0 e depois 2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_segunda"
                ],

                [
                    "texto" =>
                        "O código não funciona porque getTotalContas é chamado pela classe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_metodo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE O SALDO TAMBÉM É COMPARTILHADO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_saldo_compartilhado" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "saldo não tem static. Então cada Conta possui seu próprio valor. A segunda conta não apaga nem altera o saldo da primeira.",

            "natanael" =>
                "O atributo saldo pertence às instâncias. conta1 armazena 100.0 e conta2 armazena 250.0 de maneira independente.",

            "pergunta" =>
                "O atributo saldo foi declarado como static?",

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
                        "pergunta_4_erro_saldo_compartilhado"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONSIDEROU APENAS UMA CONTA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_total" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O construtor é executado duas vezes. Em cada criação, totalContas aumenta uma unidade.",

            "natanael" =>
                "A primeira instância altera o total de zero para um. A segunda utiliza o mesmo atributo static e o altera de um para dois.",

            "pergunta" =>
                "Quantas expressões new Conta aparecem?",

            "opcoes" => [

                [
                    "texto" =>
                        "Duas",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Uma",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_total"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUNDIU O SALDO DA SEGUNDA CONTA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_segunda" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A segunda conta foi criada com saldoInicial igual a 250.0. O construtor guarda esse valor no saldo daquele objeto.",

            "natanael" =>
                "Cada chamada ao construtor recebe um argumento diferente. conta2 recebe 250.0 e mantém esse valor em seu atributo individual.",

            "pergunta" =>
                "Qual valor foi enviado em new Conta(250.0)?",

            "opcoes" => [

                [
                    "texto" =>
                        "250.0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "100.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_segunda"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE MÉTODO STATIC NÃO PODE SER CHAMADO PELA CLASSE
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_metodo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "É justamente por ser static que getTotalContas pode ser chamado com Conta.getTotalContas().",

            "natanael" =>
                "O método consulta uma informação pertencente à classe. Portanto a forma mais adequada de acesso utiliza diretamente o nome Conta.",

            "pergunta" =>
                "getTotalContas foi declarado com a palavra static?",

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
                        "pergunta_4_erro_metodo"
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
                "Os saldos são individuais: 100 e 250. Já totalContas é compartilhado e aumenta nas duas construções, chegando em 2.",

            "natanael" =>
                "Os getters de saldo consultam estados pertencentes a objetos diferentes. O método static consulta o único contador mantido pela classe.",

            "pergunta" =>
                "Qual sequência completa será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "100.0 → 250.0 → 2",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "250.0 → 250.0 → 1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_saldo_compartilhado"
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
                "Static desbloqueado!",

            "robert" =>
                "Boa po kkkkk. Agora ficou clara a diferença: um atributo normal pertence a cada objeto, enquanto um atributo static pertence à classe e é compartilhado. Métodos static também são chamados direto pela classe, tipo Calculadora.dobro(). Só lembra que um método static não sabe qual objeto usar, então não pode acessar diretamente um atributo de instância sem receber um objeto.",

            "natanael" =>
                "Concluímos o funcionamento básico de static. Atributos comuns mantêm valores independentes em cada instância, enquanto atributos static representam informações únicas e compartilhadas pela classe. Métodos static podem ser chamados sem a criação de objetos e são adequados para operações que não dependem do estado de uma instância específica. Também vimos que um contexto static não acessa diretamente atributos de instância sem possuir uma referência para o objeto. Na próxima aula vamos estudar sobrecarga e entender como vários métodos podem compartilhar o mesmo nome utilizando parâmetros diferentes.",

            "mensagem" =>
                "Tu concluiu a trigésima sexta Aula Conversada."

        ]

    ]

];