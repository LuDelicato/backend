<?php
session_start();

$envContent = file_get_contents(__DIR__ . '/.env');
$env = explode("\n", $envContent);

foreach ($env as $data) {
    $data = trim($data);

    if (!empty($data) && $data[0] !== '#') {
        list($envVariable, $envValue) = explode('=', $data, 2);
        $envVariable = trim($envVariable);
        $envValue = trim($envValue);

        putenv("$envVariable=$envValue");
    }
}

$dbHost = getenv('DB_HOST');
$dbName = getenv('DB_DATABASE');
$dbUsername = getenv('DB_USERNAME');
$dbPassword = getenv('DB_PASSWORD');

try {
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUsername, $dbPassword);
    echo "funca!";
} catch (PDOException $err) {
    die("Conexão falhou: " . $err->getMessage());
}
