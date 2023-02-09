<?php
// Ne pas oublier de démarrer le système de session sinon la fonction session_destroy() ne fonctionne pas
session_start();

// Détruire les variables de session pour faire une déconnexion
session_destroy();

// Rediriger vers la page d'accueil
header("Location: index.php");