<?php
include_once "../../config/database.php";
include_once "../classes/Contact.php";

use app\classes\Contact;

$contactObj = new Contact($pdo);
$contacts = $contactObj::getAllContacts();
?>

<h2>Contacts</h2>

<?php foreach($contacts as $contact) { ?>
    <table id="form"  class="editTable">
        <tr>
            <th scope="row" class="thTable">Nom</th>
            <td class="tdTable">
                <span id="contactLastName" class="tdContent"><?php echo $contact->getLastName(); ?></span>
                <input type="text" class="editInput" id="editContactLastName" placeholder="<?php echo $contact->getLastName() ?>" style="display:none;" >
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Prénom</th>
            <td class="tdTable">
                <span id="contactFirstName" class="tdContent"><?php echo $contact->getFirstName(); ?></span>
                <input type="text" class="editInput" id="editContactFirstName" placeholder="<?php echo $contact->getFirstName() ?>" style="display:none;" >
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Date de naissance</th>
            <td class="tdTable">
                <span id="contactBirthDate" class="tdContent">
                    <?php
                    $birthDate = $contact->getBirthDate(); 
                    $birthDateObj = DateTime::createFromFormat('Y-m-d', $birthDate);
                    $formattedBirthDate = $birthDateObj->format('d/m/Y');
                    echo $formattedBirthDate;
                     ?>
                </span>
                <input type="text" class="editInput" id="editContactBirthDate" placeholder="<?php echo $formattedBirthDate; ?>" style="display:none;" >
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Nationalité</th>
            <td class="tdTable">
                <span id="contactNationality" class="tdContent"><?php echo $contact->getNationality(); ?></span>
                <input type="text" class="editInput" id="editContactNationality" placeholder="<?php echo $contact->getNationality() ?>" style="display:none;" >
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Nom de code</th>
            <td class="tdTable">
                <span id="contactCodeName" class="tdContent"><?php echo $contact->getCodeName(); ?></span>
                <input type="text" class="editInput" id="editContactCodeName" placeholder="<?php echo $contact->getCodeName() ?>" style="display:none;" >
            </td>
        </tr>
        <td class="tbTable" colspan="2">
            <div class="buttonsContainer">
                <button class="editButton" onClick="toggleEdit(this)">Modifier</button>
                <button class="saveButton" onClick="" style="display:none;">Sauvegarder</button>
            </div>
        </td>
    </table>
<?php } ?>
