<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Caisse Barman</title>
    <link rel="stylesheet" href="css/barman.css">
</head>
<body>

    <div style="display:flex; justify-content: space-between; align-items: center;">
        <h1>Caisse</h1>
        <div>
            <a href="index.php?page=barman&view=stocks" style="margin-right: 20px";>Liste des Stocks</a>
            <a href="index.php">Retour Accueil</a>
        </div>
    </div>

<?php if (isset($success_message)): ?><div class="msg success"><?php echo $success_message; ?></div><?php endif; ?>
<?php if (isset($error_message)): ?><div class="msg error"><?php echo $error_message; ?></div><?php endif; ?>

<div class="layout">
    <!-- Zone Produits -->
    <div class="products-list">
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                <strong><?php echo htmlspecialchars($product['name']); ?></strong><br>
                <?php echo number_format($product['price'], 2); ?> €
                <form method="POST">
                    <input type="hidden" name="action" value="add_to_cart">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                    <button type="submit" class="btn-add">Ajouter</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Zone Caisse -->
    <div class="checkout-box">
        <h3>1. Client</h3>
        <?php if (isset($_SESSION['selected_client']) && $_SESSION['selected_client']): ?>
            <div class="msg success" style="font-size: 0.9em;">
                <?php echo htmlspecialchars($_SESSION['selected_client']['email']); ?><br>
                Solde: <?php echo number_format($_SESSION['selected_client']['balance'], 2); ?> €
                <form method="POST"><input type="hidden" name="action" value="reset_client"><button type="submit">Changer</button></form>
            </div>
        <?php else: ?>
            <form method="GET" action="index.php">
                <input type="hidden" name="page" value="barman">
                <input type="text" name="q" placeholder="Email..." style="width: 70%;">
                <button type="submit" style="width: 25%;">OK</button>
            </form>
            <?php foreach ($searchResults ?? [] as $client): ?>
                <form method="POST" style="margin-top: 5px;">
                    <input type="hidden" name="action" value="select_client">
                    <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                    <button type="submit" style="font-size: 0.8em; text-align: left;">Choisir <?php echo $client['email']; ?></button>
                </form>
            <?php endforeach; ?>
        <?php endif; ?>

        <h3>2. Panier</h3>
        <table>
            <thead>
            <tr>
                <th>Nom</th>
                <th>Qté</th>
                <th>Prix</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $total = 0; foreach (($_SESSION['cart'] ?? []) as $item):
                $line = $item['price'] * $item['qty']; $total += $line; ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo $item['qty']; ?></td>
                    <td><?php echo number_format($line, 2); ?>€</td>
                    <td style="text-align: right; padding-right: 25px;">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="remove_one">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" style="color: red; border: none; font-size: 1.2em;"> [ - ] </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <p><strong>Total : <?php echo number_format($total, 2); ?> €</strong></p>

        <form method="POST">
            <input type="hidden" name="action" value="validate_purchase">
            <button type="submit" class="btn-validate" <?php echo (empty($_SESSION['cart']) || !($_SESSION['selected_client'] ?? false)) ? 'disabled' : ''; ?>>
                ENCAISSER
            </button>
        </form>
    </div>
</div>

</body>
</html>