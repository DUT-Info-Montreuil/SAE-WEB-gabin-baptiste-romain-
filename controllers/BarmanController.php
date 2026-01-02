<?php
require_once 'models/BarmanModel.php';

class BarmanController {
    private $barmanModel;

    public function __construct() {
        $this->barmanModel = new BarmanModel();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        // Nouvelle action pour l'affichage de la liste des stocks
        $view = $_GET['view'] ?? 'caisse';
        
        if ($view === 'stocks') {
            $products = $this->barmanModel->getAllProducts();
            require_once __DIR__ . '/../views/StockVue.php';
            exit;
        }

        // Initialisation du panier s'il n'existe pas
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Initialisation du client sélectionné
        if (!isset($_SESSION['selected_client'])) {
            $_SESSION['selected_client'] = null;
        }

        // Ajout panier, Recherche client, Validation, Reset
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'add_to_cart') {
                $id = $_POST['product_id'];
                $name = $_POST['product_name'];
                $price = (float)$_POST['product_price'];

                // Vérif si déjà dans le panier
                $found = false;
                foreach ($_SESSION['cart'] as &$item) {
                    if ($item['id'] == $id) {
                        $item['qty']++;
                        $found = true;
                        break;
                    }
                }
                unset($item);

                if (!$found) {
                    $_SESSION['cart'][] = ['id' => $id, 'name' => $name, 'price' => $price, 'qty' => 1];
                }
            } elseif ($action === 'remove_one') {
                $id = $_POST['product_id'];
                foreach ($_SESSION['cart'] as $key => $item) {
                    if ($item['id'] == $id) {
                        $_SESSION['cart'][$key]['qty']--;

                        // Si la quantité tombe à 0, on supprime l'article
                        if ($_SESSION['cart'][$key]['qty'] <= 0) {
                            unset($_SESSION['cart'][$key]);
                        }
                        break;
                    }
                }
                $_SESSION['cart'] = array_values($_SESSION['cart']);

            }elseif ($action === 'select_client') {
                $clientId = $_POST['client_id'];
                $_SESSION['selected_client'] = $this->barmanModel->getClientById($clientId);
            } elseif ($action === 'reset_client') {
                $_SESSION['selected_client'] = null;
            } elseif ($action === 'validate_purchase') {
                $client = $_SESSION['selected_client'];
                $cart = $_SESSION['cart'];

                if ($client && !empty($cart)) {
                    $total = 0;
                    foreach ($cart as $item) $total += $item['price'] * $item['qty'];

                    try {
                        $this->barmanModel->processTransaction($client['id'], $total, $cart);
                        $_SESSION['cart'] = []; // Vider panier
                        $_SESSION['selected_client'] = null; // Deselectionner client
                        $success_message = "Achat validé avec succès !";
                    } catch (Exception $e) {
                        $error_message = $e->getMessage();
                    }
                }
            }
        }

        $searchResults = [];
        $searchQuery = $_GET['q'] ?? '';
        if (!empty($searchQuery) && strlen($searchQuery) >= 2) {
            $searchResults = $this->barmanModel->searchClient($searchQuery);
        }

        $products = $this->barmanModel->getAllProducts();

        if (empty($products)) {
            $products = [
                ['id' => 1, 'name' => 'Coca-Cola', 'price' => 1.50],
                ['id' => 2, 'name' => 'Bière Pression', 'price' => 2.50],
                ['id' => 3, 'name' => 'Sandwich Jambon', 'price' => 3.00],
                ['id' => 4, 'name' => 'Chips', 'price' => 1.00],
                ['id' => 5, 'name' => 'Eau', 'price' => 0.50],
            ];
        }

        require_once __DIR__ . '/../views/BarmanVue.php';
    }
}