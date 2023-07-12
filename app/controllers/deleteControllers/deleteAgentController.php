<?php
require_once '../../classes/Agent.php';
require '../../../config/database.php';

use app\classes\Agent;

if (isset($_POST['agentId']) && isset($_POST['specialities'])) {
    $agentId = $_POST['agentId'];
    $specialities = $_POST['specialities'];

    // Créez une instance de la classe Agent en utilisant le $pdo
    $agent = new Agent($pdo);
    // Chargez les détails de l'agent correspondant
    $agent->getAgentById($agentId);
    // Supprimez l'agent
    $agent->deleteAgentById($agentId, $specialityId);
}
?>