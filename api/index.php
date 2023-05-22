<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$url_parts = isset($_SERVER['REQUEST_URI']) ? explode("/", $_SERVER['REQUEST_URI']) : [];

define("ENV", parse_ini_file(".env"));

$controller = "home";

$allowed_controllers = [
    "products",
    "api"
];

if (!empty($url_parts[1])) {
    $controller = $url_parts[1];
}

if (!empty($url_parts[2])) {
    $id = $url_parts[2];
} else {
    $id = null;
}

/* Verificar se o controller pretendido está na nossa whitelist */
if ( !in_array($controller, $allowed_controllers)){
    http_response_code(404);
    die("Não encontrado");
}

require ("controllers/" . $controller . ".php");
