<?php
require_once __DIR__ . "/cont_home.php";

class mod_home {
    public function exec() {
        $controller = new cont_home();
        $controller->exec();
    }
}
?>