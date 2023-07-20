<?php

// Configuration des paramètres de connexion à la base de données
$host = "sql111.infinityfree.com"; 
$db_name = "if0_34654273_SecretService"; 
$username = "if0_34654273";
$password = "zdxKMRM8tJDdi";

try {
    // Création de la connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->exec('SET NAMES utf8');
} catch (PDOException $e) {
    $errorMessage = "[" . date('Y-m-d H:i:s') . "] Erreur de connexion : " . $e->getMessage();
    error_log($errorMessage, 3, "../logs/error.log");
    die("Une erreur est survenue lors de la connexion à la base de données. Veuillez réessayer plus tard.");
    exit();
}