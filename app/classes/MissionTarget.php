<?php

namespace app\classes;

class MissionTarget
{
    private string $missionId;
    private string $targetId;
    private \PDO $pdo;

    private static array $missionTargets = [];

    public function __construct(\PDO $pdo, string $missionId = '', string $targetId = '')
    {
        $this->pdo = $pdo;
        $this->missionId = $missionId;
        $this->targetId = $targetId;

        if (!isset(self::$missionTargets[$missionId])) {
            self::$missionTargets[$missionId] = [];
        }

        if (!isset(self::$missionTargets[$targetId])) {
            self::$missionTargets[$targetId] = [];
        }

        $existingMissionTarget = $this->findExistingMissionTarget($missionId, $targetId);
        if ($existingMissionTarget) {
            return $existingMissionTarget;
        }

        self::$missionTargets[$missionId][] = $this;
        self::$missionTargets[$targetId][] = $this;
    }

    /**
     * Recherche une mission cible existante pour une mission et une cible spécifiées.
     *
     * @param string $missionId L'ID de la mission.
     * @param string $targetId L'ID de la cible.
     * @return MissionTarget|null La mission cible existante ou null si elle n'existe pas.
     */
    private function findExistingMissionTarget(string $missionId, string $targetId): ?MissionTarget
    {
        if (isset(self::$missionTargets[$missionId])) {
            foreach (self::$missionTargets[$missionId] as $missionTarget) {
                if ($missionTarget->getMissionId() === $missionId && $missionTarget->getTargetId() === $targetId) {
                    return $missionTarget;
                }
            }
        }
        return null;
    }

    /**
     * Récupère toutes les missions cibles de la base de données.
     *
     * @return array Un tableau contenant toutes les missions cibles.
     */
    public static function getAllMissionTargets($pdo): array
    {
        $query = "SELECT mission_id, target_id FROM Missions_targets";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $missionTargetsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionTargets = [];

        foreach ($missionTargetsData as $missionTargetData) {
            $missionId = $missionTargetData['mission_id'];
            $targetId = $missionTargetData['target_id'];

            $existingMissionTarget = MissionTarget::findExistingMissionTarget($missionId, $targetId);
            if ($existingMissionTarget) {
                $missionTargets[] = $existingMissionTarget;
            } else {
                $missionTarget = new MissionTarget($pdo, $missionId, $targetId);
                $missionTargets[] = $missionTarget;
            }
        }

        return $missionTargets;
    }

    /**
     * Récupère les cibles associées à une mission donnée.
     *
     * @param string $missionId L'identifiant de la mission.
     * @return array Un tableau contenant les cibles associées à la mission.
     */
    public static function getTargetsByMissionId($pdo, string $missionId): array
    {
        if (isset(self::$missionTargets[$missionId])) {
            return self::$missionTargets[$missionId];
        }

        $query = "SELECT * FROM Missions_targets WHERE mission_id = :missionId";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->execute();

        $missionTargetDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionTargets = [];

        foreach ($missionTargetDatas as $missionTargetData) {
            $missionId = $missionTargetData['mission_id'];
            $targetId = $missionTargetData['target_id'];

            $missionTarget = new MissionTarget($pdo, $missionId, $targetId);
            $missionTargets[] = $missionTarget;
        }

        self::$missionTargets[$missionId] = $missionTargets;

        return $missionTargets;
    }

    /**
     * Récupère les missions associées à une cible donnée.
     *
     * @param string $targetId L'identifiant de la cible.
     * @return array Un tableau contenant les missions associées à la cible.
     */
    public function getMissionsByTargetId(string $targetId): array
    {
        if (isset(self::$missionTargets[$targetId])) {
            return self::$missionTargets[$targetId];
        }

        $query = "SELECT * FROM Missions_targets WHERE target_id = :targetId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':targetId', $targetId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionTargets = [];

        foreach ($rows as $row) {
            $missionTarget = new MissionTarget($this->pdo, $row['mission_id'], $row['target_id']);
            $missionTargets[] = $missionTarget;
        }

        self::$missionTargets[$targetId] = $missionTargets;

        return $missionTargets;
    }

    /**
     * Ajoute une cible à une mission donnée.
     *
     * @param string $missionId L'identifiant de la mission.
     * @param string $targetId L'identifiant de la cible.
     * @return MissionTarget|null L'objet MissionTarget correspondant à la nouvelle association ou null si elle existe déjà.
     */
    public function addTargetToMission(string $missionId, string $targetId): ?MissionTarget
    {
        $existingMissionTarget = $this->findExistingMissionTarget($missionId, $targetId);
        if ($existingMissionTarget) {
            return $existingMissionTarget;
        }

        $query = "INSERT INTO Missions_targets (mission_id, target_id) VALUES (:missionId, :targetId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->bindValue(':targetId', $targetId);
        $stmt->execute();

        $newMissionTarget = new MissionTarget($this->pdo, $missionId, $targetId);
        self::$missionTargets[$missionId][] = $newMissionTarget;
        self::$missionTargets[$targetId][] = $newMissionTarget;

        return $newMissionTarget;
    }

    /**
     * Met à jour les propriétés d'une cible dans une mission donnée.
     *
     * @param string $missionId L'identifiant de la mission.
     * @param array $propertiesToUpdate Les propriétés à mettre à jour sous la forme [nomPropriete => nouvelleValeur].
     * @return bool Indique si la mise à jour a été effectuée avec succès.
     */
    public function updateTargetProperties(string $missionId, array $propertiesToUpdate): bool
    {
        $targetId = $this->getTargetId();

        $updatedMissionTargets = [];

        foreach (self::$missionTargets[$missionId] as $missionTarget) {
            foreach ($propertiesToUpdate as $property => $value) {
                if ($missionTarget->$property !== $value) {
                    $missionTarget->$property = $value;
                }
            }
            $updatedMissionTargets[] = $missionTarget;
        }

        $query = "UPDATE Missions_targets SET target_id = :newTargetId WHERE mission_id = :missionId AND target_id = :targetId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':newTargetId', $this->targetId);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->bindValue(':targetId', $targetId);
        $stmt->execute();

        self::$missionTargets[$missionId] = $updatedMissionTargets;

        return true;
    }

    /**
     * Supprime toutes les cibles associées à une mission donnée.
     *
     * @param string $missionId L'identifiant de la mission.
     * @return bool Indique si la suppression a été effectuée avec succès.
     */
    public function deleteTargetsByMissionId(string $missionId): bool
    {
        $query = "DELETE FROM Missions_targets WHERE mission_id = :missionId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->execute();

        unset(self::$missionTargets[$missionId]);

        return true;
    }

    /**
     * Supprime toutes les missions associées à une cible donnée.
     *
     * @param string $targetId L'identifiant de la cible.
     * @return bool Indique si la suppression a été effectuée avec succès.
     */
    public function deleteMissionsByTargetId(string $targetId): bool
    {
        $query = "DELETE FROM Missions_targets WHERE target_id = :targetId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':targetId', $targetId);
        $stmt->execute();

        unset(self::$missionTargets[$targetId]);

        return true;
    }

    //Getters
    public function getMissionId(): string
    {
        return $this->missionId;
    }

    public function getTargetId(): string
    {
        return $this->targetId;
    }
}