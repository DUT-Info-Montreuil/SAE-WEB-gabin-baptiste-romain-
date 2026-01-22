<?php
require_once __DIR__ . "/cont_staff.php";

class mod_staff {
    public function exec() {
        $cont = new cont_staff();
        $cont->exec();
    }
}
?>