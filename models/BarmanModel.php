<?php
require_once 'Database.php';

class BarmanModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    // Récupérer tous les produits disponibles
    public function getAllProducts() {
        // On suppose une table products. 
        // Si elle n'existe pas encore, il faudra la créer (id, name, price, category, image_url)
        $sql = "SELECT * FROM products ORDER BY name ASC";
        // Pour éviter une erreur si la table n'existe pas dans votre structure actuelle, 
        // on entoure d'un try/catch ou on suppose que la table existe comme indiqué dans Organization.php
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    // Rechercher un client par nom ou email
    public function searchClient($query) {
        $sql = "SELECT id, email, balance FROM users WHERE email LIKE ? LIMIT 10";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$query%"]);
        return $stmt->fetchAll();
    }

    // Récupérer un client par ID
    public function getClientById($id) {
        $stmt = $this->pdo->prepare("SELECT id, email, balance FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Effectuer la transaction
    public function processTransaction($clientId, $totalAmount, $cartItems) {
        try {
            $this->pdo->beginTransaction();

            // 1. Vérifier le solde actuel (pour éviter les race conditions)
            $stmt = $this->pdo->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
            $stmt->execute([$clientId]);
            $currentBalance = $stmt->fetchColumn();

            if ($currentBalance === false) {
                throw new Exception("Client introuvable.");
            }

            if ($currentBalance < $totalAmount) {
                throw new Exception("Solde insuffisant (Solde: " . number_format($currentBalance, 2) . " €).");
            }

            // 2. Débiter le client
            $newBalance = $currentBalance - $totalAmount;
            $updateStmt = $this->pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
            $updateStmt->execute([$newBalance, $clientId]);

            // 3. Enregistrer la transaction (Optionnel pour l'instant, mais recommandé pour l'historique)
            // $histStmt = $this->pdo->prepare("INSERT INTO transactions (...) VALUES (...)");
            
            // 4. Mettre à jour les stocks (Optionnel selon complexité actuelle)
            // foreach ($cartItems as $item) { ... }

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e; // On relance l'exception pour l'afficher dans le contrôleur
        }
    }
}
