<?php
// Chargement des dépendances Composer
require("vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;

// Création d'une constante pour générer le lien de réinitialisation du mot de passe
define("HOST", "http://localhost/startauth");

if (isset($_POST["email"])) {
    $email = trim(strip_tags($_POST["email"]));

    // la fonction random_bytes renvoie un binaire que nous allons transformer en une chaine hexadecimale avec la fonction bin2hex --> c'est ainsi que l'on obtient notre token
    // si nous indiquons 50 en paramètre de la fonction random_bytes nous obtiendrons un token de 100 caractères avec bin2hex
    $token = bin2hex(random_bytes(50));

    $dsn = "mysql:host=localhost;dbname=startauth";
    $db = new PDO($dsn, "root", "");

    // Insertion du token en BDD
    $query = $db->prepare("INSERT INTO password_reset (email, token) VALUES (:email, :token)");
    $query->bindParam(":email", $email);
    $query->bindParam(":token", $token);

    if ($query->execute()) {
        // On valide que l'insertion en BDD est bien réalisée avant de faire l'envoi du mail

        // Appel au constructeur de la classe PHPMailer
        $phpmailer = new PHPMailer();
        // On indique que l'on utilise le protocole SMTP
        $phpmailer->isSMTP();
        // Informations du compte Mailtrap
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'c1a7a99002d70d';
        $phpmailer->Password = '5f3652e2fd507b';

        // Expéditeur
        $phpmailer->From = "no-reply@dwwm.fr";
        // Nom à afficher à la place de l'adresse mail dans le client mail
        $phpmailer->FromName = "Team DWWM";

        // Destinataire
        $phpmailer->addAddress($email);

        // On indique que le contenu du mail sera du code HTML
        $phpmailer->isHTML();

        // Encodage de caractères (UTF8)
        $phpmailer->CharSet = "UTF-8";

        // Sujet du mail
        $phpmailer->Subject = "Réinitialisation du mot de passe";

        // Corps du mail
        $phpmailer->Body = "<a href=\"". HOST ."/new_password.php?token={$token}\">Réinitialisation du mot de passe</a>";

        // Envoi de l'email
        $phpmailer->send();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oubli d'un mot de passe</title>
</head>

<body>
    <h1>J'ai oublié mon mot de passe :(</h1>

    <form action="" method="post">
        <div class="form-group">
            <label for="inputEmail">Email :</label>
            <input type="email" name="email" id="inputEmail">
        </div>

        <input type="submit" value="Envoyer">
    </form>
</body>

</html>