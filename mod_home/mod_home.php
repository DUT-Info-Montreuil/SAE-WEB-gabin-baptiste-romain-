<?php
require_once "cont_home.php";

class mod_home {
    public function exec() {
        $controller = new cont_home();
        $controller->exec();
    }
}
?>