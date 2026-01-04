<?php
class vue_home {
    public function displayHome($orgas, $search, $userBalance, $topOrgas, $favIds = [], $currentView = 'all') {
        ?>
        <div>
            <?php if (!empty($topOrgas) && $currentView === 'all'): ?>
            <section>
                <h2>Buvettes Populaires</h2>
                <ul>
                    <?php foreach ($topOrgas as $index => $orga): ?>
                        <li>
                            <strong>Top <?= $index + 1 ?> : <?= htmlspecialchars($orga['name']) ?></strong> - <?= htmlspecialchars($orga['address']) ?>
                            <a href="index.php?page=orga&id=<?= $orga['id'] ?>">[Visiter]</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <hr>
            <?php endif; ?>

            <section>
                <form method="get" action="index.php">
                    <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit">Rechercher</button>
                </form>
            </section>

            <section>
                <h3><?= $currentView === 'favorites' ? 'Mes Buvettes Favorites' : 'Toutes les buvettes' ?></h3>
                <?php if($currentView === 'favorites'): ?>
                    <a href="index.php">Voir toutes les buvettes</a>
                <?php endif; ?>

                <ul>
                    <?php foreach ($orgas as $orga): ?>
                        <li>
                            <h4><?= htmlspecialchars($orga['name']) ?></h4>
                            <p><?= htmlspecialchars($orga['address']) ?></p>
                            
                            <?php if(isset($_SESSION['user_id'])): 
                                $isFav = in_array($orga['id'], $favIds);
                            ?>
                                <a href="index.php?page=home&action=toggle_favorite&id_orga=<?= $orga['id'] ?>">
                                    <?= $isFav ? '[Retirer des favoris]' : '[Ajouter aux favoris]' ?>
                                </a>
                            <?php endif; ?>
                            
                            <a href="index.php?page=orga&id=<?= $orga['id'] ?>">Voir les produits</a>
                        </li>
                        <hr>
                    <?php endforeach; ?>
                </ul>

                <?php if (empty($orgas)): ?>
                    <p><?= $currentView === 'favorites' ? 'Aucun favori' : 'Aucune buvette trouvée' ?></p>
                    <a href="index.php">Réinitialiser</a>
                <?php endif; ?>
            </section>
        </div>
        <?php
    }
}
?>