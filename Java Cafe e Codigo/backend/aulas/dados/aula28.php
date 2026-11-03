<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 28,

    "titulo" =>
        "Informações entrando e resultados saindo",

    "assunto" =>
        "Parâmetros e valores de retorno",

    "descricao" =>
        "Entenda como enviar informações para métodos por meio de parâmetros e como receber resultados utilizando return.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        29,


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
                "Enviando uma informação para o método...",

            "mensagem" =>
                "O método apresentar precisa receber um nome para conseguir montar sua mensagem.",

            "codigo" => <<<'JAVA'
public class Main {

    static void apresentar(
        String nome
    ) {

        System.out.println(
            "Olá, " + nome
        );

    }


    public static void main(String[] args) {

        apresentar("Ana");

    }

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Olá, Ana",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Olá, nome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_literal"
                ],

                [
                    "texto" =>
                        "Olá, String",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
                ],

                [
                    "texto" =>
                        "Nenhuma mensagem aparece porque nome não possui valor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_sem_valor"
                ],

                [
                    "texto" =>
                        "Não sei como o valor chega ao método",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE MOSTRA O NOME DO PARÂMETRO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_literal" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "nome não está entre aspas no println. Então o Java usa o valor que chegou no parâmetro. Nesse caso, chegou Ana.",

            "natanael" =>
                "O trecho nome representa o parâmetro do método. Como ele não está entre aspas, o Java utiliza o conteúdo recebido na chamada. O valor enviado foi a String Ana.",

            "pergunta" =>
                "Qual valor foi enviado em apresentar(\"Ana\")?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "nome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_literal"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU TIPO COM VALOR
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "String é o tipo da informação. O valor de verdade enviado pro método foi Ana. É tipo dizer que uma caixa aceita texto e depois colocar Ana dentro dela.",

            "natanael" =>
                "String informa qual tipo de dado o parâmetro pode receber. Ela não é o conteúdo apresentado. Durante a chamada, a String Ana é enviada e armazenada temporariamente no parâmetro nome.",

            "pergunta" =>
                "String representa o tipo ou o conteúdo recebido?",

            "opcoes" => [

                [
                    "texto" =>
                        "O tipo",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "O conteúdo Ana",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O PARÂMETRO NÃO RECEBE VALOR
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_sem_valor" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O parâmetro recebe valor na hora da chamada. apresentar(\"Ana\") faz o nome do método receber Ana.",

            "natanael" =>
                "O parâmetro não precisa possuir um valor antes da chamada. Quando apresentar é executado, o argumento Ana é enviado e passa a ser o valor utilizado pela variável nome dentro do método.",

            "pergunta" =>
                "Em qual momento nome recebe o valor Ana?",

            "opcoes" => [

                [
                    "texto" =>
                        "Quando apresentar(\"Ana\") é chamado",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Nunca recebe valor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_sem_valor"
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
                "Pensa num formulário. O método possui um espaço chamado nome. Quando tu chama apresentar(\"Ana\"), tu preenche esse espaço com Ana.",

            "natanael" =>
                "Imagine que o método possui um campo vazio chamado nome. A chamada fornece a informação que preencherá esse campo durante aquela execução específica.",

            "pergunta" =>
                "Dentro do método, quanto vale o parâmetro nome?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Nenhum valor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_sem_valor"
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
                "A String Ana é o argumento enviado. String nome é o parâmetro que recebe esse valor. Depois o println usa Ana na mensagem.",

            "natanael" =>
                "A chamada fornece o argumento Ana. O método recebe esse argumento no parâmetro nome e utiliza seu conteúdo para montar a saudação.",

            "pergunta" =>
                "Qual mensagem completa será formada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Olá, Ana",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Olá, nome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_literal"
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
                "Mais de um parâmetro...",

            "mensagem" =>
                "Um método pode receber várias informações. Cada argumento é entregue ao parâmetro correspondente.",

            "codigo" => <<<'JAVA'
public class Main {

    static void mostrarPerfil(
        String nome,
        int nivel
    ) {

        System.out.println(
            nome + " está no nível " + nivel
        );

    }


    public static void main(String[] args) {

        mostrarPerfil(
            "Carlos",
            4
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Carlos está no nível 4",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "4 está no nível Carlos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_ordem"
                ],

                [
                    "texto" =>
                        "nome está no nível nivel",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_literal"
                ],

                [
                    "texto" =>
                        "O método recebe apenas Carlos e ignora o número 4",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_segundo"
                ],

                [
                    "texto" =>
                        "Não sei como os valores são distribuídos",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUNDIU A ORDEM
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_ordem" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Os valores entram na mesma ordem dos parâmetros. Carlos vai pro primeiro, que é nome. O 4 vai pro segundo, que é nivel.",

            "natanael" =>
                "A associação acontece pela posição. O primeiro argumento corresponde ao primeiro parâmetro e o segundo argumento corresponde ao segundo parâmetro. Além disso, os tipos precisam ser compatíveis.",

            "codigo" => <<<'JAVA'
mostrarPerfil("Carlos", 4);

// nome recebe "Carlos"
// nivel recebe 4
JAVA,

            "pergunta" =>
                "Qual parâmetro recebe o valor 4?",

            "opcoes" => [

                [
                    "texto" =>
                        "nivel",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "nome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_ordem"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE MOSTRA OS NOMES DOS PARÂMETROS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_literal" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "nome e nivel são variáveis do método. Como não estão entre aspas, o Java pega os valores que chegaram: Carlos e 4.",

            "natanael" =>
                "Os identificadores nome e nivel representam parâmetros. Durante a execução, eles possuem os valores enviados na chamada. Apenas os trechos entre aspas são mostrados literalmente.",

            "pergunta" =>
                "Qual valor fica guardado no parâmetro nome?",

            "opcoes" => [

                [
                    "texto" =>
                        "Carlos",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "nome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_literal"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O SEGUNDO ARGUMENTO É IGNORADO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_segundo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método declarou dois parâmetros, então recebe os dois valores. O 4 entra em nivel e é usado no println.",

            "natanael" =>
                "A chamada fornece a quantidade exata de argumentos exigida. O segundo argumento é compatível com o parâmetro int nivel e participa da mensagem apresentada.",

            "pergunta" =>
                "Quantos parâmetros mostrarPerfil declarou?",

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
                        "Um",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_segundo"
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
                "Pensa em duas caixas em fila. A primeira aceita texto e se chama nome. A segunda aceita inteiro e se chama nivel. Carlos entra na primeira e 4 entra na segunda.",

            "natanael" =>
                "O método estabelece duas posições de entrada. A primeira espera uma String e a segunda espera um int. A chamada entrega os valores seguindo exatamente essa organização.",

            "pergunta" =>
                "Qual combinação está correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "nome = Carlos | nivel = 4",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "nome = 4 | nivel = Carlos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_ordem"
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
                "Primeiro argumento vai pro primeiro parâmetro. Segundo argumento vai pro segundo. Carlos vira nome e 4 vira nivel.",

            "natanael" =>
                "A ordem e os tipos determinam a correspondência. Depois de receber os argumentos, o método utiliza os dois parâmetros para formar uma única mensagem.",

            "pergunta" =>
                "Qual frase será apresentada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Carlos está no nível 4",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "4 está no nível Carlos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_ordem"
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
                "Recebendo um resultado de volta...",

            "mensagem" =>
                "Agora o método não apenas executa uma ação. Ele calcula e devolve um valor para quem realizou a chamada.",

            "codigo" => <<<'JAVA'
public class Main {

    static int somar(
        int numero1,
        int numero2
    ) {

        int resultado =
            numero1 + numero2;

        return resultado;

    }


    public static void main(String[] args) {

        int total =
            somar(7, 5);

        System.out.println(
            total * 2
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual número aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "24",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "12",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_multiplicacao"
                ],

                [
                    "texto" =>
                        "14",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_primeiro"
                ],

                [
                    "texto" =>
                        "O método mostra 12 dentro dele e total fica vazio",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_return"
                ],

                [
                    "texto" =>
                        "Não sei o que return resultado faz",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ESQUECEU A MULTIPLICAÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_multiplicacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "12 é o valor devolvido por somar. Só que depois o main faz total * 2. Então ainda falta multiplicar.",

            "natanael" =>
                "A soma produz 12 e esse resultado é armazenado em total. Entretanto o println não apresenta total diretamente. Ele calcula total multiplicado por dois.",

            "pergunta" =>
                "Quanto resulta 12 * 2?",

            "opcoes" => [

                [
                    "texto" =>
                        "24",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "12",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_multiplicacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — USOU SOMENTE O PRIMEIRO NÚMERO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_primeiro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método não usa só o 7. Ele soma numero1 com numero2. Então primeiro faz 7 + 5 e chega em 12.",

            "natanael" =>
                "Os dois argumentos são recebidos pelos parâmetros. A expressão numero1 + numero2 utiliza os valores 7 e 5, produzindo 12 antes de qualquer multiplicação.",

            "pergunta" =>
                "Qual é o resultado de somar(7, 5)?",

            "opcoes" => [

                [
                    "texto" =>
                        "12",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "7",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_primeiro"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU RETURN COM PRINTLN
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_return" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "return não mostra nada sozinho. Ele entrega o resultado de volta. Quem recebe é a variável total.",

            "natanael" =>
                "O comando return encerra o método e envia um valor para o local da chamada. Nesse caso, o inteiro 12 é devolvido e armazenado na variável total. Nenhuma saída ocorre dentro de somar.",

            "pergunta" =>
                "Qual variável recebe o valor devolvido por somar?",

            "opcoes" => [

                [
                    "texto" =>
                        "total",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "resultado do método continua preso lá dentro",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_return"
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
                "Pensa que o método é uma calculadora. Tu entrega 7 e 5. Ela calcula 12 e o return entrega esse 12 de volta pro main.",

            "natanael" =>
                "O método recebe dois valores, realiza um processamento e devolve sua resposta. A chamada aguarda esse retorno para preencher a variável total.",

            "pergunta" =>
                "Qual valor return resultado devolve?",

            "opcoes" => [

                [
                    "texto" =>
                        "12",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Nenhum valor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_return"
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
                "O caminho é: entra 7 e 5, o método soma e devolve 12. total recebe 12. Depois o println calcula 12 vezes 2.",

            "natanael" =>
                "Os argumentos alimentam os parâmetros, a soma produz o retorno e o retorno preenche a variável total. A multiplicação acontece somente depois desse processo.",

            "pergunta" =>
                "Qual valor final é mostrado?",

            "opcoes" => [

                [
                    "texto" =>
                        "24",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "12",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_multiplicacao"
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
                "Um retorno também pode responder verdadeiro ou falso...",

            "mensagem" =>
                "O método podeAvancar recebe uma pontuação e devolve um valor boolean utilizado diretamente pelo if.",

            "codigo" => <<<'JAVA'
public class Main {

    static boolean podeAvancar(
        int pontos
    ) {

        return pontos >= 70;

    }


    public static void main(String[] args) {

        int pontosDoAluno = 75;

        if (
            podeAvancar(pontosDoAluno)
        ) {

            System.out.println(
                "Próxima fase liberada"
            );

        } else {

            System.out.println(
                "Continue praticando"
            );

        }

    }

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Próxima fase liberada",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Continue praticando",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_condicao"
                ],

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_mostra_boolean"
                ],

                [
                    "texto" =>
                        "75",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_pontos"
                ],

                [
                    "texto" =>
                        "O método não pode ser utilizado dentro de um if",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_if"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE A CONDIÇÃO É FALSA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_condicao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Os pontos são 75. O método testa 75 >= 70, que é true. Então entra no bloco do if, não no else.",

            "natanael" =>
                "O parâmetro pontos recebe 75. A comparação verifica se esse valor é maior ou igual a 70. Como a expressão resulta em true, o primeiro caminho é selecionado.",

            "pergunta" =>
                "75 é maior ou igual a 70?",

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
                        "pergunta_4_erro_condicao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE O BOOLEAN É MOSTRADO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_mostra_boolean" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método realmente devolve true, mas não existe println mostrando esse true. O valor é usado pelo if pra escolher um caminho.",

            "natanael" =>
                "O retorno booleano participa da condição do if. Ele não é enviado diretamente ao console. Quando recebe true, o if executa a mensagem presente em seu bloco.",

            "pergunta" =>
                "O retorno true é mostrado ou utilizado como condição?",

            "opcoes" => [

                [
                    "texto" =>
                        "Utilizado como condição",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Mostrado diretamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_mostra_boolean"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE MOSTRA A PONTUAÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_pontos" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "75 é enviado pro método, mas nenhum println mostra os pontos. O número só é usado pra calcular se o resultado é true ou false.",

            "natanael" =>
                "O valor 75 participa da comparação interna. Depois da avaliação, o resultado devolvido é boolean. O código de saída apresenta somente uma das mensagens do if ou do else.",

            "pergunta" =>
                "Qual tipo o método podeAvancar devolve?",

            "opcoes" => [

                [
                    "texto" =>
                        "boolean",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "int",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_pontos"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE MÉTODO NÃO PODE SER USADO NO IF
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_if" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Pode sim. O if precisa de true ou false. Como podeAvancar devolve boolean, dá pra usar a chamada direto na condição.",

            "natanael" =>
                "Uma condição aceita qualquer expressão cujo resultado seja booleano. Como o método foi declarado com retorno boolean, sua chamada pode ocupar diretamente esse espaço.",

            "pergunta" =>
                "Qual tipo de resultado o if precisa receber?",

            "opcoes" => [

                [
                    "texto" =>
                        "true ou false",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Somente texto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_if"
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
                "podeAvancar recebe 75, testa 75 >= 70 e devolve true. O if recebe esse true e libera a próxima fase.",

            "natanael" =>
                "O argumento alimenta o parâmetro, a comparação produz um boolean e o return envia esse resultado para a condição. Como o retorno é verdadeiro, o primeiro bloco é executado.",

            "pergunta" =>
                "Qual mensagem o bloco verdadeiro apresenta?",

            "opcoes" => [

                [
                    "texto" =>
                        "Próxima fase liberada",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Continue praticando",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_condicao"
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
                "Parâmetros e retornos dominados!",

            "robert" =>
                "Boa po kkkkk. Agora ficou claro como a informação viaja entre os métodos. Os argumentos são os valores enviados na chamada. Os parâmetros recebem esses valores dentro do método. Se o método for void, ele executa uma ação e não devolve resultado. Se tiver um tipo como int, String ou boolean, o return precisa devolver um valor compatível. Assim dá pra criar métodos que calculam, validam e entregam respostas pro restante do programa.",

            "natanael" =>
                "Concluímos o funcionamento dos parâmetros e valores de retorno. Os argumentos representam os valores informados em uma chamada, enquanto os parâmetros são as variáveis que recebem esses dados dentro do método. A ordem e os tipos precisam ser compatíveis. Métodos void executam ações sem devolver resultados, enquanto métodos com tipo de retorno utilizam return para enviar uma resposta ao local da chamada. Esses resultados podem ser armazenados, utilizados em cálculos ou aplicados diretamente em condições. Na próxima aula vamos utilizar métodos e objetos juntos para organizar responsabilidades em classes diferentes.",

            "mensagem" =>
                "Tu concluiu a vigésima oitava Aula Conversada."

        ]

    ]

];