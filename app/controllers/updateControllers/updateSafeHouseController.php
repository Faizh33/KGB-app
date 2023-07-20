<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/SafeHouse.php";

use app\classes\SafeHouse;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'id est passé dans le POST
    if (isset($_POST["safeHouseId"])) {
        $safeHouseId = $_POST["safeHouseId"];
    }

    $safeHouseObj = new SafeHouse($pdo);
    $safeHouse = $safeHouseObj::getSafeHouseById($safeHouseId);

    // Vérifier si les valeurs POST existent et ne sont pas vides
    // Si elles sont vides, conserver les valeurs existantes
    $code = isset($_POST["safeHouseCode"]) && $_POST["safeHouseCode"] !== '' ? valid_datas($_POST["safeHouseCode"]) : $safeHouse->getCode();
    $address = isset($_POST["safeHouseAddress"]) && $_POST["safeHouseAddress"] !== '' ? valid_datas($_POST["safeHouseAddress"]) : $safeHouse->getAddress();  
    $country = isset($_POST["safeHouseCountry"]) && $_POST["safeHouseCountry"] !== '' ? valid_datas($_POST["safeHouseCountry"]) : $safeHouse->getCountry()->getId();
    $type = isset($_POST["safeHouseType"]) && $_POST["safeHouseType"] !== '' ? valid_datas($_POST["safeHouseType"]) : $safeHouse->getType();

    $propertiesToUpdate = [
        'code' => $code,
        'address' => $address,
        'country' => $country,
        'type' => $type
    ];

    //Insertion des nouvelles données
    $safeHouse = $safeHouseObj::updateSafeHouseProperties($safeHouseId, $propertiesToUpdate);
    
    //Si la modification en base de données à réussi : redirection vers la page de création
    if(isset($safeHouse)) {
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
