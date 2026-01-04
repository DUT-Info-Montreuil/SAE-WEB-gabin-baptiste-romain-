<?php
class vue_auth {
    public function form_login($error = null) {
        ?>
        <div>
            <div>
                <h2>Buvettes.</h2>
                <h2>Bon retour parmi nous</h2>
                <p>Connectez-vous à votre compte</p>
            </div>

            <div>
                <div>
                    <!-- Decorative element -->
                    <div></div>
                    
                    <?php if ($error): ?>
                        <div>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?page=auth&action=login" method="POST">
                        <div>
                            <label>Adresse Email</label>
                            <input name="email" type="email" required 
                                  >
                        </div>

                        <div>
                            <label>Mot de passe</label>
                            <input name="password" type="password" required 
                                  >
                        </div>

                        <div>
                            <button type="submit" 
                                   >
                                Se connecter
                            </button>
                        </div>
                    </form>

                    <div>
                        <p>Pas encore de compte ?</p>
                        <a href="index.php?page=auth&action=register_form">Créer un compte</a>
                    </div>
                </div>
                
                <div>
                    <a href="index.php">
                        
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    public function form_register($error = null) {
        ?>
        <div>
            <div>
                <h2>Rejoignez-nous</h2>
                <p>Créez votre compte en quelques secondes</p>
            </div>

            <div>
                <div>
                    <?php if ($error): ?>
                        <div>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?page=auth&action=register" method="POST">
                        <div>
                            <div>
                                <label>Nom</label>
                                <input name="nom" type="text" required 
                                      >
                            </div>
                            <div>
                                <label>Prénom</label>
                                <input name="prenom" type="text" required 
                                      >
                            </div>
                        </div>

                        <div>
                            <label>Adresse Email</label>
                            <input name="email" type="email" required 
                                  >
                        </div>

                        <div>
                            <label>Mot de passe</label>
                            <input name="password" type="password" required 
                                  >
                        </div>

                        <div>
                            <label>Confirmation</label>
                            <input name="confirm_password" type="password" required 
                                  >
                        </div>

                        <div>
                            <button type="submit" 
                                   >
                                Créer mon compte
                            </button>
                        </div>
                    </form>

                    <div>
                        <p>Déjà un compte ?</p>
                        <a href="index.php?page=auth&action=login_form">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
