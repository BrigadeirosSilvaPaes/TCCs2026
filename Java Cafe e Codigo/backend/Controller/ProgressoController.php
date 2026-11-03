<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| PROGRESSO CONTROLLER
|
| Responsável pela comunicação entre a aplicação
| e o Model de progresso.
|
| Operações:
|
| - buscar progresso de um módulo;
| - listar progresso do usuário;
| - salvar progresso;
| - atualizar progresso;
| - adicionar XP.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| MODEL
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../models/Progresso.php";


class ProgressoController
{

    /*
    |--------------------------------------------------------------------------
    | MODEL
    |--------------------------------------------------------------------------
    */

    private Progresso $progresso;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {

        $this->progresso =
            new Progresso();

    }


    /*
    |--------------------------------------------------------------------------
    | RESPOSTA PADRÃO
    |--------------------------------------------------------------------------
    */

    private function resposta(
        bool $status,
        string $mensagem,
        array $dados = []
    ): array {

        $resultado = [

            "status" =>
                $status,

            "mensagem" =>
                $mensagem

        ];


        foreach (
            $dados as
            $chave => $valor
        ) {

            /*
            |--------------------------------------------------------------------------
            | NÃO PERMITE SOBRESCREVER CAMPOS PADRÃO
            |--------------------------------------------------------------------------
            */

            if (
                $chave === "status"
                ||
                $chave === "mensagem"
            ) {

                continue;

            }


            $resultado[$chave] =
                $valor;

        }


        return $resultado;

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDA USUÁRIO
    |--------------------------------------------------------------------------
    */

    private function usuarioValido(
        int $usuarioId
    ): bool {

        return
            $usuarioId > 0;

    }


    /*
    |--------------------------------------------------------------------------
    | COMPRIMENTO
    |--------------------------------------------------------------------------
    */

    private function comprimento(
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
    | NORMALIZA MÓDULO
    |--------------------------------------------------------------------------
    */

    private function normalizarModulo(
        string $modulo
    ): string {

        return strtolower(
            trim(
                $modulo
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDA MÓDULO
    |--------------------------------------------------------------------------
    |
    | O banco atual utiliza campo de até 50 caracteres.
    |
    | Não aplicamos uma whitelist neste Controller porque
    | ainda existem diferentes fluxos de progresso e o catálogo
    | definitivo será conferido durante a auditoria do Model.
    |
    |--------------------------------------------------------------------------
    */

    private function moduloValido(
        string $modulo
    ): bool {

        if (
            $modulo === ""
        ) {

            return false;

        }


        if (
            $this->comprimento(
                $modulo
            )
            > 50
        ) {

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | BLOQUEIA CARACTERES DE CONTROLE
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                "/[\x00-\x1F\x7F]/",
                $modulo
            )
            === 1
        ) {

            return false;

        }


        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDA NÍVEL
    |--------------------------------------------------------------------------
    */

    private function nivelValido(
        int $nivel
    ): bool {

        return
            $nivel >= 0;

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDA XP TOTAL
    |--------------------------------------------------------------------------
    */

    private function xpValido(
        int $xp
    ): bool {

        return
            $xp >= 0;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR PROGRESSO
    |--------------------------------------------------------------------------
    */

    public function buscar(
        int $usuarioId,
        string $modulo
    ): array {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Usuário inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MÓDULO
        |--------------------------------------------------------------------------
        */

        $modulo =
            $this->normalizarModulo(
                $modulo
            );


        if (
            !$this->moduloValido(
                $modulo
            )
        ) {

            return $this->resposta(
                false,
                "Módulo inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MODEL
        |--------------------------------------------------------------------------
        */

        try {

            $dados =
                $this->progresso->buscar(
                    $usuarioId,
                    $modulo
                );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[ProgressoController][Buscar] Usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Erro ao buscar progresso."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | NÃO ENCONTRADO
        |--------------------------------------------------------------------------
        */

        if (
            !is_array(
                $dados
            )
        ) {

            return $this->resposta(
                false,
                "Progresso não encontrado."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SUCESSO
        |--------------------------------------------------------------------------
        */

        return $this->resposta(
            true,
            "Progresso encontrado.",
            [
                "progresso" =>
                    $dados
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LISTAR PROGRESSO
    |--------------------------------------------------------------------------
    */

    public function listar(
        int $usuarioId
    ): array {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Usuário inválido.",
                [
                    "progresso" =>
                        []
                ]
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MODEL
        |--------------------------------------------------------------------------
        */

        try {

            $dados =
                $this->progresso->listar(
                    $usuarioId
                );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[ProgressoController][Listar] Usuário "
                .
                $usuarioId
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Erro ao listar progresso.",
                [
                    "progresso" =>
                        []
                ]
            );

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZA RESULTADO
        |--------------------------------------------------------------------------
        */

        if (
            !is_array(
                $dados
            )
        ) {

            $dados =
                [];

        }


        /*
        |--------------------------------------------------------------------------
        | SUCESSO
        |--------------------------------------------------------------------------
        |
        | Uma lista vazia não é erro.
        |
        | Significa apenas que o usuário ainda não possui
        | progresso registrado.
        |
        |--------------------------------------------------------------------------
        */

        return $this->resposta(
            true,
            "Progresso carregado.",
            [
                "progresso" =>
                    $dados
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SALVAR PROGRESSO
    |--------------------------------------------------------------------------
    */

    public function salvar(
        int $usuarioId,
        string $modulo,
        int $nivel,
        int $xp
    ): array {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Usuário inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MÓDULO
        |--------------------------------------------------------------------------
        */

        $modulo =
            $this->normalizarModulo(
                $modulo
            );


        if (
            !$this->moduloValido(
                $modulo
            )
        ) {

            return $this->resposta(
                false,
                "Módulo inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | NÍVEL
        |--------------------------------------------------------------------------
        */

        if (
            !$this->nivelValido(
                $nivel
            )
        ) {

            return $this->resposta(
                false,
                "Nível inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | XP
        |--------------------------------------------------------------------------
        */

        if (
            !$this->xpValido(
                $xp
            )
        ) {

            return $this->resposta(
                false,
                "XP inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MODEL
        |--------------------------------------------------------------------------
        */

        try {

            $resultado =
                $this->progresso->salvar(
                    $usuarioId,
                    $modulo,
                    $nivel,
                    $xp
                );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[ProgressoController][Salvar] Usuário "
                .
                $usuarioId
                .
                ", módulo "
                .
                $modulo
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Erro ao salvar progresso."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FALHA
        |--------------------------------------------------------------------------
        */

        if (
            $resultado !== true
        ) {

            return $this->resposta(
                false,
                "Erro ao salvar progresso."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SUCESSO
        |--------------------------------------------------------------------------
        */

        return $this->resposta(
            true,
            "Progresso salvo com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR PROGRESSO
    |--------------------------------------------------------------------------
    */

    public function atualizar(
        int $usuarioId,
        string $modulo,
        int $nivel,
        int $xp
    ): array {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Usuário inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MÓDULO
        |--------------------------------------------------------------------------
        */

        $modulo =
            $this->normalizarModulo(
                $modulo
            );


        if (
            !$this->moduloValido(
                $modulo
            )
        ) {

            return $this->resposta(
                false,
                "Módulo inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | NÍVEL
        |--------------------------------------------------------------------------
        */

        if (
            !$this->nivelValido(
                $nivel
            )
        ) {

            return $this->resposta(
                false,
                "Nível inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | XP
        |--------------------------------------------------------------------------
        */

        if (
            !$this->xpValido(
                $xp
            )
        ) {

            return $this->resposta(
                false,
                "XP inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MODEL
        |--------------------------------------------------------------------------
        */

        try {

            $resultado =
                $this->progresso->atualizar(
                    $usuarioId,
                    $modulo,
                    $nivel,
                    $xp
                );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[ProgressoController][Atualizar] Usuário "
                .
                $usuarioId
                .
                ", módulo "
                .
                $modulo
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Erro ao atualizar progresso."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FALHA
        |--------------------------------------------------------------------------
        */

        if (
            $resultado !== true
        ) {

            return $this->resposta(
                false,
                "Erro ao atualizar progresso."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SUCESSO
        |--------------------------------------------------------------------------
        */

        return $this->resposta(
            true,
            "Progresso atualizado."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ADICIONAR XP
    |--------------------------------------------------------------------------
    */

    public function adicionarXP(
        int $usuarioId,
        string $modulo,
        int $xp
    ): array {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Usuário inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MÓDULO
        |--------------------------------------------------------------------------
        */

        $modulo =
            $this->normalizarModulo(
                $modulo
            );


        if (
            !$this->moduloValido(
                $modulo
            )
        ) {

            return $this->resposta(
                false,
                "Módulo inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | XP
        |--------------------------------------------------------------------------
        |
        | Aqui exigimos XP positivo.
        |
        | adicionarXP(..., 0) não possui efeito prático.
        |
        |--------------------------------------------------------------------------
        */

        if (
            $xp <= 0
        ) {

            return $this->resposta(
                false,
                "Quantidade de XP inválida."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MODEL
        |--------------------------------------------------------------------------
        */

        try {

            $resultado =
                $this->progresso->adicionarXP(
                    $usuarioId,
                    $modulo,
                    $xp
                );

        }
        catch (
            Throwable $erro
        ) {

            error_log(
                "[ProgressoController][AdicionarXP] Usuário "
                .
                $usuarioId
                .
                ", módulo "
                .
                $modulo
                .
                ": "
                .
                $erro->getMessage()
            );


            return $this->resposta(
                false,
                "Erro ao adicionar XP."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FALHA
        |--------------------------------------------------------------------------
        */

        if (
            $resultado !== true
        ) {

            return $this->resposta(
                false,
                "Erro ao adicionar XP."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | SUCESSO
        |--------------------------------------------------------------------------
        */

        return $this->resposta(
            true,
            "XP adicionado."
        );

    }

}