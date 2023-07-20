<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/database.php';
require_once '../classes/Mission.php';
require_once '../classes/Speciality.php';
require_once '../classes/MissionStatus.php';
require_once '../classes/MissionType.php';
require_once '../classes/CountryNationality.php';

use app\classes\Mission;
use app\classes\Speciality;
use app\classes\MissionStatus;
use app\classes\MissionType;
use app\classes\CountryNationality;

// Création des objets
$missionObj = new Mission($pdo);
$specialityObj = new Speciality($pdo);
$missionStatusObj = new MissionStatus($pdo);
$missionTypeObj = new MissionType($pdo);
$countryNationalityObj = new CountryNationality($pdo);

// Obtenir la page actuelle à partir des paramètres GET
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Nombre d'éléments par page
$perPage = 2;

// Obtenir le nombre total d'éléments
$totalItems = $missionObj::countMissions();

// Calculer le nombre total de pages nécessaires
$totalPages = ceil($totalItems / $perPage);

// Obtenir le tri souhaité à partir du paramètre GET
$sort = isset($_GET['sort']) ? $_GET['sort'] : null;

// Obtenir le tri actuel (ascendant ou descendant) à partir du paramètre GET
$currentSortDir = isset($_GET['sortDir']) ? $_GET['sortDir'] : 'asc';

// Inverser la direction du tri pour le prochain clic sur l'en-tête de colonne
$nextSortDir = $currentSortDir === 'asc' ? 'desc' : 'asc';

// Définir le lien pour inverser le tri
$sortDirLink = "?sort=$sort&sortDir=" . ($currentSortDir === 'asc' ? 'desc' : 'asc');

// Récupération de toutes les missions et données en lien
$missions = $missionObj::getAllMissionsPagination($page, $perPage);
$specialities = $specialityObj::getAllSpecialities();
$missionStatuses = $missionStatusObj::getAllMissionStatuses();
$missionTypes = $missionTypeObj::getAllMissionTypes();
$countriesNationalities = $countryNationalityObj::getAllCountriesNationalities();

