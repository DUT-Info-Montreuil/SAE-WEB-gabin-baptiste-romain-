<?php
require_once "modele_solde.php";
require_once "vue_solde.php";

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
        // Sécurité : l'utilisateur doit être connecté pour voir son solde
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=auth&action=login_form");
            exit;
        }

        switch($this->action) {
            case 'recharge':
                $this->recharge();
                break;
            case 'show':
            default:
                $this->showBalance();
                break;
        }
    }

    private function showBalance($message = null) {
        $balance = $this->model->getBalance($_SESSION['user_id']);
        $this->view->displayBalance($balance, $message);
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

        $this->showBalance($message);
    }
}
