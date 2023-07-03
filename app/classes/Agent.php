<?php

namespace app\classes;

require_once 'Person.php';
require_once 'AgentSpeciality.php';

class Agent extends Person
{
    protected string $identificationCode;

    public function __construct($pdo, string $id, string $lastName, string $firstName, string $birthDate, string $nationality, string $identificationCode)
    {
        parent::__construct($pdo, $id, $lastName, $firstName, $birthDate, $nationality);
        $this->identificationCode = $identificationCode;
    }

    //Méthode qui récupère un agent en fonction de son id
    public static function getAgentById($pdo, $id): ?Agent
    {
        $person = parent::getPersonById($pdo, $id);

        if ($person instanceof Person) {
            $query = "SELECT * FROM Agents WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($row) {
                return new Agent($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $row['identification_code']);
            }
        }

        return null;
    }

    //Méthode qui récupère tous les agents de la base de données et les insére dans la classe
    public static function getAllAgents($pdo): array
    {
        $persons = parent::getAllPersons($pdo);
        $agents = [];

        foreach ($persons as $person) {
            $query = "SELECT * FROM Agents WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $person->getId());
            $stmt->execute();

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($row) {
                $agents[] = new Agent($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $row['identification_code']);
            }
        }

        return $agents;
    }

    //Méthode qui ajoute un nouvel agent dans la base de données et dans la classe
    public function addAgentProperties($pdo, string $lastName, string $firstName, string $birthDate, string $nationality, string $identificationCode): ?Agent
    {
        $person = parent::addPersonProperties($pdo, $lastName, $firstName, $birthDate, $nationality);

        if ($person instanceof Person) {
            $query = "INSERT INTO Agents (id, identification_code) VALUES (:id, :identificationCode)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $person->getId());
            $stmt->bindParam(':identificationCode', $identificationCode);
            $stmt->execute();

            return new Agent($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $identificationCode);
        }

        return null;
    }

    //Méthode qui modifie les propriétés d'un agent en fonction de l'id
    public function updateAgentProperties($propertiesToUpdate): bool
    {
        $personUpdated = parent::updateProperties($propertiesToUpdate);

        if ($personUpdated) {
            $query = "UPDATE Agents SET identification_code = :identificationCode WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':identificationCode', $this->identificationCode);
            $stmt->execute();

            return true;
        }

        return false;
    }

    // Méthode qui supprime un agent de la base de donnée et de la classe en fonction de l'id
    public function deleteAgent(): bool
    {
        // Vérifier l'existence de l'agent
        $agent = self::getAgentById($this->pdo, $this->id);
        if (!$agent) {
            return false; // L'agent n'existe pas, retourner false
        }
    
        // Supprimer les associations AgentSpeciality pour cet agent
        $agentSpeciality = new AgentSpeciality($this->pdo, $this->id, 0);
        $agentSpeciality->deleteSpecialitiesByAgentId($this->id);
    
        // Supprimer l'agent lui-même
        $personDeleted = parent::deletePersonById($this->id);
    
        if ($personDeleted) {
            $query = "DELETE FROM Agents WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();
    
            return true;
        }
    
        return false;
    }    

    //Getter et Setter
    public function getIdentificationCode(): string
    {
        return $this->identificationCode;
    }

    public function setIdentificationCode(string $identificationCode): void
    {
        $this->identificationCode = $identificationCode;
    }
}
