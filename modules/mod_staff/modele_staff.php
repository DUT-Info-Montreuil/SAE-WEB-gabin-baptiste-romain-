<?php
require_once __DIR__ . "/../../Connection.php";

class modele_staff extends Connection {
    public function __construct() {
        parent::__construct();
    }

    public function isSuperAdmin($userId) {
        $stmt = self::$db->prepare("SELECT est_admin FROM Utilisateur WHERE id = ?");
        $stmt->execute([$userId]);
        return (bool)$stmt->fetchColumn();
    }

    public function getStaffRoles($userId) {
        $roles = [];

        // Check for Super Admin
        if ($this->isSuperAdmin($userId)) {
            $roles[] = [
                'type' => 'admin',
                'title' => 'Super Administrateur',
                'buvette_name' => 'Administration Globale',
                'link' => 'index.php?page=admin'
            ];
        }

        // Check for specific roles in buvettes
        $stmt = self::$db->prepare("
            SELECT b.id, b.nom, em.role 
            FROM etre_membre em
            JOIN Buvette b ON em.id_buvette = b.id
            WHERE em.id_utilisateur = ? AND em.role IN ('ROLE_GESTION', 'ROLE_BARMAN')
            ORDER BY b.nom
        ");
        $stmt->execute([$userId]);
        $memberships = $stmt->fetchAll();

        foreach ($memberships as $m) {
            if ($m['role'] === 'ROLE_GESTION') {
                $roles[] = [
                    'type' => 'gestion',
                    'title' => 'Gestionnaire',
                    'buvette_name' => $m['nom'],
                    'link' => 'index.php?page=gestion&id=' . $m['id']
                ];
            } elseif ($m['role'] === 'ROLE_BARMAN') {
                $roles[] = [
                    'type' => 'barman',
                    'title' => 'Barman',
                    'buvette_name' => $m['nom'],
                    'link' => 'index.php?page=barman&action=caisse&id=' . $m['id']
                ];
            }
        }

        return $roles;
    }
}
?>