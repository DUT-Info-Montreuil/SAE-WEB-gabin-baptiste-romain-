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
        $returnBuvetteId = $_GET['buvette_id'] ?? null;
        $this->view->displayCart($cart, $message, $success, $returnBuvetteId);
    }

    private function addToCart() {
        $productId = $_GET['id_product'] ?? null;
        if($productId) {
            $product = $this->model->getProductInfo($productId);
            if($product) {
                if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
                $buvetteId = $product['id_buvette'];
                
                if(!isset($_SESSION['cart'][$buvetteId])) {
                    $_SESSION['cart'][$buvetteId] = [];
                }

                if(isset($_SESSION['cart'][$buvetteId][$productId])) {
                    $_SESSION['cart'][$buvetteId][$productId]['quantity']++;
                } else {
                    $_SESSION['cart'][$buvetteId][$productId] = [
                        'id' => $product['id'],
                        'name' => $product['nom'],
                        'price' => $product['prix_vente'],
                        'quantity' => 1,
                        'id_buvette' => $product['id_buvette'],
                        'buvette_name' => $product['nom_buvette'] ?? 'Buvette'
                    ];
                }
                
                $this->handleAjax($productId, $buvetteId);

                header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
                exit;
            }
        }
    }

    private function removeOneFromCart() {
        $productId = $_GET['id_product'] ?? null;
        $qty = 0;
        
        // Find product in nested cart
        $foundBuvetteId = null;
        if ($productId && isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $buvetteId => &$items) {
                if (isset($items[$productId])) {
                    $items[$productId]['quantity']--;
                    $qty = $items[$productId]['quantity'];
                    $foundBuvetteId = $buvetteId;
                    if ($items[$productId]['quantity'] <= 0) {
                        unset($items[$productId]);
                        $qty = 0;
                        if (empty($items)) {
                            unset($_SESSION['cart'][$buvetteId]);
                        }
                    }
                    break;
                }
            }
        }

        $this->handleAjax($productId, $foundBuvetteId);

        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
        exit;
    }

    private function handleAjax($productId, $buvetteId) {
        if(isset($_GET['ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json');
            
            require_once __DIR__ . '/../mod_product/vue_product.php';
            $pv = new vue_product();
            
            $qty = 0;
            if ($buvetteId && isset($_SESSION['cart'][$buvetteId][$productId])) {
                $qty = $_SESSION['cart'][$buvetteId][$productId]['quantity'];
            }

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
            if ($buvetteId && isset($_SESSION['cart'][$buvetteId][$productId])) {
                $itemPrice = $_SESSION['cart'][$buvetteId][$productId]['quantity'] * $_SESSION['cart'][$buvetteId][$productId]['price'];
            }
            
            $buvetteCount = 0;
            if ($buvetteId && isset($_SESSION['cart'][$buvetteId])) {
                foreach($_SESSION['cart'][$buvetteId] as $item) $buvetteCount += $item['quantity'];
            }

            echo json_encode([
                'success' => true, 
                'qty' => $qty, 
                'totalCount' => $this->getCartCount(),
                'buvetteCount' => $buvetteCount,
                'buvetteId' => $buvetteId,
                'totalPrice' => number_format($this->getTotalPrice(), 2),
                'itemPrice' => number_format($itemPrice, 2),
                'htmlCard' => $htmlCard,
                'htmlDetail' => $htmlDetail,
                'htmlCart' => $htmlCart
            ]);
            exit;
        }
    }

    private function getCartCount() {
        $count = 0;
        if(isset($_SESSION['cart'])) {
            foreach($_SESSION['cart'] as $buvetteItems) {
                foreach($buvetteItems as $item) {
                    $count += $item['quantity'];
                }
            }
        }
        return $count;
    }

    private function getTotalPrice() {
        $total = 0;
        if(isset($_SESSION['cart'])) {
            foreach($_SESSION['cart'] as $buvetteItems) {
                foreach($buvetteItems as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
            }
        }
        return $total;
    }

    private function removeFromCart() {
        $productId = $_GET['id_product'] ?? null;
        if ($productId && isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $buvetteId => &$items) {
                if (isset($items[$productId])) {
                    unset($items[$productId]);
                    if (empty($items)) {
                        unset($_SESSION['cart'][$buvetteId]);
                    }
                    break;
                }
            }
        }
        header("Location: index.php?page=buy");
    }

    private function confirmPurchase() {
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=auth&action=login_form");
            exit;
        }

        $buvetteId = $_GET['buvette_id'] ?? null;
        if (!$buvetteId || !isset($_SESSION['cart'][$buvetteId])) {
             $this->displayCart("Panier introuvable pour cette buvette.");
             return;
        }

        $cart = $_SESSION['cart'][$buvetteId];
        
        try {
            $this->model->processPurchase($_SESSION['user_id'], $cart, $buvetteId);
            unset($_SESSION['cart'][$buvetteId]);
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