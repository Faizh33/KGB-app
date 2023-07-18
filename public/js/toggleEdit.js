// Fonction permettant d'afficher les inputs des tableaux
function toggleEdit(button) {
    const row = button.parentNode.parentNode.parentNode.parentNode;
    const spans = row.getElementsByTagName('span');
    const inputs = row.getElementsByTagName('input');
    const textareas = row.getElementsByTagName('textarea');
    const selects = row.getElementsByTagName('select');
    const chkDivs = row.getElementsByClassName('chk');
    const checkboxes = row.getElementsByClassName('editChk');
    const labels = row.getElementsByClassName('labelChk');

    // Masquer tous les spans et afficher tous les inputs, textareas, selects et checkboxes
    for (let i = 0; i < spans.length; i++) {
        spans[i].style.display = 'none';
    }

    for (let j = 0; j < inputs.length; j++) {
        inputs[j].style.display = 'block';
    }

    for (let k = 0; k < textareas.length; k++) {
        textareas[k].style.display = 'block';
    }

    for (let l = 0; l < selects.length; l++) {
        selects[l].style.display = 'block';
    }

    for (let m = 0; m < chkDivs.length; m++) {
        chkDivs[m].style.display = 'block';
    }

    for (let n = 0; n < checkboxes.length; n++) {
        checkboxes[n].style.display = 'inline-block';
    }

    for (let o = 0; o < labels.length; o++) {
        labels[o].style.display = 'inline-block';
    }

    // Masquer le bouton "Modifier" et afficher le bouton "Sauvegarder"
    button.style.display = 'none';
    button.parentNode.querySelector('.saveButton').style.display = 'block';

    // Masquer le bouton "Supprimer"
    button.parentNode.querySelector('.deleteButton').style.display = 'none';
}
