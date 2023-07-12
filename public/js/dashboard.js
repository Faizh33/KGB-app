document.addEventListener("DOMContentLoaded", function() {
    //Sélection des différents éléments
    var buttons = document.getElementsByClassName("dashboardButton");
    var dashboardContainer = document.querySelector(".dashboardContainer");
    var backButtonContainer = document.querySelector(".backButtonContainer");

    // Boucle sur les boutons du tableau de bord, ajoute un gestionnaire de clic et récupère l'ID du formulaire
    for (var i = 0; i < buttons.length; i++) {
        var button = buttons[i];
        button.addEventListener("click", function(event) {
            var formId = event.target.getAttribute("data-form");
            var tables = document.getElementsByClassName("tableContainer");

            // Masque tous les formulaires en définissant leur style d'affichage sur "none"
            for (var j = 0; j < tables.length; j++) {
                tables[j].style.display = "none";
            }

            var form = document.getElementById(formId);

            // Affiche le formulaire
            if (form) {
                form.style.display = "block";
            }

            // Masque le container du tableau de bord et affiche le container du bouton de retour
            dashboardContainer.style.display = "none";
            backButtonContainer.style.display = "block";
        });
    }

    var backButton = document.querySelector(".backButton");

    //Sélectionne les formulaires et les masque
    backButton.addEventListener("click", function() {
        var forms = document.getElementsByClassName("tableContainer");
        for (var j = 0; j < forms.length; j++) {
            forms[j].style.display = "none";
        }

        // Affiche le container du tableau de bord et masque le container du bouton de retour
        dashboardContainer.style.display = "block";
        backButtonContainer.style.display = "none";
    });
});

// Fonction permettant d'afficher les inputs des tableaux
function toggleEdit(button) {
    var row = button.parentNode.parentNode.parentNode.parentNode;
    var spans = row.getElementsByTagName('span');
    var inputs = row.getElementsByTagName('input');
    var textareas = row.getElementsByTagName('textarea');
    var selects = row.getElementsByTagName('select');
    var chkDivs = row.getElementsByClassName('chk');
    var checkboxes = row.getElementsByClassName('editChk');
    var labels = row.getElementsByClassName('labelChk');

    // Masquer tous les spans et afficher tous les inputs, textareas, selects et checkboxes
    for (var i = 0; i < spans.length; i++) {
        spans[i].style.display = 'none';
    }

    for (var j = 0; j < inputs.length; j++) {
        inputs[j].style.display = 'block';
    }

    for (var k = 0; k < textareas.length; k++) {
        textareas[k].style.display = 'block';
    }

    for (var l = 0; l < selects.length; l++) {
        selects[l].style.display = 'block';
    }

    for (var m = 0; m < chkDivs.length; m++) {
        chkDivs[m].style.display = 'block';
    }

    for (var n = 0; n < checkboxes.length; n++) {
        checkboxes[n].style.display = 'inline-block';
    }

    for (var o = 0; o < labels.length; o++) {
        labels[o].style.display = 'inline-block';
    }

    // Masquer le bouton "Modifier" et afficher le bouton "Sauvegarder"
    button.style.display = 'none';
    button.parentNode.querySelector('.saveButton').style.display = 'block';

    // Masquer le bouton "Supprimer"
    button.parentNode.querySelector('.deleteButton').style.display = 'none';
}
