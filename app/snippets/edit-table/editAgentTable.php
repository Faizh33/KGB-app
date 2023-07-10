<?php
include_once "../../config/database.php";
include_once "../classes/Agent.php";

use app\classes\Agent;

$agentObj = new Agent($pdo);
$agents = $agentObj::getAllAgents();
?>

<h2>Agents</h2>

<?php foreach($agents as $agent) { ?>
    <table id="form"  class="editTable">
        <tr>
            <th scope="row" class="thTable">Nom</th>
            <td class="tdTable">
                <span id="agentLastName" class="tdContent"><?php echo $agent->getLastName(); ?></span>
                <input type="text" class="editInput" id="editAgentLastName" placeholder="<?php echo $agent->getLastName() ?>" style="display:none;">
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Prénom</th>
            <td class="tdTable">
                <span id="agentFirstName"><?php echo $agent->getFirstName(); ?></span>
                <input type="text" class="editInput" id="editAgentFirstName" placeholder="<?php echo $agent->getFirstName() ?>" style="display:none;">
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
                <input type="text" class="editInput" id="editAgentBirthDate" placeholder="<?php echo $formattedBirthDate; ?>" style="display:none;">
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Nationalité</th>
            <td class="tdTable">
                <span id="agentNationality"><?php echo $agent->getNationality(); ?></span>
                <input type="text" class="editInput" id="editAgentNationality" placeholder="<?php echo $agent->getNationality() ?>" style="display:none;">
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Code d'identification</th>
            <td class="tdTable">
                <span id="agentIdCode"><?php echo $agent->getIdentificationCode(); ?></span>
                <input type="text" class="editInput" id="editAgentIdCode" placeholder="<?php echo $agent->getIdentificationCode() ?>" style="display:none;">
            </td>
        </tr>
        <tr>
            <td class="tbTable" colspan="2">
                <div class="buttonsContainer">
                    <button class="editButton" onClick="toggleEdit(this)">Modifier</button>
                    <button class="saveButton" onClick="" style="display:none;">Sauvegarder</button>
                </div>
            </td>
        </tr>
    </table>
<?php } ?>
