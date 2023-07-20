<?php

use app\classes\MissionType;

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/MissionType.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST["type"])) {
        //Vérifier si les informations sont passées dans le POST
        $type = valid_datas($_POST["type"]);

        //Création d'un nouvel objet MissionType et insertion des données
        $typeObj = new MissionType($pdo);
        $type = $typeObj::addMissionType($type);

        //Si l'ajout en base de données à réussi : redirection vers la page de création
        if(isset($type)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouveau type de mission ajoutée en base de données</div>";
            echo "<div style='color:rgb(3, 114, 103);font-style:italic'>Redirection dans 3 secondes</div>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '/dashboard-create';
                }, 3000);
            </script>";
            exit;
        }
    }
}