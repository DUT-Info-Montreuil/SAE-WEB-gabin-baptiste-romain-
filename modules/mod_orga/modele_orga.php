<?php
require_once __DIR__ . "/../../Connection.php";

class modele_orga extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getOrgaById($id) {
        $stmt = self::$db->prepare("SELECT id, nom AS name, adresse AS address, solde AS balance, photo, prix_adhesion, email, telephone FROM Buvette WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getUserRoleInOrga($userId, $orgaId) {
        $stmt = self::$db->prepare("SELECT role FROM etre_membre WHERE id_utilisateur = ? AND id_buvette = ?");
        $stmt->execute([$userId, $orgaId]);
        return $stmt->fetchColumn();
    }

    public function getProductsByOrga($orgaId) {
        $stmt = self::$db->prepare("SELECT id, nom AS name, prix_vente AS price, stock_actuel AS stock, description, categorie, id_buvette, photo FROM Produit WHERE id_buvette = ?");
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

    public function getUserBalance($userId, $buvetteId) {
        $stmt = self::$db->prepare("SELECT solde FROM Solde WHERE id_utilisateur = ? AND id_buvette = ?");
        $stmt->execute([$userId, $buvetteId]);
        return $stmt->fetchColumn() ?: 0;
    }

    public function hasPendingRequest($userId, $orgaId) {
        $stmt = self::$db->prepare("SELECT 1 FROM Demande_Adhesion WHERE id_utilisateur = ? AND id_buvette = ? AND statut = 'EN_ATTENTE'");
        $stmt->execute([$userId, $orgaId]);
        return (bool)$stmt->fetch();
    }

    public function createJoinRequest($userId, $orgaId, $nom, $prenom, $adresse, $majeur) {
        // Prevent duplicate requests
        if ($this->hasPendingRequest($userId, $orgaId)) return false;

        $stmt = self::$db->prepare("INSERT INTO Demande_Adhesion (id_utilisateur, id_buvette, nom_form, prenom_form, adresse_form, est_majeur, statut) VALUES (?, ?, ?, ?, ?, ?, 'EN_ATTENTE')");
        return $stmt->execute([$userId, $orgaId, $nom, $prenom, $adresse, $majeur]);
    }

    public function cancelJoinRequest($userId, $orgaId) {
        $stmt = self::$db->prepare("DELETE FROM Demande_Adhesion WHERE id_utilisateur = ? AND id_buvette = ? AND statut = 'EN_ATTENTE'");
        return $stmt->execute([$userId, $orgaId]);
    }

    public function leaveOrga($userId, $orgaId) {
        $stmt = self::$db->prepare("UPDATE etre_membre SET est_demissionnaire = 1 WHERE id_utilisateur = ? AND id_buvette = ?");
        return $stmt->execute([$userId, $orgaId]);
    }

    public function getMemberInfo($userId, $orgaId) {
        $stmt = self::$db->prepare("SELECT * FROM etre_membre WHERE id_utilisateur = ? AND id_buvette = ?");
        $stmt->execute([$userId, $orgaId]);
        return $stmt->fetch();
    }
}
?>
