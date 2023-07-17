<?php

namespace app\classes;

require_once 'CountryNationality.php';

class SafeHouse
{
    private int $id;
    private string $code;
    private string $address;
    private string $type;
    private ?CountryNationality $country;
    private static \PDO $pdo;

    private static array $safeHouses = [];

    public function __construct(\PDO $pdo, int $id = null, string $code = '', string $address = '', string $type = '', ?CountryNationality $country = null)
    {
        self::$pdo = $pdo;
        $this->id = $id ?? 0;
        $this->code = $code;
        $this->address = $address;
        $this->country = $country;
        $this->type = $type;

        self::$safeHouses[$id] = $this;
    }

    /**
     * Récupère une SafeHouse à partir de son identifiant.
     *
     * @param mixed $id L'identifiant de la SafeHouse.
     * @return SafeHouse|null La SafeHouse correspondante ou null si non trouvée.
     */
    public static function getSafeHouseById(int $id): ?SafeHouse
    {
        if (isset(self::$safeHouses[$id])) {
            return self::$safeHouses[$id];
        }

        $query = "SELECT * FROM SafeHouses WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $safeHouseData = $stmt->fetch(\PDO::FETCH_ASSOC);

        $id = $safeHouseData['id'];
        $code = $safeHouseData['code'];
        $address = $safeHouseData['address'];
        $type = $safeHouseData['type'];
        $countryId = $safeHouseData['countrynationality_id'];

        if ($safeHouseData) {
            $countryNationalityObj = new CountryNationality(self::$pdo);
            $country = $countryNationalityObj::getCountryNationalityById($countryId);
            $safeHouse = new SafeHouse( self::$pdo, $id, $code, $address, $type, $country);

            self::$safeHouses[$id] = $safeHouse;

            return $safeHouse;
        }

        return null;
    }

    /**
     * Récupère toutes les SafeHouses de la base de données.
     *
     * @return array Les SafeHouses.
     */
    public static function getAllSafeHouses(): array
    {
        $query = "SELECT * FROM SafeHouses";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $safeHousesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $safeHouses = [];
        foreach ($safeHousesData as $safeHouseData) {
            $id = $safeHouseData['id'];
            $code = $safeHouseData['code'];
            $address = $safeHouseData['address'];
            $type = $safeHouseData['type'];
            $countryId = $safeHouseData['countrynationality_id'];

            $countryNationalityObj = new CountryNationality(self::$pdo);
            $country = $countryNationalityObj::getCountryNationalityById($countryId);

            if (!isset(self::$safeHouses[$id])) {
                $safeHouse = new SafeHouse(self::$pdo, $id, $code, $address, $type, $country);

                self::$safeHouses[$id] = $safeHouse;
            }

            $safeHouses[] = self::$safeHouses[$id];
        }

        return $safeHouses;
    }

    /**
     * Récupère toutes les SafeHouses de la base de données.
     *
     * @return array Les SafeHouses.
     */
    public static function getAllSafeHousesPagination(int $page, int $perPage): array
    {
        // Calculer l'offset pour la page spécifiée
        $offset = ($page - 1) * $perPage;

        // Requête SQL avec les clauses LIMIT et OFFSET
        $query = "SELECT * FROM SafeHouses LIMIT :perPage OFFSET :offset";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':perPage', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $safeHousesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $safeHouses = [];
        foreach ($safeHousesData as $safeHouseData) {
            $id = $safeHouseData['id'];
            $code = $safeHouseData['code'];
            $address = $safeHouseData['address'];
            $type = $safeHouseData['type'];
            $countryId = $safeHouseData['countrynationality_id'];

            $countryNationalityObj = new CountryNationality(self::$pdo);
            $country = $countryNationalityObj::getCountryNationalityById($countryId);

            if (!isset(self::$safeHouses[$id])) {
                $safeHouse = new SafeHouse(self::$pdo, $id, $code, $address, $type, $country);

                self::$safeHouses[$id] = $safeHouse;
            }

            $safeHouses[] = self::$safeHouses[$id];
        }

        return $safeHouses;
    }

    public static function countSafeHouses(): int
    {
        $query = "SELECT COUNT(*) AS total FROM SafeHouses";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $result['total'];
    }

