<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/CountryNationality.php";

use app\classes\CountryNationality;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["country"]) && isset($_POST["nationality"])) {
        //Vérifier si les informations sont passées dans le POST
        $country = valid_datas($_POST["country"]);
        $nationality = valid_datas($_POST["nationality"]);

        //Création d'un nouvel objet CountryNationality et insertion des données
        $countryNationalityObj = new CountryNationality($pdo);
        $countryNationality = $countryNationalityObj::addCountryNationality($country, $nationality);

        //Si l'ajout en base de données à réussi : redirection vers la page de création
        if(isset($countryNationality)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouvelle association pays/nationalité ajoutée en base de données</div>";
            echo "<div style='color:rgb(3, 114, 103);font-style:italic'>Redirection dans 3 secondes</div>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '../../views/dashboardCreate.php';
                }, 3000);
            </script>";
            exit;
        }
    }
}