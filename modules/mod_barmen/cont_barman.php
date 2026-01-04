<?php
include_once 'modele_barman.php';
include_once 'vue_barman.php';

class cont_barman {
    private $modele;
    private $vue;
    private $action;

    public function __construct() {
        $this->modele = new modele_barman();
        $this->vue = new vue_barman();
        $this->action = isset($_GET["action"]) ? $_GET["action"] : "caisse";
    }

    public function exec() {
        // Initialisation session
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (!isset($_SESSION['selected_client'])) $_SESSION['selected_client'] = null;

        switch ($this->action) {
            case "caisse":
                $this->afficher_caisse();
                break;
            case "add_to_cart":
                $this->add_to_cart();
                break;
            case "remove_from_cart":
                $this->remove_from_cart();
                break;
            case "select_client":
                $this->select_client();
                break;
            case "reset_client":
                $this->reset_client();
                break;
            case "validate_purchase":
                $this->validate_purchase();
                break;
        }
    }

    public function afficher_caisse($msgSuccess = null, $msgError = null) {
        $searchQuery = $_POST['q'] ?? ($_GET['q'] ?? '');
        $searchResults = [];

        if (!empty($searchQuery) && strlen($searchQuery) >= 2) {
            $searchResults = $this->modele->searchClient($searchQuery);
        }

        $products = $this->modele->getAllProducts();

        // Données bidon si pas de BDD pour tester l'affichage
        if (empty($products)) {
            $products = [
                ['id' => 1, 'name' => 'Coca-Cola', 'price' => 1.50],
                ['id' => 2, 'name' => 'Bière', 'price' => 2.50],
            ];
        }

        $this->vue->menu();
        $this->vue->afficher_interface($products, $searchResults, $searchQuery, $msgSuccess, $msgError);
    }

    // --- Actions ---

    public function add_to_cart() {
        if (isset($_POST['product_id'])) {
            $id = $_POST['product_id'];
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $id) {
                    $item['qty']++;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $_POST['product_id'],
                    'name' => $_POST['product_name'],
                    'price' => $_POST['product_price'],
                    'qty' => 1
                ];
            }
        }
        $this->afficher_caisse();
    }

    public function remove_from_cart() {
        if (isset($_POST['product_id'])) {
            $id = $_POST['product_id'];
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $id) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        $this->afficher_caisse();
    }

    public function select_client() {
        if (isset($_POST['client_id'])) {
            $_SESSION['selected_client'] = $this->modele->getClientById($_POST['client_id']);
        }
        $this->afficher_caisse();
    }

    public function reset_client() {
        $_SESSION['selected_client'] = null;
        $this->afficher_caisse();
    }

    public function validate_purchase() {
        $client = $_SESSION['selected_client'];
        $cart = $_SESSION['cart'];
        $msgSuccess = null;
        $msgError = null;

        if ($client && !empty($cart)) {
            $total = 0;
            foreach ($cart as $item) $total += $item['price'] * $item['qty'];

            try {
                $this->modele->processTransaction($client['id'], $total);
                $_SESSION['cart'] = [];
                $_SESSION['selected_client'] = null;
                $msgSuccess = "Achat validé !";
            } catch (Exception $e) {
                $msgError = $e->getMessage();
            }
        } else {
            $msgError = "Panier vide ou client manquant.";
        }
        $this->afficher_caisse($msgSuccess, $msgError);
    }
}
?>