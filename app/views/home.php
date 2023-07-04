<?php
session_start();
require_once '../../config/database.php';
require_once '../classes/Mission.php';
require_once '../classes/Speciality.php';
require_once '../classes/MissionStatus.php';
require_once '../classes/MissionType.php';

use app\classes\Mission;
use app\classes\Speciality;
use app\classes\MissionStatus;
use app\classes\MissionType;

// Création de l'objet Mission
$missionObj = new Mission($pdo);
$specialityObj = new Speciality($pdo);
$missionStatusObj = new MissionStatus($pdo);
$missionTypeObj = new MissionType($pdo);

// Récupération de toutes les missions
$specialities = $specialityObj->getAllSpecialities();
$missionStatuses = $missionStatusObj->getAllMissionStatuses();
$missionTypes = $missionTypeObj->getAllMissionTypes();
$missions = $missionObj->getAllMissions();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/home.css">
    <title>Missions du KGB</title>
</head>
<body>
    <header id="homeHeader">
        <div id="logContainer">
            <div id="logButton">
                <?php
                if (isset($_SESSION['admin'])) {
                    echo "<a href='../controller/logout.php' id='logLink'>Déconnexion</a>";
                } else {
                    echo "<a href='loginForm.php' id='logLink'>Se connecter</a>";
                }
                ?>
            </div>
        </div>
    </header>
    <h1>Liste des missions du KGB</h1>
    <table>
        <tr>
            <th>Date de début</th>
            <th>Titre de la mission</th>
            <th>Nom de code</th>
            <th>Statut</th>
            <th></th>
        </tr>

        <?php foreach ($missions as $mission) : ?>
            <tr>
                <td>
                <?php 
                    $startDate = $mission->getStartDate();
                    $startDateObj = DateTime::createFromFormat('Y-m-d', $startDate);
                    $formattedStartDate = $startDateObj->format('d/m/Y');
                    echo $formattedStartDate;
                ?></td>
                <td><a href=""><?php echo $mission->getTitle(); ?></a></td>
                <td><?php echo $mission->getCodeName(); ?></td>
                <td>
                <?php
                    $missionStatus = MissionStatus::getMissionStatusById($pdo, $mission->getMissionStatus()->getId());
                    echo $missionStatus->getStatus();
                ?>
                </td>
                <td>
                <?php
                    $missionId = $mission->getId();
                    if(isset($_SESSION['admin'])) {
                        echo "<a href='../controller/deleteMission.php?mission=" . $missionId . "' id='deleteButton'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='1.5vw' height='1.5vw' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                                    <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z'/>
                                </svg>
                            </a>";
                    }
                ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <!-- Fermer la connexion -->
        <?php $pdo = null; ?>
    </table>
</body>
</html>
