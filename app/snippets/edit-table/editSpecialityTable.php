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
                <span id="specialityCode"  class="tdContent">
                    <?php echo $speciality->getSpeciality(); ?>
                </span>
            </td>
        </tr>
    </table>
<?php } ?>
