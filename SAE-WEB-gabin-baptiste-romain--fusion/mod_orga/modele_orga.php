<?php
require_once __DIR__ . "/../Connection.php";

class modele_orga extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getOrga($id) {
        $stmt = self::$db->prepare("SELECT * FROM organizations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getProducts($orgId) {
        $stmt = self::$db->prepare("SELECT * FROM products WHERE organization_id = ?");
        $stmt->execute([$orgId]);
        return $stmt->fetchAll();
    }
}
?>