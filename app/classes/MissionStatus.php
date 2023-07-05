<?php

namespace app\classes;

class MissionStatus
{
    private int $id;
    private string $status;
    private $pdo;

    private static array $missionStatuses = [];

    public function __construct($pdo, int $id = NULL, string $status = '')
    {
        $this->pdo = $pdo;
        $this->id = $id ?? 0;
        $this->status = $status;

        self::$missionStatuses[$id] = $this;
    }

    /**
     * Récupère le statut d'une mission à partir de son identifiant.
     *
     * @param mixed $id L'identifiant de la mission.
     * @return MissionStatus|null Le statut de la mission ou null si non trouvé.
     */
    public static function getMissionStatusById($pdo, int $id)
    {
        if (isset(self::$missionStatuses[$id])) {
            return self::$missionStatuses[$id];
        }

        $query = "SELECT * FROM MissionStatuses WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $statusDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id = $statusDatas['id'];
        $status = $statusDatas['status'];

        if ($statusDatas) {
            $missionStatus = new MissionStatus($pdo, $id, $status);
            self::$missionStatuses[$id] = $missionStatus;
            return $missionStatus;
        }

        return null;
    }

    /**
     * Récupère tous les statuts de mission de la base de données.
     *
     * @return array Les statuts de mission.
     */
    public static function getAllMissionStatuses($pdo): array
    {
        $query = "SELECT * FROM MissionStatuses";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $missionStatusesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionStatuses = [];
        foreach ($missionStatusesData as $missionStatusData) {
            $missionStatusId = $missionStatusData['id'];

            if (!isset(self::$missionStatuses[$missionStatusId])) {
                $missionStatus = new MissionStatus($pdo, $missionStatusId, $missionStatusData['status']);
                self::$missionStatuses[$missionStatusId] = $missionStatus;
            }

            $missionStatuses[] = self::$missionStatuses[$missionStatusId];
        }

        return $missionStatuses;
    }

    /**
     * Ajoute un nouveau statut de mission.
     *
     * @param string $status Le statut de mission à ajouter.
     * @return MissionStatus|null Le nouveau statut de mission ajouté, ou null si le statut existe déjà.
     */
    public function addMissionStatus(string $status): ?MissionStatus
    {
        // Vérifier si le statut de mission existe déjà dans la base de données
        $query = "SELECT * FROM MissionStatuses WHERE status = :status";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->execute();

        $statusDatas = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($statusDatas) {
            return null;
        }

        // Insérer le nouveau statut de mission dans la base de données et dans la classe
        $query = "INSERT INTO MissionStatuses (status) VALUES (:status)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->execute();

        $newMissionStatusId = $this->pdo->lastInsertId();

        $newMissionStatus = new MissionStatus($this->pdo, $newMissionStatusId, $status);

        self::$missionStatuses[$newMissionStatusId] = $newMissionStatus;

        return $newMissionStatus;
    }

    /**
     * Met à jour les propriétés du statut de mission dans la base de données et dans la classe.
     *
     * @param int $id L'identifiant du statut de mission à mettre à jour.
     * @param array $propertiesToUpdate Les propriétés à mettre à jour avec leurs nouvelles valeurs.
     * @return bool Indique si la mise à jour a été effectuée avec succès.
     */
    public function updateMissionStatusProperties(int $id, array $propertiesToUpdate): bool
    {
        // Mettre à jour les propriétés dans la classe
        foreach ($propertiesToUpdate as $property => $value) {
            if ($this->$property !== $value) {
                $this->$property = $value;
            }
        }

        // Mettre à jour les propriétés dans la base de données
        $query = "UPDATE MissionStatuses SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':status', $this->status);
        $stmt->execute();

        // Mettre à jour le tableau $missionStatuses
        self::$missionStatuses[$id] = $this;

        return true;
    }

    /**
     * Supprime un statut de mission de la base de données et de la classe en fonction de son ID.
     *
     * @param int $id L'identifiant du statut de mission à supprimer.
     * @return bool Indique si la suppression a été effectuée avec succès.
     */
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
