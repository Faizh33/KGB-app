<?php
require_once '../../classes/Mission.php';
require '../../../config/database.php';

use app\classes\Mission;

if (isset($_GET['mission'])) {
    $missionId = $_GET['mission'];

    // Créez une instance de la classe Mission en utilisant le $pdo
    $mission = new Mission($pdo);
    // Chargez les détails de la mission correspondante
    $mission->getMissionById($missionId);
    // Supprimez la mission
    $mission->deleteMissionById($missionId);
}

// Redirection vers la page d'accueil
header("Location: ../../views/home.php");
exit();
?>
