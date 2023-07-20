<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Agent.php";
include_once "../../classes/CountryNationality.php";

use app\classes\Agent;
use app\classes\CountryNationality;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'id est passé dans le POST
    if (isset($_POST["agentId"])) {
        $agentId = $_POST["agentId"];
    }

    $agentObj = new Agent($pdo);
    $agent = $agentObj::getAgentById($agentId);

    // Vérifier si les valeurs POST existent et ne sont pas vides
    // Si elles sont vides, conserver les valeurs existantes
    $lastName = isset($_POST["agentLastName"]) && $_POST["agentLastName"] !== '' ? valid_datas($_POST["agentLastName"]) : $agent->getLastName();
    $firstName = isset($_POST["agentFirstName"]) && $_POST["agentFirstName"] !== '' ? valid_datas($_POST["agentFirstName"]) : $agent->getFirstName();
    $birthDateInput = isset($_POST["agentBirthDate"]) ? $_POST["agentBirthDate"] : $agent->getBirthDate();
    $birthDate = $birthDateInput !== '' ? DateTime::createFromFormat('d/m/Y', $birthDateInput)->format('Y-m-d') : $agent->getBirthDate();    
    $nationality = isset($_POST["agentNationality"]) && $_POST["agentNationality"] !== '' ? valid_datas($_POST["agentNationality"]) : $agent->getNationality()->getId();
    $idCode = isset($_POST["agentIdCode"]) && $_POST["agentIdCode"] !== '' ? valid_datas($_POST["agentIdCode"]) : $agent->getIdentificationCode();

    $propertiesToUpdate = [
        'lastName' => $lastName,
        'firstName' => $firstName,
        'birthDate' => $birthDate,
        'nationality' => $nationality,
        'identificationCode' => $idCode
    ];

    //Insertion des nouvelles données
    $agent = $agentObj::updateAgentProperties($agentId, $propertiesToUpdate);
    
    //Si la modification en base de données à réussi : redirection vers la page de création
    if(isset($agent)) {
        echo "<br><div style='font-weight:bold;color:rgb(3, 114, 103)'>Données modifiées en base de données</div><br>";
        echo "<div style='color:rgb(3, 114, 103);font-style:italic'>Redirection dans 3 secondes</div>";
        echo "<script>
            setTimeout(function() {
                window.location.href = '../../views/dashboardEdit.php';
            }, 3000);
        </script>";
        exit;
    } 
}
