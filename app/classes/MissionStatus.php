<?php

namespace app\classes;

class MissionStatus
{
    private int $id;
    private string $status;
    private static \PDO $pdo;

    private static array $missionStatuses = [];

    public function __construct($pdo, int $id = NULL, string $status = '')
    {
        self::$pdo = $pdo;
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
    public static function getMissionStatusById(int $id)
    {
        if (isset(self::$missionStatuses[$id])) {
            return self::$missionStatuses[$id];
        }

        $query = "SELECT * FROM MissionStatuses WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $statusDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id = $statusDatas['id'];
        $status = $statusDatas['status'];

        if ($statusDatas) {
            $missionStatus = new MissionStatus(self::$pdo, $id, $status);
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
    public static function getAllMissionStatuses(): array
    {
        $query = "SELECT * FROM MissionStatuses";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $missionStatusesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionStatuses = [];
        foreach ($missionStatusesData as $missionStatusData) {
            $missionStatusId = $missionStatusData['id'];

            if (!isset(self::$missionStatuses[$missionStatusId])) {
                $missionStatus = new MissionStatus(self::$pdo, $missionStatusId, $missionStatusData['status']);
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
    public static function addMissionStatus(string $status): ?MissionStatus
    {
        // Vérifier si le statut de mission existe déjà dans la base de données
        $query = "SELECT * FROM MissionStatuses WHERE status = :status";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->execute();

        $statusDatas = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($statusDatas) {
            return null;
        }

        // Insérer le nouveau statut de mission dans la base de données et dans la classe
        $query = "INSERT INTO MissionStatuses (status) VALUES (:status)";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':status', $status);
        $stmt->execute();

        $newMissionStatusId = self::$pdo->lastInsertId();

        $newMissionStatus = new MissionStatus(self::$pdo, $newMissionStatusId, $status);

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
    public static function updateMissionStatusProperties(int $id, array $propertiesToUpdate): bool
    {
        // Récupérer l'instance du statut de mission correspondant à l'ID
        $missionStatus = self::getMissionStatusById($id);

        if ($missionStatus) {
            // Mettre à jour les propriétés dans la classe
            foreach ($propertiesToUpdate as $property => $value) {
                if ($missionStatus->$property !== $value) {
                    $missionStatus->$property = $value;
                }
            }

            // Mettre à jour les propriétés dans la base de données
            $query = "UPDATE MissionStatuses SET status = :status WHERE id = :id";
            $stmt = self::$pdo->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':status', $missionStatus->status);
            $stmt->execute();

            // Mettre à jour le tableau $missionStatuses
            self::$missionStatuses[$id] = $missionStatus;

            return true;
        }

        return false;
    }

    /**
     * Supprime un statut de mission de la base de données et de la classe en fonction de son ID.
     *
     * @param int $id L'identifiant du statut de mission à supprimer.
     * @return json
     */
    public static function deleteMissionStatusById($id)
    {
        // Vérifier si le statut de mission est utilisé dans une ou plusieurs missions
        $query = "SELECT COUNT(*) FROM Missions WHERE missionstatuses_id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        // Si le statut de mission est utilisé ailleurs, ne pas le supprimer
        if ($count > 0) {
            echo json_encode(array('status' => 'used'));
            exit;
        }

        // Supprimer le statut de mission de la base de données
        $query = "DELETE FROM MissionStatuses WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Supprimer le statut de mission de la classe
        if (isset(self::$missionStatuses[$id])) {
            unset(self::$missionStatuses[$id]);
            
            echo json_encode(array('status' => 'success'));
            exit;
        }

        echo json_encode(array('status' => 'error'));
        exit;
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
