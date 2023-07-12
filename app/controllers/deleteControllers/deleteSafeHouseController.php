<?php
require_once '../../classes/SafeHouse.php';
require '../../../config/database.php';

use app\classes\SafeHouse;

if (isset($_POST['safeHouseId'])) {
    $safeHouseId = $_POST['safeHouseId'];

    // Créez une instance de la classe SafeHouse en utilisant le $pdo
    $safeHouse = new SafeHouse($pdo);
    // Chargez les détails de la planque correspondante
    $safeHouse->getSafeHouseById($safeHouseId);
    // Supprimez la planque
    $safeHouse->deleteSafeHouseById($safeHouseId);
}
?>