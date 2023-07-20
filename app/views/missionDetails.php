<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use app\classes\Mission;
use app\classes\MissionType;
use app\classes\MissionAgent;
use app\classes\Agent;
use app\classes\MissionContact;
use app\classes\Contact;
use app\classes\MissionTarget;
use app\classes\Target;
use app\classes\MissionSafeHouse;
use app\classes\SafeHouse;
use app\classes\Speciality;
use app\classes\MissionStatus;
use app\classes\CountryNationality;


$missions = []; 
include_once "../../config/database.php";
include_once "../classes/Mission.php";
include_once "../classes/MissionType.php";
include_once "../classes/MissionAgent.php";
include_once "../classes/Agent.php";
include_once "../classes/MissionContact.php";
include_once "../classes/Contact.php";
include_once "../classes/MissionTarget.php";
include_once "../classes/Target.php";
include_once "../classes/MissionSafeHouse.php";
include_once "../classes/Speciality.php";
include_once "../classes/MissionStatus.php";
include_once "../classes/SafeHouse.php";
include_once "../classes/CountryNationality.php";

// Vérifier si l'ID de la mission est passé en paramètre
if (isset($_GET['mission'])) {
    $missionId = $_GET['mission'];

    // Créer une instance des classes nécessaires
    $mission = new Mission($pdo);
    $missionTypeObj = new MissionType($pdo);
    $missionAgentObj = new MissionAgent($pdo);
    $agentObj = new Agent($pdo);
    $missionContactObj = new MissionContact($pdo);
    $contactObj = new Contact($pdo);
    $missionTargetObj = new MissionTarget($pdo);
    $targetObj = new Target($pdo);
    $missionSafeHouseObj = new MissionSafeHouse($pdo);
    $specialityObj = new Speciality($pdo);
    $missionStatusObj = new MissionStatus($pdo);
    $safeHouseObj = new SafeHouse($pdo);
    $countryNationalityObj = new CountryNationality($pdo);

    // Récupérer la mission spécifique en utilisant son ID
    $mission = $mission::getMissionById($missionId);

    // Ajout de la mission au cache
    $missions[$mission->getId()] = $mission;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/editTable.css">
    <title>Détail de la mission</title>
</head>
<body>
    <h1>Détail de la mission</h1>
    <table>
        <tbody>
            <?php if (!empty($mission) && $mission->getId() != '') : ?>
                <tr>
                    <!-- Titre de la mission  -->
                    <th scope="row">Titre</th>
                    <td>
                        <span id="missionTitle"><?php echo $mission->getTitle(); ?></span>
                    </td>
                </tr>
                <tr>
                    <!-- Description de la mission  -->
                    <th scope="row">Description</th>
                    <td>
                        <span id="missionDescription"><?php echo $mission->getDescription(); ?></span>
                    </td>
                </tr>
                <tr>
                    <!--  Nom de code de la mission -->
                    <th scope="row">Nom de Code</th>
                    <td>
                        <span id="missionCodeName"><?php echo $mission->getCodeName(); ?></span>
                    </td>
                </tr>
                <tr>
                    <!-- Pays de la mission -->
                    <th scope="row">Pays</th>
                    <td>
                        <span id="missionCountry">
                            <?php 
                            $country = $countryNationalityObj::getCountryNationalityById($mission->getCountry()->getId());
                            echo $country->getCountry();
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <!-- Date de début de la mission -->
                    <th scope="row">Date de début</th>
                    <td>
                        <span id="missionStartDate">
                            <?php 
                                $startDate = $mission->getStartDate();
                                $startDateObj = DateTime::createFromFormat('Y-m-d', $startDate);
                                $formattedStartDate = $startDateObj->format('d/m/Y');
                                echo $formattedStartDate; 
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <!-- Date de fin de la mission -->
                    <th scope="row">Date de fin</th>
                    <td>
                        <span id="missionEndDate">
                            <?php 
                                $endDate = $mission->getEndDate();
                                $endDateObj = DateTime::createFromFormat('Y-m-d', $endDate);
                                $formattedEndDate = $endDateObj->format('d/m/Y');
                                echo $formattedEndDate; 
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <!-- Type de mission -->
                    <th scope="row">Type de mission</th>
                    <td>
                        <span id="missionType">
                            <?php
                                $missionType = $missionTypeObj->getMissionTypeById($mission->getMissionType()->getId());
                                echo $missionType->getType();
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <!-- Agents participant à la mission -->
                    <th scope="row">Agent(s)</th>
                    <td>
                        <span id="missionAgent">
                            <?php
                                $missionAgents = $missionAgentObj::getAgentsByMissionId($mission->getId());
                                foreach($missionAgents as $missionAgent) {
                                    $agentId = $missionAgent->getAgentId();
                                    $agent = $agentObj::getAgentById($agentId);
                                    echo "<br>Nom : " . $agent->getLastName() . " " . $agent->getFirstName() . "<br>" . "Date de naissance: " . $agent->getBirthDate() . "<br>" . "Nationalité :  " . $agent->getNationality()->getNationality() . "<br>" . "Code d'identification : " . $agent->getIdentificationCode() . "<br><br>";
                                }
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <!-- Contacts participant à la mission -->
                    <th scope="row">Contact(s)</th>
                    <td>
                        <span id="missionContact">
                            <?php 
                                $missionContacts = $missionContactObj::getContactsByMissionId($mission->getId());
                                foreach($missionContacts as $missionContact) {
                                    $contactId = $missionContact->getContactId();
                                    $contact = $contactObj::getContactById($contactId);
                                    echo "<br>Nom : " . $contact->getLastName() . " " . $contact->getFirstName() . "<br>" . "Date de naissance: " . $contact->getBirthDate() . "<br>" . "Nationalité :  " . $contact->getNationality()->getNationality() . "<br>" . "Code d'identification : " . $contact->getCodeName() . "<br><br>";
                                }
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <!-- Cibles concernées par la mission -->
                    <th scope="row">Cible(s)</th>
                    <td>
                        <span id="missionTarget">
                            <?php 
                                $missionTargets = $missionTargetObj::getTargetsByMissionId($mission->getId());
                                foreach($missionTargets as $missionTarget) {
                                    $targetId = $missionTarget->getTargetId();
                                    $target = $targetObj::getTargetById($targetId);
                                    echo "<br>Nom : " . $target->getLastName() . " " . $target->getFirstName() . "<br>" . "Date de naissance: " . $target->getBirthDate() . "<br>" . "Nationalité :  " . $target->getNationality()->getNationality() . "<br>" . "Code d'identification : " . $target->getCodeName() . "<br><br>";
                                }
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <!-- Planques allouées à la mission -->
                    <th scope="row">Planque(s)</th>
                    <td>
                        <span id="missionSafeHouse">
                        <?php
                            $missionSafeHouses = $missionSafeHouseObj::getSafeHousesByMissionId($missionId);
                            if(empty($missionSafeHouses)) {
                                echo "Aucune";
                            } else {
                                foreach ($missionSafeHouses as $missionSafeHouse) {
                                    $safeHouse = $safeHouseObj::getSafeHouseById($missionSafeHouse->getSafeHouseId());
                                    if ($safeHouse) {
                                        echo "<span><br>Code : " . $safeHouse->getCode() . "<br>" . "Adresse: " . $safeHouse->getAddress() . "<br>" . "Pays :  " . $safeHouse->getCountry()->getCountry() . "<br>" . "Type : " . $safeHouse->getType() . "<br><br></span>";
                                    }
                                }
                            }
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <!-- Spécialité nécessaire à la mission -->
                    <th scope="row">Spécialité</th>
                    <td>
                        <span id="missionSpeciality">
                        <?php
                            $missionSpeciality = $specialityObj->getSpecialityById($mission->getSpeciality()->getId());
                            echo $missionSpeciality->getSpeciality();
                        ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <!-- Statut de la mission -->
                    <th scope="row">Statut</th>
                    <td>
                        <span id="missionStatus">
                            <?php
                                $missionStatus = $missionStatusObj::getMissionStatusById($mission->getMissionStatus()->getId());
                                echo $missionStatus->getStatus();
                            ?>
                        </span>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="3">La mission n'existe pas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- Bouton de retour arrière -->
    <div class="backButtonContainer">
        <a href="home.php" class="backButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="1.5vw" height="1.5vw" fill="currentColor" color="white" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
            </svg>
            <span class="backBtnText">Retour</span>
        </a>
    </div>
</body>
</html>