<?php

include_once "../../config/database.php";
include_once "../helpers/dataHelpers.php";
include_once "../classes/MissionStatus.php";

use app\classes\MissionStatus;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["status"])) {
        //Vérifier si les informations sont passées dans le POST
        $status = valid_datas($_POST["status"]);

        //Création d'un nouvel objet MissionStatus et insertion des données
        $statusObj = new MissionStatus($pdo);
        $status = $statusObj::addMissionStatus($status);

        //Si l'ajout en base de données à réussi : redirection vers la page de création
        if(isset($status)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouveau statut de mission ajoutée en base de données</div>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '../views/dashboardCreate.php';
                }, 3000);
            </script>";
            exit;
        }
    }
}