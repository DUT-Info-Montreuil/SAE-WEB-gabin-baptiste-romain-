<?php
require_once __DIR__ . "/cont_solde.php";

class mod_solde {
    public function exec() {
        $controller = new cont_solde();
        $controller->exec();
    }
}
?>