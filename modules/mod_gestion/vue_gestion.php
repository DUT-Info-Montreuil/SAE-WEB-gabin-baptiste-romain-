<?php
class vue_gestion {
    public function displayGestion($orga, $products, $history, $transactions, $message = null) {
        $currentTab = $_GET['tab'] ?? 'products';
        ?>
        <div>
            <a href="index.php?page=orga&id=<?= $orga['id'] ?>"><- Retour à la buvette</a>
            <h1>Gestion : <?= htmlspecialchars($orga['nom']) ?></h1>
            <p><strong>Trésorerie Actuelle : <?= number_format($orga['solde'], 2) ?> €</strong></p>

            <?php if ($message): ?>
                <p><strong><?= htmlspecialchars($message) ?></strong></p>
            <?php endif; ?>

            <nav>
                <a href="index.php?page=gestion&id=<?= $orga['id'] ?>&tab=products">[PRODUITS]</a> | 
                <a href="index.php?page=gestion&id=<?= $orga['id'] ?>&tab=sales">[VENTES]</a> | 
                <a href="index.php?page=gestion&id=<?= $orga['id'] ?>&tab=stock">[STOCK]</a> | 
                <a href="index.php?page=gestion&id=<?= $orga['id'] ?>&tab=barmen">[BARMEN]</a>
            </nav>
            <hr>

            <!-- Tab Content: PRODUCTS -->
            <?php if ($currentTab === 'products'): ?>
                <h2>Liste des produits</h2>
                <a href="index.php?page=gestion&id=<?= $orga['id'] ?>&tab=add_product_form">[ + AJOUTER UN PRODUIT ]</a>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Catégorie</th>
                            <th>Nom</th>
                            <th>Prix</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['categorie']) ?></td>
                                <td><?= htmlspecialchars($p['nom']) ?></td>
                                <td><?= number_format($p['prix_vente'], 2) ?> €</td>
                                <td>
                                    <a href="index.php?page=gestion&id=<?= $orga['id'] ?>&tab=edit_product_form&id_produit=<?= $p['id'] ?>">[Modifier]</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- Tab Content: SALES -->
            <?php if ($currentTab === 'sales'): ?>
                <h2>Historique des Ventes</h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Articles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $order): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($order['date_heure'])) ?></td>
                                <td><?= htmlspecialchars($order['client_prenom'] . ' ' . $order['client_nom']) ?> (<?= htmlspecialchars($order['client_email']) ?>)</td>
                                <td><?= number_format($order['montant_total'], 2) ?> €</td>
                                <td>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <?= $item['quantite'] ?>x <?= htmlspecialchars($item['nom']) ?><br>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <!-- Tab Content: STOCK -->
            <?php if ($currentTab === 'stock'): ?>
                <h2>État des Stocks</h2>
                <table border="1">
                    <thead><tr><th>Produit</th><th>Quantité</th></tr></thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr><td><?= htmlspecialchars($p['nom']) ?></td><td><?= $p['stock'] ?> unités</td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <hr>
                <h2>Approvisionnement</h2>
                <form method="post" action="index.php?page=gestion&id=<?= $orga['id'] ?>&action=add_stock&tab=stock">
                    Produit : <select name="product_id" required>
                        <?php foreach ($products as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
                        <?php endforeach; ?>
                    </select><br>
                    Quantité : <input type="number" name="quantity" required><br>
                    Coût total : <input type="number" name="cost" step="0.01" required><br>
                    Fournisseur : <input type="text" name="supplier" required><br>
                    <button type="submit">Valider l'achat</button>
                </form>
                <hr>
                <h2>Historique Appro</h2>
                <table border="1">
                    <?php foreach ($history as $h): ?>
                        <tr>
                            <td><?= htmlspecialchars($h['product_name']) ?></td>
                            <td>+<?= $h['quantity'] ?> (<?= number_format($h['cost'], 2) ?> €)</td>
                            <td><?= htmlspecialchars($h['supplier']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

            <!-- Tab Content: BARMEN -->
            <?php if ($currentTab === 'barmen'): ?>
                <h2>Promotion Barman</h2>
                <form method="post" action="index.php?page=gestion&id=<?= $orga['id'] ?>&action=add_barman_existing&tab=barmen">
                    Email : <input type="email" name="email" required>
                    <button type="submit">Promouvoir</button>
                </form>
                <hr>
                <h2>Nouveau compte Barman</h2>
                <form method="post" action="index.php?page=gestion&id=<?= $orga['id'] ?>&action=create_barman&tab=barmen">
                    Email : <input type="email" name="email" required><br>
                    Nom : <input type="text" name="nom" required><br>
                    Prénom : <input type="text" name="prenom" required><br>
                    Mot de passe : <input type="password" name="password" required><br>
                    <button type="submit">Créer et lier</button>
                </form>
            <?php endif; ?>

            <!-- Custom Tab: Add Product Form -->
            <?php if ($currentTab === 'add_product_form'): ?>
                <h2>Nouveau produit</h2>
                <form action="index.php?page=gestion&id=<?= $orga['id'] ?>&action=add_product&tab=products" method="POST">
                    Nom : <input type="text" name="nom" required><br>
                    Prix : <input type="number" name="prix" step="0.01" required><br>
                    Catégorie : <input type="text" name="categorie" required><br>
                    Stock : <input type="number" name="stock" required><br>
                    Description : <textarea name="description"></textarea><br>
                    <button type="submit">Créer</button>
                    <a href="index.php?page=gestion&id=<?= $orga['id'] ?>">Annuler</a>
                </form>
            <?php endif; ?>

            <!-- Custom Tab: Edit Product Form -->
            <?php if ($currentTab === 'edit_product_form'): 
                $id_to_edit = $_GET['id_produit'] ?? null;
                $p_edit = null;
                foreach($products as $prod) { if($prod['id'] == $id_to_edit) $p_edit = $prod; }
                if($p_edit):
            ?>
                <h2>Modifier : <?= htmlspecialchars($p_edit['nom']) ?></h2>
                <form action="index.php?page=gestion&id=<?= $orga['id'] ?>&action=update_product&tab=products" method="POST">
                    <input type="hidden" name="id_produit" value="<?= $p_edit['id'] ?>">
                    Nom : <input type="text" name="nom" value="<?= htmlspecialchars($p_edit['nom']) ?>" required><br>
                    Prix : <input type="number" name="prix" step="0.01" value="<?= $p_edit['prix_vente'] ?>" required><br>
                    Catégorie : <input type="text" name="categorie" value="<?= htmlspecialchars($p_edit['categorie']) ?>" required><br>
                    Description : <textarea name="description"><?= htmlspecialchars($p_edit['description']) ?></textarea><br>
                    <button type="submit">Enregistrer</button>
                    <a href="index.php?page=gestion&id=<?= $orga['id'] ?>">Annuler</a>
                </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }

    public function displayEditForm($orga, $product) {
        // Redirige vers le système d'onglets unifié
        header("Location: index.php?page=gestion&id=" . $orga['id'] . "&tab=edit_product_form&id_produit=" . $product['id']);
        exit;
    }
}
?>
