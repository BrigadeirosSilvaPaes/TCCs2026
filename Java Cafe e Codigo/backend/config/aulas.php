<?php

return [

    [
        "id" => "fundamentos",
        "numero" => "01",
        "titulo" => "Fundamentos de Java",
        "resumo" => "Aprenda os primeiros conceitos necessários para começar a programar em Java.",
        "icone" => "&lt;/&gt;",

        "aulas" => [

            [
                "numero" => 1,
                "titulo" => "Inserindo texto na tela",
                "descricao" => "Aprenda como utilizar o System.out.println() para exibir informações na tela.",
                "arquivo" => "inserindo_tela.php",
                "conceito" => "System.out.println()"
            ],

            [
                "numero" => 2,
                "titulo" => "Variáveis",
                "descricao" => "Entenda como guardar informações dentro do programa utilizando variáveis.",
                "arquivo" => "variaveis.php",
                "conceito" => "int idade = 17;"
            ],

            [
                "numero" => 3,
                "titulo" => "Tipos de dados",
                "descricao" => "Conheça String, int, double, char e boolean e entenda quando utilizar cada tipo.",
                "arquivo" => "tipos_dados.php",
                "conceito" => "String, int, double"
            ],

            [
                "numero" => 4,
                "titulo" => "Entrada de dados com Scanner",
                "descricao" => "Aprenda como receber informações digitadas pelo usuário.",
                "arquivo" => "scanner.php",
                "conceito" => "Scanner entrada"
            ],

            [
                "numero" => 5,
                "titulo" => "Operadores matemáticos",
                "descricao" => "Realize cálculos utilizando soma, subtração, multiplicação, divisão e resto.",
                "arquivo" => "operadores_matematicos.php",
                "conceito" => "+  -  *  /  %"
            ],

            [
                "numero" => 6,
                "titulo" => "Operadores relacionais",
                "descricao" => "Compare valores e descubra se uma informação é maior, menor ou igual a outra.",
                "arquivo" => "operadores_relacionais.php",
                "conceito" => "==  !=  >  <"
            ],

            [
                "numero" => 7,
                "titulo" => "Operadores lógicos",
                "descricao" => "Combine condições utilizando AND, OR e NOT.",
                "arquivo" => "operadores_logicos.php",
                "conceito" => "&&  ||  !"
            ]

        ]
    ],

    [
        "id" => "decisoes",
        "numero" => "02",
        "titulo" => "Tomando decisões no programa",
        "resumo" => "Ensine o programa a analisar situações e escolher caminhos diferentes.",
        "icone" => "if",

        "aulas" => [

            [
                "numero" => 8,
                "titulo" => "Estrutura if",
                "descricao" => "Execute um comando somente quando determinada condição for verdadeira.",
                "arquivo" => "if.php",
                "conceito" => "if (condicao)"
            ],

            [
                "numero" => 9,
                "titulo" => "If e Else",
                "descricao" => "Crie dois caminhos diferentes para o programa escolher.",
                "arquivo" => "if_else.php",
                "conceito" => "if / else"
            ],

            [
                "numero" => 10,
                "titulo" => "Else If",
                "descricao" => "Trabalhe com várias possibilidades e condições diferentes.",
                "arquivo" => "else_if.php",
                "conceito" => "else if"
            ],

            [
                "numero" => 11,
                "titulo" => "Estrutura Switch",
                "descricao" => "Trabalhe com diferentes opções utilizando case e break.",
                "arquivo" => "switch.php",
                "conceito" => "switch / case"
            ]

        ]
    ],

    [
        "id" => "metodos",
        "numero" => "03",
        "titulo" => "Organizando o código",
        "resumo" => "Divida o programa em partes menores, organizadas e reutilizáveis.",
        "icone" => "( )",

        "aulas" => [

            [
                "numero" => 12,
                "titulo" => "Métodos em Java",
                "descricao" => "Crie métodos para organizar melhor seus programas.",
                "arquivo" => "metodos.php",
                "conceito" => "void executar()"
            ],

            [
                "numero" => 13,
                "titulo" => "Parâmetros e argumentos",
                "descricao" => "Envie informações para dentro dos métodos.",
                "arquivo" => "parametros.php",
                "conceito" => "somar(a, b)"
            ],

            [
                "numero" => 14,
                "titulo" => "Retorno de métodos",
                "descricao" => "Faça um método devolver um resultado para o programa.",
                "arquivo" => "retorno.php",
                "conceito" => "return resultado"
            ]

        ]
    ],

    [
        "id" => "repeticoes",
        "numero" => "04",
        "titulo" => "Fazendo o programa repetir tarefas",
        "resumo" => "Automatize tarefas sem escrever o mesmo código várias vezes.",
        "icone" => "for",

        "aulas" => [

            [
                "numero" => 15,
                "titulo" => "Estrutura de repetição For",
                "descricao" => "Repita comandos quando souber quantas vezes a ação deverá acontecer.",
                "arquivo" => "for.php",
                "conceito" => "for (...)"
            ],

            [
                "numero" => 16,
                "titulo" => "Estrutura While",
                "descricao" => "Repita uma ação enquanto uma condição continuar verdadeira.",
                "arquivo" => "while.php",
                "conceito" => "while (...)"
            ],

            [
                "numero" => 17,
                "titulo" => "Estrutura Do While",
                "descricao" => "Execute uma ação pelo menos uma vez antes de verificar a condição.",
                "arquivo" => "do_while.php",
                "conceito" => "do / while"
            ]

        ]
    ],

    [
        "id" => "colecoes",
        "numero" => "05",
        "titulo" => "Trabalhando com vários dados",
        "resumo" => "Organize conjuntos de informações utilizando arrays e coleções.",
        "icone" => "[ ]",

        "aulas" => [

            [
                "numero" => 18,
                "titulo" => "Arrays",
                "descricao" => "Guarde vários valores relacionados dentro de uma única estrutura.",
                "arquivo" => "arrays.php",
                "conceito" => "int[] numeros"
            ],

            [
                "numero" => 19,
                "titulo" => "Percorrendo Arrays",
                "descricao" => "Acesse cada informação armazenada dentro de um array.",
                "arquivo" => "percorrendo_arrays.php",
                "conceito" => "array[i]"
            ],

            [
                "numero" => 20,
                "titulo" => "ArrayList",
                "descricao" => "Utilize uma estrutura mais flexível para trabalhar com listas.",
                "arquivo" => "arraylist.php",
                "conceito" => "ArrayList<>"
            ]

        ]
    ],

    [
        "id" => "poo",
        "numero" => "06",
        "titulo" => "Programação Orientada a Objetos",
        "resumo" => "Aprenda a construir programas utilizando classes, objetos e responsabilidades.",
        "icone" => "class",

        "aulas" => [

            [
                "numero" => 21,
                "titulo" => "Classes e objetos",
                "descricao" => "Represente elementos do mundo real dentro do programa.",
                "arquivo" => "classes_objetos.php",
                "conceito" => "class Pessoa"
            ],

            [
                "numero" => 22,
                "titulo" => "Atributos e métodos",
                "descricao" => "Defina características e ações pertencentes aos objetos.",
                "arquivo" => "atributos_metodos.php",
                "conceito" => "nome / falar()"
            ],

            [
                "numero" => 23,
                "titulo" => "Construtores",
                "descricao" => "Prepare um objeto no momento em que ele é criado.",
                "arquivo" => "construtores.php",
                "conceito" => "new Pessoa()"
            ],

            [
                "numero" => 24,
                "titulo" => "Encapsulamento",
                "descricao" => "Proteja os dados de um objeto e controle o acesso às informações.",
                "arquivo" => "encapsulamento.php",
                "conceito" => "private"
            ],

            [
                "numero" => 25,
                "titulo" => "Herança",
                "descricao" => "Aproveite características e comportamentos de outra classe.",
                "arquivo" => "heranca.php",
                "conceito" => "extends"
            ],

            [
                "numero" => 26,
                "titulo" => "Polimorfismo",
                "descricao" => "Faça objetos diferentes responderem de maneiras diferentes.",
                "arquivo" => "polimorfismo.php",
                "conceito" => "@Override"
            ],

            [
                "numero" => 27,
                "titulo" => "Interfaces",
                "descricao" => "Crie contratos que definem comportamentos obrigatórios.",
                "arquivo" => "interfaces.php",
                "conceito" => "implements"
            ]

        ]
    ],

    [
        "id" => "erros",
        "numero" => "07",
        "titulo" => "Lidando com erros",
        "resumo" => "Trate situações inesperadas sem deixar o programa simplesmente quebrar.",
        "icone" => "try",

        "aulas" => [

            [
                "numero" => 28,
                "titulo" => "Tratamento de exceções",
                "descricao" => "Entenda como o Java trabalha com erros durante a execução.",
                "arquivo" => "excecoes.php",
                "conceito" => "Exception"
            ],

            [
                "numero" => 29,
                "titulo" => "Try e Catch",
                "descricao" => "Tente executar um código e trate possíveis problemas.",
                "arquivo" => "try_catch.php",
                "conceito" => "try / catch"
            ]

        ]
    ]

];