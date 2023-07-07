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
    <script src="../../public/js/edit-mission-form.js"></script>
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
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                            <input type="text" class="editInput" id="editMissionTitle" style="display:none;">
                        <?php endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editTitleButton" onclick="editTitle()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveTitleButton" onclick="saveTitle()" style="visibility:hidden;;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <!-- Description de la mission  -->
                    <th scope="row">Description</th>
                    <td>
                        <span id="missionDescription"><?php echo $mission->getDescription(); ?></span>
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                            <input type="text" class="editInput" id="editMissionDescription" style="display:none;">
                        <?php endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editDescriptionButton" onclick="editDescription()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveDescriptionButton" onclick="saveDescription()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <!--  Nom de code de la mission -->
                    <th scope="row">Nom de Code</th>
                    <td>
                        <span id="missionCodeName"><?php echo $mission->getCodeName(); ?></span>
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                            <input type="text" class="editInput" id="editMissionCodeName" style="display:none;">
                        <?php endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editCodeNameButton" onclick="editCodeName()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveCodeNameButton" onclick="saveCodeName()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <!-- Pays de la mission -->
                    <th scope="row">Pays</th>
                    <td>
                        <span id="missionCountry"><?php echo $mission->getCountry(); ?></span>
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                            <input type="text" class="editInput" id="editMissionCountry" style="display:none;">
                        <?php endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editCountryButton" onclick="editCountry()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveCountryButton" onclick="saveCountry()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
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
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                            <input type="date" class="editInput" id="editMissionStartDate" style="display:none;">
                        <?php endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editStartDateButton" onclick="editStartDate()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveStartDateButton" onclick="saveStartDate()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
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
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                            <input type="date" class="editInput" id="editMissionEndDate" style="display:none;">
                        <?php endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td> 
                            <button class="missionDetailsButtons" id="editEndDateButton" onclick="editEndDate()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveEndDateButton" onclick="saveEndDate()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
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
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                            <select class="editSelect" id="editMissionType" style="display:none;">
                            <option value="">--Choisir un type--</option>
                                <?php
                                    $missionTypes = $missionTypeObj::getAllMissionTypes();
                                    foreach($missionTypes as $missionType) {
                                        $id = $missionType->getId();
                                        $type = $missionType->getType();
                                        echo "<option value=\"$id\">$type</option>";
                                    }
                                ?>
                            </select>
                        <?php endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editDescriptionButton" onclick="editDescription()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveDescriptionButton" onclick="saveDescription()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
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
                                    echo "<br>Nom : " . $agent->getLastName() . " " . $agent->getFirstName() . "<br>" . "Date de naissance: " . $agent->getBirthDate() . "<br>" . "Nationalité :  " . $agent->getNationality() . "<br>" . "Code d'identification : " . $agent->getIdentificationCode() . "<br><br>";
                                }
                            ?>
                        </span>
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : 
                            $agents = $agentObj::getAllAgents();
                            foreach($agents as $agent) {
                                $agentId = $agent->getId(); ?>
                                <div class=chk style="display:none;">
                                    <input type="checkbox" name="agents[]" class="editChk" value='"<?php $agentId ?>"' id="editAgent <?php $agentId ?>">
                                    <label for="editAgent <?php echo $agentId ?>" class="labelChk"> <?php echo $agent->getLastName() . ' ' . $agent->getFirstName() ?> </label><br>
                                </div>
                            <?php } 
                        endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editDescriptionButton" onclick="editDescription()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveDescriptionButton" onclick="saveDescription()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
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
                                    echo "<br>Nom : " . $contact->getLastName() . " " . $contact->getFirstName() . "<br>" . "Date de naissance: " . $contact->getBirthDate() . "<br>" . "Nationalité :  " . $contact->getNationality() . "<br>" . "Code d'identification : " . $contact->getCodeName() . "<br><br>";
                                }
                            ?>
                        </span>
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : 
                            $contacts = $contactObj::getAllContacts();
                            foreach($contacts as $contact) {
                                $contactId = $contact->getId(); ?>
                                <div class=chk style="display:none;">
                                    <input type="checkbox" name="contacts[]" class="editChk" value='"<?php $contactId ?>"' id="editContact <?php $contactId ?>">
                                    <label for="editContact <?php echo $contactId ?>" class="labelChk"> <?php echo $contact->getLastName() . ' ' . $contact->getFirstName() ?> </label><br>
                                </div>
                            <?php } 
                        endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editDescriptionButton" onclick="editDescription()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveDescriptionButton" onclick="saveDescription()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
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
                                    echo "<br>Nom : " . $target->getLastName() . " " . $target->getFirstName() . "<br>" . "Date de naissance: " . $target->getBirthDate() . "<br>" . "Nationalité :  " . $target->getNationality() . "<br>" . "Code d'identification : " . $target->getCodeName() . "<br><br>";
                                }
                            ?>
                        </span>
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : 
                            $targets = $targetObj::getAllTargets();
                            foreach($targets as $target) {
                                $targetId = $target->getId(); ?>
                                <div class=chk style="display:none;">
                                    <input type="checkbox" name="targets[]" class="editChk" value='"<?php $targetId ?>"' id="editTarget <?php $targetId ?>">
                                    <label for="editTarget <?php echo $targetId ?>" class="labelChk"> <?php echo $target->getLastName() . ' ' . $target->getFirstName() ?> </label><br>
                                </div>
                            <?php } 
                        endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editDescriptionButton" onclick="editDescription()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveDescriptionButton" onclick="saveDescription()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <!-- Planques allouées à la mission -->
                    <th scope="row">Planque(s)</th>
                    <td>
                        <span id="missionSafeHouse">
                        <?php
                            $missionSafeHouses = $missionSafeHouseObj::getSafeHousesByMissionId($missionId);
                            foreach ($missionSafeHouses as $missionSafeHouse) {
                                $safeHouse = $safeHouseObj::getSafeHouseById($missionSafeHouse->getSafeHouseId());
                                if ($safeHouse) {
                                    echo "<span><br>Code : " . $safeHouse->getCode() . "<br>" . "Adresse: " . $safeHouse->getAddress() . "<br>" . "Pays :  " . $safeHouse->getCountry() . "<br>" . "Type : " . $safeHouse->getType() . "<br><br></span>";
                                }
                            }
                            ?>
                        </span>
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : 
                            $safeHouses = $safeHouseObj::getAllSafeHouses();
                            foreach($safeHouses as $safeHouse) {
                                $safeHouseId = $safeHouse->getId(); ?>
                                <div class=chk style="display:none;">
                                    <input type="checkbox" name="safeHouses[]" class="editChk" value='"<?php $safeHouseId ?>"' id="editSafeHouse <?php $safeHouseId ?>">
                                    <label for="editSafeHouse <?php echo $safeHouseId ?>" class="labelChk"> <?php echo $safeHouse->getAddress() . ', ' . $safeHouse->getCountry() ?> </label><br>
                                </div>
                            <?php } 
                        endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editDescriptionButton" onclick="editDescription()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveDescriptionButton" onclick="saveDescription()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
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
                            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                                <?php
                                    $missionSpecialities = $specialityObj->getAllSpecialities();
                                ?>
                                <select class="editSelect" id="editMissionSpeciality" style="display:none;">
                                    <option value="">--Choisir une spécialité--</option>
                                    <?php foreach($missionSpecialities as $speciality) {
                                        $id = $speciality->getId();
                                        $name = $speciality->getSpeciality();
                                        echo "<option value=\"$id\">$name</option>";
                                    } ?>
                                </select>
                            <?php endif; ?>
                        </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editDescriptionButton" onclick="editDescription()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveDescriptionButton" onclick="saveDescription()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
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
                        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                            <select class="editSelect" id="editMissionStatus" style="display:none;">
                            <option value="">--Choisir une spécialité--</option>
                                <?php
                                    $missionStatuses = $missionStatusObj::getAllMissionStatuses();
                                    foreach($missionStatuses as $missionStatus) {
                                        $id = $missionStatus->getId();
                                        $status = $missionStatus->getStatus();
                                        echo "<option value=\"$id\">$status</option>";
                                    }
                                ?>
                            </select>
                        <?php endif; ?>
                    </td>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
                        <td>
                            <button class="missionDetailsButtons" id="editDescriptionButton" onclick="editDescription()">Modifier</button>
                            <button class="missionDetailsButtons" id="saveDescriptionButton" onclick="saveDescription()" style="visibility:hidden;">Enregistrer</button>
                        </td>
                    <?php endif; ?>
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
