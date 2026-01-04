<?php
class vue_profile {
    public function form_profile($user, $roles = [], $message = null) {
        ?>
        <div>
            <header>
                <a href="index.php">
                    
                    Retour
                </a>
                <h1>Paramètres Profil</h1>
            </header>
            
            <?php if ($message): ?>
                <div>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div>
                <div>
                    <div>
                        
                    </div>
                    <h2><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>

                <form method="post" action="index.php?page=profile&action=update">
                    <div>
                        <label>Nom</label>
                        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required
                              >
                    </div>
                    
                    <div>
                        <label>Prénom</label>
                        <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required
                              >
                    </div>
                    
                    <div>
                        <label>Email</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled
                              >
                        <p>L'adresse email ne peut pas être modifiée.</p>
                    </div>

                    <button type="submit" 
                           >
                        Mettre à jour
                    </button>
                </form>

                <!-- Staff Roles Section -->
                <?php if (!empty($roles)): ?>
                    <div>
                        <h3>Mes Accès Staff</h3>
                        <div>
                            <?php foreach ($roles as $row): ?>
                                <div>
                                    <div>
                                        <p><?= htmlspecialchars($row['buvette_name']) ?></p>
                                        <p>
                                            <?= $row['role'] === 'ROLE_GESTION' ? 'Gestionnaire' : 'Barman' ?>
                                        </p>
                                    </div>
                                      >
                                        Accéder
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div>
                    <a href="index.php?page=auth&action=logout" 
                      >
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
