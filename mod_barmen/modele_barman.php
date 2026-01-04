<?php
require_once __DIR__ . "/../Connection.php";

class modele_barman extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getAllProducts() {
        try {
            $sql = "SELECT * FROM products ORDER BY name ASC";
            $stmt = self::$db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function searchClient($query) {
        $sql = "SELECT id, email, balance FROM users WHERE email LIKE :query LIMIT 10";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([':query' => "%$query%"]);
        return $stmt->fetchAll();
    }

    public function getClientById($id) {
        $sql = "SELECT id, email, balance FROM users WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function processTransaction($clientId, $totalAmount) {
        try {
            self::$db->beginTransaction();

            // Vérification solde
            $stmt = self::$db->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
            $stmt->execute([$clientId]);
            $currentBalance = $stmt->fetchColumn();

            if ($currentBalance === false) throw new Exception("Client introuvable.");
            if ($currentBalance < $totalAmount) throw new Exception("Solde insuffisant.");

            // Débit
            $newBalance = $currentBalance - $totalAmount;
            $updateStmt = self::$db->prepare("UPDATE users SET balance = ? WHERE id = ?");
            $updateStmt->execute([$newBalance, $clientId]);

            self::$db->commit();
            return true;
        } catch (Exception $e) {
            self::$db->rollBack();
            throw $e;
        }
    }
}
?>