<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once "../../../config/database.php";
include_once "../../classes/MissionStatus.php";

use app\classes\MissionStatus;

$missionStatusObj = new MissionStatus($pdo);

// Obtenir la page actuelle à partir des paramètres GET
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Nombre d'éléments par page
$perPage = 4;

// Obtenir le nombre total d'éléments
$totalItems = $missionStatusObj::countMissionStatuses();

// Calculer le nombre total de pages nécessaires
$totalPages = ceil($totalItems / $perPage);

//Récupération de tous les statuts de mission
$missionStatuses = $missionStatusObj::getAllMissionStatusesPagination($page, $perPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../../public/css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" type="text/javascript"></script>
    <title>Statuts</title>
</head>
<body>
    <header id="dashboardHeader">
        <!-- Bouton de retour à l'accueil -->
        <div id="homeBtn">
            <a href="../views/home.php" id="homeBtnLink">Accueil</a>
        </div>  
    </header>

    <h1>Tableau de bord Administrateur</h1>
    <h2>Statuts</h2>

    <?php 
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) :
        foreach($missionStatuses as $missionStatus) { 
    ?>
        <form method="POST" action="../../controllers/updateControllers/updateStatusController.php" class="editForm">
            <input type="hidden" name="statusId" value="<?php echo $missionStatus->getId(); ?>">
            <table class="editTable editTables">
                <!-- Nom du statut -->
                <tr>
                    <th scope="row" class="thTable">Statut</th>
                    <td class="tdTable">
                        <span id="missionStatus" class="tdContent"><?php echo $missionStatus->getStatus(); ?></span>
                        <input type="text" class="editInput" id="editStatus" name="status" placeholder="<?php echo $missionStatus->getStatus() ?>" style="display:none;" >
                    </td>
                </tr>
                <!-- Boutons d'édition, de sauvegarde et de suppression -->
                <tr>
                    <td class="tbTable" colspan="2">
                        <div class="buttonsContainer">
                            <button class="button editButton" type="button" onClick="toggleEdit(this)">Modifier</button>
                            <button class="button saveButton" type="submit" style="display:none;">Sauvegarder</button>
                            <button class="button deleteButton" type="button" data-url="../../controllers/deleteControllers/deleteStatusController.php">Supprimer</button>
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
    <script src="../../../public/js/confirmDelete.js"></script>
    <script src="../../../public/js/toggleEdit.js"></script>
</body>
</html>