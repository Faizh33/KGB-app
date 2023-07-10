<?php
include_once "../../config/database.php";
include_once "../classes/MissionType.php";

use app\classes\MissionType;

$missionTypeObj = new MissionType($pdo);
$missionTypes = $missionTypeObj->getAllMissionTypes();
?>

<h2>Types</h2>

<?php foreach($missionTypes as $missionType) { ?>
    <form method="POST" action="votre_script.php">
        <table id="form" class="editTable">
            <tr>
                <th scope="row" class="thTable">Type</th>
                <td class="tdTable">
                    <span id="missionTypeType" class="tdContent"><?php echo $missionType->getType(); ?></span>
                    <input type="text" class="editInput" id="editType" name="type" placeholder="<?php echo $missionType->getType(); ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <td class="tbTable" colspan="2">
                    <div class="buttonsContainer">
                        <button class="editButton" onClick="toggleEdit(this)">Modifier</button>
                        <button class="saveButton" type="submit" style="display:none;">Sauvegarder</button>
                    </div>
                </td>
            </tr>
        </table>
    </form>
<?php } ?>