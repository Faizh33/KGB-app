<?php

namespace app\classes;

class AgentSpeciality
{
    private string $agentId;
    private int $specialityId;
    private $pdo;

    private static array $agentSpecialities = [];

    public function __construct($pdo, string $agentId, int $specialityId)
    {
        $this->pdo = $pdo;
        $this->agentId = $agentId;
        $this->specialityId = $specialityId;

        self::$agentSpecialities[$agentId] = $this;
        self::$agentSpecialities[$specialityId] = $this;
    }

    public function getSpecialitiesByAgentId(string $agentId): ?array
    {
        if (isset(self::$agentSpecialities[$agentId])) {
            return self::$agentSpecialities[$agentId];
        }

        $query = "SELECT * FROM Agents_Specialities WHERE agent_id = :agentId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $agentSpecialities = [];

        foreach ($rows as $row) {
            $agentSpeciality = new AgentSpeciality($this->pdo, $row['agent_id'], $row['speciality_id']);
            $agentSpecialities[] = $agentSpeciality;
        }

        if (!empty($agentSpecialities)) {
            self::$agentSpecialities[$agentId] = $agentSpecialities;
            return $agentSpecialities;
        }

        return null;
    }

    public function getAgentsBySpeciality(int $specialityId): ?array
    {
        if (isset(self::$agentSpecialities[$specialityId])) {
            return self::$agentSpecialities[$specialityId];
        }

        $query = "SELECT * FROM Agents_Specialities WHERE speciality_id = :specialityId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':specialityId', $specialityId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $agentSpecialities = [];

        foreach ($rows as $row) {
            $agentSpeciality = new AgentSpeciality($this->pdo, $row['agent_id'], $row['speciality_id']);
            $agentSpecialities[] = $agentSpeciality;
        }

        if (!empty($agentSpecialities)) {
            self::$agentSpecialities[$specialityId] = $agentSpecialities;
            return $agentSpecialities;
        }

        return null;
    }

    // Ajouter une nouvelle association agent/spécialité dans la base de données et dans la classe
    public function addAgentSpeciality(string $agentId, int $specialityId): ?AgentSpeciality
    {
        // Vérifier si l'association existe déjà dans la base de données
        $query = "SELECT * FROM Agents_Specialities WHERE agent_id = :agentId AND speciality_id = :specialityId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->bindParam(':specialityId', $specialityId);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return null;
        }

        // Insérer la nouvelle association agent/spécialité dans la base de données et dans la classe
        $query = "INSERT INTO Agents_Specialities (agent_id, speciality_id) VALUES (:agentId, :specialityId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->bindParam(':specialityId', $specialityId);
        $stmt->execute();

        $newAgentSpeciality = new AgentSpeciality($this->pdo, $agentId, $specialityId);

        self::$agentSpecialities[$agentId] = $newAgentSpeciality;
        self::$agentSpecialities[$specialityId] = $newAgentSpeciality;

        return $newAgentSpeciality;
    }

    // Méthode qui met à jour les propriétés de l'association agent/spécialité dans la base de données et dans la classe
    public function updateProperties(array $propertiesToUpdate): bool
    {
        $id = [$this->getAgentId(), $this->getSpecialityId()];

        foreach ($propertiesToUpdate as $property => $value) {
            if ($this->$property !== $value) {
                $this->$property = $value;
            }
        }

        $query = "UPDATE Agents_Specialities SET agent_id = :newAgentId, speciality_id = :newSpecialityId WHERE agent_id = :agentId AND speciality_id = :specialityId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':newAgentId', $this->agentId);
        $stmt->bindParam(':newSpecialityId', $this->specialityId);
        $stmt->bindParam(':agentId', $id[0]);
        $stmt->bindParam(':specialityId', $id[1]);
        $stmt->execute();

        // Mettre à jour le tableau $agentSpecialities
        self::$agentSpecialities[$this->agentId] = $this;
        self::$agentSpecialities[$this->specialityId] = $this;

        return true;
    }

    // Méthode pour supprimer une association agent/spécialité dans la base de donnée et dans la classe
    public function deleteAgentSpecialityById($agentId, $specialityId): bool
    {
        // Supprimer l'association de la base de données
        $query = "DELETE FROM Agents_Specialities WHERE agent_id = :agentId AND speciality_id = :specialityId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->bindParam(':specialityId', $specialityId);
        $stmt->execute();

        // Supprimer l'association de la classe
        if (isset(self::$agentSpecialities[$agentId]) && isset(self::$agentSpecialities[$specialityId])) {
            unset(self::$agentSpecialities[$agentId]);
            unset(self::$agentSpecialities[$specialityId]);
            return true;
        }

        return false;
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
