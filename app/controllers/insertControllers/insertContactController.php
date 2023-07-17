<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Contact.php";
include_once "../../classes/CountryNationality.php";

use app\classes\Contact;
use app\classes\CountryNationality;

$countryNationalityObj = new CountryNationality($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Vérifier si les informations sont passées dans le POST
    if(isset($_POST["contactLastName"]) && isset($_POST["contactFirstName"]) && isset($_POST["contactBirthDate"]) && isset($_POST["contactNationality"]) && isset($_POST["contactIdCode"])) {
        $lastName = valid_datas($_POST["contactLastName"]);
        $firstName = valid_datas($_POST["contactFirstName"]);
        $birthDate = valid_datas($_POST["contactBirthDate"]);
        $nationality = $countryNationalityObj->getCountryNationalityById($_POST["contactNationality"]);
        $codeName = valid_datas($_POST["contactIdCode"]);

        //Récupération de l'id de la nationalité
        $nationalityId = $nationality->getId();

        //Création d'un nouvel objet Contact et insertion des données
        $contactObj = new Contact($pdo);
        $contact = $contactObj::addContactProperties($lastName, $firstName, $birthDate, $nationalityId, $codeName);

        //Si l'ajout en base de données à réussi : redirection vers la page de création
        if(isset($contact)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouveau contact ajouté en base de données</div>";
            echo "<div style='color:rgb(3, 114, 103);font-style:italic'>Redirection dans 3 secondes</div>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '../../views/dashboardCreate.php';
                }, 3000);
            </script>";
            exit;
        }        
    }
}