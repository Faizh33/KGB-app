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

    /**
     * Assigner une mission à une planque, à la fois dans la classe et dans la base de données.
     *
     * @param int $id L'identifiant de la planque.
     * @param Mission $mission La mission à assigner.
     */
    public function assignMission(int $id, Mission $mission): void
    {
        // Vérifie si la planque existe dans le tableau static $safeHouses
        if (isset(self::$safeHouses[$id])) {
            // Récupère la planque correspondante
            $safeHouse = self::$safeHouses[$id];
            
            // Assigner la mission à la planque dans la classe
            $safeHouse->mission = $mission;

            // Mettre à jour le champ mission_id dans la base de données
            $query = "UPDATE SafeHouses SET mission_id = :missionId WHERE id = :id";
            $stmt = $safeHouse->pdo->prepare($query);
            $stmt->execute(['missionId' => $mission->getId(), 'id' => $id]);
        }
    }

        /**
     * Récupère une planque en fonction de son identifiant.
     *
     * @param int $id L'identifiant de la planque.
     * @return SafeHouse|null La planque correspondante ou null si aucune planque n'est trouvée.
     */
    public function getSafeHouseById($id)
    {
        // Vérifie si la planque est déjà stockée dans le tableau static $safeHouses
        if (isset(self::$safeHouses[$id])) {
            return self::$safeHouses[$id];
        }

        // Requête SQL pour récupérer les données de la planque depuis la base de données
        $query = "SELECT * FROM SafeHouses WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $safeHouseData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($safeHouseData) {
            // Extrait les données de la planque de l'array associatif
            $code = $safeHouseData['code'];
            $address = $safeHouseData['address'];
            $country = $safeHouseData['country'];
            $type = $safeHouseData['type'];
            $mission = $safeHouseData['mission_id'];

            // Crée une nouvelle instance de SafeHouse avec les données récupérées
            $safeHouse = new SafeHouse($this->pdo, $id, $code, $address, $country, $type, $mission);

            // Stocke la nouvelle instance dans le tableau static $safeHouses
            self::$safeHouses[$id] = $safeHouse;

            return $safeHouse;
        }

        return null;
    }


        /**
     * Récupère toutes les planques depuis la base de données et les insère dans la classe.
     *
     * @return array Un tableau contenant toutes les planques.
     */
    public function getAllSafeHouses(): array
    {
        // Récupérer toutes les données des planques depuis la base de données
        $query = "SELECT * FROM SafeHouses";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $safeHousesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Tableau pour stocker les instances de planques
        $safeHouses = [];

        // Parcourir les données des planques récupérées
        foreach ($safeHousesData as $safeHouseData) {
            $id = $safeHouseData['id'];
            $code = $safeHouseData['code'];
            $address = $safeHouseData['address'];
            $country = $safeHouseData['country'];
            $type = $safeHouseData['type'];
            $mission = $safeHouseData['mission_id'];

            // Vérifier si la planque existe déjà dans le tableau static $safeHouses
            if (!isset(self::$safeHouses[$id])) {
                // Créer une nouvelle instance de planque
                $safeHouse = new SafeHouse($this->pdo, $id, $code, $address, $country, $type, $mission);
                self::$safeHouses[$id] = $safeHouse;
            }

            // Ajouter la planque au tableau des planques
            $safeHouses[] = self::$safeHouses[$id];
        }

        return $safeHouses;
    }

    /**
     * Ajoute une nouvelle planque dans la base de données et dans la classe.
     *
     * @param string $code Le code de la planque.
     * @param string $address L'adresse de la planque.
     * @param string $country Le pays de la planque.
     * @param string $type Le type de la planque.
     * @return SafeHouse|null La nouvelle instance de la planque ajoutée, ou null si la planque existe déjà.
     */
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
            // La planque existe déjà, retourner null
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

        // Créer une nouvelle instance de SafeHouse avec les données fournies
        $newSafeHouse = new SafeHouse($this->pdo, $id, $code, $address, $country, $type, null);

        // Mettre à jour le tableau $safeHouses avec la nouvelle instance de planque
        self::$safeHouses[$id] = $newSafeHouse;

        // Retourner la nouvelle instance de la planque ajoutée
        return $newSafeHouse;
    }

    /**
     * Met à jour les propriétés de la planque dans la base de données et dans la classe.
     *
     * @param array $propertiesToUpdate Les propriétés à mettre à jour sous la forme d'un tableau associatif.
     * @return bool Retourne true si la mise à jour a réussi, sinon false.
     */
    public function updateProperties(array $propertiesToUpdate): bool
    {
        $id = $this->getId();

        foreach ($propertiesToUpdate as $property => $value) {
            if ($this->$property !== $value) {
                // Mettre à jour la propriété de la planque dans la classe
                $this->$property = $value;
            }
        }

        $query = "UPDATE SafeHouses SET code = :code, address = :address, country = :country, type = :type WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':type', $this->type);
        $stmt->execute();

        // Mettre à jour le tableau $safeHouses avec la planque modifiée
        self::$safeHouses[$id] = $this;

        // Retourner true pour indiquer que la mise à jour a réussi
        return true;
    }

    /**
     * Supprime une planque de la base de données et de la classe en utilisant son ID.
     *
     * @param mixed $id L'ID de la planque à supprimer.
     * @return bool Retourne true si la suppression est réussie, sinon false.
     */
    public function deleteSafeHouseById($id): bool
    {
        // Vérifier si l'ID existe en base de données
        $query = "SELECT * FROM SafeHouses WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            // L'ID n'existe pas, retourner false pour indiquer une suppression non réussie
            return false;
        }

        // Supprimer la planque de la base de données
        $query = "DELETE FROM SafeHouses WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Supprimer la planque de la classe
        if (isset(self::$safeHouses[$id])) {
            unset(self::$safeHouses[$id]);
            // Retourner true pour indiquer une suppression réussie
            return true;
        }

        // Si la planque n'est pas trouvée dans la classe, retourner false pour indiquer une suppression non réussie
        return false;
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