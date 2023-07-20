<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Speciality.php";

use app\classes\Speciality;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'id est passé dans le POST
    if (isset($_POST["specialityId"])) {
        $specialityId = $_POST["specialityId"];
    }

    $specialityObj = new Speciality($pdo);
    $speciality = $specialityObj::getSpecialityById($specialityId);

    // Vérifier si les valeurs POST existent et ne sont pas vides
    // Si elles sont vides, conserver les valeurs existantes
    $specialityName = isset($_POST["speciality"]) && $_POST["speciality"] !== '' ? valid_datas($_POST["speciality"]) : $speciality->getSpeciality();

    $propertiesToUpdate = [
        'speciality' => $specialityName
    ];

    //Insertion des nouvelles données
    $speciality = $specialityObj::updateSpecialityProperties($specialityId, $propertiesToUpdate);
    
    //Si la modification en base de données à réussi : redirection vers la page de création
    if(isset($speciality)) {
        echo "<br><div style='font-weight:bold;color:rgb(3, 114, 103)'>Données modifiées en base de données</div><br>";
        echo "<div style='color:rgb(3, 114, 103);font-style:italic'>Redirection dans 3 secondes</div>";
        echo "<script>
            setTimeout(function() {
                window.location.href = '/dashboard-edit';
            }, 3000);
        </script>";
        exit;
    } 
}
