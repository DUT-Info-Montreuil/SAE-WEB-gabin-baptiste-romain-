<?php
require_once __DIR__ . '/../../Connection.php';

class modele_buy extends Connection {
    public function __construct() {
        parent::__construct();
    }

    public function getProductInfo($id) {
        $stmt = self::$db->prepare("SELECT * FROM Produit WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function processPurchase($userId, $cart) {
        try {
            self::$db->beginTransaction();

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Check Balance
            $stmt = self::$db->prepare("SELECT solde FROM Utilisateur WHERE id = ? FOR UPDATE");
            $stmt->execute([$userId]);
            $balance = $stmt->fetchColumn();

            if ($balance < $total) {
                throw new Exception("Solde insuffisant.");
            }

            // Deduct Balance
            $stmt = self::$db->prepare("UPDATE Utilisateur SET solde = solde - ? WHERE id = ?");
            $stmt->execute([$total, $userId]);

            // Create Order
            // On récupère l'id_buvette du premier produit du panier pour lier la commande
            $firstItem = reset($cart);
            $buvetteId = $firstItem['id_buvette'];

            $stmt = self::$db->prepare("INSERT INTO Commande (id_client, montant_total, id_buvette) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $total, $buvetteId]);
            $orderId = self::$db->lastInsertId();

            // Ajouter le montant au solde de la buvette
            $stmt = self::$db->prepare("UPDATE Buvette SET solde = solde + ? WHERE id = ?");
            $stmt->execute([$total, $buvetteId]);

            // Create Order items and update stock
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