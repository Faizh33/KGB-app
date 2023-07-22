<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once "../../../config/database.php";
include_once "../../classes/CountryNationality.php";

use app\classes\CountryNationality;

$countryNationalityObj = new CountryNationality($pdo);

// Obtenir la page actuelle à partir des paramètres GET
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Nombre d'éléments par page
$perPage = 4;

// Obtenir le nombre total d'éléments
$totalItems = $countryNationalityObj::countCountriesNationalities();

// Calculer le nombre total de pages nécessaires
$totalPages = ceil($totalItems / $perPage);

//Récupération de toutes les spécialités
$countriesNationalities = $countryNationalityObj::getAllCountriesNationalitiesPagination($page, $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../public/css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" type="text/javascript"></script>
    <title>Pays/Nationalités</title>
</head>
<body>
    <header id="dashboardHeader">
        <!-- Bouton de retour à l'accueil -->
        <div id="homeBtn">
            <a href="../home.php" id="homeBtnLink">Accueil</a>
        </div>  
    </header>

    <h1>Tableau de bord Administrateur</h1>
    <h2>Pays / Nationalités</h2>

    <?php 
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
        <div class="plusItem">
            <a href="../dashboardCreate.php" class="plusLink">
                <svg xmlns="http://www.w3.org/2000/svg" width="3vw" height="3vw" fill="rgb(186, 238, 233)" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                </svg>
            </a>
        </div>
        <?php 
        foreach($countriesNationalities as $countryNationality) { 
        ?>
        <form method="POST" action="../../controllers/updateControllers/updateCountryNationalityController.php" class="editForm">
            <input type="hidden" name="countryNationalityId" value="<?php echo $countryNationality->getId(); ?>">
            <table class="editTable editTables">
                <!-- Pays -->
                <tr>
                    <th scope="row" class="thTable">Pays</th>
                    <td class="tdTable">
                        <span id="countryNationalityCountry" class="tdContent"><?php echo $countryNationality->getCountry(); ?></span>
                        <input type="text" class="editInput" id="editCountry" name="country" placeholder="<?php echo $countryNationality->getCountry(); ?>" style="display:none;">
                    </td>
                </tr>
                <!-- Nationalité -->
                <tr>
                    <th scope="row" class="thTable">Nationalité</th>
                    <td class="tdTable">
                        <span id="countryNationalityNationality" class="tdContent"><?php echo $countryNationality->getNationality(); ?></span>
                        <input type="text" class="editInput" id="editNationality" name="nationality" placeholder="<?php echo $countryNationality->getNationality(); ?>" style="display:none;">
                    </td>
                </tr>
                <!-- Boutons d'édition, de sauvegarde et de suppression -->
                <tr>
                    <td class="tbTable" colspan="2">
                        <div class="buttonsContainer">
                            <button class="button editButton" type="button" onClick="toggleEdit(this)">Modifier</button>
                            <button class="button saveButton" type="submit" style="display:none;">Sauvegarder</button>
                            <button class="button deleteButton" type="button" data-url="../../controllers/deleteControllers/deleteCountryNationalityController.php">Supprimer</button>
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