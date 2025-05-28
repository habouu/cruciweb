<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = $_POST['db_host'];
    $dbName = $_POST['db_name'];
    $dbUser = $_POST['db_user'];
    $dbPass = $_POST['db_pass'];


    $content = "<?php\nreturn [\n";
    $content .= " 'db_host' => '$dbHost',\n";
    $content .= " 'db_name' => '$dbName',\n";
    $content .= " 'db_user' => '$dbUser',\n";
    $content .= " 'db_pass' => '$dbPass',\n";
    $content .= "];";

    $path = '/var/www/html/cruciweb/app/config/config.php';

    if (file_put_contents($path, $content) === false) {
        die('Impossible de générer le fichier de configuration');
    } else {
        echo 'Fichier de configuration opérationnel';
    }
    header('Location: ../app/index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Configuration de la base de données</title>
        <link rel="stylesheet" href="../app/assets/css/style.css">
    </head>
    <body>
        <main class="main">
            <section class="setup section">
            <h1>COnfiguration de la base de données</h1>
                <form action="" method="POST" class="setup-form">
                    <label for="db_host">Adresse du serveur</label>
                    <input type="text" name="db_host" id="db_host" required>

                    <label for="db_name">Nom de la base de données</label>
                    <input type="text" name="db_name" id="db_name" required>

                    <label for="db_user">Nom de l'utilisateur</label>
                    <input type="text" name="db_user" id="db_user" required>

                    <label for="db_pass">Mot de passe de l'utilisateur</label>
                    <input type="password" name="db_pass" id="db_pass" required>

                    <button type="submit">Se connecter</button>
                </form>
            </section>
        </main>
    </body>
</html>
