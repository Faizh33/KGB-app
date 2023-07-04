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
     * @param \PDO   $pdo L'objet PDO pour exécuter la requête SQL.
     * @param int    $id  L'ID de l'agent à récupérer.
     *
     * @return Agent|null L'instance de l'agent correspondant à l'ID donné, ou null si non trouvé.
     */
    public function getAgentById($pdo, $id): ?Agent
    {
        $person = parent::getPersonById($pdo, $id);

        if ($person instanceof Person) {
            $query = "SELECT * FROM Agents WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $agentDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
            $idCode = $agentDatas['identification_code'];

            if ($agentDatas) {
                // Les données correspondent à un agent, création d'une nouvelle instance de Agent
                return new Agent($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $idCode
                );
            }
        }
        // Aucune personne trouvée pour l'ID donné ou les données ne correspondent pas à un agent
        return null;
    }

    /**
     * Récupère tous les agents de la base de données et les insère dans la classe.
     *
     * @param \PDO $pdo L'objet PDO pour exécuter la requête SQL.
     *
     * @return array Le tableau contenant toutes les instances d'agents récupérées de la base de données.
     */
    public function getAllAgents($pdo): array
    {
        $persons = parent::getAllPersons($pdo);
        $agents = [];

        foreach ($persons as $person) {
            $query = "SELECT * FROM Agents WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $person->getId());
            $stmt->execute();

            $agentDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
            $idCode = $agentDatas['identification_code'];

            if ($agentDatas) {
                $agents[] = new Agent($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $idCode);
            }
        }

        return $agents;
    }

    /**
     * Ajoute un nouvel agent dans la base de données et dans la classe.
     *
     * @param \PDO $pdo L'objet PDO pour exécuter la requête SQL.
     * @param string $lastName Le nom de famille de l'agent.
     * @param string $firstName Le prénom de l'agent.
     * @param string $birthDate La date de naissance de l'agent.
     * @param string $nationality La nationalité de l'agent.
     * @param string $identificationCode Le code d'identification de l'agent.
     *
     * @return Agent|null L'instance de l'agent ajouté ou null si l'ajout a échoué.
     */
    public function addAgentProperties(\PDO $pdo, string $lastName, string $firstName, string $birthDate, string $nationality, string $identificationCode): ?Agent
    {
        $person = parent::addPerson($pdo, $lastName, $firstName, $birthDate, $nationality);

        if ($person instanceof Person) {
            $query = "INSERT INTO Agents (id, identification_code) VALUES (:id, :identificationCode)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $person->getId());
            $stmt->bindParam(':identificationCode', $identificationCode);
            $stmt->execute();

            return new Agent($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $identificationCode);
        }

        return null;
    }

    /**
     * Modifie les propriétés d'un agent en fonction de l'id.
     *
     * @param array $propertiesToUpdate Les propriétés à mettre à jour.
     *
     * @return bool Retourne true si les propriétés ont été mises à jour avec succès, sinon false.
     */
    public function updateAgentProperties(string $id, array $propertiesToUpdate): bool
    {
        $personUpdated = parent::updateProperties($id, $propertiesToUpdate);

        if ($personUpdated) {
            $query = "UPDATE Agents SET identification_code = :identificationCode WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':identificationCode', $this->identificationCode);
            $stmt->execute();

            return true;
        }

        return false;
    }

    /**
     * Supprime un agent de la base de données et de la classe en fonction de l'id.
     *
     * @return bool Retourne true si l'agent a été supprimé avec succès, sinon false.
     */
    public function deleteAgentById(string $id, $specialityId): bool
    {
        // Vérifier l'existence de l'agent
        $agent = self::getAgentById($this->pdo, $id);
        if (!$agent) {
            return false; // L'agent n'existe pas, retourner false
        }

        // Supprimer les associations AgentSpeciality pour cet agent
        $agentSpeciality = new AgentSpeciality($this->pdo, $id, $specialityId);
        $agentSpeciality->deleteSpecialitiesByAgentId($id);

        // Supprimer l'agent lui-même
        $personDeleted = parent::deletePersonById($id);

        if ($personDeleted) {
            $query = "DELETE FROM Agents WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
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
