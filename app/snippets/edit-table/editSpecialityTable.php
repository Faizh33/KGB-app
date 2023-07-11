<?php
include_once "../../config/database.php";
include_once "../classes/Speciality.php";

use app\classes\Speciality;

$specialityObj = new Speciality($pdo);
$specialities = $specialityObj::getAllSpecialities();
?>

<h2>Spécialités</h2>

<?php foreach($specialities as $speciality) { ?>
    <form method="POST" action="../controllers/updateControllers/updateSpecialityController.php">
        <input type="hidden" name="specialityId" value="<?php echo $speciality->getId(); ?>">
        <table id="form" class="editTable">
            <tr>
                <th scope="row" class="thTable">Spécialité</th>
                <td class="tdTable">
                    <span id="speciality" class="tdContent"><?php echo $speciality->getSpeciality(); ?></span>
                    <input type="text" class="editInput" id="editSpeciality" name="speciality" placeholder="<?php echo $speciality->getSpeciality() ?>" style="display:none;" >
                </td>
            </tr>
            <tr>
                <td class="tbTable" colspan="2">
                    <div class="buttonsContainer">
                        <button type="button" class="editButton" onClick="toggleEdit(this)">Modifier</button>
                        <button type="submit" class="saveButton" style="display:none;">Sauvegarder</button>
                    </div>
                </td>
            </tr>
        </table>
    </form>
<?php } ?>
