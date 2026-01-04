<?php
require_once __DIR__ . "/modele_home.php";
require_once __DIR__ . "/vue_home.php";

class cont_home {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new modele_home();
        $this->view = new vue_home();
    }

    public function exec() {
        $action = $_GET['action'] ?? null;
        if ($action === 'toggle_favorite' && isset($_SESSION['user_id'])) {
            $this->model->toggleFavorite($_SESSION['user_id'], $_GET['id_orga']);
            
            if(isset($_GET['ajax'])) {
                if (ob_get_length()) ob_clean();
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }

            header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
            exit;
        }

        $view = $_GET['view'] ?? 'all';
        $search = $_GET['search'] ?? '';
        
        $favIds = [];
        if (isset($_SESSION['user_id'])) {
            $favIds = $this->model->getFavoriteIds($_SESSION['user_id']);
        }

        if ($view === 'favorites' && isset($_SESSION['user_id'])) {
            $orgas = $this->model->getFavorites($_SESSION['user_id']);
        } else {
            $orgas = $this->model->getAllOrgas($search);
        }

        $topOrgas = $this->model->getTopOrgas();

        $userBalance = 0;
        if(isset($_SESSION['user_id'])) {
            $userBalance = $this->model->getUserBalance($_SESSION['user_id']);
        }

        $this->view->displayHome($orgas, $search, $userBalance, $topOrgas, $favIds, $view);
    }
}
?>