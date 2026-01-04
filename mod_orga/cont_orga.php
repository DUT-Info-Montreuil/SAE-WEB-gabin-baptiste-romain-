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

        $orga = $this->model->getOrga($id);
        $products = $this->model->getProducts($id);

        $this->view->displayOrga($orga, $products);
    }
}
?>