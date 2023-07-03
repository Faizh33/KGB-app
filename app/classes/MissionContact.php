<?php 

namespace app\classes;

class MissionContact
{
    private string $missionId;
    private string $contactId;
    private \PDO $pdo;

    private static array $missionContacts = [];

    public function __construct(\PDO $pdo, string $missionId, string $contactId)
    {
        $this->pdo = $pdo;
        $this->missionId = $missionId;
        $this->contactId = $contactId;

        if (!isset(self::$missionContacts[$missionId])) {
            self::$missionContacts[$missionId] = [];
        }

        if (!isset(self::$missionContacts[$contactId])) {
            self::$missionContacts[$contactId] = [];
        }

        $existingMissionContact = $this->findExistingMissionContact($missionId, $contactId);
        if ($existingMissionContact) {
            return $existingMissionContact;
        }

        self::$missionContacts[$missionId][] = $this;
        self::$missionContacts[$contactId][] = $this;
    }

    private function findExistingMissionContact(string $missionId, string $contactId): ?MissionContact
    {
        if (isset(self::$missionContacts[$missionId])) {
            foreach (self::$missionContacts[$missionId] as $missionContact) {
                if ($missionContact->getMissionId() === $missionId && $missionContact->getContactId() === $contactId) {
                    return $missionContact;
                }
            }
        }
        return null;
    }

    public function getAllMissionContacts(): array
    {
        $query = "SELECT mission_id, contact_id FROM Missions_contacts";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $missionContactsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionContacts = [];

        foreach ($missionContactsData as $missionContactData) {
            $missionId = $missionContactData['mission_id'];
            $contactId = $missionContactData['contact_id'];

            $existingMissionContact = $this->findExistingMissionContact($missionId, $contactId);
            if ($existingMissionContact) {
                $missionContacts[] = $existingMissionContact;
            } else {
                $missionContact = new MissionContact($this->pdo, $missionId, $contactId);
                $missionContacts[] = $missionContact;
            }
        }

        return $missionContacts;
    }

    public function getContactsByMissionId(string $missionId): array
    {
        if (isset(self::$missionContacts[$missionId])) {
            return self::$missionContacts[$missionId];
        }

        $query = "SELECT * FROM Missions_contacts WHERE mission_id = :missionId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':missionId', $missionId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionContacts = [];

        foreach ($rows as $row) {
            $missionContact = new MissionContact($this->pdo, $row['mission_id'], $row['contact_id']);
            $missionContacts[] = $missionContact;
        }

        self::$missionContacts[$missionId] = $missionContacts;

        return $missionContacts;
    }

    public function getMissionsByContactId(string $contactId): array
    {
        if (isset(self::$missionContacts[$contactId])) {
            return self::$missionContacts[$contactId];
        }

        $query = "SELECT * FROM Missions_contacts WHERE contact_id = :contactId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':contactId', $contactId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionContacts = [];

        foreach ($rows as $row) {
            $missionContact = new MissionContact($this->pdo, $row['mission_id'], $row['contact_id']);
            $missionContacts[] = $missionContact;
        }

        self::$missionContacts[$contactId] = $missionContacts;

        return $missionContacts;
    }

    public function addContactToMission(string $missionId, string $contactId): ?MissionContact
    {
        $existingMissionContact = $this->findExistingMissionContact($missionId, $contactId);
        if ($existingMissionContact) {
            return $existingMissionContact;
        }

        $query = "INSERT INTO Missions_contacts (mission_id, contact_id) VALUES (:missionId, :contactId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':missionId', $missionId);
        $stmt->bindParam(':contactId', $contactId);
        $stmt->execute();

        $newMissionContact = new MissionContact($this->pdo, $missionId, $contactId);
        self::$missionContacts[$missionId][] = $newMissionContact;
        self::$missionContacts[$contactId][] = $newMissionContact;

        return $newMissionContact;
    }

    public function updateContactProperties(array $propertiesToUpdate): bool
    {
        $contactId = $this->getContactId();

        $updatedMissionAgents = [];

        foreach (self::$missionContacts[$contactId] as $missionContact) {
            foreach ($propertiesToUpdate as $property => $value) {
                if ($missionContact->$property !== $value) {
                    $missionContact->$property = $value;
                }
            }
            $updatedMissionContacts[] = $missionContact;
        }

        $query = "UPDATE Missions_contacts SET contact_id = :newContactId WHERE mission_id = :missionId AND contact_id = :contactId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':newContactId', $this->contactId);
        $stmt->bindParam(':missionId', $this->missionId);
        $stmt->bindParam(':contactId', $contactId);
        $stmt->execute();

        self::$missionContacts[$contactId] = $updatedMissionContacts;

        return true;
    }

    public function deleteContactsByMissionId(string $missionId): bool
    {
        $query = "DELETE FROM Missions_contacts WHERE mission_id = :missionId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->execute();

        unset(self::$missionContacts[$missionId]);

        return true;
    }

    public function deleteMissionsByContactId(string $contactId): bool
    {
        $query = "DELETE FROM Missions_contacts WHERE contact_id = :contactId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':contactId', $contactId);
        $stmt->execute();

        unset(self::$missionContacts[$contactId]);

        return true;
    }

    public function getMissionId(): string
    {
        return $this->missionId;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }
}