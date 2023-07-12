<?php
require_once '../../classes/MissionStatus.php';
require '../../../config/database.php';

use app\classes\MissionStatus;

if (isset($_POST['statusId'])) {
    $statusId = $_POST['statusId'];

    // Créez une instance de la classe Status en utilisant le $pdo
    $status = new MissionStatus($pdo);
    // Chargez les détails de la planque correspondante
    $status->getMissionStatusById($statusId);
    // Supprimez la planque
    $status->deleteMissionStatusById($statusId);
}
?>