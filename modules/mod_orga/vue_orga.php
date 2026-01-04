<?php
require_once __DIR__ . '/../mod_product/vue_product.php';

class vue_orga {
    public function displayOrga($orga, $products, $isMember = false, $userRole = null) {
        ?>
        <div>
            <a href="index.php"><- Retour</a>
            <h1><?= htmlspecialchars($orga['name']) ?></h1>
            <p><?= htmlspecialchars($orga['address']) ?></p>

            <nav>
                <?php if ($userRole === 'ROLE_GESTION'): ?>
                    <a href="index.php?page=gestion&id=<?= $orga['id'] ?>">[ADMINISTRATION]</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (!$isMember): ?>
                        <a href="index.php?page=orga&id=<?= $orga['id'] ?>&action=join">[REJOINDRE LA BUVETTE]</a>
                    <?php else: ?>
                        <strong>(MEMBRE)</strong>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>

            <hr>
            <h3>Produits disponibles</h3>
            
            <ul>
                <?php foreach ($products as $product): ?>
                    <li>
                        <?php 
                        $pv = new vue_product();
                        $pv->displayProductCard($product); 
                        ?>
                    </li>
                    <hr>
                <?php endforeach; ?>
            </ul>

            <?php if (empty($products)): ?>
                <p>Aucun produit disponible</p>
            <?php endif; ?>
        </div>
        <?php
    }
}
?>
