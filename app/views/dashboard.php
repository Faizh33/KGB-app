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
    <?php endif; ?>

    <script src="../../public/js/dashboard.js"></script>
</body>
</html>