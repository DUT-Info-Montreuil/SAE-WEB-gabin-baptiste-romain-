<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Stocks</title>
    <link rel="stylesheet" href="css/barman.css">
    <style>
        .stock-table { width: 100%; border-collapse: collapse; background: white; }
        .stock-table th, .stock-table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        .stock-table th { background-color: #eee; }
    </style>
</head>
<body>

<div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>État des Stocks</h1>
    <div>
        <a href="index.php?page=barman" style="margin-right: 15px;">Retour Caisse</a>
        <a href="index.php">Accueil</a>
    </div>
</div>

<table class="stock-table">
    <thead>
    <tr>
        <th>Produit</th>
        <th>Prix de Vente</th>
        <th>Stock Actuel</th>
        <th>Statut</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><strong><?php echo htmlspecialchars($product['name']); ?></strong></td>
            <td><?php echo number_format($product['price'], 2); ?> €</td>
            <td> <?php echo htmlspecialchars($product['quantity']); ?></td>
            <td>
                <?php if ($product['quantity'] <= 0): ?>
                    <span style="color: red">Rupture</span>
                <?php elseif ($product['quantity'] <= 10): ?>
                    <span style="color: orange;">À réapprovisionner</span>
                <?php else: ?>
                    Disponible
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>