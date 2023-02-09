<?php
$message = "";
if (!empty($_POST)) {
    $email = trim(strip_tags($_POST["email"]));
    $password = trim(strip_tags($_POST["password"]));

    $dsn = "mysql:host=localhost;dbname=startauth";
    $db = new PDO($dsn, "root", "");
    // Récupération de l'utilisateur à partir de l'email
    $query = $db->prepare("SELECT * FROM users WHERE email LIKE :email");
    $query->bindParam(":email", $email);
    $query->execute();
    $result = $query->fetch();

    // if($password === $result["password"]) pas possible de le tester comme ça car nous avons d'un côté une donnée non cryptée et de l'autre une donnée cryptée
    // if(password_hash($password, PASSWORD_DEFAULT) === $result["password"]) pas possible non plus car le hash généré par password_hash change à chaque appel
    
    // password_verify va nous permettre de vérifier la correspondance entre le mot de passe saisi et le hash stocké en BDD
    // la fonction va nous retourner TRUE si le mot de passe est ok OU FALSE si pas ok
    if (!empty($result) && password_verify($password, $result["password"])) {
        // Les informations de connexion sont correctes on peut donner accès à l'utilisateur à des pages protégées
        // Démarrage du système de session
        session_start();
        // Création d'une variable de session
        // On stocke l'adresse ip de l'utilisateur pour palier à une possible attaque "session hijacking"
        // Pour obtenir l'adresse ip de l'utilisateur / du client on utilise : $_SERVER["REMOTE_ADDR"]
        $_SESSION["user"] = [
            "id" => $result["id"],
            "firstname" => $result["firstname"],
            "ip" => $_SERVER["REMOTE_ADDR"]
        ];

        // Redirection vers la page d'accueil
        header("Location: index.php");
    } else {
        // Les informations saisies sont incorrectes
        $message = "<p>Impossible de se connecter avec les informations saisies, veuillez réessayer</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion à l'espace utilisateur</title>
</head>

<body>
    <h1>Connexion à l'espace utilisateur</h1>

    <?= $message ?>

    <form action="" method="post">
        <div class="form-group">
            <label for="inputEmail">Email :</label>
            <input type="email" name="email" id="inputEmail">
        </div>
        <div class="form-group">
            <label for="inputPassword">Mot de passe :</label>
            <input type="password" name="password" id="inputPassword">
        </div>
        <input type="submit" value="Se connecter">
    </form>
    <a href="reset_password.php">J'ai oublié mon mot de passe</a>
</body>

</html>