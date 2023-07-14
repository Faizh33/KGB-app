<?php
require_once '../../classes/Agent.php';
require '../../../config/database.php';

use app\classes\Agent;

if (isset($_POST['agentId'])) {
    $agentId = $_POST['agentId'];

    // Créez une instance de la classe agent en utilisant le $pdo
    $agent = new Agent($pdo);
    // Chargez les détails de l'agent correspondant
    $agent->getAgentById($agentId);

    // Supprimez l'agent lui-même
    $agent->deleteAgentById($agentId);
}
?>
