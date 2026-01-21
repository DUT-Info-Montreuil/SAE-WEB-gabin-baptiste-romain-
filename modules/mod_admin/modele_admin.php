<?php
require_once __DIR__ . "/../../Connection.php";

class modele_admin extends Connection {
    public function __construct() {
        parent::__construct();
    }

    public function getBuvettes() {
        $stmt = self::$db->query("SELECT * FROM Buvette ORDER BY nom");
        return $stmt->fetchAll();
    }

    public function getBuvette($id) {
        $stmt = self::$db->prepare("SELECT * FROM Buvette WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createBuvette($nom, $adresse, $photo, $prix, $email, $telephone) {
        $stmt = self::$db->prepare("INSERT INTO Buvette (nom, adresse, photo, solde, prix_adhesion, email, telephone) VALUES (?, ?, ?, 0, ?, ?, ?)");
        return $stmt->execute([$nom, $adresse, $photo, $prix, $email, $telephone]);
    }

    public function updateBuvette($id, $nom, $adresse, $photo = null, $prix = null, $email = null, $telephone = null) {
        if ($photo) {
            $stmt = self::$db->prepare("UPDATE Buvette SET nom = ?, adresse = ?, photo = ?, prix_adhesion = ?, email = ?, telephone = ? WHERE id = ?");
            return $stmt->execute([$nom, $adresse, $photo, $prix, $email, $telephone, $id]);
        } else {
            $stmt = self::$db->prepare("UPDATE Buvette SET nom = ?, adresse = ?, prix_adhesion = ?, email = ?, telephone = ? WHERE id = ?");
            return $stmt->execute([$nom, $adresse, $prix, $email, $telephone, $id]);
        }
    }

    public function assignRole($email, $buvetteId, $role) {
        $stmt = self::$db->prepare("SELECT id FROM Utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $userId = $stmt->fetchColumn();
        
        if (!$userId) return false;

        $stmt = self::$db->prepare("INSERT INTO etre_membre (id_utilisateur, id_buvette, role) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE role = ?");
        return $stmt->execute([$userId, $buvetteId, $role, $role]);
    }

    public function getStaff($buvetteId) {
        $stmt = self::$db->prepare("SELECT u.email, u.nom, u.prenom, em.role 
                                   FROM etre_membre em 
                                   JOIN Utilisateur u ON em.id_utilisateur = u.id 
                                   WHERE em.id_buvette = ? AND em.role IN ('ROLE_GESTION', 'ROLE_BARMAN')");
        $stmt->execute([$buvetteId]);
        return $stmt->fetchAll();
    }

    public function deleteBuvette($id) {
        // Warning: this might fail if foreign keys exist. 
        // For now we assume we might need to delete dependencies first or rely on cascade.
        // But usually we don't delete buvettes easily.
        $stmt = self::$db->prepare("DELETE FROM Buvette WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function isAdmin($userId) {
        $stmt = self::$db->prepare("SELECT est_admin FROM Utilisateur WHERE id = ?");
        $stmt->execute([$userId]);
        return (bool)$stmt->fetchColumn();
    }
}
?>