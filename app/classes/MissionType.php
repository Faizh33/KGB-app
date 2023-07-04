<?php

namespace app\classes;

class MissionType
{
    private int $id;
    private string $type;
    private $pdo;

    private static array $missionTypes = [];

    public function __construct($pdo, int $id = NULL, string $type = '')
    {
        $this->pdo = $pdo;
        $this->id = $id ?? 0;
        $this->type = $type;

        self::$missionTypes[$id] = $this;
    }

    // Méthode qui récupère un type de mission en fonction de son id
    /**
     * Récupère un type de mission à partir de son identifiant.
     *
     * @param int $id L'identifiant du type de mission à récupérer.
     * @return MissionType|null Le type de mission correspondant ou null si non trouvé.
     */
    public static function getMissionTypeById($pdo, int $id)
    {
        // Vérifier si le type de mission existe déjà dans le tableau des types de mission
        if (isset(self::$missionTypes[$id])) {
            return self::$missionTypes[$id];
        }

        // Préparer la requête SQL pour sélectionner le type de mission avec l'identifiant donné
        $query = "SELECT * FROM MissionTypes WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Récupérer les données du type de mission
        $typeDatas = $stmt->fetch(\PDO::FETCH_ASSOC);
        $id = $typeDatas['id'];
        $type = $typeDatas['type'];

        // Vérifier si des données de type de mission ont été trouvées
        if ($typeDatas) {
            // Créer une nouvelle instance de la classe MissionType avec les données récupérées
            $missionType = new MissionType($pdo, $id, $type);

            // Ajouter le type de mission au tableau des types de mission pour une utilisation ultérieure
            self::$missionTypes[$id] = $missionType;

            return $missionType;
        }

        // Si aucune correspondance de type de mission n'est trouvée, renvoyer null
        return null;
    }

    /**
     * Récupère tous les types de mission de la base de données et les insère dans la classe.
     *
     * @return array Les types de mission récupérés.
     */
    public function getAllMissionTypes(): array
    {
        // Préparer la requête SQL pour sélectionner tous les types de mission
        $query = "SELECT * FROM MissionTypes";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        // Récupérer les données de tous les types de mission
        $missionTypesData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Tableau pour stocker les types de mission
        $missionTypes = [];

        // Parcourir les données de chaque type de mission
        foreach ($missionTypesData as $missionTypeData) {
            $missionTypeId = $missionTypeData['id'];
            // Vérifier si le type de mission n'est pas déjà présent dans le tableau des types de mission
            if (!isset(self::$missionTypes[$missionTypeId])) {
                // Créer une nouvelle instance de la classe MissionType avec les données du type de mission
                $missionType = new MissionType($this->pdo, $missionTypeId, $missionTypeData['type']);
                // Ajouter le type de mission au tableau des types de mission pour une utilisation ultérieure
                self::$missionTypes[$missionTypeId] = $missionType;
            }

            // Ajouter le type de mission au tableau des types de mission à retourner
            $missionTypes[] = self::$missionTypes[$missionTypeId];
        }

        return $missionTypes;
    }

    /**
     * Ajoute un nouveau type de mission.
     *
     * @param string $type Le nom du nouveau type de mission.
     * @return MissionType|null Le nouveau type de mission ajouté ou null si le type existe déjà.
     */
    public function addMissionType(string $type): ?MissionType
    {
        // Vérifier si le type de mission existe déjà dans la base de données
        $query = "SELECT * FROM MissionTypes WHERE type = :type";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();

        $typeDatas = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Si le type de mission existe déjà, retourner null
        if ($typeDatas) {
            return null;
        }

        // Insérer le nouveau type de mission dans la base de données et dans la classe
        $query = "INSERT INTO MissionTypes (type) VALUES (:type)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();

        // Récupérer l'identifiant du nouveau type de mission inséré
        $newMissionTypeId = $this->pdo->lastInsertId();
        // Créer une instance de la classe MissionType avec le nouveau type de mission
        $newMissionType = new MissionType($this->pdo, $newMissionTypeId, $type);
        // Ajouter le nouveau type de mission au tableau des types de mission pour une utilisation ultérieure
        self::$missionTypes[$newMissionTypeId] = $newMissionType;

        return $newMissionType;
    }

    /**
     * Met à jour les propriétés du type de mission dans la base de données et dans la classe.
     *
     * @param array $propertiesToUpdate Les propriétés à mettre à jour avec leurs nouvelles valeurs.
     * @return bool Indique si la mise à jour a été effectuée avec succès.
     */
    public function updateProperties(array $propertiesToUpdate): bool
    {
        // Récupérer l'identifiant du type de mission
        $id = $this->getId();

        // Mettre à jour les propriétés dans la classe
        foreach ($propertiesToUpdate as $property => $value) {
            if ($this->$property !== $value) {
                $this->$property = $value;
            }
        }

        // Mettre à jour les propriétés dans la base de données
        $query = "UPDATE MissionTypes SET type = :type WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $this->type);
        $stmt->execute();

        // Mettre à jour le tableau $missionTypes
        self::$missionTypes[$id] = $this;

        return true;
    }

    /**
     * Supprime un type de mission de la base de données et de la classe en fonction de son identifiant.
     *
     * @param mixed $id L'identifiant du type de mission à supprimer.
     * @return bool Indique si la suppression a été effectuée avec succès.
     */
    public function deleteMissionTypeById($id): bool
    {
        // Supprimer le type de mission de la base de données
        $query = "DELETE FROM MissionTypes WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Vérifier si le type de mission existe dans le tableau des types de mission
        if (isset(self::$missionTypes[$id])) {
            // Supprimer le type de mission du tableau des types de mission
            unset(self::$missionTypes[$id]);
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
