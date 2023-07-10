<?php

namespace app\classes;

require_once 'Person.php';
require_once 'AgentSpeciality.php';

class Agent extends Person
{
    protected string $identificationCode;

    public function __construct($pdo, string $id = '', string $lastName = '', string $firstName = '', string $birthDate = '', string $nationality = '', string $identificationCode = '')
    {
        parent::__construct($pdo, $id, $lastName, $firstName, $birthDate, $nationality);
        $this->identificationCode = $identificationCode;
    }

    /**
     * Récupère un agent en fonction de son ID.
     *
     * @Value \PDO   $pdo L'objet PDO pour exécuter la requête SQL.
     * @Value int    $id  L'ID de l'agent à récupérer.
     *
     * @return Agent|null L'instance de l'agent correspondant à l'ID donné, ou null si non trouvé.
     */
    public static function getAgentById(string $id): ?Agent
    {
        $person = parent::getPersonById($id);

        if ($person instanceof Person) {
            $query = "SELECT * FROM Agents WHERE id = :id";
            $stmt = self::$pdo->prepare($query);
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            $agentDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
            $idCode = $agentDatas['identification_code'];

            if ($agentDatas) {
                // Les données correspondent à un agent, création d'une nouvelle instance de Agent
                return new Agent(self::$pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $idCode
                );
            }
        }
        // Aucune personne trouvée pour l'ID donné ou les données ne correspondent pas à un agent
        return null;
    }

    /**
     * Récupère tous les agents de la base de données et les insère dans la classe.
     *
     * @Value \PDO $pdo L'objet PDO pour exécuter la requête SQL.
     *
     * @return array Le tableau contenant toutes les instances d'agents récupérées de la base de données.
     */
    public static function getAllAgents(): array
    {
        $persons = parent::getAllPersons();
        $agents = [];

        foreach ($persons as $person) {
            $query = "SELECT * FROM Agents WHERE id = :id";
            $stmt = self::$pdo->prepare($query);
            $stmt->bindValue(":id", $person->getId());
            $stmt->execute();

            $agentDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($agentDatas !== false) {
                $idCode = $agentDatas['identification_code'];
                $agents[] = new Agent(self::$pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $idCode);
            }
        }

        return $agents;
    }

    /**
     * Ajoute un nouvel agent dans la base de données et dans la classe.
     *
     * @Value \PDO $pdo L'objet PDO pour exécuter la requête SQL.
     * @Value string $lastName Le nom de famille de l'agent.
     * @Value string $firstName Le prénom de l'agent.
     * @Value string $birthDate La date de naissance de l'agent.
     * @Value string $nationality La nationalité de l'agent.
     * @Value string $identificationCode Le code d'identification de l'agent.
     *
     * @return Agent|null L'instance de l'agent ajouté ou null si l'ajout a échoué.
     */
    public static function addAgentProperties(string $lastName, string $firstName, string $birthDate, string $nationality, string $identificationCode): ?Agent
    {
        $person = parent::addPerson($lastName, $firstName, $birthDate, $nationality);

        if ($person instanceof Person) {
            $query = "INSERT INTO Agents (id, identification_code) VALUES (:id, :identificationCode)";
            $stmt = self::$pdo->prepare($query);
            $stmt->bindValue(':id', $person->getId());
            $stmt->bindValue(':identificationCode', $identificationCode);
            $stmt->execute();

            return new Agent(self::$pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $identificationCode);
        }

        return null;
    }

    /**
     * Met à jour les propriétés d'un agent en fonction de son identifiant.
     *
     * @param string $id L'identifiant de l'agent.
     * @param array $propertiesToUpdate Les propriétés à mettre à jour.
     *
     * @return bool Retourne true si les propriétés ont été mises à jour avec succès, sinon false.
     */
    public static function updateAgentProperties(string $id, array $propertiesToUpdate): bool
    {
        // Vérifie si "identificationCode" est présent dans $propertiesToUpdate
        $identificationCode = null;
        if (isset($propertiesToUpdate['identificationCode'])) {
            $identificationCode = $propertiesToUpdate['identificationCode'];
            unset($propertiesToUpdate['identificationCode']);

            // Vérifie si la valeur de "identificationCode" existe déjà dans d'autres enregistrements
            $query = "SELECT id FROM Agents WHERE identification_code = :identificationCode AND id != :id";
            $stmt = self::$pdo->prepare($query);
            $stmt->bindValue(':identificationCode', $identificationCode);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            $existingAgent = $stmt->fetch();

            if ($existingAgent) {
                echo "Le code d'identification existe déjà en base de données";
                return false;
            }
        }

        // Met à jour les propriétés du parent
        $personUpdated = parent::updatePersonProperties($id, $propertiesToUpdate);

        if ($personUpdated) {
            // Met à jour la propriété "identification_code" dans la table "Agents"
            if ($identificationCode !== null) {
                $query = "UPDATE Agents SET identification_code = :identificationCode WHERE id = :id";
                $stmt = self::$pdo->prepare($query);
                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':identificationCode', $identificationCode);
                $stmt->execute();
            }

            return true;
        }

        return false;
    }

    /**
     * Supprime un agent de la base de données et de la classe en fonction de l'id.
     *
     * @return bool Retourne true si l'agent a été supprimé avec succès, sinon false.
     */
    public static function deleteAgentById(string $id, $specialityId): bool
    {
        // Vérifier l'existence de l'agent
        $agent = self::getAgentById($id);
        if (!$agent) {
            return false; // L'agent n'existe pas, retourner false
        }

        // Supprimer les associations AgentSpeciality pour cet agent
        $agentSpeciality = new AgentSpeciality(self::$pdo, $id, $specialityId);
        $agentSpeciality->deleteSpecialitiesByAgentId($id);

        // Supprimer l'agent lui-même
        $personDeleted = parent::deletePersonById($id);

        if ($personDeleted) {
            $query = "DELETE FROM Agents WHERE id = :id";
            $stmt = self::$pdo->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            return true;
        }

        return false;
    }

    //Getter et Setter
    public function getIdentificationCode(): string
    {
        return $this->identificationCode;
    }

    public function setIdentificationCode(string $identificationCode): void
    {
        $this->identificationCode = $identificationCode;
    }
}
