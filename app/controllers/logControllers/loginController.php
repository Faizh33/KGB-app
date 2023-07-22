<?php
session_start();

// Nombre maximum de tentatives infructueuses autorisées
$maxLoginAttempts = 5;

// Vérifier si l'utilisateur a dépassé le nombre maximum de tentatives infructueuses
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $maxLoginAttempts) {
    // Bloquer l'accès pour une période définie en ajoutant un délai à la prochaine tentative.
    $blockDuration = 5 * 60;
    $remainingTime = $blockDuration - (time() - $_SESSION['last_login_attempt']);
    
    if ($remainingTime > 0) {
        echo "Vous avez dépassé le nombre maximum de tentatives de connexion. Veuillez réessayer dans " . $remainingTime . " secondes.";
        exit();
    } else {
        // Réinitialisation du compteur de tentatives après la période de blocage
        unset($_SESSION['login_attempts']);
    }
}

require_once "../../../config/database.php";
require_once "../../classes/Admin.php";
use app\classes\Admin;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $adminObj = new Admin($pdo);

    if($adminObj->verifyCredentials($email, $password) == false) {
        echo 'Identifiant ou Mot De Passe incorrect. Redirection vers la page de connexion.<br/>';
        header("Refresh: 5; url=loginForm.php");
        exit();
    } else {
        $_SESSION['admin'] = true;
        header("Location: ../../views/home.php");
        exit();
        }
    };