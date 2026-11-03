<?php

/*
|--------------------------------------------------------------------------
| JAVA, CAFÉ E CÓDIGO
|--------------------------------------------------------------------------
|
| PERFIL CONTROLLER
|
| Responsável pela comunicação entre os fluxos da aplicação
| e os models relacionados aos perfis:
|
| - PerfilUsuario;
| - PerfilProfessor;
| - PerfilEstudante;
| - PerfilAprendiz.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CONFIGURAÇÕES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../config/permissions.php";


/*
|--------------------------------------------------------------------------
| MODELS
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../models/PerfilUsuario.php";
require_once __DIR__ . "/../models/PerfilProfessor.php";
require_once __DIR__ . "/../models/PerfilEstudante.php";
require_once __DIR__ . "/../models/PerfilAprendiz.php";


class PerfilController
{

    /*
    |--------------------------------------------------------------------------
    | MODELS
    |--------------------------------------------------------------------------
    */

    private PerfilUsuario $perfilUsuario;

    private PerfilProfessor $perfilProfessor;

    private PerfilEstudante $perfilEstudante;

    private PerfilAprendiz $perfilAprendiz;


    /*
    |--------------------------------------------------------------------------
    | TIPOS DE PERFIL
    |--------------------------------------------------------------------------
    */

    private const TIPOS_PERMITIDOS = [

        PROFILE_ESTUDANTE,
        PROFILE_PROFESSOR,
        PROFILE_APRENDIZ

    ];


    /*
    |--------------------------------------------------------------------------
    | EXPERIÊNCIAS DO APRENDIZ
    |--------------------------------------------------------------------------
    */

    private const EXPERIENCIAS_PERMITIDAS = [

        "nenhuma",
        "basica",
        "intermediaria",
        "avancada"

    ];


    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {

        $this->perfilUsuario =
            new PerfilUsuario();


        $this->perfilProfessor =
            new PerfilProfessor();


        $this->perfilEstudante =
            new PerfilEstudante();


        $this->perfilAprendiz =
            new PerfilAprendiz();

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
            | STATUS E MENSAGEM NÃO PODEM SER SOBRESCRITOS
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
    | VALIDA ID DO USUÁRIO
    |--------------------------------------------------------------------------
    */

    private function usuarioValido(
        int $usuarioId
    ): bool {

        return
            $usuarioId >
            0;

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZA TIPO DE PERFIL
    |--------------------------------------------------------------------------
    */

    private function normalizarTipoUsuario(
        string $tipoUsuario
    ): string {

        return strtolower(
            trim(
                $tipoUsuario
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDA TIPO DE PERFIL
    |--------------------------------------------------------------------------
    */

    private function tipoUsuarioValido(
        string $tipoUsuario
    ): bool {

        return in_array(
            $tipoUsuario,
            self::TIPOS_PERMITIDOS,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZA EXPERIÊNCIA
    |--------------------------------------------------------------------------
    */

    private function normalizarExperiencia(
        string $experiencia
    ): string {

        return strtolower(
            trim(
                $experiencia
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | VALIDA EXPERIÊNCIA
    |--------------------------------------------------------------------------
    */

    private function experienciaValida(
        string $experiencia
    ): bool {

        return in_array(
            $experiencia,
            self::EXPERIENCIAS_PERMITIDAS,
            true
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZA TEXTO CURTO
    |--------------------------------------------------------------------------
    */

    private function normalizarTextoCurto(
        string $texto
    ): string {

        $texto =
            trim(
                $texto
            );


        $normalizado =
            preg_replace(
                "/\s+/u",
                " ",
                $texto
            );


        return
            is_string(
                $normalizado
            )
                ?
                trim(
                    $normalizado
                )
                :
                "";

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
    | BUSCA PERFIL PRINCIPAL
    |--------------------------------------------------------------------------
    */

    private function buscarPerfilPrincipal(
        int $usuarioId
    ): ?array {

        if (
            !$this->usuarioValido(
                $usuarioId
            )
        ) {

            return null;

        }


        $perfil =
            $this->perfilUsuario->buscarPorUsuario(
                $usuarioId
            );


        return
            is_array(
                $perfil
            )
                ?
                $perfil
                :
                null;

    }


    /*
    |--------------------------------------------------------------------------
    | PERFIL PRINCIPAL É DO TIPO ESPERADO?
    |--------------------------------------------------------------------------
    |
    | Esta validação protege o Controller mesmo caso algum endpoint
    | esqueça de conferir o tipo do perfil.
    |
    |--------------------------------------------------------------------------
    */

    private function perfilPrincipalEhDoTipo(
        int $usuarioId,
        string $tipoEsperado
    ): bool {

        $perfil =
            $this->buscarPerfilPrincipal(
                $usuarioId
            );


        if (
            !is_array(
                $perfil
            )
        ) {

            return false;

        }


        $tipoAtual =
            $perfil["tipo_usuario"]
            ??
            null;


        if (
            !is_string(
                $tipoAtual
            )
        ) {

            return false;

        }


        $tipoAtual =
            $this->normalizarTipoUsuario(
                $tipoAtual
            );


        return
            $tipoAtual ===
            $tipoEsperado;

    }


    /*
    |--------------------------------------------------------------------------
    | BUSCAR PERFIL COMPLETO
    |--------------------------------------------------------------------------
    |
    | Primeiro carregamos o perfil principal.
    |
    | Depois carregamos somente o perfil específico correspondente
    | ao tipo_usuario armazenado.
    |
    |--------------------------------------------------------------------------
    */

    public function buscarPerfil(
        int $usuarioId
    ): array {

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
        | PERFIL PRINCIPAL
        |--------------------------------------------------------------------------
        */

        $usuario =
            $this->buscarPerfilPrincipal(
                $usuarioId
            );


        if (
            !is_array(
                $usuario
            )
        ) {

            return $this->resposta(
                false,
                "Perfil não encontrado."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | TIPO
        |--------------------------------------------------------------------------
        */

        $tipoRecebido =
            $usuario["tipo_usuario"]
            ??
            null;


        if (
            !is_string(
                $tipoRecebido
            )
        ) {

            return $this->resposta(
                false,
                "Tipo de perfil inválido."
            );

        }


        $tipoUsuario =
            $this->normalizarTipoUsuario(
                $tipoRecebido
            );


        if (
            !$this->tipoUsuarioValido(
                $tipoUsuario
            )
        ) {

            return $this->resposta(
                false,
                "Tipo de perfil inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | RESULTADO
        |--------------------------------------------------------------------------
        */

        $perfil = [

            "usuario" =>
                $usuario

        ];


        /*
        |--------------------------------------------------------------------------
        | PERFIL ESPECÍFICO
        |--------------------------------------------------------------------------
        */

        switch (
            $tipoUsuario
        ) {

            /*
            |--------------------------------------------------------------------------
            | ESTUDANTE
            |--------------------------------------------------------------------------
            */

            case PROFILE_ESTUDANTE:

                $estudante =
                    $this->perfilEstudante->buscarPorUsuario(
                        $usuarioId
                    );


                if (
                    is_array(
                        $estudante
                    )
                ) {

                    $perfil["estudante"] =
                        $estudante;

                }


                break;


            /*
            |--------------------------------------------------------------------------
            | PROFESSOR
            |--------------------------------------------------------------------------
            */

            case PROFILE_PROFESSOR:

                $professor =
                    $this->perfilProfessor->buscarPorUsuario(
                        $usuarioId
                    );


                if (
                    is_array(
                        $professor
                    )
                ) {

                    $perfil["professor"] =
                        $professor;

                }


                break;


            /*
            |--------------------------------------------------------------------------
            | APRENDIZ
            |--------------------------------------------------------------------------
            */

            case PROFILE_APRENDIZ:

                $aprendiz =
                    $this->perfilAprendiz->buscarPorUsuario(
                        $usuarioId
                    );


                if (
                    is_array(
                        $aprendiz
                    )
                ) {

                    $perfil["aprendiz"] =
                        $aprendiz;

                }


                break;

        }


        return $this->resposta(
            true,
            "Perfil carregado.",
            [
                "perfil" =>
                    $perfil
            ]
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SALVAR PERFIL PRINCIPAL
    |--------------------------------------------------------------------------
    |
    | Este método:
    |
    | - cria o perfil principal caso ainda não exista;
    | - atualiza os dados gerais caso ele já exista;
    | - NÃO permite trocar o tipo de perfil depois da criação.
    |
    | Isso evita:
    |
    | estudante
    |     ↓
    | virar professor
    |
    | enquanto perfil_estudante continua existente no banco.
    |
    |--------------------------------------------------------------------------
    */

    public function salvarPerfilUsuario(
        int $usuarioId,
        string $tipoUsuario,
        ?string $estado,
        ?string $cidade,
        ?string $objetivo
    ): array {

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


        $tipoUsuario =
            $this->normalizarTipoUsuario(
                $tipoUsuario
            );


        if (
            !$this->tipoUsuarioValido(
                $tipoUsuario
            )
        ) {

            return $this->resposta(
                false,
                "Tipo de perfil inválido."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | PERFIL JÁ EXISTE
        |--------------------------------------------------------------------------
        */

        $perfilAtual =
            $this->buscarPerfilPrincipal(
                $usuarioId
            );


        if (
            is_array(
                $perfilAtual
            )
        ) {

            $tipoAtualRecebido =
                $perfilAtual["tipo_usuario"]
                ??
                null;


            if (
                !is_string(
                    $tipoAtualRecebido
                )
            ) {

                return $this->resposta(
                    false,
                    "Tipo de perfil atual inválido."
                );

            }


            $tipoAtual =
                $this->normalizarTipoUsuario(
                    $tipoAtualRecebido
                );


            /*
            |--------------------------------------------------------------------------
            | NÃO PERMITE TROCA DE TIPO
            |--------------------------------------------------------------------------
            */

            if (
                $tipoAtual !==
                $tipoUsuario
            ) {

                return $this->resposta(
                    false,
                    "O tipo de perfil não pode ser alterado após sua criação."
                );

            }


            /*
            |--------------------------------------------------------------------------
            | ATUALIZA DADOS GERAIS
            |--------------------------------------------------------------------------
            */

            if (
                !$this->perfilUsuario->atualizar(
                    $usuarioId,
                    $tipoUsuario,
                    $estado,
                    $cidade,
                    $objetivo
                )
            ) {

                return $this->resposta(
                    false,
                    "Erro ao atualizar perfil."
                );

            }


            return $this->resposta(
                true,
                "Perfil atualizado com sucesso."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CRIA PERFIL PRINCIPAL
        |--------------------------------------------------------------------------
        */

        if (
            !$this->perfilUsuario->criar(
                $usuarioId,
                $tipoUsuario,
                $estado,
                $cidade,
                $objetivo
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao salvar perfil."
            );

        }


        return $this->resposta(
            true,
            "Perfil salvo com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SALVAR PROFESSOR
    |--------------------------------------------------------------------------
    |
    | Mantemos comportamento create-or-update por compatibilidade.
    |
    | Os endpoints de criação e edição possuem regras adicionais.
    |
    |--------------------------------------------------------------------------
    */

    public function salvarProfessor(
        int $usuarioId,
        string $instituicao,
        string $disciplina
    ): array {

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
        | PERFIL PRINCIPAL PRECISA SER PROFESSOR
        |--------------------------------------------------------------------------
        */

        if (
            !$this->perfilPrincipalEhDoTipo(
                $usuarioId,
                PROFILE_PROFESSOR
            )
        ) {

            return $this->resposta(
                false,
                "O usuário não possui perfil Professor."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZA
        |--------------------------------------------------------------------------
        */

        $instituicao =
            $this->normalizarTextoCurto(
                $instituicao
            );


        $disciplina =
            $this->normalizarTextoCurto(
                $disciplina
            );


        /*
        |--------------------------------------------------------------------------
        | VALIDA CAMPOS
        |--------------------------------------------------------------------------
        */

        if (
            $this->comprimento(
                $instituicao
            )
            < 2
            ||
            $this->comprimento(
                $instituicao
            )
            > 150
        ) {

            return $this->resposta(
                false,
                "Instituição inválida."
            );

        }


        if (
            $this->comprimento(
                $disciplina
            )
            < 2
            ||
            $this->comprimento(
                $disciplina
            )
            > 100
        ) {

            return $this->resposta(
                false,
                "Disciplina inválida."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ATUALIZA
        |--------------------------------------------------------------------------
        */

        if (
            $this->perfilProfessor->existePerfil(
                $usuarioId
            )
        ) {

            if (
                !$this->perfilProfessor->atualizar(
                    $usuarioId,
                    $instituicao,
                    $disciplina
                )
            ) {

                return $this->resposta(
                    false,
                    "Erro ao atualizar o perfil de professor."
                );

            }


            return $this->resposta(
                true,
                "Perfil de professor atualizado com sucesso."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CRIA
        |--------------------------------------------------------------------------
        */

        if (
            !$this->perfilProfessor->criar(
                $usuarioId,
                $instituicao,
                $disciplina
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao criar o perfil de professor."
            );

        }


        return $this->resposta(
            true,
            "Professor cadastrado com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SALVAR ESTUDANTE
    |--------------------------------------------------------------------------
    */

    public function salvarEstudante(
        int $usuarioId,
        string $instituicao
    ): array {

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
        | PERFIL PRINCIPAL PRECISA SER ESTUDANTE
        |--------------------------------------------------------------------------
        */

        if (
            !$this->perfilPrincipalEhDoTipo(
                $usuarioId,
                PROFILE_ESTUDANTE
            )
        ) {

            return $this->resposta(
                false,
                "O usuário não possui perfil Estudante."
            );

        }


        $instituicao =
            strtolower(
                trim(
                    $instituicao
                )
            );


        if (
            $instituicao === ""
            ||
            $this->comprimento(
                $instituicao
            )
            > 50
        ) {

            return $this->resposta(
                false,
                "Instituição inválida."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ATUALIZA
        |--------------------------------------------------------------------------
        */

        if (
            $this->perfilEstudante->existePerfil(
                $usuarioId
            )
        ) {

            if (
                !$this->perfilEstudante->atualizar(
                    $usuarioId,
                    $instituicao
                )
            ) {

                return $this->resposta(
                    false,
                    "Erro ao atualizar o perfil de estudante."
                );

            }


            return $this->resposta(
                true,
                "Perfil de estudante atualizado com sucesso."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CRIA
        |--------------------------------------------------------------------------
        */

        if (
            !$this->perfilEstudante->criar(
                $usuarioId,
                $instituicao
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao criar o perfil de estudante."
            );

        }


        return $this->resposta(
            true,
            "Perfil de estudante criado com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SALVAR APRENDIZ
    |--------------------------------------------------------------------------
    */

    public function salvarAprendiz(
        int $usuarioId,
        string $experiencia,
        ?string $objetivo
    ): array {

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
        | PERFIL PRINCIPAL PRECISA SER APRENDIZ
        |--------------------------------------------------------------------------
        */

        if (
            !$this->perfilPrincipalEhDoTipo(
                $usuarioId,
                PROFILE_APRENDIZ
            )
        ) {

            return $this->resposta(
                false,
                "O usuário não possui perfil Aprendiz."
            );

        }


        $experiencia =
            $this->normalizarExperiencia(
                $experiencia
            );


        if (
            !$this->experienciaValida(
                $experiencia
            )
        ) {

            return $this->resposta(
                false,
                "Experiência de programação inválida."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | ATUALIZA
        |--------------------------------------------------------------------------
        */

        if (
            $this->perfilAprendiz->existePerfil(
                $usuarioId
            )
        ) {

            if (
                !$this->perfilAprendiz->atualizar(
                    $usuarioId,
                    $experiencia,
                    $objetivo
                )
            ) {

                return $this->resposta(
                    false,
                    "Erro ao atualizar o perfil de aprendiz."
                );

            }


            return $this->resposta(
                true,
                "Perfil de aprendiz atualizado com sucesso."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | CRIA
        |--------------------------------------------------------------------------
        */

        if (
            !$this->perfilAprendiz->criar(
                $usuarioId,
                $experiencia,
                $objetivo
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao criar o perfil de aprendiz."
            );

        }


        return $this->resposta(
            true,
            "Perfil de aprendiz cadastrado com sucesso."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR PERFIL PRINCIPAL
    |--------------------------------------------------------------------------
    |
    | Atualiza somente dados gerais.
    |
    | O tipo atual não pode ser alterado.
    |
    |--------------------------------------------------------------------------
    */

    public function atualizarPerfilUsuario(
        int $usuarioId,
        string $tipoUsuario,
        ?string $estado,
        ?string $cidade,
        ?string $objetivo
    ): array {

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


        $tipoUsuario =
            $this->normalizarTipoUsuario(
                $tipoUsuario
            );


        if (
            !$this->tipoUsuarioValido(
                $tipoUsuario
            )
        ) {

            return $this->resposta(
                false,
                "Tipo de perfil inválido."
            );

        }


        $perfilAtual =
            $this->buscarPerfilPrincipal(
                $usuarioId
            );


        if (
            !is_array(
                $perfilAtual
            )
        ) {

            return $this->resposta(
                false,
                "Perfil não encontrado."
            );

        }


        $tipoAtualRecebido =
            $perfilAtual["tipo_usuario"]
            ??
            null;


        if (
            !is_string(
                $tipoAtualRecebido
            )
        ) {

            return $this->resposta(
                false,
                "Tipo de perfil atual inválido."
            );

        }


        $tipoAtual =
            $this->normalizarTipoUsuario(
                $tipoAtualRecebido
            );


        if (
            $tipoAtual !==
            $tipoUsuario
        ) {

            return $this->resposta(
                false,
                "O tipo de perfil não pode ser alterado."
            );

        }


        if (
            !$this->perfilUsuario->atualizar(
                $usuarioId,
                $tipoAtual,
                $estado,
                $cidade,
                $objetivo
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao atualizar perfil."
            );

        }


        return $this->resposta(
            true,
            "Perfil atualizado."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function atualizarProfessor(
        int $usuarioId,
        string $instituicao,
        string $disciplina
    ): array {

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


        if (
            !$this->perfilPrincipalEhDoTipo(
                $usuarioId,
                PROFILE_PROFESSOR
            )
        ) {

            return $this->resposta(
                false,
                "O usuário não possui perfil Professor."
            );

        }


        if (
            !$this->perfilProfessor->existePerfil(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Perfil de professor não encontrado."
            );

        }


        $instituicao =
            $this->normalizarTextoCurto(
                $instituicao
            );


        $disciplina =
            $this->normalizarTextoCurto(
                $disciplina
            );


        if (
            $this->comprimento(
                $instituicao
            )
            < 2
            ||
            $this->comprimento(
                $instituicao
            )
            > 150
            ||
            $this->comprimento(
                $disciplina
            )
            < 2
            ||
            $this->comprimento(
                $disciplina
            )
            > 100
        ) {

            return $this->resposta(
                false,
                "Dados profissionais inválidos."
            );

        }


        if (
            !$this->perfilProfessor->atualizar(
                $usuarioId,
                $instituicao,
                $disciplina
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao atualizar professor."
            );

        }


        return $this->resposta(
            true,
            "Professor atualizado."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR ESTUDANTE
    |--------------------------------------------------------------------------
    */

    public function atualizarEstudante(
        int $usuarioId,
        string $instituicao
    ): array {

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


        if (
            !$this->perfilPrincipalEhDoTipo(
                $usuarioId,
                PROFILE_ESTUDANTE
            )
        ) {

            return $this->resposta(
                false,
                "O usuário não possui perfil Estudante."
            );

        }


        if (
            !$this->perfilEstudante->existePerfil(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Perfil de estudante não encontrado."
            );

        }


        $instituicao =
            strtolower(
                trim(
                    $instituicao
                )
            );


        if (
            $instituicao === ""
            ||
            $this->comprimento(
                $instituicao
            )
            > 50
        ) {

            return $this->resposta(
                false,
                "Instituição inválida."
            );

        }


        if (
            !$this->perfilEstudante->atualizar(
                $usuarioId,
                $instituicao
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao atualizar estudante."
            );

        }


        return $this->resposta(
            true,
            "Estudante atualizado."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR APRENDIZ
    |--------------------------------------------------------------------------
    */

    public function atualizarAprendiz(
        int $usuarioId,
        string $experiencia,
        ?string $objetivo
    ): array {

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


        if (
            !$this->perfilPrincipalEhDoTipo(
                $usuarioId,
                PROFILE_APRENDIZ
            )
        ) {

            return $this->resposta(
                false,
                "O usuário não possui perfil Aprendiz."
            );

        }


        $experiencia =
            $this->normalizarExperiencia(
                $experiencia
            );


        if (
            !$this->experienciaValida(
                $experiencia
            )
        ) {

            return $this->resposta(
                false,
                "Experiência de programação inválida."
            );

        }


        if (
            !$this->perfilAprendiz->existePerfil(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Perfil de aprendiz não encontrado."
            );

        }


        if (
            !$this->perfilAprendiz->atualizar(
                $usuarioId,
                $experiencia,
                $objetivo
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao atualizar aprendiz."
            );

        }


        return $this->resposta(
            true,
            "Aprendiz atualizado."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | APROVAR PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function aprovarProfessor(
        int $usuarioId
    ): array {

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


        if (
            !$this->perfilPrincipalEhDoTipo(
                $usuarioId,
                PROFILE_PROFESSOR
            )
        ) {

            return $this->resposta(
                false,
                "O usuário não possui perfil Professor."
            );

        }


        if (
            !$this->perfilProfessor->existePerfil(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Perfil de professor não encontrado."
            );

        }


        if (
            !$this->perfilProfessor->aprovar(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao aprovar professor."
            );

        }


        return $this->resposta(
            true,
            "Professor aprovado."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVER APROVAÇÃO DO PROFESSOR
    |--------------------------------------------------------------------------
    */

    public function removerAprovacaoProfessor(
        int $usuarioId
    ): array {

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


        if (
            !$this->perfilPrincipalEhDoTipo(
                $usuarioId,
                PROFILE_PROFESSOR
            )
        ) {

            return $this->resposta(
                false,
                "O usuário não possui perfil Professor."
            );

        }


        if (
            !$this->perfilProfessor->existePerfil(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Perfil de professor não encontrado."
            );

        }


        if (
            !$this->perfilProfessor->removerAprovacao(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao remover aprovação."
            );

        }


        return $this->resposta(
            true,
            "Aprovação removida."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MARCAR PERFIL COMO COMPLETO
    |--------------------------------------------------------------------------
    |
    | Não basta existir perfil_usuario.
    |
    | Também exigimos que o perfil específico correspondente exista.
    |
    |--------------------------------------------------------------------------
    */

    public function marcarPerfilCompleto(
        int $usuarioId
    ): array {

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


        $perfilPrincipal =
            $this->buscarPerfilPrincipal(
                $usuarioId
            );


        if (
            !is_array(
                $perfilPrincipal
            )
        ) {

            return $this->resposta(
                false,
                "Perfil não encontrado."
            );

        }


        $tipoRecebido =
            $perfilPrincipal["tipo_usuario"]
            ??
            null;


        if (
            !is_string(
                $tipoRecebido
            )
        ) {

            return $this->resposta(
                false,
                "Tipo de perfil inválido."
            );

        }


        $tipoUsuario =
            $this->normalizarTipoUsuario(
                $tipoRecebido
            );


        /*
        |--------------------------------------------------------------------------
        | PERFIL ESPECÍFICO PRECISA EXISTIR
        |--------------------------------------------------------------------------
        */

        $perfilEspecificoExiste =
            match (
                $tipoUsuario
            ) {

                PROFILE_ESTUDANTE =>
                    $this->perfilEstudante->existePerfil(
                        $usuarioId
                    ),

                PROFILE_PROFESSOR =>
                    $this->perfilProfessor->existePerfil(
                        $usuarioId
                    ),

                PROFILE_APRENDIZ =>
                    $this->perfilAprendiz->existePerfil(
                        $usuarioId
                    ),

                default =>
                    false

            };


        if (
            !$perfilEspecificoExiste
        ) {

            return $this->resposta(
                false,
                "O perfil específico ainda não foi configurado."
            );

        }


        /*
        |--------------------------------------------------------------------------
        | MARCA COMO COMPLETO
        |--------------------------------------------------------------------------
        */

        if (
            !$this->perfilUsuario->marcarCompleto(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao finalizar perfil."
            );

        }


        return $this->resposta(
            true,
            "Perfil finalizado."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ALTERAR EXPERIÊNCIA DO APRENDIZ
    |--------------------------------------------------------------------------
    */

    public function alterarExperiencia(
        int $usuarioId,
        string $experiencia
    ): array {

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


        if (
            !$this->perfilPrincipalEhDoTipo(
                $usuarioId,
                PROFILE_APRENDIZ
            )
        ) {

            return $this->resposta(
                false,
                "O usuário não possui perfil Aprendiz."
            );

        }


        $experiencia =
            $this->normalizarExperiencia(
                $experiencia
            );


        if (
            !$this->experienciaValida(
                $experiencia
            )
        ) {

            return $this->resposta(
                false,
                "Experiência de programação inválida."
            );

        }


        if (
            !$this->perfilAprendiz->existePerfil(
                $usuarioId
            )
        ) {

            return $this->resposta(
                false,
                "Perfil de aprendiz não encontrado."
            );

        }


        if (
            !$this->perfilAprendiz->alterarExperiencia(
                $usuarioId,
                $experiencia
            )
        ) {

            return $this->resposta(
                false,
                "Erro ao alterar experiência."
            );

        }


        return $this->resposta(
            true,
            "Experiência atualizada."
        );

    }

}