document.addEventListener("DOMContentLoaded", function() {
    //Sélection des différents éléments
    const buttons = document.getElementsByClassName("dashboardButton");
    const dashboardContainer = document.querySelector(".dashboardContainer");
    const backButtonContainer = document.querySelector(".backButtonContainer");

    // Boucle sur les boutons du tableau de bord, ajoute un gestionnaire de clic et récupère l'ID du formulaire
    for (let i = 0; i < buttons.length; i++) {
        let button = buttons[i];
        button.addEventListener("click", function(event) {
            let formId = event.target.getAttribute("data-form");
            let tables = document.getElementsByClassName("tableContainer");

            // Masque tous les formulaires en définissant leur style d'affichage sur "none"
            for (let j = 0; j < tables.length; j++) {
                tables[j].style.display = "none";
            }

            let form = document.getElementById(formId);

            // Affiche le formulaire
            if (form) {
                form.style.display = "block";
            }

            // Masque le container du tableau de bord et affiche le container du bouton de retour
            dashboardContainer.style.display = "none";
            backButtonContainer.style.display = "flex";
        });
    }

    let backButton = document.querySelector(".backButton");

    //Sélectionne les formulaires et les masque
    backButton.addEventListener("click", function() {
        let forms = document.getElementsByClassName("tableContainer");
        for (let j = 0; j < forms.length; j++) {
            forms[j].style.display = "none";
        }

        // Affiche le container du tableau de bord et masque le container du bouton de retour
        dashboardContainer.style.display = "block";
        backButtonContainer.style.display = "none";
    });
});