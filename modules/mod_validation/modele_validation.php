<?php
require_once __DIR__ . '/../../Connection.php';

class modele_validation extends Connection {
    public function __construct() {
        parent::__construct();
    }

    public function checkPendingOrder($userId) {
        // On cherche une commande EN_ATTENTE pour ce client
        $sql = "SELECT id, montant_total FROM Commande WHERE id_client = ? AND statut = 'EN_ATTENTE' ORDER BY date_heure DESC LIMIT 1";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function validateOrder($orderId, $userId) {
        try {
            self::$db->beginTransaction();

            // 1. Récupérer les infos de la commande
            $stmt = self::$db->prepare("SELECT montant_total, id_buvette FROM Commande WHERE id = ? AND id_client = ? AND statut = 'EN_ATTENTE'");
            $stmt->execute([$orderId, $userId]);
            $order = $stmt->fetch();

            if (!$order) throw new Exception("Commande introuvable ou déjà traitée.");

            $amount = $order['montant_total'];
            $buvetteId = $order['id_buvette'];

            // 2. Vérifier le solde du client
            $stmt = self::$db->prepare("SELECT solde FROM Utilisateur WHERE id = ? FOR UPDATE");
            $stmt->execute([$userId]);
            $balance = $stmt->fetchColumn();

            if ($balance < $amount) throw new Exception("Solde insuffisant.");

            // 3. Débiter le client
            $stmt = self::$db->prepare("UPDATE Utilisateur SET solde = solde - ? WHERE id = ?");
            $stmt->execute([$amount, $userId]);

            // 4. Créditer la buvette
            $stmt = self::$db->prepare("UPDATE Buvette SET solde = solde + ? WHERE id = ?");
            $stmt->execute([$amount, $buvetteId]);

            // 5. Passer la commande en PAYEE
            $stmt = self::$db->prepare("UPDATE Commande SET statut = 'PAYEE' WHERE id = ?");
            $stmt->execute([$orderId]);

            self::$db->commit();
            return true;
        } catch (Exception $e) {
            self::$db->rollBack();
            return false;
        }
    }

    public function refuseOrder($orderId, $userId) {
        // Si refusé, on passe juste le statut à REFUSEE (ou ANNULEE)
        // Note : Idéalement, il faudrait aussi remettre le stock des produits, 
        // mais pour l'instant on gère juste le statut.
        $sql = "UPDATE Commande SET statut = 'REFUSEE' WHERE id = ? AND id_client = ? AND statut = 'EN_ATTENTE'";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute([$orderId, $userId]);
    }
}
?>