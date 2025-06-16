<?php

namespace App\Utils;

class AssetHelper {
    public static function asset($path) {
        // Get the project root directory (where index.php is located)
        $projectRoot = dirname(dirname(__DIR__));
        
        // Get the file's last modification time
        $realPath = $projectRoot . '/' . $path;
        
        // Force browser to reload by using current timestamp
        // This ensures immediate updates during development
        $version = time();
        
        // Return the path with version parameter
        return $path . '?v=' . $version;
    }
} 