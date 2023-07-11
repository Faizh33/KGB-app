<?php

use app\classes\Mission;
use app\classes\Speciality;
use app\classes\MissionStatus;
use app\classes\MissionType;

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Mission.php";
include_once "../../classes/Speciality.php";
include_once "../../classes/MissionStatus.php";
include_once "../../classes/MissionType.php";

$specialityObj = new Speciality($pdo);
$missionStatusObj = new MissionStatus($pdo);
$missionTypeObj = new MissionType($pdo);


// Vérifie si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupère les données soumises via POST
    if(isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["codeName"]) && isset($_POST["country"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["missionType"]) && isset($_POST["missionSpeciality"]) && isset($_POST["missionStatus"]) && isset($_POST["agents"]) && isset($_POST["contacts"]) && isset($_POST["targets"]) && isset($_POST["safeHouses"])) {
        $title = valid_datas($_POST["title"]);
        $description = valid_datas($_POST["description"]);
        $codeName = valid_datas($_POST["codeName"]);
        $country = valid_datas($_POST["country"]);
        $startDate = ($_POST["startDate"]);
        $endDate = ($_POST["endDate"]);
        $specialityId = ($_POST["missionSpeciality"]);
        $missionStatusId = ($_POST["missionStatus"]); 
        $missionTypeId = ($_POST["missionType"]);
        $agents = ($_POST["agents"]);
        $contacts = ($_POST["contacts"]);
        $targets = ($_POST["targets"]);
        $safeHouses = ($_POST["safeHouses"]);     

        // Récupère les objets de spécialité, statut de mission et type de mission à partir de leurs identifiants
        $speciality = $specialityObj::getSpecialityById($specialityId);
        $missionStatus = $missionStatusObj::getMissionStatusById($missionStatusId);
        $missionType = $missionTypeObj::getMissionTypeById($missionStatusId);

        // Crée une nouvelle instance de la classe Mission
        $mission = new Mission($pdo);

        // Ajoute une nouvelle mission à la base de données
        $newMission = $mission::addMission($title, $description, $codeName, $country, $startDate, $endDate, $speciality, $missionStatus, $missionType, $agents, $contacts, $targets, $safeHouses);

        if(isset($newMission)) {
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouvelle mission ajoutée en base de données</div>";
            echo "<div style='color:rgb(3, 114, 103);font-style:italic'>Redirection dans 3 secondes</div>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '../../views/dashboardCreate.php';
                }, 3000);
            </script>";
            exit;
        }  
    } else {
        echo "Tous les champs sont requis.";
    }
} else {
    echo "La requête doit être de type POST.";
}
