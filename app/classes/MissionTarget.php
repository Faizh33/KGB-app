<?php

namespace app\classes;

class MissionTarget
{
    private string $missionId;
    private string $targetId;
    private \PDO $pdo;

    private static array $missionTargets = [];

    public function __construct(\PDO $pdo, string $missionId, string $targetId)
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

    public function getAllMissionTargets(): array
    {
        $query = "SELECT mission_id, target_id FROM Missions_targets";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $missionTargetsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionTargets = [];

        foreach ($missionTargetsData as $missionTargetData) {
            $missionId = $missionTargetData['mission_id'];
            $targetId = $missionTargetData['target_id'];

            $existingMissionTarget = $this->findExistingMissionTarget($missionId, $targetId);
            if ($existingMissionTarget) {
                $missionTargets[] = $existingMissionTarget;
            } else {
                $missionTarget = new MissionTarget($this->pdo, $missionId, $targetId);
                $missionTargets[] = $missionTarget;
            }
        }

        return $missionTargets;
    }

    public function getTargetsByMissionId(string $missionId): array
    {
        if (isset(self::$missionTargets[$missionId])) {
            return self::$missionTargets[$missionId];
        }

        $query = "SELECT * FROM Missions_targets WHERE mission_id = :missionId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':missionId', $missionId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionTargets = [];

        foreach ($rows as $row) {
            $missionTarget = new MissionTarget($this->pdo, $row['mission_id'], $row['target_id']);
            $missionTargets[] = $missionTarget;
        }

        self::$missionTargets[$missionId] = $missionTargets;

        return $missionTargets;
    }

    public function getMissionsByTargetId(string $targetId): array
    {
        if (isset(self::$missionTargets[$targetId])) {
            return self::$missionTargets[$targetId];
        }

        $query = "SELECT * FROM Missions_targets WHERE target_id = :targetId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':targetId', $targetId);
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

    public function addTargetToMission(string $missionId, string $targetId): ?MissionTarget
    {
        $existingMissionTarget = $this->findExistingMissionTarget($missionId, $targetId);
        if ($existingMissionTarget) {
            return $existingMissionTarget;
        }

        $query = "INSERT INTO Missions_targets (mission_id, target_id) VALUES (:missionId, :targetId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':missionId', $missionId);
        $stmt->bindParam(':targetId', $targetId);
        $stmt->execute();

        $newMissionTarget = new MissionTarget($this->pdo, $missionId, $targetId);
        self::$missionTargets[$missionId][] = $newMissionTarget;
        self::$missionTargets[$targetId][] = $newMissionTarget;

        return $newMissionTarget;
    }

    public function updateTargetProperties(array $propertiesToUpdate): bool
    {
        $targetId = $this->getTargetId();

        $updatedMissionTargets = [];

        foreach (self::$missionTargets[$targetId] as $missionTarget) {
            foreach ($propertiesToUpdate as $property => $value) {
                if ($missionTarget->$property !== $value) {
                    $missionTarget->$property = $value;
                }
            }
            $updatedMissionTargets[] = $missionTarget;
        }

        $query = "UPDATE Missions_targets SET target_id = :newTargetId WHERE mission_id = :missionId AND target_id = :targetId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':newTargetId', $this->targetId);
        $stmt->bindParam(':missionId', $this->missionId);
        $stmt->bindParam(':targetId', $targetId);
        $stmt->execute();

        self::$missionTargets[$targetId] = $updatedMissionTargets;

        return true;
    }

    public function deleteTargetsByMissionId(string $missionId): bool
    {
        $query = "DELETE FROM Missions_targets WHERE mission_id = :missionId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->execute();

        unset(self::$missionTargets[$missionId]);

        return true;
    }

    public function deleteMissionsByTargetId(string $targetId): bool
    {
        $query = "DELETE FROM Missions_targets WHERE target_id = :targetId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':targetId', $targetId);
        $stmt->execute();

        unset(self::$missionTargets[$targetId]);

        return true;
    }

    public function getMissionId(): string
    {
        return $this->missionId;
    }

    public function getTargetId(): string
    {
        return $this->targetId;
    }
}