<?php

require_once 'models/Organization.php';
require_once 'views/buvette/orga_detail.php';

class OrgaController {

    public function detail() {
        $organizationId = $_GET['id'] ?? null;

        if (!$organizationId) {
            header('Location: index.php?page=home');
            exit;
        }

        $orgModel = new Organization();
        $organization = $orgModel->findById($organizationId);

        if (!$organization) {
            echo "Organisation non trouvée.";
            return;
        }

        $products = $orgModel->getProducts($organizationId);

        $view = new OrgaDetailView();
        $view->display($organization, $products);
    }
}
