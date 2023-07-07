<?php

namespace app\classes;

class Admin
{
    private static \PDO $pdo;
    private static array $admins = [];

    public function __construct($pdo)
    {
        self::$pdo = $pdo;
        $this->getAllAdmins($pdo);
    }

    /**
     * Récupère tous les administrateurs présents en base de données.
     */
    private static function getAllAdmins(): void
    {
        // Requête pour sélectionner tous les administrateurs
        $query = "SELECT * FROM Admins";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        // Récupération des données des administrateurs
        $adminsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Parcours des données des administrateurs
        foreach ($adminsData as $adminData) {
            $adminId = $adminData['id'];

            if (!isset(self::$admins[$adminId])) {
                // Ajout des données de l'administrateur au tableau statique $admins
                self::$admins[$adminId] = $adminData;
            }
        }
    }

    /**
     * Vérifie si les informations d'identification correspondent aux données présentes dans la classe.
     *
     * @param string $email    L'adresse e-mail à vérifier.
     * @param string $password Le mot de passe à vérifier.
     *
     * @return bool True si les informations d'identification sont valides, false sinon.
     */
    public function verifyCredentials(string $email, string $password): bool
    {
        foreach (self::$admins as $adminData) {
            if ($adminData['email'] === $email && $adminData['password'] === $password) {
                return true;
            }
        }

        return false;
    }
}
