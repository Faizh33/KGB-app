<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once "../../../config/database.php";
include_once "../../classes/Agent.php";
include_once "../../classes/AgentSpeciality.php";
include_once "../../classes/Speciality.php";
include_once "../../classes/CountryNationality.php";

use app\classes\Agent;
use app\classes\AgentSpeciality;
use app\classes\Speciality;
use app\classes\CountryNationality;

// Création des objets
$agentSpecialityObj = new AgentSpeciality($pdo);
$specialityObj = new Speciality($pdo);
$countryNationalityObj = new CountryNationality($pdo);
$agentObj = new Agent($pdo);

// Obtenir la page actuelle à partir des paramètres GET
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Nombre d'éléments par page
$perPage = 3;

// Obtenir le nombre total d'éléments
$totalItems = $agentObj::countAgents();

// Calculer le nombre total de pages nécessaires
$totalPages = ceil($totalItems / $perPage);

//Récupération de tous les agents
$agents = $agentObj::getAllAgentsPagination($page, $perPage);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../public/css/dashboard.css">
    <title>Agents</title>
</head>
<body>
    <h1>Tableau de bord Administrateur</h1>
    <h2>Agents</h2>
    <?php 
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) :
        foreach($agents as $agent) { 
    ?>
        <form method="POST" action="../controllers/updateControllers/updateAgentController.php" class="editForm">
            <input type="hidden" name="agentId" value="<?php echo $agent->getId(); ?>" />
            <table class="editTable editTables">
                <tr>
                    <th scope="row" class="thTable">Nom</th>
                    <td class="tdTable">
                        <span id="agentLastName" class="tdContent"><?php echo $agent->getLastName(); ?></span>
                        <input type="text" class="editInput" id="editAgentLastName" name="agentLastName" placeholder="<?php echo $agent->getLastName() ?>" style="display:none;">
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Prénom</th>
                    <td class="tdTable">
                        <span id="agentFirstName"><?php echo $agent->getFirstName(); ?></span>
                        <input type="text" class="editInput" id="editAgentFirstName" name="agentFirstName" placeholder="<?php echo $agent->getFirstName() ?>" style="display:none;">
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Date de naissance</th>
                    <td class="tdTable">
                        <span id="agentBirthDate">
                            <?php 
                            $birthDate = $agent->getBirthDate(); 
                            $birthDateObj = DateTime::createFromFormat('Y-m-d', $birthDate);
                            $formattedBirthDate = $birthDateObj->format('d/m/Y');
                            echo $formattedBirthDate;
                            ?>
                        </span>
                        <input type="text" class="editInput" id="editAgentBirthDate" name="agentBirthDate" placeholder="<?php echo $formattedBirthDate; ?>" style="display:none;">
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Nationalité</th>
                    <td class="tdTable">
                        <span id="agentNationality"><?php echo $agent->getNationality()->getNationality(); ?></span>
                        <select name="agentNationality" id="agentNationality" class="editInput" style="display: none;">
                        <option value="">--<?php echo $agent->getNationality()->getNationality(); ?>--</option>
                        <?php
                        $countriesNationalities = $countryNationalityObj::getAllCountriesNationalities();
                        foreach ($countriesNationalities as $countryNationality) {
                            $nationality = $countryNationality->getNationality();
                            echo "<option value=\"$nationality\">$nationality</option>";
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Code d'identification</th>
                    <td class="tdTable">
                        <span id="agentIdCode"><?php echo $agent->getIdentificationCode(); ?></span>
                        <input type="text" class="editInput" id="editAgentIdCode" name="agentIdCode" placeholder="<?php echo $agent->getIdentificationCode() ?>" style="display:none;">
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Spécialité(s)</th>
                    <td class="tdTable">
                        <?php 
                        $specialities = $agentSpecialityObj::getSpecialitiesByAgentId($agent->getId());
                        foreach($specialities as $speciality) {
                            $specialityId = $speciality->getSpecialityId();
                            $specialityName = $specialityObj::getSpecialityById($specialityId)->getSpeciality();
                        ?>
                        <span id="agentSpeciality"><?php echo $specialityName; ?><br></span>
                        <?php 
                        }
                        $specialities = $specialityObj::getAllSpecialities();
                        foreach($specialities as $speciality) {
                            $specialityId = $speciality->getId(); ?>
                            <div class="chk" style="display:none;">
                                <input type="checkbox" name="specialities[]" class="editChk" value="<?php echo $specialityId ?>" id="editSpeciality <?php echo $specialityId ?>">
                                <label for="editSpeciality <?php echo $specialityId ?>" class="labelChk"> <?php echo $speciality->getSpeciality() ?> </label><br>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td class="tbTable" colspan="2">
                        <div class="buttonsContainer">
                            <button type="button" class="button editButton" onClick="toggleEdit(this)">Modifier</button>
                            <button type="submit" class="button saveButton" style="display:none;">Sauvegarder</button>
                            <button type="button" class="button deleteButton" data-url="../../controllers/deleteControllers/deleteAgentController.php">Supprimer</button>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="messageDivContainer">
                <div class="messageDiv"></div>
            </div>
        </form>
    <?php } endif; ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="?page=<?php echo $i; ?>" class="paginationLink"><?php echo $i; ?></a>
        <?php } ?>
    </div>

    <!-- Bouton de retour arrière -->
    <div class="backButtonContainer">
        <a href="../../views/dashboardEdit.php" class="backButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="1.5vw" height="1.5vw" fill="currentColor" color="white" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
            </svg>
            <span class="backText">Retour</span>
        </a>
    </div>
    <script src="../../public/js/confirmDelete.js"></script>
</body>
</html>