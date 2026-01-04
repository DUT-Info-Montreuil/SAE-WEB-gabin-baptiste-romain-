<?php
require_once __DIR__ . "/../Connection.php";

class modele_solde extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getBalance($userId) {
        $stmt = self::$db->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function addMoney($userId, $amount) {
        try {
            self::$db->beginTransaction();

            $stmt = self::$db->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
            $stmt->execute([$userId]);
            $currentBalance = $stmt->fetchColumn();

            $newBalance = $currentBalance + $amount;

            $update = self::$db->prepare("UPDATE users SET balance = ? WHERE id = ?");
            $update->execute([$newBalance, $userId]);

            self::$db->commit();
            return true;
        } catch (Exception $e) {
            self::$db->rollBack();
            return false;
        }
    }
}
?>