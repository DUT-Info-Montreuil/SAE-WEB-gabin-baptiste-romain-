<?php
require_once __DIR__ . "/../Connection.php";

class modele_product extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getProductById($id) {
        $stmt = self::$db->prepare("SELECT id, nom AS name, prix_vente AS price, stock_actuel AS stock, description FROM Produit WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>