<?php

use app\classes\Target;
use app\classes\CountryNationality;

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Target.php";
include_once "../../classes/CountryNationality.php";

$countryNationalityObj = new CountryNationality($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Vérifier si les informations sont passées dans le POST
    if(isset($_POST["targetLastName"]) && isset($_POST["targetFirstName"]) && isset($_POST["targetBirthDate"]) && isset($_POST["targetNationality"]) && isset($_POST["targetIdCode"])) {
        $lastName = valid_datas($_POST["targetLastName"]);
        $firstName = valid_datas($_POST["targetFirstName"]);
        $birthDate = valid_datas($_POST["targetBirthDate"]);
        $nationality = $countryNationalityObj->getCountryNationalityById($_POST["targetNationality"]);
        $idCode = valid_datas($_POST["targetIdCode"]);

        //Récupération de l'id de la nationalité
        $nationalityId = $nationality->getId();

        //Création d'un nouvel objet Target et insertion des données
        $targetObj = new Target($pdo);
        $target = $targetObj::addTargetProperties($lastName, $firstName, $birthDate, $nationalityId, $idCode);

        //Si l'ajout en base de données à réussi : redirection vers la page de création
        if(isset($target)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouvelle cible ajoutée en base de données</div>";
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