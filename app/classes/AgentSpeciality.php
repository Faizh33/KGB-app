<?php

namespace app\classes;

class AgentSpeciality
{
    private string $agentId;
    private int $specialityId;
    private \PDO $pdo;

    private static array $agentSpecialities = [];

    public function __construct(\PDO $pdo, string $agentId, int $specialityId)
    {
        $this->pdo = $pdo;
        $this->agentId = $agentId;
        $this->specialityId = $specialityId;

        if (!isset(self::$agentSpecialities[$agentId])) {
            self::$agentSpecialities[$agentId] = [];
        }

        $existingAgentSpeciality = $this->findExistingAgentSpeciality($agentId, $specialityId);
        if ($existingAgentSpeciality) {
            return $existingAgentSpeciality;
        }

        self::$agentSpecialities[$agentId][] = $this;
    }

    private function findExistingAgentSpeciality(string $agentId, int $specialityId): ?AgentSpeciality
    {
        if (isset(self::$agentSpecialities[$agentId])) {
            foreach (self::$agentSpecialities[$agentId] as $agentSpeciality) {
                if ($agentSpeciality->getSpecialityId() === $specialityId) {
                    return $agentSpeciality;
                }
            }
        }
        return null;
    }

    public function getAgentSpecialitiesByAgentId(string $agentId): array
    {
        if (isset(self::$agentSpecialities[$agentId])) {
            return self::$agentSpecialities[$agentId];
        }

        $query = "SELECT speciality_id FROM Agents_Specialities WHERE agent_id = :agentId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $agentSpecialities = [];

        foreach ($rows as $row) {
            $specialityId = $row['speciality_id'];

            $existingAgentSpeciality = $this->findExistingAgentSpeciality($agentId, $specialityId);
            if ($existingAgentSpeciality) {
                $agentSpecialities[] = $existingAgentSpeciality;
            } else {
                $agentSpeciality = new AgentSpeciality($this->pdo, $agentId, $specialityId);
                $agentSpecialities[] = $agentSpeciality;
            }
        }

        return $agentSpecialities;
    }

    public function getAgentsBySpecialityId(int $specialityId): array
    {
        $query = "SELECT agent_id FROM Agents_Specialities WHERE speciality_id = :specialityId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':specialityId', $specialityId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $agents = [];

        foreach ($rows as $row) {
            $agentId = $row['agent_id'];

            $existingAgentSpeciality = $this->findExistingAgentSpeciality($agentId, $specialityId);
            if ($existingAgentSpeciality) {
                $agents[] = $existingAgentSpeciality;
            } else {
                $agentSpeciality = new AgentSpeciality($this->pdo, $agentId, $specialityId);
                $agents[] = $agentSpeciality;
            }
        }

        return $agents;
    }

    public function addSpecialityToAgent(string $agentId, int $specialityId): ?AgentSpeciality
    {
        $existingAgentSpeciality = $this->findExistingAgentSpeciality($agentId, $specialityId);
        if ($existingAgentSpeciality) {
            return $existingAgentSpeciality;
        }

        $query = "INSERT INTO Agents_Specialities (agent_id, speciality_id) VALUES (:agentId, :specialityId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->bindParam(':specialityId', $specialityId);
        $stmt->execute();

        $newAgentSpeciality = new AgentSpeciality($this->pdo, $agentId, $specialityId);
        self::$agentSpecialities[$agentId][] = $newAgentSpeciality;

        return $newAgentSpeciality;
    }

    public function updateProperties(array $propertiesToUpdate): bool
    {
        $query = "UPDATE Agents_Specialities SET ";

        foreach ($propertiesToUpdate as $property => $value) {
            if ($this->$property !== $value) {
                $this->$property = $value;
                $query .= "$property = :$property, ";
            }
        }

        $query = rtrim($query, ', ');
        $query .= " WHERE agent_id = :agentId AND speciality_id = :specialityId";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':agentId', $this->agentId);
        $stmt->bindValue(':specialityId', $this->specialityId);

        foreach ($propertiesToUpdate as $property => $value) {
            $stmt->bindValue(":$property", $value);
        }

        return $stmt->execute();
    }

    public function deleteSpecialitiesByAgentId(string $agentId): bool
    {
        $query = "DELETE FROM Agents_Specialities WHERE agent_id = :agentId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':agentId', $agentId);
        $stmt->execute();

        unset(self::$agentSpecialities[$agentId]);

        return true;
    }

    public function deleteAgentsBySpecialityId(int $specialityId): bool
    {
        $query = "DELETE FROM Agents_Specialities WHERE speciality_id = :specialityId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':specialityId', $specialityId);
        $stmt->execute();

        foreach (self::$agentSpecialities as $agentId => $agentSpecialities) {
            foreach ($agentSpecialities as $key => $agentSpeciality) {
                if ($agentSpeciality->getSpecialityId() === $specialityId) {
                    unset(self::$agentSpecialities[$agentId][$key]);
                }
            }
        }

        return true;
    }

    public function getAgentId(): string
    {
        return $this->agentId;
    }

    public function getSpecialityId(): int
    {
        return $this->specialityId;
    }
}
