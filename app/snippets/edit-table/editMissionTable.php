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

$missions = $mission->getAllMissions();

?>

<h2>Missions</h2>
<?php foreach ($missions as $mission) : ?>
    <form method="POST" action="votre_script.php">
        <input type="hidden" name="missionId" value="<?php echo $mission->getId(); ?>">
        <table class="editMissionTable">
            <tbody>
                <tr>
                    <!-- Titre de la mission -->
                    <th scope="row" class="thMissionTable">Titre</th>
                    <td class="tdMissionTable">
                        <span id="missionTitle"><?php echo $mission->getTitle(); ?></span>
                        <input type="text" class="editInput" id="editMissionTitle" placeholder="<?php echo $mission->getTitle(); ?>" style="display:none;">
                    </td>
                </tr>
                <tr>
                    <!-- Description de la mission -->
                    <th scope="row" class="thMissionTable">Description</th>
                    <td class="tdMissionTable">
                        <span id="missionDescription"><?php echo $mission->getDescription(); ?></span>
                        <textarea class="editInput" id="editMissionDescription" placeholder="<?php echo $mission->getDescription(); ?>" style="display:none;"></textarea>
                    </td>
                </tr>
                <tr>
                    <!-- Nom de code de la mission -->
                    <th scope="row" class="thMissionTable">Nom de Code</th>
                    <td class="tdMissionTable">
                        <span id="missionCodeName"><?php echo $mission->getCodeName(); ?></span>
                        <input type="text" class="editInput" id="editMissionCodeName" placeholder="<?php echo $mission->getCodeName(); ?>" style="display:none;">
                    </td>
                </tr>
                <tr>
                    <!-- Pays de la mission -->
                    <th scope="row" class="thMissionTable">Pays</th>
                    <td class="tdMissionTable">
                        <span id="missionCountry"><?php echo $mission->getCountry(); ?></span>
                        <input type="text" class="editInput" id="editMissionCountry" placeholder="<?php echo $mission->getCountry(); ?>" style="display:none;">
                    </td>
                </tr>
                <tr>
                    <!-- Date de début de la mission -->
                    <th scope="row" class="thMissionTable">Date de début</th>
                    <td class="tdMissionTable">
                        <span id="missionStartDate">
                            <?php 
                                $startDate = $mission->getStartDate();
                                $startDateObj = DateTime::createFromFormat('Y-m-d', $startDate);
                                $formattedStartDate = $startDateObj->format('d/m/Y');
                                echo $formattedStartDate; 
                            ?>
                        </span>
                        <input type="date" class="editInput" id="editMissionStartDate" value="<?php echo $mission->getStartDate(); ?>" style="display:none;">
                    </td>
                </tr>
                <tr>
                    <!-- Date de fin de la mission -->
                    <th scope="row" class="thMissionTable">Date de fin</th>
                    <td class="tdMissionTable">
                        <span id="missionEndDate">
                            <?php 
                                $endDate = $mission->getEndDate();
                                $endDateObj = DateTime::createFromFormat('Y-m-d', $endDate);
                                $formattedEndDate = $endDateObj->format('d/m/Y');
                                echo $formattedEndDate; 
                            ?>
                        </span>
                        <input type="date" class="editInput" id="editMissionEndDate" value="<?php echo $mission->getEndDate(); ?>" style="display:none;">
                    </td>
                </tr>
                <tr>
                    <!-- Type de mission -->
                    <th scope="row" class="thMissionTable">Type de mission</th>
                    <td class="tdMissionTable">
                        <span id="missionType">
                            <?php
                                $missionType = $missionTypeObj->getMissionTypeById($mission->getMissionType()->getId());
                                echo $missionType->getType();
                            ?>
                        </span>
                        <select class="editSelect" id="editMissionType" style="display:none;">
                            <option value="">--<?php echo $missionType->getType() ?>--</option>
                            <?php
                                $missionTypes = $missionTypeObj->getAllMissionTypes();
                                foreach($missionTypes as $missionType) {
                                    $id = $missionType->getId();
                                    $type = $missionType->getType();
                                    echo "<option value=\"$id\">$type</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <!-- Agents participant à la mission -->
                    <th scope="row" class="thMissionTable">Agent(s)</th>
                    <td class="tdMissionTable">
                        <span id="missionAgent">
                            <?php
                                $missionAgents = $missionAgentObj->getAgentsByMissionId($mission->getId());
                                foreach($missionAgents as $missionAgent) {
                                    $agentId = $missionAgent->getAgentId();
                                    $agent = $agentObj->getAgentById($agentId);
                                    echo "<br>Nom : " . $agent->getLastName() . " " . $agent->getFirstName() . "<br>" . "Date de naissance: " . $agent->getBirthDate() . "<br>" . "Nationalité :  " . $agent->getNationality() . "<br>" . "Code d'identification : " . $agent->getIdentificationCode() . "<br><br>";
                                }
                            ?>
                        </span>
                        <?php $agents = $agentObj->getAllAgents();
                        foreach($agents as $agent) {
                            $agentId = $agent->getId();
                            $isChecked = false;
                            foreach($missionAgents as $missionAgent) {
                                if ($missionAgent->getAgentId() == $agentId) {
                                    $isChecked = true;
                                    break;
                                }
                            }
                        ?>
                            <div class="chk" style="display:none;">
                                <input type="checkbox" name="agents[]" class="editChk" value="<?php echo $agentId ?>" id="editAgent<?php echo $agentId ?>" <?php if ($isChecked) echo "checked"; ?>>
                                <label for="editAgent<?php echo $agentId ?>" class="labelChk"><?php echo $agent->getLastName() . ' ' . $agent->getFirstName() ?></label><br>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <!-- Contacts participant à la mission -->
                    <th scope="row" class="thMissionTable">Contact(s)</th>
                    <td class="tdMissionTable">
                        <span id="missionContact">
                            <?php 
                                $missionContacts = $missionContactObj->getContactsByMissionId($mission->getId());
                                foreach($missionContacts as $missionContact) {
                                    $contactId = $missionContact->getContactId();
                                    $contact = $contactObj->getContactById($contactId);
                                    echo "<br>Nom : " . $contact->getLastName() . " " . $contact->getFirstName() . "<br>" . "Date de naissance: " . $contact->getBirthDate() . "<br>" . "Nationalité :  " . $contact->getNationality() . "<br>" . "Code d'identification : " . $contact->getCodeName() . "<br><br>";
                                }
                            ?>
                        </span> 
                        <?php $contacts = $contactObj->getAllContacts();
                        foreach($contacts as $contact) {
                            $contactId = $contact->getId(); 
                            $isChecked = false;
                            foreach($missionContacts as $missionContact) {
                                if ($missionContact->getContactId() == $contactId) {
                                    $isChecked = true;
                                    break;
                                }
                            }
                            ?>
                            <div class="chk" style="display:none;">
                                <input type="checkbox" name="contacts[]" class="editChk" value="<?php echo $contactId ?>" id="editContact<?php echo $contactId ?>" <?php if ($isChecked) echo "checked"; ?>>
                                <label for="editContact<?php echo $contactId ?>" class="labelChk"><?php echo $contact->getLastName() . ' ' . $contact->getFirstName() ?></label><br>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
                    <tr>
                        <!-- Cibles concernées par la mission -->
                        <th scope="row" class="thMissionTable">Cible(s)</th>
                        <td class="tdMissionTable">
                            <span id="missionTarget">
                                <?php 
                                    $missionTargets = $missionTargetObj->getTargetsByMissionId($mission->getId());
                                    foreach($missionTargets as $missionTarget) {
                                        $targetId = $missionTarget->getTargetId();
                                        $target = $targetObj->getTargetById($targetId);
                                        echo "<br>Nom : " . $target->getLastName() . " " . $target->getFirstName() . "<br>" . "Date de naissance: " . $target->getBirthDate() . "<br>" . "Nationalité :  " . $target->getNationality() . "<br>" . "Code d'identification : " . $target->getCodeName() . "<br><br>";
                                    }
                                ?>
                            </span> 
                            <?php $targets = $targetObj->getAllTargets();
                            foreach($targets as $target) {
                                $targetId = $target->getId(); 
                                $isChecked = false;
                                foreach($missionTargets as $missionTarget) {
                                    if ($missionTarget->getTargetId() == $targetId) {
                                        $isChecked = true;
                                        break;
                                    }
                                }
                            ?>
                                <div class="chk" style="display:none;">
                                    <input type="checkbox" name="targets[]" class="editChk" value="<?php echo $targetId ?>" id="editTarget<?php echo $targetId ?>" <?php if ($isChecked) echo "checked"; ?>>
                                    <label for="editTarget<?php echo $targetId ?>" class="labelChk"><?php echo $target->getLastName() . ' ' . $target->getFirstName() ?></label><br>
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                    <!-- Planques allouées à la mission -->
                    <th scope="row" class="thMissionTable">Planque(s)</th>
                    <td class="tdMissionTable">
                        <span id="missionSafeHouse">
                            <?php
                                $missionSafeHouses = $missionSafeHouseObj->getSafeHousesByMissionId($mission->getId());
                                if (empty($missionSafeHouses)) {
                                    echo "Aucune";
                                } else {
                                    foreach ($missionSafeHouses as $missionSafeHouse) {
                                        $safeHouse = $safeHouseObj->getSafeHouseById($missionSafeHouse->getSafeHouseId());
                                        if ($safeHouse) {
                                            echo "<span><br>Code : " . $safeHouse->getCode() . "<br>" . "Adresse: " . $safeHouse->getAddress() . "<br>" . "Pays :  " . $safeHouse->getCountry() . "<br>" . "Type : " . $safeHouse->getType() . "<br><br></span>";
                                        }
                                    }
                                }
                            ?>
                        </span>
                        <?php $safeHouses = $safeHouseObj->getAllSafeHouses();
                        foreach($safeHouses as $safeHouse) {
                            $safeHouseId = $safeHouse->getId(); 
                            $isChecked = false;
                            foreach($missionSafeHouses as $missionSafeHouse) {
                                if ($missionSafeHouse->getSafeHouseId() == $safeHouseId) {
                                    $isChecked = true;
                                    break;
                                }
                            }
                        ?>
                            <div class="chk" style="display:none;">
                                <input type="checkbox" name="safeHouses[]" class="editChk" value="<?php echo $safeHouseId ?>" id="editSafeHouse<?php echo $safeHouseId ?>" <?php if ($isChecked) echo "checked"; ?>>
                                <label for="editSafeHouse<?php echo $safeHouseId ?>" class="labelChk"><?php echo $safeHouse->getAddress() . ', ' . $safeHouse->getCountry() ?></label><br>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <!-- Spécialité nécessaire à la mission -->
                    <th scope="row" class="thMissionTable">Spécialité</th>
                    <td class="tdMissionTable">
                        <span id="missionSpeciality">
                            <?php
                                $missionSpeciality = $specialityObj->getSpecialityById($mission->getSpeciality()->getId());
                                echo $missionSpeciality->getSpeciality();
                            ?>
                        </span>
                        <?php
                            $missionSpecialities = $specialityObj->getAllSpecialities();
                        ?>
                        <select class="editSelect" id="editMissionSpeciality" style="display:none;">
                            <option value="">--<?php echo $missionSpeciality->getSpeciality(); ?>--</option>
                            <?php foreach($missionSpecialities as $speciality) {
                                $id = $speciality->getId();
                                $name = $speciality->getSpeciality();
                                echo "<option value=\"$id\">$name</option>";
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <!-- Statut de la mission -->
                    <th scope="row" class="thMissionTable">Statut</th>
                    <td class="tdMissionTable tdEnd">
                        <span id="missionStatus">
                            <?php
                                $missionStatus = $missionStatusObj->getMissionStatusById($mission->getMissionStatus()->getId());
                                echo $missionStatus->getStatus();
                            ?>
                        </span>
                        <select class="editSelect" id="editMissionStatus" style="display:none;">
                            <option value="">--<?php echo $missionStatus->getStatus() ?>--</option>
                            <?php
                                $missionStatuses = $missionStatusObj->getAllMissionStatuses();
                                foreach($missionStatuses as $missionStatus) {
                                    $id = $missionStatus->getId();
                                    $status = $missionStatus->getStatus();
                                    echo "<option value=\"$id\">$status</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tbTable" colspan="2">
                        <div class="buttonsContainer">
                            <button type="button" class="editButton" onClick="toggleEdit(this)">Modifier</button>
                            <button type="submit" class="saveButton" style="display:none;">Sauvegarder</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
<?php endforeach; ?>
