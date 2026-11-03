<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 35,

    "titulo" =>
        "Criando opções fixas e seguras",

    "assunto" =>
        "enum",

    "descricao" =>
        "Aprenda a representar conjuntos fixos de opções utilizando enum, comparações, switch e atributos de objetos.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        36,


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
                "Definindo um conjunto de opções...",

            "mensagem" =>
                "O enum Status define os únicos estados que serão utilizados nesse exemplo.",

            "codigo" => <<<'JAVA'
enum Status {

    PENDENTE,
    EM_ANDAMENTO,
    CONCLUIDA

}

public class Main {

    public static void main(String[] args) {

        Status status =
            Status.PENDENTE;

        System.out.println(
            status
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual valor aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "PENDENTE",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Status",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
                ],

                [
                    "texto" =>
                        "EM_ANDAMENTO",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_opcao"
                ],

                [
                    "texto" =>
                        "O número 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_numero"
                ],

                [
                    "texto" =>
                        "Não sei como enum funciona",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU O TIPO COM O VALOR
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Status é o tipo, po. O valor guardado na variável é Status.PENDENTE. Quando mostra a variável, aparece o nome da opção PENDENTE.",

            "natanael" =>
                "Status representa o tipo criado pelo enum. A variável status, porém, recebeu uma das constantes pertencentes a esse tipo: PENDENTE.",

            "pergunta" =>
                "Qual opção foi atribuída à variável status?",

            "opcoes" => [

                [
                    "texto" =>
                        "PENDENTE",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Status",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ESCOLHEU OUTRA OPÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_opcao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "EM_ANDAMENTO existe no enum, mas não foi a opção escolhida. A atribuição usa Status.PENDENTE.",

            "natanael" =>
                "O enum possui três constantes válidas, mas somente uma delas foi atribuída. Precisamos observar o valor presente depois do sinal de igual.",

            "codigo" => <<<'JAVA'
Status status =
    Status.PENDENTE;
JAVA,

            "pergunta" =>
                "Qual constante aparece depois do sinal de igual?",

            "opcoes" => [

                [
                    "texto" =>
                        "PENDENTE",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "EM_ANDAMENTO",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_opcao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE ENUM DEVOLVE NÚMERO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_numero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O enum até mantém uma ordem interna, mas mostrar a constante diretamente não imprime 0. O console apresenta o nome PENDENTE.",

            "natanael" =>
                "As constantes possuem uma posição interna, acessível por métodos específicos como ordinal. Entretanto o println da própria constante utiliza seu nome.",

            "pergunta" =>
                "O código chamou algum método como ordinal()?",

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
                        "pergunta_1_erro_numero"
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
                "Pensa num pedido que só pode estar pendente, em andamento ou concluído. O enum cria essa lista fechada. Depois a variável escolhe uma dessas opções.",

            "natanael" =>
                "Imagine um formulário com opções já definidas. Em vez de permitir qualquer texto, o enum limita o programa aos valores declarados dentro da estrutura.",

            "pergunta" =>
                "A variável recebeu qual estado inicial?",

            "opcoes" => [

                [
                    "texto" =>
                        "PENDENTE",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "CONCLUIDA",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_opcao"
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
                "Status é o tipo. PENDENTE é uma das opções permitidas. Como foi essa opção que entrou na variável, é ela que aparece.",

            "natanael" =>
                "A declaração cria uma variável do tipo Status e armazena nela a constante PENDENTE. A saída apresenta diretamente essa constante.",

            "pergunta" =>
                "Qual é a saída correta?",

            "opcoes" => [

                [
                    "texto" =>
                        "PENDENTE",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Status",

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
                "Comparando uma opção do enum...",

            "mensagem" =>
                "Valores de enum podem ser comparados diretamente com o operador de igualdade.",

            "codigo" => <<<'JAVA'
enum Status {

    PENDENTE,
    EM_ANDAMENTO,
    CONCLUIDA

}

public class Main {

    public static void main(String[] args) {

        Status status =
            Status.CONCLUIDA;

        if (
            status == Status.CONCLUIDA
        ) {

            System.out.println(
                "Atividade finalizada"
            );

        } else {

            System.out.println(
                "Atividade aberta"
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
                        "Atividade finalizada",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Atividade aberta",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_condicao"
                ],

                [
                    "texto" =>
                        "CONCLUIDA",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_saida"
                ],

                [
                    "texto" =>
                        "O código não funciona porque enum não pode ser comparado com ==",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_comparacao"
                ],

                [
                    "texto" =>
                        "Não sei interpretar a comparação",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE A CONDIÇÃO É FALSA
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_condicao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A variável recebeu exatamente Status.CONCLUIDA. Depois o if compara com a mesma opção. Então a condição é true.",

            "natanael" =>
                "Os dois lados da comparação representam a mesma constante do enum. Por isso a igualdade é satisfeita e o primeiro bloco é executado.",

            "pergunta" =>
                "status e Status.CONCLUIDA representam a mesma opção?",

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
                        "pergunta_2_erro_condicao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE MOSTRA O ENUM DIRETAMENTE
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_saida" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "CONCLUIDA é usada na condição, mas nenhum println mostra essa variável. O println dentro do if mostra Atividade finalizada.",

            "natanael" =>
                "A constante participa somente da decisão. A saída do programa é determinada pelo texto presente dentro do bloco selecionado.",

            "pergunta" =>
                "Qual mensagem está dentro do bloco if?",

            "opcoes" => [

                [
                    "texto" =>
                        "Atividade finalizada",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "CONCLUIDA",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_saida"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE == NÃO FUNCIONA COM ENUM
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_comparacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Com enum, comparar usando == é normal e seguro. Cada opção representa uma constante única.",

            "natanael" =>
                "As constantes de enum são instâncias únicas. Por isso o operador == pode ser utilizado para verificar se duas referências representam exatamente a mesma constante.",

            "pergunta" =>
                "O operador == pode comparar duas constantes do mesmo enum?",

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
                        "pergunta_2_erro_comparacao"
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
                "Lê assim: se o status for igual a CONCLUIDA, mostra que finalizou. E o status realmente está concluído.",

            "natanael" =>
                "A variável mantém uma das opções permitidas. O if verifica se essa opção corresponde ao estado concluído. Como corresponde, o caminho verdadeiro é selecionado.",

            "pergunta" =>
                "A condição resulta em true ou false?",

            "opcoes" => [

                [
                    "texto" =>
                        "true",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "false",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_condicao"
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
                "status vale CONCLUIDA. A comparação encontra a mesma constante e entra no if.",

            "natanael" =>
                "A igualdade entre as constantes produz true. Assim o bloco verdadeiro apresenta a mensagem de finalização.",

            "pergunta" =>
                "Qual mensagem será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Atividade finalizada",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Atividade aberta",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_condicao"
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
                "Guardando o estado dentro de um objeto...",

            "mensagem" =>
                "Um atributo também pode utilizar um enum como tipo, limitando os valores que podem ser armazenados.",

            "codigo" => <<<'JAVA'
enum StatusPedido {

    AGUARDANDO,
    ENVIADO,
    ENTREGUE

}

class Pedido {

    private StatusPedido status;


    Pedido() {

        status =
            StatusPedido.AGUARDANDO;

    }


    void enviar() {

        status =
            StatusPedido.ENVIADO;

    }


    StatusPedido getStatus() {

        return status;

    }

}

public class Main {

    public static void main(String[] args) {

        Pedido pedido =
            new Pedido();

        System.out.println(
            pedido.getStatus()
        );

        pedido.enviar();

        System.out.println(
            pedido.getStatus()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "AGUARDANDO e depois ENVIADO",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "ENVIADO duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_inicial"
                ],

                [
                    "texto" =>
                        "AGUARDANDO duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_metodo"
                ],

                [
                    "texto" =>
                        "ENTREGUE e depois ENVIADO",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_entregue"
                ],

                [
                    "texto" =>
                        "O atributo status deveria ser uma String",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE COMEÇA ENVIADO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_inicial" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O objeto não começa enviado. No construtor, status recebe AGUARDANDO. Só depois da chamada enviar ele muda.",

            "natanael" =>
                "A primeira configuração acontece durante a construção do objeto. O estado ENVIADO é atribuído somente em uma etapa posterior.",

            "pergunta" =>
                "Qual valor o construtor coloca no atributo status?",

            "opcoes" => [

                [
                    "texto" =>
                        "AGUARDANDO",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "ENVIADO",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_inicial"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE ENVIAR NÃO ALTERA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_metodo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Dentro de enviar existe outra atribuição. O método troca o estado do pedido para ENVIADO.",

            "natanael" =>
                "O método modifica diretamente o atributo interno. Após sua execução, o getter deixa de devolver AGUARDANDO e passa a devolver ENVIADO.",

            "codigo" => <<<'JAVA'
void enviar() {

    status =
        StatusPedido.ENVIADO;

}
JAVA,

            "pergunta" =>
                "Qual valor é atribuído dentro de enviar()?",

            "opcoes" => [

                [
                    "texto" =>
                        "ENVIADO",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "AGUARDANDO",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_metodo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ESCOLHEU ENTREGUE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_entregue" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "ENTREGUE é uma opção válida, mas nenhuma linha atribuiu ela ao pedido. O código só usa AGUARDANDO e ENVIADO.",

            "natanael" =>
                "A existência de uma constante no enum não significa que ela será utilizada automaticamente. Precisamos observar as atribuições realmente realizadas.",

            "pergunta" =>
                "Existe alguma atribuição com StatusPedido.ENTREGUE?",

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
                        "pergunta_3_erro_entregue"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE PRECISA SER STRING
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Usar String deixaria entrar qualquer texto, até escrito errado. Com StatusPedido, o atributo aceita só as opções do enum.",

            "natanael" =>
                "O enum cria um tipo próprio. Isso evita valores livres ou inconsistentes, pois o atributo somente pode receber constantes pertencentes a StatusPedido.",

            "pergunta" =>
                "O atributo pode receber qualquer texto como \"mandado\"?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não, somente opções de StatusPedido",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Sim, qualquer String é aceita",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_tipo"
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
                "O construtor deixa o pedido aguardando. O primeiro getter mostra esse estado. Depois enviar muda o atributo e o segundo getter mostra ENVIADO.",

            "natanael" =>
                "O objeto mantém um estado inicial definido pelo construtor. A chamada enviar modifica esse estado, e cada consulta apresenta o valor correspondente ao momento.",

            "pergunta" =>
                "Qual sequência completa será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "AGUARDANDO → ENVIADO",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "AGUARDANDO → AGUARDANDO",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_metodo"
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
                "Escolhendo um caminho com switch...",

            "mensagem" =>
                "O switch também pode comparar diretamente as constantes de um enum.",

            "codigo" => <<<'JAVA'
enum Prioridade {

    BAIXA,
    MEDIA,
    ALTA

}

public class Main {

    public static void main(String[] args) {

        Prioridade prioridade =
            Prioridade.ALTA;

        switch (prioridade) {

            case BAIXA:

                System.out.println(
                    "Pode aguardar"
                );

                break;

            case MEDIA:

                System.out.println(
                    "Resolver em breve"
                );

                break;

            case ALTA:

                System.out.println(
                    "Atendimento imediato"
                );

                break;

        }

    }

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Atendimento imediato",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Pode aguardar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_baixa"
                ],

                [
                    "texto" =>
                        "Resolver em breve",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_media"
                ],

                [
                    "texto" =>
                        "As três mensagens aparecem",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_todas"
                ],

                [
                    "texto" =>
                        "O código deveria usar case Prioridade.ALTA",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_case"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ESCOLHEU BAIXA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_baixa" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A variável não recebeu BAIXA. Ela recebeu Prioridade.ALTA, então o switch pula os primeiros casos.",

            "natanael" =>
                "O switch procura uma correspondência com o valor armazenado. Como a constante atual é ALTA, o bloco BAIXA não é selecionado.",

            "pergunta" =>
                "Qual valor está guardado em prioridade?",

            "opcoes" => [

                [
                    "texto" =>
                        "ALTA",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "BAIXA",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_baixa"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ESCOLHEU MÉDIA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_media" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "MEDIA seria outro caminho possível, mas o valor real é ALTA. O switch entra no case que combina exatamente.",

            "natanael" =>
                "Os casos representam alternativas diferentes. Apenas aquele igual à constante armazenada será executado.",

            "pergunta" =>
                "Qual case corresponde à constante ALTA?",

            "opcoes" => [

                [
                    "texto" =>
                        "case ALTA",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "case MEDIA",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_media"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE TODAS EXECUTAM
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_todas" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O switch encontra ALTA e executa esse case. Depois o break encerra o switch, então não aparecem outras mensagens.",

            "natanael" =>
                "A seleção começa no caso correspondente. O comando break impede que a execução avance para outros blocos depois da mensagem.",

            "pergunta" =>
                "Existe um break depois da mensagem Atendimento imediato?",

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
                        "pergunta_4_erro_todas"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE PRECISA REPETIR O TIPO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_case" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Dentro do switch de Prioridade, os cases podem usar BAIXA, MEDIA e ALTA direto. O Java já sabe qual enum está sendo analisado.",

            "natanael" =>
                "O tipo da expressão do switch já é conhecido. Por isso as constantes são informadas sem repetir o nome Prioridade em cada case.",

            "pergunta" =>
                "O switch está analisando uma variável de qual tipo?",

            "opcoes" => [

                [
                    "texto" =>
                        "Prioridade",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_case"
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
                "A prioridade vale ALTA. O switch encontra case ALTA, mostra Atendimento imediato e para no break.",

            "natanael" =>
                "A constante armazenada corresponde ao terceiro caso. Esse bloco é executado e a estrutura termina após o break.",

            "pergunta" =>
                "Qual é a mensagem final?",

            "opcoes" => [

                [
                    "texto" =>
                        "Atendimento imediato",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Resolver em breve",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_media"
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
                "Enum desbloqueado!",

            "robert" =>
                "Boa po kkkkk. enum serve pra criar uma lista fechada de opções. Em vez de deixar entrar qualquer texto, tu define exatamente os valores permitidos. Dá pra guardar enum em variável ou atributo, comparar com == e usar no switch. Isso evita erro de digitação e deixa o código muito mais organizado e seguro.",

            "natanael" =>
                "Concluímos o funcionamento básico de enum. Essa estrutura cria um tipo composto por um conjunto fixo de constantes. Utilizamos valores de enum em variáveis, comparações, atributos de objetos e estruturas switch. Como somente as opções declaradas podem ser atribuídas, reduzimos estados inválidos e tornamos as regras do programa mais claras. Na próxima aula vamos estudar static e entender como determinados atributos e métodos pertencem à classe, em vez de pertencerem individualmente a cada objeto.",

            "mensagem" =>
                "Tu concluiu a trigésima quinta Aula Conversada."

        ]

    ]

];