<?php

class vue_barman {

    public function __construct() {
    }

    public function menu() {
        // Optionnel : afficher un menu spécifique ici si besoin
    }

    public function afficher_interface($products, $searchResults, $searchQuery, $success_message, $error_message) {
        // Début de l'affichage HTML
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Caisse Barman</title>
        </head>
        <body>

        <div>

            <?php if ($success_message): ?>
                <div><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <div>
                <div>
                    <div>
                        <h2>Produits</h2>
                        <a href="index.php">Retour Accueil</a>
                    </div>

                    <div>
                        <?php foreach ($products as $product): ?>
                            <div>
                                <div>
                                    <div>
                                        <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                                        <p><?php echo number_format($product['price'], 2); ?> €</p>

                                        <form method="POST" action="index.php?page=barman&action=add_to_cart">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                            <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                                            <button type="submit">Ajouter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <div>
                        <div>
                            <h4>Caisse</h4>
                        </div>
                        <div>

                            <div>
                                <h5>1. Client</h5>

                                <?php if (isset($_SESSION['selected_client']) && $_SESSION['selected_client']): ?>
                                    <div>
                                        <strong><?php echo htmlspecialchars($_SESSION['selected_client']['email']); ?></strong><br>
                                        Solde : <?php echo number_format($_SESSION['selected_client']['balance'], 2); ?> €
                                        <form method="POST" action="index.php?page=barman&action=reset_client">
                                            <button type="submit">Changer</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <form method="POST" action="index.php?page=barman&action=caisse">
                                        <input type="text" name="q" placeholder="Email..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                                        <button type="submit">Go</button>
                                    </form>

                                    <?php if (!empty($searchResults)): ?>
                                        <div>
                                            <?php foreach ($searchResults as $client): ?>
                                                <form method="POST" action="index.php?page=barman&action=select_client">
                                                    <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                                    <div>
                                                        <?php echo htmlspecialchars($client['email']); ?>
                                                        <small>(<?php echo $client['balance']; ?> €)</small>
                                                    </div>
                                                    <button type="submit">Choisir</button>
                                                </form>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <div>
                                <h5>2. Panier</h5>
                                <?php if (empty($_SESSION['cart'])): ?>
                                    <p>Vide.</p>
                                <?php else: ?>
                                    <table>
                                        <tbody>
                                        <?php
                                        $total = 0;
                                        foreach ($_SESSION['cart'] as $item):
                                            $lineTotal = $item['price'] * $item['qty'];
                                            $total += $lineTotal;
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                <td>x<?php echo $item['qty']; ?></td>
                                                <td><?php echo number_format($lineTotal, 2); ?> €</td>
                                                <td>
                                                    <form method="POST" action="index.php?page=barman&action=remove_from_cart" style="display:inline;">
                                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                        <button type="submit">&times;</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="2">TOTAL</td>
                                            <td colspan="2"><?php echo number_format($total, 2); ?> €</td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                <?php endif; ?>
                            </div>

                            <form method="POST" action="index.php?page=barman&action=validate_purchase">
                                <button type="submit"
                                        <?php if (empty($_SESSION['cart']) || !isset($_SESSION['selected_client']) || !$_SESSION['selected_client']): ?> disabled <?php endif; ?>>
                                    VALIDER L'ACHAT
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </body>
        </html>
        <?php
    }
}
?>