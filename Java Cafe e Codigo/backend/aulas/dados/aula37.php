<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 37,

    "titulo" =>
        "Mesmo nome, diferentes possibilidades",

    "assunto" =>
        "Sobrecarga de métodos",

    "descricao" =>
        "Aprenda a criar métodos e construtores com o mesmo nome, mas com diferentes quantidades ou tipos de parâmetros.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        38,


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
                "Mesmo nome com quantidades diferentes...",

            "mensagem" =>
                "Os dois métodos se chamam somar, mas um recebe dois números e o outro recebe três.",

            "codigo" => <<<'JAVA'
class Calculadora {

    static int somar(
        int numero1,
        int numero2
    ) {

        return numero1 + numero2;

    }


    static int somar(
        int numero1,
        int numero2,
        int numero3
    ) {

        return numero1 + numero2 + numero3;

    }

}

public class Main {

    public static void main(String[] args) {

        int resultado1 =
            Calculadora.somar(
                3,
                4
            );

        int resultado2 =
            Calculadora.somar(
                3,
                4,
                5
            );

        System.out.println(
            resultado1
        );

        System.out.println(
            resultado2
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "7 e depois 12",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "7 duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_dois"
                ],

                [
                    "texto" =>
                        "12 duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tres"
                ],

                [
                    "texto" =>
                        "O código não compila porque existem dois métodos chamados somar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nome"
                ],

                [
                    "texto" =>
                        "Não sei qual método é escolhido",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — USOU O MÉTODO DE DOIS PARÂMETROS NAS DUAS CHAMADAS
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_dois" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Na primeira chamada entram dois números. Na segunda entram três. O Java escolhe a versão que combina com a quantidade de argumentos.",

            "natanael" =>
                "A primeira chamada corresponde ao método com dois parâmetros. A segunda corresponde ao método com três parâmetros, pois apresenta três argumentos.",

            "pergunta" =>
                "Quantos números são enviados na segunda chamada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Três números",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Dois números",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_dois"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — USOU O MÉTODO DE TRÊS PARÂMETROS NAS DUAS CHAMADAS
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_tres" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A primeira chamada só envia 3 e 4. Como não existe terceiro argumento, o Java usa a versão com dois parâmetros.",

            "natanael" =>
                "A escolha depende da estrutura da chamada. Como resultado1 utiliza somente dois argumentos, o método com três parâmetros não corresponde.",

            "pergunta" =>
                "Quanto resulta somar 3 com 4?",

            "opcoes" => [

                [
                    "texto" =>
                        "7",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "12",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tres"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE NOMES REPETIDOS NÃO SÃO PERMITIDOS
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_nome" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Pode ter o mesmo nome, po. O que precisa mudar é a lista de parâmetros. Aqui um somar recebe dois números e o outro recebe três.",

            "natanael" =>
                "Esse recurso é justamente a sobrecarga. Métodos podem compartilhar o nome quando suas listas de parâmetros são diferentes.",

            "pergunta" =>
                "O que diferencia os dois métodos somar?",

            "opcoes" => [

                [
                    "texto" =>
                        "A quantidade de parâmetros",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Somente o nome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nome"
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
                "Pensa numa lancheria. Tu pode pedir um combo com dois itens ou outro com três. O nome do pedido pode ser igual, mas a quantidade informa qual versão deve ser preparada.",

            "natanael" =>
                "O compilador observa a chamada realizada. Ele compara a quantidade e os tipos dos argumentos com os métodos disponíveis e seleciona a versão compatível.",

            "pergunta" =>
                "Qual método combina com Calculadora.somar(3, 4, 5)?",

            "opcoes" => [

                [
                    "texto" =>
                        "O método com três parâmetros",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "O método com dois parâmetros",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_dois"
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
                "A chamada com dois valores usa o primeiro método e calcula 3 + 4. A chamada com três valores usa o segundo e calcula 3 + 4 + 5.",

            "natanael" =>
                "A quantidade de argumentos determina a versão escolhida. Os resultados são sete para a primeira chamada e doze para a segunda.",

            "pergunta" =>
                "Qual sequência completa será apresentada?",

            "opcoes" => [

                [
                    "texto" =>
                        "7 → 12",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "12 → 7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tres"
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
                "Mesmo nome com tipos diferentes...",

            "mensagem" =>
                "A sobrecarga também pode utilizar tipos diferentes na lista de parâmetros.",

            "codigo" => <<<'JAVA'
class Exibidor {

    static void mostrar(
        int valor
    ) {

        System.out.println(
            "Número: " + valor
        );

    }


    static void mostrar(
        String valor
    ) {

        System.out.println(
            "Texto: " + valor
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Exibidor.mostrar(10);

        Exibidor.mostrar("Java");

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Número: 10 e depois Texto: Java",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Texto: 10 e depois Texto: Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_primeiro"
                ],

                [
                    "texto" =>
                        "Número: 10 e depois Número: Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_segundo"
                ],

                [
                    "texto" =>
                        "Somente Texto: Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_substitui"
                ],

                [
                    "texto" =>
                        "Não sei como o Java escolhe pelo tipo",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE 10 USA A VERSÃO STRING
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_primeiro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O argumento 10 foi escrito sem aspas, então ele é int. O Java escolhe mostrar(int valor).",

            "natanael" =>
                "A primeira chamada fornece um valor inteiro. Por isso ela corresponde à versão cujo parâmetro foi declarado como int.",

            "pergunta" =>
                "Qual é o tipo do valor 10 sem aspas?",

            "opcoes" => [

                [
                    "texto" =>
                        "int",

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
                        "pergunta_2_erro_primeiro"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE JAVA USA A VERSÃO INT
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_segundo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "\"Java\" está entre aspas. Isso faz o valor ser uma String, então entra na versão mostrar(String valor).",

            "natanael" =>
                "A presença das aspas cria um texto. O método com parâmetro int não consegue receber diretamente esse argumento.",

            "pergunta" =>
                "Qual é o tipo de \"Java\"?",

            "opcoes" => [

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "int",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_segundo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE UMA SOBRECARGA SUBSTITUI A OUTRA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_substitui" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Uma versão não apaga a outra. As duas ficam disponíveis. Cada chamada escolhe aquela que combina com o argumento.",

            "natanael" =>
                "Os métodos coexistem dentro da classe. A primeira chamada utiliza uma versão e a segunda chamada utiliza a outra.",

            "pergunta" =>
                "Quantas chamadas de Exibidor.mostrar aparecem no main?",

            "opcoes" => [

                [
                    "texto" =>
                        "Duas chamadas",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Uma chamada",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_substitui"
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
                "O Java olha o que foi colocado dentro dos parênteses. Número sem aspas chama a versão int. Texto entre aspas chama a versão String.",

            "natanael" =>
                "A resolução da sobrecarga considera os tipos dos argumentos. Cada chamada é ligada ao método cuja lista de parâmetros é compatível.",

            "pergunta" =>
                "Qual versão recebe o argumento \"Java\"?",

            "opcoes" => [

                [
                    "texto" =>
                        "mostrar(String valor)",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "mostrar(int valor)",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_segundo"
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
                "10 é int e usa a versão que escreve Número. Java é String e usa a versão que escreve Texto.",

            "natanael" =>
                "Os tipos dos argumentos correspondem a métodos diferentes. Por isso cada chamada produz uma mensagem adequada ao valor recebido.",

            "pergunta" =>
                "Qual sequência completa será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Número: 10 → Texto: Java",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Texto: 10 → Número: Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_primeiro"
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
                "O retorno sozinho não diferencia métodos...",

            "mensagem" =>
                "Observe que os dois métodos possuem o mesmo nome e exatamente o mesmo parâmetro.",

            "codigo" => <<<'JAVA'
class Conversor {

    static int converter(
        String texto
    ) {

        return Integer.parseInt(
            texto
        );

    }


    static double converter(
        String texto
    ) {

        return Double.parseDouble(
            texto
        );

    }

}

public class Main {

    public static void main(String[] args) {

        int numero =
            Conversor.converter("10");

        System.out.println(
            numero
        );

    }

}
JAVA,

            "pergunta" =>
                "O que acontece com esse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não compila porque o tipo de retorno sozinho não diferencia uma sobrecarga",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Compila e mostra 10",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_int"
                ],

                [
                    "texto" =>
                        "Compila e mostra 10.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_double"
                ],

                [
                    "texto" =>
                        "O Java executa os dois métodos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_dois"
                ],

                [
                    "texto" =>
                        "Não sei por que os retornos não bastam",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O INT DEFINE A VERSÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_int" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O Java escolhe a sobrecarga olhando os argumentos da chamada, não a variável que vai receber o resultado. As duas versões recebem uma String igual.",

            "natanael" =>
                "A resolução do método acontece com base no nome e na lista de parâmetros. O tipo da variável numero não é utilizado para diferenciar as declarações.",

            "pergunta" =>
                "Qual parâmetro aparece nas duas versões?",

            "opcoes" => [

                [
                    "texto" =>
                        "String texto",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Parâmetros diferentes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_int"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O DOUBLE É ESCOLHIDO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_double" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Nem a versão int nem a double pode ser escolhida, porque elas possuem a mesma assinatura. O problema aparece antes de executar.",

            "natanael" =>
                "As declarações entram em conflito durante a compilação. Como os parâmetros são idênticos, o compilador considera que o método foi declarado duas vezes.",

            "pergunta" =>
                "O programa chega a ser executado?",

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
                        "pergunta_3_erro_double"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE OS DOIS MÉTODOS SÃO EXECUTADOS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_dois" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Uma chamada nunca executa todas as sobrecargas. Ela precisa escolher uma versão específica. Aqui nem consegue escolher porque os parâmetros são iguais.",

            "natanael" =>
                "Sobrecarga não significa executar vários métodos ao mesmo tempo. Ela oferece alternativas, mas cada chamada precisa corresponder a uma única assinatura.",

            "pergunta" =>
                "As duas versões possuem listas de parâmetros diferentes?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não, são iguais",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Sim, são diferentes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_dois"
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
                "Pensa que tu chama converter passando uma String. As duas versões aceitam exatamente uma String. O Java não olha o retorno pra decidir, então fica sem uma diferença válida.",

            "natanael" =>
                "Para distinguir os métodos, o compilador precisa encontrar alguma diferença nos parâmetros. Alterar apenas o tipo devolvido não modifica a forma da chamada.",

            "pergunta" =>
                "O que precisa mudar para criar uma sobrecarga válida?",

            "opcoes" => [

                [
                    "texto" =>
                        "A lista de parâmetros",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Somente o tipo de retorno",

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
                "Os dois métodos se chamam converter e recebem String texto. Trocar int por double no retorno não muda a chamada. Por isso ocorre erro de compilação.",

            "natanael" =>
                "A assinatura utilizada para diferenciar a sobrecarga considera o nome e os parâmetros. Como esses elementos são idênticos, as declarações entram em conflito.",

            "pergunta" =>
                "Qual afirmação está correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "O retorno sozinho não cria uma sobrecarga válida",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Qualquer mudança no retorno cria uma sobrecarga",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_int"
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
                "Sobrecarregando construtores...",

            "mensagem" =>
                "Construtores também podem possuir o mesmo nome e receber diferentes listas de parâmetros.",

            "codigo" => <<<'JAVA'
class Usuario {

    private String nome;

    private int nivel;


    Usuario(
        String nome
    ) {

        this(
            nome,
            1
        );

    }


    Usuario(
        String nome,
        int nivel
    ) {

        this.nome = nome;

        this.nivel = nivel;

    }


    void exibir() {

        System.out.println(
            nome +
            " - nível " +
            nivel
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Usuario usuario1 =
            new Usuario("Ana");

        Usuario usuario2 =
            new Usuario(
                "Carlos",
                3
            );

        usuario1.exibir();

        usuario2.exibir();

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana - nível 1 e depois Carlos - nível 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Ana - nível 0 e depois Carlos - nível 3",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_padrao"
                ],

                [
                    "texto" =>
                        "Ana - nível 1 e depois Carlos - nível 1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_segundo"
                ],

                [
                    "texto" =>
                        "O código não compila porque existem dois construtores Usuario",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_sobrecarga"
                ],

                [
                    "texto" =>
                        "São criados três objetos Usuario",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_objetos"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE ANA FICA NO NÍVEL ZERO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_padrao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O construtor com um parâmetro chama this(nome, 1). Então ele manda o nome recebido e usa nível 1 como valor padrão.",

            "natanael" =>
                "A chamada this(nome, 1) direciona a construção para a outra versão do construtor. O segundo argumento define explicitamente o nível inicial como um.",

            "codigo" => <<<'JAVA'
this(
    nome,
    1
);
JAVA,

            "pergunta" =>
                "Qual nível é enviado pelo primeiro construtor?",

            "opcoes" => [

                [
                    "texto" =>
                        "1",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_padrao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE CARLOS USA O NÍVEL PADRÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_segundo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Carlos foi criado com dois argumentos: o nome e o nível 3. Então o Java usa direto o construtor com dois parâmetros.",

            "natanael" =>
                "A segunda criação corresponde à versão que recebe String e int. O valor três é armazenado no atributo nivel desse objeto.",

            "pergunta" =>
                "Qual nível foi enviado em new Usuario(\"Carlos\", 3)?",

            "opcoes" => [

                [
                    "texto" =>
                        "3",

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
                        "pergunta_4_erro_segundo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE CONSTRUTOR NÃO PODE SER SOBRECARREGADO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_sobrecarga" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Construtores já possuem o nome da classe, então a diferença precisa estar nos parâmetros. Um recebe só String e o outro recebe String mais int.",

            "natanael" =>
                "A sobrecarga também se aplica aos construtores. As duas versões são válidas porque possuem listas de parâmetros diferentes.",

            "pergunta" =>
                "O primeiro construtor recebe quantos parâmetros?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um parâmetro",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Dois parâmetros",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_sobrecarga"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE THIS CRIA OUTRO OBJETO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_objetos" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "this(nome, 1) não cria outro objeto. Ele só continua a construção do mesmo objeto usando o outro construtor.",

            "natanael" =>
                "A chamada entre construtores reutiliza a lógica da outra versão. Ela não representa uma expressão new e não cria uma instância adicional.",

            "pergunta" =>
                "Quantas expressões new Usuario aparecem no main?",

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
                        "Três",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_objetos"
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
                "Ana usa o construtor com um parâmetro, que define nível 1. Carlos usa o construtor com dois parâmetros e recebe nível 3.",

            "natanael" =>
                "A quantidade de argumentos seleciona o construtor. A primeira criação utiliza o valor padrão, enquanto a segunda fornece todos os valores explicitamente.",

            "pergunta" =>
                "Qual sequência completa será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana - nível 1 → Carlos - nível 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Ana - nível 0 → Carlos - nível 1",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_padrao"
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
                "Sobrecarga desbloqueada!",

            "robert" =>
                "Boa po kkkkk. Agora tu pode criar métodos com o mesmo nome sem deixar o código confuso. O Java escolhe a versão olhando a quantidade, os tipos e a ordem dos parâmetros. Só mudar o retorno não vale. Construtores também podem ser sobrecarregados, deixando o objeto ser criado de formas diferentes.",

            "natanael" =>
                "Concluímos o funcionamento da sobrecarga. Métodos podem compartilhar o mesmo nome quando apresentam listas de parâmetros diferentes em quantidade, tipo ou ordem. O compilador analisa os argumentos fornecidos e seleciona a versão compatível. Também vimos que alterar apenas o tipo de retorno não cria uma sobrecarga válida. Por fim, aplicamos o mesmo conceito aos construtores, permitindo diferentes formas de inicializar um objeto. Na próxima aula vamos estudar classes abstratas e entender como criar modelos que servem de base para outras classes.",

            "mensagem" =>
                "Tu concluiu a trigésima sétima Aula Conversada."

        ]

    ]

];