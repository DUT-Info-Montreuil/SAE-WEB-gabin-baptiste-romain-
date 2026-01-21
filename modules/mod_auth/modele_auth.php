<?php
require_once __DIR__ . "/../../Connection.php";

class modele_auth extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function findByEmail($email) {
        $stmt = self::$db->prepare("SELECT * FROM Utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function createUser($nom, $prenom, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = self::$db->prepare("INSERT INTO Utilisateur (nom, prenom, email, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nom, $prenom, $email, $hashed_password]);
    }

    public function userExists($email) {
        $stmt = self::$db->prepare("SELECT id FROM Utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

}
?>