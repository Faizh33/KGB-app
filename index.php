<?php
// Capture l'URL demandée
$request = $_SERVER['REQUEST_URI'];

if ($request === '/') {
    include('app/views/home.php');
} elseif ($request === '/mission-details') {
    include('app/views/missionDetails.php');
} elseif ($request === '/login') {
    include('app/views/loginForm.php');
} elseif ($request === '/dashboard-edit') {
    include('app/views/dashboardEdit.php');
} elseif ($request === '/dashboard-edit/agent') {
    include('app/views/edit-table/editAgentTable.php');
} elseif ($request === '/dashboard-edit/contact') {
    include('app/views/edit-table/editContactTable.php');
} elseif ($request === '/dashboard-edit/country-nationality') {
    include('app/views/edit-table/editCountryNationalityTable.php');
} elseif ($request === '/dashboard-edit/mission') {
    include('app/views/edit-table/editMissionTable.php');
} elseif ($request === '/dashboard-edit/safehouse') {
    include('app/views/edit-table/editSafeHouseTable.php');
} elseif ($request === '/dashboard-edit/speciality') {
    include('app/views/edit-table/editSpecialityTable.php');
} elseif ($request === '/dashboard-edit/status') {
    include('app/views/edit-table/editStatusTable.php');
} elseif ($request === '/dashboard-edit/target') {
    include('app/views/edit-table/editTargetTable.php');
} elseif ($request === '/dashboard-edit/type') {
    include('app/views/edit-table/editTypeTable.php');
} elseif ($request === '/dashboardCreate') {
    include('app/views/dashboardCreate.php');
} else {
    // Gérer les pages d'erreur ou autres actions
    header("HTTP/1.0 404 Not Found");
    echo '404 - Page Not Found';
}
