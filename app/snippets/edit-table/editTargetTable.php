<?php
include_once "../../config/database.php";
include_once "../classes/Target.php";

use app\classes\Target;

$targetObj = new Target($pdo);
$targets = $targetObj->getAllTargets();
?>

<h2>Cibles</h2>

<?php foreach($targets as $target) { ?>
    <form method="POST" action="votre_script.php">
        <table id="form" class="editTable">
            <tr>
                <th scope="row" class="thTable">Nom</th>
                <td class="tdTable">
                    <span id="targetLastName" class="tdContent"><?php echo $target->getLastName(); ?></span>
                    <input type="text" class="editInput" id="editTargetLastName" name="lastName" placeholder="<?php echo $target->getLastName(); ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <th scope="row" class="thTable">Prénom</th>
                <td class="tdTable">
                    <span id="targetFirstName" class="tdContent"><?php echo $target->getFirstName(); ?></span>
                    <input type="text" class="editInput" id="editTargetFirstName" name="firstName" placeholder="<?php echo $target->getFirstName(); ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <th scope="row" class="thTable">Date de naissance</th>
                <td class="tdTable">
                    <span id="targetBirthDate" class="tdContent">
                        <?php 
                            $birthDate = $target->getBirthDate(); 
                            $birthDateObj = DateTime::createFromFormat('Y-m-d', $birthDate);
                            $formattedBirthDate = $birthDateObj->format('d/m/Y');
                            echo $formattedBirthDate;
                        ?>
                    </span>
                    <input type="text" class="editInput" id="editTargetBirthDate" name="birthDate" placeholder="<?php echo $formattedBirthDate; ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <th scope="row" class="thTable">Nationalité</th>
                <td class="tdTable">
                    <span id="targetNationality" class="tdContent"><?php echo $target->getNationality(); ?></span>
                    <input type="text" class="editInput" id="editTargetNationality" name="nationality" placeholder="<?php echo $target->getNationality(); ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <th scope="row" class="thTable">Nom de code</th>
                <td class="tdTable">
                    <span id="targetCodeName" class="tdContent"><?php echo $target->getCodeName(); ?></span>
                    <input type="text" class="editInput" id="editTargetCodeName" name="codeName" placeholder="<?php echo $target->getCodeName(); ?>" style="display:none;">
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