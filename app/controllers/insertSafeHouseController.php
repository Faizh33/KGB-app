<?php

include_once "../../config/database.php";
include_once "../helpers/dataHelpers.php";
include_once "../classes/SafeHouse.php";

use app\classes\SafeHouse;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Vérifier si les informations sont passées dans le POST
    if(isset($_POST["code"]) && isset($_POST["address"]) && isset($_POST["country"]) && isset($_POST["type"])) {
        $codeName = valid_datas($_POST["code"]);
        $address = valid_datas($_POST["address"]);
        $country = valid_datas($_POST["country"]);
        $type = valid_datas($_POST["type"]);

        //Création d'un nouvel objet Agent et insertion des données
        $safeHouseObj = new SafeHouse($pdo);
        $safeHouse = $safeHouseObj::addSafeHouse($codeName, $address, $country, $type);

        //Si l'ajout en base de données à réussi : redirection vers la page de création
        if(isset($safeHouse)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouvelle planque ajouté en base de données</div>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '../views/dashboardCreate.php';
                }, 3000);
            </script>";
            exit;
        }  
    }
}