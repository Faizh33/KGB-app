<?php

include_once "../../config/database.php";
include_once "../helpers/dataHelpers.php";
include_once "../classes/Agent.php";

use app\classes\Agent;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Vérifier si les informations sont passées dans le POST
    if(isset($_POST["agentLastName"]) && isset($_POST["agentFirstName"]) && isset($_POST["agentBirthDate"]) && isset($_POST["agentNationality"]) && isset($_POST["agentIdCode"])) {
        $lastName = valid_datas($_POST["agentLastName"]);
        $firstName = valid_datas($_POST["agentFirstName"]);
        $birthDate = valid_datas($_POST["agentBirthDate"]);
        $nationality = valid_datas($_POST["agentNationality"]);
        $idCode = valid_datas($_POST["agentIdCode"]);

        //Création d'un nouvel objet Agent et insertion des données
        $agent = new Agent($pdo);
        $agent->addAgentProperties($lastName, $firstName, $birthDate, $nationality, $idCode);
        
        //Si l'ajout en base de données à réussi : redirection vers la page de création
        if(isset($agent)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouvel agent ajouté en base de données</div>";
            header('refresh:5;Location: ../views/dashboardCreate.php');
        }
    }
}
