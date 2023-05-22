<?php

class Base
{
    public $db;

    public function __construct()
    {
        $env = parse_ini_file('.env');

        $dbHost = $env['DB_HOST'];
        $dbName = $env['DB_NAME'];
        $dbUser = $env['DB_USER'];
        $dbPassword = $env['DB_PASSWORD'];

        try {
            $this->db = new PDO(
                "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
                $dbUser,
                $dbPassword,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
