    document.addEventListener("DOMContentLoaded", function() {
        var buttons = document.getElementsByClassName("dashboardButton");
        var dashboardContainer = document.querySelector(".dashboardContainer");

        for (var i = 0; i < buttons.length; i++) {
            var button = buttons[i];
            button.addEventListener("click", function(event) {
                var formId = event.target.getAttribute("data-form");
                var forms = document.getElementsByClassName("formContainer");

                for (var j = 0; j < forms.length; j++) {
                    forms[j].style.display = "none";
                }

                var form = document.getElementById(formId);
                if (form) {
                    form.style.display = "block";
                }

                dashboardContainer.style.display = "none";
            });
        }
    });