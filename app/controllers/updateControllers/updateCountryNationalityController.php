<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/CountryNationality.php";

use app\classes\CountryNationality;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les données sont passées dans le POST
    if (isset($_POST["countryNationalityId"]) && isset($_POST["country"]) && isset($_POST["nationality"])) {
        $countryNationalityId = $_POST["countryNationalityId"];
        $country = $_POST["country"];
        $nationality = $_POST["nationality"];
    }

    $countryNationalityObj = new CountryNationality($pdo);
    $countryNationality = $countryNationalityObj->getCountryNationalityById($countryNationalityId);

    // Vérifier si les valeurs POST existent et ne sont pas vides
    // Si elles sont vides, conserver les valeurs existantes
    $countryName = isset($_POST["country"]) && $_POST["country"] !== '' ? valid_datas($_POST["country"]) : $countryNationality->getCountry();
    $nationalityName = isset($_POST["nationality"]) && $_POST["nationality"] !== '' ? valid_datas($_POST["nationality"]) : $countryNationality->getNationality();

    $propertiesToUpdate = [
        'country' => $countryName,
        'nationality' => $nationalityName
    ];

    //Insertion des nouvelles données
    $countryNationality = $countryNationalityObj->updateCountryNationalityProperties($countryNationalityId, $propertiesToUpdate);
    
    //Si la modification en base de données a réussi : redirection vers la page de création
    if(isset($countryNationality)) {
        echo "<br><div style='font-weight:bold;color:rgb(3, 114, 103)'>Données modifiées en base de données</div><br>";
        echo "<div style='color:rgb(3, 114, 103);font-style:italic'>Redirection dans 3 secondes</div>";
        echo "<script>
            setTimeout(function() {
                window.location.href = '../../views/dashboardEdit.php';
            }, 3000);
        </script>";
        exit;
    } 
}
