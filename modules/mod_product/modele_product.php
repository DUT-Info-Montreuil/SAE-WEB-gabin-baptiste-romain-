<?php
require_once __DIR__ . "/../../Connection.php";

class modele_product extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getProductById($id) {
        $stmt = self::$db->prepare("SELECT id, nom AS name, prix_vente AS price, stock_actuel AS stock, description, id_buvette, photo FROM Produit WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function isMember($userId, $buvetteId) {
        $stmt = self::$db->prepare("SELECT 1 FROM etre_membre WHERE id_utilisateur = ? AND id_buvette = ?");
        $stmt->execute([$userId, $buvetteId]);
        return (bool)$stmt->fetch();
    }
}
?>