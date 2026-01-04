<?php
require_once __DIR__ . "/../Connection.php";

class modele_home extends Connection {
    
    public function __construct() {
        parent::__construct();
    }

    public function getAllOrgas($search = '') {
        $sql = "SELECT * FROM organizations";
        $params = [];
        if (!empty($search)) {
            $sql .= " WHERE name LIKE ? OR address LIKE ?";
            $params = ["%$search%", "%$search%"];
        }
        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getUserBalance($id) {
        $stmt = self::$db->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
}
?>