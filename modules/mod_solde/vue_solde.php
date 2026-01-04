<?php
class vue_solde {
    public function displaySolde($balance, $history, $message = null) {
        ?>
        <div id="app-solde">
            <div>
                <header>
                    <div>
                        <a href="index.php">
                            
                            Retour
                        </a>
                        <h1>Solde & Historique</h1>
                    </div>
                    
                    <!-- QR Code Button -->
                    <button 
                           >
                        
                    </button>
                </header>

                <!-- Desktop Header Info -->
                <div>
                    <h1>Solde & Historique</h1>
                    <button 
                           >
                        
                        Mon QR Code
                    </button>
                </div>

                <?php if ($message): ?>
                    <div>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <!-- Balance Card -->
                <div>
                    <span>Mon Solde Actuel</span>
                    <div><?= number_format($balance, 2) ?> €</div>
                    
                    <form method="post" action="index.php?page=solde&action=add">
                        <input type="number" name="amount" placeholder="Montant (€)" min="1" required
                              >
                        <button type="submit">
                            Recharger
                        </button>
                    </form>
                </div>

                <!-- Order History -->
                <h3>
                    <span></span>
                    Derniers Achats
                </h3>

                <div>
                    <?php foreach ($history as $order): ?>
                        <div>
                            <div>
                                <div>
                                    <h4><?= htmlspecialchars($order['buvette_name']) ?></h4>
                                    <p><?= date('d/m/Y H:i', strtotime($order['date_heure'])) ?></p>
                                </div>
                                <span>-<?= number_format($order['montant_total'], 2) ?> €</span>
                            </div>
                            <div>
                                <?php foreach ($order['items'] as $item): ?>
                                    <p>
                                        <span><?= $item['quantite'] ?>x</span> 
                                        <?= htmlspecialchars($item['nom']) ?>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($history)): ?>
                        <div>
                            <p>Aucun historique d'achat</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- QR Code Modal -->
            <div>
                <div>
                    <button>
                        
                    </button>
                    
                    <h3>Votre QR Code</h3>
                    <p>Scanner pour identifier</p>
                    
                    <div>
                        <img alt="QR Code">
                    </div>
                    
                    <button>
                        Fermer
                    </button>
                </div>
            </div>
        </div>

        
        <?php
    }
}
?>
