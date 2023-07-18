<?php

namespace app\classes;

class CountryNationality
{
    private int $id;
    private string $country;
    private string $nationality;
    private static \PDO $pdo;

    private static array $countriesNationalities = [];

    public function __construct($pdo, int $id = null, string $country = '', string $nationality = '')
    {
        self::$pdo = $pdo;
        $this->id = $id ?? 0;
        $this->country = $country;
        $this->nationality = $nationality;

        self::$countriesNationalities[$id] = $this;
    }

    /**
     * Récupère les informations d'un pays et d'une nationalité à partir de son identifiant.
     *
     * @param mixed $id L'identifiant du pays et de la nationalité.
     * @return CountryNationality|null Les informations du pays et de la nationalité ou null si non trouvé.
     */
    public static function getCountryNationalityById(int $id)
    {
        if (isset(self::$countriesNationalities[$id])) {
            return self::$countriesNationalities[$id];
        }

        $query = "SELECT * FROM CountriesNationalities WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id = $data['id'];
        $country = $data['country'];
        $nationality = $data['nationality'];

        if ($data) {
            $countriesNationality = new CountryNationality(self::$pdo, $id, $country, $nationality);
            self::$countriesNationalities[$id] = $countriesNationality;
            return $countriesNationality;
        }

        return null;
    }

    /**
     * Récupère tous les pays et nationalités de la base de données.
     *
     * @return array Les pays et nationalités.
     */
    public static function getAllCountriesNationalities(): array
    {
        $query = "SELECT * FROM CountriesNationalities";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $countriesNationalities = [];
        foreach ($data as $row) {
            $id = $row['id'];

            if (!isset(self::$countriesNationalities[$id])) {
                $countriesNationality = new CountryNationality(
                    self::$pdo,
                    $id,
                    $row['country'],
                    $row['nationality']
                );
                self::$countriesNationalities[$id] = $countriesNationality;
            }

            $countriesNationalities[] = self::$countriesNationalities[$id];
        }

        return $countriesNationalities;
    }

        /**
     * Récupère toutes les spécialités de la base de données et met en place une pagination.
     *
     * @return array Un tableau contenant toutes les spécialités.
     */
    public static function getAllCountriesNationalitiesPagination(int $page, int $perPage): array
    {
        // Calculer l'offset pour la page spécifiée
        $offset = ($page - 1) * $perPage;

        // Requête SQL avec les clauses LIMIT et OFFSET
        $query = "SELECT * FROM CountriesNationalities LIMIT :perPage OFFSET :offset";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':perPage', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $countriesNationalitiesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $countriesNationalities = [];
        foreach ($countriesNationalitiesData as $countryNationalityData) {
            $id = $countryNationalityData['id'];
            $country = $countryNationalityData['country'];
            $nationality = $countryNationalityData['nationality'];

            if (!isset(self::$countriesNationalities[$id])) {
                // Si la spécialité n'existe pas encore dans le tableau $countriesNationalities, créer une nouvelle instance de countryNationality
                $countryNationality = new CountryNationality(self::$pdo, $id, $country, $nationality);
                // Ajouter la spécialité au tableau $countriesNationalities
                self::$countriesNationalities[$id] = $countryNationality;
            }

            // Ajouter la spécialité au tableau de résultats
            $countriesNationalities[] = self::$countriesNationalities[$id];
        }

        // Retourner le tableau contenant toutes les spécialités
        return $countriesNationalities;
    }

    public static function countCountriesNationalities(): int
    {
        $query = "SELECT COUNT(*) AS total FROM CountriesNationalities";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $result['total'];
    }

