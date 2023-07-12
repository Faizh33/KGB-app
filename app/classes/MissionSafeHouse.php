<?php

namespace app\classes;

class MissionSafeHouse
{
    private string $missionId;
    private string $safeHouseId;
    private static \PDO $pdo;

    private static array $missionSafeHouses = [];

    public function __construct(\PDO $pdo, string $missionId = '', string $safeHouseId = '')
    {
        self::$pdo = $pdo;
        $this->missionId = $missionId;
        $this->safeHouseId = $safeHouseId;

        if (!isset(self::$missionSafeHouses[$missionId])) {
            self::$missionSafeHouses[$missionId] = [];
        }

        if (!isset(self::$missionSafeHouses[$safeHouseId])) {
            self::$missionSafeHouses[$safeHouseId] = [];
        }

        self::$missionSafeHouses[$missionId][] = $this;
        self::$missionSafeHouses[$safeHouseId][] = $this;
    }

    /**
     * Récupère toutes les associations entre missions et planques.
     *
     * @return array Un tableau contenant toutes les associations MissionSafeHouse.
     */
    public static function getAllMissionSafeHouses(): array
    {
        $query = "SELECT mission_id, safehouse_id FROM Missions_safehouses";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $missionSafeHousesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionSafeHouses = [];

        foreach ($missionSafeHousesData as $missionSafeHouseData) {
            $missionId = $missionSafeHouseData['mission_id'];
            $safeHouseId = $missionSafeHouseData['safehouse_id'];

            $missionSafeHouse = new MissionSafeHouse(self::$pdo, $missionId, $safeHouseId);
            $missionSafeHouses[] = $missionSafeHouse;
        }

        return $missionSafeHouses;
    }

    /**
     * Récupère toutes les planques associées à une mission spécifique.
     *
     * @param string $missionId L'ID de la mission.
     * @return array Un tableau d'instances MissionSafeHouse représentant les planques associées à la mission.
     */
    public static function getSafeHousesByMissionId(string $missionId): array
    {
        if (isset(self::$missionSafeHouses[$missionId])) {
            return self::$missionSafeHouses[$missionId];
        }

        $query = "SELECT * FROM Missions_safehouses WHERE mission_id = :missionId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionSafeHouses = [];

        foreach ($rows as $row) {
            $missionSafeHouse = new MissionSafeHouse(self::$pdo, $row['mission_id'], $row['safehouse_id']);
            $missionSafeHouses[] = $missionSafeHouse;
        }

        self::$missionSafeHouses[$missionId] = $missionSafeHouses;

        return $missionSafeHouses;
    }

    /**
     * Récupère toutes les missions associées à une planque spécifique.
     *
     * @param string $safeHouseId L'ID de la planque.
     * @return array Un tableau d'instances MissionSafeHouse représentant les missions associées à la planque.
     */
    public static function getMissionsBySafeHouseId(string $safeHouseId): array
    {
        if (isset(self::$missionSafeHouses[$safeHouseId])) {
            return self::$missionSafeHouses[$safeHouseId];
        }

        $query = "SELECT * FROM Missions_safehouses WHERE safehouse_id = :safeHouseId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':safeHouseId', $safeHouseId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionSafeHouses = [];

        foreach ($rows as $row) {
            $missionSafeHouse = new MissionSafeHouse(self::$pdo, $row['mission_id'], $row['safehouse_id']);
            $missionSafeHouses[] = $missionSafeHouse;
        }

        self::$missionSafeHouses[$safeHouseId] = $missionSafeHouses;

        return $missionSafeHouses;
    }

    /**
     * Ajoute une planque à une mission spécifique.
     *
     * @param string $missionId L'ID de la mission.
     * @param string $safeHouseId L'ID de la planque.
     * @return MissionSafeHouse|null L'instance de MissionSafeHouse représentant l'association entre la mission et la planque, ou null en cas d'erreur.
     */
    public static function addSafeHouseToMission(string $missionId, string $safeHouseId): ?MissionSafeHouse
    {
        $query = "INSERT INTO Missions_safehouses (mission_id, safehouse_id) VALUES (:missionId, :safeHouseId)";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->bindValue(':safeHouseId', $safeHouseId);
        $stmt->execute();

        $newMissionSafeHouse = new MissionSafeHouse(self::$pdo, $missionId, $safeHouseId);
        self::$missionSafeHouses[$missionId][] = $newMissionSafeHouse;
        self::$missionSafeHouses[$safeHouseId][] = $newMissionSafeHouse;

        return $newMissionSafeHouse;
    }

    /**
     * Met à jour les propriétés d'une planque dans toutes les missions associées.
     *
     * @param string $missionId L'ID de la mission.
     * @param array $propertiesToUpdate Les propriétés à mettre à jour sous la forme [nomPropriete => nouvelleValeur].
     * @return bool Indique si la mise à jour a réussi ou non.
     */
    public static function updateSafeHouseProperties(string $missionId, array $propertiesToUpdate): bool
    {
        $safeHouseId = self::getSafeHouseId();

        $updatedMissionSafeHouses = [];

        foreach (self::$missionSafeHouses[$missionId] as $missionSafeHouse) {
            foreach ($propertiesToUpdate as $property => $value) {
                if ($missionSafeHouse->$property !== $value) {
                    $missionSafeHouse->$property = $value;
                }
            }
            $updatedMissionSafeHouses[] = $missionSafeHouse;
        }

        $query = "UPDATE Missions_safehouses SET safehouse_id = :newSafeHouseId WHERE mission_id = :missionId AND safehouse_id = :safeHouseId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':newSafeHouseId', $safeHouseId);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->bindValue(':safeHouseId', $safeHouseId);
        $stmt->execute();

        self::$missionSafeHouses[$missionId] = $updatedMissionSafeHouses;

        return true;
    }

    /**
     * Supprime toutes les planques associées à une mission donnée.
     *
     * @param string $missionId L'ID de la mission.
     * @return bool Indique si la suppression a réussi ou non.
     */
    public static function deleteSafeHousesByMissionId(string $missionId): bool
    {
        $query = "DELETE FROM Missions_safehouses WHERE mission_id = :missionId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->execute();

        unset(self::$missionSafeHouses[$missionId]);

        return true;
    }

    /**
     * Supprime toutes les missions associées à une planque donnée.
     *
     * @param string $safeHouseId L'ID de la planque.
     * @return bool Indique si la suppression a réussi ou non.
     */
    public static function deleteMissionsBySafeHouseId(string $safeHouseId): bool
    {
        $query = "DELETE FROM Missions_safehouses WHERE safehouse_id = :safeHouseId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':safeHouseId', $safeHouseId);
        $stmt->execute();

        unset(self::$missionSafeHouses[$safeHouseId]);

        return true;
    }

    // Getters
    public function getMissionId(): string
    {
        return $this->missionId;
    }

    public function getSafeHouseId(): string
    {
        return $this->safeHouseId;
    }
}