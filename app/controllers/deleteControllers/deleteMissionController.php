<?php
require_once '../../classes/Mission.php';
require '../../../config/database.php';

use app\classes\Mission;

if (isset($_POST['missionId'])) {
    $missionId = $_POST['missionId'];

    // Créez une instance de la classe Mission en utilisant le $pdo
    $mission = new Mission($pdo);
    // Chargez les détails de la mission correspondante
    $mission->getMissionById($missionId);
    // Supprimez la mission
    $mission->deleteMissionById($missionId);
}
?>