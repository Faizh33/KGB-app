<?php

namespace app\classes;

class Admin
{
    private $pdo;
    private static array $admins = [];

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->getAllAdmins();
    }

    //Méthode qui récupère tous les admins présents en base de données
    private function getAllAdmins(): void
    {
        $query = "SELECT * FROM Admins";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $adminsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($adminsData as $adminData) {
            $adminId = $adminData['id'];

            if (!isset(self::$admins[$adminId])) {
                self::$admins[$adminId] = $adminData;
            }
        }
    }

    //Méthode qui vérifie si les informations d'identification correspondent aux données présentes dans la classe
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
