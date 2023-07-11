<?php

include_once "../../../config/database.php";
include_once "../../helpers/dataHelpers.php";
include_once "../../classes/Contact.php";

use app\classes\Contact;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si l'id est passé dans le POST
    if (isset($_POST["contactId"])) {
        $contactId = $_POST["contactId"];
    }

    $contactObj = new Contact($pdo);
    $contact = $contactObj::getContactById($contactId);

    // Vérifier si les valeurs POST existent et ne sont pas vides
    // Si elles sont vides, conserver les valeurs existantes
    $lastName = isset($_POST["contactLastName"]) && $_POST["contactLastName"] !== '' ? valid_datas($_POST["contactLastName"]) : $contact->getLastName();
    $firstName = isset($_POST["contactFirstName"]) && $_POST["contactFirstName"] !== '' ? valid_datas($_POST["contactFirstName"]) : $contact->getFirstName();
    $birthDateInput = isset($_POST["contactBirthDate"]) ? $_POST["contactBirthDate"] : $contact->getBirthDate();
    $birthDate = $birthDateInput !== '' ? DateTime::createFromFormat('d/m/Y', $birthDateInput)->format('Y-m-d') : $contact->getBirthDate();    
    $nationality = isset($_POST["contactNationality"]) && $_POST["contactNationality"] !== '' ? valid_datas($_POST["contactNationality"]) : $contact->getNationality();
    $codeName = isset($_POST["contactCodeName"]) && $_POST["contactCodeName"] !== '' ? valid_datas($_POST["contactCodeName"]) : $contact->getCodeName();

    $propertiesToUpdate = [
        'lastName' => $lastName,
        'firstName' => $firstName,
        'birthDate' => $birthDate,
        'nationality' => $nationality,
        'codeName' => $codeName
    ];

    //Insertion des nouvelles données
    $contact = $contactObj::updateContactProperties($contactId, $propertiesToUpdate);
    
    //Si la modification en base de données à réussi : redirection vers la page de création
    if(isset($contact)) {
        echo "<br><div style='font-weight:bold;color:rgb(3, 114, 103)'>Données modifiées en base de données</div><br>";
        echo "<div style='color:rgb(3, 114, 103);font-style:italic'>Redirection dans 3 secondes</div>";
        echo "<script>
            setTimeout(function() {
                window.location.href = '../../views/dashboardEdit.php';
            }, 3000);
        </script>";
        exit;
    } 
}
