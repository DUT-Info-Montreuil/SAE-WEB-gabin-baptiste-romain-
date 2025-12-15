<?php
require_once 'Database.php';

class Organization {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM organizations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
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

    public function ajouter($donnees) {
        // Cette méthode semble incorrecte et concerne les produits, pas les organisations.
        // Elle utilise également une méthode de connexion différente.
        // Il est recommandé de la corriger ou de la déplacer dans un modèle Product.
        $query = Connexion::$connect->prepare(
            "INSERT INTO products (name, price, description, quantity, image) 
             VALUES (:name, :price, :description, :quantity, :image)"
        );

        return $query->execute([
            ':name' => $donnees['name'],
            ':price' => $donnees['price'],
            ':description' => $donnees['description'],
            ':quantity' => $donnees['quantity'],
            ':image' => $donnees['image']
        ]);
    }
}
