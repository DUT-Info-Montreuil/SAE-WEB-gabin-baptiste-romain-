<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>

<div>
    <h2>Connexion</h2>

    <?php if (isset($error) && $error): ?>
        <div>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?page=login" method="POST">
        <div>
            <label for="email">Adresse Email</label>
            <input type="email" id="email" name="email" required
                   value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Se connecter</button>
    </form>

    <div>
        <p>Pas encore de compte ? <a href="index.php?page=register">S'inscrire</a></p>
    </div>
</div>

</body>
</html>