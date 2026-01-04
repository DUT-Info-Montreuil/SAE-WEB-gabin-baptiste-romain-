<?php
require_once __DIR__ . "/../Connection.php";

class modele_orga extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getOrgaById($id) {
        $stmt = self::$db->prepare("SELECT id, nom AS name, adresse AS address FROM Buvette WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getProductsByOrga($orgaId) {
        $stmt = self::$db->prepare("SELECT id, nom AS name, prix_vente AS price, stock_actuel AS stock FROM Produit WHERE id_buvette = ?");
        $stmt->execute([$orgaId]);
        return $stmt->fetchAll();
    }
}
?>