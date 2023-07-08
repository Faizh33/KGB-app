<?php
include_once "../../config/database.php";
include_once "../classes/Target.php";

use app\classes\Target;

$targetObj = new Target($pdo);
$targets = $targetObj::getAllTargets();
?>

<h2>Cibles</h2>

<?php foreach($targets as $target) { ?>
    <table id="form"  class="editTable">
        <tr>
            <th scope="row" class="thTable">Nom</th>
            <td class="tdTable">
                <span id="targetLastName" class="tdContent">
                    <?php echo $target->getLastName(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Prénom</th>
            <td class="tdTable">
                <span id="targetFirstName" class="tdContent">
                    <?php echo $target->getFirstName(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Date de naissance</th>
            <td class="tdTable">
                <span id="targetBirthDate" class="tdContent">
                    <?php echo $target->getBirthDate(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Nationalité</th>
            <td class="tdTable">
                <span id="targetNationality" class="tdContent">
                    <?php echo $target->getNationality(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Nom de code</th>
            <td class="tdTable">
                <span id="targetCodeName" class="tdContent">
                    <?php echo $target->getCodeName(); ?>
                </span>
            </td>
        </tr>
    </table>
<?php } ?>
