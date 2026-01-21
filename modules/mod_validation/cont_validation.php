<?php
require_once __DIR__ . '/modele_validation.php';

class cont_validation {
    private $model;

    public function __construct() {
        $this->model = new modele_validation();
    }

    public function exec() {

        if (!isset($_SESSION['user_id'])) {
            if (ob_get_length()) ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Non connecté']);
            exit;
        }

        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'check_pending':
                $this->checkPending();
                break;
            case 'validate':
                $this->validate();
                break;
            case 'refuse':
                $this->refuse();
                break;
        }
    }

    private function checkPending() {
        $order = $this->model->checkPendingOrder($_SESSION['user_id']);
        
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        
        if ($order) {
            echo json_encode([
                'waiting' => true,
                'montant' => $order['montant_total'],
                'id_commande' => $order['id']
            ]);
        } else {
            echo json_encode(['waiting' => false]);
        }
        exit;
    }

    private function validate() {
        $orderId = $_GET['id_commande'] ?? null;
        $success = false;
        
        if ($orderId) {
            $success = $this->model->validateOrder($orderId, $_SESSION['user_id']);
        }

        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }

    private function refuse() {
        $orderId = $_GET['id_commande'] ?? null;
        $success = false;

        if ($orderId) {
            $success = $this->model->refuseOrder($orderId, $_SESSION['user_id']);
        }

        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }
}
?>