<?php
include_once "../../config/database.php";
include_once "../classes/SafeHouse.php";

use app\classes\SafeHouse;

$safeHouseObj = new SafeHouse($pdo);
$safeHouses = $safeHouseObj::getAllSafeHouses();
?>

<h2>Planques</h2>

<?php foreach($safeHouses as $safeHouse) { ?>
    <table id="form"  class="editTable">
        <tr>
            <th scope="row" class="thTable">Code</th>
            <td class="tdTable">
                <span id="safeHouseCode" class="tdContent"><?php echo $safeHouse->getCode(); ?></span>
                <input type="text" class="editInput" id="editSafeHouseCode" placeholder="<?php echo $safeHouse->getCode() ?>" style="display:none;" >
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Adresse</th>
            <td class="tdTable">
                <span id="safeHouseAddress" class="tdContent"><?php echo $safeHouse->getAddress(); ?></span>
                <input type="text" class="editInput" id="editSafeHouseAddress" placeholder="<?php echo $safeHouse->getAddress() ?>" style="display:none;" >
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Pays</th>
            <td class="tdTable">
                <span id="safeHouseCountry" class="tdContent"><?php echo $safeHouse->getCountry(); ?></span>
                <input type="text" class="editInput" id="editSafeHouseCountry" placeholder="<?php echo $safeHouse->getCountry() ?>" style="display:none;" >
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Type</th>
            <td class="tdTable">
                <span id="safeHouseType" class="tdContent"><?php echo $safeHouse->getType(); ?></span>
                <input type="text" class="editInput" id="editSafeHouseType" placeholder="<?php echo $safeHouse->getType() ?>" style="display:none;" >
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
