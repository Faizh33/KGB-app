<?php

namespace app\classes;

require_once "Speciality.php";
require_once "MissionStatus.php";
require_once "MissionType.php";
require_once "MissionAgent.php";
require_once "MissionContact.php";
require_once "MissionTarget.php";
require_once "MissionSafeHouse.php";
require_once "CountryNationality.php";

class Mission
{
    private string $id;
    private string $title;
    private string $description;
    private string $codeName;
    private string $startDate;
    private string $endDate;
    private ?CountryNationality $country;
    private ?Speciality $speciality;
    private ?MissionStatus $missionStatus;
    private ?MissionType $missionType;
    private static \PDO $pdo;

    private static array $missions = [];

    public function __construct($pdo, string $id = '', string $title = '', string $description = '', string $codeName = '', string $startDate = '', string $endDate = '', ?CountryNationality $country = null, ?Speciality $speciality = null, ?MissionStatus $missionStatus = null, ?MissionType $missionType = null)
    {
        self::$pdo = $pdo;
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->codeName = $codeName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->country = $country;
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
    public static function getAllMissions(): array
    {
        // Requête SQL pour récupérer toutes les missions
        $query = "SELECT * FROM Missions";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        // Récupérer les données des missions
        $missionsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $missions = [];
        foreach ($missionsData as $missionData) {
            $id = $missionData['id'];
            $title = $missionData['title'];
            $description = $missionData['description'];
            $codeName = $missionData['codeName'];
            $startDate = $missionData['startDate'];
            $endDate = $missionData['endDate'];
            $countryId = $missionData['countrynationality_id'];
            $specialityId = $missionData['speciality_id'];
            $missionStatusId = $missionData['missionstatuses_id'];
            $missionTypeId = $missionData['missiontype_id'];

            // Vérifier si la mission existe déjà dans la liste des missions
            if (!isset(self::$missions[$id])) {
                // Récupérer l'objet Speciality, MissionStatus et MissionType correspondant à partir de leurs identifiants
                $countryNationalityObj = new CountryNationality(self::$pdo);
                $country = $countryNationalityObj::getCountryNationalityById($countryId);
                $speciality = Speciality::getSpecialityById($specialityId);
                $missionStatus = MissionStatus::getMissionStatusById($missionStatusId);
                $missionType = MissionType::getMissionTypeById($missionTypeId);

                // Créer la mission en passant les objets récupérés
                $mission = new Mission(self::$pdo, $id, $title, $description, $codeName, $startDate, $endDate, $country, $speciality, $missionStatus, $missionType);
                self::$missions[$id] = $mission;
            }

            // Ajouter la mission à la liste des missions à retourner
            $missions[] = self::$missions[$id];
        }

        // Retourner toutes les missions
        return $missions;
    }

     /**
     * Récupère toutes les missions et créer une pagination.
     *
     * @return array   Un tableau contenant toutes les missions.
     */
    public static function getAllMissionsPagination(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
    
        // Obtenir le tri souhaité à partir du paramètre GET
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
        $sortDirection = 'ASC'; // Direction de tri par défaut (croissant)
    
        // Options de tri
        $validSortOptions = ['startDate', 'title', 'codeName', 'missionstatuses_id'];
        $sortField = in_array($sort, $validSortOptions) ? $sort : null;
    
        if ($sortField) {
            // Déterminer la direction de tri en fonction du paramètre URL
            if (isset($_GET['sortDir']) && $_GET['sortDir'] === 'desc') {
                $sortDirection = 'DESC';
            }
        }
    
        // Requête SQL pour récupérer toutes les missions avec pagination et tri
        $query = "SELECT * FROM Missions";
    
        if ($sortField) {
            // Ajouter le tri à la requête SQL
            $query .= " ORDER BY $sortField $sortDirection";
        }
    
        $query .= " LIMIT :offset, :perPage";
    
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, \PDO::PARAM_INT);
        $stmt->execute();

        // Récupérer les données des missions
        $missionsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Obtenir le tri souhaité à partir du paramètre GET
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;

        // Options de tri
        $validSortOptions = ['startDate', 'title', 'codeName', 'missionstatuses_id'];
        $sortField = in_array($sort, $validSortOptions) ? $sort : null;
        $sortDirection = 'ASC'; // Direction de tri par défaut (croissant)

        if ($sortField) {
            // Déterminer la direction de tri en fonction du paramètre URL
            if (isset($_GET['sortDir']) && $_GET['sortDir'] === 'desc') {
                $sortDirection = 'DESC';
            }

            // Fonction de comparaison pour le tri des missions
            $compareMissions = function ($a, $b) use ($sortField, $sortDirection) {
                switch ($sortField) {
                    case 'startDate':
                        $propA = $a->getStartDate();
                        $propB = $b->getStartDate();
                        break;
                    case 'title':
                        $propA = $a->getTitle();
                        $propB = $b->getTitle();
                        break;
                    case 'codeName':
                        $propA = $a->getCodeName();
                        $propB = $b->getCodeName();
                        break;
                    case 'missionstatuses_id':
                        $propA = $a->getMissionStatus()->getStatus();
                        $propB = $b->getMissionStatus()->getStatus();
                        break;
                    default:
                        $propA = null;
                        $propB = null;
                        break;
                }

                // Effectuer le tri en fonction de la direction spécifiée
                if ($sortDirection === 'asc') {
                    return $propA <=> $propB;
                } else {
                    return $propB <=> $propA;
                }
            };

            // Trier les missions en utilisant la fonction de comparaison
            usort($missionsData, function ($a, $b) use ($compareMissions) {
                if (!is_object($a) || !is_object($b)) {
                    return 0;
                }
                return $compareMissions($a, $b);
            });
        }

        $missions = [];
        foreach ($missionsData as $missionData) {
            $id = $missionData['id'];
            $title = $missionData['title'];
            $description = $missionData['description'];
            $codeName = $missionData['codeName'];
            $startDate = $missionData['startDate'];
            $endDate = $missionData['endDate'];
            $countryId = $missionData['countrynationality_id'];
            $specialityId = $missionData['speciality_id'];
            $missionStatusId = $missionData['missionstatuses_id'];
            $missionTypeId = $missionData['missiontype_id'];

            // Vérifier si la mission existe déjà dans la liste des missions
            if (!isset(self::$missions[$id])) {
                // Récupérer l'objet Speciality, MissionStatus et MissionType correspondant à partir de leurs identifiants
                $countryNationalityObj = new CountryNationality(self::$pdo);
                $country = $countryNationalityObj::getCountryNationalityById($countryId);
                $speciality = Speciality::getSpecialityById($specialityId);
                $missionStatus = MissionStatus::getMissionStatusById($missionStatusId);
                $missionType = MissionType::getMissionTypeById($missionTypeId);

                // Créer la mission en passant les objets récupérés
                $mission = new Mission(self::$pdo, $id, $title, $description, $codeName, $startDate, $endDate, $country, $speciality, $missionStatus, $missionType);
                self::$missions[$id] = $mission;
            }

            // Ajouter la mission à la liste des missions à retourner
            $missions[] = self::$missions[$id];
        }

        // Retourner toutes les missions
        return $missions;
    }

