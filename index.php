<?php
session_start();

// Routeur modulaire
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'auth':
        require_once 'mod_auth/mod_auth.php';
        $module = new mod_auth();
        $module->exec();
        break;
    case 'product':
        require_once 'mod_product/mod_product.php';
        $module = new mod_product();
        $module->exec();
        break;
    case 'orga':
        require_once 'mod_orga/mod_orga.php';
        $module = new mod_orga();
        $module->exec();
        break;
    case 'barman':
        require_once 'mod_barmen/mod_barman.php';
        $module = new mod_barman();
        $module->exec();
        break;
    case 'solde':
        require_once 'mod_solde/mod_solde.php';
        $module = new mod_solde();
        $module->exec();
        break;
    case 'home':
    default:
        require_once 'mod_home/mod_home.php';
        $module = new mod_home();
        $module->exec();
        break;
}
