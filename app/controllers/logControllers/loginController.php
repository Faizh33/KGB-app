<?php
session_start();

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