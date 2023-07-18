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
    <div class="dashboardContainer dashboardEdit">
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
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editContactTable.php" class="dashboardButtonLink">
                            Contacts
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editTargetTable.php" class="dashboardButtonLink">
                            Cibles
                        </a>
                    </div>
                </td>
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
                <td>
                    <div class="dashboardButton">    
                        <a href="edit-table/editCountryNationalityTable.php" class="dashboardButtonLink">
                            Pays / Nationalités
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <?php endif; ?>
</body>
</html>