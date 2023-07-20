<?php

// Configuration des paramètres de connexion à la base de données
$host = "localhost"; 
$db_name = "SecretService"; 
$username = "root";
$password = "";

try {
    // Création de la connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
} catch (PDOException $e) {
    $errorMessage = "[" . date('Y-m-d H:i:s') . "] Erreur de connexion : " . $e->getMessage();
    error_log($errorMessage, 3, "../logs/error.log");
    exit();
}