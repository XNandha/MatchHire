<?php
// config/database.php

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {

            $config = require __DIR__ . '/config.php';
            $dbEnv = require __DIR__ . '/env.php';

            $dsn = "mysql:host={$dbEnv['DB_HOST']};dbname={$dbEnv['DB_NAME']};charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $dbEnv['DB_USER'], $dbEnv['DB_PASS'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                if ($config['environment'] === 'development') {
                    die("Database Connection Error: " . $e->getMessage());
                } else {
                    die("Internal server error.");
                }
            }
        }

        return self::$instance;
    }
}
