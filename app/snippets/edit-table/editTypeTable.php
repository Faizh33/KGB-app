<?php
include_once "../../config/database.php";
include_once "../classes/MissionType.php";

use app\classes\MissionType;

$missionTypeObj = new MissionType($pdo);
$missionTypes = $missionTypeObj::getAllMissionTypes();
?>

<h2>Types</h2>

<?php foreach($missionTypes as $missionType) { ?>
    <table id="form" class="editTable">
        <tr>
            <th scope="row" class="thTable">Type</th>
            <td class="tdTable">
                <span id="missionTypeType"  class="tdContent"><?php echo $missionType->getType(); ?></span>
                <input type="text" class="editInput" id="editType" placeholder="<?php echo $missionType->getType() ?>" style="display:none;" >
            </td>
        </tr>
    </table>
<?php } ?>
