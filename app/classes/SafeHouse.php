<?php

namespace app\classes;

class SafeHouse
{
    private int $id;
    private string $code;
    private string $address;
    private string $country;
    private string $type;
    private $pdo;

    private static array $safeHouses = [];

    public function __construct($pdo, int $id = null, string $code = '', string $address = '', string $country = '', string $type = '')
    {
        $this->pdo = $pdo;
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
    public static function getSafeHouseById($pdo, int $id): ?SafeHouse
    {
        if (isset(self::$safeHouses[$id])) {
            return self::$safeHouses[$id];
        }

        $query = "SELECT * FROM SafeHouses WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $safeHouseData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($safeHouseData) {
            $safeHouse = new SafeHouse(
                $pdo,
                $safeHouseData['id'],
                $safeHouseData['code'],
                $safeHouseData['address'],
                $safeHouseData['country'],
                $safeHouseData['type']
            );

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
    public static function getAllSafeHouses($pdo): array
    {
        $query = "SELECT * FROM SafeHouses";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $safeHousesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $safeHouses = [];
        foreach ($safeHousesData as $safeHouseData) {
            $safeHouseId = $safeHouseData['id'];

            if (!isset(self::$safeHouses[$safeHouseId])) {
                $safeHouse = new SafeHouse(
                    $pdo,
                    $safeHouseData['id'],
                    $safeHouseData['code'],
                    $safeHouseData['address'],
                    $safeHouseData['country'],
                    $safeHouseData['type']
                );

                self::$safeHouses[$safeHouseId] = $safeHouse;
            }

            $safeHouses[] = self::$safeHouses[$safeHouseId];
        }

        return $safeHouses;
    }

    /**
     * Ajoute une nouvelle SafeHouse.
     *
     * @param string $code Le code de la SafeHouse.
     * @param string $address L'adresse de la SafeHouse.
     * @param string $country Le pays de la SafeHouse.
     * @param string $type Le type de la SafeHouse.
     * @return SafeHouse|null La nouvelle SafeHouse ajoutée, ou null si le code existe déjà.
```php
     */
    public function addSafeHouse(string $code, string $address, string $country, string $type): ?SafeHouse
    {
        // Vérifier si le code de la SafeHouse existe déjà dans la base de données
        $query = "SELECT * FROM SafeHouses WHERE code = :code";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':code', $code);
        $stmt->execute();

        $safeHouseData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($safeHouseData) {
            return null;
        }

        // Insérer la nouvelle SafeHouse dans la base de données et dans la classe
        $query = "INSERT INTO SafeHouses (code, address, country, type) VALUES (:code, :address, :country, :type)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':code', $code);
        $stmt->bindValue(':address', $address);
        $stmt->bindValue(':country', $country);
        $stmt->bindValue(':type', $type);
        $stmt->execute();

        $newSafeHouseId = $this->pdo->lastInsertId();

        $newSafeHouse = new SafeHouse($this->pdo, $newSafeHouseId, $code, $address, $country, $type);

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
    public function updateSafeHouseProperties(int $id, array $propertiesToUpdate): bool
    {
        // Mettre à jour les propriétés dans la classe
        foreach ($propertiesToUpdate as $property => $value) {
            if ($this->$property !== $value) {
                $this->$property = $value;
            }
        }

        // Mettre à jour les propriétés dans la base de données
        $query = "UPDATE SafeHouses SET code = :code, address = :address, country = :country, type = :type WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':code', $this->code);
        $stmt->bindValue(':address', $this->address);
        $stmt->bindValue(':country', $this->country);
        $stmt->bindValue(':type', $this->type);
        $stmt->execute();

        // Mettre à jour le tableau $safeHouses
        self::$safeHouses[$id] = $this;

        return true;
    }

    /**
     * Supprime une SafeHouse de la base de données et de la classe en fonction de son ID.
     *
     * @param int $id L'identifiant de la SafeHouse à supprimer.
     * @return bool Indique si la suppression a été effectuée avec succès.
     */
    public function deleteSafeHouseById($id): bool
    {
        // Supprimer la SafeHouse de la base de données
        $query = "DELETE FROM SafeHouses WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        // Supprimer la SafeHouse de la classe
        if (isset(self::$safeHouses[$id])) {
            unset(self::$safeHouses[$id]);
            return true;
        }

        return false;
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

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
