<?php
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/../models/Organization.php';
require_once __DIR__ . '/../models/User.php';

class HomeController {
    private $orgModel;
    private $userModel;

    public function __construct() {
        $this->orgModel = new Organization();
        $this->userModel = new User();
    }

    public function index() {
        $is_logged_in = isset($_SESSION['user_id']);
        $user_email = $_SESSION['user_email'] ?? '';
        $user_balance = 0.00;

        if ($is_logged_in) {
            $user_balance = $this->userModel->getBalance($_SESSION['user_id']);
        }
        
        $search = $_GET['search'] ?? '';
        $organizations = $this->orgModel->findAll($search);

        try {
            // Test de connexion juste pour l'affichage du statut
            Database::getConnection();
            $db_status = "Connexion à la base de données établie.";
        } catch (Exception $e) {
            $db_status = "Erreur de connexion à la BDD : " . $e->getMessage();
        }

        require_once __DIR__ . '/../views/home.php';
    }
}