<?php
require_once __DIR__ . "/../../Connection.php";

class modele_home extends Connection {
    
    public function __construct() {
        parent::__construct();
    }

    public function getAllOrgas($search = '') {
        $sql = "SELECT id, nom AS name, adresse AS address, photo FROM Buvette";
        $params = [];
        if (!empty($search)) {
            $sql .= " WHERE nom LIKE ? OR adresse LIKE ?";
            $params = ["%$search%", "%$search%"];
        }
        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTopOrgas() {
        $sql = "SELECT b.id, b.nom AS name, b.adresse AS address, b.photo, COUNT(em.id_utilisateur) as member_count 
                FROM Buvette b 
                LEFT JOIN etre_membre em ON b.id = em.id_buvette 
                GROUP BY b.id 
                ORDER BY member_count DESC 
                LIMIT 5";
        $stmt = self::$db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function toggleFavorite($userId, $orgaId) {
        $stmt = self::$db->prepare("SELECT 1 FROM Favoris WHERE id_utilisateur = ? AND id_buvette = ?");
        $stmt->execute([$userId, $orgaId]);
        if ($stmt->fetch()) {
            $stmt = self::$db->prepare("DELETE FROM Favoris WHERE id_utilisateur = ? AND id_buvette = ?");
            return $stmt->execute([$userId, $orgaId]);
        } else {
            $stmt = self::$db->prepare("INSERT INTO Favoris (id_utilisateur, id_buvette) VALUES (?, ?)");
            return $stmt->execute([$userId, $orgaId]);
        }
    }

    public function getFavorites($userId) {
        $stmt = self::$db->prepare("SELECT b.id, b.nom AS name, b.adresse AS address, b.photo 
                                   FROM Buvette b 
                                   JOIN Favoris f ON b.id = f.id_buvette 
                                   WHERE f.id_utilisateur = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getFavoriteIds($userId) {
        $stmt = self::$db->prepare("SELECT id_buvette FROM Favoris WHERE id_utilisateur = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getUserBalance($id) {
        $stmt = self::$db->prepare("SELECT solde FROM Utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() ?: 0;
    }

    public function hasStaffRole($userId) {
        // Check Admin
        $stmt = self::$db->prepare("SELECT est_admin FROM Utilisateur WHERE id = ?");
        $stmt->execute([$userId]);
        if ($stmt->fetchColumn()) return true;

        // Check Buvette Roles
        $stmt = self::$db->prepare("SELECT 1 FROM etre_membre WHERE id_utilisateur = ? AND role IN ('ROLE_GESTION', 'ROLE_BARMAN') LIMIT 1");
        $stmt->execute([$userId]);
        return (bool)$stmt->fetchColumn();
    }
}
?>