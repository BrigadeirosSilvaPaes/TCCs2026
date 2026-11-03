<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 3,

    "titulo" =>
        "Nem toda informação é igual",

    "assunto" =>
        "Tipos de dados",

    "descricao" =>
        "Compare diferentes informações e descubra por que o Java precisa saber que tipo de valor está sendo utilizado.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        4,


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
                "Repara nessas informações...",

            "mensagem" =>
                "As três variáveis abaixo guardam coisas diferentes. O que tu acha que int, String e boolean estão indicando?",

            "codigo" => <<<'JAVA'
int idade = 17;

String nome = "Ana";

boolean estudando = true;
JAVA,

            "opcoes" => [

                [
                    "texto" =>
                        "São formas diferentes de organizar informações",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_quase"
                ],

                [
                    "texto" =>
                        "Indicam o tipo de informação que cada variável pode guardar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "São os nomes das variáveis",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nomes"
                ],

                [
                    "texto" =>
                        "Não sei ainda",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — QUASE
        |--------------------------------------------------------------------------
        */

        "pergunta_1_quase" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "quase",

            "robert" =>
                "Isso, tá bem perto. Pensa num formulário: o campo idade espera número, o campo nome espera texto e uma pergunta de sim ou não espera outra coisa. O Java também precisa saber que tipo de informação vai em cada lugar.",

            "natanael" =>
                "Tu já percebeu que essas palavras ajudam a separar informações diferentes. Imagina um formulário de cadastro: no campo idade esperamos um número, no campo nome esperamos um texto e numa pergunta como 'está estudando?' esperamos uma resposta de sim ou não. O Java também precisa saber antecipadamente qual tipo de informação cada espaço deve receber.",

            "pergunta" =>
                "Então int, String e boolean ajudam o Java a identificar o quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "O tipo de valor que a variável pode guardar",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "O nome que será mostrado no console",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nomes"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUSÃO COM NOMES
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_nomes" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Olha a linha de novo kkkkk. O nome da variável é idade. O int vem antes dela. Então int e idade não podem estar fazendo o mesmo trabalho.",

            "natanael" =>
                "Vamos separar as partes. Em int idade = 17, a palavra idade identifica aquela variável. Já int aparece antes do nome e está descrevendo alguma característica do valor que poderá ficar armazenado ali. As duas partes têm funções diferentes.",

            "codigo" => <<<'JAVA'
int idade = 17;
JAVA,

            "pergunta" =>
                "Se idade é o nome da variável, o que int parece estar dizendo sobre o valor 17?",

            "opcoes" => [

                [
                    "texto" =>
                        "Que existe um tipo de informação esperado",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_quase"
                ],

                [
                    "texto" =>
                        "Que int também é o nome da variável",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nomes"
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
                "Suave. Pensa numa gaveta de talheres. Tu não vai guardar café líquido ali kkkkk. Cada lugar foi pensado pra um tipo de coisa. No Java acontece uma ideia parecida.",

            "natanael" =>
                "Vamos pensar fora da programação. Em uma casa, diferentes recipientes existem para diferentes tipos de coisas: uma garrafa recebe líquido, uma gaveta pode receber objetos e uma carteira guarda documentos ou dinheiro. O Java também precisa organizar as informações sabendo que tipo de valor será colocado em cada variável.",

            "pergunta" =>
                "Então essas palavras antes do nome da variável parecem definir o quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "Que tipo de informação pode ser armazenada",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_quase"
                ],

                [
                    "texto" =>
                        "Onde a informação aparece na tela",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_nomes"
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
                "Texto ou só uma letra?",

            "mensagem" =>
                "Agora observa uma diferença pequena, mas importante.",

            "codigo" => <<<'JAVA'
String linguagem = "Java";

char inicial = 'J';
JAVA,

            "pergunta" =>
                "Qual diferença tu percebe entre String e char nesse exemplo?",

            "opcoes" => [

                [
                    "texto" =>
                        "String pode representar um texto e char representa um único caractere",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "String e char fazem exatamente a mesma coisa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_iguais"
                ],

                [
                    "texto" =>
                        "char é usado somente para números",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_numero"
                ],

                [
                    "texto" =>
                        "Não sei",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — STRING E CHAR IGUAIS
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_iguais" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Parecem parecidos, mas olha o tamanho da informação. Java tem quatro letras. J é só uma. É tipo comparar teu nome inteiro com só tua inicial.",

            "natanael" =>
                "Os dois trabalham com caracteres, mas existe uma diferença importante na quantidade. Pensa em uma ficha de cadastro: um campo pode pedir teu nome completo, enquanto outro pede somente a inicial do teu nome. String consegue representar uma sequência de caracteres; char representa apenas um caractere.",

            "pergunta" =>
                "Se eu quiser guardar somente a letra R, qual ideia faz mais sentido?",

            "opcoes" => [

                [
                    "texto" =>
                        "Usar algo pensado para um único caractere",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Usar obrigatoriamente um número",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_numero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CHAR COMO NÚMERO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_numero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aí não kkkkk. Olha o valor do char: 'J'. É uma letra. O detalhe é que ele guarda um caractere só.",

            "natanael" =>
                "Observa o próprio exemplo: o valor colocado em inicial é a letra J. Portanto char não está limitado a números. A principal ideia aqui é que ele representa um único caractere, como uma letra, um símbolo ou outro caractere individual.",

            "codigo" => <<<'JAVA'
char inicial = 'J';
JAVA,

            "pergunta" =>
                "Quantos caracteres estão sendo guardados aqui?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "Vários",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_iguais"
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
                "Pensa assim: String seria teu nome completo. char seria só tua inicial. Um guarda uma sequência, o outro só um caractere.",

            "natanael" =>
                "Usa a comparação de uma ficha. No campo nome completo poderíamos guardar Robert. Em outro campo chamado inicial, guardaríamos apenas R. O primeiro precisa comportar uma sequência de caracteres; o segundo precisa somente de um caractere.",

            "pergunta" =>
                "Qual opção representa melhor essa diferença?",

            "opcoes" => [

                [
                    "texto" =>
                        "String para texto e char para um único caractere",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "Os dois sempre guardam a mesma coisa",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_iguais"
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
                "E quando aparece uma vírgula?",

            "mensagem" =>
                "Agora temos dois valores numéricos diferentes.",

            "codigo" => <<<'JAVA'
int alunos = 30;

double temperatura = 21.5;
JAVA,

            "pergunta" =>
                "Por que temperatura usa double em vez de int?",

            "opcoes" => [

                [
                    "texto" =>
                        "Porque double consegue representar valores com parte decimal",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "Porque int serve apenas para textos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_int"
                ],

                [
                    "texto" =>
                        "Porque todo número precisa ser double",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_tudo_double"
                ],

                [
                    "texto" =>
                        "Não sei",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUSÃO COM INT
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_int" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "int não é texto não kkkkk. Olha o exemplo: alunos vale 30. Ele combina bem com quantidade inteira. Não existe 30,5 alunos numa turma.",

            "natanael" =>
                "O int também representa números. A diferença é que ele é usado para valores inteiros, sem parte fracionária. Quantidade de alunos é um bom exemplo: podemos ter 30 alunos, mas não faria sentido dizer que existem 30,5 alunos presentes.",

            "pergunta" =>
                "Temperatura pode ter valores como 21.5, 18.7 ou 30.2?",

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
                        "Não, só pode ser inteira",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_int"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — TUDO DOUBLE
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_tudo_double" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Não precisa usar double pra tudo. Pensa em quantidade de irmãos: 2. Agora pensa em altura: 1.68. São números, mas têm necessidades diferentes.",

            "natanael" =>
                "Nem todo número precisa de parte decimal. Pensa em duas situações: quantidade de cadeiras em uma sala e altura de uma pessoa. Podemos ter 30 cadeiras, um valor inteiro. Já uma altura pode ser 1.68. O tipo escolhido deve combinar com o tipo de valor que precisamos representar.",

            "pergunta" =>
                "Qual desses exemplos precisa representar uma parte decimal?",

            "opcoes" => [

                [
                    "texto" =>
                        "Uma temperatura de 21.5 graus",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "Uma turma com 30 alunos",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_tudo_double"
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
                "Pensa em quantidade e medida. 30 alunos é inteiro. 21.5 graus tem uma parte depois do ponto. É aí que muda.",

            "natanael" =>
                "Uma forma simples de separar é pensar em contagens e medições. Uma contagem normalmente pode ser representada por um valor inteiro: 30 alunos, 5 computadores, 2 irmãos. Já medidas como temperatura, altura ou preço podem precisar de uma parte decimal.",

            "pergunta" =>
                "Então qual tipo faz mais sentido para 21.5?",

            "opcoes" => [

                [
                    "texto" =>
                        "double",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "int",

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
                "Agora só existem dois caminhos...",

            "mensagem" =>
                "Olha a variável abaixo e pensa no tipo de informação que ela representa.",

            "codigo" => <<<'JAVA'
boolean portaAberta = false;
JAVA,

            "pergunta" =>
                "O que o valor false representa nesse exemplo?",

            "opcoes" => [

                [
                    "texto" =>
                        "Um estado lógico: a porta não está aberta",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "O texto escrito false",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_texto"
                ],

                [
                    "texto" =>
                        "Um número decimal",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_numero"
                ],

                [
                    "texto" =>
                        "Não sei",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — FALSE COMO TEXTO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_texto" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Se fosse texto teria aspas, tipo \"false\". Aqui não tem. Pensa num interruptor: ligado ou desligado. boolean trabalha mais nessa ideia.",

            "natanael" =>
                "Existe uma diferença visual importante. Um texto normalmente apareceria entre aspas, como \"false\". Aqui false aparece diretamente porque representa um valor lógico. Podemos comparar com um interruptor, que possui dois estados possíveis: ligado ou desligado.",

            "pergunta" =>
                "Nesse exemplo da porta, quais seriam os dois estados possíveis?",

            "opcoes" => [

                [
                    "texto" =>
                        "Aberta ou fechada",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "10 ou 20",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_numero"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — FALSE COMO NÚMERO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_numero" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Aqui não tem medida nem conta. É só uma pergunta de sim ou não: a porta está aberta? false quer dizer que não.",

            "natanael" =>
                "Nesse caso não estamos representando quantidade nem medida. A variável responde uma pergunta que possui apenas dois estados possíveis: a porta está aberta ou não está. Valores boolean são usados justamente para representar decisões ou estados desse tipo.",

            "pergunta" =>
                "Se portaAberta fosse true, o que isso indicaria?",

            "opcoes" => [

                [
                    "texto" =>
                        "Que a porta está aberta",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "Que a porta vale o número 1.5",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_numero"
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
                "Pensa num interruptor: ligado ou desligado. Ou numa pergunta: sim ou não. boolean trabalha com essa ideia de dois estados.",

            "natanael" =>
                "Imagina situações que possuem apenas duas possibilidades: uma lâmpada pode estar ligada ou desligada, uma porta pode estar aberta ou fechada, um usuário pode estar autenticado ou não. O boolean representa justamente esse tipo de informação usando true e false.",

            "pergunta" =>
                "Então false em portaAberta significa o quê?",

            "opcoes" => [

                [
                    "texto" =>
                        "Que a condição porta aberta é falsa",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "Que false é um texto qualquer",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_texto"
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
                "Mais um conceito desbloqueado!",

            "robert" =>
                "Boa po kkkkk. Guarda esse resumão: int pra inteiro, double quando precisa de decimal, char pra um caractere, boolean pra verdadeiro ou falso e String pra texto. É tipo escolher o recipiente certo pra cada informação. Agora já dá pra começar a fazer umas contas com esses valores.",

            "natanael" =>
                "Fechamos essa etapa entendendo por que o tipo da informação importa. int representa números inteiros; double permite valores com parte decimal; char representa um único caractere; boolean trabalha com os estados true e false; e String é o tipo utilizado para sequências de caracteres, ou seja, textos. Uma observação importante: int, double, char e boolean são tipos primitivos do Java, enquanto String é uma classe usada para representar textos. Na próxima etapa vamos começar a utilizar alguns desses valores em operações.",

            "mensagem" =>
                "Tu concluiu a terceira Aula Conversada."

        ]

    ]

];