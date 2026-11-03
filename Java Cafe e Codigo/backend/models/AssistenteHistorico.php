<?php

/*
|--------------------------------------------------------------------------
| ASSISTENTE HISTÓRICO
|--------------------------------------------------------------------------
|
| Java, Café e Código
|
| Camada de persistência do Assistente Java.
|
| Responsável por:
|
| - salvar mensagens do usuário;
| - salvar respostas do Assistente Java;
| - buscar o histórico recente;
| - preparar histórico em formato de chat quando necessário;
| - limpar exclusivamente o histórico de um usuário;
| - contar mensagens.
|
| Este model NÃO:
|
| - executa inteligência artificial;
| - chama APIs externas;
| - conhece dados da página atual;
| - recebe ID de usuário diretamente do navegador.
|
|--------------------------------------------------------------------------
*/

class AssistenteHistorico
{

    /*
    |--------------------------------------------------------------------------
    | CONEXÃO
    |--------------------------------------------------------------------------
    */

    private PDO $pdo;


    /*
    |--------------------------------------------------------------------------
    | LIMITES
    |--------------------------------------------------------------------------
    |
    | Mantidos alinhados com:
    |
    | - responder.php;
    | - historico.php;
    | - assistente_ia.js.
    |
    |--------------------------------------------------------------------------
    */

    private const LIMITE_HISTORICO_VISUAL =
        50;


    private const LIMITE_CONTEXTO_MODELO =
        10;


    private const TAMANHO_MAXIMO_USUARIO =
        4000;


