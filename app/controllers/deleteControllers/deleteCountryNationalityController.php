<?php
require_once '../../classes/CountryNationality.php';
require '../../../config/database.php';

use app\classes\CountryNationality;

if (isset($_POST['countryNationalityId'])) {
    $countryNationalityId = $_POST['countryNationalityId'];

    // Créez une instance de la classe CountryNationality en utilisant le $pdo
    $countryNationality = new CountryNationality($pdo);
    // Chargez les détails de l'association pays/nationalité correspondante
    $countryNationality->getCountryNationalityById($countryNationalityId);
    // Supprimez l'association pays/nationalité
    $countryNationality->deleteCountryNationalityById($countryNationalityId);
}
?>