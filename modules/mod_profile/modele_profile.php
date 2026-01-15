<?php
require_once __DIR__ . "/../../Connection.php";

class modele_profile extends Connection {
    public function __construct() {
        parent::__construct();
    }

    public function getUserById($id) {
        $stmt = self::$db->prepare("SELECT id, nom, prenom, email FROM Utilisateur WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateProfile($id, $nom, $prenom) {
        $stmt = self::$db->prepare("UPDATE Utilisateur SET nom = ?, prenom = ? WHERE id = ?");
        return $stmt->execute([$nom, $prenom, $id]);
    }

    public function getUserRoles($userId) {
        $sql = "SELECT b.id, b.nom AS buvette_name, em.role 
                FROM etre_membre em
                JOIN Buvette b ON em.id_buvette = b.id
                WHERE em.id_utilisateur = ? AND em.role IN ('ROLE_BARMAN', 'ROLE_GESTION')";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
?>
