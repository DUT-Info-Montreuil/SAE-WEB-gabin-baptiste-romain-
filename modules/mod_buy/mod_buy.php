<?php
require_once __DIR__ . '/cont_buy.php';

class mod_buy {
    public function exec() {
        $controller = new cont_buy();
        $controller->exec();
    }
}
?>
