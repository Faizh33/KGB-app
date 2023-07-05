<?php

namespace app\classes;

class MissionAgent
{
    private string $missionId;
    private string $agentId;
    private \PDO $pdo;

    private static array $missionAgents = [];

    public function __construct(\PDO $pdo, string $missionId = '', string $agentId = '')
    {
        $this->pdo = $pdo;
        $this->missionId = $missionId;
        $this->agentId = $agentId;

        if (!isset(self::$missionAgents[$missionId])) {
            self::$missionAgents[$missionId] = [];
        }

        if (!isset(self::$missionAgents[$agentId])) {
            self::$missionAgents[$agentId] = [];
        }

        $existingMissionAgent = $this->findExistingMissionAgent($missionId, $agentId);
        if ($existingMissionAgent) {
            return $existingMissionAgent;
        }

        self::$missionAgents[$missionId][] = $this;
        self::$missionAgents[$agentId][] = $this;
    }

    /**
     * Recherche un MissionAgent existant dans la classe en fonction de l'ID de la mission et de l'ID de l'agent.
     *
     * @param string $missionId L'ID de la mission.
     * @param string $agentId L'ID de l'agent.
     *
     * @return MissionAgent|null Retourne le MissionAgent correspondant s'il existe, sinon retourne null.
     */
    private function findExistingMissionAgent(string $missionId, string $agentId): ?MissionAgent
    {
        if (isset(self::$missionAgents[$missionId])) {
            foreach (self::$missionAgents[$missionId] as $missionAgent) {
                if ($missionAgent->getMissionId() === $missionId && $missionAgent->getAgentId() === $agentId) {
                    return $missionAgent;
                }
            }
        }
        return null;
    }

    /**
     * Récupère tous les MissionAgent de la base de données et les insère dans la classe.
     *
     * @return array Un tableau contenant tous les MissionAgent.
     */
    public static function getAllMissionAgents($pdo): array
    {
        $query = "SELECT mission_id, agent_id FROM Missions_agents";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $missionAgentsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionAgents = [];

        foreach ($missionAgentsData as $missionAgentData) {
            $missionId = $missionAgentData['mission_id'];
            $agentId = $missionAgentData['agent_id'];

            $existingMissionAgent = MissionAgent::findExistingMissionAgent($missionId, $agentId);
            if ($existingMissionAgent) {
                $missionAgents[] = $existingMissionAgent;
            } else {
                $missionAgent = new MissionAgent($pdo, $missionId, $agentId);
                $missionAgents[] = $missionAgent;
            }
        }

        return $missionAgents;
    }

    /**
     * Récupère tous les agents associés à une mission donnée.
     *
     * @param string $missionId L'ID de la mission.
     * @return array Un tableau contenant tous les agents associés à la mission.
     */
    public static function getAgentsByMissionId($pdo, string $missionId): array
    {
        if (isset(self::$missionAgents[$missionId])) {
            return self::$missionAgents[$missionId];
        }

        $query = "SELECT * FROM Missions_agents WHERE mission_id = :missionId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':missionId', $missionId);
        $stmt->execute();

        $missionAgentDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionAgents = [];

        foreach ($missionAgentDatas as $missionAgentData) {
            $missionId = $missionAgentData['mission_id'];
            $agentId = $missionAgentData['agent_id'];

            $missionAgent = new MissionAgent($pdo, $missionId, $agentId);
            $missionAgents[] = $missionAgent;
        }

        self::$missionAgents[$missionId] = $missionAgents;

        return $missionAgents;
    }

    /**
     * Récupère toutes les missions associées à un agent donné.
     *
     * @param string $agentId L'ID de l'agent.
     * @return array Un tableau contenant toutes les missions associées à l'agent.
     */
    public function getMissionsByAgentId(string $agentId): array
    {
        if (isset(self::$missionAgents[$agentId])) {
            return self::$missionAgents[$agentId];
        }

        $query = "SELECT * FROM Missions_agents WHERE agent_id = :agentId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->execute();

        $missionAgentDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionAgents = [];

        foreach ($missionAgentDatas as $missionAgentData) {
            $missionId = $missionAgentData['mission_id'];
            $agentId = $missionAgentData['agent_id'];

            $missionAgent = new MissionAgent($this->pdo, $missionId, $agentId);
            $missionAgents[] = $missionAgent;
        }

        self::$missionAgents[$agentId] = $missionAgents;

        return $missionAgents;
    }

    /**
     * Ajoute un agent à une mission.
     *
     * @param string $missionId L'ID de la mission.
     * @param string $agentId L'ID de l'agent.
     * @return MissionAgent|null L'objet MissionAgent créé si l'ajout est réussi, sinon null.
     */
    public function addAgentToMission(string $missionId, string $agentId): ?MissionAgent
    {
        $existingMissionAgent = $this->findExistingMissionAgent($missionId, $agentId);
        if ($existingMissionAgent) {
            return $existingMissionAgent;
        }

        $query = "INSERT INTO Missions_agents (mission_id, agent_id) VALUES (:missionId, :agentId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':missionId', $missionId);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->execute();

        $newMissionAgent = new MissionAgent($this->pdo, $missionId, $agentId);
        self::$missionAgents[$missionId][] = $newMissionAgent;
        self::$missionAgents[$agentId][] = $newMissionAgent;

        return $newMissionAgent;
    }

    /**
     * Met à jour les propriétés d'un agent dans une mission spécifiée.
     *
     * @param string $missionId L'ID de la mission.
     * @param array $propertiesToUpdate Les propriétés à mettre à jour.
     * @return bool Indique si la mise à jour a réussi.
     */
    public function updateAgentProperties(string $missionId, array $propertiesToUpdate): bool
    {
        $agentId = $this->getAgentId();

        $updatedMissionAgents = [];

        foreach (self::$missionAgents[$missionId] as $missionAgent) {
            foreach ($propertiesToUpdate as $property => $value) {
                if ($missionAgent->$property !== $value) {
                    $missionAgent->$property = $value;
                }
            }
            $updatedMissionAgents[] = $missionAgent;
        }

        $query = "UPDATE Missions_agents SET agent_id = :newAgentId WHERE mission_id = :missionId AND agent_id = :agentId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':newAgentId', $this->agentId);
        $stmt->bindParam(':missionId', $missionId);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->execute();

        self::$missionAgents[$missionId] = $updatedMissionAgents;

        return true;
    }

    /**
     * Supprime tous les agents d'une mission spécifiée.
     *
     * @param string $missionId L'ID de la mission.
     * @return bool Indique si la suppression a réussi.
     */
    public function deleteAgentsByMissionId(string $missionId): bool
    {
        $query = "DELETE FROM Missions_agents WHERE mission_id = :missionId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->execute();

        unset(self::$missionAgents[$missionId]);

        return true;
    }

    /**
     * Supprime toutes les missions d'un agent spécifié.
     *
     * @param string $agentId L'ID de l'agent.
     * @return bool Indique si la suppression a réussi.
     */
    public function deleteMissionsByAgentId(string $agentId): bool
    {
        $query = "DELETE FROM Missions_agents WHERE agent_id = :agentId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':agentId', $agentId);
        $stmt->execute();

        unset(self::$missionAgents[$agentId]);

        return true;
    }

    //Getters
    public function getMissionId(): string
    {
        return $this->missionId;
    }

    public function getAgentId(): string
    {
        return $this->agentId;
    }
}
