<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES DA AULA
    |--------------------------------------------------------------------------
    */

    "id" => 34,

    "titulo" =>
        "Trabalhando com datas e horários",

    "assunto" =>
        "LocalDate e LocalDateTime",

    "descricao" =>
        "Aprenda a criar datas, acrescentar dias e horas e apresentar informações em formatos mais fáceis de entender.",


    /*
    |--------------------------------------------------------------------------
    | NAVEGAÇÃO
    |--------------------------------------------------------------------------
    */

    "proxima_aula" =>
        35,


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
                "Criando uma data específica...",

            "mensagem" =>
                "A classe LocalDate representa uma data sem guardar informações de horário.",

            "codigo" => <<<'JAVA'
import java.time.LocalDate;

public class Main {

    public static void main(String[] args) {

        LocalDate data =
            LocalDate.of(
                2026,
                8,
                4
            );

        System.out.println(
            data
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual valor aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "2026-08-04",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "04/08/2026",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_formato"
                ],

                [
                    "texto" =>
                        "2026-04-08",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ordem"
                ],

                [
                    "texto" =>
                        "2026-08-04 00:00",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_horario"
                ],

                [
                    "texto" =>
                        "Não sei como LocalDate.of funciona",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU O FORMATO PADRÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_formato" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "A data representa 4 de agosto, mas o println usa o formato padrão do LocalDate: ano, mês e dia. Por isso aparece 2026-08-04.",

            "natanael" =>
                "O conteúdo da data está correto, porém nenhuma formatação personalizada foi aplicada. LocalDate utiliza por padrão a sequência ano-mês-dia, seguindo o formato ISO.",

            "pergunta" =>
                "No formato padrão, qual informação aparece primeiro?",

            "opcoes" => [

                [
                    "texto" =>
                        "O ano",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "O dia",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_formato"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — CONFUNDIU MÊS E DIA
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_ordem" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "No LocalDate.of, a ordem é ano, mês e dia. Então o 8 representa agosto e o 4 representa o dia.",

            "natanael" =>
                "Os argumentos seguem a ordem year, month e dayOfMonth. Dessa forma, 2026 representa o ano, 8 representa o mês e 4 representa o dia.",

            "codigo" => <<<'JAVA'
LocalDate.of(
    2026, // ano
    8,    // mês
    4     // dia
);
JAVA,

            "pergunta" =>
                "Qual número representa o mês?",

            "opcoes" => [

                [
                    "texto" =>
                        "8",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "4",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ordem"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 1 — ACHA QUE LOCALDATE GUARDA HORÁRIO
        |--------------------------------------------------------------------------
        */

        "pergunta_1_erro_horario" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "LocalDate guarda só a data, po. Não tem hora, minuto nem segundo dentro desse objeto.",

            "natanael" =>
                "A responsabilidade de LocalDate é representar apenas ano, mês e dia. Para armazenar também um horário, podemos utilizar LocalDateTime.",

            "pergunta" =>
                "LocalDate guarda horas e minutos?",

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
                        "Sim, sempre começa em 00:00",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_horario"
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
                "Pensa numa data escrita no calendário. Primeiro tu informa o ano, depois o mês e por último o dia. LocalDate guarda exatamente isso.",

            "natanael" =>
                "Imagine o preenchimento de três campos: ano, mês e dia. O método of recebe essas informações e cria um objeto que representa aquela data específica.",

            "pergunta" =>
                "Qual data foi criada no código?",

            "opcoes" => [

                [
                    "texto" =>
                        "4 de agosto de 2026",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_1_dica"
                ],

                [
                    "texto" =>
                        "8 de abril de 2026",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_ordem"
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
                "O código cria 4 de agosto de 2026. Como não tem formatação personalizada, o Java mostra ano-mês-dia.",

            "natanael" =>
                "A data criada possui ano 2026, mês 8 e dia 4. Ao apresentar diretamente o LocalDate, o formato padrão organiza essas partes como ano-mês-dia.",

            "pergunta" =>
                "Qual será a saída completa?",

            "opcoes" => [

                [
                    "texto" =>
                        "2026-08-04",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_2"
                ],

                [
                    "texto" =>
                        "04/08/2026",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_1_erro_formato"
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
                "Acrescentando dias a uma data...",

            "mensagem" =>
                "O método plusDays cria uma nova data, sem alterar o objeto original.",

            "codigo" => <<<'JAVA'
import java.time.LocalDate;

public class Main {

    public static void main(String[] args) {

        LocalDate hoje =
            LocalDate.of(
                2026,
                8,
                4
            );

        LocalDate prazo =
            hoje.plusDays(7);

        System.out.println(
            hoje
        );

        System.out.println(
            prazo
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "2026-08-04 e depois 2026-08-11",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "2026-08-11 duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_original"
                ],

                [
                    "texto" =>
                        "2026-08-04 duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_retorno"
                ],

                [
                    "texto" =>
                        "2026-08-04 e depois 2026-08-07",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_soma"
                ],

                [
                    "texto" =>
                        "Não sei como plusDays funciona",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE ALTERA O OBJETO ORIGINAL
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_original" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "LocalDate é imutável. plusDays não muda hoje por dentro. Ele cria outra data e entrega ela pra variável prazo.",

            "natanael" =>
                "Assim como String, LocalDate é imutável. A operação produz um novo objeto representando a data calculada, enquanto a referência hoje continua apontando para a data original.",

            "pergunta" =>
                "Qual variável recebe o resultado de hoje.plusDays(7)?",

            "opcoes" => [

                [
                    "texto" =>
                        "prazo",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "hoje é alterada automaticamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_original"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — ACHA QUE O RESULTADO NÃO FOI GUARDADO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_retorno" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O resultado não foi ignorado. A nova data foi guardada em prazo. Por isso o segundo println mostra uma data diferente.",

            "natanael" =>
                "A chamada plusDays devolve um LocalDate. Como esse retorno participa de uma atribuição, a data calculada permanece disponível na variável prazo.",

            "codigo" => <<<'JAVA'
LocalDate prazo =
    hoje.plusDays(7);
JAVA,

            "pergunta" =>
                "A variável prazo guarda a data original ou a data com sete dias acrescentados?",

            "opcoes" => [

                [
                    "texto" =>
                        "A data com sete dias acrescentados",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "A data original sem nenhuma alteração",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_retorno"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 2 — CONFUNDIU O VALOR SOMADO
        |--------------------------------------------------------------------------
        */

        "pergunta_2_erro_soma" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O 7 não vira o novo dia do mês. Ele representa sete dias inteiros acrescentados ao dia 4. Quatro mais sete chega no dia 11.",

            "natanael" =>
                "plusDays utiliza o argumento como quantidade a ser somada. A contagem começa a partir da data original, produzindo 11 de agosto.",

            "pergunta" =>
                "Quanto resulta acrescentar sete dias ao dia 4 de agosto?",

            "opcoes" => [

                [
                    "texto" =>
                        "11 de agosto",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "7 de agosto",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_soma"
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
                "Pensa num prazo de uma semana. Se hoje é dia 4, sete dias depois é dia 11. Mas a data de hoje continua sendo dia 4.",

            "natanael" =>
                "Imagine duas anotações no calendário: a data atual e uma data de prazo. A segunda é calculada a partir da primeira, mas ambas continuam disponíveis separadamente.",

            "pergunta" =>
                "Qual é a data do prazo?",

            "opcoes" => [

                [
                    "texto" =>
                        "11 de agosto de 2026",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_2_dica"
                ],

                [
                    "texto" =>
                        "4 de agosto de 2026",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_retorno"
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
                "hoje continua no dia 4. plusDays cria uma nova data sete dias depois, que fica no dia 11.",

            "natanael" =>
                "O primeiro objeto mantém seu valor original. O retorno da operação representa uma semana depois e é armazenado em outra variável.",

            "pergunta" =>
                "Qual sequência completa será mostrada?",

            "opcoes" => [

                [
                    "texto" =>
                        "2026-08-04 → 2026-08-11",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_3"
                ],

                [
                    "texto" =>
                        "2026-08-11 → 2026-08-11",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_2_erro_original"
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
                "Guardando uma data junto com um horário...",

            "mensagem" =>
                "LocalDateTime representa ano, mês, dia, hora e minuto dentro do mesmo objeto.",

            "codigo" => <<<'JAVA'
import java.time.LocalDateTime;

public class Main {

    public static void main(String[] args) {

        LocalDateTime inicio =
            LocalDateTime.of(
                2026,
                8,
                4,
                15,
                30
            );

        LocalDateTime fim =
            inicio.plusHours(2);

        System.out.println(
            inicio
        );

        System.out.println(
            fim
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual sequência aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "2026-08-04T15:30 e depois 2026-08-04T17:30",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "2026-08-04T17:30 duas vezes",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_original"
                ],

                [
                    "texto" =>
                        "2026-08-04T15:30 e depois 2026-08-04T15:32",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_minutos"
                ],

                [
                    "texto" =>
                        "2026-08-04 e depois 2026-08-04",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_horario"
                ],

                [
                    "texto" =>
                        "Não sei como LocalDateTime funciona",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_nao_sei"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ACHA QUE ALTERA O ORIGINAL
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_original" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "LocalDateTime também é imutável. inicio continua em 15:30. O horário com duas horas a mais fica na variável fim.",

            "natanael" =>
                "A operação plusHours produz um novo objeto. A variável inicio mantém o valor utilizado na criação, enquanto fim recebe o resultado calculado.",

            "pergunta" =>
                "Qual variável recebeu o horário com duas horas acrescentadas?",

            "opcoes" => [

                [
                    "texto" =>
                        "fim",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "inicio foi modificada diretamente",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_original"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — CONFUNDIU HORAS COM MINUTOS
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_minutos" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O método chamado é plusHours, então soma horas, não minutos. Duas horas depois de 15:30 é 17:30.",

            "natanael" =>
                "O nome do método indica a unidade utilizada. plusHours acrescenta horas completas e mantém os minutos originais quando não existe mudança de dia.",

            "pergunta" =>
                "Qual horário fica duas horas depois de 15:30?",

            "opcoes" => [

                [
                    "texto" =>
                        "17:30",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "15:32",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_minutos"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 3 — ESQUECEU O HORÁRIO
        |--------------------------------------------------------------------------
        */

        "pergunta_3_erro_horario" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Esse não é LocalDate. É LocalDateTime. O Time no nome já avisa que ele guarda horário junto com a data.",

            "natanael" =>
                "LocalDateTime combina duas informações: a parte de data e a parte de tempo. Por isso a apresentação contém também hora e minuto.",

            "pergunta" =>
                "Quais valores representam a hora e o minuto no método of?",

            "opcoes" => [

                [
                    "texto" =>
                        "15 e 30",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "8 e 4",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_horario"
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
                "Pensa num compromisso marcado: dia 4 de agosto às 15:30. LocalDateTime guarda essas duas partes juntas. Depois o código calcula o horário duas horas mais tarde.",

            "natanael" =>
                "Enquanto LocalDate representa apenas um dia no calendário, LocalDateTime também registra o momento dentro daquele dia. Neste exemplo, o horário inicial é 15:30.",

            "pergunta" =>
                "Qual é o horário inicial do objeto?",

            "opcoes" => [

                [
                    "texto" =>
                        "15:30",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_3_dica"
                ],

                [
                    "texto" =>
                        "17:30",

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
                "inicio guarda 4 de agosto às 15:30. plusHours(2) cria outro valor no mesmo dia, mas às 17:30.",

            "natanael" =>
                "A data permanece igual porque o acréscimo não atravessa a meia-noite. Apenas a hora muda de quinze para dezessete.",

            "pergunta" =>
                "Qual sequência completa será apresentada?",

            "opcoes" => [

                [
                    "texto" =>
                        "2026-08-04T15:30 → 2026-08-04T17:30",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "pergunta_4"
                ],

                [
                    "texto" =>
                        "2026-08-04T15:30 → 2026-08-04T15:32",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_3_erro_minutos"
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
                "Apresentando a data em outro formato...",

            "mensagem" =>
                "DateTimeFormatter permite definir a ordem e a aparência das informações apresentadas.",

            "codigo" => <<<'JAVA'
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

public class Main {

    public static void main(String[] args) {

        LocalDateTime momento =
            LocalDateTime.of(
                2026,
                8,
                4,
                15,
                30
            );

        DateTimeFormatter formato =
            DateTimeFormatter.ofPattern(
                "dd/MM/yyyy HH:mm"
            );

        String texto =
            momento.format(formato);

        System.out.println(
            texto
        );

    }

}
JAVA,

            "pergunta" =>
                "Qual texto aparece no console?",

            "opcoes" => [

                [
                    "texto" =>
                        "04/08/2026 15:30",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "2026-08-04T15:30",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_padrao"
                ],

                [
                    "texto" =>
                        "08/04/2026 15:30",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_ordem"
                ],

                [
                    "texto" =>
                        "04/08/2026 15:08",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_letras"
                ],

                [
                    "texto" =>
                        "O resultado continua sendo LocalDateTime",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_tipo"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — IGNOROU A FORMATAÇÃO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_padrao" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Esse seria o formato padrão. Só que o código usa momento.format(formato), então a aparência é alterada antes do println.",

            "natanael" =>
                "A representação padrão seria utilizada se o objeto fosse apresentado diretamente. Neste caso, o DateTimeFormatter cria uma String seguindo o padrão personalizado.",

            "pergunta" =>
                "Qual método aplica o formato personalizado?",

            "opcoes" => [

                [
                    "texto" =>
                        "momento.format(formato)",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "System.out.println sem nenhuma conversão",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_padrao"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUNDIU DIA E MÊS
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_ordem" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "O padrão começa com dd e depois MM. Então primeiro aparece o dia 04 e depois o mês 08.",

            "natanael" =>
                "A sequência dos símbolos controla a ordem da saída. dd representa o dia com dois dígitos e MM representa o mês com dois dígitos.",

            "codigo" => <<<'JAVA'
dd = dia
MM = mês
yyyy = ano
JAVA,

            "pergunta" =>
                "Qual informação aparece primeiro no padrão dd/MM/yyyy?",

            "opcoes" => [

                [
                    "texto" =>
                        "O dia",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "O mês",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_ordem"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUNDIU MÊS COM MINUTOS
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_letras" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "Maiúscula e minúscula mudam tudo aqui. MM maiúsculo é mês. mm minúsculo é minuto.",

            "natanael" =>
                "Os símbolos são sensíveis a letras maiúsculas e minúsculas. O padrão utiliza MM para o mês e mm para os minutos do horário.",

            "codigo" => <<<'JAVA'
MM = mês
mm = minutos
HH = hora no formato de 24 horas
JAVA,

            "pergunta" =>
                "Qual símbolo representa os minutos?",

            "opcoes" => [

                [
                    "texto" =>
                        "mm",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "MM",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_letras"
                ]

            ]

        ],


        /*
        |--------------------------------------------------------------------------
        | PERGUNTA 4 — CONFUNDIU O TIPO DO RESULTADO
        |--------------------------------------------------------------------------
        */

        "pergunta_4_erro_tipo" => [

            "tipo" =>
                "feedback",

            "resultado" =>
                "erro",

            "robert" =>
                "momento continua sendo LocalDateTime. Mas format devolve uma String, que foi guardada na variável texto.",

            "natanael" =>
                "O objeto original não muda de tipo. A operação de formatação produz uma representação textual separada, compatível com a variável String.",

            "codigo" => <<<'JAVA'
String texto =
    momento.format(formato);
JAVA,

            "pergunta" =>
                "Qual é o tipo da variável texto?",

            "opcoes" => [

                [
                    "texto" =>
                        "String",

                    "resultado" =>
                        "quase",

                    "destino" =>
                        "pergunta_4_dica"
                ],

                [
                    "texto" =>
                        "LocalDateTime",

                    "resultado" =>
                        "erro",

                    "destino" =>
                        "pergunta_4_erro_tipo"
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
                "O padrão pede dia, mês, ano, hora e minuto. Então fica 04/08/2026 15:30.",

            "natanael" =>
                "A formatação reorganiza os dados do objeto. O dia aparece com dois dígitos, seguido pelo mês, ano e horário no formato de 24 horas.",

            "pergunta" =>
                "Qual é o texto final formatado?",

            "opcoes" => [

                [
                    "texto" =>
                        "04/08/2026 15:30",

                    "resultado" =>
                        "acerto",

                    "destino" =>
                        "final"
                ],

                [
                    "texto" =>
                        "2026-08-04T15:30",

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
                "Datas e horários desbloqueados!",

            "robert" =>
                "Boa po kkkkk. LocalDate guarda só ano, mês e dia. LocalDateTime também guarda hora e minuto. Métodos como plusDays e plusHours criam novos valores, porque essas classes são imutáveis. E DateTimeFormatter deixa a saída bem mais amigável, tipo 04/08/2026 15:30. Só cuida com MM maiúsculo pra mês e mm minúsculo pra minuto.",

            "natanael" =>
                "Concluímos a introdução à API moderna de datas do Java. LocalDate representa datas sem horário, enquanto LocalDateTime combina as duas informações. Utilizamos métodos como plusDays e plusHours e observamos que os objetos originais permanecem inalterados. Também aplicamos DateTimeFormatter para criar representações mais adequadas ao usuário, entendendo a diferença entre símbolos como MM para mês e mm para minutos. Na próxima aula vamos conhecer enum, uma estrutura utilizada para representar conjuntos fixos e seguros de opções.",

            "mensagem" =>
                "Tu concluiu a trigésima quarta Aula Conversada."

        ]

    ]

];