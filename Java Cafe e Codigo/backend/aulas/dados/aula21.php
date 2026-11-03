<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 21,

    "titulo" =>
        "Um mesmo comando, comportamentos diferentes",

    "assunto" =>
        "Polimorfismo",

    "descricao" =>
        "Descubra como objetos diferentes podem responder de maneiras específicas à mesma chamada de método.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        22,


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
                "Uma variável Animal com um Cachorro dentro...",

            "mensagem" =>
                "Repara que a variável possui o tipo Animal, mas o objeto criado é um Cachorro.",

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

        Animal animal = new Cachorro();

        animal.emitirSom();

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
                        "Som de animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
                ],

                [
                    "texto" =>
                        "As duas mensagens aparecem",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_duas"
                ],

                [
                    "texto" =>
                        "O código não compila porque os tipos são diferentes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_compilacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ESCOLHEU A VERSÃO DE ANIMAL
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A variável é do tipo Animal, mas o objeto criado com new é um Cachorro. Na hora de executar o método, o Java olha o objeto real e usa a versão dele.",

            "natanael" =>
                "Precisamos separar o tipo da variável do objeto realmente criado. A variável aceita um Animal, mas recebe uma instância de Cachorro. Como Cachorro redefiniu emitirSom, essa implementação específica será executada.",

            "pergunta" =>
                "Qual objeto foi criado depois da palavra new?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um Cachorro",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Um Animal comum",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE AS DUAS VERSÕES EXECUTAM
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_duas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não executa as duas automaticamente. Como o objeto real é Cachorro, a versão dele é escolhida. Só aparece Au au.",

            "natanael" =>
                "A chamada do método seleciona uma implementação. Como a instância armazenada é um Cachorro, o Java utiliza a redefinição existente nessa classe. O método de Animal não é executado junto automaticamente.",

            "pergunta" =>
                "Qual classe possui a versão que mostra Au au?",

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
                        "pergunta_1_erro_duas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE NÃO COMPILA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_compilacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Compila sim. Cachorro é um tipo de Animal por causa do extends. Então uma variável Animal pode guardar um objeto Cachorro.",

            "natanael" =>
                "A atribuição é permitida porque existe uma relação de herança. Todo Cachorro também pode ser tratado como Animal. Por isso uma referência do tipo Animal pode apontar para uma instância de Cachorro.",

            "pergunta" =>
                "Cachorro utiliza extends Animal?",

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
                        "pergunta_1_erro_compilacao"
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
                "Esse é o começo do polimorfismo: a variável é Animal, mas o objeto real é Cachorro. O mesmo emitirSom() usa a versão do objeto.",

            "natanael" =>
                "O tipo Animal oferece uma forma geral de trabalhar com o objeto. Porém, durante a execução, o Java identifica que a instância é um Cachorro e chama sua implementação específica.",

            "pergunta" =>
                "Qual mensagem é exibida pela versão de Cachorro?",

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
                        "Som de animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
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
                "Vários objetos no mesmo array...",

            "mensagem" =>
                "Agora um array do tipo Animal guarda objetos de classes diferentes.",

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

class Gato extends Animal {

    @Override
    void emitirSom() {

        System.out.println(
            "Miau"
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Animal[] animais = {
            new Cachorro(),
            new Gato()
        };

        for (
            int i = 0;
            i < animais.length;
            i++
        ) {

            animais[i].emitirSom();

        }

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Au au e depois Miau",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Som de animal duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_geral"
                ],

                [
                    "texto" =>
                        "Au au duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_cachorro"
                ],

                [
                    "texto" =>
                        "O array não aceita Cachorro e Gato juntos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_array"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE USA SEMPRE ANIMAL
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_geral" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O array é Animal[], mas cada espaço guarda um objeto real diferente. O primeiro é Cachorro e o segundo é Gato. Cada um responde do seu jeito.",

            "natanael" =>
                "O tipo do array permite organizar objetos relacionados à classe Animal. Entretanto cada posição mantém sua instância real. A primeira contém um Cachorro e a segunda contém um Gato, portanto suas redefinições são respeitadas.",

            "pergunta" =>
                "Qual objeto está na segunda posição do array?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um Gato",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Um Animal sem tipo específico",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_geral"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE OS DOIS SÃO CACHORROS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_cachorro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Só o primeiro objeto é Cachorro. O segundo foi criado com new Gato(), então ele usa a versão que mostra Miau.",

            "natanael" =>
                "O primeiro elemento foi criado com new Cachorro(). Já o segundo foi criado com new Gato(). Embora ambos sejam tratados como Animal, continuam sendo objetos diferentes.",

            "pergunta" =>
                "Qual som está definido dentro da classe Gato?",

            "opcoes" => [

                [
                    "texto" =>
                        "Miau",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Au au",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_cachorro"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O ARRAY NÃO ACEITA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_array" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aceita porque Cachorro e Gato são tipos de Animal. É tipo uma caixa escrita Animais: pode entrar cachorro, gato e outros animais.",

            "natanael" =>
                "Como Cachorro e Gato estendem Animal, ambos podem ser armazenados em posições cujo tipo é Animal. Essa possibilidade permite tratar objetos diferentes de uma forma comum.",

            "pergunta" =>
                "Cachorro e Gato possuem Animal como classe principal?",

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
                        "Não",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_array"
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
                "Na primeira volta, animais[0] é Cachorro e mostra Au au. Na segunda, animais[1] é Gato e mostra Miau.",

            "natanael" =>
                "O for percorre as duas posições. Em cada chamada, o método é escolhido conforme o objeto real armazenado naquela posição. Por isso os resultados são diferentes.",

            "pergunta" =>
                "Qual sequência completa será exibida?",

            "opcoes" => [

                [
                    "texto" =>
                        "Au au → Miau",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Som de animal → Som de animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_geral"
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
                "Um método capaz de receber vários tipos...",

            "mensagem" =>
                "O método apresentarSom recebe um Animal, mas será utilizado com objetos diferentes.",

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

class Gato extends Animal {

    @Override
    void emitirSom() {

        System.out.println(
            "Miau"
        );

    }

}

public class Main {

    static void apresentarSom(
        Animal animal
    ) {

        animal.emitirSom();

    }


    public static void main(String[] args) {

        apresentarSom(
            new Cachorro()
        );

        apresentarSom(
            new Gato()
        );

    }

}
JAVA,

            "pergunta" =>
                "Por que o mesmo método apresentarSom produz resultados diferentes?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque cada objeto possui sua própria versão de emitirSom",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Porque apresentarSom muda seu próprio código automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_metodo"
                ],

                [
                    "texto" =>
                        "Porque Cachorro e Gato executam os dois métodos ao mesmo tempo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_duas"
                ],

                [
                    "texto" =>
                        "Porque o parâmetro Animal transforma todos os objetos em iguais",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_iguais"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O MÉTODO MUDA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_metodo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O código de apresentarSom continua exatamente igual. O que muda é o objeto recebido. Uma hora entra Cachorro, outra hora entra Gato.",

            "natanael" =>
                "O método apresentarSom possui uma única implementação. Ele sempre chama animal.emitirSom(). O comportamento varia porque o argumento recebido possui uma classe real diferente em cada chamada.",

            "pergunta" =>
                "Na primeira chamada, qual objeto é enviado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um Cachorro",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Um Gato",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_metodo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE DUAS VERSÕES EXECUTAM JUNTAS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_duas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Cada chamada recebe só um objeto. Quando recebe Cachorro, mostra Au au. Quando recebe Gato, mostra Miau.",

            "natanael" =>
                "As chamadas acontecem separadamente. Na primeira execução o parâmetro referencia um Cachorro. Na segunda, referencia um Gato. Em cada momento apenas a implementação correspondente é utilizada.",

            "pergunta" =>
                "Quantos objetos são enviados em cada chamada de apresentarSom?",

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
                        "Dois objetos ao mesmo tempo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_duas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE OS OBJETOS FICAM IGUAIS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_iguais" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Tratar os dois como Animal não apaga o que eles são de verdade. Cachorro continua Cachorro e Gato continua Gato.",

            "natanael" =>
                "O parâmetro Animal oferece uma visão comum dos objetos, mas não altera suas classes reais. A identidade de cada instância é preservada durante a execução.",

            "pergunta" =>
                "Um objeto criado com new Gato() deixa de ser Gato ao entrar no parâmetro Animal?",

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
                        "pergunta_3_erro_iguais"
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
                "O mesmo comando animal.emitirSom() funciona pros dois. Só que Cachorro responde Au au e Gato responde Miau. Isso é polimorfismo na prática.",

            "natanael" =>
                "O método trabalha com o tipo geral Animal. Durante a execução, a implementação específica é selecionada com base na instância recebida. Assim um único código atende vários tipos relacionados.",

            "pergunta" =>
                "Qual sequência aparece nas duas chamadas?",

            "opcoes" => [

                [
                    "texto" =>
                        "Au au → Miau",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Som de animal → Som de animal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_iguais"
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
                "Existe um limite importante...",

            "mensagem" =>
                "O objeto criado é um Cachorro, mas observa o tipo utilizado na variável.",

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


    void buscarBola() {

        System.out.println(
            "Buscando bola"
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Animal animal = new Cachorro();

        animal.buscarBola();

    }

}
JAVA,

            "pergunta" =>
                "O que acontece ao tentar executar animal.buscarBola()?",

            "opcoes" => [

                [
                    "texto" =>
                        "O código não compila porque Animal não declara buscarBola",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Aparece Buscando bola normalmente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_objeto"
                ],

                [
                    "texto" =>
                        "O Java executa emitirSom no lugar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_outro"
                ],

                [
                    "texto" =>
                        "Um novo Cachorro é criado automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_new"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — OLHOU APENAS O OBJETO REAL
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_objeto" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O objeto realmente é Cachorro, mas a variável foi declarada como Animal. Pelo nome animal, o Java só libera os métodos conhecidos pelo tipo Animal.",

            "natanael" =>
                "A instância possui o método buscarBola, mas a referência utilizada possui o tipo Animal. Durante a compilação, o Java verifica quais métodos estão declarados nesse tipo de referência. Animal não conhece buscarBola.",

            "pergunta" =>
                "Qual é o tipo declarado antes do nome da variável animal?",

            "opcoes" => [

                [
                    "texto" =>
                        "Animal",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Cachorro",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_objeto"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE EXECUTA OUTRO MÉTODO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_outro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Java não troca um método por outro kkkkk. Se buscarBola não está disponível pelo tipo Animal, a chamada gera erro de compilação.",

            "natanael" =>
                "O compilador não substitui uma chamada inválida por outro método. emitirSom e buscarBola representam comportamentos diferentes. Como buscarBola não está declarado em Animal, a chamada não é aceita.",

            "pergunta" =>
                "Animal possui um método chamado buscarBola?",

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
                        "pergunta_4_erro_outro"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE CRIA OUTRO OBJETO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_new" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Objeto novo só aparece com new. animal.buscarBola() seria só uma chamada de método. O problema é que o tipo Animal não conhece esse método.",

            "natanael" =>
                "Nenhuma nova instância é criada nessa linha, pois não existe uma expressão new. A linha tenta utilizar um comportamento no objeto existente, mas esse comportamento não está disponível pelo tipo da referência.",

            "pergunta" =>
                "Existe a palavra new na linha animal.buscarBola()?",

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
                        "pergunta_4_erro_new"
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
                "Pensa assim: o objeto real escolhe qual versão de um método herdado vai executar. Mas o tipo da variável decide quais nomes de métodos tu pode chamar.",

            "natanael" =>
                "Existem duas regras trabalhando juntas. O tipo da referência determina quais métodos estão disponíveis durante a compilação. Já o objeto real determina qual implementação redefinida será executada.",

            "codigo" => <<<'JAVA'
Animal animal = new Cachorro();

animal.emitirSom();  // permitido
animal.buscarBola(); // não permitido pelo tipo Animal
JAVA,

            "pergunta" =>
                "Por que buscarBola não pode ser chamado nessa referência?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque o método não está declarado em Animal",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Porque nenhum Cachorro foi criado",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_objeto"
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
                "Polimorfismo desbloqueado!",

            "robert" =>
                "Boa po kkkkk. O polimorfismo deixa tu tratar Cachorro e Gato como Animal, mas cada um responde do seu jeito ao mesmo método. O tipo da variável diz quais métodos podem ser chamados, e o objeto real decide qual versão redefinida vai executar. Isso ajuda a criar código mais flexível sem fazer um método separado pra cada classe.",

            "natanael" =>
                "Fechamos o conceito básico de polimorfismo. Uma referência de um tipo geral pode apontar para objetos de classes filhas diferentes. Quando um método redefinido é chamado, a implementação escolhida depende do objeto realmente criado. Isso permite utilizar arrays, parâmetros e outras estruturas gerais para trabalhar com vários tipos relacionados. Também vimos que o tipo da referência determina quais métodos ficam disponíveis durante a compilação. Na próxima aula vamos estudar interfaces, que permitem definir comportamentos que diferentes classes devem implementar.",

            "mensagem" =>
                "Tu concluiu a vigésima primeira Aula Conversada."

        ]

    ]

];