<?php

namespace app\classes;

require_once 'person.php';

class Target extends Person
{
    protected string $codeName;

    public function __construct($pdo, string $id, string $lastName, string $firstName, string $birthDate, string $nationality, string $codeName)
    {
        parent::__construct($pdo, $id, $lastName, $firstName, $birthDate, $nationality);
        $this->codeName = $codeName;
    }

    /**
     * Récupère une cible en fonction de son ID.
     *
     * @param PDO $pdo L'objet PDO pour la connexion à la base de données.
     * @param int $id L'ID de la cible à récupérer.
     * @return Target|null La cible correspondante, ou null si la cible n'existe pas.
     */
    public function getTargetById($pdo, $id): ?Target
    {
        // Appeler la méthode getPersonById de la classe parente pour récupérer la personne correspondante
        $person = parent::getPersonById($pdo, $id);

        if ($person instanceof Person) {
            // Si la personne existe, exécuter la requête SQL pour récupérer les données de la cible
            $query = "SELECT * FROM Targets WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $targetData = $stmt->fetch(\PDO::FETCH_ASSOC);
            $codeName = $targetData['code_name'];

            if ($targetData) {
                // Si les données de la cible existent, créer une instance de la classe Target avec les données récupérées
                return new Target($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $codeName);
            }
        }

        return null;
    }

    /**
     * Récupère toutes les cibles de la base de données et les insère dans la classe.
     *
     * @param PDO $pdo L'objet PDO pour la connexion à la base de données.
     * @return array Un tableau contenant toutes les cibles récupérées.
     */
    public function getAllTargets($pdo): array
    {
        // Appeler la méthode getAllPersons de la classe parente pour récupérer toutes les personnes
        $persons = parent::getAllPersons($pdo);
        $targets = [];

        foreach ($persons as $person) {
            // Pour chaque personne, exécuter une requête SQL pour récupérer les données de la cible correspondante
            $query = "SELECT * FROM Targets WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $person->getId());
            $stmt->execute();

            $targetData = $stmt->fetch(\PDO::FETCH_ASSOC);
            $codeName = $targetData['code_name'];

            if ($targetData) {
                // Si les données de la cible existent, créer une instance de la classe Target avec les données récupérées
                $targets[] = new Target($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $codeName);
            }
        }

        return $targets;
    }

    /**
     * Ajoute les propriétés d'une nouvelle cible dans la base de données et dans la classe.
     *
     * @param PDO    $pdo         L'objet PDO pour la connexion à la base de données.
     * @param string $lastName    Le nom de famille de la cible.
     * @param string $firstName   Le prénom de la cible.
     * @param string $birthDate   La date de naissance de la cible.
     * @param string $nationality La nationalité de la cible.
     * @param string $codeName    Le code de nom de la cible.
     * @return Target|null        L'instance de la classe Target créée, ou null si l'ajout a échoué.
     */
    public function addTargetProperties($pdo, string $lastName, string $firstName, string $birthDate, string $nationality, string $codeName): ?Target
    {
        // Appeler la méthode addPerson de la classe parente pour ajouter une nouvelle personne dans la base de données
        $person = parent::addPerson($pdo, $lastName, $firstName, $birthDate, $nationality);

        if ($person instanceof Person) {
            // Si l'ajout de la personne a réussi, insérer les données de la cible dans la table Targets
            $query = "INSERT INTO Targets (id, code_name) VALUES (:id, :codeName)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $person->getId());
            $stmt->bindParam(':codeName', $codeName);
            $stmt->execute();

            // Créer une instance de la classe Target avec les données fournies et retourner cette instance
            return new Target($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $codeName);
        }

        return null;
    }

    /**
     * Met à jour les propriétés d'une cible en fonction de son ID.
     *
     * @param string $id                  L'ID de la cible à mettre à jour.
     * @param array  $propertiesToUpdate  Les propriétés à mettre à jour, sous la forme d'un tableau associatif.
     * @return bool                       True si la mise à jour a réussi, false sinon.
     */
    public function updateTargetProperties(string $id, array $propertiesToUpdate): bool
    {
        // Appeler la méthode updateProperties de la classe parente pour mettre à jour les propriétés de la personne
        $personUpdated = parent::updateProperties($id, $propertiesToUpdate);

        if ($personUpdated) {
            // Si la mise à jour de la personne a réussi, mettre à jour le code de nom dans la table Targets
            $query = "UPDATE Targets SET code_name = :codeName WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':codeName', $this->codeName);
            $stmt->execute();

            return true;
        }

        return false;
    }

    /**
     * Supprime une cible de la base de données et de la classe en fonction de son ID.
     *
     * @param string $id  L'ID de la cible à supprimer.
     * @return bool       True si la suppression a réussi, false sinon.
     */
    public function deleteTarget(string $id): bool
    {
        // Appeler la méthode deletePersonById de la classe parente pour supprimer la personne correspondante
        $personDeleted = parent::deletePersonById($id);

        if ($personDeleted) {
            // Si la suppression de la personne a réussi, supprimer la cible de la table Targets
            $query = "DELETE FROM Targets WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return true;
        }

        return false;
    }

    //Getters
    public function getCodeName(): string
    {
        return $this->codeName;
    }

    public function setCodeName(string $codeName): void
    {
        $this->codeName = $codeName;
    }
}
