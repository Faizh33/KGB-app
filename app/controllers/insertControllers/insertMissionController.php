<?php

use app\classes\Mission;
use app\classes\Speciality;
use app\classes\MissionStatus;
use app\classes\MissionType;
use app\classes\Agent;
use app\classes\Target;
use app\classes\Contact;
use app\classes\SafeHouse;
use app\classes\AgentSpeciality;
use app\classes\CountryNationality;

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Mission.php";
include_once "../../classes/Speciality.php";
include_once "../../classes/MissionStatus.php";
include_once "../../classes/MissionType.php";
include_once "../../classes/Agent.php";
include_once "../../classes/Target.php";
include_once "../../classes/Contact.php";
include_once "../../classes/SafeHouse.php";
include_once "../../classes/AgentSpeciality.php";
include_once "../../classes/CountryNationality.php";

$specialityObj = new Speciality($pdo);
$missionStatusObj = new MissionStatus($pdo);
$missionTypeObj = new MissionType($pdo);
$countryNationalityObj = new CountryNationality($pdo);

// Vérifie si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupère les données soumises via POST
    if(isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["codeName"]) && isset($_POST["country"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["missionType"]) && isset($_POST["missionSpeciality"]) && isset($_POST["missionStatus"]) && isset($_POST["agents"]) && isset($_POST["contacts"]) && isset($_POST["targets"]) && isset($_POST["safeHouses"])) {
        $title = valid_datas($_POST["title"]);
        $description = valid_datas($_POST["description"]);
        $codeName = valid_datas($_POST["codeName"]);
        $country = $countryNationalityObj::getCountryNationalityById($_POST["country"]);
        $startDate = ($_POST["startDate"]);
        $endDate = ($_POST["endDate"]);
        $specialityId = ($_POST["missionSpeciality"]);
        $missionStatusId = ($_POST["missionStatus"]); 
        $missionTypeId = ($_POST["missionType"]);
        $agentsId = ($_POST["agents"]);
        $contactsId = ($_POST["contacts"]);
        $targetsId = ($_POST["targets"]);
        $safeHousesId = ($_POST["safeHouses"]); 

        //Récupère les objets de spécialité, statut de mission et type de mission à partir de leurs identifiants
        $speciality = $specialityObj::getSpecialityById($specialityId);
        $missionStatus = $missionStatusObj::getMissionStatusById($missionStatusId);
        $missionType = $missionTypeObj::getMissionTypeById($missionStatusId);

        //Création de tableaux pour stocker les différents id
        $targetsNationalityId = array();
        $contactsNationalityId = array(); 
        $agentsNationalityId = array();
        $agentsSpecialityId = array();
        $safeHousesCountryId = array();

        //Récupération de la nationalité des cibles et des contacts
        foreach($targetsId as $targetId) {
            $targetObj = new Target($pdo);
            $target = $targetObj::getTargetById($targetId);
            $targetNationality = $countryNationalityObj::getCountryNationalityById($target->getNationality()->getId());
            $targetNationalityId = $targetNationality->getId();
            $targetsNationalityId[] = $targetNationalityId;
        }

        foreach($contactsId as $contactId) {
            $contactObj = new Contact($pdo);
            $contact = $contactObj::getContactById($contactId);
            $contactNationality = $countryNationalityObj::getCountryNationalityById($contact->getNationality()->getId());
            $contactNationalityId = $contactNationality->getId();
            $contactsNationalityId[] = $targetNationalityId;
        }

        //Récupération de la nationalité et de la spécialité des agents
        foreach($agentsId as $agentId) {
            $agentObj = new Agent($pdo);
            $agent = $agentObj::getAgentById($agentId);
            $agentNationality = $countryNationalityObj::getCountryNationalityById($agent->getNationality()->getId());
            $agentNationalityId = $agentNationality->getId();
            $agentsNationalityId[] = $agentNationalityId;

            $agentSpecialityObj = new AgentSpeciality($pdo);
            $agentSpeciality = $agentSpecialityObj::getSpecialitiesByAgentId($agentId);
            $agentSpecialityId = $agentSpeciality[0]->getSpecialityId();
            $agentsSpecialityId[] = $agentSpeciality;
        }

        //Récupération du pays des planques
        foreach($safeHousesId as $safeHouseId) {
            $safeHouseObj = new SafeHouse($pdo);
            $safeHouse = $safeHouseObj::getSafeHouseById($safeHouseId);
            $safeHouseCountry = $countryNationalityObj::getCountryNationalityById($safeHouse->getCountry()->getId());
            $safeHouseCountryId = $safeHouseCountry->getId();
            $safeHousesCountryId[] = $safeHouseCountryId;
        }

        // Vérification des contraintes
        $constraintsSatisfied = true;

        // Vérification de la contrainte : la date de début doit être avant la date de fin
        if (strtotime($startDate) > strtotime($endDate)) {
            $constraintsSatisfied = false;
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Erreur : La date de début ne peut pas être après la date de fin.</div>";
        }

        // Vérification de la contrainte : les cibles ne peuvent pas avoir la même nationalité que les agents
        foreach ($targetsNationalityId as $targetNationalityId) {
            if (in_array($targetNationalityId, $agentsNationalityId)) {
                $constraintsSatisfied = false;
                echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Erreur : Les cibles ne peuvent pas avoir la même nationalité que les agents.</div>";
                break;
            }
        }

        // Vérification de la contrainte : les contacts sont obligatoirement de la nationalité du pays de la mission
        if (!in_array($country->getId(), $contactsNationalityId)) {
            $constraintsSatisfied = false;
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Erreur : Les contacts doivent être de la nationalité du pays de la mission.</div>";
        }

        // Vérification de la contrainte : la planque est obligatoirement dans le même pays que la mission
        foreach ($safeHousesCountryId as $safeHouseCountryId) {
            if ($safeHouseCountryId !== $country->getId()) {
                $constraintsSatisfied = false;
                echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Erreur : La planque doit être dans le même pays que la mission.</div>";
                break;
            }
        }

        // Vérification de la contrainte : au moins 1 agent avec la spécialité requise
        $requiredSpecialityId = $speciality->getId();
        $hasRequiredSpeciality = false;
        foreach ($agentsSpecialityId as $agentSpecialityId) {
            if ($agentSpecialityId === $requiredSpecialityId) {
                $hasRequiredSpeciality = true;
                break;
            }
        }
        if (!$hasRequiredSpeciality) {
            $constraintsSatisfied = false;
            echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Erreur : Il faut assigner au moins 1 agent disposant de la spécialité requise.</div>";
        }

        // Si toutes les contraintes sont satisfaites, ajoute la mission à la base de données
        if ($constraintsSatisfied) {
            // Crée une nouvelle instance de la classe Mission
            $mission = new Mission($pdo);

            // Ajoute une nouvelle mission à la base de données
            $newMission = $mission::addMission($title, $description, $codeName, $country, $startDate, $endDate, $speciality, $missionStatus, $missionType, $agentsId, $contactsId, $targetsId, $safeHousesId);

            if (isset($newMission)) {
                echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Nouvelle mission ajoutée en base de données</div>";
                echo "<div style='color:rgb(3, 114, 103);font-style:italic'>Redirection dans 3 secondes</div>";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = '/dashboard-create';
                    }, 3000);
                </script>";
                exit;
            }
        }
    } else {
        echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>Tous les champs sont requis.</div>";
    }
} else {
    echo "<div style='font-weight:bold;color:rgb(3, 114, 103)'>La requête doit être de type POST.</div>";
}