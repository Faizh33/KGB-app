<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once "../../config/database.php";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs soumises depuis le formulaire
$countryId = $_POST['country'] ?? '';
$startDate = $_POST['startDate'] ?? '';
$missionStatusId = $_POST['missionStatus'] ?? '';
$missionTypeId = $_POST['missionType'] ?? '';

// Construction de la requête SQL en fonction des critères de filtrage
$query = "SELECT * FROM missions WHERE 1=1";

if (!empty($countryId)) {
    $query .= " AND countrynationality_id = :countryId";
}

if (!empty($startDate)) {
    $query .= " AND startDate >= :startDate";
}

if (!empty($missionStatusId)) {
    $query .= " AND missionstatuses_id = :missionStatusId";
}

if (!empty($missionTypeId)) {
    $query .= " AND missiontype_id = :missionTypeId";
}

// Préparer la requête
$stmt = $pdo->prepare($query);

// Lier les valeurs des paramètres si nécessaire
if (!empty($countryId)) {
    $stmt->bindValue(':countryId', $countryId, PDO::PARAM_INT);
}

if (!empty($startDate)) {
    $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
}

if (!empty($missionStatusId)) {
    $stmt->bindValue(':missionStatusId', $missionStatusId, PDO::PARAM_INT);
}

if (!empty($missionTypeId)) {
    $stmt->bindValue(':missionTypeId', $missionTypeId, PDO::PARAM_INT);
}

$stmt->execute();

// Récupérer les missions filtrées sous forme d'un tableau d'objets
$filteredMissions = $stmt->fetchAll(PDO::FETCH_OBJ);

// Stocker les missions filtrées dans une variable de session
$_SESSION['filteredMissions'] = $filteredMissions;

// Rediriger vers la page d'accueil avec les résultats de filtrage
header('Location: ../views/home.php');
exit();
}
?>