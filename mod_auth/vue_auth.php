<?php
class vue_auth {
    public function form_login($error = null) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Connexion</title>
        </head>
        <body>
            <div>
                <div>
                    <div>
                        <div>
                            <div>Connexion</div>
                            <div>
                                <?php if ($error): ?>
                                    <div><?= htmlspecialchars($error) ?></div>
                                <?php endif; ?>
                                <form method="post" action="index.php?page=auth&action=login">
                                    <div>
                                        <label>Email</label>
                                        <input type="email" name="email" required>
                                    </div>
                                    <div>
                                        <label>Mot de passe</label>
                                        <input type="password" name="password" required>
                                    </div>
                                    <button type="submit">Se connecter</button>
                                    <a href="index.php?page=auth&action=register_form">Créer un compte</a>
                                    <div>
                                        <a href="index.php?page=home">Accueil</a>
                                    </div>
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

    public function form_register($error = null) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Inscription</title>
        </head>
        <body>
            <div>
                <div>
                    <div>
                        <div>
                            <div>Inscription</div>
                            <div>
                                <?php if ($error): ?>
                                    <div><?= htmlspecialchars($error) ?></div>
                                <?php endif; ?>
                                <form method="post" action="index.php?page=auth&action=register">
                                    <div>
                                        <label>Email</label>
                                        <input type="email" name="email" required>
                                    </div>
                                    <div>
                                        <label>Mot de passe</label>
                                        <input type="password" name="password" required>
                                    </div>
                                    <div>
                                        <label>Confirmer le mot de passe</label>
                                        <input type="password" name="confirm_password" required>
                                    </div>
                                    <button type="submit">S'inscrire</button>
                                    <a href="index.php?page=auth&action=login_form">Déjà un compte ?</a>
                                    <div>
                                        <a href="index.php?page=home">Accueil</a>
                                    </div>
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