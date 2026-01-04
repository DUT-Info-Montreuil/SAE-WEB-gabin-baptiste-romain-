<?php
class vue_orga {
    public function displayOrga($orga, $products) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title><?= htmlspecialchars($orga['name']) ?></title>
        </head>
        <body>
            <div>
                <a href="index.php">Retour</a>
                <h1><?= htmlspecialchars($orga['name']) ?></h1>
                <p><?= htmlspecialchars($orga['address']) ?></p>
                
                <h3>Produits disponibles</h3>
                <div>
                    <?php foreach ($products as $product): ?>
                        <div>
                            <div>
                                <div>
                                    <h5><?= htmlspecialchars($product['name']) ?></h5>
                                    <p><?= number_format($product['price'], 2) ?> €</p>
                                    <a href="index.php?page=product&id=<?= $product['id'] ?>">Détails</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}
?>