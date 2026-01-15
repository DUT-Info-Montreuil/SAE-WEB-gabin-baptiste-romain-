<?php
require_once __DIR__ . "/modele_solde.php";
require_once __DIR__ . "/vue_solde.php";

class cont_solde{
    private $model;
    private $view;
    private $action;

    public function __construct(){
        $this->model = new modele_solde();
        $this->view = new vue_solde();
        $this->action = isset($_GET['action']) ? $_GET['action'] : 'show';
    }

    public function exec(){
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=auth&action=login_form");
            exit;
        }

        switch($this->action) {
            case 'add':
                $this->recharge();
                break;
            case 'show':
            default:
                $this->display();
                break;
        }
    }

    private function display($message = null) {
        $balance = $this->model->getBalance($_SESSION['user_id']);
        $history = $this->model->getOrderHistory($_SESSION['user_id']);
        $this->view->displaySolde($balance, $history, $message);
    }

    private function recharge() {
        $amount = $_POST['amount'] ?? 0;
        $message = '';

        if ($amount > 0) {
            if ($this->model->addMoney($_SESSION['user_id'], $amount)) {
                $message = "Votre compte a été rechargé de " . htmlspecialchars($amount) . " €.";
            } else {
                $message = "Une erreur est survenue lors du rechargement.";
            }
        } else {
            $message = "Veuillez entrer un montant valide.";
        }

        $this->display($message);
    }
}
