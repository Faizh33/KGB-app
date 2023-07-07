<?php

use app\classes\Contact;

include_once "../../config/database.php";
include_once "../helpers/dataHelpers.php";
include_once "../classes/Contact.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Vérifier si les informations sont passées dans le POST
    if(isset($_POST["contactLastName"]) && isset($_POST["contactFirstName"]) && isset($_POST["contactBirthDate"]) && isset($_POST["contactNationality"]) && isset($_POST["contactIdCode"])) {
        $lastName = valid_datas($_POST["contactLastName"]);
        $firstName = valid_datas($_POST["contactFirstName"]);
        $birthDate = valid_datas($_POST["contactBirthDate"]);
        $nationality = valid_datas($_POST["contactNationality"]);
        $idCode = valid_datas($_POST["contactIdCode"]);

        $uuid = uniqid(); // Génère un UUID

        //Création d'un nouvel objet Agent et insertion des données
        $contactObj = new Contact($pdo);
        $contact = $contactObj::addContact($lastName, $firstName, $birthDate, $nationality, $idCode);

        //Si l'ajout en base de données à réussi : redirection vers la page de création
        if(isset($contact)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouveau contact ajouté en base de données</div>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '../views/dashboardCreate.php';
                }, 3000);
            </script>";
            exit;
        }        
    }
}