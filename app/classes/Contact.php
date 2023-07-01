<?php

namespace app\classes;

require_once 'person.php';

class Contact extends Person
{
    protected string $codeName;

    public function __construct($pdo, string $id, string $lastName, string $firstName, string $birthDate, string $nationality, string $codeName)
    {
        parent::__construct($pdo, $id, $lastName, $firstName, $birthDate, $nationality);
        $this->codeName = $codeName;
    }

    public static function getContactById($pdo, $id): ?Contact
    {
        $person = parent::getPersonById($pdo, $id);

        if ($person instanceof Person) {
            $query = "SELECT * FROM Contacts WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($row) {
                return new Contact($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $row['code_name']);
            }
        }

        return null;
    }

    public static function getAllContacts($pdo): array
    {
        $persons = parent::getAllPersons($pdo);
        $contacts = [];

        foreach ($persons as $person) {
            $query = "SELECT * FROM Contacts WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $person->getId());
            $stmt->execute();

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($row) {
                $contacts[] = new Contact($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $row['code_name']);
            }
        }

        return $contacts;
    }

    public function addContactProperties($pdo, string $lastName, string $firstName, string $birthDate, string $nationality, string $codeName): ?Contact
    {
        $person = parent::addPersonProperties($pdo, $lastName, $firstName, $birthDate, $nationality);

        if ($person instanceof Person) {
            $query = "INSERT INTO Contacts (id, code_name) VALUES (:id, :codeName)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $person->getId());
            $stmt->bindParam(':codeName', $codeName);
            $stmt->execute();

            return new Contact($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $codeName);
        }

        return null;
    }

    public function updateContactProperties($propertiesToUpdate): bool
    {
        $personUpdated = parent::updateProperties($propertiesToUpdate);

        if ($personUpdated) {
            $query = "UPDATE Contacts SET code_name = :codeName WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':codeName', $this->codeName);
            $stmt->execute();

            return true;
        }

        return false;
    }

    public function deleteContact(): bool
    {
        $personDeleted = parent::deletePersonById($this->id);

        if ($personDeleted) {
            $query = "DELETE FROM Contacts WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $this->id);
            $stmt->execute();

            return true;
        }

        return false;
    }

    public function getCodeName(): string
    {
        return $this->codeName;
    }

    public function setCodeName(string $codeName): void
    {
        $this->codeName = $codeName;
    }
}
