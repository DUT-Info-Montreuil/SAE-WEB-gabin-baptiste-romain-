<?php
require_once __DIR__ . "/cont_profile.php";

class mod_profile {
    public function exec() {
        $controller = new cont_profile();
        $controller->exec();
    }
}
?>
