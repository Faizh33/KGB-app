<?php 

namespace app\classes;

class MissionContact
{
    private string $missionId;
    private string $contactId;
    private \PDO $pdo;

    private static array $missionContacts = [];

    public function __construct(\PDO $pdo, string $missionId = '', string $contactId = '')
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

    /**
     * Recherche une instance existante de MissionContact correspondant à une association mission_id et contact_id donnée.
     *
     * @param string $missionId L'ID de la mission.
     * @param string $contactId L'ID du contact.
     * @return MissionContact|null L'instance MissionContact correspondante, ou null si aucune instance n'est trouvée.
     */
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

    /**
     * Récupère toutes les associations entre missions et contacts.
     *
     * @return array Un tableau contenant toutes les associations MissionContact.
     */
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

    /**
     * Récupère tous les contacts associés à une mission spécifique.
     *
     * @param string $missionId L'ID de la mission.
     * @return array Un tableau d'instances MissionContact représentant les contacts associés à la mission.
     */
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

    /**
     * Récupère toutes les missions associées à un contact spécifique.
     *
     * @param string $contactId L'ID du contact.
     * @return array Un tableau d'instances MissionContact représentant les missions associées au contact.
     */
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

    /**
     * Ajoute un contact à une mission spécifique.
     *
     * @param string $missionId L'ID de la mission.
     * @param string $contactId L'ID du contact.
     * @return MissionContact|null L'instance de MissionContact représentant l'association entre la mission et le contact, ou null en cas d'erreur.
     */
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

    /**
     * Met à jour les propriétés d'un contact dans toutes les missions associées.
     *
     * @param array $propertiesToUpdate Les propriétés à mettre à jour sous la forme [nomPropriete => nouvelleValeur].
     * @return bool Indique si la mise à jour a réussi ou non.
     */
    public function updateContactProperties(string $missionId, array $propertiesToUpdate): bool
    {
        $contactId = $this->getContactId();

        $updatedMissionContacts = [];

        foreach (self::$missionContacts[$missionId] as $missionContact) {
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
        $stmt->bindParam(':missionId', $missionId);
        $stmt->bindParam(':contactId', $contactId);
        $stmt->execute();

        self::$missionContacts[$missionId] = $updatedMissionContacts;

        return true;
    }

    /**
     * Supprime tous les contacts associés à une mission donnée.
     *
     * @param string $missionId L'ID de la mission.
     * @return bool Indique si la suppression a réussi ou non.
     */
    public function deleteContactsByMissionId(string $missionId): bool
    {
        $query = "DELETE FROM Missions_contacts WHERE mission_id = :missionId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':missionId', $missionId);
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
    public function deleteMissionsByContactId(string $contactId): bool
    {
        $query = "DELETE FROM Missions_contacts WHERE contact_id = :contactId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':contactId', $contactId);
        $stmt->execute();

        unset(self::$missionContacts[$contactId]);

        return true;
    }

    //Getters
    public function getMissionId(): string
    {
        return $this->missionId;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }
}