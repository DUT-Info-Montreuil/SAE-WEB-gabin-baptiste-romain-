<?php
require_once 'models/Product.php';

class ProductController {
    public function detail() {
        if (!isset($_GET['id'])) {
            echo "Produit non spécifié.";
            return;
        }

        $id = $_GET['id'];
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            echo "Produit introuvable.";
            return;
        }

        require 'views/product/detail.php';
    }
}
