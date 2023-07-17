<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once "../../../config/database.php";
include_once "../../classes/SafeHouse.php";
include_once "../../classes/CountryNationality.php";

use app\classes\SafeHouse;
use app\classes\CountryNationality;

$safeHouseObj = new SafeHouse($pdo);
$countryNationalityObj = new CountryNationality($pdo);

// Obtenir la page actuelle à partir des paramètres GET
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Nombre d'éléments par page
$perPage = 3;

// Obtenir le nombre total d'éléments
$totalItems = $safeHouseObj::countSafeHouses();

// Calculer le nombre total de pages nécessaires
$totalPages = ceil($totalItems / $perPage);

//Récupération de toutes les planques
$safeHouses = $safeHouseObj::getAllSafeHousesPagination($page, $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../public/css/dashboard.css">
    <title>Planques</title>
</head>
<body>
    <h1>Tableau de bord Administrateur</h1>
    <h2>Planques</h2>

    <?php 
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) :
        foreach($safeHouses as $safeHouse) { ?>
        <form method="POST" action="../controllers/updateControllers/updateSafeHouseController.php" class="editForm">
            <input type="hidden" name="safeHouseId" value="<?php echo $safeHouse->getId(); ?>">
            <table class="editTable editTables">
                <tr>
                    <th scope="row" class="thTable">Code</th>
                    <td class="tdTable">
                        <span id="safeHouseCode" class="tdContent"><?php echo $safeHouse->getCode(); ?></span>
                        <input type="text" class="editInput" id="editSafeHouseCode" name="safeHouseCode" placeholder="<?php echo $safeHouse->getCode() ?>" style="display:none;" >
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Adresse</th>
                    <td class="tdTable">
                        <span id="safeHouseAddress" class="tdContent"><?php echo $safeHouse->getAddress(); ?></span>
                        <input type="text" class="editInput" id="editSafeHouseAddress" name="safeHouseAddress" placeholder="<?php echo $safeHouse->getAddress() ?>" style="display:none;" >
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Pays</th>
                    <td class="tdTable">
                        <span id="safeHouseCountry" class="tdContent"><?php echo $safeHouse->getCountry()->getCountry(); ?></span>
                        <select name="safeHouseCountry" id="safeHouseCountry" class="editInput" style="display:none" required>
                        <option value="">--<?php echo $safeHouse->getCountry()->getCountry(); ?>--</option>
                        <?php
                        $countriesNationalities = $countryNationalityObj::getAllCountriesNationalities();
                        foreach ($countriesNationalities as $countryNationality) {
                            $country = $countryNationality->getCountry();
                            echo "<option value=\"$country\">$country</option>";
                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="thTable">Type</th>
                    <td class="tdTable">
                        <span id="safeHouseType" class="tdContent"><?php echo $safeHouse->getType(); ?></span>
                        <input type="text" class="editInput" id="editSafeHouseType" name="safeHouseType" placeholder="<?php echo $safeHouse->getType() ?>" style="display:none;" >
                    </td>
                </tr>
                <tr>
                    <td class="tbTable" colspan="2">
                        <div class="buttonsContainer">
                            <button class="button editButton" type="button" onClick="toggleEdit(this)">Modifier</button>
                            <button class="button saveButton" type="submit" style="display:none;">Sauvegarder</button>
                            <button class="button deleteButton" type="button" data-url="../controllers/deleteControllers/deleteSafeHouseController.php">Supprimer</button>
                        </div>
                    </td>
                </tr>
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
    <script src="../../public/js/confirmDelete.js"></script>
</body>
</html>