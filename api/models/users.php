<?php

require_once ("base.php");

Class Users extends Base
{
    public function getUserFromEmail($email){
        $query = $this->db->prepare("
        SELECT user_id, name, password, email
        FROM users
        WHERE email = ?
    ");

        $query->execute([
            $email
        ]);

        return $query->fetch();
    }

    public function create($data) {

        $query = $this->db->prepare("
                INSERT INTO users
                (name, email, password, address, city, postal_code, country)
                VALUES(?, ?, ?, ?, ?, ?, ?)
            ");

        $result = $query->execute([
            $data["name"],
            $data["email"],
            password_hash($data["password"], PASSWORD_DEFAULT),
            $data["address"],
            $data["city"],
            $data["postal_code"],
            $data["country"]
        ]);

        return $this->db->lastInsertId();
    }

    public function login($data) {
        $email = $data['email'];
        $password = $data['password'];

        $user = $this->getUserFromEmail($email);

        if ($user) {
            $hashedPassword = $user['password'];

            if (password_verify($password, $hashedPassword)) {
                $jwtHandler = new JwtHandler();


                $payload = [
                    'user_id' => $user['user_id'],
                    'email' => $user['email']
                ];

                $token = $jwtHandler->generateToken($payload);

                return array('message' => 'Login com sucesso.', 'token' => $token);
            }
        }

        return array('message' => 'Email ou Password incorretos');
    }

}