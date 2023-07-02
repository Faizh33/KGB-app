<?php

namespace app\classes;

require_once 'AgentSpeciality.php';

class Speciality
{
    private int $id;
    private string $speciality;
    private $pdo;

    private static array $specialities = [];

    public function __construct($pdo, int $id, string $speciality)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->speciality = $speciality;

        self::$specialities[$id] = $this;
    }

    public function getSpecialityById($id)
    {
        if (isset(self::$specialities[$id])) {
            return self::$specialities[$id];
        }

        $query = "SELECT * FROM Specialities WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            $speciality = new Speciality($this->pdo, $row['id'], $row['speciality']);
            self::$specialities[$id] = $speciality;
            return $speciality;
        }

        return null;
    }

    public function getAllSpecialities(): array
    {
        $query = "SELECT * FROM Specialities";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $specialitiesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $specialities = [];
        foreach ($specialitiesData as $specialityData) {
            $specialityId = $specialityData['id'];

            if (!isset(self::$specialities[$specialityId])) {
                $speciality = new Speciality($this->pdo, $specialityId, $specialityData['speciality']);
                self::$specialities[$specialityId] = $speciality;
            }

            $specialities[] = self::$specialities[$specialityId];
        }

        return $specialities;
    }

    //Ajouter une nouvelle spécialité dans la base de donnée et dans la classe
    public function addSpeciality(string $speciality): ?Speciality
    {
        // Vérifier si la spécialité existe déjà dans la base de données
        $query = "SELECT * FROM Specialities WHERE speciality = :speciality";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':speciality', $speciality);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return null;
        }

        // Insérer la nouvelle spécialité dans la base de données et dans la classe
        $query = "INSERT INTO Specialities (speciality) VALUES (:speciality)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':speciality', $speciality);
        $stmt->execute();
    
        $newSpecialityId = $this->pdo->lastInsertId();

        $newSpeciality = new Speciality($this->pdo, $newSpecialityId, $speciality);

        self::$specialities[$newSpecialityId] = $newSpeciality;

        return $newSpeciality;
    }


    // Méthode qui met à jour les propriétés de la spécialité dans la base de données et dans la classe
    public function updateProperties(array $propertiesToUpdate): bool
    {
        $id = $this->getId();

        foreach ($propertiesToUpdate as $property => $value) {
            if ($this->$property !== $value) {
                $this->$property = $value;
            }
        }

        $query = "UPDATE Specialities SET speciality = :speciality WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':speciality', $this->speciality);
        $stmt->execute();

        // Mettre à jour le tableau $persons
        self::$specialities[$id] = $this;

        return true;
    }

    public function deleteSpecialityById($id): bool
    {
        // Vérifier si l'ID existe en base de données
        $query = "SELECT * FROM Specialities WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
    
        // Supprimer la spécialité de la base de données
        $query = "DELETE FROM Specialities WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        // Supprimer les associations agent/spécialité correspondantes en utilisant une instance de la classe AgentSpeciality
        $agentSpeciality = new AgentSpeciality($this->pdo, '', $id);
        $agentSpeciality->deleteAgentsBySpecialityId($id);
    
        // Supprimer la spécialité de la classe
        if (isset(self::$specialities[$id])) {
            unset(self::$specialities[$id]);
            return true;
        }
    
        return false;
    }
    
    //Getters et Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSpeciality(): string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): void
    {
        $this->speciality = $speciality;
    }
}
