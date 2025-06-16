<?php

namespace App;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $database;

    private function __construct()
    {
        try {
            $config = require_once 'config/database.php';
            
            $this->database = new PDO(
                "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}",
                $config['user'],
                $config['password'],
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false
                )
            );
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->database;
    }

    private function __clone() {}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
} 