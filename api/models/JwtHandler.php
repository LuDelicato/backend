<?php
require_once("base.php");
require_once(__DIR__ . '/../../vendor/autoload.php');

use Firebase\JWT\JWT;

class JwtHandler extends Base {
    public $secretKey;
    public $algorithm;

    public function __construct() {
        $this->secretKey = ENV['JWT_SECRET_KEY'];
        $this->algorithm = 'HS256';

        if (empty($this->secretKey)) {
            http_response_code(500);
            die('{"message" : "Internal Server Error: JWT configuration missing."}');
        }
    }

    public function generateToken($payload) {
        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function validateToken($token) {
        try {
            return JWT::decode($token, $this->secretKey);
        } catch (Exception $e) {
            return false;
        }
    }
}