    /**
     * Ajoute un nouveau pays et une nouvelle nationalité.
     *
     * @param string $country Le nom du pays.
     * @param string $nationality Le nom de la nationalité.
     * @return CountryNationality|null Le nouveau pays et la nouvelle nationalité ajoutés, ou null si déjà existants.
     */
    public static function addCountryNationality(string $country, string $nationality): ?CountryNationality
    {
        // Vérifier si le pays et la nationalité existent déjà dans la base de données
        $query = "SELECT * FROM CountriesNationalities WHERE country = :country AND nationality = :nationality";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':country', $country);
        $stmt->bindValue(':nationality', $nationality);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return null;
        }

        // Insérer le nouveau pays et la nouvelle nationalité dans la base de données et dans la classe
        $query = "INSERT INTO CountriesNationalities (country, nationality) VALUES (:country, :nationality)";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':country', $country);
        $stmt->bindValue(':nationality', $nationality);
        $stmt->execute();

        $newId = self::$pdo->lastInsertId();

        $newCountryNationality = new CountryNationality(self::$pdo, $newId, $country, $nationality);

        self::$countriesNationalities[$newId] = $newCountryNationality;

        return $newCountryNationality;
    }

    /**
     * Met à jour les propriétés du pays et de la nationalité dans la base de données et dans la classe.
     *
     * @param int $id L'identifiant du pays et de la nationalité à mettre à jour.
     * @param array $propertiesToUpdate Les propriétés à mettre à jour avec leurs nouvelles valeurs.
     * @return bool Indique si la mise à jour a été effectuée avec succès.
     */
    public static function updateCountryNationalityProperties(int $id, array $propertiesToUpdate): bool
    {
        // Récupérer l'instance du pays et de la nationalité correspondant à l'ID
        $countriesNationality = self::getCountryNationalityById($id);

        if ($countriesNationality) {
            // Mettre à jour les propriétés dans la classe
            foreach ($propertiesToUpdate as $property => $value) {
                if ($countriesNationality->$property !== $value) {
                    $countriesNationality->$property = $value;
                }
            }

            // Mettre à jour les propriétés dans la base de données
            $query = "UPDATE CountriesNationalities SET country = :country, nationality = :nationality WHERE id = :id";
            $stmt = self::$pdo->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':country', $countriesNationality->country);
            $stmt->bindValue(':nationality', $countriesNationality->nationality);
            $stmt->execute();

            // Mettre à jour le tableau $countriesNationalities
            self::$countriesNationalities[$id] = $countriesNationality;

            return true;
        }

        return false;
    }

    /**
     * Supprime un pays et une nationalité de la base de données et de la classe en fonction de leur ID.
     *
     * @param int $id L'identifiant du pays et de la nationalité à supprimer.
     * @return json
     */
    public static function deleteCountryNationalityById(int $id)
    {
        // Vérifier si l'association pays/nationalité est utilisée dans une ou plusieurs personnes
        $query = "SELECT COUNT(*) FROM Persons WHERE countrynationality_id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $countInPerson = $stmt->fetchColumn();

        // Vérifier si l'association pays/nationalité est utilisée dans une ou plusieurs planques
        $query = "SELECT COUNT(*) FROM SafeHouses WHERE countrynationality_id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $countInSafeHouse = $stmt->fetchColumn();

        // Vérifier si l'association pays/nationalité est utilisée dans une ou plusieurs missions
        $query = "SELECT COUNT(*) FROM Missions WHERE countrynationality_id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $countInMission = $stmt->fetchColumn();
        

        // Si la spécialité est utilisée ailleurs, ne pas le supprimer
        if ($countInPerson > 0 || $countInSafeHouse > 0 || $countInMission > 0) {
            echo json_encode(array('status' => 'used'));
            exit;
        }

        // Supprimer le pays et la nationalité de la base de données
        $query = "DELETE FROM CountriesNationalities WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Supprimer le pays et la nationalité de la classe
        if (isset(self::$countriesNationalities[$id])) {
            unset(self::$countriesNationalities[$id]);

            echo json_encode(['status' => 'success']);
            exit;
        }

        echo json_encode(['status' => 'error']);
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

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getNationality(): string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): void
    {
        $this->nationality = $nationality;
    }
}
