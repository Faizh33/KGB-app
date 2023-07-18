$(document).on('click', '.deleteButton', function() {
    if (confirm('Êtes-vous sûr de vouloir supprimer ?')) {
        const form = $(this).closest('form');
        const table = $(this).closest('table');
        const url = $(this).data('url');
        const messageDiv = $(this).closest('.editForm').find('.messageDiv');

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.status === 'used') {
                    messageDiv.html('Suppression impossible : cet élément est utilisé ailleurs');
                } else if (response.status === 'success') {
                    messageDiv.html('Suppression réussie');
                    // Supprimer le formulaire et la div de message associée
                    table.closest('.editTable').remove();
                    
                    setTimeout(function() {
                        messageDiv.hide();
                    }, 3000);
                } else {
                    messageDiv.html('Une erreur s\'est produite');
                }
            },
            error: function() {
                messageDiv.html('Suppression échouée');
            }
        });
    }
});