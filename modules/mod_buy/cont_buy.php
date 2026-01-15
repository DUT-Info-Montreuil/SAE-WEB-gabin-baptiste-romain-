<?php
require_once __DIR__ . '/modele_buy.php';
require_once __DIR__ . '/vue_buy.php';

class cont_buy {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new modele_buy();
        $this->view = new vue_buy();
    }

    public function exec() {
        $action = $_GET['action'] ?? 'display';

        switch($action) {
            case 'add':
                $this->addToCart();
                break;
            case 'remove_one':
                $this->removeOneFromCart();
                break;
            case 'remove':
                $this->removeFromCart();
                break;
            case 'confirm':
                $this->confirmPurchase();
                break;
            case 'display':
            default:
                $this->displayCart();
                break;
        }
    }

    private function displayCart($message = null, $success = false) {
        $cart = $_SESSION['cart'] ?? [];
        if (isset($_SESSION['buy_msg'])) {
            $message = $_SESSION['buy_msg'];
            $success = $_SESSION['buy_success'];
            unset($_SESSION['buy_msg'], $_SESSION['buy_success']);
        }
        $this->view->displayCart($cart, $message, $success);
    }

    private function addToCart() {
        $productId = $_GET['id_product'] ?? null;
        if($productId) {
            $product = $this->model->getProductInfo($productId);
            if($product) {
                if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
                
                if(isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity']++;
                } else {
                    $_SESSION['cart'][$productId] = [
                        'id' => $product['id'],
                        'name' => $product['nom'],
                        'price' => $product['prix_vente'],
                        'quantity' => 1,
                        'id_buvette' => $product['id_buvette']
                    ];
                }
                
                if(isset($_GET['ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
                    if (ob_get_length()) ob_clean();
                    
                    header('Content-Type: application/json');
                    
                    require_once __DIR__ . '/../mod_product/vue_product.php';
                    $pv = new vue_product();
                    $qty = $_SESSION['cart'][$productId]['quantity'];

                    ob_start();
                    $pv->renderCardControls($productId, $qty);
                    $htmlCard = ob_get_clean();

                    ob_start();
                    $pv->renderDetailControls($productId, $qty);
                    $htmlDetail = ob_get_clean();

                    ob_start();
                    $pv->renderCartControls($productId, $qty);
                    $htmlCart = ob_get_clean();

                    echo json_encode([
                        'success' => true, 
                        'qty' => $qty, 
                        'totalCount' => $this->getCartCount(),
                        'totalPrice' => number_format($this->getTotalPrice(), 2),
                        'itemPrice' => number_format($qty * $_SESSION['cart'][$productId]['price'], 2),
                        'htmlCard' => $htmlCard,
                        'htmlDetail' => $htmlDetail,
                        'htmlCart' => $htmlCart
                    ]);
                    exit;
                }

                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
                exit;
            }
        }
    }

    private function removeOneFromCart() {
        $productId = $_GET['id_product'] ?? null;
        $qty = 0;
        if($productId && isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']--;
            $qty = $_SESSION['cart'][$productId]['quantity'];
            if($_SESSION['cart'][$productId]['quantity'] <= 0) {
                unset($_SESSION['cart'][$productId]);
                $qty = 0;
            }
        }

        if(isset($_GET['ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json');
            
            require_once __DIR__ . '/../mod_product/vue_product.php';
            $pv = new vue_product();

            ob_start();
            $pv->renderCardControls($productId, $qty);
            $htmlCard = ob_get_clean();

            ob_start();
            $pv->renderDetailControls($productId, $qty);
            $htmlDetail = ob_get_clean();

            ob_start();
            $pv->renderCartControls($productId, $qty);
            $htmlCart = ob_get_clean();

            $itemPrice = 0;
            if(isset($_SESSION['cart'][$productId])) {
                $itemPrice = $_SESSION['cart'][$productId]['quantity'] * $_SESSION['cart'][$productId]['price'];
            }

            echo json_encode([
                'success' => true, 
                'qty' => $qty, 
                'totalCount' => $this->getCartCount(),
                'totalPrice' => number_format($this->getTotalPrice(), 2),
                'itemPrice' => number_format($itemPrice, 2),
                'htmlCard' => $htmlCard,
                'htmlDetail' => $htmlDetail,
                'htmlCart' => $htmlCart
            ]);
            exit;
        }

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }

    private function getCartCount() {
        $count = 0;
        if(isset($_SESSION['cart'])) {
            foreach($_SESSION['cart'] as $item) $count += $item['quantity'];
        }
        return $count;
    }

    private function getTotalPrice() {
        $total = 0;
        if(isset($_SESSION['cart'])) {
            foreach($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        return $total;
    }

    private function removeFromCart() {
        $productId = $_GET['id_product'] ?? null;
        if($productId && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        header("Location: index.php?page=buy");
    }

    private function confirmPurchase() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=auth&action=login_form");
            exit;
        }

        $cart = $_SESSION['cart'] ?? [];
        if(empty($cart)) {
            $this->displayCart("Votre panier est vide.");
            return;
        }

        try {
            $this->model->processPurchase($_SESSION['user_id'], $cart);
            $_SESSION['cart'] = [];
            $_SESSION['buy_msg'] = "Achat réussi !";
            $_SESSION['buy_success'] = true;
            header("Location: index.php?page=buy");
            exit;
        } catch (Exception $e) {
            $this->displayCart($e->getMessage());
        }
    }
}
?>
