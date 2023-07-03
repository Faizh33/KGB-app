<?php
namespace app\classes;

require_once("Mission.php");


class SafeHouse {
    private int $id;
    private string $code;
    private string $address;
    private string $country;
    private string $type;
    private ?Mission $mission;
    private $pdo;

    private static array $safeHouses = [];

    public function __construct($pdo, int $id, string $code, string $address, string $country, string $type, ?Mission $mission)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->code = $code;
        $this->address = $address;
        $this->country = $country;
        $this->type = $type;
        $this->mission = $mission;

        self::$safeHouses[$id] = $this;
    }

    //Méthode qui permet d'assigner une mission à une planque, dans la classe puis dans la base de données.
    public function assignMission(int $id, Mission $mission): void
    {
        if (isset(self::$safeHouses[$id])) {
            $safeHouse = self::$safeHouses[$id];
            $safeHouse->mission = $mission;
    
            // Mettre à jour mission_id en base de données
            $query = "UPDATE SafeHouses SET mission_id = :missionId WHERE id = :id";
            $stmt = $safeHouse->pdo->prepare($query);
            $stmt->execute(['missionId' => $mission->getId(), 'id' => $id]);
        }
    }

    //Méthode qui récupère une planque en fonction de son id
    public function getSafeHouseById($id)
    {
        if (isset(self::$safeHouses[$id])) {
            return self::$safeHouses[$id];
        }

        $query = "SELECT * FROM SafeHouses WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $safeHouseData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($safeHouseData) {
            $code = $safeHouseData['code'];
            $address = $safeHouseData['address'];
            $country = $safeHouseData['country'];
            $type = $safeHouseData['type'];
            $mission = $safeHouseData['mission_id'];

            $safeHouse = new SafeHouse($this->pdo, $id, $code, $address, $country, $type, $mission);
            self::$safeHouses[$id] = $safeHouse;
            return $safeHouse;
        }

        return null;
    }

    //Méthode qui récupère toutes les planques pour les insérer dans la classe
    public function getAllSafeHouses(): array
    {
        $query = "SELECT * FROM SafeHouses";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $safeHousesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $safeHouses = [];
        foreach ($safeHousesData as $safeHouseData) {
            $id = $safeHouseData['id'];
            $code = $safeHouseData['code'];
            $address = $safeHouseData['address'];
            $country = $safeHouseData['country'];
            $type = $safeHouseData['type'];
            $mission = $safeHouseData['mission_id'];

            if (!isset(self::$safeHouses[$id])) {
                $safeHouse = new SafeHouse($this->pdo, $id, $code, $address, $country, $type, $mission);
                self::$safeHouses[$id] = $safeHouse;
            }

            $safeHouses[] = self::$safeHouses[$id];
        }

        return $safeHouses;
    }

    //Méthode qui ajoute une nouvelle planque dans la base de donnée et dans la classe
    public function addSafeHouse(string $code, string $address, string $country, string $type): ?SafeHouse
    {
        // Vérifier si la planque existe déjà dans la base de données
        $query = "SELECT * FROM SafeHouses WHERE code = :code AND address = :address AND country = :country AND type = :type";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':type', $type);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return null;
        }

        // Insérer la nouvelle planque dans la base de données
        $query = "INSERT INTO SafeHouses (code, address, country, type) VALUES (:code, :address, :country, :type)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':type', $type);
        $stmt->execute();

        $id = $this->pdo->lastInsertId();

        // Créer une nouvelle instance de SafeHouse
        $newSafeHouse = new SafeHouse($this->pdo, $id, $code, $address, $country, $type, null);

        // Mettre à jour le tableau $safeHouses
        self::$safeHouses[$id] = $newSafeHouse;

        return $newSafeHouse;
    }

    //Getters
    public function getId():int {
        return $this->id;
    }

    public function getCode(): string {
        return $this->code;
    }

    public function getAddress(): string {
        return $this->address;
    }
    
    public function getCountry(): string {
        return $this->country;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getMission(): ?Mission {
        return $this->mission;
    }
};