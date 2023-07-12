<?php

namespace app\classes;

class AgentSpeciality
{
    private string $agentId;
    private int $specialityId;
    private static \PDO $pdo;

    private static array $agentSpecialities = [];

    public function __construct(\PDO $pdo, string $agentId = '', int $specialityId = null)
    {
        self::$pdo = $pdo;
        $this->agentId = $agentId ?? '';
        $this->specialityId = $specialityId ?? 0;

        if (!isset(self::$agentSpecialities[$agentId])) {
            self::$agentSpecialities[$agentId] = [];
        }

        self::$agentSpecialities[$agentId][] = $this;
    }

    /**
     * Récupère les spécialités d'un agent en fonction de son ID.
     *
     * @param string $agentId  L'ID de l'agent.
     * @return array           Un tableau contenant les spécialités de l'agent.
     */
    public static function getSpecialitiesByAgentId(string $agentId): array
    {
        // Vérifier si les spécialités de l'agent sont déjà présentes dans la mémoire cache
        if (isset(self::$agentSpecialities[$agentId])) {
            return self::$agentSpecialities[$agentId];
        }

        // Si les spécialités ne sont pas en mémoire cache, les récupérer depuis la base de données
        $query = "SELECT speciality_id FROM Agents_Specialities WHERE agent_id = :agentId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':agentId', $agentId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $agentSpecialities = [];

        // Parcourir les résultats de la requête
        foreach ($rows as $row) {
            $specialityId = $row['speciality_id'];

            // Créer une nouvelle spécialité pour l'agent
            $agentSpeciality = new AgentSpeciality(self::$pdo, $agentId, $specialityId);
            $agentSpecialities[] = $agentSpeciality;
        }

        // Mettre en cache les spécialités de l'agent
        self::$agentSpecialities[$agentId] = $agentSpecialities;

        return $agentSpecialities;
    }

    /**
     * Récupère les agents ayant une spécialité spécifique en fonction de l'ID de la spécialité.
     *
     * @param int $specialityId  L'ID de la spécialité.
     * @return array             Un tableau contenant les agents ayant la spécialité spécifiée.
     */
    public static function getAgentsBySpecialityId(int $specialityId): array
    {
        // Requête SQL pour récupérer les IDs des agents ayant la spécialité spécifiée
        $query = "SELECT agent_id FROM Agents_Specialities WHERE speciality_id = :specialityId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':specialityId', $specialityId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $agents = [];

        // Parcourir les résultats de la requête
        foreach ($rows as $row) {
            $agentId = $row['agent_id'];

            // Créer une nouvelle spécialité pour l'agent
            $agentSpeciality = new AgentSpeciality(self::$pdo, $agentId, $specialityId);
            $agents[] = $agentSpeciality;
        }

        return $agents;
    }

    /**
     * Ajoute une spécialité à un agent spécifié.
     *
     * @param string $agentId      L'ID de l'agent.
     * @param int $specialityId    L'ID de la spécialité à ajouter.
     * @return AgentSpeciality|null L'objet AgentSpeciality représentant la nouvelle spécialité ajoutée, ou null en cas d'échec.
     */
    public static function addSpecialityToAgent(string $agentId, int $specialityId): ?AgentSpeciality
    {
        // Insérer la nouvelle spécialité dans la base de données et dans la classe
        $query = "INSERT INTO Agents_Specialities (agent_id, speciality_id) VALUES (:agentId, :specialityId)";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':agentId', $agentId);
        $stmt->bindValue(':specialityId', $specialityId);
        $stmt->execute();

        // Créer une nouvelle spécialité pour l'agent
        $agentSpeciality = new AgentSpeciality(self::$pdo, $agentId, $specialityId);

        // Retourner la nouvelle spécialité ajoutée
        return $agentSpeciality;
    }

    /**
     * Met à jour les propriétés d'une spécialité pour un agent spécifié.
     *
     * @param string $agentId              L'ID de l'agent.
     * @param array $propertiesToUpdate    Les propriétés à mettre à jour sous la forme d'un tableau associatif.
     * @return bool                        Indique si la mise à jour des propriétés a réussi ou non.
     */
    public static function updateSpecialityProperties(string $agentId, array $propertiesToUpdate): bool
    {
        // Obtenir l'ID de la spécialité actuelle
        $specialityId = self::$agentSpecialities[$agentId][0]->getSpecialityId();

        // Parcourir les spécialités de l'agent et les propriétés à mettre à jour
        foreach (self::$agentSpecialities[$agentId] as $agentSpeciality) {
            foreach ($propertiesToUpdate as $property => $value) {
                // Vérifier si la propriété doit être mise à jour
                if ($agentSpeciality->$property !== $value) {
                    $agentSpeciality->$property = $value;
                }
            }
        }

        // Mettre à jour la spécialité dans la table Agents_Specialities
        $query = "UPDATE Agents_Specialities SET speciality_id = :newSpecialityId WHERE agent_id = :agentId AND speciality_id = :specialityId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':newSpecialityId', $specialityId);
        $stmt->bindValue(':agentId', $agentId);
        $stmt->bindValue(':specialityId', $specialityId);
        $stmt->execute();

        // Retourner true pour indiquer que la mise à jour des propriétés a réussi
        return true;
    }

    /**
     * Supprime toutes les spécialités d'un agent spécifié.
     *
     * @param string $agentId   L'ID de l'agent.
     * @return bool             Indique si la suppression des spécialités a réussi ou non.
     */
    public static function deleteSpecialitiesByAgentId(string $agentId): bool
    {
        // Requête SQL pour supprimer les spécialités de l'agent
        $query = "DELETE FROM Agents_Specialities WHERE agent_id = :agentId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':agentId', $agentId);
        $stmt->execute();

        // Supprimer les spécialités de l'agent de la liste des spécialités
        unset(self::$agentSpecialities[$agentId]);

        // Retourner true pour indiquer que la suppression des spécialités a réussi
        return true;
    }

    //Getters
    public function getAgentId(): string
    {
        return $this->agentId;
    }

    public function getSpecialityId(): int
    {
        return $this->specialityId;
    }
}