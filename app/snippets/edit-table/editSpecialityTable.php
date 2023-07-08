<?php
include_once "../../config/database.php";
include_once "../classes/Speciality.php";

use app\classes\Speciality;

$specialityObj = new Speciality($pdo);
$specialities = $specialityObj::getAllSpecialities();
?>

<h2>Spécialités</h2>

<?php foreach($specialities as $speciality) { ?>
    <table id="form" class="editTable">
        <tr>
            <th scope="row" class="thTable">Spécialité</th>
            <td class="tdTable">
                <span id="speciality"  class="tdContent"><?php echo $speciality->getSpeciality(); ?></span>
                <input type="text" class="editInput" id="editSpeciality" placeholder="<?php echo $speciality->getSpeciality() ?>" style="display:none;" >
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
