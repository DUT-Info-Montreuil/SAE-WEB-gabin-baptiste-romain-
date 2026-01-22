<?php
require_once __DIR__ . "/modele_product.php";
require_once __DIR__ . "/vue_product.php";

class cont_product {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new modele_product();
        $this->view = new vue_product();
    }

    public function exec() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php");
            exit;
        }

        $product = $this->model->getProductById($id);
        
        $isMember = false;
        if ($product && isset($_SESSION['user_id'])) {
            $isMember = $this->model->isMember($_SESSION['user_id'], $product['id_buvette']);
        }

        $this->view->displayProduct($product, $isMember);
    }
}
?>