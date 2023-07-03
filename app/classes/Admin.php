<?php

namespace app\classes;

class Admin
{
    private $pdo;
    private static array $admins = [];

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->loadAdmins();
    }

    private function loadAdmins(): void
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
