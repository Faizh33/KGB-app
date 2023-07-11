<?php
require_once '../classes/Mission.php';
require '../../config/database.php';

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

// Redirigez l'utilisateur vers la page d'accueil ou une autre page appropriée
header("Location: ../views/home.php");
exit();
?>
