<?php
if (!empty($_POST)) {
    $errors = array();

    // Le formulaire a été soumis
    $firstname = trim(strip_tags($_POST["firstname"]));
    $email = trim(strip_tags($_POST["email"]));
    $password = trim(strip_tags($_POST["password"]));

    // VALIDATION DE L'EMAIL
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "L'email n'est pas valide";
    }

    // VALIDATION DU MOT DE PASSE
    // Pour valider notre mot de passe nous allons vérifier :
    // - la taille du mot de passe --> strlen()
    // - la présence d'une majuscule, d'une minuscule et d'un chiffre --> les expressions régulières avec la fonction preg_match()
    // Par exemple pour rechercher une lettre majuscule n'importe où dans la chaine nous allons utiliser une syntaxe [A-Z]
    // Exemple pour un caractère spécial : [^a-zA-Z0-9]
    // Correspond à la chaine toto : toto
    // Commence par toto : ^toto
    // Termine par toto : toto$
    // Contient toto : ^toto$
    // Contient trois lettres majuscules : [A-Z]{3}
    // Commence par http ou https : ^(http|https)

    // On valide la présence ou non d'une lettre majuscule
    $uppercase = preg_match("/[A-Z]/", $password);
    // On valide la présence ou non d'une lettre minuscule
    $lowercase = preg_match("/[a-z]/", $password);
    // On valide la présence ou non d'un chiffre
    $number = preg_match("/[0-9]/", $password);

    if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
        $errors["password"] = "Le mot de passe doit contenir 8 caractères minimum, une lettre majuscule, un chiffre et un caractère spécial";
    }

    // Si pas d'erreur
    if (empty($errors)) {
        // Vous verrez peut-être l'utilisation de md5($password) mais c'est à bannir !!!
        // En effet les hash md5 se décryptent très facilement aujourd'hui
        // Cryptage du mot de passe (pas d'insertion du mot de passe en bdd sans cryptage)
        // La méthode de cryptage par défaut est le bcrypt
        // Il est également possible d'utiliser le argon2
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $dsn = "mysql:host=localhost;dbname=startauth";
        $db = new PDO($dsn, "root", "");
        $query = $db->prepare("INSERT INTO users (firstname, email, password) VALUES (:firstname, :email, :password)");
        $query->bindParam(":firstname", $firstname);
        $query->bindParam(":email", $email);
        $query->bindParam(":password", $hash);

        if ($query->execute()) {
            // Rediriger l'utilisateur vers la page de connexion
            header("Location: login.php");
        } else {
            // Requête ne s'est pas bien déroulée
            $errors["execute"] = "Un problème est survenu veuillez réessayer ultérieurement";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'un compte utilisateur</title>
</head>

<body>
    <h1>Création d'un compte utilisateur</h1>

    <form action="" method="post">
        <div class="form-group">
            <label for="inputFirstname">Prénom :</label>
            <input type="text" name="firstname" id="inputFirstname">
        </div>
        <div class="form-group">
            <label for="inputEmail">Email :</label>
            <input type="email" name="email" id="inputEmail" value="<?= $email ?? "" ?>">
            <!-- Equivalent avec un ternaire : 
            <input type="email" name="email" id="inputEmail" value="<?= isset($email) ? $email : "" ?>"> -->
            <?php
            if (isset($errors["email"])) {
            ?>
                <p class="error"><?= $errors["email"] ?></p>
            <?php
            }
            ?>
        </div>
        <div class="form-group">
            <label for="inputPassword">Mot de passe :</label>
            <input type="password" name="password" id="inputPassword" value="<?= $password ?? "" ?>">
            <?php
            if (isset($errors["password"])) {
            ?>
                <p class="error"><?= $errors["password"] ?></p>
            <?php
            }
            ?>
        </div>

        <input type="submit" value="Création du compte">
    </form>
</body>

</html>