<?php
class vue_product {
    public function displayProduct($p) {
        if(!$p) { echo "Produit non trouvé"; return; }
        $cart = $_SESSION['cart'] ?? [];
        $qty = isset($cart[$p['id']]) ? $cart[$p['id']]['quantity'] : 0;
        ?>
        <div>
            <a href="index.php?page=orga&id=<?= $p['id_buvette'] ?>"><- Retour</a>
            <h1><?php echo htmlspecialchars($p['name']); ?></h1>
            <p><strong>Prix : <?php echo number_format($p['price'], 2); ?> €</strong></p>
            <p><?php echo nl2br(htmlspecialchars($p['description'] ?? '')); ?></p>
            <p>Stock : <?= $p['stock'] ?> unités</p>

            <?php if(($p['stock'] ?? 0) > 0): ?>
                <p>Quantité au panier : <?= $qty ?></p>
                <a href="index.php?page=buy&action=add&id_product=<?= $p['id'] ?>">[ + ] AJOUTER</a>
                <?php if($qty > 0): ?>
                    | <a href="index.php?page=buy&action=remove_one&id_product=<?= $p['id'] ?>">[ - ] RETIRER UN</a>
                <?php endif; ?>
            <?php else: ?>
                <strong>RUPTURE DE STOCK</strong>
            <?php endif; ?>
        </div>
        <?php
    }

    public function displayProductCard($product) {
        $cart = $_SESSION['cart'] ?? [];
        $qty = isset($cart[$product['id']]) ? $cart[$product['id']]['quantity'] : 0;
        ?>
        <div>
            <strong><?= htmlspecialchars($product['name']) ?></strong> (<?= $product['categorie'] ?>)<br>
            Prix : <?= number_format($product['price'], 2) ?> €<br>
            Stock : <?= $product['stock'] ?><br>
            
            <a href="index.php?page=product&id=<?= $product['id'] ?>">[INFOS]</a> |
            <?php if(($product['stock'] ?? 0) > 0): ?>
                <a href="index.php?page=buy&action=add&id_product=<?= $product['id'] ?>">[+] AJOUTER (Panier: <?= $qty ?>)</a>
                <?php if($qty > 0): ?>
                    | <a href="index.php?page=buy&action=remove_one&id_product=<?= $product['id'] ?>">[-] RETIRER</a>
                <?php endif; ?>
            <?php else: ?>
                <em>Épuisé</em>
            <?php endif; ?>
        </div>
        <?php
    }

    // Fallbacks for Brut version without AJAX
    public function renderCardControls($id, $qty) { $this->displayProductCard(['id'=>$id, 'stock'=>1]); }
    public function renderDetailControls($id, $qty) { $this->displayProduct(['id'=>$id, 'stock'=>1]); }
    public function renderCartControls($id, $qty) { ?> [Qté: <?= $qty ?>] <?php }
}
?>
