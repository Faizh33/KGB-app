// Récupérer le formulaire et les checkboxes
var missionForm = document.getElementById('newMissionForm');
var checkboxesAgent = missionForm.getElementsByClassName('agent-checkbox');
var checkboxesContact = missionForm.getElementsByClassName('contact-checkbox');
var checkboxesTarget = missionForm.getElementsByClassName('target-checkbox');

// Ajouter un événement de soumission du formulaire
missionForm.addEventListener('submit', function(event) {
  var isCheckedAgent = false;
  var isCheckedContact = false;
  var isCheckedTarget = false;

  // Vérifier si au moins une checkbox est cochée pour les agents
  for (var i = 0; i < checkboxesAgent.length; i++) {
    if (checkboxesAgent[i].checked) {
      isCheckedAgent = true;
      break;
    }
  }

  // Vérifier si au moins une checkbox est cochée pour les contacts
  for (var i = 0; i < checkboxesContact.length; i++) {
    if (checkboxesContact[i].checked) {
      isCheckedContact = true;
      break;
    }
  }

  // Vérifier si au moins une checkbox est cochée pour les cibles
  for (var i = 0; i < checkboxesTarget.length; i++) {
    if (checkboxesTarget[i].checked) {
      isCheckedTarget = true;
      break;
    }
  }

  // Si aucune checkbox n'est cochée pour les agents, empêcher la soumission du formulaire
  if (!isCheckedAgent) {
    event.preventDefault();
    alert('Veuillez sélectionner au moins un agent.');
  }

  // Si aucune checkbox n'est cochée pour les contacts, empêcher la soumission du formulaire
  if (!isCheckedContact) {
    event.preventDefault();
    alert('Veuillez sélectionner au moins un contact.');
  }

  // Si aucune checkbox n'est cochée pour les cibles, empêcher la soumission du formulaire
  if (!isCheckedTarget) {
    event.preventDefault();
    alert('Veuillez sélectionner au moins une cible.');
  }
});
