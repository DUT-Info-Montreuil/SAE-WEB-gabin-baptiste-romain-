<?php
require_once __DIR__ . "/../../Connection.php";

class modele_gestion extends Connection {
    public function __construct() {
        parent::__construct();
    }

    public function getOrgaInfo($orgaId) {
        $stmt = self::$db->prepare("SELECT id, nom, solde FROM Buvette WHERE id = ?");
        $stmt->execute([$orgaId]);
        $orga = $stmt->fetch();
        
        $stmt = self::$db->prepare("SELECT SUM(montant_total) FROM Commande WHERE id_buvette = ?");
        $stmt->execute([$orgaId]);
        $orga['chiffre_affaire'] = $stmt->fetchColumn() ?: 0;
        
        return $orga;
    }

    public function checkPermission($userId, $orgaId) {
        $stmt = self::$db->prepare("SELECT role FROM etre_membre WHERE id_utilisateur = ? AND id_buvette = ?");
        $stmt->execute([$userId, $orgaId]);
        return $stmt->fetchColumn() === 'ROLE_GESTION';
    }

    public function addProduct($name, $desc, $categorie, $price, $stock, $orgaId) {
        $stmt = self::$db->prepare("INSERT INTO Produit (nom, description, categorie, prix_vente, stock_actuel, id_buvette) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $desc, $categorie, $price, $stock, $orgaId]);
    }

    public function updateProduct($id, $name, $desc, $categorie, $price) {
        $stmt = self::$db->prepare("UPDATE Produit SET nom = ?, description = ?, categorie = ?, prix_vente = ? WHERE id = ?");
        return $stmt->execute([$name, $desc, $categorie, $price, $id]);
    }

    public function getProductsByOrga($orgaId) {
        $stmt = self::$db->prepare("SELECT id, nom, prix_vente, description, categorie, stock_actuel AS stock FROM Produit WHERE id_buvette = ?");
        $stmt->execute([$orgaId]);
        return $stmt->fetchAll();
    }

    public function getProductById($productId) {
        $stmt = self::$db->prepare("SELECT * FROM Produit WHERE id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }

    public function addStockEntry($productId, $quantity, $supplier, $cost, $orgaId) {
        try {
            self::$db->beginTransaction();

            $stmt = self::$db->prepare("SELECT solde FROM Buvette WHERE id = ? FOR UPDATE");
            $stmt->execute([$orgaId]);
            $currentBalance = $stmt->fetchColumn();

            if ($currentBalance < $cost) {
                throw new Exception("Trésorerie insuffisante pour cet approvisionnement (" . number_format($cost, 2) . " € requis).");
            }

            $stmt = self::$db->prepare("INSERT INTO Entree_Stock (nom_fournisseur, id_buvette) VALUES (?, ?)");
            $stmt->execute([$supplier, $orgaId]);
            $entryId = self::$db->lastInsertId();

            $stmt = self::$db->prepare("INSERT INTO ligne_ent (id_entree, id_produit, quantite, prix) VALUES (?, ?, ?, ?)");
            $stmt->execute([$entryId, $productId, $quantity, $cost]);

            $stmt = self::$db->prepare("UPDATE Produit SET stock_actuel = stock_actuel + ? WHERE id = ?");
            $stmt->execute([$quantity, $productId]);

            $stmt = self::$db->prepare("UPDATE Buvette SET solde = solde - ? WHERE id = ?");
            $stmt->execute([$cost, $orgaId]);

            self::$db->commit();
            return true;
        } catch (Exception $e) {
            self::$db->rollBack();
            throw $e;
        }
    }

    public function getStockHistory($orgaId) {
        $sql = "SELECT es.id, es.nom_fournisseur AS supplier, p.nom AS product_name, p.stock_actuel AS current_stock, le.quantite AS quantity, le.prix AS cost
                FROM Entree_Stock es
                JOIN ligne_ent le ON es.id = le.id_entree
                JOIN Produit p ON le.id_produit = p.id
                WHERE es.id_buvette = ?
                ORDER BY es.id DESC LIMIT 20";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$orgaId]);
        return $stmt->fetchAll();
    }

    public function getBuvetteTransactions($orgaId) {
        $sql = "SELECT c.id, c.date_heure, c.montant_total, u.nom as client_nom, u.prenom as client_prenom, u.email as client_email
                FROM Commande c
                JOIN Utilisateur u ON c.id_client = u.id
                WHERE c.id_buvette = ?
                ORDER BY c.date_heure DESC LIMIT 50";
        $stmt = self::$db->prepare($sql);
        $stmt->execute([$orgaId]);
        $orders = $stmt->fetchAll();

        foreach ($orders as &$order) {
            $stmt = self::$db->prepare("SELECT p.nom, co.quantite FROM composer co JOIN Produit p ON co.id_produit = p.id WHERE co.id_commande = ?");
            $stmt->execute([$order['id']]);
            $order['items'] = $stmt->fetchAll();
        }
        return $orders;
    }

    public function getUserByEmail($email) {
        $stmt = self::$db->prepare("SELECT id FROM Utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function createQuickUser($email, $nom, $prenom, $password) {
        $stmt = self::$db->prepare("INSERT INTO Utilisateur (email, nom, prenom, password) VALUES (?, ?, ?, ?)");
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$email, $nom, $prenom, $hashedPwd]);
        return self::$db->lastInsertId();
    }

    public function assignBarmanRole($userId, $orgaId) {
        $stmt = self::$db->prepare("INSERT INTO etre_membre (id_utilisateur, id_buvette, role) VALUES (?, ?, ?) 
                                   ON DUPLICATE KEY UPDATE role = ?");
        return $stmt->execute([$userId, $orgaId, 'ROLE_BARMAN', 'ROLE_BARMAN']);
    }
}
?>