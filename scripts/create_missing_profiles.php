<?php

require_once __DIR__ . '/../src/Config/Database.php';
require_once __DIR__ . '/../src/Model/BaseModel.php';
require_once __DIR__ . '/../src/Model/User.php';
require_once __DIR__ . '/../src/Model/Profile.php';

use App\Model\User;
use App\Model\Profile;
use App\Config\Database;

$db = Database::getInstance()->getConnection();

// user that dont have profiles
$sql = "
    SELECT u.id, u.email 
    FROM users u 
    LEFT JOIN profiles p ON u.id = p.user_id 
    WHERE p.id IS NULL
";

try {
    $stmt = $db->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "No users found without profiles.\n";
        exit(0);
    }
    
    echo "Found " . count($users) . " users without profiles.\n";
    
    $profile = new Profile();
    $created = 0;
    
    foreach ($users as $user) {
        echo "Creating profile for user {$user['email']}... ";
        
        try {
            if ($profile->create($user['id'])) {
                echo "Success!\n";
                $created++;
            } else {
                echo "Failed!\n";
                error_log("Failed to create profile for user {$user['email']} (ID: {$user['id']})");
            }
        } catch (Exception $e) {
            echo "Error!\n";
            error_log("Exception while creating profile for user {$user['email']} (ID: {$user['id']}): " . $e->getMessage());
        }
    }
    
    echo "\nCreated $created profiles successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
} 