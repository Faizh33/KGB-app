<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/MissionStatus.php";

use app\classes\MissionStatus;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'id est passé dans le POST
    if (isset($_POST["statusId"])) {
        $statusId = $_POST["statusId"];
    }

    $statusObj = new MissionStatus($pdo);
    $status = $statusObj::getMissionStatusById($statusId);

    // Vérifier si les valeurs POST existent et ne sont pas vides
    // Si elles sont vides, conserver les valeurs existantes
    $statusName = isset($_POST["status"]) && $_POST["status"] !== '' ? valid_datas($_POST["status"]) : $status->getStatus();

    $propertiesToUpdate = [
        'status' => $statusName
    ];

    //Insertion des nouvelles données
    $status = $statusObj::updateMissionStatusProperties($statusId, $propertiesToUpdate);
    
    //Si la modification en base de données à réussi : redirection vers la page de création
    if(isset($status)) {
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
