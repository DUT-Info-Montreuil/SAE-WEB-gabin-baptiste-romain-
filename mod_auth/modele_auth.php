<?php
require_once __DIR__ . "/../Connection.php";

class modele_auth extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function findByEmail($email) {
        $stmt = self::$db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function userExists($email) {
        $stmt = self::$db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    public function createUser($email, $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = self::$db->prepare("INSERT INTO users (email, password, balance) VALUES (?, ?, 0.00)");
        return $stmt->execute([$email, $hashed]);
    }
}
?>