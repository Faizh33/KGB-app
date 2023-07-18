<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="../../public/css/dashboard.css">
    <title>Tableau de bord</title>
</head>
<body>
    <header id="dashboardHeader">
        <!-- Bouton de retour à l'accueil -->
        <div id="homeBtn">  
            <a href="../views/home.php" id="homeBtnLink">Accueil</a>
        </div>  
    </header>

    <h1>Tableau de bord Administrateur</h1>

    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) :?>
    <div class="dashboardContainer">
        <!-- Contenu du tableau de bord -->
        <table class="dashboardTable">
            <tr>
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editMissionTable.php" class="dashboardButtonLink">
                            Missions
                        </a>
                    </div>
                </td>
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editAgentTable.php" class="dashboardButtonLink">
                            Agents
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editContactTable.php" class="dashboardButtonLink">
                            Contacts
                        </a>
                    </div>
                </td>
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editTargetTable.php" class="dashboardButtonLink">
                            Cibles
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editSpecialityTable.php" class="dashboardButtonLink">
                            Spécialités
                        </a>
                    </div>
                </td>
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editSafeHouseTable.php" class="dashboardButtonLink">
                            Planques
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editStatusTable.php" class="dashboardButtonLink">
                            Statuts de mission
                        </a>
                    </div>
                </td>
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editTypeTable.php" class="dashboardButtonLink">
                            Types de mission
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Bouton de retour arrière -->
    <div class="backButtonContainer" style="display:none;">
        <button class="backButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="1.5vw" height="1.5vw" fill="currentColor" color="white" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
            </svg>
            <span class="backText">Retour</span>
        </button>
    </div>
    <?php endif; ?>
    <script src="../../public/js/dashboard.js"></script>
</body>
</html>