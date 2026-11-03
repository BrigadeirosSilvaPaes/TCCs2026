<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 22,

    "titulo" =>
        "Definindo comportamentos obrigatórios",

    "assunto" =>
        "Interfaces",

    "descricao" =>
        "Descubra como as interfaces definem comportamentos que diferentes classes podem implementar de maneiras próprias.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        23,


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
                "Um contrato para a classe...",

            "mensagem" =>
                "Observa que Pagamento não é uma classe comum. Ela foi declarada como interface.",

            "codigo" => <<<'JAVA'
interface Pagamento {

    void pagar();

}

class Pix implements Pagamento {

    @Override
    public void pagar() {

        System.out.println(
            "Pagamento realizado via Pix"
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Pix pagamento = new Pix();

        pagamento.pagar();

    }

}
JAVA,

            "pergunta" =>
                "O que significa class Pix implements Pagamento?",

            "opcoes" => [

                [
                    "texto" =>
                        "Pix assume o contrato de Pagamento e precisa fornecer o método pagar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Pix copia automaticamente todo o código de Pagamento",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_copia"
                ],

                [
                    "texto" =>
                        "Pagamento vira um objeto dentro de Pix",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_objeto"
                ],

                [
                    "texto" =>
                        "Não sei o que implements significa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE COPIA UMA IMPLEMENTAÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_copia" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A interface só avisou que precisa existir uma ação chamada pagar. O código dessa ação foi escrito dentro de Pix. Não apareceu pronto por mágica kkkkk.",

            "natanael" =>
                "Nessa forma básica, a interface define quais comportamentos devem existir, mas não fornece o conteúdo específico de pagar. A classe Pix assume esse contrato e escreve sua própria implementação dentro do método.",

            "pergunta" =>
                "Em qual parte está escrito o println que realiza o pagamento?",

            "opcoes" => [

                [
                    "texto" =>
                        "Dentro do método pagar da classe Pix",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Dentro da interface Pagamento",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_copia"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU COM OBJETO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_objeto" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "implements não coloca um objeto dentro da classe. Ele cria uma obrigação: se Pix diz que implementa Pagamento, precisa cumprir os métodos desse contrato.",

            "natanael" =>
                "Não existe uma composição de objetos nessa declaração. implements estabelece uma relação entre a classe e a interface. A classe afirma que oferece os comportamentos definidos por aquele contrato.",

            "pergunta" =>
                "Qual comportamento a interface Pagamento exige?",

            "opcoes" => [

                [
                    "texto" =>
                        "pagar()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "criarObjeto()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_objeto"
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
                "Pensa num contrato de emprego. O contrato diz que a pessoa precisa realizar certa tarefa. Cada pessoa pode fazer do seu jeito, mas precisa cumprir o combinado. implements é a classe aceitando esse contrato.",

            "natanael" =>
                "Imagine uma tomada que estabelece um padrão. Vários aparelhos podem funcionar de formas diferentes, mas precisam possuir uma conexão compatível. A interface define esse padrão, e a classe que utiliza implements compromete-se a fornecê-lo.",

            "pergunta" =>
                "Ao implementar Pagamento, Pix precisa possuir qual método?",

            "opcoes" => [

                [
                    "texto" =>
                        "pagar()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "emitirSom()",

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
                "É isso. Pagamento define o combinado e Pix escreve como pagar vai funcionar para pagamentos via Pix.",

            "natanael" =>
                "Exatamente. A interface estabelece o método necessário, enquanto a classe implementadora fornece o comportamento concreto correspondente.",

            "pergunta" =>
                "Qual mensagem aparece quando pagamento.pagar() é chamado?",

            "opcoes" => [

                [
                    "texto" =>
                        "Pagamento realizado via Pix",

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
                        "pergunta_1_erro_copia"
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
                "O contrato precisa ser cumprido...",

            "mensagem" =>
                "Agora uma classe afirma que implementa uma interface, mas não fornece o método exigido.",

            "codigo" => <<<'JAVA'
interface Ligavel {

    void ligar();

}

class Lampada implements Ligavel {

}

public class Main {

    public static void main(String[] args) {

        Lampada lampada = new Lampada();

    }

}
JAVA,

            "pergunta" =>
                "Qual é o problema desse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "Lampada precisa implementar o método ligar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "A interface deveria ser criada com new",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_new"
                ],

                [
                    "texto" =>
                        "A classe Lampada não pode possuir métodos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_metodos"
                ],

                [
                    "texto" =>
                        "Não existe problema, o Java cria ligar automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_automatico"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE PRECISA CRIAR A INTERFACE COM NEW
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_new" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O problema não é faltar new pra interface. O problema é que Lampada prometeu ter ligar(), mas deixou a classe vazia.",

            "natanael" =>
                "A interface já foi declarada e está sendo utilizada como contrato. A falha acontece porque a classe concreta Lampada não forneceu a implementação do método exigido.",

            "pergunta" =>
                "Qual método aparece dentro da interface Ligavel?",

            "opcoes" => [

                [
                    "texto" =>
                        "ligar()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "new()",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_new"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE A CLASSE NÃO PODE TER MÉTODOS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_metodos" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "É justamente o contrário kkkkk. Lampada precisa criar o método ligar pra cumprir a interface.",

            "natanael" =>
                "Uma classe pode possuir métodos normalmente. Nesse caso, inclusive, ela precisa declarar ligar para cumprir o contrato estabelecido por Ligavel.",

            "pergunta" =>
                "Lampada deve evitar ou adicionar o método ligar?",

            "opcoes" => [

                [
                    "texto" =>
                        "Adicionar o método ligar",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Evitar todos os métodos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_metodos"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O JAVA CRIA AUTOMATICAMENTE
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_automatico" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Java não adivinha o que uma lâmpada deve fazer ao ligar kkkkk. A classe precisa escrever o comportamento.",

            "natanael" =>
                "A interface informa apenas a existência necessária do método. O compilador não inventa uma implementação para a classe. Lampada precisa definir o código que será executado ao ligar.",

            "pergunta" =>
                "Quem deve escrever o comportamento específico de ligar?",

            "opcoes" => [

                [
                    "texto" =>
                        "A classe Lampada",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "O Java automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_automatico"
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
                "Pra corrigir, Lampada precisa colocar public void ligar() e decidir o que acontece nessa ação.",

            "natanael" =>
                "A solução é fornecer uma implementação pública compatível com o método da interface. Assim a classe cumpre o contrato assumido com implements.",

            "codigo" => <<<'JAVA'
class Lampada implements Ligavel {

    @Override
    public void ligar() {

        System.out.println(
            "Lâmpada ligada"
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual parte corrigiu o problema?",

            "opcoes" => [

                [
                    "texto" =>
                        "A implementação de public void ligar()",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "A remoção de todos os métodos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_metodos"
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
                "Mesmo contrato, respostas diferentes...",

            "mensagem" =>
                "Duas classes diferentes implementam a mesma interface, mas cada uma envia a notificação de uma maneira.",

            "codigo" => <<<'JAVA'
interface Notificacao {

    void enviar();

}

class Email implements Notificacao {

    @Override
    public void enviar() {

        System.out.println(
            "Enviando e-mail"
        );

    }

}

class SMS implements Notificacao {

    @Override
    public void enviar() {

        System.out.println(
            "Enviando SMS"
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Notificacao[] canais = {
            new Email(),
            new SMS()
        };

        for (
            int i = 0;
            i < canais.length;
            i++
        ) {

            canais[i].enviar();

        }

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Enviando e-mail e depois Enviando SMS",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Enviando e-mail duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_email"
                ],

                [
                    "texto" =>
                        "Enviando SMS duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_sms"
                ],

                [
                    "texto" =>
                        "O array não aceita Email e SMS juntos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_array"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE OS DOIS USAM EMAIL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_email" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Só a primeira posição guarda new Email(). A segunda guarda new SMS(), então ela usa a implementação que mostra Enviando SMS.",

            "natanael" =>
                "O array possui duas instâncias diferentes. A primeira posição referencia um objeto Email. A segunda referencia um objeto SMS. Cada objeto executa sua própria implementação do método enviar.",

            "pergunta" =>
                "Qual objeto está em canais[1]?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um SMS",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Um Email",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_email"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE OS DOIS USAM SMS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_sms" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O primeiro objeto ainda é Email. O for começa no índice 0, então primeiro envia e-mail e só depois chega no SMS.",

            "natanael" =>
                "A ordem do array é preservada durante a repetição. O índice 0 contém Email e é processado primeiro. Depois o índice 1 contém SMS.",

            "pergunta" =>
                "Qual objeto está em canais[0]?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um Email",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Um SMS",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_sms"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O ARRAY NÃO ACEITA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_array" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aceita porque os dois implementam Notificacao. É tipo uma lista de canais: pode ter e-mail, SMS e qualquer outro que cumpra o contrato.",

            "natanael" =>
                "Email e SMS são classes diferentes, mas compartilham o tipo Notificacao porque ambas implementam essa interface. Por isso podem ser armazenadas em um mesmo array declarado como Notificacao[].",

            "pergunta" =>
                "Email e SMS implementam qual interface?",

            "opcoes" => [

                [
                    "texto" =>
                        "Notificacao",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Pagamento",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_array"
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
                "O comando é o mesmo: canais[i].enviar(). Só que cada objeto responde do seu jeito. Email manda e-mail e SMS manda SMS.",

            "natanael" =>
                "A interface oferece um tipo comum e uma chamada comum. Durante a execução, o comportamento específico é escolhido conforme a classe real do objeto armazenado em cada posição.",

            "pergunta" =>
                "Qual sequência completa será apresentada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Enviando e-mail → Enviando SMS",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Enviando e-mail → Enviando e-mail",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_email"
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
                "Podemos criar a interface diretamente?",

            "mensagem" =>
                "Agora observa a tentativa de utilizar new diretamente com o nome da interface.",

            "codigo" => <<<'JAVA'
interface Recarregavel {

    void recarregar();

}

public class Main {

    public static void main(String[] args) {

        Recarregavel dispositivo =
            new Recarregavel();

    }

}
JAVA,

            "pergunta" =>
                "O que acontece com esse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não compila porque uma interface não pode ser instanciada diretamente",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Cria um objeto Recarregavel normalmente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_instancia"
                ],

                [
                    "texto" =>
                        "Executa recarregar automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_executa"
                ],

                [
                    "texto" =>
                        "A interface se transforma em uma classe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_transforma"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE PODE INSTANCIAR
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_instancia" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A interface é o contrato, não um objeto pronto. Pra usar new, precisa existir uma classe concreta que implemente esse contrato.",

            "natanael" =>
                "A interface define um tipo e um conjunto de comportamentos esperados, mas não representa uma implementação concreta que possa ser construída diretamente. Precisamos criar uma classe implementadora.",

            "pergunta" =>
                "Para usar new, precisamos de uma interface sozinha ou de uma classe concreta?",

            "opcoes" => [

                [
                    "texto" =>
                        "De uma classe concreta",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Somente da interface",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_instancia"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE EXECUTA O MÉTODO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_executa" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Além de não poder criar a interface direto, ninguém chamou recarregar(). O código só tentou criar um objeto com new Recarregavel().",

            "natanael" =>
                "Não existe uma chamada ao método recarregar nesse trecho. Antes disso, a própria criação já é inválida, pois Recarregavel é uma interface e não uma classe concreta instanciável.",

            "pergunta" =>
                "Existe alguma linha com dispositivo.recarregar()?",

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
                        "pergunta_4_erro_executa"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE A INTERFACE VIRA CLASSE
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_transforma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Usar new não muda o tipo da estrutura. Interface continua sendo interface. O código só vai dar erro nessa tentativa.",

            "natanael" =>
                "A palavra new não converte uma interface em classe. Cada estrutura mantém seu papel. Para produzir um objeto, é necessário possuir uma implementação concreta do contrato.",

            "pergunta" =>
                "Recarregavel foi declarado com class ou interface?",

            "opcoes" => [

                [
                    "texto" =>
                        "interface",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "class",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_transforma"
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
                "O jeito certo é criar uma classe, tipo Celular, fazer ela implementar Recarregavel e depois usar new Celular().",

            "natanael" =>
                "A interface pode ser utilizada como tipo da variável, mas o objeto precisa ser criado a partir de uma classe concreta que implemente o comportamento exigido.",

            "codigo" => <<<'JAVA'
class Celular implements Recarregavel {

    @Override
    public void recarregar() {

        System.out.println(
            "Celular recarregando"
        );

    }

}

Recarregavel dispositivo =
    new Celular();

dispositivo.recarregar();
JAVA,

            "pergunta" =>
                "Por que new Celular() é permitido?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque Celular é uma classe concreta que implementa Recarregavel",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Porque toda interface pode ser criada diretamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_instancia"
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
                "Interfaces desbloqueadas!",

            "robert" =>
                "Boa po kkkkk. Interface é tipo um contrato: ela define quais ações precisam existir. A classe usa implements e escreve como essas ações funcionam. Classes diferentes podem cumprir o mesmo contrato de jeitos diferentes, e isso deixa o código bem mais flexível. Só lembra que não dá pra fazer new direto na interface; precisa criar uma classe concreta. Agora vamos entrar nos erros que podem aparecer durante a execução do programa.",

            "natanael" =>
                "Fechamos o conceito básico de interfaces. Uma interface define comportamentos que classes implementadoras devem oferecer. A palavra implements estabelece esse compromisso, e uma classe concreta precisa fornecer implementações públicas compatíveis com os métodos exigidos. Classes sem relação direta de herança podem compartilhar a mesma interface e ser tratadas por um tipo comum, mantendo comportamentos específicos. Também vimos que interfaces não são instanciadas diretamente; o objeto deve vir de uma classe concreta. Na próxima aula vamos estudar exceções e entender como o Java representa problemas ocorridos durante a execução.",

            "mensagem" =>
                "Tu concluiu a vigésima segunda Aula Conversada."

        ]

    ]

];