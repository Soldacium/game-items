<?php

$directories = [
    __DIR__ . '/../public/uploads',
    __DIR__ . '/../public/uploads/avatars'
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        echo "Creating directory: $dir\n";
        if (mkdir($dir, 0777, true)) {
            echo "Directory created successfully!\n";
        } else {
            echo "Failed to create directory!\n";
            exit(1);
        }
    }

    echo "Setting permissions for: $dir\n";
    if (chmod($dir, 0777)) {
        echo "Permissions set successfully!\n";
    } else {
        echo "Failed to set permissions!\n";
        exit(1);
    }
}

echo "\nAll directories set up successfully!\n"; 