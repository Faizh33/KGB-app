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
    <h1>Tableau de bord Administrateur</h1>

    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) :?>
    <div class="dashboardContainer">
        <!-- Contenu du tableau de bord -->
        <table>
            <tr>
                <td>
                    <button class="dashboardButton" data-form="missionForm">
                        Nouvelle mission
                    </button>
                </td>
                <td>
                    <button class="dashboardButton" data-form="agentForm">
                        Nouvel agent
                    </button>
                </td>
            </tr>
            <tr>
                <td>
                    <button class="dashboardButton" data-form="contactForm">
                        Nouveau contact
                    </button>
                </td>
                <td>
                    <button class="dashboardButton" data-form="targetForm">
                        Nouvelle cible
                    </button>
                </td>
            </tr>
            <tr>
                <td>
                    <button class="dashboardButton" data-form="specialityForm">
                        Nouvelle spécialité
                    </button>
                </td>
                <td>
                    <button class="dashboardButton" data-form="safehouseForm">
                        Nouvelle planque
                    </button>
                </td>
            </tr>
            <tr>
                <td>
                    <button class="dashboardButton" data-form="statusForm">
                        Nouveau statut de mission
                    </button>
                </td>
                <td>
                    <button class="dashboardButton" data-form="typeForm">
                        Nouveau type de mission
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <div class="formContainer" id="missionForm" style="display:none;">
        <?php include_once "../snippets/create-form/newMissionForm.php"; ?>
    </div>
    <div class="formContainer" id="agentForm" style="display:none;">
        <?php include_once "../snippets/create-form/newAgentForm.php"; ?>
    </div>    
    <div class="formContainer" id="contactForm" style="display:none;">
        <?php include_once "../snippets/create-form/newContactForm.php"; ?>
    </div>    
    <div class="formContainer" id="targetForm" style="display:none;">
        <?php include_once "../snippets/create-form/newTargetForm.php"; ?>
    </div>
    <div class="formContainer" id="specialityForm" style="display:none;">
        <?php include_once "../snippets/create-form/newSpecialityForm.php"; ?>
    </div>
    <div class="formContainer" id="safehouseForm" style="display:none;">
        <?php include_once "../snippets/create-form/newSafeHouseForm.php"; ?>
    </div>    
    <div class="formContainer" id="statusForm" style="display:none;">
        <?php include_once "../snippets/create-form/newStatusForm.php"; ?>
    </div>    
    <div class="formContainer" id="typeForm" style="display:none;">
        <?php include_once "../snippets/create-form/newTypeForm.php"; ?>
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