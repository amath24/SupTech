<?php
/**
 * Configuration de la base de données
 * Base de données : gestion_academique
 */

class Database
{
    private static ?PDO $pdo = null;

    /**
     * Établit la connexion à la base de données
     */
    public static function connect(): PDO
    {
        if (self::$pdo === null) {
            $host = 'localhost';
            $dbname = 'gestion_academique';
            $user = 'root';
            $password = '';

            try {
                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                die('Erreur de connexion à la base de données: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    /**
     * Obtient l'instance de connexion PDO
     */
    public static function getInstance(): PDO
    {
        return self::connect();
    }
}
