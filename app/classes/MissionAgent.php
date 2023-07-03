<?php

namespace app\classes;

class MissionAgent
{
    private string $missionId;
    private string $agentId;
    private \PDO $pdo;

    private static array $missionAgents = [];

    public function __construct(\PDO $pdo, string $missionId, string $agentId)
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

    public function getAllMissionAgents(): array
    {
        $query = "SELECT mission_id, agent_id FROM Missions_agents";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $missionAgentsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionAgents = [];

        foreach ($missionAgentsData as $missionAgentData) {
            $missionId = $missionAgentData['mission_id'];
            $agentId = $missionAgentData['agent_id'];

            $existingMissionAgent = $this->findExistingMissionAgent($missionId, $agentId);
            if ($existingMissionAgent) {
                $missionAgents[] = $existingMissionAgent;
            } else {
                $missionAgent = new MissionAgent($this->pdo, $missionId, $agentId);
                $missionAgents[] = $missionAgent;
            }
        }

        return $missionAgents;
    }

    public function getAgentsByMissionId(string $missionId): array
    {
        if (isset(self::$missionAgents[$missionId])) {
            return self::$missionAgents[$missionId];
        }

        $query = "SELECT * FROM Missions_agents WHERE mission_id = :missionId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':missionId', $missionId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionAgents = [];

        foreach ($rows as $row) {
            $missionAgent = new MissionAgent($this->pdo, $row['mission_id'], $row['agent_id']);
            $missionAgents[] = $missionAgent;
        }

        self::$missionAgents[$missionId] = $missionAgents;

        return $missionAgents;
    }

    public function getMissionsByAgentId(string $agentId): array
    {
        if (isset(self::$missionAgents[$agentId])) {
            return self::$missionAgents[$agentId];
        }

        $query = "SELECT * FROM Missions_agents WHERE agent_id = :agentId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionAgents = [];

        foreach ($rows as $row) {
            $missionAgent = new MissionAgent($this->pdo, $row['mission_id'], $row['agent_id']);
            $missionAgents[] = $missionAgent;
        }

        self::$missionAgents[$agentId] = $missionAgents;

        return $missionAgents;
    }

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

    public function updateAgentProperties(array $propertiesToUpdate): bool
    {
        $agentId = $this->getAgentId();

        $updatedMissionAgents = [];

        foreach (self::$missionAgents[$agentId] as $missionAgent) {
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
        $stmt->bindParam(':missionId', $this->missionId);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->execute();

        self::$missionAgents[$agentId] = $updatedMissionAgents;

        return true;
    }

    public function deleteAgentsByMissionId(string $missionId): bool
    {
        $query = "DELETE FROM Missions_agents WHERE mission_id = :missionId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->execute();

        unset(self::$missionAgents[$missionId]);

        return true;
    }

    public function deleteMissionsByAgentId(string $agentId): bool
    {
        $query = "DELETE FROM Missions_agents WHERE agent_id = :agentId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':agentId', $agentId);
        $stmt->execute();

        unset(self::$missionAgents[$agentId]);

        return true;
    }

    public function getMissionId(): string
    {
        return $this->missionId;
    }

    public function getAgentId(): string
    {
        return $this->agentId;
    }
}
