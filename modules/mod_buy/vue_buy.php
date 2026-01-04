<?php
class vue_buy {
    public function displayCart($cart, $message = null, $success = false) {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        require_once __DIR__ . '/../mod_product/vue_product.php';
        $pv = new vue_product();
        ?>
        <div>
            <header>
                <a href="index.php">
                    
                    Retour
                </a>
                <h1>Mon Panier</h1>
            </header>

            <?php if ($message): ?>
                <div>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div id="cart-content">
                <?php if (empty($cart)): ?>
                    <div>
                        <div>
                            
                        </div>
                        <p>Votre panier est vide</p>
                        <a href="index.php">Découvrir les buvettes</a>
                    </div>
                <?php else: ?>
                    <div>
                        <?php foreach ($cart as $id => $item): ?>
                            <div data-product-id="<?= $id ?>">
                                <div>
                                    <h4><?= htmlspecialchars($item['name']) ?></h4>
                                    <p><?= number_format($item['price'], 2) ?> € / unité</p>
                                </div>
                                <div>
                                    <div data-product-id="<?= $id ?>">
                                        <?php $pv->renderCartControls($id, $item['quantity']); ?>
                                    </div>
                                    <span><?= number_format($item['price'] * $item['quantity'], 2) ?> €</span>
                                    <a href="index.php?page=buy&action=remove&id_product=<?= $id ?>">
                                        
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div>
                        <div>
                            <span>Total à payer</span>
                            <span><span id="cart-total-price"><?= number_format($total, 2) ?></span> €</span>
                        </div>
                        
                                            <a href="index.php?page=buy&action=confirm">
                        
                                                Confirmer l'achat
                        
                                            </a>
                        
                        
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
?>