// Vérifier si la variable de session contenant les missions filtrées existe
if (isset($_SESSION['filteredMissions'])) {
    // Utiliser les missions filtrées si elles existent
    $filteredMissions = $_SESSION['filteredMissions'];

    // Créer un tableau pour stocker les nouveaux objets Mission
    $missionsToShow = [];

    // Parcourir les objets stdClass filtrés et créer de nouveaux objets Mission
    foreach ($filteredMissions as $missionData) {
        // Extraire les propriétés de chaque objet stdClass
        $id = $missionData->id;
        // Créer un nouvel objet Mission en utilisant les propriétés extraites
        $missionObj = new Mission($pdo);
        $mission = $missionObj::getMissionById($id);
        $missionsToShow[] = $mission;
    }

    // Supprimer la variable de session pour ne pas conserver les anciennes données filtrées
    unset($_SESSION['filteredMissions']);
} else {
    // Utiliser toutes les missions si les données filtrées n'existent pas
    $missionsToShow = $missions;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/home.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" type="text/javascript"></script>
    <title>Missions du KGB</title>
</head>
<body>
    <header id="homeHeader">
        <div id="logContainer">
            <div id="logButton" class="button">
                <?php
                if (isset($_SESSION['admin'])) {
                    echo "<a href='../controllers/logControllers/logoutController.php' id='logLink' class='link'>Déconnexion</a>";
                } else {
                    echo "<a href='loginForm.php' id='logLink' class='link'>Se connecter</a>";
                }
                ?>
            </div>
        </div>
    </header>
    <h1>Liste des missions du KGB</h1>
        <!-- Formulaire de filtrage -->
        <div class="filterBar">
        <form method="post" action="../controllers/filterController.php">
            <label for="country" class="filterLabelText">Pays :</label>
            <select name="country" id="country">
                <option value="">Tous les pays</option>
                <?php foreach($countriesNationalities as $countryNationality) {
                    $countryId = $countryNationality->getId();
                    $country = $countryNationality->getCountry();
                    echo "<option value='$countryId'>$country</option>";
                 } ?>
            </select>

            <label for="startDate" class="filterLabelText">Date de début :</label>
            <input type="date" name="startDate" id="startDate">

            <label for="missionStatus" class="filterLabelText">Statut de mission :</label>
            <select name="missionStatus" id="missionStatus">
                <option value="">Tous les statuts</option>
                <?php foreach($missionStatuses as $missionStatus) {
                    $statusId = $missionStatus->getId();
                    $status = $missionStatus->getStatus();
                    echo "<option value='$statusId'>$status</option>";
                 } ?>
            </select>

            <label for="missionType" class="filterLabelText">Type de mission :</label>
            <select name="missionType" id="missionType">
                <option value="">Tous les types de mission</option>
                <?php foreach($missionTypes as $missionType) {
                    $typeId = $missionType->getId();
                    $type = $missionType->getType();
                    echo "<option value='$typeId'>$type</option>";
                 } ?>
            </select>

            <button type="submit">Filtrer</button>
        </form>
    </div>
    <!-- tableau des missions -->
    <table>
        <tr>
            <th>
                <a href="?sort=startDate&sortDir=<?php echo $sort === 'startDate' ? $nextSortDir : 'asc'; ?>" class="thLink">
                    <?php
                    if ($sort === 'startDate') {
                        echo ($currentSortDir === 'asc') ? ' ▲' : ' ▼';
                    }
                    ?>
                    Date de début
                </a>
            </th>
            <th>
                <a href="?sort=title&sortDir=<?php echo $sort === 'title' ? $nextSortDir : 'asc'; ?>" class="thLink">
                    <?php
                    if ($sort === 'title') {
                        echo ($currentSortDir === 'asc') ? ' ▲' : ' ▼';
                    }
                    ?>
                    Titre de la mission
                </a>
            </th>
            <th>
                <a href="?sort=codeName&sortDir=<?php echo $sort === 'codeName' ? $nextSortDir : 'asc'; ?>" class="thLink">
                    <?php
                    if ($sort === 'codeName') {
                        echo ($currentSortDir === 'asc') ? ' ▲' : ' ▼';
                    }
                    ?>
                    Nom de code
                </a>
            </th>
            <th>
                <a href="?sort=missionstatuses_id&sortDir=<?php echo $sort === 'missionstatuses_id' ? $nextSortDir : 'asc'; ?>" class="thLink">
                    <?php
                    if ($sort === 'missionstatuses_id') {
                        echo ($currentSortDir === 'asc') ? ' ▲' : ' ▼';
                    }
                    ?>
                    Statut
                </a>
            </th>
            <th></th>
        </tr>

        <?php foreach ($missionsToShow as $mission) : ?>
            <tr>
                <td>
                    <?php 
                    $startDate = $mission->getStartDate();
                    $startDateObj = DateTime::createFromFormat('Y-m-d', $startDate);
                    $formattedStartDate = $startDateObj->format('d/m/Y');
                    echo $formattedStartDate;
                    ?>
                </td>
                <td><a href="missionDetails.php?mission=<?php echo $mission->getId(); ?>"><?php echo $mission->getTitle(); ?></a></td>
                <td><?php echo $mission->getCodeName(); ?></td>
                <td>
                    <?php
                    $missionStatus = MissionStatus::getMissionStatusById($mission->getMissionStatus()->getId());
                    echo $missionStatus->getStatus();
                    ?>
                </td>
                <td>
                    <?php
                    $missionId = $mission->getId();
                    if(isset($_SESSION['admin'])) {
                        echo "<a href='../controllers/deleteControllers/deleteMissionController.php?mission=" . $missionId . "' id='deleteButton' class='' onclick='return confirmDelete();'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='1.5vw' height='1.5vw' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                                    <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z'/>
                                </svg>
                                <span class='tooltip'>Supprimer</span>
                            </a>";
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <!-- Fermer la connexion -->
        <?php $pdo = null; ?>
    </table>
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
    <?php if (isset($_SESSION['admin'])) { ?>
        <div class="adminButtonContainer">
            <div id="dashboardHomeButton" class="button adminButton" >
                <a href='../views/dashboardEdit.php' id='dashboardLink' class="link">Tableau de bord</a>
            </div>
        </div>
    <?php } ?>
    
    <script src="../../public/js/confirmDelete.js"></script>
</body>
</html>