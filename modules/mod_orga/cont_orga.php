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
        if ($action === 'submit_join' && isset($_SESSION['user_id'])) {
            $this->submitJoin($id);
            header("Location: index.php?page=orga&id=" . $id);
            exit;
        }
        if ($action === 'cancel_join' && isset($_SESSION['user_id'])) {
            $this->model->cancelJoinRequest($_SESSION['user_id'], $id);
            header("Location: index.php?page=orga&id=" . $id);
            exit;
        }
        if ($action === 'leave' && isset($_SESSION['user_id'])) {
            $this->model->leaveOrga($_SESSION['user_id'], $id);
            header("Location: index.php?page=orga&id=" . $id);
            exit;
        }

        $orga = $this->model->getOrgaById($id);
        $products = $this->model->getProductsByOrga($id);
        
        $isMember = false;
        $userRole = null;
        $balance = 0;
        $cartCount = 0;
        $hasPending = false;
        $memberInfo = null;
        
        if (isset($_SESSION['user_id'])) {
            $isMember = $this->model->isMember($_SESSION['user_id'], $id);
            if ($isMember) {
                $userRole = $this->model->getUserRoleInOrga($_SESSION['user_id'], $id);
                $memberInfo = $this->model->getMemberInfo($_SESSION['user_id'], $id);
            } else {
                $hasPending = $this->model->hasPendingRequest($_SESSION['user_id'], $id);
            }
            $balance = $this->model->getUserBalance($_SESSION['user_id'], $id);
            
            if (isset($_SESSION['cart'][$id])) {
                 foreach($_SESSION['cart'][$id] as $item) {
                     $cartCount += $item['quantity'];
                 }
            }
        }

        $this->view->displayOrga($orga, $products, $isMember, $userRole, $balance, $cartCount, $hasPending, $memberInfo);
    }

    private function submitJoin($orgaId) {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        $majeur = isset($_POST['majeur']) ? 1 : 0;

        if ($nom && $prenom && $adresse && $majeur) {
            $this->model->createJoinRequest($_SESSION['user_id'], $orgaId, $nom, $prenom, $adresse, $majeur);
        }
    }
}
?>