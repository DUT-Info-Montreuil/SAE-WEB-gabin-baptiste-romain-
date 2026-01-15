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
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=auth&action=login_form");
            exit;
        }

        if (!isset($_SESSION['cart_barman'])) $_SESSION['cart_barman'] = [];
        if (!isset($_SESSION['selected_client'])) $_SESSION['selected_client'] = null;

        switch ($this->action) {
            case "caisse":
                $this->afficher_caisse();
                break;
            case "add_to_cart":
                $this->add_to_cart();
                break;
            case "update_qty":
                $this->update_qty();
                break;
            case "remove_all":
                $this->remove_all();
                break;
            case "select_client":
                $this->select_client();
                break;
            case "reset_client":
                $this->reset_client();
                break;
            case "prepare_order":
                $this->prepare_order();
                break;
            case "validate_purchase":
                $this->validate_purchase();
                break;
        }
    }

    public function afficher_caisse($msgSuccess = null, $msgError = null) {
        $searchQuery = $_GET['q'] ?? '';
        $searchResults = [];
        $buvetteId = $_GET['id'] ?? '';

        if (!empty($searchQuery)) {
            $cleanEmail = explode('#', $searchQuery)[0];
            $exactUser = $this->modele->getClientByEmail($cleanEmail);
            if ($exactUser) {
                $_SESSION['selected_client'] = $exactUser;
                header("Location: index.php?page=barman&id=" . $buvetteId);
                exit;
            }
            if (strlen($searchQuery) >= 2) {
                $searchResults = $this->modele->searchClient($searchQuery);
            }
        }
        $products = $this->modele->getAllProducts();
        $pendingOrders = $this->modele->getPendingOrders($buvetteId);
        $this->vue->afficher_interface($products, $searchResults, $searchQuery, $pendingOrders, $msgSuccess, $msgError);
    }

    public function add_to_cart() {
        if (isset($_POST['product_id'])) {
            $id = $_POST['product_id'];
            if(isset($_SESSION['cart_barman'][$id])) {
                $_SESSION['cart_barman'][$id]['qty']++;
            } else {
                $_SESSION['cart_barman'][$id] = [
                    'id' => $_POST['product_id'],
                    'name' => $_POST['product_name'],
                    'price' => $_POST['product_price'],
                    'qty' => 1
                ];
            }
        }
        $this->render_ajax_response();
    }

    public function update_qty() {
        $id = $_POST['product_id'] ?? null;
        $op = $_POST['op'] ?? '';
        if ($id && isset($_SESSION['cart_barman'][$id])) {
            if ($op === 'plus') $_SESSION['cart_barman'][$id]['qty']++;
            elseif ($op === 'minus') {
                $_SESSION['cart_barman'][$id]['qty']--;
                if ($_SESSION['cart_barman'][$id]['qty'] <= 0) unset($_SESSION['cart_barman'][$id]);
            }
        }
        $this->render_ajax_response();
    }

    public function remove_all() {
        $id = $_POST['product_id'] ?? null;
        if ($id && isset($_SESSION['cart_barman'][$id])) unset($_SESSION['cart_barman'][$id]);
        $this->render_ajax_response();
    }

    private function render_ajax_response() {
        if(isset($_GET['ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json');
            $cart = $_SESSION['cart_barman'] ?? [];
            $total = 0;
            foreach($cart as $item) $total += $item['price'] * $item['qty'];
            ob_start();
            $this->vue->render_cart_content($cart, $total, $_GET['id'] ?? '');
            $html = ob_get_clean();
            echo json_encode(['success' => true, 'html' => $html]);
            exit;
        }
        $this->afficher_caisse();
    }

    public function select_client() {
        if (isset($_POST['client_id'])) $_SESSION['selected_client'] = $this->modele->getClientById($_POST['client_id']);
        $this->afficher_caisse();
    }

    public function reset_client() {
        $_SESSION['selected_client'] = null;
        $this->afficher_caisse();
    }

    public function prepare_order() {
        $orderId = $_GET['order_id'] ?? null;
        $buvetteId = $_GET['id'] ?? '';
        if ($orderId) {
            $this->modele->assignBarmanToOrder($orderId, $_SESSION['user_id']);
        }
        header("Location: index.php?page=barman&id=" . $buvetteId);
        exit;
    }

    public function validate_purchase() {
        $client = $_SESSION['selected_client'];
        $cart = $_SESSION['cart_barman'];
        $buvetteId = $_GET['id'] ?? null;
        $barmanId = $_SESSION['user_id'];
        $msgSuccess = null; $msgError = null;
        if ($client && !empty($cart) && $buvetteId) {
            try {
                $this->modele->processTransaction($client['id'], $barmanId, $buvetteId, $cart);
                $_SESSION['cart_barman'] = [];
                $_SESSION['selected_client'] = null;
                $msgSuccess = "Vente enregistrée avec succès !";
            } catch (Exception $e) { $msgError = $e->getMessage(); }
        } else { $msgError = "Sélectionnez un client et des produits."; }
        $this->afficher_caisse($msgSuccess, $msgError);
    }
}
?>
