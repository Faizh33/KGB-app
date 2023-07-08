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
                <span id="safeHouseCode" class="tdContent">
                    <?php echo $safeHouse->getCode(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Adresse</th>
            <td class="tdTable">
                <span id="safeHouseAddress" class="tdContent">
                    <?php echo $safeHouse->getAddress(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Pays</th>
            <td class="tdTable">
                <span id="safeHouseCountry" class="tdContent">
                    <?php echo $safeHouse->getCountry(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Type</th>
            <td class="tdTable">
                <span id="safeHouseType" class="tdContent">
                    <?php echo $safeHouse->getType(); ?>
                </span>
            </td>
        </tr>
    </table>
<?php } ?>
