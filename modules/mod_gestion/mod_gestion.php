<?php
require_once __DIR__ . "/cont_gestion.php";

class mod_gestion {
    public function exec() {
        $controller = new cont_gestion();
        $controller->exec();
    }
}
?>
