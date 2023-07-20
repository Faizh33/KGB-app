<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Speciality.php";

use app\classes\Speciality;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Vérifier si les informations sont passées dans le POST
    if(isset($_POST["speciality"])) {
        $speciality = valid_datas($_POST["speciality"]);

        //Création d'un nouvel objet Speciality et insertion des données
        $specialityObj = new Speciality($pdo);
        $speciality = $specialityObj::addSpeciality($speciality);

        //Si l'ajout en base de données à réussi : redirection vers la page de création
        if(isset($speciality)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouvelle spécialité ajoutée en base de données</div>";
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