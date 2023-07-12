<?php
require_once '../../classes/Contact.php';
require '../../../config/database.php';

use app\classes\Contact;

if (isset($_POST['contactId'])) {
    $contactId = $_POST['contactId'];

    // Créez une instance de la classe contact en utilisant le $pdo
    $contact = new Contact($pdo);
    // Chargez les détails de l'contact correspondant
    $contact->getContactById($contactId);
    // Supprimez la contact
    $contact->deleteContactById($contactId);
}
?>
