<?php
require_once '../../classes/Speciality.php';
require '../../../config/database.php';

use app\classes\Speciality;

if (isset($_POST['specialityId'])) {
    $specialityId = $_POST['specialityId'];

    // Créez une instance de la classe Speciality en utilisant le $pdo
    $speciality = new Speciality($pdo);
    // Chargez les détails de la spécialité correspondante
    $speciality->getSpecialityById($specialityId);
    // Supprimez la spécialité
    $speciality->deleteSpecialityById($specialityId);
}
?>