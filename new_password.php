<?php
session_start();

$dsn = "mysql:host=localhost;dbname=startauth";
$db = new PDO($dsn, "root", "");

if (isset($_GET["token"]) && !isset($_SESSION["email"])) {
    $token = trim(strip_tags($_GET["token"]));
    // Vérification que le token existe
    $query = $db->prepare("SELECT email FROM password_reset WHERE token LIKE :token");
    $query->bindParam(":token", $token);
    $query->execute();
    $result = $query->fetch();

    if (!empty($result)) {
        // Le token existe bel et bien et qu'un email lui est associé
        // On stocke l'email pour la mise à jour à venir (une fois que le formulaire sera envoyé)
        $_SESSION["email"] = $result["email"];
    } else {
        // Truand bis !
        header("Location: index.php");
    }
} else if (isset($_SESSION["email"]) && isset($_POST["password"])) {
    // N'oubliez pas de valider la consistance du mot de passe !

    $password = trim(strip_tags($_POST["password"]));
    // Cryptage du mot de passe
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $query = $db->prepare("UPDATE users SET password = :password WHERE email LIKE :email");
    $query->bindParam(":password", $hash);
    $query->bindParam(":email", $_SESSION["email"]);

    if ($query->execute()) {
        // Possibilité de compléter avec une requête DELETE sur la table password_reset pour purger la ligne en question

        // Nettoyage des variables de sessions
        session_destroy();

        // Redirection vers la page de login pour que l'utilisateur puisse se connecter avec son nouveau mot de passe
        header("Location: login.php");
    }
} else {
    // Truand !
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe</title>
</head>
<body>
    <h1>Nouveau mot de passe</h1>

    <form action="" method="post">
        <div class="form-group">
            <label for="inputPassword">Nouveau mot de passe :</label>
            <input type="password" name="password" id="inputPassword">
        </div>
        <input type="submit" value="Envoyer">
    </form>
</body>
</html>