    public static function countMissions(): int
    {
        $query = "SELECT COUNT(*) AS total FROM Missions";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $result['total'];
    }

    /**
     * Récupère une mission à partir de son ID.
     *
     * @param string $id L'ID de la mission à récupérer.
     * @return Mission|null Retourne l'objet Mission correspondant à l'ID spécifié, sinon null.
     */
    public static function getMissionById($id)
    {
        // Vérifier si la mission est déjà présente dans le cache
        if (isset(self::$missions[$id])) {
            return self::$missions[$id];
        }

        // Requête pour récupérer les données de la mission depuis la base de données
        $query = "SELECT * FROM Missions WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        // Récupération des données de la mission
        $missionData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($missionData) {
            // Extraction des données de la mission
            $id = $missionData['id'];
            $title = $missionData['title'];
            $description = $missionData['description'];
            $codeName = $missionData['codeName'];
            $startDate = $missionData['startDate'];
            $endDate = $missionData['endDate'];
            $countryId = $missionData['countrynationality_id'];
            $specialityId = $missionData['speciality_id'];
            $missionStatusId = $missionData['missionstatuses_id'];
            $missionTypeId = $missionData['missiontype_id'];

            // Récupérer les objets Speciality, MissionStatus et MissionType correspondants à partir de leurs identifiants
            $countryNationalityObj = new CountryNationality(self::$pdo);
            $country = $countryNationalityObj::getCountryNationalityById($countryId);
            $specialityObj = new Speciality(self::$pdo);
            $speciality = $specialityObj::getSpecialityById($specialityId);
            $missionStatusObj = new MissionStatus(self::$pdo);
            $missionStatus = $missionStatusObj::getMissionStatusById($missionStatusId);
            $missionTypeObj = new MissionType(self::$pdo);
            $missionType = $missionTypeObj::getMissionTypeById($missionTypeId);

            // Création d'une nouvelle instance de Mission
            $mission = new Mission(self::$pdo, $id, $title, $description, $codeName, $startDate, $endDate, $country, $speciality, $missionStatus, $missionType);

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
     * @param CountryNationality  $country        Le pays de la mission.
     * @param string      $startDate      La date de début de la mission.
     * @param string      $endDate        La date de fin de la mission.
     * @param Speciality  $speciality     L'objet Speciality associé à la mission.
     * @param MissionStatus  $missionStatus  L'objet MissionStatus associé à la mission.
     * @param MissionType  $missionType    L'objet MissionType associé à la mission.
     * @return Mission|null                Retourne l'objet Mission si l'ajout a réussi, sinon null.
     */
    public static function addMission(string $title, string $description, string $codeName, CountryNationality $country, string $startDate, string $endDate, Speciality $speciality, MissionStatus $missionStatus, MissionType $missionType): ?Mission
    {
        // Générer un nouvel ID pour la mission
        $id = generateUUID();

        // Insérer la nouvelle mission dans la base de données
        $query = "INSERT INTO Missions (id, title, description, codeName, countrynationality_id, startDate, endDate, speciality_id, missionstatuses_id, missiontype_id) VALUES (:id, :title, :description, :codeName, :countryId, :startDate, :endDate, :specialityId, :missionStatusId, :missionTypeId)";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':codeName', $codeName);
        $stmt->bindValue(':startDate', $startDate);
        $stmt->bindValue(':endDate', $endDate);
        $stmt->bindValue(':countryId', $country->getId());
        $stmt->bindValue(':specialityId', $speciality->getId());
        $stmt->bindValue(':missionStatusId', $missionStatus->getId());
        $stmt->bindValue(':missionTypeId', $missionType->getId());
        $stmt->execute();

        // Créer une nouvelle instance de Mission
        $newMission = new Mission(self::$pdo, $id, $title, $description, $codeName, $startDate, $endDate, $country, $speciality, $missionStatus, $missionType);

        // Ajouter la nouvelle mission au tableau des missions
        self::$missions[$id] = $newMission;

        return $newMission;
    }

    /**
     * Met à jour les propriétés de la mission dans la base de données et dans la classe.
     *
     * @param Mission $mission               La mission à mettre à jour.
     * @param array $propertiesToUpdate      Les propriétés à mettre à jour avec leurs nouvelles valeurs.
     * @return bool                          Retourne true si la mise à jour a réussi, sinon false.
     */
    public static function updateMissionProperties(Mission $mission, array $propertiesToUpdate): bool
    {
        $id = $mission->getId();

        foreach ($propertiesToUpdate as $property => $value) {
            // Vérifier si la propriété est 'specialityId'
            if ($property === 'specialityId') {
                // Récupérer l'objet Speciality correspondant à la nouvelle valeur de 'specialityId'
                $speciality = Speciality::getSpecialityById($value);
                if ($speciality) {
                    // Mettre à jour la propriété 'speciality' de la mission avec le nouvel objet Speciality
                    $mission->setSpeciality($speciality);
                }
            }
            // Vérifier si la propriété est 'missionStatusId'
            elseif ($property === 'missionStatusId') {
                // Récupérer l'objet MissionStatus correspondant à la nouvelle valeur de 'missionStatusId'
                $missionStatus = MissionStatus::getMissionStatusById($value);
                if ($missionStatus) {
                    // Mettre à jour la propriété 'missionStatus' de la mission avec le nouvel objet MissionStatus
                    $mission->setMissionStatus($missionStatus);
                }
            }
            // Vérifier si la propriété est 'missionTypeId'
            elseif ($property === 'missionTypeId') {
                // Récupérer l'objet MissionType correspondant à la nouvelle valeur de 'missionTypeId'
                $missionType = MissionType::getMissionTypeById($value);
                if ($missionType) {
                    // Mettre à jour la propriété 'missionType' de la mission avec le nouvel objet MissionType
                    $mission->setMissionType($missionType);
                }
            }
            // Vérifier si la propriété est 'country'
            elseif ($property === 'country') {
                // Récupérer l'objet Country correspondant à la nouvelle valeur de 'country'
                $country = CountryNationality::getCountryNationalityById($value);
                if ($country) {
                    // Mettre à jour la propriété 'country' de la mission avec le nouvel objet Country
                    $mission->setCountry($country);
                }
            } else {
                // Pour les autres propriétés, mettre à jour leur valeur si elle est différente de la nouvelle valeur
                if ($mission->$property !== $value) {
                    $mission->$property = $value;
                }
            }
        }

        // Requête SQL pour mettre à jour les propriétés de la mission dans la base de données
        $query = "UPDATE Missions SET title = :title, description = :description, codeName = :codeName, startDate = :startDate, endDate = :endDate, countrynationality_id = :countryId, speciality_id = :specialityId, missionstatuses_id = :missionStatusId, missiontype_id = :missionTypeId WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':title', $mission->getTitle());
        $stmt->bindValue(':description', $mission->getDescription());
        $stmt->bindValue(':codeName', $mission->getCodeName());
        $stmt->bindValue(':startDate', $mission->getStartDate());
        $stmt->bindValue(':endDate', $mission->getEndDate());
        $stmt->bindValue(':countryId', $mission->getCountry()->getId());
        $stmt->bindValue(':specialityId', $mission->getSpeciality()->getId());
        $stmt->bindValue(':missionStatusId', $mission->getMissionStatus()->getId());
        $stmt->bindValue(':missionTypeId', $mission->getMissionType()->getId());
        $stmt->execute();

        return true;
    }

    /**
     * Supprime une mission à partir de son ID.
     *
     * @param string $id L'ID de la mission à supprimer.
     * @return json
     */
    public static function deleteMissionById($id)
    {
        // Supprimer la mission de la base de données
        $query = "DELETE FROM Missions WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Supprimer les associations agent/mission correspondantes en utilisant une instance de la classe MissionAgent
        $missionAgentObj = new MissionAgent(self::$pdo);
        $missionAgentObj::deleteAgentsByMissionId($id);

        // Supprimer les associations contact/mission correspondantes en utilisant une instance de la classe MissionContact
        $missionContactObj = new MissionContact(self::$pdo);
        $missionContactObj::deleteContactsByMissionId($id);

        // Supprimer les associations contact/mission correspondantes en utilisant une instance de la classe MissionContact
        $missionTargetObj = new MissionTarget(self::$pdo);
        $missionTargetObj::deleteTargetsByMissionId($id);

        // Supprimer les associations contact/mission correspondantes en utilisant une instance de la classe MissionContact
        $missionSafeHouseObj = new MissionSafeHouse(self::$pdo);
        $missionSafeHouseObj::deleteSafeHousesByMissionId($id);

        // Supprimer la mission de la classe
        if (isset(self::$missions[$id])) {
            unset(self::$missions[$id]);
            echo json_encode(array('status' => 'success'));
            exit;
        }

        echo json_encode(array('status' => 'error'));
        exit;
    }

    // Getters et Setters pour les propriétés de la classe

    public function getId(): string
    {
        return $this->id;
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

    public function getCountry(): ?CountryNationality
    {
        return $this->country;
    }

    public function setCountry(?CountryNationality $country): void
    {
        $this->country = $country;
    }

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(?Speciality $speciality): void
    {
        $this->speciality = $speciality;
    }

    public function getMissionStatus(): ?MissionStatus
    {
        return $this->missionStatus;
    }

    public function setMissionStatus(?MissionStatus $missionStatus): void
    {
        $this->missionStatus = $missionStatus;
    }

    public function getMissionType(): ?MissionType
    {
        return $this->missionType;
    }

    public function setMissionType(?MissionType $missionType): void
    {
        $this->missionType = $missionType;
    }
}
