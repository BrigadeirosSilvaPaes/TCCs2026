<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 17,

    "titulo" =>
        "Informações e comportamentos",

    "assunto" =>
        "Atributos e métodos",

    "descricao" =>
        "Descubra como os atributos guardam informações e como os métodos definem as ações realizadas pelos objetos.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        18,


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
                "Informação ou ação?",

            "mensagem" =>
                "Observa essa classe e tenta separar o que guarda informações daquilo que representa um comportamento.",

            "codigo" => <<<'JAVA'
class Jogador {

    String nome;

    int pontos;

    void jogar() {
        System.out.println("Jogando");
    }

}
JAVA,

            "pergunta" =>
                "Qual alternativa identifica corretamente os atributos e o método?",

            "opcoes" => [

                [
                    "texto" =>
                        "nome e pontos são atributos; jogar é um método",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "nome é método; pontos e jogar são atributos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nome"
                ],

                [
                    "texto" =>
                        "Todos são métodos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_todos"
                ],

                [
                    "texto" =>
                        "Não sei diferenciar atributo de método",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU NOME COM MÉTODO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_nome" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "nome não faz nenhuma ação. Ele só guarda um texto. Método normalmente tem parênteses, tipo jogar().",

            "natanael" =>
                "O atributo nome foi declarado com o tipo String e serve para armazenar uma informação do jogador. Já jogar possui parênteses e um bloco de código, indicando que representa uma ação.",

            "pergunta" =>
                "O nome de um jogador é uma informação ou uma ação?",

            "opcoes" => [

                [
                    "texto" =>
                        "Uma informação",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Uma ação",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nome"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE TODOS SÃO MÉTODOS
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_todos" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se tudo fosse método, o objeto não teria onde guardar nome nem pontos kkkkk. Essas duas partes são dados. jogar() é a ação.",

            "natanael" =>
                "Uma classe normalmente combina estado e comportamento. nome e pontos fazem parte do estado do objeto, pois armazenam valores. jogar representa um comportamento, porque executa um conjunto de instruções.",

            "pergunta" =>
                "Qual trecho possui parênteses e um bloco de código?",

            "opcoes" => [

                [
                    "texto" =>
                        "jogar()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "String nome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_todos"
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
                "Pensa numa pessoa. Nome e idade são informações dela. Correr e estudar são ações. Na classe funciona igual: atributo guarda; método faz.",

            "natanael" =>
                "Vamos comparar com uma ficha de personagem. A ficha pode guardar nome, pontos e nível. Essas informações são atributos. Já ações como jogar, atacar ou defender são representadas por métodos.",

            "pergunta" =>
                "Nesse exemplo, jogar representa uma informação ou uma ação?",

            "opcoes" => [

                [
                    "texto" =>
                        "Uma ação",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Uma informação armazenada",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nome"
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
                "É isso: atributo guarda dados e método representa uma ação. nome e pontos guardam. jogar() executa.",

            "natanael" =>
                "Exatamente. nome e pontos representam características mantidas pelo objeto. jogar representa um comportamento que poderá ser solicitado quando utilizarmos aquele objeto.",

            "pergunta" =>
                "Qual divisão está correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Atributos: nome e pontos | Método: jogar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Atributo: jogar | Métodos: nome e pontos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_todos"
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
                "Cada objeto guarda seus próprios dados...",

            "mensagem" =>
                "A mesma classe será usada para criar duas contas diferentes.",

            "codigo" => <<<'JAVA'
class Conta {

    String titular;

    double saldo;

}

public class Main {

    public static void main(String[] args) {

        Conta conta1 = new Conta();

        conta1.titular = "Ana";
        conta1.saldo = 500.0;


        Conta conta2 = new Conta();

        conta2.titular = "Carlos";
        conta2.saldo = 200.0;


        System.out.println(conta1.saldo);

        System.out.println(conta2.saldo);

    }

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "500.0 e depois 200.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "200.0 e depois 200.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_mesmo"
                ],

                [
                    "texto" =>
                        "500.0 e depois 500.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_primeiro"
                ],

                [
                    "texto" =>
                        "Os saldos são somados e aparece 700.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_soma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE ALTEROU OS DOIS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_mesmo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "conta1 e conta2 são objetos separados. Colocar 200 na conta2 não troca o saldo da conta1.",

            "natanael" =>
                "Apesar de os dois objetos terem sido criados a partir da classe Conta, cada instância mantém seu próprio estado. O atributo saldo de conta1 é independente do atributo saldo de conta2.",

            "pergunta" =>
                "O valor 200.0 foi atribuído a qual objeto?",

            "opcoes" => [

                [
                    "texto" =>
                        "conta2",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "conta1 e conta2 ao mesmo tempo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_mesmo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE OS DOIS POSSUEM O PRIMEIRO VALOR
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_primeiro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "500 foi colocado só na conta1. Depois o código criou outra conta e colocou 200 nela.",

            "natanael" =>
                "A atribuição conta1.saldo = 500.0 modifica apenas o primeiro objeto. Depois conta2.saldo = 200.0 define um valor diferente para a segunda instância.",

            "pergunta" =>
                "Qual valor foi colocado diretamente em conta2.saldo?",

            "opcoes" => [

                [
                    "texto" =>
                        "200.0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "500.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_primeiro"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE OS VALORES SÃO SOMADOS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_soma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não tem nenhuma soma no código kkkkk. Os dois println mostram um atributo por vez.",

            "natanael" =>
                "Os valores pertencem a objetos diferentes e não existe uma operação de adição entre eles. O primeiro println consulta conta1.saldo e o segundo consulta conta2.saldo.",

            "pergunta" =>
                "Existe algum sinal de + somando os dois saldos?",

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
                        "pergunta_2_erro_soma"
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
                "Pensa em duas carteiras. Uma tem 500 e outra tem 200. Elas seguem o mesmo modelo de carteira, mas o dinheiro guardado é separado.",

            "natanael" =>
                "Podemos imaginar duas contas bancárias reais. Ambas possuem titular e saldo, mas cada uma mantém suas próprias informações. A classe define a estrutura comum; os objetos guardam valores independentes.",

            "pergunta" =>
                "Qual sequência será exibida?",

            "opcoes" => [

                [
                    "texto" =>
                        "500.0 → 200.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "200.0 → 200.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_mesmo"
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
                "O método pode usar os atributos...",

            "mensagem" =>
                "Agora observa como um método utiliza informações guardadas no próprio objeto.",

            "codigo" => <<<'JAVA'
class Aluno {

    String nome;

    int nivel;

    void apresentar() {

        System.out.println(
            nome + " está no nível " + nivel
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Aluno aluno = new Aluno();

        aluno.nome = "Robert";
        aluno.nivel = 5;

        aluno.apresentar();

    }

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece quando apresentar() é chamado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Robert está no nível 5",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "nome está no nível nivel",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_literais"
                ],

                [
                    "texto" =>
                        "Nada aparece porque nome e nivel estão fora do método",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_acesso"
                ],

                [
                    "texto" =>
                        "O método cria um novo aluno",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_criacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE MOSTRA OS NOMES DAS VARIÁVEIS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_literais" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "nome e nivel não estão entre aspas. Então o Java pega os valores guardados neles: Robert e 5.",

            "natanael" =>
                "Os trechos nome e nivel são referências aos atributos do objeto. Como não estão entre aspas, o Java utiliza seus valores atuais. Apenas os textos está no nível e os espaços são literais.",

            "pergunta" =>
                "Qual valor foi atribuído ao atributo nome?",

            "opcoes" => [

                [
                    "texto" =>
                        "Robert",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "nome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_literais"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O MÉTODO NÃO ACESSA OS ATRIBUTOS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_acesso" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método tá dentro da mesma classe Aluno. Então ele consegue usar os atributos nome e nivel daquele objeto.",

            "natanael" =>
                "Os atributos e o método pertencem à mesma classe. Quando apresentar é chamado por um objeto, o método trabalha com os valores armazenados naquele próprio objeto.",

            "pergunta" =>
                "apresentar() pertence à mesma classe que nome e nivel?",

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
                        "pergunta_3_erro_acesso"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU MÉTODO COM CRIAÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_criacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Quem cria o aluno é new Aluno(). apresentar() só usa o aluno que já existe pra mostrar os dados dele.",

            "natanael" =>
                "A criação da instância acontece na expressão new Aluno(). O método apresentar é chamado depois e apenas utiliza os atributos do objeto já criado para montar uma mensagem.",

            "pergunta" =>
                "Qual trecho realmente cria o objeto?",

            "opcoes" => [

                [
                    "texto" =>
                        "new Aluno()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "aluno.apresentar()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_criacao"
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
                "O objeto já guarda nome Robert e nível 5. Quando chama apresentar(), o método pega esses valores e monta a frase.",

            "natanael" =>
                "O estado do objeto foi preparado antes da chamada. nome contém Robert e nivel contém 5. O método acessa essas duas informações e as combina com o texto definido no println.",

            "pergunta" =>
                "Qual frase completa será montada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Robert está no nível 5",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "nome está no nível nivel",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_literais"
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
                "Métodos também podem alterar o objeto...",

            "mensagem" =>
                "Até agora o método apenas mostrou informações. Neste exemplo ele modifica um atributo.",

            "codigo" => <<<'JAVA'
class Personagem {

    int energia;

    void descansar() {

        energia = energia + 5;

    }

}

public class Main {

    public static void main(String[] args) {

        Personagem personagem = new Personagem();

        personagem.energia = 10;

        personagem.descansar();

        personagem.descansar();

        System.out.println(
            personagem.energia
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual valor aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "20",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "15",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_uma"
                ],

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_nao_muda"
                ],

                [
                    "texto" =>
                        "5",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_substitui"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONSIDEROU APENAS UMA CHAMADA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_uma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "15 seria depois de descansar uma vez. Só que o método foi chamado duas vezes. Na segunda ele soma mais 5.",

            "natanael" =>
                "A primeira chamada transforma energia de 10 para 15. Porém existe uma segunda chamada de descansar. O método é executado novamente utilizando o valor atualizado de 15.",

            "pergunta" =>
                "Quantas vezes personagem.descansar() aparece?",

            "opcoes" => [

                [
                    "texto" =>
                        "Duas vezes",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Uma vez",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_uma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE O MÉTODO NÃO ALTERA O ATRIBUTO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_nao_muda" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Dentro do método tem energia = energia + 5. Isso pega o valor atual, soma 5 e salva de volta no atributo.",

            "natanael" =>
                "O método não está apenas consultando energia. A atribuição coloca um novo valor no atributo. Por isso o estado do objeto é modificado após cada chamada.",

            "pergunta" =>
                "Depois da primeira chamada, quanto vale energia?",

            "opcoes" => [

                [
                    "texto" =>
                        "15",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_nao_muda"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE SUBSTITUI POR 5
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_substitui" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não tá fazendo energia = 5. Tá fazendo energia = energia + 5. Então usa o valor que já tinha e soma mais cinco.",

            "natanael" =>
                "A expressão do lado direito utiliza o valor atual do atributo. Se energia vale 10, energia + 5 resulta em 15. Esse resultado é então armazenado novamente no atributo.",

            "codigo" => <<<'JAVA'
energia = energia + 5;
JAVA,

            "pergunta" =>
                "Se energia vale 10, quanto resulta energia + 5?",

            "opcoes" => [

                [
                    "texto" =>
                        "15",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "5",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_substitui"
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
                "Faz a conta: começa em 10. Primeiro descanso soma 5 e vira 15. Segundo descanso soma mais 5 e vira 20.",

            "natanael" =>
                "Acompanhe o estado do objeto. Inicialmente energia vale 10. Depois da primeira chamada passa a 15. A segunda chamada utiliza esse novo valor e acrescenta mais cinco, chegando a 20.",

            "pergunta" =>
                "Qual é o valor final de energia?",

            "opcoes" => [

                [
                    "texto" =>
                        "20",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "15",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_uma"
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
                "Atributos e métodos conectados!",

            "robert" =>
                "Boa po kkkkk. Agora ficou bem claro: atributo guarda informação e método representa comportamento. Cada objeto tem seus próprios valores, e os métodos podem consultar ou até mudar esses valores. É assim que os objetos deixam de ser só um monte de dados e começam a ter ações de verdade. Agora falta aprender como criar o objeto já com as informações prontas.",

            "natanael" =>
                "Fechamos o funcionamento básico de atributos e métodos. Os atributos representam o estado de um objeto e armazenam suas informações. Os métodos representam comportamentos, podendo consultar os atributos ou modificar seus valores. Cada instância mantém seu próprio estado, mesmo quando vários objetos são criados a partir da mesma classe. Na próxima aula vamos estudar os construtores, que permitem preparar os atributos no momento em que um objeto é criado.",

            "mensagem" =>
                "Tu concluiu a décima sétima Aula Conversada."

        ]

    ]

];