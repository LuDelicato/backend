<?php

class Base
{
    public $db;
    public $secretKey;

    public function __construct()
    {
        $dbHost = ENV['DB_HOST'];
        $dbName = ENV['DB_NAME'];
        $dbUser = ENV['DB_USER'];
        $dbPassword = ENV['DB_PASSWORD'];
        $this->secretKey = ENV['JWT_SECRET_KEY'];

        try {
            $this->db = new PDO(
                "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
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
