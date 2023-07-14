<?php
include_once "../../config/database.php";
include_once "../classes/MissionStatus.php";

use app\classes\MissionStatus;

$missionStatusObj = new MissionStatus($pdo);
$missionStatuses = $missionStatusObj->getAllMissionStatuses();
?>

<h2>Statuts</h2>

<?php foreach($missionStatuses as $missionStatus) { ?>
    <form method="POST" action="../controllers/updateControllers/updateStatusController.php" class="editForm">
        <input type="hidden" name="statusId" value="<?php echo $missionStatus->getId(); ?>">
        <table class="editTable editTables">
            <tr>
                <th scope="row" class="thTable">Statut</th>
                <td class="tdTable">
                    <span id="missionStatus" class="tdContent"><?php echo $missionStatus->getStatus(); ?></span>
                    <input type="text" class="editInput" id="editStatus" name="status" placeholder="<?php echo $missionStatus->getStatus() ?>" style="display:none;" >
                </td>
            </tr>
            <tr>
                <td class="tbTable" colspan="2">
                    <div class="buttonsContainer">
                        <button class="button editButton" type="button" onClick="toggleEdit(this)">Modifier</button>
                        <button class="button saveButton" type="submit" style="display:none;">Sauvegarder</button>
                        <button class="button deleteButton" type="button" data-url="../controllers/deleteControllers/deleteStatusController.php">Supprimer</button>
                    </div>
                </td>
            </tr>
        </table>
        <div class="messageDivContainer">
            <div class="messageDiv"></div>
        </div>
    </form>
<?php } ?>