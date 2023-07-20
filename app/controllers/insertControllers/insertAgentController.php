<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Agent.php";
include_once "../../classes/CountryNationality.php";

use app\classes\Agent;
use app\classes\CountryNationality;

$countryNationalityObj = new CountryNationality($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Vérifier si les informations sont passées dans le POST
    if(isset($_POST["agentLastName"]) && isset($_POST["agentFirstName"]) && isset($_POST["agentBirthDate"]) && isset($_POST["agentNationality"]) && isset($_POST["agentIdCode"]) && isset($_POST["specialities"])) {
        $lastName = valid_datas($_POST["agentLastName"]);
        $firstName = valid_datas($_POST["agentFirstName"]);
        $birthDate = valid_datas($_POST["agentBirthDate"]);
        $nationality = $countryNationalityObj->getCountryNationalityById($_POST["agentNationality"]);
        $idCode = valid_datas($_POST["agentIdCode"]);
        $specialities = $_POST["specialities"];

        //Récupération de l'id de la nationalité
        $nationalityId = $nationality->getId();

        //Création d'un nouvel objet Agent et insertion des données
        $agentObj = new Agent($pdo);
        $agent = $agentObj::addAgentProperties($lastName, $firstName, $birthDate, $nationalityId, $idCode, $specialities);
        
        //Si l'ajout en base de données à réussi : redirection vers la page de création
        if(isset($agent)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouvel agent ajouté en base de données</div>";
            echo "<div style='color:rgb(3, 114, 103);font-style:italic'>Redirection dans 3 secondes</div>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '/dashboard-create';
                }, 3000);
            </script>";
            exit;
        }  
        
    }
}
