<?php
require_once __DIR__ . "/cont_auth.php";

class mod_auth {
    public function exec() {
        $controller = new cont_auth();
        $controller->exec();
    }
}
?>