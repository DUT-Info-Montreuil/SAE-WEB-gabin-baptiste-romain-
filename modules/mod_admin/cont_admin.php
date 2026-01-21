<?php
require_once __DIR__ . "/modele_admin.php";
require_once __DIR__ . "/vue_admin.php";

class cont_admin {
    private $model;
    private $view;

    public function __construct() {
        $this->model = new modele_admin();
        $this->view = new vue_admin();
    }

    public function exec() {
        if (!isset($_SESSION['user_id']) || !$this->model->isAdmin($_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        }

        $action = $_GET['action'] ?? 'list';
        switch ($action) {
            case 'add': $this->add(); break;
            case 'edit': $this->edit(); break;
            case 'form_add': $this->view->displayForm(); break;
            case 'form_edit': $this->form_edit(); break;
            case 'manage_staff': $this->manageStaff(); break;
            case 'assign_role': $this->assignRole(); break;
            case 'list': default: $this->list(); break;
        }
    }

    private function list() {
        $buvettes = $this->model->getBuvettes();
        $this->view->displayList($buvettes);
    }

    private function form_edit() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $buvette = $this->model->getBuvette($id);
            if ($buvette) {
                $this->view->displayForm($buvette);
                return;
            }
        }
        header("Location: index.php?page=admin");
    }

    private function add() {
        $nom = $_POST['nom'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $prix = $_POST['prix'] ?? 10.00;
        $photo = null;

        if (isset($_FILES['photo'])) {
            $photo = $this->uploadImage($_FILES['photo'], 'uploads/buvettes');
        }

        if ($nom) {
            $this->model->createBuvette($nom, $adresse, $photo, $prix, $email, $telephone);
        }
        header("Location: index.php?page=admin");
    }

    private function edit() {
        $id = $_GET['id'] ?? null;
        $nom = $_POST['nom'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $prix = $_POST['prix'] ?? 10.00;
        $photo = null;

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
            $photo = $this->uploadImage($_FILES['photo'], 'uploads/buvettes');
        }

        if ($id && $nom) {
            $this->model->updateBuvette($id, $nom, $adresse, $photo, $prix, $email, $telephone);
        }
        header("Location: index.php?page=admin");
    }

    private function manageStaff() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $buvette = $this->model->getBuvette($id);
            $staff = $this->model->getStaff($id);
            $this->view->displayStaffForm($buvette, $staff);
        } else {
            header("Location: index.php?page=admin");
        }
    }

    private function assignRole() {
        $buvetteId = $_GET['id'] ?? null;
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? '';
        
        if ($buvetteId && $email && in_array($role, ['ROLE_GESTION', 'ROLE_BARMAN'])) {
            $this->model->assignRole($email, $buvetteId, $role);
        }
        header("Location: index.php?page=admin&action=manage_staff&id=$buvetteId");
    }

    private function uploadImage($file, $targetDir) {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) return null;

        // Ensure directory exists
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $filename = uniqid() . '.' . $ext;
        $targetFile = $targetDir . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $targetFile;
        }
        return null;
    }
}
?>