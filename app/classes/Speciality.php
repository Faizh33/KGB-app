<?php

namespace app\classes;

require_once 'AgentSpeciality.php';

class Speciality
{
    private int $id;
    private string $speciality;
    private static \PDO $pdo;

    private static array $specialities = [];

    public function __construct($pdo, int $id = NULL, string $speciality = '')
    {
        self::$pdo = $pdo;
        $this->id = $id ?? 0;
        $this->speciality = $speciality;

        self::$specialities[$id] = $this;
    }

        /**
     * Récupère une spécialité en fonction de son ID.
     *
     * @param int $id L'ID de la spécialité à récupérer.
     * @return Speciality|null La spécialité correspondante si elle existe, sinon null.
     */
    public static function getSpecialityById(int $id)
    {
        if (isset(self::$specialities[$id])) {
            // Si la spécialité existe déjà dans le tableau $specialities, la retourner directement
            return self::$specialities[$id];
        }

        $query = "SELECT * FROM Specialities WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $specialityDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id = $specialityDatas['id'];
        $specialityName = $specialityDatas['speciality'];

        if ($specialityDatas) {
            // Si la spécialité est trouvée dans la base de données, créer une nouvelle instance de Speciality
            $speciality = new Speciality(self::$pdo, $id, $specialityName);
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
    public static function getAllSpecialities(): array
    {
        $query = "SELECT * FROM Specialities";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $specialitiesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $specialities = [];
        foreach ($specialitiesData as $specialityData) {
            $id = $specialityData['id'];
            $specialityName = $specialityData['speciality'];

            if (!isset(self::$specialities[$id])) {
                // Si la spécialité n'existe pas encore dans le tableau $specialities, créer une nouvelle instance de Speciality
                $speciality = new Speciality(self::$pdo, $id, $specialityName);
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
     * Récupère toutes les spécialités de la base de données et met en place une pagination.
     *
     * @return array Un tableau contenant toutes les spécialités.
     */
    public static function getAllSpecialitiesPagination(int $page, int $perPage): array
    {
        // Calculer l'offset pour la page spécifiée
        $offset = ($page - 1) * $perPage;

        // Requête SQL avec les clauses LIMIT et OFFSET
        $query = "SELECT * FROM Specialities LIMIT :perPage OFFSET :offset";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':perPage', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $specialitiesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $specialities = [];
        foreach ($specialitiesData as $specialityData) {
            $id = $specialityData['id'];
            $specialityName = $specialityData['speciality'];

            if (!isset(self::$specialities[$id])) {
                // Si la spécialité n'existe pas encore dans le tableau $specialities, créer une nouvelle instance de Speciality
                $speciality = new Speciality(self::$pdo, $id, $specialityName);
                // Ajouter la spécialité au tableau $specialities
                self::$specialities[$id] = $speciality;
            }

            // Ajouter la spécialité au tableau de résultats
            $specialities[] = self::$specialities[$id];
        }

        // Retourner le tableau contenant toutes les spécialités
        return $specialities;
    }

    public static function countSpecialities(): int
    {
        $query = "SELECT COUNT(*) AS total FROM Specialities";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $result['total'];
    }

    /**
     * Ajoute une nouvelle spécialité dans la base de données et dans la classe.
     *
     * @param string $speciality La spécialité à ajouter.
     * @return Speciality|null La nouvelle instance de Speciality si l'ajout est réussi, sinon null.
     */
    public static function addSpeciality(string $speciality): ?Speciality
    {
        // Vérifier si la spécialité existe déjà dans la base de données
        $query = "SELECT * FROM Specialities WHERE speciality = :speciality";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':speciality', $speciality);
        $stmt->execute();

        $specialityDatas = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($specialityDatas) {
            // La spécialité existe déjà, retourner null
            return null;
        }

        // Insérer la nouvelle spécialité dans la base de données et dans la classe
        $query = "INSERT INTO Specialities (speciality) VALUES (:speciality)";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':speciality', $speciality);
        $stmt->execute();

        // Récupérer l'id de la nouvelle spécialité insérée
        $newSpecialityId = self::$pdo->lastInsertId();

        // Créer une nouvelle instance de Speciality avec les données fournies
        $newSpeciality = new Speciality(self::$pdo, $newSpecialityId, $speciality);

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
    public static function updateSpecialityProperties(int $id, array $propertiesToUpdate): bool
    {
        // Récupérer l'instance de la spécialité correspondant à l'ID
        $speciality = self::getSpecialityById($id);

        if ($speciality) {
            // Mettre à jour les propriétés dans la classe
            foreach ($propertiesToUpdate as $property => $value) {
                if ($speciality->$property !== $value) {
                    $speciality->$property = $value;
                }
            }

            // Mettre à jour les propriétés dans la base de données
            $query = "UPDATE Specialities SET speciality = :speciality WHERE id = :id";
            $stmt = self::$pdo->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':speciality', $speciality->speciality);
            $stmt->execute();

            // Mettre à jour le tableau $specialities avec la spécialité modifiée
            self::$specialities[$id] = $speciality;

            // Retourner true pour indiquer que la mise à jour a réussi
            return true;
        }

        return false;
    }

    /**
     * Supprime une spécialité de la base de données et de la classe en utilisant son ID.
     *
     * @param mixed $id L'ID de la spécialité à supprimer.
     * @return json
     */
    public static function deleteSpecialityById($id)
    {
        // Vérifier si la spécialité est utilisée dans une ou plusieurs missions
        $query = "SELECT COUNT(*) FROM Missions WHERE speciality_id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $countInMission = $stmt->fetchColumn();

        // Vérifier si la spécialité est utilisée dans un ou plusieurs agents
        $query = "SELECT COUNT(*) FROM Agents_Specialities WHERE speciality_id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $countInAgentSpeciality = $stmt->fetchColumn();

        // Si la spécialité est utilisée ailleurs, ne pas le supprimer
        if ($countInMission > 0 || $countInAgentSpeciality > 0) {
            echo json_encode(array('status' => 'used'));
            exit;
        }

        // Supprimer la spécialité de la base de données
        $query = "DELETE FROM Specialities WHERE id = :id";
        $stmt = self::$pdo->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Supprimer les associations agent/spécialité correspondantes en utilisant une instance de la classe AgentSpeciality
        $agentSpeciality = new AgentSpeciality(self::$pdo, '', $id);
        $agentSpeciality->deleteAgentsBySpecialityId($id);

        // Supprimer la spécialité de la classe
        if (isset(self::$specialities[$id])) {
            unset(self::$specialities[$id]);

            echo json_encode(array('status' => 'success'));
            exit;
        }

        // Si la spécialité n'est pas trouvée dans la classe, retourner false pour indiquer une suppression non réussie
        echo json_encode(array('status' => 'error'));
        exit;
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
