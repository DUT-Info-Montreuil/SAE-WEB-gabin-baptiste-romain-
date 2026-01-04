<?php
require_once __DIR__ . "/../Connection.php";

class modele_solde extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getBalance($userId) {
        $stmt = self::$db->prepare("SELECT solde FROM etre_membre WHERE id_utilisateur = ? LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() ?: 0;
    }

    public function addMoney($userId, $amount) {
        try {
            self::$db->beginTransaction();

            $stmt = self::$db->prepare("SELECT solde, id_buvette FROM etre_membre WHERE id_utilisateur = ? FOR UPDATE LIMIT 1");
            $stmt->execute([$userId]);
            $membership = $stmt->fetch();

            if (!$membership) {
                // Si l'utilisateur n'est membre d'aucune buvette, on ne peut pas ajouter de solde dans ce schéma
                throw new Exception("L'utilisateur doit être membre d'au moins une buvette.");
            }

            $update = self::$db->prepare("UPDATE etre_membre SET solde = solde + ? WHERE id_utilisateur = ? AND id_buvette = ?");
            $update->execute([$amount, $userId, $membership['id_buvette']]);

            self::$db->commit();
            return true;
        } catch (Exception $e) {
            self::$db->rollBack();
            return false;
        }
    }
}
?>