    private const TAMANHO_MAXIMO_ASSISTENTE =
        5000;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        PDO $pdo
    ) {

        $this->pdo =
            $pdo;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR TEXTO
    |--------------------------------------------------------------------------
    */

    private function normalizarTexto(
        string $texto
    ): string {

        $texto =
            str_replace(
                [
                    "\r\n",
                    "\r"
                ],
                "\n",
                $texto
            );


        $texto =
            str_replace(
                "\0",
                "",
                $texto
            );


        return trim(
            $texto
        );

    }


    /*
    |--------------------------------------------------------------------------
    | UTF-8 VÁLIDO
    |--------------------------------------------------------------------------
    */

    private function textoUtf8Valido(
        string $texto
    ): bool {

        if (
            function_exists(
                "mb_check_encoding"
            )
        ) {

            return mb_check_encoding(
                $texto,
                "UTF-8"
            );

        }


        return preg_match(
            "//u",
            $texto
        )
        === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | COMPRIMENTO
    |--------------------------------------------------------------------------
    |
    | Se mbstring estiver disponível, contamos caracteres.
    |
    | Caso contrário usamos bytes. O fallback pode ser mais restritivo
    | para textos com acentos, mas continua seguro e não causa erro fatal.
    |
    |--------------------------------------------------------------------------
    */

    private function comprimentoTexto(
        string $texto
    ): int {

        if (
            function_exists(
                "mb_strlen"
            )
        ) {

            return mb_strlen(
                $texto,
                "UTF-8"
            );

        }


        return strlen(
            $texto
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CARACTERES DE CONTROLE
    |--------------------------------------------------------------------------
    |
    | Permitimos:
    |
    | - tabulação;
    | - quebra de linha.
    |
    |--------------------------------------------------------------------------
    */

    private function possuiControleInvalido(
        string $texto
    ): bool {

        return preg_match(
            "/[\x01-\x08\x0B\x0C\x0E-\x1F\x7F]/",
            $texto
        )
        === 1;

    }


    /*
    |--------------------------------------------------------------------------
    | LIMITE POR PAPEL
    |--------------------------------------------------------------------------
    */

    private function limiteMensagem(
        string $papel
    ): int {

        return match (
            $papel
        ) {

            "usuario" =>
                self::TAMANHO_MAXIMO_USUARIO,

            "assistente" =>
                self::TAMANHO_MAXIMO_ASSISTENTE,

            default =>
                0

        };

    }


    /*
    |--------------------------------------------------------------------------
    | SALVAR MENSAGEM
    |--------------------------------------------------------------------------
    |
    | Papéis permitidos:
    |
    | - usuario;
    | - assistente.
    |
    |--------------------------------------------------------------------------
    */

    public function salvarMensagem(
        int $usuarioId,
        string $papel,
        string $mensagem
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        if (
            $usuarioId <= 0
        ) {

            throw new InvalidArgumentException(
                "Usuário inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PAPEL
        |--------------------------------------------------------------------------
        */

        $papel =
            strtolower(
                trim(
                    $papel
                )
            );


        if (
            !in_array(
                $papel,
                [
                    "usuario",
                    "assistente"
                ],
                true
            )
        ) {

            throw new InvalidArgumentException(
                "Papel da mensagem inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TEXTO
        |--------------------------------------------------------------------------
        */

        $mensagem =
            $this->normalizarTexto(
                $mensagem
            );


        if (
            $mensagem === ""
        ) {

            throw new InvalidArgumentException(
                "A mensagem não pode estar vazia."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | UTF-8
        |--------------------------------------------------------------------------
        */

        if (
            !$this->textoUtf8Valido(
                $mensagem
            )
        ) {

            throw new InvalidArgumentException(
                "A mensagem contém caracteres inválidos."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CONTROLES
        |--------------------------------------------------------------------------
        */

        if (
            $this->possuiControleInvalido(
                $mensagem
            )
        ) {

            throw new InvalidArgumentException(
                "A mensagem contém caracteres não permitidos."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TAMANHO
        |--------------------------------------------------------------------------
        */

        $limite =
            $this->limiteMensagem(
                $papel
            );


        if (
            $limite <= 0
            ||
            $this->comprimentoTexto(
                $mensagem
            )
            >
            $limite
        ) {

            throw new InvalidArgumentException(
                "A mensagem ultrapassou o tamanho permitido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        $sql = "

            INSERT INTO assistente_historico (

                usuario_id,
                papel,
                mensagem

            ) VALUES (

                :usuario_id,
                :papel,
                :mensagem

            )

        ";


        $stmt =
            $this->pdo->prepare(
                $sql
            );


        return $stmt->execute(
            [

                ":usuario_id" =>
                    $usuarioId,

                ":papel" =>
                    $papel,

                ":mensagem" =>
                    $mensagem

            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SALVAR PERGUNTA
    |--------------------------------------------------------------------------
    */

    public function salvarUsuario(
        int $usuarioId,
        string $mensagem
    ): bool {

        return $this->salvarMensagem(

            $usuarioId,

            "usuario",

            $mensagem

        );

    }


    /*
    |--------------------------------------------------------------------------
    | SALVAR RESPOSTA
    |--------------------------------------------------------------------------
    */

    public function salvarAssistente(
        int $usuarioId,
        string $mensagem
    ): bool {

        return $this->salvarMensagem(

            $usuarioId,

            "assistente",

            $mensagem

        );

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR HISTÓRICO
    |--------------------------------------------------------------------------
    |
    | O SQL busca primeiro as mensagens mais recentes:
    |
    | nova
    | ↓
    | antiga
    |
    | Depois o PHP inverte:
    |
    | antiga
    | ↓
    | nova
    |
    | Dessa forma o frontend recebe a conversa na ordem correta.
    |
    |--------------------------------------------------------------------------
    */

    public function buscarHistorico(
        int $usuarioId,
        int $limite = self::LIMITE_HISTORICO_VISUAL
    ): array {

        if (
            $usuarioId <= 0
        ) {

            return [];

        }


        /*
        |--------------------------------------------------------------------------
        | LIMITE
        |--------------------------------------------------------------------------
        |
        | O valor que entra diretamente no LIMIT é convertido e restringido
        | aqui antes da montagem do SQL.
        |
        |--------------------------------------------------------------------------
        */

        $limite =
            max(
                1,
                min(
                    (int) $limite,
                    self::LIMITE_HISTORICO_VISUAL
                )
            );


        /*
        |--------------------------------------------------------------------------
        | QUERY
        |--------------------------------------------------------------------------
        */

        $sql = "

            SELECT

                id,
                usuario_id,
                papel,
                mensagem,
                criado_em

            FROM assistente_historico

            WHERE
                usuario_id = :usuario_id

            ORDER BY
                id DESC

            LIMIT {$limite}

        ";


        $stmt =
            $this->pdo->prepare(
                $sql
            );


        $stmt->execute(
            [

                ":usuario_id" =>
                    $usuarioId

            ]
        );


        $historico =
            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            );


        if (
            !is_array(
                $historico
            )
        ) {

            return [];

        }


        /*
        |--------------------------------------------------------------------------
        | ORDEM CRONOLÓGICA
        |--------------------------------------------------------------------------
        */

        return array_reverse(
            $historico
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR EM FORMATO DE CHAT
    |--------------------------------------------------------------------------
    |
    | Atualmente a IA executa no navegador e o frontend recebe o histórico
    | através de historico.php.
    |
    | Mantemos este método como utilitário para qualquer ponto interno que
    | precise obter mensagens já no formato:
    |
    | user
    | assistant
    |
    |--------------------------------------------------------------------------
    */

    public function buscarParaModelo(
        int $usuarioId,
        int $limite = self::LIMITE_CONTEXTO_MODELO
    ): array {

        if (
            $usuarioId <= 0
        ) {

            return [];

        }


        $limite =
            max(
                1,
                min(
                    (int) $limite,
                    self::LIMITE_CONTEXTO_MODELO
                )
            );


        $historico =
            $this->buscarHistorico(

                $usuarioId,

                $limite

            );


        $resultado =
            [];


        foreach (
            $historico as $mensagem
        ) {

            if (
                !is_array(
                    $mensagem
                )
            ) {

                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | PAPEL
            |--------------------------------------------------------------------------
            */

            $papel =
                strtolower(
                    trim(
                        (string) (
                            $mensagem["papel"]
                            ??
                            ""
                        )
                    )
                );


            $role =
                match (
                    $papel
                ) {

                    "usuario" =>
                        "user",

                    "assistente" =>
                        "assistant",

                    default =>
                        null

                };


            if (
                $role === null
            ) {

                continue;

            }


            /*
            |--------------------------------------------------------------------------
            | CONTEÚDO
            |--------------------------------------------------------------------------
            */

            $conteudo =
                $this->normalizarTexto(
                    (string) (
                        $mensagem["mensagem"]
                        ??
                        ""
                    )
                );


            if (
                $conteudo === ""
            ) {

                continue;

            }


            $resultado[] = [

                "role" =>
                    $role,

                "content" =>
                    $conteudo

            ];

        }


        return $resultado;

    }


    /*
    |--------------------------------------------------------------------------
    | LIMPAR HISTÓRICO
    |--------------------------------------------------------------------------
    |
    | IMPORTANTE:
    |
    | A exclusão possui obrigatoriamente:
    |
    | WHERE usuario_id = :usuario_id
    |
    | Portanto, uma chamada para um usuário não remove o histórico
    | pertencente a outros usuários.
    |
    |--------------------------------------------------------------------------
    */

    public function limparHistorico(
        int $usuarioId
    ): bool {

        if (
            $usuarioId <= 0
        ) {

            return false;

        }


        $sql = "

            DELETE FROM assistente_historico

            WHERE
                usuario_id = :usuario_id

        ";


        $stmt =
            $this->pdo->prepare(
                $sql
            );


        return $stmt->execute(
            [

                ":usuario_id" =>
                    $usuarioId

            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CONTAR MENSAGENS
    |--------------------------------------------------------------------------
    */

    public function contarMensagens(
        int $usuarioId
    ): int {

        if (
            $usuarioId <= 0
        ) {

            return 0;

        }


        $sql = "

            SELECT
                COUNT(*)

            FROM assistente_historico

            WHERE
                usuario_id = :usuario_id

        ";


        $stmt =
            $this->pdo->prepare(
                $sql
            );


        $stmt->execute(
            [

                ":usuario_id" =>
                    $usuarioId

            ]
        );


        $total =
            $stmt->fetchColumn();


        if (
            $total === false
        ) {

            return 0;

        }


        return max(
            0,
            (int) $total
        );

    }

}