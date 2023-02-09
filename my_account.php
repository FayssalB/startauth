<?php
session_start();

// On teste déjà l'existence de la variable de session user
// Puis on teste l'adresse ip de l'utilisateur pour vérifier qu'il n'y a pas de tentative d'attaque session hijacking
if (!isset($_SESSION["user"]) || $_SESSION["user"]["ip"] != $_SERVER["REMOTE_ADDR"]) {
    // Potentiellement une ALERTE INTRUS ! --> rediriger vers la page de login
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte utilisateur</title>
</head>
<body>
    <h1>Le compte utilisateur de <?= $_SESSION["user"]["firstname"] ?></h1>
</body>
</html>