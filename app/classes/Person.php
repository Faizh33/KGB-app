<?php

namespace app\classes;

class Person
{
    protected string $id;
    protected string $lastName;
    protected string $firstName;
    protected string $birthDate;
    protected string $nationality;
    protected static \PDO $pdo;

    protected static array $persons = [];

    public function __construct($pdo, string $id = '', string $lastName = '', string $firstName = '', string $birthDate = '', string $nationality = '')
    {
        self::$pdo = $pdo;
        $this->id = $id;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->birthDate = $birthDate;
        $this->nationality = $nationality;

        self::$persons[$id] = $this;
    }

    /**
     * Récupère une personne par son ID et l'insère dans la classe.
     *
     * @param int $id L'ID de la personne à récupérer.
     * @return Person|null La personne correspondante si elle existe, sinon null.
     */
    public static function getPersonById(string $id): ?Person
    {
        if (isset(self::$persons[$id])) {
            return self::$persons[$id];
        }

        $query = "SELECT * FROM Persons WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $personData = $stmt->fetch(\PDO::FETCH_ASSOC);
        $lastName = $personData['lastName'];
        $firstName = $personData['firstName'];
        $birthDate = $personData['birthDate'];
        $nationality = $personData['nationality'];

        if ($personData) {
            $person = new Person(self::$pdo, $id, $lastName, $firstName, $birthDate, $nationality);
            self::$persons[$id] = $person;
            return $person;
        }

        return null;
    }

    /**
     * Récupère toutes les personnes en base de données.
     *
     * @return array Un tableau contenant toutes les personnes.
     */
    public static function getAllPersons(): array
    {
        $query = "SELECT * FROM Persons";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $personsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $persons = [];
        foreach ($personsData as $personData) {
            $id = $personData['id'];
            $lastName = $personData['lastName'];
            $firstName = $personData['firstName'];
            $birthDate = $personData['birthDate'];
            $nationality = $personData['nationality'];

            if (!isset(self::$persons[$id])) {
                $person = new Person(self::$pdo, $id, $lastName, $firstName, $birthDate, $nationality);
                self::$persons[$id] = $person;
            }

            $persons[] = self::$persons[$id];
        }

        return $persons;
    }

    /**
     * Ajoute une nouvelle personne dans la base de données et dans la classe.
     *
     * @param PDO    $pdo         L'instance de PDO pour l'accès à la base de données.
     * @param string $lastName    Le nom de famille de la personne.
     * @param string $firstName   Le prénom de la personne.
     * @param string $birthDate   La date de naissance de la personne.
     * @param string $nationality La nationalité de la personne.
     *
     * @return Person|null La nouvelle personne créée ou null si la personne existe déjà.
     */
    public static function addPerson(string $lastName, string $firstName, string $birthDate, string $nationality): ?Person
    {
        // Vérifier si la personne existe déjà dans la base de données
        $query = "SELECT * FROM Persons WHERE lastName = :lastName AND firstName = :firstName AND birthDate = :birthDate AND nationality = :nationality";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':lastName', $lastName);
        $stmt->bindValue(':firstName', $firstName);
        $stmt->bindValue(':birthDate', $birthDate);
        $stmt->bindValue(':nationality', $nationality);
        $stmt->execute();

        $personDatas = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($personDatas) {
            return null;
        }

        // Insérer la nouvelle personne dans la base de données et dans la classe
        $id = generateUUID();
        $query = "INSERT INTO Persons (id, lastName, firstName, birthDate, nationality) VALUES (:id, :lastName, :firstName, :birthDate, :nationality)";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':lastName', $lastName);
        $stmt->bindValue(':firstName', $firstName);
        $stmt->bindValue(':birthDate', $birthDate);
        $stmt->bindValue(':nationality', $nationality);
        $stmt->execute();

        $newPerson = new Person(self::$pdo, $id, $lastName, $firstName, $birthDate, $nationality);

        self::$persons[$id] = $newPerson;

        return $newPerson;
    }

    /**
     * Met à jour les propriétés de la personne dans la base de données et dans la classe.
     *
     * @param array $propertiesToUpdate Les propriétés à mettre à jour avec leurs nouvelles valeurs.
     *
     * @return bool Retourne true si les propriétés ont été mises à jour avec succès, sinon false.
     */
    public static function updatePersonProperties(string $id, array $propertiesToUpdate): bool
    {
        // Récupérer l'instance de la personne correspondant à l'ID
        $person = self::getPersonById($id);

        if ($person) {
            foreach ($propertiesToUpdate as $property => $value) {
                if ($person->$property !== $value) {
                    $person->$property = $value;
                }
            }

            $query = "UPDATE Persons SET lastName = :lastName, firstName = :firstName, birthDate = :birthDate, nationality = :nationality WHERE id = :id";
            $stmt = self::$pdo->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':lastName', $person->lastName);
            $stmt->bindValue(':firstName', $person->firstName);
            $stmt->bindValue(':birthDate', $person->birthDate);
            $stmt->bindValue(':nationality', $person->nationality);
            $stmt->execute();

            // Mettre à jour le tableau $persons
            self::$persons[$id] = $person;

            return true;
        }

        return false;
    }

    /**
     * Supprime une personne de la base de données et de la classe en fonction de son ID.
     *
     * @param string $id L'ID de la personne à supprimer.
     *
     * @return bool Retourne true si la personne a été supprimée avec succès, sinon false.
     */
    public static function deletePersonById($id): bool
    {
        if (empty($id)) {
            return false;
        }

        // Supprimer la personne de la base de données
        $query = "DELETE FROM Persons WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Supprimer la personne de la classe
        if (isset(self::$persons[$id])) {
            unset(self::$persons[$id]);
            return true;
        }

        return false;
    }

    // Getters et setters
    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getBirthDate(): string
    {
        return $this->birthDate;
    }

    public function setBirthDate(string $birthDate): void
    {
        $this->birthDate = $birthDate;
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
