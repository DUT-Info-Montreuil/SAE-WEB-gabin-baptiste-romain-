<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>

<div>
    <h2>Inscription</h2>

    <?php if (isset($error) && $error): ?>
        <div>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success) && $success): ?>
        <div>
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?page=register" method="POST">
        <div>
            <label for="email">Adresse Email</label>
            <input type="email" id="email" name="email" required
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit">S'inscrire</button>
    </form>

    <div>
        <p>Déjà un compte ? <a href="index.php?page=login">Se connecter</a></p>
    </div>
</div>

</body>
</html>