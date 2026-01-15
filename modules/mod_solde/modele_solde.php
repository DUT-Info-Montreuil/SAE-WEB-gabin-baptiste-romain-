<?php
require_once __DIR__ . "/../../Connection.php";

class modele_solde extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getBalance($userId) {
        $stmt = self::$db->prepare("SELECT solde FROM Utilisateur WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() ?: 0;
    }

    public function getOrderHistory($userId) {
        $sql = "SELECT c.id, c.date_heure, c.montant_total, b.nom AS buvette_name 
                FROM Commande c
                JOIN Buvette b ON c.id_buvette = b.id
                WHERE c.id_client = ?
                ORDER BY c.date_heure DESC LIMIT 10";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll();

        foreach ($orders as &$order) {
            $stmt = self::$db->prepare("SELECT p.nom, co.quantite FROM composer co JOIN Produit p ON co.id_produit = p.id WHERE co.id_commande = ?");
            $stmt->execute([$order['id']]);
            $order['items'] = $stmt->fetchAll();
        }
        return $orders;
    }

    public function addMoney($userId, $amount) {
        try {
            self::$db->beginTransaction();

            $update = self::$db->prepare("UPDATE Utilisateur SET solde = solde + ? WHERE id = ?");
            $update->execute([$amount, $userId]);

            self::$db->commit();
            return true;
        } catch (Exception $e) {
            self::$db->rollBack();
            return false;
        }
    }
}
?>