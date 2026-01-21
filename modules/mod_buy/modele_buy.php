<?php
require_once __DIR__ . '/../../Connection.php';

class modele_buy extends Connection {
    public function __construct() {
        parent::__construct();
    }

    public function getProductInfo($id) {
        // Ajout de la jointure pour récupérer le nom de la buvette
        $stmt = self::$db->prepare("
            SELECT p.*, b.nom AS nom_buvette 
            FROM Produit p
            JOIN Buvette b ON p.id_buvette = b.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function processPurchase($userId, $cart, $buvetteId) {
        try {
            self::$db->beginTransaction();

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Check balance in Solde table for this buvette
            $stmt = self::$db->prepare("SELECT solde FROM Solde WHERE id_utilisateur = ? AND id_buvette = ? FOR UPDATE");
            $stmt->execute([$userId, $buvetteId]);
            $balance = $stmt->fetchColumn();

            // If no record, balance is 0
            if ($balance === false) $balance = 0;

            if ($balance < $total) {
                throw new Exception("Solde insuffisant pour cette buvette (Solde: " . number_format($balance, 2) . " €).");
            }

            // Deduct from Solde
            $stmt = self::$db->prepare("UPDATE Solde SET solde = solde - ? WHERE id_utilisateur = ? AND id_buvette = ?");
            $stmt->execute([$total, $userId, $buvetteId]);

            $stmt = self::$db->prepare("INSERT INTO Commande (id_client, montant_total, id_buvette) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $total, $buvetteId]);
            $orderId = self::$db->lastInsertId();

            $stmt = self::$db->prepare("UPDATE Buvette SET solde = solde + ? WHERE id = ?");
            $stmt->execute([$total, $buvetteId]);

            foreach ($cart as $item) {
                $stmt = self::$db->prepare("INSERT INTO composer (id_commande, id_produit, quantite, prix_unit) VALUES (?, ?, ?, ?)");
                $stmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);

                $stmt = self::$db->prepare("UPDATE Produit SET stock_actuel = stock_actuel - ? WHERE id = ?");
                $stmt->execute([$item['quantity'], $item['id']]);
            }

            self::$db->commit();
            return true;
        } catch (Exception $e) {
            self::$db->rollBack();
            throw $e;
        }
    }
}
?>