<?php

namespace app\classes;

class MissionContact
{
    private string $missionId;
    private string $contactId;
    private static \PDO $pdo;

    private static array $missionContacts = [];

    public function __construct(\PDO $pdo, string $missionId = '', string $contactId = '')
    {
        self::$pdo = $pdo;
        $this->missionId = $missionId;
        $this->contactId = $contactId;

        if (!isset(self::$missionContacts[$missionId])) {
            self::$missionContacts[$missionId] = [];
        }

        if (!isset(self::$missionContacts[$contactId])) {
            self::$missionContacts[$contactId] = [];
        }

        self::$missionContacts[$missionId][] = $this;
        self::$missionContacts[$contactId][] = $this;
    }

    /**
     * Récupère toutes les associations entre missions et contacts.
     *
     * @return array Un tableau contenant toutes les associations MissionContact.
     */
    public static function getAllMissionContacts(): array
    {
        $query = "SELECT mission_id, contact_id FROM Missions_contacts";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $missionContactsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missionContacts = [];

        foreach ($missionContactsData as $missionContactData) {
            $missionId = $missionContactData['mission_id'];
            $contactId = $missionContactData['contact_id'];

            $missionContact = new MissionContact(self::$pdo, $missionId, $contactId);
            $missionContacts[] = $missionContact;
        }

        return $missionContacts;
    }

    /**
     * Récupère tous les contacts associés à une mission spécifique.
     *
     * @param string $missionId L'ID de la mission.
     * @return array Un tableau d'instances MissionContact représentant les contacts associés à la mission.
     */
    public static function getContactsByMissionId(string $missionId): array
    {
        if (isset(self::$missionContacts[$missionId])) {
            return self::$missionContacts[$missionId];
        }

        $query = "SELECT * FROM Missions_contacts WHERE mission_id = :missionId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionContacts = [];

        foreach ($rows as $row) {
            $missionContact = new MissionContact(self::$pdo, $row['mission_id'], $row['contact_id']);
            $missionContacts[] = $missionContact;
        }

        self::$missionContacts[$missionId] = $missionContacts;

        return $missionContacts;
    }

    /**
     * Récupère toutes les missions associées à un contact spécifique.
     *
     * @param string $contactId L'ID du contact.
     * @return array Un tableau d'instances MissionContact représentant les missions associées au contact.
     */
    public static function getMissionsByContactId(string $contactId): array
    {
        if (isset(self::$missionContacts[$contactId])) {
            return self::$missionContacts[$contactId];
        }

        $query = "SELECT * FROM Missions_contacts WHERE contact_id = :contactId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':contactId', $contactId);
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $missionContacts = [];

        foreach ($rows as $row) {
            $missionContact = new MissionContact(self::$pdo, $row['mission_id'], $row['contact_id']);
            $missionContacts[] = $missionContact;
        }

        self::$missionContacts[$contactId] = $missionContacts;

        return $missionContacts;
    }

    /**
     * Met à jour les propriétés d'un contact dans toutes les missions associées.
     *
     * @param string $missionId L'ID de la mission.
     * @param array $propertiesToUpdate Les propriétés à mettre à jour sous la forme [nomPropriete => nouvelleValeur].
     * @return bool Indique si la mise à jour a réussi ou non.
     */
    public static function updateMissionContactProperties(string $missionId, array $propertiesToUpdate): bool
    {
        $updatedMissionContacts = [];

        foreach (self::$missionContacts[$missionId] as $missionContact) {
            foreach ($propertiesToUpdate as $property => $value) {
                if ($property === 'contactId' && is_array($value) && isset($value[0])) {
                    $missionContact->setContactId($value[0]);
                } else {
                    if ($missionContact->$property !== $value) {
                        $missionContact->$property = $value;
                    }
                }
            }
            $updatedMissionContacts[] = $missionContact;
        }

        $query = "UPDATE Missions_contacts SET contact_id = :newContactId WHERE mission_id = :missionId AND contact_id = :contactId";
        $stmt = self::$pdo->prepare($query);

        foreach ($propertiesToUpdate['contactId'] as $index => $contactId) {
            $stmt->bindValue(':newContactId', $contactId);
            $stmt->bindValue(':missionId', $missionId);
            $stmt->bindValue(':contactId', $contactId);
            $stmt->execute();
        }

        self::$missionContacts[$missionId] = $updatedMissionContacts;

        return true;
    }

    /**
     * Supprime tous les contacts associés à une mission donnée.
     *
     * @param string $missionId L'ID de la mission.
     * @return bool Indique si la suppression a réussi ou non.
     */
    public static function deleteContactsByMissionId(string $missionId): bool
    {
        $query = "DELETE FROM Missions_contacts WHERE mission_id = :missionId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':missionId', $missionId);
        $stmt->execute();

        unset(self::$missionContacts[$missionId]);

        return true;
    }

    /**
     * Supprime toutes les missions associées à un contact donné.
     *
     * @param string $contactId L'ID du contact.
     * @return bool Indique si la suppression a réussi ou non.
     */
    public static function deleteMissionsByContactId(string $contactId): bool
    {
        $query = "DELETE FROM Missions_contacts WHERE contact_id = :contactId";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':contactId', $contactId);
        $stmt->execute();

        unset(self::$missionContacts[$contactId]);

        return true;
    }

    // Getters
    public function getMissionId(): string
    {
        return $this->missionId;
    }

    public function setMissionId(string $missionId): void
    {
        $this->missionId = $missionId;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function setContactId(string $contactId): void
    {
        $this->contactId = $contactId;
    }
}