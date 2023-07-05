<?php

namespace app\classes;

require_once "Speciality.php";
require_once "MissionStatus.php";
require_once "MissionType.php";
require_once "MissionAgent.php";
require_once "MissionContact.php";
require_once "MissionTarget.php";
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
    private ?Speciality $speciality;
    private ?MissionStatus $missionStatus;
    private ?MissionType $missionType;
    private \PDO $pdo;

    private static array $missions = [];

    public function __construct($pdo, string $id = '', string $title = '', string $description = '', string $codeName = '', string $country = '', string $startDate = '', string $endDate = '', Speciality $speciality = null, MissionStatus $missionStatus = null, MissionType $missionType = null)    {
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

    /**
     * Récupère toutes les missions.
     *
     * @return array   Un tableau contenant toutes les missions.
     */
    public function getAllMissions(): array
    {
        // Requête SQL pour récupérer toutes les missions
        $query = "SELECT * FROM Missions";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
    
        // Récupérer les données des missions
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
            $specialityId = $missionData['speciality_id'];
            $missionStatusId = $missionData['missionstatuses_id'];
            $missionTypeId = $missionData['missiontype_id'];
    
            // Vérifier si la mission existe déjà dans la liste des missions
            if (!isset(self::$missions[$id])) {
                // Récupérer les objets Speciality, MissionStatus et MissionType correspondants à partir de leurs identifiants
                if (!$this->speciality) {
                    $this->speciality = new Speciality($this->pdo);
                }
                $speciality = $this->speciality->getSpecialityById($this->pdo, $specialityId);

                if (!$this->missionStatus) {
                    $this->missionStatus = new MissionStatus($this->pdo);
                }
                $missionStatus = $this->missionStatus->getMissionStatusById($this->pdo, $missionStatusId);

                if (!$this->missionType) {
                    $this->missionType = new MissionType($this->pdo);
                }
                $missionType = $this->missionType->getMissionTypeById($this->pdo, $missionTypeId);

    
                // Créer la mission en passant les objets récupérés
                $mission = new Mission($this->pdo, $id, $title, $description, $codeName, $country, $startDate, $endDate, $speciality, $missionStatus, $missionType);
                self::$missions[$id] = $mission;
            }
    
            // Ajouter la mission à la liste des missions à retourner
            $missions[] = self::$missions[$id];
        }
    
        // Retourner toutes les missions
        return $missions;
    }

    /**
     * Récupère une mission à partir de son ID.
     *
     * @param string $id L'ID de la mission à récupérer.
     * @return Mission|null Retourne l'objet Mission correspondant à l'ID spécifié, sinon null.
     */
    public function getMissionById($id)
    {
        // Vérifier si la mission est déjà présente dans le cache
        if (isset(self::$missions[$id])) {
            return self::$missions[$id];
        }

        // Requête pour récupérer les données de la mission depuis la base de données
        $query = "SELECT * FROM Missions WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        // Récupération des données de la mission
        $missionData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($missionData) {
            // Extraction des données de la mission
            $id = $missionData['id'];
            $title = $missionData['title'];
            $description = $missionData['description'];
            $codeName = $missionData['codeName'];
            $country = $missionData['country'];
            $startDate = $missionData['startDate'];
            $endDate = $missionData['endDate'];
            $specialityId = $missionData['speciality_id'];
            $missionStatusId = $missionData['missionstatuses_id'];
            $missionTypeId = $missionData['missiontype_id'];

            // Récupérer les objets Speciality, MissionStatus et MissionType correspondants à partir de leurs identifiants
            if (!$this->speciality) {
                $this->speciality = new Speciality($this->pdo);
            }
            $speciality = $this->speciality->getSpecialityById($this->pdo, $specialityId);

            if (!$this->missionStatus) {
                $this->missionStatus = new MissionStatus($this->pdo);
            }
            $missionStatus = $this->missionStatus->getMissionStatusById($this->pdo, $missionStatusId);

            if (!$this->missionType) {
                $this->missionType = new MissionType($this->pdo);
            }
            $missionType = $this->missionType->getMissionTypeById($this->pdo, $missionTypeId);

            // Création d'une nouvelle instance de Mission
            $mission = new Mission($this->pdo, $id, $title, $description, $codeName, $country, $startDate, $endDate, $speciality, $missionStatus, $missionType);

            // Ajout de la mission au cache
            self::$missions[$id] = $mission;

            return $mission;
        }

        return null;
    }

    /**
     * Ajoute une nouvelle mission dans la base de données et dans la liste des missions.
     *
     * @param string      $title          Le titre de la mission.
     * @param string      $description    La description de la mission.
     * @param string      $codeName       Le code de la mission.
     * @param string      $country        Le pays de la mission.
     * @param string      $startDate      La date de début de la mission.
     * @param string      $endDate        La date de fin de la mission.
     * @param Speciality  $speciality     L'objet Speciality associé à la mission.
     * @param MissionStatus  $missionStatus  L'objet MissionStatus associé à la mission.
     * @param MissionType  $missionType    L'objet MissionType associé à la mission.
     * @return Mission|null                Retourne l'objet Mission si l'ajout a réussi, sinon null.
     */
    public function addMission(string $title, string $description, string $codeName, string $country, string $startDate, string $endDate, Speciality $speciality, MissionStatus $missionStatus, MissionType $missionType): ?Mission
    {
        // Générer un nouvel ID pour la mission
        $id = generateUUID();

        // Insérer la nouvelle mission dans la base de données
        $query = "INSERT INTO Missions (id, title, description, codeName, country, startDate, endDate, speciality_id, missionstatuses_id, missiontype_id) VALUES (:id, :title, :description, :codeName, :country, :startDate, :endDate, :specialityId, :missionStatusId, :missionTypeId)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':codeName', $codeName);
        $stmt->bindValue(':country', $country);
        $stmt->bindValue(':startDate', $startDate);
        $stmt->bindValue(':endDate', $endDate);
        $stmt->bindValue(':specialityId', $speciality->getId());
        $stmt->bindValue(':missionStatusId', $missionStatus->getId());
        $stmt->bindValue(':missionTypeId', $missionType->getId());
        $stmt->execute();

        // Créer une nouvelle instance de Mission
        $newMission = new Mission($this->pdo, $id, $title, $description, $codeName, $country, $startDate, $endDate, $speciality, $missionStatus, $missionType);

        // Ajouter la nouvelle mission au tableau des missions
        self::$missions[$id] = $newMission;

        return $newMission;
    }

    /**
     * Met à jour les propriétés de la mission dans la base de données et dans la classe.
     *
     * @param array $propertiesToUpdate   Les propriétés à mettre à jour avec leurs nouvelles valeurs.
     * @return bool                      Retourne true si la mise à jour a réussi, sinon false.
     */
    public function updateMissionProperties(array $propertiesToUpdate): bool
    {
        $id = $this->getId();

        foreach ($propertiesToUpdate as $property => $value) {
            // Vérifier si la propriété est 'specialityId'
            if ($property === 'specialityId') {
                // Récupérer l'objet Speciality correspondant à la nouvelle valeur de 'specialityId'
                $speciality = $this->getSpeciality()->getSpecialityById($this->pdo, $value);
                if ($speciality) {
                    // Mettre à jour la propriété 'speciality' de la mission avec le nouvel objet Speciality
                    $this->setSpeciality($speciality);
                }
            }
            // Vérifier si la propriété est 'missionStatusId'
            elseif ($property === 'missionStatusId') {
                // Récupérer l'objet MissionStatus correspondant à la nouvelle valeur de 'missionStatusId'
                $missionStatus = $this->getMissionStatus()->getMissionStatusById($this->pdo, $value);
                if ($missionStatus) {
                    // Mettre à jour la propriété 'missionStatus' de la mission avec le nouvel objet MissionStatus
                    $this->setMissionStatus($missionStatus);
                }
            }
            // Vérifier si la propriété est 'missionTypeId'
            elseif ($property === 'missionTypeId') {
                // Récupérer l'objet MissionType correspondant à la nouvelle valeur de 'missionTypeId'
                $missionType = $this->getMissionType()->getMissionTypeById($this->pdo, $value);
                if ($missionType) {
                    // Mettre à jour la propriété 'missionType' de la mission avec le nouvel objet MissionType
                    $this->setMissionType($missionType);
                }
            }
            else {
                // Pour les autres propriétés, mettre à jour leur valeur si elle est différente de la nouvelle valeur
                if ($this->$property !== $value) {
                    $this->$property = $value;
                }
            }
        }

        // Requête SQL pour mettre à jour les propriétés de la mission dans la base de données
        $query = "UPDATE Missions SET title = :title, description = :description, codeName = :codeName, country = :country, startDate = :startDate, endDate = :endDate, speciality_id = :specialityId, missionstatuses_id = :missionStatusId, missiontype_id = :missionTypeId WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':title', $this->title);
        $stmt->bindValue(':description', $this->description);
        $stmt->bindValue(':codeName', $this->codeName);
        $stmt->bindValue(':country', $this->country);
        $stmt->bindValue(':startDate', $this->startDate);
        $stmt->bindValue(':endDate', $this->endDate);
        $stmt->bindValue(':specialityId', $this->speciality->getId());
        $stmt->bindValue(':missionStatusId', $this->missionStatus->getId());
        $stmt->bindValue(':missionTypeId', $this->missionType->getId());
        $stmt->execute();

        // Mettre à jour la mission dans la liste des missions
        self::$missions[$id] = $this;

        return true;
    }

    /**
     * Supprime une mission à partir de son ID.
     *
     * @param string $id L'ID de la mission à supprimer.
     * @return bool Retourne true si la mission a été supprimée avec succès, sinon false.
     */
    public function deleteMissionById($id): bool
    {
        // Vérifier si l'ID existe en base de données
        $query = "SELECT * FROM Missions WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return false;
        }

        // Supprimer la mission de la base de données
        $query = "DELETE FROM Missions WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
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