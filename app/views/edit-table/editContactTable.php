<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once "../../../config/database.php";
include_once "../../classes/Contact.php";
include_once "../../classes/CountryNationality.php";

use app\classes\Contact;
use app\classes\CountryNationality;

// Création des objets
$countryNationalityObj = new CountryNationality($pdo);
$contactObj = new Contact($pdo);

// Obtenir la page actuelle à partir des paramètres GET
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Nombre d'éléments par page
$perPage = 3;

// Obtenir le nombre total d'éléments
$totalItems = $contactObj::countContacts();

// Calculer le nombre total de pages nécessaires
$totalPages = ceil($totalItems / $perPage);

//Récupération de tous les contacts
$contacts = $contactObj::getAllContactsPagination($page, $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../../../public/css/dashboard.css">
    <title>Contacts</title>
</head>
<body>
    <h1>Tableau de bord Administrateur</h1>
    <h2>Contacts</h2>

    <?php 
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) :
        foreach($contacts as $contact) { 
    ?>
        <form method="POST" action="../../controllers/updateControllers/updateContactController.php" class="editForm">
            <input type="hidden" name="contactId" value="<?php echo $contact->getId(); ?>">
            <table class="editTable editTables">
                <tr>
                    <th scope="row" class="thTable">Nom</th>
                    <td class="tdTable">
                        <span id="contactLastName" class="tdContent"><?php echo $contact->getLastName(); ?></span>
                        <input type="text" class="editInput" id="editContactLastName" name="contactLastName" placeholder="<?php echo $contact->getLastName() ?>" style="display:none;" >
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Prénom</th>
                    <td class="tdTable">
                        <span id="contactFirstName" class="tdContent"><?php echo $contact->getFirstName(); ?></span>
                        <input type="text" class="editInput" id="editContactFirstName" name="contactFirstName" placeholder="<?php echo $contact->getFirstName() ?>" style="display:none;" >
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
                        <input type="text" class="editInput" id="editContactBirthDate" name="contactBirthDate" placeholder="<?php echo $formattedBirthDate; ?>" style="display:none;" >
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Nationalité</th>
                    <td class="tdTable">
                        <span id="contactNationality" class="tdContent"><?php echo $contact->getNationality()->getNationality(); ?></span>
                        <select name="contactNationality" id="contactNationality" class="editInput" style="display:none;" required>
                        <option value="">--<?php echo $contact->getNationality()->getNationality(); ?>--</option>
                        <?php
                            $countriesNationalities = $countryNationalityObj::getAllCountriesNationalities();
                            foreach ($countriesNationalities as $countryNationality) {
                                $nationality = $countryNationality->getNationality();
                                $nationalityId = $countryNationality->getId();
                                echo "<option value=\"$nationalityId\">$nationality</option>";                            }
                        ?>
                        </select>                
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Nom de code</th>
                    <td class="tdTable">
                        <span id="contactCodeName" class="tdContent"><?php echo $contact->getCodeName(); ?></span>
                        <input type="text" class="editInput" id="editContactCodeName" name="contactCodeName" placeholder="<?php echo $contact->getCodeName() ?>" style="display:none;" >
                    </td>
                </tr>
                <td class="tbTable" colspan="2">
                    <div class="buttonsContainer">
                        <button type="button" class="button editButton" onClick="toggleEdit(this)">Modifier</button>
                        <button type="submit" class="button saveButton" style="display:none;">Sauvegarder</button>
                        <button type="button" class="button deleteButton" data-url="../controllers/deleteControllers/deleteContactController.php">Supprimer</button>
                    </div>
                </td>
            </table>
            <div class="messageDivContainer">
                <div class="messageDiv"></div>
            </div>
        </form>
    <?php } endif; ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="?page=<?php echo $i; ?>" class="paginationLink"><?php echo $i; ?></a>
        <?php } ?>
    </div>

    <!-- Bouton de retour arrière -->
    <div class="backButtonContainer">
        <a href="../../views/dashboardEdit.php" class="backButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="1.5vw" height="1.5vw" fill="currentColor" color="white" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
            </svg>
            <span class="backText">Retour</span>
        </a>
    </div>
    <script src="../../../public/js/confirmDelete.js"></script>
    <script src="../../../public/js/toggleEdit.js"></script>
</body>
</html>