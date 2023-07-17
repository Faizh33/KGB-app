<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once "../../../config/database.php";
include_once "../../classes/Speciality.php";

use app\classes\Speciality;

$specialityObj = new Speciality($pdo);

// Obtenir la page actuelle à partir des paramètres GET
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Nombre d'éléments par page
$perPage = 4;

// Obtenir le nombre total d'éléments
$totalItems = $specialityObj::countSpecialities();

// Calculer le nombre total de pages nécessaires
$totalPages = ceil($totalItems / $perPage);

//Récupération de toutes les spécialités
$specialities = $specialityObj::getAllSpecialitiesPagination($page, $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../public/css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" type="text/javascript"></script>
    <title>Spécialités</title>
</head>
<body>
    <h1>Tableau de bord Administrateur</h1>
    <h2>Spécialités</h2>

    <?php 
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) :
        foreach($specialities as $speciality) { 
    ?>
        <form method="POST" action="../../controllers/updateControllers/updateSpecialityController.php" class="editForm">
            <input type="hidden" name="specialityId" value="<?php echo $speciality->getId(); ?>">
            <table class="editTable editTables">
                <tr>
                    <th scope="row" class="thTable">Spécialité</th>
                    <td class="tdTable">
                        <span id="speciality" class="tdContent"><?php echo $speciality->getSpeciality(); ?></span>
                        <input type="text" class="editInput" id="editSpeciality" name="speciality" placeholder="<?php echo $speciality->getSpeciality() ?>" style="display:none;" >
                    </td>
                </tr>
                <tr>
                    <td class="tbTable" colspan="2">
                        <div class="buttonsContainer">
                            <button type="button" class="button editButton" onClick="toggleEdit(this)">Modifier</button>
                            <button type="submit" class="button saveButton" style="display:none;">Sauvegarder</button>
                            <button type="button" class="button deleteButton" data-url="../controllers/deleteControllers/deleteSpecialityController.php">Supprimer</button>
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
    <script src="../../../public/js/confirmDelete.js"></script>
    <script src="../../../public/js/toggleEdit.js"></script>
</body>
</html>