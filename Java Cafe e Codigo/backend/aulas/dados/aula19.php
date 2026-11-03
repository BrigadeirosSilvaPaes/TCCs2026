<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 19,

    "titulo" =>
        "Protegendo as informações do objeto",

    "assunto" =>
        "Encapsulamento",

    "descricao" =>
        "Descubra como proteger os atributos de uma classe e controlar a forma como seus valores são consultados ou modificados.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        20,


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
                "Um atributo protegido...",

            "mensagem" =>
                "Observa que o atributo nome agora possui a palavra private.",

            "codigo" => <<<'JAVA'
class Pessoa {

    private String nome;

}

public class Main {

    public static void main(String[] args) {

        Pessoa pessoa = new Pessoa();

        pessoa.nome = "Ana";

    }

}
JAVA,

            "pergunta" =>
                "O que acontece ao tentar acessar pessoa.nome diretamente?",

            "opcoes" => [

                [
                    "texto" =>
                        "O código não compila porque nome é private",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "O nome recebe Ana normalmente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_acesso"
                ],

                [
                    "texto" =>
                        "O atributo deixa de existir",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_existe"
                ],

                [
                    "texto" =>
                        "Não sei o que private significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O ACESSO É PERMITIDO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_acesso" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Com private não dá pra chegar de fora e mexer direto. O atributo continua dentro da Pessoa, mas o Main não pode acessar pessoa.nome desse jeito.",

            "natanael" =>
                "A palavra private limita o acesso ao interior da própria classe. O atributo nome continua existindo, mas outra classe, como Main, não pode consultá-lo ou modificá-lo diretamente por meio de pessoa.nome.",

            "pergunta" =>
                "O código de Main está dentro ou fora da classe Pessoa?",

            "opcoes" => [

                [
                    "texto" =>
                        "Fora da classe Pessoa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Dentro da classe Pessoa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_acesso"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O ATRIBUTO SOME
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_existe" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O atributo não some kkkkk. Ele só fica protegido. A própria classe Pessoa ainda consegue usar nome normalmente.",

            "natanael" =>
                "private não remove o atributo. Ele apenas controla sua visibilidade. Os métodos e construtores da própria classe Pessoa continuam podendo acessar nome.",

            "pergunta" =>
                "O private apaga o atributo ou restringe seu acesso?",

            "opcoes" => [

                [
                    "texto" =>
                        "Restringe seu acesso",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Apaga o atributo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_existe"
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
                "Pensa numa gaveta com chave. A informação continua lá dentro, mas ninguém de fora abre diretamente. Precisa usar uma forma permitida pela própria classe.",

            "natanael" =>
                "Imagine que o objeto possui uma área interna protegida. Os dados continuam existindo, mas o acesso externo precisa passar por caminhos definidos pela própria classe. Isso ajuda a impedir alterações inadequadas.",

            "pergunta" =>
                "Um atributo private pode ser acessado diretamente por qualquer outra classe?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Sim",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_acesso"
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
                "É isso. private protege o atributo contra acesso direto vindo de fora da classe.",

            "natanael" =>
                "Exatamente. O atributo permanece disponível internamente, mas o acesso externo direto é bloqueado. Essa proteção faz parte do encapsulamento.",

            "pergunta" =>
                "Então o que acontece com pessoa.nome = \"Ana\"?",

            "opcoes" => [

                [
                    "texto" =>
                        "Gera erro de compilação",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Altera normalmente o atributo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_acesso"
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
                "Consultando de forma permitida...",

            "mensagem" =>
                "Agora a classe possui um método público que devolve o valor protegido.",

            "codigo" => <<<'JAVA'
class Pessoa {

    private String nome;


    Pessoa(String nome) {

        this.nome = nome;

    }


    public String getNome() {

        return nome;

    }

}

public class Main {

    public static void main(String[] args) {

        Pessoa pessoa = new Pessoa("Ana");

        System.out.println(
            pessoa.getNome()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "getNome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_nome_metodo"
                ],

                [
                    "texto" =>
                        "Nada, porque nome é private",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_private"
                ],

                [
                    "texto" =>
                        "O método altera o nome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_altera"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE MOSTRA O NOME DO MÉTODO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_nome_metodo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "getNome é o nome do método, mas ele retorna o valor do atributo. Como nome guarda Ana, esse é o texto que chega no println.",

            "natanael" =>
                "A chamada pessoa.getNome() executa o método. Dentro dele, return nome devolve o conteúdo atual do atributo. O println recebe esse valor e não o nome do método.",

            "pergunta" =>
                "Qual valor está guardado no atributo nome?",

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
                        "getNome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_nome_metodo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE PRIVATE BLOQUEIA A PRÓPRIA CLASSE
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_private" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "private bloqueia acesso direto vindo de fora. Mas getNome está dentro da própria classe Pessoa, então ele consegue usar nome.",

            "natanael" =>
                "A restrição não impede a própria classe de utilizar seus atributos. Como getNome foi declarado dentro de Pessoa, ele pode acessar nome e devolver seu valor para outras partes do programa.",

            "pergunta" =>
                "getNome está dentro da classe Pessoa?",

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
                        "pergunta_2_erro_private"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O GETTER ALTERA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_altera" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Esse método só faz return nome. Ele consulta e devolve. Não existe nenhuma atribuição mudando o atributo.",

            "natanael" =>
                "O método getNome é um método de leitura. Ele possui apenas return nome, portanto devolve a informação armazenada sem modificar o estado do objeto.",

            "pergunta" =>
                "Existe algum sinal de = alterando nome dentro de getNome?",

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
                        "pergunta_2_erro_altera"
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
                "get vem de pegar. getNome pega e devolve o nome protegido. Nesse objeto, o nome é Ana.",

            "natanael" =>
                "O getter oferece um caminho público e controlado para consultar a informação privada. Ele não expõe o atributo diretamente, mas devolve seu valor quando solicitado.",

            "pergunta" =>
                "O que pessoa.getNome() devolve?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Nenhum valor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_private"
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
                "Alterando com controle...",

            "mensagem" =>
                "Agora a classe possui um setter que verifica o valor antes de alterar o atributo.",

            "codigo" => <<<'JAVA'
class Pessoa {

    private int idade;


    public void setIdade(int idade) {

        if (idade >= 0) {

            this.idade = idade;

        }

    }


    public int getIdade() {

        return idade;

    }

}

public class Main {

    public static void main(String[] args) {

        Pessoa pessoa = new Pessoa();

        pessoa.setIdade(17);

        System.out.println(
            pessoa.getIdade()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual número aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "17",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "0, porque idade é private",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_private"
                ],

                [
                    "texto" =>
                        "-17",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_valor"
                ],

                [
                    "texto" =>
                        "O setter só consulta a idade",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_consulta"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE PRIVATE IMPEDE O SETTER
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_private" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "setIdade está dentro da classe Pessoa. Então ele pode alterar o atributo private. E como 17 passa na validação, o valor é salvo.",

            "natanael" =>
                "O setter pertence à própria classe e possui acesso ao atributo privado. Além disso, o valor 17 satisfaz idade >= 0. Por isso this.idade recebe 17.",

            "pergunta" =>
                "17 é maior ou igual a zero?",

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
                        "pergunta_3_erro_private"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU O VALOR
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_valor" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O valor enviado foi 17 positivo. O código não transforma ele em negativo. Só verifica se ele é válido.",

            "natanael" =>
                "A chamada pessoa.setIdade(17) envia o número positivo 17. O método apenas verifica se esse valor é maior ou igual a zero e, sendo válido, realiza a atribuição.",

            "pergunta" =>
                "Qual valor foi enviado para setIdade?",

            "opcoes" => [

                [
                    "texto" =>
                        "17",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "-17",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_valor"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU SETTER COM GETTER
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_consulta" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Quem consulta é getIdade. O setIdade recebe um valor e pode colocar ele no atributo. set é pra definir ou alterar.",

            "natanael" =>
                "Os dois métodos possuem papéis diferentes. O getter devolve uma informação. Já o setter recebe um valor e pode utilizá-lo para modificar o estado do objeto.",

            "pergunta" =>
                "Qual método possui a atribuição this.idade = idade?",

            "opcoes" => [

                [
                    "texto" =>
                        "setIdade",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "getIdade",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_consulta"
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
                "O setter recebe 17, testa se é válido e salva. Depois o getter pega o valor que ficou guardado.",

            "natanael" =>
                "O fluxo é: setIdade recebe 17; a validação confirma que não é negativo; this.idade recebe o valor; getIdade devolve o estado atualizado.",

            "pergunta" =>
                "Qual valor getIdade devolve depois disso?",

            "opcoes" => [

                [
                    "texto" =>
                        "17",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_private"
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
                "Protegendo o objeto de valores inválidos...",

            "mensagem" =>
                "Agora observa o que acontece quando tentamos colocar um preço negativo.",

            "codigo" => <<<'JAVA'
class Produto {

    private double preco;


    public void setPreco(double preco) {

        if (preco > 0) {

            this.preco = preco;

        }

    }


    public double getPreco() {

        return preco;

    }

}

public class Main {

    public static void main(String[] args) {

        Produto produto = new Produto();

        produto.setPreco(50.0);

        produto.setPreco(-10.0);

        System.out.println(
            produto.getPreco()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual valor aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "50.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "-10.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_negativo"
                ],

                [
                    "texto" =>
                        "0.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_zero"
                ],

                [
                    "texto" =>
                        "60.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_soma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE O NEGATIVO É ACEITO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_negativo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O setter não aceita qualquer coisa. Quando chega -10, ele testa -10 > 0. Dá false. Então não altera o preço que já era 50.",

            "natanael" =>
                "O método possui uma regra de validação. A atribuição só acontece quando preco > 0 é verdadeira. Como -10 não satisfaz essa condição, a segunda chamada não modifica o atributo.",

            "pergunta" =>
                "-10 é maior que zero?",

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
                        "pergunta_4_erro_negativo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE VOLTA PARA ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_zero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O preço começou em 0, mas a primeira chamada colocou 50. A segunda foi rejeitada, então o 50 continua lá.",

            "natanael" =>
                "O valor inicial do atributo realmente seria 0.0. Porém setPreco(50.0) é aceito e altera o estado. Quando o valor negativo é rejeitado, o atributo não retorna ao valor inicial.",

            "pergunta" =>
                "Qual valor foi aceito na primeira chamada?",

            "opcoes" => [

                [
                    "texto" =>
                        "50.0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "0.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_zero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE OS VALORES SÃO SOMADOS
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_soma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não tem soma nenhuma aí kkkkk. O setter tenta substituir o preço. E a segunda tentativa nem passa na validação.",

            "natanael" =>
                "O método não possui nenhuma operação de adição. Sua função seria substituir o atributo pelo valor recebido, mas apenas quando esse valor fosse positivo.",

            "pergunta" =>
                "Existe algum operador + dentro de setPreco?",

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
                        "pergunta_4_erro_soma"
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
                "Faz o caminho: preço começa 0. Envia 50, passa na regra e salva. Envia -10, falha na regra e ignora. Continua 50.",

            "natanael" =>
                "A primeira chamada recebe 50.0 e satisfaz a condição, atualizando o atributo. A segunda recebe -10.0, mas a condição resulta em false. Como nenhuma nova atribuição acontece, o valor anterior permanece.",

            "pergunta" =>
                "Qual é o preço final protegido pelo objeto?",

            "opcoes" => [

                [
                    "texto" =>
                        "50.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "-10.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_negativo"
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
                "Encapsulamento desbloqueado!",

            "robert" =>
                "Boa po kkkkk. Encapsular é proteger o que tá dentro do objeto. private bloqueia o acesso direto, getter deixa consultar e setter permite alterar com regras. Assim ninguém chega do nada e coloca idade negativa, preço impossível ou saldo errado. Agora que as classes estão mais organizadas, dá pra aprender como uma classe pode aproveitar características de outra.",

            "natanael" =>
                "Fechamos o conceito de encapsulamento. Os atributos private ficam protegidos contra acesso externo direto. Getters oferecem métodos controlados de leitura, enquanto setters podem permitir alterações com validações e regras. Isso ajuda a preservar um estado válido e reduz alterações indevidas nos objetos. Encapsular não significa apenas esconder dados, mas definir caminhos seguros para utilizá-los. Na próxima aula vamos estudar herança e entender como uma classe pode reutilizar características e comportamentos de outra.",

            "mensagem" =>
                "Tu concluiu a décima nona Aula Conversada."

        ]

    ]

];