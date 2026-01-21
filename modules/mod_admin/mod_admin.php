<?php
require_once __DIR__ . "/cont_admin.php";

class mod_admin {
    public function exec() {
        $cont = new cont_admin();
        $cont->exec();
    }
}
?>