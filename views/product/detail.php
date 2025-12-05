<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit - <?php echo htmlspecialchars($product['name']); ?></title>
</head>
<body>
    <div class="product-container">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        
        <?php 
        $imageName = $product['image'];
        $imageDir = "ressources/Product/";
        $imagePath = $imageDir . $imageName;
        $displayPath = $imagePath;
        $found = false;

        if (!empty($imageName)) {
            if (file_exists($imagePath)) {
                $found = true;
            } else {
                // Try extensions
                $extensions = ['.png', '.jpg', '.jpeg', '.gif'];
                foreach ($extensions as $ext) {
                    if (file_exists($imagePath . $ext)) {
                        $displayPath = $imagePath . $ext;
                        $found = true;
                        break;
                    }
                }
            }
        }

        if ($found): ?>
            <img src="<?php echo htmlspecialchars($displayPath); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
        <?php else: ?>
            <p><em>Aucune image disponible (<?php echo htmlspecialchars($imageName); ?>)</em></p>
        <?php endif; ?>

        <div class="product-price"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</div>
        <?php if ($product['quantity'] == 0): ?>
            <div class="product-quantity">Hors stock</div>
        <?php endif; ?>
        
        <p class="product-description">
            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
        </p>

        <a href="index.php?page=buvette" class="btn-back">Retour à l'accueil</a>
    </div>
</body>
</html>
