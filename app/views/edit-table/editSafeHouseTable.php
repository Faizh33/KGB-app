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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" type="text/javascript"></script>
    <title>Planques</title>
</head>
<body>
    <header id="dashboardHeader">
        <!-- Bouton de retour à l'accueil -->
        <div id="homeBtn">
            <a href="../home.php" id="homeBtnLink">Accueil</a>
        </div>  
    </header>

    <h1>Tableau de bord Administrateur</h1>
    <h2>Planques</h2>

    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
        <div class="plusItem">
            <a href="../dashboardCreate.php" class="plusLink">
                <svg xmlns="http://www.w3.org/2000/svg" width="3vw" height="3vw" fill="rgb(186, 238, 233)" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                </svg>
            </a>
        </div>
        <?php foreach($safeHouses as $safeHouse) { ?>
        <form method="POST" action="../../controllers/updateControllers/updateSafeHouseController.php" class="editForm">
            <input type="hidden" name="safeHouseId" value="<?php echo $safeHouse->getId(); ?>">
            <table class="editTable editTables">
                <!-- Code de la planque -->
                <tr>
                    <th scope="row" class="thTable">Code</th>
                    <td class="tdTable">
                        <span id="safeHouseCode" class="tdContent"><?php echo $safeHouse->getCode(); ?></span>
                        <input type="text" class="editInput" id="editSafeHouseCode" name="safeHouseCode" placeholder="<?php echo $safeHouse->getCode() ?>" style="display:none;" >
                    </td>
                </tr>
                <!-- Adresse de la planque -->
                <tr>
                    <th scope="row" class="thTable">Adresse</th>
                    <td class="tdTable">
                        <span id="safeHouseAddress" class="tdContent"><?php echo $safeHouse->getAddress(); ?></span>
                        <input type="text" class="editInput" id="editSafeHouseAddress" name="safeHouseAddress" placeholder="<?php echo $safeHouse->getAddress() ?>" style="display:none;" >
                    </td>
                </tr>
                <!-- Pays de la planque -->
                <tr>
                    <th scope="row" class="thTable">Pays</th>
                    <td class="tdTable">
                        <span id="safeHouseCountry" class="tdContent"><?php echo $safeHouse->getCountry()->getCountry(); ?></span>
                        <select name="safeHouseCountry" id="safeHouseCountry" class="editInput" style="display:none" >
                        <option value="">--<?php echo $safeHouse->getCountry()->getCountry(); ?>--</option>
                        <?php
                        $countriesNationalities = $countryNationalityObj::getAllCountriesNationalities();
                        foreach ($countriesNationalities as $countryNationality) {
                            $country = $countryNationality->getCountry();
                            $countryId = $countryNationality->getId();
                            echo "<option value=\"$countryId\">$country</option>";                        }
                        ?>
                        </select>
                    </td>
                </tr>
                <!-- Type de la planque -->
                <tr>
                    <th scope="row" class="thTable">Type</th>
                    <td class="tdTable">
                        <span id="safeHouseType" class="tdContent"><?php echo $safeHouse->getType(); ?></span>
                        <input type="text" class="editInput" id="editSafeHouseType" name="safeHouseType" placeholder="<?php echo $safeHouse->getType() ?>" style="display:none;" >
                    </td>
                </tr>
                <!-- Boutons d'édition, de sauvegarde et de suppression -->
                <tr>
                    <td class="tbTable" colspan="2">
                        <div class="buttonsContainer">
                            <button class="button editButton" type="button" onClick="toggleEdit(this)">Modifier</button>
                            <button class="button saveButton" type="submit" style="display:none;">Sauvegarder</button>
                            <button class="button deleteButton" type="button" data-url="../../controllers/deleteControllers/deleteSafeHouseController.php">Supprimer</button>
                        </div>
                    </td>
                </tr>
            </table>
            <!-- message affiché à la suppression -->
            <div class="messageDivContainer">
                <div class="messageDiv"></div>
            </div>
        </form>
    <?php } endif; ?>
    <!-- Liens de la pagination -->
    <div class="pagination">
        <?php
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1; // Définit la valeur par défaut de la page à 1 si elle n'est pas définie dans l'URL
        for ($i = 1; $i <= $totalPages; $i++) {
            $activeClass = ($currentPage == $i) ? 'active' : '';
        ?>
            <a href="?page=<?php echo $i; ?>" class="paginationLink <?php echo $activeClass; ?>"><?php echo $i; ?></a>
        <?php } ?>
    </div>
    <!-- Bouton de retour arrière -->
    <div class="backButtonContainer">
        <a href="../../views/dashboardEdit.php" class="backButton">
            Retour
        </a>
    </div>
    <script src="../../../dist/confirmDelete.bundle.js"></script>
    <script src="../../../dist/toggleEdit.bundle.js"></script>
</body>
</html>