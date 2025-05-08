<?php
class PasswordHasher {
    private $salt_length = 32;

    public function generateSalt() {
        return bin2hex(random_bytes($this->salt_length / 2));
    }

    public function hashPassword($password, $salt) {
        return hash('sha256', $salt . $password);
    }

    public function hashAdminPassword($password) {
        $salt = $this->generateSalt();
        $hashed_password = $this->hashPassword($password, $salt);
        return [
            'salt' => $salt,
            'hashed_password' => $hashed_password
        ];
    }
}

// Hash the admin password
$hasher = new PasswordHasher();
$result = $hasher->hashAdminPassword('admin');

// Output the results for database insertion
echo "Salt: " . $result['salt'] . "\n";
echo "Hashed Password: " . $result['hashed_password'] . "\n";
?>
