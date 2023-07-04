<?php

namespace app\classes;

require_once 'person.php';

class Contact extends Person
{
    protected string $codeName;

    public function __construct($pdo, string $id = '', string $lastName = '', string $firstName = '', string $birthDate = '', string $nationality = '', string $codeName = '')
    {
        parent::__construct($pdo, $id, $lastName, $firstName, $birthDate, $nationality);
        $this->codeName = $codeName;
    }

    /**
     * Récupère un contact en fonction de son ID.
     *
     * @param PDO $pdo L'objet PDO utilisé pour la connexion à la base de données.
     * @param int $id L'identifiant du contact à récupérer.
     * @return Contact|null Le contact correspondant à l'ID spécifié, ou null s'il n'existe pas.
     */
    public function getContactById($pdo, $id): ?Contact
    {
        // Récupérer la personne correspondante à l'ID
        $person = parent::getPersonById($pdo, $id);

        if ($person instanceof Person) {
            // Si la personne existe, récupérer le contact associé dans la table "Contacts"
            $query = "SELECT * FROM Contacts WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $contactDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
            $codeName = $contactDatas['code_name'];

            if ($contactDatas) {
                // Créer et retourner une instance de Contact en utilisant les données récupérées
                return new Contact($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $codeName);
            }
        }

        // Retourner null si le contact n'a pas été trouvé
        return null;
    }


    /**
     * Récupère tous les contacts de la base de données.
     *
     * @param PDO $pdo L'objet PDO utilisé pour la connexion à la base de données.
     * @return array Un tableau contenant tous les contacts de la base de données.
     */
    public function getAllContacts($pdo): array
    {
        // Récupérer toutes les personnes de la base de données
        $persons = parent::getAllPersons($pdo);

        $contacts = [];

        foreach ($persons as $person) {
            // Pour chaque personne, récupérer le contact associé dans la table "Contacts"
            $query = "SELECT * FROM Contacts WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":id", $person->getId());
            $stmt->execute();

            $contactDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
            $codeName = $contactDatas['code_name'];

            if ($contactDatas) {
                // Créer une instance de Contact en utilisant les données récupérées et l'ajouter au tableau des contacts
                $contacts[] = new Contact($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $codeName);
            }
        }

        return $contacts;
    }

    /**
     * Ajoute un nouveau contact dans la base de données et dans la classe.
     *
     * @param PDO $pdo L'objet PDO utilisé pour la connexion à la base de données.
     * @param string $lastName Le nom de famille du contact.
     * @param string $firstName Le prénom du contact.
     * @param string $birthDate La date de naissance du contact.
     * @param string $nationality La nationalité du contact.
     * @param string $codeName Le nom de code du contact.
     * @return Contact|null Le contact ajouté, ou null en cas d'erreur.
     */
    public function addContact($pdo, string $lastName, string $firstName, string $birthDate, string $nationality, string $codeName): ?Contact
    {
        // Ajouter une personne à la base de données en utilisant la méthode addPerson de la classe parente
        $person = parent::addPerson($pdo, $lastName, $firstName, $birthDate, $nationality);

        if ($person instanceof Person) {
            // Si la personne a été ajoutée avec succès, insérer les données spécifiques du contact dans la table "Contacts"
            $query = "INSERT INTO Contacts (id, code_name) VALUES (:id, :codeName)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $person->getId());
            $stmt->bindParam(':codeName', $codeName);
            $stmt->execute();

            // Créer une nouvelle instance de Contact en utilisant les données fournies et les données de la personne correspondante
            return new Contact($pdo, $person->getId(), $person->getLastName(), $person->getFirstName(), $person->getBirthDate(), $person->getNationality(), $codeName);
        }

        return null;
    }

    /**
     * Modifie les propriétés d'un contact en fonction de son ID.
     *
     * @param string $id L'ID du contact à modifier.
     * @param array $propertiesToUpdate Les propriétés à mettre à jour avec leurs nouvelles valeurs.
     * @return bool Indique si la mise à jour a été effectuée avec succès (true) ou non (false).
     */
    public function updateContactProperties(string $id, array $propertiesToUpdate): bool
    {
        // Appeler la méthode updateProperties de la classe parente pour mettre à jour les propriétés de la personne
        $personUpdated = parent::updateProperties($id, $propertiesToUpdate);

        if ($personUpdated) {
            // Si la mise à jour des propriétés de la personne a réussi, mettre à jour le nom de code du contact dans la table "Contacts"
            $query = "UPDATE Contacts SET code_name = :codeName WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':codeName', $this->codeName);
            $stmt->execute();

            return true;
        }

        return false;
    }

    /**
     * Supprime un contact de la base de données et de la classe en fonction de son ID.
     *
     * @return bool Indique si la suppression a été effectuée avec succès (true) ou non (false).
     */
    public function deleteContact(string $id): bool
    {
        // Appeler la méthode deletePersonById de la classe parente pour supprimer la personne correspondante dans la base de données
        $personDeleted = parent::deletePersonById($id);

        if ($personDeleted) {
            // Si la suppression de la personne a réussi, supprimer le contact de la table "Contacts"
            $query = "DELETE FROM Contacts WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return true;
        }

        return false;
    }

    //Getter et Setter
    public function getCodeName(): string
    {
        return $this->codeName;
    }

    public function setCodeName(string $codeName): void
    {
        $this->codeName = $codeName;
    }
}
