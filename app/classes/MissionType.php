<?php

namespace app\classes;

class MissionType
{
    private int $id;
    private string $type;
    private $pdo;

    private static array $missionTypes = [];

    public function __construct($pdo, int $id, string $type)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->type = $type;

        self::$missionTypes[$id] = $this;
    }

    //Méthode qui récupère un type de mission en fonction de son id
    public function getMissionTypeById($id)
    {
        if (isset(self::$missionTypes[$id])) {
            return self::$missionTypes[$id];
        }

        $query = "SELECT * FROM MissionTypes WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            $missionType = new MissionType($this->pdo, $row['id'], $row['type']);
            self::$missionTypes[$id] = $missionType;
            return $missionType;
        }

        return null;
    }

    //Méthode qui récupère tous les types de mission de la base de donnée et les insère dans la classe
    public function getAllMissionTypes(): array
    {
        $query = "SELECT * FROM MissionTypes";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $missionTypesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionTypes = [];
        foreach ($missionTypesData as $missionTypeData) {
            $missionTypeId = $missionTypeData['id'];

            if (!isset(self::$missionTypes[$missionTypeId])) {
                $missionType = new MissionType($this->pdo, $missionTypeId, $missionTypeData['type']);
                self::$missionTypes[$missionTypeId] = $missionType;
            }

            $missionTypes[] = self::$missionTypes[$missionTypeId];
        }

        return $missionTypes;
    }

    //Méthode qui ajoute un nouveau type de mission
    public function addMissionType(string $type): ?MissionType
    {
        // Vérifier si le statut de mission existe déjà dans la base de données
        $query = "SELECT * FROM MissionTypes WHERE type = :type";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return null;
        }

        // Insérer le nouveau statut de mission dans la base de données et dans la classe
        $query = "INSERT INTO MissionTypes (type) VALUES (:type)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();

        $newMissionTypeId = $this->pdo->lastInsertId();

        $newMissionType = new MissionType($this->pdo, $newMissionTypeId, $type);

        self::$missionTypes[$newMissionTypeId] = $newMissionType;

        return $newMissionType;
    }

    // Méthode qui met à jour les propriétés du type de mission dans la base de données et dans la classe
    public function updateProperties(array $propertiesToUpdate): bool
    {
        $id = $this->getId();

        foreach ($propertiesToUpdate as $property => $value) {
            if ($this->$property !== $value) {
                $this->$property = $value;
            }
        }

        $query = "UPDATE MissionTypes SET type = :type WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $this->type);
        $stmt->execute();

        self::$missionTypes[$id] = $this;

        return true;
    }

    //Méthode qui supprime un type de mission dans la base de donnée et dans la classe en fonction de son id
    public function deleteMissionTypeById($id): bool
    {
        $query = "DELETE FROM MissionTypes WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if (isset(self::$missionTypes[$id])) {
            unset(self::$missionTypes[$id]);
            return true;
        }

        return false;
    }

    // Getters et Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
