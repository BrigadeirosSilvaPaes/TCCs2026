<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 18,

    "titulo" =>
        "Preparando o objeto desde a criação",

    "assunto" =>
        "Construtores",

    "descricao" =>
        "Descubra como os construtores preparam os atributos automaticamente quando um objeto é criado.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        19,


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
                "Algo acontece durante a criação...",

            "mensagem" =>
                "Observa que existe uma parte da classe com o mesmo nome dela.",

            "codigo" => <<<'JAVA'
class Cafeteira {

    Cafeteira() {

        System.out.println(
            "Cafeteira criada"
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Cafeteira maquina = new Cafeteira();

    }

}
JAVA,

            "pergunta" =>
                "O que acontece quando new Cafeteira() é executado?",

            "opcoes" => [

                [
                    "texto" =>
                        "O objeto é criado e o construtor mostra Cafeteira criada",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "O objeto é criado, mas nenhuma mensagem aparece",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nao_executa"
                ],

                [
                    "texto" =>
                        "Uma segunda classe chamada Cafeteira é criada",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_classe"
                ],

                [
                    "texto" =>
                        "Não sei o que Cafeteira() representa",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O CONSTRUTOR NÃO EXECUTA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_nao_executa" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O construtor não precisa ser chamado separado. Quando aparece new Cafeteira(), o Java cria o objeto e já executa Cafeteira() automaticamente.",

            "natanael" =>
                "A execução do construtor está ligada à criação do objeto. Quando o Java encontra new Cafeteira(), ele reserva o novo objeto e chama automaticamente o construtor Cafeteira(). Como existe um println dentro dele, a mensagem será apresentada.",

            "pergunta" =>
                "Qual trecho inicia a criação do objeto e chama o construtor?",

            "opcoes" => [

                [
                    "texto" =>
                        "new Cafeteira()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "class Cafeteira",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nao_executa"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU COM OUTRA CLASSE
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_classe" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A classe já existe. Cafeteira() dentro dela não cria outra classe. Essa parte é o construtor usado quando um objeto nasce.",

            "natanael" =>
                "Existe apenas uma classe chamada Cafeteira. O trecho Cafeteira() está dentro dessa classe e representa seu construtor. Ele descreve o que deve acontecer quando uma nova instância for criada.",

            "pergunta" =>
                "Cafeteira() está criando uma classe nova ou preparando um objeto da classe existente?",

            "opcoes" => [

                [
                    "texto" =>
                        "Preparando um objeto da classe existente",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Criando outra classe",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_classe"
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
                "Pensa no construtor como a preparação inicial. Tu compra uma cafeteira e ela já vem montada. O new cria o objeto e o construtor faz essa montagem inicial.",

            "natanael" =>
                "Imagina uma fábrica. A classe funciona como o projeto da cafeteira. Quando solicitamos uma nova unidade com new, o construtor representa a etapa de montagem e preparação antes de entregar o objeto pronto para uso.",

            "pergunta" =>
                "O construtor acontece antes ou durante a criação do objeto?",

            "opcoes" => [

                [
                    "texto" =>
                        "Durante a criação",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Somente quando chamamos depois",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nao_executa"
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
                "É isso. O construtor tem o mesmo nome da classe e é executado automaticamente quando usamos new.",

            "natanael" =>
                "Exatamente. O construtor possui o mesmo nome da classe e participa diretamente da criação da instância. Nesse exemplo, ele apresenta uma mensagem durante esse processo.",

            "pergunta" =>
                "Qual mensagem aparece ao criar a cafeteira?",

            "opcoes" => [

                [
                    "texto" =>
                        "Cafeteira criada",

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
                        "pergunta_1_erro_nao_executa"
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
                "Criando o objeto com informações...",

            "mensagem" =>
                "Agora o construtor recebe valores e utiliza esses dados para preparar os atributos.",

            "codigo" => <<<'JAVA'
class Aluno {

    String nome;

    int nivel;


    Aluno(String nomeRecebido, int nivelRecebido) {

        nome = nomeRecebido;

        nivel = nivelRecebido;

    }


    void apresentar() {

        System.out.println(
            nome + " está no nível " + nivel
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Aluno aluno = new Aluno(
            "Ana",
            3
        );

        aluno.apresentar();

    }

}
JAVA,

            "pergunta" =>
                "Qual mensagem aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana está no nível 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "nomeRecebido está no nível nivelRecebido",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_parametros"
                ],

                [
                    "texto" =>
                        "null está no nível 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_nao_atribui"
                ],

                [
                    "texto" =>
                        "O código cria o aluno sem nome e sem nível",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_sem_valores"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE MOSTRA OS NOMES DOS PARÂMETROS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_parametros" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "nomeRecebido e nivelRecebido são variáveis temporárias do construtor. Elas recebem Ana e 3. Depois esses valores são guardados nos atributos nome e nivel.",

            "natanael" =>
                "Os parâmetros representam espaços que recebem os argumentos enviados na criação. nomeRecebido recebe o texto Ana e nivelRecebido recebe o número 3. Em seguida, esses valores são atribuídos aos atributos do objeto.",

            "pergunta" =>
                "Qual valor entra em nomeRecebido?",

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
                        "nomeRecebido",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_parametros"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE OS ATRIBUTOS NÃO SÃO PREENCHIDOS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_nao_atribui" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "null e 0 seriam os valores iniciais se ninguém mudasse nada. Só que o construtor muda: nome recebe Ana e nivel recebe 3.",

            "natanael" =>
                "Antes de uma atribuição, atributos de objeto podem possuir valores padrão. Porém o construtor executa imediatamente e substitui esses valores. As linhas nome = nomeRecebido e nivel = nivelRecebido preparam o estado do aluno.",

            "pergunta" =>
                "Qual linha coloca o valor recebido dentro do atributo nome?",

            "opcoes" => [

                [
                    "texto" =>
                        "nome = nomeRecebido;",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Aluno aluno;",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_nao_atribui"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE OS VALORES NÃO SÃO ENVIADOS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_sem_valores" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Os valores estão bem ali dentro do new Aluno: Ana e 3. Eles são enviados pro construtor na mesma ordem dos parâmetros.",

            "natanael" =>
                "A chamada new Aluno(\"Ana\", 3) envia dois argumentos. O primeiro corresponde ao parâmetro String nomeRecebido, e o segundo corresponde ao parâmetro int nivelRecebido.",

            "codigo" => <<<'JAVA'
Aluno aluno = new Aluno(
    "Ana",
    3
);
JAVA,

            "pergunta" =>
                "Quais valores são enviados para o construtor?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana e 3",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Nenhum valor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_sem_valores"
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
                "O caminho é: new envia Ana e 3, o construtor recebe esses valores e coloca nos atributos. Depois apresentar() usa o que ficou guardado.",

            "natanael" =>
                "Podemos acompanhar o fluxo completo: a criação envia os argumentos; os parâmetros recebem esses valores; o construtor atribui os valores aos atributos; por fim, o método apresentar consulta o estado preparado.",

            "pergunta" =>
                "Qual frase será montada por apresentar()?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana está no nível 3",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "null está no nível 0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_nao_atribui"
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
                "O que significa this?",

            "mensagem" =>
                "É comum o parâmetro possuir o mesmo nome do atributo. Para separar os dois, podemos utilizar this.",

            "codigo" => <<<'JAVA'
class Conta {

    String titular;


    Conta(String titular) {

        this.titular = titular;

    }

}
JAVA,

            "pergunta" =>
                "O que this.titular representa?",

            "opcoes" => [

                [
                    "texto" =>
                        "O atributo titular do objeto que está sendo criado",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "O parâmetro titular recebido pelo construtor",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_parametro"
                ],

                [
                    "texto" =>
                        "Uma nova variável chamada this",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_variavel"
                ],

                [
                    "texto" =>
                        "Não sei por que existem dois titular",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU THIS COM PARÂMETRO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_parametro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O titular sem this é o parâmetro. this.titular é o atributo do objeto. A linha pega o valor do parâmetro e salva no objeto.",

            "natanael" =>
                "Como os dois possuem o mesmo nome, precisamos de uma forma de distingui-los. O titular à direita representa o parâmetro recebido. Já this.titular, à esquerda, aponta para o atributo pertencente à instância atual.",

            "codigo" => <<<'JAVA'
this.titular = titular;
// atributo       parâmetro
JAVA,

            "pergunta" =>
                "Qual lado representa o atributo do objeto?",

            "opcoes" => [

                [
                    "texto" =>
                        "this.titular",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "titular sem this",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_parametro"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE THIS É UMA NOVA VARIÁVEL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_variavel" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "this não é uma variável aleatória criada ali. Ele é uma palavra do Java que aponta pro próprio objeto atual.",

            "natanael" =>
                "this é uma palavra-chave da linguagem Java. Dentro de um objeto, ela permite fazer referência à própria instância que está executando aquele código.",

            "pergunta" =>
                "Dentro do construtor, this aponta para quem?",

            "opcoes" => [

                [
                    "texto" =>
                        "Para o próprio objeto atual",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Para uma variável nova",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_variavel"
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
                "Pensa que o objeto fala: este meu titular recebe o titular que chegou. this seria tipo dizer 'meu'.",

            "natanael" =>
                "Imagina o próprio objeto dizendo: meu atributo titular receberá o valor titular enviado ao construtor. O this ajuda a indicar que estamos falando de uma característica pertencente àquele objeto específico.",

            "pergunta" =>
                "Na expressão this.titular, estamos falando do atributo de qual objeto?",

            "opcoes" => [

                [
                    "texto" =>
                        "Do objeto atual",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "De todos os objetos ao mesmo tempo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_variavel"
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
                "Fica assim: this.titular é o atributo do objeto. titular sozinho é o parâmetro que chegou no construtor.",

            "natanael" =>
                "Exatamente. O this identifica a instância atual. Assim conseguimos utilizar nomes iguais sem confundir o atributo pertencente ao objeto com o parâmetro recebido pelo construtor.",

            "pergunta" =>
                "O que acontece em this.titular = titular?",

            "opcoes" => [

                [
                    "texto" =>
                        "O atributo do objeto recebe o valor do parâmetro",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "O parâmetro recebe um objeto novo",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_parametro"
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
                "Mesmo construtor, objetos diferentes...",

            "mensagem" =>
                "Agora o construtor será utilizado duas vezes para preparar dois objetos independentes.",

            "codigo" => <<<'JAVA'
class Produto {

    String nome;

    double preco;


    Produto(String nome, double preco) {

        this.nome = nome;

        this.preco = preco;

    }


    void exibir() {

        System.out.println(
            nome + " - R$ " + preco
        );

    }

}

public class Main {

    public static void main(String[] args) {

        Produto produto1 = new Produto(
            "Café",
            12.5
        );

        Produto produto2 = new Produto(
            "Livro",
            30.0
        );


        produto1.exibir();

        produto2.exibir();

    }

}
JAVA,

            "pergunta" =>
                "Qual resultado aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Café - R$ 12.5 e depois Livro - R$ 30.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Livro - R$ 30.0 duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_mesmo"
                ],

                [
                    "texto" =>
                        "Café - R$ 12.5 duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_primeiro"
                ],

                [
                    "texto" =>
                        "O construtor só pode ser executado uma vez",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_uma_vez"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE OS DOIS FICAM COM O ÚLTIMO VALOR
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_mesmo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "São dois objetos separados. Criar produto2 como Livro não transforma produto1 em Livro. Cada new prepara uma instância diferente.",

            "natanael" =>
                "Cada expressão new Produto(...) cria uma nova instância. produto1 mantém os valores Café e 12.5. produto2 mantém Livro e 30.0. O estado de um objeto não substitui automaticamente o estado do outro.",

            "pergunta" =>
                "Quantos objetos Produto foram criados?",

            "opcoes" => [

                [
                    "texto" =>
                        "Dois",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Um",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_mesmo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE OS DOIS FICAM COM O PRIMEIRO VALOR
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_primeiro" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Café foi enviado só pro produto1. No segundo new, o construtor recebe Livro e 30.0 pra preparar produto2.",

            "natanael" =>
                "A primeira chamada do construtor recebe Café e 12.5. A segunda chamada recebe argumentos diferentes: Livro e 30.0. Cada conjunto de valores é armazenado em seu respectivo objeto.",

            "pergunta" =>
                "Quais valores foram enviados na criação de produto2?",

            "opcoes" => [

                [
                    "texto" =>
                        "Livro e 30.0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Café e 12.5",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_primeiro"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE CONSTRUTOR EXECUTA SOMENTE UMA VEZ
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_uma_vez" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O construtor executa sempre que um novo objeto é criado. Tem dois new Produto(), então ele roda duas vezes.",

            "natanael" =>
                "O construtor não pertence apenas ao primeiro objeto. Ele é utilizado em cada nova criação compatível com sua definição. Como existem duas expressões new Produto, o construtor é executado duas vezes.",

            "pergunta" =>
                "Quantas vezes aparece new Produto(...) no código?",

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
                        "pergunta_4_erro_uma_vez"
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
                "Pensa em duas fichas preenchidas pelo mesmo formulário. A primeira recebe Café e 12.5. A segunda recebe Livro e 30.0. Cada ficha mantém seus dados.",

            "natanael" =>
                "O construtor funciona como um processo de preenchimento inicial. Ele é o mesmo para os dois produtos, mas recebe argumentos diferentes em cada criação. Por isso os objetos resultantes possuem estados independentes.",

            "pergunta" =>
                "Qual sequência completa será exibida?",

            "opcoes" => [

                [
                    "texto" =>
                        "Café - R$ 12.5 → Livro - R$ 30.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Livro - R$ 30.0 → Livro - R$ 30.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_mesmo"
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
                "Construtores desbloqueados!",

            "robert" =>
                "Boa po kkkkk. O construtor prepara o objeto no momento do new. Ele tem o mesmo nome da classe, pode receber parâmetros e não precisa de tipo de retorno. O this ajuda a dizer que estamos mexendo no atributo daquele objeto. E cada novo objeto passa pelo construtor com seus próprios valores. Agora bora aprender a proteger melhor essas informações.",

            "natanael" =>
                "Fechamos o funcionamento básico dos construtores. Eles são executados durante a criação dos objetos e normalmente são utilizados para preparar seu estado inicial. Um construtor possui o mesmo nome da classe, não declara tipo de retorno e pode receber parâmetros. Também vimos que this representa a instância atual e permite diferenciar atributos de parâmetros com o mesmo nome. Cada criação executa novamente o construtor e produz um objeto independente. Na próxima aula vamos estudar encapsulamento e entender como controlar o acesso aos atributos.",

            "mensagem" =>
                "Tu concluiu a décima oitava Aula Conversada."

        ]

    ]

];