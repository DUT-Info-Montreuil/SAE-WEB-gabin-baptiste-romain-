<?php

class Database {
    private static ?PDO $connection = null;

    private static function loadEnv(string $path): void {
        if (!file_exists($path)) {
            throw new Exception("Le fichier .env n'existe pas au chemin : " . $path);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
            }
        }
    }

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            // Ajustement du chemin pour .env car Database.php est maintenant dans /models
            self::loadEnv(__DIR__ . '/../.env');

            try {
                $dsn = sprintf(
                    "mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4",
                    $_ENV['DB_HOST'],
                    $_ENV['DB_PORT'],
                    $_ENV['DB_NAME']
                );

                self::$connection = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
