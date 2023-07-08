<?php
include_once "../../config/database.php";
include_once "../classes/MissionStatus.php";

use app\classes\MissionStatus;

$missionStatusObj = new MissionStatus($pdo);
$missionStatuses = $missionStatusObj::getAllMissionStatuses();
?>

<h2>Statuts</h2>

<?php foreach($missionStatuses as $missionStatus) { ?>
    <table id="form"  class="editTable">
        <tr>
            <th scope="row" class="thTable">Statut</th>
            <td class="tdTable">
                <span id="missionStatus"  class="tdContent"><?php echo $missionStatus->getStatus(); ?></span>
                <input type="text" class="editInput" id="editStatus" placeholder="<?php echo $missionStatus->getStatus() ?>" style="display:none;" >
            </td>
        </tr>
        <tr>
            <td class="tbTable" colspan="2">
                <div class="editButtonContainer">
                    <button class="editButton" onClick="">Modifier</button>
                </div>
            </td>
        </tr>
    </table>
<?php } ?>
