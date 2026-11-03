<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 39,

    "titulo" =>
        "Organizando classes e controlando acessos",

    "assunto" =>
        "Pacotes e modificadores de acesso",

    "descricao" =>
        "Aprenda a organizar classes em pacotes, utilizar import e controlar o acesso com public, private, protected e acesso de pacote.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        40,


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
                "Utilizando uma classe de outro pacote...",

            "mensagem" =>
                "As classes Produto e Main estão em pacotes diferentes. O import permite que Main utilize Produto pelo seu nome simples.",

            "codigo" => <<<'JAVA'
// Arquivo: modelo/Produto.java

package modelo;

public class Produto {

    private String nome;


    public Produto(String nome) {

        this.nome = nome;

    }


    public String getNome() {

        return nome;

    }

}


// Arquivo: app/Main.java

package app;

import modelo.Produto;

public class Main {

    public static void main(String[] args) {

        Produto produto =
            new Produto("Café");

        System.out.println(
            produto.getNome()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual texto aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "Café",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "Produto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
                ],

                [
                    "texto" =>
                        "modelo.Produto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_pacote"
                ],

                [
                    "texto" =>
                        "O código não funciona porque as classes estão em pacotes diferentes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_import"
                ],

                [
                    "texto" =>
                        "Não sei para que serve o import",

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
                "Produto é o tipo da variável, po. O objeto recebeu o nome Café e getNome devolve esse valor.",

            "natanael" =>
                "Produto representa a classe utilizada na criação do objeto. A saída consulta o atributo nome por meio do método getNome, que devolve a String Café.",

            "pergunta" =>
                "Qual valor foi enviado ao construtor de Produto?",

            "opcoes" => [

                [
                    "texto" =>
                        "Café",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Produto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE MOSTRA O NOME DO PACOTE
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_pacote" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "modelo.Produto identifica onde a classe está. Só que o println não mostra o pacote. Ele mostra o retorno de produto.getNome().",

            "natanael" =>
                "O nome do pacote participa da organização e da identificação da classe. Entretanto a saída do programa é determinada pelo valor devolvido pelo método getNome.",

            "pergunta" =>
                "Qual método é enviado ao println?",

            "opcoes" => [

                [
                    "texto" =>
                        "produto.getNome()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "modelo.Produto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_pacote"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE PACOTES DIFERENTES IMPEDEM O USO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_import" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Classes de pacotes diferentes podem trabalhar juntas. Produto é public e foi importada com import modelo.Produto.",

            "natanael" =>
                "A separação em pacotes não impede a colaboração. Como a classe Produto é pública, Main pode importá-la e utilizá-la normalmente.",

            "codigo" => <<<'JAVA'
import modelo.Produto;
JAVA,

            "pergunta" =>
                "Qual classe esse import disponibiliza para Main?",

            "opcoes" => [

                [
                    "texto" =>
                        "Produto",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "Somente Main",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_import"
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
                "Pensa nos pacotes como pastas organizadas. Produto está na pasta modelo e Main está na pasta app. O import avisa ao Main onde encontrar Produto.",

            "natanael" =>
                "O pacote identifica o endereço lógico da classe. A declaração import permite utilizar uma classe localizada em outro pacote sem repetir seu endereço completo em cada linha.",

            "pergunta" =>
                "Em qual pacote está a classe Produto?",

            "opcoes" => [

                [
                    "texto" =>
                        "modelo",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "app",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_nao_sei"
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
                "Main importa Produto, cria um objeto com o nome Café e chama getNome. O método devolve exatamente Café.",

            "natanael" =>
                "O import permite reconhecer a classe. O construtor armazena a String recebida e o getter devolve esse conteúdo para a saída.",

            "pergunta" =>
                "Qual é a saída final?",

            "opcoes" => [

                [
                    "texto" =>
                        "Café",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "modelo.Produto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_pacote"
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
                "Uma classe sem o modificador public...",

            "mensagem" =>
                "Produto está em outro pacote, mas sua declaração não possui o modificador public.",

            "codigo" => <<<'JAVA'
// Arquivo: modelo/Produto.java

package modelo;

class Produto {

    String nome;


    Produto(String nome) {

        this.nome = nome;

    }

}


// Arquivo: app/Main.java

package app;

import modelo.Produto;

public class Main {

    public static void main(String[] args) {

        Produto produto =
            new Produto("Café");

    }

}
JAVA,

            "pergunta" =>
                "O que acontece com esse código?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não compila porque Produto não é public e só pode ser utilizada dentro do pacote modelo",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Compila normalmente porque existe um import",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_import"
                ],

                [
                    "texto" =>
                        "Compila porque todas as classes são públicas automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_automatico"
                ],

                [
                    "texto" =>
                        "Compila, mas produto recebe null",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_null"
                ],

                [
                    "texto" =>
                        "Não sei o que significa não colocar um modificador",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE IMPORT IGNORA O MODIFICADOR
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_import" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O import encontra a classe, mas não muda a permissão dela. Sem public, Produto continua presa ao pacote modelo.",

            "natanael" =>
                "Importar uma classe não modifica seu nível de acesso. A declaração continua com acesso de pacote e não pode ser utilizada pelo pacote app.",

            "pergunta" =>
                "O import altera o modificador de acesso da classe?",

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
                        "Sim, transforma a classe em public",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_import"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE CLASSES SÃO PÚBLICAS AUTOMATICAMENTE
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_automatico" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Quando a classe não possui public, ela não vira pública automaticamente. Ela recebe acesso de pacote.",

            "natanael" =>
                "A ausência de um modificador produz o acesso conhecido como package-private. A classe fica disponível somente para outras classes do mesmo pacote.",

            "pergunta" =>
                "Em qual pacote Produto pode ser utilizada sem ser public?",

            "opcoes" => [

                [
                    "texto" =>
                        "Somente no pacote modelo",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Em qualquer pacote",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_automatico"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O PROBLEMA APARECE NA EXECUÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_null" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O programa nem chega a executar. Main tenta usar uma classe que não está acessível, então o erro aparece na compilação.",

            "natanael" =>
                "A verificação de acesso ocorre durante a compilação. Como Produto não está visível para app, nenhum objeto é criado.",

            "pergunta" =>
                "O erro acontece durante a compilação ou execução?",

            "opcoes" => [

                [
                    "texto" =>
                        "Durante a compilação",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Somente durante a execução",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_null"
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
                "Sem modificador, a classe funciona como algo reservado pro próprio pacote. É como uma sala que só pode ser usada por quem já está dentro do setor modelo.",

            "natanael" =>
                "A ausência de public limita a visibilidade da classe. Somente códigos pertencentes ao mesmo pacote conseguem utilizá-la diretamente.",

            "pergunta" =>
                "Main está no mesmo pacote de Produto?",

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
                "Produto não possui public. Por isso só as classes do pacote modelo podem usar ela. Main está em app, então ocorre erro de compilação.",

            "natanael" =>
                "A classe possui acesso de pacote. Como a tentativa de uso acontece em outro pacote, a declaração não está visível para Main.",

            "pergunta" =>
                "Qual correção permite que app utilize Produto?",

            "opcoes" => [

                [
                    "texto" =>
                        "Declarar public class Produto",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Apenas repetir o import",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_import"
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
                "Protegendo um atributo com private...",

            "mensagem" =>
                "O atributo saldo só pode ser acessado diretamente dentro da classe Conta. Outras classes utilizam métodos públicos.",

            "codigo" => <<<'JAVA'
package banco;

public class Conta {

    private double saldo;


    public Conta(double saldoInicial) {

        saldo = saldoInicial;

    }


    public void depositar(
        double valor
    ) {

        if (valor > 0) {

            saldo =
                saldo + valor;

        }

    }


    public double getSaldo() {

        return saldo;

    }

}


// Arquivo: app/Main.java

package app;

import banco.Conta;

public class Main {

    public static void main(String[] args) {

        Conta conta =
            new Conta(100.0);

        conta.depositar(50.0);

        conta.depositar(-20.0);

        System.out.println(
            conta.getSaldo()
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual valor aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "150.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "130.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_negativo"
                ],

                [
                    "texto" =>
                        "100.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_deposito"
                ],

                [
                    "texto" =>
                        "O código não funciona porque saldo é private",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_private"
                ],

                [
                    "texto" =>
                        "Não sei como acessar um atributo private",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONSIDEROU O DEPÓSITO NEGATIVO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_negativo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O depósito de -20 não passa no if. Como -20 não é maior que zero, o saldo não muda nessa chamada.",

            "natanael" =>
                "O método valida o argumento antes de realizar a alteração. A condição valor > 0 é falsa para -20.0, portanto nenhuma subtração acontece.",

            "pergunta" =>
                "A expressão -20.0 > 0 é verdadeira?",

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
                        "pergunta_3_erro_negativo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE O DEPÓSITO POSITIVO NÃO FUNCIONA
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_deposito" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O primeiro depósito recebe 50, que é maior que zero. Então o método soma esse valor ao saldo de 100.",

            "natanael" =>
                "A primeira chamada satisfaz a validação. O método executa saldo = saldo + valor, alterando o estado da conta para 150.0.",

            "pergunta" =>
                "Quanto resulta 100.0 + 50.0?",

            "opcoes" => [

                [
                    "texto" =>
                        "150.0",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "100.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_deposito"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE PRIVATE IMPEDE QUALQUER USO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_private" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "private impede acesso direto ao atributo, não impede usar a classe. Main trabalha com métodos públicos como depositar e getSaldo.",

            "natanael" =>
                "O encapsulamento protege o campo interno. A classe continua oferecendo operações públicas controladas para consultar e modificar seu estado.",

            "codigo" => <<<'JAVA'
conta.depositar(50.0);
conta.getSaldo();
JAVA,

            "pergunta" =>
                "Os métodos depositar e getSaldo foram declarados como public?",

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
        | PERGUNTA 3 — NÃO SEI
        |--------------------------------------------------------------------------
        */

        "pergunta_3_nao_sei" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Pensa no saldo como dinheiro dentro de um caixa fechado. Main não mexe direto lá dentro. Ele pede pra Conta depositar ou informar o saldo.",

            "natanael" =>
                "O atributo private fica protegido pela própria classe. As demais classes utilizam métodos públicos, que podem aplicar validações antes de permitir alterações.",

            "pergunta" =>
                "Qual método público devolve o saldo?",

            "opcoes" => [

                [
                    "texto" =>
                        "getSaldo()",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "main() altera saldo diretamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_private"
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
                "A conta começa com 100. O depósito de 50 é aceito e leva pra 150. O depósito negativo é ignorado. getSaldo devolve 150.",

            "natanael" =>
                "O acesso ao estado acontece por métodos públicos. Apenas o valor positivo é aprovado pela regra de depósito, mantendo o saldo final em 150.0.",

            "pergunta" =>
                "Qual é o saldo final?",

            "opcoes" => [

                [
                    "texto" =>
                        "150.0",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "130.0",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_negativo"
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
                "Protected e acesso de pacote...",

            "mensagem" =>
                "Gerente herda Funcionario em outro pacote. O atributo nome é protected, mas codigo não possui modificador.",

            "codigo" => <<<'JAVA'
// Arquivo: empresa/Funcionario.java

package empresa;

public class Funcionario {

    protected String nome;

    int codigo;


    public Funcionario(
        String nome,
        int codigo
    ) {

        this.nome = nome;

        this.codigo = codigo;

    }

}


// Arquivo: administracao/Gerente.java

package administracao;

import empresa.Funcionario;

public class Gerente extends Funcionario {

    public Gerente(
        String nome,
        int codigo
    ) {

        super(
            nome,
            codigo
        );

    }


    public void exibirDados() {

        System.out.println(
            nome
        );

        System.out.println(
            codigo
        );

    }

}
JAVA,

            "pergunta" =>
                "O que acontece com a classe Gerente?",

            "opcoes" => [

                [
                    "texto" =>
                        "Não compila por causa do acesso direto a codigo, que só está disponível no pacote empresa",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Não compila porque nome é protected",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_protected"
                ],

                [
                    "texto" =>
                        "Compila e apresenta nome e codigo normalmente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_pacote"
                ],

                [
                    "texto" =>
                        "Não compila porque uma classe de outro pacote não pode utilizar extends",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_heranca"
                ],

                [
                    "texto" =>
                        "Os dois atributos são private automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_private"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE PROTECTED NÃO É ACESSÍVEL
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_protected" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "nome é protected justamente pra permitir acesso na classe filha. Mesmo Gerente estando em outro pacote, ela pode usar o atributo herdado.",

            "natanael" =>
                "O modificador protected permite o acesso por subclasses. Como Gerente herda Funcionario, a utilização direta do atributo nome é permitida.",

            "pergunta" =>
                "Gerente é uma subclasse de Funcionario?",

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
                        "pergunta_4_erro_protected"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE CODIGO É VISÍVEL EM OUTRO PACOTE
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_pacote" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "codigo não possui public, protected nem private. Então ele tem acesso de pacote e só pode ser usado diretamente dentro de empresa.",

            "natanael" =>
                "A ausência de modificador cria acesso package-private. Mesmo existindo herança, esse membro não fica visível para uma subclasse localizada em outro pacote.",

            "codigo" => <<<'JAVA'
int codigo;
JAVA,

            "pergunta" =>
                "Gerente está no pacote empresa ou administracao?",

            "opcoes" => [

                [
                    "texto" =>
                        "administracao",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "empresa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_pacote"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE HERANÇA ENTRE PACOTES NÃO É PERMITIDA
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_heranca" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Pode usar extends entre pacotes, desde que a classe-base esteja acessível. Funcionario é public e foi importada.",

            "natanael" =>
                "A herança não exige que as classes pertençam ao mesmo pacote. A classe pública Funcionario pode ser importada e estendida normalmente.",

            "pergunta" =>
                "Funcionario foi declarada como public?",

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
                        "pergunta_4_erro_heranca"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — ACHA QUE SEM MODIFICADOR VIRA PRIVATE
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_private" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Sem modificador não significa private. codigo possui acesso de pacote. Ele pode ser usado por outras classes de empresa, mas não por administracao.",

            "natanael" =>
                "Private e acesso de pacote são diferentes. Um membro private pertence somente à própria classe. Um membro sem modificador pode ser acessado pelas classes do mesmo pacote.",

            "pergunta" =>
                "codigo poderia ser acessado por outra classe localizada no pacote empresa?",

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
                        "Não, porque é private",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_private"
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
                "Gerente pode acessar nome porque é uma subclasse e nome é protected. Mas codigo não tem modificador, então fica preso ao pacote empresa.",

            "natanael" =>
                "A herança permite o acesso ao membro protegido. Entretanto o membro com acesso de pacote não está disponível fora do pacote em que foi declarado.",

            "pergunta" =>
                "Qual atributo provoca o erro de compilação?",

            "opcoes" => [

                [
                    "texto" =>
                        "codigo",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "nome",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_protected"
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
                "Pacotes e acessos desbloqueados!",

            "robert" =>
                "Boa po kkkkk. Agora tu consegue separar as classes em pacotes sem deixar tudo jogado na mesma pasta. package informa onde a classe está e import permite usar uma classe de outro pacote. public libera o acesso geral, private protege dentro da própria classe, protected também permite acesso pelas subclasses e, sem modificador, o acesso fica limitado ao pacote. O import encontra a classe, mas não ignora essas permissões.",

            "natanael" =>
                "Concluímos a organização por pacotes e os principais modificadores de acesso. A declaração package identifica o espaço ao qual uma classe pertence, enquanto import permite utilizar tipos localizados em outros pacotes. Public oferece acesso amplo, private restringe o membro à própria classe, protected permite o uso no mesmo pacote e por subclasses, e a ausência de modificador limita o acesso ao pacote. Essas regras ajudam a proteger informações internas e a definir quais partes de uma classe fazem parte de sua interface pública. Na próxima aula vamos personalizar a representação textual dos objetos com o método toString.",

            "mensagem" =>
                "Tu concluiu a trigésima nona Aula Conversada."

        ]

    ]

];