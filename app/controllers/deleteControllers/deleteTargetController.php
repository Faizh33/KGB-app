<?php
require_once '../../classes/Target.php';
require '../../../config/database.php';

use app\classes\Target;

if (isset($_POST['targetId'])) {
    $targetId = $_POST['targetId'];

    // Créez une instance de la classe target en utilisant le $pdo
    $target = new Target($pdo);
    // Chargez les détails de la cible correspondante
    $target->getTargetById($targetId);
    // Supprimez la cible
    $target->deleteTargetById($targetId);
}
?>