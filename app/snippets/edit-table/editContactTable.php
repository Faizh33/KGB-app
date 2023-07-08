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
                <span id="contactLastName" class="tdContent">
                    <?php echo $contact->getLastName(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Prénom</th>
            <td class="tdTable">
                <span id="contactFirstName" class="tdContent">
                    <?php echo $contact->getFirstName(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Date de naissance</th>
            <td class="tdTable">
                <span id="contactBirthDate" class="tdContent">
                    <?php echo $contact->getBirthDate(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Nationalité</th>
            <td class="tdTable">
                <span id="contactNationality" class="tdContent">
                    <?php echo $contact->getNationality(); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th scope="row" class="thTable">Nom de code</th>
            <td class="tdTable">
                <span id="contactCodeName" class="tdContent">
                    <?php echo $contact->getCodeName(); ?>
                </span>
            </td>
        </tr>
    </table>
<?php } ?>
