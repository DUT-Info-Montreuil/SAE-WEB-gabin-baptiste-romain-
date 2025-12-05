<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        $error = '';
        
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = "Veuillez remplir tous les champs.";
            } else {
                $user = $this->userModel->findByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Email ou mot de passe incorrect.";
                }
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Adresse email invalide.";
            } elseif (empty($password) || strlen($password) < 6) {
                $error = "Le mot de passe doit contenir au moins 6 caractères.";
            } elseif ($password !== $confirm_password) {
                $error = "Les mots de passe ne correspondent pas.";
            } else {
                if ($this->userModel->exists($email)) {
                    $error = "Cet email est déjà utilisé.";
                } else {
                    if ($this->userModel->create($email, $password)) {
                        $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                        header("Refresh: 2; url=index.php?page=login");
                    } else {
                        $error = "Une erreur est survenue lors de l'inscription.";
                    }
                }
            }
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?page=login");
        exit;
    }
}
