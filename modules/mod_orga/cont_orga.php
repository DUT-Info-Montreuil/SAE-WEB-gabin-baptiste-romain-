<?php
require_once __DIR__ . "/modele_orga.php";
require_once __DIR__ . "/vue_orga.php";

class cont_orga {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new modele_orga();
        $this->view = new vue_orga();
    }

    public function exec() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php");
            exit;
        }

        $action = $_GET['action'] ?? null;
        if ($action === 'join' && isset($_SESSION['user_id'])) {
            $this->model->joinOrga($_SESSION['user_id'], $id);
            header("Location: index.php?page=orga&id=" . $id);
            exit;
        }

        $orga = $this->model->getOrgaById($id);
        $products = $this->model->getProductsByOrga($id);
        
        $isMember = false;
        $userRole = null;
        if (isset($_SESSION['user_id'])) {
            $isMember = $this->model->isMember($_SESSION['user_id'], $id);
            if ($isMember) {
                $userRole = $this->model->getUserRoleInOrga($_SESSION['user_id'], $id);
            }
        }

        $this->view->displayOrga($orga, $products, $isMember, $userRole);
    }
}
?>