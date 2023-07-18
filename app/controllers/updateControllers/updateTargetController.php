<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Target.php";

use app\classes\Target;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'id est passé dans le POST
    if (isset($_POST["targetId"])) {
        $targetId = $_POST["targetId"];
    }

    $targetObj = new Target($pdo);
    $target = $targetObj::getTargetById($targetId);

    // Vérifier si les valeurs POST existent et ne sont pas vides
    // Si elles sont vides, conserver les valeurs existantes
    $lastName = isset($_POST["lastName"]) && $_POST["lastName"] !== '' ? valid_datas($_POST["lastName"]) : $target->getLastName();
    $firstName = isset($_POST["firstName"]) && $_POST["firstName"] !== '' ? valid_datas($_POST["firstName"]) : $target->getFirstName();
    $birthDateInput = isset($_POST["birthDate"]) ? $_POST["birthDate"] : $target->getBirthDate();
    $birthDate = $birthDateInput !== '' ? DateTime::createFromFormat('d/m/Y', $birthDateInput)->format('Y-m-d') : $target->getBirthDate();    
    $nationality = isset($_POST["nationality"]) && $_POST["nationality"] !== '' ? valid_datas($_POST["nationality"]) : $target->getNationality()->getId();
    $codeName = isset($_POST["codeName"]) && $_POST["codeName"] !== '' ? valid_datas($_POST["codeName"]) : $target->getCodeName();

    $propertiesToUpdate = [
        'lastName' => $lastName,
        'firstName' => $firstName,
        'birthDate' => $birthDate,
        'nationality' => $nationality,
        'codeName' => $codeName
    ];

    //Insertion des nouvelles données
    $target = $targetObj::updateTargetProperties($targetId, $propertiesToUpdate);
    
    //Si la modification en base de données à réussi : redirection vers la page de création
    if(isset($target)) {
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
