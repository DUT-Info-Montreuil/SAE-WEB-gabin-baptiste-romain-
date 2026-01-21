<?php
require_once __DIR__ . "/../../Connection.php";

class modele_barman extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getAllProducts() {
        try {
            $sql = "SELECT id, nom AS name, prix_vente AS price, stock_actuel AS stock FROM Produit ORDER BY nom ASC";
            $stmt = self::$db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function searchClient($query) {
        $sql = "SELECT id, email, solde AS balance 
                FROM Utilisateur 
                WHERE email LIKE :query LIMIT 10";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([':query' => "%$query%"]);
        return $stmt->fetchAll();
    }

    public function getClientById($id) {
        $sql = "SELECT id, email, solde AS balance 
                FROM Utilisateur 
                WHERE id = :id";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getClientByEmail($email) {
        $sql = "SELECT id, email, solde AS balance 
                FROM Utilisateur 
                WHERE email = :email";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function getPendingOrders($buvetteId) {
        // On récupère les commandes qui n'ont pas de serveur assigné (Click & Collect)
        // On exclut celles qui sont EN_ATTENTE de validation client
        $sql = "SELECT c.id, c.date_heure, c.montant_total, u.nom, u.prenom, u.email 
                FROM Commande c
                JOIN Utilisateur u ON c.id_client = u.id
                WHERE c.id_buvette = ? 
                AND c.id_serveur IS NULL 
                AND (c.statut IS NULL OR c.statut = 'PAYEE')
                ORDER BY c.date_heure ASC";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$buvetteId]);
        $orders = $stmt->fetchAll();

        foreach ($orders as &$order) {
            $stmt = self::$db->prepare("SELECT p.nom, co.quantite FROM composer co JOIN Produit p ON co.id_produit = p.id WHERE co.id_commande = ?");
            $stmt->execute([$order['id']]);
            $order['items'] = $stmt->fetchAll();
        }
        return $orders;
    }

    public function assignBarmanToOrder($orderId, $barmanId) {
        $stmt = self::$db->prepare("UPDATE Commande SET id_serveur = ? WHERE id = ?");
        return $stmt->execute([$barmanId, $orderId]);
    }

    public function processTransaction($clientId, $barmanId, $buvetteId, $cart) {
        try {
            self::$db->beginTransaction();

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['qty'];
            }

            // 1. Vérifier le solde (sans débiter pour l'instant)
            $stmt = self::$db->prepare("SELECT solde FROM Utilisateur WHERE id = ?");
            $stmt->execute([$clientId]);
            $currentBalance = $stmt->fetchColumn();

            if ($currentBalance === false) throw new Exception("Client introuvable.");
            if ($currentBalance < $total) throw new Exception("Solde client insuffisant.");

            // 2. Créer la commande avec le statut EN_ATTENTE
            // On ne touche PAS au solde du client ni de la buvette ici.
            // Ce sera fait lors de la validation par le client.
            
            $stmt = self::$db->prepare("INSERT INTO Commande (id_client, id_serveur, id_buvette, montant_total, statut) VALUES (?, ?, ?, ?, 'EN_ATTENTE')");
            $stmt->execute([$clientId, $barmanId, $buvetteId, $total]);
            $orderId = self::$db->lastInsertId();

            // 3. Mettre à jour les stocks (on réserve les produits)
            foreach ($cart as $item) {
                $stmt = self::$db->prepare("INSERT INTO composer (id_commande, id_produit, quantite, prix_unit) VALUES (?, ?, ?, ?)");
                $stmt->execute([$orderId, $item['id'], $item['qty'], $item['price']]);

                $stmt = self::$db->prepare("UPDATE Produit SET stock_actuel = stock_actuel - ? WHERE id = ?");
                $stmt->execute([$item['qty'], $item['id']]);
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