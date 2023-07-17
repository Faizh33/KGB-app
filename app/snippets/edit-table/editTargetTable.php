<?php
include_once "../../config/database.php";
include_once "../classes/Target.php";

use app\classes\Target;

$targetObj = new Target($pdo);
$targets = $targetObj->getAllTargets();
?>

<h2>Cibles</h2>

<?php foreach($targets as $target) { ?>
    <form method="POST" action="../controllers/updateControllers/updateTargetController.php" class="editForm">
        <input type="hidden" name="targetId" value="<?php echo $target->getId(); ?>">
        <table class="editTable editTables">
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
                    <span id="targetNationality" class="tdContent"><?php echo $target->getNationality()->getNationality(); ?></span>
                    <select name="targetNationality" id="targetNationality" class="editInput" style="display:none;" required>
                    <option value="">--<?php echo $target->getNationality()->getNationality(); ?>--</option>
                        <?php
                        $countriesNationalities = $countryNationalityObj::getAllCountriesNationalities();
                        foreach ($countriesNationalities as $countryNationality) {
                            $nationality = $countryNationality->getNationality();
                            echo "<option value=\"$nationality\">$nationality</option>";
                        }
                        ?>
                    </select>
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
                        <button class="button editButton" type="button" onClick="toggleEdit(this)">Modifier</button>
                        <button class="button saveButton" type="submit" style="display:none;">Sauvegarder</button>
                        <button class="button deleteButton" type="button" data-url="../controllers/deleteControllers/deleteTargetController.php">Supprimer</button>
                    </div>
                </td>
            </tr>
        </table>
        <div class="messageDivContainer">
            <div class="messageDiv"></div>
        </div>
    </form>
<?php } ?>