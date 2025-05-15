<?php

class PasswordHasher {
    private $salt_length = 32;

    public function generateSalt() {
        return bin2hex(random_bytes($this->salt_length / 2));
    }

    public function hashPassword($password, $salt) {
        // Combine salt and password, then hash with bcrypt
        $salted_password = $salt . $password;
        return [
            'salt' => $salt,
            'hashed_password' => password_hash($salted_password, PASSWORD_BCRYPT)
        ];
    }

    public function hashAdminPassword($password) {
        $salt = $this->generateSalt();
        return $this->hashPassword($password, $salt);
    }
}

