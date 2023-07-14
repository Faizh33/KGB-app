<?php
include_once "../../config/database.php";
include_once "../classes/Agent.php";
include_once "../classes/AgentSpeciality.php";
include_once "../classes/Speciality.php";

use app\classes\Agent;
use app\classes\AgentSpeciality;
use app\classes\Speciality;

$agentObj = new Agent($pdo);
$agents = $agentObj::getAllAgents();

$agentSpecialityObj = new AgentSpeciality($pdo);
$specialityObj = new Speciality($pdo);
?>

<h2>Agents</h2>

<?php foreach($agents as $agent) { ?>
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
                    <span id="agentNationality"><?php echo $agent->getNationality(); ?></span>
                    <input type="text" class="editInput" id="editAgentNationality" name="agentNationality" placeholder="<?php echo $agent->getNationality() ?>" style="display:none;">
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
                        <button type="button" class="button deleteButton" data-url="../controllers/deleteControllers/deleteAgentController.php">Supprimer</button>
                    </div>
                </td>
            </tr>
        </table>
        <div class="messageDivContainer">
            <div class="messageDiv"></div>
        </div>
    </form>
<?php } ?>
