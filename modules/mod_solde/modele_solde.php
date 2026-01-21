<?php
require_once __DIR__ . "/../../Connection.php";

class modele_solde extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getBalances($userId, $buvetteId = null) {
        $sql = "SELECT b.id, b.nom, COALESCE(s.solde, 0) as solde 
                FROM Buvette b 
                LEFT JOIN Solde s ON b.id = s.id_buvette AND s.id_utilisateur = ?";
        $params = [$userId];

        if ($buvetteId) {
            $sql .= " WHERE b.id = ?";
            $params[] = $buvetteId;
        }

        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getOrderHistory($userId, $buvetteId = null) {
        $sql = "SELECT c.id, c.date_heure, c.montant_total, b.nom AS buvette_name 
                FROM Commande c
                JOIN Buvette b ON c.id_buvette = b.id
                WHERE c.id_client = ?";
        $params = [$userId];

        if ($buvetteId) {
            $sql .= " AND c.id_buvette = ?";
            $params[] = $buvetteId;
        }

        $sql .= " ORDER BY c.date_heure DESC LIMIT 10";

        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();

        foreach ($orders as &$order) {
            $stmt = self::$db->prepare("SELECT p.nom, co.quantite FROM composer co JOIN Produit p ON co.id_produit = p.id WHERE co.id_commande = ?");
            $stmt->execute([$order['id']]);
            $order['items'] = $stmt->fetchAll();
        }
        return $orders;
    }

    public function addMoney($userId, $buvetteId, $amount) {
        try {
            self::$db->beginTransaction();

            // Check if record exists, if not create it
            $check = self::$db->prepare("SELECT 1 FROM Solde WHERE id_utilisateur = ? AND id_buvette = ?");
            $check->execute([$userId, $buvetteId]);
            
            if ($check->fetch()) {
                $update = self::$db->prepare("UPDATE Solde SET solde = solde + ? WHERE id_utilisateur = ? AND id_buvette = ?");
                $update->execute([$amount, $userId, $buvetteId]);
            } else {
                $insert = self::$db->prepare("INSERT INTO Solde (id_utilisateur, id_buvette, solde) VALUES (?, ?, ?)");
                $insert->execute([$userId, $buvetteId, $amount]);
            }

            self::$db->commit();
            return true;
        } catch (Exception $e) {
            self::$db->rollBack();
            return false;
        }
    }
}
?>