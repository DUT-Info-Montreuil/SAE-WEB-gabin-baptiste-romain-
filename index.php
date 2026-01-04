<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Buvettes App (Version Brut)</title>
</head>
<body>
    <div>
        <?php
        $page = $_GET['page'] ?? 'home';
        $currentView = $_GET['view'] ?? 'all';
        $navBalance = 0;
        $cartCount = 0;
        if (isset($_SESSION['user_id'])) {
            require_once 'modules/mod_home/modele_home.php';
            $homeModel = new modele_home();
            $navBalance = $homeModel->getUserBalance($_SESSION['user_id']);
            if(isset($_SESSION['cart'])) {
                foreach($_SESSION['cart'] as $item) $cartCount += $item['quantity'];
            }
        }
        ?>

        <main>
            <?php
            switch ($page) {
                case 'auth': require_once 'modules/mod_auth/mod_auth.php'; (new mod_auth())->exec(); break;
                case 'profile': require_once 'modules/mod_profile/mod_profile.php'; (new mod_profile())->exec(); break;
                case 'gestion': require_once 'modules/mod_gestion/mod_gestion.php'; (new mod_gestion())->exec(); break;
                case 'product': require_once 'modules/mod_product/mod_product.php'; (new mod_product())->exec(); break;
                case 'orga': require_once 'modules/mod_orga/mod_orga.php'; (new mod_orga())->exec(); break;
                case 'solde': require_once 'modules/mod_solde/mod_solde.php'; (new mod_solde())->exec(); break;
                case 'buy': require_once 'modules/mod_buy/mod_buy.php'; (new mod_buy())->exec(); break;
                case 'home': default: require_once 'modules/mod_home/mod_home.php'; (new mod_home())->exec(); break;
            }
            ?>
        </main>

        <?php if($page !== 'barman'): ?>
        <footer>
            <hr>
            <nav>
                <a href="index.php">ACCUEIL</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                | <a href="index.php?view=favorites">FAVORIS</a>
                | <a href="index.php?page=solde">RECHARGER (<?= number_format($navBalance, 0) ?>€)</a>
                | <a href="index.php?page=buy">PANIER (<?= $cartCount ?>)</a>
                | <a href="index.php?page=profile">PROFIL</a>
                <?php else: ?>
                | <a href="index.php?page=auth&action=login_form">CONNEXION</a>
                <?php endif; ?>
            </nav>
        </footer>
        <?php endif; ?>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>