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

include_once "../../../config/database.php";
include_once "../../classes/Mission.php";
include_once "../../classes/MissionType.php";
include_once "../../classes/MissionAgent.php";
include_once "../../classes/Agent.php";
include_once "../../classes/MissionContact.php";
include_once "../../classes/Contact.php";
include_once "../../classes/MissionTarget.php";
include_once "../../classes/Target.php";
include_once "../../classes/MissionSafeHouse.php";
include_once "../../classes/Speciality.php";
include_once "../../classes/MissionStatus.php";
include_once "../../classes/SafeHouse.php";
include_once "../../classes/CountryNationality.php";

// Créer une instance des classes nécessaires
$missionObj = new Mission($pdo);
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

// Obtenir la page actuelle à partir des paramètres GET
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Nombre d'éléments par page
$perPage = 2;

// Obtenir le nombre total d'éléments
$totalItems = $missionObj::countMissions();

// Calculer le nombre total de pages nécessaires
$totalPages = ceil($totalItems / $perPage);

//Récupération de tous les missions
$missions = $missionObj->getAllMissionsPagination($page, $perPage);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../public/css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" type="text/javascript"></script>
    <title>Missions</title>
</head>
<body>
    <header id="dashboardHeader">
        <!-- Bouton de retour à l'accueil -->
        <div id="homeBtn">
            <a href="/" id="homeBtnLink">Accueil</a>
        </div>  
    </header>

    <h1>Tableau de bord Administrateur</h1>
    <h2>Missions</h2>

    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
        <div class="plusItem">
            <a href="/dashboard-create" class="plusLink">
                <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" fill="rgb(186, 238, 233)" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                </svg>
            </a>
        </div>
        <?php foreach ($missions as $mission) : ?>
        <form method="POST" action="../../controllers/updateControllers/updateMissionController.php" class="editForm">
            <input type="hidden" name="missionId" value="<?php echo $mission->getId(); ?>">
            <table class="editTable editMissionTable">
                <tbody>
                    <!-- Titre de la mission -->
                    <tr>
                        <th scope="row" class="thMissionTable">Titre</th>
                        <td class="tdMissionTable">
                            <span id="missionTitle"><?php echo $mission->getTitle(); ?></span>
                            <input type="text" name="title" class="editInput" id="editMissionTitle" placeholder="<?php echo $mission->getTitle(); ?>" style="display:none;">
                        </td>
                    </tr>
                    <!-- Description de la mission -->
                    <tr>
                        <th scope="row" class="thMissionTable">Description</th>
                        <td class="tdMissionTable">
                            <span id="missionDescription"><?php echo $mission->getDescription(); ?></span>
                            <textarea class="editInput" name="description" id="editMissionDescription" placeholder="<?php echo $mission->getDescription(); ?>" style="display:none;"></textarea>
                        </td>
                    </tr>
                    <!-- Nom de code de la mission -->
                    <tr>
                        <th scope="row" class="thMissionTable">Nom de Code</th>
                        <td class="tdMissionTable">
                            <span id="missionCodeName"><?php echo $mission->getCodeName(); ?></span>
                            <input type="text" name="codeName" class="editInput" id="editMissionCodeName" placeholder="<?php echo $mission->getCodeName(); ?>" style="display:none;">
                        </td>
                    </tr>
                    <!-- Pays de la mission -->
                    <tr>
                        <th scope="row" class="thMissionTable">Pays</th>
                        <td class="tdMissionTable">
                            <span id="missionCountry">
                            <?php 
                                $country = $countryNationalityObj::getCountryNationalityById($mission->getCountry()->getId());
                                echo $country->getCountry();
                            ?>
                            </span>
                            <select name="country" id="country" style="display:none;">
                            <option value="">--<?php echo $country->getCountry() ?>--</option>
                            <?php
                                $countries = CountryNationality::getAllCountriesNationalities();
                                foreach ($countries as $country) {
                                    $countryId = $country->getId();
                                    $countryName = $country->getCountry();
                                    echo "<option value=\"$countryId\">$countryName</option>";
                                }
                            ?>
                        </select>
                        </td>
                    </tr>
                    <!-- Date de début de la mission -->
                    <tr>
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
                            <input type="date" name="startDate" class="editInput" id="editMissionStartDate" value="<?php echo $mission->getStartDate(); ?>" style="display:none;">
                        </td>
                    </tr>
                    <!-- Date de fin de la mission -->
                    <tr>
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
                            <input type="date" name="endDate" class="editInput" id="editMissionEndDate" value="<?php echo $mission->getEndDate(); ?>" style="display:none;">
                        </td>
                    </tr>
                    <!-- Type de mission -->
                    <tr>
                        <th scope="row" class="thMissionTable">Type de mission</th>
                        <td class="tdMissionTable">
                            <span id="missionType">
                                <?php
                                    $missionType = $missionTypeObj->getMissionTypeById($mission->getMissionType()->getId());
                                    echo $missionType->getType();
                                ?>
                            </span>
                            <select name="type" class="editSelect" id="editMissionType" style="display:none;">
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
                    <!-- Agents participant à la mission -->
                    <tr>
                        <th scope="row" class="thMissionTable">Agent(s)</th>
                        <td class="tdMissionTable">
                            <span id="missionAgent">
                                <?php
                                    $missionAgents = $missionAgentObj->getAgentsByMissionId($mission->getId());
                                    foreach($missionAgents as $missionAgent) {
                                        $agentId = $missionAgent->getAgentId();
                                        $agent = $agentObj->getAgentById($agentId);
                                        $nationality = $countryNationalityObj::getCountryNationalityById($agent->getNationality()->getId())->getNationality();
                                        echo "<br>Nom : " . $agent->getLastName() . " " . $agent->getFirstName() . "<br>" . "Date de naissance: " . $agent->getBirthDate() . "<br>" . "Nationalité :  " . $nationality . "<br>" . "Code d'identification : " . $agent->getIdentificationCode() . "<br><br>";
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
                    <!-- Contacts participant à la mission -->
                    <tr>
                        <th scope="row" class="thMissionTable">Contact(s)</th>
                        <td class="tdMissionTable">
                            <span id="missionContact">
                                <?php 
                                    $missionContacts = $missionContactObj->getContactsByMissionId($mission->getId());
                                    foreach($missionContacts as $missionContact) {
                                        $contactId = $missionContact->getContactId();
                                        $contact = $contactObj->getContactById($contactId);
                                        $nationality = $countryNationalityObj::getCountryNationalityById($contact->getNationality()->getId())->getNationality();
                                        echo "<br>Nom : " . $contact->getLastName() . " " . $contact->getFirstName() . "<br>" . "Date de naissance: " . $contact->getBirthDate() . "<br>" . "Nationalité :  " . $nationality . "<br>" . "Code d'identification : " . $contact->getCodeName() . "<br><br>";
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
                                        $nationality = $countryNationalityObj::getCountryNationalityById($target->getNationality()->getId())->getNationality();
                                        echo "<br>Nom : " . $target->getLastName() . " " . $target->getFirstName() . "<br>" . "Date de naissance: " . $target->getBirthDate() . "<br>" . "Nationalité :  " . $nationality . "<br>" . "Code d'identification : " . $target->getCodeName() . "<br><br>";
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
                    <!-- Planques allouées à la mission -->
                    <tr>
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
                                            $country = $countryNationalityObj::getCountryNationalityById($safeHouse->getCountry()->getId())->getCountry();
                                            if ($safeHouse) {
                                                echo "<span><br>Code : " . $safeHouse->getCode() . "<br>" . "Adresse: " . $safeHouse->getAddress() . "<br>" . "Pays :  " . $country . "<br>" . "Type : " . $safeHouse->getType() . "<br><br></span>";
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
                                    <label for="editSafeHouse<?php echo $safeHouseId ?>" class="labelChk">
                                    <?php 
                                    echo $safeHouse->getAddress() . ', ' . $safeHouse->getCountry()->getCountry(); 
                                    ?>
                                    </label><br>
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                    <!-- Spécialité nécessaire à la mission -->
                    <tr>
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
                            <select name="speciality" class="editSelect" id="editMissionSpeciality" style="display:none;">
                                <option value="">--<?php echo $missionSpeciality->getSpeciality(); ?>--</option>
                                <?php foreach($missionSpecialities as $speciality) {
                                    $id = $speciality->getId();
                                    $name = $speciality->getSpeciality();
                                    echo "<option value=\"$id\">$name</option>";
                                } ?>
                            </select>
                        </td>
                    </tr>
                    <!-- Statut de la mission -->
                    <tr>
                        <th scope="row" class="thMissionTable">Statut</th>
                        <td class="tdMissionTable tdEnd">
                            <span id="missionStatus">
                                <?php
                                    $missionStatus = $missionStatusObj->getMissionStatusById($mission->getMissionStatus()->getId());
                                    echo $missionStatus->getStatus();
                                ?>
                            </span>
                            <select name="status" class="editSelect" id="editMissionStatus" style="display:none;">
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
                    <!-- Boutons d'édition, de sauvegarde et de suppression -->
                    <tr>
                        <td class="tdTable" colspan="2">
                            <div class="buttonsContainer">
                                <button type="button" class="button editButton" onClick="toggleEdit(this)">Modifier</button>
                                <button type="submit" class="button saveButton" style="display:none;">Sauvegarder</button>
                                <button type="button" class="button deleteButton" data-url="../../controllers/deleteControllers/deleteMissionController.php">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- message affiché à la suppression -->
            <div class="messageDivContainer">
                <div class="messageDiv"></div>
            </div>
        </form>
    <?php endforeach; endif; ?>
    <!-- Liens de la pagination -->
    <div class="pagination">
        <?php
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1; // Définit la valeur par défaut de la page à 1 si elle n'est pas définie dans l'URL
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($currentPage == $i) ? 'active' : '';
        ?>
            <a href="?page=<?php echo $i; ?>" class="paginationLink <?php echo $activeClass; ?>"><?php echo $i; ?></a>
        <?php } ?>
    </div>
    <!-- Bouton de retour arrière -->
    <div class="backButtonContainer">
        <a href="/dashboard-edit" class="backButton">
            Retour
        </a>
    </div>
    <script src="../../../dist/confirmDelete.bundle.js"></script>
    <script src="../../../dist/toggleEdit.bundle.js"></script>
</body>
</html>