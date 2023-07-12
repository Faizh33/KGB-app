<?php
require_once '../../classes/MissionType.php';
require '../../../config/database.php';

use app\classes\MissionType;

if (isset($_POST['typeId'])) {
    $typeId = $_POST['typeId'];

    // Créez une instance de la classe MissionType en utilisant le $pdo
    $type = new MissionType($pdo);
    // Chargez les détails du type de mission correspondant
    $type->getMissionTypeById($typeId);
    // Supprimez le type de mission
    $type->deleteMissionTypeById($typeId);
}
?>