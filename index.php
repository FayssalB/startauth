<?php
// Démarrage du système de session (avant toute balise HTML)
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification en PHP</title>
</head>
<body>
    <h1>Authentification en PHP</h1>

    <nav>
        <ul>
            <?php
            if (isset($_SESSION["user"])) {
                ?>
                <li><a href="my_account.php">Mon compte</a></li>
                <li><a href="logout.php">Se déconnecter</a></li>
                <?php
            } else {
                ?>
                <li><a href="create_account.php">Création d'un compte</a></li>
                <li><a href="login.php">Connexion d'un utilisateur</a></li>
                <?php
            }
            ?>
        </ul>
    </nav>
</body>
</html>