<?php


echo "directories\n";
require_once __DIR__ . '/setup_directories.php';
echo "\n";

echo "migrations\n";
require_once __DIR__ . '/run_migrations.php';
echo "\n";

echo "profiles\n";
require_once __DIR__ . '/create_missing_profiles.php';
echo "\n"; 