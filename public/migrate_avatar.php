<?php

require_once dirname(__DIR__) . '/config/config.php';

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = APP_PATH . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Database;

try {
    $db = Database::getInstance();
    
    // Add avatar column to users table if it doesn't exist
    $columns = $db->query("SHOW COLUMNS FROM users LIKE 'avatar'")->fetchAll();
    if (empty($columns)) {
        $db->query("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) DEFAULT NULL AFTER email;");
        echo "Successfully added 'avatar' column to the 'users' table!";
    } else {
        echo "Column 'avatar' already exists in the 'users' table.";
    }
} catch (Exception $e) {
    echo "Error running migration: " . $e->getMessage();
}
