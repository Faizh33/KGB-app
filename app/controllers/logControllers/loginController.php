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
        echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Identifiant ou Mot De Passe incorrect. <br> Redirection vers la page de connexion.</div><br/>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = '/login';
                }, 3000);
            </script>";
        exit();
    } else {
        $_SESSION['admin'] = true;
        header("Location: /");
        exit();
        }
    };