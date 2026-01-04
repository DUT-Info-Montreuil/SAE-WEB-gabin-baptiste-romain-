<?php
require_once "cont_orga.php";

class mod_orga {
    public function exec() {
        $controller = new cont_orga();
        $controller->exec();
    }
}
?>