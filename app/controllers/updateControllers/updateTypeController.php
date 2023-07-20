<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/MissionType.php";

use app\classes\MissionType;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'id est passé dans le POST
    if (isset($_POST["typeId"])) {
        $typeId = $_POST["typeId"];
    }

    $typeObj = new MissionType($pdo);
    $type = $typeObj::getMissionTypeById($typeId);

    // Vérifier si les valeurs POST existent et ne sont pas vides
    // Si elles sont vides, conserver les valeurs existantes
    $typeName = isset($_POST["type"]) && $_POST["type"] !== '' ? valid_datas($_POST["type"]) : $type->gettype();

    $propertiesToUpdate = [
        'type' => $typeName
    ];

    //Insertion des nouvelles données
    $type = $typeObj::updateMissionTypeProperties($typeId, $propertiesToUpdate);
    
    //Si la modification en base de données à réussi : redirection vers la page de création
    if(isset($type)) {
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
