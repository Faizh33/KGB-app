<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Mission.php";
include_once "../../classes/MissionAgent.php";
include_once "../../classes/MissionContact.php";
include_once "../../classes/MissionTarget.php";
include_once "../../classes/MissionSafeHouse.php";

use app\classes\Mission;
use app\classes\MissionAgent;
use app\classes\MissionContact;
use app\classes\MissionTarget;
use app\classes\MissionSafeHouse;
use app\classes\Speciality;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'id est passé dans le POST
    if (isset($_POST["missionId"])) {
        $missionId = $_POST["missionId"];
    }

    $missionObj = new Mission($pdo);
    $specialityObj = new Speciality($pdo);
    $mission = $missionObj::getMissionById($missionId);
    $missionAgentObj = new MissionAgent($pdo);
    $missionAgents = $missionAgentObj::getAgentsByMissionId($missionId);
    $missionContactObj = new MissionContact($pdo);
    $missionContacts = $missionContactObj::getContactsByMissionId($missionId);
    $missionTargetObj = new MissionTarget($pdo);
    $missionTargets = $missionTargetObj::getTargetsByMissionId($missionId);
    $missionSafeHouseObj = new MissionSafeHouse($pdo);
    $missionSafeHouses = $missionSafeHouseObj::getSafeHousesByMissionId($missionId);

    // Vérifier si les valeurs POST existent et ne sont pas vides
    // Si elles sont vides, conserver les valeurs existantes
    $title = isset($_POST["title"]) && $_POST["title"] !== '' ? valid_datas($_POST["title"]) : $mission->getTitle();
    $description = isset($_POST["description"]) && $_POST["description"] !== '' ? valid_datas($_POST["description"]) : $mission->getDescription();
    $codeName = isset($_POST["codeName"]) && $_POST["codeName"] !== '' ? valid_datas($_POST["codeName"]) : $mission->getCodeName();
    $country = isset($_POST["country"]) && $_POST["country"] !== '' ? valid_datas($_POST["country"]) : $mission->getCountry()->getId();

    $startDateInput = isset($_POST["startDate"]) ? $_POST["startDate"] : '';
    $startDate = $startDateInput !== '' ? DateTime::createFromFormat('d/m/Y', $startDateInput) : false;
    $startDate = $startDate !== false ? $startDate->format('Y-m-d') : $mission->getStartDate();

    $endDateInput = isset($_POST["endDate"]) ? $_POST["endDate"] : '';
    $endDate = $endDateInput !== '' ? DateTime::createFromFormat('d/m/Y', $endDateInput) : false;
    $endDate = $endDate !== false ? $endDate->format('Y-m-d') : $mission->getEndDate();

    $type = isset($_POST["type"]) && $_POST["type"] !== '' ? valid_datas($_POST["type"]) : $mission->getMissionType();

    // Obtenez l'identifiant de la missionType
    $missionTypeId = $type->getId();

    $agents = isset($_POST["agent"]) && $_POST["agent"] !== '' ? $_POST["agent"] : [];
    if (!empty($missionAgents)) {
        $agentIds = [];
        foreach ($missionAgents as $missionAgent) {
            $agentIds[] = $missionAgent->getAgentId();
        }
        $agents = $agentIds;
    }

    $contacts = isset($_POST["contact"]) && $_POST["contact"] !== '' ? $_POST["contact"] : [];
    if (!empty($missionContacts)) {
        $contactIds = [];
        foreach ($missionContacts as $missionContact) {
            $contactIds[] = $missionContact->getContactId();
        }
        $contacts = $contactIds;
    }

    $targets = isset($_POST["target"]) && $_POST["target"] !== '' ? $_POST["target"] : [];
    if (!empty($missionTargets)) {
        $targetIds = [];
        foreach ($missionTargets as $missionTarget) {
            $targetIds[] = $missionTarget->getTargetId();
        }
        $targets = $targetIds;
    }

    $safeHouses = isset($_POST["safeHouse"]) && $_POST["safeHouse"] !== '' ? $_POST["safeHouse"] : [];
    if (!empty($missionSafeHouses)) {
        $safeHouseIds = [];
        foreach ($missionSafeHouses as $missionSafeHouse) {
            $safeHouseIds[] = $missionSafeHouse->getSafeHouseId();
        }
        $safeHouses = $safeHouseIds;
    }

    $specialityId = isset($_POST["speciality"]) && $_POST["speciality"] !== '' ? valid_datas($_POST["speciality"]) : $mission->getSpeciality()->getId();
    $statusId = isset($_POST["status"]) && $_POST["status"] !== '' ? valid_datas($_POST["status"]) : $mission->getMissionStatus()->getId();

    $propertiesToUpdateMission = [
        'title' => $title,
        'description' => $description,
        'codeName' => $codeName,
        'country' => $country,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'missionTypeId' => $missionTypeId,
        'specialityId' => $specialityId,
        'missionStatusId' => $statusId
    ];

    $propertiesToUpdateMissionAgents = [
        'agentId' => $agents
    ];

    $propertiesToUpdateMissionContacts = [
        'contactId' => $contacts
    ];

    $propertiesToUpdateMissionTargets = [
        'targetId' => $targets
    ];

    $propertiesToUpdateMissionSafeHouses = [
        'safeHouseId' => $safeHouses
    ];

    //Insertion des nouvelles données
    $mission = $missionObj::updateMissionProperties($mission, $propertiesToUpdateMission);
    $missionAgentObj->updateMissionAgentProperties($missionId, $propertiesToUpdateMissionAgents);
    $missionAgents = $missionAgentObj::getAgentsByMissionId($missionId);
    $missionContacts = $missionContactObj::updateMissionContactProperties($missionId, $propertiesToUpdateMissionContacts);
    $missionTargets = $missionTargetObj::updateMissionTargetProperties($missionId, $propertiesToUpdateMissionTargets);
    $missionSafeHouses = $missionSafeHouseObj::updateSafeHouseProperties($missionId, $propertiesToUpdateMissionSafeHouses);

    //Si la modification en base de données a réussi : redirection vers la page de création
    if (isset($mission)) {
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