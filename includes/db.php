<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

class DB {
    private static ?\PDO $instance = null;

    public static function conn(): \PDO {
        if (self::$instance === null) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->safeLoad();

            $host = $_ENV['DB_HOST'];
            $port = $_ENV['DB_PORT'];
            $db   = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];
            $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ];

            self::$instance = new \PDO($dsn, $user, $pass, $options);
        }

        return self::$instance;
    }
}
