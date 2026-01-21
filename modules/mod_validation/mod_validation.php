<?php
require_once __DIR__ . '/cont_validation.php';

class mod_validation {
    public function exec() {
        $cont = new cont_validation();
        $cont->exec();
    }
}
?>