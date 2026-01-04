<?php
require_once __DIR__ . "/../Connection.php";

class modele_home extends Connection {
    
    public function __construct() {
        parent::__construct();
    }

    public function getAllOrgas($search = '') {
        $sql = "SELECT id, nom AS name, adresse AS address FROM Buvette";
        $params = [];
        if (!empty($search)) {
            $sql .= " WHERE nom LIKE ? OR adresse LIKE ?";
            $params = ["%$search%", "%$search%"];
        }
        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getUserBalance($id) {
        // Note: Dans script.sql, le solde est dans etre_membre. 
        // On prend le premier solde trouvé pour cet utilisateur par défaut.
        $stmt = self::$db->prepare("SELECT solde FROM etre_membre WHERE id_utilisateur = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() ?: 0;
    }
}
?>