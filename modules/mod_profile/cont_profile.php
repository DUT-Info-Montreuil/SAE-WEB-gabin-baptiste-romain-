<?php
require_once __DIR__ . "/modele_profile.php";
require_once __DIR__ . "/vue_profile.php";

class cont_profile {
    private $model;
    private $view;
    private $action;

    public function __construct() {
        $this->model = new modele_profile();
        $this->view = new vue_profile();
        $this->action = $_GET['action'] ?? 'display';
    }

    public function exec() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=auth&action=login_form");
            exit;
        }

        switch ($this->action) {
            case 'display':
                $this->display();
                break;
            case 'update':
                $this->update();
                break;
        }
    }

    private function display($message = null) {
        $user = $this->model->getUserById($_SESSION['user_id']);
        $roles = $this->model->getUserRoles($_SESSION['user_id']);
        $this->view->form_profile($user, $roles, $message);
    }

    private function update() {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';

        if (!empty($nom) && !empty($prenom)) {
            if ($this->model->updateProfile($_SESSION['user_id'], $nom, $prenom)) {
                $this->display("Profil mis à jour avec succès !");
            } else {
                $this->display("Erreur lors de la mise à jour.");
            }
        } else {
            $this->display("Tous les champs sont obligatoires.");
        }
    }
}
?>
