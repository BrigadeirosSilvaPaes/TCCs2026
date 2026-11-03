<?php
require_once "../../../config/app.php";
?>

    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>
            <?= SITE_NAME ?>
        </title>

        <link rel="stylesheet" href="<?= BASE_URL ?>/frontend/assets/css/style.css">
        <link rel="stylesheet" href="<?= BASE_URL ?>/frontend/assets/css/mensagem.css">

    </head>

    <body>
<?php include "../../componentes/header.php"; ?>

        <main class="mensagem-card">

            <h2 id="titulo"></h2>

            <p id="descricao"></p>

            <a id="botao" class="botao"></a>

        </main>

        <?php include "../../componentes/footer.php"; ?>

        <script src="<?= BASE_URL ?>/frontend/assets/js/theme.js"></script>
        <script>
const BASE_URL = "<?= BASE_URL ?>";
</script>

        <script src="<?= BASE_URL ?>/frontend/assets/js/mensagem.js"></script>

    </body>

    </html>