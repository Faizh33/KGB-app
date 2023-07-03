<?php

namespace app\classes;

require_once "Speciality.php";
require_once "MissionStatus.php";
require_once "MissionType.php";
require_once '../helpers/dataHelpers.php';

class Mission
{
    private string $id;
    private string $title;
    private string $description;
    private string $codeName;
    private string $country;
    private string $startDate;
    private string $endDate;
    private Speciality $speciality;
    private MissionStatus $missionStatus;
    private MissionType $missionType;
    private $pdo;

    private static array $missions = [];

    public function __construct($pdo, string $id, string $title, string $description, string $codeName, string $country, string $startDate, string $endDate, Speciality $speciality, MissionStatus $missionStatus, MissionType $missionType)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->codeName = $codeName;
        $this->country = $country;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->speciality = $speciality;
        $this->missionStatus = $missionStatus;
        $this->missionType = $missionType;

        self::$missions[$id] = $this;
    }

    public function getAllMissions(): array
    {
        $query = "SELECT * FROM Missions";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $missionsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missions = [];
        foreach ($missionsData as $missionData) {
            $id = $missionData['id'];
            $title = $missionData['title'];
            $description = $missionData['description'];
            $codeName = $missionData['codeName'];
            $country = $missionData['country'];
            $startDate = $missionData['startDate'];
            $endDate = $missionData['endDate'];
            $speciality = $missionData['speciality_id'];
            $missionStatus = $missionData['missionstatuses_id'];
            $missionType = $missionData['missiontype_id'];

            if (!isset(self::$missions[$id])) {
                $mission = new Mission($this->pdo, $id, $title, $description, $codeName, $country, $startDate, $endDate, $speciality, $missionStatus, $missionType);
                self::$missions[$id] = $mission;
            }

            $missions[] = self::$missions[$id];
        }

        return $missions;
    }

    public function getMissionById($id)
    {
        if (isset(self::$missions[$id])) {
            return self::$missions[$id];
        }

        $query = "SELECT * FROM Missions WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            $mission = new Mission(
                $this->pdo,
                $row['id'],
                $row['title'],
                $row['description'],
                $row['codeName'],
                $row['country'],
                $row['startDate'],
                $row['endDate'],
                $this->speciality,
                $this->missionStatus,
                $this->missionType
            );

            self::$missions[$id] = $mission;

            return $mission;
        }

        return null;
    }


    public function addMission(string $title, string $description, string $codeName, string $country, string $startDate, string $endDate, Speciality $speciality, MissionStatus $missionStatus, MissionType $missionType): ?Mission
    {
        // Générer un nouvel ID pour la mission
        $id = generateUUID();

        // Insérer la nouvelle mission dans la base de données
        $query = "INSERT INTO Missions (id, title, description, codeName, country, startDate, endDate, speciality_id, missionstatuses_id, missiontype_id) VALUES (:id, :title, :description, :codeName, :country, :startDate, :endDate, :specialityId, :missionStatusId, :missionTypeId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':codeName', $codeName);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->bindParam(':speciality_id', $speciality);
        $stmt->bindParam(':missionstatuses_id', $missionStatus);
        $stmt->bindParam(':missiontype_id', $missionType);
        $stmt->execute();

        // Créer une nouvelle instance de Mission
        $newMission = new Mission($this->pdo, $id, $title, $description, $codeName, $country, $startDate, $endDate, $speciality, $missionStatus, $missionType);

        // Ajouter la nouvelle mission au tableau des missions
        self::$missions[$id] = $newMission;

        return $newMission;
    }

    // Méthode qui met à jour les propriétés de la mission dans la base de données et dans la classe
    public function updateProperties(array $propertiesToUpdate): bool
    {
        $id = $this->getId();

        foreach ($propertiesToUpdate as $property => $value) {
            if ($property === 'specialityId') {
                $speciality = $this->getSpeciality()->getSpecialityById($value);
                if ($speciality) {
                    $this->setSpeciality($speciality);
                }
            } elseif ($property === 'missionStatusId') {
                $missionStatus = $this->getMissionStatus()->getMissionStatusById($value);
                if ($missionStatus) {
                    $this->setMissionStatus($missionStatus);
                }
            } elseif ($property === 'missionTypeId') {
                $missionType = $this->getMissionType()->getMissionTypeById($value);
                if ($missionType) {
                    $this->setMissionType($missionType);
                }
            } else {
                if ($this->$property !== $value) {
                    $this->$property = $value;
                }
            }
        }

        $query = "UPDATE Missions SET title = :title, description = :description, codeName = :codeName, country = :country, startDate = :startDate, endDate = :endDate, speciality_id = :specialityId, missionstatuses_id = :missionStatusId, missiontype_id = :missionTypeId WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':codeName', $this->codeName);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':startDate', $this->startDate);
        $stmt->bindParam(':endDate', $this->endDate);
        $stmt->bindParam(':specialityId', $this->speciality->getId());
        $stmt->bindParam(':missionStatusId', $this->missionStatus->getId());
        $stmt->bindParam(':missionTypeId', $this->missionType->getId());
        $stmt->execute();

        self::$missions[$id] = $this;

        return true;
    }

    public function deleteMissionById($id): bool
    {
        // Vérifier si l'ID existe en base de données
        $query = "SELECT * FROM Missions WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }
    
        // Supprimer la mission de la base de données
        $query = "DELETE FROM Missions WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        // Supprimer les associations agent/mission correspondantes en utilisant une instance de la classe MissionAgent
        $missionAgent = new MissionAgent($this->pdo, '', $id);
        $missionAgent->deleteAgentsByMissionId($id);
    
        // Supprimer les associations contact/mission correspondantes en utilisant une instance de la classe MissionContact
        $missionContact = new MissionContact($this->pdo, '', $id);
        $missionContact->deleteContactsByMissionId($id);

        // Supprimer les associations contact/mission correspondantes en utilisant une instance de la classe MissionContact
        $missionTarget = new MissionTarget($this->pdo, '', $id);
        $missionTarget->deleteTargetsByMissionId($id);

        // Supprimer la mission de la classe
        if (isset(self::$missions[$id])) {
            unset(self::$missions[$id]);
            return true;
        }
    
        return false;
    }

    //Getters et Setters
    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCodeName(): string
    {
        return $this->codeName;
    }

    public function setCodeName(string $codeName): void
    {
        $this->codeName = $codeName;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function setEndDate(string $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getSpeciality(): Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(Speciality $speciality): void
    {
        $this->speciality = $speciality;
    }

    public function getMissionStatus(): MissionStatus
    {
        return $this->missionStatus;
    }

    public function setMissionStatus(MissionStatus $missionStatus): void
    {
        $this->missionStatus = $missionStatus;
    }

    public function getMissionType(): MissionType
    {
        return $this->missionType;
    }

    public function setMissionType(MissionType $missionType): void
    {
        $this->missionType = $missionType;
    }
}