<?php

namespace app\classes;

require_once 'AgentSpeciality.php';

class Speciality
{
    private int $id;
    private string $speciality;
    private $pdo;

    private static array $specialities = [];

    public function __construct($pdo, int $id = NULL, string $speciality = '')
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $this->speciality = $speciality;

        self::$specialities[$id] = $this;
    }

        /**
     * Récupère une spécialité en fonction de son ID.
     *
     * @param int $id L'ID de la spécialité à récupérer.
     * @return Speciality|null La spécialité correspondante si elle existe, sinon null.
     */
    public function getSpecialityById($id)
    {
        if (isset(self::$specialities[$id])) {
            // Si la spécialité existe déjà dans le tableau $specialities, la retourner directement
            return self::$specialities[$id];
        }

        $query = "SELECT * FROM Specialities WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $specialityDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id = $specialityDatas['id'];
        $specialityName = $specialityDatas['speciality'];

        if ($specialityDatas) {
            // Si la spécialité est trouvée dans la base de données, créer une nouvelle instance de Speciality
            $speciality = new Speciality($this->pdo, $id, $specialityName);
            // Ajouter la spécialité au tableau $specialities pour une utilisation future
            self::$specialities[$id] = $speciality;
            return $speciality;
        }

        // Retourner null si la spécialité n'est pas trouvée
        return null;
    }

    /**
     * Récupère toutes les spécialités de la base de données et les insère dans la classe.
     *
     * @return array Un tableau contenant toutes les spécialités.
     */
    public function getAllSpecialities(): array
    {
        $query = "SELECT * FROM Specialities";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $specialitiesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $specialities = [];
        foreach ($specialitiesData as $specialityData) {
            $id = $specialityData['id'];
            $specialityName = $specialityData['speciality'];

            if (!isset(self::$specialities[$id])) {
                // Si la spécialité n'existe pas encore dans le tableau $specialities, créer une nouvelle instance de Speciality
                $speciality = new Speciality($this->pdo, $id, $specialityName);
                // Ajouter la spécialité au tableau $specialities
                self::$specialities[$id] = $speciality;
            }

            // Ajouter la spécialité au tableau de résultats
            $specialities[] = self::$specialities[$id];
        }

        // Retourner le tableau contenant toutes les spécialités
        return $specialities;
    }

    /**
     * Ajoute une nouvelle spécialité dans la base de données et dans la classe.
     *
     * @param string $speciality La spécialité à ajouter.
     * @return Speciality|null La nouvelle instance de Speciality si l'ajout est réussi, sinon null.
     */
    public function addSpeciality(string $speciality): ?Speciality
    {
        // Vérifier si la spécialité existe déjà dans la base de données
        $query = "SELECT * FROM Specialities WHERE speciality = :speciality";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':speciality', $speciality);
        $stmt->execute();

        $specialityDatas = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($specialityDatas) {
            // La spécialité existe déjà, retourner null
            return null;
        }

        // Insérer la nouvelle spécialité dans la base de données et dans la classe
        $query = "INSERT INTO Specialities (speciality) VALUES (:speciality)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':speciality', $speciality);
        $stmt->execute();

        // Récupérer l'id de la nouvelle spécialité insérée
        $newSpecialityId = $this->pdo->lastInsertId();

        // Créer une nouvelle instance de Speciality avec les données fournies
        $newSpeciality = new Speciality($this->pdo, $newSpecialityId, $speciality);

        // Ajouter la nouvelle spécialité au tableau $specialities
        self::$specialities[$newSpecialityId] = $newSpeciality;

        // Retourner la nouvelle instance de Speciality pour indiquer un ajout réussi
        return $newSpeciality;
    }

    /**
     * Met à jour les propriétés de la spécialité dans la base de données et dans la classe.
     *
     * @param array $propertiesToUpdate Les propriétés à mettre à jour sous la forme d'un tableau associatif.
     * @return bool Retourne true si la mise à jour a réussi, sinon false.
     */
    public function updateProperties(array $propertiesToUpdate): bool
    {
        $id = $this->getId();

        foreach ($propertiesToUpdate as $property => $value) {
            if ($this->$property !== $value) {
                // Mettre à jour la propriété de la spécialité dans la classe
                $this->$property = $value;
            }
        }

        $query = "UPDATE Specialities SET speciality = :speciality WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':speciality', $this->speciality);
        $stmt->execute();

        // Mettre à jour le tableau $specialities avec la spécialité modifiée
        self::$specialities[$id] = $this;

        // Retourner true pour indiquer que la mise à jour a réussi
        return true;
    }

    /**
     * Supprime une spécialité de la base de données et de la classe en utilisant son ID.
     *
     * @param mixed $id L'ID de la spécialité à supprimer.
     * @return bool Retourne true si la suppression est réussie, sinon false.
     */
    public function deleteSpecialityById($id): bool
    {
        // Vérifier si l'ID existe en base de données
        $query = "SELECT * FROM Specialities WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $specialityDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$specialityDatas) {
            // L'ID n'existe pas, retourner false pour indiquer une suppression non réussie
            return false;
        }

        // Supprimer la spécialité de la base de données
        $query = "DELETE FROM Specialities WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Supprimer les associations agent/spécialité correspondantes en utilisant une instance de la classe AgentSpeciality
        $agentSpeciality = new AgentSpeciality($this->pdo, '', $id);
        $agentSpeciality->deleteAgentsBySpecialityId($id);

        // Supprimer la spécialité de la classe
        if (isset(self::$specialities[$id])) {
            unset(self::$specialities[$id]);
            // Retourner true pour indiquer une suppression réussie
            return true;
        }

        // Si la spécialité n'est pas trouvée dans la classe, retourner false pour indiquer une suppression non réussie
        return false;
    }
    
    //Getters et Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSpeciality(): string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): void
    {
        $this->speciality = $speciality;
    }
}
