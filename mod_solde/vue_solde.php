<?php
class vue_solde {
    public function displayBalance($balance, $message = null) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Mon Solde</title>
        </head>
        <body>
            <div>
                <a href="index.php">Retour Accueil</a>
                <h1>Mon Solde</h1>
                
                <?php if ($message): ?>
                    <p><strong><?= htmlspecialchars($message) ?></strong></p>
                <?php endif; ?>

                <p>Votre solde actuel est de : <strong><?= number_format($balance, 2) ?> €</strong></p>

                <hr>

                <h3>Recharger mon compte</h3>
                <form method="post" action="index.php?page=solde&action=recharge">
                    <label>Montant à ajouter (€) :</label>
                    <input type="number" name="amount" step="0.01" min="1" required>
                    <button type="submit">Recharger</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    }
}
?>