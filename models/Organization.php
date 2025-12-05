<?php
require_once 'Database.php';

class Organization {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function findAll($search = '') {
        $sql = "SELECT * FROM organizations";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE name LIKE ? OR address LIKE ?";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getProducts($organizationId) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE organization_id = ?");
        $stmt->execute([$organizationId]);
        return $stmt->fetchAll();
    }
}
