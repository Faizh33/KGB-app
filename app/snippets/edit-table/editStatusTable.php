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
                <span id="missionStatusStatus"  class="tdContent">
                    <?php echo $missionStatus->getStatus(); ?>
                </span>
            </td>
        </tr>
    </table>
<?php } ?>
