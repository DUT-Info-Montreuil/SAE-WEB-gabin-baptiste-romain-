<?php
require_once __DIR__ . "/../Connection.php";

class modele_product extends Connection {

    public function __construct() {
        parent::__construct();
    }

    public function getProduct($id) {
        $stmt = self::$db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>