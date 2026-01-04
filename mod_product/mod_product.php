<?php
require_once __DIR__ . "/cont_product.php";

class mod_product {
    public function exec() {
        $controller = new cont_product();
        $controller->exec();
    }
}
?>