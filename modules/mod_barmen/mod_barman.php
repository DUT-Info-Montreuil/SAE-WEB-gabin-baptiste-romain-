<?php
require_once __DIR__ . '/cont_barman.php';

class mod_barman {
    public function exec() {
        $controller = new cont_barman();
        $controller->exec();
    }
}
?>
