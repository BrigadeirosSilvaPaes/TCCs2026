<?php

require_once __DIR__ . "/../config/bootstrap.php";


class MensagemSala
{

    private PDO $pdo;


    public function __construct()
    {

        global $pdo;

        $this->pdo = $pdo;

    }



    public function enviar(

        int $salaId,

        int $usuarioId,

        string $mensagem

    ): bool
    {


        $stmt = $this->pdo->prepare("

            INSERT INTO mensagens_sala

            (

                sala_id,

                usuario_id,

                mensagem

            )

            VALUES

            (

                :sala,

                :usuario,

                :mensagem

            )

        ");


        return $stmt->execute([


            ":sala" => $salaId,


            ":usuario" => $usuarioId,


            ":mensagem" => $mensagem


        ]);

    }





    public function listarPorSala(

        int $salaId

    ): array
    {


        $stmt = $this->pdo->prepare("

            SELECT

                m.id,

                m.mensagem,

                m.enviado_em,

                u.id AS usuario_id,

                u.nome

            FROM mensagens_sala m

            INNER JOIN usuarios u

            ON u.id = m.usuario_id

            WHERE m.sala_id = :sala

            ORDER BY m.enviado_em ASC

        ");


        $stmt->execute([


            ":sala" => $salaId


        ]);


        return $stmt->fetchAll();


    }
        public function listarPorUsuario(

        int $usuarioId

    ): array
    {


        $stmt = $this->pdo->prepare("

            SELECT

                m.id,

                m.sala_id,

                m.mensagem,

                m.enviado_em

            FROM mensagens_sala m

            WHERE m.usuario_id = :usuario

            ORDER BY m.enviado_em DESC

        ");


        $stmt->execute([


            ":usuario" => $usuarioId


        ]);


        return $stmt->fetchAll();


    }





    public function excluir(

        int $id

    ): bool
    {


        $stmt = $this->pdo->prepare("

            DELETE FROM mensagens_sala

            WHERE id = :id

        ");


        return $stmt->execute([


            ":id" => $id


        ]);

    }


}