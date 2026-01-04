<?php
require_once __DIR__ . "/../../Connection.php";

class modele_orga extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getOrgaById($id) {
        $stmt = self::$db->prepare("SELECT id, nom AS name, adresse AS address, solde AS balance FROM Buvette WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getUserRoleInOrga($userId, $orgaId) {
        $stmt = self::$db->prepare("SELECT role FROM etre_membre WHERE id_utilisateur = ? AND id_buvette = ?");
        $stmt->execute([$userId, $orgaId]);
        return $stmt->fetchColumn();
    }

    public function getProductsByOrga($orgaId) {
        $stmt = self::$db->prepare("SELECT id, nom AS name, prix_vente AS price, stock_actuel AS stock, description, categorie, id_buvette FROM Produit WHERE id_buvette = ?");
        $stmt->execute([$orgaId]);
        return $stmt->fetchAll();
    }

    public function getProductById($id) {
        $stmt = self::$db->prepare("SELECT id, nom AS name, prix_vente AS price, stock_actuel AS stock, description, id_buvette FROM Produit WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function isMember($userId, $orgaId) {
        $stmt = self::$db->prepare("SELECT 1 FROM etre_membre WHERE id_utilisateur = ? AND id_buvette = ?");
        $stmt->execute([$userId, $orgaId]);
        return (bool)$stmt->fetch();
    }

    public function joinOrga($userId, $orgaId) {
        $stmt = self::$db->prepare("INSERT INTO etre_membre (id_utilisateur, id_buvette, role) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $orgaId, 'ROLE_USER']);
    }
}
?>
