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
                <span id="agentLastName" class="tdContent">
                    <?php echo $agent->getLastName(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Prénom</th>
            <td class="tdTable">
                <span id="agentFirstName">
                    <?php echo $agent->getFirstName(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Date de naissance</th>
            <td class="tdTable">
                <span id="agentBirthDate">
                    <?php echo $agent->getBirthDate(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Nationalité</th>
            <td class="tdTable">
                <span id="agentNationality">
                    <?php echo $agent->getNationality(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Code d'identification</th>
            <td class="tdTable">
                <span id="agentIdentificationCode">
                    <?php echo $agent->getIdentificationCode(); ?>
                </span>
            </td>
        </tr>
    </table>
<?php } ?>
