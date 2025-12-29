<?php
include_once "cont_barman.php";

class mod_barman {

    public function exec(){
        $cont = new cont_barman();
        return $cont->exec();
    }
}
?>