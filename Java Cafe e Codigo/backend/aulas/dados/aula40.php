<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 40,

    "titulo" =>
        "Personalizando a apresentação dos objetos",

    "assunto" =>
        "toString()",

    "descricao" =>
        "Aprenda a sobrescrever o método toString para criar representações textuais mais claras para objetos, mensagens e coleções.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        41,


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
                "Mostrando um objeto sem personalização...",

            "mensagem" =>
                "A classe Produto ainda não sobrescreveu o método toString herdado da classe Object.",

            "codigo" => <<<'JAVA'
class Produto {

    private String nome;


    Produto(String nome) {

        this.nome = nome;

    }

}

public class Main {

    public static void main(String[] args) {

        Produto produto =
            new Produto("Café");

        System.out.println(
            produto
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual afirmação está correta sobre essa saída?",

            "opcoes" => [

                [
                    "texto" =>
                        "Aparece uma representação padrão semelhante a Produto@1a2b3c, e não apenas o nome Café",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Aparece Café automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_atributo"
                ],

                [
                    "texto" =>
                        "Aparece null porque o objeto não possui toString",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_null"
                ],

                [
                    "texto" =>
                        "Aparece obrigatoriamente o endereço exato do objeto na memória",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_memoria"
                ],

                [
                    "texto" =>
                        "Não sei o que acontece ao mostrar um objeto",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE MOSTRA O ATRIBUTO AUTOMATICAMENTE
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_atributo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O Java não adivinha qual atributo tu quer mostrar. Mesmo o objeto guardando Café, ainda não existe uma regra dizendo que o nome deve aparecer.",

            "natanael" =>
                "O atributo nome está armazenado corretamente, mas o método padrão não consulta automaticamente todos os campos do objeto. Precisamos sobrescrever toString para escolher a representação desejada.",

            "pergunta" =>
                "A classe Produto declarou algum método toString personalizado?",

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
                        "pergunta_1_erro_atributo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE O RESULTADO É NULL
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_null" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A variável produto não está null. Ela aponta pra um objeto criado com new Produto(\"Café\"). Todo objeto já herda um toString básico da classe Object.",

            "natanael" =>
                "A instância foi criada normalmente. Mesmo sem declarar um método próprio, Produto herda a implementação padrão de toString existente em Object.",

            "pergunta" =>
                "O objeto produto foi criado com new?",

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
                        "Não, produto vale null",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_null"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU COM ENDEREÇO DE MEMÓRIA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_memoria" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Muita gente chama isso de endereço de memória, mas não é uma definição correta. A saída padrão combina o nome da classe com um código hexadecimal baseado no hash do objeto.",

            "natanael" =>
                "A implementação padrão de Object.toString utiliza o nome da classe, o símbolo arroba e uma representação hexadecimal relacionada ao hash code. Isso não garante que o valor seja um endereço real de memória.",

            "codigo" => <<<'JAVA'
Produto@1a2b3c
JAVA,

            "pergunta" =>
                "A parte depois do @ deve ser tratada obrigatoriamente como endereço real de memória?",

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
                        "Sim, sempre",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_memoria"
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
                "Pensa num objeto sem etiqueta personalizada. O Java ainda consegue mostrar alguma identificação, mas ela não fica bonita nem útil pro usuário.",

            "natanael" =>
                "Todo objeto possui uma representação padrão herdada. Entretanto essa representação normalmente identifica a classe e um código técnico, em vez de apresentar os atributos importantes.",

            "pergunta" =>
                "A representação padrão costuma mostrar diretamente os dados escolhidos pelo programador?",

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
                        "Sim, todos os atributos aparecem automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_atributo"
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
                "Sem um toString próprio, Produto usa a versão herdada de Object. Ela gera algo parecido com Produto@1a2b3c, mas o código depois do @ pode variar.",

            "natanael" =>
                "Como a classe não personalizou sua representação, o println utiliza Object.toString. O formato inclui o nome da classe e um código técnico variável.",

            "pergunta" =>
                "Qual comportamento está correto?",

            "opcoes" => [

                [
                    "texto" =>
                        "A saída utiliza uma representação padrão, não apenas Café",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "O Java apresenta automaticamente todos os atributos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_atributo"
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
                "Criando uma representação personalizada...",

            "mensagem" =>
                "Agora Produto sobrescreve toString e escolhe exatamente quais informações devem formar o texto.",

            "codigo" => <<<'JAVA'
class Produto {

    private String nome;

    private double preco;


    Produto(
        String nome,
        double preco
    ) {

        this.nome = nome;

        this.preco = preco;

    }


    @Override
    public String toString() {

        return nome +
            " - R$ " +
            preco;

    }

}

public class Main {

    public static void main(String[] args) {

        Produto produto =
            new Produto(
                "Café",
                12.5
            );

        System.out.println(
            produto
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual texto aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Café - R$ 12.5",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Produto@ seguido de um código",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_padrao"
                ],

                [
                    "texto" =>
                        "Café",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_preco"
                ],

                [
                    "texto" =>
                        "O código não compila porque toString pertence à classe Object",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_override"
                ],

                [
                    "texto" =>
                        "Não sei quando toString é executado",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE CONTINUA USANDO A VERSÃO PADRÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_padrao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Agora existe um toString dentro de Produto. Essa versão substitui o comportamento padrão quando o objeto é transformado em texto.",

            "natanael" =>
                "A classe forneceu uma implementação mais específica. Portanto a versão herdada de Object deixa de ser utilizada para objetos Produto.",

            "pergunta" =>
                "Produto declarou sua própria implementação de toString?",

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
                        "pergunta_2_erro_padrao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ESQUECEU O PREÇO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_preco" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O return não devolve só nome. Depois do nome ele junta o texto R$ e também o atributo preco.",

            "natanael" =>
                "A expressão de retorno concatena três partes: o nome, o separador textual e o valor do preço. Todo esse conteúdo forma uma única String.",

            "codigo" => <<<'JAVA'
return nome +
    " - R$ " +
    preco;
JAVA,

            "pergunta" =>
                "Qual valor está guardado no atributo preco?",

            "opcoes" => [

                [
                    "texto" =>
                        "12.5",

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
                        "pergunta_2_erro_preco"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE NÃO PODE SOBRESCREVER
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_override" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "É justamente por toString vir de Object que ele pode ser sobrescrito. A classe Produto cria uma versão mais útil pro seu próprio tipo.",

            "natanael" =>
                "Object fornece uma implementação inicial, mas o método foi criado para permitir sobrescrita. A anotação Override confirma que estamos substituindo um método herdado.",

            "pergunta" =>
                "Qual é o tipo de retorno obrigatório de toString?",

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
                        "void",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_override"
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
                "Quando o println recebe um objeto, ele precisa transformar esse objeto em texto. Aí chama o toString daquele objeto automaticamente.",

            "natanael" =>
                "O println trabalha com a representação textual do valor recebido. Quando o argumento é um objeto, sua implementação de toString é utilizada para produzir essa representação.",

            "pergunta" =>
                "Qual método transforma Produto em uma String?",

            "opcoes" => [

                [
                    "texto" =>
                        "toString()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "main()",

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
                "O println chama toString. O método devolve nome, separador e preço. Então as três partes aparecem juntas.",

            "natanael" =>
                "A implementação personalizada devolve uma única String formada pelos dois atributos e pelo texto de separação.",

            "pergunta" =>
                "Qual é a saída completa?",

            "opcoes" => [

                [
                    "texto" =>
                        "Café - R$ 12.5",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Produto@1a2b3c",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_padrao"
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
                "Usando o objeto dentro de outras mensagens...",

            "mensagem" =>
                "O método toString também é utilizado automaticamente durante uma concatenação entre texto e objeto.",

            "codigo" => <<<'JAVA'
class Pessoa {

    private String nome;

    private int idade;


    Pessoa(
        String nome,
        int idade
    ) {

        this.nome = nome;

        this.idade = idade;

    }


    @Override
    public String toString() {

        return nome +
            " (" +
            idade +
            " anos)";

    }

}

public class Main {

    public static void main(String[] args) {

        Pessoa pessoa =
            new Pessoa(
                "Ana",
                17
            );

        System.out.println(
            pessoa
        );

        String mensagem =
            "Usuário: " + pessoa;

        System.out.println(
            mensagem
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana (17 anos) e depois Usuário: Ana (17 anos)",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Pessoa e depois Usuário: Pessoa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_classe"
                ],

                [
                    "texto" =>
                        "Ana (17 anos) e depois Usuário: pessoa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_literal"
                ],

                [
                    "texto" =>
                        "Somente Usuário: Ana (17 anos)",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_quantidade"
                ],

                [
                    "texto" =>
                        "A concatenação não aceita objetos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_concatenacao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE MOSTRA O NOME DA CLASSE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_classe" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Pessoa é o tipo, mas a classe possui um toString personalizado. É esse método que decide o texto apresentado.",

            "natanael" =>
                "A saída não utiliza somente o nome da classe porque existe uma sobrescrita. O objeto passa a ser representado pela String devolvida pelo método.",

            "pergunta" =>
                "Qual nome está guardado no objeto?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Pessoa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_classe"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE MOSTRA O NOME DA VARIÁVEL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_literal" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "pessoa é o nome da variável no código. Como não está entre aspas, o Java usa o objeto e chama seu toString.",

            "natanael" =>
                "A concatenação não utiliza o identificador da variável como texto literal. Ela solicita a representação textual do valor guardado na variável.",

            "pergunta" =>
                "A palavra pessoa está entre aspas na concatenação?",

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
                        "pergunta_3_erro_literal"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONSIDEROU APENAS UMA SAÍDA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_quantidade" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Existem dois println. O primeiro mostra o objeto sozinho e o segundo mostra a variável mensagem.",

            "natanael" =>
                "A representação de Pessoa é utilizada duas vezes: diretamente no primeiro println e durante a construção da mensagem apresentada depois.",

            "pergunta" =>
                "Quantas chamadas de System.out.println aparecem?",

            "opcoes" => [

                [
                    "texto" =>
                        "Duas",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Uma",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_quantidade"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE NÃO PODE CONCATENAR
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_concatenacao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Pode sim. Quando uma String é somada com um objeto, o Java usa a representação textual dele, normalmente chamando toString.",

            "natanael" =>
                "A concatenação precisa transformar os valores envolvidos em texto. Para objetos, a implementação de toString fornece a representação necessária.",

            "codigo" => <<<'JAVA'
String mensagem =
    "Usuário: " + pessoa;
JAVA,

            "pergunta" =>
                "Qual método representa pessoa como texto?",

            "opcoes" => [

                [
                    "texto" =>
                        "toString()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Nenhum método pode fazer isso",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_concatenacao"
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
                "O toString da pessoa devolve Ana (17 anos). Primeiro isso aparece sozinho. Depois o mesmo texto é colocado depois de Usuário:.",

            "natanael" =>
                "A mesma representação personalizada participa das duas saídas. Na segunda, ela é concatenada com um prefixo textual.",

            "pergunta" =>
                "Qual sequência completa será apresentada?",

            "opcoes" => [

                [
                    "texto" =>
                        "Ana (17 anos) → Usuário: Ana (17 anos)",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Pessoa → Usuário: pessoa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_classe"
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
                "Mostrando objetos dentro de uma coleção...",

            "mensagem" =>
                "Ao mostrar um ArrayList, a lista utiliza o método toString de cada objeto armazenado.",

            "codigo" => <<<'JAVA'
import java.util.ArrayList;

class Tarefa {

    private String descricao;

    private boolean concluida;


    Tarefa(String descricao) {

        this.descricao = descricao;

        this.concluida = false;

    }


    void concluir() {

        concluida = true;

    }


    @Override
    public String toString() {

        if (concluida) {

            return "[x] " + descricao;

        }

        return "[ ] " + descricao;

    }

}

public class Main {

    public static void main(String[] args) {

        ArrayList<Tarefa> tarefas =
            new ArrayList<>();

        Tarefa tarefa1 =
            new Tarefa(
                "Estudar Java"
            );

        Tarefa tarefa2 =
            new Tarefa(
                "Treinar"
            );

        tarefas.add(tarefa1);

        tarefas.add(tarefa2);

        tarefa1.concluir();

        System.out.println(
            tarefas
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual texto aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "[[x] Estudar Java, [ ] Treinar]",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "[x] Estudar Java, [ ] Treinar",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_lista"
                ],

                [
                    "texto" =>
                        "[[x] Estudar Java, [x] Treinar]",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_estado"
                ],

                [
                    "texto" =>
                        "[Tarefa@1a2b3c, Tarefa@4d5e6f]",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_padrao"
                ],

                [
                    "texto" =>
                        "Aparece somente [x] Estudar Java",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_quantidade"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ESQUECEU OS COLCHETES DA LISTA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_lista" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Os textos das tarefas estão certos, mas o ArrayList coloca colchetes externos ao redor de todos os elementos.",

            "natanael" =>
                "A representação de ArrayList envolve o conteúdo completo com colchetes e separa os elementos utilizando vírgula e espaço.",

            "codigo" => <<<'JAVA'
[primeiro elemento, segundo elemento]
JAVA,

            "pergunta" =>
                "O ArrayList coloca colchetes ao redor da coleção inteira?",

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
                        "pergunta_4_erro_lista"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE AS DUAS FORAM CONCLUÍDAS
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_estado" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Só tarefa1 recebeu a chamada concluir(). tarefa2 continua com concluida igual a false.",

            "natanael" =>
                "Os objetos mantêm estados independentes. A alteração realizada sobre a primeira instância não modifica automaticamente a segunda.",

            "pergunta" =>
                "Qual objeto recebe diretamente a chamada concluir()?",

            "opcoes" => [

                [
                    "texto" =>
                        "Somente tarefa1",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "tarefa1 e tarefa2",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_estado"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE USA A REPRESENTAÇÃO PADRÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_padrao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Tarefa possui um toString próprio. Quando a lista mostra os elementos, ela utiliza esse método em cada objeto.",

            "natanael" =>
                "A implementação herdada de Object foi substituída. Assim, os elementos da coleção aparecem conforme o estado e a descrição de cada tarefa.",

            "pergunta" =>
                "A classe Tarefa sobrescreveu toString?",

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
                        "pergunta_4_erro_padrao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONSIDEROU APENAS UMA TAREFA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_quantidade" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "As duas tarefas foram adicionadas ao ArrayList. O println da lista mostra todos os elementos armazenados.",

            "natanael" =>
                "A coleção recebeu duas chamadas de add. Portanto sua representação contém tanto tarefa1 quanto tarefa2.",

            "pergunta" =>
                "Quantos objetos foram adicionados ao ArrayList?",

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
                        "pergunta_4_erro_quantidade"
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
                "A primeira tarefa fica com [x] e a segunda com [ ]. O ArrayList junta os dois textos com vírgula e ainda coloca colchetes externos.",

            "natanael" =>
                "Cada objeto fornece sua representação personalizada. A coleção organiza essas representações dentro de seus próprios colchetes.",

            "pergunta" =>
                "Qual é a saída completa da lista?",

            "opcoes" => [

                [
                    "texto" =>
                        "[[x] Estudar Java, [ ] Treinar]",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "[[x] Estudar Java, [x] Treinar]",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_estado"
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
                "toString desbloqueado!",

            "robert" =>
                "Boa po kkkkk. Agora os objetos não precisam mais aparecer como Produto@qualquercoisa. Sobrescrevendo toString, tu escolhe quais atributos formam o texto. O println chama esse método automaticamente, a concatenação com String também usa ele e até o ArrayList aproveita o toString de cada elemento. Isso deixa testes, mensagens e listagens muito mais claros.",

            "natanael" =>
                "Concluímos a personalização da representação textual dos objetos. Sem uma sobrescrita, as classes utilizam a implementação padrão herdada de Object. Ao declarar public String toString, podemos selecionar os atributos e o formato mais adequado. Essa representação é utilizada automaticamente em saídas, concatenações e coleções. Na próxima aula vamos estudar equals e hashCode para entender quando dois objetos devem ser considerados iguais.",

            "mensagem" =>
                "Tu concluiu a quadragésima Aula Conversada."

        ]

    ]

];