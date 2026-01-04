<?php
require_once __DIR__ . "/modele_auth.php";
require_once __DIR__ . "/vue_auth.php";

class cont_auth {
    private $model;
    private $view;
    private $action;

    public function __construct() {
        $this->model = new modele_auth();
        $this->view = new vue_auth();
        $this->action = isset($_GET['action']) ? $_GET['action'] : 'login_form';
    }

    public function exec() {
        switch($this->action) {
            case "login_form":
                $this->view->form_login();
                break;
            case "login":
                $this->login();
                break;
            case "register_form":
                $this->view->form_register();
                break;
            case "register":
                $this->register();
                break;
            case "logout":
                session_destroy();
                header("Location: index.php");
                exit;
                break;
        }
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
             $this->view->form_login("Veuillez remplir tous les champs.");
             return;
        }

        $user = $this->model->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: index.php");
            exit;
        } else {
            $this->view->form_login("Email ou mot de passe incorrect.");
        }
    }

    public function register() {
        $email = $_POST['email'] ?? '';
        $pwd = $_POST['password'] ?? '';
        $conf = $_POST['confirm_password'] ?? '';

        if ($pwd !== $conf) {
            $this->view->form_register("Les mots de passe ne correspondent pas.");
            return;
        }
        if ($this->model->userExists($email)) {
            $this->view->form_register("Cet email est déjà utilisé.");
            return;
        }

        if ($this->model->createUser($email, $pwd)) {
            $this->view->form_login("Compte créé ! Veuillez vous connecter.");
        } else {
            $this->view->form_register("Erreur technique.");
        }
    }
}
?>