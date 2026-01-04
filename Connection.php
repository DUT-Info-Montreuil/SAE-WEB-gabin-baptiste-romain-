<?php
class Connection {
    public static $db;

    public function __construct() {
        if (self::$db === null) {
            $this->connectDatabase();
        }
    }

    protected function connectDatabase() {
        $envPath = __DIR__ . '/.env';
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#')) continue;
                $parts = explode('=', $line, 2);
                if (count($parts) === 2) {
                    $_ENV[trim($parts[0])] = trim($parts[1]);
                }
            }
        }

        try {
            $dsn = "mysql:host=" . ($_ENV['DB_HOST'] ?? 'localhost') .
                ";port=" . ($_ENV['DB_PORT'] ?? '3306') .
                ";dbname=" . ($_ENV['DB_NAME'] ?? 'sae') .
                ";charset=utf8mb4";

            self::$db = new PDO($dsn, $_ENV['DB_USER'] ?? 'root', $_ENV['DB_PASSWORD'] ?? '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }
}
?>