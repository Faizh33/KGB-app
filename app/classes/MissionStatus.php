<?php

namespace app\classes;

class MissionStatus
{
    private int $id;
    private string $status;
    private $pdo;

    private static array $missionStatuses = [];

    public function __construct($pdo, int $id, string $status)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->status = $status;

        self::$missionStatuses[$id] = $this;
    }

    //Méthode qui récupère un statut de mission en fonction de son id
    public function getMissionStatusById($id)
    {
        if (isset(self::$missionStatuses[$id])) {
            return self::$missionStatuses[$id];
        }

        $query = "SELECT * FROM MissionStatuses WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            $missionStatus = new MissionStatus($this->pdo, $row['id'], $row['status']);
            self::$missionStatuses[$id] = $missionStatus;
            return $missionStatus;
        }

        return null;
    }

    //Méthode qui récupère tous les statuts de mission de la base de donnée et les insère dans la classe
    public function getAllMissionStatuses(): array
    {
        $query = "SELECT * FROM MissionStatuses";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $missionStatusesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionStatuses = [];
        foreach ($missionStatusesData as $missionStatusData) {
            $missionStatusId = $missionStatusData['id'];

            if (!isset(self::$missionStatuses[$missionStatusId])) {
                $missionStatus = new MissionStatus($this->pdo, $missionStatusId, $missionStatusData['status']);
                self::$missionStatuses[$missionStatusId] = $missionStatus;
            }

            $missionStatuses[] = self::$missionStatuses[$missionStatusId];
        }

        return $missionStatuses;
    }

    //Méthode qui ajoute un nouveau statut de mission
    public function addMissionStatus(string $status): ?MissionStatus
    {
        // Vérifier si le statut de mission existe déjà dans la base de données
        $query = "SELECT * FROM MissionStatuses WHERE status = :status";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return null;
        }

        // Insérer le nouveau statut de mission dans la base de données et dans la classe
        $query = "INSERT INTO MissionStatuses (status) VALUES (:status)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['status' => $status]);
    
        $newMissionStatusId = $this->pdo->lastInsertId();

        $newMissionStatus = new MissionStatus($this->pdo, $newMissionStatusId, $status);

        self::$missionStatuses[$newMissionStatusId] = $newMissionStatus;

        return $newMissionStatus;
    }

        // Méthode qui met à jour les propriétés du statut de mission dans la base de données et dans la classe
        public function updateProperties(array $propertiesToUpdate): bool
        {
            $id = $this->getId();
    
            foreach ($propertiesToUpdate as $property => $value) {
                if ($this->$property !== $value) {
                    $this->$property = $value;
                }
            }
    
            $query = "UPDATE MissionStatuses SET status = :status WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $this->status);
            $stmt->execute();
    
            // Mettre à jour le tableau $missionStatuses
            self::$missionStatuses[$id] = $this;
    
            return true;
        }

    //Méthode qui supprime un statut de mission dans la base de donnée et dans la classe en fonction de son id
    public function deleteMissionStatusById($id): bool
    {
        // Supprimer le statut de mission de la base de données
        $query = "DELETE FROM MissionStatuses WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        // Supprimer le statut de mission de la classe
        if (isset(self::$missionStatuses[$id])) {
            unset(self::$missionStatuses[$id]);
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
