<?php
session_start();

// Chargement des contrôleurs
require_once 'controllers/AuthController.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/BarmanController.php';

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
    case 'barman':
        $controller = new BarmanController();
        $controller->index();
        break;
        // Suppression des anciennes routes AJAX inutiles
        case 'home':
        default:
            $controller = new HomeController();
        $controller->index();
        break;
}