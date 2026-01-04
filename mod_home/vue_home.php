<?php
class vue_home {
    public function displayHome($orgas, $search, $userBalance) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Accueil</title>
        </head>
        <body>
            <nav>
                <div>
                    <a href="index.php">Accueil</a>
                    <div>
                        <ul>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li><span>Solde: <?= number_format($userBalance, 2) ?> €</span></li>
                                <li><a href="index.php?page=barman">Espace Barman</a></li>
                                <li><a href="index.php?page=auth&action=logout">Déconnexion</a></li>
                                <li><a href="index.php?page=solde">Recharger solde</a></li>
                            <?php else: ?>
                                <li><a href="index.php?page=auth&action=login_form">Connexion</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>

            <div>
                <form method="get" action="index.php">
                    <div>
                        <input type="text" name="search" placeholder="Rechercher une organisation..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit">Rechercher</button>
                    </div>
                </form>

                <div>
                    <?php foreach ($orgas as $orga): ?>
                        <div>
                            <div>
                                <div>
                                    <h5><?= htmlspecialchars($orga['name']) ?></h5>
                                    <p><?= htmlspecialchars($orga['address']) ?></p>
                                    <a href="index.php?page=orga&id=<?= $orga['id'] ?>">Voir détails</a>
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