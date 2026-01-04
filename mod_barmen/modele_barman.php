<?php
require_once __DIR__ . "/../Connection.php";

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
        $sql = "SELECT u.id, u.email, em.solde AS balance 
                FROM Utilisateur u 
                LEFT JOIN etre_membre em ON u.id = em.id_utilisateur 
                WHERE u.email LIKE :query LIMIT 10";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([':query' => "%$query%"]);
        return $stmt->fetchAll();
    }

    public function getClientById($id) {
        $sql = "SELECT u.id, u.email, em.solde AS balance 
                FROM Utilisateur u 
                LEFT JOIN etre_membre em ON u.id = em.id_utilisateur 
                WHERE u.id = :id";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function processTransaction($clientId, $totalAmount) {
        try {
            self::$db->beginTransaction();

            // Vérification solde (sur la première buvette trouvée pour l'instant)
            $stmt = self::$db->prepare("SELECT solde, id_buvette FROM etre_membre WHERE id_utilisateur = ? FOR UPDATE");
            $stmt->execute([$clientId]);
            $membership = $stmt->fetch();

            if (!$membership) throw new Exception("Client non membre d'une buvette.");
            if ($membership['solde'] < $totalAmount) throw new Exception("Solde insuffisant.");

            // Débit
            $updateStmt = self::$db->prepare("UPDATE etre_membre SET solde = solde - ? WHERE id_utilisateur = ? AND id_buvette = ?");
            $updateStmt->execute([$totalAmount, $clientId, $membership['id_buvette']]);

            self::$db->commit();
            return true;
        } catch (Exception $e) {
            self::$db->rollBack();
            throw $e;
        }
    }
}
?>