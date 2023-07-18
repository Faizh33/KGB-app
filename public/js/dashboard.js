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