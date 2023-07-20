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
            <a href="/" id="homeBtnLink">Accueil</a>
        </div>  
    </header>

    <h1>Tableau de bord Administrateur</h1>

    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) :?>
    <div class="dashboardContainer dashboardEdit">
        <!-- Contenu du tableau de bord -->
        <table class="dashboardTable">
            <tr>
                <td>
                    <div class="dashboardButton datasButton dashboardButtonEdit">    
                        <a href="/dashboard-edit/mission" class="dashboardButtonLink datasButtonLink">
                            Missions
                        </a>
                    </div>
                </td>
                <td>
                    <div class="dashboardButton datasButton dashboardButtonEdit">    
                        <a href="/dashboard-edit/agent" class="dashboardButtonLink datasButtonLink">
                            Agents
                        </a>
                    </div>
                </td>
                <td>
                    <div class="dashboardButton datasButton dashboardButtonEdit">    
                        <a href="/dashboard-edit/contact" class="dashboardButtonLink datasButtonLink">
                            Contacts
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="dashboardButton datasButton dashboardButtonEdit">    
                        <a href="/dashboard-edit/target" class="dashboardButtonLink datasButtonLink">
                            Cibles
                        </a>
                    </div>
                </td>
                <td>
                    <div class="dashboardButton datasButton dashboardButtonEdit">    
                        <a href="/dashboard-edit/speciality" class="dashboardButtonLink datasButtonLink">
                            Spécialités
                        </a>
                    </div>
                </td>
                <td>
                    <div class="dashboardButton datasButton dashboardButtonEdit">    
                        <a href="/dashboard-edit/safehouse" class="dashboardButtonLink datasButtonLink">
                            Planques
                        </a>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="dashboardButton datasButton dashboardButtonEdit">    
                        <a href="/dashboard-edit/status" class="dashboardButtonLink datasButtonLink">
                            Statuts de mission
                        </a>
                    </div>
                </td>
                <td>
                    <div class="dashboardButton datasButton dashboardButtonEdit">    
                        <a href="/dashboard-edit/type" class="dashboardButtonLink datasButtonLink">
                            Types de mission
                        </a>
                    </div>
                </td>
                <td>
                    <div class="dashboardButton datasButton dashboardButtonEdit">    
                        <a href="/dashboard-edit/country-nationality" class="dashboardButtonLink datasButtonLink">
                            Pays / Nationalités
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="dashBoardButton createButton dashboardButtonEdit">
        <a href="/dashboard-create" class="dashboardButtonLink createButtonLink">
            Créer
        </a>
    </div>
    <?php endif; ?>
</body>
</html>