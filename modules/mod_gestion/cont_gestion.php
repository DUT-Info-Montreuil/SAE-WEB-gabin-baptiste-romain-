<?php
require_once __DIR__ . "/modele_gestion.php";
require_once __DIR__ . "/vue_gestion.php";

class cont_gestion {
    private $model;
    private $view;
    private $orgaId;

    public function __construct() {
        $this->model = new modele_gestion();
        $this->view = new vue_gestion();
        $this->orgaId = $_GET['id'] ?? null;
    }

    public function exec() {
        if (!isset($_SESSION['user_id']) || !$this->orgaId) {
            header("Location: index.php");
            exit;
        }

        if (!$this->model->checkPermission($_SESSION['user_id'], $this->orgaId)) {
            die("Accès refusé : Vous n'avez pas les droits de gestion pour cette buvette.");
        }

        $action = $_GET['action'] ?? 'display';
        switch ($action) {
            case 'display':
                $this->display();
                break;
            case 'display_edit':
                $this->displayEdit();
                break;
            case 'add_product':
                $this->addProduct();
                break;
            case 'update_product':
                $this->updateProduct();
                break;
            case 'add_stock':
                $this->addStock();
                break;
            case 'add_barman_existing':
                $this->addBarmanExisting();
                break;
            case 'create_barman':
                $this->createBarman();
                break;
        }
    }

    private function display($message = null) {
        $orga = $this->model->getOrgaInfo($this->orgaId);
        $products = $this->model->getProductsByOrga($this->orgaId);
        $history = $this->model->getStockHistory($this->orgaId);
        $transactions = $this->model->getBuvetteTransactions($this->orgaId);
        $this->view->displayGestion($orga, $products, $history, $transactions, $message);
    }

    private function displayEdit() {
        $productId = $_POST['id_produit'] ?? null;
        if ($productId) {
            $orga = $this->model->getOrgaInfo($this->orgaId);
            $product = $this->model->getProductById($productId);
            $this->view->displayEditForm($orga, $product);
        } else {
            $this->display("Produit introuvable.");
        }
    }

    private function addStock() {
        $productId = $_POST['product_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 0;
        $supplier = $_POST['supplier'] ?? '';
        $cost = $_POST['cost'] ?? 0;

        if ($productId && $quantity > 0 && !empty($supplier)) {
            try {
                if ($this->model->addStockEntry($productId, $quantity, $supplier, $cost, $this->orgaId)) {
                    $this->display("Stock mis à jour et trésorerie déduite avec succès !");
                } else {
                    $this->display("Erreur lors de la mise à jour du stock.");
                }
            } catch (Exception $e) {
                $this->display($e->getMessage());
            }
        } else {
            $this->display("Tous les champs sont obligatoires pour l'entrée de stock.");
        }
    }

    private function addBarmanExisting() {
        $email = $_POST['email'] ?? '';
        $user = $this->model->getUserByEmail($email);
        
        if ($user) {
            if ($this->model->assignBarmanRole($user['id'], $this->orgaId)) {
                $this->display("L'utilisateur a été promu Barman !");
            } else {
                $this->display("Erreur lors de l'assignation du rôle.");
            }
        } else {
            $this->display("Aucun utilisateur trouvé avec cet email.");
        }
    }

    private function createBarman() {
        $email = $_POST['email'] ?? '';
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->model->getUserByEmail($email)) {
            $this->display("Cet email est déjà utilisé par un autre compte.");
            return;
        }

        $newUserId = $this->model->createQuickUser($email, $nom, $prenom, $password);
        if ($newUserId) {
            $this->model->assignBarmanRole($newUserId, $this->orgaId);
            $this->display("Compte créé et Barman assigné !");
        } else {
            $this->display("Erreur lors de la création du compte.");
        }
    }

    private function addProduct() {
        $nom = $_POST['nom'] ?? '';
        $desc = $_POST['description'] ?? '';
        $cat = $_POST['categorie'] ?? 'Divers';
        $prix = $_POST['prix'] ?? 0;
        $stock = $_POST['stock'] ?? 0;

        if ($this->model->addProduct($nom, $desc, $cat, $prix, $stock, $this->orgaId)) {
            $this->display("Produit ajouté avec succès !");
        } else {
            $this->display("Erreur lors de l'ajout du produit.");
        }
    }

    private function updateProduct() {
        $id = $_POST['id_produit'] ?? null;
        $nom = $_POST['nom'] ?? '';
        $desc = $_POST['description'] ?? '';
        $cat = $_POST['categorie'] ?? 'Divers';
        $prix = $_POST['prix'] ?? 0;

        if ($id && $this->model->updateProduct($id, $nom, $desc, $cat, $prix)) {
            $this->display("Produit mis à jour !");
        } else {
            $this->display("Erreur lors de la mise à jour.");
        }
    }
}
?>
