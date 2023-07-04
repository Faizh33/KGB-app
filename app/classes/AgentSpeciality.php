<?php

namespace app\classes;

class AgentSpeciality
{
    private string $agentId;
    private int $specialityId;
    private \PDO $pdo;

    private static array $agentSpecialities = [];

    public function __construct(\PDO $pdo, string $agentId = '', int $specialityId = null)
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

    /**
     * Recherche une AgentSpeciality existante dans la classe en fonction de l'ID de l'agent et de l'ID de la spécialité.
     *
     * @param string $agentId L'ID de l'agent.
     * @param int $specialityId L'ID de la spécialité.
     *
     * @return AgentSpeciality|null Retourne l'AgentSpeciality correspondante si elle existe, sinon retourne null.
     */
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


    /**
     * Récupère les spécialités d'un agent en fonction de son ID.
     *
     * @param string $agentId  L'ID de l'agent.
     * @return array           Un tableau contenant les spécialités de l'agent.
     */
    public function getSpecialitiesByAgentId(string $agentId): array
    {
        // Vérifier si les spécialités de l'agent sont déjà présentes dans la mémoire cache
        if (isset(self::$agentSpecialities[$agentId])) {
            return self::$agentSpecialities[$agentId];
        }

        // Si les spécialités ne sont pas en mémoire cache, les récupérer depuis la base de données
        $query = "SELECT speciality_id FROM Agents_Specialities WHERE agent_id = :agentId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $agentSpecialities = [];

        // Parcourir les résultats de la requête
        foreach ($rows as $row) {
            $specialityId = $row['speciality_id'];

            // Chercher une spécialité existante pour cet agent et cette spécialité
            $existingAgentSpeciality = $this->findExistingAgentSpeciality($agentId, $specialityId);

            // Si une spécialité existante est trouvée, l'ajouter au tableau des spécialités de l'agent
            if ($existingAgentSpeciality) {
                $agentSpecialities[] = $existingAgentSpeciality;
            } else {
                // Si aucune spécialité existante n'est trouvée, créer une nouvelle spécialité pour l'agent
                $agentSpeciality = new AgentSpeciality($this->pdo, $agentId, $specialityId);
                $agentSpecialities[] = $agentSpeciality;
            }
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
    public function getAgentsBySpecialityId(int $specialityId): array
    {
        // Requête SQL pour récupérer les IDs des agents ayant la spécialité spécifiée
        $query = "SELECT agent_id FROM Agents_Specialities WHERE speciality_id = :specialityId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':specialityId', $specialityId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $agents = [];

        // Parcourir les résultats de la requête
        foreach ($rows as $row) {
            $agentId = $row['agent_id'];

            // Chercher une spécialité existante pour cet agent et cette spécialité
            $existingAgentSpeciality = $this->findExistingAgentSpeciality($agentId, $specialityId);

            // Si une spécialité existante est trouvée, l'ajouter au tableau des agents
            if ($existingAgentSpeciality) {
                $agents[] = $existingAgentSpeciality;
            } else {
                // Si aucune spécialité existante n'est trouvée, créer une nouvelle spécialité pour l'agent
                $agentSpeciality = new AgentSpeciality($this->pdo, $agentId, $specialityId);
                $agents[] = $agentSpeciality;
            }
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
    public function addSpecialityToAgent(string $agentId, int $specialityId): ?AgentSpeciality
    {
        // Chercher une spécialité existante pour cet agent et cette spécialité
        $existingAgentSpeciality = $this->findExistingAgentSpeciality($agentId, $specialityId);
        
        // Si une spécialité existante est trouvée, la retourner
        if ($existingAgentSpeciality) {
            return $existingAgentSpeciality;
        }

        // Sinon, insérer la nouvelle spécialité dans la table Agents_Specialities
        $query = "INSERT INTO Agents_Specialities (agent_id, speciality_id) VALUES (:agentId, :specialityId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->bindParam(':specialityId', $specialityId);
        $stmt->execute();

        // Créer un nouvel objet AgentSpeciality pour représenter la nouvelle spécialité
        $newAgentSpeciality = new AgentSpeciality($this->pdo, $agentId, $specialityId);
        
        // Ajouter la nouvelle spécialité à la liste des spécialités de l'agent
        self::$agentSpecialities[$agentId][] = $newAgentSpeciality;

        // Retourner la nouvelle spécialité ajoutée
        return $newAgentSpeciality;
    }

    /**
     * Met à jour les propriétés d'une spécialité pour un agent spécifié.
     *
     * @param string $agentId              L'ID de l'agent.
     * @param array $propertiesToUpdate    Les propriétés à mettre à jour sous la forme d'un tableau associatif.
     * @return bool                        Indique si la mise à jour des propriétés a réussi ou non.
     */
    public function updateSpecialityProperties(string $agentId, array $propertiesToUpdate): bool
    {
        // Obtenir l'ID de la spécialité actuelle
        $specialityId = $this->getSpecialityId();

        // Tableau pour stocker les spécialités mises à jour
        $updatedAgentSpecialities = [];

        // Parcourir les spécialités de l'agent et les propriétés à mettre à jour
        foreach (self::$agentSpecialities[$agentId] as $agentSpeciality) {
            foreach ($propertiesToUpdate as $property => $value) {
                // Vérifier si la propriété doit être mise à jour
                if ($agentSpeciality->$property !== $value) {
                    $agentSpeciality->$property = $value;
                }
            }
            $updatedagentSpecialities[] = $agentSpeciality;
        }

        // Mettre à jour la spécialité dans la table Agents_Specialities
        $query = "UPDATE Agents_Specialities SET speciality_id = :newSpecialityId WHERE agent_id = :agentId AND speciality_id = :specialityId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':newSpecialityId', $this->specialityId);
        $stmt->bindParam(':agentId', $agentId);
        $stmt->bindParam(':specialityId', $specialityId);
        $stmt->execute();

        // Mettre à jour la liste des spécialités de l'agent
        self::$agentSpecialities[$agentId] = $updatedAgentSpecialities;

        // Retourner true pour indiquer que la mise à jour des propriétés a réussi
        return true;
    }

    /**
     * Supprime toutes les spécialités d'un agent spécifié.
     *
     * @param string $agentId   L'ID de l'agent.
     * @return bool             Indique si la suppression des spécialités a réussi ou non.
     */
    public function deleteSpecialitiesByAgentId(string $agentId): bool
    {
        // Requête SQL pour supprimer les spécialités de l'agent
        $query = "DELETE FROM Agents_Specialities WHERE agent_id = :agentId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':agentId', $agentId);
        $stmt->execute();

        // Supprimer les spécialités de l'agent de la liste des spécialités
        unset(self::$agentSpecialities[$agentId]);

        // Retourner true pour indiquer que la suppression des spécialités a réussi
        return true;
    }

    /**
     * Supprime tous les agents ayant une spécialité spécifiée.
     *
     * @param int $specialityId   L'ID de la spécialité.
     * @return bool               Indique si la suppression des agents a réussi ou non.
     */
    public function deleteAgentsBySpecialityId(int $specialityId): bool
    {
        // Requête SQL pour supprimer les agents ayant la spécialité spécifiée
        $query = "DELETE FROM Agents_Specialities WHERE speciality_id = :specialityId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':specialityId', $specialityId);
        $stmt->execute();

        // Parcourir la liste des spécialités des agents
        foreach (self::$agentSpecialities as $agentId => $agentSpecialities) {
            foreach ($agentSpecialities as $key => $agentSpeciality) {
                // Si la spécialité de l'agent correspond à l'ID de la spécialité spécifiée, la supprimer
                if ($agentSpeciality->getSpecialityId() === $specialityId) {
                    unset(self::$agentSpecialities[$agentId][$key]);
                }
            }
        }

        // Retourner true pour indiquer que la suppression des agents a réussi
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
