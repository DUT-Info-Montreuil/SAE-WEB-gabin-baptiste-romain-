<!DOCTYPE html>
<html lang="fr">
<head>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Caisse Barman (No JS)</title>
    <link href="" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid py-3">
    
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- GAUCHE : Liste des produits -->
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Produits</h2>
                <a href="index.php" class="btn btn-outline-secondary btn-sm">Retour Accueil</a>
            </div>
            
            <div class="row g-3">
                <?php foreach ($products as $product): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text fw-bold text-primary"><?php echo number_format($product['price'], 2); ?> €</p>
                                
                                <!-- Formulaire d'ajout au panier -->
                                <form method="POST" action="index.php?page=barman">
                                    <input type="hidden" name="action" value="add_to_cart">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Ajouter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- DROITE : Panier et Client -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Caisse</h4>
                </div>
                <div class="card-body">
                    
                    <!-- 1. Sélection Client -->
                    <div class="mb-4 border-bottom pb-3">
                        <h5>1. Client</h5>
                        
                        <?php if (isset($_SESSION['selected_client']) && $_SESSION['selected_client']): ?>
                            <!-- Client Sélectionné -->
                            <div class="alert alert-success">
                                <strong><?php echo htmlspecialchars($_SESSION['selected_client']['email']); ?></strong><br>
                                Solde : <?php echo number_format($_SESSION['selected_client']['balance'], 2); ?> €
                                <form method="POST" action="index.php?page=barman" class="mt-2">
                                    <input type="hidden" name="action" value="reset_client">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Changer de client</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <!-- Formulaire Recherche -->
                            <form method="GET" action="index.php" class="d-flex mb-2">
                                <input type="hidden" name="page" value="barman">
                                <input type="text" name="q" class="form-control me-2" placeholder="Rechercher (email)..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                                <button type="submit" class="btn btn-secondary">Go</button>
                            </form>

                            <!-- Résultats Recherche -->
                            <?php if (!empty($searchResults)): ?>
                                <div class="list-group">
                                    <?php foreach ($searchResults as $client): ?>
                                        <form method="POST" action="index.php?page=barman" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <input type="hidden" name="action" value="select_client">
                                            <input type="hidden" name="client_id" value="<?php echo $client['id']; ?>">
                                            <div>
                                                <?php echo htmlspecialchars($client['email']); ?> 
                                                <small class="text-muted">(<?php echo $client['balance']; ?> €)</small>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-success">Choisir</button>
                                        </form>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif(!empty($searchQuery)): ?>
                                <p class="text-muted small">Aucun client trouvé.</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- 2. Panier -->
                    <div class="mb-3">
                        <h5>2. Panier</h5>
                        <?php if (empty($_SESSION['cart'])): ?>
                            <p class="text-muted">Panier vide.</p>
                        <?php else: ?>
                            <table class="table table-sm table-striped">
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
                                            <form method="POST" action="index.php?page=barman" style="display:inline;">
                                                <input type="hidden" name="action" value="remove_from_cart">
                                                <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="btn btn-xs text-danger p-0 border-0">&times;</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold fs-5">
                                        <td colspan="2">TOTAL</td>
                                        <td colspan="2"><?php echo number_format($total, 2); ?> €</td>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php endif; ?>
                    </div>

                    <!-- 3. Validation -->
                    <form method="POST" action="index.php?page=barman">
                        <input type="hidden" name="action" value="validate_purchase">
                        <button type="submit" class="btn btn-success w-100 py-3" 
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