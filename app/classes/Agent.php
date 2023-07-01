<?php

namespace app\classes;

require_once 'person.php';

class Agent extends Person
{
    protected string $identificationCode;

    public function __construct($pdo, string $id, string $lastName, string $firstName, string $birthDate, string $nationality, string $identificationCode)
    {
        parent::__construct($pdo, $id, $lastName, $firstName, $birthDate, $nationality);
        $this->identificationCode = $identificationCode;
    }

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

    public function deleteAgent(): bool
    {
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

    public function getIdentificationCode(): string
    {
        return $this->identificationCode;
    }

    public function setIdentificationCode(string $identificationCode): void
    {
        $this->identificationCode = $identificationCode;
    }
}
