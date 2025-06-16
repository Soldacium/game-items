<?php

require_once __DIR__ . '/../src/Config/Database.php';

use App\Config\Database;

try {
    $db = Database::getInstance()->getConnection();

    $schema = file_get_contents(__DIR__ . '/schema.sql');
    $result = pg_query($db, $schema);

    if ($result === false) {
        throw new Exception(pg_last_error($db));
    }

    echo "Database initialized successfully!\n";
    echo "You can now log in with:\n";
    echo "Admin: admin@demo.io / secret123\n";
    echo "User: user@demo.io / secret123\n";

} catch (Exception $e) {
    echo "Error initializing database: " . $e->getMessage() . "\n";
    exit(1);
} 