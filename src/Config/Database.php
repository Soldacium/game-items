<?php

namespace App\Config;

class Database {
    private static $instance = null;
    private $connection = null;

    private function __construct() {
        try {
            $this->connection = new \PDO(
                "pgsql:host=db;port=5432;dbname=game_items;",
                "postgres",
                "postgres",
                [
                    "sslmode" => "disable",
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_STRINGIFY_FETCHES => false
                ]
            );

            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            // Ensure proper handling of binary data
            $this->connection->exec("SET bytea_output = 'escape'");
        } catch(\PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO {
        return $this->connection;
    }
} 