    /**
     * Ajoute une nouvelle SafeHouse.
     *
     * @param string $code Le code de la SafeHouse.
     * @param string $address L'adresse de la SafeHouse.
     * @param string $type Le type de la SafeHouse.
     * @param CountryNationality $country L'objet CountryNationality représentant le pays de la SafeHouse.
     * @return SafeHouse|null La nouvelle SafeHouse ajoutée, ou null si le code existe déjà.
     */
    public static function addSafeHouse(string $code, string $address, string $type, int $countryId): ?SafeHouse
    {
        // Vérifier si le code de la SafeHouse existe déjà dans la base de données
        $query = "SELECT * FROM SafeHouses WHERE code = :code";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':code', $code);
        $stmt->execute();

        $safeHouseData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($safeHouseData) {
            return null;
        }

        // Insérer la nouvelle SafeHouse dans la base de données et dans la classe
        $query = "INSERT INTO SafeHouses (code, address, type, countrynationality_id) VALUES (:code, :address, :type, :countryId)";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':code', $code);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':type', $type);
        $stmt->bindValue(':countryId', $countryId);
        $stmt->execute();

        // Récupérer l'objet CountryNationality correspondant à l'identifiant de nationalité
        $country = CountryNationality::getCountryNationalityById($countryId);

        $newSafeHouseId = self::$pdo->lastInsertId();

        $newSafeHouse = new SafeHouse(self::$pdo, $newSafeHouseId, $code, $address, $type, $country);

        self::$safeHouses[$newSafeHouseId] = $newSafeHouse;

        return $newSafeHouse;
    }

    /**
     * Met à jour les propriétés d'une SafeHouse dans la base de données et dans la classe.
     *
     * @param int $id L'identifiant de la SafeHouse à mettre à jour.
     * @param array $propertiesToUpdate Les propriétés à mettre à jour avec leurs nouvelles valeurs.
     * @return bool Indique si la mise à jour a été effectuée avec succès.
     */
    /**
     * Met à jour les propriétés d'une SafeHouse dans la base de données et dans la classe.
     *
     * @param int $id L'identifiant de la SafeHouse à mettre à jour.
     * @param array $propertiesToUpdate Les propriétés à mettre à jour avec leurs nouvelles valeurs.
     * @return bool Indique si la mise à jour a été effectuée avec succès.
     */
    public static function updateSafeHouseProperties(int $id, array $propertiesToUpdate): bool
    {
        // Récupérer l'instance de la safe house correspondant à l'ID
        $safeHouse = self::getSafeHouseById($id);

        if ($safeHouse) {
            // Mettre à jour les propriétés dans la classe
            foreach ($propertiesToUpdate as $property => $value) {
                if ($property === 'country') {
                    $country = CountryNationality::getCountryNationalityById($value);
                    $safeHouse->setCountry($country);
                } else {
                    $setterMethod = 'set' . ucfirst($property);
                    if (method_exists($safeHouse, $setterMethod)) {
                        $safeHouse->$setterMethod($value);
                    }
                }
            }

            // Mettre à jour les propriétés dans la base de données
            $query = "UPDATE SafeHouses SET code = :code, address = :address, type = :type, countrynationality_id = :countryId WHERE id = :id";
            $stmt = self::$pdo->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':code', $safeHouse->getCode());
            $stmt->bindValue(':address', $safeHouse->getAddress());
            $stmt->bindValue(':type', $safeHouse->getType());
            $stmt->bindValue(':countryId', $safeHouse->getCountry()->getId());
            $stmt->execute();

            // Mettre à jour le tableau $safeHouses
            self::$safeHouses[$id] = $safeHouse;

            return true;
        }

        return false;
    }
    

    /**
     * Supprime une SafeHouse de la base de données et de la classe en fonction de son ID.
     *
     * @param int $id L'identifiant de la SafeHouse à supprimer.
     * @return json
     */
    public static function deleteSafeHouseById($id)
    {
        // Vérifier si la planque est utilisée dans une ou plusieurs missions
        $query = "SELECT COUNT(*) FROM Missions_safeHouses WHERE safeHouse_id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        // Si la planque est utilisée ailleurs, ne pas le supprimer
        if ($count > 0) {
            echo json_encode(array('status' => 'used'));
            exit;
        }
        
        // Supprimer la SafeHouse de la base de données
        $query = "DELETE FROM SafeHouses WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Supprimer la SafeHouse de la classe
        if (isset(self::$safeHouses[$id])) {
            unset(self::$safeHouses[$id]);
            
            echo json_encode(array('status' => 'success'));
            exit;
        }

        echo json_encode(array('status' => 'error'));
        exit;
    }

    // Getters et Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getCountry(): ?CountryNationality
{
    return $this->country;
}

    public function setCountry(?CountryNationality $country): void
    {
        $this->country = $country;
    }
}
