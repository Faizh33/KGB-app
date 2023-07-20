<?php 
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>
    <div class="dashboardContainer">
        <!-- Contenu du tableau de bord -->
        <table class="dashboardTable dashboardEdit">
            <tr>
                <td>
                    <button class="dashboardButton datasButton dashboardButtonCreate" data-form="missionForm">
                        Nouvelle mission
                    </button>
                
                </td>
                <td>
                    <button class="dashboardButton datasButton dashboardButtonCreate" data-form="agentForm">
                        Nouvel agent
                    </button>
                
                </td>
                <td>
                    <button class="dashboardButton datasButton dashboardButtonCreate" data-form="contactForm">
                        Nouveau contact
                    </button>
                
                </td>
            </tr>
            <tr>
                <td>
                    <button class="dashboardButton datasButton dashboardButtonCreate" data-form="targetForm">
                        Nouvelle cible
                    </button>
                
                </td>
                <td>
                    <button class="dashboardButton datasButton dashboardButtonCreate" data-form="specialityForm">
                        Nouvelle spécialité
                    </button>
                
                </td>
                <td>
                    <button class="dashboardButton datasButton dashboardButtonCreate" data-form="safehouseForm">
                        Nouvelle planque
                    </button>
                
                </td>
            </tr>
            <tr>
                <td>
                    <button class="dashboardButton datasButton dashboardButtonCreate" data-form="statusForm">
                        Nouveau statut de mission
                    </button>
                
                </td>
                <td>
                    <button class="dashboardButton datasButton dashboardButtonCreate" data-form="typeForm">
                        Nouveau type de mission
                    </button>
                
                </td>
                <td>
                    <button class="dashboardButton datasButton dashboardButtonCreate" data-form="countryNationalityForm">
                        Nouveau pays/nationalité
                    </button>
                
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="dashBoardButton createButton dashboardButtonEdit" style="text-align: center;">
                        <a href="dashboardEdit.php" class="dashboardButtonLink createButtonLink">
                            Retour au tableau de bord
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="tableContainer" id="missionForm" style="display:none;">
        <?php include_once "../snippets/create-form/newMissionForm.php"; ?>
    </div>
    <div class="tableContainer" id="agentForm" style="display:none;">
        <?php include_once "../snippets/create-form/newAgentForm.php"; ?>
    </div>    
    <div class="tableContainer" id="contactForm" style="display:none;">
        <?php include_once "../snippets/create-form/newContactForm.php"; ?>
    </div>    
    <div class="tableContainer" id="targetForm" style="display:none;">
        <?php include_once "../snippets/create-form/newTargetForm.php"; ?>
    </div>
    <div class="tableContainer" id="specialityForm" style="display:none;">
        <?php include_once "../snippets/create-form/newSpecialityForm.php"; ?>
    </div>
    <div class="tableContainer" id="safehouseForm" style="display:none;">
        <?php include_once "../snippets/create-form/newSafeHouseForm.php"; ?>
    </div>    
    <div class="tableContainer" id="statusForm" style="display:none;">
        <?php include_once "../snippets/create-form/newStatusForm.php"; ?>
    </div>    
    <div class="tableContainer" id="typeForm" style="display:none;">
        <?php include_once "../snippets/create-form/newTypeForm.php"; ?>
    </div>
    <div class="tableContainer" id="countryNationalityForm" style="display:none;">
        <?php include_once "../snippets/create-form/newCountryNationalityForm.php"; ?>
    </div>
    <!-- Bouton de retour arrière -->
    <div class="backButtonContainer" style="display:none;">
        <a href="dashboardCreate.php" class="backButton">
            Retour
        </a>
    </div>
    <?php endif; ?>

    <script src="../../dist/dashboard.bundle.js"></script>
</body>
</html>