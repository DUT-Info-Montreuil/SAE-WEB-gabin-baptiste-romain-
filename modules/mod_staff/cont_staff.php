<?php
require_once __DIR__ . "/modele_staff.php";
require_once __DIR__ . "/vue_staff.php";

class cont_staff {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new modele_staff();
        $this->view = new vue_staff();
    }

    public function exec() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=auth&action=login_form");
            exit;
        }

        $roles = $this->model->getStaffRoles($_SESSION['user_id']);
        $this->view->displayStaffList($roles);
    }
}
?>