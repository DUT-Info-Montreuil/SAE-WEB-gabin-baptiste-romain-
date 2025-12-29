<?php
require_once "modele_product.php";
require_once "vue_product.php";

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

        $product = $this->model->getProduct($id);
        $this->view->displayProduct($product);
    }
}
?>