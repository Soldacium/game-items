<?php

require_once __DIR__ . '/../src/Config/Database.php';

use App\Config\Database;

function runMigration($file) {
    $db = Database::getInstance()->getConnection();
    
    echo "Running migration: " . basename($file) . "\n";
    
    try {
        $sql = file_get_contents($file);
        $db->exec($sql);
        echo "Migration successful!\n";
    } catch (PDOException $e) {
        echo "Migration failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

$migrationFiles = glob(__DIR__ . '/migrations/*.sql');
sort($migrationFiles);

foreach ($migrationFiles as $file) {
    runMigration($file);
}

echo "\nAll migrations completed!\n"; 