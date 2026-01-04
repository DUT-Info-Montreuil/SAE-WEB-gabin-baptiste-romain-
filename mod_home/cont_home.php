<?php
require_once "modele_home.php";
require_once "vue_home.php";

class cont_home {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new modele_home();
        $this->view = new vue_home();
    }

    public function exec() {
        $search = $_GET['search'] ?? '';
        $orgas = $this->model->getAllOrgas($search);

        $userBalance = 0;
        if(isset($_SESSION['user_id'])) {
            $userBalance = $this->model->getUserBalance($_SESSION['user_id']);
        }

        $this->view->displayHome($orgas, $search, $userBalance);
    }
}
?>