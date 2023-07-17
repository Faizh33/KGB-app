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
    <title>Statuts</title>
</head>
<body>
    <h1>Tableau de bord Administrateur</h1>
    <h2>Statuts</h2>

    <?php 
    if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) :
        foreach($missionStatuses as $missionStatus) { 
    ?>
        <form method="POST" action="../controllers/updateControllers/updateStatusController.php" class="editForm">
            <input type="hidden" name="statusId" value="<?php echo $missionStatus->getId(); ?>">
            <table class="editTable editTables">
                <tr>
                    <th scope="row" class="thTable">Statut</th>
                    <td class="tdTable">
                        <span id="missionStatus" class="tdContent"><?php echo $missionStatus->getStatus(); ?></span>
                        <input type="text" class="editInput" id="editStatus" name="status" placeholder="<?php echo $missionStatus->getStatus() ?>" style="display:none;" >
                    </td>
                </tr>
                <tr>
                    <td class="tbTable" colspan="2">
                        <div class="buttonsContainer">
                            <button class="button editButton" type="button" onClick="toggleEdit(this)">Modifier</button>
                            <button class="button saveButton" type="submit" style="display:none;">Sauvegarder</button>
                            <button class="button deleteButton" type="button" data-url="../controllers/deleteControllers/deleteStatusController.php">Supprimer</button>
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