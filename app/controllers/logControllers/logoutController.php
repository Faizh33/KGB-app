<?php
session_start();

// Destruction de la session
unset($_SESSION['admin']);

// Suppression du cookie de session
if (isset($_COOKIE['PHPSESSID'])) {
    setcookie('PHPSESSID', '', time() - 3600, '/');
}

// Redirection vers la page d'accueil
header('Location: ../../views/home.php');
exit();
?>
