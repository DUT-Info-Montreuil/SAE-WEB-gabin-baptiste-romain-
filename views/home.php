<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Buvettes</title>
</head>
<body>

    <nav>
        <div>
            <h1>SAE Buvettes</h1>
            <ul>
                <?php if ($is_logged_in): ?>
                    <li>
                        <span>Bonjour, <strong><?php echo htmlspecialchars($user_email); ?></strong></span>
                        <span>(Solde : <?php echo number_format($user_balance, 2, ',', ' '); ?> €)</span>
                    </li>
                    <li><a href="index.php?page=logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="index.php?page=login">Se connecter</a></li>
                    <li><a href="index.php?page=register">S'inscrire</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main>
        <h2>Liste des Buvettes</h2>

        <!-- Barre de recherche -->
        <form action="index.php" method="GET">
            <input type="hidden" name="page" value="home">
            <input type="text" name="search" placeholder="Rechercher une buvette..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Rechercher</button>
            <?php if (!empty($search)): ?>
                <a href="index.php">Effacer</a>
            <?php endif; ?>
        </form>

        <hr>

        <!-- Liste des organisations -->
        <?php if (empty($organizations)): ?>
            <p>Aucune buvette trouvée.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($organizations as $org): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($org['name']); ?></h3>
                        <p>Adresse : <?php echo htmlspecialchars($org['address']); ?></p>
                        <a href="index.php?page=orga&id=<?php echo $org['id']; ?>">Voir les articles</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>

</body>
</html>
