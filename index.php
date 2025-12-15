<?php
session_start();

// Chargement des contrôleurs
require_once 'controllers/AuthController.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/OrgaController.php'; // Ajout du contrôleur manquant

// Routeur simple
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;
    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'product':
        $controller = new ProductController();
        $controller->detail();
        break;
    case 'orga':
        $controller = new OrgaController();
        $controller->detail();
        break;
    case 'home':
    default:
        $controller = new HomeController();
        $controller->index();
        break;
}
